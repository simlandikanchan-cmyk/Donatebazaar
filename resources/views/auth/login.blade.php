<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — DonateBazaar</title>
    <script>
        (function () {
            var t = localStorage.getItem('theme');
            if (!t) {
                t = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            }
            document.documentElement.setAttribute('data-theme', t);
        })();
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            /* Backgrounds */
            --bg:          #f4f5fb;
            --surface:     #ffffff;
            --surface2:    #f8f9fe;

            /* Borders */
            --border:      rgba(0,0,0,0.06);
            --border2:     rgba(0,0,0,0.10);

            /* Text */
            --text:        #0f1117;
            --text2:       #4b5563;
            --text3:       #9ca3af;

            /* Accent */
            --accent:      #2563eb;
            --accent2:     #0d9488;
            --accent-glow: rgba(37,99,235,0.18);

            /* Semantic */
            --green:       #16a34a;
            --yellow:      #f59e0b;
            --red:         #ef4444;
            --blue:        #3b82f6;

            /* Typography */
            --font:        'DM Sans', sans-serif;
            --font-mono:   'DM Mono', monospace;

            /* Shape */
            --radius:      14px;
            --radius-sm:   9px;

            /* Elevation */
            --shadow:      0 1px 3px rgba(0,0,0,0.06), 0 4px 16px rgba(0,0,0,0.04);
            --shadow-lg:   0 8px 40px rgba(0,0,0,0.12);

            /* Login-specific mappings */
            --card:  var(--surface);
            --muted: var(--text2);
            --danger: var(--red);
        }

        [data-theme="dark"] {
            --bg:          #0b0c14;
            --surface:     #13141f;
            --surface2:    #1a1b2e;
            --border:      rgba(255,255,255,0.06);
            --border2:     rgba(255,255,255,0.10);
            --text:        #f0f1ff;
            --text2:       #a5b4c8;
            --text3:       #5a6579;
            --accent-glow: rgba(37,99,235,0.25);
            --shadow:      0 1px 3px rgba(0,0,0,0.3), 0 4px 16px rgba(0,0,0,0.2);
            --shadow-lg:   0 8px 40px rgba(0,0,0,0.5);
        }

        body {
            font-family: var(--font);
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
            position: relative;
            overflow: hidden;
            transition: background 0.3s ease, color 0.3s ease;
        }

        /* subtle bg blobs */
        body::before, body::after {
            content: '';
            position: fixed;
            border-radius: 50%;
            filter: blur(100px);
            opacity: 0.13;
            pointer-events: none;
            z-index: 0;
            animation: float 10s ease-in-out infinite;
        }
        body::before {
            width: 540px; height: 540px;
            background: radial-gradient(circle, #2563eb , transparent);
            top: -140px; left: -140px;
        }
        body::after {
            width: 440px; height: 440px;
            background: radial-gradient(circle, #0d9488, transparent);
            bottom: -120px; right: -120px;
            animation-delay: 5s;
        }
        @keyframes float {
            0%, 100% { transform: translate(0,0) scale(1); }
            50%       { transform: translate(18px, 18px) scale(1.04); }
        }

        /* ── Card wrapper ── */
        .page-wrapper {
            position: relative;
            z-index: 1;
            display: grid;
            grid-template-columns: 1fr 1fr;
            max-width: 920px;
            width: 100%;
            border-radius: 24px;
            overflow: hidden;
            border: 1px solid var(--border);
            box-shadow: var(--shadow-lg), 0 4px 24px rgba(0,0,0,0.10);
            animation: rise 0.55s cubic-bezier(0.22,1,0.36,1) both;
        }
        @keyframes rise {
            from { opacity: 0; transform: translateY(30px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ═══════════════════════════════
           LEFT PANEL — dark navy/purple
        ═══════════════════════════════ */
        .left-panel {
            background:
                radial-gradient(ellipse at 70% 10%, rgba(37,99,235,0.28) 0%, transparent 55%),
                radial-gradient(ellipse at 20% 90%, rgba(13,148,136,0.20) 0%, transparent 50%),
                linear-gradient(160deg, #0d0e1a 0%, #13122b 45%, #1a1040 100%);
            padding: 40px 36px;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
            min-height: 600px;
        }

        /* decorative rings */
        .deco-ring {
            position: absolute;
            border-radius: 50%;
            border: 1px solid rgba(37,99,235,0.12);
            pointer-events: none;
        }
        .deco-ring-1 { width: 340px; height: 340px; top: -90px; right: -90px; }
        .deco-ring-2 { width: 230px; height: 230px; top: -30px; right: -30px; border-color: rgba(37,99,235,0.08); }
        .deco-ring-3 { width: 280px; height: 280px; bottom: -70px; left: -70px; border-color: rgba(13,148,136,0.08); }
        .deco-blob {
            position: absolute;
            width: 160px; height: 160px;
            border-radius: 50%;
            background: rgba(37,99,235,0.07);
            bottom: 90px; right: -50px;
            pointer-events: none;
        }
        /* subtle grid overlay */
        .left-panel::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(37,99,235,0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(37,99,235,0.04) 1px, transparent 1px);
            background-size: 32px 32px;
            pointer-events: none;
            z-index: 0;
        }

        /* ── Brand ── */
        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            position: relative;
            z-index: 1;
            margin-bottom: 32px;
        }
        .brand-icon {
            width: 38px; height: 38px;
            background: rgba(37,99,235,0.18);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            border: 1px solid rgba(37,99,235,0.35);
        }
        /* DM MONO for brand name */
        .brand-name {
            font-family: var(--font-mono);
            font-size: 18px;
            font-weight: 500;
            color: white;
            letter-spacing: -0.01em;
        }

        /* ── Left heading block ── */
        .left-content {
            position: relative;
            z-index: 1;
            margin-bottom: 26px;
        }
        .left-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(37,99,235,0.14);
            border: 1px solid rgba(37,99,235,0.28);
            color: rgba(255,255,255,0.82);
            font-family: var(--font-mono);
            font-size: 10px;
            font-weight: 500;
            letter-spacing: 0.10em;
            text-transform: uppercase;
            padding: 5px 12px;
            border-radius: 99px;
            margin-bottom: 14px;
        }
        .tag-dot {
            width: 6px; height: 6px;
            border-radius: 50%;
            background: var(--green);
            flex-shrink: 0;
            animation: blink 2s ease-in-out infinite;
        }
        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.35; }
        }

        /* DM MONO headings */
        .left-heading {
            font-family: var(--font-mono);
            font-size: 30px;
            font-weight: 500;
            color: white;
            line-height: 1.22;
            margin-bottom: 11px;
            letter-spacing: -0.02em;
        }
        .left-heading .dim {
            color: rgba(255,255,255,0.42);
        }
        .left-sub {
            font-family: var(--font);
            font-size: 13px;
            color: rgba(255,255,255,0.52);
            line-height: 1.68;
        }

        /* ── Live activity ── */
        .activity-wrap {
            position: relative;
            z-index: 1;
            margin-bottom: 24px;
        }
        .live-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: rgba(126,255,196,0.10);
            border: 1px solid rgba(126,255,196,0.22);
            border-radius: 99px;
            padding: 3px 10px;
            font-family: var(--font-mono);
            font-size: 10px;
            font-weight: 500;
            color: var(--green);
            letter-spacing: 0.06em;
            margin-bottom: 9px;
            width: fit-content;
        }
        .live-dot {
            width: 5px; height: 5px;
            border-radius: 50%;
            background: var(--green);
            animation: blink 1.3s ease-in-out infinite;
        }
        .activity-card {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(37,99,235,0.18);
            border-radius: 14px;
            padding: 13px 15px;
            display: flex;
            flex-direction: column;
            gap: 9px;
        }
        .activity-item {
            display: flex;
            align-items: center;
            gap: 11px;
        }
        .activity-avatar {
            width: 32px; height: 32px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-family: var(--font-mono);
            font-size: 12px;
            font-weight: 500;
            color: white;
            flex-shrink: 0;
        }
        .activity-info { flex: 1; min-width: 0; }
        .activity-name {
            font-family: var(--font);
            font-size: 12.5px;
            font-weight: 600;
            color: rgba(255,255,255,0.88);
            line-height: 1.2;
        }
        .activity-desc {
            font-size: 11px;
            color: rgba(255,255,255,0.45);
        }
        .activity-amount {
            font-family: var(--font-mono);
            font-size: 12.5px;
            font-weight: 500;
            color: var(--green);
            flex-shrink: 0;
        }
        .activity-divider { height: 1px; background: rgba(37,99,235,0.12); margin: 0; }

        /* ── Trust list ── */
        .trust-list {
            position: relative;
            z-index: 1;
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-bottom: 24px;
        }
        .trust-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 12.5px;
            color: rgba(255,255,255,0.62);
            font-weight: 500;
        }
        .trust-icon {
            width: 30px; height: 30px;
            background: rgba(37,99,235,0.12);
            border: 1px solid rgba(37,99,235,0.22);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }

        /* ── Stats row ── */
        .stats-row {
            position: relative;
            z-index: 1;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
            margin-top: auto;
        }
        .stat-card {
            background: rgba(37,99,235,0.10);
            border: 1px solid rgba(37,99,235,0.20);
            border-radius: 11px;
            padding: 11px 8px;
            text-align: center;
        }
        .stat-num {
            font-family: var(--font-mono);
            font-size: 16px;
            font-weight: 500;
            color: white;
            line-height: 1;
            margin-bottom: 4px;
        }
        .stat-label {
            font-family: var(--font);
            font-size: 10px;
            color: rgba(255,255,255,0.45);
            font-weight: 500;
        }

        /* ═══════════════════════════════
           RIGHT PANEL — white form
        ═══════════════════════════════ */
        .right-panel {
            background: var(--surface);
            padding: 46px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            transition: background 0.3s ease;
        }

        .form-header { margin-bottom: 26px; }
        .form-title {
            font-family: var(--font-mono);
            font-size: 24px;
            font-weight: 500;
            color: var(--text);
            letter-spacing: -0.03em;
            margin-bottom: 5px;
        }
        .form-subtitle {
            font-family: var(--font);
            font-size: 13.5px;
            color: var(--muted);
        }

        /* Session status */
        .session-status {
            background: rgba(16,185,129,0.07);
            border: 1px solid rgba(16,185,129,0.22);
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 13px;
            color: #059669;
            font-weight: 500;
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Validation errors */
        .alert-errors {
            background: rgba(239,68,68,0.06);
            border: 1px solid rgba(239,68,68,0.18);
            border-radius: 10px;
            padding: 11px 14px;
            margin-bottom: 16px;
        }
        .alert-errors ul { list-style: none; display: flex; flex-direction: column; gap: 4px; }
        .alert-errors li {
            font-size: 12.5px; color: #dc2626;
            display: flex; align-items: center; gap: 5px;
        }
        .alert-errors li::before { content: '·'; font-weight: bold; font-size: 16px; line-height: 1; }

        /* Fields */
        .fields { display: flex; flex-direction: column; gap: 15px; }
        .field { display: flex; flex-direction: column; gap: 6px; }
        .field label {
            font-family: var(--font);
            font-size: 12.5px;
            font-weight: 600;
            color: var(--text);
        }

        .input-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }
        .input-wrap .ico {
            position: absolute;
            left: 13px; top: 50%;
            transform: translateY(-50%);
            color: var(--text3);
            pointer-events: none;
            transition: color 0.2s;
            width: 15px; height: 15px;
            z-index: 1;
        }
        .input-wrap input {
            width: 100%;
            height: 44px;
            padding: 0 13px 0 40px;
            font-family: var(--font);
            font-size: 13.5px;
            color: var(--text);
            background: var(--surface2);
            border: 1.5px solid var(--border2);
            border-radius: 10px;
            outline: none;
            transition: border-color 0.2s, background 0.2s, box-shadow 0.2s, color 0.3s ease;
        }
        .input-wrap.has-toggle input { padding-right: 42px; }
        .input-wrap input::placeholder { color: var(--text3); font-size: 13px; }
        .input-wrap input:focus {
            border-color: var(--accent);
            background: var(--surface);
            box-shadow: 0 0 0 3px var(--accent-glow);
        }
        .input-wrap:focus-within .ico { color: var(--accent); }

        .pwd-toggle {
            position: absolute;
            right: 11px; top: 50%;
            transform: translateY(-50%);
            background: none; border: none;
            cursor: pointer; color: var(--text3);
            padding: 4px;
            display: flex; align-items: center;
            z-index: 2;
            transition: color 0.2s;
        }
        .pwd-toggle:hover { color: var(--accent); }

        .field-error { font-size: 11.5px; color: var(--danger); }

        /* Remember + Forgot */
        .meta-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
        }
        .remember-label {
            display: flex;
            align-items: center;
            gap: 7px;
            cursor: pointer;
            font-size: 13px;
            color: var(--muted);
            font-weight: 500;
            user-select: none;
        }
        .remember-label input[type="checkbox"] {
            width: 14px; height: 14px;
            accent-color: var(--accent);
            cursor: pointer;
        }
        .forgot-link {
            font-size: 13px;
            font-weight: 600;
            color: var(--accent);
            text-decoration: none;
            transition: opacity 0.2s;
        }
        .forgot-link:hover { opacity: 0.72; text-decoration: underline; }

        /* Submit */
        .btn-login {
            width: 100%;
            height: 46px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-family: var(--font-mono);
            font-size: 13.5px;
            font-weight: 500;
            color: white;
            background: linear-gradient(135deg, var(--accent) 0%, var(--accent2) 100%);
            border: none;
            border-radius: 11px;
            cursor: pointer;
            transition: opacity 0.2s, transform 0.15s, box-shadow 0.2s;
            box-shadow: 0 4px 22px var(--accent-glow);
            letter-spacing: 0.01em;
        }
        .btn-login:hover {
            opacity: 0.91;
            transform: translateY(-1px);
            box-shadow: 0 8px 30px var(--accent-glow);
        }
        .btn-login:active { transform: translateY(0); }

        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(0,0,0,0.07);
        }
        .divider span { font-size: 11.5px; color: var(--text3); font-weight: 500; font-family: var(--font-mono); }

        /* Google */
        .btn-google {
            width: 100%;
            height: 43px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-family: var(--font);
            font-size: 13.5px;
            font-weight: 600;
            color: #333;
            background: white;
            border: 1.5px solid rgba(0,0,0,0.09);
            border-radius: 11px;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.2s, box-shadow 0.2s;
        }
        .btn-google:hover {
            background: #f9fafb;
            box-shadow: 0 2px 12px rgba(0,0,0,0.07);
        }

        /* Register link */
        .register-link {
            text-align: center;
            font-size: 13px;
            color: var(--muted);
        }
        .register-link a {
            color: var(--accent);
            font-weight: 600;
            text-decoration: none;
        }
        .register-link a:hover { text-decoration: underline; }

        /* Theme toggle */
        .theme-toggle {
            position: fixed;
            top: 18px; right: 18px;
            z-index: 10;
            width: 42px; height: 42px;
            display: flex; align-items: center; justify-content: center;
            border-radius: 50%;
            background: var(--surface);
            border: 1.5px solid var(--border2);
            color: var(--text);
            cursor: pointer;
            box-shadow: var(--shadow);
            transition: background 0.3s ease, color 0.3s ease, transform 0.2s ease, border-color 0.2s;
        }
        .theme-toggle:hover { transform: translateY(-1px); border-color: var(--accent); color: var(--accent); }
        .theme-toggle svg { width: 19px; height: 19px; }
        .theme-toggle .icon-moon { display: none; }
        [data-theme="dark"] .theme-toggle .icon-sun  { display: none; }
        [data-theme="dark"] .theme-toggle .icon-moon { display: block; }

        /* ── Responsive ── */
        @media (max-width: 720px) {
            .page-wrapper {
                grid-template-columns: 1fr;
                max-width: 430px;
                border-radius: 20px;
            }
            .left-panel { padding: 28px 24px; min-height: auto; }
            .left-heading { font-size: 24px; }
            .right-panel { padding: 34px 26px; }
        }
        @media (max-width: 400px) {
            .right-panel { padding: 28px 18px; }
            .left-panel  { padding: 24px 18px; }
        }
    </style>
