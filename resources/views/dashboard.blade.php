@extends('layouts.user')

@section('page_title', 'Dashboard')
@section('page_subtitle', now()->format('l, d F Y'))

@section('content')
@php
    $totalRaised  = $campaigns->sum('raised_amount');
    $totalGoal    = $campaigns->sum('goal_amount');
    $overallPct   = $totalGoal > 0 ? min(100, round(($totalRaised / $totalGoal) * 100)) : 0;
    $totalDonors  = $campaigns->sum('donors_count') ?? 0;
    $hour         = now()->hour;
    $greeting     = $hour < 12 ? 'morning' : ($hour < 17 ? 'afternoon' : 'evening');

    $countAll      = $campaigns->count();
    $countActive   = $campaigns->where('campaign_state','active')->count();
    $countInactive = $campaigns->where('campaign_state','inactive')->count();
    $countPending  = $campaigns->where('campaign_state','pending')->count();
    $countPaused   = $campaigns->where('campaign_state','paused')->count();
    $countRejected = $campaigns->where('campaign_state','rejected')->count();
    $countExpired  = $campaigns->where('campaign_state','expired')->count();
@endphp

{{-- ══ WELCOME BANNER ══ --}}
<div class="welcome-banner">
    <span class="wb-glow g1"></span>
    <span class="wb-glow g2"></span>
    <svg class="wb-deco" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
        <circle cx="180" cy="30" r="80" stroke="currentColor" stroke-width=".5" opacity=".06"/>
        <circle cx="180" cy="30" r="100" stroke="currentColor" stroke-width=".5" opacity=".04"/>
        <circle cx="180" cy="30" r="120" stroke="currentColor" stroke-width=".5" opacity=".03"/>
        <path d="M0 200 Q 50 150 100 180 T 200 160" stroke="currentColor" stroke-width=".5" opacity=".04"/>
    </svg>
    <div class="wb-left">
        <div class="wb-tag">
            <span class="wb-tag-dot"></span>
            Good {{ $greeting }}, Fundraiser
            @if($levelName !== 'Starter')
                <span class="wb-badge wbb-primary wb-badge--inline">{{ $levelName }}</span>
            @endif
        </div>
        <div class="wb-name">{{ auth()->user()->name }} <span class="wave"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 11V6a2 2 0 0 0-2-2 2 2 0 0 0-2 2"/><path d="M14 10V4a2 2 0 0 0-2-2 2 2 0 0 0-2 2v2"/><path d="M10 10.5V6a2 2 0 0 0-2-2 2 2 0 0 0-2 2v8"/><path d="M18 8a2 2 0 1 1 4 0v6a8 8 0 0 1-8 8h-2c-2.8 0-4.5-.86-5.99-2.34l-3.6-3.6a2 2 0 0 1 2.83-2.82L7 15"/></svg></span></div>
        <div class="wb-sub wb-sub--flex">
            <span>Here's what's happening with your campaigns today.</span>
            @if($daysActive > 0)
                <span class="wb-sub-text">
                    Member for {{ $daysActive }} day{{ $daysActive !== 1 ? 's' : '' }}
                </span>
            @endif
            @if($level)
                <span class="wb-sub-text">
                    {{ $levelName }} · Max goal ₹{{ number_format($user->maxCampaignGoal()) }}
                </span>
            @endif
        </div>
        <div class="wb-badges">
            @if($countActive > 0)
                <span class="wb-badge wbb-green"><svg class="badge-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>{{ $countActive }} live</span>
            @endif
            @if($countPending > 0)
                <span class="wb-badge wbb-yellow"><svg class="badge-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>{{ $countPending }} pending review</span>
            @endif
            @if($countRejected > 0)
                <span class="wb-badge wbb-red"><svg class="badge-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>{{ $countRejected }} rejected</span>
            @endif
            @if($overallPct > 0)
                <span class="wb-badge wbb-primary">{{ $overallPct }}% overall funded</span>
            @endif
            @if($countAll === 0)
                <span class="wb-badge wbb-primary">Get started — create your first campaign</span>
            @endif
        </div>
    </div>
    <div class="wb-right">
        <x-button variant="primary" href="{{ route('campaign.create') }}" class="wb-btn wb-btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            New Campaign
        </x-button>
        @if(!$kyc || $kyc->status !== 'approved')
        <x-button variant="primary" href="{{ url('/user/kyc') }}" class="wb-btn wb-btn-yellow">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            {{ $kyc ? 'KYC '.ucfirst($kyc->status) : 'Submit KYC' }}
        </x-button>
        @endif
        <x-button variant="ghost" href="{{ route('profile.show') }}" class="wb-btn wb-btn-ghost">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            My Profile
        </x-button>
    </div>
