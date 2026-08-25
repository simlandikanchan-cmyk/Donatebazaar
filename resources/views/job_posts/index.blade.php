<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Careers – DonateBazaar</title>
    <meta name="description" content="Build your future. Change the world. Explore open roles at DonateBazaar — a remote-first team building the future of giving.">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="DonateBazaar Careers">
    <meta property="og:title" content="Careers at DonateBazaar">
    <meta property="og:description" content="Build your future. Change the world. Explore open roles at DonateBazaar — a remote-first team building the future of giving.">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta name="twitter:card" content="summary">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/public/job-posts-index.css'])
</head>
<body>
<script nonce="{{ request()->get('csp_nonce') }}">document.body.classList.add('js');</script>

<div class="scroll-progress" id="scrollProgress" aria-hidden="true"></div>

{{-- NAV --}}
<header class="topbar">
    <a href="/" class="topbar-brand">Donate<span>Bazaar</span><small class="brand-tag">.careers</small></a>
    <nav class="topbar-nav">
        <a href="#why" class="nav-link">Why Us</a>
        <a href="#impact" class="nav-link">Impact</a>
        <a href="#growth" class="nav-link">Growth</a>
        <a href="#teams" class="nav-link">Teams</a>
        <a href="#process" class="nav-link">How We Hire</a>
        <a href="#life" class="nav-link">Life</a>
        <a href="#faq" class="nav-link">FAQ</a>
        <a href="#open-roles" class="nav-link">Open Roles</a>
    </nav>
    <a href="/" class="nav-cta">← Back to Home</a>
    <button type="button" class="nav-toggle" id="navToggle" aria-label="Toggle menu" aria-expanded="false" aria-controls="mobileNav">
        <svg class="nav-toggle-open" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
        <svg class="nav-toggle-close" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M18 6 6 18M6 6l12 12"/></svg>
    </button>
</header>

<div class="mobile-nav" id="mobileNav">
    <a href="#why" class="mobile-link">Why Us</a>
    <a href="#impact" class="mobile-link">Impact</a>
    <a href="#growth" class="mobile-link">Growth</a>
    <a href="#teams" class="mobile-link">Teams</a>
    <a href="#process" class="mobile-link">How We Hire</a>
    <a href="#life" class="mobile-link">Life</a>
    <a href="#faq" class="mobile-link">FAQ</a>
    <a href="#open-roles" class="mobile-link">Open Roles</a>
    <a href="/" class="mobile-cta">← Back to Home</a>
</div>

{{-- HERO --}}
<section class="hero">
    <div class="hero-bg" aria-hidden="true"></div>
    <div class="hero-inner">
        <p class="hero-eyebrow">Careers at DonateBazaar</p>
        <h1>Build your future.<br><span>Change the world.</span></h1>
        <p class="hero-sub">We're building the future of giving — a platform where every line of code helps someone, somewhere. Join a team driven by purpose, not just profit.</p>

        <form method="GET" action="{{ url('/career') }}" class="hero-search" id="heroSearchForm">
            <svg class="hero-search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
            </svg>
            <input type="search" name="search" id="heroSearch" class="hero-search-input"
                placeholder="Search open roles by title, skill, or location…"
                value="{{ request('search', '') }}" autocomplete="off" aria-label="Search open roles">
            <button type="submit" class="hero-search-btn">
                Search Jobs
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </button>
        </form>

        @if($departments->isNotEmpty())
        <div class="hero-chips" aria-label="Browse by team">
            @foreach($departments as $i => $d)
            <a href="{{ url('/career?department=' . urlencode($d)) }}" class="hero-chip">
                {{ $d }}
                <span class="hero-chip-count">{{ $deptCounts[$d] ?? 0 }}</span>
            </a>
            @endforeach
            <a href="#open-roles" class="hero-chip hero-chip-all">View all {{ $openCount }} roles →</a>
        </div>
        @endif

        <div class="hero-stats">
            <div class="hero-stat">
                <div class="hero-stat-val"><span class="stat-count" data-count="{{ $openCount }}" data-suffix="+">0</span></div>
                <div class="hero-stat-label">Open Roles</div>
            </div>
            <div class="hero-stat">
                <div class="hero-stat-val"><span class="stat-count" data-count="{{ $remoteCount }}">0</span></div>
                <div class="hero-stat-label">Remote-Friendly</div>
            </div>
            <div class="hero-stat">
                <div class="hero-stat-val"><span class="stat-count" data-count="{{ $departments->count() }}">0</span></div>
                <div class="hero-stat-label">Departments</div>
            </div>
            <div class="hero-stat">
                <div class="hero-stat-val"><span class="stat-count" data-count="24" data-suffix="h">0</span></div>
                <div class="hero-stat-label">Interview Feedback</div>
            </div>
        </div>
    </div>
