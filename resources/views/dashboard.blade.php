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
    $blogTotal     = \App\Models\Blog::where('author_id', auth()->id())->count();
@endphp

{{-- ══ WELCOME BANNER ══ --}}
<div class="welcome-banner">
    <div class="wb-left">
        <div class="wb-tag">
            <span class="wb-tag-dot"></span>
            Good {{ $greeting }}, Fundraiser
            @if($levelName !== 'Starter')
                <span class="wb-badge wbb-purple" style="margin-left:8px;font-size:10px;">{{ $levelName }}</span>
            @endif
        </div>
        <div class="wb-name">{{ auth()->user()->name }} <span class="wave">👋</span></div>
        <div class="wb-sub" style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
            <span>Here's what's happening with your campaigns today.</span>
            @if($daysActive > 0)
                <span style="font-size:11.5px;color:var(--text3);font-family:var(--mono);">
                    Member for {{ $daysActive }} day{{ $daysActive !== 1 ? 's' : '' }}
                </span>
            @endif
            @if($level)
                <span style="font-size:11.5px;color:var(--text3);font-family:var(--mono);">
                    {{ $levelName }} · Max goal ₹{{ number_format($user->maxCampaignGoal()) }}
                </span>
            @endif
        </div>
        <div class="wb-badges">
            @if($countActive > 0)
                <span class="wb-badge wbb-green">✓ {{ $countActive }} live</span>
            @endif
            @if($countPending > 0)
                <span class="wb-badge wbb-yellow">⏱ {{ $countPending }} pending review</span>
            @endif
            @if($countRejected > 0)
                <span class="wb-badge wbb-red">✕ {{ $countRejected }} rejected</span>
            @endif
            @if($overallPct > 0)
                <span class="wb-badge wbb-purple">{{ $overallPct }}% overall funded</span>
            @endif
            @if($countAll === 0)
                <span class="wb-badge wbb-purple">Get started — create your first campaign</span>
            @endif
        </div>
    </div>
    <div class="wb-right">
        <a href="{{ route('campaign.create') }}" class="btn btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            New Campaign
        </a>
        @if(!$kyc || $kyc->status !== 'approved')
        <a href="{{ url('/user/kyc') }}" class="btn btn-yellow">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            {{ $kyc ? 'KYC '.ucfirst($kyc->status) : 'Submit KYC' }}
        </a>
        @endif
        <a href="{{ route('profile.show') }}" class="btn btn-secondary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            My Profile
        </a>
    </div>
</div>

{{-- ══ STATS (5 cards) ══ --}}
@php $avgDonation = $totalDonationsCount > 0 ? round($totalRaised / $totalDonationsCount) : 0; @endphp
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon-wrap si-indigo">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="stat-info">
            <div class="stat-label">Total Raised</div>
            <div class="stat-val sv-indigo">₹{{ number_format($totalRaised, 0) }}</div>
            <div class="stat-foot">{{ $overallPct }}% of total goal</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon-wrap si-pink">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
        </div>
        <div class="stat-info">
            <div class="stat-label">Total Goal</div>
            <div class="stat-val sv-pink">₹{{ number_format($totalGoal, 0) }}</div>
            <div class="stat-foot">Across {{ $countAll }} campaigns</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon-wrap si-green">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="stat-info">
            <div class="stat-label">Active Campaigns</div>
            <div class="stat-val sv-green">{{ $countActive }}</div>
            <div class="stat-foot">Live &amp; accepting donations</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon-wrap si-yellow">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 7v10c0 2 1 3 3 3h10c2 0 3-1 3-3V7c0-2-1-3-3-3H7C5 4 4 5 4 7z"/><polyline points="16 10 12 14 8 10"/></svg>
        </div>
        <div class="stat-info">
            <div class="stat-label">Total Donations</div>
            <div class="stat-val sv-yellow">{{ number_format($totalDonationsCount) }}</div>
            <div class="stat-foot">Avg ₹{{ number_format($avgDonation) }} per donation</div>
        </div>
    </div>
    <a href="{{ url('/user/dashboard') }}#cGrid" class="stat-card" style="cursor:pointer;text-decoration:none;display:flex;">
        <div class="stat-icon-wrap si-blue">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
        </div>
        <div class="stat-info">
            <div class="stat-label">All Campaigns</div>
            <div class="stat-val sv-blue">{{ $countAll }}</div>
            <div class="stat-foot">View all →</div>
        </div>
    </a>
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
        <div class="activity-item">
            <div class="activity-dot-col">
                <div class="activity-dot d-green"><span class="ad-letter">{{ $initial }}</span></div>
                <div class="activity-line"></div>
            </div>
            <div class="activity-body">
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
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- ══ ONBOARDING CHECKLIST (new users) ══ --}}
@php
    $checklist = [];
    if (!$kyc || $kyc->status !== 'approved') {
        $checklist[] = ['label' => 'Complete KYC Verification', 'sub' => 'Submit identity documents', 'url' => url('/user/kyc'), 'done' => false];
    } else {
        $checklist[] = ['label' => 'KYC Verified', 'sub' => 'Identity confirmed', 'url' => '#', 'done' => true];
    }
    $campaignCount = $campaigns->count();
    if ($campaignCount === 0) {
        $checklist[] = ['label' => 'Create Your First Campaign', 'sub' => 'Start fundraising', 'url' => route('campaign.create'), 'done' => false];
    } else {
        $checklist[] = ['label' => 'Campaigns Created', 'sub' => $campaignCount.' campaign(s) live', 'url' => '#', 'done' => true];
    }
    $pendingItems = array_filter($checklist, fn($i) => !$i['done']);
