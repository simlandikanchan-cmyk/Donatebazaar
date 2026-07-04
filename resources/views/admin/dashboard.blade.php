@extends('layouts.admin')

@section('sidebar_dashboard', 'active')
@section('page_title', 'Dashboard')
@section('page_subtitle', now()->format('l, d F Y'))

@section('topbar_left')
  <div class="search-wrap">
    <svg class="s-icon-inp" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
    <input class="search-inp" id="searchInput" type="text" placeholder="Search campaigns…" autocomplete="off">
  </div>
  <select class="sort-sel" id="sortSelect">
    <option value="">Sort by…</option>
    <option value="amount-desc">Amount ↓</option>
    <option value="amount-asc">Amount ↑</option>
    <option value="date-desc">Newest first</option>
    <option value="date-asc">Oldest first</option>
  </select>
  <button class="tb-btn" title="Notifications">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
    @if($cntPending > 0)<span class="notif-dot"></span>@endif
  </button>
@endsection

@section('content')
@php
  $hour = now()->hour;
  $greeting = $hour < 12 ? 'Good morning' : ($hour < 17 ? 'Good afternoon' : 'Good evening');
  $approvalRate = $totalCampaigns > 0 ? round(($cntActive / $totalCampaigns) * 100) : 0;
  $activeJobs = \App\Models\JobPost::where('status','active')->count();
  $totalApplicants = \App\Models\JobPostApplication::count();
@endphp

<div class="hero">
  <div class="hero-left">
    <div class="hero-tag"><span class="hero-tag-dot"></span>{{ $greeting }}, Administrator</div>
    <div class="hero-name">{{ auth()->user()->name ?? 'Admin' }} <span class="wave">👋</span></div>
    <div class="hero-sub">Here's your platform overview for today. Manage campaigns, job posts, and keep DonateBazaar running smoothly.</div>
    <div class="hero-badges">
      @if($cntPending > 0)
        <span class="hero-badge hb-amber">⏱ {{ $cntPending }} awaiting review</span>
      @else
        <span class="hero-badge hb-green">✓ All caught up</span>
      @endif
      <span class="hero-badge hb-green">{{ $cntActive }} active campaigns</span>
      <span class="hero-badge hb-purple">{{ $approvalRate }}% approval rate</span>
      <span class="hero-badge hb-teal">{{ $activeJobs }} open jobs</span>
    </div>
  </div>
  <div class="hero-right">
    @if($cntPending > 0)
    <button onclick="setFilter('pending')" class="hero-btn hero-btn-primary">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
      Review Pending
    </button>
    @endif
    <a href="{{ route('admin.job_posts.create') }}" class="hero-btn hero-btn-teal">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
      Post a Job
    </a>
    <a href="{{ route('profile.show') }}" class="hero-btn hero-btn-ghost">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
      My Profile
    </a>
  </div>
</div>

<div class="stats-grid">
  <div class="stat">
    <div class="stat-icon si-a"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg></div>
    <div class="stat-body"><div class="stat-lbl">Total Campaigns</div><div class="stat-val sv-a">{{ $totalCampaigns }}</div><div class="stat-foot">All time on platform</div></div>
  </div>
  <div class="stat">
    <div class="stat-icon si-amber"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div>
    <div class="stat-body"><div class="stat-lbl">Pending Review</div><div class="stat-val sv-amber">{{ $cntPending }}</div><div class="stat-foot">Awaiting your decision</div></div>
  </div>
  <div class="stat">
    <div class="stat-icon si-green"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
    <div class="stat-body"><div class="stat-lbl">Active Now</div><div class="stat-val sv-green">{{ $cntActive }}</div><div class="stat-foot">Currently running live</div></div>
  </div>
  <div class="stat">
    <div class="stat-icon si-blue"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg></div>
    <div class="stat-body"><div class="stat-lbl">Approval Rate</div><div class="stat-val sv-blue">{{ $approvalRate }}%</div><div class="stat-foot">Of all campaigns</div></div>
  </div>
  <div class="stat">
    <div class="stat-icon si-teal"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg></div>
    <div class="stat-body"><div class="stat-lbl">Active Jobs</div><div class="stat-val sv-teal">{{ $activeJobs }}</div><div class="stat-foot"><a href="{{ route('admin.job_posts.create') }}">+ Post new job →</a></div></div>
  </div>
