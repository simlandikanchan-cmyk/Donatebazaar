@extends('layouts.app')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300&family=DM+Mono:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,700;1,700&display=swap" rel="stylesheet">

@push('styles') @vite(['resources/css/ddrf.css']) @endpush
@endpush

@section('content')


{{-- ═══ ALERT BANNER ═══ --}}
<div class="ddrf-alert-banner">
    <span class="alert-pulse"></span>
    DDRF Active — DonateBazaar Disaster Relief Force is currently operational. Donations are disbursed within 72 hours.
    <span class="alert-pulse"></span>
</div>


{{-- ═══ HERO ═══ --}}
<div class="ddrf-hero">
    <div class="ddrf-hero-bg">
        <img src="{{ asset('images/ddrf-hero.jpg') }}" alt="Disaster Relief" loading="eager">
    </div>
    <div class="ddrf-hero-overlay"></div>
    <div class="ddrf-hero-grid"></div>

    <div class="ddrf-radar">
        <div class="radar-ring"></div>
        <div class="radar-ring"></div>
        <div class="radar-ring"></div>
        <div class="radar-ring"></div>
        <div class="radar-ring"></div>
    </div>

    <div class="ddrf-hero-inner">
        <div class="ddrf-hero-content">
            <div class="ddrf-hero-badge">
                <span class="ddrf-badge-dot"></span>
                DonateBazaar Disaster Relief Force
            </div>
            <h1 class="ddrf-hero-title">Relief. Rebuild.<br>Restore.</h1>
            <p class="ddrf-hero-sub">Emergency Crowdfunding for NGOs &amp; CSR</p>
            <p class="ddrf-hero-desc">
                DonateBazaar leverages technology and pan-India operational expertise to make disaster relief truly holistic. We partner with verified on-ground NGOs to deliver essential aid within 72 hours — and let donors track every rupee of impact.
            </p>
            <div class="ddrf-hero-tags">
                <span class="ddrf-hero-tag">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/></svg>
                    100% Verified NGOs
                </span>
                <span class="ddrf-hero-tag">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="9" y1="13" x2="15" y2="13"/></svg>
                    80G Tax Benefits
                </span>
                <span class="ddrf-hero-tag">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                    72-Hour Deployment
                </span>
                <span class="ddrf-hero-tag">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>
                    Real-time Tracking
                </span>
            </div>
            <div class="ddrf-hero-btns">
                <a href="{{ route('all.campaigns') }}?type=disaster" class="btn btn-accent" style="font-size:15px;padding:14px 32px">
                    Donate to Relief
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
                </a>
                <a href="{{ route('partnership') }}" class="btn btn-white" style="font-size:15px;padding:14px 32px">
                    Apply For Partnership
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                </a>
            </div>
        </div>

        <div class="ddrf-stat-bar">
            @foreach([
                ['val' => '₹' . number_format($totalRaised),  'lbl' => 'Amount Raised',     'highlight' => true],
                ['val' => number_format($totalDonors),         'lbl' => 'Lives Touched',     'highlight' => false],
                ['val' => $activeCamps,                        'lbl' => 'Active Campaigns',  'highlight' => false],
                ['val' => '500+',                              'lbl' => 'NGO Partners',      'highlight' => false],
                ['val' => '72 hrs',                            'lbl' => 'Max Response Time', 'highlight' => true],
                ['val' => '28',                                'lbl' => 'States Covered',    'highlight' => false],
            ] as $s)
            <div class="ddrf-stat-item">
                <span class="ddrf-stat-val {{ ($s['highlight'] ?? false) ? 'highlight' : '' }}">{{ $s['val'] }}</span>
                <span class="ddrf-stat-lbl">{{ $s['lbl'] }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>


{{-- ═══ MARQUEE ═══ --}}
<div class="marquee-band">
    <div class="marquee-inner">
        <div class="marquee-row">
            @php $mItems = ['Emergency Relief','72-Hour Deployment','Verified NGOs','80G Tax Benefits','Flood Relief','Earthquake Aid','Drought Support','Real-time Tracking','CSR Partnerships','Pan-India Coverage','RBI-Compliant','Product Giving']; @endphp
            @for($r=0;$r<3;$r++)
                @foreach($mItems as $mi)
                    <span class="m-item"><span class="m-dot"></span>{{ $mi }}</span>
                @endforeach
            @endfor
        </div>
    </div>
</div>


{{-- ═══ ACTIVE RELIEF CAMPAIGNS ═══ --}}
<section class="campaigns-section">
    <div class="container">
        <div class="campaigns-header reveal">
            <div class="eyebrow">Active Campaigns</div>
            <h2 class="section-title">Relief Campaigns <em>Live Now</em></h2>
            <p style="font-size:15px;color:var(--text2);font-weight:300;line-height:1.75;max-width:480px;margin:0 auto">
                Every campaign is verified by our team. Funds are released in milestone-based tranches directly to NGOs operating on the ground.
            </p>
        </div>
        <div class="campaigns-grid">
            @forelse($disasterCampaigns ?? [] as $i => $campaign)
            <div class="campaign-card reveal d{{ ($i % 3) + 1 }}">
                <div class="campaign-card-img">
                    <img src="{{ $campaign['image'] ?? asset('images/placeholder-relief.jpg') }}" alt="{{ $campaign['title'] }}" loading="lazy">
                    <span class="campaign-urgency urgency-{{ $campaign['urgency'] ?? 'active' }}">
                        <span class="urgency-dot"></span>{{ ucfirst($campaign['urgency'] ?? 'active') }}
                    </span>
                </div>
                <div class="campaign-card-body">
                    <div class="campaign-location">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        {{ $campaign['location'] ?? 'India' }}
                    </div>
                    <div class="campaign-card-title">{{ $campaign['title'] }}</div>
                    <div class="campaign-card-desc">{{ Str::limit($campaign['description'] ?? '', 110) }}</div>
                    <div class="campaign-progress-wrap">
                        <div class="campaign-progress-bar">
                            <div class="campaign-progress-fill" style="width:{{ min($campaign['percent'] ?? 0, 100) }}%"></div>
                        </div>
                        <div class="campaign-progress-meta">
                            <span class="cp-raised">₹{{ number_format($campaign['raised'] ?? 0) }}</span>
                            <span class="cp-pct">{{ $campaign['percent'] ?? 0 }}%</span>
                            <span class="cp-goal">of ₹{{ number_format($campaign['goal'] ?? 0) }}</span>
                        </div>
                    </div>
                </div>
                <div class="campaign-card-footer">
                    <span class="cf-donors">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
                        {{ $campaign['donors'] ?? 0 }} donors
                    </span>
<a href="{{ route('campaign.public', [
    'category' => $campaign['category'],
    'slug'     => $campaign['slug']
]) }}" class="cf-btn">
                        Donate Now
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>
            @empty
            <div class="no-campaigns reveal">
                <div class="no-campaigns-icon">🆘</div>
                <h3>No Active Campaigns Right Now</h3>
                <p>There are currently no active disaster relief campaigns. Check back soon — new campaigns launch within hours of a disaster.</p>
                <a href="{{ route('campaign.create') }}" class="btn btn-accent" style="font-size:14px;padding:12px 26px">
                    Start a Relief Campaign
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 4v16m8-8H4"/></svg>
                </a>
            </div>
            @endforelse
        </div>
    </div>
