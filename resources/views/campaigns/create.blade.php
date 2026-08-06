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

    {{-- STEPPER: 6 steps (KYC removed — it's on its own page) --}}
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
                  <span class="input-prefix">₹</span>
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
                <p class="field-hint">A video can increase donations by up to 4×</p>
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
                  <div class="toggle-text"><div class="toggle-title">Urgent campaign</div><div class="toggle-desc">Adds a red urgent badge — use only when time-sensitive</div></div>
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
                <div class="title">Campaign updates &amp; documents <span style="color:var(--danger);font-size:11px;font-weight:700;margin-left:6px;">● Required</span></div>
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
            <div class="upload-zone" id="uploadZone" onclick="document.getElementById('coverInput').click()">
              <input type="file" id="coverInput" name="cover_image" accept="image/*">
              <div id="uploadPrompt">
                <div class="upload-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M17 8l-5-5-5 5M12 3v12"/></svg></div>
                <div class="upload-title">Drop your cover image here <span style="color:var(--danger);">*</span></div>
                <div class="upload-hint">or click to browse from your device</div>
                <div class="upload-btn">Choose file</div>
                <div style="font-size:11px;color:var(--ink-muted);margin-top:12px;">JPG or PNG · Max 2MB · Min 1200×630px recommended</div>
              </div>
              <div id="imagePreview">
                <img id="previewImg" src="" alt="Cover preview">
                <div id="fileName"></div>
                <div><span class="change-img-btn">Change image</span></div>
              </div>
            </div>
            <p style="font-size:12px;color:var(--ink-muted);margin-top:14px;line-height:1.6;text-align:center;">
              <strong style="color:var(--danger);">Required.</strong> Campaigns with a compelling cover image raise <strong style="color:var(--purple-main);">3× more</strong> on average.
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
              <div class="grand-total-amount" id="grandTotalAmount">₹0</div>
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
              <div class="review-row"><span class="review-label">Title</span><span class="review-value" id="rv-title">—</span></div>
              <div class="review-row"><span class="review-label">Goal amount</span><span class="review-value" id="rv-goal">—</span></div>
              <div class="review-row"><span class="review-label">Category</span><span class="review-value" id="rv-category">—</span></div>
              <div class="review-row"><span class="review-label">Location</span><span class="review-value" id="rv-location">—</span></div>
              <div class="review-row"><span class="review-label">Duration</span><span class="review-value" id="rv-dates">—</span></div>
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
                <span class="review-value" style="color:var(--purple-deep);font-size:15px;" id="rv-products-total">₹0</span>
              </div>
            </div>
            <div class="grand-summary-card" id="grandSummaryCard">
              <div class="grand-summary-header">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 7H6a2 2 0 00-2 2v9a2 2 0 002 2h12a2 2 0 002-2V9a2 2 0 00-2-2h-3M9 7V5a2 2 0 012-2h2a2 2 0 012 2v2M9 7h6"/></svg>
                Campaign financial summary
              </div>
              <div class="grand-summary-row"><span class="lbl">Fundraising goal</span><span class="val" id="gs-goal">—</span></div>
              <div class="grand-summary-row" id="gs-products-row" style="display:none;"><span class="lbl">Total product value</span><span class="val" id="gs-products">₹0</span></div>
              <div class="grand-summary-total-row"><span class="lbl">Combined total</span><span class="val" id="gs-combined">—</span></div>
            </div>

            {{-- KYC next-step notice (replaces old KYC form) --}}
            <div class="review-notice" style="margin-top:16px;">
              <div class="review-notice-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
              </div>
              <div class="review-notice-text">
                After submitting you'll be taken to <strong>KYC verification</strong> — upload your Aadhaar/PAN/Passport to activate your campaign. It only takes 2 minutes.
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

{{-- Pass admin category products to JS --}}
<script>
var categoryProductsMap = {};
@foreach($categoryProducts as $product)
  @if($product->is_active)
  (function(){
    var cid = '{{ $product->category_id }}';
    if (!categoryProductsMap[cid]) categoryProductsMap[cid] = [];
    categoryProductsMap[cid].push({
      id:    {{ $product->id }},
      name:  @json($product->name),
      price: {{ (float) $product->price }},
      desc:  @json($product->description ?? ''),
      stock: {{ (int) ($product->stock ?? 10) }},
      image: @json($product->image_url ?? ''),
    });
  })();
  @endif
@endforeach

</script>

<script>
var WizardState = {
  currentStep: 1,
  totalSteps: 6,
  isNavigating: false,
  isSubmitting: false,
  _boundHandlers: null,

  stepMeta: {
    1:{badge:'Step 1 of 6',heading:'Campaign basics',         sub:'Start with the essential information about your campaign.'},
    2:{badge:'Step 2 of 6',heading:'Additional details',      sub:"Help donors understand where, when, and how you'll fundraise."},
    3:{badge:'Step 3 of 6',heading:'Updates & documents',     sub:'At least one update with title & description is required.'},
    4:{badge:'Step 4 of 6',heading:'Cover image',             sub:'A great image makes your campaign stand out and builds trust.'},
    5:{badge:'Step 5 of 6',heading:'Fundraiser products',     sub:'Optional — add products donors can purchase to support your cause.'},
    6:{badge:'Step 6 of 6',heading:'Review & submit',         sub:'Almost there — check everything, then submit to begin KYC.'},
  },

  progressMap: {1:'16.6%',2:'33.2%',3:'49.8%',4:'66.4%',5:'83%',6:'100%'},

  els: {},

  init: function(){
    this.restoreStep();
    this.cacheElements();
    this.bindEvents();
    this.applyStep();
  },

  cacheElements: function(){
    this.els.btnBack    = document.getElementById('btnBack');
    this.els.btnNext    = document.getElementById('btnNext');
    this.els.btnSubmit  = document.getElementById('btnSubmit');
    this.els.nav        = document.getElementById('wizardNav');
    this.els.progressBar= document.getElementById('progressBar');
    this.els.stepBadge  = document.getElementById('stepBadge');
    this.els.stepHeading= document.getElementById('stepHeading');
    this.els.stepSub    = document.getElementById('stepSub');
    this.els.stepCounter= document.getElementById('stepCounter');
  },

  bindEvents: function(){
    if (this._boundHandlers) return;
    this._boundHandlers = true;

    this.els.btnBack.addEventListener('click', this.goBack.bind(this));
    this.els.btnNext.addEventListener('click', this.goNext.bind(this));

    var form = document.getElementById('campaignForm');
    form.addEventListener('submit', this.onFormSubmit.bind(this));
  },

  restoreStep: function(){
    try {
      var saved = sessionStorage.getItem('campaign_wizard_step');
      if (saved !== null) {
        var step = parseInt(saved, 10);
        if (step >= 1 && step <= this.totalSteps) {
          this.currentStep = step;
        }
      }
    } catch(e) { /* sessionStorage unavailable */ }
  },

  persistStep: function(){
    try {
      sessionStorage.setItem('campaign_wizard_step', String(this.currentStep));
    } catch(e) { /* sessionStorage unavailable */ }
  },

  applyStep: function(){
    this.updateNav();
    this.updateHeader();
    this.updateDots(0, this.currentStep);
    this.updateProgress();
    this.updateStepCounter();
  },

  goBack: function(){
    if (this.isNavigating || this.currentStep <= 1) return;
    this.changeStep(-1);
  },

  goNext: function(){
    if (this.isNavigating || this.currentStep >= this.totalSteps) return;
    if (!this.validateStep(this.currentStep)) return;
    this.changeStep(1);
  },

  changeStep: function(dir){
    this.isNavigating = true;
    var prev = this.currentStep;
    this.currentStep = Math.max(1, Math.min(this.totalSteps, this.currentStep + dir));
    this.persistStep();

    document.getElementById('step-' + prev).classList.remove('active');
    document.getElementById('step-' + this.currentStep).classList.add('active');

    this.updateDots(prev, this.currentStep);
    this.updateNav();
    this.updateHeader();
    this.updateProgress();
    this.updateStepCounter();

    if (this.currentStep === 6) populateReview();
    if (this.currentStep === 5) renderSuggestions();

    window.scrollTo({top:0, behavior:'smooth'});

    var self = this;
    requestAnimationFrame(function(){ self.isNavigating = false; });
  },

  updateNav: function(){
    var s = this.currentStep;
    var t = this.totalSteps;

    this.els.btnBack.classList.toggle('wizard-nav__btn--hidden', s <= 1);
    this.els.btnNext.classList.toggle('wizard-nav__btn--hidden', s >= t);
    this.els.btnSubmit.classList.toggle('wizard-nav__btn--hidden', s !== t);

    var spacer = this.els.nav.querySelector('.wizard-nav__spacer');
    if (spacer) {
      spacer.style.display = (s > 1 && s < t) ? '' : 'none';
    }
  },

  updateHeader: function(){
    var m = this.stepMeta[this.currentStep];
    this.els.stepBadge.textContent   = m.badge;
    this.els.stepHeading.textContent = m.heading;
    this.els.stepSub.textContent     = m.sub;
  },

  updateDots: function(prev, current){
    if (prev > 0) {
      var pd=document.getElementById('dot-'+prev), pl=document.getElementById('label-'+prev), pi=document.getElementById('sitem-'+prev);
      if(current>prev){
        pd.classList.remove('active'); pd.classList.add('done');
        pd.innerHTML='<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:14px;height:14px;"><path d="M20 6L9 17l-5-5"/></svg>';
        pl.classList.remove('active'); pl.classList.add('done'); pi.classList.remove('active');
      } else {
        pd.classList.remove('done','active'); pd.classList.add('active'); pd.textContent=prev;
        pl.classList.remove('active','done'); pl.classList.add('active');
      }
    }
    var cd=document.getElementById('dot-'+current), cl=document.getElementById('label-'+current);
    cd.classList.remove('done'); cd.classList.add('active'); cd.textContent=current;
    cl.classList.remove('done'); cl.classList.add('active');
    document.getElementById('sitem-'+current).classList.add('active');
  },

  updateProgress: function(){
    this.els.progressBar.style.width = this.progressMap[this.currentStep];
  },

  updateStepCounter: function(){
    this.els.stepCounter.textContent = this.currentStep;
  },

  onFormSubmit: function(e){
    if (this.isSubmitting) return;
    this.isSubmitting = true;
    this.els.btnSubmit.disabled = true;
    this.els.btnSubmit.setAttribute('aria-busy','true');
    this.els.btnSubmit.classList.add('wizard-nav__btn--loading');
    var goal = document.getElementById('goalAmount');
    if (goal) goal.value = goal.value.replace(/,/g,'');
  },

  validateStep: function(step){
    if(step===1){
      var title = document.querySelector('[name=title]').value.trim();
      if(!title){ showToast('Please enter a campaign title.'); document.querySelector('[name=title]').focus(); return false; }
      if(title.length < 5){ showToast('Campaign title must be at least 5 characters long.'); document.querySelector('[name=title]').focus(); return false; }
      var goalRaw = document.getElementById('goalAmount').value.replace(/,/g, '').trim();
      if(!goalRaw){ showToast('Please enter a goal amount.'); document.getElementById('goalAmount').focus(); return false; }
      if(isNaN(parseFloat(goalRaw)) || parseFloat(goalRaw) <= 0){ showToast('Goal amount must be a valid number greater than 0.'); document.getElementById('goalAmount').focus(); return false; }
      if(!document.querySelector('[name=category_id]').value){ showToast('Please select a category.'); document.querySelector('[name=category_id]').focus(); return false; }
      var desc = document.querySelector('[name=description]').value.trim();
      if(!desc){ showToast('Please enter a campaign description.'); document.querySelector('[name=description]').focus(); return false; }
      if(desc.length < 20){ showToast('Description must be at least 20 characters long.'); document.querySelector('[name=description]').focus(); return false; }
    }
    if(step===3){
      var entries=document.querySelectorAll('.update-entry');
      if(entries.length===0){ showToast('Please add at least one update or document before continuing.'); return false; }
      var hasValid=false;
      for(var i=0;i<entries.length;i++){
        var title=entries[i].querySelector('[data-ufield="title"]').value.trim();
        var body=entries[i].querySelector('[data-ufield="body"]').value.trim();
        if(title && body){ hasValid=true; }
        if(body && !title){ showToast('Please enter a title for update #'+(i+1)+'.'); return false; }
        if(title && !body){ showToast('Please enter a description for update #'+(i+1)+'.'); return false; }
      }
      if(!hasValid){ showToast('Each update needs both a title and a description.'); return false; }
    }
    if(step===4){
      var coverInput=document.getElementById('coverInput');
      if(!coverInput.files || coverInput.files.length===0){
        document.getElementById('uploadZone').classList.add('required-error');
        showToast('Please upload a cover image before continuing.');
        return false;
      }
      if(coverInput.files[0].size > 2 * 1024 * 1024){
        document.getElementById('uploadZone').classList.add('required-error');
        showToast('Image size must be less than 2MB.');
        coverInput.value = '';
        document.getElementById('uploadPrompt').style.display = '';
        document.getElementById('imagePreview').classList.remove('show');
        return false;
      }
    }
    if(step===5){
      var items=document.querySelectorAll('.product-item');
      for(var i=0;i<items.length;i++){
        var item=items[i];
        var name=item.querySelector('[data-field="name"]').value.trim();
        var price=item.querySelector('[data-field="price"]').value.trim();
        var qty=item.querySelector('[data-field="stock"]').value.trim();
        if(name && !price){showToast('Enter a price for "'+name+'".'); return false;}
        if(name && !qty)  {showToast('Enter a quantity for "'+name+'".'); return false;}
        if(price && !name){showToast('Enter a name for the product with price ₹'+price+'.'); return false;}
      }
    }
    return true;
  }
};

document.addEventListener('DOMContentLoaded', function(){
  WizardState.init();
});

/* ── TOAST ── */
function showToast(html, type, duration){
  type = type||'purple'; duration = duration||3000;
  var el=document.createElement('div');
  el.className='toast-alert '+type;
  el.innerHTML=html;
  document.body.appendChild(el);
  setTimeout(function(){el.style.opacity='0';el.style.transition='opacity .4s';},duration);
  setTimeout(function(){if(el.parentNode)el.remove();},duration+450);
}

/* ── SUBMIT ── */
document.getElementById('campaignForm').addEventListener('submit', function(){
  var goal = document.getElementById('goalAmount');
  goal.value = goal.value.replace(/,/g,'');
});

/* ── UPDATES ── */
var updateCount = 0;
document.getElementById('addUpdateBtn').addEventListener('click',function(){addUpdate();});
function addUpdate(){
  updateCount++;
  var id=updateCount, fid='docFile-'+id, fnid='docName-'+id;
  var html='<div class="update-entry" id="update-'+id+'">'+
    '<div class="update-entry-header">'+
      '<span class="update-entry-num"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>Update '+id+'</span>'+
      '<button type="button" class="remove-update-btn" onclick="removeUpdate('+id+')"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg></button>'+
    '</div>'+
    '<div class="field-stack">'+
      '<div class="field-wrap"><label class="field-label">Update title <span>*</span></label><input type="text" name="updates['+id+'][title]" data-ufield="title" class="field-input" placeholder="e.g. Week 2 progress report"></div>'+
      '<div class="field-wrap"><label class="field-label">Update body <span>*</span></label><textarea name="updates['+id+'][body]" data-ufield="body" class="field-input" rows="3" placeholder="Share your progress, milestones, or any relevant information..."></textarea></div>'+
      '<div class="field-wrap"><label class="field-label">Attach document <span style="color:var(--ink-muted);font-weight:400;text-transform:none;letter-spacing:0;">(optional)</span></label>'+
        '<div class="doc-attach-row">'+
          '<label class="doc-attach-label" for="'+fid+'"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21.44 11.05l-9.19 9.19a6 6 0 01-8.49-8.49l9.19-9.19a4 4 0 015.66 5.66l-9.2 9.19a2 2 0 01-2.83-2.83l8.49-8.48"/></svg>Attach file</label>'+
          '<input type="file" id="'+fid+'" name="updates['+id+'][document]" accept=".pdf,.jpg,.jpeg,.png,.docx,.xlsx" style="display:none" onchange="showDocName('+id+',this)">'+
          '<span class="doc-filename" id="'+fnid+'">No file chosen</span>'+
        '</div>'+
      '</div>'+
    '</div></div>';
  document.getElementById('updateEntries').insertAdjacentHTML('beforeend',html);
}
function removeUpdate(id){
  var el=document.getElementById('update-'+id); if(!el)return;
  el.style.opacity='0'; el.style.transform='scale(.97)'; el.style.transition='all .25s';
  setTimeout(function(){el.remove();},260);
}
function showDocName(id,input){
  var nameEl=document.getElementById('docName-'+id);
  if(input.files&&input.files[0]){
    var f=input.files[0];
    nameEl.innerHTML='<span class="doc-preview-tag"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:11px;height:11px;"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>'+f.name+'</span>';
  } else {
    nameEl.textContent='No file chosen';
  }
}

/* ── PRODUCTS ── */
var productCount = 0;
function renderSuggestions(){
  var wrap=document.getElementById('suggestionsWrap');
  wrap.innerHTML='';
  var catEl=document.getElementById('categorySelect');
  var catId=catEl.value;
  var list=categoryProductsMap[catId];
  if(!list||list.length===0){
    wrap.innerHTML='<span style="font-size:12px;color:var(--ink-muted);">No admin products found for this category. Add your own below.</span>';
    return;
  }
  list.forEach(function(s){
    var pill=document.createElement('button');
    pill.type='button';
    pill.className='suggestion-pill';
    var imgHtml = s.image
      ? '<img src="'+s.image+'" class="suggestion-pill-img" onerror="this.style.display=\'none\'">'
      : '<span class="suggestion-pill-placeholder"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2zM16 3H8l-2 4h12l-2-4z"/></svg></span>';
    pill.innerHTML = imgHtml + s.name + ' &middot; ₹' + s.price.toLocaleString('en-IN');
    pill.onclick = function(){ addProduct(s.id, s.name, s.price, s.desc, s.stock, s.image); };
    wrap.appendChild(pill);
  });
}

function addProduct(categoryProductId, name, price, desc, stock, image){
  categoryProductId = categoryProductId || '';
  productCount++;
  var id = productCount;
  var imgPreviewHtml = image
    ? '<div class="product-img-preview-wrap"><img src="'+image+'" class="product-img-preview" onerror="this.style.display=\'none\'"></div>'
    : '';
  var html='<div class="product-item" id="product-'+id+'">'+
    '<div class="product-item-header">'+
      '<span class="product-item-num"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2zM16 3H8l-2 4h12l-2-4z"/></svg>Product '+id+'</span>'+
      '<button type="button" class="remove-product-btn" onclick="removeProduct('+id+')"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg></button>'+
    '</div>'+
    '<div class="product-img-upload-wrap">'+
      '<label class="product-img-label" for="prodImg-'+id+'">'+
        '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>'+
        '<span>Add Image</span>'+
      '</label>'+
      '<input type="file" id="prodImg-'+id+'" name="products['+id+'][image]" accept="image/jpeg,image/png,image/webp" style="display:none" onchange="previewProdImg('+id+',this)">'+
      '<div class="product-img-preview-wrap" id="prodImgPreview-'+id+'">'+imgPreviewHtml+'</div>'+
    '</div>'+
    '<div class="field-stack">'+
      '<div class="field-wrap"><label class="field-label">Product name <span>*</span></label><input type="text" name="products['+id+'][name]" data-field="name" class="field-input" placeholder="e.g. Handmade bracelet" value="'+(name||'')+'"></div>'+
      '<div class="field-wrap"><label class="field-label">Description</label><textarea name="products['+id+'][description]" data-field="description" class="field-input" rows="2" placeholder="Brief description...">'+(desc||'')+'</textarea></div>'+
      '<div class="field-grid-3">'+
        '<div class="field-wrap"><label class="field-label">Price (₹) <span>*</span></label><div class="input-prefix-wrap"><span class="input-prefix">₹</span><input type="number" name="products['+id+'][price]" data-field="price" class="field-input" placeholder="199" min="0" step="1" value="'+(price||'')+'" oninput="recalcProduct('+id+')"></div></div>'+
        '<div class="field-wrap"><label class="field-label">Quantity <span>*</span></label><input type="number" name="products['+id+'][stock]" data-field="stock" class="field-input" placeholder="10" min="1" step="1" value="'+(stock||'')+'" oninput="recalcProduct('+id+')"></div>'+
        '<div class="field-wrap"><label class="field-label">Status</label><select name="products['+id+'][is_active]" class="field-input"><option value="1">Active</option><option value="0">Hidden</option></select></div>'+
      '</div>'+
      '<div class="product-subtotal-row"><span class="product-subtotal-label">Subtotal (price × qty)</span><span class="product-subtotal-value" id="subtotal-'+id+'">₹0</span></div>'+
    '</div></div>';
  document.getElementById('productList').insertAdjacentHTML('beforeend',html);
  if(price) recalcProduct(id);
  updateGrandTotal();
}

function removeProduct(id){
  var el=document.getElementById('product-'+id); if(!el)return;
  el.style.opacity='0'; el.style.transform='scale(.97)'; el.style.transition='all .25s';
  setTimeout(function(){el.remove();updateGrandTotal();},260);
}
function recalcProduct(id){
  var item=document.getElementById('product-'+id); if(!item)return;
  var price=parseFloat(item.querySelector('[data-field="price"]').value)||0;
  var qty=parseFloat(item.querySelector('[data-field="stock"]').value)||0;
  document.getElementById('subtotal-'+id).textContent='₹'+Math.round(price*qty).toLocaleString('en-IN');
  updateGrandTotal();
}
function updateGrandTotal(){
  var items=document.querySelectorAll('.product-item');
  var grand=0,count=0;
  items.forEach(function(item){
    var price=parseFloat(item.querySelector('[data-field="price"]').value)||0;
    var qty=parseFloat(item.querySelector('[data-field="stock"]').value)||0;
    if(price>0&&qty>0){grand+=price*qty;count++;}
  });
  var card=document.getElementById('grandTotalCard');
  if(items.length===0){card.style.display='none';return;}
  card.style.display='flex';
  document.getElementById('grandTotalAmount').textContent='₹'+Math.round(grand).toLocaleString('en-IN');
  document.getElementById('grandTotalSub').textContent=count+' product'+(count!==1?'s':'')+' with price & qty filled';
}

/* ── REVIEW ── */
function getGoalRaw(){return parseFloat(document.getElementById('goalAmount').value.replace(/,/g,''))||0;}
function populateReview(){
  var catEl=document.querySelector('[name=category_id]');
  var start=document.querySelector('[name=start_date]').value;
  var end=document.querySelector('[name=end_date]').value;
  var fileEl=document.getElementById('coverInput');
  var goalRaw=getGoalRaw();
  document.getElementById('rv-title').textContent    = document.querySelector('[name=title]').value||'—';
  document.getElementById('rv-goal').textContent     = goalRaw?'₹'+Math.round(goalRaw).toLocaleString('en-IN'):'—';
  document.getElementById('rv-category').textContent = catEl.options[catEl.selectedIndex]?catEl.options[catEl.selectedIndex].text:'—';
  document.getElementById('rv-location').textContent = document.querySelector('[name=location]').value||'—';
  document.getElementById('rv-dates').textContent    = (start&&end)?start+' → '+end:(start||end||'—');
  document.getElementById('rv-image').textContent    = (fileEl.files&&fileEl.files.length)?fileEl.files[0].name:'Not uploaded';

  var updateEntries=document.querySelectorAll('.update-entry');
  var rvUpdatesCard=document.getElementById('rvUpdatesCard');
  if(updateEntries.length>0){
    rvUpdatesCard.style.display='';
    document.getElementById('rvUpdateCount').textContent='('+updateEntries.length+')';
    document.getElementById('rvUpdatesBody').innerHTML=Array.from(updateEntries).map(function(entry){
      var title=entry.querySelector('[data-ufield="title"]').value.trim();
      var fi=entry.querySelector('input[type="file"]');
      var docName=(fi&&fi.files&&fi.files[0])?fi.files[0].name:'';
      if(!title) return '';
      return '<span class="review-update-pill">'+title+(docName?' · 📎'+docName:'')+'</span>';
    }).join('');
  } else { rvUpdatesCard.style.display='none'; }

  var items=document.querySelectorAll('.product-item');
  var products=[],productTotal=0;
  items.forEach(function(item){
    var name=item.querySelector('[data-field="name"]').value.trim();
    var price=parseFloat(item.querySelector('[data-field="price"]').value)||0;
    var qty=parseFloat(item.querySelector('[data-field="stock"]').value)||0;
    if(name){products.push({name:name,price:price,qty:qty});productTotal+=price*qty;}
  });
  var rvCard=document.getElementById('rvProductsCard');
  if(products.length>0){
    rvCard.style.display='';
    document.getElementById('rvProductCount').textContent='('+products.length+')';
    document.getElementById('rv-products-total').textContent='₹'+Math.round(productTotal).toLocaleString('en-IN');
    document.getElementById('rvProductsBody').innerHTML=products.map(function(p){
      return '<span class="review-product-pill">'+p.name+(p.price?' · ₹'+p.price.toLocaleString('en-IN'):'')+(p.qty?' · qty '+p.qty:'')+(p.price&&p.qty?' · <strong>₹'+Math.round(p.price*p.qty).toLocaleString('en-IN')+'</strong>':'')+'</span>';
    }).join('');
  } else { rvCard.style.display='none'; }

  var combined=goalRaw+productTotal;
  document.getElementById('gs-goal').textContent='₹'+Math.round(goalRaw).toLocaleString('en-IN');
  document.getElementById('gs-combined').textContent='₹'+Math.round(combined).toLocaleString('en-IN');
  var gsRow=document.getElementById('gs-products-row');
  if(productTotal>0){gsRow.style.display='flex';document.getElementById('gs-products').textContent='₹'+Math.round(productTotal).toLocaleString('en-IN');}
  else{gsRow.style.display='none';}
}

/* ── PRODUCT IMAGE PREVIEW ── */
function previewProdImg(id,input){
  var wrap=document.getElementById('prodImgPreview-'+id);
  wrap.innerHTML='';
  if(input.files&&input.files[0]){
    var reader=new FileReader();
    reader.onload=function(e){
      wrap.innerHTML='<img src="'+e.target.result+'" class="product-img-preview">';
    };
    reader.readAsDataURL(input.files[0]);
  }
}

/* ── UTILITIES ── */
var goalInput=document.getElementById('goalAmount');
goalInput.addEventListener('input',function(e){
  var v=e.target.value.replace(/,/g,'');
  if(!v) return;
  var n=parseInt(v);
  if(!isNaN(n)) e.target.value=n.toLocaleString('en-IN');
});
goalInput.addEventListener('keypress',function(e){if(!/[0-9]/.test(e.key))e.preventDefault();});

var titleInput=document.getElementById('titleInput');
var descInput=document.getElementById('descInput');
if(titleInput) titleInput.addEventListener('input',function(){document.getElementById('titleCount').textContent=titleInput.value.length;});
if(descInput)  descInput.addEventListener('input', function(){document.getElementById('descCount').textContent=descInput.value.length+' / 20000';});

document.getElementById('coverInput').addEventListener('change',function(e){
  var file=e.target.files[0]; if(!file) return;
  document.getElementById('uploadZone').classList.remove('required-error');
  var reader=new FileReader();
  reader.onload=function(ev){
    document.getElementById('previewImg').src=ev.target.result;
    document.getElementById('fileName').textContent=file.name+' · '+(file.size/1024).toFixed(0)+' KB';
    document.getElementById('uploadPrompt').style.display='none';
    document.getElementById('imagePreview').classList.add('show');
  };
  reader.readAsDataURL(file);
});

document.getElementById('addProductBtn').addEventListener('click',function(){
  addProduct('','','','','','');
});
</script>

@endsection