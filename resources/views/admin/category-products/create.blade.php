@extends('layouts.admin')

@section('sidebar_category_products', 'active')
@section('page_title', 'Create Category Product')
@section('page_subtitle', 'Add a new product category')

@push('page_styles')
<style>
/* ── BREADCRUMB ── */
.breadcrumb{display:flex;align-items:center;gap:7px;font-size:11.5px;color:var(--text3);font-family:var(--mono);margin-bottom:22px;animation:fadeUp .3s ease both;}
.breadcrumb a{color:var(--text3);transition:color var(--ease);}
.breadcrumb a:hover{color:var(--a);}
.breadcrumb svg{width:10px;height:10px;flex-shrink:0;}
.breadcrumb span{color:var(--text2);}

/* ── ALERT ── */
.alert-error{background:var(--red-lt);border:1px solid rgba(240,68,68,.22);color:#b91c1c;padding:12px 16px;border-radius:var(--r-sm);font-size:13px;margin-bottom:20px;display:flex;align-items:flex-start;gap:10px;animation:fadeUp .3s ease;}
.alert-error svg{width:15px;height:15px;flex-shrink:0;margin-top:1px;}
[data-theme="dark"] .alert-error{color:#f87171;}

/* ── PAGE GRID ── */
.page-grid{display:grid;grid-template-columns:1fr 300px;gap:20px;align-items:start;}

/* ── CARDS ── */
.card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);overflow:hidden;margin-bottom:16px;animation:fadeUp .4s ease both;}
.card-head{display:flex;align-items:center;gap:10px;padding:14px 20px;border-bottom:1px solid var(--border);background:var(--surface2);}
.card-head-icon{width:30px;height:30px;border-radius:8px;background:var(--a-lt);color:var(--a);display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.card-head-icon svg{width:14px;height:14px;}
.card-head-title{font-size:11.5px;font-weight:700;color:var(--text2);text-transform:uppercase;letter-spacing:.09em;font-family:var(--mono);}
.card-body{padding:22px;}

/* ── FIELDS ── */
.field{margin-bottom:20px;}
.field:last-child{margin-bottom:0;}
.field-row{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px;}
.field-row:last-child{margin-bottom:0;}
.f-label{display:block;font-size:11.5px;font-weight:600;color:var(--text2);margin-bottom:7px;font-family:var(--mono);text-transform:uppercase;letter-spacing:.06em;}
.f-label .req{color:var(--red);margin-left:2px;}
.f-input,.f-select,.f-textarea{width:100%;background:var(--surface2);border:1px solid var(--border2);border-radius:var(--r-sm);padding:10px 13px;font-size:13px;color:var(--text);font-family:var(--font);outline:none;transition:border-color .2s,box-shadow .2s,background .2s;}
.f-input::placeholder,.f-textarea::placeholder{color:var(--text3);}
.f-input:focus,.f-select:focus,.f-textarea:focus{border-color:var(--a);box-shadow:0 0 0 3px var(--a-glow);background:var(--surface);}
.f-input.err,.f-select.err,.f-textarea.err{border-color:var(--red);}
.f-select{appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%239096b4' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 12px center;padding-right:34px;cursor:pointer;}
.f-textarea{resize:vertical;min-height:90px;line-height:1.6;}
.f-hint{font-size:11px;color:var(--text3);margin-top:5px;line-height:1.5;}
.f-error{font-size:11.5px;color:var(--red);margin-top:5px;font-family:var(--mono);}

/* ── TOGGLE ── */
.toggle-row{display:flex;align-items:center;justify-content:space-between;padding:4px 0;}
.toggle-lbl{font-size:13px;font-weight:600;color:var(--text);}
.toggle-sub{font-size:11.5px;color:var(--text3);margin-top:2px;}
.sw{position:relative;flex-shrink:0;}
.sw input{position:absolute;opacity:0;width:0;height:0;}
.sw label{display:block;width:46px;height:26px;border-radius:100px;background:var(--border2);cursor:pointer;position:relative;transition:background .2s;}
.sw label::after{content:'';position:absolute;width:20px;height:20px;border-radius:50%;background:#fff;top:3px;left:3px;transition:transform .25s cubic-bezier(.4,0,.2,1);box-shadow:0 1px 4px rgba(0,0,0,.2);}
.sw input:checked+label{background:var(--a);}
.sw input:checked+label::after{transform:translateX(20px);}

/* ── UPLOAD ZONE ── */
.upload-zone{border:2px dashed var(--border2);border-radius:var(--r-sm);padding:28px 20px;text-align:center;cursor:pointer;transition:all .2s;position:relative;background:var(--surface2);}
.upload-zone:hover,.upload-zone.drag{border-color:var(--a);background:var(--a-lt);}
.upload-zone input[type=file]{position:absolute;inset:0;opacity:0;cursor:pointer;width:100%;height:100%;}
.upload-ico{width:44px;height:44px;border-radius:12px;background:var(--a-lt);color:var(--a);display:flex;align-items:center;justify-content:center;margin:0 auto 12px;}
.upload-ico svg{width:20px;height:20px;}
.upload-title{font-size:13px;font-weight:600;color:var(--text);margin-bottom:4px;}
.upload-sub{font-size:11.5px;color:var(--text3);}
.img-preview-wrap{display:none;flex-direction:column;align-items:center;gap:10px;}
.img-preview{width:100px;height:100px;border-radius:var(--r-sm);object-fit:cover;border:1px solid var(--border2);box-shadow:var(--sh);}
.img-remove{font-size:11.5px;color:var(--red);cursor:pointer;background:none;border:none;font-family:var(--font);font-weight:500;padding:4px 8px;border-radius:6px;transition:background var(--ease);}
.img-remove:hover{background:var(--red-lt);}

/* ── SUBMIT ── */
.submit-btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;width:100%;padding:13px 20px;background:linear-gradient(135deg,var(--a),var(--a2));color:#fff;border:none;border-radius:var(--r-sm);font-size:14px;font-weight:700;cursor:pointer;font-family:var(--mono);letter-spacing:-.01em;transition:opacity .2s,transform .15s;box-shadow:0 4px 18px rgba(110,86,247,.35);animation:fadeUp .4s .25s ease both;}
.submit-btn:hover{opacity:.88;transform:translateY(-1px);}
.submit-btn:active{transform:scale(.98);}
.submit-btn:disabled{opacity:.6;cursor:not-allowed;transform:none;}
.submit-btn svg{width:15px;height:15px;}

/* ── PREVIEW CARD ── */
.preview-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh);overflow:hidden;position:sticky;top:82px;animation:fadeUp .4s .15s ease both;}
.preview-live{padding:28px 20px;display:flex;flex-direction:column;align-items:center;text-align:center;background:var(--surface2);border-bottom:1px solid var(--border);min-height:180px;gap:10px;}
.prev-img-box{width:80px;height:80px;border-radius:16px;background:var(--a-lt);display:flex;align-items:center;justify-content:center;color:var(--a);font-size:28px;box-shadow:var(--sh-md);overflow:hidden;flex-shrink:0;}
.prev-img-box img{width:100%;height:100%;object-fit:cover;}
.prev-prod-name{font-family:var(--mono);font-size:14px;font-weight:700;color:var(--text);letter-spacing:-.01em;}
.prev-prod-name.empty{color:var(--text3);font-weight:400;font-style:italic;}
.prev-badge{display:inline-flex;align-items:center;gap:5px;padding:4px 11px;border-radius:100px;font-size:10.5px;font-weight:700;font-family:var(--mono);text-transform:uppercase;letter-spacing:.06em;}
.pb-active{background:rgba(5,196,138,.12);color:var(--green);border:1px solid rgba(5,196,138,.22);}
.pb-inactive{background:rgba(100,116,139,.08);color:var(--text3);border:1px solid var(--border2);}
.preview-meta{padding:14px 20px;}
.prev-row{display:flex;align-items:center;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--border);font-size:12px;}
.prev-row:last-child{border-bottom:none;}
.prev-row-lbl{color:var(--text3);font-family:var(--mono);font-size:10px;text-transform:uppercase;letter-spacing:.07em;}
.prev-row-val{color:var(--text2);font-weight:600;font-family:var(--mono);font-size:11.5px;}

