@extends('layouts.app')

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<style>
/* ═══════════════════════════════════════
   CSS CUSTOM PROPERTIES
═══════════════════════════════════════ */
:root {
    --bg:           #f5f6fa;
    --surface:      #ffffff;
    --surface2:     #f8f9fc;
    --surface3:     #f0f1f7;
    --border:       #eaecf4;
    --border2:      #dde0ee;
    --text:         #1a1d2e;
    --text2:        #52576e;
    --text3:        #9499b0;
    --sidebar-bg:   #ffffff;
    --sidebar-text: #6b7080;
    --sidebar-act-bg: #f0f1fd;
    --sidebar-act:  #4f46e5;
    --accent:       #4f46e5;
    --accent2:      #7c3aed;
    --accent-lt:    #eef0fd;
    --accent-glow:  rgba(79,70,229,0.15);
    --green:        #059669;
    --green-lt:     #d1fae5;
    --yellow:       #d97706;
    --yellow-lt:    #fef3c7;
    --red:          #dc2626;
    --red-lt:       #fee2e2;
    --blue:         #2563eb;
    --blue-lt:      #dbeafe;
    --pink:         #db2777;
    --pink-lt:      #fce7f3;
    --font:         'Plus Jakarta Sans', sans-serif;
    --r:            12px;
    --r-sm:         8px;
    --r-xs:         6px;
    --shadow-sm:    0 1px 3px rgba(16,24,40,0.06), 0 1px 2px rgba(16,24,40,0.04);
    --shadow:       0 2px 8px rgba(16,24,40,0.06), 0 1px 3px rgba(16,24,40,0.04);
    --shadow-md:    0 4px 20px rgba(16,24,40,0.08), 0 2px 6px rgba(16,24,40,0.04);
    --shadow-lg:    0 10px 40px rgba(16,24,40,0.12);
    --ease:         0.18s ease;
}

/* ═══════════════════════════════════════
   DARK MODE
═══════════════════════════════════════ */
[data-theme="dark"] {
    --bg:             #0f1117;
    --surface:        #181b25;
    --surface2:       #1e2130;
    --surface3:       #242738;
    --border:         rgba(255,255,255,0.07);
    --border2:        rgba(255,255,255,0.12);
    --text:           #eef0ff;
    --text2:          #9ba3c4;
    --text3:          #555e7a;
    --sidebar-bg:     #13151f;
    --sidebar-text:   rgba(255,255,255,0.55);
    --sidebar-act-bg: rgba(79,70,229,0.18);
    --sidebar-act:    #a5b4fc;
    --accent-lt:      rgba(79,70,229,0.18);
    --accent-glow:    rgba(79,70,229,0.25);
    --green-lt:       rgba(5,150,105,0.18);
    --yellow-lt:      rgba(217,119,6,0.18);
    --red-lt:         rgba(220,38,38,0.18);
    --blue-lt:        rgba(37,99,235,0.18);
    --pink-lt:        rgba(219,39,119,0.18);
    --shadow-sm:      0 1px 3px rgba(0,0,0,0.3), 0 1px 2px rgba(0,0,0,0.2);
    --shadow:         0 2px 8px rgba(0,0,0,0.35), 0 1px 3px rgba(0,0,0,0.2);
    --shadow-md:      0 4px 20px rgba(0,0,0,0.45), 0 2px 6px rgba(0,0,0,0.2);
    --shadow-lg:      0 10px 40px rgba(0,0,0,0.6);
}
[data-theme="dark"] .si-pink   { background: rgba(219,39,119,0.2); }
[data-theme="dark"] .si-blue   { background: rgba(37,99,235,0.2); }
[data-theme="dark"] .si-orange { background: rgba(234,88,12,0.2); }
[data-theme="dark"] .si-teal   { background: rgba(13,148,136,0.2); }

/* ─── Dark mode toggle ─── */
.theme-toggle { display: flex; align-items: center; gap: 7px; }
.theme-toggle-track {
    position: relative;
    width: 42px; height: 24px;
    border-radius: 100px;
    background: var(--surface3);
    border: 1.5px solid var(--border2);
    cursor: pointer;
    transition: background 0.25s, border-color 0.25s;
    flex-shrink: 0;
}
.theme-toggle-track.on {
    background: var(--accent);
    border-color: var(--accent);
}
.theme-toggle-thumb {
    position: absolute;
    top: 2px; left: 2px;
    width: 16px; height: 16px;
    border-radius: 50%;
    background: #fff;
    box-shadow: 0 1px 4px rgba(0,0,0,0.2);
    transition: transform 0.25s cubic-bezier(.4,0,.2,1);
}
.theme-toggle-track.on .theme-toggle-thumb { transform: translateX(18px); }
.theme-toggle-icon { width: 14px; height: 14px; color: var(--text3); flex-shrink: 0; }

/* ═══════════════════════════════════════
   RESET & BASE
═══════════════════════════════════════ */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
body {
    font-family: var(--font);
    background: var(--bg);
    color: var(--text);
    line-height: 1.5;
    -webkit-font-smoothing: antialiased;
    transition: background 0.2s, color 0.2s;
}

/* ═══════════════════════════════════════
   LAYOUT SHELL
═══════════════════════════════════════ */
.shell { display: flex; min-height: 100vh; }

/* ═══════════════════════════════════════
   SIDEBAR
═══════════════════════════════════════ */
.sidebar {
    width: 220px;
    flex-shrink: 0;
    background: var(--sidebar-bg);
    display: flex;
    flex-direction: column;
    position: fixed;
    top: 0; left: 0; bottom: 0;
    z-index: 200;
    overflow-y: auto;
    border-right: 1px solid var(--border);
    transition: transform 0.3s cubic-bezier(.4,0,.2,1);
}
.sidebar::-webkit-scrollbar { width: 0; }

