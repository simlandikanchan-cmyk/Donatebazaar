@extends('layouts.app')

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300&family=DM+Mono:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,700;1,700&display=swap" rel="stylesheet">

<style>
/* ═══════════════════════════════════════════════════════════
   DESIGN SYSTEM
═══════════════════════════════════════════════════════════ */
:root {
    --bg:           #f4f5fb;
    --surface:      #ffffff;
    --surface2:     #f8f9fe;
    --surface3:     #f0f1fa;
    --border:       rgba(0,0,0,0.06);
    --border2:      rgba(0,0,0,0.10);
    --text:         #0f1117;
    --text2:        #4b5563;
    --text3:        #9ca3af;
    --accent:       #6366f1;
    --accent2:      #8b5cf6;
    --accent-glow:  rgba(99,102,241,0.18);
    --green:        #10b981;
    --yellow:       #f59e0b;
    --red:          #ef4444;
    --blue:         #3b82f6;
    --font:         'DM Sans', sans-serif;
    --font-display: 'DM Mono', serif;
    --font-mono:    'DM Mono', monospace;
    --radius:       16px;
    --radius-sm:    10px;
    --radius-lg:    24px;
    --shadow:       0 1px 3px rgba(0,0,0,0.06), 0 4px 16px rgba(0,0,0,0.04);
    --shadow-md:    0 4px 24px rgba(0,0,0,0.08);
    --shadow-lg:    0 8px 40px rgba(0,0,0,0.12);
    --shadow-xl:    0 24px 80px rgba(0,0,0,0.18);
    --transition:   0.25s cubic-bezier(0.4,0,0.2,1);
}

*,*::before,*::after { box-sizing: border-box; margin: 0; padding: 0; }
html { scroll-behavior: smooth; }
body { font-family: var(--font); color: var(--text); background: var(--bg); -webkit-font-smoothing: antialiased; overflow-x: hidden; }
img  { max-width: 100%; display: block; }
a    { text-decoration: none; color: inherit; }

.container { max-width: 1180px; margin: 0 auto; padding: 0 24px; }

/* ── Typography ── */
.eyebrow { font-size: 11px; font-weight: 600; letter-spacing: .14em; text-transform: uppercase; color: var(--accent); font-family: var(--font-mono); display: inline-flex; align-items: center; gap: 8px; margin-bottom: 14px; }
.eyebrow::before { content: ''; width: 20px; height: 2px; background: var(--accent); border-radius: 2px; flex-shrink: 0; }
.section-title { font-family: var(--font-display); font-size: clamp(2rem,3.5vw,2.8rem); font-weight: 700; line-height: 1.15; color: var(--text); margin-bottom: 16px; }
.section-title em { font-style: normal; color: var(--accent); }

