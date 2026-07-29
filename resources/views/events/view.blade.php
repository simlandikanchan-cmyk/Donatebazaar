<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ $event->title }} — DonateBazaar</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
@vite(['resources/css/user/user.css', 'resources/css/public/events-view.css'])

@php
    if ($event->status === 'active') {
        $chipClass = 'chip-active'; $chipLabel = 'Active';
    } elseif ($event->status === 'pending') {
        $chipClass = 'chip-pending'; $chipLabel = 'Pending';
    } elseif ($event->status === 'completed') {
        $chipClass = 'chip-completed'; $chipLabel = 'Completed';
    } elseif ($event->status === 'cancelled') {
        $chipClass = 'chip-rejected'; $chipLabel = 'Cancelled';
    } else {
        $chipClass = 'chip-pending'; $chipLabel = ucfirst($event->status ?? 'Draft');
    }
    $raised     = $event->raised_amount ?? 0;
    $goalAmount = $event->goal_amount   ?? 0;
    $percentage = ($goalAmount > 0) ? min(100, round(($raised / $goalAmount) * 100)) : 0;
    $remaining  = max(0, $goalAmount - $raised);
    $registered = $event->registered_count ?? 0;
    $maxPart    = $event->max_participants  ?? 0;
    $partPct    = ($maxPart > 0) ? min(100, round(($registered / $maxPart) * 100)) : 0;
@endphp
</head>
<body>
<div class="shell">

{{-- ══════════ SIDEBAR ══════════ --}}
@include('partials.user-sidebar')

