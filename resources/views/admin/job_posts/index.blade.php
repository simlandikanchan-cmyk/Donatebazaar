@extends('layouts.admin')

@section('sidebar_job_posts', 'active')
@section('page_title', 'Job Posts')
@section('page_subtitle', 'Manage job listings')

@push('page_styles')
<style>
/* ── extra stat colors (not in admin.css) ── */
.si-gray{background:rgba(107,114,128,.12);color:#6b7280}
.sv-gray{color:#6b7280}
.si-pink{background:var(--pink-lt);color:var(--pink)}
.sv-pink{color:var(--pink)}

/* ── badge for closed (not in admin.css) ── */
.b-closed{background:rgba(240,68,68,.85);color:#fff}

/* ── dual stat card ── */
.stat-dual{display:flex;gap:14px;align-items:center}
.stat-dual-sep{width:1px;height:32px;background:var(--border2);flex-shrink:0}

/* ── filter/search row ── */
.filter-row{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:18px;flex-wrap:wrap;animation:fadeUp .4s .15s ease both}
.filter-right{display:flex;gap:8px;align-items:center}
.swrap{position:relative}
.swrap .sico{position:absolute;left:10px;top:50%;transform:translateY(-50%);width:12px;height:12px;color:var(--text3);pointer-events:none;z-index:1}
.sinp{height:36px;min-width:180px;padding:0 10px 0 30px;border:1px solid var(--border2);border-radius:var(--r-sm);font-size:12.5px;color:var(--text);font-family:var(--font);background:var(--surface2);outline:none;transition:border-color var(--ease),box-shadow var(--ease),width var(--ease)}
.sinp::placeholder{color:var(--text3)}
.sinp:focus{border-color:var(--a);box-shadow:0 0 0 3px var(--a-glow);width:220px}

/* ── table card ── */
.table-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh);overflow:hidden;animation:fadeUp .4s .2s ease both}
.table-scroll{overflow-x:auto}
table{width:100%;min-width:900px;border-collapse:collapse}
thead{background:var(--surface2);border-bottom:1px solid var(--border)}
thead th{padding:12px 16px;text-align:left;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.09em;color:var(--text3);font-family:var(--mono);white-space:nowrap}
thead th:first-child{padding-left:20px}
thead th:last-child{padding-right:20px;text-align:right}
tbody td{padding:14px 16px;border-bottom:1px solid var(--border);vertical-align:middle}
tbody td:first-child{padding-left:20px}
tbody td:last-child{padding-right:20px}
tbody tr:last-child td{border-bottom:none}
tbody tr{transition:background var(--ease)}
tbody tr:hover{background:var(--surface2)}

/* ── job cells ── */
.job-title{font-size:13.5px;font-weight:600;color:var(--text);line-height:1.2}
.job-slug{font-size:10.5px;color:var(--text3);font-family:var(--mono);margin-top:2px;max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.job-dept{display:flex;align-items:center;gap:4px;margin-top:3px;font-size:10px;color:var(--text3);font-family:var(--mono)}
.job-dept svg{width:9px;height:9px;color:var(--text3);flex-shrink:0}
.cell-id{font-family:var(--mono);font-size:11px;color:var(--text3);font-weight:500}
.cell-mono{font-family:var(--mono);font-size:11.5px;font-weight:600;color:var(--text2)}
.cell-date{font-family:var(--mono);font-size:11px;color:var(--text3);white-space:nowrap}
.cell-date-sub{font-size:9.5px;margin-top:1px;color:var(--text3)}

/* ── deadline ── */
.deadline-chip{display:inline-flex;align-items:center;gap:3px;font-size:10px;font-weight:600;color:var(--amber);font-family:var(--mono);white-space:nowrap}
.deadline-chip.expired{color:var(--red)}
.deadline-chip svg{width:10px;height:10px}
.deadline-sub{font-size:9.5px;color:var(--text3);font-family:var(--mono);margin-top:1px}
.deadline-sub.expired{color:var(--red)}

/* ── feature / remote pills ── */
.featured-star{display:inline-flex;align-items:center;gap:3px;font-size:9.5px;font-weight:700;padding:2px 7px;border-radius:100px;background:var(--amber-lt);color:var(--amber);border:1px solid rgba(245,158,11,.25);font-family:var(--mono);white-space:nowrap;margin-top:3px}
.remote-pill{display:inline-flex;align-items:center;gap:3px;font-size:9.5px;font-weight:700;padding:2px 7px;border-radius:100px;background:var(--a-lt);color:var(--a);border:1px solid rgba(37,99,235,.2);font-family:var(--mono);white-space:nowrap}
.remote-pill svg{width:9px;height:9px}

/* ── compact detail cells ── */
.details-wrap{display:flex;flex-direction:column;gap:3px}
.details-row{display:flex;flex-wrap:wrap;gap:2px 10px;font-size:11.5px;color:var(--text2)}
.details-row span{display:inline-flex;align-items:center;gap:3px;font-family:var(--mono)}
.details-row svg{width:9px;height:9px;color:var(--text3);flex-shrink:0}

/* ── metrics ── */
.metric-dual{display:flex;gap:14px;align-items:center}
.metric-dual-sep{width:1px;height:26px;background:var(--border2);flex-shrink:0}
.metric-val{font-family:var(--mono);font-size:13px;font-weight:700;line-height:1}
.metric-lbl{font-size:9px;color:var(--text3);font-family:var(--mono);margin-top:1px}
.vac-val{font-family:var(--mono);font-size:13px;font-weight:700;color:var(--a);line-height:1}
.vac-lbl{font-size:9.5px;color:var(--text3);font-family:var(--mono);margin-top:1px}

/* ── action buttons ── */
.act-btns{display:flex;align-items:center;justify-content:flex-end;gap:4px}
.act-btn{display:inline-flex;align-items:center;gap:4px;padding:5px 10px;border-radius:7px;font-size:11.5px;font-weight:500;cursor:pointer;border:1px solid transparent;transition:all var(--ease);text-decoration:none;font-family:var(--font);white-space:nowrap}
.act-btn svg{width:11px;height:11px}
.act-btn:active{transform:scale(.96)}
.ab-view{background:var(--surface2);color:var(--text2);border-color:var(--border2)}
.ab-view:hover{background:var(--a-lt);color:var(--a);border-color:rgba(37,99,235,.2)}
.ab-edit{background:var(--a-lt);color:var(--a);border-color:rgba(37,99,235,.18)}
.ab-edit:hover{background:var(--a);color:#fff;border-color:var(--a)}
.ab-delete{background:var(--red-lt);color:var(--red);border-color:rgba(240,68,68,.18)}
.ab-delete:hover{background:var(--red);color:#fff;border-color:var(--red)}

/* ── empty state ── */
.empty-row td{text-align:center;padding:56px 20px}
.empty-inner{display:flex;flex-direction:column;align-items:center;gap:10px}
.empty-inner svg{width:48px;height:48px;color:var(--text3);opacity:.25}
.empty-inner strong{font-family:var(--mono);font-size:15px;font-weight:700;color:var(--text2)}
.empty-inner span{font-size:13px;color:var(--text3)}

/* ── mobile card layout ── */
@media(max-width:960px){
  thead{display:none}
  table{min-width:0}
  tbody tr{display:block;margin-bottom:12px;padding:12px;background:var(--surface);border:1px solid var(--border);border-radius:var(--r-sm);box-shadow:var(--sh)}
  tbody tr:hover{background:var(--surface)}
  tbody td{display:flex;align-items:flex-start;gap:8px;padding:7px 6px;border:none!important;text-align:left}
  tbody td:first-child{padding-left:6px}
  tbody td:last-child{padding-right:6px}
  tbody td::before{content:attr(data-label);font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--text3);font-family:var(--mono);min-width:70px;flex-shrink:0;padding-top:2px}
  .sinp{min-width:0;width:100%}
  .sinp:focus{width:100%}
  .filter-row{flex-direction:column;align-items:stretch}
  .filter-right{flex-wrap:wrap}
  .act-btns{flex-wrap:wrap;gap:5px;width:100%}
  .act-btn{flex:1;justify-content:center;min-width:0}
  .sec-hdr{flex-direction:column;align-items:flex-start}
}
@media(max-width:600px){
  .stats-grid{grid-template-columns:1fr 1fr!important}
  .act-btn span{display:none}
}
@media(max-width:480px){
  .stats-grid{grid-template-columns:1fr!important}
}
@media(max-width:380px){
  .sec-hdr h2{font-size:clamp(16px,4.5vw,18px)}.sec-hdr p{font-size:11px}
  .stats-grid{gap:8px}.stat-card{padding:14px 10px}.stat-num{font-size:clamp(18px,5vw,20px)}.stat-lbl{font-size:9px}
  .filter-row{gap:6px}.sinp{font-size:11px;height:34px;padding:0 10px}.filter-right{gap:4px}.filter-right .btn{font-size:11px;height:32px;padding:0 10px;flex:1;justify-content:center}
  tbody tr{padding:10px}.table{margin:0 -10px;width:calc(100% + 20px)}
  tbody td{font-size:11px;padding:5px 4px}
  tbody td::before{font-size:8px;min-width:55px}
  .act-btns{gap:4px}.act-btn{font-size:10px;padding:5px 8px;height:28px}
  .pagination-wrap{flex-direction:column;gap:8px;padding:12px 14px}
  .empty-state{padding:30px 16px}
}
</style>
@endpush

@section('content')

@php
  $totalJobs   = $jobPosts->total();
  $cntActive   = \App\Models\JobPost::where('status', 'active')
                   ->where(fn($q) => $q->whereNull('application_deadline')
                                        ->orWhereDate('application_deadline', '>=', now()))
                   ->count();
  $cntDraft    = \App\Models\JobPost::where('status', 'draft')->count();
  $cntClosed   = \App\Models\JobPost::where(fn($q) =>
                   $q->where('status', 'closed')
                     ->orWhere(fn($q2) => $q2->whereNotNull('application_deadline')
                                             ->whereDate('application_deadline', '<', now()))
                 )->count();
  $cntRemote   = \App\Models\JobPost::where('is_remote', 1)->count();
  $cntFeatured = \App\Models\JobPost::where('featured', 1)->count();
  $totalVac    = \App\Models\JobPost::sum('vacancies');
  $totalViews  = \App\Models\JobPost::sum('views_count');
  $totalApps   = \App\Models\JobPost::sum('applications_count');
@endphp

<div class="hero">
  <div class="hero-left">
    <div class="hero-tag"><span class="hero-tag-dot"></span>Job Board</div>
    <div class="hero-name">All Job Posts</div>
    <div class="hero-sub">Manage, monitor, and publish every listing on the DonateBazaar job board.</div>
    <div class="hero-badges">
      <span class="hero-badge hb-teal">{{ $totalJobs }} total</span>
      @if($cntActive > 0)
        <span class="hero-badge hb-green">● {{ $cntActive }} active</span>
      @endif
      @if($cntDraft > 0)
        <span class="hero-badge hb-amber">✎ {{ $cntDraft }} draft</span>
      @endif
      @if($cntClosed > 0)
        <span class="hero-badge hb-red">✕ {{ $cntClosed }} closed</span>
      @endif
    </div>
  </div>
  <div class="hero-right">
    <a href="{{ route('admin.job_posts.create') }}" class="hero-btn hero-btn-primary">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
      Post a Job
    </a>
    <a href="{{ route('admin.job_post_applications.index') }}" class="hero-btn hero-btn-ghost">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
      Applicants
    </a>
  </div>
</div>

<div class="stats-grid" style="grid-template-columns:repeat(4,1fr)">
  <div class="stat" onclick="setFilter('all')" style="cursor:pointer">
    <div class="stat-icon si-teal">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
    </div>
    <div class="stat-body">
      <div class="stat-lbl">Total Posts</div>
      <div class="stat-val sv-teal">{{ $totalJobs }}</div>
      <div class="stat-foot">All listings</div>
    </div>
  </div>
  <div class="stat" onclick="setFilter('active')" style="cursor:pointer">
    <div class="stat-icon si-green">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    </div>
    <div class="stat-body">
      <div class="stat-lbl">Active</div>
      <div class="stat-val sv-green">{{ $cntActive }}</div>
      <div class="stat-foot">Open &amp; accepting</div>
    </div>
  </div>
  <div class="stat" onclick="setFilter('draft')" style="cursor:pointer">
    <div class="stat-icon si-amber">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
    </div>
    <div class="stat-body">
      <div class="stat-lbl">Drafts</div>
      <div class="stat-val sv-amber">{{ $cntDraft }}</div>
      <div class="stat-foot">Unpublished</div>
    </div>
  </div>
  <div class="stat" onclick="setFilter('closed')" style="cursor:pointer">
    <div class="stat-icon si-gray">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
    </div>
    <div class="stat-body">
      <div class="stat-lbl">Closed</div>
      <div class="stat-val sv-gray">{{ $cntClosed }}</div>
      <div class="stat-foot">Expired or closed</div>
    </div>
  </div>
</div>

<div class="stats-grid" style="grid-template-columns:repeat(4,1fr)">
  <div class="stat" onclick="setFilter('remote')" style="cursor:pointer">
    <div class="stat-icon si-a">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064"/></svg>
    </div>
    <div class="stat-body">
      <div class="stat-lbl">Remote</div>
      <div class="stat-val sv-a">{{ $cntRemote }}</div>
      <div class="stat-foot">Work from anywhere</div>
    </div>
  </div>
  <div class="stat" onclick="setFilter('featured')" style="cursor:pointer">
    <div class="stat-icon si-amber">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
    </div>
    <div class="stat-body">
      <div class="stat-lbl">Featured</div>
      <div class="stat-val sv-amber">{{ $cntFeatured }}</div>
      <div class="stat-foot">Promoted listings</div>
    </div>
  </div>
  <div class="stat" style="cursor:default">
    <div class="stat-icon si-blue">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
    </div>
    <div class="stat-body">
      <div class="stat-lbl">Total Views</div>
      <div class="stat-val sv-blue">{{ number_format($totalViews) }}</div>
      <div class="stat-foot">Across all posts</div>
    </div>
  </div>
  <div class="stat" style="cursor:default">
    <div class="stat-icon si-pink">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
    </div>
    <div class="stat-body">
      <div class="stat-lbl">Applications &amp; Vacancies</div>
      <div class="stat-dual">
        <div>
          <div class="stat-val sv-pink" style="font-size:1.6rem;">{{ number_format($totalApps) }}</div>
          <div class="stat-foot">applications</div>
        </div>
        <div class="stat-dual-sep"></div>
        <div>
          <div class="stat-val sv-green" style="font-size:1.6rem;">{{ number_format($totalVac) }}</div>
          <div class="stat-foot">vacancies</div>
        </div>
      </div>
    </div>
  </div>
</div>

@if(session('success'))
<div style="background:rgba(5,196,138,.09);border:1px solid rgba(5,196,138,.25);color:#065f46;padding:12px 16px;border-radius:var(--r-sm);font-size:13px;font-weight:500;margin-bottom:18px;display:flex;align-items:center;gap:8px">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:15px;height:15px;flex-shrink:0"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
  {{ session('success') }}
</div>
@endif

<div class="filter-row">
  <div class="ftabs" id="ftabs">
    <button class="ftab on" data-filter="all">All <span class="cnt">{{ $totalJobs }}</span></button>
    <button class="ftab" data-filter="active">Active <span class="cnt">{{ $cntActive }}</span></button>
    <button class="ftab" data-filter="draft">Draft <span class="cnt">{{ $cntDraft }}</span></button>
    <button class="ftab" data-filter="closed">Closed <span class="cnt">{{ $cntClosed }}</span></button>
    <button class="ftab" data-filter="remote">Remote <span class="cnt">{{ $cntRemote }}</span></button>
    <button class="ftab" data-filter="featured">Featured <span class="cnt">{{ $cntFeatured }}</span></button>
  </div>
  <div class="filter-right">
    <div class="swrap">
      <svg class="sico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
      <input type="text" id="searchInput" class="sinp" placeholder="Search jobs…">
    </div>
    <select class="sort-sel" id="sortSelect">
      <option value="">Sort by…</option>
      <option value="date-desc">Newest first</option>
      <option value="date-asc">Oldest first</option>
      <option value="az">A → Z</option>
      <option value="za">Z → A</option>
      <option value="views-desc">Most views</option>
      <option value="apps-desc">Most applications</option>
      <option value="vac-desc">Most vacancies</option>
    </select>
  </div>
</div>

<div class="sec-hdr">
  <div class="sec-ttl">Job Listings</div>
  <div style="font-size:12px;color:var(--text3);font-family:var(--mono);">
    Showing <strong style="color:var(--text);">{{ $jobPosts->firstItem() }}–{{ $jobPosts->lastItem() }}</strong> of <strong style="color:var(--text);">{{ $jobPosts->total() }}</strong>
  </div>
</div>

<div class="table-card">
  <div class="table-scroll">
    <table id="jobTable">
      <thead>
        <tr>
          <th style="width:40px">#</th>
          <th>Job / Department</th>
          <th>Details</th>
          <th>Salary</th>
          <th style="width:60px">Vac.</th>
          <th>Deadline</th>
          <th>Stats</th>
          <th>Status</th>
          <th>Posted</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody id="tableBody">
        @forelse($jobPosts as $i => $job)

        @php
          $isExpiredRow = $job->application_deadline
              && \Carbon\Carbon::parse($job->application_deadline)->isPast();
          $rowFilter = match(true) {
              $job->status === 'draft'           => 'draft',
              $job->status === 'closed'
                  || $isExpiredRow               => 'closed',
              default                            => 'active',
          };
        @endphp

        <tr
          data-filter="{{ $rowFilter }}"
          data-remote="{{ $job->is_remote ? 'remote' : '' }}"
          data-featured="{{ $job->featured ? 'featured' : '' }}"
          data-title="{{ strtolower($job->title) }} {{ strtolower($job->slug) }} {{ strtolower($job->department ?? '') }}"
          data-date="{{ $job->created_at }}"
          data-views="{{ $job->views_count }}"
          data-apps="{{ $job->applications_count }}"
          data-vac="{{ $job->vacancies ?? 0 }}"
        >
          <td class="cell-id" data-label="#">{{ $jobPosts->firstItem() + $i }}</td>

          <td data-label="Job">
            <div style="display:flex;align-items:center;gap:5px;flex-wrap:wrap;margin-bottom:1px;">
              <span class="job-title">{{ $job->title }}</span>
              @if($job->featured)
                <span style="font-size:11px;color:var(--amber);" title="Featured">★</span>
              @endif
            </div>
            @if($job->department)
              <div class="job-dept">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                <span>{{ $job->department }}</span>
              </div>
            @endif
            <div style="display:flex;align-items:center;gap:5px;margin-top:3px;flex-wrap:wrap;">
              <span class="job-slug">/{{ $job->slug }}</span>
              @if($job->is_remote)
                <span class="remote-pill">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064"/></svg>
                  Remote
                </span>
              @endif
            </div>
          </td>

          <td data-label="Details">
            <div class="details-wrap">
              @if($job->type)
                <div class="details-row">
                  <span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    {{ $job->type }}
                  </span>
              @endif
              @if($job->location)
                  <span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    {{ $job->location }}
                  </span>
              @endif
              @if($job->experience_required)
                  <span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    {{ $job->experience_required }}
                  </span>
              @endif
                </div>
              @if(!$job->type && !$job->location && !$job->experience_required)
                <span style="color:var(--text3);font-size:11px;">—</span>
              @endif
            </div>
          </td>

          <td data-label="Salary">
            @if($job->salary)
              <span class="cell-mono" style="color:var(--green);">{{ $job->salary }}</span>
            @else
              <span style="color:var(--text3);font-size:11px;">Undisclosed</span>
            @endif
          </td>

          <td data-label="Vac." style="text-align:center">
            @if($job->vacancies)
              <div class="vac-val">{{ $job->vacancies }}</div>
              <div class="vac-lbl">{{ $job->vacancies === 1 ? 'seat' : 'seats' }}</div>
            @else
              <span style="color:var(--text3);font-size:11px;">—</span>
            @endif
          </td>

          <td data-label="Deadline">
            @if($job->application_deadline)
              @php $dl = \Carbon\Carbon::parse($job->application_deadline); @endphp
              <div class="deadline-chip {{ $isExpiredRow ? 'expired' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                {{ $dl->format('d M Y') }}
              </div>
              <div class="deadline-sub {{ $isExpiredRow ? 'expired' : '' }}">
                {{ $isExpiredRow ? 'Expired' : $dl->diffForHumans() }}
              </div>
            @else
              <span style="color:var(--text3);font-size:11px;">Rolling</span>
            @endif
          </td>

          <td data-label="Stats">
            <div class="metric-dual">
              <div>
                <div class="metric-val" style="color:var(--blue);">{{ number_format($job->views_count) }}</div>
                <div class="metric-lbl">views</div>
              </div>
              <div class="metric-dual-sep"></div>
              <div>
                <div class="metric-val" style="color:var(--pink);">{{ number_format($job->applications_count) }}</div>
                <div class="metric-lbl">apps</div>
              </div>
            </div>
          </td>

          <td data-label="Status">
            @if($rowFilter === 'closed')
              <span class="badge b-closed"><span class="b-dot" style="background:#fff;"></span>Closed</span>
            @elseif($rowFilter === 'draft')
              <span class="badge b-draft">Draft</span>
            @else
              <span class="badge b-active"><span class="b-dot" style="background:#fff;"></span>Active</span>
            @endif
            @if($job->featured)
              <div class="featured-star">★ Featured</div>
            @endif
            @if($job->published_at)
              <div class="cell-date-sub" style="margin-top:3px;">Live {{ \Carbon\Carbon::parse($job->published_at)->diffForHumans() }}</div>
            @endif
          </td>

          <td data-label="Posted" class="cell-date">
            {{ $job->created_at->format('d M Y') }}
            <div class="cell-date-sub">{{ $job->created_at->format('H:i') }}</div>
          </td>

          <td data-label="Actions">
            <div class="act-btns">
              <a href="{{ route('admin.job_posts.show', $job->id) }}" class="btn btn-secondary act-btn ab-view">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                <span>View</span>
              </a>
              <a href="{{ route('admin.job_posts.edit', $job->id) }}" class="btn btn-secondary act-btn ab-edit">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                <span>Edit</span>
              </a>
              <button type="button" onclick="confirmDelete({{ $job->id }}, '{{ addslashes($job->title) }}')" class="btn btn-red act-btn ab-delete">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                <span>Delete</span>
              </button>
            </div>
          </td>
        </tr>

        @empty
        <tr class="empty-row">
          <td colspan="10" style="text-align:center;padding:56px 20px;">
            <div class="empty-inner">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
              <strong>No job posts yet</strong>
              <span>Get started by posting your first job listing.</span>
              <a href="{{ route('admin.job_posts.create') }}" class="hero-btn hero-btn-primary" style="margin-top:8px;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                Post First Job
              </a>
            </div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>

    <div id="noResults"></div>
  </div>

  <div class="pagination-wrap" style="display:flex;align-items:center;justify-content:space-between;padding:14px 16px;border-top:1px solid var(--border);flex-wrap:wrap;gap:10px;">
    <div style="font-size:12px;color:var(--text3);font-family:var(--mono);">
      Showing <strong style="color:var(--text);">{{ $jobPosts->firstItem() }}–{{ $jobPosts->lastItem() }}</strong> of <strong style="color:var(--text);">{{ $jobPosts->total() }}</strong>
    </div>
    {{ $jobPosts->onEachSide(1)->links('vendor.pagination.admin') }}
  </div>
</div>

<div id="deleteOverlay" class="overlay" role="dialog" aria-modal="true">
  <div class="modal">
    <button type="button" class="modal-x" onclick="closeDelete()">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
    <div class="modal-head">
      <div class="modal-ico" style="background:var(--red-lt);">
        <svg viewBox="0 0 24 24" fill="none" stroke="var(--red)" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
      </div>
      <div>
        <div class="modal-ttl">Delete Job Post</div>
        <div class="modal-sub">This action cannot be undone</div>
      </div>
    </div>
    <div class="modal-body">Are you sure you want to delete <strong id="deleteJobTitle">"Job Title"</strong>? All applicants for this listing will also lose access.</div>
    <div class="modal-acts">
      <button type="button" onclick="closeDelete()" class="btn btn-secondary modal-btn modal-cancel">Cancel</button>
      <form id="deleteForm" method="POST" style="flex:1;">
        @csrf @method('DELETE')
        <button type="submit" class="btn btn-red modal-btn modal-red">🗑 Delete Permanently</button>
      </form>
    </div>
  </div>
</div>

<div id="toastWrap" class="toast-wrap"></div>

@endsection

@push('page_scripts')
<script>
(function () {
  'use strict';

  /* ── toast notifications ── */
  function toast(msg, type) {
    var icons = {
      success: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
      error:   '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
    };
    var t = document.createElement('div');
    t.className = 'toast toast-' + (type === 'success' ? 'ok' : 'err');
    t.innerHTML = (icons[type] || '') + '<span>' + msg + '</span><button class="toast-x" onclick="this.parentElement.remove()">✕</button>';
    document.getElementById('toastWrap').appendChild(t);
    setTimeout(function () {
      t.style.transition = 'opacity .3s,transform .3s';
      t.style.opacity = '0';
      t.style.transform = 'translateX(20px)';
      setTimeout(function () { t.remove(); }, 300);
    }, 4200);
  }
  @if(session('success')) setTimeout(function(){toast(@json(session('success')),'success');},200); @endif

  /* ── filter / sort / search ── */
  var rows         = Array.from(document.querySelectorAll('#tableBody tr[data-filter]'));
  var activeFilter = 'all';
  var searchQ      = '';
  var sortVal      = '';

  function applyFilters() {
    var sorted = rows.slice();
    var fn = {
      'date-desc':  function (a, b) { return new Date(b.dataset.date)  - new Date(a.dataset.date); },
      'date-asc':   function (a, b) { return new Date(a.dataset.date)  - new Date(b.dataset.date); },
      'az':         function (a, b) { return (a.dataset.title || '').localeCompare(b.dataset.title || ''); },
      'za':         function (a, b) { return (b.dataset.title || '').localeCompare(a.dataset.title || ''); },
      'views-desc': function (a, b) { return +b.dataset.views  - +a.dataset.views; },
      'apps-desc':  function (a, b) { return +b.dataset.apps   - +a.dataset.apps; },
      'vac-desc':   function (a, b) { return +b.dataset.vac    - +a.dataset.vac; },
    };
    if (fn[sortVal]) sorted.sort(fn[sortVal]);
    var tb = document.getElementById('tableBody');
    sorted.forEach(function (r) { tb.appendChild(r); });

    var visible = 0;
    rows.forEach(function (r) {
      var mf;
      if      (activeFilter === 'all')      mf = true;
      else if (activeFilter === 'remote')   mf = r.dataset.remote   === 'remote';
      else if (activeFilter === 'featured') mf = r.dataset.featured === 'featured';
      else                                  mf = r.dataset.filter   === activeFilter;

      var ms = !searchQ || (r.dataset.title || '').includes(searchQ);
      r.style.display = (mf && ms) ? '' : 'none';
      if (mf && ms) visible++;
    });
    document.getElementById('noResults').style.display = visible > 0 ? 'none' : 'block';
  }

  document.querySelectorAll('.ftab').forEach(function (tab) {
    tab.addEventListener('click', function () {
      document.querySelectorAll('.ftab').forEach(function (t) { t.classList.remove('on'); });
      this.classList.add('on');
      activeFilter = this.dataset.filter;
      applyFilters();
    });
  });

  window.setFilter = function (f) {
    activeFilter = f;
    document.querySelectorAll('.ftab').forEach(function (t) {
      t.classList.toggle('on', t.dataset.filter === f);
    });
    applyFilters();
  };

  var st;
  document.getElementById('searchInput').addEventListener('input', function () {
    clearTimeout(st);
    searchQ = this.value.toLowerCase().trim();
    st = setTimeout(applyFilters, 180);
  });

  document.getElementById('sortSelect').addEventListener('change', function () {
    sortVal = this.value;
    applyFilters();
  });

  /* ── delete modal ── */
  window.confirmDelete = function (id, title) {
    document.getElementById('deleteForm').action = '{{ route('admin.job_posts.destroy', ':id') }}'.replace(':id', id);
    document.getElementById('deleteJobTitle').textContent = '"' + title + '"';
    document.getElementById('deleteOverlay').classList.add('open');
  };
  window.closeDelete = function () { document.getElementById('deleteOverlay').classList.remove('open'); };
  document.getElementById('deleteOverlay').addEventListener('click', function (e) { if (e.target === this) closeDelete(); });
  document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeDelete(); });

}());
</script>
@endpush
