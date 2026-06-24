@extends('layouts.app')

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300&family=DM+Mono:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,700;1,700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
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
[data-theme="dark"] {
    --bg:       #0b0c14;
    --surface:  #13141f;
    --surface2: #1a1b2e;
    --surface3: #1e1f35;
    --border:   rgba(255,255,255,0.06);
    --border2:  rgba(255,255,255,0.10);
    --text:     #f0f1ff;
    --text2:    #a5b4c8;
    --text3:    #5a6579;
    --accent-glow: rgba(99,102,241,0.25);
    --shadow:   0 1px 3px rgba(0,0,0,0.3),0 4px 16px rgba(0,0,0,0.2);
    --shadow-lg:0 8px 40px rgba(0,0,0,0.5);
}

*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth}
body{font-family:var(--font);color:var(--text);background:var(--bg);-webkit-font-smoothing:antialiased;overflow-x:hidden}
img{max-width:100%;display:block}
a{text-decoration:none;color:inherit}

/* ── Layout ── */
.container{max-width:1180px;margin:0 auto;padding:0 24px}
.container--wide{max-width:1320px;margin:0 auto;padding:0 24px}

/* ── Typography ── */
.eyebrow{font-size:11px;font-weight:600;letter-spacing:.14em;text-transform:uppercase;color:var(--accent);font-family:var(--font-mono);display:inline-flex;align-items:center;gap:8px;margin-bottom:14px}
.eyebrow::before{content:'';width:20px;height:2px;background:var(--accent);border-radius:2px;flex-shrink:0}
.section-title{font-family:var(--font-display);font-size:clamp(2rem,3.5vw,2.8rem);font-weight:600; text-transform: capitalize; text-traline-height:1.15;color:var(--text);margin-bottom:16px}
.section-title em{font-style:normal;color:var(--accent)}
.section-sub{font-size:15px;color:var(--text3);font-weight:300;line-height:1.75;max-width:560px;font-family:var(--font)}
.section-header{text-align:center;margin-bottom:64px}
.section-header .section-sub{margin:0 auto}