</head>
<body>

<button type="button" class="theme-toggle" id="themeToggle" aria-label="Toggle dark mode" title="Toggle theme">
    <svg class="icon-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="4"/>
        <path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"/>
    </svg>
    <svg class="icon-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
    </svg>
</button>

<div class="page-wrapper">

    <!-- ══════════════════════════════
         LEFT PANEL
    ══════════════════════════════ -->
    <div class="left-panel">

        <div class="deco-ring deco-ring-1"></div>
        <div class="deco-ring deco-ring-2"></div>
        <div class="deco-ring deco-ring-3"></div>
        <div class="deco-blob"></div>

        <!-- Brand — DM Mono -->
        <a href="{{ route('home') }}" class="brand">
            <span class="brand-icon">
                <svg width="19" height="19" viewBox="0 0 24 24" fill="rgba(37,99,235,0.9)">
                    <path d="M12 21.593c-5.63-5.539-11-10.297-11-14.402C1 3.335 4.18 1 7.5 1c1.862 0 3.706.902 4.5 2.338C12.794 1.902 14.638 1 16.5 1 19.82 1 23 3.335 23 7.191c0 4.105-5.37 8.863-11 14.402z"/>
                </svg>
            </span>
            <span class="brand-name">DonateBazaar</span>
        </a>

        <!-- Heading — DM Mono -->
        <div class="left-content">
            <div class="left-tag">
                <span class="tag-dot"></span>
                Welcome back
            </div>
            <h1 class="left-heading">
                Good to See<br>
                <span class="dim">You Again</span>
            </h1>
            <p class="left-sub">Log back in and continue your journey of making a difference. Your campaigns are waiting for you.</p>
        </div>

        <!-- Live activity feed -->
        <div class="activity-wrap">
            <div class="live-badge">
                <span class="live-dot"></span>
                Live activity
            </div>
            <div class="activity-card">
                <div class="activity-item">
                    <div class="activity-avatar" style="background:linear-gradient(135deg,#f97316,#ef4444);">R</div>
                    <div class="activity-info">
                        <div class="activity-name">Rahul M.</div>
                        <div class="activity-desc">Donated to Child Education</div>
                    </div>
                    <div class="activity-amount">+₹500</div>
                </div>
                <div class="activity-divider"></div>
                <div class="activity-item">
                    <div class="activity-avatar" style="background:linear-gradient(135deg,#06b6d4,#3b82f6);">P</div>
                    <div class="activity-info">
                        <div class="activity-name">Priya S.</div>
                        <div class="activity-desc">Supported Flood Relief Fund</div>
                    </div>
                    <div class="activity-amount">+₹1,200</div>
                </div>
                <div class="activity-divider"></div>
                <div class="activity-item">
                    <div class="activity-avatar" style="background:linear-gradient(135deg,#2563eb ,#ec4899);">A</div>
                    <div class="activity-info">
                        <div class="activity-name">Amit K.</div>
                        <div class="activity-desc">Funded Animal Rescue Drive</div>
                    </div>
                    <div class="activity-amount">+₹750</div>
                </div>
            </div>
        </div>

        <!-- Trust signals -->
        <ul class="trust-list">
            <li class="trust-item">
                <span class="trust-icon">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="rgba(37,99,235,0.85)" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    </svg>
                </span>
                100% secure &amp; bank-grade encrypted
            </li>
            <li class="trust-item">
                <span class="trust-icon">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="rgba(37,99,235,0.85)" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                    </svg>
                </span>
                2.5M+ donors already trust us
            </li>
            <li class="trust-item">
                <span class="trust-icon">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="rgba(37,99,235,0.85)" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                    </svg>
                </span>
                ₹50Cr+ raised for good causes
            </li>
        </ul>

        <!-- Stats — DM Mono numbers -->
        <div class="stats-row">
            <div class="stat-card">
                <div class="stat-num">2.5M+</div>
                <div class="stat-label">Donors</div>
            </div>
            <div class="stat-card">
                <div class="stat-num">10K+</div>
                <div class="stat-label">Campaigns</div>
            </div>
            <div class="stat-card">
                <div class="stat-num">₹50Cr+</div>
                <div class="stat-label">Raised</div>
            </div>
        </div>

    </div><!-- /left-panel -->

    <!-- ══════════════════════════════
         RIGHT PANEL
    ══════════════════════════════ -->
    <div class="right-panel">

        <div class="form-header">
            <h2 class="form-title">Welcome back</h2>
            <p class="form-subtitle">Sign in to your DonateBazaar account</p>
        </div>

        {{-- Session Status --}}
        @if (session('status'))
            <div class="session-status">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
                </svg>
                {{ session('status') }}
            </div>
        @endif

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="alert-errors">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="fields">

                {{-- Email --}}
                <div class="field">
                    <label for="email">Email Address</label>
                    <div class="input-wrap">
                        <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/>
                        </svg>
                        <input type="email" id="email" name="email"
                               value="{{ old('email') }}"
                               placeholder="rahul@example.com"
                               required autofocus autocomplete="username">
                    </div>
                    @error('email')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="field">
                    <label for="password">Password</label>
                    <div class="input-wrap has-toggle">
                        <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                        <input type="password" id="password" name="password"
                               placeholder="Enter your password"
                               required minlength="8" autocomplete="current-password">
                        <button type="button" class="pwd-toggle" onclick="togglePwd('password',this)" aria-label="Show password" aria-pressed="false">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Remember + Forgot --}}
                <div class="meta-row">
                    <label class="remember-label" for="remember_me">
                        <input type="checkbox" id="remember_me" name="remember">
                        Remember me
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-link">Forgot password?</a>
                    @endif
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn-login">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/>
                    </svg>
                    Log In to Your Account
                </button>

                <div class="divider"><span>or</span></div>

                {{-- Google OAuth --}}
                <a href="{{ route('auth.google') }}" class="btn-google">
                    <svg width="17" height="17" viewBox="0 0 24 24">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                    </svg>
                    Continue with Google
                </a>

                {{-- Phone OTP --}}
                <a href="{{ route('otp.login') }}" class="btn-google" style="margin-top:10px;">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#555" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="6" y="2" width="12" height="20" rx="2"/>
                        <line x1="11" y1="18" x2="13" y2="18"/>
                    </svg>
                    Continue with Phone
                </a>

                <p class="register-link">
                    Don't have an account? <a href="{{ route('register') }}">Create one free</a>
                </p>

            </div>
        </form>

    </div><!-- /right-panel -->

