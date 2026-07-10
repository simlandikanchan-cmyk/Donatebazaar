@extends('layouts.admin')

@section('sidebar_products', 'active')
@section('page_title', 'Category Products')
@section('page_subtitle', 'Manage product categories')

@push('page_styles')
<style>
/* ── TOOLBAR ── */
.toolbar{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:16px;flex-wrap:wrap;}
.toolbar-left{display:flex;align-items:center;gap:8px;flex-wrap:wrap;}
.toolbar-right{display:flex;align-items:center;gap:8px;}
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
.filter-btn.on{background:var(--a);color:#fff;border-color:var(--a);}
.cnt-badge{background:var(--a);color:#fff;font-size:9.5px;font-weight:700;padding:1px 5px;border-radius:100px;font-family:var(--mono);}

/* ── EXPORT BTN ── */
.export-btn{display:inline-flex;align-items:center;gap:6px;height:36px;padding:0 14px;background:var(--surface);border:1px solid var(--border2);border-radius:var(--r-sm);font-size:12px;font-weight:500;color:var(--text2);text-decoration:none;cursor:pointer;font-family:var(--font);transition:all var(--ease);}
.export-btn:hover{border-color:var(--green);color:var(--green);background:rgba(5,196,138,.06);}
.export-btn svg{width:13px;height:13px;}

/* ── MAIN CARD ── */
.main-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh);overflow:hidden;animation:fadeUp .4s .15s ease both;}
.card-head{padding:14px 20px;border-bottom:1px solid var(--border);background:var(--surface2);display:flex;align-items:center;justify-content:space-between;}
.card-head-left{display:flex;align-items:center;gap:10px;}
.card-head-icon{width:30px;height:30px;border-radius:8px;background:var(--a-lt);color:var(--a);display:flex;align-items:center;justify-content:center;}
.card-head-icon svg{width:14px;height:14px;}
.card-head-title{font-size:12px;font-weight:600;color:var(--text2);text-transform:uppercase;letter-spacing:.09em;font-family:var(--mono);}
.card-head-count{font-size:10.5px;color:var(--text3);font-family:var(--mono);}

/* ── TABLE ── */
.table-wrap{overflow-x:auto;}
table{width:100%;border-collapse:collapse;}
thead th{padding:10px 18px;text-align:left;font-size:10px;font-family:var(--mono);letter-spacing:.12em;text-transform:uppercase;color:var(--text3);background:var(--surface2);border-bottom:1px solid var(--border);font-weight:500;white-space:nowrap;}
thead th:last-child{text-align:right;}
.sort-link{display:inline-flex;align-items:center;gap:4px;color:var(--text3);cursor:pointer;text-decoration:none;transition:color var(--ease);user-select:none;}
.sort-link:hover{color:var(--a);}
.sort-link .sort-ico{width:10px;height:10px;opacity:.5;}
.sort-link .sort-ico.asc{opacity:1;color:var(--a);}
.sort-link .sort-ico.desc{opacity:1;color:var(--a);transform:rotate(180deg);}
tbody tr{border-bottom:1px solid var(--border);transition:background var(--ease);}
tbody tr:last-child{border-bottom:none;}
tbody tr:hover{background:var(--surface2);}
td{padding:13px 18px;font-size:13px;vertical-align:middle;}
.serial{font-size:11.5px;color:var(--text3);font-family:var(--mono);}
.prod-img{width:52px;height:52px;border-radius:12px;object-fit:cover;border:1px solid var(--border);cursor:pointer;transition:opacity .2s;}
.prod-img:hover{opacity:.8;}
.prod-placeholder{width:52px;height:52px;border-radius:12px;background:var(--a-lt);display:flex;align-items:center;justify-content:center;color:var(--a);font-size:18px;flex-shrink:0;border:1px solid rgba(37,99,235,.15);}
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
.td-check{width:40px;text-align:center;}
.td-check input[type="checkbox"]{width:15px;height:15px;cursor:pointer;accent-color:var(--a);}