</div>

<div class="analytics-row">
  <div class="chart-card">
    <div class="chart-hdr">
      <div>
        <div class="chart-ttl">Campaign Activity</div>
        <div class="chart-sub">Monthly overview — last 12 months</div>
      </div>
      <div class="chart-legend">
        <div class="leg-item"><div class="leg-dot" style="background:#6e56f7"></div>Total</div>
        <div class="leg-item"><div class="leg-dot" style="background:#05c48a"></div>Approved</div>
      </div>
    </div>
    <div class="chart-wrap"><canvas id="lineChart"></canvas></div>
  </div>
  <div class="status-panel">
    <div class="sp-ttl">Status Breakdown</div>
    <div class="sp-row" onclick="setFilter('active')"><div class="sp-left"><div class="sp-dot" style="background:var(--green)"></div><span class="sp-label">Active</span></div><span class="sp-val">{{ $cntActive }}</span></div>
    <div class="sp-row" onclick="setFilter('pending')"><div class="sp-left"><div class="sp-dot" style="background:var(--amber)"></div><span class="sp-label">Pending</span></div><span class="sp-val">{{ $cntPending }}</span></div>
    <div class="sp-row" onclick="setFilter('paused')"><div class="sp-left"><div class="sp-dot" style="background:var(--a)"></div><span class="sp-label">Paused</span></div><span class="sp-val">{{ $cntPaused }}</span></div>
    <div class="sp-row" onclick="setFilter('rejected')"><div class="sp-left"><div class="sp-dot" style="background:var(--red)"></div><span class="sp-label">Rejected</span></div><span class="sp-val">{{ $cntRejected }}</span></div>
    <div class="sp-row" onclick="setFilter('inactive')"><div class="sp-left"><div class="sp-dot" style="background:var(--gray)"></div><span class="sp-label">Inactive / Expired</span></div><span class="sp-val">{{ $cntExpired + $cntCompleted }}</span></div>
    <div class="sp-prog">
      <div class="sp-prog-lbl"><span>Approval rate</span><span>{{ $approvalRate }}%</span></div>
      <div class="sp-bar"><div class="sp-fill" id="approvalBar" style="width:0%"></div></div>
    </div>
  </div>
</div>

<div class="qnav">
  @php
    $navItems = [
      ['url'=>route('admin.categories.index'),                    'lbl'=>'Categories',    'sub'=>'Manage all',     'delay'=>'.05s','bg'=>'var(--a-lt)',           'color'=>'var(--a)',    'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a2 2 0 012-2z"/>'],
      ['url'=>route('admin.partnership.index'),                  'lbl'=>'Partnerships',  'sub'=>'View requests',  'delay'=>'.10s','bg'=>'var(--green-lt)',        'color'=>'var(--green)','icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/>'],
      ['url'=>route('admin.messages'),                      'lbl'=>'Messages',      'sub'=>'View all',       'delay'=>'.15s','bg'=>'rgba(249,115,22,.10)',   'color'=>'#f97316',    'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>'],
      ['url'=>route('admin.applications'),                  'lbl'=>'Applications',  'sub'=>'NGO requests',   'delay'=>'.25s','bg'=>'var(--blue-lt)',         'color'=>'var(--blue)', 'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>'],
      ['url'=>route('admin.job_posts.index'),              'lbl'=>'Job Posts',     'sub'=>'All listings',   'delay'=>'.30s','bg'=>'rgba(5,196,138,.10)',    'color'=>'#05c48a',    'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>'],
      ['url'=>route('admin.job_posts.create'),             'lbl'=>'Post a Job',    'sub'=>'Create listing', 'delay'=>'.35s','bg'=>'rgba(245,158,11,.10)',   'color'=>'#f59e0b',    'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>'],
      ['url'=>route('admin.job_post_applications.index'),  'lbl'=>'Applicants',    'sub'=>'Job applicants', 'delay'=>'.40s','bg'=>'rgba(236,72,153,.10)',   'color'=>'#ec4899',    'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/>'],
      ['url'=>route('profile.show'),                       'lbl'=>'My Profile',    'sub'=>'View & edit',    'delay'=>'.45s','bg'=>'var(--pink-lt)',         'color'=>'var(--pink)', 'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>'],
    ];
  @endphp
  @foreach($navItems as $item)
  <a href="{{ $item['url'] }}" class="qnav-card" style="animation-delay:{{ $item['delay'] }};--qc:{{ $item['bg'] }};">
    <div class="qnav-ico" style="background:{{ $item['bg'] }};color:{{ $item['color'] }};"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">{!! $item['icon'] !!}</svg></div>
    <div><div class="qnav-lbl">{{ $item['lbl'] }}</div><div class="qnav-sub">{{ $item['sub'] }}</div></div>
  </a>
  @endforeach
