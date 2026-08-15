@extends('layouts.app')

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,300&family=DM+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">

@push('styles') @vite(['resources/css/public/public-show.css']) @endpush


@php
    $goal       = $campaign->goal_amount   ?? 0;
    $raised     = $campaign->raised_amount ?? 0;
    $percentRaw = $goal > 0 ? round(($raised / $goal) * 100) : 0;
    $percent    = min(100, $percentRaw); // capped, used only for bar widths
    $goalReached = $goal > 0 && $raised >= $goal;
    $donors  = $campaign->donations->count() ?? 0;
    $daysLeft = isset($campaign->end_date) && $campaign->end_date
                ? max(0, now()->diffInDays($campaign->end_date, false))
                : null;

    // ── Raised breakdown — computed in controller to avoid N+1 ──

    // Products
    $products = collect();
    try {
        if (method_exists($campaign, 'products')) {
            $products = $campaign->products;
        }
    } catch (\Throwable $e) {}

    // Updates
    $updates = collect();
    try {
        if (method_exists($campaign, 'updates')) {
            $updates = $campaign->updates->sortBy('created_at');
        }
    } catch (\Throwable $e) {}

    // Video
    $videoUrl   = $campaign->video_url ?? null;
    $videoEmbed = null;
    if ($videoUrl) {
        if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([A-Za-z0-9_\-]+)/', $videoUrl, $m)) {
            $videoEmbed = 'https://www.youtube.com/embed/' . $m[1];
        } elseif (preg_match('/vimeo\.com\/(\d+)/', $videoUrl, $m)) {
            $videoEmbed = 'https://player.vimeo.com/video/' . $m[1];
        }
    }

    // Recent donations for sticky bar ticker
    $latestDonation = $campaign->donations->sortByDesc('created_at')->first();
    $topAmount = $campaign->donations->max('total_amount') ?? 0;
@endphp


{{-- Top scroll progress --}}
<div class="scroll-progress"><div class="scroll-progress-fill" id="scrollProgressFill"></div></div>


{{-- ═══ HERO ═══ --}}
<div class="hero">
    <div class="hero-bg">
        <img src="{{ $campaign->cover_image ? asset('storage/'.$campaign->cover_image) : asset('images/about.jpg') }}"
             alt="{{ $campaign->title }}" loading="eager">
    </div>
    <div class="hero-overlay"></div>
    <div class="hero-grid-lines"></div>

    <div class="hero-float-cards" aria-hidden="true">
        <div class="hero-float-card">
            <div class="fcard-lbl">Raised</div>
            <div class="fcard-val">₹{{ number_format($raised) }}</div>
            <div class="fcard-sub">of ₹{{ number_format($goal) }} goal</div>
        </div>
        <div class="hero-float-card">
            <div class="fcard-lbl">Donors</div>
            <div class="fcard-val">{{ number_format($donors) }}</div>
            <div class="fcard-sub">people contributed</div>
        </div>
        @if($daysLeft !== null)
        <div class="hero-float-card">
            <div class="fcard-lbl">{{ $daysLeft > 0 ? 'Days Left' : 'Campaign' }}</div>
            <div class="fcard-val">{{ $daysLeft > 0 ? $daysLeft : '🎯' }}</div>
            <div class="fcard-sub">{{ $daysLeft > 0 ? 'until campaign ends' : 'Ends today!' }}</div>
        </div>
        @endif
    </div>

    <div class="hero-inner">
        <div class="hero-content">
            <div class="hero-breadcrumb">
                <a href="{{ url('/') }}">Home</a>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                <a href="{{ route('all.campaigns') }}">Campaigns</a>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                <span style="color:rgba(255,255,255,.75)">{{ Str::limit($campaign->title, 40) }}</span>
            </div>

            <div class="hero-cat-pill">{{ $campaign->category->name ?? 'General' }}</div>
            <h1 class="hero-title">{{ $campaign->title }}</h1>

            <div class="hero-meta">
                <span class="hero-pill">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    {{ $campaign->created_at->format('d M Y') }}
                </span>
                <span class="hero-pill">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    By {{ $campaign->user->name ?? 'DonateBazaar' }}
                </span>
                <span class="hero-pill">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                    {{ number_format($donors) }} Donors
                </span>
                @if($daysLeft !== null)
                <span class="hero-pill" style="background:rgba(16,185,129,.15);border-color:rgba(16,185,129,.25)">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    <span style="color:#34d399">{{ $daysLeft > 0 ? $daysLeft . ' days left' : 'Ends today' }}</span>
                </span>
                @endif
                @if($campaign->is_featured)
                <span class="hero-pill" style="background:rgba(245,158,11,.15);border-color:rgba(245,158,11,.3)">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2"><path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                    <span style="color:#fcd34d">Featured</span>
                </span>
                @endif
                @if($campaign->is_urgent)
                <span class="hero-pill" style="background:rgba(239,68,68,.15);border-color:rgba(239,68,68,.3)">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    <span style="color:#a72a2a">Urgent</span>
                </span>
                @endif
                <span class="hero-pill" style="background:rgba(37,99,235,.15);border-color:rgba(37,99,235,.25)">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#93c5fd" stroke-width="2"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    <span style="color:#93c5fd">Verified Campaign</span>
                </span>
            </div>

            <div class="hero-progress-wrap">
                <div class="hero-progress-track">
                    <div class="hero-progress-fill" style="width:{{ $percent }}%"></div>
                </div>
                <div class="hero-progress-meta">
                    <div>
                        <div class="hero-raised">₹{{ number_format($raised) }}</div>
                        <div class="hero-goal">of ₹{{ number_format($goal) }} goal</div>
                    </div>
                    @if($goalReached)
                    <div class="goal-reached-pill">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                        Goal Reached · {{ $percentRaw }}%
                    </div>
                    @else
                    <div class="hero-pct">{{ $percentRaw }}% funded</div>
                    @endif
                </div>
                @if($goalReached)
