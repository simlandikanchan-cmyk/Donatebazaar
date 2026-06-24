@extends('layouts.app')

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300&family=DM+Mono:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,700;1,700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
/* ═══════════════════════════════════════════════════════════
   DESIGN SYSTEM — matches About page tokens exactly
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

*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth}
body{font-family:var(--font);color:var(--text);background:var(--bg);-webkit-font-smoothing:antialiased;overflow-x:hidden}
img{max-width:100%;display:block}
a{text-decoration:none;color:inherit}

/* ── Layout ── */
.container{max-width:1180px;margin:0 auto;padding:0 24px}

/* ── Typography ── */
.eyebrow{font-size:11px;font-weight:600;letter-spacing:.14em;text-transform:uppercase;color:var(--accent);font-family:var(--font-mono);display:inline-flex;align-items:center;gap:8px;margin-bottom:14px}
.eyebrow::before{content:'';width:20px;height:2px;background:var(--accent);border-radius:2px;flex-shrink:0}
.section-title{font-family:var(--font-display);font-size:clamp(2rem,3.5vw,2.8rem);font-weight:700;line-height:1.15;color:var(--text);margin-bottom:16px}
.section-title em{font-style:italic;color:var(--accent)}
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
.btn-ghost{background:transparent;color:var(--accent);border:1.5px solid var(--border2);font-size:13px;padding:10px 22px}
.btn-ghost:hover{background:var(--accent-glow);border-color:var(--accent)}

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
   1. HERO SLIDER
═══════════════════════════════════════════════════════════ */
.hero-wrap{position:relative;width:100%;height:100vh;min-height:620px;overflow:hidden;display:flex;flex-direction:column}
.hero-slide{position:absolute;inset:0;opacity:0;transition:opacity 1.2s ease-in-out;z-index:0}
.hero-slide.active{opacity:1;z-index:1}
.hero-slide img{width:100%;height:100%;object-fit:cover;object-position:center}
.hero-overlay{position:absolute;inset:0;background:linear-gradient(120deg,rgba(5,5,20,.9) 0%,rgba(10,10,35,.8) 45%,rgba(15,15,40,.5) 100%);display:flex;align-items:center;z-index:2}
.hero-grid-lines{position:absolute;inset:0;z-index:2;background-image:linear-gradient(rgba(99,102,241,.05) 1px,transparent 1px),linear-gradient(90deg,rgba(99,102,241,.05) 1px,transparent 1px);background-size:60px 60px;opacity:.5;pointer-events:none}

.hero-content{max-width:1180px;margin:0 auto;padding:0 24px;color:#fff;width:100%}
.hero-pill{display:inline-flex;align-items:center;gap:10px;background:rgba(255,255,255,.09);border:1px solid rgba(255,255,255,.2);backdrop-filter:blur(12px);border-radius:100px;padding:8px 20px;font-size:11.5px;font-weight:600;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,.8);width:fit-content;margin-bottom:28px;font-family:var(--font-mono)}
.hero-pill-dot{width:7px;height:7px;border-radius:50%;background:var(--green);animation:pulse-live 2s ease infinite;flex-shrink:0}
@keyframes pulse-live{0%,100%{opacity:1;box-shadow:0 0 0 0 rgba(16,185,129,.5)}50%{opacity:.8;box-shadow:0 0 0 6px rgba(16,185,129,0)}}