</div>

@php $cntAll = $cntPending + $cntActive + $cntPaused + $cntRejected + $cntExpired + $cntCompleted; @endphp
<div class="sec-hdr" id="cGrid">
  <div class="sec-ttl">All Campaigns</div>
  <div class="sec-right">
    <div class="ftabs" id="ftabs">
      <button class="ftab on" data-filter="all">All <span class="cnt">{{ $cntAll }}</span></button>
      <button class="ftab" data-filter="pending">Pending <span class="cnt">{{ $cntPending }}</span></button>
      <button class="ftab" data-filter="active">Active <span class="cnt">{{ $cntActive }}</span></button>
      <button class="ftab" data-filter="paused">Paused <span class="cnt">{{ $cntPaused }}</span></button>
      <button class="ftab" data-filter="inactive">Inactive <span class="cnt">{{ $cntExpired + $cntCompleted }}</span></button>
      <button class="ftab" data-filter="rejected">Rejected <span class="cnt">{{ $cntRejected }}</span></button>
    </div>
  </div>
</div>

<div id="noResults">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
  <strong>No campaigns found</strong>
  <span>No campaigns match your current filter or search.</span>
</div>

<div class="c-grid" id="campaignGrid">

  @foreach($pendingCampaigns as $i => $c)
  @php $raised=$c->raised_amount??0;$goal=$c->goal_amount>0?$c->goal_amount:1;$pct=min(100,round(($raised/$goal)*100));$uName=$c->user?->name??'Unknown';$uEmail=$c->user?->email??'';$uInit=strtoupper(substr($uName,0,1)); @endphp
  <div class="c-card" data-filter="pending" data-title="{{ strtolower($c->title) }}" data-amount="{{ $c->goal_amount }}" data-date="{{ $c->created_at }}" style="animation-delay:{{ $i*0.04 }}s">
    <div class="c-thumb">
      @if($c->cover_image)<img src="{{ asset('storage/'.$c->cover_image) }}" alt="{{ $c->title }}" loading="lazy"><div class="c-overlay"></div>
      @else<div class="c-placeholder"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg></div>@endif
      <div class="c-badge-pos"><span class="badge b-pending">Pending</span></div>
    </div>
    <div class="c-user"><div class="c-uav">{{ $uInit }}</div><div><div class="c-uname">{{ $uName }}</div>@if($uEmail)<div class="c-uemail">{{ $uEmail }}</div>@endif</div></div>
    <div class="c-body">
      <div class="c-title">{{ $c->title }}</div>
      <div class="prog"><div class="prog-nums"><span class="prog-raised">₹{{ number_format($raised) }}</span><span class="prog-goal">of ₹{{ number_format($c->goal_amount) }}</span></div><div class="prog-bar"><div class="prog-fill" style="width:{{ $pct }}%"></div></div><div class="prog-pct">{{ $pct }}% funded</div></div>
      <div class="c-actions">
        <form action="{{ route('admin.campaign.approve',$c->id) }}" method="POST" style="flex:1;" onsubmit="return handleSub(this,'Approving…')">@csrf<button class="c-btn c-btn-approve" style="width:100%;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>Approve</button></form>
        <button type="button" onclick="openReject({{ $c->id }})" class="c-btn c-btn-reject"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>Reject</button>
        <a href="{{ route('admin.campaign.show',$c->id) }}" class="c-btn c-btn-view"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg></a>
      </div>
    </div>
  </div>
  @endforeach

  @foreach($activeCampaigns as $i => $c)
  @php $raised=$c->raised_amount??0;$goal=$c->goal_amount>0?$c->goal_amount:1;$pct=min(100,round(($raised/$goal)*100));$isPaused=($c->campaign_state==='paused');$fv=$isPaused?'paused':'active';$uName=$c->user?->name??'Unknown';$uEmail=$c->user?->email??'';$uInit=strtoupper(substr($uName,0,1)); @endphp
  <div class="c-card" data-filter="{{ $fv }}" data-title="{{ strtolower($c->title) }}" data-amount="{{ $c->goal_amount }}" data-date="{{ $c->created_at }}" style="animation-delay:{{ $i*0.04 }}s">
    <div class="c-thumb">
      @if($c->cover_image)<img src="{{ asset('storage/'.$c->cover_image) }}" alt="{{ $c->title }}" loading="lazy"><div class="c-overlay"></div>
      @else<div class="c-placeholder"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg></div>@endif
      <div class="c-badge-pos"><span class="badge {{ $isPaused?'b-paused':'b-active' }}">{{ $isPaused?'Paused':'Active' }}</span></div>
    </div>
    <div class="c-user"><div class="c-uav">{{ $uInit }}</div><div><div class="c-uname">{{ $uName }}</div>@if($uEmail)<div class="c-uemail">{{ $uEmail }}</div>@endif</div></div>
    <div class="c-body">
      <div class="c-title">{{ $c->title }}</div>
      @if($isPaused && $c->pause_reason)<div class="reason reason-amber"><div class="reason-lbl">⏸ PAUSE REASON</div><div class="reason-txt">{{ $c->pause_reason }}</div></div>@endif
      <div class="prog"><div class="prog-nums"><span class="prog-raised">₹{{ number_format($raised) }}</span><span class="prog-goal">of ₹{{ number_format($c->goal_amount) }}</span></div><div class="prog-bar"><div class="prog-fill" style="width:{{ $pct }}%"></div></div><div class="prog-pct">{{ $pct }}% funded</div></div>
      <div class="c-actions">
        @if(!$isPaused)
        <button type="button" onclick="openPause({{ $c->id }})" class="c-btn c-btn-pause" style="flex:1;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>Pause</button>
        @else
        <form action="{{ route('admin.campaign.resume',$c->id) }}" method="POST" style="flex:1;" onsubmit="return handleSub(this,'Resuming…')">@csrf<button class="c-btn c-btn-resume" style="width:100%;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>Resume</button></form>
        @endif
        <a href="{{ route('admin.campaign.show',$c->id) }}" class="c-btn c-btn-view" style="flex:1;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>View</a>
      </div>
    </div>
  </div>
  @endforeach

  @foreach($rejectedCampaigns as $i => $c)
  @php $raised=$c->raised_amount??0;$goal=$c->goal_amount>0?$c->goal_amount:1;$pct=min(100,round(($raised/$goal)*100));$uName=$c->user?->name??'Unknown';$uEmail=$c->user?->email??'';$uInit=strtoupper(substr($uName,0,1)); @endphp
  <div class="c-card" data-filter="rejected" data-title="{{ strtolower($c->title) }}" data-amount="{{ $c->goal_amount }}" data-date="{{ $c->created_at }}" style="animation-delay:{{ $i*0.04 }}s">
    <div class="c-thumb">
      @if($c->cover_image)<img src="{{ asset('storage/'.$c->cover_image) }}" alt="{{ $c->title }}" loading="lazy"><div class="c-overlay"></div>
      @else<div class="c-placeholder"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg></div>@endif
      <div class="c-badge-pos"><span class="badge b-rejected">Rejected</span></div>
    </div>
    <div class="c-user"><div class="c-uav" style="background:linear-gradient(135deg,#f04444,#dc2626);">{{ $uInit }}</div><div><div class="c-uname">{{ $uName }}</div>@if($uEmail)<div class="c-uemail">{{ $uEmail }}</div>@endif</div></div>
    <div class="c-body">
      <div class="c-title">{{ $c->title }}</div>
      @if($c->rejection_reason)<div class="reason reason-red"><div class="reason-lbl">✕ REJECTION REASON</div><div class="reason-txt">{{ $c->rejection_reason }}</div></div>@endif
      <div class="prog"><div class="prog-nums"><span class="prog-raised">₹{{ number_format($raised) }}</span><span class="prog-goal">of ₹{{ number_format($c->goal_amount) }}</span></div><div class="prog-bar"><div class="prog-fill prog-fill-red" style="width:{{ $pct }}%"></div></div><div class="prog-pct" style="color:var(--red)">{{ $pct }}% funded</div></div>
      <div class="c-actions">
        <a href="{{ route('admin.campaign.show',$c->id) }}" class="c-btn c-btn-view" style="flex:1;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>View Details</a>
      </div>
    </div>
  </div>
  @endforeach

  @foreach($inactiveCampaigns as $i => $c)
  @php $raised=$c->raised_amount??0;$goal=$c->goal_amount>0?$c->goal_amount:1;$pct=min(100,round(($raised/$goal)*100));$uName=$c->user?->name??'Unknown';$uEmail=$c->user?->email??'';$uInit=strtoupper(substr($uName,0,1)); @endphp
  <div class="c-card" data-filter="inactive" data-title="{{ strtolower($c->title) }}" data-amount="{{ $c->goal_amount }}" data-date="{{ $c->created_at }}" style="animation-delay:{{ $i*0.04 }}s">
    <div class="c-thumb">
      @if($c->cover_image)<img src="{{ asset('storage/'.$c->cover_image) }}" alt="{{ $c->title }}" loading="lazy"><div class="c-overlay"></div>
      @else<div class="c-placeholder"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg></div>@endif
      <div class="c-badge-pos"><span class="badge b-inactive">{{ $c->status==='completed'?'Completed':'Inactive' }}</span></div>
    </div>
    <div class="c-user"><div class="c-uav" style="background:linear-gradient(135deg,#64748b,#475569);">{{ $uInit }}</div><div><div class="c-uname">{{ $uName }}</div>@if($uEmail)<div class="c-uemail">{{ $uEmail }}</div>@endif</div></div>
    <div class="c-body">
      <div class="c-title">{{ $c->title }}</div>
      <div class="prog"><div class="prog-nums"><span class="prog-raised">₹{{ number_format($raised) }}</span><span class="prog-goal">of ₹{{ number_format($c->goal_amount) }}</span></div><div class="prog-bar"><div class="prog-fill prog-fill-gray" style="width:{{ $pct }}%"></div></div><div class="prog-pct" style="color:#64748b">{{ $pct }}% funded</div></div>
      <div class="c-actions">
        <a href="{{ route('admin.campaign.show',$c->id) }}" class="c-btn c-btn-view" style="flex:1;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>View Details</a>
      </div>
    </div>
  </div>
  @endforeach

