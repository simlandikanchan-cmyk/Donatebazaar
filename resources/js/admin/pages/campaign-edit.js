/* ═══════════════════════════════════════════════════════════════════
   Admin Campaign Edit page — extracted from inline <script>.
   Reads server data from #campaignEditData (JSON) injected by the blade.
   ═══════════════════════════════════════════════════════════════════ */

(function(){
  'use strict';

  var pageData = JSON.parse(document.getElementById('campaignEditData').textContent);

  /* ── Char counters ── */
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

  /* ── Cover image preview ── */
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

  /* ── Unsaved changes tracker ── */
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

  /* ── Discard modal ── */
  document.getElementById('discardBtn').addEventListener('click', function(){
    if (isDirty) {
      document.getElementById('discardModal').classList.add('show');
    } else {
      window.location.href = pageData.showUrl;
    }
  });

  /* Intercept back button when dirty */
  document.getElementById('backBtn').addEventListener('click', function(e){
    if (isDirty) {
      e.preventDefault();
      document.getElementById('discardModal').classList.add('show');
    }
  });

  function closeDiscardModal() {
    document.getElementById('discardModal').classList.remove('show');
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

  /* ── Save button loading state ── */
  document.getElementById('editForm').addEventListener('submit', function(){
    var btn = document.getElementById('saveBtn');
    btn.disabled = true;
    btn.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:13px;height:13px;animation:spin .7s linear infinite;"><path stroke-linecap="round" stroke-linejoin="round" d="M4 12a8 8 0 018-8v4l3-3-3-3v4A10 10 0 002 12h2z"/></svg> Saving…';
    btn.style.opacity = '.75';
  });

  /* flash toasts from session (server values via #campaignEditData) */
  if(pageData.success) window.addEventListener('DOMContentLoaded', function(){ showToast(pageData.success, 'success'); });
  if(pageData.error) window.addEventListener('DOMContentLoaded', function(){ showToast(pageData.error, 'error'); });

  /* ── Spin keyframe for save button ── */
  var style = document.createElement('style');
  style.textContent = '@keyframes spin{to{transform:rotate(360deg);}}';
  document.head.appendChild(style);

  /* ── delegated handlers for data-action attributes ── */

  /* modal "Keep Editing" (was onclick="closeDiscardModal()") */
  document.addEventListener('click', function(e){
    var el = e.target.closest('[data-action="close-discard"]');
    if(!el) return;
    closeDiscardModal();
  });

  /* cover "Remove" (was onclick="removeCover()") */
  document.addEventListener('click', function(e){
    var el = e.target.closest('[data-action="remove-cover"]');
    if(!el) return;
    removeCover();
  });
})();