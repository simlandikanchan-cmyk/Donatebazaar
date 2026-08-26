/* ══════════════════════════════════════
   ADMIN — Shared Shell
   Sidebar, theme, avatar dropdown, toast,
   modals, form loading, and generic
   data-action handlers.
   ══════════════════════════════════════ */

import { toast as showToast } from '../../shared/toast.js';
import { initModalDefaults } from '../../shared/modal.js';
import { initThemeToggle } from '../../shared/theme.js';

(function () {
  'use strict';

  const html = document.documentElement;
  const sidebar = document.getElementById('sidebar');
  const overlay = document.getElementById('sidebarOverlay');
  const hamburger = document.getElementById('hamburger');
  const avWrap = document.getElementById('avWrap');
  const toastWrap = document.getElementById('toastWrap');

  /* ── Theme ── */
  initThemeToggle({ storageKey: 'adminTheme', dispatchEvent: true });

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
      avBtn.addEventListener('click', function (e) {
        e.stopPropagation();
        dd.classList.toggle('open');
      });
      document.addEventListener('click', function (e) {
        if (!avWrap.contains(e.target)) dd.classList.remove('open');
      });
    }
  }

  /* ── Toast System (uses shared/toast.js, auto-detects #toastWrap) ── */
  if (toastWrap) {
    const data = toastWrap.dataset;
    if (data.success) setTimeout(function () { showToast(data.success, 'success'); }, 200);
    if (data.error) setTimeout(function () { showToast(data.error, 'error'); }, 200);
    if (data.warning) setTimeout(function () { showToast(data.warning, 'warn'); }, 200);
  }

  /* ── Modal helpers ── */
  initModalDefaults();

  /* ══════════════════════════════════════
     FORM SUBMIT LOADING — data-loading-text
     ══════════════════════════════════════ */
  document.addEventListener('submit', function (e) {
    var form = e.target.closest('[data-loading-text]');
    if (!form) return;
    var txt = form.getAttribute('data-loading-text');
    form.querySelectorAll('button[type=submit]').forEach(function (b) {
      b.disabled = true;
      b.textContent = txt;
    });
  });

  /* ══════════════════════════════════════
     DATA-CONFIRM — global confirm before submit
     ══════════════════════════════════════ */
  document.addEventListener('submit', function (e) {
    var form = e.target.closest('form[data-confirm]');
    if (!form) return;
    if (!confirm(form.getAttribute('data-confirm'))) e.preventDefault();
  });

  /* ── navigate (data-action="navigate" data-href="...") ── */
  document.addEventListener('click', function (e) {
    var el = e.target.closest('[data-action="navigate"]');
    if (!el) return;
    e.preventDefault();
    window.location.href = el.getAttribute('data-href');
  });

  /* ── close-modal (data-action="close-modal" data-target="#modalId") ── */
  document.addEventListener('click', function (e) {
    var el = e.target.closest('[data-action="close-modal"]');
    if (!el) return;
    var target = el.getAttribute('data-target');
    var modal = target ? document.querySelector(target) : el.closest('.overlay, .modal, [class*="Overlay"]');
    if (modal) modal.classList.remove('open', 'show');
  });

})();
