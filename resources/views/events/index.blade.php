{{-- resources/views/events/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Events — DonateBazaar')

@section('content')

@push('styles') @vite(['resources/css/public/events-index.css']) @endpush

{{-- ── HERO ── --}}
<section class="ev-hero">
    <div class="ev-hero-bg">
        @if(isset($heroImage) && $heroImage)
            <img src="{{ asset('storage/'.$heroImage) }}" alt="">
        @endif
    </div>
    <div class="ev-hero-overlay"></div>
    <div class="ev-hero-grid"></div>
    <div class="ev-hero-inner">
        <div class="ev-hero-content">
            <div class="ev-hero-tag">
                <span></span>
                Live Events
            </div>
            <h1>Make an <em>Impact</em><br>in Person</h1>
            <p class="ev-hero-sub">
                Join events that matter. Every registration brings real change to real communities.
            </p>
            <div class="ev-hero-stats">
                <div class="ev-stat">
                    <div class="ev-stat-num">{{ $totalEvents }}</div>
                    <div class="ev-stat-lbl">Total Events</div>
                </div>
                <div class="ev-stat">
                    <div class="ev-stat-num">{{ $activeEvents }}</div>
                    <div class="ev-stat-lbl">Active Now</div>
                </div>
                <div class="ev-stat">
                    <div class="ev-stat-num">{{ $categories->count() }}</div>
                    <div class="ev-stat-lbl">Categories</div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── BODY ── --}}
