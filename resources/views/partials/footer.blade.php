<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Footer — DonateBazaar</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --accent:  #7c6dfa;
            --accent2: #9b59f5;
            --green:   #7effc4;
            --pink:    #ec6eaf;
            --font-mono: 'DM Mono', monospace;
            --font:      'DM Sans', sans-serif;
        }

        body {
            font-family: var(--font);
            background: #f0f2f8;
        }

        /* Respect reduced motion preference across the whole footer */
        @media (prefers-reduced-motion: reduce) {
            * { animation-duration: 0.001ms !important; animation-iteration-count: 1 !important; transition-duration: 0.001ms !important; }
        }

        /* Visible keyboard focus, consistent everywhere */
        .site-footer a:focus-visible,
        .site-footer button:focus-visible,
        .site-footer input:focus-visible {
            outline: 2px solid var(--green);
            outline-offset: 3px;
            border-radius: 6px;
        }

        /* ═══════════════════════════════
           FOOTER
        ═══════════════════════════════ */
        .site-footer {
            position: relative;
            background:
                radial-gradient(ellipse at 65% 0%,  rgba(124,109,250,0.18) 0%, transparent 52%),
                radial-gradient(ellipse at 10% 100%, rgba(155,89,245,0.12) 0%, transparent 48%),
                linear-gradient(160deg, #0d0e1a 0%, #13122b 55%, #1a1040 100%);
            color: white;
            padding-top: 96px;
            padding-bottom: 44px;
            overflow: hidden;
            font-family: var(--font);
        }

        /* Grid overlay */
        .site-footer::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(124,109,250,0.035) 1px, transparent 1px),
                linear-gradient(90deg, rgba(124,109,250,0.035) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
            z-index: 0;
        }

        /* Decorative rings */
        .footer-ring {
            position: absolute;
            border-radius: 50%;
            border: 1px solid rgba(124,109,250,0.09);
            pointer-events: none;
        }
        .footer-ring-1 { width: 560px; height: 560px; top: -200px; right: -160px; }
        .footer-ring-2 { width: 360px; height: 360px; top: -110px; right: -90px;  border-color: rgba(124,109,250,0.06); }
        .footer-ring-3 { width: 400px; height: 400px; bottom: -130px; left: -130px; border-color: rgba(155,89,245,0.06); }

        /* Particle canvas */
        #footer-canvas {
            position: absolute;
            inset: 0;
            pointer-events: none;
            z-index: 0;
        }

        /* Inner wrapper */
        .footer-inner {
            position: relative;
            z-index: 1;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 36px;
        }

        /* ── CTA Banner ── */
        .footer-cta {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(124,109,250,0.20);
            border-radius: 22px;
            padding: 38px 44px;
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
            gap: 28px;
            margin-bottom: 76px;
            backdrop-filter: blur(14px);
            position: relative;
            overflow: hidden;
            isolation: isolate;
            transition: border-color 0.4s ease, box-shadow 0.4s ease;
        }
        /* Subtle "alive" glow that drifts — gives the banner presence without being loud */
        .footer-cta::before {
            content: '';
            position: absolute;
            inset: -40%;
            background: conic-gradient(from 0deg, transparent 0deg, rgba(124,109,250,0.10) 90deg, transparent 180deg, rgba(236,110,175,0.08) 270deg, transparent 360deg);
            animation: ctaSpin 16s linear infinite;
            z-index: -1;
            opacity: 0.7;
        }
        @keyframes ctaSpin { to { transform: rotate(360deg); } }
        .footer-cta::after {
            content: '';
            position: absolute;
            bottom: 0; left: 50%; transform: translateX(-50%);
            width: 40%; height: 1px;
            background: linear-gradient(90deg, transparent, rgba(155,89,245,0.20), transparent);
        }
        .footer-cta:hover {
            border-color: rgba(124,109,250,0.34);
            box-shadow: 0 0 60px -20px rgba(124,109,250,0.45);
        }

        .cta-eyebrow {
            font-family: var(--font-mono);
            font-size: 10px;
            font-weight: 500;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: var(--green);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .cta-eyebrow-dot {
            width: 5px; height: 5px;
            border-radius: 50%;
            background: var(--green);
            box-shadow: 0 0 0 0 rgba(126,255,196,0.6);
            animation: blinkPulse 1.8s ease-in-out infinite;
        }
        .cta-text h2 {
            font-size: 23px;
            font-weight: 600;
            letter-spacing: -0.025em;
            color: white;
            margin-bottom: 6px;
            line-height: 1.25;
        }
        .cta-text p {
            font-size: 14px;
            color: rgba(255,255,255,0.50);
            line-height: 1.6;
        }

        /* Stats inside CTA — now with a count-up affordance hint */
        .cta-stats {
            display: flex;
            gap: 28px;
            margin-top: 18px;
        }
        .cta-stat { display: flex; flex-direction: column; gap: 2px; }
        .cta-stat-num {
            font-family: var(--font-mono);
            font-size: 18px;
            font-weight: 500;
            color: white;
            letter-spacing: -0.02em;
            font-variant-numeric: tabular-nums;
        }
        .cta-stat-label { font-size: 11.5px; color: rgba(255,255,255,0.40); }

        /* CTA buttons */
        .cta-actions {
            display: flex;
            gap: 12px;
            align-items: center;
            flex-shrink: 0;
        }
        .cta-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #6c5ff5 0%, #9b59f5 100%);
            color: white;
            font-family: var(--font-mono);
            font-size: 13px;
            font-weight: 500;
            padding: 13px 28px;
            border-radius: 12px;
            text-decoration: none;
            white-space: nowrap;
            box-shadow: 0 4px 24px rgba(124,109,250,0.40);
            transition: opacity 0.2s, transform 0.15s, box-shadow 0.2s;
            position: relative;
            overflow: hidden;
        }
        /* Sheen sweep on hover — reads as "responsive", not decorative noise */
        .cta-btn::before {
            content: '';
            position: absolute;
            top: 0; left: -120%;
            width: 60%; height: 100%;
            background: linear-gradient(115deg, transparent, rgba(255,255,255,0.30), transparent);
            transition: left 0.55s ease;
        }
        .cta-btn:hover::before { left: 130%; }
        .cta-btn:hover {
            opacity: 0.96;
            transform: translateY(-1px);
            box-shadow: 0 8px 36px rgba(124,109,250,0.55);
        }
        .cta-btn:active { transform: translateY(0) scale(0.98); }
        .cta-btn svg { transition: transform 0.25s ease; }
        .cta-btn:hover svg { transform: rotate(-8deg) scale(1.08); }

        .cta-btn-ghost {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.12);
            color: rgba(255,255,255,0.72);
            font-family: var(--font-mono);
            font-size: 13px;
            font-weight: 500;
            padding: 13px 24px;
            border-radius: 12px;
            text-decoration: none;
            white-space: nowrap;
            transition: background 0.2s, color 0.2s, border-color 0.2s, transform 0.15s;
        }
        .cta-btn-ghost span {
            display: inline-block;
            transition: transform 0.25s ease;
        }
        .cta-btn-ghost:hover {
            background: rgba(255,255,255,0.10);
            color: white;
            border-color: rgba(255,255,255,0.22);
            transform: translateY(-1px);
        }
        .cta-btn-ghost:hover span { transform: translateX(3px); }
        .cta-btn-ghost:active { transform: translateY(0) scale(0.98); }

        /* ── Main grid ── */
        .footer-grid {
            display: grid;
            grid-template-columns: 1.7fr 1fr 1fr 1.6fr;
            gap: 52px;
            margin-bottom: 68px;
        }

        /* Brand column */
        .footer-brand-name {
            font-family: var(--font-mono);
            font-size: 21px;
            font-weight: 500;
            letter-spacing: -0.025em;
            background: linear-gradient(135deg, #a89cff, #c77dff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 14px;
            display: inline-block;
        }
        .footer-brand-desc {
            font-size: 13.5px;
            color: rgba(255,255,255,0.45);
            line-height: 1.75;
            margin-bottom: 24px;
            max-width: 320px;
        }

        /* Trust pill */
        .trust-pill {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: rgba(126,255,196,0.07);
            border: 1px solid rgba(126,255,196,0.16);
            border-radius: 99px;
            padding: 6px 14px;
            font-family: var(--font-mono);
            font-size: 10.5px;
            font-weight: 500;
            color: var(--green);
            letter-spacing: 0.06em;
            margin-bottom: 24px;
            width: fit-content;
            transition: background 0.2s, border-color 0.2s;
        }
        .trust-pill:hover {
            background: rgba(126,255,196,0.12);
            border-color: rgba(126,255,196,0.30);
        }
        .trust-pill-dot {
            width: 5px; height: 5px;
            border-radius: 50%;
            background: var(--green);
            animation: blinkPulse 1.8s ease-in-out infinite;
        }
        @keyframes blinkPulse {
            0%   { opacity: 1;    box-shadow: 0 0 0 0 rgba(126,255,196,0.55); }
            70%  { opacity: 0.35; box-shadow: 0 0 0 5px rgba(126,255,196,0); }
            100% { opacity: 1;    box-shadow: 0 0 0 0 rgba(126,255,196,0); }
        }

        /* Social icons */
        .social-row { display: flex; gap: 9px; }
        .social-btn {
            width: 37px; height: 37px;
            border-radius: 10px;
            background: rgba(124,109,250,0.10);
            border: 1px solid rgba(124,109,250,0.20);
            display: flex; align-items: center; justify-content: center;
            color: rgba(255,255,255,0.55);
            text-decoration: none;
            transition: background 0.2s, color 0.2s, border-color 0.2s, transform 0.15s, box-shadow 0.2s;
        }
        .social-btn:hover {
            background: var(--accent);
            border-color: var(--accent);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 8px 16px -4px rgba(124,109,250,0.55);
        }
        .social-btn:active { transform: translateY(-1px) scale(0.95); }

        /* Link columns */
        .footer-col h3 {
            font-family: var(--font-mono);
            font-size: 10.5px;
            font-weight: 500;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.32);
            margin-bottom: 22px;
            position: relative;
            padding-bottom: 12px;
        }
        /* Tiny accent underline so section headers feel anchored, not floating labels */
        .footer-col h3::after {
            content: '';
            position: absolute;
            left: 0; bottom: 0;
            width: 18px; height: 2px;
            background: linear-gradient(90deg, var(--accent), transparent);
            border-radius: 2px;
        }
        .footer-col ul {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 13px;
        }
        .footer-col ul li a {
            font-size: 13.5px;
            color: rgba(255,255,255,0.52);
            text-decoration: none;
            transition: color 0.2s, padding-left 0.2s;
            display: flex;
            align-items: center;
            gap: 7px;
        }
        .footer-col ul li a::before {
            content: '';
            width: 4px; height: 4px;
            border-radius: 50%;
            background: rgba(124,109,250,0.45);
            flex-shrink: 0;
            transition: background 0.2s, transform 0.2s;
        }
        .footer-col ul li a:hover { color: white; padding-left: 4px; }
        .footer-col ul li a:hover::before { background: var(--green); transform: scale(1.5); }

        /* ── Newsletter column ── */
        .newsletter-col h3 {
            font-family: var(--font-mono);
            font-size: 10.5px;
            font-weight: 500;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.32);
            margin-bottom: 22px;
            position: relative;
            padding-bottom: 12px;
        }
        .newsletter-col h3::after {
            content: '';
            position: absolute;
            left: 0; bottom: 0;
            width: 18px; height: 2px;
            background: linear-gradient(90deg, var(--green), transparent);
            border-radius: 2px;
        }
        .newsletter-desc {
            font-size: 13px;
            color: rgba(255,255,255,0.45);
            line-height: 1.68;
            margin-bottom: 18px;
        }

        .newsletter-form-wrap {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(124,109,250,0.22);
            border-radius: 12px;
            overflow: hidden;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
            margin-bottom: 14px;
        }
        .newsletter-form-wrap:hover {
            border-color: rgba(124,109,250,0.36);
            background: rgba(255,255,255,0.07);
        }
        .newsletter-form-wrap:focus-within {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(124,109,250,0.14);
        }
        .newsletter-form-inner {
            display: flex;
            align-items: stretch;
        }
        .newsletter-form-inner input {
            flex: 1;
            background: transparent;
            border: none;
            outline: none;
            padding: 11px 14px;
            font-family: var(--font);
            font-size: 13px;
            color: white;
            min-width: 0;
        }
        .newsletter-form-inner input::placeholder { color: rgba(255,255,255,0.25); }
        /* Invalid email feedback right on the field, no layout shift */
        .newsletter-form-inner input:invalid:not(:placeholder-shown):not(:focus) {
            box-shadow: inset 0 -2px 0 0 rgba(255,100,100,0.55);
        }
        .newsletter-form-inner button {
            background: linear-gradient(135deg, #6c5ff5, #9b59f5);
            border: none;
            padding: 0 20px;
            font-family: var(--font-mono);
            font-size: 12px;
            font-weight: 500;
            color: white;
            cursor: pointer;
            transition: opacity 0.2s, letter-spacing 0.2s;
            white-space: nowrap;
            letter-spacing: 0.03em;
            flex-shrink: 0;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            min-width: 78px;
            justify-content: center;
        }
        .newsletter-form-inner button:hover { opacity: 0.88; letter-spacing: 0.05em; }
        .newsletter-form-inner button:disabled { opacity: 0.55; cursor: default; letter-spacing: 0.03em; }

        /* Lightweight inline spinner shown while the form submits */
        .btn-spinner {
            width: 11px; height: 11px;
            border-radius: 50%;
            border: 1.6px solid rgba(255,255,255,0.35);
            border-top-color: white;
            display: none;
            animation: spin 0.7s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
        .newsletter-form-inner button.is-loading .btn-spinner { display: inline-block; }
        .newsletter-form-inner button.is-loading .btn-label { display: none; }

        /* Feedback messages */
        .nl-feedback {
            font-size: 12px;
            padding: 8px 12px;
            border-radius: 8px;
            margin-bottom: 14px;
            display: none;
            align-items: center;
            gap: 7px;
        }
        .nl-feedback.show { display: flex; }
        .nl-feedback.success {
            background: rgba(126,255,196,0.08);
            border: 1px solid rgba(126,255,196,0.20);
            color: var(--green);
        }
        .nl-feedback.error {
            background: rgba(255,100,100,0.08);
            border: 1px solid rgba(255,100,100,0.20);
            color: #ff9090;
        }

        /* Mini stats */
        .mini-stats { display: flex; gap: 16px; flex-wrap: wrap; }
        .mini-stat {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: rgba(255,255,255,0.38);
        }
        .mini-stat-dot {
            width: 5px; height: 5px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        /* ── Bottom bar ── */
        .footer-divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(124,109,250,0.22) 30%, rgba(124,109,250,0.22) 70%, transparent);
            margin-bottom: 32px;
        }
        .footer-bottom {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }
        .footer-bottom-copy {
            font-size: 12.5px;
            color: rgba(255,255,255,0.28);
            font-family: var(--font-mono);
        }
        .footer-bottom-links { display: flex; gap: 24px; }
        .footer-bottom-links a {
            font-size: 12.5px;
            color: rgba(255,255,255,0.32);
            text-decoration: none;
            transition: color 0.2s;
            position: relative;
        }
        .footer-bottom-links a::after {
            content: '';
            position: absolute;
            left: 0; bottom: -3px;
            width: 0; height: 1px;
            background: rgba(255,255,255,0.5);
            transition: width 0.2s ease;
        }
        .footer-bottom-links a:hover { color: rgba(255,255,255,0.85); }
        .footer-bottom-links a:hover::after { width: 100%; }

        /* Back-to-top — small, useful, on-brand */
        .back-to-top {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 34px; height: 34px;
            border-radius: 10px;
            background: rgba(124,109,250,0.10);
            border: 1px solid rgba(124,109,250,0.20);
            color: rgba(255,255,255,0.55);
            cursor: pointer;
            transition: background 0.2s, color 0.2s, border-color 0.2s, transform 0.15s;
            flex-shrink: 0;
        }
        .back-to-top:hover {
            background: var(--accent);
            border-color: var(--accent);
            color: white;
            transform: translateY(-2px);
        }
        .back-to-top svg { transition: transform 0.2s ease; }
        .back-to-top:hover svg { transform: translateY(-2px); }

        .made-with {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 12px;
            color: rgba(255,255,255,0.26);
            font-family: var(--font-mono);
        }
        .heart-pulse {
            display: inline-block;
            animation: heartbeat 1.4s ease-in-out infinite;
        }
        @keyframes heartbeat {
            0%, 100% { transform: scale(1); }
            14%       { transform: scale(1.28); }
            28%       { transform: scale(1); }
            42%       { transform: scale(1.16); }
            70%       { transform: scale(1); }
        }

        /* ── Responsive ── */
        @media (max-width: 960px) {
            .footer-grid { grid-template-columns: 1fr 1fr; gap: 36px; }
            .cta-actions { flex-direction: column; }
        }
        @media (max-width: 600px) {
            .footer-grid { grid-template-columns: 1fr; gap: 28px; }
            .footer-cta { flex-direction: column; align-items: flex-start; padding: 28px 24px; }
            .footer-bottom { flex-direction: column; align-items: flex-start; gap: 12px; }
            .site-footer { padding-top: 60px; }
            .footer-inner { padding: 0 20px; }
            .cta-stats { gap: 20px; }
            .cta-actions { flex-direction: column; width: 100%; }
            .cta-btn, .cta-btn-ghost { width: 100%; justify-content: center; }
            .footer-bottom-links { gap: 18px; }
        }
    </style>
</head>
<body>

<!-- ══════════════════════════════
     FOOTER
══════════════════════════════ -->
<footer class="site-footer" id="site-footer">

    <!-- Decorative geometry -->
    <div class="footer-ring footer-ring-1"></div>
    <div class="footer-ring footer-ring-2"></div>
    <div class="footer-ring footer-ring-3"></div>

    <!-- Particle canvas -->
    <canvas id="footer-canvas"></canvas>

    <div class="footer-inner">

        <!-- CTA Banner -->
        <div class="footer-cta">
            <div class="cta-left">
                <div class="cta-eyebrow">
                    <span class="cta-eyebrow-dot"></span>
                    Live campaigns
                </div>
                <div class="cta-text">
                    <h2>Ready to Make an Impact?</h2>
                    <p>Join thousands of donors changing lives every single day.</p>
                </div>
                <div class="cta-stats">
                    <div class="cta-stat">
                        <span class="cta-stat-num" data-count-to="2.5" data-suffix="M+">0</span>
                        <span class="cta-stat-label">Donors</span>
                    </div>
                    <div class="cta-stat">
                        <span class="cta-stat-num" data-count-to="480" data-prefix="₹" data-suffix="Cr+">₹0</span>
                        <span class="cta-stat-label">Raised</span>
                    </div>
                    <div class="cta-stat">
                        <span class="cta-stat-num" data-count-to="12" data-suffix="K+">0</span>
                        <span class="cta-stat-label">Campaigns</span>
                    </div>
                </div>
            </div>
            <div class="cta-actions">
                <a href="{{ route('all.campaigns') }}" class="cta-btn">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                    </svg>
                    Explore Campaigns
                </a>
                <a href="{{ route('campaign.create') }}" class="cta-btn-ghost">Start a Fundraiser <span>→</span></a>
            </div>
        </div>

        <!-- Main grid -->
        <div class="footer-grid">

            <!-- Brand -->
            <div>
                <div class="footer-brand-name">DonateBazaar</div>
                <p class="footer-brand-desc">
                    A trusted platform connecting donors with verified causes.
                    Transparent, secure, and impactful giving for a better world.
                </p>
                <div class="trust-pill">
                    <span class="trust-pill-dot"></span>
                    2.5M+ donors trust us
                </div>
                <div class="social-row">
                    <a href="#" class="social-btn" aria-label="Facebook">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>
                        </svg>
                    </a>
                    <a href="#" class="social-btn" aria-label="Twitter / X">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M4 4l16 16M20 4L4 20" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" fill="none"/>
                        </svg>
                    </a>
                    <a href="#" class="social-btn" aria-label="Instagram">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="2" width="20" height="20" rx="5" ry="5"/>
                            <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/>
                            <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/>
                        </svg>
                    </a>
                    <a href="#" class="social-btn" aria-label="LinkedIn">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/>
                            <rect x="2" y="9" width="4" height="12"/>
                            <circle cx="4" cy="4" r="2"/>
                        </svg>
                    </a>
                    <a href="#" class="social-btn" aria-label="YouTube">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22.54 6.42a2.78 2.78 0 0 0-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46a2.78 2.78 0 0 0-1.95 1.96A29 29 0 0 0 1 12a29 29 0 0 0 .46 5.58A2.78 2.78 0 0 0 3.41 19.6C5.12 20 12 20 12 20s6.88 0 8.59-.46a2.78 2.78 0 0 0 1.95-1.95A29 29 0 0 0 23 12a29 29 0 0 0-.46-5.58z"/>
                            <polygon points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02" fill="currentColor"/>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Platform links -->
            <div class="footer-col">
                <h3>Platform</h3>
                <ul>
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="{{ route('all.campaigns') }}">Campaigns</a></li>
                    <li><a href="{{ route('campaign.create') }}">Start Fundraiser</a></li>
                    <li><a href="{{ route('how.it.works') }}">How It Works</a></li>
                    <li><a href="{{ route('ddrf.index') }}">Disaster Relief</a></li>
                </ul>
            </div>

            <!-- Company links -->
            <div class="footer-col">
                <h3>Company</h3>
                <ul>
                    <li><a href="{{ route('about') }}">About Us</a></li>
                    <li><a href="{{ route('contact') }}">Contact</a></li>
                    <li><a href="{{ route('job_posts.index') }}">Careers</a></li>
                    <li><a href="{{ route('blogs.index') }}">Blog</a></li>
                    <li><a href="{{ route('partnership') }}">Partnership</a></li>
                </ul>
            </div>

            <!-- Newsletter -->
            <div class="newsletter-col">
                <h3>Stay Updated</h3>
                <p class="newsletter-desc">
                    Get inspiring stories, new campaigns, and impact reports — straight to your inbox.
                </p>

                {{--
                    <form> remains the outer element so Laravel/CSRF/validation work exactly as before.
                    JS below only adds a loading state + inline success/error swap;
                    it still allows a normal POST if JS is unavailable.
                --}}
                <form id="newsletter-form" action="{{ route('newsletter.subscribe') }}" method="POST" novalidate>
                    @csrf

                    {{-- Success flash --}}
                    <div class="nl-feedback success @if(session('newsletter_success')) show @endif" id="nl-success">
                        <span>✓</span><span>{{ session('newsletter_success') ?? 'You are subscribed. Welcome aboard!' }}</span>
                    </div>

                    {{-- Validation error --}}
                    @error('email')
                        <div class="nl-feedback error show">
                            <span>!</span><span>{{ $message }}</span>
                        </div>
                    @enderror
                    <div class="nl-feedback error" id="nl-error">
                        <span>!</span><span>Enter a valid email address.</span>
                    </div>

                    <div class="newsletter-form-wrap">
                        <div class="newsletter-form-inner">
                            <input
                                type="email"
                                name="email"
                                placeholder="Enter your email"
                                aria-label="Email for newsletter"
                                value="{{ old('email') }}"
                                required
                            >
                            <button type="submit" id="nl-submit">
                                <span class="btn-label">Join</span>
                                <span class="btn-spinner" aria-hidden="true"></span>
                            </button>
                        </div>
                    </div>
                </form>

                <div class="mini-stats">
                    <div class="mini-stat">
                        <span class="mini-stat-dot" style="background:var(--green);"></span>
                        10K+ subscribers
                    </div>
                    <div class="mini-stat">
                        <span class="mini-stat-dot" style="background:rgba(124,109,250,0.8);"></span>
                        No spam, ever
                    </div>
                </div>
            </div>

        </div><!-- /footer-grid -->

        <!-- Bottom bar -->
        <div class="footer-divider"></div>
        <div class="footer-bottom">
            <div class="footer-bottom-copy">© 2026 DonateBazaar. All rights reserved.</div>
            <div class="made-with">
                Made with <span class="heart-pulse">♥</span> for a better world
            </div>
            <div style="display:flex; align-items:center; gap:24px;">
                <div class="footer-bottom-links">
                    <a href="#">Privacy</a>
                    <a href="#">Terms</a>
                    <a href="#">Security</a>
                </div>
                <button class="back-to-top" id="back-to-top" aria-label="Back to top">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 19V5M5 12l7-7 7 7"/>
                    </svg>
                </button>
            </div>
        </div>

    </div><!-- /footer-inner -->

</footer>

<script>
(function () {
    const canvas = document.getElementById('footer-canvas');
    const footer = canvas.parentElement;
    const ctx    = canvas.getContext('2d');
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    function resize() {
        canvas.width  = footer.offsetWidth;
        canvas.height = footer.offsetHeight;
    }
    resize();
    window.addEventListener('resize', resize);

    const ACCENT = { r: 124, g: 109, b: 250 };
    const GREEN  = { r: 126, g: 255, b: 196 };
    const PINK   = { r: 236, g: 110, b: 175 };

    const TOTAL_PARTICLES = 55;
    const particles = [];

    function rand(min, max) { return Math.random() * (max - min) + min; }

    function makeParticle() {
        const isHeart = Math.random() < 0.18;
        const palette = Math.random() < 0.6 ? ACCENT : (Math.random() < 0.5 ? GREEN : PINK);
        return {
            x: rand(0, canvas.width),
            y: rand(canvas.height * 0.1, canvas.height),
            vx: rand(-0.18, 0.18),
            vy: rand(-0.55, -0.18),
            size: isHeart ? rand(5, 10) : rand(1.5, 3.5),
            alpha: 0,
            alphaTarget: rand(0.12, 0.38),
            fadeIn: true,
            life: 0,
            maxLife: rand(200, 380),
            isHeart,
            r: palette.r, g: palette.g, b: palette.b,
        };
    }

    for (let i = 0; i < TOTAL_PARTICLES; i++) {
        const p = makeParticle();
        p.life = Math.floor(rand(0, p.maxLife));
        particles.push(p);
    }

    function drawHeart(ctx, x, y, size, alpha, r, g, b) {
        ctx.save();
        ctx.translate(x, y);
        ctx.scale(size * 0.08, size * 0.08);
        ctx.beginPath();
        ctx.moveTo(0, -3);
        ctx.bezierCurveTo( 0, -6, -5, -6, -5, -2);
        ctx.bezierCurveTo(-5,  1,  0,  5,  0,  7);
        ctx.bezierCurveTo( 0,  5,  5,  1,  5, -2);
        ctx.bezierCurveTo( 5, -6,  0, -6,  0, -3);
        ctx.closePath();
        ctx.fillStyle = `rgba(${r},${g},${b},${alpha})`;
        ctx.fill();
        ctx.restore();
    }

    function tick() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        for (const p of particles) {
            if (p.fadeIn) {
                p.alpha = Math.min(p.alpha + 0.008, p.alphaTarget);
                if (p.alpha >= p.alphaTarget) p.fadeIn = false;
            } else {
                if (p.life > p.maxLife * 0.7) {
                    p.alpha = Math.max(p.alpha - 0.006, 0);
                }
            }

            p.x += p.vx;
            p.y += p.vy;
            p.life++;

            if (p.life >= p.maxLife || p.y < -20) {
                Object.assign(p, makeParticle());
                p.y      = canvas.height + 10;
                p.alpha  = 0;
                p.life   = 0;
                p.fadeIn = true;
            }

            if (p.alpha <= 0) continue;

            if (p.isHeart) {
                drawHeart(ctx, p.x, p.y, p.size, p.alpha, p.r, p.g, p.b);
            } else {
                const grd = ctx.createRadialGradient(p.x, p.y, 0, p.x, p.y, p.size * 2.5);
                grd.addColorStop(0,   `rgba(${p.r},${p.g},${p.b},${p.alpha})`);
                grd.addColorStop(0.4, `rgba(${p.r},${p.g},${p.b},${p.alpha * 0.4})`);
                grd.addColorStop(1,   `rgba(${p.r},${p.g},${p.b},0)`);
                ctx.beginPath();
                ctx.arc(p.x, p.y, p.size * 2.5, 0, Math.PI * 2);
                ctx.fillStyle = grd;
                ctx.fill();
            }
        }

        if (!reduceMotion) requestAnimationFrame(tick);
    }
    tick();
})();

