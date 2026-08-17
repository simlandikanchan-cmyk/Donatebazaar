import { toast as showToast } from '../shared/toast.js';

(function () {
    var toastContainer = document.getElementById('toastContainer');
    if (!toastContainer) return;
    var success = toastContainer.getAttribute('data-success');
    var error = toastContainer.getAttribute('data-error');

    if (success) showToast(success, 'success', { duration: 4500 });
    if (error) showToast(error, 'error', { duration: 4500 });
})();
