@extends('layouts.admin')

@section('page_title', 'Partnership Requests')
@section('page_subtitle', 'Review partnership inquiries')
@section('sidebar_partnerships', 'active')

@push('page_styles')
<style>
.toolbar{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:16px;flex-wrap:wrap;}
.toolbar-left{display:flex;align-items:center;gap:8px;flex-wrap:wrap;}
.si{position:absolute;left:11px;top:50%;transform:translateY(-50%);width:13px;height:13px;color:var(--text3);pointer-events:none;}
.search-input{width:220px;height:36px;padding:0 12px 0 33px;background:var(--surface);border:1px solid var(--border2);border-radius:var(--r-sm);font-size:12.5px;color:var(--text);font-family:var(--font);outline:none;transition:border-color var(--ease),box-shadow var(--ease),width .3s ease;}
.search-input::placeholder{color:var(--text3);}
.search-input:focus{border-color:var(--a);box-shadow:0 0 0 3px var(--a-glow);width:260px;}
.filter-btn{display:inline-flex;align-items:center;gap:5px;height:36px;padding:0 13px;background:var(--surface);border:1px solid var(--border2);border-radius:var(--r-sm);font-size:12px;font-weight:500;color:var(--text2);cursor:pointer;font-family:var(--font);transition:all var(--ease);}
.filter-btn:hover,.filter-btn.on{border-color:var(--a);color:var(--a);background:var(--a-lt);}
.cnt-badge{background:var(--a);color:#fff;font-size:9.5px;font-weight:700;padding:1px 5px;border-radius:100px;font-family:var(--mono);}
.main-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh);overflow:hidden;animation:fadeUp .4s .15s ease both;}
.card-head{padding:14px 20px;border-bottom:1px solid var(--border);background:var(--surface2);display:flex;align-items:center;justify-content:space-between;}
.card-head-left{display:flex;align-items:center;gap:10px;}
.card-head-icon{width:30px;height:30px;border-radius:8px;background:var(--a-lt);color:var(--a);display:flex;align-items:center;justify-content:center;}
.card-head-icon svg{width:14px;height:14px;}
.card-head-title{font-size:12px;font-weight:600;color:var(--text2);text-transform:uppercase;letter-spacing:.09em;font-family:var(--mono);}
.card-head-count{font-size:10.5px;color:var(--text3);font-family:var(--mono);background:var(--surface);border:1px solid var(--border2);padding:2px 8px;border-radius:100px;}
.table-wrap{overflow-x:auto;}
.table-wrap::-webkit-scrollbar{height:5px;}
.table-wrap::-webkit-scrollbar-thumb{background:var(--border2);border-radius:100px;}
table{width:100%;border-collapse:collapse;min-width:1100px;}
thead th{padding:10px 16px;text-align:left;font-size:10px;font-family:var(--mono);letter-spacing:.12em;text-transform:uppercase;color:var(--text3);background:var(--surface2);border-bottom:1px solid var(--border);font-weight:500;white-space:nowrap;}
thead th:last-child{text-align:right;}
tbody tr{border-bottom:1px solid var(--border);transition:background var(--ease);}
tbody tr:last-child{border-bottom:none;}
tbody tr:hover{background:var(--surface2);}
td{padding:13px 16px;font-size:13px;vertical-align:middle;}
.person-cell{display:flex;align-items:center;gap:11px;}
.person-av{width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,var(--a),var(--a2));color:#fff;font-size:13px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-family:var(--mono);}
.person-name{font-weight:600;color:var(--text);font-size:13.5px;}
.person-id{font-size:10.5px;color:var(--text3);font-family:var(--mono);margin-top:1px;}
.email-cell{font-size:12px;color:var(--text2);font-family:var(--mono);}
.org-cell{font-weight:600;color:var(--text);}
.type-cell{font-size:12px;color:var(--text2);}
.mono-pill{display:inline-flex;align-items:center;gap:5px;background:var(--surface2);border:1px solid var(--border2);color:var(--text3);padding:3px 10px;border-radius:100px;font-size:11px;font-family:var(--mono);}
.status-pill{display:inline-flex;align-items:center;gap:5px;padding:4px 10px;border-radius:100px;font-size:10.5px;font-weight:600;font-family:var(--mono);text-transform:uppercase;letter-spacing:.05em;}
.s-pending{background:var(--amber-lt);color:#b45309;border:1px solid rgba(245,158,11,.22);}
.s-approved{background:var(--green-lt);color:#059669;border:1px solid rgba(5,196,138,.22);}
.s-rejected{background:var(--red-lt);color:#b91c1c;border:1px solid rgba(240,68,68,.22);}
[data-theme="dark"] .s-pending{color:var(--amber);}
[data-theme="dark"] .s-approved{color:var(--green);}
[data-theme="dark"] .s-rejected{color:var(--red);}
.status-dot{width:5px;height:5px;border-radius:50%;background:currentColor;display:inline-block;}
.pri-pill{display:inline-flex;align-items:center;gap:4px;padding:3px 8px;border-radius:100px;font-size:10px;font-weight:700;font-family:var(--mono);text-transform:uppercase;}
.pri-high{background:var(--green-lt);color:#059669;border:1px solid rgba(5,196,138,.22);}
.pri-medium{background:var(--amber-lt);color:#b45309;border:1px solid rgba(245,158,11,.22);}
.pri-low{background:var(--red-lt);color:#b91c1c;border:1px solid rgba(240,68,68,.22);}
[data-theme="dark"] .pri-high{color:var(--green);}
[data-theme="dark"] .pri-medium{color:var(--amber);}
[data-theme="dark"] .pri-low{color:var(--red);}
.score-num{font-size:11px;font-weight:700;font-family:var(--mono);color:var(--text3);}
.serial{font-size:11.5px;color:var(--text3);font-family:var(--mono);}
.actions{display:flex;align-items:center;justify-content:flex-end;gap:5px;}
.act-btn{display:inline-flex;align-items:center;gap:4px;padding:5px 11px;border-radius:7px;font-size:11.5px;font-weight:500;text-decoration:none;border:1px solid transparent;transition:all .15s;cursor:pointer;font-family:var(--font);white-space:nowrap;}
.act-btn svg{width:11px;height:11px;}
.act-view{background:var(--a-lt);color:var(--a);border-color:rgba(110,86,247,.18);}
.act-view:hover{background:var(--a);color:#fff;transform:translateY(-1px);}
.act-del{background:var(--red-lt);color:var(--red);border-color:rgba(240,68,68,.18);}
.act-del:hover{background:var(--red);color:#fff;transform:translateY(-1px);}
.empty-state{padding:72px 24px;text-align:center;}
.empty-icon-wrap{width:72px;height:72px;border-radius:20px;background:var(--surface2);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;font-size:26px;margin:0 auto 18px;animation:float 3s ease-in-out infinite;}
.empty-state h3{font-size:16px;font-weight:700;color:var(--text);margin-bottom:6px;}
.empty-state p{font-size:13px;color:var(--text3);}
.alert-ok{background:rgba(5,196,138,.08);border:1px solid rgba(5,196,138,.22);color:#065f46;padding:12px 16px;border-radius:var(--r-sm);font-size:13px;margin-bottom:16px;display:flex;align-items:center;gap:10px;animation:fadeUp .3s ease;}
[data-theme="dark"] .alert-ok{color:#6ee7b7;}
.alert-ok svg{width:15px;height:15px;flex-shrink:0;}
.pagination-wrap{display:flex;align-items:center;justify-content:space-between;padding:14px 20px;border-top:1px solid var(--border);background:var(--surface2);flex-wrap:wrap;gap:10px;}
.page-info{font-size:12px;color:var(--text3);font-family:var(--mono);}
.page-btns{display:flex;gap:4px;}
.page-btn{width:30px;height:30px;border-radius:7px;display:flex;align-items:center;justify-content:center;font-size:11.5px;font-weight:600;border:1px solid var(--border2);background:var(--surface);color:var(--text2);cursor:pointer;transition:all .15s;font-family:var(--mono);text-decoration:none;}
.page-btn:hover{border-color:var(--a);color:var(--a);background:var(--a-lt);}
.page-btn.cur{background:var(--a);border-color:var(--a);color:#fff;}
.page-btn svg{width:12px;height:12px;}
@keyframes float{0%,100%{transform:translateY(0)}50%{transform:translateY(-6px)}}
@media(max-width:600px){.stats-grid{grid-template-columns:1fr 1fr}}
</style>
@endpush
@section('content')
{{-- Delete Confirm Modal --}}
<div class="overlay" id="deleteOverlay" role="dialog" aria-modal="true">
  <div class="modal">
    <button type="button" class="modal-x" onclick="closeModal()">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
    <div class="modal-ico">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg>
    </div>
    <h3>Delete Partnership?</h3>
    <p>This will permanently remove the request from <strong id="modalPartnerName"></strong>. This action cannot be undone.</p>
    <div class="modal-acts">
      <button class="modal-cancel" onclick="closeModal()">Cancel</button>
      <button class="modal-del" onclick="confirmDelete()">Yes, Delete</button>
    </div>
  </div>
</div>
<form id="deleteForm" method="POST" style="display:none;">@csrf @method('DELETE')</form>

@php
  $total    = $partnerships->count();
  $pending  = $partnerships->where('status','pending')->count();
  $approved = $partnerships->where('status','approved')->count();
  $rejected = $partnerships->where('status','rejected')->count();
@endphp

{{-- STATS --}}
<div class="stats-grid">
      <div class="stat">
        <div class="stat-icon si-amber">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        </div>
        <div class="stat-body">
          <div class="stat-lbl">Pending</div>
          <div class="stat-val sv-amber">{{ $pending }}</div>
          <div class="stat-foot">Awaiting review</div>
        </div>
      </div>
      <div class="stat">
        <div class="stat-icon si-green">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="stat-body">
          <div class="stat-lbl">Approved</div>
          <div class="stat-val sv-green">{{ $approved }}</div>
          <div class="stat-foot">Active partners</div>
        </div>
      </div>
      <div class="stat">
        <div class="stat-icon si-red">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="stat-body">
          <div class="stat-lbl">Rejected</div>
          <div class="stat-val sv-red">{{ $rejected }}</div>
          <div class="stat-foot">Declined requests</div>
        </div>
      </div>
      <div class="stat">
        <div class="stat-icon si-a">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
        </div>
        <div class="stat-body">
          <div class="stat-lbl">Total</div>
          <div class="stat-val sv-a">{{ $total }}</div>
          <div class="stat-foot">All requests</div>
        </div>
      </div>
    </div>

    {{-- TOOLBAR --}}
    <div class="toolbar">
      <div class="toolbar-left">
        <div class="search-wrap">
          <svg class="si" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
          <input type="text" class="search-input" id="searchInput" placeholder="Search partnerships…" oninput="filterTable()">
        </div>
        <button class="filter-btn on" id="fAll"      onclick="setFilter('all',this)">All <span class="cnt-badge">{{ $total }}</span></button>
        <button class="filter-btn"    id="fPending"  onclick="setFilter('pending',this)">
          <span style="width:6px;height:6px;border-radius:50%;background:var(--amber);display:inline-block;"></span>Pending
        </button>
        <button class="filter-btn"    id="fApproved" onclick="setFilter('approved',this)">
          <span style="width:6px;height:6px;border-radius:50%;background:var(--green);display:inline-block;"></span>Approved
        </button>
        <button class="filter-btn"    id="fRejected" onclick="setFilter('rejected',this)">Rejected</button>
      </div>
    </div>

    {{-- MAIN CARD --}}
    <div class="main-card">
      <div class="card-head">
        <div class="card-head-left">
          <div class="card-head-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
          </div>
          <span class="card-head-title">All Partnership Requests</span>
        </div>
        <span class="card-head-count" id="visibleCount">{{ $total }} total</span>
      </div>

      @if($partnerships->isEmpty())
      <div class="empty-state">
        <div class="empty-icon-wrap">🤝</div>
        <h3>No partnership requests yet</h3>
        <p>Incoming applications will appear here</p>
      </div>
      @else
      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th style="width:50px;">#</th>
              <th>Applicant</th>
              <th>Email</th>
              <th>Organisation</th>
              <th>Type</th>
              <th>Location</th>
              <th>Partnership</th>
              <th>Timeline</th>
              <th>Priority</th>
              <th>Status</th>
              <th>Reviewed By</th>
              <th style="text-align:right;">Actions</th>
            </tr>
          </thead>
          <tbody id="tableBody">
            @foreach($partnerships as $p)
            @php
              $score  = $p->priority_score ?? 0;
              $priCls = $score >= 30 ? 'pri-high' : ($score >= 10 ? 'pri-medium' : 'pri-low');
              $priLbl = $score >= 30 ? 'High'     : ($score >= 10 ? 'Medium'     : 'Low');
              $init   = strtoupper(substr($p->name ?? 'A', 0, 1));
              $srch   = strtolower(($p->name??'').' '.($p->email??'').' '.($p->organization_name??'').' '.($p->organization_type??'').' '.($p->location??'').' '.($p->partnership_type??'').' '.($p->timeline??''));
            @endphp
            <tr class="part-row"
                data-status="{{ $p->status }}"
                data-search="{{ $srch }}"
                style="animation:fadeUp 0.35s {{ $loop->index*0.03 }}s ease both;opacity:0;animation-fill-mode:both;">
              <td><span class="serial">{{ str_pad($loop->iteration,2,'0',STR_PAD_LEFT) }}</span></td>
              <td>
                <div class="person-cell">
                  <div class="person-av">{{ $init }}</div>
                  <div>
                    <div class="person-name">{{ $p->name }}</div>
                    <div class="person-id">#{{ $p->id }}</div>
                  </div>
                </div>
              </td>
              <td><span class="email-cell">{{ $p->email }}</span></td>
              <td class="org-cell">{{ $p->organization_name ?? '—' }}</td>
              <td class="type-cell">{{ $p->organization_type ?? '—' }}</td>
              <td>
                @if($p->location)
                  <span class="mono-pill">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:10px;height:10px;"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    {{ $p->location }}
                  </span>
                @else —
                @endif
              </td>
              <td style="font-weight:500;color:var(--text);">{{ ucfirst($p->partnership_type ?? '—') }}</td>
              <td><span class="mono-pill">{{ ucfirst(str_replace('_',' ',$p->timeline ?? '—')) }}</span></td>
              <td>
                <span class="pri-pill {{ $priCls }}">
                  <span class="status-dot"></span>{{ $priLbl }}
                </span>
                <span class="score-num" style="margin-left:4px;">{{ $score }}</span>
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
                  <span style="font-weight:600;color:var(--text);font-size:12.5px;">{{ $p->reviewer->name }}</span>
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
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/></svg>
                    Delete
                  </button>
                </div>
              </td>
            </tr>
            @endforeach
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
            <a href="{{ $partnerships->previousPageUrl() }}" class="page-btn"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg></a>
          @endif
          @foreach($partnerships->getUrlRange(1,$partnerships->lastPage()) as $page=>$url)
            <a href="{{ $url }}" class="page-btn {{ $partnerships->currentPage()==$page?'cur':'' }}">{{ $page }}</a>
          @endforeach
          @if($partnerships->hasMorePages())
            <a href="{{ $partnerships->nextPageUrl() }}" class="page-btn"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg></a>
          @else
            <span class="page-btn" style="opacity:.4;cursor:not-allowed;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg></span>
          @endif
        </div>
      </div>
      @endif
      @endif

    </div>{{-- /main-card --}}
</div>

@push('page_scripts')
<script>
(function(){
'use strict';
var activeFilter='all';
window.setFilter=function(f,btn){
  activeFilter=f;
  document.querySelectorAll('.filter-btn').forEach(function(b){b.classList.remove('on');});
  if(btn)btn.classList.add('on');
  filterTable();
};

window.filterTable=function(){
  var q=document.getElementById('searchInput').value.toLowerCase().trim();
  var rows=document.querySelectorAll('.part-row');
  var visible=0;
  rows.forEach(function(r){
    var mF=activeFilter==='all'||(r.getAttribute('data-status')===activeFilter);
    var mS=!q||(r.getAttribute('data-search')||'').includes(q);
    var show=mF&&mS;
    r.style.display=show?'':'none';
    if(show)visible++;
  });
  document.getElementById('visibleCount').textContent=visible+' total';
};

var pendingUrl=null;
window.openModal=function(id,name,url){
  pendingUrl=url;
  document.getElementById('modalPartnerName').textContent='"'+name+'"';
  document.getElementById('deleteOverlay').classList.add('open');
};
window.closeModal=function(){document.getElementById('deleteOverlay').classList.remove('open');pendingUrl=null;};
window.confirmDelete=function(){if(!pendingUrl)return;var f=document.getElementById('deleteForm');f.action=pendingUrl;f.submit();};
document.getElementById('deleteOverlay').addEventListener('click',function(e){if(e.target===this)closeModal();});
document.addEventListener('keydown',function(e){if(e.key==='Escape')closeModal();});
})();
</script>
@endpush
@endsection