<div class="overfund-note" style="color:rgba(255,255,255,.55)">
    Goal reached! All additional contributions go toward ongoing support for this cause.
</div>
@else
<div class="overfund-note" style="color:rgba(255,255,255,.42);margin-top:8px;font-size:12px;line-height:1.55;">
    Funds raised beyond the goal will go toward ongoing needs for this cause.
    Donations are eligible for 80G tax deduction.
</div>
@endif
            </div>

            <div class="hero-cta">
                <x-button variant="primary" type="button" class="hero-donate-btn" data-action="scroll-to-donate">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
                    Donate Now
                </x-button>
                <x-button variant="secondary" type="button" class="hero-share-btn" data-action="share-campaign">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
                    Share
                </x-button>
            </div>
        </div>
    </div>
</div>


{{-- ═══ PAGE BODY ═══ --}}
<div class="page-wrap" data-campaign-id="{ $campaign->id }" data-campaign-title="{ $campaign->title }" data-coupon-route="{ route('coupon.validate') }">

    {{-- ════ LEFT COLUMN ════ --}}
    <div class="left-col">

        {{-- ── About ── --}}
        <div class="sec-card reveal">
            <div class="sec-body-pad">
                <div class="eyebrow">About this Campaign</div>
                <h2 class="sec-title">The Story Behind <em>This Cause</em></h2>
                <div class="story story-dropcap">{!! nl2br(e($campaign->description)) !!}</div>

                <div class="mission-chips">
                    <span class="mission-chip chip-verified">
                        <span class="chip-icon"><svg viewBox="0 0 24 24" fill="none" stroke="#1e40af" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/></svg></span>Verified Campaign
                    </span>
                    <span class="mission-chip chip-transparent">
                        <span class="chip-icon"><svg viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></span>100% Transparent
                    </span>
                    <span class="mission-chip chip-secure">
                        <span class="chip-icon"><svg viewBox="0 0 24 24" fill="none" stroke="#1d4ed8" stroke-width="2.5"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg></span>Secure Payments
                    </span>
                    <span class="mission-chip chip-tax">
                        <span class="chip-icon"><svg viewBox="0 0 24 24" fill="none" stroke="#b45309" stroke-width="2.5"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg></span>80G Tax Benefit
                    </span>
                    @if($campaign->location)
                    <span class="mission-chip chip-location">
                        <span class="chip-icon"><svg viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="2.5"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg></span>{{ $campaign->location }}
                    </span>
                    @endif
                    @if($campaign->is_featured)
                    <span class="mission-chip chip-featured">
                        <span class="chip-icon"><svg viewBox="0 0 24 24" fill="none" stroke="#b45309" stroke-width="2.5"><path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg></span>Featured Campaign
                    </span>
                    @endif
                    @if($campaign->is_urgent)
                    <span class="mission-chip chip-urgent">
                        <span class="chip-icon"><svg viewBox="0 0 24 24" fill="none" stroke="#b91c1c" stroke-width="2.5"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg></span>Urgent — Act Now
                    </span>
                    @endif
                    @if($daysLeft !== null && $daysLeft <= 7)
                    <span class="mission-chip chip-ending">
                        <span class="chip-icon"><svg viewBox="0 0 24 24" fill="none" stroke="#b91c1c" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></span>Ending Soon
                    </span>
                    @endif
                </div>
            </div>

            {{-- ── Stats Strip — Total / Cash / Products ── --}}
            <div style="padding:22px 30px 4px;border-top:1px solid var(--border2);">
                <div class="eyebrow" style="margin-bottom:0">Campaign at a Glance</div>
            </div>
            <div class="stats-strip">
                <div class="stat-cell">
                    <span class="stat-val">₹{{ number_format($raised) }}</span>
                    <div class="stat-lbl">Total Raised</div>
                </div>
                <div class="stat-cell stat-money">
                    <span class="stat-val">₹{{ number_format($moneyRaised) }}</span>
                    <div class="stat-lbl">Cash Donations</div>
                </div>
                <div class="stat-cell stat-product">
                    <span class="stat-val">₹{{ number_format($productRaised) }}</span>
                    <div class="stat-lbl">Product Donations</div>
                </div>
            </div>
        </div>

        {{-- ── Video ── --}}
        @if($videoUrl)
        <div class="sec-card reveal d1">
            <div class="sec-body-pad">
                <div class="eyebrow">Campaign Video</div>
                <h2 class="sec-title">Watch the <em>Story</em></h2>
                @if($videoEmbed)
                    <div class="video-wrap">
                        <iframe src="{{ $videoEmbed }}" title="Campaign video" allowfullscreen
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture">
                        </iframe>
                    </div>
                @else
                    <a href="{{ $videoUrl }}" target="_blank" rel="noopener" class="video-link-fallback">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polygon points="10 8 16 12 10 16 10 8"/></svg>
                        Watch Campaign Video
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:12px;height:12px;margin-left:auto;opacity:.5"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6M15 3h6v6M10 14L21 3"/></svg>
                    </a>
                @endif
            </div>
        </div>
        @endif

        {{-- ── Products Left Col ── --}}
        <!-- @if($products->count() > 0)
        <div class="sec-card reveal d1">
            <div class="sec-body-pad">
                <div class="eyebrow">Fundraiser Products</div>
                <h2 class="sec-title">Support by <em>Shopping</em></h2>
                <p class="sec-text" style="margin-bottom:0">Every product purchase goes directly towards the campaign goal.</p>
                <div class="products-grid" style="margin-top:20px">
                    @foreach($products as $product)
                    <div class="product-card-left">
                        <div class="product-card-left-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2zM16 3H8l-2 4h12l-2-4z"/></svg>
                        </div>
                        <div class="product-card-left-name">{{ $product->name }}</div>
                        @if($product->description)
                        <div class="product-card-left-desc">{{ $product->description }}</div>
                        @endif
                        <div class="product-card-left-footer">
                            <span class="product-price">₹{{ number_format($product->price) }}</span>
                            @if(isset($product->stock))
                                @if($product->stock > 0)
                                    <span class="product-qty-badge"><span class="product-status-dot"></span>{{ $product->stock }} left</span>
                                @else
                                    <span class="product-qty-badge out-of-stock">Sold out</span>
                                @endif
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif -->

        {{-- ── Updates ── --}}
        @if($updates->count() > 0)
        <div class="sec-card reveal d2">
            <div class="sec-body-pad">
                <div class="eyebrow">Campaign Updates</div>
                <h2 class="sec-title">Latest <em>Progress</em></h2>
                <p class="sec-text" style="margin-bottom:0">Real-time updates from the campaign team.</p>
                <div class="updates-timeline" style="margin-top:26px">
                    @foreach($updates as $i => $update)
                    <div class="update-item">
                        <div class="update-dot">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                        </div>
                        <div class="update-body">
                            <div class="update-meta">
                                <span class="update-num-badge">Update #{{ $i + 1 }}</span>
                                @if($update->created_at)
                                <span class="update-date">{{ $update->created_at->format('d M Y') }} · {{ $update->created_at->diffForHumans() }}</span>
                                @endif
                            </div>
                            <div class="update-title">{{ $update->title }}</div>
                            @if($update->body)
                            <div class="update-text">{!! nl2br(e($update->body)) !!}</div>
                            @endif
                            @if($update->document)
                            <a href="{{ asset('storage/' . $update->document) }}" target="_blank" rel="noopener" class="update-doc-link">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21.44 11.05l-9.19 9.19a6 6 0 01-8.49-8.49l9.19-9.19a4 4 0 015.66 5.66l-9.2 9.19a2 2 0 01-2.83-2.83l8.49-8.48"/></svg>
                                View Attached Document
                            </a>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        {{-- ── How Donation Works ── --}}
        <div class="dark-strip reveal d1">
            <div class="eyebrow">Simple Process</div>
            <h2 class="sec-title" style="margin-bottom:6px">How Your <em>Donation Works</em></h2>
            <p style="font-size:13px;color:rgba(255,255,255,.42);font-weight:300;line-height:1.7;max-width:500px">Every rupee is tracked from your payment to the final impact report.</p>
            <div class="hiw-steps">
                <div class="hiw-step">
                    <div class="hiw-step-num">STEP 01</div>
                    <div class="hiw-step-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg></div>
                    <div class="hiw-step-title">Choose an Amount</div>
                    <div class="hiw-step-desc">Pick from quick amounts or enter a custom figure. Every rupee matters.</div>
                </div>
                <div class="hiw-step">
                    <div class="hiw-step-num">STEP 02</div>
                    <div class="hiw-step-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg></div>
                    <div class="hiw-step-title">Pay Securely</div>
                    <div class="hiw-step-desc">UPI, card, or net banking — end-to-end encrypted via RBI-compliant gateways.</div>
                </div>
                <div class="hiw-step">
                    <div class="hiw-step-num">STEP 03</div>
                    <div class="hiw-step-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg></div>
                    <div class="hiw-step-title">Track Your Impact</div>
                    <div class="hiw-step-desc">Live updates, photo reports, and your 80G certificate — all in your inbox.</div>
                </div>
            </div>
        </div>

        {{-- ── Impact ── --}}
        <div class="sec-card reveal d2">
            <div class="sec-body-pad">
                <div class="eyebrow">Where Your Money Goes</div>
                <h2 class="sec-title">Tangible <em>Impact</em></h2>
                <div class="impact-grid">
                    <div class="impact-card">
                        <img src="{{ asset('images/donation1 (3).jpg') }}" class="impact-img" alt="Relief Kits" loading="lazy">
                        <div class="impact-body">
                            <div class="impact-title"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2z"/><path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/></svg>Relief Kits</div>
                            <div class="impact-text">Essential food, hygiene supplies, and hope for families in crisis.</div>
                        </div>
                    </div>
                    <div class="impact-card">
                        <img src="{{ asset('images/donation1 (2).jpg') }}" class="impact-img" alt="Medical Care" loading="lazy">
                        <div class="impact-body">
                            <div class="impact-title"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>Medical Care</div>
                            <div class="impact-text">Lifesaving medicines, checkups, and urgent care for those in need.</div>
                        </div>
                    </div>
                    <div class="impact-card">
                        <img src="{{ asset('images/donation1 (1).jpg') }}" class="impact-img" alt="Shelter" loading="lazy">
                        <div class="impact-body">
                            <div class="impact-title"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>Shelter</div>
                            <div class="impact-text">Safe shelter, warmth, and stability for families without a home.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Why DonateBazaar ── --}}
        <div class="sec-card reveal d2">
            <div class="sec-body-pad">
                <div class="eyebrow">6 Reasons of Assurance</div>
                <h2 class="sec-title">Why <em>DonateBazaar?</em></h2>
                @php
                $whys = [
                    ['bg'=>'#fff7ed','color'=>'#ea580c','wi'=>'#ea580c','title'=>'Product Giving',    'desc'=>'Donate products and make your impact tangible.',               'svg'=>'<path d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2z"/><path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/><line x1="12" y1="12" x2="12" y2="16"/><line x1="10" y1="14" x2="14" y2="14"/>'],
                    ['bg'=>'#f0fdf4','color'=>'#16a34a','wi'=>'#16a34a','title'=>'Verified & Trusted', 'desc'=>'100% verified charities via strict multi-step KYC process.',      'svg'=>'<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/>'],
                    ['bg'=>'#eff6ff','color'=>'#2563eb ','wi'=>'#2563eb ','title'=>'Guaranteed Updates','desc'=>'Regular photo and video updates sent directly to you.',            'svg'=>'<polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>'],
                    ['bg'=>'#f0fdfa','color'=>'#0d9488','wi'=>'#0d9488','title'=>'Easy Setup',         'desc'=>'Launch a fundraiser in just a few minutes — no hassle.',          'svg'=>'<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>'],
                    ['bg'=>'#fff1f2','color'=>'#dc2626','wi'=>'#dc2626','title'=>'Secure & Private',   'desc'=>'256-bit SSL encrypted payments, your data is never stored.',      'svg'=>'<rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/>'],
                    ['bg'=>'#f0f9ff','color'=>'#0284c7','wi'=>'#0284c7','title'=>'24×7 Support',       'desc'=>'Our dedicated team is always here to help you succeed.',          'svg'=>'<path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/>'],
                ];
                @endphp
                <div class="why-grid" style="margin-top:4px">
                    @foreach($whys as $w)
                    <div class="why-item" style="--wi-color:{{ $w['wi'] }}">
                        <div class="why-icon" style="background:{{ $w['bg'] }}">
                            <svg viewBox="0 0 24 24" fill="none" stroke="{{ $w['color'] }}" stroke-width="1.8">{!! $w['svg'] !!}</svg>
                        </div>
                        <div>
                            <div class="why-name">{{ $w['title'] }}</div>
                            <div class="why-desc">{{ $w['desc'] }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ── FAQ ── --}}
        <div class="sec-card reveal d3">
            <div class="sec-body-pad">
                <div class="eyebrow">FAQs</div>
                <h2 class="sec-title">Frequently Asked <em>Questions</em></h2>
                @php
                $faqs = [
                    ['How will my donation be used?','Your donation directly supports relief activities — food, medical care, shelter, and essential supplies for people affected. Funds are disbursed in milestone-based tranches for full accountability.'],
                    ['Is my donation secure?','Absolutely. All donations use 256-bit SSL encryption and are processed through RBI-authorised, PCI-DSS compliant payment gateways. We never store your card details on our servers.'],
                    ['How do I get my 80G tax certificate?','Your 80G certificate is automatically generated and emailed to you within 24 hours of donating. It is also always available in your donor dashboard.'],
                    ['Can I track how my donation is used?','Yes. Campaign creators post regular photo and video updates. You receive notifications for every milestone and can see a full disbursement log in real time.'],
                    ['Can I set up recurring donations?','Yes — choose Weekly or Monthly giving from the donate card. No long-term commitment; you can cancel anytime from your dashboard.'],
                ];
                @endphp
                <div class="faq-list" style="margin-top:4px">
                    @foreach($faqs as $i => $faq)
                    <div class="faq-item" id="faq-{{ $i }}">
                        <div class="faq-q" data-action="toggle-faq" data-id="{{ $i }}">
                            <span class="faq-q-text">{{ $faq[0] }}</span>
                            <div class="faq-chevron">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                            </div>
                        </div>
                        <div class="faq-answer" id="faq-ans-{{ $i }}">
                            <div class="faq-answer-inner">{{ $faq[1] }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ── Recent Donors ── --}}
        @if($campaign->donations && $campaign->donations->count() > 0)
        <div class="sec-card reveal d3">
            <div class="sec-body-pad">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:18px;">
                    <div>
                        <div class="eyebrow">Community Support</div>
                        <h2 class="sec-title" style="margin-bottom:0">Recent <em>Donors</em></h2>
                    </div>
                    <div style="width:46px;height:46px;border-radius:13px;background:rgba(37,99,235,.1);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="1.8" width="22" height="22"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
                    </div>
                </div>
                <div style="display:flex;flex-direction:column;gap:10px;">
                    @foreach($campaign->donations->where('payment_status','completed')->sortByDesc('created_at')->take(10) as $donation)
                    <div class="donor-row{{ $topAmount > 0 && $donation->total_amount == $topAmount ? ' top-supporter' : '' }}">
                        <div class="donor-row-left">
