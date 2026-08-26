@push('page_css')
@vite('resources/css/admin/entries/categories-edit.css')
@endpush

@extends('layouts.admin')

@section('sidebar_categories', 'active')
@section('page_title', 'Edit Category')
@section('page_subtitle', 'Modify category')

@section('topbar_left')
  <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary back-btn">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5M12 19l-7-7 7-7"/></svg>
    All Categories
  </a>
@endsection

@section('content')
{{-- Delete modal --}}
<div class="overlay" id="deleteOverlay" role="dialog" aria-modal="true">
  <div class="modal">
    <button type="button" class="modal-x" data-action="close-modal"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
    <div class="modal-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg></div>
    <h3>Delete Category?</h3>
    <p>This will permanently remove <strong>{{ $category->name }}</strong>. Campaigns using this category may be affected.</p>
    <div class="modal-acts">
      <button class="btn btn-secondary modal-cancel" data-action="close-modal">Cancel</button>
      <button class="btn btn-red modal-del" data-action="submit-delete">Yes, Delete</button>
    </div>
  </div>
</div>
<form id="deleteForm" method="POST" action="{{ route('admin.categories.destroy',$category->id) }}" style="display:none;">@csrf @method('DELETE')</form>

<div class="breadcrumb">
  <a href="{{ route('admin.dashboard') }}">Dashboard</a>
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
  <a href="{{ route('admin.categories.index') }}">Categories</a>
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
  <span>Edit</span>
</div>

@if(session('success'))
<div class="alert-ok" id="flashAlert">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
  {{ session('success') }}
</div>
@endif

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

<form method="POST" action="{{ route('admin.categories.update',$category->id) }}" id="catForm">
@csrf @method('PUT')

@php
  $curIcon=old('icon',$category->icon??'fa-heart');
  $curColor=old('color',$category->color??'#2563eb ');
  $icons=['fa-heart'=>'Heart','fa-book'=>'Book','fa-paw'=>'Paw','fa-user'=>'Person','fa-hand-holding-heart'=>'Giving','fa-stethoscope'=>'Medical','fa-graduation-cap'=>'Education','fa-globe'=>'Global','fa-child'=>'Child','fa-hands-helping'=>'Helping','fa-tree'=>'Nature','fa-home'=>'Housing','fa-water'=>'Water','fa-fire'=>'Emergency','fa-church'=>'Religion','fa-bread-slice'=>'Food','fa-wheelchair'=>'Disability','fa-music'=>'Arts'];
  $colors=['#2563eb '=>'Purple','#0d9488'=>'Violet','#ec4899'=>'Pink','#f04444'=>'Red','#f59e0b'=>'Amber','#05c48a'=>'Emerald','#3b82f6'=>'Blue','#06b6d4'=>'Cyan','#84cc16'=>'Lime','#f97316'=>'Orange','#64748b'=>'Slate','#0f172a'=>'Dark'];
@endphp

