@extends('layouts.app')

@section('content')

@push('styles')
    @vite(['resources/css/public/campaigns.css'])
@endpush


{{-- ═══════════════════════════════════════════════════════════
     FILTER MODAL
═══════════════════════════════════════════════════════════ --}}
<div class="filter-modal-backdrop" id="filterBackdrop" onclick="closeFilterModal()"></div>
<div class="filter-modal" id="filterModal" role="dialog" aria-modal="true" aria-label="Filter campaigns">
    <div class="fm-header">
        <span class="fm-title">Filter Campaigns</span>
        <button class="fm-close" onclick="closeFilterModal()" aria-label="Close">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>

    <div class="fm-body">

        {{-- Location --}}
        <div class="fm-section">
            <span class="fm-group-label">Location</span>
            <div class="custom-select" id="locationSelect">
                <div class="cs-trigger" id="locationTrigger" onclick="toggleDropdown('locationDropdown','locationTrigger')">
                    <span id="locationLabel">All Locations</span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                </div>
                <div class="cs-dropdown" id="locationDropdown">
                    @php
                    $locations = [
                        'all'           => 'All Locations',
                        'pan_india'     => 'PAN INDIA',
                        'bengaluru'     => 'Bengaluru',
                        'chennai'       => 'Chennai',
                        'hyderabad'     => 'Hyderabad',
                        'kolkata'       => 'Kolkata',
                        'mumbai'        => 'Mumbai',
                        'new_delhi'     => 'New Delhi',
                        'agartala'      => 'Agartala',
                        'ahmedabad'     => 'Ahmedabad',
                        'bhopal'        => 'Bhopal',
                        'bhubaneswar'   => 'Bhubaneswar',
                        'chandigarh'    => 'Chandigarh',
                        'coimbatore'    => 'Coimbatore',
                        'guwahati'      => 'Guwahati',
                        'indore'        => 'Indore',
                        'jaipur'        => 'Jaipur',
                        'lucknow'       => 'Lucknow',
                        'nagpur'        => 'Nagpur',
                        'patna'         => 'Patna',
                        'pune'          => 'Pune',
                        'surat'         => 'Surat',
                        'vadodara'      => 'Vadodara',
                        'visakhapatnam' => 'Visakhapatnam',
                    ];
                    @endphp
                    @foreach($locations as $val => $label)
                    <div class="cs-option {{ request('location','all') === $val ? 'selected' : '' }}"
                         data-value="{{ $val }}"
                         onclick="selectOption('locationDropdown','locationTrigger','locationLabel','{{ $val }}','{{ $label }}','filterLocation')">
                        <div class="cs-option-check"></div>
                        {{ $label }}
                    </div>
                    @endforeach
                </div>
                <input type="hidden" name="location" id="filterLocation" value="{{ request('location','all') }}">
            </div>
            <span class="fm-note"> After deployment, location will be auto-fetched from Google Maps API</span>
        </div>

        {{-- Campaign Type --}}
        <div class="fm-section">
            <span class="fm-group-label">Campaign Type</span>
            <div class="custom-select" id="typeSelect">
                <div class="cs-trigger" id="typeTrigger" onclick="toggleDropdown('typeDropdown','typeTrigger')">
                    <span id="typeLabel">Active</span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                </div>
                <div class="cs-dropdown" id="typeDropdown">
                    @php
                    $campTypes = [
                        'all'            => 'All Types',
                        'active'         => 'Active',
                        'urgent'         => 'Urgent',
                        'newly_launched' => 'Newly Launched',
                        'closed'         => 'Closed',
                        'most_raised'    => 'Most Raised',
                    ];
                    @endphp
                    @foreach($campTypes as $val => $label)
                    <div class="cs-option {{ request('campaign_type','active') === $val ? 'selected' : '' }}"
                         data-value="{{ $val }}"
                         onclick="selectOption('typeDropdown','typeTrigger','typeLabel','{{ $val }}','{{ $label }}','filterCampaignType')">
                        <div class="cs-option-check"></div>
                        {{ $label }}
                    </div>
                    @endforeach
                </div>
                <input type="hidden" name="campaign_type" id="filterCampaignType" value="{{ request('campaign_type','active') }}">
            </div>
        </div>

        {{-- Funding Progress --}}
        <div class="fm-section">
            <span class="fm-group-label">Funding Progress</span>
            <div class="type-chips" id="fundingChips">
                @foreach(['any'=>'Any','lt25'=>'Under 25%','25to75'=>'25% – 75%','gt75'=>'75%+','100'=>'Fully Funded'] as $val => $label)
                <span class="type-chip {{ request('funding','any') === $val ? 'selected' : '' }}"
                      onclick="selectChip(this,'fundingChips','filterFunding','{{ $val }}')"
                      data-value="{{ $val }}">{{ $label }}</span>
                @endforeach
            </div>
            <input type="hidden" id="filterFunding" value="{{ request('funding','any') }}">
        </div>

        {{-- Category --}}
        <div class="fm-section">
            <span class="fm-group-label">Category</span>
            <div class="type-chips" id="categoryChips">
                <span class="type-chip {{ !request('category') ? 'selected' : '' }}"
                      onclick="selectChip(this,'categoryChips','filterCategory','')"
                      data-value="">All</span>
                @foreach($categories ?? [] as $cat)
                <span class="type-chip {{ request('category') === $cat->slug ? 'selected' : '' }}"
                      onclick="selectChip(this,'categoryChips','filterCategory','{{ $cat->slug }}')"
                      data-value="{{ $cat->slug }}">{{ $cat->name }}</span>
                @endforeach
            </div>
            <input type="hidden" id="filterCategory" value="{{ request('category','') }}">
        </div>

    </div>

    <div class="fm-footer">
        <button class="fm-clear-btn" onclick="clearAllFilters()">Clear All</button>
        <button class="fm-apply-btn" onclick="applyModalFilters()">Apply Filters</button>
    </div>