/* ── TOAST ── */
.toast-wrap{position:fixed;top:20px;right:20px;z-index:9999;display:flex;flex-direction:column;gap:8px;pointer-events:none;}
.toast{display:flex;align-items:center;gap:10px;padding:13px 16px;border-radius:14px;font-size:13px;font-weight:500;color:#fff;min-width:270px;box-shadow:var(--sh-lg);pointer-events:all;animation:toastIn .3s ease both;}
.toast svg{width:15px;height:15px;flex-shrink:0;}
.toast-ok{background:linear-gradient(135deg,#059669,#10b981);}

@keyframes fadeUp{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:none}}
@keyframes toastIn{from{opacity:0;transform:translateX(18px) scale(.96)}to{opacity:1;transform:none}}

@media(max-width:860px){.sidebar{transform:translateX(-100%)}.sidebar.open{transform:translateX(0)}.main{margin-left:0}.hamburger{display:flex}}
@media(max-width:700px){.field-row{grid-template-columns:1fr;}.page-grid{grid-template-columns:1fr;}}
@media(max-width:600px){.topbar{padding:0 16px}.body{padding:14px 14px 48px}}
</style>
@endpush

@section('content')
<div class="breadcrumb">
  <a href="{{ route('admin.dashboard') }}">Dashboard</a>
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
  <a href="{{ route('admin.category-products.index') }}">Category Products</a>
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
  <span>Add Product</span>
</div>

@if($errors->any())
<div class="alert-error">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
  <div>
    <strong>Please fix the following:</strong>
    <ul style="margin-top:4px;padding-left:16px;">
      @foreach($errors->all() as $e)<li style="font-size:12px;margin-top:2px;">{{ $e }}</li>@endforeach
    </ul>
  </div>
</div>
@endif

<form method="POST" action="{{ route('admin.category-products.store') }}" enctype="multipart/form-data" id="prodForm">
@csrf

<div class="page-grid">
  <div>

    <div class="card">
      <div class="card-head">
        <div class="card-head-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
        <span class="card-head-title">Basic Information</span>
      </div>
      <div class="card-body">
        <div class="field">
          <label class="f-label" for="name">Product Name <span class="req">*</span></label>
          <input id="name" name="name" type="text" value="{{ old('name') }}"
            class="f-input {{ $errors->has('name')?'err':'' }}"
            placeholder="e.g. Awareness T-Shirt, Donation Kit…"
            oninput="updatePreview()" required>
          @error('name')<p class="f-error">{{ $message }}</p>@enderror
        </div>
        <div class="field">
          <label class="f-label" for="description">Description</label>
          <textarea id="description" name="description" class="f-textarea {{ $errors->has('description')?'err':'' }}"
            placeholder="Brief description of this product…" rows="3">{{ old('description') }}</textarea>
          @error('description')<p class="f-error">{{ $message }}</p>@enderror
        </div>
        <div class="field">
          <div class="toggle-row">
            <div>
              <div class="toggle-lbl">Active</div>
              <div class="toggle-sub">Make this product visible on the public site</div>
            </div>
            <div class="sw">
              <input type="checkbox" name="is_active" id="isActive" value="1" checked onchange="updatePreview()">
              <label for="isActive"></label>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-head">
        <div class="card-head-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a2 2 0 012-2z"/></svg></div>
        <span class="card-head-title">Category & Type</span>
      </div>
      <div class="card-body">
        <div class="field-row">
          <div>
            <label class="f-label" for="category_id">Category <span class="req">*</span></label>
            <select id="category_id" name="category_id" class="f-select {{ $errors->has('category_id')?'err':'' }}" onchange="updatePreview()" required>
              <option value="">Select category…</option>
              @foreach($categories as $cat)
              <option value="{{ $cat->id }}" {{ old('category_id')==$cat->id?'selected':'' }}>{{ $cat->name }}</option>
              @endforeach
            </select>
            @error('category_id')<p class="f-error">{{ $message }}</p>@enderror
          </div>
          <div>
            <label class="f-label" for="product_type">Product Type <span class="req">*</span></label>
            <select id="product_type" name="product_type" class="f-select {{ $errors->has('product_type')?'err':'' }}" onchange="updatePreview()" required>
              <option value="">Select type…</option>
              <option value="physical" {{ old('product_type')=='physical'?'selected':'' }}>Physical</option>
              <option value="digital" {{ old('product_type')=='digital'?'selected':'' }}>Digital</option>
              <option value="service" {{ old('product_type')=='service'?'selected':'' }}>Service</option>
              <option value="bundle" {{ old('product_type')=='bundle'?'selected':'' }}>Bundle</option>
            </select>
            @error('product_type')<p class="f-error">{{ $message }}</p>@enderror
          </div>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-head">
        <div class="card-head-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
        <span class="card-head-title">Pricing & Stock</span>
      </div>
      <div class="card-body">
        <div class="field-row">
          <div>
            <label class="f-label" for="price">Price (₹) <span class="req">*</span></label>
            <input id="price" name="price" type="number" step="0.01" min="0"
              value="{{ old('price') }}"
              class="f-input {{ $errors->has('price')?'err':'' }}"
              placeholder="0.00" oninput="updatePreview()" required>
            @error('price')<p class="f-error">{{ $message }}</p>@enderror
          </div>
          <div>
            <label class="f-label" for="stock">Stock Quantity <span class="req">*</span></label>
            <input id="stock" name="stock" type="number" min="0"
              value="{{ old('stock',0) }}"
              class="f-input {{ $errors->has('stock')?'err':'' }}"
              placeholder="0" oninput="updatePreview()" required>
            @error('stock')<p class="f-error">{{ $message }}</p>@enderror
            <p class="f-hint">Set to 0 for unlimited / digital products</p>
          </div>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-head">
        <div class="card-head-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg></div>
        <span class="card-head-title">Product Image</span>
      </div>
      <div class="card-body">
        <div class="upload-zone" id="uploadZone">
          <input type="file" name="image" id="imageInput" accept="image/*" onchange="handleImageChange(this)">
          <div id="uploadPrompt">
            <div class="upload-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg></div>
            <div class="upload-title">Drop image here or click to browse</div>
            <div class="upload-sub">PNG, JPG, WEBP — max 2MB</div>
          </div>
          <div class="img-preview-wrap" id="imgPreviewWrap">
            <img src="" alt="Preview" class="img-preview" id="imgPreview">
            <button type="button" class="img-remove" onclick="removeImage()">✕ Remove image</button>
          </div>
        </div>
        @error('image')<p class="f-error" style="margin-top:8px;">{{ $message }}</p>@enderror
      </div>
    </div>

    <button type="submit" class="submit-btn" id="submitBtn">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
      Create Product
    </button>

  </div>

  <div>
    <div class="preview-card">
      <div class="card-head">
        <div class="card-head-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg></div>
        <span class="card-head-title">Live Preview</span>
      </div>
      <div class="preview-live">
        <div class="prev-img-box" id="prevImgBox">
          <i class="fa fa-box" id="prevImgIcon"></i>
          <img src="" id="prevImgEl" style="display:none;width:100%;height:100%;object-fit:cover;">
        </div>
        <div class="prev-prod-name empty" id="prevName">Product name…</div>
        <span class="prev-badge pb-active" id="prevBadge"><span style="width:5px;height:5px;border-radius:50%;background:currentColor;display:inline-block;"></span> Active</span>
      </div>
      <div class="preview-meta">
        <div class="prev-row">
          <span class="prev-row-lbl">Category</span>
          <span class="prev-row-val" id="prevCat">—</span>
        </div>
        <div class="prev-row">
          <span class="prev-row-lbl">Type</span>
          <span class="prev-row-val" id="prevType">—</span>
        </div>
        <div class="prev-row">
          <span class="prev-row-lbl">Price</span>
          <span class="prev-row-val" id="prevPrice">₹0.00</span>
        </div>
        <div class="prev-row">
          <span class="prev-row-lbl">Stock</span>
          <span class="prev-row-val" id="prevStock">0</span>
        </div>
        <div class="prev-row">
          <span class="prev-row-lbl">Status</span>
          <span class="prev-row-val" id="prevStatus" style="color:var(--green);">Active</span>
        </div>
      </div>
    </div>
  </div>

</div>
</form>
@endsection

@push('page_scripts')
<script>
(function(){
'use strict';

window.updatePreview=function(){
  var name=document.getElementById('name').value.trim();
  var active=document.getElementById('isActive').checked;
  var catSel=document.getElementById('category_id');
  var catText=catSel.options[catSel.selectedIndex]?catSel.options[catSel.selectedIndex].text:'—';
  var typeSel=document.getElementById('product_type');
  var typeText=typeSel.value?typeSel.value.charAt(0).toUpperCase()+typeSel.value.slice(1):'—';
  var price=parseFloat(document.getElementById('price').value)||0;
  var stock=parseInt(document.getElementById('stock').value)||0;

  var nameEl=document.getElementById('prevName');
  nameEl.textContent=name||'Product name…';
  nameEl.classList.toggle('empty',!name);

  var badge=document.getElementById('prevBadge');
  var statusEl=document.getElementById('prevStatus');
  if(active){
    badge.className='prev-badge pb-active';
    badge.innerHTML='<span style="width:5px;height:5px;border-radius:50%;background:currentColor;display:inline-block;"></span> Active';
    statusEl.textContent='Active';statusEl.style.color='var(--green)';
  } else {
    badge.className='prev-badge pb-inactive';
    badge.innerHTML='<span style="width:5px;height:5px;border-radius:50%;background:currentColor;display:inline-block;"></span> Inactive';
    statusEl.textContent='Inactive';statusEl.style.color='var(--text3)';
  }
  document.getElementById('prevCat').textContent=catText==='Select category…'?'—':catText;
  document.getElementById('prevType').textContent=typeText;
  document.getElementById('prevPrice').textContent='₹'+price.toFixed(2);
  document.getElementById('prevStock').textContent=stock;
};

window.handleImageChange=function(input){
  if(!input.files||!input.files[0])return;
  var reader=new FileReader();
  reader.onload=function(e){
    document.getElementById('prevImgIcon').style.display='none';
    var el=document.getElementById('prevImgEl');
    el.src=e.target.result;el.style.display='block';
    document.getElementById('uploadPrompt').style.display='none';
    document.getElementById('imgPreviewWrap').style.display='flex';
    document.getElementById('imgPreview').src=e.target.result;
  };
  reader.readAsDataURL(input.files[0]);
};

window.removeImage=function(){
  document.getElementById('imageInput').value='';
  document.getElementById('prevImgIcon').style.display='';
  document.getElementById('prevImgEl').style.display='none';
  document.getElementById('uploadPrompt').style.display='';
  document.getElementById('imgPreviewWrap').style.display='none';
};

var zone=document.getElementById('uploadZone');
zone.addEventListener('dragover',function(e){e.preventDefault();zone.classList.add('drag');});
zone.addEventListener('dragleave',function(){zone.classList.remove('drag');});
zone.addEventListener('drop',function(){zone.classList.remove('drag');});

document.getElementById('prodForm').addEventListener('submit',function(){
  var btn=document.getElementById('submitBtn');
  btn.disabled=true;
  btn.innerHTML='<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="15" height="15"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg> Creating…';
});

updatePreview();
})();
</script>
@endpush
