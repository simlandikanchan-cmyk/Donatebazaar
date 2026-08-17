@extends('layouts.user')

@section('page_title', 'Verify Identity')
@section('page_subtitle', Str::limit($campaign->title, 45))

@push('page_styles')
@vite(['resources/css/user/pages/kyc-upload.css'])
@endpush

@section('content')
<script type="application/json" id="kycUploadData">
@json(['selectedDocType' => old('document_type') ?? ''])
</script>
{{-- Stepper --}}
<div class="card" style="margin-bottom:20px;">
    <div class="card-body" style="padding:16px 20px;">
        <div class="stepper">
            <div class="step-item step-done">
                <div class="step-circle">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                </div>
                <span class="step-label">Campaign</span>
            </div>
            <div class="step-connector step-connector-done"></div>
            <div class="step-item step-active">
                <div class="step-circle">2</div>
                <span class="step-label">KYC</span>
            </div>
            <div class="step-connector step-connector-idle"></div>
            <div class="step-item step-idle">
                <div class="step-circle">3</div>
                <span class="step-label">Approval</span>
            </div>
        </div>
    </div>
</div>

<div class="page-grid">

    {{-- ════ LEFT — FORM ════ --}}
    <div>

        {{-- Status Alerts --}}
        @if($existingKyc && $existingKyc->status === 'pending')
        @elseif($existingKyc && $existingKyc->status === 'rejected')
        <div class="alert alert-error">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 9l-6 6m0-6l6 6"/></svg>
            <div>
                <div class="alert-title">Verification Failed</div>
                @if($existingKyc->rejection_reason)
                <div class="alert-body">Reason: {{ $existingKyc->rejection_reason }}</div>
                @endif
                <div class="alert-body">Please upload the correct documents again.</div>
            </div>
        </div>
        @endif

        @if(session('success'))
        <div class="alert alert-success">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4"/></svg>
            <div class="alert-body">{{ session('success') }}</div>
        </div>
        @endif

        @if($errors->any())
        <div class="alert alert-error">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
            <ul style="list-style:disc;padding-left:14px;font-size:11.5px;line-height:1.7;">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('kyc.upload', $campaign->id) }}"
              method="POST"
              enctype="multipart/form-data"
              id="kycForm">
            @csrf

            {{-- ── Step 1: Document Type ── --}}
            <div class="card">
                <div class="card-header">
                    <div class="card-header-left">
                        <div class="card-icon ic-indigo">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"/><path stroke-linecap="round" d="M16 10h2M16 14h2M6 10h5M6 14h3"/></svg>
                        </div>
                        <div>
                            <div class="card-title">Select Document Type</div>
                            <div class="card-sub">Choose the ID you'll be uploading</div>
                        </div>
                    </div>
                    <span style="font-size:9.5px;font-weight:700;font-family:var(--mono);color:var(--text3);background:var(--surface2);border:1px solid var(--border2);padding:3px 8px;border-radius:100px;">Step 1</span>
                </div>
                <div class="card-body">
                    <div class="doc-type-grid" id="docTypeGrid">

                        <label class="doc-type-label">
                            <input type="radio" name="document_type" value="pan"
                                   data-action="doc-type" data-type="pan"
                                   {{ old('document_type') === 'pan' ? 'checked' : '' }}>
                            <div class="doc-type-inner">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2" y="5" width="20" height="14" rx="2"/><path stroke-linecap="round" d="M16 10h2M16 14h2M6 10h5M6 14h3"/></svg>
                                <span>PAN Card</span>
                            </div>
                        </label>

                        <label class="doc-type-label">
                            <input type="radio" name="document_type" value="aadhaar"
                                   data-action="doc-type" data-type="aadhaar"
                                   {{ old('document_type') === 'aadhaar' ? 'checked' : '' }}>
                            <div class="doc-type-inner">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 11c0-1.105.895-2 2-2s2 .895 2 2v3m-2-3c0-1.105-.895-2-2-2s-2 .895-2 2v3m8-1a8 8 0 11-16 0 8 8 0 0116 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M9 13v1a3 3 0 006 0v-1"/></svg>
                                <span>Aadhaar</span>
                            </div>
                        </label>

                        <label class="doc-type-label">
                            <input type="radio" name="document_type" value="passport"
                                   data-action="doc-type" data-type="passport"
                                   {{ old('document_type') === 'passport' ? 'checked' : '' }}>
                            <div class="doc-type-inner">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0118 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/></svg>
                                <span>Passport</span>
                            </div>
                        </label>

                        <label class="doc-type-label">
                            <input type="radio" name="document_type" value="other"
                                   data-action="doc-type" data-type="other"
                                   {{ old('document_type') === 'other' ? 'checked' : '' }}>
                            <div class="doc-type-inner">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <span>Other</span>
                            </div>
                        </label>

                    </div>
                </div>
            </div>

            {{-- ── Step 2: Document Number ── --}}
            <div class="card">
                <div class="card-header">
                    <div class="card-header-left">
                        <div class="card-icon ic-blue">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 9h16M4 15h16M10 3L8 21M16 3l-2 18"/></svg>
                        </div>
                        <div>
                            <div class="card-title">Document Number</div>
                            <div class="card-sub">Enter the number printed on your ID</div>
                        </div>
                    </div>
                    <span style="font-size:9.5px;font-weight:700;font-family:var(--mono);color:var(--text3);background:var(--surface2);border:1px solid var(--border2);padding:3px 8px;border-radius:100px;">Step 2</span>
                </div>
                <div class="card-body">
                    <div class="input-wrap">
                        <div class="input-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 9h16M4 15h16M10 3L8 21M16 3l-2 18"/></svg>
                        </div>
                        <input
                            type="text"
                            name="document_number"
                            id="documentNumber"
                            value="{{ old('document_number') }}"
                            placeholder="Select a document type first"
                            disabled
                            class="form-input">
                        <p class="input-hint" id="docFormatHint"></p>
                    </div>
                </div>
            </div>

            {{-- ── Step 3: Upload Document ── --}}
            <div class="card">
                <div class="card-header">
                    <div class="card-header-left">
                        <div class="card-icon ic-yellow">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 16V4m0 0L8 8m4-4l4 4"/><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2"/></svg>
                        </div>
                        <div>
                            <div class="card-title">Upload Document</div>
                            <div class="card-sub">PDF, JPG, or PNG — max 5 MB</div>
                        </div>
                    </div>
                    <span style="font-size:9.5px;font-weight:700;font-family:var(--mono);color:var(--text3);background:var(--surface2);border:1px solid var(--border2);padding:3px 8px;border-radius:100px;">Step 3</span>
                </div>
                <div class="card-body">

                    <div
                        id="dropZone"
                        data-action="dz-zone"
                        class="drop-zone">

                        <input type="file" name="document_file" id="document_file"
                               accept=".pdf,.jpg,.jpeg,.png" style="display:none;"
                               data-action="dz-file">

                        <div class="drop-zone-icon" id="uploadIconWrap">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 16V4m0 0L8 8m4-4l4 4"/><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2"/></svg>
                        </div>
                        <p class="drop-zone-primary" id="uploadPrimary">Drag & drop or click to upload</p>
                        <p class="drop-zone-sub" id="uploadSub">PDF, JPG, PNG · max 5 MB</p>
                        <div class="file-selected-row" id="filePreview">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span class="file-selected-name" id="fileName"></span>
                        </div>
                    </div>

                    <div class="security-notice">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="5" y="11" width="14" height="10" rx="2"/><path stroke-linecap="round" stroke-linejoin="round" d="M8 11V7a4 4 0 018 0v4"/></svg>
                        <p>Your documents are <strong>end-to-end encrypted</strong> and only accessible by our compliance team. We never share your data with third parties.</p>
                    </div>

                    <x-button variant="primary" type="submit">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                        <span id="submitLabel">Complete Verification</span>
                    </x-button>

                </div>
            </div>

        </form>

    </div>{{-- /left --}}

    {{-- ════ RIGHT COLUMN ════ --}}
    <div class="right-col">

        {{-- How It Works --}}
        <div class="card">
            <div class="card-header">
                <div class="card-header-left">
                    <div class="card-icon ic-indigo">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <div class="card-title">How It Works</div>
                    </div>
                </div>
            </div>
            <div class="status-section">
                <div class="how-step">
                    <div class="how-step-num how-step-1">1</div>
                    <div>
                        <div class="how-step-title">Upload Documents</div>
                        <div class="how-step-desc">Submit your government-issued ID now</div>
                    </div>
                </div>
                <div class="how-step">
                    <div class="how-step-num how-step-2">2</div>
                    <div>
                        <div class="how-step-title">We Review</div>
                        <div class="how-step-desc">Our compliance team checks within 2–6 hours</div>
                    </div>
                </div>
                <div class="how-step">
                    <div class="how-step-num how-step-3">3</div>
                    <div>
                        <div class="how-step-title">Campaign Goes Live</div>
                        <div class="how-step-desc">Your fundraiser activates after approval</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Campaign Info --}}
        <div class="card">
            <div class="card-header">
                <div class="card-header-left">
                    <div class="card-icon ic-indigo">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    <div>
                        <div class="card-title">Campaign Info</div>
                    </div>
                </div>
            </div>
            <div class="card-body" style="padding-top:10px;padding-bottom:10px;">
                <div class="info-row">
                    <span class="info-row-lbl">CAMPAIGN</span>
                    <span style="font-size:11.5px;font-weight:600;color:var(--text);text-align:right;max-width:160px;line-height:1.4;">{{ Str::limit($campaign->title, 28) }}</span>
                </div>
                <div class="info-row">
                    <span class="info-row-lbl">STATUS</span>
                    <span class="badge b-{{ $campaign->campaign_state ?? 'pending' }}">
                        <span class="badge-dot"></span>{{ ucfirst($campaign->campaign_state ?? 'pending') }}
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-row-lbl">KYC</span>
                    <span style="font-size:11px;font-weight:700;
                        color:{{ ($existingKyc?->status === 'approved') ? 'var(--green)' : (($existingKyc?->status === 'pending') ? 'var(--yellow)' : 'var(--red)') }};">
                        @if(!$existingKyc) Not Submitted
                        @elseif($existingKyc->status === 'pending')  Pending
                        @elseif($existingKyc->status === 'approved') ✓ Verified
                        @else ✗ Rejected
                        @endif
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-row-lbl">CREATED</span>
                    <span style="font-weight:600;color:var(--text2);font-size:11px;">{{ $campaign->created_at->format('d M Y') }}</span>
                </div>
            </div>
        </div>

    </div>{{-- /right-col --}}
</div>{{-- /page-grid --}}

{{-- Success Toast --}}
<div id="successToast"
     style="position:fixed;bottom:24px;left:50%;transform:translateX(-50%) translateY(80px);opacity:0;
            display:flex;align-items:center;gap:10px;background:#0f1117;color:#fff;
            font-size:13px;font-weight:500;padding:13px 18px;border-radius:14px;
            box-shadow:0 8px 40px rgba(0,0,0,0.25);z-index:9999;
            transition:all .45s cubic-bezier(.4,0,.2,1);white-space:nowrap;overflow:hidden;">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4"/></svg>
    <span>Documents submitted!</span>
    <span style="color:rgba(255,255,255,0.4);margin-left:2px;">Redirecting…</span>
    <div style="position:absolute;bottom:0;left:0;height:2px;background:#10b981;border-radius:0 0 14px 14px;animation:shrink 2.1s linear forwards;animation-delay:.15s;transform-origin:left;width:100%;"></div>
</div>
@endsection

@push('page_scripts')
@vite(['resources/js/user/kyc-upload.js'])
@endpush
