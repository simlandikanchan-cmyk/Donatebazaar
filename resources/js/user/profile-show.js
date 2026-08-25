/* ═══════════════════════════════════════════════════════════════════
   Profile Show page — moved from profile/show.blade.php inline <script>
   Behaviors converted to data-action delegation (no window.* bridges).
   ═══════════════════════════════════════════════════════════════════ */

import { toast } from '../shared/toast.js';

(function () {
    'use strict';

    var TABS = ['campaigns', 'about', 'settings'];

    function switchTab(name) {
        TABS.forEach(function (t) {
            var tc = document.getElementById('tc-' + t);
            var tb = document.getElementById('tb-' + t);
            var sl = document.getElementById('sl-' + t);
            var pill = document.getElementById('pill-' + t);
            if (tc) tc.className = 'tab-content' + (t === name ? ' on' : '');
            if (tb) tb.className = 'tab-btn' + (t === name ? ' on' : '');
            if (sl) sl.className = 's-link' + (t === name ? ' active' : '');
            if (pill) pill.className = 'stat-pill' + (t === name ? ' active' : '');
        });
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    var uploadModal = document.getElementById('uploadModal');
    var deleteModal = document.getElementById('deleteModal');
    var activeUploadForm = null;

    function cancelUpload() {
        if (!uploadModal) return;
        uploadModal.classList.remove('open');
        document.body.style.overflow = '';
        var ai = document.getElementById('avatarInput'); if (ai) ai.value = '';
        var ci = document.getElementById('coverInput'); if (ci) ci.value = '';
        activeUploadForm = null;
    }

    function openDeleteModal() {
        if (!deleteModal) return;
        deleteModal.classList.add('open');
        document.body.style.overflow = 'hidden';
        var pw = document.getElementById('del-pw'); if (pw) pw.focus();
    }

    function closeDeleteModal() {
        if (!deleteModal) return;
        deleteModal.classList.remove('open');
        document.body.style.overflow = '';
    }

    function openUploadModal(file, title) {
        var reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById('modalPreviewImg').src = e.target.result;
            document.getElementById('modalTitle').textContent = title;
            uploadModal.classList.add('open');
            document.body.style.overflow = 'hidden';
        };
        reader.readAsDataURL(file);
    }

    function shareCampaign(title, url) {
        if (navigator.share) {
            navigator.share({ title: title, url: url }).catch(function () {});
        } else if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(url).then(function () { toast('Campaign link copied', 'success'); });
        } else {
            var ta = document.createElement('textarea');
            ta.value = url;
            document.body.appendChild(ta);
            ta.select();
            try { document.execCommand('copy'); toast('Campaign link copied', 'success'); } catch (e) {}
            document.body.removeChild(ta);
        }
    }

    function toggleEye(inputId, btn) {
        var input = document.getElementById(inputId);
        if (!input) return;
        var isText = input.type === 'text';
        input.type = isText ? 'password' : 'text';
        btn.querySelector('svg').innerHTML = isText
            ? '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>'
            : '<path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>';
    }

    /* ── delegated actions ── */
    document.addEventListener('click', function (e) {
        var el = e.target.closest('[data-action]');
        if (!el) return;
        var action = el.getAttribute('data-action');

        if (action === 'switch-tab') {
            switchTab(el.getAttribute('data-tab'));
        } else if (action === 'open-delete-modal') {
            openDeleteModal();
        } else if (action === 'close-delete-modal') {
            closeDeleteModal();
        } else if (action === 'cancel-upload') {
            cancelUpload();
        } else if (action === 'trigger-click') {
            var target = document.getElementById(el.getAttribute('data-target'));
            if (target) target.click();
        } else if (action === 'toggle-eye') {
            toggleEye(el.getAttribute('data-input'), el);
        } else if (action === 'share-campaign') {
            shareCampaign(el.getAttribute('data-title'), el.getAttribute('data-url'));
        }
    });

    /* ── avatar / cover file inputs ── */
    var ai = document.getElementById('avatarInput');
    if (ai) ai.addEventListener('change', function () {
        var file = this.files[0]; if (!file) return;
        if (file.size > 2 * 1024 * 1024) { toast('Avatar must be under 2 MB', 'error'); this.value = ''; return; }
        activeUploadForm = document.getElementById('avatarForm');
        var liveImg = document.getElementById('avatarImg');
        var initials = document.getElementById('avatarInitials');
        liveImg.src = URL.createObjectURL(file);
        liveImg.style.display = 'block';
        if (initials) initials.style.display = 'none';
        document.querySelectorAll('.t-avatar img, .s-avatar img').forEach(function (el) { el.src = liveImg.src; });
        openUploadModal(file, 'Update profile photo');
    });

    var ci = document.getElementById('coverInput');
    if (ci) ci.addEventListener('change', function () {
        var file = this.files[0]; if (!file) return;
        if (file.size > 5 * 1024 * 1024) { toast('Cover must be under 5 MB', 'error'); this.value = ''; return; }
        activeUploadForm = document.getElementById('coverForm');
        var liveImg = document.getElementById('coverImg');
        liveImg.src = URL.createObjectURL(file);
        liveImg.style.display = 'block';
        openUploadModal(file, 'Update cover photo');
    });

    var confirmBtn = document.getElementById('confirmUploadBtn');
    if (confirmBtn) confirmBtn.addEventListener('click', function () {
        if (activeUploadForm) activeUploadForm.submit();
    });

    if (uploadModal) uploadModal.addEventListener('click', function (e) { if (e.target === uploadModal) cancelUpload(); });
    if (deleteModal) deleteModal.addEventListener('click', function (e) { if (e.target === deleteModal) closeDeleteModal(); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') { cancelUpload(); closeDeleteModal(); } });

    /* ── Auto-actions from Blade conditionals ── */
    function runAutoActions() {
        var el = document.querySelector('[data-auto-action]');
        if (!el) return;
        var action = el.getAttribute('data-auto-action');
        if (action === 'switch-tab') {
            switchTab(el.getAttribute('data-tab'));
        } else if (action === 'open-delete-modal') {
            openDeleteModal();
        }
    }
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', runAutoActions);
    } else {
        runAutoActions();
    }

})();