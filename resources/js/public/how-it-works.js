document.addEventListener('DOMContentLoaded', function () {
    document.documentElement.classList.add('js-enabled');

    /* ── Scroll Reveal ── */
    var revEls = document.querySelectorAll('.reveal,.reveal-left,.reveal-right');
    if (revEls.length) {
        var obs = new IntersectionObserver(function(entries) {
            entries.forEach(function(e) {
                /* Reveal when entering the viewport OR when already scrolled
                   past (top above viewport — happens with scroll restoration,
                   deep links like #fundraisers, or fast jumps), otherwise such
                   elements would stay invisible forever. Zero-rect elements
                   (hidden tab panes) keep waiting for switchTab(). */
                if (e.isIntersecting || e.boundingClientRect.top < 0) {
                    e.target.classList.add('visible');
                    obs.unobserve(e.target);
                }
            });
        }, { threshold: 0.08, rootMargin: '0px 0px -28px 0px' });
        revEls.forEach(function(el) { obs.observe(el); });
    }

    /* ── Scroll to top ── */
    var sBtn = document.getElementById('scrollTopBtn');
    if (sBtn) {
        window.addEventListener('scroll', function() {
            sBtn.classList.toggle('visible', window.scrollY > 600);
        }, { passive: true });
    }

    /* ── Set tab from URL hash ── */
    if (window.location.hash === '#fundraisers') switchTab('fundraisers');
});

/* ── Main tab switch (Donors / Fundraisers) ── */
function switchTab(tab) {
    ['donors', 'fundraisers'].forEach(function(t) {
        var bt = document.getElementById('tab-' + t);
        var pn = document.getElementById('pane-' + t);
        if (bt) bt.classList.toggle('active', t === tab);
        if (pn) pn.classList.toggle('active', t === tab);
    });
    history.replaceState(null, '', tab === 'donors' ? '#donors' : '#fundraisers');
    setTimeout(function() {
        var pane = document.getElementById('pane-' + tab);
        if (!pane) return;
        pane.querySelectorAll('.reveal, .reveal-left, .reveal-right').forEach(function(el) {
            el.classList.add('visible');
        });
    }, 50);
}

/* ── FAQ tab switch ── */
function switchFaqTab(tab) {
    ['donors', 'fundraisers'].forEach(function(t) {
        var bt = document.getElementById('faq-tab-' + t);
        var pn = document.getElementById('faq-pane-' + t);
        if (bt) bt.classList.toggle('active', t === tab);
        if (pn) pn.style.display = t === tab ? 'block' : 'none';
    });
    var pane = document.getElementById('faq-pane-' + tab);
    if (pane) {
        pane.querySelectorAll('.faq-item.reveal').forEach(function(el) {
            el.classList.add('visible');
        });
    }
}

/* ── FAQ accordion ── */
function toggleFaq(id) {
    var item = document.querySelector('[data-faq="' + id + '"]');
    if (!item) return;
    var isOpen = item.classList.contains('open');
    document.querySelectorAll('.faq-item.open').forEach(function(el) { el.classList.remove('open'); });
    if (!isOpen) item.classList.add('open');
}

/* ── Event delegation for tab and FAQ buttons ── */
document.addEventListener('click', function(e) {
    var tabBtn = e.target.closest('[data-action="switch-tab"]');
    if (tabBtn) {
        switchTab(tabBtn.dataset.tab);
        return;
    }
    var faqBtn = e.target.closest('[data-action="toggle-faq"]');
    if (faqBtn) {
        toggleFaq(faqBtn.dataset.faq);
        return;
    }
    var scrollBtn = e.target.closest('[data-action="scroll-top"]');
    if (scrollBtn) {
        window.scrollTo({ top: 0, behavior: 'smooth' });
        return;
    }
    var faqTabBtn = e.target.closest('[data-action="switch-faq-tab"]');
    if (faqTabBtn) {
        switchFaqTab(faqTabBtn.dataset.faqTab);
        return;
    }
});
