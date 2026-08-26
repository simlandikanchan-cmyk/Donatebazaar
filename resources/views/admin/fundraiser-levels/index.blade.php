@push('page_css')
@vite('resources/css/admin/entries/finance.css')
@endpush

@extends('layouts.admin')

@section('sidebar_fundraiser_levels', 'active')
@section('page_title', 'Fundraiser Levels')
@section('page_subtitle', 'Configure fundraiser progression & requirements')

@section('content')

@if(session('success'))
  <div style="display:flex;align-items:center;gap:8px;padding:12px 16px;border-radius:var(--r-sm);background:var(--green-lt);border:1px solid rgba(5,196,138,.2);color:var(--green);font-size:13px;font-weight:500;margin-bottom:18px;">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:15px;height:15px;flex-shrink:0"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    {{ session('success') }}
  </div>
@endif
@if(session('error'))
  <div style="display:flex;align-items:center;gap:8px;padding:12px 16px;border-radius:var(--r-sm);background:var(--red-lt);border:1px solid rgba(240,68,68,.2);color:var(--red);font-size:13px;font-weight:500;margin-bottom:18px;">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:15px;height:15px;flex-shrink:0"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    {{ session('error') }}
  </div>
@endif

<div class="hero">
  <div class="hero-left">
    <div class="hero-tag"><span class="hero-tag-dot"></span>Configuration</div>
    <div class="hero-name">Fundraiser Levels</div>
    <div class="hero-sub">Define progression tiers, requirements, and approval flows for fundraisers on the platform.</div>
    <div class="hero-badges">
      <span class="hero-badge hb-teal">{{ $stats['total'] }} levels</span>
      <span class="hero-badge hb-green">{{ $stats['auto'] }} auto-upgrade</span>
      @if($stats['approval'] > 0)
        <span class="hero-badge hb-amber">{{ $stats['approval'] }} need approval</span>
      @endif
    </div>
  </div>
  <div class="hero-right">
    <div class="hero-actions">
      <a href="{{ route('admin.dashboard') }}" class="hero-btn hero-btn-ghost">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m7 7l-7-7 7-7"/></svg>
        Dashboard
      </a>
      <a href="{{ route('admin.fundraiser-levels.create') }}" class="hero-btn hero-btn-primary">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        Add Level
      </a>
    </div>
  </div>
</div>

<div class="stats-grid" style="grid-template-columns:repeat(3,1fr);margin-bottom:24px;">
  <div class="stat">
    <div class="stat-icon si-teal"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 2L3 14h7l-1 8 10-12h-7l1-8z"/></svg></div>
    <div class="stat-body"><div class="stat-lbl">Total Levels</div><div class="stat-val sv-teal">{{ $stats['total'] }}</div><div class="stat-foot">Configured tiers</div></div>
  </div>
  <div class="stat">
    <div class="stat-icon si-green"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
    <div class="stat-body"><div class="stat-lbl">Auto-Upgrade</div><div class="stat-val sv-green">{{ $stats['auto'] }}</div><div class="stat-foot">No admin approval</div></div>
  </div>
  <div class="stat">
    <div class="stat-icon si-amber"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg></div>
    <div class="stat-body"><div class="stat-lbl">Need Approval</div><div class="stat-val sv-amber">{{ $stats['approval'] }}</div><div class="stat-foot">Requires review</div></div>
  </div>
</div>

<div class="table-card">
  <div class="card-head">
    <div class="card-head-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 2L3 14h7l-1 8 10-12h-7l1-8z"/></svg></div>
    <span class="card-head-title">Progression Levels</span>
    <span class="card-head-count">{{ $levels->count() }} configured levels</span>
  </div>

  @if($levels->isEmpty())
    <div class="empty-state">
      <div class="empty-icon-wrap">🏅</div>
      <h3>No levels configured</h3>
      <p style="color:var(--text3);font-size:13px;margin-top:6px;">Create your first fundraiser level to get started.</p>
      <a href="{{ route('admin.fundraiser-levels.create') }}" class="hero-btn hero-btn-primary" style="margin-top:18px;display:inline-flex;">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        Add Level
      </a>
    </div>
  @else
    <div class="level-list">
      @foreach($levels as $level)
      <div class="level-card">
        <div class="level-badge" style="background:{{ $level->badge_color ?? '#6366f1' }};">{{ $level->level_number }}</div>
        <div class="level-main">
          <div class="level-name">
            {{ $level->level_name }}
            @if($level->is_default)<span class="def-pill">Default</span>@endif
          </div>
          <div class="level-desc">{{ $level->description ?? '—' }}</div>
          <div class="level-attrs">
            <span class="attr"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 1v22M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>Max goal ₹{{ number_format($level->max_goal_amount, 0) }}</span>
            <span class="attr"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>{{ $level->max_active_campaigns }} active</span>
            <span class="attr"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 11l3 3L22 4M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>{{ $level->min_campaigns_completed }} completed</span>
            <span class="attr"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>{{ $level->min_raised_percent }}% raised</span>
            <span class="attr"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>KYC: {{ ucfirst($level->kyc_requirement) }}</span>
            <span class="apr-pill ap-{{ $level->requires_admin_approval?'yes':'no' }}">{{ $level->requires_admin_approval?'Approval req.':'Auto-upgrade' }}</span>
          </div>
        </div>
        <div class="actions">
          <a href="{{ route('admin.fundraiser-levels.edit', $level->id) }}" class="btn btn-secondary act-btn act-edit"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>Edit</a>
          <form method="POST" action="{{ route('admin.fundraiser-levels.destroy', $level->id) }}" data-confirm="Delete this level?">@csrf @method('DELETE')
            <button type="submit" class="btn btn-red act-btn act-del"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/></svg>Delete</button>
          </form>
        </div>
      </div>
      @endforeach
    </div>
  @endif
