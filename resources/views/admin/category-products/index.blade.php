@extends('layouts.admin')

@push('page_css')
@vite('resources/css/admin/entries/categories.css')
@endpush


@section('sidebar_products', 'active')
@section('page_title', 'Category Products')
@section('page_subtitle', 'Manage all product categories and inventory')

@section('content')
{{-- Delete single modal --}}
<div class="overlay" id="deleteOverlay" role="dialog" aria-modal="true">
  <div class="modal">
    <button type="button" class="modal-x" onclick="closeModal()"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
    <div class="modal-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg></div>
    <h3>Delete Product?</h3>
    <p>This will permanently remove <strong id="modalProdName"></strong>. This action cannot be undone.</p>
    <div class="modal-acts">
      <x-button variant="secondary" type="button">Cancel</x-button>
      <x-button variant="destructive" type="button" class="modal-del">Yes, Delete</x-button>
    </div>
  </div>
</div>
<form id="deleteForm" method="POST" style="display:none;">@csrf @method('DELETE')</form>

{{-- Delete bulk modal --}}
<div class="overlay" id="bulkDeleteOverlay" role="dialog" aria-modal="true">
  <div class="modal">
    <button type="button" class="modal-x" onclick="closeBulkModal()"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
    <div class="modal-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg></div>
    <h3>Delete Selected Products?</h3>
    <p>This will permanently remove <strong id="bulkCountDisplay">0</strong> product(s). This action cannot be undone.</p>
    <div class="modal-acts">
      <x-button variant="secondary" type="button">Cancel</x-button>
      <x-button variant="destructive" type="button" class="modal-del">Yes, Delete</x-button>
    </div>
  </div>
</div>

{{-- Lightbox --}}
<div class="lightbox-overlay" id="lightboxOverlay" onclick="closeLightbox()">
  <button class="lightbox-close" onclick="closeLightbox()"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
  <img id="lightboxImg" src="" alt="Product image">
</div>

@if(session('success'))
<div class="alert-ok" id="flashAlert">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
  {{ session('success') }}
</div>
@endif

@php
  $total = $products->total();
  $activeCount = \App\Models\CategoryProduct::where('is_active', 1)->count();
  $inactiveCount = $total - $activeCount;

  $sortUrl = function($column) use ($sort, $dir) {
      $newDir = $sort === $column && $dir === 'asc' ? 'desc' : 'asc';
      return request()->fullUrlWithQuery(['sort' => $column, 'direction' => $newDir]);
  };
@endphp

{{-- Page Header --}}
<div class="page-header">
  <div class="page-header-left">
    <nav class="breadcrumb" aria-label="Breadcrumb">
      <a href="{{ route('admin.dashboard') }}">Dashboard</a>
      <svg class="bc-sep" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
      <span class="bc-cur">Category Products</span>
    </nav>
    <h1 class="page-title">Category Products</h1>
    <p class="page-desc">Manage all product categories and inventory</p>
  </div>
  <div class="page-header-actions">
    <a href="{{ route('admin.category-products.export', request()->only('search', 'category', 'status')) }}" class="btn btn-secondary btn-export">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
      Export CSV
    </a>
    <x-button variant="primary" href="{{ route('admin.category-products.create') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
      Add Product
    </x-button>
  </div>
</div>

{{-- Stats Cards --}}
<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-icon si-a">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
    </div>
    <div class="stat-body">
      <div class="stat-label">Total Products</div>
      <div class="stat-value">{{ $total }}</div>
      <div class="stat-sub">All products</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon si-green">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    </div>
    <div class="stat-body">
      <div class="stat-label">Active</div>
      <div class="stat-value text-green">{{ $activeCount }}</div>
      <div class="stat-sub">Visible to public</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon si-red">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
    </div>
    <div class="stat-body">
      <div class="stat-label">Inactive</div>
      <div class="stat-value text-red">{{ $inactiveCount }}</div>
      <div class="stat-sub">Hidden from public</div>
    </div>
  </div>
</div>

