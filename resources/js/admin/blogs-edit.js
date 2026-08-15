/* ═══════════════════════════════════════════════════════════════════
   Admin Blogs Edit page — moved from admin/blogs/edit.blade.php
   inline <script>. window.* bridges converted to internal functions
   with data-action delegation; inline onclick/onchange handlers moved
   to data-action/direct listeners. Destroy-form onsubmit confirm left
   in blade (out of scope).
   ═══════════════════════════════════════════════════════════════════ */

(function () {
'use strict';

/* ── RICH TEXT EDITOR ── */
var editor       = document.getElementById('editor');
var contentInput = document.getElementById('contentInput');

document.querySelectorAll('.editor-btn[data-cmd]').forEach(function (btn) {
  btn.addEventListener('click', function () {
    var cmd = this.dataset.cmd;
    if      (cmd === 'h2')         { document.execCommand('formatBlock', false, 'h2'); }
    else if (cmd === 'h3')         { document.execCommand('formatBlock', false, 'h3'); }
    else if (cmd === 'blockquote') { document.execCommand('formatBlock', false, 'blockquote'); }
    else if (cmd === 'createLink') {
      var url = prompt('Enter URL:');
      if (url) document.execCommand('createLink', false, url);
    } else {
      document.execCommand(cmd, false, null);
    }
    editor.focus();
    updateCounts();
  });
});

function updateCounts() {
  var text  = editor.innerText || '';
  var words = text.trim() ? text.trim().split(/\s+/).length : 0;
  document.getElementById('wordCount').textContent = words + ' word' + (words !== 1 ? 's' : '');
  document.getElementById('charCount').textContent = text.length + ' character' + (text.length !== 1 ? 's' : '');
  contentInput.value = editor.innerHTML;
  updateSEO();
}
editor.addEventListener('input', updateCounts);
updateCounts();

/* ── TITLE COUNTER + AUTO-SLUG ── */
var titleInput = document.getElementById('title');
var slugInput  = document.getElementById('slug');
if (slugInput.value.trim()) { slugInput.dataset.manual = '1'; }

function updateTitleCounter() {
  var len = titleInput.value.length;
  document.getElementById('titleCounter').textContent = len + ' / 100';
  if (!slugInput.dataset.manual) {
    slugInput.value = titleInput.value.toLowerCase()
      .replace(/[^a-z0-9\s-]/g, '').replace(/\s+/g, '-')
      .replace(/-+/g, '-').replace(/^-|-$/g, '');
  }
  updateSEO();
}
titleInput.addEventListener('input', updateTitleCounter);
slugInput.addEventListener('input', function () { this.dataset.manual = '1'; });
updateTitleCounter();

/* ── EXCERPT COUNTER ── */
var excerptEl = document.getElementById('excerpt');
function updateExcerptCounter() {
  document.getElementById('excerptCounter').textContent = excerptEl.value.length + ' / 200';
  updateSEO();
}
excerptEl.addEventListener('input', updateExcerptCounter);
updateExcerptCounter();

/* ── META COUNTERS ── */
[['meta_title','metaTitleCounter',65],['meta_description','metaDescCounter',160]].forEach(function (cfg) {
  var el = document.getElementById(cfg[0]);
  var counter = document.getElementById(cfg[1]);
  var max = cfg[2];
  function upd() {
    var l = el.value.length;
    counter.textContent = l + ' / ' + max;
    counter.style.color = l > max * 0.9 ? 'var(--red)' : 'var(--text3)';
  }
  el.addEventListener('input', upd); upd();
});

/* ── SEO CHECKER ── */
function updateSEO() {
  var titleLen   = (document.getElementById('title').value || '').length;
  var excerptLen = (document.getElementById('excerpt').value || '').length;
  var slugVal    = (document.getElementById('slug').value || '').trim();
  var wordCount  = (editor.innerText || '').trim().split(/\s+/).filter(Boolean).length;
  var imgSrc     = document.getElementById('coverPreview').src;
  var hasImage   = imgSrc && imgSrc !== '' && imgSrc !== window.location.href;

  var checks = [
    { id:'chkTitle',   pass: titleLen >= 40 && titleLen <= 65,   label:'Title is 40–65 characters' },
    { id:'chkExcerpt', pass: excerptLen > 0 && excerptLen <= 160, label:'Excerpt is under 160 characters' },
    { id:'chkSlug',    pass: slugVal.length > 0,                  label:'URL slug is set' },
    { id:'chkContent', pass: wordCount >= 300,                    label:'Content is at least 300 words' },
    { id:'chkImage',   pass: !!hasImage,                          label:'Cover image uploaded' },
  ];

  var score = 0;
  checks.forEach(function (c) {
    var el = document.getElementById(c.id);
    if (!el) return;
    if (c.pass) {
      el.className = 'seo-check pass';
      el.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> ' + c.label;
      score++;
    } else {
      el.className = 'seo-check fail';
      el.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="8" y1="12" x2="16" y2="12"/></svg> ' + c.label;
    }
  });

  var pct  = Math.round((score / checks.length) * 100);
  var fill = document.getElementById('seoFill');
  fill.style.width      = pct + '%';
  fill.style.background = pct >= 80 ? 'linear-gradient(90deg,#059669,#05c48a)' :
                          pct >= 50 ? 'linear-gradient(90deg,var(--a),var(--a2))' :
                                      'linear-gradient(90deg,#dc2626,#f04444)';
  document.getElementById('seoScoreVal').textContent = pct + '%';
}
updateSEO();

/* ── COVER IMAGE PREVIEW ── */
function previewCover(input) {
  if (!input.files || !input.files[0]) return;
  var reader = new FileReader();
  reader.onload = function (e) {
    document.getElementById('coverPreview').src                = e.target.result;
    document.getElementById('coverPreviewWrap').style.display  = '';
    document.getElementById('coverDropzone').style.display     = 'none';
    document.getElementById('removeCoverFlag').value           = '0';
    updateSEO();
  };
  reader.readAsDataURL(input.files[0]);
}

function removeCover() {
  document.getElementById('coverPreview').src                = '';
  document.getElementById('coverPreviewWrap').style.display  = 'none';
  document.getElementById('coverDropzone').style.display     = '';
  document.getElementById('coverInput').value                = '';
  document.getElementById('removeCoverFlag').value           = '1';
  updateSEO();
}

var coverInput = document.getElementById('coverInput');
coverInput.addEventListener('change', function () { previewCover(this); });
document.querySelectorAll('.cpb-change').forEach(function (btn) {
  btn.addEventListener('click', function () { coverInput.click(); });
});

var dz = document.getElementById('coverDropzone');
if (dz) {
  dz.addEventListener('dragover',  function (e) { e.preventDefault(); this.classList.add('drag-over'); });
  dz.addEventListener('dragleave', function ()  { this.classList.remove('drag-over'); });
  dz.addEventListener('drop', function (e) {
    e.preventDefault(); this.classList.remove('drag-over');
    if (e.dataTransfer.files[0]) {
      coverInput.files = e.dataTransfer.files;
      previewCover(coverInput);
    }
  });
}

/* ── TAGS CHIP INPUT ── */
function syncTags() {
  var chips = document.querySelectorAll('#tagsWrap .tag-chip');
  var vals  = Array.from(chips).map(function (c) { return c.dataset.tag; });
  document.getElementById('tagsHidden').value = vals.join(',');
}
function removeTag(chip) { chip.remove(); syncTags(); }

var tagsWrap = document.getElementById('tagsWrap');
var tagInput = document.getElementById('tagInput');
tagsWrap.addEventListener('click', function () { tagInput.focus(); });
tagInput.addEventListener('keydown', function (e) {
  if (e.key === 'Enter' || e.key === ',') {
    e.preventDefault();
    var val = this.value.replace(/,/g, '').trim().toLowerCase();
    if (!val) return;
    var exists = Array.from(document.querySelectorAll('#tagsWrap .tag-chip'))
                      .some(function (c) { return c.dataset.tag === val; });
    if (!exists) {
      var chip         = document.createElement('span');
      chip.className   = 'tag-chip';
      chip.dataset.tag = val;
      chip.innerHTML   = val + '<button type="button" data-action="remove-tag">×</button>';
      document.getElementById('tagsWrap').insertBefore(chip, tagInput);
      syncTags();
    }
    this.value = '';
  }
  if (e.key === 'Backspace' && !this.value) {
    var chips = document.querySelectorAll('#tagsWrap .tag-chip');
    if (chips.length) { chips[chips.length - 1].remove(); syncTags(); }
  }
});

/* ── STATUS RADIO HIGHLIGHT ── */
document.querySelectorAll('.status-option input[type="radio"]').forEach(function (radio) {
  radio.addEventListener('change', function () {
    document.querySelectorAll('.status-option').forEach(function (o) { o.classList.remove('selected'); });
    this.closest('.status-option').classList.add('selected');
  });
});

/* ── TOGGLE SWITCHES ── */
function toggleSwitch(inputId, trackId, thumbId) {
  var cb     = document.getElementById(inputId);
  cb.checked = !cb.checked;
  var track  = document.getElementById(trackId);
  var thumb  = document.getElementById(thumbId);
  track.style.background = cb.checked ? 'var(--a)' : 'var(--border2)';
  thumb.style.left        = cb.checked ? '20px' : '2px';
}

/* ── SYNC CONTENT ON SUBMIT ── */
document.getElementById('editForm').addEventListener('submit', function () {
  contentInput.value = editor.innerHTML;
});

/* ── delegated actions ── */
document.addEventListener('click', function (e) {
  var el = e.target.closest('[data-action]');
  if (!el) return;
  var action = el.getAttribute('data-action');

  if (action === 'remove-cover') { removeCover(); }
  else if (action === 'remove-tag') {
    var chip = el.closest('.tag-chip');
    if (chip) removeTag(chip);
  }
  else if (action === 'toggle-switch') {
    toggleSwitch(el.getAttribute('data-input'), el.getAttribute('data-track'), el.getAttribute('data-thumb'));
  }
});

})();