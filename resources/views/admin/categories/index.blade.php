@extends('layouts.admin')

@section('sidebar_categories', 'active')
@section('page_title', 'Categories')
@section('page_subtitle', 'Manage Categories')

@section('topbar_left')
  <a href="{{ route('admin.categories.create') }}" class="add-btn">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
    Add Category
  </a>
@endsection

@push('page_styles')
<style>
.add-btn{display:inline-flex;align-items:center;gap:7px;height:36px;padding:0 16px;background:var(--a);color:#fff;border:none;border-radius:var(--r-sm);font-size:12.5px;font-weight:600;text-decoration:none;cursor:pointer;transition:opacity .2s,transform .15s;font-family:var(--font);box-shadow:0 4px 14px rgba(37,99,235,.3);}
.add-btn:hover{opacity:.88;transform:translateY(-1px);}
.add-btn svg{width:13px;height:13px;}

.stats-grid{grid-template-columns:repeat(3,1fr);}
.stat{cursor:pointer;transition:transform .15s,box-shadow .2s,border-color .2s;}
.stat:hover{transform:translateY(-2px);box-shadow:var(--sh-lg);}
.stat.stat-on{border-color:var(--a);box-shadow:0 0 0 3px var(--a-glow);}
.stat:nth-child(1){animation-delay:.05s;}.stat:nth-child(1)::after{background:linear-gradient(90deg,var(--a),var(--a2));}
.stat:nth-child(2){animation-delay:.10s;}.stat:nth-child(2)::after{background:linear-gradient(90deg,var(--green),#34d399);}
.stat:nth-child(3){animation-delay:.15s;}.stat:nth-child(3)::after{background:linear-gradient(90deg,var(--text3),#64748b);}
.si-slate{background:rgba(100,116,139,.10);color:var(--text3);}
.sv-slate{color:var(--text3);}

.toolbar{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:12px;flex-wrap:wrap;}
.toolbar-left{display:flex;align-items:center;gap:8px;flex-wrap:wrap;}
.filter-group{display:flex;align-items:center;gap:6px;padding-left:10px;margin-left:2px;border-left:1px solid var(--border2);}
.filter-group:first-child{border-left:none;padding-left:0;margin-left:0;}
.search-wrap{position:relative;}
.search-wrap .si{position:absolute;left:11px;top:50%;transform:translateY(-50%);width:13px;height:13px;color:var(--text3);pointer-events:none;}
.search-input{width:220px;height:36px;padding:0 30px 0 33px;background:var(--surface);border:1px solid var(--border2);border-radius:var(--r-sm);font-size:12.5px;color:var(--text);font-family:var(--font);outline:none;transition:border-color var(--ease),box-shadow var(--ease),width .3s ease;}
.search-input::placeholder{color:var(--text3);}
.search-input:focus{border-color:var(--a);box-shadow:0 0 0 3px var(--a-glow);width:260px;}
.search-clear{position:absolute;right:6px;top:50%;transform:translateY(-50%);width:22px;height:22px;border:none;background:transparent;color:var(--text3);cursor:pointer;display:none;align-items:center;justify-content:center;border-radius:6px;transition:all .15s;}
.search-clear:hover{background:var(--surface2);color:var(--text);}
.search-clear svg{width:12px;height:12px;}
.search-clear.show{display:flex;}
.search-kbd{position:absolute;right:8px;top:50%;transform:translateY(-50%);font-size:10px;color:var(--text3);background:var(--surface2);border:1px solid var(--border2);border-radius:4px;padding:1px 5px;font-family:var(--mono);pointer-events:none;}
.search-kbd.hide{display:none;}
.filter-btn{display:inline-flex;align-items:center;gap:5px;height:36px;padding:0 13px;background:var(--surface);border:1px solid var(--border2);border-radius:var(--r-sm);font-size:12px;font-weight:500;color:var(--text2);cursor:pointer;font-family:var(--font);transition:all var(--ease);white-space:nowrap;}
.filter-btn:hover,.filter-btn.on{border-color:var(--a);color:var(--a);background:var(--a-lt);}
.filter-btn svg{width:12px;height:12px;}
.cnt-badge{background:var(--a);color:#fff;font-size:9.5px;font-weight:700;padding:1px 5px;border-radius:100px;font-family:var(--mono);min-width:15px;text-align:center;display:inline-block;}
.filter-btn:not(.on) .cnt-badge{background:var(--text3);opacity:.7;}
.sort-select{height:36px;padding:0 30px 0 12px;background:var(--surface) url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M7 15l5 5 5-5M7 9l5-5 5 5'/%3E%3C/svg%3E") no-repeat right 8px center/12px 12px;border:1px solid var(--border2);border-radius:var(--r-sm);font-size:12px;font-weight:500;color:var(--text2);cursor:pointer;font-family:var(--font);appearance:none;-webkit-appearance:none;transition:all var(--ease);}
.sort-select:hover,.sort-select:focus{border-color:var(--a);color:var(--a);outline:none;}
.view-toggle{display:flex;background:var(--surface);border:1px solid var(--border2);border-radius:var(--r-sm);overflow:hidden;}
.view-btn{width:36px;height:36px;display:flex;align-items:center;justify-content:center;cursor:pointer;color:var(--text3);border:none;background:transparent;transition:all .15s;}
.view-btn:hover{color:var(--a);}
.view-btn.on{background:var(--a);color:#fff;}
.view-btn svg{width:14px;height:14px;}

.active-filters{display:flex;align-items:center;gap:8px;flex-wrap:wrap;margin-bottom:14px;animation:fadeUp .25s ease both;}
.active-filters.hide{display:none;}
.af-label{font-size:11px;color:var(--text3);font-family:var(--mono);text-transform:uppercase;letter-spacing:.06em;}
.filter-chip{display:inline-flex;align-items:center;gap:6px;background:var(--a-lt);color:var(--a);border:1px solid rgba(37,99,235,.25);padding:4px 6px 4px 11px;border-radius:100px;font-size:11.5px;font-weight:600;}
.chip-x{width:16px;height:16px;border-radius:50%;border:none;background:rgba(37,99,235,.15);color:var(--a);display:flex;align-items:center;justify-content:center;cursor:pointer;transition:background .15s;}
.chip-x:hover{background:var(--a);color:#fff;}
.chip-x svg{width:8px;height:8px;}
.clear-all-btn{font-size:11.5px;font-weight:600;color:var(--text3);background:none;border:none;cursor:pointer;text-decoration:underline;text-underline-offset:2px;}
.clear-all-btn:hover{color:var(--red);}

.main-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh);overflow:hidden;animation:fadeUp .4s .15s ease both;}
.card-head{padding:14px 20px;border-bottom:1px solid var(--border);background:var(--surface2);display:flex;align-items:center;justify-content:space-between;}
.card-head-left{display:flex;align-items:center;gap:10px;}
.card-head-icon{width:30px;height:30px;border-radius:8px;background:var(--a-lt);color:var(--a);display:flex;align-items:center;justify-content:center;}
.card-head-icon svg{width:14px;height:14px;}
.card-head-title{font-size:12px;font-weight:600;color:var(--text2);text-transform:uppercase;letter-spacing:.09em;font-family:var(--mono);}
.card-head-count{font-size:10.5px;color:var(--text3);font-family:var(--mono);background:var(--surface);border:1px solid var(--border2);padding:2px 8px;border-radius:100px;}

.table-wrap{overflow-x:auto;}
table{width:100%;border-collapse:collapse;}
thead th{padding:10px 18px;text-align:left;font-size:10px;font-family:var(--mono);letter-spacing:.12em;text-transform:uppercase;color:var(--text3);background:var(--surface2);border-bottom:1px solid var(--border);font-weight:500;white-space:nowrap;position:sticky;top:0;z-index:2;}
thead th:last-child{text-align:right;}
thead th.sortable{cursor:pointer;user-select:none;}
thead th.sortable:hover{color:var(--a);}
thead th.sortable.active-sort{color:var(--a);}
.sort-icon{display:inline-flex;vertical-align:middle;margin-left:4px;opacity:.5;transition:transform .2s;}
.sort-icon svg{width:10px;height:10px;}
.sortable.active-sort.desc .sort-icon{transform:rotate(180deg);opacity:1;}
.sortable.active-sort:not(.desc) .sort-icon{opacity:1;}
.th-check,.td-check{width:38px;padding-left:18px;padding-right:0;}
.chk{width:15px;height:15px;accent-color:var(--a);cursor:pointer;}
tbody tr{border-bottom:1px solid var(--border);transition:background var(--ease);}
tbody tr:last-child{border-bottom:none;}
tbody tr:hover{background:var(--surface2);}
tbody tr.row-selected{background:var(--a-lt);}
td{padding:13px 18px;font-size:13px;vertical-align:middle;}
.serial{font-size:11.5px;color:var(--text3);font-family:var(--mono);}
.cat-cell{display:flex;align-items:center;gap:11px;}
.cat-icon-box{width:36px;height:36px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:15px;color:#fff;flex-shrink:0;transition:transform .2s;}
tbody tr:hover .cat-icon-box{transform:scale(1.08) rotate(-3deg);}
.cat-name-text{font-weight:600;color:var(--text);font-size:13.5px;}
.cat-name-sub{font-size:11px;color:var(--text3);margin-top:1px;}
.slug-pill{display:inline-flex;align-items:center;gap:5px;background:var(--surface2);border:1px solid var(--border2);color:var(--text3);padding:3px 10px;border-radius:100px;font-size:11px;font-family:var(--mono);}
.slug-pill svg{width:10px;height:10px;}
.status-pill{display:inline-flex;align-items:center;gap:5px;padding:4px 10px;border-radius:100px;font-size:10.5px;font-weight:600;font-family:var(--mono);text-transform:uppercase;letter-spacing:.05em;}
.s-active{background:rgba(5,196,138,.12);color:var(--green);border:1px solid rgba(5,196,138,.22);}
.s-inactive{background:rgba(100,116,139,.08);color:var(--text3);border:1px solid var(--border2);}
.status-dot{width:5px;height:5px;border-radius:50%;background:currentColor;display:inline-block;}
.campaign-count{display:inline-flex;align-items:center;gap:5px;font-size:12px;color:var(--text2);font-family:var(--mono);}
.campaign-count svg{width:12px;height:12px;color:var(--text3);}
.campaign-count.zero{color:var(--text3);}
.actions{display:flex;align-items:center;justify-content:flex-end;gap:5px;}
.act-btn{display:inline-flex;align-items:center;gap:4px;padding:5px 11px;border-radius:7px;font-size:11.5px;font-weight:500;text-decoration:none;border:1px solid transparent;transition:all .15s;cursor:pointer;font-family:var(--font);white-space:nowrap;}
.act-btn svg{width:11px;height:11px;}
.act-edit{background:var(--blue-lt);color:var(--blue);border-color:rgba(59,130,246,.18);}
.act-edit:hover{background:var(--blue);color:#fff;transform:translateY(-1px);}
.act-del{background:var(--red-lt);color:var(--red);border-color:rgba(240,68,68,.18);}
.act-del:hover{background:var(--red);color:#fff;transform:translateY(-1px);}

/* ── STATUS TOGGLE ── */
.cat-toggle{display:inline-flex;align-items:center;gap:9px;cursor:pointer;user-select:none;}
.cat-toggle .sw{position:relative;flex-shrink:0;}
.cat-toggle .sw input{position:absolute;opacity:0;width:0;height:0;}
.cat-toggle .sw label{display:block;width:42px;height:23px;border-radius:100px;background:var(--border2);cursor:pointer;position:relative;transition:background .25s;margin:0;}
.cat-toggle .sw label::after{content:'';position:absolute;width:17px;height:17px;border-radius:50%;background:#fff;top:3px;left:3px;transition:transform .28s cubic-bezier(.4,0,.2,1);box-shadow:0 1px 3px rgba(0,0,0,.22);}
.cat-toggle .sw input:checked+label{background:var(--green);}
.cat-toggle .sw input:checked+label::after{transform:translateX(19px);}
.cat-toggle-txt{font-size:11px;font-weight:700;font-family:var(--mono);text-transform:uppercase;letter-spacing:.05em;line-height:1;}
.cat-toggle-txt.active{color:var(--green);}
.cat-toggle-txt.inactive{color:var(--text3);}

.cat-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:14px;padding:20px;}
.cat-grid-item{background:var(--surface2);border:1px solid var(--border);border-radius:var(--r);padding:20px 16px;display:flex;flex-direction:column;align-items:center;text-align:center;transition:all .2s;cursor:default;position:relative;overflow:hidden;animation:fadeUp .4s ease both;}
.cat-grid-item::before{content:'';position:absolute;top:0;left:0;right:0;height:3px;background:var(--item-color,var(--a));opacity:0;transition:opacity .2s;}
.cat-grid-item:hover{box-shadow:var(--sh-lg);transform:translateY(-3px);border-color:var(--border2);}
.cat-grid-item:hover::before{opacity:1;}
.cat-grid-item.row-selected{background:var(--a-lt);border-color:var(--a);}
.grid-check{position:absolute;top:10px;left:10px;}
.grid-icon-box{width:52px;height:52px;border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:20px;color:#fff;margin-bottom:12px;transition:transform .25s cubic-bezier(.4,0,.2,1);box-shadow:0 6px 18px rgba(0,0,0,.12);}
.cat-grid-item:hover .grid-icon-box{transform:scale(1.1) rotate(-5deg);}
.grid-cat-name{font-size:13.5px;font-weight:700;color:var(--text);margin-bottom:4px;}
.grid-cat-slug{font-size:10.5px;color:var(--text3);font-family:var(--mono);margin-bottom:8px;}
.grid-count-badge{font-size:10.5px;font-weight:600;font-family:var(--mono);padding:2px 9px;border-radius:100px;background:var(--surface);border:1px solid var(--border2);color:var(--text3);margin-bottom:12px;}
.grid-actions{display:flex;gap:6px;}

.empty-state{padding:72px 24px;text-align:center;}
.empty-icon-wrap{width:72px;height:72px;border-radius:20px;background:var(--surface2);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;font-size:26px;margin:0 auto 18px;animation:float 3s ease-in-out infinite;}
.empty-state h3{font-size:16px;font-weight:700;color:var(--text);margin-bottom:6px;}
.empty-state p{font-size:13px;color:var(--text3);margin-bottom:20px;}
.empty-state .reset-btn{display:inline-flex;align-items:center;gap:6px;height:36px;padding:0 16px;background:var(--surface2);border:1px solid var(--border2);border-radius:var(--r-sm);font-size:12.5px;font-weight:600;color:var(--text2);cursor:pointer;font-family:var(--font);transition:all .15s;}
.empty-state .reset-btn:hover{border-color:var(--a);color:var(--a);}

.pagination-wrap{display:flex;align-items:center;justify-content:space-between;padding:14px 20px;border-top:1px solid var(--border);background:var(--surface2);flex-wrap:wrap;gap:10px;}
.page-info{font-size:12px;color:var(--text3);font-family:var(--mono);}
.page-btns{display:flex;gap:4px;}
.page-btn{width:30px;height:30px;border-radius:7px;display:flex;align-items:center;justify-content:center;font-size:11.5px;font-weight:600;border:1px solid var(--border2);background:var(--surface);color:var(--text2);cursor:pointer;transition:all .15s;font-family:var(--mono);text-decoration:none;}
.page-btn:hover{border-color:var(--a);color:var(--a);background:var(--a-lt);}
.page-btn.cur{background:var(--a);border-color:var(--a);color:#fff;}
.page-btn svg{width:12px;height:12px;}

.modal-ico{width:48px;height:48px;border-radius:14px;background:var(--red-lt);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;}
.modal-ico svg{width:22px;height:22px;color:var(--red);}
.modal h3{font-size:16px;font-weight:700;color:var(--text);text-align:center;margin-bottom:8px;font-family:var(--font);}
.modal p{font-size:13px;color:var(--text3);text-align:center;line-height:1.6;margin-bottom:22px;}
.modal-del{flex:1;height:40px;border-radius:var(--r-sm);border:none;background:linear-gradient(135deg,var(--red),#dc2626);font-size:13px;font-weight:600;color:#fff;cursor:pointer;font-family:var(--font);transition:opacity var(--ease);box-shadow:0 4px 16px rgba(240,68,68,.3);}
.modal-del:hover{opacity:.88;}

.bulk-bar{position:sticky;bottom:16px;left:0;right:0;margin:16px auto 0;max-width:560px;background:var(--text);color:#fff;border-radius:100px;box-shadow:0 12px 32px rgba(0,0,0,.25);padding:8px 8px 8px 20px;display:flex;align-items:center;justify-content:space-between;gap:14px;transform:translateY(120%);opacity:0;transition:transform .25s cubic-bezier(.4,0,.2,1),opacity .25s ease;z-index:20;}
.bulk-bar.show{transform:translateY(0);opacity:1;}
.bulk-count{font-size:12.5px;font-weight:600;font-family:var(--mono);white-space:nowrap;}
.bulk-acts{display:flex;align-items:center;gap:6px;}
.bulk-btn{display:inline-flex;align-items:center;gap:5px;height:32px;padding:0 13px;border-radius:100px;font-size:11.5px;font-weight:600;border:none;cursor:pointer;font-family:var(--font);transition:opacity .15s;white-space:nowrap;}
.bulk-btn svg{width:11px;height:11px;}
.bulk-btn-del{background:var(--red);color:#fff;}
.bulk-btn-cancel{background:rgba(255,255,255,.12);color:#fff;}
.bulk-btn:hover{opacity:.85;}

@keyframes float{0%,100%{transform:translateY(0)}50%{transform:translateY(-6px)}}

@media(max-width:600px){.stats-grid{grid-template-columns:1fr 1fr}.bulk-bar{margin-left:12px;margin-right:12px;}}
</style>
@endpush

@section('content')
<div class="overlay" id="deleteOverlay" role="dialog" aria-modal="true">
  <div class="modal">
    <button type="button" class="modal-x" onclick="closeModal()" aria-label="Close"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
    <div class="modal-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg></div>
    <h3 id="modalTitle">Delete Category?</h3>
    <p id="modalMsg">This will permanently remove <strong id="modalCatName"></strong>. Campaigns using this category may be affected.</p>
    <div class="modal-acts">
      <button class="modal-cancel" onclick="closeModal()">Cancel</button>
      <button class="modal-del" onclick="confirmDelete()">Yes, Delete</button>
    </div>
  </div>
</div>
<form id="deleteForm" method="POST" style="display:none;">@csrf @method('DELETE')</form>

@php
  $total=$categories->count();
  $active=$categories->where('is_active',1)->count();
  $inactive=$total-$active;
  $withCampaigns=$categories->filter(fn($c)=>($c->campaigns_count??0)>0)->count();
  $withoutCampaigns=$total-$withCampaigns;
@endphp

<div class="stats-grid">
  <div class="stat stat-on" id="statAll" onclick="setFilter('all',document.getElementById('fAll'))">
    <div class="stat-icon si-a"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a2 2 0 012-2z"/></svg></div>
    <div class="stat-body"><div class="stat-lbl">Total Categories</div><div class="stat-val sv-a">{{ $total }}</div><div class="stat-foot">All on platform</div></div>
  </div>
  <div class="stat" id="statActive" onclick="setFilter('active',document.getElementById('fActive'))">
    <div class="stat-icon si-green"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
    <div class="stat-body"><div class="stat-lbl">Active</div><div class="stat-val sv-green">{{ $active }}</div><div class="stat-foot">Visible to donors</div></div>
  </div>
  <div class="stat" id="statInactive" onclick="setFilter('inactive',document.getElementById('fInactive'))">
    <div class="stat-icon si-slate"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg></div>
    <div class="stat-body"><div class="stat-lbl">Inactive</div><div class="stat-val sv-slate">{{ $inactive }}</div><div class="stat-foot">Hidden from public</div></div>
  </div>
</div>

<div class="toolbar">
  <div class="toolbar-left">
    <div class="search-wrap">
      <svg class="si" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
      <input type="text" class="search-input" id="searchInput" placeholder="Search categories…" oninput="filterTable()" aria-label="Search categories">
      <span class="search-kbd" id="searchKbd">/</span>
      <button type="button" class="search-clear" id="searchClearBtn" onclick="clearSearch()" aria-label="Clear search">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
      </button>
    </div>

    <div class="filter-group">
      <button class="filter-btn on" id="fAll" onclick="setFilter('all',this)">All <span class="cnt-badge">{{ $total }}</span></button>
      <button class="filter-btn" id="fActive" onclick="setFilter('active',this)">
        <span style="width:6px;height:6px;border-radius:50%;background:var(--green);display:inline-block;"></span>Active <span class="cnt-badge">{{ $active }}</span>
      </button>
      <button class="filter-btn" id="fInactive" onclick="setFilter('inactive',this)">
        <span style="width:6px;height:6px;border-radius:50%;background:var(--text3);display:inline-block;"></span>Inactive <span class="cnt-badge">{{ $inactive }}</span>
      </button>
    </div>

    <div class="filter-group">
      <button class="filter-btn on" id="cAll" onclick="setCampaignFilter('all',this)">Any Campaigns</button>
      <button class="filter-btn" id="cWith" onclick="setCampaignFilter('with',this)">With <span class="cnt-badge">{{ $withCampaigns }}</span></button>
      <button class="filter-btn" id="cWithout" onclick="setCampaignFilter('without',this)">Without <span class="cnt-badge">{{ $withoutCampaigns }}</span></button>
    </div>

    <div class="filter-group">
      <select class="sort-select" id="sortSelect" onchange="setSort(this.value)" aria-label="Sort categories">
        <option value="default">Sort: Default</option>
        <option value="name-asc">Name (A–Z)</option>
        <option value="name-desc">Name (Z–A)</option>
        <option value="campaigns-desc">Most Campaigns</option>
        <option value="campaigns-asc">Fewest Campaigns</option>
        <option value="status">Status</option>
      </select>
    </div>
  </div>
  <div style="display:flex;align-items:center;gap:8px;">
    <div class="view-toggle">
      <button class="view-btn on" id="viewTable" onclick="setView('table',this)" title="Table view" aria-label="Table view">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18"/></svg>
      </button>
      <button class="view-btn" id="viewGrid" onclick="setView('grid',this)" title="Grid view" aria-label="Grid view">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
      </button>
    </div>
  </div>
</div>

<div class="active-filters hide" id="activeFilters">
  <span class="af-label">Filters:</span>
  <span class="filter-chip" id="chipSearch" style="display:none;">Search: "<span id="chipSearchText"></span>"<button class="chip-x" onclick="clearSearch()"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button></span>
  <span class="filter-chip" id="chipStatus" style="display:none;"><span id="chipStatusText"></span><button class="chip-x" onclick="setFilter('all',document.getElementById('fAll'))"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button></span>
  <span class="filter-chip" id="chipCampaigns" style="display:none;"><span id="chipCampaignsText"></span><button class="chip-x" onclick="setCampaignFilter('all',document.getElementById('cAll'))"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button></span>
  <button class="clear-all-btn" onclick="clearAllFilters()">Clear all</button>
</div>

<div class="main-card">
  <div class="card-head">
    <div class="card-head-left">
      <div class="card-head-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a2 2 0 012-2z"/></svg></div>
      <span class="card-head-title">All Categories</span>
    </div>
    <span class="card-head-count" id="visibleCount">{{ $total }} total</span>
  </div>

  {{-- TABLE VIEW --}}
  <div id="tableView">
    @if($categories->isEmpty())
    <div class="empty-state">
      <div class="empty-icon-wrap">📂</div>
      <h3>No categories yet</h3>
      <p>Create your first category to get started</p>
      <a href="{{ route('admin.categories.create') }}" class="add-btn" style="margin:0 auto;">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>Add Category
      </a>
    </div>
    @else
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th class="th-check"><input type="checkbox" class="chk" id="selectAll" onchange="toggleSelectAll(this)" aria-label="Select all"></th>
            <th style="width:50px;">#</th>
            <th class="sortable" data-sort="name" onclick="sortTable('name')">Name <span class="sort-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 15l5 5 5-5M7 9l5-5 5 5"/></svg></span></th>
            <th>Slug</th>
            <th class="sortable" data-sort="status" onclick="sortTable('status')">Status <span class="sort-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 15l5 5 5-5M7 9l5-5 5 5"/></svg></span></th>
            <th class="sortable" data-sort="campaigns" onclick="sortTable('campaigns')">Campaigns <span class="sort-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 15l5 5 5-5M7 9l5-5 5 5"/></svg></span></th>
            <th style="text-align:right;">Actions</th>
          </tr>
        </thead>
        <tbody id="tableBody">
          @forelse($categories as $category)
           <tr class="cat-row" data-id="{{ $category->id }}" data-name="{{ strtolower($category->name) }}" data-status="{{ $category->is_active?'active':'inactive' }}" data-campaigns="{{ $category->campaigns_count??0 }}" data-delete-url="{{ route('admin.categories.destroy',$category->id) }}" data-toggle-url="{{ route('admin.categories.toggle',$category->id) }}" style="animation:fadeUp 0.35s {{ $loop->index*0.04 }}s ease both;opacity:0;animation-fill-mode:both;">
            <td class="td-check"><input type="checkbox" class="chk row-check" onchange="toggleRowSelect(this)" aria-label="Select {{ $category->name }}"></td>
            <td><span class="serial">{{ str_pad($loop->iteration,2,'0',STR_PAD_LEFT) }}</span></td>
            <td>
              <div class="cat-cell">
                <div class="cat-icon-box" style="background:{{ $category->color??'#2563eb ' }};"><i class="fa {{ $category->icon??'fa-tag' }}"></i></div>
                <div>
                  <div class="cat-name-text">{{ $category->name }}</div>
                  <div class="cat-name-sub">Added {{ $category->created_at->format('M d, Y') }}</div>
                </div>
              </div>
            </td>
            <td><span class="slug-pill"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101"/><path stroke-linecap="round" stroke-linejoin="round" d="M14.828 14.828a4 4 0 015.656 0l4-4a4 4 0 01-5.656-5.656l-1.1 1.1"/></svg>{{ $category->slug }}</span></td>
            <td>
              <label class="cat-toggle" title="Toggle active status">
                <span class="sw">
                  <input type="checkbox" {{ $category->is_active?'checked':'' }} onchange="toggleStatus('{{ $category->id }}',this.checked)" aria-label="Toggle status for {{ $category->name }}">
                </span>
                <span class="cat-toggle-txt {{ $category->is_active?'active':'inactive' }}" id="statusTxt-{{ $category->id }}">{{ $category->is_active?'Active':'Inactive' }}</span>
              </label>
            </td>
            <td><span class="campaign-count {{ ($category->campaigns_count??0)==0?'zero':'' }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>{{ $category->campaigns_count??0 }}</span></td>
            <td>
              <div class="actions">
                <a href="{{ route('admin.categories.edit',$category->id) }}" class="act-btn act-edit"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>Edit</a>
                <button type="button" class="act-btn act-del" onclick="openModal('{{ $category->id }}','{{ addslashes($category->name) }}','{{ route('admin.categories.destroy',$category->id) }}')"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/></svg>Delete</button>
              </div>
            </td>
          </tr>
          @empty
          <tr><td colspan="7">
            <div class="empty-state"><div class="empty-icon-wrap">📂</div><h3>No categories yet</h3><p>Create your first category to get started</p></div>
          </td></tr>
          @endforelse
        </tbody>
      </table>
      <div class="empty-state" id="noResultsState" style="display:none;">
        <div class="empty-icon-wrap">🔍</div>
        <h3>No matching categories</h3>
        <p>Try adjusting your search or filters</p>
        <button type="button" class="reset-btn" onclick="clearAllFilters()">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:13px;height:13px;"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h5M20 20v-5h-5M4 9a9 9 0 0114.13-5.36M20 15a9 9 0 01-14.13 5.36"/></svg>
          Clear filters
        </button>
      </div>
    </div>

    @if($categories instanceof \Illuminate\Pagination\LengthAwarePaginator && $categories->hasPages())
    <div class="pagination-wrap">
      <span class="page-info">Showing {{ $categories->firstItem() }}–{{ $categories->lastItem() }} of {{ $categories->total() }}</span>
      <div class="page-btns">
        @if($categories->onFirstPage())
          <span class="page-btn" style="opacity:.4;cursor:not-allowed;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg></span>
        @else
          <a href="{{ $categories->previousPageUrl() }}" class="page-btn"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg></a>
        @endif
        @foreach($categories->getUrlRange(1,$categories->lastPage()) as $page=>$url)
          <a href="{{ $url }}" class="page-btn {{ $categories->currentPage()==$page?'cur':'' }}">{{ $page }}</a>
        @endforeach
        @if($categories->hasMorePages())
          <a href="{{ $categories->nextPageUrl() }}" class="page-btn"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg></a>
        @else
          <span class="page-btn" style="opacity:.4;cursor:not-allowed;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg></span>
        @endif
      </div>
    </div>
    @endif
    @endif
  </div>

  {{-- GRID VIEW --}}
  <div id="gridView" style="display:none;">
    @if(!$categories->isEmpty())
    <div class="cat-grid" id="gridBody">
      @foreach($categories as $category)
      <div class="cat-grid-item" data-id="{{ $category->id }}" style="--item-color:{{ $category->color??'#2563eb ' }};animation-delay:{{ $loop->index*0.04 }}s;" data-name="{{ strtolower($category->name) }}" data-status="{{ $category->is_active?'active':'inactive' }}" data-campaigns="{{ $category->campaigns_count??0 }}" data-delete-url="{{ route('admin.categories.destroy',$category->id) }}" data-toggle-url="{{ route('admin.categories.toggle',$category->id) }}">
        <input type="checkbox" class="chk row-check grid-check" onchange="toggleRowSelect(this)" aria-label="Select {{ $category->name }}">
        <div class="grid-icon-box" style="background:{{ $category->color??'#2563eb ' }};"><i class="fa {{ $category->icon??'fa-tag' }}" style="color:#fff;"></i></div>
        <div class="grid-cat-name">{{ $category->name }}</div>
        <div class="grid-cat-slug">{{ $category->slug }}</div>
        <label class="cat-toggle" style="margin-bottom:12px;" title="Toggle active status">
          <span class="sw">
            <input type="checkbox" {{ $category->is_active?'checked':'' }} onchange="toggleStatus('{{ $category->id }}',this.checked)" aria-label="Toggle status for {{ $category->name }}">
          </span>
          <span class="cat-toggle-txt {{ $category->is_active?'active':'inactive' }}" id="statusTxt-{{ $category->id }}">{{ $category->is_active?'Active':'Inactive' }}</span>
        </label>
        <div class="grid-count-badge">{{ $category->campaigns_count??0 }} campaigns</div>
        <div class="grid-actions">
          <a href="{{ route('admin.categories.edit',$category->id) }}" class="act-btn act-edit" style="font-size:11px;padding:4px 10px;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>Edit</a>
          <button type="button" class="act-btn act-del" style="font-size:11px;padding:4px 10px;" onclick="openModal('{{ $category->id }}','{{ addslashes($category->name) }}','{{ route('admin.categories.destroy',$category->id) }}')"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/></svg>Delete</button>
        </div>
      </div>
      @endforeach
    </div>
    <div class="empty-state" id="noResultsStateGrid" style="display:none;">
      <div class="empty-icon-wrap">🔍</div>
      <h3>No matching categories</h3>
      <p>Try adjusting your search or filters</p>
      <button type="button" class="reset-btn" onclick="clearAllFilters()">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:13px;height:13px;"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h5M20 20v-5h-5M4 9a9 9 0 0114.13-5.36M20 15a9 9 0 01-14.13 5.36"/></svg>
        Clear filters
      </button>
    </div>
    @else
    <div class="empty-state"><div class="empty-icon-wrap">📂</div><h3>No categories yet</h3><p>Create your first category to get started</p><a href="{{ route('admin.categories.create') }}" class="add-btn" style="margin:0 auto;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>Add Category</a></div>
    @endif
  </div>

  <div class="bulk-bar" id="bulkBar">
    <span class="bulk-count" id="bulkCount">0 selected</span>
    <div class="bulk-acts">
      <button type="button" class="bulk-btn bulk-btn-del" onclick="openBulkModal()">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/></svg>Delete
      </button>
      <button type="button" class="bulk-btn bulk-btn-cancel" onclick="clearSelection()">Cancel</button>
    </div>
  </div>
</div>
@endsection

@push('page_scripts')
<script>
(function(){
'use strict';

// ---------- Toast ----------
function toast(msg,type){
  var t=document.createElement('div');
  t.style.cssText='position:fixed;top:20px;right:20px;z-index:9999;display:flex;align-items:center;gap:10px;padding:13px 16px;border-radius:14px;font-size:13px;font-weight:500;color:#fff;min-width:240px;box-shadow:0 10px 30px rgba(0,0,0,.25);animation:fadeUp .3s ease both;'+(type==='error'?'background:linear-gradient(135deg,#dc2626,#f04444);':'background:linear-gradient(135deg,#059669,#10b981);');
  t.innerHTML=(type==='error'?'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:15px;height:15px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>':'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:15px;height:15px;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>')+'<span>'+msg+'</span><button style="margin-left:auto;background:transparent;border:none;color:inherit;opacity:.7;cursor:pointer;font-size:14px;" onclick="this.parentElement.remove()">✕</button>';
  document.body.appendChild(t);
  setTimeout(function(){t.style.transition='opacity .3s,transform .3s';t.style.opacity='0';t.style.transform='translateX(20px)';setTimeout(function(){t.remove();},300);},3800);
}

// ---------- Inline status toggle ----------
function updateStatusStat(toActive){
  var a=document.getElementById('statActive'),i=document.getElementById('statInactive');
  if(a&&i){
    var av=parseInt(a.querySelector('.stat-val').textContent||'0',10);
    var iv=parseInt(i.querySelector('.stat-val').textContent||'0',10);
    a.querySelector('.stat-val').textContent=toActive?av+1:av-1;
    i.querySelector('.stat-val').textContent=toActive?iv-1:iv+1;
  }
}

window.toggleStatus=function(id,toActive){
  var row=document.querySelector('[data-id="'+id+'"]');
  var url=row?row.getAttribute('data-toggle-url'):null;
  var txt=document.getElementById('statusTxt-'+id);
  if(!url)return;
  // optimistic UI
  if(row)row.setAttribute('data-status',toActive?'active':'inactive');
  if(txt){txt.textContent=toActive?'Active':'Inactive';txt.className='cat-toggle-txt '+(toActive?'active':'inactive');}
  updateStatusStat(toActive);
  var token=document.querySelector('#deleteForm input[name="_token"]').value;
  var fd=new FormData();fd.append('_token',token);
  fetch(url,{method:'POST',body:fd,headers:{'X-Requested-With':'XMLHttpRequest'}})
    .then(function(r){if(!r.ok)throw new Error('Failed');toast('Status updated','ok');})
    .catch(function(){ // rollback
      if(row)row.setAttribute('data-status',toActive?'inactive':'active');
      if(txt){txt.textContent=toActive?'Inactive':'Active';txt.className='cat-toggle-txt '+(toActive?'inactive':'active');}
      updateStatusStat(!toActive);
      toast('Could not update status','error');
    });
};

// ---------- View toggle ----------
var currentView=localStorage.getItem('catView')||'table';
function applyView(v){
  document.getElementById('tableView').style.display=v==='table'?'':'none';
  document.getElementById('gridView').style.display=v==='grid'?'':'none';
  document.querySelectorAll('.view-btn').forEach(function(b){b.classList.remove('on');});
  document.getElementById(v==='table'?'viewTable':'viewGrid').classList.add('on');
}
window.setView=function(v){currentView=v;localStorage.setItem('catView',v);applyView(v);filterTable();};
applyView(currentView);

// ---------- Filters ----------
var activeFilter='all';
var campaignFilter='all';
var currentSort='default';
var sortDir={};

window.setFilter=function(f,btn){
  activeFilter=f;
  document.querySelectorAll('#fAll,#fActive,#fInactive').forEach(function(b){b.classList.remove('on');});
  if(btn)btn.classList.add('on');
  document.querySelectorAll('.stat').forEach(function(s){s.classList.remove('stat-on');});
  var map={all:'statAll',active:'statActive',inactive:'statInactive'};
  document.getElementById(map[f]).classList.add('stat-on');
  updateChips();
  filterTable();
};

window.setCampaignFilter=function(f,btn){
  campaignFilter=f;
  document.querySelectorAll('#cAll,#cWith,#cWithout').forEach(function(b){b.classList.remove('on');});
  if(btn)btn.classList.add('on');
  updateChips();
  filterTable();
};

window.clearSearch=function(){
  var input=document.getElementById('searchInput');
  input.value='';
  input.focus();
  filterTable();
};

window.clearAllFilters=function(){
  document.getElementById('searchInput').value='';
  setFilter('all',document.getElementById('fAll'));
  setCampaignFilter('all',document.getElementById('cAll'));
};

function updateChips(){
  var q=document.getElementById('searchInput').value.trim();
  var wrap=document.getElementById('activeFilters');
  var any=false;

  var chipSearch=document.getElementById('chipSearch');
  if(q){chipSearch.style.display='inline-flex';document.getElementById('chipSearchText').textContent=q;any=true;}
  else{chipSearch.style.display='none';}

  var chipStatus=document.getElementById('chipStatus');
  if(activeFilter!=='all'){chipStatus.style.display='inline-flex';document.getElementById('chipStatusText').textContent=activeFilter==='active'?'Active only':'Inactive only';any=true;}
  else{chipStatus.style.display='none';}

  var chipCampaigns=document.getElementById('chipCampaigns');
  if(campaignFilter!=='all'){chipCampaigns.style.display='inline-flex';document.getElementById('chipCampaignsText').textContent=campaignFilter==='with'?'With campaigns':'Without campaigns';any=true;}
  else{chipCampaigns.style.display='none';}

  wrap.classList.toggle('hide',!any);
}

window.filterTable=function(){
  var q=document.getElementById('searchInput').value.toLowerCase().trim();
  document.getElementById('searchClearBtn').classList.toggle('show',q.length>0);
  document.getElementById('searchKbd').classList.toggle('hide',q.length>0);
  updateChips();

  var tableRows=document.querySelectorAll('.cat-row');
  var gridItems=document.querySelectorAll('#gridBody .cat-grid-item');
  var visible=0;

  function matches(el){
    var nameOk=!q||(el.getAttribute('data-name')||'').includes(q);
    var statusOk=activeFilter==='all'||el.getAttribute('data-status')===activeFilter;
    var count=parseInt(el.getAttribute('data-campaigns')||'0',10);
    var campOk=campaignFilter==='all'||(campaignFilter==='with'?count>0:count===0);
    return nameOk&&statusOk&&campOk;
  }

  tableRows.forEach(function(r){var s=matches(r);r.style.display=s?'':'none';if(s)visible++;});
  gridItems.forEach(function(r){r.style.display=matches(r)?'':'none';});

  document.getElementById('visibleCount').textContent=visible+' total';

  var noRes=document.getElementById('noResultsState');
  if(noRes){noRes.style.display=(visible===0&&tableRows.length>0)?'':'none';}
  var noResGrid=document.getElementById('noResultsStateGrid');
  if(noResGrid){noResGrid.style.display=(visible===0&&gridItems.length>0)?'':'none';}

  updateSelectAllState();
};

// ---------- Sorting ----------
window.setSort=function(v){
  currentSort='default';
  document.querySelectorAll('.sortable').forEach(function(th){th.classList.remove('active-sort','desc');});
  if(v==='default')return;
  var map={'name-asc':['name',1],'name-desc':['name',-1],'campaigns-desc':['campaigns',-1],'campaigns-asc':['campaigns',1],'status':['status',1]};
  var conf=map[v];
  if(conf)applySort(conf[0],conf[1]);
};

window.sortTable=function(col){
  var dir=sortDir[col]===1?-1:1;
  sortDir={};sortDir[col]=dir;
  document.getElementById('sortSelect').value='default';
  applySort(col,dir);
};

function applySort(col,dir){
  document.querySelectorAll('.sortable').forEach(function(th){
    th.classList.toggle('active-sort',th.getAttribute('data-sort')===col);
    th.classList.toggle('desc',th.getAttribute('data-sort')===col&&dir===-1);
  });
  var tb=document.getElementById('tableBody');
  var rows=Array.from(tb.querySelectorAll('tr.cat-row'));
  rows.sort(function(a,b){
    var va,vb;
    if(col==='campaigns'){va=parseInt(a.getAttribute('data-campaigns')||'0',10);vb=parseInt(b.getAttribute('data-campaigns')||'0',10);return dir*(va-vb);}
    if(col==='status'){va=a.getAttribute('data-status');vb=b.getAttribute('data-status');return dir*va.localeCompare(vb);}
    va=a.getAttribute('data-name')||'';vb=b.getAttribute('data-name')||'';
    return dir*va.localeCompare(vb);
  });
  rows.forEach(function(r){tb.appendChild(r);});

  var gb=document.getElementById('gridBody');
  if(gb){
    var gitems=Array.from(gb.querySelectorAll('.cat-grid-item'));
    gitems.sort(function(a,b){
      var va,vb;
      if(col==='campaigns'){va=parseInt(a.getAttribute('data-campaigns')||'0',10);vb=parseInt(b.getAttribute('data-campaigns')||'0',10);return dir*(va-vb);}
      if(col==='status'){va=a.getAttribute('data-status');vb=b.getAttribute('data-status');return dir*va.localeCompare(vb);}
      va=a.getAttribute('data-name')||'';vb=b.getAttribute('data-name')||'';
      return dir*va.localeCompare(vb);
    });
    gitems.forEach(function(r){gb.appendChild(r);});
  }
}

// ---------- Keyboard shortcuts ----------
document.addEventListener('keydown',function(e){
  var tag=(e.target.tagName||'').toLowerCase();
  if(e.key==='/'&&tag!=='input'&&tag!=='textarea'){
    e.preventDefault();
    document.getElementById('searchInput').focus();
  }
  if(e.key==='Escape'&&tag==='input'&&e.target.id==='searchInput'){
    clearSearch();
  }
});

// ---------- Bulk selection ----------
var selected=new Set();

window.toggleRowSelect=function(cb){
  var row=cb.closest('[data-id]');
  var id=row.getAttribute('data-id');
  if(cb.checked){selected.add(id);row.classList.add('row-selected');}
  else{selected.delete(id);row.classList.remove('row-selected');}
  syncCheckboxesForId(id,cb.checked);
  updateBulkBar();
  updateSelectAllState();
};

function syncCheckboxesForId(id,checked){
  document.querySelectorAll('[data-id="'+id+'"] .row-check').forEach(function(cb){cb.checked=checked;});
}

window.toggleSelectAll=function(cb){
  var visibleRows=Array.from(document.querySelectorAll('.cat-row')).filter(function(r){return r.style.display!=='none';});
  visibleRows.forEach(function(r){
    var id=r.getAttribute('data-id');
    var rowCb=r.querySelector('.row-check');
    if(cb.checked){selected.add(id);r.classList.add('row-selected');if(rowCb)rowCb.checked=true;}
    else{selected.delete(id);r.classList.remove('row-selected');if(rowCb)rowCb.checked=false;}
    syncCheckboxesForId(id,cb.checked);
  });
  updateBulkBar();
};

function updateSelectAllState(){
  var selAll=document.getElementById('selectAll');
  if(!selAll)return;
  var visibleRows=Array.from(document.querySelectorAll('.cat-row')).filter(function(r){return r.style.display!=='none';});
  var allChecked=visibleRows.length>0&&visibleRows.every(function(r){return selected.has(r.getAttribute('data-id'));});
  selAll.checked=allChecked;
}

function updateBulkBar(){
  var bar=document.getElementById('bulkBar');
  var count=selected.size;
  document.getElementById('bulkCount').textContent=count+' selected';
  bar.classList.toggle('show',count>0);
}

window.clearSelection=function(){
  selected.clear();
  document.querySelectorAll('.row-check').forEach(function(cb){cb.checked=false;});
  document.querySelectorAll('.row-selected').forEach(function(r){r.classList.remove('row-selected');});
  var selAll=document.getElementById('selectAll');
  if(selAll)selAll.checked=false;
  updateBulkBar();
};

// ---------- Delete modal (single + bulk) ----------
var pendingUrl=null;
var bulkMode=false;

window.openModal=function(id,name,url){
  bulkMode=false;
  pendingUrl=url;
  document.getElementById('modalTitle').textContent='Delete Category?';
  document.getElementById('modalMsg').innerHTML='This will permanently remove <strong id="modalCatName">"'+name+'"</strong>. Campaigns using this category may be affected.';
  document.getElementById('deleteOverlay').classList.add('open');
};

window.openBulkModal=function(){
  bulkMode=true;
  var ids=Array.from(selected);
  var withCamp=0;
  ids.forEach(function(id){
    var row=document.querySelector('[data-id="'+id+'"]');
    if(row&&parseInt(row.getAttribute('data-campaigns')||'0',10)>0)withCamp++;
  });
  var deletable=ids.length-withCamp;
  document.getElementById('modalTitle').textContent='Delete '+deletable+' Categor'+(deletable===1?'y':'ies')+'?';
  var msg='This will permanently remove <strong>'+deletable+' selected categor'+(deletable===1?'y':'ies')+'</strong>.';
  if(withCamp>0)msg+=' <span style="color:var(--amber);font-weight:600;">'+withCamp+' linked to campaigns will be skipped.</span>';
  document.getElementById('modalMsg').innerHTML=msg;
  document.getElementById('deleteOverlay').classList.add('open');
};

window.closeModal=function(){
  document.getElementById('deleteOverlay').classList.remove('open');
  pendingUrl=null;
  bulkMode=false;
};

window.confirmDelete=function(){
  if(bulkMode){
    var token=document.querySelector('#deleteForm input[name="_token"]').value;
    var ids=Array.from(selected);
    var reqs=ids.filter(function(id){
      var row=document.querySelector('[data-id="'+id+'"]');
      return row && parseInt(row.getAttribute('data-campaigns')||'0',10)===0;
    }).map(function(id){
      var row=document.querySelector('[data-id="'+id+'"]');
      var url=row?row.getAttribute('data-delete-url'):null;
      if(!url)return null;
      var fd=new FormData();
      fd.append('_token',token);
      fd.append('_method','DELETE');
      return fetch(url,{method:'POST',body:fd,headers:{'X-Requested-With':'XMLHttpRequest'}});
    }).filter(Boolean);
    Promise.all(reqs).then(function(){window.location.reload();}).catch(function(){window.location.reload();});
    return;
  }
  if(!pendingUrl)return;
  var f=document.getElementById('deleteForm');
  f.action=pendingUrl;
  f.submit();
};

// init
filterTable();
})();
</script>
@endpush