{{-- Toolbar --}}
<div class="toolbar-card">
  <form id="filterForm" method="GET" action="{{ route('admin.category-products.index') }}">
    <div class="toolbar-inner">
      <div class="toolbar-search">
        <svg class="tb-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
        <input type="text" name="search" value="{{ $search }}" placeholder="Search products…" oninput="autoSubmit()" aria-label="Search products">
        @if($search)
        <button type="button" class="search-clear-btn" onclick="clearSearch()" aria-label="Clear search">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18 6L6 18M6 6l12 12"/></svg>
        </button>
        @endif
      </div>
      <div class="toolbar-filters">
        <div class="select-wrap">
          <svg class="tb-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a2 2 0 012-2z"/></svg>
          <select name="category" onchange="this.form.submit()" aria-label="Filter by category">
            <option value="">All Categories</option>
            @foreach($categories as $cat)
              <option value="{{ $cat->id }}" {{ (string)$catId === (string)$cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="select-wrap">
          <svg class="tb-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
          <select name="status" onchange="this.form.submit()" aria-label="Filter by status">
            <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All Status</option>
            <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ $status === 'inactive' ? 'selected' : '' }}>Inactive</option>
          </select>
        </div>
        <input type="hidden" name="sort" value="{{ $sort }}">
        <input type="hidden" name="direction" value="{{ $dir }}">
      </div>
      <div class="toolbar-actions">
        @if($search || $catId || $status !== 'all')
        <a href="{{ route('admin.category-products.index') }}" class="btn btn-ghost btn-reset">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
          Reset Filters
        </a>
        @endif
      </div>
    </div>
  </form>
</div>

