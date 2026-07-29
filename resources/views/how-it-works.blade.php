@extends('layouts.app')

@section('content')

@push('styles') @vite(['resources/css/public/how-it-works.css']) @endpush


{{-- ═══ HERO ═══ --}}
<div class="hiw-hero">
    <div class="hiw-hero-bg">
        <img src="{{ asset('images/about.jpg') }}" alt="How It Works" loading="eager">
    </div>
    <div class="hiw-hero-overlay"></div>
    <div class="hiw-hero-grid"></div>

    {{-- Animated concentric rings --}}
    <div class="hiw-hero-rings">
        <div class="hiw-ring"></div>
        <div class="hiw-ring"></div>
        <div class="hiw-ring"></div>
        <div class="hiw-ring"></div>
        <div class="hiw-ring"></div>
    </div>

    <div class="hiw-hero-inner">
        <div class="hiw-hero-content">
            <div class="hiw-hero-pill">
                <span class="hiw-pill-dot"></span>
                100% Verified · Transparent · Secure
            </div>
            <h1 class="hiw-hero-title">
                How <em>DonateBazaar</em><br>Actually Works
            </h1>
            <p class="hiw-hero-desc">
                Every rupee is tracked from the moment you donate to the final impact report.
                Learn how we keep donors and fundraisers safe, transparent, and accountable.
            </p>
            <div class="hiw-hero-btns">
                <a href="{{ route('all.campaigns') }}" class="btn btn-accent btn-lg">
                    Browse Campaigns
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
                <a href="{{ route('campaign.create') }}" class="btn btn-white btn-lg">
                    Start Fundraiser
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 4v16m8-8H4"/></svg>
                </a>
            </div>
        </div>

        <div class="hiw-stat-bar">
            @foreach($stats as $s)
            <div class="hiw-stat-item">
                <span class="hiw-stat-val">{{ $s['val'] }}</span>
                <span class="hiw-stat-lbl">{{ $s['lbl'] }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>


{{-- ═══ MARQUEE ═══ --}}
<div class="marquee-band">
    <div class="marquee-inner">
        <div class="marquee-row">
            @php $mItems = ['Verified Campaigns','256-bit SSL Payments','80G Tax Benefits','Real-time Tracking','Donor Protection Fund','24×7 Support','RBI-Compliant','Product Giving','Pan-India Coverage']; @endphp
            @for($r=0;$r<3;$r++)
                @foreach($mItems as $mi)
                    <span class="m-item"><span class="m-dot"></span>{{ $mi }}</span>
                @endforeach
            @endfor
        </div>
    </div>
</div>


{{-- ═══ STICKY TABS ═══ --}}
<div class="tabs-section">
    <div class="tabs-inner">
        <button class="hiw-tab active" id="tab-donors" onclick="switchTab('donors')">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
            For Donors
        </button>
        <button class="hiw-tab" id="tab-fundraisers" onclick="switchTab('fundraisers')">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
            For Fundraisers
        </button>
    </div>
</div>


{{-- ═══ DONORS TAB PANE ═══ --}}
<div class="hiw-tab-pane active" id="pane-donors">

    {{-- Steps --}}
    <section class="steps-section">
        <div class="container">
            <div class="steps-header reveal">
                <div class="eyebrow">4 Simple Steps</div>
                <h2 class="section-title">How to <em>Donate</em></h2>
                <p style="font-size:15px;color:var(--text2);font-weight:300;line-height:1.75;max-width:480px;margin:0 auto">From choosing a cause to tracking your impact — donating on DonateBazaar takes less than two minutes.</p>
            </div>

            <div class="steps-grid">
                @foreach($donorSteps as $i => $step)
                <div class="step-card reveal d{{ $i + 1 }}">
                    <div class="step-number-wrap">
                        <div class="step-icon-ring" style="background:{{ $step['bg'] }};color:{{ $step['color'] }}">
                            @if($step['icon'] === 'search')
                                <svg viewBox="0 0 24 24" fill="none" stroke="{{ $step['color'] }}" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                            @elseif($step['icon'] === 'heart')
                                <svg viewBox="0 0 24 24" fill="none" stroke="{{ $step['color'] }}" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
                            @elseif($step['icon'] === 'credit-card')
                                <svg viewBox="0 0 24 24" fill="none" stroke="{{ $step['color'] }}" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                            @elseif($step['icon'] === 'activity')
                                <svg viewBox="0 0 24 24" fill="none" stroke="{{ $step['color'] }}" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                            @endif
                        </div>
                        <div class="step-num-badge">{{ $step['number'] }}</div>
                    </div>
                    <div class="step-title">{{ $step['title'] }}</div>
                    <div class="step-desc">{{ $step['desc'] }}</div>
                    <div class="step-arrow">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Donation Journey --}}
    <section class="journey-section">
        <div class="container">
            <div class="journey-header reveal">
                <div class="eyebrow">Full Transparency</div>
                <h2 class="section-title">Where Your <em>Rupee Goes</em></h2>
                <p>We track every rupee from your payment gateway right to the beneficiary's hands — and report back to you at every step.</p>
            </div>

            <div class="journey-timeline reveal d1">
                @php
                $journeySteps = [
                    ['icon'=>'credit-card','num'=>'Step 1','title'=>'Payment Initiated','desc'=>'You donate via UPI, card, or net banking. 256-bit SSL protects every transaction.'],
                    ['icon'=>'shield','num'=>'Step 2','title'=>'Funds Secured','desc'=>'Money is held in an escrow-like trust account — never directly with the fundraiser.'],
                    ['icon'=>'check-circle','num'=>'Step 3','title'=>'Verified &amp; Disbursed','desc'=>'Our team verifies receipts and bills, then releases funds in milestone-based tranches.'],
                    ['icon'=>'activity','num'=>'Step 4','title'=>'Impact Reported','desc'=>'Fundraiser uploads photo/video proof. You get a full report and your 80G certificate.'],
                ];
                @endphp
                @foreach($journeySteps as $js)
                <div class="journey-step">
                    <div class="journey-dot">
                        @if($js['icon']==='credit-card')
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                        @elseif($js['icon']==='shield')
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        @elseif($js['icon']==='check-circle')
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        @elseif($js['icon']==='activity')
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                        @endif
                    </div>
                    <div class="journey-step-content">
                        <div class="journey-step-num">{{ $js['num'] }}</div>
                        <div class="journey-step-title">{!! $js['title'] !!}</div>
                        <div class="journey-step-desc">{{ $js['desc'] }}</div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="rupee-flow reveal d2">
                @php $rfItems = ['Your Payment','→','Secure Escrow','→','Milestone Verification','→','Disbursement','→','Impact Report + 80G']; @endphp
                @foreach($rfItems as $rfi)
                    @if($rfi === '→')
                        <span class="rf-arrow">→</span>
                    @else
                        <div class="rf-item">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                            {{ $rfi }}
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </section>

