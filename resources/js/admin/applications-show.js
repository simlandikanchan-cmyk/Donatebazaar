/* ═══════════════════════════════════════════════════════════════════
   Admin Applications Show page — extracted from inline <script>.
   Reject modal + admin notes save. Flash toast by admin.js layout.
   ═══════════════════════════════════════════════════════════════════ */

(function () {
  'use strict';

  /* ── Reject modal ── */
  var rejectOverlay = document.getElementById('rejectOverlay');
  if (rejectOverlay) {
    rejectOverlay.addEventListener('click', function (e) {
      if (e.target === this) rejectOverlay.classList.remove('open');
    });
  }
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && rejectOverlay && rejectOverlay.classList.contains('open')) {
      rejectOverlay.classList.remove('open');
    }
  });

  document.querySelectorAll('.chip-red').forEach(function (btn) {
    btn.addEventListener('click', function () {
      document.querySelectorAll('.chip-red').forEach(function (b) { b.classList.remove('on'); });
      this.classList.add('on');
      var r = document.getElementById('rejectReason');
      if (r) r.value = this.dataset.r;
      var err = document.getElementById('rejectErr');
      if (err) err.style.display = 'none';
    });
  });

  var rejectForm = document.getElementById('rejectForm');
  if (rejectForm) {
    rejectForm.addEventListener('submit', function (e) {
      var reason = document.getElementById('rejectReason');
      if (reason && !reason.value.trim()) {
        e.preventDefault();
        var err = document.getElementById('rejectErr');
        if (err) err.style.display = 'block';
        return;
      }
      var btn = document.getElementById('rejectBtn');
      if (btn) { btn.disabled = true; btn.innerHTML = 'Rejecting…'; }
    });
  }

  /* ── Admin Notes Save ── */
  var adminNotesSave = document.getElementById('adminNotesSaveBtn');
  if (adminNotesSave) {
    adminNotesSave.addEventListener('click', function () {
      var notes = document.getElementById('adminNotesTextarea');
      var input = document.getElementById('adminNotesInput');
      var form = document.getElementById('adminNotesForm');
      if (notes && input && form) {
        input.value = notes.value.trim();
        form.submit();
      }
    });
  }

  /* ── data-action delegation ── */
  document.addEventListener('click', function (e) {
    var el = e.target.closest('[data-action]');
    if (!el) return;
    var action = el.getAttribute('data-action');

    if (action === 'open-reject-modal') {
      var id = el.getAttribute('data-id');
      var rf = document.getElementById('rejectForm');
      if (rf) rf.action = '/admin/applications/' + id + '/reject';
      var rr = document.getElementById('rejectReason');
      if (rr) rr.value = '';
      var err = document.getElementById('rejectErr');
      if (err) err.style.display = 'none';
      var btn = document.getElementById('rejectBtn');
      if (btn) { btn.disabled = false; btn.innerHTML = '✕ Reject Application'; }
      document.querySelectorAll('.chip-red').forEach(function (c) { c.classList.remove('on'); });
      if (rejectOverlay) rejectOverlay.classList.add('open');
      setTimeout(function () { var r = document.getElementById('rejectReason'); if (r) r.focus(); }, 80);
    }
  });
})();
