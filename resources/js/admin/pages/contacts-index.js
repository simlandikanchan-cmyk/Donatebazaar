(function () {
    'use strict';

    var searchInput = document.getElementById('searchInput');
    var subjectFilter = document.getElementById('subjectFilter');
    var rows = document.querySelectorAll('.contact-row');

    if (!searchInput || !subjectFilter || !rows.length) return;

    function filterTable() {
        var search = searchInput.value.toLowerCase();
        var subject = subjectFilter.value;
        rows.forEach(function (row) {
            var name = row.dataset.name;
            var email = row.dataset.email;
            var rowSubject = row.dataset.subject;
            var matchesSearch = name.includes(search) || email.includes(search);
            var matchesSubject = subject === '' || rowSubject === subject;
            row.style.display = (matchesSearch && matchesSubject) ? '' : 'none';
        });
    }

    searchInput.addEventListener('keyup', filterTable);
    subjectFilter.addEventListener('change', filterTable);
})();
