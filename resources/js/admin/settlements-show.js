/* ═══════════════════════════════════════════════════════════════════
   Admin Settlements Show page — extracted from inline <script>.
   Approve/reject modal open/close + chip reason selection.
   ═══════════════════════════════════════════════════════════════════ */

(function () {
  'use strict';

  var approveOverlay = document.getElementById('approveOverlay');
  var rejectOverlay = document.getElementById('rejectOverlay');

  /* ── Overlay click-to-close ── */
  if (approveOverlay) approveOverlay.addEventListener('click', function (e) { if (e.target === this) approveOverlay.classList.remove('open'); });
  if (rejectOverlay) rejectOverlay.addEventListener('click', function (e) { if (e.target === this) rejectOverlay.classList.remove('open'); });
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
      if (approveOverlay && approveOverlay.classList.contains('open')) approveOverlay.classList.remove('open');
      if (rejectOverlay && rejectOverlay.classList.contains('open')) rejectOverlay.classList.remove('open');
    }
  });

  /* ── data-action delegation ── */
  document.addEventListener('click', function (e) {
    var el = e.target.closest('[data-action]');
    if (!el) return;
    var action = el.getAttribute('data-action');

    if (action === 'open-modal') {
      var target = el.getAttribute('data-target');
      if (target) {
        var m = document.querySelector(target);
        if (m) m.classList.add('open');
      }
    } else if (action === 'set-reason') {
      var text = el.textContent;
      var ta = document.getElementById('rejectReason');
      if (ta) {
        ta.value = text;
        ta.dispatchEvent(new Event('input'));
        ta.focus();
      }
    }
  });

  /* ── Enable/disable reject button based on reason input ── */
  var rejectReason = document.getElementById('rejectReason');
  var rejectBtn = document.getElementById('rejectBtn');
  if (rejectReason && rejectBtn) {
    rejectReason.addEventListener('input', function () {
      rejectBtn.disabled = this.value.trim() === '';
    });
  }
})();