</div>

{{-- ══ STATS (5 cards) ══ --}}
@php
$avgDonation = $totalDonationsCount > 0 ? round($totalRaised / $totalDonationsCount) : 0;

$icoTotalRaised = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
$icoTotalGoal  = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>';
$icoActive     = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
$icoDonations  = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 7v10c0 2 1 3 3 3h10c2 0 3-1 3-3V7c0-2-1-3-3-3H7C5 4 4 5 4 7z"/><polyline points="16 10 12 14 8 10"/></svg>';
$icoAllCamps   = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>';
$icoWallet     = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>';
@endphp
<div class="stats-grid">
    <x-stat-card color="primary" label="Total Raised" value="₹{{ number_format($totalRaised, 0) }}" footer="{{ $overallPct }}% of total goal" :icon="$icoTotalRaised" />
    <x-stat-card color="pink" label="Total Goal" value="₹{{ number_format($totalGoal, 0) }}" footer="Across {{ $countAll }} campaigns" :icon="$icoTotalGoal" />
    <x-stat-card color="green" label="Active Campaigns" value="{{ $countActive }}" footer="Live &amp; accepting donations" :icon="$icoActive" />
    <x-stat-card color="yellow" label="Total Donations" value="{{ number_format($totalDonationsCount) }}" footer="Avg ₹{{ number_format($avgDonation) }} per donation" :icon="$icoDonations" />
    <x-stat-card color="blue" label="All Campaigns" value="{{ $countAll }}" footer="View all &rarr;" href="{{ url('/user/dashboard') }}#cGrid" :icon="$icoAllCamps" />
    <x-stat-card color="secondary" label="Wallet" value="₹{{ number_format($wallet->available_balance) }}" footer="Available balance &rarr;" href="{{ route('dashboard.wallet') }}" :icon="$icoWallet" />
</div>

{{-- ══ RECENT DONOR ACTIVITY ══ --}}
@if($recentDonations->isNotEmpty())
<div class="activity-card">
    <div class="activity-hdr">
        <div class="activity-title">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
            Recent Donor Activity
        </div>
    </div>
    <div class="activity-list">
        @foreach($recentDonations as $donation)
        @php
            $initial = $donation->is_anonymous ? '?' : strtoupper(substr(trim($donation->donor_name) ?: 'D', 0, 1));
        @endphp
        <x-activity-item color="green" :initial="$initial">
            <div class="activity-body-top">
                <div class="activity-lbl">
                    @if($donation->is_anonymous)
                        <span>Someone</span>
                    @else
                        {{ $donation->donor_name ?? 'A donor' }}
                    @endif
                    donated
                </div>
                <div class="activity-amt">+₹{{ number_format($donation->total_amount) }}</div>
            </div>
            <div class="activity-sub">
                to <span>{{ $donation->campaign?->title ?? 'a campaign' }}</span>
                @if($donation->message)
                    · "{{ Str::limit($donation->message, 60) }}"
                @endif
            </div>
        </x-activity-item>
        @endforeach
    </div>
</div>
@endif

{{-- ══ PENDING TASKS / ALERTS ══ --}}
@if($pendingTasks->isNotEmpty())
<div class="pending-card">
    <div class="pending-hdr">
        <div class="pending-title">
            <svg class="pending-title-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/></svg>
            Action Needed
        </div>
        <span class="pending-count">{{ $pendingTasks->count() }} task{{ $pendingTasks->count() !== 1 ? 's' : '' }}</span>
    </div>
    <div class="pending-list">
        @foreach($pendingTasks as $task)
        <x-pending-item
            :icon="$task['icon']"
            :label="$task['label']"
            :sub="$task['sub']"
            :url="$task['url']"
        />
        @endforeach
    </div>
