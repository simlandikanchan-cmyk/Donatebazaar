(function () {
    var toastContainer = document.getElementById('toastContainer');
    if (!toastContainer) return;
    var success = toastContainer.getAttribute('data-success');
    var error = toastContainer.getAttribute('data-error');

    function showToast(message, type) {
        var t = document.createElement('div');
        t.className = 'toast toast-' + type;
        var icon = type === 'success'
            ? '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
            : '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
        t.innerHTML = icon + '<span>' + message + '</span><button class="toast-close" onclick="this.parentElement.remove()">x</button>';
        toastContainer.appendChild(t);
        setTimeout(function () { t.remove(); }, 4500);
    }

    if (success) showToast(success, 'success');
    if (error) showToast(error, 'error');
})();
