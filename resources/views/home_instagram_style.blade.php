@extends('layouts.app')

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
/* ═══════════════════════════════════════
   RESET & BASE
═══════════════════════════════════════ */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
    --brand:        #7c3aed;
    --brand-dark:   #5b21b6;
    --brand-light:  #a78bfa;
    --brand-pale:   #f5f3ff;
    --brand-mist:   #ede9fe;
    --ink:          #0f0f0f;
    --ink-2:        #3f3f46;
    --ink-3:        #71717a;
    --ink-4:        #a1a1aa;
    --surface:      #ffffff;
    --surface-2:    #fafafa;
    --surface-3:    #f4f4f5;
    --border:       #e4e4e7;
    --border-2:     #d4d4d8;
    --red:          #ef4444;
    --green:        #10b981;
    --amber:        #f59e0b;
    --sidebar-w:    244px;
    --right-w:      340px;
    --nav-h:        60px;
    --feed-max:     520px;
    --radius-sm:    8px;
    --radius-md:    12px;
    --radius-lg:    18px;
    --radius-xl:    24px;
    --radius-full:  9999px;
    --shadow-sm:    0 1px 3px rgba(0,0,0,.07);
    --shadow-md:    0 4px 16px rgba(0,0,0,.09);
    --shadow-lg:    0 12px 40px rgba(0,0,0,.12);
    --font-body:    'DM Sans', sans-serif;
    --font-display: 'Instrument Serif', serif;
    --trans:        0.18s ease;
}

body {
    font-family: var(--font-body);
    background: var(--surface-2);
    color: var(--ink);
    overflow-x: hidden;
    -webkit-font-smoothing: antialiased;
}

img { display: block; max-width: 100%; }
a { text-decoration: none; color: inherit; }
button { font-family: var(--font-body); cursor: pointer; border: none; outline: none; }
input, textarea { font-family: var(--font-body); }
ul { list-style: none; }

/* ═══════════════════════════════════════
   SCROLLBAR
═══════════════════════════════════════ */
::-webkit-scrollbar { width: 4px; height: 4px; }
::-webkit-scrollbar-track { background: transparent; }
::-webkit-scrollbar-thumb { background: var(--border-2); border-radius: 4px; }

/* ═══════════════════════════════════════
   DESKTOP TOP NAV (hidden on mobile)
═══════════════════════════════════════ */
.desktop-nav {
    display: none;
    position: fixed; top: 0; left: 0; right: 0; z-index: 100;
    height: var(--nav-h);
    background: var(--surface);
    border-bottom: 1px solid var(--border);
    align-items: center;
    padding: 0 24px;
    gap: 20px;
}
.desktop-nav .d-logo {
    font-family: var(--font-display);
    font-size: 22px; color: var(--ink);
    white-space: nowrap; flex-shrink: 0;
}
.desktop-nav .d-logo span { color: var(--brand); font-style: italic; }
.d-search {
    flex: 1; max-width: 320px;
    display: flex; align-items: center; gap: 8px;
    background: var(--surface-3);
    border: 1px solid var(--border);
    border-radius: var(--radius-full);
    padding: 8px 14px;
    transition: border-color var(--trans);
}
.d-search:focus-within { border-color: var(--brand-light); }
.d-search i { color: var(--ink-4); font-size: 13px; }
.d-search input {
    background: none; border: none; outline: none;
    font-size: 14px; color: var(--ink); width: 100%;
}
.d-search input::placeholder { color: var(--ink-4); }
.d-nav-links {
    display: flex; gap: 4px; align-items: center; margin-left: auto;
}
.d-nav-btn {
    display: flex; align-items: center; gap: 6px;
    padding: 8px 14px; border-radius: var(--radius-md);
    font-size: 14px; font-weight: 500; color: var(--ink-2);
    transition: background var(--trans), color var(--trans);
    white-space: nowrap;
}
.d-nav-btn:hover { background: var(--surface-3); color: var(--ink); }
.d-nav-btn.active { color: var(--brand); }
.d-nav-btn i { font-size: 15px; }
.d-donate-btn {
    background: var(--brand); color: #fff;
    padding: 9px 20px; border-radius: var(--radius-md);
    font-size: 14px; font-weight: 500;
    transition: background var(--trans), transform var(--trans);
    white-space: nowrap;
}
.d-donate-btn:hover { background: var(--brand-dark); transform: translateY(-1px); }
.d-avatar {
    width: 34px; height: 34px; border-radius: 50%;
    background: var(--brand-mist);
    border: 2px solid var(--brand-light);
    display: flex; align-items: center; justify-content: center;
    font-size: 14px; color: var(--brand); font-weight: 600;
    flex-shrink: 0; cursor: pointer;
}

/* ═══════════════════════════════════════
   MAIN LAYOUT WRAPPER
═══════════════════════════════════════ */
.app-layout {
    min-height: 100vh;
    display: flex;
}