</div>
@endif

{{-- ══ ANALYTICS ROW ══ --}}
<div class="analytics-row">
    <div class="chart-card">
        <div class="chart-card-hdr">
            <div>
                <div class="chart-title">Fundraising Overview</div>
                <div class="chart-sub">Monthly funds raised — last 12 months</div>
            </div>
            <div class="chart-legend">
                <div class="chart-legend-dot"></div>
                Amount Raised (₹)
            </div>
        </div>
        <div class="chart-wrap"><canvas id="fundChart"></canvas></div>
    </div>

    <div class="impact-ring-card">
        <div class="impact-ring-hdr">Funding Health</div>
        <div class="impact-ring-sub">Overall goal completion</div>
        <div class="impact-ring-wrap">
            <svg viewBox="0 0 120 120" class="impact-ring-svg">
                <defs>
                    <linearGradient id="ringGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" stop-color="#2563eb"/>
                        <stop offset="100%" stop-color="#0d9488"/>
                    </linearGradient>
                </defs>
                <circle class="impact-ring-bg" cx="60" cy="60" r="52"/>
                <circle class="impact-ring-fg" id="impactRing" cx="60" cy="60" r="52"/>
            </svg>
            <div class="impact-ring-center">
                <div class="impact-ring-pct" id="impactRingPct">{{ $overallPct }}%</div>
                <div class="impact-ring-lbl">Funded</div>
            </div>
        </div>
        <div class="impact-ring-foot">
            <b>&#8377;{{ number_format($totalRaised, 0) }}</b> of &#8377;{{ number_format($totalGoal, 0) }}
        </div>
    </div>

    <div class="qs-panel">
        <div class="qs-title">Campaign Status</div>
        @php
            $qsRows = [
                ['var(--green)',  'Active',         $countActive,   'active'],
                ['var(--blue)',   'Awaiting Review',$countInactive, 'inactive'],
                ['var(--yellow)', 'Pending',        $countPending,  'pending'],
                ['var(--accent)', 'Paused',         $countPaused,   'paused'],
                ['var(--red)',    'Rejected',       $countRejected, 'rejected'],
                ['var(--gray)',   'Expired',        $countExpired,  'expired'],
            ];
        @endphp
        @foreach($qsRows as [$color, $label, $val, $filter])
        <div class="qs-row" role="button" tabindex="0"
             data-action="set-filter" data-filter="{{ $filter }}">
            <div class="qs-row-left">
                <div class="qs-dot qs-dot-dynamic" style="--qs-dot-color:{{ $color }}"></div>
                <span class="qs-label">{{ $label }}</span>
            </div>
            <span class="qs-val">{{ $val }}</span>
        </div>
        @endforeach
        <div class="qs-progress">
            <div class="qs-prog-label">
                <span>Overall funding progress</span>
                <span>{{ $overallPct }}%</span>
            </div>
            <div class="qs-prog-bar">
                <div class="qs-prog-fill qs-prog-fill--init" id="overallBar"></div>
            </div>
        </div>
    </div>
</div>

