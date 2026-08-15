/* ═══════════════════════════════════════════════════════════════════
   shared/modal.js — modal open/close helpers
   Works with the admin portal's .overlay.open pattern and generic
   [data-modal] elements.
   ═══════════════════════════════════════════════════════════════════ */

export function openModal(el) {
    if (!el) return;
    el.classList.add('open');
    document.body.classList.add('modal-open');
}

export function closeModal(el) {
    if (!el) return;
    el.classList.remove('open');
}

/** Close every open modal overlay. */
export function closeAllModals() {
    document.querySelectorAll('.overlay.open').forEach((o) => o.classList.remove('open'));
    document.body.classList.remove('modal-open');
}

/** Global plumbing: Escape closes modals, clicking the backdrop closes it. */
export function initModalDefaults() {
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeAllModals();
    });
    document.querySelectorAll('.overlay').forEach((o) => {
        o.addEventListener('click', (e) => {
            if (e.target === o) closeModal(o);
        });
    });
}