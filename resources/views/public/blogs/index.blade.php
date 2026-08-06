@extends('layouts.app')

@section('title', isset($category) ? $category->name : (isset($tag) ? '#'.$tag->name : 'Blogs'))

@section('content')

@push('styles') @vite(['resources/css/public/blogs.css']) @endpush

{{-- ═══ HERO ═══ --}}
<section class="blog-hero">
    <div class="blog-hero-grid"></div>
    <div class="container blog-hero-inner">

        <div class="hero-eyebrow">
            <span></span>
            @if(isset($category)) {{ $category->name }}
            @elseif(isset($tag)) Tagged Posts
            @else Community Stories
            @endif
        </div>

        <h1 class="blog-hero-title">
            @if(isset($category)) {{ $category->name }}
            @elseif(isset($tag)) #<em>{{ $tag->name }}</em>
            @else Stories &amp; <em>Perspectives</em>
            @endif
        </h1>

        <p class="blog-hero-sub">
            @if(isset($category))
                Explore all posts in <strong style="color:rgba(165,180,252,.9);font-weight:500;">{{ $category->name }}</strong> — real voices on real causes.
            @elseif(isset($tag))
                Posts tagged with <strong style="color:rgba(165,180,252,.9);font-weight:500;">#{{ $tag->name }}</strong>.
            @else
                Insights, ideas, and stories from our writers — real voices on real causes across India.
            @endif
        </p>

        <div class="blog-hero-stats">
            <div class="blog-hero-stat">
                <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                <strong>{{ number_format($blogs->total() ?? 0) }}</strong>
                <span>Posts Published</span>
            </div>
            @if(isset($categories) && $categories->isNotEmpty())
            <div class="blog-hero-stat">
                <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 01-2 2H4a2 2 0 01-2-2V5a2 2 0 012-2h5l2 3h9a2 2 0 012 2z"/></svg>
                <strong>{{ number_format($categories->count()) }}</strong>
                <span>Categories</span>
            </div>
            @endif
            @if(isset($tags) && $tags->isNotEmpty())
            <div class="blog-hero-stat">
                <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                <strong>{{ number_format($tags->count()) }}</strong>
                <span>Tags</span>
            </div>
            @endif
        </div>

    </div>
</section>

{{-- ═══ MARQUEE ═══ --}}
<div class="marquee-wrap">
    <div class="marquee-track">
        @php $items = ['Community Stories','Expert Insights','Impact Reports','Verified Writers','Fresh Perspectives','Weekly Updates','Real Stories','Donor Voices']; @endphp
        @for($r=0;$r<2;$r++)
            @foreach($items as $item)
                <span class="marquee-item"><span class="marquee-dot"></span>{{ $item }}</span>
            @endforeach
        @endfor
    </div>
</div>

{{-- ═══ FILTER BAR ═══ --}}
{{--
    NOTE: This is a GET form, so it intentionally has no @csrf — GET requests
    are not state-changing and Laravel's VerifyCsrfToken middleware does not
    check them. Do not add @csrf here; doing so would leak the token into the
    URL/query string and into browser history, referrer headers, and analytics.
    Validate `category`, `sort`, and `q` server-side in the controller
    (e.g. with a FormRequest using `in:` rules for sort/category and a max
    length + strip on `q`) — never trust these for raw SQL/LIKE building.
--}}
<div class="blog-filter-bar">
    <div class="container">
        <form method="GET" action="{{ route('blogs.index') }}" class="blog-filter-inner" role="search" aria-label="Search blog posts">
            <div class="bfb-search">
                <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                </svg>
                <input
                    type="text"
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="Search stories…"
                    maxlength="120"
                    autocomplete="off"
                    inputmode="search">
            </div>
            @if(isset($categories) && $categories->isNotEmpty())
            <select name="category" class="bfb-select">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->slug }}" @selected(request('category') === $cat->slug || (isset($category) && $category->slug === $cat->slug))>{{ $cat->name }}</option>
                @endforeach
            </select>
            @endif
            <select name="sort" class="bfb-select">
                <option value="recent"   @selected(request('sort','recent') === 'recent')>Most Recent</option>
                <option value="popular"  @selected(request('sort') === 'popular')>Most Popular</option>
                <option value="trending" @selected(request('sort') === 'trending')>Trending</option>
            </select>
            <x-button variant="primary" type="submit">Search</x-button>
            @if(request('q') || (request('sort') && request('sort') !== 'recent'))
                <a href="{{ route('blogs.index') }}" class="bfb-clear">✕ Clear</a>
            @endif
        </form>
    </div>