</section>

{{-- IMPACT --}}
<section class="impact-section" id="impact">
    <div class="container impact-inner">
        <div class="impact-head">
            <p class="section-eyebrow">The impact you'll drive</p>
            <h2 class="section-title">Your work moves<br>real numbers.</h2>
        </div>
        <div class="impact-stats">
            <div class="impact-stat">
                <div class="impact-val"><span class="stat-count" data-count="1200" data-suffix="+">0</span></div>
                <div class="impact-label">NGOs Fundraising</div>
            </div>
            <div class="impact-stat">
                <div class="impact-val"><span class="stat-count" data-count="85000" data-suffix="+">0</span></div>
                <div class="impact-label">Donors & Volunteers</div>
            </div>
            <div class="impact-stat">
                <div class="impact-val"><span class="impact-prefix">₹</span><span class="stat-count" data-count="45" data-suffix="Cr+">0</span></div>
                <div class="impact-label">Funds Raised</div>
            </div>
            <div class="impact-stat">
                <div class="impact-val"><span class="stat-count" data-count="28">0</span></div>
                <div class="impact-label">States Reached</div>
            </div>
        </div>
    </div>
</section>

{{-- WHY US --}}
<section class="why-section" id="why">
    <div class="container">
        <div class="section-header">
            <div>
                <p class="section-eyebrow">Why DonateBazaar</p>
                <h2 class="section-title">Built different,<br>built for good.</h2>
            </div>
            <a href="#open-roles" class="section-link">Browse open roles →</a>
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
    </div>
</section>

