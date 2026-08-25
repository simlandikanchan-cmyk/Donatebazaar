/* ═══════════════════════════════════════════════════════════════════
   shared/api.js — thin fetch wrapper with CSRF plumbing
   ═══════════════════════════════════════════════════════════════════ */

import { getCsrfToken } from './csrf.js';

/**
 * Fetch with automatic CSRF token and X-Requested-With headers.
 * @param {string} url
 * @param {RequestInit} [options]
 * @returns {Promise<Response>}
 */
export function csrfFetch(url, options = {}) {
    const headers = new Headers(options.headers || {});

    const csrfToken = getCsrfToken();
    if (csrfToken) {
        headers.set('X-CSRF-TOKEN', csrfToken);
    }

    if (!headers.has('X-Requested-With')) {
        headers.set('X-Requested-With', 'XMLHttpRequest');
    }

    return fetch(url, {
        ...options,
        headers,
    });
}

/**
 * Fetch JSON with automatic CSRF headers and error handling.
 * @param {string} url
 * @param {RequestInit} [options]
 * @returns {Promise<any>}
 */
export async function csrfFetchJSON(url, options = {}) {
    const response = await csrfFetch(url, {
        ...options,
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            ...(options.headers || {}),
        },
    });

    if (!response.ok) {
        const error = new Error(`HTTP ${response.status}`);
        error.status = response.status;
        try {
            error.body = await response.json();
        } catch {
            error.body = null;
        }
        throw error;
    }

    return response.json();
}
