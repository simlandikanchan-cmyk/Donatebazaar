@extends('layouts.admin')

@section('sidebar_category_products', 'active')
@section('page_title', 'Category Products')
@section('page_subtitle', 'Manage product categories')

@push('page_styles')
<style>
/* ── TOOLBAR ── */
.toolbar{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:16px;flex-wrap:wrap;}
.toolbar-left{display:flex;align-items:center;gap:8px;flex-wrap:wrap;}
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
.cnt-badge{background:var(--a);color:#fff;font-size:9.5px;font-weight:700;padding:1px 5px;border-radius:100px;font-family:var(--mono);}

/* ── MAIN CARD ── */
.main-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh);overflow:hidden;animation:fadeUp .4s .15s ease both;}
.card-head{padding:14px 20px;border-bottom:1px solid var(--border);background:var(--surface2);display:flex;align-items:center;justify-content:space-between;}
.card-head-left{display:flex;align-items:center;gap:10px;}
.card-head-icon{width:30px;height:30px;border-radius:8px;background:var(--a-lt);color:var(--a);display:flex;align-items:center;justify-content:center;}
.card-head-icon svg{width:14px;height:14px;}
.card-head-title{font-size:12px;font-weight:600;color:var(--text2);text-transform:uppercase;letter-spacing:.09em;font-family:var(--mono);}
.card-head-count{font-size:10.5px;color:var(--text3);font-family:var(--mono);background:var(--surface);border:1px solid var(--border2);padding:2px 8px;border-radius:100px;}

