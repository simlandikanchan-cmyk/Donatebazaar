@extends('layouts.app')

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,300&family=DM+Mono:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,700;1,700&display=swap" rel="stylesheet">

<style>
/* ═══════════════════════════════════════════
   ROOT TOKENS
═══════════════════════════════════════════ */
:root {
    --bg:          #f4f5fb;
    --surface:     #ffffff;
    --surface2:    #f8f9fe;
    --surface3:    #f0f1fa;
    --border:      rgba(0,0,0,0.06);
    --border2:     rgba(0,0,0,0.10);
    --text:        #0f1117;
    --text2:       #4b5563;
    --text3:       #9ca3af;
    --accent:      #6366f1;
    --accent2:     #8b5cf6;
    --accent-glow: rgba(99,102,241,0.18);
    --orange:      #f97316;
    --orange2:     #ea580c;
    --orange-light:#fff7ed;
    --orange-glow: rgba(249,115,22,0.15);
    --green:       #10b981;
    --yellow:      #f59e0b;
    --red:         #ef4444;
    --blue:        #3b82f6;
    --font:        'DM Sans', sans-serif;
    --font-display:'DM Mono', serif;
    --font-mono:   'DM Mono', monospace;
    --radius:      14px;
    --radius-sm:   10px;
    --radius-lg:   22px;
    --shadow:      0 1px 3px rgba(0,0,0,0.06), 0 4px 16px rgba(0,0,0,0.04);
    --shadow-md:   0 4px 24px rgba(0,0,0,0.08);
    --shadow-lg:   0 8px 40px rgba(0,0,0,0.12);
    --transition:  0.22s cubic-bezier(0.4,0,0.2,1);
}

*,*::before,*::after { box-sizing:border-box; margin:0; padding:0; }
html { scroll-behavior:smooth; }
body { font-family:var(--font); color:var(--text); background:var(--bg); -webkit-font-smoothing:antialiased; overflow-x:hidden; }
img  { max-width:100%; display:block; }
a    { text-decoration:none; color:inherit; }
button { font-family:var(--font); }

/* ─── Reveal Animations ─── */
.reveal       { opacity:0; transform:translateY(26px); transition:opacity .65s ease, transform .65s ease; }
.reveal.visible { opacity:1; transform:translateY(0); }
.reveal-left  { opacity:0; transform:translateX(-26px); transition:opacity .65s ease, transform .65s ease; }
.reveal-left.visible  { opacity:1; transform:translateX(0); }
.reveal-right { opacity:0; transform:translateX(26px); transition:opacity .65s ease, transform .65s ease; }
.reveal-right.visible { opacity:1; transform:translateX(0); }
.d1{transition-delay:.1s} .d2{transition-delay:.2s} .d3{transition-delay:.3s}
.d4{transition-delay:.4s} .d5{transition-delay:.5s}

/* ─── Eyebrow ─── */
.eyebrow {
    font-size:11px; font-weight:600; letter-spacing:.14em;
    text-transform:uppercase; color:var(--accent); font-family:var(--font-mono);
    display:inline-flex; align-items:center; gap:8px; margin-bottom:12px;
}
.eyebrow::before { content:''; width:18px; height:2px; background:var(--accent); border-radius:2px; flex-shrink:0; }