@endphp
@if(!empty($pendingItems))
<div class="checklist-card">
    <div class="checklist-title">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
        Getting Started
    </div>
    <div class="checklist-grid">
        @foreach($checklist as $item)
        <div class="checklist-item {{ $item['done'] ? 'done' : '' }}">
            <div class="checklist-ico {{ $item['done'] ? 'done' : 'pending' }}">
                @if($item['done'])
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                @else
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                @endif
            </div>
            <div class="checklist-body">
                <div class="checklist-lbl">{{ $item['label'] }}</div>
                <div class="checklist-sub">{{ $item['sub'] }}</div>
            </div>
            @if(!$item['done'])
            <a href="{{ $item['url'] }}" class="checklist-action">Go →</a>
            @endif
        </div>
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

    <div class="qs-panel">
        <div class="qs-title">Campaign Status</div>
        @php
            $qsRows = [
                ['var(--green)',  'Active',         $countActive],
                ['var(--blue)',   'Awaiting Review',$countInactive],
                ['var(--yellow)', 'Pending',        $countPending],
                ['var(--accent)', 'Paused',         $countPaused],
                ['var(--red)',    'Rejected',       $countRejected],
                ['var(--gray)',   'Expired',        $countExpired],
            ];
        @endphp
        @foreach($qsRows as [$color, $label, $val])
        <div class="qs-row" onclick="setFilter('{{ strtolower(str_replace(' ','-',$label)) }}')">
            <div class="qs-row-left">
                <div class="qs-dot" style="background:{{ $color }}"></div>
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
                <div class="qs-prog-fill" id="overallBar" style="width:0%"></div>
            </div>
        </div>
    </div>
</div>

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
    ];
    @endphp
    @foreach($navItems as $item)
    <a href="{{ $item['url'] }}" class="qnav-card" style="animation-delay:{{ $item['delay'] }};--qc:{{ $item['bg'] }};">
        <div class="qnav-ico" style="background:{{ $item['bg'] }};color:{{ $item['color'] }};">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" data-icon="{{ $item['lbl'] }}">{!! $item['icon'] !!}</svg>
        </div>
        <div>
            <div class="qnav-lbl">{{ $item['lbl'] }}</div>
            <div class="qnav-sub">{{ $item['sub'] }}</div>
        </div>
    </a>
    @endforeach
</div>

{{-- ══ CAMPAIGNS SECTION ══ --}}
<div class="sec-hdr" id="cGrid">
    <div class="sec-title">
        Your Campaigns
        @if($campaigns->total() > 0)
            <span style="font-size:11px;font-weight:400;color:var(--text3);font-family:var(--mono);margin-left:8px;">
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
        <div class="view-toggle">
            <button class="vt-btn on" id="btnGrid" title="Grid view">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
            </button>
            <button class="vt-btn" id="btnList" title="List view">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
            </button>
        </div>
    </div>
</div>

<div id="noResults">No campaigns match this filter.</div>

@if($campaigns->count() > 0)

