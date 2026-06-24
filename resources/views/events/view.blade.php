<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ $event->title }} — DonateBazaar</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">

@php
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
    $raised     = $event->raised_amount ?? 0;
    $goalAmount = $event->goal_amount   ?? 0;
    $percentage = ($goalAmount > 0) ? min(100, round(($raised / $goalAmount) * 100)) : 0;
    $remaining  = max(0, $goalAmount - $raised);
    $registered = $event->registered_count ?? 0;
    $maxPart    = $event->max_participants  ?? 0;
    $partPct    = ($maxPart > 0) ? min(100, round(($registered / $maxPart) * 100)) : 0;
@endphp

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
    --blue:         #3b82f6;
    --font:         'DM Sans', sans-serif;
    --font-mono:    'DM Mono', monospace;
    --radius:       14px;
    --radius-sm:    9px;
    --shadow:       0 1px 3px rgba(0,0,0,0.06), 0 4px 16px rgba(0,0,0,0.04);
    --shadow-lg:    0 8px 40px rgba(0,0,0,0.12);
    --transition:   0.2s ease;
    --sb-w:         256px;
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
    --shadow-lg:    0 8px 40px rgba(0,0,0,0.5);
}

*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
html,body{height:100%;}
body{
    font-family:var(--font);background:var(--bg);color:var(--text);
    line-height:1.5;-webkit-font-smoothing:antialiased;overflow-x:hidden;
    transition:background var(--transition),color var(--transition);
}
a{text-decoration:none;color:inherit;}

/* ── SHELL ── */
.shell{display:flex;min-height:100vh;}

