{{-- resources/views/events/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Events — DonateBazaar')

@section('content')

<style>
/* ── FONTS ── */
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800;900&family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap');

:root {
    --cream:#faf8f4;
    --ink:#0f0d0a;
    --ink2:#3d3830;
    --ink3:#8c8478;
    --accent:#c8502a;
    --accent2:#e8855f;
    --accent-lt:rgba(200,80,42,.08);
    --green:#1a7a52;
    --green-lt:rgba(26,122,82,.09);
    --gold:#b8963e;
    --gold-lt:rgba(184,150,62,.1);
    --surface:#ffffff;
    --surface2:#f5f2ed;
    --border:rgba(15,13,10,.08);
    --border2:rgba(15,13,10,.14);
    --r:16px;
    --r-sm:10px;
    --sh:0 2px 12px rgba(15,13,10,.06),0 1px 3px rgba(15,13,10,.04);
    --sh-hover:0 12px 40px rgba(15,13,10,.14),0 4px 12px rgba(15,13,10,.08);
    --ease:.22s cubic-bezier(.4,0,.2,1);
}

/* ── HERO ── */
.ev-hero {
    background: var(--ink);
    padding: 80px 0 60px;
    position: relative;
    overflow: hidden;
}
.ev-hero::before {
    content:'';
    position:absolute;
    inset:0;
    background:
        radial-gradient(ellipse 60% 80% at 80% 20%, rgba(200,80,42,.18) 0%, transparent 60%),
        radial-gradient(ellipse 40% 60% at 10% 80%, rgba(184,150,62,.12) 0%, transparent 55%);
}
.ev-hero-inner {
    max-width:1200px;
    margin:0 auto;
    padding:0 24px;
    position:relative;
    z-index:1;
}
.ev-hero-tag {
    display:inline-flex;
    align-items:center;
    gap:8px;
    background:rgba(255,255,255,.06);
    border:1px solid rgba(255,255,255,.12);
    border-radius:100px;
    padding:6px 16px;
    font-family:'DM Mono',monospace;
    font-size:11px;
    color:rgba(255,255,255,.55);
    letter-spacing:.12em;
    text-transform:uppercase;
    margin-bottom:20px;
}
.ev-hero-tag span {
    width:6px;height:6px;border-radius:50%;
    background:var(--accent2);
    animation:pulse 2s ease infinite;
}
@keyframes pulse {
    0%,100%{opacity:1;transform:scale(1);}
    50%{opacity:.5;transform:scale(1.4);}
}
.ev-hero h1 {
    font-family:'DM MONO',Monospace;
    font-size:clamp(2.4rem,5vw,4rem);
    font-weight:900;
    color:#fff;
    line-height:1.1;
    letter-spacing:-.02em;
    margin-bottom:16px;
}
.ev-hero h1 em {
    font-style:normal;
    color:var(--accent2);
}
.ev-hero-sub {
    font-family:'DM Sans',sans-serif;
    font-size:16px;
    color:rgba(255,255,255,.5);
    max-width:480px;
    line-height:1.65;
}
.ev-hero-stats {
    display:flex;
    gap:32px;
    margin-top:40px;
    flex-wrap:wrap;
}
.ev-stat {
    display:flex;
    flex-direction:column;
    gap:3px;
}
.ev-stat-num {
    font-family:;
    font-size:2rem;
    font-weight:800;
    color:#fff;
    line-height:1;
}
.ev-stat-lbl {
    font-size:11px;
    color:rgba(255,255,255,.4);
    font-family:'DM Mono',monospace;
    text-transform:uppercase;
    letter-spacing:.1em;
}

/* ── MAIN BODY ── */
.ev-body {
    background:var(--cream);
    min-height:60vh;
    padding:0 0 80px;
}
.ev-container {
    max-width:1200px;
    margin:0 auto;
    padding:0 24px;
}

/* ── CATEGORY FILTER ── */
.ev-filter-wrap {
    padding:28px 0 32px;
    position:sticky;
    top:0;
    z-index:100;
    background:var(--cream);
    border-bottom:1px solid var(--border);
    margin-bottom:40px;
}
.ev-filter-scroll {
    display:flex;
    gap:8px;
    overflow-x:auto;
    padding-bottom:2px;
    scrollbar-width:none;
}
.ev-filter-scroll::-webkit-scrollbar{display:none;}
.ev-filter-btn {
    display:inline-flex;
    align-items:center;
    gap:8px;
    padding:9px 20px;
    border-radius:100px;
    border:1.5px solid var(--border2);
    background:var(--surface);
    font-family:'DM Sans',sans-serif;
    font-size:13px;
    font-weight:500;
    color:var(--ink2);
    cursor:pointer;
    transition:all var(--ease);
    white-space:nowrap;
    text-decoration:none;
    flex-shrink:0;
}
.ev-filter-btn:hover {
    border-color:var(--accent);
    color:var(--accent);
    background:var(--accent-lt);
}
.ev-filter-btn.active {
    background:var(--ink);
    border-color:var(--ink);
    color:#fff;
}
.ev-filter-count {
    font-family:'DM Mono',monospace;
    font-size:10px;
    padding:2px 7px;
    border-radius:100px;
    background:rgba(255,255,255,.15);
    color:inherit;
    opacity:.7;
}
.ev-filter-btn:not(.active) .ev-filter-count {
    background:var(--surface2);
    color:var(--ink3);
}

/* ── SECTION HEADING ── */
.ev-section-head {
    display:flex;
    align-items:center;
    gap:16px;
    margin-bottom:24px;
}
.ev-section-title {
    font-family:;
    font-size:1.5rem;
    font-weight:800;
    color:var(--ink);
    letter-spacing:-.02em;
}
.ev-section-line {
    flex:1;
    height:1px;
    background:var(--border2);
}
.ev-section-count {
    font-family:'DM Mono',monospace;
    font-size:11px;
    color:var(--ink3);
}

/* ── GRID ── */
.ev-grid {
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(320px,1fr));
    gap:24px;
    margin-bottom:56px;
}

/* ── CARD ── */
.ev-card {
    background:var(--surface);
    border:1px solid var(--border);
    border-radius:var(--r);
    overflow:hidden;
    box-shadow:var(--sh);
    transition:all var(--ease);
    display:flex;
    flex-direction:column;
    text-decoration:none;
    color:inherit;
    animation:cardIn .4s ease both;
}
@keyframes cardIn{from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:none}}
.ev-card:hover {
    box-shadow:var(--sh-hover);
    transform:translateY(-4px);
    border-color:rgba(15,13,10,.16);
}