/* ── TABLE ── */
.table-wrap{overflow-x:auto;}
table{width:100%;border-collapse:collapse;}
thead th{padding:10px 18px;text-align:left;font-size:10px;font-family:var(--mono);letter-spacing:.12em;text-transform:uppercase;color:var(--text3);background:var(--surface2);border-bottom:1px solid var(--border);font-weight:500;white-space:nowrap;}
thead th:last-child{text-align:right;}
tbody tr{border-bottom:1px solid var(--border);transition:background var(--ease);}
tbody tr:last-child{border-bottom:none;}
tbody tr:hover{background:var(--surface2);}
td{padding:13px 18px;font-size:13px;vertical-align:middle;}
.serial{font-size:11.5px;color:var(--text3);font-family:var(--mono);}
.prod-img{width:52px;height:52px;border-radius:12px;object-fit:cover;border:1px solid var(--border);}
.prod-placeholder{width:52px;height:52px;border-radius:12px;background:var(--a-lt);display:flex;align-items:center;justify-content:center;color:var(--a);font-size:18px;flex-shrink:0;border:1px solid rgba(110,86,247,.15);}
.prod-name{font-weight:600;color:var(--text);font-size:13.5px;margin-bottom:2px;}
.prod-desc{font-size:11.5px;color:var(--text3);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:200px;}
.type-pill{display:inline-flex;align-items:center;height:22px;padding:0 9px;border-radius:100px;font-size:10.5px;font-weight:600;font-family:var(--mono);text-transform:uppercase;letter-spacing:.04em;background:var(--blue-lt);color:var(--blue);border:1px solid rgba(59,130,246,.18);}
.price-val{font-family:var(--mono);font-size:13px;font-weight:700;color:var(--text);}
.stock-val{font-family:var(--mono);font-size:12.5px;color:var(--text2);}
.stock-low{color:var(--amber);}
.stock-out{color:var(--red);}
.status-pill{display:inline-flex;align-items:center;gap:5px;padding:4px 10px;border-radius:100px;font-size:10.5px;font-weight:600;font-family:var(--mono);text-transform:uppercase;letter-spacing:.05em;}
.s-active{background:rgba(5,196,138,.12);color:var(--green);border:1px solid rgba(5,196,138,.22);}
.s-inactive{background:rgba(100,116,139,.08);color:var(--text3);border:1px solid var(--border2);}
.status-dot{width:5px;height:5px;border-radius:50%;background:currentColor;display:inline-block;}
.actions{display:flex;align-items:center;justify-content:flex-end;gap:5px;}
.act-btn{display:inline-flex;align-items:center;gap:4px;padding:5px 11px;border-radius:7px;font-size:11.5px;font-weight:500;text-decoration:none;border:1px solid transparent;transition:all .15s;cursor:pointer;font-family:var(--font);white-space:nowrap;}
.act-btn svg{width:11px;height:11px;}
.act-edit{background:var(--blue-lt);color:var(--blue);border-color:rgba(59,130,246,.18);}
.act-edit:hover{background:var(--blue);color:#fff;transform:translateY(-1px);}
.act-del{background:var(--red-lt);color:var(--red);border-color:rgba(240,68,68,.18);}
.act-del:hover{background:var(--red);color:#fff;transform:translateY(-1px);}

/* ── EMPTY STATE ── */
.empty-state{padding:72px 24px;text-align:center;}
.empty-icon-wrap{width:72px;height:72px;border-radius:20px;background:var(--surface2);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;font-size:26px;margin:0 auto 18px;animation:float 3s ease-in-out infinite;}
.empty-state h3{font-size:16px;font-weight:700;color:var(--text);margin-bottom:6px;}
.empty-state p{font-size:13px;color:var(--text3);margin-bottom:20px;}

/* ── ADD BUTTON ── */
.add-btn{display:inline-flex;align-items:center;gap:7px;height:36px;padding:0 16px;background:var(--a);color:#fff;border:none;border-radius:var(--r-sm);font-size:12.5px;font-weight:600;text-decoration:none;cursor:pointer;transition:opacity .2s,transform .15s;font-family:var(--font);box-shadow:0 4px 14px rgba(110,86,247,.3);}
.add-btn:hover{opacity:.88;transform:translateY(-1px);}
.add-btn svg{width:13px;height:13px;}

/* ── ALERT ── */
.alert-ok{background:rgba(5,196,138,.08);border:1px solid rgba(5,196,138,.22);color:#065f46;padding:12px 16px;border-radius:var(--r-sm);font-size:13px;margin-bottom:16px;display:flex;align-items:center;gap:10px;animation:fadeUp .3s ease;}
[data-theme="dark"] .alert-ok{color:#6ee7b7;}
.alert-ok svg{width:15px;height:15px;flex-shrink:0;}

/* ── OVERLAY / MODAL ── */
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

/* ── TOAST ── */
.toast-wrap{position:fixed;top:20px;right:20px;z-index:9999;display:flex;flex-direction:column;gap:8px;pointer-events:none;}
.toast{display:flex;align-items:center;gap:10px;padding:13px 16px;border-radius:14px;font-size:13px;font-weight:500;color:#fff;min-width:270px;box-shadow:var(--sh-lg);pointer-events:all;animation:toastIn .3s ease both;}
.toast svg{width:15px;height:15px;flex-shrink:0;}
.toast-ok{background:linear-gradient(135deg,#059669,#10b981);}
.toast-err{background:linear-gradient(135deg,#dc2626,#f04444);}

@keyframes fadeUp{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:none}}
@keyframes float{0%,100%{transform:translateY(0)}50%{transform:translateY(-6px)}}
@keyframes toastIn{from{opacity:0;transform:translateX(18px) scale(.96)}to{opacity:1;transform:none}}

@media(max-width:600px){.search-input{width:160px;}}
</style>
@endpush

@section('content')
<div class="overlay" id="deleteOverlay" role="dialog" aria-modal="true">
  <div class="modal">
    <button type="button" class="modal-x" onclick="closeModal()"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
    <div class="modal-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg></div>
    <h3>Delete Product?</h3>
    <p>This will permanently remove <strong id="modalProdName"></strong>. This action cannot be undone.</p>
    <div class="modal-acts">
      <button class="modal-cancel" onclick="closeModal()">Cancel</button>
      <button class="modal-del" onclick="confirmDelete()">Yes, Delete</button>
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
  $total=$products->total()??$products->count();
  $active=$products->where('is_active',1)->count();
  $inactive=$total-$active;

  $categoryOptions = isset($categories)
      ? $categories
      : $products->pluck('category')->filter()->unique('id')->sortBy('name')->values();
@endphp

<div class="stats-grid">
  <div class="stat">
    <div class="stat-icon si-a"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg></div>
    <div class="stat-body"><div class="stat-lbl">Total Products</div><div class="stat-val sv-a">{{ $total }}</div><div class="stat-foot">All products</div></div>
  </div>
  <div class="stat">
    <div class="stat-icon si-green"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
    <div class="stat-body"><div class="stat-lbl">Active</div><div class="stat-val sv-green">{{ $active }}</div><div class="stat-foot">Visible to public</div></div>
  </div>
  <div class="stat">
    <div class="stat-icon si-red"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg></div>
    <div class="stat-body"><div class="stat-lbl">Inactive</div><div class="stat-val sv-red">{{ $inactive }}</div><div class="stat-foot">Hidden from public</div></div>
  </div>
</div>

<div class="toolbar">
  <div class="toolbar-left">
    <div class="search-wrap">
      <svg class="si" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
      <input type="text" class="search-input" id="searchInput" placeholder="Search products…" oninput="filterRows()">
    </div>
    <div class="select-wrap">
      <svg class="si" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a2 2 0 012-2z"/></svg>
      <select class="filter-select" id="categorySelect" onchange="onCategoryChange(this)">
        <option value="">All Categories</option>
        @foreach($categoryOptions as $cat)
          <option value="{{ $cat->id }}">{{ $cat->name }}</option>
        @endforeach
      </select>
    </div>
    <button class="filter-btn on" id="fAll" onclick="setFilter('all',this)">All <span class="cnt-badge">{{ $total }}</span></button>
    <button class="filter-btn" id="fActive" onclick="setFilter('active',this)">
      <span style="width:6px;height:6px;border-radius:50%;background:var(--green);display:inline-block;"></span>Active
    </button>
    <button class="filter-btn" id="fInactive" onclick="setFilter('inactive',this)">Inactive</button>
  </div>
</div>

<div class="main-card">
  <div class="card-head">
    <div class="card-head-left">
      <div class="card-head-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg></div>
      <span class="card-head-title">All Products</span>
    </div>
    <span class="card-head-count" id="visibleCount">{{ $total }} total</span>
  </div>

  @if($products->isEmpty())
  <div class="empty-state">
    <div class="empty-icon-wrap">📦</div>
    <h3>No products yet</h3>
    <p>Add your first category product to get started</p>
    <a href="{{ route('admin.category-products.create') }}" class="add-btn" style="margin:0 auto;">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>Add Product
    </a>
  </div>
  @else
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th style="width:50px;">#</th>
          <th style="width:70px;">Image</th>
          <th>Product</th>
          <th>Category</th>
          <th>Type</th>
          <th>Price</th>
          <th>Stock</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody id="tableBody">
        @forelse($products as $product)
        <tr class="prod-row"
          data-name="{{ strtolower($product->name) }}"
          data-status="{{ $product->is_active?'active':'inactive' }}"
          data-category="{{ $product->category_id ?? '' }}"
          style="animation:fadeUp 0.35s {{ $loop->index*0.04 }}s ease both;opacity:0;animation-fill-mode:both;">
          <td><span class="serial">{{ str_pad($loop->iteration,2,'0',STR_PAD_LEFT) }}</span></td>
          <td>
            @if($product->image)
              <img src="{{ asset('storage/'.$product->image) }}" class="prod-img" alt="{{ $product->name }}">
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
          <td>
            <div class="actions">
              <a href="{{ route('admin.category-products.edit',$product->id) }}" class="act-btn act-edit">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>Edit
              </a>
              <button type="button" class="act-btn act-del"
                onclick="openModal('{{ $product->id }}','{{ addslashes($product->name) }}','{{ route('admin.category-products.destroy',$product->id) }}')">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/></svg>Delete
              </button>
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="9">
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
        <a href="{{ $products->previousPageUrl() }}" class="page-btn"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg></a>
      @endif
      @foreach($products->getUrlRange(1,$products->lastPage()) as $page=>$url)
        <a href="{{ $url }}" class="page-btn {{ $products->currentPage()==$page?'cur':'' }}">{{ $page }}</a>
      @endforeach
      @if($products->hasMorePages())
        <a href="{{ $products->nextPageUrl() }}" class="page-btn"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg></a>
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

var activeFilter='all';
window.setFilter=function(f,btn){
  activeFilter=f;
  document.querySelectorAll('.filter-btn').forEach(function(b){b.classList.remove('on');});
  if(btn)btn.classList.add('on');
  filterRows();
};

window.onCategoryChange=function(sel){
  sel.classList.toggle('on', sel.value !== '');
  filterRows();
};

window.filterRows=function(){
  var q=document.getElementById('searchInput').value.toLowerCase().trim();
  var cat=document.getElementById('categorySelect').value;
  var rows=document.querySelectorAll('.prod-row');
  var visible=0;
  rows.forEach(function(r){
    var matchesSearch   = !q || (r.getAttribute('data-name')||'').includes(q);
    var matchesStatus   = activeFilter==='all' || r.getAttribute('data-status')===activeFilter;
    var matchesCategory = !cat || r.getAttribute('data-category')===cat;
    var ok = matchesSearch && matchesStatus && matchesCategory;
    r.style.display=ok?'':'none';
    if(ok)visible++;
  });
  document.getElementById('visibleCount').textContent=visible+' total';
};

var pendingUrl=null;
window.openModal=function(id,name,url){
  pendingUrl=url;
  document.getElementById('modalProdName').textContent='"'+name+'"';
  document.getElementById('deleteOverlay').classList.add('open');
};
window.closeModal=function(){document.getElementById('deleteOverlay').classList.remove('open');pendingUrl=null;};
window.confirmDelete=function(){if(!pendingUrl)return;var f=document.getElementById('deleteForm');f.action=pendingUrl;f.submit();};
document.getElementById('deleteOverlay').addEventListener('click',function(e){if(e.target===this)closeModal();});
document.addEventListener('keydown',function(e){if(e.key==='Escape')closeModal();});
})();
</script>
@endpush