{{-- GRID VIEW --}}
<div class="c-grid" id="campaignGrid">
    @foreach($campaigns as $i => $campaign)
    @php
        $state = $campaign->campaign_state;
        if      ($state === 'active')   { $fv='active';   $bc='b-active';   $bl='Active'; }
        elseif  ($state === 'paused')   { $fv='paused';   $bc='b-paused';   $bl='Paused'; }
        elseif  ($state === 'rejected') { $fv='rejected'; $bc='b-rejected'; $bl='Rejected'; }
        elseif  ($state === 'expired')  { $fv='expired';  $bc='b-expired';  $bl='Expired'; }
        elseif  ($state === 'inactive') { $fv='inactive'; $bc='b-inactive'; $bl='Under Review'; }
        elseif  ($state === 'pending')  { $fv='pending';  $bc='b-pending';  $bl='Pending'; }
        else                            { $fv='other';    $bc='b-default';  $bl=ucfirst($state ?? 'Draft'); }
        $raised = $campaign->raised_amount ?? 0;
        $goal   = $campaign->goal_amount > 0 ? $campaign->goal_amount : 1;
        $pct    = min(100, round(($raised / $goal) * 100));
    @endphp
    @php
        $daysLeft = $campaign->end_date ? now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($campaign->end_date)->startOfDay(), false) : null;
        $campCats = $campaign->category;
    @endphp
    <div class="c-card"
         data-filter="{{ $fv }}"
         data-title="{{ strtolower($campaign->title) }}"
         data-amount="{{ $campaign->goal_amount }}"
         data-date="{{ $campaign->created_at }}"
         style="animation-delay:{{ $i * .04 }}s">
        <div class="c-thumb">
            @if($campaign->cover_image)
                <img src="{{ asset('storage/'.$campaign->cover_image) }}" alt="{{ $campaign->title }}" loading="lazy">
                <div class="c-thumb-overlay"></div>
            @else
                <div class="c-thumb-placeholder">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                </div>
            @endif
            <div class="c-badge-wrap">
                <span class="badge {{ $bc }}">{{ $bl }}</span>
                @if($fv === 'active' && $daysLeft !== null && $daysLeft >= 0)
                    <span class="badge b-active" style="margin-left:4px;">
                        @if($daysLeft === 0)
                            Ends today
                        @elseif($daysLeft <= 3)
                            {{ $daysLeft }} day{{ $daysLeft !== 1 ? 's' : '' }} left
                        @else
                            {{ $daysLeft }}d left
                        @endif
                    </span>
                @elseif($fv === 'expired')
                    <span class="badge b-expired" style="margin-left:4px;">Ended</span>
                @endif
            </div>
        </div>
        <div class="c-body">
            <div class="c-title">
                {{ $campaign->title }}
                @if($campCats)
                    <span style="display:inline-block;font-size:10px;font-weight:500;color:var(--text3);font-family:var(--mono);margin-left:6px;">{{ $campCats->name }}</span>
                @endif
            </div>

            @if($fv === 'inactive')
            <div class="reason reason-b">
                <div class="reason-lbl">⏳ Awaiting admin review</div>
                <div class="reason-txt">Your campaign will go live once approved.</div>
            </div>
            @elseif($fv === 'pending')
            <div class="reason reason-b">
                <div class="reason-lbl">Pending submission</div>
                <div class="reason-txt">Waiting to be reviewed by an admin.</div>
            </div>
            @elseif($fv === 'rejected' && $campaign->rejection_reason)
            <div class="reason reason-r">
                <div class="reason-lbl">✕ Rejection reason</div>
                <div class="reason-txt">{{ $campaign->rejection_reason }}</div>
            </div>
            @elseif($fv === 'paused' && $campaign->pause_reason)
            <div class="reason reason-y">
                <div class="reason-lbl">⏸ Pause reason</div>
                <div class="reason-txt">{{ $campaign->pause_reason }}</div>
            </div>
            @elseif($fv === 'expired')
            <div class="reason reason-g">
                <div class="reason-lbl">Expired</div>
                <div class="reason-txt">This campaign has ended. Create a new one to continue.</div>
            </div>
            @endif

            <div class="prog-wrap">
                <div class="prog-numbers">
                    <span class="prog-raised">₹{{ number_format($raised) }}</span>
                    <span class="prog-goal">of ₹{{ number_format($campaign->goal_amount) }}</span>
                </div>
                <div class="prog-bar"><div class="prog-fill" style="width:{{ $pct }}%"></div></div>
                <div class="prog-meta">
                    <span class="prog-pct">{{ $pct }}% funded</span>
                    @if($campaign->donations_count > 0)
                        <span style="margin-left:auto;font-size:10.5px;color:var(--text3);">{{ $campaign->donations_count }} donation{{ $campaign->donations_count !== 1 ? 's' : '' }}</span>
                    @endif
                </div>
            </div>

            <div class="c-actions">
                <a href="{{ route('campaign.show', $campaign->id) }}" class="btn btn-accent">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    View
                </a>
                <a href="{{ route('campaign.edit', $campaign->id) }}" class="btn btn-secondary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Edit
                </a>
                @if($fv === 'active')
                <form action="{{ route('campaign.pause', $campaign->id) }}" method="POST" style="flex:1;" onsubmit="return handleSub(this,'Pausing…')">
                    @csrf
                    <button class="btn btn-red" style="width:100%;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Pause
                    </button>
                </form>
                @elseif($fv === 'paused')
                <form action="{{ route('campaign.resume', $campaign->id) }}" method="POST" style="flex:1;" onsubmit="return handleSub(this,'Resuming…')">
                    @csrf
                    <button class="btn btn-green" style="width:100%;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Resume
                    </button>
                </form>
                @elseif($fv === 'rejected')
                <form action="{{ route('campaign.resubmit', $campaign->id) }}" method="POST" style="flex:1;" onsubmit="return handleSub(this,'Resubmitting…')">
                    @csrf
                    <button class="btn btn-green" style="width:100%;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        Resubmit
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- LIST VIEW --}}
<div class="c-list" id="campaignList" style="display:none;">
    @foreach($campaigns as $i => $campaign)
    @php
        $state = $campaign->campaign_state;
        if      ($state === 'active')   { $fv='active';   $bc='b-active';   $bl='Active'; }
        elseif  ($state === 'paused')   { $fv='paused';   $bc='b-paused';   $bl='Paused'; }
        elseif  ($state === 'rejected') { $fv='rejected'; $bc='b-rejected'; $bl='Rejected'; }
        elseif  ($state === 'expired')  { $fv='expired';  $bc='b-expired';  $bl='Expired'; }
        elseif  ($state === 'inactive') { $fv='inactive'; $bc='b-inactive'; $bl='Under Review'; }
        elseif  ($state === 'pending')  { $fv='pending';  $bc='b-pending';  $bl='Pending'; }
        else                            { $fv='other';    $bc='b-default';  $bl=ucfirst($state ?? 'Draft'); }
        $raised = $campaign->raised_amount ?? 0;
        $goal   = $campaign->goal_amount > 0 ? $campaign->goal_amount : 1;
        $pct    = min(100, round(($raised / $goal) * 100));
    @endphp
    @php
        $daysLeft = $campaign->end_date ? now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($campaign->end_date)->startOfDay(), false) : null;
        $campCats = $campaign->category;
    @endphp
    <div class="c-list-item"
         data-filter="{{ $fv }}"
         data-title="{{ strtolower($campaign->title) }}"
         data-amount="{{ $campaign->goal_amount }}"
         data-date="{{ $campaign->created_at }}"
         style="animation-delay:{{ $i * .03 }}s">
        <div class="c-list-thumb">
            @if($campaign->cover_image)
                <img src="{{ asset('storage/'.$campaign->cover_image) }}" alt="{{ $campaign->title }}" loading="lazy">
            @else
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
            @endif
        </div>
        <div class="c-list-info">
            <div class="c-list-title">
                {{ $campaign->title }}
                @if($campCats)
                    <span style="font-size:10px;font-weight:500;color:var(--text3);font-family:var(--mono);margin-left:6px;">{{ $campCats->name }}</span>
                @endif
            </div>
            <div class="c-list-sub">
                <span>₹{{ number_format($raised) }} raised</span>
                <span class="c-list-dot"></span>
                <span>of ₹{{ number_format($campaign->goal_amount) }}</span>
                @if($campaign->donations_count > 0)
                    <span class="c-list-dot"></span>
                    <span>{{ $campaign->donations_count }} donation{{ $campaign->donations_count !== 1 ? 's' : '' }}</span>
                @endif
                @if($fv === 'active' && $daysLeft !== null && $daysLeft >= 0)
                    <span class="c-list-dot"></span>
                    <span style="color:var(--yellow);font-weight:600;">
                        @if($daysLeft === 0) Ends today
                        @elseif($daysLeft <= 3) {{ $daysLeft }}d left
                        @else {{ $daysLeft }}d
                        @endif
                    </span>
                @endif
            </div>
        </div>
        <div class="c-list-prog">
            <div class="c-list-pct">{{ $pct }}%</div>
            <div class="c-list-bar"><div class="c-list-fill" style="width:{{ $pct }}%"></div></div>
        </div>
        <div class="c-list-badge">
            <span class="badge {{ $bc }}">{{ $bl }}</span>
            @if($fv === 'expired')
                <span class="badge b-expired" style="margin-left:3px;">Ended</span>
            @endif
        </div>
        <div class="c-list-actions">
            <a href="{{ route('campaign.show', $campaign->id) }}" class="btn btn-secondary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>View
            </a>
            <a href="{{ route('campaign.edit', $campaign->id) }}" class="btn btn-secondary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>Edit
            </a>
            @if($fv === 'active')
            <form action="{{ route('campaign.pause', $campaign->id) }}" method="POST" onsubmit="return handleSub(this,'…')">
                @csrf
                <button class="btn btn-secondary" style="border-color:rgba(245,158,11,.3);color:var(--yellow);">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>Pause
                </button>
            </form>
            @elseif($fv === 'paused')
            <form action="{{ route('campaign.resume', $campaign->id) }}" method="POST" onsubmit="return handleSub(this,'…')">
                @csrf
                <button class="btn btn-secondary" style="border-color:rgba(16,185,129,.3);color:var(--green);">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>Resume
                </button>
            </form>
            @endif
        </div>
    </div>
    @endforeach
