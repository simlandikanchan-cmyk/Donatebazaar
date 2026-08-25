@push('page_css')
@vite('resources/css/admin/entries/campaigns.css')
@endpush

@extends('layouts.admin')

@section('sidebar_campaigns', 'active')

@section('page_title', 'All Campaigns')
@section('page_subtitle', 'Manage and review fundraiser campaigns')

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
      <select class="ftab-select" data-action="tab-select">
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
                 value="{{ $search }}" data-action="auto-submit">
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
      <button type="button" class="btn btn-green cp-bulk-btn cp-bulk-approve" data-action="bulk-action" data-url="{{ route('admin.campaigns.bulk-approve') }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4"/></svg>
        Approve
      </button>
      <button type="button" class="btn btn-red cp-bulk-btn cp-bulk-reject" data-action="bulk-action" data-url="{{ route('admin.campaigns.bulk-reject') }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
        Reject
      </button>
      <button type="button" class="btn btn-secondary cp-bulk-btn cp-bulk-pause" data-action="bulk-action" data-url="{{ route('admin.campaigns.bulk-pause') }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 9v6m4-6v6"/></svg>
        Pause
      </button>
      <button type="button" class="btn btn-secondary cp-bulk-btn cp-bulk-clear" data-action="clear-selection">Clear Selection</button>
    </div>
  </div>

  @if($campaigns->count() > 0)
  <div class="table-wrap">
    <table class="p-table" id="campaignsTable">
      <thead>
        <tr>
          <th style="width:36px;">
            <input type="checkbox" id="selectAll" data-action="toggle-all" style="cursor:pointer;">
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
                   data-action="update-bulk" style="cursor:pointer;">
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
               <a href="{{ route('admin.campaign.show', $campaign->id) }}" class="btn btn-secondary act-btn ab-view">
                 <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                 View
               </a>
               <a href="{{ route('admin.campaign.edit', $campaign->id) }}" class="btn btn-secondary act-btn act-edit"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>Edit</a>
               <form action="{{ route('admin.campaign.destroy', $campaign->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this campaign? This cannot be undone.');">
                 @csrf @method('DELETE')
                 <button type="submit" class="btn btn-red act-btn ab-delete" title="Delete">
                   <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/></svg>Delete
                 </button>
               </form>
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
@vite('resources/js/admin/entries/campaign-index.js')
@endpush

@push('page_styles')
@vite('resources/css/admin/pages/campaign-index.css')
<style>
@media(max-width:960px){
  .stats-grid{grid-template-columns:repeat(2,1fr)!important}
}
@media(max-width:640px){
  .stats-grid{grid-template-columns:1fr!important}
  .table-wrap{overflow-x:auto}
  .p-table{min-width:720px}
  .toolbar{flex-wrap:wrap}
  .toolbar-left{flex-wrap:wrap;width:100%}
  .search-wrap{width:100%}
  .search-input{width:100%}
  .cmp-actions{flex-wrap:wrap;gap:4px}
  .cmp-actions .btn{width:100%;justify-content:center}
}
@media(max-width:480px){
  .stats-grid{grid-template-columns:1fr!important}
}
</style>
@endpush
