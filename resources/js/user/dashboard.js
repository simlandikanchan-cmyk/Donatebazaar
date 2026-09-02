/* ═══════════════════════════════════════════════════════════════════
   user/dashboard.js — user dashboard behavior
   Reads server data from the #dashboardData JSON block.
   Re-renders charts on the 'themechange' event (dispatched by user.js).
   ═══════════════════════════════════════════════════════════════════ */

import Chart from 'chart.js/auto';

(function () {
  'use strict';

  var html = document.documentElement;

  /* ── SERVER DATA ── */
  var data = {};
  (function () {
    var dataEl = document.getElementById('dashboardData');
    if (!dataEl) return;
    try { data = JSON.parse(dataEl.textContent); } catch (e) { /* keep defaults */ }
  })();
  var overallPct = parseInt(data.overallPct, 10) || 0;
  var levelProgress = parseInt(data.levelProgress, 10) || 0;
  var monthlyData = data.monthlyData || {};
  var campChartData = data.campChartData || [];

  /* ── Font-ready guard + reveal orchestration ── */
  var prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  function startAnimations() {
    animateCounters();
    runReveal();
    runBars();
    runRings();
  }

  /* Show below-the-fold content once scroll reaches it.
     Elements are only hidden AFTER JS is confirmed running,
     so a JS failure never leaves content invisible. */
  function runReveal() {
    var targets = document.querySelectorAll('[data-reveal]');
    // Immediately reveal everything when motion is reduced or IO is absent.
    if (prefersReduced || !('IntersectionObserver' in window)) {
      targets.forEach(function (t) { t.classList.add('revealed'); });
      return;
    }
    targets.forEach(function (t) { t.classList.add('reveal-on-load'); });
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('revealed');
          io.unobserve(entry.target);
        }
      });
    }, { threshold: 0.06, rootMargin: '0px 0px -40px 0px' });
    targets.forEach(function (t) { io.observe(t); });
  }

  // Preserve layout stability by reserving space before reveal fades in.
  function runBars() {
    var bar = document.getElementById('overallBar');
    if (bar) bar.style.width = overallPct + '%';
    var lvl = document.getElementById('levelFill');
    if (lvl) lvl.style.width = levelProgress + '%';
  }
  function runRings() {
    var ring = document.getElementById('impactRing');
    if (ring) {
      var circ = 2 * Math.PI * 52;
      ring.style.strokeDasharray = circ;
      ring.style.strokeDashoffset = circ - (circ * overallPct / 100);
    }
  }

  /* ── Animated stat counters ── */
  function animateCounter(el, target, prefix, suffix) {
    if (prefersReduced) {
      el.textContent = prefix + target.toLocaleString('en-IN') + suffix;
      return;
    }
    var duration = 900, start = 0, startTime = null;
    prefix = prefix || '';
    suffix = suffix || '';
    function step(timestamp) {
      if (!startTime) startTime = timestamp;
      var progress = Math.min((timestamp - startTime) / duration, 1);
      var eased = 1 - Math.pow(1 - progress, 3);
      var current = Math.floor(eased * target);
      el.textContent = prefix + current.toLocaleString('en-IN') + suffix;
      if (progress < 1) requestAnimationFrame(step);
    }
    requestAnimationFrame(step);
  }
  function animateCounters() {
    document.querySelectorAll('.stat-val').forEach(function (el) {
      var original = el.textContent;
      var prefixMatch = original.match(/^(\D*)/);
      var prefix = prefixMatch ? prefixMatch[1] : '';
      var raw = original.replace(/[₹,]/g, '').trim();
      var num = parseInt(raw, 10);
      if (!isNaN(num) && num > 0) {
        var suffix = original.includes('%') ? '%' : '';
        el.textContent = prefix + '0' + suffix;
        animateCounter(el, num, prefix, suffix);
      }
    });
  }

  /* ── Start animations once fonts (and thus metrics) are ready ── */
  var started = false;
  function boot() {
    if (started) return;
    started = true;
    if (prefersReduced) { startAnimations(); return; }
    // Small staggered sync so layout stays stable while content appears.
    setTimeout(function () {
      animateCounters();
      runBars();
      runRings();
      runReveal();
    }, 40);
  }
  if (document.fonts && document.fonts.ready) {
    document.fonts.ready.then(boot);
  } else {
    window.addEventListener('load', boot);
  }
  // Safety fallback in case the above never resolves.
  setTimeout(boot, 2000);

  /* ── Filter + Search + Sort ── */
  var activeFilter = 'all', searchQ = '', sortVal = '';

  function getCards() {
    var isGrid = document.getElementById('campaignGrid').style.display !== 'none';
    return Array.from(document.querySelectorAll(isGrid ? '#campaignGrid .c-card' : '#campaignList .c-list-item'));
  }

  function applyFilters() {
    var all = Array.from(document.querySelectorAll('#campaignGrid .c-card, #campaignList .c-list-item'));

    if (sortVal) {
      var grids = Array.from(document.querySelectorAll('#campaignGrid .c-card'));
      var lists = Array.from(document.querySelectorAll('#campaignList .c-list-item'));
      [grids, lists].forEach(function (arr) {
        if (!arr.length) return;
        if (sortVal === 'amount-desc') arr.sort(function (a, b) { return +b.dataset.amount - +a.dataset.amount; });
        if (sortVal === 'amount-asc') arr.sort(function (a, b) { return +a.dataset.amount - +b.dataset.amount; });
        if (sortVal === 'date-desc') arr.sort(function (a, b) { return new Date(b.dataset.date) - new Date(a.dataset.date); });
        if (sortVal === 'date-asc') arr.sort(function (a, b) { return new Date(a.dataset.date) - new Date(b.dataset.date); });
        var parent = arr[0].parentElement;
        arr.forEach(function (c) { parent.appendChild(c); });
      });
    }

    var visible = 0;
    all.forEach(function (c) {
      var mF = activeFilter === 'all' || c.dataset.filter === activeFilter;
      var mS = !searchQ || (c.dataset.title || '').includes(searchQ);
      c.style.display = (mF && mS) ? '' : 'none';
      if (mF && mS) visible++;
    });
    document.getElementById('noResults').style.display = visible === 0 ? 'block' : 'none';
  }

  function setFilter(f) {
    activeFilter = f;
    document.querySelectorAll('.ftab').forEach(function (t) { t.classList.toggle('on', t.dataset.filter === f); });
    document.getElementById('ftabSelect').value = f;
    applyFilters();
    var el = document.getElementById('cGrid');
    if (el) {
      // Offset for the sticky topbar so the section heading is fully visible.
      var top = el.getBoundingClientRect().top + window.pageYOffset - 76;
      window.scrollTo({ top: top, behavior: 'smooth' });
    }
  }

  document.querySelectorAll('.ftab').forEach(function (tab) {
    tab.addEventListener('click', function () {
      document.querySelectorAll('.ftab').forEach(function (t) { t.classList.remove('on'); });
      this.classList.add('on');
      activeFilter = this.dataset.filter;
      document.getElementById('ftabSelect').value = activeFilter;
      applyFilters();
    });
  });

  document.getElementById('ftabSelect').addEventListener('change', function () {
    activeFilter = this.value;
    document.querySelectorAll('.ftab').forEach(function (t) { t.classList.toggle('on', t.dataset.filter === activeFilter); });
    applyFilters();
  });

  /* Status rows in the "Campaign Status" panel (data-action="set-filter"). */
  document.addEventListener('click', function (e) {
    var el = e.target.closest('[data-action="set-filter"]');
    if (!el) return;
    setFilter(el.getAttribute('data-filter'));
  });

  var searchTimeout;
  document.getElementById('searchInput').addEventListener('input', function () {
    clearTimeout(searchTimeout);
    searchQ = this.value.toLowerCase().trim();
    searchTimeout = setTimeout(applyFilters, 180);
  });

  document.getElementById('sortSelect').addEventListener('change', function () {
    sortVal = this.value;
    applyFilters();
  });

  var mobileSearch = document.getElementById('mobileSearchInput');
  var mobileSort = document.getElementById('mobileSortSelect');
  if (mobileSearch) {
    mobileSearch.addEventListener('input', function () {
      var desktopSearch = document.getElementById('searchInput');
      if (desktopSearch) {
        desktopSearch.value = this.value;
        desktopSearch.dispatchEvent(new Event('input'));
      }
    });
  }
  if (mobileSort) {
    mobileSort.addEventListener('change', function () {
      var desktopSort = document.getElementById('sortSelect');
      if (desktopSort) {
        desktopSort.value = this.value;
        desktopSort.dispatchEvent(new Event('change'));
      }
    });
  }

  var grid = document.getElementById('campaignGrid');
  var viewSelect = document.getElementById('viewSelect');

  if (viewSelect) {
    viewSelect.addEventListener('change', function () {
      applyFilters();
    });
  }

  function handleSub(form, txt) {
    form.querySelectorAll('button[type=submit]').forEach(function (b) { b.disabled = true; b.textContent = txt; });
    return true;
  }

  /* ── Chart ── */
  var fundChart;
  function renderChart() {
    var isDark = html.getAttribute('data-theme') === 'dark';
    var gridColor = isDark ? 'rgba(255,255,255,.05)' : 'rgba(0,0,0,.04)';
    var lblColor = isDark ? 'rgba(255,255,255,.35)' : 'rgba(0,0,0,.35)';
    var tipBg = isDark ? '#1e2033' : '#fff';
    var tipTx = isDark ? '#eef0ff' : '#111';

    Chart.defaults.color = lblColor;
    Chart.defaults.font.family = "'DM Mono', monospace";
    Chart.defaults.font.size = 10.5;

    var ctx = document.getElementById('fundChart');
    if (!ctx) return;
    if (fundChart) fundChart.destroy();

    var labels = Object.keys(monthlyData);
    var values = Object.values(monthlyData);

    var cctx = ctx.getContext('2d');
    var grad = cctx.createLinearGradient(0, 0, 0, 180);
    grad.addColorStop(0, 'rgba(37,99,235,.20)');
    grad.addColorStop(1, 'rgba(37,99,235,0)');

    fundChart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: labels,
        datasets: [{
          label: 'Amount Raised (₹)',
          data: values,
          borderColor: '#2563eb', backgroundColor: grad,
          borderWidth: 2.5, fill: true, tension: .45,
          pointBackgroundColor: '#2563eb',
          pointBorderColor: tipBg, pointBorderWidth: 2,
          pointRadius: 4, pointHoverRadius: 6,
        }]
      },
      options: {
        responsive: true, maintainAspectRatio: false,
        interaction: { intersect: false, mode: 'index' },
        plugins: {
          legend: { display: false },
          tooltip: {
            backgroundColor: tipBg, titleColor: tipTx, bodyColor: tipTx,
            borderColor: gridColor, borderWidth: 1, padding: 12, cornerRadius: 10,
            callbacks: { label: function (c) { return ' ₹' + Number(c.parsed.y).toLocaleString('en-IN'); } }
          }
        },
        scales: {
          x: { grid: { color: gridColor }, border: { dash: [3, 3] } },
          y: { grid: { color: gridColor }, border: { dash: [3, 3] }, ticks: { callback: function (v) { return '₹' + Number(v).toLocaleString('en-IN'); } } }
        }
      }
    });
    if (!prefersReduced) {
      ctx.style.transition = 'opacity .6s ease';
      ctx.style.opacity = '0';
      requestAnimationFrame(function () { ctx.style.opacity = '1'; });
    }
  }
  renderChart();

  /* ── Campaign comparison bar chart ── */
  var campChart;
  (function () {
    var ctx = document.getElementById('campChart');
    if (!ctx) return;
    if (campChart) campChart.destroy();

    var isDark = html.getAttribute('data-theme') === 'dark';
    var gridColor = isDark ? 'rgba(255,255,255,.05)' : 'rgba(0,0,0,.04)';
    var lblColor = isDark ? 'rgba(255,255,255,.35)' : 'rgba(0,0,0,.35)';
    var tipBg = isDark ? '#1e2033' : '#fff';
    var tipTx = isDark ? '#eef0ff' : '#111';

    var campaigns = campChartData;

    campChart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: campaigns.map(function (c) { return c.title; }),
        datasets: [
          {
            label: 'Raised (₹)',
            data: campaigns.map(function (c) { return c.raised; }),
            backgroundColor: '#2563eb',
            borderRadius: 4,
            barPercentage: .65,
          },
          {
            label: 'Goal (₹)',
            data: campaigns.map(function (c) { return c.goal; }),
            backgroundColor: isDark ? 'rgba(37,99,235,.2)' : 'rgba(37,99,235,.10)',
            borderRadius: 4,
            barPercentage: .65,
          }
        ]
      },
      options: {
        responsive: true, maintainAspectRatio: false,
        interaction: { intersect: false, mode: 'index' },
        plugins: {
          legend: {
            position: 'top',
            align: 'end',
            labels: { boxWidth: 10, boxHeight: 10, borderRadius: 2, usePointStyle: true, padding: 16, color: lblColor, font: { family: "'DM Sans', sans-serif", size: 11 } }
          },
          tooltip: {
            backgroundColor: tipBg, titleColor: tipTx, bodyColor: tipTx,
            borderColor: gridColor, borderWidth: 1, padding: 12, cornerRadius: 10,
            callbacks: { label: function (c) { return c.dataset.label + ': ₹' + Number(c.parsed.y).toLocaleString('en-IN'); } }
          }
        },
        scales: {
          x: { grid: { display: false }, ticks: { color: lblColor, font: { size: 10 } } },
          y: { grid: { color: gridColor }, border: { dash: [3, 3] }, ticks: { callback: function (v) { return '₹' + Number(v).toLocaleString('en-IN'); } } }
        }
      }
    });
  })();

  /* Theme re-render (dispatched by user.js theme toggle). */
  window.addEventListener('themechange', function () {
    renderChart();
  });

  /* ── 3D Tilt on campaign cards ── */
  var tiltCards = document.querySelectorAll('#campaignGrid .c-card');
  tiltCards.forEach(function (card) {
    card.addEventListener('mousemove', function (e) {
      var rect = this.getBoundingClientRect();
      var x = (e.clientX - rect.left) / rect.width;
      var y = (e.clientY - rect.top) / rect.height;
      var tiltX = (y - .5) * -12;
      var tiltY = (x - .5) * 12;
      this.style.transform = 'translateY(-6px) perspective(1200px) rotateX(' + tiltX + 'deg) rotateY(' + tiltY + 'deg)';
    });
    card.addEventListener('mouseleave', function () {
      this.style.transform = 'translateY(0) perspective(1200px) rotateX(0deg) rotateY(0deg)';
    });
  });
})();