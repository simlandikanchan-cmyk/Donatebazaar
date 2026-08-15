/* ═══════════════════════════════════════════════════════════════════
   shared/csrf.js — CSRF token helpers
   ═══════════════════════════════════════════════════════════════════ */

/** Read the CSRF token from the meta tag (or a form input). */
export function getCsrfToken() {
    const meta = document.querySelector('meta[name="csrf-token"]');
    if (meta) return meta.getAttribute('content') || '';

    const input = document.querySelector('input[name="_token"]');
    return input ? input.value : '';
}

/** Headers object for fetch/axios requests that need CSRF protection. */
export function csrfHeaders(extra = {}) {
    return {
        'X-CSRF-TOKEN': getCsrfToken(),
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json',
        ...extra,
    };
}