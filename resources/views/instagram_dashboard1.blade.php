@extends('layouts.app')

@section('content')

<style>
@import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=DM+Serif+Display:ital@0;1&display=swap');

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
    --black:      #0a0a0a;
    --ink:        #111111;
    --ink-2:      #1a1a1a;
    --ink-3:      #222222;
    --mid:        #6b6b6b;
    --muted:      #a0a0a0;
    --faint:      #e8e8e8;
    --surface:    #f7f7f7;
    --white:      #ffffff;
    --ig-grad:    linear-gradient(45deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888);
    --ig-pink:    #e1306c;
    --ig-orange:  #f56040;
    --ig-purple:  #833ab4;
    --radius:     12px;
    --radius-lg:  20px;
    --radius-xl:  28px;
}

body { font-family: 'DM Sans', sans-serif; background: var(--surface); color: var(--ink); -webkit-font-smoothing: antialiased; }

/* ── Page shell ── */
.ig-shell {
    min-height: 100vh;
    background: var(--surface);
    padding-bottom: 60px;
}

/* ── Top profile bar (Instagram-style header) ── */
.ig-topbar {
    background: var(--white);
    border-bottom: 1px solid var(--faint);
    position: sticky;
    top: 0;
    z-index: 100;
    padding: 0 20px;
}
.ig-topbar-inner {
    max-width: 975px;
    margin: 0 auto;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
}
.ig-logo {
    font-family: 'DM Serif Display', serif;
    font-size: 22px;
    color: var(--ink);
    letter-spacing: -0.5px;
    text-decoration: none;
}
.ig-topbar-actions { display: flex; align-items: center; gap: 20px; }
.ig-icon-btn {
    width: 36px; height: 36px;
    display: flex; align-items: center; justify-content: center;
    border-radius: 50%; cursor: pointer;
    transition: background .15s;
    color: var(--ink);
    text-decoration: none;
}
.ig-icon-btn:hover { background: var(--surface); }
.ig-icon-btn svg { width: 24px; height: 24px; }
.ig-new-btn {
    display: inline-flex; align-items: center; gap: 6px;
    background: var(--ig-pink);
    color: #fff;
    padding: 8px 18px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    transition: opacity .2s, transform .15s;
}
.ig-new-btn:hover { opacity: .9; transform: translateY(-1px); }
.ig-new-btn svg { width: 14px; height: 14px; }

/* ── Main layout ── */
.ig-main {
    max-width: 975px;
    margin: 0 auto;
    padding: 32px 20px 0;
}

/* ── Profile section (Instagram-style) ── */
.ig-profile {
    display: flex;
    align-items: flex-start;
    gap: 40px;
    padding-bottom: 36px;
    border-bottom: 1px solid var(--faint);
    margin-bottom: 32px;
}

.ig-avatar-ring {
    width: 96px; height: 96px;
    border-radius: 50%;
    padding: 3px;
    background: var(--ig-grad);
    flex-shrink: 0;
}
.ig-avatar-inner {
    width: 100%; height: 100%;
    border-radius: 50%;
    background: var(--white);
    display: flex; align-items: center; justify-content: center;
    font-family: 'DM Serif Display', serif;
    font-size: 32px;
    color: var(--ink);
    border: 3px solid var(--white);
    overflow: hidden;
}

