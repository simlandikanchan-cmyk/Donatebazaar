<section class="cta-section">
    <img src="{{ asset('images/banner2.jpeg') }}" alt="">
    <div class="cta-overlay">
        <div>
            <h2 class="cta-title">Your Donation Can Change a Life</h2>
            <p class="cta-sub">Support urgent needs like medical care, education, and disaster relief — every rupee makes a difference. You can also start your own fundraiser in just a few minutes.</p>
            <div class="flex justify-center flex-wrap gap-3">
                <x-button variant="primary" size="lg" href="{{ route('all.campaigns') }}">
                    Donate Now
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </x-button>
                <x-button variant="outline" size="lg" href="/campaign/create">
                    Start Fundraiser
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 4v16m8-8H4"/></svg>
                </x-button>
            </div>
        </div>
    </div>
</section>