<div class="donor-avatar-new"
     style="{{ $donation->donation_type === 'product' 
               ? 'background:linear-gradient(135deg,var(--orange),var(--orange2))' 
               : '' }}">

    @php
        $user = $donation->user;
        $avatar = $user?->profile_photo_path ?? $user?->avatar ?? null;
    @endphp

    @if($avatar)
        <img src="{{ Storage::url($avatar) }}"
             alt="{{ $donation->donor_name }}"
             style="width:100%;height:100%;object-fit:cover;border-radius:inherit;">
    @else
        {{ strtoupper(substr($donation->donor_name ?? 'A', 0, 1)) }}
    @endif
</div>
                            <div>
                                <div class="donor-info-name">{{ $donation->is_anonymous ? 'Anonymous Donor' : ($donation->donor_name ?? 'Anonymous') }}</div>
                                <div class="donor-info-time">{{ $donation->created_at->diffForHumans() }}</div>
                                @if($topAmount > 0 && $donation->total_amount == $topAmount)
                                <span class="top-supporter-badge">
                                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M5 16L3 5l5.5 5L12 4l3.5 6L21 5l-2 11H5zm0 2h14v2H5v-2z"/></svg>
                                    Top Supporter
                                </span>
                                @endif
                                <span class="donor-type-badge {{ $donation->donation_type === 'product' ? 'donor-type-product' : 'donor-type-money' }}">
                                    {{ $donation->donation_type === 'product' ? 'Product' : 'Cash' }}
                                </span>
                            </div>
                        </div>
                        <div>
                            <div class="donor-amount">₹{{ number_format($donation->total_amount, 2) }}</div>
                            <div class="donor-contrib">Contribution</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

    </div>{{-- /left-col --}}


    {{-- ════ RIGHT COLUMN ════ --}}
    <div class="right-col">

        {{-- ══════════════════════════════════
             ★ NEW DONATE CARD
        ══════════════════════════════════ --}}
        <div class="donate-card-new reveal-right" id="donateCardEl">

            {{-- Dark header with progress --}}
            <div class="donate-head-new">
                <div class="donate-head-new-title">Support This Cause</div>
                <div class="donate-raised-row-new">
                    <span class="donate-raised-new">₹{{ number_format($raised) }}</span>
                    <span class="donate-goal-new">of ₹{{ number_format($goal) }}</span>
                </div>
                <div class="donate-prog-track-new">
                    <div class="donate-prog-fill-new" style="width:{{ $percent }}%"></div>
                </div>
                <div class="donate-prog-meta-new">
                    @if($goalReached)
                    <span class="donate-pct-new" style="color:var(--accent2)"> Goal Reached · {{ $percentRaw }}%</span>
                    @else
                    <span class="donate-pct-new">{{ $percentRaw }}% funded</span>
                    @endif
                    <span class="donate-donors-count-new">· {{ $donors }} donors</span>
                </div>
