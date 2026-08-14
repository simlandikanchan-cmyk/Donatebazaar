(function () {
    var btn = document.getElementById('themeToggle');
    if (!btn) return;
    btn.addEventListener('click', function () {
        var root = document.documentElement;
        var next = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
        root.setAttribute('data-theme', next);
        try { localStorage.setItem('theme', next); } catch (e) {}
    });
})();

window.togglePwd = function (fieldId, btn) {
    const input = document.getElementById(fieldId);
    const isText = input.type === 'text';
    input.type = isText ? 'password' : 'text';
    btn.setAttribute('aria-label', isText ? 'Hide password' : 'Show password');
    btn.setAttribute('aria-pressed', isText ? 'true' : 'false');
    btn.innerHTML = isText
        ? `<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>`
        : `<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>`;
    btn.style.color = isText ? '#2563eb ' : '';
};

(function () {
    const pwd = document.getElementById('password');
    const confirm = document.getElementById('password_confirmation');
    if (!pwd || !confirm) return;
    const confirmWrap = confirm.closest('.field');

    let matchMsg = document.createElement('span');
    matchMsg.className = 'field-error';
    matchMsg.style.display = 'none';
    matchMsg.textContent = "Passwords don't match";
    confirmWrap.appendChild(matchMsg);

    function checkMatch() {
        if (confirm.value.length === 0) {
            matchMsg.style.display = 'none';
            confirm.style.borderColor = '';
            return;
        }
        if (pwd.value !== confirm.value) {
            matchMsg.style.display = 'block';
            confirm.style.borderColor = '#ef4444';
        } else {
            matchMsg.style.display = 'none';
            confirm.style.borderColor = '#18965d';
        }
    }

    pwd.addEventListener('input', checkMatch);
    confirm.addEventListener('input', checkMatch);
})();

document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');
    if (!form) return;
    form.addEventListener('submit', function () {
        const btn = this.querySelector('button[type="submit"]');
        if (!btn) return;
        if (btn.dataset.submitted === 'true') {
            event?.preventDefault?.();
            return;
        }
        btn.dataset.submitted = 'true';
        btn.disabled = true;
        btn.style.opacity = '0.7';
        btn.style.cursor = 'not-allowed';
    });
});