/* cover */
.ev-card-cover {
    position:relative;
    height:200px;
    overflow:hidden;
    background:var(--surface2);
    flex-shrink:0;
}
.ev-card-cover img {
    width:100%;height:100%;
    object-fit:cover;
    transition:transform .5s ease;
}
.ev-card:hover .ev-card-cover img {
    transform:scale(1.04);
}
.ev-card-cover-placeholder {
    width:100%;height:100%;
    display:flex;align-items:center;justify-content:center;
    background:linear-gradient(135deg,var(--surface2),#ede9e0);
}
.ev-card-cover-placeholder svg {
    width:40px;height:40px;
    color:var(--ink3);
    opacity:.3;
}

/* date badge */
.ev-date-badge {
    position:absolute;
    top:14px;left:14px;
    background:var(--ink);
    color:#fff;
    border-radius:10px;
    padding:8px 12px;
    text-align:center;
    min-width:52px;
    box-shadow:0 4px 14px rgba(0,0,0,.25);
}
.ev-date-day {
    font-family:;
    font-size:1.4rem;
    font-weight:900;
    line-height:1;
}
.ev-date-mon {
    font-family:'DM Mono',monospace;
    font-size:9px;
    text-transform:uppercase;
    letter-spacing:.1em;
    opacity:.65;
    margin-top:2px;
}

/* status badge */
.ev-status {
    position:absolute;
    top:14px;right:14px;
    padding:4px 10px;
    border-radius:100px;
    font-family:'DM Mono',monospace;
    font-size:10px;
    font-weight:500;
    letter-spacing:.06em;
}
.ev-status-active  {background:rgba(26,122,82,.9);color:#fff;}
.ev-status-pending {background:rgba(184,150,62,.9);color:#fff;}
.ev-status-expired {background:rgba(100,100,100,.8);color:#fff;}

/* card body */
.ev-card-body { padding:20px 20px 0; flex:1; }

.ev-card-cat {
    display:inline-flex;
    align-items:center;
    gap:5px;
    font-family:'DM Mono',monospace;
    font-size:10px;
    font-weight:500;
    color:var(--accent);
    text-transform:uppercase;
    letter-spacing:.1em;
    margin-bottom:10px;
}
.ev-card-title {
    font-family:;
    font-size:1.1rem;
    font-weight:800;
    color:var(--ink);
    line-height:1.3;
    margin-bottom:10px;
    letter-spacing:-.01em;
}
.ev-card-meta {
    display:flex;
    flex-wrap:wrap;
    gap:12px;
    margin-bottom:16px;
}
.ev-card-meta-item {
    display:flex;
    align-items:center;
    gap:5px;
    font-size:12px;
    color:var(--ink3);
    font-family:'DM Sans',sans-serif;
}
.ev-card-meta-item svg {
    width:13px;height:13px;
    flex-shrink:0;
}

/* goal bar */
.ev-goal-wrap { margin-bottom:16px; }
.ev-goal-label {
    display:flex;
    justify-content:space-between;
    font-size:11px;
    font-family:'DM Mono',monospace;
    color:var(--ink3);
    margin-bottom:5px;
}
.ev-goal-label span:first-child { color:var(--ink2); font-weight:500; }
.ev-goal-bar {
    height:4px;
    background:var(--surface2);
    border-radius:100px;
    overflow:hidden;
}
.ev-goal-fill {
    height:100%;
    border-radius:100px;
    background:linear-gradient(90deg,var(--accent),var(--gold));
    transition:width .8s ease;
}

/* card footer */
.ev-card-footer {
    padding:16px 20px;
    border-top:1px solid var(--border);
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:12px;
    margin-top:auto;
}
.ev-spots {
    font-size:12px;
    color:var(--ink3);
    font-family:'DM Sans',sans-serif;
    display:flex;
    align-items:center;
    gap:4px;
}
.ev-spots svg{width:12px;height:12px;}
.ev-spots.full{color:var(--accent);}

.ev-reg-btn {
    display:inline-flex;
    align-items:center;
    gap:6px;
    padding:8px 18px;
    border-radius:100px;
    background:var(--ink);
    color:#fff;
    font-family:'DM Sans',sans-serif;
    font-size:12px;
    font-weight:600;
    text-decoration:none;
    transition:all var(--ease);
    border:none;
    cursor:pointer;
    white-space:nowrap;
}
.ev-reg-btn:hover {
    background:var(--accent);
    transform:translateY(-1px);
    box-shadow:0 4px 14px rgba(200,80,42,.35);
}
.ev-reg-btn svg{width:12px;height:12px;}
.ev-reg-btn.disabled {
    background:var(--surface2);
    color:var(--ink3);
    cursor:not-allowed;
    pointer-events:none;
}

/* ── EMPTY STATE ── */
.ev-empty {
    grid-column:1/-1;
    text-align:center;
    padding:60px 20px;
    color:var(--ink3);
}
.ev-empty svg {
    width:48px;height:48px;
    opacity:.25;
    margin:0 auto 16px;
    display:block;
}
.ev-empty-title {
    font-family:;
    font-size:1.2rem;
    color:var(--ink2);
    margin-bottom:6px;
}
.ev-empty-sub { font-size:13px; }

/* ── HIDDEN CLASS for JS filter ── */
.ev-category-section.hidden { display:none; }

@media(max-width:640px){
    .ev-hero{padding:56px 0 40px;}
    .ev-grid{grid-template-columns:1fr;}
    .ev-hero-stats{gap:20px;}
}
</style>

{{-- ── HERO ── --}}
<section class="ev-hero">
    <div class="ev-hero-inner">
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
</section>

{{-- ── BODY ── --}}
<div class="ev-body">
    <div class="ev-container">

        {{-- ── FILTER TABS ── --}}
        <div class="ev-filter-wrap">
            <div class="ev-filter-scroll">
                <button class="ev-filter-btn active" data-cat="all" onclick="filterCat('all', this)">
                    All Events
                    <span class="ev-filter-count">{{ $totalEvents }}</span>
                </button>
                @foreach($categories as $cat)
                    @if($eventsByCategory->has($cat->id))
                    <button class="ev-filter-btn" data-cat="{{ $cat->id }}" onclick="filterCat('{{ $cat->id }}', this)">
                        {{ $cat->emoji ?? '' }} {{ $cat->name }}
                        <span class="ev-filter-count">{{ $eventsByCategory[$cat->id]->count() }}</span>
                    </button>
                    @endif
                @endforeach
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
                    <div class="ev-card" style="animation-delay:{{ $loop->index * 0.06 }}s;">

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
                                <div class="ev-date-mon">{{ $event->event_date?->format('M Y') ?? '' }}</div>
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
                                <a href="{{ route('events.register', $event->id) }}" class="ev-reg-btn">
                                    Register
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            @elseif($event->hasEnded())
                                <span class="ev-reg-btn disabled">Event Ended</span>
                            @elseif($event->isFull())
                                <span class="ev-reg-btn disabled">Full</span>
                            @else
                                <a href="{{ route('events.show', $event->id) }}" class="ev-reg-btn" style="background:var(--surface2);color:var(--ink2);">
                                    View Details
                                </a>
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

    </div>
</div>

<script>
function filterCat(catId, btn) {
    // Update active button
    document.querySelectorAll('.ev-filter-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    // Show/hide sections
    document.querySelectorAll('.ev-category-section').forEach(section => {
        if (catId === 'all' || section.dataset.cat === catId) {
            section.classList.remove('hidden');
        } else {
            section.classList.add('hidden');
        }
    });

    // Scroll to top of content
    document.querySelector('.ev-filter-wrap').scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}
</script>

@endsection