</div>

@if($campaigns->hasPages())
<div class="rd-pagination" style="margin-top:18px;">
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
    <a href="{{ route('campaign.create') }}" class="btn btn-primary">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        Create Campaign
    </a>
</div>
@endif

{{-- ══ RECURRING DONATIONS SECTION ══ --}}
@if(isset($recurringDonations) && $recurringDonations->count() > 0)
<div class="sec-hdr" style="margin-top:32px;">
    <div class="sec-title">Recurring Donations</div>
    <div class="sec-right">
        <a href="{{ route('recurring.index') }}" style="font-size:12.5px;color:var(--accent);font-weight:600;">View all →</a>
    </div>
</div>
<div style="display:flex;flex-direction:column;gap:10px;">
    @foreach($recurringDonations->take(3) as $rd)
    <div class="rec-card">
        <div class="rec-icon" style="background:var(--accent-lt);">
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
            <a href="{{ route('recurring.show', $rd->id) }}" class="btn btn-secondary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                View
            </a>
            @if($rd->status === 'active')
            <form action="{{ route('recurring.pause', $rd->id) }}" method="POST">
                @csrf @method('PATCH')
                <button class="btn btn-secondary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 9v6m4-6v6"/></svg>Pause
                </button>
            </form>
            @elseif($rd->status === 'paused')
            <form action="{{ route('recurring.resume', $rd->id) }}" method="POST">
                @csrf @method('PATCH')
                <button class="btn btn-green">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/></svg>Resume
                </button>
            </form>
            @endif
        </div>
    </div>
    @endforeach
