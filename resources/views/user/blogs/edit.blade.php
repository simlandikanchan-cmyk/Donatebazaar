<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edit Blog — DonateBazaar</title>
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
    --shadow-lg:    0 8px 40px rgba(0,0,0,0.12);
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
    --shadow-lg:    0 8px 40px rgba(0,0,0,0.5);
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
html, body { height: 100%; }
body {
    font-family: var(--font);
    background: var(--bg);
    color: var(--text);
    line-height: 1.5;
    -webkit-font-smoothing: antialiased;
    transition: background var(--transition), color var(--transition);
    overflow-x: hidden;
}

/* ══ SHELL ══ */
.shell { display: flex; min-height: 100vh; }

/* ══ SIDEBAR ══ */
.sidebar {
    width: 256px; flex-shrink: 0;
    background: var(--sidebar-bg);
    display: flex; flex-direction: column;
    position: fixed; top: 0; left: 0; bottom: 0;
    z-index: 200; overflow-y: auto; overflow-x: hidden;
    border-right: 1px solid rgba(255,255,255,0.04);
    transition: transform 0.3s ease;
}
.sidebar::-webkit-scrollbar { width: 3px; }
.sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.08); border-radius: 3px; }

.s-logo { display: flex; align-items: center; gap: 10px; padding: 22px 18px 18px; border-bottom: 1px solid rgba(255,255,255,0.05); flex-shrink: 0; }
.s-logo-mark { width: 36px; height: 36px; border-radius: 10px; background: linear-gradient(135deg, var(--accent), var(--accent2)); display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 4px 14px rgba(99,102,241,0.35); }
.s-logo-mark svg { width: 18px; height: 18px; color: #fff; }
.s-logo-name { font-size: 17px; font-weight: 700; color: #fff; letter-spacing: -0.01em; }
.s-logo-tag  { font-size: 9px; color: rgba(255,255,255,0.3); text-transform: uppercase; letter-spacing: 0.12em; margin-top: 1px; }

.s-user { margin: 12px 10px 4px; padding: 10px 12px; background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.06); border-radius: var(--radius-sm); display: flex; align-items: center; gap: 9px; flex-shrink: 0; }
.s-avatar { width: 32px; height: 32px; border-radius: 8px; background: linear-gradient(135deg, var(--accent), var(--accent2)); color: #fff; font-size: 13px; font-weight: 700; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.s-user-name { font-size: 12.5px; font-weight: 600; color: rgba(255,255,255,0.85); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.s-user-role { font-size: 10px; color: rgba(255,255,255,0.35); margin-top: 1px; }

.s-label { font-size: 9.5px; font-weight: 700; color: rgba(255,255,255,0.25); text-transform: uppercase; letter-spacing: 0.14em; padding: 16px 18px 5px; font-family: var(--font-mono); }
.s-nav { padding: 0 8px; }
.s-link { display: flex; align-items: center; gap: 10px; padding: 9px 11px; border-radius: 9px; color: var(--sidebar-text); font-size: 13px; font-weight: 500; text-decoration: none; transition: background var(--transition), color var(--transition); margin-bottom: 1px; border: none; background: transparent; width: 100%; text-align: left; cursor: pointer; position: relative; }
.s-link:hover  { background: rgba(255,255,255,0.06); color: rgba(255,255,255,0.9); }
.s-link.active { background: var(--sidebar-act); color: #a5b4fc; }
.s-link.active::before { content: ''; position: absolute; left: 0; top: 20%; bottom: 20%; width: 3px; border-radius: 0 2px 2px 0; background: var(--accent); }
.s-icon { width: 16px; height: 16px; flex-shrink: 0; opacity: 0.8; }
.s-badge { margin-left: auto; font-size: 10px; font-weight: 700; padding: 2px 7px; border-radius: 100px; background: rgba(99,102,241,0.25); color: #a5b4fc; font-family: var(--font-mono); }

.s-sub { padding: 0 8px 0 24px; }
.s-sub-link { display: flex; align-items: center; gap: 8px; padding: 7px 10px; border-radius: 7px; color: rgba(255,255,255,0.4); font-size: 12px; font-weight: 500; text-decoration: none; transition: background var(--transition), color var(--transition); margin-bottom: 1px; cursor: pointer; }
.s-sub-link:hover { background: rgba(255,255,255,0.05); color: rgba(255,255,255,0.8); }
.s-sub-link.active { color: #a5b4fc; background: rgba(120,119,255,0.12); }
.s-sub-dot { width: 5px; height: 5px; border-radius: 50%; background: rgba(255,255,255,0.2); flex-shrink: 0; }
.s-sub-link:hover .s-sub-dot, .s-sub-link.active .s-sub-dot { background: var(--accent); }

.s-divider { height: 1px; background: rgba(255,255,255,0.05); margin: 8px 16px; }
.s-bottom { margin-top: auto; padding: 12px 8px 16px; border-top: 1px solid rgba(255,255,255,0.05); flex-shrink: 0; }

/* ══ MAIN ══ */
.main { margin-left: 256px; flex: 1; min-width: 0; display: flex; flex-direction: column; min-height: 100vh; }

/* ── Topbar ── */
.topbar { display: flex; align-items: center; justify-content: space-between; padding: 0 28px; height: 64px; background: var(--surface); border-bottom: 1px solid var(--border); position: sticky; top: 0; z-index: 100; gap: 16px; flex-shrink: 0; }
.topbar-left h1 { font-size: 18px; font-weight: 700; color: var(--text); letter-spacing: -0.02em; }
.topbar-left p  { font-size: 11px; color: var(--text3); margin-top: 1px; }
.topbar-right   { display: flex; align-items: center; gap: 8px; }

.theme-toggle { position: relative; }
.theme-toggle input { position: absolute; opacity: 0; width: 0; height: 0; }
.theme-toggle label { display: flex; align-items: center; justify-content: space-between; width: 52px; height: 28px; border-radius: 100px; background: var(--surface2); border: 1px solid var(--border2); cursor: pointer; padding: 3px 4px; position: relative; transition: background var(--transition); }
.theme-toggle label::after { content: ''; width: 20px; height: 20px; border-radius: 50%; background: var(--accent); position: absolute; left: 4px; transition: transform 0.3s cubic-bezier(.4,0,.2,1); box-shadow: 0 2px 6px rgba(99,102,241,0.4); }
.theme-toggle input:checked + label::after { transform: translateX(24px); }
.theme-icons { display: flex; justify-content: space-between; width: 100%; position: relative; z-index: 1; }
.theme-icons svg { width: 12px; height: 12px; color: var(--text3); }

.t-avatar { width: 36px; height: 36px; border-radius: 10px; background: linear-gradient(135deg,var(--accent),var(--accent2)); color: #fff; font-size: 13px; font-weight: 700; display: flex; align-items: center; justify-content: center; cursor: pointer; flex-shrink: 0; }

.hamburger { display: none; width: 36px; height: 36px; border-radius: 10px; border: 1px solid var(--border2); background: var(--surface2); cursor: pointer; color: var(--text2); align-items: center; justify-content: center; flex-shrink: 0; }
.hamburger svg { width: 16px; height: 16px; }

/* ── Body ── */
.body { padding: 28px 32px 60px; flex: 1; }

/* ══ PAGE HEADER ══ */
.page-hdr { margin-bottom: 24px; display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; flex-wrap: wrap; }
.page-hdr-left h2 { font-size: 22px; font-weight: 700; color: var(--text); letter-spacing: -0.02em; }
.page-hdr-left p  { font-size: 12.5px; color: var(--text3); margin-top: 3px; }

.btn-back {
    display: inline-flex; align-items: center; gap: 7px;
    height: 38px; padding: 0 16px;
    background: var(--surface2); color: var(--text2);
    border-radius: var(--radius-sm); font-size: 13px; font-weight: 600;
    text-decoration: none; font-family: var(--font);
    border: 1px solid var(--border2);
    transition: background var(--transition), color var(--transition);
}
.btn-back:hover { background: var(--border2); color: var(--text); }
.btn-back svg { width: 13px; height: 13px; }

/* ══ FORM CARDS ══ */
.form-card {
    background: var(--surface);
    border: 1px solid var(--border2);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    padding: 24px 26px;
    margin-bottom: 16px;
}
.form-card-title {
    font-size: 13px; font-weight: 700; color: var(--text);
    letter-spacing: -0.01em; margin-bottom: 18px;
    padding-bottom: 12px; border-bottom: 1px solid var(--border);
    display: flex; align-items: center; gap: 8px;
}
.form-card-title svg { width: 15px; height: 15px; color: var(--accent); opacity: 0.8; }

/* ══ FORM CONTROLS ══ */
.field { margin-bottom: 18px; }
.field:last-child { margin-bottom: 0; }
.field-label {
    display: block; font-size: 12px; font-weight: 600;
    color: var(--text2); margin-bottom: 7px; letter-spacing: 0.01em;
}
.field-label span { color: var(--red); margin-left: 2px; }

.field-input,
.field-select,
.field-textarea {
    width: 100%;
    padding: 10px 14px;
    background: var(--surface2);
    border: 1px solid var(--border2);
    border-radius: var(--radius-sm);
    font-size: 13px; font-family: var(--font);
    color: var(--text);
    outline: none;
    transition: border-color var(--transition), box-shadow var(--transition);
    appearance: none;
    -webkit-appearance: none;
}
.field-input:focus,
.field-select:focus,
.field-textarea:focus {
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(99,102,241,0.12);
}
.field-input.has-error,
.field-textarea.has-error { border-color: var(--red); }
.field-error { font-size: 11px; color: var(--red); margin-top: 5px; }
.field-hint  { font-size: 11px; color: var(--text3); margin-top: 5px; }

.field-textarea { resize: vertical; line-height: 1.6; }

/* Select wrapper for custom arrow */
.select-wrap { position: relative; }
.select-wrap::after {
    content: '';
    position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
    width: 0; height: 0;
    border-left: 4px solid transparent;
    border-right: 4px solid transparent;
    border-top: 5px solid var(--text3);
    pointer-events: none;
}

.field-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }

/* File input */
.file-wrap {
    border: 1.5px dashed var(--border2);
    border-radius: var(--radius-sm);
    padding: 14px 16px;
    background: var(--surface2);
    transition: border-color var(--transition);
    cursor: pointer;
}
.file-wrap:hover { border-color: var(--accent); }
.file-wrap input[type="file"] {
    width: 100%; font-size: 12.5px; font-family: var(--font);
    color: var(--text2); background: transparent; border: none; outline: none; cursor: pointer;
}

/* Cover preview */
.cover-preview {
    position: relative; border-radius: var(--radius-sm);
    overflow: hidden; margin-bottom: 12px;
    border: 1px solid var(--border2);
    background: var(--surface2);
}
.cover-preview img { width: 100%; height: 160px; object-fit: cover; display: block; }
.cover-preview-label {
    position: absolute; top: 10px; left: 10px;
    font-size: 10px; font-weight: 700; padding: 3px 10px;
    border-radius: 100px; font-family: var(--font-mono);
    letter-spacing: 0.05em; text-transform: uppercase;
    background: rgba(0,0,0,0.55); color: #d1d5db;
    backdrop-filter: blur(6px);
}

/* Char counter */
.textarea-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 7px; }
.char-count { font-size: 11px; font-family: var(--font-mono); color: var(--text3); transition: color var(--transition); }
.char-count.warn { color: var(--red); }

/* ══ ACTION BAR ══ */
.action-bar {
    position: sticky; bottom: 16px;
    background: var(--surface);
    border: 1px solid var(--border2);
    border-radius: var(--radius);
    padding: 14px 20px;
    display: flex; justify-content: space-between; align-items: center;
    box-shadow: var(--shadow-lg);
    gap: 12px; flex-wrap: wrap;
    backdrop-filter: blur(12px);
    margin-top: 8px;
}
.action-bar-info { font-size: 12.5px; color: var(--text3); }

.action-btns { display: flex; align-items: center; gap: 8px; }

.btn-cancel {
    display: inline-flex; align-items: center; gap: 6px;
    height: 38px; padding: 0 18px;
    background: var(--surface2); color: var(--text2);
    border-radius: var(--radius-sm); font-size: 13px; font-weight: 600;
    text-decoration: none; font-family: var(--font);
    border: 1px solid var(--border2);
    transition: background var(--transition), color var(--transition);
    cursor: pointer;
}
.btn-cancel:hover { background: var(--border2); color: var(--text); }

.btn-submit {
    display: inline-flex; align-items: center; gap: 7px;
    height: 38px; padding: 0 22px;
    background: var(--accent); color: #fff;
    border-radius: var(--radius-sm); font-size: 13px; font-weight: 600;
    font-family: var(--font); border: none; cursor: pointer;
    box-shadow: 0 4px 14px rgba(99,102,241,0.3);
    transition: opacity var(--transition), transform var(--transition);
}
.btn-submit:hover { opacity: 0.88; transform: translateY(-1px); }
.btn-submit svg { width: 14px; height: 14px; }

/* ══ TOAST ══ */
.toast-container { position: fixed; top: 20px; right: 20px; z-index: 9999; display: flex; flex-direction: column; gap: 10px; pointer-events: none; }
.toast { display: flex; align-items: center; gap: 10px; padding: 13px 16px; border-radius: 13px; font-size: 13px; font-weight: 500; color: #fff; min-width: 260px; box-shadow: var(--shadow-lg); pointer-events: all; animation: toastIn 0.35s cubic-bezier(.4,0,.2,1) both; }
.toast-success { background: linear-gradient(135deg, #059669, #10b981); }
.toast-error   { background: linear-gradient(135deg, #dc2626, #ef4444); }
.toast svg { width: 16px; height: 16px; flex-shrink: 0; }
.toast-close { margin-left: auto; width: 18px; height: 18px; border-radius: 4px; background: rgba(255,255,255,0.2); border: none; cursor: pointer; color: #fff; font-size: 12px; display: flex; align-items: center; justify-content: center; }

/* ══ ANIMATIONS ══ */
@keyframes toastIn { from { opacity: 0; transform: translateX(20px) scale(0.96); } to { opacity: 1; transform: translateX(0) scale(1); } }

/* ══ RESPONSIVE ══ */
@media (max-width: 860px) {
    .sidebar { transform: translateX(-100%); }
    .sidebar.open { transform: translateX(0); }
    .main { margin-left: 0; }
    .hamburger { display: flex; }
    .body { padding: 16px 16px 60px; }
}
@media (max-width: 640px) {
    .field-grid { grid-template-columns: 1fr; }
    .action-bar { flex-direction: column; align-items: stretch; }
    .action-btns { justify-content: flex-end; }
}
</style>
</head>
<body>

<div class="toast-container" id="toastContainer"></div>

<div class="shell">

@php
    $userId        = auth()->id();
    $blogTotal     = \App\Models\Blog::where('author_id', $userId)->count();
    $blogPublished = \App\Models\Blog::where('author_id', $userId)->where('status', 'approved')->count();
    $blogDraft     = \App\Models\Blog::where('author_id', $userId)->where('status', 'draft')->count();
    $blogPending   = \App\Models\Blog::where('author_id', $userId)->where('status', 'pending')->count();
    $blogRejected  = \App\Models\Blog::where('author_id', $userId)->where('status', 'rejected')->count();
@endphp

{{-- ══ SIDEBAR ══ --}}
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
        <div style="overflow:hidden;">
            <div class="s-user-name">{{ auth()->user()->name ?? 'User' }}</div>
            <div class="s-user-role">Fundraiser</div>
        </div>
    </div>

    <div class="s-label">Navigation</div>
    <nav class="s-nav">
        <a href="{{ url('/user/dashboard') }}" class="s-link">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
            Dashboard
        </a>
        <a href="{{ route('campaign.create') }}" class="s-link">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            New Campaign
        </a>
    </nav>

    <div class="s-divider"></div>

    <div class="s-label">Blogs</div>
    <nav class="s-nav">
        <a href="{{ url('/user/dashboard/blogs') }}" class="s-link">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
            My Blogs
            @if($blogTotal > 0)<span class="s-badge">{{ $blogTotal }}</span>@endif
        </a>

        <a href="{{ url('/user/dashboard/blogs/create') }}" class="s-link active">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Write a Blog
        </a>
    </nav>

    @if($blogTotal > 0)
    <div class="s-sub">
        @if($blogPublished > 0)
        <a href="{{ url('/user/dashboard/blogs') }}" class="s-sub-link">
            <span class="s-sub-dot"></span>Published
            <span style="margin-left:auto;font-size:10px;color:var(--green);font-family:var(--font-mono);">{{ $blogPublished }}</span>
        </a>
        @endif
        @if($blogDraft > 0)
        <a href="{{ url('/user/dashboard/blogs') }}" class="s-sub-link">
            <span class="s-sub-dot"></span>Drafts
            <span style="margin-left:auto;font-size:10px;color:var(--yellow);font-family:var(--font-mono);">{{ $blogDraft }}</span>
        </a>
        @endif
        @if($blogPending > 0)
        <a href="{{ url('/user/dashboard/blogs') }}" class="s-sub-link">
            <span class="s-sub-dot"></span>In Review
            <span style="margin-left:auto;font-size:10px;color:var(--text3);font-family:var(--font-mono);">{{ $blogPending }}</span>
        </a>
        @endif
        @if($blogRejected > 0)
        <a href="{{ url('/user/dashboard/blogs') }}" class="s-sub-link">
            <span class="s-sub-dot"></span>Rejected
            <span style="margin-left:auto;font-size:10px;color:var(--red);font-family:var(--font-mono);">{{ $blogRejected }}</span>
        </a>
        @endif
    </div>
    @endif

    <div class="s-bottom">
        <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('__lf').submit();" class="s-link" style="color:rgba(248,113,113,0.75);">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
            Sign Out
        </a>
        <form id="__lf" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
    </div>

</aside>

{{-- ══ MAIN ══ --}}
<div class="main">

    <header class="topbar">
        <div style="display:flex;align-items:center;gap:10px;">
            <button class="hamburger" id="hamburger" aria-label="Menu">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <div class="topbar-left">
                <h1>Edit Blog</h1>
                <p>Update your blog content</p>
            </div>
        </div>
        <div class="topbar-right">
            <div class="theme-toggle" title="Toggle dark mode">
                <input type="checkbox" id="themeToggle">
                <label for="themeToggle">
                    <div class="theme-icons">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="5"/><path stroke-linecap="round" d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/></svg>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
                    </div>
                </label>
            </div>
            <div class="t-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}</div>
        </div>
    </header>

    <div class="body">

        {{-- Page Header --}}
        <div class="page-hdr">
            <div class="page-hdr-left">
                <h2>Edit Blog</h2>
                <p>Update "{{ Str::limit($blog->title, 50) }}"</p>
            </div>
            <a href="{{ route('user.blogs.index') }}" class="btn-back">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                Back to Blogs
            </a>
        </div>

        <form action="{{ route('user.blogs.update', $blog) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- MAIN INFO --}}
            <div class="form-card">
                <div class="form-card-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Basic Information
                </div>

                {{-- TITLE --}}
                <div class="field">
                    <label class="field-label">Title <span>*</span></label>
                    <input
                        type="text"
                        name="title"
                        value="{{ old('title', $blog->title) }}"
                        placeholder="Enter a compelling title…"
                        class="field-input @error('title') has-error @enderror">
                    @error('title')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- CATEGORY + TAGS --}}
                <div class="field-grid">
                    <div class="field">
                        <label class="field-label">Category</label>
                        <div class="select-wrap">
                            <select name="category_id" class="field-select">
                                <option value="">Select category…</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}"
                                        @selected(old('category_id', $blog->category_id) == $cat->id)>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="field">
                        <label class="field-label">Tags</label>
                        <select name="tag_ids[]" multiple class="field-select" style="height: auto; min-height: 42px;">
                            @foreach($tags as $tag)
                                <option value="{{ $tag->id }}"
                                    @selected(in_array($tag->id, old('tag_ids', $blog->tags->pluck('id')->toArray() ?? [])))>
                                    {{ $tag->name }}
                                </option>
                            @endforeach
                        </select>
                        <p class="field-hint">Hold Ctrl / Cmd to select multiple</p>
                    </div>
                </div>

                {{-- EXCERPT --}}
                <div class="field">
                    <label class="field-label">Excerpt</label>
                    <textarea
                        name="excerpt"
                        rows="3"
                        placeholder="A short summary shown in blog listings…"
                        class="field-textarea">{{ old('excerpt', $blog->excerpt) }}</textarea>
                </div>
            </div>

            {{-- COVER IMAGE --}}
            <div class="form-card">
                <div class="form-card-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path stroke-linecap="round" stroke-linejoin="round" d="M21 15l-5-5L5 21"/></svg>
                    Cover Image
                </div>

                @if($blog->cover_image)
                <div class="cover-preview">
                    <img src="{{ asset('storage/' . $blog->cover_image) }}" alt="Current cover">
                    <span class="cover-preview-label">Current</span>
                </div>
                @endif

                <div class="file-wrap">
                    <input type="file" name="cover_image" accept="image/*">
                </div>
                <p class="field-hint" style="margin-top:8px;">Upload a new image to replace the existing one. Recommended: 1200×630px.</p>
            </div>

            {{-- CONTENT --}}
            <div class="form-card">
                <div class="textarea-header">
                    <label class="field-label" style="margin-bottom:0;">
                        <span style="display:flex;align-items:center;gap:6px;">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="2" style="opacity:.8;"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"/></svg>
                            Content <span style="color:var(--red);margin-left:2px;">*</span>
                        </span>
                    </label>
                    <span id="charCount" class="char-count">0 characters</span>
                </div>

                <textarea
                    name="content"
                    id="blogContent"
                    rows="18"
                    placeholder="Write your blog content here…"
                    class="field-textarea @error('content') has-error @enderror">{{ old('content', $blog->content) }}</textarea>

                @error('content')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- SEO --}}
            <div class="form-card">
                <div class="form-card-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35"/></svg>
                    SEO Settings
                </div>

                <div class="field">
                    <label class="field-label">Meta Title</label>
                    <input
                        type="text"
                        name="meta_title"
                        value="{{ old('meta_title', $blog->meta_title) }}"
                        placeholder="Override the page title for search engines…"
                        class="field-input">
                    <p class="field-hint">Leave blank to use the blog title. Recommended: 50–60 characters.</p>
                </div>
            </div>

            {{-- ACTION BAR --}}
            <div class="action-bar">
                <p class="action-bar-info">All changes will be saved and submitted for review.</p>
                <!-- <div class="action-btns">
                    <a href="{{ route('user.blogs.index') }}" class="btn-cancel">Cancel</a>
                    <button type="submit" class="btn-submit">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        Update Blog
                    </button>
                </div> -->




                <div class="action-btns">
    
    <a href="{{ route('user.blogs.index') }}" class="btn-cancel">
        Cancel
    </a>

    <button type="submit" 
            name="action" 
            value="draft" 
            class="btn-draft">
        Save Draft
    </button>

    @if($blog->status == 'draft')
        <button type="submit" 
                name="action" 
                value="publish" 
                class="btn-submit">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
            </svg>
            Publish Blog
        </button>
    @else
        <button type="submit" 
                class="btn-submit">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
            </svg>
            Update Blog
        </button>
    @endif

</div>





            </div>

        </form>

    </div>{{-- /.body --}}
</div>{{-- /.main --}}
</div>{{-- /.shell --}}

<script>
/* ── Dark mode ── */
var html   = document.documentElement;
var toggle = document.getElementById('themeToggle');
var saved  = localStorage.getItem('theme') || 'light';
if (saved === 'dark') { html.setAttribute('data-theme','dark'); toggle.checked = true; }
toggle.addEventListener('change', function(){
    var t = this.checked ? 'dark' : 'light';
    html.setAttribute('data-theme', t);
    localStorage.setItem('theme', t);
});

/* ── Hamburger ── */
var sidebar = document.getElementById('sidebar');
document.getElementById('hamburger').addEventListener('click', function(){
    sidebar.classList.toggle('open');
});
document.addEventListener('click', function(e){
    if (window.innerWidth <= 860 && !sidebar.contains(e.target) && !document.getElementById('hamburger').contains(e.target)){
        sidebar.classList.remove('open');
    }
});

/* ── Char counter ── */
var textarea = document.getElementById('blogContent');
var counter  = document.getElementById('charCount');

function updateCount() {
    var length = textarea.value.length;
    counter.textContent = length.toLocaleString() + ' characters';
    counter.classList.toggle('warn', length < 50);
}

textarea.addEventListener('input', updateCount);
updateCount();

/* ── Toast flash ── */
@if(session('success'))
    window.addEventListener('DOMContentLoaded', function(){
        var t = document.createElement('div');
        t.className = 'toast toast-success';
        t.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><span>{{ addslashes(session('success')) }}</span><button class="toast-close" onclick="this.parentElement.remove()">✕</button>';
        document.getElementById('toastContainer').appendChild(t);
        setTimeout(function(){ t.remove(); }, 4500);
    });
@endif
@if(session('error'))
    window.addEventListener('DOMContentLoaded', function(){
        var t = document.createElement('div');
        t.className = 'toast toast-error';
        t.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><span>{{ addslashes(session('error')) }}</span><button class="toast-close" onclick="this.parentElement.remove()">✕</button>';
        document.getElementById('toastContainer').appendChild(t);
        setTimeout(function(){ t.remove(); }, 4500);
    });
@endif
</script>

</body>
</html>