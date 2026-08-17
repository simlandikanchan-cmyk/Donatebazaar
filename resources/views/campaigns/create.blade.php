@extends('layouts.app')

@section('content')

@push('styles') @vite(['resources/css/public/campaigns-create.css']) @endpush

<div class="page-shell">
  <div class="shell-inner">

    <div class="page-header">
      <div class="page-eyebrow"><span></span> New Campaign</div>
      <h1 class="page-title">Launch Your Fundraiser</h1>
      <p class="page-subtitle">Share your story, set a goal, and start making an impact today.</p>
    </div>

    {{-- STEPPER: 6 steps (KYC removed â€” it's on its own page) --}}
    <div class="stepper-wrap">
      @foreach([['1','Basics'],['2','Details'],['3','Updates'],['4','Media'],['5','Products'],['6','Review']] as $i => [$num,$label])
      <div class="stepper-item {{ $i===0?'active':'' }}" id="sitem-{{ $num }}">
        <div class="stepper-dot {{ $i===0?'active':'' }}" id="dot-{{ $num }}">{{ $num }}</div>
        <span class="stepper-label {{ $i===0?'active':'' }}" id="label-{{ $num }}">{{ $label }}</span>
      </div>
      @endforeach
    </div>

    @if($errors->any())
    <div class="error-box">
      <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <div class="form-card">
      <div class="progress-track">
        <div class="progress-fill" id="progressBar" style="width:16.6%;"></div>
      </div>

      <form action="{{ route('campaign.store') }}" method="POST" enctype="multipart/form-data" id="campaignForm">
        @csrf

        <div class="card-header-bar">
          <div class="step-badge" id="stepBadge">Step 1 of 6</div>
          <div class="step-heading" id="stepHeading">Campaign basics</div>
          <p class="step-sub" id="stepSub">Start with the essential information about your campaign.</p>
        </div>

        <div class="card-body">

          {{-- STEP 1: Basics --}}
          <div class="step-panel active" id="step-1">
            <div class="field-stack">
              <div class="field-wrap">
                <label class="field-label">Campaign title <span>*</span></label>
                <input type="text" name="title" class="field-input" value="{{ old('title') }}" placeholder="e.g. Help rebuild our community library" maxlength="100" id="titleInput">
                <div class="char-counter"><span id="titleCount">0</span> / 100</div>
              </div>
              <div class="field-wrap">
                <label class="field-label">Goal amount <span>*</span></label>
                <div class="input-prefix-wrap">
                  <span class="input-prefix">â‚¹</span>
                  <input type="text" id="goalAmount" name="goal_amount" class="field-input" value="{{ old('goal_amount') }}" placeholder="5,00,000">
                </div>
                <p class="field-hint">Enter the total amount you need to raise in Indian Rupees</p>
              </div>
              <div class="field-wrap">
                <label class="field-label">Category <span>*</span></label>
                <select name="category_id" id="categorySelect" class="field-input">
                  <option value="">Select a category</option>
                  @foreach($categories as $category)
                  <option value="{{ $category->id }}" data-id="{{ $category->id }}" {{ old('category_id')==$category->id?'selected':'' }}>{{ $category->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="field-wrap">
                <label class="field-label">Campaign description <span>*</span></label>
                <textarea name="description" class="field-input" rows="5" placeholder="Tell people why this campaign matters..." maxlength="20000" id="descInput">{{ old('description') }}</textarea>
                <div class="char-counter"><span id="descCount">0</span> / 20000</div>
              </div>
            </div>
          </div>

          {{-- STEP 2: Details --}}
          <div class="step-panel" id="step-2">
            <div class="field-stack">
              <div class="section-title">Location &amp; links</div>
              <div class="field-wrap">
                <label class="field-label">Location</label>
                <input type="text" name="location" class="field-input" value="{{ old('location') }}" placeholder="e.g. Mumbai, Maharashtra">
              </div>
              <div class="field-wrap">
                <label class="field-label">Video URL</label>
                <input type="url" name="video_url" class="field-input" value="{{ old('video_url') }}" placeholder="https://youtube.com/...">
                <p class="field-hint">A video can increase donations by up to 4Ã—</p>
              </div>
              <div class="section-title">Campaign duration</div>
              <div class="field-grid">
                <div class="field-wrap">
                  <label class="field-label">Start date</label>
                  <input type="date" name="start_date" class="field-input" value="{{ old('start_date') }}">
                </div>
                <div class="field-wrap">
                  <label class="field-label">End date</label>
                  <input type="date" name="end_date" class="field-input" value="{{ old('end_date') }}">
                </div>
              </div>
              <div class="section-title">Campaign options</div>
              <div class="toggle-set">
                <label class="toggle-card">
                  <input type="checkbox" class="toggle-input" name="is_featured" {{ old('is_featured')?'checked':'' }}>
                  <div class="toggle-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg></div>
                  <div class="toggle-text"><div class="toggle-title">Featured campaign</div><div class="toggle-desc">Shown prominently on the homepage</div></div>
                  <div class="toggle-track"><div class="toggle-thumb"></div></div>
                </label>
                <label class="toggle-card">
                  <input type="checkbox" class="toggle-input" name="is_urgent" {{ old('is_urgent')?'checked':'' }}>
                  <div class="toggle-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg></div>
                  <div class="toggle-text"><div class="toggle-title">Urgent campaign</div><div class="toggle-desc">Adds a red urgent badge â€” use only when time-sensitive</div></div>
                  <div class="toggle-track"><div class="toggle-thumb"></div></div>
                </label>
              </div>
            </div>
          </div>

          {{-- STEP 3: Updates & Documents --}}
          <div class="step-panel" id="step-3">
            <div class="updates-info-bar">
              <div class="updates-info-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" style="width:17px;height:17px;"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
              </div>
              <div class="updates-info-text">
                <div class="title">Campaign updates &amp; documents <span style="color:var(--danger);font-size:11px;font-weight:700;margin-left:6px;">â— Required</span></div>
                <div class="sub">Add at least one update or supporting document. Donors trust campaigns with transparent records.</div>
              </div>
            </div>
            <div id="updateEntries" class="update-entries"></div>
            <button type="button" class="add-update-btn" id="addUpdateBtn">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v8M8 12h8"/></svg>
              Add update or document
            </button>
            <div class="skip-note" style="background:rgba(254,242,242,.6);border-color:#fecaca;">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:var(--danger);"><circle cx="12" cy="12" r="10"/><path d="M12 8v4m0 4h.01"/></svg>
              <span><strong style="color:var(--danger);">Required:</strong> You must add at least one update with a title and description before continuing.</span>
            </div>
          </div>

          {{-- STEP 4: Media --}}
          <div class="step-panel" id="step-4">
            <div class="upload-zone" id="uploadZone" data-action="pick-cover">
              <input type="file" id="coverInput" name="cover_image" accept="image/*">
              <div id="uploadPrompt">
                <div class="upload-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M17 8l-5-5-5 5M12 3v12"/></svg></div>
                <div class="upload-title">Drop your cover image here <span style="color:var(--danger);">*</span></div>
                <div class="upload-hint">or click to browse from your device</div>
                <div class="upload-btn">Choose file</div>
                <div style="font-size:11px;color:var(--ink-muted);margin-top:12px;">JPG or PNG Â· Max 2MB Â· Min 1200Ã—630px recommended</div>
              </div>
              <div id="imagePreview">
                <img id="previewImg" src="" alt="Cover preview">
                <div id="fileName"></div>
                <div><span class="change-img-btn">Change image</span></div>
              </div>
            </div>
            <p style="font-size:12px;color:var(--ink-muted);margin-top:14px;line-height:1.6;text-align:center;">
              <strong style="color:var(--danger);">Required.</strong> Campaigns with a compelling cover image raise <strong style="color:var(--purple-main);">3Ã— more</strong> on average.
            </p>
          </div>

          {{-- STEP 5: Products --}}
          <div class="step-panel" id="step-5">
            <div style="display:flex;align-items:flex-start;gap:14px;padding:16px 18px;background:var(--purple-mist);border:1px solid var(--border);border-radius:16px;margin-bottom:20px;">
              <div style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,var(--purple-main),var(--indigo-main));display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" style="width:17px;height:17px;"><path d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2zM16 3H8l-2 4h12l-2-4z"/></svg>
              </div>
              <div>
                <div style="font-size:13px;font-weight:600;color:var(--ink);margin-bottom:3px;">Fundraiser products</div>
                <div style="font-size:12px;color:var(--ink-soft);line-height:1.6;">Pick from admin suggestions for your category or add your own custom products.</div>
              </div>
            </div>
            <div id="suggestionsSection">
              <div class="suggestions-label">Suggested for your category</div>
              <div class="suggestions-wrap" id="suggestionsWrap">
                <span style="font-size:12px;color:var(--ink-muted);">Select a category in Step 1 to see suggestions.</span>
              </div>
            </div>
            <div id="productList" class="product-list"></div>
            <button type="button" class="add-product-btn" id="addProductBtn">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v8M8 12h8"/></svg>
              Add a custom product
            </button>
            <div class="grand-total-card" id="grandTotalCard">
              <div>
                <div class="grand-total-left">Total product value</div>
                <div class="grand-total-sub" id="grandTotalSub"></div>
              </div>
              <div class="grand-total-amount" id="grandTotalAmount">â‚¹0</div>
            </div>
            <div class="skip-note">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4m0 4h.01"/></svg>
              Optional. You can add products after approval from your dashboard.
            </div>
          </div>

          {{-- STEP 6: Review --}}
          <div class="step-panel" id="step-6">
            <div class="review-card">
              <div class="review-card-header"><div class="review-card-title">Campaign summary</div></div>
              <div class="review-row"><span class="review-label">Title</span><span class="review-value" id="rv-title">â€”</span></div>
              <div class="review-row"><span class="review-label">Goal amount</span><span class="review-value" id="rv-goal">â€”</span></div>
              <div class="review-row"><span class="review-label">Category</span><span class="review-value" id="rv-category">â€”</span></div>
              <div class="review-row"><span class="review-label">Location</span><span class="review-value" id="rv-location">â€”</span></div>
              <div class="review-row"><span class="review-label">Duration</span><span class="review-value" id="rv-dates">â€”</span></div>
              <div class="review-row"><span class="review-label">Cover image</span><span class="review-value" id="rv-image">Not uploaded</span></div>
            </div>
            <div class="review-card" id="rvUpdatesCard" style="display:none;">
              <div class="review-card-header"><div class="review-card-title">Updates &amp; documents <span id="rvUpdateCount" style="font-weight:400;color:var(--ink-soft);"></span></div></div>
              <div class="review-updates-body" id="rvUpdatesBody"></div>
            </div>
            <div class="review-card" id="rvProductsCard" style="display:none;">
              <div class="review-card-header"><div class="review-card-title">Products <span id="rvProductCount" style="font-weight:400;color:var(--ink-soft);"></span></div></div>
              <div class="review-products-body" id="rvProductsBody"></div>
              <div class="review-row" style="background:var(--purple-mist);">
                <span class="review-label" style="font-weight:600;color:var(--ink-mid);">Total product value</span>
                <span class="review-value" style="color:var(--purple-deep);font-size:15px;" id="rv-products-total">â‚¹0</span>
              </div>
            </div>
            <div class="grand-summary-card" id="grandSummaryCard">
              <div class="grand-summary-header">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 7H6a2 2 0 00-2 2v9a2 2 0 002 2h12a2 2 0 002-2V9a2 2 0 00-2-2h-3M9 7V5a2 2 0 012-2h2a2 2 0 012 2v2M9 7h6"/></svg>
                Campaign financial summary
              </div>
              <div class="grand-summary-row"><span class="lbl">Fundraising goal</span><span class="val" id="gs-goal">â€”</span></div>
              <div class="grand-summary-row" id="gs-products-row" style="display:none;"><span class="lbl">Total product value</span><span class="val" id="gs-products">â‚¹0</span></div>
              <div class="grand-summary-total-row"><span class="lbl">Combined total</span><span class="val" id="gs-combined">â€”</span></div>
            </div>

            {{-- KYC next-step notice (replaces old KYC form) --}}
            <div class="review-notice" style="margin-top:16px;">
              <div class="review-notice-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
              </div>
              <div class="review-notice-text">
                After submitting you'll be taken to <strong>KYC verification</strong> â€” upload your Aadhaar/PAN/Passport to activate your campaign. It only takes 2 minutes.
              </div>
            </div>
          </div>

          <nav class="wizard-nav" id="wizardNav" aria-label="Campaign wizard navigation">
            <button type="button" class="wizard-nav__btn wizard-nav__btn--back" id="btnBack" data-nav="back" aria-label="Go back to previous step">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
              Back
            </button>
            <div class="wizard-nav__spacer"></div>
            <button type="button" class="wizard-nav__btn wizard-nav__btn--primary" id="btnNext" data-nav="next" aria-label="Continue to next step">
              Continue
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </button>
            <button type="submit" class="wizard-nav__btn wizard-nav__btn--primary" id="btnSubmit" data-nav="submit" aria-label="Submit campaign and complete KYC">
              Submit &amp; complete KYC
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            </button>
          </nav>

        </div>
      </form>
    </div>

    <span class="step-pill">Step <span id="stepCounter">1</span> of 6</span>

  </div>
</div>

{{-- Admin category products for the wizard (read by campaigns-create.js) --}}
<script type="application/json" id="categoryProductsData">
@php
    $categoryProductsData = $categoryProducts->filter(fn($p) => $p->is_active)->map(fn($p) => [
        'id'          => (int) $p->id,
        'category_id' => (int) $p->category_id,
        'name'        => $p->name,
        'price'       => (float) $p->price,
        'desc'        => $p->description ?? '',
        'stock'       => (int) ($p->stock ?? 10),
        'image'       => $p->image_url ?? '',
    ])->values();
@endphp
@json($categoryProductsData)
</script>

@push('scripts')
@vite('resources/js/public/campaigns-create.js')
@endpush


@endsection
