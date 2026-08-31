<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBlogRequest;
use App\Http\Requests\Admin\UpdateBlogRequest;
use App\Http\Requests\Blog\BulkBlogActionRequest;
use App\Http\Requests\Blog\RejectBlogRequest;
use App\Http\Requests\Blog\ReorderBlogRequest;
use App\Models\Blog;
use App\Models\BlogReport;
use App\Models\Category;
use App\Models\Tag;
use App\Services\Blog\AdminBlogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function __construct(
        private AdminBlogService $blogService
    ) {
        $this->middleware(['auth', 'admin']);
    }

    public function index(Request $request)
    {
        $status = $request->input('status', 'all');
        $sort = $request->input('sort', 'latest');
        $search = $request->input('search', '');

        $query = Blog::with(['author', 'category:id,name']);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%")
                    ->orWhereHas('author', fn ($q2) => $q2->where('name', 'like', "%{$search}%"));
            });
        }

        match ($sort) {
            'oldest' => $query->oldest(),
            'title' => $query->orderBy('title'),
            default => $query->latest(),
        };

        $categories = Category::where('is_active', true)->orderBy('name')->get(['id', 'name']);

        return view('admin.blogs.index', [
            'blogs' => $query->paginate(15)->withQueryString(),
            'pendingCount'      => Blog::where('status', Blog::STATUS_PENDING)->count(),
            'publishedCount'    => Blog::where('status', Blog::STATUS_PUBLISHED)->count(),
            'rejectedCount'     => Blog::where('status', Blog::STATUS_REJECTED)->count(),
            'archivedCount'     => Blog::where('status', Blog::STATUS_ARCHIVED)->count(),
            'flaggedCount'      => Blog::where('status', Blog::STATUS_FLAGGED)->count(),
            'draftCount'        => Blog::where('status', Blog::STATUS_DRAFT)->count(),
            'categories'        => $categories,
            'activeSearch'      => $search,
        ]);
    }

    public function pending(Request $request)
    {
        $blogs = Blog::pending()
            ->with(['author:id,name,avatar,role', 'category:id,name'])
            ->oldest()
            ->paginate(15);

        return view('admin.blogs.pending', compact('blogs'));
    }

    public function flagged(Request $request)
    {
        $blogs = Blog::flagged()
            ->with(['author:id,name,role', 'reports.reporter'])
            ->orderByDesc('reports_count')
            ->paginate(15);

        return view('admin.blogs.flagged', compact('blogs'));
    }

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

    public function create()
    {
        $categories = Category::where('is_active', true)->get(['id', 'name']);
        $tags = Tag::orderBy('name')->get(['id', 'name']);

        $blogStats = [
            'total'     => Blog::count(),
            'published' => Blog::where('status', 'approved')->count(),
            'drafts'    => Blog::where('status', 'draft')->count(),
            'pending'   => Blog::where('status', 'pending')->count(),
        ];

        return view('admin.blogs.create', compact('categories', 'tags', 'blogStats'));
    }

    public function store(StoreBlogRequest $request)
    {
        $blog = $this->blogService->create($request, Auth::id());

        return redirect()->route('admin.blogs.show', $blog)
            ->with('success', 'Blog published successfully.');
    }

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

    public function edit(Blog $blog)
    {
        abort_if($blog->trashed(), 404);

        $categories = Category::where('is_active', true)->get(['id', 'name']);
        $tags = Tag::orderBy('name')->get(['id', 'name']);
        $selectedTags = $blog->tags->pluck('id')->toArray();

        return view('admin.blogs.edit', compact('blog', 'categories', 'tags', 'selectedTags'));
    }

    public function update(UpdateBlogRequest $request, Blog $blog)
    {
        abort_if($blog->trashed(), 404);

        $data = $request->validated();

        if (! empty($data['slug'])) {
            $data['slug'] = Str::slug($data['slug']);
        } elseif ($blog->slug) {
            $data['slug'] = $blog->slug;
        } else {
            $data['slug'] = Blog::generateUniqueSlug($data['title'], $blog->id);
        }

        if ($request->hasFile('cover_image')) {
            if ($blog->cover_image) {
                Storage::disk('public')->delete($blog->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')
                ->store('blogs/covers', 'public');
        } elseif (! empty($data['remove_cover'])) {
            if ($blog->cover_image) {
                Storage::disk('public')->delete($blog->cover_image);
            }
            $data['cover_image'] = null;
        } else {
            unset($data['cover_image']);
        }

        $data['is_featured'] = $request->boolean('is_featured');
        $data['allow_comments'] = $request->boolean('allow_comments');
        $data['is_pinned'] = $request->boolean('is_pinned');
        $data['allow_likes'] = $request->boolean('allow_likes');
        $data['show_share'] = $request->boolean('show_share');

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

        if ($request->filled('tag_ids')) {
            $blog->tags()->sync($request->input('tag_ids'));
        } elseif ($request->has('tags')) {
            $tagList = array_filter(
                array_map('trim', explode(',', $request->input('tags', '')))
            );
            if (! empty($tagList)) {
                $tagIds = Tag::whereIn('name', $tagList)->pluck('id');
                $blog->tags()->sync($tagIds);
            }
        }

        return redirect()
            ->route('admin.blogs.edit', $blog)
            ->with('success', 'Blog updated successfully.');
    }

    public function destroy(Blog $blog)
    {
        $blog->delete();

        return redirect()->route('admin.blogs.index')
            ->with('success', 'Blog soft-deleted.');
    }

    public function bulk(BulkBlogActionRequest $request)
    {
        $ids = $request->input('ids');
        $action = $request->input('action');

        if ($action === 'delete') {
            $count = Blog::whereIn('id', $ids)->delete();
            $msg = $count.' blog'.($count === 1 ? '' : 's').' deleted.';
        } else {
            $result = $this->blogService->bulkPublish($ids, Auth::id());
            $count = $result['count'];
            $msg = $count.' blog'.($count === 1 ? '' : 's').' published.';
        }

        return response()->json([
            'ok' => true,
            'done' => $count,
            'msg' => $msg,
        ]);
    }

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

    public function restore(int $id)
    {
        $blog = Blog::withTrashed()->findOrFail($id);
        $blog->restore();

        return back()->with('success', 'Blog restored.');
    }

    public function approve(Request $request, Blog $blog)
    {
        abort_unless($blog->status === Blog::STATUS_PENDING, 422, 'Only pending blogs can be approved.');

        $this->blogService->approve($blog, Auth::id(), $request->get('note'));

        if ($request->wantsJson()) {
            return response()->json([
                'ok' => true,
                'message' => "Blog \"{$blog->title}\" approved and published.",
                'status' => $blog->status,
            ]);
        }

        return back()->with('success', "Blog \"{$blog->title}\" approved and published.");
    }

    public function reject(RejectBlogRequest $request, Blog $blog)
    {
        abort_unless(
            in_array($blog->status, [Blog::STATUS_PENDING, Blog::STATUS_FLAGGED]),
            422,
            'Blog cannot be rejected in its current state.'
        );

        $this->blogService->reject($blog, Auth::id(), $request->reason);

        return back()->with('success', "Blog \"{$blog->title}\" rejected.");
    }

    public function feature(Request $request, Blog $blog)
    {
        $result = $this->blogService->toggleFeature($blog);
        $msg = $result['message'];

        if ($request->wantsJson()) {
            return response()->json([
                'ok' => true,
                'message' => $msg,
                'is_featured' => $blog->is_featured,
            ]);
        }

        return back()->with('success', $msg);
    }

    public function archive(Request $request, Blog $blog)
    {
        $this->blogService->archive($blog, Auth::id());

        if ($request->wantsJson()) {
            return response()->json([
                'ok' => true,
                'message' => "Blog \"{$blog->title}\" archived.",
                'status' => $blog->status,
            ]);
        }

        return back()->with('success', "Blog \"{$blog->title}\" archived.");
    }

    public function flag(Request $request, Blog $blog)
    {
        $this->blogService->flag($blog, Auth::id(), $request->get('note'));

        if ($request->wantsJson()) {
            return response()->json([
                'ok' => true,
                'message' => 'Blog flagged for review.',
                'status' => $blog->status,
            ]);
        }

        return back()->with('success', 'Blog flagged for review.');
    }

    public function reorder(ReorderBlogRequest $request)
    {
        DB::transaction(function () use ($request) {
            foreach ($request->validated()['order'] as $position => $blogId) {
                Blog::where('id', $blogId)->update(['carousel_order' => $position + 1]);
            }
        });

        return response()->json(['success' => true]);
    }

    public function analytics()
    {
        $stats = [
            'total' => Blog::withTrashed()->count(),
            'pending' => Blog::pending()->count(),
            'published' => Blog::published()->count(),
            'flagged' => Blog::flagged()->count(),
            'total_views' => Blog::sum('views_count'),
            'total_likes' => Blog::sum('likes_count'),
        ];

        $topBlogs = Blog::public()
            ->orderByDesc('views_count')
            ->take(10)
            ->get(['id', 'title', 'slug', 'views_count', 'likes_count']);

        $recentActivity = Blog::with('statusLogs')
            ->whereHas('statusLogs', fn ($q) => $q->where('created_at', '>=', now()->subDays(7)))
            ->take(20)
            ->get();

        return view('admin.blogs.analytics', compact('stats', 'topBlogs', 'recentActivity'));
    }

    public function dismissReport(BlogReport $report)
    {
        $report->update([
            'status' => 'dismissed',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        return back()->with('success', 'Report dismissed.');
    }
}
