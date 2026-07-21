@extends('layouts.admin')

@section('sidebar_campaigns', 'active')

@section('page_title', 'All Campaigns')
@section('page_subtitle', 'Manage and review fundraiser campaigns')

@push('page_styles')
<style>
/* ── Toolbar / filters (shared admin design system) ── */
.toolbar{display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;}
.toolbar-left{display:flex;align-items:center;gap:8px;flex-wrap:wrap;}
.search-wrap{position:relative;}
.search-wrap .si{position:absolute;left:11px;top:50%;transform:translateY(-50%);width:14px;height:14px;color:var(--text3);pointer-events:none;}
.search-input{width:240px;height:38px;padding:0 12px 0 34px;background:var(--surface);border:1px solid var(--border2);border-radius:var(--r-sm);font-size:12.5px;color:var(--text);font-family:var(--font);outline:none;transition:border-color var(--ease),box-shadow var(--ease),width .3s ease;}
.search-input::placeholder{color:var(--text3);}
.search-input:focus{border-color:var(--a);box-shadow:0 0 0 3px var(--a-glow);width:280px;}
.select-wrap{position:relative;}
.select-wrap .si{position:absolute;left:11px;top:50%;transform:translateY(-50%);width:14px;height:14px;color:var(--text3);pointer-events:none;z-index:1;}
.filter-select{height:38px;padding:0 30px 0 34px;background:var(--surface);border:1px solid var(--border2);border-radius:var(--r-sm);font-size:12.5px;color:var(--text2);font-family:var(--font);outline:none;cursor:pointer;transition:all var(--ease);appearance:none;-webkit-appearance:none;-moz-appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%239096b4' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 9px center;background-size:13px;}
.filter-select:hover,.filter-select:focus{border-color:var(--a);color:var(--a);background-color:var(--a-lt);box-shadow:0 0 0 3px var(--a-glow);}
.card-head-right{display:flex;align-items:center;gap:8px;}
.card-head-count{font-size:10.5px;color:var(--text3);font-family:var(--mono);}

/* ── Bulk action bar ── */
.cp-bulkbar{display:none;align-items:center;gap:12px;flex-wrap:wrap;padding:12px 20px;background:var(--a-lt);border-bottom:1px solid var(--a);}
.cp-bulkbar strong{color:var(--a);font-family:var(--mono);}
.cp-bulk-acts{display:flex;align-items:center;gap:8px;flex-wrap:wrap;}
.cp-bulk-btn{display:inline-flex;align-items:center;gap:5px;height:34px;padding:0 14px;border-radius:var(--r-sm);font-size:12px;font-weight:600;border:1px solid transparent;cursor:pointer;font-family:var(--font);transition:all var(--ease);}
.cp-bulk-approve{background:var(--green);color:#fff;}
.cp-bulk-approve:hover{filter:brightness(1.05);}
.cp-bulk-reject{background:var(--red);color:#fff;}
.cp-bulk-reject:hover{filter:brightness(1.05);}
.cp-bulk-pause{background:var(--surface);border-color:var(--border2);color:var(--text2);}
.cp-bulk-pause:hover{border-color:var(--a);color:var(--a);background:var(--a-lt);}
.cp-bulk-clear{background:var(--surface);border-color:var(--border2);color:var(--text3);}
.cp-bulk-clear:hover{color:var(--text2);border-color:var(--text3);}

/* ── Table ── */
.cmp-title{font-weight:600;color:var(--text);font-size:13px;display:block;max-width:240px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
.cmp-sub{font-size:10.5px;color:var(--text3);margin-top:2px;font-family:var(--mono);}
.cmp-owner{font-size:12.5px;color:var(--text);font-weight:500;}
.cmp-owner-email{font-size:10.5px;color:var(--text3);font-family:var(--mono);}
.cmp-mono{font-family:var(--mono);font-size:12.5px;font-weight:600;color:var(--text);}
.cmp-raised{color:var(--green);}

/* progress */
.cmp-progress{margin-top:6px;width:140px;max-width:100%;height:6px;border-radius:100px;background:var(--surface2);overflow:hidden;}
.cmp-progress > span{display:block;height:100%;border-radius:100px;background:linear-gradient(90deg,var(--green),#34d399);}
.cmp-pct{font-size:10.5px;color:var(--text3);margin-top:3px;font-family:var(--mono);}

.cmp-actions{display:flex;align-items:center;gap:6px;flex-wrap:nowrap;white-space:nowrap;}
.cmp-view,.cmp-edit{display:inline-flex;align-items:center;gap:5px;height:32px;padding:0 12px;border-radius:var(--r-sm);font-size:12px;font-weight:500;text-decoration:none;transition:all var(--ease);}
.cmp-view{background:var(--surface);border:1px solid var(--border2);color:var(--text2);}
.cmp-view:hover{border-color:var(--a);color:var(--a);background:var(--a-lt);}
.cmp-view svg{width:13px;height:13px;}
.cmp-edit{background:var(--a-lt);color:var(--a);border:1px solid rgba(37,99,235,.2);}
.cmp-edit:hover{filter:brightness(.97);}
.cmp-edit svg{width:13px;height:13px;}

.sort-link{color:var(--text);text-decoration:none;display:inline-flex;align-items:center;gap:4px;}
.sort-link:hover{color:var(--a);}
.sort-ico{width:11px;height:11px;}

.empty-state{padding:56px 20px;text-align:center;}
.empty-state svg{width:40px;height:40px;color:var(--text3);opacity:.2;margin:0 auto 12px;display:block;}
.empty-state strong{display:block;font-family:var(--mono);font-size:15px;font-weight:700;color:var(--text2);margin-bottom:5px;}
.empty-state p{font-size:13px;color:var(--text3);}

@media(max-width:860px){
  .search-input{width:100%;}.search-input:focus{width:100%;}
  .search-wrap{flex:1;min-width:180px;}
}

/* ── Shared bits not in global admin.css ── */
.alert-ok{background:rgba(16,185,129,.09);border:1px solid rgba(16,185,129,.25);color:#065f46;padding:11px 14px;border-radius:10px;font-size:13px;font-weight:500;margin-bottom:16px;display:flex;align-items:center;gap:8px;animation:fadeUp .3s ease;}
.alert-ok svg{width:16px;height:16px;flex-shrink:0;}
.alert-error{background:rgba(239,68,68,.09);border:1px solid rgba(239,68,68,.25);color:#dc2626;padding:11px 14px;border-radius:10px;font-size:13px;font-weight:500;margin-bottom:16px;display:flex;align-items:center;gap:8px;animation:fadeUp .3s ease;}
.alert-error svg{width:16px;height:16px;flex-shrink:0;}
.clear-btn{display:inline-flex;align-items:center;gap:5px;height:38px;padding:0 13px;background:var(--surface);border:1px solid var(--border2);border-radius:var(--r-sm);font-size:12px;color:var(--text3);text-decoration:none;transition:all var(--ease);}
.clear-btn:hover{border-color:var(--red);color:var(--red);background:var(--red-lt);}
.badge-dot{width:5px;height:5px;border-radius:50%;background:currentColor;flex-shrink:0;}
.table-wrap{overflow-x:auto;}
.pagination-wrap{display:flex;align-items:center;justify-content:space-between;padding:14px 20px;border-top:1px solid var(--border);flex-wrap:wrap;gap:10px;background:var(--surface2);}
.pagination-info{font-size:12px;color:var(--text3);}
.pagination-links{display:flex;gap:4px;flex-wrap:wrap;}
.pagination-links a,.pagination-links span{display:inline-flex;align-items:center;justify-content:center;min-width:30px;height:30px;padding:0 9px;border-radius:6px;font-size:12px;font-weight:600;border:1px solid var(--border2);background:var(--surface);color:var(--text2);transition:all var(--ease);}
.pagination-links a:hover{background:var(--a-lt);color:var(--a);border-color:var(--a);}
.pagination-links .active{background:var(--a);color:#fff;border-color:var(--a);}
.pagination-links .disabled{color:var(--text3);opacity:.5;cursor:default;}
.si-paused{background:rgba(107,114,128,.12);color:#6b7280;}
.sv-paused{color:#6b7280;}
.sv-red{color:var(--red);}

/* 6 stat cards across */
.stats-grid{grid-template-columns:repeat(6,1fr);gap:12px;}
@media(max-width:1200px){.stats-grid{grid-template-columns:repeat(3,1fr);}}
@media(max-width:860px){.stats-grid{grid-template-columns:repeat(2,1fr);}}
@media(max-width:520px){.stats-grid{grid-template-columns:1fr;}}

@media(max-width:380px){
  .stats-grid{gap:8px;}
  .stat{border-radius:10px;padding:12px 10px;}
  .stat-icon{width:32px;height:32px;}
  .stat-icon svg{width:14px;height:14px;}
  .stat-value{font-size:clamp(16px,4.5vw,18px);}
  .stat-label{font-size:9px;}
  .table td,.table th{padding:7px 5px;font-size:10px;}
  .table .col-campaign,.table .col-raised{display:none;}
  .pagination-wrap{flex-direction:column;gap:8px;}
  .search-input{font-size:11px;height:34px;padding:0 10px;}
  .search-wrap{min-width:0;}
}
</style>
@endpush

@section('content')

@php
$stateColors = [
    'active'    => ['class' => 'b-active',    'label' => 'Active'],
    'pending'   => ['class' => 'b-pending',   'label' => 'Pending'],
    'paused'    => ['class' => 'b-paused',    'label' => 'Paused'],
    'rejected'  => ['class' => 'b-rejected',  'label' => 'Rejected'],
    'expired'   => ['class' => 'b-expired',   'label' => 'Expired'],
    'completed' => ['class' => 'b-completed', 'label' => 'Completed'],
];
$sortUrl = function($column) use ($sort, $dir) {
    $newDir = $sort === $column && $dir === 'asc' ? 'desc' : 'asc';
    return request()->fullUrlWithQuery(['sort' => $column, 'direction' => $newDir]);
};
@endphp

@if(session('success'))
<div class="alert-ok">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
  {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="alert-error">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
  {{ session('error') }}
</div>
@endif

{{-- STATS --}}
<div class="stats-grid">
  <div class="stat">
    <div class="stat-icon si-green">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
    </div>
    <div class="stat-body">
      <div class="stat-lbl">Active</div>
      <div class="stat-val sv-green">{{ $cntActive }}</div>
      <div class="stat-foot">Live campaigns</div>
    </div>
  </div>
  <div class="stat">
    <div class="stat-icon si-amber">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
    </div>
    <div class="stat-body">
      <div class="stat-lbl">Pending</div>
      <div class="stat-val sv-amber">{{ $cntPending }}</div>
      <div class="stat-foot">Awaiting review</div>
    </div>
  </div>
  <div class="stat">
    <div class="stat-icon si-a">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    </div>
    <div class="stat-body">
      <div class="stat-lbl">Paused</div>
      <div class="stat-val sv-a">{{ $cntPaused }}</div>
      <div class="stat-foot">Temporarily halted</div>
    </div>
  </div>
  <div class="stat">
    <div class="stat-icon si-red">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    </div>
    <div class="stat-body">
      <div class="stat-lbl">Rejected</div>
      <div class="stat-val sv-red">{{ $cntRejected }}</div>
      <div class="stat-foot">Declined</div>
    </div>
  </div>
  <div class="stat">
    <div class="stat-icon si-paused">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    </div>
    <div class="stat-body">
      <div class="stat-lbl">Expired</div>
      <div class="stat-val sv-paused">{{ $cntExpired }}</div>
      <div class="stat-foot">Ended / lapsed</div>
    </div>
  </div>
  <div class="stat">
    <div class="stat-icon si-blue">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    </div>
    <div class="stat-body">
      <div class="stat-lbl">Completed</div>
      <div class="stat-val sv-blue">{{ $cntCompleted }}</div>
      <div class="stat-foot">Goal reached</div>
    </div>
  </div>
</div>

<div class="main-card">
  <div class="card-head">
    <div class="card-head-left">
      <div class="ftabs">
        <a href="{{ route('admin.campaign.index', array_merge(request()->except(['status','page']), ['status' => 'all'])) }}"
           class="ftab {{ $status === 'all' ? 'on' : '' }}">All <span class="cnt">{{ $campaigns->total() }}</span></a>
        <a href="{{ route('admin.campaign.index', array_merge(request()->except(['status','page']), ['status' => 'active'])) }}"
           class="ftab {{ $status === 'active' ? 'on' : '' }}">Active <span class="cnt">{{ $cntActive }}</span></a>
        <a href="{{ route('admin.campaign.index', array_merge(request()->except(['status','page']), ['status' => 'pending'])) }}"
           class="ftab {{ $status === 'pending' ? 'on' : '' }}">Pending <span class="cnt">{{ $cntPending }}</span></a>
        <a href="{{ route('admin.campaign.index', array_merge(request()->except(['status','page']), ['status' => 'paused'])) }}"
           class="ftab {{ $status === 'paused' ? 'on' : '' }}">Paused <span class="cnt">{{ $cntPaused }}</span></a>
        <a href="{{ route('admin.campaign.index', array_merge(request()->except(['status','page']), ['status' => 'rejected'])) }}"
           class="ftab {{ $status === 'rejected' ? 'on' : '' }}">Rejected <span class="cnt">{{ $cntRejected }}</span></a>
        <a href="{{ route('admin.campaign.index', array_merge(request()->except(['status','page']), ['status' => 'expired'])) }}"
           class="ftab {{ $status === 'expired' ? 'on' : '' }}">Expired <span class="cnt">{{ $cntExpired }}</span></a>
        <a href="{{ route('admin.campaign.index', array_merge(request()->except(['status','page']), ['status' => 'completed'])) }}"
           class="ftab {{ $status === 'completed' ? 'on' : '' }}">Completed <span class="cnt">{{ $cntCompleted }}</span></a>
      </div>
      <select class="ftab-select" onchange="window.location.href=this.value">
        <option value="{{ route('admin.campaign.index', array_merge(request()->except(['status','page']), ['status' => 'all'])) }}" {{ $status === 'all' ? 'selected' : '' }}>All ({{ $campaigns->total() }})</option>
        <option value="{{ route('admin.campaign.index', array_merge(request()->except(['status','page']), ['status' => 'active'])) }}" {{ $status === 'active' ? 'selected' : '' }}>Active ({{ $cntActive }})</option>
        <option value="{{ route('admin.campaign.index', array_merge(request()->except(['status','page']), ['status' => 'pending'])) }}" {{ $status === 'pending' ? 'selected' : '' }}>Pending ({{ $cntPending }})</option>
        <option value="{{ route('admin.campaign.index', array_merge(request()->except(['status','page']), ['status' => 'paused'])) }}" {{ $status === 'paused' ? 'selected' : '' }}>Paused ({{ $cntPaused }})</option>
        <option value="{{ route('admin.campaign.index', array_merge(request()->except(['status','page']), ['status' => 'rejected'])) }}" {{ $status === 'rejected' ? 'selected' : '' }}>Rejected ({{ $cntRejected }})</option>
        <option value="{{ route('admin.campaign.index', array_merge(request()->except(['status','page']), ['status' => 'expired'])) }}" {{ $status === 'expired' ? 'selected' : '' }}>Expired ({{ $cntExpired }})</option>
        <option value="{{ route('admin.campaign.index', array_merge(request()->except(['status','page']), ['status' => 'completed'])) }}" {{ $status === 'completed' ? 'selected' : '' }}>Completed ({{ $cntCompleted }})</option>
      </select>
    </div>
  </div>

  <div style="padding:16px 20px;border-bottom:1px solid var(--border);background:var(--surface2);">
    <form method="GET" action="{{ route('admin.campaign.index') }}" id="filterForm" class="toolbar">
      <div class="toolbar-left">
        <input type="hidden" name="status" value="{{ $status }}">
        <div class="search-wrap">
          <svg class="si" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
          <input type="text" name="search" class="search-input" placeholder="Search campaign or owner…"
                 value="{{ $search }}" oninput="autoSubmit()">
        </div>
        @if($search)
          <a href="{{ route('admin.campaign.index', ['status' => $status]) }}" class="clear-btn">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:13px;height:13px;"><path stroke-linecap="round" stroke-linejoin="round" d="M18 6L6 18M6 6l12 12"/></svg>
            Clear
          </a>
        @endif
        <input type="hidden" name="sort" value="{{ $sort }}">
        <input type="hidden" name="direction" value="{{ $dir }}">
      </div>
    </form>
  </div>

  {{-- BULK BAR --}}
  <div id="bulkBar" class="cp-bulkbar">
    <span><strong id="bulkCount">0</strong> campaign(s) selected</span>
    <div class="cp-bulk-acts">
      <button type="button" class="btn btn-green cp-bulk-btn cp-bulk-approve" onclick="bulkAction('{{ route('admin.campaigns.bulk-approve') }}')">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4"/></svg>
        Approve
      </button>
      <button type="button" class="btn btn-red cp-bulk-btn cp-bulk-reject" onclick="bulkAction('{{ route('admin.campaigns.bulk-reject') }}')">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
        Reject
      </button>
      <button type="button" class="btn btn-secondary cp-bulk-btn cp-bulk-pause" onclick="bulkAction('{{ route('admin.campaigns.bulk-pause') }}')">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 9v6m4-6v6"/></svg>
        Pause
      </button>
      <button type="button" class="btn btn-secondary cp-bulk-btn cp-bulk-clear" onclick="clearSelection()">Clear Selection</button>
    </div>
  </div>

  @if($campaigns->count() > 0)
  <div class="table-wrap">
    <table class="p-table" id="campaignsTable">
      <thead>
        <tr>
          <th style="width:36px;">
            <input type="checkbox" id="selectAll" onchange="toggleAllCheckboxes()" style="cursor:pointer;">
          </th>
          <th>
            <a href="{{ $sortUrl('title') }}" class="sort-link">
              Campaign
              @if($sort === 'title')<svg class="sort-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="{{ $dir === 'asc' ? 'M12 5l7 7H5l7-7z' : 'M12 19l7-7H5l7 7z' }}"/></svg>@endif
            </a>
          </th>
          <th>Owner</th>
          <th>Category</th>
          <th>
            <a href="{{ $sortUrl('goal_amount') }}" class="sort-link">
              Goal
              @if($sort === 'goal_amount')<svg class="sort-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="{{ $dir === 'asc' ? 'M12 5l7 7H5l7-7z' : 'M12 19l7-7H5l7 7z' }}"/></svg>@endif
            </a>
          </th>
          <th>
            <a href="{{ $sortUrl('raised_amount') }}" class="sort-link">
              Raised
              @if($sort === 'raised_amount')<svg class="sort-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="{{ $dir === 'asc' ? 'M12 5l7 7H5l7-7z' : 'M12 19l7-7H5l7 7z' }}"/></svg>@endif
            </a>
          </th>
          <th>Status</th>
          <th>
            <a href="{{ $sortUrl('created_at') }}" class="sort-link">
              Created
              @if($sort === 'created_at')<svg class="sort-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="{{ $dir === 'asc' ? 'M12 5l7 7H5l7-7z' : 'M12 19l7-7H5l7 7z' }}"/></svg>@endif
            </a>
          </th>
          <th style="width:200px;">Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($campaigns as $campaign)
        <tr>
          <td>
            <input type="checkbox" class="cmp-checkbox" value="{{ $campaign->id }}"
                   onchange="updateBulkBar()" style="cursor:pointer;">
          </td>
          <td>
            <span class="cmp-title" title="{{ $campaign->title }}">{{ Str::limit($campaign->title, 42) }}</span>
            <span class="cmp-sub">ID #{{ $campaign->id }}</span>
          </td>
          <td>
            <div class="cmp-owner">{{ $campaign->user?->name ?? '—' }}</div>
            <div class="cmp-owner-email">{{ $campaign->user?->email ?? '' }}</div>
          </td>
          <td>
            <span class="cmp-sub" style="font-size:11px;color:var(--text);font-weight:500;">{{ $campaign->category?->name ?? '—' }}</span>
          </td>
          <td><span class="cmp-mono">₹{{ number_format($campaign->goal_amount) }}</span></td>
          <td>
            <span class="cmp-mono cmp-raised">₹{{ number_format($campaign->raised_amount) }}</span>
            @php
              $goal = (float) $campaign->goal_amount;
              $raised = (float) $campaign->raised_amount;
              $pct = $goal > 0 ? min(100, round(($raised / $goal) * 100)) : 0;
            @endphp
            <div class="cmp-progress"><span style="width:{{ $pct }}%"></span></div>
            <div class="cmp-pct">{{ $pct }}% of goal</div>
          </td>
          <td>
            @php $s = $stateColors[$campaign->campaign_state] ?? ['class' => 'b-pending', 'label' => 'Unknown']; @endphp
            <span class="badge {{ $s['class'] }}">
              <span class="badge-dot"></span>
              {{ $s['label'] }}
            </span>
          </td>
          <td>
            <span class="cmp-sub">{{ $campaign->created_at->format('d M Y') }}</span>
          </td>
          <td>
            <div class="cmp-actions">
              <a href="{{ route('admin.campaign.show', $campaign->id) }}" class="cmp-view">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                View
              </a>
              <a href="{{ route('admin.campaign.edit', $campaign->id) }}" class="cmp-edit">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit
              </a>
            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <div class="pagination-wrap" style="margin-top:0;">
    <div class="pagination-info">
      Showing {{ $campaigns->firstItem() }}–{{ $campaigns->lastItem() }} of {{ $campaigns->total() }}
    </div>
    {{ $campaigns->onEachSide(1)->links('vendor.pagination.admin') }}
  </div>
  @else
  <div class="empty-state">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
    <strong>No campaigns found</strong>
    <p>There are no {{ $status !== 'all' ? $status : '' }} campaigns to display.</p>
  </div>
  @endif
</div>

<form id="bulkForm" method="POST" style="display:none;">
  @csrf
  <div id="bulkIds"></div>
</form>

@endsection

@push('page_scripts')
<script>
function toggleAllCheckboxes() {
  var checked = document.getElementById('selectAll').checked;
  document.querySelectorAll('.cmp-checkbox').forEach(function(cb) { cb.checked = checked; });
  updateBulkBar();
}

function updateBulkBar() {
  var checked = document.querySelectorAll('.cmp-checkbox:checked');
  var bar = document.getElementById('bulkBar');
  if (checked.length > 0) {
    bar.style.display = 'flex';
    document.getElementById('bulkCount').textContent = checked.length;
  } else {
    bar.style.display = 'none';
  }
}

function clearSelection() {
  document.querySelectorAll('.cmp-checkbox').forEach(function(cb) { cb.checked = false; });
  document.getElementById('selectAll').checked = false;
  updateBulkBar();
}

function bulkAction(url) {
  var checked = document.querySelectorAll('.cmp-checkbox:checked');
  if (checked.length === 0) return;

  var ids = [];
  checked.forEach(function(cb) { ids.push(cb.value); });

  var container = document.getElementById('bulkIds');
  container.innerHTML = '';
  ids.forEach(function(id) {
    var input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'ids[]';
    input.value = id;
    container.appendChild(input);
  });

  if (confirm('Apply action to ' + ids.length + ' campaign(s)?')) {
    document.getElementById('bulkForm').action = url;
    document.getElementById('bulkForm').submit();
  }
}

let _t;
function autoSubmit(){clearTimeout(_t);_t=setTimeout(()=>document.getElementById('filterForm').submit(),400);}
</script>
@endpush