</div>{{-- /donors pane --}}


{{-- ═══ FUNDRAISERS TAB PANE ═══ --}}
<div class="hiw-tab-pane" id="pane-fundraisers">

    <section class="steps-section">
        <div class="container">
            <div class="steps-header reveal">
                <div class="eyebrow">4 Simple Steps</div>
                <h2 class="section-title">How to <em>Fundraise</em></h2>
                <p style="font-size:15px;color:var(--text2);font-weight:300;line-height:1.75;max-width:480px;margin:0 auto">From idea to live campaign in under 5 minutes. Our team handles verification, compliance, and support so you can focus on your cause.</p>
            </div>

            <div class="steps-grid">
                @foreach($fundraiserSteps as $i => $step)
                <div class="step-card reveal d{{ $i + 1 }}">
                    <div class="step-number-wrap">
                        <div class="step-icon-ring" style="background:{{ $step['bg'] }};color:{{ $step['color'] }}">
                            @if($step['icon'] === 'edit')
                                <svg viewBox="0 0 24 24" fill="none" stroke="{{ $step['color'] }}" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            @elseif($step['icon'] === 'shield')
                                <svg viewBox="0 0 24 24" fill="none" stroke="{{ $step['color'] }}" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/></svg>
                            @elseif($step['icon'] === 'zap')
                                <svg viewBox="0 0 24 24" fill="none" stroke="{{ $step['color'] }}" stroke-width="2"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            @elseif($step['icon'] === 'trending-up')
                                <svg viewBox="0 0 24 24" fill="none" stroke="{{ $step['color'] }}" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
                            @endif
                        </div>
                        <div class="step-num-badge">{{ $step['number'] }}</div>
                    </div>
                    <div class="step-title">{{ $step['title'] }}</div>
                    <div class="step-desc">{{ $step['desc'] }}</div>
                    <div class="step-arrow">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Product Giving Feature --}}
    <section class="product-section">
        <div class="container">
            <div class="product-inner">
                <div class="product-left reveal-left">
                    <div class="eyebrow">Unique Feature</div>
                    <h2 class="section-title">Product <em>Giving</em></h2>
                    <p>DonateBazaar's exclusive Product Giving feature lets donors buy physical products — not just donate money. Each product purchase directly funds your cause and creates a tangible connection between donor and beneficiary.</p>
                    <div class="product-features">
                        <div class="product-feature">
                            <div class="pf-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2z"/><path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/></svg></div>
                            <div class="pf-text"><strong>List any physical product</strong>Stationery, food kits, medicines, blankets, solar lanterns — anything your beneficiaries need.</div>
                        </div>
                        <div class="product-feature">
                            <div class="pf-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg></div>
                            <div class="pf-text"><strong>Donors see real impact</strong>Instead of just a money amount, donors see exactly what their ₹500 buys — a school kit, medicine pack, or food parcel.</div>
                        </div>
                        <div class="product-feature">
                            <div class="pf-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg></div>
                            <div class="pf-text"><strong>Track every product</strong>Live stock counter, sold-out states, and full purchase reports in your campaign dashboard.</div>
                        </div>
                    </div>
                    <a href="{{ route('campaign.create') }}" class="btn btn-white">
                        Add Products to My Campaign
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </a>
                </div>

