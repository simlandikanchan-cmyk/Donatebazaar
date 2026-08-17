(function () {
  'use strict';

  /* ── SERVER DATA (from #blogsShowData JSON block) ── */
  var data = {};
  (function () {
    var dataEl = document.getElementById('blogsShowData');
    if (!dataEl) return;
    try { data = JSON.parse(dataEl.textContent); } catch (e) { /* keep defaults */ }
  })();

  /* ── Reading progress ── */
  function updateProgress() {
    const body = document.getElementById('article-body');
    if (!body) return;
    const winH = window.innerHeight;
    const bodyRect = body.getBoundingClientRect();
    const total = body.offsetHeight;
    const scrolled = -bodyRect.top;

    // Guard against division by zero / negative denominator when the
    // article is shorter than the viewport.
    const denom = total - winH;
    const pct = denom > 0
      ? Math.min(100, Math.max(0, Math.round((scrolled / denom) * 100)))
      : 100;

    document.getElementById('reading-progress-bar').style.width = pct + '%';

    const sidebar = document.getElementById('progress-bar-sidebar');
    const pctEl = document.getElementById('progress-pct');
    const timeLeft = document.getElementById('time-left');
    if (sidebar) sidebar.style.width = pct + '%';
    if (pctEl) pctEl.textContent = pct + '%';
    if (timeLeft) {
      const totalMins = data.readTimeMinutes;
      const minsLeft = Math.ceil((1 - pct / 100) * totalMins);
      timeLeft.textContent = pct >= 95 ? '✓ Finished' : minsLeft + ' min left';
    }
    updateTOC();
  }
  window.addEventListener('scroll', updateProgress, { passive: true });
  updateProgress();

  /* ── Table of Contents ── */
  function buildTOC() {
    const headings = document.querySelectorAll('#article-body h2, #article-body h3');
    const nav = document.getElementById('toc-nav');
    const card = document.getElementById('toc-card');
    if (!nav || headings.length < 2) return;

    headings.forEach((h, i) => {
      if (!h.id) h.id = 'heading-' + i;
      const a = document.createElement('a');
      a.href = '#' + h.id;
      a.textContent = h.textContent;
      a.style.paddingLeft = h.tagName === 'H3' ? '20px' : '10px';
      nav.appendChild(a);
    });
    if (card && headings.length > 1) card.style.display = 'block';
  }

  function updateTOC() {
    const headings = document.querySelectorAll('#article-body h2, #article-body h3');
    const links = document.querySelectorAll('#toc-nav a');
    if (!links.length) return;
    let active = 0;
    headings.forEach((h, i) => {
      if (h.getBoundingClientRect().top < 120) active = i;
    });
    links.forEach((l, i) => l.classList.toggle('toc-active', i === active));
  }

  buildTOC();

  /* ── Copy link ── */
  function copyLink() {
    navigator.clipboard.writeText(window.location.href).then(() => {
      const el = document.getElementById('copy-label');
      el.textContent = '✓ Copied!';
      setTimeout(() => el.textContent = 'Copy Link', 2000);
    });
  }

  /* ── Toggle reply form ── */
  function toggleReply(id) {
    const el = document.getElementById('reply-' + id);
    el.classList.toggle('hidden');
    if (!el.classList.contains('hidden')) {
      const input = el.querySelector('input');
      if (input) { input.focus(); }
    }
  }

  /* ── Like via AJAX ── */
  const likeForm = document.getElementById('like-form');
  if (likeForm) {
    likeForm.addEventListener('submit', async function(e) {
      e.preventDefault();
      const btn = document.getElementById('like-btn');
      const token = document.querySelector('[name=_token]').value;

      try {
        const res = await fetch(likeForm.action, {
          method: 'POST',
          headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json', 'Content-Type': 'application/json' },
          body: JSON.stringify({})
        });
        if (!res.ok) return;
        const data = await res.json();
        const liked = data.liked;

        document.getElementById('heart-icon').textContent = liked ? '♥' : '♡';
        document.getElementById('like-count').textContent = data.likes_count.toLocaleString();
        btn.lastElementChild.textContent = liked ? 'Liked' : 'Like';

        const floatLike = document.getElementById('float-like');
        const floatCount = document.getElementById('float-count');
        if (floatLike) {
          floatLike.classList.toggle('liked', liked);
          floatLike.querySelector('span').textContent = liked ? '♥' : '♡';
        }
        if (floatCount) floatCount.textContent = data.likes_count.toLocaleString();

        if (liked) {
          btn.classList.remove('border-stone-200', 'text-stone-600', 'hover:border-rose-300', 'hover:bg-rose-50', 'hover:text-rose-500');
          btn.classList.add('border-rose-300', 'bg-rose-50', 'text-rose-600');
        } else {
          btn.classList.remove('border-rose-300', 'bg-rose-50', 'text-rose-600');
          btn.classList.add('border-stone-200', 'text-stone-600', 'hover:border-rose-300', 'hover:bg-rose-50', 'hover:text-rose-500');
        }
      } catch(err) { likeForm.submit(); }
    });
  }

  /* ── data-action delegation (replaces inline onclick) ── */
  document.addEventListener('click', function (e) {
    const el = e.target.closest('[data-action]');
    if (!el) return;
    const action = el.getAttribute('data-action');
    if (action === 'toggle-reply') {
      toggleReply(el.getAttribute('data-id'));
    } else if (action === 'report-modal-backdrop') {
      if (e.target === el) el.classList.add('hidden');
    }
    /* action === 'report-modal-inner': no-op (prevents backdrop handler) */
  });

})();