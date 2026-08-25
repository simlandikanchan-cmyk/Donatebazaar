@extends('layouts.app')

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,300&family=DM+Mono:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,700;1,700&display=swap" rel="stylesheet">

@push('styles') @vite(['resources/css/public/events-public-show.css']) @endpush

@php
$daysUntil = now()->diffInDays(\Carbon\Carbon::parse($event->event_date), false);
$raised = $event->raised_amount ?? 0;
$goalAmount = $event->goal_amount ?? 0;
$percentage = ($goalAmount > 0) ? min(100, round(($raised / $goalAmount) * 100)) : 0;
$registered = $event->registered_count ?? 0;
$maxPart = $event->max_participants ?? 0;
$partPct = ($maxPart > 0) ? min(100, round(($registered / $maxPart) * 100)) : 0;
if ($event->status === 'active') {
$chipClass = 'chip-active'; $chipLabel = 'Active';
} elseif ($event->status === 'pending') {
$chipClass = 'chip-pending'; $chipLabel = 'Pending';
} elseif ($event->status === 'completed') {
$chipClass = 'chip-completed'; $chipLabel = 'Completed';
} elseif ($event->status === 'cancelled') {
$chipClass = 'chip-cancelled'; $chipLabel = 'Cancelled';
} else {
$chipClass = 'chip-pending'; $chipLabel = ucfirst($event->status ?? 'Draft');
}
@endphp

