{{-- resources/views/admin/events/edit.blade.php --}}
@extends('layouts.admin')

@section('sidebar_events', 'active')
@section('page_title', 'Edit Event')
@section('page_subtitle', 'Modify event details')

@push('page_styles')
<style>
.form-grid{display:grid;grid-template-columns:1fr 340px;gap:20px;align-items:start;}
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
.sel{width:100%;height:42px;background:var(--surface2);border:1.5px solid var(--border2);border-radius:var(--r-sm);padding:0 14px;font-size:13.5px;color:var(--text);font-family:var(--font);outline:none;cursor:pointer;transition:border-color var(--ease),box-shadow var(--ease);}
.sel:focus{border-color:var(--a);box-shadow:0 0 0 3px var(--a-glow);}
.row-2{display:grid;grid-template-columns:1fr 1fr;gap:14px;}
.row-3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px;}
.status-opts{display:flex;gap:8px;flex-wrap:wrap;}
.status-opt{flex:1;min-width:90px;border:2px solid var(--border2);border-radius:var(--r-sm);padding:10px 14px;cursor:pointer;transition:all .2s;display:flex;align-items:center;gap:8px;font-size:12.5px;font-weight:600;font-family:var(--mono);color:var(--text2);background:var(--surface2);}
.status-opt:hover{border-color:var(--a);background:var(--a-lt);color:var(--a);}
.status-opt.selected-status{border-color:var(--a);background:var(--a-lt);color:var(--a);}
.status-opt.sel-draft.selected-status{border-color:var(--amber);background:var(--amber-lt);color:#b45309;}
.status-opt.sel-active.selected-status{border-color:var(--green);background:var(--green-lt);color:#059669;}
.status-opt.sel-cancelled.selected-status{border-color:var(--red);background:var(--red-lt);color:var(--red);}
.status-dot{width:8px;height:8px;border-radius:50%;flex-shrink:0;}
.dot-draft{background:var(--amber);}
.dot-active{background:var(--green);}
.dot-cancelled{background:var(--red);}
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
.summary-badge{display:inline-flex;align-items:center;gap:4px;padding:3px 8px;border-radius:6px;font-size:10px;font-weight:700;font-family:var(--mono);}
.sb-draft{background:var(--amber-lt);color:#b45309;}
.sb-active{background:var(--green-lt);color:#059669;}
.btn{display:inline-flex;align-items:center;gap:8px;padding:11px 22px;border-radius:var(--r-sm);font-size:13px;font-weight:600;font-family:var(--font);cursor:pointer;border:none;transition:all var(--ease);}
.btn svg{width:14px;height:14px;}
.btn-publish{background:linear-gradient(135deg,var(--a),var(--a2));color:#fff;box-shadow:0 4px 18px rgba(110,86,247,.4);}
.btn-publish:hover{transform:translateY(-2px);box-shadow:0 8px 28px rgba(110,86,247,.5);}
.btn-draft{background:var(--amber-lt);color:#b45309;border:1px solid rgba(245,158,11,.3);}
.btn-draft:hover{background:rgba(245,158,11,.2);transform:translateY(-1px);}
.btn-ghost{background:var(--surface2);color:var(--text2);border:1px solid var(--border2);}
.btn-ghost:hover{background:var(--surface3);color:var(--text);}
.btn-danger{background:var(--red-lt);color:var(--red);border:1px solid rgba(240,68,68,.25);}
.btn-danger:hover{background:rgba(240,68,68,.18);}
.btn-sm{padding:8px 16px;font-size:12px;}
.action-bar{display:flex;gap:10px;align-items:center;flex-wrap:wrap;padding-top:20px;border-top:1px solid var(--border);margin-top:24px;}
.current-img-wrap{margin-bottom:14px;}
.current-img{width:100%;height:150px;object-fit:cover;border-radius:var(--r-sm);border:1px solid var(--border2);}
.current-img-label{font-size:10.5px;color:var(--text3);font-family:var(--mono);margin-top:6px;}
.error-msg{font-size:11px;color:var(--red);margin-top:5px;font-family:var(--mono);display:none;}
.error-msg.show{display:block;}
.flash{padding:12px 16px;border-radius:var(--r-sm);margin-bottom:20px;font-size:13px;font-weight:500;display:flex;align-items:center;gap:10px;animation:fadeUp .3s ease both;}
.flash-error{background:var(--red-lt);border:1px solid rgba(240,68,68,.25);color:var(--red);}
.flash-success{background:var(--green-lt);border:1px solid rgba(5,196,138,.25);color:#059669;}
.flash svg{width:14px;height:14px;flex-shrink:0;}
@media(max-width:860px){.form-grid{grid-template-columns:1fr}}
@media(max-width:600px){.row-2,.row-3{grid-template-columns:1fr}}
</style>
@endpush
@section('content')
@if(session('success'))
<div class="flash flash-success">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
  {{ session('success') }}
</div>
@endif

@if($errors->any())
<div class="flash flash-error">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
  Please fix the errors below before saving.
</div>
@endif

<form method="POST" action="{{ route('admin.events.update', $event) }}" enctype="multipart/form-data" id="editForm">
  @csrf
  @method('PUT')

  <div class="form-grid">

    {{-- ── LEFT: FORM SECTIONS ── --}}
    <div>

      {{-- ── BASIC INFO ── --}}
      <div class="card">
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
              value="{{ old('title', $event->title) }}" maxlength="255" id="titleInp">
            @error('title')<div class="error-msg show">{{ $message }}</div>@enderror
          </div>

          <div class="field">
            <label class="field-label">Description <span class="req">*</span></label>
            <div class="char-wrap">
              <textarea name="description" class="textarea {{ $errors->has('description') ? 'inp-error' : '' }}"
                placeholder="Describe what this event is about…"
                maxlength="2000" id="descInp" rows="5">{{ old('description', $event->description) }}</textarea>
              <span class="char-count" id="descCount">{{ strlen(old('description', $event->description ?? '')) }} / 2000</span>
            </div>
            @error('description')<div class="error-msg show">{{ $message }}</div>@enderror
          </div>

          <div class="row-2">
            <div class="field">
              <label class="field-label">Category <span class="req">*</span></label>
              <select name="category_id" class="sel {{ $errors->has('category_id') ? 'inp-error' : '' }}">
                <option value="">Select category…</option>
                @foreach($categories as $cat)
                  <option value="{{ $cat->id }}" {{ old('category_id', $event->category_id) == $cat->id ? 'selected' : '' }}>
                    {{ $cat->emoji ?? '' }} {{ $cat->name }}
                  </option>
                @endforeach
              </select>
              @error('category_id')<div class="error-msg show">{{ $message }}</div>@enderror
            </div>
            <div class="field">
              <label class="field-label">Campaign <span class="req">*</span></label>
              <select name="campaign_id" class="sel {{ $errors->has('campaign_id') ? 'inp-error' : '' }}" id="campaignSel">
                <option value="">Select campaign…</option>
                @foreach($campaigns as $camp)
                  <option value="{{ $camp->id }}" {{ old('campaign_id', $event->campaign_id) == $camp->id ? 'selected' : '' }}>
                    {{ $camp->title }}
                  </option>
                @endforeach
              </select>
              @error('campaign_id')<div class="error-msg show">{{ $message }}</div>@enderror
            </div>
          </div>

          <div class="row-2">
            <div class="field">
              <label class="field-label">Event Date <span class="req">*</span></label>
              <input type="date" name="event_date" class="inp {{ $errors->has('event_date') ? 'inp-error' : '' }}"
                value="{{ old('event_date', $event->event_date?->format('Y-m-d')) }}">
              @error('event_date')<div class="error-msg show">{{ $message }}</div>@enderror
            </div>
            <div class="field">
              <label class="field-label">Location / Venue</label>
              <input type="text" name="location" class="inp"
                placeholder="e.g. Mumbai Convention Centre"
                value="{{ old('location', $event->location) }}" maxlength="255">
            </div>
          </div>

          <div class="row-3">
            <div class="field">
              <label class="field-label">Start Time</label>
              <input type="time" name="start_time" class="inp" value="{{ old('start_time', $event->start_time) }}">
            </div>
            <div class="field">
              <label class="field-label">End Time</label>
              <input type="time" name="end_time" class="inp" value="{{ old('end_time', $event->end_time) }}">
            </div>
            <div class="field">
              <label class="field-label">Max Participants</label>
              <input type="number" name="max_participants" class="inp"
                placeholder="Unlimited" min="1"
                value="{{ old('max_participants', $event->max_participants) }}">
              <div class="field-hint">Leave blank for unlimited</div>
            </div>
          </div>

          <div class="field">
            <label class="field-label">Fundraising Goal (₹) <span class="req">*</span></label>
            <input type="number" name="goal_amount" class="inp {{ $errors->has('goal_amount') ? 'inp-error' : '' }}"
              placeholder="e.g. 100000" min="1" step="0.01"
              value="{{ old('goal_amount', $event->goal_amount) }}" id="goalInp">
            @error('goal_amount')<div class="error-msg show">{{ $message }}</div>@enderror
          </div>

        </div>
      </div>

      {{-- ── STATUS ── --}}
      <div class="card" style="animation-delay:.05s;">
        <div class="card-header">
          <div class="card-icon ci-purple">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          </div>
          <div>
            <div class="card-title">Event Status</div>
            <div class="card-subtitle">Control visibility and registration availability</div>
          </div>
        </div>
        <div class="card-body">
          <input type="hidden" name="status" id="statusHidden" value="{{ old('status', $event->status) }}">
          <div class="status-opts">
            <div class="status-opt sel-draft {{ old('status', $event->status) === 'draft' ? 'selected-status' : '' }}" onclick="setStatus('draft')">
              <span class="status-dot dot-draft"></span> Draft
            </div>
            <div class="status-opt sel-active {{ old('status', $event->status) === 'active' ? 'selected-status' : '' }}" onclick="setStatus('active')">
              <span class="status-dot dot-active"></span> Active
            </div>
            <div class="status-opt sel-cancelled {{ old('status', $event->status) === 'cancelled' ? 'selected-status' : '' }}" onclick="setStatus('cancelled')">
              <span class="status-dot dot-cancelled"></span> Cancelled
            </div>
          </div>
        </div>
      </div>

      {{-- ── COVER IMAGE ── --}}
      <div class="card" style="animation-delay:.1s;">
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
          @if($event->cover_image)
          <div class="current-img-wrap">
            <img src="{{ asset('storage/'.$event->cover_image) }}" class="current-img" alt="Current cover">
            <div class="current-img-label">Current cover image — upload a new one to replace</div>
          </div>
          @endif
          <div class="upload-zone {{ $event->cover_image ? '' : '' }}" id="uploadZone">
            <input type="file" name="cover_image" id="coverInput" accept="image/*" onchange="previewImage(this)">
            <img src="" alt="Preview" class="upload-preview" id="uploadPreview">
            <div class="upload-overlay"><span>Click to change</span></div>
            <div id="uploadPlaceholder">
              <div class="upload-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
              </div>
              <div class="upload-text">{{ $event->cover_image ? 'Click to replace cover image' : 'Click to upload cover image' }}</div>
              <div class="upload-sub">PNG, JPG, WEBP — max 2MB</div>
            </div>
          </div>
        </div>
      </div>

      {{-- ── SETTINGS ── --}}
      <div class="card" style="animation-delay:.15s;">
        <div class="card-header">
          <div class="card-icon ci-green">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><circle cx="12" cy="12" r="3"/></svg>
          </div>
          <div>
            <div class="card-title">Event Settings</div>
            <div class="card-subtitle">Control registration and visibility options</div>
          </div>
        </div>
        <div class="card-body">
          <div class="toggle-row">
            <div class="toggle-info">
              <div class="toggle-name">Allow Registrations</div>
              <div class="toggle-desc">Participants can register for this event</div>
            </div>
            <label class="toggle">
              <input type="checkbox" name="allow_registrations" value="1" {{ $event->allow_registrations ? 'checked' : '' }}>
              <span class="toggle-slider"></span>
            </label>
          </div>
          <div class="toggle-row">
            <div class="toggle-info">
              <div class="toggle-name">Show on Campaign Page</div>
              <div class="toggle-desc">Display this event on the linked campaign page</div>
            </div>
            <label class="toggle">
              <input type="checkbox" name="show_on_campaign" value="1" {{ $event->show_on_campaign ? 'checked' : '' }}>
              <span class="toggle-slider"></span>
            </label>
          </div>
        </div>
      </div>

      {{-- ── ACTION BAR ── --}}
      <div class="action-bar" style="animation:fadeUp .4s .2s ease both;">
        <a href="{{ route('admin.events.index') }}" class="btn btn-ghost">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
          Cancel
        </a>
        <button type="submit" class="btn btn-publish" style="margin-left:auto;">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
          Save Changes
        </button>
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
            <span class="summary-badge {{ $event->status === 'active' ? 'sb-active' : ($event->status === 'cancelled' ? '' : 'sb-draft') }}"
              id="summaryStatusBadge"
              style="{{ $event->status === 'cancelled' ? 'background:var(--red-lt);color:var(--red);' : '' }}">
              {{ ucfirst($event->status) }}
            </span>
          </div>
          <div class="summary-item">
            <div class="summary-key">Event ID</div>
            <div class="summary-val">#{{ $event->id }}</div>
          </div>
          <div class="summary-item">
            <div class="summary-key">Category</div>
            <div class="summary-val">{{ $event->campaign?->category ? (($event->campaign->category->emoji ?? '').' '.$event->campaign->category->name) : '—' }}</div>
          </div>
          <div class="summary-item">
            <div class="summary-key">Campaign</div>
            <div class="summary-val" id="sum-campaign">{{ $event->campaign->title ?? '—' }}</div>
          </div>
          <div class="summary-item">
            <div class="summary-key">Title</div>
            <div class="summary-val" id="sum-title">{{ $event->title }}</div>
          </div>
          <div class="summary-item">
            <div class="summary-key">Date</div>
            <div class="summary-val">{{ $event->event_date?->format('d M Y') ?? '—' }}</div>
          </div>
          <div class="summary-item">
            <div class="summary-key">Goal</div>
            <div class="summary-val">₹{{ number_format($event->goal_amount, 0) }}</div>
          </div>
          <div class="summary-item">
            <div class="summary-key">Max Participants</div>
            <div class="summary-val">{{ $event->max_participants ? $event->max_participants.' max' : 'Unlimited' }}</div>
          </div>
          <div class="summary-item">
            <div class="summary-key">Created</div>
            <div class="summary-val" style="font-size:11px;">{{ $event->created_at->format('d M Y, H:i') }}</div>
          </div>
        </div>
        <div style="padding:0 18px 16px;">
          <a href="{{ route('admin.events.show', $event) }}" class="btn btn-ghost btn-sm" style="width:100%;justify-content:center;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            View Full Details
          </a>
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

window.setStatus = function(val) {
  document.getElementById('statusHidden').value = val;
  document.querySelectorAll('.status-opt').forEach(function(o){ o.classList.remove('selected-status'); });
  document.querySelector('.sel-' + val).classList.add('selected-status');
  var badge = document.getElementById('summaryStatusBadge');
  var map = { draft:'sb-draft', active:'sb-active', cancelled:'' };
  badge.className = 'summary-badge ' + (map[val] || '');
  if (val === 'cancelled') badge.style.cssText = 'background:var(--red-lt);color:var(--red);';
  else badge.style.cssText = '';
  badge.textContent = val.charAt(0).toUpperCase() + val.slice(1);
};

var descEl = document.getElementById('descInp');
var descCount = document.getElementById('descCount');
if (descEl) {
  descEl.addEventListener('input', function(){
    descCount.textContent = this.value.length + ' / 2000';
  });
}

window.previewImage = function(input) {
  if (!input.files || !input.files[0]) return;
  var reader = new FileReader();
  reader.onload = function(e) {
    var preview = document.getElementById('uploadPreview');
    var zone = document.getElementById('uploadZone');
    var placeholder = document.getElementById('uploadPlaceholder');
    preview.src = e.target.result;
    preview.classList.add('show');
    placeholder.style.display = 'none';
    zone.classList.add('has-preview');
  };
  reader.readAsDataURL(input.files[0]);
};

})();
</script>
@endpush
