{{-- resources/views/job_posts/show.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $jobPost->title }} – DonateBazaar Careers</title>
    <meta name="description" content="Apply for {{ $jobPost->title }} at DonateBazaar. {{ $jobPost->is_remote ? 'Remote-friendly role. ' : '' }}Help us build the future of giving.">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="DonateBazaar Careers">
    <meta property="og:title" content="{{ $jobPost->title }} – DonateBazaar">
    <meta property="og:description" content="Apply for {{ $jobPost->title }} at DonateBazaar. Help us build the future of giving.">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta name="twitter:card" content="summary">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    @vite(['resources/css/public/job-posts-show.css'])
</head>
<body>
<script nonce="{{ request()->get('csp_nonce') }}">document.body.classList.add('js');</script>

<div class="scroll-progress" id="scrollProgress" aria-hidden="true"></div>

{{-- NAV --}}
<header class="topbar">
    <a href="/" class="topbar-brand">Donate<span>Bazaar</span><small class="brand-tag">.careers</small></a>
    <nav class="topbar-nav">
        <a href="{{ url('/career#why') }}" class="nav-link">Why Us</a>
        <a href="{{ url('/career#teams') }}" class="nav-link">Teams</a>
        <a href="{{ url('/career#process') }}" class="nav-link">How We Hire</a>
        <a href="{{ url('/career#life') }}" class="nav-link">Life</a>
        <a href="{{ url('/career#open-roles') }}" class="nav-link">Open Roles</a>
    </nav>
    <a href="{{ url('/career') }}" class="nav-cta">View All Roles</a>
    <button type="button" class="nav-toggle" id="navToggle" aria-label="Toggle menu" aria-expanded="false" aria-controls="mobileNav">
        <svg class="nav-toggle-open" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
        <svg class="nav-toggle-close" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M18 6 6 18M6 6l12 12"/></svg>
    </button>
</header>

<div class="mobile-nav" id="mobileNav">
    <a href="{{ url('/career#why') }}" class="mobile-link">Why Us</a>
    <a href="{{ url('/career#growth') }}" class="mobile-link">Growth</a>
    <a href="{{ url('/career#teams') }}" class="mobile-link">Teams</a>
    <a href="{{ url('/career#process') }}" class="mobile-link">How We Hire</a>
    <a href="{{ url('/career#life') }}" class="mobile-link">Life</a>
    <a href="{{ url('/career#faq') }}" class="mobile-link">FAQ</a>
    <a href="{{ url('/career#open-roles') }}" class="mobile-link">Open Roles</a>
    <a href="{{ url('/career') }}" class="mobile-cta">View All Roles</a>
</div>

