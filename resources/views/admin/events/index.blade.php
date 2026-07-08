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
<style>
/* ── FILTER BAR ── */
.filter-bar{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);padding:16px 20px;box-shadow:var(--sh);margin-bottom:14px;display:flex;align-items:center;gap:10px;flex-wrap:wrap;animation:fadeUp .4s .1s ease both;}
.filter-inp,.filter-sel{height:36px;background:var(--surface2);border:1px solid var(--border2);border-radius:var(--r-sm);padding:0 12px;font-size:12.5px;color:var(--text);font-family:var(--font);outline:none;transition:border-color var(--ease),box-shadow var(--ease);}
.filter-inp{width:200px;}
.filter-inp:focus,.filter-sel:focus{border-color:var(--a);box-shadow:0 0 0 3px var(--a-glow);}
.filter-inp::placeholder{color:var(--text3);}
.filter-sel{cursor:pointer;min-width:140px;}
.filter-date{width:150px;}
.filter-btn{height:36px;padding:0 18px;background:linear-gradient(135deg,var(--a),var(--a2));color:#fff;border:none;border-radius:var(--r-sm);font-size:12.5px;font-weight:600;font-family:var(--font);cursor:pointer;transition:opacity var(--ease),transform var(--ease);box-shadow:0 3px 10px rgba(110,86,247,.3);}
.filter-btn:hover{opacity:.88;transform:translateY(-1px);}
.filter-clear{height:36px;padding:0 14px;background:transparent;border:1px solid var(--border2);border-radius:var(--r-sm);font-size:12px;color:var(--text3);font-family:var(--font);cursor:pointer;transition:all var(--ease);text-decoration:none;display:inline-flex;align-items:center;gap:5px;}
.filter-clear:hover{border-color:var(--red);color:var(--red);}
.filter-spacer{margin-left:auto;}
.filter-count{font-size:12px;color:var(--text3);font-family:var(--mono);white-space:nowrap;}
.filter-div{width:1px;height:22px;background:var(--border2);flex-shrink:0;}

/* topbar quick-filter search extras */
.search-wrap{position:relative;}
.s-inp-kbd{position:absolute;right:9px;top:50%;transform:translateY(-50%);font-size:9.5px;color:var(--text3);background:var(--surface);border:1px solid var(--border2);border-radius:4px;padding:1px 4px;font-family:var(--mono);pointer-events:none;}
.s-inp-kbd.hide{display:none;}
.s-inp-clear{position:absolute;right:6px;top:50%;transform:translateY(-50%);width:22px;height:22px;border:none;background:transparent;color:var(--text3);cursor:pointer;display:none;align-items:center;justify-content:center;border-radius:6px;}
.s-inp-clear:hover{background:var(--surface2);color:var(--text);}
.s-inp-clear svg{width:11px;height:11px;}
.s-inp-clear.show{display:flex;}

/* active filter chips */
.active-filters{display:flex;align-items:center;gap:8px;flex-wrap:wrap;margin-bottom:14px;animation:fadeUp .25s ease both;}
.af-label{font-size:11px;color:var(--text3);font-family:var(--mono);text-transform:uppercase;letter-spacing:.06em;}
.filter-chip{display:inline-flex;align-items:center;gap:6px;background:var(--a-lt);color:var(--a);border:1px solid rgba(110,86,247,.25);padding:4px 6px 4px 11px;border-radius:100px;font-size:11.5px;font-weight:600;text-decoration:none;}
.chip-x{width:16px;height:16px;border-radius:50%;background:rgba(110,86,247,.15);color:var(--a);display:flex;align-items:center;justify-content:center;transition:background .15s;}
.chip-x:hover{background:var(--a);color:#fff;}
.chip-x svg{width:8px;height:8px;}
.clear-all-btn{font-size:11.5px;font-weight:600;color:var(--text3);text-decoration:underline;text-underline-offset:2px;}
.clear-all-btn:hover{color:var(--red);}

/* ── SECTION HEADER ── */
.sec-hdr{display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;flex-wrap:wrap;gap:12px;}
.sec-ttl{font-family:var(--mono);font-size:16px;font-weight:700;color:var(--text);letter-spacing:-.02em;display:flex;align-items:center;gap:9px;}
.sec-ttl svg{width:17px;height:17px;color:var(--a);}
.sec-right{font-size:12px;color:var(--text3);font-family:var(--mono);}

/* clickable stat cards */
.stat{cursor:pointer;transition:transform .15s,box-shadow .2s,border-color .2s;text-decoration:none;display:flex;}
.stat:hover{transform:translateY(-2px);box-shadow:var(--sh-lg);}
.stat.stat-on{border-color:var(--a);box-shadow:0 0 0 3px var(--a-glow);}

/* ── EVENT LIST (table) ── */
.table-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh);overflow:hidden;animation:fadeUp .4s .18s ease both;}
.table-wrap{overflow-x:auto;}
table{width:100%;border-collapse:collapse;}
thead{background:var(--surface2);border-bottom:1px solid var(--border);}
thead th{padding:12px 16px;text-align:left;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.09em;color:var(--text3);font-family:var(--mono);white-space:nowrap;position:sticky;top:0;background:var(--surface2);z-index:2;}
tbody td{padding:14px 16px;border-bottom:1px solid var(--border);vertical-align:middle;}
tbody tr:last-child td{border-bottom:none;}
tbody tr{transition:background var(--ease);}
tbody tr:hover{background:var(--surface2);}
tbody tr.row-selected{background:var(--a-lt);}
.th-check,.td-check{width:36px;padding-right:0;}
.chk{width:14px;height:14px;accent-color:var(--a);cursor:pointer;}

