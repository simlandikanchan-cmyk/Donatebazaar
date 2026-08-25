@push('page_css')
@vite('resources/css/admin/entries/categories.css')
@endpush

@extends('layouts.admin')

@section('sidebar_categories', 'active')
@section('page_title', 'Categories')
@section('page_subtitle', 'Manage Categories')

@section('content')
<div class="overlay" id="deleteOverlay" role="dialog" aria-modal="true">
  <div class="modal">
    <button type="button" class="modal-x" data-action="close-modal" aria-label="Close"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
    <div class="modal-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg></div>
    <h3 id="modalTitle">Delete Category?</h3>
    <p id="modalMsg">This will permanently remove <strong id="modalCatName"></strong>. Campaigns using this category may be affected.</p>
    <div class="modal-acts">
      <button class="btn btn-secondary modal-cancel" data-action="close-modal">Cancel</button>
      <button class="btn btn-red modal-del" data-action="confirm-delete">Yes, Delete</button>
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

<div class="hero">
  <div class="hero-left">
    <div class="hero-tag"><span class="hero-tag-dot"></span>Catalog</div>
    <div class="hero-name">Categories</div>
    <div class="hero-sub">Organize campaigns into categories and control their visibility across the platform.</div>
    <div class="hero-badges">
      <span class="hero-badge hb-purple">{{ $total }} total</span>
      <span class="hero-badge hb-green">{{ $active }} active</span>
      <span class="hero-badge hb-gray">{{ $inactive }} inactive</span>
    </div>
  </div>
  <div class="hero-right">
    <a href="{{ route('admin.categories.create') }}" class="hero-btn hero-btn-primary">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
      Add Category
    </a>
  </div>
</div>

<div class="stats-grid">
  <div class="stat stat-on" id="statAll" data-action="set-filter" data-filter="all">
    <div class="stat-icon si-a"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a2 2 0 012-2z"/></svg></div>
    <div class="stat-body"><div class="stat-lbl">Total Categories</div><div class="stat-val sv-a">{{ $total }}</div><div class="stat-foot">All on platform</div></div>
  </div>
  <div class="stat" id="statActive" data-action="set-filter" data-filter="active">
    <div class="stat-icon si-green"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
    <div class="stat-body"><div class="stat-lbl">Active</div><div class="stat-val sv-green">{{ $active }}</div><div class="stat-foot">Visible to donors</div></div>
  </div>
  <div class="stat" id="statInactive" data-action="set-filter" data-filter="inactive">
    <div class="stat-icon si-slate"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg></div>
    <div class="stat-body"><div class="stat-lbl">Inactive</div><div class="stat-val sv-slate">{{ $inactive }}</div><div class="stat-foot">Hidden from public</div></div>
  </div>
</div>

<div class="toolbar">
  <div class="toolbar-left">
    <div class="search-wrap">
      <svg class="si" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
      <input type="text" class="search-input" id="searchInput" placeholder="Search categories…" aria-label="Search categories">
      <span class="search-kbd" id="searchKbd">/</span>
      <button type="button" class="search-clear" id="searchClearBtn" data-action="clear-search" aria-label="Clear search">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
      </button>
    </div>

    <div class="filter-group">
      <button class="filter-btn on" id="fAll" data-action="set-filter" data-filter="all">All <span class="cnt-badge">{{ $total }}</span></button>
      <button class="filter-btn" id="fActive" data-action="set-filter" data-filter="active">
        <span style="width:6px;height:6px;border-radius:50%;background:var(--green);display:inline-block;"></span>Active <span class="cnt-badge">{{ $active }}</span>
      </button>
      <button class="filter-btn" id="fInactive" data-action="set-filter" data-filter="inactive">
        <span style="width:6px;height:6px;border-radius:50%;background:var(--text3);display:inline-block;"></span>Inactive <span class="cnt-badge">{{ $inactive }}</span>
      </button>
    </div>

    <div class="filter-group">
      <button class="filter-btn on" id="cAll" data-action="set-campaign-filter" data-filter="all">Any Campaigns</button>
      <button class="filter-btn" id="cWith" data-action="set-campaign-filter" data-filter="with">With <span class="cnt-badge">{{ $withCampaigns }}</span></button>
      <button class="filter-btn" id="cWithout" data-action="set-campaign-filter" data-filter="without">Without <span class="cnt-badge">{{ $withoutCampaigns }}</span></button>
    </div>

    <div class="filter-group">
      <select class="sort-select" id="sortSelect" aria-label="Sort categories">
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
      <button class="view-btn on" id="viewTable" data-action="set-view" data-view="table" title="Table view" aria-label="Table view">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18"/></svg>
      </button>
      <button class="view-btn" id="viewGrid" data-action="set-view" data-view="grid" title="Grid view" aria-label="Grid view">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
      </button>
    </div>
  </div>