@if($goalReached)
<div class="overfund-note" style="color:rgba(255,255,255,.5);position:relative;z-index:1;">
    Goal reached! Extra donations go toward continued support for this cause.
</div>
@else
<div class="overfund-note" style="color:rgba(255,255,255,.42);position:relative;z-index:1;font-size:11.5px;line-height:1.55;margin-top:6px;">
    Funds beyond the goal go toward ongoing needs for this cause.
    All donations qualify for 80G tax deduction.
</div>
@endif
                {{-- Breakdown pills --}}
                @if($moneyRaised > 0 || $productRaised > 0)
                <div class="donate-breakdown">
                    @if($moneyRaised > 0)
                    <span class="donate-breakdown-pill dbp-money">
                         Cash ₹{{ number_format($moneyRaised) }}
                    </span>
                    @endif
                    @if($productRaised > 0)
                    <span class="donate-breakdown-pill dbp-product">
                         Products ₹{{ number_format($productRaised) }}
                    </span>
                    @endif
                </div>
                @endif
            </div>

            {{-- Main tabs --}}
            <div class="main-donate-tabs">
                <button type="button" id="tabProducts"
                        class="main-donate-tab {{ $products->count() > 0 ? 'active-products' : '' }}"
                        data-action="switch-main-tab" data-tab="products">Donate Products</button>
                <button type="button" id="tabMoney"
                        class="main-donate-tab {{ $products->count() === 0 ? 'active-money' : '' }}"
                        data-action="switch-main-tab" data-tab="money">Donate Money</button>
            </div>

            {{-- ─── PRODUCTS PANEL ─── --}}
            <div id="panelProducts" style="{{ $products->count() === 0 ? 'display:none' : '' }}">
                <div class="panel-products">
                    @if($products->count() > 0)
                    <div class="dp-cart-bar" id="dpCartBar">
                        <span class="dp-cart-bar-items" id="dpCartItems">0 items selected</span>
                        <span>·</span>
                        <span style="font-family:var(--font-mono);font-weight:700;" id="dpCartTotal">₹0</span>
                        <span class="dp-cart-clear" data-action="clear-product-cart">Clear all</span>
                    </div>

                    <div class="dp-grid" id="dpGrid">
                        @foreach($products as $idx => $product)
                        @php
                            $qtyNeeded = max(0, ($product->remaining_quantity ?? 0) - ($product->reservations->where('expires_at', '>', now())->count()));
                            $totalQty  = $product->quantity ?? max($qtyNeeded, 1000);
                            $soldPct   = $totalQty > 0 ? min(100, round((($totalQty - $qtyNeeded) / $totalQty) * 100)) : 0;
                            $isFirst   = $idx === 0;
                            $isPopular = $idx === 2;
                        @endphp
                        <div class="dp-card"
                             id="dpCard_{{ $product->id }}"
                             data-id="{{ $product->id }}"
                             data-price="{{ $product->price }}"
                             data-name="{{ $product->name }}"
                             style="{{ $idx >= 6 ? 'display:none' : '' }}"
                             data-hidden="{{ $idx >= 6 ? '1' : '0' }}">

                            @if($isFirst)
                            <div class="dp-badge dp-badge-impactful visible">Most Impactful</div>
                            @elseif($isPopular)
                            <div class="dp-badge dp-badge-popular visible">Popular</div>
                            @endif

                            <div class="dp-img-wrap">
