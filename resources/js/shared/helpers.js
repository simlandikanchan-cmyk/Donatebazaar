/* ═══════════════════════════════════════════════════════════════════
   shared/helpers.js — small, genuinely reusable helpers
   ═══════════════════════════════════════════════════════════════════ */

/** Escape a string for safe insertion into HTML. */
export function escapeHtml(str) {
    const div = document.createElement('div');
    div.textContent = str ?? '';
    return div.innerHTML;
}

/** Debounce a function. */
export function debounce(fn, wait = 200) {
    let timer = null;
    return function (...args) {
        clearTimeout(timer);
        timer = setTimeout(() => fn.apply(this, args), wait);
    };
}

/** Format a number with the en-IN locale. */
export function formatNumber(value) {
    const n = Number(value) || 0;
    return n.toLocaleString('en-IN');
}

/** Animate a counter from 0 to target. */
export function animateCounter(el, target, duration = 900) {
    if (!el) return;
    let startTime = null;
    function step(timestamp) {
        if (!startTime) startTime = timestamp;
        const progress = Math.min((timestamp - startTime) / duration, 1);
        const current = Math.floor((1 - Math.pow(1 - progress, 3)) * target);
        el.textContent = current.toLocaleString('en-IN');
        if (progress < 1) requestAnimationFrame(step);
    }
    requestAnimationFrame(step);
}