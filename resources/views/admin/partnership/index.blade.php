@push('page_css')
@vite('resources/css/admin/entries/misc.css')
@endpush

@extends('layouts.admin')

@section('page_title', 'Partnership Requests')
@section('page_subtitle', 'Review partnership inquiries')
@section('sidebar_partnerships', 'active')

@push('page_styles')
@vite('resources/css/admin/entries/partnership-index.css')
@endpush

@section('content')
{{-- delete single modal --}}
<div class="overlay" id="deleteOverlay" role="dialog" aria-modal="true">
  <div class="modal">
    <button type="button" class="modal-x" data-action="close-modal"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
    <div class="modal-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg></div>
    <h3>Delete Partnership?</h3>
    <p>This will permanently remove the request from <strong id="modalPartnerName"></strong>. This action cannot be undone.</p>
    <div class="modal-acts">
      <button class="btn btn-secondary modal-cancel" data-action="close-modal">Cancel</button>
      <button class="btn btn-red modal-del" data-action="confirm-delete">Yes, Delete</button>
    </div>
  </div>
</div>
<form id="deleteForm" method="POST" style="display:none;">@csrf @method('DELETE')</form>

{{-- delete bulk modal --}}
<div class="overlay" id="bulkDeleteOverlay" role="dialog" aria-modal="true">
  <div class="modal">
    <button type="button" class="modal-x" data-action="close-bulk-modal"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
    <div class="modal-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg></div>
    <h3>Delete Selected Requests?</h3>
    <p>This will permanently remove <strong id="bulkCountDisplay">0</strong> partnership request(s).</p>
    <div class="modal-acts">
      <button class="btn btn-secondary modal-cancel" data-action="close-bulk-modal">Cancel</button>
      <button class="btn btn-red modal-del" data-action="confirm-bulk-delete">Yes, Delete</button>
    </div>
  </div>
</div>

@if(session('success'))
<div class="alert-ok" id="flashAlert">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
  {{ session('success') }}
</div>
@endif

@php
  $allTotal    = \App\Models\Partnership::count();
  $allPending  = \App\Models\Partnership::where('status','pending')->count();
  $allApproved = \App\Models\Partnership::where('status','approved')->count();
  $allRejected = \App\Models\Partnership::where('status','rejected')->count();

  $sortUrl = function($column) use ($sort, $dir) {
      $newDir = $sort === $column && $dir === 'asc' ? 'desc' : 'asc';
      return request()->fullUrlWithQuery(['sort' => $column, 'direction' => $newDir]);
  };
@endphp

{{-- HERO --}}
<div class="hero">
  <div class="hero-left">
    <div class="hero-tag"><span class="hero-tag-dot"></span>Partnerships</div>
    <div class="hero-name">Partnership Requests</div>
    <div class="hero-sub">Review, approve or reject partnership inquiries submitted through the platform.</div>
    <div class="hero-badges">
      <span class="hero-badge hb-purple">{{ $allTotal }} total</span>
      <span class="hero-badge hb-amber">{{ $allPending }} pending</span>
      <span class="hero-badge hb-green">{{ $allApproved }} approved</span>
      <span class="hero-badge hb-red">{{ $allRejected }} rejected</span>
    </div>
  </div>
  <div class="hero-right">
    <a href="{{ route('admin.partnership.export', request()->only('search', 'status')) }}" class="hero-btn hero-btn-ghost">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
      Export CSV
    </a>
  </div>
</div>

{{-- STATS --}}
<div class="stats-grid">
  <div class="stat">
    <div class="stat-icon si-amber">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
    </div>
    <div class="stat-body">
      <div class="stat-lbl">Pending</div>
      <div class="stat-val sv-amber">{{ $allPending }}</div>
      <div class="stat-foot">Awaiting review</div>
    </div>
  </div>
  <div class="stat">
    <div class="stat-icon si-green">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    </div>
    <div class="stat-body">
      <div class="stat-lbl">Approved</div>
      <div class="stat-val sv-green">{{ $allApproved }}</div>
      <div class="stat-foot">Active partners</div>
    </div>
  </div>
  <div class="stat">
    <div class="stat-icon si-red">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    </div>
    <div class="stat-body">
      <div class="stat-lbl">Rejected</div>
      <div class="stat-val sv-red">{{ $allRejected }}</div>
      <div class="stat-foot">Declined requests</div>
    </div>
  </div>
  <div class="stat">
    <div class="stat-icon si-a">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
    </div>
    <div class="stat-body">
      <div class="stat-lbl">Total</div>
      <div class="stat-val sv-a">{{ $allTotal }}</div>
      <div class="stat-foot">All requests</div>
    </div>
  </div>