.ig-profile-info { flex: 1; }
.ig-username {
    display: flex; align-items: center; gap: 12px;
    margin-bottom: 16px; flex-wrap: wrap;
}
.ig-username h1 { font-size: 20px; font-weight: 400; color: var(--ink); }
.ig-verified {
    width: 18px; height: 18px;
    background: var(--ig-grad);
    border-radius: 50%;
    display: inline-flex; align-items: center; justify-content: center;
}
.ig-verified svg { width: 10px; height: 10px; color: #fff; }

.ig-stats-row {
    display: flex;
    gap: 36px;
    margin-bottom: 16px;
}
.ig-stat { text-align: center; }
.ig-stat-num {
    font-size: 17px;
    font-weight: 700;
    color: var(--ink);
    display: block;
}
.ig-stat-lbl {
    font-size: 13px;
    color: var(--mid);
    display: block;
    margin-top: 2px;
}

.ig-bio {
    font-size: 14px;
    line-height: 1.6;
    color: var(--ink);
}
.ig-bio span { color: var(--ig-pink); font-weight: 500; }

/* ── Story bubbles (stat categories) ── */
.ig-stories {
    display: flex;
    gap: 20px;
    padding: 4px 0 20px;
    overflow-x: auto;
    scrollbar-width: none;
    margin-bottom: 32px;
}
.ig-stories::-webkit-scrollbar { display: none; }
.ig-story {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    flex-shrink: 0;
    min-width: 72px;
}
.ig-story-ring {
    width: 66px; height: 66px;
    border-radius: 50%;
    padding: 2.5px;
    background: var(--ig-grad);
    transition: transform .2s;
}
.ig-story:hover .ig-story-ring { transform: scale(1.05); }
.ig-story.inactive .ig-story-ring { background: var(--faint); }
.ig-story-inner {
    width: 100%; height: 100%;
    border-radius: 50%;
    background: var(--white);
    border: 3px solid var(--white);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    overflow: hidden;
}
.ig-story-inner svg { width: 24px; height: 24px; color: var(--ig-pink); }
.ig-story.inactive .ig-story-inner svg { color: var(--muted); }
.ig-story-lbl { font-size: 12px; color: var(--ink); text-align: center; line-height: 1.2; max-width: 72px; }

/* ── Filter tabs (Instagram highlight-style) ── */
.ig-tabs {
    display: flex;
    border-top: 1px solid var(--faint);
    margin-bottom: 4px;
}
.ig-tab {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 14px 0;
    font-size: 12px;
    font-weight: 600;
    letter-spacing: .08em;
    text-transform: uppercase;
    color: var(--muted);
    cursor: pointer;
    border: none;
    background: transparent;
    border-top: 2px solid transparent;
    margin-top: -1px;
    transition: color .2s, border-color .2s;
    font-family: 'DM Sans', sans-serif;
}
.ig-tab:hover { color: var(--ink); }
.ig-tab.active { color: var(--ink); border-top-color: var(--ink); }
.ig-tab svg { width: 15px; height: 15px; }
.ig-tab .tab-count {
    background: var(--faint);
    color: var(--mid);
    font-size: 10px;
    padding: 1px 6px;
    border-radius: 10px;
    font-weight: 700;
}
.ig-tab.active .tab-count { background: var(--ink); color: #fff; }

/* ── Campaign grid (Instagram post grid) ── */
.ig-post-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 3px;
}
@media(max-width: 600px) {
    .ig-post-grid { grid-template-columns: repeat(2, 1fr); }
    .ig-profile { flex-direction: column; gap: 20px; }
    .ig-stats-row { gap: 20px; }
}

/* ── Post tile (the main Instagram-look card) ── */
.ig-post {
    position: relative;
    aspect-ratio: 1;
    overflow: hidden;
    cursor: pointer;
    background: var(--faint);
    animation: fadeIn .4s ease both;
}
.ig-post.ig-hidden { display: none !important; }
@keyframes fadeIn { from{opacity:0;transform:scale(.97)} to{opacity:1;transform:scale(1)} }

.ig-post img {
    width: 100%; height: 100%;
    object-fit: cover;
    display: block;
    transition: transform .4s cubic-bezier(.4,0,.2,1);
}
.ig-post:hover img { transform: scale(1.06); }

/* No-image placeholder */
.ig-post-placeholder {
    width: 100%; height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 10px;
    background: linear-gradient(135deg, #f3f0ff, #fce4ec);
}
.ig-post-placeholder svg { width: 32px; height: 32px; color: var(--ig-pink); opacity: .5; }

/* Hover overlay */
.ig-post-overlay {
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,.52);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 8px;
    opacity: 0;
    transition: opacity .3s;
    padding: 14px;
}
.ig-post:hover .ig-post-overlay { opacity: 1; }

.ig-overlay-stats {
    display: flex;
    gap: 20px;
    margin-bottom: 6px;
}
.ig-overlay-stat {
    display: flex;
    align-items: center;
    gap: 5px;
    color: #fff;
    font-size: 14px;
    font-weight: 700;
}
.ig-overlay-stat svg { width: 16px; height: 16px; }

.ig-overlay-title {
    font-size: 12px;
    color: rgba(255,255,255,.85);
    text-align: center;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    max-width: 90%;
}

/* Status pill on tile */
.ig-post-status {
    position: absolute;
    top: 8px;
    left: 8px;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: .06em;
    text-transform: uppercase;
    padding: 3px 9px;
    border-radius: 20px;
    backdrop-filter: blur(8px);
}
.ps-active   { background: rgba(16,185,129,.9); color: #fff; }
.ps-pending  { background: rgba(245,158,11,.9); color: #fff; }
.ps-rejected { background: rgba(239,68,68,.9);  color: #fff; }
.ps-paused   { background: rgba(99,102,241,.9);  color: #fff; }
.ps-default  { background: rgba(0,0,0,.5);       color: #fff; }

/* Progress bar on tile */
.ig-post-progress {
    position: absolute;
    bottom: 0; left: 0; right: 0;
    height: 3px;
    background: rgba(255,255,255,.25);
}
.ig-post-progress-fill {
    height: 100%;
    background: var(--ig-grad);
}

/* ── Modal / detail drawer ── */
.ig-modal-bg {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.72);
    z-index: 999;
    align-items: center;
    justify-content: center;
    padding: 20px;
    animation: bgFadeIn .2s ease;
}
.ig-modal-bg.open { display: flex; }
@keyframes bgFadeIn { from{opacity:0} to{opacity:1} }

.ig-modal {
    background: var(--white);
    border-radius: var(--radius-lg);
    overflow: hidden;
    width: 100%;
    max-width: 900px;
    max-height: 90vh;
    display: flex;
    animation: modalIn .25s cubic-bezier(.4,0,.2,1);
}
@keyframes modalIn { from{opacity:0;transform:scale(.95)} to{opacity:1;transform:scale(1)} }
@media(max-width:700px) { .ig-modal { flex-direction: column; } }

.ig-modal-left {
    flex: 1;
    background: var(--black);
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 300px;
    max-height: 90vh;
    overflow: hidden;
}
.ig-modal-left img {
    width: 100%; height: 100%;
    object-fit: cover;
    max-height: 90vh;
}
.ig-modal-left-placeholder {
    width: 100%; height: 100%; min-height: 300px;
    background: linear-gradient(135deg, #f3f0ff, #fce4ec);
    display: flex; align-items: center; justify-content: center;
}
.ig-modal-left-placeholder svg { width: 64px; height: 64px; color: var(--ig-pink); opacity: .3; }

.ig-modal-right {
    width: 340px;
    flex-shrink: 0;
    display: flex;
    flex-direction: column;
    overflow-y: auto;
    max-height: 90vh;
}
@media(max-width:700px) { .ig-modal-right { width: 100%; max-height: 50vh; } }

.ig-modal-header {
    padding: 16px 20px;
    border-bottom: 1px solid var(--faint);
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: sticky;
    top: 0;
    background: var(--white);
    z-index: 1;
}
.ig-modal-user { display: flex; align-items: center; gap: 10px; }
.ig-modal-avatar {
    width: 36px; height: 36px;
    border-radius: 50%;
    background: var(--ig-grad);
    display: flex; align-items: center; justify-content: center;
    color: #fff;
    font-weight: 700;
    font-size: 14px;
}
.ig-modal-username { font-size: 14px; font-weight: 600; color: var(--ink); }
.ig-modal-date     { font-size: 12px; color: var(--muted); }
.ig-modal-close {
    width: 30px; height: 30px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; color: var(--mid); transition: background .15s;
}
.ig-modal-close:hover { background: var(--faint); }
.ig-modal-close svg { width: 20px; height: 20px; }

.ig-modal-body { padding: 20px; flex: 1; overflow-y: auto; }

.ig-modal-title {
    font-family: 'DM Serif Display', serif;
    font-size: 18px;
    color: var(--ink);
    margin-bottom: 12px;
    line-height: 1.3;
}

.ig-modal-progress { margin-bottom: 20px; }
.ig-modal-progress-bar {
    height: 5px;
    background: var(--faint);
    border-radius: 3px;
    overflow: hidden;
    margin: 10px 0 6px;
}
.ig-modal-progress-fill {
    height: 100%;
    background: var(--ig-grad);
    border-radius: 3px;
    transition: width 1s cubic-bezier(.4,0,.2,1);
}
.ig-modal-progress-nums {
    display: flex;
    justify-content: space-between;
    font-size: 13px;
}
.ig-modal-progress-nums strong { color: var(--ig-pink); font-weight: 700; }
.ig-modal-progress-nums span   { color: var(--muted); }

.ig-modal-actions {
    padding: 16px 20px;
    border-top: 1px solid var(--faint);
    display: flex;
    gap: 10px;
    position: sticky;
    bottom: 0;
    background: var(--white);
}
.ig-modal-btn {
    flex: 1;
    padding: 12px;
    border-radius: var(--radius);
    font-size: 14px;
    font-weight: 600;
    text-align: center;
    text-decoration: none;
    transition: opacity .2s, transform .15s;
    display: block;
}
.ig-modal-btn:hover { opacity: .9; transform: translateY(-1px); }
.ig-modal-btn-view { background: var(--ink); color: #fff; }
.ig-modal-btn-edit { background: var(--faint); color: var(--ink); }

/* ── Reaction bar ── */
.ig-modal-reactions {
    padding: 14px 20px 0;
    display: flex;
    align-items: center;
    gap: 16px;
}
.ig-react-btn {
    display: flex; align-items: center; gap: 5px;
    color: var(--mid); font-size: 13px; font-weight: 500; cursor: pointer;
    transition: color .15s;
}
.ig-react-btn:hover { color: var(--ig-pink); }
.ig-react-btn svg { width: 22px; height: 22px; }

/* Info rows */
.ig-modal-info { display: flex; flex-direction: column; gap: 10px; }
.ig-info-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 14px;
    background: var(--surface);
    border-radius: 10px;
    font-size: 13px;
}
.ig-info-row-label { color: var(--muted); }
.ig-info-row-value { font-weight: 600; color: var(--ink); }

/* ── Empty state ── */
.ig-empty {
    display: none;
    flex-direction: column;
    align-items: center;
    padding: 80px 20px;
    text-align: center;
    grid-column: 1/-1;
}
.ig-empty-ring {
    width: 80px; height: 80px;
    border-radius: 50%;
    border: 2px solid var(--faint);
    display: flex; align-items: center; justify-content: center;
    margin-bottom: 20px;
}
.ig-empty-ring svg { width: 36px; height: 36px; color: var(--muted); }
.ig-empty h3 { font-size: 20px; font-weight: 700; color: var(--ink); margin-bottom: 8px; }
.ig-empty p  { font-size: 14px; color: var(--muted); }

/* ── Chart section ── */
.ig-chart-wrap {
    background: var(--white);
    border-radius: var(--radius-xl);
    padding: 28px;
    margin-bottom: 28px;
    border: 1px solid var(--faint);
}
.ig-chart-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 24px;
    gap: 12px;
    flex-wrap: wrap;
}
.ig-chart-title { font-size: 15px; font-weight: 700; color: var(--ink); }
.ig-chart-sub   { font-size: 13px; color: var(--muted); margin-top: 2px; }
.ig-chart-legend {
    display: flex; align-items: center; gap: 6px;
    font-size: 12px; color: var(--mid); font-weight: 500;
}
.ig-chart-legend span {
    width: 28px; height: 3px;
    border-radius: 2px;
    background: var(--ig-grad);
    display: inline-block;
}
.h-56 { height: 224px; }

/* ── No-results message ── */
#noResults { display: none; text-align: center; padding: 60px 20px; color: var(--muted); font-size: 14px; grid-column: 1/-1; }

/* ── Util ── */
@media(max-width:768px) {
    .ig-topbar-inner .ig-logo { font-size: 18px; }
    .ig-main { padding: 20px 12px 0; }
    .ig-post-grid { gap: 2px; }
}
</style>

<div class="ig-shell">

    {{-- ── Top bar ── --}}
    <header class="ig-topbar">
        <div class="ig-topbar-inner">
            <span class="ig-logo">DonateBazar</span>
            <div class="ig-topbar-actions">
                <a href="{{ route('campaign.create') }}" class="ig-icon-btn" title="New campaign">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="4"/><path d="M12 8v8M8 12h8"/></svg>
                </a>
                <a href="{{ route('campaign.create') }}" class="ig-new-btn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 4v16m8-8H4"/></svg>
                    New Campaign
                </a>
            </div>
        </div>
    </header>

    <div class="ig-main">

        {{-- ── Compute counts ── --}}
        @php
            $totalRaised  = $campaigns->sum('raised_amount');
            $totalGoal    = $campaigns->sum('goal_amount');
            $overallPct   = $totalGoal > 0 ? min(100, round(($totalRaised / $totalGoal) * 100)) : 0;
            $countAll     = $campaigns->count();
            $countActive  = $campaigns->filter(fn($c) => $c->status === 'approved' && $c->campaign_state === 'active')->count();
            $countPending = $campaigns->filter(fn($c) => $c->status === 'pending')->count();
            $countPaused  = $campaigns->filter(fn($c) => $c->campaign_state === 'paused')->count();
            $countReject  = $campaigns->filter(fn($c) => $c->status === 'rejected')->count();
            $initials     = strtoupper(substr(auth()->user()->name, 0, 1));
        @endphp

        {{-- ── Profile row ── --}}
        <div class="ig-profile">
            <div class="ig-avatar-ring">
                <div class="ig-avatar-inner">{{ $initials }}</div>
            </div>
            <div class="ig-profile-info">
                <div class="ig-username">
                    <h1>{{ auth()->user()->name }}</h1>
                    <div class="ig-verified">
                        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <div class="ig-stats-row">
                    <div class="ig-stat">
                        <span class="ig-stat-num">{{ $countAll }}</span>
                        <span class="ig-stat-lbl">Campaigns</span>
                    </div>
                    <div class="ig-stat">
                        <span class="ig-stat-num">₹{{ number_format($totalRaised, 0) }}</span>
                        <span class="ig-stat-lbl">Raised</span>
                    </div>
                    <div class="ig-stat">
                        <span class="ig-stat-num">{{ $overallPct }}%</span>
                        <span class="ig-stat-lbl">Goal Met</span>
                    </div>
                    <div class="ig-stat">
                        <span class="ig-stat-num">{{ $countActive }}</span>
                        <span class="ig-stat-lbl">Active</span>
                    </div>
                </div>
                <div class="ig-bio">
                    Fundraiser on DonateBazar &nbsp;·&nbsp;
                    <span>{{ $countActive }} active campaign{{ $countActive !== 1 ? 's' : '' }}</span><br>
                    ₹{{ number_format($totalGoal, 0) }} total goal &nbsp;·&nbsp; {{ $overallPct }}% achieved
                </div>
            </div>
        </div>

        {{-- ── Story bubbles (stat highlights) ── --}}
        <div class="ig-stories">
            <div class="ig-story {{ $countActive > 0 ? '' : 'inactive' }}" onclick="setFilter('active')">
                <div class="ig-story-ring">
                    <div class="ig-story-inner">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <span class="ig-story-lbl">Active<br>{{ $countActive }}</span>
            </div>
            <div class="ig-story {{ $countPending > 0 ? '' : 'inactive' }}" onclick="setFilter('pending')">
                <div class="ig-story-ring">
                    <div class="ig-story-inner">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    </div>
                </div>
                <span class="ig-story-lbl">Pending<br>{{ $countPending }}</span>
            </div>
            <div class="ig-story {{ $countPaused > 0 ? '' : 'inactive' }}" onclick="setFilter('paused')">
                <div class="ig-story-ring">
                    <div class="ig-story-inner">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/></svg>
                    </div>
                </div>
                <span class="ig-story-lbl">Paused<br>{{ $countPaused }}</span>
            </div>
            <div class="ig-story {{ $countReject > 0 ? '' : 'inactive' }}" onclick="setFilter('rejected')">
                <div class="ig-story-ring">
                    <div class="ig-story-inner">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                    </div>
                </div>
                <span class="ig-story-lbl">Rejected<br>{{ $countReject }}</span>
            </div>
            <div class="ig-story" onclick="setFilter('all')">
                <div class="ig-story-ring" style="background:var(--faint);">
                    <div class="ig-story-inner">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                    </div>
                </div>
                <span class="ig-story-lbl">All<br>{{ $countAll }}</span>
            </div>
        </div>

        {{-- ── Chart ── --}}
        <div class="ig-chart-wrap">
            <div class="ig-chart-header">
                <div>
                    <div class="ig-chart-title">Fundraising Overview</div>
                    <div class="ig-chart-sub">Monthly funds raised this year</div>
                </div>
                <div class="ig-chart-legend">
                    <span></span> Amount Raised
                </div>
            </div>
            <div class="h-56">
                <canvas id="fundChart"></canvas>
            </div>
        </div>

        {{-- ── Filter tabs ── --}}
        <div class="ig-tabs">
            <button class="ig-tab active" data-filter="all" onclick="setFilter('all')">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                Posts <span class="tab-count">{{ $countAll }}</span>
            </button>
            <button class="ig-tab" data-filter="active" onclick="setFilter('active')">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                Active <span class="tab-count">{{ $countActive }}</span>
            </button>
            <button class="ig-tab" data-filter="pending" onclick="setFilter('pending')">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                Pending <span class="tab-count">{{ $countPending }}</span>
            </button>
            <button class="ig-tab" data-filter="paused" onclick="setFilter('paused')">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/></svg>
                Paused <span class="tab-count">{{ $countPaused }}</span>
            </button>
            <button class="ig-tab" data-filter="rejected" onclick="setFilter('rejected')">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                Rejected <span class="tab-count">{{ $countReject }}</span>
            </button>
        </div>

        {{-- ── Post grid ── --}}
        @if($campaigns->count() > 0)
        <div class="ig-post-grid" id="campaignGrid">

            <div class="ig-empty" id="noResults">
                <div class="ig-empty-ring">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
                </div>
                <h3>No campaigns</h3>
                <p>Nothing here yet in this category.</p>
            </div>

            @foreach($campaigns as $campaign)
            @php
                if ($campaign->campaign_state === 'paused') {
                    $filterVal  = 'paused';
                    $statusClass = 'ps-paused';
                    $statusLabel = 'Paused';
                } elseif ($campaign->status === 'rejected') {
                    $filterVal  = 'rejected';
                    $statusClass = 'ps-rejected';
                    $statusLabel = 'Rejected';
                } elseif ($campaign->status === 'pending') {
                    $filterVal  = 'pending';
                    $statusClass = 'ps-pending';
                    $statusLabel = 'Pending';
                } elseif ($campaign->status === 'approved' && $campaign->campaign_state === 'active') {
                    $filterVal  = 'active';
                    $statusClass = 'ps-active';
                    $statusLabel = 'Active';
                } else {
                    $filterVal  = 'other';
                    $statusClass = 'ps-default';
                    $statusLabel = ucfirst($campaign->status ?? 'Draft');
                }
                $raised     = $campaign->raised_amount ?? 0;
                $goal       = $campaign->goal_amount ?? 0;
                $percentage = $goal > 0 ? min(100, round(($raised / $goal) * 100)) : 0;
                $donors     = $campaign->donations_count ?? 0;
            @endphp

            <div class="ig-post"
                 data-filter="{{ $filterVal }}"
                 data-id="{{ $campaign->id }}"
                 data-title="{{ addslashes($campaign->title) }}"
                 data-raised="{{ $raised }}"
                 data-goal="{{ $goal }}"
                 data-pct="{{ $percentage }}"
                 data-status="{{ $statusLabel }}"
                 data-status-class="{{ $statusClass }}"
                 data-view="{{ route('campaign.show', $campaign->id) }}"
                 data-edit="{{ route('campaign.edit', $campaign->id) }}"
                 data-date="{{ $campaign->created_at->format('d M Y') }}"
                 data-donors="{{ $donors }}"
                 @if($campaign->cover_image)
                 data-img="{{ asset('storage/' . $campaign->cover_image) }}"
                 @endif
                 onclick="openModal(this)">

                @if($campaign->cover_image)
                    <img src="{{ asset('storage/' . $campaign->cover_image) }}"
                         loading="lazy" alt="{{ $campaign->title }}">
                @else
                    <div class="ig-post-placeholder">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                @endif

                {{-- Hover overlay ── --}}
                <div class="ig-post-overlay">
                    <div class="ig-overlay-stats">
                        <div class="ig-overlay-stat">
                            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                            {{ number_format($raised / 1000, 0) }}k
                        </div>
                        <div class="ig-overlay-stat">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                            {{ $donors }}
                        </div>
                    </div>
                    <div class="ig-overlay-title">{{ $campaign->title }}</div>
                </div>

                {{-- Status pill ── --}}
                <div class="ig-post-status {{ $statusClass }}">{{ $statusLabel }}</div>

                {{-- Progress bar ── --}}
                <div class="ig-post-progress">
                    <div class="ig-post-progress-fill" style="width:{{ $percentage }}%"></div>
                </div>

            </div>
            @endforeach

        </div>

        @else
        <div style="display:flex;flex-direction:column;align-items:center;padding:80px 20px;text-align:center;">
            <div class="ig-empty-ring" style="display:flex;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width:36px;height:36px;color:#a0a0a0;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h3 style="font-size:20px;font-weight:700;margin:20px 0 8px;">Start your first campaign</h3>
            <p style="color:#a0a0a0;font-size:14px;margin-bottom:28px;">Share your story and start making an impact.</p>
            <a href="{{ route('campaign.create') }}" class="ig-new-btn" style="font-size:14px;padding:12px 28px;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:14px;height:14px;"><path d="M12 4v16m8-8H4"/></svg>
                Create Campaign
            </a>
        </div>
        @endif

    </div>
</div>

{{-- ── Detail Modal ── --}}
<div class="ig-modal-bg" id="igModal" onclick="closeModalBg(event)">
    <div class="ig-modal">
        <div class="ig-modal-left" id="modalLeft">
            <div class="ig-modal-left-placeholder">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
        </div>
        <div class="ig-modal-right">
            <div class="ig-modal-header">
                <div class="ig-modal-user">
                    <div class="ig-modal-avatar">{{ $initials }}</div>
                    <div>
                        <div class="ig-modal-username">{{ auth()->user()->name }}</div>
                        <div class="ig-modal-date" id="modalDate">—</div>
                    </div>
                </div>
                <div class="ig-modal-close" onclick="closeModal()">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </div>
            </div>

            <div class="ig-modal-reactions">
                <div class="ig-react-btn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                    <span id="modalRaised">—</span>
                </div>
                <div class="ig-react-btn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    <span id="modalDonors">—</span>
                </div>
                <div class="ig-react-btn" style="margin-left:auto;">
                    <span id="modalStatusBadge" class="ig-post-status" style="position:static;font-size:11px;">—</span>
                </div>
            </div>

            <div class="ig-modal-body">
                <div class="ig-modal-title" id="modalTitle">—</div>

                <div class="ig-modal-progress">
                    <div class="ig-modal-progress-bar">
                        <div class="ig-modal-progress-fill" id="modalProgressFill" style="width:0%"></div>
                    </div>
                    <div class="ig-modal-progress-nums">
                        <strong id="modalRaisedNum">—</strong>
                        <span id="modalGoalNum">—</span>
                    </div>
                </div>

                <div class="ig-modal-info">
                    <div class="ig-info-row">
                        <span class="ig-info-row-label">Goal</span>
                        <span class="ig-info-row-value" id="modalGoalInfo">—</span>
                    </div>
                    <div class="ig-info-row">
                        <span class="ig-info-row-label">Progress</span>
                        <span class="ig-info-row-value" id="modalPctInfo">—</span>
                    </div>
                    <div class="ig-info-row">
                        <span class="ig-info-row-label">Donors</span>
                        <span class="ig-info-row-value" id="modalDonorsInfo">—</span>
                    </div>
                    <div class="ig-info-row">
                        <span class="ig-info-row-label">Created</span>
                        <span class="ig-info-row-value" id="modalDateInfo">—</span>
                    </div>
                </div>
            </div>

            <div class="ig-modal-actions">
                <a href="#" class="ig-modal-btn ig-modal-btn-view" id="modalViewBtn">View Campaign</a>
                <a href="#" class="ig-modal-btn ig-modal-btn-edit" id="modalEditBtn">Edit</a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Chart ──
    var ctx = document.getElementById('fundChart');
    if (ctx) {
        var monthlyData = @json($monthlyData ?? []);
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: Object.keys(monthlyData),
                datasets: [{
                    label: 'Amount Raised (₹)',
                    data: Object.values(monthlyData),
                    borderColor: '#e1306c',
                    backgroundColor: 'rgba(225,48,108,0.07)',
                    borderWidth: 2.5,
                    fill: true,
                    tension: 0.45,
                    pointBackgroundColor: '#e1306c',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#111',
                        titleColor: '#e1306c',
                        bodyColor: '#fff',
                        padding: 12,
                        cornerRadius: 10,
                        callbacks: { label: function(c){ return ' ₹' + Number(c.parsed.y).toLocaleString('en-IN'); } }
                    }
                },
                scales: {
                    x: { grid: { display: false }, ticks: { color: '#a0a0a0', font: { size: 11, family: 'DM Sans' } } },
                    y: {
                        grid: { color: 'rgba(0,0,0,0.04)' },
                        ticks: { color: '#a0a0a0', font: { size: 11, family: 'DM Sans' }, callback: function(v){ return '₹' + Number(v).toLocaleString('en-IN'); } }
                    }
                }
            }
        });
    }
});