</section>


{{-- ═══ 72-HOUR RESPONSE SYSTEM ═══ --}}
<section class="response-section">
    <div class="container">
        <div class="response-header reveal">
            <div class="eyebrow">In Times of Need, We Arrive First</div>
            <h2 class="section-title">Our <em>72-Hour</em> Response System</h2>
            <p>We are capable of timely delivery of relief material — critical during a disaster. Our nationwide NGO, community, and vendor network makes it possible.</p>
        </div>
        <div class="response-timeline reveal d1">
            @php
            $steps = [
                ['icon'=>'map-pin','time'=>'0–24 Hrs','step'=>'Step 01','title'=>'Understand the Gravity','desc'=>'Our DDRF team assesses the disaster — scale, affected population, immediate requirements — within 24 hours of the incident.'],
                ['icon'=>'package','time'=>'24–48 Hrs','step'=>'Step 02','title'=>'Procure Relief Materials','desc'=>'We source essential materials from our trusted vendor list — food, medicine, blankets, water, sanitation kits — at scale and speed.'],
                ['icon'=>'truck','time'=>'48–72 Hrs','step'=>'Step 03','title'=>'Last-Mile Delivery','desc'=>'Materials are dispatched directly to our NGO partners operating in the disaster-affected areas, reaching beneficiaries fast.'],
            ];
            @endphp
            @foreach($steps as $s)
            <div class="rts-item">
                <div class="rts-circle">
                    @if($s['icon']==='map-pin')
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    @elseif($s['icon']==='package')
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
                    @elseif($s['icon']==='truck')
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                    @endif
                    <span class="rts-time-badge">{{ $s['time'] }}</span>
                </div>
                <div>
                    <div class="rts-step">{{ $s['step'] }}</div>
                    <div class="rts-title">{{ $s['title'] }}</div>
                    <div class="rts-desc">{{ $s['desc'] }}</div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="network-stats reveal d2">
            @foreach([
                ['val'=>'686','lbl'=>'Districts'],
                ['val'=>'500+','lbl'=>'NGO Partners'],
                ['val'=>'28','lbl'=>'States'],
                ['val'=>'680+','lbl'=>'Vendors'],
                ['val'=>'70+','lbl'=>'Relief Projects'],
                ['val'=>'8+','lbl'=>'Years Active'],
            ] as $ns)
            <div class="ns-item">
                <span class="ns-val">{{ $ns['val'] }}</span>
                <span class="ns-lbl">{{ $ns['lbl'] }}</span>
            </div>
            @endforeach
        </div>
    </div>
