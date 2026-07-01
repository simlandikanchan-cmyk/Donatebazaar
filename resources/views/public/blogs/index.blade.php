@extends('layouts.app')

@section('title', isset($category) ? $category->name : (isset($tag) ? '#'.$tag->name : 'Blogs'))

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
/* ═══════════════════════════════════════════════════════════════
   DESIGN SYSTEM — identical to home.blade.php + about.blade.php
   ═══════════════════════════════════════════════════════════════ */
:root {
    --bg:           #f4f5fb;
    --surface:      #ffffff;
    --surface2:     #f8f9fe;
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
    --radius:       14px;
    --radius-sm:    9px;
    --shadow:       0 1px 3px rgba(0,0,0,0.06), 0 4px 16px rgba(0,0,0,0.04);
    --shadow-lg:    0 8px 40px rgba(0,0,0,0.12);
    --transition:   0.2s ease;
}

[data-theme="dark"] {
    --bg:       #0b0c14;
    --surface:  #13141f;
    --surface2: #1a1b2e;
    --border:   rgba(255,255,255,0.06);
    --border2:  rgba(255,255,255,0.10);
    --text:     #f0f1ff;
    --text2:    #a5b4c8;
    --text3:    #5a6579;
    --accent-glow: rgba(99,102,241,0.25);
    --shadow:   0 1px 3px rgba(0,0,0,0.3), 0 4px 16px rgba(0,0,0,0.2);
    --shadow-lg:0 8px 40px rgba(0,0,0,0.5);
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: var(--font); color: var(--text); background: var(--bg); -webkit-font-smoothing: antialiased; }

.container { max-width: 1200px; margin: 0 auto; padding: 0 24px; }
.section-eyebrow {
    font-size: 11px; font-weight: 600; letter-spacing: .12em;
    text-transform: uppercase; color: var(--accent); margin-bottom: 10px;
    font-family: var(--font-mono);
}

.reveal { opacity: 0; transform: translateY(22px); transition: opacity .6s ease, transform .6s ease; }
.reveal.visible { opacity: 1; transform: translateY(0); }
.reveal-d1{transition-delay:.08s} .reveal-d2{transition-delay:.16s}
.reveal-d3{transition-delay:.24s} .reveal-d4{transition-delay:.32s}
.reveal-d5{transition-delay:.40s} .reveal-d6{transition-delay:.48s}

.blog-hero {
    position: relative;
    background: linear-gradient(160deg, #07080f 0%, #0d0e1a 45%, #13141f 100%);
    overflow: hidden;
    padding: 88px 0 100px;
}
.blog-hero::before {
    content: ''; position: absolute; top: -200px; left: -180px;
    width: 580px; height: 580px; border-radius: 50%;
    background: radial-gradient(circle, rgba(99,102,241,.12) 0%, transparent 65%);
    pointer-events: none;
}
.blog-hero::after {
    content: ''; position: absolute; bottom: -120px; right: -100px;
    width: 440px; height: 440px; border-radius: 50%;
    background: radial-gradient(circle, rgba(139,92,246,.08) 0%, transparent 65%);
    pointer-events: none;
}
.blog-hero-grid {
    position: absolute; inset: 0; pointer-events: none;
    background-image:
        linear-gradient(rgba(99,102,241,.04) 1px, transparent 1px),
        linear-gradient(90deg, rgba(99,102,241,.04) 1px, transparent 1px);
    background-size: 60px 60px;
}

.blog-hero-inner { position: relative; z-index: 1; }

.hero-eyebrow {
    display: inline-flex; align-items: center; gap: 8px;
    background: rgba(255,255,255,.10); border: 1px solid rgba(255,255,255,.22);
    backdrop-filter: blur(8px); border-radius: 100px;
    padding: 6px 18px; font-size: 11px; font-weight: 600;
    letter-spacing: .1em; text-transform: uppercase;
    color: rgba(255,255,255,.8); margin-bottom: 22px;
    font-family: var(--font-mono); width: fit-content;
}
.hero-eyebrow span {
    width: 6px; height: 6px; border-radius: 50%;
    background: var(--accent2); display: inline-block;
    animation: pulse-dot 2s ease infinite; flex-shrink: 0;
}
@keyframes pulse-dot { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.5;transform:scale(.7)} }

