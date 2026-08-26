(function () {
    'use strict';

    document.addEventListener('submit', function (event) {
        var form = event.target.closest('form[data-action="reject-reason"]');
        if (!form) return;
        var btn = form.querySelector('button[data-id]');
        if (!btn) return;
        var id = btn.dataset.id;
        var reason = prompt('Rejection reason (required):');
        if (!reason || !reason.trim()) {
            event.preventDefault();
            return;
        }
        var input = document.getElementById('reject_reason_' + id);
        if (input) input.value = reason;
    });
})();
