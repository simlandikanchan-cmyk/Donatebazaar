/* ═══════════════════════════════════════════════════════════════════
   Admin Events Create page — moved from admin/events/create.blade.php
   inline <script> blocks. window.* bridges converted to internal
   functions with data-action delegation; Blade @json data moved to the
   data-campaigns attribute on #campaignList; all logic preserved.
   ═══════════════════════════════════════════════════════════════════ */

(function(){
'use strict';
var currentStep = 1;
var state = { categoryId: null, categoryName: '', campaignId: null, campaignName: '' };

var campaignsDataEl = document.getElementById('campaignList');
var campaignsData = campaignsDataEl ? JSON.parse(campaignsDataEl.getAttribute('data-campaigns') || '{}') : {};

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

function goStep(step) {
  if (step < currentStep) showPanel(step);
}

function nextStep(from) {
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
}

function prevStep(from) { showPanel(from - 1); }

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

function selectCategory(id, name, emoji) {
  state.categoryId   = id;
  state.categoryName = emoji + ' ' + name;
  document.getElementById('categoryInput').value = id;
  document.querySelectorAll('.cat-card').forEach(function(c){ c.classList.remove('selected'); });
  document.querySelector('.cat-card[data-id="' + id + '"]').classList.add('selected');
  updateSummary();
}

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
    html += '<div class="campaign-item" data-id="' + c.id + '" data-name="' + escHtml(c.title) + '" data-action="select-campaign">'
      + thumb
      + '<div class="campaign-info"><div class="campaign-title">' + escHtml(c.title) + '</div>'
      + '<div class="campaign-meta">' + (goal ? goal + ' goal' : '') + ' · active</div></div>'
      + '<div class="campaign-check"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></div>'
      + '</div>';
  });
  list.innerHTML = html;
}

function selectCampaign(id, name) {
  state.campaignId   = id;
  state.campaignName = name;
  document.getElementById('campaignInput').value = id;
  document.querySelectorAll('.campaign-item').forEach(function(c){ c.classList.remove('selected'); });
  var el = document.querySelector('.campaign-item[data-id="' + id + '"]');
  if (el) el.classList.add('selected');
  updateSummary();
}

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

function setStatus(val) {
  document.getElementById('statusField').value = val;
  var badge = document.getElementById('summaryStatus');
  if (val === 'active') {
    badge.className = 'summary-badge sb-publish';
    badge.textContent = 'Active';
  } else {
    badge.className = 'summary-badge sb-draft';
    badge.textContent = 'Draft';
  }
}

function previewImage(input) {
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
}

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

/* ── delegated actions ── */
document.addEventListener('click', function (e) {
  var el = e.target.closest('[data-action]');
  if (!el) return;
  var action = el.getAttribute('data-action');

  if(action==='go-step'){goStep(parseInt(el.getAttribute('data-step'),10));}
  else if(action==='next-step'){nextStep(parseInt(el.getAttribute('data-step'),10));}
  else if(action==='prev-step'){prevStep(parseInt(el.getAttribute('data-step'),10));}
  else if(action==='select-category'){selectCategory(el.getAttribute('data-id'),el.getAttribute('data-cat-name'),el.getAttribute('data-emoji'));}
  else if(action==='select-campaign'){selectCampaign(el.getAttribute('data-id'),el.getAttribute('data-name'));}
  else if(action==='set-status'){setStatus(el.getAttribute('data-status'));}
});

document.addEventListener('change', function (e) {
  var el = e.target.closest('[data-action]');
  if (!el) return;
  var action = el.getAttribute('data-action');

  if(action==='preview-image'){previewImage(el);}
});

})();