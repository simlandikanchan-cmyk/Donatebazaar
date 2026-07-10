@extends('layouts.admin')

@section('page_title', 'Partnership Requests')
@section('page_subtitle', 'Review partnership inquiries')
@section('sidebar_partnerships', 'active')

@push('page_styles')
<style>
.toolbar{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:16px;flex-wrap:wrap;}
.toolbar-left{display:flex;align-items:center;gap:8px;flex-wrap:wrap;}
.toolbar-right{display:flex;align-items:center;gap:8px;}
.search-wrap{position:relative;}
.search-wrap .si{position:absolute;left:11px;top:50%;transform:translateY(-50%);width:13px;height:13px;color:var(--text3);pointer-events:none;}
.search-input{width:220px;height:36px;padding:0 12px 0 33px;background:var(--surface);border:1px solid var(--border2);border-radius:var(--r-sm);font-size:12.5px;color:var(--text);font-family:var(--font);outline:none;transition:border-color var(--ease),box-shadow var(--ease),width .3s ease;}
.search-input::placeholder{color:var(--text3);}
.search-input:focus{border-color:var(--a);box-shadow:0 0 0 3px var(--a-glow);width:260px;}
.select-wrap{position:relative;}
.select-wrap .si{position:absolute;left:11px;top:50%;transform:translateY(-50%);width:13px;height:13px;color:var(--text3);pointer-events:none;z-index:1;}
.filter-select{height:36px;padding:0 30px 0 33px;background:var(--surface);border:1px solid var(--border2);border-radius:var(--r-sm);font-size:12.5px;color:var(--text2);font-family:var(--font);outline:none;cursor:pointer;transition:all var(--ease);appearance:none;-webkit-appearance:none;-moz-appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%239096b4' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 9px center;background-size:13px;}
.filter-select:hover,.filter-select:focus{border-color:var(--a);color:var(--a);background-color:var(--a-lt);box-shadow:0 0 0 3px var(--a-glow);}
.filter-btn{display:inline-flex;align-items:center;gap:5px;height:36px;padding:0 13px;background:var(--surface);border:1px solid var(--border2);border-radius:var(--r-sm);font-size:12px;font-weight:500;color:var(--text2);cursor:pointer;font-family:var(--font);transition:all var(--ease);}
.filter-btn:hover,.filter-btn.on{border-color:var(--a);color:var(--a);background:var(--a-lt);}
.filter-btn.on{background:var(--a);color:#fff;border-color:var(--a);}
.cnt-badge{background:var(--a);color:#fff;font-size:9.5px;font-weight:700;padding:1px 5px;border-radius:100px;font-family:var(--mono);}

.export-btn{display:inline-flex;align-items:center;gap:6px;height:36px;padding:0 14px;background:var(--surface);border:1px solid var(--border2);border-radius:var(--r-sm);font-size:12px;font-weight:500;color:var(--text2);text-decoration:none;cursor:pointer;font-family:var(--font);transition:all var(--ease);}
.export-btn:hover{border-color:var(--green);color:var(--green);background:rgba(5,196,138,.06);}
.export-btn svg{width:13px;height:13px;}

.main-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh);overflow:hidden;animation:fadeUp .4s .15s ease both;}
.card-head{padding:14px 20px;border-bottom:1px solid var(--border);background:var(--surface2);display:flex;align-items:center;justify-content:space-between;}
.card-head-left{display:flex;align-items:center;gap:10px;}
.card-head-icon{width:30px;height:30px;border-radius:8px;background:var(--a-lt);color:var(--a);display:flex;align-items:center;justify-content:center;}
.card-head-icon svg{width:14px;height:14px;}
.card-head-title{font-size:12px;font-weight:600;color:var(--text2);text-transform:uppercase;letter-spacing:.09em;font-family:var(--mono);}
.card-head-count{font-size:10.5px;color:var(--text3);font-family:var(--mono);}

.table-wrap{overflow-x:auto;}
table{width:100%;border-collapse:collapse;}
thead th{padding:10px 16px;text-align:left;font-size:10px;font-family:var(--mono);letter-spacing:.12em;text-transform:uppercase;color:var(--text3);background:var(--surface2);border-bottom:1px solid var(--border);font-weight:500;white-space:nowrap;}
thead th:last-child{text-align:right;}
.sort-link{display:inline-flex;align-items:center;gap:4px;color:var(--text3);cursor:pointer;text-decoration:none;transition:color var(--ease);user-select:none;}
.sort-link:hover{color:var(--a);}
.sort-link .sort-ico{width:10px;height:10px;opacity:.5;}
.sort-link .sort-ico.asc{opacity:1;color:var(--a);}
.sort-link .sort-ico.desc{opacity:1;color:var(--a);transform:rotate(180deg);}
tbody tr{border-bottom:1px solid var(--border);transition:background var(--ease);}
tbody tr:last-child{border-bottom:none;}
tbody tr:hover{background:var(--surface2);}
td{padding:12px 16px;font-size:13px;vertical-align:middle;}
.td-check{width:40px;text-align:center;}
.td-check input[type="checkbox"]{width:15px;height:15px;cursor:pointer;accent-color:var(--a);}
.serial{font-size:11.5px;color:var(--text3);font-family:var(--mono);}
.person-cell{display:flex;align-items:center;gap:11px;}
.person-av{width:34px;height:34px;border-radius:10px;background:linear-gradient(135deg,var(--a),var(--a2));color:#fff;font-size:12px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-family:var(--mono);}
.person-name{font-weight:600;color:var(--text);font-size:13px;}
.person-email{font-size:10.5px;color:var(--text3);font-family:var(--mono);margin-top:1px;max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
.org-cell{font-weight:500;color:var(--text2);font-size:12.5px;max-width:140px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
.type-cell{font-size:12px;color:var(--text2);}
.mono-pill{display:inline-flex;align-items:center;gap:5px;background:var(--surface2);border:1px solid var(--border2);color:var(--text3);padding:2px 9px;border-radius:100px;font-size:11px;font-family:var(--mono);white-space:nowrap;}
.mono-pill svg{width:10px;height:10px;}
.status-pill{display:inline-flex;align-items:center;gap:5px;padding:3px 10px;border-radius:100px;font-size:10.5px;font-weight:600;font-family:var(--mono);text-transform:uppercase;letter-spacing:.05em;white-space:nowrap;}
.s-pending{background:var(--amber-lt);color:#b45309;border:1px solid rgba(245,158,11,.22);}
.s-approved{background:var(--green-lt);color:#059669;border:1px solid rgba(5,196,138,.22);}
.s-rejected{background:var(--red-lt);color:#b91c1c;border:1px solid rgba(240,68,68,.22);}
[data-theme="dark"] .s-pending{color:var(--amber);}
[data-theme="dark"] .s-approved{color:var(--green);}
[data-theme="dark"] .s-rejected{color:var(--red);}
.status-dot{width:5px;height:5px;border-radius:50%;background:currentColor;display:inline-block;}
.pri-pill{display:inline-flex;align-items:center;gap:4px;padding:2px 8px;border-radius:100px;font-size:10px;font-weight:700;font-family:var(--mono);text-transform:uppercase;white-space:nowrap;}
.pri-high{background:var(--green-lt);color:#059669;border:1px solid rgba(5,196,138,.22);}
.pri-medium{background:var(--amber-lt);color:#b45309;border:1px solid rgba(245,158,11,.22);}
.pri-low{background:var(--red-lt);color:#b91c1c;border:1px solid rgba(240,68,68,.22);}
[data-theme="dark"] .pri-high{color:var(--green);}
[data-theme="dark"] .pri-medium{color:var(--amber);}
[data-theme="dark"] .pri-low{color:var(--red);}
.score-num{font-size:10px;font-weight:700;font-family:var(--mono);color:var(--text3);margin-left:2px;}
.actions{display:flex;align-items:center;justify-content:flex-end;gap:5px;}
.act-btn{display:inline-flex;align-items:center;gap:4px;padding:5px 10px;border-radius:7px;font-size:11.5px;font-weight:500;text-decoration:none;border:1px solid transparent;transition:all .15s;cursor:pointer;font-family:var(--font);white-space:nowrap;}
.act-btn svg{width:11px;height:11px;}
.act-view{background:var(--a-lt);color:var(--a);border-color:rgba(37,99,235,.18);}
.act-view:hover{background:var(--a);color:#fff;transform:translateY(-1px);}
.act-del{background:var(--red-lt);color:var(--red);border-color:rgba(240,68,68,.18);}
.act-del:hover{background:var(--red);color:#fff;transform:translateY(-1px);}

.bulk-bar{display:none;align-items:center;gap:10px;padding:10px 18px;background:var(--a-lt);border-bottom:1px solid var(--border);font-size:12.5px;color:var(--a);font-weight:500;animation:fadeUp .25s ease;}
.bulk-bar.show{display:flex;}
.bulk-bar .bulk-count{font-family:var(--mono);}
.bulk-bar .bulk-acts{display:flex;gap:6px;margin-left:auto;}
.bulk-btn{display:inline-flex;align-items:center;gap:5px;height:30px;padding:0 12px;border-radius:6px;font-size:11px;font-weight:600;font-family:var(--font);border:none;cursor:pointer;transition:all .15s;text-decoration:none;}
.bulk-btn svg{width:11px;height:11px;}
.bulk-approve{background:var(--green);color:#fff;}
.bulk-approve:hover{opacity:.85;}
.bulk-reject{background:var(--red);color:#fff;}
.bulk-reject:hover{opacity:.85;}
.bulk-pending{background:var(--amber);color:#fff;}
.bulk-pending:hover{opacity:.85;}
.bulk-del{background:#64748b;color:#fff;}
.bulk-del:hover{opacity:.85;}
.bulk-cancel{background:transparent;border:1px solid var(--border2);color:var(--text2);padding:0 10px;}
.bulk-cancel:hover{background:var(--surface2);}

.empty-state{padding:72px 24px;text-align:center;}
.empty-icon-wrap{width:72px;height:72px;border-radius:20px;background:var(--surface2);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;font-size:26px;margin:0 auto 18px;animation:float 3s ease-in-out infinite;}
.empty-state h3{font-size:16px;font-weight:700;color:var(--text);margin-bottom:6px;}
.empty-state p{font-size:13px;color:var(--text3);margin-bottom:20px;}

.alert-ok{background:rgba(5,196,138,.08);border:1px solid rgba(5,196,138,.22);color:#065f46;padding:12px 16px;border-radius:var(--r-sm);font-size:13px;margin-bottom:16px;display:flex;align-items:center;gap:10px;animation:fadeUp .3s ease;}
[data-theme="dark"] .alert-ok{color:#189d68;}
.alert-ok svg{width:15px;height:15px;flex-shrink:0;}

.overlay{display:none;position:fixed;inset:0;z-index:9998;background:rgba(4,5,14,.65);backdrop-filter:blur(12px);align-items:center;justify-content:center;padding:20px;}
.overlay.open{display:flex;}
.modal{background:var(--surface);border:1px solid var(--border2);border-radius:22px;box-shadow:var(--sh-lg);width:100%;max-width:390px;padding:28px;position:relative;animation:modalIn .2s ease;}
@keyframes modalIn{from{opacity:0;transform:scale(.95) translateY(12px)}to{opacity:1;transform:none}}
.modal-x{position:absolute;top:16px;right:16px;width:28px;height:28px;border-radius:9px;border:1px solid var(--border2);background:var(--surface2);cursor:pointer;color:var(--text2);display:flex;align-items:center;justify-content:center;transition:all var(--ease);}
.modal-x:hover{background:var(--border2);transform:rotate(90deg);}
.modal-x svg{width:11px;height:11px;}
.modal-ico{width:48px;height:48px;border-radius:14px;background:var(--red-lt);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;}
.modal-ico svg{width:22px;height:22px;color:var(--red);}
.modal h3{font-size:16px;font-weight:700;color:var(--text);text-align:center;margin-bottom:8px;font-family:var(--mono);}
.modal p{font-size:13px;color:var(--text3);text-align:center;line-height:1.6;margin-bottom:22px;}
.modal-acts{display:flex;gap:10px;}
.modal-cancel{flex:1;height:40px;border-radius:var(--r-sm);border:1px solid var(--border2);background:var(--surface2);font-size:13px;font-weight:600;color:var(--text2);cursor:pointer;font-family:var(--font);transition:all var(--ease);}
.modal-cancel:hover{background:var(--surface3);}
.modal-del{flex:1;height:40px;border-radius:var(--r-sm);border:none;background:linear-gradient(135deg,var(--red),#dc2626);font-size:13px;font-weight:600;color:#fff;cursor:pointer;font-family:var(--font);transition:opacity var(--ease);box-shadow:0 4px 16px rgba(240,68,68,.3);}
.modal-del:hover{opacity:.88;}

.stats-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:24px;}
.stat{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);padding:16px 18px;position:relative;overflow:hidden;animation:fadeUp .4s ease both;display:flex;align-items:center;gap:14px;}
.stat::after{content:'';position:absolute;top:0;left:0;right:0;height:3px;opacity:.6;}
.stat:nth-child(1){animation-delay:.05s;}.stat:nth-child(1)::after{background:linear-gradient(90deg,var(--amber),#fbbf24);}
.stat:nth-child(2){animation-delay:.10s;}.stat:nth-child(2)::after{background:linear-gradient(90deg,var(--green),#34d399);}
.stat:nth-child(3){animation-delay:.15s;}.stat:nth-child(3)::after{background:linear-gradient(90deg,var(--red),#f87171);}
.stat:nth-child(4){animation-delay:.20s;}.stat:nth-child(4)::after{background:linear-gradient(90deg,var(--a),var(--a2));}
.stat-icon{width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.stat-icon svg{width:16px;height:16px;}
.si-amber{background:rgba(245,158,11,.10);color:var(--amber);}
.si-green{background:rgba(5,196,138,.10);color:var(--green);}
.si-red{background:rgba(240,68,68,.08);color:var(--red);}
.si-a{background:var(--a-lt);color:var(--a);}
.stat-body{flex:1;min-width:0;}
.stat-lbl{font-size:10px;font-family:var(--mono);text-transform:uppercase;letter-spacing:.08em;color:var(--text3);margin-bottom:2px;}
.stat-val{font-size:20px;font-weight:800;font-family:var(--mono);letter-spacing:-.03em;line-height:1.1;margin-bottom:1px;}
.sv-amber{color:var(--amber);}
.sv-green{color:var(--green);}
.sv-red{color:var(--red);}
.sv-a{color:var(--a);}
.stat-foot{font-size:10px;color:var(--text3);}

.pagination-wrap{display:flex;align-items:center;justify-content:space-between;padding:14px 20px;border-top:1px solid var(--border);background:var(--surface2);flex-wrap:wrap;gap:10px;}
.page-info{font-size:12px;color:var(--text3);font-family:var(--mono);}
.page-btns{display:flex;gap:4px;}
.page-btn{width:30px;height:30px;border-radius:7px;display:flex;align-items:center;justify-content:center;font-size:11.5px;font-weight:600;border:1px solid var(--border2);background:var(--surface);color:var(--text2);cursor:pointer;transition:all .15s;font-family:var(--mono);text-decoration:none;}
.page-btn:hover{border-color:var(--a);color:var(--a);background:var(--a-lt);}
.page-btn.cur{background:var(--a);border-color:var(--a);color:#fff;}
.page-btn svg{width:12px;height:12px;}

@media(max-width:860px){.stats-grid{grid-template-columns:repeat(2,1fr);}}
@media(max-width:600px){.stats-grid{grid-template-columns:1fr 1fr}.search-input{width:160px;}.search-input:focus{width:180px;}}
@keyframes fadeUp{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:none}}
@keyframes float{0%,100%{transform:translateY(0)}50%{transform:translateY(-6px)}}
</style>
@endpush

@section('content')
{{-- delete single modal --}}
<div class="overlay" id="deleteOverlay" role="dialog" aria-modal="true">
  <div class="modal">
    <button type="button" class="modal-x" onclick="closeModal()"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
    <div class="modal-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg></div>
    <h3>Delete Partnership?</h3>
    <p>This will permanently remove the request from <strong id="modalPartnerName"></strong>. This action cannot be undone.</p>
    <div class="modal-acts">
      <button class="modal-cancel" onclick="closeModal()">Cancel</button>
      <button class="modal-del" onclick="confirmDelete()">Yes, Delete</button>
    </div>
  </div>
</div>
<form id="deleteForm" method="POST" style="display:none;">@csrf @method('DELETE')</form>

{{-- delete bulk modal --}}
<div class="overlay" id="bulkDeleteOverlay" role="dialog" aria-modal="true">
  <div class="modal">
    <button type="button" class="modal-x" onclick="closeBulkModal()"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
    <div class="modal-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg></div>
    <h3>Delete Selected Requests?</h3>
    <p>This will permanently remove <strong id="bulkCountDisplay">0</strong> partnership request(s).</p>
    <div class="modal-acts">
      <button class="modal-cancel" onclick="closeBulkModal()">Cancel</button>
      <button class="modal-del" onclick="confirmBulkDelete()">Yes, Delete</button>
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
        <input type="text" class="search-input" name="search" value="{{ $search }}" placeholder="Search partnerships…" oninput="autoSubmit()">
      </div>
      <div class="select-wrap">
        <svg class="si" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
        <select class="filter-select" name="status" onchange="this.form.submit()">
          <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All Status</option>
          <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
          <option value="approved" {{ $status === 'approved' ? 'selected' : '' }}>Approved</option>
          <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Rejected</option>
        </select>
      </div>
      <input type="hidden" name="sort" value="{{ $sort }}">
      <input type="hidden" name="direction" value="{{ $dir }}">
    </div>
    <div class="toolbar-right">
      <a href="{{ route('admin.partnership.export', request()->only('search', 'status')) }}" class="export-btn">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>Export CSV
      </a>
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
      <button type="button" class="bulk-btn bulk-approve" onclick="bulkAction('approved')">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4"/></svg>Approve
      </button>
      <button type="button" class="bulk-btn bulk-reject" onclick="bulkAction('rejected')">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>Reject
      </button>
      <button type="button" class="bulk-btn bulk-pending" onclick="bulkAction('pending')">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>Pending
      </button>
      <button type="button" class="bulk-btn bulk-del" onclick="openBulkDelete()">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/></svg>Delete
      </button>
      <button type="button" class="bulk-btn bulk-cancel" onclick="clearAllCheckboxes()">Cancel</button>
    </div>
  </div>

  <form id="bulkForm" method="POST" style="display:none;">@csrf</form>

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
          <th class="td-check"><input type="checkbox" id="selectAll" onchange="toggleAll(this)"></th>
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
          <td class="td-check"><input type="checkbox" class="row-check" value="{{ $p->id }}" onchange="updateBulkBar()"></td>
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
              <a href="{{ route('admin.partnership.show', $p->id) }}" class="act-btn act-view">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                View
              </a>
              <button type="button" class="act-btn act-del"
                onclick="openModal('{{ $p->id }}','{{ addslashes($p->name) }}','{{ route('admin.partnership.delete', $p->id) }}')">
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
<script>
(function(){
'use strict';
(function(){var a=document.getElementById('flashAlert');if(!a)return;setTimeout(function(){a.style.transition='opacity .4s,transform .4s';a.style.opacity='0';a.style.transform='translateY(-6px)';setTimeout(function(){a.remove();},400);},4000);})();

var searchTimer;
window.autoSubmit=function(){
  clearTimeout(searchTimer);
  searchTimer=setTimeout(function(){
    document.getElementById('filterForm').submit();
  },500);
};

// single delete modal
var pendingUrl=null;
window.openModal=function(id,name,url){
  pendingUrl=url;
  document.getElementById('modalPartnerName').textContent='"'+name+'"';
  document.getElementById('deleteOverlay').classList.add('open');
};
window.closeModal=function(){document.getElementById('deleteOverlay').classList.remove('open');pendingUrl=null;};
window.confirmDelete=function(){if(!pendingUrl)return;var f=document.getElementById('deleteForm');f.action=pendingUrl;f.submit();};
document.getElementById('deleteOverlay').addEventListener('click',function(e){if(e.target===this)closeModal();});
document.addEventListener('keydown',function(e){if(e.key==='Escape'){closeModal();closeBulkModal();}});

// bulk
var bulkForm=document.getElementById('bulkForm');

window.toggleAll=function(cb){
  document.querySelectorAll('.row-check').forEach(function(c){c.checked=cb.checked;});
  updateBulkBar();
};

window.updateBulkBar=function(){
  var checks=document.querySelectorAll('.row-check:checked');
  var bar=document.getElementById('bulkBar');
  document.getElementById('bulkCount').textContent=checks.length;
  bar.classList.toggle('show',checks.length>0);
};

window.bulkAction=function(status){
  var checks=document.querySelectorAll('.row-check:checked');
  if(!checks.length)return;
  bulkForm.innerHTML='@csrf';
  checks.forEach(function(c){
    var inp=document.createElement('input');
    inp.type='hidden';inp.name='ids[]';inp.value=c.value;
    bulkForm.appendChild(inp);
  });
  var inp=document.createElement('input');
  inp.type='hidden';inp.name='status';inp.value=status;
  bulkForm.appendChild(inp);
  bulkForm.action='{{ route("admin.partnership.bulk-update") }}';
  bulkForm.submit();
};

window.openBulkDelete=function(){
  var checks=document.querySelectorAll('.row-check:checked');
  if(!checks.length)return;
  document.getElementById('bulkCountDisplay').textContent=checks.length;
  document.getElementById('bulkDeleteOverlay').classList.add('open');
};

window.closeBulkModal=function(){
  document.getElementById('bulkDeleteOverlay').classList.remove('open');
};

window.confirmBulkDelete=function(){
  var checks=document.querySelectorAll('.row-check:checked');
  if(!checks.length){closeBulkModal();return;}
  bulkForm.innerHTML='@csrf';
  checks.forEach(function(c){
    var inp=document.createElement('input');
    inp.type='hidden';inp.name='ids[]';inp.value=c.value;
    bulkForm.appendChild(inp);
  });
  bulkForm.action='{{ route("admin.partnership.bulk-delete") }}';
  bulkForm.submit();
};

document.getElementById('bulkDeleteOverlay').addEventListener('click',function(e){if(e.target===this)closeBulkModal();});

window.clearAllCheckboxes=function(){
  document.querySelectorAll('.row-check').forEach(function(c){c.checked=false;});
  if(document.getElementById('selectAll'))document.getElementById('selectAll').checked=false;
  updateBulkBar();
};

document.getElementById('filterForm').addEventListener('keypress',function(e){
  if(e.key==='Enter'){e.preventDefault();this.submit();}
});
})();
</script>
@endpush
