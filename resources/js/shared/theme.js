/* ═══════════════════════════════════════════════════════════════════
   shared/theme.js — theme toggle helpers
   Supports the public/user pattern (localStorage 'theme' key) and the
   admin pattern ('adminTheme' key, dispatches 'themechange' event for
   Chart.js re-render).
   ═══════════════════════════════════════════════════════════════════ */

/**
 * Initialise theme toggle from localStorage.
 * @param {object} [opts]
 * @param {string} [opts.storageKey='theme']   — localStorage key
 * @param {boolean} [opts.dispatchEvent=false] — dispatch 'themechange' on change
 */
export function initThemeToggle(opts = {}) {
    const storageKey = opts.storageKey || 'theme';
    const html = document.documentElement;
    const toggle = document.getElementById('themeToggle');

    const saved = (localStorage.getItem(storageKey) || '').trim();
    if (saved === 'dark') {
        html.setAttribute('data-theme', 'dark');
        if (toggle) toggle.checked = true;
    }

    if (toggle) {
        toggle.addEventListener('change', function () {
            const t = this.checked ? 'dark' : 'light';
            html.setAttribute('data-theme', t);
            try { localStorage.setItem(storageKey, t); } catch (e) {}
            if (opts.dispatchEvent) {
                window.dispatchEvent(new Event('themechange'));
            }
        });
    }
}