<div class="product-cards-grid reveal-right">

    @php
    $sampleProducts = [
        [
            'icon' => 'book-open',
            'name' => 'School Kit',
            'price' => '₹250',
            'desc' => 'Notebook, pens, ruler set',
            'bg' => 'bg-blue-100',
            'color' => 'text-blue-600',
        ],
        [
            'icon' => 'heart',
            'name' => 'Medicine Pack',
            'price' => '₹400',
            'desc' => 'Essential medicines (1 month)',
            'bg' => 'bg-rose-100',
            'color' => 'text-rose-600',
        ],
        [
            'icon' => 'shopping-bag',
            'name' => 'Food Parcel',
            'price' => '₹200',
            'desc' => 'Nutritious family meal kit',
            'bg' => 'bg-amber-100',
            'color' => 'text-amber-600',
        ],
        [
            'icon' => 'sparkles',
            'name' => 'Tree Sapling',
            'price' => '₹50',
            'desc' => 'Plant a tree in your name',
            'bg' => 'bg-emerald-100',
            'color' => 'text-emerald-600',
        ],
    ];
    @endphp

    @foreach($sampleProducts as $sp)

    <div class="product-sample-card text-center flex flex-col items-center">

        <!-- Icon -->
        <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-5 {{ $sp['bg'] }} {{ $sp['color'] }}">

            @if($sp['icon'] === 'book-open')
                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                     viewBox="0 0 24 24" stroke-width="1.8"
                     stroke="currentColor" class="w-7 h-7">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5A4.5 4.5 0 003 9.5v9A4.5 4.5 0 017.5 14c1.746 0 3.332.477 4.5 1.253m0-9C13.168 5.477 14.754 5 16.5 5A4.5 4.5 0 0121 9.5v9a4.5 4.5 0 00-4.5-4.5c-1.746 0-3.332.477-4.5 1.253" />
                </svg>

            @elseif($sp['icon'] === 'heart')
                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                     viewBox="0 0 24 24" stroke-width="1.8"
                     stroke="currentColor" class="w-7 h-7">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M21 8.25c0-2.485-2.015-4.5-4.5-4.5-1.74 0-3.247.99-4 2.437A4.506 4.506 0 008.5 3.75C6.015 3.75 4 5.765 4 8.25c0 7.22 8 11.25 8 11.25s8-4.03 8-11.25z" />
                </svg>

            @elseif($sp['icon'] === 'shopping-bag')
                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                     viewBox="0 0 24 24" stroke-width="1.8"
                     stroke="currentColor" class="w-7 h-7">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M2.25 7.5h19.5m-16.5 0V6A2.25 2.25 0 017.5 3.75h9A2.25 2.25 0 0118.75 6v1.5m-13.5 0v10.125c0 .621.504 1.125 1.125 1.125h11.25c.621 0 1.125-.504 1.125-1.125V7.5" />
                </svg>

            @elseif($sp['icon'] === 'sparkles')
                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                     viewBox="0 0 24 24" stroke-width="1.8"
                     stroke="currentColor" class="w-7 h-7">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9.813 15.904L9 18l-1.813-2.096L5 15l2.187-.904L9 12l.813 2.096L12 15l-2.187.904zM18 13l.75 2.25L21 16l-2.25.75L18 19l-.75-2.25L15 16l2.25-.75L18 13zM12 3l1.125 3.375L16.5 7.5l-3.375 1.125L12 12l-1.125-3.375L7.5 7.5l3.375-1.125L12 3z" />
                </svg>
            @endif

        </div>

        <!-- Content -->
        <div class="psc-name text-center">{{ $sp['name'] }}</div>

        <div class="psc-price text-center">
            {{ $sp['price'] }}
        </div>

        <div class="psc-desc text-center">
            {{ $sp['desc'] }}
        </div>

    </div>

    @endforeach

