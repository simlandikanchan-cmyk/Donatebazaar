@extends('layouts.admin')

@push('page_css')
@vite('resources/css/admin/entries/categories.css')
@endpush


@section('sidebar_categories', 'active')
@section('page_title', 'Create Category')
@section('page_subtitle', 'Add a new category')

@section('topbar_left')
  <x-button variant="secondary" size="sm" href="{{ route('admin.categories.index') }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5M12 19l-7-7 7-7"/></svg>
    All Categories
  </x-button>
@endsection

@section('content')
@php
  $curIcon  = old('icon', 'fa-heart');
  $curColor = old('color', '#2563eb ');

  $commonIcons = [
    'fa-heart'=>'Heart','fa-hand-holding-heart'=>'Giving','fa-hands-helping'=>'Helping',
    'fa-users'=>'Community','fa-child'=>'Child','fa-stethoscope'=>'Medical',
    'fa-graduation-cap'=>'Education','fa-home'=>'Housing','fa-water'=>'Water',
    'fa-fire'=>'Emergency','fa-paw'=>'Paw','fa-seedling'=>'Environment','fa-lightbulb'=>'Ideas',
  ];

  $allIcons = [
    'fa-heart'=>'Heart','fa-hand-holding-heart'=>'Giving','fa-hand-holding-usd'=>'Donate',
    'fa-hands-helping'=>'Helping','fa-users'=>'Community','fa-user'=>'Person','fa-child'=>'Child',
    'fa-baby'=>'Infant','fa-female'=>'Women','fa-blind'=>'Visually Impaired','fa-wheelchair'=>'Disability',
    'fa-book'=>'Book','fa-graduation-cap'=>'Education','fa-school'=>'School','fa-lightbulb'=>'Ideas',
    'fa-stethoscope'=>'Medical','fa-heartbeat'=>'Health','fa-hospital'=>'Hospital','fa-pills'=>'Medicine',
    'fa-paw'=>'Paw','fa-dove'=>'Peace','fa-seedling'=>'Environment','fa-leaf'=>'Nature',
    'fa-tree'=>'Forest','fa-recycle'=>'Recycling','fa-sun'=>'Solar','fa-tint'=>'Clean Water',
    'fa-water'=>'Water','fa-utensils'=>'Meals','fa-bread-slice'=>'Food','fa-home'=>'Housing',
    'fa-fire'=>'Emergency','fa-shield-alt'=>'Safety','fa-church'=>'Religion','fa-music'=>'Arts',
    'fa-briefcase'=>'Livelihood','fa-brain'=>'Mental Health','fa-globe'=>'Global',
  ];

  $colors = [
    '#2563eb '=>'Blue','#3b82f6'=>'Sky','#06b6d4'=>'Cyan','#0d9488'=>'Teal','#05c48a'=>'Emerald',
    '#84cc16'=>'Lime','#f59e0b'=>'Amber','#f97316'=>'Orange','#f04444'=>'Red','#ec4899'=>'Pink',
    '#8b5cf6'=>'Violet','#64748b'=>'Slate','#0f172a'=>'Dark',
  ];
@endphp

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

<form method="POST" action="{{ route('admin.categories.store') }}" id="catForm" class="cat-form">
@csrf
<input type="hidden" name="icon" id="iconInput" value="{{ $curIcon }}">
<input type="hidden" name="color" id="colorInput" value="{{ trim($curColor) }}">

