document.documentElement.classList.add('js-enabled');

document.addEventListener('click', function (e) {
    const btn = e.target.closest('[data-action="toggle-faq"]');
    if (!btn) return;
    const item = btn.closest('.faq-item');
    if (!item) return;
    const isOpen = item.classList.contains('open');
    document.querySelectorAll('.faq-item.open').forEach(el => {
        el.classList.remove('open');
    });
    if (!isOpen) {
        item.classList.add('open');
    }
});
