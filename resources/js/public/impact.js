'use strict';
(function(){
  const $ = (sel, ctx) => (ctx||document).querySelector(sel);
  const $$ = (sel, ctx) => (ctx||document).querySelectorAll(sel);
  const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* ── Scroll Reveal ── */
  function initReveal() {
    const els = $$('.reveal');
    if (!els.length) return;
    if (reduced) { els.forEach(function(el){ el.classList.add('visible'); }); return; }
    var obs = new IntersectionObserver(function(entries) {
      entries.forEach(function(entry) {
        if (!entry.isIntersecting) return;
        entry.target.classList.add('visible');
        obs.unobserve(entry.target);
      });
    }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });
    els.forEach(function(el) { obs.observe(el); });
  }

  /* ── Counter Animation ── */
  function animateCounter(el, target, dur) {
    dur = dur || 1400;
    var start = performance.now();
    function step(now) {
      var p = Math.min((now - start) / dur, 1);
      var eased = 1 - Math.pow(1 - p, 3);
      var prefix = el.dataset.prefix || '';
      var suffix = el.dataset.suffix || '';
      el.textContent = prefix + Math.round(eased * target).toLocaleString('en-IN') + suffix;
      if (p < 1) requestAnimationFrame(step);
    }
    requestAnimationFrame(step);
  }

  /* ── Init Stats Counters ── */
  function initCounters() {
    var stats = $('.impact-stats-inner');
    if (!stats) return;
    var els = $$('.counter', stats);
    if (!els.length) return;
    if (reduced) {
      els.forEach(function(el) {
        var t = parseInt(el.dataset.target, 10);
        if (!Number.isFinite(t)) return;
        var prefix = el.dataset.prefix || '';
        var suffix = el.dataset.suffix || '';
        el.textContent = prefix + t.toLocaleString('en-IN') + suffix;
      });
      return;
    }
    var obs = new IntersectionObserver(function(entries) {
      if (entries[0].isIntersecting) {
        obs.disconnect();
        els.forEach(function(el) {
          var t = parseInt(el.dataset.target, 10);
          if (Number.isFinite(t)) animateCounter(el, t);
        });
      }
    }, { threshold: 0.3 });
    obs.observe(stats);
  }

  /* ── Scroll-to-top ── */
  function initScrollTop() {
    var btn = $('#scrollTopBtn');
    if (!btn) return;
    var ticking = false;
    function toggle() {
      btn.classList.toggle('visible', window.scrollY > 500);
      ticking = false;
    }
    window.addEventListener('scroll', function() {
      if (!ticking) { ticking = true; requestAnimationFrame(toggle); }
    }, { passive: true });
    btn.addEventListener('click', function() {
      window.scrollTo({ top: 0, behavior: reduced ? 'instant' : 'smooth' });
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
      initReveal(); initCounters(); initScrollTop();
    });
  } else {
    initReveal(); initCounters(); initScrollTop();
  }
})();