</div>


{{-- ═══════════════════════════════════════════════════════════
     1. HERO
═══════════════════════════════════════════════════════════ --}}
<div class="hero">
    <div class="hero-bg">
        <img src="{{ asset('images/about.jpg') }}" alt="All Campaigns" loading="eager">
    </div>
    <div class="hero-overlay"></div>
    <div class="hero-grid-lines"></div>

    <div class="hero-inner">
        <div class="hero-content">
            <div class="hero-pill">
                <span class="hero-pill-dot"></span>
                {{ $campaigns->total() ?? $campaigns->count() }}+ lives changed — one campaign at a time
            </div>

            <h1 class="hero-title">
                Every cause has<br>a <em>story</em> worth telling
            </h1>

            <p class="hero-desc">
                Behind each campaign is a person waiting — a child who needs surgery, a family rebuilding after a flood. Read their stories and help write the ending.
            </p>

            {{-- Search bar --}}
            <form method="GET" action="{{ url()->current() }}" class="hero-search">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                <input type="text" name="search" placeholder="Search campaigns, causes, NGOs…" value="{{ request('search') }}">
                @if(request('category'))<input type="hidden" name="category" value="{{ request('category') }}">@endif
                @if(request('sort'))<input type="hidden" name="sort" value="{{ request('sort') }}">@endif
                <button type="submit" class="hero-search-btn">Search</button>
            </form>

            <div class="hero-causes">
                <span class="hero-causes-label">Browse by cause</span>
                @foreach(($categories ?? collect())->take(5) as $cat)
                <a class="hero-cause" href="{{ url()->current() }}?category={{ $cat->slug }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
                    {{ $cat->name }}
                </a>
                @endforeach
            </div>
