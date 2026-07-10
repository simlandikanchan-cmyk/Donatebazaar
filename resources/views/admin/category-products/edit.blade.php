@extends('layouts.admin')

@section('sidebar_category_products', 'active')
@section('page_title', 'Edit Category Product')
@section('page_subtitle', 'Modify product category')

@push('page_styles')
<style>
/* ── BREADCRUMB ── */
.breadcrumb{display:flex;align-items:center;gap:6px;font-size:12px;color:var(--text3);font-family:var(--mono);margin-bottom:22px;animation:fadeUp .3s ease both;}
.breadcrumb a{color:var(--text3);transition:color var(--ease);}
.breadcrumb a:hover{color:var(--a);}
.breadcrumb svg{width:12px;height:12px;flex-shrink:0;}
.breadcrumb span{color:var(--text2);font-weight:600;}

/* ── FORM LAYOUT ── */
.form-layout{display:grid;grid-template-columns:1fr 300px;gap:20px;align-items:start;}

/* ── CARDS ── */
.card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);padding:26px;box-shadow:var(--sh);animation:fadeUp .4s ease both;}
.card+.card{margin-top:16px;}
.card-hdr{display:flex;align-items:center;gap:12px;margin-bottom:22px;padding-bottom:18px;border-bottom:1px solid var(--border);}
.card-ico{width:38px;height:38px;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.card-ico svg{width:17px;height:17px;}
.ci-purple{background:var(--a-lt);color:var(--a);}
.ci-green{background:var(--green-lt);color:var(--green);}
.ci-blue{background:var(--blue-lt);color:var(--blue);}
.ci-amber{background:var(--amber-lt);color:var(--amber);}
.card-ttl{font-family:var(--mono);font-size:14.5px;font-weight:700;color:var(--text);letter-spacing:-.01em;}
.card-sub{font-size:11px;color:var(--text3);margin-top:2px;font-family:var(--mono);}

/* ── FIELDS ── */
.field{margin-bottom:20px;}
.field:last-child{margin-bottom:0;}
.field-row{display:grid;grid-template-columns:1fr 1fr;gap:16px;}
label.lbl{display:block;font-size:12px;font-weight:600;color:var(--text2);margin-bottom:8px;font-family:var(--mono);letter-spacing:.04em;text-transform:uppercase;}
label.lbl .req{color:var(--red);margin-left:2px;}
.inp,.sel,.ta{width:100%;padding:11px 14px;border:1px solid var(--border2);border-radius:var(--r-sm);font-size:13.5px;color:var(--text);font-family:var(--font);background:var(--surface2);outline:none;transition:border-color var(--ease),box-shadow var(--ease),background var(--ease);line-height:1.5;}
.inp:focus,.sel:focus,.ta:focus{border-color:var(--a);box-shadow:0 0 0 3px var(--a-glow);background:var(--surface);}
.inp::placeholder,.ta::placeholder{color:var(--text3);}
.sel{cursor:pointer;appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%239096b4' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 12px center;background-size:14px;padding-right:38px;}
.ta{resize:vertical;min-height:100px;}
.inp.err,.sel.err,.ta.err{border-color:var(--red);box-shadow:0 0 0 3px rgba(240,68,68,.12);}
.inp-wrap{position:relative;}
.inp-wrap .inp-icon{position:absolute;left:13px;top:50%;transform:translateY(-50%);width:15px;height:15px;color:var(--text3);pointer-events:none;}
.inp-wrap .inp{padding-left:40px;}
.field-hint{font-size:11px;color:var(--text3);margin-top:6px;font-family:var(--mono);line-height:1.5;}
.field-error{font-size:11.5px;color:var(--red);margin-top:6px;font-family:var(--mono);font-weight:600;}

/* ── PRICE WRAP ── */
.price-wrap{position:relative;}
.price-wrap .inp{padding-left:36px;}
.price-symbol{position:absolute;left:13px;top:50%;transform:translateY(-50%);font-size:14px;font-weight:700;color:var(--text3);pointer-events:none;font-family:var(--mono);}

/* ── TOGGLE ── */
.toggle-row{display:flex;align-items:center;justify-content:space-between;padding:14px 16px;background:var(--surface2);border:1px solid var(--border2);border-radius:var(--r-sm);gap:14px;}
.toggle-row-title{font-size:13.5px;font-weight:600;color:var(--text);}
.toggle-row-sub{font-size:11px;color:var(--text3);margin-top:2px;font-family:var(--mono);}
.toggle-switch{position:relative;flex-shrink:0;}
.toggle-switch input{position:absolute;opacity:0;width:0;height:0;}
.toggle-switch label{display:block;width:44px;height:24px;border-radius:100px;background:var(--border2);cursor:pointer;position:relative;transition:background .2s;}
.toggle-switch label::after{content:'';position:absolute;top:3px;left:3px;width:18px;height:18px;border-radius:50%;background:#fff;transition:transform .2s cubic-bezier(.4,0,.2,1);box-shadow:0 1px 4px rgba(0,0,0,.18);}
.toggle-switch input:checked+label{background:var(--green);}
.toggle-switch input:checked+label::after{transform:translateX(20px);}
.toggle-row.active-toggle{background:var(--green-lt);border-color:rgba(5,196,138,.25);}

/* ── UPLOAD ZONE ── */
.upload-zone{border:2px dashed var(--border2);border-radius:var(--r-sm);padding:28px 20px;text-align:center;cursor:pointer;transition:all var(--ease);background:var(--surface2);}
.upload-zone:hover,.upload-zone.drag-over{border-color:var(--a);background:var(--a-lt);}
.upload-zone input[type="file"]{display:none;}
.upload-icon{width:44px;height:44px;border-radius:12px;background:var(--a-lt);color:var(--a);display:flex;align-items:center;justify-content:center;margin:0 auto 10px;}
.upload-icon svg{width:20px;height:20px;}
.upload-title{font-size:13.5px;font-weight:600;color:var(--text);margin-bottom:4px;}
.upload-sub{font-size:11.5px;color:var(--text3);font-family:var(--mono);}
.img-preview-wrap{position:relative;display:inline-block;}
.img-preview{width:100%;max-height:180px;object-fit:cover;border-radius:var(--r-sm);border:1px solid var(--border);display:block;}
.img-remove{position:absolute;top:8px;right:8px;width:26px;height:26px;border-radius:7px;background:rgba(240,68,68,.9);border:none;color:#fff;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:opacity var(--ease);}
.img-remove:hover{opacity:.8;}
.img-remove svg{width:11px;height:11px;}
.img-label{font-size:10.5px;color:var(--text3);font-family:var(--mono);margin-top:6px;text-align:center;}

/* ── SUBMIT ROW ── */
.submit-row{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-top:20px;padding-top:20px;border-top:1px solid var(--border);flex-wrap:wrap;}
.submit-info{font-size:12px;color:var(--text3);font-family:var(--mono);}
.submit-info .req{color:var(--red);}
.submit-btns{display:flex;gap:9px;}

/* ── BUTTONS ── */
.btn{display:inline-flex;align-items:center;gap:7px;padding:11px 22px;border-radius:var(--r-sm);font-size:13px;font-weight:600;cursor:pointer;border:none;transition:all var(--ease);font-family:var(--font);}
.btn:active{transform:scale(.97);}
.btn svg{width:14px;height:14px;}
.btn-secondary{background:var(--surface2);color:var(--text2);border:1px solid var(--border2);}
.btn-secondary:hover{background:var(--surface3);color:var(--text);}
.btn-danger{background:var(--red-lt);color:var(--red);border:1px solid rgba(240,68,68,.2);}
.btn-danger:hover{background:var(--red);color:#fff;border-color:var(--red);}
.btn-primary{background:#6366f1;color:#fff;box-shadow:0 4px 18px rgba(99,102,241,.3);}
.btn-primary:hover{transform:translateY(-2px);box-shadow:0 8px 28px rgba(99,102,241,.45);}
.btn-primary:disabled{opacity:.6;cursor:not-allowed;transform:none;box-shadow:none;}

/* ── ALERTS ── */
.alert-ok{background:rgba(5,196,138,.08);border:1px solid rgba(5,196,138,.22);color:#065f46;padding:12px 16px;border-radius:var(--r-sm);font-size:13px;margin-bottom:16px;display:flex;align-items:center;gap:10px;animation:fadeUp .3s ease;}
.alert-err{background:var(--red-lt);border:1px solid rgba(240,68,68,.22);color:var(--red);padding:14px 16px;border-radius:var(--r-sm);font-size:13px;margin-bottom:16px;display:flex;align-items:flex-start;gap:12px;animation:fadeUp .3s ease;}
.alert-err ul{padding-left:16px;margin-top:6px;}
.alert-err ul li{margin-bottom:3px;font-size:12px;}
[data-theme="dark"] .alert-ok{color:#189d68;}
.alert-ok svg,.alert-err svg{width:15px;height:15px;flex-shrink:0;margin-top:1px;}

/* ── PREVIEW CARD ── */
.side-stack{display:flex;flex-direction:column;gap:16px;position:sticky;top:80px;}
.preview-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);padding:20px;box-shadow:var(--sh);animation:fadeUp .4s .08s ease both;}
.preview-hdr{display:flex;align-items:center;gap:8px;margin-bottom:14px;}
.preview-hdr svg{width:14px;height:14px;color:var(--text3);}
.preview-hdr span{font-size:12px;font-weight:700;color:var(--text2);font-family:var(--mono);text-transform:uppercase;letter-spacing:.08em;}
.prev-img-wrap{width:100%;height:130px;border-radius:var(--r-sm);overflow:hidden;margin-bottom:14px;background:var(--surface2);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;color:var(--text3);}
.prev-img-wrap img{width:100%;height:100%;object-fit:cover;}
.prev-img-wrap .placeholder-ico{font-size:28px;opacity:.3;}
.prev-prod-name{font-family:var(--mono);font-size:15px;font-weight:700;color:var(--text);margin-bottom:6px;min-height:22px;}
.prev-meta{display:flex;flex-wrap:wrap;gap:6px;margin-bottom:10px;}
.prev-chip{display:inline-flex;align-items:center;gap:4px;font-size:11px;font-weight:600;padding:4px 10px;border-radius:100px;font-family:var(--mono);background:var(--surface2);border:1px solid var(--border2);color:var(--text3);}
.prev-chip.green-chip{background:var(--green-lt);border-color:rgba(5,196,138,.25);color:var(--green);}
.prev-chip.purple-chip{background:var(--a-lt);border-color:rgba(37,99,235,.25);color:var(--a);}
.prev-desc{font-size:12px;color:var(--text3);line-height:1.6;min-height:32px;}
.prev-divider{height:1px;background:var(--border);margin:12px 0;}
.prev-stat-row{display:flex;align-items:center;justify-content:space-between;}
.prev-stat{text-align:center;}
.prev-stat-val{font-family:var(--mono);font-size:15px;font-weight:700;color:var(--text);line-height:1;}
.prev-stat-lbl{font-size:10px;color:var(--text3);font-family:var(--mono);margin-top:3px;}

/* ── TIPS ── */
.tips-card{background:linear-gradient(135deg,rgba(37,99,235,.06),rgba(13,148,136,.03));border:1px solid rgba(37,99,235,.15);border-radius:var(--r);padding:18px;animation:fadeUp .4s .12s ease both;}
.tips-hdr{display:flex;align-items:center;gap:8px;margin-bottom:12px;}
.tips-hdr svg{width:15px;height:15px;color:var(--a);}
.tips-hdr span{font-size:12px;font-weight:700;color:var(--a);font-family:var(--mono);text-transform:uppercase;letter-spacing:.08em;}
.tip-item{display:flex;align-items:flex-start;gap:8px;margin-bottom:9px;font-size:12px;color:var(--text2);line-height:1.5;}
.tip-item:last-child{margin-bottom:0;}
.tip-num{width:16px;height:16px;border-radius:5px;background:var(--a-lt);color:var(--a);font-size:9px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:1px;font-family:var(--mono);}

/* ── TOAST ── */
.toast-wrap{position:fixed;top:20px;right:20px;z-index:9999;display:flex;flex-direction:column;gap:8px;pointer-events:none;}
.toast{display:flex;align-items:center;gap:10px;padding:13px 16px;border-radius:14px;font-size:13px;font-weight:500;color:#fff;min-width:270px;box-shadow:var(--sh-lg);pointer-events:all;animation:toastIn .3s ease both;}
.toast svg{width:15px;height:15px;flex-shrink:0;}
.toast-ok{background:linear-gradient(135deg,#059669,#10b981);}
.toast-err{background:linear-gradient(135deg,#dc2626,#f04444);}
.toast-x{margin-left:auto;width:18px;height:18px;border-radius:5px;background:rgba(255,255,255,.22);border:none;cursor:pointer;color:#fff;font-size:11px;display:flex;align-items:center;justify-content:center;}

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

/* ── RESPONSIVE ── */
@media(max-width:1100px){.form-layout{grid-template-columns:1fr;}.side-stack{position:static;}}
@media(max-width:860px){.sidebar{transform:translateX(-100%)}.sidebar.open{transform:translateX(0)}.main{margin-left:0}.hamburger{display:flex}}
@media(max-width:600px){.topbar{padding:0 16px}.body{padding:14px 14px 48px}.field-row{grid-template-columns:1fr}}
</style>
@endpush

@section('content')
<div class="overlay" id="deleteOverlay" role="dialog" aria-modal="true">
  <div class="modal">
    <button type="button" class="modal-x" onclick="closeDeleteModal()">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
    <div class="modal-ico">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg>
    </div>
    <h3>Delete Product?</h3>
    <p>This will permanently remove <strong>{{ $categoryProduct->name }}</strong>. This action cannot be undone.</p>
    <div class="modal-acts">
      <button class="modal-cancel" onclick="closeDeleteModal()">Cancel</button>
      <button class="modal-del" onclick="document.getElementById('deleteForm').submit()">Yes, Delete</button>
    </div>
  </div>
</div>
<form id="deleteForm" action="{{ route('admin.category-products.destroy', $categoryProduct->id) }}" method="POST" style="display:none;">
  @csrf @method('DELETE')
</form>

<div class="breadcrumb">
  <a href="{{ route('admin.dashboard') }}">Dashboard</a>
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
  <a href="{{ route('admin.category-products.index') }}">Products</a>
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
  <span>Edit #{{ $categoryProduct->id }}</span>
</div>

<div class="hero">
  <div class="hero-left">
    <div class="hero-tag"><span class="hero-tag-dot"></span>Editing</div>
    <div class="hero-name">{{ $categoryProduct->name }}</div>
    <div class="hero-sub">Update product details, pricing, stock and visibility settings.</div>
  </div>
  <div class="hero-right">
    <a href="{{ route('admin.category-products.index') }}" class="hero-btn hero-btn-ghost">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
      Back to Products
    </a>
  </div>
</div>

@if($errors->any())
<div class="alert-err">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
  <div>
    <strong>Please fix the following errors:</strong>
    <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
  </div>
</div>
@endif

@if(session('success'))
<div class="alert-ok" id="flashAlert">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
  {{ session('success') }}
</div>
@endif

<form id="editForm"
  action="{{ route('admin.category-products.update', $categoryProduct->id) }}"
  method="POST"
  enctype="multipart/form-data"
  novalidate>
  @csrf
  @method('PUT')

  <div class="form-layout">

    <div>
      <div class="card" style="animation-delay:.05s;">
        <div class="card-hdr">
          <div class="card-ico ci-purple">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
          </div>
          <div>
            <div class="card-ttl">Basic Information</div>
            <div class="card-sub">Core product details visible to donors</div>
          </div>
        </div>

        <div class="field">
          <label class="lbl" for="name">Product Name <span class="req">*</span></label>
          <div class="inp-wrap">
            <svg class="inp-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            <input type="text" id="name" name="name" class="inp @error('name') err @enderror"
              placeholder="e.g. Warm Blanket Set" autocomplete="off" required
              value="{{ old('name', $categoryProduct->name) }}">
          </div>
          @error('name')<p class="field-error">{{ $message }}</p>@enderror
        </div>

        <div class="field-row field">
          <div>
            <label class="lbl" for="category_id">Category <span class="req">*</span></label>
            <select id="category_id" name="category_id" class="sel @error('category_id') err @enderror" required>
              <option value="" disabled>Select category…</option>
              @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ old('category_id', $categoryProduct->category_id) == $cat->id ? 'selected' : '' }}>
                  {{ $cat->name }}
                </option>
              @endforeach
            </select>
            @error('category_id')<p class="field-error">{{ $message }}</p>@enderror
          </div>
          <div>
            <label class="lbl" for="product_type">Product Type <span class="req">*</span></label>
            <select id="product_type" name="product_type" class="sel @error('product_type') err @enderror" required>
              <option value="" disabled>Select type…</option>
              @foreach(['physical'=>'Physical','digital'=>'Digital','service'=>'Service','bundle'=>'Bundle'] as $val=>$lbl)
                <option value="{{ $val }}" {{ old('product_type', $categoryProduct->product_type) === $val ? 'selected' : '' }}>{{ $lbl }}</option>
              @endforeach
            </select>
            @error('product_type')<p class="field-error">{{ $message }}</p>@enderror
          </div>
        </div>

        <div class="field">
          <label class="lbl" for="description">Description</label>
          <textarea id="description" name="description" class="ta @error('description') err @enderror"
            rows="4" placeholder="Describe this product for potential donors…">{{ old('description', $categoryProduct->description) }}</textarea>
          @error('description')<p class="field-error">{{ $message }}</p>@enderror
        </div>
      </div>

      <div class="card" style="animation-delay:.10s;">
        <div class="card-hdr">
          <div class="card-ico ci-green">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          </div>
          <div>
            <div class="card-ttl">Pricing &amp; Stock</div>
            <div class="card-sub">Set price and inventory levels</div>
          </div>
        </div>

        <div class="field-row field">
          <div>
            <label class="lbl" for="price">Price <span class="req">*</span></label>
            <div class="price-wrap">
              <span class="price-symbol">₹</span>
              <input type="number" id="price" name="price" class="inp @error('price') err @enderror"
                placeholder="0.00" step="0.01" min="0" required
                value="{{ old('price', $categoryProduct->price) }}">
            </div>
            @error('price')<p class="field-error">{{ $message }}</p>@enderror
            <p class="field-hint">Amount donors contribute per unit.</p>
          </div>
          <div>
            <label class="lbl" for="stock">Stock Quantity <span class="req">*</span></label>
            <div class="inp-wrap">
              <svg class="inp-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a2 2 0 012-2z"/></svg>
              <input type="number" id="stock" name="stock" class="inp @error('stock') err @enderror"
                placeholder="0" min="0" required
                value="{{ old('stock', $categoryProduct->stock) }}">
            </div>
            @error('stock')<p class="field-error">{{ $message }}</p>@enderror
            <p class="field-hint">Units available for donation.</p>
          </div>
        </div>
      </div>

      <div class="card" style="animation-delay:.14s;">
        <div class="card-hdr">
          <div class="card-ico ci-blue">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
          </div>
          <div>
            <div class="card-ttl">Product Image</div>
            <div class="card-sub">Upload a clear, high-quality photo</div>
          </div>
        </div>

        @if($categoryProduct->image)
        <div class="field">
          <label class="lbl">Current Image</label>
          <div style="display:flex;align-items:flex-start;gap:14px;">
            <div style="flex-shrink:0;">
              <img src="{{ asset('storage/'.$categoryProduct->image) }}"
                style="width:90px;height:90px;object-fit:cover;border-radius:var(--r-sm);border:1px solid var(--border);"
                alt="{{ $categoryProduct->name }}" id="currentImg">
            </div>
            <div style="flex:1;">
              <p class="field-hint" style="margin-top:0;">This is the current product image. Upload a new one below to replace it.</p>
              <label style="display:inline-flex;align-items:center;gap:6px;margin-top:8px;cursor:pointer;">
                <input type="checkbox" id="removeImage" name="remove_image" value="1"
                  style="accent-color:var(--red);width:14px;height:14px;">
                <span style="font-size:12px;font-family:var(--mono);color:var(--red);font-weight:600;">Remove current image</span>
              </label>
            </div>
          </div>
        </div>
        @endif

        <div class="field">
          <label class="lbl" for="imageUpload">{{ $categoryProduct->image ? 'Replace Image' : 'Upload Image' }}</label>
          <div class="upload-zone" id="uploadZone" onclick="document.getElementById('imageUpload').click()">
            <input type="file" id="imageUpload" name="image" accept="image/*">
            <div class="upload-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <div class="upload-title" id="uploadTitle">Click to upload or drag &amp; drop</div>
            <div class="upload-sub">PNG, JPG, WebP · Max 2 MB</div>
          </div>
          <div id="imgPreviewWrap" style="display:none;margin-top:12px;">
            <div class="img-preview-wrap">
              <img id="imgPreview" class="img-preview" src="" alt="Preview">
              <button type="button" class="img-remove" onclick="clearImagePreview()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
              </button>
            </div>
            <p class="img-label" id="imgLabel"></p>
          </div>
          @error('image')<p class="field-error">{{ $message }}</p>@enderror
          <p class="field-hint">Recommended: 800×800px square. The image will be used on the donation page.</p>
        </div>
      </div>

      <div class="card" style="animation-delay:.18s;">
        <div class="card-hdr">
          <div class="card-ico ci-amber">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          </div>
          <div>
            <div class="card-ttl">Visibility</div>
            <div class="card-sub">Control whether this product is public</div>
          </div>
        </div>

        <div class="field">
          <label class="lbl">Status</label>
          <label class="toggle-row" id="statusRow">
            <div>
              <div class="toggle-row-title">Product is active &amp; visible</div>
              <div class="toggle-row-sub">Uncheck to hide this product from donors</div>
            </div>
            <div class="toggle-switch">
              <input type="checkbox" id="is_active" name="is_active" value="1"
                {{ old('is_active', $categoryProduct->is_active) ? 'checked' : '' }}>
              <label for="is_active"></label>
            </div>
          </label>
        </div>

        <div class="submit-row">
          <div class="submit-info">Fields marked <span class="req">*</span> are required</div>
          <div class="submit-btns">
            <a href="{{ route('admin.category-products.index') }}" class="btn btn-secondary">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
              Discard
            </a>
            <button type="button" class="btn btn-danger" onclick="document.getElementById('deleteOverlay').classList.add('open')">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg>
              Delete
            </button>
            <button type="submit" class="btn btn-primary" id="saveBtn">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
              Save Changes
            </button>
          </div>
        </div>
      </div>

    </div>

    <div class="side-stack">

      <div class="preview-card">
        <div class="preview-hdr">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
          <span>Live Preview</span>
        </div>

        <div class="prev-img-wrap" id="prevImgWrap">
          @if($categoryProduct->image)
            <img src="{{ asset('storage/'.$categoryProduct->image) }}" alt="preview" id="prevImg">
          @else
            <div class="placeholder-ico"><i class="fa fa-box"></i></div>
          @endif
        </div>

        <div class="prev-prod-name" id="prevName">{{ $categoryProduct->name }}</div>
        <div class="prev-meta">
          <span class="prev-chip green-chip" id="prevPrice">₹{{ number_format($categoryProduct->price, 2) }}</span>
          <span class="prev-chip purple-chip" id="prevType">{{ ucfirst($categoryProduct->product_type) }}</span>
          <span class="prev-chip" id="prevStock">Stock: {{ $categoryProduct->stock }}</span>
        </div>
        <div class="prev-desc" id="prevDesc">{{ $categoryProduct->description ?: 'Description will appear here…' }}</div>

        <div class="prev-divider"></div>
        <div class="prev-stat-row">
          <div class="prev-stat">
            <div class="prev-stat-val" id="prevPriceVal">₹{{ number_format($categoryProduct->price, 2) }}</div>
            <div class="prev-stat-lbl">Price</div>
          </div>
          <div class="prev-stat">
            <div class="prev-stat-val" id="prevStockVal">{{ $categoryProduct->stock }}</div>
            <div class="prev-stat-lbl">In Stock</div>
          </div>
          <div class="prev-stat">
            <div class="prev-stat-val" id="prevStatusVal" style="color:{{ $categoryProduct->is_active ? 'var(--green)' : 'var(--red)' }};">
              {{ $categoryProduct->is_active ? '●' : '○' }}
            </div>
            <div class="prev-stat-lbl">Status</div>
          </div>
        </div>
      </div>

      <div class="preview-card" style="animation-delay:.1s;">
        <div class="preview-hdr">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          <span>Product Info</span>
        </div>
        <div style="display:flex;flex-direction:column;gap:10px;">
          @foreach([
            ['ID', '#'.$categoryProduct->id],
            ['Created', $categoryProduct->created_at->format('d M Y')],
            ['Last Updated', $categoryProduct->updated_at->diffForHumans()],
          ] as $row)
          <div style="display:flex;align-items:center;justify-content:space-between;">
            <span style="font-size:11.5px;color:var(--text3);font-family:var(--mono);">{{ $row[0] }}</span>
            <span style="font-size:12px;font-weight:600;color:var(--text2);font-family:var(--mono);">{{ $row[1] }}</span>
          </div>
          @endforeach
        </div>
      </div>

      <div class="tips-card">
        <div class="tips-hdr">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
          <span>Update Tips</span>
        </div>
        @foreach([
          'Use a clear product name that matches what donors expect.',
          'Keep stock updated — zero stock hides the item from donation flows.',
          'A square 800×800px image renders best on campaign pages.',
          'Set to Inactive instead of deleting to preserve donation history.',
        ] as $idx => $tip)
        <div class="tip-item">
          <div class="tip-num">{{ $idx + 1 }}</div>
          <div>{{ $tip }}</div>
        </div>
        @endforeach
      </div>

    </div>
  </div>
</form>
@endsection

@push('page_scripts')
<script>
(function () {
  'use strict';

  var flash = document.getElementById('flashAlert');
  if (flash) setTimeout(function () {
    flash.style.transition = 'opacity .4s,transform .4s'; flash.style.opacity = '0';
    flash.style.transform = 'translateY(-6px)';
    setTimeout(function () { flash.remove(); }, 400);
  }, 4000);

  window.closeDeleteModal = function () { document.getElementById('deleteOverlay').classList.remove('open'); };
  document.getElementById('deleteOverlay').addEventListener('click', function (e) { if (e.target === this) closeDeleteModal(); });
  document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeDeleteModal(); });

  var isActiveChk = document.getElementById('is_active');
  var statusRow   = document.getElementById('statusRow');
  function syncStatus() { statusRow.classList.toggle('active-toggle', isActiveChk.checked); }
  isActiveChk.addEventListener('change', syncStatus); syncStatus();

  var removeChk   = document.getElementById('removeImage');
  var currentImg  = document.getElementById('currentImg');
  if (removeChk && currentImg) {
    removeChk.addEventListener('change', function () {
      currentImg.style.opacity  = this.checked ? '.3' : '1';
      currentImg.style.filter   = this.checked ? 'grayscale(1)' : '';
    });
  }

  var uploadInput   = document.getElementById('imageUpload');
  var uploadZone    = document.getElementById('uploadZone');
  var imgPreviewWrap= document.getElementById('imgPreviewWrap');
  var imgPreview    = document.getElementById('imgPreview');
  var imgLabel      = document.getElementById('imgLabel');
  var uploadTitle   = document.getElementById('uploadTitle');
  var prevImgWrap   = document.getElementById('prevImgWrap');

  uploadInput.addEventListener('change', function () { loadPreview(this.files[0]); });

  ['dragover','dragenter'].forEach(function (ev) {
    uploadZone.addEventListener(ev, function (e) { e.preventDefault(); uploadZone.classList.add('drag-over'); });
  });
  ['dragleave','drop'].forEach(function (ev) {
    uploadZone.addEventListener(ev, function (e) {
      e.preventDefault(); uploadZone.classList.remove('drag-over');
      if (ev === 'drop' && e.dataTransfer.files.length) loadPreview(e.dataTransfer.files[0]);
    });
  });

  function loadPreview(file) {
    if (!file || !file.type.startsWith('image/')) return;
    var reader = new FileReader();
    reader.onload = function (e) {
      imgPreview.src = e.target.result;
      imgLabel.textContent = file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)';
      imgPreviewWrap.style.display = 'block';
      uploadTitle.textContent = 'Image selected';
      var pi = prevImgWrap.querySelector('img');
      if (pi) { pi.src = e.target.result; }
      else {
        prevImgWrap.innerHTML = '';
        var ni = document.createElement('img');
        ni.src = e.target.result; ni.alt = 'preview'; ni.id = 'prevImg';
        prevImgWrap.appendChild(ni);
      }
    };
    reader.readAsDataURL(file);
  }

  window.clearImagePreview = function () {
    uploadInput.value = '';
    imgPreviewWrap.style.display = 'none';
    imgPreview.src = '';
    uploadTitle.textContent = 'Click to upload or drag & drop';
  };

  var nameInp   = document.getElementById('name');
  var priceInp  = document.getElementById('price');
  var stockInp  = document.getElementById('stock');
  var typeInp   = document.getElementById('product_type');
  var descInp   = document.getElementById('description');

  function fmt(v) {
    var n = parseFloat(v);
    return isNaN(n) ? '—' : '₹' + n.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
  }

  function updatePreview() {
    var n = nameInp.value.trim();
    document.getElementById('prevName').textContent = n || 'Product name…';

    var p = priceInp.value;
    var pf = fmt(p);
    document.getElementById('prevPrice').textContent    = pf;
    document.getElementById('prevPriceVal').textContent = pf;

    var s = parseInt(stockInp.value, 10);
    document.getElementById('prevStock').textContent    = 'Stock: ' + (isNaN(s) ? '—' : s);
    document.getElementById('prevStockVal').textContent = isNaN(s) ? '—' : s;

    document.getElementById('prevType').textContent = typeInp.value ? typeInp.options[typeInp.selectedIndex].text : '—';

    var d = descInp.value.trim();
    document.getElementById('prevDesc').textContent = d ? (d.length > 120 ? d.slice(0,120)+'…' : d) : 'Description will appear here…';

    var active = isActiveChk.checked;
    var sv = document.getElementById('prevStatusVal');
    sv.textContent = active ? '●' : '○';
    sv.style.color = active ? 'var(--green)' : 'var(--red)';
  }

  [nameInp, priceInp, stockInp, descInp].forEach(function (el) { el.addEventListener('input', updatePreview); });
  typeInp.addEventListener('change', updatePreview);
  isActiveChk.addEventListener('change', updatePreview);

  document.getElementById('editForm').addEventListener('submit', function () {
    var btn = document.getElementById('saveBtn');
    btn.disabled = true;
    btn.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="animation:spin .7s linear infinite"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg> Saving…';
  });

}());
</script>
@endpush