<div class="page-grid">
  {{-- LEFT — form --}}
  <div class="form-col-main">

    {{-- Basic Information --}}
    <div class="card">
      <div class="card-head">
        <div class="card-head-left">
          <div class="card-head-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
          <div class="card-head-txt">
            <span class="card-head-title">Basic Information</span>
            <span class="card-head-sub">Name and visibility used across the public site</span>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="field">
          <label class="f-label" for="name">Category Name <span class="req">*</span></label>
          <input id="name" name="name" type="text" value="{{ old('name') }}"
            class="f-input {{ $errors->has('name')?'err':'' }}"
            placeholder="e.g. Medical, Education, Animal Welfare…"
            oninput="updatePreviewName(this.value)" required autocomplete="off">
          @error('name')<p class="f-error">{{ $message }}</p>@enderror
          <p class="f-hint">Displayed on public campaign listing pages</p>
        </div>
        <div class="field">
          <div class="toggle-row">
            <div class="toggle-row-info">
              <div class="toggle-lbl">Active</div>
              <div class="toggle-sub">Visible on the public site for campaign creation</div>
            </div>
            <div class="sw">
              <input type="checkbox" name="is_active" id="isActive" value="1" checked onchange="updatePreviewStatus(this.checked)">
              <label for="isActive"></label>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Appearance (icon + color) --}}
    <div class="card">
      <div class="card-head">
        <div class="card-head-left">
          <div class="card-head-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg></div>
          <div class="card-head-txt">
            <span class="card-head-title">Appearance</span>
            <span class="card-head-sub">Icon and color shown on category cards</span>
          </div>
        </div>
      </div>
      <div class="card-body">

        {{-- Icon picker --}}
        <div class="section-block" id="iconSection">
          <div class="section-head">
            <span class="section-lbl">Icon</span>
            <span class="section-hint">{{ count($allIcons) }} symbols available</span>
          </div>
          <div class="icon-search">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35"/></svg>
            <input type="search" id="iconSearch" placeholder="Search icons…" aria-label="Search icons" autocomplete="off">
            <button type="button" class="icon-search-clear" id="iconSearchClear" aria-label="Clear search" hidden>✕</button>
          </div>

          <div class="icon-group" id="groupCommon">
            <span class="icon-group-lbl" id="groupCommonLbl">Common</span>
            <div class="icon-grid">
              @foreach($commonIcons as $icon=>$label)
              <button type="button" class="icon-tile {{ $curIcon===$icon?'selected':'' }}" data-icon="{{ $icon }}" data-label="{{ $label }}"
                aria-label="{{ $label }} icon" aria-pressed="{{ $curIcon===$icon?'true':'false' }}" title="{{ $label }}"
                onclick="selectIcon(this,'{{ $icon }}')">
                <i class="fa-solid {{ $icon }}"></i>
              </button>
              @endforeach
            </div>
          </div>

          <div class="icon-group" id="groupAll">
            <span class="icon-group-lbl" id="groupAllLbl">All icons</span>
            <div class="icon-grid">
              @foreach($allIcons as $icon=>$label)
              <button type="button" class="icon-tile {{ $curIcon===$icon?'selected':'' }}" data-icon="{{ $icon }}" data-label="{{ $label }}"
                aria-label="{{ $label }} icon" aria-pressed="{{ $curIcon===$icon?'true':'false' }}" title="{{ $label }}"
                onclick="selectIcon(this,'{{ $icon }}')">
                <i class="fa-solid {{ $icon }}"></i>
              </button>
              @endforeach
            </div>
            <p class="icon-empty" id="iconEmpty" hidden>No icons match your search.</p>
          </div>
        </div>

        <div class="section-divider" role="separator"></div>

        {{-- Color picker --}}
        <div class="section-block" id="colorSection">
          <div class="section-head">
            <span class="section-lbl">Color</span>
            <span class="section-hint">Icon background tint</span>
          </div>
          <div class="color-grid" role="group" aria-label="Color palette">
            @foreach($colors as $hex=>$label)
            <button type="button" class="c-swatch {{ trim($curColor)===$hex?'selected':'' }}" style="background:{{ $hex }};"
              data-hex="{{ $hex }}" data-label="{{ $label }}" aria-label="{{ $label }} color {{ $hex }}"
              aria-pressed="{{ trim($curColor)===$hex?'true':'false' }}" title="{{ $label }}"
              onclick="selectColor(this,'{{ $hex }}')"></button>
            @endforeach
          </div>
          <div class="color-readout" aria-live="polite">
            <span class="color-readout-swatch" id="colorReadoutSwatch" style="background:{{ trim($curColor) }};"></span>
            <span class="color-readout-name" id="colorReadoutName">{{ $colors[trim($curColor)] ?? 'Custom' }}</span>
            <code class="color-readout-hex" id="colorReadoutHex">{{ trim($curColor) }}</code>
          </div>
          <div class="custom-color-row">
            <label for="colorPicker" class="custom-color-lbl">Custom</label>
            <input type="color" id="colorPicker" value="{{ trim($curColor) }}" class="color-picker-input" oninput="selectCustomColor(this.value)" aria-label="Custom color picker">
            <input type="text" id="hexInput" class="f-input hex-input" value="{{ trim($curColor) }}" placeholder="#2563eb" maxlength="7" oninput="syncHexInput(this.value)" aria-label="Hex color value">
          </div>
        </div>
      </div>
    </div>

    {{-- Sticky actions --}}
    <div class="form-actions-bar">
      <div class="form-actions-info">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01"/></svg>
        <span>Fields marked <span class="req">*</span> are required</span>
      </div>
      <div class="form-actions-btns">
        <x-button variant="ghost" href="{{ route('admin.categories.index') }}">Cancel</x-button>
        <x-button variant="primary" type="submit" id="submitBtn">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
          Create Category
        </x-button>
      </div>
    </div>
  </div>

  {{-- RIGHT — sticky Live Preview --}}
  <div class="preview-col">
    <div class="preview-card">
      <div class="card-head">
        <div class="card-head-left">
          <div class="card-head-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg></div>
          <div class="card-head-txt">
            <span class="card-head-title">Live Preview</span>
            <span class="card-head-sub">Updates as you edit</span>
          </div>
        </div>
      </div>
      <div class="preview-live" id="previewLive">
        <div class="prev-main">
          <div class="prev-icon-box" id="previewBox" style="background:{{ trim($curColor) }};"><i class="fa-solid {{ $curIcon }}" id="previewIcon"></i></div>
          <div class="prev-main-txt">
            <div class="prev-name empty" id="previewName">Category name…</div>
            <div class="prev-slug" id="previewSlug">/category-slug</div>
          </div>
          <span class="prev-badge pb-active" id="previewBadge"><span style="width:5px;height:5px;border-radius:50%;background:currentColor;display:inline-block;"></span> Active</span>
        </div>
        <div class="prev-live-foot">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
          Live preview updates as you type
        </div>
      </div>
      <div class="preview-meta">
        <div class="prev-row">
          <span class="prev-row-lbl">Icon</span>
          <span class="prev-row-val" id="prevMetaIcon"><i class="fa-solid {{ $curIcon }}" style="color:var(--a);"></i> {{ $curIcon }}</span>
        </div>
        <div class="prev-row">
          <span class="prev-row-lbl">Color</span>
          <span class="prev-row-val"><span class="prev-color-dot" id="prevColorDot" style="background:{{ trim($curColor) }};"></span><span id="prevColorHex" style="font-size:11px;">{{ trim($curColor) }}</span></span>
        </div>
        <div class="prev-row">
          <span class="prev-row-lbl">Status</span>
          <span class="prev-row-val" id="prevMetaStatus" style="color:var(--green);">Active</span>
        </div>
        <div class="prev-row">
          <span class="prev-row-lbl">Slug</span>
          <span class="prev-row-val" id="prevMetaSlug" style="color:var(--text3);font-size:11px;">category-slug</span>
        </div>
      </div>
      <div class="pub-card-wrap">
        <span class="pub-card-lbl">Public site card</span>
        <div class="pub-card-inner" id="pubCard">
          <div class="pub-icon" id="pubIconBox" style="background:{{ trim($curColor) }};"><i class="fa-solid {{ $curIcon }}" id="pubIcon"></i></div>
          <div class="pub-card-body">
            <div class="pub-name" id="pubName">Category name</div>
            <div class="pub-count" id="pubCount">0 Campaigns</div>
          </div>
          <span class="pub-arrow" aria-hidden="true"></span>
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

