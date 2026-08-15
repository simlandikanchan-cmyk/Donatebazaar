@push('page_css')
@vite('resources/css/admin/entries/events.css')
@endpush

{{-- resources/views/admin/events/index.blade.php --}}
@extends('layouts.admin')

@section('sidebar_events', 'active')
@section('page_title', 'Events')
@section('page_subtitle', 'Manage all events')

@section('topbar_left')
<div class="search-wrap">
  <svg class="s-icon-inp" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
  <input class="search-inp" type="text" placeholder="Filter this page…" autocomplete="off" id="liveSearch" value="{{ request('search') }}">
  <span class="s-inp-kbd" id="liveSearchKbd">/</span>
  <button type="button" class="s-inp-clear" id="liveSearchClear" aria-label="Clear">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
  </button>
</div>
@endsection

@push('page_styles')
@vite('resources/css/admin/entries/events-index.css')
@endpush

@section('content')
{{-- ── FLASH ── --}}
@if(session('success'))
  <div class="flash flash-success" role="status">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20 6L9 17l-5-5"/></svg>
    {{ session('success') }}
  </div>
@endif
@if(session('error'))
  <div class="flash flash-error" role="alert">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
    {{ session('error') }}
  </div>
@endif

{{-- ── HERO ── --}}
<div class="hero">
  <div class="hero-left">
    <div class="hero-tag"><span class="hero-tag-dot"></span>Campaigns</div>
    <div class="hero-name">Events Management</div>
    <div class="hero-sub">Manage all campaign events — monitor status, participants, and fundraising goals across every organizer.</div>
    <div class="hero-badges">
      <span class="hero-badge hb-purple">{{ $events->total() }} total</span>
      @if(($stats['active'] ?? 0) > 0)
        <span class="hero-badge hb-green">● {{ $stats['active'] }} active</span>
      @endif
      @if(($stats['pending'] ?? 0) > 0)
        <span class="hero-badge hb-amber">⏱ {{ $stats['pending'] }} pending</span>
      @endif
      @if(($stats['draft'] ?? 0) > 0)
        <span class="hero-badge hb-purple">✎ {{ $stats['draft'] }} drafts</span>
      @endif
      @if(($stats['completed'] ?? 0) > 0)
        <span class="hero-badge hb-blue">✓ {{ $stats['completed'] }} completed</span>
      @endif
      @if(($stats['cancelled'] ?? 0) > 0)
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

{{-- ── STATS (clickable → filters by status, preserving other filters) ── --}}
@php
  $mkStatUrl = fn($status) => request()->fullUrlWithQuery(['status' => $status, 'page' => null]);
  $curStatus = request('status');
@endphp
<div class="stats-grid">
  <a href="{{ $mkStatUrl(null) }}" class="stat {{ !$curStatus ? 'stat-on' : '' }}">
    <div class="stat-icon si-blue">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
    </div>
    <div class="stat-body">
      <div class="stat-lbl">Total</div>
      <div class="stat-val sv-blue">{{ $stats['total'] ?? $events->total() }}</div>
      <div class="stat-foot">All events</div>
    </div>
  </a>
  <a href="{{ $mkStatUrl('active') }}" class="stat {{ $curStatus === 'active' ? 'stat-on' : '' }}">
    <div class="stat-icon si-green">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    </div>
    <div class="stat-body">
      <div class="stat-lbl">Active</div>
      <div class="stat-val sv-green">{{ $stats['active'] ?? 0 }}</div>
      <div class="stat-foot">Live now</div>
    </div>
  </a>
  <a href="{{ $mkStatUrl('pending') }}" class="stat {{ $curStatus === 'pending' ? 'stat-on' : '' }}">
    <div class="stat-icon si-amber">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
    </div>
    <div class="stat-body">
      <div class="stat-lbl">Pending</div>
      <div class="stat-val sv-amber">{{ $stats['pending'] ?? 0 }}</div>
      <div class="stat-foot">Awaiting review</div>
    </div>
  </a>
  <a href="{{ $mkStatUrl('draft') }}" class="stat {{ $curStatus === 'draft' ? 'stat-on' : '' }}">
    <div class="stat-icon si-purple">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
    </div>
    <div class="stat-body">
      <div class="stat-lbl">Drafts</div>
      <div class="stat-val sv-purple">{{ $stats['draft'] ?? 0 }}</div>
      <div class="stat-foot">Not published</div>
    </div>
  </a>
  <a href="{{ $mkStatUrl('cancelled') }}" class="stat {{ $curStatus === 'cancelled' ? 'stat-on' : '' }}">
    <div class="stat-icon si-red">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    </div>
    <div class="stat-body">
      <div class="stat-lbl">Cancelled</div>
      <div class="stat-val sv-red">{{ $stats['cancelled'] ?? 0 }}</div>
      <div class="stat-foot">Not proceeding</div>
    </div>
  </a>
