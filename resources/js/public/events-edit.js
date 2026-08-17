/* ═══════════════════════════════════════════════════════════════════
   EVENT EDIT PAGE — moved from events/edit.blade.php inline <script>
   ═══════════════════════════════════════════════════════════════════ */

import { initThemeToggle } from '../shared/theme.js';

(function () {
  'use strict';

  initThemeToggle();

  const sidebar = document.getElementById('sidebar');
    const hamburger = document.getElementById('hamburger');
    hamburger.addEventListener('click', function () { sidebar.classList.toggle('open'); });
    document.addEventListener('click', function (e) {
        if (window.innerWidth <= 860 && !sidebar.contains(e.target) && !hamburger.contains(e.target)) {
            sidebar.classList.remove('open');
        }
    });

    /* ── Cover image preview: data-action="preview-edit-image" ── */
    document.addEventListener('change', function (e) {
        const input = e.target.closest('input[data-action="preview-edit-image"]');
        if (!input || !input.files || !input.files[0]) return;

        const reader = new FileReader();
        reader.onload = function (ev) {
            const preview = document.getElementById('editUploadPreview');
            const zone = document.getElementById('editUploadZone');
            const placeholder = document.getElementById('editUploadPlaceholder');
            preview.src = ev.target.result;
            preview.classList.add('show');
            placeholder.style.display = 'none';
            zone.classList.add('has-preview');
        };
        reader.readAsDataURL(input.files[0]);
    });
})();