.blog-hero-title {
    font-family: var(--font-display);
    font-size: clamp(2.6rem, 4vw, 3.4rem); font-weight: 700;
    line-height: 1.1; color: #fff; margin-bottom: 18px;
}
.blog-hero-title em { font-style: normal; color: #a5b4fc; }

.blog-hero-sub {
    font-size: clamp(.95rem, 1.5vw, 1.1rem); font-weight: 300;
    color: rgba(255,255,255,.68); max-width: 500px;
    line-height: 1.75; margin-bottom: 36px; font-family: var(--font);
}

.blog-hero-stats {
    display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 0;
}
.blog-hero-stat {
    display: inline-flex; align-items: center; gap: 8px;
    background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.14);
    backdrop-filter: blur(8px); border-radius: var(--radius-sm);
    padding: 8px 16px; font-family: var(--font-mono); font-size: 11.5px;
}
.blog-hero-stat strong { color: #a5b4fc; font-weight: 700; }
.blog-hero-stat span   { color: rgba(255,255,255,.45); }
.blog-hero-stat svg { width: 13px; height: 13px; stroke: rgba(165,180,252,.6); fill: none; stroke-width: 2; flex-shrink: 0; }

.marquee-wrap { background: #0d0e1a; overflow: hidden; padding: 14px 0; border-bottom: 1px solid rgba(99,102,241,.1); }
.marquee-track { display: flex; white-space: nowrap; animation: marquee 28s linear infinite; }
.marquee-track:hover { animation-play-state: paused; }
.marquee-item {
    display: inline-flex; align-items: center; gap: 9px; padding: 0 30px;
    font-size: 11px; font-weight: 600; color: rgba(165,180,252,.55);
    letter-spacing: .12em; text-transform: uppercase; font-family: var(--font-mono);
}
.marquee-dot { width: 5px; height: 5px; border-radius: 50%; background: var(--accent2); flex-shrink: 0; }
@keyframes marquee { 0%{transform:translateX(0)} 100%{transform:translateX(-50%)} }

.blog-filter-bar {
    position: sticky; top: 0; z-index: 40;
    background: rgba(255,255,255,.96);
    backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px);
    border-bottom: 1px solid var(--border2);
    box-shadow: 0 2px 16px rgba(0,0,0,.05);
}
[data-theme="dark"] .blog-filter-bar { background: rgba(13,14,26,.96); }

.blog-filter-inner {
    display: flex; flex-wrap: wrap; gap: 10px;
    align-items: center; padding: 12px 0;
}

.bfb-search { position: relative; flex: 1; min-width: 180px; }
.bfb-search svg {
    position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
    width: 15px; height: 15px; pointer-events: none;
    stroke: var(--text3); fill: none; stroke-width: 2;
}
.bfb-search input {
    width: 100%; padding: 9px 14px 9px 36px; font-size: 13px; font-family: var(--font);
    border: 1px solid var(--border2); border-radius: var(--radius-sm);
    background: var(--surface2); color: var(--text); outline: none;
    transition: border-color .2s, box-shadow .2s;
}
.bfb-search input:focus { border-color: var(--accent); box-shadow: 0 0 0 3px var(--accent-glow); }
.bfb-search input::placeholder { color: var(--text3); }

.bfb-select {
    font-size: 13px; font-family: var(--font);
    border: 1px solid var(--border2); border-radius: var(--radius-sm);
    padding: 9px 14px; background: var(--surface2); color: var(--text);
    outline: none; cursor: pointer; transition: border-color .2s;
}
.bfb-select:focus { border-color: var(--accent); }

.bfb-submit {
    padding: 9px 24px; border-radius: var(--radius-sm);
    background: linear-gradient(135deg, var(--accent), var(--accent2));
    color: #fff; font-size: 13px; font-weight: 600; font-family: var(--font);
    border: none; cursor: pointer;
    box-shadow: 0 4px 14px rgba(99,102,241,.35);
    transition: opacity .2s, transform .2s;
}
.bfb-submit:hover { opacity: .9; transform: translateY(-1px); }

.bfb-clear {
    font-size: 12px; color: var(--text3); text-decoration: none;
    font-family: var(--font-mono); transition: color .2s; white-space: nowrap;
}
.bfb-clear:hover { color: var(--red); }

.blog-body { padding: 40px 0 88px; background: var(--bg); }
.blog-layout { display: flex; gap: 28px; align-items: flex-start; }
.blog-main   { flex: 1; min-width: 0; }
.blog-sidebar { width: 260px; flex-shrink: 0; position: sticky; top: 72px; }

.cat-pills { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 28px; }
.cat-pill {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 7px 20px; border-radius: 100px; font-size: 12.5px; font-weight: 500;
    border: 1px solid var(--border2); background: var(--surface);
    color: var(--text3); text-decoration: none; font-family: var(--font);
    transition: all .2s; white-space: nowrap; cursor: pointer;
}
.cat-pill i { font-size: 11px; }
.cat-pill:hover { border-color: rgba(99,102,241,.4); color: var(--accent); background: rgba(99,102,241,.05); }
.cat-pill.active {
    background: linear-gradient(135deg, var(--accent), var(--accent2));
    color: #fff; border-color: transparent;
    box-shadow: 0 4px 14px rgba(99,102,241,.35);
}

.featured-label {
    font-size: 11px; font-weight: 600; letter-spacing: .12em; text-transform: uppercase;
    color: var(--accent); margin-bottom: 14px; font-family: var(--font-mono);
    display: flex; align-items: center; gap: 8px;
}
.featured-label::after {
    content: ''; flex: 1; height: 1px;
    background: linear-gradient(90deg, rgba(99,102,241,.25), transparent);
}
.featured-strip {
    display: flex; gap: 14px; overflow-x: auto; margin-bottom: 32px;
    scroll-snap-type: x mandatory; -webkit-overflow-scrolling: touch;
    padding-bottom: 6px; scrollbar-width: thin;
    scrollbar-color: rgba(99,102,241,.2) transparent;
}
.featured-strip::-webkit-scrollbar { height: 3px; }
.featured-strip::-webkit-scrollbar-thumb { background: rgba(99,102,241,.2); border-radius: 2px; }

.featured-card {
    position: relative; flex-shrink: 0; width: 280px; height: 164px;
    border-radius: var(--radius); overflow: hidden; scroll-snap-align: start;
    text-decoration: none; display: block;
    border: 1px solid var(--border2);
    transition: transform .3s, box-shadow .3s;
}
.featured-card:hover { transform: translateY(-4px); box-shadow: 0 20px 48px rgba(99,102,241,.18); }
.featured-card img { width: 100%; height: 100%; object-fit: cover; display: block; transition: transform .5s; }
.featured-card:hover img { transform: scale(1.06); }
.featured-card-bg { width: 100%; height: 100%; background: linear-gradient(135deg, #0d0e1a, #1a1b2e); }
.featured-card-overlay {
    position: absolute; inset: 0;
    background: linear-gradient(to top, rgba(0,0,0,.9) 0%, rgba(0,0,0,.18) 55%, transparent 100%);
}
.featured-card-body { position: absolute; bottom: 0; left: 0; right: 0; padding: 14px 16px; }
.featured-badge {
    display: inline-block;
    background: linear-gradient(135deg, var(--accent), var(--accent2));
    color: #fff; font-size: 9px; font-weight: 700;
    letter-spacing: .1em; text-transform: uppercase;
    padding: 3px 10px; border-radius: 100px; margin-bottom: 7px; font-family: var(--font-mono);
}
.featured-card-title {
    color: #fff; font-size: 13px; font-weight: 600; line-height: 1.4;
    display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;
    overflow: hidden; font-family: var(--font);
}

.result-count {
    display: flex; align-items: center; gap: 6px;
    font-size: 11.5px; color: var(--text3); margin-bottom: 20px; font-family: var(--font-mono);
}
.result-count svg { width: 12px; height: 12px; stroke: var(--text3); fill: none; stroke-width: 2; }
.result-count .rq { color: var(--accent); font-weight: 600; }

.blog-grid { display: grid; grid-template-columns: repeat(2,1fr); gap: 20px; }
@media(max-width:680px) { .blog-grid { grid-template-columns: 1fr; } }

.blog-card {
    background: var(--surface); border-radius: var(--radius);
    border: 1px solid var(--border2); overflow: hidden;
    display: flex; flex-direction: column;
    box-shadow: var(--shadow); position: relative;
    transition: transform .3s, box-shadow .3s, border-color .3s;
}
.blog-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 24px 56px rgba(99,102,241,.12);
    border-color: rgba(99,102,241,.3);
}
.blog-card-bar {
    position: absolute; bottom: 0; left: 0; width: 0; height: 2px;
    background: linear-gradient(90deg, var(--accent), var(--accent2));
    transition: width .4s cubic-bezier(.4,0,.2,1); z-index: 2;
}
.blog-card:hover .blog-card-bar { width: 100%; }

.blog-card-img
{
    height: 192px;
    overflow: hidden;
    flex-shrink: 0;
    position: relative;

}

.blog-card-img a
{
    display: block;
}

.blog-card-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform .5s;

}


.blog-card:hover .blog-card-img img {

    transform: scale(1.06);

}

.blog-card-img-ph {

    width: 100%; height: 100%;
    background: linear-gradient(135deg, #eef2ff, #e0e7ff);
    display: flex;
    align-items: center;
    justify-content: center;
}

.blog-card-img-ph svg {

    width: 32px;
    height: 32px;
    stroke: #c7d2fe;
    fill: none;
    stroke-width: 1.5; }

.blog-card-cat-badge {
    position: absolute; top: 12px; left: 12px;
    background: rgba(255,255,255,.92); backdrop-filter: blur(8px);
    color: #1e1b4b; font-size: 10.5px; font-weight: 600;
    padding: 4px 12px; border-radius: 100px;
    border: 1px solid rgba(99,102,241,.2); font-family: var(--font-mono);
    text-decoration: none; transition: background .2s;
}
.blog-card-cat-badge:hover { background: rgba(99,102,241,.1); color: var(--accent); }
.blog-card-featured-badge {
    position: absolute; top: 12px; right: 12px;
    background: rgba(245,158,11,.12); color: #d97706;
    border: 1px solid rgba(245,158,11,.3);
    font-size: 10px; font-weight: 700; letter-spacing: .06em;
    padding: 4px 11px; border-radius: 100px; font-family: var(--font-mono);
}

.blog-card-body { padding: 18px 20px 20px; flex: 1; display: flex; flex-direction: column; }

.blog-card-read-time {
    display: flex; align-items: center; gap: 5px;
    font-size: 11px; color: var(--text3); font-family: var(--font-mono);
    margin-bottom: 10px;
}
.blog-card-read-time svg { width: 11px; height: 11px; stroke: var(--text3); fill: none; stroke-width: 2; }

.blog-card-title {
    font-size: 15px; font-weight: 600; color: var(--text);
    line-height: 1.45; margin-bottom: 10px; font-family: var(--font);
}
.blog-card-title a { text-decoration: none; color: inherit; transition: color .2s; }
.blog-card-title a:hover { color: var(--accent); }

.blog-card-excerpt {
    font-size: 13px; color: var(--text2); line-height: 1.7; font-weight: 300;
    display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;
    overflow: hidden; flex: 1; margin-bottom: 16px;
}

.blog-card-footer {
    display: flex; align-items: center; justify-content: space-between;
    padding-top: 12px; border-top: 1px solid var(--border); margin-top: auto;
}
.blog-card-author { display: flex; align-items: center; gap: 8px; min-width: 0; }
.blog-card-avatar { width: 28px; height: 28px; border-radius: var(--radius-sm); object-fit: cover; flex-shrink: 0; }
.blog-card-initials {
    width: 28px; height: 28px; border-radius: var(--radius-sm); flex-shrink: 0;
    background: linear-gradient(135deg, var(--accent), var(--accent2));
    display: flex; align-items: center; justify-content: center;
    font-size: 10px; font-weight: 700; color: #fff; font-family: var(--font-mono);
}
.blog-card-author-name {
    font-size: 12px; font-weight: 500; color: var(--text2); font-family: var(--font);
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.blog-card-stats { display: flex; align-items: center; gap: 10px; flex-shrink: 0; }
.blog-card-stat {
    display: flex; align-items: center; gap: 4px;
    font-size: 11px; color: var(--text3); font-family: var(--font-mono);
}
.blog-card-stat svg { width: 11px; height: 11px; stroke: currentColor; fill: none; stroke-width: 2; flex-shrink: 0; }

.blog-empty {
    text-align: center; padding: 72px 24px;
    background: var(--surface); border-radius: var(--radius);
    border: 1px solid var(--border2); grid-column: 1/-1;
    box-shadow: var(--shadow);
}
.blog-empty-icon {
    width: 64px; height: 64px; border-radius: var(--radius);
    background: var(--surface2); border: 1px solid var(--border2);
    display: flex; align-items: center; justify-content: center; margin: 0 auto 18px;
}
.blog-empty-icon svg { width: 28px; height: 28px; stroke: #c7d2fe; fill: none; stroke-width: 1.5; }
.blog-empty-title { font-family: var(--font-display); font-size: 18px; font-weight: 700; color: var(--text); margin-bottom: 8px; }
.blog-empty-desc { font-size: 13.5px; color: var(--text3); margin-bottom: 24px; line-height: 1.65; }
.blog-empty-btn {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 11px 26px; border-radius: var(--radius-sm);
    background: linear-gradient(135deg, var(--accent), var(--accent2));
    color: #fff; font-size: 13.5px; font-weight: 600; font-family: var(--font);
    text-decoration: none; box-shadow: 0 4px 14px rgba(99,102,241,.35);
    transition: opacity .2s, transform .2s;
}
.blog-empty-btn:hover { opacity: .9; transform: translateY(-2px); }
.blog-empty-btn svg { width: 14px; height: 14px; stroke: currentColor; fill: none; stroke-width: 2; }

.blog-pagination { margin-top: 36px; display: flex; justify-content: center; }

.sidebar-card {
    background: var(--surface); border-radius: var(--radius);
    border: 1px solid var(--border2); padding: 20px 22px;
    box-shadow: var(--shadow); margin-bottom: 16px;
    transition: border-color .2s;
}
.sidebar-card:hover { border-color: rgba(99,102,241,.25); }

.sidebar-label {
    font-size: 10.5px; font-weight: 600; letter-spacing: .12em; text-transform: uppercase;
    color: var(--accent); font-family: var(--font-mono); margin-bottom: 14px;
    display: flex; align-items: center; gap: 8px;
}
.sidebar-label::before {
    content: ''; width: 3px; height: 13px;
    background: linear-gradient(var(--accent), var(--accent2));
    border-radius: 2px; flex-shrink: 0;
}

.sidebar-tags { display: flex; flex-wrap: wrap; gap: 7px; }
.sidebar-tag {
    padding: 5px 13px; border-radius: 100px;
    background: var(--surface2); border: 1px solid var(--border2);
    font-size: 11.5px; color: var(--text2); text-decoration: none; font-family: var(--font-mono);
    transition: all .2s;
}
.sidebar-tag:hover { background: rgba(99,102,241,.08); border-color: rgba(99,102,241,.3); color: var(--accent); }

.sidebar-cat-list { list-style: none; }
.sidebar-cat-list li { border-bottom: 1px solid var(--border); }
.sidebar-cat-list li:last-child { border-bottom: none; }
.sidebar-cat-link {
    display: flex; align-items: center; gap: 8px; padding: 9px 0;
    text-decoration: none; font-size: 13px; color: var(--text2); font-family: var(--font);
    transition: color .2s;
}
.sidebar-cat-link:hover { color: var(--accent); }
.sidebar-cat-link i { font-size: 12px; width: 16px; text-align: center; color: var(--accent); flex-shrink: 0; }
.sidebar-cat-link .cat-arrow {
    margin-left: auto; width: 13px; height: 13px;
    stroke: var(--border2); fill: none; stroke-width: 2;
    transition: stroke .2s, transform .2s; flex-shrink: 0;
}
.sidebar-cat-link:hover .cat-arrow { stroke: var(--accent); transform: translateX(3px); }

.sidebar-cta {
    position: relative; border-radius: var(--radius); overflow: hidden;
    background: linear-gradient(160deg, #07080f 0%, #0d0e1a 55%, #13141f 100%);
    border: 1px solid rgba(99,102,241,.2); padding: 24px 20px; text-align: center;
}
.sidebar-cta::before {
    content: ''; position: absolute; top: -50px; right: -50px;
    width: 140px; height: 140px; border-radius: 50%;
    background: rgba(99,102,241,.12); pointer-events: none;
}
.sidebar-cta-icon {
    width: 46px; height: 46px; border-radius: var(--radius-sm);
    background: rgba(99,102,241,.15); border: 1px solid rgba(99,102,241,.25);
    display: flex; align-items: center; justify-content: center; margin: 0 auto 14px;
}
.sidebar-cta-icon svg { width: 20px; height: 20px; stroke: #a5b4fc; fill: none; stroke-width: 2; }
.sidebar-cta p {
    font-size: 12.5px; color: rgba(255,255,255,.5); margin-bottom: 16px;
    line-height: 1.65; font-family: var(--font); position: relative; z-index: 1;
}
.sidebar-cta-btn {
    display: block; padding: 11px 18px; border-radius: var(--radius-sm);
    background: linear-gradient(135deg, var(--accent), var(--accent2));
    color: #fff; font-size: 13px; font-weight: 600; font-family: var(--font);
    text-decoration: none; box-shadow: 0 4px 14px rgba(99,102,241,.4);
    transition: opacity .2s, transform .2s; position: relative; z-index: 1;
}
.sidebar-cta-btn:hover { opacity: .9; transform: translateY(-2px); }

@media(max-width:1024px) { .blog-sidebar { display: none; } }
@media(max-width:600px) {
    .blog-hero { padding: 60px 0 72px; }
    .blog-hero-title { font-size: 2.2rem; }
    .blog-hero-stats { gap: 8px; }
}
</style>

{{-- ═══ HERO ═══ --}}
<section class="blog-hero">
    <div class="blog-hero-grid"></div>
    <div class="container blog-hero-inner">

        <div class="hero-eyebrow">
            <span></span>
            @if(isset($category)) {{ $category->name }}
            @elseif(isset($tag)) Tagged Posts
            @else Community Stories
            @endif
        </div>

        <h1 class="blog-hero-title">
            @if(isset($category)) {{ $category->name }}
            @elseif(isset($tag)) #<em>{{ $tag->name }}</em>
            @else Stories &amp; <em>Perspectives</em>
            @endif
        </h1>

        <p class="blog-hero-sub">
            @if(isset($category))
                Explore all posts in <strong style="color:rgba(165,180,252,.9);font-weight:500;">{{ $category->name }}</strong> — real voices on real causes.
            @elseif(isset($tag))
                Posts tagged with <strong style="color:rgba(165,180,252,.9);font-weight:500;">#{{ $tag->name }}</strong>.
            @else
                Insights, ideas, and stories from our writers — real voices on real causes across India.
            @endif
        </p>

        <div class="blog-hero-stats">
            <div class="blog-hero-stat">
                <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                <strong>{{ number_format($blogs->total() ?? 0) }}</strong>
                <span>Posts Published</span>
            </div>
            @if(isset($categories) && $categories->isNotEmpty())
            <div class="blog-hero-stat">
                <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 01-2 2H4a2 2 0 01-2-2V5a2 2 0 012-2h5l2 3h9a2 2 0 012 2z"/></svg>
                <strong>{{ number_format($categories->count()) }}</strong>
                <span>Categories</span>
            </div>
            @endif
            @if(isset($tags) && $tags->isNotEmpty())
            <div class="blog-hero-stat">
                <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                <strong>{{ number_format($tags->count()) }}</strong>
                <span>Tags</span>
            </div>
            @endif
        </div>

    </div>
</section>

{{-- ═══ MARQUEE ═══ --}}
<div class="marquee-wrap">
    <div class="marquee-track">
        @php $items = ['Community Stories','Expert Insights','Impact Reports','Verified Writers','Fresh Perspectives','Weekly Updates','Real Stories','Donor Voices']; @endphp
        @for($r=0;$r<2;$r++)
            @foreach($items as $item)
                <span class="marquee-item"><span class="marquee-dot"></span>{{ $item }}</span>
            @endforeach
        @endfor
    </div>
</div>

{{-- ═══ FILTER BAR ═══ --}}
{{--
    NOTE: This is a GET form, so it intentionally has no @csrf — GET requests
    are not state-changing and Laravel's VerifyCsrfToken middleware does not
    check them. Do not add @csrf here; doing so would leak the token into the
    URL/query string and into browser history, referrer headers, and analytics.
    Validate `category`, `sort`, and `q` server-side in the controller
    (e.g. with a FormRequest using `in:` rules for sort/category and a max
    length + strip on `q`) — never trust these for raw SQL/LIKE building.
--}}
<div class="blog-filter-bar">
    <div class="container">
        <form method="GET" action="{{ route('blogs.index') }}" class="blog-filter-inner" role="search" aria-label="Search blog posts">
            <div class="bfb-search">
                <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                </svg>
                <input
                    type="text"
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="Search stories…"
                    maxlength="120"
                    autocomplete="off"
                    inputmode="search">
            </div>
            @if(isset($categories) && $categories->isNotEmpty())
            <select name="category" class="bfb-select">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->slug }}" @selected(request('category') === $cat->slug || (isset($category) && $category->slug === $cat->slug))>{{ $cat->name }}</option>
                @endforeach
            </select>
            @endif
            <select name="sort" class="bfb-select">
                <option value="recent"   @selected(request('sort','recent') === 'recent')>Most Recent</option>
                <option value="popular"  @selected(request('sort') === 'popular')>Most Popular</option>
                <option value="trending" @selected(request('sort') === 'trending')>Trending</option>
            </select>
            <button type="submit" class="bfb-submit">Search</button>
            @if(request('q') || (request('sort') && request('sort') !== 'recent'))
                <a href="{{ route('blogs.index') }}" class="bfb-clear">✕ Clear</a>
            @endif
        </form>
    </div>
</div>

{{-- ═══ BODY ═══ --}}
<div class="blog-body">
    <div class="container">
        <div class="blog-layout">

            {{-- ── MAIN ── --}}
            <div class="blog-main">

                {{-- Category pills --}}
                @if(isset($categories) && $categories->isNotEmpty())
                <div class="cat-pills">
                    <a href="{{ route('blogs.index') }}"
                       class="cat-pill {{ !request('category') && !isset($category) ? 'active' : '' }}">
                        All Posts
                    </a>
                    @foreach($categories as $cat)
                    <a href="{{ route('blogs.category', $cat->slug) }}"
                       class="cat-pill {{ isset($category) && $category->slug === $cat->slug ? 'active' : '' }}">
                        {{-- Icon class is restricted to a safe fa-* whitelist pattern to prevent
                             attribute/markup injection if the icon field is ever editable by a
                             non-trusted admin or imported from an external feed. --}}
                        @if($cat->icon && preg_match('/^fa-[a-z0-9-]+$/', $cat->icon))
                            <i class="fa-solid {{ $cat->icon }}" aria-hidden="true"></i>
                        @endif
                        {{ $cat->name }}
                    </a>
                    @endforeach
                </div>
                @endif

                {{-- Featured strip --}}
                @if(!isset($tag) && !request('q') && isset($featured) && $featured->isNotEmpty())
                <div class="reveal">
                    <div class="featured-label">★ Featured Posts</div>
                    <div class="featured-strip">
                        @foreach($featured as $feat)
                        <a href="{{ route('blogs.show', $feat->slug) }}" class="featured-card">
                            @if($feat->cover_image)
                                <img
                                    src="{{ $feat->cover_image_url ?? Storage::url($feat->cover_image) }}"
                                    alt="{{ $feat->title }}"
                                    loading="lazy"
                                    referrerpolicy="no-referrer">
                            @else
                                <div class="featured-card-bg"></div>
                            @endif
                            <div class="featured-card-overlay"></div>
                            <div class="featured-card-body">
                                <div class="featured-badge">Featured</div>
                                <div class="featured-card-title">{{ $feat->title }}</div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Result count --}}
                <div class="result-count reveal">
                    <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                    {{ number_format($blogs->total()) }} {{ Str::plural('result', $blogs->total()) }}
                    @if(request('q'))<span class="rq">"{{ Str::limit(request('q'), 60) }}"</span>@endif
                </div>

                {{-- Grid --}}
                @if($blogs->isEmpty())
                <div class="blog-grid">
                    <div class="blog-empty">
                        <div class="blog-empty-icon">
                            <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                            </svg>
                        </div>
                        <div class="blog-empty-title">No posts found</div>
                        <p class="blog-empty-desc">
                            @if(request('q')) No results for "{{ Str::limit(request('q'), 60) }}". Try a different keyword.
                            @else Nothing published yet — check back soon!
                            @endif
                        </p>
                        <a href="{{ route('blogs.index') }}" class="blog-empty-btn">
                            Browse all
                            <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>

                @else
                {{--
                    SCALABILITY: this loop touches $blog->author and $blog->category for
                    every row. Make sure the controller eager-loads these
                    (Blog::with(['author:id,name,avatar', 'category:id,name,slug,icon'])->...)
                    to avoid N+1 queries — the view cannot fix that on its own.
                    Also prefer paginate()/cursorPaginate() with a sane per-page cap
                    (e.g. 12-24) rather than loading unbounded result sets.
                --}}
                <div class="blog-grid">
                    @foreach($blogs as $i => $blog)
                    @php
                        $author = $blog->author; // expected eager-loaded relation
                        $blogCategory = $blog->category;
                    @endphp
                    <article class="blog-card reveal reveal-d{{ min(($i % 6) + 1, 6) }}">
                        <div class="blog-card-bar"></div>

                        {{-- Image --}}
                        <div class="blog-card-img">
                            <a href="{{ route('blogs.show', $blog->slug) }}">
                                @if($blog->cover_image)
                                    <img
                                        src="{{ $blog->cover_image_url ?? Storage::url($blog->cover_image) }}"
                                        alt="{{ $blog->title }}"
                                        loading="lazy"
                                        referrerpolicy="no-referrer"
                                        width="280" height="192">
                                @else
                                    <div class="blog-card-img-ph">
                                        <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909"/>
                                        </svg>
                                    </div>
                                @endif
                            </a>
                            @if($blogCategory)
                            <a href="{{ route('blogs.category', $blogCategory->slug) }}" class="blog-card-cat-badge">
                                {{ $blogCategory->name }}
                            </a>
                            @endif
                            @if($blog->is_featured)
                            <span class="blog-card-featured-badge">★ Featured</span>
                            @endif
                        </div>

                        {{-- Body --}}
                        <div class="blog-card-body">
                            <div class="blog-card-read-time">
                                <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                {{ $blog->read_time_minutes ?? 1 }} min read · {{ optional($blog->published_at)->diffForHumans() ?? 'Recently' }}
                            </div>

                            <h3 class="blog-card-title">
                                <a href="{{ route('blogs.show', $blog->slug) }}">{{ $blog->title }}</a>
                            </h3>
                            <p class="blog-card-excerpt">{{ Str::limit(strip_tags($blog->excerpt ?? $blog->content ?? ''), 120) }}</p>

                            <div class="blog-card-footer">
                                <div class="blog-card-author">
                                    @if($author && $author->avatar)
                                        <img
                                            src="{{ Storage::url($author->avatar) }}"
                                            class="blog-card-avatar"
                                            alt="{{ $author->name }}"
                                            loading="lazy"
                                            referrerpolicy="no-referrer">
                                    @else
                                        <div class="blog-card-initials">{{ strtoupper(substr($author->name ?? 'A', 0, 1)) }}</div>
                                    @endif
                                    <span class="blog-card-author-name">{{ $author->name ?? 'Anonymous' }}</span>
                                </div>
                                <div class="blog-card-stats">
                                    <span class="blog-card-stat">
                                        <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                        {{ number_format($blog->views_count ?? 0) }}
                                    </span>
                                    <span class="blog-card-stat">
                                        <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
                                        {{ number_format($blog->likes_count ?? 0) }}
                                    </span>
                                    <span class="blog-card-stat">
                                        <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
                                        {{ number_format($blog->comments_count ?? 0) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </article>
                    @endforeach
                </div>
                <div class="blog-pagination">{{ $blogs->appends(request()->query())->links() }}</div>
                @endif

            </div>{{-- /main --}}

            {{-- ── SIDEBAR ── --}}
            <aside class="blog-sidebar">

                @if(isset($tags) && $tags->isNotEmpty())
                <div class="sidebar-card">
                    <div class="sidebar-label">Popular Tags</div>
                    <div class="sidebar-tags">
                        @foreach($tags as $t)
                        <a href="{{ route('blogs.tag', $t->slug) }}" class="sidebar-tag">#{{ $t->name }}</a>
                        @endforeach
                    </div>
                </div>
                @endif

                @if(isset($categories) && $categories->isNotEmpty())
                <div class="sidebar-card">
                    <div class="sidebar-label">Categories</div>
                    <ul class="sidebar-cat-list">
                        @foreach($categories as $cat)
                        <li>
                            <a href="{{ route('blogs.category', $cat->slug) }}" class="sidebar-cat-link">
                                @if($cat->icon && preg_match('/^fa-[a-z0-9-]+$/', $cat->icon))
                                    <i class="fa-solid {{ $cat->icon }}" aria-hidden="true"></i>
                                @endif
                                <span>{{ $cat->name }}</span>
                                <svg class="cat-arrow" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @auth
                <div class="sidebar-cta">
                    <div class="sidebar-cta-icon">
                        <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                        </svg>
                    </div>
                    <p>Have something to share with the community? Write your story today.</p>
                    <a href="{{ route('user.blogs.create') }}" class="sidebar-cta-btn">Write a Story</a>
                </div>
                @endauth

            </aside>

        </div>
    </div>
</div>

{{-- ═══ SCRIPTS ═══ --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    var reveals = document.querySelectorAll('.reveal');
    if (!('IntersectionObserver' in window)) {
        reveals.forEach(function (el) { el.classList.add('visible'); });
        return;
    }
    var io = new IntersectionObserver(function(entries){
        entries.forEach(function(e){
            if(e.isIntersecting){ e.target.classList.add('visible'); io.unobserve(e.target); }
        });
    }, { threshold: 0.1 });
    reveals.forEach(function(el){ io.observe(el); });
});
</script>

@endsection