</div>

{{-- ── FILTER BAR ── --}}
<form method="GET" action="{{ route('admin.events.index') }}" class="filter-bar" id="filterForm">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:15px;height:15px;color:var(--text3);flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
  <input class="filter-inp" type="text" name="search" id="filterSearch" placeholder="Search events…" value="{{ request('search') }}">
  <select class="filter-sel" name="status">
    <option value="">All statuses</option>
    @foreach(['pending','active','draft','completed','cancelled','expired'] as $s)
      <option value="{{ $s }}" @selected(request('status') === $s)>{{ ucfirst($s) }}</option>
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

  <div class="filter-div"></div>
  <select class="filter-sel" id="sortSelect" style="min-width:150px;">
    <option value="default">Sort: Default</option>
    <option value="date-soon">Date: Soonest</option>
    <option value="date-far">Date: Latest</option>
    <option value="raised-high">Raised: High–Low</option>
    <option value="raised-low">Raised: Low–High</option>
    <option value="participants">Most Participants</option>
    <option value="title">Title: A–Z</option>
  </select>

  <span class="filter-spacer"></span>
  <span class="filter-count">{{ $events->total() }} result{{ $events->total() !== 1 ? 's' : '' }}</span>
</form>

@if(request('search') || request('status') || request('date'))
<div class="active-filters">
  <span class="af-label">Filters:</span>
  @if(request('search'))
    <a href="{{ request()->fullUrlWithQuery(['search'=>null,'page'=>null]) }}" class="filter-chip">
      Search: "{{ request('search') }}"
      <span class="chip-x"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></span>
    </a>
  @endif
  @if(request('status'))
    <a href="{{ request()->fullUrlWithQuery(['status'=>null,'page'=>null]) }}" class="filter-chip">
      Status: {{ ucfirst(request('status')) }}
      <span class="chip-x"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></span>
    </a>
  @endif
  @if(request('date'))
    <a href="{{ request()->fullUrlWithQuery(['date'=>null,'page'=>null]) }}" class="filter-chip">
      Date: {{ request('date') }}
      <span class="chip-x"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></span>
    </a>
  @endif
  <a href="{{ route('admin.events.index') }}" class="clear-all-btn">Clear all</a>
</div>
@endif

{{-- ── TABLE (desktop) ── --}}
<div class="sec-hdr">
  <div class="sec-ttl">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
    Events
  </div>
</div>