{{-- GROWTH --}}
<section class="growth-section" id="growth">
    <div class="container">
        <div class="section-header">
            <div>
                <p class="section-eyebrow">Career growth</p>
                <h2 class="section-title">Grow with<br>DonateBazaar.</h2>
            </div>
            <a href="#open-roles" class="section-link">Find your starting point →</a>
        </div>

        <div class="growth-ladder">
            @php
            $levels = [
                ['num' => '01', 'title' => 'Internship', 'time' => '3–6 months', 'desc' => 'Paid internships with real ownership from day one. Ship, learn, and get mentored by senior engineers and designers.'],
                ['num' => '02', 'title' => 'Junior', 'time' => '0–2 years', 'desc' => 'Build features end-to-end with a dedicated mentor. Weekly 1:1s, a ₹50K learning budget, and a clear growth plan.'],
                ['num' => '03', 'title' => 'Senior', 'time' => '3–5 years', 'desc' => 'Own major initiatives, mentor juniors, and lead technical direction. Your opinions shape the roadmap.'],
                ['num' => '04', 'title' => 'Lead', 'time' => '5–7 years', 'desc' => 'Lead a squad or practice area. Drive cross-team decisions and help hire the next generation of leaders.'],
                ['num' => '05', 'title' => 'Head', 'time' => '7+ years', 'desc' => 'Own a department, define strategy, and scale impact. Leadership paths into founder-adjacent roles exist too.'],
            ];
            @endphp
            <div class="growth-track" aria-hidden="true"></div>
            @foreach($levels as $lv)
            <div class="growth-level">
                <div class="growth-node">
                    <span class="growth-node-num">{{ $lv['num'] }}</span>
                </div>
                <div class="growth-card">
                    <div class="growth-card-top">
                        <div class="growth-title">{{ $lv['title'] }}</div>
                        <span class="growth-time">{{ $lv['time'] }}</span>
                    </div>
                    <p class="growth-desc">{{ $lv['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- TEAMS --}}
@if($departments->isNotEmpty())
<section class="teams-section" id="teams">
    <div class="container">
        <div class="section-header">
            <div>
                <p class="section-eyebrow">Explore our teams</p>
                <h2 class="section-title">Find your team.</h2>
            </div>
            <a href="#open-roles" class="section-link">All departments →</a>
        </div>

        <div class="teams-grid">
            @foreach($departments as $i => $d)
            <a href="{{ url('/career?department=' . urlencode($d)) }}" class="team-card team-{{ $i % 6 }}" style="--d:{{ $i * 40 }}ms;">
                <div class="team-card-top">
                    <span class="team-card-name">{{ $d }}</span>
                    <span class="team-card-arrow">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </span>
                </div>
                <div class="team-card-count">{{ $deptCounts[$d] ?? 0 }} open role{{ ($deptCounts[$d] ?? 0) !== 1 ? 's' : '' }}</div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- PROCESS --}}
<section class="process-section" id="process">
    <div class="container">
        <div class="section-header section-header-dark">
            <div>
                <p class="section-eyebrow">How we hire</p>
                <h2 class="section-title">Simple. Transparent.<br>Respectful of your time.</h2>
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
    </div>
</section>

{{-- LIFE / TESTIMONIALS --}}
<section class="life-section" id="life">
    <div class="container">
        <div class="section-header">
            <div>
                <p class="section-eyebrow">Team voices</p>
                <h2 class="section-title">Life at DonateBazaar.</h2>
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
            <figure class="testimonial-card">
                <div class="testimonial-stars" aria-label="5 out of 5">★★★★★</div>
                <blockquote class="testimonial-text">{{ $quote }}</blockquote>
                <figcaption class="testimonial-author">
                    <div class="author-avatar">{{ $initials }}</div>
                    <div>
                        <div class="author-name">{{ $name }}</div>
                        <div class="author-role">{{ $role }}</div>
                    </div>
                </figcaption>
            </figure>
            @endforeach
        </div>
    </div>
</section>

{{-- FAQ --}}
<section class="faq-section" id="faq">
    <div class="container faq-container">
        <div class="section-header">
            <div>
                <p class="section-eyebrow">FAQ</p>
                <h2 class="section-title">Questions?<br>Answered.</h2>
            </div>
            <a href="mailto:careers@donatebazaar.com" class="section-link">Still curious? Ask us →</a>
        </div>

        <div class="faq-list">
            @php
            $faqs = [
                ['q' => 'How does the application process work?', 'a' => 'Apply online with your CV and a short cover note. If there\'s a fit, we schedule a 30-minute intro call, then a paid take-home assessment, and finally a team interview. Most candidates hear back within 5 business days of applying and within 48 hours of the final round.'],
                ['q' => 'Is DonateBazaar fully remote?', 'a' => 'We\'re remote-first, not remote-only. Most roles are fully remote within India, with optional co-working meetups in major cities. Some roles (like volunteering) are location-specific — check each job posting for details.'],
                ['q' => 'Do you offer internships and campus hiring?', 'a' => 'Yes. We run paid internships year-round across engineering, design, product and growth. Campus hiring happens twice a year — follow our careers page and LinkedIn for announcements.'],
                ['q' => 'What about visas and international candidates?', 'a' => 'Currently, we hire within India for most roles. We\'re happy to consider exceptional international candidates on a case-by-case basis for senior positions, with full relocation support.'],
                ['q' => 'How long does the whole process take?', 'a' => 'Typically 2–3 weeks from application to offer. We keep every step short, pay for assessment time, and never leave you hanging — you\'ll always know where you stand.'],
                ['q' => 'What does the learning budget cover?', 'a' => '₹50,000/year for courses, books, conferences, certifications and tools. Plus a dedicated mentor for your first 6 months and quarterly growth reviews with your manager.'],
            ];
            @endphp
            @foreach($faqs as $i => $faq)
            <div class="faq-item">
                <button type="button" class="faq-question" aria-expanded="false" aria-controls="faq-a-{{ $i }}">
                    <span>{{ $faq['q'] }}</span>
                    <svg class="faq-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
                </button>
                <div class="faq-answer" id="faq-a-{{ $i }}" role="region">
                    <p>{{ $faq['a'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- OPEN ROLES --}}
<section class="jobs-section" id="open-roles">
    <div class="container">
        <div class="jobs-header">
            <div>
                <p class="section-eyebrow">Open roles</p>
                <h2 class="section-title">Find your place<br>on the team.</h2>
            </div>
            <span class="jobs-count" id="jobsCount" aria-live="polite">
                {{ $jobPosts->total() }} position{{ $jobPosts->total() !== 1 ? 's' : '' }}
            </span>
        </div>

        <form method="GET" action="{{ url('/career') }}" id="filterForm" class="filter-form">
            <input type="hidden" id="hiddenType"   name="type"   value="{{ request('type', '') }}">
            <input type="hidden" id="hiddenDept"   name="department" value="{{ request('department', '') }}">
            <input type="hidden" id="hiddenSearch" name="search" value="{{ request('search', '') }}">

            <div class="filters-bar" id="filtersBar">
                <div class="search-wrap">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                    </svg>
                    <input class="search-input" type="search" id="searchVisible" aria-label="Search open roles"
                        placeholder="Search roles, skills…" value="{{ request('search', '') }}" autocomplete="off">
                </div>

                <div class="type-filter" id="typeFilter" role="group" aria-label="Filter by employment type">
                    @php
                        $types  = ['full-time','part-time','contract','internship','freelance','remote','volunteer'];
                        $active = request('type', '');
                    @endphp
                    <button type="button" class="filter-chip {{ $active === '' ? 'active' : '' }}" data-type="" aria-pressed="{{ $active === '' ? 'true' : 'false' }}">All</button>
                    @foreach($types as $t)
                    <button type="button" class="filter-chip {{ $active === $t ? 'active' : '' }}" data-type="{{ $t }}" aria-pressed="{{ $active === $t ? 'true' : 'false' }}">{{ ucfirst($t) }}</button>
                    @endforeach
                </div>

                @if($departments->isNotEmpty())
                <div class="type-filter dept-filter" id="deptFilter" role="group" aria-label="Filter by department">
                    @php $activeDept = request('department', ''); @endphp
                    <button type="button" class="filter-chip {{ $activeDept === '' ? 'active' : '' }}" data-dept="" aria-pressed="{{ $activeDept === '' ? 'true' : 'false' }}">All Teams</button>
                    @foreach($departments as $d)
                    <button type="button" class="filter-chip {{ $activeDept === $d ? 'active' : '' }}" data-dept="{{ $d }}" aria-pressed="{{ $activeDept === $d ? 'true' : 'false' }}">{{ $d }}<span class="chip-count">{{ $deptCounts[$d] ?? 0 }}</span></button>
                    @endforeach
                </div>
                @endif

                <div class="sort-wrap">
                    <label for="sortSelect" class="sort-label">Sort</label>
                    <select id="sortSelect" name="sort" class="sort-select">
                        <option value="newest" @selected($sort === 'newest')>Newest first</option>
                        <option value="featured" @selected($sort === 'featured')>Featured first</option>
                        <option value="deadline" @selected($sort === 'deadline')>Closing soonest</option>
                    </select>
                </div>
            </div>
        </form>

        {{-- JOB GRID --}}
        {{-- NOTE: The controller already excludes expired jobs via whereDate('application_deadline','>=',today).
             No view-level filtering needed — every $job here is guaranteed to be active and not expired. --}}
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

            <article class="job-card {{ $job->featured ? 'is-featured' : '' }}">
                @if($job->featured)
                <span class="featured-flag">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                    </svg>
                    Featured
                </span>
                @endif

                <div class="card-top">
                    <h3 class="job-title">
                        <a href="{{ route('job_posts.show', $job->slug) }}" class="stretched-link">{{ $job->title }}</a>
                    </h3>
                </div>

                <div class="badge-row">
                    <span class="badge badge-type badge-{{ $job->type }}">{{ ucfirst($job->type) }}</span>
                    @if($job->is_remote)
                    <span class="badge badge-remote">Remote</span>
                    @endif
                    @if($job->department)
                    <span class="badge badge-dept">{{ $job->department }}</span>
                    @endif
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
                    @if($job->salary)
                    <span class="meta-item meta-salary">₹{{ $job->salary }}<span class="meta-sub"> / annum</span></span>
                    @endif
                    @if(($job->vacancies ?? 1) > 1)
                    <span class="meta-item">{{ $job->vacancies }} openings</span>
                    @endif
                </div>

                <p class="card-excerpt">{{ Str::limit(strip_tags($job->description), 150) }}</p>

                @if(!empty($job->skills))
                <div class="skills-row">
                    @foreach(array_slice($job->skills, 0, 3) as $skill)
                    <span class="skill-chip">{{ $skill }}</span>
                    @endforeach
                </div>
                @endif

                <div class="card-footer">
                    <div class="footer-left">
                        @if($deadlineLabel)
                            <span class="deadline {{ $deadlineUrgent ? 'urgent' : '' }}">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                                </svg>
                                {{ $deadlineLabel }}
                            </span>
                        @endif
                        <span class="posted">
                            Posted {{ $job->published_at ? $job->published_at->diffForHumans() : $job->created_at->diffForHumans() }}
                        </span>
                    </div>
                    <a href="{{ route('job_posts.show', $job->slug) }}" class="apply-btn" aria-label="View {{ $job->title }} and apply">
                        Apply now
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path d="M5 12h14M12 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </article>

            @empty
            <div class="empty">
                <div class="empty-icon">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                        <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                    </svg>
                </div>
                <h3>No openings found</h3>
                <p>Try adjusting your search or check back soon — we're always growing.</p>
                @if(request('search') || request('type') || request('department') || request('sort') !== 'newest')
                <a href="{{ url('/career') }}" class="empty-clear">Clear all filters</a>
                @endif
            </div>
            @endforelse
        </div>

        {{-- PAGINATION --}}
        @if($jobPosts->hasPages())
        <div class="pagination-wrap">
            {{ $jobPosts->appends(['search' => request('search'), 'type' => request('type')])->links() }}
        </div>
        @endif
    </div>
</section>

{{-- CTA --}}
<section class="cta-section">
    <div class="cta-inner">
        <h2>Don't see the right role?</h2>
        <p>We're always on the lookout for exceptional people. Send us your profile and we'll reach out when something fits.</p>
        <a href="mailto:careers@donatebazaar.com" class="cta-btn">
            Send Open Application
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </a>
    </div>
</section>

{{-- FOOTER --}}
<footer>
    <div class="footer-grid">
        <div class="footer-col footer-col-brand">
            <div class="footer-brand">Donate<span>Bazaar</span></div>
            <p class="footer-tagline">Work that matters. Built by people who care.</p>
        </div>
        <div class="footer-col">
            <div class="footer-col-title">Company</div>
            <a href="/">Home</a>
            <a href="/about">About</a>
            <a href="/contact">Contact</a>
        </div>
        <div class="footer-col">
            <div class="footer-col-title">Careers</div>
            <a href="#open-roles">Open Roles</a>
            <a href="#growth">Growth</a>
            <a href="#teams">Teams</a>
            <a href="#life">Life at DonateBazaar</a>
            <a href="#faq">FAQ</a>
        </div>
        <div class="footer-col">
            <div class="footer-col-title">Get in touch</div>
            <a href="mailto:careers@donatebazaar.com">careers@donatebazaar.com</a>
            <a href="/career" style="cursor:default; opacity:.55;">Stay curious. Build boldly.</a>
        </div>
    </div>
    <div class="footer-bottom">
        <span>© {{ date('Y') }} DonateBazaar. All rights reserved.</span>
        <span>Made with ❤ for NGOs everywhere.</span>
    </div>
</footer>

<button type="button" class="back-to-top" id="backToTop" aria-label="Back to top">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M12 19V5M5 12l7-7 7 7"/></svg>
</button>

<script>
(function () {
'use strict';

var form        = document.getElementById('filterForm');
var hiddenType  = document.getElementById('hiddenType');
var hiddenDept  = document.getElementById('hiddenDept');
var hiddenSearch = document.getElementById('hiddenSearch');
var filtersBar  = document.getElementById('filtersBar');
var chips       = Array.prototype.slice.call(document.querySelectorAll('.filter-chip'));

document.querySelectorAll('.job-card').forEach(function (card, i) {
    card.style.transitionDelay = (i * 0.06) + 's';
});

var cards = Array.prototype.slice.call(document.querySelectorAll('.job-card'));
if ('IntersectionObserver' in window && cards.length) {
    var cardObserver = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (!entry.isIntersecting) return;
            entry.target.classList.add('is-revealed');
            cardObserver.unobserve(entry.target);
            setTimeout(function () { entry.target.style.transitionDelay = '0s'; }, 700);
        });
    }, { threshold: 0.08 });
    cards.forEach(function (card) { cardObserver.observe(card); });
} else {
    cards.forEach(function (card) { card.classList.add('is-revealed'); });
}