</div>

<div class="pagination-wrap">{{ $activeCampaigns->links('vendor.pagination.admin') }}</div>

{{-- PAUSE MODAL --}}
<div id="pauseOverlay" class="overlay" role="dialog" aria-modal="true">
  <div class="modal">
    <button type="button" class="modal-x" onclick="closePause()"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
    <div class="modal-head">
      <div class="modal-ico" style="background:var(--amber-lt);"><svg viewBox="0 0 24 24" fill="none" stroke="var(--amber)" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
      <div><div class="modal-ttl">Pause Campaign</div><div class="modal-sub">Reason will be shown to the campaign owner</div></div>
    </div>
    <form id="pauseForm" method="POST">@csrf
      <div class="modal-lbl">Select or write a reason <span>*</span></div>
      <div class="chips">
        <button type="button" class="chip chip-amber" data-r="Suspicious activity detected">Suspicious activity</button>
        <button type="button" class="chip chip-amber" data-r="Incomplete or missing documents">Missing documents</button>
        <button type="button" class="chip chip-amber" data-r="Under review by admin team">Under review</button>
        <button type="button" class="chip chip-amber" data-r="Violation of platform guidelines">Policy violation</button>
        <button type="button" class="chip chip-amber" data-r="Awaiting additional verification">Pending verification</button>
      </div>
      <textarea id="pauseReason" name="reason" rows="3" placeholder="Or type a custom reason…" class="modal-ta"></textarea>
      <p id="pauseErr" class="modal-err">⚠ Please provide a reason before pausing.</p>
      <div class="modal-acts"><button type="button" onclick="closePause()" class="modal-btn modal-cancel">Cancel</button><button type="submit" id="pauseBtn" class="modal-btn modal-amber">⏸ Pause Campaign</button></div>
    </form>
  </div>
