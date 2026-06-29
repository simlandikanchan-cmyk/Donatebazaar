{{-- ═══ CAMPAIGNS ═══ --}}
<section class="campaigns-section">
    <div class="container">

        <div class="section-header">
            <div class="section-eyebrow">Make an impact</div>

            <h2 class="section-title">
                Featured Campaigns
            </h2>

            <p class="section-sub">
                Support urgent and impactful causes across India.
            </p>
        </div>

        {{-- ═══ CATEGORY FILTERS ═══ --}}
        <div class="camp-filter-wrap" id="campFilterWrap">

            <button class="camp-filter-btn active" data-cat="all">
                All
            </button>

            @foreach($categories as $category)
                <button
                    class="camp-filter-btn"
                    data-cat="{{ $category->slug }}"
                >
                    {{ $category->name }}
                </button>
            @endforeach

        </div>

        {{-- ═══ CAMPAIGN GRID ═══ --}}
        <div class="camp-grid" id="campaignContainer">

            <p class="camp-filter-empty" id="campEmpty">
                No campaigns found in this category.
            </p>

            @foreach($campaigns as $index => $campaign)

                @php
                    /*
                    |--------------------------------------------------------------------------
                    | Raised / Goal / Donors
                    |--------------------------------------------------------------------------
                    | raised_amount is a cached column on campaigns, read directly —
                    | not recomputed from donations, so it stays consistent with
                    | total_settled / pending_settlement / platform_earnings.
                    | donors_count comes pre-aggregated from the controller (withCount).
                    | Expiry + active-state filtering also already happened in the
                    | controller, so there is no need to re-check $isExpired in this view.
                    */
                    $raised = $campaign->raised_amount ?? 0;
                    $goal   = $campaign->goal_amount ?? 0;
                    $donors = $campaign->donors_count ?? 0;

                    // Use the model's own `progress` accessor (uncapped — can exceed
                    // 100% for an overfunded campaign) for the displayed badge text,
                    // but cap a separate value at 100 for the progress-bar width so an
                    // overfunded campaign never visually overflows the track.
                    $percentage = $campaign->progress;
                    $progressBarWidth = min($percentage, 100);
                @endphp

                {{-- ═══ CAMPAIGN CARD ═══ --}}
                <div
                    class="camp-card hidden"
                    data-cat="{{ $campaign->category?->slug ?? 'uncategorized' }}"
                >

                    {{-- IMAGE --}}
                    <div class="camp-img">

                        <img
                            loading="lazy"
                            src="{{ asset('storage/' . $campaign->cover_image) }}"
                            alt="{{ $campaign->title }}"
                        >

                        <div class="camp-badge">
                            {{ $percentage }}% Funded
                        </div>

                        {{--
                            KYC is verified per fundraiser (user), not per campaign —
                            see kyc_verifications table (status: pending/approved/rejected).
                            Reuses Campaign::ownerKycApproved() so the check stays
                            consistent with the rest of the app (e.g. resume()).
                        --}}
                        @if($campaign->ownerKycApproved())
                            <div class="camp-verified">
                                Verified
                            </div>
                        @endif

                    </div>

                    {{-- BODY --}}
                    <div class="camp-body">

                        <h3 class="camp-title">
                            {{ $campaign->title }}
                        </h3>

                        {{-- PROGRESS BAR --}}
                        <div class="camp-progress-track">
                            <div
                                class="camp-progress-fill"
                                style="width: {{ $progressBarWidth }}%"
                            ></div>
                        </div>

                        {{-- META --}}
                        <div class="camp-meta">

                            <span>
                                <strong>
                                    ₹{{ number_format($raised) }}
                                </strong>
                                raised
                            </span>

                            <span>
                                Goal
                                <strong>
                                    ₹{{ number_format($goal) }}
                                </strong>
                            </span>

                        </div>

                        {{-- DONORS --}}
                        <div class="camp-donors">
                            {{ number_format($donors) }}
                            donors · Active Campaign
                        </div>

                        {{-- BUTTON --}}
                        <a
                            href="{{ route('campaign.public', [
                                'category' => $campaign->category?->slug ?? 'general',
                                'slug'     => $campaign->slug
                            ]) }}"
                            class="camp-btn"
                        >
                            Donate Now
                        </a>

                    </div>
                </div>

            @endforeach

        </div>

        {{-- ═══ INFINITE SCROLL LOADER ═══ --}}
        <div class="infinite-loader" id="infiniteLoader">

            <div class="infinite-loader-inner">

                <svg
                    class="loader-spinner"
                    id="loaderSpinner"
                    width="20"
                    height="20"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>
                </svg>

                <span id="loaderText">
                    Scroll to load more
                </span>

            </div>

        </div>

    </div>
</section>