function submitFilters() {
    form.classList.add('is-loading');
    form.submit();
}

chips.forEach(function (chip) {
    chip.addEventListener('click', function () {
        var group = chip.closest('[role="group"]');
        var isDept = group && group.id === 'deptFilter';
        var hidden = isDept ? hiddenDept : hiddenType;
        hidden.value = isDept ? (chip.dataset.dept || '') : (chip.dataset.type || '');

        group.querySelectorAll('.filter-chip').forEach(function (b) {
            b.classList.remove('active');
            b.setAttribute('aria-pressed', 'false');
        });
        chip.classList.add('active');
        chip.setAttribute('aria-pressed', 'true');
        submitFilters();
    });
});

document.getElementById('sortSelect').addEventListener('change', function () {
    submitFilters();
});

var searchTimer;
var searchVisible = document.getElementById('searchVisible');

searchVisible.addEventListener('input', function () {
    hiddenSearch.value = this.value;
    clearTimeout(searchTimer);
    searchTimer = setTimeout(submitFilters, 420);
});

searchVisible.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
        this.value = '';
        hiddenSearch.value = '';
        submitFilters();
    }
});

var heroSearch = document.getElementById('heroSearch');
if (heroSearch && hiddenSearch.value) {
    heroSearch.value = hiddenSearch.value;
}