<!-- 
            <div class="hero-trust">
                <div class="hero-trust-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    All campaigns verified
                </div>
                <div class="hero-trust-sep"></div>
                <div class="hero-trust-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                    256-bit SSL secure
                </div>
                <div class="hero-trust-sep"></div>
                <div class="hero-trust-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    80G tax benefits
                </div>
                <div class="hero-trust-sep"></div>
                <div class="hero-trust-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                    Real-time tracking
                </div>
            </div> -->
        </div>

        {{-- Stat bar --}}
        <div class="hero-stat-bar">
            <div class="hero-stat-item">
                <span class="hero-stat-val">₹10 Cr+</span>
                <span class="hero-stat-lbl">Funds Raised</span>
            </div>
            <div class="hero-stat-item">
                <span class="hero-stat-val">50,000+</span>
                <span class="hero-stat-lbl">Donors</span>
            </div>
            <div class="hero-stat-item">
                <span class="hero-stat-val">2,000+</span>
                <span class="hero-stat-lbl">Campaigns</span>
            </div>
            <div class="hero-stat-item">
                <span class="hero-stat-val">98.7%</span>
                <span class="hero-stat-lbl">Success Rate</span>
            </div>
        </div>
    </div>
</div>


{{-- ═══ MARQUEE ═══ --}}
<div class="marquee-band">
    <div class="marquee-inner">
        <div class="marquee-row">
            @php $mItems = ['Medical · Education · Disaster · Animal · Child Welfare · Environment', 'Verified by DonateBazaar', 'RBI-Compliant Payments', '80G Tax Benefits', '24×7 Donor Support', '100% Transparent', 'Pan-India Coverage']; @endphp
            @for($r=0;$r<3;$r++)
                @foreach($mItems as $mi)
                    <span class="m-item"><span class="m-dot"></span>{{ $mi }}</span>
                @endforeach
            @endfor
        </div>
    </div>
</div>


{{-- ═══ STICKY FILTER TOOLBAR ═══ --}}
<div class="toolbar-section">
    <div class="toolbar-inner">
        {{-- Category chips --}}
        <div class="cat-chips">
            <a href="{{ url()->current() }}" class="cat-chip {{ !request('category') ? 'active' : '' }}">All</a>
            @foreach($categories ?? [] as $category)
                <a href="{{ url()->current() }}?category={{ $category->slug }}{{ request('search') ? '&search='.urlencode(request('search')) : '' }}{{ request('sort') ? '&sort='.request('sort') : '' }}"
                   class="cat-chip {{ request('category') === $category->slug ? 'active' : '' }}">
                    {{ $category->name }}
                    <span style="font-size:10px;opacity:.7;margin-left:3px">({{ $category->campaigns_count ?? 0 }})</span>
                </a>
            @endforeach
        </div>

        {{-- Sort + Filter + View --}}
        <div class="toolbar-right">
            <span class="results-count">{{ $campaigns->total() ?? $campaigns->count() }} campaigns</span>

            {{-- Filter button --}}
            <button class="filter-trigger-btn" onclick="openFilterModal()" id="filterTriggerBtn">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4h18M7 12h10M11 20h2"/></svg>
                Filters
                <span class="filter-badge" id="filterBadge" style="display:none">0</span>
            </button>

            <form method="GET" action="{{ url()->current() }}" id="sortForm">
                @if(request('search'))<input type="hidden" name="search" value="{{ request('search') }}">@endif
                @if(request('category'))<input type="hidden" name="category" value="{{ request('category') }}">@endif
                <select name="sort" class="sort-select" onchange="document.getElementById('sortForm').submit()">
                    <option value="newest"      {{ request('sort','newest') === 'newest'      ? 'selected' : '' }}>Newest First</option>
                    <option value="ending_soon" {{ request('sort') === 'ending_soon'          ? 'selected' : '' }}>Ending Soon</option>
                    <option value="most_funded" {{ request('sort') === 'most_funded'          ? 'selected' : '' }}>Most Funded</option>
                    <option value="most_donors" {{ request('sort') === 'most_donors'          ? 'selected' : '' }}>Most Donors</option>
                </select>
            </form>

            <div class="view-toggle">
                <button class="view-btn active" id="gridBtn" onclick="setView('grid')" title="Grid view">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
                </button>
                <button class="view-btn" id="listBtn" onclick="setView('list')" title="List view">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
                </button>
            </div>
        </div>
    </div>
</div>