<div class="ep-wrap">

    {{-- Breadcrumb --}}
    <div class="ep-bread">
        <a href="{{ route('campaigns.index') }}">Campaigns</a>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 5l7 7-7 7"/></svg>
        @if($event->campaign && $event->campaign->category)
        <a href="{{ route('campaign.public', ['category' => $event->campaign->category->slug, 'slug' => $event->campaign->slug]) }}">{{ $event->campaign->title }}</a>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 5l7 7-7 7"/></svg>
        @endif
        <span>{{ $event->title }}</span>
    </div>

    {{-- Countdown --}}
    @if($daysUntil > 0)
    <div class="ep-countdown ep-d1">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        <div>
            <div class="ep-countdown-text">{{ $daysUntil }} {{ Str::plural('day', $daysUntil) }} until this event</div>
            <div class="ep-countdown-sub">{{ \Carbon\Carbon::parse($event->event_date)->format('l, d F Y') }}</div>
        </div>
    </div>
    @elseif($daysUntil === 0)
    <div class="ep-countdown ep-d1" style="background:rgba(16,185,129,0.1);border-color:rgba(16,185,129,0.25);">
        <svg viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="2"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <div>
            <div class="ep-countdown-text" style="color:var(--green);">This event is happening today!</div>
            <div class="ep-countdown-sub">{{ \Carbon\Carbon::parse($event->event_date)->format('l, d F Y') }}</div>
        </div>
    </div>
    @endif

    {{-- Cover Image --}}
    @if($event->cover_image)
    <div class="ep-cover ep-d1">
        <img src="{{ asset('storage/'.$event->cover_image) }}" alt="{{ $event->title }}" loading="lazy">
    </div>
    @endif

    {{-- Header --}}
    <div class="ep-header ep-d2">
        @if($event->campaign)
        <a href="{{ route('campaign.public', ['category' => $event->campaign->category->slug, 'slug' => $event->campaign->slug]) }}" class="ep-campaign-link">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            {{ Str::limit($event->campaign->title, 50) }}
        </a>
        @endif
        <h1 class="ep-title">{{ $event->title }}</h1>
        <div class="ep-meta">
            <span class="ep-meta-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                {{ \Carbon\Carbon::parse($event->event_date)->format('d M Y') }}
            </span>
            @if($event->start_time)
            <span class="ep-meta-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                {{ \Carbon\Carbon::parse($event->start_time)->format('h:i A') }}
                @if($event->end_time) – {{ \Carbon\Carbon::parse($event->end_time)->format('h:i A') }} @endif
            </span>
            @endif
            @if($event->location)
            <span class="ep-meta-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                {{ $event->location }}
            </span>
            @endif
            <span class="status-chip {{ $chipClass }}"><span class="dot"></span>{{ $chipLabel }}</span>
        </div>
    </div>

    {{-- Grid --}}
    <div class="ep-grid">

        {{-- LEFT COL --}}
        <div>

            {{-- Description --}}
            <div class="ep-card ep-d3">
                <div class="ep-card-header">
                    <div class="ep-card-icon ic-indigo">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div>
                        <div class="ep-card-title">About This Event</div>
                        <div class="ep-card-sub">Event description</div>
                    </div>
                </div>
                <div class="ep-card-body">
                    <p class="ep-desc">{{ $event->description }}</p>
                </div>
            </div>

            {{-- Schedule --}}
            <div class="ep-card ep-d3" style="animation-delay:.12s">
                <div class="ep-card-header">
                    <div class="ep-card-icon ic-yellow">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    </div>
                    <div>
                        <div class="ep-card-title">Schedule</div>
                        <div class="ep-card-sub">Date &amp; timings</div>
                    </div>
                </div>
                <div class="ep-card-body">
                    <div class="ep-time-grid">
                        <div class="ep-time-tile">
                            <div class="ep-time-label">Date</div>
                            <div class="ep-time-val">{{ \Carbon\Carbon::parse($event->event_date)->format('d M Y') }}</div>
                        </div>
                        <div class="ep-time-tile">
                            <div class="ep-time-label">Day</div>
                            <div class="ep-time-val">{{ \Carbon\Carbon::parse($event->event_date)->format('l') }}</div>
                        </div>
                        @if($event->start_time)
                        <div class="ep-time-tile">
                            <div class="ep-time-label">Start</div>
                            <div class="ep-time-val">{{ \Carbon\Carbon::parse($event->start_time)->format('h:i A') }}</div>
                        </div>
                        @endif
                        @if($event->end_time)
                        <div class="ep-time-tile">
                            <div class="ep-time-label">End</div>
                            <div class="ep-time-val">{{ \Carbon\Carbon::parse($event->end_time)->format('h:i A') }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>{{-- /.left --}}

        {{-- RIGHT COL --}}
        <div class="ep-right">

            {{-- Register Card --}}
            <div class="ep-card ep-d3" style="animation-delay:.08s">
                <div class="ep-card-header">
                    <div class="ep-card-icon ic-green">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 20V10M12 20V4M6 20v-6"/></svg>
                    </div>
                    <div>
                        <div class="ep-card-title">Registration</div>
                        <div class="ep-card-sub">Join this event</div>
                    </div>
                </div>
                <div class="ep-card-body" style="display:flex;flex-direction:column;gap:12px;">
                    @if($event->allow_registrations && $event->isActive() && !$event->hasEnded())
                    @if($maxPart > 0 && $event->isFull())
                    <x-button type="button" variant="primary" disabled class="ep-reg-btn">Event Full</x-button>
                    @else
                    <x-button href="{{ route('events.register', $event->id) }}" variant="primary" class="ep-reg-btn">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 20V10M12 20V4M6 20v-6"/></svg>
                        Register Now
                    </x-button>
                    @endif
                    @else
                    <x-button type="button" variant="primary" disabled class="ep-reg-btn">
                        {{ $event->hasEnded() ? 'Event Ended' : ($event->isActive() ? 'Registrations Closed' : 'Not Available') }}
                    </x-button>
                    @endif
                    <a href="{{ route('campaign.public', ['category' => $event->campaign->category->slug, 'slug' => $event->campaign->slug]) }}" class="ep-back">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
                        Back to Campaign
                    </a>
                </div>
            </div>

            {{-- Fundraising --}}
            @if($goalAmount > 0)
            <div class="ep-card ep-d3" style="animation-delay:.14s">
                <div class="ep-card-header">
                    <div class="ep-card-icon ic-indigo">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <div class="ep-card-title">Fundraising</div>
                        <div class="ep-card-sub">Event progress</div>
                    </div>
                </div>
                <div class="ep-card-body">
                    <div class="ep-prog-numbers">
                        <div class="ep-prog-raised">₹{{ number_format($raised) }}</div>
                        <div class="ep-prog-goal">of ₹{{ number_format($goalAmount) }}</div>
                    </div>
                    <div class="ep-prog-bar">
                        <div class="ep-prog-fill" id="progFill" style="width:0%"></div>
                    </div>
                    <div class="ep-prog-pct">{{ $percentage }}% funded</div>
                </div>
            </div>
            @endif

            {{-- Participants --}}
            @if($maxPart > 0)
            <div class="ep-card ep-d3" style="animation-delay:.20s">
                <div class="ep-card-header">
                    <div class="ep-card-icon ic-green">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
                    </div>
                    <div>
                        <div class="ep-card-title">Participants</div>
                        <div class="ep-card-sub">Registration capacity</div>
                    </div>
                </div>
                <div class="ep-card-body">
                    <div class="ep-part-numbers">
                        <div class="ep-part-val">{{ $registered }}</div>
                        <div class="ep-part-max">of {{ $maxPart }} spots</div>
                    </div>
                    <div class="ep-part-bar">
                        <div class="ep-part-fill" id="partFill" style="width:0%"></div>
                    </div>
                    <div class="ep-part-pct">{{ $partPct }}% capacity filled</div>
                </div>
            </div>
            @endif

            {{-- Event Info --}}
            <div class="ep-card ep-d3" style="animation-delay:.26s">
                <div class="ep-card-header">
                    <div class="ep-card-icon ic-pink">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    <div><div class="ep-card-title">Event Info</div></div>
                </div>
                <div class="ep-card-body" style="display:flex;flex-direction:column;">
                    <div class="ep-info-row">
                        <span class="ep-info-label">Status</span>
                        <span class="status-chip {{ $chipClass }}" style="font-size:10px;padding:3px 9px;"><span class="dot"></span>{{ $chipLabel }}</span>
                    </div>
                    <div class="ep-info-row">
                        <span class="ep-info-label">Date</span>
                        <span class="ep-info-val">{{ \Carbon\Carbon::parse($event->event_date)->format('d M Y') }}</span>
                    </div>
                    @if($event->start_time)
                    <div class="ep-info-row">
                        <span class="ep-info-label">Start</span>
                        <span class="ep-info-val">{{ \Carbon\Carbon::parse($event->start_time)->format('h:i A') }}</span>
                    </div>
                    @endif
                    @if($event->end_time)
                    <div class="ep-info-row">
                        <span class="ep-info-label">End</span>
                        <span class="ep-info-val">{{ \Carbon\Carbon::parse($event->end_time)->format('h:i A') }}</span>
                    </div>
                    @endif
                    @if($event->location)
                    <div class="ep-info-row">
                        <span class="ep-info-label">Location</span>
                        <span class="ep-info-val" style="font-size:11px;max-width:150px;">{{ $event->location }}</span>
                    </div>
                    @endif
                    @if($maxPart > 0)
                    <div class="ep-info-row">
                        <span class="ep-info-label">Capacity</span>
                        <span class="ep-info-val">{{ $registered }} / {{ $maxPart }}</span>
                    </div>
                    @endif
                    <div class="ep-info-row">
                        <span class="ep-info-label">Campaign</span>
                        <span class="ep-info-val" style="font-size:11px;max-width:140px;color:var(--primary);">{{ Str::limit($event->campaign->title, 25) }}</span>
                    </div>
                </div>
            </div>

        </div>{{-- /.right --}}
    </div>{{-- /.ep-grid --}}
</div>{{-- /.ep-wrap --}}

<script>
(function(){
'use strict';
function animateBars(){
var pf = document.getElementById('progFill');
var ptf = document.getElementById('partFill');
if (pf) { setTimeout(function(){ pf.style.width = '{{ $percentage }}%'; }, 400); }
if (ptf) { setTimeout(function(){ ptf.style.width = '{{ $partPct }}%'; }, 500); }
}
if ('IntersectionObserver' in window) {
var obs = new IntersectionObserver(function(entries){
entries.forEach(function(e){ if(e.isIntersecting){ animateBars(); obs.disconnect(); } });
}, { threshold: 0.2 });
var card = document.querySelector('.ep-card');
if (card) obs.observe(card);
} else { setTimeout(animateBars, 600); }
})();
</script>
@endsection