</div>

{{-- REJECT MODAL --}}
<div id="rejectOverlay" class="overlay" role="dialog" aria-modal="true">
  <div class="modal">
    <button type="button" class="modal-x" onclick="closeReject()"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
    <div class="modal-head">
      <div class="modal-ico" style="background:var(--red-lt);"><svg viewBox="0 0 24 24" fill="none" stroke="var(--red)" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
      <div><div class="modal-ttl">Reject Campaign</div><div class="modal-sub">Reason will be shown to the campaign owner</div></div>
    </div>
    <form id="rejectForm" method="POST">@csrf
      <div class="modal-lbl">Select or write a reason <span>*</span></div>
      <div class="chips">
        <button type="button" class="chip chip-red" data-r="Fraudulent or misleading content">Fraudulent content</button>
        <button type="button" class="chip chip-red" data-r="Incomplete campaign information">Incomplete info</button>
        <button type="button" class="chip chip-red" data-r="Violation of platform terms">Terms violation</button>
        <button type="button" class="chip chip-red" data-r="Duplicate campaign detected">Duplicate campaign</button>
        <button type="button" class="chip chip-red" data-r="Insufficient documentation provided">Insufficient docs</button>
      </div>
      <textarea id="rejectReason" name="reason" rows="3" placeholder="Or type a custom reason…" class="modal-ta"></textarea>
      <p id="rejectErr" class="modal-err">⚠ Please provide a reason before rejecting.</p>
      <div class="modal-acts"><button type="button" onclick="closeReject()" class="modal-btn modal-cancel">Cancel</button><button type="submit" id="rejectBtn" class="modal-btn modal-red">✕ Reject Campaign</button></div>
    </form>
  </div>