{{-- ══ WALLET MINI-VIEW ══ --}}
@if($recentTransactions->isNotEmpty())
<div class="wallet-mini-card">
    <div class="wallet-mini-hdr">
        <div class="wallet-mini-title">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
            Wallet Activity
        </div>
        <a href="{{ route('dashboard.wallet') }}" class="wallet-mini-link">Full Wallet →</a>
    </div>
    <div class="wallet-mini-bal">
        <div class="wallet-mini-bal-lbl">Available Balance</div>
        <div class="wallet-mini-bal-val">₹{{ number_format($wallet->available_balance, 2) }}</div>
    </div>
    <div class="wallet-mini-list">
        @foreach($recentTransactions as $tx)
        @php
            $txIcon = $tx->type === 'credit' ? 'plus' : 'minus';
            $txColor = $tx->type === 'credit' ? 'var(--green)' : 'var(--red)';
            $txLabel = ucfirst(str_replace('_', ' ', $tx->source));
        @endphp
        <div class="wallet-mini-item">
            <div class="wallet-mini-item-left">
                <div class="wallet-mini-ico {{ $tx->type === 'credit' ? 'wallet-mini-ico--credit' : 'wallet-mini-ico--debit' }}">
                    @if($tx->type === 'credit')
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"><path d="M12 5v14m-7-7h14"/></svg>
                    @else
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"><path d="M5 12h14"/></svg>
                    @endif
                </div>
                <div>
                    <div class="wallet-mini-lbl">{{ $txLabel }}</div>
                    <div class="wallet-mini-sub">{{ $tx->created_at->diffForHumans() }}</div>
                </div>
            </div>
            <div class="wallet-mini-amt {{ $tx->type === 'credit' ? 'wallet-mini-amt--credit' : 'wallet-mini-amt--debit' }}">
                {{ $tx->type === 'credit' ? '+' : '-' }}₹{{ number_format(abs($tx->amount), 2) }}
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- ══ CAMPAIGN COMPARISON BAR CHART ══ --}}
@php $campChartData = $campaigns->count() > 1 ? $campaigns->map(fn($c) => ['title' => Str::limit($c->title, 22), 'raised' => (float)$c->raised_amount, 'goal' => (float)$c->goal_amount])->values() : collect(); @endphp
@if($campaigns->count() > 1)
<div class="chart-card bar-chart-card">
    <div class="chart-card-hdr">
        <div>
            <div class="chart-title">Campaign Comparison</div>
            <div class="chart-sub">Raised vs goal per campaign</div>
        </div>
    </div>
    <div class="chart-wrap"><canvas id="campChart"></canvas></div>
</div>
@endif

{{-- ══ QUICK NAV ══ --}}
<div class="qnav">
    @php
    $navItems = [
        ['url'=> route('campaign.create'),       'lbl'=>'New Campaign',     'sub'=>'Start fundraising', 'delay'=>'.05s','bg'=>'var(--accent-lt)',          'color'=>'var(--accent)',  'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>'],
        ['url'=> url('/user/dashboard').'#cGrid','lbl'=>'All Campaigns',    'sub'=>$countAll.' total',   'delay'=>'.10s','bg'=>'var(--green-lt)',            'color'=>'var(--green)',   'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>'],
        ['url'=> route('profile.show'),           'lbl'=>'My Profile',       'sub'=>'View & edit',       'delay'=>'.15s','bg'=>'rgba(59,130,246,.10)',       'color'=>'var(--blue)',    'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>'],
        ['url'=> url('/user/kyc'),                'lbl'=>'KYC Status',       'sub'=>$kyc ? ucfirst($kyc->status) : 'Not submitted','delay'=>'.25s','bg'=>'var(--yellow-lt)', 'color'=>'var(--yellow)',  'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>'],
        ['url'=> route('recurring.index'),        'lbl'=>'Recurring',        'sub'=>'Manage donations',  'delay'=>'.30s','bg'=>'var(--pink-lt)',             'color'=>'var(--pink)',    'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>'],
        ['url'=> url('/user/dashboard/blogs'),    'lbl'=>'My Blogs',         'sub'=>$blogTotal.' posts', 'delay'=>'.35s','bg'=>'rgba(245,158,11,.10)',       'color'=>'var(--yellow)',  'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>'],
        ['url'=> url('/user/dashboard/blogs/create'),'lbl'=>'Write Blog',   'sub'=>'New post',          'delay'=>'.40s','bg'=>'rgba(16,185,129,.10)',       'color'=>'var(--green)',   'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>'],
        ['url'=> route('gift-cards.index'),       'lbl'=>'Gift Cards',       'sub'=>'Buy & redeem',      'delay'=>'.45s','bg'=>'rgba(236,72,153,.10)',       'color'=>'var(--pink)',    'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>'],
        ['url'=> route('dashboard.wallet'),        'lbl'=>'Wallet',           'sub'=>'View balance & payout','delay'=>'.50s','bg'=>'var(--primary-tint-bg)',         'color'=>'var(--primary)',  'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>'],
    ];
    @endphp
    @foreach($navItems as $item)
    <a href="{{ $item['url'] }}" class="qnav-card qnav-card-dynamic" style="--qnav-delay:{{ $item['delay'] }};--qnav-bg:{{ $item['bg'] }};">
        <div class="qnav-ico qnav-ico-dynamic" style="--qnav-bg:{{ $item['bg'] }};--qnav-color:{{ $item['color'] }};">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" data-icon="{{ $item['lbl'] }}">{!! $item['icon'] !!}</svg>
        </div>
        <div>
            <div class="qnav-lbl">{{ $item['lbl'] }}</div>
            <div class="qnav-sub">{{ $item['sub'] }}</div>
        </div>
    </a>
    @endforeach
