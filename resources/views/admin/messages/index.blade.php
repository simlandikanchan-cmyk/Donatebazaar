@extends('layouts.admin')

@section('sidebar_messages', 'active')
@section('page_title', 'Messages')
@section('page_subtitle', 'Manage all messages')

@push('page_styles')
<style>
.flash-ok{background:rgba(5,196,138,.09);border:1px solid rgba(5,196,138,.25);color:#065f46;padding:12px 16px;border-radius:var(--r-sm);font-size:13px;font-weight:500;margin-bottom:18px;display:flex;align-items:center;gap:8px}
[data-theme="dark"] .flash-ok{color:#34d399}
.flash-ok svg{width:15px;height:15px;flex-shrink:0}
.si-orange{background:rgba(249,115,22,.12);color:#ea580c}
.sv-orange{color:#ea580c}
.si-purple{background:var(--a-lt);color:var(--a)}
.sv-purple{color:var(--a)}
.sec-search{position:relative;display:flex;align-items:center}
.sec-search svg{position:absolute;left:11px;top:50%;transform:translateY(-50%);width:14px;height:14px;color:var(--text3);pointer-events:none}
.sec-search input{height:38px;width:230px;max-width:46vw;padding:0 12px 0 34px;border-radius:var(--r-sm);border:1px solid var(--border2);background:var(--surface2);color:var(--text);font-size:12.5px;font-family:var(--font);outline:none;transition:border-color var(--ease),box-shadow var(--ease)}
.sec-search input:focus{border-color:var(--a);box-shadow:0 0 0 3px var(--a-glow);background:var(--surface)}
.sec-search input::placeholder{color:var(--text3)}

/* Filter bar */
.filter-bar{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);padding:14px 18px;margin-bottom:16px;display:flex;align-items:center;gap:10px;flex-wrap:wrap;box-shadow:var(--sh);animation:fadeUp .4s .18s ease both}
.filter-group{display:flex;align-items:center;gap:6px;flex-shrink:0}
.filter-lbl{font-size:10px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.09em;font-family:var(--mono);white-space:nowrap}
.filter-sel{height:32px;padding:0 26px 0 10px;border-radius:var(--r-xs);border:1px solid var(--border2);background:var(--surface2);color:var(--text);font-size:12px;font-family:var(--font);outline:none;cursor:pointer;transition:border-color var(--ease),box-shadow var(--ease);appearance:none;-webkit-appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='10' viewBox='0 0 24 24' fill='none' stroke='%239096b4' stroke-width='2.5'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 8px center}
.filter-sel:focus{border-color:var(--a);box-shadow:0 0 0 3px var(--a-glow)}
.filter-date{height:32px;padding:0 10px;border-radius:var(--r-xs);border:1px solid var(--border2);background:var(--surface2);color:var(--text);font-size:12px;font-family:var(--mono);outline:none;cursor:pointer;transition:border-color var(--ease),box-shadow var(--ease)}
.filter-date:focus{border-color:var(--a);box-shadow:0 0 0 3px var(--a-glow)}
.filter-date::-webkit-calendar-picker-indicator{opacity:.4;cursor:pointer}
[data-theme="dark"] .filter-date::-webkit-calendar-picker-indicator{filter:invert(1);opacity:.4}
.filter-div{width:1px;height:22px;background:var(--border2);flex-shrink:0}
.filter-reset{margin-left:auto;display:inline-flex;align-items:center;gap:5px;height:32px;padding:0 12px;border-radius:var(--r-xs);border:1px solid var(--border2);background:transparent;color:var(--text3);font-size:11.5px;font-weight:600;cursor:pointer;transition:all var(--ease);font-family:var(--font);white-space:nowrap}
.filter-reset:hover{background:var(--red-lt);color:var(--red);border-color:rgba(240,68,68,.3)}
.filter-reset svg{width:11px;height:11px}

/* Bulk action bar */
.bulk-bar{display:none;align-items:center;justify-content:space-between;gap:12px;background:linear-gradient(135deg,var(--a-lt),rgba(155,89,245,.12));border:1px solid rgba(37,99,235,.3);border-radius:var(--r);padding:10px 16px;margin-bottom:14px;animation:fadeUp .25s ease both;flex-wrap:wrap}
.bulk-bar.show{display:flex}
.bulk-left{font-size:12.5px;color:var(--text);font-weight:500}
.bulk-left strong{font-family:var(--mono);font-size:13px;color:var(--a)}
.bulk-actions{display:flex;align-items:center;gap:8px;flex-wrap:wrap}
.bb-btn{display:inline-flex;align-items:center;gap:5px;height:32px;padding:0 14px;border-radius:var(--r-xs);font-size:12px;font-weight:600;cursor:pointer;border:1px solid transparent;transition:all var(--ease);font-family:var(--font)}
.bb-btn svg{width:12px;height:12px}
.bb-read{background:var(--surface);color:var(--a);border-color:rgba(37,99,235,.25)}
.bb-read:hover{background:var(--a);color:#fff}
.bb-delete{background:var(--surface);color:var(--red);border-color:rgba(240,68,68,.25)}
.bb-delete:hover{background:var(--red);color:#fff}
.bb-clear{background:transparent;color:var(--text3);border-color:transparent}
.bb-clear:hover{color:var(--text)}

/* Table */
.table-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh);overflow:hidden;animation:fadeUp .4s .25s ease both}
.table-scroll{overflow-x:auto}
.table-scroll::-webkit-scrollbar{height:5px}
.table-scroll::-webkit-scrollbar-track{background:var(--surface2)}
.table-scroll::-webkit-scrollbar-thumb{background:rgba(37,99,235,.35);border-radius:10px}
.table-scroll::-webkit-scrollbar-thumb:hover{background:var(--a)}
table{width:100%;min-width:760px;border-collapse:collapse}
thead tr{border-bottom:1px solid var(--border)}
thead th{padding:12px 16px;text-align:left;font-size:10px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.09em;font-family:var(--mono);background:var(--surface2);white-space:nowrap}
thead th.sortable{cursor:pointer;user-select:none;transition:color var(--ease)}
thead th.sortable:hover{color:var(--a)}
.col-check{width:42px;text-align:center!important;padding-left:16px!important}
.sort-arrows{display:inline-flex;flex-direction:column;gap:1px;margin-left:4px;vertical-align:middle;opacity:.4}
thead th.sort-asc .sort-arrows,thead th.sort-desc .sort-arrows{opacity:1;color:var(--a)}
.sort-arrows svg{width:7px;height:7px}
tbody tr{border-bottom:1px solid var(--border);transition:background var(--ease);position:relative}
tbody tr:last-child{border-bottom:none}
tbody tr:hover{background:var(--surface2)}
tbody td{padding:14px 16px;font-size:13px;color:var(--text2);vertical-align:middle}
.row-select{width:16px;height:16px;accent-color:var(--a);cursor:pointer}
.sender-cell{display:flex;align-items:center;gap:11px}
.row-av{width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,var(--a),var(--a2));color:#fff;font-size:13px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-family:var(--mono);position:relative}
.row-av .unread-dot{position:absolute;top:-3px;right:-3px;width:9px;height:9px;border-radius:50%;background:#f97316;border:2px solid var(--surface)}
.sender-name{font-size:13px;font-weight:600;color:var(--text);line-height:1.3}
.sender-email{font-size:10.5px;color:var(--text3);font-family:var(--mono);margin-top:1px}
tbody tr.unread .sender-name{font-weight:800;color:var(--text)}
tbody tr.unread{background:rgba(37,99,235,.04)}
tbody tr.unread::before{content:'';position:absolute;left:0;top:0;bottom:0;width:3px;background:linear-gradient(180deg,var(--a),var(--a2))}
.msg-cell{white-space:normal;max-width:360px}
.msg-subj{font-size:12.5px;font-weight:700;color:var(--text);line-height:1.35;margin-bottom:2px;display:flex;align-items:center;gap:6px}
.msg-subj .subj-tag{font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--a);background:var(--a-lt);padding:1px 6px;border-radius:5px;font-family:var(--mono)}
.msg-prev{font-size:12px;line-height:1.5;color:var(--text3);display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
.date-cell{font-family:var(--mono);font-size:11.5px;color:var(--text3);white-space:nowrap}
.date-ago{font-size:10px;color:var(--text3);margin-top:2px;font-family:var(--mono)}
.badge{display:inline-flex;align-items:center;gap:4px;font-size:10px;font-weight:700;padding:4px 9px;border-radius:7px;text-transform:uppercase;letter-spacing:.07em;font-family:var(--mono)}
.badge-dot{width:5px;height:5px;border-radius:50%;background:currentColor;flex-shrink:0}
.b-new{background:rgba(59,130,246,.12);color:#1d4ed8;border:1px solid rgba(59,130,246,.2)}
.b-read{background:rgba(5,196,138,.12);color:#065f46;border:1px solid rgba(5,196,138,.2)}
[data-theme="dark"] .b-new{color:#93c5fd}
[data-theme="dark"] .b-read{color:#34d399}
.actions{display:flex;align-items:center;gap:5px;flex-wrap:wrap}
.act-btn{display:inline-flex;align-items:center;gap:4px;padding:6px 11px;border-radius:var(--r-xs);font-size:11px;font-weight:600;cursor:pointer;border:1px solid transparent;transition:all var(--ease);white-space:nowrap;font-family:var(--font);text-decoration:none;background:none}
.act-btn:hover{transform:translateY(-1px)}
.act-btn:active{transform:scale(.96)}
.act-btn svg{width:11px;height:11px}
.ab-view{background:var(--a-lt);color:var(--a);border-color:rgba(37,99,235,.2)}
.ab-view:hover{background:var(--a);color:#fff;box-shadow:0 4px 14px rgba(37,99,235,.35)}
.ab-toggle{background:var(--surface2);color:var(--text3);border-color:var(--border2)}
.ab-toggle:hover{background:var(--surface3);color:var(--text)}
.ab-delete{background:var(--red-lt);color:var(--red);border-color:rgba(240,68,68,.2)}
.ab-delete:hover{background:var(--red);color:#fff;box-shadow:0 4px 14px rgba(240,68,68,.3)}
.empty-row td{padding:60px 20px;text-align:center}
.empty-wrap{display:flex;flex-direction:column;align-items:center;gap:8px;color:var(--text3)}
.empty-wrap svg{width:44px;height:44px;opacity:.2}
.empty-wrap strong{font-family:var(--mono);font-size:15px;font-weight:700;color:var(--text2)}
.empty-wrap p{font-size:13px}
.table-footer{display:flex;align-items:center;justify-content:space-between;padding:12px 18px;border-top:1px solid var(--border);background:var(--surface2);flex-wrap:wrap;gap:8px}
.tfoot-info{font-size:11.5px;color:var(--text3);font-family:var(--mono)}
.tfoot-info strong{color:var(--text);font-weight:600}
.tfoot-total{font-size:11px;color:var(--text3);font-family:var(--mono)}
.pagination-wrap{margin-top:20px;display:flex;align-items:center;justify-content:center;gap:10px}
.pg-pages{display:flex;align-items:center;gap:6px}
.pg-page,.pg-arrow{width:38px;height:38px;border-radius:var(--r-sm);border:1px solid var(--border);background:var(--surface);color:var(--text2);display:flex;align-items:center;justify-content:center;text-decoration:none;font-size:14px;font-weight:700;transition:all var(--ease);box-shadow:var(--sh)}
.pg-page:hover,.pg-arrow:hover{background:var(--a-lt);border-color:var(--a);color:var(--a);transform:translateY(-2px)}
.pg-page.active{background:linear-gradient(135deg,var(--a),var(--a2));border-color:transparent;color:#fff;box-shadow:0 8px 20px rgba(37,99,235,.35)}
.pg-arrow.disabled{opacity:.3;pointer-events:none}

/* Mobile card layout */
@media(max-width:760px){
  .sec-search{order:3;width:100%}
  .sec-search input{width:100%;max-width:none}
  table{min-width:0}
  thead{display:none}
  tbody tr{display:block;border:1px solid var(--border);border-radius:var(--r-sm);margin-bottom:12px;padding:6px 4px;background:var(--surface)}
  tbody tr.unread{background:rgba(37,99,235,.04)}
  tbody tr::before{display:none}
  tbody td{display:flex;align-items:flex-start;gap:10px;padding:9px 12px;border:none!important;text-align:left;white-space:normal}
  tbody td.col-check{display:none}
  tbody td::before{content:attr(data-label);font-size:9.5px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--text3);font-family:var(--mono);min-width:74px;padding-top:2px}
  tbody td.msg-cell{max-width:none}
  tbody td.msg-cell::before{display:none}
  tbody td.date-cell{flex-direction:column;align-items:flex-start;gap:2px}
  tbody td.actions::before{display:none}
  tbody td.actions{justify-content:flex-start;flex-wrap:wrap}
  .msg-prev{-webkit-line-clamp:3}
}
<<<<<<< HEAD
@media(max-width:380px){
  .stats-grid{gap:8px;}
  .stat{padding:10px 12px;gap:8px;}
  .stat-icon{width:30px;height:30px;border-radius:8px;}
  .stat-icon svg{width:13px;height:13px;}
  .stat-val{font-size:1.1rem;}
  .sec-hdr{gap:8px;}
  .sec-search input{width:100%;max-width:none;}
  .ftab-select{margin-top:4px;}
  .filter-bar{flex-direction:column;align-items:stretch;gap:8px;padding:12px 14px;}
  .filter-group{flex-wrap:wrap;}
  .filter-div{display:none;}
  .filter-reset{margin-left:0;width:100%;justify-content:center;}
  .table-card .table-footer{flex-direction:column;gap:4px;text-align:center;}
}
=======
@media(max-width:640px){.filter-bar{padding:12px 14px;gap:8px}.filter-group{flex:1;min-width:0}.filter-sel{width:100%}}
@media(max-width:480px){.filter-bar{flex-direction:column;align-items:stretch}.filter-group{flex-wrap:wrap;width:100%}.filter-sel{width:100%}.filter-div{display:none}.filter-reset{width:100%;justify-content:center}}
@media(max-width:380px){.pagination-wrap{flex-direction:column;gap:8px}}
>>>>>>> origin/master
</style>
@endpush

@section('content')
@php
  $cntTotal = $total;
  $cntRead  = $read;
  $cntNew   = $unread;
@endphp

<div class="stats-grid">
  <div class="stat">
    <div class="stat-icon si-blue">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
    </div>
    <div class="stat-body">
      <div class="stat-lbl">Total Messages</div>
      <div class="stat-val sv-blue" id="statTotal">{{ $cntTotal }}</div>
      <div class="stat-foot">All time received</div>
    </div>
  </div>
  <div class="stat">
    <div class="stat-icon si-orange">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 2a10 10 0 100 20 10 10 0 000-20zm0 5v5l3 3"/></svg>
    </div>
    <div class="stat-body">
      <div class="stat-lbl">Unread</div>
      <div class="stat-val sv-orange" id="statUnread">{{ $cntNew }}</div>
      <div class="stat-foot">Need attention</div>
    </div>
  </div>
  <div class="stat">
    <div class="stat-icon si-green">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    </div>
    <div class="stat-body">
      <div class="stat-lbl">Read</div>
      <div class="stat-val sv-green" id="statRead">{{ $cntRead }}</div>
      <div class="stat-foot">Already reviewed</div>
    </div>
  </div>
  <div class="stat">
    <div class="stat-icon si-purple">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
    </div>
    <div class="stat-body">
      <div class="stat-lbl">Today</div>
      <div class="stat-val sv-purple" id="statToday">{{ $today }}</div>
      <div class="stat-foot">Received today</div>
    </div>
  </div>
</div>

@if(session('success'))
<div class="flash-ok">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
  {{ session('success') }}
</div>
@endif

<div class="sec-hdr">
  <div class="sec-ttl">All Messages</div>
  <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
    <div class="sec-search">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
      <input type="text" id="searchInput" placeholder="Search name, email, subject…" autocomplete="off" aria-label="Search messages">
    </div>
    <div class="ftabs" id="ftabs">
      <button class="ftab on" data-filter="all">All <span class="cnt" id="cntAll">{{ $cntTotal }}</span></button>
      <button class="ftab" data-filter="new">Unread <span class="cnt" id="cntUnread">{{ $cntNew }}</span></button>
      <button class="ftab" data-filter="read">Read <span class="cnt" id="cntRead">{{ $cntRead }}</span></button>
    </div>
    <select class="ftab-select" onchange="var btn=document.querySelector('.ftab[data-filter=&quot;'+this.value+'&quot;]');if(btn)btn.click();">
      <option value="all">All ({{ $cntTotal }})</option>
      <option value="new">Unread ({{ $cntNew }})</option>
      <option value="read">Read ({{ $cntRead }})</option>
    </select>
  </div>
</div>

<div class="filter-bar">
  <div class="filter-group">
    <span class="filter-lbl">Period</span>
    <select class="filter-sel" id="filterPeriod">
      <option value="all">All time</option>
      <option value="today">Today</option>
      <option value="yesterday">Yesterday</option>
      <option value="week">This week</option>
      <option value="month">This month</option>
      <option value="custom">Custom…</option>
    </select>
  </div>

  <div class="filter-group" id="customDateGroup" style="display:none;">
    <span class="filter-lbl">From</span>
    <input type="date" class="filter-date" id="dateFrom">
    <span class="filter-lbl">To</span>
    <input type="date" class="filter-date" id="dateTo">
  </div>

  <div class="filter-div"></div>

  <div class="filter-group">
    <span class="filter-lbl">Sort</span>
    <select class="filter-sel" id="filterSort">
      <option value="newest">Newest first</option>
      <option value="oldest">Oldest first</option>
      <option value="name_az">Name A → Z</option>
      <option value="name_za">Name Z → A</option>
    </select>
  </div>

  <div class="filter-div"></div>

  <div class="filter-group">
    <span class="filter-lbl">Subject</span>
    <select class="filter-sel" id="filterSubject">
      <option value="all">Any</option>
      <option value="has">Has subject</option>
      <option value="none">No subject</option>
    </select>
  </div>

  <button class="filter-reset" id="filterReset">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
    Reset
  </button>
</div>

<div class="bulk-bar" id="bulkBar">
  <div class="bulk-left"><strong id="bulkCount">0</strong> selected</div>
  <div class="bulk-actions">
    <button class="btn btn-secondary bb-btn bb-read" id="bulkRead">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      Mark as read
    </button>
    <button class="btn btn-red bb-btn bb-delete" id="bulkDelete">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6"/></svg>
      Delete
    </button>
    <button class="btn btn-secondary bb-btn bb-clear" id="bulkClear">Clear</button>
  </div>
</div>

<div class="table-card">
  <div class="table-scroll">
    <table>
      <thead>
        <tr>
          <th class="col-check"><input type="checkbox" id="selectAll" class="row-select" aria-label="Select all"></th>
          <th>Sender</th>
          <th>Message</th>
          <th class="sortable" id="thDate">
            Date
            <span class="sort-arrows">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M18 15l-6-6-6 6"/></svg>
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M6 9l6 6 6-6"/></svg>
            </span>
          </th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody id="tbody">
        @forelse($messages as $msg)
        @php
          $init    = strtoupper(substr($msg->name ?? 'U', 0, 1));
          $isRead  = (bool) $msg->is_read;
          $status  = $isRead ? 'read' : 'new';
          $hasSubj = !empty($msg->subject) ? 'has' : 'none';
          $srch    = strtolower(($msg->name ?? '').' '.($msg->email ?? '').' '.($msg->subject ?? '').' '.($msg->message ?? ''));
        @endphp
        <tr data-id="{{ $msg->id }}"
            data-status="{{ $status }}"
            data-search="{{ $srch }}"
            data-subject="{{ $hasSubj }}"
            data-ts="{{ $msg->created_at->timestamp }}"
            data-name="{{ strtolower($msg->name ?? '') }}"
            data-datestr="{{ $msg->created_at->format('Y-m-d') }}">
          <td class="col-check">
            <input type="checkbox" class="row-select row-check" value="{{ $msg->id }}" aria-label="Select message">
          </td>
          <td data-label="Sender">
            <div class="sender-cell">
              <div class="row-av">{{ $init }}@if(!$isRead)<span class="unread-dot"></span>@endif</div>
              <div>
                <div class="sender-name">{{ $msg->name }}</div>
                <div class="sender-email">{{ $msg->email }}</div>
              </div>
            </div>
          </td>
          <td class="msg-cell" data-label="Message">
            @if($msg->subject)
              <div class="msg-subj"><span class="subj-tag">Subject</span>{{ $msg->subject }}</div>
            @endif
            <div class="msg-prev">{{ \Illuminate\Support\Str::limit($msg->message, 140) }}</div>
          </td>
          <td class="date-cell" data-label="Date">
            {{ $msg->created_at->format('d M Y') }}
            <div class="date-ago">{{ $msg->created_at->diffForHumans() }}</div>
          </td>
          <td data-label="Status">
            <span class="badge b-{{ $status }}">
              <span class="badge-dot"></span>{{ $isRead ? 'Read' : 'New' }}
            </span>
          </td>
          <td data-label="Actions">
            <div class="actions">
              <a href="{{ route('admin.messages.show', $msg->id) }}" class="btn btn-secondary act-btn ab-view">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                View
              </a>
              <button type="button" class="btn btn-secondary act-btn ab-toggle" data-id="{{ $msg->id }}" data-read="{{ $isRead ? '1' : '0' }}">
                @if($isRead)
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9l9 6 9-6"/></svg> Unread
                @else
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Read
                @endif
              </button>
              <form action="{{ route('admin.messages.delete', $msg->id) }}" method="POST" style="display:inline;">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-red act-btn ab-delete" onclick="return confirm('Delete this message?')">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6"/></svg>
                  Delete
                </button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr class="empty-row">
          <td colspan="6">
            <div class="empty-wrap">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
              <strong>No messages yet</strong>
              <p>When users send messages they'll appear here.</p>
            </div>
          </td>
        </tr>
        @endforelse
        <tr id="noResultsRow" style="display:none;">
          <td colspan="6">
            <div class="empty-wrap">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
              <strong>No results found</strong>
              <p>Try adjusting your filters or search query.</p>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
  <div class="table-footer">
    <div class="tfoot-info">Showing <strong id="cntVisF">{{ $messages->count() }}</strong> of <strong id="cntTotalF">{{ $cntTotal }}</strong> messages</div>
    <div class="tfoot-total">{{ $cntTotal }} total</div>
  </div>
</div>

@if($messages->lastPage() > 1)
<div class="pagination-wrap">
  @if($messages->onFirstPage())
    <span class="pg-arrow disabled">‹</span>
  @else
    <a href="{{ $messages->previousPageUrl() }}" class="pg-arrow">‹</a>
  @endif
  <div class="pg-pages">
    @for($i = 1; $i <= $messages->lastPage(); $i++)
      @if($i === $messages->currentPage())
        <span class="pg-page active">{{ $i }}</span>
      @else
        <a href="{{ $messages->url($i) }}" class="pg-page">{{ $i }}</a>
      @endif
    @endfor
  </div>
  @if($messages->hasMorePages())
    <a href="{{ $messages->nextPageUrl() }}" class="pg-arrow">›</a>
  @else
    <span class="pg-arrow disabled">›</span>
  @endif
</div>
@endif
@endsection

@push('page_scripts')
<script>
(function(){
'use strict';

var csrf   = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
var bulkUrl = "{{ route('admin.messages.bulk') }}";

/* live totals (server-provided, adjusted on actions) */
var totals = {
  total:  parseInt(document.getElementById('statTotal').textContent, 10) || 0,
  unread: parseInt(document.getElementById('statUnread').textContent, 10) || 0,
  read:   parseInt(document.getElementById('statRead').textContent, 10) || 0,
  today:  parseInt(document.getElementById('statToday').textContent, 10) || 0
};

/* sidebar unread chip */
(function(){
  var chip = document.getElementById('sidebarUnread');
  if(chip && totals.unread > 0){
    chip.textContent = totals.unread;
    chip.style.display = '';
  } else if(chip){
    chip.style.display = 'none';
  }
})();

var activeFilter = 'all';
var activeSort   = 'newest';
var activePeriod = 'all';
var activeSubj   = 'all';
var dateFrom = '', dateTo = '';
var rows  = Array.from(document.querySelectorAll('#tbody tr[data-id]'));
var tbody = document.getElementById('tbody');
var noRow = document.getElementById('noResultsRow');
var bulkBar = document.getElementById('bulkBar');

function todayStr(){ return new Date().toISOString().slice(0,10); }
function yesterdayStr(){ var d=new Date(); d.setDate(d.getDate()-1); return d.toISOString().slice(0,10); }
function weekStartStr(){ var d=new Date(); d.setDate(d.getDate()-d.getDay()); return d.toISOString().slice(0,10); }
function monthStartStr(){ var d=new Date(); return d.getFullYear()+'-'+String(d.getMonth()+1).padStart(2,'0')+'-01'; }
function getPeriodRange(p){
  var t = todayStr();
  if(p==='today')     return [t,t];
  if(p==='yesterday') return [yesterdayStr(),yesterdayStr()];
  if(p==='week')      return [weekStartStr(),t];
  if(p==='month')     return [monthStartStr(),t];
  if(p==='custom')    return [dateFrom,dateTo];
  return ['',''];
}

function applyFilters(){
  var q     = (document.getElementById('searchInput').value || '').toLowerCase().trim();
  var range = getPeriodRange(activePeriod);
  var from  = range[0], to = range[1];
  var vis   = 0;

  rows.forEach(function(r){
    var mF    = activeFilter === 'all' || r.dataset.status === activeFilter;
    var mS    = !q || (r.dataset.search || '').includes(q);
    var mSubj = activeSubj === 'all' || r.dataset.subject === activeSubj;
    var mDate = true;
    if(from && to){
      var ds = r.dataset.datestr || '';
      mDate = ds >= from && ds <= to;
    } else if(from){
      mDate = (r.dataset.datestr || '') >= from;
    } else if(to){
      mDate = (r.dataset.datestr || '') <= to;
    }
    var show = mF && mS && mSubj && mDate;
    r.classList.toggle('row-hidden', !show);
    if(show) vis++;
  });

  sortRows();

  var e2 = document.getElementById('cntVisF');
  if(e2) e2.textContent = vis;
  if(noRow) noRow.style.display = (vis === 0 && rows.length > 0) ? '' : 'none';
}

function sortRows(){
  var visible = rows.filter(function(r){ return !r.classList.contains('row-hidden'); });
  visible.sort(function(a,b){
    if(activeSort === 'newest')  return Number(b.dataset.ts) - Number(a.dataset.ts);
    if(activeSort === 'oldest')  return Number(a.dataset.ts) - Number(b.dataset.ts);
    if(activeSort === 'name_az') return (a.dataset.name||'').localeCompare(b.dataset.name||'');
    if(activeSort === 'name_za') return (b.dataset.name||'').localeCompare(a.dataset.name||'');
    return 0;
  });
  visible.forEach(function(r){ tbody.appendChild(r); });
  if(noRow) tbody.appendChild(noRow);
}

/* filter + search wiring */
document.querySelectorAll('.ftab').forEach(function(tab){
  tab.addEventListener('click', function(){
    document.querySelectorAll('.ftab').forEach(function(t){ t.classList.remove('on'); });
    this.classList.add('on');
    activeFilter = this.dataset.filter;
    applyFilters();
  });
});

var st;
document.getElementById('searchInput').addEventListener('input', function(){
  clearTimeout(st);
  st = setTimeout(applyFilters, 180);
});

document.getElementById('filterPeriod').addEventListener('change', function(){
  activePeriod = this.value;
  var g = document.getElementById('customDateGroup');
  if(this.value === 'custom'){ g.style.display = 'flex'; }
  else { g.style.display = 'none'; dateFrom = ''; dateTo = ''; }
  applyFilters();
});
document.getElementById('dateFrom').addEventListener('change', function(){ dateFrom = this.value; applyFilters(); });
document.getElementById('dateTo').addEventListener('change',   function(){ dateTo   = this.value; applyFilters(); });
document.getElementById('filterSort').addEventListener('change', function(){ activeSort = this.value; applyFilters(); });
document.getElementById('filterSubject').addEventListener('change', function(){ activeSubj = this.value; applyFilters(); });
document.getElementById('filterReset').addEventListener('click', function(){
  activeFilter = 'all'; activePeriod = 'all'; activeSort = 'newest'; activeSubj = 'all';
  dateFrom = ''; dateTo = '';
  document.getElementById('filterPeriod').value  = 'all';
  document.getElementById('filterSort').value    = 'newest';
  document.getElementById('filterSubject').value = 'all';
  document.getElementById('searchInput').value   = '';
  document.getElementById('customDateGroup').style.display = 'none';
  document.getElementById('dateFrom').value = '';
  document.getElementById('dateTo').value   = '';
  document.querySelectorAll('.ftab').forEach(function(t){ t.classList.remove('on'); });
  document.querySelector('.ftab[data-filter="all"]').classList.add('on');
  applyFilters();
});
document.getElementById('thDate').addEventListener('click', function(){
  if(activeSort === 'newest'){ activeSort = 'oldest'; }
  else if(activeSort === 'oldest'){ activeSort = 'newest'; }
  else { activeSort = 'newest'; }
  this.classList.toggle('sort-asc', activeSort === 'oldest');
  this.classList.toggle('sort-desc', activeSort === 'newest');
  document.getElementById('filterSort').value = activeSort;
  applyFilters();
});

/* selection + bulk */
function selectedIds(){
  return Array.from(document.querySelectorAll('.row-check:checked')).map(function(c){ return c.value; });
}
function syncBulkBar(){
  var ids = selectedIds();
  document.getElementById('bulkCount').textContent = ids.length;
  bulkBar.classList.toggle('show', ids.length > 0);
  var all = document.querySelectorAll('.row-check').length;
  var checked = ids.length;
  document.getElementById('selectAll').checked = all > 0 && checked === all;
  document.getElementById('selectAll').indeterminate = checked > 0 && checked < all;
}
document.getElementById('selectAll').addEventListener('change', function(){
  document.querySelectorAll('.row-check').forEach(function(c){ c.checked = document.getElementById('selectAll').checked; });
  syncBulkBar();
});
document.querySelectorAll('.row-check').forEach(function(c){
  c.addEventListener('change', syncBulkBar);
});
document.getElementById('bulkClear').addEventListener('click', function(){
  document.querySelectorAll('.row-check').forEach(function(c){ c.checked = false; });
  syncBulkBar();
});

function postBulk(action, ids, onDone){
  fetch(bulkUrl, {
    method: 'POST',
    headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': csrf, 'Accept':'application/json' },
    body: JSON.stringify({ ids: ids, action: action })
  })
  .then(function(r){ return r.json(); })
  .then(function(d){ if(d.ok && onDone) onDone(d); else toast('Something went wrong.', 'error'); })
  .catch(function(){ toast('Network error.', 'error'); });
}

document.getElementById('bulkRead').addEventListener('click', function(){
  var ids = selectedIds();
  if(!ids.length) return;
  postBulk('read', ids, function(){
    ids.forEach(function(id){
      var tr = document.querySelector('#tbody tr[data-id="'+id+'"]');
      if(tr && tr.dataset.status === 'new'){ setRowRead(tr, true); }
    });
    syncBulkBar();
    toast(ids.length + ' message' + (ids.length===1?'':'s') + ' marked as read.', 'success');
  });
});
document.getElementById('bulkDelete').addEventListener('click', function(){
  var ids = selectedIds();
  if(!ids.length) return;
  if(!confirm('Delete ' + ids.length + ' selected message(s)?')) return;
  /* adjust totals before removing rows from the DOM */
  ids.forEach(function(id){
    var tr = document.querySelector('#tbody tr[data-id="'+id+'"]');
    if(tr && tr.dataset.status === 'new'){ totals.unread = Math.max(0, totals.unread - 1); }
    else { totals.read = Math.max(0, totals.read - 1); }
    totals.total = Math.max(0, totals.total - 1);
  });
  postBulk('delete', ids, function(d){
    ids.forEach(function(id){
      var tr = document.querySelector('#tbody tr[data-id="'+id+'"]');
      if(tr) tr.remove();
    });
    rows = Array.from(document.querySelectorAll('#tbody tr[data-id]'));
    writeTotals();
    syncBulkBar();
    applyFilters();
    toast(d.msg || 'Deleted.', 'success');
  });
});

/* per-row mark read / unread toggle */
function setRowRead(tr, isRead){
  tr.dataset.status = isRead ? 'read' : 'new';
  var name = tr.querySelector('.sender-name');
  var av   = tr.querySelector('.row-av .unread-dot');
  var badge = tr.querySelector('.badge');
  var toggle = tr.querySelector('.ab-toggle');
  tr.classList.toggle('unread', !isRead);
  if(isRead && av){ av.remove(); }
  if(!isRead && !av){
    var nd = document.createElement('span'); nd.className = 'unread-dot'; tr.querySelector('.row-av').appendChild(nd);
  }
  if(badge){
    badge.className = 'badge b-' + (isRead ? 'read' : 'new');
    badge.innerHTML = '<span class="badge-dot"></span>' + (isRead ? 'Read' : 'New');
  }
  if(toggle){
    toggle.dataset.read = isRead ? '1' : '0';
    toggle.innerHTML = isRead
      ? '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9l9 6 9-6"/></svg> Unread'
      : '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Read';
  }
}
function writeTotals(){
  document.getElementById('statUnread').textContent = totals.unread;
  document.getElementById('statRead').textContent   = totals.read;
  document.getElementById('statTotal').textContent  = totals.total;
  document.getElementById('cntUnread').textContent  = totals.unread;
  document.getElementById('cntRead').textContent    = totals.read;
  var chip = document.getElementById('sidebarUnread');
  if(chip){
    if(totals.unread > 0){ chip.textContent = totals.unread; chip.style.display = ''; }
    else { chip.style.display = 'none'; }
  }
}

document.querySelectorAll('.ab-toggle').forEach(function(btn){
  btn.addEventListener('click', function(e){
    e.preventDefault();
    var id = btn.dataset.id;
    var tr = document.querySelector('#tbody tr[data-id="'+id+'"]');
    if(!tr) return;
    var url = "{{ route('admin.messages.toggle-read', '__ID__') }}".replace('__ID__', id);
    fetch(url, {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': csrf, 'Accept':'application/json' }
    })
    .then(function(r){ return r.json(); })
    .then(function(d){
      if(!d.ok) return;
      setRowRead(tr, d.is_read);
      if(d.is_read){ totals.unread = Math.max(0, totals.unread - 1); totals.read++; }
      else { totals.read = Math.max(0, totals.read - 1); totals.unread++; }
      totals.total = totals.unread + totals.read;
      writeTotals();
      toast(d.is_read ? 'Marked as read.' : 'Marked as unread.', 'success');
    })
    .catch(function(){ toast('Network error.', 'error'); });
  });
});

/* toast */
function toast(msg, type){
  var icons = {
    success: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
    error: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
  };
  var el = document.createElement('div');
  el.className = 'toast ' + (type === 'error' ? 'toast-err' : 'toast-ok');
  el.innerHTML = (icons[type]||icons.success) + '<span>' + msg + '</span><button class="toast-x" onclick="this.parentElement.remove()">✕</button>';
  document.getElementById('toastWrap').appendChild(el);
  setTimeout(function(){
    el.style.transition = 'opacity .3s,transform .3s';
    el.style.opacity = '0';
    el.style.transform = 'translateX(20px)';
    setTimeout(function(){ el.remove(); }, 300);
  }, 4200);
}

@if(session('success'))
  setTimeout(function(){ toast(@json(session('success')), 'success'); }, 200);
@endif

syncBulkBar();
applyFilters();
})();
</script>
@endpush
