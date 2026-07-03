@extends('layouts.admin')

@section('sidebar_campaigns', 'active')

@section('page_title', 'Edit — '.$campaign->title)
@section('page_subtitle', 'Modify campaign details')

@push('page_styles')
<style>
.page-grid{display:grid;grid-template-columns:1fr 308px;gap:20px;align-items:start;}
.right-col{position:sticky;top:80px;display:flex;flex-direction:column;gap:16px;}
.card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh);overflow:hidden;}
.card:nth-child(1){animation-delay:.05s;}.card:nth-child(2){animation-delay:.10s;}.card:nth-child(3){animation-delay:.15s;}.card:nth-child(4){animation-delay:.20s;}
.card+.card{margin-top:16px;}
.card-header{padding:14px 18px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;gap:10px;}
.card-header-left{display:flex;align-items:center;gap:10px;}
.card-icon{width:32px;height:32px;border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.card-icon svg{width:14px;height:14px;}
.ic-indigo{background:var(--a-lt);color:var(--a);}
.ic-green{background:var(--green-lt);color:var(--green);}
.ic-yellow{background:var(--amber-lt);color:var(--amber);}
.ic-pink{background:var(--pink-lt);color:var(--pink);}
.ic-red{background:var(--red-lt);color:var(--red);}
.ic-blue{background:var(--blue-lt);color:var(--blue);}
.card-title{font-size:13px;font-weight:700;color:var(--text);letter-spacing:-.01em;font-family:var(--mono);}
.card-sub{font-size:11px;color:var(--text3);margin-top:1px;}
.card-body{padding:18px;}
.badge-dot{width:5px;height:5px;border-radius:50%;background:currentColor;flex-shrink:0;}
.b-pending  {background:rgba(245,158,11,.15);color:#b45309;border:1px solid rgba(245,158,11,.30);}
.b-active   {background:rgba(16,185,129,.15);color:#065f46;border:1px solid rgba(16,185,129,.30);}
.b-approved {background:rgba(16,185,129,.15);color:#065f46;border:1px solid rgba(16,185,129,.30);}
.b-rejected {background:rgba(239,68,68,.15);color:#991b1b;border:1px solid rgba(239,68,68,.30);}
.b-paused   {background:rgba(99,102,241,.15);color:#3730a3;border:1px solid rgba(99,102,241,.30);}
.b-expired  {background:rgba(107,114,128,.15);color:#374151;border:1px solid rgba(107,114,128,.30);}
.b-completed{background:rgba(59,130,246,.15);color:#1e40af;border:1px solid rgba(59,130,246,.30);}
[data-theme="dark"] .b-pending{color:#fbbf24;}[data-theme="dark"] .b-active{color:#34d399;}[data-theme="dark"] .b-approved{color:#34d399;}[data-theme="dark"] .b-rejected{color:#f87171;}[data-theme="dark"] .b-paused{color:#a5b4fc;}[data-theme="dark"] .b-expired{color:#9ca3af;}[data-theme="dark"] .b-completed{color:#93c5fd;}
.flash-success{background:rgba(16,185,129,.09);border:1px solid rgba(16,185,129,.25);color:#065f46;padding:11px 14px;border-radius:10px;font-size:13px;font-weight:500;margin-bottom:16px;display:flex;align-items:center;gap:8px;}
.flash-error{background:rgba(239,68,68,.09);border:1px solid rgba(239,68,68,.25);color:#dc2626;padding:11px 14px;border-radius:10px;font-size:13px;font-weight:500;margin-bottom:16px;display:flex;align-items:center;gap:8px;}
[data-theme="dark"] .flash-success{color:#34d399;}[data-theme="dark"] .flash-error{color:#f87171;}
.form-group{margin-bottom:18px;}
.form-label{display:block;font-size:10.5px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.12em;font-family:var(--mono);margin-bottom:7px;}
.form-label span.req{color:var(--red);margin-left:2px;}
.form-input,.form-select,.form-textarea{width:100%;padding:10px 13px;background:var(--surface2);border:1.5px solid var(--border2);border-radius:var(--r-sm);color:var(--text);font-family:var(--font);font-size:13.5px;outline:none;transition:border-color var(--ease),box-shadow var(--ease),background var(--ease);-webkit-appearance:none;appearance:none;}
.form-input::placeholder,.form-textarea::placeholder{color:var(--text3);}
.form-input:focus,.form-select:focus,.form-textarea:focus{border-color:var(--a);background:var(--surface);box-shadow:0 0 0 3px var(--a-lt);}
.form-input.is-invalid,.form-select.is-invalid,.form-textarea.is-invalid{border-color:var(--red);box-shadow:0 0 0 3px rgba(240,68,68,.10);}
.form-textarea{resize:vertical;min-height:130px;line-height:1.7;}
.form-select{background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%239096b4' stroke-width='2.5'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 12px center;padding-right:34px;cursor:pointer;}
.form-hint{font-size:11px;color:var(--text3);margin-top:5px;font-family:var(--mono);}
.form-error{font-size:11px;color:var(--red);margin-top:5px;font-family:var(--mono);display:flex;align-items:center;gap:4px;}
.form-error::before{content:'\2716';font-size:9px;}
.input-row{display:grid;grid-template-columns:1fr 1fr;gap:14px;}
.cover-upload-zone{position:relative;border-radius:var(--r-sm);overflow:hidden;border:2px dashed var(--border2);transition:border-color var(--ease),background var(--ease);cursor:pointer;}
.cover-upload-zone:hover,.cover-upload-zone.drag-over{border-color:var(--a);background:var(--a-lt);}
.cover-upload-zone input[type="file"]{position:absolute;inset:0;opacity:0;cursor:pointer;z-index:2;width:100%;height:100%;}
.cover-upload-placeholder{padding:28px 20px;text-align:center;display:flex;flex-direction:column;align-items:center;gap:9px;}
.cover-upload-icon{width:44px;height:44px;border-radius:12px;background:var(--a-lt);color:var(--a);display:flex;align-items:center;justify-content:center;margin:0 auto;}
.cover-upload-icon svg{width:20px;height:20px;}
.cover-upload-text{font-size:13px;font-weight:600;color:var(--text2);}
.cover-upload-hint{font-size:11px;color:var(--text3);font-family:var(--mono);}
.cover-preview-wrap{position:relative;}
.cover-preview-img{width:100%;height:200px;object-fit:cover;display:block;}
.cover-preview-overlay{position:absolute;inset:0;background:rgba(0,0,0,.45);display:flex;align-items:center;justify-content:center;gap:8px;opacity:0;transition:opacity var(--ease);}
.cover-preview-wrap:hover .cover-preview-overlay{opacity:1;}
.cover-preview-btn{display:inline-flex;align-items:center;gap:5px;padding:7px 13px;border-radius:8px;font-size:12px;font-weight:600;border:none;cursor:pointer;font-family:var(--font);transition:opacity var(--ease);}
.cover-preview-btn:hover{opacity:.82;}
.cover-preview-btn-change{background:#fff;color:#0a0b14;}
.cover-preview-btn-remove{background:rgba(240,68,68,.85);color:#fff;}
.cover-preview-btn svg{width:11px;height:11px;}
.char-count{float:right;font-size:10.5px;font-family:var(--mono);color:var(--text3);transition:color var(--ease);}
.char-count.warn{color:var(--amber);}
.char-count.over{color:var(--red);}
.action-btn{display:flex;align-items:center;justify-content:center;gap:6px;width:100%;padding:10px 16px;border-radius:var(--r-sm);font-size:12.5px;font-weight:600;cursor:pointer;border:1px solid transparent;font-family:var(--font);transition:opacity var(--ease),transform var(--ease),box-shadow var(--ease);text-decoration:none;letter-spacing:.01em;}
.action-btn:hover{opacity:.88;transform:translateY(-1px);}
.action-btn svg{width:13px;height:13px;}
.action-btn+.action-btn{margin-top:8px;}
.btn-accent{background:var(--a);color:#fff;border-color:var(--a);box-shadow:0 4px 14px rgba(110,86,247,.28);}
.btn-green{background:var(--green);color:#fff;border-color:var(--green);box-shadow:0 4px 14px rgba(5,196,138,.28);}
.btn-red{background:rgba(240,68,68,.1);color:#b91c1c;border-color:rgba(240,68,68,.25);}
.btn-ghost{background:var(--surface2);color:var(--text2);border-color:var(--border2);}
[data-theme="dark"] .btn-red{color:#f87171;}
.status-section-label{font-size:9.5px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.14em;font-family:var(--mono);margin-bottom:10px;}
.info-row{display:flex;justify-content:space-between;align-items:center;font-size:12px;padding:9px 0;}
.info-row+.info-row{border-top:1px solid var(--border);}
.info-row-lbl{color:var(--text3);font-family:var(--mono);letter-spacing:.04em;font-size:10.5px;}
.unsaved-dot{width:7px;height:7px;border-radius:50%;background:var(--amber);display:inline-block;margin-right:5px;animation:pulse 1.8s ease-in-out infinite;vertical-align:middle;}
.unsaved-bar{background:rgba(245,158,11,.09);border:1px solid rgba(245,158,11,.25);border-radius:var(--r-sm);padding:10px 13px;font-size:11.5px;font-weight:500;color:#92400e;display:none;align-items:center;gap:8px;margin-bottom:14px;}
.unsaved-bar.show{display:flex;}
[data-theme="dark"] .unsaved-bar{color:#fbbf24;}
.section-divider{display:flex;align-items:center;gap:10px;margin:22px 0 18px;}
.section-divider-line{flex:1;height:1px;background:var(--border);}
.section-divider-label{font-size:9.5px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.14em;font-family:var(--mono);white-space:nowrap;}
.modal-overlay{position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:500;display:flex;align-items:center;justify-content:center;backdrop-filter:blur(4px);opacity:0;pointer-events:none;transition:opacity var(--ease);}
.modal-overlay.show{opacity:1;pointer-events:all;}
.modal{background:var(--surface);border:1px solid var(--border2);border-radius:var(--r);padding:24px;max-width:380px;width:90%;box-shadow:var(--sh-lg);transform:scale(.95);transition:transform var(--ease);}
.modal-overlay.show .modal{transform:scale(1);}
.modal-title{font-family:var(--mono);font-size:16px;font-weight:800;color:var(--text);margin-bottom:7px;}
.modal-body{font-size:13px;color:var(--text2);line-height:1.6;margin-bottom:18px;}
.modal-actions{display:flex;gap:8px;}
.modal-actions .action-btn{flex:1;margin:0;}
@keyframes pulse{0%,100%{opacity:1;}50%{opacity:.4;}}
@media(max-width:960px){.page-grid{grid-template-columns:1fr;}.right-col{position:static;}.input-row{grid-template-columns:1fr;}}
</style>
@endpush

@section('content')

{{-- Discard Confirm Modal --}}
<div class="modal-overlay" id="discardModal">
    <div class="modal">
        <div class="modal-title">Discard Changes?</div>
        <p class="modal-body">You have unsaved changes. Are you sure you want to leave? All edits will be lost.</p>
        <div class="modal-actions">
            <button type="button" class="action-btn btn-ghost" onclick="closeDiscardModal()">Keep Editing</button>
            <a href="{{ route('admin.campaign.show', $campaign->id) }}" class="action-btn btn-red" id="discardConfirmBtn">Discard</a>
        </div>
    </div>
</div>

@php
    $state = $campaign->campaign_state;
    $chipLabel = match($state) {
        'active'    => 'Active',
        'paused'    => 'Paused',
        'pending'   => 'Pending',
        'rejected'  => 'Rejected',
        'expired'   => 'Expired',
        'completed' => 'Completed',
        default     => ucfirst($state ?? 'Unknown'),
    };
    $raised     = $campaign->raised_amount ?? 0;
    $goal       = $campaign->goal_amount > 0 ? $campaign->goal_amount : 1;
    $percentage = min(100, round(($raised / $goal) * 100));
@endphp

@if(session('success'))
<div class="flash-success">
    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="flash-error">
    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    {{ session('error') }}
</div>
@endif

{{-- Unsaved indicator --}}
<div class="unsaved-bar" id="unsavedBar">
    <span class="unsaved-dot"></span>
    You have unsaved changes
</div>

<form
    id="editForm"
    action="{{ route('admin.campaign.update', $campaign->id) }}"
    method="POST"
    enctype="multipart/form-data"
    novalidate
>
    @csrf
    @method('PUT')

    <div class="page-grid">

        {{-- ════ LEFT COLUMN ════ --}}
        <div>

            {{-- Cover Image --}}
            <div class="card">
                <div class="card-header">
                    <div class="card-header-left">
                        <div class="card-icon ic-indigo">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                        </div>
                        <div>
                            <div class="card-title">Cover Image</div>
                            <div class="card-sub">Recommended: 1200×630px, max 5 MB</div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-group" style="margin-bottom:0;">
                        <div class="cover-upload-zone" id="coverZone">
                            {{-- Hidden: shown when existing cover present --}}
                            <div class="cover-preview-wrap" id="coverPreviewWrap"
                                style="{{ $campaign->cover_image ? '' : 'display:none;' }}">
                                <img
                                    id="coverPreviewImg"
                                    src="{{ $campaign->cover_image ? asset('storage/' . $campaign->cover_image) : '' }}"
                                    alt="Cover preview"
                                    class="cover-preview-img"
                                >
                                <div class="cover-preview-overlay">
                                    <label for="coverInput" class="cover-preview-btn cover-preview-btn-change" style="cursor:pointer;">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                        Change
                                    </label>
                                    <button type="button" class="cover-preview-btn cover-preview-btn-remove" onclick="removeCover()">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        Remove
                                    </button>
                                </div>
                            </div>

                            {{-- Placeholder: shown when no cover --}}
                            <div class="cover-upload-placeholder" id="coverPlaceholder"
                                style="{{ $campaign->cover_image ? 'display:none;' : '' }}">
                                <div class="cover-upload-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                </div>
                                <div class="cover-upload-text">Click or drag to upload a cover</div>
                                <div class="cover-upload-hint">JPG, PNG, WEBP — Max 5 MB</div>
                            </div>

                            <input
                                type="file"
                                id="coverInput"
                                name="cover_image"
                                accept="image/*"
                                style="position:absolute;inset:0;opacity:0;cursor:pointer;z-index:2;width:100%;height:100%;"
                            >
                        </div>
                        {{-- Hidden flag to signal removal --}}
                        <input type="hidden" name="remove_cover" id="removeCoverFlag" value="0">
                        @error('cover_image')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Basic Info --}}
            <div class="card">
                <div class="card-header">
                    <div class="card-header-left">
                        <div class="card-icon ic-indigo">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </div>
                        <div>
                            <div class="card-title">Basic Information</div>
                            <div class="card-sub">Core campaign details</div>
                        </div>
                    </div>
                </div>
                <div class="card-body">

                    {{-- Title --}}
                    <div class="form-group">
                        <label class="form-label" for="title">
                            Campaign Title <span class="req">*</span>
                            <span class="char-count" id="titleCount">{{ strlen(old('title', $campaign->title)) }}/120</span>
                        </label>
                        <input
                            type="text"
                            id="title"
                            name="title"
                            class="form-input @error('title') is-invalid @enderror"
                            value="{{ old('title', $campaign->title) }}"
                            placeholder="Enter campaign title…"
                            maxlength="120"
                            required
                            autocomplete="off"
                        >
                        @error('title')
                            <div class="form-error">{{ $message }}</div>
                        @else
                            <div class="form-hint">Keep it concise and compelling.</div>
                        @enderror
                    </div>

                    {{-- Category & Status row --}}
                    <div class="input-row">
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label" for="category_id">Category <span class="req">*</span></label>
                            <select
                                id="category_id"
                                name="category_id"
                                class="form-select @error('category_id') is-invalid @enderror"
                                required
                            >
                                <option value="">Select category…</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}"
                                        {{ old('category_id', $campaign->category_id) == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label" for="campaign_state">Status</label>
                            <select
                                id="campaign_state"
                                name="campaign_state"
                                class="form-select @error('campaign_state') is-invalid @enderror"
                            >
                                @foreach(['pending','active','paused','rejected','expired','completed'] as $s)
                                    <option value="{{ $s }}"
                                        {{ old('campaign_state', $campaign->campaign_state) === $s ? 'selected' : '' }}>
                                        {{ ucfirst($s) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('campaign_state')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                </div>
            </div>

            {{-- Description --}}
            <div class="card">
                <div class="card-header">
                    <div class="card-header-left">
                        <div class="card-icon ic-blue">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div>
                            <div class="card-title">Description</div>
                            <div class="card-sub">Campaign story &amp; details</div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label" for="description">
                            Description <span class="req">*</span>
                            <span class="char-count" id="descCount">{{ strlen(old('description', $campaign->description)) }}/5000</span>
                        </label>
                        <textarea
                            id="description"
                            name="description"
                            class="form-textarea @error('description') is-invalid @enderror"
                            placeholder="Describe what this campaign is about, its goals, and how funds will be used…"
                            maxlength="5000"
                            required
                            style="min-height:180px;"
                        >{{ old('description', $campaign->description) }}</textarea>
                        @error('description')
                            <div class="form-error">{{ $message }}</div>
                        @else
                            <div class="form-hint">Be clear and transparent to build donor trust.</div>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Financials & Dates --}}
            <div class="card">
                <div class="card-header">
                    <div class="card-header-left">
                        <div class="card-icon ic-green">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <div class="card-title">Financials &amp; Dates</div>
                            <div class="card-sub">Funding goal and timeline</div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="input-row">
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label" for="goal_amount">Goal Amount (₹) <span class="req">*</span></label>
                            <input
                                type="number"
                                id="goal_amount"
                                name="goal_amount"
                                class="form-input @error('goal_amount') is-invalid @enderror"
                                value="{{ old('goal_amount', $campaign->goal_amount) }}"
                                placeholder="e.g. 100000"
                                min="1"
                                step="1"
                                required
                            >
                            @error('goal_amount')
                                <div class="form-error">{{ $message }}</div>
                            @else
                                <div class="form-hint">Minimum ₹1</div>
                            @enderror
                        </div>

                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label" for="end_date">End Date</label>
                            <input
                                type="date"
                                id="end_date"
                                name="end_date"
                                class="form-input @error('end_date') is-invalid @enderror"
                                value="{{ old('end_date', $campaign->end_date ? \Carbon\Carbon::parse($campaign->end_date)->format('Y-m-d') : '') }}"
                            >
                            @error('end_date')
                                <div class="form-error">{{ $message }}</div>
                            @else
                                <div class="form-hint">Leave empty for no deadline.</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

        </div>{{-- /left --}}

        {{-- ════ RIGHT COLUMN ════ --}}
        <div class="right-col">

            {{-- Save Actions --}}
            <div class="card">
                <div class="card-header">
                    <div class="card-header-left">
                        <div class="card-icon ic-green">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                        </div>
                        <div>
                            <div class="card-title">Save Changes</div>
                        </div>
                    </div>
                </div>
                <div class="card-body" style="padding-top:14px;padding-bottom:14px;">
                    <button type="submit" class="action-btn btn-accent" id="saveBtn">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                        Save Changes
                    </button>
                    <button type="button" class="action-btn btn-ghost" id="discardBtn">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        Discard &amp; Go Back
                    </button>
                    <p style="font-size:10.5px;color:var(--text3);margin-top:10px;font-family:var(--mono);text-align:center;line-height:1.6;">
                        Changes are saved immediately.<br>This cannot be undone.
                    </p>
                </div>
            </div>

            {{-- Current Progress (read-only) --}}
            <div class="card">
                <div class="card-header">
                    <div class="card-header-left">
                        <div class="card-icon ic-indigo">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        </div>
                        <div>
                            <div class="card-title">Current Progress</div>
                            <div class="card-sub">Read-only live snapshot</div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div style="display:flex;justify-content:space-between;align-items:baseline;margin-bottom:9px;">
                        <div style="font-size:24px;font-weight:800;color:var(--a);letter-spacing:-.03em;font-family:var(--mono);line-height:1;">₹{{ number_format($raised) }}</div>
                        <div style="font-size:11.5px;color:var(--text3);font-family:var(--mono);">of ₹{{ number_format($campaign->goal_amount) }}</div>
                    </div>
                    <div style="width:100%;background:var(--surface2);border-radius:100px;height:5px;overflow:hidden;margin-bottom:5px;border:1px solid var(--border);">
                        <div style="height:100%;border-radius:100px;background:linear-gradient(90deg,var(--a),var(--a2));width:{{ $percentage }}%;transition:width 1.2s ease;"></div>
                    </div>
                    <div style="font-size:10.5px;color:var(--text3);font-family:var(--mono);">{{ $percentage }}% funded</div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:9px;margin-top:14px;">
                        <div style="background:var(--surface2);border:1px solid var(--border);border-radius:var(--r-sm);padding:11px;text-align:center;">
                            <div style="font-size:17px;font-weight:800;color:var(--text);font-family:var(--mono);line-height:1;">{{ $percentage }}%</div>
                            <div style="font-size:9.5px;color:var(--text3);margin-top:4px;font-family:var(--mono);text-transform:uppercase;letter-spacing:.06em;">Funded</div>
                        </div>
                        <div style="background:var(--surface2);border:1px solid var(--border);border-radius:var(--r-sm);padding:11px;text-align:center;">
                            <div style="font-size:14px;font-weight:800;color:var(--text);font-family:var(--mono);line-height:1;">₹{{ number_format(max(0, $campaign->goal_amount - $raised)) }}</div>
                            <div style="font-size:9.5px;color:var(--text3);margin-top:4px;font-family:var(--mono);text-transform:uppercase;letter-spacing:.06em;">Remaining</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Campaign Meta (read-only) --}}
            <div class="card">
                <div class="card-header">
                    <div class="card-header-left">
                        <div class="card-icon ic-pink">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <div class="card-title">Campaign Meta</div>
                        </div>
                    </div>
                </div>
                <div class="card-body" style="padding-top:10px;padding-bottom:10px;">
                    <div class="info-row">
                        <span class="info-row-lbl">FUNDRAISER</span>
                        <span style="font-size:11.5px;font-weight:600;color:var(--text);">{{ $campaign->user->name ?? '—' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-row-lbl">EMAIL</span>
                        <span style="font-size:11px;color:var(--text2);font-family:var(--mono);">{{ $campaign->user->email ?? '—' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-row-lbl">CAMPAIGN ID</span>
                        <span style="font-size:11px;color:var(--text3);font-family:var(--mono);">#{{ $campaign->id }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-row-lbl">CREATED</span>
                        <span style="font-size:11px;color:var(--text2);">{{ $campaign->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-row-lbl">LAST UPDATED</span>
                        <span style="font-size:11px;color:var(--text2);">{{ $campaign->updated_at->diffForHumans() }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-row-lbl">EVENTS</span>
                        <span style="font-weight:700;color:var(--text);font-family:var(--mono);">{{ $campaign->events->count() }}</span>
                    </div>
                </div>
            </div>

            {{-- View Campaign Link --}}
            <a href="{{ route('admin.campaign.show', $campaign->id) }}" class="action-btn btn-ghost" id="backBtn" style="text-decoration:none;display:flex;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:13px;height:13px;"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                View Campaign Page
            </a>

        </div>{{-- /right-col --}}

    </div>{{-- /page-grid --}}
</form>

@endsection

@push('page_scripts')
<script>
/* ── Char counters ── */
function makeCounter(inputId, countId, max) {
    var el  = document.getElementById(inputId);
    var cnt = document.getElementById(countId);
    if (!el || !cnt) return;
    function update() {
        var len = el.value.length;
        cnt.textContent = len + '/' + max;
        cnt.className = 'char-count' + (len > max * .9 ? (len >= max ? ' over' : ' warn') : '');
    }
    el.addEventListener('input', update);
    update();
}
makeCounter('title',       'titleCount', 120);
makeCounter('description', 'descCount',  5000);

/* ── Cover image preview ── */
var coverInput       = document.getElementById('coverInput');
var coverZone        = document.getElementById('coverZone');
var coverPreviewWrap = document.getElementById('coverPreviewWrap');
var coverPreviewImg  = document.getElementById('coverPreviewImg');
var coverPlaceholder = document.getElementById('coverPlaceholder');
var removeCoverFlag  = document.getElementById('removeCoverFlag');

coverInput.addEventListener('change', function(){
    var file = this.files[0];
    if (!file) return;
    var reader = new FileReader();
    reader.onload = function(e){
        coverPreviewImg.src = e.target.result;
        coverPreviewWrap.style.display = '';
        coverPlaceholder.style.display = 'none';
        removeCoverFlag.value = '0';
        markDirty();
    };
    reader.readAsDataURL(file);
});

function removeCover() {
    coverInput.value = '';
    coverPreviewImg.src = '';
    coverPreviewWrap.style.display = 'none';
    coverPlaceholder.style.display = '';
    removeCoverFlag.value = '1';
    markDirty();
}

/* Drag-over styling */
coverZone.addEventListener('dragover', function(e){ e.preventDefault(); this.classList.add('drag-over'); });
coverZone.addEventListener('dragleave', function(){ this.classList.remove('drag-over'); });
coverZone.addEventListener('drop', function(e){
    e.preventDefault(); this.classList.remove('drag-over');
    var file = e.dataTransfer.files[0];
    if (file && file.type.startsWith('image/')) {
        var dt = new DataTransfer();
        dt.items.add(file);
        coverInput.files = dt.files;
        coverInput.dispatchEvent(new Event('change'));
    }
});

/* ── Unsaved changes tracker ── */
var isDirty = false;
var unsavedBar = document.getElementById('unsavedBar');

function markDirty() {
    if (!isDirty) {
        isDirty = true;
        unsavedBar.classList.add('show');
    }
}

var formFields = document.querySelectorAll('#editForm input, #editForm textarea, #editForm select');
formFields.forEach(function(f) {
    f.addEventListener('change', markDirty);
    f.addEventListener('input',  markDirty);
});

/* Clear dirty flag on save */
document.getElementById('editForm').addEventListener('submit', function(){
    isDirty = false;
});

/* ── Discard modal ── */
document.getElementById('discardBtn').addEventListener('click', function(){
    if (isDirty) {
        document.getElementById('discardModal').classList.add('show');
    } else {
        window.location.href = '{{ route('admin.campaign.show', $campaign->id) }}';
    }
});

/* Intercept back button when dirty */
document.getElementById('backBtn').addEventListener('click', function(e){
    if (isDirty) {
        e.preventDefault();
        document.getElementById('discardModal').classList.add('show');
    }
});

function closeDiscardModal() {
    document.getElementById('discardModal').classList.remove('show');
}

document.getElementById('discardModal').addEventListener('click', function(e){
    if (e.target === this) closeDiscardModal();
});
document.addEventListener('keydown', function(e){
    if (e.key === 'Escape') closeDiscardModal();
});

/* Warn on browser navigation away */
window.addEventListener('beforeunload', function(e){
    if (isDirty) {
        e.preventDefault();
        e.returnValue = '';
    }
});

/* ── Save button loading state ── */
document.getElementById('editForm').addEventListener('submit', function(){
    var btn = document.getElementById('saveBtn');
    btn.disabled = true;
    btn.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:13px;height:13px;animation:spin .7s linear infinite;"><path stroke-linecap="round" stroke-linejoin="round" d="M4 12a8 8 0 018-8v4l3-3-3-3v4A10 10 0 002 12h2z"/></svg> Saving…';
    btn.style.opacity = '.75';
});

@if(session('success'))
window.addEventListener('DOMContentLoaded', function(){ showToast(@json(session('success')), 'success'); });
@endif
@if(session('error'))
window.addEventListener('DOMContentLoaded', function(){ showToast(@json(session('error')), 'error'); });
@endif

/* ── Spin keyframe for save button ── */
var style = document.createElement('style');
style.textContent = '@keyframes spin{to{transform:rotate(360deg);}}';
document.head.appendChild(style);
</script>
@endpush
