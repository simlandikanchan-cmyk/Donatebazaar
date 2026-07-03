@extends('layouts.user')

@section('page_title', 'Verify Identity')
@section('page_subtitle', Str::limit($campaign->title, 45))

@push('page_styles')
<style>
.page-grid{display:grid;grid-template-columns:1fr 308px;gap:20px;align-items:start;}
.right-col{position:sticky;top:80px;display:flex;flex-direction:column;gap:16px;}
.card{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);box-shadow:var(--shadow);overflow:hidden;animation:fadeUp .4s both;}
.card:nth-child(1){animation-delay:0.05s;}.card:nth-child(2){animation-delay:0.10s;}.card:nth-child(3){animation-delay:0.15s;}.card:nth-child(4){animation-delay:0.20s;}
.card+.card{margin-top:16px;}
.card-header{padding:14px 18px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;gap:10px;}
.card-header-left{display:flex;align-items:center;gap:10px;}
.card-icon{width:32px;height:32px;border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.card-icon svg{width:14px;height:14px;}
.ic-indigo{background:rgba(99,102,241,0.12);color:var(--accent);}
.ic-green{background:rgba(16,185,129,0.12);color:var(--green);}
.ic-yellow{background:rgba(245,158,11,0.12);color:var(--yellow);}
.ic-blue{background:rgba(59,130,246,0.12);color:var(--blue);}
.ic-red{background:rgba(239,68,68,0.12);color:var(--red);}
.card-title{font-size:13px;font-weight:700;color:var(--text);letter-spacing:-0.01em;font-family:var(--font-display);}
.card-sub{font-size:11px;color:var(--text3);margin-top:1px;}
.card-body{padding:18px;}
.stepper{display:flex;align-items:center;margin-bottom:4px;}
.step-item{display:flex;flex-direction:column;align-items:center;gap:5px;}
.step-circle{width:32px;height:32px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;flex-shrink:0;font-family:var(--mono);}
.step-done .step-circle{background:rgba(16,185,129,0.15);border:1.5px solid rgba(16,185,129,0.35);color:var(--green);}
.step-active .step-circle{background:var(--accent-glow);border:2px solid var(--accent);color:var(--accent);}
.step-idle .step-circle{background:var(--surface2);border:1.5px solid var(--border2);color:var(--text3);}
.step-label{font-size:10px;font-weight:700;font-family:var(--mono);letter-spacing:0.04em;white-space:nowrap;}
.step-done .step-label{color:var(--green);}
.step-active .step-label{color:var(--accent);}
.step-idle .step-label{color:var(--text3);}
.step-connector{flex:1;height:1.5px;margin:0 10px;margin-bottom:18px;}
.step-connector-done{background:rgba(16,185,129,0.4);}
.step-connector-idle{background:var(--border2);}
.doc-type-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:8px;margin-bottom:0;}
.doc-type-label{display:block;cursor:pointer;position:relative;}
.doc-type-label input{position:absolute;opacity:0;width:0;height:0;}
.doc-type-inner{display:flex;flex-direction:column;align-items:center;gap:6px;padding:12px 8px;border-radius:var(--radius-sm);border:1.5px solid var(--border2);background:var(--surface2);transition:all var(--tr);text-align:center;}
.doc-type-inner:hover{border-color:var(--accent);background:var(--accent-glow);}
.doc-type-label input:checked ~ .doc-type-inner{border-color:var(--accent);background:var(--accent-glow);border-width:2px;}
.doc-type-inner svg{width:20px;height:20px;color:var(--text3);transition:color var(--tr);}
.doc-type-label input:checked ~ .doc-type-inner svg,
.doc-type-inner:hover svg{color:var(--accent);}
.doc-type-inner span{font-size:11px;font-weight:600;color:var(--text2);line-height:1.3;font-family:var(--mono);}
.doc-type-label input:checked ~ .doc-type-inner span{color:var(--accent);}
.input-wrap{position:relative;margin-bottom:0;}
.input-icon{position:absolute;left:11px;top:50%;transform:translateY(-50%);display:flex;align-items:center;pointer-events:none;}
.input-icon svg{width:14px;height:14px;color:var(--text3);}
.form-input{width:100%;padding:10px 14px 10px 34px;border-radius:var(--radius-sm);border:1.5px solid var(--border2);background:var(--surface2);color:var(--text);font-family:var(--font);font-size:13px;transition:border-color var(--tr),background var(--tr),color var(--tr);outline:none;}
.form-input::placeholder{color:var(--text3);}
.form-input:focus{border-color:var(--accent);background:var(--surface);box-shadow:0 0 0 3px var(--accent-glow);}
.form-input:disabled{cursor:not-allowed;opacity:0.6;}
.input-hint{font-size:10.5px;color:var(--text3);margin-top:5px;font-family:var(--mono);}
.drop-zone{border:2px dashed var(--border2);border-radius:var(--radius);padding:32px 20px;text-align:center;cursor:pointer;transition:all var(--tr);margin-bottom:16px;background:var(--surface2);}
.drop-zone:hover,.drop-zone.drag-over{border-color:var(--accent);background:var(--accent-glow);}
.drop-zone.file-selected{border-color:var(--green);background:rgba(16,185,129,0.06);border-style:solid;}
.drop-zone-icon{display:flex;justify-content:center;margin-bottom:10px;}
.drop-zone-icon svg{width:36px;height:36px;color:var(--text3);}
.drop-zone.file-selected .drop-zone-icon svg{color:var(--green);}
.drop-zone-primary{font-size:13px;font-weight:600;color:var(--accent);font-family:var(--font);}
.drop-zone.file-selected .drop-zone-primary{color:var(--green);}
.drop-zone-sub{font-size:11px;color:var(--text3);margin-top:4px;font-family:var(--mono);}
.file-selected-row{display:none;align-items:center;justify-content:center;gap:6px;margin-top:10px;}
.file-selected-row.visible{display:flex;}
.file-selected-row svg{width:13px;height:13px;color:var(--green);}
.file-selected-name{font-size:12px;font-weight:600;color:var(--green);font-family:var(--mono);}
.security-notice{display:flex;align-items:flex-start;gap:10px;background:rgba(99,102,241,0.07);border:1px solid rgba(99,102,241,0.15);border-radius:var(--radius-sm);padding:11px 13px;margin-bottom:16px;}
.security-notice svg{width:13px;height:13px;color:var(--accent);flex-shrink:0;margin-top:1px;}
.security-notice p{font-size:11.5px;color:var(--text2);line-height:1.6;}
.submit-btn{width:100%;display:flex;align-items:center;justify-content:center;gap:7px;padding:11px 20px;border-radius:var(--radius-sm);font-size:13px;font-weight:600;font-family:var(--font);border:none;cursor:pointer;transition:all var(--tr);background:var(--accent);color:#fff;box-shadow:0 4px 14px rgba(99,102,241,0.28);}
.submit-btn:hover:not(:disabled){background:var(--accent2);transform:translateY(-1px);box-shadow:0 6px 20px rgba(99,102,241,0.35);}
.submit-btn:disabled{background:var(--surface2);color:var(--text3);cursor:not-allowed;box-shadow:none;transform:none;}
.submit-btn svg{width:14px;height:14px;}
.alert{display:flex;gap:11px;border-radius:var(--radius-sm);padding:12px 14px;margin-bottom:14px;font-size:12.5px;}
.alert svg{width:15px;height:15px;flex-shrink:0;margin-top:1px;}
.alert-title{font-weight:700;font-size:12px;margin-bottom:2px;font-family:var(--font-display);}
.alert-body{font-size:11.5px;opacity:0.9;line-height:1.5;}
.alert-warning{background:rgba(245,158,11,0.09);border:1px solid rgba(245,158,11,0.25);color:#92400e;}
.alert-warning svg{color:#f59e0b;}
.alert-error{background:rgba(239,68,68,0.09);border:1px solid rgba(239,68,68,0.25);color:#991b1b;}
.alert-error svg{color:#ef4444;}
.alert-success{background:rgba(16,185,129,0.09);border:1px solid rgba(16,185,129,0.25);color:#065f46;}
.alert-success svg{color:#10b981;}
[data-theme="dark"] .alert-warning{color:#fbbf24;} [data-theme="dark"] .alert-error{color:#f87171;} [data-theme="dark"] .alert-success{color:#34d399;}
.status-section{padding:16px;}
.status-section-label{font-size:9.5px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:0.14em;font-family:var(--mono);margin-bottom:10px;}
.how-step{display:flex;align-items:flex-start;gap:12px;padding:11px 0;}
.how-step+.how-step{border-top:1px solid var(--border);}
.how-step-num{width:28px;height:28px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:800;font-family:var(--font-display);flex-shrink:0;}
.how-step-1{background:rgba(99,102,241,0.12);color:var(--accent);}
.how-step-2{background:rgba(245,158,11,0.12);color:var(--yellow);}
.how-step-3{background:rgba(16,185,129,0.12);color:var(--green);}
.how-step-title{font-size:12px;font-weight:700;color:var(--text);font-family:var(--font-display);}
.how-step-desc{font-size:11px;color:var(--text3);margin-top:2px;}
.info-row{display:flex;justify-content:space-between;align-items:center;font-size:12px;padding:9px 0;}
.info-row+.info-row{border-top:1px solid var(--border);}
.info-row-lbl{color:var(--text3);font-family:var(--mono);letter-spacing:0.04em;font-size:10.5px;}
@keyframes fadeUp{from{opacity:0;transform:translateY(14px);}to{opacity:1;transform:none;}}
@keyframes shrink{from{width:100%;}to{width:0%;}}
@media(max-width:960px){.page-grid{grid-template-columns:1fr;}.right-col{position:static;}}
@media(max-width:600px){.doc-type-grid{grid-template-columns:repeat(2,1fr);}}
</style>
@endpush

@section('content')
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
                                   onchange="onDocTypeChange('pan')"
                                   {{ old('document_type') === 'pan' ? 'checked' : '' }}>
                            <div class="doc-type-inner">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2" y="5" width="20" height="14" rx="2"/><path stroke-linecap="round" d="M16 10h2M16 14h2M6 10h5M6 14h3"/></svg>
                                <span>PAN Card</span>
                            </div>
                        </label>

                        <label class="doc-type-label">
                            <input type="radio" name="document_type" value="aadhaar"
                                   onchange="onDocTypeChange('aadhaar')"
                                   {{ old('document_type') === 'aadhaar' ? 'checked' : '' }}>
                            <div class="doc-type-inner">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 11c0-1.105.895-2 2-2s2 .895 2 2v3m-2-3c0-1.105-.895-2-2-2s-2 .895-2 2v3m8-1a8 8 0 11-16 0 8 8 0 0116 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M9 13v1a3 3 0 006 0v-1"/></svg>
                                <span>Aadhaar</span>
                            </div>
                        </label>

                        <label class="doc-type-label">
                            <input type="radio" name="document_type" value="passport"
                                   onchange="onDocTypeChange('passport')"
                                   {{ old('document_type') === 'passport' ? 'checked' : '' }}>
                            <div class="doc-type-inner">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0118 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/></svg>
                                <span>Passport</span>
                            </div>
                        </label>

                        <label class="doc-type-label">
                            <input type="radio" name="document_type" value="other"
                                   onchange="onDocTypeChange('other')"
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
                        onclick="document.getElementById('document_file').click()"
                        ondragover="handleDragOver(event)"
                        ondragleave="handleDragLeave(event)"
                        ondrop="handleDrop(event)"
                        class="drop-zone">

                        <input type="file" name="document_file" id="document_file"
                               accept=".pdf,.jpg,.jpeg,.png" style="display:none;"
                               onchange="handleFileSelect(this)">

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

                    <button type="submit" id="submitBtn" disabled class="submit-btn">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                        <span id="submitLabel">Complete Verification</span>
                    </button>

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
<script>
const docConfig = {
    pan:      { placeholder: 'ABCDE1234F',           hint: 'Format: ABCDE1234F (5 letters · 4 digits · 1 letter)' },
    aadhaar:  { placeholder: 'XXXX XXXX XXXX',       hint: 'Format: 12-digit Aadhaar number' },
    passport: { placeholder: 'A1234567',              hint: 'Format: Letter followed by 7 digits' },
    other:    { placeholder: 'Enter document number', hint: 'Enter the number printed on your document' },
};

let selectedDocType = '{{ old('document_type') ?? '' }}';
let hasFile = false;

function onDocTypeChange(type) {
    selectedDocType = type;
    const input  = document.getElementById('documentNumber');
    const hintEl = document.getElementById('docFormatHint');
    const config = docConfig[type];
    input.placeholder = config.placeholder;
    input.disabled = false;
    input.focus();
    hintEl.textContent = config.hint;
    checkReady();
}

function handleFileSelect(input) {
    if (input.files && input.files.length > 0) setFileSelected(input.files[0].name);
}
function handleDragOver(e) {
    e.preventDefault();
    document.getElementById('dropZone').classList.add('drag-over');
}
function handleDragLeave(e) {
    document.getElementById('dropZone').classList.remove('drag-over');
}
function handleDrop(e) {
    e.preventDefault();
    handleDragLeave(e);
    const files = e.dataTransfer.files;
    if (files && files.length > 0) {
        const dt = new DataTransfer();
        dt.items.add(files[0]);
        document.getElementById('document_file').files = dt.files;
        setFileSelected(files[0].name);
    }
}
function resetDropZone() {
    const zone = document.getElementById('dropZone');
    zone.classList.remove('file-selected','drag-over');
    document.getElementById('uploadIconWrap').innerHTML = `
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width:36px;height:36px;color:var(--text3);">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 16V4m0 0L8 8m4-4l4 4"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2"/>
        </svg>`;
    document.getElementById('uploadPrimary').textContent = 'Drag & drop or click to upload';
    document.getElementById('uploadSub').style.display = '';
    document.getElementById('fileName').textContent = '';
    document.getElementById('filePreview').classList.remove('visible');
    hasFile = false;
}
function setFileSelected(name) {
    resetDropZone();
    hasFile = true;
    const zone = document.getElementById('dropZone');
    zone.classList.add('file-selected');
    document.getElementById('uploadIconWrap').innerHTML = `
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width:36px;height:36px;color:var(--green);">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>`;
    document.getElementById('uploadPrimary').textContent = 'File selected — click to change';
    document.getElementById('uploadSub').style.display = 'none';
    document.getElementById('fileName').textContent = name;
    document.getElementById('filePreview').classList.add('visible');
    checkReady();
}
function checkReady() {
    const numVal = document.getElementById('documentNumber').value.trim();
    const btn = document.getElementById('submitBtn');
    btn.disabled = !(selectedDocType && hasFile && numVal.length > 2);
}

document.getElementById('documentNumber').addEventListener('input', checkReady);

document.getElementById('kycForm').addEventListener('submit', function(e) {
    const btn = document.getElementById('submitBtn');
    const label = document.getElementById('submitLabel');
    label.textContent = 'Submitting…';
    btn.disabled = true;
    btn.style.opacity = '0.7';
    showToast();
    e.preventDefault();
    setTimeout(() => this.submit(), 600);
});

function showToast() {
    const t = document.getElementById('successToast');
    t.style.transform = 'translateX(-50%) translateY(0)';
    t.style.opacity = '1';
}

if (selectedDocType) onDocTypeChange(selectedDocType);
</script>
@endpush
