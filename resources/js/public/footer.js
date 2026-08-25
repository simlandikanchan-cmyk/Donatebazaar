(function () {
    const stats = document.querySelectorAll('.cta-stat-num[data-count-to]');
    if (!stats.length) return;

    function animateCount(el) {
        const target = parseFloat(el.dataset.countTo);
        const prefix = el.dataset.prefix || '';
        const suffix = el.dataset.suffix || '';
        const decimals = target % 1 !== 0 ? 1 : 0;
        const duration = 1100;
        const start = performance.now();

        function frame(now) {
            const progress = Math.min((now - start) / duration, 1);
            const eased = 1 - Math.pow(1 - progress, 3);
            const value = (target * eased).toFixed(decimals);
            el.textContent = `${prefix}${value}${suffix}`;
            if (progress < 1) requestAnimationFrame(frame);
        }
        requestAnimationFrame(frame);
    }

    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (reduceMotion) {
        stats.forEach(el => {
            const target = parseFloat(el.dataset.countTo);
            el.textContent = `${el.dataset.prefix || ''}${target}${el.dataset.suffix || ''}`;
        });
        return;
    }

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateCount(entry.target);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.4 });

    stats.forEach(el => observer.observe(el));
})();

(function () {
    const form = document.getElementById('newsletter-form');
    if (!form) return;
    const input = form.querySelector('input[name="email"]');
    const button = document.getElementById('nl-submit');
    const successEl = document.getElementById('nl-success');
    const errorEl = document.getElementById('nl-error');

    form.addEventListener('submit', function (e) {
        const isValidEmail = input.checkValidity();
        if (!isValidEmail) {
            e.preventDefault();
            successEl.classList.remove('show');
            errorEl.classList.add('show');
            input.focus();
            return;
        }
        errorEl.classList.remove('show');
        button.classList.add('is-loading');
        button.disabled = true;
    });

    input.addEventListener('input', function () {
        errorEl.classList.remove('show');
    });
})();

(function () {
    const btn = document.getElementById('back-to-top');
    if (!btn) return;
    btn.addEventListener('click', function () {
        window.scrollTo({ top: 0, behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth' });
    });
})();