var navSections = ['why', 'impact', 'growth', 'teams', 'process', 'life', 'faq', 'open-roles'];
var navLinks = Array.prototype.slice.call(document.querySelectorAll('.topbar-nav .nav-link'));

if ('IntersectionObserver' in window && navLinks.length) {
    var spy = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (!entry.isIntersecting) return;
            var id = entry.target.id;
            navLinks.forEach(function (link) {
                var active = link.getAttribute('href') === '#' + id;
                link.classList.toggle('active', active);
                if (active) {
                    link.setAttribute('aria-current', 'true');
                } else {
                    link.removeAttribute('aria-current');
                }
            });
        });
    }, { rootMargin: '-45% 0px -50% 0px' });

    navSections.forEach(function (id) {
        var el = document.getElementById(id);
        if (el) spy.observe(el);
    });
}

var growth = document.getElementById('growth');
if (growth && 'IntersectionObserver' in window) {
    var growthObs = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                growthObs.disconnect();
            }
        });
    }, { threshold: 0.25 });
    growthObs.observe(growth);
}

document.querySelectorAll('.faq-question').forEach(function (btn) {
    btn.addEventListener('click', function () {
        var item = btn.closest('.faq-item');
        var answer = item.querySelector('.faq-answer');
        var isOpen = item.classList.contains('is-open');

        document.querySelectorAll('.faq-item.is-open').forEach(function (openItem) {
            if (openItem === item) return;
            openItem.classList.remove('is-open');
            openItem.querySelector('.faq-answer').style.maxHeight = null;
            openItem.querySelector('.faq-question').setAttribute('aria-expanded', 'false');
        });

        if (isOpen) {
            item.classList.remove('is-open');
            answer.style.maxHeight = null;
            btn.setAttribute('aria-expanded', 'false');
        } else {
            item.classList.add('is-open');
            answer.style.maxHeight = answer.scrollHeight + 'px';
            btn.setAttribute('aria-expanded', 'true');
        }
    });
});

