@extends('layouts.admin')

@section('sidebar_dashboard', 'active')
@section('page_title', 'Dashboard')
@section('page_subtitle', now()->format('l, d F Y'))

@push('page_css')
@vite('resources/css/admin/entries/dashboard.css')
@endpush
@push('page_styles')
<style>
.stats-grid{grid-template-columns:repeat(3,1fr) !important;}
.chart-row-2{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:24px;}
@media(max-width:640px){
  .stats-grid{grid-template-columns:repeat(2,1fr) !important;}
  .chart-row-2{grid-template-columns:1fr;}
}
@media(max-width:480px){
  .stats-grid{grid-template-columns:1fr !important;}
}
@media(max-width:400px){
  .hero-greeting{gap:10px;}
  .hero-avatar{width:40px;height:40px;border-radius:12px;font-size:14px;}
  .hero-name{font-size:16px !important;}
  .hero-tag{font-size:9px;}
  .hero-sub{font-size:11px !important;}
  .hero{padding:14px 12px !important;}
  .hero-btn{padding:7px 12px !important;font-size:11px !important;gap:5px;}
  .hero-badge{font-size:10px;padding:4px 10px;}
  .chart-card,.chart-row-2 .chart-card{padding:14px !important;}
  .doughnut-card{padding:14px !important;}
  .stat{padding:10px 12px !important;gap:8px;}
  .stat-icon{width:30px;height:30px;border-radius:8px;}
  .stat-icon svg{width:12px;height:12px;}
  .stat-val{font-size:1rem !important;}
  .hm-val{font-size:14px;}
  .hm-icon{width:34px;height:34px;}
  .hm-icon svg{width:15px;height:15px;}
  .hero-metric{padding:10px 12px;gap:10px;}
}
@media(max-width:360px){
  .chart-hdr{margin-bottom:12px;gap:8px;flex-direction:column;}
  .chart-card,.chart-row-2 .chart-card{padding:12px !important;}
  .chart-wrap{height:120px;}
  .chart-ttl{font-size:13px;}
  .chart-sub{font-size:10px;}
}

/* ── Pending Actions ── */
.pending-actions .sp-row{text-decoration:none;}
.pending-actions .sp-row[data-zero=true]{opacity:.45;pointer-events:none;}
.pa-ico{width:16px;height:16px;flex-shrink:0;color:var(--text3);}
.pa-badge{font-size:13px;font-weight:700;font-family:var(--mono);padding:2px 10px;border-radius:20px;line-height:1;}

