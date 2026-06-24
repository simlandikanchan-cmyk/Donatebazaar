/**
 * main.js — Enhanced & Performance-Optimised
 *
 * Sections:
 *  1. Utilities
 *  2. Hero Slider
 *  3. Campaigns (Filter + Infinite Scroll)
 *  4. Counters
 *  5. Testimonial Marquee
 *  6. How It Works — Interactive Steps
 *  7. Tab Switch (global)
 *  8. Impact Section (scroll-triggered counters + bars)
 *  9. Bootstrap
 */

'use strict';

/* ═══════════════════════════════════════════════════════════
   1. UTILITIES
═══════════════════════════════════════════════════════════ */

/**
 * Lightweight wrapper — returns null instead of throwing
 * when the selector matches nothing.
 */
const $ = (sel, ctx = document) => ctx.querySelector(sel);
const $$ = (sel, ctx = document) => ctx.querySelectorAll(sel);

/**
 * requestAnimationFrame-based easing counter.
 * Shared by both the early counter init and the
 * scroll-triggered impact counter.
 *
 * @param {HTMLElement} el        - Target element
 * @param {number}      target    - End value
 * @param {number}      [dur=1400] - Duration in ms
 * @param {string}      [locale='en-IN']
 */
function animateCounter(el, target, dur = 1400, locale = 'en-IN') {
  const start = performance.now();
  function step(now) {
    const p = Math.min((now - start) / dur, 1);
    // Ease-out cubic
    const eased = 1 - Math.pow(1 - p, 3);
    el.textContent = Math.round(eased * target).toLocaleString(locale);
    if (p < 1) requestAnimationFrame(step);
  }
  requestAnimationFrame(step);
}

/**
 * Returns true when the user prefers reduced motion.
 * Used to skip non-essential animations gracefully.
 */
const prefersReducedMotion = () =>
  window.matchMedia('(prefers-reduced-motion: reduce)').matches;


/* ═══════════════════════════════════════════════════════════
   2. HERO SLIDER
═══════════════════════════════════════════════════════════ */
function initHeroSlider() {
  const slides = $$('.hero-slide');
  const dots   = $$('.hero-dot');
  if (!slides.length) return;

  let current   = 0;
  let autoTimer = null;
  const INTERVAL = 4500;

  function showSlide(idx) {
    slides.forEach((s, i) => {
      const active = i === idx;
      s.classList.toggle('active', active);
      // Improve a11y: hide inactive slides from assistive tech
      s.setAttribute('aria-hidden', String(!active));
    });
    dots.forEach((d, i) => d?.classList.toggle('active', i === idx));
  }

  const advance = (dir) => {
    current = (current + dir + slides.length) % slides.length;
    showSlide(current);
  };

  function startAuto() {
    stopAuto();
    if (!prefersReducedMotion()) {
      autoTimer = setInterval(() => advance(1), INTERVAL);
    }
  }

  function stopAuto() {
    clearInterval(autoTimer);
    autoTimer = null;
  }

  // Pause on hover / focus — better UX
  const heroEl = $('.hero');
  heroEl?.addEventListener('mouseenter', stopAuto);
  heroEl?.addEventListener('mouseleave', startAuto);
  heroEl?.addEventListener('focusin',    stopAuto);
  heroEl?.addEventListener('focusout',   startAuto);

  // Navigation buttons
  $('#heroNext')?.addEventListener('click', () => { stopAuto(); advance(1);  startAuto(); });
  $('#heroPrev')?.addEventListener('click', () => { stopAuto(); advance(-1); startAuto(); });

  // Dot navigation
  dots.forEach((d, i) => {
    d.addEventListener('click', () => {
      stopAuto();
      current = i;
      showSlide(i);
      startAuto();
    });
  });

  // Keyboard support on dot list
  dots.forEach((d) => {
    d.setAttribute('role', 'button');
    d.setAttribute('tabindex', '0');
    d.addEventListener('keydown', (e) => {
      if (e.key === 'Enter' || e.key === ' ') d.click();
    });
  });

  // Touch/swipe support
  let touchStartX = 0;
  heroEl?.addEventListener('touchstart', (e) => {
    touchStartX = e.changedTouches[0].screenX;
  }, { passive: true });
  heroEl?.addEventListener('touchend', (e) => {
    const dx = e.changedTouches[0].screenX - touchStartX;
    if (Math.abs(dx) > 40) { stopAuto(); advance(dx < 0 ? 1 : -1); startAuto(); }
  }, { passive: true });

  showSlide(0);
  startAuto();
}