/* ═══════════════════════════════════════════
   HERO
═══════════════════════════════════════════ */
.hero {
    position:relative; width:100%; height:88vh;
    min-height:560px; overflow:hidden;
    display:flex; flex-direction:column;
}
.hero-bg { position:absolute; inset:0; z-index:0; }
.hero-bg img {
    width:100%; height:100%; object-fit:cover; object-position:center;
    transform:scale(1.04); transition:transform 8s ease;
}
.hero:hover .hero-bg img { transform:scale(1); }
.hero-overlay {
    position:absolute; inset:0; z-index:1;
    background:linear-gradient(110deg,rgba(5,5,20,.93) 0%,rgba(10,10,35,.82) 45%,rgba(15,15,40,.45) 100%);
}
.hero-grid-lines {
    position:absolute; inset:0; z-index:1;
    background-image:linear-gradient(rgba(99,102,241,.05) 1px,transparent 1px),
                     linear-gradient(90deg,rgba(99,102,241,.05) 1px,transparent 1px);
    background-size:60px 60px; opacity:.5; pointer-events:none;
}
.hero-inner { position:relative; z-index:2; display:flex; flex-direction:column; height:100%; }
.hero-content {
    flex:1; display:flex; flex-direction:column;
    justify-content:flex-end; max-width:1180px;
    margin:0 auto; padding:0 24px 72px; width:100%;
}
.hero-breadcrumb {
    display:flex; align-items:center; gap:8px;
    margin-bottom:20px; font-size:12px;
    color:rgba(255,255,255,.5); font-family:var(--font-mono);
}
.hero-breadcrumb a { color:rgba(255,255,255,.5); transition:color var(--transition); }
.hero-breadcrumb a:hover { color:rgba(255,255,255,.85); }
.hero-breadcrumb svg { width:12px; height:12px; opacity:.5; }
.hero-cat-pill {
    display:inline-flex; align-items:center; gap:8px;
    background:linear-gradient(135deg,var(--accent),var(--accent2));
    border-radius:100px; padding:6px 16px;
    font-size:11px; font-weight:700; color:#fff;
    letter-spacing:.09em; text-transform:uppercase;
    font-family:var(--font-mono); margin-bottom:18px;
    width:fit-content; box-shadow:0 6px 20px rgba(99,102,241,.4);
}
.hero-cat-pill::before {
    content:''; width:6px; height:6px; border-radius:50%;
    background:rgba(255,255,255,.8); animation:pulse-live 2s ease infinite;
}
@keyframes pulse-live {
    0%,100%{opacity:1;box-shadow:0 0 0 0 rgba(255,255,255,.4)}
    50%{opacity:.7;box-shadow:0 0 0 5px rgba(255,255,255,0)}
}
.hero-title {
    font-family:var(--font-display); text-transform:capitalize;
    font-size:clamp(2.2rem,4.5vw,3.6rem); font-weight:500;
    line-height:1.1; color:#fff; margin-bottom:22px;
    max-width:780px; letter-spacing:-.01em;
}
.hero-meta { display:flex; flex-wrap:wrap; gap:10px; margin-bottom:32px; }
.hero-pill {
    display:inline-flex; align-items:center; gap:7px;
    background:rgba(255,255,255,.1); border:1px solid rgba(255,255,255,.18);
    backdrop-filter:blur(12px); border-radius:100px;
    padding:7px 16px; font-size:13px; color:rgba(255,255,255,.85); font-weight:500;
}
.hero-pill svg { width:13px; height:13px; opacity:.7; flex-shrink:0; }
.hero-progress-wrap { max-width:520px; }
.hero-progress-track { height:5px; background:rgba(255,255,255,.15); border-radius:3px; overflow:hidden; margin-bottom:10px; }
.hero-progress-fill {
    height:100%; border-radius:3px;
    background:linear-gradient(90deg,var(--green),#34d399);
    transition:width 1.2s cubic-bezier(.4,0,.2,1);
}
.hero-progress-meta { display:flex; justify-content:space-between; align-items:center; }
.hero-raised { font-family:var(--font-mono); font-size:22px; font-weight:700; color:#fff; }
.hero-goal   { font-size:13px; color:rgba(255,255,255,.55); font-family:var(--font-mono); }
.hero-pct    {
    font-family:var(--font-mono); font-size:13px; font-weight:600;
    color:var(--green); padding:3px 10px;
    background:rgba(16,185,129,.15); border:1px solid rgba(16,185,129,.25);
    border-radius:100px;
}
/* Float cards */
.hero-float-cards {
    position:absolute; right:5%; top:50%;
    transform:translateY(-60%); display:flex;
    flex-direction:column; gap:12px; z-index:3;
}
@media(max-width:1100px){ .hero-float-cards { display:none; } }
.hero-float-card {
    background:rgba(255,255,255,.09); border:1px solid rgba(255,255,255,.15);
    backdrop-filter:blur(20px); border-radius:var(--radius);
    padding:14px 18px; min-width:170px;
    animation:float-card 4s ease-in-out infinite;
}
.hero-float-card:nth-child(2){ animation-delay:1.4s; }
.hero-float-card:nth-child(3){ animation-delay:2.8s; }
@keyframes float-card { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-8px)} }
.fcard-lbl { font-size:10px; color:rgba(255,255,255,.5); font-family:var(--font-mono); text-transform:uppercase; letter-spacing:.08em; margin-bottom:5px; }
.fcard-val  { font-family:var(--font-mono); font-size:20px; font-weight:700; color:#fff; line-height:1; }
.fcard-sub  { font-size:10px; color:rgba(255,255,255,.35); margin-top:2px; }

/* ═══════════════════════════════════════════
   PAGE LAYOUT
═══════════════════════════════════════════ */
.page-wrap {
    max-width:1180px; margin:0 auto;
    padding:52px 24px 120px;
    display:grid; grid-template-columns:1fr 400px;
    gap:32px; align-items:start;
}
@media(max-width:980px) { .page-wrap { grid-template-columns:1fr; } }
@media(max-width:480px) { .page-wrap { padding:28px 16px 120px; } }
.left-col  { display:flex; flex-direction:column; gap:24px; }
.right-col { position:sticky; top:20px; display:flex; flex-direction:column; gap:16px; }
@media(max-width:980px) { .right-col { position:static; } }

/* ═══════════════════════════════════════════
   SECTION CARDS
═══════════════════════════════════════════ */
.sec-card {
    background:var(--surface); border:1px solid var(--border2);
    border-radius:var(--radius-lg); overflow:hidden;
    box-shadow:var(--shadow); transition:box-shadow var(--transition);
}
.sec-card:hover { box-shadow:var(--shadow-md); }
.sec-body-pad { padding:30px; }
@media(max-width:480px){ .sec-body-pad { padding:18px; } }
.sec-title {
    font-family:var(--font-display); text-transform:capitalize;
    font-size:clamp(1.25rem,2vw,1.65rem); font-weight:700;
    line-height:1.2; color:var(--text); margin-bottom:14px;
}
.sec-title em { font-style:normal; color:var(--accent); }
.sec-text { font-size:14.5px; line-height:1.85; color:var(--text2); font-weight:300; }

/* ─── Stats Strip ─── */
.stats-strip { display:grid; grid-template-columns:repeat(3,1fr); border-top:1px solid var(--border2); }
.stat-cell {
    padding:20px; text-align:center; border-right:1px solid var(--border2);
    position:relative; overflow:hidden; transition:background var(--transition);
}
.stat-cell:last-child { border-right:none; }
.stat-cell:hover { background:var(--surface2); }
.stat-cell::after {
    content:''; position:absolute; top:0; left:50%; right:50%; height:2px;
    background:linear-gradient(90deg,var(--accent),var(--accent2));
    transition:left .35s, right .35s;
}
.stat-cell:hover::after { left:0; right:0; }
.stat-val { font-family:var(--font-mono); font-size:clamp(20px,2.5vw,26px); font-weight:700; color:var(--accent); line-height:1; display:block; margin-bottom:5px; }
.stat-lbl { font-size:11px; font-weight:600; color:var(--text3); letter-spacing:.08em; text-transform:uppercase; font-family:var(--font-mono); }

/* ─── Stat cell colour overrides ─── */
.stat-cell.stat-money .stat-val  { color:var(--accent); }
.stat-cell.stat-product .stat-val { color:var(--orange); }
.stat-cell.stat-product::after    { background:linear-gradient(90deg,var(--orange),var(--orange2)); }

/* ─── Mission Chips ─── */
.mission-chips { display:flex; flex-wrap:wrap; gap:9px; margin-top:20px; }
.mission-chip {
    display:inline-flex; align-items:center; gap:8px;
    padding:8px 14px 8px 9px; border-radius:100px;
    font-size:12px; font-weight:600; font-family:var(--font);
    transition:all var(--transition); cursor:default;
    border:1.5px solid transparent; line-height:1; white-space:nowrap;
}
.chip-icon {
    width:22px; height:22px; border-radius:6px;
    display:flex; align-items:center; justify-content:center;
    flex-shrink:0; transition:transform var(--transition);
}
.mission-chip:hover .chip-icon { transform:scale(1.12); }
.chip-icon svg { width:12px; height:12px; }
.chip-verified    { background:rgba(99,102,241,.08);  color:#4338ca; border-color:rgba(99,102,241,.2); }
.chip-verified .chip-icon { background:rgba(99,102,241,.15); }
.chip-transparent { background:rgba(16,185,129,.07);  color:#065f46; border-color:rgba(16,185,129,.2); }
.chip-transparent .chip-icon { background:rgba(16,185,129,.15); }
.chip-secure      { background:rgba(59,130,246,.07);  color:#1d4ed8; border-color:rgba(59,130,246,.2); }
.chip-secure .chip-icon { background:rgba(59,130,246,.14); }
.chip-tax         { background:rgba(245,158,11,.07);  color:#92400e; border-color:rgba(245,158,11,.22); }
.chip-tax .chip-icon { background:rgba(245,158,11,.15); }
.chip-location    { background:rgba(100,116,139,.07); color:#334155; border-color:rgba(100,116,139,.2); }
.chip-location .chip-icon { background:rgba(100,116,139,.13); }
.chip-ending      { background:rgba(239,68,68,.07);   color:#b91c1c; border-color:rgba(239,68,68,.22); }
.chip-ending .chip-icon { background:rgba(239,68,68,.14); }
.chip-ending .chip-icon svg { animation:tick-pulse 1.4s ease-in-out infinite; }
@keyframes tick-pulse { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.6;transform:scale(0.85)} }
.chip-featured { background:rgba(245,158,11,.08); color:#b45309; border-color:rgba(245,158,11,.22); }
.chip-featured .chip-icon { background:rgba(245,158,11,.15); }
.chip-urgent   { background:rgba(239,68,68,.08);  color:#b91c1c; border-color:rgba(239,68,68,.22); }
.chip-urgent .chip-icon { background:rgba(239,68,68,.14); }

/* ─── Video ─── */
.video-wrap {
    position:relative; width:100%; padding-bottom:56.25%;
    border-radius:var(--radius); overflow:hidden; background:#000; margin-top:4px;
}
.video-wrap iframe { position:absolute; inset:0; width:100%; height:100%; border:none; }
.video-link-fallback {
    display:flex; align-items:center; gap:12px; padding:16px 18px;
    background:var(--surface2); border:1px solid var(--border2);
    border-radius:var(--radius); color:var(--accent); font-weight:500;
    font-size:14px; transition:all var(--transition);
}
.video-link-fallback:hover { background:var(--accent-glow); border-color:rgba(99,102,241,.3); }
.video-link-fallback svg { width:20px; height:20px; flex-shrink:0; }

/* ─── Updates Timeline ─── */
.updates-timeline { display:flex; flex-direction:column; gap:0; position:relative; margin-top:8px; }
.updates-timeline::before {
    content:''; position:absolute; left:19px; top:0; bottom:0; width:2px;
    background:linear-gradient(to bottom, var(--accent), rgba(99,102,241,.1));
    border-radius:2px; z-index:0;
}
.update-item { display:flex; gap:18px; align-items:flex-start; padding-bottom:26px; position:relative; z-index:1; }
.update-item:last-child { padding-bottom:0; }
.update-dot {
    width:40px; height:40px; border-radius:50%;
    background:linear-gradient(135deg,var(--accent),var(--accent2));
    display:flex; align-items:center; justify-content:center;
    flex-shrink:0; box-shadow:0 4px 14px rgba(99,102,241,.3);
    border:3px solid var(--surface);
}
.update-dot svg { width:15px; height:15px; color:#fff; }
.update-body {
    flex:1; background:var(--surface2); border:1px solid var(--border2);
    border-radius:var(--radius); padding:16px 18px;
    transition:all var(--transition);
}
.update-body:hover { border-color:rgba(99,102,241,.25); box-shadow:var(--shadow-md); }
.update-meta { display:flex; align-items:center; gap:10px; margin-bottom:8px; flex-wrap:wrap; }
.update-num-badge {
    font-size:10px; font-weight:700; letter-spacing:.1em; text-transform:uppercase;
    color:var(--accent); background:rgba(99,102,241,.1);
    padding:3px 10px; border-radius:100px; font-family:var(--font-mono);
}
.update-date { font-size:11px; color:var(--text3); font-family:var(--font-mono); }
.update-title { font-size:14.5px; font-weight:600; color:var(--text); margin-bottom:6px; }
.update-text  { font-size:13.5px; color:var(--text2); line-height:1.75; font-weight:300; }
.update-doc-link {
    display:inline-flex; align-items:center; gap:8px; margin-top:12px;
    padding:7px 14px; background:var(--surface);
    border:1.5px solid var(--border2); border-radius:var(--radius-sm);
    font-size:12px; font-weight:600; color:var(--accent); transition:all var(--transition);
}
.update-doc-link:hover { border-color:var(--accent); background:var(--accent-glow); }
.update-doc-link svg { width:13px; height:13px; }

/* ─── Products grid (left col) ─── */
.products-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(190px,1fr)); gap:14px; margin-top:8px; }
@media(max-width:480px){ .products-grid { grid-template-columns:1fr 1fr; } }
.product-card-left {
    background:var(--surface2); border:1.5px solid var(--border2);
    border-radius:var(--radius); padding:18px 16px;
    transition:all var(--transition); position:relative; overflow:hidden;
}
.product-card-left::before {
    content:''; position:absolute; top:0; left:0; right:0; height:2px;
    background:linear-gradient(90deg,var(--accent),var(--accent2));
    transform:scaleX(0); transform-origin:left; transition:transform .35s;
}
.product-card-left:hover { border-color:rgba(99,102,241,.3); box-shadow:var(--shadow-md); transform:translateY(-3px); }
.product-card-left:hover::before { transform:scaleX(1); }
.product-card-left-icon {
    width:42px; height:42px; border-radius:11px;
    background:linear-gradient(135deg,rgba(99,102,241,.12),rgba(139,92,246,.12));
    display:flex; align-items:center; justify-content:center; margin-bottom:12px;
}
.product-card-left-icon svg { width:19px; height:19px; color:var(--accent); }
.product-card-left-name  { font-size:13.5px; font-weight:700; color:var(--text); margin-bottom:5px; line-height:1.3; }
.product-card-left-desc  { font-size:12px; color:var(--text3); line-height:1.6; font-weight:300; margin-bottom:12px; }
.product-card-left-footer { display:flex; align-items:center; justify-content:space-between; padding-top:10px; border-top:1px dashed var(--border2); }
.product-price      { font-family:var(--font-mono); font-size:15px; font-weight:700; color:var(--accent); }
.product-qty-badge  {
    font-size:10px; font-weight:600; color:var(--text3);
    background:var(--surface); border:1px solid var(--border2);
    padding:3px 9px; border-radius:100px; font-family:var(--font-mono);
}
.product-qty-badge.out-of-stock { color:var(--red); border-color:rgba(239,68,68,.2); background:rgba(239,68,68,.05); }
.product-status-dot { width:5px; height:5px; border-radius:50%; background:var(--green); display:inline-block; margin-right:4px; animation:status-pulse 2s ease infinite; }
@keyframes status-pulse { 0%,100%{opacity:1} 50%{opacity:.35} }

/* ─── Impact Grid ─── */
.impact-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:12px; }
@media(max-width:680px){ .impact-grid { grid-template-columns:1fr; } }
.impact-card { border:1px solid var(--border2); border-radius:var(--radius); overflow:hidden; transition:transform var(--transition),box-shadow var(--transition),border-color var(--transition); }
.impact-card:hover { transform:translateY(-4px); box-shadow:var(--shadow-lg); border-color:rgba(99,102,241,.25); }
.impact-img { width:100%; height:148px; object-fit:cover; transition:transform .55s ease; display:block; }
.impact-card:hover .impact-img { transform:scale(1.07); }
.impact-body { padding:13px 15px; background:var(--surface); }
.impact-title { font-size:13px; font-weight:600; color:var(--text); margin-bottom:4px; display:flex; align-items:center; gap:6px; }
.impact-title svg { width:13px; height:13px; color:var(--accent); flex-shrink:0; }
.impact-text  { font-size:11.5px; color:var(--text3); line-height:1.65; font-weight:300; }

/* ─── Why Grid ─── */
.why-grid { display:grid; grid-template-columns:1fr 1fr; gap:11px; }
@media(max-width:560px){ .why-grid { grid-template-columns:1fr; } }
.why-item {
    display:flex; align-items:flex-start; gap:13px; padding:16px;
    border:1px solid var(--border2); border-radius:var(--radius);
    background:var(--surface2); transition:all var(--transition);
    position:relative; overflow:hidden;
}
.why-item::before {
    content:''; position:absolute; top:0; left:0; right:0; height:2px;
    background:var(--wi-color,var(--accent));
    transform:scaleX(0); transition:transform .35s; transform-origin:left;
}
.why-item:hover { border-color:rgba(99,102,241,.25); transform:translateY(-3px); box-shadow:var(--shadow-md); }
.why-item:hover::before { transform:scaleX(1); }
.why-icon { width:42px; height:42px; border-radius:11px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.why-icon svg { width:19px; height:19px; }
.why-name { font-size:13px; font-weight:600; color:var(--text); margin-bottom:4px; }
.why-desc { font-size:11.5px; color:var(--text3); line-height:1.6; font-weight:300; }

/* ─── FAQ ─── */
.faq-list { display:flex; flex-direction:column; gap:9px; }
.faq-item {
    background:var(--surface2); border:1px solid var(--border2);
    border-radius:var(--radius); overflow:hidden;
    transition:border-color var(--transition), box-shadow var(--transition);
}
.faq-item.open { border-color:rgba(99,102,241,.3); box-shadow:0 4px 16px rgba(99,102,241,.08); }
.faq-q {
    display:flex; align-items:center; justify-content:space-between;
    padding:17px 19px; cursor:pointer; gap:14px;
    -webkit-tap-highlight-color:transparent; user-select:none;
}
.faq-q-text { font-size:13.5px; font-weight:600; color:var(--text); line-height:1.4; }
.faq-chevron {
    width:28px; height:28px; border-radius:50%;
    background:var(--surface); border:1px solid var(--border2);
    display:flex; align-items:center; justify-content:center;
    flex-shrink:0; transition:all var(--transition);
}
.faq-chevron svg { width:12px; height:12px; color:var(--text3); transition:transform .35s ease; }
.faq-item.open .faq-chevron { background:var(--accent); border-color:var(--accent); }
.faq-item.open .faq-chevron svg { transform:rotate(180deg); color:#fff; }
.faq-answer { max-height:0; overflow:hidden; transition:max-height .42s cubic-bezier(.4,0,.2,1); }
.faq-item.open .faq-answer { max-height:400px; }
.faq-answer-inner {
    padding:0 19px 17px;
    font-size:13.5px; color:var(--text2); line-height:1.8; font-weight:300;
    border-top:1px solid var(--border);
    padding-top:14px;
    margin:0 19px;
    margin-bottom:17px;
}

/* ─── Dark strip (How Donation Works) ─── */
.dark-strip {
    background:linear-gradient(160deg,#07080f 0%,#0d0e1a 60%,#13141f 100%);
    border-radius:var(--radius-lg); padding:30px;
    position:relative; overflow:hidden;
}
.dark-strip::before {
    content:''; position:absolute; top:-80px; left:-80px;
    width:280px; height:280px; border-radius:50%;
    background:radial-gradient(circle,rgba(99,102,241,.08) 0%,transparent 70%);
    pointer-events:none;
}
.dark-strip .eyebrow { color:#a5b4fc; }
.dark-strip .eyebrow::before { background:#a5b4fc; }
.dark-strip .sec-title { color:#fff; }
.dark-strip .sec-title em { color:#a5b4fc; }
.hiw-steps { display:grid; grid-template-columns:repeat(3,1fr); gap:16px; margin-top:22px; position:relative; z-index:1; }
@media(max-width:600px){ .hiw-steps { grid-template-columns:1fr; } }
.hiw-step {
    background:rgba(255,255,255,.06); border:1px solid rgba(255,255,255,.09);
    border-radius:var(--radius); padding:20px;
    transition:all var(--transition); position:relative; overflow:hidden;
}
.hiw-step::before {
    content:''; position:absolute; top:0; left:0; right:0; height:2px;
    background:linear-gradient(90deg,var(--accent),var(--accent2));
    transform:scaleX(0); transition:transform .4s; transform-origin:left;
}
.hiw-step:hover { background:rgba(255,255,255,.09); border-color:rgba(99,102,241,.3); transform:translateY(-4px); }
.hiw-step:hover::before { transform:scaleX(1); }
.hiw-step-num   { font-family:var(--font-mono); font-size:11px; font-weight:700; color:var(--accent); letter-spacing:.1em; margin-bottom:11px; }
.hiw-step-icon  { width:42px; height:42px; border-radius:11px; background:rgba(255,255,255,.08); border:1px solid rgba(255,255,255,.1); display:flex; align-items:center; justify-content:center; margin-bottom:13px; transition:all var(--transition); }
.hiw-step:hover .hiw-step-icon { background:rgba(99,102,241,.2); border-color:rgba(99,102,241,.3); }
.hiw-step-icon svg { width:19px; height:19px; color:#a5b4fc; }
.hiw-step-title { font-size:13.5px; font-weight:600; color:#fff; margin-bottom:5px; }
.hiw-step-desc  { font-size:12px; color:rgba(255,255,255,.5); line-height:1.7; font-weight:300; }

/* ─── Recent Donors ─── */
.donor-row {
    display:flex; align-items:center; justify-content:space-between;
    background:var(--surface2); border:1px solid var(--border);
    border-radius:var(--radius); padding:13px 16px;
    transition:all var(--transition);
}
.donor-row:hover { background:#eff0fe; border-color:rgba(99,102,241,.2); }
.donor-row-left { display:flex; align-items:center; gap:13px; }
.donor-avatar-new {
    width:46px; height:46px; border-radius:13px; flex-shrink:0;
    background:linear-gradient(135deg,var(--accent),var(--accent2));
    color:#fff; display:flex; align-items:center; justify-content:center;
    font-size:16px; font-weight:700; box-shadow:0 4px 12px rgba(99,102,241,.3);
    font-family:var(--font-mono);
}
.donor-info-name { font-size:14px; font-weight:600; color:var(--text); }
.donor-info-time { font-size:12px; color:var(--text3); margin-top:2px; }
.donor-amount    { font-size:20px; font-weight:700; color:#259471; font-family:var(--font-mono); }
.donor-contrib   { font-size:10px; color:var(--text3); text-transform:uppercase; letter-spacing:.06em; text-align:right; margin-top:2px; }
.donor-type-badge {
    font-size:9px; font-weight:700; letter-spacing:.06em; text-transform:uppercase;
    padding:2px 8px; border-radius:100px; margin-top:3px; display:inline-block;
}
.donor-type-money   { background:rgba(99,102,241,.1); color:var(--accent); }
.donor-type-product { background:rgba(249,115,22,.1); color:var(--orange2); }

/* ─── Share & Action ─── */
.share-card { background:var(--surface); border:1px solid var(--border2); border-radius:var(--radius); padding:18px; box-shadow:var(--shadow); }
.share-card-title { font-size:13px; font-weight:700; color:var(--text); margin-bottom:13px; display:flex; align-items:center; gap:8px; }
.share-card-title svg { width:14px; height:14px; color:var(--accent); }
.share-btn {
    width:100%; display:flex; align-items:center; justify-content:center; gap:8px;
    padding:11px; border:1.5px solid var(--border2); border-radius:var(--radius-sm);
    background:var(--surface2); font-size:13px; font-weight:600;
    color:var(--text2); cursor:pointer; transition:all var(--transition);
}
.share-btn:hover { border-color:var(--accent); color:var(--accent); background:var(--accent-glow); }
.share-btn svg { width:14px; height:14px; }
.action-card {
    background:linear-gradient(135deg,var(--accent),var(--accent2));
    border-radius:var(--radius); padding:20px; color:#fff;
    text-align:center; position:relative; overflow:hidden;
}
.action-card::before {
    content:''; position:absolute; top:-40px; right:-40px;
    width:130px; height:130px; border-radius:50%;
    background:rgba(255,255,255,.07); pointer-events:none;
}
.action-card h4 { font-family:var(--font-display); font-size:14px; font-weight:700; margin-bottom:6px; position:relative; z-index:1; }
.action-card p  { font-size:12.5px; opacity:.78; margin-bottom:15px; font-weight:300; line-height:1.6; position:relative; z-index:1; }
.action-btn {
    display:inline-flex; align-items:center; gap:8px;
    background:rgba(255,255,255,.15); border:1px solid rgba(255,255,255,.28);
    border-radius:var(--radius-sm); padding:9px 18px;
    font-size:12.5px; font-weight:600; color:#fff;
    backdrop-filter:blur(8px); transition:background var(--transition);
    position:relative; z-index:1;
}
.action-btn:hover { background:rgba(255,255,255,.25); }
.action-btn svg { width:13px; height:13px; }

/* ─── Campaign Details ─── */
.details-row { display:flex; align-items:center; gap:10px; font-size:12.5px; color:var(--text2); }
.details-row svg { width:14px; height:14px; flex-shrink:0; }

/* ═══════════════════════════════════════════
   ★★★ NEW DONATE CARD — Products + Money
═══════════════════════════════════════════ */
.donate-card-new {
    background:var(--surface);
    border:1px solid var(--border2);
    border-radius:var(--radius-lg);
    overflow:hidden;
    box-shadow:var(--shadow-lg);
}

/* Header (dark) */
.donate-head-new {
    background:linear-gradient(160deg,#07080f 0%,#0d0e1a 60%,#13141f 100%);
    padding:22px 22px 20px; position:relative; overflow:hidden;
}
.donate-head-new::before {
    content:''; position:absolute; top:-50px; right:-50px;
    width:160px; height:160px; border-radius:50%;
    background:rgba(99,102,241,.09); pointer-events:none;
}
.donate-head-new-title { font-family:var(--font-display); font-size:15px; font-weight:700; color:#fff; margin-bottom:14px; position:relative; z-index:1; }
.donate-raised-row-new { display:flex; justify-content:space-between; align-items:baseline; margin-bottom:9px; position:relative; z-index:1; }
.donate-raised-new { font-family:var(--font-mono); font-size:24px; font-weight:700; color:var(--green); }
.donate-goal-new   { font-size:12.5px; color:rgba(255,255,255,.5); font-family:var(--font-mono); }
.donate-prog-track-new { height:5px; background:rgba(255,255,255,.12); border-radius:3px; overflow:hidden; margin-bottom:8px; position:relative; z-index:1; }
.donate-prog-fill-new  { height:100%; background:linear-gradient(90deg,var(--green),#34d399); border-radius:3px; transition:width 1.2s cubic-bezier(.4,0,.2,1); }
.donate-prog-meta-new  { display:flex; align-items:center; gap:10px; position:relative; z-index:1; }
.donate-pct-new         { font-family:var(--font-mono); font-size:12px; font-weight:700; color:var(--green); }
.donate-donors-count-new{ font-size:12px; color:rgba(255,255,255,.4); font-family:var(--font-mono); }

/* breakdown pills inside donate head */
.donate-breakdown {
    display:flex; gap:8px; margin-top:10px; position:relative; z-index:1; flex-wrap:wrap;
}
.donate-breakdown-pill {
    display:inline-flex; align-items:center; gap:5px;
    padding:4px 10px; border-radius:100px; font-size:11px;
    font-family:var(--font-mono); font-weight:600;
}
.dbp-money   { background:rgba(99,102,241,.2); color:#a5b4fc; }
.dbp-product { background:rgba(249,115,22,.2); color:#fdba74; }

/* ─── Main Tabs (Products / Money) ─── */
.main-donate-tabs {
    display:grid; grid-template-columns:1fr 1fr;
    border-bottom:1px solid var(--border2);
}
.main-donate-tab {
    padding:14px 12px; border:none; background:transparent;
    font-size:13.5px; font-weight:700; cursor:pointer;
    transition:all var(--transition); letter-spacing:.01em;
    display:flex; align-items:center; justify-content:center;
    gap:8px; position:relative; color:var(--text3);
}
.main-donate-tab::after {
    content:''; position:absolute; bottom:0; left:0; right:0; height:2.5px;
    border-radius:2px 2px 0 0; background:transparent; transition:background var(--transition);
}
.main-donate-tab.active-products { color:var(--orange); background:var(--orange-light); }
.main-donate-tab.active-products::after { background:var(--orange); }
.main-donate-tab.active-money { color:var(--accent); background:rgba(99,102,241,.05); }
.main-donate-tab.active-money::after { background:var(--accent); }
.main-donate-tab svg { width:15px; height:15px; }

/* ─── Products Panel ─── */
.panel-products { padding:14px 14px 0; }
.dp-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:10px; }
@media(max-width:420px){ .dp-grid { grid-template-columns:repeat(2,1fr); } }
.dp-card {
    border:1.5px solid var(--border2); border-radius:12px;
    overflow:hidden; background:var(--surface);
    transition:all var(--transition); position:relative; cursor:default;
}
.dp-card:hover { border-color:rgba(249,115,22,.35); box-shadow:0 6px 20px rgba(249,115,22,.12); transform:translateY(-2px); }
.dp-card.in-cart { border-color:var(--orange); }
.dp-badge { position:absolute; top:7px; left:7px; z-index:2; font-size:9px; font-weight:700; letter-spacing:.05em; text-transform:uppercase; padding:3px 8px; border-radius:100px; display:none; }
.dp-badge.visible { display:block; }
.dp-badge-impactful { background:#d1fae5; color:#065f46; }
.dp-badge-popular   { background:#ede9fe; color:#5b21b6; }
.dp-img-wrap { width:100%; height:110px; overflow:hidden; background:var(--surface2); display:flex; align-items:center; justify-content:center; position:relative; flex-shrink:0; }
.dp-img { max-width:86%; max-height:86%; width:auto; height:auto; object-fit:contain; display:block; transition:transform .45s ease; }
.dp-card:hover .dp-img { transform:scale(1.07); }
.dp-img-placeholder { width:50%; height:50%; opacity:.25; display:flex; align-items:center; justify-content:center; }
.dp-img-placeholder svg { width:100%; height:100%; color:var(--text3); }
.dp-add-btn { width:100%; padding:8px; border:none; background:var(--orange); color:#fff; font-size:12.5px; font-weight:700; cursor:pointer; transition:background var(--transition), opacity var(--transition); display:flex; align-items:center; justify-content:center; gap:5px; letter-spacing:.02em; }
.dp-add-btn:hover { background:var(--orange2); }
.dp-card.in-cart .dp-add-btn { background:#059669; }
.dp-add-btn svg { width:12px; height:12px; }
.dp-counter { display:none; align-items:center; justify-content:space-between; gap:0; width:100%; background:var(--orange2); }
.dp-counter.show { display:flex; }
.dp-minus, .dp-plus { flex:1; padding:8px; border:none; background:transparent; color:#fff; font-size:15px; font-weight:700; cursor:pointer; transition:background var(--transition); }
.dp-minus:hover { background:rgba(0,0,0,.12); }
.dp-plus:hover  { background:rgba(0,0,0,.12); }
.dp-count { font-family:var(--font-mono); font-size:13px; font-weight:700; color:#fff; }
.dp-info { padding:8px 9px 10px; }
.dp-name { font-size:11.5px; font-weight:600; color:var(--text); display:flex; align-items:center; justify-content:space-between; gap:4px; cursor:pointer; }
.dp-name-text { flex:1; line-height:1.3; }
.dp-expand-icon { width:16px; height:16px; flex-shrink:0; transition:transform var(--transition); color:var(--text3); }
.dp-card.expanded .dp-expand-icon { transform:rotate(180deg); }
.dp-expanded-desc { font-size:11px; color:var(--text3); line-height:1.6; margin-top:5px; max-height:0; overflow:hidden; transition:max-height .3s ease; }
.dp-card.expanded .dp-expanded-desc { max-height:120px; }
.dp-qty-row { margin-top:5px; }
.dp-qty-label { font-size:10px; color:var(--text3); margin-bottom:3px; }
.dp-qty-track { height:3px; background:var(--surface3); border-radius:2px; overflow:hidden; }
.dp-qty-fill  { height:100%; background:var(--orange); border-radius:2px; }
.dp-price { font-family:var(--font-mono); font-size:12.5px; font-weight:700; color:var(--text); margin-top:6px; }
.dp-load-more { width:100%; padding:11px; margin:12px 0 0; border:1.5px dashed var(--border2); border-radius:var(--radius-sm); background:transparent; font-size:12.5px; font-weight:600; color:var(--text3); cursor:pointer; transition:all var(--transition); display:flex; align-items:center; justify-content:center; gap:7px; }
.dp-load-more:hover { border-color:var(--orange); color:var(--orange); background:var(--orange-light); }
.dp-load-more svg { width:13px; height:13px; }
.dp-cart-bar { margin:12px 0 14px; display:none; align-items:center; justify-content:space-between; background:var(--orange-light); border:1.5px solid rgba(249,115,22,.3); border-radius:var(--radius-sm); padding:10px 14px; font-size:13px; font-weight:600; color:var(--orange2); gap:12px; }
.dp-cart-bar.show { display:flex; }
.dp-cart-bar-items { font-family:var(--font-mono); }
.dp-cart-clear { font-size:11px; color:var(--text3); cursor:pointer; text-decoration:underline; white-space:nowrap; }
.dp-cart-clear:hover { color:var(--red); }

/* ─── Money Panel ─── */
.panel-money { padding:16px 16px 0; }
.freq-tabs-new { display:flex; gap:5px; background:var(--surface2); border-radius:var(--radius-sm); padding:4px; margin-bottom:16px; border:1px solid var(--border2); }
.freq-tab-new { flex:1; padding:8px 5px; border:none; border-radius:8px; font-size:12px; font-weight:600; cursor:pointer; background:transparent; color:var(--text3); transition:all var(--transition); display:flex; align-items:center; justify-content:center; gap:4px; }
.freq-tab-new.active { background:var(--surface); color:var(--text); box-shadow:0 2px 8px rgba(0,0,0,.07); border:1px solid var(--border2); }
.freq-tab-new.active.ft-once    { color:var(--accent); }
.freq-tab-new.active.ft-weekly  { color:var(--blue); }
.freq-tab-new.active.ft-monthly { color:var(--accent2); }
.freq-banner-new { display:none; align-items:center; gap:9px; padding:9px 13px; border-radius:var(--radius-sm); font-size:12px; font-weight:500; margin-bottom:13px; line-height:1.5; }
.freq-banner-new.show { display:flex; }
.freq-banner-weekly-new  { background:#eff6ff; color:#1d4ed8; border:1px solid #bfdbfe; }
.freq-banner-monthly-new { background:var(--accent-glow); color:var(--accent); border:1px solid rgba(99,102,241,.2); }
.freq-banner-new svg { flex-shrink:0; width:13px; height:13px; }
.existing-sub-new { display:flex; align-items:flex-start; gap:9px; padding:11px 13px; background:#f0fdf4; border:1px solid #bbf7d0; border-radius:var(--radius-sm); font-size:12px; color:#166534; margin-bottom:13px; }
.existing-sub-new svg { flex-shrink:0; width:13px; height:13px; margin-top:1px; }
.amt-grid-new { display:grid; grid-template-columns:repeat(3,1fr); gap:7px; margin-bottom:12px; }
.amt-btn-new { padding:9px 5px; border:1.5px solid var(--border2); border-radius:var(--radius-sm); background:var(--surface2); font-family:var(--font); font-size:12.5px; font-weight:600; color:var(--text2); cursor:pointer; transition:all var(--transition); text-align:center; }
.amt-btn-new:hover { border-color:var(--accent); color:var(--accent); background:var(--accent-glow); }
.amt-btn-new.active { background:linear-gradient(135deg,var(--accent),var(--accent2)); border-color:transparent; color:#fff; box-shadow:0 4px 14px rgba(99,102,241,.35); transform:translateY(-2px); }
.custom-input-new { width:100%; border:1.5px solid var(--border2); border-radius:var(--radius-sm); padding:11px 13px; font-family:var(--font); font-size:14.5px; font-weight:600; color:var(--text); background:var(--surface2); outline:none; transition:border-color var(--transition),box-shadow var(--transition),background var(--transition); margin-bottom:11px; }
.custom-input-new:focus { border-color:var(--accent); box-shadow:0 0 0 3px var(--accent-glow); background:var(--surface); }
.custom-input-new:disabled { opacity:.5; cursor:not-allowed; }
.impact-preview-new { display:none; padding:10px 13px; background:var(--surface2); border:1px solid var(--border2); border-radius:var(--radius-sm); margin-bottom:11px; font-size:12px; color:var(--text2); line-height:1.65; }
.impact-preview-new.show { display:block; }
.impact-preview-new strong { color:var(--text); font-size:12.5px; display:block; margin-bottom:2px; }
.btn-donate-new { width:100%; display:flex; align-items:center; justify-content:center; gap:9px; padding:14px; border:none; border-radius:var(--radius); font-size:14.5px; font-weight:700; cursor:pointer; transition:all var(--transition); letter-spacing:.01em; font-family:var(--font); }
.btn-once    { background:linear-gradient(135deg,var(--accent),var(--accent2)); color:#fff; box-shadow:0 6px 22px rgba(99,102,241,.4); }
.btn-once:hover    { opacity:.9; transform:translateY(-2px); box-shadow:0 10px 28px rgba(99,102,241,.5); }
.btn-weekly  { background:linear-gradient(135deg,#2563eb,#3b82f6); color:#fff; box-shadow:0 6px 22px rgba(37,99,235,.35); }
.btn-weekly:hover  { opacity:.9; transform:translateY(-2px); }
.btn-monthly { background:linear-gradient(135deg,var(--accent2),#7c3aed); color:#fff; box-shadow:0 6px 22px rgba(139,92,246,.35); }
.btn-monthly:hover { opacity:.9; transform:translateY(-2px); }
.btn-donate-new svg { width:16px; height:16px; flex-shrink:0; }
.login-note-new { display:flex; align-items:center; gap:8px; padding:9px 13px; background:#fffbeb; border:1px solid #fde68a; border-radius:var(--radius-sm); font-size:12px; color:#92400e; margin-top:9px; }
.login-note-new svg { flex-shrink:0; width:13px; height:13px; }
.login-note-new a { color:#d97706; font-weight:600; }
.cancel-lnk { font-size:11px; color:var(--text3); text-align:center; display:block; margin-top:7px; }
.cancel-lnk a { color:var(--text3); text-decoration:underline; cursor:pointer; }
.cancel-lnk a:hover { color:var(--red); }
.money-panel-foot { padding:12px 16px 14px; }
.trust-row-new { display:flex; align-items:center; justify-content:center; gap:12px; flex-wrap:wrap; }
.trust-item-new { display:flex; align-items:center; gap:4px; font-size:11px; color:var(--text3); font-family:var(--font-mono); }
.trust-item-new svg { width:11px; height:11px; color:var(--green); }
.trust-sep-new { width:3px; height:3px; border-radius:50%; background:var(--border2); }

/* ═══════════════════════════════════════════
   STICKY DONATE BAR (bottom)
═══════════════════════════════════════════ */
.sticky-donate-bar { position:fixed; bottom:0; left:0; right:0; z-index:800; background:linear-gradient(90deg,#0d0e1a 0%,#131420 100%); border-top:1px solid rgba(255,255,255,.08); padding:10px 20px; display:flex; align-items:center; justify-content:space-between; gap:14px; transform:translateY(100%); transition:transform .45s cubic-bezier(.4,0,.2,1); backdrop-filter:blur(20px); -webkit-backdrop-filter:blur(20px); }
.sticky-donate-bar.visible { transform:translateY(0); }
@media(max-width:480px){ .sticky-donate-bar { padding:10px 14px; } }
.sdb-ticker { display:flex; align-items:center; gap:10px; overflow:hidden; flex:1; min-width:0; }
.sdb-ticker-icon { width:28px; height:28px; border-radius:50%; flex-shrink:0; background:linear-gradient(135deg,#34d399,#10b981); display:flex; align-items:center; justify-content:center; box-shadow:0 0 0 0 rgba(52,211,153,.5); animation:sdb-pulse 2s ease infinite; }
@keyframes sdb-pulse { 0%{box-shadow:0 0 0 0 rgba(52,211,153,.5)} 70%{box-shadow:0 0 0 7px rgba(52,211,153,0)} 100%{box-shadow:0 0 0 0 rgba(52,211,153,0)} }
.sdb-ticker-icon svg { width:12px; height:12px; color:#fff; }
.sdb-ticker-text { font-size:12.5px; color:rgba(255,255,255,.75); font-weight:500; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.sdb-ticker-text strong { color:#fff; }
.sdb-right { display:flex; align-items:center; gap:10px; flex-shrink:0; }
.sdb-btn { display:flex; align-items:center; gap:8px; background:var(--orange); color:#fff; border:none; border-radius:var(--radius-sm); padding:11px 22px; font-family:var(--font); font-size:13.5px; font-weight:700; cursor:pointer; text-transform:uppercase; letter-spacing:.04em; transition:all var(--transition); white-space:nowrap; box-shadow:0 4px 18px rgba(249,115,22,.45); }
.sdb-btn:hover { background:var(--orange2); transform:translateY(-1px); box-shadow:0 8px 24px rgba(249,115,22,.5); }
.sdb-btn svg { width:15px; height:15px; }
.sdb-share-btn { width:40px; height:40px; border-radius:50%; border:1.5px solid rgba(255,255,255,.2); background:rgba(255,255,255,.07); color:rgba(255,255,255,.7); cursor:pointer; display:flex; align-items:center; justify-content:center; transition:all var(--transition); flex-shrink:0; }
.sdb-share-btn:hover { border-color:rgba(255,255,255,.45); background:rgba(255,255,255,.15); color:#fff; }
.sdb-share-btn svg { width:15px; height:15px; }

/* ═══════════════════════════════════════════
   SCROLL TOP BUTTON
═══════════════════════════════════════════ */
.scroll-top { position:fixed; bottom:76px; right:22px; width:42px; height:42px; border-radius:50%; background:var(--accent); color:#fff; border:none; cursor:pointer; display:flex; align-items:center; justify-content:center; box-shadow:0 5px 18px rgba(99,102,241,.45); opacity:0; transform:translateY(14px); transition:all var(--transition); z-index:801; }
.scroll-top.visible { opacity:1; transform:translateY(0); }
.scroll-top:hover { transform:translateY(-2px); }
.scroll-top svg { width:17px; height:17px; }

/* ═══════════════════════════════════════════
   EMPTY STATE / ERROR ALERT
═══════════════════════════════════════════ */
.empty-state { text-align:center; padding:28px; color:var(--text3); }
.empty-state svg { width:38px; height:38px; margin:0 auto 11px; opacity:.35; }
.empty-state p { font-size:13px; }
.alert-error { background:rgba(239,68,68,0.08); border:1px solid rgba(239,68,68,0.25); color:#dc2626; padding:10px 13px; border-radius:var(--radius-sm); font-size:12.5px; font-weight:500; margin-bottom:11px; display:flex; align-items:center; gap:8px; }
.alert-error svg { flex-shrink:0; width:14px; height:14px; }

/* ─── Goal Reached badge / disclosure ─── */
.goal-reached-pill {
    font-family:var(--font-mono); font-size:13px; font-weight:600;
    color:var(--accent); padding:3px 10px;
    background:rgba(99,102,241,.15); border:1px solid rgba(99,102,241,.3);
    border-radius:100px; display:inline-flex; align-items:center; gap:5px;
}
.goal-reached-pill svg { width:11px; height:11px; }
.overfund-note { font-size:11.5px; line-height:1.55; margin-top:8px; }
</style>


@php
    $goal       = $campaign->goal_amount   ?? 0;
    $raised     = $campaign->raised_amount ?? 0;
    $percentRaw = $goal > 0 ? round(($raised / $goal) * 100) : 0;
    $percent    = min(100, $percentRaw); // capped, used only for bar widths
    $goalReached = $goal > 0 && $raised >= $goal;
    $donors  = $campaign->donations->count() ?? 0;
    $daysLeft = isset($campaign->end_date) && $campaign->end_date
                ? max(0, now()->diffInDays($campaign->end_date, false))
                : null;

    // ── Raised breakdown (money vs product) ──
    $moneyRaised   = $campaign->moneyRaised();
    $productRaised = $campaign->productRaised();

    // Products
    $products = collect();
    try {
        if (method_exists($campaign, 'products')) {
            $products = $campaign->products()->where('is_active', 1)->with('categoryProduct')->get();
        }
    } catch (\Throwable $e) {}

    // Updates
    $updates = collect();
    try {
        if (method_exists($campaign, 'updates')) {
            $updates = $campaign->updates()->orderBy('created_at', 'asc')->get();
        }
    } catch (\Throwable $e) {}

    // Video
    $videoUrl   = $campaign->video_url ?? null;
    $videoEmbed = null;
    if ($videoUrl) {
        if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([A-Za-z0-9_\-]+)/', $videoUrl, $m)) {
            $videoEmbed = 'https://www.youtube.com/embed/' . $m[1];
        } elseif (preg_match('/vimeo\.com\/(\d+)/', $videoUrl, $m)) {
            $videoEmbed = 'https://player.vimeo.com/video/' . $m[1];
        }
    }

    // Recent donations for sticky bar ticker
    $latestDonation = $campaign->donations->sortByDesc('created_at')->first();
@endphp


{{-- ═══ HERO ═══ --}}
<div class="hero">
    <div class="hero-bg">
        <img src="{{ $campaign->cover_image ? asset('storage/'.$campaign->cover_image) : asset('images/about.jpg') }}"
             alt="{{ $campaign->title }}" loading="eager">
    </div>
    <div class="hero-overlay"></div>
    <div class="hero-grid-lines"></div>

    <div class="hero-float-cards">
        <div class="hero-float-card">
            <div class="fcard-lbl">Raised</div>
            <div class="fcard-val">₹{{ number_format($raised) }}</div>
            <div class="fcard-sub">of ₹{{ number_format($goal) }} goal</div>
        </div>
        <div class="hero-float-card">
            <div class="fcard-lbl">Donors</div>
            <div class="fcard-val">{{ number_format($donors) }}</div>
            <div class="fcard-sub">people contributed</div>
        </div>
        @if($daysLeft !== null)
        <div class="hero-float-card">
            <div class="fcard-lbl">{{ $daysLeft > 0 ? 'Days Left' : 'Campaign' }}</div>
            <div class="fcard-val">{{ $daysLeft > 0 ? $daysLeft : '🎯' }}</div>
            <div class="fcard-sub">{{ $daysLeft > 0 ? 'until campaign ends' : 'Ends today!' }}</div>
        </div>
        @endif
    </div>

    <div class="hero-inner">
        <div class="hero-content">
            <div class="hero-breadcrumb">
                <a href="{{ url('/') }}">Home</a>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                <a href="{{ route('all.campaigns') }}">Campaigns</a>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                <span style="color:rgba(255,255,255,.75)">{{ Str::limit($campaign->title, 40) }}</span>
            </div>

            <div class="hero-cat-pill">{{ $campaign->category->name ?? 'General' }}</div>
            <h1 class="hero-title">{{ $campaign->title }}</h1>

            <div class="hero-meta">
                <span class="hero-pill">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    {{ $campaign->created_at->format('d M Y') }}
                </span>
                <span class="hero-pill">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    By {{ $campaign->user->name ?? 'DonateBazaar' }}
                </span>
                <span class="hero-pill">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                    {{ number_format($donors) }} Donors
                </span>
                @if($daysLeft !== null)
                <span class="hero-pill" style="background:rgba(16,185,129,.15);border-color:rgba(16,185,129,.25)">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    <span style="color:#34d399">{{ $daysLeft > 0 ? $daysLeft . ' days left' : 'Ends today' }}</span>
                </span>
                @endif
                @if($campaign->is_featured)
                <span class="hero-pill" style="background:rgba(245,158,11,.15);border-color:rgba(245,158,11,.3)">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2"><path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                    <span style="color:#fcd34d">Featured</span>
                </span>
                @endif
                @if($campaign->is_urgent)
                <span class="hero-pill" style="background:rgba(239,68,68,.15);border-color:rgba(239,68,68,.3)">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    <span style="color:#fca5a5">Urgent</span>
                </span>
                @endif
                <span class="hero-pill" style="background:rgba(99,102,241,.15);border-color:rgba(99,102,241,.25)">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#a5b4fc" stroke-width="2"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    <span style="color:#a5b4fc">Verified Campaign</span>
                </span>
            </div>

            <div class="hero-progress-wrap">
                <div class="hero-progress-track">
                    <div class="hero-progress-fill" style="width:{{ $percent }}%"></div>
                </div>
                <div class="hero-progress-meta">
                    <div>
                        <div class="hero-raised">₹{{ number_format($raised) }}</div>
                        <div class="hero-goal">of ₹{{ number_format($goal) }} goal</div>
                    </div>
                    @if($goalReached)
                    <div class="goal-reached-pill">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                        Goal Reached · {{ $percentRaw }}%
                    </div>
                    @else
                    <div class="hero-pct">{{ $percentRaw }}% funded</div>
                    @endif
                </div>
                @if($goalReached)
                <div class="overfund-note" style="color:rgba(255,255,255,.55)">
                    Goal reached! Continued contributions go toward ongoing support for this cause.
                </div>
                @endif
            </div>
        </div>
    </div>
</div>


{{-- ═══ PAGE BODY ═══ --}}
<div class="page-wrap">

    {{-- ════ LEFT COLUMN ════ --}}
    <div class="left-col">

        {{-- ── About ── --}}
        <div class="sec-card reveal">
            <div class="sec-body-pad">
                <div class="eyebrow">About this Campaign</div>
                <h2 class="sec-title">{{ $campaign->title }}</h2>
                <div class="sec-text">{!! nl2br(e($campaign->description)) !!}</div>

                <div class="mission-chips">
                    <span class="mission-chip chip-verified">
                        <span class="chip-icon"><svg viewBox="0 0 24 24" fill="none" stroke="#4338ca" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/></svg></span>Verified Campaign
                    </span>
                    <span class="mission-chip chip-transparent">
                        <span class="chip-icon"><svg viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></span>100% Transparent
                    </span>
                    <span class="mission-chip chip-secure">
                        <span class="chip-icon"><svg viewBox="0 0 24 24" fill="none" stroke="#1d4ed8" stroke-width="2.5"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg></span>Secure Payments
                    </span>
                    <span class="mission-chip chip-tax">
                        <span class="chip-icon"><svg viewBox="0 0 24 24" fill="none" stroke="#b45309" stroke-width="2.5"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg></span>80G Tax Benefit
                    </span>
                    @if($campaign->location)
                    <span class="mission-chip chip-location">
                        <span class="chip-icon"><svg viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="2.5"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg></span>{{ $campaign->location }}
                    </span>
                    @endif
                    @if($campaign->is_featured)
                    <span class="mission-chip chip-featured">
                        <span class="chip-icon"><svg viewBox="0 0 24 24" fill="none" stroke="#b45309" stroke-width="2.5"><path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg></span>Featured Campaign
                    </span>
                    @endif
                    @if($campaign->is_urgent)
                    <span class="mission-chip chip-urgent">
                        <span class="chip-icon"><svg viewBox="0 0 24 24" fill="none" stroke="#b91c1c" stroke-width="2.5"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg></span>Urgent — Act Now
                    </span>
                    @endif
                    @if($daysLeft !== null && $daysLeft <= 7)
                    <span class="mission-chip chip-ending">
                        <span class="chip-icon"><svg viewBox="0 0 24 24" fill="none" stroke="#b91c1c" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></span>Ending Soon
                    </span>
                    @endif
                </div>
            </div>

            {{-- ── Stats Strip — Total / Cash / Products ── --}}
            <div class="stats-strip">
                <div class="stat-cell">
                    <span class="stat-val">₹{{ number_format($raised) }}</span>
                    <div class="stat-lbl">Total Raised</div>
                </div>
                <div class="stat-cell stat-money">
                    <span class="stat-val">₹{{ number_format($moneyRaised) }}</span>
                    <div class="stat-lbl">Cash Donations</div>
                </div>
                <div class="stat-cell stat-product">
                    <span class="stat-val">₹{{ number_format($productRaised) }}</span>
                    <div class="stat-lbl">Product Donations</div>
                </div>
            </div>
        </div>

        {{-- ── Video ── --}}
        @if($videoUrl)
        <div class="sec-card reveal d1">
            <div class="sec-body-pad">
                <div class="eyebrow">Campaign Video</div>
                <h2 class="sec-title">Watch the <em>Story</em></h2>
                @if($videoEmbed)
                    <div class="video-wrap">
                        <iframe src="{{ $videoEmbed }}" title="Campaign video" allowfullscreen
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture">
                        </iframe>
                    </div>
                @else
                    <a href="{{ $videoUrl }}" target="_blank" rel="noopener" class="video-link-fallback">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polygon points="10 8 16 12 10 16 10 8"/></svg>
                        Watch Campaign Video
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:12px;height:12px;margin-left:auto;opacity:.5"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6M15 3h6v6M10 14L21 3"/></svg>
                    </a>
                @endif
            </div>
        </div>
        @endif

        {{-- ── Products Left Col ── --}}
        <!-- @if($products->count() > 0)
        <div class="sec-card reveal d1">
            <div class="sec-body-pad">
                <div class="eyebrow">Fundraiser Products</div>
                <h2 class="sec-title">Support by <em>Shopping</em></h2>
                <p class="sec-text" style="margin-bottom:0">Every product purchase goes directly towards the campaign goal.</p>
                <div class="products-grid" style="margin-top:20px">
                    @foreach($products as $product)
                    <div class="product-card-left">
                        <div class="product-card-left-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2zM16 3H8l-2 4h12l-2-4z"/></svg>
                        </div>
                        <div class="product-card-left-name">{{ $product->name }}</div>
                        @if($product->description)
                        <div class="product-card-left-desc">{{ $product->description }}</div>
                        @endif
                        <div class="product-card-left-footer">
                            <span class="product-price">₹{{ number_format($product->price) }}</span>
                            @if(isset($product->stock))
                                @if($product->stock > 0)
                                    <span class="product-qty-badge"><span class="product-status-dot"></span>{{ $product->stock }} left</span>
                                @else
                                    <span class="product-qty-badge out-of-stock">Sold out</span>
                                @endif
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif -->

        {{-- ── Updates ── --}}
        @if($updates->count() > 0)
        <div class="sec-card reveal d2">
            <div class="sec-body-pad">
                <div class="eyebrow">Campaign Updates</div>
                <h2 class="sec-title">Latest <em>Progress</em></h2>
                <p class="sec-text" style="margin-bottom:0">Real-time updates from the campaign team.</p>
                <div class="updates-timeline" style="margin-top:26px">
                    @foreach($updates as $i => $update)
                    <div class="update-item">
                        <div class="update-dot">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                        </div>
                        <div class="update-body">
                            <div class="update-meta">
                                <span class="update-num-badge">Update #{{ $i + 1 }}</span>
                                @if($update->created_at)
                                <span class="update-date">{{ $update->created_at->format('d M Y') }} · {{ $update->created_at->diffForHumans() }}</span>
                                @endif
                            </div>
                            <div class="update-title">{{ $update->title }}</div>
                            @if($update->body)
                            <div class="update-text">{!! nl2br(e($update->body)) !!}</div>
                            @endif
                            @if($update->document)
                            <a href="{{ asset('storage/' . $update->document) }}" target="_blank" rel="noopener" class="update-doc-link">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21.44 11.05l-9.19 9.19a6 6 0 01-8.49-8.49l9.19-9.19a4 4 0 015.66 5.66l-9.2 9.19a2 2 0 01-2.83-2.83l8.49-8.48"/></svg>
                                View Attached Document
                            </a>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        {{-- ── How Donation Works ── --}}
        <div class="dark-strip reveal d1">
            <div class="eyebrow">Simple Process</div>
            <h2 class="sec-title" style="margin-bottom:6px">How Your <em>Donation Works</em></h2>
            <p style="font-size:13px;color:rgba(255,255,255,.42);font-weight:300;line-height:1.7;max-width:500px">Every rupee is tracked from your payment to the final impact report.</p>
            <div class="hiw-steps">
                <div class="hiw-step">
                    <div class="hiw-step-num">STEP 01</div>
                    <div class="hiw-step-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg></div>
                    <div class="hiw-step-title">Choose an Amount</div>
                    <div class="hiw-step-desc">Pick from quick amounts or enter a custom figure. Every rupee matters.</div>
                </div>
                <div class="hiw-step">
                    <div class="hiw-step-num">STEP 02</div>
                    <div class="hiw-step-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg></div>
                    <div class="hiw-step-title">Pay Securely</div>
                    <div class="hiw-step-desc">UPI, card, or net banking — end-to-end encrypted via RBI-compliant gateways.</div>
                </div>
                <div class="hiw-step">
                    <div class="hiw-step-num">STEP 03</div>
                    <div class="hiw-step-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg></div>
                    <div class="hiw-step-title">Track Your Impact</div>
                    <div class="hiw-step-desc">Live updates, photo reports, and your 80G certificate — all in your inbox.</div>
                </div>
            </div>
        </div>

        {{-- ── Impact ── --}}
        <div class="sec-card reveal d2">
            <div class="sec-body-pad">
                <div class="eyebrow">Where Your Money Goes</div>
                <h2 class="sec-title">Tangible <em>Impact</em></h2>
                <div class="impact-grid">
                    <div class="impact-card">
                        <img src="{{ asset('images/donation1 (3).jpg') }}" class="impact-img" alt="Relief Kits" loading="lazy">
                        <div class="impact-body">
                            <div class="impact-title"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2z"/><path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/></svg>Relief Kits</div>
                            <div class="impact-text">Essential food, hygiene supplies, and hope for families in crisis.</div>
                        </div>
                    </div>
                    <div class="impact-card">
                        <img src="{{ asset('images/donation1 (2).jpg') }}" class="impact-img" alt="Medical Care" loading="lazy">
                        <div class="impact-body">
                            <div class="impact-title"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>Medical Care</div>
                            <div class="impact-text">Lifesaving medicines, checkups, and urgent care for those in need.</div>
                        </div>
                    </div>
                    <div class="impact-card">
                        <img src="{{ asset('images/donation1 (1).jpg') }}" class="impact-img" alt="Shelter" loading="lazy">
                        <div class="impact-body">
                            <div class="impact-title"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>Shelter</div>
                            <div class="impact-text">Safe shelter, warmth, and stability for families without a home.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Why DonateBazaar ── --}}
        <div class="sec-card reveal d2">
            <div class="sec-body-pad">
                <div class="eyebrow">6 Reasons of Assurance</div>
                <h2 class="sec-title">Why <em>DonateBazaar?</em></h2>
                @php
                $whys = [
                    ['bg'=>'#fff7ed','color'=>'#ea580c','wi'=>'#ea580c','title'=>'Product Giving',    'desc'=>'Donate products and make your impact tangible.',               'svg'=>'<path d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2z"/><path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/><line x1="12" y1="12" x2="12" y2="16"/><line x1="10" y1="14" x2="14" y2="14"/>'],
                    ['bg'=>'#f0fdf4','color'=>'#16a34a','wi'=>'#16a34a','title'=>'Verified & Trusted', 'desc'=>'100% verified charities via strict multi-step KYC process.',      'svg'=>'<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/>'],
                    ['bg'=>'#eff6ff','color'=>'#2563eb','wi'=>'#2563eb','title'=>'Guaranteed Updates','desc'=>'Regular photo and video updates sent directly to you.',            'svg'=>'<polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>'],
                    ['bg'=>'#faf5ff','color'=>'#7c3aed','wi'=>'#7c3aed','title'=>'Easy Setup',         'desc'=>'Launch a fundraiser in just a few minutes — no hassle.',          'svg'=>'<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>'],
                    ['bg'=>'#fff1f2','color'=>'#dc2626','wi'=>'#dc2626','title'=>'Secure & Private',   'desc'=>'256-bit SSL encrypted payments, your data is never stored.',      'svg'=>'<rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/>'],
                    ['bg'=>'#f0f9ff','color'=>'#0284c7','wi'=>'#0284c7','title'=>'24×7 Support',       'desc'=>'Our dedicated team is always here to help you succeed.',          'svg'=>'<path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/>'],
                ];
                @endphp
                <div class="why-grid" style="margin-top:4px">
                    @foreach($whys as $w)
                    <div class="why-item" style="--wi-color:{{ $w['wi'] }}">
                        <div class="why-icon" style="background:{{ $w['bg'] }}">
                            <svg viewBox="0 0 24 24" fill="none" stroke="{{ $w['color'] }}" stroke-width="1.8">{!! $w['svg'] !!}</svg>
                        </div>
                        <div>
                            <div class="why-name">{{ $w['title'] }}</div>
                            <div class="why-desc">{{ $w['desc'] }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ── FAQ ── --}}
        <div class="sec-card reveal d3">
            <div class="sec-body-pad">
                <div class="eyebrow">FAQs</div>
                <h2 class="sec-title">Frequently Asked <em>Questions</em></h2>
                @php
                $faqs = [
                    ['How will my donation be used?','Your donation directly supports relief activities — food, medical care, shelter, and essential supplies for people affected. Funds are disbursed in milestone-based tranches for full accountability.'],
                    ['Is my donation secure?','Absolutely. All donations use 256-bit SSL encryption and are processed through RBI-authorised, PCI-DSS compliant payment gateways. We never store your card details on our servers.'],
                    ['How do I get my 80G tax certificate?','Your 80G certificate is automatically generated and emailed to you within 24 hours of donating. It is also always available in your donor dashboard.'],
                    ['Can I track how my donation is used?','Yes. Campaign creators post regular photo and video updates. You receive notifications for every milestone and can see a full disbursement log in real time.'],
                    ['Can I set up recurring donations?','Yes — choose Weekly or Monthly giving from the donate card. No long-term commitment; you can cancel anytime from your dashboard.'],
                ];
                @endphp
                <div class="faq-list" style="margin-top:4px">
                    @foreach($faqs as $i => $faq)
                    <div class="faq-item" id="faq-{{ $i }}">
                        <div class="faq-q" onclick="toggleFaq({{ $i }})">
                            <span class="faq-q-text">{{ $faq[0] }}</span>
                            <div class="faq-chevron">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                            </div>
                        </div>
                        <div class="faq-answer" id="faq-ans-{{ $i }}">
                            <div class="faq-answer-inner">{{ $faq[1] }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ── Recent Donors ── --}}
        @if($campaign->donations && $campaign->donations->count() > 0)
        <div class="sec-card reveal d3">
            <div class="sec-body-pad">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:18px;">
                    <div>
                        <div class="eyebrow">Community Support</div>
                        <h2 class="sec-title" style="margin-bottom:0">Recent <em>Donors</em></h2>
                    </div>
                    <div style="width:46px;height:46px;border-radius:13px;background:rgba(99,102,241,.1);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#4f46e5" stroke-width="1.8" width="22" height="22"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
                    </div>
                </div>
                <div style="display:flex;flex-direction:column;gap:10px;">
                    @foreach($campaign->donations->where('payment_status','completed')->sortByDesc('created_at')->take(10) as $donation)
                    <div class="donor-row">
                        <div class="donor-row-left">
<div class="donor-avatar-new"
     style="{{ $donation->donation_type === 'product' 
               ? 'background:linear-gradient(135deg,var(--orange),var(--orange2))' 
               : '' }}">

    @php
        $user = \App\Models\User::where('email', $donation->donor_email)
                    ->orWhere('name', $donation->donor_name)
                    ->first();
        $avatar = $user?->profile_photo_path ?? $user?->avatar ?? null;
    @endphp

    @if($avatar)
        <img src="{{ Storage::url($avatar) }}"
             alt="{{ $donation->donor_name }}"
             style="width:100%;height:100%;object-fit:cover;border-radius:inherit;">
    @else
        {{ strtoupper(substr($donation->donor_name ?? 'A', 0, 1)) }}
    @endif
</div>
                            <div>
                                <div class="donor-info-name">{{ $donation->is_anonymous ? 'Anonymous Donor' : ($donation->donor_name ?? 'Anonymous') }}</div>
                                <div class="donor-info-time">{{ $donation->created_at->diffForHumans() }}</div>
                                <span class="donor-type-badge {{ $donation->donation_type === 'product' ? 'donor-type-product' : 'donor-type-money' }}">
                                    {{ $donation->donation_type === 'product' ? 'Product' : 'Cash' }}
                                </span>
                            </div>
                        </div>
                        <div>
                            <div class="donor-amount">₹{{ number_format($donation->total_amount, 2) }}</div>
                            <div class="donor-contrib">Contribution</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

    </div>{{-- /left-col --}}


    {{-- ════ RIGHT COLUMN ════ --}}
    <div class="right-col">

        {{-- ══════════════════════════════════
             ★ NEW DONATE CARD
        ══════════════════════════════════ --}}
        <div class="donate-card-new reveal-right" id="donateCardEl">

            {{-- Dark header with progress --}}
            <div class="donate-head-new">
                <div class="donate-head-new-title">Support This Cause</div>
                <div class="donate-raised-row-new">
                    <span class="donate-raised-new">₹{{ number_format($raised) }}</span>
                    <span class="donate-goal-new">of ₹{{ number_format($goal) }}</span>
                </div>
                <div class="donate-prog-track-new">
                    <div class="donate-prog-fill-new" style="width:{{ $percent }}%"></div>
                </div>
                <div class="donate-prog-meta-new">
                    @if($goalReached)
                    <span class="donate-pct-new" style="color:var(--accent2)"> Goal Reached · {{ $percentRaw }}%</span>
                    @else
                    <span class="donate-pct-new">{{ $percentRaw }}% funded</span>
                    @endif
                    <span class="donate-donors-count-new">· {{ $donors }} donors</span>
                </div>
                @if($goalReached)
                <div class="overfund-note" style="color:rgba(255,255,255,.5);position:relative;z-index:1;">
                    Extra donations beyond the goal go toward continued support for this cause.
                </div>
                @endif
                {{-- Breakdown pills --}}
                @if($moneyRaised > 0 || $productRaised > 0)
                <div class="donate-breakdown">
                    @if($moneyRaised > 0)
                    <span class="donate-breakdown-pill dbp-money">
                         Cash ₹{{ number_format($moneyRaised) }}
                    </span>
                    @endif
                    @if($productRaised > 0)
                    <span class="donate-breakdown-pill dbp-product">
                         Products ₹{{ number_format($productRaised) }}
                    </span>
                    @endif
                </div>
                @endif
            </div>

            {{-- Main tabs --}}
            <div class="main-donate-tabs">
                <button type="button" id="tabProducts"
                        class="main-donate-tab {{ $products->count() > 0 ? 'active-products' : '' }}"
                        onclick="switchMainTab('products')">Donate Products</button>
                <button type="button" id="tabMoney"
                        class="main-donate-tab {{ $products->count() === 0 ? 'active-money' : '' }}"
                        onclick="switchMainTab('money')">Donate Money</button>
            </div>

            {{-- ─── PRODUCTS PANEL ─── --}}
            <div id="panelProducts" style="{{ $products->count() === 0 ? 'display:none' : '' }}">
                <div class="panel-products">
                    @if($products->count() > 0)
                    <div class="dp-cart-bar" id="dpCartBar">
                        <span class="dp-cart-bar-items" id="dpCartItems">0 items selected</span>
                        <span>·</span>
                        <span style="font-family:var(--font-mono);font-weight:700;" id="dpCartTotal">₹0</span>
                        <span class="dp-cart-clear" onclick="clearProductCart()">Clear all</span>
                    </div>

                    <div class="dp-grid" id="dpGrid">
                        @foreach($products as $idx => $product)
                        @php
                            $qtyNeeded = $product->remaining_quantity ?? $product->stock ?? 0;
                            $totalQty  = $product->quantity ?? max($qtyNeeded, 1000);
                            $soldPct   = $totalQty > 0 ? min(100, round((($totalQty - $qtyNeeded) / $totalQty) * 100)) : 0;
                            $isFirst   = $idx === 0;
                            $isPopular = $idx === 2;
                        @endphp
                        <div class="dp-card"
                             id="dpCard_{{ $product->id }}"
                             data-id="{{ $product->id }}"
                             data-price="{{ $product->price }}"
                             data-name="{{ $product->name }}"
                             style="{{ $idx >= 6 ? 'display:none' : '' }}"
                             data-hidden="{{ $idx >= 6 ? '1' : '0' }}">

                            @if($isFirst)
                            <div class="dp-badge dp-badge-impactful visible">Most Impactful</div>
                            @elseif($isPopular)
                            <div class="dp-badge dp-badge-popular visible">Popular</div>
                            @endif

                            <div class="dp-img-wrap">
@php
    $imgUrl = $product->image
        ? asset('storage/' . $product->image)
        : optional($product->categoryProduct)->image_url;
@endphp
@if($imgUrl)
<img src="{{ $imgUrl }}"
     alt="{{ $product->name }}"
     class="dp-img" loading="lazy"
     onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
<div class="dp-img-placeholder" style="display:none;">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><path d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2zM16 3H8l-2 4h12l-2-4z"/></svg>
</div>
@else
<div class="dp-img-placeholder">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><path d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2zM16 3H8l-2 4h12l-2-4z"/></svg>
</div>
@endif
                            </div>

                            <div id="dpAddWrap_{{ $product->id }}">
                                <button type="button" class="dp-add-btn" id="dpAddBtn_{{ $product->id }}"
                                        onclick="addProductToCart({{ $product->id }})">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                    Add
                                </button>
                            </div>
                            <div class="dp-counter" id="dpCounter_{{ $product->id }}">
                                <button type="button" class="dp-minus" onclick="changeQty({{ $product->id }}, -1)">−</button>
                                <span class="dp-count" id="dpCount_{{ $product->id }}">1</span>
                                <button type="button" class="dp-plus"  onclick="changeQty({{ $product->id }}, +1)">+</button>
                            </div>

                            <div class="dp-info">
                                <div class="dp-name" onclick="toggleDpExpand({{ $product->id }})">
                                    <span class="dp-name-text">{{ $product->name }}</span>
                                    @if($product->description)
                                    <svg class="dp-expand-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                                    @endif
                                </div>
                                @if($product->description)
                                <div class="dp-expanded-desc">{{ $product->description }}</div>
                                @endif
                                @if($qtyNeeded > 0)
                                <div class="dp-qty-row">
                                    <div class="dp-qty-label">{{ number_format($qtyNeeded) }} remaining</div>
                                    <div class="dp-qty-track"><div class="dp-qty-fill" style="width:{{ $soldPct }}%"></div></div>
                                </div>
                                @endif
                                <div class="dp-price">₹{{ number_format($product->price) }}/unit</div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    @if($products->count() > 6)
                    <button type="button" class="dp-load-more" id="dpLoadMore" onclick="loadMoreProducts()">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                        Show {{ $products->count() - 6 }} more products
                    </button>
                    @endif

                    <form id="productDonateForm" action="{{ route('donate.redirect', $campaign->id) }}" method="POST" style="display:none">
                        @csrf
                        <input type="hidden" name="amount" id="productDonateAmount">
                        <input type="hidden" name="product_ids" id="productDonateIds">
                        <input type="hidden" name="product_qtys" id="productDonateQtys">
                        <input type="hidden" name="donation_type" value="products">
                    </form>

                    @else
                    <div class="empty-state">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2zM16 3H8l-2 4h12l-2-4z"/></svg>
                        <p>No products available for this campaign.</p>
                    </div>
                    @endif
                </div>

                @if($products->count() > 0)
                <div style="padding:0 14px 14px;">
                    <button type="button" class="btn-donate-new"
                            id="dpDonateBtn"
                            style="background:linear-gradient(135deg,var(--orange),var(--orange2));box-shadow:0 6px 22px rgba(249,115,22,.4);margin-top:10px;opacity:.5;cursor:not-allowed;"
                            onclick="submitProductDonation()" disabled>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
                        <span id="dpDonateBtnText">Select Products to Donate</span>
                    </button>
                </div>
                @endif
            </div>{{-- /panelProducts --}}


            {{-- ─── MONEY PANEL ─── --}}
            <div id="panelMoney" style="{{ $products->count() > 0 ? 'display:none' : '' }}">
                <div class="panel-money">
                    <div class="freq-tabs-new">
                        <button type="button" class="freq-tab-new ft-once active" onclick="switchFreq('once',this)">One-time</button>
                        <button type="button" class="freq-tab-new ft-weekly" onclick="switchFreq('weekly',this)">Weekly</button>
                        <button type="button" class="freq-tab-new ft-monthly" onclick="switchFreq('monthly',this)">Monthly</button>
                    </div>
                    <div class="freq-banner-new freq-banner-weekly-new" id="mFreqWeekly">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4m0 4h.01"/></svg>
                        Charged automatically <strong style="display:inline">&nbsp;every week</strong>. Cancel anytime.
                    </div>
                    <div class="freq-banner-new freq-banner-monthly-new" id="mFreqMonthly">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4m0 4h.01"/></svg>
                        Charged automatically <strong style="display:inline">&nbsp;every month</strong>. Cancel anytime.
                    </div>

                    @auth
                    @php
                        $activeSub = \App\Models\RecurringDonation::where('user_id', auth()->id())
                            ->where('campaign_id', $campaign->id)->where('status','active')->first();
                    @endphp
                    @if($activeSub)
                    <div class="existing-sub-new">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <div>You have an active <strong>{{ $activeSub->frequency }}</strong> donation of <strong>₹{{ number_format($activeSub->amount) }}</strong>. Next billing: {{ $activeSub->next_billing_date?->format('d M Y') ?? 'Soon' }}.</div>
                    </div>
                    @endif
                    @endauth

                    <div class="amt-grid-new">
                        @foreach([100,500,1000,2000,5000,10000,20000,50000,100000] as $amt)
                        <button type="button" class="amt-btn-new" onclick="pickAmtNew({{ $amt }},this)">
                            ₹{{ $amt >= 1000 ? number_format($amt/1000).'K' : $amt }}
                        </button>
                        @endforeach
                    </div>

                    <div class="impact-preview-new" id="impactPreviewNew">
                        <strong id="impactHeadNew">Your impact</strong>
                        <span id="impactTxtNew"></span>
                    </div>

                    <div id="mFormOnce">
                        @if(session('error'))
                        <div class="alert-error">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            {{ session('error') }}
                        </div>
                        @endif
                        <form action="{{ route('donate.redirect', $campaign->id) }}" method="POST"
                              id="donateFormOnce" onsubmit="return validateDonateForm()">
                            @csrf
                            <input type="number" id="amtOnce" name="amount"
                                   placeholder="₹ Enter custom amount"
                                   required min="1" max="500000" step="1"
                                   class="custom-input-new" oninput="syncAmtNew('once')">
                            <button type="submit" class="btn-donate-new btn-once">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
                                Donate Now
                            </button>
                        </form>
                    </div>

                    <div id="mFormWeekly" style="display:none">
                        @auth
                        <form action="{{ route('recurring.store', $campaign->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="frequency" value="weekly">
                            <input type="number" id="amtWeekly" name="amount" placeholder="₹ Amount per week" required min="10" class="custom-input-new" oninput="syncAmtNew('weekly')">
                            <button type="submit" class="btn-donate-new btn-weekly">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M17 1l4 4-4 4"/><path d="M3 11V9a4 4 0 014-4h14"/><path d="M7 23l-4-4 4-4"/><path d="M21 13v2a4 4 0 01-4 4H3"/></svg>
                                Start Weekly Donation
                            </button>
                        </form>
                        <span class="cancel-lnk">No commitment — <a onclick="alert('Cancel anytime from My Dashboard → Recurring Donations.')">cancel anytime</a></span>
                        @else
                        <input type="number" placeholder="₹ Amount per week" class="custom-input-new" disabled>
                        <div class="login-note-new">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4m0 4h.01"/></svg>
                            Please <a href="{{ route('login') }}">log in</a> to set up recurring donations.
                        </div>
                        @endauth
                    </div>

                    <div id="mFormMonthly" style="display:none">
                        @auth
                        <form action="{{ route('recurring.store', $campaign->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="frequency" value="monthly">
                            <input type="number" id="amtMonthly" name="amount" placeholder="₹ Amount per month" required min="10" class="custom-input-new" oninput="syncAmtNew('monthly')">
                            <button type="submit" class="btn-donate-new btn-monthly">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M17 1l4 4-4 4"/><path d="M3 11V9a4 4 0 014-4h14"/><path d="M7 23l-4-4 4-4"/><path d="M21 13v2a4 4 0 01-4 4H3"/></svg>
                                Start Monthly Donation
                            </button>
                        </form>
                        <span class="cancel-lnk">No commitment — <a onclick="alert('Cancel anytime from My Dashboard → Recurring Donations.')">cancel anytime</a></span>
                        @else
                        <input type="number" placeholder="₹ Amount per month" class="custom-input-new" disabled>
                        <div class="login-note-new">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4m0 4h.01"/></svg>
                            Please <a href="{{ route('login') }}">log in</a> to set up recurring donations.
                        </div>
                        @endauth
                    </div>
                </div>

                <div class="money-panel-foot">
                    <div class="trust-row-new">
                        <span class="trust-item-new"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>Secure</span>
                        <span class="trust-sep-new"></span>
                        <span class="trust-item-new"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>Verified</span>
                        <span class="trust-sep-new"></span>
                        <span class="trust-item-new">80G Eligible</span>
                        <span class="trust-sep-new"></span>
                        <span class="trust-item-new">RBI Compliant</span>
                    </div>
                </div>
            </div>{{-- /panelMoney --}}

        </div>{{-- /donate-card-new --}}

        {{-- ── Campaign Details Card ── --}}
        <div class="share-card reveal-right d1">
            <div class="share-card-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                Campaign Details
            </div>
            <div style="display:flex;flex-direction:column;gap:10px;">
                @if($campaign->start_date || $campaign->end_date)
                <div class="details-row">
                    <svg viewBox="0 0 24 24" fill="none" stroke="var(--text3)" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    <span>
                        @if($campaign->start_date){{ \Carbon\Carbon::parse($campaign->start_date)->format('d M Y') }}@endif
                        @if($campaign->start_date && $campaign->end_date) → @endif
                        @if($campaign->end_date){{ \Carbon\Carbon::parse($campaign->end_date)->format('d M Y') }}@endif
                    </span>
                </div>
                @endif
                @if($campaign->location)
                <div class="details-row">
                    <svg viewBox="0 0 24 24" fill="none" stroke="var(--text3)" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    <span>{{ $campaign->location }}</span>
                </div>
                @endif
                <div class="details-row">
                    <svg viewBox="0 0 24 24" fill="none" stroke="var(--text3)" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    <span>By {{ $campaign->user->name ?? 'DonateBazaar' }}</span>
                </div>
                <div class="details-row">
                    <svg viewBox="0 0 24 24" fill="none" stroke="var(--text3)" stroke-width="2"><path d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/></svg>
                    <span>{{ $campaign->category->name ?? 'General' }}</span>
                </div>
                @if($updates->count() > 0)
                <div class="details-row">
                    <svg viewBox="0 0 24 24" fill="none" stroke="var(--text3)" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    <span>{{ $updates->count() }} update{{ $updates->count() !== 1 ? 's' : '' }} posted</span>
                </div>
                @endif
            </div>
        </div>

        {{-- ── Share ── --}}
        <div class="share-card reveal-right d2">
            <div class="share-card-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 12v8a2 2 0 002 2h12a2 2 0 002-2v-8"/><polyline points="16 6 12 2 8 6"/><line x1="12" y1="2" x2="12" y2="15"/></svg>
                Spread the Word
            </div>
            <button onclick="shareCampaign()" class="share-btn">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
                Share This Campaign
            </button>
        </div>

        {{-- ── Ask Update ── --}}
        <div class="action-card reveal-right d3">
            <h4>Want a Campaign Update?</h4>
            <p>Ask the campaign creator for the latest news, photos, or impact reports directly.</p>
            <a href="{{ route('contact', ['campaign' => $campaign->title]) }}" class="action-btn">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
                Ask for Update
            </a>
        </div>

    </div>{{-- /right-col --}}

</div>{{-- /page-wrap --}}


{{-- ═══ STICKY BOTTOM BAR ═══ --}}
<div class="sticky-donate-bar" id="stickyBar">
    <div class="sdb-ticker">
        <div class="sdb-ticker-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
        </div>
        <div class="sdb-ticker-text" id="sdbText">
            @if($latestDonation)
                <strong>₹{{ number_format($latestDonation->total_amount) }}</strong> donated by
                {{ $latestDonation->is_anonymous ? 'Anonymous' : ($latestDonation->donor_name ?? 'Anonymous') }}
                · {{ $latestDonation->created_at->diffForHumans() }}
            @else
                Be the <strong>first donor</strong> — make an impact today!
            @endif
        </div>
    </div>
    <div class="sdb-right">
        <button type="button" class="sdb-btn" id="sdbDonateBtn" onclick="scrollToDonate()">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
            <span id="sdbBtnLabel">Donate Now</span>
        </button>
        <button type="button" class="sdb-share-btn" onclick="shareCampaign()" aria-label="Share">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
        </button>
    </div>
</div>

<button class="scroll-top" id="scrollTopBtn" onclick="window.scrollTo({top:0,behavior:'smooth'})" aria-label="Scroll to top">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 15l-6-6-6 6"/></svg>
</button>


<script>
(function(){
    var els = document.querySelectorAll('.reveal,.reveal-left,.reveal-right');
    var obs = new IntersectionObserver(function(entries){
        entries.forEach(function(e){
            if(e.isIntersecting){ e.target.classList.add('visible'); obs.unobserve(e.target); }
        });
    },{ threshold:0.07, rootMargin:'0px 0px -28px 0px' });
    els.forEach(function(el){ obs.observe(el); });
})();

window.addEventListener('DOMContentLoaded', function(){
    var fills = document.querySelectorAll('.hero-progress-fill,.donate-prog-fill-new');
    fills.forEach(function(el){
        var w = el.style.width; el.style.width='0%';
        setTimeout(function(){ el.style.width = w; }, 500);
    });
});

var scrollTopBtn = document.getElementById('scrollTopBtn');
window.addEventListener('scroll', function(){
    scrollTopBtn.classList.toggle('visible', window.scrollY > 600);
}, {passive:true});

(function(){
    var bar    = document.getElementById('stickyBar');
    var btnLbl = document.getElementById('sdbBtnLabel');
    var shown  = false;
    window._sdbTotal = 0;

    function update(){
        var scrollY    = window.scrollY;
        var heroH      = document.querySelector('.hero')?.offsetHeight || 500;
        var shouldShow = scrollY > heroH * 0.6;
        if(shouldShow !== shown){ bar.classList.toggle('visible', shouldShow); shown = shouldShow; }
        if(window._sdbTotal > 0){
            btnLbl.textContent = 'Donate Now (₹' + window._sdbTotal.toLocaleString('en-IN') + ')';
        } else {
            var amtOnce = parseFloat(document.getElementById('amtOnce')?.value) || 0;
            btnLbl.textContent = amtOnce > 0 ? 'Donate Now (₹' + amtOnce.toLocaleString('en-IN') + ')' : 'Donate Now';
        }
    }
    window.addEventListener('scroll', update, {passive:true});
    update();
})();

function scrollToDonate(){
    var card = document.getElementById('donateCardEl');
    if(card){
        card.scrollIntoView({ behavior:'smooth', block:'center' });
        card.style.boxShadow = '0 0 0 3px rgba(249,115,22,.5), var(--shadow-lg)';
        setTimeout(function(){ card.style.boxShadow = ''; }, 1800);
    }
}

function switchMainTab(tab){
    var tabProducts   = document.getElementById('tabProducts');
    var tabMoney      = document.getElementById('tabMoney');
    var panelProducts = document.getElementById('panelProducts');
    var panelMoney    = document.getElementById('panelMoney');
    if(tab === 'products'){
        tabProducts.className = 'main-donate-tab active-products';
        tabMoney.className    = 'main-donate-tab';
        panelProducts.style.display = '';
        panelMoney.style.display    = 'none';
    } else {
        tabMoney.className    = 'main-donate-tab active-money';
        tabProducts.className = 'main-donate-tab';
        panelMoney.style.display    = '';
        panelProducts.style.display = 'none';
    }
}

var productCart = {};

function addProductToCart(id){
    var card    = document.getElementById('dpCard_' + id);
    var addWrap = document.getElementById('dpAddWrap_' + id);
    var counter = document.getElementById('dpCounter_' + id);
    var price   = parseFloat(card.dataset.price) || 0;
    var name    = card.dataset.name;
    if(!productCart[id]){ productCart[id] = { qty:1, price:price, name:name }; } else { productCart[id].qty++; }
    addWrap.style.display = 'none';
    counter.classList.add('show');
    document.getElementById('dpCount_' + id).textContent = productCart[id].qty;
    card.classList.add('in-cart');
    updateProductCartUI();
}

function changeQty(id, delta){
    if(!productCart[id]) return;
    productCart[id].qty += delta;
    if(productCart[id].qty <= 0){
        delete productCart[id];
        document.getElementById('dpAddWrap_' + id).style.display = '';
        document.getElementById('dpCounter_' + id).classList.remove('show');
        document.getElementById('dpCard_' + id).classList.remove('in-cart');
    } else {
        document.getElementById('dpCount_' + id).textContent = productCart[id].qty;
    }
    updateProductCartUI();
}

function updateProductCartUI(){
    var totalItems = 0, totalAmt = 0;
    Object.values(productCart).forEach(function(p){ totalItems += p.qty; totalAmt += p.qty * p.price; });
    var bar    = document.getElementById('dpCartBar');
    var itemEl = document.getElementById('dpCartItems');
    var totEl  = document.getElementById('dpCartTotal');
    var btn    = document.getElementById('dpDonateBtn');
    var btnTxt = document.getElementById('dpDonateBtnText');
    if(totalItems > 0){
        bar.classList.add('show');
        itemEl.textContent = totalItems + ' item' + (totalItems !== 1 ? 's' : '') + ' selected';
        totEl.textContent  = '₹' + totalAmt.toLocaleString('en-IN');
        btn.disabled = false; btn.style.opacity = '1'; btn.style.cursor = 'pointer';
        btnTxt.textContent = 'Donate Now (₹' + totalAmt.toLocaleString('en-IN') + ')';
    } else {
        bar.classList.remove('show');
        btn.disabled = true; btn.style.opacity = '.5'; btn.style.cursor = 'not-allowed';
        btnTxt.textContent = 'Select Products to Donate';
    }
    window._sdbTotal = totalItems > 0 ? totalAmt : 0;
}

function clearProductCart(){
    Object.keys(productCart).forEach(function(id){
        delete productCart[id];
        var aw = document.getElementById('dpAddWrap_' + id);
        var ct = document.getElementById('dpCounter_' + id);
        var cd = document.getElementById('dpCard_' + id);
        if(aw) aw.style.display = '';
        if(ct) ct.classList.remove('show');
        if(cd) cd.classList.remove('in-cart');
    });
    updateProductCartUI();
}

function submitProductDonation(){
    if(Object.keys(productCart).length === 0) return;
    var total = Object.values(productCart).reduce(function(s,p){ return s + p.qty * p.price; }, 0);
    var ids   = Object.keys(productCart).join(',');
    var qtys  = Object.values(productCart).map(function(p){ return p.qty; }).join(',');
    document.getElementById('productDonateAmount').value = total;
    document.getElementById('productDonateIds').value    = ids;
    document.getElementById('productDonateQtys').value   = qtys;
    document.getElementById('productDonateForm').submit();
}

function toggleDpExpand(id){
    document.getElementById('dpCard_' + id).classList.toggle('expanded');
}

function loadMoreProducts(){
    document.querySelectorAll('.dp-card[data-hidden="1"]').forEach(function(el){ el.style.display=''; el.dataset.hidden='0'; });
    var btn = document.getElementById('dpLoadMore');
    if(btn) btn.style.display = 'none';
}

var currentFreq = 'once';

function switchFreq(type, tabEl){
    currentFreq = type;
    document.querySelectorAll('.freq-tab-new').forEach(function(t){ t.classList.remove('active'); });
    tabEl.classList.add('active');
    ['Once','Weekly','Monthly'].forEach(function(t){
        var el = document.getElementById('mForm' + t);
        if(el) el.style.display = (t.toLowerCase() === type) ? 'block' : 'none';
    });
    document.getElementById('mFreqWeekly')?.classList.toggle('show', type === 'weekly');
    document.getElementById('mFreqMonthly')?.classList.toggle('show', type === 'monthly');
    document.querySelectorAll('.amt-btn-new').forEach(function(b){ b.classList.remove('active'); });
    document.getElementById('impactPreviewNew')?.classList.remove('show');
}

var impactMap = [
    {min:10,   max:99,       text:'buys a nutritious meal for a child in need.'},
    {min:100,  max:499,      text:'provides school stationery for one student for a month.'},
    {min:500,  max:999,      text:'covers basic medicines for a family for two weeks.'},
    {min:1000, max:4999,     text:'sponsors a full medical checkup for one person.'},
    {min:5000, max:9999,     text:'equips an entire classroom with learning materials.'},
    {min:10000,max:49999,    text:'helps provide emergency shelter for a displaced family.'},
    {min:50000,max:Infinity, text:'funds comprehensive relief for 10 families for a month.'},
];

function pickAmtNew(amt, btn){
    var inputMap = {once:'amtOnce', weekly:'amtWeekly', monthly:'amtMonthly'};
    var inputEl  = document.getElementById(inputMap[currentFreq]);
    if(inputEl) inputEl.value = amt;
    document.querySelectorAll('.amt-btn-new').forEach(function(b){ b.classList.remove('active'); });
    btn.classList.add('active');
    showImpactNew(amt);
}

function syncAmtNew(type){
    var inputMap = {once:'amtOnce', weekly:'amtWeekly', monthly:'amtMonthly'};
    var val = parseFloat(document.getElementById(inputMap[type])?.value) || 0;
    document.querySelectorAll('.amt-btn-new').forEach(function(b){ b.classList.remove('active'); });
    showImpactNew(val);
}

function showImpactNew(amount){
    var preview = document.getElementById('impactPreviewNew');
    var head    = document.getElementById('impactHeadNew');
    var txt     = document.getElementById('impactTxtNew');
    if(!preview) return;
    if(!amount || amount < 10){ preview.classList.remove('show'); return; }
    var match = impactMap.find(function(m){ return amount >= m.min && amount <= m.max; });
    if(match){
        var prefix = currentFreq === 'weekly' ? 'Every week, ₹' : currentFreq === 'monthly' ? 'Every month, ₹' : '₹';
        head.textContent = 'Your impact';
        txt.textContent  = prefix + Number(amount).toLocaleString('en-IN') + ' ' + match.text;
        preview.classList.add('show');
    } else {
        preview.classList.remove('show');
    }
}

function validateDonateForm(){
    var input = document.getElementById('amtOnce');
    if(!input) return true;
    var val = parseFloat(input.value);
    if(!val || val < 1){
        input.focus();
        input.style.borderColor = 'var(--red)';
        input.style.boxShadow   = '0 0 0 3px rgba(239,68,68,.15)';
        setTimeout(function(){ input.style.borderColor=''; input.style.boxShadow=''; }, 2000);
        return false;
    }
    return true;
}

function toggleFaq(idx){
    var item   = document.getElementById('faq-' + idx);
    if(!item) return;
    var isOpen = item.classList.contains('open');
    document.querySelectorAll('.faq-item').forEach(function(i){ i.classList.remove('open'); });
    if(!isOpen){ item.classList.add('open'); }
}

document.addEventListener('DOMContentLoaded', function(){
    document.querySelectorAll('.faq-q').forEach(function(q){
        q.addEventListener('keydown', function(e){
            if(e.key==='Enter'||e.key===' '){ e.preventDefault(); q.click(); }
        });
        q.setAttribute('role','button');
        q.setAttribute('tabindex','0');
    });
});

function shareCampaign(){
    var title = '{{ addslashes($campaign->title) }}';
    var url   = window.location.href;
    if(navigator.share){
        navigator.share({ title:title, url:url }).catch(function(){});
    } else {
        navigator.clipboard.writeText(url).then(function(){
            var btn  = event.currentTarget;
            var orig = btn.innerHTML;
            btn.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg> Copied!';
            btn.style.color = 'var(--green)'; btn.style.borderColor = 'var(--green)';
            setTimeout(function(){ btn.innerHTML=orig; btn.style.color=''; btn.style.borderColor=''; }, 2000);
        }).catch(function(){ alert('Copy this link: ' + url); });
    }
}
</script>

@endsection