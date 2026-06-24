<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogReport;
use App\Models\Category;
use App\Models\Tag;
use App\Http\Requests\BlogRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Admin\BlogController
 *
 * Full blog management: list, CRUD, approve, reject, feature, archive, carousel reorder.
 */
class BlogController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    // ── Index ─────────────────────────────────────────────────────────────────

// Replace your index() method with this:

public function index(Request $request)
{
    $status = $request->input('status', 'all');
    $sort   = $request->input('sort', 'latest');
    $search = $request->input('search', '');

    $query = Blog::with('author');

    if ($status !== 'all') {
        $query->where('status', $status);
    }

    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhereHas('author', fn($q2) =>
                  $q2->where('name', 'like', "%{$search}%")
              );
        });
    }

    match ($sort) {
        'oldest' => $query->oldest(),
        'title'  => $query->orderBy('title'),
        default  => $query->latest(),
    };

    return view('admin.blogs.index', [
        'blogs'          => $query->paginate(15)->withQueryString(),
        'pendingCount'   => Blog::where('status', Blog::STATUS_PENDING)->count(),
        'publishedCount' => Blog::where('status', Blog::STATUS_PUBLISHED)->count(), // was approvedCount
        'rejectedCount'  => Blog::where('status', Blog::STATUS_REJECTED)->count(),
    ]);
}

    // ── Pending Queue ─────────────────────────────────────────────────────────

    public function pending(Request $request)
    {
        $blogs = Blog::pending()
            ->with(['author:id,name,avatar,role', 'category:id,name'])
            ->oldest()
            ->paginate(15);

        return view('admin.blogs.pending', compact('blogs'));
    }

    // ── Flagged Blogs ─────────────────────────────────────────────────────────

    public function flagged(Request $request)
    {
        $blogs = Blog::flagged()
            ->with(['author:id,name,role', 'reports.reporter'])
            ->orderByDesc('reports_count')
            ->paginate(15);

        return view('admin.blogs.flagged', compact('blogs'));
    }

    // ── Carousel Management ───────────────────────────────────────────────────

    public function carousel()
    {
        $featured = Blog::featured()
            ->with('author:id,name')
            ->get();

        $eligible = Blog::public()
            ->where('is_featured', false)
            ->latest('published_at')
            ->take(50)
            ->get(['id', 'title', 'slug']);

        return view('admin.blogs.carousel', compact('featured', 'eligible'));
    }

    // ── CRUD: Create ──────────────────────────────────────────────────────────

    public function create()
    {
        $categories = Category::where('is_active', true)->get(['id', 'name']);
        $tags       = Tag::orderBy('name')->get(['id', 'name']);

        return view('admin.blogs.create', compact('categories', 'tags'));
    }

    public function store(BlogRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')
                ->store('blogs/covers', 'public');
        }

        $data['author_id']    = Auth::id();
        $data['author_role']  = 'admin';
        $data['status']       = Blog::STATUS_PUBLISHED;
        $data['published_at'] = now();
        $data['reviewed_by']  = Auth::id();
        $data['reviewed_at']  = now();

        unset($data['tag_ids']);

        $blog = Blog::create($data);

        if ($request->filled('tag_ids')) {
            $blog->tags()->sync($request->input('tag_ids'));
        }

        return redirect()->route('admin.blogs.show', $blog)
            ->with('success', 'Blog published successfully.');
    }

    // ── CRUD: Show ────────────────────────────────────────────────────────────

    public function show(Blog $blog)
    {
        $blog->load([
            'author',
            'category',
            'tags',
            'reviewer',
            'statusLogs.actor',
            'reports.reporter',
        ]);

        return view('admin.blogs.show', compact('blog'));
    }

    // ── CRUD: Edit ────────────────────────────────────────────────────────────

    public function edit(Blog $blog)
    {
        // Prevent editing a soft-deleted blog
        abort_if($blog->trashed(), 404);

        $categories   = Category::where('is_active', true)->get(['id', 'name']);
        $tags         = Tag::orderBy('name')->get(['id', 'name']);
        $selectedTags = $blog->tags->pluck('id')->toArray();

        return view('admin.blogs.edit', compact('blog', 'categories', 'tags', 'selectedTags'));
    }

    // ── CRUD: Update ──────────────────────────────────────────────────────────

    public function update(BlogRequest $request, Blog $blog)
    {
        // Safety: bail out if the blog has already been soft-deleted
        abort_if($blog->trashed(), 404);

        // DEBUG: remove these two Log lines once confirmed working
        Log::info('BlogController@update called', [
            'blog_id'      => $blog->id,
            'http_method'  => $request->method(),
            '_method'      => $request->input('_method'),
            'route'        => $request->route()->getName(),
        ]);

        $data = $request->validated();

        // ── 1. Slug ───────────────────────────────────────────────────────────
        if (!empty($data['slug'])) {
            $data['slug'] = Str::slug($data['slug']);
        } elseif ($blog->slug) {
            $data['slug'] = $blog->slug;
        } else {
            $data['slug'] = Blog::generateUniqueSlug($data['title'], $blog->id);
        }

        // ── 2. Cover image ────────────────────────────────────────────────────
        if ($request->hasFile('cover_image')) {
            // Replace existing cover
            if ($blog->cover_image) {
                Storage::disk('public')->delete($blog->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')
                ->store('blogs/covers', 'public');
        } elseif (!empty($data['remove_cover'])) {
            // Explicit remove
            if ($blog->cover_image) {
                Storage::disk('public')->delete($blog->cover_image);
            }
            $data['cover_image'] = null;
        } else {
            // No change — keep existing path
            unset($data['cover_image']);
        }

        // ── 3. Booleans (unchecked checkboxes are not sent by browsers) ───────
        $data['is_featured']    = $request->boolean('is_featured');
        $data['allow_comments'] = $request->boolean('allow_comments');
        $data['is_pinned']      = $request->boolean('is_pinned');
        $data['allow_likes']    = $request->boolean('allow_likes');
        $data['show_share']     = $request->boolean('show_share');

        // ── 4. Whitelist — only update columns that belong to this form ───────
        // 'status' is deliberately excluded; use approve/reject/archive actions
        // for workflow transitions instead.
        $allowed = [
            'title',
            'slug',
            'excerpt',
            'content',
            'cover_image',
            'category_id',
            'read_time_minutes',
            'is_featured',
            'allow_comments',
            'is_pinned',
            'allow_likes',
            'show_share',
            'meta_title',
            'meta_description',
        ];

        $updateData = array_intersect_key($data, array_flip($allowed));

        $blog->update($updateData);

        // ── 5. Tags ───────────────────────────────────────────────────────────
        if ($request->filled('tag_ids')) {
            // Multiselect array of IDs
            $blog->tags()->sync($request->input('tag_ids'));
        } elseif ($request->has('tags')) {
            // Comma-separated chip string of tag names
            $tagList = array_filter(
                array_map('trim', explode(',', $request->input('tags', '')))
            );
            if (!empty($tagList)) {
                $tagIds = Tag::whereIn('name', $tagList)->pluck('id');
                $blog->tags()->sync($tagIds);
            }
        }

        return redirect()
            ->route('admin.blogs.edit', $blog)
            ->with('success', 'Blog updated successfully.');
    }

    // ── CRUD: Destroy (Soft Delete) ───────────────────────────────────────────

    public function destroy(Blog $blog)
    {
        // DEBUG: remove once soft-delete issue is confirmed resolved
        Log::info('BlogController@destroy called', [
            'blog_id' => $blog->id,
            'trace'   => collect(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 6))
                            ->pluck('function')
                            ->implode(' → '),
        ]);

        $blog->delete();

        return redirect()->route('admin.blogs.index')
            ->with('success', 'Blog soft-deleted.');
    }

    // ── CRUD: Force Delete ────────────────────────────────────────────────────

    public function forceDestroy(int $id)
    {
        $blog = Blog::withTrashed()->findOrFail($id);

        if ($blog->cover_image) {
            Storage::disk('public')->delete($blog->cover_image);
        }

        $blog->tags()->detach();
        $blog->forceDelete();

        return redirect()->route('admin.blogs.index')
            ->with('success', 'Blog permanently deleted.');
    }

    // ── CRUD: Restore ─────────────────────────────────────────────────────────

    public function restore(int $id)
    {
        $blog = Blog::withTrashed()->findOrFail($id);
        $blog->restore();

        return back()->with('success', 'Blog restored.');
    }

    // ── Workflow: Approve ─────────────────────────────────────────────────────

    public function approve(Request $request, Blog $blog)
    {
        abort_unless($blog->status === Blog::STATUS_PENDING, 422, 'Only pending blogs can be approved.');

        $blog->transitionTo(Blog::STATUS_PUBLISHED, Auth::id(), $request->get('note'));

        return back()->with('success', "Blog \"{$blog->title}\" approved and published.");
    }

    // ── Workflow: Reject ──────────────────────────────────────────────────────

    public function reject(Request $request, Blog $blog)
    {
        $request->validate(['reason' => 'required|string|max:1000']);

        abort_unless(
            in_array($blog->status, [Blog::STATUS_PENDING, Blog::STATUS_FLAGGED]),
            422,
            'Blog cannot be rejected in its current state.'
        );

        $blog->transitionTo(Blog::STATUS_REJECTED, Auth::id(), $request->reason);

        return back()->with('success', "Blog \"{$blog->title}\" rejected.");
    }

    // ── Workflow: Feature / Unfeature ─────────────────────────────────────────

    public function feature(Blog $blog)
    {
        abort_unless($blog->is_publicly_visible, 422, 'Only visible blogs can be featured.');

        if ($blog->is_featured) {
            $blog->update(['is_featured' => false, 'featured_at' => null]);
            $msg = 'Blog removed from carousel.';
        } else {
            $maxOrder = Blog::where('is_featured', true)->max('carousel_order') ?? 0;
            $blog->update([
                'is_featured'    => true,
                'carousel_order' => $maxOrder + 1,
                'featured_at'    => now(),
            ]);
            $msg = 'Blog added to carousel.';
        }

        return back()->with('success', $msg);
    }

    // ── Workflow: Archive ─────────────────────────────────────────────────────

    public function archive(Blog $blog)
    {
        $blog->transitionTo(Blog::STATUS_ARCHIVED, Auth::id(), 'Archived by admin.');

        return back()->with('success', "Blog \"{$blog->title}\" archived.");
    }

    // ── Workflow: Flag ────────────────────────────────────────────────────────

    public function flag(Request $request, Blog $blog)
    {
        $blog->transitionTo(
            Blog::STATUS_FLAGGED,
            Auth::id(),
            $request->get('note', 'Manually flagged by admin.')
        );

        return back()->with('success', 'Blog flagged for review.');
    }

    // ── AJAX: Carousel Reorder ────────────────────────────────────────────────

    public function reorder(Request $request)
    {
        $request->validate([
            'order'   => 'required|array',
            'order.*' => 'integer|exists:blogs,id',
        ]);

        DB::transaction(function () use ($request) {
            foreach ($request->order as $position => $blogId) {
                Blog::where('id', $blogId)->update(['carousel_order' => $position + 1]);
            }
        });

        return response()->json(['success' => true]);
    }

    // ── Analytics Overview ────────────────────────────────────────────────────

    public function analytics()
    {
        $stats = [
            'total'       => Blog::withTrashed()->count(),
            'pending'     => Blog::pending()->count(),
            'published'   => Blog::published()->count(),
            'flagged'     => Blog::flagged()->count(),
            'total_views' => Blog::sum('views_count'),
            'total_likes' => Blog::sum('likes_count'),
        ];

        $topBlogs = Blog::public()
            ->orderByDesc('views_count')
            ->take(10)
            ->get(['id', 'title', 'slug', 'views_count', 'likes_count']);

        $recentActivity = Blog::with('statusLogs')
            ->whereHas('statusLogs', fn($q) => $q->where('created_at', '>=', now()->subDays(7)))
            ->take(20)
            ->get();

        return view('admin.blogs.analytics', compact('stats', 'topBlogs', 'recentActivity'));
    }

    // ── Dismiss a Report ──────────────────────────────────────────────────────

    public function dismissReport(BlogReport $report)
    {
        $report->update([
            'status'      => 'dismissed',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        return back()->with('success', 'Report dismissed.');
    }
}