/* ═══════════════════════════════════════
   LEFT SIDEBAR (desktop only)
═══════════════════════════════════════ */
.left-sidebar {
    display: none;
    width: var(--sidebar-w);
    position: fixed; top: var(--nav-h); bottom: 0; left: 0;
    flex-direction: column;
    border-right: 1px solid var(--border);
    background: var(--surface);
    padding: 20px 12px;
    overflow-y: auto;
    z-index: 50;
}
.sidebar-section { margin-bottom: 28px; }
.sidebar-section-label {
    font-size: 10px; font-weight: 600; letter-spacing: .1em;
    text-transform: uppercase; color: var(--ink-4);
    padding: 0 10px; margin-bottom: 6px;
}
.sidebar-link {
    display: flex; align-items: center; gap: 12px;
    padding: 10px 12px; border-radius: var(--radius-md);
    font-size: 14px; font-weight: 400; color: var(--ink-2);
    transition: background var(--trans), color var(--trans);
    cursor: pointer;
}
.sidebar-link:hover { background: var(--surface-3); color: var(--ink); }
.sidebar-link.active { background: var(--brand-pale); color: var(--brand); font-weight: 500; }
.sidebar-link i { width: 18px; text-align: center; font-size: 15px; }
.sidebar-link .sl-badge {
    margin-left: auto;
    background: var(--brand); color: #fff;
    font-size: 10px; padding: 2px 7px;
    border-radius: var(--radius-full); font-weight: 600;
}
.sidebar-start-btn {
    display: flex; align-items: center; justify-content: center; gap: 8px;
    width: 100%; padding: 12px;
    background: linear-gradient(135deg, var(--brand), #4f46e5);
    color: #fff; border-radius: var(--radius-md);
    font-size: 14px; font-weight: 500;
    transition: opacity var(--trans), transform var(--trans);
    margin-top: 4px;
}
.sidebar-start-btn:hover { opacity: .92; transform: translateY(-1px); }
.sidebar-stats {
    background: var(--brand-pale);
    border-radius: var(--radius-md);
    padding: 14px;
}
.sidebar-stat { display: flex; justify-content: space-between; align-items: baseline; padding: 5px 0; border-bottom: 1px solid var(--brand-mist); }
.sidebar-stat:last-child { border-bottom: none; }
.sidebar-stat .lbl { font-size: 12px; color: var(--ink-3); }
.sidebar-stat .val { font-size: 14px; font-weight: 600; color: var(--brand); font-family: var(--font-display); font-style: italic; }

/* ═══════════════════════════════════════
   MAIN CONTENT AREA
═══════════════════════════════════════ */
.main-content {
    width: 100%;
    min-height: 100vh;
    padding-bottom: 70px; /* mobile bottom nav */
}

/* ═══════════════════════════════════════
   MOBILE TOP NAR
═══════════════════════════════════════ */
.mobile-topbar {
    position: sticky; top: 0; z-index: 80;
    background: var(--surface);
    border-bottom: 1px solid var(--border);
    display: flex; align-items: center; justify-content: space-between;
    padding: 12px 16px;
}
.m-logo {
    font-family: var(--font-display);
    font-size: 20px; color: var(--ink);
}
.m-logo span { color: var(--brand); font-style: italic; }
.m-topbar-icons { display: flex; gap: 16px; align-items: center; }
.m-icon-btn {
    width: 36px; height: 36px;
    display: flex; align-items: center; justify-content: center;
    border-radius: var(--radius-md); background: none;
    position: relative;
}
.m-icon-btn i { font-size: 18px; color: var(--ink); }
.m-notif-dot {
    position: absolute; top: 5px; right: 5px;
    width: 8px; height: 8px; border-radius: 50%;
    background: var(--red); border: 2px solid var(--surface);
}

/* ═══════════════════════════════════════
   MOBILE SEARCH BAR
═══════════════════════════════════════ */
.mobile-search-bar {
    padding: 10px 16px;
    background: var(--surface);
    border-bottom: 1px solid var(--border);
}
.m-search-inner {
    display: flex; align-items: center; gap: 8px;
    background: var(--surface-3);
    border: 1px solid var(--border);
    border-radius: var(--radius-full);
    padding: 9px 14px;
}
.m-search-inner i { color: var(--ink-4); font-size: 13px; }
.m-search-inner input {
    background: none; border: none; outline: none;
    font-size: 14px; color: var(--ink); width: 100%;
}
.m-search-inner input::placeholder { color: var(--ink-4); }

/* ═══════════════════════════════════════
   CATEGORY PILLS (horizontal scroll)
═══════════════════════════════════════ */
.cat-pills-wrap {
    background: var(--surface);
    border-bottom: 1px solid var(--border);
    padding: 10px 0;
    overflow-x: auto;
    scrollbar-width: none;
    white-space: nowrap;
    -webkit-overflow-scrolling: touch;
}
.cat-pills-wrap::-webkit-scrollbar { display: none; }
.cat-pills-inner {
    display: inline-flex; gap: 8px;
    padding: 0 16px;
}
.cat-pill {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 16px;
    border-radius: var(--radius-full);
    font-size: 13px; font-weight: 500;
    border: 1.5px solid var(--border);
    background: var(--surface);
    color: var(--ink-2);
    cursor: pointer;
    transition: all var(--trans);
    white-space: nowrap;
    flex-shrink: 0;
}
.cat-pill i { font-size: 13px; }
.cat-pill:hover { border-color: var(--brand-light); color: var(--brand); background: var(--brand-pale); }
.cat-pill.active {
    background: var(--brand); color: #fff;
    border-color: var(--brand);
    box-shadow: 0 3px 10px rgba(124,58,237,.3);
}

/* ═══════════════════════════════════════
   STORIES ROW
═══════════════════════════════════════ */
.stories-section {
    background: var(--surface);
    border-bottom: 1px solid var(--border);
    padding: 14px 0;
}
.stories-row {
    display: flex; gap: 12px;
    overflow-x: auto; scrollbar-width: none;
    padding: 0 16px;
    -webkit-overflow-scrolling: touch;
}
.stories-row::-webkit-scrollbar { display: none; }
.story-item {
    display: flex; flex-direction: column;
    align-items: center; gap: 5px;
    flex-shrink: 0; cursor: pointer;
}
.story-ring-wrap {
    width: 64px; height: 64px; border-radius: 50%;
    background: conic-gradient(var(--brand) 0deg, var(--brand-light) 120deg, #4f46e5 240deg, var(--brand) 360deg);
    padding: 2.5px;
    display: flex; align-items: center; justify-content: center;
    transition: transform var(--trans);
}
.story-ring-wrap:hover { transform: scale(1.05); }
.story-ring-wrap.seen { background: var(--border); }
.story-ring-wrap.add-story { background: var(--surface-3); border: 2px dashed var(--border-2); }
.story-inner {
    width: 57px; height: 57px; border-radius: 50%;
    background: var(--surface-3);
    border: 2.5px solid var(--surface);
    display: flex; align-items: center; justify-content: center;
    font-size: 24px; overflow: hidden;
    position: relative;
}
.story-add-icon {
    width: 20px; height: 20px; border-radius: 50%;
    background: var(--brand); color: #fff;
    display: flex; align-items: center; justify-content: center;
    font-size: 13px; position: absolute; bottom: -2px; right: -2px;
    border: 2px solid var(--surface);
}
.story-label {
    font-size: 11px; color: var(--ink-2);
    max-width: 66px; text-align: center;
    overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
}

/* ═══════════════════════════════════════
   FEED AREA
═══════════════════════════════════════ */
.feed-container {
    padding: 12px 0;
}

/* ═══════════════════════════════════════
   CAMPAIGN POST CARD
═══════════════════════════════════════ */
.camp-post {
    background: var(--surface);
    border-top: 1px solid var(--border);
    border-bottom: 1px solid var(--border);
    margin-bottom: 8px;
    animation: fadeUp .4s ease both;
}
.camp-post.hidden { display: none; }
@keyframes fadeUp {
    from { opacity: 0; transform: translateY(16px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* Post header */
.post-header {
    display: flex; align-items: center; gap: 10px;
    padding: 12px 14px;
}
.post-avatar-wrap { position: relative; flex-shrink: 0; }
.post-avatar {
    width: 40px; height: 40px; border-radius: 50%;
    background: var(--brand-mist);
    border: 2px solid transparent;
    background-clip: padding-box;
    display: flex; align-items: center; justify-content: center;
    font-size: 18px; overflow: hidden;
    position: relative;
}
.post-avatar-ring {
    position: absolute; inset: -2px; border-radius: 50%;
    background: conic-gradient(var(--brand), var(--brand-light), #4f46e5, var(--brand));
    z-index: -1;
}
.post-meta { flex: 1; min-width: 0; }
.post-name {
    font-size: 14px; font-weight: 500; color: var(--ink);
    display: flex; align-items: center; gap: 4px;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.verified-icon {
    width: 14px; height: 14px; border-radius: 50%;
    background: var(--brand); flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
}
.verified-icon i { font-size: 7px; color: #fff; }
.post-sub {
    font-size: 12px; color: var(--ink-3);
    margin-top: 1px; display: flex; align-items: center; gap: 4px;
}
.post-sub i { font-size: 11px; }
.post-more-btn {
    background: none; border: none; padding: 4px;
    color: var(--ink-2); font-size: 18px; cursor: pointer;
    border-radius: var(--radius-sm);
    transition: background var(--trans);
}
.post-more-btn:hover { background: var(--surface-3); }

/* Post image */
.post-image-wrap {
    position: relative;
    width: 100%;
    background: var(--surface-3);
    overflow: hidden;
}
.post-image-wrap img {
    width: 100%; display: block;
    aspect-ratio: 4/3; object-fit: cover;
    transition: transform .5s ease;
}
.camp-post:hover .post-image-wrap img { transform: scale(1.02); }
.post-image-placeholder {
    aspect-ratio: 4/3;
    display: flex; align-items: center; justify-content: center;
    font-size: 72px;
}

/* Overlays on image */
.post-overlay-top {
    position: absolute; top: 10px; left: 10px; right: 10px;
    display: flex; justify-content: space-between; align-items: flex-start;
    pointer-events: none;
}
.urgency-badge {
    display: flex; align-items: center; gap: 5px;
    background: rgba(239,68,68,.92);
    color: #fff; font-size: 11px; font-weight: 500;
    padding: 5px 12px; border-radius: var(--radius-full);
    pointer-events: all;
}
.urgency-pulse { width: 6px; height: 6px; border-radius: 50%; background: #fff; animation: pulse 1.5s ease infinite; }
@keyframes pulse { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.4;transform:scale(.7)} }
.days-badge {
    background: rgba(0,0,0,.65); backdrop-filter: blur(8px);
    color: #fff; font-size: 11px; font-weight: 500;
    padding: 5px 12px; border-radius: var(--radius-full);
}

.post-overlay-bottom {
    position: absolute; bottom: 0; left: 0; right: 0;
    background: linear-gradient(to top, rgba(0,0,0,.72) 0%, transparent 100%);
    padding: 32px 14px 12px;
}
.progress-row { display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 6px; }
.progress-raised { font-size: 15px; font-weight: 600; color: #fff; }
.progress-goal { font-size: 12px; color: rgba(255,255,255,.75); }
.progress-track {
    height: 5px; background: rgba(255,255,255,.25);
    border-radius: 3px; overflow: hidden; margin-bottom: 6px;
}
.progress-fill {
    height: 100%; border-radius: 3px;
    background: linear-gradient(90deg, #a78bfa, #7c3aed);
    transition: width 1.2s cubic-bezier(.4,0,.2,1);
}
.progress-meta { display: flex; justify-content: space-between; }
.progress-pct { font-size: 12px; color: #c4b5fd; font-weight: 500; }
.progress-donors { font-size: 12px; color: rgba(255,255,255,.65); }

/* Post actions row */
.post-actions {
    display: flex; align-items: center;
    padding: 10px 14px; gap: 14px;
}
.action-btn {
    display: flex; align-items: center; gap: 5px;
    background: none; border: none; padding: 4px;
    font-size: 13px; color: var(--ink-2);
    cursor: pointer; border-radius: var(--radius-sm);
    transition: color var(--trans);
}
.action-btn i { font-size: 20px; transition: transform .15s ease, color .15s; }
.action-btn:hover i { transform: scale(1.15); }
.action-btn.liked i { color: var(--red); }
.action-btn .a-count { font-size: 13px; font-weight: 500; }
.action-spacer { flex: 1; }
.donate-cta {
    display: flex; align-items: center; gap: 7px;
    background: var(--brand); color: #fff;
    padding: 9px 18px; border-radius: var(--radius-md);
    font-size: 13px; font-weight: 500;
    transition: background var(--trans), transform var(--trans), box-shadow var(--trans);
    box-shadow: 0 3px 12px rgba(124,58,237,.3);
}
.donate-cta:hover { background: var(--brand-dark); transform: translateY(-1px); box-shadow: 0 6px 18px rgba(124,58,237,.4); }
.donate-cta.donated { background: var(--green); box-shadow: 0 3px 12px rgba(16,185,129,.3); }
.donate-cta i { font-size: 13px; }

/* Post text */
.post-caption {
    padding: 4px 14px 4px;
    font-size: 14px; line-height: 1.5; color: var(--ink);
}
.post-caption .poster { font-weight: 500; }
.post-caption .more-link { color: var(--ink-3); cursor: pointer; }
.hashtag { color: var(--brand); font-weight: 500; }
.view-comments-btn {
    display: block; padding: 2px 14px 4px;
    font-size: 13px; color: var(--ink-4); cursor: pointer;
    background: none; border: none; font-family: var(--font-body);
    text-align: left;
}
.post-time { padding: 2px 14px 12px; font-size: 11px; color: var(--ink-4); }

/* ═══════════════════════════════════════
   IMPACT REELS ROW
═══════════════════════════════════════ */
.reels-section {
    background: var(--surface);
    border-top: 1px solid var(--border);
    border-bottom: 1px solid var(--border);
    margin-bottom: 8px;
    padding: 14px 0;
}
.reels-header {
    display: flex; justify-content: space-between; align-items: center;
    padding: 0 16px; margin-bottom: 12px;
}
.reels-title { font-size: 14px; font-weight: 500; color: var(--ink); }
.reels-see-all { font-size: 13px; font-weight: 500; color: var(--brand); cursor: pointer; }
.reels-row {
    display: flex; gap: 10px;
    overflow-x: auto; scrollbar-width: none;
    padding: 0 16px;
    -webkit-overflow-scrolling: touch;
}
.reels-row::-webkit-scrollbar { display: none; }
.reel-card {
    flex-shrink: 0; width: 110px;
    background: var(--surface-2);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    overflow: hidden; cursor: pointer;
    transition: transform var(--trans), box-shadow var(--trans);
}
.reel-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-md); }
.reel-thumb {
    width: 100%; height: 90px;
    display: flex; align-items: center; justify-content: center;
    font-size: 32px;
}
.reel-body { padding: 8px 9px 9px; }
.reel-name { font-size: 11px; font-weight: 500; color: var(--ink); line-height: 1.35; }
.reel-stat { font-size: 11px; color: var(--brand); margin-top: 3px; font-weight: 500; }

/* ═══════════════════════════════════════
   SUGGESTED CAMPAIGNS BANNER
═══════════════════════════════════════ */
.sugg-banner {
    background: var(--surface);
    border-top: 1px solid var(--border);
    border-bottom: 1px solid var(--border);
    margin-bottom: 8px;
    padding: 16px;
}
.sugg-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; }
.sugg-title { font-size: 14px; font-weight: 500; color: var(--ink); }
.sugg-see { font-size: 13px; font-weight: 500; color: var(--brand); }
.sugg-grid {
    display: grid; grid-template-columns: 1fr 1fr;
    gap: 10px;
}
.sugg-card {
    border: 1px solid var(--border);
    border-radius: var(--radius-md); overflow: hidden;
    cursor: pointer; transition: border-color var(--trans), box-shadow var(--trans);
}
.sugg-card:hover { border-color: var(--brand-light); box-shadow: var(--shadow-sm); }
.sugg-img {
    height: 70px; display: flex; align-items: center;
    justify-content: center; font-size: 28px;
    background: var(--surface-3);
}
.sugg-info { padding: 8px; }
.sugg-name { font-size: 12px; font-weight: 500; color: var(--ink); line-height: 1.3; margin-bottom: 4px; }
.sugg-prog-bg { height: 3px; background: var(--surface-3); border-radius: 2px; margin-bottom: 4px; }
.sugg-prog-fill { height: 100%; background: var(--brand-light); border-radius: 2px; }
.sugg-pct { font-size: 11px; color: var(--brand); font-weight: 500; }

/* ═══════════════════════════════════════
   LOAD MORE / INFINITE LOADER
═══════════════════════════════════════ */
.infinite-trigger { height: 1px; }
.load-spinner {
    display: flex; justify-content: center; align-items: center;
    gap: 8px; padding: 20px;
    font-size: 13px; color: var(--ink-4);
}
.spin-ring {
    width: 18px; height: 18px; border-radius: 50%;
    border: 2px solid var(--border-2);
    border-top-color: var(--brand);
    animation: spin 0.7s linear infinite;
    display: none;
}
@keyframes spin { to { transform: rotate(360deg); } }

/* ═══════════════════════════════════════
   MOBILE BOTTOM NAV
═══════════════════════════════════════ */
.mobile-bottom-nav {
    position: fixed; bottom: 0; left: 0; right: 0; z-index: 90;
    background: var(--surface);
    border-top: 1px solid var(--border);
    display: flex; justify-content: space-around; align-items: center;
    padding: 8px 0 calc(8px + env(safe-area-inset-bottom));
    box-shadow: 0 -4px 20px rgba(0,0,0,.06);
}
.mob-nav-item {
    display: flex; flex-direction: column;
    align-items: center; gap: 3px;
    padding: 4px 14px;
    cursor: pointer; position: relative;
    flex: 1;
}
.mob-nav-item i { font-size: 20px; color: var(--ink-3); transition: color var(--trans), transform .2s; }
.mob-nav-item.active i { color: var(--brand); }
.mob-nav-item:hover i { transform: translateY(-2px); }
.mob-nav-label { font-size: 10px; color: var(--ink-4); }
.mob-nav-item.active .mob-nav-label { color: var(--brand); }
.mob-nav-dot {
    width: 4px; height: 4px; border-radius: 50%;
    background: var(--brand); margin-top: 1px;
}
.mob-nav-fab {
    width: 44px; height: 44px;
    background: linear-gradient(135deg, var(--brand), #4f46e5);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    box-shadow: 0 4px 14px rgba(124,58,237,.4);
    transition: transform .2s ease;
}
.mob-nav-fab:hover { transform: scale(1.08); }
.mob-nav-fab i { font-size: 18px; color: #fff; }

/* ═══════════════════════════════════════
   RIGHT SIDEBAR (desktop lg)
═══════════════════════════════════════ */
.right-sidebar {
    display: none;
    width: var(--right-w);
    flex-shrink: 0;
}
.right-sticky { position: sticky; top: calc(var(--nav-h) + 20px); padding-right: 24px; }
.right-section { margin-bottom: 24px; }
.right-section-title {
    font-size: 12px; font-weight: 600; letter-spacing: .08em;
    text-transform: uppercase; color: var(--ink-4);
    margin-bottom: 12px;
}
.r-stats-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
.r-stat-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius-md);
    padding: 14px;
}
.r-stat-val { font-size: 20px; font-weight: 600; color: var(--ink); font-family: var(--font-display); font-style: italic; }
.r-stat-lbl { font-size: 11px; color: var(--ink-4); margin-top: 2px; }
.r-trending-item {
    display: flex; align-items: center; gap: 10px;
    padding: 10px 0;
    border-bottom: 1px solid var(--border);
    cursor: pointer;
}
.r-trending-item:last-child { border-bottom: none; }
.r-trend-icon {
    width: 40px; height: 40px; border-radius: var(--radius-md);
    background: var(--surface-3);
    display: flex; align-items: center; justify-content: center;
    font-size: 18px; flex-shrink: 0;
}
.r-trend-info { flex: 1; min-width: 0; }
.r-trend-name { font-size: 13px; font-weight: 500; color: var(--ink); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.r-trend-meta { font-size: 11px; color: var(--ink-4); }
.r-trend-pct { font-size: 13px; font-weight: 600; color: var(--green); }
.r-impact-row {
    display: flex; justify-content: space-between;
    align-items: center; padding: 9px 0;
    border-bottom: 1px solid var(--border);
}
.r-impact-row:last-child { border-bottom: none; }
.r-impact-state { font-size: 13px; color: var(--ink-2); }
.r-impact-val { font-size: 14px; font-weight: 600; color: var(--brand); font-family: var(--font-display); font-style: italic; }
.r-box {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 16px;
}
.r-start-btn {
    display: flex; align-items: center; justify-content: center; gap: 8px;
    width: 100%; padding: 13px;
    background: linear-gradient(135deg, var(--brand), #4f46e5);
    color: #fff; border-radius: var(--radius-md);
    font-size: 14px; font-weight: 500;
    border: none; cursor: pointer;
    transition: opacity var(--trans), transform var(--trans);
    margin-top: 12px;
}
.r-start-btn:hover { opacity: .92; transform: translateY(-1px); }
.r-footer { font-size: 11px; color: var(--ink-4); line-height: 1.6; }
.r-footer a { color: var(--ink-3); }
.r-footer a:hover { text-decoration: underline; }

/* ═══════════════════════════════════════
   DESKTOP BREAKPOINTS
═══════════════════════════════════════ */
@media (min-width: 768px) {
    .mobile-topbar { display: none; }
    .mobile-search-bar { display: none; }
    .mobile-bottom-nav { display: none; }
    .desktop-nav { display: flex; }
    .main-content { padding-top: var(--nav-h); padding-bottom: 0; }

    .cat-pills-wrap { border-top: none; }
    .stories-section { border-top: 1px solid var(--border); }

    .feed-container { max-width: 520px; margin: 0 auto; }
    .camp-post { border-left: 1px solid var(--border); border-right: 1px solid var(--border); border-radius: var(--radius-lg); margin-bottom: 16px; overflow: hidden; }
    .stories-section { max-width: 520px; margin: 0 auto; border-left: 1px solid var(--border); border-right: 1px solid var(--border); border-radius: var(--radius-lg); }
}

@media (min-width: 1024px) {
    .left-sidebar { display: flex; }
    .app-layout { margin-left: var(--sidebar-w); }
    .main-content { padding-right: 0; }
    .desktop-nav { left: var(--sidebar-w); }
    .desktop-nav .d-logo { display: none; }
}

@media (min-width: 1280px) {
    .right-sidebar { display: block; }
    .app-layout { display: flex; margin-left: var(--sidebar-w); }
    .main-content { flex: 1; min-width: 0; }
    .feed-wrapper {
        display: flex;
        gap: 40px;
        max-width: calc(var(--feed-max) + var(--right-w) + 40px);
        margin: 0 auto;
        padding: 24px 0;
        align-items: flex-start;
    }
    .feed-col { flex: 1; min-width: 0; }
    .feed-container { max-width: 100%; margin: 0; }
    .stories-section { max-width: 100%; border-left: 1px solid var(--border); border-right: 1px solid var(--border); border-radius: var(--radius-lg); }
    .camp-post { border-left: 1px solid var(--border); border-right: 1px solid var(--border); border-radius: var(--radius-lg); margin-bottom: 16px; overflow: hidden; }
    .cat-pills-wrap { display: none; }
    .mobile-search-bar { display: none; }
    .mobile-topbar { display: none; }
    .desktop-nav { left: var(--sidebar-w); }
    .main-content { padding-top: var(--nav-h); }
    .reels-section { border-radius: var(--radius-lg); border: 1px solid var(--border); margin-bottom: 16px; }
    .sugg-banner { border-radius: var(--radius-lg); border: 1px solid var(--border); margin-bottom: 16px; }
}

/* Tablet specific */
@media (min-width: 768px) and (max-width: 1023px) {
    .feed-container { padding: 16px; max-width: 600px; }
    .camp-post { border: 1px solid var(--border); border-radius: var(--radius-lg); overflow: hidden; margin-bottom: 16px; }
    .stories-section { max-width: 600px; margin: 0 auto 16px; padding: 14px 16px; border: 1px solid var(--border); border-radius: var(--radius-lg); }
}

/* ═══════════════════════════════════════
   MISC UTILS
═══════════════════════════════════════ */
.hidden { display: none !important; }

.sr-only {
    position: absolute; width: 1px; height: 1px;
    padding: 0; margin: -1px; overflow: hidden;
    clip: rect(0,0,0,0); white-space: nowrap; border: 0;
}

/* Card entry animation delay per card */
.camp-post:nth-child(1) { animation-delay: 0s; }
.camp-post:nth-child(2) { animation-delay: .06s; }
.camp-post:nth-child(3) { animation-delay: .12s; }
.camp-post:nth-child(4) { animation-delay: .18s; }
.camp-post:nth-child(5) { animation-delay: .24s; }
.camp-post:nth-child(6) { animation-delay: .3s; }
</style>

{{-- ═══ DESKTOP TOP NAV ═══ --}}
<nav class="desktop-nav" role="navigation" aria-label="Main navigation">
    <a href="/" class="d-logo">Donate<span>Bazar</span></a>
    <div class="d-search">
        <i class="fa fa-search"></i>
        <input type="text" placeholder="Search campaigns, causes...">
    </div>
    <div class="d-nav-links">
        <a href="{{ url('/') }}" class="d-nav-btn active"><i class="fa fa-home"></i> Home</a>
        <a href="{{ route('all.campaigns') }}" class="d-nav-btn"><i class="fa fa-compass"></i> Explore</a>
        <a href="#" class="d-nav-btn"><i class="fa fa-heart"></i> Saved</a>
        <a href="{{ url('/campaign/create') }}" class="d-donate-btn">+ Start Fundraiser</a>
        <div class="d-avatar">U</div>
    </div>
</nav>

<div class="app-layout">

    {{-- ═══ LEFT SIDEBAR ═══ --}}
    <aside class="left-sidebar" role="complementary" aria-label="Sidebar navigation">
        <a href="/" class="d-logo" style="font-family:'Instrument Serif',serif;font-size:20px;padding:0 10px 16px;display:block;">
            Donate<span style="color:var(--brand);font-style:italic;">Bazar</span>
        </a>

        <div class="sidebar-section">
            <div class="sidebar-section-label">Navigate</div>
            <a href="{{ url('/') }}" class="sidebar-link active"><i class="fa fa-home"></i> Home</a>
            <a href="{{ route('all.campaigns') }}" class="sidebar-link"><i class="fa fa-compass"></i> Explore</a>
            <a href="#" class="sidebar-link"><i class="fa fa-heart"></i> Saved <span class="sl-badge">3</span></a>
            <a href="#" class="sidebar-link"><i class="fa fa-bell"></i> Notifications <span class="sl-badge">5</span></a>
            <a href="#" class="sidebar-link"><i class="fa fa-user"></i> Profile</a>
        </div>

        <div class="sidebar-section">
            <div class="sidebar-section-label">Categories</div>
            @foreach($categories->take(6) as $category)
            <a href="{{ route('campaigns.byCategory', $category->slug) }}" class="sidebar-link">
                <i class="fa {{ $category->icon ?? 'fa-heart' }}"></i>
                {{ $category->name }}
            </a>
            @endforeach
        </div>

        <div class="sidebar-section">
            <a href="{{ url('/campaign/create') }}" class="sidebar-start-btn">
                <i class="fa fa-plus"></i> Start Fundraiser
            </a>
        </div>

        <div class="sidebar-section">
            <div class="sidebar-stats">
                <div class="sidebar-stat"><span class="lbl">Total Raised</span><span class="val">₹50Cr+</span></div>
                <div class="sidebar-stat"><span class="lbl">Campaigns</span><span class="val">10K+</span></div>
                <div class="sidebar-stat"><span class="lbl">Donors</span><span class="val">2.5M+</span></div>
                <div class="sidebar-stat"><span class="lbl">NGOs</span><span class="val">1,200+</span></div>
            </div>
        </div>
    </aside>

    {{-- ═══ MAIN CONTENT ═══ --}}
    <main class="main-content" role="main">

        {{-- Mobile top bar --}}
        <div class="mobile-topbar">
            <a href="/" class="m-logo">Donate<span>Bazar</span></a>
            <div class="m-topbar-icons">
                <button class="m-icon-btn" aria-label="Notifications">
                    <i class="fa fa-bell"></i>
                    <span class="m-notif-dot"></span>
                </button>
                <button class="m-icon-btn" aria-label="Messages">
                    <i class="fa fa-paper-plane"></i>
                </button>
            </div>
        </div>

        {{-- Mobile search --}}
        <div class="mobile-search-bar">
            <div class="m-search-inner">
                <i class="fa fa-search"></i>
                <input type="text" placeholder="Search campaigns, causes...">
            </div>
        </div>

        {{-- Feed wrapper (for desktop layout) --}}
        <div class="feed-wrapper">
            <div class="feed-col">

                {{-- Category pills --}}
                <div class="cat-pills-wrap" role="navigation" aria-label="Category filter">
                    <div class="cat-pills-inner" id="catPillsInner">
                        <button class="cat-pill active" data-cat="all">
                            <i class="fa fa-star"></i> All
                        </button>
                        @foreach($categories as $category)
                        <button class="cat-pill" data-cat="{{ $category->slug }}">
                            <i class="fa {{ $category->icon ?? 'fa-heart' }}"></i>
                            {{ $category->name }}
                        </button>
                        @endforeach
                    </div>
                </div>

                {{-- Stories --}}
                <div class="stories-section" aria-label="Category stories">
                    <div class="stories-row">
                        <div class="story-item">
                            <div class="story-ring-wrap add-story">
                                <div class="story-inner">
                                    <div class="story-add-icon"><i class="fa fa-plus" style="font-size:10px;"></i></div>
                                    <span style="font-size:20px;">👤</span>
                                </div>
                            </div>
                            <span class="story-label">Your Story</span>
                        </div>
                        @foreach($categories as $i => $category)
                        <div class="story-item" data-story="{{ $category->slug }}">
                            <div class="story-ring-wrap {{ $i >= 3 ? 'seen' : '' }}">
                                <div class="story-inner">
                                    <i class="fa {{ $category->icon ?? 'fa-heart' }}" style="font-size:22px;color:var(--brand);"></i>
                                </div>
                            </div>
                            <span class="story-label">{{ $category->name }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Feed --}}
                <div class="feed-container" id="feedContainer">

                    {{-- Posts loop --}}
                    @foreach($campaigns as $index => $campaign)
                    @php
                        $raised     = $campaign->donations->sum('amount');
                        $goal       = $campaign->goal_amount;
                        $pct        = $goal > 0 ? min(100, round(($raised / $goal) * 100)) : 0;
                        $donors     = $campaign->donations->count();
                        $isUrgent   = $pct < 30;
                        $daysLeft   = rand(5, 28); // replace with actual days_left if your model has it
                        $emojis     = ['🏥','📚','🌊','🌱','👶','🐾','🏠','⚡'];
                        $bgColors   = ['#fce7f3','#fef3c7','#d1fae5','#dbeafe','#ede9fe','#fef9c3'];
                        $emoji      = $emojis[$index % count($emojis)];
                        $bgColor    = $bgColors[$index % count($bgColors)];
                    @endphp

                    {{-- Insert impact reels after 2nd post --}}
                    @if($index === 2)
                    <div class="reels-section">
                        <div class="reels-header">
                            <span class="reels-title">Impact Stories</span>
                            <a href="{{ route('all.campaigns') }}" class="reels-see-all">See all →</a>
                        </div>
                        <div class="reels-row">
                            @foreach($campaigns->take(6) as $ri => $rc)
                            @php
                                $rRaised = $rc->donations->sum('amount');
                                $rGoal   = $rc->goal_amount;
                                $rPct    = $rGoal > 0 ? min(100, round(($rRaised / $rGoal) * 100)) : 0;
                                $rEmojis = ['📖','🌊','👶','🌱','🏥','🎒'];
                                $rBgs    = ['#fef3c7','#d1fae5','#fce7f3','#ede9fe','#dbeafe','#fef9c3'];
                            @endphp
                            <div class="reel-card">
                                <div class="reel-thumb" style="background:{{ $rBgs[$ri % count($rBgs)] }};">{{ $rEmojis[$ri % count($rEmojis)] }}</div>
                                <div class="reel-body">
                                    <div class="reel-name">{{ Str::limit($rc->title, 28) }}</div>
                                    <div class="reel-stat">{{ $rPct }}% funded</div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Insert suggested campaigns after 4th post --}}
                    @if($index === 4)
                    <div class="sugg-banner">
                        <div class="sugg-header">
                            <span class="sugg-title">Suggested for you</span>
                            <a href="{{ route('all.campaigns') }}" class="sugg-see">See all →</a>
                        </div>
                        <div class="sugg-grid">
                            @foreach($campaigns->skip(8)->take(4) as $si => $sc)
                            @php
                                $sRaised = $sc->donations->sum('amount');
                                $sGoal   = $sc->goal_amount;
                                $sPct    = $sGoal > 0 ? min(100, round(($sRaised / $sGoal) * 100)) : 0;
                                $sEmojis = ['🎗️','🌿','🏫','💊'];
                                $sBgs    = ['#fce7f3','#d1fae5','#dbeafe','#fef3c7'];
                            @endphp
                            <a href="{{ route('campaign.public', $sc->slug) }}" class="sugg-card">
                                <div class="sugg-img" style="background:{{ $sBgs[$si % count($sBgs)] }};">{{ $sEmojis[$si % count($sEmojis)] }}</div>
                                <div class="sugg-info">
                                    <div class="sugg-name">{{ Str::limit($sc->title, 30) }}</div>
                                    <div class="sugg-prog-bg"><div class="sugg-prog-fill" style="width:{{ $sPct }}%"></div></div>
                                    <div class="sugg-pct">{{ $sPct }}% funded</div>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Campaign post card --}}
                    <article class="camp-post {{ $index >= 6 ? 'hidden' : '' }}"
                             data-cat="{{ $campaign->category->slug ?? 'uncategorized' }}"
                             data-index="{{ $index }}"
                             aria-label="{{ $campaign->title }}">

                        {{-- Header --}}
                        <div class="post-header">
                            <div class="post-avatar-wrap">
                                <div class="post-avatar-ring"></div>
                                <div class="post-avatar" style="background:{{ $bgColor }};">{{ $emoji }}</div>
                            </div>
                            <div class="post-meta">
                                <div class="post-name">
                                    {{ Str::limit($campaign->title, 28) }}
                                    <div class="verified-icon"><i class="fa fa-check"></i></div>
                                </div>
                                <div class="post-sub">
                                    <i class="fa fa-map-marker-alt"></i>
                                    {{ $campaign->category->name ?? 'General' }}
                                    @if($campaign->city ?? false)
                                        · {{ $campaign->city }}
                                    @endif
                                </div>
                            </div>
                            <button class="post-more-btn" aria-label="More options">···</button>
                        </div>

                        {{-- Image --}}
                        <div class="post-image-wrap">
                            @if($campaign->cover_image)
                            <img src="{{ asset('storage/' . $campaign->cover_image) }}"
                                 alt="{{ $campaign->title }}"
                                 loading="{{ $index < 3 ? 'eager' : 'lazy' }}">
                            @else
                            <div class="post-image-placeholder" style="background:{{ $bgColor }};">{{ $emoji }}</div>
                            @endif

                            <div class="post-overlay-top">
                                @if($isUrgent)
                                <div class="urgency-badge">
                                    <span class="urgency-pulse"></span> Urgent
                                </div>
                                @else
                                <div></div>
                                @endif
                                <div class="days-badge"><i class="fa fa-clock"></i> {{ $daysLeft }} days left</div>
                            </div>

                            <div class="post-overlay-bottom">
                                <div class="progress-row">
                                    <span class="progress-raised">₹{{ number_format($raised) }}</span>
                                    <span class="progress-goal">of ₹{{ number_format($goal) }}</span>
                                </div>
                                <div class="progress-track">
                                    <div class="progress-fill" style="width:{{ $pct }}%;"></div>
                                </div>
                                <div class="progress-meta">
                                    <span class="progress-pct">{{ $pct }}% funded</span>
                                    <span class="progress-donors">{{ $donors }} donors</span>
                                </div>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="post-actions">
                            <button class="action-btn like-btn" aria-label="Like campaign">
                                <i class="fa-regular fa-heart"></i>
                                <span class="a-count">{{ rand(120, 2500) }}</span>
                            </button>
                            <button class="action-btn" aria-label="Comment">
                                <i class="fa-regular fa-comment"></i>
                                <span class="a-count">{{ rand(10, 200) }}</span>
                            </button>
                            <button class="action-btn" aria-label="Share">
                                <i class="fa-regular fa-paper-plane"></i>
                            </button>
                            <div class="action-spacer"></div>
                            <a href="{{ route('campaign.public', $campaign->slug) }}" class="donate-cta" aria-label="Donate to {{ $campaign->title }}">
                                <i class="fa fa-heart"></i> Donate
                            </a>
                        </div>

                        {{-- Caption --}}
                        <div class="post-caption">
                            <span class="poster">{{ Str::slug($campaign->title, '_') }}</span>
                            {{ Str::limit(strip_tags($campaign->description ?? ''), 100) }}
                            <span class="more-link">... more</span>
                            @if($campaign->category)
                            <span class="hashtag">#{{ Str::studly($campaign->category->name) }}</span>
                            @endif
                            <span class="hashtag">#DonateBazaar</span>
                        </div>

                        <button class="view-comments-btn">View all {{ rand(10, 200) }} comments</button>
                        <div class="post-time">{{ $campaign->created_at->diffForHumans() }}</div>
                    </article>

                    @endforeach

                </div>{{-- /#feedContainer --}}

                {{-- Infinite scroll trigger + loader --}}
                <div class="infinite-trigger" id="infiniteTrigger"></div>
                <div class="load-spinner" id="loadSpinner">
                    <div class="spin-ring" id="spinRing"></div>
                    <span id="spinText">Scroll to see more</span>
                </div>

            </div>{{-- /.feed-col --}}

            {{-- RIGHT SIDEBAR --}}
            <aside class="right-sidebar" role="complementary" aria-label="Trending and stats">
                <div class="right-sticky">

                    {{-- Stats --}}
                    <div class="right-section r-box" style="margin-bottom:20px;">
                        <div class="right-section-title" style="margin-bottom:12px;">Platform stats</div>
                        <div class="r-stats-grid">
                            <div class="r-stat-card">
                                <div class="r-stat-val">₹50Cr+</div>
                                <div class="r-stat-lbl">Total raised</div>
                            </div>
                            <div class="r-stat-card">
                                <div class="r-stat-val">2.5M+</div>
                                <div class="r-stat-lbl">Donors</div>
                            </div>
                            <div class="r-stat-card">
                                <div class="r-stat-val">10K+</div>
                                <div class="r-stat-lbl">Campaigns</div>
                            </div>
                            <div class="r-stat-card">
                                <div class="r-stat-val">1,200+</div>
                                <div class="r-stat-lbl">NGO partners</div>
                            </div>
                        </div>
                        <button class="r-start-btn" onclick="window.location='/campaign/create'">
                            <i class="fa fa-plus"></i> Start a Fundraiser
                        </button>
                    </div>

                    {{-- Trending --}}
                    <div class="right-section r-box" style="margin-bottom:20px;">
                        <div class="right-section-title">Trending now</div>
                        @foreach($campaigns->take(5) as $ti => $tc)
                        @php
                            $tRaised = $tc->donations->sum('amount');
                            $tGoal   = $tc->goal_amount;
                            $tPct    = $tGoal > 0 ? min(100, round(($tRaised / $tGoal) * 100)) : 0;
                            $tEmojis = ['🔥','💫','⭐','🎯','💡'];
                            $tBgs    = ['#fef3c7','#ede9fe','#d1fae5','#dbeafe','#fce7f3'];
                        @endphp
                        <a href="{{ route('campaign.public', $tc->slug) }}" class="r-trending-item">
                            <div class="r-trend-icon" style="background:{{ $tBgs[$ti % count($tBgs)] }};">{{ $tEmojis[$ti % count($tEmojis)] }}</div>
                            <div class="r-trend-info">
                                <div class="r-trend-name">{{ Str::limit($tc->title, 26) }}</div>
                                <div class="r-trend-meta">{{ $tc->category->name ?? 'General' }}</div>
                            </div>
                            <div class="r-trend-pct">{{ $tPct }}%</div>
                        </a>
                        @endforeach
                    </div>

                    {{-- Impact map --}}
                    <div class="right-section r-box" style="margin-bottom:20px;">
                        <div class="right-section-title">Lives impacted</div>
                        @php
                        $impactData = [
                            'Maharashtra'    => 82541,
                            'Rajasthan'      => 59981,
                            'Uttarakhand'    => 66423,
                            'Andhra Pradesh' => 42964,
                            'Assam'          => 27549,
                        ];
                        @endphp
                        @foreach($impactData as $state => $count)
                        <div class="r-impact-row">
                            <span class="r-impact-state">{{ $state }}</span>
                            <span class="r-impact-val">{{ number_format($count) }}</span>
                        </div>
                        @endforeach
                    </div>

                    {{-- Footer links --}}
                    <div class="r-footer">
                        <a href="#">About</a> · <a href="#">Privacy</a> · <a href="#">Terms</a> · <a href="#">Help</a> · <a href="#">Contact</a>
                        <br><br>
                        © {{ date('Y') }} DonateBazaar. All rights reserved.
                    </div>

                </div>
            </aside>

        </div>{{-- /.feed-wrapper --}}
    </main>{{-- /.main-content --}}
</div>{{-- /.app-layout --}}

{{-- ═══ MOBILE BOTTOM NAV ═══ --}}
<nav class="mobile-bottom-nav" role="navigation" aria-label="Mobile navigation">
    <div class="mob-nav-item active">
        <i class="fa fa-home"></i>
        <div class="mob-nav-dot"></div>
    </div>
    <div class="mob-nav-item" onclick="window.location='{{ route('all.campaigns') }}'">
        <i class="fa fa-compass"></i>
        <span class="mob-nav-label">Explore</span>
    </div>
    <div class="mob-nav-item" onclick="window.location='/campaign/create'">
        <div class="mob-nav-fab">
            <i class="fa fa-plus"></i>
        </div>
    </div>
    <div class="mob-nav-item">
        <i class="fa fa-heart"></i>
        <span class="mob-nav-label">Saved</span>
    </div>
    <div class="mob-nav-item">
        <i class="fa fa-user"></i>
        <span class="mob-nav-label">Profile</span>
    </div>
</nav>


{{-- ═══ SCRIPTS ═══ --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ── CATEGORY FILTER ── */
    var allPosts    = Array.from(document.querySelectorAll('.camp-post'));
    var activeCat   = 'all';

    document.querySelectorAll('.cat-pill').forEach(function (pill) {
        pill.addEventListener('click', function () {
            document.querySelectorAll('.cat-pill').forEach(function (p) { p.classList.remove('active'); });
            this.classList.add('active');
            activeCat = this.getAttribute('data-cat');
            applyFilter(activeCat);
        });
    });

    function applyFilter(cat) {
        allPosts.forEach(function (post) {
            var postCat = post.getAttribute('data-cat');
            if (cat === 'all' || postCat === cat) {
                post.classList.remove('hidden');
            } else {
                post.classList.add('hidden');
            }
        });
        resetInfinite();
    }

    /* ── INFINITE SCROLL ── */
    var visibleCount = 6;
    var loading      = false;
    var spinRing     = document.getElementById('spinRing');
    var spinText     = document.getElementById('spinText');

    function getHiddenPosts() {
        return allPosts.filter(function (p) {
            return p.classList.contains('hidden') &&
                   (activeCat === 'all' || p.getAttribute('data-cat') === activeCat);
        });
    }

    function resetInfinite() {
        visibleCount = 6;
        loading      = false;
        spinText.textContent = 'Scroll to see more';
        spinRing.style.display = 'none';
        document.getElementById('loadSpinner').style.opacity = '1';
    }

    function loadMore() {
        if (loading) return;
        var hidden = allPosts.filter(function (p) {
            return p.classList.contains('hidden') &&
                   (activeCat === 'all' || p.getAttribute('data-cat') === activeCat);
        });
        if (hidden.length === 0) {
            spinText.textContent = 'All campaigns loaded ✓';
            spinRing.style.display = 'none';
            document.getElementById('loadSpinner').style.opacity = '0.5';
            if (observer) observer.disconnect();
            return;
        }
        loading = true;
        spinRing.style.display = 'inline-block';
        spinText.textContent = 'Loading more...';

        setTimeout(function () {
            var toShow = hidden.slice(0, 6);
            toShow.forEach(function (post) {
                post.classList.remove('hidden');
            });
            visibleCount += toShow.length;
            loading = false;
            spinRing.style.display = 'none';
            spinText.textContent = 'Scroll to see more';

            if (toShow.length < 6) {
                spinText.textContent = 'All campaigns loaded ✓';
                document.getElementById('loadSpinner').style.opacity = '0.5';
                if (observer) observer.disconnect();
            }
        }, 600);
    }

    var observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) loadMore();
        });
    }, { rootMargin: '300px' });

    var trigger = document.getElementById('infiniteTrigger');
    if (trigger) observer.observe(trigger);

    /* Hide posts beyond 6 initially */
    allPosts.forEach(function (post, i) {
        if (i >= 6) post.classList.add('hidden');
    });

    /* ── LIKE BUTTON ── */
    document.querySelectorAll('.like-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            this.classList.toggle('liked');
            var icon  = this.querySelector('i');
            var count = this.querySelector('.a-count');
            var n     = parseInt(count.textContent.replace(/,/g, ''), 10);
            if (this.classList.contains('liked')) {
                icon.classList.remove('fa-regular');
                icon.classList.add('fas');
                count.textContent = (n + 1).toLocaleString('en-IN');
                icon.style.transform = 'scale(1.4)';
                setTimeout(function () { icon.style.transform = ''; }, 200);
            } else {
                icon.classList.remove('fas');
                icon.classList.add('fa-regular');
                count.textContent = (n - 1).toLocaleString('en-IN');
            }
        });
    });

    /* ── STORY RING CLICK (mark seen) ── */
    document.querySelectorAll('.story-item[data-story]').forEach(function (item) {
        item.addEventListener('click', function () {
            var ring = this.querySelector('.story-ring-wrap');
            if (ring) ring.classList.add('seen');
        });
    });

    /* ── DONATE CTA CLICK (inline feedback) ── */
    document.querySelectorAll('.donate-cta').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            /* Allow default href navigation — this just adds visual pulse */
            this.style.transform = 'scale(0.95)';
            setTimeout(function () { }, 150);
        });
    });

    /* ── MOBILE BOTTOM NAV ACTIVE ── */
    document.querySelectorAll('.mob-nav-item').forEach(function (item) {
        item.addEventListener('click', function () {
            document.querySelectorAll('.mob-nav-item').forEach(function (i) {
                i.classList.remove('active');
                var dot = i.querySelector('.mob-nav-dot');
                if (dot) dot.style.display = 'none';
            });
            this.classList.add('active');
        });
    });

    /* ── SCROLL PROGRESS FILL animation ── */
    var fills = document.querySelectorAll('.progress-fill');
    var fillObserver = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                var target = entry.target.style.width;
                entry.target.style.width = '0%';
                setTimeout(function () {
                    entry.target.style.width = target;
                }, 100);
                fillObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.3 });
    fills.forEach(function (fill) { fillObserver.observe(fill); });

});
</script>

@endsection