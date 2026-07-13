<!-- ══════════════════════════════
     FOOTER
══════════════════════════════ -->
<footer class="site-footer" id="site-footer">

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
                    <a href="https://www.facebook.com/" class="social-btn" aria-label="Facebook">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>
                        </svg>
                    </a>
                    <a href="https://x.com/" class="social-btn" aria-label="Twitter / X">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M4 4l16 16M20 4L4 20" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" fill="none"/>
                        </svg>
                    </a>
                    <a href="https://www.instagram.com/" class="social-btn" aria-label="Instagram">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="2" width="20" height="20" rx="5" ry="5"/>
                            <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/>
                            <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/>
                        </svg>
                    </a>
                    <a href="https://www.linkedin.com/" class="social-btn" aria-label="LinkedIn">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/>
                            <rect x="2" y="9" width="4" height="12"/>
                            <circle cx="4" cy="4" r="2"/>
                        </svg>
                    </a>
                    <a href="https://www.youtube.com/" class="social-btn" aria-label="YouTube">
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
                    <li><a href="{{ route('search') }}">Search</a></li>
                    <li><a href="{{ route('impact') }}">Impact Stories</a></li>
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
                                placeholder="Your email"
                                aria-label="Email for newsletter"
                                value="{{ old('email') }}"
                                required
                            >
                            <button type="submit" id="nl-submit">
                                <span class="btn-label">Subscribe</span>
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
                        <span class="mini-stat-dot" style="background:rgba(37,99,235,0.8);"></span>
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
                    <a href="{{ route('privacy') }}">Privacy</a>
                    <a href="{{ route('terms') }}">Terms</a>
                    <a href="{{ route('refund') }}">Refunds</a>
                    <a href="{{ route('cookies') }}">Cookies</a>
                    <a href="{{ route('faq') }}">FAQ</a>
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