<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Create Event — {{ Str::limit($campaign->title, 40) }}</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
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
.card-header{
    padding:15px 20px;border-bottom:1px solid var(--border);
    display:flex;align-items:center;gap:10px;
}
.card-icon{width:32px;height:32px;border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.card-icon svg{width:15px;height:15px;}
.ic-indigo{background:rgba(99,102,241,0.12);color:var(--accent);}
.ic-green {background:rgba(16,185,129,0.12);color:var(--green);}
.ic-yellow{background:rgba(245,158,11,0.12);color:var(--yellow);}
.ic-pink  {background:rgba(236,72,153,0.12);color:#ec4899;}
.card-title{font-size:13px;font-weight:700;color:var(--text);letter-spacing:-0.01em;}
.card-sub  {font-size:11px;color:var(--text3);margin-top:1px;}
.card-body {padding:20px;}

/* ── FORM ── */
.form-section{margin-bottom:18px;}
.form-section:last-child{margin-bottom:0;}
.form-label{
    display:block;font-size:11px;font-weight:600;color:var(--text2);
    text-transform:uppercase;letter-spacing:0.08em;font-family:var(--font-mono);margin-bottom:6px;
}
.form-label .req{color:var(--red);margin-left:2px;}

.form-input,
.form-textarea,
.form-select{
    width:100%;background:var(--surface2);border:1px solid var(--border2);
    border-radius:var(--radius-sm);padding:10px 13px;
    font-size:13px;color:var(--text);font-family:var(--font);outline:none;
    transition:border-color var(--transition),box-shadow var(--transition),background var(--transition);
    appearance:none;-webkit-appearance:none;
}
.form-input::placeholder,.form-textarea::placeholder{color:var(--text3);}
.form-input:focus,.form-textarea:focus,.form-select:focus{
    border-color:var(--accent);box-shadow:0 0 0 3px var(--accent-glow);background:var(--surface);
}
.form-input.err,.form-textarea.err{border-color:var(--red);box-shadow:0 0 0 3px rgba(239,68,68,0.10);}
.form-textarea{resize:vertical;min-height:100px;line-height:1.65;}

.input-wrap{position:relative;}
.input-wrap .input-icon{
    position:absolute;left:12px;top:50%;transform:translateY(-50%);
    width:14px;height:14px;color:var(--text3);pointer-events:none;
}
.input-wrap .form-input{padding-left:36px;}
.input-prefix{
    position:absolute;left:0;top:0;bottom:0;
    display:flex;align-items:center;padding:0 12px;
    font-size:13px;font-weight:600;color:var(--text3);font-family:var(--font-mono);
    border-right:1px solid var(--border2);background:var(--surface2);
    border-radius:var(--radius-sm) 0 0 var(--radius-sm);pointer-events:none;
}
.has-prefix{padding-left:44px;border-radius:0 var(--radius-sm) var(--radius-sm) 0 !important;}

.form-hint{font-size:11px;color:var(--text3);margin-top:5px;font-family:var(--font-mono);}
.form-hint.err-msg{color:var(--red);}

.form-row{display:grid;grid-template-columns:1fr 1fr;gap:14px;}

/* ── FLASH ── */
.alert{
    display:flex;align-items:flex-start;gap:10px;
    padding:12px 14px;border-radius:var(--radius-sm);
    font-size:12.5px;margin-bottom:18px;border:1px solid transparent;
    animation:fadeUp 0.35s both;
}
.alert svg{width:15px;height:15px;flex-shrink:0;margin-top:1px;}
.alert-success{background:rgba(16,185,129,0.08);border-color:rgba(16,185,129,0.25);color:#065f46;}
.alert-error  {background:rgba(239,68,68,0.08); border-color:rgba(239,68,68,0.25); color:#991b1b;}
[data-theme="dark"] .alert-success{color:#6ee7b7;}
[data-theme="dark"] .alert-error  {color:#fca5a5;}
.alert ul{list-style:disc;padding-left:16px;display:flex;flex-direction:column;gap:3px;}

/* ── SUBMIT BTN ── */
.submit-btn{
    display:flex;align-items:center;justify-content:center;gap:8px;
    width:100%;padding:13px 20px;border-radius:var(--radius-sm);
    font-size:14px;font-weight:700;cursor:pointer;border:none;font-family:var(--font);
    background:linear-gradient(135deg,var(--accent),var(--accent2));color:#fff;
    box-shadow:0 4px 18px rgba(99,102,241,0.35);letter-spacing:-0.01em;
    transition:opacity var(--transition),transform var(--transition),box-shadow var(--transition);
}
.submit-btn:hover{opacity:.92;transform:translateY(-1px);box-shadow:0 7px 24px rgba(99,102,241,0.45);}
.submit-btn:active{transform:translateY(0);}
.submit-btn svg{width:15px;height:15px;}

/* ── RIGHT COL BTNS ── */
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

/* campaign preview */
.preview-thumb{width:100%;height:100px;object-fit:cover;border-radius:var(--radius-sm);margin-bottom:12px;display:block;}
.preview-title{font-size:14px;font-weight:700;color:var(--text);letter-spacing:-0.01em;line-height:1.4;margin-bottom:5px;}
.preview-meta {font-size:11px;color:var(--text3);font-family:var(--font-mono);}
.prog-bar{width:100%;background:var(--surface2);border-radius:100px;height:5px;overflow:hidden;margin:10px 0 4px;}
.prog-fill{height:100%;border-radius:100px;background:linear-gradient(90deg,var(--accent),var(--accent2));}
.prog-pct{font-size:10px;color:var(--text3);font-family:var(--font-mono);}

/* tips */
.tips-list{display:flex;flex-direction:column;gap:12px;}
.tip-row{display:flex;align-items:flex-start;gap:10px;}
.tip-icon{width:26px;height:26px;border-radius:7px;background:rgba(99,102,241,0.10);display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.tip-icon svg{width:13px;height:13px;color:var(--accent);}
.tip-text{font-size:11.5px;color:var(--text2);line-height:1.6;padding-top:3px;}

/* ── ANIMATIONS ── */
@keyframes fadeUp{from{opacity:0;transform:translateY(14px);}to{opacity:1;transform:translateY(0);}}
.d1{animation-delay:.05s}.d2{animation-delay:.10s}.d3{animation-delay:.15s}.d4{animation-delay:.20s}

/* scrollbar */
::-webkit-scrollbar{width:5px;height:5px;}
::-webkit-scrollbar-track{background:transparent;}
::-webkit-scrollbar-thumb{background:var(--border2);border-radius:100px;}

/* ── RESPONSIVE ── */
@media(max-width:960px){
    .page-grid{grid-template-columns:1fr;}
    .right-col{position:static;}
    .form-row{grid-template-columns:1fr;}
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

    <div class="s-label">This Campaign</div>
    <nav class="s-nav">
        <a href="{{ route('campaign.show', $campaign->id) }}" class="s-link">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            Overview
        </a>
        <a href="{{ route('campaign.edit', $campaign->id) }}" class="s-link">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Edit Campaign
        </a>
        <a href="#" class="s-link active">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            Add Event
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
            <a href="{{ route('campaign.show', $campaign->id) }}" class="topbar-back" title="Back to Campaign">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5M12 5l-7 7 7 7"/></svg>
            </a>
            <div class="topbar-title">
                <h1>Create Event</h1>
                <p>{{ Str::limit($campaign->title, 45) }}</p>
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
            <div class="t-av">{{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}</div>
        </div>
    </header>

    {{-- BODY --}}
    <div class="body">
        <div class="page-grid">

            {{-- ═════ LEFT — FORM ═════ --}}
            <div>

                @if(session('success'))
                <div class="alert alert-success">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
                @endif

                @if($errors->any())
                <div class="alert alert-error">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" style="margin-top:2px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
                @endif

                <form action="{{ route('events.store', $campaign->id) }}" method="POST" id="eventForm">
                    @csrf

                    {{-- Card 1: Basic Info --}}
                    <div class="card d1">
                        <div class="card-header">
                            <div class="card-icon ic-indigo">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a2 2 0 012-2z"/></svg>
                            </div>
                            <div>
                                <div class="card-title">Basic Information</div>
                                <div class="card-sub">Event name and description</div>
                            </div>
                        </div>
                        <div class="card-body">

                            <div class="form-section">
                                <label class="form-label" for="title">Event Title <span class="req">*</span></label>
                                <input
                                    id="title" type="text" name="title"
                                    value="{{ old('title') }}"
                                    placeholder="e.g. Annual Charity Walkathon 2025"
                                    required
                                    class="form-input {{ $errors->has('title') ? 'err' : '' }}"
                                >
                                @error('title')<p class="form-hint err-msg">{{ $message }}</p>@enderror
                            </div>

                            <div class="form-section">
                                <label class="form-label" for="description">Description <span class="req">*</span></label>
                                <textarea
                                    id="description" name="description" rows="4" required
                                    placeholder="Describe the event, what attendees can expect, and how it connects to the campaign…"
                                    class="form-textarea {{ $errors->has('description') ? 'err' : '' }}"
                                >{{ old('description') }}</textarea>
                                @error('description')<p class="form-hint err-msg">{{ $message }}</p>@enderror
                            </div>

                        </div>
                    </div>

                    {{-- Card 2: Date & Time --}}
                    <div class="card d2">
                        <div class="card-header">
                            <div class="card-icon ic-yellow">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <div class="card-title">Date &amp; Time</div>
                                <div class="card-sub">Schedule your event</div>
                            </div>
                        </div>
                        <div class="card-body">

                            <div class="form-section">
                                <label class="form-label" for="event_date">Event Date <span class="req">*</span></label>
                                <div class="input-wrap">
                                    <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <input
                                        id="event_date" type="date" name="event_date"
                                        value="{{ old('event_date') }}" required
                                        class="form-input {{ $errors->has('event_date') ? 'err' : '' }}"
                                    >
                                </div>
                                @error('event_date')<p class="form-hint err-msg">{{ $message }}</p>@enderror
                            </div>

                            <div class="form-row">
                                <div class="form-section">
                                    <label class="form-label" for="start_time">Start Time</label>
                                    <div class="input-wrap">
                                        <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                        <input
                                            id="start_time" type="time" name="start_time"
                                            value="{{ old('start_time') }}"
                                            class="form-input {{ $errors->has('start_time') ? 'err' : '' }}"
                                        >
                                    </div>
                                    @error('start_time')<p class="form-hint err-msg">{{ $message }}</p>@enderror
                                </div>
                                <div class="form-section">
                                    <label class="form-label" for="end_time">End Time</label>
                                    <div class="input-wrap">
                                        <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                        <input
                                            id="end_time" type="time" name="end_time"
                                            value="{{ old('end_time') }}"
                                            class="form-input {{ $errors->has('end_time') ? 'err' : '' }}"
                                        >
                                    </div>
                                    @error('end_time')<p class="form-hint err-msg">{{ $message }}</p>@enderror
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- Card 3: Location --}}
                    <div class="card d3">
                        <div class="card-header">
                            <div class="card-icon ic-green">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <div>
                                <div class="card-title">Location</div>
                                <div class="card-sub">Where will the event take place?</div>
                            </div>
                        </div>
                        <div class="card-body">

                            <div class="form-section">
                                <label class="form-label" for="location">Venue / Address</label>
                                <div class="input-wrap">
                                    <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    <input
                                        id="location" type="text" name="location"
                                        value="{{ old('location') }}"
                                        placeholder="e.g. City Park, Mumbai or Online (Zoom)"
                                        class="form-input {{ $errors->has('location') ? 'err' : '' }}"
                                    >
                                </div>
                                @error('location')<p class="form-hint err-msg">{{ $message }}</p>@enderror
                            </div>

                        </div>
                    </div>

                    {{-- Card 4: Goals & Capacity --}}
                    <div class="card d4">
                        <div class="card-header">
                            <div class="card-icon ic-pink">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <div class="card-title">Goals &amp; Capacity</div>
                                <div class="card-sub">Optional — leave blank to skip</div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-row">
                                <div class="form-section">
                                    <label class="form-label" for="goal_amount">Goal Amount</label>
                                    <div class="input-wrap">
                                        <span class="input-prefix">₹</span>
                                        <input
                                            id="goal_amount" type="number" step="0.01" min="0"
                                            name="goal_amount" value="{{ old('goal_amount') }}"
                                            placeholder="0.00"
                                            class="form-input has-prefix {{ $errors->has('goal_amount') ? 'err' : '' }}"
                                        >
                                    </div>
                                    <p class="form-hint">Fundraising target for this event</p>
                                    @error('goal_amount')<p class="form-hint err-msg">{{ $message }}</p>@enderror
                                </div>
                                <div class="form-section">
                                    <label class="form-label" for="max_participants">Max Participants</label>
                                    <div class="input-wrap">
                                        <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
                                        <input
                                            id="max_participants" type="number" min="1"
                                            name="max_participants" value="{{ old('max_participants') }}"
                                            placeholder="Unlimited"
                                            class="form-input {{ $errors->has('max_participants') ? 'err' : '' }}"
                                        >
                                    </div>
                                    <p class="form-hint">Leave blank for unlimited seats</p>
                                    @error('max_participants')<p class="form-hint err-msg">{{ $message }}</p>@enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <button type="submit" class="submit-btn" id="submitBtn">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Create Event
                    </button>

                </form>
            </div>

            {{-- ═════ RIGHT COL ═════ --}}
            <div class="right-col">

                {{-- Campaign Preview --}}
                <div class="card" style="animation-delay:.08s">
                    <div class="card-header">
                        <div class="card-icon ic-indigo">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </div>
                        <div>
                            <div class="card-title">Campaign</div>
                            <div class="card-sub">This event belongs to</div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($campaign->cover_image)
                            <img src="{{ asset('storage/'.$campaign->cover_image) }}" class="preview-thumb" alt="{{ $campaign->title }}">
                        @endif
                        <div class="preview-title">{{ $campaign->title }}</div>
                        <div class="preview-meta">Created {{ $campaign->created_at->diffForHumans() }}</div>
                        @php
                            $raised = $campaign->raised_amount ?? 0;
                            $goal   = $campaign->goal_amount > 0 ? $campaign->goal_amount : 1;
                            $pct    = min(100, round(($raised / $goal) * 100));
                        @endphp
                        <div class="prog-bar"><div class="prog-fill" style="width:{{ $pct }}%"></div></div>
                        <div class="prog-pct">{{ $pct }}% funded · ₹{{ number_format($raised) }} raised</div>
                    </div>
                </div>

                {{-- Quick Links --}}
                <div class="card" style="animation-delay:.14s">
                    <div class="card-header">
                        <div class="card-icon ic-green">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <div><div class="card-title">Quick Links</div></div>
                    </div>
                    <div class="card-body">
                        <a href="{{ route('campaign.show', $campaign->id) }}" class="action-btn">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            Campaign Overview
                        </a>
                        <a href="{{ route('campaign.edit', $campaign->id) }}" class="action-btn">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Edit Campaign
                        </a>
                        <a href="{{ route('dashboard') }}" class="action-btn">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5M12 5l-7 7 7 7"/></svg>
                            Back to Dashboard
                        </a>
                    </div>
                </div>

                {{-- Tips --}}
                <div class="card" style="animation-delay:.20s">
                    <div class="card-header">
                        <div class="card-icon ic-pink">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                        </div>
                        <div><div class="card-title">Tips</div><div class="card-sub">Make your event stand out</div></div>
                    </div>
                    <div class="card-body">
                        <div class="tips-list">
                            <div class="tip-row">
                                <div class="tip-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></div>
                                <p class="tip-text">Use a clear, action-oriented title that tells people exactly what to expect.</p>
                            </div>
                            <div class="tip-row">
                                <div class="tip-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></div>
                                <p class="tip-text">Setting start and end times helps donors plan their participation easily.</p>
                            </div>
                            <div class="tip-row">
                                <div class="tip-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                                <p class="tip-text">Adding a fundraising goal motivates attendees to contribute more.</p>
                            </div>
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

/* ── SUBMIT GUARD ── */
document.getElementById('eventForm').addEventListener('submit', function(){
    var btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:15px;height:15px;animation:spin 1s linear infinite"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg> Creating…';
});

})();
</script>
<style>
@keyframes spin{from{transform:rotate(0deg);}to{transform:rotate(360deg);}}
</style>
</body>
</html>