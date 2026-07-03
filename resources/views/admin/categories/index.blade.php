@extends('layouts.admin')

@section('sidebar_categories', 'active')
@section('page_title', 'Categories')
@section('page_subtitle', 'Manage campaign categories')

@section('topbar_left')
  <a href="{{ route('admin.categories.create') }}" class="add-btn">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
    Add Category
  </a>
@endsection

@push('page_styles')
<style>
.add-btn{display:inline-flex;align-items:center;gap:7px;height:36px;padding:0 16px;background:var(--a);color:#fff;border:none;border-radius:var(--r-sm);font-size:12.5px;font-weight:600;text-decoration:none;cursor:pointer;transition:opacity .2s,transform .15s;font-family:var(--font);box-shadow:0 4px 14px rgba(110,86,247,.3);}
.add-btn:hover{opacity:.88;transform:translateY(-1px);}
.add-btn svg{width:13px;height:13px;}

.stats-grid{grid-template-columns:repeat(3,1fr);}
.stat:nth-child(1){animation-delay:.05s;}.stat:nth-child(1)::after{background:linear-gradient(90deg,var(--a),var(--a2));}
.stat:nth-child(2){animation-delay:.10s;}.stat:nth-child(2)::after{background:linear-gradient(90deg,var(--green),#34d399);}
.stat:nth-child(3){animation-delay:.15s;}.stat:nth-child(3)::after{background:linear-gradient(90deg,var(--text3),#64748b);}
.si-slate{background:rgba(100,116,139,.10);color:var(--text3);}
.sv-slate{color:var(--text3);}

.toolbar{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:16px;flex-wrap:wrap;}
.toolbar-left{display:flex;align-items:center;gap:8px;flex-wrap:wrap;}
.search-wrap .si{position:absolute;left:11px;top:50%;transform:translateY(-50%);width:13px;height:13px;color:var(--text3);pointer-events:none;}
.search-input{width:220px;height:36px;padding:0 12px 0 33px;background:var(--surface);border:1px solid var(--border2);border-radius:var(--r-sm);font-size:12.5px;color:var(--text);font-family:var(--font);outline:none;transition:border-color var(--ease),box-shadow var(--ease),width .3s ease;}
.search-input::placeholder{color:var(--text3);}
.search-input:focus{border-color:var(--a);box-shadow:0 0 0 3px var(--a-glow);width:260px;}
.filter-btn{display:inline-flex;align-items:center;gap:5px;height:36px;padding:0 13px;background:var(--surface);border:1px solid var(--border2);border-radius:var(--r-sm);font-size:12px;font-weight:500;color:var(--text2);cursor:pointer;font-family:var(--font);transition:all var(--ease);}
.filter-btn:hover,.filter-btn.on{border-color:var(--a);color:var(--a);background:var(--a-lt);}
.filter-btn svg{width:12px;height:12px;}
.cnt-badge{background:var(--a);color:#fff;font-size:9.5px;font-weight:700;padding:1px 5px;border-radius:100px;font-family:var(--mono);}
.view-toggle{display:flex;background:var(--surface);border:1px solid var(--border2);border-radius:var(--r-sm);overflow:hidden;}
.view-btn{width:36px;height:36px;display:flex;align-items:center;justify-content:center;cursor:pointer;color:var(--text3);border:none;background:transparent;transition:all .15s;}
.view-btn:hover{color:var(--a);}
.view-btn.on{background:var(--a);color:#fff;}
.view-btn svg{width:14px;height:14px;}

.main-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh);overflow:hidden;animation:fadeUp .4s .15s ease both;}
.card-head{padding:14px 20px;border-bottom:1px solid var(--border);background:var(--surface2);display:flex;align-items:center;justify-content:space-between;}
.card-head-left{display:flex;align-items:center;gap:10px;}
.card-head-icon{width:30px;height:30px;border-radius:8px;background:var(--a-lt);color:var(--a);display:flex;align-items:center;justify-content:center;}
.card-head-icon svg{width:14px;height:14px;}
.card-head-title{font-size:12px;font-weight:600;color:var(--text2);text-transform:uppercase;letter-spacing:.09em;font-family:var(--mono);}
.card-head-count{font-size:10.5px;color:var(--text3);font-family:var(--mono);background:var(--surface);border:1px solid var(--border2);padding:2px 8px;border-radius:100px;}

.table-wrap{overflow-x:auto;}
table{width:100%;border-collapse:collapse;}
thead th{padding:10px 18px;text-align:left;font-size:10px;font-family:var(--mono);letter-spacing:.12em;text-transform:uppercase;color:var(--text3);background:var(--surface2);border-bottom:1px solid var(--border);font-weight:500;white-space:nowrap;}
thead th:last-child{text-align:right;}
thead th.sortable{cursor:pointer;user-select:none;}
thead th.sortable:hover{color:var(--a);}
.sort-icon{display:inline-flex;vertical-align:middle;margin-left:4px;opacity:.5;}
.sort-icon svg{width:10px;height:10px;}
tbody tr{border-bottom:1px solid var(--border);transition:background var(--ease);}
tbody tr:last-child{border-bottom:none;}
tbody tr:hover{background:var(--surface2);}
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
.actions{display:flex;align-items:center;justify-content:flex-end;gap:5px;}
.act-btn{display:inline-flex;align-items:center;gap:4px;padding:5px 11px;border-radius:7px;font-size:11.5px;font-weight:500;text-decoration:none;border:1px solid transparent;transition:all .15s;cursor:pointer;font-family:var(--font);white-space:nowrap;}
.act-btn svg{width:11px;height:11px;}
.act-edit{background:var(--blue-lt);color:var(--blue);border-color:rgba(59,130,246,.18);}
.act-edit:hover{background:var(--blue);color:#fff;transform:translateY(-1px);}
.act-del{background:var(--red-lt);color:var(--red);border-color:rgba(240,68,68,.18);}
.act-del:hover{background:var(--red);color:#fff;transform:translateY(-1px);}

.cat-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:14px;padding:20px;}
.cat-grid-item{background:var(--surface2);border:1px solid var(--border);border-radius:var(--r);padding:20px 16px;display:flex;flex-direction:column;align-items:center;text-align:center;transition:all .2s;cursor:default;position:relative;overflow:hidden;animation:fadeUp .4s ease both;}
.cat-grid-item::before{content:'';position:absolute;top:0;left:0;right:0;height:3px;background:var(--item-color,var(--a));opacity:0;transition:opacity .2s;}
.cat-grid-item:hover{box-shadow:var(--sh-lg);transform:translateY(-3px);border-color:var(--border2);}
.cat-grid-item:hover::before{opacity:1;}
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

.pagination-wrap{display:flex;align-items:center;justify-content:space-between;padding:14px 20px;border-top:1px solid var(--border);background:var(--surface2);flex-wrap:wrap;gap:10px;}
.page-info{font-size:12px;color:var(--text3);font-family:var(--mono);}
.page-btns{display:flex;gap:4px;}
.page-btn{width:30px;height:30px;border-radius:7px;display:flex;align-items:center;justify-content:center;font-size:11.5px;font-weight:600;border:1px solid var(--border2);background:var(--surface);color:var(--text2);cursor:pointer;transition:all .15s;font-family:var(--mono);text-decoration:none;}
.page-btn:hover{border-color:var(--a);color:var(--a);background:var(--a-lt);}
.page-btn.cur{background:var(--a);border-color:var(--a);color:#fff;}
.page-btn svg{width:12px;height:12px;}

.modal-ico{width:48px;height:48px;border-radius:14px;background:var(--red-lt);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;}
.modal-ico svg{width:22px;height:22px;color:var(--red);}
.modal h3{font-size:16px;font-weight:700;color:var(--text);text-align:center;margin-bottom:8px;font-family:var(--mono);}
.modal p{font-size:13px;color:var(--text3);text-align:center;line-height:1.6;margin-bottom:22px;}
.modal-del{flex:1;height:40px;border-radius:var(--r-sm);border:none;background:linear-gradient(135deg,var(--red),#dc2626);font-size:13px;font-weight:600;color:#fff;cursor:pointer;font-family:var(--font);transition:opacity var(--ease);box-shadow:0 4px 16px rgba(240,68,68,.3);}
.modal-del:hover{opacity:.88;}

@keyframes float{0%,100%{transform:translateY(0)}50%{transform:translateY(-6px)}}

@media(max-width:600px){.stats-grid{grid-template-columns:1fr 1fr}}
</style>
@endpush

@section('content')
<div class="overlay" id="deleteOverlay" role="dialog" aria-modal="true">
  <div class="modal">
    <button type="button" class="modal-x" onclick="closeModal()"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
    <div class="modal-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg></div>
    <h3>Delete Category?</h3>
    <p>This will permanently remove <strong id="modalCatName"></strong>. Campaigns using this category may be affected.</p>
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
@endphp

<div class="stats-grid">
  <div class="stat">
    <div class="stat-icon si-a"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a2 2 0 012-2z"/></svg></div>
    <div class="stat-body"><div class="stat-lbl">Total Categories</div><div class="stat-val sv-a">{{ $total }}</div><div class="stat-foot">All on platform</div></div>
  </div>
  <div class="stat">
    <div class="stat-icon si-green"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
    <div class="stat-body"><div class="stat-lbl">Active</div><div class="stat-val sv-green">{{ $active }}</div><div class="stat-foot">Visible to donors</div></div>
  </div>
  <div class="stat">
    <div class="stat-icon si-slate"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg></div>
    <div class="stat-body"><div class="stat-lbl">Inactive</div><div class="stat-val sv-slate">{{ $inactive }}</div><div class="stat-foot">Hidden from public</div></div>
  </div>
</div>

<div class="toolbar">
  <div class="toolbar-left">
    <div class="search-wrap">
      <svg class="si" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
      <input type="text" class="search-input" id="searchInput" placeholder="Search categories…" oninput="filterTable()">
    </div>
    <button class="filter-btn on" id="fAll" onclick="setFilter('all',this)">All <span class="cnt-badge">{{ $total }}</span></button>
    <button class="filter-btn" id="fActive" onclick="setFilter('active',this)">
      <span style="width:6px;height:6px;border-radius:50%;background:var(--green);display:inline-block;"></span>Active
    </button>
    <button class="filter-btn" id="fInactive" onclick="setFilter('inactive',this)">Inactive</button>
  </div>
  <div style="display:flex;align-items:center;gap:8px;">
    <div class="view-toggle">
      <button class="view-btn on" id="viewTable" onclick="setView('table',this)" title="Table view">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18"/></svg>
      </button>
      <button class="view-btn" id="viewGrid" onclick="setView('grid',this)" title="Grid view">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
      </button>
    </div>
  </div>
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
            <th style="width:50px;">#</th>
            <th class="sortable" onclick="sortTable(1)">Name <span class="sort-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 15l5 5 5-5M7 9l5-5 5 5"/></svg></span></th>
            <th>Slug</th>
            <th>Status</th>
            <th>Campaigns</th>
            <th style="text-align:right;">Actions</th>
          </tr>
        </thead>
        <tbody id="tableBody">
          @forelse($categories as $category)
          <tr class="cat-row" data-name="{{ strtolower($category->name) }}" data-status="{{ $category->is_active?'active':'inactive' }}" style="animation:fadeUp 0.35s {{ $loop->index*0.04 }}s ease both;opacity:0;animation-fill-mode:both;">
            <td><span class="serial">{{ str_pad($loop->iteration,2,'0',STR_PAD_LEFT) }}</span></td>
            <td>
              <div class="cat-cell">
                <div class="cat-icon-box" style="background:{{ $category->color??'#6e56f7' }};"><i class="fa {{ $category->icon??'fa-tag' }}"></i></div>
                <div>
                  <div class="cat-name-text">{{ $category->name }}</div>
                  <div class="cat-name-sub">{{ $category->color??'#6e56f7' }}</div>
                </div>
              </div>
            </td>
            <td><span class="slug-pill"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101"/><path stroke-linecap="round" stroke-linejoin="round" d="M14.828 14.828a4 4 0 015.656 0l4-4a4 4 0 01-5.656-5.656l-1.1 1.1"/></svg>{{ $category->slug }}</span></td>
            <td>
              @if($category->is_active)<span class="status-pill s-active"><span class="status-dot"></span> Active</span>
              @else<span class="status-pill s-inactive"><span class="status-dot"></span> Inactive</span>@endif
            </td>
            <td><span class="campaign-count"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>{{ $category->campaigns_count??0 }}</span></td>
            <td>
              <div class="actions">
                <a href="{{ route('admin.categories.edit',$category->id) }}" class="act-btn act-edit"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>Edit</a>
                <button type="button" class="act-btn act-del" onclick="openModal('{{ $category->id }}','{{ addslashes($category->name) }}','{{ route('admin.categories.destroy',$category->id) }}')"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/></svg>Delete</button>
              </div>
            </td>
          </tr>
          @empty
          <tr><td colspan="6">
            <div class="empty-state"><div class="empty-icon-wrap">📂</div><h3>No categories yet</h3><p>Create your first category to get started</p></div>
          </td></tr>
          @endforelse
        </tbody>
      </table>
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
      <div class="cat-grid-item" style="--item-color:{{ $category->color??'#6e56f7' }};animation-delay:{{ $loop->index*0.04 }}s;" data-name="{{ strtolower($category->name) }}" data-status="{{ $category->is_active?'active':'inactive' }}">
        <div class="grid-icon-box" style="background:{{ $category->color??'#6e56f7' }};"><i class="fa {{ $category->icon??'fa-tag' }}" style="color:#fff;"></i></div>
        <div class="grid-cat-name">{{ $category->name }}</div>
        <div class="grid-cat-slug">{{ $category->slug }}</div>
        @if($category->is_active)<div class="status-pill s-active" style="margin-bottom:12px;"><span class="status-dot"></span> Active</div>
        @else<div class="status-pill s-inactive" style="margin-bottom:12px;"><span class="status-dot"></span> Inactive</div>@endif
        <div class="grid-count-badge">{{ $category->campaigns_count??0 }} campaigns</div>
        <div class="grid-actions">
          <a href="{{ route('admin.categories.edit',$category->id) }}" class="act-btn act-edit" style="font-size:11px;padding:4px 10px;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>Edit</a>
          <button type="button" class="act-btn act-del" style="font-size:11px;padding:4px 10px;" onclick="openModal('{{ $category->id }}','{{ addslashes($category->name) }}','{{ route('admin.categories.destroy',$category->id) }}')"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/></svg>Delete</button>
        </div>
      </div>
      @endforeach
    </div>
    @else
    <div class="empty-state"><div class="empty-icon-wrap">📂</div><h3>No categories yet</h3><p>Create your first category to get started</p><a href="{{ route('admin.categories.create') }}" class="add-btn" style="margin:0 auto;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>Add Category</a></div>
    @endif
  </div>

</div>
@endsection

@push('page_scripts')
<script>
(function(){
'use strict';

var currentView=localStorage.getItem('catView')||'table';
function applyView(v){
  document.getElementById('tableView').style.display=v==='table'?'':'none';
  document.getElementById('gridView').style.display=v==='grid'?'':'none';
  document.querySelectorAll('.view-btn').forEach(function(b){b.classList.remove('on');});
  document.getElementById(v==='table'?'viewTable':'viewGrid').classList.add('on');
}
window.setView=function(v){currentView=v;localStorage.setItem('catView',v);applyView(v);filterTable();};
applyView(currentView);

var activeFilter='all';
window.setFilter=function(f,btn){
  activeFilter=f;
  document.querySelectorAll('.filter-btn').forEach(function(b){b.classList.remove('on');});
  if(btn)btn.classList.add('on');
  filterTable();
};

window.filterTable=function(){
  var q=document.getElementById('searchInput').value.toLowerCase().trim();
  var tableRows=document.querySelectorAll('.cat-row');
  var gridItems=document.querySelectorAll('#gridBody .cat-grid-item');
  var visible=0;
  function matches(el){return(!q||(el.getAttribute('data-name')||'').includes(q))&&(activeFilter==='all'||el.getAttribute('data-status')===activeFilter);}
  tableRows.forEach(function(r){var s=matches(r);r.style.display=s?'':'none';if(s)visible++;});
  gridItems.forEach(function(r){r.style.display=matches(r)?'':'none';});
  document.getElementById('visibleCount').textContent=visible+' total';
};

var sortDir=1;
window.sortTable=function(col){
  var tb=document.getElementById('tableBody');
  var rows=Array.from(tb.querySelectorAll('tr.cat-row'));
  rows.sort(function(a,b){
    var va=a.cells[col]?a.cells[col].innerText.trim().toLowerCase():'';
    var vb=b.cells[col]?b.cells[col].innerText.trim().toLowerCase():'';
    return sortDir*va.localeCompare(vb);
  });
  sortDir=-sortDir;
  rows.forEach(function(r){tb.appendChild(r);});
};

var pendingUrl=null;
window.openModal=function(id,name,url){
  pendingUrl=url;
  document.getElementById('modalCatName').textContent='"'+name+'"';
  document.getElementById('deleteOverlay').classList.add('open');
};
window.closeModal=function(){document.getElementById('deleteOverlay').classList.remove('open');pendingUrl=null;};
window.confirmDelete=function(){if(!pendingUrl)return;var f=document.getElementById('deleteForm');f.action=pendingUrl;f.submit();};
})();
</script>
@endpush
