@extends('layouts.app')

@section('content')

@push('styles') @vite(['resources/css/public/campaigns-old.css']) @endpush

<div class="page-shell">
    <div class="shell-inner">

        <div class="page-header">
            <div class="page-eyebrow"><span></span> New Campaign</div>
            <h1 class="page-title">Launch Your Fundraiser</h1>
            <p class="page-subtitle">Share your story, set a goal, and start making an impact today.</p>
        </div>

        <div class="stepper-wrap">
            @foreach([['1','Basics'],['2','Details'],['3','Media'],['4','Products'],['5','Review']] as $i => [$num,$label])
            <div class="stepper-item {{ $i === 0 ? 'active' : '' }}" id="sitem-{{ $num }}">
                <div class="stepper-dot {{ $i === 0 ? 'active' : '' }}" id="dot-{{ $num }}">{{ $num }}</div>
                <span class="stepper-label {{ $i === 0 ? 'active' : '' }}" id="label-{{ $num }}">{{ $label }}</span>
            </div>
            @endforeach
        </div>

        @if ($errors->any())
            <div class="error-box">
                <ul>@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        <div class="form-card">
            <div class="progress-track">
                <div class="progress-fill" id="progressBar" style="width:20%;"></div>
            </div>

            <form action="{{ route('campaign.store') }}" method="POST" enctype="multipart/form-data" id="campaignForm">
                @csrf

                <div class="card-header-bar">
                    <div class="step-badge" id="stepBadge">Step 1 of 5</div>
                    <div class="step-heading" id="stepHeading">Campaign basics</div>
                    <p class="step-sub" id="stepSub">Start with the essential information about your campaign.</p>
                </div>

                <div class="card-body">

                    {{-- STEP 1 --}}
                    <div class="step-panel active" id="step-1">
                        <div class="field-stack">
                            <div class="field-wrap">
                                <label class="field-label">Campaign title <span>*</span></label>
                                <input type="text" name="title" class="field-input"
                                    value="{{ old('title') }}"
                                    placeholder="e.g. Help rebuild our community library"
                                    maxlength="100" id="titleInput">
                                <div class="char-counter"><span id="titleCount">0</span> / 100</div>
                            </div>
                            <div class="field-wrap">
                                <label class="field-label">Goal amount <span>*</span></label>
                                <div class="input-prefix-wrap">
                                    <span class="input-prefix">₹</span>
                                    <input type="text" id="goalAmount" name="goal_amount" class="field-input"
                                        value="{{ old('goal_amount') }}" placeholder="5,00,000">
                                </div>
                                <p class="field-hint">Enter the total amount you need to raise in Indian Rupees</p>
                            </div>
                            <div class="field-wrap">
                                <label class="field-label">Category <span>*</span></label>
                                <select name="category_id" id="categorySelect" class="field-input">
                                    <option value="">Select a category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            data-name="{{ $category->name }}"
                                            {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="field-wrap">
                                <label class="field-label">Campaign description <span>*</span></label>
                                <textarea name="description" class="field-input" rows="5"
                                    placeholder="Tell people why this campaign matters..."
                                    maxlength="1000" id="descInput">{{ old('description') }}</textarea>
                                <div class="char-counter"><span id="descCount">0</span> / 1000</div>
                            </div>
                        </div>
                    </div>

                    {{-- STEP 2 --}}
                    <div class="step-panel" id="step-2">
                        <div class="field-stack">
                            <div class="section-title">Location &amp; links</div>
                            <div class="field-wrap">
                                <label class="field-label">Location</label>
                                <input type="text" name="location" class="field-input"
                                    value="{{ old('location') }}" placeholder="e.g. Mumbai, Maharashtra">
                            </div>
                            <div class="field-wrap">
                                <label class="field-label">Video URL</label>
                                <input type="url" name="video_url" class="field-input"
                                    value="{{ old('video_url') }}" placeholder="https://youtube.com/...">
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
                                    <input type="checkbox" class="toggle-input" name="is_featured" {{ old('is_featured') ? 'checked' : '' }}>
                                    <div class="toggle-icon">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                                    </div>
                                    <div class="toggle-text">
                                        <div class="toggle-title">Featured campaign</div>
                                        <div class="toggle-desc">Shown prominently on the homepage</div>
                                    </div>
                                    <div class="toggle-track"><div class="toggle-thumb"></div></div>
                                </label>
                                <label class="toggle-card">
                                    <input type="checkbox" class="toggle-input" name="is_urgent" {{ old('is_urgent') ? 'checked' : '' }}>
                                    <div class="toggle-icon">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                    </div>
                                    <div class="toggle-text">
                                        <div class="toggle-title">Urgent campaign</div>
                                        <div class="toggle-desc">Adds a red urgent badge — use only when time-sensitive</div>
                                    </div>
                                    <div class="toggle-track"><div class="toggle-thumb"></div></div>
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- STEP 3 --}}
                    <div class="step-panel" id="step-3">
                        <div class="upload-zone" onclick="document.getElementById('coverInput').click()">
                            <input type="file" id="coverInput" name="cover_image" accept="image/*">
                            <div id="uploadPrompt">
                                <div class="upload-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M17 8l-5-5-5 5M12 3v12"/></svg>
                                </div>
                                <div class="upload-title">Drop your cover image here</div>
                                <div class="upload-hint">or click to browse from your device</div>
                                <div class="upload-btn">Choose file</div>
                                <div style="font-size:11px;color:var(--ink-muted);margin-top:12px;">JPG or PNG &middot; Max 2MB &middot; Min 1200&times;630px recommended</div>
                            </div>
                            <div id="imagePreview">
                                <img id="previewImg" src="" alt="Cover preview">
                                <div id="fileName"></div>
                                <div><span class="change-img-btn">Change image</span></div>
                            </div>
                        </div>
                        <p style="font-size:12px;color:var(--ink-muted);margin-top:14px;line-height:1.6;text-align:center;">
                            Campaigns with a compelling cover image raise <strong style="color:var(--purple-main);">3× more</strong> on average.
                        </p>
                    </div>

                    {{-- STEP 4 --}}
                    <div class="step-panel" id="step-4">
                        <div style="display:flex;align-items:flex-start;gap:14px;padding:16px 18px;background:var(--purple-mist);border:1px solid var(--border);border-radius:16px;margin-bottom:20px;">
                            <div style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,var(--purple-main),var(--indigo-main));display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" style="width:17px;height:17px;"><path d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2zM16 3H8l-2 4h12l-2-4z"/></svg>
                            </div>
                            <div>
                                <div style="font-size:13px;font-weight:600;color:var(--ink);margin-bottom:3px;">Fundraiser products</div>
                                <div style="font-size:12px;color:var(--ink-soft);line-height:1.6;">Add products donors can buy to support your cause. Pick from suggestions or add your own.</div>
                            </div>
                        </div>

                        <div class="suggestions-label">Suggested for your category</div>
                        <div class="suggestions-wrap" id="suggestionsWrap"></div>

                        <div id="productList" class="product-list"></div>

                        <x-button variant="primary" type="button" class="add-product-btn">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v8M8 12h8"/></svg>
                            Add a product
                        </x-button>

                        <div class="grand-total-card grand-total-hidden" id="grandTotalCard">
                            <div>
                                <div class="grand-total-left">Total product value</div>
                                <div class="grand-total-sub" id="grandTotalSub"></div>
                            </div>
                            <div class="grand-total-amount" id="grandTotalAmount">₹0</div>
                        </div>

                        <div class="skip-note">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4m0 4h.01"/></svg>
                            This step is completely optional. You can skip it and add products later from your campaign dashboard after approval.
                        </div>
                    </div>

                    {{-- STEP 5 --}}
                    <div class="step-panel" id="step-5">

                        <div class="review-card">
                            <div class="review-card-header"><div class="review-card-title">Campaign summary</div></div>
                            <div class="review-row"><span class="review-label">Title</span><span class="review-value" id="rv-title">—</span></div>
                            <div class="review-row"><span class="review-label">Goal amount</span><span class="review-value" id="rv-goal">—</span></div>
                            <div class="review-row"><span class="review-label">Category</span><span class="review-value" id="rv-category">—</span></div>
                            <div class="review-row"><span class="review-label">Location</span><span class="review-value" id="rv-location">—</span></div>
                            <div class="review-row"><span class="review-label">Duration</span><span class="review-value" id="rv-dates">—</span></div>
                            <div class="review-row"><span class="review-label">Cover image</span><span class="review-value" id="rv-image">Not uploaded</span></div>
                        </div>

                        <div class="review-card" id="rvProductsCard" style="display:none;">
                            <div class="review-card-header">
                                <div class="review-card-title">Products <span id="rvProductCount" style="font-weight:400;color:var(--ink-soft);"></span></div>
                            </div>
                            <div class="review-products-body" id="rvProductsBody"></div>
                            <div class="review-row" style="background:var(--purple-mist);">
                                <span class="review-label" style="font-weight:600;color:var(--ink-mid);">Total product value</span>
                                <span class="review-value" style="color:var(--purple-deep);font-size:15px;" id="rv-products-total">₹0</span>
                            </div>
                        </div>

                        {{-- / Grand Summary Card --}}
                        <div class="grand-summary-card" id="grandSummaryCard">
                            <div class="grand-summary-header">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 7H6a2 2 0 00-2 2v9a2 2 0 002 2h12a2 2 0 002-2V9a2 2 0 00-2-2h-3M9 7V5a2 2 0 012-2h2a2 2 0 012 2v2M9 7h6"/></svg>
                                Campaign financial summary
                            </div>
                            <div class="grand-summary-row">
                                <span class="lbl">Fundraising goal</span>
                                <span class="val" id="gs-goal">—</span>
                            </div>
                            <div class="grand-summary-row" id="gs-products-row" style="display:none;">
                                <span class="lbl">Total product value</span>
                                <span class="val" id="gs-products">₹0</span>
                            </div>
                            <div class="grand-summary-total-row">
                                <span class="lbl">Combined total</span>
                                <span class="val" id="gs-combined">—</span>
                            </div>
                        </div>

                        <div class="review-notice" style="margin-top:16px;">
                            <div class="review-notice-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            </div>
                            <div class="review-notice-text">
                                Your campaign will be reviewed by our team within <strong>24 hours</strong> before going live. By submitting you agree to our fundraising guidelines.
                            </div>
                        </div>
                    </div>

                    <div class="form-nav">
                        <x-button variant="secondary" type="button">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                            Back
                        </x-button>
                        <div style="flex:1;"></div>
                        <x-button variant="primary" type="button">
                            Continue
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </x-button>
                        <x-button variant="primary" type="submit">
                            Submit campaign
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg>
                        </x-button>
                    </div>

                </div>
            </form>
        </div>

        <span class="step-pill">Step <span id="stepCounter">1</span> of 5</span>

    </div>