// ── Filter ──
function setFilter(filter) {
    // Update tabs
    document.querySelectorAll('.ig-tab').forEach(function(t){
        t.classList.toggle('active', t.getAttribute('data-filter') === filter);
    });

    // Show/hide posts
    var posts   = document.querySelectorAll('.ig-post');
    var visible = 0;
    posts.forEach(function(p){
        var match = filter === 'all' || p.getAttribute('data-filter') === filter;
        p.classList.toggle('ig-hidden', !match);
        if (match) visible++;
    });

    // Empty state
    var empty = document.getElementById('noResults');
    if (empty) empty.style.display = visible === 0 ? 'flex' : 'none';
}

// ── Modal ──
function openModal(el) {
    var modal = document.getElementById('igModal');
    var d     = el.dataset;

    // Left image
    var left = document.getElementById('modalLeft');
    if (d.img) {
        left.innerHTML = '<img src="' + d.img + '" style="width:100%;height:100%;object-fit:cover;max-height:90vh;">';
    } else {
        left.innerHTML = '<div class="ig-modal-left-placeholder"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width:64px;height:64px;color:#e1306c;opacity:.3"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></div>';
    }

    // Populate
    var raised = parseFloat(d.raised) || 0;
    var goal   = parseFloat(d.goal)   || 0;

    document.getElementById('modalTitle').textContent       = d.title;
    document.getElementById('modalDate').textContent        = d.date;
    document.getElementById('modalRaised').textContent      = '₹' + (raised/1000).toFixed(1) + 'k raised';
    document.getElementById('modalDonors').textContent      = d.donors + ' donors';
    document.getElementById('modalRaisedNum').textContent   = '₹' + Number(raised).toLocaleString('en-IN');
    document.getElementById('modalGoalNum').textContent     = 'of ₹' + Number(goal).toLocaleString('en-IN');
    document.getElementById('modalGoalInfo').textContent    = '₹' + Number(goal).toLocaleString('en-IN');
    document.getElementById('modalPctInfo').textContent     = d.pct + '%';
    document.getElementById('modalDonorsInfo').textContent  = d.donors;
    document.getElementById('modalDateInfo').textContent    = d.date;
    document.getElementById('modalViewBtn').href            = d.view;
    document.getElementById('modalEditBtn').href            = d.edit;

    var badge = document.getElementById('modalStatusBadge');
    badge.textContent  = d.status;
    badge.className    = 'ig-post-status ' + d.statusClass;
    badge.style.position = 'static';
    badge.style.fontSize = '11px';

    // Animate progress after small delay
    var fill = document.getElementById('modalProgressFill');
    fill.style.width = '0%';
    setTimeout(function(){ fill.style.width = d.pct + '%'; }, 80);

    modal.classList.add('open');
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    document.getElementById('igModal').classList.remove('open');
    document.body.style.overflow = '';
}

function closeModalBg(e) {
    if (e.target === document.getElementById('igModal')) closeModal();
}

document.addEventListener('keydown', function(e){ if (e.key === 'Escape') closeModal(); });


</script>

@endsection