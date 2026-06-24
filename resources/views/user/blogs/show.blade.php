<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $blog->title }} — DonateBazaar</title>
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

/* ══ BREADCRUMB ══ */
.breadcrumb { display: flex; align-items: center; gap: 6px; font-size: 12px; color: var(--text3); margin-bottom: 20px; }
.breadcrumb a { color: var(--text3); text-decoration: none; transition: color var(--transition); }
.breadcrumb a:hover { color: var(--accent); }
.breadcrumb-sep { opacity: 0.4; }
.breadcrumb-cur { color: var(--text2); font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 260px; }

/* ══ PAGE HEADER ══ */
.page-hdr { margin-bottom: 24px; display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; flex-wrap: wrap; }
.page-hdr-left h2 { font-size: 22px; font-weight: 700; color: var(--text); letter-spacing: -0.02em; }
.page-hdr-left p  { font-size: 12.5px; color: var(--text3); margin-top: 3px; }
.page-hdr-actions { display: flex; align-items: center; gap: 8px; }

.btn-back {
    display: inline-flex; align-items: center; gap: 6px;
    height: 38px; padding: 0 16px;
    background: var(--surface2); color: var(--text2);
    border: 1px solid var(--border2);
    border-radius: var(--radius-sm); font-size: 13px; font-weight: 500;
    text-decoration: none; font-family: var(--font);
    transition: background var(--transition), color var(--transition);
    cursor: pointer;
}
.btn-back:hover { background: var(--border2); color: var(--text); }
.btn-back svg { width: 13px; height: 13px; }

.btn-edit-page {
    display: inline-flex; align-items: center; gap: 7px;
    height: 38px; padding: 0 18px;
    background: var(--accent); color: #fff;
    border-radius: var(--radius-sm); font-size: 13px; font-weight: 600;
    text-decoration: none; font-family: var(--font);
    box-shadow: 0 4px 14px rgba(99,102,241,0.3);
    transition: opacity var(--transition), transform var(--transition);
    border: none; cursor: pointer;
}
.btn-edit-page:hover { opacity: 0.88; transform: translateY(-1px); }
.btn-edit-page svg { width: 13px; height: 13px; }

/* ══ BLOG SHOW CARD ══ */
.blog-show-card {
    background: var(--surface);
    border: 1px solid var(--border2);
    border-radius: var(--radius);
    overflow: hidden;
    box-shadow: var(--shadow);
}

/* Cover */
.show-cover { position: relative; height: 320px; overflow: hidden; flex-shrink: 0; }
.show-cover img { width: 100%; height: 100%; object-fit: cover; display: block; }
.show-cover-placeholder {
    width: 100%; height: 100%;
    background: linear-gradient(135deg, rgba(99,102,241,0.08) 0%, rgba(139,92,246,0.08) 100%);
    display: flex; align-items: center; justify-content: center;
    flex-direction: column; gap: 10px;
}
.show-cover-placeholder svg { width: 48px; height: 48px; color: var(--accent); opacity: 0.25; }
.show-cover-placeholder span { font-size: 12px; color: var(--text3); }

