/* ═══════════════════════════════════════════════════════════════════
   shared/form-handler.js — shared form submission helpers
   ═══════════════════════════════════════════════════════════════════ */

/**
 * Disable submit button and show loading text.
 * Used by forms that call: onsubmit="return handleSub(this,'Approving…')"
 * @param {HTMLFormElement} form
 * @param {string} txt
 */
export function handleSub(form, txt) {
    form.querySelectorAll('button[type=submit]').forEach(function (b) {
        b.disabled = true;
        b.textContent = txt;
    });
    return true;
}