<div class="table-card">
  <div class="table-wrap">
    <table id="eventsTable">
      <thead>
        <tr>
          <th class="th-check"><input type="checkbox" class="chk" id="selectAll" data-action="toggle-select-all" aria-label="Select all"></th>
          <th>Event</th>
          <th>Campaign</th>
          <th>Organizer</th>
          <th>Date</th>
          <th>Raised / Goal</th>
          <th>Status</th>
          <th>Participants</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($events as $event)
          @php
            $badgeClass = match($event->status) {
              'active'    => 'b-active',
              'completed' => 'b-completed',
              'cancelled' => 'b-cancelled',
              'expired'   => 'b-expired',
              'draft'     => 'b-draft',
              default     => 'b-pending',
            };
            $goal   = $event->goal_amount ? (float) $event->goal_amount : 0;
            $raised = $event->raised_amount ? (float) $event->raised_amount : 0;
            $pct    = $goal > 0 ? min(100, round($raised / $goal * 100)) : 0;
            $isFull = $event->isFull();
          @endphp
          <tr data-id="{{ $event->id }}"
              data-name="{{ strtolower($event->title) }}"
              data-ts="{{ $event->event_date?->timestamp ?? 0 }}"
              data-raised="{{ $raised }}"
              data-participants="{{ $event->registered_count }}"
              data-delete-url="{{ route('admin.events.destroy', $event->id) }}">
            <td class="td-check"><input type="checkbox" class="chk row-check" data-action="toggle-row-select" aria-label="Select {{ $event->title }}"></td>
            <td>
              <div class="ev-cell">
                @if($event->cover_image)
                  <img class="ev-thumb" src="{{ asset('storage/'.$event->cover_image) }}" alt="{{ $event->title }}" loading="lazy">
                @else
                  <div class="ev-thumb-ph">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                  </div>
                @endif
                <div style="min-width:0">
                  <div class="ev-title">{{ $event->title }}</div>
                  <div class="ev-goal">
                    Goal: ₹{{ number_format($goal, 2) }}
                    @if($event->location)
                      <span class="ev-loc">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        {{ $event->location }}
                      </span>
                    @endif
                  </div>
                </div>
              </div>
            </td>
            <td>
              <span class="campaign-chip" title="{{ $event->campaign->title ?? '' }}">
                <span>{{ $event->campaign->title ?? '—' }}</span>
              </span>
            </td>
            <td><div class="organizer-name">{{ $event->user->name ?? '—' }}</div></td>
            <td class="cell-date">
              {{ $event->event_date?->format('d M Y') ?? '—' }}
              @if($event->start_time)
                <span class="dt-time">{{ \Carbon\Carbon::parse($event->start_time)->format('h:i A') }}@if($event->end_time) – {{\Carbon\Carbon::parse($event->end_time)->format('h:i A') }}@endif</span>
              @endif
            </td>
            <td>
              <div class="prog-wrap">
                <div class="prog-top">
                  <span class="prog-amt">₹{{ number_format($raised, 0) }}</span>
                  <span class="prog-pct">{{ $pct }}%</span>
                </div>
                <div class="prog-bar">
                  <div class="prog-fill {{ $pct >= 100 ? 'pf-full' : ($pct == 0 ? 'pf-none' : '') }}" style="width:{{ $pct }}%"></div>
                </div>
              </div>
            </td>
            <td>
              <span class="badge {{ $badgeClass }}">
                <span class="status-dot dot-{{ $event->status === 'draft' ? 'pending' : $event->status }}"></span>
                {{ ucfirst($event->status) }}
              </span>
            </td>
            <td>
              <span class="participants-val">
                {{ $event->registered_count }}<span class="participants-total"> / {{ $event->max_participants ?: '∞' }}</span>
              </span>
              @if($isFull)<span class="participants-full">Full</span>@endif
            </td>
            <td>
              <div class="act-wrap">
                <a href="{{ route('admin.events.show', $event->id) }}" class="btn btn-secondary act-btn ab-view"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>View</a>
                <a href="{{ route('admin.events.edit', $event->id) }}" class="btn btn-secondary act-btn act-edit"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>Edit</a>
              </div>
            </td>
          </tr>
        @empty
          <tr class="empty-row">
            <td colspan="9">
              <div class="empty-inner">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <strong>No events found</strong>
                <span>No events match your current filters.</span>
                @if(request('search') || request('status') || request('date'))
                  <a href="{{ route('admin.events.index') }}" class="reset-btn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:12px;height:12px;"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h5M20 20v-5h-5M4 9a9 9 0 0114.13-5.36M20 15a9 9 0 01-14.13 5.36"/></svg>
                    Clear filters
                  </a>
                @endif
              </div>
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

