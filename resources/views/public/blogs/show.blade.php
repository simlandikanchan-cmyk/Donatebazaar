{{-- resources/views/public/blogs/show.blade.php --}}
@extends('layouts.app')

@section('title', $blog->title)

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
    body { font-family: 'Outfit', sans-serif; }
    .font-display { font-family: 'DM Mono', monospace; }

    /* ── Reading progress bar ── */
    #reading-progress-bar {
        position: fixed;
        top: 0; left: 0;
        height: 3px;
        width: 0%;
        background: linear-gradient(to right, #f59e0b, #ef4444);
        z-index: 9999;
        transition: width 0.1s linear;
        border-radius: 0 2px 2px 0;
    }

    /* ── Prose ── */
    .prose-custom { font-family: 'DM Mono', monospace; font-size: 1.0625rem; line-height: 1.9; color: #292524; }

    .prose-custom h2,
    .prose-custom h3,
    .prose-custom h4 {
        font-family: 'DM Mono', monospace;
        font-weight: 700;
        margin-top: 2.2em;
        margin-bottom: 0.6em;
        color: #0c0a09;
        scroll-margin-top: 80px;
    }

    .prose-custom h2 { font-size: 1.5rem; }
    .prose-custom h3 { font-size: 1.2rem; }
    .prose-custom p { margin-bottom: 1.5em; }
    .prose-custom a { color: #065ad9; text-decoration: underline; text-underline-offset: 3px; }
    .prose-custom blockquote { border-left: 3px solid #f59e0b; padding: 14px 22px; margin: 32px 0; background: #fffbeb; border-radius: 0 10px 10px 0; font-style: italic; color: #57534e; font-size: 1.05rem; }
    .prose-custom ul, .prose-custom ol { padding-left: 1.5rem; margin-bottom: 1.5em; }
    .prose-custom li { margin-bottom: 0.5em; }
    .prose-custom img { max-width: 100%; border-radius: 14px; margin: 28px 0; box-shadow: 0 4px 24px rgba(0,0,0,0.08); }
    .prose-custom code { background: #f5f5f4; padding: 2px 7px; border-radius: 5px; font-size: 0.875em; font-family: 'Courier New', monospace; color: #b91c1c; }
    .prose-custom pre { background: #1c1917; color: #e7e5e4; padding: 22px 26px; border-radius: 14px; overflow-x: auto; margin: 30px 0; }
    .prose-custom pre code { background: none; padding: 0; color: inherit; }
    .prose-custom strong { font-weight: 700; color: #0c0a09; }
    .prose-custom hr { border: none; border-top: 1px solid #e7e5e4; margin: 2.5em 0; }

    /* ── TOC ── */
    #toc-nav a { display: block; padding: 5px 10px; font-size: 0.8rem; border-left: 2px solid transparent; color: #78716c; text-decoration: none; transition: all 0.2s; border-radius: 0 6px 6px 0; line-height: 1.4; }
    #toc-nav a:hover { color: #d97706; border-left-color: #fde68a; background: #fffbeb; }
    #toc-nav a.toc-active { color: #d97706; border-left-color: #f59e0b; background: #fffbeb; font-weight: 500; }

    /* ── Floating like ── */
    #float-like { transition: transform 0.15s, box-shadow 0.15s; }
    #float-like:hover { transform: scale(1.08); }
    #float-like.liked { background: #fff1f2; border-color: #fda4af !important; color: #e11d48 !important; }

    /* ── Comment box focus ── */
    #comment-body:focus { border-color: #f59e0b; box-shadow: 0 0 0 3px rgba(245,158,11,0.12); }
    #reply-input:focus { border-color: #f59e0b; box-shadow: 0 0 0 3px rgba(245,158,11,0.12); }

    /* ── Animations ── */
    @keyframes fadeUp { from { opacity:0; transform:translateY(14px); } to { opacity:1; transform:translateY(0); } }
    .animate-up { animation: fadeUp 0.45s ease both; }
    .animate-up-2 { animation: fadeUp 0.45s 0.1s ease both; }
    .animate-up-3 { animation: fadeUp 0.45s 0.2s ease both; }

    /* ── Misc ── */
    .line-clamp-2 { display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; }
    .line-clamp-3 { display:-webkit-box; -webkit-line-clamp:3; -webkit-box-orient:vertical; overflow:hidden; }
</style>
@endpush

@section('content')

{{-- ── READING PROGRESS BAR ── --}}
<div id="reading-progress-bar"></div>

{{-- ── COVER ── --}}
<div class="relative w-full overflow-hidden" style="max-height:520px; min-height:200px;">
    @if($blog->cover_image)
    <img src="{{ $blog->cover_image_url ?? Storage::url($blog->cover_image) }}"
         class="w-full object-cover"
         style="max-height:520px; min-height:200px;"
         alt="{{ $blog->title }}">
    <div class="absolute inset-0" style="background: linear-gradient(to top, rgba(12,10,9,0.82) 0%, rgba(12,10,9,0.2) 55%, transparent 100%)"></div>
    @else
    <div class="w-full flex items-end" style="min-height:220px; background: linear-gradient(135deg,#1c1917 0%,#292524 60%,#3d2b1a 100%);">
        <div class="absolute inset-0 opacity-5" style="background-image: radial-gradient(circle at 20% 30%, white 1px, transparent 1px), radial-gradient(circle at 80% 70%, white 1px, transparent 1px); background-size: 40px 40px;"></div>
    </div>
    @endif

    {{-- Overlay text on cover --}}
    <div class="absolute bottom-0 left-0 right-0 px-6 pb-8 pt-16">
        <div class="max-w-4xl mx-auto">
            @if($blog->category)
            <a href="{{ route('blogs.category', $blog->category->slug) }}"
               class="inline-block text-xs font-semibold text-amber-400 uppercase tracking-widest mb-3 hover:text-amber-300 transition-colors">
                {{ $blog->category->name }}
            </a>
            @endif
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold text-white leading-tight mb-4 animate-up"
                style="font-family:'DM Mono', monospace;">
                {{ $blog->title }}
            </h1>
            {{-- Meta row inside cover --}}
            <div class="flex flex-wrap items-center gap-4 text-sm text-stone-300 animate-up-2">
                <div class="flex items-center gap-2.5">
                    @if($blog->author->avatar ?? false)
                        <img src="{{ Storage::url($blog->author->avatar) }}"
                             class="w-8 h-8 rounded-full object-cover ring-2 ring-white/30" alt="{{ $blog->author->name }}">
                    @else
                        <div class="w-8 h-8 rounded-full bg-amber-400 text-stone-900 flex items-center justify-center font-bold text-sm ring-2 ring-white/20">
                            {{ strtoupper(substr($blog->author->name, 0, 1)) }}
                        </div>
                    @endif
                    <span class="font-medium text-white">{{ $blog->author->name }}</span>
                </div>
                <span class="text-stone-400">·</span>
                <span>{{ ($blog->published_at ?? $blog->created_at)->format('M d, Y') }}</span>
                <span class="text-stone-400">·</span>
                <span>{{ $blog->read_time_minutes ?? 1 }} min read</span>
                <span class="text-stone-400">·</span>
                <span>{{ number_format($blog->views_count ?? 0) }} views</span>
            </div>
        </div>
    </div>
</div>

{{-- ── MAIN LAYOUT ── --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 py-10">
    <div class="flex flex-col lg:flex-row gap-10 xl:gap-16">

        {{-- ── ARTICLE ── --}}
        <article class="flex-1 min-w-0 max-w-3xl animate-up-3">

            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-2 text-xs text-stone-400 mb-8">
                <a href="{{ route('blogs.index') }}" class="hover:text-amber-600 transition-colors">Blogs</a>
                @if($blog->category)
                <span class="text-stone-300">/</span>
                <a href="{{ route('blogs.category', $blog->category->slug) }}" class="hover:text-amber-600 transition-colors">
                    {{ $blog->category->name }}
                </a>
                @endif
                <span class="text-stone-300">/</span>
                <span class="text-stone-500 truncate max-w-xs">{{ $blog->title }}</span>
            </nav>

            {{-- Stats row (mobile friendly) — all figures sourced from cached counter columns on $blog for consistency with the action bar below --}}
            <div class="flex flex-wrap items-center gap-4 pb-6 border-b border-stone-100 mb-8 text-sm text-stone-500">
                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    {{ $blog->read_time_minutes ?? 1 }} min read
                </span>
                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    {{ number_format($blog->views_count ?? 0) }} views
                </span>
                <span class="flex items-center gap-1.5">
                    <span class="text-rose-400">♥</span> {{ number_format($blog->likes_count ?? 0) }}
                </span>
                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    {{ number_format($blog->comments_count ?? 0) }}
                </span>
            </div>

            {{-- ── CONTENT ── --}}
            <div class="prose-custom max-w-none mb-10" id="article-body">
                {!! $blog->content !!}
            </div>

            {{-- Tags --}}
            @if($blog->tags->isNotEmpty())
            <div class="flex flex-wrap gap-2 mb-8">
                @foreach($blog->tags as $tag)
                <a href="{{ route('blogs.tag', $tag->slug) }}"
                   class="px-3 py-1.5 bg-stone-100 text-stone-600 text-xs font-medium rounded-full hover:bg-amber-100 hover:text-amber-700 transition-colors border border-transparent hover:border-amber-200">
                    #{{ $tag->name }}
                </a>
                @endforeach
            </div>
            @endif

            {{-- ── ACTION BAR ── --}}
            <div class="flex flex-wrap items-center gap-3 py-5 border-y border-stone-200 mb-10">
                {{-- Like --}}
                @auth
                <form method="POST" action="{{ route('blogs.like', $blog) }}" id="like-form">
                    @csrf
                    <button type="submit" id="like-btn"
                        class="flex items-center gap-2 px-4 py-2 rounded-full border transition-all text-sm font-medium
                               {{ $isLiked ? 'border-rose-300 bg-rose-50 text-rose-600' : 'border-stone-200 text-stone-600 hover:border-rose-300 hover:bg-rose-50 hover:text-rose-500' }}">
                        <span id="heart-icon" class="text-base">{{ $isLiked ? '♥' : '♡' }}</span>
                        <span id="like-count">{{ number_format($blog->likes_count ?? 0) }}</span>
                        <span>{{ $isLiked ? 'Liked' : 'Like' }}</span>
                    </button>
                </form>
                @else
                <a href="{{ route('login') }}"
                   class="flex items-center gap-2 px-4 py-2 rounded-full border border-stone-200 text-stone-500 text-sm hover:border-rose-300 hover:text-rose-500 transition-colors">
                    ♡ Like
                </a>
                @endauth

                {{-- Copy link --}}
                <button onclick="copyLink()"
                    class="flex items-center gap-2 px-4 py-2 rounded-full border border-stone-200 text-stone-600 text-sm hover:border-stone-400 hover:bg-stone-50 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                    </svg>
                    <span id="copy-label">Copy Link</span>
                </button>

                {{-- Tweet --}}
                <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($blog->title) }}"
                   target="_blank" rel="noopener"
                   class="flex items-center gap-2 px-4 py-2 rounded-full border border-stone-200 text-stone-600 text-sm hover:border-sky-400 hover:text-sky-500 hover:bg-sky-50 transition-colors">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.744l7.737-8.835L1.254 2.25H8.08l4.253 5.622zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                    </svg>
                    Share
                </a>

                {{-- Report --}}
                @auth
                <button onclick="document.getElementById('report-modal').classList.remove('hidden')"
                    class="ml-auto text-xs text-stone-400 hover:text-rose-500 transition-colors underline underline-offset-2">
                    Report post
                </button>
                @endauth
            </div>

            {{-- ── AUTHOR CARD ── --}}
            <div class="flex gap-5 bg-gradient-to-br from-stone-50 to-amber-50/40 rounded-2xl p-6 mb-12 border border-stone-100">
                @if($blog->author->avatar ?? false)
                    <img src="{{ Storage::url($blog->author->avatar) }}"
                         class="w-16 h-16 rounded-full object-cover ring-4 ring-white shadow-md flex-shrink-0" alt="{{ $blog->author->name }}">
                @else
                    <div class="w-16 h-16 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center font-display font-bold text-2xl flex-shrink-0 ring-4 ring-white shadow-md">
                        {{ strtoupper(substr($blog->author->name, 0, 1)) }}
                    </div>
                @endif
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-stone-900 text-base mb-1">{{ $blog->author->name }}</p>
                    <p class="text-sm text-stone-500 leading-relaxed mb-3">
                        {{ $blog->author->bio ?? 'Writer and contributor at our community.' }}
                    </p>
                    {{-- Uses the author's own social link if the column exists; falls back to the author profile route, never a generic Instagram homepage --}}
                    <a href="{{ $blog->author->instagram_url ?? route('blogs.index', ['author' => $blog->author->id]) }}"
                       class="inline-flex items-center gap-1.5 text-xs font-medium text-stone-700 border border-stone-300 px-3 py-1.5 rounded-full hover:bg-stone-900 hover:text-white hover:border-stone-900 transition-all">
                        + Follow
                    </a>
                </div>
            </div>

            {{-- ── COMMENTS ── --}}
            <section id="comments">
                <h2 class="text-2xl font-bold text-stone-900 mb-6 flex items-center gap-3"
                    style="font-family:'DM Mono', monospace;">
                    {{ $blog->comments_count ?? 0 }}
                    {{ Str::plural('Comment', $blog->comments_count ?? 0) }}
                    <span class="text-sm font-sans font-normal text-stone-400 ml-1">
                        Join the discussion
                    </span>
                </h2>

                {{-- Post comment --}}
                @auth
                <div class="bg-white rounded-2xl border border-stone-200 p-5 mb-8 shadow-sm">
                    <div class="flex gap-3 items-start">
                        @if(auth()->user()->avatar ?? false)
                            <img src="{{ Storage::url(auth()->user()->avatar) }}"
                                 class="w-9 h-9 rounded-full object-cover flex-shrink-0 mt-0.5" alt="{{ auth()->user()->name }}">
                        @else
                            <div class="w-9 h-9 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center font-bold text-sm flex-shrink-0 mt-0.5">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                        @endif
                        <div class="flex-1">
                            <form method="POST" action="{{ route('blogs.comment', $blog) }}">
                                @csrf
                                <input type="hidden" name="parent_id" value="">
                                <textarea id="comment-body" name="content" rows="3"
                                    placeholder="Share your thoughts with us"
                                    class="w-full border border-stone-200 rounded-xl p-3.5 text-sm bg-stone-50 focus:outline-none focus:bg-white resize-none transition-all @error('content') border-rose-400 @enderror">{{ old('content') }}</textarea>
                                @error('content')
                                    <p class="text-rose-500 text-xs mt-1.5">{{ $message }}</p>
                                @enderror
                                <div class="flex items-center justify-between mt-3">
                                    <p class="text-xs text-stone-400">Be kind and constructive</p>
                                    <button type="submit"
                                        class="mt-5 md:mt-0 text-white text-sm font-semibold rounded-xl transition-all hover:scale-105"
                                        style="
                                            padding:11px 18px;
                                            font-family:'DM Sans', sans-serif;
                                            background: linear-gradient(135deg, #6366f1, #8b5cf6);
                                            box-shadow: 0 4px 14px rgba(99,102,241,.4);
                                        ">
                                        Post Comment
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @else
                <div class="text-center py-10 bg-stone-50 rounded-2xl border border-stone-100 mb-8">
                    <p class="text-stone-500 text-sm mb-3">Join the conversation</p>
                    <a href="{{ route('login') }}"
                       class="inline-flex items-center gap-2 px-5 py-2.5 bg-stone-900 text-white text-sm font-medium rounded-xl hover:bg-amber-500 hover:text-stone-900 transition-colors">
                        Login to comment
                    </a>
                </div>
                @endauth

                {{-- Success message --}}
                @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 text-sm rounded-xl px-4 py-3 mb-6 flex items-center gap-2">
                    <span>✓</span> {{ session('success') }}
                </div>
                @endif

                {{-- Comment list --}}
                <div class="space-y-6">
                @forelse($blog->comments->whereNull('parent_id') as $comment)
                <div class="flex gap-3">
                    {{-- Avatar --}}
                    @if($comment->author->avatar ?? false)
                        <img src="{{ Storage::url($comment->author->avatar) }}"
                             class="w-9 h-9 rounded-full object-cover flex-shrink-0 mt-0.5" alt="{{ $comment->author->name }}">
                    @else
                        <div class="w-9 h-9 rounded-full bg-stone-100 text-stone-500 flex items-center justify-center font-bold text-sm flex-shrink-0 mt-0.5 border border-stone-200">
                            {{ strtoupper(substr($comment->author->name ?? '?', 0, 1)) }}
                        </div>
                    @endif

                    <div class="flex-1 min-w-0">
                        <div class="bg-stone-50 rounded-2xl rounded-tl-sm px-4 py-3.5 border border-stone-100">
                            <div class="flex items-center gap-2 mb-1.5">
                                <span class="font-semibold text-stone-800 text-sm">{{ $comment->author->name }}</span>
                                @if($comment->author_id === $blog->author_id)
                                <span class="px-2 py-0.5 bg-amber-100 text-amber-700 text-xs font-medium rounded-full border border-amber-200">Author</span>
                                @endif
                                <span class="text-xs text-stone-400 ml-auto">{{ $comment->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-stone-700 text-sm leading-relaxed">{{ $comment->content }}</p>
                        </div>

                        {{-- Comment actions --}}
                        <div class="flex items-center gap-4 mt-1.5 ml-1.5">
                            @auth
                            <button onclick="toggleReply({{ $comment->id }})"
                                class="text-xs text-stone-400 hover:text-amber-600 transition-colors flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                                Reply
                            </button>
                            @endauth
                        </div>

                        {{-- Reply form --}}
                        @auth
                        <div id="reply-{{ $comment->id }}" class="hidden mt-3">
                            <form method="POST" action="{{ route('blogs.comment', $blog) }}" class="flex gap-2 items-start">
                                @csrf
                                <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                <div class="w-7 h-7 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center font-bold text-xs flex-shrink-0 mt-1">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                                <input id="reply-input" type="text" name="content" placeholder="Write a reply…"
                                    class="flex-1 border border-stone-200 rounded-xl px-3.5 py-2.5 text-sm bg-white focus:outline-none transition-all"
                                    required>
                                <button type="submit"
                                    class="px-4 py-2.5 bg-stone-900 text-white text-xs font-medium rounded-xl hover:bg-amber-500 hover:text-stone-900 transition-colors whitespace-nowrap">
                                    Reply
                                </button>
                            </form>
                        </div>
                        @endauth

                        {{-- Nested replies --}}
                        @if($comment->replies->isNotEmpty())
                        <div class="mt-4 space-y-3 ml-3 pl-4 border-l-2 border-stone-100">
                            @foreach($comment->replies as $reply)
                            <div class="flex gap-2.5">
                                @if($reply->author->avatar ?? false)
                                    <img src="{{ Storage::url($reply->author->avatar) }}"
                                         class="w-7 h-7 rounded-full object-cover flex-shrink-0 mt-0.5" alt="{{ $reply->author->name }}">
                                @else
                                    <div class="w-7 h-7 rounded-full bg-stone-100 text-stone-500 flex items-center justify-center font-bold text-xs flex-shrink-0 mt-0.5 border border-stone-200">
                                        {{ strtoupper(substr($reply->author->name ?? '?', 0, 1)) }}
                                    </div>
                                @endif
                                <div class="bg-white rounded-xl rounded-tl-sm px-3.5 py-2.5 border border-stone-100 flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="font-semibold text-stone-700 text-xs">{{ $reply->author->name }}</span>
                                        @if($reply->author_id === $blog->author_id)
                                        <span class="px-1.5 py-0.5 bg-amber-100 text-amber-700 text-[10px] font-medium rounded-full">Author</span>
                                        @endif
                                        <span class="text-[11px] text-stone-400 ml-auto">{{ $reply->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-stone-600 text-sm leading-relaxed">{{ $reply->content }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
                @empty
                <div class="text-center py-14 border border-dashed border-stone-200 rounded-2xl">
                    <p class="text-stone-500 text-sm font-medium">No comments yet</p>
                    <p class="text-stone-400 text-xs mt-1">Be the first to share your thoughts!</p>
                </div>
                @endforelse
                </div>

            </section>

        </article>{{-- /article --}}

        {{-- ── SIDEBAR ── --}}
        <aside class="hidden lg:block w-64 xl:w-72 flex-shrink-0">
            <div class="sticky top-24 space-y-5">

                {{-- Table of Contents --}}
                <div class="bg-white rounded-2xl border border-stone-100 p-4 shadow-sm" id="toc-card" style="display:none">
                    <h3 class="text-xs font-semibold text-stone-500 uppercase tracking-wider mb-3 flex items-center gap-2">
                        <span class="w-1 h-3.5 bg-amber-400 rounded-full inline-block"></span>
                        Contents
                    </h3>
                    <nav id="toc-nav" class="space-y-0.5"></nav>
                </div>

                {{-- Related --}}
                @if($related->isNotEmpty())
                <div class="bg-white rounded-2xl border border-stone-100 p-4 shadow-sm">
                    <h3 class="text-xs font-semibold text-stone-500 uppercase tracking-wider mb-4 flex items-center gap-2" style="font-family:'DM Mono', monospace;">
                        <span class="w-1 h-3.5 bg-amber-400 rounded-full inline-block"></span>
                        You Might Like
                    </h3>
                    <div class="space-y-4">
                        @foreach($related as $rel)
                        <a href="{{ route('blogs.show', $rel->slug) }}" class="flex gap-3 group">
                            @if($rel->cover_image)
                                <img src="{{ $rel->cover_image_url ?? Storage::url($rel->cover_image) }}"
                                     class="w-16 h-12 rounded-lg object-cover flex-shrink-0 group-hover:opacity-80 transition-opacity"
                                     alt="{{ $rel->title }}">
                            @else
                                <div class="w-16 h-12 rounded-lg bg-stone-100 flex-shrink-0"></div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-stone-800 leading-snug group-hover:text-amber-600 transition-colors line-clamp-2">
                                    {{ $rel->title }}
                                </p>
                                <p class="text-xs text-stone-400 mt-1">
                                    {{ $rel->author->name ?? '' }} · {{ $rel->read_time_minutes ?? 1 }}m
                                </p>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Back to listing --}}
                <a href="{{ route('blogs.index') }}"
                   class="flex items-center gap-2 text-sm text-stone-500 hover:text-amber-600 transition-colors group px-1">
                    <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to all blogs
                </a>

                {{-- Write CTA --}}
                @auth
                <div class="bg-stone-900 rounded-2xl p-5 text-center">
                    <p class="text-stone-400 text-xs mb-1">Inspired by this post?</p>
                    <p class="text-stone-200 text-sm font-medium mb-4">Write your own story.</p>
                    <a href="{{ route('user.blogs.create') }}"
                       class="block w-full text-white text-sm font-semibold rounded-xl transition-all"
                       style="
                            padding:11px 18px;
                            font-family:'DM Sans', sans-serif;
                            background: linear-gradient(135deg, #6366f1, #8b5cf6);
                            box-shadow: 0 4px 14px rgba(99,102,241,.4);
                       ">
                       Start Writing
                    </a>
                </div>
                @endauth

            </div>
        </aside>

    </div>{{-- /flex --}}
</div>

{{-- ── FLOATING LIKE BUTTON (mobile) ── --}}
@auth
<div class="fixed bottom-6 right-6 z-40 lg:hidden">
    <button id="float-like"
        class="flex items-center gap-2 px-5 py-3 rounded-full shadow-xl border text-sm font-semibold
               {{ $isLiked ? 'bg-rose-50 border-rose-300 text-rose-600' : 'bg-white border-stone-200 text-stone-600' }}"
        onclick="document.getElementById('like-btn').click()">
        <span>{{ $isLiked ? '♥' : '♡' }}</span>
        <span id="float-count">{{ number_format($blog->likes_count ?? 0) }}</span>
    </button>
</div>
@endauth

{{-- ── REPORT MODAL ── --}}
@auth
<div id="report-modal" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4 backdrop-blur-sm"
     onclick="if(event.target===this) this.classList.add('hidden')">
    <div class="bg-white rounded-2xl w-full max-w-md p-6 shadow-2xl" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between mb-5">
            <h3 class="font-display text-lg font-bold text-stone-800">Report This Post</h3>
            <button onclick="document.getElementById('report-modal').classList.add('hidden')"
                class="w-8 h-8 flex items-center justify-center rounded-full text-stone-400 hover:bg-stone-100 hover:text-stone-600 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form method="POST" action="{{ route('blogs.report', $blog) }}">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-stone-700 mb-1.5">Reason</label>
                <select name="reason"
                    class="w-full border border-stone-200 rounded-xl px-3 py-2.5 text-sm bg-stone-50 focus:outline-none focus:ring-2 focus:ring-amber-400 cursor-pointer">
                    <option value="spam">Spam or misleading</option>
                    <option value="misinformation">Misinformation</option>
                    <option value="offensive">Offensive or harmful</option>
                    <option value="copyright">Copyright violation</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div class="mb-5">
                <label class="block text-sm font-medium text-stone-700 mb-1.5">Additional note <span class="text-stone-400 font-normal">(optional)</span></label>
                <textarea name="note" rows="3" placeholder="Describe the issue…"
                    class="w-full border border-stone-200 rounded-xl px-3 py-2.5 text-sm bg-stone-50 focus:outline-none focus:ring-2 focus:ring-amber-400 resize-none"></textarea>
            </div>
            <div class="flex gap-3">
                <button type="submit"
                    class="flex-1 py-2.5 bg-rose-600 text-white text-sm font-semibold rounded-xl hover:bg-rose-700 transition-colors">
                    Submit Report
                </button>
                <button type="button"
                    onclick="document.getElementById('report-modal').classList.add('hidden')"
                    class="flex-1 py-2.5 border border-stone-200 text-stone-600 text-sm font-medium rounded-xl hover:bg-stone-50 transition-colors">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>
@endauth

@push('scripts')
<script>
    /* ── Reading progress ── */
    function updateProgress() {
        const body = document.getElementById('article-body');
        if (!body) return;
        const winH = window.innerHeight;
        const bodyRect = body.getBoundingClientRect();
        const total = body.offsetHeight;
        const scrolled = -bodyRect.top;

        // Guard against division by zero / negative denominator when the
        // article is shorter than the viewport.
        const denom = total - winH;
        const pct = denom > 0
            ? Math.min(100, Math.max(0, Math.round((scrolled / denom) * 100)))
            : 100;

        document.getElementById('reading-progress-bar').style.width = pct + '%';

        const sidebar = document.getElementById('progress-bar-sidebar');
        const pctEl = document.getElementById('progress-pct');
        const timeLeft = document.getElementById('time-left');
        if (sidebar) sidebar.style.width = pct + '%';
        if (pctEl) pctEl.textContent = pct + '%';
        if (timeLeft) {
            const totalMins = {{ $blog->read_time_minutes ?? 1 }};
            const minsLeft = Math.ceil((1 - pct / 100) * totalMins);
            timeLeft.textContent = pct >= 95 ? '✓ Finished' : minsLeft + ' min left';
        }
        updateTOC();
    }
    window.addEventListener('scroll', updateProgress, { passive: true });
    updateProgress();

    /* ── Table of Contents ── */
    function buildTOC() {
        const headings = document.querySelectorAll('#article-body h2, #article-body h3');
        const nav = document.getElementById('toc-nav');
        const card = document.getElementById('toc-card');
        if (!nav || headings.length < 2) return;

        headings.forEach((h, i) => {
            if (!h.id) h.id = 'heading-' + i;
            const a = document.createElement('a');
            a.href = '#' + h.id;
            a.textContent = h.textContent;
            a.style.paddingLeft = h.tagName === 'H3' ? '20px' : '10px';
            nav.appendChild(a);
        });
        if (card && headings.length > 1) card.style.display = 'block';
    }

    function updateTOC() {
        const headings = document.querySelectorAll('#article-body h2, #article-body h3');
        const links = document.querySelectorAll('#toc-nav a');
        if (!links.length) return;
        let active = 0;
        headings.forEach((h, i) => {
            if (h.getBoundingClientRect().top < 120) active = i;
        });
        links.forEach((l, i) => l.classList.toggle('toc-active', i === active));
    }

    buildTOC();

    /* ── Copy link ── */
    function copyLink() {
        navigator.clipboard.writeText(window.location.href).then(() => {
            const el = document.getElementById('copy-label');
            el.textContent = '✓ Copied!';
            setTimeout(() => el.textContent = 'Copy Link', 2000);
        });
    }

    /* ── Toggle reply form ── */
    function toggleReply(id) {
        const el = document.getElementById('reply-' + id);
        el.classList.toggle('hidden');
        if (!el.classList.contains('hidden')) {
            const input = el.querySelector('input');
            if (input) { input.focus(); }
        }
    }

    /* ── Like via AJAX ── */
    const likeForm = document.getElementById('like-form');
    if (likeForm) {
        likeForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const btn = document.getElementById('like-btn');
            const token = document.querySelector('[name=_token]').value;

            try {
                const res = await fetch(likeForm.action, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json', 'Content-Type': 'application/json' },
                    body: JSON.stringify({})
                });
                if (!res.ok) return;
                const data = await res.json();
                const liked = data.liked;

                document.getElementById('heart-icon').textContent = liked ? '♥' : '♡';
                document.getElementById('like-count').textContent = data.likes_count.toLocaleString();
                btn.lastElementChild.textContent = liked ? 'Liked' : 'Like';

                const floatLike = document.getElementById('float-like');
                const floatCount = document.getElementById('float-count');
                if (floatLike) {
                    floatLike.classList.toggle('liked', liked);
                    floatLike.querySelector('span').textContent = liked ? '♥' : '♡';
                }
                if (floatCount) floatCount.textContent = data.likes_count.toLocaleString();

                if (liked) {
                    btn.classList.remove('border-stone-200', 'text-stone-600', 'hover:border-rose-300', 'hover:bg-rose-50', 'hover:text-rose-500');
                    btn.classList.add('border-rose-300', 'bg-rose-50', 'text-rose-600');
                } else {
                    btn.classList.remove('border-rose-300', 'bg-rose-50', 'text-rose-600');
                    btn.classList.add('border-stone-200', 'text-stone-600', 'hover:border-rose-300', 'hover:bg-rose-50', 'hover:text-rose-500');
                }
            } catch(err) { likeForm.submit(); }
        });
    }

</script>
@endpush

@endsection