{{-- ═══ MAIN CONTENT ═══ --}}
<section class="campaigns-section">
    <div class="container">
        <div class="sidebar-backdrop" id="sidebarBackdrop" onclick="closeSidebar()"></div>

        <div class="campaigns-layout">

            {{-- ── SIDEBAR ── --}}
            <aside class="sidebar" id="sidebarDrawer">
                <button class="sidebar-close" onclick="closeSidebar()" aria-label="Close filters">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>

                {{-- Quick Stats --}}
                <div class="filter-card">
                    <div class="filter-card-title">Platform Stats</div>
                    <div class="sidebar-stat">
                        <div class="sidebar-stat-icon" style="background:rgba(37,99,235,.1)">
                            <svg viewBox="0 0 24 24" fill="none" stroke="#2563eb " stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
                        </div>
                        <div>
                            <div class="sidebar-stat-num">2,000+</div>
                            <div class="sidebar-stat-lbl">Active Campaigns</div>
                        </div>
                    </div>
                    <div class="sidebar-stat">
                        <div class="sidebar-stat-icon" style="background:rgba(16,185,129,.1)">
                            <svg viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
                        </div>
                        <div>
                            <div class="sidebar-stat-num">50,000+</div>
                            <div class="sidebar-stat-lbl">Generous Donors</div>
                        </div>
                    </div>
                    <div class="sidebar-stat">
                        <div class="sidebar-stat-icon" style="background:rgba(245,158,11,.1)">
                            <svg viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/></svg>
                        </div>
                        <div>
                            <div class="sidebar-stat-num">₹10 Cr+</div>
                            <div class="sidebar-stat-lbl">Total Raised</div>
                        </div>
                    </div>
                </div>

                {{-- Funding progress filter --}}
                <div class="filter-card">
                    <div class="filter-card-title">
                        Funding Progress
                        <span class="filter-card-clear" onclick="clearFundingFilter()">Clear</span>
                    </div>
                    <label class="filter-checkbox">
                        <input type="checkbox" name="funding" value="any" {{ !request('funding') || request('funding') === 'any' ? 'checked' : '' }} onchange="applySidebarFilters()">
                        <span class="filter-checkbox-label">Any progress</span>
                        <span class="filter-checkbox-count">{{ $campaigns->total() ?? '' }}</span>
                    </label>
                    <div class="filter-divider"></div>
                    <label class="filter-checkbox">
                        <input type="checkbox" name="funding" value="lt25" {{ request('funding') === 'lt25' ? 'checked' : '' }} onchange="applySidebarFilters()">
                        <span class="filter-checkbox-label">Under 25% funded</span>
                    </label>
                    <label class="filter-checkbox">
                        <input type="checkbox" name="funding" value="25to75" {{ request('funding') === '25to75' ? 'checked' : '' }} onchange="applySidebarFilters()">
                        <span class="filter-checkbox-label">25% – 75% funded</span>
                    </label>
                    <label class="filter-checkbox">
                        <input type="checkbox" name="funding" value="gt75" {{ request('funding') === 'gt75' ? 'checked' : '' }} onchange="applySidebarFilters()">
                        <span class="filter-checkbox-label">75%+ funded</span>
                    </label>
                    <label class="filter-checkbox">
                        <input type="checkbox" name="funding" value="100" {{ request('funding') === '100' ? 'checked' : '' }} onchange="applySidebarFilters()">
                        <span class="filter-checkbox-label">Fully funded</span>
                    </label>
                </div>

                {{-- Category filter --}}
                <div class="filter-card">
                    <div class="filter-card-title">Categories</div>
                    @foreach($categories ?? [] as $cat)
                    <label class="filter-checkbox">
                        <input type="checkbox" name="cat_sidebar" value="{{ $cat->slug }}" onchange="applySidebarFilters()" {{ request('category') === $cat->slug ? 'checked' : '' }}>
                        <span class="filter-checkbox-label">{{ $cat->name }}</span>
                        <span class="filter-checkbox-count">{{ $cat->campaigns_count ?? '' }}</span>
                    </label>
                    @endforeach
                </div>

                {{-- Trust badge --}}
                <div class="filter-card" style="background:linear-gradient(135deg,rgba(37,99,235,.06),rgba(13,148,136,.06));border-color:rgba(37,99,235,.18)">
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px">
                        <div style="width:36px;height:36px;border-radius:10px;background:rgba(37,99,235,.12);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#2563eb " stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        </div>
                        <div style="font-size:13px;font-weight:700;color:var(--text)">Donor Protection</div>
                    </div>
                    <p style="font-size:12.5px;color:var(--text2);line-height:1.7;font-weight:300">Every campaign on DonateBazaar is verified. If a campaign is found fraudulent, our Donor Protection Fund covers your refund.</p>
                    <a href="{{ url('/about') }}" style="display:inline-flex;align-items:center;gap:5px;font-size:12px;color:var(--accent);font-weight:600;margin-top:12px">
                        Learn more
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </a>
                </div>

            </aside>

            {{-- ── CAMPAIGN GRID ── --}}
            <div>

                {{-- Mobile/tablet drawer toggle --}}
                <button class="sidebar-toggle-btn" onclick="openSidebar()" aria-label="Open filters and stats">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4h18M7 12h10M11 20h2"/></svg>
                    Filters &amp; Stats
                </button>

                {{-- Active filter chips --}}
                <div class="active-filters" id="activeFilters"></div>

                <div class="camp-grid reveal" id="campGrid">

