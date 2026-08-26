(function () {
    'use strict';

    var overlay = document.getElementById('deleteOverlay');
    if (!overlay) return;

    document.addEventListener('click', function (e) {
        var open = e.target.closest('[data-action="open-delete"]');
        if (open) { overlay.classList.add('open'); return; }
        var close = e.target.closest('[data-action="close-delete"]');
        if (close) { overlay.classList.remove('open'); }
    });

    overlay.addEventListener('click', function (e) {
        if (e.target === this) this.classList.remove('open');
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') overlay.classList.remove('open');
    });
})();
