(function () {
    'use strict';

    window.promptFlagReason = function (form) {
        var btn = form.querySelector('button[data-id]');
        if (!btn) return false;
        var id = btn.dataset.id;
        var reason = prompt('Rejection reason (required):');
        if (!reason || !reason.trim()) return false;
        var input = document.getElementById('flag_reject_reason_' + id);
        if (input) input.value = reason;
        return true;
    };
})();