<div class="ev-body">
    <div class="ev-container">

        {{-- ── TOOLBAR: SEARCH + SORT ── --}}
        <div class="ev-toolbar">
            <div class="ev-search">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="7"/>
                    <path stroke-linecap="round" d="M21 21l-4.3-4.3"/>
                </svg>
                <input type="search" id="evSearch" placeholder="Search events or locations…" aria-label="Search events">
                <button type="button" id="evClear" class="ev-search-clear" aria-label="Clear search" hidden>&times;</button>
            </div>
            <div class="ev-sort">
                <label for="evSort" class="ev-sort-label">Sort</label>
                <select id="evSort" aria-label="Sort events">
                    <option value="date-asc">Soonest first</option>
                    <option value="date-desc">Latest first</option>
                </select>
            </div>
        </div>

        {{-- ── PERIOD FILTER ── --}}
        <div class="ev-period-wrap">
            <x-button variant="primary" type="button" class="ev-period-btn">Today</x-button>
            <x-button variant="primary" type="button" class="ev-period-btn">This Week</x-button>
            <x-button variant="primary" type="button" class="ev-period-btn">This Month</x-button>
            <x-button variant="primary" type="button" class="ev-period-btn active">All Upcoming</x-button>
        </div>

        {{-- ── FILTER TABS ── --}}
        <div class="ev-filter-wrap">
            <div class="ev-filter-scroll">
                <x-button variant="primary" type="button" class="ev-filter-btn active" data-action="filter-cat" data-cat="all">
                    All Events
                    <span class="ev-filter-count">{{ $totalEvents }}</span>
                </x-button>
                @foreach($categories as $cat)
                    @if($eventsByCategory->has($cat->id))
                    <x-button variant="primary" type="button" class="ev-filter-btn" data-action="filter-cat" data-cat="{{ $cat->id }}">
                        {{ $cat->emoji ?? '' }} {{ $cat->name }}
                        <span class="ev-filter-count">{{ $eventsByCategory[$cat->id]->count() }}</span>
                    </x-button>
                    @endif
                @endforeach
            </div>
            <div class="ev-result-count" aria-live="polite">
                <span id="evCount"></span>
            </div>
        </div>

        {{-- ── EVENTS BY CATEGORY ── --}}
        @forelse($categories as $cat)
            @if($eventsByCategory->has($cat->id))
            <div class="ev-category-section" data-cat="{{ $cat->id }}" id="cat-{{ $cat->id }}">

                {{-- Section heading --}}
                <div class="ev-section-head">
                    <div class="ev-section-title">
                        {{ $cat->emoji ?? '' }} {{ $cat->name }}
                    </div>
                    <div class="ev-section-line"></div>
                    <div class="ev-section-count">{{ $eventsByCategory[$cat->id]->count() }} event{{ $eventsByCategory[$cat->id]->count() !== 1 ? 's' : '' }}</div>
                </div>

                {{-- Cards grid --}}
                <div class="ev-grid">
                    @foreach($eventsByCategory[$cat->id] as $event)
                    @php
                        $pct = $event->goal_amount > 0
                            ? min(100, round(($event->raised_amount / $event->goal_amount) * 100))
                            : 0;
                        $canRegister = $event->isActive() && !$event->hasEnded() && !$event->isFull();
                    @endphp
                    @php
                        $diffNow = $event->event_date ? now()->diffInDays($event->event_date, false) : 999;
                        $period = $diffNow < 0 ? 'past' : ($diffNow == 0 ? 'today' : ($diffNow <= 7 ? 'week' : ($diffNow <= 30 ? 'month' : 'future')));
                        $descPreview = $event->description ? strip_tags(substr($event->description, 0, 120)) : '';
                    @endphp
                    <div class="ev-card"
                         data-url="{{ route('events.show', $event->id) }}"
                         data-title="{{ strtolower($event->title) }}"
                         data-location="{{ strtolower($event->location ?? '') }}"
                         data-date="{{ $event->event_date?->format('Y-m-d') ?? '' }}"
                         data-time="{{ $event->start_time ?? '' }}"
                         data-period="{{ $period }}"
                         tabindex="0"
                         role="link"
                         aria-label="View event: {{ $event->title }}"
                          style="--delay:{{ $loop->index * 0.06 }}s;">

                        {{-- Cover --}}
                        <div class="ev-card-cover">
                            @if($event->cover_image)
                                <img src="{{ asset('storage/'.$event->cover_image) }}" alt="{{ $event->title }}">
                            @else
                                <div class="ev-card-cover-placeholder">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endif

                            {{-- Date badge --}}
                            <div class="ev-date-badge">
                                <div class="ev-date-day">{{ $event->event_date?->format('d') ?? '—' }}</div>
                                <div class="ev-date-mon">{{ $event->event_date?->format('M') ?? '' }}</div>
                                <div class="ev-date-yr">{{ $event->event_date?->format('Y') ?? '' }}</div>
                            </div>

                            {{-- Status badge --}}
                            <div class="ev-status ev-status-{{ $event->status }}">
                                {{ ucfirst($event->status) }}
                            </div>
                        </div>

                        {{-- Body --}}
                        <div class="ev-card-body">
                            <div class="ev-card-cat">
                                {{ $cat->emoji ?? '' }} {{ $cat->name }}
                            </div>

                            <div class="ev-card-title">{{ $event->title }}</div>

                            @if($descPreview)
                                <div class="ev-card-desc">{{ $descPreview }}@if(strlen($event->description) > 120)&hellip;@endif</div>
                            @endif

                            @if($diffNow >= 0 && $diffNow <= 7)
                                <div class="ev-time-remaining">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    @if($diffNow == 0) Today @elseif($diffNow == 1) Tomorrow @else In {{ $diffNow }} days @endif
                                </div>
                            @endif

                            <div class="ev-card-meta">
                                @if($event->start_time)
                                <div class="ev-card-meta-item">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ \Carbon\Carbon::parse($event->start_time)->format('g:i A') }}
                                    @if($event->end_time)
                                        – {{ \Carbon\Carbon::parse($event->end_time)->format('g:i A') }}
                                    @endif
                                </div>
                                @endif

                                @if($event->location)
                                <div class="ev-card-meta-item" title="{{ e($event->location) }}">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <span class="ev-loc">{{ $event->location }}</span>
                                </div>
                                @endif

                                @if($event->max_participants)
                                <div class="ev-card-meta-item">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/>
                                    </svg>
                                    {{ $event->max_participants }} spots
                                </div>
                                @endif
                            </div>

                            {{-- Goal bar --}}
                            @if($event->goal_amount > 0)
                            <div class="ev-goal-wrap">
                                <div class="ev-goal-label">
                                    <span>₹{{ number_format($event->raised_amount, 0) }} raised</span>
                                    <span>{{ $pct }}% of ₹{{ number_format($event->goal_amount, 0) }}</span>
                                </div>
                                <div class="ev-goal-bar">
                                    <div class="ev-goal-fill" style="width:{{ $pct }}%;"></div>
                                </div>
                            </div>
                            @endif
                        </div>

                        {{-- Footer --}}
                        <div class="ev-card-footer">
                            {{-- Spots remaining --}}
                            @if($event->max_participants)
                                @if($event->isFull())
                                    <div class="ev-spots full">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Fully booked
                                    </div>
                                @else
                                    <div class="ev-spots">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ $event->remainingSpots() }} left
                                    </div>
                                @endif
                            @else
                                <div class="ev-spots">Open registration</div>
                            @endif

                            {{-- CTA button --}}
                            @if($canRegister)
                                <x-button variant="primary" href="{{ route('events.register', $event->id) }}">
                                    Register
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </x-button>
                            @elseif($event->hasEnded())
                                <x-button variant="primary" disabled>Event Ended</x-button>
                            @elseif($event->isFull())
                                <x-button variant="primary" disabled>Full</x-button>
                            @else
                                <x-button variant="secondary" href="{{ route('events.show', $event->id) }}">
                                    View Details
                                </x-button>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

            </div>
            @endif
        @empty
            <div class="ev-grid">
                <div class="ev-empty">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <div class="ev-empty-title">No events yet</div>
                    <div class="ev-empty-sub">Check back soon for upcoming events.</div>
                </div>
            </div>
        @endforelse

        {{-- ── NO SEARCH RESULTS (client-side) ── --}}
        <div class="ev-no-results" id="evNoResults" hidden>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <circle cx="11" cy="11" r="7"/>
                <path stroke-linecap="round" d="M21 21l-4.3-4.3"/>
            </svg>
            <div class="ev-empty-title">No matching events</div>
            <div class="ev-empty-sub">Try adjusting your search, category, or time period.</div>
            <x-button variant="secondary" type="button">Clear filters</x-button>
        </div>

    </div>
</div>

@endsection

@push('scripts') @vite(['resources/js/public/events-index.js']) @endpush
