@push('page_css')
@vite('resources/css/admin/entries/category-products.css')
@endpush

@extends('layouts.admin')

@section('sidebar_products', 'active')
@section('page_title', 'Category Products')
@section('page_subtitle', 'Manage product categories')

@section('content')
{{-- delete single modal --}}
<div class="overlay" id="deleteOverlay" role="dialog" aria-modal="true">
  <div class="modal">
    <button type="button" class="modal-x" data-action="close-modal"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
    <div class="modal-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg></div>
    <h3>Delete Product?</h3>
    <p>This will permanently remove <strong id="modalProdName"></strong>. This action cannot be undone.</p>
    <div class="modal-acts">
      <button class="btn btn-secondary modal-cancel" data-action="close-modal">Cancel</button>
      <button class="btn btn-red modal-del" data-action="confirm-delete">Yes, Delete</button>
    </div>
  </div>
</div>
<form id="deleteForm" method="POST" style="display:none;">@csrf @method('DELETE')</form>

{{-- delete bulk modal --}}
<div class="overlay" id="bulkDeleteOverlay" role="dialog" aria-modal="true">
  <div class="modal">
    <button type="button" class="modal-x" data-action="close-bulk-modal"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
    <div class="modal-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg></div>
    <h3>Delete Selected Products?</h3>
    <p>This will permanently remove <strong id="bulkCountDisplay">0</strong> product(s). This action cannot be undone.</p>
    <div class="modal-acts">
      <button class="btn btn-secondary modal-cancel" data-action="close-bulk-modal">Cancel</button>
      <button class="btn btn-red modal-del" data-action="confirm-bulk-delete">Yes, Delete</button>
    </div>
  </div>
</div>

{{-- lightbox --}}
<div class="lightbox-overlay" id="lightboxOverlay" data-action="close-lightbox">
  <button class="lightbox-close" data-action="close-lightbox"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
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

<div class="hero">
  <div class="hero-left">
    <div class="hero-tag"><span class="hero-tag-dot"></span>Catalog</div>
    <div class="hero-name">Category Products</div>
    <div class="hero-sub">Manage products that appear under campaign categories across the platform.</div>
    <div class="hero-badges">
      <span class="hero-badge hb-purple">{{ $total }} total</span>
      <span class="hero-badge hb-green">{{ $activeCount }} active</span>
      <span class="hero-badge hb-gray">{{ $inactiveCount }} inactive</span>
    </div>
  </div>
  <div class="hero-right">
    <a href="{{ route('admin.category-products.create') }}" class="hero-btn hero-btn-primary">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
      Add Product
    </a>
  </div>
</div>

<div class="stats-grid">
  <div class="stat">
    <div class="stat-icon si-a"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg></div>
    <div class="stat-body"><div class="stat-lbl">Total Products</div><div class="stat-val sv-a">{{ $total }}</div><div class="stat-foot">All products</div></div>
  </div>
  <div class="stat">
    <div class="stat-icon si-green"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
    <div class="stat-body"><div class="stat-lbl">Active</div><div class="stat-val sv-green">{{ $activeCount }}</div><div class="stat-foot">Visible to public</div></div>
  </div>
  <div class="stat">
    <div class="stat-icon si-red"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg></div>
    <div class="stat-body"><div class="stat-lbl">Inactive</div><div class="stat-val sv-red">{{ $inactiveCount }}</div><div class="stat-foot">Hidden from public</div></div>
  </div>
</div>