var state = {
  icon:  {!! json_encode($curIcon) !!},
  color: {!! json_encode(trim($curColor)) !!},
  name:  '',
  active: true
};

function slugify(v){
  return v.toLowerCase().trim()
    .replace(/['"]/g, '')
    .replace(/[^a-z0-9]+/g, '-')
    .replace(/^-+|-+$/g, '');
}

function isDark(hex){
  var h = (hex || '').replace('#', '');
  if (!/^[0-9a-fA-F]{3}$/.test(h) && !/^[0-9a-fA-F]{6}$/.test(h)) return true;
  if (h.length === 3) h = h.split('').map(function(x){ return x + x; }).join('');
  var r = parseInt(h.substr(0, 2), 16),
      g = parseInt(h.substr(2, 2), 16),
      b = parseInt(h.substr(4, 2), 16);
  return (0.299 * r + 0.587 * g + 0.114 * b) < 150;
}

function flashPreview(){
  var live = document.getElementById('previewLive');
  live.classList.remove('is-updating');
  void live.offsetWidth;
  live.classList.add('is-updating');
}

function updatePreview(){
  var c = state.color, ic = state.icon, nm = state.name || '';
  var box = document.getElementById('previewBox');
  var pubBox = document.getElementById('pubIconBox');
  box.style.background = c;
  pubBox.style.background = c;
  var dark = isDark(c);
  document.getElementById('previewIcon').style.color = dark ? '#fff' : '#0a0b14';
  document.getElementById('pubIcon').style.color = dark ? '#fff' : '#0a0b14';

  document.getElementById('previewIcon').className = 'fa-solid ' + ic;
  document.getElementById('pubIcon').className = 'fa-solid ' + ic;

  var nameEl = document.getElementById('previewName');
  nameEl.textContent = nm || 'Category name…';
  nameEl.classList.toggle('empty', !nm);

  var slug = nm ? slugify(nm) : 'category-slug';
  document.getElementById('previewSlug').textContent = '/' + slug;
  document.getElementById('prevMetaSlug').textContent = slug;

  var badge = document.getElementById('previewBadge');
  var statusEl = document.getElementById('prevMetaStatus');
  if (state.active){
    badge.className = 'prev-badge pb-active';
    badge.innerHTML = '<span style="width:5px;height:5px;border-radius:50%;background:currentColor;display:inline-block;"></span> Active';
    statusEl.style.color = 'var(--green)';
    statusEl.textContent = 'Active';
  } else {
    badge.className = 'prev-badge pb-inactive';
    badge.innerHTML = '<span style="width:5px;height:5px;border-radius:50%;background:currentColor;display:inline-block;"></span> Inactive';
    statusEl.style.color = 'var(--text3)';
    statusEl.textContent = 'Inactive';
  }

  document.getElementById('prevMetaIcon').innerHTML = '<i class="fa-solid ' + ic + '" style="color:var(--a);"></i> ' + ic;
  document.getElementById('prevColorDot').style.background = c;
  document.getElementById('prevColorHex').textContent = c;
  document.getElementById('pubName').textContent = nm || 'Category name';
}

function syncReadout(){
  var c = state.color;
  document.getElementById('colorReadoutSwatch').style.background = c;
  document.getElementById('colorReadoutHex').textContent = c;
  var nameEl = document.getElementById('colorReadoutName');
  var label = '';
  document.querySelectorAll('.c-swatch').forEach(function(s){
    if (s.dataset.hex === c) label = s.dataset.label || '';
  });
  nameEl.textContent = label || 'Custom';
}

window.updatePreviewName = function(v){
  state.name = v.trim();
  updatePreview();
};
window.updatePreviewStatus = function(v){
  state.active = v;
  updatePreview();
};
window.selectIcon = function(el, icon){
  state.icon = icon;
  document.getElementById('iconInput').value = icon;
  document.querySelectorAll('.icon-tile').forEach(function(t){
    var sel = t.dataset.icon === icon;
    t.classList.toggle('selected', sel);
    t.setAttribute('aria-pressed', sel ? 'true' : 'false');
  });
  updatePreview();
  flashPreview();
};
window.selectColor = function(el, hex){
  state.color = hex;
  document.getElementById('colorInput').value = hex;
  document.getElementById('colorPicker').value = hex;
  document.getElementById('hexInput').value = hex;
  document.querySelectorAll('.c-swatch').forEach(function(s){
    var sel = s.dataset.hex === hex;
    s.classList.toggle('selected', sel);
    s.setAttribute('aria-pressed', sel ? 'true' : 'false');
    s.style.setProperty('--sw-check', isDark(hex) ? '#fff' : '#0f1117');
  });
  syncReadout();
  updatePreview();
  flashPreview();
};
window.selectCustomColor = function(hex){
  state.color = hex;
  document.getElementById('colorInput').value = hex;
  document.getElementById('hexInput').value = hex;
  document.getElementById('hexInput').classList.remove('err');
  document.querySelectorAll('.c-swatch').forEach(function(s){
    s.classList.remove('selected');
    s.setAttribute('aria-pressed', 'false');
    s.style.setProperty('--sw-check', isDark(hex) ? '#fff' : '#0f1117');
  });
  syncReadout();
  updatePreview();
};
window.syncHexInput = function(val){
  var m = /^#([0-9a-fA-F]{6})$/.exec(val.trim());
  if (!m){
    document.getElementById('hexInput').classList.add('err');
    return;
  }
  document.getElementById('hexInput').classList.remove('err');
  selectCustomColor('#' + m[1].toLowerCase());
};

/* ── Icon search ── */
var search = document.getElementById('iconSearch');
var clearBtn = document.getElementById('iconSearchClear');

function filterIcons(){
  var q = search.value.trim().toLowerCase();
  var common = 0, all = 0;
  document.querySelectorAll('.icon-tile').forEach(function(t){
    var hit = !q ||
      t.dataset.icon.toLowerCase().indexOf(q) !== -1 ||
      (t.dataset.label || '').toLowerCase().indexOf(q) !== -1;
    t.hidden = !hit;
    if (!hit) return;
    if (t.closest('#groupCommon')) common++;
    else all++;
  });
  document.getElementById('groupCommon').hidden = common === 0;
  document.getElementById('groupAll').hidden = all === 0;
  document.getElementById('iconEmpty').hidden = (common + all) > 0;
  clearBtn.hidden = !q;
}
search.addEventListener('input', filterIcons);
clearBtn.addEventListener('click', function(){
  search.value = '';
  filterIcons();
  search.focus();
});

/* ── Submit state ── */
document.getElementById('catForm').addEventListener('submit', function(){
  var b = document.getElementById('submitBtn');
  b.disabled = true;
  b.setAttribute('aria-busy', 'true');
  var lbl = b.querySelector('.btn__label');
  if (lbl){
    lbl.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="14" height="14"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg> Creating…';
  }
});

/* ── Init: contrast + selected state ── */
document.querySelectorAll('.c-swatch').forEach(function(s){
  s.style.setProperty('--sw-check', isDark(s.dataset.hex) ? '#fff' : '#0f1117');
});

updatePreview();
syncReadout();
})();
</script>
@endpush
