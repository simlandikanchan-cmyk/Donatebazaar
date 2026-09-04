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

        {{-- ═══ FILTER TOOLBAR ═══ --}}
        <div class="camp-filter-toolbar">
            <div class="camp-filter-dropdown-wrap">
                <label for="campFilterSelect" class="camp-filter-label">Category</label>
                <div class="camp-filter-select-inner">
                    <select class="camp-filter-select" id="campFilterSelect" aria-label="Filter campaigns by category">
                        <option value="all" selected>All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->slug }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <span class="camp-filter-chevron">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                    </span>
                </div>
            </div>
            <div class="camp-filter-count">
                <span id="campCount">{{ $campaigns->count() }}</span> campaigns
            </div>
        </div>

        {{-- ═══ CAMPAIGN GRID ═══ --}}
        <div class="camp-grid" id="campaignContainer">

            <p class="camp-filter-empty" id="campEmpty">
                No campaigns found
                <span>There are currently no campaigns in this category.</span>
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
                    $daysLeft = $endDate ? (int) floor(now()->diffInDays($endDate, false)) : null;
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

                        @if($campaign->description)
                        <p class="camp-excerpt">
                            {{ \Illuminate\Support\Str::limit(strip_tags($campaign->description), 110) }}
                        </p>
                        @endif

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
                        <x-button variant="primary" href="{{ $campUrl }}">
                            Donate Now
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </x-button>

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