</div>

<script>
let currentStep  = 1;
const totalSteps = 5;
let productCount = 0;

const stepMeta = {
    1: { badge:'Step 1 of 5', heading:'Campaign basics',     sub:'Start with the essential information about your campaign.' },
    2: { badge:'Step 2 of 5', heading:'Additional details',  sub:"Help donors understand where, when, and how you'll fundraise." },
    3: { badge:'Step 3 of 5', heading:'Cover image',         sub:'A great image makes your campaign stand out and builds trust.' },
    4: { badge:'Step 4 of 5', heading:'Fundraiser products', sub:'Optional — add products donors can purchase to support your cause.' },
    5: { badge:'Step 5 of 5', heading:'Review & submit',     sub:'Almost there — check everything before you go live.' },
};

const progressMap = { 1:'20%', 2:'40%', 3:'60%', 4:'80%', 5:'100%' };

const categorySuggestions = {
    'Disaster Relief': [
        {name:'Emergency kit',   price:500,  desc:'Basic survival supply kit'},
        {name:'Food parcel',     price:200,  desc:'Nutritious food for one family'},
        {name:'Blanket set',     price:350,  desc:'Warm blankets for 2 people'},
        {name:'Water purifier',  price:800,  desc:'Portable water purifier'},
    ],
    'Education': [
        {name:'School kit',      price:250,  desc:'Notebook, pens, and ruler set'},
        {name:'Textbook bundle', price:450,  desc:'Core subject textbooks'},
        {name:'Scholarship pin', price:100,  desc:'Support badge for donors'},
        {name:'Digital course',  price:999,  desc:'Online learning access'},
    ],
    'Healthcare': [
        {name:'First aid kit',   price:600,  desc:'Complete first aid supplies'},
        {name:'Medicine pack',   price:400,  desc:'Essential medicines for one month'},
        {name:'Health wristband',price:150,  desc:'Awareness wristband'},
        {name:'Sanitizer pack',  price:200,  desc:'Hand sanitizer bottles x5'},
    ],
    'Environment': [
        {name:'Tree sapling',    price:50,   desc:'Plant a tree in your name'},
        {name:'Seed kit',        price:150,  desc:'Home gardening starter pack'},
        {name:'Eco tote bag',    price:250,  desc:'Reusable eco-friendly bag'},
        {name:'Solar lantern',   price:800,  desc:'Solar-powered lantern'},
    ],
    'Animal Welfare': [
        {name:'Food bag',        price:300,  desc:'Dog/cat food for one month'},
        {name:'Adoption kit',    price:500,  desc:'Basic care kit for adopted pet'},
        {name:'Vet checkup',     price:700,  desc:'Sponsor a vet visit'},
        {name:'Shelter brick',   price:100,  desc:'Symbolic shelter contribution'},
    ],
    'Community': [
        {name:'Tote bag',        price:199,  desc:'Branded community tote'},
        {name:'Membership card', price:50,   desc:'Annual community membership'},
        {name:'Event ticket',    price:300,  desc:'Access to community events'},
        {name:'Hoodie',          price:899,  desc:'Community hoodie'},
    ],
    'default': [
        {name:'Thank you card',  price:50,   desc:'Personalised thank you card'},
        {name:'Sticker pack',    price:100,  desc:'Campaign sticker set'},
        {name:'Tote bag',        price:299,  desc:'Canvas tote bag'},
        {name:'Custom mug',      price:499,  desc:'Ceramic mug with campaign logo'},
    ],
};