/* ── Buttons ── */
.btn { display: inline-flex; align-items: center; gap: 8px; padding: 13px 28px; border-radius: var(--radius); font-weight: 600; font-size: 14px; font-family: var(--font); transition: all var(--transition); border: none; cursor: pointer; white-space: nowrap; }
.btn svg { width: 16px; height: 16px; flex-shrink: 0; transition: transform var(--transition); }
.btn:hover svg { transform: translateX(2px); }
.btn-accent { background: linear-gradient(135deg,var(--accent),var(--accent2)); color: #fff; box-shadow: 0 6px 24px rgba(99,102,241,0.45); }
.btn-accent:hover { transform: translateY(-2px); box-shadow: 0 12px 32px rgba(99,102,241,0.55); opacity: .94; }
.btn-white { background: #fff; color: #1e1b4b; box-shadow: 0 4px 20px rgba(0,0,0,0.15); }
.btn-white:hover { transform: translateY(-2px); box-shadow: 0 12px 32px rgba(0,0,0,0.2); }
.btn-outline-dark { background: transparent; color: var(--text2); border: 1.5px solid var(--border2); }
.btn-outline-dark:hover { border-color: var(--accent); color: var(--accent); background: var(--accent-glow); }

/* ── Reveal animations ── */
.reveal { opacity: 0; transform: translateY(32px); transition: opacity .7s ease, transform .7s ease; }
.reveal.visible { opacity: 1; transform: translateY(0); }
.d1{transition-delay:.1s}.d2{transition-delay:.2s}.d3{transition-delay:.3s}.d4{transition-delay:.4s}.d5{transition-delay:.5s}.d6{transition-delay:.6s}


/* ═══════════════════════════════════════════════════════════
   1. HERO
═══════════════════════════════════════════════════════════ */
.hero { position: relative; width: 100%; min-height: 76vh; overflow: hidden; display: flex; flex-direction: column; }
.hero-bg { position: absolute; inset: 0; z-index: 0; }
.hero-bg img { width: 100%; height: 100%; object-fit: cover; object-position: center 30%; }
.hero-overlay { position: absolute; inset: 0; z-index: 1; background: linear-gradient(110deg, rgba(5,5,20,.95) 0%, rgba(10,10,35,.88) 50%, rgba(15,15,40,.65) 100%); }
.hero-grid-lines { position: absolute; inset: 0; z-index: 1; background-image: linear-gradient(rgba(99,102,241,.06) 1px,transparent 1px), linear-gradient(90deg,rgba(99,102,241,.06) 1px,transparent 1px); background-size: 60px 60px; opacity: .5; }

.hero-inner { position: relative; z-index: 2; display: flex; flex-direction: column; min-height: 76vh; }
.hero-content { flex: 1; display: flex; flex-direction: column; justify-content: center; max-width: 1180px; margin: 0 auto; padding: 110px 24px 180px; width: 100%; }

.hero-pill { display: inline-flex; align-items: center; gap: 10px; background: rgba(255,255,255,.09); border: 1px solid rgba(255,255,255,.2); backdrop-filter: blur(12px); border-radius: 100px; padding: 8px 20px; font-size: 11.5px; font-weight: 600; letter-spacing: .1em; text-transform: uppercase; color: rgba(255,255,255,.85); width: fit-content; margin-bottom: 24px; font-family: var(--font-mono); }
.hero-pill-dot { width: 7px; height: 7px; border-radius: 50%; background: var(--green); animation: pulse-live 2s ease infinite; flex-shrink: 0; }
@keyframes pulse-live { 0%,100%{opacity:1;box-shadow:0 0 0 0 rgba(16,185,129,.5)} 50%{opacity:.8;box-shadow:0 0 0 6px rgba(16,185,129,0)} }

.hero-title { font-family: var(--font-display); font-size: clamp(2.8rem,5.5vw,4.2rem); font-weight: 500; line-height: 1.05; color: #fff; margin-bottom: 20px; max-width: 680px; }
.hero-title em { font-style: normal; color: #a5b4fc; }
.hero-desc { font-size: clamp(15px,1.8vw,17px); color: rgba(255,255,255,.65); font-weight: 300; line-height: 1.8; max-width: 520px; margin-bottom: 36px; }

/* Search bar in hero */
.hero-search { display: flex; align-items: center; gap: 10px; background: rgba(255,255,255,.1); border: 1px solid rgba(255,255,255,.22); backdrop-filter: blur(16px); border-radius: var(--radius); padding: 8px 8px 8px 18px; max-width: 560px; margin-bottom: 36px; transition: background var(--transition), border-color var(--transition); }
.hero-search:focus-within { background: rgba(255,255,255,.15); border-color: rgba(255,255,255,.4); }
.hero-search svg { width: 18px; height: 18px; color: rgba(255,255,255,.5); flex-shrink: 0; }
.hero-search input { flex: 1; background: transparent; border: none; outline: none; color: #fff; font-size: 14px; font-family: var(--font); min-width: 0; }
.hero-search input::placeholder { color: rgba(255,255,255,.45); }
.hero-search-btn { padding: 10px 22px; border-radius: 10px; background: linear-gradient(135deg,var(--accent),var(--accent2)); color: #fff; font-weight: 600; font-size: 13px; font-family: var(--font); border: none; cursor: pointer; transition: opacity var(--transition), transform var(--transition); white-space: nowrap; }
.hero-search-btn:hover { opacity: .9; transform: translateY(-1px); }

/* Trust strip */
.hero-trust { display: flex; align-items: center; gap: 0; flex-wrap: wrap; row-gap: 10px; }
.hero-trust-item { display: inline-flex; align-items: center; gap: 7px; font-size: 13px; color: rgba(255,255,255,.65); padding-right: 22px; white-space: nowrap; }
.hero-trust-item svg { width: 15px; height: 15px; color: var(--green); flex-shrink: 0; }
.hero-trust-sep { width: 1px; height: 16px; background: rgba(255,255,255,.18); margin-right: 22px; flex-shrink: 0; }

/* Stat bar at bottom of hero */
.hero-stat-bar { background: rgba(5,5,18,.92); backdrop-filter: blur(20px); border-top: 1px solid rgba(255,255,255,.07); display: flex; }
.hero-stat-item { flex: 1; padding: 22px; text-align: center; border-left: 1px solid rgba(255,255,255,.06); }
.hero-stat-item:first-child { border-left: none; }
.hero-stat-val { font-family: var(--font-mono); font-size: clamp(18px,2.2vw,26px); color: #fff; display: block; font-weight: 700; line-height: 1; margin-bottom: 5px; letter-spacing: -0.02em; }
.hero-stat-lbl { font-size: 10px; letter-spacing: 1.4px; text-transform: uppercase; color: rgba(255,255,255,.38); font-family: var(--font-mono); }
@media(max-width:600px) {
    .hero-stat-bar{flex-wrap:wrap}
    .hero-stat-item{flex:1 1 50%;border-top:1px solid rgba(255,255,255,.06)}
    .hero-stat-item:nth-child(odd){border-left:none}
}


/* ═══════════════════════════════════════════════════════════
   2. FILTER + SORT TOOLBAR
═══════════════════════════════════════════════════════════ */
.toolbar-section { background: var(--surface); border-bottom: 1px solid var(--border); position: sticky; top: 0; z-index: 200; box-shadow: var(--shadow); }
.toolbar-inner { display: flex; align-items: center; gap: 12px; padding: 14px 24px; max-width: 1180px; margin: 0 auto; flex-wrap: wrap; }

/* Category chips */
.cat-chips { display: flex; align-items: center; gap: 8px; overflow-x: auto; flex: 1; scrollbar-width: none; min-width: 0; }
.cat-chips::-webkit-scrollbar { display: none; }
.cat-chip { padding: 8px 18px; border-radius: 100px; font-size: 13px; font-weight: 500; cursor: pointer; border: 1.5px solid var(--border2); background: var(--surface2); color: var(--text2); font-family: var(--font); transition: all var(--transition); white-space: nowrap; flex-shrink: 0; }
.cat-chip:hover { border-color: rgba(99,102,241,.4); color: var(--accent); background: var(--accent-glow); }
.cat-chip.active { background: linear-gradient(135deg,var(--accent),var(--accent2)); color: #fff; border-color: transparent; box-shadow: 0 4px 14px rgba(99,102,241,.35); }

/* Sort + View toggle */
.toolbar-right { display: flex; align-items: center; gap: 10px; flex-shrink: 0; }
.sort-select { padding: 8px 14px; border-radius: var(--radius-sm); border: 1.5px solid var(--border2); background: var(--surface2); color: var(--text2); font-family: var(--font); font-size: 13px; font-weight: 500; cursor: pointer; outline: none; transition: border-color var(--transition); }
.sort-select:focus { border-color: var(--accent); }
.view-toggle { display: flex; gap: 4px; background: var(--surface2); border: 1.5px solid var(--border2); border-radius: var(--radius-sm); padding: 4px; }
.view-btn { width: 32px; height: 32px; border-radius: 7px; display: flex; align-items: center; justify-content: center; border: none; background: transparent; cursor: pointer; color: var(--text3); transition: all var(--transition); }
.view-btn.active { background: var(--accent); color: #fff; }
.view-btn svg { width: 15px; height: 15px; }

/* Filter button */
.filter-trigger-btn { display: flex; align-items: center; gap: 7px; padding: 8px 16px; border-radius: var(--radius-sm); border: 1.5px solid var(--border2); background: var(--surface2); color: var(--text2); font-family: var(--font); font-size: 13px; font-weight: 500; cursor: pointer; transition: all var(--transition); white-space: nowrap; flex-shrink: 0; }
.filter-trigger-btn:hover { border-color: var(--accent); color: var(--accent); background: var(--accent-glow); }
.filter-trigger-btn svg { width: 14px; height: 14px; }
.filter-trigger-btn .filter-badge { background: var(--accent); color: #fff; font-size: 10px; font-weight: 700; width: 17px; height: 17px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-family: var(--font-mono); }

/* Results count */
.results-count { font-size: 12.5px; color: var(--text3); font-family: var(--font-mono); white-space: nowrap; }


/* ═══════════════════════════════════════════════════════════
   3. FILTER MODAL
═══════════════════════════════════════════════════════════ */

/* Backdrop */
.filter-modal-backdrop {
    position: fixed; inset: 0;
    background: rgba(0,0,0,.38);
    z-index: 900;
    opacity: 0; pointer-events: none;
    transition: opacity .28s ease;
}
.filter-modal-backdrop.open { opacity: 1; pointer-events: all; }

/* Modal shell */
.filter-modal {
    position: fixed;
    top: 50%; left: 50%;
    transform: translate(-50%, -46%) scale(.96);
    z-index: 901;
    background: #fff;
    border-radius: 16px;
    width: min(460px, calc(100vw - 32px));
    display: flex;
    flex-direction: column;
    max-height: min(620px, calc(100vh - 80px));
    box-shadow: 0 20px 60px rgba(0,0,0,.22);
    opacity: 0; pointer-events: none;
    transition: transform .3s cubic-bezier(.4,0,.2,1), opacity .3s ease;
}
.filter-modal.open {
    transform: translate(-50%, -50%) scale(1);
    opacity: 1; pointer-events: all;
}

/* Header */
.fm-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 20px 22px 18px;
    border-bottom: 1px solid #f0f0f0;
    flex-shrink: 0;
}
.fm-title { font-size: 16px; font-weight: 700; color: #111; font-family: var(--font); }
.fm-close {
    width: 30px; height: 30px; border-radius: 50%;
    border: none; background: none;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; color: #555;
    transition: background var(--transition), color var(--transition);
}
.fm-close:hover { background: #f5f5f5; color: #111; }
.fm-close svg { width: 16px; height: 16px; }

/* Scrollable body */
.fm-body {
    flex: 1;
    overflow-y: auto;
    overscroll-behavior: contain;
    padding: 0;
    scrollbar-width: thin;
    scrollbar-color: #e5e7eb transparent;
}
.fm-body::-webkit-scrollbar { width: 3px; }
.fm-body::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 2px; }

/* ── FIX 1: Group label — was completely missing ── */
.fm-group-label {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .1em;
    text-transform: uppercase;
    color: var(--text3);
    font-family: var(--font-mono);
    padding: 18px 22px 8px;
    display: block;
}

/* ── FIX 2: Custom select — was completely missing ── */
.custom-select { position: relative; padding: 0 22px 16px; }

.cs-trigger {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 11px 14px;
    border: 1.5px solid var(--border2);
    border-radius: var(--radius-sm);
    background: var(--surface2);
    font-size: 13.5px;
    font-family: var(--font);
    color: var(--text);
    cursor: pointer;
    user-select: none;
    transition: border-color var(--transition);
}
.cs-trigger svg { width: 16px; height: 16px; color: var(--text3); flex-shrink: 0; transition: transform .22s ease; }
.cs-trigger.open { border-color: var(--accent); }
.cs-trigger.open svg { transform: rotate(180deg); }
.cs-trigger:hover { border-color: var(--accent); }

.cs-dropdown {
    display: none;
    position: absolute;
    left: 22px; right: 22px;
    top: 100%;
    background: #fff;
    border: 1.5px solid var(--border2);
    border-radius: var(--radius-sm);
    box-shadow: 0 8px 28px rgba(0,0,0,.12);
    z-index: 20;
    max-height: 200px;
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: #e5e7eb transparent;
}
.cs-dropdown.open { display: block; }
.cs-dropdown::-webkit-scrollbar { width: 3px; }
.cs-dropdown::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 2px; }

.cs-option {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 11px 14px;
    font-size: 13.5px;
    font-family: var(--font);
    color: var(--text2);
    cursor: pointer;
    transition: background .15s;
}
.cs-option:hover { background: #fafafa; }
.cs-option.selected { color: var(--accent); font-weight: 600; background: var(--accent-glow); }

.cs-option-check {
    width: 16px; height: 16px;
    border-radius: 50%;
    border: 1.5px solid #ddd;
    flex-shrink: 0;
    transition: all .15s;
    display: flex; align-items: center; justify-content: center;
}
.cs-option.selected .cs-option-check {
    background: var(--accent);
    border-color: var(--accent);
}
.cs-option.selected .cs-option-check::after {
    content: '';
    width: 6px; height: 6px;
    border-radius: 50%;
    background: #fff;
}

/* ── FIX 3: Type/funding chips — was completely missing ── */
.type-chips {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    padding: 0 22px 18px;
}
.type-chip {
    padding: 7px 16px;
    border-radius: 100px;
    font-size: 13px;
    font-weight: 500;
    font-family: var(--font);
    cursor: pointer;
    border: 1.5px solid var(--border2);
    background: var(--surface2);
    color: var(--text2);
    transition: all var(--transition);
    user-select: none;
    white-space: nowrap;
}
.type-chip:hover { border-color: rgba(99,102,241,.4); color: var(--accent); background: var(--accent-glow); }
.type-chip.selected {
    background: linear-gradient(135deg, var(--accent), var(--accent2));
    color: #fff;
    border-color: transparent;
    box-shadow: 0 3px 10px rgba(99,102,241,.35);
}

/* Section divider inside modal body */
.fm-section { border-bottom: 1px solid #f0f0f0; padding-bottom: 4px; }
.fm-section:last-child { border-bottom: none; }

/* Helper note text inside modal */
.fm-note {
    font-size: 11px;
    color: var(--text3);
    font-family: var(--font-mono);
    padding: 0 22px 14px;
    line-height: 1.5;
    display: block;
}

/* ── FIX 4 & 5: Footer layout + clear button — was missing display:flex and .fm-clear-btn ── */
.fm-footer {
    display: flex;
    gap: 10px;
    padding: 16px 22px 20px;
    flex-shrink: 0;
    background: #fff;
    border-top: 1px solid #f0f0f0;
}
.fm-clear-btn {
    flex: 1;
    padding: 15px;
    border-radius: 10px;
    border: 1.5px solid var(--border2);
    background: transparent;
    color: var(--text2);
    font-family: var(--font);
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    transition: border-color var(--transition), color var(--transition), background var(--transition);
}
.fm-clear-btn:hover { border-color: var(--accent); color: var(--accent); background: var(--accent-glow); }
.fm-apply-btn {
    flex: 2;
    padding: 15px;
    border-radius: 10px;
    border: none;
    background: linear-gradient(135deg, var(--accent), var(--accent2));
    color: #fff;
    font-family: var(--font);
    font-size: 15px;
    font-weight: 700;
    cursor: pointer;
    letter-spacing: .01em;
    transition: opacity .2s, transform .2s;
    box-shadow: 0 4px 16px rgba(99,102,241,.35);
}
.fm-apply-btn:hover { opacity: .92; transform: translateY(-1px); }

/* Active filter chips above results */
.active-filters { display: flex; flex-wrap: wrap; gap: 7px; margin-bottom: 16px; }
.active-filter-chip { display: inline-flex; align-items: center; gap: 6px; padding: 5px 12px; border-radius: 100px; background: var(--accent-glow); border: 1px solid rgba(99,102,241,.25); color: var(--accent); font-size: 12px; font-weight: 600; font-family: var(--font); }
.active-filter-chip button { background: none; border: none; cursor: pointer; color: var(--accent); display: flex; align-items: center; padding: 0; opacity: .7; }
.active-filter-chip button:hover { opacity: 1; }
.active-filter-chip button svg { width: 11px; height: 11px; }


/* ═══════════════════════════════════════════════════════════
   4. MAIN CONTENT AREA
═══════════════════════════════════════════════════════════ */
.campaigns-section { background: var(--bg); padding: 52px 0 80px; }
.campaigns-layout { display: grid; grid-template-columns: 260px 1fr; gap: 28px; }
@media(max-width:960px) { .campaigns-layout { grid-template-columns: 1fr; } }


/* ═══════════════════════════════════════════════════════════
   5. SIDEBAR FILTERS
═══════════════════════════════════════════════════════════ */
.sidebar { position: sticky; top: 70px; height: fit-content; display: flex; flex-direction: column; gap: 16px; }
@media(max-width:960px) { .sidebar { display: none; } }

.filter-card { background: var(--surface); border: 1px solid var(--border2); border-radius: var(--radius); padding: 22px; box-shadow: var(--shadow); }
.filter-card-title { font-size: 12px; font-weight: 700; color: var(--text3); letter-spacing: .1em; text-transform: uppercase; font-family: var(--font-mono); margin-bottom: 16px; display: flex; align-items: center; justify-content: space-between; }
.filter-card-clear { font-size: 11px; color: var(--accent); cursor: pointer; font-weight: 600; letter-spacing: 0; text-transform: none; font-family: var(--font); }
.filter-card-clear:hover { text-decoration: underline; }

.filter-range { width: 100%; accent-color: var(--accent); cursor: pointer; margin: 10px 0 6px; }
.filter-range-labels { display: flex; justify-content: space-between; font-size: 11.5px; color: var(--text3); font-family: var(--font-mono); }

.filter-checkbox { display: flex; align-items: center; gap: 10px; padding: 8px 0; cursor: pointer; }
.filter-checkbox input { width: 16px; height: 16px; accent-color: var(--accent); cursor: pointer; }
.filter-checkbox-label { font-size: 13.5px; color: var(--text2); transition: color var(--transition); flex: 1; }
.filter-checkbox:hover .filter-checkbox-label { color: var(--accent); }
.filter-checkbox-count { font-size: 11px; color: var(--text3); font-family: var(--font-mono); background: var(--surface2); padding: 2px 8px; border-radius: 100px; border: 1px solid var(--border2); }

.filter-divider { height: 1px; background: var(--border); margin: 4px 0; }

.sidebar-stat { display: flex; align-items: center; gap: 12px; padding: 12px 0; border-bottom: 1px solid var(--border); }
.sidebar-stat:last-child { border-bottom: none; }
.sidebar-stat-icon { width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.sidebar-stat-icon svg { width: 16px; height: 16px; }
.sidebar-stat-num { font-size: 16px; font-weight: 700; color: var(--text); font-family: var(--font-mono); line-height: 1; }
.sidebar-stat-lbl { font-size: 11px; color: var(--text3); margin-top: 1px; }


/* ═══════════════════════════════════════════════════════════
   6. CAMPAIGN GRID / LIST VIEW
═══════════════════════════════════════════════════════════ */
.camp-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 22px; }
.camp-grid.list-view { grid-template-columns: 1fr; gap: 16px; }

/* ── GRID CARD ── */
.camp-card { background: var(--surface); border-radius: var(--radius-lg); border: 1.5px solid var(--border2); overflow: hidden; transition: transform var(--transition), box-shadow var(--transition), border-color var(--transition); position: relative; display: flex; flex-direction: column; }
.camp-card:hover { transform: translateY(-6px); box-shadow: 0 24px 60px rgba(99,102,241,.13); border-color: rgba(99,102,241,.28); }

.camp-img { position: relative; height: 210px; overflow: hidden; flex-shrink: 0; }
.camp-img img { width: 100%; height: 100%; object-fit: cover; transition: transform .55s ease; }
.camp-card:hover .camp-img img { transform: scale(1.06); }

.camp-badge-wrap { position: absolute; top: 14px; left: 14px; right: 14px; display: flex; justify-content: space-between; align-items: flex-start; }
.camp-cat-badge { background: rgba(255,255,255,.93); backdrop-filter: blur(8px); color: #1e1b4b; font-size: 11.5px; font-weight: 600; padding: 5px 14px; border-radius: 100px; border: 1px solid rgba(99,102,241,.15); font-family: var(--font); }
.camp-verified-badge { background: #ecfdf5; color: #065f46; font-size: 11px; font-weight: 600; padding: 4px 12px; border-radius: 100px; border: 1px solid #a7f3d0; display: flex; align-items: center; gap: 5px; font-family: var(--font); }
.camp-verified-badge::before { content: ''; width: 6px; height: 6px; border-radius: 50%; background: #10b981; }

.camp-urgency { position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(to top, rgba(5,5,20,.85) 0%, transparent 100%); padding: 16px 14px 10px; display: flex; align-items: center; gap: 6px; }
.camp-urgency-dot { width: 6px; height: 6px; border-radius: 50%; background: var(--yellow); flex-shrink: 0; animation: pulse-live 2s ease infinite; }
.camp-urgency-text { font-size: 11px; color: rgba(255,255,255,.85); font-family: var(--font-mono); font-weight: 600; }

.camp-body { padding: 22px 22px 24px; display: flex; flex-direction: column; flex: 1; }
.camp-title { font-weight: 600; font-size: 15.5px; color: var(--text); margin-bottom: 6px; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; transition: color var(--transition); }
.camp-card:hover .camp-title { color: var(--accent); }
.camp-excerpt { font-size: 13px; color: var(--text3); line-height: 1.7; margin-bottom: 16px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; font-weight: 300; flex: 1; }

.camp-progress-wrap { margin-bottom: 12px; }
.camp-progress-track { height: 5px; background: var(--surface3); border-radius: 3px; overflow: hidden; margin-bottom: 8px; }
.camp-progress-fill { height: 100%; border-radius: 3px; background: linear-gradient(90deg, var(--accent), var(--accent2)); transition: width .9s cubic-bezier(.4,0,.2,1); }
.camp-meta-row { display: flex; justify-content: space-between; font-size: 12.5px; }
.camp-raised { color: var(--text); font-weight: 700; font-family: var(--font-mono); }
.camp-pct { color: var(--accent); font-weight: 700; font-family: var(--font-mono); }
.camp-goal { color: var(--text3); font-size: 11.5px; }

.camp-info-strip { display: flex; align-items: center; gap: 14px; margin-bottom: 18px; padding: 10px 5px; background: var(--surface2); border-radius: var(--radius-sm); border: 1px solid var(--border); }
.camp-info-item { display: flex; align-items: center; gap: 5px; font-size: 12px; color: var(--text3); font-family: var(--font-mono); }
.camp-info-item svg { width: 13px; height: 13px; color: var(--accent); flex-shrink: 0; }
.camp-info-sep { width: 1px; height: 14px; background: var(--border2); }

.camp-btn { display: flex; align-items: center; justify-content: center; gap: 8px; background: linear-gradient(135deg,var(--accent),var(--accent2)); color: #fff; padding: 13px; border-radius: var(--radius); font-weight: 600; font-size: 13.5px; transition: opacity var(--transition), transform var(--transition); box-shadow: 0 4px 14px rgba(99,102,241,.35); }
.camp-btn svg { width: 14px; height: 14px; flex-shrink: 0; transition: transform var(--transition); }
.camp-btn:hover { opacity: .9; transform: translateY(-2px); }
.camp-btn:hover svg { transform: translateX(2px); }

/* ── LIST CARD ── */
.camp-grid.list-view .camp-card { flex-direction: row; border-radius: var(--radius); }
.camp-grid.list-view .camp-img { width: 220px; height: auto; min-height: 160px; flex-shrink: 0; border-radius: 0; }
.camp-grid.list-view .camp-body { padding: 20px 24px; }
.camp-grid.list-view .camp-excerpt { -webkit-line-clamp: 2; }
@media(max-width:600px) { .camp-grid.list-view .camp-card { flex-direction: column; } .camp-grid.list-view .camp-img { width: 100%; height: 200px; } }

/* Empty state */
.empty-state { grid-column: 1/-1; text-align: center; padding: 80px 20px; }
.empty-icon { width: 80px; height: 80px; border-radius: 50%; background: var(--surface2); border: 1px solid var(--border2); display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; }
.empty-icon svg { width: 36px; height: 36px; color: var(--text3); }
.empty-title { font-family: var(--font-display); font-size: 22px; color: var(--text); margin-bottom: 8px; }
.empty-sub { font-size: 14px; color: var(--text3); font-weight: 300; }


/* ═══════════════════════════════════════════════════════════
   7. SPOTLIGHT CARD
═══════════════════════════════════════════════════════════ */
.spotlight-card { grid-column: 1 / -1; background: linear-gradient(135deg,var(--accent),var(--accent2)); border-radius: var(--radius-lg); overflow: hidden; display: grid; grid-template-columns: 1fr 360px; border: none; box-shadow: 0 16px 48px rgba(99,102,241,.4); }
@media(max-width:768px) { .spotlight-card { grid-template-columns: 1fr; } }
.camp-grid.list-view .spotlight-card { flex-direction: column; grid-template-columns: 1fr; }
.spotlight-body { padding: 40px; display: flex; flex-direction: column; justify-content: center; }
.spotlight-eyebrow { font-size: 10.5px; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; color: rgba(255,255,255,.65); font-family: var(--font-mono); margin-bottom: 14px; display: flex; align-items: center; gap: 8px; }
.spotlight-eyebrow::before { content: ''; width: 16px; height: 2px; background: rgba(255,255,255,.5); border-radius: 2px; }
.spotlight-title { font-family: var(--font-display); font-size: clamp(1.5rem,2.5vw,2rem); font-weight: 500; color: #fff; line-height: 1.2; margin-bottom: 14px; }
.spotlight-excerpt { font-size: 14px; color: rgba(255,255,255,.75); line-height: 1.8; margin-bottom: 24px; font-weight: 300; }
.spotlight-stats { display: flex; gap: 28px; margin-bottom: 28px; }
.spotlight-stat-val { font-family: var(--font-mono); font-size: 22px; font-weight: 700; color: #fff; display: block; line-height: 1; }
.spotlight-stat-lbl { font-size: 11px; color: rgba(255,255,255,.6); margin-top: 3px; font-family: var(--font-mono); }
.spotlight-progress-track { height: 6px; background: rgba(255,255,255,.2); border-radius: 3px; overflow: hidden; margin-bottom: 8px; }
.spotlight-progress-fill { height: 100%; border-radius: 3px; background: rgba(255,255,255,.8); }
.spotlight-btn { display: inline-flex; align-items: center; gap: 8px; background: #fff; color: var(--accent); padding: 13px 28px; border-radius: var(--radius); font-weight: 700; font-size: 14px; width: fit-content; transition: all var(--transition); }
.spotlight-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,.2); }
.spotlight-btn svg { width: 15px; height: 15px; transition: transform var(--transition); }
.spotlight-btn:hover svg { transform: translateX(3px); }
.spotlight-img { position: relative; overflow: hidden; min-height: 260px; }
.spotlight-img img { width: 100%; height: 100%; object-fit: cover; transition: transform .6s ease; }
.spotlight-card:hover .spotlight-img img { transform: scale(1.05); }


/* ═══════════════════════════════════════════════════════════
   8. PAGINATION
═══════════════════════════════════════════════════════════ */
.pagination-wrap { margin-top: 52px; display: flex; align-items: center; justify-content: center; gap: 8px; flex-wrap: wrap; }
.pagination-wrap .page-link, .pagination-wrap a, .pagination-wrap span {
    display: inline-flex; align-items: center; justify-content: center;
    min-width: 40px; height: 40px; padding: 0 14px;
    border-radius: var(--radius-sm); font-size: 13.5px; font-weight: 600;
    font-family: var(--font); transition: all var(--transition);
    border: 1.5px solid var(--border2); background: var(--surface); color: var(--text2);
    cursor: pointer;
}
.pagination-wrap a:hover { border-color: var(--accent); color: var(--accent); background: var(--accent-glow); }
.pagination-wrap .active span, .pagination-wrap span.active {
    background: linear-gradient(135deg,var(--accent),var(--accent2)); color: #fff; border-color: transparent;
    box-shadow: 0 4px 14px rgba(99,102,241,.35);
}
.pagination-wrap .disabled span, .pagination-wrap span[aria-disabled="true"] { opacity: .35; cursor: not-allowed; }


/* ═══════════════════════════════════════════════════════════
   9. MARQUEE
═══════════════════════════════════════════════════════════ */
.marquee-band { background: #07080f; overflow: hidden; border-top: 1px solid rgba(255,255,255,.04); border-bottom: 1px solid rgba(255,255,255,.04); }
.marquee-inner { display: flex; width: max-content; animation: marquee 28s linear infinite; }
.marquee-inner:hover { animation-play-state: paused; }
.marquee-row { display: flex; padding: 13px 0; }
.m-item { display: inline-flex; align-items: center; gap: 10px; padding: 0 36px; font-size: 11px; font-weight: 600; color: rgba(165,180,252,.6); letter-spacing: .12em; text-transform: uppercase; font-family: var(--font-mono); white-space: nowrap; }
.m-dot { width: 4px; height: 4px; border-radius: 50%; background: var(--accent2); flex-shrink: 0; }
@keyframes marquee { 0%{transform:translateX(0)} 100%{transform:translateX(-50%)} }


/* ═══════════════════════════════════════════════════════════
   10. CTA BANNER
═══════════════════════════════════════════════════════════ */
.cta-section { position: relative; overflow: hidden; padding: 100px 0; text-align: center; background: linear-gradient(160deg,#07080f 0%,#0d0e1a 50%,#13141f 100%); }
.cta-section::before { content: ''; position: absolute; top: 50%; left: 50%; transform: translate(-50%,-50%); width: 700px; height: 700px; border-radius: 50%; background: radial-gradient(circle,rgba(99,102,241,.12) 0%,transparent 70%); pointer-events: none; }
.cta-bg-img { position: absolute; inset: 0; z-index: 0; }
.cta-bg-img img { width: 100%; height: 100%; object-fit: cover; opacity: .15; }
.cta-inner { position: relative; z-index: 1; max-width: 600px; margin: 0 auto; padding: 0 24px; }
.cta-title { font-family: var(--font-display); font-size: clamp(2rem,4vw,3rem); font-weight: 700; color: #fff; margin-bottom: 14px; line-height: 1.1; }
.cta-title em { font-style: normal; color: #a5b4fc; }
.cta-sub { font-size: 15px; color: rgba(255,255,255,.52); font-weight: 300; line-height: 1.8; max-width: 460px; margin: 0 auto 32px; }
.cta-btns { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }

.scroll-top { position: fixed; bottom: 24px; right: 24px; width: 44px; height: 44px; border-radius: 50%; background: var(--accent); color: #fff; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; box-shadow: 0 6px 20px rgba(99,102,241,.45); opacity: 0; transform: translateY(16px); transition: all var(--transition); z-index: 999; }
.scroll-top.visible { opacity: 1; transform: translateY(0); }
.scroll-top:hover { transform: translateY(-2px); }
.scroll-top svg { width: 18px; height: 18px; }

/* ── Responsive ── */
@media(max-width:768px) {
    .hero-title { font-size: clamp(2.2rem,7vw,3rem); }
    .hero-search { max-width: 100%; }
    .toolbar-inner { padding: 12px 16px; gap: 8px; }
    .campaigns-section { padding: 32px 0 60px; }
    .camp-grid { grid-template-columns: 1fr; }
    .spotlight-card { grid-template-columns: 1fr; }
    .spotlight-body { padding: 28px 24px; }
    .results-count { display: none; }
}
@media(max-width:480px) {
    .container { padding: 0 16px; }
    .hero-content { padding: 80px 16px 80px; }
    .hero-trust { gap: 10px; }
}
</style>


{{-- ═══════════════════════════════════════════════════════════
     FILTER MODAL
═══════════════════════════════════════════════════════════ --}}
<div class="filter-modal-backdrop" id="filterBackdrop" onclick="closeFilterModal()"></div>
<div class="filter-modal" id="filterModal" role="dialog" aria-modal="true" aria-label="Filter campaigns">
    <div class="fm-header">
        <span class="fm-title">Filter Campaigns</span>
        <button class="fm-close" onclick="closeFilterModal()" aria-label="Close">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>

    <div class="fm-body">

        {{-- Location --}}
        <div class="fm-section">
            <span class="fm-group-label">Location</span>
            <div class="custom-select" id="locationSelect">
                <div class="cs-trigger" id="locationTrigger" onclick="toggleDropdown('locationDropdown','locationTrigger')">
                    <span id="locationLabel">All Locations</span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                </div>
                <div class="cs-dropdown" id="locationDropdown">
                    @php
                    $locations = [
                        'all'           => 'All Locations',
                        'pan_india'     => 'PAN INDIA',
                        'bengaluru'     => 'Bengaluru',
                        'chennai'       => 'Chennai',
                        'hyderabad'     => 'Hyderabad',
                        'kolkata'       => 'Kolkata',
                        'mumbai'        => 'Mumbai',
                        'new_delhi'     => 'New Delhi',
                        'agartala'      => 'Agartala',
                        'ahmedabad'     => 'Ahmedabad',
                        'bhopal'        => 'Bhopal',
                        'bhubaneswar'   => 'Bhubaneswar',
                        'chandigarh'    => 'Chandigarh',
                        'coimbatore'    => 'Coimbatore',
                        'guwahati'      => 'Guwahati',
                        'indore'        => 'Indore',
                        'jaipur'        => 'Jaipur',
                        'lucknow'       => 'Lucknow',
                        'nagpur'        => 'Nagpur',
                        'patna'         => 'Patna',
                        'pune'          => 'Pune',
                        'surat'         => 'Surat',
                        'vadodara'      => 'Vadodara',
                        'visakhapatnam' => 'Visakhapatnam',
                    ];
                    @endphp
                    @foreach($locations as $val => $label)
                    <div class="cs-option {{ request('location','all') === $val ? 'selected' : '' }}"
                         data-value="{{ $val }}"
                         onclick="selectOption('locationDropdown','locationTrigger','locationLabel','{{ $val }}','{{ $label }}','filterLocation')">
                        <div class="cs-option-check"></div>
                        {{ $label }}
                    </div>
                    @endforeach
                </div>
                <input type="hidden" name="location" id="filterLocation" value="{{ request('location','all') }}">
            </div>
            <span class="fm-note"> After deployment, location will be auto-fetched from Google Maps API</span>
        </div>

        {{-- Campaign Type --}}
        <div class="fm-section">
            <span class="fm-group-label">Campaign Type</span>
            <div class="custom-select" id="typeSelect">
                <div class="cs-trigger" id="typeTrigger" onclick="toggleDropdown('typeDropdown','typeTrigger')">
                    <span id="typeLabel">Active</span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                </div>
                <div class="cs-dropdown" id="typeDropdown">
                    @php
                    $campTypes = [
                        'all'            => 'All Types',
                        'active'         => 'Active',
                        'urgent'         => 'Urgent',
                        'newly_launched' => 'Newly Launched',
                        'closed'         => 'Closed',
                        'most_raised'    => 'Most Raised',
                    ];
                    @endphp
                    @foreach($campTypes as $val => $label)
                    <div class="cs-option {{ request('campaign_type','active') === $val ? 'selected' : '' }}"
                         data-value="{{ $val }}"
                         onclick="selectOption('typeDropdown','typeTrigger','typeLabel','{{ $val }}','{{ $label }}','filterCampaignType')">
                        <div class="cs-option-check"></div>
                        {{ $label }}
                    </div>
                    @endforeach
                </div>
                <input type="hidden" name="campaign_type" id="filterCampaignType" value="{{ request('campaign_type','active') }}">
            </div>
        </div>

        {{-- Funding Progress --}}
        <div class="fm-section">
            <span class="fm-group-label">Funding Progress</span>
            <div class="type-chips" id="fundingChips">
                @foreach(['any'=>'Any','lt25'=>'Under 25%','25to75'=>'25% – 75%','gt75'=>'75%+','100'=>'Fully Funded'] as $val => $label)
                <span class="type-chip {{ request('funding','any') === $val ? 'selected' : '' }}"
                      onclick="selectChip(this,'fundingChips','filterFunding','{{ $val }}')"
                      data-value="{{ $val }}">{{ $label }}</span>
                @endforeach
            </div>
            <input type="hidden" id="filterFunding" value="{{ request('funding','any') }}">
        </div>

        {{-- Category --}}
        <div class="fm-section">
            <span class="fm-group-label">Category</span>
            <div class="type-chips" id="categoryChips">
                <span class="type-chip {{ !request('category') ? 'selected' : '' }}"
                      onclick="selectChip(this,'categoryChips','filterCategory','')"
                      data-value="">All</span>
                @foreach($categories ?? [] as $cat)
                <span class="type-chip {{ request('category') === $cat->slug ? 'selected' : '' }}"
                      onclick="selectChip(this,'categoryChips','filterCategory','{{ $cat->slug }}')"
                      data-value="{{ $cat->slug }}">{{ $cat->name }}</span>
                @endforeach
            </div>
            <input type="hidden" id="filterCategory" value="{{ request('category','') }}">
        </div>

    </div>

    <div class="fm-footer">
        <button class="fm-clear-btn" onclick="clearAllFilters()">Clear All</button>
        <button class="fm-apply-btn" onclick="applyModalFilters()">Apply Filters</button>
    </div>
</div>


{{-- ═══════════════════════════════════════════════════════════
     1. HERO
═══════════════════════════════════════════════════════════ --}}
<div class="hero">
    <div class="hero-bg">
        <img src="{{ asset('images/about.jpg') }}" alt="All Campaigns" loading="eager">
    </div>
    <div class="hero-overlay"></div>
    <div class="hero-grid-lines"></div>

    <div class="hero-inner">
        <div class="hero-content">
            <div class="hero-pill">
                <span class="hero-pill-dot"></span>
                {{ $campaigns->total() ?? $campaigns->count() }}+ Verified Campaigns Live
            </div>

            <h1 class="hero-title">
                Browse &amp; Support<br><em>Every Cause</em>
            </h1>

            <p class="hero-desc">
                From medical emergencies to education and disaster relief — find a verified campaign that speaks to your heart and make every rupee count.
            </p>

            {{-- Search bar --}}
            <form method="GET" action="{{ url()->current() }}" class="hero-search">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                <input type="text" name="search" placeholder="Search campaigns, causes, NGOs…" value="{{ request('search') }}">
                @if(request('category'))<input type="hidden" name="category" value="{{ request('category') }}">@endif
                @if(request('sort'))<input type="hidden" name="sort" value="{{ request('sort') }}">@endif
                <button type="submit" class="hero-search-btn">Search</button>
            </form>

            <div class="hero-trust">
                <div class="hero-trust-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    All campaigns verified
                </div>
                <div class="hero-trust-sep"></div>
                <div class="hero-trust-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                    256-bit SSL secure
                </div>
                <div class="hero-trust-sep"></div>
                <div class="hero-trust-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    80G tax benefits
                </div>
                <div class="hero-trust-sep"></div>
                <div class="hero-trust-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                    Real-time tracking
                </div>
            </div>
        </div>

        {{-- Stat bar --}}
        <div class="hero-stat-bar">
            <div class="hero-stat-item">
                <span class="hero-stat-val">₹10 Cr+</span>
                <span class="hero-stat-lbl">Funds Raised</span>
            </div>
            <div class="hero-stat-item">
                <span class="hero-stat-val">50,000+</span>
                <span class="hero-stat-lbl">Donors</span>
            </div>
            <div class="hero-stat-item">
                <span class="hero-stat-val">2,000+</span>
                <span class="hero-stat-lbl">Campaigns</span>
            </div>
            <div class="hero-stat-item">
                <span class="hero-stat-val">98.7%</span>
                <span class="hero-stat-lbl">Success Rate</span>
            </div>
        </div>
    </div>
</div>


{{-- ═══ MARQUEE ═══ --}}
<div class="marquee-band">
    <div class="marquee-inner">
        <div class="marquee-row">
            @php $mItems = ['Medical · Education · Disaster · Animal · Child Welfare · Environment', 'Verified by DonateBazaar', 'RBI-Compliant Payments', '80G Tax Benefits', '24×7 Donor Support', '100% Transparent', 'Pan-India Coverage']; @endphp
            @for($r=0;$r<3;$r++)
                @foreach($mItems as $mi)
                    <span class="m-item"><span class="m-dot"></span>{{ $mi }}</span>
                @endforeach
            @endfor
        </div>
    </div>
</div>


{{-- ═══ STICKY FILTER TOOLBAR ═══ --}}
<div class="toolbar-section">
    <div class="toolbar-inner">
        {{-- Category chips --}}
        <div class="cat-chips">
            <a href="{{ url()->current() }}" class="cat-chip {{ !request('category') ? 'active' : '' }}">All</a>
            @foreach($categories ?? [] as $category)
                <a href="{{ url()->current() }}?category={{ $category->slug }}{{ request('search') ? '&search='.urlencode(request('search')) : '' }}{{ request('sort') ? '&sort='.request('sort') : '' }}"
                   class="cat-chip {{ request('category') === $category->slug ? 'active' : '' }}">
                    {{ $category->name }}
                    <span style="font-size:10px;opacity:.7;margin-left:3px">({{ $category->campaigns_count ?? 0 }})</span>
                </a>
            @endforeach
        </div>

        {{-- Sort + Filter + View --}}
        <div class="toolbar-right">
            <span class="results-count">{{ $campaigns->total() ?? $campaigns->count() }} campaigns</span>

            {{-- Filter button --}}
            <button class="filter-trigger-btn" onclick="openFilterModal()" id="filterTriggerBtn">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4h18M7 12h10M11 20h2"/></svg>
                Filters
                <span class="filter-badge" id="filterBadge" style="display:none">0</span>
            </button>

            <form method="GET" action="{{ url()->current() }}" id="sortForm">
                @if(request('search'))<input type="hidden" name="search" value="{{ request('search') }}">@endif
                @if(request('category'))<input type="hidden" name="category" value="{{ request('category') }}">@endif
                <select name="sort" class="sort-select" onchange="document.getElementById('sortForm').submit()">
                    <option value="newest"      {{ request('sort','newest') === 'newest'      ? 'selected' : '' }}>Newest First</option>
                    <option value="ending_soon" {{ request('sort') === 'ending_soon'          ? 'selected' : '' }}>Ending Soon</option>
                    <option value="most_funded" {{ request('sort') === 'most_funded'          ? 'selected' : '' }}>Most Funded</option>
                    <option value="most_donors" {{ request('sort') === 'most_donors'          ? 'selected' : '' }}>Most Donors</option>
                </select>
            </form>

            <div class="view-toggle">
                <button class="view-btn active" id="gridBtn" onclick="setView('grid')" title="Grid view">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
                </button>
                <button class="view-btn" id="listBtn" onclick="setView('list')" title="List view">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
                </button>
            </div>
        </div>
    </div>
</div>


{{-- ═══ MAIN CONTENT ═══ --}}
<section class="campaigns-section">
    <div class="container">
        <div class="campaigns-layout">

            {{-- ── SIDEBAR ── --}}
            <aside class="sidebar">

                {{-- Quick Stats --}}
                <div class="filter-card">
                    <div class="filter-card-title">Platform Stats</div>
                    <div class="sidebar-stat">
                        <div class="sidebar-stat-icon" style="background:rgba(99,102,241,.1)">
                            <svg viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
                        </div>
                        <div>
                            <div class="sidebar-stat-num">2,000+</div>
                            <div class="sidebar-stat-lbl">Active Campaigns</div>
                        </div>
                    </div>
                    <div class="sidebar-stat">
                        <div class="sidebar-stat-icon" style="background:rgba(16,185,129,.1)">
                            <svg viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
                        </div>
                        <div>
                            <div class="sidebar-stat-num">50,000+</div>
                            <div class="sidebar-stat-lbl">Generous Donors</div>
                        </div>
                    </div>
                    <div class="sidebar-stat">
                        <div class="sidebar-stat-icon" style="background:rgba(245,158,11,.1)">
                            <svg viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/></svg>
                        </div>
                        <div>
                            <div class="sidebar-stat-num">₹10 Cr+</div>
                            <div class="sidebar-stat-lbl">Total Raised</div>
                        </div>
                    </div>
                </div>

                {{-- Funding progress filter --}}
                <div class="filter-card">
                    <div class="filter-card-title">
                        Funding Progress
                        <span class="filter-card-clear" onclick="clearFundingFilter()">Clear</span>
                    </div>
                    <label class="filter-checkbox">
                        <input type="checkbox" name="funding" value="any" {{ !request('funding') || request('funding') === 'any' ? 'checked' : '' }} onchange="applySidebarFilters()">
                        <span class="filter-checkbox-label">Any progress</span>
                        <span class="filter-checkbox-count">{{ $campaigns->total() ?? '' }}</span>
                    </label>
                    <div class="filter-divider"></div>
                    <label class="filter-checkbox">
                        <input type="checkbox" name="funding" value="lt25" {{ request('funding') === 'lt25' ? 'checked' : '' }} onchange="applySidebarFilters()">
                        <span class="filter-checkbox-label">Under 25% funded</span>
                    </label>
                    <label class="filter-checkbox">
                        <input type="checkbox" name="funding" value="25to75" {{ request('funding') === '25to75' ? 'checked' : '' }} onchange="applySidebarFilters()">
                        <span class="filter-checkbox-label">25% – 75% funded</span>
                    </label>
                    <label class="filter-checkbox">
                        <input type="checkbox" name="funding" value="gt75" {{ request('funding') === 'gt75' ? 'checked' : '' }} onchange="applySidebarFilters()">
                        <span class="filter-checkbox-label">75%+ funded</span>
                    </label>
                    <label class="filter-checkbox">
                        <input type="checkbox" name="funding" value="100" {{ request('funding') === '100' ? 'checked' : '' }} onchange="applySidebarFilters()">
                        <span class="filter-checkbox-label">Fully funded</span>
                    </label>
                </div>

                {{-- Category filter --}}
                <div class="filter-card">
                    <div class="filter-card-title">Categories</div>
                    @foreach($categories ?? [] as $cat)
                    <label class="filter-checkbox">
                        <input type="checkbox" name="cat_sidebar" value="{{ $cat->slug }}" onchange="applySidebarFilters()" {{ request('category') === $cat->slug ? 'checked' : '' }}>
                        <span class="filter-checkbox-label">{{ $cat->name }}</span>
                        <span class="filter-checkbox-count">{{ $cat->campaigns_count ?? '' }}</span>
                    </label>
                    @endforeach
                </div>

                {{-- Trust badge --}}
                <div class="filter-card" style="background:linear-gradient(135deg,rgba(99,102,241,.06),rgba(139,92,246,.06));border-color:rgba(99,102,241,.18)">
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px">
                        <div style="width:36px;height:36px;border-radius:10px;background:rgba(99,102,241,.12);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        </div>
                        <div style="font-size:13px;font-weight:700;color:var(--text)">Donor Protection</div>
                    </div>
                    <p style="font-size:12.5px;color:var(--text2);line-height:1.7;font-weight:300">Every campaign on DonateBazaar is verified. If a campaign is found fraudulent, our Donor Protection Fund covers your refund.</p>
                    <a href="{{ url('/about') }}" style="display:inline-flex;align-items:center;gap:5px;font-size:12px;color:var(--accent);font-weight:600;margin-top:12px">
                        Learn more
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </a>
                </div>

            </aside>

            {{-- ── CAMPAIGN GRID ── --}}
            <div>

                {{-- Active filter chips --}}
                <div class="active-filters" id="activeFilters"></div>

                <div class="camp-grid reveal" id="campGrid">

@forelse($campaigns as $index => $campaign)

    @php
        $isExpired =
            $campaign->end_date &&
            \Carbon\Carbon::parse($campaign->end_date)->isPast();
    @endphp

    @if($isExpired)
        @continue
    @endif

    @php
        $raised = $campaign->donations_sum_amount ?? $campaign->raised_amount ?? 0;
        $goal        = $campaign->goal_amount ?? 0;
        $percentage  = $goal > 0 ? min(100, round(($raised / $goal) * 100)) : 0;
        $donors      = $campaign->donations->count() ?? 0;
        $daysLeft    = isset($campaign->end_date)
            ? max(0, now()->diffInDays($campaign->end_date, false))
            : null;

        $isSpotlight = $index === 0 && !request('search') && !request('category');
        $categorySlug = $campaign->category->slug ?? 'general';
    @endphp

                    @if($isSpotlight)
                    {{-- ═══ SPOTLIGHT CARD ═══ --}}
                    <div class="spotlight-card reveal">
                        <div class="spotlight-body">
                            <div class="spotlight-eyebrow">Featured Campaign</div>
                            <div class="spotlight-title">{{ $campaign->title }}</div>
                            <div class="spotlight-excerpt">{{ Str::limit(strip_tags($campaign->description), 160) }}</div>
                            <div class="spotlight-stats">
                                <div>
                                    <span class="spotlight-stat-val">₹{{ number_format($raised) }}</span>
                                    <div class="spotlight-stat-lbl">Raised so far</div>
                                </div>
                                <div>
                                    <span class="spotlight-stat-val">{{ number_format($donors) }}</span>
                                    <div class="spotlight-stat-lbl">Donors</div>
                                </div>
                                @if($daysLeft !== null)
                                <div>
                                    <span class="spotlight-stat-val">{{ $daysLeft }}</span>
                                    <div class="spotlight-stat-lbl">Days left</div>
                                </div>
                                @endif
                            </div>
                            <div class="spotlight-progress-track">
                                <div class="spotlight-progress-fill" style="width:{{ $percentage }}%"></div>
                            </div>
                            <div style="font-size:12px;color:rgba(255,255,255,.6);font-family:var(--font-mono);margin-bottom:24px">
                                {{ $percentage }}% of ₹{{ number_format($goal) }} goal
                            </div>
                            <a href="{{ route('campaign.public', [$categorySlug, $campaign->slug]) }}" class="spotlight-btn">
                                Donate Now
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                        <div class="spotlight-img">
                            <img src="{{ asset('storage/' . $campaign->cover_image) }}" alt="{{ $campaign->title }}" loading="lazy">
                        </div>
                    </div>
                    @else
                    {{-- ═══ REGULAR CARD ═══ --}}
                    <div class="camp-card reveal d{{ ($index % 3) + 1 }}"
                         data-cat="{{ $campaign->category->slug ?? 'uncategorized' }}"
                         data-pct="{{ $percentage }}">
                        <div class="camp-img">
                            <img loading="lazy" src="{{ asset('storage/' . $campaign->cover_image) }}" alt="{{ $campaign->title }}">
                            <div class="camp-badge-wrap">
                                <span class="camp-cat-badge">{{ $campaign->category->name ?? 'General' }}</span>
                                <span class="camp-verified-badge">Verified</span>
                            </div>
                            @if($daysLeft !== null && $daysLeft <= 7)
                            <div class="camp-urgency">
                                <span class="camp-urgency-dot"></span>
                                <span class="camp-urgency-text">{{ $daysLeft === 0 ? 'Last day!' : $daysLeft . ' days left' }}</span>
                            </div>
                            @endif
                        </div>
                        <div class="camp-body">
                            <h3 class="camp-title">{{ $campaign->title }}</h3>
                            <p class="camp-excerpt">{{ Str::limit(strip_tags($campaign->description), 100) }}</p>

                            <div class="camp-progress-wrap">
                                <div class="camp-progress-track">
                                    <div class="camp-progress-fill" style="width:{{ $percentage }}%"></div>
                                </div>
                                <div class="camp-meta-row">
                                    <span class="camp-raised">₹{{ number_format($raised) }}</span>
                                    <span class="camp-pct">{{ $percentage }}%</span>
                                </div>
                                <div class="camp-goal">of ₹{{ number_format($goal) }} goal</div>
                            </div>

                            <div class="camp-info-strip">
                                <div class="camp-info-item">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                                    {{ number_format($donors) }} donors
                                </div>
                                <div class="camp-info-sep"></div>
                                @if($daysLeft !== null)
                                <div class="camp-info-item">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                    {{ $daysLeft > 0 ? $daysLeft.'d left' : 'Ends today' }}
                                </div>
                                <div class="camp-info-sep"></div>
                                @endif
                                <div class="camp-info-item">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                    Verified
                                </div>
                            </div>

                            <a href="{{ route('campaign.public', [$categorySlug, $campaign->slug]) }}" class="camp-btn">
                                Donate Now
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    </div>
                    @endif

                    @empty
                    <div class="empty-state">
                        <div class="empty-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
                        </div>
                        <div class="empty-title">No campaigns found</div>
                        <p class="empty-sub">Try a different search term or browse all categories.</p>
                        <a href="{{ url()->current() }}" class="btn btn-accent" style="margin:24px auto 0;width:fit-content">
                            View All Campaigns
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                    @endforelse

                </div>

                {{-- PAGINATION --}}
 @if ($campaigns->hasPages())
    <div class="pagination-wrap">

        {{-- Previous --}}
        @if ($campaigns->onFirstPage())
            <span class="page-btn disabled">‹</span>
        @else
            <a href="{{ $campaigns->previousPageUrl() }}" class="page-btn">‹</a>
        @endif

        {{-- Page Numbers --}}
        @foreach ($campaigns->links()->elements[0] ?? [] as $page => $url)
            @if ($page == $campaigns->currentPage())
                <span class="page-btn active">{{ $page }}</span>
            @else
                <a href="{{ $url }}" class="page-btn">{{ $page }}</a>
            @endif
        @endforeach

        {{-- Next --}}
        @if ($campaigns->hasMorePages())
            <a href="{{ $campaigns->nextPageUrl() }}" class="page-btn">›</a>
        @else
            <span class="page-btn disabled">›</span>
        @endif

    </div>
@endif
            </div>

        </div>
    </div>
</section>


{{-- ═══ CTA BANNER ═══ --}}
<section class="cta-section">
    <div class="cta-bg-img"><img src="{{ asset('images/banner2.jpeg') }}" alt=""></div>
    <div class="cta-inner">
        <div class="eyebrow reveal" style="justify-content:center;color:#a5b4fc">Want to raise funds?</div>
        <h2 class="cta-title reveal d1">Start Your Own <em>Campaign</em></h2>
        <p class="cta-sub reveal d2">Medical emergency, education, disaster relief — whatever the cause, our team verifies and supports your fundraiser from day one.</p>
        <div class="cta-btns reveal d3">
            <a href="/campaign/create" class="btn btn-accent" style="font-size:15px;padding:15px 34px">
                Start Fundraiser
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 4v16m8-8H4"/></svg>
            </a>
            <a href="{{ url('/about') }}" class="btn btn-white" style="font-size:15px;padding:15px 34px">
                How It Works
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>
</section>

<button class="scroll-top" id="scrollTopBtn" onclick="window.scrollTo({top:0,behavior:'smooth'})" aria-label="Scroll to top">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 15l-6-6-6 6"/></svg>
</button>


<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ── SCROLL REVEAL ── */
    var revealEls = document.querySelectorAll('.reveal');
    var obs = new IntersectionObserver(function(entries){
        entries.forEach(function(e){
            if(e.isIntersecting){ e.target.classList.add('visible'); obs.unobserve(e.target); }
        });
    },{ threshold: 0.08, rootMargin: '0px 0px -30px 0px' });
    revealEls.forEach(function(el){ obs.observe(el); });

    /* ── SCROLL TO TOP ── */
    var sBtn = document.getElementById('scrollTopBtn');
    window.addEventListener('scroll', function(){ sBtn.classList.toggle('visible', window.scrollY > 600); },{ passive: true });

    /* ── PROGRESS FILL ANIMATION ── */
    var fills = document.querySelectorAll('.camp-progress-fill, .spotlight-progress-fill');
    var fObs = new IntersectionObserver(function(entries){
        entries.forEach(function(e){
            if(e.isIntersecting){ e.target.style.width = e.target.getAttribute('data-w') || e.target.style.width; }
        });
    },{ threshold: 0.3 });
    fills.forEach(function(el){
        el.setAttribute('data-w', el.style.width);
        el.style.width = '0%';
        setTimeout(function(){ fObs.observe(el); }, 100);
    });

    /* ── VIEW TOGGLE persistence ── */
    var savedView = localStorage.getItem('campView') || 'grid';
    applyView(savedView);

    /* ── Close dropdowns on outside click ── */
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.custom-select')) {
            document.querySelectorAll('.cs-dropdown.open').forEach(function(d){ d.classList.remove('open'); });
            document.querySelectorAll('.cs-trigger.open').forEach(function(t){ t.classList.remove('open'); });
        }
    });

    /* ── Sync modal dropdowns to current URL params on load ── */
    var params = new URLSearchParams(window.location.search);

    var locVal = params.get('location') || 'all';
    var locOption = document.querySelector('#locationDropdown .cs-option[data-value="' + locVal + '"]');
    if (locOption) document.getElementById('locationLabel').textContent = locOption.textContent.trim();

    var typeVal = params.get('campaign_type') || 'active';
    var typeOption = document.querySelector('#typeDropdown .cs-option[data-value="' + typeVal + '"]');
    if (typeOption) document.getElementById('typeLabel').textContent = typeOption.textContent.trim();

    /* ── Update filter badge on load ── */
    updateFilterBadge();
    renderActiveFilterChips();
});

/* ══════════════════════
   VIEW TOGGLE
══════════════════════ */
function setView(v){
    localStorage.setItem('campView', v);
    applyView(v);
}
function applyView(v){
    var grid = document.getElementById('campGrid');
    var gBtn = document.getElementById('gridBtn');
    var lBtn = document.getElementById('listBtn');
    if(!grid) return;
    if(v === 'list'){
        grid.classList.add('list-view');
        if(lBtn) lBtn.classList.add('active');
        if(gBtn) gBtn.classList.remove('active');
    } else {
        grid.classList.remove('list-view');
        if(gBtn) gBtn.classList.add('active');
        if(lBtn) lBtn.classList.remove('active');
    }
}

/* ══════════════════════
   FILTER MODAL
══════════════════════ */
function openFilterModal() {
    document.getElementById('filterModal').classList.add('open');
    document.getElementById('filterBackdrop').classList.add('open');
    document.body.style.overflow = 'hidden';
}
function closeFilterModal() {
    document.getElementById('filterModal').classList.remove('open');
    document.getElementById('filterBackdrop').classList.remove('open');
    document.body.style.overflow = '';
}

/* ── FIX: Custom dropdown toggle ── */
function toggleDropdown(dropdownId, triggerId) {
    var dropdown = document.getElementById(dropdownId);
    var trigger  = document.getElementById(triggerId);
    var isOpen   = dropdown.classList.contains('open');
    /* Close all first */
    document.querySelectorAll('.cs-dropdown.open').forEach(function(d){ d.classList.remove('open'); });
    document.querySelectorAll('.cs-trigger.open').forEach(function(t){ t.classList.remove('open'); });
    if (!isOpen) {
        dropdown.classList.add('open');
        trigger.classList.add('open');
    }
}

/* ── FIX: Use data-value for reliable selected-state detection ── */
function selectOption(dropdownId, triggerId, labelId, value, label, hiddenId) {
    document.getElementById(labelId).textContent = label;
    document.getElementById(hiddenId).value = value;
    document.querySelectorAll('#' + dropdownId + ' .cs-option').forEach(function(o){
        o.classList.toggle('selected', o.dataset.value === value);
    });
    document.getElementById(dropdownId).classList.remove('open');
    document.getElementById(triggerId).classList.remove('open');
}

/* ── Chip selection (single-select within a group) ── */
function selectChip(el, groupId, hiddenId, value) {
    document.querySelectorAll('#' + groupId + ' .type-chip').forEach(function(c){ c.classList.remove('selected'); });
    el.classList.add('selected');
    document.getElementById(hiddenId).value = value;
}

/* ── Apply modal filters — build query string and navigate ── */
function applyModalFilters() {
    var params = new URLSearchParams(window.location.search);

    var location = document.getElementById('filterLocation').value;
    var campType = document.getElementById('filterCampaignType').value;
    var funding  = document.getElementById('filterFunding').value;
    var category = document.getElementById('filterCategory').value;

    if (location && location !== 'all')  params.set('location', location);      else params.delete('location');
    if (campType && campType !== 'all')  params.set('campaign_type', campType); else params.delete('campaign_type');
    if (funding  && funding !== 'any')   params.set('funding', funding);        else params.delete('funding');
    if (category)                        params.set('category', category);      else params.delete('category');

    params.delete('page');
    closeFilterModal();
    window.location.href = window.location.pathname + '?' + params.toString();
}

/* ── Clear all modal filters ── */
function clearAllFilters() {
    /* Reset location dropdown */
    document.getElementById('locationLabel').textContent = 'All Locations';
    document.getElementById('filterLocation').value = 'all';
    document.querySelectorAll('#locationDropdown .cs-option').forEach(function(o){
        o.classList.toggle('selected', o.dataset.value === 'all');
    });

    /* Reset type dropdown */
    document.getElementById('typeLabel').textContent = 'All Types';
    document.getElementById('filterCampaignType').value = 'all';
    document.querySelectorAll('#typeDropdown .cs-option').forEach(function(o){
        o.classList.toggle('selected', o.dataset.value === 'all');
    });

    /* Reset funding chips */
    document.querySelectorAll('#fundingChips .type-chip').forEach(function(c){
        c.classList.toggle('selected', c.dataset.value === 'any');
    });
    document.getElementById('filterFunding').value = 'any';

    /* Reset category chips */
    document.querySelectorAll('#categoryChips .type-chip').forEach(function(c){
        c.classList.toggle('selected', c.dataset.value === '');
    });
    document.getElementById('filterCategory').value = '';
}

/* ── Count active filters and update badge ── */
function updateFilterBadge() {
    var params = new URLSearchParams(window.location.search);
    var count = 0;
    ['location','campaign_type','funding','category'].forEach(function(k){
        var v = params.get(k);
        if (v && v !== 'all' && v !== 'any' && v !== '') count++;
    });
    var badge = document.getElementById('filterBadge');
    if (count > 0) {
        badge.textContent = count;
        badge.style.display = 'inline-flex';
    } else {
        badge.style.display = 'none';
    }
}

/* ── Render active filter chips above grid ── */
function renderActiveFilterChips() {
    var params = new URLSearchParams(window.location.search);
    var wrap = document.getElementById('activeFilters');
    if (!wrap) return;
    wrap.innerHTML = '';

    var labels = {
        location:      { label: 'Location', values: { pan_india:'PAN India', bengaluru:'Bengaluru', chennai:'Chennai', hyderabad:'Hyderabad', kolkata:'Kolkata', mumbai:'Mumbai', new_delhi:'New Delhi', agartala:'Agartala', ahmedabad:'Ahmedabad', bhopal:'Bhopal', bhubaneswar:'Bhubaneswar', chandigarh:'Chandigarh', coimbatore:'Coimbatore', guwahati:'Guwahati', indore:'Indore', jaipur:'Jaipur', lucknow:'Lucknow', nagpur:'Nagpur', patna:'Patna', pune:'Pune', surat:'Surat', vadodara:'Vadodara', visakhapatnam:'Visakhapatnam' } },
        campaign_type: { label: 'Type',     values: { active:'Active', urgent:'Urgent', newly_launched:'Newly Launched', closed:'Closed', most_raised:'Most Raised' } },
        funding:       { label: 'Funding',  values: { lt25:'Under 25%', '25to75':'25–75%', gt75:'75%+', '100':'Fully Funded' } },
    };

    ['location','campaign_type','funding','category'].forEach(function(key){
        var val = params.get(key);
        if (!val || val === 'all' || val === 'any' || val === '') return;
        var displayVal = (labels[key] && labels[key].values[val]) ? labels[key].values[val] : val;
        var displayKey = labels[key] ? labels[key].label : key;
        var chip = document.createElement('span');
        chip.className = 'active-filter-chip';
        chip.innerHTML = displayKey + ': <strong>' + displayVal + '</strong>' +
            '<button onclick="removeFilter(\'' + key + '\')" aria-label="Remove ' + displayKey + ' filter">' +
            '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 18L18 6M6 6l12 12"/></svg></button>';
        wrap.appendChild(chip);
    });

    /* Search chip */
    var s = params.get('search');
    if (s) {
        var chip = document.createElement('span');
        chip.className = 'active-filter-chip';
        chip.innerHTML = 'Search: <strong>' + s + '</strong>' +
            '<button onclick="removeFilter(\'search\')" aria-label="Remove search filter">' +
            '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 18L18 6M6 6l12 12"/></svg></button>';
        wrap.appendChild(chip);
    }
}

function removeFilter(key) {
    var params = new URLSearchParams(window.location.search);
    params.delete(key);
    params.delete('page');
    window.location.href = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
}

/* ── FIX: Sidebar filters now actually navigate ── */
function applySidebarFilters() {
    var params = new URLSearchParams(window.location.search);

    /* Funding — radio-like: only the last checked wins; "any" clears it */
    var checkedFunding = document.querySelector('input[name="funding"]:checked');
    if (checkedFunding && checkedFunding.value !== 'any') {
        params.set('funding', checkedFunding.value);
    } else {
        params.delete('funding');
    }

    /* Category — first checked wins */
    var checkedCat = document.querySelector('input[name="cat_sidebar"]:checked');
    if (checkedCat) {
        params.set('category', checkedCat.value);
    } else {
        params.delete('category');
    }

    params.delete('page');
    window.location.href = window.location.pathname + '?' + params.toString();
}

function clearFundingFilter() {
    document.querySelectorAll('input[name="funding"]').forEach(function(c){
        c.checked = c.value === 'any';
    });
    applySidebarFilters();
}

/* ── Close modal on Escape ── */
document.addEventListener('keydown', function(e){
    if (e.key === 'Escape') closeFilterModal();
});
</script>

@endsection


