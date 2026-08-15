/* ═══════════════════════════════════════════════════════════════════
   shared/confirmation.js — confirmation dialog helpers
   ═══════════════════════════════════════════════════════════════════ */

/**
 * Ask the user to confirm an action.
 * @param {string} message
 * @returns {boolean}
 */
export function confirmAction(message) {
    return window.confirm(message);
}

/**
 * Confirm before submitting a form.
 * Bind with: data-action="confirm-submit" data-confirm="Delete this item?"
 * Returns true if the confirmation was accepted.
 */
export function confirmSubmit(form, message) {
    return window.confirm(message || form.dataset.confirm || 'Are you sure?');
}