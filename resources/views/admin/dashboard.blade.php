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
      <div class="stat">
        <div class="stat-icon si-purple"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg></div>
        <div class="stat-body"><div class="stat-lbl">Volunteers</div><div class="stat-val sv-purple">{{ $volunteerCount }}</div><div class="stat-foot"><a href="{{ route('admin.volunteers.index') }}">{{ $pendingVolunteerApps }} pending →</a></div></div>
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
        <div class="leg-item"><div class="leg-dot" style="background:#2563eb "></div>Total</div>
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

{{-- ══ DOUGHNUT CHART ══ --}}
<div class="chart-card doughnut-card">
  <div class="chart-hdr">
    <div>
      <div class="chart-ttl">Campaign Status Distribution</div>
      <div class="chart-sub">Current state of all campaigns on the platform</div>
    </div>
  </div>
  <div class="doughnut-wrap"><canvas id="doughnutChart"></canvas></div>
</div>

<div class="qnav">
  @php
    $navItems = [
      ['url'=>route('admin.categories.index'),                    'lbl'=>'Categories',    'sub'=>'Manage all',     'delay'=>'.05s','bg'=>'var(--a-lt)',           'color'=>'var(--a)',    'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a2 2 0 012-2z"/>'],
      ['url'=>route('admin.partnership.index'),                  'lbl'=>'Partnerships',  'sub'=>'View requests',  'delay'=>'.10s','bg'=>'var(--green-lt)',        'color'=>'var(--green)','icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/>'],
      ['url'=>route('admin.messages'),                      'lbl'=>'Messages',      'sub'=>'View all',       'delay'=>'.15s','bg'=>'rgba(249,115,22,.10)',   'color'=>'#f97316',    'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>'],
      ['url'=>route('admin.applications'),                  'lbl'=>'Applications',  'sub'=>'NGO requests',   'delay'=>'.25s','bg'=>'var(--blue-lt)',         'color'=>'var(--blue)', 'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>'],
      ['url'=>route('admin.organizations.index'),          'lbl'=>'NGOs',          'sub'=>'All organizations','delay'=>'.27s','bg'=>'rgba(99,102,241,.10)',     'color'=>'#6366f1',    'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/>'],
      ['url'=>route('admin.job_posts.index'),              'lbl'=>'Job Posts',     'sub'=>'All listings',   'delay'=>'.30s','bg'=>'rgba(5,196,138,.10)',    'color'=>'#05c48a',    'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>'],
      ['url'=>route('admin.job_posts.create'),             'lbl'=>'Post a Job',    'sub'=>'Create listing', 'delay'=>'.35s','bg'=>'rgba(245,158,11,.10)',   'color'=>'#f59e0b',    'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>'],
      ['url'=>route('admin.job_post_applications.index'),  'lbl'=>'Applicants',    'sub'=>'Job applicants', 'delay'=>'.40s','bg'=>'rgba(236,72,153,.10)',   'color'=>'#ec4899',    'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/>'],
      ['url'=>route('admin.volunteers.index'),             'lbl'=>'Volunteers',    'sub'=>'All volunteers', 'delay'=>'.42s','bg'=>'var(--a-lt)',           'color'=>'var(--a)',    'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/>'],
      ['url'=>route('admin.volunteer_applications.index'),'lbl'=>'Vol. Applications','sub'=>'Pending review', 'delay'=>'.44s','bg'=>'var(--amber-lt)',       'color'=>'var(--amber)','icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>'],
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

<div class="sec-hdr" id="cGrid">
  <div class="sec-ttl">All Campaigns</div>
  <div class="sec-right">
    <div class="ftabs" id="ftabs">
      <button class="ftab" data-filter="all">All <span class="cnt">{{ $totalCampaigns }}</span></button>
      <button class="ftab" data-filter="pending">Pending <span class="cnt">{{ $cntPending }}</span></button>
      <button class="ftab on" data-filter="active">Active <span class="cnt">{{ $cntActive }}</span></button>
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
  @include('admin._campaign_cards', ['campaigns' => $activeCampaigns])
</div>

<div class="pagination-wrap" id="paginationWrap">{{ $activeCampaigns->links('vendor.pagination.admin') }}</div>

{{-- BULK ACTION BAR --}}
<div class="bulk-bar" id="bulkBar" role="region" aria-label="Bulk actions">
  <div class="bb-info"><span id="bbCount">0</span> campaign(s) selected</div>
  <div class="bb-acts">
    <button type="button" class="bb-btn bb-approve" id="bbApprove">✓ Approve</button>
    <button type="button" class="bb-btn bb-pause" id="bbPause">⏸ Pause</button>
    <button type="button" class="bb-btn bb-reject" id="bbReject">✕ Reject</button>
    <button type="button" class="bb-clear" id="bbClear">Clear</button>
  </div>
</div>

{{-- QUICK VIEW SLIDE-OVER --}}
<div class="slide-over-backdrop" id="quickBackdrop" onclick="closeQuick()"></div>
<aside class="slide-over" id="quickPanel" role="dialog" aria-modal="true" aria-labelledby="quickTitle">
  <button type="button" class="modal-x" onclick="closeQuick()" aria-label="Close"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
  <div id="quickContent" aria-live="polite"></div>
  <div class="qk-loading" id="quickLoading"><span class="spin"></span> Loading…</div>
</aside>

{{-- BULK REASON MODAL --}}
<div id="bulkOverlay" class="overlay" role="dialog" aria-modal="true">
  <div class="modal">
    <button type="button" class="modal-x" onclick="closeBulk()"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
    <div class="modal-head">
      <div class="modal-ico" id="bulkIco" style="background:var(--red-lt);"><svg viewBox="0 0 24 24" fill="none" stroke="var(--red)" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
      <div><div class="modal-ttl" id="bulkTtl">Bulk Action</div><div class="modal-sub" id="bulkSub"></div></div>
    </div>
    <form id="bulkForm" method="POST">@csrf
      <div class="modal-lbl">Reason <span id="bulkReq">*</span></div>
      <textarea id="bulkReason" name="reason" rows="3" class="modal-ta" placeholder="Provide a reason for the campaign owner…"></textarea>
      <p id="bulkErr" class="modal-err">⚠ Please provide a reason.</p>
      <div class="modal-acts"><button type="button" onclick="closeBulk()" class="modal-btn modal-cancel">Cancel</button><button type="submit" id="bulkBtn" class="modal-btn modal-red">Confirm</button></div>
    </form>
  </div>
</div>

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
<script type="module">
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
  g1.addColorStop(0,'rgba(37,99,235,.22)');g1.addColorStop(1,'rgba(37,99,235,0)');
  var g2=ctx.createLinearGradient(0,0,0,190);
  g2.addColorStop(0,'rgba(5,196,138,.18)');g2.addColorStop(1,'rgba(5,196,138,0)');
  lineChart=new Chart(ctx,{
    type:'line',
    data:{
      labels:chartLabels,
      datasets:[
        {label:'Total Campaigns',data:chartTotal,borderColor:'#2563eb ',backgroundColor:g1,borderWidth:2.5,pointRadius:4,tension:.45,fill:true,pointBackgroundColor:'#2563eb ',pointBorderColor:tipBg,pointBorderWidth:2,pointHoverRadius:6},
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

/* ── Animated stat counters ── */
function animateCounter(el, target) {
  var duration = 900, start = 0, startTime = null;
  function step(timestamp) {
    if (!startTime) startTime = timestamp;
    var progress = Math.min((timestamp - startTime) / duration, 1);
    var current = Math.floor((1 - Math.pow(1 - progress, 3)) * target);
    el.textContent = current.toLocaleString('en-IN');
    if (progress < 1) requestAnimationFrame(step);
  }
  requestAnimationFrame(step);
}
document.querySelectorAll('.stat .stat-val').forEach(function (el) {
  var text = el.textContent.replace(/[%₹,]/g, '').trim();
  var num = parseInt(text, 10);
  if (!isNaN(num) && num > 0) {
    var suffix = el.textContent.includes('%') ? '%' : '';
    el.textContent = '0' + suffix;
    animateCounter(el, num);
  }
});

/* ── Doughnut chart ── */
var doughnutChart;
function loadDoughnut(){
  var canvas = document.getElementById('doughnutChart');
  if (!canvas || typeof Chart === 'undefined') return;
  if (doughnutChart) { doughnutChart.destroy(); doughnutChart = null; }
  var isDark = html.getAttribute('data-theme') === 'dark';
  var tipBg = isDark ? '#1d1f35' : '#fff';
  var tipTx = isDark ? '#eef0ff' : '#0a0b14';
  var gridCol = isDark ? 'rgba(255,255,255,.05)' : 'rgba(0,0,0,.04)';

  doughnutChart = new Chart(canvas, {
    type: 'doughnut',
    data: {
      labels: ['Active', 'Pending', 'Paused', 'Rejected', 'Expired'],
      datasets: [{
        data: [{{ $cntActive }}, {{ $cntPending }}, {{ $cntPaused }}, {{ $cntRejected }}, {{ $cntExpired + $cntCompleted }}],
        backgroundColor: ['#05c48a', '#f59e0b', '#2563eb ', '#f04444', '#94a3b8'],
        borderColor: isDark ? '#1c1d36' : '#fff',
        borderWidth: 3,
        hoverOffset: 10
      }]
    },
    options: {
      responsive: true, maintainAspectRatio: false,
      cutout: '68%',
      plugins: {
        legend: {
          position: 'bottom',
          labels: {
            padding: 16,
            usePointStyle: true,
            pointStyle: 'circle',
            color: isDark ? '#9ba3c8' : '#454863',
            font: { family: "'DM Sans', sans-serif", size: 11, weight: '500' },
            boxWidth: 8, boxHeight: 8
          }
        },
        tooltip: {
          backgroundColor: tipBg, titleColor: tipTx, bodyColor: tipTx,
          borderColor: gridCol, borderWidth: 1, padding: 13, cornerRadius: 11,
          callbacks: {
            label: function(c) {
              var total = c.dataset.data.reduce(function(a,b){ return a + b; }, 0);
              var pct = total > 0 ? Math.round((c.parsed / total) * 100) : 0;
              return ' ' + c.label + ': ' + c.parsed + ' (' + pct + '%)';
            }
          }
        }
      },
      animation: { animateRotate: true, duration: 1200, easing: 'easeOutQuart' }
    }
  });
}
setTimeout(loadDoughnut, 200);

/* ── AJAX Campaign grid (filter / search / sort / pagination) ── */
var grid=document.getElementById('campaignGrid');
var paginationWrap=document.getElementById('paginationWrap');
var noResults=document.getElementById('noResults');
var state='active',searchQ='',sortVal='',isFetching=false,currentPage=1;

function csrfToken(){var m=document.querySelector('meta[name=csrf-token]');return m?m.getAttribute('content'):'';}

function setTab(f,v){var el=document.querySelector('.ftab[data-filter="'+f+'"] .cnt');if(el)el.textContent=v;}

function fetchGrid(page){
  page=page||1;currentPage=page;
  if(isFetching)return;
  isFetching=true;grid.classList.add('loading');
  var params=new URLSearchParams({state:state,search:searchQ,sort:sortVal,cpage:page});
  fetch('{{ route('admin.dashboard.campaigns') }}?'+params.toString(),{headers:{'X-Requested-With':'XMLHttpRequest'}})
    .then(function(r){return r.json();})
    .then(function(data){
      grid.innerHTML=data.cards;
      paginationWrap.innerHTML=data.pagination;
      noResults.style.display=data.total>0?'none':'block';
      if(data.counts){
        setTab('all',data.counts.totalCampaigns);
        setTab('pending',data.counts.cntPending);
        setTab('active',data.counts.cntActive);
        setTab('paused',data.counts.cntPaused);
        setTab('inactive',data.counts.cntExpired+data.counts.cntCompleted);
        setTab('rejected',data.counts.cntRejected);
      }
      bindCardInteractions();
    })
    .catch(function(){window.toast('Failed to load campaigns.','error');})
    .finally(function(){isFetching=false;grid.classList.remove('loading');});
}

document.querySelectorAll('.ftab').forEach(function(tab){
  tab.addEventListener('click',function(){
    document.querySelectorAll('.ftab').forEach(function(t){t.classList.remove('on');});
    this.classList.add('on');state=this.dataset.filter;fetchGrid(1);
  });
});

window.setFilter=function(f){
  state=f;
  document.querySelectorAll('.ftab').forEach(function(t){t.classList.toggle('on',t.dataset.filter===f);});
  fetchGrid(1);
  var el=document.getElementById('cGrid');
  if(el)el.scrollIntoView({behavior:'smooth',block:'start'});
};

var st;
var searchInput=document.getElementById('searchInput');
if(searchInput)searchInput.addEventListener('input',function(){
  clearTimeout(st);searchQ=this.value.toLowerCase().trim();st=setTimeout(function(){fetchGrid(1);},180);
});
var sortSelect=document.getElementById('sortSelect');
if(sortSelect)sortSelect.addEventListener('change',function(){sortVal=this.value;fetchGrid(1);});

if(paginationWrap)paginationWrap.addEventListener('click',function(e){
  var a=e.target.closest('a');if(!a)return;
  e.preventDefault();
  var url=new URL(a.href,location.href);
  fetchGrid(parseInt(url.searchParams.get('cpage'))||1);
});

/* ── Card interactions: quick view + selection ── */
function bindCardInteractions(){
  grid.querySelectorAll('.c-card').forEach(function(card){
    card.addEventListener('click',function(e){
      if(e.target.closest('a,button,form,label,input,textarea'))return;
      openQuick(card.dataset.id);
    });
  });
  grid.querySelectorAll('.c-checkbox').forEach(function(cb){
    cb.addEventListener('change',updateBulkBar);
  });
}
bindCardInteractions();

/* ── Bulk actions ── */
var bulkBar=document.getElementById('bulkBar');
function getSelectedIds(){return Array.from(grid.querySelectorAll('.c-checkbox:checked')).map(function(c){return +c.value;});}
function updateBulkBar(){
  var n=getSelectedIds().length;
  document.getElementById('bbCount').textContent=n;
  bulkBar.classList.toggle('open',n>0);
}
function clearSelection(){
  grid.querySelectorAll('.c-checkbox:checked').forEach(function(c){c.checked=false;});
  updateBulkBar();
}
document.getElementById('bbClear').addEventListener('click',clearSelection);

function postBulk(url,body,done){
  fetch(url,{method:'POST',headers:{'X-Requested-With':'XMLHttpRequest','X-CSRF-TOKEN':csrfToken(),'Content-Type':'application/json','Accept':'application/json'},body:JSON.stringify(body)})
    .then(function(r){return r.json();})
    .then(function(d){if(d.message)window.toast(d.message,d.type||'success');if(done)done();})
    .catch(function(){window.toast('Bulk action failed.','error');});
}
document.getElementById('bbApprove').addEventListener('click',function(){
  var ids=getSelectedIds();if(!ids.length)return;
  postBulk('{{ route('admin.campaigns.bulk-approve') }}',{ids:ids},function(){clearSelection();fetchGrid(currentPage);});
});

/* Bulk reason modal (reject / pause) */
var bulkMode=null;
document.getElementById('bbReject').addEventListener('click',function(){openBulk('reject');});
document.getElementById('bbPause').addEventListener('click',function(){openBulk('pause');});
function openBulk(mode){
  bulkMode=mode;
  var form=document.getElementById('bulkForm');
  form.action=mode==='reject'?'{{ route('admin.campaigns.bulk-reject') }}':'{{ route('admin.campaigns.bulk-pause') }}';
  document.getElementById('bulkTtl').textContent=mode==='reject'?'Reject Campaigns':'Pause Campaigns';
  document.getElementById('bulkSub').textContent='Action applies to '+getSelectedIds().length+' selected campaign(s).';
  document.getElementById('bulkReason').value='';
  document.getElementById('bulkErr').style.display='none';
  document.getElementById('bulkReq').style.display=mode==='reject'?'inline':'none';
  var btn=document.getElementById('bulkBtn');
  btn.className='modal-btn '+(mode==='reject'?'modal-red':'modal-amber');
  btn.textContent=mode==='reject'?'✕ Reject':'⏸ Pause';
  document.getElementById('bulkOverlay').classList.add('open');
  setTimeout(function(){document.getElementById('bulkReason').focus();},80);
}
function closeBulk(){document.getElementById('bulkOverlay').classList.remove('open');}
window.closeBulk=closeBulk;
document.getElementById('bulkForm').addEventListener('submit',function(e){
  e.preventDefault();
  if(bulkMode==='reject'&&!document.getElementById('bulkReason').value.trim()){document.getElementById('bulkErr').style.display='block';return;}
  var ids=getSelectedIds();
  var fd=new FormData(this);ids.forEach(function(id){fd.append('ids[]',id);});
  var btn=document.getElementById('bulkBtn');btn.disabled=true;btn.textContent='Processing…';
  fetch(this.action,{method:'POST',headers:{'X-Requested-With':'XMLHttpRequest','X-CSRF-TOKEN':csrfToken(),'Accept':'application/json'},body:fd})
    .then(function(r){return r.json();})
    .then(function(d){if(d.message)window.toast(d.message,d.type||'success');closeBulk();clearSelection();fetchGrid(currentPage);})
    .catch(function(){window.toast('Bulk action failed.','error');})
    .finally(function(){btn.disabled=false;btn.textContent=bulkMode==='reject'?'✕ Reject':'⏸ Pause';});
});

/* ── Quick view slide-over ── */
var quickPanel=document.getElementById('quickPanel');
var quickBackdrop=document.getElementById('quickBackdrop');
function openQuick(id){
  document.getElementById('quickContent').innerHTML='';
  document.getElementById('quickLoading').style.display='flex';
  quickPanel.classList.add('open');quickBackdrop.classList.add('open');
  fetch('{{ route('admin.campaign.quick','__ID__') }}'.replace('__ID__',id),{headers:{'X-Requested-With':'XMLHttpRequest'}})
    .then(function(r){return r.text();})
    .then(function(html){document.getElementById('quickContent').innerHTML=html;document.getElementById('quickLoading').style.display='none';})
    .catch(function(){document.getElementById('quickLoading').style.display='none';window.toast('Failed to load details.','error');});
}
function closeQuick(){quickPanel.classList.remove('open');quickBackdrop.classList.remove('open');}
window.closeQuick=closeQuick;

/* ── Re-render charts when theme toggles ── */
window.addEventListener('themechange',function(){loadChart();loadDoughnut();});

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
