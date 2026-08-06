@extends('layouts.admin')

@push('page_css')
@vite('resources/css/admin/entries/categories.css')
@endpush


@section('sidebar_category_products', 'active')
@section('page_title', 'Create Category Product')
@section('page_subtitle', 'Add a new product category')

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

    <x-button variant="primary" type="submit">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
      Create Product
    </x-button>

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
