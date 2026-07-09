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
    hamburger.addEventListener('click', function () {
      sidebar.classList.toggle('open');
      if (overlay) overlay.classList.toggle('open');
    });
    document.addEventListener('click', function (e) {
      if (window.innerWidth <= 960 && !sidebar.contains(e.target) && !hamburger.contains(e.target)) {
        sidebar.classList.remove('open');
        if (overlay) overlay.classList.remove('open');
      }
    });
    if (overlay) {
      overlay.addEventListener('click', function () {
        sidebar.classList.remove('open');
        overlay.classList.remove('open');
      });
    }
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
})();