{{-- Table Container --}}
<div class="table-card">
  <div class="table-card-head">
    <div class="table-card-head-left">
      <div class="table-card-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
      </div>
      <div>
        <div class="table-card-title">All Products</div>
        <div class="table-card-sub">{{ $total }} product{{ $total !== 1 ? 's' : '' }}</div>
      </div>
    </div>
    <div class="table-card-head-right">
      <span class="result-count">{{ $products->firstItem() }}-{{ $products->lastItem() }} of {{ $total }}</span>
    </div>
  </div>

  {{-- Bulk action bar --}}
  <div class="table-bulk-bar" id="bulkBar">
    <div class="table-bulk-bar-inner">
      <span class="table-bulk-count"><strong id="bulkCount">0</strong> selected</span>
      <div class="table-bulk-actions">
        <button type="button" class="bulk-btn btn btn-primary btn-sm" onclick="bulkAction('activate')">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4"/></svg>Activate
        </button>
        <button type="button" class="bulk-btn btn btn-primary btn-sm" onclick="bulkAction('deactivate')">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>Deactivate
        </button>
        <button type="button" class="btn btn--destructive btn--sm" onclick="openBulkDelete()">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/></svg>Delete
        </button>
        <button type="button" class="bulk-btn btn btn-secondary btn-sm" onclick="clearAllCheckboxes()">Clear Selection</button>
      </div>
    </div>
  </div>

  <form id="bulkForm" method="POST" style="display:none;">@csrf</form>

  @if($products->isEmpty())
  <div class="empty-state">
    <div class="empty-illustration">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
    </div>
    <h3>No Products Found</h3>
    @if($search || $catId || $status !== 'all')
      <p>Try adjusting your search or filters</p>
    @else
      <p>Add your first product to get started</p>
      <x-button variant="primary" href="{{ route('admin.category-products.create') }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>Add Product
      </x-button>
    @endif
  </div>
  @else
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th class="th-check"><input type="checkbox" id="selectAll" onchange="toggleAll(this)" aria-label="Select all products"></th>
          <th class="th-num">#</th>
          <th class="th-img">Image</th>
          <th>
            <a href="{{ $sortUrl('name') }}" class="sort-link">
              Product
              <svg class="sort-ico {{ $sort==='name' ? ($dir==='asc'?'asc':'desc') : '' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
            </a>
          </th>
          <th>Category</th>
          <th>
            <a href="{{ $sortUrl('product_type') }}" class="sort-link">
              Type
              <svg class="sort-ico {{ $sort==='product_type' ? ($dir==='asc'?'asc':'desc') : '' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
            </a>
          </th>
          <th>
            <a href="{{ $sortUrl('price') }}" class="sort-link">
              Price
              <svg class="sort-ico {{ $sort==='price' ? ($dir==='asc'?'asc':'desc') : '' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
            </a>
          </th>
          <th>
            <a href="{{ $sortUrl('stock') }}" class="sort-link">
              Stock
              <svg class="sort-ico {{ $sort==='stock' ? ($dir==='asc'?'asc':'desc') : '' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
            </a>
          </th>
          <th>Status</th>
          <th class="th-actions">Actions</th>
        </tr>
      </thead>
      <tbody id="tableBody">
        @forelse($products as $product)
        <tr>
          <td class="td-check"><input type="checkbox" class="row-check" value="{{ $product->id }}" onchange="updateBulkBar()" aria-label="Select product"></td>
          <td class="td-num"><span class="serial">{{ $products->firstItem() + $loop->index }}</span></td>
          <td class="td-img">
            @if($product->image)
              <img src="{{ asset('storage/'.$product->image) }}" class="prod-img" alt="{{ $product->name }}" onclick="openLightbox(this.src)" loading="lazy">
            @else
              <div class="prod-placeholder"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2 3h6a4 4 0 014 4v14a3 3 0 00-3-3H2z"/><path stroke-linecap="round" stroke-linejoin="round" d="M22 3h-6a4 4 0 00-4 4v14a3 3 0 013-3h7z"/></svg></div>
            @endif
          </td>
          <td class="td-product">
            <div class="product-cell">
              <div class="product-name">{{ $product->name }}</div>
              <div class="product-meta">{{ $product->description ? \Illuminate\Support\Str::limit($product->description, 40) : 'No description' }}</div>
            </div>
          </td>
          <td><span class="cat-badge">{{ $product->category->name ?? '—' }}</span></td>
          <td><span class="type-badge">{{ ucfirst($product->product_type) }}</span></td>
          <td class="td-price"><span class="price-val">?{{ number_format($product->price, 2) }}</span></td>
          <td>
            @php
              $stockClass = $product->stock == 0 ? 'stock-badge stock-out' : ($product->stock < 10 ? 'stock-badge stock-low' : 'stock-badge stock-ok');
              $stockLabel = $product->stock == 0 ? 'Out of Stock' : ($product->stock < 10 ? 'Low Stock' : 'In Stock');
            @endphp
            <span class="{{ $stockClass }}">{{ $product->stock }} {{ $stockLabel }}</span>
          </td>
          <td>
            @if($product->is_active)
              <span class="status-badge status-active"><span class="status-dot"></span>Active</span>
            @else
              <span class="status-badge status-inactive"><span class="status-dot"></span>Inactive</span>
            @endif
          </td>
          <td class="td-actions">
            <div class="action-btns">
              <a href="{{ route('admin.category-products.edit', $product->id) }}" class="act-btn act-view" title="View" aria-label="View product">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
              </a>
              <a href="{{ route('admin.category-products.edit', $product->id) }}" class="act-btn act-edit" title="Edit" aria-label="Edit product">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
              </a>
              <button type="button" class="act-btn act-del" onclick="openModal('{{ $product->id }}','{{ addslashes($product->name) }}','{{ route('admin.category-products.destroy',$product->id) }}')" title="Delete" aria-label="Delete product">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg>
              </button>
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="10">
          <div class="empty-state"><div class="empty-illustration"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg></div><h3>No products found</h3><p>Try adjusting your search or filters</p></div>
        </td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{-- Pagination --}}
  @if($products instanceof \Illuminate\Pagination\LengthAwarePaginator && $products->hasPages())
  <div class="table-footer">
    <div class="tfoot-info">Showing <strong>{{ $products->firstItem() }}</strong>–<strong>{{ $products->lastItem() }}</strong> of <strong>{{ $products->total() }}</strong> products</div>
    <div class="pagination-wrap">
      @if($products->onFirstPage())
        <span class="page-btn" style="opacity:.4;cursor:not-allowed;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg></span>
      @else
        <a href="{{ $products->appends(request()->query())->previousPageUrl() }}" class="page-btn"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg></a>
      @endif
      @foreach($products->getUrlRange(1,$products->lastPage()) as $page=>$url)
        <a href="{{ $products->appends(request()->query())->url($page) }}" class="page-btn {{ $products->currentPage()==$page?'cur':'' }}">{{ $page }}</a>
      @endforeach
      @if($products->hasMorePages())
        <a href="{{ $products->appends(request()->query())->nextPageUrl() }}" class="page-btn"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg></a>
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

