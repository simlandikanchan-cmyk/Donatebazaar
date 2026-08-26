@push('page_css')
@vite('resources/css/admin/entries/volunteers.css')
@endpush

@extends('layouts.admin')

@section('sidebar_volunteer_assignments', 'active')
@section('page_title', 'Volunteer Assignments')
@section('page_subtitle', 'Manage volunteer assignments to events & campaigns')

@push('page_styles')
<style>
.main-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh);overflow:hidden;}
.card-head{display:flex;align-items:center;gap:10px;padding:14px 20px;border-bottom:1px solid var(--border);background:var(--surface2);}
.card-head-icon{width:30px;height:30px;border-radius:8px;background:var(--a-lt);color:var(--a);display:flex;align-items:center;justify-content:center;}
.card-head-icon svg{width:14px;height:14px;}
.card-head-title{font-size:11.5px;font-weight:700;color:var(--text2);text-transform:uppercase;letter-spacing:.09em;font-family:var(--mono);}
.card-head-count{font-size:10.5px;color:var(--text3);font-family:var(--mono);background:var(--surface);border:1px solid var(--border2);padding:2px 8px;border-radius:100px;}
.toolbar{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:14px;flex-wrap:wrap;}
.search-wrap{position:relative;}
.search-wrap .si{position:absolute;left:11px;top:50%;transform:translateY(-50%);width:13px;height:13px;color:var(--text3);pointer-events:none;}
.search-input{width:260px;height:36px;padding:0 14px 0 33px;background:var(--surface);border:1px solid var(--border2);border-radius:var(--r-sm);font-size:12.5px;color:var(--text);font-family:var(--font);outline:none;transition:border-color var(--ease),box-shadow var(--ease);}
.search-input::placeholder{color:var(--text3);}
.search-input:focus{border-color:var(--a);box-shadow:0 0 0 3px var(--a-glow);}
.filter-btn{display:inline-flex;align-items:center;gap:6px;height:36px;padding:0 13px;background:var(--surface);border:1px solid var(--border2);border-radius:var(--r-sm);font-size:12px;font-weight:500;color:var(--text2);cursor:pointer;font-family:var(--font);transition:all var(--ease);text-decoration:none;}
.filter-btn:hover,.filter-btn.on{border-color:var(--a);color:var(--a);background:var(--a-lt);}
.table-wrap{overflow-x:auto;}
table{width:100%;border-collapse:collapse;}
thead th{padding:10px 18px;text-align:left;font-size:10px;font-family:var(--mono);letter-spacing:.12em;text-transform:uppercase;color:var(--text3);background:var(--surface2);border-bottom:1px solid var(--border);font-weight:500;white-space:nowrap;}
thead th:last-child{text-align:right;}
tbody tr{border-bottom:1px solid var(--border);transition:background var(--ease);}
tbody tr:last-child{border-bottom:none;}
tbody tr:hover{background:var(--surface2);}
td{padding:13px 18px;font-size:13px;vertical-align:middle;}
.vol-name{font-weight:600;color:var(--text);font-size:13.5px;}
.vol-sub{font-size:11.5px;color:var(--text3);}
.target-cell{font-size:12.5px;color:var(--text2);}
.role-pill{display:inline-block;padding:3px 10px;border-radius:100px;font-size:11px;font-weight:600;font-family:var(--mono);background:var(--a-lt);color:var(--a);border:1px solid rgba(37,99,235,.18);}
.status-pill{display:inline-flex;align-items:center;gap:5px;padding:4px 10px;border-radius:100px;font-size:10.5px;font-weight:600;font-family:var(--mono);text-transform:uppercase;letter-spacing:.05em;}
.s-active{background:rgba(5,196,138,.12);color:var(--green);border:1px solid rgba(5,196,138,.22);}
.s-completed{background:rgba(59,130,246,.12);color:var(--blue);border:1px solid rgba(59,130,246,.22);}
.s-cancelled{background:rgba(100,116,139,.08);color:var(--text3);border:1px solid var(--border2);}
.actions{display:flex;align-items:center;justify-content:flex-end;gap:5px;}
.act-btn{display:inline-flex;align-items:center;gap:4px;padding:5px 10px;border-radius:7px;font-size:11.5px;font-weight:500;text-decoration:none;border:1px solid transparent;transition:all .15s;cursor:pointer;font-family:var(--font);white-space:nowrap;}
.act-btn svg{width:11px;height:11px;}
.act-edit{background:var(--blue-lt);color:var(--blue);border-color:rgba(59,130,246,.18);}
.act-edit:hover{background:var(--blue);color:#fff;transform:translateY(-1px);}
.act-del{background:var(--red-lt);color:var(--red);border-color:rgba(240,68,68,.18);}
.act-del:hover{background:var(--red);color:#fff;transform:translateY(-1px);}
.empty-state{padding:64px 24px;text-align:center;}
.empty-icon-wrap{width:64px;height:64px;border-radius:18px;background:var(--surface2);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;font-size:24px;margin:0 auto 16px;}
.empty-state h3{font-size:16px;font-weight:700;color:var(--text);margin-bottom:6px;}
.pagination-wrap{display:flex;align-items:center;justify-content:space-between;padding:14px 20px;border-top:1px solid var(--border);background:var(--surface2);flex-wrap:wrap;gap:10px;}
.page-info{font-size:12px;color:var(--text3);font-family:var(--mono);}
.page-btns{display:flex;gap:4px;}
.page-btn{height:30px;min-width:30px;padding:0 9px;border-radius:7px;display:flex;align-items:center;justify-content:center;font-size:11.5px;font-weight:600;border:1px solid var(--border2);background:var(--surface);color:var(--text2);cursor:pointer;transition:all .15s;font-family:var(--mono);text-decoration:none;}
.page-btn:hover{border-color:var(--a);color:var(--a);background:var(--a-lt);}
.page-btn.cur{background:var(--a);border-color:var(--a);color:#fff;}
@media(max-width:640px){.table-wrap{min-width:560px}.toolbar{flex-direction:column;align-items:stretch}.search-input{width:100%}}
@media(max-width:480px){
  #asnTable thead{display:none}
  #asnTable tbody tr{display:flex;flex-direction:column;padding:14px 16px;border-bottom:1px solid var(--border);gap:8px}
  #asnTable tbody tr td{padding:0;border:none;display:flex;align-items:center;gap:8px}
  #asnTable tbody tr td::before{content:attr(data-label);font-size:10px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.06em;font-family:var(--mono);min-width:75px;flex-shrink:0}
  #asnTable .actions{justify-content:flex-start;width:100%}
  #asnTable td[data-label="Actions"]{flex-wrap:wrap}
  #asnTable td[data-label="Actions"]::before{content:"Actions";min-width:auto;margin-right:auto}
}
</style>
@endpush

@section('content')
<div class="hero">
  <div class="hero-left">
    <div class="hero-tag"><span class="hero-tag-dot"></span>Volunteers</div>
    <div class="hero-name">Volunteer Assignments</div>
    <div class="hero-sub">Assign volunteers to events & campaigns and track their work.</div>
    <div class="hero-badges">
      <span class="hero-badge hb-teal">{{ $stats['total'] }} total</span>
      @if($stats['active'] > 0)
        <span class="hero-badge hb-amber">● {{ $stats['active'] }} active</span>
      @endif
      @if($stats['completed'] > 0)
        <span class="hero-badge hb-blue">✓ {{ $stats['completed'] }} completed</span>
      @endif
    </div>
  </div>
  <div class="hero-right">
    <a href="{{ route('admin.volunteer_assignments.create') }}" class="hero-btn hero-btn-primary">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
      New Assignment
    </a>
  </div>
</div>

<div class="toolbar">
  <form method="GET" action="{{ route('admin.volunteer_assignments.index') }}" style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;">
    <div class="search-wrap">
      <svg class="si" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
      <input type="text" class="search-input" name="search" value="{{ request('search') }}" placeholder="Search volunteer or role…">
    </div>
    <select class="filter-btn" name="status" onchange="this.form.submit()">
      <option value="">All statuses</option>
      <option value="active" {{ request('status')==='active'?'selected':'' }}>Active</option>
      <option value="completed" {{ request('status')==='completed'?'selected':'' }}>Completed</option>
      <option value="cancelled" {{ request('status')==='cancelled'?'selected':'' }}>Cancelled</option>
    </select>
    <button type="submit" class="filter-btn">Filter</button>
    @if(request('search') || request('status'))
      <a href="{{ route('admin.volunteer_assignments.index') }}" class="filter-btn">Clear</a>
    @endif
  </form>
</div>

<div class="main-card">
  <div class="card-head">
    <div class="card-head-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg></div>
    <span class="card-head-title">Assignments</span>
    <span class="card-head-count">{{ $assignments->total() }} total · {{ $stats['active'] }} active</span>
  </div>

  @if($assignments->isEmpty())
    <div class="empty-state">
      <div class="empty-icon-wrap">🤝</div>
      <h3>No assignments yet</h3>
      <p>Assign volunteers to events or campaigns to track their work.</p>
      <a href="{{ route('admin.volunteer_assignments.create') }}" class="add-btn" style="margin:0 auto;">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>New Assignment
      </a>
    </div>
  @else
    <div class="table-wrap">
       <table id="asnTable">
        <thead>
          <tr>
            <th style="width:50px;">#</th>
            <th>Volunteer</th>
            <th>Assigned To</th>
            <th>Role</th>
            <th>Period</th>
            <th>Status</th>
            <th style="text-align:right;">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($assignments as $a)
          <tr>
            <td data-label="#"><span style="font-size:11.5px;color:var(--text3);font-family:var(--mono);">{{ str_pad($loop->iteration,2,'0',STR_PAD_LEFT) }}</span></td>
            <td data-label="Volunteer">
              <div class="vol-name">{{ $a->volunteer->user->name ?? 'Volunteer #'.$a->volunteer_id }}</div>
              <div class="vol-sub">{{ $a->volunteer->user->email ?? '' }}</div>
            </td>
            <td data-label="Assigned To">
               <div class="target-cell">
                @if($a->event)📅 {{ $a->event->title }}@endif
                @if($a->campaign)🎯 {{ $a->campaign->title }}@endif
                @if(!$a->event && !$a->campaign)<span style="color:var(--text3);">—</span>@endif
              </div>
            </td>
            <td data-label="Role"><span class="role-pill">{{ $a->role }}</span></td>
            <td data-label="Period">
              <span style="font-size:12px;color:var(--text3);">
                {{ $a->start_date?->format('M d, Y') ?? '—' }}
                @if($a->end_date) → {{ $a->end_date->format('M d, Y') }}@endif
              </span>
            </td>
            <td data-label="Status">
               <span class="status-pill s-{{ $a->status }}">
                <span style="width:5px;height:5px;border-radius:50%;background:currentColor;display:inline-block;"></span>
                {{ ucfirst($a->status) }}
              </span>
            </td>
            <td data-label="Actions">
               <div class="actions">
                <a href="{{ route('admin.volunteer_assignments.edit', $a->id) }}" class="btn btn-secondary act-btn act-edit"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>Edit</a>
                <form method="POST" action="{{ route('admin.volunteer_assignments.destroy', $a->id) }}" data-confirm="Remove this assignment?">@csrf @method('DELETE')
                  <button type="submit" class="btn btn-red act-btn act-del"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/></svg>Delete</button>
                </form>
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    @if($assignments->hasPages())
    <div class="pagination-wrap">
      <span class="page-info">Showing {{ $assignments->firstItem() }}–{{ $assignments->lastItem() }} of {{ $assignments->total() }}</span>
      <div class="page-btns">
        @if($assignments->onFirstPage())<span class="page-btn" style="opacity:.4;cursor:not-allowed;">‹</span>
        @else<a href="{{ $assignments->previousPageUrl() }}" class="page-btn">‹</a>@endif
        @foreach($assignments->getUrlRange(1,$assignments->lastPage()) as $page=>$url)
          <a href="{{ $url }}" class="page-btn {{ $assignments->currentPage()==$page?'cur':'' }}">{{ $page }}</a>
        @endforeach
        @if($assignments->hasMorePages())<a href="{{ $assignments->nextPageUrl() }}" class="page-btn">›</a>
        @else<span class="page-btn" style="opacity:.4;cursor:not-allowed;">›</span>@endif
      </div>
    </div>
    @endif
  @endif
</div>
@endsection
