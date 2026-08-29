<div class="footer-grid">

    <div style="grid-column: 1 / -1;">
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
            <x-button variant="secondary" href="https://www.facebook.com/">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>
                </svg>
            </x-button>
            <x-button variant="secondary" href="https://x.com/">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M4 4l16 16M20 4L4 20" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" fill="none"/>
                </svg>
            </x-button>
            <x-button variant="secondary" href="https://www.instagram.com/">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="2" y="2" width="20" height="20" rx="5" ry="5"/>
                    <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/>
                    <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/>
                </svg>
            </x-button>
            <x-button variant="secondary" href="https://www.linkedin.com/">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/>
                    <rect x="2" y="9" width="4" height="12"/>
                    <circle cx="4" cy="4" r="2"/>
                </svg>
            </x-button>
            <x-button variant="secondary" href="https://www.youtube.com/">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22.54 6.42a2.78 2.78 0 0 0-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46a2.78 2.78 0 0 0-1.95 1.96A29 29 0 0 0 1 12a29 29 0 0 0 .46 5.58A2.78 2.78 0 0 0 3.41 19.6C5.12 20 12 20 12 20s6.88 0 8.59-.46a2.78 2.78 0 0 0 1.95-1.95A29 29 0 0 0 23 12a29 29 0 0 0-.46-5.58z"/>
                    <polygon points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02" fill="currentColor"/>
                </svg>
            </x-button>
        </div>
    </div>

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

    <div class="footer-col">
        <h3>Legal</h3>
        <ul>
            <li><a href="{{ route('terms') }}">Terms of Service</a></li>
            <li><a href="{{ route('privacy') }}">Privacy Policy</a></li>
            <li><a href="{{ route('refund') }}">Refund Policy</a></li>
            <li><a href="{{ route('cookies') }}">Cookie Policy</a></li>
            <li><a href="{{ route('donor') }}">Donor Policy</a></li>
            <li><a href="{{ route('campaign') }}">Campaign Policy</a></li>
            <li><a href="{{ route('kyc') }}">KYC Policy</a></li>
            <li><a href="{{ route('grievance') }}">Grievance Policy</a></li>
            <li><a href="{{ route('acceptable.use') }}">Acceptable Use</a></li>
            <li><a href="{{ route('payment') }}">Payment Policy</a></li>
        </ul>
    </div>

    <div class="newsletter-col">
        <h3>Stay Updated</h3>
        <p class="newsletter-desc">
            Get inspiring stories, new campaigns, and impact reports — straight to your inbox.
        </p>

        <form id="newsletter-form" action="{{ route('newsletter.subscribe') }}" method="POST" novalidate>
            @csrf

            <div class="nl-feedback success @if(session('newsletter_success')) show @endif" id="nl-success">
                <span>✓</span><span>{{ session('newsletter_success') ?? 'You are subscribed. Welcome aboard!' }}</span>
            </div>

            @error('email')
                <div class="nl-feedback error show">
                    <span>!</span><span>{{ $message }}</span>
                </div>
            @enderror
            <div class="nl-feedback error" id="nl-error">
                <span>!</span><span>Enter a valid email address.</span>
            </div>

            <label for="nl-email" class="nl-label">Your email</label>
            <div class="newsletter-form-wrap">
                <div class="newsletter-form-inner">
                    <input
                        id="nl-email"
                        type="email"
                        name="email"
                        placeholder="Your email"
                        aria-label="Email for newsletter"
                        value="{{ old('email') }}"
                        required
                    >
                    <button type="submit" id="nl-submit">
                        Subscribe
                        <span class="spinner" aria-hidden="true"></span>
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

</div>
