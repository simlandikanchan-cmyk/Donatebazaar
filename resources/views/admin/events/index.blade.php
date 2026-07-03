{{-- resources/views/admin/events/index.blade.php --}}
@extends('layouts.admin')

@section('sidebar_events', 'active')
@section('page_title', 'Events')
@section('page_subtitle', 'Manage all events')

@section('topbar_left')
<div class="search-wrap">
  <svg class="s-icon-inp" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
  <input class="search-inp" type="text" placeholder="Search events…" autocomplete="off" id="liveSearch">
</div>
@endsection

@push('page_styles')
<style>
.filter-bar{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);padding:16px 20px;box-shadow:var(--sh);margin-bottom:20px;display:flex;align-items:center;gap:10px;flex-wrap:wrap;animation:fadeUp .4s .1s ease both;}
.filter-inp,.filter-sel{height:36px;background:var(--surface2);border:1px solid var(--border2);border-radius:var(--r-sm);padding:0 12px;font-size:12.5px;color:var(--text);font-family:var(--font);outline:none;transition:border-color var(--ease),box-shadow var(--ease);}
.filter-inp{width:200px;}
.filter-inp:focus,.filter-sel:focus{border-color:var(--a);box-shadow:0 0 0 3px var(--a-glow);}
.filter-inp::placeholder{color:var(--text3);}
.filter-sel{cursor:pointer;min-width:130px;}
.filter-date{width:150px;}
.filter-btn{height:36px;padding:0 18px;background:linear-gradient(135deg,var(--a),var(--a2));color:#fff;border:none;border-radius:var(--r-sm);font-size:12.5px;font-weight:600;font-family:var(--font);cursor:pointer;transition:opacity var(--ease),transform var(--ease);box-shadow:0 3px 10px rgba(110,86,247,.3);}
.filter-btn:hover{opacity:.88;transform:translateY(-1px);}
.filter-clear{height:36px;padding:0 14px;background:transparent;border:1px solid var(--border2);border-radius:var(--r-sm);font-size:12px;color:var(--text3);font-family:var(--font);cursor:pointer;transition:all var(--ease);text-decoration:none;display:inline-flex;align-items:center;gap:5px;}
.filter-clear:hover{border-color:var(--red);color:var(--red);}
.table-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh);overflow:hidden;animation:fadeUp .4s .18s ease both;}
.table-wrap{overflow-x:auto;}
table{width:100%;border-collapse:collapse;}
thead{background:var(--surface2);border-bottom:1px solid var(--border);}
thead th{padding:12px 16px;text-align:left;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.09em;color:var(--text3);font-family:var(--mono);white-space:nowrap;}
tbody td{padding:14px 16px;border-bottom:1px solid var(--border);vertical-align:middle;}
tbody tr:last-child td{border-bottom:none;}
tbody tr{transition:background var(--ease);}
tbody tr:hover{background:var(--surface2);}
.cell-id{font-family:var(--mono);font-size:11px;color:var(--text3);font-weight:500;}
.event-title{font-size:13.5px;font-weight:600;color:var(--text);line-height:1.2;}
.event-goal{font-size:11px;color:var(--text3);margin-top:2px;font-family:var(--mono);}
.campaign-name{font-size:13px;font-weight:500;color:var(--text2);}
.organizer-name{font-size:13px;font-weight:500;color:var(--text2);}
.cell-date{font-family:var(--mono);font-size:11.5px;color:var(--text3);}
.participants-val{font-family:var(--mono);font-size:12px;font-weight:600;color:var(--text2);}
.participants-total{color:var(--text3);font-weight:400;}
.b-completed{background:rgba(5,196,138,.15);color:#059669;border:1px solid rgba(5,196,138,.25);}
.b-cancelled{background:rgba(240,68,68,.15);color:var(--red);border:1px solid rgba(240,68,68,.25);}
.b-expired{background:rgba(107,114,128,.15);color:#374151;border:1px solid rgba(107,114,128,.25);}
[data-theme="dark"] .b-completed{color:#93c5fd;}
[data-theme="dark"] .b-cancelled{color:#f87171;}
[data-theme="dark"] .b-expired{color:#9ca3af;}
.status-dot{width:6px;height:6px;border-radius:50%;flex-shrink:0;}
.dot-active{background:var(--green);}
.dot-pending{background:var(--amber);}
.dot-completed{background:var(--blue);}
.dot-cancelled{background:var(--red);}
.dot-expired{background:var(--gray);}
.act-wrap{display:flex;gap:6px;align-items:center;}
.act-link{display:inline-flex;align-items:center;gap:5px;padding:5px 11px;border-radius:7px;font-size:11.5px;font-weight:500;color:var(--text2);background:var(--surface2);border:1px solid var(--border2);transition:all var(--ease);text-decoration:none;}
.act-link:hover{background:var(--surface3);color:var(--text);transform:translateY(-1px);}
.act-link svg{width:11px;height:11px;}
.act-edit{color:var(--a);background:var(--a-lt);border-color:rgba(110,86,247,.2);}
.act-edit:hover{background:var(--a);color:#fff;border-color:var(--a);}
.empty-row td{text-align:center;padding:56px 20px;}
.empty-inner{display:flex;flex-direction:column;align-items:center;gap:10px;}
.empty-inner svg{width:48px;height:48px;color:var(--text3);opacity:.25;}
.empty-inner strong{font-family:var(--mono);font-size:15px;font-weight:700;color:var(--text2);}
.empty-inner span{font-size:13px;color:var(--text3);}
.flash{padding:12px 16px;border-radius:var(--r-sm);margin-bottom:20px;font-size:13px;font-weight:500;display:flex;align-items:center;gap:10px;animation:fadeUp .3s ease both;}
.flash-success{background:var(--green-lt);border:1px solid rgba(5,196,138,.25);color:#059669;}
.flash-error{background:var(--red-lt);border:1px solid rgba(240,68,68,.25);color:var(--red);}
.flash svg{width:14px;height:14px;flex-shrink:0;}
@media(max-width:860px){.search-wrap{display:none}.stats-grid{grid-template-columns:repeat(2,1fr)}}
@media(max-width:600px){.filter-bar{flex-direction:column;align-items:stretch}.filter-inp,.filter-sel,.filter-date{width:100%}}
</style>
@endpush
@section('content')
{{-- ── HERO ── --}}
<div class="hero">
  <div class="hero-left">
    <div class="hero-tag"><span class="hero-tag-dot"></span>Campaigns</div>
    <div class="hero-name">Events Management</div>
    <div class="hero-sub">Manage all campaign events — monitor status, participants, and goals across every organizer.</div>
    <div class="hero-badges">
      <span class="hero-badge hb-purple">{{ $events->total() }} total</span>
      @if(isset($stats['active']) && $stats['active'] > 0)
        <span class="hero-badge hb-green">● {{ $stats['active'] }} active</span>
      @endif
      @if(isset($stats['pending']) && $stats['pending'] > 0)
        <span class="hero-badge hb-amber">⏱ {{ $stats['pending'] }} pending</span>
      @endif
      @if(isset($stats['completed']) && $stats['completed'] > 0)
        <span class="hero-badge hb-blue">✓ {{ $stats['completed'] }} completed</span>
      @endif
      @if(isset($stats['cancelled']) && $stats['cancelled'] > 0)
        <span class="hero-badge hb-red">✕ {{ $stats['cancelled'] }} cancelled</span>
      @endif
    </div>
  </div>
  <div class="hero-right">
    <a href="{{ route('admin.events.create') }}" class="hero-btn hero-btn-primary">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
      New Event
    </a>
    <a href="{{ route('admin.campaign.index') }}" class="hero-btn hero-btn-ghost">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
      All Campaigns
    </a>
  </div>
</div>

{{-- ── STATS ── --}}
<div class="stats-grid">
  <div class="stat">
    <div class="stat-icon si-blue">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
    </div>
    <div class="stat-body">
      <div class="stat-lbl">Total</div>
      <div class="stat-val sv-blue">{{ $stats['total'] ?? $events->total() }}</div>
      <div class="stat-foot">All events</div>
    </div>
  </div>
  <div class="stat">
    <div class="stat-icon si-green">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    </div>
    <div class="stat-body">
      <div class="stat-lbl">Active</div>
      <div class="stat-val sv-green">{{ $stats['active'] ?? 0 }}</div>
      <div class="stat-foot">Live now</div>
    </div>
  </div>
  <div class="stat">
    <div class="stat-icon si-amber">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
    </div>
    <div class="stat-body">
      <div class="stat-lbl">Pending</div>
      <div class="stat-val sv-amber">{{ $stats['pending'] ?? 0 }}</div>
      <div class="stat-foot">Awaiting start</div>
    </div>
  </div>
  <div class="stat">
    <div class="stat-icon si-purple">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
    </div>
    <div class="stat-body">
      <div class="stat-lbl">Completed</div>
      <div class="stat-val sv-purple">{{ $stats['completed'] ?? 0 }}</div>
      <div class="stat-foot">Finished</div>
    </div>
  </div>
  <div class="stat">
    <div class="stat-icon si-red">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    </div>
    <div class="stat-body">
      <div class="stat-lbl">Cancelled</div>
      <div class="stat-val sv-red">{{ $stats['cancelled'] ?? 0 }}</div>
      <div class="stat-foot">Not proceeding</div>
    </div>
  </div>
</div>

{{-- ── FILTER BAR ── --}}
<form method="GET" action="{{ route('admin.events.index') }}" class="filter-bar">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:15px;height:15px;color:var(--text3);flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
  <input class="filter-inp" type="text" name="search" placeholder="Search events…" value="{{ request('search') }}">
  <select class="filter-sel" name="status">
    <option value="">All statuses</option>
    @foreach(['pending','active','completed','cancelled','expired'] as $status)
      <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
    @endforeach
  </select>
  <input class="filter-inp filter-date" type="date" name="date" value="{{ request('date') }}">
  <button type="submit" class="filter-btn">Apply Filters</button>
  @if(request('search') || request('status') || request('date'))
    <a href="{{ route('admin.events.index') }}" class="filter-clear">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:11px;height:11px;"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
      Clear
    </a>
  @endif
</form>

{{-- ── TABLE ── --}}
<div class="sec-hdr">
  <div class="sec-ttl">Events</div>
  <div class="sec-right" style="font-size:12px;color:var(--text3);font-family:var(--mono);">
    {{ $events->total() }} result{{ $events->total() !== 1 ? 's' : '' }}
  </div>
</div>

<div class="table-card">
  <div class="table-wrap">
    <table id="eventsTable">
      <thead>
        <tr>
          <th>Sl No</th>
          <th>Event</th>
          <th>Campaign</th>
          <th>Organizer</th>
          <th>Date</th>
          <th>Status</th>
          <th>Participants</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($events as $event)
        <tr data-name="{{ strtolower($event->title) }}">
          <td class="cell-id">{{ $event->id }}</td>
          <td>
            <div class="event-title">{{ $event->title }}</div>
            <div class="event-goal">Goal: ₹{{ number_format($event->goal_amount, 2) }}</div>
          </td>
          <td>
            <div class="campaign-name">{{ $event->campaign->title ?? '—' }}</div>
          </td>
          <td>
            <div class="organizer-name">{{ $event->user->name ?? '—' }}</div>
          </td>
          <td class="cell-date">{{ $event->event_date?->format('d M Y') ?? '—' }}</td>
          <td>
            @php
              $badgeClass = match($event->status) {
                'active'    => 'b-active',
                'completed' => 'b-completed',
                'cancelled' => 'b-cancelled',
                'expired'   => 'b-expired',
                default     => 'b-pending',
              };
              $dotClass = match($event->status) {
                'active'    => 'dot-active',
                'completed' => 'dot-completed',
                'cancelled' => 'dot-cancelled',
                'expired'   => 'dot-expired',
                default     => 'dot-pending',
              };
            @endphp
            <span class="badge {{ $badgeClass }}">
              <span class="status-dot {{ $dotClass }}"></span>
              {{ ucfirst($event->status) }}
            </span>
          </td>
          <td>
            <span class="participants-val">
              {{ $event->registered_count }}<span class="participants-total"> / {{ $event->max_participants ?: '∞' }}</span>
            </span>
          </td>
          <td>
            <div class="act-wrap">
              <a href="{{ route('admin.events.show', $event->id) }}" class="act-link">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                View
              </a>
              <a href="{{ route('admin.events.edit', $event->id) }}" class="act-link act-edit">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit
              </a>
            </div>
          </td>
        </tr>
        @empty
        <tr class="empty-row">
          <td colspan="8">
            <div class="empty-inner">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
              <strong>No events found</strong>
              <span>No events match your current filters.</span>
            </div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<div class="pagination-wrap">{{ $events->links('vendor.pagination.admin') }}</div>
@endsection

@push('page_scripts')
<script>
(function(){
'use strict';
var searchEl = document.getElementById('liveSearch');
if (searchEl) {
  var st;
  searchEl.addEventListener('input', function(){
    clearTimeout(st);
    var q = this.value.toLowerCase().trim();
    st = setTimeout(function(){
      document.querySelectorAll('#eventsTable tbody tr[data-name]').forEach(function(row){
        row.style.display = (!q || row.dataset.name.includes(q)) ? '' : 'none';
      });
    }, 160);
  });
}
})();
</script>
@endpush