{{-- filter / toolbar --}}
<form id="filterForm" method="GET" action="{{ route('admin.category-products.index') }}" style="margin-bottom:0;">
  <div class="toolbar">
    <div class="toolbar-left">
      <div class="search-wrap">
        <svg class="si" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
        <input type="text" class="search-input" name="search" value="{{ $search }}" placeholder="Search products…">
      </div>
      <div class="select-wrap">
        <svg class="si" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a2 2 0 012-2z"/></svg>
        <select class="filter-select" name="category">
          <option value="">All Categories</option>
          @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ (string)$catId === (string)$cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="select-wrap">
        <svg class="si" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
        <select class="filter-select" name="status">
          <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All Status</option>
          <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Active</option>
          <option value="inactive" {{ $status === 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
      </div>
      <input type="hidden" name="sort" value="{{ $sort }}">
      <input type="hidden" name="direction" value="{{ $dir }}">
    </div>
    <div class="toolbar-right">
      <a href="{{ route('admin.category-products.export', request()->only('search', 'category', 'status')) }}" class="export-btn">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>Export CSV
      </a>
    </div>
  </div>
</form>

<div class="main-card">
  <div class="card-head">
    <div class="card-head-left">
      <div class="card-head-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg></div>
      <span class="card-head-title">All Products</span>
    </div>
    <span class="card-head-count" id="visibleCount">{{ $total }} total</span>
  </div>

  {{-- bulk action bar --}}
  <div class="bulk-bar" id="bulkBar">
    <span><strong class="bulk-count" id="bulkCount">0</strong> selected</span>
    <div class="bulk-acts">
      <button type="button" class="bulk-btn bulk-activate" data-action="bulk-action" data-status="activate">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4"/></svg>Activate
      </button>
      <button type="button" class="bulk-btn bulk-deactivate" data-action="bulk-action" data-status="deactivate">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>Deactivate
      </button>
      <button type="button" class="btn btn-red bulk-btn bulk-del" data-action="open-bulk-delete">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/></svg>Delete
      </button>
      <button type="button" class="btn btn-secondary bulk-btn bulk-cancel" data-action="clear-checkboxes">Cancel</button>
    </div>
  </div>

  <form id="bulkForm" method="POST" style="display:none;" data-toggle-url="{{ route('admin.category-products.bulk-toggle') }}" data-delete-url="{{ route('admin.category-products.bulk-delete') }}">@csrf</form>

  @if($products->isEmpty())
  <div class="empty-state">
    <div class="empty-icon-wrap">📦</div>
    <h3>No products found</h3>
    @if($search || $catId || $status !== 'all')
      <p>Try adjusting your search or filters</p>
    @else
      <p>Add your first category product to get started</p>
      <a href="{{ route('admin.category-products.create') }}" class="add-btn" style="margin:0 auto;">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>Add Product
      </a>
    @endif
  </div>
  @else
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th class="td-check"><input type="checkbox" id="selectAll" data-action="toggle-all"></th>
          <th style="width:50px;">#</th>
          <th style="width:70px;">Image</th>
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
          <th>Actions</th>
        </tr>
      </thead>
      <tbody id="tableBody">
        @forelse($products as $product)
        <tr>
          <td class="td-check"><input type="checkbox" class="row-check" value="{{ $product->id }}" data-action="update-bulk-bar"></td>
          <td><span class="serial">{{ $products->firstItem() + $loop->index }}</span></td>
          <td>
            @if($product->image)
              <img src="{{ asset('storage/'.$product->image) }}" class="prod-img" alt="{{ $product->name }}" data-action="open-lightbox" data-src="{{ asset('storage/'.$product->image) }}" style="cursor:pointer;">
            @else
              <div class="prod-placeholder"><i class="fa fa-box"></i></div>
            @endif
          </td>
          <td>
            <div class="prod-name">{{ $product->name }}</div>
            <div class="prod-desc">{{ $product->description }}</div>
          </td>
          <td>
            <span style="font-size:12.5px;color:var(--text2);font-weight:500;">{{ $product->category->name??'—' }}</span>
          </td>
          <td><span class="type-pill">{{ ucfirst($product->product_type) }}</span></td>
          <td><span class="price-val">₹{{ number_format($product->price,2) }}</span></td>
          <td>
            <span class="stock-val {{ $product->stock==0?'stock-out':($product->stock<10?'stock-low':'') }}">
              {{ $product->stock }}
            </span>
          </td>
          <td>
            @if($product->is_active)
              <span class="status-pill s-active"><span class="status-dot"></span> Active</span>
            @else
              <span class="status-pill s-inactive"><span class="status-dot"></span> Inactive</span>
            @endif
          </td>
          <td data-label="Actions">
            <div class="actions">
              <a href="{{ route('admin.category-products.edit', $product) }}" class="btn btn-secondary act-btn ab-edit">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path stroke-linecap="round" stroke-linejoin="round" d="M18.5 2.5a2.12 2.12 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                Edit
              </a>
              <form method="POST" action="{{ route('admin.category-products.destroy', $product) }}" style="display:inline;" data-confirm="Delete '{{ $product->name }}'?">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-red act-btn ab-delete" title="Delete">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/></svg>Delete
                </button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="10">
          <div class="empty-state"><div class="empty-icon-wrap">📦</div><h3>No products found</h3><p>Try adjusting your search or filters</p></div>
        </td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if($products instanceof \Illuminate\Pagination\LengthAwarePaginator && $products->hasPages())
  <div class="pagination-wrap">
    <span class="page-info">Showing {{ $products->firstItem() }}–{{ $products->lastItem() }} of {{ $products->total() }}</span>
    <div class="page-btns">
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
@vite('resources/js/admin/entries/category-products-index.js')
@endpush

