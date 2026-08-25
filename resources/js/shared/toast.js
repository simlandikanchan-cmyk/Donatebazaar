/* ═══════════════════════════════════════════════════════════════════
   shared/toast.js — toast notification factory
   Supports the user portal (.toast/.toast-success) and admin portal
   (.toast/.toast-ok/.toast-err/.toast-warn) markups.
   ═══════════════════════════════════════════════════════════════════ */

const ICONS = {
    success: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
    error: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
    warn: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>',
    info: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
};

function toastClassName(type) {
    switch (type) {
        case 'error': return 'toast-err';
        case 'warn': return 'toast-warn';
        case 'info': return 'toast-info';
        default: return 'toast-ok';
    }
}

/**
 * Show a toast message.
 * @param {string} msg
 * @param {'success'|'error'|'warn'} [type]
 * @param {object} [opts] - { container, duration, undo, undoLabel }
 */
export function toast(msg, type = 'success', opts = {}) {
    const container = opts.container || document.getElementById('toastContainer') || document.getElementById('toastWrap');
    if (!container) return;

    const isAdmin = container.id === 'toastWrap';
    const el = document.createElement('div');
    el.className = 'toast ' + (isAdmin ? toastClassName(type) : 'toast-' + type);

    let undoHtml = '';
    if (typeof opts.undo === 'function') {
        undoHtml = '<button class="toast-undo">' + (opts.undoLabel || 'Undo') + '</button>';
    }

    const icon = ICONS[type] || ICONS.success;
    if (isAdmin) {
        el.insertAdjacentHTML('afterbegin', icon);
        const span = document.createElement('span');
        span.textContent = msg;
        el.appendChild(span);
        if (undoHtml) {
            el.insertAdjacentHTML('beforeend', undoHtml);
        }
        const close = document.createElement('button');
        close.className = 'toast-x';
        close.textContent = '\u2715';
        el.appendChild(close);
    } else {
        el.insertAdjacentHTML('afterbegin', icon);
        const span = document.createElement('span');
        span.textContent = msg;
        el.appendChild(span);
        const close = document.createElement('button');
        close.className = 'toast-close';
        close.textContent = '\u2715';
        close.addEventListener('click', () => el.remove());
        el.appendChild(close);
    }

    container.appendChild(el);

    const duration = opts.duration || 5000;
    const timeout = setTimeout(() => {
        el.style.transition = 'opacity .3s,transform .3s';
        el.style.opacity = '0';
        el.style.transform = 'translateX(20px)';
        setTimeout(() => el.remove(), 300);
    }, duration);

    if (typeof opts.undo === 'function') {
        el.querySelector('.toast-undo').addEventListener('click', () => {
            clearTimeout(timeout);
            opts.undo();
            el.remove();
        });
    }

    const closeBtn = el.querySelector('.toast-x, .toast-close');
    if (closeBtn && isAdmin) closeBtn.addEventListener('click', () => el.remove());
}