/* Logo */
.s-logo {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 20px 18px 16px;
    border-bottom: 1px solid var(--border);
}
.s-logo-mark {
    width: 34px; height: 34px;
    border-radius: 9px;
    background: linear-gradient(135deg, var(--accent), var(--accent2));
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.s-logo-mark svg { width: 17px; height: 17px; color: #fff; }
.s-logo-name { font-size: 15px; font-weight: 700; color: var(--text); letter-spacing: -0.02em; }
.s-logo-tag  { font-size: 10px; color: var(--text3); margin-top: 1px; }

/* Nav */
.s-label {
    font-size: 10px;
    font-weight: 600;
    color: var(--text3);
    text-transform: uppercase;
    letter-spacing: 0.1em;
    padding: 16px 18px 5px;
}
.s-nav { padding: 3px 10px; }
.s-link {
    display: flex;
    align-items: center;
    gap: 9px;
    padding: 9px 10px;
    border-radius: var(--r-sm);
    color: var(--sidebar-text);
    font-size: 13px;
    font-weight: 500;
    text-decoration: none;
    transition: background var(--ease), color var(--ease);
    margin-bottom: 2px;
    border: none;
    background: transparent;
    width: 100%;
    text-align: left;
    cursor: pointer;
    position: relative;
}
.s-link:hover { background: var(--surface2); color: var(--text); }
.s-link.active {
    background: var(--sidebar-act-bg);
    color: var(--sidebar-act);
    font-weight: 600;
}
.s-icon { width: 16px; height: 16px; flex-shrink: 0; }
.s-badge {
    margin-left: auto;
    font-size: 11px;
    font-weight: 600;
    padding: 2px 7px;
    border-radius: 100px;
    background: var(--accent-lt);
    color: var(--accent);
}
.s-badge.ok   { background: var(--green-lt);   color: var(--green); }
.s-badge.warn { background: var(--yellow-lt);  color: var(--yellow); }
.s-divider { height: 1px; background: var(--border); margin: 8px 14px; }
.s-bottom { margin-top: auto; padding: 10px 10px 20px; border-top: 1px solid var(--border); }

/* Upgrade card in sidebar */
.s-upgrade {
    margin: 12px 10px;
    padding: 14px;
    background: linear-gradient(135deg, #4f46e5, #7c3aed);
    border-radius: var(--r);
    color: #fff;
}
.s-upgrade-title { font-size: 13px; font-weight: 700; margin-bottom: 4px; }
.s-upgrade-sub   { font-size: 11px; opacity: 0.75; margin-bottom: 12px; line-height: 1.45; }
.s-upgrade-btn {
    display: block;
    background: rgba(255,255,255,0.95);
    color: var(--accent);
    font-size: 12px;
    font-weight: 700;
    text-align: center;
    padding: 8px;
    border-radius: var(--r-sm);
    text-decoration: none;
    transition: opacity var(--ease);
}
.s-upgrade-btn:hover { opacity: 0.9; }

/* ═══════════════════════════════════════
   MAIN CONTENT
═══════════════════════════════════════ */
.main { margin-left: 220px; flex: 1; min-width: 0; display: flex; flex-direction: column; }

/* ── Topbar ── */
.topbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 26px;
    height: 64px;
    background: var(--surface);
    border-bottom: 1px solid var(--border);
    position: sticky;
    top: 0;
    z-index: 100;
    gap: 14px;
}
.topbar-left h1 { font-size: 20px; font-weight: 700; color: var(--text); letter-spacing: -0.03em; }
.topbar-left p  { font-size: 12px; color: var(--text3); margin-top: 1px; }
.topbar-right   { display: flex; align-items: center; gap: 10px; }

.search-wrap { position: relative; }
.search-wrap svg { position: absolute; left: 11px; top: 50%; transform: translateY(-50%); width: 14px; height: 14px; color: var(--text3); pointer-events: none; }
.search-input {
    width: 230px; height: 36px;
    background: var(--surface2);
    border: 1px solid var(--border);
    border-radius: 100px;
    padding: 0 14px 0 34px;
    font-size: 13px;
    color: var(--text);
    font-family: var(--font);
    outline: none;
    transition: border-color var(--ease), box-shadow var(--ease);
}
.search-input::placeholder { color: var(--text3); }
.search-input:focus { border-color: var(--accent); box-shadow: 0 0 0 3px var(--accent-glow); }

.icon-btn {
    width: 36px; height: 36px;
    border-radius: 10px;
    border: 1px solid var(--border);
    background: var(--surface2);
    display: flex; align-items: center; justify-content: center;
    cursor: pointer;
    color: var(--text2);
    flex-shrink: 0;
    transition: all var(--ease);
    position: relative;
}
.icon-btn:hover { background: var(--accent-lt); color: var(--accent); border-color: var(--accent); }
.icon-btn svg { width: 16px; height: 16px; }
.icon-btn .dot { width: 7px; height: 7px; border-radius: 50%; background: var(--red); position: absolute; top: 5px; right: 5px; border: 2px solid var(--surface); }

.t-user-info { text-align: right; }
.t-user-name { font-size: 13px; font-weight: 600; color: var(--text); }
.t-user-role { font-size: 11px; color: var(--text3); }

/* Avatar */
.avatar-wrap { position: relative; }
.t-avatar {
    width: 36px; height: 36px;
    border-radius: 50%;
    background: linear-gradient(135deg, #f87171, #fb923c);
    color: #fff;
    font-size: 14px;
    font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer;
    flex-shrink: 0;
    overflow: hidden;
    border: 2px solid var(--border2);
    transition: all var(--ease);
}
.t-avatar:hover { border-color: var(--accent); }
.t-avatar img { width: 100%; height: 100%; object-fit: cover; }
.avatar-dropdown {
    position: absolute; top: calc(100% + 8px); right: 0;
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--r);
    box-shadow: var(--shadow-lg);
    min-width: 200px;
    z-index: 9999;
    overflow: hidden;
    display: none;
    animation: ddIn 0.18s ease;
}
.avatar-dropdown.open { display: block; }
@keyframes ddIn { from { opacity: 0; transform: translateY(-6px); } to { opacity: 1; transform: translateY(0); } }
.dd-header { padding: 12px 14px; border-bottom: 1px solid var(--border); }
.dd-name  { font-size: 13px; font-weight: 600; color: var(--text); }
.dd-email { font-size: 11px; color: var(--text3); margin-top: 2px; }
.dd-item {
    display: flex; align-items: center; gap: 9px;
    padding: 9px 14px;
    font-size: 12.5px; font-weight: 500;
    color: var(--text2);
    cursor: pointer;
    transition: background var(--ease);
    text-decoration: none;
}
.dd-item:hover { background: var(--surface2); color: var(--text); }
.dd-item svg { width: 13px; height: 13px; color: var(--text3); }
.dd-item.danger { color: var(--red); }
.dd-item.danger svg { color: var(--red); }
.dd-sep { height: 1px; background: var(--border); margin: 3px 0; }

.hamburger {
    display: none; width: 36px; height: 36px;
    border-radius: var(--r-sm);
    border: 1px solid var(--border);
    background: var(--surface2);
    cursor: pointer;
    color: var(--text2);
    align-items: center; justify-content: center;
    flex-shrink: 0;
}
.hamburger svg { width: 16px; height: 16px; }

/* ═══════════════════════════════════════
   PAGE BODY
═══════════════════════════════════════ */
.body { padding: 24px 26px 48px; }

/* ═══════════════════════════════════════
   TOAST
═══════════════════════════════════════ */
.toast-container {
    position: fixed; top: 20px; right: 20px; z-index: 9999;
    display: flex; flex-direction: column; gap: 8px;
    pointer-events: none;
}
.toast {
    display: flex; align-items: center; gap: 10px;
    padding: 12px 15px;
    border-radius: 10px;
    font-size: 13px; font-weight: 500;
    color: #fff;
    min-width: 260px; max-width: 360px;
    box-shadow: var(--shadow-lg);
    pointer-events: all;
    animation: toastIn 0.3s ease both;
    position: relative; overflow: hidden;
}
.toast-success { background: linear-gradient(135deg, #059669, #10b981); }
.toast-error   { background: linear-gradient(135deg, #dc2626, #ef4444); }
.toast-warn    { background: linear-gradient(135deg, #d97706, #f59e0b); }
.toast svg { width: 15px; height: 15px; flex-shrink: 0; }
.toast-close {
    margin-left: auto; width: 18px; height: 18px;
    border-radius: 4px;
    background: rgba(255,255,255,0.2);
    border: none; cursor: pointer;
    color: #fff; font-size: 11px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
@keyframes toastIn { from { opacity: 0; transform: translateX(20px); } to { opacity: 1; transform: translateX(0); } }

/* ═══════════════════════════════════════
   TWO-COLUMN MAIN LAYOUT
═══════════════════════════════════════ */
.main-layout {
    display: grid;
    grid-template-columns: 1fr 290px;
    gap: 20px;
    align-items: start;
}
.main-col { min-width: 0; }
.side-col { min-width: 0; }

/* ═══════════════════════════════════════
   STATS GRID
═══════════════════════════════════════ */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 14px;
    margin-bottom: 18px;
}
.stat-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--r);
    padding: 18px 18px 16px;
    box-shadow: var(--shadow-sm);
    transition: transform var(--ease), box-shadow var(--ease);
    animation: fadeUp 0.4s ease both;
    cursor: default;
}
.stat-card:hover { transform: translateY(-2px); box-shadow: var(--shadow); }
.stat-icon-row { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 12px; }
.stat-icon-wrap {
    width: 38px; height: 38px;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
}
.stat-icon-wrap svg { width: 18px; height: 18px; }
.si-pink   { background: #fce7f3; color: #db2777; }
.si-blue   { background: #dbeafe; color: #2563eb; }
.si-orange { background: #ffedd5; color: #ea580c; }
.si-teal   { background: #ccfbf1; color: #0d9488; }
.stat-label { font-size: 11px; font-weight: 500; color: var(--text3); margin-bottom: 4px; }
.stat-val   { font-size: 1.6rem; font-weight: 700; letter-spacing: -0.03em; color: var(--text); }
.stat-delta { font-size: 11.5px; font-weight: 500; margin-top: 4px; display: flex; align-items: center; gap: 3px; }
.delta-up   { color: var(--green); }
.delta-dn   { color: var(--red); }
.delta-neutral { color: var(--text3); }

/* ═══════════════════════════════════════
   CHARTS ROW
═══════════════════════════════════════ */
.charts-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
    margin-bottom: 18px;
}
.chart-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--r);
    padding: 18px 20px 16px;
    box-shadow: var(--shadow-sm);
    animation: fadeUp 0.4s 0.1s ease both;
}
.chart-card-hdr {
    display: flex; align-items: flex-start; justify-content: space-between;
    margin-bottom: 16px; gap: 10px;
}
.chart-title { font-size: 14px; font-weight: 700; color: var(--text); letter-spacing: -0.01em; }
.chart-sub   { font-size: 11px; color: var(--text3); margin-top: 2px; }
.chart-badge {
    display: inline-flex; align-items: center; gap: 5px;
    font-size: 11.5px; font-weight: 500;
    color: var(--text2);
    background: var(--surface2);
    border: 1px solid var(--border);
    padding: 4px 10px;
    border-radius: 6px;
    cursor: pointer;
    white-space: nowrap;
}
.chart-badge svg { width: 12px; height: 12px; }
.chart-wrap  { position: relative; height: 180px; }

/* ═══════════════════════════════════════
   CAMPAIGNS SECTION
═══════════════════════════════════════ */
.sec-hdr {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 14px; flex-wrap: wrap; gap: 12px;
}
.sec-title { font-size: 15px; font-weight: 700; color: var(--text); letter-spacing: -0.02em; }
.sec-right { display: flex; align-items: center; gap: 8px; }

.sort-select {
    height: 32px; padding: 0 10px;
    border: 1px solid var(--border);
    border-radius: var(--r-sm);
    font-size: 12px; color: var(--text2);
    font-family: var(--font);
    background: var(--surface2);
    outline: none; cursor: pointer;
}

.ftabs {
    display: flex; gap: 2px;
    background: var(--surface2);
    border: 1px solid var(--border);
    padding: 3px;
    border-radius: 9px;
}
.ftab {
    padding: 5px 12px;
    border-radius: 6px;
    font-size: 12px; font-weight: 500;
    cursor: pointer; border: none;
    background: transparent; color: var(--text3);
    transition: all var(--ease);
    display: inline-flex; align-items: center; gap: 4px;
    font-family: var(--font);
    white-space: nowrap;
}
.ftab:hover { color: var(--accent); }
.ftab.on { background: var(--surface); color: var(--accent); font-weight: 600; box-shadow: 0 1px 4px rgba(0,0,0,0.07); }
.ftab .cnt {
    display: inline-flex; align-items: center; justify-content: center;
    min-width: 16px; height: 16px;
    border-radius: 100px;
    font-size: 10px; padding: 0 3px;
    background: var(--accent-lt); color: var(--accent);
    font-weight: 700;
}

/* Campaigns grid */
.c-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 14px;
}
.c-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--r);
    overflow: hidden;
    display: flex; flex-direction: column;
    box-shadow: var(--shadow-sm);
    transition: transform var(--ease), box-shadow var(--ease), border-color var(--ease);
    animation: fadeUp 0.4s ease both;
}
.c-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-md); border-color: var(--border2); }

.c-thumb { position: relative; flex-shrink: 0; }
.c-thumb img { width: 100%; height: 140px; object-fit: cover; display: block; }
.c-thumb-placeholder {
    width: 100%; height: 140px;
    display: flex; align-items: center; justify-content: center;
    background: var(--surface2);
}
.c-thumb-placeholder svg { width: 28px; height: 28px; color: var(--text3); opacity: 0.3; }
.c-overlay { position: absolute; inset: 0; background: linear-gradient(to top, rgba(0,0,0,0.35) 0%, transparent 55%); }
.c-badge-wrap { position: absolute; top: 8px; left: 8px; }

.badge { display: inline-flex; align-items: center; font-size: 10px; font-weight: 600; padding: 3px 9px; border-radius: 100px; letter-spacing: 0.02em; }
.b-active   { background: rgba(5,150,105,0.9);  color: #fff; }
.b-pending  { background: rgba(217,119,6,0.9);  color: #fff; }
.b-rejected { background: rgba(220,38,38,0.9);  color: #fff; }
.b-paused   { background: rgba(79,70,229,0.9);  color: #fff; }

.c-cat-badge {
    position: absolute; bottom: 8px; left: 8px;
    background: rgba(0,0,0,0.55);
    backdrop-filter: blur(8px);
    color: #fff; font-size: 10px; font-weight: 500;
    padding: 3px 9px; border-radius: 100px;
}

.c-user {
    display: flex; align-items: center; gap: 7px;
    padding: 8px 12px;
    border-bottom: 1px solid var(--border);
    font-size: 11px; color: var(--text3);
}
.c-av {
    width: 22px; height: 22px; border-radius: 50%;
    background: linear-gradient(135deg, var(--accent), var(--accent2));
    color: #fff; font-size: 9px; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.c-uname  { font-size: 11.5px; font-weight: 600; color: var(--text2); }
.c-uemail { font-size: 10px; color: var(--text3); }

.c-body { padding: 12px 13px; flex: 1; display: flex; flex-direction: column; }
.c-org   { font-size: 11px; color: var(--text3); margin-bottom: 3px; }
.c-title { font-size: 13px; font-weight: 700; color: var(--text); margin-bottom: 10px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.45; }

/* Reason banner */
.reason { padding: 8px 10px; border-radius: 7px; margin-bottom: 10px; border: 1px solid transparent; }
.reason-y { background: var(--yellow-lt); border-color: rgba(217,119,6,0.2); }
.reason-r { background: var(--red-lt);    border-color: rgba(220,38,38,0.2); }
.reason-lbl { font-size: 10px; font-weight: 700; margin-bottom: 2px; }
.reason-y .reason-lbl { color: #92400e; }
.reason-r .reason-lbl { color: #991b1b; }
.reason-txt { font-size: 11px; line-height: 1.4; }
.reason-y .reason-txt { color: #92400e; }
.reason-r .reason-txt { color: #991b1b; }

/* Progress */
.prog-wrap { margin-bottom: 11px; }
.prog-nums { display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 5px; }
.prog-raised { font-size: 14px; font-weight: 700; color: var(--text); letter-spacing: -0.02em; }
.prog-goal   { font-size: 10.5px; color: var(--text3); }
.prog-bar    { width: 100%; background: var(--surface3); border-radius: 100px; height: 5px; overflow: hidden; margin-bottom: 3px; }
.prog-fill   { height: 100%; border-radius: 100px; background: linear-gradient(90deg, var(--accent), var(--accent2)); transition: width 0.8s ease; }
.prog-fill.ok     { background: linear-gradient(90deg, #059669, #10b981); }
.prog-fill.danger { background: linear-gradient(90deg, #f87171, var(--red)); }
.prog-meta { display: flex; justify-content: space-between; align-items: center; }
.prog-pct  { font-size: 10.5px; color: var(--text3); font-weight: 500; }
.prog-days { font-size: 10.5px; color: var(--text3); }

/* Action buttons */
.c-actions { display: flex; gap: 6px; margin-top: auto; }
.btn {
    display: inline-flex; align-items: center; justify-content: center; gap: 4px;
    padding: 7px 12px;
    border-radius: var(--r-sm);
    font-size: 12px; font-weight: 600;
    cursor: pointer;
    border: 1px solid transparent;
    transition: all var(--ease);
    text-decoration: none;
    white-space: nowrap;
    font-family: var(--font);
}
.btn svg { width: 12px; height: 12px; }
.btn-g { background: var(--green-lt);  color: var(--green);  border-color: rgba(5,150,105,0.2); }
.btn-r { background: var(--red-lt);    color: var(--red);    border-color: rgba(220,38,38,0.2); }
.btn-y { background: var(--yellow-lt); color: var(--yellow); border-color: rgba(217,119,6,0.2); }
.btn-i { background: var(--accent-lt); color: var(--accent); border-color: rgba(79,70,229,0.2); }
.btn-g:hover { background: var(--green);  color: #fff; border-color: var(--green); }
.btn-r:hover { background: var(--red);    color: #fff; border-color: var(--red); }
.btn-y:hover { background: var(--yellow); color: #fff; border-color: var(--yellow); }
.btn-i:hover { background: var(--accent); color: #fff; border-color: var(--accent); }

/* ═══════════════════════════════════════
   RIGHT SIDE PANELS
═══════════════════════════════════════ */
.side-panel {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--r);
    padding: 18px 18px 16px;
    box-shadow: var(--shadow-sm);
    margin-bottom: 14px;
    animation: fadeUp 0.4s ease both;
}
.panel-hdr {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 16px;
}
.panel-title { font-size: 14px; font-weight: 700; color: var(--text); letter-spacing: -0.01em; }
.panel-more {
    width: 26px; height: 26px; border-radius: 6px;
    border: 1px solid var(--border);
    background: var(--surface2);
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; color: var(--text3);
    font-size: 13px; font-weight: 700; letter-spacing: 0.05em;
    transition: all var(--ease);
}
.panel-more:hover { background: var(--accent-lt); color: var(--accent); border-color: var(--accent); }

/* Donut chart panel */
.donut-wrap {
    display: flex; justify-content: center;
    margin-bottom: 16px;
}
.donut-wrap canvas { max-width: 160px; max-height: 160px; }
.cat-list { display: flex; flex-direction: column; gap: 8px; }
.cat-row  { display: flex; align-items: center; justify-content: space-between; gap: 10px; }
.cat-left { display: flex; align-items: center; gap: 8px; flex: 1; min-width: 0; }
.cat-dot  { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
.cat-info {}
.cat-name    { font-size: 12px; font-weight: 600; color: var(--text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.cat-amount  { font-size: 10.5px; color: var(--text3); }
.cat-pct     { font-size: 12px; font-weight: 700; color: var(--text); }

/* Top donors */
.donor-row {
    display: flex; align-items: center; gap: 10px;
    padding: 8px 0;
    border-bottom: 1px solid var(--border);
}
.donor-row:last-child { border-bottom: none; }
.donor-av {
    width: 32px; height: 32px; border-radius: 50%;
    background: linear-gradient(135deg, #f87171, #fb923c);
    color: #fff; font-size: 12px; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.donor-name  { font-size: 12.5px; font-weight: 600; color: var(--text); }
.donor-count { font-size: 11px; color: var(--text3); }
.donor-amount { font-size: 13px; font-weight: 700; color: var(--text); margin-left: auto; flex-shrink: 0; }

/* Status breakdown */
.sp-rows  { display: flex; flex-direction: column; gap: 6px; }
.sp-row {
    display: flex; align-items: center; justify-content: space-between;
    padding: 8px 10px;
    border-radius: var(--r-sm);
    background: var(--surface2);
    border: 1px solid var(--border);
    transition: background var(--ease);
}
.sp-row:hover { background: var(--surface3); }
.sp-row-left  { display: flex; align-items: center; gap: 8px; }
.sp-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
.sp-label { font-size: 12.5px; font-weight: 500; color: var(--text2); }
.sp-val   { font-size: 13px; font-weight: 700; color: var(--text); }
.sp-progress { margin-top: 14px; padding-top: 14px; border-top: 1px solid var(--border); }
.sp-prog-hdr { display: flex; justify-content: space-between; font-size: 11px; margin-bottom: 6px; }
.sp-prog-hdr span:first-child { color: var(--text3); }
.sp-prog-hdr span:last-child  { font-weight: 700; color: var(--accent); }
.sp-prog-bar { height: 6px; background: var(--surface3); border-radius: 100px; overflow: hidden; }
.sp-prog-fill { height: 100%; border-radius: 100px; background: linear-gradient(90deg, var(--accent), var(--accent2)); }

/* Recent activity */
.act-row { display: flex; gap: 10px; padding: 8px 0; border-bottom: 1px solid var(--border); }
.act-row:last-child { border-bottom: none; padding-bottom: 0; }
.act-icon {
    width: 30px; height: 30px; border-radius: 50%;
    background: var(--accent-lt);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; color: var(--accent);
}
.act-icon svg { width: 14px; height: 14px; }
.act-time { font-size: 10px; color: var(--text3); margin-bottom: 2px; }
.act-text { font-size: 11.5px; color: var(--text2); line-height: 1.45; }

/* ═══════════════════════════════════════
   MODALS
═══════════════════════════════════════ */
.overlay {
    display: none; position: fixed; inset: 0; z-index: 9998;
    background: rgba(5,5,20,0.45);
    backdrop-filter: blur(6px);
    align-items: center; justify-content: center; padding: 20px;
}
.overlay.open { display: flex; }
.modal {
    background: var(--surface);
    border: 1px solid var(--border2);
    border-radius: 16px;
    box-shadow: var(--shadow-lg);
    width: 100%; max-width: 400px; padding: 22px;
    position: relative;
    animation: modalIn 0.2s ease;
}
@keyframes modalIn { from { opacity: 0; transform: scale(0.96) translateY(8px); } to { opacity: 1; transform: scale(1) translateY(0); } }
.modal-x {
    position: absolute; top: 14px; right: 14px;
    width: 26px; height: 26px; border-radius: 6px;
    border: 1px solid var(--border); background: var(--surface2);
    cursor: pointer; color: var(--text2);
    display: flex; align-items: center; justify-content: center;
    transition: background var(--ease);
}
.modal-x:hover { background: var(--surface3); }
.modal-x svg { width: 11px; height: 11px; }
.modal-head { display: flex; align-items: center; gap: 11px; margin-bottom: 16px; }
.modal-icon { width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.modal-icon svg { width: 18px; height: 18px; }
.modal-ttl { font-size: 14px; font-weight: 700; color: var(--text); }
.modal-sub { font-size: 11px; color: var(--text3); margin-top: 2px; }
.chips { display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 10px; }
.chip {
    font-size: 11.5px; padding: 5px 11px;
    border-radius: 100px;
    border: 1.5px solid var(--border2);
    color: var(--text2); background: transparent;
    cursor: pointer; transition: all 0.15s;
    font-family: var(--font);
}
.chip-y:hover, .chip-y.on { border-color: var(--yellow); background: var(--yellow-lt); color: #92400e; font-weight: 600; }
.chip-r:hover, .chip-r.on { border-color: var(--red);    background: var(--red-lt);    color: #991b1b; font-weight: 600; }
.modal-ta {
    width: 100%; border: 1.5px solid var(--border2);
    border-radius: 9px; padding: 10px 12px;
    font-size: 12.5px; color: var(--text); resize: none;
    font-family: var(--font); background: var(--surface2); outline: none;
    transition: border-color var(--ease), box-shadow var(--ease);
}
.modal-ta:focus { border-color: var(--accent); box-shadow: 0 0 0 3px var(--accent-glow); }
.modal-err { font-size: 11px; color: var(--red); margin-top: 5px; display: none; }
.modal-acts { display: flex; gap: 8px; margin-top: 15px; }
.modal-btn { flex: 1; padding: 10px; border-radius: 9px; font-size: 13px; font-weight: 600; cursor: pointer; border: none; transition: opacity var(--ease); font-family: var(--font); }
.modal-btn:hover { opacity: 0.88; }
.modal-cancel { background: var(--surface2); color: var(--text2); border: 1px solid var(--border2); }
.modal-y-btn  { background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; }
.modal-r-btn  { background: linear-gradient(135deg, #ef4444, #dc2626); color: #fff; }

/* ═══════════════════════════════════════
   EMPTY STATE
═══════════════════════════════════════ */
#noResults { display: none; text-align: center; padding: 40px 20px; color: var(--text3); font-size: 13px; }
#noResults svg { width: 40px; height: 40px; margin: 0 auto 10px; display: block; opacity: 0.25; }

/* ═══════════════════════════════════════
   PAGINATION
═══════════════════════════════════════ */
.pagination-wrap { margin-top: 18px; }

/* ═══════════════════════════════════════
   ANIMATIONS
═══════════════════════════════════════ */
@keyframes fadeUp { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }

/* ═══════════════════════════════════════
   RESPONSIVE
═══════════════════════════════════════ */
@media (max-width: 1200px) {
    .main-layout { grid-template-columns: 1fr; }
    .side-col { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; }
    .side-panel { margin-bottom: 0; }
}
@media (max-width: 1000px) {
    .stats-grid { grid-template-columns: repeat(2, 1fr); }
    .c-grid     { grid-template-columns: 1fr; }
}
@media (max-width: 860px) {
    .sidebar { transform: translateX(-100%); }
    .sidebar.open { transform: translateX(0); }
    .main { margin-left: 0; }
    .hamburger { display: flex; }
    .search-wrap { display: none; }
    .side-col { grid-template-columns: 1fr; }
}
@media (max-width: 600px) {
    .topbar { padding: 0 16px; }
    .body   { padding: 14px 16px 40px; }
    .stats-grid { grid-template-columns: 1fr 1fr; }
    .charts-row { grid-template-columns: 1fr; }
    .ftabs { flex-wrap: wrap; }
}
</style>

{{-- TOAST --}}
<div class="toast-container" id="toastContainer"></div>

<div class="shell" id="shell">

{{-- ═══════════════════ SIDEBAR ═══════════════════ --}}
<aside class="sidebar" id="sidebar">

    <div class="s-logo">
        <div class="s-logo-mark">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
        </div>
        <div>
            <div class="s-logo-name">DonateBazaar</div>
            <div class="s-logo-tag">Admin Portal</div>
        </div>
    </div>

    <nav class="s-nav" style="margin-top:8px;">
        <a href="{{ route('admin.dashboard') }}" class="s-link active">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/>
                <rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>
            </svg>
            Dashboard
        </a>
        <a href="{{ url('/admin/messages') }}" class="s-link">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            Inbox
        </a>
        <a href="{{ url('/admin/applications') }}" class="s-link">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="4" width="18" height="18" rx="2"/><path stroke-linecap="round" d="M16 2v4M8 2v4M3 10h18"/>
            </svg>
            Applications
        </a>
    </nav>

    <div class="s-label">Campaigns</div>
    <nav class="s-nav">
        <a href="#cGrid" class="s-link" onclick="setFilter('all')">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            All Campaigns
            <span class="s-badge">{{ $totalCampaigns }}</span>
        </a>
        <a href="#cGrid" class="s-link" onclick="setFilter('pending')">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
            </svg>
            Pending
            <span class="s-badge warn">{{ $pendingCampaigns }}</span>
        </a>
        <a href="#cGrid" class="s-link" onclick="setFilter('active')">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Active
            <span class="s-badge ok">{{ $cntActive ?? 0 }}</span>
        </a>
    </nav>

    <div class="s-label">Manage</div>
    <nav class="s-nav">
        <a href="{{ url('/admin/categories') }}" class="s-link">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a2 2 0 012-2z"/>
            </svg>
            Categories
        </a>
        <a href="{{ url('/admin/partnerships') }}" class="s-link">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/>
            </svg>
            Partnerships
        </a>
        <a href="{{ url('/admin/blogs') }}" class="s-link">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
            </svg>
            Blogs
        </a>
        <a href="{{ route('profile.show') }}" class="s-link">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            My Profile
        </a>
        <a href="{{ url('/admin/financials') }}" class="s-link">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
            </svg>
            Financials
        </a>
    </nav>

    <div class="s-divider"></div>

    {{-- Upgrade card --}}
    <div class="s-upgrade">
        <div class="s-upgrade-title">DonateBazaar<br>Just Got an Upgrade</div>
        <div class="s-upgrade-sub">Fresh, faster, and better tools for productivity</div>
        <a href="#" class="s-upgrade-btn">Try the New Version</a>
    </div>

    <div class="s-bottom">
        <a href="{{ route('logout') }}"
           onclick="event.preventDefault(); document.getElementById('lf').submit();"
           class="s-link" style="color:#dc2626;">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
            </svg>
            Logout
        </a>
        <form id="lf" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
    </div>

</aside>

{{-- ═══════════════════ MAIN ═══════════════════ --}}
<div class="main">

    <header class="topbar">
        <div style="display:flex;align-items:center;gap:10px;">
            <button class="hamburger" id="hamburger" aria-label="Menu">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <div class="topbar-left">
                @php
                    $hour = now()->hour;
                    $greeting = $hour < 12 ? 'Good Morning' : ($hour < 17 ? 'Good Afternoon' : 'Good Evening');
                @endphp
                <h1>Dashboard</h1>
                <p>Hello {{ auth()->user()->name ?? 'Admin' }}, {{ $greeting }}!</p>
            </div>
        </div>

        <div class="topbar-right">
            <div class="search-wrap">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                </svg>
                <input class="search-input" id="searchInput" type="text" placeholder="Search anything" autocomplete="off">
            </div>

            <button class="icon-btn" title="Settings">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><circle cx="12" cy="12" r="3"/>
                </svg>
            </button>

            <button class="icon-btn" title="Notifications">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                <span class="dot"></span>
            </button>

            {{-- Dark Mode Toggle --}}
            <div class="theme-toggle" title="Toggle dark mode">
                <svg class="theme-toggle-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="5"/><path stroke-linecap="round" d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/>
                </svg>
                <div class="theme-toggle-track" id="themeTrack" onclick="toggleTheme()">
                    <div class="theme-toggle-thumb"></div>
                </div>
                <svg class="theme-toggle-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>
                </svg>
            </div>

            <div class="t-user-info">
                <div class="t-user-name">{{ auth()->user()->name ?? 'Admin' }}</div>
                <div class="t-user-role">Admin</div>
            </div>

            <div class="avatar-wrap" id="avatarWrap">
                <div class="t-avatar" id="avatarBtn" onclick="toggleAvatarDD()">
                    @if(auth()->user()->avatar)
                        <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="">
                    @else
                        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                    @endif
                </div>
                <div class="avatar-dropdown" id="avatarDD">
                    <div class="dd-header">
                        <div class="dd-name">{{ auth()->user()->name ?? 'Admin' }}</div>
                        <div class="dd-email">{{ auth()->user()->email ?? '' }}</div>
                    </div>
                    <a href="{{ route('profile.show') }}" class="dd-item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        View Profile
                    </a>
                    <a href="{{ route('profile.edit') }}" class="dd-item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Edit Profile
                    </a>
                    <div class="dd-sep"></div>
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('lf').submit();" class="dd-item danger">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Sign Out
                    </a>
                </div>
            </div>
        </div>
    </header>

    <div class="body">
        <div class="main-layout">

            {{-- ═══ LEFT / MAIN COL ═══ --}}
            <div class="main-col">

                {{-- STATS --}}
                <div class="stats-grid">
                    <div class="stat-card" style="animation-delay:.04s;">
                        <div class="stat-icon-row">
                            <div>
                                <div class="stat-label">Total Campaigns</div>
                                <div class="stat-val">{{ $totalCampaigns }}</div>
                            </div>
                            <div class="stat-icon-wrap si-pink">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            </div>
                        </div>
                        <div class="stat-delta delta-up">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="12" height="12"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/></svg>
                            All time total
                        </div>
                    </div>
                    <div class="stat-card" style="animation-delay:.08s;">
                        <div class="stat-icon-row">
                            <div>
                                <div class="stat-label">Active Campaigns</div>
                                <div class="stat-val">{{ $cntActive ?? 0 }}</div>
                            </div>
                            <div class="stat-icon-wrap si-blue">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                        </div>
                        <div class="stat-delta delta-up">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="12" height="12"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/></svg>
                            Currently running
                        </div>
                    </div>
                    <div class="stat-card" style="animation-delay:.12s;">
                        <div class="stat-icon-row">
                            <div>
                                <div class="stat-label">Pending Review</div>
                                <div class="stat-val">{{ $pendingCampaigns }}</div>
                            </div>
                            <div class="stat-icon-wrap si-orange">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            </div>
                        </div>
                        <div class="stat-delta {{ $pendingCampaigns > 0 ? 'delta-dn' : 'delta-neutral' }}">
                            @if($pendingCampaigns > 0)
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="12" height="12"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            Awaiting review
                            @else
                            All caught up
                            @endif
                        </div>
                    </div>
                    <div class="stat-card" style="animation-delay:.16s;">
                        <div class="stat-icon-row">
                            <div>
                                <div class="stat-label">Approved</div>
                                <div class="stat-val">{{ $approvedCampaigns }}</div>
                            </div>
                            <div class="stat-icon-wrap si-teal">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </div>
                        </div>
                        @php $approvalRate = $totalCampaigns > 0 ? round(($approvedCampaigns / $totalCampaigns) * 100) : 0; @endphp
                        <div class="stat-delta delta-up">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="12" height="12"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/></svg>
                            {{ $approvalRate }}% approval rate
                        </div>
                    </div>
                </div>

                {{-- CHARTS ROW --}}
                <div class="charts-row">
                    <div class="chart-card">
                        <div class="chart-card-hdr">
                            <div>
                                <div class="chart-title">Campaign Activity</div>
                                <div class="chart-sub">Monthly overview</div>
                            </div>
                            <div class="chart-badge">
                                Last 12 Months
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </div>
                        <div class="chart-wrap">
                            <canvas id="lineChart"></canvas>
                        </div>
                    </div>
                    <div class="chart-card">
                        <div class="chart-card-hdr">
                            <div>
                                <div class="chart-title">Status Breakdown</div>
                                <div class="chart-sub">Campaign states</div>
                            </div>
                            <div class="chart-badge">
                                Last 8 Months
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </div>
                        <div class="chart-wrap">
                            <canvas id="barChart"></canvas>
                        </div>
                    </div>
                </div>

                {{-- CAMPAIGNS --}}
                <div class="sec-hdr" id="cGrid">
                    <div class="sec-title">Active Campaigns</div>
                    <div class="sec-right">
                        <select class="sort-select" id="sortSelect">
                            <option value="">Sort by…</option>
                            <option value="amount-desc">Amount ↓</option>
                            <option value="amount-asc">Amount ↑</option>
                            <option value="date-desc">Newest first</option>
                            <option value="date-asc">Oldest first</option>
                        </select>
                    </div>
                </div>

                <div style="margin-bottom:14px;">
                    @php $cntAll = ($cntPending ?? 0) + ($cntActive ?? 0) + ($cntPaused ?? 0) + ($cntRejected ?? 0); @endphp
                    <div class="ftabs" id="ftabs">
                        <button class="ftab on" data-filter="all">All <span class="cnt">{{ $cntAll }}</span></button>
                        <button class="ftab" data-filter="active">Active <span class="cnt">{{ $cntActive ?? 0 }}</span></button>
                        <button class="ftab" data-filter="pending">Pending <span class="cnt">{{ $pendingCampaigns }}</span></button>
                        <button class="ftab" data-filter="paused">Paused <span class="cnt">{{ $cntPaused ?? 0 }}</span></button>
                        <button class="ftab" data-filter="rejected">Rejected <span class="cnt">{{ $cntRejected ?? 0 }}</span></button>
                    </div>
                </div>

                <div id="noResults">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                    No campaigns match your search or filter.
                </div>

                <div class="c-grid" id="campaignGrid">

                    {{-- PENDING --}}
                    @forelse($campaigns as $c)
                    @php
                        $raised = $c->raised_amount ?? 0;
                        $goal   = $c->goal_amount > 0 ? $c->goal_amount : 1;
                        $pct    = min(100, round(($raised / $goal) * 100));
                        $uName  = $c->user?->name ?? 'Unknown';
                        $uEmail = $c->user?->email ?? '';
                        $uInit  = strtoupper(substr($uName, 0, 1));
                    @endphp
                    <div class="c-card" data-filter="pending" data-title="{{ strtolower($c->title) }}" data-amount="{{ $c->goal_amount }}" data-date="{{ $c->created_at }}">
                        <div class="c-thumb">
                            @if($c->cover_image)
                                <img src="{{ asset('storage/' . $c->cover_image) }}" alt="{{ $c->title }}" loading="lazy">
                                <div class="c-overlay"></div>
                            @else
                                <div class="c-thumb-placeholder"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg></div>
                            @endif
                            <div class="c-badge-wrap"><span class="badge b-pending">Pending</span></div>
                            @if($c->category?->name)
                            <div class="c-cat-badge">{{ $c->category->name }}</div>
                            @endif
                        </div>
                        <div class="c-user">
                            <div class="c-av">{{ $uInit }}</div>
                            <div>
                                <div class="c-uname">{{ $uName }}</div>
                                @if($uEmail)<div class="c-uemail">{{ $uEmail }}</div>@endif
                            </div>
                        </div>
                        <div class="c-body">
                            <div class="c-title">{{ $c->title }}</div>
                            <div class="prog-wrap">
                                <div class="prog-nums">
                                    <span class="prog-raised">₹{{ number_format($raised) }}</span>
                                    <span class="prog-goal">of ₹{{ number_format($c->goal_amount) }}</span>
                                </div>
                                <div class="prog-bar"><div class="prog-fill" style="width:{{ $pct }}%"></div></div>
                                <div class="prog-meta">
                                    <span class="prog-pct">{{ $pct }}% funded</span>
                                    @if($c->end_date)
                                    <span class="prog-days">{{ \Carbon\Carbon::parse($c->end_date)->diffForHumans() }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="c-actions">
                                <form action="{{ route('admin.campaign.approve', $c->id) }}" method="POST" style="flex:1;" onsubmit="return handleSub(this,'Approving…')">
                                    @csrf
                                    <button class="btn btn-g" style="width:100%;">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                        Approve
                                    </button>
                                </form>
                                <button type="button" onclick="openReject({{ $c->id }})" class="btn btn-r" style="flex:1;">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                    Reject
                                </button>
                                <a href="{{ route('admin.campaign.show', $c->id) }}" class="btn btn-i">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                    @empty
                    @endforelse

                    {{-- ACTIVE + PAUSED --}}
                    @isset($activeCampaigns)
                    @foreach($activeCampaigns as $c)
                    @php
                        $raised   = $c->raised_amount ?? 0;
                        $goal     = $c->goal_amount > 0 ? $c->goal_amount : 1;
                        $pct      = min(100, round(($raised / $goal) * 100));
                        $isPaused = ($c->campaign_state === 'paused');
                        $fv       = $isPaused ? 'paused' : 'active';
                        $uName    = $c->user?->name ?? 'Unknown';
                        $uEmail   = $c->user?->email ?? '';
                        $uInit    = strtoupper(substr($uName, 0, 1));
                    @endphp
                    <div class="c-card" data-filter="{{ $fv }}" data-title="{{ strtolower($c->title) }}" data-amount="{{ $c->goal_amount }}" data-date="{{ $c->created_at }}">
                        <div class="c-thumb">
                            @if($c->cover_image)
                                <img src="{{ asset('storage/' . $c->cover_image) }}" alt="{{ $c->title }}" loading="lazy">
                                <div class="c-overlay"></div>
                            @else
                                <div class="c-thumb-placeholder"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg></div>
                            @endif
                            <div class="c-badge-wrap">
                                <span class="badge {{ $isPaused ? 'b-paused' : 'b-active' }}">{{ $isPaused ? 'Paused' : 'Active' }}</span>
                            </div>
                            @if($c->category?->name)
                            <div class="c-cat-badge">{{ $c->category->name }}</div>
                            @endif
                        </div>
                        <div class="c-user">
                            <div class="c-av">{{ $uInit }}</div>
                            <div>
                                <div class="c-uname">{{ $uName }}</div>
                                @if($uEmail)<div class="c-uemail">{{ $uEmail }}</div>@endif
                            </div>
                        </div>
                        <div class="c-body">
                            @if($c->user?->name) <div class="c-org">{{ $c->user->name }}</div> @endif
                            <div class="c-title">{{ $c->title }}</div>
                            @if($isPaused && $c->pause_reason)
                            <div class="reason reason-y">
                                <div class="reason-lbl">⏸ Pause reason</div>
                                <div class="reason-txt">{{ $c->pause_reason }}</div>
                            </div>
                            @endif
                            <div class="prog-wrap">
                                <div class="prog-nums">
                                    <span class="prog-raised">₹{{ number_format($raised) }}</span>
                                    <span class="prog-goal">/ ₹{{ number_format($c->goal_amount) }}</span>
                                </div>
                                <div class="prog-bar"><div class="prog-fill {{ $pct >= 90 ? 'ok' : '' }}" style="width:{{ $pct }}%"></div></div>
                                <div class="prog-meta">
                                    <span class="prog-pct">{{ $pct }}%</span>
                                    @if($c->end_date)
                                    <span class="prog-days">{{ \Carbon\Carbon::parse($c->end_date)->diffForHumans() }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="c-actions">
                                @if(!$isPaused)
                                <button type="button" onclick="openPause({{ $c->id }})" class="btn btn-y" style="flex:1;">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Pause
                                </button>
                                @else
                                <form action="{{ route('admin.campaign.resume', $c->id) }}" method="POST" style="flex:1;" onsubmit="return handleSub(this,'Resuming…')">
                                    @csrf
                                    <button class="btn btn-g" style="width:100%;">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Resume
                                    </button>
                                </form>
                                @endif
                                <a href="{{ route('admin.campaign.show', $c->id) }}" class="btn btn-i" style="flex:1;">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    View
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @endisset

                    {{-- REJECTED --}}
                    @isset($rejectedCampaigns)
                    @foreach($rejectedCampaigns as $c)
                    @php
                        $raised = $c->raised_amount ?? 0;
                        $goal   = $c->goal_amount > 0 ? $c->goal_amount : 1;
                        $pct    = min(100, round(($raised / $goal) * 100));
                        $uName  = $c->user?->name ?? 'Unknown';
                        $uEmail = $c->user?->email ?? '';
                        $uInit  = strtoupper(substr($uName, 0, 1));
                    @endphp
                    <div class="c-card" data-filter="rejected" data-title="{{ strtolower($c->title) }}" data-amount="{{ $c->goal_amount }}" data-date="{{ $c->created_at }}">
                        <div class="c-thumb">
                            @if($c->cover_image)
                                <img src="{{ asset('storage/' . $c->cover_image) }}" alt="{{ $c->title }}" loading="lazy">
                                <div class="c-overlay"></div>
                            @else
                                <div class="c-thumb-placeholder" style="background:rgba(220,38,38,0.04);"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg></div>
                            @endif
                            <div class="c-badge-wrap"><span class="badge b-rejected">Rejected</span></div>
                        </div>
                        <div class="c-user">
                            <div class="c-av" style="background:linear-gradient(135deg,#ef4444,#dc2626);">{{ $uInit }}</div>
                            <div>
                                <div class="c-uname">{{ $uName }}</div>
                                @if($uEmail)<div class="c-uemail">{{ $uEmail }}</div>@endif
                            </div>
                        </div>
                        <div class="c-body">
                            <div class="c-title">{{ $c->title }}</div>
                            @if($c->rejection_reason)
                            <div class="reason reason-r">
                                <div class="reason-lbl">✕ Rejection reason</div>
                                <div class="reason-txt">{{ $c->rejection_reason }}</div>
                            </div>
                            @endif
                            <div class="prog-wrap">
                                <div class="prog-nums">
                                    <span class="prog-raised">₹{{ number_format($raised) }}</span>
                                    <span class="prog-goal">/ ₹{{ number_format($c->goal_amount) }}</span>
                                </div>
                                <div class="prog-bar"><div class="prog-fill danger" style="width:{{ $pct }}%"></div></div>
                                <div class="prog-meta"><span class="prog-pct" style="color:var(--red);">{{ $pct }}% funded</span></div>
                            </div>
                            <div class="c-actions">
                                <a href="{{ route('admin.campaign.show', $c->id) }}" class="btn btn-i" style="flex:1;">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @endisset

                </div>{{-- /#campaignGrid --}}

                @isset($activeCampaigns)
                <div class="pagination-wrap">{{ $activeCampaigns->links() }}</div>
                @endisset

            </div>{{-- /.main-col --}}

            {{-- ═══ RIGHT SIDE COL ═══ --}}
            <div class="side-col">

                {{-- Top Campaign Categories --}}
                <div class="side-panel">
                    <div class="panel-hdr">
                        <div class="panel-title">Top Campaign Categories</div>
                        <div class="panel-more">•••</div>
                    </div>
                    <div class="donut-wrap">
                        <canvas id="donutChart" width="160" height="160"></canvas>
                    </div>
                    <div class="cat-list">
                        @php
                            $catColors = ['#4f46e5','#3b82f6','#10b981','#9ca3af'];
                            $catNames  = ['Community & Environment','Education & Empowerment','Health & Medical Aid','Others'];
                            $catPcts   = [34, 27, 23, 16];
                        @endphp
                        @foreach($catNames as $i => $cn)
                        <div class="cat-row">
                            <div class="cat-left">
                                <div class="cat-dot" style="background:{{ $catColors[$i] }};"></div>
                                <div class="cat-info">
                                    <div class="cat-name">{{ $cn }}</div>
                                </div>
                            </div>
                            <div class="cat-pct">{{ $catPcts[$i] }}%</div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Status Breakdown --}}
                <div class="side-panel">
                    <div class="panel-hdr">
                        <div class="panel-title">Status Breakdown</div>
                        <div class="panel-more">•••</div>
                    </div>
                    @php
                        $cntPending  = $cntPending  ?? 0;
                        $cntActive   = $cntActive   ?? 0;
                        $cntPaused   = $cntPaused   ?? 0;
                        $cntRejected = $cntRejected ?? 0;
                    @endphp
                    <div class="sp-rows">
                        <div class="sp-row">
                            <div class="sp-row-left"><div class="sp-dot" style="background:#059669"></div><span class="sp-label">Active</span></div>
                            <span class="sp-val">{{ $cntActive }}</span>
                        </div>
                        <div class="sp-row">
                            <div class="sp-row-left"><div class="sp-dot" style="background:#d97706"></div><span class="sp-label">Pending</span></div>
                            <span class="sp-val">{{ $cntPending }}</span>
                        </div>
                        <div class="sp-row">
                            <div class="sp-row-left"><div class="sp-dot" style="background:#4f46e5"></div><span class="sp-label">Paused</span></div>
                            <span class="sp-val">{{ $cntPaused }}</span>
                        </div>
                        <div class="sp-row">
                            <div class="sp-row-left"><div class="sp-dot" style="background:#dc2626"></div><span class="sp-label">Rejected</span></div>
                            <span class="sp-val">{{ $cntRejected }}</span>
                        </div>
                        
                    </div>
                    <div class="sp-progress">
                        <div class="sp-prog-hdr">
                            <span>Approval rate</span>
                            <span>{{ $approvalRate }}%</span>
                        </div>
                        <div class="sp-prog-bar">
                            <div class="sp-prog-fill" style="width:{{ $approvalRate }}%"></div>
                        </div>
                    </div>
                </div>

                {{-- Recent Activity --}}
                <div class="side-panel">
                    <div class="panel-hdr">
                        <div class="panel-title">Recent Activity</div>
                        <div class="panel-more">•••</div>
                    </div>
                    <div>
                        @if($pendingCampaigns > 0)
                        <div class="act-row">
                            <div class="act-icon" style="background:#fef3c7;color:#d97706;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            </div>
                            <div>
                                <div class="act-time">Today</div>
                                <div class="act-text">{{ $pendingCampaigns }} campaign{{ $pendingCampaigns > 1 ? 's' : '' }} awaiting review</div>
                            </div>
                        </div>
                        @endif
                        <div class="act-row">
                            <div class="act-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <div class="act-time">Active</div>
                                <div class="act-text">{{ $cntActive }} campaigns currently running on the platform</div>
                            </div>
                        </div>
                        <div class="act-row">
                            <div class="act-icon" style="background:#d1fae5;color:#059669;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <div>
                                <div class="act-time">All time</div>
                                <div class="act-text">{{ $approvedCampaigns }} campaigns approved ({{ $approvalRate }}% approval rate)</div>
                            </div>
                        </div>
                        <div class="act-row">
                            <div class="act-icon" style="background:#fee2e2;color:#dc2626;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            </div>
                            <div>
                                <div class="act-time">Total</div>
                                <div class="act-text">{{ $cntRejected }} campaigns rejected from the platform</div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>{{-- /.side-col --}}

        </div>{{-- /.main-layout --}}
    </div>{{-- /.body --}}

</div>{{-- /.main --}}
</div>{{-- /.shell --}}

{{-- PAUSE MODAL --}}
<div id="pauseOverlay" class="overlay" role="dialog" aria-modal="true">
    <div class="modal">
        <button type="button" class="modal-x" onclick="closePause()">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        <div class="modal-head">
            <div class="modal-icon" style="background:var(--yellow-lt);">
                <svg viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <div class="modal-ttl">Pause Campaign</div>
                <div class="modal-sub">Reason will be shown to the campaign owner</div>
            </div>
        </div>
        <form id="pauseForm" method="POST">
            @csrf
            <p style="font-size:11.5px;font-weight:600;color:var(--text2);margin-bottom:8px;">Select or type a reason <span style="color:var(--red);">*</span></p>
            <div class="chips">
                <button type="button" class="chip chip-y" data-r="Suspicious activity detected">Suspicious activity</button>
                <button type="button" class="chip chip-y" data-r="Incomplete or missing documents">Missing documents</button>
                <button type="button" class="chip chip-y" data-r="Under review by admin team">Under review</button>
                <button type="button" class="chip chip-y" data-r="Violation of platform guidelines">Policy violation</button>
                <button type="button" class="chip chip-y" data-r="Awaiting additional verification">Pending verification</button>
            </div>
            <textarea id="pauseReason" name="reason" rows="3" placeholder="Or type a custom reason…" class="modal-ta"></textarea>
            <p id="pauseErr" class="modal-err">⚠ Please provide a reason before pausing.</p>
            <div class="modal-acts">
                <button type="button" onclick="closePause()" class="modal-btn modal-cancel">Cancel</button>
                <button type="submit" id="pauseBtn" class="modal-btn modal-y-btn">⏸ Pause Campaign</button>
            </div>
        </form>
    </div>
</div>

{{-- REJECT MODAL --}}
<div id="rejectOverlay" class="overlay" role="dialog" aria-modal="true">
    <div class="modal">
        <button type="button" class="modal-x" onclick="closeReject()">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        <div class="modal-head">
            <div class="modal-icon" style="background:var(--red-lt);">
                <svg viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <div class="modal-ttl">Reject Campaign</div>
                <div class="modal-sub">Reason will be shown to the campaign owner</div>
            </div>
        </div>
        <form id="rejectForm" method="POST">
            @csrf
            <p style="font-size:11.5px;font-weight:600;color:var(--text2);margin-bottom:8px;">Select or type a reason <span style="color:var(--red);">*</span></p>
            <div class="chips">
                <button type="button" class="chip chip-r" data-r="Fraudulent or misleading content">Fraudulent content</button>
                <button type="button" class="chip chip-r" data-r="Incomplete campaign information">Incomplete info</button>
                <button type="button" class="chip chip-r" data-r="Violation of platform terms">Terms violation</button>
                <button type="button" class="chip chip-r" data-r="Duplicate campaign detected">Duplicate campaign</button>
                <button type="button" class="chip chip-r" data-r="Insufficient documentation provided">Insufficient docs</button>
            </div>
            <textarea id="rejectReason" name="reason" rows="3" placeholder="Or type a custom reason…" class="modal-ta"></textarea>
            <p id="rejectErr" class="modal-err">⚠ Please provide a reason before rejecting.</p>
            <div class="modal-acts">
                <button type="button" onclick="closeReject()" class="modal-btn modal-cancel">Cancel</button>
                <button type="submit" id="rejectBtn" class="modal-btn modal-r-btn">✕ Reject Campaign</button>
            </div>
        </form>
    </div>
</div>

<script>
(function () {
'use strict';

/* ── Avatar dropdown ── */
window.toggleAvatarDD = function () {
    document.getElementById('avatarDD').classList.toggle('open');
};
document.addEventListener('click', function (e) {
    var wrap = document.getElementById('avatarWrap');
    var dd   = document.getElementById('avatarDD');
    if (wrap && dd && !wrap.contains(e.target)) dd.classList.remove('open');
});

/* ── Hamburger ── */
var sidebar = document.getElementById('sidebar');
document.getElementById('hamburger').addEventListener('click', function () {
    sidebar.classList.toggle('open');
});
document.addEventListener('click', function (e) {
    if (window.innerWidth <= 860 && sidebar && !sidebar.contains(e.target) && !document.getElementById('hamburger').contains(e.target)) {
        sidebar.classList.remove('open');
    }
});

/* ── Toast ── */
function toast(msg, type) {
    type = type || 'success';
    var icons = {
        success: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
        error:   '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
        warn:    '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>',
    };
    var t = document.createElement('div');
    t.className = 'toast toast-' + type;
    t.innerHTML = (icons[type]||'') + '<span>' + msg + '</span><button class="toast-close" onclick="this.parentElement.remove()">✕</button>';
    document.getElementById('toastContainer').appendChild(t);
    setTimeout(function () {
        t.style.transition = 'opacity 0.3s, transform 0.3s';
        t.style.opacity = '0'; t.style.transform = 'translateX(20px)';
        setTimeout(function () { t.remove(); }, 300);
    }, 4000);
}
window._toast = toast;

@if(session('success'))
    setTimeout(function(){ toast(@json(session('success')), 'success'); }, 200);
@endif
@if(session('error'))
    setTimeout(function(){ toast(@json(session('error')), 'error'); }, 200);
@endif

/* ── Filter / Search / Sort ── */
var cards        = Array.from(document.querySelectorAll('#campaignGrid .c-card'));
var activeFilter = 'all';
var searchQ      = '';
var sortVal      = '';

function applyFilters() {
    var sorted = cards.slice();
    if      (sortVal === 'amount-desc') sorted.sort(function(a,b){ return +b.dataset.amount - +a.dataset.amount; });
    else if (sortVal === 'amount-asc')  sorted.sort(function(a,b){ return +a.dataset.amount - +b.dataset.amount; });
    else if (sortVal === 'date-desc')   sorted.sort(function(a,b){ return new Date(b.dataset.date) - new Date(a.dataset.date); });
    else if (sortVal === 'date-asc')    sorted.sort(function(a,b){ return new Date(a.dataset.date) - new Date(b.dataset.date); });
    var grid = document.getElementById('campaignGrid');
    sorted.forEach(function(c){ grid.appendChild(c); });
    var visible = 0;
    cards.forEach(function(c) {
        var mF = activeFilter === 'all' || c.dataset.filter === activeFilter;
        var mS = !searchQ || (c.dataset.title||'').includes(searchQ);
        c.style.display = (mF && mS) ? '' : 'none';
        if (mF && mS) visible++;
    });
    document.getElementById('noResults').style.display = visible > 0 ? 'none' : 'block';
}

document.querySelectorAll('.ftab').forEach(function(tab) {
    tab.addEventListener('click', function() {
        document.querySelectorAll('.ftab').forEach(function(t){ t.classList.remove('on'); });
        this.classList.add('on');
        activeFilter = this.dataset.filter;
        applyFilters();
    });
});

window.setFilter = function(f) {
    activeFilter = f;
    document.querySelectorAll('.ftab').forEach(function(t){ t.classList.toggle('on', t.dataset.filter === f); });
    applyFilters();
    setTimeout(function(){ var el = document.getElementById('cGrid'); if(el) el.scrollIntoView({behavior:'smooth',block:'start'}); }, 100);
};

var si = document.getElementById('searchInput');
if (si) { var st; si.addEventListener('input', function(){ clearTimeout(st); searchQ = this.value.toLowerCase().trim(); st = setTimeout(applyFilters, 180); }); }

var ss = document.getElementById('sortSelect');
if (ss) { ss.addEventListener('change', function(){ sortVal = this.value; applyFilters(); }); }

/* ── Pause modal ── */
function openPause(id) {
    document.getElementById('pauseForm').action = '/admin/campaign/' + id + '/pause';
    document.getElementById('pauseReason').value = '';
    document.getElementById('pauseErr').style.display = 'none';
    document.getElementById('pauseBtn').disabled = false;
    document.getElementById('pauseBtn').innerHTML = '⏸ Pause Campaign';
    document.querySelectorAll('.chip-y').forEach(function(c){ c.classList.remove('on'); });
    document.getElementById('pauseOverlay').classList.add('open');
    setTimeout(function(){ document.getElementById('pauseReason').focus(); }, 60);
}
function closePause() { document.getElementById('pauseOverlay').classList.remove('open'); }
window.openPause = openPause; window.closePause = closePause;

document.querySelectorAll('.chip-y').forEach(function(btn) {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.chip-y').forEach(function(b){ b.classList.remove('on'); });
        this.classList.add('on');
        document.getElementById('pauseReason').value = this.dataset.r;
        document.getElementById('pauseErr').style.display = 'none';
    });
});
document.getElementById('pauseForm').addEventListener('submit', function(e) {
    if (!document.getElementById('pauseReason').value.trim()) { e.preventDefault(); document.getElementById('pauseErr').style.display = 'block'; return; }
    var btn = document.getElementById('pauseBtn'); btn.disabled = true; btn.innerHTML = 'Pausing…';
});

/* ── Reject modal ── */
function openReject(id) {
    document.getElementById('rejectForm').action = '/admin/campaign/' + id + '/reject';
    document.getElementById('rejectReason').value = '';
    document.getElementById('rejectErr').style.display = 'none';
    document.getElementById('rejectBtn').disabled = false;
    document.getElementById('rejectBtn').innerHTML = '✕ Reject Campaign';
    document.querySelectorAll('.chip-r').forEach(function(c){ c.classList.remove('on'); });
    document.getElementById('rejectOverlay').classList.add('open');
    setTimeout(function(){ document.getElementById('rejectReason').focus(); }, 60);
}
function closeReject() { document.getElementById('rejectOverlay').classList.remove('open'); }
window.openReject = openReject; window.closeReject = closeReject;

document.querySelectorAll('.chip-r').forEach(function(btn) {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.chip-r').forEach(function(b){ b.classList.remove('on'); });
        this.classList.add('on');
        document.getElementById('rejectReason').value = this.dataset.r;
        document.getElementById('rejectErr').style.display = 'none';
    });
});
document.getElementById('rejectForm').addEventListener('submit', function(e) {
    if (!document.getElementById('rejectReason').value.trim()) { e.preventDefault(); document.getElementById('rejectErr').style.display = 'block'; return; }
    var btn = document.getElementById('rejectBtn'); btn.disabled = true; btn.innerHTML = 'Rejecting…';
});

document.getElementById('pauseOverlay').addEventListener('click',  function(e){ if(e.target===this) closePause(); });
document.getElementById('rejectOverlay').addEventListener('click', function(e){ if(e.target===this) closeReject(); });
document.addEventListener('keydown', function(e){ if(e.key==='Escape'){ closePause(); closeReject(); } });

function handleSub(form, txt) {
    form.querySelectorAll('button[type=submit]').forEach(function(b){ b.disabled=true; b.textContent=txt; });
    return true;
}
window.handleSub = handleSub;

/* ── Charts ── */
var lineChart, barChart, donutChart;

function renderCharts() {
    var gridColor = 'rgba(0,0,0,0.05)';
    var lblColor  = '#9499b0';

    Chart.defaults.color       = lblColor;
    Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
    Chart.defaults.font.size   = 11;

    var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];

    /* Line chart */
    if (lineChart) lineChart.destroy();
    var lCtx = document.getElementById('lineChart').getContext('2d');
    var grad1 = lCtx.createLinearGradient(0,0,0,180);
    grad1.addColorStop(0,'rgba(79,70,229,0.12)');
    grad1.addColorStop(1,'rgba(79,70,229,0)');
    var grad2 = lCtx.createLinearGradient(0,0,0,180);
    grad2.addColorStop(0,'rgba(219,39,119,0.10)');
    grad2.addColorStop(1,'rgba(219,39,119,0)');

    lineChart = new Chart(lCtx, {
        type: 'line',
        data: {
            labels: months,
            datasets: [
                { label:'Campaigns', data:[3,5,4,8,6,10,9,14,11,16,13,{{ $totalCampaigns }}], borderColor:'#4f46e5', backgroundColor:grad1, borderWidth:2, pointRadius:3, pointBackgroundColor:'#4f46e5', tension:0.4, fill:true },
                { label:'Approved',  data:[2,3,3,6,5,7,7,11,9,13,10,{{ $approvedCampaigns }}], borderColor:'#db2777', backgroundColor:grad2, borderWidth:2, pointRadius:3, pointBackgroundColor:'#db2777', tension:0.4, fill:true }
            ]
        },
        options: {
            responsive:true, maintainAspectRatio:false,
            interaction:{ intersect:false, mode:'index' },
            plugins:{
                legend:{ display:true, position:'top', labels:{ boxWidth:8, boxHeight:8, borderRadius:3, useBorderRadius:true, padding:12 } },
                tooltip:{ backgroundColor:'#fff', titleColor:'#1a1d2e', bodyColor:'#52576e', borderColor:'#eaecf4', borderWidth:1, padding:10, cornerRadius:8 }
            },
            scales:{
                x:{ grid:{ color:gridColor }, border:{ dash:[3,3], display:false } },
                y:{ grid:{ color:gridColor }, border:{ dash:[3,3], display:false }, ticks:{ stepSize:5 } }
            }
        }
    });

    /* Bar chart */
    if (barChart) barChart.destroy();
    var bCtx = document.getElementById('barChart').getContext('2d');
    barChart = new Chart(bCtx, {
        type: 'bar',
        data: {
            labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug'],
            datasets: [
                { label:'New', data:[4,3,5,7,6,8,5,{{ $pendingCampaigns }}], backgroundColor:'rgba(79,70,229,0.75)', borderRadius:5, borderSkipped:false },
                { label:'Approved', data:[3,2,4,5,4,6,4,{{ $approvedCampaigns > 8 ? 8 : $approvedCampaigns }}], backgroundColor:'rgba(219,39,119,0.65)', borderRadius:5, borderSkipped:false }
            ]
        },
        options: {
            responsive:true, maintainAspectRatio:false,
            interaction:{ intersect:false, mode:'index' },
            plugins:{
                legend:{ display:true, position:'top', labels:{ boxWidth:8, boxHeight:8, borderRadius:3, useBorderRadius:true, padding:12 } },
                tooltip:{ backgroundColor:'#fff', titleColor:'#1a1d2e', bodyColor:'#52576e', borderColor:'#eaecf4', borderWidth:1, padding:10, cornerRadius:8 }
            },
            scales:{
                x:{ grid:{ display:false }, border:{ display:false } },
                y:{ grid:{ color:gridColor }, border:{ dash:[3,3], display:false }, ticks:{ stepSize:3 } }
            }
        }
    });

    /* Donut chart */
    if (donutChart) donutChart.destroy();
    var dCtx = document.getElementById('donutChart').getContext('2d');
    donutChart = new Chart(dCtx, {
        type: 'doughnut',
        data: {
            labels: ['Community & Environment','Education & Empowerment','Health & Medical Aid','Others'],
            datasets: [{ data:[34,27,23,16], backgroundColor:['#4f46e5','#3b82f6','#10b981','#9ca3af'], borderWidth:0, hoverOffset:4 }]
        },
        options: {
            responsive:false, cutout:'72%',
            plugins:{ legend:{ display:false }, tooltip:{ backgroundColor:'#fff', titleColor:'#1a1d2e', bodyColor:'#52576e', borderColor:'#eaecf4', borderWidth:1, padding:10, cornerRadius:8 } }
        }
    });
}

renderCharts();

/* ── Dark mode ── */
function toggleTheme() {
    var html  = document.documentElement;
    var track = document.getElementById('themeTrack');
    var isDark = html.getAttribute('data-theme') === 'dark';
    if (isDark) {
        html.removeAttribute('data-theme');
        track.classList.remove('on');
        localStorage.setItem('theme','light');
    } else {
        html.setAttribute('data-theme','dark');
        track.classList.add('on');
        localStorage.setItem('theme','dark');
    }
    setTimeout(renderCharts, 60);
}
window.toggleTheme = toggleTheme;

// Restore saved theme on load
(function(){
    var saved = localStorage.getItem('theme');
    if (saved === 'dark') {
        document.documentElement.setAttribute('data-theme','dark');
        var t = document.getElementById('themeTrack');
        if (t) t.classList.add('on');
    }
})();

})();
</script>

{{-- ═══ FOOTER ═══ --}}
<footer style="
    margin-left: 220px;
    background: var(--surface);
    border-top: 1px solid var(--border);
    padding: 14px 26px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 10px;
    font-size: 12px;
    color: var(--text3);
    font-family: var(--font);
">
    <span>Copyright &copy; {{ date('Y') }} DonateBazaar. All rights reserved.</span>
    <div style="display:flex;align-items:center;gap:14px;flex-wrap:wrap;">
        <a href="#" style="color:var(--text3);text-decoration:none;transition:color 0.15s;" onmouseover="this.style.color='var(--accent)'" onmouseout="this.style.color='var(--text3)'">Privacy Policy</a>
        <a href="#" style="color:var(--text3);text-decoration:none;transition:color 0.15s;" onmouseover="this.style.color='var(--accent)'" onmouseout="this.style.color='var(--text3)'">Term and conditions</a>
        <a href="{{ url('/admin/messages') }}" style="color:var(--text3);text-decoration:none;transition:color 0.15s;" onmouseover="this.style.color='var(--accent)'" onmouseout="this.style.color='var(--text3)'">Contact</a>
    </div>
    <div style="display:flex;align-items:center;gap:10px;">
        {{-- Facebook --}}
        <a href="#" style="width:28px;height:28px;border-radius:6px;border:1px solid var(--border);background:var(--surface2);display:flex;align-items:center;justify-content:center;color:var(--text3);transition:all 0.15s;text-decoration:none;" onmouseover="this.style.color='var(--accent)';this.style.borderColor='var(--accent)'" onmouseout="this.style.color='var(--text3)';this.style.borderColor='var(--border)'">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg>
        </a>
        {{-- X / Twitter --}}
        <a href="#" style="width:28px;height:28px;border-radius:6px;border:1px solid var(--border);background:var(--surface2);display:flex;align-items:center;justify-content:center;color:var(--text3);transition:all 0.15s;text-decoration:none;" onmouseover="this.style.color='var(--accent)';this.style.borderColor='var(--accent)'" onmouseout="this.style.color='var(--text3)';this.style.borderColor='var(--border)'">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.736l7.73-8.835L1.254 2.25H8.08l4.253 5.622zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
        </a>
        {{-- Instagram --}}
        <a href="#" style="width:28px;height:28px;border-radius:6px;border:1px solid var(--border);background:var(--surface2);display:flex;align-items:center;justify-content:center;color:var(--text3);transition:all 0.15s;text-decoration:none;" onmouseover="this.style.color='var(--accent)';this.style.borderColor='var(--accent)'" onmouseout="this.style.color='var(--text3)';this.style.borderColor='var(--border)'">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg>
        </a>
        {{-- YouTube --}}
        <a href="#" style="width:28px;height:28px;border-radius:6px;border:1px solid var(--border);background:var(--surface2);display:flex;align-items:center;justify-content:center;color:var(--text3);transition:all 0.15s;text-decoration:none;" onmouseover="this.style.color='var(--accent)';this.style.borderColor='var(--accent)'" onmouseout="this.style.color='var(--text3)';this.style.borderColor='var(--border)'">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M22.54 6.42a2.78 2.78 0 00-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46a2.78 2.78 0 00-1.95 1.96A29 29 0 001 12a29 29 0 00.46 5.58A2.78 2.78 0 003.41 19.6C5.12 20 12 20 12 20s6.88 0 8.59-.46a2.78 2.78 0 001.95-1.95A29 29 0 0023 12a29 29 0 00-.46-5.58zM9.75 15.02V8.98L15.5 12l-5.75 3.02z"/></svg>
        </a>
        {{-- LinkedIn --}}
        <a href="#" style="width:28px;height:28px;border-radius:6px;border:1px solid var(--border);background:var(--surface2);display:flex;align-items:center;justify-content:center;color:var(--text3);transition:all 0.15s;text-decoration:none;" onmouseover="this.style.color='var(--accent)';this.style.borderColor='var(--accent)'" onmouseout="this.style.color='var(--text3)';this.style.borderColor='var(--border)'">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6zM2 9h4v12H2z"/><circle cx="4" cy="4" r="2"/></svg>
        </a>
    </div>
</footer>

@endsection