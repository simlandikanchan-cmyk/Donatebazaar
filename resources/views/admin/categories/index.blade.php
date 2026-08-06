@extends('layouts.admin')

@push('page_css')
@vite('resources/css/admin/entries/categories.css')
@endpush


@section('sidebar_categories', 'active')
@section('page_title', 'Categories')
@section('page_subtitle', 'Organize and manage campaign categories')

@section('content')
<script>document.documentElement.classList.add('js');</script>

<div class="cat-page" id="catPage">

{{-- -------------- DELETE MODAL -------------- --}}
<div class="overlay" id="deleteOverlay" role="dialog" aria-modal="true" aria-labelledby="modalTitle" aria-describedby="modalMsg">
  <div class="modal">
    <button type="button" class="modal-x" onclick="closeModal()" aria-label="Close"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
    <div class="modal-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg></div>
    <h3 id="modalTitle">Delete Category?</h3>
    <p id="modalMsg">This will permanently remove <strong id="modalCatName"></strong>. Campaigns using this category may be affected.</p>
    <div class="modal-acts">
      <x-button variant="secondary" type="button" onclick="closeModal()">Cancel</x-button>
      <x-button variant="destructive" type="button" class="modal-del" onclick="confirmDelete()">Yes, Delete</x-button>
    </div>
  </div>
</div>
<form id="deleteForm" method="POST" style="display:none;">@csrf @method('DELETE')</form>

@if(session('success'))
<div class="alert-ok" id="flashAlert">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
  {{ session('success') }}
</div>
@endif

@php
  $total = $categories->count();
  $active = $categories->where('is_active', 1)->count();
  $inactive = $total - $active;
  $withCampaigns = $categories->filter(fn($c) => ($c->campaigns_count ?? 0) > 0)->count();
  $totalCampaigns = $categories->sum('campaigns_count');
@endphp

{{-- -------------- PAGE HEADER -------------- --}}
<div class="page-header">
  <div class="page-header-left">
    <nav class="breadcrumb" aria-label="Breadcrumb">
      <a href="{{ route('admin.dashboard') }}">Dashboard</a>
      <svg class="bc-sep" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
      <span class="bc-cur">Categories</span>
    </nav>
    <h1 class="page-title">Categories</h1>
    <p class="page-desc">Organize and manage campaign categories</p>
  </div>
  <div class="page-header-actions">
    <x-button variant="primary" href="{{ route('admin.categories.create') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
      Add Category
    </x-button>
  </div>
</div>

{{-- -------------- STATS CARDS -------------- --}}
<div class="stats-grid">
  <button type="button" class="stat-card on" id="statAll" onclick="setFilter('all')" aria-pressed="true">
    <div class="stat-icon si-a">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a2 2 0 012-2z"/></svg>
    </div>
    <div class="stat-body">
      <div class="stat-label">Total Categories</div>
      <div class="stat-value sv-a" id="statValAll">{{ $total }}</div>
      <div class="stat-sub">All categories</div>
    </div>
  </button>
  <button type="button" class="stat-card" id="statActive" onclick="setFilter('active')" aria-pressed="false">
    <div class="stat-icon si-green">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    </div>
    <div class="stat-body">
      <div class="stat-label">Active</div>
      <div class="stat-value text-green" id="statValActive">{{ $active }}</div>
      <div class="stat-sub">Visible to public</div>
    </div>
  </button>
  <button type="button" class="stat-card" id="statInactive" onclick="setFilter('inactive')" aria-pressed="false">
    <div class="stat-icon si-red">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
    </div>
    <div class="stat-body">
      <div class="stat-label">Inactive</div>
      <div class="stat-value text-red" id="statValInactive">{{ $inactive }}</div>
      <div class="stat-sub">Hidden from public</div>
    </div>
  </button>
</div>

