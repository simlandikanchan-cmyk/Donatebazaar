@extends('layouts.app')

@section('title', $query ? "Search: $query" : 'Search')
@section('meta_description', 'Search campaigns, blogs, and events on DonateBazaar.')

@section('content')
@php
$hasResults = $total > 0;
@endphp

<div class="search-page">
    <div class="search-hero">
        <div class="search-hero-bg"></div>
        <div class="search-hero-inner">
            <h1>Search</h1>
            <p>Find campaigns, blog posts, and events</p>
            <form action="{{ route('search') }}" method="GET" class="search-form">
                <div class="search-form-wrap">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                    <input type="text" name="q" class="search-input" placeholder="Search campaigns, blogs, events…" value="{{ old('q', $query) }}" autocomplete="off" autofocus>
                    <x-button variant="primary" type="submit">Search</x-button>
                </div>
            </form>
        </div>
    </div>

    <div class="search-body">
        @if($query && !$hasResults)
        <div class="search-empty">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><circle cx="11" cy="11" r="7"/><path stroke-linecap="round" d="M21 21l-3.5-3.5"/></svg>
            <h2>No results for "{{ $query }}"</h2>
            <p>Try adjusting your search terms or browse our <a href="{{ route('all.campaigns') }}">campaigns</a>.</p>
        </div>
        @elseif($query && $hasResults)
        <div class="search-meta">{{ $total }} {{ Str::plural('result', $total) }} for "{{ $query }}"</div>

        <div class="search-results">
            @if($campaigns->isNotEmpty())
            <div class="search-section">
                <div class="search-section-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                    Campaigns
                    <span class="search-count">{{ $campaigns->count() }}</span>
                </div>
                <div class="search-list">
                    @foreach($campaigns as $c)
                    <a href="{{ route('campaign.public', ['category' => $c->category->slug ?? 'general', 'slug' => $c->slug]) }}" class="search-item">
                        <div class="search-item-img">
                            @if($c->cover_image)
                            <img src="{{ asset('storage/'.$c->cover_image) }}" alt="">
                            @else
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                            @endif
                        </div>
                        <div class="search-item-body">
                            <div class="search-item-title">{{ $c->title }}</div>
                            <div class="search-item-desc">{{ Str::limit(strip_tags($c->description), 120) }}</div>
                            <div class="search-item-meta">
                                <span>₹{{ number_format($c->raised_amount, 0) }} raised</span>
                                <span class="search-item-badge">{{ ucfirst($c->campaign_state) }}</span>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            @if($blogs->isNotEmpty())
            <div class="search-section">
                <div class="search-section-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                    Blog Posts
                    <span class="search-count">{{ $blogs->count() }}</span>
                </div>
                <div class="search-list">
                    @foreach($blogs as $b)
                    <a href="{{ route('blogs.show', $b->slug) }}" class="search-item">
                        <div class="search-item-img">
                            @if($b->featured_image)
                            <img src="{{ asset('storage/'.$b->featured_image) }}" alt="">
                            @else
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                            @endif
                        </div>
                        <div class="search-item-body">
                            <div class="search-item-title">{{ $b->title }}</div>
                            <div class="search-item-desc">{{ Str::limit(strip_tags($b->excerpt ?? $b->content), 120) }}</div>
                            <div class="search-item-meta">
                                <span>{{ $b->created_at->format('d M Y') }}</span>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            @if($events->isNotEmpty())
            <div class="search-section">
                <div class="search-section-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    Events
                    <span class="search-count">{{ $events->count() }}</span>
                </div>
                <div class="search-list">
                    @foreach($events as $e)
                    <a href="{{ route('events.show', $e->id) }}" class="search-item">
                        <div class="search-item-img">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        </div>
                        <div class="search-item-body">
                            <div class="search-item-title">{{ $e->title }}</div>
                            <div class="search-item-desc">{{ Str::limit(strip_tags($e->description), 120) }}</div>
                            <div class="search-item-meta">
                                <span>{{ $e->event_date ? \Carbon\Carbon::parse($e->event_date)->format('d M Y') : '' }}</span>
                                @if($e->location)<span>{{ $e->location }}</span>@endif
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
        @else
        <div class="search-prompt">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
            <h2>Search campaigns, blogs & events</h2>
            <p>Type a keyword above to find what you're looking for.</p>
        </div>
        @endif
    </div>
</div>
@endsection

@push('styles') @vite(['resources/css/public/search.css']) @endpush