</section>


{{-- ═══ HOW IT WORKS ═══ --}}
<section class="hiw-section">
    <div class="container">
        <div class="hiw-header reveal">
            <div class="eyebrow">Small Steps, Large Impact</div>
            <h2 class="section-title">How It <em>Works</em></h2>
            <p style="font-size:15px;color:var(--text2);font-weight:300;line-height:1.75;max-width:440px;margin:0 auto">Whether you're donating or starting a relief campaign, DDRF makes it simple and transparent.</p>
        </div>
        <div class="hiw-cols">
            <div class="hiw-col reveal-left">
                <div class="hiw-col-title">
                    <div class="hiw-col-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg></div>
                    For Donors
                </div>
                <div class="hiw-steps">
                    <div class="hiw-step"><div class="hiw-step-num">1</div><div class="hiw-step-body"><div class="hiw-step-title">Choose a Cause</div><div class="hiw-step-desc">Browse active disaster relief campaigns filtered by region, disaster type, or urgency. Every campaign is DDRF-verified.</div></div></div>
                    <div class="hiw-step"><div class="hiw-step-num">2</div><div class="hiw-step-body"><div class="hiw-step-title">Donate Money or Products</div><div class="hiw-step-desc">Contribute via UPI, card, or net banking — or use Product Giving to buy specific relief items like food kits, blankets, or medicine packs.</div></div></div>
                    <div class="hiw-step"><div class="hiw-step-num">3</div><div class="hiw-step-body"><div class="hiw-step-title">Track &amp; Get Your 80G</div><div class="hiw-step-desc">Receive real-time field updates, photo/video proof of delivery, and your 80G tax certificate — all from your donor dashboard.</div></div></div>
                </div>
                <div class="hiw-col-cta">
                    <a href="{{ route('all.campaigns') }}?type=disaster" class="btn btn-accent" style="font-size:14px;padding:12px 26px">
                        Donate Now
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>
            <div class="hiw-col reveal-right">
                <div class="hiw-col-title">
                    <div class="hiw-col-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg></div>
                    For NGOs &amp; Charities
                </div>
                <div class="hiw-steps">
                    <div class="hiw-step"><div class="hiw-step-num">1</div><div class="hiw-step-body"><div class="hiw-step-title">Begin Your Relief Fundraiser</div><div class="hiw-step-desc">Launch a campaign in minutes. Share your on-ground story, add product giving items, and publish — all at zero platform cost.</div></div></div>
                    <div class="hiw-step"><div class="hiw-step-num">2</div><div class="hiw-step-body"><div class="hiw-step-title">Get Verified &amp; Spread the Word</div><div class="hiw-step-desc">DDRF team verifies your campaign within 24 hours. Share with your community, CSR partners, and social networks for maximum reach.</div></div></div>
                    <div class="hiw-step"><div class="hiw-step-num">3</div><div class="hiw-step-body"><div class="hiw-step-title">Receive Milestone-Based Funds</div><div class="hiw-step-desc">Funds are released in tranches as you upload field proof — photos, bills, delivery reports — keeping donors informed and confident.</div></div></div>
                </div>
                <div class="hiw-col-cta">
                    <a href="{{ route('campaign.create') }}" class="btn btn-accent" style="font-size:14px;padding:12px 26px">
                        Start a Relief Campaign
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 4v16m8-8H4"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>