</div><!-- /page-wrapper -->

<script>
// Theme toggle (persisted, mirrors site [data-theme] convention)
(function () {
    var btn = document.getElementById('themeToggle');
    if (!btn) return;
    btn.addEventListener('click', function () {
        var root = document.documentElement;
        var next = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
        root.setAttribute('data-theme', next);
        try { localStorage.setItem('theme', next); } catch (e) {}
    });
})();

function togglePwd(fieldId, btn) {
    const input = document.getElementById(fieldId);
    const isText = input.type === 'text';
    input.type = isText ? 'password' : 'text';
    btn.setAttribute('aria-label', isText ? 'Hide password' : 'Show password');
    btn.setAttribute('aria-pressed', isText ? 'true' : 'false');
    btn.innerHTML = isText
        ? `<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>`
        : `<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>`;
    btn.style.color = isText ? '#2563eb ' : '';
}

// Prevent double submission on slow networks



document.querySelector('form').addEventListener('submit', function () {
    
    
    
    const btn = this.querySelector('button[type="submit"]');
    if (btn.dataset.submitted === 'true') {
        event?.preventDefault?.();
        return;
    }
    btn.dataset.submitted = 'true';
    btn.disabled = true;
    btn.style.opacity = '0.7';
    btn.style.cursor = 'not-allowed';


});




</script>
</body>
</html>