/* ── Activity Feed ── */
.af-list{display:flex;flex-direction:column;gap:2px;}
.af-item{display:flex;align-items:flex-start;gap:11px;padding:10px 0;border-bottom:1px solid var(--border);}
.af-item:last-child{border-bottom:none;padding-bottom:0;}
.af-ico{width:30px;height:30px;border-radius:var(--r-sm);display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.af-ico svg{width:14px;height:14px;}
.af-body{min-width:0;}
.af-desc{font-size:12.5px;color:var(--text2);line-height:1.4;}
.af-desc a{color:var(--text);font-weight:500;text-decoration:none;}
.af-desc a:hover{color:var(--a);}
.af-time{font-size:10.5px;color:var(--text3);margin-top:2px;}
.af-empty{padding:20px;text-align:center;color:var(--text3);font-size:13px;}
</style>
@endpush

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
  <span class="hero-glow g1"></span>
  <span class="hero-glow g2"></span>
  <span class="hero-particle" style="--x:12%;--y:18%;--s:4px;--d:0s"></span>
  <span class="hero-particle" style="--x:70%;--y:10%;--s:3px;--d:1.2s"></span>
  <span class="hero-particle" style="--x:85%;--y:60%;--s:5px;--d:2.5s"></span>
  <span class="hero-particle" style="--x:25%;--y:70%;--s:3px;--d:3.8s"></span>
  <span class="hero-particle" style="--x:55%;--y:80%;--s:4px;--d:5s"></span>
  <div class="hero-left">
    <div class="hero-tag"><span class="hero-tag-dot"></span>{{ $greeting }}, Administrator</div>
    <div class="hero-name">{{ auth()->user()->name ?? 'Admin' }}</div>
    <div class="hero-sub">Every donation tells a story of hope. Together, we're turning compassion into action — one campaign at a time.</div>
    <div class="hero-badges">
      @if($cntPending > 0)
        <span class="hero-badge hb-amber"><span class="hb-count" data-count="{{ $cntPending }}">0</span> awaiting review</span>
      @else
        <span class="hero-badge hb-green">All caught up</span>
      @endif
      <span class="hero-badge hb-green"><span class="hb-count" data-count="{{ $cntActive }}">0</span> active campaigns</span>
      <span class="hero-badge hb-purple"><span class="hb-count" data-count="{{ $approvalRate }}">0</span>% approval rate</span>
      <span class="hero-badge hb-teal"><span class="hb-count" data-count="{{ $activeJobs }}">0</span> open jobs</span>
    </div>
    <div class="hero-ticker">
      <span class="hero-ticker-dot"></span>
      <div class="hero-ticker-track" id="tickerTrack"></div>
    </div>
  </div>
  <div class="hero-right">
    <div class="hero-actions">
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
    <div class="hero-metric">
      <div class="hm-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      </div>
      <div class="hm-body">
        <div class="hm-lbl">Total Revenue</div>
        <div class="hm-val">₹ {{ number_format($totalRevenue) }}</div>
      </div>
    </div>
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
    <div class="stat-icon si-teal"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg></div>
    <div class="stat-body"><div class="stat-lbl">Active Jobs</div><div class="stat-val sv-teal">{{ $activeJobs }}</div><div class="stat-foot"><a href="{{ route('admin.job_posts.create') }}">+ Post new job →</a></div></div>
  </div>
  <div class="stat">
    <div class="stat-icon si-pink"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg></div>
    <div class="stat-body"><div class="stat-lbl">Volunteers</div><div class="stat-val sv-pink">{{ $volunteerCount }}</div><div class="stat-foot"><a href="{{ route('admin.volunteers.index') }}">{{ $pendingVolunteerApps }} pending →</a></div></div>
  </div>
  <div class="stat">
    <div class="stat-icon si-gray"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg></div>
    <div class="stat-body"><div class="stat-lbl">Total Users</div><div class="stat-val sv-gray">{{ $totalUsers }}</div><div class="stat-foot">Registered on platform</div></div>
  </div>
  <div class="stat">
    <div class="stat-icon si-red"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg></div>
    <div class="stat-body"><div class="stat-lbl">Donations</div><div class="stat-val sv-red">{{ $totalDonations }}</div><div class="stat-foot">All time on platform</div></div>
  </div>
  <div class="stat" onclick="window.location='{{ route('admin.wallets.index') }}'" style="cursor:pointer;">
    <div class="stat-icon si-teal"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg></div>
    <div class="stat-body"><div class="stat-lbl">Wallet Balance</div><div class="stat-val sv-teal">₹{{ number_format($totalWalletBalance) }}</div><div class="stat-foot">Across all wallets →</div></div>
  </div>
  <div class="stat">
    <div class="stat-icon si-blue"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg></div>
    <div class="stat-body"><div class="stat-lbl">New Today</div><div class="stat-val sv-blue">{{ $newUsersToday }}</div><div class="stat-foot">Users joined today</div></div>
  </div>
  <div class="stat">
    <div class="stat-icon si-purple"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
    <div class="stat-body"><div class="stat-lbl">Donations Today</div><div class="stat-val sv-purple">{{ $donationsToday }}</div><div class="stat-foot">Received in last 24h</div></div>
  </div>
      <div class="stat">
        <div class="stat-icon si-teal"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg></div>
        <div class="stat-body"><div class="stat-lbl">Active Jobs</div><div class="stat-val sv-teal">{{ $activeJobs }}</div><div class="stat-foot"><a href="{{ route('admin.job_posts.create') }}">+ Post new job →</a></div></div>
      </div>
      <div class="stat">
        <div class="stat-icon si-purple"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg></div>
        <div class="stat-body"><div class="stat-lbl">Volunteers</div><div class="stat-val sv-purple">{{ $volunteerCount }}</div><div class="stat-foot"><a href="{{ route('admin.volunteers.index') }}">{{ $pendingVolunteerApps }} pending →</a></div></div>
      </div>
      <div class="stat">
        <div class="stat-icon si-gray"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg></div>
        <div class="stat-body"><div class="stat-lbl">Total Users</div><div class="stat-val sv-gray">{{ $totalUsers }}</div><div class="stat-foot">{{ $newUsersToday }} joined today</div></div>
      </div>
      <div class="stat">
        <div class="stat-icon si-red"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg></div>
        <div class="stat-body"><div class="stat-lbl">Donations</div><div class="stat-val sv-red">{{ $totalDonations }}</div><div class="stat-foot">{{ $donationsToday }} received today</div></div>
      </div>
      <div class="stat">
        <div class="stat-icon si-amber"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
        <div class="stat-body"><div class="stat-lbl">Revenue</div><div class="stat-val sv-amber">₹ {{ number_format($totalRevenue) }}</div><div class="stat-foot">All time on platform</div></div>
      </div>
      <div class="stat">
        <div class="stat-icon si-a"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg></div>
        <div class="stat-body"><div class="stat-lbl">Avg Donation</div><div class="stat-val sv-a">₹ {{ number_format($avgDonation) }}</div><div class="stat-foot">Per donation transaction</div></div>
      </div>
      <div class="stat">
        <div class="stat-icon si-purple"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg></div>
        <div class="stat-body"><div class="stat-lbl">Unique Donors</div><div class="stat-val sv-purple">{{ $uniqueDonors }}</div><div class="stat-foot">People who donated</div></div>
      </div>
      <div class="stat">
        <div class="stat-icon si-green"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
        <div class="stat-body"><div class="stat-lbl">Success Rate</div><div class="stat-val sv-green">{{ $successRate }}%</div><div class="stat-foot">Campaigns completed</div></div>
      </div>
</div>

<div class="analytics-row">
  <div class="chart-card">
    <div class="chart-hdr">
      <div>
        <div class="chart-ttl">Campaign Activity</div>
        <div class="chart-sub">Monthly overview — last 6 months</div>
      </div>
      <div class="chart-legend">
        <div class="leg-item"><div class="leg-dot" style="background:#0d9488"></div>Total</div>
        <div class="leg-item"><div class="leg-dot" style="background:#f43f5e"></div>Approved</div>
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
      <div class="sp-prog-lbl"><span>Approved of reviewed</span><span>{{ $approvalRate }}%</span></div>
      <div class="sp-bar"><div class="sp-fill" id="approvalBar" style="width:0%"></div></div>
    </div>
  </div>

  {{-- ══ PENDING ACTIONS ══ --}}
  <div class="status-panel pending-actions">
    <div class="sp-ttl">Pending Actions</div>
    <a href="{{ route('admin.campaign.index') }}" class="sp-row">
      <div class="sp-left">
        <svg class="pa-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        <span class="sp-label">Campaigns awaiting review</span>
      </div>
      @if($cntPending > 0)
        <span class="pa-badge" style="background:var(--amber-lt);color:var(--amber);">{{ $cntPending }}</span>
      @else
        <span class="sp-label" style="font-size:11px;color:var(--text3);">All caught up</span>
      @endif
    </a>
    <a href="{{ route('admin.volunteer_applications.index') }}" class="sp-row">
      <div class="sp-left">
        <svg class="pa-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
        <span class="sp-label">Volunteer applications pending</span>
      </div>
      @if($pendingVolunteerApps > 0)
        <span class="pa-badge" style="background:var(--pink-lt);color:var(--pink);">{{ $pendingVolunteerApps }}</span>
      @else
        <span class="sp-label" style="font-size:11px;color:var(--text3);">All caught up</span>
      @endif
    </a>
    <a href="{{ route('admin.messages') }}" class="sp-row">
      <div class="sp-left">
        <svg class="pa-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        <span class="sp-label">Unread messages</span>
      </div>
      @if($unreadMessages > 0)
        <span class="pa-badge" style="background:var(--a-lt);color:var(--a);">{{ $unreadMessages }}</span>
      @else
        <span class="sp-label" style="font-size:11px;color:var(--text3);">All caught up</span>
      @endif
    </a>
    <a href="{{ route('admin.job_post_applications.index') }}" class="sp-row">
      <div class="sp-left">
        <svg class="pa-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        <span class="sp-label">Job applicants awaiting review</span>
      </div>
      @if($pendingJobApplicants > 0)
        <span class="pa-badge" style="background:var(--red-lt);color:var(--red);">{{ $pendingJobApplicants }}</span>
      @else
        <span class="sp-label" style="font-size:11px;color:var(--text3);">All caught up</span>
      @endif
    </a>
    <a href="{{ route('admin.settlements.index') }}" class="sp-row">
      <div class="sp-left">
        <svg class="pa-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
        <span class="sp-label">Settlements pending approval</span>
      </div>
      @if($pendingSettlements > 0)
        <span class="pa-badge" style="background:var(--teal-lt);color:var(--teal);">{{ $pendingSettlements }}</span>
      @else
        <span class="sp-label" style="font-size:11px;color:var(--text3);">All caught up</span>
      @endif
    </a>
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
  <div class="doughnut-wrap"><canvas id="doughnutChart"></canvas>
    <div class="doughnut-center">
      <div class="dc-val" id="dcVal">{{ $approvalRate }}%</div>
      <div class="dc-lbl">Approval Rate</div>
      <div class="dc-sub">{{ $cntActive }} of {{ $totalCampaigns }} active</div>
    </div>
  </div>
</div>

{{-- ══ COMMUNITY IMPACT ══ --}}
<div class="impact-row">
  <div class="impact-card impact-revenue" style="--accent:#0d9488">
    <div class="impact-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg></div>
    <div class="impact-body">
      <div class="impact-label"><svg class="impact-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>Community Impact</div>
      <div class="impact-value" id="impactRevenue"><span class="hc-count" data-count="{{ $totalRevenue }}">0</span></div>
      <div class="impact-foot">Raised by <strong>{{ $uniqueDonors }}</strong> donors across <strong>{{ $totalDonations }}</strong> contributions</div>
    </div>
  </div>
  <div class="impact-card impact-donors" style="--accent:#f43f5e">
    <div class="impact-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg></div>
    <div class="impact-body">
      <div class="impact-label"><svg class="impact-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>Community Growth</div>
      <div class="impact-value" id="impactUsers"><span class="hc-count" data-count="{{ $totalUsers }}">0</span></div>
      <div class="impact-foot"><strong>{{ $newUsersToday }}</strong> new member{{ $newUsersToday !== 1 ? 's' : '' }} joined today</div>
    </div>
  </div>
  <div class="impact-card impact-avg" style="--accent:#f59e0b">
    <div class="impact-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
    <div class="impact-body">
      <div class="impact-label"><svg class="impact-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>Average Gift</div>
      <div class="impact-value" id="impactAvg">₹<span class="hc-count" data-count="{{ $avgDonation }}">0</span></div>
      <div class="impact-foot">Per donation &mdash; <strong>{{ $successRate > 0 ? $successRate . '%' : 'awaiting' }}</strong> campaign completion rate</div>
    </div>
  </div>
</div>

{{-- ══ REVENUE TREND + TOP CAMPAIGNS (2-column row) ══ --}}
<div class="chart-row-2">
  <div class="chart-card">
    <div class="chart-hdr">
      <div>
        <div class="chart-ttl">Revenue Trend</div>
        <div class="chart-sub">Monthly donation revenue — last 6 months</div>
      </div>
      <div class="chart-legend">
        <div class="leg-item"><div class="leg-dot" style="background:var(--a)"></div>Revenue</div>
      </div>
    </div>
    <div class="chart-wrap"><canvas id="revenueChart"></canvas></div>
  </div>
  <div class="chart-card">
    <div class="chart-hdr">
      <div>
        <div class="chart-ttl">Top Campaigns by Funds Raised</div>
        <div class="chart-sub">Highest-grossing campaigns</div>
      </div>
    </div>
    <div class="chart-wrap"><canvas id="topCampChart"></canvas></div>
  </div>
</div>

{{-- ══ RECENT ACTIVITY ══ --}}
<div class="chart-card" style="margin-bottom:24px;">
  <div class="chart-hdr">
    <div>
      <div class="chart-ttl">Recent Activity</div>
      <div class="chart-sub">Latest platform events</div>
    </div>
  </div>
  @if($recentActivity->isNotEmpty())
    <div class="af-list">
      @foreach($recentActivity as $ev)
        <div class="af-item">
          @php
            $icoCfg = match($ev['type']) {
              'donation' => ['bg' => 'var(--green-lt)', 'col' => 'var(--green)', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>'],
              'campaign' => ['bg' => 'var(--amber-lt)', 'col' => 'var(--amber)', 'icon' => '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>'],
              'volunteer' => ['bg' => 'var(--pink-lt)', 'col' => 'var(--pink)', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/>'],
              'message' => ['bg' => 'var(--a-lt)', 'col' => 'var(--a)', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>'],
            };
          @endphp
          <div class="af-ico" style="background:{{ $icoCfg['bg'] }};color:{{ $icoCfg['col'] }};"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">{!! $icoCfg['icon'] !!}</svg></div>
          <div class="af-body">
            <div class="af-desc">
              <a href="{{ $ev['link'] }}">{{ $ev['desc'] }}</a>
            </div>
            <div class="af-time">{{ $ev['time']->diffForHumans() }}</div>
          </div>
        </div>
      @endforeach
    </div>
  @else
    <div class="af-empty">No recent activity to show.</div>
  @endif
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
    <select class="ftab-select" id="ftabSelect">
      <option value="all">All ({{ $totalCampaigns }})</option>
      <option value="pending">Pending ({{ $cntPending }})</option>
      <option value="active" selected>Active ({{ $cntActive }})</option>
      <option value="paused">Paused ({{ $cntPaused }})</option>
      <option value="inactive">Inactive ({{ $cntExpired + $cntCompleted }})</option>
      <option value="rejected">Rejected ({{ $cntRejected }})</option>
    </select>
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
    <button type="button" class="btn btn-green bb-btn bb-approve" id="bbApprove">✓ Approve</button>
    <button type="button" class="btn btn-yellow bb-btn bb-pause" id="bbPause">⏸ Pause</button>
    <button type="button" class="btn btn-red bb-btn bb-reject" id="bbReject">✕ Reject</button>
    <button type="button" class="btn btn-secondary bb-clear" id="bbClear">Clear</button>
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
      <div class="modal-acts"><button type="button" onclick="closeBulk()" class="btn btn-secondary modal-btn modal-cancel">Cancel</button><button type="submit" id="bulkBtn" class="btn btn-red modal-btn modal-red">Confirm</button></div>
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
      <div class="modal-acts"><button type="button" onclick="closePause()" class="btn btn-secondary modal-btn modal-cancel">Cancel</button><button type="submit" id="pauseBtn" class="btn btn-yellow modal-btn modal-amber">⏸ Pause Campaign</button></div>
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
      <div class="modal-acts"><button type="button" onclick="closeReject()" class="btn btn-secondary modal-btn modal-cancel">Cancel</button><button type="submit" id="rejectBtn" class="btn btn-red modal-btn modal-red">✕ Reject Campaign</button></div>
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

/* ── Entrance animations ── */
(function(){
  setTimeout(function(){
    var b=document.getElementById('approvalBar');
    if(b)b.style.width='{{ $approvalRate??0 }}%';
  },700);
  var cards=document.querySelectorAll('.stats-grid .stat');
  cards.forEach(function(c,i){
    c.style.animationDelay=(.08*i)+'s';
    c.style.opacity='0';
  });
  setTimeout(function(){
    cards.forEach(function(c,i){
      requestAnimationFrame(function(){
        c.style.animation='fadeUp .5s ease both';
        c.style.animationDelay=(.08*i)+'s';
      });
    });
  },50);
  var dcv=document.getElementById('dcVal');
  if(dcv){
    var match=dcv.textContent.match(/^(\d+)/);
    if(match&&match[1]>0){
      var target=parseInt(match[1],10);
      dcv.textContent='0%';
      requestAnimationFrame(function step(ts){
        if(!dcv._st)dcv._st=ts;
        var p=Math.min((ts-dcv._st)/900,1);
        dcv.textContent=Math.floor((1-Math.pow(1-p,3))*target)+'%';
        if(p<1)requestAnimationFrame(step);
      });
    }
  }
})();

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
  g1.addColorStop(0,'rgba(13,148,136,.25)');g1.addColorStop(1,'rgba(13,148,136,0)');
  var g2=ctx.createLinearGradient(0,0,0,190);
  g2.addColorStop(0,'rgba(244,63,94,.18)');g2.addColorStop(1,'rgba(244,63,94,0)');
  lineChart=new Chart(ctx,{
    type:'line',
    data:{
      labels:chartLabels,
      datasets:[
        {label:'Total Campaigns',data:chartTotal,borderColor:'#0d9488',backgroundColor:g1,borderWidth:2.5,pointRadius:4,tension:.45,fill:true,pointBackgroundColor:'#0d9488',pointBorderColor:tipBg,pointBorderWidth:2,pointHoverRadius:6},
        {label:'Approved',data:chartApproved,borderColor:'#f43f5e',backgroundColor:g2,borderWidth:2.5,pointRadius:4,tension:.45,fill:true,pointBackgroundColor:'#f43f5e',pointBorderColor:tipBg,pointBorderWidth:2,pointHoverRadius:6}
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
document.querySelectorAll('.hb-count').forEach(function (el) {
  var target = parseInt(el.getAttribute('data-count'), 10) || 0;
  if (target > 0) animateCounter(el, target);
});
document.querySelectorAll('.hc-count').forEach(function (el) {
  var target = parseInt(el.getAttribute('data-count'), 10) || 0;
  if (target > 0) animateCounter(el, target);
});

/* ── Live activity ticker ── */
(function(){
  var items = [
    '<b>{{ $donationsToday }}</b> donation{{ $donationsToday !== 1 ? "s" : "" }} received today',
    '<b>{{ $newUsersToday }}</b> new user{{ $newUsersToday !== 1 ? "s" : "" }} joined today',
    '<b>{{ $cntPending }}</b> campaign{{ $cntPending !== 1 ? "s" : "" }} awaiting review',
    '<b>{{ $cntActive }}</b> campaign{{ $cntActive !== 1 ? "s" : "" }} running live',
    'Platform raised <b>&#8377;{{ number_format($totalRevenue) }}</b> all time'
  ].filter(function(s){ return !s.startsWith('<b>0</b>'); });
  if (!items.length) return;
  var track = document.getElementById('tickerTrack');
  if (!track) return;
  var i = 0;
  function show(){
    var el = document.createElement('div');
    el.className = 'hero-ticker-item';
    el.innerHTML = items[i];
    track.innerHTML = '';
    track.appendChild(el);
    requestAnimationFrame(function(){ el.classList.add('show'); });
    i = (i + 1) % items.length;
    setTimeout(show, 3200);
  }
  show();
})();

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
        backgroundColor: ['#0d9488', '#f59e0b', '#f97316', '#f43f5e', '#94a3b8'],
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

/* ── Revenue Trend chart ── */
var revLabels=@json($revLabels);
var revData=@json($revData);
var revenueChart;

function loadRevenueChart(){
  var canvas=document.getElementById('revenueChart');
  if(!canvas||typeof Chart==='undefined')return;
  if(revenueChart){revenueChart.destroy();revenueChart=null;}
  var isDark=html.getAttribute('data-theme')==='dark';
  var gridCol=isDark?'rgba(255,255,255,.05)':'rgba(0,0,0,.04)';
  var lblCol=isDark?'rgba(255,255,255,.32)':'rgba(0,0,0,.32)';
  var tipBg=isDark?'#1d1f35':'#fff';
  var tipTx=isDark?'#eef0ff':'#0a0b14';
  var ctx=canvas.getContext('2d');
  var g=ctx.createLinearGradient(0,0,0,190);
  g.addColorStop(0,'rgba(37,99,235,.18)');g.addColorStop(1,'rgba(37,99,235,0)');
  revenueChart=new Chart(ctx,{
    type:'line',
    data:{
      labels:revLabels,
      datasets:[{
        label:'Revenue',
        data:revData,
        borderColor:'#2563eb',
        backgroundColor:g,
        borderWidth:2.5,
        pointRadius:4,
        tension:.45,
        fill:true,
        pointBackgroundColor:'#2563eb',
        pointBorderColor:tipBg,
        pointBorderWidth:2,
        pointHoverRadius:6
      }]
    },
    options:{
      responsive:true,maintainAspectRatio:false,
      interaction:{intersect:false,mode:'index'},
      plugins:{
        legend:{display:false},
        tooltip:{
          backgroundColor:tipBg,titleColor:tipTx,bodyColor:tipTx,
          borderColor:gridCol,borderWidth:1,padding:13,cornerRadius:11,
          titleFont:{size:11,weight:'700'},bodyFont:{size:11},
          callbacks:{
            label:function(c){return ' ₹'+Number(c.parsed).toLocaleString('en-IN',{maximumFractionDigits:0});}
          }
        }
      },
      scales:{
        x:{grid:{color:gridCol},border:{dash:[3,3],display:false},ticks:{color:lblCol}},
        y:{grid:{color:gridCol},border:{dash:[3,3],display:false},beginAtZero:true,ticks:{color:lblCol,callback:function(v){return '₹'+(v/1000).toFixed(0)+'k';}}}
      },
      animation:{duration:900,easing:'easeOutQuart'}
    }
  });
}
setTimeout(loadRevenueChart, 300);

/* ── Top Campaigns bar chart ── */
var topCampLabels=@json($topCampLabels);
var topCampValues=@json($topCampValues);
var topCampChart;

function loadTopCampChart(){
  var canvas=document.getElementById('topCampChart');
  if(!canvas||typeof Chart==='undefined')return;
  if(topCampChart){topCampChart.destroy();topCampChart=null;}
  var isDark=html.getAttribute('data-theme')==='dark';
  var gridCol=isDark?'rgba(255,255,255,.05)':'rgba(0,0,0,.04)';
  var lblCol=isDark?'rgba(255,255,255,.32)':'rgba(0,0,0,.32)';
  var tipBg=isDark?'#1d1f35':'#fff';
  var tipTx=isDark?'#eef0ff':'#0a0b14';
  var barColor=isDark?'rgba(37,99,235,.7)':'rgba(37,99,235,.75)';
  var barHover=isDark?'rgba(37,99,235,.9)':'rgba(37,99,235,.95)';
  topCampChart=new Chart(canvas,{
    type:'bar',
    data:{
      labels:topCampLabels,
      datasets:[{
        label:'Raised',
        data:topCampValues,
        backgroundColor:barColor,
        hoverBackgroundColor:barHover,
        borderColor:isDark?'rgba(37,99,235,.9)':'#2563eb',
        borderWidth:1,
        borderRadius:4,
        barPercentage:.65
      }]
    },
    options:{
      responsive:true,maintainAspectRatio:false,
      indexAxis:'y',
      plugins:{
        legend:{display:false},
        tooltip:{
          backgroundColor:tipBg,titleColor:tipTx,bodyColor:tipTx,
          borderColor:gridCol,borderWidth:1,padding:13,cornerRadius:11,
          titleFont:{size:11,weight:'700'},bodyFont:{size:11},
          callbacks:{
            label:function(c){return ' ₹'+Number(c.parsed).toLocaleString('en-IN',{maximumFractionDigits:0});}
          }
        }
      },
      scales:{
        x:{
          grid:{color:gridCol},
          border:{dash:[3,3],display:false},
          beginAtZero:true,
          ticks:{color:lblCol,callback:function(v){return '₹'+(v/1000).toFixed(0)+'k';}}
        },
        y:{
          grid:{display:false},
          border:{display:false},
          ticks:{color:lblCol,font:{size:10.5}}
        }
      },
      animation:{duration:900,easing:'easeOutQuart'}
    }
  });
}
setTimeout(loadTopCampChart, 400);

/* ── AJAX Campaign grid (filter / search / sort / pagination) ── */
var grid=document.getElementById('campaignGrid');
var paginationWrap=document.getElementById('paginationWrap');
var noResults=document.getElementById('noResults');
var state='active',searchQ='',sortVal='',isFetching=false,currentPage=1;

function csrfToken(){var m=document.querySelector('meta[name=csrf-token]');return m?m.getAttribute('content'):'';}

function setTab(f,v){
  var el=document.querySelector('.ftab[data-filter="'+f+'"] .cnt');
  if(el)el.textContent=v;
  var opt=document.querySelector('#ftabSelect option[value="'+f+'"]');
  if(opt)opt.textContent=f.charAt(0).toUpperCase()+f.slice(1)+' ('+v+')';
}

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
    var sel=document.getElementById('ftabSelect');
    if(sel)sel.value=state;
  });
});
var ftabSelect=document.getElementById('ftabSelect');
if(ftabSelect){
  ftabSelect.addEventListener('change',function(){
    window.setFilter(this.value);
  });
}

document.getElementById('ftabSelect').addEventListener('change',function(){
  state=this.value;
  document.querySelectorAll('.ftab').forEach(function(t){t.classList.toggle('on',t.dataset.filter===state);});
  fetchGrid(1);
});

window.setFilter=function(f){
  state=f;
  document.querySelectorAll('.ftab').forEach(function(t){t.classList.toggle('on',t.dataset.filter===f);});
  var sel=document.getElementById('ftabSelect');
  if(sel)sel.value=f;
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
  bindTilt();
}

/* ── 3D hover tilt on campaign cards ── */
function bindTilt(){
  if (window.matchMedia('(hover: none)').matches) return;
  grid.querySelectorAll('.c-card:not(.no-tilt)').forEach(function(card){
    if (card.dataset.tilted) return;
    card.dataset.tilted = '1';
    card.addEventListener('mousemove', function(e){
      var r = card.getBoundingClientRect();
      var px = (e.clientX - r.left) / r.width  - .5;
      var py = (e.clientY - r.top)  / r.height - .5;
      card.style.transform = 'translateY(-5px) rotateX(' + (-py * 6).toFixed(2) + 'deg) rotateY(' + (px * 8).toFixed(2) + 'deg)';
    });
    card.addEventListener('mouseleave', function(){
      card.style.transform = '';
    });
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
window.addEventListener('themechange',function(){loadChart();loadDoughnut();loadRevenueChart();loadTopCampChart();});

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
