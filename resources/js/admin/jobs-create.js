import { toast } from '../shared/toast.js';

/* ═══════════════════════════════════════════════════════════════════
   Admin Job Post Create page — moved from admin/job_posts/create.blade.php
   inline <script>. Session toasts now read from the layout's #toastWrap
   data-success/data-error attributes; the old('slug') guard moved to a
   data-old attribute on the slug input; the tagsWrap click-to-focus
   handler moved from an inline onclick to a direct listener.
   ═══════════════════════════════════════════════════════════════════ */

(function () {
  'use strict';

  var toastWrapEl = document.getElementById('toastWrap');
  if (toastWrapEl) {
    var flashSuccess = toastWrapEl.getAttribute('data-success');
    if (flashSuccess) setTimeout(function(){ toast(flashSuccess, 'success', { duration: 4200 }); }, 200);
    var flashError = toastWrapEl.getAttribute('data-error');
    if (flashError) setTimeout(function(){ toast(flashError, 'error', { duration: 4200 }); }, 200);
  }

  /* ── SLUG AUTO-GENERATION ── */
  var titleInp  = document.getElementById('title');
  var slugInp   = document.getElementById('slug');
  var slugDisp  = document.getElementById('slugDisplay');
  var slugBtn   = document.getElementById('slugLockBtn');
  var slugAuto  = true;

  function toSlug(s) {
    return s.toLowerCase().replace(/[^a-z0-9\s-]/g,'').trim()
            .replace(/\s+/g,'-').replace(/-+/g,'-').slice(0,255);
  }
  function refreshSlug() {
    slugDisp.textContent = slugInp.value || toSlug(titleInp.value) || 'your-job-slug-here';
  }
  titleInp.addEventListener('input', function () {
    if (slugAuto) { slugInp.value = toSlug(this.value); refreshSlug(); }
  });
  slugInp.addEventListener('input', function () {
    slugAuto = false; slugBtn.textContent = 'Manual';
    slugBtn.style.cssText = 'color:var(--amber);border-color:var(--amber);';
    refreshSlug();
  });
  slugBtn.addEventListener('click', function () {
    slugAuto = !slugAuto;
    if (slugAuto) { slugInp.value = toSlug(titleInp.value); this.textContent = 'Auto'; this.style.cssText = ''; }
    else          { this.textContent = 'Manual'; this.style.cssText = 'color:var(--amber);border-color:var(--amber);'; }
    refreshSlug();
  });
  if (slugInp.getAttribute('data-old') === '1') { slugAuto = false; slugBtn.textContent = 'Manual'; slugBtn.style.cssText = 'color:var(--amber);border-color:var(--amber);'; }
  refreshSlug();

  /* ── Toggle rows ── */
  var remoteChk  = document.getElementById('is_remote');
  var remoteRow  = document.getElementById('remoteRow');
  var featChk    = document.getElementById('featured');
  var featRow    = document.getElementById('featuredRow');
  var featLabel  = document.getElementById('featuredLabel');

  function syncRemote()   { remoteRow.classList.toggle('active-toggle',       remoteChk.checked); }
  function syncFeatured() {
    featRow.classList.toggle('active-toggle-amber', featChk.checked);
    featLabel.style.background = featChk.checked ? 'var(--amber)' : '';
  }
  remoteChk.addEventListener('change', syncRemote);
  featChk.addEventListener('change',   syncFeatured);
  syncRemote(); syncFeatured();

  /* ── Vacancies badge ── */
  var vacInp   = document.getElementById('vacancies');
  var vacBadge = document.getElementById('vacancyBadge');
  function syncVac() {
    var v = parseInt(vacInp.value, 10);
    vacBadge.textContent = (!isNaN(v) && v > 0) ? v + ' open' : 'open';
  }
  vacInp.addEventListener('input', syncVac); syncVac();

  /* ─────────────────────────────────────────
     SKILLS TAGS
  ───────────────────────────────────────── */
  var tagsWrap    = document.getElementById('tagsWrap');
  var skillInput  = document.getElementById('skillTagInput');
  var skillHidden = document.getElementById('skillsHidden');
  var skills      = [];

  tagsWrap.addEventListener('click', function () { skillInput.focus(); });

  (function hydrate() {
    var raw = skillHidden.value.trim();
    if (!raw) return;
    raw.split(',').map(function (s) { return s.trim(); }).filter(Boolean).forEach(function (s) { addTag(s); });
  }());

  function addTag(val) {
    val = val.trim();
    if (!val || skills.indexOf(val) !== -1) return;
    skills.push(val);
    var span = document.createElement('span');
    span.className = 'tag-item';
    span.innerHTML = val + '<button type="button" class="tag-remove">✕</button>';
    span.querySelector('.tag-remove').addEventListener('click', function () {
      skills.splice(skills.indexOf(val), 1); span.remove(); syncSkills();
    });
    tagsWrap.insertBefore(span, skillInput);
    syncSkills();
  }
  function syncSkills() { skillHidden.value = skills.join(','); }

  skillInput.addEventListener('keydown', function (e) {
    if (e.key === 'Enter' || e.key === ',') {
      e.preventDefault();
      var v = this.value.replace(/,/g,'').trim();
      if (v) { addTag(v); this.value = ''; }
    }
    if (e.key === 'Backspace' && !this.value && skills.length) {
      var items = tagsWrap.querySelectorAll('.tag-item');
      if (items.length) { skills.pop(); items[items.length - 1].remove(); syncSkills(); }
    }
  });
  skillInput.addEventListener('blur', function () {
    var v = this.value.replace(/,/g,'').trim();
    if (v) { addTag(v); this.value = ''; }
  });

  /* ─────────────────────────────────────────
     LIVE PREVIEW
  ───────────────────────────────────────── */
  var typeInp     = document.getElementById('type');
  var deptInp     = document.getElementById('department');
  var locInp      = document.getElementById('location');
  var salInp      = document.getElementById('salary');
  var descInp     = document.getElementById('description');
  var dlInp       = document.getElementById('application_deadline');

  var statusColors = { draft:'#6b7280', active:'#05c48a', closed:'#f04444' };
  var statusLabels = { draft:'Draft', active:'Active', closed:'Closed' };

  function fmtDate(v) {
    if (!v) return '';
    var d = new Date(v + 'T00:00:00');
    return d.toLocaleDateString('en-IN', { day:'numeric', month:'short', year:'numeric' });
  }

  function chip(elId, val, transform) {
    var el = document.getElementById(elId);
    var valEl = document.getElementById(elId + 'Val');
    if (val) { if (valEl) valEl.textContent = transform ? transform(val) : val; el.style.display = 'inline-flex'; }
    else      { el.style.display = 'none'; }
  }

  function updatePreview() {
    var t = titleInp.value.trim();
    var prev = document.getElementById('prevTitle');
    prev.textContent = t || 'Job title will appear here';
    prev.style.color = t ? '' : 'var(--text3)';

    chip('prevType', typeInp.value);
    chip('prevDept', deptInp.value.trim());
    chip('prevLoc',  locInp.value.trim());
    chip('prevSal',  salInp.value.trim());
    var v = parseInt(vacInp.value, 10);
    chip('prevVac', (!isNaN(v) && v > 0) ? v + ' ' + (v === 1 ? 'vacancy' : 'vacancies') : '', null);

    document.getElementById('prevRemote').style.display   = remoteChk.checked ? 'inline-flex' : 'none';
    document.getElementById('prevFeatured').style.display = featChk.checked   ? 'inline-flex' : 'none';
    chip('prevDeadline', dlInp.value, function (v) { return 'Deadline: ' + fmtDate(v); });

    var d = descInp.value.trim();
    var prevDesc = document.getElementById('prevDesc');
    prevDesc.textContent = d ? (d.length > 160 ? d.slice(0,160) + '…' : d) : 'Description preview will appear here…';
    prevDesc.style.color = d ? 'var(--text2)' : '';

    var sv = (document.querySelector('input[name="status"]:checked') || {}).value || 'draft';
    document.getElementById('prevDot').style.background  = statusColors[sv];
    document.getElementById('prevStatus').textContent    = statusLabels[sv];
    document.getElementById('prevStatus').style.color    = statusColors[sv];
  }

  [titleInp, deptInp, locInp, salInp, vacInp, descInp].forEach(function (el) { el.addEventListener('input', updatePreview); });
  [typeInp, remoteChk, featChk, dlInp].forEach(function (el) { el.addEventListener('change', updatePreview); });
  document.querySelectorAll('input[name="status"]').forEach(function (r) { r.addEventListener('change', updatePreview); });
  updatePreview();

  /* ─────────────────────────────────────────
     CHARACTER COUNTERS (shared helper)
  ───────────────────────────────────────── */
  function attachCounter(inputId, counterId, max) {
    var inp = document.getElementById(inputId);
    var cnt = document.getElementById(counterId);
    if (!inp || !cnt) return;
    function update() {
      var len = inp.value.length;
      cnt.textContent = max ? len + ' / ' + max : len + ' chars';
      cnt.className   = 'counter' + (max && len > max * 0.9 ? (len >= max ? ' over' : ' warn') : '');
    }
    inp.addEventListener('input', update); update();
  }
  attachCounter('title',            'titleCounter',    150);
  attachCounter('description',      'descCounter',     null);
  attachCounter('meta_title',       'metaTitleCounter', 70);
  attachCounter('meta_description', 'metaDescCounter', 160);

  /* ─────────────────────────────────────────
     FORM SUBMIT
  ───────────────────────────────────────── */
  var jobForm    = document.getElementById('jobForm');
  var publishBtn = document.getElementById('publishBtn');
  var draftBtn   = document.getElementById('draftBtn');

  jobForm.addEventListener('submit', function (e) {
    var action = (document.activeElement && document.activeElement.name === '_action')
      ? document.activeElement.value : 'publish';

    if (action === 'publish') {
      var ar = document.querySelector('input[name="status"][value="active"]');
      if (ar) ar.checked = true;
    }

    /* ensure slug */
    if (!slugInp.value.trim() && titleInp.value.trim())
      slugInp.value = toSlug(titleInp.value);

    /* flush tag input */
    var raw = skillInput.value.replace(/,/g,'').trim();
    if (raw) { addTag(raw); skillInput.value = ''; }

    /* validation */
    var valid = true;
    [[titleInp, true], [slugInp, true], [typeInp, true], [descInp, true]].forEach(function (pair) {
      var el = pair[0], req = pair[1];
      if (req && !el.value.trim()) { el.classList.add('err'); valid = false; }
      else el.classList.remove('err');
    });

    if (!valid) { e.preventDefault(); toast('Please fill in all required fields.', 'error', { duration: 4200 }); return; }

    publishBtn.disabled = draftBtn.disabled = true;
    publishBtn.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="animation:spin .7s linear infinite"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg> Publishing…';
  });

}());