@forelse($campaigns as $index => $campaign)

    @php
        $isExpired =
            $campaign->end_date &&
            $campaign->campaign_state !== 'active' &&
            \Carbon\Carbon::parse($campaign->end_date)->isPast();
    @endphp

    @if($isExpired)
        @continue
    @endif

    @php
        $raised = $campaign->donations_sum_total_amount ?? $campaign->raised_amount ?? 0;
        $goal        = $campaign->goal_amount ?? 0;
        $percentage  = $goal > 0 ? round(($raised / $goal) * 100) : 0;
        $donors      = $campaign->donations_count ?? 0;
        $daysLeft    = isset($campaign->end_date)
            ? max(0, now()->diffInDays($campaign->end_date, false))
            : null;

        $isSpotlight = $index === 0 && !request('search') && !request('category');
        $categorySlug = $campaign->category->slug ?? 'general';

        $isNew    = $campaign->created_at && \Carbon\Carbon::parse($campaign->created_at)->diffInDays(now()) <= 7;
        $isUrgent = $daysLeft !== null && $daysLeft <= 7;
        $isAlmost = $percentage >= 75;
    @endphp

                    @if($isSpotlight)
                    {{-- ═══ SPOTLIGHT CARD ═══ --}}
                    <div class="spotlight-card reveal">
                        <div class="spotlight-body">
                            <div class="spotlight-eyebrow">Featured Campaign</div>
                            <div class="spotlight-title">{{ $campaign->title }}</div>
                            <div class="spotlight-excerpt">{{ Str::limit(strip_tags($campaign->description), 160) }}</div>
                            <div style="font-size:13px;color:rgba(255,255,255,.82);font-family:var(--font);margin-bottom:18px;display:flex;align-items:center;gap:9px;">
                                <span style="width:28px;height:28px;border-radius:50%;background:rgba(255,255,255,.18);display:inline-flex;align-items:center;justify-content:center;font-weight:700;font-size:11px;">{{ strtoupper(substr(($campaign->user->name ?? 'O'),0,1)) }}</span>
                                Started by {{ $campaign->user->name ?? 'a verified organiser' }}
                            </div>
                            <div class="spotlight-stats">
                                <div>
                                    <span class="spotlight-stat-val">₹{{ number_format($raised) }}</span>
                                    <div class="spotlight-stat-lbl">Raised so far</div>
                                </div>
                                <div>
                                    <span class="spotlight-stat-val">{{ number_format($donors) }}</span>
                                    <div class="spotlight-stat-lbl">Donors</div>
                                </div>
                                @if($daysLeft !== null)
                                <div>
                                    <span class="spotlight-stat-val">{{ $daysLeft }}</span>
                                    <div class="spotlight-stat-lbl">Days left</div>
                                </div>
                                @endif
                            </div>
                            <div class="spotlight-progress-track">
                                <div class="spotlight-progress-fill" style="width:0%" data-w="{{ $percentage }}%"></div>
                            </div>
                            <div style="font-size:12px;color:rgba(255,255,255,.6);font-family:var(--font-mono);margin-bottom:24px">
                                {{ $percentage }}% of ₹{{ number_format($goal) }} goal
                            </div>
                            <a href="{{ route('campaign.public', [$categorySlug, $campaign->slug]) }}" class="btn btn-spotlight">
                                Donate Now
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                        <div class="spotlight-img">
                            <img src="{{ asset('storage/' . $campaign->cover_image) }}" alt="{{ $campaign->title }}" loading="lazy">
                        </div>
                    </div>
                    @else
                    {{-- ═══ REGULAR CARD ═══ --}}
                    <div class="camp-card reveal d{{ ($index % 3) + 1 }}"
                         data-cat="{{ $campaign->category->slug ?? 'uncategorized' }}"
                         data-pct="{{ $percentage }}">
                        <div class="camp-img">
                            <img loading="lazy" src="{{ asset('storage/' . $campaign->cover_image) }}" alt="{{ $campaign->title }}">
                            <div class="camp-badge-wrap">
                                <span class="camp-cat-badge">{{ $campaign->category->name ?? 'General' }}</span>
                                <span style="display:flex;flex-direction:column;gap:6px;align-items:flex-end;">
                                    @if($isUrgent)
                                        <span class="camp-status-badge st-urgent">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.3 3.86l-8.5 14.7A2 2 0 003.5 21h17a2 2 0 001.7-3.44l-8.5-14.7a2 2 0 00-3.4 0z"/></svg>
                                            Urgent
                                        </span>
                                    @elseif($isAlmost)
                                        <span class="camp-status-badge st-almost">Almost there</span>
                                    @elseif($isNew)
                                        <span class="camp-status-badge st-new">New</span>
                                    @endif
                                    <span class="camp-verified-badge">Verified</span>
                                </span>
                            </div>
                            </div>
                        <div class="camp-body">
                            <div class="camp-byline">
                                <span class="camp-avatar">{{ strtoupper(substr(($campaign->user->name ?? 'O'),0,1)) }}</span>
                                <span class="camp-org-name">by <b>{{ $campaign->user->name ?? 'Verified organiser' }}</b></span>
                            </div>
                            <h3 class="camp-title">{{ $campaign->title }}</h3>
                            <p class="camp-excerpt">{{ Str::limit(strip_tags($campaign->description), 100) }}</p>

                            <div class="camp-progress-wrap">
                                <div class="camp-progress-track">
                                    <div class="camp-progress-fill" style="width:0%" data-w="{{ $percentage }}%"></div>
                                </div>
                                <div class="camp-meta-row">
                                    <span class="camp-raised">₹{{ number_format($raised) }}</span>
                                    <span class="camp-pct">{{ $percentage }}%</span>
                                </div>
                                <div class="camp-goal">of ₹{{ number_format($goal) }} goal</div>
                            </div>

                            <div class="camp-info-strip">
                                <div class="camp-info-item">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                                    {{ number_format($donors) }} donors
                                </div>
                                <div class="camp-info-sep"></div>
                                @if($daysLeft !== null)
                                <div class="camp-info-item">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                    {{ $daysLeft > 0 ? $daysLeft.'d left' : 'Ends today' }}
                                </div>
                                @endif
                            </div>

                            <div class="camp-donors-line"><b>{{ number_format($donors) }}</b> people came together for this cause</div>

                            <a href="{{ route('campaign.public', [$categorySlug, $campaign->slug]) }}" class="btn btn-accent btn-block">
                                Donate Now
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    </div>
                    @endif

                    @empty
                    <div class="empty-state">
                        <div class="empty-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
                        </div>
                        <div class="empty-title">No stories match yet</div>
                        <p class="empty-sub">We couldn't find a campaign for that search. Try another cause or clear your filters to discover more.</p>
                        <a href="{{ url()->current() }}" class="btn btn-accent" style="margin:24px auto 0;width:fit-content">
                            View All Campaigns
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                    @endforelse

                </div>

                {{-- PAGINATION --}}
 @if ($campaigns->hasPages())
    <div class="pagination-wrap">

        {{-- Previous --}}
        @if ($campaigns->onFirstPage())
            <span class="page-btn disabled">‹</span>
        @else
            <a href="{{ $campaigns->previousPageUrl() }}" class="page-btn">‹</a>
        @endif

        {{-- Page Numbers --}}
        @foreach ($campaigns->links()->elements[0] ?? [] as $page => $url)
            @if ($page == $campaigns->currentPage())
                <span class="page-btn active">{{ $page }}</span>
            @else
                <a href="{{ $url }}" class="page-btn">{{ $page }}</a>
            @endif
        @endforeach

        {{-- Next --}}
        @if ($campaigns->hasMorePages())
            <a href="{{ $campaigns->nextPageUrl() }}" class="page-btn">›</a>
        @else
            <span class="page-btn disabled">›</span>
        @endif

    </div>