/* ── Count-up stats: trigger once when the CTA scrolls into view ── */
(function () {
    const stats = document.querySelectorAll('.cta-stat-num[data-count-to]');
    if (!stats.length) return;

    function animateCount(el) {
        const target = parseFloat(el.dataset.countTo);
        const prefix = el.dataset.prefix || '';
        const suffix = el.dataset.suffix || '';
        const decimals = target % 1 !== 0 ? 1 : 0;
        const duration = 1100;
        const start = performance.now();

        function frame(now) {
            const progress = Math.min((now - start) / duration, 1);
            const eased = 1 - Math.pow(1 - progress, 3); // ease-out-cubic
            const value = (target * eased).toFixed(decimals);
            el.textContent = `${prefix}${value}${suffix}`;
            if (progress < 1) requestAnimationFrame(frame);
        }
        requestAnimationFrame(frame);
    }

    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (reduceMotion) {
        stats.forEach(el => {
            const target = parseFloat(el.dataset.countTo);
            el.textContent = `${el.dataset.prefix || ''}${target}${el.dataset.suffix || ''}`;
        });
        return;
    }

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateCount(entry.target);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.4 });

    stats.forEach(el => observer.observe(el));
})();

/* ── Newsletter form: lightweight client feedback, falls back to real POST ── */
(function () {
    const form = document.getElementById('newsletter-form');
    if (!form) return;
    const input = form.querySelector('input[name="email"]');
    const button = document.getElementById('nl-submit');
    const successEl = document.getElementById('nl-success');
    const errorEl = document.getElementById('nl-error');

    form.addEventListener('submit', function (e) {
        const isValidEmail = input.checkValidity();
        if (!isValidEmail) {
            e.preventDefault();
            successEl.classList.remove('show');
            errorEl.classList.add('show');
            input.focus();
            return;
        }
        // Let the real form submission proceed (server handles persistence + CSRF);
        // show a loading state in the meantime for immediate feedback.
        errorEl.classList.remove('show');
        button.classList.add('is-loading');
        button.disabled = true;
    });

    input.addEventListener('input', function () {
        errorEl.classList.remove('show');
    });
})();

/* ── Back to top ── */
(function () {
    const btn = document.getElementById('back-to-top');
    if (!btn) return;
    btn.addEventListener('click', function () {
        window.scrollTo({ top: 0, behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth' });
    });
})();
</script>

</body>
</html>