.hero-title{font-family:var(--font-display);font-size:clamp(2.8rem,5.5vw,4.2rem);font-weight:700;line-height:1.08;color:#fff;margin-bottom:22px;max-width:680px}
.hero-title em{font-style:italic;color:#a5b4fc}
.hero-desc{font-size:clamp(15px,1.8vw,17px);color:rgba(255,255,255,.65);font-weight:300;line-height:1.8;max-width:500px;margin-bottom:38px}
.hero-btns{display:flex;gap:12px;flex-wrap:wrap;margin-bottom:52px}
.hero-trust{display:flex;align-items:center;gap:20px;flex-wrap:wrap}
.hero-trust-item{display:flex;align-items:center;gap:7px;font-size:12.5px;color:rgba(255,255,255,.55)}
.hero-trust-item svg{width:14px;height:14px;color:var(--green)}
.hero-trust-sep{width:1px;height:16px;background:rgba(255,255,255,.15)}

/* Slider nav */
.hero-arrow{position:absolute;top:50%;transform:translateY(-50%);background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.2);backdrop-filter:blur(10px);color:#fff;width:48px;height:48px;border-radius:50%;display:flex;align-items:center;justify-content:center;cursor:pointer;z-index:10;transition:background var(--transition);font-size:0}
.hero-arrow svg{width:18px;height:18px}
.hero-arrow:hover{background:rgba(255,255,255,.22)}
#heroPrev{left:24px}
#heroNext{right:24px}
.hero-dots{position:absolute;bottom:24px;left:50%;transform:translateX(-50%);display:flex;gap:10px;z-index:10}
.hero-dot{width:8px;height:8px;border-radius:4px;background:rgba(255,255,255,.35);cursor:pointer;transition:background var(--transition),width var(--transition)}
.hero-dot.active{background:#fff;width:28px}

/* Floating stat cards */
.hero-float-cards{position:absolute;right:5%;top:50%;transform:translateY(-50%);display:flex;flex-direction:column;gap:12px;z-index:10}
@media(max-width:1100px){.hero-float-cards{display:none}}
.hero-float-card{background:rgba(255,255,255,.09);border:1px solid rgba(255,255,255,.15);backdrop-filter:blur(20px);border-radius:var(--radius);padding:16px 20px;min-width:190px;animation:float-card 4s ease-in-out infinite}
.hero-float-card:nth-child(2){animation-delay:1.3s}.hero-float-card:nth-child(3){animation-delay:2.6s}
@keyframes float-card{0%,100%{transform:translateY(0)}50%{transform:translateY(-8px)}}
.float-card-top{display:flex;align-items:center;gap:8px;margin-bottom:6px}
.float-card-icon{width:28px;height:28px;border-radius:8px;display:flex;align-items:center;justify-content:center}
.float-card-icon svg{width:14px;height:14px}
.float-card-label{font-size:10.5px;color:rgba(255,255,255,.5);font-family:var(--font-mono)}
.float-card-num{font-size:22px;font-weight:700;color:#fff;font-family:var(--font-mono);line-height:1}
.float-card-sub{font-size:10px;color:rgba(255,255,255,.4);margin-top:2px}

/* Bottom stat bar */
.hero-stat-bar{position:absolute;bottom:0;left:0;right:0;z-index:10;background:rgba(5,5,18,.88);backdrop-filter:blur(20px);border-top:1px solid rgba(255,255,255,.07);display:flex}
.hero-stat-item{flex:1;padding:20px;text-align:center;border-left:1px solid rgba(255,255,255,.06)}
.hero-stat-item:first-child{border-left:none}
.hero-stat-val{font-family:var(--font-mono);font-size:clamp(17px,2vw,24px);color:#fff;display:block;font-weight:700;line-height:1;margin-bottom:4px}
.hero-stat-lbl{font-size:10px;letter-spacing:1.4px;text-transform:uppercase;color:rgba(255,255,255,.35);font-family:var(--font-mono)}
@media(max-width:600px){.hero-stat-bar{position:static;flex-wrap:wrap}.hero-stat-item{flex:1 1 50%;border-top:1px solid rgba(255,255,255,.06)}.hero-stat-item:nth-child(odd){border-left:none}}


/* ═══════════════════════════════════════════════════════════
   2. MARQUEE
═══════════════════════════════════════════════════════════ */
.marquee-band{background:#07080f;overflow:hidden;border-top:1px solid rgba(255,255,255,.04);border-bottom:1px solid rgba(255,255,255,.04)}
.marquee-inner{display:flex;width:max-content;animation:marquee 28s linear infinite}
.marquee-inner:hover{animation-play-state:paused}
.marquee-row{display:flex;padding:14px 0}
.m-item{display:inline-flex;align-items:center;gap:10px;padding:0 36px;font-size:11px;font-weight:600;color:rgba(165,180,252,.6);letter-spacing:.12em;text-transform:uppercase;font-family:var(--font-mono);white-space:nowrap}
.m-dot{width:4px;height:4px;border-radius:50%;background:var(--accent2);flex-shrink:0}
@keyframes marquee{0%{transform:translateX(0)}100%{transform:translateX(-50%)}}


/* ═══════════════════════════════════════════════════════════
   3. MEDIA TRUST BAR
═══════════════════════════════════════════════════════════ */
.media-section{background:var(--surface);padding:40px 0;border-bottom:1px solid var(--border)}
.media-inner{display:flex;align-items:center;gap:40px;flex-wrap:wrap;justify-content:center}
.media-label{font-size:11px;font-weight:600;letter-spacing:.12em;text-transform:uppercase;color:var(--text3);font-family:var(--font-mono);flex-shrink:0}
.media-divider{width:1px;height:28px;background:var(--border2)}
.media-logos{display:flex;align-items:center;gap:36px;flex-wrap:wrap;justify-content:center}
.media-logo{font-family:var(--font-display);font-size:15px;font-weight:700;color:var(--text3);opacity:.45;transition:opacity var(--transition);cursor:default}
.media-logo:hover{opacity:.8}


/* ═══════════════════════════════════════════════════════════
   4. CATEGORIES
═══════════════════════════════════════════════════════════ */
.categories-section{background:var(--surface);padding:100px 0;position:relative;overflow:hidden}
.categories-section::before{content:'';position:absolute;top:-200px;right:-200px;width:500px;height:500px;border-radius:50%;background:radial-gradient(circle,rgba(99,102,241,.07) 0%,transparent 70%);pointer-events:none}
.cat-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:18px}
.cat-card{position:relative;border-radius:20px;overflow:hidden;border:1.5px solid var(--border2);background:var(--surface2);padding:28px 16px 24px;text-align:center;text-decoration:none;color:var(--text);transition:transform var(--transition),box-shadow var(--transition),border-color var(--transition)}
.cat-card::before{content:'';position:absolute;inset:0;background:linear-gradient(135deg,rgba(99,102,241,.06),rgba(79,70,229,.06));opacity:0;transition:opacity var(--transition)}
.cat-card:hover{transform:translateY(-6px);box-shadow:0 20px 48px rgba(99,102,241,.14);border-color:rgba(99,102,241,.3)}
.cat-card:hover::before{opacity:1}
.cat-icon{width:60px;height:60px;border-radius:16px;margin:0 auto 16px;background:linear-gradient(135deg,var(--accent),var(--accent2));display:flex;align-items:center;justify-content:center;box-shadow:0 8px 20px rgba(99,102,241,.3);transition:transform var(--transition)}
.cat-card:hover .cat-icon{transform:scale(1.1) rotate(-4deg)}
.cat-icon i{color:#fff;font-size:20px}
.cat-name{font-weight:600;font-size:13.5px;color:var(--text);margin-bottom:4px}
.cat-count{font-size:11.5px;color:var(--text3);font-family:var(--font-mono)}
.cat-accent-bar{position:absolute;bottom:0;left:0;right:0;height:2px;background:linear-gradient(90deg,var(--accent),var(--accent2));transform:scaleX(0);transition:transform var(--transition);transform-origin:left}
.cat-card:hover .cat-accent-bar{transform:scaleX(1)}


/* ═══════════════════════════════════════════════════════════
   5. CAMPAIGNS
═══════════════════════════════════════════════════════════ */
.campaigns-section{background:var(--bg);padding:100px 0}
.camp-filter-wrap{display:flex;align-items:center;gap:8px;flex-wrap:wrap;margin-bottom:40px;justify-content:center}
.camp-filter-btn{padding:9px 20px;border-radius:100px;font-size:13px;font-weight:500;cursor:pointer;border:1.5px solid var(--border2);background:var(--surface);color:var(--text2);font-family:var(--font);transition:all var(--transition);white-space:nowrap}
.camp-filter-btn:hover{border-color:rgba(99,102,241,.4);color:var(--accent);background:var(--accent-glow)}
.camp-filter-btn.active{background:linear-gradient(135deg,var(--accent),var(--accent2));color:#fff;border-color:transparent;box-shadow:0 4px 16px rgba(99,102,241,.35)}
.camp-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:24px}
.camp-card{background:var(--surface);border-radius:var(--radius-lg);border:1.5px solid var(--border2);overflow:hidden;transition:transform var(--transition),box-shadow var(--transition),border-color var(--transition)}
.camp-card:hover{transform:translateY(-6px);box-shadow:0 24px 60px rgba(99,102,241,.12);border-color:rgba(99,102,241,.25)}
.camp-card.hidden{display:none}
.camp-img{position:relative;height:210px;overflow:hidden}
.camp-img img{width:100%;height:100%;object-fit:cover;transition:transform .5s ease}
.camp-card:hover .camp-img img{transform:scale(1.05)}
.camp-badge{position:absolute;top:14px;left:14px;background:rgba(255,255,255,.93);backdrop-filter:blur(8px);color:#1e1b4b;font-size:11.5px;font-weight:600;padding:5px 14px;border-radius:100px;border:1px solid rgba(99,102,241,.15)}
.camp-verified{position:absolute;top:14px;right:14px;background:#ecfdf5;color:#065f46;font-size:11px;font-weight:600;padding:4px 12px;border-radius:100px;border:1px solid #a7f3d0;display:flex;align-items:center;gap:5px}
.camp-verified::before{content:'';width:6px;height:6px;border-radius:50%;background:#10b981}
.camp-body{padding:22px 24px 24px}
.camp-title{font-weight:600;font-size:15.5px;color:var(--text);margin-bottom:14px;line-height:1.4;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
.camp-progress-track{height:5px;background:var(--surface3);border-radius:3px;margin-bottom:10px;overflow:hidden}
.camp-progress-fill{height:100%;border-radius:3px;background:linear-gradient(90deg,var(--accent),var(--accent2));transition:width .8s cubic-bezier(.4,0,.2,1)}
.camp-meta{display:flex;justify-content:space-between;font-size:13px;color:var(--text3);margin-bottom:5px}
.camp-meta strong{color:var(--text);font-weight:600}
.camp-donors{font-size:11.5px;color:var(--text3);margin-bottom:18px;font-family:var(--font-mono)}
.camp-btn{display:block;text-align:center;background:linear-gradient(135deg,var(--accent),var(--accent2));color:#fff;padding:13px;border-radius:var(--radius);font-weight:600;font-size:13.5px;transition:opacity var(--transition),transform var(--transition);box-shadow:0 4px 14px rgba(99,102,241,.35)}
.camp-btn:hover{opacity:.9;transform:translateY(-2px)}
.camp-filter-empty{display:none;text-align:center;padding:60px 20px;color:var(--text3);font-size:14px;grid-column:1/-1}

.infinite-loader{text-align:center;margin-top:48px}
.infinite-loader-inner{display:inline-flex;align-items:center;gap:10px;color:var(--text3);font-size:13px;font-family:var(--font-mono)}
.loader-spinner{animation:spin 1s linear infinite;display:none}
@keyframes spin{0%{transform:rotate(0deg)}100%{transform:rotate(360deg)}}


/* ═══════════════════════════════════════════════════════════
   6. STATS STRIP
═══════════════════════════════════════════════════════════ */
.stats-strip{background:linear-gradient(160deg,#07080f 0%,#0d0e1a 50%,#13141f 100%);position:relative;overflow:hidden}
.stats-strip::before{content:'';position:absolute;top:-200px;left:-100px;width:600px;height:600px;border-radius:50%;background:radial-gradient(circle,rgba(99,102,241,.07) 0%,transparent 70%);pointer-events:none}
.stats-inner{max-width:1180px;margin:0 auto;padding:0 24px;display:grid;grid-template-columns:repeat(4,1fr)}
@media(max-width:700px){.stats-inner{grid-template-columns:repeat(2,1fr)}}
.stat-tile{padding:56px 28px;text-align:center;border-left:1px solid rgba(255,255,255,.06);position:relative;overflow:hidden;cursor:default}
.stat-tile:first-child{border-left:none}
@media(max-width:700px){.stat-tile:nth-child(odd){border-left:none}.stat-tile{border-top:1px solid rgba(255,255,255,.06)}.stat-tile:first-child,.stat-tile:nth-child(2){border-top:none}}
.stat-tile::after{content:'';position:absolute;top:0;left:50%;right:50%;height:2px;background:linear-gradient(90deg,var(--accent),var(--accent2));transition:left .4s,right .4s}
.stat-tile:hover::after{left:0;right:0}
.stat-tile-icon{width:44px;height:44px;border-radius:12px;background:rgba(99,102,241,.1);border:1px solid rgba(99,102,241,.2);display:flex;align-items:center;justify-content:center;margin:0 auto 14px}
.stat-tile-icon svg{width:20px;height:20px;color:#a5b4fc}
.stat-tile-num{font-family:var(--font-mono);font-size:clamp(32px,4vw,48px);font-weight:700;color:#fff;display:block;line-height:1;margin-bottom:8px}
.stat-tile-num .suf{color:var(--accent)}
.stat-tile-lbl{font-size:10.5px;letter-spacing:1.4px;text-transform:uppercase;color:rgba(255,255,255,.35);font-family:var(--font-mono)}
.stat-tile-sub{font-size:11.5px;color:rgba(255,255,255,.2);margin-top:4px;font-family:var(--font)}


/* ═══════════════════════════════════════════════════════════
   7. HOW IT WORKS
═══════════════════════════════════════════════════════════ */
.how-section{padding:110px 0;background:linear-gradient(160deg,#07080f 0%,#0d0e1a 50%,#13141f 100%);position:relative;overflow:hidden}
.how-section::before{content:'';position:absolute;top:-200px;left:-150px;width:700px;height:700px;border-radius:50%;background:radial-gradient(circle,rgba(99,102,241,.09) 0%,transparent 70%);pointer-events:none}
.how-section::after{content:'';position:absolute;bottom:-150px;right:-100px;width:600px;height:600px;border-radius:50%;background:radial-gradient(circle,rgba(139,92,246,.07) 0%,transparent 70%);pointer-events:none}
.how-section .section-title{color:#fff}
.how-section .section-sub{color:rgba(255,255,255,.45)}
.how-grid{display:grid;grid-template-columns:1fr 1fr;gap:22px;position:relative;z-index:1}
@media(max-width:768px){.how-grid{grid-template-columns:1fr}}
.how-card-glass{background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);backdrop-filter:blur(24px);border-radius:24px;padding:36px;position:relative;overflow:hidden;transition:all var(--transition)}
.how-card-glass:hover{background:rgba(255,255,255,.09);border-color:rgba(99,102,241,.3);transform:translateY(-4px)}
.how-card-glass::before{content:'';position:absolute;top:0;left:0;right:0;height:2px;background:linear-gradient(90deg,var(--accent),var(--accent2));transform:scaleX(0);transition:transform .4s;transform-origin:left;border-radius:24px 24px 0 0}
.how-card-glass:hover::before{transform:scaleX(1)}
.how-card-label{font-family:var(--font-display);font-size:20px;font-weight:700;color:#fff;margin-bottom:6px}
.how-card-div{height:1px;background:rgba(255,255,255,.12);margin-bottom:28px}
.how-step{display:flex;gap:16px;align-items:flex-start;padding-bottom:22px;margin-bottom:22px;border-bottom:1px solid rgba(255,255,255,.08)}
.how-step:last-of-type{border-bottom:none;padding-bottom:0;margin-bottom:22px}
.how-step-icon{width:48px;height:48px;border-radius:14px;flex-shrink:0;background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;transition:background var(--transition)}
.how-step:hover .how-step-icon{background:rgba(99,102,241,.25)}
.how-step-icon svg{width:20px;height:20px;color:#c4b5fd}
.how-step-title{font-size:14.5px;font-weight:600;color:#fff;margin-bottom:3px}
.how-step-desc{font-size:12.5px;color:rgba(255,255,255,.55);line-height:1.65}
.how-cta-btn{display:block;text-align:center;padding:14px;border-radius:var(--radius);font-weight:600;font-size:14px;transition:transform var(--transition),opacity var(--transition)}
.how-cta-orange{background:linear-gradient(135deg,#f97316,#ea580c);color:#fff;box-shadow:0 6px 20px rgba(249,115,22,.4)}
.how-cta-blue{background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.25);color:#fff}
.how-cta-btn:hover{transform:translateY(-2px);opacity:.92}


/* ═══════════════════════════════════════════════════════════
   8. TRUST PILLARS
═══════════════════════════════════════════════════════════ */
.trust-section{background:var(--surface);padding:110px 0}
.trust-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:18px}
@media(max-width:900px){.trust-grid{grid-template-columns:repeat(2,1fr)}}
@media(max-width:560px){.trust-grid{grid-template-columns:1fr}}
.trust-card{background:var(--surface2);border-radius:var(--radius);border:1px solid var(--border2);padding:28px;display:flex;flex-direction:column;gap:14px;transition:all var(--transition);box-shadow:var(--shadow);position:relative;overflow:hidden}
.trust-card::before{content:'';position:absolute;top:0;left:0;right:0;height:3px;background:var(--tc-color,var(--accent));opacity:0;transition:opacity var(--transition)}
.trust-card:hover{transform:translateY(-5px);box-shadow:0 20px 48px rgba(99,102,241,.1);border-color:rgba(99,102,241,.25)}
.trust-card:hover::before{opacity:1}
.trust-icon-wrap{width:52px;height:52px;border-radius:14px;display:flex;align-items:center;justify-content:center}
.trust-icon-wrap svg{width:24px;height:24px}
.trust-card-title{font-size:15px;font-weight:600;color:var(--text);margin-bottom:5px}
.trust-card-desc{font-size:13px;color:var(--text2);line-height:1.7;font-weight:300}
.trust-card-badge{display:inline-flex;align-items:center;gap:5px;font-size:11px;font-weight:600;color:var(--text3);font-family:var(--font-mono);background:var(--surface3);padding:4px 10px;border-radius:100px;border:1px solid var(--border2);margin-top:6px;width:fit-content}


/* ═══════════════════════════════════════════════════════════
   9. TESTIMONIALS
═══════════════════════════════════════════════════════════ */
.testimonials-section{background:var(--bg);padding:110px 0}
.testi-tabs{display:flex;gap:10px;margin-bottom:40px;justify-content:center}
.testi-tab-btn{padding:10px 24px;border-radius:100px;font-size:13px;font-weight:600;border:1.5px solid var(--border2);background:var(--surface);color:var(--text2);cursor:pointer;transition:all var(--transition);font-family:var(--font)}
.testi-tab-btn.active{background:linear-gradient(135deg,var(--accent),var(--accent2));color:#fff;border-color:transparent;box-shadow:0 4px 14px rgba(99,102,241,.35)}
.testi-tab-pane{display:none}
.testi-tab-pane.active{display:block}
.testi-track{overflow:hidden}
.testi-slider{display:flex;gap:20px;transition:transform .5s cubic-bezier(.4,0,.2,1)}
.testi-card{min-width:300px;background:var(--surface);border:1.5px solid var(--border2);border-radius:var(--radius);padding:24px;transition:border-color var(--transition),box-shadow var(--transition)}
.testi-card:hover{border-color:rgba(99,102,241,.25);box-shadow:0 8px 24px rgba(99,102,241,.08)}
.testi-quote-icon{color:var(--accent);opacity:.3;font-size:32px;line-height:1;margin-bottom:10px}
.testi-badge{display:inline-block;font-size:11px;font-weight:600;padding:4px 12px;border-radius:100px;margin-bottom:12px}
.badge-blue{background:rgba(99,102,241,.1);color:var(--accent)}
.badge-green{background:#d1fae5;color:#065f46}
.badge-purple{background:rgba(139,92,246,.1);color:var(--accent2)}
.testi-text{font-size:13px;color:var(--text2);line-height:1.75;margin-bottom:18px;font-weight:300;font-style:italic}
.testi-author{display:flex;align-items:center;gap:12px;padding-top:16px;border-top:1px solid var(--border)}
.testi-avatar{width:38px;height:38px;border-radius:50%;object-fit:cover}
.testi-name{font-size:13px;font-weight:600;color:var(--text)}
.testi-role{font-size:11px;color:var(--text3);margin-top:1px}


/* ═══════════════════════════════════════════════════════════
   10. IMPACT MAP
═══════════════════════════════════════════════════════════ */
.impact-section{background:var(--surface);padding:100px 0}
.impact-grid{display:grid;grid-template-columns:1fr 380px;gap:48px;align-items:start}
@media(max-width:900px){.impact-grid{grid-template-columns:1fr}}
.impact-map-img{width:100%;border-radius:var(--radius-lg);box-shadow:var(--shadow-lg);transition:transform .5s;display:block}
.impact-map-img:hover{transform:scale(1.02)}
.impact-stats-card{background:var(--surface2);border:1px solid var(--border2);border-radius:var(--radius-lg);padding:32px;position:sticky;top:100px}
.impact-stats-title{font-family:var(--font-display);font-size:18px;font-weight:700;color:var(--text);margin-bottom:24px}
.impact-row{display:flex;justify-content:space-between;align-items:center;padding:13px 0;border-bottom:1px solid var(--border)}
.impact-row:last-child{border-bottom:none}
.impact-state{font-size:14px;color:var(--text2)}
.impact-count{font-size:15px;font-weight:700;color:var(--accent);font-family:var(--font-mono)}


/* ═══════════════════════════════════════════════════════════
   11. BLOG SECTION  ← NEW
═══════════════════════════════════════════════════════════ */
.blog-section{background:var(--bg);padding:110px 0;position:relative;overflow:hidden}
.blog-section::before{content:'';position:absolute;bottom:-200px;right:-150px;width:600px;height:600px;border-radius:50%;background:radial-gradient(circle,rgba(99,102,241,.05) 0%,transparent 70%);pointer-events:none}

/* Section header row with "View All" link */
.blog-header-row{display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:64px;gap:24px}
@media(max-width:680px){.blog-header-row{flex-direction:column;align-items:flex-start}}
.blog-header-left .eyebrow{margin-bottom:12px}

/* Featured + list layout */
.blog-layout{display:grid;grid-template-columns:1.4fr 1fr;gap:28px;align-items:start}
@media(max-width:900px){.blog-layout{grid-template-columns:1fr}}

/* ── Featured card (big) ── */
.blog-featured{background:var(--surface);border:1px solid var(--border2);border-radius:var(--radius-lg);overflow:hidden;transition:all var(--transition);box-shadow:var(--shadow);display:flex;flex-direction:column}
.blog-featured:hover{transform:translateY(-5px);box-shadow:var(--shadow-lg);border-color:rgba(99,102,241,.2)}
.blog-featured-img{position:relative;height:280px;overflow:hidden}
.blog-featured-img img{width:100%;height:100%;object-fit:cover;transition:transform .6s ease}
.blog-featured:hover .blog-featured-img img{transform:scale(1.05)}
.blog-featured-cat{position:absolute;top:16px;left:16px;background:linear-gradient(135deg,var(--accent),var(--accent2));color:#fff;font-size:11px;font-weight:700;padding:5px 14px;border-radius:100px;font-family:var(--font-mono);letter-spacing:.05em}
.blog-featured-body{padding:28px}
.blog-featured-meta{display:flex;align-items:center;gap:14px;margin-bottom:14px;flex-wrap:wrap}
.blog-meta-item{display:flex;align-items:center;gap:6px;font-size:11.5px;color:var(--text3);font-family:var(--font-mono)}
.blog-meta-item svg{width:13px;height:13px;flex-shrink:0}
.blog-featured-title{font-family:var(--font-display);font-size:clamp(1.3rem,2vw,1.7rem);font-weight:700;color:var(--text);line-height:1.25;margin-bottom:14px;transition:color var(--transition)}
.blog-featured:hover .blog-featured-title{color:var(--accent)}
.blog-featured-excerpt{font-size:14px;color:var(--text2);line-height:1.8;font-weight:300;margin-bottom:22px;display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden}
.blog-author-row{display:flex;align-items:center;gap:12px;padding-top:20px;border-top:1px solid var(--border)}
.blog-author-avatar{width:36px;height:36px;border-radius:50%;object-fit:cover;background:linear-gradient(135deg,var(--accent),var(--accent2));display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:700;color:#fff;font-family:var(--font-mono);flex-shrink:0}
.blog-author-name{font-size:13px;font-weight:600;color:var(--text)}
.blog-author-date{font-size:11.5px;color:var(--text3);margin-top:1px}
.blog-read-link{margin-left:auto;display:flex;align-items:center;gap:5px;font-size:12.5px;font-weight:600;color:var(--accent);transition:gap var(--transition)}
.blog-featured:hover .blog-read-link{gap:8px}
.blog-read-link svg{width:13px;height:13px}

/* ── Side list of small blog cards ── */
.blog-list{display:flex;flex-direction:column;gap:16px}
.blog-list-card{background:var(--surface);border:1px solid var(--border2);border-radius:var(--radius);overflow:hidden;display:flex;gap:0;transition:all var(--transition);box-shadow:var(--shadow)}
.blog-list-card:hover{transform:translateX(4px);box-shadow:var(--shadow-md);border-color:rgba(99,102,241,.2)}
.blog-list-img{width:110px;flex-shrink:0;overflow:hidden}
.blog-list-img img{width:100%;height:100%;object-fit:cover;transition:transform .5s}
.blog-list-card:hover .blog-list-img img{transform:scale(1.08)}
.blog-list-body{padding:16px 18px;display:flex;flex-direction:column;justify-content:center;flex:1}
.blog-list-cat{font-size:10px;font-weight:700;color:var(--accent);font-family:var(--font-mono);letter-spacing:.07em;text-transform:uppercase;margin-bottom:6px}
.blog-list-title{font-size:13.5px;font-weight:600;color:var(--text);line-height:1.4;margin-bottom:8px;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;transition:color var(--transition)}
.blog-list-card:hover .blog-list-title{color:var(--accent)}
.blog-list-meta{display:flex;align-items:center;gap:8px;font-size:11px;color:var(--text3);font-family:var(--font-mono)}
.blog-list-dot{width:3px;height:3px;border-radius:50%;background:var(--text3)}

/* ── Blog bottom row (3 mini cards) ── */
.blog-bottom-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:20px;margin-top:28px}
@media(max-width:768px){.blog-bottom-grid{grid-template-columns:1fr 1fr}}
@media(max-width:480px){.blog-bottom-grid{grid-template-columns:1fr}}
.blog-mini-card{background:var(--surface);border:1px solid var(--border2);border-radius:var(--radius);overflow:hidden;transition:all var(--transition);box-shadow:var(--shadow)}
.blog-mini-card:hover{transform:translateY(-4px);box-shadow:var(--shadow-md);border-color:rgba(99,102,241,.2)}
.blog-mini-img{height:160px;overflow:hidden;position:relative}
.blog-mini-img img{width:100%;height:100%;object-fit:cover;transition:transform .5s}
.blog-mini-card:hover .blog-mini-img img{transform:scale(1.06)}
.blog-mini-cat-chip{position:absolute;bottom:12px;left:12px;background:rgba(99,102,241,.9);backdrop-filter:blur(4px);color:#fff;font-size:10px;font-weight:700;padding:3px 10px;border-radius:100px;font-family:var(--font-mono)}
.blog-mini-body{padding:16px}
.blog-mini-title{font-size:13px;font-weight:600;color:var(--text);line-height:1.4;margin-bottom:10px;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;transition:color var(--transition)}
.blog-mini-card:hover .blog-mini-title{color:var(--accent)}
.blog-mini-footer{display:flex;align-items:center;justify-content:space-between;font-size:11px;color:var(--text3);font-family:var(--font-mono)}
.blog-mini-read{display:flex;align-items:center;gap:4px;color:var(--accent);font-weight:600;font-size:11px;transition:gap var(--transition)}
.blog-mini-card:hover .blog-mini-read{gap:6px}
.blog-mini-read svg{width:10px;height:10px}

/* ── Newsletter strip inside blog section ── */
.blog-newsletter{background:linear-gradient(135deg,var(--accent),var(--accent2));border-radius:var(--radius-lg);padding:40px 48px;display:flex;align-items:center;gap:32px;margin-top:56px;position:relative;overflow:hidden}
@media(max-width:768px){.blog-newsletter{flex-direction:column;padding:32px 28px;text-align:center}}
.blog-newsletter::before{content:'';position:absolute;top:-60px;right:-60px;width:200px;height:200px;border-radius:50%;background:rgba(255,255,255,.08);pointer-events:none}
.blog-newsletter::after{content:'';position:absolute;bottom:-40px;right:100px;width:140px;height:140px;border-radius:50%;background:rgba(255,255,255,.05);pointer-events:none}
.blog-nl-icon{width:56px;height:56px;border-radius:16px;background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;flex-shrink:0}
.blog-nl-icon svg{width:24px;height:24px;color:#fff}
.blog-nl-text{flex:1}
.blog-nl-title{font-family:var(--font-display);font-size:20px;font-weight:700;color:#fff;margin-bottom:4px}
.blog-nl-sub{font-size:13.5px;color:rgba(255,255,255,.75);font-weight:300}
.blog-nl-form{display:flex;gap:10px;flex-shrink:0}
@media(max-width:768px){.blog-nl-form{width:100%;justify-content:center;flex-wrap:wrap}}
.blog-nl-input{padding:12px 18px;border-radius:var(--radius);border:none;background:rgba(255,255,255,.15);backdrop-filter:blur(8px);color:#fff;font-family:var(--font);font-size:13.5px;width:220px;outline:none;border:1px solid rgba(255,255,255,.25);transition:background var(--transition)}
.blog-nl-input::placeholder{color:rgba(255,255,255,.55)}
.blog-nl-input:focus{background:rgba(255,255,255,.22)}
.blog-nl-btn{padding:12px 24px;border-radius:var(--radius);background:#fff;color:var(--accent);font-weight:700;font-size:13.5px;border:none;cursor:pointer;font-family:var(--font);transition:all var(--transition);white-space:nowrap}
.blog-nl-btn:hover{transform:translateY(-2px);box-shadow:0 8px 20px rgba(0,0,0,.15)}


/* ═══════════════════════════════════════════════════════════
   12. CTA BANNER
═══════════════════════════════════════════════════════════ */
.cta-section{position:relative;overflow:hidden;padding:120px 0;text-align:center;background:linear-gradient(160deg,#07080f 0%,#0d0e1a 50%,#13141f 100%)}
.cta-section::before{content:'';position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:800px;height:800px;border-radius:50%;background:radial-gradient(circle,rgba(99,102,241,.12) 0%,transparent 70%);pointer-events:none}
.cta-bg-img{position:absolute;inset:0;z-index:0}
.cta-bg-img img{width:100%;height:100%;object-fit:cover;opacity:.15}
.cta-inner{position:relative;z-index:1;max-width:640px;margin:0 auto;padding:0 24px}
.cta-title{font-family:var(--font-display);font-size:clamp(2rem,4.5vw,3.2rem);font-weight:700;color:#fff;margin-bottom:16px;line-height:1.1}
.cta-title em{font-style:italic;color:#a5b4fc}
.cta-sub{font-size:15px;color:rgba(255,255,255,.55);font-weight:300;line-height:1.8;max-width:480px;margin:0 auto 36px}
.cta-btns{display:flex;gap:12px;justify-content:center;flex-wrap:wrap}
.cta-trust{display:flex;align-items:center;justify-content:center;gap:20px;margin-top:24px;flex-wrap:wrap}
.cta-trust-item{display:flex;align-items:center;gap:6px;font-size:12px;color:rgba(255,255,255,.4);font-family:var(--font-mono)}
.cta-trust-item svg{width:13px;height:13px;color:var(--green)}


/* ── Scroll top ── */
.scroll-top{position:fixed;bottom:24px;right:24px;width:44px;height:44px;border-radius:50%;background:var(--accent);color:#fff;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;box-shadow:0 6px 20px rgba(99,102,241,.45);opacity:0;transform:translateY(16px);transition:all var(--transition);z-index:999}
.scroll-top.visible{opacity:1;transform:translateY(0)}
.scroll-top:hover{transform:translateY(-2px);box-shadow:0 10px 28px rgba(99,102,241,.55)}
.scroll-top svg{width:18px;height:18px}

/* Responsive tweaks */
@media(max-width:768px){
    .hero-title{font-size:clamp(2.2rem,7vw,3rem)}
    .hero-desc{font-size:15px}
    .stats-inner{padding:0 12px}
    .stat-tile{padding:38px 18px}
    .blog-featured-img{height:220px}
    .blog-list-img{width:90px}
}
@media(max-width:480px){
    .hero-wrap{height:auto;min-height:100vh}
    .container{padding:0 16px}
    .categories-section,.campaigns-section,.trust-section,.testimonials-section,.impact-section,.blog-section{padding:72px 0}
    .how-section{padding:72px 0}
    .section-header{margin-bottom:40px}
    .blog-newsletter{padding:28px 20px}
    .blog-nl-input{width:100%}
}
</style>


{{-- ═══════════════════════════════════════════════════════════
     1. HERO SLIDER
═══════════════════════════════════════════════════════════ --}}
<div class="hero-wrap">
    {{-- Slide 1 --}}
    <div class="hero-slide active">
        <img src="{{ asset('images/2149012198.jpg') }}" alt="Be Someone's Hope Today">
        <div class="hero-overlay">
            <div class="hero-content">
                <div class="hero-pill"><span class="hero-pill-dot"></span>Trusted by 50,000+ Donors Across India</div>
                <h1 class="hero-title">Be Someone's<br><em>Hope Today</em></h1>
                <p class="hero-desc">Stand with people in crisis — from medical emergencies to education and disasters — every rupee can change a life.</p>
                <div class="hero-btns">
                    <a href="{{ route('all.campaigns') }}" class="btn btn-white">
                        Donate Now
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </a>
                    <a href="/campaign/create" class="btn btn-outline">
                        Start Fundraiser
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 4v16m8-8H4"/></svg>
                    </a>
                </div>
                <div class="hero-trust">
                    <div class="hero-trust-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg> Verified Campaigns</div>
                    <div class="hero-trust-sep"></div>
                    <div class="hero-trust-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg> 256-bit SSL Secure</div>
                    <div class="hero-trust-sep"></div>
                    <div class="hero-trust-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg> RBI Compliant</div>
                    <div class="hero-trust-sep"></div>
                    <div class="hero-trust-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg> 24×7 Support</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Slide 2 --}}
    <div class="hero-slide">
        <img src="{{ asset('images/2149012178.jpg') }}" alt="Save Children's Lives">
        <div class="hero-overlay">
            <div class="hero-content">
                <div class="hero-pill"><span class="hero-pill-dot"></span>Change a Child's Life</div>
                <h1 class="hero-title">Together Save<br><em>Precious Lives</em></h1>
                <p class="hero-desc">Help children access education, nutrition, and the care they deserve — creating brighter futures across every corner of India.</p>
                <div class="hero-btns">
                    <a href="{{ route('all.campaigns') }}" class="btn btn-white">Donate Now <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg></a>
                    <a href="/campaign/create" class="btn btn-outline">Start Fundraiser <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 4v16m8-8H4"/></svg></a>
                </div>
            </div>
        </div>
    </div>

    {{-- Slide 3 --}}
    <div class="hero-slide">
        <img src="{{ asset('images/18576.jpg') }}" alt="Be the Change">
        <div class="hero-overlay">
            <div class="hero-content">
                <div class="hero-pill"><span class="hero-pill-dot"></span>Be the Change</div>
                <h1 class="hero-title">Be the Reason<br><em>Someone Smiles</em></h1>
                <p class="hero-desc">Begin your journey of giving today — make a lasting difference in someone's life with complete transparency and trust.</p>
                <div class="hero-btns">
                    <a href="{{ route('all.campaigns') }}" class="btn btn-white">Donate Now <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg></a>
                    <a href="/campaign/create" class="btn btn-outline">Start Fundraiser <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 4v16m8-8H4"/></svg></a>
                </div>
            </div>
        </div>
    </div>

    <div class="hero-grid-lines"></div>

    {{-- Floating metric cards --}}
    <div class="hero-float-cards">
        <div class="hero-float-card">
            <div class="float-card-top">
                <div class="float-card-icon" style="background:rgba(16,185,129,.15)">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
                </div>
                <span class="float-card-label">Funds Raised</span>
            </div>
            <div class="float-card-num">₹10 Cr+</div>
            <div class="float-card-sub">and growing every day</div>
        </div>
        <div class="hero-float-card">
            <div class="float-card-top">
                <div class="float-card-icon" style="background:rgba(99,102,241,.15)">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#a5b4fc" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
                </div>
                <span class="float-card-label">Donors</span>
            </div>
            <div class="float-card-num">50K+</div>
            <div class="float-card-sub">across all 28 states</div>
        </div>
        <div class="hero-float-card">
            <div class="float-card-top">
                <div class="float-card-icon" style="background:rgba(245,158,11,.15)">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
                </div>
                <span class="float-card-label">Campaigns</span>
            </div>
            <div class="float-card-num">2,000+</div>
            <div class="float-card-sub">active &amp; verified</div>
        </div>
    </div>

    {{-- Slider nav --}}
    <button class="hero-arrow" id="heroPrev" aria-label="Previous">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
    </button>
    <button class="hero-arrow" id="heroNext" aria-label="Next">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
    </button>
    <div class="hero-dots">
        <span class="hero-dot active"></span>
        <span class="hero-dot"></span>
        <span class="hero-dot"></span>
    </div>

    {{-- Stat bar --}}
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


{{-- ═══ MARQUEE ═══ --}}
<div class="marquee-band">
    <div class="marquee-inner">
        <div class="marquee-row">
            @php $marqueeItems = ['Trusted by 50,000+ Donors','2,000+ Verified Campaigns','₹10 Crore+ Raised','Pan-India Coverage','RBI-Compliant Payments','256-bit SSL Encryption','24×7 Donor Support','100% Transparency Guaranteed','Featured in 15+ National Media']; @endphp
            @for($r=0;$r<3;$r++)
                @foreach($marqueeItems as $item)
                    <span class="m-item"><span class="m-dot"></span>{{ $item }}</span>
                @endforeach
            @endfor
        </div>
    </div>
</div>


{{-- ═══ MEDIA TRUST BAR ═══ --}}
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


{{-- ═══ CATEGORIES ═══ --}}
<section class="categories-section">
    <div class="container">
        <div class="section-header">
            <div class="eyebrow reveal" style="justify-content:center">Browse by cause</div>
            <h2 class="section-title reveal d1">Explore Our <em>Categories</em></h2>
            <p class="section-sub reveal d2">Discover causes that need your support — find what moves you and give with confidence.</p>
        </div>
        <div class="cat-grid">
            @foreach($categories as $category)
            <a href="{{ route('campaigns.byCategory', $category->slug) }}" class="cat-card reveal">
                <div class="cat-icon">
                    <i class="fa {{ $category->icon ?? 'fa-heart' }}"></i>
                </div>
                <div class="cat-name">{{ $category->name }}</div>
                <div class="cat-count">{{ $category->campaigns_count }} Campaigns</div>
                <div class="cat-accent-bar"></div>
            </a>
            @endforeach
        </div>
    </div>
</section>


{{-- ═══ FEATURED CAMPAIGNS ═══ --}}
<section class="campaigns-section">
    <div class="container">
        <div class="section-header">
            <div class="eyebrow reveal" style="justify-content:center">Make an impact</div>
            <h2 class="section-title reveal d1">Featured <em>Campaigns</em></h2>
            <p class="section-sub reveal d2">Support urgent and impactful causes across India — every donation is verified and tracked.</p>
        </div>

        <div class="camp-filter-wrap" id="campFilterWrap">
            <button class="camp-filter-btn active" data-cat="all">All</button>
            @foreach($categories as $category)
                <button class="camp-filter-btn" data-cat="{{ $category->slug }}">{{ $category->name }}</button>
            @endforeach
        </div>

        <div class="camp-grid" id="campaignContainer">
            <p class="camp-filter-empty" id="campEmpty">No campaigns found in this category.</p>
            @foreach($campaigns as $index => $campaign)
            @php
                $raised     = $campaign->donations->sum('amount');
                $goal       = $campaign->goal_amount;
                $percentage = $goal > 0 ? min(100, round(($raised / $goal) * 100)) : 0;
                $donors     = $campaign->donations->count();
            @endphp
            <div class="camp-card {{ $index >= 6 ? 'hidden' : '' }}" data-cat="{{ $campaign->category->slug ?? 'uncategorized' }}">
                <div class="camp-img">
                    <img loading="lazy" src="{{ asset('storage/' . $campaign->cover_image) }}" alt="{{ $campaign->title }}">
                    <div class="camp-badge">{{ $percentage }}% Funded</div>
                    <div class="camp-verified">Verified</div>
                </div>
                <div class="camp-body">
                    <h3 class="camp-title">{{ $campaign->title }}</h3>
                    <div class="camp-progress-track">
                        <div class="camp-progress-fill" style="width:{{ $percentage }}%"></div>
                    </div>
                    <div class="camp-meta">
                        <span><strong>₹{{ number_format($raised) }}</strong> raised</span>
                        <span>Goal <strong>₹{{ number_format($goal) }}</strong></span>
                    </div>
                    <div class="camp-donors">{{ $donors }} donors · Active Campaign</div>
                    <a href="{{ route('campaign.public', $campaign->slug) }}" class="camp-btn">Donate Now</a>
                </div>
            </div>
            @endforeach
        </div>

        <div class="infinite-loader" id="infiniteLoader">
            <div class="infinite-loader-inner">
                <svg class="loader-spinner" id="loaderSpinner" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>
                </svg>
                <span id="loaderText">Scroll to load more</span>
            </div>
        </div>
    </div>
</section>


{{-- ═══ STATS STRIP ═══ --}}
<div class="stats-strip">
    <div class="stats-inner">
        <div class="stat-tile">
            <div class="stat-tile-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg></div>
            <span class="stat-tile-num"><span class="counter" data-target="10000000" data-format="crore">₹10 Cr+</span></span>
            <span class="stat-tile-lbl">Funds Raised</span>
            <span class="stat-tile-sub">Since 2020</span>
        </div>
        <div class="stat-tile">
            <div class="stat-tile-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg></div>
            <span class="stat-tile-num"><span class="counter" data-target="50000">0</span><span class="suf">+</span></span>
            <span class="stat-tile-lbl">Donors</span>
            <span class="stat-tile-sub">Across all 28 states</span>
        </div>
        <div class="stat-tile">
            <div class="stat-tile-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg></div>
            <span class="stat-tile-num"><span class="counter" data-target="2000">0</span><span class="suf">+</span></span>
            <span class="stat-tile-lbl">Campaigns</span>
            <span class="stat-tile-sub">Fully verified</span>
        </div>
        <div class="stat-tile">
            <div class="stat-tile-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div>
            <span class="stat-tile-num">100<span class="suf">%</span></span>
            <span class="stat-tile-lbl">Transparency</span>
            <span class="stat-tile-sub">Zero hidden fees</span>
        </div>
    </div>
</div>


{{-- ═══ HOW IT WORKS ═══ --}}
<section class="how-section">
    <div class="container" style="position:relative;z-index:1">
        <div class="section-header">
            <div class="eyebrow reveal" style="color:#a5b4fc;justify-content:center">Simple, Transparent, Secure</div>
            <h2 class="section-title reveal d1">How It <em style="color:#a5b4fc">Works</em></h2>
            <p class="section-sub reveal d2" style="color:rgba(255,255,255,.45);margin:0 auto">Giving should feel good — not complicated. Here is how DonateBazaar makes it effortless.</p>
        </div>
        <div class="how-grid">
            <div class="how-card-glass reveal d1">
                <div class="how-card-label">For Donors</div>
                <div class="how-card-div"></div>
                <div class="how-step">
                    <div class="how-step-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg></div>
                    <div><div class="how-step-title">Choose Your Cause</div><div class="how-step-desc">Browse hundreds of verified campaigns by category — medical, education, disaster relief, and more.</div></div>
                </div>
                <div class="how-step">
                    <div class="how-step-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg></div>
                    <div><div class="how-step-title">Donate Securely</div><div class="how-step-desc">UPI, cards, or net banking — every transaction encrypted end-to-end through RBI-compliant gateways.</div></div>
                </div>
                <div class="how-step">
                    <div class="how-step-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg></div>
                    <div><div class="how-step-title">Track Your Impact</div><div class="how-step-desc">Receive real-time updates, photo reports, and 80G certificates automatically in your inbox.</div></div>
                </div>
                <a href="{{ url('/all-campaigns') }}" class="how-cta-btn how-cta-orange">Donate Now</a>
            </div>
            <div class="how-card-glass reveal d2">
                <div class="how-card-label">For Fundraisers</div>
                <div class="how-card-div"></div>
                <div class="how-step">
                    <div class="how-step-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg></div>
                    <div><div class="how-step-title">Begin Your Fundraiser</div><div class="how-step-desc">Start in minutes — share your story with the world and our team verifies it fast.</div></div>
                </div>
                <div class="how-step">
                    <div class="how-step-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.11c.54.5 1.25.81 2.04.81 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.5 9.31 6.79 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.79 0 1.5-.31 2.04-.81l7.12 4.16c-.05.21-.08.43-.08.65 0 1.61 1.31 2.92 2.92 2.92s2.92-1.31 2.92-2.92c0-1.61-1.31-2.92-2.92-2.92z"/></svg></div>
                    <div><div class="how-step-title">Spread the Word</div><div class="how-step-desc">Invite friends, family, and community to amplify your campaign reach across India.</div></div>
                </div>
                <div class="how-step">
                    <div class="how-step-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg></div>
                    <div><div class="how-step-title">Receive Funds Safely</div><div class="how-step-desc">Withdraw funds in milestone-based tranches with full documentation and donor notifications.</div></div>
                </div>
                <a href="{{ url('/campaign/create') }}" class="how-cta-btn how-cta-blue">Start a Campaign</a>
            </div>
        </div>
    </div>
</section>


{{-- ═══ TRUST PILLARS ═══ --}}
<section class="trust-section">
    <div class="container">
        <div class="section-header">
            <div class="eyebrow reveal" style="justify-content:center">Why trust us</div>
            <h2 class="section-title reveal d1">Built on <em>Integrity</em> &amp; Accountability</h2>
            <p class="section-sub reveal d2">Every feature is designed around one goal — your donation reaches the right person, at the right time, every time.</p>
        </div>
        @php
        $pillars = [
            ['bg'=>'#ede9fe','color'=>'#6366f1','tc'=>'#6366f1','title'=>'Rigorous Verification','desc'=>'Every campaign undergoes multi-step checks — document verification, identity validation, and periodic audits.','badge'=>'ISO Certified Process','svg'=>'<path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/>'],
            ['bg'=>'#d1fae5','color'=>'#10b981','tc'=>'#10b981','title'=>'Bank-Grade Security','desc'=>'256-bit SSL encryption on every transaction. Your payment details are never stored on our servers — ever.','badge'=>'PCI-DSS Compliant','svg'=>'<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>'],
            ['bg'=>'#fef3c7','color'=>'#f59e0b','tc'=>'#f59e0b','title'=>'Real-time Updates','desc'=>'Campaign creators post regular photo and video updates so you know exactly how your donation creates impact.','badge'=>'Live Tracking','svg'=>'<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>'],
            ['bg'=>'#ede9fe','color'=>'#8b5cf6','tc'=>'#8b5cf6','title'=>'Zero Hidden Fees','desc'=>'Complete transparency about platform costs — see exactly how much reaches the cause before you donate.','badge'=>'Full Breakdown','svg'=>'<line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/>'],
            ['bg'=>'#dbeafe','color'=>'#3b82f6','tc'=>'#3b82f6','title'=>'80G Tax Benefits','desc'=>'All eligible donations get 80G certificates automatically emailed to you. Save up to 50% on taxes.','badge'=>'Auto Tax Certificate','svg'=>'<path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/>'],
            ['bg'=>'#d1fae5','color'=>'#059669','tc'=>'#059669','title'=>'24×7 Donor Support','desc'=>'Our dedicated team is available around the clock via chat, email, and phone for all your needs.','badge'=>'Avg. 4 min response','svg'=>'<path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/>'],
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
            <p class="section-sub reveal d2">Thousands of donors trust DonateBazaar every month. Here is what some of them have to say.</p>
        </div>
        <div class="testi-tabs reveal d2">
            <button class="testi-tab-btn active" onclick="switchTestiTab('donors',this)">Donors</button>
            <button class="testi-tab-btn" onclick="switchTestiTab('ngos',this)">NGOs</button>
            <button class="testi-tab-btn" onclick="switchTestiTab('celebs',this)">Celebrities</button>
        </div>

        <div id="donors" class="testi-tab-pane active">
            <div class="testi-track"><div class="testi-slider" id="slider-donors">
                @for($i=1;$i<=10;$i++)
                <div class="testi-card">
                    <div class="testi-quote-icon">"</div>
                    <span class="testi-badge badge-blue">Contributed {{ $i+2 }} Times</span>
                    <p class="testi-text">Donating here makes me deeply happy. The real-time updates and 80G certificate arrive instantly — this is what trustworthy giving looks like.</p>
                    <div class="testi-author">
                        <img src="https://i.pravatar.cc/40?img={{ $i }}" class="testi-avatar" alt="">
                        <div><div class="testi-name">Donor {{ $i }}</div><div class="testi-role">Supporter · {{ ['Mumbai','Delhi','Bengaluru','Chennai','Pune','Kolkata','Hyderabad','Jaipur','Ahmedabad','Surat'][$i-1] }}</div></div>
                    </div>
                </div>
                @endfor
            </div></div>
        </div>

        <div id="ngos" class="testi-tab-pane">
            <div class="testi-track"><div class="testi-slider" id="slider-ngos">
                @for($i=1;$i<=10;$i++)
                <div class="testi-card">
                    <div class="testi-quote-icon">"</div>
                    <span class="testi-badge badge-green">NGO Partner</span>
                    <p class="testi-text">This platform helped our NGO reach donors we never could before. The verification process builds genuine confidence in our campaigns.</p>
                    <div class="testi-author">
                        <img src="https://i.pravatar.cc/40?img={{ $i+5 }}" class="testi-avatar" alt="">
                        <div><div class="testi-name">NGO Partner {{ $i }}</div><div class="testi-role">Organization · Verified</div></div>
                    </div>
                </div>
                @endfor
            </div></div>
        </div>

        <div id="celebs" class="testi-tab-pane">
            <div class="testi-track"><div class="testi-slider" id="slider-celebs">
                @for($i=1;$i<=10;$i++)
                <div class="testi-card">
                    <div class="testi-quote-icon">"</div>
                    <span class="testi-badge badge-purple">Celebrity Supporter</span>
                    <p class="testi-text">Giving back to society is important to me. DonateBazaar makes it easy to contribute meaningfully and track every rupee of impact.</p>
                    <div class="testi-author">
                        <img src="https://i.pravatar.cc/40?img={{ $i+10 }}" class="testi-avatar" alt="">
                        <div><div class="testi-name">Supporter {{ $i }}</div><div class="testi-role">Public Figure · Influencer</div></div>
                    </div>
                </div>
                @endfor
            </div></div>
        </div>
    </div>
</section>


{{-- ═══ IMPACT MAP ═══ --}}
<section class="impact-section">
    <div class="container">
        <div class="section-header">
            <div class="eyebrow reveal" style="justify-content:center">Our reach</div>
            <h2 class="section-title reveal d1"><em>Impact</em> Across India</h2>
            <p class="section-sub reveal d2">Together with our supporters, we are transforming lives in every corner of the country.</p>
        </div>
        <div class="impact-grid reveal">
            <div>
                <img src="{{ asset('images/map2.png') }}" alt="Impact Map" class="impact-map-img">
            </div>
            <div class="impact-stats-card">
                <div class="impact-stats-title">Lives Impacted by State</div>
                @php $impactData = ['Uttarakhand'=>66423,'Haryana'=>65751,'Rajasthan'=>59981,'Andhra Pradesh'=>42964,'Assam'=>27549,'Maharashtra'=>98234,'Karnataka'=>75431,'Tamil Nadu'=>61820]; @endphp
                @foreach($impactData as $state => $count)
                <div class="impact-row">
                    <span class="impact-state">{{ $state }}</span>
                    <span class="impact-count counter" data-target="{{ $count }}">0</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>


{{-- ═══════════════════════════════════════════════════════════
     BLOG SECTION ← NEW
═══════════════════════════════════════════════════════════ --}}
<section class="blog-section">
    <div class="container">
 
        {{-- Header row --}}
        <div class="blog-header-row">
            <div class="blog-header-left">
                <div class="eyebrow reveal">Stories &amp; Insights</div>
                <h2 class="section-title reveal d1" style="margin-bottom:8px">From Our <em>Blog</em></h2>
                <p class="section-sub reveal d2" style="margin:0;max-width:420px">Inspiring stories, fundraising tips, and news from the DonateBazaar community.</p>
            </div>
            <div class="reveal d3">
                <a href="{{ url('/blog') }}" class="btn btn-ghost">
                    View All Posts
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>
 
        @if(isset($blogs) && $blogs->count() > 0)
 
        {{-- Category Filter Buttons --}}
        @php
            $blogCats = $blogs->pluck('category')->filter()->unique()->values();
        @endphp
 
        @if($blogCats->count() > 1)
        <div class="blog-filter-wrap reveal" id="blogFilterWrap">
            <button class="blog-filter-btn active" data-blogcat="all">All</button>
            @foreach($blogCats as $bc)
                <button class="blog-filter-btn" data-blogcat="{{ Str::slug($bc) }}">{{ $bc }}</button>
            @endforeach
        </div>
        @endif
 
        {{-- Featured + Side list --}}
        <div class="blog-layout" id="blogMainLayout">
 
            {{-- FEATURED BLOG CARD --}}
            @php $featured = $blogs->first(); @endphp
            <a href="{{ url('/blog/' . $featured->slug) }}"
               class="blog-featured reveal-left blog-card-item"
               data-blogcat="{{ Str::slug($featured->category ?? '') }}">
                <div class="blog-featured-img">
                    <img src="{{ asset('storage/' . $featured->cover_image) }}" alt="{{ $featured->title }}" loading="lazy">
                    <div class="blog-featured-cat">{{ $featured->category ?? 'Featured' }}</div>
                </div>
                <div class="blog-featured-body">
                    <div class="blog-featured-meta">
                        <div class="blog-meta-item">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                            {{ $featured->created_at->format('d M Y') }}
                        </div>
                        <div class="blog-meta-item">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            {{ $featured->read_time ?? '5' }} min read
                        </div>
                        <div class="blog-meta-item">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            {{ number_format($featured->views ?? rand(800,3000)) }} views
                        </div>
                    </div>
                    <div class="blog-featured-title">{{ $featured->title }}</div>
                    <div class="blog-featured-excerpt">{{ $featured->excerpt ?? Str::limit(strip_tags($featured->content), 180) }}</div>
                    <div class="blog-author-row">
                        <div class="blog-author-avatar">{{ strtoupper(substr($featured->author->name ?? 'A', 0, 1)) }}</div>
                        <div>
                            <div class="blog-author-name">{{ $featured->author->name ?? 'DonateBazaar Team' }}</div>
                            <div class="blog-author-date">{{ $featured->created_at->format('d M Y') }}</div>
                        </div>
                        <div class="blog-read-link">
                            Read More
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </div>
                    </div>
                </div>
            </a>
 
            {{-- SIDE LIST CARDS --}}
            <div class="blog-list" id="blogSideList">
                @foreach($blogs->skip(1)->take(4) as $blog)
                <a href="{{ url('/blog/' . $blog->slug) }}"
                   class="blog-list-card reveal-right blog-card-item"
                   data-blogcat="{{ Str::slug($blog->category ?? '') }}">
                    <div class="blog-list-img">
                        <img src="{{ asset('storage/' . $blog->cover_image) }}" alt="{{ $blog->title }}" loading="lazy">
                    </div>
                    <div class="blog-list-body">
                        <div class="blog-list-cat">{{ $blog->category ?? 'Insight' }}</div>
                        <div class="blog-list-title">{{ $blog->title }}</div>
                        <div class="blog-list-meta">
                            <span>{{ $blog->created_at->format('d M Y') }}</span>
                            <span class="blog-list-dot"></span>
                            <span>{{ $blog->read_time ?? '4' }} min read</span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
 
        </div>
 
        {{-- No results message --}}
        <div id="blogNoResults" style="display:none;text-align:center;padding:48px 20px;color:var(--text3);font-size:14px;background:var(--surface);border:1px solid var(--border2);border-radius:var(--radius-lg);margin-top:8px">
            No blog posts found in this category.
        </div>
 
        {{-- BOTTOM MINI CARD ROW — only blogs from index 5 onwards --}}
        @if($blogs->count() > 5)
        <div class="blog-bottom-grid" id="blogBottomGrid">
            @foreach($blogs->skip(5)->take(3) as $mp)
            @php
                $gradients = ['linear-gradient(135deg,#6366f1,#8b5cf6)','linear-gradient(135deg,#3b82f6,#2563eb)','linear-gradient(135deg,#10b981,#059669)'];
                $gi = $loop->index % 3;
            @endphp
            <a href="{{ url('/blog/' . $mp->slug) }}"
               class="blog-mini-card reveal d{{ $loop->iteration }} blog-card-item"
               data-blogcat="{{ Str::slug($mp->category ?? '') }}">
                <div class="blog-mini-img">
                    @if($mp->cover_image)
                        <img src="{{ asset('storage/' . $mp->cover_image) }}" alt="{{ $mp->title }}" loading="lazy" style="width:100%;height:100%;object-fit:cover">
                    @else
                        <div style="width:100%;height:100%;background:{{ $gradients[$gi] }};display:flex;align-items:center;justify-content:center;opacity:.35">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.5"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
                        </div>
                    @endif
                    <div class="blog-mini-cat-chip">{{ $mp->category ?? 'Story' }}</div>
                </div>
                <div class="blog-mini-body">
                    <div class="blog-mini-title">{{ $mp->title }}</div>
                    <div class="blog-mini-footer">
                        <span>{{ $mp->created_at->format('d M Y') }}</span>
                        <span class="blog-mini-read">Read <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg></span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        @endif
 
        @else
        {{-- STATIC FALLBACK if no published blogs exist --}}
        <div class="blog-layout">
            <a href="{{ url('/blog') }}" class="blog-featured reveal-left">
                <div class="blog-featured-img" style="background:linear-gradient(135deg,#6366f1,#8b5cf6);height:280px;display:flex;align-items:center;justify-content:center">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,.5)" stroke-width="1.5"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                </div>
                <div class="blog-featured-body">
                    <div class="blog-featured-meta">
                        <div class="blog-meta-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="3" y1="10" x2="21" y2="10"/></svg>29 Apr 2026</div>
                        <div class="blog-meta-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>6 min read</div>
                    </div>
                    <div class="blog-featured-title">How Crowdfunding is Transforming Healthcare Access in Rural India</div>
                    <div class="blog-featured-excerpt">From remote villages in Rajasthan to tribal communities in Jharkhand, crowdfunding campaigns on DonateBazaar are bridging the healthcare gap that traditional systems have long struggled to close.</div>
                    <div class="blog-author-row">
                        <div class="blog-author-avatar">D</div>
                        <div><div class="blog-author-name">DonateBazaar Team</div><div class="blog-author-date">29 Apr 2026</div></div>
                        <div class="blog-read-link">Read More <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg></div>
                    </div>
                </div>
            </a>
            <div class="blog-list">
                @php
                $staticBlogs = [
                    ['cat'=>'Tips','title'=>'5 Ways to Make Your Fundraising Campaign Go Viral','date'=>'22 Apr 2026','read'=>'4'],
                    ['cat'=>'Story','title'=>'₹8 Lakh Raised in 72 Hours — The Kerala Flood Relief Campaign','date'=>'18 Apr 2026','read'=>'5'],
                    ['cat'=>'Guide','title'=>'How to Claim 80G Tax Deductions on Your DonateBazaar Donations','date'=>'12 Apr 2026','read'=>'3'],
                    ['cat'=>'News','title'=>'DonateBazaar Crosses ₹10 Crore Milestone — A Message of Gratitude','date'=>'05 Apr 2026','read'=>'2'],
                ];
                $blogColors = ['linear-gradient(135deg,#6366f1,#8b5cf6)','linear-gradient(135deg,#10b981,#059669)','linear-gradient(135deg,#f59e0b,#d97706)','linear-gradient(135deg,#3b82f6,#2563eb)'];
                @endphp
                @foreach($staticBlogs as $idx => $sb)
                <a href="{{ url('/blog') }}" class="blog-list-card reveal-right d{{ $idx+1 }}">
                    <div class="blog-list-img" style="background:{{ $blogColors[$idx] }};display:flex;align-items:center;justify-content:center">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,.6)" stroke-width="1.5"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    </div>
                    <div class="blog-list-body">
                        <div class="blog-list-cat">{{ $sb['cat'] }}</div>
                        <div class="blog-list-title">{{ $sb['title'] }}</div>
                        <div class="blog-list-meta"><span>{{ $sb['date'] }}</span><span class="blog-list-dot"></span><span>{{ $sb['read'] }} min read</span></div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif
 
        {{-- NEWSLETTER STRIP --}}
        <div class="blog-newsletter reveal">
            <div class="blog-nl-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
            </div>
            <div class="blog-nl-text">
                <div class="blog-nl-title">Get Stories That Matter</div>
                <div class="blog-nl-sub">Weekly impact stories, fundraising tips &amp; platform updates delivered to your inbox.</div>
            </div>
            <div class="blog-nl-form">
                <input type="email" class="blog-nl-input" placeholder="your@email.com" id="nlEmail">
                <button class="blog-nl-btn" onclick="subscribeNewsletter()">Subscribe</button>
            </div>
        </div>
 
    </div>
</section>
 


{{-- ═══ CTA BANNER ═══ --}}
<section class="cta-section">
    <div class="cta-bg-img"><img src="{{ asset('images/banner2.jpeg') }}" alt=""></div>
    <div class="cta-inner">
        <div class="eyebrow reveal" style="justify-content:center;color:#a5b4fc">Ready to help?</div>
        <h2 class="cta-title reveal d1">Change Someone's Life <em>Today</em></h2>
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
            <div class="cta-trust-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg> Verified Campaigns</div>
            <div class="cta-trust-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg> 100% Secure</div>
            <div class="cta-trust-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg> 80G Tax Benefits</div>
            <div class="cta-trust-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg> 24×7 Support</div>
        </div>
    </div>
</section>

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
    },{ threshold:0.08, rootMargin:'0px 0px -30px 0px' });
    revealEls.forEach(function(el){ revealObs.observe(el); });


    /* ── HERO SLIDER ── */
    var slides   = document.querySelectorAll('.hero-slide');
    var dots     = document.querySelectorAll('.hero-dot');
    var current  = 0, autoTimer;
    function showSlide(idx){
        slides.forEach(function(s,i){ s.classList.toggle('active', i===idx); dots[i].classList.toggle('active', i===idx); });
    }
    function nextSlide(){ current=(current+1)%slides.length; showSlide(current); }
    function prevSlide(){ current=(current-1+slides.length)%slides.length; showSlide(current); }
    function startAuto(){ autoTimer=setInterval(nextSlide,5000); }
    function stopAuto(){ clearInterval(autoTimer); }
    document.getElementById('heroNext').onclick=function(){ stopAuto(); nextSlide(); startAuto(); };
    document.getElementById('heroPrev').onclick=function(){ stopAuto(); prevSlide(); startAuto(); };
    dots.forEach(function(d,i){ d.onclick=function(){ stopAuto(); current=i; showSlide(i); startAuto(); }; });
    showSlide(0); startAuto();


    /* ── CAMPAIGNS — filter + infinite scroll ── */
    var allCards     = Array.from(document.querySelectorAll('.camp-card'));
    var loader       = document.getElementById('infiniteLoader');
    var spinner      = document.getElementById('loaderSpinner');
    var loaderTxt    = document.getElementById('loaderText');
    var campEmpty    = document.getElementById('campEmpty');
    var loading      = false, visibleCount = 0, activeFilter = 'all';
    var filteredCards= allCards.slice();

    function showNextBatch(){
        if(loading || visibleCount >= filteredCards.length) return;
        loading=true; spinner.style.display='inline-block'; loaderTxt.textContent='Loading...';
        setTimeout(function(){
            var end=Math.min(visibleCount+6,filteredCards.length);
            for(var i=visibleCount;i<end;i++) filteredCards[i].classList.remove('hidden');
            visibleCount=end; loading=false; spinner.style.display='none';
            if(visibleCount>=filteredCards.length){ loaderTxt.textContent='All campaigns loaded'; loader.style.opacity='.5'; campObserver.disconnect(); }
            else loaderTxt.textContent='Scroll to load more';
        },600);
    }
    var campObserver=new IntersectionObserver(function(entries){ entries.forEach(function(e){ if(e.isIntersecting) showNextBatch(); }); },{rootMargin:'200px'});
    function applyFilter(cat){
        activeFilter=cat;
        allCards.forEach(function(c){ c.classList.add('hidden'); });
        filteredCards=allCards.filter(function(c){ return cat==='all'||c.getAttribute('data-cat')===cat; });
        visibleCount=0; loading=false; campObserver.disconnect();
        campEmpty.style.display=filteredCards.length===0?'block':'none';
        if(filteredCards.length===0){ loader.style.display='none'; return; }
        var firstBatch=Math.min(6,filteredCards.length);
        for(var i=0;i<firstBatch;i++) filteredCards[i].classList.remove('hidden');
        visibleCount=firstBatch;
        if(filteredCards.length>6){ loader.style.display=''; loader.style.opacity='1'; loaderTxt.textContent='Scroll to load more'; spinner.style.display='none'; campObserver.observe(loader); }
        else loader.style.display='none';
    }
    document.querySelectorAll('.camp-filter-btn').forEach(function(btn){
        btn.addEventListener('click',function(){
            document.querySelectorAll('.camp-filter-btn').forEach(function(b){ b.classList.remove('active'); });
            this.classList.add('active');
            applyFilter(this.getAttribute('data-cat'));
            document.getElementById('campaignContainer').scrollIntoView({behavior:'smooth',block:'start'});
        });
    });
    applyFilter('all');


    /* ── ANIMATED COUNTERS ── */
    var counters=document.querySelectorAll('.counter[data-target]');
    var counterObs=new IntersectionObserver(function(entries){
        entries.forEach(function(e){
            if(!e.isIntersecting) return;
            var el=e.target, target=+el.getAttribute('data-target'), suffix=el.getAttribute('data-suffix')||'', fmt=el.getAttribute('data-format');
            var steps=80, step=0;
            var timer=setInterval(function(){
                step++;
                var progress=1-Math.pow(1-step/steps,3);
                var cur=Math.floor(progress*target);
                if(fmt==='crore') el.textContent='₹'+(cur/10000000).toFixed(1)+' Cr';
                else el.textContent=cur.toLocaleString('en-IN')+suffix;
                if(step>=steps){ clearInterval(timer); el.textContent=fmt==='crore'?'₹10 Cr+':target.toLocaleString('en-IN')+suffix; }
            },18);
            counterObs.unobserve(el);
        });
    },{threshold:0.5});
    counters.forEach(function(el){ counterObs.observe(el); });


    /* ── TESTIMONIAL AUTO SCROLL ── */
    setInterval(function(){
        document.querySelectorAll('.testi-slider').forEach(function(slider){
            if(slider.closest('.testi-tab-pane').classList.contains('active')){
                var first=slider.firstElementChild;
                if(first){ slider.appendChild(first.cloneNode(true)); slider.removeChild(first); }
            }
        });
    },3200);


    /* ── SCROLL TO TOP ── */
    var btn=document.getElementById('scrollTopBtn');
    window.addEventListener('scroll',function(){ btn.classList.toggle('visible',window.scrollY>600); },{passive:true});

});

/* ── TESTIMONIAL TAB SWITCH — global ── */
function switchTestiTab(tab,el){
    document.querySelectorAll('.testi-tab-pane').forEach(function(p){ p.classList.remove('active'); });
    document.getElementById(tab).classList.add('active');
    document.querySelectorAll('.testi-tab-btn').forEach(function(b){ b.classList.remove('active'); });
    el.classList.add('active');
}

/* ── NEWSLETTER ── */
function subscribeNewsletter(){
    var email=document.getElementById('nlEmail').value.trim();
    if(!email||!email.includes('@')){ alert('Please enter a valid email address.'); return; }
    // Replace with actual AJAX or form submission
    alert('Thank you for subscribing! You will receive our first newsletter soon.');
    document.getElementById('nlEmail').value='';
}
</script>

@endsection