<div class="hero">
    <div class="hero-bg">
        <img src="{{ asset('images/about.jpg') }}" alt="About DonateBazaar" loading="eager">
    </div>
    <div class="hero-overlay"></div>
    <div class="hero-grid"></div>

    <div class="hero-inner">
        <div class="hero-content">
            <div class="hero-pill">
                <span class="hero-pill-dot"></span>
                Trusted by 50,000+ Donors Across India
            </div>

            <h1 class="hero-title">
                Giving Made <em>Simple,</em>
                <span class="line2">Secure &amp; Impactful</span>
            </h1>

            <p class="hero-desc">
                We connect compassionate donors with verified campaigns across India — building a movement of trust, transparency, and lasting change since 2020.
            </p>

            <div class="hero-btns">
                <a href="{{ route('campaigns.index') }}" class="btn btn-white">
                    Donate Now
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
                <a href="/campaign/create" class="btn btn-outline">
                    Start Fundraiser
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 4v16m8-8H4"/></svg>
                </a>
            </div>

            <div class="hero-trust">
                <div class="hero-trust-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    Verified Campaigns
                </div>
                <div class="hero-trust-sep"></div>
                <div class="hero-trust-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                    256-bit SSL Secure
                </div>
                <div class="hero-trust-sep"></div>
                <div class="hero-trust-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    RBI Compliant
                </div>
                <div class="hero-trust-sep"></div>
                <div class="hero-trust-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    24×7 Support
                </div>
            </div>
        </div>

        {{-- Floating metric cards --}}
        <!-- <div class="hero-float-cards">
            <div class="hero-float-card">
                <div class="float-card-top">
                    <div class="float-card-icon" style="background:rgba(16,185,129,.15);">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
                    </div>
                    <span class="float-card-label">Funds Raised</span>
                </div>
                <div class="float-card-num">₹10 Cr+</div>
                <div class="float-card-sub">and growing every day</div>
            </div>
            <div class="hero-float-card">
                <div class="float-card-top">
                    <div class="float-card-icon" style="background:rgba(99,102,241,.15);">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#a5b4fc" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
                    </div>
                    <span class="float-card-label">Donors</span>
                </div>
                <div class="float-card-num">50K+</div>
                <div class="float-card-sub">across all 28 states</div>
            </div>
            <div class="hero-float-card">
                <div class="float-card-top">
                    <div class="float-card-icon" style="background:rgba(245,158,11,.15);">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
                    </div>
                    <span class="float-card-label">Campaigns</span>
                </div>
                <div class="float-card-num">2,000+</div>
                <div class="float-card-sub">active &amp; verified</div>
            </div>
        </div> -->

        {{-- Bottom stat bar --}}
        <!-- <div class="hero-stat-bar">
            <div class="hero-stat-item">
                <span class="hero-stat-val counter" data-target="10000000" data-format="crore">₹10 Cr+</span>
                <span class="hero-stat-lbl">Funds Raised</span>
            </div>
            <div class="hero-stat-item">
                <span class="hero-stat-val counter" data-target="50000" data-suffix="+">0</span>
                <span class="hero-stat-lbl">Generous Donors</span>
            </div>
            <div class="hero-stat-item">
                <span class="hero-stat-val counter" data-target="2000" data-suffix="+">0</span>
                <span class="hero-stat-lbl">Campaigns</span>
            </div>
            <div class="hero-stat-item">
                <span class="hero-stat-val">100%</span>
                <span class="hero-stat-lbl">Transparent</span>
            </div>
        </div> -->
    </div>
</div>
