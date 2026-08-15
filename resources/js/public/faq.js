document.addEventListener('DOMContentLoaded', function () {
    document.documentElement.classList.add('js-enabled');

    /* ── FAQ accordion (max-height based) ── */
    document.addEventListener('click', function (e) {
        var btn = e.target.closest('[data-action="toggle-faq"]');
        if (!btn) return;
        var item = btn.closest('.faq-item');
        var answer = item.querySelector('.faq-answer');
        if (!item || !answer) return;
        var isOpen = answer.style.maxHeight && answer.style.maxHeight !== '0px';
        answer.style.maxHeight = isOpen ? '0px' : answer.scrollHeight + 'px';
        answer.style.paddingBottom = isOpen ? '0px' : '20px';
        answer.style.opacity = isOpen ? '0' : '1';
        item.classList.toggle('faq-open');
    });

    /* ── Scroll Reveal ── */
    var revEls = document.querySelectorAll('.reveal');
    if (revEls.length) {
        var obs = new IntersectionObserver(function (entries) {
            entries.forEach(function (e) {
                if (e.isIntersecting) {
                    e.target.classList.add('visible');
                    obs.unobserve(e.target);
                }
            });
        }, { threshold: 0.08, rootMargin: '0px 0px -28px 0px' });
        revEls.forEach(function (el) { obs.observe(el); });
    }
});