{{-- ═══ DRR SYSTEM ═══ --}}
<section class="drr-section">
    <div class="container">
        <div class="drr-inner">
            <div class="drr-left reveal-left">
                <div class="eyebrow">Our Infrastructure</div>
                <h2 class="section-title">Connected to the <em>Last Mile</em></h2>
                <p>DDRF isn't just a fundraising platform — it's a full disaster response ecosystem. From CSR onboarding to field delivery, we manage the entire pipeline so relief reaches people when it matters most.</p>
                <div class="drr-pillars">
                    @foreach([
                        ['icon'=>'zap','title'=>'Rapid Deployment','desc'=>'Materials sourced and dispatched to affected districts within 72 hours of disaster confirmation.'],
                        ['icon'=>'shield','title'=>'Verified NGO Network','desc'=>'500+ partner NGOs across 28 states, all KYC-verified and trained in last-mile distribution.'],
                        ['icon'=>'eye','title'=>'Full Transparency','desc'=>'Real-time GPS tracking, photo proof uploads, and live donor dashboards for every campaign.'],
                        ['icon'=>'briefcase','title'=>'CSR Ready','desc'=>'End-to-end CSR management — from campaign alignment to 80G receipts and impact reports for board filing.'],
                    ] as $p)
                    <div class="drr-pillar">
                        <div class="drr-pillar-icon">
                            @if($p['icon']==='zap')<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            @elseif($p['icon']==='shield')<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/></svg>
                            @elseif($p['icon']==='eye')<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            @elseif($p['icon']==='briefcase')<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/></svg>
                            @endif
                        </div>
                        <div class="drr-pillar-text">
                            <div class="drr-pillar-title">{{ $p['title'] }}</div>
                            <div class="drr-pillar-desc">{{ $p['desc'] }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="drr-visual reveal-right">
                <div class="drr-map-card">
                    <div class="drr-map-card-title">Active District Coverage</div>
                    <div class="drr-map-dots">
                        @foreach([
                            ['name'=>'Kerala','active'=>true],['name'=>'Assam','active'=>true],['name'=>'Odisha','active'=>false],
                            ['name'=>'Gujarat','active'=>true],['name'=>'Bihar','active'=>false],['name'=>'WB','active'=>true],
                            ['name'=>'Tamil Nadu','active'=>false],['name'=>'Andhra','active'=>true],['name'=>'Rajasthan','active'=>false],
                        ] as $state)
                        <div class="drr-state-dot">
                            <div class="drr-state-ring {{ $state['active'] ? 'active' : '' }}">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            </div>
                            <span class="drr-state-name">{{ $state['name'] }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="drr-live-feed">
                    <div class="dlf-header">
                        <span class="dlf-dot"></span>
                        <span class="dlf-label">Live Field Updates</span>
                    </div>
                    <div class="dlf-items">
                        <div class="dlf-item"><span class="dlf-item-dot"></span><span class="dlf-item-text"><strong>Kerala Flood Relief:</strong> 2,400 food kits dispatched to Ernakulam districts</span></div>
                        <div class="dlf-item"><span class="dlf-item-dot"></span><span class="dlf-item-text"><strong>Assam Flood NGO:</strong> ₹8.2L released — milestone 2 verified</span></div>
                        <div class="dlf-item"><span class="dlf-item-dot"></span><span class="dlf-item-text"><strong>Gujarat Drought Aid:</strong> 600 water purification units en route</span></div>
                        <div class="dlf-item"><span class="dlf-item-dot"></span><span class="dlf-item-text"><strong>Andhra Relief:</strong> Proof photos uploaded — final tranche approved</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


{{-- ═══ FOUNDER MESSAGE ═══ --}}
<section class="founder-section">
    <div class="container">
        <div class="founder-inner">
            <div class="founder-photo-wrap reveal-left">
                <div class="founder-photo">
                    <img src="{{ asset('images/founder.jpg') }}" alt="Founder of DonateBazaar" loading="lazy">
                </div>
                <div class="founder-card-badge">
                    <div class="fcb-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
                    </div>
                    <div>
                        <div class="fcb-name">Founder of DonateBazaar</div>
                        <div class="fcb-role">A Message of Hope</div>
                    </div>
                </div>
            </div>
            <div class="founder-right reveal-right">
                <div class="eyebrow">Founder's Vision</div>
                <h2 class="section-title">Why We Built <em>DDRF</em></h2>
                <div class="founder-quote">
                    We envision the DonateBazaar Disaster Relief Force to be the first leg ahead — supporting every community wherever disaster strikes in India.
                </div>
                <p>Our nationwide network of partner NGOs, communities, and trusted vendors gives us the leverage to provide immediate relief material within 72 hours of any disaster. Our technology platform empowers corporate partners with real-time insights into ground operations during these critical moments.</p>
                <p>DonateBazaar strives to provide relief when people need it most — with utmost care, speed, and empathy. Every rupee you donate is tracked, verified, and reported back to you.</p>
                <div class="founder-sig">
                    Founder, DonateBazaar
                    <span>Empowering India's relief infrastructure since day one</span>
                </div>
            </div>
        </div>
    </div>
</section>


{{-- ═══ CSR / PARTNERS ═══ --}}
<section class="partners-section">
    <div class="container">
        <div class="partners-header reveal">
            <div class="eyebrow">Corporate Partners</div>
            <h2>Our <em>CSR Partners</em></h2>
        </div>
        <div class="partners-grid">
            @foreach($csrPartners ?? ['Partner NGO 1','Partner NGO 2','Partner NGO 3','Partner NGO 4','Partner NGO 5','Partner Corp 1','Partner Corp 2','Partner Corp 3','Partner Corp 4','Partner Corp 5'] as $partner)
            <div class="partner-logo reveal">
                @if(is_array($partner) && isset($partner['logo']))
                    <img src="{{ $partner['logo'] }}" alt="{{ $partner['name'] }}" style="max-height:36px;filter:brightness(0) invert(.55);transition:.3s" onmouseover="this.style.filter='brightness(0) invert(.82)'" onmouseout="this.style.filter='brightness(0) invert(.55)'">
                @else
                    <span>{{ is_array($partner) ? $partner['name'] : $partner }}</span>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</section>


{{-- ═══ CTA ═══ --}}
<section class="ddrf-cta">
    <div class="ddrf-cta-inner">
        <div class="eyebrow reveal" style="justify-content:center;color:var(--p3)">Join the Movement</div>
        <h2 class="ddrf-cta-title reveal d1">Together, We <em>Rebuild</em></h2>
        <p class="ddrf-cta-sub reveal d2">Donate, volunteer, partner with us for CSR — every action, big or small, brings us closer to a world where no community suffers alone.</p>
        <div class="ddrf-cta-btns reveal d3">
            <a href="{{ route('all.campaigns') }}?type=disaster" class="btn btn-accent" style="font-size:15px;padding:15px 34px">
                Donate to Relief
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
            </a>
            <a href="{{ route('campaign.create') }}" class="btn btn-white" style="font-size:15px;padding:15px 34px">
                Start Relief Campaign
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 4v16m8-8H4"/></svg>
            </a>
        </div>
    </div>
</section>

<button class="scroll-top" id="scrollTopBtn" onclick="window.scrollTo({top:0,behavior:'smooth'})" aria-label="Scroll to top">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 15l-6-6-6 6"/></svg>
</button>

<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ── Scroll Reveal ── */
    var revEls = document.querySelectorAll('.reveal,.reveal-left,.reveal-right');
    var obs = new IntersectionObserver(function(entries){
        entries.forEach(function(e){
            if(e.isIntersecting){ e.target.classList.add('visible'); obs.unobserve(e.target); }
        });
    },{ threshold: 0.08, rootMargin: '0px 0px -28px 0px' });
    revEls.forEach(function(el){ obs.observe(el); });

    /* ── Scroll to top ── */
    var sBtn = document.getElementById('scrollTopBtn');
    window.addEventListener('scroll', function(){
        sBtn.classList.toggle('visible', window.scrollY > 600);
    },{ passive: true });

    /* ── Animate progress bars ── */
    var bars = document.querySelectorAll('.campaign-progress-fill');
    var barObs = new IntersectionObserver(function(entries){
        entries.forEach(function(e){
            if(e.isIntersecting){
                var target = e.target.dataset.width || e.target.style.width;
                e.target.style.width = target;
                barObs.unobserve(e.target);
            }
        });
    },{ threshold: 0.2 });
    bars.forEach(function(bar){
        var w = bar.style.width;
        bar.dataset.width = w;
        bar.style.width = '0';
        setTimeout(function(){ barObs.observe(bar); }, 200);
    });

    /* ── Counter animation ── */
    function animateCounter(el) {
        var originalText = el.textContent.trim();

        // Detect currency symbol
        var hasRupee = originalText.indexOf('₹') !== -1;

        // Detect plus sign
        var hasPlus = originalText.indexOf('+') !== -1;

        // Extract numeric value only
        var num = parseInt(originalText.replace(/[^\d]/g, ''), 10);

        if (isNaN(num) || num === 0) return;

        var duration = 1500;
        var startTime = null;

        function step(timestamp) {
            if (!startTime) startTime = timestamp;

            var progress = Math.min((timestamp - startTime) / duration, 1);
            var current = Math.floor(progress * num);

            var formatted = current.toLocaleString('en-IN');

            if (hasRupee) formatted = '₹' + formatted;
            if (hasPlus) formatted += '+';

            el.textContent = formatted;

            if (progress < 1) {
                requestAnimationFrame(step);
            } else {
                var finalText = num.toLocaleString('en-IN');
                if (hasRupee) finalText = '₹' + finalText;
                if (hasPlus) finalText += '+';
                el.textContent = finalText;
            }
        }

        requestAnimationFrame(step);
    }

    /* ── Wire up counter animation on stat numbers ── */
    var statEls = document.querySelectorAll('.ddrf-stat-val, .ns-val');
    var statObs = new IntersectionObserver(function(entries){
        entries.forEach(function(e){
            if (e.isIntersecting) {
                animateCounter(e.target);
                statObs.unobserve(e.target);
            }
        });
    }, { threshold: 0.3 });
    statEls.forEach(function(el){ statObs.observe(el); });

});
</script>

@endsection