</div>

{{-- ══ LEVEL PROGRESS + TOP CAMPAIGN ══ --}}
@php
    $achievements = [
        ['ico'=>'target','lbl'=>'First Campaign', 'sub'=>'Create your first campaign', 'done'=>$countAll > 0, 'color'=>'var(--blue)'],
        ['ico'=>'heart','lbl'=>'First Donation', 'sub'=>'Receive your first donation', 'done'=>$totalDonationsCount > 0, 'color'=>'var(--pink)'],
        ['ico'=>'shield','lbl'=>'KYC Verified', 'sub'=>'Complete identity verification', 'done'=>$kyc && $kyc->status === 'approved', 'color'=>'var(--green)'],
        ['ico'=>'zap','lbl'=>'Active Fundraiser', 'sub'=>'Have a live campaign', 'done'=>$countActive > 0, 'color'=>'var(--yellow)'],
        ['ico'=>'award','lbl'=>'Goal Crusher', 'sub'=>'Reach 100% on any campaign', 'done'=>$campaigns->contains(fn($c)=>($c->goal_amount>0 && ($c->raised_amount/$c->goal_amount)>=1)), 'color'=>'var(--accent)'],
        ['ico'=>'refresh','lbl'=>'Recurring Ready', 'sub'=>'Set up recurring donations', 'done'=>$recurringCount > 0, 'color'=>'var(--primary)'],
    ];
    $earnedCount = count(array_filter($achievements, fn($a)=>$a['done']));
@endphp
<div class="insight-row">
    @if($nextLevel)
    <div class="insight-card level-card">
        <div class="insight-card-hdr">
            <div class="insight-card-title">Fundraiser Level</div>
            <div class="level-badge level-badge-dynamic" style="--level-badge-color:{{ $currentLevelModel?->badge_color ?? 'var(--accent)' }}">{{ $levelName }}</div>
        </div>
        <div class="level-next">Next: <strong>{{ $nextLevel->level_name }}</strong></div>
        <div class="level-bar-wrap">
            <div class="level-bar">
                <div class="level-fill level-fill--init" id="levelFill"></div>
            </div>
            <div class="level-pct">{{ $levelProgress }}%</div>
        </div>
        <div class="level-reqs">
            <div class="level-req {{ $campaignsCompleted >= $nextLevel->min_campaigns_completed ? 'done' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                {{ $campaignsCompleted }}/{{ $nextLevel->min_campaigns_completed }} campaigns
            </div>
        </div>
    </div>
    @endif

    @if($topCampaign)
    @php
        $tcr = $topCampaign->raised_amount ?? 0;
        $tcg = $topCampaign->goal_amount > 0 ? $topCampaign->goal_amount : 1;
        $tcp = min(100, round(($tcr / $tcg) * 100));
        $tcCircum = 2 * 3.14159 * 36;
        $tcOffset = $tcCircum - ($tcp / 100) * $tcCircum;
    @endphp
    <div class="insight-card top-campaign-card">
        <div class="insight-card-hdr">
            <div class="insight-card-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="tpc-star-svg"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                Top Performer
            </div>
            <span class="badge b-active">Active</span>
        </div>

        <svg class="svg-hidden" aria-hidden="true">
            <defs>
                <linearGradient id="tpcRingGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" stop-color="var(--primary)"/>
                    <stop offset="100%" stop-color="var(--secondary)"/>
                </linearGradient>
            </defs>
        </svg>

        <div class="tpc-ring-wrap">
            <svg viewBox="0 0 80 80" class="tpc-ring-svg">
                <circle class="tpc-ring-bg" cx="40" cy="40" r="36"/>
                <circle class="tpc-ring-fg tpc-ring-dynamic" id="tpcRing" cx="40" cy="40" r="36"
                    style="--tpc-circum:{{ $tcCircum }};--tpc-offset:{{ $tcOffset }}"/>
            </svg>
            <div class="tpc-ring-center">
                <div class="tpc-ring-pct">{{ $tcp }}%</div>
                <div class="tpc-ring-label">Funded</div>
            </div>
        </div>

        <div class="tpc-meta">
            @if($topCampaign->category)
                <span class="tpc-meta-chip cat">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 01-2 2H4a2 2 0 01-2-2V5a2 2 0 012-2h5l2 3h9a2 2 0 012 2z"/></svg>
                    {{ $topCampaign->category->name }}
                </span>
            @endif
            <span class="tpc-meta-chip goal">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V7m0 1v8m0 1v1"/></svg>
                ₹{{ number_format($tcg) }} goal
            </span>
        </div>

        <div class="tpc-title">{{ $topCampaign->title }}</div>

        <div class="tpc-stats">
            <div class="tpc-stat">
                <div class="tpc-stat-val">₹{{ number_format($tcr) }}</div>
                <div class="tpc-stat-lbl">Raised</div>
            </div>
            <div class="tpc-stat">
                <div class="tpc-stat-val">{{ $topCampaign->donations_count }}</div>
                <div class="tpc-stat-lbl">Donors</div>
            </div>
            <div class="tpc-stat">
                <div class="tpc-stat-val">{{ $tcp }}%</div>
                <div class="tpc-stat-lbl">Funded</div>
            </div>
        </div>

        <a href="{{ route('campaign.show', $topCampaign->id) }}" class="tpc-link">
            View Campaign
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14m-7-7l7 7-7 7"/></svg>
        </a>
    </div>
    @endif