</div>

{{-- TOOLBAR --}}
<form id="filterForm" method="GET" action="{{ route('admin.partnership.index') }}" style="margin-bottom:0;">
  <div class="toolbar">
    <div class="toolbar-left">
      <div class="search-wrap">
        <svg class="si" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
        <input type="text" class="search-input" name="search" value="{{ $search }}" placeholder="Search partnerships…">
      </div>
      <div class="select-wrap">
        <svg class="si" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
        <select class="filter-select" name="status">
          <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All Status</option>
          <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
          <option value="approved" {{ $status === 'approved' ? 'selected' : '' }}>Approved</option>
          <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Rejected</option>
        </select>
      </div>
      <input type="hidden" name="sort" value="{{ $sort }}">
      <input type="hidden" name="direction" value="{{ $dir }}">
    </div>
  </div>
</form>

<div class="main-card">
  <div class="card-head">
    <div class="card-head-left">
      <div class="card-head-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg></div>
      <span class="card-head-title">All Partnership Requests</span>
    </div>
    <span class="card-head-count" id="visibleCount">{{ $partnerships->total() }} total</span>
  </div>

  {{-- BULK BAR --}}
  <div class="bulk-bar" id="bulkBar">
    <span><strong class="bulk-count" id="bulkCount">0</strong> selected</span>
    <div class="bulk-acts">
      <button type="button" class="btn btn-green bulk-btn bulk-approve" data-action="bulk-action" data-status="approved">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4"/></svg>Approve
      </button>
      <button type="button" class="btn btn-red bulk-btn bulk-reject" data-action="bulk-action" data-status="rejected">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>Reject
      </button>
      <button type="button" class="btn btn-yellow bulk-btn bulk-pending" data-action="bulk-action" data-status="pending">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>Pending
      </button>
      <button type="button" class="btn btn-red bulk-btn bulk-del" data-action="open-bulk-delete">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/></svg>Delete
      </button>
      <button type="button" class="btn btn-secondary bulk-btn bulk-cancel" data-action="clear-checkboxes">Cancel</button>
    </div>
  </div>

  <form id="bulkForm" method="POST" style="display:none;" data-update-url="{{ route('admin.partnership.bulk-update') }}" data-delete-url="{{ route('admin.partnership.bulk-delete') }}">@csrf</form>

  @if($partnerships->isEmpty())
  <div class="empty-state">
    <div class="empty-icon-wrap">🤝</div>
    <h3>No partnership requests</h3>
    @if($search || $status !== 'all')
      <p>Try adjusting your search or filters</p>
    @else
      <p>Incoming applications will appear here</p>
    @endif
  </div>
  @else
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th class="td-check"><input type="checkbox" id="selectAll" data-action="toggle-all"></th>
          <th style="width:50px;">#</th>
          <th>
            <a href="{{ $sortUrl('name') }}" class="sort-link">
              Applicant
              <svg class="sort-ico {{ $sort==='name' ? ($dir==='asc'?'asc':'desc') : '' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
            </a>
          </th>
          <th>Org</th>
          <th>
            <a href="{{ $sortUrl('partnership_type') }}" class="sort-link">
              Type
              <svg class="sort-ico {{ $sort==='partnership_type' ? ($dir==='asc'?'asc':'desc') : '' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
            </a>
          </th>
          <th>Location</th>
          <th>Timeline</th>
          <th>
            <a href="{{ $sortUrl('priority_score') }}" class="sort-link">
              Priority
              <svg class="sort-ico {{ $sort==='priority_score' ? ($dir==='asc'?'asc':'desc') : '' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
            </a>
          </th>
          <th>
            <a href="{{ $sortUrl('status') }}" class="sort-link">
              Status
              <svg class="sort-ico {{ $sort==='status' ? ($dir==='asc'?'asc':'desc') : '' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
            </a>
          </th>
          <th>Reviewed By</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($partnerships as $p)
        @php
          $score  = $p->priority_score ?? 0;
          $priCls = $score >= 30 ? 'pri-high' : ($score >= 10 ? 'pri-medium' : 'pri-low');
          $priLbl = $score >= 30 ? 'High'     : ($score >= 10 ? 'Medium'     : 'Low');
          $init   = strtoupper(substr($p->name ?? 'A', 0, 1));
        @endphp
        <tr>
          <td class="td-check"><input type="checkbox" class="row-check" value="{{ $p->id }}" data-action="update-bulk-bar"></td>
          <td><span class="serial">{{ $partnerships->firstItem() + $loop->index }}</span></td>
          <td>
            <div class="person-cell">
              <div class="person-av">{{ $init }}</div>
              <div>
                <div class="person-name">{{ $p->name }}</div>
                <div class="person-email">{{ $p->email }}</div>
              </div>
            </div>
          </td>
          <td><span class="org-cell">{{ $p->organization_name ?? '—' }}</span></td>
          <td><span class="type-cell">{{ ucfirst($p->partnership_type ?? '—') }}</span></td>
          <td>
            @if($p->location)
              <span class="mono-pill">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                {{ $p->location }}
              </span>
            @else <span style="color:var(--text3);font-size:12px;">—</span>
            @endif
          </td>
          <td><span class="mono-pill">{{ ucfirst(str_replace('_',' ',$p->timeline ?? '—')) }}</span></td>
          <td>
            <span class="pri-pill {{ $priCls }}">
              <span class="status-dot"></span>{{ $priLbl }}
            </span>
            <span class="score-num">{{ $score }}</span>
          </td>
          <td>
            @if($p->status === 'pending')
              <span class="status-pill s-pending"><span class="status-dot"></span> Pending</span>
            @elseif($p->status === 'approved')
              <span class="status-pill s-approved"><span class="status-dot"></span> Approved</span>
            @else
              <span class="status-pill s-rejected"><span class="status-dot"></span> Rejected</span>
            @endif
          </td>
          <td>
            @if($p->reviewer)
              <span style="font-weight:600;color:var(--text);font-size:12px;">{{ $p->reviewer->name }}</span>
            @else
              <span style="font-size:11px;color:var(--text3);font-family:var(--mono);">—</span>
            @endif
          </td>
          <td>
            <div class="actions">
              <a href="{{ route('admin.partnership.show', $p->id) }}" class="btn btn-secondary act-btn ab-view">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                View
              </a>
              <button type="button" class="btn btn-red act-btn act-del"
                data-action="open-modal" data-id="{{ $p->id }}" data-name="{{ addslashes($p->name) }}" data-url="{{ route('admin.partnership.delete', $p->id) }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/></svg>Delete
              </button>
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="11">
          <div class="empty-state"><div class="empty-icon-wrap">🤝</div><h3>No partnership requests found</h3><p>Try adjusting your search or filters</p></div>
        </td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if($partnerships instanceof \Illuminate\Pagination\LengthAwarePaginator && $partnerships->hasPages())
  <div class="pagination-wrap">
    <span class="page-info">Showing {{ $partnerships->firstItem() }}–{{ $partnerships->lastItem() }} of {{ $partnerships->total() }}</span>
    <div class="page-btns">
      @if($partnerships->onFirstPage())
        <span class="page-btn" style="opacity:.4;cursor:not-allowed;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg></span>
      @else
        <a href="{{ $partnerships->appends(request()->query())->previousPageUrl() }}" class="page-btn"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg></a>
      @endif
      @foreach($partnerships->getUrlRange(1,$partnerships->lastPage()) as $page=>$url)
        <a href="{{ $partnerships->appends(request()->query())->url($page) }}" class="page-btn {{ $partnerships->currentPage()==$page?'cur':'' }}">{{ $page }}</a>
      @endforeach
      @if($partnerships->hasMorePages())
        <a href="{{ $partnerships->appends(request()->query())->nextPageUrl() }}" class="page-btn"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg></a>
      @else
        <span class="page-btn" style="opacity:.4;cursor:not-allowed;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg></span>
      @endif
    </div>
  </div>
  @endif
  @endif
</div>
@endsection

@push('page_scripts')
@vite('resources/js/admin/partnership-index.js')
@endpush
