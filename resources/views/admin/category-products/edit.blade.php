@push('page_css')
@vite('resources/css/admin/entries/misc.css')
@endpush

@extends('layouts.admin')

@section('sidebar_category_products', 'active')
@section('page_title', 'Edit Category Product')
@section('page_subtitle', 'Modify product category')

@section('content')
<div class="overlay" id="deleteOverlay" role="dialog" aria-modal="true">
  <div class="modal">
    <button type="button" class="modal-x" data-action="close-delete-modal">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
    <div class="modal-ico">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg>
    </div>
    <h3>Delete Product?</h3>
    <p>This will permanently remove <strong>{{ $categoryProduct->name }}</strong>. This action cannot be undone.</p>
    <div class="modal-acts">
      <button class="btn btn-secondary modal-cancel" data-action="close-delete-modal">Cancel</button>
      <button class="btn btn-red modal-del" data-action="confirm-delete">Yes, Delete</button>
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
          <div class="upload-zone" id="uploadZone">
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
              <button type="button" class="img-remove" data-action="clear-image-preview">
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
            <button type="button" class="btn btn-red" data-action="open-delete-modal">
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
@vite('resources/js/admin/entries/category-products-edit.js')
@endpush