</div>

{{-- ══ ACHIEVEMENT BADGES ══ --}}
@php
    $achievePct = count($achievements) > 0 ? round(($earnedCount / count($achievements)) * 100) : 0;
@endphp
<div class="achieve-card">
    <div class="achieve-hdr">
        <div class="achieve-hdr-left">
            <div class="achieve-title-row">
                <div class="achieve-trophy">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9H4.5a2.5 2.5 0 010-5H6"/><path d="M18 9h1.5a2.5 2.5 0 000-5H18"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/><path d="M18 2H6v7a6 6 0 0012 0V2Z"/></svg>
                </div>
                <div class="achieve-title">Achievements</div>
            </div>
            <div class="achieve-subtitle">Complete milestones to unlock badges</div>
        </div>
        <div class="achieve-progress-wrap">
            <div class="achieve-progress-header">
                <span class="achieve-progress-label">{{ $earnedCount }} of {{ count($achievements) }}</span>
                <span class="achieve-progress-pct">{{ $achievePct }}%</span>
            </div>
            <div class="achieve-progress-bar">
                <div class="achieve-progress-fill achieve-progress-fill-dynamic" style="--achieve-width:{{ $achievePct }}%"></div>
            </div>
        </div>
    </div>
    <div class="achieve-grid">
        @foreach($achievements as $a)
        <div class="achieve-item {{ $a['done'] ? 'earned' : '' }}" title="{{ $a['sub'] }}">
            <div class="achieve-ico achieve-ico-dynamic" style="--achieve-color:{{ $a['color'] }}">
                @if($a['done'])
                <div class="achieve-check">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                </div>
                @endif
                @if($a['ico']==='target')
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg>
                @elseif($a['ico']==='heart')
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
                @elseif($a['ico']==='shield')
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                @elseif($a['ico']==='zap')
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                @elseif($a['ico']==='award')
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="6"/><path d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11"/></svg>
                @elseif($a['ico']==='refresh')
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 4v6h-6M1 20v-6h6"/><path d="M3.51 9a9 9 0 0114.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0020.49 15"/></svg>
                @endif
            </div>
            <div class="achieve-info">
                <div class="achieve-lbl">{{ $a['lbl'] }}</div>
                <div class="achieve-desc">{{ $a['sub'] }}</div>
            </div>
            @if(!$a['done'])
            <div class="achieve-lock">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
            </div>
            @endif
        </div>
        @endforeach
    </div>
