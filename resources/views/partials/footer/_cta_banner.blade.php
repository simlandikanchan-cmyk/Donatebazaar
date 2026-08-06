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
        <x-button variant="primary" href="{{ route('all.campaigns') }}" class="cta-btn">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
            </svg>
            Explore Campaigns
        </x-button>
        <x-button variant="outline" href="{{ route('campaign.create') }}" class="cta-btn-ghost">Start a Fundraiser <span>→</span></x-button>
    </div>
</div>
