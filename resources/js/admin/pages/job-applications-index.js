(function () {
    'use strict';

    var searchEl = document.getElementById('liveSearch');
    if (!searchEl) return;

    var st;
    searchEl.addEventListener('input', function () {
        clearTimeout(st);
        var q = this.value.toLowerCase().trim();
        st = setTimeout(function () {
            document.querySelectorAll('#appTable tbody tr[data-name]').forEach(function (row) {
                row.style.display = (!q || row.dataset.name.includes(q)) ? '' : 'none';
            });
        }, 160);
    });
})();