<div class="page-grid">
  {{-- LEFT --}}
  <div>
    {{-- Basic Info --}}
    <div class="card">
      <div class="card-head">
        <div class="card-head-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
        <span class="card-head-title">Basic Information</span>
        <span class="mod-badge" id="modBadge"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>Modified</span>
      </div>
      <div class="card-body">
        <div class="field">
          <label class="f-label" for="name">Category Name <span class="req">*</span></label>
          <input id="name" name="name" type="text" value="{{ old('name',$category->name) }}"
            class="f-input {{ $errors->has('name')?'err':'' }}"
            placeholder="e.g. Medical, Education, Animal Welfare…"
            data-action="preview-name" required>
          @error('name')<p class="f-error">{{ $message }}</p>@enderror
          <p class="f-hint">Displayed on public campaign listing pages</p>
        </div>
        <div class="field">
          <div class="toggle-row">
            <div>
              <div class="toggle-lbl">Active</div>
              <div class="toggle-sub">Visible on the public site for campaign creation</div>
            </div>
            <div class="sw">
              <input type="checkbox" name="is_active" id="isActive" value="1"
                {{ old('is_active',$category->is_active)?'checked':'' }}
                data-action="preview-status">
              <label for="isActive"></label>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Icon Picker --}}
    <div class="card">
      <div class="card-head">
        <div class="card-head-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg></div>
        <span class="card-head-title">Icon</span>
      </div>
      <div class="card-body">
        <input type="hidden" name="icon" id="iconInput" value="{{ $curIcon }}">
        <p class="f-hint" style="margin-bottom:12px;">Select an icon that best represents this category</p>
        <div class="icon-grid">
          @foreach($icons as $icon=>$label)
          <div class="icon-tile {{ $curIcon===$icon?'selected':'' }}" data-action="select-icon" data-icon="{{ $icon }}" title="{{ $label }}">
            <i class="fa {{ $icon }}"></i>
          </div>
          @endforeach
        </div>
        <div class="icon-sel-row">
          <div class="icon-sel-prev" id="iconPreview"><i class="fa {{ $curIcon }}"></i></div>
          <div>
            <div style="font-size:12px;font-weight:600;color:var(--text);">Selected icon</div>
            <div id="iconName" style="font-size:11px;color:var(--text3);font-family:var(--mono);">{{ $curIcon }}</div>
          </div>
        </div>
      </div>
    </div>

    {{-- Color Picker --}}
    <div class="card">
      <div class="card-head">
        <div class="card-head-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg></div>
        <span class="card-head-title">Color</span>
      </div>
      <div class="card-body">
        <input type="hidden" name="color" id="colorInput" value="{{ $curColor }}">
        <p class="f-hint" style="margin-bottom:14px;">Used for the category icon background</p>
        <div class="color-grid">
          @foreach($colors as $hex=>$label)
          <div class="c-swatch {{ $curColor===$hex?'selected':'' }}" style="background:{{ $hex }};" data-action="select-color" data-color="{{ $hex }}" title="{{ $label }}"></div>
          @endforeach
        </div>
        <div class="custom-color-row" style="margin-top:16px;">
          <label style="font-size:11.5px;font-weight:600;color:var(--text2);font-family:var(--mono);text-transform:uppercase;letter-spacing:.06em;white-space:nowrap;">Custom hex</label>
          <input type="color" id="colorPicker" value="{{ $curColor }}" class="color-picker-input" data-action="custom-color">
          <input type="text" id="hexInput" class="f-input" value="{{ $curColor }}" style="font-family:var(--mono);font-size:12px;max-width:110px;" placeholder="#2563eb " data-action="sync-hex">
        </div>
      </div>
    </div>

    <button type="submit" class="submit-btn" id="submitBtn">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
      Save Changes
    </button>
  </div>

  {{-- RIGHT --}}
  <div style="display:flex;flex-direction:column;">
    {{-- Live Preview --}}
    <div class="preview-card">
      <div class="card-head">
        <div class="card-head-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg></div>
        <span class="card-head-title">Live Preview</span>
      </div>
      <div class="preview-live">
        <div class="prev-icon-box" id="previewBox" style="background:{{ $curColor }};"><i class="fa {{ $curIcon }}" id="previewIcon"></i></div>
        <div class="prev-name {{ $category->name?'':'empty' }}" id="previewName">{{ $category->name?:'Category name…' }}</div>
        <div style="margin-top:8px;">
          <span class="prev-badge {{ $category->is_active?'pb-active':'pb-inactive' }}" id="previewBadge">
            <span style="width:5px;height:5px;border-radius:50%;background:currentColor;display:inline-block;"></span>
            {{ $category->is_active?'Active':'Inactive' }}
          </span>
        </div>
      </div>
      <div class="preview-meta">
        <div class="prev-row">
          <span class="prev-row-lbl">Icon</span>
          <span class="prev-row-val" id="prevMetaIcon"><i class="fa {{ $curIcon }}" style="color:var(--a);"></i> {{ $curIcon }}</span>
        </div>
        <div class="prev-row">
          <span class="prev-row-lbl">Color</span>
          <span class="prev-row-val"><span class="prev-color-dot" id="prevColorDot" style="background:{{ $curColor }};"></span><span id="prevColorHex" style="font-family:var(--mono);font-size:11.5px;">{{ $curColor }}</span></span>
        </div>
        <div class="prev-row">
          <span class="prev-row-lbl">Status</span>
          <span class="prev-row-val" id="prevMetaStatus" style="color:{{ $category->is_active?'var(--green)':'var(--text3)' }};">{{ $category->is_active?'Active':'Inactive' }}</span>
        </div>
        <div class="prev-row">
          <span class="prev-row-lbl">Slug</span>
          <span class="prev-row-val" style="font-family:var(--mono);font-size:11.5px;color:var(--text3);">{{ $category->slug }}</span>
        </div>
      </div>
      <div class="pub-card-wrap">
        <div class="pub-card-lbl">Public site card</div>
        <div class="pub-card-inner">
          <div class="pub-icon" id="pubIconBox" style="background:{{ $curColor }};"><i class="fa {{ $curIcon }}" id="pubIcon"></i></div>
          <div>
            <div style="font-size:13px;font-weight:600;color:var(--text);" id="pubName">{{ $category->name }}</div>
            <div style="font-size:11.5px;color:var(--text3);margin-top:2px;">{{ $category->campaigns_count??0 }} campaigns</div>
          </div>
          <svg style="margin-left:auto;width:14px;height:14px;color:var(--text3);" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        </div>
      </div>
    </div>

    {{-- Danger Zone --}}
    <div class="danger-card">
      <div class="danger-head">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        Danger Zone
      </div>
      <div class="danger-body">
        <p class="danger-desc">Deleting this category is permanent and cannot be undone. Campaigns assigned to it may lose their category reference.</p>
        <button type="button" class="btn btn-red delete-btn" data-action="open-delete">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg>
          Delete "{{ $category->name }}"
        </button>
      </div>
    </div>
  </div>
</div>
</form>
{{-- Page data for categories-edit.js --}}
<script type="application/json" id="categoryEditData">
@php
    $categoryEditData = [
        'icon'   => old('icon', $category->icon ?? 'fa-heart'),
        'color'  => old('color', $category->color ?? '#2563eb '),
        'name'   => old('name', $category->name),
        'active' => (bool) old('is_active', $category->is_active),
    ];
@endphp
@json($categoryEditData)
</script>
@endsection

@push('page_scripts')
@vite('resources/js/admin/entries/categories-edit.js')
@endpush

@push('page_styles')
@vite('resources/css/admin/pages/categories-edit.css')
<style>
@media(max-width:860px){.page-grid{grid-template-columns:1fr!important}.page-grid>div:last-child{display:none}}
@media(max-width:640px){.card-body{padding:16px!important}.card-head{padding:12px 16px!important}.submit-btn{width:100%;justify-content:center}}
</style>
@endpush
