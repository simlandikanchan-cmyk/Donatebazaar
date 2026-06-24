@extends('layouts.app')

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300&family=DM+Mono:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,700;1,700&display=swap" rel="stylesheet">

<style>
/* ═══════════════════════════════════════════════
   DESIGN TOKENS — Purple/Indigo Theme
═══════════════════════════════════════════════ */
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
    --accent:       #6e56f7;
    --accent2:      #9b6dff;
    --accent-glow:  rgba(110,86,247,0.18);
    --accent-lt:    rgba(110,86,247,0.10);
    --green:        #10b981;
    --yellow:       #f59e0b;
    --blue:         #3b82f6;
    --orange:       #f97316;
    --font:         'DM Sans', sans-serif;
    --font-display: 'DM Mono', serif;
    --font-mono:    'DM Mono', monospace;
    --radius:       16px;
    --radius-sm:    10px;
    --radius-lg:    24px;
    --shadow:       0 1px 3px rgba(0,0,0,0.06), 0 4px 16px rgba(0,0,0,0.04);
    --shadow-md:    0 4px 24px rgba(0,0,0,0.08);
    --shadow-lg:    0 8px 40px rgba(0,0,0,0.12);
    --transition:   0.25s cubic-bezier(0.4,0,0.2,1);

    /* Purple-specific */
    --p1: #6e56f7;   /* primary purple */
    --p2: #9b6dff;   /* lighter purple */
    --p3: #b8a9ff;   /* soft purple text */
    --p-glow: rgba(110,86,247,0.22);
    --p-lt:   rgba(110,86,247,0.12);
    --p-mid:  rgba(110,86,247,0.28);
}

*,*::before,*::after { box-sizing:border-box; margin:0; padding:0; }
html { scroll-behavior:smooth; }
body { font-family:var(--font); color:var(--text); background:var(--bg); -webkit-font-smoothing:antialiased; overflow-x:hidden; }
img  { max-width:100%; display:block; }
a    { text-decoration:none; color:inherit; }

.container { max-width:1180px; margin:0 auto; padding:0 24px; }
@media(max-width:480px){ .container { padding:0 16px; } }

/* ── Typography ── */
.eyebrow { font-size:11px; font-weight:600; letter-spacing:.14em; text-transform:uppercase; color:var(--p1); font-family:var(--font-mono); display:inline-flex; align-items:center; gap:8px; margin-bottom:14px; }
.eyebrow::before { content:''; width:20px; height:2px; background:var(--p1); border-radius:2px; flex-shrink:0; }
.section-title { font-family:var(--font-display); font-size:clamp(1.9rem,3.2vw,2.6rem); font-weight:600; line-height:1.15; color:var(--text); margin-bottom:14px; }
.section-title em { font-style:normal; color:var(--p1); }

