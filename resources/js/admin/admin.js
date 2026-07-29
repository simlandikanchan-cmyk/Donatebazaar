/* ══════════════════════════════════════
    ADMIN PANEL — Shared JavaScript
    ══════════════════════════════════════ */

import Chart from 'chart.js/auto';
window.Chart = Chart;

(function () {
  'use strict';

  const html = document.documentElement;
  const toggle = document.getElementById('themeToggle');
  const sidebar = document.getElementById('sidebar');
  const overlay = document.getElementById('sidebarOverlay');
  const hamburger = document.getElementById('hamburger');
  const avWrap = document.getElementById('avWrap');
  const toastWrap = document.getElementById('toastWrap');

  /* ── Theme ── */
  const saved = localStorage.getItem('adminTheme') || 'light';
  if (saved === 'dark') {
    html.setAttribute('data-theme', 'dark');
    if (toggle) toggle.checked = true;
  }
  if (toggle) {
    toggle.addEventListener('change', function () {
      const t = this.checked ? 'dark' : 'light';
      html.setAttribute('data-theme', t);
      localStorage.setItem('adminTheme', t);
      window.dispatchEvent(new Event('themechange'));
    });
  }

  /* ── Sidebar ── */
  if (hamburger && sidebar) {
    function setBodyScroll(locked) {
      document.body.style.overflow = locked ? 'hidden' : '';
    }
    hamburger.addEventListener('click', function () {
      sidebar.classList.toggle('open');
      if (overlay) overlay.classList.toggle('open');
      setBodyScroll(sidebar.classList.contains('open'));
    });
    document.addEventListener('click', function (e) {
      if (window.innerWidth <= 960 && sidebar.classList.contains('open') && !sidebar.contains(e.target) && !hamburger.contains(e.target)) {
        sidebar.classList.remove('open');
        if (overlay) overlay.classList.remove('open');
        setBodyScroll(false);
      }
    });
    if (overlay) {
      overlay.addEventListener('click', function () {
        sidebar.classList.remove('open');
        overlay.classList.remove('open');
        setBodyScroll(false);
      });
    }
    sidebar.querySelectorAll('.s-link').forEach(function (link) {
      link.addEventListener('click', function () {
        if (window.innerWidth <= 960) {
          sidebar.classList.remove('open');
          if (overlay) overlay.classList.remove('open');
          setBodyScroll(false);
        }
      });
    });
  }

  /* ── Avatar Dropdown ── */
  if (avWrap) {
    const dd = avWrap.querySelector('.av-dd');
    const avBtn = avWrap.querySelector('.t-av');
    if (avBtn && dd) {
      window.toggleDD = function () {
        dd.classList.toggle('open');
      };
      avBtn.addEventListener('click', function (e) {
        e.stopPropagation();
        dd.classList.toggle('open');
      });
      document.addEventListener('click', function (e) {
        if (!avWrap.contains(e.target)) dd.classList.remove('open');
      });
    }
  }

  /* ── Toast System ── */
  window.toast = function (msg, type) {
    type = type || 'success';
    const icons = {
      success:
        '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
      error:
        '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
      warn: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>',
    };
    const el = document.createElement('div');
    el.className =
      'toast toast-' + (type === 'success' ? 'ok' : type === 'error' ? 'err' : 'warn');
    el.innerHTML =
      (icons[type] || '') +
      '<span>' +
      msg +
      '</span><button class="toast-x" onclick="this.parentElement.remove()">✕</button>';
    if (toastWrap) {
      toastWrap.appendChild(el);
      setTimeout(function () {
        el.style.transition = 'opacity .3s,transform .3s';
        el.style.opacity = '0';
        el.style.transform = 'translateX(20px)';
        setTimeout(function () { el.remove(); }, 300);
      }, 4200);
    }
  };

  /* ── Lazy toast from session flash ── */
  if (toastWrap) {
    const data = toastWrap.dataset;
    if (data.success) setTimeout(function () { window.toast(data.success, 'success'); }, 200);
    if (data.error) setTimeout(function () { window.toast(data.error, 'error'); }, 200);
    if (data.warning) setTimeout(function () { window.toast(data.warning, 'warn'); }, 200);
  }

  /* ── Modal helpers ── */
  function closeAllModals() {
    document.querySelectorAll('.overlay.open').forEach(function (o) {
      o.classList.remove('open');
    });
  }
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closeAllModals();
  });
  document.querySelectorAll('.overlay').forEach(function (o) {
    o.addEventListener('click', function (e) {
      if (e.target === this) this.classList.remove('open');
    });
  });

  /* ══════════════════════════════════════
     DASHBOARD INITIALIZATION
     ══════════════════════════════════════ */

  function animateCounter(el, target) {
    var duration = 900, start = 0, startTime = null;
    function step(timestamp) {
      if (!startTime) startTime = timestamp;
      var progress = Math.min((timestamp - startTime) / duration, 1);
      var current = Math.floor((1 - Math.pow(1 - progress, 3)) * target);
      el.textContent = current.toLocaleString('en-IN');
      if (progress < 1) requestAnimationFrame(step);
    }
    requestAnimationFrame(step);
  }

  function initDashboardCharts(config) {
    var lineChart = null;
    var doughnutChart = null;
    var revenueChart = null;
    var topCampChart = null;

    /* ── Entrance animations ── */
    (function () {
      setTimeout(function () {
        var b = document.getElementById('approvalBar');
        if (b) b.style.width = (config.approvalRate || 0) + '%';
      }, 700);
      var cards = document.querySelectorAll('.stats-grid .stat');
      cards.forEach(function (c, i) {
        c.style.animationDelay = (.08 * i) + 's';
        c.style.opacity = '0';
      });
      setTimeout(function () {
        cards.forEach(function (c, i) {
          requestAnimationFrame(function () {
            c.style.animation = 'fadeUp .5s ease both';
            c.style.animationDelay = (.08 * i) + 's';
          });
        });
      }, 50);
      var dcv = document.getElementById('dcVal');
      if (dcv) {
        var match = dcv.textContent.match(/^(\d+)/);
        if (match && match[1] > 0) {
          var target = parseInt(match[1], 10);
          dcv.textContent = '0%';
          requestAnimationFrame(function step(ts) {
            if (!dcv._st) dcv._st = ts;
            var p = Math.min((ts - dcv._st) / 900, 1);
            dcv.textContent = Math.floor((1 - Math.pow(1 - p, 3)) * target) + '%';
            if (p < 1) requestAnimationFrame(step);
          });
        }
      }
    })();

    function isDark() {
      return html.getAttribute('data-theme') === 'dark';
    }

    /* ── Line Chart ── */
    function loadLineChart() {
      var canvas = document.getElementById('lineChart');
      if (!canvas || typeof Chart === 'undefined') return;
      if (lineChart) { lineChart.destroy(); lineChart = null; }
      var ig = isDark() ? 'rgba(255,255,255,.05)' : 'rgba(0,0,0,.04)';
      var lc = isDark() ? 'rgba(255,255,255,.32)' : 'rgba(0,0,0,.32)';
      var tb = isDark() ? '#1d1f35' : '#fff';
      var tx = isDark() ? '#eef0ff' : '#0a0b14';
      var ctx = canvas.getContext('2d');
      var g1 = ctx.createLinearGradient(0, 0, 0, 190);
      g1.addColorStop(0, 'rgba(13,148,136,.25)');
      g1.addColorStop(1, 'rgba(13,148,136,0)');
      var g2 = ctx.createLinearGradient(0, 0, 0, 190);
      g2.addColorStop(0, 'rgba(244,63,94,.18)');
      g2.addColorStop(1, 'rgba(244,63,94,0)');
      lineChart = new Chart(ctx, {
        type: 'line',
        data: {
          labels: config.chartLabels,
          datasets: [
            { label: 'Total Campaigns', data: config.chartTotal, borderColor: '#0d9488', backgroundColor: g1, borderWidth: 2.5, pointRadius: 4, tension: .45, fill: true, pointBackgroundColor: '#0d9488', pointBorderColor: tb, pointBorderWidth: 2, pointHoverRadius: 6 },
            { label: 'Approved', data: config.chartActive, borderColor: '#f43f5e', backgroundColor: g2, borderWidth: 2.5, pointRadius: 4, tension: .45, fill: true, pointBackgroundColor: '#f43f5e', pointBorderColor: tb, pointBorderWidth: 2, pointHoverRadius: 6 }
          ]
        },
        options: {
          responsive: true, maintainAspectRatio: false,
          interaction: { intersect: false, mode: 'index' },
          plugins: { legend: { display: false }, tooltip: { backgroundColor: tb, titleColor: tx, bodyColor: tx, borderColor: ig, borderWidth: 1, padding: 13, cornerRadius: 11, titleFont: { size: 11, weight: '700' }, bodyFont: { size: 11 } } },
          scales: {
            x: { grid: { color: ig }, border: { dash: [3, 3], display: false }, ticks: { color: lc } },
            y: { grid: { color: ig }, border: { dash: [3, 3], display: false }, beginAtZero: true, ticks: { stepSize: 1, precision: 0, color: lc } }
          },
          animation: { duration: 900, easing: 'easeOutQuart' }
        }
      });
    }

    /* ── Doughnut Chart ── */
    function loadDoughnut() {
      var canvas = document.getElementById('doughnutChart');
      if (!canvas || typeof Chart === 'undefined') return;
      if (doughnutChart) { doughnutChart.destroy(); doughnutChart = null; }
      var ib = isDark() ? '#1c1d36' : '#fff';
      var tb = isDark() ? '#1d1f35' : '#fff';
      var tx = isDark() ? '#eef0ff' : '#0a0b14';
      var ig = isDark() ? 'rgba(255,255,255,.05)' : 'rgba(0,0,0,.04)';
      doughnutChart = new Chart(canvas, {
        type: 'doughnut',
        data: {
          labels: ['Active', 'Pending', 'Paused', 'Rejected', 'Expired'],
          datasets: [{ data: config.doughnutData, backgroundColor: ['#0d9488', '#f59e0b', '#f97316', '#f43f5e', '#94a3b8'], borderColor: ib, borderWidth: 3, hoverOffset: 10 }]
        },
        options: {
          responsive: true, maintainAspectRatio: false,
          cutout: '68%',
          plugins: {
            legend: { position: 'bottom', labels: { padding: 16, usePointStyle: true, pointStyle: 'circle', color: isDark() ? '#9ba3c8' : '#454863', font: { family: "'DM Sans', sans-serif", size: 11, weight: '500' }, boxWidth: 8, boxHeight: 8 } },
            tooltip: { backgroundColor: tb, titleColor: tx, bodyColor: tx, borderColor: ig, borderWidth: 1, padding: 13, cornerRadius: 11, callbacks: { label: function (c) { var total = c.dataset.data.reduce(function (a, b) { return a + b; }, 0); var pct = total > 0 ? Math.round((c.parsed / total) * 100) : 0; return ' ' + c.label + ': ' + c.parsed + ' (' + pct + '%)'; } } }
          },
          animation: { animateRotate: true, duration: 1200, easing: 'easeOutQuart' }
        }
      });
    }

    /* ── Revenue Trend Chart ── */
    function loadRevenueChart() {
      var canvas = document.getElementById('revenueChart');
      if (!canvas || typeof Chart === 'undefined') return;
      if (revenueChart) { revenueChart.destroy(); revenueChart = null; }
      var ig = isDark() ? 'rgba(255,255,255,.05)' : 'rgba(0,0,0,.04)';
      var lc = isDark() ? 'rgba(255,255,255,.32)' : 'rgba(0,0,0,.32)';
      var tb = isDark() ? '#1d1f35' : '#fff';
      var tx = isDark() ? '#eef0ff' : '#0a0b14';
      var ctx = canvas.getContext('2d');
      var g = ctx.createLinearGradient(0, 0, 0, 190);
      g.addColorStop(0, 'rgba(37,99,235,.18)');
      g.addColorStop(1, 'rgba(37,99,235,0)');
      revenueChart = new Chart(ctx, {
        type: 'line',
        data: { labels: config.revLabels, datasets: [{ label: 'Revenue', data: config.revData, borderColor: '#2563eb', backgroundColor: g, borderWidth: 2.5, pointRadius: 4, tension: .45, fill: true, pointBackgroundColor: '#2563eb', pointBorderColor: tb, pointBorderWidth: 2, pointHoverRadius: 6 }] },
        options: {
          responsive: true, maintainAspectRatio: false,
          interaction: { intersect: false, mode: 'index' },
          plugins: { legend: { display: false }, tooltip: { backgroundColor: tb, titleColor: tx, bodyColor: tx, borderColor: ig, borderWidth: 1, padding: 13, cornerRadius: 11, callbacks: { label: function (c) { return '₹' + Number(c.parsed).toLocaleString('en-IN', { maximumFractionDigits: 0 }); } } } },
          scales: {
            x: { grid: { color: ig }, border: { dash: [3, 3], display: false }, ticks: { color: lc } },
            y: { grid: { color: ig }, border: { dash: [3, 3], display: false }, beginAtZero: true, ticks: { color: lc, callback: function (v) { return '₹' + (v / 1000).toFixed(0) + 'k'; } } }
          },
          animation: { duration: 900, easing: 'easeOutQuart' }
        }
      });
    }

    /* ── Top Campaigns Bar Chart ── */
    function loadTopCampChart() {
      var canvas = document.getElementById('topCampChart');
      if (!canvas || typeof Chart === 'undefined') return;
      if (topCampChart) { topCampChart.destroy(); topCampChart = null; }
      var ig = isDark() ? 'rgba(255,255,255,.05)' : 'rgba(0,0,0,.04)';
      var lc = isDark() ? 'rgba(255,255,255,.32)' : 'rgba(0,0,0,.32)';
      var tb = isDark() ? '#1d1f35' : '#fff';
      var tx = isDark() ? '#eef0ff' : '#0a0b14';
      topCampChart = new Chart(canvas, {
        type: 'bar',
        data: {
          labels: config.topCampLabels,
          datasets: [{ label: 'Raised', data: config.topCampValues, backgroundColor: isDark() ? 'rgba(37,99,235,.7)' : 'rgba(37,99,235,.75)', hoverBackgroundColor: isDark() ? 'rgba(37,99,235,.9)' : 'rgba(37,99,235,.95)', borderColor: isDark() ? 'rgba(37,99,235,.9)' : '#2563eb', borderWidth: 1, borderRadius: 4, barPercentage: .65 }]
        },
        options: {
          responsive: true, maintainAspectRatio: false,
          indexAxis: 'y',
          plugins: { legend: { display: false }, tooltip: { backgroundColor: tb, titleColor: tx, bodyColor: tx, borderColor: ig, borderWidth: 1, padding: 13, cornerRadius: 11, titleFont: { size: 11, weight: '700' }, bodyFont: { size: 11 }, callbacks: { label: function (c) { return '₹' + Number(c.parsed).toLocaleString('en-IN', { maximumFractionDigits: 0 }); } } } },
          scales: {
            x: { grid: { color: ig }, border: { dash: [3, 3], display: false }, beginAtZero: true, ticks: { color: lc, callback: function (v) { return '₹' + (v / 1000).toFixed(0) + 'k'; } } },
            y: { grid: { display: false }, border: { display: false }, ticks: { color: lc, font: { size: 10.5 } } }
          },
          animation: { duration: 900, easing: 'easeOutQuart' }
        }
      });
    }

    loadLineChart();
    loadDoughnut();
    loadRevenueChart();
    loadTopCampChart();

    window.addEventListener('themechange', function () {
      loadLineChart();
      loadDoughnut();
      loadRevenueChart();
      loadTopCampChart();
    });
  }

  function initCampaignGrid(config) {
    var grid = document.getElementById('campaignGrid');
    var paginationWrap = document.getElementById('paginationWrap');
    var noResults = document.getElementById('noResults');
    if (!grid) return;

    var state = 'active', searchQ = '', sortVal = '', isFetching = false, currentPage = 1;

    function csrfToken() {
      var m = document.querySelector('meta[name="csrf-token"]');
      return m ? m.getAttribute('content') : '';
    }

    function setTab(f, v) {
      var el = document.querySelector('.ftab[data-filter="' + f + '"] .cnt');
      if (el) el.textContent = v;
      var opt = document.querySelector('#ftabSelect option[value="' + f + '"]');
      if (opt) opt.textContent = f.charAt(0).toUpperCase() + f.slice(1) + ' (' + v + ')';
    }

    function fetchGrid(page) {
      page = page || 1;
      currentPage = page;
      if (isFetching) return;
      isFetching = true;
      grid.classList.add('loading');
      var params = new URLSearchParams({ state: state, search: searchQ, sort: sortVal, cpage: page });
      fetch(config.routes.campaigns + '?' + params.toString(), { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(function (r) { return r.json(); })
        .then(function (data) {
          grid.innerHTML = data.cards;
          paginationWrap.innerHTML = data.pagination;
          noResults.style.display = data.total > 0 ? 'none' : 'block';
          if (data.counts) {
            setTab('all', data.counts.totalCampaigns);
            setTab('pending', data.counts.cntPending);
            setTab('active', data.counts.cntActive);
            setTab('paused', data.counts.cntPaused);
            setTab('inactive', data.counts.cntExpired + data.counts.cntCompleted);
            setTab('rejected', data.counts.cntRejected);
          }
          bindCardInteractions();
        })
        .catch(function () { window.toast('Failed to load campaigns.', 'error'); })
        .finally(function () { isFetching = false; grid.classList.remove('loading'); });
    }

    document.querySelectorAll('.ftab').forEach(function (tab) {
      tab.addEventListener('click', function () {
        document.querySelectorAll('.ftab').forEach(function (t) { t.classList.remove('on'); });
        this.classList.add('on');
        state = this.dataset.filter;
        fetchGrid(1);
        var sel = document.getElementById('ftabSelect');
        if (sel) sel.value = state;
      });
    });
    var ftabSelect = document.getElementById('ftabSelect');
    if (ftabSelect) {
      ftabSelect.addEventListener('change', function () {
        window.setFilter(this.value);
      });
    }

    window.setFilter = function (f) {
      state = f;
      document.querySelectorAll('.ftab').forEach(function (t) { t.classList.toggle('on', t.dataset.filter === f); });
      var sel = document.getElementById('ftabSelect');
      if (sel) sel.value = f;
      fetchGrid(1);
      var el = document.getElementById('cGrid');
      if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
    };

    var st;
    var searchInput = document.getElementById('searchInput');
    if (searchInput) {
      searchInput.addEventListener('input', function () {
        clearTimeout(st);
        searchQ = this.value.toLowerCase().trim();
        st = setTimeout(function () { fetchGrid(1); }, 180);
      });
    }
    var sortSelect = document.getElementById('sortSelect');
    if (sortSelect) {
      sortSelect.addEventListener('change', function () {
        sortVal = this.value;
        fetchGrid(1);
      });
    }

    if (paginationWrap) {
      paginationWrap.addEventListener('click', function (e) {
        var a = e.target.closest('a');
        if (!a) return;
        e.preventDefault();
        var url = new URL(a.href, location.href);
        fetchGrid(parseInt(url.searchParams.get('cpage')) || 1);
      });
    }

    function bindCardInteractions() {
      grid.querySelectorAll('.c-card').forEach(function (card) {
        card.addEventListener('click', function (e) {
          if (e.target.closest('a,button,form,label,input,textarea')) return;
          openQuick(card.dataset.id);
        });
      });
      grid.querySelectorAll('.c-checkbox').forEach(function (cb) {
        cb.addEventListener('change', updateBulkBar);
      });
      bindTilt();
    }

    function bindTilt() {
      if (window.matchMedia('(hover: none)').matches) return;
      grid.querySelectorAll('.c-card:not(.no-tilt)').forEach(function (card) {
        if (card.dataset.tilted) return;
        card.dataset.tilted = '1';
        card.addEventListener('mousemove', function (e) {
          var r = card.getBoundingClientRect();
          var px = (e.clientX - r.left) / r.width - .5;
          var py = (e.clientY - r.top) / r.height - .5;
          card.style.transform = 'translateY(-5px) rotateX(' + (-py * 6).toFixed(2) + 'deg) rotateY(' + (px * 8).toFixed(2) + 'deg)';
        });
        card.addEventListener('mouseleave', function () {
          card.style.transform = '';
        });
      });
    }
    bindCardInteractions();

    /* ── Bulk actions ── */
    var bulkBar = document.getElementById('bulkBar');
    function getSelectedIds() { return Array.from(grid.querySelectorAll('.c-checkbox:checked')).map(function (c) { return +c.value; }); }
    function updateBulkBar() {
      var n = getSelectedIds().length;
      document.getElementById('bbCount').textContent = n;
      if (bulkBar) bulkBar.classList.toggle('open', n > 0);
    }
    function clearSelection() {
      grid.querySelectorAll('.c-checkbox:checked').forEach(function (c) { c.checked = false; });
      updateBulkBar();
    }
    var bbClear = document.getElementById('bbClear');
    if (bbClear) bbClear.addEventListener('click', clearSelection);

    function postBulk(url, body, done) {
      fetch(url, { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrfToken(), 'Content-Type': 'application/json', 'Accept': 'application/json' }, body: JSON.stringify(body) })
        .then(function (r) { return r.json(); })
        .then(function (d) { if (d.message) window.toast(d.message, d.type || 'success'); if (done) done(); })
        .catch(function () { window.toast('Bulk action failed.', 'error'); });
    }
    var bbApprove = document.getElementById('bbApprove');
    if (bbApprove) {
      bbApprove.addEventListener('click', function () {
        var ids = getSelectedIds();
        if (!ids.length) return;
        postBulk(config.routes.bulkApprove, { ids: ids }, function () { clearSelection(); fetchGrid(currentPage); });
      });
    }

    var bulkMode = null;
    var bbReject = document.getElementById('bbReject');
    var bbPause = document.getElementById('bbPause');
    if (bbReject) bbReject.addEventListener('click', function () { openBulk('reject'); });
    if (bbPause) bbPause.addEventListener('click', function () { openBulk('pause'); });

    function openBulk(mode) {
      bulkMode = mode;
      var form = document.getElementById('bulkForm');
      if (mode === 'reject') form.action = config.routes.bulkReject;
      else form.action = config.routes.bulkPause;
      var ttl = document.getElementById('bulkTtl');
      var sub = document.getElementById('bulkSub');
      if (ttl) ttl.textContent = mode === 'reject' ? 'Reject Campaigns' : 'Pause Campaigns';
      if (sub) sub.textContent = 'Action applies to ' + getSelectedIds().length + ' selected campaign(s).';
      var reason = document.getElementById('bulkReason');
      if (reason) reason.value = '';
      var err = document.getElementById('bulkErr');
      if (err) err.style.display = 'none';
      var req = document.getElementById('bulkReq');
      if (req) req.style.display = mode === 'reject' ? 'inline' : 'none';
      var btn = document.getElementById('bulkBtn');
      if (btn) {
        btn.className = 'modal-btn ' + (mode === 'reject' ? 'modal-red' : 'modal-amber');
        btn.textContent = mode === 'reject' ? '✕ Reject' : '⏸ Pause';
      }
      var overlay = document.getElementById('bulkOverlay');
      if (overlay) overlay.classList.add('open');
      setTimeout(function () { var r = document.getElementById('bulkReason'); if (r) r.focus(); }, 80);
    }
    window.closeBulk = function () {
      var o = document.getElementById('bulkOverlay');
      if (o) o.classList.remove('open');
    };
    var bulkForm = document.getElementById('bulkForm');
    if (bulkForm) {
      bulkForm.addEventListener('submit', function (e) {
        e.preventDefault();
        if (bulkMode === 'reject' && !document.getElementById('bulkReason').value.trim()) {
          var err = document.getElementById('bulkErr');
          if (err) err.style.display = 'block';
          return;
        }
        var ids = getSelectedIds();
        var fd = new FormData(this);
        ids.forEach(function (id) { fd.append('ids[]', id); });
        var btn = document.getElementById('bulkBtn');
        if (btn) { btn.disabled = true; btn.textContent = 'Processing…'; }
        fetch(this.action, { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrfToken(), 'Accept': 'application/json' }, body: fd })
          .then(function (r) { return r.json(); })
          .then(function (d) { if (d.message) window.toast(d.message, d.type || 'success'); closeBulk(); clearSelection(); fetchGrid(currentPage); })
          .catch(function () { window.toast('Bulk action failed.', 'error'); })
          .finally(function () { if (btn) { btn.disabled = false; btn.textContent = bulkMode === 'reject' ? '✕ Reject' : '⏸ Pause'; } });
      });
    }

    /* ── Quick view slide-over ── */
    var quickPanel = document.getElementById('quickPanel');
    var quickBackdrop = document.getElementById('quickBackdrop');
    function openQuick(id) {
      var qc = document.getElementById('quickContent');
      var ql = document.getElementById('quickLoading');
      if (qc) qc.innerHTML = '';
      if (ql) ql.style.display = 'flex';
      if (quickPanel) quickPanel.classList.add('open');
      if (quickBackdrop) quickBackdrop.classList.add('open');
      var url = config.routes.quickView.replace('__ID__', id);
      fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(function (r) { return r.text(); })
        .then(function (html) {
          if (qc) qc.innerHTML = html;
          if (ql) ql.style.display = 'none';
        })
        .catch(function () {
          if (ql) ql.style.display = 'none';
          window.toast('Failed to load details.', 'error');
        });
    }
    window.closeQuick = function () {
      if (quickPanel) quickPanel.classList.remove('open');
      if (quickBackdrop) quickBackdrop.classList.remove('open');
    };

    /* ── Pause modal ── */
    function openPause(id) {
      var form = document.getElementById('pauseForm');
      if (form) form.action = '/admin/campaign/' + id + '/pause';
      var reason = document.getElementById('pauseReason');
      if (reason) reason.value = '';
      var err = document.getElementById('pauseErr');
      if (err) err.style.display = 'none';
      var btn = document.getElementById('pauseBtn');
      if (btn) { btn.disabled = false; btn.innerHTML = '⏸ Pause Campaign'; }
      document.querySelectorAll('.chip-amber').forEach(function (c) { c.classList.remove('on'); });
      var overlay = document.getElementById('pauseOverlay');
      if (overlay) overlay.classList.add('open');
      setTimeout(function () { var r = document.getElementById('pauseReason'); if (r) r.focus(); }, 80);
    }
    window.openPause = openPause;
    window.closePause = function () {
      var o = document.getElementById('pauseOverlay');
      if (o) o.classList.remove('open');
    };
    document.querySelectorAll('.chip-amber').forEach(function (btn) {
      btn.addEventListener('click', function () {
        document.querySelectorAll('.chip-amber').forEach(function (b) { b.classList.remove('on'); });
        this.classList.add('on');
        var r = document.getElementById('pauseReason');
        if (r) r.value = this.dataset.r;
        var err = document.getElementById('pauseErr');
        if (err) err.style.display = 'none';
      });
    });
    var pauseForm = document.getElementById('pauseForm');
    if (pauseForm) {
      pauseForm.addEventListener('submit', function (e) {
        if (!document.getElementById('pauseReason').value.trim()) {
          e.preventDefault();
          var err = document.getElementById('pauseErr');
          if (err) err.style.display = 'block';
          return;
        }
        var btn = document.getElementById('pauseBtn');
        if (btn) { btn.disabled = true; btn.innerHTML = 'Pausing…'; }
      });
    }

    /* ── Reject modal ── */
    function openReject(id) {
      var form = document.getElementById('rejectForm');
      if (form) form.action = '/admin/campaign/' + id + '/reject';
      var reason = document.getElementById('rejectReason');
      if (reason) reason.value = '';
      var err = document.getElementById('rejectErr');
      if (err) err.style.display = 'none';
      var btn = document.getElementById('rejectBtn');
      if (btn) { btn.disabled = false; btn.innerHTML = '✕ Reject Campaign'; }
      document.querySelectorAll('.chip-red').forEach(function (c) { c.classList.remove('on'); });
      var overlay = document.getElementById('rejectOverlay');
      if (overlay) overlay.classList.add('open');
      setTimeout(function () { var r = document.getElementById('rejectReason'); if (r) r.focus(); }, 80);
    }
    window.openReject = openReject;
    window.closeReject = function () {
      var o = document.getElementById('rejectOverlay');
      if (o) o.classList.remove('open');
    };
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
        if (!document.getElementById('rejectReason').value.trim()) {
          e.preventDefault();
          var err = document.getElementById('rejectErr');
          if (err) err.style.display = 'block';
          return;
        }
        var btn = document.getElementById('rejectBtn');
        if (btn) { btn.disabled = true; btn.innerHTML = 'Rejecting…'; }
      });
    }
  }

  function initDashboard(config) {
    if (!config) return;
    if (document.getElementById('lineChart') || document.getElementById('doughnutChart')) {
      initDashboardCharts(config);
    }
    if (document.getElementById('campaignGrid')) {
      initCampaignGrid(config);
    }
    /* ── Live activity ticker ── */
    (function () {
      var items = (config.tickerItems || []).filter(function (s) { return !s.startsWith('<b>0</b>'); });
      if (!items.length) return;
      var track = document.getElementById('tickerTrack');
      if (!track) return;
      var i = 0;
      function show() {
        var el = document.createElement('div');
        el.className = 'hero-ticker-item';
        el.innerHTML = items[i];
        track.innerHTML = '';
        track.appendChild(el);
        requestAnimationFrame(function () { el.classList.add('show'); });
        i = (i + 1) % items.length;
        setTimeout(show, 3200);
      }
      show();
    })();

    /* ── Animated stat counters ── */
    document.querySelectorAll('.hb-count').forEach(function (el) {
      var target = parseInt(el.getAttribute('data-count'), 10) || 0;
      if (target > 0) animateCounter(el, target);
    });
    document.querySelectorAll('.hc-count').forEach(function (el) {
      var target = parseInt(el.getAttribute('data-count'), 10) || 0;
      if (target > 0) animateCounter(el, target);
    });
  }

  /* ── Auto-init dashboard if data exists ── */
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function () {
      if (window.__DASHBOARD_DATA__) initDashboard(window.__DASHBOARD_DATA__);
    });
  } else {
    if (window.__DASHBOARD_DATA__) initDashboard(window.__DASHBOARD_DATA__);
  }

})();