{{-- JOB HERO --}}
<section class="job-hero">
    <div class="job-hero-inner">
        <a href="{{ url('/career') }}" class="hero-back">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path d="M19 12H5M12 19l-7-7 7-7"/>
            </svg>
            All openings
        </a>
        <p class="hero-eyebrow">{{ ucfirst($jobPost->type) }} Position</p>
        <h1>{{ $jobPost->title }}</h1>

        <div class="meta-row">
            <span class="badge badge-type">{{ ucfirst($jobPost->type) }}</span>

            @if($jobPost->is_remote)
            <span class="badge badge-remote">Remote</span>
            @endif

            @if($jobPost->department)
            <span class="badge badge-dept">{{ $jobPost->department }}</span>
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
            <span class="meta-chip meta-salary">₹{{ $jobPost->salary }}<span class="meta-sub"> / annum</span></span>
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
            <a href="#apply-form" class="hero-apply-btn">
                Apply Now
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M5 12h14M12 5l7 7-7 7"/>
                </svg>
            </a>
            <button type="button" class="hero-share-btn btn-share" onclick="copyLink()">
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
        <div class="content-section reveal">
            <div class="content-section-title">
                <span class="title-dot"></span>
                About This Role
            </div>
            <div class="content">
                {!! nl2br(e($jobPost->description)) !!}
            </div>
        </div>

        @if($similarJobs->isNotEmpty())
        {{-- Similar Roles --}}
        <div class="similar-jobs-section reveal">
            <div class="content-section-title">
                <span class="title-dot"></span>
                Similar roles you might like
            </div>
            <div class="similar-jobs-grid">
                @foreach($similarJobs as $job)
                <a href="{{ route('job_posts.show', $job->slug) }}" class="similar-job-card">
                    <div class="similar-job-title">{{ $job->title }}</div>
                    <div class="badge-row">
                        <span class="badge badge-type badge-{{ $job->type }}">{{ ucfirst($job->type) }}</span>
                        @if($job->is_remote)
                        <span class="badge badge-remote">Remote</span>
                        @endif
                        @if($job->department)
                        <span class="badge badge-dept">{{ $job->department }}</span>
                        @endif
                    </div>
                    <div class="similar-job-meta">
                        @if($job->location)
                        <span class="meta-item">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                <circle cx="12" cy="10" r="3"/>
                            </svg>
                            {{ $job->location }}
                        </span>
                        @endif
                        @if($job->salary)
                        <span class="meta-item">₹{{ $job->salary }}<span class="meta-sub"> / annum</span></span>
                        @endif
                    </div>
                    <span class="similar-job-cta">
                        View role
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </span>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Share --}}
        <div class="share-section reveal">
            <div class="share-text">
                Know someone perfect for this?
                <span>Help us find great people — share this role.</span>
            </div>
            <div class="share-btns">
                <button type="button" class="share-btn btn-share" onclick="copyLink()">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/>
                        <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/>
                    </svg>
                    Copy Link
                </button>
                <a class="share-btn" href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(request()->url()) }}" target="_blank" rel="noopener">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/>
                        <rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/>
                    </svg>
                    LinkedIn
                </a>
                <a class="share-btn" href="https://twitter.com/intent/tweet?text={{ urlencode('Hiring: '.$jobPost->title.' at DonateBazaar') }}&url={{ urlencode(request()->url()) }}" target="_blank" rel="noopener">
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
        <div class="sidebar-card reveal">
            <div class="sidebar-card-title">Position Details</div>

            <div class="detail-row">
                <span class="detail-label">Job Type</span>
                <span class="detail-val">{{ ucfirst($jobPost->type) }}</span>
            </div>

            @if($jobPost->department)
            <div class="detail-row">
                <span class="detail-label">Department</span>
                <span class="detail-val">{{ $jobPost->department }}</span>
            </div>
            @endif

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
                <span class="detail-val">₹{{ $jobPost->salary }} / annum</span>
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
        <div class="apply-card reveal" id="apply-form">
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
        <div class="similar-card reveal">
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
            <a href="{{ url('/career#open-roles') }}">Open Roles</a>
            <a href="{{ url('/career#teams') }}">Teams</a>
            <a href="{{ url('/career#life') }}">Life at DonateBazaar</a>
        </div>
        <div class="footer-col">
            <div class="footer-col-title">Get in touch</div>
            <a href="mailto:careers@donatebazaar.com">careers@donatebazaar.com</a>
            <a href="{{ url('/career') }}" style="cursor:default; opacity:.55;">Stay curious. Build boldly.</a>
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
function copyLink() {
    if (navigator.share) {
        navigator.share({ title: document.title, url: window.location.href }).catch(function () {});
        return;
    }
    navigator.clipboard.writeText(window.location.href).then(function() {
        document.querySelectorAll('.btn-share').forEach(function(btn) {
            var orig = btn.innerHTML;
            btn.innerHTML = '✓ Copied!';
            setTimeout(function() { btn.innerHTML = orig; }, 2000);
        });
    }).catch(function () {});
}

(function () {
'use strict';

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

var revealEls = Array.prototype.slice.call(document.querySelectorAll('.reveal'));
if (revealEls.length && 'IntersectionObserver' in window) {
    var revealObserver = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (!entry.isIntersecting) return;
            entry.target.classList.add('is-revealed');
            revealObserver.unobserve(entry.target);
        });
    }, { threshold: 0.12 });
    revealEls.forEach(function (el) { revealObserver.observe(el); });
} else {
    revealEls.forEach(function (el) { el.classList.add('is-revealed'); });
}

})();
</script>
</body>
</html>