{{-- -------------- TOOLBAR -------------- --}}
<div class="toolbar-card">
  <div class="toolbar-inner">
    <div class="toolbar-search">
      <svg class="tb-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
      <input type="text" id="searchInput" placeholder="Search categories…" oninput="filterTable()" aria-label="Search categories">
      <button type="button" class="search-clear-btn" id="searchClearBtn" onclick="clearSearch()" aria-label="Clear search" style="display:none;">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
      </button>
    </div>
    <div class="toolbar-filters">
      <div class="select-wrap">
        <svg class="tb-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
        <select id="statusFilter" onchange="setFilter(this.value)" aria-label="Filter by status">
          <option value="all">All Status</option>
          <option value="active">Active</option>
          <option value="inactive">Inactive</option>
        </select>
      </div>
      <div class="select-wrap">
        <svg class="tb-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
        <select id="campaignFilter" onchange="setCampaignFilter(this.value)" aria-label="Filter by campaigns">
          <option value="all">Any Campaigns</option>
          <option value="with">With Campaigns</option>
          <option value="without">Without Campaigns</option>
        </select>
      </div>
    </div>
    <div class="toolbar-actions">
      <button type="button" class="btn btn-ghost btn-reset" id="resetFiltersBtn" onclick="clearAllFilters()" style="display:none;">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
        Reset Filters
      </button>
      <div class="view-toggle">
        <button class="view-btn on" id="viewTable" onclick="setView('table',this)" title="Table view" aria-label="Table view" aria-pressed="true">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18"/></svg>
        </button>
        <button class="view-btn" id="viewGrid" onclick="setView('grid',this)" title="Grid view" aria-label="Grid view" aria-pressed="false">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
        </button>
      </div>
    </div>
  </div>
</div>

