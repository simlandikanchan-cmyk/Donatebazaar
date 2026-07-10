{{-- ═══ CAMPAIGNS ═══ --}}
<section class="campaigns-section" id="campaigns">
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

        {{-- ═══ CATEGORY FILTERS (sticky on scroll) ═══ --}}
        <div class="camp-filter-sticky">
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
        </div>

        {{-- ═══ CAMPAIGN GRID ═══ --}}
        <div class="camp-grid" id="campaignContainer">

            <p class="camp-filter-empty" id="campEmpty">
                No campaigns found in this category.
            </p>

            @foreach($campaigns as $index => $campaign)

                @php
                    $raised = $campaign->raised_amount ?? 0;
                    $goal   = $campaign->goal_amount ?? 0;
                    $donors = $campaign->donors_count ?? 0;

                    $percentage = $campaign->progress;
                    $progressBarWidth = min($percentage, 100);

                    // Urgency: days remaining until the campaign closes.
                    $endDate  = $campaign->end_date;
                    $daysLeft = $endDate ? now()->diffInDays($endDate, false) : null;
                    $endingSoon = $daysLeft !== null && $daysLeft >= 0 && $daysLeft <= 7;

                    $ownerAvatar = $campaign->user?->avatar
                        ? asset('storage/' . $campaign->user->avatar)
                        : asset('images/default-avatar.png');

                    $isFeatured = (bool) $campaign->is_featured;

                    $campUrl = route('campaign.public', [
                        'category' => $campaign->category?->slug ?? 'general',
                        'slug'     => $campaign->slug,
                    ]);
                @endphp

                {{-- ═══ CAMPAIGN CARD ═══ --}}
                <div
                    class="camp-card hidden{{ $isFeatured ? ' is-featured' : '' }}"
                    data-cat="{{ $campaign->category?->slug ?? 'uncategorized' }}"
                >

                    {{-- Featured ribbon --}}
                    @if($isFeatured)
                        <div class="camp-ribbon">Featured</div>
                    @endif

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

                        @if($campaign->ownerKycApproved())
                            <div class="camp-verified">
                                Verified
                            </div>
                        @endif

                        {{-- Urgency pill --}}
                        @if($daysLeft !== null && $daysLeft >= 0)
                            <div class="camp-urgency{{ $endingSoon ? ' is-urgent' : '' }}">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="11" height="11" aria-hidden="true">
                                    <circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>
                                </svg>
                                {{ $daysLeft == 0 ? 'Ends today' : $daysLeft . ' days left' }}
                            </div>
                        @endif

                        {{-- Whole-card clickable overlay --}}
                        <a class="camp-card-overlay" href="{{ $campUrl }}" aria-label="View {{ $campaign->title }}"></a>
                    </div>

                    {{-- BODY --}}
                    <div class="camp-body">

                        <h3 class="camp-title">
                            <a href="{{ $campUrl }}">{{ $campaign->title }}</a>
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

                        {{-- DONORS + OWNER --}}
                        <div class="camp-donors">
                            <span class="camp-avatars" aria-hidden="true">
                                <img class="camp-avatar" src="{{ $ownerAvatar }}" alt="">
                                <span class="camp-avatar camp-avatar--ring">+{{ number_format($donors) }}</span>
                            </span>
                            <span class="camp-donors-text">
                                {{ number_format($donors) }} donors
                            </span>
                        </div>

                        {{-- BUTTON --}}
                        <a
                            href="{{ $campUrl }}"
                            class="btn btn-accent btn-block camp-donate-btn"
                        >
                            Donate Now
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
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
