@push('page_css')
@vite('resources/css/admin/entries/categories.css')
@endpush

@extends('layouts.admin')

@section('sidebar_categories', 'active')
@section('page_title', 'Create Category')
@section('page_subtitle', 'Add a new category')

@section('topbar_left')
  <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary back-btn">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5M12 19l-7-7 7-7"/></svg>
    All Categories
  </a>
@endsection

@section('content')
<div class="breadcrumb">
  <a href="{{ route('admin.dashboard') }}">Dashboard</a>
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
  <a href="{{ route('admin.categories.index') }}">Categories</a>
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
  <span>Create</span>
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

<form method="POST" action="{{ route('admin.categories.store') }}" id="catForm">
@csrf

<div class="page-grid">
  {{-- LEFT --}}
  <div>
    {{-- Basic Info --}}
    <div class="card">
      <div class="card-head">
        <div class="card-head-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
        <span class="card-head-title">Basic Information</span>
      </div>
      <div class="card-body">
        <div class="field">
          <label class="f-label" for="name">Category Name <span class="req">*</span></label>
          <input id="name" name="name" type="text" value="{{ old('name') }}"
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
              <input type="checkbox" name="is_active" id="isActive" value="1" checked data-action="preview-status">
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
        <input type="hidden" name="icon" id="iconInput" value="{{ old('icon','fa-heart') }}">
        <p class="f-hint" style="margin-bottom:12px;">Select an icon that best represents this category</p>
         @php $icons=['fa-heart'=>'Heart','fa-hand-holding-heart'=>'Giving','fa-hand-holding-usd'=>'Donate','fa-hands-helping'=>'Helping','fa-users'=>'Community','fa-user'=>'Person','fa-child'=>'Child','fa-baby'=>'Infant','fa-female'=>'Women','fa-blind'=>'Visually Impaired','fa-wheelchair'=>'Disability','fa-book'=>'Book','fa-graduation-cap'=>'Education','fa-school'=>'School','fa-lightbulb'=>'Ideas','fa-stethoscope'=>'Medical','fa-heartbeat'=>'Health','fa-hospital'=>'Hospital','fa-pills'=>'Medicine','fa-paw'=>'Paw','fa-dove'=>'Peace','fa-seedling'=>'Environment','fa-leaf'=>'Nature','fa-tree'=>'Forest','fa-recycle'=>'Recycling','fa-sun'=>'Solar','fa-tint'=>'Clean Water','fa-water'=>'Water','fa-utensils'=>'Meals','fa-bread-slice'=>'Food','fa-home'=>'Housing','fa-fire'=>'Emergency','fa-shield-alt'=>'Safety','fa-church'=>'Religion','fa-music'=>'Arts','fa-briefcase'=>'Livelihood','fa-brain'=>'Mental Health','fa-globe'=>'Global']; @endphp
        <div class="icon-grid">
          @foreach($icons as $icon=>$label)
          <div class="icon-tile {{ old('icon','fa-heart')===$icon?'selected':'' }}" data-action="select-icon" data-icon="{{ $icon }}" title="{{ $label }}">
            <i class="fa {{ $icon }}"></i>
          </div>
          @endforeach
        </div>
        <div class="icon-sel-row">
          <div class="icon-sel-prev" id="iconPreview"><i class="fa fa-heart"></i></div>
          <div>
            <div style="font-size:12px;font-weight:600;color:var(--text);">Selected icon</div>
            <div id="iconName" style="font-size:11px;color:var(--text3);font-family:var(--mono);">fa-heart</div>
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
        <input type="hidden" name="color" id="colorInput" value="{{ old('color','#2563eb ') }}">
        <p class="f-hint" style="margin-bottom:14px;">Used for the category icon background</p>
        @php $colors=['#2563eb '=>'Purple','#0d9488'=>'Violet','#ec4899'=>'Pink','#f04444'=>'Red','#f59e0b'=>'Amber','#05c48a'=>'Emerald','#3b82f6'=>'Blue','#06b6d4'=>'Cyan','#84cc16'=>'Lime','#f97316'=>'Orange','#64748b'=>'Slate','#0f172a'=>'Dark']; @endphp
        <div class="color-grid">
          @foreach($colors as $hex=>$label)
          <div class="c-swatch {{ old('color','#2563eb ')===$hex?'selected':'' }}" style="background:{{ $hex }};" data-action="select-color" data-color="{{ $hex }}" title="{{ $label }}"></div>
          @endforeach
        </div>
        <div class="custom-color-row" style="margin-top:16px;">
          <label style="font-size:11.5px;font-weight:600;color:var(--text2);font-family:var(--mono);text-transform:uppercase;letter-spacing:.06em;white-space:nowrap;">Custom hex</label>
          <input type="color" id="colorPicker" value="{{ old('color','#2563eb ') }}" class="color-picker-input" data-action="custom-color">
          <input type="text" id="hexInput" class="f-input" value="{{ old('color','#2563eb ') }}" style="font-family:var(--mono);font-size:12px;max-width:110px;" placeholder="#2563eb " data-action="sync-hex">
        </div>
      </div>
    </div>

    <button type="submit" class="submit-btn">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
      Create Category
    </button>
  </div>

  {{-- RIGHT — Live Preview --}}
  <div>
    <div class="preview-card">
      <div class="card-head">
        <div class="card-head-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg></div>
        <span class="card-head-title">Live Preview</span>
      </div>
      <div class="preview-live">
        <div class="prev-icon-box" id="previewBox" style="background:#2563eb ;"><i class="fa fa-heart" id="previewIcon"></i></div>
        <div class="prev-name empty" id="previewName">Category name…</div>
        <div style="margin-top:8px;">
          <span class="prev-badge pb-active" id="previewBadge"><span style="width:5px;height:5px;border-radius:50%;background:currentColor;display:inline-block;"></span> Active</span>
        </div>
      </div>
      <div class="preview-meta">
        <div class="prev-row">
          <span class="prev-row-lbl">Icon</span>
          <span class="prev-row-val" id="prevMetaIcon"><i class="fa fa-heart" style="color:var(--a);"></i> fa-heart</span>
        </div>
        <div class="prev-row">
          <span class="prev-row-lbl">Color</span>
          <span class="prev-row-val"><span class="prev-color-dot" id="prevColorDot" style="background:#2563eb ;"></span><span id="prevColorHex" style="font-family:var(--mono);font-size:11.5px;">#2563eb </span></span>
        </div>
        <div class="prev-row">
          <span class="prev-row-lbl">Status</span>
          <span class="prev-row-val" id="prevMetaStatus" style="color:var(--green);">Active</span>
        </div>
      </div>
      <div class="pub-card-wrap">
        <div class="pub-card-lbl">Public site card</div>
        <div class="pub-card-inner">
          <div class="pub-icon" id="pubIconBox" style="background:#2563eb ;"><i class="fa fa-heart" id="pubIcon"></i></div>
          <div>
            <div style="font-size:13px;font-weight:600;color:var(--text);" id="pubName">Category name</div>
            <div style="font-size:11.5px;color:var(--text3);margin-top:2px;">0 campaigns</div>
          </div>
          <svg style="margin-left:auto;width:14px;height:14px;color:var(--text3);" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        </div>
      </div>
    </div>
  </div>
</div>
</form>
{{-- Page data for categories-create.js --}}
<script type="application/json" id="categoryCreateData">
@php
    $categoryCreateData = [
        'icon'  => old('icon', 'fa-heart'),
        'color' => old('color', '#2563eb '),
    ];
@endphp
@json($categoryCreateData)
</script>
@endsection

@push('page_scripts')
@vite('resources/js/admin/entries/categories-create.js')
@endpush

@push('page_styles')
@vite('resources/css/admin/pages/categories-create.css')
<style>
@media(max-width:860px){.page-grid{grid-template-columns:1fr!important}.page-grid>div:last-child{display:none}}
@media(max-width:640px){.main-card>div{padding:16px!important}.card-head{padding:12px 16px!important}.submit-btn{width:100%;justify-content:center}.form-actions{flex-direction:column;align-items:stretch}.form-actions .btn-primary,.form-actions .btn-ghost{width:100%;justify-content:center}}
</style>
@endpush
