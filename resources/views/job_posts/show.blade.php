{{-- resources/views/job_posts/show.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $jobPost->title }} – DonateBazaar Careers</title>
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
        }

        html { scroll-behavior: smooth; }

        body {
            background: var(--paper);
            color: var(--ink);
            font-family: 'Outfit', sans-serif;
            font-weight: 300;
            min-height: 100vh;
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
        .nav-brand {
            font-family: 'Cormorant Garamond', serif;
            font-weight: 700; font-size: 1.4rem;
            color: var(--ink); letter-spacing: -0.01em;
        }
        .nav-brand span { color: var(--accent); }
        .nav-right { display: flex; align-items: center; gap: 2rem; }
        .back-link {
            font-size: 0.82rem; font-weight: 500;
            color: var(--muted);
            display: flex; align-items: center; gap: 0.5rem;
            letter-spacing: 0.04em;
            transition: color .2s;
        }
        .back-link:hover { color: var(--ink); }
        .nav-cta {
            font-size: 0.82rem; font-weight: 500;
            padding: 0.5rem 1.2rem;
            background: var(--ink); color: var(--white);
            border-radius: 2px; letter-spacing: 0.04em;
            transition: background .2s;
        }
        .nav-cta:hover { background: var(--accent); }

        /* ─── HERO BANNER ─────────────────────────── */
        .job-hero {
            background: var(--ink);
            padding: clamp(3rem, 7vw, 5rem) clamp(1.5rem, 5vw, 5rem);
            position: relative;
            overflow: hidden;
        }
        .job-hero::before {
            content: '';
            position: absolute; inset: 0;
            background: radial-gradient(ellipse 50% 80% at 100% 50%, rgba(200,75,47,.18) 0%, transparent 70%);
            pointer-events: none;
        }
        .job-hero-inner {
            max-width: 1100px; margin: 0 auto;
            position: relative; z-index: 1;
        }
        .hero-eyebrow {
            font-size: 0.72rem; font-weight: 600;
            letter-spacing: 0.18em; text-transform: uppercase;
            color: var(--accent2);
            margin-bottom: 1.25rem;
            display: flex; align-items: center; gap: 0.75rem;
        }
        .hero-eyebrow::before {
            content: '';
            display: inline-block; width: 28px; height: 1px;
            background: var(--accent2);
        }
        .job-hero h1 {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(2.2rem, 5vw, 4rem);
            font-weight: 700; line-height: 1.0;
            letter-spacing: -0.03em;
            color: var(--white);
            margin-bottom: 1.75rem;
        }
        .job-hero h1 em { font-style: italic; color: var(--accent2); }
        .meta-row {
            display: flex; flex-wrap: wrap;
            gap: 0.5rem 1.5rem;
            margin-bottom: 2rem;
        }
        .meta-chip {
            display: inline-flex; align-items: center; gap: 0.4rem;
            font-size: 0.85rem; color: rgba(255,255,255,.6);
        }
        .meta-chip svg { opacity: 0.7; flex-shrink: 0; }
        .badge {
            display: inline-flex; align-items: center;
            padding: 0.3rem 0.85rem;
            border-radius: 100px;
            font-size: 0.7rem; font-weight: 600;
            letter-spacing: 0.07em; text-transform: uppercase;
        }
        .badge-type {
            background: rgba(255,255,255,.1);
            color: rgba(255,255,255,.8);
            border: 1px solid rgba(255,255,255,.15);
        }
        .badge-remote {
            background: rgba(200,75,47,.2);
            color: var(--accent2);
            border: 1px solid rgba(200,75,47,.3);
        }
        .hero-actions { display: flex; gap: 1rem; flex-wrap: wrap; align-items: center; }
        .btn-apply {
            display: inline-flex; align-items: center; gap: 0.5rem;
            padding: 0.85rem 2rem;
            background: var(--accent); color: var(--white);
            font-family: 'Outfit', sans-serif;
            font-size: 0.85rem; font-weight: 500;
            letter-spacing: 0.04em; border-radius: 2px;
            transition: background .2s, transform .15s;
        }
        .btn-apply:hover { background: #a83a20; transform: translateY(-2px); }
        .btn-share {
            display: inline-flex; align-items: center; gap: 0.5rem;
            padding: 0.85rem 1.5rem;
            background: transparent; color: rgba(255,255,255,.6);
            font-family: 'Outfit', sans-serif;
            font-size: 0.85rem; font-weight: 500;
            letter-spacing: 0.04em; border-radius: 2px;
            border: 1px solid rgba(255,255,255,.15);
            transition: all .2s; cursor: pointer;
        }
        .btn-share:hover { border-color: rgba(255,255,255,.4); color: var(--white); }

        /* ─── DEADLINE STRIP ──────────────────────── */
        .deadline-strip {
            background: rgba(200,75,47,.12);
            border-top: 1px solid rgba(200,75,47,.2);
            border-bottom: 1px solid rgba(200,75,47,.2);
            padding: 0.75rem clamp(1.5rem, 5vw, 5rem);
            display: flex; align-items: center; gap: 0.6rem;
            font-size: 0.82rem; font-weight: 500;
            color: var(--accent);
        }
        .deadline-strip.no-urgency {
            background: rgba(13,12,11,.03);
            border-color: var(--border);
            color: var(--muted);
        }
        .deadline-strip svg { flex-shrink: 0; }

        /* ─── PAGE LAYOUT ─────────────────────────── */
        .page {
            max-width: 1100px; margin: 0 auto;
            padding: clamp(2.5rem, 5vw, 4rem) clamp(1.5rem, 5vw, 2rem);
            display: grid;
            grid-template-columns: 1fr 340px;
            gap: 3rem;
            align-items: start;
        }

        /* ─── MAIN CONTENT ────────────────────────── */
        .content-section {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 4px;
            padding: 2.5rem;
            margin-bottom: 1.5rem;
        }
        .content-section-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.4rem; font-weight: 700;
            color: var(--ink);
            margin-bottom: 1.25rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--warm);
            display: flex; align-items: center; gap: 0.75rem;
        }
        .content-section-title .title-dot {
            width: 6px; height: 6px;
            border-radius: 50%;
            background: var(--accent);
            flex-shrink: 0;
        }
        .content p {
            font-size: 0.95rem;
            line-height: 1.85;
            color: #3a3530;
            margin-bottom: 0.9rem;
        }
        .content p:last-child { margin-bottom: 0; }
        .content ul, .content ol {
            padding-left: 1.5rem;
            margin-bottom: 0.9rem;
        }
        .content li {
            font-size: 0.95rem;
            line-height: 1.75;
            color: #3a3530;
            margin-bottom: 0.35rem;
        }
        .content strong { font-weight: 600; color: var(--ink); }

        /* ─── SHARE SECTION ───────────────────────── */
        .share-section {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 4px;
            padding: 1.5rem 2.5rem;
            display: flex; align-items: center;
            justify-content: space-between; gap: 1.5rem;
            flex-wrap: wrap;
        }
        .share-text {
            font-size: 0.9rem; font-weight: 500; color: var(--ink);
        }
        .share-text span { color: var(--muted); font-weight: 300; font-size: 0.82rem; display: block; margin-top: 2px; }
        .share-btns { display: flex; gap: 0.5rem; flex-wrap: wrap; }
        .share-btn {
            display: inline-flex; align-items: center; gap: 0.4rem;
            padding: 0.5rem 1rem;
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 2px;
            font-family: 'Outfit', sans-serif;
            font-size: 0.78rem; font-weight: 500;
            color: var(--muted); cursor: pointer;
            transition: all .15s;
        }
        .share-btn:hover { border-color: var(--ink); color: var(--ink); }

        /* ─── SIDEBAR ─────────────────────────────── */
        .sidebar { position: sticky; top: 84px; }

        .sidebar-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 4px;
            padding: 1.75rem;
            margin-bottom: 1.25rem;
        }
        .sidebar-card-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.15rem; font-weight: 700;
            color: var(--ink);
            margin-bottom: 1.25rem;
            padding-bottom: 0.85rem;
            border-bottom: 1px solid var(--warm);
        }
        .detail-row {
            display: flex; justify-content: space-between; align-items: center;
            padding: 0.65rem 0;
            border-bottom: 1px solid var(--warm);
            font-size: 0.85rem; gap: 0.5rem;
        }
        .detail-row:last-child { border-bottom: none; padding-bottom: 0; }
        .detail-label { color: var(--muted); flex-shrink: 0; }
        .detail-val { font-weight: 500; text-align: right; }
        .detail-val.green { color: #2d6a4f; }
        .detail-val.accent { color: var(--accent); }

        /* ─── APPLY CARD ──────────────────────────── */
        .apply-card {
            background: var(--ink);
            color: var(--white);
            border-radius: 4px;
            padding: 1.75rem;
            margin-bottom: 1.25rem;
            position: relative;
            overflow: hidden;
        }
        .apply-card::before {
            content: '';
            position: absolute; top: 0; right: 0;
            width: 120px; height: 120px;
            background: radial-gradient(circle, rgba(200,75,47,.25) 0%, transparent 70%);
            pointer-events: none;
        }
        .apply-card-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.25rem; font-weight: 700;
            color: var(--white); margin-bottom: 0.3rem;
        }
        .apply-card-sub {
            font-size: 0.8rem;
            color: rgba(255,255,255,.45);
            margin-bottom: 1.5rem;
            line-height: 1.5;
        }

        .flash { padding: 0.85rem 1rem; border-radius: 3px; font-size: 0.85rem; margin-bottom: 1rem; }
        .flash-success { background: rgba(255,255,255,.08); color: #6ee7b7; border: 1px solid rgba(110,231,183,.2); }
        .flash-error   { background: rgba(200,75,47,.15); color: #fca5a5; border: 1px solid rgba(200,75,47,.25); }

        .field { margin-bottom: 1rem; }
        .field label {
            display: block; font-size: 0.72rem; font-weight: 600;
            letter-spacing: 0.1em; text-transform: uppercase;
            color: rgba(255,255,255,.45); margin-bottom: 0.45rem;
        }
        .field input,
        .field textarea {
            width: 100%; padding: 0.65rem 0.85rem;
            background: rgba(255,255,255,.06);
            border: 1px solid rgba(255,255,255,.1);
            border-radius: 2px;
            color: var(--white);
            font-family: 'Outfit', sans-serif; font-size: 0.875rem;
            outline: none; transition: border-color .2s;
        }
        .field input:focus,
        .field textarea:focus { border-color: var(--accent2); background: rgba(255,255,255,.08); }
        .field input::placeholder,
        .field textarea::placeholder { color: rgba(255,255,255,.2); }
        .field textarea { resize: vertical; min-height: 90px; line-height: 1.6; }
        .field input[type="file"] {
            cursor: pointer; font-size: 0.8rem;
            color: rgba(255,255,255,.5);
            padding: 0.5rem 0.75rem;
        }
        .field-error { font-size: 0.75rem; color: #fca5a5; margin-top: 0.35rem; }

        .submit-btn {
            width: 100%; padding: 0.9rem;
            background: var(--accent); color: var(--white);
            border: none; border-radius: 2px;
            font-family: 'Outfit', sans-serif; font-size: 0.875rem; font-weight: 500;
            cursor: pointer; letter-spacing: 0.05em;
            transition: background .2s, transform .15s;
            margin-top: 0.25rem;
            display: flex; align-items: center; justify-content: center; gap: 0.5rem;
        }
        .submit-btn:hover { background: #a83a20; transform: translateY(-1px); }
        .submit-btn:active { transform: translateY(0); }

        /* ─── SIMILAR ROLES ───────────────────────── */
        .similar-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 4px;
            padding: 1.5rem;
        }
        .similar-title {
            font-size: 0.72rem; font-weight: 600;
            letter-spacing: 0.12em; text-transform: uppercase;
            color: var(--muted); margin-bottom: 1rem;
        }
        .similar-link {
            display: flex; align-items: center; justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px solid var(--warm);
            font-size: 0.875rem; font-weight: 500;
            color: var(--ink); gap: 0.5rem;
            transition: color .15s;
        }
        .similar-link:last-child { border-bottom: none; padding-bottom: 0; }
        .similar-link:hover { color: var(--accent); }
        .similar-link svg { flex-shrink: 0; opacity: 0.4; transition: opacity .15s; }
        .similar-link:hover svg { opacity: 1; }
        .similar-meta { font-size: 0.75rem; color: var(--muted); font-weight: 300; margin-top: 1px; }

        /* ─── FOOTER ──────────────────────────────── */
        footer {
            background: var(--ink);
            padding: 2.5rem clamp(1.5rem, 5vw, 5rem);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1.5rem;
            margin-top: 4rem;
        }
        .footer-brand {
            font-family: 'Cormorant Garamond', serif;
            font-weight: 700; font-size: 1.25rem;
            color: var(--white);
        }
        .footer-brand span { color: var(--accent2); }
        .footer-links { display: flex; gap: 2rem; flex-wrap: wrap; }
        .footer-links a {
            font-size: 0.8rem; color: rgba(255,255,255,.4);
            letter-spacing: 0.04em; transition: color .2s;
        }
        .footer-links a:hover { color: var(--white); }
        .footer-copy { font-size: 0.78rem; color: rgba(255,255,255,.28); }

        /* ─── ANIMATIONS ──────────────────────────── */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .content-section { animation: fadeUp .4s ease both; }
        .content-section:nth-child(2) { animation-delay: .06s; }
        .content-section:nth-child(3) { animation-delay: .12s; }

        /* ─── RESPONSIVE ──────────────────────────── */
        @media (max-width: 820px) {
            .page { grid-template-columns: 1fr; }
            .sidebar { position: static; order: -1; }
            .nav-right .back-link span { display: none; }
        }
        @media (max-width: 540px) {
            .share-section { flex-direction: column; align-items: flex-start; }
            .hero-actions { flex-direction: column; align-items: flex-start; }
        }
    </style>
</head>
<body>

{{-- NAV --}}
<nav>
    <a href="/" class="nav-brand">Donate<span>Bazaar</span></a>
    <div class="nav-right">
        <a href="{{ url('/career') }}" class="back-link">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path d="M19 12H5M12 19l-7-7 7-7"/>
            </svg>
            <span>All openings</span>
        </a>
        <a href="{{ url('/career') }}" class="nav-cta">View All Roles</a>
    </div>
</nav>

{{-- JOB HERO --}}
<section class="job-hero">
    <div class="job-hero-inner">
        <p class="hero-eyebrow">{{ ucfirst($jobPost->type) }} Position</p>
        <h1>{{ $jobPost->title }}</h1>

        <div class="meta-row">
            <span class="badge badge-type">{{ ucfirst($jobPost->type) }}</span>

            @if($jobPost->is_remote)
            <span class="badge badge-remote">Remote</span>
            @endif

            @if($jobPost->location)
            <span class="meta-chip">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                    <circle cx="12" cy="10" r="3"/>
                </svg>
                {{ $jobPost->location }}
            </span>
            @endif

            @if($jobPost->salary)
            <span class="meta-chip">
                <!-- <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="12" y1="1" x2="12" y2="23"/>
                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                </svg> -->
                ₹{{ $jobPost->salary }} / Anumn
            </span>
            @endif

            @if($jobPost->published_at)
            <span class="meta-chip">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/>
                    <line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                </svg>
                Posted {{ $jobPost->published_at->diffForHumans() }}
            </span>
            @endif
        </div>

        <div class="hero-actions">
            <a href="#apply-form" class="btn-apply">
                Apply Now
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M5 12h14M12 5l7 7-7 7"/>
                </svg>
            </a>
            <button type="button" class="btn-share" onclick="copyLink()">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/>
                    <polyline points="16 6 12 2 8 6"/><line x1="12" y1="2" x2="12" y2="15"/>
                </svg>
                Share Role
            </button>
        </div>
    </div>
</section>

{{-- DEADLINE STRIP --}}
@if($jobPost->application_deadline)
    @php $daysLeft = now()->diffInDays($jobPost->application_deadline, false); @endphp
    <div class="deadline-strip {{ $daysLeft > 7 ? 'no-urgency' : '' }}">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
        </svg>
        @if($daysLeft < 0)
            Application deadline has passed.
        @elseif($daysLeft === 0)
            Applications close today — apply now!
        @elseif($daysLeft <= 7)
            Only {{ $daysLeft }} day{{ $daysLeft !== 1 ? 's' : '' }} left to apply. Deadline: {{ $jobPost->application_deadline->format('d M Y') }}
        @else
            Application deadline: {{ $jobPost->application_deadline->format('d M Y') }} ({{ $daysLeft }} days remaining)
        @endif
    </div>
@endif

{{-- PAGE BODY --}}
<div class="page">

    {{-- ── MAIN ── --}}
    <main>

        {{-- Job Description --}}
        <div class="content-section">
            <div class="content-section-title">
                <span class="title-dot"></span>
                About This Role
            </div>
            <div class="content">
                {!! nl2br(e($jobPost->description)) !!}
            </div>
        </div>

        {{-- Share --}}
        <div class="share-section">
            <div class="share-text">
                Know someone perfect for this?
                <span>Help us find great people — share this role.</span>
            </div>
            <div class="share-btns">
                <button class="share-btn" onclick="copyLink()">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/>
                        <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/>
                    </svg>
                    Copy Link
                </button>
                <a class="share-btn"
                   href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(request()->url()) }}"
                   target="_blank" rel="noopener">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/>
                        <rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/>
                    </svg>
                    LinkedIn
                </a>
                <a class="share-btn"
                   href="https://twitter.com/intent/tweet?text={{ urlencode('Hiring: '.$jobPost->title.' at DonateBazaar') }}&url={{ urlencode(request()->url()) }}"
                   target="_blank" rel="noopener">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"/>
                    </svg>
                    Twitter
                </a>
            </div>
        </div>
    </main>

    {{-- ── SIDEBAR ── --}}
    <aside class="sidebar">

        {{-- Position Details --}}
        <div class="sidebar-card">
            <div class="sidebar-card-title">Position Details</div>

            <div class="detail-row">
                <span class="detail-label">Job Type</span>
                <span class="detail-val">{{ ucfirst($jobPost->type) }}</span>
            </div>

            @if($jobPost->location)
            <div class="detail-row">
                <span class="detail-label">Location</span>
                <span class="detail-val">{{ $jobPost->location }}</span>
            </div>
            @endif

            <div class="detail-row">
                <span class="detail-label">Remote</span>
                <span class="detail-val {{ $jobPost->is_remote ? 'green' : '' }}">
                    {{ $jobPost->is_remote ? 'Yes — Remote OK' : 'On-site' }}
                </span>
            </div>

            @if($jobPost->salary)
            <div class="detail-row">
                <span class="detail-label">Salary (₹)</span>
                <span class="detail-val">{{ $jobPost->salary }} / Anumn</span>
            </div>
            @endif

            @if($jobPost->application_deadline)
            <div class="detail-row">
                <span class="detail-label">Deadline</span>
                <span class="detail-val {{ $daysLeft <= 7 && $daysLeft >= 0 ? 'accent' : '' }}">
                    {{ $jobPost->application_deadline->format('d M Y') }}
                </span>
            </div>
            @endif

            @if($jobPost->published_at)
            <div class="detail-row">
                <span class="detail-label">Posted</span>
                <span class="detail-val">{{ $jobPost->published_at->format('d M Y') }}</span>
            </div>
            @endif
        </div>

        {{-- Apply Form --}}
        <div class="apply-card" id="apply-form">
            <div class="apply-card-title">Apply For This Role</div>
            <p class="apply-card-sub">CV in PDF or DOC format, max 5 MB. We respond to every application within 5 business days.</p>

            @if(session('success'))
                <div class="flash flash-success">
                    ✓ {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="flash flash-error">Please fix the errors below.</div>
            @endif

            <form method="POST"
                  action="{{ route('job_posts.apply', $jobPost->slug) }}"
                  enctype="multipart/form-data">
                @csrf

                <div class="field">
                    <label for="name">Full name *</label>
                    <input type="text" id="name" name="name"
                           placeholder="Enter Your Name"
                           value="{{ old('name') }}" required>
                    @error('name')<p class="field-error">{{ $message }}</p>@enderror
                </div>

                <div class="field">
                    <label for="email">Email address *</label>
                    <input type="email" id="email" name="email"
                           placeholder="Enter Your email Id"
                           value="{{ old('email') }}" required>
                    @error('email')<p class="field-error">{{ $message }}</p>@enderror
                </div>

                <div class="field">
                    <label for="phone">Phone number</label>
                    <input type="tel" id="phone" name="phone"
                           placeholder="Enter Your Phone Number"
                           value="{{ old('phone') }}">
                    @error('phone')<p class="field-error">{{ $message }}</p>@enderror
                </div>

                <div class="field">
                    <label for="cover_letter">Cover letter</label>
                    <textarea id="cover_letter" name="cover_letter"
    placeholder="Write a short cover letter highlighting your experience, skills, and why you're the right fit for this role...">{{ old('cover_letter') }}</textarea>
                    @error('cover_letter')<p class="field-error">{{ $message }}</p>@enderror
                </div>

                <div class="field">
                    <label for="cv">Upload CV *</label>
                    <input type="file" id="cv" name="cv"
                           accept=".pdf,.doc,.docx" required>
                    @error('cv')<p class="field-error">{{ $message }}</p>@enderror
                </div>

                <button type="submit" class="submit-btn">
                    Submit Application
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                </button>
            </form>
        </div>

        {{-- Back to all roles --}}
        <div class="similar-card">
            <div class="similar-title">Explore more roles</div>
            <a href="{{ url('/career') }}" class="similar-link">
                <div>
                    View all open positions
                    <div class="similar-meta">DonateBazaar Careers</div>
                </div>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M5 12h14M12 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

    </aside>
</div>

{{-- FOOTER --}}
<footer>
    <div class="footer-brand">Donate<span>Bazaar</span></div>
    <div class="footer-links">
        <a href="/">Home</a>
        <a href="/about">About</a>
        <a href="{{ url('/career') }}">Careers</a>
        <a href="/contact">Contact</a>
    </div>
    <div class="footer-copy">© {{ date('Y') }} DonateBazaar. All rights reserved.</div>
</footer>

<script>
function copyLink() {
    navigator.clipboard.writeText(window.location.href).then(function() {
        var btn = document.querySelector('.btn-share');
        if (btn) {
            var orig = btn.innerHTML;
            btn.innerHTML = '✓ Copied!';
            setTimeout(function() { btn.innerHTML = orig; }, 2000);
        }
    });
}

</script>
</body>
</html>