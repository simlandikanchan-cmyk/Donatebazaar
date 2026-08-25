/* ═══════════════════════════════════════════════════════════════════
   Admin Blogs Show page — moved from admin/blogs/show.blade.php
   inline <script>. Top-level page-global functions
   openRejectModal/closeRejectModal/submitReject converted to internal
   functions with data-action delegation; modal-backdrop inline
   onclick converted to a direct listener. Archive-form onsubmit
   confirm left in blade (out of scope).
   ═══════════════════════════════════════════════════════════════════ */

(function () {
  'use strict';

  var html   = document.documentElement;
  var toggle = document.getElementById('themeToggle');

  window.addEventListener('load', function () {
    setTimeout(function () {
      document.querySelectorAll('.eng-bar-fill[data-width]').forEach(function (el) {
        el.style.width = el.dataset.width;
      });
    }, 300);
  });

  function openRejectModal() {
    document.getElementById('rejectModal').classList.add('open');
    setTimeout(function () { document.getElementById('reject_reason').focus(); }, 180);
  }

  function closeRejectModal() {
    document.getElementById('rejectModal').classList.remove('open');
    document.getElementById('reject_reason').value = '';
    document.getElementById('reject-error').style.display = 'none';
  }

  function submitReject() {
    var reason = document.getElementById('reject_reason').value.trim();
    var errEl  = document.getElementById('reject-error');
    if (!reason) {
      errEl.style.display = 'block';
      document.getElementById('reject_reason').focus();
      return;
    }
    errEl.style.display = 'none';
    document.getElementById('rejectForm').submit();
  }

  document.getElementById('rejectModal').addEventListener('click', function (e) {
    if (e.target === this) closeRejectModal();
  });

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closeRejectModal();
  });

  /* ── delegated actions ── */
  document.addEventListener('click', function (e) {
    var el = e.target.closest('[data-action]');
    if (!el) return;
    var action = el.getAttribute('data-action');

    if (action === 'open-reject-modal') { openRejectModal(); }
    else if (action === 'close-reject-modal') { closeRejectModal(); }
    else if (action === 'submit-reject') { submitReject(); }
  });

})();