{{-- -------------- TABLE CARD -------------- --}}
<div class="table-card">

  <div class="table-card-head">
    <div class="table-card-head-left">
      <div class="table-card-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a2 2 0 012-2z"/></svg>
      </div>
      <div>
        <div class="table-card-title">All Categories</div>
        <div class="table-card-sub">{{ $total }} categor{{ $total === 1 ? 'y' : 'ies' }} • {{ $totalCampaigns }} campaign{{ $totalCampaigns === 1 ? '' : 's' }} linked</div>
      </div>
    </div>
    <div class="table-card-head-right">
      <span class="result-count" id="visibleCount">{{ $total }} of {{ $total }}</span>
    </div>
  </div>

  {{-- Bulk action bar --}}
  <div class="table-bulk-bar" id="bulkBar">
    <div class="table-bulk-bar-inner">
      <span class="table-bulk-count"><strong id="bulkCount">0</strong> selected</span>
      <div class="table-bulk-actions">
        <button type="button" class="bulk-btn btn btn-primary btn-sm" onclick="bulkToggleStatus(true)">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4"/></svg>Activate
        </button>
        <button type="button" class="bulk-btn btn btn-primary btn-sm" onclick="bulkToggleStatus(false)">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>Deactivate
        </button>
        <button type="button" class="btn btn--destructive btn--sm" onclick="openBulkModal()">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/></svg>Delete
        </button>
        <button type="button" class="bulk-btn btn btn-secondary btn-sm" onclick="clearSelection()">Clear Selection</button>
      </div>
    </div>
  </div>

  {{-- ------ TABLE VIEW ------ --}}
  <div id="tableView">
    @if($categories->isEmpty())
      <div class="empty-state">
        <div class="empty-illustration">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a2 2 0 012-2z"/></svg>
        </div>
        <h3>No Categories Found</h3>
        <p>Add your first category to get started</p>
        <x-button variant="primary" href="{{ route('admin.categories.create') }}">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>Add Category
        </x-button>
      </div>
    @else
      <div class="cat-skeleton" id="catSkeleton" aria-hidden="true">
        @for($i=0;$i<5;$i++)
        <div class="cat-skel-row">
          <span class="sk sk-check"></span>
          <span class="sk sk-ico"></span>
          <span class="sk sk-line w40"></span>
          <span class="sk sk-line w22"></span>
          <span class="sk sk-line w14"></span>
          <span class="sk sk-line w10"></span>
        </div>
        @endfor
      </div>

      <div class="cat-table-content" id="catTableContent">
        <div class="table-wrap">
          <table>
            <thead>
              <tr>
                <th class="th-check"><input type="checkbox" id="selectAll" onchange="toggleSelectAll(this)" aria-label="Select all categories"></th>
                <th class="th-num">#</th>
                <th class="th-img">Icon</th>
                <th class="sortable" data-sort="name">
                  <a href="javascript:void(0)" class="sort-link" onclick="sortTable('name')">
                    Category
                    <svg class="sort-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                  </a>
                </th>
                <th class="sortable" data-sort="campaigns">
                  <a href="javascript:void(0)" class="sort-link" onclick="sortTable('campaigns')">
                    Campaigns
                    <svg class="sort-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                  </a>
                </th>
                <th>Status</th>
                <th class="th-actions">Actions</th>
              </tr>
            </thead>
            <tbody id="tableBody">
              @foreach($categories as $category)
              <tr class="cat-row" data-id="{{ $category->id }}" data-name="{{ strtolower($category->name) }}" data-status="{{ $category->is_active?'active':'inactive' }}" data-campaigns="{{ $category->campaigns_count??0 }}" data-delete-url="{{ route('admin.categories.destroy',$category->id) }}" data-toggle-url="{{ route('admin.categories.toggle',$category->id) }}">
                <td class="td-check"><input type="checkbox" class="chk row-check" onchange="toggleRowSelect(this)" aria-label="Select {{ $category->name }}"></td>
                <td class="td-num"><span class="serial">{{ str_pad($loop->iteration,2,'0',STR_PAD_LEFT) }}</span></td>
                <td class="td-img">
                  <div class="cat-icon-box" style="background:{{ $category->color??'#2563eb ' }};"><i class="fa-solid {{ $category->icon??'fa-tag' }}"></i></div>
                </td>
                <td>
                  <div class="product-cell">
                    <div class="product-meta">
                      <span class="product-name">{{ $category->name }}</span>
                      <span class="td-sub">{{ $category->slug }}</span>
                    </div>
                  </div>
                </td>
                <td>
                  <span class="campaign-count {{ ($category->campaigns_count??0)==0?'zero':'' }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>{{ $category->campaigns_count??0 }}</span>
                </td>
                <td>
                  <button type="button" class="status-badge status-{{ $category->is_active?'active':'inactive' }}" id="statusBadge-{{ $category->id }}" onclick="toggleStatus('{{ $category->id }}', {{ $category->is_active?'false':'true' }})" title="Toggle active status" aria-pressed="{{ $category->is_active?'true':'false' }}">
                    <span class="status-dot {{ $category->is_active?'dot-active':'dot-draft' }}"></span>
                    <span id="statusTxt-{{ $category->id }}">{{ $category->is_active?'Active':'Inactive' }}</span>
                  </button>
                </td>
                <td class="td-actions">
                  <div class="action-btns">
                    <a href="{{ route('admin.categories.edit',$category->id) }}" class="act-btn act-edit" title="Edit" aria-label="Edit {{ $category->name }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></a>
                    <button type="button" class="act-btn act-del" onclick="openModal('{{ $category->id }}','{{ addslashes($category->name) }}','{{ route('admin.categories.destroy',$category->id) }}')" title="Delete" aria-label="Delete {{ $category->name }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/></svg></button>
                  </div>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        <div class="empty-state" id="noResultsState" style="display:none;">
          <div class="empty-illustration">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
          </div>
          <h3>No Categories Found</h3>
          <p>Try adjusting your search or filters</p>
          <x-button variant="secondary" type="button" onclick="clearAllFilters()">Reset Filters</x-button>
        </div>

        @if($categories instanceof \Illuminate\Pagination\LengthAwarePaginator && $categories->hasPages())
        <div class="table-footer">
          <div class="tfoot-info">Showing <strong>{{ $categories->firstItem() }}</strong>–<strong>{{ $categories->lastItem() }}</strong> of <strong>{{ $categories->total() }}</strong> categories</div>
          <div class="pagination-wrap">
            @if($categories->onFirstPage())
              <span class="page-btn" style="opacity:.4;cursor:not-allowed;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg></span>
            @else
              <a href="{{ $categories->previousPageUrl() }}" class="page-btn" aria-label="Previous page"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg></a>
            @endif
            @foreach($categories->getUrlRange(1,$categories->lastPage()) as $page=>$url)
              <a href="{{ $url }}" class="page-btn {{ $categories->currentPage()==$page?'cur':'' }}" aria-label="Page {{ $page }}">{{ $page }}</a>
            @endforeach
            @if($categories->hasMorePages())
              <a href="{{ $categories->nextPageUrl() }}" class="page-btn" aria-label="Next page"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg></a>
            @else
              <span class="page-btn" style="opacity:.4;cursor:not-allowed;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg></span>
            @endif
          </div>
        </div>
        @endif
      </div>
    @endif
  </div>

  {{-- ------ GRID VIEW ------ --}}
  <div id="gridView" style="display:none;">
    @if($categories->isEmpty())
      <div class="empty-state">
        <div class="empty-illustration">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a2 2 0 012-2z"/></svg>
        </div>
        <h3>No Categories Found</h3>
        <p>Add your first category to get started</p>
        <x-button variant="primary" href="{{ route('admin.categories.create') }}">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>Add Category
        </x-button>
      </div>
    @else
      <div class="cat-grid" id="gridBody">
        @foreach($categories as $category)
        <article class="cat-grid-item" data-id="{{ $category->id }}" style="--item-color:{{ $category->color??'#2563eb ' }};animation-delay:{{ $loop->index*0.04 }}s;" data-name="{{ strtolower($category->name) }}" data-status="{{ $category->is_active?'active':'inactive' }}" data-campaigns="{{ $category->campaigns_count??0 }}" data-delete-url="{{ route('admin.categories.destroy',$category->id) }}" data-toggle-url="{{ route('admin.categories.toggle',$category->id) }}">
          <input type="checkbox" class="chk row-check grid-check" onchange="toggleRowSelect(this)" aria-label="Select {{ $category->name }}">
          <div class="cg-head">
            <span class="grid-icon-box" style="background:{{ $category->color??'#2563eb ' }};"><i class="fa-solid {{ $category->icon??'fa-tag' }}"></i></span>
            <div class="cg-title">
              <span class="cg-name">{{ $category->name }}</span>
              <span class="cg-slug">{{ $category->slug }}</span>
            </div>
          </div>
          <div class="cg-mid">
            <span class="cat-count-chip {{ ($category->campaigns_count??0)==0?'zero':'' }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>{{ $category->campaigns_count??0 }} campaign{{ ($category->campaigns_count??0)===1?'':'s' }}</span>
            <label class="cat-toggle" title="Toggle active status">
              <span class="sw">
                <input type="checkbox" {{ $category->is_active?'checked':'' }} onchange="toggleStatus('{{ $category->id }}',this.checked)" aria-label="Toggle status for {{ $category->name }}">
              </span>
              <span class="cat-toggle-txt {{ $category->is_active?'active':'inactive' }}" id="statusTxt-{{ $category->id }}">{{ $category->is_active?'Active':'Inactive' }}</span>
            </label>
          </div>
          <footer class="cg-foot">
            <span class="cg-date">Added {{ $category->created_at->format('M d, Y') }}</span>
            <div class="action-btns">
              <a href="{{ route('admin.categories.edit',$category->id) }}" class="act-btn act-edit" title="Edit {{ $category->name }}" aria-label="Edit {{ $category->name }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></a>
              <button type="button" class="act-btn act-del" onclick="openModal('{{ $category->id }}','{{ addslashes($category->name) }}','{{ route('admin.categories.destroy',$category->id) }}')" title="Delete {{ $category->name }}" aria-label="Delete {{ $category->name }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/></svg></button>
            </div>
          </footer>
        </article>
        @endforeach
      </div>
      <div class="empty-state" id="noResultsStateGrid" style="display:none;">
        <div class="empty-illustration">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
        </div>
        <h3>No Categories Found</h3>
        <p>Try adjusting your search or filters</p>
        <x-button variant="secondary" type="button" onclick="clearAllFilters()">Reset Filters</x-button>
      </div>
    @endif
  </div>
