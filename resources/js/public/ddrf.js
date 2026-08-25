(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {

        /* ── Scroll Reveal ── */
        var revEls = document.querySelectorAll('.reveal,.reveal-left,.reveal-right');
        var obs = new IntersectionObserver(function(entries){
            entries.forEach(function(e){
                if(e.isIntersecting){ e.target.classList.add('visible'); obs.unobserve(e.target); }
            });
        },{ threshold: 0.08, rootMargin: '0px 0px -28px 0px' });
        revEls.forEach(function(el){ obs.observe(el); });

        /* ── Scroll to top ── */
        var sBtn = document.getElementById('scrollTopBtn');
        window.addEventListener('scroll', function(){
            sBtn.classList.toggle('visible', window.scrollY > 600);
        },{ passive: true });

        /* ── Animate progress bars ── */
        var bars = document.querySelectorAll('.campaign-progress-fill');
        var barObs = new IntersectionObserver(function(entries){
            entries.forEach(function(e){
                if(e.isIntersecting){
                    var target = e.target.dataset.width || e.target.style.width;
                    e.target.style.width = target;
                    barObs.unobserve(e.target);
                }
            });
        },{ threshold: 0.2 });
        bars.forEach(function(bar){
            var w = bar.style.width;
            bar.dataset.width = w;
            bar.style.width = '0';
            setTimeout(function(){ barObs.observe(bar); }, 200);
        });

        /* ── Counter animation ── */
        function animateCounter(el) {
            var originalText = el.textContent.trim();

            // Detect currency symbol
            var hasRupee = originalText.indexOf('₹') !== -1;

            // Detect plus sign
            var hasPlus = originalText.indexOf('+') !== -1;

            // Extract numeric value only
            var num = parseInt(originalText.replace(/[^\d]/g, ''), 10);

            if (isNaN(num) || num === 0) return;

            var duration = 1500;
            var startTime = null;

            function step(timestamp) {
                if (!startTime) startTime = timestamp;

                var progress = Math.min((timestamp - startTime) / duration, 1);
                var current = Math.floor(progress * num);

                var formatted = current.toLocaleString('en-IN');

                if (hasRupee) formatted = '₹' + formatted;
                if (hasPlus) formatted += '+';

                el.textContent = formatted;

                if (progress < 1) {
                    requestAnimationFrame(step);
                } else {
                    var finalText = num.toLocaleString('en-IN');
                    if (hasRupee) finalText = '₹' + finalText;
                    if (hasPlus) finalText += '+';
                    el.textContent = finalText;
                }
            }

            requestAnimationFrame(step);
        }

        /* ── Wire up counter animation on stat numbers ── */
        var statEls = document.querySelectorAll('.ddrf-stat-val, .ns-val');
        var statObs = new IntersectionObserver(function(entries){
            entries.forEach(function(e){
                if (e.isIntersecting) {
                    animateCounter(e.target);
                    statObs.unobserve(e.target);
                }
            });
        }, { threshold: 0.3 });
        statEls.forEach(function(el){ statObs.observe(el); });

    });

    /* ── Scroll to top (delegated) ── */
    document.addEventListener('click', function (e) {
        if (e.target.closest('[data-action="scroll-top"]')) {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    });

    /* ── Partner logo hover (delegated) ── */
    document.addEventListener('mouseover', function (e) {
        const img = e.target.closest('[data-action="partner-hover"]');
        if (img) img.style.filter = img.dataset.hoverFilter;
    });
    document.addEventListener('mouseout', function (e) {
        const img = e.target.closest('[data-action="partner-hover"]');
        if (img) img.style.filter = img.dataset.hoverFilterBase;
    });
})();