@endif
            </div>

        </div>
    </div>
</section>


{{-- ═══ VOICES OF IMPACT (storytelling) ═══ --}}
<section class="voices-section">
    <div class="container">
        <div class="voices-head reveal">
            <div class="eyebrow" style="justify-content:center;color:var(--accent)">Voices of Impact</div>
            <h2 class="voices-title">Real people, <em>real change</em></h2>
            <p class="voices-sub">Every rupee carries a story. Here's what giving looks like from the other side.</p>
        </div>
        <div class="voices-grid">
            <div class="voice-card reveal d1">
                <div class="voice-quote-mark">&ldquo;</div>
                <p class="voice-quote">When my daughter needed heart surgery, I had nowhere to turn. Strangers I'll never meet came together in nine days. We're home, and she's running again.</p>
                <div class="voice-author">
                    <div class="voice-avatar">A</div>
                    <div><div class="voice-name">Anita R.</div><div class="voice-role">Mother · Bengaluru</div></div>
                </div>
                <span class="voice-cause">Medical Emergency</span>
            </div>
            <div class="voice-card reveal d2">
                <div class="voice-quote-mark">&ldquo;</div>
                <p class="voice-quote">The flood took everything but our school rebuilt with help from 200 donors. Watching the kids return to class was the first time I breathed easy in months.</p>
                <div class="voice-author">
                    <div class="voice-avatar">M</div>
                    <div><div class="voice-name">Meera K.</div><div class="voice-role">Teacher · Assam</div></div>
                </div>
                <span class="voice-cause">Disaster Relief</span>
            </div>
            <div class="voice-card reveal d3">
                <div class="voice-quote-mark">&ldquo;</div>
                <p class="voice-quote">I sponsored one girl's education two years ago. Yesterday she video-called to say she topped her class. That small monthly gift rewrote her whole future.</p>
                <div class="voice-author">
                    <div class="voice-avatar">R</div>
                    <div><div class="voice-name">Rahul S.</div><div class="voice-role">Donor · Mumbai</div></div>
                </div>
                <span class="voice-cause">Education</span>
            </div>
        </div>
    </div>
</section>


{{-- ═══ CTA BANNER ═══ --}}
<section class="cta-section">
    <div class="cta-bg-img"><img src="{{ asset('images/banner2.jpeg') }}" alt=""></div>
    <div class="cta-inner">
        <div class="eyebrow reveal" style="justify-content:center;color:#2563eb ">Want to raise funds?</div>
        <h2 class="cta-title reveal d1">Start Your Own <em>Campaign</em></h2>
        <p class="cta-sub reveal d2">Medical emergency, education, disaster relief — whatever the cause, our team verifies and supports your fundraiser from day one.</p>
        <div class="cta-btns reveal d3">
            <a href="/campaign/create" class="btn btn-accent btn-lg">
                Start Fundraiser
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 4v16m8-8H4"/></svg>
            </a>
            <a href="{{ url('/about') }}" class="btn btn-white btn-lg">
                How It Works
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>
</section>

<button class="scroll-top" id="scrollTopBtn" onclick="window.scrollTo({top:0,behavior:'smooth'})" aria-label="Scroll to top">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 15l-6-6-6 6"/></svg>
</button>


@push('scripts')
    @vite(['resources/js/public/campaigns.js'])
@endpush

@endsection


