(function () {
    'use strict';

    var _t;
    var form = document.getElementById('filterForm');
    if (!form) return;

    var searchInput = form.querySelector('input[name="search"]');
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            clearTimeout(_t);
            _t = setTimeout(function () { form.submit(); }, 400);
        });
    }
})();
