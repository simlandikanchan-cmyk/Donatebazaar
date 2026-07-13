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
                    <button type="submit" class="search-submit">Search</button>
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

@push('styles')
<style>
.search-page{--font:'DM Sans',sans-serif;--mono:'DM Mono',monospace;--accent:#6366f1;--accent2:#8b5cf6;}
.search-hero{position:relative;overflow:hidden;background:linear-gradient(160deg,#0d0e1a,#0f172a 50%,#042f2e);padding:80px 24px 64px;text-align:center;}
.search-hero-bg{position:absolute;inset:0;background-image:linear-gradient(rgba(99,102,241,0.04) 1px,transparent 1px),linear-gradient(90deg,rgba(99,102,241,0.04) 1px,transparent 1px);background-size:36px 36px;pointer-events:none;}
.search-hero-inner{position:relative;z-index:1;max-width:600px;margin:0 auto;}
.search-hero-inner h1{font-family:var(--mono);font-size:clamp(24px,4vw,34px);font-weight:500;color:#fff;letter-spacing:-0.03em;margin-bottom:6px;}
.search-hero-inner p{font-size:14px;color:rgba(255,255,255,.45);margin-bottom:24px;}
.search-form-wrap{display:flex;align-items:center;gap:0;background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.12);border-radius:14px;padding:4px;backdrop-filter:blur(8px);transition:border-color .25s;}
.search-form-wrap:focus-within{border-color:var(--accent);box-shadow:0 0 0 3px rgba(99,102,241,.15);}
.search-form-wrap svg{width:18px;height:18px;color:rgba(255,255,255,.35);margin-left:14px;flex-shrink:0;}
.search-input{flex:1;background:none;border:none;padding:12px 14px;font-size:15px;font-family:var(--font);color:#fff;outline:none;}
.search-input::placeholder{color:rgba(255,255,255,.3);}
.search-submit{padding:10px 24px;border-radius:10px;border:none;background:var(--accent);color:#fff;font-family:var(--mono);font-size:13px;font-weight:500;letter-spacing:.02em;cursor:pointer;transition:opacity .2s;white-space:nowrap;}
.search-submit:hover{opacity:.85;}
.search-body{max-width:760px;margin:0 auto;padding:32px 20px 60px;}
.search-meta{font-size:13px;color:#6b7280;font-family:var(--mono);margin-bottom:20px;}
.search-empty{text-align:center;padding:60px 20px;}
.search-empty svg{width:48px;height:48px;color:#9ca3af;opacity:.3;}
.search-empty h2{font-size:18px;font-weight:700;color:#1a1a2e;margin:14px 0 6px;}
.search-empty p{font-size:13px;color:#6b7280;}
.search-empty a{color:var(--accent);text-decoration:none;}
.search-prompt{text-align:center;padding:80px 20px;}
.search-prompt svg{width:48px;height:48px;color:#9ca3af;opacity:.25;}
.search-prompt h2{font-size:18px;font-weight:700;color:#1a1a2e;margin:14px 0 6px;}
.search-prompt p{font-size:13px;color:#6b7280;}
.search-section{margin-bottom:28px;}
.search-section-title{display:flex;align-items:center;gap:8px;font-size:14px;font-weight:700;color:#1a1a2e;margin-bottom:12px;}
.search-section-title svg{width:16px;height:16px;color:var(--accent);}
.search-count{font-size:11px;font-weight:600;color:#9ca3af;font-family:var(--mono);margin-left:auto;background:#f3f4f6;padding:2px 9px;border-radius:100px;}
.search-list{display:flex;flex-direction:column;gap:10px;}
.search-item{display:flex;gap:14px;padding:14px;border:1px solid rgba(0,0,0,.06);border-radius:12px;text-decoration:none;transition:border-color .2s,box-shadow .2s;background:#fff;}
.search-item:hover{border-color:rgba(99,102,241,.2);box-shadow:0 4px 20px rgba(99,102,241,.08);}
.search-item-img{width:52px;height:52px;border-radius:10px;background:#f3f4f6;color:#9ca3af;display:flex;align-items:center;justify-content:center;flex-shrink:0;overflow:hidden;}
.search-item-img img{width:100%;height:100%;object-fit:cover;}
.search-item-img svg{width:20px;height:20px;opacity:.4;}
.search-item-body{flex:1;min-width:0;}
.search-item-title{font-size:14px;font-weight:700;color:#0f1117;letter-spacing:-.01em;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.search-item-desc{font-size:12px;color:#6b7280;line-height:1.5;margin-top:3px;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;}
.search-item-meta{display:flex;align-items:center;gap:12px;margin-top:6px;font-size:11px;color:#9ca3af;font-family:var(--mono);}
.search-item-badge{padding:1px 8px;border-radius:100px;background:rgba(99,102,241,.08);color:var(--accent);font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.05em;}
@media(max-width:520px){.search-hero{padding:60px 16px 48px;}.search-form-wrap{flex-wrap:wrap;}.search-submit{width:100%;}}
</style>
@endpush
