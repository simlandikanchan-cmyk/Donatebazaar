<section class="story-section">
    <div class="container">
        <div class="story-grid">

            {{-- Visual side --}}
            <div class="story-visual reveal reveal-left">
                <div class="story-photo">
                    <img src="{{ asset('images/donation.webp') }}" alt="A child receiving support from a campaign">
                    <span class="story-photo-tag"><span class="story-pulse"></span>Live story</span>
                </div>
                <div class="story-badge">
                    <div class="story-badge-num">1</div>
                    <div>
                        <div class="story-badge-lbl">donation</div>
                        <div class="story-badge-sub">becomes a story</div>
                    </div>
                </div>
            </div>

            {{-- Narrative side --}}
            <div class="story-content reveal reveal-right">
                <div class="eyebrow">Our Story</div>
                <h2 class="section-title">Every gift writes<br>a <em>new chapter</em>.</h2>
                <p class="story-lead">
                    When Aarav's village lost its only well, a single campaign turned strangers
                    into neighbours. Today, 240 families drink clean water — and one small act
                    became a story the whole community tells.
                </p>

                <div class="story-quote">
                    <span class="story-quote-mark">“</span>
                    <p>I never met the people who helped us. But my children will grow up
                        knowing kindness has no address.</p>
                    <div class="story-quote-by">— Meera, Aarav's mother</div>
                </div>

                <x-button variant="secondary" href="{{ route('campaigns.index') }}">
                    Read more stories
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                </x-button>
            </div>

        </div>
    </div>
</section>