function renderSuggestions() {
    const wrap = document.getElementById('suggestionsWrap');
    wrap.innerHTML = '';
    const catEl   = document.getElementById('categorySelect');
    const catName = catEl.options[catEl.selectedIndex] ? (catEl.options[catEl.selectedIndex].dataset.name || catEl.options[catEl.selectedIndex].text) : '';
    const list    = categorySuggestions[catName] || categorySuggestions['default'];
    list.forEach(function(s) {
        const pill = document.createElement('button');
        pill.type  = 'button';
        pill.className = 'suggestion-pill';
        pill.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:12px;height:12px;"><path d="M12 5v14M5 12h14"/></svg>' + s.name + ' &middot; ₹' + s.price.toLocaleString('en-IN');
        pill.onclick = function() { addProduct(s.name, s.price, s.desc); };
        wrap.appendChild(pill);
    });
}

function changeStep(dir) {
    if (dir === 1 && !validateStep(currentStep)) return;
    const prev = currentStep;
    currentStep = Math.max(1, Math.min(totalSteps, currentStep + dir));
    if (currentStep === totalSteps) populateReview();
    if (currentStep === 4) renderSuggestions();

    document.getElementById('step-' + prev).classList.remove('active');
    document.getElementById('step-' + currentStep).classList.add('active');
    updateDots(prev, currentStep);
    updateNav();
    updateHeader();
    document.getElementById('progressBar').style.width = progressMap[currentStep];
    document.getElementById('stepCounter').textContent = currentStep;
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function updateHeader() {
    const m = stepMeta[currentStep];
    document.getElementById('stepBadge').textContent   = m.badge;
    document.getElementById('stepHeading').textContent = m.heading;
    document.getElementById('stepSub').textContent     = m.sub;
}

function updateDots(prev, current) {
    const pd = document.getElementById('dot-' + prev);
    const pl = document.getElementById('label-' + prev);
    const pi = document.getElementById('sitem-' + prev);
    if (current > prev) {
        pd.classList.remove('active'); pd.classList.add('done');
        pd.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:14px;height:14px;"><path d="M20 6L9 17l-5-5"/></svg>';
        pl.classList.remove('active'); pl.classList.add('done');
        pi.classList.remove('active');
    } else {
        pd.classList.remove('done','active'); pd.classList.add('active');
        pd.textContent = prev;
        pl.classList.remove('active','done'); pl.classList.add('active');
    }
    const cd = document.getElementById('dot-' + current);
    const cl = document.getElementById('label-' + current);
    cd.classList.remove('done'); cd.classList.add('active');
    cd.textContent = current;
    cl.classList.remove('done'); cl.classList.add('active');
    document.getElementById('sitem-' + current).classList.add('active');
}

function updateNav() {
    document.getElementById('btnBack').style.display   = currentStep > 1 ? 'inline-flex' : 'none';
    document.getElementById('btnNext').style.display   = currentStep < totalSteps ? 'inline-flex' : 'none';
    document.getElementById('btnSubmit').style.display = currentStep === totalSteps ? 'inline-flex' : 'none';
}

function validateStep(step) {
    if (step === 1) {
        if (!document.querySelector('[name=title]').value.trim())       { showToast('Please enter a campaign title.'); return false; }
        if (!document.getElementById('goalAmount').value.trim())        { showToast('Please enter a goal amount.'); return false; }
        if (!document.querySelector('[name=category_id]').value)        { showToast('Please select a category.'); return false; }
        if (!document.querySelector('[name=description]').value.trim()) { showToast('Please enter a campaign description.'); return false; }
    }
    if (step === 4) {
        const items = document.querySelectorAll('.product-item');
        for (const item of items) {
            const name  = item.querySelector('[data-field="name"]').value.trim();
            const price = item.querySelector('[data-field="price"]').value.trim();
            const qty   = item.querySelector('[data-field="stock"]').value.trim();
            if (name && !price) { showToast('Enter a price for "' + name + '".'); return false; }
            if (name && !qty)   { showToast('Enter a quantity for "' + name + '".'); return false; }
            if (price && !name) { showToast('Enter a name for the product with price ₹' + price + '.'); return false; }
        }
    }
    return true;
}

function showToast(msg) {
    const el = document.createElement('div');
    el.className   = 'toast-alert';
    el.textContent = msg;
    document.body.appendChild(el);
    setTimeout(function() { el.style.opacity = '0'; el.style.transition = 'opacity .3s'; }, 2500);
    setTimeout(function() { el.remove(); }, 3000);
}

function addProduct(name, price, desc) {
    productCount++;
    const id  = productCount;
    const html =
        '<div class="product-item" id="product-' + id + '">' +
            '<div class="product-item-header">' +
                '<span class="product-item-num">' +
                    '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2zM16 3H8l-2 4h12l-2-4z"/></svg>' +
                    'Product ' + id +
                '</span>' +
                '<x-button variant="primary" type="button" class="remove-product-btn">' +
                    '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>' +
                '</x-button>' +
            '</div>' +
            '<div class="field-stack">' +
                '<div class="field-wrap">' +
                    '<label class="field-label">Product name <span>*</span></label>' +
                    '<input type="text" name="products[' + id + '][name]" data-field="name" class="field-input" placeholder="e.g. Handmade bracelet" value="' + (name||'') + '">' +
                '</div>' +
                '<div class="field-wrap">' +
                    '<label class="field-label">Description</label>' +
                    '<textarea name="products[' + id + '][description]" data-field="description" class="field-input" rows="2" placeholder="Brief description...">' + (desc||'') + '</textarea>' +
                '</div>' +
                '<div class="field-grid-3">' +
                    '<div class="field-wrap">' +
                        '<label class="field-label">Price (₹) <span>*</span></label>' +
                        '<div class="input-prefix-wrap">' +
                            '<span class="input-prefix">₹</span>' +
                            '<input type="number" name="products[' + id + '][price]" data-field="price" class="field-input" placeholder="199" min="0" step="1" value="' + (price||'') + '" oninput="recalcProduct(' + id + ')">' +
                        '</div>' +
                    '</div>' +
                    '<div class="field-wrap">' +
                        '<label class="field-label">Quantity <span>*</span></label>' +
                        '<input type="number" name="products[' + id + '][stock]" data-field="stock" class="field-input" placeholder="10" min="1" step="1" oninput="recalcProduct(' + id + ')">' +
                    '</div>' +
                    '<div class="field-wrap">' +
                        '<label class="field-label">Status</label>' +
                        '<select name="products[' + id + '][is_active]" class="field-input"><option value="1">Active</option><option value="0">Hidden</option></select>' +
                    '</div>' +
                '</div>' +
                '<div class="product-subtotal-row">' +
                    '<span class="product-subtotal-label">Subtotal (price × qty)</span>' +
                    '<span class="product-subtotal-value" id="subtotal-' + id + '">₹0</span>' +
                '</div>' +
            '</div>' +
        '</div>';
    document.getElementById('productList').insertAdjacentHTML('beforeend', html);
    if (price) recalcProduct(id);
    updateGrandTotal();
}

function removeProduct(id) {
    const el = document.getElementById('product-' + id);
    if (!el) return;
    el.style.opacity = '0'; el.style.transform = 'scale(.97)'; el.style.transition = 'all .25s';
    setTimeout(function() { el.remove(); updateGrandTotal(); }, 260);
}

function recalcProduct(id) {
    const item  = document.getElementById('product-' + id);
    if (!item) return;
    const price = parseFloat(item.querySelector('[data-field="price"]').value) || 0;
    const qty   = parseFloat(item.querySelector('[data-field="stock"]').value) || 0;
    document.getElementById('subtotal-' + id).textContent = '₹' + Math.round(price * qty).toLocaleString('en-IN');
    updateGrandTotal();
}

function updateGrandTotal() {
    const items = document.querySelectorAll('.product-item');
    let grand = 0; let count = 0;
    items.forEach(function(item) {
        const id    = item.id.replace('product-', '');
        const price = parseFloat(item.querySelector('[data-field="price"]').value) || 0;
        const qty   = parseFloat(item.querySelector('[data-field="stock"]').value) || 0;
        if (price > 0 && qty > 0) { grand += price * qty; count++; }
    });
    const card = document.getElementById('grandTotalCard');
    if (items.length === 0) { card.classList.add('grand-total-hidden'); return; }
    card.classList.remove('grand-total-hidden');
    document.getElementById('grandTotalAmount').textContent = '₹' + Math.round(grand).toLocaleString('en-IN');
    document.getElementById('grandTotalSub').textContent    = count + ' product' + (count !== 1 ? 's' : '') + ' with price & qty filled';
}

function getGoalRaw() {
    return parseFloat(document.getElementById('goalAmount').value.replace(/,/g,'')) || 0;
}

function populateReview() {
    const catEl  = document.querySelector('[name=category_id]');
    const start  = document.querySelector('[name=start_date]').value;
    const end    = document.querySelector('[name=end_date]').value;
    const fileEl = document.getElementById('coverInput');
    const goalRaw = getGoalRaw();

    document.getElementById('rv-title').textContent    = document.querySelector('[name=title]').value || '—';
    document.getElementById('rv-goal').textContent     = goalRaw ? '₹' + Math.round(goalRaw).toLocaleString('en-IN') : '—';
    document.getElementById('rv-category').textContent = catEl.options[catEl.selectedIndex] ? catEl.options[catEl.selectedIndex].text : '—';
    document.getElementById('rv-location').textContent = document.querySelector('[name=location]').value || '—';
    document.getElementById('rv-dates').textContent    = (start && end) ? start + ' → ' + end : (start || end || '—');
    document.getElementById('rv-image').textContent    = (fileEl.files && fileEl.files.length) ? fileEl.files[0].name : 'Not uploaded';

    // Products
    const items = document.querySelectorAll('.product-item');
    const products = [];
    let productTotal = 0;
    items.forEach(function(item) {
        const name  = item.querySelector('[data-field="name"]').value.trim();
        const price = parseFloat(item.querySelector('[data-field="price"]').value) || 0;
        const qty   = parseFloat(item.querySelector('[data-field="stock"]').value) || 0;
        if (name) { products.push({ name, price, qty }); productTotal += price * qty; }
    });

    const rvCard  = document.getElementById('rvProductsCard');
    const rvBody  = document.getElementById('rvProductsBody');
    const rvCount = document.getElementById('rvProductCount');

    if (products.length > 0) {
        rvCard.style.display = '';
        rvCount.textContent  = '(' + products.length + ')';
        document.getElementById('rv-products-total').textContent = '₹' + Math.round(productTotal).toLocaleString('en-IN');
        rvBody.innerHTML = products.map(function(p) {
            return '<span class="review-product-pill">' + p.name +
                   (p.price ? ' &middot; ₹' + p.price.toLocaleString('en-IN') : '') +
                   (p.qty   ? ' &middot; qty ' + p.qty : '') +
                   (p.price && p.qty ? ' &middot; <strong>₹' + Math.round(p.price * p.qty).toLocaleString('en-IN') + '</strong>' : '') +
                   '</span>';
        }).join('');
    } else {
        rvCard.style.display = 'none';
    }

    // / Grand Summary Card
    const combined = goalRaw + productTotal;
    document.getElementById('gs-goal').textContent = goalRaw ? '₹' + Math.round(goalRaw).toLocaleString('en-IN') : '—';
    document.getElementById('gs-combined').textContent = '₹' + Math.round(combined).toLocaleString('en-IN');

    const gsProductsRow = document.getElementById('gs-products-row');
    if (productTotal > 0) {
        gsProductsRow.style.display = 'flex';
        document.getElementById('gs-products').textContent = '₹' + Math.round(productTotal).toLocaleString('en-IN');
    } else {
        gsProductsRow.style.display = 'none';
    }
}

// ── Indian number format ──
const goalInput = document.getElementById('goalAmount');
goalInput.addEventListener('input', function(e) {
    var v = e.target.value.replace(/,/g, '');
    if (!v) return;
    var n = parseInt(v);
    if (!isNaN(n)) e.target.value = n.toLocaleString('en-IN');
});
goalInput.addEventListener('keypress', function(e) { if (!/[0-9]/.test(e.key)) e.preventDefault(); });
document.getElementById('campaignForm').addEventListener('submit', function() {
    goalInput.value = goalInput.value.replace(/,/g, '');
});

// ── Char counters ──
var titleInput = document.getElementById('titleInput');
var descInput  = document.getElementById('descInput');
if (titleInput) titleInput.addEventListener('input', function() { document.getElementById('titleCount').textContent = titleInput.value.length; });
if (descInput)  descInput.addEventListener('input',  function() { document.getElementById('descCount').textContent  = descInput.value.length; });

// ── Image preview ──
document.getElementById('coverInput').addEventListener('change', function(e) {
    var file = e.target.files[0];
    if (!file) return;
    var reader = new FileReader();
    reader.onload = function(ev) {
        document.getElementById('previewImg').src = ev.target.result;
        document.getElementById('fileName').textContent = file.name + ' · ' + (file.size / 1024).toFixed(0) + ' KB';
        document.getElementById('uploadPrompt').style.display = 'none';
        document.getElementById('imagePreview').classList.add('show');
    };
    reader.readAsDataURL(file);
});

document.getElementById('addProductBtn').addEventListener('click', function() {
    addProduct('', '', '');
});
</script>

@endsection

