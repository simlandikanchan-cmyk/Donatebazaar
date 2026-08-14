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
    el.insertAdjacentHTML('afterbegin', icons[type] || icons.success);
    var span = document.createElement('span');
    span.textContent = msg;
    el.appendChild(span);
    var close = document.createElement('button');
    close.className = 'toast-close';
    close.textContent = '\u2715';
    close.addEventListener('click', function () { el.remove(); });
    el.appendChild(close);
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

  /* ── Notifications dropdown ── */
  const notifBell = document.getElementById('notifBell');
  const notifPanel = document.getElementById('notifPanel');
  const notifList = document.getElementById('notifList');
  const notifEmpty = document.getElementById('notifEmpty');
  const notifBadge = document.getElementById('notifBadge');
  const notifMarkAll = document.getElementById('notifMarkAll');

  function getCsrf() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
  }

  function escapeHtml(str) {
    const div = document.createElement('div');
    div.textContent = str ?? '';
    return div.innerHTML;
  }

  function notifIcon(type) {
    switch (type) {
      case 'kyc_requested':
        return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>';
      case 'kyc_submitted':
        return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>';
      case 'donation':
        return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>';
      case 'campaign':
        return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 19v-6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2zm0 0V9a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v10m-6 0a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2m0 0V5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-2a2 2 0 0 1-2-2z"/></svg>';
      default:
        return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>';
    }
  }

  function updateNotifBadge(count) {
    if (!notifBadge) return;
    count = parseInt(count, 10) || 0;
    if (count > 0) {
      notifBadge.textContent = count > 9 ? '9+' : count;
      notifBadge.hidden = false;
    } else {
      notifBadge.hidden = true;
    }
  }

  function renderNotifs(items) {
    if (!notifList || !notifEmpty) return;
    notifList.querySelectorAll('.notif-item').forEach((el) => el.remove());
    if (!items || items.length === 0) {
      notifEmpty.hidden = false;
      return;
    }
    notifEmpty.hidden = true;
    const frag = document.createDocumentFragment();
    items.forEach((n) => {
      const item = document.createElement('a');
      item.className = 'notif-item' + (n.read_at ? '' : ' is-unread');
      item.href = n.url || '#';
      item.dataset.id = n.id;
      item.innerHTML =
        '<span class="notif-item-icon">' + notifIcon(n.type) + '</span>' +
        '<span class="notif-item-body">' +
          '<span class="notif-item-msg">' + escapeHtml(n.message || n.title) + '</span>' +
          '<span class="notif-item-time">' + escapeHtml(n.created_at) + '</span>' +
        '</span>' +
        (n.read_at ? '' : '<span class="notif-item-dot"></span>');
      frag.appendChild(item);
    });
    notifList.appendChild(frag);
  }

  async function loadNotifs() {
    try {
      const res = await fetch('/notifications', {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
      });
      if (!res.ok) return;
      const data = await res.json();
      renderNotifs(data.notifications);
      updateNotifBadge(data.unread_count);
    } catch (e) { /* no-op */ }
  }

  async function markNotifRead(id, url) {
    try {
      await fetch('/notifications/' + encodeURIComponent(id) + '/read', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': getCsrf(), 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json', 'Content-Type': 'application/json' },
      });
    } catch (e) { /* no-op */ }
    if (url && url !== '#') window.location.href = url;
  }

  async function markAllNotifsRead() {
    try {
      const res = await fetch('/notifications/read-all', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': getCsrf(), 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json', 'Content-Type': 'application/json' },
      });
      if (!res.ok) return;
      const data = await res.json();
      updateNotifBadge(data.unread_count);
      notifList?.querySelectorAll('.notif-item.is-unread').forEach((el) => {
        el.classList.remove('is-unread');
        el.querySelector('.notif-item-dot')?.remove();
      });
    } catch (e) { /* no-op */ }
  }

  if (notifBell && notifPanel) {
    notifBell.addEventListener('click', function (e) {
      e.stopPropagation();
      const open = notifPanel.hidden;
      notifPanel.hidden = !open;
      notifBell.setAttribute('aria-expanded', String(open));
      if (open) loadNotifs();
    });
    notifList?.addEventListener('click', function (e) {
      const item = e.target.closest('.notif-item');
      if (!item || !item.dataset.id) return;
      e.preventDefault();
      markNotifRead(item.dataset.id, item.getAttribute('href'));
    });
    notifMarkAll?.addEventListener('click', function (e) {
      e.stopPropagation();
      markAllNotifsRead();
    });
    document.addEventListener('click', function (e) {
      const wrap = document.getElementById('notifWrap');
      if (wrap && !wrap.contains(e.target) && !notifPanel.hidden) {
        notifPanel.hidden = true;
        notifBell.setAttribute('aria-expanded', 'false');
      }
    });
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && !notifPanel.hidden) {
        notifPanel.hidden = true;
        notifBell.setAttribute('aria-expanded', 'false');
        notifBell.focus();
      }
    });
  }

  /* ── Button Ripple ── */
  document.addEventListener('mousedown', function (e) {
    var btn = e.target.closest('.btn');
    if (!btn) return;
    var rect = btn.getBoundingClientRect();
    var x = ((e.clientX - rect.left) / rect.width * 100).toFixed(1);
    var y = ((e.clientY - rect.top) / rect.height * 100).toFixed(1);
    btn.style.setProperty('--mx', x + '%');
    btn.style.setProperty('--my', y + '%');
  });
})();
