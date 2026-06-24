@extends('layouts.app')

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300&family=DM+Mono:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,700;1,700&display=swap" rel="stylesheet">

<style>
/* ═══════════════════════════════════════════════
   DESIGN TOKENS — identical to all-campaigns page
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
    --transition:   0.25s cubic-bezier(0.4,0,0.2,1);
}

*,*::before,*::after { box-sizing:border-box; margin:0; padding:0; }
html { scroll-behavior:smooth; }
body { font-family:var(--font); color:var(--text); background:var(--bg); -webkit-font-smoothing:antialiased; overflow-x:hidden; }
img  { max-width:100%; display:block; }
a    { text-decoration:none; color:inherit; }

.container { max-width:1180px; margin:0 auto; padding:0 24px; }
@media(max-width:480px){ .container { padding:0 16px; } }

/* ── Typography ── */
.eyebrow { font-size:11px; font-weight:600; letter-spacing:.14em; text-transform:uppercase; color:var(--accent); font-family:var(--font-mono); display:inline-flex; align-items:center; gap:8px; margin-bottom:14px; }
.eyebrow::before { content:''; width:20px; height:2px; background:var(--accent); border-radius:2px; flex-shrink:0; }
.section-title { font-family:var(--font-display); font-size:clamp(1.9rem,3.2vw,2.6rem); font-weight:600; line-height:1.15; color:var(--text); margin-bottom:14px; }
.section-title em { font-style:normal; color:var(--accent); }

