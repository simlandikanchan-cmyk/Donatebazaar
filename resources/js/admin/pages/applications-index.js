/* ═══════════════════════════════════════════════════════════════════
   Admin Applications Index page — extracted from inline <script>.
   Client-side filter + search + reject modal delegation.
   ═══════════════════════════════════════════════════════════════════ */

(function () {
  'use strict';

  var rows = Array.from(document.querySelectorAll('#tbody tr[data-id]'));
  var emptyState = document.querySelector('#tbody .empty-state');
  var activeFilter = 'all';

  function applyFilter() {
    var q = (document.getElementById('searchInput').value || '').toLowerCase().trim();
    var vis = 0;
    rows.forEach(function (r) {
      var mF = activeFilter === 'all' || r.dataset.status === activeFilter;
      var mS = !q || (r.dataset.search || '').includes(q);
      r.style.display = (mF && mS) ? '' : 'none';
      if (mF && mS) vis++;
    });
    var empty = document.querySelector('#tbody tr.empty-filter');
    if (vis === 0 && rows.length > 0) {
      if (!empty) {
        empty = document.createElement('tr');
        empty.className = 'empty-filter';
        empty.innerHTML = '<td colspan="8"><div class="empty-state"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg><strong>No results found</strong><p>Try adjusting your filters or search query.</p></div></td>';
        document.getElementById('tbody').appendChild(empty);
      }
      empty.style.display = '';
    } else if (empty) {
      empty.style.display = 'none';
    }
  }

  document.querySelectorAll('.ftab').forEach(function (tab) {
    tab.addEventListener('click', function () {
      document.querySelectorAll('.ftab').forEach(function (t) { t.classList.remove('on'); });
      this.classList.add('on');
      activeFilter = this.dataset.filter;
      applyFilter();
    });
  });

  var si = document.getElementById('searchInput');
  if (si) {
    var st;
    si.addEventListener('input', function () {
      clearTimeout(st);
      st = setTimeout(applyFilter, 180);
    });
  }

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

  applyFilter();
})();
