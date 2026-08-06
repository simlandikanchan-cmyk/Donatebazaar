@extends('layouts.admin')

@push('page_css')
@vite('resources/css/admin/entries/campaigns.css')
@endpush


@section('sidebar_campaigns', 'active')

@section('page_title', 'Edit — '.$campaign->title)
@section('page_subtitle', 'Modify campaign details')

@section('content')

{{-- Discard Confirm Modal --}}
<div class="overlay" id="discardModal">
    <div class="modal">
        <div class="modal-ttl">Discard Changes?</div>
        <p class="modal-sub">You have unsaved changes. Are you sure you want to leave? All edits will be lost.</p>
        <div class="modal-acts">
            <x-button variant="secondary" type="button">Keep Editing</x-button>
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
                                    <x-button variant="destructive" type="button" class="cover-preview-btn">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        Remove
                                    </x-button>
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
                    <x-button variant="primary" type="submit">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                        Save Changes
                    </x-button>
                    <x-button variant="secondary" type="button">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        Discard &amp; Go Back
                    </x-button>
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
            <a href="{{ route('admin.campaign.show', $campaign->id) }}" class="btn btn-secondary action-btn btn-ghost" id="backBtn" style="text-decoration:none;display:flex;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:13px;height:13px;"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                View Campaign Page
            </a>

        </div>{{-- /right-col --}}

    </div>{{-- /page-grid --}}
</form>

@endsection

@push('page_scripts')
<script>
/* —€—€ Char counters —€—€ */
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

/* —€—€ Cover image preview —€—€ */
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

/* —€—€ Unsaved changes tracker —€—€ */
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

/* —€—€ Discard modal —€—€ */
document.getElementById('discardBtn').addEventListener('click', function(){
    if (isDirty) {
        document.getElementById('discardModal').classList.add('open');
    } else {
        window.location.href = '{{ route('admin.campaign.show', $campaign->id) }}';
    }
});

/* Intercept back button when dirty */
document.getElementById('backBtn').addEventListener('click', function(e){
    if (isDirty) {
        e.preventDefault();
        document.getElementById('discardModal').classList.add('open');
    }
});

function closeDiscardModal() {
    document.getElementById('discardModal').classList.remove('open');
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

/* —€—€ Save button loading state —€—€ */
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

/* —€—€ Spin keyframe for save button —€—€ */
var style = document.createElement('style');
style.textContent = '@keyframes spin{to{transform:rotate(360deg);}}';
document.head.appendChild(style);
</script>
@endpush
