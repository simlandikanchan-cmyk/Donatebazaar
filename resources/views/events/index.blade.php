{{-- resources/views/events/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Events — DonateBazaar')

@section('content')

<style>
@import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap');

:root {
    --bg:           #f4f5fb;
    --surface:      #ffffff;
    --surface2:     #f8f9fe;
    --surface3:     #f0f1fa;
    --border:       rgba(0,0,0,0.06);
    --border2:      rgba(0,0,0,0.10);
    --text:         #0f1117;
    --text2:        #4b5563;
    --text3:        #9ca3af;
    --accent:       #2563eb;
    --accent2:      #0d9488;
    --accent-glow:  rgba(37,99,235,0.18);
    --green:        #16a34a;
    --yellow:       #f59e0b;
    --red:          #ef4444;
    --blue:         #3b82f6;
    --font:         'DM Sans', sans-serif;
    --font-mono:    'DM Mono', monospace;
    --radius:       14px;
    --radius-sm:    10px;
    --radius-lg:    24px;
    --shadow:       0 1px 3px rgba(0,0,0,0.06), 0 4px 16px rgba(0,0,0,0.04);
    --shadow-md:    0 4px 24px rgba(0,0,0,0.08);
    --shadow-lg:    0 8px 40px rgba(0,0,0,0.12);
    --transition:   0.22s cubic-bezier(0.4,0,0.2,1);
}

/* ── HERO ── */
.ev-hero {
    position:relative;
    width:100%;
    min-height:68vh;
    overflow:hidden;
    display:flex;
    flex-direction:column;
}
.ev-hero-bg {
    position:absolute;
    inset:0;
    z-index:0;
}
.ev-hero-bg img {
    width:100%;height:100%;
    object-fit:cover;
    object-position:center 30%;
}
.ev-hero-overlay {
    position:absolute;
    inset:0;
    z-index:1;
    background:linear-gradient(110deg, rgba(5,5,20,.95) 0%, rgba(10,10,35,.88) 50%, rgba(15,15,40,.65) 100%);
}
.ev-hero-grid {
    position:absolute;
    inset:0;
    z-index:1;
    background-image:linear-gradient(rgba(37,99,235,.06) 1px,transparent 1px), linear-gradient(90deg,rgba(37,99,235,.06) 1px,transparent 1px);
    background-size:60px 60px;
    opacity:.5;
    pointer-events:none;
}
.ev-hero-inner {
    position:relative;
    z-index:2;
    display:flex;
    flex-direction:column;
    min-height:68vh;
}
.ev-hero-content {
    flex:1;
    display:flex;
    flex-direction:column;
    justify-content:center;
    max-width:1180px;
    margin:0 auto;
    padding:100px 24px 80px;
    width:100%;
}
.ev-hero-tag {
    display:inline-flex;
    align-items:center;
    gap:10px;
    background:rgba(255,255,255,.09);
    border:1px solid rgba(255,255,255,.2);
    backdrop-filter:blur(12px);
    border-radius:100px;
    padding:8px 20px;
    font-size:11.5px;
    font-weight:600;
    letter-spacing:.1em;
    text-transform:uppercase;
    color:rgba(255,255,255,.85);
    width:fit-content;
    margin-bottom:24px;
    font-family:var(--font-mono);
}
.ev-hero-tag span {
    width:7px;height:7px;border-radius:50%;
    background:var(--green);
    animation:pulse-live 2s ease infinite;
    flex-shrink:0;
}
@keyframes pulse-live {
    0%,100%{opacity:1;box-shadow:0 0 0 0 rgba(16,185,129,.5)}
    50%{opacity:.8;box-shadow:0 0 0 6px rgba(16,185,129,0)}
}
.ev-hero h1 {
    font-family:var(--font-mono);
    font-size:clamp(2.8rem,5.5vw,4.2rem);
    font-weight:500;
    color:#fff;
    line-height:1.05;
    margin-bottom:20px;
    max-width:680px;
    letter-spacing:0;
}
.ev-hero h1 em {
    font-style:normal;
    color:var(--accent);
}
.ev-hero-sub {
    font-size:clamp(15px,1.8vw,17px);
    color:rgba(255,255,255,.65);
    font-weight:300;
    line-height:1.8;
    max-width:520px;
    margin-bottom:36px;
}
.ev-hero-stats {
    display:flex;
    gap:32px;
    flex-wrap:wrap;
}
.ev-stat {
    display:flex;
    flex-direction:column;
    gap:3px;
}
.ev-stat-num {
    font-family:var(--font-mono);
    font-size:2rem;
    font-weight:700;
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
    background:var(--bg);
    min-height:60vh;
    padding:0 0 80px;
}
.ev-container {
    max-width:1200px;
    margin:0 auto;
    padding:0 24px;
}

/* ── TOOLBAR (search + sort) ── */
.ev-toolbar {
    display:flex;
    gap:12px;
    align-items:center;
    padding:24px 0 4px;
    flex-wrap:wrap;
}
.ev-search {
    position:relative;
    flex:1;
    min-width:220px;
}
.ev-search svg {
    position:absolute;
    left:14px;
    top:50%;
    transform:translateY(-50%);
    width:16px;height:16px;
    color:var(--text3);
    pointer-events:none;
}
.ev-search input {
    width:100%;
    padding:12px 38px 12px 42px;
    border-radius:100px;
    border:1.5px solid var(--border2);
    background:var(--surface);
    font-family:'DM Sans',sans-serif;
    font-size:14px;
    color:var(--text);
    outline:none;
    transition:all var(--transition);
    box-shadow:var(--shadow);
}
.ev-search input::placeholder{color:var(--text3);}
.ev-search input:focus {
    border-color:var(--accent);
    box-shadow:0 0 0 4px var(--accent-glow);
}
.ev-search-clear {
    position:absolute;
    right:10px;
    top:50%;
    transform:translateY(-50%);
    width:24px;height:24px;
    border-radius:50%;
    border:none;
    background:var(--surface2);
    color:var(--text2);
    font-size:16px;
    line-height:1;
    cursor:pointer;
    display:flex;
    align-items:center;
    justify-content:center;
    transition:all var(--transition);
}
.ev-search-clear:hover{background:var(--border2);color:var(--text);}

.ev-sort {
    display:flex;
    align-items:center;
    gap:8px;
    background:var(--surface);
    border:1.5px solid var(--border2);
    border-radius:100px;
    padding:6px 14px;
    box-shadow:var(--shadow);
}
.ev-sort-label {
    font-family:'DM Mono',monospace;
    font-size:10px;
    text-transform:uppercase;
    letter-spacing:.1em;
    color:var(--text3);
}
.ev-sort select {
    border:none;
    background:transparent;
    font-family:'DM Sans',sans-serif;
    font-size:13px;
    font-weight:500;
    color:var(--text);
    outline:none;
    cursor:pointer;
    padding-right:4px;
}

/* ── CATEGORY FILTER ── */
.ev-filter-wrap {
    padding:20px 0 24px;
    position:sticky;
    top:0;
    z-index:100;
    background:var(--bg);
    border-bottom:1px solid var(--border);
    margin-bottom:12px;
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
    color:var(--text2);
    cursor:pointer;
    transition:all var(--transition);
    white-space:nowrap;
    text-decoration:none;
    flex-shrink:0;
}
.ev-filter-btn:hover {
    border-color:var(--accent);
    color:var(--accent);
    background:var(--accent-glow);
}
.ev-filter-btn.active {
    background:linear-gradient(135deg,var(--accent),var(--accent2));
    border-color:transparent;
    color:#fff;
    box-shadow:0 4px 14px rgba(37,99,235,.35);
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
    color:var(--text3);
}

.ev-result-count {
    font-family:'DM Mono',monospace;
    font-size:11px;
    color:var(--text3);
    text-transform:uppercase;
    letter-spacing:.08em;
    padding:8px 2px 0;
}

/* ── PERIOD FILTER ── */
.ev-period-wrap {
    display:flex;
    gap:6px;
    padding:0 0 4px;
    flex-wrap:wrap;
}
.ev-period-btn {
    padding:7px 18px;
    border-radius:100px;
    border:1.5px solid var(--border2);
    background:var(--surface);
    font-family:'DM Sans',sans-serif;
    font-size:12.5px;
    font-weight:500;
    color:var(--text2);
    cursor:pointer;
    transition:all var(--transition);
    white-space:nowrap;
}
.ev-period-btn:hover {
    border-color:var(--accent);
    color:var(--accent);
    background:var(--accent-glow);
}
.ev-period-btn.active {
    background:linear-gradient(135deg,var(--accent),var(--accent2));
    border-color:transparent;
    color:#fff;
    box-shadow:0 4px 14px rgba(37,99,235,.35);
}

/* ── SMOOTH CARD TRANSITIONS ── */
.ev-card {
    transition:transform var(--transition), box-shadow var(--transition), border-color var(--transition), opacity .35s ease, transform .35s ease;
}
.ev-card--hidden {
    display:none;
}
.ev-card--fade {
    opacity:0;
    transform:translateY(12px) scale(.97);
    pointer-events:none;
}

/* ── DESCRIPTION PREVIEW ── */
.ev-card-desc {
    font-size:13px;
    color:var(--text3);
    line-height:1.5;
    margin-bottom:12px;
    display:-webkit-box;
    -webkit-line-clamp:2;
    -webkit-box-orient:vertical;
    overflow:hidden;
}

/* ── TIME REMAINING ── */
.ev-time-remaining {
    display:inline-flex;
    align-items:center;
    gap:4px;
    font-size:10.5px;
    font-family:'DM Mono',monospace;
    color:var(--accent2);
    padding:3px 10px;
    border-radius:100px;
    background:rgba(13,148,136,.08);
    margin-top:4px;
}
.ev-time-remaining svg{width:10px;height:10px;}

/* ── SECTION HEADING ── */
.ev-section-head {
    display:flex;
    align-items:center;
    gap:16px;
    margin:32px 0 24px;
}
.ev-section-title {
    font-family:var(--font-mono);
    font-size:1.2rem;
    font-weight:700;
    color:var(--text);
    letter-spacing:-.01em;
}
.ev-section-line {
    flex:1;
    height:1px;
    background:var(--border2);
}
.ev-section-count {
    font-family:'DM Mono',monospace;
    font-size:11px;
    color:var(--text3);
}

/* ── GRID ── */
.ev-grid {
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(320px,1fr));
    gap:24px;
    margin-bottom:24px;
}

/* ── CARD ── */
.ev-card {
    background:var(--surface);
    border:1.5px solid var(--border2);
    border-radius:var(--radius-lg);
    overflow:hidden;
    box-shadow:var(--shadow);
    transition:transform var(--transition), box-shadow var(--transition), border-color var(--transition), opacity .3s ease;
    display:flex;
    flex-direction:column;
    text-decoration:none;
    color:inherit;
    animation:cardIn .4s ease both;
    cursor:pointer;
    outline:none;
}
@keyframes cardIn{from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:none}}
.ev-card:hover {
    box-shadow:0 24px 60px rgba(37,99,235,.13);
    transform:translateY(-6px);
    border-color:rgba(37,99,235,.28);
}
.ev-card:focus-visible {
    border-color:var(--accent);
    box-shadow:var(--shadow-lg);
}
.ev-card--hidden{display:none;}
.ev-section--empty{display:none;}