</div>

{{-- ══ UPCOMING EVENTS ══ --}}
@php
    $allEvents = $myEvents->merge($registeredEvents->pluck('event'))->sortBy('event_date')->take(5);
@endphp
@if($allEvents->isNotEmpty())
<div class="events-card">
    <div class="events-hdr">
        <div class="events-title">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
            Upcoming Events
        </div>
        @if($allEvents->count() > 3)
        <a href="{{ url('/events') }}" class="events-link">View all →</a>
        @endif
    </div>
    <div class="events-list">
        @foreach($allEvents as $event)
        @php
            $daysUntil = now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($event->event_date)->startOfDay(), false);
            $isUrgent = $daysUntil !== null && $daysUntil >= 0 && $daysUntil <= 3;
            $isToday = $daysUntil === 0;
        @endphp
        <div class="events-item">
            <div class="events-date {{ $isUrgent ? 'urgent' : '' }}">
                <div class="events-date-day">{{ \Carbon\Carbon::parse($event->event_date)->format('d') }}</div>
                <div class="events-date-mon">{{ \Carbon\Carbon::parse($event->event_date)->format('M') }}</div>
            </div>
            <div class="events-body">
                <div class="events-body-title">{{ $event->title }}</div>
                <div class="events-body-meta">
                    @if($event->location)
                    <span>
                         <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="events-location-svg"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        {{ $event->location }}
                    </span>
                    @endif
                    @if($daysUntil !== null && $daysUntil >= 0)
                    <span class="events-countdown {{ $isUrgent ? 'urgent' : '' }}">
                        @if($isToday)
                        Today
                        @elseif($daysUntil === 1)
                        Tomorrow
                        @else
                        {{ $daysUntil }} days away
                        @endif
                    </span>
                    @elseif($daysUntil !== null && $daysUntil < 0)
                    <span class="events-countdown">Past</span>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- ══ RECENT BLOG POSTS ══ --}}
@if($recentBlogs->isNotEmpty())
<div class="insight-card blog-section">
    <div class="insight-card-hdr">
        <div class="insight-card-title">Recent Blog Posts</div>
        <a href="{{ url('/user/dashboard/blogs') }}" class="insight-link">View all →</a>
    </div>
    <div class="blog-list">
        @foreach($recentBlogs as $blog)
        @php
            $bs = $blog->status;
            $bc = $bs === 'approved' ? 'b-active' : ($bs === 'pending' ? 'b-pending' : ($bs === 'draft' ? 'b-paused' : 'b-default'));
            $bl = ucfirst($bs);
        @endphp
        <div class="blog-item">
            <div class="blog-info">
                <div class="blog-title">{{ $blog->title }}</div>
                <div class="blog-meta">
                    <span class="badge {{ $bc }} badge-sm">{{ $bl }}</span>
                    <span>{{ $blog->views_count }} views</span>
                    <span>{{ $blog->created_at->diffForHumans() }}</span>
                </div>
            </div>
            <x-button variant="secondary" href="{{ url('/user/dashboard/blogs') }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                View
            </x-button>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- ══ CAMPAIGNS SECTION ══ --}}