/* ── SIDEBAR ── */
.sidebar{
    width:var(--sb-w);flex-shrink:0;background:var(--sidebar-bg);
    display:flex;flex-direction:column;
    position:fixed;top:0;left:0;bottom:0;z-index:300;
    overflow-y:auto;overflow-x:hidden;
    border-right:1px solid rgba(255,255,255,0.04);
    transition:transform 0.3s cubic-bezier(.4,0,.2,1);
}
.sidebar::-webkit-scrollbar{width:0;}
.s-logo{
    display:flex;align-items:center;gap:10px;
    padding:22px 18px 18px;border-bottom:1px solid rgba(255,255,255,0.05);
}
.s-logo-mark{
    width:36px;height:36px;border-radius:10px;
    background:linear-gradient(135deg,var(--accent),var(--accent2));
    display:flex;align-items:center;justify-content:center;flex-shrink:0;
    box-shadow:0 4px 14px rgba(99,102,241,0.35);
}
.s-logo-mark svg{width:18px;height:18px;color:#fff;}
.s-logo-name{font-size:17px;font-weight:700;color:#fff;letter-spacing:-0.01em;}
.s-logo-tag{font-size:9px;color:rgba(255,255,255,0.3);text-transform:uppercase;letter-spacing:0.12em;margin-top:1px;}
.s-user{
    margin:12px 10px 4px;padding:10px 12px;
    background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.06);
    border-radius:var(--radius-sm);display:flex;align-items:center;gap:9px;
}
.s-avatar{
    width:32px;height:32px;border-radius:8px;
    background:linear-gradient(135deg,var(--accent),var(--accent2));
    color:#fff;font-size:13px;font-weight:700;
    display:flex;align-items:center;justify-content:center;flex-shrink:0;
}
.s-user-name{font-size:12.5px;font-weight:600;color:rgba(255,255,255,0.85);}
.s-user-role{font-size:10px;color:rgba(255,255,255,0.35);margin-top:1px;}
.s-label{
    font-size:9.5px;font-weight:700;color:rgba(255,255,255,0.25);
    text-transform:uppercase;letter-spacing:0.14em;
    padding:16px 18px 5px;font-family:var(--font-mono);
}
.s-nav{padding:0 8px;}
.s-link{
    display:flex;align-items:center;gap:10px;padding:9px 11px;border-radius:9px;
    color:var(--sidebar-text);font-size:13px;font-weight:500;text-decoration:none;
    transition:background var(--transition),color var(--transition);
    margin-bottom:1px;border:none;background:transparent;
    width:100%;text-align:left;cursor:pointer;position:relative;font-family:var(--font);
}
.s-link:hover{background:rgba(255,255,255,0.06);color:rgba(255,255,255,0.9);}
.s-link.active{background:var(--sidebar-act);color:#a5b4fc;}
.s-link.active::before{
    content:'';position:absolute;left:0;top:20%;bottom:20%;
    width:3px;border-radius:0 2px 2px 0;background:var(--accent);
}
.s-icon{width:16px;height:16px;flex-shrink:0;opacity:0.8;}
.s-link.active .s-icon,.s-link:hover .s-icon{opacity:1;}
.s-divider{height:1px;background:rgba(255,255,255,0.05);margin:8px 18px;}
.s-bottom{margin-top:auto;padding:12px 8px 16px;border-top:1px solid rgba(255,255,255,0.05);}

/* ── MAIN ── */
.main{margin-left:var(--sb-w);flex:1;min-width:0;display:flex;flex-direction:column;min-height:100vh;}

/* ── TOPBAR ── */
.topbar{
    display:flex;align-items:center;justify-content:space-between;
    padding:0 28px;height:64px;
    background:var(--surface);border-bottom:1px solid var(--border);
    position:sticky;top:0;z-index:100;gap:16px;
}
.topbar-left{display:flex;align-items:center;gap:12px;}
.topbar-back{
    display:flex;align-items:center;justify-content:center;
    width:34px;height:34px;border-radius:9px;
    border:1px solid var(--border2);background:var(--surface2);
    color:var(--text2);cursor:pointer;text-decoration:none;flex-shrink:0;
    transition:all var(--transition);
}
.topbar-back:hover{background:var(--accent-glow);color:var(--accent);border-color:var(--accent);}
.topbar-back svg{width:14px;height:14px;}
.topbar-title h1{font-size:17px;font-weight:700;color:var(--text);letter-spacing:-0.02em;}
.topbar-title p{font-size:11px;color:var(--text3);margin-top:1px;font-family:var(--font-mono);}
.topbar-right{display:flex;align-items:center;gap:8px;}

/* status chips */
.status-chip{
    display:inline-flex;align-items:center;gap:6px;
    padding:4px 12px;border-radius:100px;
    font-size:11px;font-weight:700;letter-spacing:0.05em;
    text-transform:uppercase;font-family:var(--font-mono);
}
.status-chip .dot{width:6px;height:6px;border-radius:50%;background:currentColor;flex-shrink:0;}
.chip-active   {background:rgba(16,185,129,0.12);color:#10b981;border:1px solid rgba(16,185,129,0.25);}
.chip-pending  {background:rgba(245,158,11,0.12);color:#f59e0b;border:1px solid rgba(245,158,11,0.25);}
.chip-rejected {background:rgba(239,68,68,0.12); color:#ef4444;border:1px solid rgba(239,68,68,0.25);}
.chip-completed{background:rgba(99,102,241,0.12);color:#818cf8;border:1px solid rgba(99,102,241,0.25);}

/* theme toggle */
.theme-toggle{position:relative;}
.theme-toggle input{position:absolute;opacity:0;width:0;height:0;}
.theme-toggle label{
    display:flex;align-items:center;justify-content:space-between;
    width:52px;height:28px;border-radius:100px;
    background:var(--surface2);border:1px solid var(--border2);
    cursor:pointer;padding:3px 4px;position:relative;
}
.theme-toggle label::after{
    content:'';width:20px;height:20px;border-radius:50%;background:var(--accent);
    position:absolute;left:4px;transition:transform 0.3s cubic-bezier(.4,0,.2,1);
    box-shadow:0 2px 6px rgba(99,102,241,0.4);
}
.theme-toggle input:checked + label::after{transform:translateX(24px);}
.theme-icons{display:flex;justify-content:space-between;width:100%;position:relative;z-index:1;}
.theme-icons svg{width:12px;height:12px;color:var(--text3);}

.t-av{
    width:34px;height:34px;border-radius:9px;
    background:linear-gradient(135deg,var(--accent),var(--accent2));
    color:#fff;font-size:13px;font-weight:700;
    display:flex;align-items:center;justify-content:center;flex-shrink:0;
    box-shadow:0 2px 8px rgba(99,102,241,0.3);
}

.hamburger{
    display:none;width:36px;height:36px;border-radius:9px;
    border:1px solid var(--border2);background:var(--surface2);
    cursor:pointer;color:var(--text2);align-items:center;justify-content:center;flex-shrink:0;
}
.hamburger svg{width:16px;height:16px;}

/* ── BODY ── */
.body{padding:24px 28px 60px;flex:1;}

/* ── TWO-COL LAYOUT ── */
.page-grid{display:grid;grid-template-columns:1fr 300px;gap:20px;align-items:start;}
.right-col{position:sticky;top:84px;display:flex;flex-direction:column;gap:16px;}

/* ── CARD ── */
.card{
    background:var(--surface);border:1px solid var(--border);
    border-radius:var(--radius);box-shadow:var(--shadow);overflow:hidden;
    animation:fadeUp 0.4s both;margin-bottom:16px;
}
.card:last-child{margin-bottom:0;}
.right-col .card{margin-bottom:0;}
.card-header{
    padding:15px 20px;border-bottom:1px solid var(--border);
    display:flex;align-items:center;justify-content:space-between;gap:10px;
}
.card-header-left{display:flex;align-items:center;gap:10px;}
.card-icon{width:32px;height:32px;border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.card-icon svg{width:15px;height:15px;}
.ic-indigo{background:rgba(99,102,241,0.12);color:var(--accent);}
.ic-green {background:rgba(16,185,129,0.12);color:var(--green);}
.ic-yellow{background:rgba(245,158,11,0.12);color:var(--yellow);}
.ic-pink  {background:rgba(236,72,153,0.12);color:#ec4899;}
.ic-blue  {background:rgba(59,130,246,0.12);color:var(--blue);}
.card-title{font-size:13px;font-weight:700;color:var(--text);letter-spacing:-0.01em;}
.card-sub  {font-size:11px;color:var(--text3);margin-top:1px;}
.card-body {padding:20px;}

/* ── EVENT HEADER BLOCK ── */
.event-header-block{padding:20px;border-bottom:1px solid var(--border);}
.event-campaign-link{
    display:inline-flex;align-items:center;gap:5px;
    font-size:11px;font-weight:600;color:var(--accent);text-decoration:none;
    font-family:var(--font-mono);text-transform:uppercase;letter-spacing:0.06em;
    margin-bottom:10px;transition:opacity var(--transition);
}
.event-campaign-link:hover{opacity:0.7;}
.event-campaign-link svg{width:11px;height:11px;}
.event-header-block h1{
    font-size:22px;font-weight:800;color:var(--text);
    letter-spacing:-0.02em;line-height:1.3;margin-bottom:6px;
}
.event-meta-row{display:flex;align-items:center;gap:10px;flex-wrap:wrap;margin-top:8px;}
.event-meta-item{
    display:inline-flex;align-items:center;gap:5px;
    font-size:11px;color:var(--text3);font-family:var(--font-mono);
}
.event-meta-item svg{width:12px;height:12px;}

/* ── DESCRIPTION ── */
.desc-text{font-size:14px;color:var(--text2);line-height:1.75;}

/* ── TIME GRID ── */
.time-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:12px;}
.time-tile{
    background:var(--surface2);border:1px solid var(--border);
    border-radius:var(--radius-sm);padding:14px 16px;
    display:flex;flex-direction:column;gap:4px;
    transition:border-color var(--transition);
}
.time-tile:hover{border-color:rgba(99,102,241,0.2);}
.time-tile-label{
    font-size:9.5px;font-weight:700;color:var(--text3);
    text-transform:uppercase;letter-spacing:0.12em;font-family:var(--font-mono);
}
.time-tile-val{font-size:15px;font-weight:700;color:var(--text);font-family:var(--font-mono);}

/* ── PROGRESS ── */
.prog-numbers{display:flex;justify-content:space-between;align-items:baseline;margin-bottom:10px;}
.prog-raised{font-size:28px;font-weight:800;color:var(--accent);letter-spacing:-0.03em;font-family:var(--font-mono);line-height:1;}
.prog-goal  {font-size:12px;color:var(--text3);font-family:var(--font-mono);}
.prog-bar   {width:100%;background:var(--surface2);border-radius:100px;height:6px;overflow:hidden;margin-bottom:6px;}
.prog-fill  {height:100%;border-radius:100px;background:linear-gradient(90deg,var(--accent),var(--accent2));transition:width 1.2s ease;}
.prog-pct   {font-size:11px;color:var(--text3);font-family:var(--font-mono);}

/* ── PARTICIPANTS ── */
.participants-numbers{display:flex;justify-content:space-between;align-items:baseline;margin-bottom:8px;}
.participants-val{font-size:24px;font-weight:800;color:var(--green);font-family:var(--font-mono);line-height:1;}
.participants-max{font-size:12px;color:var(--text3);font-family:var(--font-mono);}
.part-bar {width:100%;background:var(--surface2);border-radius:100px;height:6px;overflow:hidden;margin-bottom:6px;}
.part-fill{height:100%;border-radius:100px;background:linear-gradient(90deg,var(--green),#34d399);transition:width 1.2s ease;}
.part-pct {font-size:11px;color:var(--text3);font-family:var(--font-mono);}

/* ── MINI STATS ── */
.mini-stats{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-top:16px;}
.mini-stat{
    background:var(--surface2);border:1px solid var(--border);
    border-radius:var(--radius-sm);padding:12px;text-align:center;
}
.mini-stat-val{font-size:18px;font-weight:800;color:var(--text);font-family:var(--font-mono);line-height:1;}
.mini-stat-lbl{font-size:10px;color:var(--text3);margin-top:4px;font-family:var(--font-mono);text-transform:uppercase;letter-spacing:0.06em;}

/* ── ACTION BUTTONS ── */
.action-btn{
    display:flex;align-items:center;justify-content:center;gap:6px;
    width:100%;padding:10px 16px;border-radius:var(--radius-sm);
    font-size:12.5px;font-weight:600;cursor:pointer;
    border:1px solid var(--border2);background:var(--surface2);
    color:var(--text2);font-family:var(--font);text-decoration:none;
    transition:all var(--transition);margin-bottom:8px;
}
.action-btn:last-child{margin-bottom:0;}
.action-btn:hover{background:var(--accent-glow);color:var(--accent);border-color:var(--accent);transform:translateY(-1px);}
.action-btn svg{width:13px;height:13px;}
.btn-accent{
    background:linear-gradient(135deg,var(--accent),var(--accent2));
    color:#fff;border-color:transparent;
    box-shadow:0 4px 14px rgba(99,102,241,0.28);
}
.btn-accent:hover{opacity:.9;background:linear-gradient(135deg,var(--accent),var(--accent2));color:#fff;border-color:transparent;}

/* ── INFO ROWS ── */
.info-row{
    display:flex;justify-content:space-between;align-items:center;
    font-size:12px;padding:10px 0;border-top:1px solid var(--border);
}
.info-row:first-child{border-top:none;padding-top:0;}
.info-row-label{color:var(--text3);font-family:var(--font-mono);text-transform:uppercase;letter-spacing:0.06em;font-size:10px;}
.info-row-val  {font-weight:600;color:var(--text2);font-size:12px;}

/* ── COUNTDOWN BANNER ── */
.countdown-banner{
    background:linear-gradient(135deg,rgba(99,102,241,0.1),rgba(139,92,246,0.08));
    border:1px solid rgba(99,102,241,0.2);border-radius:var(--radius-sm);
    padding:14px 16px;margin-bottom:20px;
    display:flex;align-items:center;gap:12px;
}
.countdown-banner svg{width:18px;height:18px;color:var(--accent);flex-shrink:0;}
.countdown-text{font-size:13px;font-weight:600;color:var(--accent);}
.countdown-sub{font-size:11px;color:var(--text3);margin-top:2px;font-family:var(--font-mono);}

/* ── ANIMATIONS ── */
@keyframes fadeUp{from{opacity:0;transform:translateY(14px);}to{opacity:1;transform:translateY(0);}}
.d1{animation-delay:.05s}.d2{animation-delay:.10s}.d3{animation-delay:.15s}.d4{animation-delay:.20s}.d5{animation-delay:.25s}

::-webkit-scrollbar{width:5px;height:5px;}
::-webkit-scrollbar-track{background:transparent;}
::-webkit-scrollbar-thumb{background:var(--border2);border-radius:100px;}

/* ── RESPONSIVE ── */
@media(max-width:960px){
    .page-grid{grid-template-columns:1fr;}
    .right-col{position:static;}
}
@media(max-width:860px){
    .sidebar{transform:translateX(-100%);}
    .sidebar.open{transform:translateX(0);}
    .main{margin-left:0;}
    .hamburger{display:flex;}
}
@media(max-width:600px){
    .topbar{padding:0 16px;}
    .body{padding:16px 16px 48px;}
    .event-header-block h1{font-size:18px;}
    .prog-raised{font-size:22px;}
}
</style>
</head>
<body>
<div class="shell">

{{-- ══════════ SIDEBAR ══════════ --}}
<aside class="sidebar" id="sidebar">

    <div class="s-logo">
        <div class="s-logo-mark">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
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
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
            Dashboard
        </a>
        <a href="{{ route('campaign.create') }}" class="s-link">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            New Campaign
        </a>
    </nav>

    <div class="s-label">This Event</div>
    <nav class="s-nav">
        <a href="{{ route('campaign.show', $event->campaign->id) }}" class="s-link">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            Parent Campaign
        </a>
        <a href="#" class="s-link active">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            Event Overview
        </a>
        <a href="{{ route('events.edit', $event->id) }}" class="s-link">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Edit Event
        </a>
    </nav>

    <div class="s-divider"></div>
    <div class="s-bottom">
        <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('lf').submit();" class="s-link" style="color:rgba(248,113,113,0.75);">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
            Sign Out
        </a>
        <form id="lf" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
    </div>

</aside>

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