@php
    $imgUrl = $product->image
        ? asset('storage/' . $product->image)
        : optional($product->categoryProduct)->image_url;
@endphp
@if($imgUrl)
<img src="{{ $imgUrl }}"
     alt="{{ $product->name }}"
     class="dp-img" loading="lazy"
     onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
<div class="dp-img-placeholder" style="display:none;">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><path d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2zM16 3H8l-2 4h12l-2-4z"/></svg>
</div>
@else
<div class="dp-img-placeholder">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><path d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2zM16 3H8l-2 4h12l-2-4z"/></svg>
</div>
@endif
                            </div>

                            <div id="dpAddWrap_{{ $product->id }}">
                                <x-button variant="primary" type="button" class="dp-add-btn" id="dpAddBtn_{{ $product->id }}"
                                        data-action="add-to-cart" data-id="{{ $product->id }}">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                    Add
                                </x-button>
                            </div>
                            <div class="dp-counter" id="dpCounter_{{ $product->id }}">
                                <button type="button" class="dp-minus" data-action="change-qty" data-id="{{ $product->id }}" data-delta="-1">−</button>
                                <span class="dp-count" id="dpCount_{{ $product->id }}">1</span>
                                <button type="button" class="dp-plus"  data-action="change-qty" data-id="{{ $product->id }}" data-delta="1">+</button>
                            </div>

                            <div class="dp-info">
                                <div class="dp-name" data-action="toggle-dp-expand" data-id="{{ $product->id }}">
                                    <span class="dp-name-text">{{ $product->name }}</span>
                                    @if($product->description)
                                    <svg class="dp-expand-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                                    @endif
                                </div>
                                @if($product->description)
                                <div class="dp-expanded-desc">{{ $product->description }}</div>
                                @endif
                                @if($qtyNeeded > 0)
                                <div class="dp-qty-row">
                                    <div class="dp-qty-label">{{ number_format($qtyNeeded) }} remaining</div>
                                    <div class="dp-qty-track"><div class="dp-qty-fill" style="width:{{ $soldPct }}%"></div></div>
                                </div>
                                @endif
                                <div class="dp-price">₹{{ number_format($product->price) }}/unit</div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    @if($products->count() > 6)
                    <button type="button" class="dp-load-more" id="dpLoadMore" data-action="load-more-products">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                        Show {{ $products->count() - 6 }} more products
                    </button>
                    @endif

                    <form id="productDonateForm" action="{{ route('donate.redirect', $campaign->id) }}" method="POST" style="display:none">
                        @csrf
                        <input type="hidden" name="amount" id="productDonateAmount">
                        <input type="hidden" name="product_ids" id="productDonateIds">
                        <input type="hidden" name="product_qtys" id="productDonateQtys">
                        <input type="hidden" name="donation_type" value="products">
                    </form>

                    @else
                    <div class="empty-state">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2zM16 3H8l-2 4h12l-2-4z"/></svg>
                        <p>No products available for this campaign.</p>
                    </div>
                    @endif
                </div>

                @if($products->count() > 0)
                <div style="padding:0 14px 14px;">
                    <x-button variant="primary" type="button" id="dpDonateBtn" disabled data-action="submit-product-donation">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
                        <span id="dpDonateBtnText">Select Products to Donate</span>
                    </x-button>
                </div>
                @endif
            </div>{{-- /panelProducts --}}


            {{-- ─── MONEY PANEL ─── --}}
            <div id="panelMoney" style="{{ $products->count() > 0 ? 'display:none' : '' }}">
                <div class="panel-money">
                    <div class="freq-tabs-new">
                        <button type="button" class="freq-tab-new ft-once active" data-action="switch-freq" data-freq="once">One-time</button>
                        <button type="button" class="freq-tab-new ft-weekly" data-action="switch-freq" data-freq="weekly">Weekly</button>
                        <button type="button" class="freq-tab-new ft-monthly" data-action="switch-freq" data-freq="monthly">Monthly</button>
                    </div>
                    <div class="freq-banner-new freq-banner-weekly-new" id="mFreqWeekly">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4m0 4h.01"/></svg>
                        Charged automatically <strong style="display:inline">&nbsp;every week</strong>. Cancel anytime.
                    </div>
                    <div class="freq-banner-new freq-banner-monthly-new" id="mFreqMonthly">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4m0 4h.01"/></svg>
                        Charged automatically <strong style="display:inline">&nbsp;every month</strong>. Cancel anytime.
                    </div>

                    @auth
                    @php
                        $activeSub = \App\Models\RecurringDonation::where('user_id', auth()->id())
                            ->where('campaign_id', $campaign->id)->where('status','active')->first();
                    @endphp
                    @if($activeSub)
                    <div class="existing-sub-new">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <div>You have an active <strong>{{ $activeSub->frequency }}</strong> donation of <strong>₹{{ number_format($activeSub->amount) }}</strong>. Next billing: {{ $activeSub->next_billing_date?->format('d M Y') ?? 'Soon' }}.</div>
                    </div>
                    @endif
                    @endauth

                    <div class="amt-grid-new">
                        @foreach([100,500,1000,2000,5000,10000,20000,50000,100000] as $amt)
                        <x-button variant="primary" type="button" class="amt-btn-new" data-action="pick-amt" data-amt="{{ $amt }}">
                            ₹{{ $amt >= 1000 ? number_format($amt/1000).'K' : $amt }}
                        </x-button>
                        @endforeach
                    </div>

                    <div class="impact-preview-new" id="impactPreviewNew" aria-live="polite">
                        <strong id="impactHeadNew">Your impact</strong>
                        <span id="impactTxtNew"></span>
                    </div>

                    <div id="mFormOnce">
                        @if(session('error'))
                        <div class="alert-error">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            {{ session('error') }}
                        </div>
                        @endif
                        <form action="{{ route('donate.redirect', $campaign->id) }}" method="POST"
                              id="donateFormOnce">
                            @csrf
                            <input type="number" id="amtOnce" name="amount"
                                   placeholder="₹ Enter custom amount"
                                   required min="1" max="500000" step="1"
                                   inputmode="decimal" autocomplete="off"
                                   class="custom-input-new" data-input-action="sync-amt" data-type="once">

                            <div style="display:flex;gap:8px;margin-top:10px;">
                                <input type="text" id="couponCode" name="coupon_code"
                                       placeholder="Coupon code (optional)" autocomplete="off"
                                       style="flex:1;height:48px;border-radius:12px;border:1.5px solid rgba(0,0,0,.12);background:#fff;padding:0 14px;font-size:14px;color:#0f1117;outline:none;text-transform:uppercase;">
                                <button type="button" data-action="apply-coupon"
                                        style="height:48px;padding:0 18px;border:none;border-radius:12px;background:#0f1117;color:#fff;font-weight:600;font-size:13px;cursor:pointer;">
                                    Apply
                                </button>
                            </div>
                            <div id="couponMsg" style="font-size:12.5px;margin-top:6px;min-height:16px;" aria-live="polite"></div>

                            <x-button variant="primary" type="submit">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
                                Donate Now
                            </x-button>
                        </form>

                    </div>

                    <div id="mFormWeekly" style="display:none">
                        @auth
                        <form action="{{ route('recurring.store', $campaign->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="frequency" value="weekly">
                            <input type="number" id="amtWeekly" name="amount" placeholder="₹ Amount per week" required min="10" inputmode="decimal" class="custom-input-new" data-input-action="sync-amt" data-type="weekly">
                            <x-button variant="primary" type="submit">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M17 1l4 4-4 4"/><path d="M3 11V9a4 4 0 014-4h14"/><path d="M7 23l-4-4 4-4"/><path d="M21 13v2a4 4 0 01-4 4H3"/></svg>
                                Start Weekly Donation
                            </x-button>
                        </form>
                        <span class="cancel-lnk">No commitment — <a onclick="alert('Cancel anytime from My Dashboard → Recurring Donations.')">cancel anytime</a></span>
                        @else
                        <input type="number" placeholder="₹ Amount per week" class="custom-input-new" disabled>
                        <div class="login-note-new">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4m0 4h.01"/></svg>
                            Please <a href="{{ route('login') }}">log in</a> to set up recurring donations.
                        </div>
                        @endauth
                    </div>

                    <div id="mFormMonthly" style="display:none">
                        @auth
                        <form action="{{ route('recurring.store', $campaign->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="frequency" value="monthly">
                            <input type="number" id="amtMonthly" name="amount" placeholder="₹ Amount per month" required min="10" inputmode="decimal" class="custom-input-new" data-input-action="sync-amt" data-type="monthly">
                            <x-button variant="primary" type="submit">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M17 1l4 4-4 4"/><path d="M3 11V9a4 4 0 014-4h14"/><path d="M7 23l-4-4 4-4"/><path d="M21 13v2a4 4 0 01-4 4H3"/></svg>
                                Start Monthly Donation
                            </x-button>
                        </form>
                        <span class="cancel-lnk">No commitment — <a onclick="alert('Cancel anytime from My Dashboard → Recurring Donations.')">cancel anytime</a></span>
                        @else
                        <input type="number" placeholder="₹ Amount per month" class="custom-input-new" disabled>
                        <div class="login-note-new">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4m0 4h.01"/></svg>
                            Please <a href="{{ route('login') }}">log in</a> to set up recurring donations.
                        </div>
                        @endauth
                    </div>
                </div>

                <div class="money-panel-foot">
                    <div class="trust-row-new">
                        <span class="trust-item-new"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>Secure</span>
                        <span class="trust-sep-new"></span>
                        <span class="trust-item-new"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>Verified</span>
                        <span class="trust-sep-new"></span>
                        <span class="trust-item-new">80G Eligible</span>
                        <span class="trust-sep-new"></span>
                        <span class="trust-item-new">RBI Compliant</span>
                    </div>
                </div>
            </div>{{-- /panelMoney --}}

        </div>{{-- /donate-card-new --}}

        {{-- ── Campaign Details Card ── --}}
        <div class="share-card reveal-right d1">
            <div class="share-card-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                Campaign Details
            </div>
            <div style="display:flex;flex-direction:column;gap:10px;">
                @if($campaign->start_date || $campaign->end_date)
                <div class="details-row">
                    <svg viewBox="0 0 24 24" fill="none" stroke="var(--text3)" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    <span>
                        @if($campaign->start_date){{ \Carbon\Carbon::parse($campaign->start_date)->format('d M Y') }}@endif
                        @if($campaign->start_date && $campaign->end_date) → @endif
                        @if($campaign->end_date){{ \Carbon\Carbon::parse($campaign->end_date)->format('d M Y') }}@endif
                    </span>
                </div>
                @endif
                @if($campaign->location)
                <div class="details-row">
                    <svg viewBox="0 0 24 24" fill="none" stroke="var(--text3)" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    <span>{{ $campaign->location }}</span>
                </div>
                @endif
                <div class="details-row">
                    <svg viewBox="0 0 24 24" fill="none" stroke="var(--text3)" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    <span>By {{ $campaign->user->name ?? 'DonateBazaar' }}</span>
                </div>
                <div class="details-row">
                    <svg viewBox="0 0 24 24" fill="none" stroke="var(--text3)" stroke-width="2"><path d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/></svg>
                    <span>{{ $campaign->category->name ?? 'General' }}</span>
                </div>
                @if($updates->count() > 0)
                <div class="details-row">
                    <svg viewBox="0 0 24 24" fill="none" stroke="var(--text3)" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    <span>{{ $updates->count() }} update{{ $updates->count() !== 1 ? 's' : '' }} posted</span>
                </div>
                @endif
            </div>

            @php
                $followerCount = $campaign->followers_count;
                $isFollowing = auth()->check() ? $campaign->followers->contains('id', auth()->id()) : false;
            @endphp

            @auth
            <form method="POST" action="{{ route('campaign.follow', $campaign) }}">
                @csrf
                <x-button variant="primary" type="submit" class="btn-follow {{ $isFollowing ? 'is-following' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    {{ $isFollowing ? 'Following' : 'Follow' }}
                </x-button>
            </form>
            @else
            <a href="{{ route('login') . '?redirect=' . urlencode(url()->current()) }}" class="btn-follow">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                Follow
            </a>
            @endauth
            <div class="follow-count">{{ $followerCount }} follower{{ $followerCount !== 1 ? 's' : '' }}</div>
            <br>
            </div>{{-- /Campaign Details card --}}

        {{-- ── Share ── --}}
        <div class="share-card reveal-right d2">
            <div class="share-card-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 12v8a2 2 0 002 2h12a2 2 0 002-2v-8"/><polyline points="16 6 12 2 8 6"/><line x1="12" y1="2" x2="12" y2="15"/></svg>
                Spread the Word
            </div>
            <div class="share-social">
                <x-button variant="primary" type="button" class="share-soc-btn s-wa" data-action="share-to" data-network="whatsapp" aria-label="Share on WhatsApp">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.71.306 1.263.489 1.694.626.712.227 1.36.195 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.245-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.885-9.885 9.885M20.52 3.449C18.24 1.245 15.24 0 12.045 0 5.463 0 .104 5.358.101 11.942c0 2.096.547 4.142 1.588 5.945L0 24l6.237-1.633a11.9 11.9 0 005.808 1.48h.002c6.583 0 11.943-5.36 11.946-11.943 0-3.18-1.235-6.17-3.473-8.425"/></svg>
                    WhatsApp
                </x-button>
                <x-button variant="primary" type="button" class="share-soc-btn s-fb" data-action="share-to" data-network="facebook" aria-label="Share on Facebook">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M22 12a10 10 0 10-11.56 9.88v-6.99H7.9V12h2.54V9.8c0-2.5 1.49-3.89 3.78-3.89 1.09 0 2.24.2 2.24.2v2.46h-1.26c-1.24 0-1.63.77-1.63 1.56V12h2.78l-.44 2.89h-2.34v6.99A10 10 0 0022 12z"/></svg>
                    Facebook
                </x-button>
                <x-button variant="primary" type="button" class="share-soc-btn s-x" data-action="share-to" data-network="x" aria-label="Share on X">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24h-6.66l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    X
                </x-button>
                <x-button variant="primary" type="button" class="share-soc-btn s-copy" data-action="copy-link" aria-label="Copy campaign link">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 007.54.54l3-3a5 5 0 00-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 00-7.54-.54l-3 3a5 5 0 007.07 7.07l1.71-1.71"/></svg>
                    Copy
                </x-button>
            </div>
            <!-- <div class="share-copy-row" onclick="copyLink()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h12v4M4 4v12h12M20 4v16H4"/></svg>
                <span id="shareCopyUrl">{{ url()->current() }}</span>
            </div> -->
        </div>

        {{-- ── Ask Update ── --}}
        <div class="action-card reveal-right d3">
            <h4>Want a Campaign Update?</h4>
            <p>Ask the campaign creator for the latest news, photos, or impact reports directly.</p>
            <a href="{{ route('contact', ['campaign' => $campaign->title]) }}" class="action-btn">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
                Ask for Update
            </a>
        </div>

    </div>{{-- /right-col --}}