<div class="sec-hdr" id="cGrid">
    <div class="sec-title">
        Your Campaigns
            @if($campaigns->total() > 0)
                <span class="text-meta">
                    {{ $campaigns->firstItem() }}–{{ $campaigns->lastItem() }} of {{ $campaigns->total() }}
                </span>
            @endif
    </div>
    <div class="sec-right">
        <div class="ftabs" id="ftabs">
            <button class="ftab on" data-filter="all">All <span class="cnt">{{ $countAll }}</span></button>
            <button class="ftab" data-filter="active">Active <span class="cnt">{{ $countActive }}</span></button>
            <button class="ftab" data-filter="inactive">Awaiting <span class="cnt">{{ $countInactive }}</span></button>
            <button class="ftab" data-filter="pending">Pending <span class="cnt">{{ $countPending }}</span></button>
            <button class="ftab" data-filter="paused">Paused <span class="cnt">{{ $countPaused }}</span></button>
            <button class="ftab" data-filter="rejected">Rejected <span class="cnt">{{ $countRejected }}</span></button>
            <button class="ftab" data-filter="expired">Expired <span class="cnt">{{ $countExpired }}</span></button>
        </div>
        <select class="ftab-select" id="ftabSelect">
            <option value="all">All ({{ $countAll }})</option>
            <option value="active">Active ({{ $countActive }})</option>
            <option value="inactive">Awaiting ({{ $countInactive }})</option>
            <option value="pending">Pending ({{ $countPending }})</option>
            <option value="paused">Paused ({{ $countPaused }})</option>
            <option value="rejected">Rejected ({{ $countRejected }})</option>
            <option value="expired">Expired ({{ $countExpired }})</option>
        </select>
        <select class="view-select" id="viewSelect">
                <option value="grid">Grid View</option>
                <option value="list">List View</option>
            </select>
    </div>
</div>

<div id="noResults">No campaigns match this filter.</div>

@if($campaigns->count() > 0)

{{-- GRID VIEW --}}
<div class="c-grid" id="campaignGrid">
    @foreach($campaigns as $i => $campaign)
        <x-campaign-card :campaign="$campaign" variant="grid" :index="$i" />
    @endforeach
</div>

{{-- LIST VIEW --}}
<div class="c-list c-list-hidden" id="campaignList">
    @foreach($campaigns as $i => $campaign)
        <x-campaign-card :campaign="$campaign" variant="list" :index="$i" />
    @endforeach
</div>

@if($campaigns->hasPages())
<div class="rd-pagination mt-18">
    {{ $campaigns->links() }}
</div>
@endif

@else
<div class="empty-state">
    <div class="empty-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    </div>
    <div class="empty-title">Start your first fundraiser</div>
    <div class="empty-sub">Create a campaign and start making a difference in the world today.</div>
    <x-button variant="primary" href="{{ route('campaign.create') }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        Create Campaign
    </x-button>
</div>
@endif

{{-- ══ RECURRING DONATIONS SECTION ══ --}}
@if(isset($recurringDonations) && $recurringDonations->count() > 0)
<div class="sec-hdr mt-32">
    <div class="sec-title">Recurring Donations</div>
    <div class="sec-right">
        <a href="{{ route('recurring.index') }}" class="sec-link">View all →</a>
    </div>
</div>
<div class="flex-column-gap">
    @foreach($recurringDonations->take(3) as $rd)
    <div class="rec-card">
        <div class="rec-icon rec-icon-accent">
            <svg viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
        </div>
        <div class="rec-info">
            <div class="rec-title">{{ $rd->campaign->title ?? 'Campaign' }}</div>
            <div class="rec-sub">Next: {{ $rd->next_billing_date ? \Carbon\Carbon::parse($rd->next_billing_date)->format('d M Y') : 'N/A' }} · {{ ucfirst($rd->status) }}</div>
        </div>
        <div class="rec-amount">
            <span class="rec-amt-val">₹{{ number_format($rd->amount) }}/{{ $rd->frequency }}</span>
        </div>
        <div class="rec-actions">
            <x-button variant="secondary" href="{{ route('recurring.show', $rd->id) }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                View
            </x-button>
            @if($rd->status === 'active')
            <form action="{{ route('recurring.pause', $rd->id) }}" method="POST">
                @csrf @method('PATCH')
                <x-button variant="secondary" type="submit">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 9v6m4-6v6"/></svg>Pause
                </x-button>
            </form>
            @elseif($rd->status === 'paused')
            <form action="{{ route('recurring.resume', $rd->id) }}" method="POST">
                @csrf @method('PATCH')
                <x-button variant="primary" type="submit">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/></svg>Resume
                </x-button>
            </form>
            @endif
        </div>
    </div>
    @endforeach
</div>
@endif
@endsection

{{-- Server data for user/dashboard.js --}}
<script type="application/json" id="dashboardData">
@json($userDashboardData)
</script>

@push('page_scripts')
@vite('resources/js/user/dashboard.js')
@endpush