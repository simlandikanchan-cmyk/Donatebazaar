@extends('layouts.app')

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">

<style>
:root {
    --bg:           #f4f5fb;
    --surface:      #ffffff;
    --surface2:     #f8f9fe;
    --border:       rgba(0,0,0,0.06);
    --border2:      rgba(0,0,0,0.10);
    --text:         #0f1117;
    --text2:        #4b5563;
    --text3:        #9ca3af;
    --sidebar-bg:   #0d0e1a;
    --sidebar-text: rgba(255,255,255,0.65);
    --sidebar-act:  rgba(120,119,255,0.18);
    --accent:       #6366f1;
    --accent2:      #8b5cf6;
    --accent-glow:  rgba(99,102,241,0.18);
    --green:        #10b981;
    --yellow:       #f59e0b;
    --red:          #ef4444;
    --font:         'DM Sans', sans-serif;
    --font-mono:    'DM Mono', monospace;
    --radius:       14px;
    --radius-sm:    9px;
    --shadow:       0 1px 3px rgba(0,0,0,0.06), 0 4px 16px rgba(0,0,0,0.04);
    --transition:   0.2s ease;
}
[data-theme="dark"] {
    --bg:           #0b0c14;
    --surface:      #13141f;
    --surface2:     #1a1b2e;
    --border:       rgba(255,255,255,0.06);
    --border2:      rgba(255,255,255,0.10);
    --text:         #f0f1ff;
    --text2:        #a5b4c8;
    --text3:        #5a6579;
    --sidebar-bg:   #07080f;
    --sidebar-text: rgba(255,255,255,0.55);
    --sidebar-act:  rgba(120,119,255,0.22);
    --accent-glow:  rgba(99,102,241,0.25);
    --shadow:       0 1px 3px rgba(0,0,0,0.3), 0 4px 16px rgba(0,0,0,0.2);
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
body {
    font-family: var(--font);
    background: var(--bg);
    color: var(--text);
    line-height: 1.5;
    -webkit-font-smoothing: antialiased;
    transition: background var(--transition), color var(--transition);
}

/* ─── SHELL ─── */
.shell { display: flex; min-height: 100vh; }

/* ─── SIDEBAR ─── */
.sidebar {
    width: 256px; flex-shrink: 0;
    background: var(--sidebar-bg);
    display: flex; flex-direction: column;
    position: fixed; top: 0; left: 0; bottom: 0;
    z-index: 200; overflow-y: auto; overflow-x: hidden;
    border-right: 1px solid rgba(255,255,255,0.04);
    transition: transform 0.3s ease;
}
.s-logo {
    display: flex; align-items: center; gap: 10px;
    padding: 22px 18px 18px;
    border-bottom: 1px solid rgba(255,255,255,0.05);
}
.s-logo-mark {
    width: 36px; height: 36px; border-radius: 10px;
    background: linear-gradient(135deg, var(--accent), var(--accent2));
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; box-shadow: 0 4px 14px rgba(99,102,241,0.35);
}
.s-logo-mark svg { width: 18px; height: 18px; color: #fff; }
.s-logo-name { font-size: 17px; font-weight: 800; color: #fff; letter-spacing: -0.01em; }
.s-logo-tag  { font-size: 9px; color: rgba(255,255,255,0.3); text-transform: uppercase; letter-spacing: 0.12em; margin-top: 1px; }
.s-user {
    margin: 12px 10px 4px; padding: 10px 12px;
    background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.06);
    border-radius: var(--radius-sm); display: flex; align-items: center; gap: 9px;
}
.s-avatar {
    width: 32px; height: 32px; border-radius: 8px;
    background: linear-gradient(135deg, var(--accent), var(--accent2));
    color: #fff; font-size: 13px; font-weight: 700;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.s-user-name { font-size: 12.5px; font-weight: 600; color: rgba(255,255,255,0.85); }
.s-user-role { font-size: 10px; color: rgba(255,255,255,0.35); margin-top: 1px; }
.s-label {
    font-size: 9.5px; font-weight: 700; color: rgba(255,255,255,0.25);
    text-transform: uppercase; letter-spacing: 0.14em;
    padding: 16px 18px 5px; font-family: var(--font-mono);
}
.s-nav { padding: 0 8px; }
.s-link {
    display: flex; align-items: center; gap: 10px;
    padding: 9px 11px; border-radius: 9px;
    color: var(--sidebar-text); font-size: 13px; font-weight: 500;
    text-decoration: none;
    transition: background var(--transition), color var(--transition);
    margin-bottom: 1px; border: none; background: transparent;
    width: 100%; text-align: left; cursor: pointer; position: relative;
}
.s-link:hover  { background: rgba(255,255,255,0.06); color: rgba(255,255,255,0.9); }
.s-link.active { background: var(--sidebar-act); color: #a5b4fc; }
.s-link.active::before {
    content: ''; position: absolute; left: 0; top: 20%; bottom: 20%;
    width: 3px; border-radius: 0 2px 2px 0; background: var(--accent);
}
.s-icon { width: 16px; height: 16px; flex-shrink: 0; opacity: 0.8; }
.s-bottom { margin-top: auto; padding: 12px 8px 16px; border-top: 1px solid rgba(255,255,255,0.05); }

/* ─── MAIN ─── */
.main { margin-left: 256px; flex: 1; min-width: 0; display: flex; flex-direction: column; }

/* ─── TOPBAR ─── */
.topbar {
    display: flex; align-items: center; justify-content: space-between;
    padding: 0 28px; height: 64px;
    background: var(--surface); border-bottom: 1px solid var(--border);
    position: sticky; top: 0; z-index: 100; gap: 16px;
}
.topbar-left { display: flex; align-items: center; gap: 12px; }
.topbar-back {
    display: flex; align-items: center; justify-content: center;
    width: 32px; height: 32px; border-radius: 8px;
    border: 1px solid var(--border2); background: var(--surface2);
    color: var(--text2); cursor: pointer; text-decoration: none;
    transition: background var(--transition), color var(--transition), border-color var(--transition);
    flex-shrink: 0;
}
.topbar-back:hover { background: var(--accent-glow); color: var(--accent); border-color: var(--accent); }
.topbar-back svg { width: 14px; height: 14px; }
.topbar-title h1 { font-size: 17px; font-weight: 800; color: var(--text); letter-spacing: -0.02em; }
.topbar-title p  { font-size: 11px; color: var(--text3); margin-top: 1px; }
.topbar-right { display: flex; align-items: center; gap: 8px; }

/* theme toggle */
.theme-toggle { position: relative; }
.theme-toggle input { position: absolute; opacity: 0; width: 0; height: 0; }
.theme-toggle label {
    display: flex; align-items: center; justify-content: space-between;
    width: 52px; height: 28px; border-radius: 100px;
    background: var(--surface2); border: 1px solid var(--border2);
    cursor: pointer; padding: 3px 4px; position: relative;
}
.theme-toggle label::after {
    content: ''; width: 20px; height: 20px; border-radius: 50%;
    background: var(--accent); position: absolute; left: 4px;
    transition: transform 0.3s cubic-bezier(.4,0,.2,1);
    box-shadow: 0 2px 6px rgba(99,102,241,0.4);
}
.theme-toggle input:checked + label::after { transform: translateX(24px); }
.theme-icons { display: flex; justify-content: space-between; width: 100%; position: relative; z-index: 1; }
.theme-icons svg { width: 12px; height: 12px; color: var(--text3); }

.hamburger {
    display: none; width: 36px; height: 36px; border-radius: 10px;
    border: 1px solid var(--border2); background: var(--surface2); cursor: pointer;
    color: var(--text2); align-items: center; justify-content: center; flex-shrink: 0;
}
.hamburger svg { width: 16px; height: 16px; }

/* ─── BODY ─── */
.body { padding: 24px 28px 60px; }

/* ─── TWO-COL ─── */
.page-grid {
    display: grid;
    grid-template-columns: 1fr 280px;
    gap: 20px;
    align-items: start;
}
.right-col { position: sticky; top: 84px; display: flex; flex-direction: column; gap: 16px; }

/* ─── CARD ─── */
.card {
    background: var(--surface); border: 1px solid var(--border);
    border-radius: var(--radius); box-shadow: var(--shadow);
    overflow: hidden; animation: fadeUp 0.35s both;
}
.card:nth-child(1) { animation-delay: 0.04s; }
.card:nth-child(2) { animation-delay: 0.08s; }
.card:nth-child(3) { animation-delay: 0.12s; }
.card:nth-child(4) { animation-delay: 0.16s; }

.card-header {
    padding: 15px 20px; border-bottom: 1px solid var(--border);
    display: flex; align-items: center; gap: 10px;
}
.card-icon {
    width: 32px; height: 32px; border-radius: 9px;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.card-icon svg { width: 15px; height: 15px; }
.ic-indigo { background: rgba(99,102,241,0.12);  color: var(--accent); }
.ic-green  { background: rgba(16,185,129,0.12);  color: var(--green); }
.ic-yellow { background: rgba(245,158,11,0.12);  color: var(--yellow); }
.ic-red    { background: rgba(239,68,68,0.12);   color: var(--red); }
.ic-blue   { background: rgba(59,130,246,0.12);  color: #3b82f6; }
.card-title { font-size: 13px; font-weight: 700; color: var(--text); letter-spacing: -0.01em; }
.card-sub   { font-size: 11px; color: var(--text3); margin-top: 1px; }
.card-body  { padding: 20px; }

/* ─── FORM ELEMENTS ─── */
.field { display: flex; flex-direction: column; gap: 6px; }
.field + .field { margin-top: 16px; }

.field-label {
    font-size: 11px; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.08em;
    color: var(--text3); font-family: var(--font-mono);
}

.field-input,
.field-textarea,
.field-select {
    width: 100%;
    background: var(--surface2);
    border: 1px solid var(--border2);
    border-radius: var(--radius-sm);
    padding: 9px 12px;
    font-family: var(--font);
    font-size: 13.5px;
    color: var(--text);
    outline: none;
    transition: border-color var(--transition), box-shadow var(--transition), background var(--transition);
    appearance: none;
}
.field-input:focus,
.field-textarea:focus,
.field-select:focus {
    border-color: var(--accent);
    box-shadow: 0 0 0 3px var(--accent-glow);
    background: var(--surface);
}
.field-input::placeholder,
.field-textarea::placeholder { color: var(--text3); }
.field-textarea { resize: vertical; min-height: 110px; line-height: 1.65; }

/* number input — hide arrows */
.field-input[type="number"]::-webkit-inner-spin-button,
.field-input[type="number"]::-webkit-outer-spin-button { -webkit-appearance: none; }
.field-input[type="number"] { -moz-appearance: textfield; }

/* date/time inputs */
.field-input[type="date"],
.field-input[type="time"] { font-family: var(--font-mono); font-size: 13px; }

/* input with prefix icon */
.field-input-wrap { position: relative; }
.field-input-wrap .field-prefix {
    position: absolute; left: 11px; top: 50%; transform: translateY(-50%);
    font-size: 13px; font-weight: 600; color: var(--text3); font-family: var(--font-mono);
    pointer-events: none; user-select: none;
}
.field-input-wrap .field-input { padding-left: 26px; }

/* field grid */
.field-grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; }
.field-grid-2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 14px; }

/* ─── ERROR BLOCK ─── */
.error-block {
    background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.22);
    border-radius: var(--radius-sm); padding: 12px 16px;
    margin-bottom: 4px;
}
.error-block ul { list-style: none; display: flex; flex-direction: column; gap: 4px; }
.error-block li {
    font-size: 12.5px; color: var(--red);
    display: flex; align-items: flex-start; gap: 6px;
}
.error-block li::before { content: '·'; font-size: 18px; line-height: 1; margin-top: -1px; }

/* ─── ACTION BTNS ─── */
.action-btn {
    display: flex; align-items: center; justify-content: center; gap: 6px;
    width: 100%; padding: 10px 16px; border-radius: var(--radius-sm);
    font-size: 12.5px; font-weight: 600; cursor: pointer;
    border: 1px solid transparent; font-family: var(--font);
    transition: opacity var(--transition), transform var(--transition);
    text-decoration: none;
}
.action-btn:hover { opacity: 0.86; transform: translateY(-1px); }
.action-btn:active { transform: scale(0.98); }
.action-btn svg { width: 13px; height: 13px; }
.btn-accent {
    background: var(--accent); color: #fff; border-color: var(--accent);
    box-shadow: 0 4px 14px rgba(99,102,241,0.28);
}
.btn-ghost  { background: var(--surface2); color: var(--text2); border-color: var(--border2); }
.btn-danger { background: rgba(239,68,68,0.09); color: var(--red); border-color: rgba(239,68,68,0.2); }
.btn-danger:hover { background: rgba(239,68,68,0.16); }
.action-btn + .action-btn { margin-top: 8px; }

/* submit btn inside form */
.submit-row {
    display: flex; align-items: center; justify-content: flex-end; gap: 10px;
    padding-top: 20px; margin-top: 4px; border-top: 1px solid var(--border);
}
.btn-cancel {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 9px 18px; border-radius: var(--radius-sm);
    font-size: 12.5px; font-weight: 600; cursor: pointer;
    background: var(--surface2); color: var(--text2);
    border: 1px solid var(--border2); text-decoration: none;
    transition: background var(--transition), color var(--transition);
    font-family: var(--font);
}
.btn-cancel:hover { background: var(--border); }
.btn-submit {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 9px 22px; border-radius: var(--radius-sm);
    font-size: 12.5px; font-weight: 700; cursor: pointer;
    background: var(--accent); color: #fff;
    border: none; font-family: var(--font);
    box-shadow: 0 4px 14px rgba(99,102,241,0.28);
    transition: opacity var(--transition), transform var(--transition);
}
.btn-submit:hover  { opacity: 0.88; transform: translateY(-1px); }
.btn-submit:active { transform: scale(0.98); }
.btn-submit svg { width: 13px; height: 13px; }

/* ─── INFO ROWS (right panel) ─── */
.info-row {
    display: flex; justify-content: space-between; align-items: center;
    font-size: 12px; padding: 10px 0; border-top: 1px solid var(--border);
}
.info-row:first-child { border-top: none; padding-top: 0; }
.info-row-label { color: var(--text3); font-family: var(--font-mono); text-transform: uppercase; letter-spacing: 0.06em; font-size: 10px; }
.info-row-val   { font-weight: 600; color: var(--text2); font-size: 12px; }

/* status chip */
.status-chip {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 4px 10px; border-radius: 100px;
    font-size: 10px; font-weight: 700; letter-spacing: 0.05em;
    text-transform: uppercase; font-family: var(--font-mono);
}
.status-chip .dot { width: 5px; height: 5px; border-radius: 50%; background: currentColor; }
.chip-active    { background: rgba(16,185,129,0.12);  color: #10b981; border: 1px solid rgba(16,185,129,0.25); }
.chip-pending   { background: rgba(245,158,11,0.12);  color: #f59e0b; border: 1px solid rgba(245,158,11,0.25); }
.chip-rejected  { background: rgba(239,68,68,0.12);   color: #ef4444; border: 1px solid rgba(239,68,68,0.25); }
.chip-completed { background: rgba(99,102,241,0.12);  color: #818cf8; border: 1px solid rgba(99,102,241,0.25); }

/* ─── ANIMATION ─── */
@keyframes fadeUp {
    from { opacity: 0; transform: translateY(12px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* ─── RESPONSIVE ─── */
@media (max-width: 960px) {
    .page-grid { grid-template-columns: 1fr; }
    .right-col { position: static; }
    .field-grid-3 { grid-template-columns: 1fr 1fr; }
}
@media (max-width: 860px) {
    .sidebar { transform: translateX(-100%); }
    .sidebar.open { transform: translateX(0); }
    .main { margin-left: 0; }
    .hamburger { display: flex; }
}
@media (max-width: 600px) {
    .topbar { padding: 0 16px; }
    .body   { padding: 16px 16px 48px; }
    .field-grid-3, .field-grid-2 { grid-template-columns: 1fr; }
}
</style>

@php
    // Status chip
    if ($event->status === 'approved') {
        $chipClass = 'chip-active'; $chipLabel = 'Approved';
    } elseif ($event->status === 'pending') {
        $chipClass = 'chip-pending'; $chipLabel = 'Pending';
    } elseif ($event->status === 'completed') {
        $chipClass = 'chip-completed'; $chipLabel = 'Completed';
    } elseif ($event->status === 'rejected') {
        $chipClass = 'chip-rejected'; $chipLabel = 'Rejected';
    } else {
        $chipClass = 'chip-pending'; $chipLabel = ucfirst($event->status ?? 'Draft');
    }
@endphp

<div class="shell" id="shell">

{{-- ═══════════ SIDEBAR ═══════════ --}}
<aside class="sidebar" id="sidebar">
    <div class="s-logo">
        <div class="s-logo-mark">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
        </div>
        <div>
            <div class="s-logo-name">DonateBazaar</div>
            <div class="s-logo-tag">My Portal</div>
        </div>
    </div>

    <div class="s-user">
        <div class="s-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}</div>
        <div>
            <div class="s-user-name">{{ auth()->user()->name ?? 'User' }}</div>
            <div class="s-user-role">Fundraiser</div>
        </div>
    </div>

    <div class="s-label">Navigation</div>
    <nav class="s-nav">
        <a href="{{ route('dashboard') }}" class="s-link">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/>
                <rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>
            </svg>
            Dashboard
        </a>
        <a href="{{ route('campaign.create') }}" class="s-link">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            New Campaign
        </a>
    </nav>

    <div class="s-label">This Event</div>
    <nav class="s-nav">
        <a href="{{ route('campaign.show', $event->campaign->id) }}" class="s-link">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
            Parent Campaign
        </a>
        <a href="{{ route('events.show', $event->id) }}" class="s-link">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
            Event Overview
        </a>
        <a href="#" class="s-link active">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Edit Event
        </a>
    </nav>

    <div class="s-bottom">
        <a href="{{ route('logout') }}"
           onclick="event.preventDefault(); document.getElementById('lf').submit();"
           class="s-link" style="color: rgba(248,113,113,0.75);">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
            </svg>
            Sign Out
        </a>
        <form id="lf" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
    </div>
</aside>

{{-- ═══════════ MAIN ═══════════ --}}
<div class="main">

    {{-- TOPBAR --}}
    <header class="topbar">
        <div class="topbar-left">
            <button class="hamburger" id="hamburger" aria-label="Menu">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <a href="{{ route('events.show', $event->id) }}" class="topbar-back" title="Back to Event">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5M12 5l-7 7 7 7"/>
                </svg>
            </a>
            <div class="topbar-title">
                <h1>Edit Event</h1>
                <p>{{ Str::limit($event->title, 45) }}</p>
            </div>
        </div>
        <div class="topbar-right">
            <div class="theme-toggle">
                <input type="checkbox" id="themeToggle">
                <label for="themeToggle">
                    <div class="theme-icons">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="5"/><path stroke-linecap="round" d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/></svg>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
                    </div>
                </label>
            </div>
            <div style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,var(--accent),var(--accent2));color:#fff;font-size:13px;font-weight:700;display:flex;align-items:center;justify-content:center;cursor:pointer;flex-shrink:0;">
                {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
            </div>
        </div>
    </header>

    {{-- BODY --}}
    <div class="body">
        <div class="page-grid">

            {{-- ════ LEFT — FORM ════ --}}
            <div>

                {{-- Validation errors --}}
                @if ($errors->any())
                <div class="card" style="margin-bottom:16px;">
                    <div class="card-header">
                        <div class="card-icon ic-red">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        </div>
                        <div>
                            <div class="card-title">Please fix the following errors</div>
                            <div class="card-sub">{{ $errors->count() }} {{ Str::plural('issue', $errors->count()) }} found</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="error-block">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                @endif

                <form action="{{ route('events.update', $event->id) }}" method="POST" id="editForm">
                    @csrf
                    @method('PUT')

                    {{-- ── Basic Info ── --}}
                    <div class="card" style="margin-bottom:16px;">
                        <div class="card-header">
                            <div class="card-icon ic-indigo">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <div>
                                <div class="card-title">Basic Information</div>
                                <div class="card-sub">Event title and description</div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="field">
                                <label class="field-label" for="title">Event Title</label>
                                <input
                                    type="text"
                                    id="title"
                                    name="title"
                                    class="field-input"
                                    value="{{ old('title', $event->title) }}"
                                    placeholder="Enter a clear, compelling title…"
                                    required>
                            </div>
                            <div class="field">
                                <label class="field-label" for="description">Description</label>
                                <textarea
                                    id="description"
                                    name="description"
                                    class="field-textarea"
                                    placeholder="Describe what this event is about…"
                                    required>{{ old('description', $event->description) }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- ── Schedule ── --}}
                    <div class="card" style="margin-bottom:16px;">
                        <div class="card-header">
                            <div class="card-icon ic-yellow">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            </div>
                            <div>
                                <div class="card-title">Schedule</div>
                                <div class="card-sub">Date and timings</div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="field-grid-3">
                                <div class="field">
                                    <label class="field-label" for="event_date">Event Date</label>
                                    <input
                                        type="date"
                                        id="event_date"
                                        name="event_date"
                                        class="field-input"
                                        value="{{ old('event_date', $event->event_date) }}"
                                        required>
                                </div>
                                <div class="field">
                                    <label class="field-label" for="start_time">Start Time</label>
                                    <input
                                        type="time"
                                        id="start_time"
                                        name="start_time"
                                        class="field-input"
                                        value="{{ old('start_time', $event->start_time) }}">
                                </div>
                                <div class="field">
                                    <label class="field-label" for="end_time">End Time</label>
                                    <input
                                        type="time"
                                        id="end_time"
                                        name="end_time"
                                        class="field-input"
                                        value="{{ old('end_time', $event->end_time) }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ── Fundraising & Capacity ── --}}
                    <div class="card" style="margin-bottom:16px;">
                        <div class="card-header">
                            <div class="card-icon ic-green">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <div class="card-title">Fundraising &amp; Capacity</div>
                                <div class="card-sub">Goal and participant limits</div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="field-grid-2">
                                <div class="field">
                                    <label class="field-label" for="goal_amount">Goal Amount</label>
                                    <div class="field-input-wrap">
                                        <span class="field-prefix">₹</span>
                                        <input
                                            type="number"
                                            id="goal_amount"
                                            name="goal_amount"
                                            class="field-input"
                                            step="0.01"
                                            min="0"
                                            value="{{ old('goal_amount', $event->goal_amount) }}"
                                            placeholder="0.00">
                                    </div>
                                </div>
                                <div class="field">
                                    <label class="field-label" for="max_participants">Max Participants</label>
                                    <input
                                        type="number"
                                        id="max_participants"
                                        name="max_participants"
                                        class="field-input"
                                        min="0"
                                        value="{{ old('max_participants', $event->max_participants) }}"
                                        placeholder="Unlimited">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ── Submit row ── --}}
                    <div class="submit-row">
                        <a href="{{ route('events.show', $event->id) }}" class="btn-cancel">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5M12 5l-7 7 7 7"/></svg>
                            Cancel
                        </a>
                        <button type="submit" class="btn-submit">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            Save Changes
                        </button>
                    </div>

                </form>
            </div>

            {{-- ════ RIGHT ════ --}}
            <div class="right-col">

                {{-- Current status --}}
                <div class="card">
                    <div class="card-header">
                        <div class="card-icon ic-blue">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        </div>
                        <div>
                            <div class="card-title">Event Info</div>
                        </div>
                    </div>
                    <div class="card-body" style="display:flex;flex-direction:column;">
                        <div class="info-row">
                            <span class="info-row-label">Status</span>
                            <span class="status-chip {{ $chipClass }}"><span class="dot"></span>{{ $chipLabel }}</span>
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
                        <div class="info-row">
                            <span class="info-row-label">Campaign</span>
                            <span class="info-row-val" style="font-size:11px;color:var(--accent);max-width:130px;text-align:right;">{{ Str::limit($event->campaign->title, 22) }}</span>
                        </div>
                    </div>
                </div>

                {{-- Quick actions --}}
                <div class="card">
                    <div class="card-header">
                        <div class="card-icon ic-indigo">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <div>
                            <div class="card-title">Quick Actions</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <button type="submit" form="editForm" class="action-btn btn-accent">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            Save Changes
                        </button>
                        <a href="{{ route('events.show', $event->id) }}" class="action-btn btn-ghost">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            View Event
                        </a>
                        <a href="{{ route('campaign.show', $event->campaign->id) }}" class="action-btn btn-ghost">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5M12 5l-7 7 7 7"/></svg>
                            Back to Campaign
                        </a>
                    </div>
                </div>

                {{-- Tips --}}
                <div class="card">
                    <div class="card-header">
                        <div class="card-icon ic-yellow">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                        </div>
                        <div>
                            <div class="card-title">Tips</div>
                        </div>
                    </div>
                    <div class="card-body" style="display:flex;flex-direction:column;gap:10px;">
                        <p style="font-size:12px;color:var(--text3);line-height:1.65;">A clear title and description help donors find and trust your event.</p>
                        <p style="font-size:12px;color:var(--text3);line-height:1.65;">Setting a goal amount shows your progress and motivates donors.</p>
                        <p style="font-size:12px;color:var(--text3);line-height:1.65;">Leave Max Participants blank for unlimited registrations.</p>
                    </div>
                </div>

            </div>
        </div>
    </div>{{-- /.body --}}
</div>{{-- /.main --}}
</div>{{-- /.shell --}}

<script>
(function () {
    'use strict';
    var html   = document.documentElement;
    var toggle = document.getElementById('themeToggle');
    var saved  = localStorage.getItem('theme') || 'light';
    if (saved === 'dark') { html.setAttribute('data-theme', 'dark'); toggle.checked = true; }
    toggle.addEventListener('change', function () {
        var t = this.checked ? 'dark' : 'light';
        html.setAttribute('data-theme', t);
        localStorage.setItem('theme', t);
    });
    var sidebar   = document.getElementById('sidebar');
    var hamburger = document.getElementById('hamburger');
    hamburger.addEventListener('click', function () { sidebar.classList.toggle('open'); });
    document.addEventListener('click', function (e) {
        if (window.innerWidth <= 860 && !sidebar.contains(e.target) && !hamburger.contains(e.target)) {
            sidebar.classList.remove('open');
        }
    });
})();
</script>

@endsection