/* ── Buttons ── */
.btn { display:inline-flex; align-items:center; gap:8px; padding:13px 28px; border-radius:var(--radius); font-weight:600; font-size:14px; font-family:var(--font); transition:all var(--transition); border:none; cursor:pointer; white-space:nowrap; }
.btn svg { width:16px; height:16px; flex-shrink:0; transition:transform var(--transition); }
.btn:hover svg { transform:translateX(3px); }
.btn-accent { background:linear-gradient(135deg,var(--accent),var(--accent2)); color:#fff; box-shadow:0 6px 24px rgba(99,102,241,.4); }
.btn-accent:hover { transform:translateY(-2px); box-shadow:0 12px 32px rgba(99,102,241,.5); }
.btn-white  { background:#fff; color:#1e1b4b; box-shadow:0 4px 20px rgba(0,0,0,.12); }
.btn-white:hover  { transform:translateY(-2px); box-shadow:0 12px 32px rgba(0,0,0,.18); }
.btn-outline { background:transparent; color:var(--text2); border:1.5px solid var(--border2); }
.btn-outline:hover { border-color:var(--accent); color:var(--accent); background:var(--accent-glow); }

/* ── Reveal ── */
.reveal      { opacity:0; transform:translateY(32px);   transition:opacity .7s ease, transform .7s ease; }
.reveal-left { opacity:0; transform:translateX(-32px);  transition:opacity .7s ease, transform .7s ease; }
.reveal-right{ opacity:0; transform:translateX(32px);   transition:opacity .7s ease, transform .7s ease; }
.reveal.visible,.reveal-left.visible,.reveal-right.visible { opacity:1; transform:none; }
.d1{transition-delay:.1s}.d2{transition-delay:.2s}.d3{transition-delay:.3s}.d4{transition-delay:.4s}.d5{transition-delay:.5s}.d6{transition-delay:.6s}


/* ═══════════════════════════════════════════════
   1. HERO
═══════════════════════════════════════════════ */
.hiw-hero {
    position:relative; width:100%; min-height:72vh;
    overflow:hidden; display:flex; flex-direction:column;
}
.hiw-hero-bg { position:absolute; inset:0; z-index:0; }
.hiw-hero-bg img { width:100%; height:100%; object-fit:cover; object-position:center 35%; }
.hiw-hero-overlay {
    position:absolute; inset:0; z-index:1;
    background:linear-gradient(110deg,rgba(5,5,20,.97) 0%,rgba(10,10,35,.9) 50%,rgba(20,15,50,.7) 100%);
}
.hiw-hero-grid {
    position:absolute; inset:0; z-index:1;
    background-image:linear-gradient(rgba(99,102,241,.06) 1px,transparent 1px),linear-gradient(90deg,rgba(99,102,241,.06) 1px,transparent 1px);
    background-size:60px 60px; opacity:.5; pointer-events:none;
}
/* Animated concentric rings in hero */
.hiw-hero-rings {
    position:absolute; top:50%; right:-5%;
    transform:translateY(-50%);
    width:640px; height:640px; z-index:1; pointer-events:none;
    opacity:.18;
}
.hiw-ring {
    position:absolute; border-radius:50%; border:1.5px solid rgba(99,102,241,.6);
    top:50%; left:50%; transform:translate(-50%,-50%);
    animation:ring-pulse 4s ease-in-out infinite;
}
.hiw-ring:nth-child(1){ width:160px;height:160px;animation-delay:0s; }
.hiw-ring:nth-child(2){ width:280px;height:280px;animation-delay:.5s; }
.hiw-ring:nth-child(3){ width:400px;height:400px;animation-delay:1s; }
.hiw-ring:nth-child(4){ width:520px;height:520px;animation-delay:1.5s; }
.hiw-ring:nth-child(5){ width:640px;height:640px;animation-delay:2s; }
@keyframes ring-pulse {
    0%,100%{opacity:.6;transform:translate(-50%,-50%) scale(1);}
    50%{opacity:1;transform:translate(-50%,-50%) scale(1.03);}
}
@media(max-width:960px){ .hiw-hero-rings{display:none;} }

.hiw-hero-inner { position:relative; z-index:2; display:flex; flex-direction:column; min-height:72vh; }
.hiw-hero-content {
    flex:1; display:flex; flex-direction:column; justify-content:center;
    max-width:1180px; margin:0 auto; padding:100px 24px 160px; width:100%;
}

.hiw-hero-pill {
    display:inline-flex; align-items:center; gap:10px;
    background:rgba(255,255,255,.09); border:1px solid rgba(255,255,255,.2);
    backdrop-filter:blur(12px); border-radius:100px;
    padding:8px 20px; font-size:11.5px; font-weight:600;
    letter-spacing:.1em; text-transform:uppercase;
    color:rgba(255,255,255,.85); width:fit-content; margin-bottom:24px;
    font-family:var(--font-mono);
}
.hiw-pill-dot { width:7px;height:7px;border-radius:50%;background:var(--green);flex-shrink:0;animation:pulse-dot 2s ease infinite; }
@keyframes pulse-dot { 0%,100%{opacity:1;box-shadow:0 0 0 0 rgba(16,185,129,.5)}50%{box-shadow:0 0 0 6px rgba(16,185,129,0)} }

.hiw-hero-title {
    font-family:var(--font-display);
    font-size:clamp(2.6rem,5vw,4rem);
    font-weight:500; line-height:1.2;
    color:#fff; margin-bottom:20px; max-width:640px;
}
.hiw-hero-title em { font-style:normal; color:#a5b4fc; }
.hiw-hero-desc {
    font-size:clamp(15px,1.8vw,17px);
    color:rgba(255,255,255,.6); font-weight:300;
    line-height:1.8; max-width:500px; margin-bottom:36px;
}
.hiw-hero-btns { display:flex; gap:12px; flex-wrap:wrap; }

/* Stat bar */
.hiw-stat-bar {
    background:rgba(5,5,18,.94); backdrop-filter:blur(20px);
    border-top:1px solid rgba(255,255,255,.06); display:flex;
}
.hiw-stat-item { flex:1; padding:22px; text-align:center; border-left:1px solid rgba(255,255,255,.05); }
.hiw-stat-item:first-child { border-left:none; }
.hiw-stat-val { font-family:var(--font-mono); font-size:clamp(18px,2.2vw,26px); color:#fff; display:block; font-weight:700; line-height:1; margin-bottom:5px; letter-spacing:-.02em; }
.hiw-stat-lbl { font-size:10px; letter-spacing:1.4px; text-transform:uppercase; color:rgba(255,255,255,.38); font-family:var(--font-mono); }
@media(max-width:600px){
    .hiw-stat-bar{flex-wrap:wrap;}
    .hiw-stat-item{flex:1 1 50%;border-top:1px solid rgba(255,255,255,.05);}
    .hiw-stat-item:nth-child(odd){border-left:none;}
}


/* ═══════════════════════════════════════════════
   2. MARQUEE — same as all-campaigns
═══════════════════════════════════════════════ */
.marquee-band { background:#07080f; overflow:hidden; border-top:1px solid rgba(255,255,255,.04); border-bottom:1px solid rgba(255,255,255,.04); }
.marquee-inner { display:flex; width:max-content; animation:marquee 28s linear infinite; }
.marquee-inner:hover { animation-play-state:paused; }
.marquee-row { display:flex; padding:13px 0; }
.m-item { display:inline-flex; align-items:center; gap:10px; padding:0 36px; font-size:11px; font-weight:600; color:rgba(165,180,252,.6); letter-spacing:.12em; text-transform:uppercase; font-family:var(--font-mono); white-space:nowrap; }
.m-dot { width:4px; height:4px; border-radius:50%; background:var(--accent2); flex-shrink:0; }
@keyframes marquee { 0%{transform:translateX(0)} 100%{transform:translateX(-50%)} }


/* ═══════════════════════════════════════════════
   3. TAB SWITCHER
═══════════════════════════════════════════════ */
.tabs-section { background:var(--surface); border-bottom:1px solid var(--border2); position:sticky; top:0; z-index:100; box-shadow:var(--shadow); }
.tabs-inner { display:flex; align-items:center; gap:4px; max-width:1180px; margin:0 auto; padding:10px 24px; }
.hiw-tab {
    display:flex; align-items:center; gap:8px;
    padding:10px 22px; border-radius:var(--radius-sm);
    font-family:var(--font); font-size:13.5px; font-weight:600;
    cursor:pointer; border:none; background:transparent; color:var(--text3);
    transition:all var(--transition);
}
.hiw-tab svg { width:15px; height:15px; flex-shrink:0; }
.hiw-tab:hover { color:var(--text); background:var(--surface2); }
.hiw-tab.active { background:linear-gradient(135deg,var(--accent),var(--accent2)); color:#fff; box-shadow:0 4px 14px rgba(99,102,241,.35); }
.hiw-tab-pane { display:none; }
.hiw-tab-pane.active { display:block; }


/* ═══════════════════════════════════════════════
   4. STEPS SECTION
═══════════════════════════════════════════════ */
.steps-section { padding:80px 0 60px; background:var(--bg); }
.steps-header { text-align:center; margin-bottom:64px; }
.steps-header .eyebrow { justify-content:center; }

.steps-grid {
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:0;
    position:relative;
}
@media(max-width:960px){ .steps-grid { grid-template-columns:repeat(2,1fr); gap:0; } }
@media(max-width:540px){ .steps-grid { grid-template-columns:1fr; } }

/* Connecting line between steps */
.steps-grid::before {
    content:'';
    position:absolute;
    top:52px; left:12.5%; right:12.5%;
    height:2px;
    background:linear-gradient(90deg,transparent,var(--accent),var(--accent2),var(--accent),transparent);
    z-index:0;
    opacity:.3;
}
@media(max-width:960px){ .steps-grid::before{display:none;} }

.step-card {
    display:flex; flex-direction:column; align-items:center;
    text-align:center; padding:32px 28px;
    position:relative; z-index:1;
    transition:transform var(--transition);
    cursor:default;
}
.step-card:hover { transform:translateY(-6px); }

.step-number-wrap { position:relative; margin-bottom:28px; }
.step-icon-ring {
    width:100px; height:100px; border-radius:50%;
    display:flex; align-items:center; justify-content:center;
    position:relative; z-index:1;
    transition:all .35s ease;
}
.step-card:hover .step-icon-ring { transform:scale(1.06); }
.step-icon-ring::before {
    content:'';
    position:absolute; inset:-6px; border-radius:50%;
    border:2px dashed currentColor; opacity:.2;
    animation:spin-ring 12s linear infinite;
}
@keyframes spin-ring { to{transform:rotate(360deg)} }
.step-icon-ring svg { width:36px; height:36px; }
.step-num-badge {
    position:absolute; top:-6px; right:-6px;
    width:28px; height:28px; border-radius:50%;
    background:linear-gradient(135deg,var(--accent),var(--accent2));
    color:#fff; font-family:var(--font-mono); font-size:11px; font-weight:700;
    display:flex; align-items:center; justify-content:center;
    box-shadow:0 4px 12px rgba(99,102,241,.4);
    border:2px solid var(--bg);
}

.step-title { font-family:var(--font-display); font-size:16px; font-weight:700; color:var(--text); margin-bottom:10px; }
.step-desc  { font-size:13.5px; color:var(--text2); line-height:1.8; font-weight:300; }

/* Arrow between steps (desktop) */
.step-arrow {
    position:absolute; right:-18px; top:50px;
    z-index:2; color:rgba(99,102,241,.35);
}
.step-arrow svg { width:28px; height:28px; }
.step-card:last-child .step-arrow { display:none; }
@media(max-width:960px){ .step-arrow { display:none; } }


/* ═══════════════════════════════════════════════
   5. DONATION JOURNEY — full-width dark section
═══════════════════════════════════════════════ */
.journey-section {
    background:linear-gradient(160deg,#07080f 0%,#0d0e1a 55%,#13141f 100%);
    padding:88px 0;
    position:relative; overflow:hidden;
}
.journey-section::before {
    content:'';
    position:absolute; top:50%; left:50%;
    transform:translate(-50%,-50%);
    width:800px; height:800px; border-radius:50%;
    background:radial-gradient(circle,rgba(99,102,241,.07) 0%,transparent 70%);
    pointer-events:none;
}

.journey-header { text-align:center; margin-bottom:60px; }
.journey-header .eyebrow { color:#a5b4fc; justify-content:center; }
.journey-header .eyebrow::before { background:#a5b4fc; }
.journey-header .section-title { color:#fff; }
.journey-header .section-title em { color:#a5b4fc; }
.journey-header p { font-size:15px; color:rgba(255,255,255,.45); font-weight:300; line-height:1.75; max-width:520px; margin:0 auto; }

/* Timeline row */
.journey-timeline {
    display:flex; align-items:flex-start; gap:0;
    position:relative; max-width:960px; margin:0 auto;
}
@media(max-width:768px){ .journey-timeline { flex-direction:column; gap:0; align-items:flex-start; } }

.journey-step {
    flex:1; display:flex; flex-direction:column; align-items:center;
    text-align:center; position:relative; padding:0 16px;
}
/* Horizontal connector */
.journey-step:not(:last-child)::after {
    content:'';
    position:absolute;
    top:28px; right:0; left:50%;
    height:2px;
    background:linear-gradient(90deg,rgba(99,102,241,.5),rgba(139,92,246,.5));
    z-index:0;
}
@media(max-width:768px){
    .journey-step { flex-direction:row; align-items:flex-start; text-align:left; padding:0 0 36px 0; gap:18px; width:100%; }
    .journey-step:not(:last-child)::after { top:auto; left:19px; right:auto; top:56px; width:2px; height:calc(100% - 56px); background:linear-gradient(180deg,rgba(99,102,241,.5),rgba(139,92,246,.3)); }
}

.journey-dot {
    width:56px; height:56px; border-radius:50%; flex-shrink:0;
    background:linear-gradient(135deg,var(--accent),var(--accent2));
    display:flex; align-items:center; justify-content:center;
    box-shadow:0 8px 24px rgba(99,102,241,.4);
    position:relative; z-index:1;
    border:3px solid rgba(255,255,255,.08);
    transition:all .35s ease;
    margin-bottom:20px;
}
.journey-step:hover .journey-dot { transform:scale(1.1); box-shadow:0 12px 32px rgba(99,102,241,.55); }
.journey-dot svg { width:22px; height:22px; color:#fff; }
@media(max-width:768px){ .journey-dot { margin-bottom:0; } }

.journey-step-content {}
.journey-step-num { font-family:var(--font-mono); font-size:10px; font-weight:700; color:rgba(165,180,252,.6); letter-spacing:.12em; margin-bottom:8px; }
@media(max-width:768px){ .journey-step-num { margin-top:4px; } }
.journey-step-title { font-size:14px; font-weight:700; color:#fff; margin-bottom:6px; }
.journey-step-desc  { font-size:12.5px; color:rgba(255,255,255,.45); line-height:1.7; font-weight:300; }

/* Rupee flow indicator */
.rupee-flow {
    display:flex; align-items:center; justify-content:center; gap:12px;
    margin-top:52px; padding:20px 28px;
    background:rgba(255,255,255,.04); border:1px solid rgba(255,255,255,.08);
    border-radius:var(--radius-lg); max-width:760px; margin-left:auto; margin-right:auto;
    flex-wrap:wrap; row-gap:12px;
}
.rf-item { display:flex; align-items:center; gap:8px; font-size:13px; font-weight:500; color:rgba(255,255,255,.7); }
.rf-item svg { width:16px; height:16px; color:var(--green); flex-shrink:0; }
.rf-arrow { color:rgba(99,102,241,.5); font-size:18px; font-weight:300; }


/* ═══════════════════════════════════════════════
   6. TRUST PILLARS
═══════════════════════════════════════════════ */
.trust-section { padding:88px 0; background:var(--bg); }
.trust-header { text-align:center; margin-bottom:60px; }
.trust-header .eyebrow { justify-content:center; }

.trust-grid {
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:20px;
}
@media(max-width:900px){ .trust-grid { grid-template-columns:repeat(2,1fr); } }
@media(max-width:560px){ .trust-grid { grid-template-columns:1fr; } }

.trust-card {
    background:var(--surface); border:1.5px solid var(--border2);
    border-radius:var(--radius-lg); padding:28px 26px;
    transition:all var(--transition); position:relative; overflow:hidden;
    cursor:default;
}
.trust-card::before {
    content:'';
    position:absolute; top:0; left:0; right:0; height:2px;
    background:var(--tc-color, var(--accent));
    transform:scaleX(0); transition:transform .35s; transform-origin:left;
}
.trust-card:hover { transform:translateY(-6px); box-shadow:var(--shadow-lg); border-color:rgba(99,102,241,.25); }
.trust-card:hover::before { transform:scaleX(1); }

.trust-icon {
    width:52px; height:52px; border-radius:14px;
    display:flex; align-items:center; justify-content:center;
    margin-bottom:18px; flex-shrink:0;
    transition:transform .3s ease;
}
.trust-card:hover .trust-icon { transform:scale(1.1) rotate(-5deg); }
.trust-icon svg { width:22px; height:22px; }
.trust-card-title { font-family:var(--font-display); font-size:15.5px; font-weight:700; color:var(--text); margin-bottom:8px; }
.trust-card-desc  { font-size:13px; color:var(--text2); line-height:1.8; font-weight:300; }


/* ═══════════════════════════════════════════════
   7. PRODUCT GIVING SPOTLIGHT
═══════════════════════════════════════════════ */
.product-section {
    background: linear-gradient(135deg, #000000 0%, #050141 100%);
    padding: 80px 0;
    position: relative;
    overflow: hidden;
}

.product-section::before {
    content:'';
    position:absolute; top:-100px; right:-100px;
    width:400px; height:400px; border-radius:50%;
    background:rgba(255,255,255,.06); pointer-events:none;
}
.product-section::after {
    content:'';
    position:absolute; bottom:-80px; left:-80px;
    width:300px; height:300px; border-radius:50%;
    background:rgba(255,255,255,.04); pointer-events:none;
}

.product-inner { display:grid; grid-template-columns:1fr 1fr; gap:60px; align-items:center; position:relative; z-index:1; }
@media(max-width:860px){ .product-inner { grid-template-columns:1fr; gap:40px; } }

.product-left .eyebrow { color:rgba(255,255,255,.7); }
.product-left .eyebrow::before { background:rgba(255,255,255,.5); }
.product-left .section-title { color:#fff; }
.product-left .section-title em { color:#c7d2fe; }
.product-left p { font-size:15px; color:rgba(255,255,255,.7); line-height:1.8; font-weight:300; margin-bottom:28px; }

.product-features { display:flex; flex-direction:column; gap:14px; margin-bottom:32px; }
.product-feature { display:flex; align-items:flex-start; gap:14px; }
.pf-icon { width:34px; height:34px; border-radius:10px; background:rgba(255,255,255,.15); border:1px solid rgba(255,255,255,.2); display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.pf-icon svg { width:15px; height:15px; color:#fff; }
.pf-text { font-size:13.5px; color:rgba(255,255,255,.8); line-height:1.65; font-weight:300; }
.pf-text strong { color:#fff; font-weight:600; display:block; font-size:13.5px; margin-bottom:2px; }

.product-cards-grid { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
.product-sample-card {
    background:rgba(255,255,255,.12);
    border:1px solid rgba(255,255,255,.2);
    backdrop-filter:blur(12px);
    border-radius:var(--radius); padding:18px;
    transition:all .3s ease;
}
.product-sample-card:hover { background:rgba(255,255,255,.18); transform:translateY(-3px); }
.psc-emoji { font-size:24px; margin-bottom:10px; }
.psc-name  { font-size:13px; font-weight:700; color:#fff; margin-bottom:4px; }
.psc-price { font-family:var(--font-mono); font-size:15px; font-weight:700; color:#c7d2fe; }
.psc-desc  { font-size:11.5px; color:rgba(255,255,255,.6); margin-top:4px; line-height:1.5; }


/* ═══════════════════════════════════════════════
   8. FAQ
═══════════════════════════════════════════════ */
.faq-section { padding:88px 0; background:var(--bg); }
.faq-header  { text-align:center; margin-bottom:48px; }
.faq-header .eyebrow { justify-content:center; }

.faq-tab-wrap { display:flex; gap:6px; justify-content:center; margin-bottom:36px; background:var(--surface); border:1px solid var(--border2); border-radius:var(--radius); padding:5px; max-width:400px; margin-left:auto; margin-right:auto; }
.faq-tab-btn { flex:1; padding:10px 20px; border-radius:var(--radius-sm); font-family:var(--font); font-size:13.5px; font-weight:600; cursor:pointer; border:none; background:transparent; color:var(--text3); transition:all var(--transition); }
.faq-tab-btn.active { background:linear-gradient(135deg,var(--accent),var(--accent2)); color:#fff; box-shadow:0 4px 14px rgba(99,102,241,.3); }

.faq-grid { display:grid; grid-template-columns:1fr 1fr; gap:14px; max-width:1000px; margin:0 auto; }
@media(max-width:768px){ .faq-grid { grid-template-columns:1fr; } }

.faq-item {
    background:var(--surface); border:1px solid var(--border2);
    border-radius:var(--radius); overflow:hidden;
    transition:border-color var(--transition), box-shadow var(--transition);
}
.faq-item.open { border-color:rgba(99,102,241,.3); box-shadow:0 4px 20px rgba(99,102,241,.08); }
.faq-q {
    display:flex; align-items:center; justify-content:space-between;
    padding:18px 22px; cursor:pointer; gap:14px;
    -webkit-tap-highlight-color:transparent;
}
.faq-q-text { font-size:14px; font-weight:600; color:var(--text); line-height:1.4; }
.faq-chevron {
    width:30px; height:30px; border-radius:50%;
    background:var(--surface2); border:1px solid var(--border2);
    display:flex; align-items:center; justify-content:center;
    flex-shrink:0; transition:all var(--transition);
}
.faq-chevron svg { width:12px; height:12px; color:var(--text3); transition:transform .3s; }
.faq-item.open .faq-chevron { background:var(--accent); border-color:var(--accent); }
.faq-item.open .faq-chevron svg { transform:rotate(180deg); color:#fff; }
.faq-answer { max-height:0; overflow:hidden; transition:max-height .4s cubic-bezier(.4,0,.2,1); }
.faq-item.open .faq-answer { max-height:300px; }
.faq-answer-inner { padding:0 22px 20px; font-size:13.5px; color:var(--text2); line-height:1.85; font-weight:300; }


/* ═══════════════════════════════════════════════
   9. CTA
═══════════════════════════════════════════════ */
.cta-section {
    position:relative; overflow:hidden; padding:100px 0; text-align:center;
    background:linear-gradient(160deg,#07080f 0%,#0d0e1a 55%,#13141f 100%);
}
.cta-section::before {
    content:''; position:absolute; top:50%; left:50%;
    transform:translate(-50%,-50%);
    width:700px; height:700px; border-radius:50%;
    background:radial-gradient(circle,rgba(99,102,241,.12) 0%,transparent 70%);
    pointer-events:none;
}
.cta-inner { position:relative; z-index:1; max-width:600px; margin:0 auto; padding:0 24px; }
.cta-title { font-family:var(--font-display); font-size:clamp(2rem,4vw,2.8rem); font-weight:600; color:#fff; margin-bottom:14px; line-height:1.2; }
.cta-title em { font-style:normal; color:#a5b4fc; }
.cta-sub { font-size:15px; color:rgba(255,255,255,.52); font-weight:300; line-height:1.8; max-width:460px; margin:0 auto 32px; }
.cta-btns { display:flex; gap:12px; justify-content:center; flex-wrap:wrap; }

/* Scroll to top */

.scroll-top { position:fixed; bottom:24px; right:24px; width:44px; height:44px; border-radius:50%; background:var(--accent); color:#fff; border:none; cursor:pointer; display:flex; align-items:center; justify-content:center; box-shadow:0 6px 20px rgba(99,102,241,.45); opacity:0; transform:translateY(16px); transition:all var(--transition); z-index:999; }
.scroll-top.visible { opacity:1; transform:translateY(0); }
.scroll-top:hover { transform:translateY(-2px); }
.scroll-top svg { width:18px; height:18px; }

@media(max-width:768px){
    .hiw-hero-content { padding:80px 16px 80px; }
    .steps-section { padding:52px 0 40px; }
    .trust-section,.journey-section,.faq-section,.product-section { padding:60px 0; }
}
</style>


{{-- ═══ HERO ═══ --}}
<div class="hiw-hero">
    <div class="hiw-hero-bg">
        <img src="{{ asset('images/about.jpg') }}" alt="How It Works" loading="eager">
    </div>
    <div class="hiw-hero-overlay"></div>
    <div class="hiw-hero-grid"></div>

    {{-- Animated concentric rings --}}
    <div class="hiw-hero-rings">
        <div class="hiw-ring"></div>
        <div class="hiw-ring"></div>
        <div class="hiw-ring"></div>
        <div class="hiw-ring"></div>
        <div class="hiw-ring"></div>
    </div>

    <div class="hiw-hero-inner">
        <div class="hiw-hero-content">
            <div class="hiw-hero-pill">
                <span class="hiw-pill-dot"></span>
                100% Verified · Transparent · Secure
            </div>
            <h1 class="hiw-hero-title">
                How <em>DonateBazaar</em><br>Actually Works
            </h1>
            <p class="hiw-hero-desc">
                Every rupee is tracked from the moment you donate to the final impact report.
                Learn how we keep donors and fundraisers safe, transparent, and accountable.
            </p>
            <div class="hiw-hero-btns">
                <a href="{{ route('all.campaigns') }}" class="btn btn-accent" style="font-size:15px;padding:14px 32px">
                    Browse Campaigns
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
                <a href="{{ route('campaign.create') }}" class="btn btn-white" style="font-size:15px;padding:14px 32px">
                    Start Fundraiser
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 4v16m8-8H4"/></svg>
                </a>
            </div>
        </div>

        <div class="hiw-stat-bar">
            @foreach($stats as $s)
            <div class="hiw-stat-item">
                <span class="hiw-stat-val">{{ $s['val'] }}</span>
                <span class="hiw-stat-lbl">{{ $s['lbl'] }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>


{{-- ═══ MARQUEE ═══ --}}
<div class="marquee-band">
    <div class="marquee-inner">
        <div class="marquee-row">
            @php $mItems = ['Verified Campaigns','256-bit SSL Payments','80G Tax Benefits','Real-time Tracking','Donor Protection Fund','24×7 Support','RBI-Compliant','Product Giving','Pan-India Coverage']; @endphp
            @for($r=0;$r<3;$r++)
                @foreach($mItems as $mi)
                    <span class="m-item"><span class="m-dot"></span>{{ $mi }}</span>
                @endforeach
            @endfor
        </div>
    </div>
</div>


{{-- ═══ STICKY TABS ═══ --}}
<div class="tabs-section">
    <div class="tabs-inner">
        <button class="hiw-tab active" id="tab-donors" onclick="switchTab('donors')">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
            For Donors
        </button>
        <button class="hiw-tab" id="tab-fundraisers" onclick="switchTab('fundraisers')">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
            For Fundraisers
        </button>
    </div>
</div>


{{-- ═══ DONORS TAB PANE ═══ --}}
<div class="hiw-tab-pane active" id="pane-donors">

    {{-- Steps --}}
    <section class="steps-section">
        <div class="container">
            <div class="steps-header reveal">
                <div class="eyebrow">4 Simple Steps</div>
                <h2 class="section-title">How to <em>Donate</em></h2>
                <p style="font-size:15px;color:var(--text2);font-weight:300;line-height:1.75;max-width:480px;margin:0 auto">From choosing a cause to tracking your impact — donating on DonateBazaar takes less than two minutes.</p>
            </div>

            <div class="steps-grid">
                @foreach($donorSteps as $i => $step)
                <div class="step-card reveal d{{ $i + 1 }}">
                    <div class="step-number-wrap">
                        <div class="step-icon-ring" style="background:{{ $step['bg'] }};color:{{ $step['color'] }}">
                            @if($step['icon'] === 'search')
                                <svg viewBox="0 0 24 24" fill="none" stroke="{{ $step['color'] }}" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                            @elseif($step['icon'] === 'heart')
                                <svg viewBox="0 0 24 24" fill="none" stroke="{{ $step['color'] }}" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
                            @elseif($step['icon'] === 'credit-card')
                                <svg viewBox="0 0 24 24" fill="none" stroke="{{ $step['color'] }}" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                            @elseif($step['icon'] === 'activity')
                                <svg viewBox="0 0 24 24" fill="none" stroke="{{ $step['color'] }}" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                            @endif
                        </div>
                        <div class="step-num-badge">{{ $step['number'] }}</div>
                    </div>
                    <div class="step-title">{{ $step['title'] }}</div>
                    <div class="step-desc">{{ $step['desc'] }}</div>
                    <div class="step-arrow">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Donation Journey --}}
    <section class="journey-section">
        <div class="container">
            <div class="journey-header reveal">
                <div class="eyebrow">Full Transparency</div>
                <h2 class="section-title">Where Your <em>Rupee Goes</em></h2>
                <p>We track every rupee from your payment gateway right to the beneficiary's hands — and report back to you at every step.</p>
            </div>

            <div class="journey-timeline reveal d1">
                @php
                $journeySteps = [
                    ['icon'=>'credit-card','num'=>'Step 1','title'=>'Payment Initiated','desc'=>'You donate via UPI, card, or net banking. 256-bit SSL protects every transaction.'],
                    ['icon'=>'shield','num'=>'Step 2','title'=>'Funds Secured','desc'=>'Money is held in an escrow-like trust account — never directly with the fundraiser.'],
                    ['icon'=>'check-circle','num'=>'Step 3','title'=>'Verified &amp; Disbursed','desc'=>'Our team verifies receipts and bills, then releases funds in milestone-based tranches.'],
                    ['icon'=>'activity','num'=>'Step 4','title'=>'Impact Reported','desc'=>'Fundraiser uploads photo/video proof. You get a full report and your 80G certificate.'],
                ];
                @endphp
                @foreach($journeySteps as $js)
                <div class="journey-step">
                    <div class="journey-dot">
                        @if($js['icon']==='credit-card')
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                        @elseif($js['icon']==='shield')
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        @elseif($js['icon']==='check-circle')
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        @elseif($js['icon']==='activity')
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                        @endif
                    </div>
                    <div class="journey-step-content">
                        <div class="journey-step-num">{{ $js['num'] }}</div>
                        <div class="journey-step-title">{!! $js['title'] !!}</div>
                        <div class="journey-step-desc">{{ $js['desc'] }}</div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="rupee-flow reveal d2">
                @php $rfItems = ['Your Payment','→','Secure Escrow','→','Milestone Verification','→','Disbursement','→','Impact Report + 80G']; @endphp
                @foreach($rfItems as $rfi)
                    @if($rfi === '→')
                        <span class="rf-arrow">→</span>
                    @else
                        <div class="rf-item">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                            {{ $rfi }}
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </section>

</div>{{-- /donors pane --}}


{{-- ═══ FUNDRAISERS TAB PANE ═══ --}}
<div class="hiw-tab-pane" id="pane-fundraisers">

    <section class="steps-section">
        <div class="container">
            <div class="steps-header reveal">
                <div class="eyebrow">4 Simple Steps</div>
                <h2 class="section-title">How to <em>Fundraise</em></h2>
                <p style="font-size:15px;color:var(--text2);font-weight:300;line-height:1.75;max-width:480px;margin:0 auto">From idea to live campaign in under 5 minutes. Our team handles verification, compliance, and support so you can focus on your cause.</p>
            </div>

            <div class="steps-grid">
                @foreach($fundraiserSteps as $i => $step)
                <div class="step-card reveal d{{ $i + 1 }}">
                    <div class="step-number-wrap">
                        <div class="step-icon-ring" style="background:{{ $step['bg'] }};color:{{ $step['color'] }}">
                            @if($step['icon'] === 'edit')
                                <svg viewBox="0 0 24 24" fill="none" stroke="{{ $step['color'] }}" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            @elseif($step['icon'] === 'shield')
                                <svg viewBox="0 0 24 24" fill="none" stroke="{{ $step['color'] }}" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/></svg>
                            @elseif($step['icon'] === 'zap')
                                <svg viewBox="0 0 24 24" fill="none" stroke="{{ $step['color'] }}" stroke-width="2"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            @elseif($step['icon'] === 'trending-up')
                                <svg viewBox="0 0 24 24" fill="none" stroke="{{ $step['color'] }}" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
                            @endif
                        </div>
                        <div class="step-num-badge">{{ $step['number'] }}</div>
                    </div>
                    <div class="step-title">{{ $step['title'] }}</div>
                    <div class="step-desc">{{ $step['desc'] }}</div>
                    <div class="step-arrow">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Product Giving Feature --}}
    <section class="product-section">
        <div class="container">
            <div class="product-inner">
                <div class="product-left reveal-left">
                    <div class="eyebrow">Unique Feature</div>
                    <h2 class="section-title">Product <em>Giving</em></h2>
                    <p>DonateBazaar's exclusive Product Giving feature lets donors buy physical products — not just donate money. Each product purchase directly funds your cause and creates a tangible connection between donor and beneficiary.</p>
                    <div class="product-features">
                        <div class="product-feature">
                            <div class="pf-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2z"/><path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/></svg></div>
                            <div class="pf-text"><strong>List any physical product</strong>Stationery, food kits, medicines, blankets, solar lanterns — anything your beneficiaries need.</div>
                        </div>
                        <div class="product-feature">
                            <div class="pf-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg></div>
                            <div class="pf-text"><strong>Donors see real impact</strong>Instead of just a money amount, donors see exactly what their ₹500 buys — a school kit, medicine pack, or food parcel.</div>
                        </div>
                        <div class="product-feature">
                            <div class="pf-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg></div>
                            <div class="pf-text"><strong>Track every product</strong>Live stock counter, sold-out states, and full purchase reports in your campaign dashboard.</div>
                        </div>
                    </div>
                    <a href="{{ route('campaign.create') }}" class="btn btn-white">
                        Add Products to My Campaign
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </a>
                </div>

<div class="product-cards-grid reveal-right">

    @php
    $sampleProducts = [
        [
            'icon' => 'book-open',
            'name' => 'School Kit',
            'price' => '₹250',
            'desc' => 'Notebook, pens, ruler set',
            'bg' => 'bg-indigo-100',
            'color' => 'text-indigo-600',
        ],
        [
            'icon' => 'heart',
            'name' => 'Medicine Pack',
            'price' => '₹400',
            'desc' => 'Essential medicines (1 month)',
            'bg' => 'bg-rose-100',
            'color' => 'text-rose-600',
        ],
        [
            'icon' => 'shopping-bag',
            'name' => 'Food Parcel',
            'price' => '₹200',
            'desc' => 'Nutritious family meal kit',
            'bg' => 'bg-amber-100',
            'color' => 'text-amber-600',
        ],
        [
            'icon' => 'sparkles',
            'name' => 'Tree Sapling',
            'price' => '₹50',
            'desc' => 'Plant a tree in your name',
            'bg' => 'bg-emerald-100',
            'color' => 'text-emerald-600',
        ],
    ];
    @endphp

    @foreach($sampleProducts as $sp)

    <div class="product-sample-card text-center flex flex-col items-center">

        <!-- Icon -->
        <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-5 {{ $sp['bg'] }} {{ $sp['color'] }}">

            @if($sp['icon'] === 'book-open')
                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                     viewBox="0 0 24 24" stroke-width="1.8"
                     stroke="currentColor" class="w-7 h-7">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5A4.5 4.5 0 003 9.5v9A4.5 4.5 0 017.5 14c1.746 0 3.332.477 4.5 1.253m0-9C13.168 5.477 14.754 5 16.5 5A4.5 4.5 0 0121 9.5v9a4.5 4.5 0 00-4.5-4.5c-1.746 0-3.332.477-4.5 1.253" />
                </svg>

            @elseif($sp['icon'] === 'heart')
                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                     viewBox="0 0 24 24" stroke-width="1.8"
                     stroke="currentColor" class="w-7 h-7">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M21 8.25c0-2.485-2.015-4.5-4.5-4.5-1.74 0-3.247.99-4 2.437A4.506 4.506 0 008.5 3.75C6.015 3.75 4 5.765 4 8.25c0 7.22 8 11.25 8 11.25s8-4.03 8-11.25z" />
                </svg>

            @elseif($sp['icon'] === 'shopping-bag')
                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                     viewBox="0 0 24 24" stroke-width="1.8"
                     stroke="currentColor" class="w-7 h-7">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M2.25 7.5h19.5m-16.5 0V6A2.25 2.25 0 017.5 3.75h9A2.25 2.25 0 0118.75 6v1.5m-13.5 0v10.125c0 .621.504 1.125 1.125 1.125h11.25c.621 0 1.125-.504 1.125-1.125V7.5" />
                </svg>

            @elseif($sp['icon'] === 'sparkles')
                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                     viewBox="0 0 24 24" stroke-width="1.8"
                     stroke="currentColor" class="w-7 h-7">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9.813 15.904L9 18l-1.813-2.096L5 15l2.187-.904L9 12l.813 2.096L12 15l-2.187.904zM18 13l.75 2.25L21 16l-2.25.75L18 19l-.75-2.25L15 16l2.25-.75L18 13zM12 3l1.125 3.375L16.5 7.5l-3.375 1.125L12 12l-1.125-3.375L7.5 7.5l3.375-1.125L12 3z" />
                </svg>
            @endif

        </div>

        <!-- Content -->
        <div class="psc-name text-center">{{ $sp['name'] }}</div>

        <div class="psc-price text-center">
            {{ $sp['price'] }}
        </div>

        <div class="psc-desc text-center">
            {{ $sp['desc'] }}
        </div>

    </div>

    @endforeach

</div>
            </div>
        </div>
    </section>

</div>{{-- /fundraisers pane --}}


{{-- ═══ TRUST PILLARS (shown in both tabs) ═══ --}}
<section class="trust-section">
    <div class="container">
        <div class="trust-header reveal">
            <div class="eyebrow">Why Choose Us</div>
            <h2 class="section-title">Built on <em>Trust</em></h2>
            <p style="font-size:15px;color:var(--text2);font-weight:300;line-height:1.75;max-width:480px;margin:0 auto">Six pillars that make DonateBazaar India's most trusted donation platform.</p>
        </div>
        <div class="trust-grid">
            @foreach($trustPillars as $i => $pillar)
            <div class="trust-card reveal d{{ ($i % 3) + 1 }}" style="--tc-color:{{ $pillar['color'] }}">
                <div class="trust-icon" style="background:{{ $pillar['bg'] }}">
                    @if($pillar['icon']==='shield')
                        <svg viewBox="0 0 24 24" fill="none" stroke="{{ $pillar['color'] }}" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/></svg>
                    @elseif($pillar['icon']==='lock')
                        <svg viewBox="0 0 24 24" fill="none" stroke="{{ $pillar['color'] }}" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                    @elseif($pillar['icon']==='eye')
                        <svg viewBox="0 0 24 24" fill="none" stroke="{{ $pillar['color'] }}" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    @elseif($pillar['icon']==='file-text')
                        <svg viewBox="0 0 24 24" fill="none" stroke="{{ $pillar['color'] }}" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="9" y1="13" x2="15" y2="13"/><line x1="9" y1="17" x2="12" y2="17"/></svg>
                    @elseif($pillar['icon']==='refresh-cw')
                        <svg viewBox="0 0 24 24" fill="none" stroke="{{ $pillar['color'] }}" stroke-width="2"><polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/><path d="M3.51 9a9 9 0 0114.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0020.49 15"/></svg>
                    @elseif($pillar['icon']==='headphones')
                        <svg viewBox="0 0 24 24" fill="none" stroke="{{ $pillar['color'] }}" stroke-width="2"><path d="M3 18v-6a9 9 0 0118 0v6"/><path d="M21 19a2 2 0 01-2 2h-1a2 2 0 01-2-2v-3a2 2 0 012-2h3v5zM3 19a2 2 0 002 2h1a2 2 0 002-2v-3a2 2 0 00-2-2H3v5z"/></svg>
                    @endif
                </div>
                <div class="trust-card-title">{{ $pillar['title'] }}</div>
                <div class="trust-card-desc">{{ $pillar['desc'] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>


{{-- ═══ FAQ ═══ --}}
<section class="faq-section">
    <div class="container">
        <div class="faq-header reveal">
            <div class="eyebrow">Got Questions?</div>
            <h2 class="section-title">Frequently Asked <em>Questions</em></h2>
        </div>

        <div class="faq-tab-wrap reveal">
            <button class="faq-tab-btn active" id="faq-tab-donors" onclick="switchFaqTab('donors')">For Donors</button>
            <button class="faq-tab-btn" id="faq-tab-fundraisers" onclick="switchFaqTab('fundraisers')">For Fundraisers</button>
        </div>

        <div id="faq-pane-donors">
            <div class="faq-grid">
                @foreach($faqsDonors as $i => $faq)
                <div class="faq-item reveal d{{ ($i%2)+1 }}" data-faq="d{{ $i }}">
                    <div class="faq-q" onclick="toggleFaq('d{{ $i }}')">
                        <span class="faq-q-text">{{ $faq['q'] }}</span>
                        <div class="faq-chevron"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg></div>
                    </div>
                    <div class="faq-answer"><div class="faq-answer-inner">{{ $faq['a'] }}</div></div>
                </div>
                @endforeach
            </div>
        </div>

        <div id="faq-pane-fundraisers" style="display:none">
            <div class="faq-grid">
                @foreach($faqsFundraisers as $i => $faq)
                <div class="faq-item reveal d{{ ($i%2)+1 }}" data-faq="f{{ $i }}">
                    <div class="faq-q" onclick="toggleFaq('f{{ $i }}')">
                        <span class="faq-q-text">{{ $faq['q'] }}</span>
                        <div class="faq-chevron"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg></div>
                    </div>
                    <div class="faq-answer"><div class="faq-answer-inner">{{ $faq['a'] }}</div></div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>


{{-- ═══ CTA ═══ --}}
<section class="cta-section">
    <div class="cta-inner">
        <div class="eyebrow reveal" style="justify-content:center;color:#a5b4fc">Ready to make a difference?</div>
        <h2 class="cta-title reveal d1">Start Your Own <em>Campaign</em></h2>
        <p class="cta-sub reveal d2">Medical emergency, education, disaster relief — whatever the cause, we verify and support your fundraiser from day one. Free to start, 24×7 support.</p>
        <div class="cta-btns reveal d3">
            <a href="{{ route('campaign.create') }}" class="btn btn-accent" style="font-size:15px;padding:15px 34px">
                Start Fundraiser
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 4v16m8-8H4"/></svg>
            </a>
            <a href="{{ route('all.campaigns') }}" class="btn btn-white" style="font-size:15px;padding:15px 34px">
                Browse Campaigns
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

    /* ── Scroll Reveal ── */
    var revEls = document.querySelectorAll('.reveal,.reveal-left,.reveal-right');
    var obs = new IntersectionObserver(function(entries){
        entries.forEach(function(e){ if(e.isIntersecting){ e.target.classList.add('visible'); obs.unobserve(e.target); } });
    },{ threshold:0.08, rootMargin:'0px 0px -28px 0px' });
    revEls.forEach(function(el){ obs.observe(el); });

    /* ── Scroll to top ── */
    var sBtn = document.getElementById('scrollTopBtn');
    window.addEventListener('scroll', function(){ sBtn.classList.toggle('visible', window.scrollY > 600); },{ passive:true });

    /* ── Set tab from URL hash ── */
    if (window.location.hash === '#fundraisers') switchTab('fundraisers');
});

/* ── Main tab switch (Donors / Fundraisers) ── */
function switchTab(tab) {
    ['donors','fundraisers'].forEach(function(t){
        document.getElementById('tab-'   + t).classList.toggle('active', t === tab);
        document.getElementById('pane-'  + t).classList.toggle('active', t === tab);
    });
    // Update URL hash without scroll
    history.replaceState(null, '', tab === 'donors' ? '#donors' : '#fundraisers');

    // Trigger reveal on newly visible elements
    setTimeout(function(){
        document.querySelectorAll('#pane-' + tab + ' .reveal, #pane-' + tab + ' .reveal-left, #pane-' + tab + ' .reveal-right').forEach(function(el){
            el.classList.add('visible');
        });
    }, 50);
}

/* ── FAQ tab switch ── */
function switchFaqTab(tab) {
    ['donors','fundraisers'].forEach(function(t){
        document.getElementById('faq-tab-'  + t).classList.toggle('active', t === tab);
        var pane = document.getElementById('faq-pane-' + t);
        pane.style.display = t === tab ? 'block' : 'none';
    });
}

/* ── FAQ accordion ── */
function toggleFaq(id) {
    var item   = document.querySelector('[data-faq="' + id + '"]');
    var isOpen = item.classList.contains('open');
    document.querySelectorAll('.faq-item.open').forEach(function(el){ el.classList.remove('open'); });
    if (!isOpen) item.classList.add('open');
}
</script>

@endsection