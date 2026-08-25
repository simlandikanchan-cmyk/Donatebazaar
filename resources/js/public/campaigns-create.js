/* ═══════════════════════════════════════════════════════════════════
   public/campaigns-create.js — campaign wizard (create page)
   Reads server data from the #categoryProductsData JSON block.
   ═══════════════════════════════════════════════════════════════════ */

(function () {
  'use strict';

  function escapeHtml(str) {
    return String(str ?? '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
  }

  /* ── SERVER DATA ── */
  var categoryProductsMap = {};
  (function () {
    var dataEl = document.getElementById('categoryProductsData');
    if (!dataEl) return;
    try {
      var list = JSON.parse(dataEl.textContent);
      list.forEach(function (p) {
        if (!categoryProductsMap[p.category_id]) categoryProductsMap[p.category_id] = [];
        categoryProductsMap[p.category_id].push(p);
      });
    } catch (e) { /* invalid data — suggestions stay empty */ }
  })();

  /* ── TOAST (page-specific .toast-alert visuals) ── */
  function showToast(html, type, duration) {
    type = type || 'purple'; duration = duration || 3000;
    var el = document.createElement('div');
    el.className = 'toast-alert ' + type;
    el.innerHTML = html;
    document.body.appendChild(el);
    setTimeout(function () { el.style.opacity = '0'; el.style.transition = 'opacity .4s'; }, duration);
    setTimeout(function () { if (el.parentNode) el.remove(); }, duration + 450);
  }

  /* ── HELPERS ── */
  function esc(str) {
    if (str == null) return '';
    return String(str).replace(/[&<>"']/g, function (m) {
      return ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' })[m];
    });
  }

  /* ── WIZARD ── */
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

    init: function () {
      this.restoreStep();
      this.cacheElements();
      this.bindEvents();
      this.applyStep();
    },

    cacheElements: function () {
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

    bindEvents: function () {
      if (this._boundHandlers) return;
      this._boundHandlers = true;

      this.els.btnBack.addEventListener('click', this.goBack.bind(this));
      this.els.btnNext.addEventListener('click', this.goNext.bind(this));

      var form = document.getElementById('campaignForm');
      form.addEventListener('submit', this.onFormSubmit.bind(this));
    },

    restoreStep: function () {
      try {
        var saved = sessionStorage.getItem('campaign_wizard_step');
        if (saved !== null) {
          var step = parseInt(saved, 10);
          if (step >= 1 && step <= this.totalSteps) {
            this.currentStep = step;
          }
        }
      } catch (e) { /* sessionStorage unavailable */ }
    },

    persistStep: function () {
      try {
        sessionStorage.setItem('campaign_wizard_step', String(this.currentStep));
      } catch (e) { /* sessionStorage unavailable */ }
    },

    applyStep: function () {
      this.updateNav();
      this.updateHeader();
      this.updateDots(0, this.currentStep);
      this.updateProgress();
      this.updateStepCounter();
    },

    goBack: function () {
      if (this.isNavigating || this.currentStep <= 1) return;
      this.changeStep(-1);
    },

    goNext: function () {
      if (this.isNavigating || this.currentStep >= this.totalSteps) return;
      if (!this.validateStep(this.currentStep)) return;
      this.changeStep(1);
    },

    changeStep: function (dir) {
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

      window.scrollTo({ top: 0, behavior: 'smooth' });

      var self = this;
      requestAnimationFrame(function () { self.isNavigating = false; });
    },

    updateNav: function () {
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

    updateHeader: function () {
      var m = this.stepMeta[this.currentStep];
      this.els.stepBadge.textContent   = m.badge;
      this.els.stepHeading.textContent = m.heading;
      this.els.stepSub.textContent     = m.sub;
    },

    updateDots: function (prev, current) {
      if (prev > 0) {
        var pd = document.getElementById('dot-' + prev), pl = document.getElementById('label-' + prev), pi = document.getElementById('sitem-' + prev);
        if (current > prev) {
          pd.classList.remove('active'); pd.classList.add('done');
          pd.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:14px;height:14px;"><path d="M20 6L9 17l-5-5"/></svg>';
          pl.classList.remove('active'); pl.classList.add('done'); pi.classList.remove('active');
        } else {
          pd.classList.remove('done', 'active'); pd.classList.add('active'); pd.textContent = prev;
          pl.classList.remove('active', 'done'); pl.classList.add('active');
        }
      }
      var cd = document.getElementById('dot-' + current), cl = document.getElementById('label-' + current);
      cd.classList.remove('done'); cd.classList.add('active'); cd.textContent = current;
      cl.classList.remove('done'); cl.classList.add('active');
      document.getElementById('sitem-' + current).classList.add('active');
    },

    updateProgress: function () {
      this.els.progressBar.style.width = this.progressMap[this.currentStep];
    },

    updateStepCounter: function () {
      this.els.stepCounter.textContent = this.currentStep;
    },

    onFormSubmit: function (e) {
      if (this.isSubmitting) return;
      this.isSubmitting = true;
      this.els.btnSubmit.disabled = true;
      this.els.btnSubmit.setAttribute('aria-busy', 'true');
      this.els.btnSubmit.classList.add('wizard-nav__btn--loading');
      var goal = document.getElementById('goalAmount');
      if (goal) goal.value = goal.value.replace(/,/g, '');
    },

    validateStep: function (step) {
      if (step === 1) {
        var title = document.querySelector('[name=title]').value.trim();
        if (!title) { showToast('Please enter a campaign title.'); document.querySelector('[name=title]').focus(); return false; }
        if (title.length < 5) { showToast('Campaign title must be at least 5 characters long.'); document.querySelector('[name=title]').focus(); return false; }
        var goalRaw = document.getElementById('goalAmount').value.replace(/,/g, '').trim();
        if (!goalRaw) { showToast('Please enter a goal amount.'); document.getElementById('goalAmount').focus(); return false; }
        if (isNaN(parseFloat(goalRaw)) || parseFloat(goalRaw) <= 0) { showToast('Goal amount must be a valid number greater than 0.'); document.getElementById('goalAmount').focus(); return false; }
        if (!document.querySelector('[name=category_id]').value) { showToast('Please select a category.'); document.querySelector('[name=category_id]').focus(); return false; }
        var desc = document.querySelector('[name=description]').value.trim();
        if (!desc) { showToast('Please enter a campaign description.'); document.querySelector('[name=description]').focus(); return false; }
        if (desc.length < 20) { showToast('Description must be at least 20 characters long.'); document.querySelector('[name=description]').focus(); return false; }
      }
      if (step === 3) {
        var entries = document.querySelectorAll('.update-entry');
        if (entries.length === 0) { showToast('Please add at least one update or document before continuing.'); return false; }
        var hasValid = false;
        for (var i = 0; i < entries.length; i++) {
          var title = entries[i].querySelector('[data-ufield="title"]').value.trim();
          var body = entries[i].querySelector('[data-ufield="body"]').value.trim();
          if (title && body) { hasValid = true; }
          if (body && !title) { showToast('Please enter a title for update #' + (i + 1) + '.'); return false; }
          if (title && !body) { showToast('Please enter a description for update #' + (i + 1) + '.'); return false; }
        }
        if (!hasValid) { showToast('Each update needs both a title and a description.'); return false; }
      }
      if (step === 4) {
        var coverInput = document.getElementById('coverInput');
        if (!coverInput.files || coverInput.files.length === 0) {
          document.getElementById('uploadZone').classList.add('required-error');
          showToast('Please upload a cover image before continuing.');
          return false;
        }
        if (coverInput.files[0].size > 2 * 1024 * 1024) {
          document.getElementById('uploadZone').classList.add('required-error');
          showToast('Image size must be less than 2MB.');
          coverInput.value = '';
          document.getElementById('uploadPrompt').style.display = '';
          document.getElementById('imagePreview').classList.remove('show');
          return false;
        }
      }
      if (step === 5) {
        var items = document.querySelectorAll('.product-item');
        for (var i = 0; i < items.length; i++) {
          var item = items[i];
          var name = item.querySelector('[data-field="name"]').value.trim();
          var price = item.querySelector('[data-field="price"]').value.trim();
          var qty = item.querySelector('[data-field="stock"]').value.trim();
          if (name && !price) { showToast('Enter a price for "' + name + '".'); return false; }
          if (name && !qty) { showToast('Enter a quantity for "' + name + '".'); return false; }
          if (price && !name) { showToast('Enter a name for the product with price ₹' + price + '.'); return false; }
        }
      }
      return true;
    }
  };

  document.addEventListener('DOMContentLoaded', function () {
    WizardState.init();
  });

  /* ── SUBMIT ── */
  document.getElementById('campaignForm').addEventListener('submit', function () {
    var goal = document.getElementById('goalAmount');
    goal.value = goal.value.replace(/,/g, '');
  });

  /* ── UPDATES ── */
  var updateCount = 0;
  document.getElementById('addUpdateBtn').addEventListener('click', function () { addUpdate(); });
  function addUpdate() {
    updateCount++;
    var id = updateCount, fid = 'docFile-' + id, fnid = 'docName-' + id;
    var html = '<div class="update-entry" id="update-' + id + '">' +
      '<div class="update-entry-header">' +
        '<span class="update-entry-num"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>Update ' + id + '</span>' +
        '<button type="button" class="remove-update-btn" data-action="remove-update" data-id="' + id + '"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg></button>' +
      '</div>' +
      '<div class="field-stack">' +
        '<div class="field-wrap"><label class="field-label">Update title <span>*</span></label><input type="text" name="updates[' + id + '][title]" data-ufield="title" class="field-input" placeholder="e.g. Week 2 progress report"></div>' +
        '<div class="field-wrap"><label class="field-label">Update body <span>*</span></label><textarea name="updates[' + id + '][body]" data-ufield="body" class="field-input" rows="3" placeholder="Share your progress, milestones, or any relevant information..."></textarea></div>' +
        '<div class="field-wrap"><label class="field-label">Attach document <span style="color:var(--ink-muted);font-weight:400;text-transform:none;letter-spacing:0;">(optional)</span></label>' +
          '<div class="doc-attach-row">' +
            '<label class="doc-attach-label" for="' + fid + '"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21.44 11.05l-9.19 9.19a6 6 0 01-8.49-8.49l9.19-9.19a4 4 0 015.66 5.66l-9.2 9.19a2 2 0 01-2.83-2.83l8.49-8.48"/></svg>Attach file</label>' +
            '<input type="file" id="' + fid + '" name="updates[' + id + '][document]" accept=".pdf,.jpg,.jpeg,.png,.docx,.xlsx" style="display:none" data-update-file="' + id + '">' +
            '<span class="doc-filename" id="' + fnid + '">No file chosen</span>' +
          '</div>' +
        '</div>' +
      '</div></div>';
    document.getElementById('updateEntries').insertAdjacentHTML('beforeend', html);
  }
  function removeUpdate(id) {
    var el = document.getElementById('update-' + id); if (!el) return;
    el.style.opacity = '0'; el.style.transform = 'scale(.97)'; el.style.transition = 'all .25s';
    setTimeout(function () { el.remove(); }, 260);
  }
  function showDocName(id, input) {
    var nameEl = document.getElementById('docName-' + id);
    if (input.files && input.files[0]) {
      var f = input.files[0];
      nameEl.innerHTML = '<span class="doc-preview-tag"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:11px;height:11px;"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>' + esc(f.name) + '</span>';
    } else {
      nameEl.textContent = 'No file chosen';
    }
  }

  /* Delegate clicks/changes inside the updates list (entries are dynamic). */
  document.getElementById('updateEntries').addEventListener('click', function (e) {
    var btn = e.target.closest('[data-action="remove-update"]');
    if (!btn) return;
    removeUpdate(parseInt(btn.getAttribute('data-id'), 10));
  });
  document.getElementById('updateEntries').addEventListener('change', function (e) {
    var input = e.target.closest('[data-update-file]');
    if (!input) return;
    showDocName(parseInt(input.getAttribute('data-update-file'), 10), input);
  });

  /* ── PRODUCTS ── */
  var productCount = 0;
  function renderSuggestions() {
    var wrap = document.getElementById('suggestionsWrap');
    wrap.innerHTML = '';
    var catEl = document.getElementById('categorySelect');
    var catId = catEl.value;
    var list = categoryProductsMap[catId];
    if (!list || list.length === 0) {
      wrap.innerHTML = '<span style="font-size:12px;color:var(--ink-muted);">No admin products found for this category. Add your own below.</span>';
      return;
    }
    list.forEach(function (s) {
      var pill = document.createElement('button');
      pill.type = 'button';
      pill.className = 'suggestion-pill';
      var imgHtml = s.image
        ? '<img src="' + s.image.replace(/"/g, '&quot;') + '" class="suggestion-pill-img" onerror="this.style.display=\'none\'">'
        : '<span class="suggestion-pill-placeholder"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2zM16 3H8l-2 4h12l-2-4z"/></svg></span>';
      pill.innerHTML = imgHtml + escapeHtml(s.name) + ' &middot; ₹' + s.price.toLocaleString('en-IN');
      pill.onclick = function () { addProduct(s.id, s.name, s.price, s.desc, s.stock, s.image); };
      wrap.appendChild(pill);
    });
  }

  function addProduct(categoryProductId, name, price, desc, stock, image) {
    categoryProductId = categoryProductId || '';
    productCount++;
    var id = productCount;
    var imgPreviewHtml = image
      ? '<div class="product-img-preview-wrap"><img src="' + image + '" class="product-img-preview" onerror="this.style.display=\'none\'"></div>'
      : '';
    var html = '<div class="product-item" id="product-' + id + '">' +
      '<div class="product-item-header">' +
        '<span class="product-item-num"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2zM16 3H8l-2 4h12l-2-4z"/></svg>Product ' + id + '</span>' +
        '<button type="button" class="remove-product-btn" data-action="remove-product" data-id="' + id + '"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg></button>' +
      '</div>' +
      '<div class="product-img-upload-wrap">' +
        '<label class="product-img-label" for="prodImg-' + id + '">' +
          '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>' +
          '<span>Add Image</span>' +
        '</label>' +
        '<input type="file" id="prodImg-' + id + '" name="products[' + id + '][image]" accept="image/jpeg,image/png,image/webp" style="display:none" data-product-image="' + id + '">' +
        '<div class="product-img-preview-wrap" id="prodImgPreview-' + id + '">' + imgPreviewHtml + '</div>' +
      '</div>' +
      '<div class="field-stack">' +
        '<div class="field-wrap"><label class="field-label">Product name <span>*</span></label><input type="text" name="products[' + id + '][name]" data-field="name" class="field-input" placeholder="e.g. Handmade bracelet" value="' + escapeHtml(name || '') + '"></div>' +
        '<div class="field-wrap"><label class="field-label">Description</label><textarea name="products[' + id + '][description]" data-field="description" class="field-input" rows="2" placeholder="Brief description...">' + escapeHtml(desc || '') + '</textarea></div>' +
        '<div class="field-grid-3">' +
          '<div class="field-wrap"><label class="field-label">Price (₹) <span>*</span></label><div class="input-prefix-wrap"><span class="input-prefix">₹</span><input type="number" name="products[' + id + '][price]" data-field="price" class="field-input" placeholder="199" min="0" step="1" value="' + (price || '') + '" data-product-id="' + id + '"></div></div>' +
          '<div class="field-wrap"><label class="field-label">Quantity <span>*</span></label><input type="number" name="products[' + id + '][stock]" data-field="stock" class="field-input" placeholder="10" min="1" step="1" value="' + (stock || '') + '" data-product-id="' + id + '"></div>' +
          '<div class="field-wrap"><label class="field-label">Status</label><select name="products[' + id + '][is_active]" class="field-input"><option value="1">Active</option><option value="0">Hidden</option></select></div>' +
        '</div>' +
        '<div class="product-subtotal-row"><span class="product-subtotal-label">Subtotal (price × qty)</span><span class="product-subtotal-value" id="subtotal-' + id + '">₹0</span></div>' +
      '</div></div>';
    document.getElementById('productList').insertAdjacentHTML('beforeend', html);
    if (price) recalcProduct(id);
    updateGrandTotal();
  }

  function removeProduct(id) {
    var el = document.getElementById('product-' + id); if (!el) return;
    el.style.opacity = '0'; el.style.transform = 'scale(.97)'; el.style.transition = 'all .25s';
    setTimeout(function () { el.remove(); updateGrandTotal(); }, 260);
  }
  function recalcProduct(id) {
    var item = document.getElementById('product-' + id); if (!item) return;
    var price = parseFloat(item.querySelector('[data-field="price"]').value) || 0;
    var qty = parseFloat(item.querySelector('[data-field="stock"]').value) || 0;
    document.getElementById('subtotal-' + id).textContent = '₹' + Math.round(price * qty).toLocaleString('en-IN');
    updateGrandTotal();
  }
  function updateGrandTotal() {
    var items = document.querySelectorAll('.product-item');
    var grand = 0, count = 0;
    items.forEach(function (item) {
      var price = parseFloat(item.querySelector('[data-field="price"]').value) || 0;
      var qty = parseFloat(item.querySelector('[data-field="stock"]').value) || 0;
      if (price > 0 && qty > 0) { grand += price * qty; count++; }
    });
    var card = document.getElementById('grandTotalCard');
    if (items.length === 0) { card.style.display = 'none'; return; }
    card.style.display = 'flex';
    document.getElementById('grandTotalAmount').textContent = '₹' + Math.round(grand).toLocaleString('en-IN');
    document.getElementById('grandTotalSub').textContent = count + ' product' + (count !== 1 ? 's' : '') + ' with price & qty filled';
  }

  /* Delegate clicks/inputs inside the product list (entries are dynamic). */
  document.getElementById('productList').addEventListener('click', function (e) {
    var btn = e.target.closest('[data-action="remove-product"]');
    if (!btn) return;
    removeProduct(parseInt(btn.getAttribute('data-id'), 10));
  });
  document.getElementById('productList').addEventListener('input', function (e) {
    var input = e.target.closest('[data-product-id]');
    if (!input) return;
    recalcProduct(parseInt(input.getAttribute('data-product-id'), 10));
  });
  document.getElementById('productList').addEventListener('change', function (e) {
    var input = e.target.closest('[data-product-image]');
    if (!input) return;
    previewProdImg(parseInt(input.getAttribute('data-product-image'), 10), input);
  });

  /* ── REVIEW ── */
  function getGoalRaw() { return parseFloat(document.getElementById('goalAmount').value.replace(/,/g, '')) || 0; }
  function populateReview() {
    var catEl = document.querySelector('[name=category_id]');
    var start = document.querySelector('[name=start_date]').value;
    var end = document.querySelector('[name=end_date]').value;
    var fileEl = document.getElementById('coverInput');
    var goalRaw = getGoalRaw();
    document.getElementById('rv-title').textContent    = document.querySelector('[name=title]').value || '—';
    document.getElementById('rv-goal').textContent     = goalRaw ? '₹' + Math.round(goalRaw).toLocaleString('en-IN') : '—';
    document.getElementById('rv-category').textContent = catEl.options[catEl.selectedIndex] ? catEl.options[catEl.selectedIndex].text : '—';
    document.getElementById('rv-location').textContent = document.querySelector('[name=location]').value || '—';
    document.getElementById('rv-dates').textContent    = (start && end) ? start + ' → ' + end : (start || end || '—');
    document.getElementById('rv-image').textContent    = (fileEl.files && fileEl.files.length) ? fileEl.files[0].name : 'Not uploaded';

    var updateEntries = document.querySelectorAll('.update-entry');
    var rvUpdatesCard = document.getElementById('rvUpdatesCard');
    if (updateEntries.length > 0) {
      rvUpdatesCard.style.display = '';
      document.getElementById('rvUpdateCount').textContent = '(' + updateEntries.length + ')';
      var updatesBody = document.getElementById('rvUpdatesBody');
      updatesBody.innerHTML = '';
      Array.from(updateEntries).forEach(function (entry) {
        var title = entry.querySelector('[data-ufield="title"]').value.trim();
        var fi = entry.querySelector('input[type="file"]');
        var docName = (fi && fi.files && fi.files[0]) ? fi.files[0].name : '';
        if (!title) return;
        var span = document.createElement('span');
        span.className = 'review-update-pill';
        span.textContent = title;
        if (docName) {
          var docSpan = document.createElement('span');
          docSpan.textContent = ' \u00b7 \ud83d\udcce' + docName;
          span.appendChild(docSpan);
        }
        updatesBody.appendChild(span);
      });
    } else { rvUpdatesCard.style.display = 'none'; }

    var items = document.querySelectorAll('.product-item');
    var products = [], productTotal = 0;
    items.forEach(function (item) {
      var name = item.querySelector('[data-field="name"]').value.trim();
      var price = parseFloat(item.querySelector('[data-field="price"]').value) || 0;
      var qty = parseFloat(item.querySelector('[data-field="stock"]').value) || 0;
      if (name) { products.push({ name: name, price: price, qty: qty }); productTotal += price * qty; }
    });
    var rvCard = document.getElementById('rvProductsCard');
    if (products.length > 0) {
      rvCard.style.display = '';
      document.getElementById('rvProductCount').textContent = '(' + products.length + ')';
      document.getElementById('rv-products-total').textContent = '\u20b9' + Math.round(productTotal).toLocaleString('en-IN');
      var productsBody = document.getElementById('rvProductsBody');
      productsBody.innerHTML = '';
      products.forEach(function (p) {
        var span = document.createElement('span');
        span.className = 'review-product-pill';
        span.textContent = p.name + ' \u00b7 \u20b9' + p.price.toLocaleString('en-IN') + ' \u00b7 qty ' + p.qty;
        if (p.price && p.qty) {
          var strong = document.createElement('strong');
          strong.textContent = ' \u00b7 \u20b9' + Math.round(p.price * p.qty).toLocaleString('en-IN');
          span.appendChild(strong);
        }
        productsBody.appendChild(span);
      });
    } else { rvCard.style.display = 'none'; }

    var combined = goalRaw + productTotal;
    document.getElementById('gs-goal').textContent = '₹' + Math.round(goalRaw).toLocaleString('en-IN');
    document.getElementById('gs-combined').textContent = '₹' + Math.round(combined).toLocaleString('en-IN');
    var gsRow = document.getElementById('gs-products-row');
    if (productTotal > 0) { gsRow.style.display = 'flex'; document.getElementById('gs-products').textContent = '₹' + Math.round(productTotal).toLocaleString('en-IN'); }
    else { gsRow.style.display = 'none'; }
  }

  /* ── PRODUCT IMAGE PREVIEW ── */
  function previewProdImg(id, input) {
    var wrap = document.getElementById('prodImgPreview-' + id);
    wrap.innerHTML = '';
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function (e) {
        wrap.innerHTML = '<img src="' + e.target.result + '" class="product-img-preview">';
      };
      reader.readAsDataURL(input.files[0]);
    }
  }

  /* ── UTILITIES ── */
  var goalInput = document.getElementById('goalAmount');
  goalInput.addEventListener('input', function (e) {
    var v = e.target.value.replace(/,/g, '');
    if (!v) return;
    var n = parseInt(v);
    if (!isNaN(n)) e.target.value = n.toLocaleString('en-IN');
  });
  goalInput.addEventListener('keypress', function (e) { if (!/[0-9]/.test(e.key)) e.preventDefault(); });

  var titleInput = document.getElementById('titleInput');
  var descInput = document.getElementById('descInput');
  if (titleInput) titleInput.addEventListener('input', function () { document.getElementById('titleCount').textContent = titleInput.value.length; });
  if (descInput) descInput.addEventListener('input', function () { document.getElementById('descCount').textContent = descInput.value.length + ' / 20000'; });

  var coverInput = document.getElementById('coverInput');
  document.getElementById('uploadZone').addEventListener('click', function () { coverInput.click(); });
  coverInput.addEventListener('change', function (e) {
    var file = e.target.files[0]; if (!file) return;
    document.getElementById('uploadZone').classList.remove('required-error');
    var reader = new FileReader();
    reader.onload = function (ev) {
      document.getElementById('previewImg').src = ev.target.result;
      document.getElementById('fileName').textContent = file.name + ' · ' + (file.size / 1024).toFixed(0) + ' KB';
      document.getElementById('uploadPrompt').style.display = 'none';
      document.getElementById('imagePreview').classList.add('show');
    };
    reader.readAsDataURL(file);
  });

  document.getElementById('addProductBtn').addEventListener('click', function () {
    addProduct('', '', '', '', '', '');
  });
})();