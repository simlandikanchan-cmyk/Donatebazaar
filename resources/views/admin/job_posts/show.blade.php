@extends('layouts.admin')

@section('sidebar_job_posts', 'active')
@section('page_title', $jobPost->title)
@section('page_subtitle', 'Job details')

@push('page_styles')
<style>
/* ── PAGE ACTIONS ── */
.page-actions{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:24px;flex-wrap:wrap;animation:fadeUp .35s ease both;}
.page-actions-right{display:flex;gap:8px;align-items:center;flex-wrap:wrap;}
.btn-back{display:inline-flex;align-items:center;gap:7px;padding:9px 16px;border-radius:var(--r-sm);font-size:12.5px;font-weight:600;border:1px solid var(--border2);background:var(--surface);color:var(--text2);cursor:pointer;transition:all var(--ease);text-decoration:none;font-family:var(--font);}
.btn-back:hover{background:var(--surface2);color:var(--text);}
.btn-back svg{width:13px;height:13px;}

/* ── HERO META ── */
.hero-meta{display:flex;flex-wrap:wrap;gap:8px;margin-bottom:14px;}
.hero-chip{display:inline-flex;align-items:center;gap:5px;padding:5px 11px;border-radius:100px;font-size:11px;font-weight:600;font-family:var(--mono);}
.hc-type{background:var(--a-lt);border:1px solid rgba(110,86,247,.3);color:#c4b5fd;}
.hc-loc{background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.14);color:rgba(255,255,255,.75);}
.hc-sal{background:rgba(5,196,138,.2);border:1px solid rgba(5,196,138,.3);color:#6ee7b7;}
.hc-exp{background:rgba(59,130,246,.2);border:1px solid rgba(59,130,246,.3);color:#93c5fd;}
.hc-vac{background:rgba(245,158,11,.2);border:1px solid rgba(245,158,11,.3);color:#fde68a;}
.hero-chip svg{width:11px;height:11px;}
.hero-badge.hb-active{background:rgba(5,196,138,.2);color:#6ee7b7;border:1px solid rgba(5,196,138,.3);}
.hero-badge.hb-draft{background:rgba(107,114,128,.2);color:#d1d5db;border:1px solid rgba(107,114,128,.3);}
.hero-badge.hb-closed{background:rgba(240,68,68,.2);color:#fca5a5;border:1px solid rgba(240,68,68,.3);}
.hero-stat-card{padding:14px 20px;background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.12);border-radius:var(--r-sm);text-align:center;min-width:96px;}
.hsc-val{font-family:var(--mono);font-size:26px;font-weight:800;line-height:1;letter-spacing:-.02em;}
.hsc-lbl{font-size:10px;font-family:var(--mono);text-transform:uppercase;letter-spacing:.1em;margin-top:4px;color:rgba(255,255,255,.5);}

/* ── STAT STRIP ── */
.stat-strip{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:24px;}
.stat-strip .stat{animation-delay:0s;}
.stat-strip .stat:nth-child(1)::after{background:linear-gradient(90deg,var(--amber),#f97316);}
.stat-strip .stat:nth-child(2)::after{background:linear-gradient(90deg,var(--green),#34d399);}
.stat-strip .stat:nth-child(3)::after{background:linear-gradient(90deg,var(--red),#f87171);}
.stat-strip .stat:nth-child(4)::after{background:linear-gradient(90deg,var(--blue),#6366f1);}

/* ── CONTENT GRID ── */
.content-grid{display:grid;grid-template-columns:1fr 300px;gap:20px;align-items:start;}

/* ── CARDS ── */
.card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh);overflow:hidden;animation:fadeUp .4s ease both;}
.card+.card{margin-top:16px;}
.card-header{display:flex;align-items:center;justify-content:space-between;gap:12px;padding:16px 22px;border-bottom:1px solid var(--border);}
.card-header-left{display:flex;align-items:center;gap:10px;}
.card-hico{width:36px;height:36px;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.card-hico svg{width:16px;height:16px;}
.ci-purple{background:var(--a-lt);color:var(--a);}
.ci-green{background:var(--green-lt);color:var(--green);}
.ci-blue{background:var(--blue-lt);color:var(--blue);}
.ci-amber{background:var(--amber-lt);color:var(--amber);}
.ci-red{background:var(--red-lt);color:var(--red);}
.card-title{font-family:var(--mono);font-size:13.5px;font-weight:700;color:var(--text);letter-spacing:-.01em;}
.card-sub{font-size:11px;color:var(--text3);font-family:var(--mono);margin-top:1px;}
.card-body{padding:22px;}

/* ── DESCRIPTION ── */
.desc-body{font-size:14px;color:var(--text2);line-height:1.85;white-space:pre-wrap;word-break:break-word;}

/* ── TABLE ── */
.table-wrap{overflow-x:auto;}
.table-wrap table{width:100%;border-collapse:collapse;}
.table-wrap thead{background:var(--surface2);border-bottom:1px solid var(--border);}
.table-wrap thead th{padding:10px 14px;text-align:left;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.09em;color:var(--text3);font-family:var(--mono);white-space:nowrap;}
.table-wrap thead th:first-child{padding-left:22px;}
.table-wrap thead th:last-child{padding-right:22px;text-align:right;}
.table-wrap tbody td{padding:13px 14px;border-bottom:1px solid var(--border);vertical-align:middle;font-size:13px;}
.table-wrap tbody td:first-child{padding-left:22px;}
.table-wrap tbody td:last-child{padding-right:22px;text-align:right;}
.table-wrap tbody tr:last-child td{border-bottom:none;}
.table-wrap tbody tr{transition:background var(--ease);}
.table-wrap tbody tr:hover{background:var(--surface2);}
.td-mono{font-family:var(--mono);font-size:11.5px;color:var(--text3);}
.td-name{font-weight:600;color:var(--text);}
.td-sub{font-size:11px;color:var(--text3);font-family:var(--mono);margin-top:1px;}

/* ── EMPTY STATE ── */
.empty-state{padding:48px 20px;text-align:center;}
.empty-icon{width:52px;height:52px;border-radius:14px;background:var(--surface2);border:1px solid var(--border2);display:flex;align-items:center;justify-content:center;margin:0 auto 14px;}
.empty-icon svg{width:22px;height:22px;color:var(--text3);}
.empty-ttl{font-family:var(--mono);font-size:15px;font-weight:700;color:var(--text2);margin-bottom:5px;}
.empty-sub{font-size:13px;color:var(--text3);}

/* ── SIDE STACK ── */
.side-stack{position:sticky;top:80px;display:flex;flex-direction:column;gap:16px;}

/* ── BUTTONS ── */
.btn{display:inline-flex;align-items:center;justify-content:center;gap:7px;padding:11px 20px;border-radius:var(--r-sm);font-size:13px;font-weight:600;cursor:pointer;border:none;transition:all var(--ease);font-family:var(--font);width:100%;}
.btn:active{transform:scale(.97);}
.btn svg{width:14px;height:14px;}
.btn-primary{background:linear-gradient(135deg,var(--a),var(--a2));color:#fff;box-shadow:0 4px 18px rgba(110,86,247,.35);}
.btn-primary:hover{transform:translateY(-2px);box-shadow:0 8px 28px rgba(110,86,247,.45);}
.btn-secondary{background:var(--surface2);color:var(--text2);border:1px solid var(--border2);}
.btn-secondary:hover{background:var(--surface3);color:var(--text);}
.btn-danger{background:var(--red-lt);color:var(--red);border:1px solid rgba(240,68,68,.2);}
.btn-danger:hover{background:var(--red);color:#fff;border-color:var(--red);}
.btn-edit{background:var(--a-lt);color:var(--a);border:1px solid rgba(110,86,247,.2);}
.btn-edit:hover{background:var(--a);color:#fff;border-color:var(--a);}

/* ── ACTION BUTTONS ── */
.act-btns{display:flex;align-items:center;gap:4px;}
.act-btn{display:inline-flex;align-items:center;gap:4px;padding:5px 10px;border-radius:7px;font-size:11.5px;font-weight:500;cursor:pointer;border:1px solid transparent;transition:all var(--ease);text-decoration:none;font-family:var(--font);white-space:nowrap;}
.act-btn svg{width:11px;height:11px;}
.act-btn:active{transform:scale(0.96);}
.ab-view{background:var(--surface2);color:var(--text2);border-color:var(--border2);}
.ab-view:hover{background:var(--a-lt);color:var(--a);border-color:rgba(110,86,247,.2);}
.ab-download{background:var(--green-lt);color:var(--green);border-color:rgba(5,196,138,.2);}
.ab-download:hover{background:var(--green);color:#fff;border-color:var(--green);}

/* ── BADGE SUB-CLASSES ── */
.b-shortlisted{background:rgba(5,196,138,.85);color:#fff;}
.b-hired{background:rgba(110,86,247,.85);color:#fff;}

/* ── SIDE CARD ── */
.side-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh);overflow:hidden;animation:fadeUp .4s ease both;}

/* ── INFO ROWS ── */
.info-list{padding:0 18px;}
.info-row{display:flex;align-items:center;justify-content:space-between;gap:10px;padding:10px 0;border-bottom:1px solid var(--border);}
.info-row:last-child{border-bottom:none;}
.info-lbl{font-size:11.5px;color:var(--text3);font-family:var(--mono);}
.info-val{font-size:12.5px;font-weight:600;color:var(--text2);text-align:right;}
.info-val.green{color:var(--green);}
.info-val.amber{color:var(--amber);}
.info-val.red{color:var(--red);}

/* ── BUTTON STACK ── */
.btn-stack{display:flex;flex-direction:column;gap:8px;padding:18px;}

/* ── DANGER ZONE ── */
.danger-zone{background:linear-gradient(135deg,rgba(240,68,68,.05),rgba(240,68,68,.02));border:1px solid rgba(240,68,68,.18);border-radius:var(--r);padding:18px;animation:fadeUp .4s .18s ease both;}
.danger-hdr{display:flex;align-items:center;gap:8px;margin-bottom:8px;}
.danger-hdr svg{width:14px;height:14px;color:var(--red);}
.danger-hdr span{font-size:11px;font-weight:700;color:var(--red);font-family:var(--mono);text-transform:uppercase;letter-spacing:.1em;}
.danger-desc{font-size:12px;color:var(--text3);line-height:1.5;margin-bottom:12px;}

/* ── RESPONSIVE ── */
@media(max-width:1100px){.content-grid{grid-template-columns:1fr}.side-stack{position:static}}
@media(max-width:860px){.stat-strip{grid-template-columns:repeat(2,1fr)}}
@media(max-width:600px){.stat-strip{grid-template-columns:1fr 1fr}.hero{flex-direction:column}.hero-right{width:100%;flex-direction:row}.hero-stat-card{flex:1}}
</style>
@endpush

@section('content')

<div id="deleteOverlay" class="overlay" role="dialog" aria-modal="true">
  <div class="modal">
    <button type="button" class="modal-x" onclick="closeDelete()">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
    <div class="modal-head">
      <div class="modal-ico">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
      </div>
      <div>
        <div class="modal-ttl">Delete Job Post</div>
        <div class="modal-sub">This action cannot be undone</div>
      </div>
    </div>
    <div class="modal-body">
      Are you sure you want to permanently delete <strong>"{{ $jobPost->title }}"</strong>?
      All <strong>{{ $jobPost->applications()->count() }} application(s)</strong> linked to this post will also be removed.
    </div>
    <div class="modal-acts">
      <button type="button" onclick="closeDelete()" class="modal-btn modal-cancel">Cancel</button>
      <form action="{{ route('admin.job_posts.destroy', $jobPost->id) }}" method="POST" style="flex:1;">
        @csrf @method('DELETE')
        <button type="submit" class="modal-btn modal-red" style="width:100%;">🗑 Delete Permanently</button>
      </form>
    </div>
  </div>
</div>

<div class="breadcrumb">
  <a href="{{ route('admin.dashboard') }}">Dashboard</a>
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
  <a href="{{ route('admin.job_posts.index') }}">Job Posts</a>
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
  <span class="cur">{{ Str::limit($jobPost->title, 40) }}</span>
</div>

<div class="page-actions">
  <a href="{{ route('admin.job_posts.index') }}" class="btn-back">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
    All Listings
  </a>
  <div class="page-actions-right">
    <a href="{{ route('admin.job_posts.edit', $jobPost->id) }}" class="btn btn-edit" style="width:auto;padding:9px 18px;font-size:12.5px;">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
      Edit Post
    </a>
    <button type="button" onclick="openDelete()" class="btn btn-danger" style="width:auto;padding:9px 18px;font-size:12.5px;">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
      Delete
    </button>
  </div>
</div>

@php
  $isExpired = $jobPost->application_deadline && \Carbon\Carbon::parse($jobPost->application_deadline)->isPast();
  $statusKey = ($jobPost->status === 'closed' || $isExpired) ? 'closed' : ($jobPost->status === 'draft' ? 'draft' : 'active');
  $appCount  = $jobPost->applications()->count();
  $pendCount = $jobPost->applications()->where('status','pending')->count();
  $accCount  = $jobPost->applications()->where('status','shortlisted')->orWhere('status','accepted')->count();
  $rejCount  = $jobPost->applications()->where('status','rejected')->count();
@endphp

<div class="hero">
  <div class="hero-left">
    <div class="hero-tag"><span class="hero-tag-dot"></span>Job Board · #{{ $jobPost->id }}</div>
    <div class="hero-name">{{ $jobPost->title }}</div>
    <div class="hero-meta">
      @if($jobPost->type)
      <span class="hero-chip hc-type">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        {{ ucfirst($jobPost->type) }}
      </span>
      @endif
      @if($jobPost->location)
      <span class="hero-chip hc-loc">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        {{ $jobPost->location }}
      </span>
      @endif
      @if($jobPost->salary)
      <span class="hero-chip hc-sal">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ $jobPost->salary }}
      </span>
      @endif
      @if($jobPost->experience_required)
      <span class="hero-chip hc-exp">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        {{ $jobPost->experience_required }}
      </span>
      @endif
      @if($jobPost->vacancies)
      <span class="hero-chip hc-vac">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
        {{ $jobPost->vacancies }} {{ Str::plural('vacancy', $jobPost->vacancies) }}
      </span>
      @endif
    </div>
    <div class="hero-badges">
      @if($statusKey === 'active')
        <span class="hero-badge hb-active">● Active</span>
      @elseif($statusKey === 'draft')
        <span class="hero-badge hb-draft">✎ Draft</span>
      @else
        <span class="hero-badge hb-closed">✕ Closed</span>
      @endif
      @if($jobPost->featured)<span class="hero-badge" style="background:rgba(245,158,11,.2);color:#fde68a;border:1px solid rgba(245,158,11,.3);">★ Featured</span>@endif
      @if($jobPost->is_remote)<span class="hero-badge" style="background:rgba(110,86,247,.2);color:#c4b5fd;border:1px solid rgba(110,86,247,.3);">🌐 Remote</span>@endif
      <span class="hero-badge" style="background:rgba(255,255,255,.06);color:rgba(255,255,255,.5);border:1px solid rgba(255,255,255,.1);">Posted {{ $jobPost->created_at->format('d M Y') }}</span>
    </div>
  </div>
  <div class="hero-right">
    <div class="hero-stat-card">
      <div class="hsc-val" style="color:var(--amber);">{{ $appCount }}</div>
      <div class="hsc-lbl">Applications</div>
    </div>
    <div class="hero-stat-card">
      <div class="hsc-val" style="color:var(--green);">{{ $accCount }}</div>
      <div class="hsc-lbl">Shortlisted</div>
    </div>
  </div>
</div>

<div class="stat-strip">
  <div class="stat" style="animation-delay:.08s;">
    <div class="stat-icon si-amber">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
    </div>
    <div class="stat-body">
      <div class="stat-lbl">Total</div>
      <div class="stat-val sv-amber">{{ $appCount }}</div>
      <div class="stat-foot">All applications</div>
    </div>
  </div>
  <div class="stat" style="animation-delay:.13s;">
    <div class="stat-icon si-green">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    </div>
    <div class="stat-body">
      <div class="stat-lbl">Shortlisted</div>
      <div class="stat-val sv-green">{{ $accCount }}</div>
      <div class="stat-foot">Moving forward</div>
    </div>
  </div>
  <div class="stat" style="animation-delay:.18s;">
    <div class="stat-icon si-red">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    </div>
    <div class="stat-body">
      <div class="stat-lbl">Rejected</div>
      <div class="stat-val sv-red">{{ $rejCount }}</div>
      <div class="stat-foot">Not selected</div>
    </div>
  </div>
  <div class="stat" style="animation-delay:.23s;">
    <div class="stat-icon si-blue">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
    </div>
    <div class="stat-body">
      <div class="stat-lbl">Pending</div>
      <div class="stat-val sv-blue">{{ $pendCount }}</div>
      <div class="stat-foot">Awaiting review</div>
    </div>
  </div>
</div>

<div class="content-grid">

  <div>

    <div class="card" style="animation-delay:.10s;">
      <div class="card-header">
        <div class="card-header-left">
          <div class="card-hico ci-purple">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h10"/></svg>
          </div>
          <div>
            <div class="card-title">Job Description</div>
            <div class="card-sub">Full listing content</div>
          </div>
        </div>
      </div>
      <div class="card-body">
        @if($jobPost->description)
          <div class="desc-body">{{ $jobPost->description }}</div>
        @else
          <div style="color:var(--text3);font-size:13px;font-style:italic;">No description provided.</div>
        @endif
      </div>
    </div>

    <div class="card" style="animation-delay:.14s;margin-top:16px;">
      <div class="card-header">
        <div class="card-header-left">
          <div class="card-hico ci-amber">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
          </div>
          <div>
            <div class="card-title">Applicants</div>
            <div class="card-sub">{{ $appCount }} submission{{ $appCount !== 1 ? 's' : '' }}</div>
          </div>
        </div>
        @if($appCount > 0)
        <a href="{{ route('admin.job_post_applications.index', ['job_id' => $jobPost->id]) }}" class="btn btn-secondary" style="width:auto;padding:7px 14px;font-size:12px;">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
          View All
        </a>
        @endif
      </div>

      @if($appCount > 0)
      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>#</th>
              <th>Applicant</th>
              <th>Email</th>
              <th>Status</th>
              <th>Applied</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($jobPost->applications()->latest()->take(10)->get() as $i => $app)
            @php
              $aBadge = match($app->status ?? 'pending') {
                'shortlisted','accepted' => 'b-shortlisted',
                'rejected'               => 'b-rejected',
                'hired'                  => 'b-hired',
                default                  => 'b-pending',
              };
            @endphp
            <tr>
              <td class="td-mono">{{ $i + 1 }}</td>
              <td>
                <div class="td-name">{{ $app->name ?? 'N/A' }}</div>
                @if($app->phone)<div class="td-sub">{{ $app->phone }}</div>@endif
              </td>
              <td class="td-mono" style="font-size:12px;">{{ $app->email ?? '—' }}</td>
              <td><span class="badge {{ $aBadge }}">{{ ucfirst($app->status ?? 'pending') }}</span></td>
              <td class="td-mono">{{ $app->created_at->format('d M Y') }}</td>
              <td>
                <div class="act-btns">
                  <a href="{{ route('admin.job_post_applications.show', $app->id) }}" class="act-btn ab-view">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    <span>Review</span>
                  </a>
                  @if($app->cv_path)
                  <a href="{{ route('admin.job_post_applications.downloadCv', $app) }}" class="act-btn ab-download" target="_blank">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>CV</span>
                  </a>
                  @endif
                </div>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      @if($appCount > 10)
      <div style="padding:14px 22px;border-top:1px solid var(--border);text-align:center;">
        <a href="{{ route('admin.job_post_applications.index', ['job_id' => $jobPost->id]) }}" style="font-size:12.5px;color:var(--a);font-weight:600;">
          View all {{ $appCount }} applicants →
        </a>
      </div>
      @endif
      @else
      <div class="empty-state">
        <div class="empty-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
        </div>
        <div class="empty-ttl">No applications yet</div>
        <div class="empty-sub">Applications will appear here once candidates apply.</div>
      </div>
      @endif
    </div>

  </div>

  <div class="side-stack">

    <div class="side-card" style="animation-delay:.10s;">
      <div class="card-header">
        <div class="card-header-left">
          <div class="card-hico ci-purple">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
          </div>
          <div class="card-title">Quick Actions</div>
        </div>
      </div>
      <div class="btn-stack">
        <a href="{{ route('admin.job_posts.edit', $jobPost->id) }}" class="btn btn-primary">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
          Edit This Post
        </a>
        <a href="{{ route('admin.job_post_applications.index', ['job_id' => $jobPost->id]) }}" class="btn btn-secondary">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
          View All Applicants
          @if($appCount)<span class="s-chip sc-amber" style="margin-left:auto;">{{ $appCount }}</span>@endif
        </a>
        <a href="{{ route('admin.job_posts.create') }}" class="btn btn-secondary">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
          Post Another Job
        </a>
      </div>
    </div>

    <div class="side-card" style="animation-delay:.14s;">
      <div class="card-header">
        <div class="card-header-left">
          <div class="card-hico ci-blue">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          </div>
          <div class="card-title">Post Details</div>
        </div>
      </div>
      <div class="info-list">
        <div class="info-row">
          <span class="info-lbl">Status</span>
          <span class="badge {{ $statusKey === 'active' ? 'b-active' : ($statusKey === 'draft' ? 'b-draft' : 'b-closed') }}">{{ ucfirst($jobPost->status ?? 'draft') }}</span>
        </div>
        <div class="info-row">
          <span class="info-lbl">Post ID</span>
          <span class="info-val">#{{ $jobPost->id }}</span>
        </div>
        <div class="info-row">
          <span class="info-lbl">Type</span>
          <span class="info-val">{{ $jobPost->type ? ucfirst($jobPost->type) : '—' }}</span>
        </div>
        <div class="info-row">
          <span class="info-lbl">Location</span>
          <span class="info-val">{{ $jobPost->location ?: '—' }}</span>
        </div>
        <div class="info-row">
          <span class="info-lbl">Salary</span>
          <span class="info-val green">{{ $jobPost->salary ?: 'Undisclosed' }}</span>
        </div>
        <div class="info-row">
          <span class="info-lbl">Experience</span>
          <span class="info-val">{{ $jobPost->experience_required ?: 'Any' }}</span>
        </div>
        <div class="info-row">
          <span class="info-lbl">Vacancies</span>
          <span class="info-val amber">{{ $jobPost->vacancies ?: '—' }}</span>
        </div>
        @if($jobPost->department)
        <div class="info-row">
          <span class="info-lbl">Department</span>
          <span class="info-val">{{ $jobPost->department }}</span>
        </div>
        @endif
        <div class="info-row">
          <span class="info-lbl">Remote</span>
          <span class="info-val {{ $jobPost->is_remote ? 'green' : '' }}">{{ $jobPost->is_remote ? 'Yes' : 'No' }}</span>
        </div>
        <div class="info-row">
          <span class="info-lbl">Featured</span>
          <span class="info-val {{ $jobPost->featured ? 'amber' : '' }}">{{ $jobPost->featured ? '★ Yes' : 'No' }}</span>
        </div>
        @if($jobPost->application_deadline)
        <div class="info-row">
          <span class="info-lbl">Deadline</span>
          <span class="info-val {{ $isExpired ? 'red' : 'amber' }}">
            {{ \Carbon\Carbon::parse($jobPost->application_deadline)->format('d M Y') }}
            {{ $isExpired ? '· Expired' : '' }}
          </span>
        </div>
        @endif
        <div class="info-row">
          <span class="info-lbl">Posted</span>
          <span class="info-val">{{ $jobPost->created_at->format('d M Y') }}</span>
        </div>
        <div class="info-row">
          <span class="info-lbl">Updated</span>
          <span class="info-val">{{ $jobPost->updated_at->diffForHumans() }}</span>
        </div>
      </div>
    </div>

    <div class="danger-zone">
      <div class="danger-hdr">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        <span>Danger Zone</span>
      </div>
      <div class="danger-desc">Permanently delete this job post and all {{ $appCount }} linked application(s). Cannot be undone.</div>
      <button type="button" onclick="openDelete()" class="btn btn-danger" style="width:100%;">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
        Delete Job Post
      </button>
    </div>

  </div>
</div>

@endsection

@push('page_scripts')
<script>
(function(){
'use strict';

window.openDelete  = function(){ document.getElementById('deleteOverlay').classList.add('open'); };
window.closeDelete = function(){ document.getElementById('deleteOverlay').classList.remove('open'); };
document.getElementById('deleteOverlay').addEventListener('click', function(e){ if(e.target === this) closeDelete(); });
document.addEventListener('keydown', function(e){ if(e.key === 'Escape') closeDelete(); });

})();
</script>
@endpush