</div>
@endif
@endsection

@push('page_scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
'use strict';

var html = document.documentElement;

/* ── Animated stat counters ── */
function animateCounter(el, target, suffix) {
    var duration = 900, start = 0, startTime = null;
    suffix = suffix || '';
    function step(timestamp) {
        if (!startTime) startTime = timestamp;
        var progress = Math.min((timestamp - startTime) / duration, 1);
        var eased = 1 - Math.pow(1 - progress, 3);
        var current = Math.floor(eased * target);
        el.textContent = current.toLocaleString('en-IN') + suffix;
        if (progress < 1) requestAnimationFrame(step);
    }
    requestAnimationFrame(step);
}
document.querySelectorAll('.stat-val').forEach(function (el) {
    var raw = el.textContent.replace(/[₹,]/g, '').trim();
    var num = parseInt(raw, 10);
    if (!isNaN(num) && num > 0) {
        var suffix = el.textContent.includes('%') ? '%' : '';
        el.textContent = '0' + suffix;
        animateCounter(el, num, suffix);
    }
});

/* ── Animate overall funding bar ── */
setTimeout(function(){
    var bar = document.getElementById('overallBar');
    if (bar) bar.style.width = '{{ $overallPct }}%';
}, 700);

/* ── Filter + Search + Sort ── */
var activeFilter = 'all', searchQ = '', sortVal = '';

function getCards(){
    var isGrid = document.getElementById('campaignGrid').style.display !== 'none';
    return Array.from(document.querySelectorAll(isGrid ? '#campaignGrid .c-card' : '#campaignList .c-list-item'));
}

function applyFilters(){
    var all = Array.from(document.querySelectorAll('#campaignGrid .c-card, #campaignList .c-list-item'));

    if (sortVal) {
        var grids = Array.from(document.querySelectorAll('#campaignGrid .c-card'));
        var lists = Array.from(document.querySelectorAll('#campaignList .c-list-item'));
        [grids, lists].forEach(function(arr){
            if (!arr.length) return;
            if (sortVal === 'amount-desc') arr.sort(function(a,b){ return +b.dataset.amount - +a.dataset.amount; });
            if (sortVal === 'amount-asc')  arr.sort(function(a,b){ return +a.dataset.amount - +b.dataset.amount; });
            if (sortVal === 'date-desc')   arr.sort(function(a,b){ return new Date(b.dataset.date) - new Date(a.dataset.date); });
            if (sortVal === 'date-asc')    arr.sort(function(a,b){ return new Date(a.dataset.date) - new Date(b.dataset.date); });
            var parent = arr[0].parentElement;
            arr.forEach(function(c){ parent.appendChild(c); });
        });
    }

    var visible = 0;
    all.forEach(function(c){
        var mF = activeFilter === 'all' || c.dataset.filter === activeFilter;
        var mS = !searchQ || (c.dataset.title || '').includes(searchQ);
        c.style.display = (mF && mS) ? '' : 'none';
        if (mF && mS) visible++;
    });
    document.getElementById('noResults').style.display = visible === 0 ? 'block' : 'none';
}

document.querySelectorAll('.ftab').forEach(function(tab){
    tab.addEventListener('click', function(){
        document.querySelectorAll('.ftab').forEach(function(t){ t.classList.remove('on'); });
        this.classList.add('on');
        activeFilter = this.dataset.filter;
        applyFilters();
    });
});

window.setFilter = function(f){
    activeFilter = f;
    document.querySelectorAll('.ftab').forEach(function(t){ t.classList.toggle('on', t.dataset.filter === f); });
    applyFilters();
    var el = document.getElementById('cGrid');
    if (el) el.scrollIntoView({ behavior:'smooth', block:'start' });
};

var searchTimeout;
document.getElementById('searchInput').addEventListener('input', function(){
    clearTimeout(searchTimeout);
    searchQ = this.value.toLowerCase().trim();
    searchTimeout = setTimeout(applyFilters, 180);
});

document.getElementById('sortSelect').addEventListener('change', function(){
    sortVal = this.value;
    applyFilters();
});

var mobileSearch = document.getElementById('mobileSearchInput');
var mobileSort = document.getElementById('mobileSortSelect');
if (mobileSearch) mobileSearch.addEventListener('input', function(){
    document.getElementById('searchInput').value = this.value;
    document.getElementById('searchInput').dispatchEvent(new Event('input'));
});
if (mobileSort) mobileSort.addEventListener('change', function(){
    document.getElementById('sortSelect').value = this.value;
    document.getElementById('sortSelect').dispatchEvent(new Event('change'));
});

var grid    = document.getElementById('campaignGrid');
var list    = document.getElementById('campaignList');
var btnGrid = document.getElementById('btnGrid');
var btnList = document.getElementById('btnList');

btnGrid.addEventListener('click', function(){
    grid.style.display = ''; list.style.display = 'none';
    btnGrid.classList.add('on'); btnList.classList.remove('on');
    applyFilters();
});
btnList.addEventListener('click', function(){
    grid.style.display = 'none'; list.style.display = '';
    btnList.classList.add('on'); btnGrid.classList.remove('on');
    applyFilters();
});

window.handleSub = function(form, txt){
    form.querySelectorAll('button[type=submit]').forEach(function(b){ b.disabled = true; b.textContent = txt; });
    return true;
};

/* ── Chart ── */
var fundChart;
window.renderChart = function(){
    var isDark    = html.getAttribute('data-theme') === 'dark';
    var gridColor = isDark ? 'rgba(255,255,255,.05)' : 'rgba(0,0,0,.04)';
    var lblColor  = isDark ? 'rgba(255,255,255,.35)' : 'rgba(0,0,0,.35)';
    var tipBg     = isDark ? '#1e2033' : '#fff';
    var tipTx     = isDark ? '#eef0ff' : '#111';

    Chart.defaults.color       = lblColor;
    Chart.defaults.font.family = "'DM Mono', monospace";
    Chart.defaults.font.size   = 10.5;

    var ctx = document.getElementById('fundChart');
    if (!ctx) return;
    if (fundChart) fundChart.destroy();

    var monthlyData = @json($monthlyData ?? []);
    var labels = Object.keys(monthlyData);
    var values = Object.values(monthlyData);

    var cctx = ctx.getContext('2d');
    var grad = cctx.createLinearGradient(0, 0, 0, 180);
    grad.addColorStop(0, 'rgba(37,99,235,.20)');
    grad.addColorStop(1, 'rgba(37,99,235,0)');

    fundChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Amount Raised (₹)',
                data: values,
                borderColor: '#2563eb', backgroundColor: grad,
                borderWidth: 2.5, fill: true, tension: .45,
                pointBackgroundColor: '#2563eb',
                pointBorderColor: tipBg, pointBorderWidth: 2,
                pointRadius: 4, pointHoverRadius: 6,
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            interaction: { intersect: false, mode: 'index' },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: tipBg, titleColor: tipTx, bodyColor: tipTx,
                    borderColor: gridColor, borderWidth: 1, padding: 12, cornerRadius: 10,
                    callbacks: { label: function(c){ return ' ₹' + Number(c.parsed.y).toLocaleString('en-IN'); } }
                }
            },
            scales: {
                x: { grid:{ color:gridColor }, border:{ dash:[3,3] } },
                y: { grid:{ color:gridColor }, border:{ dash:[3,3] }, ticks:{ callback: function(v){ return '₹'+Number(v).toLocaleString('en-IN'); } } }
            }
        }
    });
}
renderChart();

