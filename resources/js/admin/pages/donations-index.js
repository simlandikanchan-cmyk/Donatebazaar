/* ═══════════════════════════════════════════════════════════════════
   Admin Donations Index page — extracted from inline <script>.
   Row filter + client-side search + refund modal delegation.
   Flash toast handled by admin.js (shared layout).
   ═══════════════════════════════════════════════════════════════════ */

(function () {
  'use strict';

  var rows = Array.from(document.querySelectorAll('#tableBody tr[data-filter]'));
  var activeFilter = 'all';
  var refundOverlay = document.getElementById('refundOverlay');

  function applyFilters() {
    var visible = 0;
    rows.forEach(function (r) {
      var mf = (activeFilter === 'all') || (r.dataset.filter === activeFilter);
      var campaign = document.getElementById('campaignSelect').value;
      var mc = !campaign || (r.dataset.campaign === campaign);
      var q = (document.getElementById('searchInput').value || '').toLowerCase().trim();
      var ms = !q || (r.dataset.donor || '').includes(q);
      r.style.display = (mf && mc && ms) ? '' : 'none';
      if (mf && mc && ms) visible++;
    });
    var nr = document.getElementById('noResults');
    if (nr) nr.style.display = visible > 0 ? 'none' : 'block';
  }

  function setFilter(f) {
    activeFilter = f;
    document.querySelectorAll('.ftab').forEach(function (t) {
      t.classList.toggle('on', t.dataset.filter === f);
    });
    applyFilters();
  }

  document.querySelectorAll('.ftab').forEach(function (tab) {
    tab.addEventListener('click', function () {
      document.querySelectorAll('.ftab').forEach(function (t) { t.classList.remove('on'); });
      this.classList.add('on');
      activeFilter = this.dataset.filter;
      applyFilters();
    });
  });

  var cs = document.getElementById('campaignSelect');
  if (cs) cs.addEventListener('change', applyFilters);
  var si = document.getElementById('searchInput');
  if (si) {
    var st;
    si.addEventListener('input', function () {
      clearTimeout(st);
      st = setTimeout(applyFilters, 180);
    });
  }

  /* ── Refund modal close handlers ── */
  if (refundOverlay) {
    refundOverlay.addEventListener('click', function (e) {
      if (e.target === this) refundOverlay.classList.remove('open');
    });
  }
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && refundOverlay && refundOverlay.classList.contains('open')) {
      refundOverlay.classList.remove('open');
    }
  });

  /* ── data-action delegation ── */
  document.addEventListener('click', function (e) {
    var el = e.target.closest('[data-action]');
    if (!el) return;
    var action = el.getAttribute('data-action');

    if (action === 'set-filter') {
      setFilter(el.getAttribute('data-filter'));
    } else if (action === 'open-refund') {
      var id = el.getAttribute('data-id');
      var donor = el.getAttribute('data-donor') || 'this donation';
      var amount = el.getAttribute('data-amount') || '0';
      var rf = document.getElementById('refundForm');
      if (rf) rf.action = '/admin/donations/' + id + '/refund';
      var rd = document.getElementById('refundDonor');
      if (rd) rd.textContent = '"' + donor + '"';
      var ra = document.getElementById('refundAmount');
      if (ra) ra.textContent = '₹' + Number(amount).toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
      var rr = document.getElementById('refundReason');
      if (rr) rr.value = '';
      if (refundOverlay) refundOverlay.classList.add('open');
    }
  });
})();
