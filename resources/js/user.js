import Chart from 'chart.js/auto';
window.Chart = Chart;

(function () {
  'use strict';

  const html = document.documentElement;
  const toggle = document.getElementById('themeToggle');
  const sidebar = document.getElementById('sidebar');
  const backdrop = document.getElementById('sidebarBackdrop');
  const hamburger = document.getElementById('hamburger');
  const avWrap = document.getElementById('avWrap');
  const toastContainer = document.getElementById('toastContainer');

  var saved = localStorage.getItem('theme') || 'light';
  if (saved === 'dark') { html.setAttribute('data-theme', 'dark'); if (toggle) toggle.checked = true; }
  if (toggle) {
    toggle.addEventListener('change', function () {
      var t = this.checked ? 'dark' : 'light';
      html.setAttribute('data-theme', t);
      localStorage.setItem('theme', t);
      if (typeof renderChart === 'function') setTimeout(renderChart, 50);
    });
  }

  if (hamburger && sidebar) {
    hamburger.addEventListener('click', function () {
      sidebar.classList.toggle('open');
      if (backdrop) backdrop.classList.toggle('open');
    });
    document.addEventListener('click', function (e) {
      if (window.innerWidth <= 860 && !sidebar.contains(e.target) && !hamburger.contains(e.target)) {
        sidebar.classList.remove('open');
        if (backdrop) backdrop.classList.remove('open');
      }
    });
    if (backdrop) {
      backdrop.addEventListener('click', function () {
        sidebar.classList.remove('open');
        backdrop.classList.remove('open');
      });
    }
  }

  if (avWrap) {
    var dd = avWrap.querySelector('.av-dd');
    var avBtn = avWrap.querySelector('.t-avatar');
    if (avBtn && dd) {
      window.toggleDD = function () { dd.classList.toggle('open'); };
      avBtn.addEventListener('click', function (e) {
        e.stopPropagation();
        dd.classList.toggle('open');
      });
      document.addEventListener('click', function (e) {
        if (!avWrap.contains(e.target)) dd.classList.remove('open');
      });
    }
  }

  window.toast = function (msg, type) {
    type = type || 'success';
    var icons = {
      success: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
      error: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
    };
    var el = document.createElement('div');
    el.className = 'toast toast-' + type;
    el.innerHTML = (icons[type] || icons.success) + '<span>' + msg + '</span><button class="toast-close" onclick="this.parentElement.remove()">\u2715</button>';
    if (toastContainer) {
      toastContainer.appendChild(el);
      setTimeout(function () { el.remove(); }, 4500);
    }
  };

  if (toastContainer) {
    var data = toastContainer.dataset;
    if (data.success) setTimeout(function () { window.toast(data.success, 'success'); }, 200);
    if (data.error) setTimeout(function () { window.toast(data.error, 'error'); }, 200);
  }
})();