var navToggle = document.getElementById('navToggle');
var mobileNav = document.getElementById('mobileNav');
if (navToggle && mobileNav) {
    navToggle.addEventListener('click', function () {
        var open = mobileNav.classList.toggle('is-open');
        navToggle.classList.toggle('is-active', open);
        navToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
    mobileNav.querySelectorAll('a').forEach(function (link) {
        link.addEventListener('click', function () {
            mobileNav.classList.remove('is-open');
            navToggle.classList.remove('is-active');
            navToggle.setAttribute('aria-expanded', 'false');
        });
    });
}

var backToTop = document.getElementById('backToTop');
if (backToTop) {
    var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    backToTop.addEventListener('click', function () {
        window.scrollTo({ top: 0, behavior: reduceMotion ? 'auto' : 'smooth' });
    });
    var onScrollBtt = function () {
        backToTop.classList.toggle('is-visible', window.scrollY > 600);
    };
    onScrollBtt();
    window.addEventListener('scroll', onScrollBtt, { passive: true });
}

var counts = Array.prototype.slice.call(document.querySelectorAll('.stat-count'));
if (counts.length && 'IntersectionObserver' in window) {
    var statsBoxes = Array.prototype.slice.call(document.querySelectorAll('.hero-stats, .impact-stats'));
    var countObserver = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (!entry.isIntersecting) return;
            entry.target.querySelectorAll('.stat-count').forEach(function (el) {
                var target = parseInt(el.dataset.count, 10) || 0;
                var suffix = el.dataset.suffix || '';
                var start = null;
                var dur = 1400;
                function step(ts) {
                    if (!start) start = ts;
                    var p = Math.min((ts - start) / dur, 1);
                    var eased = 1 - Math.pow(1 - p, 3);
                    el.textContent = Math.round(target * eased).toLocaleString('en-IN') + suffix;
                    if (p < 1) requestAnimationFrame(step);
                }
                requestAnimationFrame(step);
            });
            countObserver.unobserve(entry.target);
        });
    }, { threshold: 0.3 });
    statsBoxes.forEach(function (box) {
        if (box) countObserver.observe(box);
    });
} else if (counts.length) {
    counts.forEach(function (el) {
        el.textContent = (parseInt(el.dataset.count, 10) || 0).toLocaleString('en-IN') + (el.dataset.suffix || '');
    });
}

var scrollProgress = document.getElementById('scrollProgress');
if (scrollProgress) {
    var onScrollProgress = function () {
        var doc = document.documentElement;
        var max = doc.scrollHeight - doc.clientHeight;
        scrollProgress.style.width = (max > 0 ? (doc.scrollTop / max) * 100 : 0) + '%';
    };
    onScrollProgress();
    window.addEventListener('scroll', onScrollProgress, { passive: true });
    window.addEventListener('resize', onScrollProgress, { passive: true });
}

window.addEventListener('scroll', function () {
    filtersBar.classList.toggle('is-stuck', window.scrollY > 80);
}, { passive: true });

})();
</script>
</body>
</html>