</div>
            </div>
        </div>
    </section>

</div>{{-- /fundraisers pane --}}


{{-- ═══ TRUST PILLARS (shown in both tabs) ═══ --}}
<section class="trust-section">
    <div class="container">
        <div class="trust-header reveal">
            <div class="eyebrow">Why Choose Us</div>
            <h2 class="section-title">Built on <em>Trust</em></h2>
            <p style="font-size:15px;color:var(--text2);font-weight:300;line-height:1.75;max-width:480px;margin:0 auto">Six pillars that make DonateBazaar India's most trusted donation platform.</p>
        </div>
        <div class="trust-grid">
            @foreach($trustPillars as $i => $pillar)
            <div class="trust-card reveal d{{ ($i % 3) + 1 }}" style="--tc-color:{{ $pillar['color'] }}">
                <div class="trust-icon" style="background:{{ $pillar['bg'] }}">
                    @if($pillar['icon']==='shield')
                        <svg viewBox="0 0 24 24" fill="none" stroke="{{ $pillar['color'] }}" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/></svg>
                    @elseif($pillar['icon']==='lock')
                        <svg viewBox="0 0 24 24" fill="none" stroke="{{ $pillar['color'] }}" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                    @elseif($pillar['icon']==='eye')
                        <svg viewBox="0 0 24 24" fill="none" stroke="{{ $pillar['color'] }}" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    @elseif($pillar['icon']==='file-text')
                        <svg viewBox="0 0 24 24" fill="none" stroke="{{ $pillar['color'] }}" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="9" y1="13" x2="15" y2="13"/><line x1="9" y1="17" x2="12" y2="17"/></svg>
                    @elseif($pillar['icon']==='refresh-cw')
                        <svg viewBox="0 0 24 24" fill="none" stroke="{{ $pillar['color'] }}" stroke-width="2"><polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/><path d="M3.51 9a9 9 0 0114.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0020.49 15"/></svg>
                    @elseif($pillar['icon']==='headphones')
                        <svg viewBox="0 0 24 24" fill="none" stroke="{{ $pillar['color'] }}" stroke-width="2"><path d="M3 18v-6a9 9 0 0118 0v6"/><path d="M21 19a2 2 0 01-2 2h-1a2 2 0 01-2-2v-3a2 2 0 012-2h3v5zM3 19a2 2 0 002 2h1a2 2 0 002-2v-3a2 2 0 00-2-2H3v5z"/></svg>
                    @endif
                </div>
                <div class="trust-card-title">{{ $pillar['title'] }}</div>
                <div class="trust-card-desc">{{ $pillar['desc'] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>


{{-- ═══ FAQ ═══ --}}
<section class="faq-section">
    <div class="container">
        <div class="faq-header reveal">
            <div class="eyebrow">Got Questions?</div>
            <h2 class="section-title">Frequently Asked <em>Questions</em></h2>
        </div>

        <div class="faq-tab-wrap reveal">
            <button class="faq-tab-btn active" id="faq-tab-donors" onclick="switchFaqTab('donors')">For Donors</button>
            <button class="faq-tab-btn" id="faq-tab-fundraisers" onclick="switchFaqTab('fundraisers')">For Fundraisers</button>
        </div>

        <div id="faq-pane-donors">
            <div class="faq-grid">
                @foreach($faqsDonors as $i => $faq)
                <div class="faq-item reveal d{{ ($i%2)+1 }}" data-faq="d{{ $i }}">
                    <div class="faq-q" onclick="toggleFaq('d{{ $i }}')">
                        <span class="faq-q-text">{{ $faq['q'] }}</span>
                        <div class="faq-chevron"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg></div>
                    </div>
                    <div class="faq-answer"><div class="faq-answer-inner">{{ $faq['a'] }}</div></div>
                </div>
                @endforeach
            </div>
        </div>

        <div id="faq-pane-fundraisers" style="display:none">
            <div class="faq-grid">
                @foreach($faqsFundraisers as $i => $faq)
                <div class="faq-item reveal d{{ ($i%2)+1 }}" data-faq="f{{ $i }}">
                    <div class="faq-q" onclick="toggleFaq('f{{ $i }}')">
                        <span class="faq-q-text">{{ $faq['q'] }}</span>
                        <div class="faq-chevron"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg></div>
                    </div>
                    <div class="faq-answer"><div class="faq-answer-inner">{{ $faq['a'] }}</div></div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>


{{-- ═══ CTA ═══ --}}
<section class="cta-section">
    <div class="cta-inner">
        <div class="eyebrow reveal" style="justify-content:center;color:#a5b4fc">Ready to make a difference?</div>
        <h2 class="cta-title reveal d1">Start Your Own <em>Campaign</em></h2>
        <p class="cta-sub reveal d2">Medical emergency, education, disaster relief — whatever the cause, we verify and support your fundraiser from day one. Free to start, 24×7 support.</p>
        <div class="cta-btns reveal d3">
            <a href="{{ route('campaign.create') }}" class="btn btn-accent btn-lg">
                Start Fundraiser
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 4v16m8-8H4"/></svg>
            </a>
            <a href="{{ route('all.campaigns') }}" class="btn btn-white btn-lg">
                Browse Campaigns
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>
</section>

<button class="scroll-top" id="scrollTopBtn" onclick="window.scrollTo({top:0,behavior:'smooth'})" aria-label="Scroll to top">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 15l-6-6-6 6"/></svg>
</button>


<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ── Mark JS as enabled ── */
    document.documentElement.classList.add('js-enabled');

    /* ── Scroll Reveal ── */
    var revEls = document.querySelectorAll('.reveal,.reveal-left,.reveal-right');
    var obs = new IntersectionObserver(function(entries){
        entries.forEach(function(e){ if(e.isIntersecting){ e.target.classList.add('visible'); obs.unobserve(e.target); } });
    },{ threshold:0.08, rootMargin:'0px 0px -28px 0px' });
    revEls.forEach(function(el){ obs.observe(el); });

    /* ── Scroll to top ── */
    var sBtn = document.getElementById('scrollTopBtn');
    window.addEventListener('scroll', function(){ sBtn.classList.toggle('visible', window.scrollY > 600); },{ passive:true });

    /* ── Set tab from URL hash ── */
    if (window.location.hash === '#fundraisers') switchTab('fundraisers');
});