</div>{{-- /page-wrap --}}


{{-- ═══ STICKY BOTTOM BAR ═══ --}}
<div class="sticky-donate-bar" id="stickyBar">
    <div class="sdb-ticker">
        <div class="sdb-ticker-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
        </div>
        <div class="sdb-ticker-text" id="sdbText">
            @if($latestDonation)
                <strong>₹{{ number_format($latestDonation->total_amount) }}</strong> donated by
                {{ $latestDonation->is_anonymous ? 'Anonymous' : ($latestDonation->donor_name ?? 'Anonymous') }}
                · {{ $latestDonation->created_at->diffForHumans() }}
            @else
                Be the <strong>first donor</strong> — make an impact today!
            @endif
        </div>
    </div>
    <div class="sdb-right">
        <x-button variant="ghost" type="button" class="sdb-btn" data-action="scroll-to-donate">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
            <span id="sdbBtnLabel">Donate Now</span>
        </x-button>
        <x-button variant="ghost" type="button" class="sdb-share-btn" data-action="share-campaign" aria-label="Share campaign">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
        </x-button>
    </div>
</div>

<button class="scroll-top" id="scrollTopBtn" data-action="scroll-top" aria-label="Scroll to top">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 15l-6-6-6 6"/></svg>
</button>


@push('scripts')
@vite(['resources/js/public/show.js'])
@endpush
@endsection