/* ── BULK BAR ── */
.bulk-bar{display:none;align-items:center;gap:10px;padding:10px 18px;background:var(--a-lt);border-bottom:1px solid var(--border);font-size:12.5px;color:var(--a);font-weight:500;animation:fadeUp .25s ease;}
.bulk-bar.show{display:flex;}
.bulk-bar .bulk-count{font-family:var(--mono);}
.bulk-bar .bulk-acts{display:flex;gap:6px;margin-left:auto;}
.bulk-btn{display:inline-flex;align-items:center;gap:5px;height:30px;padding:0 12px;border-radius:6px;font-size:11px;font-weight:600;font-family:var(--font);border:none;cursor:pointer;transition:all .15s;text-decoration:none;}
.bulk-btn svg{width:11px;height:11px;}
.bulk-activate{background:var(--green);color:#fff;}
.bulk-activate:hover{opacity:.85;}
.bulk-deactivate{background:var(--text3);color:#fff;}
.bulk-deactivate:hover{opacity:.85;}
.bulk-del{background:var(--red);color:#fff;}
.bulk-del:hover{opacity:.85;}
.bulk-cancel{background:transparent;border:1px solid var(--border2);color:var(--text2);padding:0 10px;}
.bulk-cancel:hover{background:var(--surface2);}

/* ── EMPTY STATE ── */
.empty-state{padding:72px 24px;text-align:center;}
.empty-icon-wrap{width:72px;height:72px;border-radius:20px;background:var(--surface2);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;font-size:26px;margin:0 auto 18px;animation:float 3s ease-in-out infinite;}
.empty-state h3{font-size:16px;font-weight:700;color:var(--text);margin-bottom:6px;}
.empty-state p{font-size:13px;color:var(--text3);margin-bottom:20px;}

/* ── ADD BUTTON ── */
.add-btn{display:inline-flex;align-items:center;gap:7px;height:36px;padding:0 16px;background:var(--a);color:#fff;border:none;border-radius:var(--r-sm);font-size:12.5px;font-weight:600;text-decoration:none;cursor:pointer;transition:opacity .2s,transform .15s;font-family:var(--font);box-shadow:0 4px 14px rgba(37,99,235,.3);}
.add-btn:hover{opacity:.88;transform:translateY(-1px);}
.add-btn svg{width:13px;height:13px;}

/* ── ALERT ── */
.alert-ok{background:rgba(5,196,138,.08);border:1px solid rgba(5,196,138,.22);color:#065f46;padding:12px 16px;border-radius:var(--r-sm);font-size:13px;margin-bottom:16px;display:flex;align-items:center;gap:10px;animation:fadeUp .3s ease;}
[data-theme="dark"] .alert-ok{color:#189d68;}
.alert-ok svg{width:15px;height:15px;flex-shrink:0;}

/* ── STATS ── */
.stats-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:24px;}
.stat{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);padding:18px 20px;position:relative;overflow:hidden;animation:fadeUp .4s ease both;display:flex;align-items:center;gap:16px;}
.stat::after{content:'';position:absolute;top:0;left:0;right:0;height:3px;opacity:.6;}
.stat:nth-child(1){animation-delay:.05s;}.stat:nth-child(1)::after{background:linear-gradient(90deg,var(--a),var(--a2));}
.stat:nth-child(2){animation-delay:.10s;}.stat:nth-child(2)::after{background:linear-gradient(90deg,var(--green),#34d399);}
.stat:nth-child(3){animation-delay:.15s;}.stat:nth-child(3)::after{background:linear-gradient(90deg,var(--text3),#64748b);}
.stat-icon{width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.stat-icon svg{width:18px;height:18px;}
.si-a{background:var(--a-lt);color:var(--a);}
.si-green{background:rgba(5,196,138,.10);color:var(--green);}
.si-red{background:rgba(240,68,68,.08);color:var(--red);}
.stat-body{flex:1;min-width:0;}
.stat-lbl{font-size:11px;font-family:var(--mono);text-transform:uppercase;letter-spacing:.08em;color:var(--text3);margin-bottom:3px;}
.stat-val{font-size:22px;font-weight:800;font-family:var(--mono);letter-spacing:-.03em;line-height:1.1;margin-bottom:2px;}
.sv-a{color:var(--a);}
.sv-green{color:var(--green);}
.sv-red{color:var(--red);}
.stat-foot{font-size:11px;color:var(--text3);}

@media(max-width:860px){.stats-grid{grid-template-columns:repeat(2,1fr);}}
@media(max-width:500px){.stats-grid{grid-template-columns:1fr 1fr;}}

/* ── PAGINATION ── */
.pagination-wrap{display:flex;align-items:center;justify-content:space-between;padding:14px 20px;border-top:1px solid var(--border);background:var(--surface2);flex-wrap:wrap;gap:10px;}
.page-info{font-size:12px;color:var(--text3);font-family:var(--mono);}
.page-btns{display:flex;gap:4px;}
.page-btn{width:30px;height:30px;border-radius:7px;display:flex;align-items:center;justify-content:center;font-size:11.5px;font-weight:600;border:1px solid var(--border2);background:var(--surface);color:var(--text2);cursor:pointer;transition:all .15s;font-family:var(--mono);text-decoration:none;}
.page-btn:hover{border-color:var(--a);color:var(--a);background:var(--a-lt);}
.page-btn.cur{background:var(--a);border-color:var(--a);color:#fff;}
.page-btn svg{width:12px;height:12px;}

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

/* ── LIGHTBOX ── */
.lightbox-overlay{display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,.8);align-items:center;justify-content:center;padding:20px;cursor:zoom-out;backdrop-filter:blur(8px);}
.lightbox-overlay.open{display:flex;}
.lightbox-overlay img{max-width:90vw;max-height:90vh;border-radius:12px;box-shadow:0 20px 60px rgba(0,0,0,.5);object-fit:contain;animation:lightboxIn .25s ease;}
@keyframes lightboxIn{from{opacity:0;transform:scale(.92)}to{opacity:1;transform:none}}
.lightbox-close{position:absolute;top:20px;right:20px;width:32px;height:32px;border-radius:50%;background:rgba(255,255,255,.15);border:none;color:#fff;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:background .2s;}
.lightbox-close:hover{background:rgba(255,255,255,.3);}
.lightbox-close svg{width:16px;height:16px;}

/* ── TOAST ── */
.toast-wrap{position:fixed;top:20px;right:20px;z-index:9999;display:flex;flex-direction:column;gap:8px;pointer-events:none;}
.toast{display:flex;align-items:center;gap:10px;padding:13px 16px;border-radius:14px;font-size:13px;font-weight:500;color:#fff;min-width:270px;box-shadow:var(--sh-lg);pointer-events:all;animation:toastIn .3s ease both;}
.toast svg{width:15px;height:15px;flex-shrink:0;}
.toast-ok{background:linear-gradient(135deg,#059669,#10b981);}
.toast-err{background:linear-gradient(135deg,#dc2626,#f04444);}

@keyframes fadeUp{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:none}}
@keyframes float{0%,100%{transform:translateY(0)}50%{transform:translateY(-6px)}}
@keyframes toastIn{from{opacity:0;transform:translateX(18px) scale(.96)}to{opacity:1;transform:none}}

@media(max-width:600px){.search-input{width:160px;}.search-input:focus{width:180px;}}
</style>
@endpush

@section('content')
{{-- delete single modal --}}
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

{{-- delete bulk modal --}}
<div class="overlay" id="bulkDeleteOverlay" role="dialog" aria-modal="true">
  <div class="modal">
    <button type="button" class="modal-x" onclick="closeBulkModal()"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
    <div class="modal-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg></div>
    <h3>Delete Selected Products?</h3>
    <p>This will permanently remove <strong id="bulkCountDisplay">0</strong> product(s). This action cannot be undone.</p>
    <div class="modal-acts">
      <button class="modal-cancel" onclick="closeBulkModal()">Cancel</button>
      <button class="modal-del" onclick="confirmBulkDelete()">Yes, Delete</button>
    </div>
  </div>
</div>

{{-- lightbox --}}
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
        <input type="text" class="search-input" name="search" value="{{ $search }}" placeholder="Search products…" oninput="autoSubmit()">
      </div>
      <div class="select-wrap">
        <svg class="si" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a2 2 0 012-2z"/></svg>
        <select class="filter-select" name="category" onchange="this.form.submit()">
          <option value="">All Categories</option>
          @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ (string)$catId === (string)$cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="select-wrap">
        <svg class="si" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
        <select class="filter-select" name="status" onchange="this.form.submit()">
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
      <a href="{{ route('admin.category-products.create') }}" class="add-btn">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>Add Product
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
      <button type="button" class="bulk-btn bulk-activate" onclick="bulkAction('activate')">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4"/></svg>Activate
      </button>
      <button type="button" class="bulk-btn bulk-deactivate" onclick="bulkAction('deactivate')">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>Deactivate
      </button>
      <button type="button" class="bulk-btn bulk-del" onclick="openBulkDelete()">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/></svg>Delete
      </button>
      <button type="button" class="bulk-btn bulk-cancel" onclick="clearAllCheckboxes()">Cancel</button>
    </div>
  </div>

  <form id="bulkForm" method="POST" style="display:none;">@csrf</form>

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
          <th class="td-check"><input type="checkbox" id="selectAll" onchange="toggleAll(this)"></th>
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
          <td class="td-check"><input type="checkbox" class="row-check" value="{{ $product->id }}" onchange="updateBulkBar()"></td>
          <td><span class="serial">{{ $products->firstItem() + $loop->index }}</span></td>
          <td>
            @if($product->image)
              <img src="{{ asset('storage/'.$product->image) }}" class="prod-img" alt="{{ $product->name }}" onclick="openLightbox(this.src)" style="cursor:pointer;">
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
