/* ═══════════════════════════════════════════════════════════════════
   Admin Wallets Index page — extracted from inline <script>.
   Search input debounce form submission.
   Filter tab navigation handled by admin.js (data-action="navigate").
   ═══════════════════════════════════════════════════════════════════ */

(function () {
  'use strict';

  var searchInput = document.querySelector('input[name="q"]');
  if (searchInput) {
    var st;
    searchInput.addEventListener('input', function () {
      clearTimeout(st);
      st = setTimeout(function () {
        var form = searchInput.closest('form');
        if (form) form.submit();
      }, 400);
    });
  }
})();
