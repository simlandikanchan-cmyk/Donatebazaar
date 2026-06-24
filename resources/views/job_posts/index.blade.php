<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Careers – DonateBazaar</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,700&family=Outfit:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --ink:      #0d0c0b;
            --paper:    #f7f4f0;
            --warm:     #ede7de;
            --accent:   #c84b2f;
            --accent2:  #e8a87c;
            --muted:    #7a726a;
            --border:   #ddd7ce;
            --white:    #ffffff;
            --surface:  #faf8f5;
            --green:    #2d6a4f;
            --green-lt: #e8f5ee;
        }

        html { scroll-behavior: smooth; }

        body {
            background: var(--paper);
            color: var(--ink);
            font-family: 'Outfit', sans-serif;
            font-weight: 300;
            min-height: 100vh;
            overflow-x: hidden;
        }

        a { text-decoration: none; color: inherit; }

        /* ─── NAV ─────────────────────────────────── */
        nav {
            position: sticky; top: 0; z-index: 100;
            background: rgba(247,244,240,0.92);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            padding: 0 clamp(1.5rem, 5vw, 5rem);
            display: flex; align-items: center; justify-content: space-between;
            height: 68px;
        }
        .nav-brand { font-family: 'Cormorant Garamond', serif; font-weight: 700; font-size: 1.4rem; color: var(--ink); letter-spacing: -0.01em; }
        .nav-brand span { color: var(--accent); }
        .nav-right { display: flex; align-items: center; gap: 2rem; }
        .nav-link { font-size: 0.82rem; font-weight: 500; color: var(--muted); letter-spacing: 0.06em; text-transform: uppercase; transition: color .2s; }
        .nav-link:hover { color: var(--ink); }
        .nav-cta { font-size: 0.82rem; font-weight: 500; padding: 0.5rem 1.2rem; background: var(--ink); color: var(--white); border-radius: 2px; letter-spacing: 0.04em; transition: background .2s; }
        .nav-cta:hover { background: var(--accent); }

        /* ─── HERO ────────────────────────────────── */
        .hero { min-height: 92vh; display: grid; grid-template-columns: 1fr 1fr; position: relative; overflow: hidden; }
        .hero-left { padding: clamp(4rem, 10vw, 8rem) clamp(1.5rem, 5vw, 5rem); display: flex; flex-direction: column; justify-content: center; border-right: 1px solid var(--border); }
        .hero-eyebrow { font-size: 0.72rem; font-weight: 600; letter-spacing: 0.18em; text-transform: uppercase; color: var(--accent); margin-bottom: 2rem; display: flex; align-items: center; gap: 0.75rem; }
        .hero-eyebrow::before { content: ''; display: inline-block; width: 32px; height: 1px; background: var(--accent); }
        .hero h1 { font-family: 'Cormorant Garamond', serif; font-size: clamp(3.5rem, 7vw, 6.5rem); font-weight: 700; line-height: 0.95; letter-spacing: -0.03em; color: var(--ink); margin-bottom: 2.5rem; }
        .hero h1 em { font-style: italic; color: var(--accent); }
        .hero-desc { font-size: 1rem; line-height: 1.8; color: var(--muted); max-width: 400px; margin-bottom: 2.5rem; }
        .hero-actions { display: flex; gap: 1rem; flex-wrap: wrap; }
        .btn-primary { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.85rem 2rem; background: var(--accent); color: var(--white); font-family: 'Outfit', sans-serif; font-size: 0.85rem; font-weight: 500; letter-spacing: 0.04em; border-radius: 2px; transition: background .2s, transform .15s; }
        .btn-primary:hover { background: #a83a20; transform: translateY(-2px); }
        .btn-outline { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.85rem 2rem; background: transparent; color: var(--ink); font-family: 'Outfit', sans-serif; font-size: 0.85rem; font-weight: 500; letter-spacing: 0.04em; border: 1px solid var(--border); border-radius: 2px; transition: border-color .2s, color .2s; }
        .btn-outline:hover { border-color: var(--ink); }
        .hero-right { display: flex; flex-direction: column; justify-content: flex-end; padding: clamp(3rem, 6vw, 5rem) clamp(1.5rem, 5vw, 5rem); background: var(--surface); position: relative; }
        .hero-right::before { content: '"'; position: absolute; top: 3rem; left: clamp(1.5rem, 5vw, 5rem); font-family: 'Cormorant Garamond', serif; font-size: 8rem; font-weight: 700; color: var(--warm); line-height: 1; }
        .hero-quote { font-family: 'Cormorant Garamond', serif; font-size: clamp(1.4rem, 2.5vw, 2rem); font-weight: 400; font-style: italic; line-height: 1.5; color: var(--ink); margin-bottom: 1.5rem; position: relative; z-index: 1; }
        .hero-quote-attr { font-size: 0.82rem; font-weight: 500; color: var(--muted); letter-spacing: 0.06em; }
        .hero-stats-row { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1px; border: 1px solid var(--border); border-radius: 4px; overflow: hidden; margin-bottom: 3rem; }
        .hero-stat { background: var(--white); padding: 1.5rem 1.25rem; text-align: center; }
        .hero-stat-val { font-family: 'Cormorant Garamond', serif; font-size: 2.5rem; font-weight: 700; color: var(--ink); line-height: 1; }
        .hero-stat-val span { color: var(--accent); }
        .hero-stat-label { font-size: 0.72rem; letter-spacing: 0.1em; text-transform: uppercase; color: var(--muted); margin-top: 0.4rem; }
        .scroll-indicator { display: flex; align-items: center; gap: 0.75rem; font-size: 0.78rem; color: var(--muted); letter-spacing: 0.08em; text-transform: uppercase; }
        .scroll-line { width: 40px; height: 1px; background: var(--border); position: relative; overflow: hidden; }
        .scroll-line::after { content: ''; position: absolute; top: 0; left: -100%; width: 100%; height: 100%; background: var(--accent); animation: slide 2s ease infinite; }
        @keyframes slide { to { left: 100%; } }

        /* ─── WHY US ──────────────────────────────── */
        .why-section { padding: clamp(4rem, 8vw, 7rem) clamp(1.5rem, 5vw, 5rem); border-bottom: 1px solid var(--border); }
        .section-header { display: flex; align-items: flex-end; justify-content: space-between; margin-bottom: 3.5rem; flex-wrap: wrap; gap: 1.5rem; }
        .section-eyebrow { font-size: 0.72rem; font-weight: 600; letter-spacing: 0.18em; text-transform: uppercase; color: var(--accent); margin-bottom: 0.75rem; }
        .section-title { font-family: 'Cormorant Garamond', serif; font-size: clamp(2rem, 4vw, 3.5rem); font-weight: 700; line-height: 1.05; letter-spacing: -0.02em; }
        .perks-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 1px; border: 1px solid var(--border); border-radius: 4px; overflow: hidden; }
        .perk-card { background: var(--white); padding: 2rem; transition: background .2s; }
        .perk-card:hover { background: var(--surface); }
        .perk-icon { width: 44px; height: 44px; background: #fef3ee; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 1.25rem; flex-shrink: 0; }
        .perk-icon svg { width: 20px; height: 20px; color: var(--accent); }
        .perk-title { font-size: 1rem; font-weight: 600; color: var(--ink); margin-bottom: 0.5rem; }
        .perk-desc { font-size: 0.875rem; line-height: 1.7; color: var(--muted); }

        /* ─── PROCESS ─────────────────────────────── */
        .process-section { padding: clamp(4rem, 8vw, 7rem) clamp(1.5rem, 5vw, 5rem); background: var(--ink); color: var(--white); }
        .process-section .section-title { color: var(--white); }
        .process-section .section-eyebrow { color: var(--accent2); }
        .process-steps { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 0; margin-top: 3.5rem; border: 1px solid rgba(255,255,255,.08); border-radius: 4px; overflow: hidden; }
        .process-step { padding: 2.5rem 2rem; border-right: 1px solid rgba(255,255,255,.08); position: relative; }
        .process-step:last-child { border-right: none; }
        .step-num { font-family: 'Cormorant Garamond', serif; font-size: 3.5rem; font-weight: 700; color: rgba(255,255,255,.08); line-height: 1; margin-bottom: 1.5rem; display: block; }
        .step-title { font-size: 1rem; font-weight: 600; color: var(--white); margin-bottom: 0.5rem; }
        .step-desc { font-size: 0.85rem; line-height: 1.7; color: rgba(255,255,255,.5); }
        .step-tag { display: inline-block; margin-top: 1.25rem; font-size: 0.7rem; font-weight: 600; letter-spacing: 0.1em; text-transform: uppercase; color: var(--accent2); background: rgba(232,168,124,.1); padding: 0.3rem 0.75rem; border-radius: 100px; }

        /* ─── CULTURE ─────────────────────────────── */
        .culture-section { padding: clamp(4rem, 8vw, 7rem) clamp(1.5rem, 5vw, 5rem); border-bottom: 1px solid var(--border); }
        .testimonials-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem; margin-top: 3.5rem; }
        .testimonial-card { background: var(--white); border: 1px solid var(--border); border-radius: 4px; padding: 2rem; position: relative; }
        .testimonial-card::before { content: '"'; position: absolute; top: 1.25rem; right: 1.5rem; font-family: 'Cormorant Garamond', serif; font-size: 4rem; font-weight: 700; color: var(--warm); line-height: 1; }
        .testimonial-text { font-family: 'Cormorant Garamond', serif; font-size: 1.15rem; font-style: italic; line-height: 1.6; color: var(--ink); margin-bottom: 1.5rem; }
        .testimonial-author { display: flex; align-items: center; gap: 0.75rem; }
        .author-avatar { width: 40px; height: 40px; border-radius: 50%; background: var(--warm); display: flex; align-items: center; justify-content: center; font-size: 0.85rem; font-weight: 600; color: var(--muted); flex-shrink: 0; }
        .author-name { font-size: 0.875rem; font-weight: 600; color: var(--ink); }
        .author-role { font-size: 0.78rem; color: var(--muted); margin-top: 2px; }

        /* ─── FILTERS ─────────────────────────────── */
        .jobs-header-section { padding: clamp(3rem, 6vw, 5rem) clamp(1.5rem, 5vw, 5rem) 0; }
        .filters-bar { padding: 1.25rem clamp(1.5rem, 5vw, 5rem); display: flex; flex-wrap: wrap; gap: 1rem; align-items: center; border-bottom: 1px solid var(--border); background: var(--white); position: sticky; top: 68px; z-index: 50; }
        .search-wrap { position: relative; flex: 1; min-width: 180px; max-width: 340px; }
        .search-wrap svg { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--muted); pointer-events: none; width: 15px; height: 15px; }
        .search-input { width: 100%; padding: 0.65rem 0.85rem 0.65rem 2.6rem; border: 1px solid var(--border); background: var(--surface); border-radius: 2px; font-family: 'Outfit', sans-serif; font-size: 0.875rem; color: var(--ink); outline: none; transition: border-color .2s; }
        .search-input:focus { border-color: var(--accent); }
        .search-input::placeholder { color: var(--muted); }
        .type-filter { display: flex; flex-wrap: wrap; gap: 0.4rem; }
        .type-btn { padding: 0.45rem 1rem; border: 1px solid var(--border); background: transparent; border-radius: 100px; font-family: 'Outfit', sans-serif; font-size: 0.78rem; font-weight: 500; color: var(--muted); cursor: pointer; transition: all .15s; letter-spacing: 0.04em; }
        .type-btn:hover { border-color: var(--ink); color: var(--ink); }
        .type-btn.active { background: var(--ink); border-color: var(--ink); color: var(--white); }
        .jobs-count { margin-left: auto; font-size: 0.8rem; color: var(--muted); white-space: nowrap; }

        /* ─── GRID ────────────────────────────────── */
        .jobs-section { padding: 2.5rem clamp(1.5rem, 5vw, 5rem) clamp(3rem, 6vw, 5rem); }
        .jobs-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(330px, 1fr)); gap: 1.5rem; }

        /* ─── CARD ────────────────────────────────── */
        .job-card { background: var(--white); border: 1px solid var(--border); border-radius: 4px; padding: 2rem; display: flex; flex-direction: column; gap: 1rem; transition: box-shadow .25s, transform .25s, border-color .25s; position: relative; overflow: hidden; opacity: 0; animation: fadeUp .5s ease both; }
        .job-card::before { content: ''; position: absolute; bottom: 0; left: 0; width: 0; height: 2px; background: var(--accent); transition: width .35s ease; }
        .job-card:hover { box-shadow: 0 16px 48px rgba(13,12,11,.08); transform: translateY(-4px); }
        .job-card:hover::before { width: 100%; }
        .card-top { display: flex; justify-content: space-between; align-items: flex-start; gap: 0.75rem; }
        .job-title { font-family: 'Cormorant Garamond', serif; font-size: 1.25rem; font-weight: 700; color: var(--ink); line-height: 1.25; }
        .badge { display: inline-flex; align-items: center; padding: 0.3rem 0.75rem; border-radius: 100px; font-size: 0.68rem; font-weight: 600; letter-spacing: 0.07em; text-transform: uppercase; white-space: nowrap; flex-shrink: 0; }
        .badge-type { background: var(--warm); color: var(--muted); }
        .badge-remote { background: #fef3ee; color: var(--accent); }
        .card-meta { display: flex; flex-wrap: wrap; gap: 0.5rem 1.25rem; font-size: 0.82rem; color: var(--muted); }
        .meta-item { display: flex; align-items: center; gap: 0.35rem; }
        .meta-item svg { opacity: 0.55; flex-shrink: 0; }
        .card-excerpt { font-size: 0.875rem; line-height: 1.7; color: var(--muted); display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; flex: 1; }
        .card-footer { display: flex; justify-content: space-between; align-items: center; padding-top: 1rem; border-top: 1px solid var(--warm); margin-top: auto; gap: 0.75rem; }
        .deadline { font-size: 0.75rem; color: var(--muted); display: flex; align-items: center; gap: 0.35rem; }
        .deadline.urgent { color: var(--accent); font-weight: 500; }
        .apply-btn { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.6rem 1.35rem; background: var(--accent); color: var(--white); border-radius: 2px; font-family: 'Outfit', sans-serif; font-size: 0.8rem; font-weight: 500; letter-spacing: 0.04em; white-space: nowrap; transition: background .2s, transform .15s; }
        .apply-btn:hover { background: #a83a20; transform: translateX(2px); }
        .apply-btn svg { transition: transform .2s; }
        .apply-btn:hover svg { transform: translateX(3px); }

        /* ─── EMPTY STATE ─────────────────────────── */
        .empty { grid-column: 1 / -1; text-align: center; padding: 6rem 1rem; }
        .empty-icon { font-size: 3rem; margin-bottom: 1rem; opacity: 0.25; }
        .empty h3 { font-family: 'Cormorant Garamond', serif; font-size: 1.75rem; margin-bottom: 0.5rem; }
        .empty p { color: var(--muted); font-size: 0.9rem; }

        /* ─── CTA BANNER ──────────────────────────── */
        .cta-section { margin: 0 clamp(1.5rem, 5vw, 5rem) clamp(3rem, 6vw, 5rem); background: var(--accent); border-radius: 4px; padding: clamp(3rem, 6vw, 4rem) clamp(2rem, 5vw, 4rem); display: flex; align-items: center; justify-content: space-between; gap: 2rem; flex-wrap: wrap; }
        .cta-left h2 { font-family: 'Cormorant Garamond', serif; font-size: clamp(1.75rem, 3.5vw, 2.75rem); font-weight: 700; color: var(--white); line-height: 1.1; margin-bottom: 0.5rem; }
        .cta-left p { font-size: 0.95rem; color: rgba(255,255,255,.75); max-width: 420px; }
        .cta-btn { display: inline-flex; align-items: center; gap: 0.5rem; padding: 1rem 2.25rem; background: var(--white); color: var(--accent); font-family: 'Outfit', sans-serif; font-size: 0.9rem; font-weight: 600; border-radius: 2px; white-space: nowrap; transition: background .2s; flex-shrink: 0; }
        .cta-btn:hover { background: var(--paper); }

        /* ─── PAGINATION ──────────────────────────── */
        .pagination-wrap { padding: 2rem clamp(1.5rem, 5vw, 5rem); display: flex; justify-content: center; gap: 0.4rem; border-top: 1px solid var(--border); flex-wrap: wrap; }
        .pagination-wrap nav { display: flex; justify-content: center; }
        .pagination-wrap a, .pagination-wrap span, .pagination-wrap button { display: inline-flex; align-items: center; justify-content: center; min-width: 38px; height: 38px; padding: 0 0.6rem; border: 1px solid var(--border); border-radius: 2px; font-size: 0.85rem; color: var(--muted); background: var(--white); font-family: 'Outfit', sans-serif; cursor: pointer; transition: all .15s; text-decoration: none; }
        .pagination-wrap a:hover, .pagination-wrap button:hover { border-color: var(--ink); color: var(--ink); }
        .pagination-wrap .active span, .pagination-wrap span[aria-current="page"] span, .pagination-wrap a.active { background: var(--ink); border-color: var(--ink); color: var(--white); }
        .pagination-wrap span[aria-disabled="true"], .pagination-wrap .disabled { opacity: 0.4; cursor: not-allowed; }

        /* ─── FOOTER ──────────────────────────────── */
        footer { background: var(--ink); padding: 3rem clamp(1.5rem, 5vw, 5rem); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1.5rem; }
        .footer-brand { font-family: 'Cormorant Garamond', serif; font-weight: 700; font-size: 1.25rem; color: var(--white); }
        .footer-brand span { color: var(--accent2); }
        .footer-links { display: flex; gap: 2rem; flex-wrap: wrap; }
        .footer-links a { font-size: 0.8rem; color: rgba(255,255,255,.45); letter-spacing: 0.04em; transition: color .2s; }
        .footer-links a:hover { color: var(--white); }
        .footer-copy { font-size: 0.78rem; color: rgba(255,255,255,.3); }

        /* ─── ANIMATIONS ──────────────────────────── */
        @keyframes fadeUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

        /* ─── RESPONSIVE ──────────────────────────── */
        @media (max-width: 860px) {
            .hero { grid-template-columns: 1fr; min-height: auto; }
            .hero-left { border-right: none; border-bottom: 1px solid var(--border); }
            .hero-right { padding-top: 3rem; }
            .process-steps { grid-template-columns: 1fr 1fr; }
            .process-step { border-right: none; border-bottom: 1px solid rgba(255,255,255,.08); }
            .process-step:last-child { border-bottom: none; }
            .nav-right .nav-link { display: none; }
        }
        @media (max-width: 540px) {
            .hero-stats-row { grid-template-columns: 1fr 1fr 1fr; }
            .process-steps { grid-template-columns: 1fr; }
            .cta-section { text-align: center; justify-content: center; }
        }
    </style>
</head>
<body>

{{-- NAV --}}
<nav>
    <a href="/" class="nav-brand">Donate<span>Bazaar</span></a>
    <div class="nav-right">
        <a href="/about" class="nav-link">About</a>
        <a href="/career" class="nav-link" style="color:var(--ink);">Careers</a>
        <a href="/" class="nav-cta">← Back to Home</a>
    </div>
</nav>

{{-- HERO --}}
<section class="hero">
    <div class="hero-left">
        <p class="hero-eyebrow">Join our mission</p>
        <h1>Work that <em>matters.</em></h1>
        <p class="hero-desc">We're building the future of giving. Join a team driven by purpose, not just profit — where every line of code helps someone, somewhere.</p>
        <div class="hero-actions">
            <a href="#open-roles" class="btn-primary">
                View Open Roles
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
            <a href="/about" class="btn-outline">Our Story</a>
        </div>
    </div>
    <div class="hero-right">
        <div class="hero-stats-row">
            <div class="hero-stat">
                <div class="hero-stat-val">{{ $jobPosts->total() }}<span>+</span></div>
                <div class="hero-stat-label">Open Roles</div>
            </div>
            <div class="hero-stat">
                <div class="hero-stat-val">100<span>%</span></div>
                <div class="hero-stat-label">Remote</div>
            </div>
            <div class="hero-stat">
                <div class="hero-stat-val">4.9<span>★</span></div>
                <div class="hero-stat-label">Glassdoor</div>
            </div>
        </div>
        <blockquote class="hero-quote">
            The best time to do meaningful work is now. The second best time is also now.
        </blockquote>
        <div class="hero-quote-attr">— DonateBazaar Team Culture</div>
        <br>
        <div class="scroll-indicator">
            <div class="scroll-line"></div>
            Scroll to explore
        </div>
    </div>
</section>

{{-- WHY WORK WITH US --}}
<section class="why-section">
    <div class="section-header">
        <div>
            <p class="section-eyebrow">Why DonateBazaar</p>
            <h2 class="section-title">Built different,<br>built for good.</h2>
        </div>
    </div>
    <div class="perks-grid">
        @php
        $perks = [
            ['icon' => '<circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>', 'title' => 'Purpose-Driven Work', 'desc' => 'Every feature you ship directly helps NGOs raise more funds and reach more people in need.'],
            ['icon' => '<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>', 'title' => 'Remote-First Culture', 'desc' => 'Work from wherever you do your best work. We\'re async-first and results-focused, not clock-watchers.'],
            ['icon' => '<polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/>', 'title' => 'Fast Growth', 'desc' => 'Early-stage startup energy with the stability of a funded company. Your career grows with ours.'],
            ['icon' => '<path d="M12 2a7 7 0 0 1 7 7c0 4-4 6-4 9H9c0-3-4-5-4-9a7 7 0 0 1 7-7z"/><line x1="9" y1="21" x2="15" y2="21"/><line x1="10" y1="18" x2="14" y2="18"/>', 'title' => 'Learning Budget', 'desc' => '₹50,000/year for courses, books, conferences, or tools. We invest in your growth first.'],
            ['icon' => '<path d="M22 12h-4l-3 9L9 3l-3 9H2"/>', 'title' => 'Health & Wellness', 'desc' => 'Comprehensive health coverage for you and your family, plus a monthly wellness allowance.'],
            ['icon' => '<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>', 'title' => 'Ownership & Impact', 'desc' => 'ESOPs for all full-time employees. You own a piece of what you help build.'],
        ];
        @endphp
        @foreach($perks as $perk)
        <div class="perk-card">
            <div class="perk-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">{!! $perk['icon'] !!}</svg>
            </div>
            <div class="perk-title">{{ $perk['title'] }}</div>
            <p class="perk-desc">{{ $perk['desc'] }}</p>
        </div>
        @endforeach
    </div>
</section>

{{-- HIRING PROCESS --}}
<section class="process-section">
    <div class="section-header" style="margin-bottom:0;">
        <div>
            <p class="section-eyebrow">How we hire</p>
            <h2 class="section-title" style="color:var(--white);">Simple. Transparent.<br>Respectful of your time.</h2>
        </div>
    </div>
    <div class="process-steps">
        @php
        $steps = [
            ['01','Apply Online','Submit your application with a CV and a brief cover note. No lengthy forms — we value your time.','~10 minutes'],
            ['02','Intro Call','A 30-min video call with the hiring manager. No trick questions — just a real conversation.','~30 minutes'],
            ['03','Skill Assessment','A take-home task relevant to the role. Paid for your time. We review with context, not just output.','~2–3 hours'],
            ['04','Final Interview','Meet the team. Ask us anything. We\'ll share an offer decision within 48 hours of your final round.','~1 hour'],
        ];
        @endphp
        @foreach($steps as [$num, $title, $desc, $tag])
        <div class="process-step">
            <span class="step-num">{{ $num }}</span>
            <div class="step-title">{{ $title }}</div>
            <p class="step-desc">{{ $desc }}</p>
            <span class="step-tag">{{ $tag }}</span>
        </div>
        @endforeach
    </div>
</section>

{{-- TEAM TESTIMONIALS --}}
<section class="culture-section">
    <div class="section-header">
        <div>
            <p class="section-eyebrow">Team voices</p>
            <h2 class="section-title">Hear from the<br>people inside.</h2>
        </div>
    </div>
    <div class="testimonials-grid">
        @php
        $testimonials = [
            ['RK','Rohit Kumar','Senior Backend Engineer','Joining DonateBazaar was the best career decision I\'ve made. I shipped a feature in my first week that helped 200+ NGOs — that kind of impact is rare.'],
            ['PS','Priya Sharma','Product Designer','The culture here is genuinely remote-first, not remote-tolerant. My opinions are heard, my boundaries are respected, and my work actually matters.'],
            ['AJ','Ananya Joshi','Growth & Partnerships','I was skeptical about a startup this early-stage, but the team\'s clarity of purpose and transparency won me over. Six months in, zero regrets.'],
        ];
        @endphp
        @foreach($testimonials as [$initials, $name, $role, $quote])
        <div class="testimonial-card">
            <p class="testimonial-text">{{ $quote }}</p>
            <div class="testimonial-author">
                <div class="author-avatar">{{ $initials }}</div>
                <div>
                    <div class="author-name">{{ $name }}</div>
                    <div class="author-role">{{ $role }}</div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>

{{-- OPEN ROLES HEADER --}}
<div class="jobs-header-section" id="open-roles">
    <p class="section-eyebrow">Open roles</p>
    <h2 class="section-title">Find your place<br>on the team.</h2>
</div>

<form method="GET" action="{{ url('/career') }}" id="filterForm">
    <input type="hidden" id="hiddenType"   name="type"   value="{{ request('type', '') }}">
    <input type="hidden" id="hiddenSearch" name="search" value="{{ request('search', '') }}">

    <div class="filters-bar">
        <div class="search-wrap">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
            </svg>
            <input class="search-input" type="text" id="searchVisible"
                placeholder="Search roles…" value="{{ request('search', '') }}" autocomplete="off">
        </div>

        <div class="type-filter" id="typeFilter">
            @php
                $types  = ['full-time','part-time','contract','internship','freelance','remote','volunteer'];
                $active = request('type', '');
            @endphp
            <button type="button" class="type-btn {{ ($active === '' || $active === null) ? 'active' : '' }}"
                    data-type="" onclick="setType(this)">All</button>
            @foreach($types as $t)
            <button type="button" class="type-btn {{ $active === $t ? 'active' : '' }}"
                    data-type="{{ $t }}" onclick="setType(this)">{{ ucfirst($t) }}</button>
            @endforeach
        </div>

        <span class="jobs-count" id="jobsCount">
            {{ $jobPosts->total() }} position{{ $jobPosts->total() !== 1 ? 's' : '' }}
        </span>
    </div>
</form>

{{-- JOB GRID --}}
{{-- NOTE: The controller already excludes expired jobs via whereDate('application_deadline','>=',today).
     No view-level filtering needed — every $job here is guaranteed to be active and not expired. --}}
<section class="jobs-section">
    <div class="jobs-grid" id="jobsGrid">
        @forelse($jobPosts as $job)

        @php
            /*
             * Deadline display logic — clean integers only, no floats.
             *
             * Carbon::diffInDays() always returns a positive integer.
             * We use startOfDay() comparison to get whole-day counts.
             * The controller already guarantees deadline >= today, so
             * we only need to handle: today (0 days), 1–7 days (urgent), or future.
             */
            $deadlineLabel  = null;
            $deadlineUrgent = false;

            if ($job->application_deadline) {
                $deadline  = \Carbon\Carbon::parse($job->application_deadline)->startOfDay();
                $today     = \Carbon\Carbon::now()->startOfDay();
                $daysLeft  = (int) $today->diffInDays($deadline); // always positive integer

                if ($daysLeft === 0) {
                    $deadlineLabel  = 'Closes today';
                    $deadlineUrgent = true;
                } elseif ($daysLeft === 1) {
                    $deadlineLabel  = 'Closes tomorrow';
                    $deadlineUrgent = true;
                } elseif ($daysLeft <= 7) {
                    $deadlineLabel  = $daysLeft . ' days left';
                    $deadlineUrgent = true;
                } else {
                    $deadlineLabel  = 'Closes ' . $deadline->format('d M');
                    $deadlineUrgent = false;
                }
            }
        @endphp

        <article class="job-card">
            <div class="card-top">
                <h2 class="job-title">{{ $job->title }}</h2>
                <span class="badge badge-type">{{ ucfirst($job->type) }}</span>
            </div>
            <div class="card-meta">
                @if($job->location)
                <span class="meta-item">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                        <circle cx="12" cy="10" r="3"/>
                    </svg>
                    {{ $job->location }}
                </span>
                @endif
                @if($job->is_remote)
                <span class="badge badge-remote">Remote</span>
                @endif
                @if($job->salary)
                <span class="meta-item">₹{{ $job->salary }} / Annum</span>
                @endif
            </div>
            <p class="card-excerpt">{{ Str::limit(strip_tags($job->description), 160) }}</p>
            <div class="card-footer">
                @if($deadlineLabel)
                    <span class="deadline {{ $deadlineUrgent ? 'urgent' : '' }}">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                        </svg>
                        {{ $deadlineLabel }}
                    </span>
                @else
                    <span></span>
                @endif
                <a href="{{ route('job_posts.show', $job->slug) }}" class="apply-btn">
                    View &amp; Apply
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </article>

        @empty
        <div class="empty">
            <div class="empty-icon">🔍</div>
            <h3>No openings found</h3>
            <p>Try adjusting your search or check back soon — we're always growing.</p>
        </div>
        @endforelse
    </div>
</section>

{{-- PAGINATION --}}
@if($jobPosts->hasPages())
<div class="pagination-wrap">
    {{ $jobPosts->appends(['search' => request('search'), 'type' => request('type')])->links() }}
</div>
@endif

{{-- CTA --}}
<div class="cta-section">
    <div class="cta-left">
        <h2>Don't see the right role?</h2>
        <p>We're always on the lookout for exceptional people. Send us your profile and we'll reach out when something fits.</p>
    </div>
    <a href="mailto:careers@donatebazaar.com" class="cta-btn">
        Send Open Application
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
    </a>
</div>

{{-- FOOTER --}}
<footer>
    <div class="footer-brand">Donate<span>Bazaar</span></div>
    <div class="footer-links">
        <a href="/">Home</a>
        <a href="/about">About</a>
        <a href="/career">Careers</a>
        <a href="/contact">Contact</a>
    </div>
    <div class="footer-copy">© {{ date('Y') }} DonateBazaar. All rights reserved.</div>
</footer>

<script>
(function () {
'use strict';

document.querySelectorAll('.job-card').forEach(function (card, i) {
    card.style.animationDelay = (i * 0.06) + 's';
});

window.setType = function (btn) {
    document.getElementById('hiddenType').value = btn.dataset.type;
    document.querySelectorAll('#typeFilter .type-btn').forEach(function (b) {
        b.classList.remove('active');
    });
    btn.classList.add('active');
    document.getElementById('filterForm').submit();
};

var searchTimer;
var searchVisible = document.getElementById('searchVisible');
var hiddenSearch  = document.getElementById('hiddenSearch');

searchVisible.addEventListener('input', function () {
    hiddenSearch.value = this.value;
    clearTimeout(searchTimer);
    searchTimer = setTimeout(function () {
        document.getElementById('filterForm').submit();
    }, 420);
});

searchVisible.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
        this.value = '';
        hiddenSearch.value = '';
        document.getElementById('filterForm').submit();
    }
});

})();
</script>
</body>
</html>