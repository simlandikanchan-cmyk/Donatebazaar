{{-- resources/views/admin/events/create.blade.php --}}
@extends('layouts.admin')

@section('sidebar_events', 'active')
@section('page_title', 'Create Event')
@section('page_subtitle', 'Add a new event')

@push('page_styles')
<style>
.stepper{display:flex;align-items:center;gap:12px;margin-bottom:24px;padding:6px;background:var(--surface);border:1px solid var(--border);border-radius:100px;box-shadow:var(--sh);animation:fadeUp .4s ease both;}
.step{display:flex;align-items:center;gap:8px;padding:10px 16px;border-radius:100px;cursor:pointer;transition:all var(--ease);flex:1;justify-content:center;}
.step-active{background:var(--a);color:#fff;box-shadow:0 4px 14px rgba(110,86,247,.3);}
.step-idle{opacity:.45;}.step-idle:hover{opacity:.75;}
.step-done{background:var(--green-lt);color:var(--green);cursor:pointer;}
.step-num{width:24px;height:24px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;font-family:var(--mono);flex-shrink:0;}
.step-active .step-num{background:rgba(255,255,255,.2);}
.step-done .step-num{background:var(--green);color:#fff;}
.step-idle .step-num{background:var(--surface3);color:var(--text3);}
.step-text{display:flex;flex-direction:column;}
.step-label{font-size:11px;font-weight:600;white-space:nowrap;}
.step-active .step-label{color:#fff;}.step-done .step-label{color:var(--green);}
.step-sublabel{font-size:9px;font-weight:500;opacity:.6;text-transform:uppercase;letter-spacing:.06em;font-family:var(--mono);display:none;}
.step-connector{width:24px;height:2px;background:var(--border2);border-radius:100px;position:relative;flex-shrink:0;}
.step-connector-fill{position:absolute;left:0;top:0;height:100%;border-radius:100px;background:var(--green);width:0;transition:width .4s;}
.step-connector-fill.filled{width:100%;}
.form-grid{display:grid;grid-template-columns:1fr 340px;gap:20px;align-items:start;}
.step-panel{display:none;animation:fadeUp .4s ease both;}
.step-panel.active{display:block;}
.card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh);overflow:hidden;animation:fadeUp .4s ease both;}
.card+.card{margin-top:16px;}
.card-header{padding:18px 22px;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:12px;}
.card-icon{width:36px;height:36px;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.card-icon svg{width:16px;height:16px;}
.ci-purple{background:var(--a-lt);color:var(--a);}
.ci-green{background:var(--green-lt);color:var(--green);}
.ci-amber{background:var(--amber-lt);color:var(--amber);}
.ci-blue{background:var(--blue-lt);color:var(--blue);}
.card-title{font-family:var(--mono);font-size:14px;font-weight:700;color:var(--text);letter-spacing:-.01em;}
.card-subtitle{font-size:11px;color:var(--text3);margin-top:2px;}
.card-body{padding:22px;}
.cat-search-wrap{position:relative;margin-bottom:16px;}
.cat-search-wrap svg{position:absolute;left:13px;top:50%;transform:translateY(-50%);width:14px;height:14px;color:var(--text3);pointer-events:none;}
.cat-search{width:100%;height:40px;background:var(--surface2);border:1.5px solid var(--border2);border-radius:var(--r-sm);padding:0 14px 0 37px;font-size:13px;color:var(--text);font-family:var(--font);outline:none;transition:border-color var(--ease),box-shadow var(--ease);}
.cat-search:focus{border-color:var(--a);box-shadow:0 0 0 3px var(--a-glow);}
.cat-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(140px,1fr));gap:10px;margin-bottom:4px;}
.cat-card{border:1.5px solid var(--border2);border-radius:var(--r-sm);padding:14px 12px;text-align:center;cursor:pointer;transition:all var(--ease);background:var(--surface2);}
.cat-card:hover{border-color:var(--a);background:var(--a-lt);transform:translateY(-2px);}
.cat-card.selected{border-color:var(--a);background:var(--a-lt);box-shadow:0 0 0 3px var(--a-glow);}
.cat-icon{font-size:22px;margin-bottom:8px;display:flex;align-items:center;justify-content:center;width:44px;height:44px;border-radius:11px;margin:0 auto 8px;}
.cat-name{font-size:12.5px;font-weight:600;color:var(--text);}
.cat-count{font-size:10px;color:var(--text3);font-family:var(--mono);margin-top:3px;}
.step-nav{display:flex;justify-content:space-between;align-items:center;margin-top:24px;padding-top:18px;border-top:1px solid var(--border);}
.field{margin-bottom:18px;}
.field:last-child{margin-bottom:0;}
.field-label{font-size:11.5px;font-weight:600;color:var(--text2);font-family:var(--mono);text-transform:uppercase;letter-spacing:.06em;margin-bottom:7px;display:flex;align-items:center;gap:6px;}
.field-label .req{color:var(--red);font-size:13px;}
.field-hint{font-size:11px;color:var(--text3);margin-top:5px;line-height:1.5;}
.inp{width:100%;height:42px;background:var(--surface2);border:1.5px solid var(--border2);border-radius:var(--r-sm);padding:0 14px;font-size:13.5px;color:var(--text);font-family:var(--font);outline:none;transition:border-color var(--ease),box-shadow var(--ease);}
.inp:focus{border-color:var(--a);box-shadow:0 0 0 3px var(--a-glow);}
.inp::placeholder{color:var(--text3);}
.inp-error{border-color:var(--red)!important;box-shadow:0 0 0 3px rgba(240,68,68,.12)!important;}
.textarea{width:100%;min-height:110px;resize:vertical;background:var(--surface2);border:1.5px solid var(--border2);border-radius:var(--r-sm);padding:12px 14px;font-size:13.5px;color:var(--text);font-family:var(--font);outline:none;transition:border-color var(--ease),box-shadow var(--ease);line-height:1.6;}
.textarea:focus{border-color:var(--a);box-shadow:0 0 0 3px var(--a-glow);}
.textarea::placeholder{color:var(--text3);}
.char-wrap{position:relative;}
.char-count{position:absolute;bottom:10px;right:12px;font-size:10px;font-family:var(--mono);color:var(--text3);}
.row-2{display:grid;grid-template-columns:1fr 1fr;gap:14px;}
.row-3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px;}
.upload-zone{border:2px dashed var(--border2);border-radius:var(--r-sm);padding:28px 20px;text-align:center;cursor:pointer;transition:all .2s ease;position:relative;overflow:hidden;background:var(--surface2);}
.upload-zone:hover{border-color:var(--a);background:var(--a-lt);}
.upload-zone.has-preview{border-style:solid;border-color:var(--a);padding:0;}
.upload-zone input{position:absolute;inset:0;opacity:0;cursor:pointer;}
.upload-icon{width:44px;height:44px;border-radius:12px;background:var(--a-lt);display:flex;align-items:center;justify-content:center;margin:0 auto 10px;}
.upload-icon svg{width:20px;height:20px;color:var(--a);}
.upload-text{font-size:13px;font-weight:600;color:var(--text2);}
.upload-sub{font-size:11px;color:var(--text3);margin-top:4px;}
.upload-preview{width:100%;height:160px;object-fit:cover;border-radius:calc(var(--r-sm) - 2px);display:none;}
.upload-preview.show{display:block;}
.upload-overlay{position:absolute;inset:0;background:rgba(0,0,0,.4);display:none;align-items:center;justify-content:center;border-radius:calc(var(--r-sm) - 2px);}
.upload-zone.has-preview:hover .upload-overlay{display:flex;}
.upload-overlay span{color:#fff;font-size:12px;font-weight:600;font-family:var(--mono);}
.draft-banner{background:linear-gradient(135deg,rgba(245,158,11,.08),rgba(245,158,11,.04));border:1px solid rgba(245,158,11,.25);border-radius:var(--r-sm);padding:14px 18px;display:flex;align-items:flex-start;gap:12px;margin-bottom:18px;}
.draft-banner svg{width:16px;height:16px;color:var(--amber);flex-shrink:0;margin-top:1px;}
.draft-banner-text{font-size:12.5px;color:var(--text2);line-height:1.6;}
.draft-banner-text strong{color:var(--amber);font-family:var(--mono);}
.toggle-row{display:flex;align-items:center;justify-content:space-between;padding:12px 0;border-bottom:1px solid var(--border);}
.toggle-row:last-child{border-bottom:none;}
.toggle-info .toggle-name{font-size:13px;font-weight:600;color:var(--text);}
.toggle-info .toggle-desc{font-size:11px;color:var(--text3);margin-top:2px;}
.toggle{position:relative;width:42px;height:24px;flex-shrink:0;}
.toggle input{position:absolute;opacity:0;width:0;height:0;}
.toggle-slider{position:absolute;inset:0;border-radius:100px;background:var(--surface3);border:1.5px solid var(--border2);cursor:pointer;transition:all .25s;}
.toggle-slider::after{content:'';position:absolute;width:16px;height:16px;border-radius:50%;background:#fff;left:3px;top:2px;transition:transform .25s;box-shadow:0 2px 4px rgba(0,0,0,.15);}
.toggle input:checked+.toggle-slider{background:var(--a);border-color:var(--a);}
.toggle input:checked+.toggle-slider::after{transform:translateX(18px);}
.summary-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh);overflow:hidden;position:sticky;top:82px;animation:fadeUp .4s .1s ease both;}
.summary-header{padding:16px 18px;border-bottom:1px solid var(--border);background:var(--surface2);}
.summary-title{font-family:var(--mono);font-size:13px;font-weight:700;color:var(--text);}
.summary-body{padding:16px 18px;}
.summary-item{display:flex;flex-direction:column;gap:3px;margin-bottom:14px;padding-bottom:14px;border-bottom:1px solid var(--border);}
.summary-item:last-child{border-bottom:none;margin-bottom:0;padding-bottom:0;}
.summary-key{font-size:10px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.08em;font-family:var(--mono);}
.summary-val{font-size:13px;font-weight:500;color:var(--text);font-family:var(--mono);}
.summary-val.empty{color:var(--text3);font-style:italic;font-weight:400;}
.summary-badge{display:inline-flex;align-items:center;gap:4px;padding:3px 8px;border-radius:6px;font-size:10px;font-weight:700;font-family:var(--mono);}
.sb-draft{background:var(--amber-lt);color:#b45309;}
.sb-publish{background:var(--green-lt);color:#059669;}
.action-bar{display:flex;gap:10px;align-items:center;flex-wrap:wrap;}
.btn{display:inline-flex;align-items:center;gap:8px;padding:11px 22px;border-radius:var(--r-sm);font-size:13px;font-weight:600;font-family:var(--font);cursor:pointer;border:none;transition:all var(--ease);}
.btn svg{width:14px;height:14px;}
.btn-publish{background:linear-gradient(135deg,var(--a),var(--a2));color:#fff;box-shadow:0 4px 18px rgba(110,86,247,.4);}
.btn-publish:hover{transform:translateY(-2px);box-shadow:0 8px 28px rgba(110,86,247,.5);}
.btn-draft{background:var(--amber-lt);color:#b45309;border:1px solid rgba(245,158,11,.3);}
.btn-draft:hover{background:rgba(245,158,11,.2);transform:translateY(-1px);}
.btn-ghost{background:var(--surface2);color:var(--text2);border:1px solid var(--border2);}
.btn-ghost:hover{background:var(--surface3);color:var(--text);}
.btn-next{background:linear-gradient(135deg,var(--a),var(--a2));color:#fff;box-shadow:0 4px 18px rgba(110,86,247,.35);}
.btn-next:hover{transform:translateY(-2px);}
.btn-back-step{background:var(--surface2);color:var(--text2);border:1px solid var(--border2);}
.btn-back-step:hover{background:var(--surface3);}
.btn-sm{padding:8px 16px;font-size:12px;}
.selected-badge{display:inline-flex;align-items:center;gap:6px;padding:5px 12px;background:var(--a-lt);border:1px solid rgba(110,86,247,.2);border-radius:var(--r-xs);font-size:12px;font-weight:600;color:var(--a);font-family:var(--mono);margin-bottom:14px;}
.selected-badge svg{width:12px;height:12px;}
.flash{padding:12px 16px;border-radius:var(--r-sm);margin-bottom:20px;font-size:13px;font-weight:500;display:flex;align-items:center;gap:10px;animation:fadeUp .3s ease both;}
.flash-error{background:var(--red-lt);border:1px solid rgba(240,68,68,.25);color:var(--red);}
.flash svg{width:14px;height:14px;flex-shrink:0;}
.error-msg{font-size:11px;color:var(--red);margin-top:5px;font-family:var(--mono);display:none;}
.error-msg.show{display:block;}
.campaign-list{margin-top:6px;}
.campaign-item{display:flex;align-items:center;gap:12px;padding:10px 12px;border:1.5px solid var(--border2);border-radius:var(--r-sm);margin-bottom:8px;cursor:pointer;transition:all var(--ease);background:var(--surface2);}
.campaign-item:hover{border-color:var(--a);background:var(--a-lt);}
.campaign-item.selected{border-color:var(--a);background:var(--a-lt);box-shadow:0 0 0 2px var(--a-glow);}
.campaign-thumb{width:40px;height:40px;border-radius:8px;object-fit:cover;flex-shrink:0;}
.campaign-thumb-placeholder{width:40px;height:40px;border-radius:8px;background:var(--surface3);display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.campaign-thumb-placeholder svg{width:16px;height:16px;color:var(--text3);opacity:.4;}
.campaign-info{flex:1;min-width:0;}
.campaign-title{font-size:12.5px;font-weight:600;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.campaign-meta{font-size:10px;color:var(--text3);font-family:var(--mono);margin-top:2px;}
.campaign-check{width:20px;height:20px;border-radius:5px;border:1.5px solid var(--border2);display:flex;align-items:center;justify-content:center;flex-shrink:0;opacity:0;transition:all var(--ease);}
.campaign-item.selected .campaign-check{opacity:1;background:var(--a);border-color:var(--a);}
.campaign-check svg{width:10px;height:10px;color:#fff;}
.no-campaigns{text-align:center;padding:40px 20px;color:var(--text3);font-size:13px;}
.no-campaigns svg{width:36px;height:36px;opacity:.2;margin:0 auto 10px;display:block;}
@media(max-width:860px){.form-grid{grid-template-columns:1fr}}
@media(max-width:600px){.row-2,.row-3{grid-template-columns:1fr}.stepper{gap:4px}.step{padding:8px 12px}}
</style>
@endpush
@section('content')
{{-- ── STEPPER ── --}}
<div class="stepper" id="stepper">
  <div class="step step-active" id="step-tab-1" onclick="goStep(1)">
    <div class="step-num">1</div>
    <div class="step-text">
      <div class="step-label">Category</div>
      <div class="step-sublabel">Pick a category</div>
    </div>
  </div>
  <div class="step-connector"><div class="step-connector-fill" id="conn-1"></div></div>
  <div class="step step-idle" id="step-tab-2" onclick="goStep(2)">
    <div class="step-num">2</div>
    <div class="step-text">
      <div class="step-label">Campaign</div>
      <div class="step-sublabel">Link a campaign</div>
    </div>
  </div>
  <div class="step-connector"><div class="step-connector-fill" id="conn-2"></div></div>
  <div class="step step-idle" id="step-tab-3" onclick="goStep(3)">
    <div class="step-num">3</div>
    <div class="step-text">
      <div class="step-label">Event Details</div>
      <div class="step-sublabel">Fill in the info</div>
    </div>
  </div>
  <div class="step-connector"><div class="step-connector-fill" id="conn-3"></div></div>
  <div class="step step-idle" id="step-tab-4" onclick="goStep(4)">
    <div class="step-num">4</div>
    <div class="step-text">
      <div class="step-label">Review & Publish</div>
      <div class="step-sublabel">Draft or go live</div>
    </div>
  </div>
</div>

@if ($errors->any())
<div class="flash flash-error">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
  Please fix the errors below before submitting.
</div>
@endif

{{-- THE FORM -- wraps all steps --}}
<form method="POST" action="{{ route('admin.events.store') }}" enctype="multipart/form-data" id="eventForm">
  @csrf
  {{-- Hidden status field controlled by buttons --}}
  <input type="hidden" name="status" id="statusField" value="draft">

  <div class="form-grid">
    {{-- ── LEFT: STEP PANELS ── --}}
    <div>

      {{-- ───────── STEP 1: CATEGORY ───────── --}}
      <div class="step-panel active" id="panel-1">
        <div class="card">
          <div class="card-header">
            <div class="card-icon ci-purple">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a2 2 0 012-2z"/></svg>
            </div>
            <div>
              <div class="card-title">Choose a Category</div>
              <div class="card-subtitle">Select the category this event belongs to — campaigns will filter accordingly</div>
            </div>
          </div>
          <div class="card-body">
            <div class="cat-search-wrap">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
              <input type="text" class="cat-search" id="catSearch" placeholder="Search categories…" autocomplete="off">
            </div>
            <input type="hidden" name="category_id" id="categoryInput" value="{{ old('category_id') }}">
            <div class="cat-grid" id="catGrid">
              @forelse($categories as $cat)
              <div class="cat-card {{ old('category_id') == $cat->id ? 'selected' : '' }}"
                   data-id="{{ $cat->id }}"
                   data-name="{{ strtolower($cat->name) }}"
                   onclick="selectCategory({{ $cat->id }}, '{{ addslashes($cat->name) }}', '{{ $cat->emoji ?? '' }}')">
                <div class="cat-icon" style="background:{{ $cat->color ?? 'var(--a-lt)' }}20;">
                  {{ $cat->emoji ?? '📁' }}
                </div>
                <div class="cat-name">{{ $cat->name }}</div>
                <div class="cat-count">{{ $cat->campaigns_count ?? 0 }} campaigns</div>
              </div>
              @empty
              <div style="grid-column:1/-1;text-align:center;padding:32px;color:var(--text3);font-size:13px;">
                No categories found.
              </div>
              @endforelse
            </div>
            @error('category_id')
              <div class="error-msg show">{{ $message }}</div>
            @enderror
          </div>
        </div>
        <div class="step-nav">
          <span></span>
          <button type="button" class="btn btn-next" onclick="nextStep(1)">
            Next: Pick Campaign
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
          </button>
        </div>
      </div>

      {{-- ───────── STEP 2: CAMPAIGN ───────── --}}
      <div class="step-panel" id="panel-2">
        <div class="card">
          <div class="card-header">
            <div class="card-icon ci-green">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            </div>
            <div>
              <div class="card-title">Select Campaign</div>
              <div class="card-subtitle" id="campaignSubtitle">Showing campaigns for selected category</div>
            </div>
          </div>
          <div class="card-body">
            <div id="selectedCatBadge" style="display:none;">
              <div class="selected-badge">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a2 2 0 012-2z"/></svg>
                <span id="selectedCatName"></span>
              </div>
            </div>
            <input type="hidden" name="campaign_id" id="campaignInput" value="{{ old('campaign_id') }}">
            <div class="campaign-list" id="campaignList">
              <div class="no-campaigns">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                Select a category first to see campaigns
              </div>
            </div>
            @error('campaign_id')
              <div class="error-msg show" style="margin-top:8px;">{{ $message }}</div>
            @enderror
          </div>
        </div>
        <div class="step-nav">
          <button type="button" class="btn btn-back-step" onclick="prevStep(2)">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            Back
          </button>
          <button type="button" class="btn btn-next" onclick="nextStep(2)">
            Next: Event Details
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
          </button>
        </div>
      </div>

      {{-- ───────── STEP 3: EVENT DETAILS ───────── --}}
      <div class="step-panel" id="panel-3">
        <div class="card" style="animation-delay:.05s;">
          <div class="card-header">
            <div class="card-icon ci-amber">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </div>
            <div>
              <div class="card-title">Event Information</div>
              <div class="card-subtitle">Core details about this event</div>
            </div>
          </div>
          <div class="card-body">
            <div class="field">
              <label class="field-label">Event Title <span class="req">*</span></label>
              <input type="text" name="title" class="inp {{ $errors->has('title') ? 'inp-error' : '' }}"
                placeholder="e.g. Annual Fundraising Gala 2025"
                value="{{ old('title') }}" maxlength="255" id="titleInp">
              @error('title')<div class="error-msg show">{{ $message }}</div>@enderror
            </div>

            <div class="field">
              <label class="field-label">Description <span class="req">*</span></label>
              <div class="char-wrap">
                <textarea name="description" class="textarea {{ $errors->has('description') ? 'inp-error' : '' }}"
                  placeholder="Describe what this event is about, who should attend, and what attendees can expect…"
                  maxlength="2000" id="descInp" rows="5">{{ old('description') }}</textarea>
                <span class="char-count" id="descCount">0 / 2000</span>
              </div>
              @error('description')<div class="error-msg show">{{ $message }}</div>@enderror
            </div>

            <div class="row-2">
              <div class="field">
                <label class="field-label">Event Date <span class="req">*</span></label>
                <input type="date" name="event_date" class="inp {{ $errors->has('event_date') ? 'inp-error' : '' }}"
                  value="{{ old('event_date') }}" min="{{ date('Y-m-d') }}">
                @error('event_date')<div class="error-msg show">{{ $message }}</div>@enderror
              </div>
              <div class="field">
                <label class="field-label">Location / Venue</label>
                <input type="text" name="location" class="inp"
                  placeholder="e.g. Mumbai Convention Centre"
                  value="{{ old('location') }}" maxlength="255">
              </div>
            </div>

            <div class="row-3">
              <div class="field">
                <label class="field-label">Start Time</label>
                <input type="time" name="start_time" class="inp" value="{{ old('start_time') }}">
              </div>
              <div class="field">
                <label class="field-label">End Time</label>
                <input type="time" name="end_time" class="inp" value="{{ old('end_time') }}">
              </div>
              <div class="field">
                <label class="field-label">Max Participants</label>
                <input type="number" name="max_participants" class="inp"
                  placeholder="Unlimited" min="1"
                  value="{{ old('max_participants') }}">
                <div class="field-hint">Leave blank for unlimited</div>
              </div>
            </div>

            <div class="field">
              <label class="field-label">Fundraising Goal (₹) <span class="req">*</span></label>
              <input type="number" name="goal_amount" class="inp {{ $errors->has('goal_amount') ? 'inp-error' : '' }}"
                placeholder="e.g. 100000" min="1" step="0.01"
                value="{{ old('goal_amount') }}" id="goalInp">
              @error('goal_amount')<div class="error-msg show">{{ $message }}</div>@enderror
            </div>
          </div>
        </div>

        {{-- Cover Image --}}
        <div class="card" style="margin-top:16px;animation-delay:.1s;">
          <div class="card-header">
            <div class="card-icon ci-blue">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
            </div>
            <div>
              <div class="card-title">Cover Image</div>
              <div class="card-subtitle">Recommended: 1200×600px, max 2MB</div>
            </div>
          </div>
          <div class="card-body">
            <div class="upload-zone" id="uploadZone">
              <input type="file" name="cover_image" id="coverInput" accept="image/*" onchange="previewImage(this)">
              <img src="" alt="Preview" class="upload-preview" id="uploadPreview">
              <div class="upload-overlay"><span>Click to change</span></div>
              <div id="uploadPlaceholder">
                <div class="upload-icon">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <div class="upload-text">Click to upload cover image</div>
                <div class="upload-sub">PNG, JPG, WEBP — max 2MB</div>
              </div>
            </div>
          </div>
        </div>

        <div class="step-nav">
          <button type="button" class="btn btn-back-step" onclick="prevStep(3)">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            Back
          </button>
          <button type="button" class="btn btn-next" onclick="nextStep(3)">
            Next: Review
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
          </button>
        </div>
      </div>

      {{-- ───────── STEP 4: REVIEW & PUBLISH ───────── --}}
      <div class="step-panel" id="panel-4">
        <div class="card" style="animation-delay:.05s;">
          <div class="card-header">
            <div class="card-icon ci-purple">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
              <div class="card-title">Review Your Event</div>
              <div class="card-subtitle">Check all details before publishing or saving as draft</div>
            </div>
          </div>
          <div class="card-body">
            {{-- Review preview --}}
            <div id="reviewContent" style="display:flex;flex-direction:column;gap:10px;">
              <div style="background:var(--surface2);border-radius:var(--r-sm);padding:16px 18px;">
                <div style="font-size:10px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.08em;font-family:var(--mono);margin-bottom:12px;">Event Overview</div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;" id="reviewGrid">
                  <div><div style="font-size:10px;color:var(--text3);font-family:var(--mono);margin-bottom:3px;">CATEGORY</div><div style="font-size:13px;font-weight:600;color:var(--text);" id="rv-cat">—</div></div>
                  <div><div style="font-size:10px;color:var(--text3);font-family:var(--mono);margin-bottom:3px;">CAMPAIGN</div><div style="font-size:13px;font-weight:600;color:var(--text);" id="rv-campaign">—</div></div>
                  <div><div style="font-size:10px;color:var(--text3);font-family:var(--mono);margin-bottom:3px;">EVENT TITLE</div><div style="font-size:13px;font-weight:600;color:var(--text);" id="rv-title">—</div></div>
                  <div><div style="font-size:10px;color:var(--text3);font-family:var(--mono);margin-bottom:3px;">DATE</div><div style="font-size:13px;font-weight:600;color:var(--text);" id="rv-date">—</div></div>
                  <div><div style="font-size:10px;color:var(--text3);font-family:var(--mono);margin-bottom:3px;">GOAL AMOUNT</div><div style="font-size:13px;font-weight:600;color:var(--text);" id="rv-goal">—</div></div>
                  <div><div style="font-size:10px;color:var(--text3);font-family:var(--mono);margin-bottom:3px;">PARTICIPANTS</div><div style="font-size:13px;font-weight:600;color:var(--text);" id="rv-participants">—</div></div>
                </div>
              </div>
            </div>

            {{-- Draft banner --}}
            <div class="draft-banner" style="margin-top:18px;">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              <div class="draft-banner-text">
                <strong>Not ready yet?</strong> Save as <strong>Draft</strong> — you can edit every detail and publish whenever you're ready. Drafts are invisible to the public until you publish them.
              </div>
            </div>

            {{-- Settings toggles --}}
            <div style="margin-top:18px;">
              <div style="font-size:11px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.08em;font-family:var(--mono);margin-bottom:10px;">Event Settings</div>
              <div class="toggle-row">
                <div class="toggle-info">
                  <div class="toggle-name">Allow Registrations</div>
                  <div class="toggle-desc">Participants can register for this event</div>
                </div>
                <label class="toggle">
                  <input type="checkbox" name="allow_registrations" value="1" checked>
                  <span class="toggle-slider"></span>
                </label>
              </div>
              <div class="toggle-row">
                <div class="toggle-info">
                  <div class="toggle-name">Show on Campaign Page</div>
                  <div class="toggle-desc">Display this event on the linked campaign page</div>
                </div>
                <label class="toggle">
                  <input type="checkbox" name="show_on_campaign" value="1" checked>
                  <span class="toggle-slider"></span>
                </label>
              </div>
              <div class="toggle-row">
                <div class="toggle-info">
                  <div class="toggle-name">Send Notification Email</div>
                  <div class="toggle-desc">Also email the campaign creator when published</div>
                </div>
                <label class="toggle">
                  <input type="checkbox" name="send_notification" value="1">
                  <span class="toggle-slider"></span>
                </label>
              </div>
            </div>
          </div>
        </div>

        {{-- Action bar --}}
        <div class="step-nav" style="flex-wrap:wrap;gap:10px;">
          <button type="button" class="btn btn-back-step" onclick="prevStep(4)">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            Back to Edit
          </button>
          <div class="action-bar">
            <button type="submit" class="btn btn-draft" onclick="setStatus('draft')">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
              Save as Draft
            </button>
            <button type="submit" class="btn btn-publish" onclick="setStatus('active')">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
              Publish Event
            </button>
          </div>
        </div>
      </div>

    </div>

    {{-- ── RIGHT: LIVE SUMMARY ── --}}
    <div>
      <div class="summary-card">
        <div class="summary-header">
          <div class="summary-title">Event Summary</div>
        </div>
        <div class="summary-body">
          <div class="summary-item">
            <div class="summary-key">Status</div>
            <span class="summary-badge sb-draft" id="summaryStatus">Draft</span>
          </div>
          <div class="summary-item">
            <div class="summary-key">Category</div>
            <div class="summary-val empty" id="sum-cat">Not selected</div>
          </div>
          <div class="summary-item">
            <div class="summary-key">Campaign</div>
            <div class="summary-val empty" id="sum-campaign">Not selected</div>
          </div>
          <div class="summary-item">
            <div class="summary-key">Title</div>
            <div class="summary-val empty" id="sum-title">Untitled event</div>
          </div>
          <div class="summary-item">
            <div class="summary-key">Date</div>
            <div class="summary-val empty" id="sum-date">Not set</div>
          </div>
          <div class="summary-item">
            <div class="summary-key">Goal</div>
            <div class="summary-val empty" id="sum-goal">Not set</div>
          </div>
          <div class="summary-item">
            <div class="summary-key">Max Participants</div>
            <div class="summary-val empty" id="sum-participants">Unlimited</div>
          </div>
        </div>
        <div style="padding:0 18px 16px;">
          <div style="font-size:11px;color:var(--text3);line-height:1.5;font-family:var(--mono);">
            Complete all steps and publish to make this event live, or save as draft to continue later.
          </div>
        </div>
      </div>
    </div>

  </div>{{-- /.form-grid --}}
</form>
@endsection

@push('page_scripts')
<script>
var campaignsData = @json($campaignsByCategory ?? []);
</script>
<script>
(function(){
'use strict';
var currentStep = 1;
var state = { categoryId: null, categoryName: '', campaignId: null, campaignName: '' };

function updateStepper(step) {
  for (var i = 1; i <= 4; i++) {
    var tab = document.getElementById('step-tab-' + i);
    tab.className = 'step ' + (i < step ? 'step-done' : i === step ? 'step-active' : 'step-idle');
    if (i < step) {
      tab.querySelector('.step-num').innerHTML = '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>';
    } else {
      tab.querySelector('.step-num').textContent = i;
    }
  }
  for (var c = 1; c <= 3; c++) {
    var fill = document.getElementById('conn-' + c);
    fill.className = 'step-connector-fill' + (c < step ? ' filled' : '');
  }
}

function showPanel(step) {
  document.querySelectorAll('.step-panel').forEach(function(p){ p.classList.remove('active'); });
  document.getElementById('panel-' + step).classList.add('active');
  updateStepper(step);
  currentStep = step;
  window.scrollTo({ top: 0, behavior: 'smooth' });
}

window.goStep = function(step) {
  if (step < currentStep) showPanel(step);
};

window.nextStep = function(from) {
  if (from === 1) {
    if (!state.categoryId) { alert('Please select a category first.'); return; }
    loadCampaigns(state.categoryId);
    showPanel(2);
  } else if (from === 2) {
    if (!state.campaignId) { alert('Please select a campaign first.'); return; }
    showPanel(3);
  } else if (from === 3) {
    if (!validateStep3()) return;
    populateReview();
    showPanel(4);
  }
};

window.prevStep = function(from) { showPanel(from - 1); };

function validateStep3() {
  var title = document.querySelector('[name="title"]').value.trim();
  var desc  = document.querySelector('[name="description"]').value.trim();
  var date  = document.querySelector('[name="event_date"]').value;
  var goal  = document.querySelector('[name="goal_amount"]').value;
  if (!title) { alert('Event title is required.'); return false; }
  if (!desc)  { alert('Description is required.'); return false; }
  if (!date)  { alert('Event date is required.'); return false; }
  if (!goal || parseFloat(goal) <= 0) { alert('A valid goal amount is required.'); return false; }
  return true;
}

window.selectCategory = function(id, name, emoji) {
  state.categoryId   = id;
  state.categoryName = emoji + ' ' + name;
  document.getElementById('categoryInput').value = id;
  document.querySelectorAll('.cat-card').forEach(function(c){ c.classList.remove('selected'); });
  document.querySelector('.cat-card[data-id="' + id + '"]').classList.add('selected');
  updateSummary();
};

document.getElementById('catSearch').addEventListener('input', function(){
  var q = this.value.toLowerCase();
  document.querySelectorAll('.cat-card').forEach(function(c){
    c.style.display = (!q || c.dataset.name.includes(q)) ? '' : 'none';
  });
});

function loadCampaigns(catId) {
  var list = document.getElementById('campaignList');
  var campaigns = campaignsData[catId] || [];
  document.getElementById('campaignSubtitle').textContent =
    campaigns.length + ' campaign' + (campaigns.length !== 1 ? 's' : '') + ' in ' + state.categoryName;
  var badge = document.getElementById('selectedCatBadge');
  badge.style.display = 'block';
  document.getElementById('selectedCatName').textContent = state.categoryName;
  if (!campaigns.length) {
    list.innerHTML = '<div class="no-campaigns"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>No active campaigns found for this category</div>';
    return;
  }
  var html = '';
  campaigns.forEach(function(c) {
    var thumb = c.cover_image
      ? '<img src="/storage/' + c.cover_image + '" class="campaign-thumb" alt="">'
      : '<div class="campaign-thumb-placeholder"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg></div>';
    var goal = c.goal_amount ? '₹' + Number(c.goal_amount).toLocaleString('en-IN') : '';
    html += '<div class="campaign-item" data-id="' + c.id + '" data-name="' + escHtml(c.title) + '" onclick="selectCampaign(' + c.id + ', \'' + escJs(c.title) + '\')">'
      + thumb
      + '<div class="campaign-info"><div class="campaign-title">' + escHtml(c.title) + '</div>'
      + '<div class="campaign-meta">' + (goal ? goal + ' goal' : '') + ' · active</div></div>'
      + '<div class="campaign-check"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></div>'
      + '</div>';
  });
  list.innerHTML = html;
}

window.selectCampaign = function(id, name) {
  state.campaignId   = id;
  state.campaignName = name;
  document.getElementById('campaignInput').value = id;
  document.querySelectorAll('.campaign-item').forEach(function(c){ c.classList.remove('selected'); });
  var el = document.querySelector('.campaign-item[data-id="' + id + '"]');
  if (el) el.classList.add('selected');
  updateSummary();
};

function updateSummary() {
  setText('sum-cat',          state.categoryName || null);
  setText('sum-campaign',     state.campaignName || null);
  setText('sum-title',        document.querySelector('[name="title"]').value.trim() || null);
  setText('sum-date',         document.querySelector('[name="event_date"]').value || null);
  var g = document.querySelector('[name="goal_amount"]').value;
  setText('sum-goal',         g ? '₹' + Number(g).toLocaleString('en-IN') : null);
  var mp = document.querySelector('[name="max_participants"]').value;
  setText('sum-participants', mp ? mp + ' max' : 'Unlimited');
}

function setText(id, val) {
  var el = document.getElementById(id);
  if (!el) return;
  if (val) { el.textContent = val; el.classList.remove('empty'); }
  else      { el.textContent = el.dataset.empty || 'Not set'; el.classList.add('empty'); }
}

function populateReview() {
  document.getElementById('rv-cat').textContent          = state.categoryName || '—';
  document.getElementById('rv-campaign').textContent     = state.campaignName || '—';
  document.getElementById('rv-title').textContent        = document.querySelector('[name="title"]').value || '—';
  document.getElementById('rv-date').textContent         = document.querySelector('[name="event_date"]').value || '—';
  var g  = document.querySelector('[name="goal_amount"]').value;
  document.getElementById('rv-goal').textContent         = g ? '₹' + Number(g).toLocaleString('en-IN') : '—';
  var mp = document.querySelector('[name="max_participants"]').value;
  document.getElementById('rv-participants').textContent = mp ? mp + ' max' : 'Unlimited';
}

['[name="title"]','[name="event_date"]','[name="goal_amount"]','[name="max_participants"]'].forEach(function(sel){
  var el = document.querySelector(sel);
  if (el) el.addEventListener('input', updateSummary);
});

window.setStatus = function(val) {
  document.getElementById('statusField').value = val;
  var badge = document.getElementById('summaryStatus');
  if (val === 'active') {
    badge.className = 'summary-badge sb-publish';
    badge.textContent = 'Active';
  } else {
    badge.className = 'summary-badge sb-draft';
    badge.textContent = 'Draft';
  }
};

window.previewImage = function(input) {
  if (!input.files || !input.files[0]) return;
  var reader = new FileReader();
  reader.onload = function(e) {
    var preview  = document.getElementById('uploadPreview');
    var zone     = document.getElementById('uploadZone');
    var placeholder = document.getElementById('uploadPlaceholder');
    preview.src = e.target.result;
    preview.classList.add('show');
    placeholder.style.display = 'none';
    zone.classList.add('has-preview');
  };
  reader.readAsDataURL(input.files[0]);
};

var descEl    = document.getElementById('descInp');
var descCount = document.getElementById('descCount');
if (descEl) {
  descEl.addEventListener('input', function(){
    descCount.textContent = this.value.length + ' / 2000';
  });
}

function escHtml(str) {
  return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}
function escJs(str) {
  return String(str).replace(/\\/g,'\\\\').replace(/'/g,"\\'");
}

['sum-cat','sum-campaign','sum-title','sum-date','sum-goal'].forEach(function(id){
  var el = document.getElementById(id);
  if (el) el.dataset.empty = el.textContent;
});

(function restoreOldValues(){
  var oldCatId   = document.getElementById('categoryInput').value;
  var oldCampId  = document.getElementById('campaignInput').value;
  if (oldCatId) {
    var catCard = document.querySelector('.cat-card[data-id="' + oldCatId + '"]');
    if (catCard) {
      var emoji = catCard.querySelector('.cat-icon').textContent.trim();
      var name  = catCard.querySelector('.cat-name').textContent.trim();
      state.categoryId   = parseInt(oldCatId);
      state.categoryName = emoji + ' ' + name;
      catCard.classList.add('selected');
    }
  }
  if (oldCampId) {
    state.campaignId = parseInt(oldCampId);
  }
  updateSummary();
})();

})();
</script>
@endpush