/* ── Campaign comparison bar chart ── */
var campChart;
(function(){
    var ctx = document.getElementById('campChart');
    if (!ctx) return;
    if (campChart) campChart.destroy();

    var isDark    = html.getAttribute('data-theme') === 'dark';
    var gridColor = isDark ? 'rgba(255,255,255,.05)' : 'rgba(0,0,0,.04)';
    var lblColor  = isDark ? 'rgba(255,255,255,.35)' : 'rgba(0,0,0,.35)';
    var tipBg     = isDark ? '#1e2033' : '#fff';
    var tipTx     = isDark ? '#eef0ff' : '#111';

    var campaigns = @json($campChartData);

    campChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: campaigns.map(function(c){ return c.title; }),
            datasets: [
                {
                    label: 'Raised (₹)',
                    data: campaigns.map(function(c){ return c.raised; }),
                    backgroundColor: '#2563eb',
                    borderRadius: 4,
                    barPercentage: .65,
                },
                {
                    label: 'Goal (₹)',
                    data: campaigns.map(function(c){ return c.goal; }),
                    backgroundColor: isDark ? 'rgba(37,99,235,.2)' : 'rgba(37,99,235,.10)',
                    borderRadius: 4,
                    barPercentage: .65,
                }
            ]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            interaction: { intersect: false, mode: 'index' },
            plugins: {
                legend: {
                    position: 'top',
                    align: 'end',
                    labels: { boxWidth: 10, boxHeight: 10, borderRadius: 2, usePointStyle: true, padding: 16, color: lblColor, font: { family: "'DM Sans', sans-serif", size: 11 } }
                },
                tooltip: {
                    backgroundColor: tipBg, titleColor: tipTx, bodyColor: tipTx,
                    borderColor: gridColor, borderWidth: 1, padding: 12, cornerRadius: 10,
                    callbacks: { label: function(c){ return c.dataset.label + ': ₹' + Number(c.parsed.y).toLocaleString('en-IN'); } }
                }
            },
            scales: {
                x: { grid:{ display:false }, ticks:{ color: lblColor, font:{ size:10 } } },
                y: { grid:{ color:gridColor }, border:{ dash:[3,3] }, ticks:{ callback: function(v){ return '₹'+Number(v).toLocaleString('en-IN'); } } }
            }
        }
    });
})();

});
</script>
@endpush