/* cover */
.ev-card-cover {
    position:relative;
    height:200px;
    overflow:hidden;
    background:var(--surface2);
    flex-shrink:0;
}
.ev-card-cover::after {
    content:'';
    position:absolute;
    inset:0;
    background:linear-gradient(135deg, rgba(0,0,0,.35) 0%, transparent 60%);
    pointer-events:none;
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
    color:var(--text3);
    opacity:.3;
}

/* date badge */
.ev-date-badge {
    position:absolute;
    top:14px;left:14px;
    background:rgba(255,255,255,.93);
    backdrop-filter:blur(8px);
    color:var(--text);
    border-radius:10px;
    padding:8px 12px;
    text-align:center;
    min-width:54px;
    box-shadow:0 4px 14px rgba(0,0,0,.12);
    border:1px solid rgba(37,99,235,.15);
}
.ev-date-day {
    font-family:var(--font-mono);
    font-size:1.3rem;
    font-weight:700;
    line-height:1;
}
.ev-date-mon {
    font-family:'DM Mono',monospace;
    font-size:10px;
    text-transform:uppercase;
    letter-spacing:.12em;
    opacity:.85;
    margin-top:3px;
}
.ev-date-yr {
    font-family:'DM Mono',monospace;
    font-size:9px;
    opacity:.6;
    margin-top:1px;
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
.ev-status-pending {background:var(--yellow);color:#fff;}
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
    font-family:'DM Sans',sans-serif;
    font-size:1.1rem;
    font-weight:800;
    color:var(--text);
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
    color:var(--text3);
    font-family:'DM Sans',sans-serif;
    min-width:0;
}
.ev-card-meta-item svg {
    width:13px;height:13px;
    flex-shrink:0;
}
.ev-card-meta-item .ev-loc {
    overflow:hidden;
    text-overflow:ellipsis;
    white-space:nowrap;
    max-width:160px;
}

/* goal bar */
.ev-goal-wrap { margin-bottom:16px; }
.ev-goal-label {
    display:flex;
    justify-content:space-between;
    font-size:11px;
    font-family:'DM Mono',monospace;
    color:var(--text3);
    margin-bottom:5px;
}
.ev-goal-label span:first-child { color:var(--text2); font-weight:500; }
.ev-goal-bar {
    height:6px;
    background:var(--surface3);
    border-radius:3px;
    overflow:hidden;
}
.ev-goal-fill {
    height:100%;
    border-radius:3px;
    background:linear-gradient(90deg,var(--accent),var(--accent2));
    transition:width .9s cubic-bezier(.4,0,.2,1);
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
    color:var(--text3);
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
    background:linear-gradient(135deg,var(--accent),var(--accent2));
    color:#fff;
    font-family:'DM Sans',sans-serif;
    font-size:12px;
    font-weight:600;
    text-decoration:none;
    transition:all var(--transition);
    border:none;
    cursor:pointer;
    white-space:nowrap;
    box-shadow:0 4px 14px rgba(37,99,235,.35);
}
.ev-reg-btn:hover {
    opacity:.94;
    transform:translateY(-2px);
    box-shadow:0 12px 32px rgba(37,99,235,.55);
}
.ev-reg-btn svg{width:12px;height:12px;}
.ev-reg-btn.disabled {
    background:var(--surface2);
    color:var(--text3);
    cursor:not-allowed;
    pointer-events:none;
}

/* ── EMPTY / NO RESULTS ── */
.ev-empty, .ev-no-results {
    grid-column:1/-1;
    text-align:center;
    padding:60px 20px;
    color:var(--text3);
}
.ev-empty svg, .ev-no-results svg {
    width:48px;height:48px;
    opacity:.25;
    margin:0 auto 16px;
    display:block;
}
.ev-empty-title {
    font-family:var(--font-mono);
    font-size:1.1rem;
    font-weight:600;
    color:var(--text2);
    margin-bottom:6px;
}
.ev-empty-sub { font-size:13px; }
.ev-no-results .ev-reg-btn { display:inline-flex; margin-top:18px; }

/* ── HIDDEN CLASS for JS filter ── */
.ev-category-section.hidden { display:none; }

@media(max-width:640px){
    .ev-hero-content{padding:64px 20px 48px;}
    .ev-grid{grid-template-columns:1fr;}
    .ev-hero-stats{gap:20px;}
    .ev-toolbar{flex-direction:column;align-items:stretch;}
    .ev-sort{justify-content:space-between;}
    .ev-period-wrap{flex-wrap:nowrap;overflow-x:auto;padding-bottom:6px;scrollbar-width:none;}
    .ev-period-wrap::-webkit-scrollbar{display:none;}
    .ev-period-btn{flex-shrink:0;}
}
</style>

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
            <button class="ev-period-btn" data-period="today" onclick="filterPeriod('today', this)">Today</button>
            <button class="ev-period-btn" data-period="week" onclick="filterPeriod('week', this)">This Week</button>
            <button class="ev-period-btn" data-period="month" onclick="filterPeriod('month', this)">This Month</button>
            <button class="ev-period-btn active" data-period="all" onclick="filterPeriod('all', this)">All Upcoming</button>
        </div>

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
                         style="animation-delay:{{ $loop->index * 0.06 }}s;">

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
                                <a href="{{ route('events.show', $event->id) }}" class="ev-reg-btn" style="background:var(--surface2);color:var(--text2);">
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

        {{-- ── NO SEARCH RESULTS (client-side) ── --}}
        <div class="ev-no-results" id="evNoResults" hidden>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <circle cx="11" cy="11" r="7"/>
                <path stroke-linecap="round" d="M21 21l-4.3-4.3"/>
            </svg>
            <div class="ev-empty-title">No matching events</div>
            <div class="ev-empty-sub">Try adjusting your search, category, or time period.</div>
            <button type="button" class="ev-reg-btn" onclick="resetFilters()">Clear filters</button>
        </div>

    </div>
</div>

<script>
(function () {
    const searchInput = document.getElementById('evSearch');
    const clearBtn    = document.getElementById('evClear');
    const sortSel     = document.getElementById('evSort');
    const countEl     = document.getElementById('evCount');
    const noResults   = document.getElementById('evNoResults');
    let activeCategory = 'all';
    let activePeriod = 'all';

    function sortKey(el) {
        return (el.dataset.date || '0000-00-00') + ' ' + (el.dataset.time || '00:00');
    }

    function animateCard(card, show) {
        if (show) {
            card.classList.remove('ev-card--hidden');
            requestAnimationFrame(function() {
                card.classList.remove('ev-card--fade');
            });
        } else {
            card.classList.add('ev-card--fade');
            setTimeout(function() {
                card.classList.add('ev-card--hidden');
                card.classList.remove('ev-card--fade');
            }, 300);
        }
    }

    function applyFilters() {
        const term = (searchInput.value || '').trim().toLowerCase();
        let total = 0;

        document.querySelectorAll('.ev-category-section').forEach(section => {
            if (activeCategory !== 'all' && section.dataset.cat !== activeCategory) {
                section.classList.add('hidden');
                return;
            }
            section.classList.remove('hidden');

            const grid  = section.querySelector('.ev-grid');
            const cards = Array.from(grid.querySelectorAll('.ev-card'));
            const dir   = sortSel.value;

            cards.sort((a, b) => {
                const cmp = sortKey(a).localeCompare(sortKey(b));
                return dir === 'date-desc' ? -cmp : cmp;
            });
            cards.forEach(c => grid.appendChild(c));

            let visible = 0;
            cards.forEach(function(card) {
                const hay = (card.dataset.title + ' ' + card.dataset.location).toLowerCase();
                const matchesSearch = !term || hay.includes(term);
                const matchesPeriod = activePeriod === 'all' || card.dataset.period === activePeriod;
                const show = matchesSearch && matchesPeriod;
                animateCard(card, show);
                if (show) visible++;
            });

            section.classList.toggle('ev-section--empty', visible === 0);
            total += visible;
        });

        countEl.textContent = total + (total === 1 ? ' event' : ' events');
        noResults.hidden = total !== 0;
        clearBtn.hidden = term === '';
    }

    window.filterCat = function (catId, btn) {
        document.querySelectorAll('.ev-filter-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        activeCategory = catId;
        applyFilters();
    };

    window.filterPeriod = function (period, btn) {
        document.querySelectorAll('.ev-period-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        activePeriod = period;
        applyFilters();
    };

    window.resetFilters = function () {
        searchInput.value = '';
        activeCategory = 'all';
        activePeriod = 'all';
        sortSel.value = 'date-asc';
        document.querySelectorAll('.ev-filter-btn').forEach(b =>
            b.classList.toggle('active', b.dataset.cat === 'all'));
        document.querySelectorAll('.ev-period-btn').forEach(b =>
            b.classList.toggle('active', b.dataset.period === 'all'));
        applyFilters();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    };

    if (searchInput) searchInput.addEventListener('input', applyFilters);
    if (sortSel)     sortSel.addEventListener('change', applyFilters);
    if (clearBtn)    clearBtn.addEventListener('click', function () {
        searchInput.value = '';
        applyFilters();
        searchInput.focus();
    });

    document.querySelectorAll('.ev-card').forEach(function (card) {
        card.addEventListener('click', function (e) {
            if (e.target.closest('a, button, select, input')) return;
            window.location.href = card.dataset.url;
        });
        card.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                window.location.href = card.dataset.url;
            }
        });
    });

    applyFilters();
})();
</script>

@endsection