/* ═══════════════════════════════════════════════════════════
   3. CAMPAIGNS — FILTER + INFINITE SCROLL
═══════════════════════════════════════════════════════════ */
function initCampaigns() {
  const allCards   = Array.from($$('.camp-card'));
  if (!allCards.length) return;

  const loader    = $('#infiniteLoader');
  const spinner   = $('#loaderSpinner');
  const loaderTxt = $('#loaderText');
  const campEmpty = $('#campEmpty');
  const container = $('#campaignContainer');

  const BATCH = 6;
  let loading      = false;
  let visibleCount = 0;
  let filteredCards = [];

  // Lazy-load spinner hide/show via CSS class for cleaner separation
  function setLoading(on) {
    loading = on;
    spinner?.classList.toggle('hidden', !on);
    if (loaderTxt) loaderTxt.textContent = on ? 'Loading…' : 'Scroll to load more';
  }

  function showNextBatch() {
    if (loading || visibleCount >= filteredCards.length) return;
    setLoading(true);

    // Use rAF + minimal timeout: avoids layout thrashing on batch reveal
    setTimeout(() => {
      const frag = document.createDocumentFragment();
      const end  = Math.min(visibleCount + BATCH, filteredCards.length);

      for (let i = visibleCount; i < end; i++) {
        filteredCards[i].classList.remove('hidden');
      }

      visibleCount = end;
      setLoading(false);

      if (visibleCount >= filteredCards.length) {
        if (loaderTxt) loaderTxt.textContent = 'All campaigns loaded';
        if (loader)    loader.style.opacity  = '0.5';
        scrollObserver.disconnect();
      }
    }, 300);
  }

  const scrollObserver = new IntersectionObserver(
    (entries) => { if (entries[0].isIntersecting) showNextBatch(); },
    { rootMargin: '200px' }
  );

  function applyFilter(cat) {
    // Batch DOM writes — hide all, then reveal filtered
    allCards.forEach(c => c.classList.add('hidden'));

    filteredCards = cat === 'all'
      ? allCards
      : allCards.filter(c => c.dataset.cat === cat);

    visibleCount = 0;
    loading      = false;
    scrollObserver.disconnect();

    const empty = filteredCards.length === 0;
    campEmpty?.style.setProperty('display', empty ? 'block' : 'none');

    if (empty) {
      if (loader) loader.style.display = 'none';
      return;
    }

    // Reveal first batch immediately (no timeout needed here)
    const firstEnd = Math.min(BATCH, filteredCards.length);
    for (let i = 0; i < firstEnd; i++) filteredCards[i].classList.remove('hidden');
    visibleCount = firstEnd;

    if (filteredCards.length > BATCH) {
      if (loader) { loader.style.display = ''; loader.style.opacity = '1'; }
      if (loaderTxt) loaderTxt.textContent = 'Scroll to load more';
      scrollObserver.observe(loader);
    } else {
      if (loader) loader.style.display = 'none';
    }
  }

  // Filter button clicks — delegate to parent for better perf
  const filterParent = $('.camp-filter-btns') ?? document;
  filterParent.addEventListener('click', (e) => {
    const btn = e.target.closest('.camp-filter-btn');
    if (!btn) return;

    $$('.camp-filter-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    applyFilter(btn.dataset.cat);
    container?.scrollIntoView({ behavior: 'smooth', block: 'start' });
  });

  applyFilter('all');
}


/* ═══════════════════════════════════════════════════════════
   4. INLINE COUNTERS (immediate — no scroll trigger)
   For counters outside the impact section.
═══════════════════════════════════════════════════════════ */
function initInlineCounters() {
  $$('.counter[data-target]').forEach(el => {
    const target = parseInt(el.dataset.target, 10);
    if (!Number.isFinite(target)) return;
    if (prefersReducedMotion()) {
      el.textContent = target.toLocaleString('en-IN');
    } else {
      animateCounter(el, target);
    }
  });
}


/* ═══════════════════════════════════════════════════════════
   5. TESTIMONIAL MARQUEE
   Uses a single rAF loop for all tracks — no setInterval.
   Clamps delta to avoid jump on tab restore.
═══════════════════════════════════════════════════════════ */
function initTestimonialMarquee() {
  const tracks = $$('.testi-track');
  if (!tracks.length || prefersReducedMotion()) return;

  const SPEED  = 60; // px/s
  const MAX_DT = 0.1; // cap at 100 ms to avoid large jumps

  // Per-slider state stored on the element to avoid closure arrays
  $$('.testi-slider').forEach(s => { s._x = 0; s._paused = false; });

  tracks.forEach(track => {
    track.addEventListener('mouseenter', () => {
      $$('.testi-slider', track).forEach(s => { s._paused = true; });
    }, { passive: true });
    track.addEventListener('mouseleave', () => {
      $$('.testi-slider', track).forEach(s => { s._paused = false; });
    }, { passive: true });
  });

  let lastTime = null;

  function tick(now) {
    if (document.hidden) { lastTime = null; requestAnimationFrame(tick); return; }

    const dt = lastTime ? Math.min((now - lastTime) / 1000, MAX_DT) : 0;
    lastTime = now;

    $$('.testi-slider').forEach(slider => {
      if (slider._paused) return;

      // Skip if tab is hidden
      const tab = slider.closest('.testi-tab');
      if (tab && getComputedStyle(tab).display === 'none') return;

      const first = slider.firstElementChild;
      if (!first) return;

      // Cache width on the node to avoid repeated reflows
      if (!first._cachedW) {
        first._cachedW = first.offsetWidth + 18; // 18 = gap
      }

      let x = slider._x - SPEED * dt;

      if (Math.abs(x) >= first._cachedW) {
        slider.appendChild(first);
        // Invalidate cache for the new first child
        if (slider.firstElementChild) delete slider.firstElementChild._cachedW;
        x += first._cachedW;
      }

      slider.style.transform = `translateX(${x}px)`;
      slider._x = x;
    });

    requestAnimationFrame(tick);
  }

  requestAnimationFrame(tick);
}


/* ═══════════════════════════════════════════════════════════
   6. HOW IT WORKS — INTERACTIVE STEPS
═══════════════════════════════════════════════════════════ */
function initHowSteps(stepsId, progId, activeClass) {
  const container = $(`#${stepsId}`);
  const prog      = $(`#${progId}`);
  if (!container || !prog) return;

  const steps = $$('.how-step', container);
  const pdots = $$('.how-prog-dot', prog);
  if (!steps.length) return;

  function setActive(idx) {
    steps.forEach((s, i) => s.classList.toggle('active', i === idx));
    pdots.forEach((d, i) => {
      d.classList.remove('on-orange', 'on-indigo');
      if (i <= idx) d.classList.add(activeClass);
    });
  }

  setActive(0);

  // Event delegation on container — one listener instead of N*2
  container.addEventListener('click', (e) => {
    const step = e.target.closest('.how-step');
    if (!step) return;
    setActive(Array.from(steps).indexOf(step));
  });

  container.addEventListener('mouseover', (e) => {
    const step = e.target.closest('.how-step');
    if (!step) return;
    setActive(Array.from(steps).indexOf(step));
  });
}


/* ═══════════════════════════════════════════════════════════
   7. TAB SWITCH (exposed globally for inline onclick attrs)
═══════════════════════════════════════════════════════════ */
function switchTab(tabId, btn) {
  $$('.testi-tab').forEach(el => el.classList.remove('active'));
  $(`#${tabId}`)?.classList.add('active');

  $$('.tab-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
}

// Make it available on window for inline HTML handlers
window.switchTab = switchTab;


/* ═══════════════════════════════════════════════════════════
   8. IMPACT SECTION — SCROLL-TRIGGERED COUNTERS + BARS
   Single IntersectionObserver, fires once, then disconnects.
═══════════════════════════════════════════════════════════ */
function initImpactSection() {
  const section = $('.impact-section');
  if (!section) return;

  function runAnimations() {
    $$('.counter', section).forEach(el => {
      const target = parseInt(el.dataset.target, 10);
      if (!Number.isFinite(target)) return;

      if (prefersReducedMotion()) {
        el.textContent = target.toLocaleString('en-IN');
      } else {
        animateCounter(el, target, 1400);
      }
    });

    $$('.impact-bar', section).forEach(bar => {
      const w = parseFloat(bar.dataset.width);
      if (!Number.isFinite(w)) return;
      // rAF ensures the browser has already painted the initial 0-width state
      requestAnimationFrame(() => {
        bar.style.width = `${w}%`;
      });
    });
  }

  if (prefersReducedMotion()) {
    // No scroll check needed — just apply final states
    runAnimations();
    return;
  }

  const obs = new IntersectionObserver((entries) => {
    if (entries[0].isIntersecting) {
      obs.disconnect(); // fire once
      runAnimations();
    }
  }, { threshold: 0.25 });

  obs.observe(section);
}


/* ═══════════════════════════════════════════════════════════
   9. BOOTSTRAP — wire everything up after DOM is ready
═══════════════════════════════════════════════════════════ */
function init() {
  initHeroSlider();
  initCampaigns();
  initInlineCounters();
  initTestimonialMarquee();
  initHowSteps('dsteps', 'dprog', 'on-orange');
  initHowSteps('fsteps', 'fprog', 'on-indigo');
  initImpactSection();
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', init);
} else {
  // DOM already parsed (e.g. script is deferred)
  init();
}



(function () {
    var track    = document.getElementById('hbsTrack');
    var prevBtn  = document.getElementById('hbsPrev');
    var nextBtn  = document.getElementById('hbsNext');
    var dotsWrap = document.getElementById('hbsDots');
    if (!track) return;
 
    var cards     = track.querySelectorAll('.hbs-card');
    var total     = cards.length;
    var current   = 0;
    var autoTimer = null;
 
    function perView() {
        var w = track.parentElement.offsetWidth;
        if (w < 580) return 1;
        if (w < 900) return 2;
        return 3;
    }
    function maxIndex() { return Math.max(0, total - perView()); }
 
    function buildDots() {
        dotsWrap.innerHTML = '';
        for (var i = 0; i <= maxIndex(); i++) {
            var d = document.createElement('button');
            d.className = 'hbs-dot' + (i === current ? ' active' : '');
            d.setAttribute('aria-label', 'Slide ' + (i + 1));
            d.dataset.i = i;
            d.addEventListener('click', function () { goTo(+this.dataset.i); resetAuto(); });
            dotsWrap.appendChild(d);
        }
    }
    function updateDots() {
        dotsWrap.querySelectorAll('.hbs-dot').forEach(function (d, i) {
            d.classList.toggle('active', i === current);
        });
    }
    function goTo(idx) {
        current = Math.max(0, Math.min(idx, maxIndex()));
        track.style.transform = 'translateX(-' + current * (cards[0].offsetWidth + 20) + 'px)';
        prevBtn.disabled = current === 0;
        nextBtn.disabled = current >= maxIndex();
        updateDots();
    }
 
    prevBtn.addEventListener('click', function () { goTo(current - 1); resetAuto(); });
    nextBtn.addEventListener('click', function () { goTo(current + 1); resetAuto(); });
 
    var startX = 0;
    track.addEventListener('touchstart', function (e) { startX = e.touches[0].clientX; }, { passive: true });
    track.addEventListener('touchend',   function (e) {
        var diff = startX - e.changedTouches[0].clientX;
        if (Math.abs(diff) > 40) { goTo(current + (diff > 0 ? 1 : -1)); resetAuto(); }
    }, { passive: true });
 
    function startAuto() { autoTimer = setInterval(function () { goTo(current >= maxIndex() ? 0 : current + 1); }, 5000); }
    function resetAuto() { clearInterval(autoTimer); startAuto(); }
 
    function init() { current = 0; buildDots(); goTo(0); }
    init(); startAuto();
 
    var rt;
    window.addEventListener('resize', function () { clearTimeout(rt); rt = setTimeout(init, 160); });
})();