</div>

{{-- ═══ BODY ═══ --}}
<div class="blog-body">
    <div class="container">
        <div class="blog-layout">

            {{-- ── MAIN ── --}}
            <div class="blog-main">

                {{-- Category pills --}}
                @if(isset($categories) && $categories->isNotEmpty())
                <div class="cat-pills">
                    <a href="{{ route('blogs.index') }}"
                       class="cat-pill {{ !request('category') && !isset($category) ? 'active' : '' }}">
                        All Posts
                    </a>
                    @foreach($categories as $cat)
                    <a href="{{ route('blogs.category', $cat->slug) }}"
                       class="cat-pill {{ isset($category) && $category->slug === $cat->slug ? 'active' : '' }}">
                        {{-- Icon class is restricted to a safe fa-* whitelist pattern to prevent
                             attribute/markup injection if the icon field is ever editable by a
                             non-trusted admin or imported from an external feed. --}}
                        @if($cat->icon && preg_match('/^fa-[a-z0-9-]+$/', $cat->icon))
                            <i class="fa-solid {{ $cat->icon }}" aria-hidden="true"></i>
                        @endif
                        {{ $cat->name }}
                    </a>
                    @endforeach
                </div>
                @endif

                {{-- Featured strip --}}
                @if(!isset($tag) && !request('q') && isset($featured) && $featured->isNotEmpty())
                <div class="reveal">
                    <div class="featured-label">★ Featured Posts</div>
                    <div class="featured-strip">
                        @foreach($featured as $feat)
                        <a href="{{ route('blogs.show', $feat->slug) }}" class="featured-card">
                            @if($feat->cover_image)
                                <img
                                    src="{{ $feat->cover_image_url ?? Storage::url($feat->cover_image) }}"
                                    alt="{{ $feat->title }}"
                                    loading="lazy"
                                    referrerpolicy="no-referrer">
                            @else
                                <div class="featured-card-bg"></div>
                            @endif
                            <div class="featured-card-overlay"></div>
                            <div class="featured-card-body">
                                <div class="featured-badge">Featured</div>
                                <div class="featured-card-title">{{ $feat->title }}</div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Result count --}}
                <div class="result-count reveal">
                    <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                    {{ number_format($blogs->total()) }} {{ Str::plural('result', $blogs->total()) }}
                    @if(request('q'))<span class="rq">"{{ Str::limit(request('q'), 60) }}"</span>@endif
                </div>

                {{-- Grid --}}
                @if($blogs->isEmpty())
                <div class="blog-grid">
                    <div class="blog-empty">
                        <div class="blog-empty-icon">
                            <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                            </svg>
                        </div>
                        <div class="blog-empty-title">No posts found</div>
                        <p class="blog-empty-desc">
                            @if(request('q')) No results for "{{ Str::limit(request('q'), 60) }}". Try a different keyword.
                            @else Nothing published yet — check back soon!
                            @endif
                        </p>
                        <x-button variant="primary" href="{{ route('blogs.index') }}">
                            Browse all
                            <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </x-button>
                    </div>
                </div>

                @else
                {{--
                    SCALABILITY: this loop touches $blog->author and $blog->category for
                    every row. Make sure the controller eager-loads these
                    (Blog::with(['author:id,name,avatar', 'category:id,name,slug,icon'])->...)
                    to avoid N+1 queries — the view cannot fix that on its own.
                    Also prefer paginate()/cursorPaginate() with a sane per-page cap
                    (e.g. 12-24) rather than loading unbounded result sets.
                --}}
                <div class="blog-grid">
                    @foreach($blogs as $i => $blog)
                    @php
                        $author = $blog->author; // expected eager-loaded relation
                        $blogCategory = $blog->category;
                    @endphp
                    <article class="blog-card reveal reveal-d{{ min(($i % 6) + 1, 6) }}">
                        <div class="blog-card-bar"></div>

                        {{-- Image --}}
                        <div class="blog-card-img">
                            <a href="{{ route('blogs.show', $blog->slug) }}">
                                @if($blog->cover_image)
                                    <img
                                        src="{{ $blog->cover_image_url ?? Storage::url($blog->cover_image) }}"
                                        alt="{{ $blog->title }}"
                                        loading="lazy"
                                        referrerpolicy="no-referrer"
                                        width="280" height="192">
                                @else
                                    <div class="blog-card-img-ph">
                                        <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909"/>
                                        </svg>
                                    </div>
                                @endif
                            </a>
                            @if($blogCategory)
                            <a href="{{ route('blogs.category', $blogCategory->slug) }}" class="blog-card-cat-badge">
                                {{ $blogCategory->name }}
                            </a>
                            @endif
                            @if($blog->is_featured)
                            <span class="blog-card-featured-badge">★ Featured</span>
                            @endif
                        </div>

                        {{-- Body --}}
                        <div class="blog-card-body">
                            <div class="blog-card-read-time">
                                <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                {{ $blog->read_time_minutes ?? 1 }} min read · {{ optional($blog->published_at)->diffForHumans() ?? 'Recently' }}
                            </div>

                            <h3 class="blog-card-title">
                                <a href="{{ route('blogs.show', $blog->slug) }}">{{ $blog->title }}</a>
                            </h3>
                            <p class="blog-card-excerpt">{{ Str::limit(strip_tags($blog->excerpt ?? $blog->content ?? ''), 120) }}</p>

                            <div class="blog-card-footer">
                                <div class="blog-card-author">
                                    @if($author && $author->avatar)
                                        <img
                                            src="{{ Storage::url($author->avatar) }}"
                                            class="blog-card-avatar"
                                            alt="{{ $author->name }}"
                                            loading="lazy"
                                            referrerpolicy="no-referrer">
                                    @else
                                        <div class="blog-card-initials">{{ strtoupper(substr($author->name ?? 'A', 0, 1)) }}</div>
                                    @endif
                                    <span class="blog-card-author-name">{{ $author->name ?? 'Anonymous' }}</span>
                                </div>
                                <div class="blog-card-stats">
                                    <span class="blog-card-stat">
                                        <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                        {{ number_format($blog->views_count ?? 0) }}
                                    </span>
                                    <span class="blog-card-stat">
                                        <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
                                        {{ number_format($blog->likes_count ?? 0) }}
                                    </span>
                                    <span class="blog-card-stat">
                                        <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
                                        {{ number_format($blog->comments_count ?? 0) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </article>
                    @endforeach
                </div>
                <div class="blog-pagination">{{ $blogs->appends(request()->query())->links() }}</div>
                @endif

            </div>{{-- /main --}}

            {{-- ── SIDEBAR ── --}}
            <aside class="blog-sidebar">

                @if(isset($tags) && $tags->isNotEmpty())
                <div class="sidebar-card">
                    <div class="sidebar-label">Popular Tags</div>
                    <div class="sidebar-tags">
                        @foreach($tags as $t)
                        <a href="{{ route('blogs.tag', $t->slug) }}" class="sidebar-tag">#{{ $t->name }}</a>
                        @endforeach
                    </div>
                </div>
                @endif

                @if(isset($categories) && $categories->isNotEmpty())
                <div class="sidebar-card">
                    <div class="sidebar-label">Categories</div>
                    <ul class="sidebar-cat-list">
                        @foreach($categories as $cat)
                        <li>
                            <a href="{{ route('blogs.category', $cat->slug) }}" class="sidebar-cat-link">
                                @if($cat->icon && preg_match('/^fa-[a-z0-9-]+$/', $cat->icon))
                                    <i class="fa-solid {{ $cat->icon }}" aria-hidden="true"></i>
                                @endif
                                <span>{{ $cat->name }}</span>
                                <svg class="cat-arrow" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @auth
                <div class="sidebar-cta">
                    <div class="sidebar-cta-icon">
                        <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                        </svg>
                    </div>
                    <p>Have something to share with the community? Write your story today.</p>
                    <x-button variant="primary" href="{{ route('user.blogs.create') }}">Write a Story</x-button>
                </div>
                @endauth

            </aside>

        </div>
    </div>
</div>

{{-- ═══ SCRIPTS ═══ --}}
<script>
document.documentElement.classList.add('js-enabled');
document.addEventListener('DOMContentLoaded', function () {
    var reveals = document.querySelectorAll('.reveal');
    if (!('IntersectionObserver' in window)) {
        reveals.forEach(function (el) { el.classList.add('visible'); });
        return;
    }
    var io = new IntersectionObserver(function(entries){
        entries.forEach(function(e){
            if(e.isIntersecting){ e.target.classList.add('visible'); io.unobserve(e.target); }
        });
    }, { threshold: 0.1 });
    reveals.forEach(function(el){ io.observe(el); });
});
</script>

@endsection