</div>

@push('page_styles')
<style>
.level-list{display:flex;flex-direction:column;gap:12px;padding:16px;}
.level-card{display:flex;align-items:flex-start;gap:14px;padding:16px;background:var(--surface2);border:1px solid var(--border);border-radius:var(--r-sm);transition:border-color var(--ease),box-shadow var(--ease);}
.level-card:hover{border-color:var(--border2);box-shadow:var(--sh-md);}
.level-badge{width:46px;height:46px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:18px;font-weight:700;color:#fff;flex-shrink:0;font-family:var(--mono);}
.level-main{flex:1;min-width:0;}
.level-name{font-size:15px;font-weight:700;color:var(--text);display:flex;align-items:center;gap:8px;flex-wrap:wrap;}
.def-pill{font-size:9.5px;font-weight:700;text-transform:uppercase;letter-spacing:.05em;font-family:var(--mono);padding:2px 7px;border-radius:100px;background:var(--a-lt);color:var(--a);border:1px solid rgba(37,99,235,.2);}
.level-desc{font-size:12.5px;color:var(--text3);margin-top:3px;line-height:1.5;}
.level-attrs{display:flex;flex-wrap:wrap;gap:8px;margin-top:12px;}
.attr{display:inline-flex;align-items:center;gap:5px;font-size:11px;font-family:var(--mono);color:var(--text2);background:var(--surface);border:1px solid var(--border2);padding:4px 9px;border-radius:100px;}
.attr svg{width:11px;height:11px;color:var(--text3);}
.apr-pill{font-size:10px;font-weight:700;font-family:var(--mono);text-transform:uppercase;letter-spacing:.04em;padding:3px 9px;border-radius:100px;}
.ap-yes{background:rgba(245,158,11,.12);color:#d97706;border:1px solid rgba(245,158,11,.22);}
.ap-no{background:rgba(5,196,138,.12);color:var(--green);border:1px solid rgba(5,196,138,.22);}
.actions{display:flex;align-items:center;gap:5px;flex-shrink:0;flex-wrap:wrap;}
.act-btn{display:inline-flex;align-items:center;gap:4px;padding:5px 11px;border-radius:7px;font-size:11.5px;font-weight:500;text-decoration:none;border:1px solid transparent;transition:all .15s;cursor:pointer;font-family:var(--font);white-space:nowrap;}
.act-btn svg{width:11px;height:11px;}
.act-edit{background:var(--blue-lt);color:var(--blue);border-color:rgba(59,130,246,.18);}
.act-edit:hover{background:var(--blue);color:#fff;transform:translateY(-1px);}
.act-del{background:var(--red-lt);color:var(--red);border-color:rgba(240,68,68,.18);}
.act-del:hover{background:var(--red);color:#fff;transform:translateY(-1px);}
.empty-state{padding:64px 24px;text-align:center;}
.empty-icon-wrap{width:64px;height:64px;border-radius:18px;background:var(--surface2);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;font-size:24px;margin:0 auto 16px;}
.empty-state h3{font-size:16px;font-weight:700;color:var(--text);margin-bottom:6px;}
@media(max-width:860px){.stats-grid{grid-template-columns:repeat(2,1fr)!important}}
@media(max-width:640px){.stats-grid{grid-template-columns:repeat(2,1fr)!important}.level-card{flex-direction:column;gap:10px;padding:14px}.level-badge{width:38px;height:38px;font-size:15px;border-radius:10px}.level-attrs{gap:6px}.actions{width:100%;justify-content:flex-end}}
@media(max-width:480px){.stats-grid{grid-template-columns:1fr!important}.level-card{padding:12px}.level-badge{width:36px;height:36px;font-size:14px}}
</style>
@endpush

@endsection