{{-- ══════════ MAIN ══════════ --}}
<div class="main">

    {{-- TOPBAR --}}
    <header class="topbar">
        <div class="topbar-left">
            <button class="hamburger" id="hamburger" aria-label="Menu">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <a href="{{ route('campaign.show', $event->campaign->id) }}" class="topbar-back" title="Back to Campaign">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5M12 5l-7 7 7 7"/></svg>
            </a>
            <div class="topbar-title">
                <h1>{{ Str::limit($event->title, 40) }}</h1>
                <p>Event overview · {{ Str::limit($event->campaign->title, 30) }}</p>
            </div>
        </div>
        <div class="topbar-right">
            <span class="status-chip {{ $chipClass }}"><span class="dot"></span>{{ $chipLabel }}</span>
            <div class="theme-toggle">
                <input type="checkbox" id="themeToggle">
                <label for="themeToggle">
                    <div class="theme-icons">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="5"/><path stroke-linecap="round" d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/></svg>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
                    </div>
                </label>
            </div>
            <div class="t-av">{{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}</div>
        </div>
    </header>

    {{-- BODY --}}
    <div class="body">
        <div class="page-grid">

            {{-- ═════ LEFT ═════ --}}
            <div>

                {{-- Upcoming countdown banner --}}
                @php $daysUntil = now()->diffInDays(\Carbon\Carbon::parse($event->event_date), false); @endphp
                @if($daysUntil > 0)
                <div class="countdown-banner">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    <div>
                        <div class="countdown-text">{{ $daysUntil }} {{ Str::plural('day', $daysUntil) }} until this event</div>
                        <div class="countdown-sub">{{ \Carbon\Carbon::parse($event->event_date)->format('l, d F Y') }}</div>
                    </div>
                </div>
                @elseif($daysUntil === 0)
                <div class="countdown-banner" style="background:rgba(16,185,129,0.1);border-color:rgba(16,185,129,0.25);">
                    <svg viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div>
                        <div class="countdown-text" style="color:var(--green);">This event is happening today!</div>
                        <div class="countdown-sub">{{ \Carbon\Carbon::parse($event->event_date)->format('l, d F Y') }}</div>
                    </div>
                </div>
                @endif

                {{-- Event Title Card --}}
                <div class="card d1">
                    <div class="event-header-block">
                        <a href="{{ route('campaign.show', $event->campaign->id) }}" class="event-campaign-link">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            {{ Str::limit($event->campaign->title, 40) }}
                        </a>
                        <h1>{{ $event->title }}</h1>
                        <div class="event-meta-row">
                            <span class="event-meta-item">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                {{ \Carbon\Carbon::parse($event->event_date)->format('d M Y') }}
                            </span>
                            @if($event->start_time)
                            <span class="event-meta-item">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                {{ \Carbon\Carbon::parse($event->start_time)->format('h:i A') }}
                                @if($event->end_time) – {{ \Carbon\Carbon::parse($event->end_time)->format('h:i A') }} @endif
                            </span>
                            @endif
                            @if($event->location ?? null)
                            <span class="event-meta-item">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                {{ $event->location }}
                            </span>
                            @endif
                            <span class="status-chip {{ $chipClass }}" style="font-size:10px;padding:3px 9px;">
                                <span class="dot"></span>{{ $chipLabel }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Description --}}
                <div class="card d2">
                    <div class="card-header">
                        <div class="card-header-left">
                            <div class="card-icon ic-indigo">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <div>
                                <div class="card-title">About This Event</div>
                                <div class="card-sub">Event description</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="desc-text">{{ $event->description }}</p>
                    </div>
                </div>

                {{-- Schedule --}}
                <div class="card d3">
                    <div class="card-header">
                        <div class="card-header-left">
                            <div class="card-icon ic-yellow">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            </div>
                            <div>
                                <div class="card-title">Schedule</div>
                                <div class="card-sub">Date &amp; timings</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="time-grid">
                            <div class="time-tile">
                                <div class="time-tile-label">Date</div>
                                <div class="time-tile-val">{{ \Carbon\Carbon::parse($event->event_date)->format('d M Y') }}</div>
                            </div>
                            <div class="time-tile">
                                <div class="time-tile-label">Day</div>
                                <div class="time-tile-val">{{ \Carbon\Carbon::parse($event->event_date)->format('l') }}</div>
                            </div>
                            @if($event->start_time)
                            <div class="time-tile">
                                <div class="time-tile-label">Start Time</div>
                                <div class="time-tile-val">{{ \Carbon\Carbon::parse($event->start_time)->format('h:i A') }}</div>
                            </div>
                            @endif
                            @if($event->end_time)
                            <div class="time-tile">
                                <div class="time-tile-label">End Time</div>
                                <div class="time-tile-val">{{ \Carbon\Carbon::parse($event->end_time)->format('h:i A') }}</div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Participants --}}
                @if($maxPart > 0)
                <div class="card d4">
                    <div class="card-header">
                        <div class="card-header-left">
                            <div class="card-icon ic-blue">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path stroke-linecap="round" stroke-linejoin="round" d="M23 21v-2a4 4 0 00-3-3.87"/><path stroke-linecap="round" stroke-linejoin="round" d="M16 3.13a4 4 0 010 7.75"/></svg>
                            </div>
                            <div>
                                <div class="card-title">Participants</div>
                                <div class="card-sub">Registration capacity</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="participants-numbers">
                            <div class="participants-val">{{ $registered }}</div>
                            <div class="participants-max">of {{ $maxPart }} spots</div>
                        </div>
                        <div class="part-bar">
                            <div class="part-fill" id="partFill" style="width:0%"></div>
                        </div>
                        <div class="part-pct">{{ $partPct }}% capacity filled</div>
                        <div class="mini-stats">
                            <div class="mini-stat">
                                <div class="mini-stat-val">{{ $registered }}</div>
                                <div class="mini-stat-lbl">Registered</div>
                            </div>
                            <div class="mini-stat">
                                <div class="mini-stat-val">{{ max(0, $maxPart - $registered) }}</div>
                                <div class="mini-stat-lbl">Available</div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

            </div>{{-- /.left --}}

            {{-- ═════ RIGHT ═════ --}}
            <div class="right-col">

                {{-- Fundraising --}}
                @if($goalAmount > 0)
                <div class="card" style="animation-delay:.08s">
                    <div class="card-header">
                        <div class="card-header-left">
                            <div class="card-icon ic-indigo">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <div class="card-title">Fundraising</div>
                                <div class="card-sub">Event progress</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="prog-numbers">
                            <div class="prog-raised">₹{{ number_format($raised) }}</div>
                            <div class="prog-goal">of ₹{{ number_format($goalAmount) }}</div>
                        </div>
                        <div class="prog-bar">
                            <div class="prog-fill" id="progFill" style="width:0%"></div>
                        </div>
                        <div class="prog-pct">{{ $percentage }}% funded</div>
                        <div class="mini-stats">
                            <div class="mini-stat">
                                <div class="mini-stat-val">{{ $percentage }}%</div>
                                <div class="mini-stat-lbl">Completed</div>
                            </div>
                            <div class="mini-stat">
                                <div class="mini-stat-val" style="font-size:14px;">₹{{ number_format($remaining) }}</div>
                                <div class="mini-stat-lbl">Remaining</div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Actions --}}
                <div class="card" style="animation-delay:.14s">
                    <div class="card-header">
                        <div class="card-header-left">
                            <div class="card-icon ic-green">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            </div>
                            <div>
                                <div class="card-title">Actions</div>
                                <div class="card-sub">Manage this event</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <a href="{{ route('events.edit', $event->id) }}" class="action-btn btn-accent">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Edit Event
                        </a>
                        <a href="{{ route('campaign.show', $event->campaign->id) }}" class="action-btn">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            Back to Campaign
                        </a>
                        <a href="{{ route('dashboard') }}" class="action-btn">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5M12 5l-7 7 7 7"/></svg>
                            Dashboard
                        </a>
                    </div>
                </div>

                {{-- Event Info --}}
                <div class="card" style="animation-delay:.20s">
                    <div class="card-header">
                        <div class="card-header-left">
                            <div class="card-icon ic-pink">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            </div>
                            <div><div class="card-title">Event Info</div></div>
                        </div>
                    </div>
                    <div class="card-body" style="display:flex;flex-direction:column;">
                        <div class="info-row">
                            <span class="info-row-label">Status</span>
                            <span class="status-chip {{ $chipClass }}" style="font-size:10px;padding:3px 9px;"><span class="dot"></span>{{ $chipLabel }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-row-label">Date</span>
                            <span class="info-row-val">{{ \Carbon\Carbon::parse($event->event_date)->format('d M Y') }}</span>
                        </div>
                        @if($event->start_time)
                        <div class="info-row">
                            <span class="info-row-label">Start</span>
                            <span class="info-row-val">{{ \Carbon\Carbon::parse($event->start_time)->format('h:i A') }}</span>
                        </div>
                        @endif
                        @if($event->end_time)
                        <div class="info-row">
                            <span class="info-row-label">End</span>
                            <span class="info-row-val">{{ \Carbon\Carbon::parse($event->end_time)->format('h:i A') }}</span>
                        </div>
                        @endif
                        @if($event->location ?? null)
                        <div class="info-row">
                            <span class="info-row-label">Location</span>
                            <span class="info-row-val" style="font-size:11px;max-width:150px;text-align:right;">{{ $event->location }}</span>
                        </div>
                        @endif
                        @if($maxPart > 0)
                        <div class="info-row">
                            <span class="info-row-label">Capacity</span>
                            <span class="info-row-val">{{ $registered }} / {{ $maxPart }}</span>
                        </div>
                        @endif
                        <div class="info-row">
                            <span class="info-row-label">Campaign</span>
                            <span class="info-row-val" style="font-size:11px;max-width:140px;text-align:right;color:var(--accent);">{{ Str::limit($event->campaign->title, 25) }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-row-label">Created</span>
                            <span class="info-row-val">{{ $event->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>

            </div>{{-- /.right-col --}}
        </div>{{-- /.page-grid --}}
    </div>{{-- /.body --}}
</div>{{-- /.main --}}
</div>{{-- /.shell --}}

<script>
(function(){
'use strict';

/* ── THEME ── */
var html   = document.documentElement;
var toggle = document.getElementById('themeToggle');
var saved  = localStorage.getItem('theme') || 'light';
if (saved === 'dark') { html.setAttribute('data-theme','dark'); toggle.checked = true; }
toggle.addEventListener('change', function(){
    var t = this.checked ? 'dark' : 'light';
    html.setAttribute('data-theme', t);
    localStorage.setItem('theme', t);
});

/* ── HAMBURGER ── */
var sidebar   = document.getElementById('sidebar');
var hamburger = document.getElementById('hamburger');
hamburger.addEventListener('click', function(e){
    e.stopPropagation();
    sidebar.classList.toggle('open');
});
document.addEventListener('click', function(e){
    if (window.innerWidth <= 860 && !sidebar.contains(e.target) && e.target !== hamburger)
        sidebar.classList.remove('open');
});

/* ── ANIMATE PROGRESS BARS ON SCROLL INTO VIEW ── */
function animateBars(){
    var pf = document.getElementById('progFill');
    var ptf = document.getElementById('partFill');
    if (pf) {
        setTimeout(function(){ pf.style.width = '{{ $percentage }}%'; }, 400);
    }
    if (ptf) {
        setTimeout(function(){ ptf.style.width = '{{ $partPct }}%'; }, 500);
    }
}

if ('IntersectionObserver' in window) {
    var obs = new IntersectionObserver(function(entries){
        entries.forEach(function(e){ if(e.isIntersecting){ animateBars(); obs.disconnect(); } });
    }, { threshold: 0.2 });
    var card = document.querySelector('.card');
    if (card) obs.observe(card);
} else {
    setTimeout(animateBars, 600);
}

})();
</script>
</body>
</html>