.cover-badge {
    position: absolute; top: 16px; left: 16px;
    font-size: 10px; font-weight: 700; padding: 4px 12px;
    border-radius: 100px; font-family: var(--font-mono);
    letter-spacing: 0.05em; text-transform: uppercase;
    background: #ffffff;
}
.badge-draft    { background: rgba(0,0,0,0.55);      color: #d1d5db; backdrop-filter: blur(6px); }
.badge-pending  { background: rgba(245,158,11,0.18); color: #d97706; border: 1px solid rgba(245,158,11,0.3); }
.badge-approved { background: rgba(16,185,129,0.15); color: #059669; border: 1px solid rgba(16,185,129,0.25); }
.badge-rejected { background: rgba(239,68,68,0.15);  color: #dc2626; border: 1px solid rgba(239,68,68,0.25); }

/* Rejection note */
.rejection-note {
    margin: 0 24px;
    padding: 13px 16px;
    background: rgba(239,68,68,0.06);
    border: 1px solid rgba(239,68,68,0.18);
    border-radius: var(--radius-sm);
    display: flex; align-items: flex-start; gap: 10px;
    margin-top: 20px;
}
.rejection-note svg { width: 15px; height: 15px; color: var(--red); flex-shrink: 0; margin-top: 1px; }
.rejection-note-text { font-size: 12.5px; color: var(--red); line-height: 1.5; }
.rejection-note-label { font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 3px; }

/* Blog content area */
.show-body { padding: 28px 32px 36px; }

.show-title { font-size: 26px; font-weight: 700; color: var(--text); letter-spacing: -0.025em; line-height: 1.3; margin-bottom: 16px; }

.show-meta { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin-bottom: 24px; padding-bottom: 20px; border-bottom: 1px solid var(--border); }
.meta-pill {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 4px 11px; border-radius: 100px;
    font-size: 11px; font-weight: 600; font-family: var(--font-mono);
    letter-spacing: 0.04em; text-transform: uppercase;
}
.pill-draft    { background: rgba(156,163,175,0.12); color: var(--text3); border: 1px solid var(--border2); }
.pill-pending  { background: rgba(245,158,11,0.1);   color: #d97706;     border: 1px solid rgba(245,158,11,0.2); }
.pill-approved { background: rgba(16,185,129,0.1);   color: #059669;     border: 1px solid rgba(16,185,129,0.2); }
.pill-rejected { background: rgba(239,68,68,0.1);    color: #dc2626;     border: 1px solid rgba(239,68,68,0.2); }
.meta-dot { width: 3px; height: 3px; border-radius: 50%; background: var(--border2); }
.meta-date { font-size: 12px; color: var(--text3); font-family: var(--font-mono); }

.show-excerpt {
    background: var(--surface2);
    border-left: 3px solid var(--accent);
    border-radius: 0 var(--radius-sm) var(--radius-sm) 0;
    padding: 14px 18px;
    margin-bottom: 28px;
    font-size: 14px; color: var(--text2); font-weight: 300;
    line-height: 1.7; font-style: italic;
}

.show-content {
    font-size: 15px; color: var(--text2); line-height: 1.8;
    font-weight: 300;
}
.show-content p { margin-bottom: 18px; }
.show-content p:last-child { margin-bottom: 0; }

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
    .show-cover { height: 220px; }
    .show-body { padding: 20px 20px 28px; }
    .show-title { font-size: 20px; }
}
</style>
</head>
<body>

<div class="toast-container" id="toastContainer"></div>

<div class="shell">

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
        @php
            $userBlogs     = auth()->user()->blogs ?? collect();
            $blogTotal     = $userBlogs->count();
            $blogPublished = $userBlogs->where('status','approved')->count();
            $blogDraft     = $userBlogs->where('status','draft')->count();
            $blogPending   = $userBlogs->where('status','pending')->count();
        @endphp

        <a href="{{ url('/user/dashboard/blogs') }}" class="s-link active">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
            My Blogs
            @if($blogTotal > 0)<span class="s-badge">{{ $blogTotal }}</span>@endif
        </a>

        <a href="{{ url('/user/dashboard/blogs/create') }}" class="s-link">
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
                <h1>Blog Post</h1>
                <p>{{ $blog->title }}</p>
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

        {{-- Breadcrumb --}}
        <div class="breadcrumb">
            <a href="{{ url('/user/dashboard') }}">Dashboard</a>
            <span class="breadcrumb-sep">›</span>
            <a href="{{ url('/user/dashboard/blogs') }}">My Blogs</a>
            <span class="breadcrumb-sep">›</span>
            <span class="breadcrumb-cur">{{ $blog->title }}</span>
        </div>

        {{-- Page Header --}}
        <div class="page-hdr">
            <div class="page-hdr-left">
                <h2>Blog Post</h2>
                <p>{{ $blog->created_at->format('d M Y') }} · {{ ucfirst($blog->status) }}</p>
            </div>
            <div class="page-hdr-actions">
                <a href="{{ url('/user/dashboard/blogs') }}" class="btn-back">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Back
                </a>
                @if($blog->status === 'draft' || $blog->status === 'rejected')
                <a href="{{ route('user.blogs.edit', $blog) }}" class="btn-edit-page">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Edit Blog
                </a>
                @endif
            </div>
        </div>

        {{-- Blog Card --}}
        <div class="blog-show-card">

            {{-- Cover --}}
            <div class="show-cover">
                @if($blog->cover_image)
                    <img src="{{ asset('storage/' . $blog->cover_image) }}" alt="{{ $blog->title }}">
                @else
                    <div class="show-cover-placeholder">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                        <span>No cover image</span>
                    </div>
                @endif
                <span class="cover-badge badge-{{ $blog->status }}">{{ ucfirst($blog->status) }}</span>
            </div>

            {{-- Rejection reason --}}
            @if($blog->status === 'rejected' && $blog->rejection_reason)
            <div class="rejection-note">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div class="rejection-note-text">
                    <div class="rejection-note-label">Rejection Reason</div>
                    {{ $blog->rejection_reason }}
                </div>
            </div>
            @endif

            {{-- Body --}}
            <div class="show-body">

                <h1 class="show-title">{{ $blog->title }}</h1>

                <div class="show-meta">
                    @php
                        $pillClass = [
                            'draft'    => 'pill-draft',
                            'pending'  => 'pill-pending',
                            'approved' => 'pill-approved',
                            'rejected' => 'pill-rejected',
                        ][$blog->status] ?? 'pill-draft';
                    @endphp
                    <span class="meta-pill {{ $pillClass }}">{{ ucfirst($blog->status) }}</span>
                    <span class="meta-dot"></span>
                    <span class="meta-date">{{ $blog->created_at->format('d M Y') }}</span>
                    @if($blog->updated_at && $blog->updated_at->ne($blog->created_at))
                    <span class="meta-dot"></span>
                    <span class="meta-date" style="color:var(--text3);">Updated {{ $blog->updated_at->format('d M Y') }}</span>
                    @endif
                </div>

                @if($blog->excerpt)
                <div class="show-excerpt">{{ $blog->excerpt }}</div>
                @endif

                <div class="show-content">
                    {!! nl2br(e($blog->content)) !!}
                </div>

            </div>

        </div>

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