// flash auto-hide
(function(){var a=document.getElementById('flashAlert');if(!a)return;setTimeout(function(){a.style.transition='opacity .4s,transform .4s';a.style.opacity='0';a.style.transform='translateY(-6px)';setTimeout(function(){a.remove();},400);},4000);})();

// auto-submit search after debounce
var searchTimer;
window.autoSubmit=function(){
  clearTimeout(searchTimer);
  searchTimer=setTimeout(function(){
    document.getElementById('filterForm').submit();
  },500);
};

// clear search
window.clearSearch=function(){
  var input=document.querySelector('.toolbar-search input[name="search"]');
  if(input){input.value='';document.getElementById('filterForm').submit();}
};

// lightbox
window.openLightbox=function(src){
  var lb=document.getElementById('lightboxOverlay');
  document.getElementById('lightboxImg').src=src;
  lb.classList.add('open');
  lb.style.display='flex';
};
window.closeLightbox=function(){
  var lb=document.getElementById('lightboxOverlay');
  lb.classList.remove('open');
  lb.style.display='none';
};
document.addEventListener('keydown',function(e){
  if(e.key==='Escape'){closeLightbox();closeModal();closeBulkModal();}
});

// single delete modal
var pendingUrl=null;
window.openModal=function(id,name,url){
  pendingUrl=url;
  document.getElementById('modalProdName').textContent='"'+name+'"';
  document.getElementById('deleteOverlay').classList.add('open');
};
window.closeModal=function(){document.getElementById('deleteOverlay').classList.remove('open');pendingUrl=null;};
window.confirmDelete=function(){if(!pendingUrl)return;var f=document.getElementById('deleteForm');f.action=pendingUrl;f.submit();};
document.getElementById('deleteOverlay').addEventListener('click',function(e){if(e.target===this)closeModal();});

// bulk
var bulkForm=document.getElementById('bulkForm');

window.toggleAll=function(cb){
  document.querySelectorAll('.row-check').forEach(function(c){c.checked=cb.checked;});
  updateBulkBar();
};

window.updateBulkBar=function(){
  var checks=document.querySelectorAll('.row-check:checked');
  var bar=document.getElementById('bulkBar');
  var countEl=document.getElementById('bulkCount');
  var count=checks.length;
  countEl.textContent=count;
  bar.classList.toggle('show',count>0);
};

window.bulkAction=function(action){
  var checks=document.querySelectorAll('.row-check:checked');
  if(!checks.length)return;
  var ids=Array.from(checks).map(function(c){return c.value;});
  var url='';
  if(action==='activate')    url='{{ route("admin.category-products.bulk-toggle") }}';
  else if(action==='deactivate') url='{{ route("admin.category-products.bulk-toggle") }}';
  ids.forEach(function(id){
    var inp=document.createElement('input');
    inp.type='hidden';inp.name='ids[]';inp.value=id;
    bulkForm.appendChild(inp);
  });
  if(action==='activate'||action==='deactivate'){
    var inp=document.createElement('input');
    inp.type='hidden';inp.name='is_active';inp.value=action==='activate'?'1':'0';
    bulkForm.appendChild(inp);
    bulkForm.action=url;
    bulkForm.submit();
  }
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
  var url='{{ route("admin.category-products.bulk-delete") }}';
  checks.forEach(function(c){
    var inp=document.createElement('input');
    inp.type='hidden';inp.name='ids[]';inp.value=c.value;
    bulkForm.appendChild(inp);
  });
  bulkForm.action=url;
  bulkForm.submit();
};

document.getElementById('bulkDeleteOverlay').addEventListener('click',function(e){if(e.target===this)closeBulkModal();});

window.clearAllCheckboxes=function(){
  document.querySelectorAll('.row-check').forEach(function(c){c.checked=false;});
  if(document.getElementById('selectAll'))document.getElementById('selectAll').checked=false;
  updateBulkBar();
};

// prevent Enter key from submitting the form prematurely (use autoSubmit instead)
document.getElementById('filterForm').addEventListener('keypress',function(e){
  if(e.key==='Enter'){e.preventDefault();this.submit();}
});
})();
</script>
@endpush
