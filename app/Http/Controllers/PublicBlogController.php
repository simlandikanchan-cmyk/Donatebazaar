<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\BlogReport;
use App\Models\Category;
use App\Models\Tag;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

use App\Actions\Blog\ToggleBlogLikeAction;
use App\Actions\Blog\RecordBlogViewAction;

use App\Http\Requests\Blog\StoreBlogCommentRequest;
use App\Http\Requests\Blog\ReportBlogRequest;

class PublicBlogController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Index
    |--------------------------------------------------------------------------
    */

    public function index(Request $request): View
    {
        $query = Blog::public()
            ->with([
                'author:id,name,avatar',
                'category:id,name,slug',
                'tags:id,name,slug',
            ])
            ->withCount([
                'comments',
                'likes',
            ])
            ->latest('published_at');

        // Search
        if ($search = $request->get('q')) {
            $query->search($search);
        }

        // Category
        if ($category = $request->get('category')) {
            $query->byCategory($category);
        }

        // Tag
        if ($tag = $request->get('tag')) {
            $query->byTag($tag);
        }

        // Sort
        match ($request->get('sort', 'recent')) {

            'popular' =>
                $query->popular(),

            'trending' =>
                $query->trending(),

            default =>
                $query->recent(),
        };

        $blogs = $query
            ->paginate(12)
            ->withQueryString();

        $categories = Category::where('is_active', true)
            ->get([
                'id',
                'name',
                'slug',
                'icon'
            ]);

        $tags = Tag::has('blogs')
            ->take(20)
            ->get([
                'id',
                'name',
                'slug'
            ]);

        $featured = Blog::public()
            ->featured()
            ->take(6)
            ->get();

        return view(
            'public.blogs.index',
            compact(
                'blogs',
                'categories',
                'tags',
                'featured'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Show
    |--------------------------------------------------------------------------
    */

    public function show(
        string $slug,
        RecordBlogViewAction $recordView
    ): View {

        $blog = Blog::public()
            ->with([
                'author:id,name,avatar,bio,role',
                'category:id,name,slug',
                'tags:id,name,slug',
                'comments.author:id,name,avatar',
                'comments.replies.author:id,name,avatar',
                'reviewer:id,name',
            ])
            ->where('slug', $slug)
            ->firstOrFail();

        // Record view
        $recordView->execute(
            $blog,
            Auth::id(),
            request()->ip()
        );

        // Related
        $related = Blog::public()
            ->where('id', '!=', $blog->id)

            ->where(function ($q) use ($blog) {

                $q->where(
                    'category_id',
                    $blog->category_id
                )

                ->orWhereHas(
                    'tags',
                    function ($t) use ($blog) {

                        $t->whereIn(
                            'tags.id',
                            $blog->tags->pluck('id')
                        );
                    }
                );
            })

            ->latest('published_at')
            ->take(3)
            ->get();

        $isLiked = Auth::check()
            && $blog->isLikedBy(Auth::id());

        return view(
            'public.blogs.show',
            compact(
                'blog',
                'related',
                'isLiked'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Category
    |--------------------------------------------------------------------------
    */

    public function byCategory(string $slug): View
    {
        $category = Category::where(
            'slug',
            $slug
        )->firstOrFail();

        $blogs = Blog::public()

            ->where(
                'category_id',
                $category->id
            )

            ->with([
                'author:id,name,avatar',
                'category:id,name,slug',
            ])

            ->latest('published_at')
            ->paginate(12);

        return view(
            'public.blogs.index',
            compact('blogs', 'category')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Tag
    |--------------------------------------------------------------------------
    */

    public function byTag(string $slug): View
    {
        $tag = Tag::where(
            'slug',
            $slug
        )->firstOrFail();

        $blogs = Blog::public()

            ->byTag($slug)

            ->with([
                'author:id,name,avatar',
                'category:id,name,slug',
            ])

            ->latest('published_at')
            ->paginate(12);

        return view(
            'public.blogs.index',
            compact('blogs', 'tag')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Toggle Like
    |--------------------------------------------------------------------------
    */

    public function toggleLike(
        Blog $blog,
        ToggleBlogLikeAction $action
    ): JsonResponse|RedirectResponse {

        abort_unless(
            $blog->is_publicly_visible,
            404
        );

        $liked = $action->execute(
            $blog,
            Auth::id()
        );

        if (request()->wantsJson()) {

            return response()->json([
                'liked' => $liked,
                'likes_count' => $blog
                    ->fresh()
                    ->likes_count,
            ]);
        }

        return back()->with(
            'success',
            $liked
                ? 'Blog liked!'
                : 'Like removed.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Comment
    |--------------------------------------------------------------------------
    */

    // public function comment(
    //     StoreBlogCommentRequest $request,
    //     Blog $blog
    // ): RedirectResponse {

    //     abort_unless(
    //         $blog->is_publicly_visible,
    //         404
    //     );

    //     $blog->allComments()->create([
    //         'user_id' => Auth::id(),
    //         'parent_id' => $request->parent_id,
    //         'content' => $request->content,
    //         'is_approved' => true,
    //     ]);

    //     return back()->with(
    //         'success',
    //         'Comment posted successfully.'
    //     );
    // }

    public function comment(
    StoreBlogCommentRequest $request,
    Blog $blog
): RedirectResponse {

    abort_unless(
        $blog->is_publicly_visible,
        404
    );

    $blog->allComments()->create([
        'user_id' => Auth::id(),
        'parent_id' => $request->parent_id,
        'content' => $request->content,
        'is_approved' => true,
    ]);

    $blog->increment('comments_count');

    return back()->with(
        'success',
        'Comment posted successfully.'
    );
}

    /*
    |--------------------------------------------------------------------------
    | Report
    |--------------------------------------------------------------------------
    */

    public function report(
        ReportBlogRequest $request,
        Blog $blog
    ): RedirectResponse {

        abort_unless(
            $blog->is_publicly_visible,
            404
        );

        $alreadyReported = BlogReport::where(
            'blog_id',
            $blog->id
        )

        ->where(
            'reported_by',
            Auth::id()
        )

        ->exists();

        if ($alreadyReported) {

            return back()->with(
                'error',
                'You already reported this blog.'
            );
        }

        BlogReport::create([
            'blog_id' => $blog->id,
            'reported_by' => Auth::id(),
            'reason' => $request->reason,
            'note' => $request->note,
        ]);

        $blog->increment('reports_count');

        return back()->with(
            'success',
            'Report submitted successfully.'
        );
    }
}