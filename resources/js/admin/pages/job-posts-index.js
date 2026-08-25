/* ═══════════════════════════════════════════════════════════════════
   Admin Job Posts Index page — extracted from inline <script>.
   Client-side filter, sort, search + delete confirmation modal.
   Flash toast handled by admin.js (shared layout).
   ═══════════════════════════════════════════════════════════════════ */

(function () {
  'use strict';

  var rows = Array.from(document.querySelectorAll('#tableBody tr[data-filter]'));
  var activeFilter = 'all';
  var searchQ = '';
  var sortVal = '';
  var deleteOverlay = document.getElementById('deleteOverlay');

  function applyFilters() {
    var sorted = rows.slice();
    var fn = {
      'date-desc': function (a, b) { return new Date(b.dataset.date) - new Date(a.dataset.date); },
      'date-asc': function (a, b) { return new Date(a.dataset.date) - new Date(b.dataset.date); },
      'az': function (a, b) { return (a.dataset.title || '').localeCompare(b.dataset.title || ''); },
      'za': function (a, b) { return (b.dataset.title || '').localeCompare(a.dataset.title || ''); },
      'views-desc': function (a, b) { return +b.dataset.views - +a.dataset.views; },
      'apps-desc': function (a, b) { return +b.dataset.apps - +a.dataset.apps; },
      'vac-desc': function (a, b) { return +b.dataset.vac - +a.dataset.vac; },
    };
    if (fn[sortVal]) sorted.sort(fn[sortVal]);
    var tb = document.getElementById('tableBody');
    if (tb) sorted.forEach(function (r) { tb.appendChild(r); });

    var visible = 0;
    rows.forEach(function (r) {
      var mf;
      if (activeFilter === 'all') mf = true;
      else if (activeFilter === 'remote') mf = r.dataset.remote === 'remote';
      else if (activeFilter === 'featured') mf = r.dataset.featured === 'featured';
      else mf = r.dataset.filter === activeFilter;

      var ms = !searchQ || (r.dataset.title || '').includes(searchQ);
      r.style.display = (mf && ms) ? '' : 'none';
      if (mf && ms) visible++;
    });
    var nr = document.getElementById('noResults');
    if (nr) nr.style.display = visible > 0 ? 'none' : 'block';
  }

  document.querySelectorAll('.ftab').forEach(function (tab) {
    tab.addEventListener('click', function () {
      document.querySelectorAll('.ftab').forEach(function (t) { t.classList.remove('on'); });
      this.classList.add('on');
      activeFilter = this.dataset.filter;
      applyFilters();
    });
  });

  function setFilter(f) {
    activeFilter = f;
    document.querySelectorAll('.ftab').forEach(function (t) {
      t.classList.toggle('on', t.dataset.filter === f);
    });
    applyFilters();
  }

  var si = document.getElementById('searchInput');
  if (si) {
    var st;
    si.addEventListener('input', function () {
      clearTimeout(st);
      searchQ = this.value.toLowerCase().trim();
      st = setTimeout(applyFilters, 180);
    });
  }

  var ss = document.getElementById('sortSelect');
  if (ss) ss.addEventListener('change', function () {
    sortVal = this.value;
    applyFilters();
  });

  /* ── Delete modal ── */
  function closeDelete() {
    if (deleteOverlay) deleteOverlay.classList.remove('open');
  }
  if (deleteOverlay) {
    deleteOverlay.addEventListener('click', function (e) { if (e.target === this) closeDelete(); });
  }
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && deleteOverlay && deleteOverlay.classList.contains('open')) closeDelete();
  });

  /* ── data-action delegation ── */
  document.addEventListener('click', function (e) {
    var el = e.target.closest('[data-action]');
    if (!el) return;
    var action = el.getAttribute('data-action');

    if (action === 'set-filter') {
      setFilter(el.getAttribute('data-filter'));
    } else if (action === 'open-delete-modal') {
      var id = el.getAttribute('data-id');
      var title = el.getAttribute('data-title') || '';
      var df = document.getElementById('deleteForm');
      if (df) df.action = '/admin/job_posts/' + id;
      var dj = document.getElementById('deleteJobTitle');
      if (dj) dj.textContent = '"' + title + '"';
      if (deleteOverlay) deleteOverlay.classList.add('open');
    }
  });

  applyFilters();
})();