/* event cell */
.ev-cell{display:flex;align-items:center;gap:13px;min-width:240px;}
.ev-thumb{width:52px;height:52px;border-radius:12px;object-fit:cover;flex-shrink:0;background:var(--surface3);border:1px solid var(--border);}
.ev-thumb-ph{width:52px;height:52px;border-radius:12px;flex-shrink:0;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,var(--a-lt),rgba(155,109,255,.18));color:var(--a);}
.ev-thumb-ph svg{width:20px;height:20px;}
.ev-title{font-size:13.5px;font-weight:600;color:var(--text);line-height:1.25;}
.ev-goal{font-size:11px;color:var(--text3);margin-top:3px;font-family:var(--mono);display:flex;align-items:center;gap:6px;flex-wrap:wrap;}
.ev-loc{display:inline-flex;align-items:center;gap:3px;color:var(--text2);}
.ev-loc svg{width:10px;height:10px;}

.cell-id{font-family:var(--mono);font-size:11px;color:var(--text3);font-weight:500;}
.campaign-chip{display:inline-flex;align-items:center;gap:6px;padding:4px 10px;border-radius:8px;font-size:11.5px;font-weight:600;color:var(--text2);background:var(--surface2);border:1px solid var(--border2);max-width:180px;}
.campaign-chip span{overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
.organizer-name{font-size:13px;font-weight:500;color:var(--text2);}
.cell-date{font-family:var(--mono);font-size:11.5px;color:var(--text3);line-height:1.5;}
.cell-date .dt-time{display:block;color:var(--text2);font-size:10.5px;}

/* progress */
.prog-wrap{min-width:130px;}
.prog-top{display:flex;align-items:baseline;justify-content:space-between;gap:8px;margin-bottom:5px;}
.prog-amt{font-family:var(--mono);font-size:11.5px;font-weight:700;color:var(--text2);}
.prog-pct{font-family:var(--mono);font-size:10px;color:var(--text3);}
.prog-bar{height:6px;border-radius:100px;background:var(--surface3);overflow:hidden;}
.prog-fill{height:100%;border-radius:100px;background:linear-gradient(90deg,var(--a),var(--a2));transition:width .6s cubic-bezier(.4,0,.2,1);}
.prog-fill.pf-full{background:linear-gradient(90deg,var(--green),#06d99a);}
.prog-fill.pf-none{background:var(--surface3);}

/* participants */
.participants-val{font-family:var(--mono);font-size:12px;font-weight:600;color:var(--text2);}
.participants-total{color:var(--text3);font-weight:400;}
.participants-full{display:inline-block;margin-left:6px;font-size:9px;font-weight:700;color:var(--red);background:var(--red-lt);padding:1px 6px;border-radius:100px;text-transform:uppercase;letter-spacing:.05em;}

/* status badge (definitions) */
.b-draft{background:rgba(107,114,128,.85);color:#fff;}
[data-theme="dark"] .b-draft{color:#cbd0e0;background:rgba(107,114,128,.5);}

/* actions */
.act-wrap{display:flex;gap:6px;align-items:center;flex-wrap:nowrap;}
.act-link{display:inline-flex;align-items:center;gap:5px;padding:6px 11px;border-radius:7px;font-size:11.5px;font-weight:500;color:var(--text2);background:var(--surface2);border:1px solid var(--border2);transition:all var(--ease);text-decoration:none;cursor:pointer;font-family:var(--font);}
.act-link:hover{background:var(--surface3);color:var(--text);transform:translateY(-1px);}
.act-link svg{width:11px;height:11px;}
.act-icon{width:30px;height:30px;padding:0;justify-content:center;gap:0;flex-shrink:0;position:relative;}
.act-icon svg{width:13px;height:13px;}
.act-icon[title]:hover::after{content:attr(title);position:absolute;bottom:calc(100% + 6px);left:50%;transform:translateX(-50%);background:var(--text);color:#fff;font-size:10.5px;font-weight:600;padding:4px 8px;border-radius:6px;white-space:nowrap;z-index:30;pointer-events:none;box-shadow:0 4px 12px rgba(0,0,0,.2);}
.act-icon[title]:hover::before{content:'';position:absolute;bottom:100%;left:50%;transform:translateX(-50%);border:4px solid transparent;border-top-color:var(--text);margin-bottom:-2px;z-index:30;pointer-events:none;}
.act-edit{color:var(--a);background:var(--a-lt);border-color:rgba(110,86,247,.2);}
.act-edit:hover{background:var(--a);color:#fff;border-color:var(--a);}
.act-approve{color:#059669;background:var(--green-lt);border-color:rgba(5,196,138,.25);}
.act-approve:hover{background:var(--green);color:#fff;border-color:var(--green);}
.act-reject{color:var(--red);background:var(--red-lt);border-color:rgba(240,68,68,.25);}
.act-reject:hover{background:var(--red);color:#fff;border-color:var(--red);}
.act-bell{color:var(--text3);background:var(--surface2);border-color:var(--border2);}
.act-bell:hover{color:var(--text2);background:var(--surface3);}
.act-bell.is-on{color:#059669;background:var(--green-lt);border-color:rgba(5,196,138,.3);}
.act-bell.is-on:hover{color:#059669;background:var(--green-lt);}
.act-form{display:inline;}
.act-form button{font-family:var(--font);line-height:normal;}

/* empty */
.empty-row td{text-align:center;padding:56px 20px;}
.empty-inner{display:flex;flex-direction:column;align-items:center;gap:10px;}
.empty-inner svg{width:48px;height:48px;color:var(--text3);opacity:.25;}
.empty-inner strong{font-family:var(--mono);font-size:15px;font-weight:700;color:var(--text2);}
.empty-inner span{font-size:13px;color:var(--text3);}
.empty-inner .reset-btn{display:inline-flex;align-items:center;gap:6px;height:34px;padding:0 15px;background:var(--surface2);border:1px solid var(--border2);border-radius:var(--r-sm);font-size:12px;font-weight:600;color:var(--text2);cursor:pointer;font-family:var(--font);transition:all .15s;text-decoration:none;margin-top:4px;}
.empty-inner .reset-btn:hover{border-color:var(--a);color:var(--a);}

/* flash */
.flash{padding:12px 16px;border-radius:var(--r-sm);margin-bottom:20px;font-size:13px;font-weight:500;display:flex;align-items:center;gap:10px;animation:fadeUp .3s ease both;}
.flash-success{background:var(--green-lt);border:1px solid rgba(5,196,138,.25);color:#059669;}
.flash-error{background:var(--red-lt);border:1px solid rgba(240,68,68,.25);color:var(--red);}
.flash svg{width:14px;height:14px;flex-shrink:0;}

/* bulk bar */
.bulk-bar{position:sticky;bottom:16px;left:0;right:0;margin:16px auto 0;max-width:480px;background:var(--text);color:#fff;border-radius:100px;box-shadow:0 12px 32px rgba(0,0,0,.25);padding:8px 8px 8px 20px;display:flex;align-items:center;justify-content:space-between;gap:14px;transform:translateY(120%);opacity:0;transition:transform .25s cubic-bezier(.4,0,.2,1),opacity .25s ease;z-index:20;}
.bulk-bar.show{transform:translateY(0);opacity:1;}
.bulk-count{font-size:12.5px;font-weight:600;font-family:var(--mono);white-space:nowrap;}
.bulk-acts{display:flex;align-items:center;gap:6px;}
.bulk-btn{display:inline-flex;align-items:center;gap:5px;height:32px;padding:0 13px;border-radius:100px;font-size:11.5px;font-weight:600;border:none;cursor:pointer;font-family:var(--font);transition:opacity .15s;white-space:nowrap;}
.bulk-btn svg{width:11px;height:11px;}
.bulk-btn-del{background:var(--red);color:#fff;}
.bulk-btn-cancel{background:rgba(255,255,255,.12);color:#fff;}
.bulk-btn:hover{opacity:.85;}

/* ── MOBILE CARDS ── */
.ev-cards{display:none;}
.ev-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);padding:16px;box-shadow:var(--sh);margin-bottom:12px;animation:fadeUp .4s ease both;position:relative;}
.ev-card.row-selected{background:var(--a-lt);border-color:var(--a);}
.ev-card-check{position:absolute;top:14px;right:14px;}
.ev-card-top{display:flex;gap:13px;align-items:flex-start;}
.ev-card .ev-thumb,.ev-card .ev-thumb-ph{width:56px;height:56px;}
.ev-card-body{flex:1;min-width:0;}
.ev-card-meta{display:flex;align-items:center;gap:8px;margin-top:8px;flex-wrap:wrap;}
.ev-card-stats{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-top:14px;padding-top:14px;border-top:1px solid var(--border);}
.ev-card-stat{display:flex;flex-direction:column;gap:3px;}
.ev-card-stat .lbl{font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--text3);font-family:var(--mono);}
.ev-card-stat .val{font-size:12.5px;font-weight:600;color:var(--text2);font-family:var(--mono);}
.ev-card-actions{display:flex;gap:8px;margin-top:14px;flex-wrap:wrap;}
.ev-card-actions .act-link{flex:1;justify-content:center;}

@media(max-width:860px){.search-wrap{display:none}}
@media(max-width:980px){
  .table-card{display:none;}
  .ev-cards{display:block;}
}
@media(max-width:600px){
  .filter-bar{flex-direction:column;align-items:stretch}
  .filter-inp,.filter-sel,.filter-date{width:100%}
  .filter-spacer{display:none;}
  .bulk-bar{margin-left:12px;margin-right:12px;}
}
</style>
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
          <th class="th-check"><input type="checkbox" class="chk" id="selectAll" onchange="toggleSelectAll(this)" aria-label="Select all"></th>
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
            <td class="td-check"><input type="checkbox" class="chk row-check" onchange="toggleRowSelect(this)" aria-label="Select {{ $event->title }}"></td>
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
                <a href="{{ route('admin.events.show', $event->id) }}" class="act-link act-icon" title="View" aria-label="View">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </a>
                <a href="{{ route('admin.events.edit', $event->id) }}" class="act-link act-edit act-icon" title="Edit" aria-label="Edit">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </a>
                <form class="act-form" method="POST" action="{{ route('admin.events.toggleSetting', $event->id) }}">
                  @csrf
                  <input type="hidden" name="field" value="send_notification">
                  <button type="submit" class="act-link act-bell act-icon {{ $event->send_notification ? 'is-on' : '' }}" title="{{ $event->send_notification ? 'Notifications ON — also emails the campaign creator when published (followers are always notified)' : 'Notifications OFF — toggle to also email the campaign creator on publish' }}" aria-label="{{ $event->send_notification ? 'Notify On' : 'Notify' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                  </button>
                </form>
                @if($event->status === 'pending')
                  <form class="act-form" method="POST" action="{{ route('admin.events.approve', $event->id) }}" onsubmit="return confirm('Approve and publish this event?')">
                    @csrf
                    <button type="submit" class="act-link act-approve act-icon" title="Approve" aria-label="Approve">
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 6L9 17l-5-5"/></svg>
                    </button>
                  </form>
                  <form class="act-form" method="POST" action="{{ route('admin.events.reject', $event->id) }}" onsubmit="return confirm('Reject this event?')">
                    @csrf
                    <button type="submit" class="act-link act-reject act-icon" title="Reject" aria-label="Reject">
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18 6L6 18M6 6l12 12"/></svg>
                    </button>
                  </form>
                @elseif($event->status === 'draft')
                  <form class="act-form" method="POST" action="{{ route('admin.events.publish', $event->id) }}">
                    @csrf
                    <button type="submit" class="act-link act-approve act-icon" title="Publish" aria-label="Publish">
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </button>
                  </form>
                @elseif($event->status === 'active')
                  <form class="act-form" method="POST" action="{{ route('admin.events.draft', $event->id) }}" onsubmit="return confirm('Revert this event to draft? It will no longer be public.')">
                    @csrf
                    <button type="submit" class="act-link act-reject act-icon" title="Unpublish" aria-label="Unpublish">
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 3h22M9 3h6"/></svg>
                    </button>
                  </form>
                @endif
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
      <input type="checkbox" class="chk row-check ev-card-check" onchange="toggleRowSelect(this)" aria-label="Select {{ $event->title }}">
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
        <a href="{{ route('admin.events.show', $event->id) }}" class="act-link">View</a>
        <a href="{{ route('admin.events.edit', $event->id) }}" class="act-link act-edit">Edit</a>
        <form class="act-form" method="POST" action="{{ route('admin.events.toggleSetting', $event->id) }}">
          @csrf
          <input type="hidden" name="field" value="send_notification">
          <button type="submit" class="act-link act-bell {{ $event->send_notification ? 'is-on' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
            {{ $event->send_notification ? 'Notify On' : 'Notify' }}
          </button>
        </form>
        @if($event->status === 'pending')
          <form class="act-form" method="POST" action="{{ route('admin.events.approve', $event->id) }}">@csrf<button type="submit" class="act-link act-approve">Approve</button></form>
          <form class="act-form" method="POST" action="{{ route('admin.events.reject', $event->id) }}">@csrf<button type="submit" class="act-link act-reject">Reject</button></form>
        @elseif($event->status === 'draft')
          <form class="act-form" method="POST" action="{{ route('admin.events.publish', $event->id) }}">@csrf<button type="submit" class="act-link act-approve">Publish</button></form>
        @elseif($event->status === 'active')
          <form class="act-form" method="POST" action="{{ route('admin.events.draft', $event->id) }}">@csrf<button type="submit" class="act-link act-reject">Unpublish</button></form>
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
    <button type="button" class="bulk-btn bulk-btn-del" onclick="openBulkConfirm()">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6"/></svg>Delete
    </button>
    <button type="button" class="bulk-btn bulk-btn-cancel" onclick="clearSelection()">Cancel</button>
  </div>
</div>

<div class="pagination-wrap">{{ $events->links('vendor.pagination.admin') }}</div>
@endsection

@push('page_scripts')
<script>
(function(){
'use strict';

// ---------- Quick page-local search (topbar) ----------
var searchEl = document.getElementById('liveSearch');
var clearBtn = document.getElementById('liveSearchClear');
var kbdEl = document.getElementById('liveSearchKbd');

function applyLiveFilter(){
  var q = searchEl.value.toLowerCase().trim();
  clearBtn.classList.toggle('show', q.length>0);
  kbdEl.classList.toggle('hide', q.length>0);
  document.querySelectorAll('#eventsTable tbody tr[data-name], .ev-cards .ev-card[data-name]').forEach(function(row){
    row.style.display = (!q || row.dataset.name.includes(q)) ? '' : 'none';
  });
}

if (searchEl) {
  var st;
  searchEl.addEventListener('input', function(){
    clearTimeout(st);
    st = setTimeout(applyLiveFilter, 160);
  });
  // Enter commits as a real server-side search (reloads with full dataset + pagination)
  searchEl.addEventListener('keydown', function(e){
    if(e.key === 'Enter'){
      e.preventDefault();
      var fs = document.getElementById('filterSearch');
      if(fs){ fs.value = searchEl.value; document.getElementById('filterForm').submit(); }
    }
  });
  applyLiveFilter();
}

window.clearLiveSearch = function(){
  searchEl.value='';
  searchEl.focus();
  applyLiveFilter();
};
clearBtn.addEventListener('click', window.clearLiveSearch);

document.addEventListener('keydown', function(e){
  var tag = (e.target.tagName||'').toLowerCase();
  if(e.key==='/' && tag!=='input' && tag!=='textarea'){
    e.preventDefault();
    searchEl.focus();
  }
  if(e.key==='Escape' && tag==='input' && e.target.id==='liveSearch'){
    window.clearLiveSearch();
  }
});

// ---------- Client-side sort (current page only) ----------
document.getElementById('sortSelect').addEventListener('change', function(){
  var v = this.value;
  if(v==='default') return;
  var cmp;
  if(v==='date-soon')       cmp = function(a,b){ return Number(a.dataset.ts) - Number(b.dataset.ts); };
  else if(v==='date-far')   cmp = function(a,b){ return Number(b.dataset.ts) - Number(a.dataset.ts); };
  else if(v==='raised-high')cmp = function(a,b){ return Number(b.dataset.raised) - Number(a.dataset.raised); };
  else if(v==='raised-low') cmp = function(a,b){ return Number(a.dataset.raised) - Number(b.dataset.raised); };
  else if(v==='participants')cmp= function(a,b){ return Number(b.dataset.participants) - Number(a.dataset.participants); };
  else if(v==='title')      cmp = function(a,b){ return (a.dataset.name||'').localeCompare(b.dataset.name||''); };
  else return;

  var tbody = document.querySelector('#eventsTable tbody');
  var rows = Array.from(tbody.querySelectorAll('tr[data-id]'));
  rows.sort(cmp).forEach(function(r){ tbody.appendChild(r); });

  var cardsWrap = document.querySelector('.ev-cards');
  var cards = Array.from(cardsWrap.querySelectorAll('.ev-card[data-id]'));
  cards.sort(cmp).forEach(function(c){ cardsWrap.appendChild(c); });
});

// ---------- Bulk selection ----------
var selected = new Set();

window.toggleRowSelect = function(cb){
  var el = cb.closest('[data-id]');
  var id = el.dataset.id;
  if(cb.checked){ selected.add(id); el.classList.add('row-selected'); }
  else{ selected.delete(id); el.classList.remove('row-selected'); }
  syncCheckboxesForId(id, cb.checked);
  updateBulkBar();
  updateSelectAllState();
};

function syncCheckboxesForId(id, checked){
  document.querySelectorAll('[data-id="'+id+'"] .row-check').forEach(function(cb){ cb.checked = checked; });
}

window.toggleSelectAll = function(cb){
  var visibleRows = Array.from(document.querySelectorAll('#eventsTable tbody tr[data-id]')).filter(function(r){ return r.style.display !== 'none'; });
  visibleRows.forEach(function(r){
    var id = r.dataset.id;
    var rowCb = r.querySelector('.row-check');
    if(cb.checked){ selected.add(id); r.classList.add('row-selected'); if(rowCb) rowCb.checked=true; }
    else{ selected.delete(id); r.classList.remove('row-selected'); if(rowCb) rowCb.checked=false; }
    syncCheckboxesForId(id, cb.checked);
  });
  updateBulkBar();
};

function updateSelectAllState(){
  var selAll = document.getElementById('selectAll');
  if(!selAll) return;
  var visibleRows = Array.from(document.querySelectorAll('#eventsTable tbody tr[data-id]')).filter(function(r){ return r.style.display !== 'none'; });
  selAll.checked = visibleRows.length>0 && visibleRows.every(function(r){ return selected.has(r.dataset.id); });
}

function updateBulkBar(){
  var bar = document.getElementById('bulkBar');
  document.getElementById('bulkCount').textContent = selected.size + ' selected';
  bar.classList.toggle('show', selected.size>0);
}

window.clearSelection = function(){
  selected.clear();
  document.querySelectorAll('.row-check').forEach(function(cb){ cb.checked=false; });
  document.querySelectorAll('.row-selected').forEach(function(r){ r.classList.remove('row-selected'); });
  var selAll = document.getElementById('selectAll');
  if(selAll) selAll.checked=false;
  updateBulkBar();
};

window.openBulkConfirm = function(){
  if(selected.size===0) return;
  if(!confirm('Delete '+selected.size+' selected event(s)? This cannot be undone.')) return;
  var tokenInput = document.querySelector('.act-form input[name="_token"]');
  var token = tokenInput ? tokenInput.value : '';
  var ids = Array.from(selected);
  var reqs = ids.map(function(id){
    var el = document.querySelector('[data-id="'+id+'"][data-delete-url]');
    var url = el ? el.dataset.deleteUrl : null;
    if(!url) return Promise.resolve();
    var fd = new FormData();
    fd.append('_token', token);
    fd.append('_method', 'DELETE');
    return fetch(url, { method:'POST', body:fd, headers:{'X-Requested-With':'XMLHttpRequest'} });
  });
  Promise.all(reqs).then(function(){ window.location.reload(); }).catch(function(){ window.location.reload(); });
};

})();
</script>
@endpush