/* ── Buttons ── */
.btn { display:inline-flex; align-items:center; gap:8px; padding:13px 28px; border-radius:var(--radius); font-weight:600; font-size:14px; font-family:var(--font); transition:all var(--transition); border:none; cursor:pointer; white-space:nowrap; }
.btn svg { width:16px; height:16px; flex-shrink:0; transition:transform var(--transition); }
.btn:hover svg { transform:translateX(3px); }
.btn-accent  { background:linear-gradient(135deg,var(--p1),var(--p2)); color:#fff; box-shadow:0 6px 24px rgba(110,86,247,.40); }
.btn-accent:hover  { transform:translateY(-2px); box-shadow:0 12px 32px rgba(110,86,247,.55); }
.btn-purple  { background:linear-gradient(135deg,var(--p1),var(--p2)); color:#fff; box-shadow:0 6px 24px rgba(110,86,247,.40); }
.btn-purple:hover  { transform:translateY(-2px); box-shadow:0 12px 32px rgba(110,86,247,.55); }
.btn-white   { background:#fff; color:#1e1b4b; box-shadow:0 4px 20px rgba(0,0,0,.12); }
.btn-white:hover   { transform:translateY(-2px); box-shadow:0 12px 32px rgba(0,0,0,.18); }
.btn-outline { background:transparent; color:var(--text2); border:1.5px solid var(--border2); }
.btn-outline:hover { border-color:var(--p1); color:var(--p1); background:var(--accent-glow); }

/* ── Reveal ── */
.reveal      { opacity:0; transform:translateY(32px);  transition:opacity .7s ease, transform .7s ease; }
.reveal-left { opacity:0; transform:translateX(-32px); transition:opacity .7s ease, transform .7s ease; }
.reveal-right{ opacity:0; transform:translateX(32px);  transition:opacity .7s ease, transform .7s ease; }
.reveal.visible,.reveal-left.visible,.reveal-right.visible { opacity:1; transform:none; }
.d1{transition-delay:.1s}.d2{transition-delay:.2s}.d3{transition-delay:.3s}.d4{transition-delay:.4s}.d5{transition-delay:.5s}.d6{transition-delay:.6s}


/* ═══════════════════════════════════════════════
   1. HERO
═══════════════════════════════════════════════ */
.ddrf-alert-banner {
    background: linear-gradient(90deg, var(--p1), var(--p2));
    color: #fff;
    text-align: center;
    padding: 11px 24px;
    font-family: var(--font-mono);
    font-size: 12px;
    font-weight: 600;
    letter-spacing: .08em;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    position: relative;
    z-index: 200;
}
.alert-pulse { width: 8px; height: 8px; border-radius: 50%; background: #fff; flex-shrink: 0; animation: pulse-dot 1.5s ease infinite; }
@keyframes pulse-dot { 0%,100%{opacity:1;box-shadow:0 0 0 0 rgba(255,255,255,.5)}50%{box-shadow:0 0 0 6px rgba(255,255,255,0)} }

.ddrf-hero {
    position: relative; width: 100%; min-height: 84vh;
    overflow: hidden; display: flex; flex-direction: column;
}
.ddrf-hero-bg { position: absolute; inset: 0; z-index: 0; }
.ddrf-hero-bg img { width: 100%; height: 100%; object-fit: cover; object-position: center 40%; }
.ddrf-hero-overlay {
    position: absolute; inset: 0; z-index: 1;
    background: linear-gradient(110deg, rgba(4,3,20,.97) 0%, rgba(8,5,20,.90) 50%, rgba(14,8,30,.78) 100%);
}
.ddrf-hero-grid {
    position: absolute; inset: 0; z-index: 1;
    background-image: linear-gradient(rgba(110,86,247,.06) 1px, transparent 1px),
                      linear-gradient(90deg, rgba(110,86,247,.06) 1px, transparent 1px);
    background-size: 60px 60px; pointer-events: none;
}
/* Radar rings */
.ddrf-radar {
    position: absolute; top: 50%; right: -4%;
    transform: translateY(-50%);
    width: 580px; height: 580px; z-index: 1; pointer-events: none; opacity: .10;
}
.radar-ring {
    position: absolute; border-radius: 50%;
    border: 1.5px solid rgba(110,86,247,.8);
    top: 50%; left: 50%; transform: translate(-50%,-50%);
    animation: radar-ping 3.5s ease-in-out infinite;
}
.radar-ring:nth-child(1){ width:120px;height:120px;animation-delay:0s; }
.radar-ring:nth-child(2){ width:240px;height:240px;animation-delay:.6s; }
.radar-ring:nth-child(3){ width:360px;height:360px;animation-delay:1.2s; }
.radar-ring:nth-child(4){ width:480px;height:480px;animation-delay:1.8s; }
.radar-ring:nth-child(5){ width:580px;height:580px;animation-delay:2.4s; }
@keyframes radar-ping {
    0%,100%{ opacity:.5; transform:translate(-50%,-50%) scale(1); }
    50%{ opacity:1; transform:translate(-50%,-50%) scale(1.04); }
}
@media(max-width:960px){ .ddrf-radar { display:none; } }

.ddrf-hero-inner { position: relative; z-index: 2; display: flex; flex-direction: column; min-height: 84vh; }
.ddrf-hero-content {
    flex: 1; display: flex; flex-direction: column; justify-content: center;
    max-width: 1180px; margin: 0 auto; padding: 100px 24px 120px; width: 100%;
}

.ddrf-hero-badge {
    display: inline-flex; align-items: center; gap: 10px;
    background: rgba(110,86,247,.16); border: 1px solid rgba(110,86,247,.38);
    backdrop-filter: blur(12px); border-radius: 100px;
    padding: 8px 20px; font-size: 11.5px; font-weight: 700;
    letter-spacing: .12em; text-transform: uppercase;
    color: var(--p3); width: fit-content; margin-bottom: 22px;
    font-family: var(--font-mono);
}
.ddrf-badge-dot { width: 7px; height: 7px; border-radius: 50%; background: var(--p1); flex-shrink: 0; animation: pulse-dot 1.8s ease infinite; }

.ddrf-hero-title {
    font-family: var(--font-display);
    font-size: clamp(2.6rem, 5.5vw, 4.4rem);
    font-weight: 500; line-height: 1.15;
    color: #fff; margin-bottom: 10px; max-width: 680px;
}
.ddrf-hero-sub {
    font-family: var(--font-display);
    font-size: clamp(1.1rem, 2vw, 1.4rem);
    font-weight: 400; color: var(--p3);
    margin-bottom: 20px; letter-spacing: .04em;
}
.ddrf-hero-desc {
    font-size: clamp(14px, 1.7vw, 16px);
    color: rgba(255,255,255,.55); font-weight: 300;
    line-height: 1.85; max-width: 520px; margin-bottom: 36px;
}
.ddrf-hero-tags { display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 32px; }
.ddrf-hero-tag {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 7px 16px; border-radius: 100px;
    background: rgba(255,255,255,.06); border: 1px solid rgba(255,255,255,.12);
    font-size: 12px; font-weight: 600; color: rgba(255,255,255,.72);
    font-family: var(--font-mono); letter-spacing: .07em;
}
.ddrf-hero-tag svg { width: 13px; height: 13px; flex-shrink: 0; color: var(--p3); }
.ddrf-hero-btns { display: flex; gap: 12px; flex-wrap: wrap; }

/* Stat bar */
.ddrf-stat-bar {
    background: rgba(3,3,14,.96); backdrop-filter: blur(20px);
    border-top: 1px solid rgba(255,255,255,.05); display: flex;
}
.ddrf-stat-item { flex: 1; padding: 22px; text-align: center; border-left: 1px solid rgba(255,255,255,.04); }
.ddrf-stat-item:first-child { border-left: none; }
.ddrf-stat-val { font-family: var(--font-mono); font-size: clamp(18px,2.2vw,26px); color: #fff; display: block; font-weight: 700; line-height: 1; margin-bottom: 5px; letter-spacing: -.02em; }
.ddrf-stat-val.highlight { color: var(--p3); }
.ddrf-stat-lbl { font-size: 10px; letter-spacing: 1.4px; text-transform: uppercase; color: rgba(255,255,255,.35); font-family: var(--font-mono); }
@media(max-width:600px){
    .ddrf-stat-bar { flex-wrap: wrap; }
    .ddrf-stat-item { flex: 1 1 50%; border-top: 1px solid rgba(255,255,255,.04); }
    .ddrf-stat-item:nth-child(odd) { border-left: none; }
}


/* ═══════════════════════════════════════════════
   2. MARQUEE
═══════════════════════════════════════════════ */
.marquee-band { background: #07080f; overflow: hidden; border-top: 1px solid rgba(255,255,255,.04); border-bottom: 1px solid rgba(255,255,255,.04); }
.marquee-inner { display: flex; width: max-content; animation: marquee 28s linear infinite; }
.marquee-inner:hover { animation-play-state: paused; }
.marquee-row { display: flex; padding: 12px 0; }
.m-item { display: inline-flex; align-items: center; gap: 10px; padding: 0 36px; font-size: 11px; font-weight: 600; color: rgba(184,169,255,.55); letter-spacing: .12em; text-transform: uppercase; font-family: var(--font-mono); white-space: nowrap; }
.m-dot { width: 4px; height: 4px; border-radius: 50%; background: var(--p1); flex-shrink: 0; }
@keyframes marquee { 0%{ transform:translateX(0) } 100%{ transform:translateX(-50%) } }


/* ═══════════════════════════════════════════════
   3. ACTIVE CAMPAIGNS
═══════════════════════════════════════════════ */
.campaigns-section { padding: 84px 0 60px; background: var(--bg); }
.campaigns-header { text-align: center; margin-bottom: 56px; }
.campaigns-header .eyebrow { justify-content: center; }

.campaigns-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 22px; }
@media(max-width:960px){ .campaigns-grid { grid-template-columns: repeat(2, 1fr); } }
@media(max-width:560px){ .campaigns-grid { grid-template-columns: 1fr; } }

.campaign-card {
    background: var(--surface); border: 1.5px solid var(--border2);
    border-radius: var(--radius-lg); overflow: hidden;
    transition: all var(--transition); cursor: pointer; position: relative;
}
.campaign-card:hover { transform: translateY(-6px); box-shadow: var(--shadow-lg); border-color: rgba(110,86,247,.28); }
.campaign-card-img { position: relative; height: 180px; overflow: hidden; }
.campaign-card-img img { width: 100%; height: 100%; object-fit: cover; transition: transform .5s ease; }
.campaign-card:hover .campaign-card-img img { transform: scale(1.05); }

.campaign-urgency {
    position: absolute; top: 14px; left: 14px;
    display: inline-flex; align-items: center; gap: 6px;
    padding: 5px 12px; border-radius: 100px;
    font-family: var(--font-mono); font-size: 10px; font-weight: 700;
    letter-spacing: .1em; text-transform: uppercase;
}
.urgency-critical { background: var(--p1); color: #fff; }
.urgency-urgent   { background: var(--p2); color: #fff; }
.urgency-active   { background: #10b981; color: #fff; }
.urgency-dot { width: 6px; height: 6px; border-radius: 50%; background: currentColor; animation: pulse-dot 1.6s ease infinite; }

.campaign-card-body { padding: 20px; }
.campaign-location {
    display: inline-flex; align-items: center; gap: 5px;
    font-size: 11px; font-weight: 600; color: var(--text3);
    letter-spacing: .08em; text-transform: uppercase;
    font-family: var(--font-mono); margin-bottom: 8px;
}
.campaign-location svg { width: 11px; height: 11px; }
.campaign-card-title { font-size: 15px; font-weight: 700; color: var(--text); margin-bottom: 10px; line-height: 1.4; }
.campaign-card-desc  { font-size: 12.5px; color: var(--text2); line-height: 1.75; font-weight: 300; margin-bottom: 18px; }

.campaign-progress-wrap { margin-bottom: 14px; }
.campaign-progress-bar { height: 6px; background: var(--surface3); border-radius: 100px; overflow: hidden; margin-bottom: 8px; }
.campaign-progress-fill {
    height: 100%; border-radius: 100px;
    background: linear-gradient(90deg, var(--p1), var(--p2));
    transition: width 1.2s cubic-bezier(.4,0,.2,1);
}
.campaign-progress-meta { display: flex; justify-content: space-between; align-items: center; }
.cp-raised { font-family: var(--font-mono); font-size: 13px; font-weight: 700; color: var(--text); }
.cp-goal   { font-size: 12px; color: var(--text3); }
.cp-pct    { font-family: var(--font-mono); font-size: 12px; font-weight: 700; color: var(--p1); }

.campaign-card-footer {
    display: flex; align-items: center; justify-content: space-between;
    padding: 14px 20px; border-top: 1px solid var(--border);
}
.cf-donors { font-size: 12px; color: var(--text3); display: flex; align-items: center; gap: 5px; }
.cf-donors svg { width: 13px; height: 13px; }
.cf-btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 18px; border-radius: var(--radius-sm);
    background: linear-gradient(135deg, var(--p1), var(--p2)); color: #fff;
    font-size: 12.5px; font-weight: 700; border: none; cursor: pointer;
    transition: all var(--transition); font-family: var(--font);
}
.cf-btn:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(110,86,247,.42); }
.cf-btn svg { width: 13px; height: 13px; }

/* Empty state */
.no-campaigns {
    text-align: center; padding: 72px 24px;
    background: var(--surface); border: 1.5px dashed var(--border2); border-radius: var(--radius-lg);
    grid-column: 1/-1;
}
.no-campaigns-icon { font-size: 52px; margin-bottom: 16px; }
.no-campaigns h3 { font-family: var(--font-display); font-size: 20px; color: var(--text); margin-bottom: 8px; }
.no-campaigns p  { font-size: 14px; color: var(--text3); max-width: 380px; margin: 0 auto 24px; line-height: 1.7; }


/* ═══════════════════════════════════════════════
   4. 72-HOUR RESPONSE SYSTEM
═══════════════════════════════════════════════ */
.response-section {
    background: linear-gradient(160deg, #07080f 0%, #0a0814 55%, #0c0a1a 100%);
    padding: 88px 0; position: relative; overflow: hidden;
}
.response-section::before {
    content: ''; position: absolute; top: 50%; left: 50%;
    transform: translate(-50%,-50%);
    width: 700px; height: 700px; border-radius: 50%;
    background: radial-gradient(circle, rgba(110,86,247,.08) 0%, transparent 70%);
    pointer-events: none;
}
.response-header { text-align: center; margin-bottom: 68px; }
.response-header .eyebrow { color: var(--p3); justify-content: center; }
.response-header .eyebrow::before { background: var(--p3); }
.response-header .section-title { color: #fff; }
.response-header .section-title em { color: var(--p3); }
.response-header p { font-size: 15px; color: rgba(255,255,255,.42); font-weight: 300; line-height: 1.75; max-width: 520px; margin: 0 auto; }

.response-timeline {
    display: grid; grid-template-columns: repeat(3, 1fr); gap: 0;
    position: relative; max-width: 900px; margin: 0 auto 56px;
}
@media(max-width:720px){ .response-timeline { grid-template-columns: 1fr; max-width: 420px; } }

.response-timeline::before {
    content: ''; position: absolute;
    top: 40px; left: calc(16.67%); right: calc(16.67%);
    height: 2px;
    background: linear-gradient(90deg, rgba(110,86,247,.5), rgba(155,109,255,.5));
    z-index: 0;
}
@media(max-width:720px){ .response-timeline::before { display:none; } }

.rts-item {
    display: flex; flex-direction: column; align-items: center;
    text-align: center; padding: 0 20px; position: relative; z-index: 1;
}
@media(max-width:720px){
    .rts-item { flex-direction: row; text-align: left; align-items: flex-start; gap: 18px; padding: 0 0 36px; }
    .rts-item:last-child { padding-bottom: 0; }
}

.rts-circle {
    width: 80px; height: 80px; border-radius: 50%; flex-shrink: 0;
    background: rgba(110,86,247,.14);
    border: 2px solid rgba(110,86,247,.35);
    display: flex; align-items: center; justify-content: center;
    margin-bottom: 22px; position: relative; transition: all .35s ease;
}
.rts-item:hover .rts-circle { background: rgba(110,86,247,.24); border-color: rgba(110,86,247,.75); transform: scale(1.08); }
.rts-circle svg { width: 28px; height: 28px; color: var(--p3); }
.rts-circle::before {
    content: ''; position: absolute; inset: -8px; border-radius: 50%;
    border: 1px dashed rgba(110,86,247,.25); animation: spin-ring 14s linear infinite;
}
@keyframes spin-ring { to { transform: rotate(360deg); } }
@media(max-width:720px){ .rts-circle { margin-bottom: 0; } }

.rts-time-badge {
    position: absolute; top: -10px; right: -10px;
    background: var(--p1); color: #fff;
    font-family: var(--font-mono); font-size: 9.5px; font-weight: 700;
    padding: 4px 8px; border-radius: 100px;
    white-space: nowrap; border: 2px solid #07080f;
}
.rts-step  { font-size: 10px; font-weight: 700; color: rgba(184,169,255,.52); font-family: var(--font-mono); letter-spacing: .1em; margin-bottom: 8px; }
@media(max-width:720px){ .rts-step { margin-top: 4px; } }
.rts-title { font-size: 15px; font-weight: 700; color: #fff; margin-bottom: 8px; }
.rts-desc  { font-size: 12.5px; color: rgba(255,255,255,.42); line-height: 1.75; font-weight: 300; }

/* Network Stats */
.network-stats {
    display: grid; grid-template-columns: repeat(6, 1fr); gap: 1px;
    background: rgba(255,255,255,.06); border: 1px solid rgba(255,255,255,.07);
    border-radius: var(--radius-lg); overflow: hidden; max-width: 900px; margin: 0 auto;
}
@media(max-width:860px){ .network-stats { grid-template-columns: repeat(3, 1fr); } }
@media(max-width:480px){ .network-stats { grid-template-columns: repeat(2, 1fr); } }

.ns-item { padding: 24px 16px; text-align: center; background: rgba(255,255,255,.022); transition: background var(--transition); }
.ns-item:hover { background: rgba(110,86,247,.10); }
.ns-val { font-family: var(--font-mono); font-size: clamp(18px, 2vw, 24px); font-weight: 700; color: var(--p3); display: block; margin-bottom: 5px; }
.ns-lbl { font-size: 10px; letter-spacing: 1.2px; text-transform: uppercase; color: rgba(255,255,255,.32); font-family: var(--font-mono); }


/* ═══════════════════════════════════════════════
   5. HOW IT WORKS
═══════════════════════════════════════════════ */
.hiw-section { padding: 88px 0; background: var(--bg); }
.hiw-header  { text-align: center; margin-bottom: 56px; }
.hiw-header .eyebrow { justify-content: center; }

.hiw-cols { display: grid; grid-template-columns: 1fr 1fr; gap: 32px; }
@media(max-width:768px){ .hiw-cols { grid-template-columns: 1fr; } }

.hiw-col {
    background: var(--surface); border: 1.5px solid var(--border2);
    border-radius: var(--radius-lg); padding: 32px 28px;
    position: relative; overflow: hidden;
    transition: border-color var(--transition), box-shadow var(--transition);
}
.hiw-col:hover { border-color: rgba(110,86,247,.28); box-shadow: var(--shadow-lg); }
.hiw-col::before {
    content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px;
    background: linear-gradient(90deg, var(--p1), var(--p2));
}
.hiw-col-title {
    font-family: var(--font-display); font-size: 17px; font-weight: 700;
    color: var(--text); margin-bottom: 24px; display: flex; align-items: center; gap: 10px;
}
.hiw-col-icon { width: 36px; height: 36px; border-radius: 10px; background: var(--accent-lt); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.hiw-col-icon svg { width: 18px; height: 18px; color: var(--p1); }

.hiw-steps { display: flex; flex-direction: column; gap: 18px; }
.hiw-step { display: flex; align-items: flex-start; gap: 14px; }
.hiw-step-num {
    width: 32px; height: 32px; border-radius: 50%;
    background: linear-gradient(135deg, var(--p1), var(--p2));
    color: #fff; font-family: var(--font-mono); font-size: 12px; font-weight: 700;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(110,86,247,.32);
}
.hiw-step-title { font-size: 14px; font-weight: 700; color: var(--text); margin-bottom: 4px; }
.hiw-step-desc  { font-size: 12.5px; color: var(--text2); line-height: 1.75; font-weight: 300; }
.hiw-col-cta { margin-top: 28px; }


/* ═══════════════════════════════════════════════
   6. DRR SYSTEM
═══════════════════════════════════════════════ */
.drr-section {
    background: linear-gradient(135deg, #07080f 0%, #0a0814 100%);
    padding: 88px 0; position: relative; overflow: hidden;
}
.drr-inner { display: grid; grid-template-columns: 1fr 1fr; gap: 72px; align-items: center; position: relative; z-index: 1; }
@media(max-width:860px){ .drr-inner { grid-template-columns: 1fr; gap: 44px; } }

.drr-left .eyebrow { color: rgba(255,255,255,.62); }
.drr-left .eyebrow::before { background: rgba(255,255,255,.45); }
.drr-left .section-title { color: #fff; }
.drr-left .section-title em { color: var(--p3); }
.drr-left p { font-size: 15px; color: rgba(255,255,255,.50); line-height: 1.85; font-weight: 300; margin-bottom: 32px; }

.drr-pillars { display: flex; flex-direction: column; gap: 16px; }
.drr-pillar { display: flex; align-items: flex-start; gap: 14px; }
.drr-pillar-icon {
    width: 40px; height: 40px; border-radius: 11px;
    background: rgba(110,86,247,.14); border: 1px solid rgba(110,86,247,.25);
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    transition: all .3s ease;
}
.drr-pillar:hover .drr-pillar-icon { background: rgba(110,86,247,.26); }
.drr-pillar-icon svg { width: 17px; height: 17px; color: var(--p3); }
.drr-pillar-title { font-size: 14px; font-weight: 700; color: #fff; margin-bottom: 3px; }
.drr-pillar-desc  { font-size: 12.5px; color: rgba(255,255,255,.46); line-height: 1.7; font-weight: 300; }

.drr-visual { position: relative; display: flex; flex-direction: column; gap: 14px; }
.drr-map-card {
    background: rgba(255,255,255,.05); border: 1px solid rgba(255,255,255,.10);
    border-radius: var(--radius-lg); padding: 24px; backdrop-filter: blur(12px);
}
.drr-map-card-title { font-family: var(--font-mono); font-size: 10px; font-weight: 700; letter-spacing: .12em; color: rgba(255,255,255,.45); text-transform: uppercase; margin-bottom: 18px; }
.drr-map-dots { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; }
.drr-state-dot { display: flex; flex-direction: column; align-items: center; gap: 6px; }
.drr-state-ring {
    width: 44px; height: 44px; border-radius: 50%;
    border: 2px solid rgba(110,86,247,.28);
    display: flex; align-items: center; justify-content: center;
    background: rgba(110,86,247,.07); position: relative;
}
.drr-state-ring.active { border-color: var(--p1); background: rgba(110,86,247,.18); animation: state-pulse 2.5s ease infinite; }
.drr-state-ring.active::after {
    content: ''; position: absolute; inset: -6px; border-radius: 50%;
    border: 1px solid rgba(110,86,247,.28); animation: ring-expand .8s ease-out infinite;
}
@keyframes state-pulse { 0%,100%{ box-shadow:0 0 0 0 rgba(110,86,247,.32) }50%{ box-shadow:0 0 0 8px rgba(110,86,247,0) } }
@keyframes ring-expand { 0%{ transform:scale(1);opacity:1 } 100%{ transform:scale(1.6);opacity:0 } }
.drr-state-dot svg { width: 16px; height: 16px; color: var(--p3); }
.drr-state-name { font-size: 10px; color: rgba(255,255,255,.45); font-family: var(--font-mono); font-weight: 600; }

.drr-live-feed {
    background: rgba(255,255,255,.04); border: 1px solid rgba(255,255,255,.08);
    border-radius: var(--radius); padding: 16px 18px;
}
.dlf-header { display: flex; align-items: center; gap: 8px; margin-bottom: 14px; }
.dlf-dot { width: 7px; height: 7px; border-radius: 50%; background: var(--p1); animation: pulse-dot 1.5s ease infinite; }
.dlf-label { font-family: var(--font-mono); font-size: 10px; font-weight: 700; color: rgba(255,255,255,.45); letter-spacing: .1em; text-transform: uppercase; }
.dlf-items { display: flex; flex-direction: column; gap: 10px; }
.dlf-item { display: flex; align-items: center; gap: 10px; }
.dlf-item-dot { width: 6px; height: 6px; border-radius: 50%; background: rgba(110,86,247,.55); flex-shrink: 0; }
.dlf-item-text { font-size: 12px; color: rgba(255,255,255,.55); line-height: 1.5; }
.dlf-item-text strong { color: rgba(255,255,255,.82); font-weight: 600; }


/* ═══════════════════════════════════════════════
   7. FOUNDER MESSAGE
═══════════════════════════════════════════════ */
.founder-section { padding: 88px 0; background: var(--bg); }
.founder-inner {
    display: grid; grid-template-columns: 1fr 1.5fr; gap: 72px;
    align-items: center; max-width: 960px; margin: 0 auto;
}
@media(max-width:768px){ .founder-inner { grid-template-columns: 1fr; gap: 40px; } }

.founder-photo-wrap { position: relative; }
.founder-photo { width: 100%; aspect-ratio: 3/4; border-radius: var(--radius-lg); overflow: hidden; position: relative; }
.founder-photo img { width: 100%; height: 100%; object-fit: cover; }
.founder-photo::after { content: ''; position: absolute; inset: 0; background: linear-gradient(to top, rgba(0,0,0,.28), transparent); }
.founder-card-badge {
    position: absolute; bottom: -16px; left: 50%; transform: translateX(-50%);
    background: #fff; border: 1.5px solid var(--border2); border-radius: var(--radius);
    padding: 14px 24px; display: flex; align-items: center; gap: 12px;
    box-shadow: var(--shadow-md); white-space: nowrap;
}
.fcb-icon { width: 38px; height: 38px; border-radius: 10px; background: var(--accent-lt); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.fcb-icon svg { width: 18px; height: 18px; color: var(--p1); }
.fcb-name  { font-size: 13px; font-weight: 700; color: var(--text); }
.fcb-role  { font-size: 11px; color: var(--text3); font-family: var(--font-mono); }
@media(max-width:768px){ .founder-photo-wrap { max-width: 320px; margin: 0 auto; } }

.founder-right .eyebrow { margin-bottom: 16px; }
.founder-right .section-title { font-size: clamp(1.6rem, 2.8vw, 2.2rem); margin-bottom: 20px; }
.founder-quote {
    font-size: clamp(14px, 1.6vw, 16px); color: var(--text2);
    line-height: 1.9; font-weight: 300; margin-bottom: 24px;
    padding-left: 20px; border-left: 3px solid var(--p1);
}
.founder-right p { font-size: 14px; color: var(--text2); line-height: 1.85; font-weight: 300; margin-bottom: 28px; }
.founder-sig { font-family: var(--font-display); font-size: 15px; font-weight: 700; color: var(--text); display: flex; flex-direction: column; gap: 3px; }
.founder-sig span { font-family: var(--font); font-size: 12px; font-weight: 400; color: var(--text3); }


/* ═══════════════════════════════════════════════
   8. PARTNERS
═══════════════════════════════════════════════ */
.partners-section {
    padding: 64px 0;
    background: linear-gradient(160deg, #07080f 0%, #0d0e1a 100%);
}
.partners-header { text-align: center; margin-bottom: 44px; }
.partners-header .eyebrow { color: rgba(255,255,255,.58); justify-content: center; }
.partners-header .eyebrow::before { background: rgba(255,255,255,.38); }
.partners-header h2 { font-family: var(--font-display); font-size: clamp(1.4rem, 2.5vw, 2rem); color: #fff; font-weight:500; }
.partners-header h2 em { font-style: normal; color: var(--p3); }

.partners-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 14px; }
@media(max-width:900px){ .partners-grid { grid-template-columns: repeat(3, 1fr); } }
@media(max-width:560px){ .partners-grid { grid-template-columns: repeat(2, 1fr); } }

.partner-logo {
    background: rgba(255,255,255,.05); border: 1px solid rgba(255,255,255,.08);
    border-radius: var(--radius); padding: 18px;
    display: flex; align-items: center; justify-content: center;
    min-height: 80px; transition: all var(--transition);
}
.partner-logo:hover { background: rgba(110,86,247,.10); border-color: rgba(110,86,247,.25); transform: translateY(-2px); }
.partner-logo span { font-family: var(--font-mono); font-size: 12px; font-weight: 600; color: rgba(255,255,255,.42); letter-spacing: .06em; text-align: center; }


/* ═══════════════════════════════════════════════
   9. CTA
═══════════════════════════════════════════════ */
.ddrf-cta {
    position: relative; overflow: hidden; padding: 100px 0; text-align: center;
    background: linear-gradient(160deg, #07080f 0%, #0e0820 60%, #07080f 100%);
}
.ddrf-cta::before {
    content: ''; position: absolute; top: 50%; left: 50%;
    transform: translate(-50%,-50%);
    width: 700px; height: 700px; border-radius: 50%;
    background: radial-gradient(circle, rgba(110,86,247,.10) 0%, transparent 70%);
    pointer-events: none;
}
.ddrf-cta-inner { position: relative; z-index: 1; max-width: 580px; margin: 0 auto; padding: 0 24px; }
.ddrf-cta-title { font-family: var(--font-display); font-size: clamp(2rem, 4vw, 2.8rem); font-weight: 600; color: #fff; margin-bottom: 12px; line-height: 1.2; }
.ddrf-cta-title em { font-style: normal; color: var(--p3); }
.ddrf-cta-sub { font-size: 15px; color: rgba(255,255,255,.46); font-weight: 300; line-height: 1.8; max-width: 460px; margin: 0 auto 32px; }
.ddrf-cta-btns { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }

/* Scroll to top */
.scroll-top { position: fixed; bottom: 24px; right: 24px; width: 44px; height: 44px; border-radius: 50%; background: var(--p1); color: #fff; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; box-shadow: 0 6px 20px rgba(110,86,247,.48); opacity: 0; transform: translateY(16px); transition: all var(--transition); z-index: 999; }
.scroll-top.visible { opacity: 1; transform: translateY(0); }
.scroll-top:hover { transform: translateY(-2px); }
.scroll-top svg { width: 18px; height: 18px; }

@media(max-width:768px){
    .ddrf-hero-content { padding: 80px 16px 80px; }
    .campaigns-section,.hiw-section,.drr-section,.founder-section,.partners-section { padding: 60px 0; }
    .response-section { padding: 64px 0; }
}
</style>


{{-- ═══ ALERT BANNER ═══ --}}
<div class="ddrf-alert-banner">
    <span class="alert-pulse"></span>
    DDRF Active — DonateBazaar Disaster Relief Force is currently operational. Donations are disbursed within 72 hours.
    <span class="alert-pulse"></span>
</div>


{{-- ═══ HERO ═══ --}}
<div class="ddrf-hero">
    <div class="ddrf-hero-bg">
        <img src="{{ asset('images/ddrf-hero.jpg') }}" alt="Disaster Relief" loading="eager">
    </div>
    <div class="ddrf-hero-overlay"></div>
    <div class="ddrf-hero-grid"></div>

    <div class="ddrf-radar">
        <div class="radar-ring"></div>
        <div class="radar-ring"></div>
        <div class="radar-ring"></div>
        <div class="radar-ring"></div>
        <div class="radar-ring"></div>
    </div>

    <div class="ddrf-hero-inner">
        <div class="ddrf-hero-content">
            <div class="ddrf-hero-badge">
                <span class="ddrf-badge-dot"></span>
                DonateBazaar Disaster Relief Force
            </div>
            <h1 class="ddrf-hero-title">Relief. Rebuild.<br>Restore.</h1>
            <p class="ddrf-hero-sub">Emergency Crowdfunding for NGOs &amp; CSR</p>
            <p class="ddrf-hero-desc">
                DonateBazaar leverages technology and pan-India operational expertise to make disaster relief truly holistic. We partner with verified on-ground NGOs to deliver essential aid within 72 hours — and let donors track every rupee of impact.
            </p>
            <div class="ddrf-hero-tags">
                <span class="ddrf-hero-tag">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/></svg>
                    100% Verified NGOs
                </span>
                <span class="ddrf-hero-tag">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="9" y1="13" x2="15" y2="13"/></svg>
                    80G Tax Benefits
                </span>
                <span class="ddrf-hero-tag">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                    72-Hour Deployment
                </span>
                <span class="ddrf-hero-tag">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>
                    Real-time Tracking
                </span>
            </div>
            <div class="ddrf-hero-btns">
                <a href="{{ route('all.campaigns') }}?type=disaster" class="btn btn-purple" style="font-size:15px;padding:14px 32px">
                    Donate to Relief
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
                </a>
                <a href="{{ route('partnership') }}" class="btn btn-white" style="font-size:15px;padding:14px 32px">
                    Apply For Partnership
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                </a>
            </div>
        </div>

        <div class="ddrf-stat-bar">
            @foreach([
                ['val' => '₹' . number_format($totalRaised),  'lbl' => 'Amount Raised',     'highlight' => true],
                ['val' => number_format($totalDonors),         'lbl' => 'Lives Touched',     'highlight' => false],
                ['val' => $activeCamps,                        'lbl' => 'Active Campaigns',  'highlight' => false],
                ['val' => '500+',                              'lbl' => 'NGO Partners',      'highlight' => false],
                ['val' => '72 hrs',                            'lbl' => 'Max Response Time', 'highlight' => true],
                ['val' => '28',                                'lbl' => 'States Covered',    'highlight' => false],
            ] as $s)
            <div class="ddrf-stat-item">
                <span class="ddrf-stat-val {{ ($s['highlight'] ?? false) ? 'highlight' : '' }}">{{ $s['val'] }}</span>
                <span class="ddrf-stat-lbl">{{ $s['lbl'] }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>


{{-- ═══ MARQUEE ═══ --}}
<div class="marquee-band">
    <div class="marquee-inner">
        <div class="marquee-row">
            @php $mItems = ['Emergency Relief','72-Hour Deployment','Verified NGOs','80G Tax Benefits','Flood Relief','Earthquake Aid','Drought Support','Real-time Tracking','CSR Partnerships','Pan-India Coverage','RBI-Compliant','Product Giving']; @endphp
            @for($r=0;$r<3;$r++)
                @foreach($mItems as $mi)
                    <span class="m-item"><span class="m-dot"></span>{{ $mi }}</span>
                @endforeach
            @endfor
        </div>
    </div>
</div>


{{-- ═══ ACTIVE RELIEF CAMPAIGNS ═══ --}}
<section class="campaigns-section">
    <div class="container">
        <div class="campaigns-header reveal">
            <div class="eyebrow">Active Campaigns</div>
            <h2 class="section-title">Relief Campaigns <em>Live Now</em></h2>
            <p style="font-size:15px;color:var(--text2);font-weight:300;line-height:1.75;max-width:480px;margin:0 auto">
                Every campaign is verified by our team. Funds are released in milestone-based tranches directly to NGOs operating on the ground.
            </p>
        </div>
        <div class="campaigns-grid">
            @forelse($disasterCampaigns ?? [] as $i => $campaign)
            <div class="campaign-card reveal d{{ ($i % 3) + 1 }}">
                <div class="campaign-card-img">
                    <img src="{{ $campaign['image'] ?? asset('images/placeholder-relief.jpg') }}" alt="{{ $campaign['title'] }}" loading="lazy">
                    <span class="campaign-urgency urgency-{{ $campaign['urgency'] ?? 'active' }}">
                        <span class="urgency-dot"></span>{{ ucfirst($campaign['urgency'] ?? 'active') }}
                    </span>
                </div>
                <div class="campaign-card-body">
                    <div class="campaign-location">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        {{ $campaign['location'] ?? 'India' }}
                    </div>
                    <div class="campaign-card-title">{{ $campaign['title'] }}</div>
                    <div class="campaign-card-desc">{{ Str::limit($campaign['description'] ?? '', 110) }}</div>
                    <div class="campaign-progress-wrap">
                        <div class="campaign-progress-bar">
                            <div class="campaign-progress-fill" style="width:{{ min($campaign['percent'] ?? 0, 100) }}%"></div>
                        </div>
                        <div class="campaign-progress-meta">
                            <span class="cp-raised">₹{{ number_format($campaign['raised'] ?? 0) }}</span>
                            <span class="cp-pct">{{ $campaign['percent'] ?? 0 }}%</span>
                            <span class="cp-goal">of ₹{{ number_format($campaign['goal'] ?? 0) }}</span>
                        </div>
                    </div>
                </div>
                <div class="campaign-card-footer">
                    <span class="cf-donors">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
                        {{ $campaign['donors'] ?? 0 }} donors
                    </span>
<a href="{{ route('campaign.public', [
    'category' => $campaign['category'],
    'slug'     => $campaign['slug']
]) }}" class="cf-btn">
                        Donate Now
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>
            @empty
            <div class="no-campaigns reveal">
                <div class="no-campaigns-icon">🆘</div>
                <h3>No Active Campaigns Right Now</h3>
                <p>There are currently no active disaster relief campaigns. Check back soon — new campaigns launch within hours of a disaster.</p>
                <a href="{{ route('campaign.create') }}" class="btn btn-purple" style="font-size:14px;padding:12px 26px">
                    Start a Relief Campaign
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 4v16m8-8H4"/></svg>
                </a>
            </div>
            @endforelse
        </div>
    </div>
</section>


{{-- ═══ 72-HOUR RESPONSE SYSTEM ═══ --}}
<section class="response-section">
    <div class="container">
        <div class="response-header reveal">
            <div class="eyebrow">In Times of Need, We Arrive First</div>
            <h2 class="section-title">Our <em>72-Hour</em> Response System</h2>
            <p>We are capable of timely delivery of relief material — critical during a disaster. Our nationwide NGO, community, and vendor network makes it possible.</p>
        </div>
        <div class="response-timeline reveal d1">
            @php
            $steps = [
                ['icon'=>'map-pin','time'=>'0–24 Hrs','step'=>'Step 01','title'=>'Understand the Gravity','desc'=>'Our DDRF team assesses the disaster — scale, affected population, immediate requirements — within 24 hours of the incident.'],
                ['icon'=>'package','time'=>'24–48 Hrs','step'=>'Step 02','title'=>'Procure Relief Materials','desc'=>'We source essential materials from our trusted vendor list — food, medicine, blankets, water, sanitation kits — at scale and speed.'],
                ['icon'=>'truck','time'=>'48–72 Hrs','step'=>'Step 03','title'=>'Last-Mile Delivery','desc'=>'Materials are dispatched directly to our NGO partners operating in the disaster-affected areas, reaching beneficiaries fast.'],
            ];
            @endphp
            @foreach($steps as $s)
            <div class="rts-item">
                <div class="rts-circle">
                    @if($s['icon']==='map-pin')
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    @elseif($s['icon']==='package')
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
                    @elseif($s['icon']==='truck')
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                    @endif
                    <span class="rts-time-badge">{{ $s['time'] }}</span>
                </div>
                <div>
                    <div class="rts-step">{{ $s['step'] }}</div>
                    <div class="rts-title">{{ $s['title'] }}</div>
                    <div class="rts-desc">{{ $s['desc'] }}</div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="network-stats reveal d2">
            @foreach([
                ['val'=>'686','lbl'=>'Districts'],
                ['val'=>'500+','lbl'=>'NGO Partners'],
                ['val'=>'28','lbl'=>'States'],
                ['val'=>'680+','lbl'=>'Vendors'],
                ['val'=>'70+','lbl'=>'Relief Projects'],
                ['val'=>'8+','lbl'=>'Years Active'],
            ] as $ns)
            <div class="ns-item">
                <span class="ns-val">{{ $ns['val'] }}</span>
                <span class="ns-lbl">{{ $ns['lbl'] }}</span>
            </div>
            @endforeach
        </div>
    </div>
</section>


{{-- ═══ HOW IT WORKS ═══ --}}
<section class="hiw-section">
    <div class="container">
        <div class="hiw-header reveal">
            <div class="eyebrow">Small Steps, Large Impact</div>
            <h2 class="section-title">How It <em>Works</em></h2>
            <p style="font-size:15px;color:var(--text2);font-weight:300;line-height:1.75;max-width:440px;margin:0 auto">Whether you're donating or starting a relief campaign, DDRF makes it simple and transparent.</p>
        </div>
        <div class="hiw-cols">
            <div class="hiw-col reveal-left">
                <div class="hiw-col-title">
                    <div class="hiw-col-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg></div>
                    For Donors
                </div>
                <div class="hiw-steps">
                    <div class="hiw-step"><div class="hiw-step-num">1</div><div class="hiw-step-body"><div class="hiw-step-title">Choose a Cause</div><div class="hiw-step-desc">Browse active disaster relief campaigns filtered by region, disaster type, or urgency. Every campaign is DDRF-verified.</div></div></div>
                    <div class="hiw-step"><div class="hiw-step-num">2</div><div class="hiw-step-body"><div class="hiw-step-title">Donate Money or Products</div><div class="hiw-step-desc">Contribute via UPI, card, or net banking — or use Product Giving to buy specific relief items like food kits, blankets, or medicine packs.</div></div></div>
                    <div class="hiw-step"><div class="hiw-step-num">3</div><div class="hiw-step-body"><div class="hiw-step-title">Track &amp; Get Your 80G</div><div class="hiw-step-desc">Receive real-time field updates, photo/video proof of delivery, and your 80G tax certificate — all from your donor dashboard.</div></div></div>
                </div>
                <div class="hiw-col-cta">
                    <a href="{{ route('all.campaigns') }}?type=disaster" class="btn btn-purple" style="font-size:14px;padding:12px 26px">
                        Donate Now
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>
            <div class="hiw-col reveal-right">
                <div class="hiw-col-title">
                    <div class="hiw-col-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg></div>
                    For NGOs &amp; Charities
                </div>
                <div class="hiw-steps">
                    <div class="hiw-step"><div class="hiw-step-num">1</div><div class="hiw-step-body"><div class="hiw-step-title">Begin Your Relief Fundraiser</div><div class="hiw-step-desc">Launch a campaign in minutes. Share your on-ground story, add product giving items, and publish — all at zero platform cost.</div></div></div>
                    <div class="hiw-step"><div class="hiw-step-num">2</div><div class="hiw-step-body"><div class="hiw-step-title">Get Verified &amp; Spread the Word</div><div class="hiw-step-desc">DDRF team verifies your campaign within 24 hours. Share with your community, CSR partners, and social networks for maximum reach.</div></div></div>
                    <div class="hiw-step"><div class="hiw-step-num">3</div><div class="hiw-step-body"><div class="hiw-step-title">Receive Milestone-Based Funds</div><div class="hiw-step-desc">Funds are released in tranches as you upload field proof — photos, bills, delivery reports — keeping donors informed and confident.</div></div></div>
                </div>
                <div class="hiw-col-cta">
                    <a href="{{ route('campaign.create') }}" class="btn btn-accent" style="font-size:14px;padding:12px 26px">
                        Start a Relief Campaign
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 4v16m8-8H4"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>


{{-- ═══ DRR SYSTEM ═══ --}}
<section class="drr-section">
    <div class="container">
        <div class="drr-inner">
            <div class="drr-left reveal-left">
                <div class="eyebrow">Our Infrastructure</div>
                <h2 class="section-title">Connected to the <em>Last Mile</em></h2>
                <p>DDRF isn't just a fundraising platform — it's a full disaster response ecosystem. From CSR onboarding to field delivery, we manage the entire pipeline so relief reaches people when it matters most.</p>
                <div class="drr-pillars">
                    @foreach([
                        ['icon'=>'zap','title'=>'Rapid Deployment','desc'=>'Materials sourced and dispatched to affected districts within 72 hours of disaster confirmation.'],
                        ['icon'=>'shield','title'=>'Verified NGO Network','desc'=>'500+ partner NGOs across 28 states, all KYC-verified and trained in last-mile distribution.'],
                        ['icon'=>'eye','title'=>'Full Transparency','desc'=>'Real-time GPS tracking, photo proof uploads, and live donor dashboards for every campaign.'],
                        ['icon'=>'briefcase','title'=>'CSR Ready','desc'=>'End-to-end CSR management — from campaign alignment to 80G receipts and impact reports for board filing.'],
                    ] as $p)
                    <div class="drr-pillar">
                        <div class="drr-pillar-icon">
                            @if($p['icon']==='zap')<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            @elseif($p['icon']==='shield')<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/></svg>
                            @elseif($p['icon']==='eye')<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            @elseif($p['icon']==='briefcase')<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/></svg>
                            @endif
                        </div>
                        <div class="drr-pillar-text">
                            <div class="drr-pillar-title">{{ $p['title'] }}</div>
                            <div class="drr-pillar-desc">{{ $p['desc'] }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="drr-visual reveal-right">
                <div class="drr-map-card">
                    <div class="drr-map-card-title">Active District Coverage</div>
                    <div class="drr-map-dots">
                        @foreach([
                            ['name'=>'Kerala','active'=>true],['name'=>'Assam','active'=>true],['name'=>'Odisha','active'=>false],
                            ['name'=>'Gujarat','active'=>true],['name'=>'Bihar','active'=>false],['name'=>'WB','active'=>true],
                            ['name'=>'Tamil Nadu','active'=>false],['name'=>'Andhra','active'=>true],['name'=>'Rajasthan','active'=>false],
                        ] as $state)
                        <div class="drr-state-dot">
                            <div class="drr-state-ring {{ $state['active'] ? 'active' : '' }}">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            </div>
                            <span class="drr-state-name">{{ $state['name'] }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="drr-live-feed">
                    <div class="dlf-header">
                        <span class="dlf-dot"></span>
                        <span class="dlf-label">Live Field Updates</span>
                    </div>
                    <div class="dlf-items">
                        <div class="dlf-item"><span class="dlf-item-dot"></span><span class="dlf-item-text"><strong>Kerala Flood Relief:</strong> 2,400 food kits dispatched to Ernakulam districts</span></div>
                        <div class="dlf-item"><span class="dlf-item-dot"></span><span class="dlf-item-text"><strong>Assam Flood NGO:</strong> ₹8.2L released — milestone 2 verified</span></div>
                        <div class="dlf-item"><span class="dlf-item-dot"></span><span class="dlf-item-text"><strong>Gujarat Drought Aid:</strong> 600 water purification units en route</span></div>
                        <div class="dlf-item"><span class="dlf-item-dot"></span><span class="dlf-item-text"><strong>Andhra Relief:</strong> Proof photos uploaded — final tranche approved</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


{{-- ═══ FOUNDER MESSAGE ═══ --}}
<section class="founder-section">
    <div class="container">
        <div class="founder-inner">
            <div class="founder-photo-wrap reveal-left">
                <div class="founder-photo">
                    <img src="{{ asset('images/founder.jpg') }}" alt="Founder of DonateBazaar" loading="lazy">
                </div>
                <div class="founder-card-badge">
                    <div class="fcb-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
                    </div>
                    <div>
                        <div class="fcb-name">Founder of DonateBazaar</div>
                        <div class="fcb-role">A Message of Hope</div>
                    </div>
                </div>
            </div>
            <div class="founder-right reveal-right">
                <div class="eyebrow">Founder's Vision</div>
                <h2 class="section-title">Why We Built <em>DDRF</em></h2>
                <div class="founder-quote">
                    We envision the DonateBazaar Disaster Relief Force to be the first leg ahead — supporting every community wherever disaster strikes in India.
                </div>
                <p>Our nationwide network of partner NGOs, communities, and trusted vendors gives us the leverage to provide immediate relief material within 72 hours of any disaster. Our technology platform empowers corporate partners with real-time insights into ground operations during these critical moments.</p>
                <p>DonateBazaar strives to provide relief when people need it most — with utmost care, speed, and empathy. Every rupee you donate is tracked, verified, and reported back to you.</p>
                <div class="founder-sig">
                    Founder, DonateBazaar
                    <span>Empowering India's relief infrastructure since day one</span>
                </div>
            </div>
        </div>
    </div>
</section>


{{-- ═══ CSR / PARTNERS ═══ --}}
<section class="partners-section">
    <div class="container">
        <div class="partners-header reveal">
            <div class="eyebrow">Corporate Partners</div>
            <h2>Our <em>CSR Partners</em></h2>
        </div>
        <div class="partners-grid">
            @foreach($csrPartners ?? ['Partner NGO 1','Partner NGO 2','Partner NGO 3','Partner NGO 4','Partner NGO 5','Partner Corp 1','Partner Corp 2','Partner Corp 3','Partner Corp 4','Partner Corp 5'] as $partner)
            <div class="partner-logo reveal">
                @if(is_array($partner) && isset($partner['logo']))
                    <img src="{{ $partner['logo'] }}" alt="{{ $partner['name'] }}" style="max-height:36px;filter:brightness(0) invert(.55);transition:.3s" onmouseover="this.style.filter='brightness(0) invert(.82)'" onmouseout="this.style.filter='brightness(0) invert(.55)'">
                @else
                    <span>{{ is_array($partner) ? $partner['name'] : $partner }}</span>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</section>


{{-- ═══ CTA ═══ --}}
<section class="ddrf-cta">
    <div class="ddrf-cta-inner">
        <div class="eyebrow reveal" style="justify-content:center;color:var(--p3)">Join the Movement</div>
        <h2 class="ddrf-cta-title reveal d1">Together, We <em>Rebuild</em></h2>
        <p class="ddrf-cta-sub reveal d2">Donate, volunteer, partner with us for CSR — every action, big or small, brings us closer to a world where no community suffers alone.</p>
        <div class="ddrf-cta-btns reveal d3">
            <a href="{{ route('all.campaigns') }}?type=disaster" class="btn btn-purple" style="font-size:15px;padding:15px 34px">
                Donate to Relief
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
            </a>
            <a href="{{ route('campaign.create') }}" class="btn btn-white" style="font-size:15px;padding:15px 34px">
                Start Relief Campaign
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 4v16m8-8H4"/></svg>
            </a>
        </div>
    </div>
</section>

<button class="scroll-top" id="scrollTopBtn" onclick="window.scrollTo({top:0,behavior:'smooth'})" aria-label="Scroll to top">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 15l-6-6-6 6"/></svg>
</button>

<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ── Scroll Reveal ── */
    var revEls = document.querySelectorAll('.reveal,.reveal-left,.reveal-right');
    var obs = new IntersectionObserver(function(entries){
        entries.forEach(function(e){
            if(e.isIntersecting){ e.target.classList.add('visible'); obs.unobserve(e.target); }
        });
    },{ threshold: 0.08, rootMargin: '0px 0px -28px 0px' });
    revEls.forEach(function(el){ obs.observe(el); });

    /* ── Scroll to top ── */
    var sBtn = document.getElementById('scrollTopBtn');
    window.addEventListener('scroll', function(){
        sBtn.classList.toggle('visible', window.scrollY > 600);
    },{ passive: true });

    /* ── Animate progress bars ── */
    var bars = document.querySelectorAll('.campaign-progress-fill');
    var barObs = new IntersectionObserver(function(entries){
        entries.forEach(function(e){
            if(e.isIntersecting){
                var target = e.target.dataset.width || e.target.style.width;
                e.target.style.width = target;
                barObs.unobserve(e.target);
            }
        });
    },{ threshold: 0.2 });
    bars.forEach(function(bar){
        var w = bar.style.width;
        bar.dataset.width = w;
        bar.style.width = '0';
        setTimeout(function(){ barObs.observe(bar); }, 200);
    });

    /* ── Counter animation ── */
    function animateCounter(el) {
    const originalText = el.textContent.trim();

    // Detect currency symbol
    const hasRupee = originalText.includes('₹');

    // Detect plus sign
    const hasPlus = originalText.includes('+');

    // Extract numeric value only
    const num = parseInt(originalText.replace(/[^\d]/g, ''), 10);

    if (isNaN(num) || num === 0) return;

    const duration = 1500;
    let startTime = null;

    function step(timestamp) {
        if (!startTime) startTime = timestamp;

        const progress = Math.min((timestamp - startTime) / duration, 1);
        const current = Math.floor(progress * num);

        let formatted = current.toLocaleString('en-IN');

        if (hasRupee) formatted = '₹' + formatted;
        if (hasPlus) formatted += '+';

        el.textContent = formatted;

        if (progress < 1) {
            requestAnimationFrame(step);
        } else {
            let finalText = num.toLocaleString('en-IN');
            if (hasRupee) finalText = '₹' + finalText;
            if (hasPlus) finalText += '+';
            el.textContent = finalText;
        }
    }

    requestAnimationFrame(step);
}
</script>

@endsection