<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edit Campaign — DonateBazaar</title>
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
    width: 220px; flex-shrink: 0;
    background: var(--sidebar-bg);
    display: flex; flex-direction: column;
    position: fixed; top: 0; left: 0; bottom: 0;
    z-index: 200; overflow-y: auto; overflow-x: hidden;
    border-right: 1px solid rgba(255,255,255,0.04);
    transition: transform 0.3s ease;
}
.sidebar::-webkit-scrollbar { width: 3px; }
.sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.08); border-radius: 3px; }

.s-logo { display: flex; align-items: center; gap: 10px; padding: 20px 16px 16px; border-bottom: 1px solid rgba(255,255,255,0.05); flex-shrink: 0; }
.s-logo-mark { width: 34px; height: 34px; border-radius: 10px; background: linear-gradient(135deg, var(--accent), var(--accent2)); display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 4px 14px rgba(99,102,241,0.35); }
.s-logo-mark svg { width: 17px; height: 17px; color: #fff; }
.s-logo-name { font-size: 15px; font-weight: 700; color: #fff; letter-spacing: -0.01em; }
.s-logo-tag  { font-size: 9px; color: rgba(255,255,255,0.3); text-transform: uppercase; letter-spacing: 0.12em; margin-top: 1px; }

.s-user { margin: 10px 8px 4px; padding: 9px 11px; background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.06); border-radius: var(--radius-sm); display: flex; align-items: center; gap: 9px; flex-shrink: 0; }
.s-avatar { width: 30px; height: 30px; border-radius: 7px; background: linear-gradient(135deg, var(--accent), var(--accent2)); color: #fff; font-size: 12px; font-weight: 700; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.s-user-name { font-size: 12px; font-weight: 600; color: rgba(255,255,255,0.85); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.s-user-role { font-size: 10px; color: rgba(255,255,255,0.35); margin-top: 1px; }

.s-label { font-size: 9px; font-weight: 700; color: rgba(255,255,255,0.22); text-transform: uppercase; letter-spacing: 0.14em; padding: 14px 16px 4px; font-family: var(--font-mono); }
.s-nav { padding: 0 6px; }
.s-link { display: flex; align-items: center; gap: 9px; padding: 8px 10px; border-radius: 8px; color: var(--sidebar-text); font-size: 12.5px; font-weight: 500; text-decoration: none; transition: background var(--transition), color var(--transition); margin-bottom: 1px; border: none; background: transparent; width: 100%; text-align: left; cursor: pointer; position: relative; }
.s-link:hover  { background: rgba(255,255,255,0.06); color: rgba(255,255,255,0.9); }
.s-link.active { background: var(--sidebar-act); color: #a5b4fc; }
.s-link.active::before { content: ''; position: absolute; left: 0; top: 20%; bottom: 20%; width: 3px; border-radius: 0 2px 2px 0; background: var(--accent); }
.s-icon { width: 15px; height: 15px; flex-shrink: 0; opacity: 0.8; }
.s-divider { height: 1px; background: rgba(255,255,255,0.05); margin: 6px 14px; }
.s-bottom { margin-top: auto; padding: 10px 6px 14px; border-top: 1px solid rgba(255,255,255,0.05); flex-shrink: 0; }

/* ══ MAIN ══ */
.main { margin-left: 220px; flex: 1; min-width: 0; display: flex; flex-direction: column; min-height: 100vh; }

/* ── Topbar ── */
.topbar { display: flex; align-items: center; justify-content: space-between; padding: 0 24px; height: 60px; background: var(--surface); border-bottom: 1px solid var(--border); position: sticky; top: 0; z-index: 100; gap: 16px; flex-shrink: 0; }
.topbar-left { display: flex; align-items: center; gap: 10px; }
.topbar-back { display: flex; align-items: center; justify-content: center; width: 30px; height: 30px; border-radius: 8px; border: 1px solid var(--border2); background: var(--surface2); color: var(--text2); cursor: pointer; text-decoration: none; transition: all var(--transition); flex-shrink: 0; }
.topbar-back:hover { background: var(--accent-glow); color: var(--accent); border-color: var(--accent); }
.topbar-back svg { width: 13px; height: 13px; }
.topbar-title h1 { font-size: 17px; font-weight: 700; color: var(--text); letter-spacing: -0.02em; }
.topbar-title p  { font-size: 11px; color: var(--text3); margin-top: 1px; }
.topbar-right { display: flex; align-items: center; gap: 8px; }

.status-chip { display: inline-flex; align-items: center; gap: 6px; padding: 4px 11px; border-radius: 100px; font-size: 10.5px; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase; font-family: var(--font-mono); }
.status-chip .dot { width: 5px; height: 5px; border-radius: 50%; background: currentColor; flex-shrink: 0; }
.chip-active   { background: rgba(16,185,129,0.12); color: #10b981; border: 1px solid rgba(16,185,129,0.25); }
.chip-paused   { background: rgba(99,102,241,0.12); color: #818cf8; border: 1px solid rgba(99,102,241,0.25); }
.chip-pending  { background: rgba(245,158,11,0.12); color: #f59e0b; border: 1px solid rgba(245,158,11,0.25); }
.chip-rejected { background: rgba(239,68,68,0.12);  color: #ef4444; border: 1px solid rgba(239,68,68,0.25); }

.theme-toggle { position: relative; }
.theme-toggle input { position: absolute; opacity: 0; width: 0; height: 0; }
.theme-toggle label { display: flex; align-items: center; justify-content: space-between; width: 50px; height: 26px; border-radius: 100px; background: var(--surface2); border: 1px solid var(--border2); cursor: pointer; padding: 3px 4px; position: relative; transition: background var(--transition); }
.theme-toggle label::after { content: ''; width: 18px; height: 18px; border-radius: 50%; background: var(--accent); position: absolute; left: 4px; transition: transform 0.3s cubic-bezier(.4,0,.2,1); box-shadow: 0 2px 6px rgba(99,102,241,0.4); }
.theme-toggle input:checked + label::after { transform: translateX(22px); }
.theme-icons { display: flex; justify-content: space-between; width: 100%; position: relative; z-index: 1; }
.theme-icons svg { width: 11px; height: 11px; color: var(--text3); }
.t-avatar { width: 32px; height: 32px; border-radius: 9px; background: linear-gradient(135deg,var(--accent),var(--accent2)); color: #fff; font-size: 12px; font-weight: 700; display: flex; align-items: center; justify-content: center; cursor: pointer; flex-shrink: 0; }
.hamburger { display: none; width: 32px; height: 32px; border-radius: 9px; border: 1px solid var(--border2); background: var(--surface2); cursor: pointer; color: var(--text2); align-items: center; justify-content: center; flex-shrink: 0; }
.hamburger svg { width: 15px; height: 15px; }

/* ── Body ── */
.body { padding: 24px 28px 60px; flex: 1; }

/* ══ FLASH ══ */
.flash { display: flex; align-items: flex-start; gap: 10px; padding: 12px 16px; border-radius: var(--radius); font-size: 13px; font-weight: 500; margin-bottom: 20px; border: 1px solid transparent; }
.flash svg { width: 15px; height: 15px; flex-shrink: 0; margin-top: 1px; }
.flash-error   { background: rgba(239,68,68,0.08);  color: var(--red);   border-color: rgba(239,68,68,0.2); }
.flash-success { background: rgba(16,185,129,0.08); color: var(--green); border-color: rgba(16,185,129,0.2); }

/* ══ VALIDATION ERRORS ══ */
.validation-box { background: rgba(239,68,68,0.06); border: 1px solid rgba(239,68,68,0.2); border-radius: var(--radius); padding: 14px 16px; margin-bottom: 20px; }
.validation-box ul { list-style: none; display: flex; flex-direction: column; gap: 4px; }
.validation-box li { font-size: 12.5px; color: var(--red); display: flex; align-items: flex-start; gap: 6px; }
.validation-box li::before { content: '⚠'; flex-shrink: 0; }

/* ══ FORM LAYOUT ══ */
.form-layout { display: grid; grid-template-columns: 1fr 300px; gap: 18px; align-items: start; }

/* ══ CARD ══ */
.card { background: var(--surface); border: 1px solid var(--border2); border-radius: var(--radius); box-shadow: var(--shadow); overflow: hidden; }
.card + .card { margin-top: 14px; }
.card-header { padding: 14px 18px; border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: 10px; }
.card-icon { width: 30px; height: 30px; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.card-icon svg { width: 14px; height: 14px; }
.ic-indigo { background: rgba(99,102,241,0.12); color: var(--accent); }
.ic-green  { background: rgba(16,185,129,0.12); color: var(--green); }
.ic-yellow { background: rgba(245,158,11,0.12); color: var(--yellow); }
.ic-red    { background: rgba(239,68,68,0.12);  color: var(--red); }
.card-title { font-size: 13px; font-weight: 700; color: var(--text); letter-spacing: -0.01em; }
.card-sub   { font-size: 11px; color: var(--text3); margin-top: 1px; }
.card-body  { padding: 18px; }

/* ══ FIELDS ══ */
.field { margin-bottom: 16px; }
.field:last-child { margin-bottom: 0; }
.field label { display: block; font-size: 11px; font-weight: 600; color: var(--text2); margin-bottom: 6px; letter-spacing: 0.01em; text-transform: uppercase; font-family: var(--font-mono); }
.field input,
.field textarea,
.field select {
    width: 100%; border: 1.5px solid var(--border2); border-radius: var(--radius-sm);
    padding: 9px 12px; font-family: var(--font); font-size: 13.5px; color: var(--text);
    background: var(--surface2); outline: none; resize: vertical;
    transition: border-color var(--transition), box-shadow var(--transition), background var(--transition);
}
.field input:focus, .field textarea:focus, .field select:focus { border-color: var(--accent); background: var(--surface); box-shadow: 0 0 0 3px var(--accent-glow); }
.field input::placeholder, .field textarea::placeholder { color: var(--text3); }
.field input:disabled, .field textarea:disabled { opacity: 0.45; cursor: not-allowed; }
.field-err { font-size: 11px; color: var(--red); margin-top: 5px; display: flex; align-items: flex-start; gap: 4px; font-family: var(--font-mono); }
.field-err::before { content: '⚠'; flex-shrink: 0; }

/* ══ COVER ══ */
.cover-current { width: 100%; height: 170px; object-fit: cover; border-radius: var(--radius-sm); border: 1px solid var(--border); display: block; margin-bottom: 12px; }
.cover-placeholder { width: 100%; height: 100px; background: var(--surface2); border: 1px solid var(--border); border-radius: var(--radius-sm); display: flex; align-items: center; justify-content: center; margin-bottom: 12px; }
.cover-placeholder svg { width: 30px; height: 30px; color: var(--text3); opacity: 0.4; }
.file-drop { border: 1.5px dashed var(--border2); border-radius: var(--radius-sm); padding: 18px 14px; text-align: center; cursor: pointer; position: relative; transition: border-color var(--transition), background var(--transition); overflow: hidden; }
.file-drop:hover { border-color: var(--accent); background: var(--accent-glow); }
.file-drop input[type="file"] { position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%; }
.file-drop-icon { width: 34px; height: 34px; border-radius: 10px; background: rgba(99,102,241,0.1); color: var(--accent); display: flex; align-items: center; justify-content: center; margin: 0 auto 8px; }
.file-drop-icon svg { width: 15px; height: 15px; }
.file-drop-label { font-size: 12px; font-weight: 600; color: var(--text); }
.file-drop-hint  { font-size: 11px; color: var(--text3); margin-top: 3px; }
#newPreview { display: none; width: 100%; height: 150px; object-fit: cover; border-radius: var(--radius-sm); margin-top: 10px; border: 1px solid var(--border); }

/* ══ WARN BANNER ══ */
.warn-banner { display: flex; align-items: flex-start; gap: 10px; padding: 11px 13px; border-radius: var(--radius-sm); margin-bottom: 14px; background: rgba(245,158,11,0.08); border: 1px solid rgba(245,158,11,0.2); }
.warn-banner svg { width: 14px; height: 14px; color: var(--yellow); flex-shrink: 0; margin-top: 1px; }
.warn-banner-title { font-size: 11.5px; font-weight: 700; color: var(--yellow); margin-bottom: 2px; font-family: var(--font-mono); }
.warn-banner-body  { font-size: 11.5px; color: var(--text2); }

/* ══ BUTTONS ══ */
.btn { display: inline-flex; align-items: center; justify-content: center; gap: 6px; padding: 9px 16px; border-radius: var(--radius-sm); font-size: 12.5px; font-weight: 600; cursor: pointer; border: 1px solid transparent; font-family: var(--font); transition: opacity var(--transition), transform var(--transition); text-decoration: none; white-space: nowrap; width: 100%; }
.btn:hover  { opacity: 0.87; transform: translateY(-1px); }
.btn:active { transform: translateY(0); }
.btn[disabled] { opacity: 0.38; cursor: not-allowed; transform: none; pointer-events: none; }
.btn svg { width: 13px; height: 13px; }
.btn-primary { background: var(--accent); color: #fff; border-color: var(--accent); box-shadow: 0 4px 14px rgba(99,102,241,0.3); }
.btn-pause   { background: rgba(245,158,11,0.10); color: var(--yellow); border-color: rgba(245,158,11,0.25); }
.btn-resume  { background: rgba(16,185,129,0.10); color: var(--green);  border-color: rgba(16,185,129,0.25); }
.btn-ghost   { background: var(--surface2); color: var(--text2); border-color: var(--border2); }
.btn + .btn  { margin-top: 8px; }

/* ══ SIDEBAR STACK ══ */
.sidebar-stack { display: flex; flex-direction: column; gap: 14px; position: sticky; top: 74px; }

/* ══ CHAR COUNTER ══ */
.char-counter { font-size: 10.5px; color: var(--text3); margin-top: 5px; text-align: right; font-family: var(--font-mono); }

/* ══ MODAL ══ */
.overlay { display: none; position: fixed; inset: 0; z-index: 9998; background: rgba(5,5,20,0.55); backdrop-filter: blur(8px); align-items: center; justify-content: center; padding: 20px; }
.overlay.open { display: flex; }
.modal { background: var(--surface); border: 1px solid var(--border2); border-radius: 18px; box-shadow: var(--shadow-lg); width: 100%; max-width: 400px; padding: 20px; position: relative; animation: modalIn 0.22s cubic-bezier(.4,0,.2,1); }
@keyframes modalIn { from { opacity: 0; transform: scale(0.96) translateY(10px); } to { opacity: 1; transform: scale(1) translateY(0); } }
.modal-x { position: absolute; top: 13px; right: 13px; width: 26px; height: 26px; border-radius: 7px; border: 1px solid var(--border2); background: var(--surface2); cursor: pointer; color: var(--text2); display: flex; align-items: center; justify-content: center; transition: background var(--transition); }
.modal-x:hover { background: var(--border); }
.modal-x svg { width: 11px; height: 11px; }
.modal-head { display: flex; align-items: center; gap: 11px; margin-bottom: 16px; }
.modal-icon { width: 38px; height: 38px; border-radius: 11px; display: flex; align-items: center; justify-content: center; }
.modal-icon svg { width: 18px; height: 18px; }
.modal-ttl { font-size: 14px; font-weight: 700; color: var(--text); }
.modal-sub { font-size: 11px; color: var(--text3); margin-top: 2px; }
.modal-label { font-size: 11px; font-weight: 600; color: var(--text2); margin-bottom: 7px; font-family: var(--font-mono); display: block; text-transform: uppercase; }
.modal-ta { width: 100%; border: 1.5px solid var(--border2); border-radius: 10px; padding: 10px 12px; font-size: 12.5px; color: var(--text); resize: none; font-family: var(--font); background: var(--surface2); outline: none; transition: border-color var(--transition), box-shadow var(--transition); }
.modal-ta:focus { border-color: var(--accent); box-shadow: 0 0 0 3px var(--accent-glow); }
.modal-err { font-size: 11px; color: var(--red); margin-top: 5px; display: none; font-family: var(--font-mono); }
.modal-acts { display: flex; gap: 8px; margin-top: 14px; }
.modal-btn { flex: 1; padding: 10px; border-radius: 10px; font-size: 13px; font-weight: 600; cursor: pointer; border: none; transition: opacity var(--transition); font-family: var(--font); }
.modal-btn:hover { opacity: 0.88; }
.modal-cancel { background: var(--surface2); color: var(--text2); border: 1px solid var(--border2); }
.modal-y-btn  { background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; }
.modal-g-btn  { background: linear-gradient(135deg, #10b981, #059669); color: #fff; }

/* ══ TOAST ══ */
.toast-container { position: fixed; top: 20px; right: 20px; z-index: 9999; display: flex; flex-direction: column; gap: 10px; pointer-events: none; }
.toast { display: flex; align-items: center; gap: 10px; padding: 12px 15px; border-radius: 12px; font-size: 13px; font-weight: 500; color: #fff; min-width: 240px; box-shadow: var(--shadow-lg); pointer-events: all; animation: toastIn 0.35s cubic-bezier(.4,0,.2,1) both; }
.toast svg { width: 15px; height: 15px; flex-shrink: 0; }
.toast-success { background: linear-gradient(135deg, #059669, #10b981); }
.toast-error   { background: linear-gradient(135deg, #dc2626, #ef4444); }
.toast-close { margin-left: auto; width: 18px; height: 18px; border-radius: 4px; background: rgba(255,255,255,0.2); border: none; cursor: pointer; color: #fff; font-size: 12px; display: flex; align-items: center; justify-content: center; }
@keyframes toastIn { from { opacity: 0; transform: translateX(20px) scale(0.96); } to { opacity: 1; transform: translateX(0) scale(1); } }
@keyframes fadeUp  { from { opacity: 0; transform: translateY(14px); } to { opacity: 1; transform: translateY(0); } }

/* ══ RESPONSIVE ══ */
@media (max-width: 900px) { .form-layout { grid-template-columns: 1fr; } .sidebar-stack { position: static; } }
@media (max-width: 820px) { .sidebar { transform: translateX(-100%); } .sidebar.open { transform: translateX(0); } .main { margin-left: 0; } .hamburger { display: flex; } .body { padding: 14px 14px 60px; } }
@media (max-width: 600px) { .topbar { padding: 0 14px; } }
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
        {{-- ✅ FIX: use correct route names --}}
        <a href="{{ route('dashboard') }}" class="s-link">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/></svg>
            Dashboard
        </a>
        <a href="{{ route('campaign.create') }}" class="s-link">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            New Campaign
        </a>
    </nav>

    <div class="s-divider"></div>

    <div class="s-label">Editing</div>
    <nav class="s-nav">
        <a href="#" class="s-link active">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Edit Campaign
        </a>
        {{-- ✅ FIX: correct route name for owner show --}}
        <a href="{{ route('campaign.show', $campaign->id) }}" class="s-link">
            <svg class="s-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            View Campaign
        </a>
    </nav>

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
        <div class="topbar-left">
            <button class="hamburger" id="hamburger" aria-label="Menu">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <a href="{{ route('dashboard') }}" class="topbar-back" title="Back to Dashboard">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5M12 5l-7 7 7 7"/></svg>
            </a>
            <div class="topbar-title">
                <h1>Edit Campaign</h1>
                <p>{{ Str::limit($campaign->title, 45) }}</p>
            </div>
        </div>
        <div class="topbar-right">
            {{-- ✅ FIX: use campaign_state not status --}}
            @if($campaign->campaign_state === 'paused')
                <span class="status-chip chip-paused"><span class="dot"></span> Paused</span>
            @elseif($campaign->campaign_state === 'active')
                <span class="status-chip chip-active"><span class="dot"></span> Active</span>
            @elseif($campaign->campaign_state === 'pending')
                <span class="status-chip chip-pending"><span class="dot"></span> Pending</span>
            @elseif($campaign->campaign_state === 'rejected')
                <span class="status-chip chip-rejected"><span class="dot"></span> Rejected</span>
            @endif

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

        {{-- ✅ FIX: Show ALL validation errors at the top so user sees what failed --}}
        @if ($errors->any())
        <div class="validation-box">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @if(session('error'))
        <div class="flash flash-error">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            {{ session('error') }}
        </div>
        @endif
        @if(session('success'))
        <div class="flash flash-success">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
            {{ session('success') }}
        </div>
        @endif

        {{-- ✅ FIX: Correct form action — uses campaigns.update (resource route) --}}
        <form action="{{ route('campaign.update', $campaign->id) }}" method="POST" enctype="multipart/form-data" id="editForm">
            @csrf
            @method('PUT')

            <div class="form-layout">

                {{-- ════ LEFT ════ --}}
                <div>

                    {{-- Basic Info --}}
                    <div class="card" style="margin-bottom:14px;">
                        <div class="card-header">
                            <div class="card-icon ic-indigo">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </div>
                            <div>
                                <div class="card-title">Basic Information</div>
                                <div class="card-sub">Campaign title, goal amount and description</div>
                            </div>
                        </div>
                        <div class="card-body">

                            <div class="field">
                                <label>Campaign Title</label>
                                <input type="text" name="title"
                                       value="{{ old('title', $campaign->title) }}"
                                       placeholder="Give your campaign a strong title"
                                       {{ $campaign->isPaused() ? 'disabled' : '' }}>
                                @error('title')<div class="field-err">{{ $message }}</div>@enderror
                            </div>

                            {{-- ADD THIS after the title field, before goal_amount field --}}
<div class="field">
    <label>Category</label>
    <select name="category_id" {{ $campaign->isPaused() ? 'disabled' : '' }}>
        <option value="">Select a category…</option>
        @foreach($categories as $cat)
            <option value="{{ $cat->id }}"
                {{ old('category_id', $campaign->category_id) == $cat->id ? 'selected' : '' }}>
                {{ $cat->name }}
            </option>
        @endforeach
    </select>
    @error('category_id')<div class="field-err">{{ $message }}</div>@enderror
</div>

                            <div class="field">
                                <label>Goal Amount (₹)</label>
                                <input type="number" name="goal_amount"
                                       value="{{ old('goal_amount', $campaign->goal_amount) }}"
                                       placeholder="Enter target amount" min="1"
                                       {{ $campaign->isPaused() ? 'disabled' : '' }}>
                                @error('goal_amount')<div class="field-err">{{ $message }}</div>@enderror
                                {{-- ✅ Show level info so user understands the cap --}}
                                @php
                                    $userLevel   = auth()->user()->fundraiserLevelName();
                                    $userMaxGoal = auth()->user()->maxCampaignGoal();
                                @endphp
                                @if($userMaxGoal)
                                <div style="font-size:11px;color:var(--text3);margin-top:5px;font-family:var(--font-mono);">
                                    Level: <strong style="color:var(--accent);">{{ $userLevel }}</strong>
                                    — Max goal: <strong>₹{{ number_format($userMaxGoal) }}</strong>
                                </div>
                                @endif
                            </div>

                            <div class="field">
                                <label>Description</label>
                                <textarea name="description" rows="7" id="descField" maxlength="3000"
                                          placeholder="Tell your story — why this campaign matters..."
                                          {{ $campaign->isPaused() ? 'disabled' : '' }}
                                          oninput="countChars(this,'descCount',3000)">{{ old('description', $campaign->description) }}</textarea>
                                <div class="char-counter"><span id="descCount">{{ strlen(old('description', $campaign->description ?? '')) }}</span>/3000</div>
                                @error('description')<div class="field-err">{{ $message }}</div>@enderror
                            </div>

                        </div>
                    </div>

                    {{-- Cover Image --}}
                    <div class="card">
                        <div class="card-header">
                            <div class="card-icon ic-indigo">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                            </div>
                            <div>
                                <div class="card-title">Cover Image</div>
                                <div class="card-sub">JPG or PNG — max 2MB · Leave empty to keep current</div>
                            </div>
                        </div>
                        <div class="card-body">

                            @if($campaign->cover_image)
                                <img src="{{ asset('storage/' . $campaign->cover_image) }}"
                                     class="cover-current" alt="Current cover" id="currentCover">
                            @else
                                <div class="cover-placeholder" id="currentCover">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                                </div>
                            @endif

                            <div class="file-drop" style="{{ $campaign->isPaused() ? 'pointer-events:none;opacity:0.45;' : '' }}">
                                <input type="file" name="cover_image" accept="image/*"
                                       {{ $campaign->isPaused() ? 'disabled' : '' }}
                                       onchange="previewImage(event)">
                                <div class="file-drop-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                </div>
                                <div class="file-drop-label">Click to upload or drag & drop</div>
                                <div class="file-drop-hint">JPG, JPEG or PNG · max 2MB · optional</div>
                            </div>
                            <img id="newPreview" alt="New cover preview">

                        </div>
                    </div>

                </div>

                {{-- ════ RIGHT ════ --}}
                <div class="sidebar-stack">

                    {{-- Campaign Controls --}}
                    <div class="card">
                        <div class="card-header">
                            <div class="card-icon {{ $campaign->isPaused() ? 'ic-yellow' : 'ic-green' }}">
                                @if($campaign->isPaused())
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                @else
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                @endif
                            </div>
                            <div>
                                <div class="card-title">Campaign Controls</div>
                                <div class="card-sub">Manage campaign state</div>
                            </div>
                        </div>
                        <div class="card-body">
                            @if($campaign->isPaused())
                            <div class="warn-banner">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                                <div>
                                    <div class="warn-banner-title">Editing disabled while paused</div>
                                    <div class="warn-banner-body">{{ $campaign->pause_reason }}</div>
                                </div>
                            </div>
                            {{-- ✅ FIX: correct route name campaigns.resume --}}
                            <button type="button" onclick="openModal('resumeModal')" class="btn btn-resume">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Resume Campaign
                            </button>
                            @elseif($campaign->isActive())
                            <button type="button" onclick="openModal('pauseModal')" class="btn btn-pause">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Pause Campaign
                            </button>
                            @elseif($campaign->isPending())
                            <div style="text-align:center;padding:8px 0;font-size:12px;color:var(--text3);">
                                Campaign is awaiting admin approval. You can still edit content.
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Save --}}
                    <div class="card">
                        <div class="card-header">
                            <div class="card-icon ic-indigo">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                            </div>
                            <div>
                                <div class="card-title">Save Changes</div>
                                <div class="card-sub">{{ $campaign->isPaused() ? 'Resume campaign to save' : 'Updates saved immediately' }}</div>
                            </div>
                        </div>
                        <div class="card-body">
                            <button type="submit" class="btn btn-primary" id="saveBtn"
                                    {{ $campaign->isPaused() ? 'disabled' : '' }}>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/></svg>
                                Save Changes
                            </button>
                            <a href="{{ route('dashboard') }}" class="btn btn-ghost" style="margin-top:8px;display:inline-flex;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                Cancel
                            </a>
                        </div>
                    </div>

                    {{-- Progress --}}
                    <div class="card">
                        <div class="card-header">
                            <div class="card-icon ic-indigo">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                            </div>
                            <div>
                                <div class="card-title">Progress</div>
                                <div class="card-sub">Current fundraising status</div>
                            </div>
                        </div>
                        <div class="card-body">
                            @php
                                $raised = $campaign->raised_amount ?? 0;
                                $goal   = $campaign->goal_amount > 0 ? $campaign->goal_amount : 1;
                                $pct    = min(100, round(($raised / $goal) * 100));
                            @endphp
                            <div style="display:flex;justify-content:space-between;font-size:11px;margin-bottom:6px;">
                                <span style="font-weight:700;color:var(--accent);font-family:var(--font-mono);">₹{{ number_format($raised) }}</span>
                                <span style="color:var(--text3);font-family:var(--font-mono);">of ₹{{ number_format($campaign->goal_amount) }}</span>
                            </div>
                            <div style="width:100%;background:var(--surface2);border-radius:100px;height:5px;overflow:hidden;margin-bottom:5px;">
                                <div style="height:100%;border-radius:100px;width:{{ $pct }}%;background:linear-gradient(90deg,var(--accent),var(--accent2));transition:width 1s ease;"></div>
                            </div>
                            <div style="font-size:10px;color:var(--text3);font-family:var(--font-mono);">{{ $pct }}% funded · {{ $campaign->donor_count ?? 0 }} donors</div>
                        </div>
                    </div>

                </div>{{-- /.sidebar-stack --}}

            </div>{{-- /.form-layout --}}
        </form>

    </div>{{-- /.body --}}
</div>{{-- /.main --}}
</div>{{-- /.shell --}}

{{-- ══ PAUSE MODAL ══ --}}
<div id="pauseModal" class="overlay" role="dialog" aria-modal="true">
    <div class="modal">
        <button type="button" class="modal-x" onclick="closeModal('pauseModal')">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        <div class="modal-head">
            <div class="modal-icon" style="background:rgba(245,158,11,0.12);color:var(--yellow);">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <div class="modal-ttl">Pause Campaign</div>
                <div class="modal-sub">Your campaign will stop appearing publicly</div>
            </div>
        </div>
        {{-- ✅ FIX: correct route name campaigns.pause --}}
        <form action="{{ route('campaign.pause', $campaign->id) }}" method="POST" id="pauseForm">
            @csrf
            <label class="modal-label">Reason for pausing <span style="color:var(--red);">*</span></label>
            <textarea id="pauseReason" name="reason" rows="3"
                      placeholder="Tell us why you're pausing (min 10 chars)..."
                      class="modal-ta" minlength="10" maxlength="500"
                      oninput="countChars(this,'pauseCount',500)"></textarea>
            <div class="char-counter"><span id="pauseCount">0</span>/500</div>
            <p id="pauseErr" class="modal-err">Please provide a reason (min 10 characters).</p>
            <div class="modal-acts">
                <button type="button" onclick="closeModal('pauseModal')" class="modal-btn modal-cancel">Cancel</button>
                <button type="submit" id="pauseSubmitBtn" class="modal-btn modal-y-btn">⏸ Pause Campaign</button>
            </div>
        </form>
    </div>
</div>

{{-- ══ RESUME MODAL ══ --}}
<div id="resumeModal" class="overlay" role="dialog" aria-modal="true">
    <div class="modal">
        <button type="button" class="modal-x" onclick="closeModal('resumeModal')">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        <div class="modal-head">
            <div class="modal-icon" style="background:rgba(16,185,129,0.12);color:var(--green);">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <div class="modal-ttl">Resume Campaign</div>
                <div class="modal-sub">Your campaign will become public again</div>
            </div>
        </div>
        {{-- ✅ FIX: correct route name campaigns.resume --}}
        <form action="{{ route('campaign.resume', $campaign->id) }}" method="POST" id="resumeForm">
            @csrf
            <label class="modal-label">Reason for resuming <span style="color:var(--red);">*</span></label>
            <textarea id="resumeReason" name="resume_reason" rows="3"
                      placeholder="Tell us why you're resuming (min 10 chars)..."
                      class="modal-ta" minlength="10" maxlength="500"
                      oninput="countChars(this,'resumeCount',500)"></textarea>
            <div class="char-counter"><span id="resumeCount">0</span>/500</div>
            <p id="resumeErr" class="modal-err">Please provide a reason (min 10 characters).</p>
            <div class="modal-acts">
                <button type="button" onclick="closeModal('resumeModal')" class="modal-btn modal-cancel">Cancel</button>
                <button type="submit" id="resumeSubmitBtn" class="modal-btn modal-g-btn">▶ Resume Campaign</button>
            </div>
        </form>
    </div>
</div>

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
    if (window.innerWidth <= 820 && !sidebar.contains(e.target) && !document.getElementById('hamburger').contains(e.target)){
        sidebar.classList.remove('open');
    }
});

/* ── Toast ── */
function toast(msg, type) {
    type = type || 'success';
    var t = document.createElement('div');
    t.className = 'toast toast-' + type;
    var icon = type === 'success'
        ? '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
        : '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>';
    t.innerHTML = icon + '<span>' + msg + '</span><button class="toast-close" onclick="this.parentElement.remove()">✕</button>';
    document.getElementById('toastContainer').appendChild(t);
    setTimeout(function(){ if (t.parentElement) t.remove(); }, 4500);
}
@if(session('success'))
    setTimeout(function(){ toast(@json(session('success')), 'success'); }, 200);
@endif
@if(session('error'))
    setTimeout(function(){ toast(@json(session('error')), 'error'); }, 200);
@endif

/* ── Cover preview ── */
window.previewImage = function(event) {
    var file = event.target.files[0];
    if (!file) return;
    var preview = document.getElementById('newPreview');
    preview.src = URL.createObjectURL(file);
    preview.style.display = 'block';
    // Hide the old cover so user sees the new one
    var old = document.getElementById('currentCover');
    if (old) old.style.opacity = '0.4';
};

/* ── Char counter ── */
window.countChars = function(el, spanId) {
    document.getElementById(spanId).textContent = el.value.length;
};

/* ── Modal ── */
window.openModal = function(id) {
    document.getElementById(id).classList.add('open');
    document.body.style.overflow = 'hidden';
    setTimeout(function(){
        var ta = document.querySelector('#' + id + ' .modal-ta');
        if (ta) ta.focus();
    }, 60);
};
window.closeModal = function(id) {
    document.getElementById(id).classList.remove('open');
    document.body.style.overflow = '';
};
['pauseModal','resumeModal'].forEach(function(id){
    document.getElementById(id).addEventListener('click', function(e){
        if (e.target === this) closeModal(id);
    });
});
document.addEventListener('keydown', function(e){
    if (e.key === 'Escape'){ closeModal('pauseModal'); closeModal('resumeModal'); }
});

/* ── Pause form validation ── */
document.getElementById('pauseForm').addEventListener('submit', function(e){
    var v = document.getElementById('pauseReason').value.trim();
    var err = document.getElementById('pauseErr');
    if (v.length < 10){ e.preventDefault(); err.style.display = 'block'; return; }
    err.style.display = 'none';
    var btn = document.getElementById('pauseSubmitBtn');
    btn.disabled = true; btn.textContent = 'Pausing…';
});

/* ── Resume form validation ── */
document.getElementById('resumeForm').addEventListener('submit', function(e){
    var v = document.getElementById('resumeReason').value.trim();
    var err = document.getElementById('resumeErr');
    if (v.length < 10){ e.preventDefault(); err.style.display = 'block'; return; }
    err.style.display = 'none';
    var btn = document.getElementById('resumeSubmitBtn');
    btn.disabled = true; btn.textContent = 'Resuming…';
});

/* ── Save loading state ── */
document.getElementById('editForm').addEventListener('submit', function(){
    var btn = document.getElementById('saveBtn');
    if (!btn.disabled){ btn.disabled = true; btn.textContent = '⏳ Saving…'; }
});

/* ── Init char count ── */
var desc = document.getElementById('descField');
if (desc) document.getElementById('descCount').textContent = desc.value.length;
</script>

</body>
</html>