/* ── Buttons ── */
.btn{display:inline-flex;align-items:center;gap:8px;padding:13px 28px;border-radius:var(--radius);font-weight:600;font-size:14px;font-family:var(--font);transition:all var(--transition);border:none;cursor:pointer;white-space:nowrap}
.btn svg{width:16px;height:16px;flex-shrink:0;transition:transform var(--transition)}
.btn:hover svg{transform:translateX(2px)}
.btn-white{background:#fff;color:#1e1b4b;box-shadow:0 4px 20px rgba(0,0,0,0.15)}
.btn-white:hover{transform:translateY(-2px);box-shadow:0 12px 32px rgba(0,0,0,0.2)}
.btn-accent{background:linear-gradient(135deg,var(--accent),var(--accent2));color:#fff;box-shadow:0 6px 24px rgba(99,102,241,0.45)}
.btn-accent:hover{transform:translateY(-2px);box-shadow:0 12px 32px rgba(99,102,241,0.55);opacity:.94}
.btn-outline{background:transparent;color:#fff;border:1.5px solid rgba(255,255,255,0.35);backdrop-filter:blur(8px)}
.btn-outline:hover{background:rgba(255,255,255,0.1);border-color:rgba(255,255,255,0.6)}

/* ── Reveal animations ── */
.reveal{opacity:0;transform:translateY(32px);transition:opacity .7s ease,transform .7s ease}
.reveal.visible{opacity:1;transform:translateY(0)}
.reveal-left{opacity:0;transform:translateX(-32px);transition:opacity .7s ease,transform .7s ease}
.reveal-left.visible{opacity:1;transform:translateX(0)}
.reveal-right{opacity:0;transform:translateX(32px);transition:opacity .7s ease,transform .7s ease}
.reveal-right.visible{opacity:1;transform:translateX(0)}
.reveal-scale{opacity:0;transform:scale(0.92);transition:opacity .65s ease,transform .65s ease}
.reveal-scale.visible{opacity:1;transform:scale(1)}
.d1{transition-delay:.1s}.d2{transition-delay:.2s}.d3{transition-delay:.3s}.d4{transition-delay:.4s}.d5{transition-delay:.5s}.d6{transition-delay:.6s}

/* ═══════════════════════════════════════════════════════════
   1. HERO
═══════════════════════════════════════════════════════════ */
.hero{position:relative;width:100%;min-height:100vh;overflow:hidden;display:flex;flex-direction:column}
.hero-bg{position:absolute;inset:0;z-index:0}
.hero-bg img{width:100%;height:100%;object-fit:cover;object-position:center}
.hero-overlay{position:absolute;inset:0;z-index:1;background:linear-gradient(110deg,rgba(5,5,20,.92) 0%,rgba(10,10,35,.82) 45%,rgba(15,15,40,.5) 100%)}

/* Animated grid lines */
.hero-grid{position:absolute;inset:0;z-index:1;background-image:linear-gradient(rgba(99,102,241,.06) 1px,transparent 1px),linear-gradient(90deg,rgba(99,102,241,.06) 1px,transparent 1px);background-size:60px 60px;opacity:.6}

.hero-inner{position:relative;z-index:2;display:flex;flex-direction:column;min-height:100vh}
.hero-content{flex:1;display:flex;flex-direction:column;justify-content:center;max-width:1180px;margin:0 auto;padding:120px 24px 160px;width:100%}

.hero-pill{display:inline-flex;align-items:center;gap:10px;background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.18);backdrop-filter:blur(12px);border-radius:100px;padding:8px 20px;font-size:11.5px;font-weight:600;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,.8);width:fit-content;margin-bottom:28px;font-family:var(--font-mono)}
.hero-pill-dot{width:7px;height:7px;border-radius:50%;background:var(--green);animation:pulse-live 2s ease infinite;flex-shrink:0}
@keyframes pulse-live{0%,100%{opacity:1;box-shadow:0 0 0 0 rgba(16,185,129,.5)}50%{opacity:.8;box-shadow:0 0 0 6px rgba(16,185,129,0)}}

.hero-title{font-family:var(--font-display);font-size:clamp(2.8rem,5.5vw,4.2rem);font-weight:500;line-height:1.2;color:#fff;margin-bottom:24px;max-width:720px}
.hero-title em{font-style:normal;color:#a5b4fc}
.hero-title .line2{display:block;color:rgba(255,255,255,.65);font-size:.75em}

.hero-desc{font-size:clamp(15px,1.8vw,17px);color:rgba(255,255,255,.65);font-weight:300;line-height:1.8;max-width:520px;margin-bottom:40px;font-family:var(--font)}

.hero-btns{display:flex;gap:12px;flex-wrap:wrap;margin-bottom:56px}

/* Trust signals row */
.hero-trust{display:flex;align-items:center;gap:24px;flex-wrap:wrap}
.hero-trust-item{display:flex;align-items:center;gap:8px;font-size:12.5px;color:rgba(255,255,255,.55);font-family:var(--font)}
.hero-trust-item svg{width:14px;height:14px;color:var(--green)}
.hero-trust-sep{width:1px;height:16px;background:rgba(255,255,255,.15)}

/* Floating cards */
.hero-float-cards{position:absolute;right:5%;top:50%;transform:translateY(-50%);display:flex;flex-direction:column;gap:12px;z-index:2}
@media(max-width:1024px){.hero-float-cards{display:none}}
.hero-float-card{background:rgba(255,255,255,.09);border:1px solid rgba(255,255,255,.15);backdrop-filter:blur(20px);border-radius:var(--radius);padding:16px 20px;min-width:190px;animation:float-card 4s ease-in-out infinite}
.hero-float-card:nth-child(2){animation-delay:1.3s}
.hero-float-card:nth-child(3){animation-delay:2.6s}
@keyframes float-card{0%,100%{transform:translateY(0)}50%{transform:translateY(-8px)}}
.float-card-top{display:flex;align-items:center;gap:8px;margin-bottom:6px}
.float-card-icon{width:28px;height:28px;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.float-card-icon svg{width:14px;height:14px}
.float-card-label{font-size:10.5px;color:rgba(255,255,255,.5);font-family:var(--font-mono)}
.float-card-num{font-size:22px;font-weight:700;color:#fff;font-family:var(--font-mono);line-height:1}
.float-card-sub{font-size:10px;color:rgba(255,255,255,.4);margin-top:2px}

/* Bottom stat bar */
.hero-stat-bar{background:rgba(5,5,18,.92);backdrop-filter:blur(20px);border-top:1px solid rgba(255,255,255,.07);display:flex}
.hero-stat-item{flex:1;padding:22px 20px;text-align:center;border-left:1px solid rgba(255,255,255,.06)}
.hero-stat-item:first-child{border-left:none}
.hero-stat-val{font-family:var(--font-mono);font-size:clamp(18px,2.2vw,26px);color:#fff;display:block;font-weight:700;line-height:1;margin-bottom:5px}
.hero-stat-lbl{font-size:10px;letter-spacing:1.4px;text-transform:uppercase;color:rgba(255,255,255,.35);font-family:var(--font-mono)}
@media(max-width:600px){.hero-stat-bar{flex-wrap:wrap}.hero-stat-item{flex:1 1 50%;border-top:1px solid rgba(255,255,255,.06)}.hero-stat-item:nth-child(odd){border-left:none}}

/* ═══════════════════════════════════════════════════════════
   2. MARQUEE
═══════════════════════════════════════════════════════════ */
.marquee-band{background:#07080f;overflow:hidden;padding:0;border-top:1px solid rgba(255,255,255,.04);border-bottom:1px solid rgba(255,255,255,.04)}
.marquee-inner{display:flex;width:max-content;animation:marquee 28s linear infinite}
.marquee-inner:hover{animation-play-state:paused}
.marquee-row{display:flex;padding:14px 0}
.m-item{display:inline-flex;align-items:center;gap:10px;padding:0 36px;font-size:11px;font-weight:600;color:rgba(165,180,252,.6);letter-spacing:.12em;text-transform:uppercase;font-family:var(--font-mono);white-space:nowrap}
.m-dot{width:4px;height:4px;border-radius:50%;background:var(--accent2);flex-shrink:0}
@keyframes marquee{0%{transform:translateX(0)}100%{transform:translateX(-50%)}}

/* ═══════════════════════════════════════════════════════════
   3. MEDIA LOGOS (Trust signal — "As seen in")
═══════════════════════════════════════════════════════════ */
.media-section{background:var(--surface);padding:48px 0;border-bottom:1px solid var(--border)}
.media-inner{display:flex;align-items:center;gap:40px;flex-wrap:wrap;justify-content:center}
.media-label{font-size:11px;font-weight:600;letter-spacing:.12em;text-transform:uppercase;color:var(--text3);font-family:var(--font-mono);flex-shrink:0}
.media-divider{width:1px;height:28px;background:var(--border2);flex-shrink:0}
.media-logos{display:flex;align-items:center;gap:36px;flex-wrap:wrap;justify-content:center}
.media-logo{font-family:var(--font-display);font-size:16px;font-weight:700;color:var(--text3);opacity:.5;transition:opacity var(--transition);letter-spacing:-.02em;cursor:default}
.media-logo:hover{opacity:.85}
@media(max-width:640px){.media-divider{display:none}.media-label{width:100%;text-align:center}}

/* ═══════════════════════════════════════════════════════════
   4. PURPOSE / MISSION
═══════════════════════════════════════════════════════════ */
.purpose-section{background:var(--surface);padding:110px 0 80px}
.purpose-grid{display:grid;grid-template-columns:1fr 1fr;gap:80px;align-items:start}
@media(max-width:900px){.purpose-grid{grid-template-columns:1fr;gap:40px}}

.purpose-sticky{position:sticky;top:100px}
@media(max-width:900px){.purpose-sticky{position:static}}
.purpose-sticky .eyebrow{margin-bottom:20px}
.purpose-headline{font-family:var(--font-display);font-size:clamp(2rem,3vw,2.8rem);font-weight:600; text-transform: capitalize; line-height:1.2;color:var(--text);margin-bottom:24px}
.purpose-headline em{font-style:normal;color:var(--accent)}
.purpose-quote{border-left:3px solid var(--accent);padding:16px 20px;background:var(--accent-glow);border-radius:0 var(--radius-sm) var(--radius-sm) 0;margin-top:28px}
.purpose-quote p{font-size:14px;font-style:italic;color:var(--text2);line-height:1.7;font-family:var(--font)}
.purpose-quote cite{font-size:12px;color:var(--text3);font-style:normal;font-family:var(--font-mono);margin-top:8px;display:block}

.purpose-body p{font-size:14.5px;line-height:1.85;color:var(--text2);font-weight:300;margin-bottom:20px}
.purpose-body p:last-of-type{margin-bottom:0}

.chip-grid{display:flex;flex-wrap:wrap;gap:9px;margin-top:28px}
.chip{display:inline-flex;align-items:center;gap:7px;padding:8px 16px;border-radius:100px;font-size:12.5px;font-weight:500;background:var(--surface2);color:var(--text2);border:1px solid var(--border2);font-family:var(--font);transition:all var(--transition)}
.chip:hover{border-color:var(--accent);color:var(--accent);background:var(--accent-glow)}

/* ═══════════════════════════════════════════════════════════
   5. ANIMATED STATS STRIP
═══════════════════════════════════════════════════════════ */
.stats-strip{background:linear-gradient(160deg,#07080f 0%,#0d0e1a 50%,#13141f 100%);position:relative;overflow:hidden}
.stats-strip::before{content:'';position:absolute;top:-200px;left:-100px;width:600px;height:600px;border-radius:50%;background:radial-gradient(circle,rgba(99,102,241,.07) 0%,transparent 70%);pointer-events:none}
.stats-inner{max-width:1180px;margin:0 auto;padding:0 24px;display:grid;grid-template-columns:repeat(4,1fr)}
@media(max-width:700px){.stats-inner{grid-template-columns:repeat(2,1fr)}}
.stat-tile{padding:60px 32px;text-align:center;border-left:1px solid rgba(255,255,255,.06);position:relative;overflow:hidden;cursor:default}
.stat-tile:first-child{border-left:none}
@media(max-width:700px){.stat-tile:nth-child(odd){border-left:none}.stat-tile{border-top:1px solid rgba(255,255,255,.06)}.stat-tile:first-child,.stat-tile:nth-child(2){border-top:none}}
.stat-tile::after{content:'';position:absolute;top:0;left:50%;right:50%;height:2px;background:linear-gradient(90deg,var(--accent),var(--accent2));transition:left .4s ease,right .4s ease}
.stat-tile:hover::after{left:0;right:0}
.stat-tile-icon{width:44px;height:44px;border-radius:12px;background:rgba(99,102,241,.1);border:1px solid rgba(99,102,241,.2);display:flex;align-items:center;justify-content:center;margin:0 auto 16px}
.stat-tile-icon svg{width:20px;height:20px;color:#a5b4fc}
.stat-tile-num{font-family:var(--font-mono);font-size:clamp(36px,4vw,52px);font-weight:700;color:#fff;display:block;line-height:1;margin-bottom:10px}
.stat-tile-num .suf{color:var(--accent)}
.stat-tile-lbl{font-size:10.5px;letter-spacing:1.4px;text-transform:uppercase;color:rgba(255,255,255,.35);font-family:var(--font-mono)}
.stat-tile-sub{font-size:12px;color:rgba(255,255,255,.2);margin-top:5px;font-family:var(--font)}

/* ═══════════════════════════════════════════════════════════
   6. JOURNEY / TIMELINE
═══════════════════════════════════════════════════════════ */
.journey-section{background:var(--bg);padding:110px 0}
.journey-grid{display:grid;grid-template-columns:1fr 1fr;gap:72px;align-items:center}
@media(max-width:900px){.journey-grid{grid-template-columns:1fr}}

.journey-img-wrap{position:relative;border-radius:var(--radius-lg);overflow:visible}
.journey-img-inner{border-radius:var(--radius-lg);overflow:hidden;box-shadow:var(--shadow-xl)}
.journey-img-inner img{width:100%;height:420px;object-fit:cover;transition:transform .6s ease}
.journey-img-wrap:hover .journey-img-inner img{transform:scale(1.04)}
.journey-badge{position:absolute;bottom:-20px;right:-20px;background:linear-gradient(135deg,var(--accent),var(--accent2));color:#fff;border-radius:var(--radius);padding:18px 24px;box-shadow:0 16px 40px rgba(99,102,241,.45)}
.journey-badge strong{display:block;font-size:28px;font-family:var(--font-mono);line-height:1;margin-bottom:3px}
.journey-badge span{font-size:11px;opacity:.8;font-family:var(--font)}

/* Floating mini-card on image */
.journey-mini-card{position:absolute;top:20px;left:-20px;background:var(--surface);border:1px solid var(--border2);border-radius:var(--radius);padding:14px 18px;box-shadow:var(--shadow-lg);display:flex;align-items:center;gap:12px;min-width:180px}
.journey-mini-card-icon{width:36px;height:36px;border-radius:10px;background:rgba(16,185,129,.1);display:flex;align-items:center;justify-content:center;flex-shrink:0}
.journey-mini-card-icon svg{width:18px;height:18px;color:var(--green)}
.journey-mini-card-num{font-size:18px;font-weight:700;color:var(--text);font-family:var(--font-mono);line-height:1}
.journey-mini-card-lbl{font-size:11px;color:var(--text3);margin-top:2px}
@media(max-width:640px){.journey-mini-card,.journey-badge{display:none}}

.journey-text .eyebrow{margin-bottom:20px}
.journey-text h2{font-family:var(--font-display); text-transform: capitalize; font-size:clamp(1.9rem,3vw,2.5rem);font-weight:600;line-height:1.2;color:var(--text);margin-bottom:20px}
.journey-text h2 em{font-style:normal;color:var(--accent)}
.journey-text p{font-size:14.5px;line-height:1.85;color:var(--text2);font-weight:300;margin-bottom:16px}

/* Timeline */
.timeline{margin-top:36px}
.tl-item{display:flex;gap:16px;padding-bottom:28px;position:relative}
.tl-item::before{content:'';position:absolute;left:15px;top:30px;bottom:0;width:1px;background:linear-gradient(to bottom,var(--accent),transparent)}
.tl-item:last-child::before{display:none}
.tl-dot{width:32px;height:32px;border-radius:50%;flex-shrink:0;display:flex;align-items:center;justify-content:center;margin-top:1px;position:relative;z-index:1;transition:transform var(--transition)}
.tl-dot.done{background:rgba(99,102,241,.12);border:2px solid var(--accent)}
.tl-dot.active{background:linear-gradient(135deg,var(--accent),var(--accent2));box-shadow:0 0 0 6px rgba(99,102,241,.15)}
.tl-dot svg{width:12px;height:12px}
.tl-item:hover .tl-dot{transform:scale(1.15)}
.tl-year{font-size:10.5px;font-weight:700;color:var(--accent);font-family:var(--font-mono);margin-bottom:2px}
.tl-title{font-size:14px;font-weight:600;color:var(--text);margin-bottom:3px;font-family:var(--font)}
.tl-desc{font-size:12.5px;color:var(--text3);font-family:var(--font);line-height:1.6}

/* ═══════════════════════════════════════════════════════════
   7. HOW IT WORKS
═══════════════════════════════════════════════════════════ */
.how-section{padding:110px 0;background:linear-gradient(160deg,#07080f 0%,#0d0e1a 50%,#13141f 100%);position:relative;overflow:hidden}
.how-section::before{content:'';position:absolute;top:-200px;left:-150px;width:700px;height:700px;border-radius:50%;background:radial-gradient(circle,rgba(99,102,241,.09) 0%,transparent 70%);pointer-events:none}
.how-section::after{content:'';position:absolute;bottom:-150px;right:-100px;width:600px;height:600px;border-radius:50%;background:radial-gradient(circle,rgba(139,92,246,.07) 0%,transparent 70%);pointer-events:none}

.how-section .section-title{color:#fff}
.how-section .section-sub{color:rgba(255,255,255,.45)}

.how-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:22px;position:relative;z-index:1}
@media(max-width:768px){.how-grid{grid-template-columns:1fr}}

.how-card-wrap{position:relative;padding-top:22px}
.how-step-num{position:absolute;top:0;right:24px;width:38px;height:38px;border-radius:50%;background:linear-gradient(135deg,var(--accent),var(--accent2));display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;color:#fff;font-family:var(--font-mono);z-index:2;box-shadow:0 6px 20px rgba(99,102,241,.5);border:2px solid rgba(255,255,255,.15);transition:transform var(--transition),box-shadow var(--transition)}
.how-card-wrap:hover .how-step-num{transform:scale(1.12);box-shadow:0 10px 28px rgba(99,102,241,.7)}

.how-card{background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.08);backdrop-filter:blur(24px);border-radius:22px;padding:36px 30px 40px;position:relative;overflow:hidden;transition:all var(--transition);cursor:default}
.how-card-wrap:hover .how-card{background:rgba(255,255,255,.08);border-color:rgba(99,102,241,.35);transform:translateY(-6px);box-shadow:0 32px 64px rgba(99,102,241,.15)}
.how-card::before{content:'';position:absolute;top:0;left:0;right:0;height:2px;background:linear-gradient(90deg,var(--accent),var(--accent2));transform:scaleX(0);transition:transform .4s ease;transform-origin:left;border-radius:22px 22px 0 0}
.how-card-wrap:hover .how-card::before{transform:scaleX(1)}

.how-watermark{position:absolute;bottom:-8px;right:16px;font-family:var(--font-mono);font-size:100px;color:rgba(255,255,255,.03);font-weight:700;line-height:1;pointer-events:none;transition:color .4s}
.how-card-wrap:hover .how-watermark{color:rgba(99,102,241,.06)}

.how-icon{width:58px;height:58px;border-radius:16px;background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.1);display:flex;align-items:center;justify-content:center;margin-bottom:24px;transition:all var(--transition)}
.how-card-wrap:hover .how-icon{background:rgba(99,102,241,.18);border-color:rgba(99,102,241,.35);transform:scale(1.08) rotate(-5deg)}
.how-icon svg{width:26px;height:26px;color:#a5b4fc}
.how-title{font-size:18px;font-weight:600;color:#fff;margin-bottom:12px;font-family:var(--font);transition:color .2s}
.how-card-wrap:hover .how-title{color:#c7d2fe}
.how-desc{font-size:13.5px;color:rgba(255,255,255,.45);line-height:1.8;font-family:var(--font);font-weight:300;margin-bottom:24px}
.how-tags{display:flex;flex-wrap:wrap;gap:7px}
.how-tag{display:inline-flex;align-items:center;gap:5px;padding:4px 12px;border-radius:100px;font-size:11px;font-weight:500;font-family:var(--font-mono);background:rgba(99,102,241,.1);border:1px solid rgba(99,102,241,.2);color:#a5b4fc;transition:background .2s}
.how-card-wrap:hover .how-tag{background:rgba(99,102,241,.18)}

/* Connector dashes */
.how-grid{position:relative}
/* ═══════════════════════════════════════════════════════════
   8. TRUST PILLARS
═══════════════════════════════════════════════════════════ */
.trust-section{background:var(--surface);padding:110px 0}
.trust-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:18px}
@media(max-width:900px){.trust-grid{grid-template-columns:repeat(2,1fr)}}
@media(max-width:560px){.trust-grid{grid-template-columns:1fr}}
.trust-card{background:var(--surface2);border-radius:var(--radius);border:1px solid var(--border2);padding:28px;display:flex;flex-direction:column;gap:14px;transition:all var(--transition);box-shadow:var(--shadow);position:relative;overflow:hidden}
.trust-card::before{content:'';position:absolute;top:0;left:0;right:0;height:3px;background:var(--tc-color, var(--accent));opacity:0;transition:opacity var(--transition)}
.trust-card:hover{transform:translateY(-5px);box-shadow:0 20px 48px rgba(99,102,241,.1);border-color:rgba(99,102,241,.25)}
.trust-card:hover::before{opacity:1}
.trust-icon-wrap{width:52px;height:52px;border-radius:14px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.trust-icon-wrap svg{width:24px;height:24px}
.trust-card-title{font-size:15px;font-weight:600;color:var(--text);font-family:var(--font);margin-bottom:5px}
.trust-card-desc{font-size:13px;color:var(--text2);line-height:1.7;font-family:var(--font);font-weight:300}
.trust-card-badge{display:inline-flex;align-items:center;gap:5px;font-size:11px;font-weight:600;color:var(--text3);font-family:var(--font-mono);background:var(--surface3);padding:4px 10px;border-radius:100px;border:1px solid var(--border2);margin-top:6px;width:fit-content}

/* ═══════════════════════════════════════════════════════════
   9. TESTIMONIALS
═══════════════════════════════════════════════════════════ */
.testimonials-section{background:var(--bg);padding:110px 0}
.testimonials-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:20px}
@media(max-width:900px){.testimonials-grid{grid-template-columns:1fr 1fr}}
@media(max-width:600px){.testimonials-grid{grid-template-columns:1fr}}
.testimonial-card{background:var(--surface);border:1px solid var(--border2);border-radius:var(--radius);padding:28px;box-shadow:var(--shadow);transition:all var(--transition);position:relative;display:flex;flex-direction:column;gap:16px}
.testimonial-card:hover{transform:translateY(-4px);box-shadow:var(--shadow-lg);border-color:rgba(99,102,241,.2)}
.t-quote-icon{color:var(--accent);opacity:.3;flex-shrink:0}
.t-quote-icon svg{width:28px;height:28px}
.t-stars{display:flex;gap:3px}
.t-star{color:#f59e0b;font-size:13px}
.t-text{font-size:14px;color:var(--text2);line-height:1.75;font-family:var(--font);font-weight:300;font-style:italic;flex:1}
.t-author{display:flex;align-items:center;gap:12px;margin-top:auto}
.t-avatar{width:40px;height:40px;border-radius:50%;object-fit:cover;flex-shrink:0;background:linear-gradient(135deg,var(--accent),var(--accent2));display:flex;align-items:center;justify-content:center;font-size:15px;font-weight:700;color:#fff;font-family:var(--font-mono)}
.t-name{font-size:13.5px;font-weight:600;color:var(--text);font-family:var(--font)}
.t-location{font-size:11.5px;color:var(--text3);font-family:var(--font-mono);margin-top:1px}
.t-featured-badge{position:absolute;top:16px;right:16px;font-size:10px;font-weight:700;color:var(--accent);background:var(--accent-glow);padding:3px 10px;border-radius:100px;font-family:var(--font-mono);border:1px solid rgba(99,102,241,.2)}

/* Highlight card */
.testimonial-card.featured{background:linear-gradient(135deg,var(--accent),var(--accent2));border-color:transparent}
.testimonial-card.featured .t-text{color:rgba(255,255,255,.85);font-style:normal}
.testimonial-card.featured .t-name{color:#fff}
.testimonial-card.featured .t-location{color:rgba(255,255,255,.6)}
.testimonial-card.featured .t-quote-icon{color:rgba(255,255,255,.3);opacity:1}
.testimonial-card.featured .t-star{color:#fde68a}

/* ═══════════════════════════════════════════════════════════
   10. IMPACT NUMBERS (Horizontal scroll cards on mobile)
═══════════════════════════════════════════════════════════ */
.impact-section{background:var(--surface);padding:90px 0}
.impact-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:16px}
@media(max-width:768px){.impact-grid{grid-template-columns:repeat(2,1fr)}}
@media(max-width:480px){.impact-grid{display:flex;overflow-x:auto;gap:12px;padding-bottom:12px;scrollbar-width:none}}
@media(max-width:480px){.impact-grid::-webkit-scrollbar{display:none}}
.impact-card{background:var(--surface2);border:1px solid var(--border2);border-radius:var(--radius);padding:28px 24px;text-align:center;transition:all var(--transition)}
@media(max-width:480px){.impact-card{flex-shrink:0;min-width:170px}}
.impact-card:hover{transform:translateY(-3px);box-shadow:var(--shadow-md);border-color:rgba(99,102,241,.2)}
.impact-card-icon{width:48px;height:48px;border-radius:14px;display:flex;align-items:center;justify-content:center;margin:0 auto 14px}
.impact-card-icon svg{width:22px;height:22px}
.impact-card-num{font-size:clamp(22px,3vw,32px);font-weight:700;color:var(--text);font-family:var(--font-mono);line-height:1;margin-bottom:6px}
.impact-card-label{font-size:12px;color:var(--text3);font-family:var(--font);line-height:1.5}

/* ═══════════════════════════════════════════════════════════
   11. FAQ ACCORDION
═══════════════════════════════════════════════════════════ */
.faq-section{background:var(--bg);padding:110px 0}
.faq-layout{display:grid;grid-template-columns:1fr 1.5fr;gap:80px;align-items:start}
@media(max-width:900px){.faq-layout{grid-template-columns:1fr;gap:40px}}
.faq-sticky{position:sticky;top:100px}
@media(max-width:900px){.faq-sticky{position:static}}
.faq-sticky .section-title{margin-bottom:16px}
.faq-sticky p{font-size:14.5px;color:var(--text2);line-height:1.8;font-weight:300}

.faq-list{display:flex;flex-direction:column;gap:10px}
.faq-item{background:var(--surface);border:1px solid var(--border2);border-radius:var(--radius);overflow:hidden;transition:border-color var(--transition)}
.faq-item.open{border-color:rgba(99,102,241,.3)}
.faq-q{display:flex;align-items:center;justify-content:space-between;padding:20px 22px;cursor:pointer;gap:16px;-webkit-tap-highlight-color:transparent}
.faq-q-text{font-size:14.5px;font-weight:600;color:var(--text);font-family:var(--font);line-height:1.4}
.faq-chevron{width:28px;height:28px;border-radius:50%;background:var(--surface2);border:1px solid var(--border2);display:flex;align-items:center;justify-content:center;flex-shrink:0;transition:all var(--transition)}
.faq-chevron svg{width:13px;height:13px;color:var(--text3);transition:transform .35s ease}
.faq-item.open .faq-chevron{background:var(--accent);border-color:var(--accent)}
.faq-item.open .faq-chevron svg{transform:rotate(180deg);color:#fff}
.faq-a{max-height:0;overflow:hidden;transition:max-height .4s cubic-bezier(0.4,0,0.2,1),padding .4s}
.faq-item.open .faq-a{max-height:300px}
.faq-a-inner{padding:0 22px 20px;font-size:13.5px;color:var(--text2);line-height:1.8;font-weight:300;font-family:var(--font)}

/* ═══════════════════════════════════════════════════════════
   12. TEAM / FOUNDERS
═══════════════════════════════════════════════════════════ */
.team-section{background:var(--surface);padding:110px 0}
.team-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:22px;margin-top:0}
@media(max-width:768px){.team-grid{grid-template-columns:1fr 1fr}}
@media(max-width:480px){.team-grid{grid-template-columns:1fr}}
.team-card{background:var(--surface2);border:1px solid var(--border2);border-radius:var(--radius);overflow:hidden;transition:all var(--transition);box-shadow:var(--shadow)}
.team-card:hover{transform:translateY(-5px);box-shadow:var(--shadow-lg);border-color:rgba(99,102,241,.2)}
.team-img-wrap{position:relative;overflow:hidden;height:220px}
.team-img-wrap img{width:100%;height:100%;object-fit:cover;transition:transform .5s ease}
.team-card:hover .team-img-wrap img{transform:scale(1.06)}
.team-img-overlay{position:absolute;inset:0;background:linear-gradient(to top,rgba(7,8,15,.7) 0%,transparent 60%);opacity:0;transition:opacity var(--transition)}
.team-card:hover .team-img-overlay{opacity:1}
.team-info{padding:20px}
.team-name{font-size:15.5px;font-weight:600;color:var(--text);font-family:var(--font);margin-bottom:3px}
.team-role{font-size:12px;color:var(--accent);font-family:var(--font-mono);font-weight:600;letter-spacing:.04em;margin-bottom:10px}
.team-bio{font-size:12.5px;color:var(--text3);line-height:1.65;font-family:var(--font);font-weight:300}

/* Founder placeholder avatars */
.team-avatar-placeholder{width:100%;height:220px;display:flex;align-items:center;justify-content:center;font-size:56px;font-weight:700;color:#fff;font-family:var(--font-mono)}

/* ═══════════════════════════════════════════════════════════
   13. CTA BANNER
═══════════════════════════════════════════════════════════ */
.cta-section{position:relative;overflow:hidden;padding:120px 0;text-align:center;background:linear-gradient(160deg,#07080f 0%,#0d0e1a 50%,#13141f 100%)}
.cta-section::before{content:'';position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:800px;height:800px;border-radius:50%;background:radial-gradient(circle,rgba(99,102,241,.12) 0%,transparent 70%);pointer-events:none}
.cta-bg-img{position:absolute;inset:0;z-index:0}
.cta-bg-img img{width:100%;height:100%;object-fit:cover;opacity:.18}
.cta-inner{position:relative;z-index:1;max-width:640px;margin:0 auto;padding:0 24px}
.cta-title{font-family:var(--font-display);font-size:clamp(2rem,4.5vw,3.2rem);font-weight:700;color:#fff;margin-bottom:16px;line-height:1.1}
.cta-title em{font-style:normal;color:#a5b4fc}
.cta-sub{font-size:15px;color:rgba(255,255,255,.55);font-weight:300;line-height:1.8;max-width:480px;margin:0 auto 36px;font-family:var(--font)}
.cta-btns{display:flex;gap:12px;justify-content:center;flex-wrap:wrap}

/* CTA trust line */
.cta-trust{display:flex;align-items:center;justify-content:center;gap:20px;margin-top:24px;flex-wrap:wrap}
.cta-trust-item{display:flex;align-items:center;gap:6px;font-size:12px;color:rgba(255,255,255,.4);font-family:var(--font-mono)}
.cta-trust-item svg{width:13px;height:13px;color:var(--green)}

/* ═══════════════════════════════════════════════════════════
   MOBILE RESPONSIVE FIXES
═══════════════════════════════════════════════════════════ */
@media(max-width:768px){
    .hero-title{font-size:clamp(2.2rem,7vw,3rem)}
    .hero-desc{font-size:15px}
    .hero-btns{gap:10px}
    .hero-trust{gap:16px}
    .btn{padding:12px 22px;font-size:13px}
    .section-title{font-size:clamp(1.7rem,5vw,2.2rem)}
    .stats-inner{padding:0 12px}
    .stat-tile{padding:40px 20px}
    .stat-tile-num{font-size:36px}
    .how-grid{gap:14px}
    .faq-layout,.purpose-grid,.journey-grid{gap:32px}
}
@media(max-width:480px){
    .hero-content{padding:100px 16px 150px}
    .container{padding:0 16px}
    .purpose-section,.journey-section,.trust-section,.testimonials-section,.faq-section,.team-section,.impact-section{padding:72px 0}
    .how-section{padding:72px 0}
    .section-header{margin-bottom:40px}
}

/* ── Scroll top button ── */
.scroll-top{position:fixed;bottom:24px;right:24px;width:44px;height:44px;border-radius:50%;background:var(--accent);color:#fff;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;box-shadow:0 6px 20px rgba(99,102,241,.45);opacity:0;transform:translateY(16px);transition:all var(--transition);z-index:999}
.scroll-top.visible{opacity:1;transform:translateY(0)}
.scroll-top:hover{transform:translateY(-2px);box-shadow:0 10px 28px rgba(99,102,241,.55)}
.scroll-top svg{width:18px;height:18px}
</style>


{{-- ═══════════════════════════════════════════════════════════
     1. HERO
═══════════════════════════════════════════════════════════ --}}
<div class="hero">
    <div class="hero-bg">
        <img src="{{ asset('images/about.jpg') }}" alt="About DonateBazaar" loading="eager">
    </div>
    <div class="hero-overlay"></div>
    <div class="hero-grid"></div>

    <div class="hero-inner">
        <div class="hero-content">
            <div class="hero-pill">
                <span class="hero-pill-dot"></span>
                Trusted by 50,000+ Donors Across India
            </div>

            <h1 class="hero-title">
                Giving Made <em>Simple,</em>
                <span class="line2">Secure &amp; Impactful</span>
            </h1>

            <p class="hero-desc">
                We connect compassionate donors with verified campaigns across India — building a movement of trust, transparency, and lasting change since 2020.
            </p>

            <div class="hero-btns">
                <a href="{{ route('campaigns.index') }}" class="btn btn-white">
                    Donate Now
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
                <a href="/campaign/create" class="btn btn-outline">
                    Start Fundraiser
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 4v16m8-8H4"/></svg>
                </a>
            </div>

            <div class="hero-trust">
                <div class="hero-trust-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    Verified Campaigns
                </div>
                <div class="hero-trust-sep"></div>
                <div class="hero-trust-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                    256-bit SSL Secure
                </div>
                <div class="hero-trust-sep"></div>
                <div class="hero-trust-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    RBI Compliant
                </div>
                <div class="hero-trust-sep"></div>
                <div class="hero-trust-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    24×7 Support
                </div>
            </div>
        </div>

        {{-- Floating metric cards --}}
        <div class="hero-float-cards">
            <div class="hero-float-card">
                <div class="float-card-top">
                    <div class="float-card-icon" style="background:rgba(16,185,129,.15);">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
                    </div>
                    <span class="float-card-label">Funds Raised</span>
                </div>
                <div class="float-card-num">₹10 Cr+</div>
                <div class="float-card-sub">and growing every day</div>
            </div>
            <div class="hero-float-card">
                <div class="float-card-top">
                    <div class="float-card-icon" style="background:rgba(99,102,241,.15);">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#a5b4fc" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
                    </div>
                    <span class="float-card-label">Donors</span>
                </div>
                <div class="float-card-num">50K+</div>
                <div class="float-card-sub">across all 28 states</div>
            </div>
            <div class="hero-float-card">
                <div class="float-card-top">
                    <div class="float-card-icon" style="background:rgba(245,158,11,.15);">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
                    </div>
                    <span class="float-card-label">Campaigns</span>
                </div>
                <div class="float-card-num">2,000+</div>
                <div class="float-card-sub">active &amp; verified</div>
            </div>
        </div>

        {{-- Bottom stat bar --}}
        <div class="hero-stat-bar">
            <div class="hero-stat-item">
                <span class="hero-stat-val counter" data-target="10000000" data-format="crore">₹10 Cr+</span>
                <span class="hero-stat-lbl">Funds Raised</span>
            </div>
            <div class="hero-stat-item">
                <span class="hero-stat-val counter" data-target="50000" data-suffix="+">0</span>
                <span class="hero-stat-lbl">Generous Donors</span>
            </div>
            <div class="hero-stat-item">
                <span class="hero-stat-val counter" data-target="2000" data-suffix="+">0</span>
                <span class="hero-stat-lbl">Campaigns</span>
            </div>
            <div class="hero-stat-item">
                <span class="hero-stat-val">100%</span>
                <span class="hero-stat-lbl">Transparent</span>
            </div>
        </div>
    </div>
</div>


{{-- ═══ MARQUEE ═══ --}}
<div class="marquee-band">
    <div class="marquee-inner">
        <div class="marquee-row">
            @php $items = ['Trusted by 50,000+ Donors', '2,000+ Verified Campaigns', '₹10 Crore+ Raised', 'Pan-India Coverage', 'RBI-Compliant Payments', '256-bit SSL Encryption', '24×7 Donor Support', '100% Transparency Guaranteed', 'Featured in 15+ National Media']; @endphp
            @for($r=0;$r<3;$r++)
                @foreach($items as $item)
                    <span class="m-item"><span class="m-dot"></span>{{ $item }}</span>
                @endforeach
            @endfor
        </div>
    </div>
</div>


{{-- ═══ MEDIA TRUST LOGOS ═══ --}}
<div class="media-section">
    <div class="container">
        <div class="media-inner">
            <span class="media-label">Featured In</span>
            <div class="media-divider"></div>
            <div class="media-logos">
                <span class="media-logo">The Hindu</span>
                <span class="media-logo">Economic Times</span>
                <span class="media-logo">NDTV</span>
                <span class="media-logo">Mint</span>
                <span class="media-logo">YourStory</span>
                <span class="media-logo">Inc42</span>
            </div>
        </div>
    </div>
</div>


{{-- ═══ PURPOSE / MISSION ═══ --}}
<section class="purpose-section">
    <div class="container">
        <div class="purpose-grid">
            <div class="purpose-sticky">
                <div class="eyebrow reveal">Our Purpose</div>
                <h2 class="purpose-headline reveal d1">
                    Why we <em>exist</em><br>and what<br>drives us
                </h2>
                <div class="purpose-quote reveal d2">
                    <p>"Every rupee donated through DonateBazaar reaches the right hands — that is not just our promise, it is our foundation."</p>
                    <cite>— Founding Team, DonateBazaar</cite>
                </div>
            </div>
            <div class="purpose-body">
                <p class="reveal d2">DonateBazaar is built with a clear purpose — to make giving simple, transparent, and truly impactful for everyone involved. We believe that generosity should never be complicated, which is why our platform is designed to offer a seamless and trustworthy experience.</p>
                <p class="reveal d3">By connecting donors directly with real needs and carefully verified campaigns, DonateBazaar ensures that every contribution goes exactly where it is intended. Each campaign undergoes a rigorous multi-step review process to maintain authenticity and build deep confidence among donors.</p>
                <p class="reveal d4">Our goal is not just to facilitate donations, but to create meaningful connections between people who want to help and those who genuinely need support — from medical emergencies to education, disaster relief, and community upliftment across all 28 states of India.</p>
                <div class="chip-grid reveal d4">

    <span class="chip">
        <i class="fas fa-shield-check text-green-500 mr-2"></i>
        Verified Campaigns
    </span>

    <span class="chip">
        <i class="fas fa-chart-line text-blue-500 mr-2"></i>
        Full Transparency
    </span>

    <span class="chip">
        <i class="fas fa-globe-asia text-orange-500 mr-2"></i>
        Pan-India Reach
    </span>

    <span class="chip">
        <i class="fas fa-credit-card text-purple-500 mr-2"></i>
        Secure Payments
    </span>

    <span class="chip">
        <i class="fas fa-clock text-pink-500 mr-2"></i>
        Real-time Tracking
    </span>

    <span class="chip">
        <i class="fas fa-receipt text-gray-600 mr-2"></i>
        80G Tax Benefits
    </span>

</div>
            </div>
        </div>
    </div>
</section>


{{-- ═══ STATS STRIP ═══ --}}
<div class="stats-strip">
    <div class="stats-inner">
        <div class="stat-tile">
            <div class="stat-tile-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
            </div>
            <span class="stat-tile-num"><span class="counter" data-target="10000000" data-format="crore">₹10 Cr+</span></span>
            <span class="stat-tile-lbl">Funds Raised</span>
            <span class="stat-tile-sub">Since 2020</span>
        </div>
        <div class="stat-tile">
            <div class="stat-tile-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
            </div>
            <span class="stat-tile-num"><span class="counter" data-target="50000">0</span><span class="suf">+</span></span>
            <span class="stat-tile-lbl">Donors</span>
            <span class="stat-tile-sub">Across all 28 states</span>
        </div>
        <div class="stat-tile">
            <div class="stat-tile-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
            </div>
            <span class="stat-tile-num"><span class="counter" data-target="2000">0</span><span class="suf">+</span></span>
            <span class="stat-tile-lbl">Campaigns</span>
            <span class="stat-tile-sub">Fully verified</span>
        </div>
        <div class="stat-tile">
            <div class="stat-tile-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            </div>
            <span class="stat-tile-num">100<span class="suf">%</span></span>
            <span class="stat-tile-lbl">Transparency</span>
            <span class="stat-tile-sub">Zero hidden fees</span>
        </div>
    </div>
</div>


{{-- ═══ JOURNEY / TIMELINE ═══ --}}
<section class="journey-section">
    <div class="container">
        <div class="journey-grid">
            <div class="journey-img-wrap reveal-left">
                <div class="journey-mini-card">
                    <div class="journey-mini-card-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                    </div>
                    <div>
                        <div class="journey-mini-card-num">98.7%</div>
                        <div class="journey-mini-card-lbl">Success Rate</div>
                    </div>
                </div>
                <div class="journey-img-inner">
                    <img src="{{ asset('images/journey.webp') }}" alt="Our Journey" loading="lazy">
                </div>
                <div class="journey-badge">
                    <strong>2020</strong>
                    <span>Founded</span>
                </div>
            </div>

            <div class="journey-text">
                <div class="eyebrow reveal">Our Journey</div>
                <h2 class="reveal d1"><em>From a small idea</em><br>to real impact</h2>
                <p class="reveal d2">We started with a simple belief — that anyone, no matter how small their contribution, has the power to make a meaningful difference. What began as a humble idea has grown into a trusted platform where compassion meets action.</p>
                <p class="reveal d3">Today, thousands of donors place their trust in DonateBazaar every month. Every campaign represents a real story — a family seeking hope, a child striving for education, or a community rebuilding after disaster.</p>

                <div class="timeline">
                    <div class="tl-item reveal d2">
                        <div class="tl-dot done">
                            <svg viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                        </div>
                        <div>
                            <div class="tl-year">2020</div>
                            <div class="tl-title">Platform Launched</div>
                            <div class="tl-desc">First 100 campaigns went live; ₹10 Lakh raised in the debut month</div>
                        </div>
                    </div>
                    <div class="tl-item reveal d3">
                        <div class="tl-dot done">
                            <svg viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                        </div>
                        <div>
                            <div class="tl-year">2021</div>
                            <div class="tl-title">₹1 Crore Milestone</div>
                            <div class="tl-desc">Community trust grew rapidly; 5,000+ donors joined the platform</div>
                        </div>
                    </div>
                    <div class="tl-item reveal d4">
                        <div class="tl-dot done">
                            <svg viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                        </div>
                        <div>
                            <div class="tl-year">2023</div>
                            <div class="tl-title">National Expansion</div>
                            <div class="tl-desc">10,000+ NGOs onboarded; expanded to all 28 states of India</div>
                        </div>
                    </div>
                    <div class="tl-item reveal d5">
                        <div class="tl-dot active">
                            <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                        </div>
                        <div>
                            <div class="tl-year">Today</div>
                            <div class="tl-title">₹10 Crore+ Raised</div>
                            <div class="tl-desc">50,000+ donors trust us; featured in 15+ national media publications</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


{{-- ═══ HOW IT WORKS ═══ --}}
<section class="how-section">
    <div class="container" style="position:relative;z-index:1">
        <div class="section-header">
            <div class="eyebrow reveal" style="color:#a5b4fc;justify-content:center">Simple, Transparent, Secure</div>
            <h2 class="section-title reveal d1" style="color:#fff">How It Works</h2>
            <p class="section-sub reveal d2" style="color:rgba(255,255,255,.45);margin:0 auto">Giving should feel good — not complicated. Here is how DonateBazaar makes it effortless in three steps.</p>
        </div>

        <div class="how-grid">
            <div class="how-card-wrap reveal d1">
                <div class="how-step-num">01</div>
                <div class="how-card">
                    <div class="how-watermark">01</div>
                    <div class="how-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                    </div>
                    <div class="how-title">Choose Your Cause</div>
                    <div class="how-desc">Browse hundreds of verified campaigns by category — medical, education, disaster relief, and more. Every campaign is personally reviewed before going live.</div>
                    <div class="how-tags">
                        <span class="how-tag">✦ Verified NGOs</span>
                        <span class="how-tag">✦ 10+ Categories</span>
                        <span class="how-tag">✦ Real Stories</span>
                    </div>
                </div>
            </div>

            <div class="how-card-wrap reveal d2">
                <div class="how-step-num">02</div>
                <div class="how-card">
                    <div class="how-watermark">02</div>
                    <div class="how-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                    </div>
                    <div class="how-title">Donate Securely</div>
                    <div class="how-desc">Use UPI, cards, or net banking — whatever is most convenient. Every transaction is encrypted end-to-end through RBI-compliant payment gateways.</div>
                    <div class="how-tags">
                        <span class="how-tag">✦ UPI &amp; Cards</span>
                        <span class="how-tag">✦ 256-bit SSL</span>
                        <span class="how-tag">✦ RBI Compliant</span>
                    </div>
                </div>
            </div>

            <div class="how-card-wrap reveal d3">
                <div class="how-step-num">03</div>
                <div class="how-card">
                    <div class="how-watermark">03</div>
                    <div class="how-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                    </div>
                    <div class="how-title">Track Your Impact</div>
                    <div class="how-desc">Receive real-time updates from campaign creators. Watch your contribution turn into measurable, lasting change — every rupee tracked with full transparency.</div>
                    <div class="how-tags">
                        <span class="how-tag">✦ Live Updates</span>
                        <span class="how-tag">✦ Photo Reports</span>
                        <span class="how-tag">✦ Tax Receipts</span>
                    </div>
                </div>
            </div>
        </div>

        <div style="text-align:center;margin-top:52px;position:relative;z-index:1" class="reveal d3">
            <a href="{{ url('/all-campaigns') }}" class="btn btn-accent" style="padding:15px 38px;font-size:14px">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
                Start Donating Now
            </a>
        </div>
    </div>
</section>


{{-- ═══ TRUST PILLARS ═══ --}}
<section class="trust-section">
    <div class="container">
        <div class="section-header">
            <div class="eyebrow reveal" style="justify-content:center">Why trust us</div>
            <h2 class="section-title reveal d1">Built on <em>Integrity</em> &amp; Accountability</h2>
            <p class="section-sub reveal d2">Every feature of our platform is designed around one goal — making sure your donation reaches the right person, at the right time, every time.</p>
        </div>

        @php
        $pillars = [
            ['bg'=>'#ede9fe','color'=>'#6366f1','tc'=>'#6366f1','title'=>'Rigorous Verification','desc'=>'Every campaign undergoes multi-step checks — document verification, identity validation, and periodic audits to ensure 100% authenticity.','badge'=>'ISO Certified Process','svg'=>'<path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/>'],
            ['bg'=>'#d1fae5','color'=>'#10b981','tc'=>'#10b981','title'=>'Bank-Grade Security','desc'=>'256-bit SSL encryption on every transaction. Your payment details are never stored on our servers — ever.','badge'=>'PCI-DSS Compliant','svg'=>'<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>'],
            ['bg'=>'#fef3c7','color'=>'#f59e0b','tc'=>'#f59e0b','title'=>'Real-time Updates','desc'=>'Campaign creators post regular photo and video updates. Know exactly how and when your donation creates impact on the ground.','badge'=>'Live Tracking','svg'=>'<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>'],
            ['bg'=>'#ede9fe','color'=>'#8b5cf6','tc'=>'#8b5cf6','title'=>'Zero Hidden Fees','desc'=>'We are completely transparent about platform costs. You see exactly how much reaches the cause before you click donate.','badge'=>'Full Breakdown','svg'=>'<line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/>'],
            ['bg'=>'#dbeafe','color'=>'#3b82f6','tc'=>'#3b82f6','title'=>'80G Tax Benefits','desc'=>'All eligible donations come with 80G tax exemption certificates automatically sent to your registered email. Save up to 50% on taxes.','badge'=>'Auto Tax Certificate','svg'=>'<path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/>'],
            ['bg'=>'#d1fae5','color'=>'#059669','tc'=>'#059669','title'=>'24×7 Donor Support','desc'=>'Our dedicated team is available around the clock via chat, email, and phone — for donors and campaign creators alike.','badge'=>'Avg. 4 min response','svg'=>'<path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/>'],
        ];
        @endphp

        <div class="trust-grid">
            @foreach($pillars as $p)
            <div class="trust-card reveal d{{ ($loop->index % 3) + 1 }}" style="--tc-color:{{ $p['tc'] }}">
                <div class="trust-icon-wrap" style="background:{{ $p['bg'] }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="{{ $p['color'] }}" stroke-width="2">{!! $p['svg'] !!}</svg>
                </div>
                <div>
                    <div class="trust-card-title">{{ $p['title'] }}</div>
                    <div class="trust-card-desc">{{ $p['desc'] }}</div>
                    <div class="trust-card-badge">
                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="{{ $p['color'] }}" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                        {{ $p['badge'] }}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>


{{-- ═══ TESTIMONIALS ═══ --}}
<section class="testimonials-section">
    <div class="container">
        <div class="section-header">
            <div class="eyebrow reveal" style="justify-content:center">Donor Stories</div>
            <h2 class="section-title reveal d1">Real People, <em>Real Impact</em></h2>
            <p class="section-sub reveal d2">Thousands of donors trust DonateBazaar every month. Here is what some of them have to say about their experience.</p>
        </div>

        <div class="testimonials-grid">
            {{-- Featured card --}}
            <div class="testimonial-card featured reveal d1">
                <div class="t-quote-icon">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M11.192 15.757c0-.88-.23-1.618-.69-2.217-.326-.412-.768-.683-1.327-.812-.55-.128-1.07-.137-1.54-.028-.16-.95.1-1.956.76-3.022.66-1.065 1.515-1.867 2.558-2.403L9.373 5c-.8.396-1.56.898-2.26 1.505-.71.607-1.34 1.305-1.9 2.094s-.98 1.68-1.25 2.69-.346 2.04-.217 3.1c.168 1.4.62 2.52 1.356 3.35.735.84 1.652 1.26 2.748 1.26.965 0 1.766-.29 2.4-.878.628-.576.94-1.365.94-2.368l.002.003zm9.124 0c0-.88-.23-1.618-.69-2.217-.326-.42-.77-.692-1.327-.817-.56-.124-1.074-.13-1.54-.022-.16-.94.09-1.95.75-3.016.66-1.066 1.515-1.867 2.558-2.403L18.49 5c-.8.396-1.555.898-2.26 1.505-.708.607-1.34 1.305-1.894 2.094-.556.79-.97 1.68-1.24 2.69-.273 1-.345 2.04-.217 3.1.168 1.4.62 2.52 1.356 3.35.735.84 1.652 1.26 2.748 1.26.965 0 1.766-.29 2.4-.878.628-.576.94-1.365.94-2.368l.002.003z"/></svg>
                </div>
                <div class="t-stars">⭐⭐⭐⭐⭐</div>
                <div class="t-text">"I donated for a child's surgery through DonateBazaar and received photo updates from the hospital within 48 hours. I could see exactly how my ₹5,000 made a difference. This is what trust looks like."</div>
                <div class="t-author">
                    <div class="t-avatar">P</div>
                    <div>
                        <div class="t-name">Priya Sharma</div>
                        <div class="t-location">Mumbai, Maharashtra · Donor since 2021</div>
                    </div>
                </div>
            </div>

            <div class="testimonial-card reveal d2">
                <div class="t-quote-icon">
                    <svg viewBox="0 0 24 24" fill="currentColor" style="color:var(--accent)"><path d="M11.192 15.757c0-.88-.23-1.618-.69-2.217-.326-.412-.768-.683-1.327-.812-.55-.128-1.07-.137-1.54-.028-.16-.95.1-1.956.76-3.022.66-1.065 1.515-1.867 2.558-2.403L9.373 5c-.8.396-1.56.898-2.26 1.505-.71.607-1.34 1.305-1.9 2.094s-.98 1.68-1.25 2.69-.346 2.04-.217 3.1c.168 1.4.62 2.52 1.356 3.35.735.84 1.652 1.26 2.748 1.26.965 0 1.766-.29 2.4-.878.628-.576.94-1.365.94-2.368l.002.003zm9.124 0c0-.88-.23-1.618-.69-2.217-.326-.42-.77-.692-1.327-.817-.56-.124-1.074-.13-1.54-.022-.16-.94.09-1.95.75-3.016.66-1.066 1.515-1.867 2.558-2.403L18.49 5c-.8.396-1.555.898-2.26 1.505-.708.607-1.34 1.305-1.894 2.094-.556.79-.97 1.68-1.24 2.69-.273 1-.345 2.04-.217 3.1.168 1.4.62 2.52 1.356 3.35.735.84 1.652 1.26 2.748 1.26.965 0 1.766-.29 2.4-.878.628-.576.94-1.365.94-2.368l.002.003z"/></svg>
                </div>
                <div class="t-stars">⭐⭐⭐⭐⭐</div>
                <div class="t-text">"We ran a flood relief campaign and raised ₹8 Lakh in just 3 days. The platform review team was incredibly helpful and transparent. DonateBazaar genuinely cares about the cause."</div>
                <div class="t-author">
                    <div class="t-avatar">R</div>
                    <div>
                        <div class="t-name">Rajesh Menon</div>
                        <div class="t-location">Kochi, Kerala · NGO Fundraiser</div>
                    </div>
                </div>
            </div>

            <div class="testimonial-card reveal d3">
                <div class="t-quote-icon">
                    <svg viewBox="0 0 24 24" fill="currentColor" style="color:var(--accent)"><path d="M11.192 15.757c0-.88-.23-1.618-.69-2.217-.326-.412-.768-.683-1.327-.812-.55-.128-1.07-.137-1.54-.028-.16-.95.1-1.956.76-3.022.66-1.065 1.515-1.867 2.558-2.403L9.373 5c-.8.396-1.56.898-2.26 1.505-.71.607-1.34 1.305-1.9 2.094s-.98 1.68-1.25 2.69-.346 2.04-.217 3.1c.168 1.4.62 2.52 1.356 3.35.735.84 1.652 1.26 2.748 1.26.965 0 1.766-.29 2.4-.878.628-.576.94-1.365.94-2.368l.002.003zm9.124 0c0-.88-.23-1.618-.69-2.217-.326-.42-.77-.692-1.327-.817-.56-.124-1.074-.13-1.54-.022-.16-.94.09-1.95.75-3.016.66-1.066 1.515-1.867 2.558-2.403L18.49 5c-.8.396-1.555.898-2.26 1.505-.708.607-1.34 1.305-1.894 2.094-.556.79-.97 1.68-1.24 2.69-.273 1-.345 2.04-.217 3.1.168 1.4.62 2.52 1.356 3.35.735.84 1.652 1.26 2.748 1.26.965 0 1.766-.29 2.4-.878.628-.576.94-1.365.94-2.368l.002.003z"/></svg>
                </div>
                <div class="t-stars">⭐⭐⭐⭐⭐</div>
                <div class="t-text">"Got my 80G certificate in my inbox the same day I donated. The UPI payment was instant, the receipt was detailed, and the follow-up updates made me feel like a true part of the story."</div>
                <div class="t-author">
                    <div class="t-avatar">A</div>
                    <div>
                        <div class="t-name">Anjali Gupta</div>
                        <div class="t-location">Bengaluru, Karnataka · Donor since 2022</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


{{-- ═══ FAQ ═══ --}}
<section class="faq-section">
    <div class="container">
        <div class="faq-layout">
            <div class="faq-sticky">
                <div class="eyebrow reveal">FAQs</div>
                <h2 class="section-title reveal d1">Questions <em>donors</em> ask us most</h2>
                <p class="reveal d2">We believe in full transparency — about our process, our fees, and our purpose. Here are the answers to what matters most.</p>
                <div style="margin-top:28px" class="reveal d3">
                    <a href="{{ url('/contact') }}" class="btn btn-accent">
                        Ask Us Anything
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>

            <div class="faq-list reveal d2">
                @php
                $faqs = [
                    ['q'=>'How does DonateBazaar verify campaigns?','a'=>'Every campaign goes through a 3-step verification process: document review (ID, cause proof), identity validation (video call or in-person visit for high-value campaigns), and periodic audits during the fundraising period. Only 100% verified campaigns go live.'],
                    ['q'=>'Are there any hidden fees on my donation?','a'=>'No. We show a complete fee breakdown before you confirm your donation. You see exactly how much goes to the cause and what (if any) platform fee applies. For many campaigns, donors can choose to cover the fee so 100% reaches the cause.'],
                    ['q'=>'How do I get my 80G tax exemption certificate?','a'=>'For all eligible campaigns, your 80G certificate is automatically generated and emailed to you within 24 hours of your donation. It is always available in your donor dashboard as well. You can use it to claim a 50% deduction on your taxable income.'],
                    ['q'=>'Is my payment information secure?','a'=>'Absolutely. All transactions use 256-bit SSL encryption. We are PCI-DSS compliant and we never store your card or bank details on our servers. Payments are processed through RBI-authorised payment gateways only.'],
                    ['q'=>'Can I get a refund if a campaign is found to be fraudulent?','a'=>'Yes. In the unlikely event that a campaign is found to be fraudulent after funds are released, DonateBazaar\'s Donor Protection Fund covers refunds. We take campaign authenticity extremely seriously and our track record speaks for itself.'],
                    ['q'=>'How are campaign funds transferred to beneficiaries?','a'=>'Funds are transferred in milestone-based tranches — not all at once. This ensures campaign creators use the money as promised. Each transfer is logged and donors receive notifications when funds are disbursed.'],
                ];
                @endphp

                @foreach($faqs as $i => $faq)
                <div class="faq-item" data-faq="{{ $i }}">
                    <div class="faq-q" onclick="toggleFaq({{ $i }})">
                        <span class="faq-q-text">{{ $faq['q'] }}</span>
                        <div class="faq-chevron">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                        </div>
                    </div>
                    <div class="faq-a">
                        <div class="faq-a-inner">{{ $faq['a'] }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>


{{-- ═══ TEAM ═══ --}}
<section class="team-section">
    <div class="container">
        <div class="section-header">
            <div class="eyebrow reveal" style="justify-content:center">The People Behind It</div>
            <h2 class="section-title reveal d1">Meet the <em>Team</em></h2>
            <p class="section-sub reveal d2">Driven by purpose, guided by values — our founding team brings together expertise in technology, social impact, and finance.</p>
        </div>

        <div class="team-grid">
            @php
            $team = [
                ['initial'=>'A','name'=>'Soumik Banerjee','role'=>'Co-Founder & CEO','bio'=>'Former TATA Trust impact director with 12 years in social finance and nonprofit technology.','grad'=>'135deg,#6366f1,#8b5cf6'],
                ['initial'=>'S','name'=>'Vikash Das','role'=>'Co-Founder & CTO','bio'=>'Ex-Razorpay payments engineer passionate about making secure fintech accessible to NGOs.','grad'=>'135deg,#10b981,#059669'],
                ['initial'=>'V','name'=>'Vikram Thakur','role'=>'Head of Trust & Safety','bio'=>'Former RBI compliance officer ensuring every rupee on DonateBazaar is protected.','grad'=>'135deg,#f59e0b,#d97706'],
            ];
            @endphp

            @foreach($team as $member)
            <div class="team-card reveal d{{ $loop->iteration }}">
                <div class="team-img-wrap">
                    <div class="team-avatar-placeholder" style="background:linear-gradient({{ $member['grad'] }})">
                        {{ $member['initial'] }}
                    </div>
                    <div class="team-img-overlay"></div>
                </div>
                <div class="team-info">
                    <div class="team-name">{{ $member['name'] }}</div>
                    <div class="team-role">{{ $member['role'] }}</div>
                    <div class="team-bio">{{ $member['bio'] }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>


{{-- ═══ CTA BANNER ═══ --}}
<section class="cta-section">
    <div class="cta-bg-img">
        <img src="{{ asset('images/banner2.jpeg') }}" alt="">
    </div>
    <div class="cta-inner">
        <div class="eyebrow reveal" style="justify-content:center;color:#a5b4fc">Ready to help?</div>
        <h2 class="cta-title reveal d1">
            Change Someone's Life <em>Today</em>
        </h2>
        <p class="cta-sub reveal d2">Join 50,000+ donors already making a difference across India. Every rupee counts — start giving or start a campaign today.</p>
        <div class="cta-btns reveal d3">
            <a href="{{ route('campaigns.index') }}" class="btn btn-white" style="font-size:15px;padding:15px 34px">
                Donate Now
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
            <a href="/campaign/create" class="btn btn-outline" style="font-size:15px;padding:15px 34px">
                Start Fundraiser
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 4v16m8-8H4"/></svg>
            </a>
        </div>
        <div class="cta-trust reveal d4">
            <div class="cta-trust-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                Verified Campaigns
            </div>
            <div class="cta-trust-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                100% Secure
            </div>
            <div class="cta-trust-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                80G Tax Benefits
            </div>
            <div class="cta-trust-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                24×7 Support
            </div>
        </div>
    </div>
</section>


{{-- Scroll to top button --}}
<button class="scroll-top" id="scrollTopBtn" onclick="window.scrollTo({top:0,behavior:'smooth'})" aria-label="Scroll to top">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 15l-6-6-6 6"/></svg>
</button>


{{-- ═══════════════════════════════════════════════════════════
     SCRIPTS
═══════════════════════════════════════════════════════════ --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ── SCROLL REVEAL ── */
    var revealEls = document.querySelectorAll('.reveal,.reveal-left,.reveal-right,.reveal-scale');
    var revealObs = new IntersectionObserver(function(entries){
        entries.forEach(function(e){
            if(e.isIntersecting){ e.target.classList.add('visible'); revealObs.unobserve(e.target); }
        });
    }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });
    revealEls.forEach(function(el){ revealObs.observe(el); });

    /* ── ANIMATED COUNTERS ── */
    var counters = document.querySelectorAll('.counter[data-target]');
    var counterObs = new IntersectionObserver(function(entries){
        entries.forEach(function(e){
            if(!e.isIntersecting) return;
            var el = e.target;
            var target = +el.getAttribute('data-target');
            var suffix = el.getAttribute('data-suffix') || '';
            var fmt    = el.getAttribute('data-format');
            var steps  = 80, step = 0;
            var timer  = setInterval(function(){
                step++;
                var progress = 1 - Math.pow(1 - step/steps, 3);
                var cur = Math.floor(progress * target);
                if(fmt === 'crore'){
                    el.textContent = '₹' + (cur/10000000).toFixed(1) + ' Cr';
                } else {
                    el.textContent = cur.toLocaleString('en-IN') + suffix;
                }
                if(step >= steps){
                    clearInterval(timer);
                    el.textContent = fmt === 'crore' ? '₹10 Cr+' : target.toLocaleString('en-IN') + suffix;
                }
            }, 18);
            counterObs.unobserve(el);
        });
    }, { threshold: 0.5 });
    counters.forEach(function(el){ counterObs.observe(el); });

    /* ── SCROLL TO TOP BTN ── */
    var btn = document.getElementById('scrollTopBtn');
    window.addEventListener('scroll', function(){
        if(window.scrollY > 600) btn.classList.add('visible');
        else btn.classList.remove('visible');
    }, { passive: true });

});

/* ── FAQ ACCORDION ── */
function toggleFaq(idx){
    var item = document.querySelector('[data-faq="'+idx+'"]');
    var isOpen = item.classList.contains('open');
    document.querySelectorAll('.faq-item').forEach(function(el){ el.classList.remove('open'); });
    if(!isOpen) item.classList.add('open');
}
</script>

@endsection