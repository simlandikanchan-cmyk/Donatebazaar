{{-- resources/views/admin/events/show.blade.php --}}
@extends('layouts.admin')

@section('sidebar_events', 'active')
@section('page_title', $event->title)
@section('page_subtitle', 'Event details')

@push('page_styles')
<style>
.card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh);overflow:hidden;animation:fadeUp .4s ease both;}
.card+.card{margin-top:16px;}
.card-header{padding:18px 22px;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:12px;}
.card-icon{width:36px;height:36px;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.card-icon svg{width:16px;height:16px;}
.ci-purple{background:var(--a-lt);color:var(--a);}
.ci-green{background:var(--green-lt);color:var(--green);}
.ci-amber{background:var(--amber-lt);color:var(--amber);}
.ci-blue{background:var(--blue-lt);color:var(--blue);}
.ci-red{background:var(--red-lt);color:var(--red);}
.card-title{font-family:var(--mono);font-size:14px;font-weight:700;color:var(--text);letter-spacing:-.01em;}
.card-subtitle{font-size:11px;color:var(--text3);margin-top:2px;}
.card-body{padding:22px;}
.event-cover{width:100%;height:260px;object-fit:cover;display:block;}
.event-cover-placeholder{width:100%;height:160px;background:linear-gradient(135deg,var(--a-lt),var(--surface3));display:flex;align-items:center;justify-content:center;}
.event-cover-placeholder svg{width:44px;height:44px;color:var(--a);opacity:.25;}
.status-pill{display:inline-flex;align-items:center;gap:5px;padding:4px 11px;border-radius:100px;font-size:11px;font-weight:700;font-family:var(--mono);}
.sp-dot{width:6px;height:6px;border-radius:50%;flex-shrink:0;}
.pill-active{background:var(--green-lt);color:#059669;}.pill-active .sp-dot{background:var(--green);}
.pill-draft{background:var(--amber-lt);color:#b45309;}.pill-draft .sp-dot{background:var(--amber);}
.pill-cancelled{background:var(--red-lt);color:var(--red);}.pill-cancelled .sp-dot{background:var(--red);}
.pill-expired{background:var(--gray-lt);color:var(--gray);}.pill-expired .sp-dot{background:var(--gray);}
.pill-completed{background:var(--blue-lt);color:var(--blue);}.pill-completed .sp-dot{background:var(--blue);}
.pill-pending{background:var(--a-lt);color:var(--a);}.pill-pending .sp-dot{background:var(--a);}
[data-theme="dark"] .pill-active{color:#34d399;}
[data-theme="dark"] .pill-pending{color:#c4b5fd;}
.detail-grid{display:grid;grid-template-columns:1fr 1fr;gap:0;}
.detail-item{padding:13px 0;border-bottom:1px solid var(--border);}
.detail-item:nth-last-child(-n+2){border-bottom:none;}
.detail-item:nth-child(odd){padding-right:18px;}
.detail-key{font-size:10px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.08em;font-family:var(--mono);margin-bottom:4px;}
.detail-val{font-size:13.5px;font-weight:500;color:var(--text);}
.detail-val.muted{color:var(--text3);font-style:italic;font-weight:400;}
.stat-row{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:20px;}
.stat-mini{background:var(--surface);border:1px solid var(--border);border-radius:var(--r-sm);padding:16px 18px;box-shadow:var(--sh);animation:fadeUp .4s ease both;}
.stat-mini:nth-child(1){animation-delay:.04s;}
.stat-mini:nth-child(2){animation-delay:.08s;}
.stat-mini:nth-child(3){animation-delay:.12s;}
.stat-mini-lbl{font-size:10px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.08em;font-family:var(--mono);margin-bottom:6px;}
.stat-mini-val{font-size:1.6rem;font-weight:800;color:var(--text);font-family:var(--mono);letter-spacing:-.02em;line-height:1;}
.stat-mini-sub{font-size:11px;color:var(--text3);margin-top:5px;}
.progress-label{display:flex;justify-content:space-between;font-size:11px;font-family:var(--mono);color:var(--text3);margin-bottom:6px;}
.progress-bar{height:7px;background:var(--surface3);border-radius:100px;overflow:hidden;}
.progress-fill{height:100%;border-radius:100px;background:linear-gradient(90deg,var(--a),var(--green));}
.setting-row{display:flex;align-items:center;gap:12px;padding:14px 0;border-bottom:1px solid var(--border);}
.setting-row:last-child{border-bottom:none;}
.setting-icon{width:32px;height:32px;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.setting-icon svg{width:13px;height:13px;}
.setting-info{flex:1;}
.setting-name{font-size:13px;font-weight:600;color:var(--text);}
.setting-desc{font-size:11px;color:var(--text3);margin-top:1px;}
.toggle-wrap{position:relative;display:inline-block;width:46px;height:26px;flex-shrink:0;cursor:pointer;}
.toggle-wrap input{position:absolute;opacity:0;width:0;height:0;pointer-events:none;}
.toggle-track{position:absolute;inset:0;border-radius:100px;background:var(--surface3);border:1.5px solid var(--border2);transition:background .25s,border-color .25s;cursor:pointer;}
.toggle-track::after{content:'';position:absolute;width:18px;height:18px;border-radius:50%;background:#fff;top:2px;left:2px;transition:transform .25s;box-shadow:0 2px 4px rgba(0,0,0,.18);}
.toggle-wrap input:checked ~ .toggle-track{background:var(--a);border-color:var(--a);}
.toggle-wrap input:checked ~ .toggle-track::after{transform:translateX(20px);}
.btn{display:inline-flex;align-items:center;gap:8px;padding:11px 22px;border-radius:var(--r-sm);font-size:13px;font-weight:600;font-family:var(--font);cursor:pointer;border:none;transition:all var(--ease);text-decoration:none;white-space:nowrap;}
.btn svg{width:14px;height:14px;}
.btn-sm{padding:7px 14px;font-size:12px;}
.btn-approve{background:linear-gradient(135deg,var(--green),#059669);color:#fff;box-shadow:0 4px 18px rgba(5,196,138,.4);}
.btn-approve:hover{transform:translateY(-2px);box-shadow:0 8px 28px rgba(5,196,138,.5);}
.btn-publish{background:linear-gradient(135deg,var(--a),var(--a2));color:#fff;box-shadow:0 4px 18px rgba(110,86,247,.4);}
.btn-publish:hover{transform:translateY(-2px);box-shadow:0 8px 28px rgba(110,86,247,.5);}
.btn-draft{background:var(--amber-lt);color:#b45309;border:1px solid rgba(245,158,11,.3);}
.btn-draft:hover{background:rgba(245,158,11,.2);}
.btn-edit{background:var(--surface2);color:var(--text2);border:1px solid var(--border2);}
.btn-edit:hover{background:var(--a-lt);color:var(--a);border-color:rgba(110,86,247,.3);}
.btn-danger{background:var(--red-lt);color:var(--red);border:1px solid rgba(240,68,68,.2);}
.btn-danger:hover{background:rgba(240,68,68,.16);}
.btn-reject{background:var(--red-lt);color:var(--red);border:1px solid rgba(240,68,68,.2);}
.btn-reject:hover{background:rgba(240,68,68,.16);}
.show-sidebar{position:sticky;top:82px;}
.show-sidebar .card+.card{margin-top:14px;}
.summary-hdr{padding:14px 18px;border-bottom:1px solid var(--border);background:var(--surface2);}
.summary-hdr-title{font-family:var(--mono);font-size:13px;font-weight:700;color:var(--text);}
.summary-body{padding:14px 18px;}
.summary-row{display:flex;flex-direction:column;gap:3px;padding:10px 0;border-bottom:1px solid var(--border);}
.summary-row:last-child{border-bottom:none;padding-bottom:0;}
.summary-row:first-child{padding-top:0;}
.summary-key{font-size:9.5px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.09em;font-family:var(--mono);}
.summary-val{font-size:12.5px;font-weight:500;color:var(--text);font-family:var(--mono);}
.action-zone{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh);overflow:hidden;animation:fadeUp .4s .1s ease both;}
.action-zone-header{padding:14px 18px;border-bottom:1px solid var(--border);background:var(--surface2);}
.action-zone-title{font-family:var(--mono);font-size:13px;font-weight:700;color:var(--text);}
.action-zone-body{padding:16px 18px;display:flex;flex-direction:column;gap:8px;}
.flash{padding:12px 16px;border-radius:var(--r-sm);margin-bottom:20px;font-size:13px;font-weight:500;display:flex;align-items:center;gap:10px;animation:fadeUp .3s ease both;}
.flash-success{background:var(--green-lt);border:1px solid rgba(5,196,138,.25);color:#059669;}
.flash svg{width:14px;height:14px;flex-shrink:0;}
.campaign-mini{display:flex;align-items:center;gap:12px;padding:12px;background:var(--surface2);border-radius:var(--r-sm);border:1px solid var(--border2);}
.campaign-mini-thumb{width:44px;height:44px;border-radius:9px;object-fit:cover;flex-shrink:0;background:var(--a-lt);}
.campaign-mini-placeholder{width:44px;height:44px;border-radius:9px;background:var(--a-lt);display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.campaign-mini-placeholder svg{width:18px;height:18px;color:var(--a);opacity:.5;}
.campaign-mini-info{flex:1;min-width:0;}
.campaign-mini-title{font-size:13px;font-weight:600;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.campaign-mini-meta{font-size:10.5px;color:var(--text3);margin-top:2px;font-family:var(--mono);}
.desc-block{font-size:13.5px;color:var(--text2);line-height:1.75;white-space:pre-line;}
.show-grid{display:grid;grid-template-columns:1fr 310px;gap:20px;align-items:start;}
@media(max-width:860px){.show-grid{grid-template-columns:1fr}}
@media(max-width:700px){.stat-row{grid-template-columns:1fr 1fr}.detail-grid{grid-template-columns:1fr}}
</style>
@endpush
@section('content')
@if(session('success'))
<div class="flash flash-success">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
  {{ session('success') }}
</div>
@endif

{{-- STATUS BANNER --}}
@php
  $statusClass = match($event->status) {
    'active'    => 'sb-active',
    'pending'   => 'sb-pending',
    'draft'     => 'sb-draft',
    'cancelled' => 'sb-cancelled',
    'expired'   => 'sb-expired',
    'completed' => 'sb-completed',
    default     => 'sb-draft',
  };
@endphp
<div class="status-banner {{ $statusClass }}">
  @if($event->status === 'pending')
    <svg viewBox="0 0 24 24" fill="none" stroke="var(--a)" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    <div class="sb-text">
      <div class="sb-title">This event is awaiting approval</div>
      <div class="sb-sub">Review the event details and approve or reject it.</div>
    </div>
    <div style="display:flex;gap:8px;flex-shrink:0;">
      <form method="POST" action="{{ route('admin.events.approve', $event) }}">
        @csrf
        <button type="submit" class="btn btn-approve btn-sm">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
          Approve
        </button>
      </form>
      <form method="POST" action="{{ route('admin.events.reject', $event) }}">
        @csrf
        <button type="submit" class="btn btn-reject btn-sm">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
          Reject
        </button>
      </form>
    </div>
  @elseif($event->status === 'draft')
    <svg viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
    <div class="sb-text">
      <div class="sb-title">This event is saved as Draft</div>
      <div class="sb-sub">Not visible to the public. Edit anything you need, then publish when ready.</div>
    </div>
    <form method="POST" action="{{ route('admin.events.publish', $event) }}" style="flex-shrink:0;">
      @csrf
      <button type="submit" class="btn btn-publish btn-sm">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
        Publish Now
      </button>
    </form>
  @elseif($event->status === 'active')
    <svg viewBox="0 0 24 24" fill="none" stroke="#05c48a" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    <div class="sb-text">
      <div class="sb-title">This event is Live</div>
      <div class="sb-sub">Publicly visible and accepting registrations.</div>
    </div>
    <form method="POST" action="{{ route('admin.events.draft', $event) }}" style="flex-shrink:0;">
      @csrf
      <button type="submit" class="btn btn-draft btn-sm">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
        Revert to Draft
      </button>
    </form>
  @elseif($event->status === 'completed')
    <svg viewBox="0 0 24 24" fill="none" stroke="var(--blue)" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    <div class="sb-text">
      <div class="sb-title">This event has been completed</div>
      <div class="sb-sub">The event date has passed and it has been marked as completed.</div>
    </div>
  @elseif($event->status === 'cancelled')
    <svg viewBox="0 0 24 24" fill="none" stroke="var(--red)" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    <div class="sb-text">
      <div class="sb-title">This event has been cancelled</div>
      <div class="sb-sub">You can restore it by publishing again from the edit page.</div>
    </div>
  @else
    <!-- <svg viewBox="0 0 24 24" fill="none" stroke="var(--gray)" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
    <div class="sb-text">
      <div class="sb-title">Status: {{ ucfirst($event->status) }}</div>
      <div class="sb-sub">Edit the event to change its status.</div>
    </div> -->
  @endif
</div>

{{-- STATS --}}
@php
  $raised   = $event->raised_amount ?? 0;
  $goal     = $event->goal_amount ?? 1;
  $pct      = $goal > 0 ? min(100, round($raised / $goal * 100)) : 0;
  $regCount = $event->registered_count ?? 0;
  $days     = $event->event_date ? now()->diffInDays($event->event_date, false) : null;
@endphp
<div class="stat-row">
  <div class="stat-mini">
    <div class="stat-mini-lbl">Raised</div>
    <div class="stat-mini-val" style="color:var(--green);">₹{{ number_format($raised, 0) }}</div>
    <div style="margin-top:8px;">
      <div class="progress-label"><span>{{ $pct }}%</span><span>of ₹{{ number_format($goal, 0) }}</span></div>
      <div class="progress-bar"><div class="progress-fill" style="width:{{ $pct }}%;"></div></div>
    </div>
  </div>
  <div class="stat-mini">
    <div class="stat-mini-lbl">Registrations</div>
    <div class="stat-mini-val">{{ $regCount }}</div>
    <div class="stat-mini-sub">
      @if($event->max_participants) of {{ $event->max_participants }} max
      @else No limit
      @endif
    </div>
  </div>
  <div class="stat-mini">
    <div class="stat-mini-lbl">Days Away</div>
    <div class="stat-mini-val" style="{{ $days !== null && $days < 0 ? 'color:var(--text3)' : '' }}">
      {{ $days === null ? '—' : ($days < 0 ? 'Past' : ($days === 0 ? 'Today' : $days)) }}
    </div>
    <div class="stat-mini-sub">{{ $event->event_date?->format('d M Y') ?? 'No date set' }}</div>
  </div>
</div>

<div class="show-grid">

  {{-- LEFT COLUMN --}}
  <div>

    {{-- Cover + Title --}}
    <div class="card">
      @if($event->cover_image)
        <img src="{{ asset('storage/'.$event->cover_image) }}" class="event-cover" alt="">
      @else
        <div class="event-cover-placeholder">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        </div>
      @endif
      <div class="card-body">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;margin-bottom:14px;">
          <div>
            <h2 style="font-family:var(--mono);font-size:20px;font-weight:800;color:var(--text);letter-spacing:-.02em;line-height:1.2;margin-bottom:8px;">{{ $event->title }}</h2>
            <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
              @if($event->campaign?->category)
                <span style="display:inline-flex;align-items:center;gap:5px;padding:3px 10px;background:var(--a-lt);border:1px solid rgba(110,86,247,.2);border-radius:100px;font-size:11px;font-weight:700;color:var(--a);font-family:var(--mono);">
                  {{ $event->campaign->category->emoji ?? '' }} {{ $event->campaign->category->name }}
                </span>
              @endif
              <span class="status-pill pill-{{ $event->status }}">
                <span class="sp-dot"></span>{{ ucfirst($event->status) }}
              </span>
            </div>
          </div>
          <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-edit btn-sm" style="flex-shrink:0;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Edit
          </a>
        </div>
        @if($event->description)
          <div style="border-top:1px solid var(--border);padding-top:16px;">
            <div style="font-size:10px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.08em;font-family:var(--mono);margin-bottom:10px;">Description</div>
            <div class="desc-block">{{ $event->description }}</div>
          </div>
        @endif
      </div>
    </div>

    {{-- Event Details --}}
    <div class="card" style="animation-delay:.05s;">
      <div class="card-header">
        <div class="card-icon ci-amber">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        </div>
        <div>
          <div class="card-title">Event Details</div>
          <div class="card-subtitle">Date, time, location, and participation</div>
        </div>
      </div>
      <div class="card-body">
        <div class="detail-grid">
          <div class="detail-item">
            <div class="detail-key">Event Date</div>
            <div class="detail-val">{{ $event->event_date?->format('l, d F Y') ?? '—' }}</div>
          </div>
          <div class="detail-item">
            <div class="detail-key">Location / Venue</div>
            <div class="detail-val {{ $event->location ? '' : 'muted' }}">
              {{ $event->location ?: 'Not specified' }}
            </div>
          </div>
          <div class="detail-item">
            <div class="detail-key">Start Time</div>
            <div class="detail-val {{ $event->start_time ? '' : 'muted' }}">
              {{ $event->start_time ? date('g:i A', strtotime($event->start_time)) : 'Not set' }}
            </div>
          </div>
          <div class="detail-item">
            <div class="detail-key">End Time</div>
            <div class="detail-val {{ $event->end_time ? '' : 'muted' }}">
              {{ $event->end_time ? date('g:i A', strtotime($event->end_time)) : 'Not set' }}
            </div>
          </div>
          <div class="detail-item">
            <div class="detail-key">Fundraising Goal</div>
            <div class="detail-val" style="color:var(--a);font-weight:700;">₹{{ number_format($event->goal_amount, 0) }}</div>
          </div>
          <div class="detail-item">
            <div class="detail-key">Max Participants</div>
            <div class="detail-val">{{ $event->max_participants ? number_format($event->max_participants) : 'Unlimited' }}</div>
          </div>
        </div>
      </div>
    </div>

    {{-- Linked Campaign --}}
    <div class="card" style="animation-delay:.1s;">
      <div class="card-header">
        <div class="card-icon ci-green">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
        </div>
        <div>
          <div class="card-title">Linked Campaign</div>
          <div class="card-subtitle">The campaign this event is associated with</div>
        </div>
      </div>
      <div class="card-body">
        @if($event->campaign)
          <div class="campaign-mini">
            @if($event->campaign->cover_image)
              <img src="{{ asset('storage/'.$event->campaign->cover_image) }}" class="campaign-mini-thumb" alt="">
            @else
              <div class="campaign-mini-placeholder">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
              </div>
            @endif
            <div class="campaign-mini-info">
              <div class="campaign-mini-title">{{ $event->campaign->title }}</div>
              <div class="campaign-mini-meta">Goal ₹{{ number_format($event->campaign->goal_amount ?? 0) }} · {{ ucfirst($event->campaign->campaign_state ?? 'active') }}</div>
            </div>
            <a href="{{ route('admin.campaign.show', $event->campaign->id) }}" class="btn btn-edit btn-sm">
              View <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            </a>
          </div>
        @else
          <div style="text-align:center;padding:28px;color:var(--text3);font-size:13px;">
            <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin:0 auto 8px;display:block;opacity:.2;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            No campaign linked
          </div>
        @endif
      </div>
    </div>

    {{-- EVENT SETTINGS --}}
    <div class="card" style="animation-delay:.14s;">
      <div class="card-header">
        <div class="card-icon ci-purple">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><circle cx="12" cy="12" r="3"/></svg>
        </div>
        <div>
          <div class="card-title">Event Settings</div>
          <div class="card-subtitle">Toggle settings — changes save instantly</div>
        </div>
      </div>
      <div class="card-body">

        {{-- Allow Registrations --}}
        <div class="setting-row">
          <div class="setting-icon ci-green">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
          </div>
          <div class="setting-info">
            <div class="setting-name">Allow Registrations</div>
            <div class="setting-desc">Participants can sign up for this event</div>
          </div>
          <form method="POST" action="{{ route('admin.events.toggleSetting', $event) }}" id="form_allow_reg">
            @csrf
            <input type="hidden" name="field" value="allow_registrations">
            <div class="toggle-wrap" onclick="document.getElementById('chk_allow_reg').click()">
              <input type="checkbox" id="chk_allow_reg"
                     onchange="document.getElementById('form_allow_reg').submit()"
                     {{ $event->allow_registrations ? 'checked' : '' }}>
              <div class="toggle-track"></div>
            </div>
          </form>
        </div>

        {{-- Show on Campaign Page --}}
        <div class="setting-row">
          <div class="setting-icon ci-blue">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
          </div>
          <div class="setting-info">
            <div class="setting-name">Show on Campaign Page</div>
            <div class="setting-desc">Display this event on the linked campaign</div>
          </div>
          <form method="POST" action="{{ route('admin.events.toggleSetting', $event) }}" id="form_show_campaign">
            @csrf
            <input type="hidden" name="field" value="show_on_campaign">
            <div class="toggle-wrap" onclick="document.getElementById('chk_show_campaign').click()">
              <input type="checkbox" id="chk_show_campaign"
                     onchange="document.getElementById('form_show_campaign').submit()"
                     {{ $event->show_on_campaign ? 'checked' : '' }}>
              <div class="toggle-track"></div>
            </div>
          </form>
        </div>

        {{-- Send Notification Email --}}
        <div class="setting-row">
          <div class="setting-icon ci-amber">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
          </div>
          <div class="setting-info">
            <div class="setting-name">Send Notification Email</div>
              <div class="setting-desc">Also email the campaign creator when published (followers are always notified)</div>
          </div>
          <form method="POST" action="{{ route('admin.events.toggleSetting', $event) }}" id="form_send_notif">
            @csrf
            <input type="hidden" name="field" value="send_notification">
            <div class="toggle-wrap" onclick="document.getElementById('chk_send_notif').click()">
              <input type="checkbox" id="chk_send_notif"
                     onchange="document.getElementById('form_send_notif').submit()"
                     {{ ($event->send_notification ?? false) ? 'checked' : '' }}>
              <div class="toggle-track"></div>
            </div>
          </form>
        </div>

      </div>
    </div>

    {{-- Bottom action row --}}
    <div style="display:flex;gap:10px;margin-top:16px;flex-wrap:wrap;animation:fadeUp .4s .18s ease both;align-items:center;">
      <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-publish">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
        Edit Event
      </a>
      <a href="{{ route('admin.events.index') }}" class="btn btn-edit">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
        All Events
      </a>
      <form method="POST" action="{{ route('admin.events.destroy', $event) }}"
            onsubmit="return confirm('Permanently delete \'{{ addslashes($event->title) }}\'? This cannot be undone.')"
            style="margin-left:auto;">
        @csrf @method('DELETE')
        <button type="submit" class="btn btn-danger btn-sm">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
          Delete Event
        </button>
      </form>
    </div>

  </div>{{-- /left --}}

  {{-- RIGHT SIDEBAR --}}
  <div class="show-sidebar">

    {{-- Action Zone --}}
    <div class="action-zone">
      <div class="action-zone-header">
        <div class="action-zone-title">Actions</div>
      </div>
      <div class="action-zone-body">

        @if($event->status === 'pending')
          <form method="POST" action="{{ route('admin.events.approve', $event) }}">
            @csrf
            <button type="submit" class="btn btn-approve" style="width:100%;justify-content:center;">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
              Approve Event
            </button>
          </form>
          <form method="POST" action="{{ route('admin.events.reject', $event) }}">
            @csrf
            <button type="submit" class="btn btn-reject" style="width:100%;justify-content:center;">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
              Reject Event
            </button>
          </form>
          <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-edit" style="width:100%;justify-content:center;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Edit Event
          </a>
        @elseif($event->status === 'draft')
          <form method="POST" action="{{ route('admin.events.publish', $event) }}">
            @csrf
            <button type="submit" class="btn btn-publish" style="width:100%;justify-content:center;">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
              Publish Event
            </button>
          </form>
          <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-edit" style="width:100%;justify-content:center;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Continue Editing
          </a>
        @elseif($event->status === 'active')
          <form method="POST" action="{{ route('admin.events.draft', $event) }}">
            @csrf
            <button type="submit" class="btn btn-draft" style="width:100%;justify-content:center;">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
            Revert to Draft
          </form>
          <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-edit" style="width:100%;justify-content:center;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Edit Event
          </a>
        @else
          <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-publish" style="width:100%;justify-content:center;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Edit Event
          </a>
        @endif

        <div style="height:1px;background:var(--border);margin:2px 0;"></div>
        <form method="POST" action="{{ route('admin.events.destroy', $event) }}"
              onsubmit="return confirm('Permanently delete this event?')">
          @csrf @method('DELETE')
          <button type="submit" class="btn btn-danger btn-sm" style="width:100%;justify-content:center;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            Delete Event
          </button>
        </form>
      </div>
    </div>

    {{-- Summary card --}}
    <div class="card" style="animation-delay:.08s;">
      <div class="summary-hdr"><div class="summary-hdr-title">Event Summary</div></div>
      <div class="summary-body">
        <div class="summary-row">
          <div class="summary-key">Status</div>
          <span class="status-pill pill-{{ $event->status }}">
            <span class="sp-dot"></span>{{ ucfirst($event->status) }}
          </span>
        </div>
        <div class="summary-row">
          <div class="summary-key">Event ID</div>
          <div class="summary-val">#{{ $event->id }}</div>
        </div>
        <div class="summary-row">
          <div class="summary-key">Category</div>
          <div class="summary-val">{{ $event->campaign?->category ? (($event->campaign->category->emoji ?? '').' '.$event->campaign->category->name) : '—' }}</div>
        </div>
        <div class="summary-row">
          <div class="summary-key">Campaign</div>
          <div class="summary-val" style="font-size:11.5px;line-height:1.3;">{{ $event->campaign->title ?? '—' }}</div>
        </div>
        <div class="summary-row">
          <div class="summary-key">Date</div>
          <div class="summary-val">{{ $event->event_date?->format('d M Y') ?? '—' }}</div>
        </div>
        <div class="summary-row">
          <div class="summary-key">Time</div>
          <div class="summary-val">
            @if($event->start_time)
              {{ date('g:i A', strtotime($event->start_time)) }}
              @if($event->end_time) – {{ date('g:i A', strtotime($event->end_time)) }}@endif
            @else —
            @endif
          </div>
        </div>
        <div class="summary-row">
          <div class="summary-key">Goal</div>
          <div class="summary-val" style="color:var(--a);">₹{{ number_format($event->goal_amount, 0) }}</div>
        </div>
        <div class="summary-row">
          <div class="summary-key">Created</div>
          <div class="summary-val" style="font-size:11px;">{{ $event->created_at->format('d M Y, H:i') }}</div>
        </div>
        <div class="summary-row">
          <div class="summary-key">Last Updated</div>
          <div class="summary-val" style="font-size:11px;">{{ $event->updated_at->format('d M Y, H:i') }}</div>
        </div>
      </div>
    </div>

    {{-- Fundraising card --}}
    <div class="card" style="animation-delay:.14s;">
      <div class="summary-hdr"><div class="summary-hdr-title">Fundraising</div></div>
      <div class="summary-body">
        <div class="summary-row">
          <div class="summary-key">Raised</div>
          <div class="summary-val" style="color:var(--green);font-size:15px;">₹{{ number_format($raised, 0) }}</div>
        </div>
        <div class="summary-row">
          <div class="summary-key">Progress</div>
          <div style="margin-top:4px;">
            <div class="progress-label"><span style="color:var(--text);">{{ $pct }}%</span><span>₹{{ number_format($goal, 0) }} goal</span></div>
            <div class="progress-bar"><div class="progress-fill" style="width:{{ $pct }}%;"></div></div>
          </div>
        </div>
        <div class="summary-row">
          <div class="summary-key">Registrations</div>
          <div class="summary-val">{{ $regCount }}
            @if($event->max_participants)<span style="font-size:11px;color:var(--text3);"> / {{ $event->max_participants }}</span>@endif
          </div>
        </div>
      </div>
    </div>

  </div>{{-- /sidebar --}}
</div>{{-- /show-grid --}}
@endsection

@push('page_scripts')
<script>
(function(){
'use strict';
var html   = document.documentElement;
var toggle = document.getElementById('themeToggle');
var saved  = localStorage.getItem('adminTheme') || 'light';
if (saved === 'dark') { html.setAttribute('data-theme','dark'); toggle.checked = true; }
toggle.addEventListener('change', function(){
  var t = this.checked ? 'dark' : 'light';
  html.setAttribute('data-theme', t);
  localStorage.setItem('adminTheme', t);
});
document.getElementById('hamburger').addEventListener('click', function(){
  document.getElementById('sidebar').classList.toggle('open');
});
window.toggleDD = function(){ document.getElementById('avDD').classList.toggle('open'); };
document.addEventListener('click', function(e){
  var w = document.getElementById('avWrap');
  if (w && !w.contains(e.target)) document.getElementById('avDD').classList.remove('open');
});
})();
</script>
@endpush
