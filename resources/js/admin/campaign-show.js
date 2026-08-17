/* ═══════════════════════════════════════════════════════════════════
   Admin Campaign Show page — moved from admin/campaign/show.blade.php
   inline <script>. Behaviors converted to data-action delegation.
   ═══════════════════════════════════════════════════════════════════ */

import { toast } from '../shared/toast.js';

(function () {
    'use strict';

    var rejectModal = document.getElementById('rejectModal');
    var lightbox = document.getElementById('lightbox');
    var lightboxImg = document.getElementById('lightboxImg');

    function openRejectModal(id) {
        document.getElementById('rejectForm').action = '/admin/campaigns/' + id + '/reject';
        rejectModal.classList.add('show');
    }

    function closeRejectModal() {
        rejectModal.classList.remove('show');
    }

    function openLightbox(src) {
        if (!src) return;
        lightboxImg.src = src;
        lightbox.classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox() {
        if (!lightbox.classList.contains('show')) return;
        lightbox.classList.remove('show');
        document.body.style.overflow = '';
    }

    /* ── delegated actions ── */
    document.addEventListener('click', function (e) {
        var el = e.target.closest('[data-action]');
        if (!el) return;
        var action = el.getAttribute('data-action');

        if (action === 'open-reject') {
            openRejectModal(el.getAttribute('data-id'));
        } else if (action === 'close-reject') {
            closeRejectModal();
        } else if (action === 'close-lightbox') {
            closeLightbox();
        } else if (action === 'copy-link') {
            var url = el.getAttribute('data-url');
            var done = function () {
                var original = el.innerHTML;
                el.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg> Copied!';
                setTimeout(function () { el.innerHTML = original; }, 1600);
                toast('Campaign link copied', 'success');
            };
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(url).then(done).catch(function () { window.prompt('Copy link:', url); });
            } else {
                window.prompt('Copy link:', url);
            }
        } else if (action === 'reveal-acc') {
            var num = document.getElementById('accNum');
            var reveal = document.getElementById('accReveal');
            if (num) num.style.filter = 'none';
            if (reveal) reveal.style.display = 'none';
        }
    });

    /* ── backdrop / keyboard ── */
    if (rejectModal) rejectModal.addEventListener('click', function (e) { if (e.target === this) closeRejectModal(); });
    if (lightbox) lightbox.addEventListener('click', function (e) { if (e.target === lightbox) closeLightbox(); });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') { closeRejectModal(); closeLightbox(); }
    });

    /* ── zoomable images ── */
    document.querySelectorAll('.cover-wrap img, .kyc-doc-tile-img, .kyc-selfie-img, .kyc-doc-preview-img').forEach(function (img) {
        img.classList.add('zoomable');
        img.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            openLightbox(img.getAttribute('src'));
        });
    });
})();