</div>

<div class="active-filters hide" id="activeFilters">
  <span class="af-label">Filters:</span>
  <span class="filter-chip" id="chipSearch" style="display:none;">Search: "<span id="chipSearchText"></span>"<button class="chip-x" data-action="clear-search"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button></span>
  <span class="filter-chip" id="chipStatus" style="display:none;"><span id="chipStatusText"></span><button class="chip-x" data-action="set-filter" data-filter="all"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button></span>
  <span class="filter-chip" id="chipCampaigns" style="display:none;"><span id="chipCampaignsText"></span><button class="chip-x" data-action="set-campaign-filter" data-filter="all"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button></span>
  <button class="clear-all-btn" data-action="clear-all">Clear all</button>
</div>

<div class="main-card">
  <div class="card-head">
    <div class="card-head-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a2 2 0 012-2z"/></svg></div>
    <span class="card-head-title">All Categories</span>
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
       <table id="catTable">
        <thead>
          <tr>
            <th class="th-check"><input type="checkbox" class="chk" id="selectAll" data-action="toggle-select-all" aria-label="Select all"></th>
            <th style="width:50px;">#</th>
            <th class="sortable" data-sort="name" data-action="sort-table" data-col="name">Name <span class="sort-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 15l5 5 5-5M7 9l5-5 5 5"/></svg></span></th>
            <th>Slug</th>
            <th class="sortable" data-sort="status" data-action="sort-table" data-col="status">Status <span class="sort-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 15l5 5 5-5M7 9l5-5 5 5"/></svg></span></th>
            <th class="sortable" data-sort="campaigns" data-action="sort-table" data-col="campaigns">Campaigns <span class="sort-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 15l5 5 5-5M7 9l5-5 5 5"/></svg></span></th>
            <th style="text-align:right;">Actions</th>
          </tr>
        </thead>
        <tbody id="tableBody">
          @forelse($categories as $category)
           <tr class="cat-row" data-id="{{ $category->id }}" data-name="{{ strtolower($category->name) }}" data-status="{{ $category->is_active?'active':'inactive' }}" data-campaigns="{{ $category->campaigns_count??0 }}" data-delete-url="{{ route('admin.categories.destroy',$category->id) }}" data-toggle-url="{{ route('admin.categories.toggle',$category->id) }}" style="animation:fadeUp 0.35s {{ $loop->index*0.04 }}s ease both;opacity:0;animation-fill-mode:both;">
            <td class="td-check"><input type="checkbox" class="chk row-check" data-action="toggle-row-select" aria-label="Select {{ $category->name }}"></td>
            <td data-label="#">{{ str_pad($loop->iteration,2,'0',STR_PAD_LEFT) }}</span></td>
            <td data-label="Name">
              <div class="cat-cell">
                <div class="cat-icon-box" style="background:{{ $category->color??'#2563eb ' }};"><i class="fa {{ $category->icon??'fa-tag' }}"></i></div>
                <div>
                  <div class="cat-name-text">{{ $category->name }}</div>
                  <div class="cat-name-sub">Added {{ $category->created_at->format('M d, Y') }}</div>
                </div>
              </div>
            </td>
            <td data-label="Slug"><span class="slug-pill"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101"/><path stroke-linecap="round" stroke-linejoin="round" d="M14.828 14.828a4 4 0 015.656 0l4-4a4 4 0 01-5.656-5.656l-1.1 1.1"/></svg>{{ $category->slug }}</span></td>
            <td data-label="Status">
               <label class="cat-toggle" title="Toggle active status">
                <span class="sw">
                  <input type="checkbox" {{ $category->is_active?'checked':'' }} data-action="toggle-status" data-id="{{ $category->id }}" aria-label="Toggle status for {{ $category->name }}">
                </span>
                <span class="cat-toggle-txt {{ $category->is_active?'active':'inactive' }}" id="statusTxt-{{ $category->id }}">{{ $category->is_active?'Active':'Inactive' }}</span>
              </label>
            </td>
            <td data-label="Campaigns"><span class="campaign-count {{ ($category->campaigns_count??0)==0?'zero':'' }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>{{ $category->campaigns_count??0 }}</span></td>
             <td data-label="Actions">
                <div class="actions">
                 <a href="{{ route('admin.categories.edit',$category->id) }}" class="btn btn-secondary act-btn act-edit"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>Edit</a>
                 <form method="POST" action="{{ route('admin.categories.destroy',$category->id) }}" style="display:inline;" onsubmit="return confirm('Delete this category? This cannot be undone.');">
                   @csrf @method('DELETE')
                   <button type="submit" class="btn btn-red act-btn act-del" title="Delete">
                     <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/></svg>Delete
                   </button>
                 </form>
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
        <button type="button" class="reset-btn" data-action="clear-all">
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
        <input type="checkbox" class="chk row-check grid-check" data-action="toggle-row-select" aria-label="Select {{ $category->name }}">
        <div class="grid-icon-box" style="background:{{ $category->color??'#2563eb ' }};"><i class="fa {{ $category->icon??'fa-tag' }}" style="color:#fff;"></i></div>
        <div class="grid-cat-name">{{ $category->name }}</div>
        <div class="grid-cat-slug">{{ $category->slug }}</div>
        <label class="cat-toggle" style="margin-bottom:12px;" title="Toggle active status">
          <span class="sw">
            <input type="checkbox" {{ $category->is_active?'checked':'' }} data-action="toggle-status" data-id="{{ $category->id }}" aria-label="Toggle status for {{ $category->name }}">
          </span>
          <span class="cat-toggle-txt {{ $category->is_active?'active':'inactive' }}" id="statusTxt-{{ $category->id }}">{{ $category->is_active?'Active':'Inactive' }}</span>
        </label>
        <div class="grid-count-badge">{{ $category->campaigns_count??0 }} campaigns</div>
        <div class="grid-actions">
          <a href="{{ route('admin.categories.edit',$category->id) }}" class="btn btn-secondary act-btn act-edit" style="font-size:11px;padding:4px 10px;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>Edit</a>
          <form method="POST" action="{{ route('admin.categories.destroy',$category->id) }}" style="display:inline;" onsubmit="return confirm('Delete this category? This cannot be undone.');">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-red act-btn act-del" style="font-size:11px;padding:4px 10px;" title="Delete">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/></svg>Delete
            </button>
          </form>
        </div>
      </div>
      @endforeach
    </div>
    <div class="empty-state" id="noResultsStateGrid" style="display:none;">
      <div class="empty-icon-wrap">🔍</div>
      <h3>No matching categories</h3>
      <p>Try adjusting your search or filters</p>
      <button type="button" class="reset-btn" data-action="clear-all">
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
      <button type="button" class="btn btn-red bulk-btn bulk-btn-del" data-action="open-bulk-modal">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/></svg>Delete
      </button>
      <button type="button" class="btn btn-secondary bulk-btn bulk-btn-cancel" data-action="clear-selection">Cancel</button>
    </div>
  </div>
</div>
@endsection

@push('page_scripts')
@vite('resources/js/admin/entries/categories-index.js')
@endpush
