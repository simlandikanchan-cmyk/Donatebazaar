<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Careers – DonateBazaar</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,700&family=Outfit:wght@300;400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/public/job-posts-index.css'])
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