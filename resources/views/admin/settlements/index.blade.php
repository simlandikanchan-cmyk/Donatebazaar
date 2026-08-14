@push('page_css')
@vite('resources/css/admin/entries/finance.css')
@endpush

@extends('layouts.admin')

@section('sidebar_settlements', 'active')
@section('page_title', 'Settlements')
@section('page_subtitle', 'Payout requests from organizations')

@push('page_styles')
<style>
/* ── settlement-specific status badges / cells (view-scoped, matches admin.css tokens) ── */
.st-badge{display:inline-flex;align-items:center;gap:5px;padding:3px 10px;border-radius:100px;font-size:10.5px;font-weight:700;font-family:var(--mono);white-space:nowrap;border:1px solid transparent}
.st-badge::before{content:'';width:5px;height:5px;border-radius:50%;background:currentColor;flex-shrink:0}
.st-pending{background:var(--amber-lt);color:var(--amber);border-color:rgba(245,158,11,.25)}
.st-approved{background:var(--a-lt);color:var(--a);border-color:rgba(37,99,235,.22)}
.st-processing{background:rgba(59,130,246,.12);color:var(--blue);border-color:rgba(59,130,246,.25)}
.st-paid{background:rgba(5,196,138,.12);color:#059c7f;border-color:rgba(5,196,138,.25)}
.st-rejected{background:rgba(240,68,68,.12);color:var(--red);border-color:rgba(240,68,68,.25)}
.st-failed{background:rgba(240,68,68,.12);color:var(--red);border-color:rgba(240,68,68,.25)}
.st-org{font-size:13px;font-weight:600;color:var(--text);line-height:1.3}
.st-org-sub{font-size:10px;color:var(--text3);font-family:var(--mono);margin-top:1px}
.st-amount{font-family:var(--mono);font-size:13px;font-weight:700;color:var(--text)}
.st-date{font-size:12px;font-weight:500;color:var(--text);white-space:nowrap}
.st-date-sub{font-size:10px;color:var(--text3);font-family:var(--mono);margin-top:1px}
@media(max-width:960px){.st-stats-grid{grid-template-columns:repeat(3,1fr)!important}}
@media(max-width:640px){.st-stats-grid{grid-template-columns:repeat(2,1fr)!important;gap:12px}}
@media(max-width:480px){.st-stats-grid{grid-template-columns:1fr!important}}
@media(max-width:600px){
  #settlementTable thead{display:none}
  #settlementTable tbody tr:not(.empty-row){display:flex;flex-direction:column;padding:14px 16px;border-bottom:1px solid var(--border);gap:8px}
  #settlementTable tbody tr td{padding:0;border:none;display:flex;align-items:center;gap:8px}
  #settlementTable tbody tr td::before{content:attr(data-label);font-size:10px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.06em;font-family:var(--mono);min-width:80px;flex-shrink:0}
  #settlementTable tbody tr td.cell-id::before{content:"#"}
  #settlementTable td[data-label="Actions"]{justify-content:flex-start}
  #settlementTable td[data-label="Actions"]::before{content:"Actions";min-width:auto;margin-right:auto}
  #settlementTable tbody tr td.cell-id{font-size:10px;color:var(--text3);margin-bottom:0}
}
</style>
@endpush

@section('content')

<div class="hero">
  <div class="hero-left">
    <div class="hero-tag"><span class="hero-tag-dot"></span>Finance</div>
    <div class="hero-name">Settlements</div>
    <div class="hero-sub">Review and process organization payout requests. Approving locks the funds and starts the payout.</div>
    <div class="hero-badges">
      <span class="hero-badge hb-teal">{{ $counts['total'] }} total</span>
      @if($counts['pending_approval'] > 0)
        <span class="hero-badge hb-amber">● {{ $counts['pending_approval'] }} pending approval</span>
      @endif
      @if($counts['paid'] > 0)
        <span class="hero-badge hb-green">✓ {{ $counts['paid'] }} paid</span>
      @endif
    </div>
  </div>
</div>

<div class="stats-grid st-stats-grid" style="grid-template-columns:repeat(5,1fr)">
  <div class="stat" onclick="location.href='{{ route('admin.settlements.index') }}'" style="cursor:pointer">
    <div class="stat-icon si-gray"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg></div>
    <div class="stat-body"><div class="stat-lbl">Total</div><div class="stat-val sv-gray">{{ $counts['total'] }}</div><div class="stat-foot">All requests</div></div>
  </div>
  <div class="stat" onclick="location.href='{{ route('admin.settlements.index', ['status' => 'pending_approval']) }}'" style="cursor:pointer">
    <div class="stat-icon si-amber"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
    <div class="stat-body"><div class="stat-lbl">Pending Approval</div><div class="stat-val sv-amber">{{ $counts['pending_approval'] }}</div><div class="stat-foot">Awaiting your review</div></div>
  </div>
  <div class="stat" onclick="location.href='{{ route('admin.settlements.index', ['status' => 'approved']) }}'" style="cursor:pointer">
    <div class="stat-icon si-a"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
    <div class="stat-body"><div class="stat-lbl">Approved</div><div class="stat-val sv-a">{{ $counts['approved'] }}</div><div class="stat-foot">Payout queued</div></div>
  </div>
  <div class="stat" onclick="location.href='{{ route('admin.settlements.index', ['status' => 'paid']) }}'" style="cursor:pointer">
    <div class="stat-icon si-green"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></div>
    <div class="stat-body"><div class="stat-lbl">Paid</div><div class="stat-val sv-green">{{ $counts['paid'] }}</div><div class="stat-foot">Funds transferred</div></div>
  </div>
  <div class="stat" onclick="location.href='{{ route('admin.settlements.index', ['status' => 'rejected']) }}'" style="cursor:pointer">
    <div class="stat-icon si-red"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></div>
    <div class="stat-body"><div class="stat-lbl">Rejected</div><div class="stat-val sv-red">{{ $counts['rejected'] }}</div><div class="stat-foot">Funds returned</div></div>
  </div>
</div>

<div class="filter-row">
  <div class="ftabs">
    <a class="ftab {{ request('status') ? '' : 'on' }}" href="{{ route('admin.settlements.index') }}">All <span class="cnt">{{ $counts['total'] }}</span></a>
    @foreach($statuses as $st)
      <a class="ftab {{ request('status') === $st ? 'on' : '' }}" href="{{ route('admin.settlements.index', ['status' => $st]) }}">
        {{ str($st)->replace('_', ' ')->title() }} <span class="cnt">{{ $counts[$st] }}</span>
      </a>
    @endforeach
  </div>
  <select class="ftab-select" onchange="location.href=this.value;">
    <option value="{{ route('admin.settlements.index') }}" {{ request('status') ? '' : 'selected' }}>All ({{ $counts['total'] }})</option>
    @foreach($statuses as $st)
      <option value="{{ route('admin.settlements.index', ['status' => $st]) }}" {{ request('status') === $st ? 'selected' : '' }}>
        {{ str($st)->replace('_', ' ')->title() }} ({{ $counts[$st] }})
      </option>
    @endforeach
  </select>
</div>

<div class="sec-hdr">
  <div class="sec-ttl">Settlement Requests</div>
  <div style="font-size:12px;color:var(--text3);font-family:var(--mono);">
    Showing <strong style="color:var(--text);">{{ $settlements->firstItem() }}</strong>–<strong style="color:var(--text);">{{ $settlements->lastItem() }}</strong> of <strong style="color:var(--text);">{{ $settlements->total() }}</strong>
  </div>
</div>

<div class="table-card">
  <div class="table-scroll">
    <table id="settlementTable">
      <thead>
        <tr>
          <th style="width:50px">ID</th>
          <th>Organization</th>
          <th>Status</th>
          <th style="text-align:right">Net Amount</th>
          <th>Created</th>
          <th style="text-align:right">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($settlements as $s)
          @php
            $statusLabels = [
              'pending_approval' => 'Pending Approval',
              'approved' => 'Approved',
              'processing' => 'Processing',
              'paid' => 'Paid',
              'rejected' => 'Rejected',
              'failed' => 'Failed',
            ];
            $badgeClass = [
              'pending_approval' => 'st-pending',
              'approved' => 'st-approved',
              'processing' => 'st-processing',
              'paid' => 'st-paid',
              'rejected' => 'st-rejected',
              'failed' => 'st-failed',
            ];
          @endphp
          <tr>
            <td class="cell-id" data-label="ID">{{ $s->id }}</td>
            <td data-label="Organization">
              <div class="st-org">{{ optional($s->organization)->name ?? '—' }}</div>
              <div class="st-org-sub">Org #{{ $s->organization_id }}</div>
            </td>
            <td data-label="Status">
              <span class="st-badge {{ $badgeClass[$s->status] ?? 'st-approved' }}">{{ $statusLabels[$s->status] ?? $s->status }}</span>
            </td>
            <td data-label="Net Amount" style="text-align:right;">
              <span class="st-amount">₹{{ number_format($s->net_amount, 2) }}</span>
            </td>
            <td data-label="Created">
              <div class="st-date">{{ $s->created_at->format('d M Y') }}</div>
              <div class="st-date-sub">{{ $s->created_at->format('H:i') }}</div>
            </td>
            <td data-label="Actions">
              <div class="act-btns">
                <a href="{{ route('admin.settlements.show', $s) }}" class="btn btn-secondary act-btn ab-view">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                  <span>View</span>
                </a>
              </div>
            </td>
          </tr>
        @empty
          <tr class="empty-row">
            <td colspan="6">
              <div class="empty-inner">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                <strong>No settlements found</strong>
                <span>Try a different status filter.</span>
              </div>
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if($settlements->hasPages())
    <div class="table-footer">
      <div class="tfoot-info">
        Showing <strong>{{ $settlements->firstItem() }}</strong>–<strong>{{ $settlements->lastItem() }}</strong> of <strong>{{ $settlements->total() }}</strong>
      </div>
      {{ $settlements->onEachSide(1)->links('vendor.pagination.admin') }}
    </div>
  @endif
</div>

@endsection