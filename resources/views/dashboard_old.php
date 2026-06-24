@extends('layouts.app')

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<style>
/* ═══════════════════════════════════════
   CSS CUSTOM PROPERTIES — LIGHT + DARK
═══════════════════════════════════════ */
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
body {
    font-family: var(--font);
    background: var(--bg);
    color: var(--text);
    line-height: 1.5;
    -webkit-font-smoothing: antialiased;
    transition: background var(--transition), color var(--transition);
}

/* ─── LAYOUT SHELL ─── */
.shell { display: flex; min-height: 100vh; }

/* ─── SIDEBAR ─── */
.sidebar {
    width: 256px; flex-shrink: 0;
    background: var(--sidebar-bg);
    display: flex; flex-direction: column;
    position: fixed; top: 0; left: 0; bottom: 0;
    z-index: 200; overflow-y: auto; overflow-x: hidden;
    border-right: 1px solid rgba(255,255,255,0.04);
    transition: width 0.3s cubic-bezier(.4,0,.2,1), transform 0.3s ease;
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
    flex-shrink: 0;
    box-shadow: 0 4px 14px rgba(99,102,241,0.35);
}
.s-logo-mark svg { width: 18px; height: 18px; color: #fff; }
.s-logo-name { font-size: 17px; font-weight: 800; color: #fff; letter-spacing: -0.01em; }
.s-logo-tag  { font-size: 9px; color: rgba(255,255,255,0.3); text-transform: uppercase; letter-spacing: 0.12em; margin-top: 1px; }

.s-user {
    margin: 12px 10px 4px;
    padding: 10px 12px;
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(255,255,255,0.06);
    border-radius: var(--radius-sm);
    display: flex; align-items: center; gap: 9px;
}
.s-avatar {
    width: 32px; height: 32px; border-radius: 8px;
    background: linear-gradient(135deg, var(--accent), var(--accent2));
    color: #fff; font-size: 13px; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.s-user-name { font-size: 12.5px; font-weight: 600; color: rgba(255,255,255,0.85); }
.s-user-role { font-size: 10px; color: rgba(255,255,255,0.35); margin-top: 1px; }

.s-label {
    font-size: 9.5px; font-weight: 700;
    color: rgba(255,255,255,0.25);
    text-transform: uppercase; letter-spacing: 0.14em;
    padding: 16px 18px 5px;
    font-family: var(--font-mono);
}
.s-nav { padding: 0 8px; }
.s-link {
    display: flex; align-items: center; gap: 10px;
    padding: 9px 11px; border-radius: 9px;
    color: var(--sidebar-text);
    font-size: 13px; font-weight: 500;
    text-decoration: none;
    transition: background var(--transition), color var(--transition);
    margin-bottom: 1px;
    border: none; background: transparent; width: 100%;
    text-align: left; cursor: pointer; position: relative;
}
.s-link:hover  { background: rgba(255,255,255,0.06); color: rgba(255,255,255,0.9); }
.s-link.active { background: var(--sidebar-act); color: #a5b4fc; }
.s-link.active::before {
    content: ''; position: absolute;
    left: 0; top: 20%; bottom: 20%;
    width: 3px; border-radius: 0 2px 2px 0;
    background: var(--accent);
}
.s-icon  { width: 16px; height: 16px; flex-shrink: 0; opacity: 0.8; }
.s-badge {
    margin-left: auto;
    font-size: 10px; font-weight: 700;
    padding: 2px 7px; border-radius: 100px;
    background: rgba(99,102,241,0.25); color: #a5b4fc;
    font-family: var(--font-mono);
}
.s-badge.ok   { background: rgba(16,185,129,0.2); color: #34d399; }
.s-badge.warn { background: rgba(245,158,11,0.2); color: #fbbf24; }
.s-bottom {
    margin-top: auto; padding: 12px 8px 16px;
    border-top: 1px solid rgba(255,255,255,0.05);
}

/* ─── MAIN ─── */
.main { margin-left: 256px; flex: 1; min-width: 0; display: flex; flex-direction: column; }

/* ─── TOPBAR ─── */
.topbar {
    display: flex; align-items: center; justify-content: space-between;
    padding: 0 28px; height: 64px;
    background: var(--surface); border-bottom: 1px solid var(--border);
    position: sticky; top: 0; z-index: 100; gap: 16px;
}
.topbar-left h1 { font-size: 18px; font-weight: 800; color: var(--text); letter-spacing: -0.02em; }
.topbar-left p  { font-size: 11px; color: var(--text3); margin-top: 1px; }
.topbar-right   { display: flex; align-items: center; gap: 8px; }

.search-wrap { position: relative; width: 240px; }
.search-wrap svg { position: absolute; left: 11px; top: 50%; transform: translateY(-50%); width: 14px; height: 14px; color: var(--text3); pointer-events: none; }
.search-input {
    width: 100%; height: 36px;
    background: var(--surface2); border: 1px solid var(--border2); border-radius: 10px;
    padding: 0 12px 0 34px; font-size: 12.5px; color: var(--text);
    font-family: var(--font); outline: none;
    transition: border-color var(--transition), box-shadow var(--transition);
}
.search-input::placeholder { color: var(--text3); }
.search-input:focus { border-color: var(--accent); box-shadow: 0 0 0 3px var(--accent-glow); }

.icon-btn {
    width: 36px; height: 36px; border-radius: 10px;
    border: 1px solid var(--border2); background: var(--surface2);
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; color: var(--text2); flex-shrink: 0;
    transition: background var(--transition), color var(--transition), border-color var(--transition);
    position: relative;
}
.icon-btn:hover { background: var(--accent-glow); color: var(--accent); border-color: var(--accent); }
.icon-btn svg { width: 15px; height: 15px; }

.theme-toggle { position: relative; }
.theme-toggle input { position: absolute; opacity: 0; width: 0; height: 0; }
.theme-toggle label {
    display: flex; align-items: center; justify-content: space-between;
    width: 52px; height: 28px; border-radius: 100px;
    background: var(--surface2); border: 1px solid var(--border2);
    cursor: pointer; padding: 3px 4px;
    transition: background var(--transition); position: relative;
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

/* ─── PAGE BODY ─── */
.body { padding: 24px 28px 40px; }

/* ─── STATS GRID ─── */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 16px; margin-bottom: 24px;
}
.stat-card {
    background: var(--surface); border: 1px solid var(--border);
    border-radius: var(--radius); padding: 18px;
    box-shadow: var(--shadow);
    display: flex; flex-direction: column; gap: 10px;
    transition: transform var(--transition), box-shadow var(--transition);
    cursor: default; animation: fadeUp 0.4s both;
}
.stat-card:hover { transform: translateY(-3px); box-shadow: 0 12px 40px rgba(99,102,241,0.12); }
.stat-card:nth-child(1) { animation-delay: 0.05s; }
.stat-card:nth-child(2) { animation-delay: 0.10s; }
.stat-card:nth-child(3) { animation-delay: 0.15s; }
.stat-card:nth-child(4) { animation-delay: 0.20s; }
.stat-head { display: flex; align-items: center; justify-content: space-between; }
.stat-label { font-size: 11px; font-weight: 600; color: var(--text3); text-transform: uppercase; letter-spacing: 0.07em; font-family: var(--font-mono); }
.stat-icon { width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; }
.stat-icon svg { width: 16px; height: 16px; }
.stat-val { font-size: 2.2rem; font-weight: 800; line-height: 1; letter-spacing: -0.03em; }
.stat-foot { font-size: 11px; color: var(--text3); display: flex; align-items: center; gap: 5px; }
.ic-indigo { background: rgba(99,102,241,0.12); color: var(--accent); }
.ic-green  { background: rgba(16,185,129,0.12); color: var(--green); }
.ic-yellow { background: rgba(245,158,11,0.12);  color: var(--yellow); }
.ic-pink   { background: rgba(236,72,153,0.12);  color: #ec4899; }
.v-indigo  { color: var(--accent); }
.v-green   { color: var(--green); }
.v-yellow  { color: var(--yellow); }
.v-pink    { color: #ec4899; }

/* ─── CHART CARD ─── */
.chart-card {
    background: var(--surface); border: 1px solid var(--border);
    border-radius: var(--radius); padding: 20px;
    box-shadow: var(--shadow); margin-bottom: 24px;
    animation: fadeUp 0.4s 0.25s both;
}
.chart-card-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 18px; flex-wrap: wrap; gap: 10px; }
.chart-title { font-size: 14px; font-weight: 800; color: var(--text); letter-spacing: -0.01em; }
.chart-sub   { font-size: 11px; color: var(--text3); margin-top: 2px; }
.chart-legend { display: flex; align-items: center; gap: 6px; font-size: 11px; color: var(--text3); font-family: var(--font-mono); }
.chart-legend-dot { width: 8px; height: 8px; border-radius: 50%; background: var(--accent); }
.chart-wrap { position: relative; height: 200px; }

/* ─── SECTION HEADER + FILTER TABS ─── */
.sec-header {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 16px; flex-wrap: wrap; gap: 12px;
}
.sec-title { font-size: 15px; font-weight: 800; color: var(--text); letter-spacing: -0.02em; }

.ftabs {
    display: flex; gap: 2px;
    background: var(--surface2); border: 1px solid var(--border);
    padding: 3px; border-radius: 11px;
}
.ftab {
    padding: 5px 13px; border-radius: 8px;
    font-size: 12px; font-weight: 500;
    cursor: pointer; border: none;
    background: transparent; color: var(--text3);
    transition: background var(--transition), color var(--transition), box-shadow var(--transition);
    display: inline-flex; align-items: center; gap: 5px;
    font-family: var(--font); white-space: nowrap;
}
.ftab:hover { color: var(--accent); }
.ftab.on {
    background: var(--surface); color: var(--accent); font-weight: 600;
    box-shadow: 0 1px 6px rgba(99,102,241,0.14);
}
.ftab .cnt {
    display: inline-flex; align-items: center; justify-content: center;
    min-width: 17px; height: 17px; border-radius: 100px; font-size: 10px;
    padding: 0 4px; background: rgba(99,102,241,0.10); color: var(--accent);
    font-weight: 700; font-family: var(--font-mono);
}

/* ─── CAMPAIGN GRID ─── */
.c-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 18px;
}
.c-card {
    background: var(--surface); border: 1px solid var(--border);
    border-radius: var(--radius); overflow: hidden;
    display: flex; flex-direction: column;
    box-shadow: var(--shadow);
    transition: transform var(--transition), box-shadow var(--transition), border-color var(--transition);
    animation: fadeUp 0.4s both;
}
.c-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 16px 48px rgba(99,102,241,0.13);
    border-color: rgba(99,102,241,0.2);
}
.c-img-wrap { position: relative; }
.c-img-wrap img { width: 100%; height: 172px; object-fit: cover; display: block; }
.c-img-placeholder {
    width: 100%; height: 172px;
    display: flex; align-items: center; justify-content: center;
    background: var(--surface2);
}
.c-img-placeholder svg { width: 36px; height: 36px; color: var(--text3); opacity: 0.5; }
.c-badge-wrap { position: absolute; top: 10px; right: 10px; }

.badge {
    display: inline-flex; align-items: center;
    font-size: 10px; font-weight: 700; padding: 3px 9px;
    border-radius: 100px; text-transform: uppercase; letter-spacing: 0.06em;
    font-family: var(--font-mono);
}
.b-active   { background: rgba(16,185,129,0.15);  color: #10b981; border: 1px solid rgba(16,185,129,0.25); }
.b-pending  { background: rgba(245,158,11,0.15);  color: #f59e0b; border: 1px solid rgba(245,158,11,0.25); }
.b-rejected { background: rgba(239,68,68,0.15);   color: #ef4444; border: 1px solid rgba(239,68,68,0.25); }
.b-paused   { background: rgba(99,102,241,0.15);  color: #818cf8; border: 1px solid rgba(99,102,241,0.25); }
.b-default  { background: rgba(107,114,128,0.12); color: #9ca3af; border: 1px solid rgba(107,114,128,0.2); }

.c-body { padding: 14px; flex: 1; display: flex; flex-direction: column; }
.c-title {
    font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 11px;
    display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
    line-height: 1.45;
}

/* ─── REASON BANNERS ─── */
.reason {
    padding: 8px 10px; border-radius: 8px; margin-bottom: 10px; border: 1px solid transparent;
}
.reason-y { background: rgba(245,158,11,0.08); border-color: rgba(245,158,11,0.2); }
.reason-r { background: rgba(239,68,68,0.08);  border-color: rgba(239,68,68,0.2); }
.reason-lbl { font-size: 10px; font-weight: 700; margin-bottom: 2px; font-family: var(--font-mono); }
.reason-y .reason-lbl { color: #b45309; }
.reason-r .reason-lbl { color: #b91c1c; }
.reason-txt { font-size: 11px; }
.reason-y .reason-txt { color: var(--yellow); }
.reason-r .reason-txt { color: var(--red); }

/* ─── PROGRESS ─── */
.prog-wrap { margin-bottom: 12px; }
.prog-meta { display: flex; justify-content: space-between; font-size: 11px; margin-bottom: 5px; }
.prog-raised { font-weight: 700; color: var(--accent); font-family: var(--font-mono); }
.prog-goal   { color: var(--text3); font-family: var(--font-mono); }
.prog-bar { width: 100%; background: var(--surface2); border-radius: 100px; height: 4px; overflow: hidden; margin-bottom: 3px; }
.prog-fill { height: 100%; border-radius: 100px; background: linear-gradient(90deg, var(--accent), var(--accent2)); transition: width 1s ease; }
.prog-pct { font-size: 10px; color: var(--text3); font-family: var(--font-mono); }

/* ─── ACTION BUTTONS ─── */
.c-actions { display: flex; gap: 6px; margin-top: auto; padding-top: 2px; }
.btn {
    display: inline-flex; align-items: center; justify-content: center; gap: 4px;
    padding: 7px 12px; border-radius: var(--radius-sm);
    font-size: 11px; font-weight: 600;
    cursor: pointer; border: 1px solid transparent;
    transition: opacity var(--transition), transform var(--transition);
    text-decoration: none; white-space: nowrap; font-family: var(--font);
}
.btn:hover { opacity: 0.85; transform: scale(0.98); }
.btn svg   { width: 11px; height: 11px; }
.btn-i  { background: rgba(99,102,241,0.10);  color: var(--accent); border-color: rgba(99,102,241,0.2); }
.btn-gx { background: var(--surface2); color: var(--text2); border-color: var(--border2); }

/* ─── EMPTY STATE ─── */
.empty-state {
    background: var(--surface); border: 1px solid var(--border);
    border-radius: var(--radius); padding: 60px 20px; text-align: center;
    box-shadow: var(--shadow); animation: fadeUp 0.4s both;
}
.empty-icon {
    width: 56px; height: 56px; border-radius: 16px;
    background: rgba(99,102,241,0.08); border: 1px solid rgba(99,102,241,0.15);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 16px;
}
.empty-icon svg { width: 24px; height: 24px; color: var(--accent); opacity: 0.7; }
.empty-title { font-size: 15px; font-weight: 700; color: var(--text); margin-bottom: 6px; }
.empty-sub   { font-size: 12px; color: var(--text3); max-width: 300px; margin: 0 auto 20px; line-height: 1.6; }
.btn-primary {
    display: inline-flex; align-items: center; gap: 6px;
    background: var(--accent); color: #fff;
    padding: 9px 20px; border-radius: 10px;
    font-size: 13px; font-weight: 600; text-decoration: none;
    transition: opacity var(--transition), transform var(--transition);
    box-shadow: 0 4px 14px rgba(99,102,241,0.3);
}
.btn-primary:hover { opacity: 0.9; transform: translateY(-1px); }
.btn-primary svg { width: 13px; height: 13px; }

/* ─── CREATE BUTTON ─── */
.create-btn {
    display: inline-flex; align-items: center; gap: 6px;
    background: var(--accent); color: #fff;
    padding: 8px 16px; border-radius: 10px;
    font-size: 12px; font-weight: 600; text-decoration: none;
    transition: opacity var(--transition);
    box-shadow: 0 4px 14px rgba(99,102,241,0.3);
}
.create-btn:hover { opacity: 0.9; }
.create-btn svg { width: 13px; height: 13px; }

/* ─── NO RESULTS ─── */
#noResults {
    display: none; text-align: center;
    padding: 40px 20px; color: var(--text3); font-size: 13px;
}

/* ─── TOAST ─── */
.toast-container { position: fixed; top: 20px; right: 20px; z-index: 9999; display: flex; flex-direction: column; gap: 10px; pointer-events: none; }
.toast {
    display: flex; align-items: center; gap: 10px; padding: 13px 16px;
    border-radius: 13px; font-size: 13px; font-weight: 500; color: #fff;
    min-width: 260px; max-width: 360px; box-shadow: var(--shadow-lg); pointer-events: all;
    animation: toastIn 0.35s cubic-bezier(.4,0,.2,1) both;
}
.toast svg { width: 16px; height: 16px; flex-shrink: 0; }
.toast-success { background: linear-gradient(135deg, #059669, #10b981); }
.toast-error   { background: linear-gradient(135deg, #dc2626, #ef4444); }
.toast-close { margin-left: auto; width: 18px; height: 18px; border-radius: 4px; background: rgba(255,255,255,0.2); border: none; cursor: pointer; color: #fff; font-size: 12px; display: flex; align-items: center; justify-content: center; }
.toast-close:hover { background: rgba(255,255,255,0.35); }

/* ─── ANIMATIONS ─── */
@keyframes fadeUp {
    from { opacity: 0; transform: translateY(14px); }
    to   { opacity: 1; transform: translateY(0); }
}
@keyframes toastIn {
    from { opacity: 0; transform: translateX(20px) scale(0.96); }
    to   { opacity: 1; transform: translateX(0) scale(1); }
}

/* ─── RESPONSIVE ─── */
@media (max-width: 860px) {
    .sidebar { transform: translateX(-100%); }
    .sidebar.open { transform: translateX(0); }
    .main { margin-left: 0; }
    .hamburger { display: flex; }
    .search-wrap { display: none; }
}
@media (max-width: 600px) {
    .topbar { padding: 0 16px; }
    .body   { padding: 16px; }
}
</style>

{{-- TOAST CONTAINER --}}
<div class="toast-container" id="toastContainer"></div>

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
        <a href="#" class="s-link active">
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

    <div class="s-label">Campaigns</div>
    <nav class="s-nav">
        @php
            $countAll      = $campaigns->count();
            $countActive   = $campaigns->filter(fn($c) => $c->status === 'approved' && $c->campaign_state === 'active')->count();
            $countPending  = $campaigns->filter(fn($c) => $c->status === 'pending')->count();
            $countPaused   = $campaigns->filter(fn($c) => $c->campaign_state === 'paused')->count();
            $countRejected = $campaigns->filter(fn($c) => $c->status === 'rejected')->count();
        @endphp
        <a href="#cGrid" class="s-link">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            All Campaigns
            <span class="s-badge">{{ $countAll }}</span>
        </a>
        @if($countActive > 0)
        <a href="#cGrid" class="s-link">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Active
            <span class="s-badge ok">{{ $countActive }}</span>
        </a>
        @endif
        @if($countPending > 0)
        <a href="#cGrid" class="s-link">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
            </svg>
            Pending
            <span class="s-badge warn">{{ $countPending }}</span>
        </a>
        @endif
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
        <div style="display:flex;align-items:center;gap:10px;">
            <button class="hamburger" id="hamburger" aria-label="Menu">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <div class="topbar-left">
                <h1>Welcome  {{ auth()->user()->name }}</h1>
                <p>campaigns Overview</p>
            </div>
        </div>
        <div class="topbar-right">
            <div class="search-wrap">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                </svg>
                <input class="search-input" id="searchInput" type="text" placeholder="Search campaigns…" autocomplete="off">
            </div>

            {{-- Dark Mode Toggle --}}
            <div class="theme-toggle" title="Toggle dark mode">
                <input type="checkbox" id="themeToggle">
                <label for="themeToggle">
                    <div class="theme-icons">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="5"/><path stroke-linecap="round" d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/>
                        </svg>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>
                        </svg>
                    </div>
                </label>
            </div>

            <a href="{{ route('campaign.create') }}" class="create-btn">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                New Campaign
            </a>

            <div style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,var(--accent),var(--accent2));color:#fff;font-size:13px;font-weight:700;display:flex;align-items:center;justify-content:center;cursor:pointer;flex-shrink:0;">
                {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
            </div>
        </div>
    </header>

    {{-- PAGE BODY --}}
    <div class="body">

        @php
            $totalRaised = $campaigns->sum('raised_amount');
            $totalGoal   = $campaigns->sum('goal_amount');
            $overallPct  = $totalGoal > 0 ? min(100, round(($totalRaised / $totalGoal) * 100)) : 0;
        @endphp

        {{-- ════ STATS ════ --}}
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-head">
                    <span class="stat-label">Total Raised</span>
                    <div class="stat-icon ic-indigo">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="stat-val v-indigo">₹{{ number_format($totalRaised, 0) }}</div>
                <div class="stat-foot">{{ $overallPct }}% of total goal</div>
            </div>
            <div class="stat-card">
                <div class="stat-head">
                    <span class="stat-label">Total Goal</span>
                    <div class="stat-icon ic-pink">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </div>
                </div>
                <div class="stat-val v-pink">₹{{ number_format($totalGoal, 0) }}</div>
                <div class="stat-foot">Across all campaigns</div>
            </div>
            <div class="stat-card">
                <div class="stat-head">
                    <span class="stat-label">Active</span>
                    <div class="stat-icon ic-green">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="stat-val v-green">{{ $countActive }}</div>
                <div class="stat-foot">Approved &amp; running</div>
            </div>
            <div class="stat-card">
                <div class="stat-head">
                    <span class="stat-label">Total Campaigns</span>
                    <div class="stat-icon ic-yellow">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                </div>
                <div class="stat-val v-yellow">{{ $countAll }}</div>
                <div class="stat-foot">All time</div>
            </div>
        </div>

        {{-- ════ CHART ════ --}}
        <div class="chart-card">
            <div class="chart-card-header">
                <div>
                    <div class="chart-title">Fundraising Overview</div>
                    <div class="chart-sub">Monthly funds raised this year</div>
                </div>
                <div class="chart-legend">
                    <div class="chart-legend-dot"></div>
                    Amount Raised (₹)
                </div>
            </div>
            <div class="chart-wrap">
                <canvas id="fundChart"></canvas>
            </div>
        </div>

        {{-- ════ CAMPAIGNS ════ --}}
        <div class="sec-header" id="cGrid">
            <div class="sec-title">Your Campaigns</div>
            <div class="ftabs" id="ftabs">
                <button class="ftab on" data-filter="all">All <span class="cnt">{{ $countAll }}</span></button>
                <button class="ftab" data-filter="active">Active <span class="cnt">{{ $countActive }}</span></button>
                <button class="ftab" data-filter="pending">Pending <span class="cnt">{{ $countPending }}</span></button>
                <button class="ftab" data-filter="paused">Paused <span class="cnt">{{ $countPaused }}</span></button>
                <button class="ftab" data-filter="rejected">Rejected <span class="cnt">{{ $countRejected }}</span></button>
            </div>
        </div>

        <div id="noResults">No campaigns match this filter.</div>

        @if($campaigns->count() > 0)

        <div class="c-grid" id="campaignGrid">
            @foreach($campaigns as $campaign)
            @php
                if ($campaign->campaign_state === 'paused') {
                    $filterVal  = 'paused';
                    $badgeClass = 'b-paused';
                    $badgeLabel = 'Paused';
                } elseif ($campaign->status === 'rejected') {
                    $filterVal  = 'rejected';
                    $badgeClass = 'b-rejected';
                    $badgeLabel = 'Rejected';
                } elseif ($campaign->status === 'pending') {
                    $filterVal  = 'pending';
                    $badgeClass = 'b-pending';
                    $badgeLabel = 'Pending';
                } elseif ($campaign->status === 'approved' && $campaign->campaign_state === 'active') {
                    $filterVal  = 'active';
                    $badgeClass = 'b-active';
                    $badgeLabel = 'Active';
                } else {
                    $filterVal  = 'other';
                    $badgeClass = 'b-default';
                    $badgeLabel = ucfirst($campaign->status ?? 'Draft');
                }
                $raised     = $campaign->raised_amount ?? 0;
                $goal       = $campaign->goal_amount > 0 ? $campaign->goal_amount : 1;
                $percentage = min(100, round(($raised / $goal) * 100));
            @endphp

            <div class="c-card" data-filter="{{ $filterVal }}" data-title="{{ strtolower($campaign->title) }}">
                <div class="c-img-wrap">
                    @if($campaign->cover_image)
                    <img src="{{ asset('storage/' . $campaign->cover_image) }}" alt="{{ $campaign->title }}" loading="lazy">
                    @else
                    <div class="c-img-placeholder">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/>
                        </svg>
                    </div>
                    @endif
                    <div class="c-badge-wrap"><span class="badge {{ $badgeClass }}">{{ $badgeLabel }}</span></div>
                </div>

                <div class="c-body">
                    <div class="c-title">{{ $campaign->title }}</div>

                    @if($filterVal === 'rejected' && $campaign->rejection_reason)
                    <div class="reason reason-r">
                        <div class="reason-lbl">✕ Rejection reason</div>
                        <div class="reason-txt">{{ $campaign->rejection_reason }}</div>
                    </div>
                    @endif

                    @if($filterVal === 'paused' && $campaign->pause_reason)
                    <div class="reason reason-y">
                        <div class="reason-lbl">⏸ Pause reason</div>
                        <div class="reason-txt">{{ $campaign->pause_reason }}</div>
                    </div>
                    @endif

                    <div class="prog-wrap">
                        <div class="prog-meta">
                            <span class="prog-raised">₹{{ number_format($raised) }}</span>
                            <span class="prog-goal">of ₹{{ number_format($campaign->goal_amount) }}</span>
                        </div>
                        <div class="prog-bar">
                            <div class="prog-fill" style="width:{{ $percentage }}%"></div>
                        </div>
                        <div class="prog-pct">{{ $percentage }}% funded</div>
                    </div>

                    <div class="c-actions">
                        <a href="{{ route('campaign.show', $campaign->id) }}" class="btn btn-i" style="flex:1;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            View
                        </a>
                        <a href="{{ route('campaign.edit', $campaign->id) }}" class="btn btn-gx" style="flex:1;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Edit
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @else

        <div class="empty-state">
            <div class="empty-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="empty-title">Start your first fundraiser</div>
            <div class="empty-sub">Create a campaign and start making a difference today.</div>
            <a href="{{ route('campaign.create') }}" class="btn-primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Create Campaign
            </a>
        </div>

        @endif

    </div>{{-- /.body --}}
</div>{{-- /.main --}}
</div>{{-- /.shell --}}

<script>
(function () {
'use strict';

/* ─── Dark mode ─── */
var html   = document.documentElement;
var toggle = document.getElementById('themeToggle');
var saved  = localStorage.getItem('theme') || 'light';
if (saved === 'dark') { html.setAttribute('data-theme','dark'); toggle.checked = true; }
toggle.addEventListener('change', function () {
    var t = this.checked ? 'dark' : 'light';
    html.setAttribute('data-theme', t);
    localStorage.setItem('theme', t);
    setTimeout(renderChart, 50);
});

/* ─── Hamburger ─── */
var sidebar = document.getElementById('sidebar');
document.getElementById('hamburger').addEventListener('click', function () {
    sidebar.classList.toggle('open');
});
document.addEventListener('click', function (e) {
    if (window.innerWidth <= 860 && !sidebar.contains(e.target) && !document.getElementById('hamburger').contains(e.target)) {
        sidebar.classList.remove('open');
    }
});

/* ─── Toast ─── */
function toast(msg, type) {
    type = type || 'success';
    var t = document.createElement('div');
    t.className = 'toast toast-' + type;
    t.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><span>' + msg + '</span><button class="toast-close" onclick="this.parentElement.remove()">✕</button>';
    document.getElementById('toastContainer').appendChild(t);
    setTimeout(function () { t.remove(); }, 4500);
}
@if(session('success'))
    setTimeout(function(){ toast(@json(session('success')), 'success'); }, 200);
@endif
@if(session('error'))
    setTimeout(function(){ toast(@json(session('error')), 'error'); }, 200);
@endif

/* ─── Filter tabs ─── */
var cards       = Array.from(document.querySelectorAll('#campaignGrid .c-card'));
var activeFilter = 'all';
var searchQ      = '';

function applyFilters() {
    var visible = 0;
    cards.forEach(function (c) {
        var matchF = activeFilter === 'all' || c.dataset.filter === activeFilter;
        var matchS = !searchQ || (c.dataset.title || '').includes(searchQ);
        c.style.display = (matchF && matchS) ? '' : 'none';
        if (matchF && matchS) visible++;
    });
    var noRes = document.getElementById('noResults');
    if (noRes) noRes.style.display = visible > 0 ? 'none' : 'block';
}

document.querySelectorAll('.ftab').forEach(function (tab) {
    tab.addEventListener('click', function () {
        document.querySelectorAll('.ftab').forEach(function (t) { t.classList.remove('on'); });
        this.classList.add('on');
        activeFilter = this.dataset.filter;
        applyFilters();
    });
});

var searchTimeout;
document.getElementById('searchInput').addEventListener('input', function () {
    clearTimeout(searchTimeout);
    searchQ = this.value.toLowerCase().trim();
    searchTimeout = setTimeout(applyFilters, 180);
});

/* ─── Chart ─── */
var fundChart;
function renderChart() {
    var isDark     = html.getAttribute('data-theme') === 'dark';
    var gridColor  = isDark ? 'rgba(255,255,255,0.06)' : 'rgba(0,0,0,0.05)';
    var labelColor = isDark ? 'rgba(255,255,255,0.4)'  : 'rgba(0,0,0,0.4)';
    var tooltipBg  = isDark ? '#1a1b2e' : '#fff';
    var tooltipTx  = isDark ? '#f0f1ff' : '#111';

    Chart.defaults.color = labelColor;
    Chart.defaults.font.family = "'DM Mono', monospace";
    Chart.defaults.font.size = 11;

    var ctx = document.getElementById('fundChart');
    if (!ctx) return;

    if (fundChart) fundChart.destroy();

    var monthlyData = @json($monthlyData ?? []);
    var labels = Object.keys(monthlyData);
    var values = Object.values(monthlyData);

    var grad = ctx.getContext('2d').createLinearGradient(0, 0, 0, 200);
    grad.addColorStop(0, 'rgba(99,102,241,0.18)');
    grad.addColorStop(1, 'rgba(99,102,241,0)');

    fundChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Amount Raised (₹)',
                data: values,
                borderColor: '#6366f1',
                backgroundColor: grad,
                borderWidth: 2.5,
                fill: true, tension: 0.4,
                pointBackgroundColor: '#6366f1',
                pointBorderColor: tooltipBg,
                pointBorderWidth: 2,
                pointRadius: 4, pointHoverRadius: 6,
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            interaction: { intersect: false, mode: 'index' },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: tooltipBg,
                    titleColor: tooltipTx, bodyColor: tooltipTx,
                    borderColor: gridColor, borderWidth: 1,
                    padding: 12, cornerRadius: 10,
                    callbacks: {
                        label: function (ctx) {
                            return ' ₹' + Number(ctx.parsed.y).toLocaleString('en-IN');
                        }
                    }
                }
            },
            scales: {
                x: { grid: { color: gridColor }, border: { dash: [4,4] }, ticks: { color: labelColor } },
                y: {
                    grid: { color: gridColor }, border: { dash: [4,4] },
                    ticks: {
                        color: labelColor,
                        callback: function (v) { return '₹' + Number(v).toLocaleString('en-IN'); }
                    }
                }
            }
        }
    });
}

renderChart();

})();
</script>

@endsection