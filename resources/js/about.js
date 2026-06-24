/**
 * ui.js — Scroll Reveal · Animated Counters · Scroll-to-Top · FAQ Accordion
 *
 * Sections:
 *  1. Utilities
 *  2. Scroll Reveal
 *  3. Animated Counters
 *  4. Scroll-to-Top Button
 *  5. FAQ Accordion
 *  6. Bootstrap
 */

'use strict';

(function () {

  /* ═══════════════════════════════════════════════════════════
     1. UTILITIES
  ═══════════════════════════════════════════════════════════ */

  /** Shorthand selectors */
  const $  = (sel, ctx = document) => ctx.querySelector(sel);
  const $$ = (sel, ctx = document) => ctx.querySelectorAll(sel);

  /** True when the OS requests no animation */
  const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /**
   * rAF-based counter with ease-out cubic.
   * More accurate than setInterval — frame rate adapts to the device.
   *
   * @param {HTMLElement} el
   * @param {number}      target    - Final integer value
   * @param {string|null} format    - 'crore' or null
   * @param {string}      suffix    - e.g. '+', '%'
   * @param {number}      [dur=1200] - Duration in ms
   */
  function runCounter(el, target, format, suffix, dur = 1200) {
    if (reducedMotion) {
      el.textContent = formatValue(target, target, format, suffix);
      return;
    }

    const start = performance.now();

    function step(now) {
      const p       = Math.min((now - start) / dur, 1);
      const eased   = 1 - Math.pow(1 - p, 3);           // ease-out cubic
      const current = Math.floor(eased * target);

      el.textContent = formatValue(current, target, format, suffix);

      if (p < 1) {
        requestAnimationFrame(step);
      } else {
        // Snap to exact final value (avoids floating-point drift)
        el.textContent = formatValue(target, target, format, suffix);
      }
    }

    requestAnimationFrame(step);
  }

  /**
   * Formats a counter value according to its display mode.
   *
   * @param {number}      value   - Current animated value
   * @param {number}      target  - Final target (used for hard-coded crore label)
   * @param {string|null} format
   * @param {string}      suffix
   */
  function formatValue(value, target, format, suffix) {
    if (format === 'crore') {
      return value < target
        ? '₹' + (value / 10_000_000).toFixed(1) + ' Cr'
        : '₹10 Cr+';                                    // final label override
    }
    return value.toLocaleString('en-IN') + suffix;
  }


  /* ═══════════════════════════════════════════════════════════
     2. SCROLL REVEAL
  ═══════════════════════════════════════════════════════════ */
  function initScrollReveal() {
    const els = $$('.reveal, .reveal-left, .reveal-right, .reveal-scale');
    if (!els.length) return;

    // Skip animation entirely when reduced motion is requested
    if (reducedMotion) {
      els.forEach(el => el.classList.add('visible'));
      return;
    }

    const obs = new IntersectionObserver((entries) => {
      entries.forEach(({ isIntersecting, target }) => {
        if (!isIntersecting) return;
        target.classList.add('visible');
        obs.unobserve(target);              // fire once, then stop watching
      });
    }, {
      threshold:  0.1,
      rootMargin: '0px 0px -40px 0px',
    });

    els.forEach(el => obs.observe(el));
  }


  /* ═══════════════════════════════════════════════════════════
     3. ANIMATED COUNTERS
  ═══════════════════════════════════════════════════════════ */
  function initCounters() {
    const els = $$('.counter[data-target]');
    if (!els.length) return;

    const obs = new IntersectionObserver((entries) => {
      entries.forEach(({ isIntersecting, target: el }) => {
        if (!isIntersecting) return;

        const target = parseInt(el.dataset.target, 10);
        if (!Number.isFinite(target)) return;

        const format = el.dataset.format ?? null;
        const suffix = el.dataset.suffix ?? '';

        runCounter(el, target, format, suffix);
        obs.unobserve(el);                  // animate once per page load
      });
    }, { threshold: 0.5 });

    els.forEach(el => obs.observe(el));
  }


  /* ═══════════════════════════════════════════════════════════
     4. SCROLL-TO-TOP BUTTON
  ═══════════════════════════════════════════════════════════ */
  function initScrollTop() {
    const btn = $('#scrollTopBtn');
    if (!btn) return;

    const THRESHOLD = 600;

    // Throttle scroll handler with rAF — avoids layout thrashing on
    // rapid scroll events (especially on mobile).
    let ticking = false;

    function onScroll() {
      if (ticking) return;
      ticking = true;
      requestAnimationFrame(() => {
        btn.classList.toggle('visible', window.scrollY > THRESHOLD);
        ticking = false;
      });
    }

    window.addEventListener('scroll', onScroll, { passive: true });

    // Smooth scroll to top; instant fallback for reduced-motion users
    btn.addEventListener('click', () => {
      window.scrollTo({
        top:      0,
        behavior: reducedMotion ? 'instant' : 'smooth',
      });
    });
  }


  /* ═══════════════════════════════════════════════════════════
     5. FAQ ACCORDION
     Animates panel height via scrollHeight so CSS transitions
     actually fire. Event-delegated. ARIA-compliant.
     Keeps backward compatibility with toggleFaq(idx) global.
  ═══════════════════════════════════════════════════════════ */
  function initFAQ() {
    const root = $('.faq-list') ?? document;
    const ctx  = root instanceof Document ? document : root;

    /* ── Panel helpers ─────────────────────────────────────── */

    /**
     * Expand a panel.
     * Sets height: 0 → scrollHeight so the CSS transition runs.
     * After the transition ends, height is set to 'auto' so the
     * panel reflows correctly if the viewport or content changes.
     */
    function openItem(item) {
      const panel = item.querySelector('.faq-a');
      const btn   = item.querySelector('.faq-q');
      if (!panel) return;

      item.classList.add('open');
      btn?.setAttribute('aria-expanded', 'true');

      if (reducedMotion) {
        panel.style.height = 'auto';
        return;
      }

      // Start from 0, animate to real content height
      panel.style.height = '0';
      requestAnimationFrame(() => {
        panel.style.height = panel.scrollHeight + 'px';
        panel.addEventListener(
          'transitionend',
          () => { panel.style.height = 'auto'; },
          { once: true }
        );
      });
    }

    /**
     * Collapse a panel.
     * Pins height to its current pixel value first so the
     * browser has a concrete starting point to animate from.
     */
    function closeItem(item) {
      const panel = item.querySelector('.faq-a');
      const btn   = item.querySelector('.faq-q');
      if (!panel) return;

      btn?.setAttribute('aria-expanded', 'false');

      if (reducedMotion) {
        panel.style.height = '0';
        item.classList.remove('open');
        return;
      }

      // Pin to current rendered height, then animate to 0
      panel.style.height = panel.scrollHeight + 'px';
      requestAnimationFrame(() => {
        panel.style.height = '0';
        panel.addEventListener(
          'transitionend',
          () => { item.classList.remove('open'); },
          { once: true }
        );
      });
    }

    /* ── Toggle ─────────────────────────────────────────────── */

    function toggleItem(item) {
      if (!item) return;
      const isOpen = item.classList.contains('open');

      // Collapse all other open siblings first
      ctx.querySelectorAll('.faq-item.open').forEach(open => {
        if (open !== item) closeItem(open);
      });

      isOpen ? closeItem(item) : openItem(item);
    }

    /* ── Legacy index-based toggle (for inline onclick attrs) ── */

    function toggleByIdx(idx) {
      const item = ctx.querySelector(`[data-faq="${idx}"]`);
      toggleItem(item);
    }

    /* ── Event delegation ───────────────────────────────────── */

    ctx.addEventListener('click', (e) => {
      const btn  = e.target.closest('.faq-q');
      const item = btn?.closest('.faq-item');
      if (item) toggleItem(item);
    });

    ctx.addEventListener('keydown', (e) => {
      if (e.key !== 'Enter' && e.key !== ' ') return;
      const btn = e.target.closest('.faq-q');
      if (btn) { e.preventDefault(); btn.click(); }
    });

    /* ── Initial ARIA + height state ────────────────────────── */

    ctx.querySelectorAll('.faq-item').forEach(item => {
      const btn   = item.querySelector('.faq-q');
      const panel = item.querySelector('.faq-a');
      if (!btn || !panel) return;

      const isOpen = item.classList.contains('open');
      btn.setAttribute('aria-expanded', String(isOpen));

      // Ensure closed panels start at height: 0
      // (catches cases where CSS hasn't loaded yet)
      if (!isOpen) panel.style.height = '0';
    });

    /* ── Legacy global shim ─────────────────────────────────── */
    // Keeps existing inline onclick="toggleFaq(idx)" HTML working.
    // Remove once HTML is updated to use data-driven handlers.
    window.toggleFaq = toggleByIdx;
  }


  /* ═══════════════════════════════════════════════════════════
     6. BOOTSTRAP
  ═══════════════════════════════════════════════════════════ */
  function init() {
    initScrollReveal();
    initCounters();
    initScrollTop();
    initFAQ();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();   // script is deferred or DOM already parsed
  }

})();