</div>
@endsection

@push('page_scripts')
<script>
(function(){
'use strict';

var html=document.documentElement;
var lineChart;

/* ── Approval bar animation ── */
setTimeout(function(){
  var b=document.getElementById('approvalBar');
  if(b)b.style.width='{{ $approvalRate??0 }}%';
},700);

/* ── Chart ── */
var chartLabels=@json($chartLabels);
var chartTotal=@json($chartTotal);
var chartApproved=@json($chartActive);

function loadChart(){
  var canvas=document.getElementById('lineChart');
  if(!canvas)return;
  if(typeof Chart==='undefined')return;
  var isDark=html.getAttribute('data-theme')==='dark';
  var gridCol=isDark?'rgba(255,255,255,.05)':'rgba(0,0,0,.04)';
  var lblCol=isDark?'rgba(255,255,255,.32)':'rgba(0,0,0,.32)';
  var tipBg=isDark?'#1d1f35':'#fff';
  var tipTx=isDark?'#eef0ff':'#0a0b14';
  Chart.defaults.font.family="'DM Mono',monospace";
  Chart.defaults.font.size=10.5;
  if(lineChart){lineChart.destroy();lineChart=null;}
  var ctx=canvas.getContext('2d');
  var g1=ctx.createLinearGradient(0,0,0,190);
  g1.addColorStop(0,'rgba(110,86,247,.22)');g1.addColorStop(1,'rgba(110,86,247,0)');
  var g2=ctx.createLinearGradient(0,0,0,190);
  g2.addColorStop(0,'rgba(5,196,138,.18)');g2.addColorStop(1,'rgba(5,196,138,0)');
  lineChart=new Chart(ctx,{
    type:'line',
    data:{
      labels:chartLabels,
      datasets:[
        {label:'Total Campaigns',data:chartTotal,borderColor:'#6e56f7',backgroundColor:g1,borderWidth:2.5,pointRadius:4,tension:.45,fill:true,pointBackgroundColor:'#6e56f7',pointBorderColor:tipBg,pointBorderWidth:2,pointHoverRadius:6},
        {label:'Approved',data:chartApproved,borderColor:'#05c48a',backgroundColor:g2,borderWidth:2.5,pointRadius:4,tension:.45,fill:true,pointBackgroundColor:'#05c48a',pointBorderColor:tipBg,pointBorderWidth:2,pointHoverRadius:6}
      ]
    },
    options:{
      responsive:true,maintainAspectRatio:false,
      interaction:{intersect:false,mode:'index'},
      plugins:{
        legend:{display:false},
        tooltip:{backgroundColor:tipBg,titleColor:tipTx,bodyColor:tipTx,borderColor:gridCol,borderWidth:1,padding:13,cornerRadius:11,titleFont:{size:11,weight:'700'},bodyFont:{size:11}}
      },
      scales:{
        x:{grid:{color:gridCol},border:{dash:[3,3],display:false},ticks:{color:lblCol}},
        y:{grid:{color:gridCol},border:{dash:[3,3],display:false},beginAtZero:true,ticks:{stepSize:1,precision:0,color:lblCol}}
      },
      animation:{duration:900,easing:'easeOutQuart'}
    }
  });
}
setTimeout(loadChart,100);

/* ── Filter / sort / search ── */
var cards=Array.from(document.querySelectorAll('#campaignGrid .c-card'));
var activeFilter='all',searchQ='',sortVal='';

function applyFilters(){
  var sorted=cards.slice();
  if(sortVal==='amount-desc')sorted.sort((a,b)=>+b.dataset.amount - +a.dataset.amount);
  else if(sortVal==='amount-asc')sorted.sort((a,b)=>+a.dataset.amount - +b.dataset.amount);
  else if(sortVal==='date-desc')sorted.sort((a,b)=>new Date(b.dataset.date)-new Date(a.dataset.date));
  else if(sortVal==='date-asc')sorted.sort((a,b)=>new Date(a.dataset.date)-new Date(b.dataset.date));
  var grid=document.getElementById('campaignGrid');
  sorted.forEach(function(c){grid.appendChild(c);});
  var visible=0;
  cards.forEach(function(c){
    var mf=activeFilter==='all'||c.dataset.filter===activeFilter;
    var ms=!searchQ||(c.dataset.title||'').includes(searchQ);
    c.style.display=(mf&&ms)?'':'none';
    if(mf&&ms)visible++;
  });
  document.getElementById('noResults').style.display=visible>0?'none':'block';
}

document.querySelectorAll('.ftab').forEach(function(tab){
  tab.addEventListener('click',function(){
    document.querySelectorAll('.ftab').forEach(function(t){t.classList.remove('on');});
    this.classList.add('on');activeFilter=this.dataset.filter;applyFilters();
  });
});

window.setFilter=function(f){
  activeFilter=f;
  document.querySelectorAll('.ftab').forEach(function(t){t.classList.toggle('on',t.dataset.filter===f);});
  applyFilters();
  var el=document.getElementById('cGrid');
  if(el)el.scrollIntoView({behavior:'smooth',block:'start'});
};

var st;
document.getElementById('searchInput').addEventListener('input',function(){
  clearTimeout(st);searchQ=this.value.toLowerCase().trim();st=setTimeout(applyFilters,180);
});
document.getElementById('sortSelect').addEventListener('change',function(){sortVal=this.value;applyFilters();});

/* ── Pause modal ── */
function openPause(id){
  document.getElementById('pauseForm').action='/admin/campaign/'+id+'/pause';
  document.getElementById('pauseReason').value='';
  document.getElementById('pauseErr').style.display='none';
  var btn=document.getElementById('pauseBtn');btn.disabled=false;btn.innerHTML='⏸ Pause Campaign';
  document.querySelectorAll('.chip-amber').forEach(function(c){c.classList.remove('on');});
  document.getElementById('pauseOverlay').classList.add('open');
  setTimeout(function(){document.getElementById('pauseReason').focus();},80);
}
function closePause(){document.getElementById('pauseOverlay').classList.remove('open');}
window.openPause=openPause;window.closePause=closePause;

document.querySelectorAll('.chip-amber').forEach(function(btn){
  btn.addEventListener('click',function(){
    document.querySelectorAll('.chip-amber').forEach(function(b){b.classList.remove('on');});
    this.classList.add('on');document.getElementById('pauseReason').value=this.dataset.r;
    document.getElementById('pauseErr').style.display='none';
  });
});
document.getElementById('pauseForm').addEventListener('submit',function(e){
  if(!document.getElementById('pauseReason').value.trim()){e.preventDefault();document.getElementById('pauseErr').style.display='block';return;}
  var btn=document.getElementById('pauseBtn');btn.disabled=true;btn.innerHTML='Pausing…';
});

/* ── Reject modal ── */
function openReject(id){
  document.getElementById('rejectForm').action='/admin/campaign/'+id+'/reject';
  document.getElementById('rejectReason').value='';
  document.getElementById('rejectErr').style.display='none';
  var btn=document.getElementById('rejectBtn');btn.disabled=false;btn.innerHTML='✕ Reject Campaign';
  document.querySelectorAll('.chip-red').forEach(function(c){c.classList.remove('on');});
  document.getElementById('rejectOverlay').classList.add('open');
  setTimeout(function(){document.getElementById('rejectReason').focus();},80);
}
function closeReject(){document.getElementById('rejectOverlay').classList.remove('open');}
window.openReject=openReject;window.closeReject=closeReject;

document.querySelectorAll('.chip-red').forEach(function(btn){
  btn.addEventListener('click',function(){
    document.querySelectorAll('.chip-red').forEach(function(b){b.classList.remove('on');});
    this.classList.add('on');document.getElementById('rejectReason').value=this.dataset.r;
    document.getElementById('rejectErr').style.display='none';
  });
});
document.getElementById('rejectForm').addEventListener('submit',function(e){
  if(!document.getElementById('rejectReason').value.trim()){e.preventDefault();document.getElementById('rejectErr').style.display='block';return;}
  var btn=document.getElementById('rejectBtn');btn.disabled=true;btn.innerHTML='Rejecting…';
});

/* ── Global helpers ── */
window.handleSub=function(form,txt){
  form.querySelectorAll('button[type=submit]').forEach(function(b){b.disabled=true;b.textContent=txt;});
  return true;
};

})();
</script>
@endpush
