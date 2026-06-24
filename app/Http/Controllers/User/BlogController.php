<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

use App\Models\Blog;
use App\Models\Category;
use App\Models\Tag;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

use App\Services\Blog\BlogService;

use App\Http\Requests\Blog\StoreBlogRequest;
use App\Http\Requests\Blog\UpdateBlogRequest;

class BlogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /*
    |--------------------------------------------------------------------------
    | Index
    |--------------------------------------------------------------------------
    */

    public function index(Request $request): View
    {
        $query = Blog::byAuthor(Auth::id())
            ->with([
                'category:id,name,slug',
                'tags:id,name,slug'
            ])
            ->latest();

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $blogs = $query
            ->paginate(15)
            ->withQueryString();

        $statusCounts = Blog::byAuthor(Auth::id())
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        return view(
            'user.blogs.index',
            compact('blogs', 'statusCounts')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */

    public function create(): View
    {
        $categories = Category::where('is_active', true)
            ->get(['id', 'name']);

        $tags = Tag::orderBy('name')
            ->get(['id', 'name']);

        return view(
            'user.blogs.create',
            compact('categories', 'tags')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Store
    |--------------------------------------------------------------------------
    */

    public function store(
        StoreBlogRequest $request,
        BlogService $blogService
    ): RedirectResponse {

        $blog = $blogService->create(
            $request->validated(),
            Auth::id(),
            $request
        );

        return redirect()
            ->route('user.blogs.index')
            ->with(
                'success',
                $this->successMessage($blog->status)
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Show
    |--------------------------------------------------------------------------
    */

    public function show(Blog $blog): View
    {
        $this->authorize('view', $blog);

        $blog->load([
            'category',
            'tags',
            'statusLogs.actor'
        ]);

        return view(
            'user.blogs.show',
            compact('blog')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Edit
    |--------------------------------------------------------------------------
    */

    public function edit(Blog $blog): View
    {
        $this->authorize('update', $blog);

        abort_unless(
            $blog->is_editable,
            403,
            'This blog cannot be edited.'
        );

        $categories = Category::where('is_active', true)
            ->get(['id', 'name']);

        $tags = Tag::orderBy('name')
            ->get(['id', 'name']);

        $blog->load('tags');

        $selectedTags = $blog->tags
            ->pluck('id')
            ->toArray();

        return view(
            'user.blogs.edit',
            compact(
                'blog',
                'categories',
                'tags',
                'selectedTags'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

    public function update(
        UpdateBlogRequest $request,
        Blog $blog,
        BlogService $blogService
    ): RedirectResponse {

        $this->authorize('update', $blog);

        abort_unless(
            $blog->is_editable,
            403,
            'This blog cannot be edited.'
        );

        $blogService->update(
            $blog,
            $request->validated(),
            $request
        );

        return redirect()
            ->route('user.blogs.show', $blog)
            ->with(
                'success',
                'Blog updated successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Delete
    |--------------------------------------------------------------------------
    */

    public function destroy(Blog $blog): RedirectResponse
    {
        $this->authorize('delete', $blog);

        $blog->delete();

        return redirect()
            ->route('user.blogs.index')
            ->with(
                'success',
                'Blog deleted successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Submit
    |--------------------------------------------------------------------------
    */

    public function submit(
        Blog $blog,
        BlogService $blogService
    ): RedirectResponse {

        $this->authorize('submit', $blog);

        $blogService->submit(
            $blog,
            Auth::user()
        );

        return back()->with(
            'success',
            'Blog submitted successfully.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Restore
    |--------------------------------------------------------------------------
    */

    public function restore(int $id): RedirectResponse
    {
        $blog = Blog::withTrashed()
            ->where('id', $id)
            ->where('author_id', Auth::id())
            ->firstOrFail();

        $blog->restore();

        return redirect()
            ->route('user.blogs.index')
            ->with(
                'success',
                'Blog restored successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    private function successMessage(string $status): string
    {
        return match ($status) {

            Blog::STATUS_PENDING =>
                'Blog submitted for review!',

            Blog::STATUS_PUBLISHED =>
                'Blog published successfully!',

            default =>
                'Blog saved as draft.',
        };
    }
}