/* ── Main tab switch (Donors / Fundraisers) ── */
function switchTab(tab) {
    ['donors','fundraisers'].forEach(function(t){
        document.getElementById('tab-'   + t).classList.toggle('active', t === tab);
        document.getElementById('pane-'  + t).classList.toggle('active', t === tab);
    });
    // Update URL hash without scroll
    history.replaceState(null, '', tab === 'donors' ? '#donors' : '#fundraisers');

    // Trigger reveal on newly visible elements
    setTimeout(function(){
        document.querySelectorAll('#pane-' + tab + ' .reveal, #pane-' + tab + ' .reveal-left, #pane-' + tab + ' .reveal-right').forEach(function(el){
            el.classList.add('visible');
        });
    }, 50);
}

/* ── FAQ tab switch ── */
function switchFaqTab(tab) {
    ['donors','fundraisers'].forEach(function(t){
        document.getElementById('faq-tab-'  + t).classList.toggle('active', t === tab);
        var pane = document.getElementById('faq-pane-' + t);
        pane.style.display = t === tab ? 'block' : 'none';
    });
    document.querySelectorAll('#faq-pane-' + tab + ' .faq-item.reveal').forEach(function(el){
        el.classList.add('visible');
    });
}

/* ── FAQ accordion ── */
function toggleFaq(id) {
    var item   = document.querySelector('[data-faq="' + id + '"]');
    var isOpen = item.classList.contains('open');
    document.querySelectorAll('.faq-item.open').forEach(function(el){ el.classList.remove('open'); });
    if (!isOpen) item.classList.add('open');
}
</script>

@endsection