</div>
</div>
@endsection

@push('page_scripts')
<script>
(function(){
'use strict';

var page=document.getElementById('catPage');

/* ---------- Flash auto-hide ---------- */
(function(){var a=document.getElementById('flashAlert');if(!a)return;setTimeout(function(){a.style.transition='opacity .4s,transform .4s';a.style.opacity='0';a.style.transform='translateY(-6px)';setTimeout(function(){a.remove();},400);},4000);})();

/* ---------- Toast ---------- */
function toast(msg,type){
  var t=document.createElement('div');
  t.style.cssText='position:fixed;top:20px;right:20px;z-index:9999;display:flex;align-items:center;gap:10px;padding:13px 16px;border-radius:14px;font-size:13px;font-weight:500;color:#fff;min-width:240px;box-shadow:0 10px 30px rgba(0,0,0,.25);animation:fadeUp .3s ease both;'+(type==='error'?'background:linear-gradient(135deg,#dc2626,#f04444);':'background:linear-gradient(135deg,#059669,#10b981);');
  t.innerHTML=(type==='error'?'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:15px;height:15px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>':'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:15px;height:15px;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>')+'<span>'+msg+'</span><button style="margin-left:auto;background:transparent;border:none;color:inherit;opacity:.7;cursor:pointer;font-size:14px;" onclick="this.parentElement.remove()" aria-label="Dismiss">?</button>';
  document.body.appendChild(t);
  setTimeout(function(){t.style.transition='opacity .3s,transform .3s';t.style.opacity='0';t.style.transform='translateX(20px)';setTimeout(function(){t.remove();},300);},3800);
}

/* ---------- Skeleton -> content ---------- */
if(page){setTimeout(function(){page.classList.add('is-ready');},160);}

/* ---------- Inline status toggle (badge) ---------- */
function setStatCardPressed(){
  ['statAll','statActive','statInactive'].forEach(function(id){
    var el=document.getElementById(id);
    if(!el)return;
    var active=(id==='statAll'&&activeFilter==='all')||(id==='statActive'&&activeFilter==='active')||(id==='statInactive'&&activeFilter==='inactive');
    el.classList.toggle('on',active);
    el.setAttribute('aria-pressed',active?'true':'false');
  });
}

function updateStatusStat(toActive){
  var a=document.getElementById('statValActive'),i=document.getElementById('statValInactive');
  if(a&&i){
    var av=parseInt(a.textContent||'0',10);
    var iv=parseInt(i.textContent||'0',10);
    a.textContent=toActive?av+1:av-1;
    i.textContent=toActive?iv-1:iv+1;
  }
}

window.toggleStatus=function(id,toActive){
  var row=document.querySelector('[data-id="'+id+'"]');
  var url=row?row.getAttribute('data-toggle-url'):null;
  var badge=document.getElementById('statusBadge-'+id);
  var txt=document.getElementById('statusTxt-'+id);
  if(!url)return;
  if(row)row.setAttribute('data-status',toActive?'active':'inactive');
  if(badge){badge.className='status-badge status-'+(toActive?'active':'inactive');badge.setAttribute('aria-pressed',toActive?'true':'false');}
  if(txt)txt.textContent=toActive?'Active':'Inactive';
  updateStatusStat(toActive);
  var token=document.querySelector('#deleteForm input[name="_token"]').value;
  var fd=new FormData();fd.append('_token',token);
  fetch(url,{method:'POST',body:fd,headers:{'X-Requested-With':'XMLHttpRequest'}})
    .then(function(r){if(!r.ok)throw new Error('Failed');toast('Status updated','ok');})
    .catch(function(){
      if(row)row.setAttribute('data-status',toActive?'inactive':'active');
      if(badge){badge.className='status-badge status-'+(toActive?'inactive':'active');badge.setAttribute('aria-pressed',toActive?'false':'true');}
      if(txt){txt.textContent=toActive?'Inactive':'Active';}
      updateStatusStat(!toActive);
      toast('Could not update status','error');
    });
};

/* ---------- View toggle ---------- */
var currentView=localStorage.getItem('catView')||'table';
function applyView(v){
  var tv=document.getElementById('tableView'),gv=document.getElementById('gridView');
  if(tv)tv.style.display=v==='table'?'':'none';
  if(gv)gv.style.display=v==='grid'?'':'none';
  document.querySelectorAll('.view-btn').forEach(function(b){
    var active=b.id===(v==='table'?'viewTable':'viewGrid');
    b.classList.toggle('on',active);
    b.setAttribute('aria-pressed',active?'true':'false');
  });
}
window.setView=function(v){currentView=v;localStorage.setItem('catView',v);applyView(v);filterTable();};
applyView(currentView);

/* ---------- Filters ---------- */
var activeFilter='all';
var campaignFilter='all';
var sortDir={};

function setStatusSelect(v){var s=document.getElementById('statusFilter');if(s)s.value=v;}
function setCampaignSelect(v){var s=document.getElementById('campaignFilter');if(s)s.value=v;}

window.setFilter=function(f){
  activeFilter=f;
  setStatusSelect(f);
  setStatCardPressed();
  updateFilterUI();
  filterTable();
};

window.setCampaignFilter=function(f){
  campaignFilter=f;
  setCampaignSelect(f);
  updateFilterUI();
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
  setFilter('all');
  setCampaignFilter('all');
};

function updateFilterUI(){
  var q=document.getElementById('searchInput').value.trim();
  var any=q.length>0||activeFilter!=='all'||campaignFilter!=='all';
  var resetBtn=document.getElementById('resetFiltersBtn');
  if(resetBtn)resetBtn.style.display=any?'inline-flex':'none';
}

window.filterTable=function(){
  var q=document.getElementById('searchInput').value.toLowerCase().trim();
  var clearBtn=document.getElementById('searchClearBtn');
  if(clearBtn)clearBtn.style.display=q.length>0?'flex':'none';
  updateFilterUI();

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

  var total=tableRows.length;
  var vc=document.getElementById('visibleCount');
  if(vc)vc.textContent=visible+' of '+total;

  var noRes=document.getElementById('noResultsState');
  if(noRes)noRes.style.display=(visible===0&&total>0)?'':'none';
  var noResGrid=document.getElementById('noResultsStateGrid');
  if(noResGrid)noResGrid.style.display=(visible===0&&gridItems.length>0)?'':'none';

  updateSelectAllState();
};

/* ---------- Sorting ---------- */
window.sortTable=function(col){
  var dir=sortDir[col]===1?-1:1;
  sortDir={};sortDir[col]=dir;
  document.querySelectorAll('.sortable').forEach(function(th){
    var link=th.querySelector('.sort-link');
    var ico=th.querySelector('.sort-ico');
    var active=th.getAttribute('data-sort')===col;
    if(link)link.classList.toggle('sort-active',active);
    if(ico){ico.classList.toggle('asc',active&&dir===1);ico.classList.toggle('desc',active&&dir===-1);}
    th.setAttribute('aria-sort',active?(dir===-1?'descending':'ascending'):'none');
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
};

/* ---------- Keyboard shortcuts ---------- */
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

/* ---------- Bulk selection ---------- */
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
  document.getElementById('bulkCount').textContent=count;
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

/* ---------- Bulk toggle status (uses existing single toggle route) ---------- */
window.bulkToggleStatus=function(toActive){
  var ids=Array.from(selected);
  if(!ids.length)return;
  ids.forEach(function(id){toggleStatus(id,toActive);});
  clearSelection();
  toast((toActive?'Activated':'Deactivated')+' '+ids.length+' categor'+(ids.length===1?'y':'ies'),'ok');
};

/* ---------- Delete modal (single + bulk) ---------- */
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

/* init */
filterTable();
})();
</script>
@endpush