{{-- ── CARDS (mobile) ── --}}
<div class="ev-cards">
  @forelse($events as $event)
    @php
      $badgeClass = match($event->status) {
        'active'    => 'b-active',
        'completed' => 'b-completed',
        'cancelled' => 'b-cancelled',
        'expired'   => 'b-expired',
        'draft'     => 'b-draft',
        default     => 'b-pending',
      };
      $goal   = $event->goal_amount ? (float) $event->goal_amount : 0;
      $raised = $event->raised_amount ? (float) $event->raised_amount : 0;
      $pct    = $goal > 0 ? min(100, round($raised / $goal * 100)) : 0;
    @endphp
    <div class="ev-card"
         data-id="{{ $event->id }}"
         data-name="{{ strtolower($event->title) }}"
         data-ts="{{ $event->event_date?->timestamp ?? 0 }}"
         data-raised="{{ $raised }}"
         data-participants="{{ $event->registered_count }}"
         data-delete-url="{{ route('admin.events.destroy', $event->id) }}">
      <input type="checkbox" class="chk row-check ev-card-check" data-action="toggle-row-select" aria-label="Select {{ $event->title }}">
      <div class="ev-card-top">
        @if($event->cover_image)
          <img class="ev-thumb" src="{{ asset('storage/'.$event->cover_image) }}" alt="{{ $event->title }}" loading="lazy">
        @else
          <div class="ev-thumb-ph">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
          </div>
        @endif
        <div class="ev-card-body">
          <div class="ev-title">{{ $event->title }}</div>
          <span class="badge {{ $badgeClass }}" style="margin-top:7px">
            <span class="status-dot dot-{{ $event->status === 'draft' ? 'pending' : $event->status }}"></span>
            {{ ucfirst($event->status) }}
          </span>
          <div class="ev-card-meta">
            <span class="campaign-chip"><span>{{ $event->campaign->title ?? '—' }}</span></span>
          </div>
        </div>
      </div>
      <div class="ev-card-stats">
        <div class="ev-card-stat">
          <span class="lbl">Raised / Goal</span>
          <span class="val">₹{{ number_format($raised,0) }} / ₹{{ number_format($goal,0) }}</span>
          <div class="prog-bar" style="margin-top:4px">
            <div class="prog-fill {{ $pct >= 100 ? 'pf-full' : ($pct == 0 ? 'pf-none' : '') }}" style="width:{{ $pct }}%"></div>
          </div>
        </div>
        <div class="ev-card-stat">
          <span class="lbl">Participants</span>
          <span class="val">{{ $event->registered_count }} / {{ $event->max_participants ?: '∞' }}</span>
        </div>
        <div class="ev-card-stat">
          <span class="lbl">Date</span>
          <span class="val">{{ $event->event_date?->format('d M Y') ?? '—' }}</span>
        </div>
        <div class="ev-card-stat">
          <span class="lbl">Organizer</span>
          <span class="val" style="font-size:11.5px">{{ $event->user->name ?? '—' }}</span>
        </div>
      </div>
      <div class="ev-card-actions">
        <a href="{{ route('admin.events.show', $event->id) }}" class="btn btn-secondary act-btn ab-view">View</a>
        <a href="{{ route('admin.events.edit', $event->id) }}" class="btn btn-secondary act-link act-edit">Edit</a>
        <form class="act-form" method="POST" action="{{ route('admin.events.toggleSetting', $event->id) }}">
          @csrf
          <input type="hidden" name="field" value="send_notification">
          <button type="submit" class="btn btn-secondary act-link act-bell {{ $event->send_notification ? 'is-on' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
            {{ $event->send_notification ? 'Notify On' : 'Notify' }}
          </button>
        </form>
        @if($event->status === 'pending')
          <form class="act-form" method="POST" action="{{ route('admin.events.approve', $event->id) }}">@csrf<button type="submit" class="btn btn-green act-link act-approve">Approve</button></form>
          <form class="act-form" method="POST" action="{{ route('admin.events.reject', $event->id) }}">@csrf<button type="submit" class="btn btn-red act-link act-reject">Reject</button></form>
        @elseif($event->status === 'draft')
          <form class="act-form" method="POST" action="{{ route('admin.events.publish', $event->id) }}">@csrf<button type="submit" class="btn btn-green act-link act-approve">Publish</button></form>
        @elseif($event->status === 'active')
          <form class="act-form" method="POST" action="{{ route('admin.events.draft', $event->id) }}">@csrf<button type="submit" class="btn btn-red act-link act-reject">Unpublish</button></form>
        @endif
      </div>
    </div>
  @empty
    <div class="ev-card" style="text-align:center">
      <div class="empty-inner">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        <strong>No events found</strong>
        <span>No events match your current filters.</span>
        @if(request('search') || request('status') || request('date'))
          <a href="{{ route('admin.events.index') }}" class="reset-btn">Clear filters</a>
        @endif
      </div>
    </div>
  @endforelse
</div>

<div class="bulk-bar" id="bulkBar">
  <span class="bulk-count" id="bulkCount">0 selected</span>
  <div class="bulk-acts">
    <button type="button" class="btn btn-red bulk-btn bulk-btn-del" data-action="open-bulk-confirm">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6"/></svg>Delete
    </button>
    <button type="button" class="btn btn-secondary bulk-btn bulk-btn-cancel" data-action="clear-selection">Cancel</button>
  </div>
</div>

<div class="pagination-wrap">{{ $events->links('vendor.pagination.admin') }}</div>
@endsection

@push('page_scripts')
@vite('resources/js/admin/events-index.js')
@endpush