import { toast } from '../shared/toast.js';

/* Campaign Edit page - extracted from campaigns/edit.blade.php inline <script>.
   Internal functions were promoted from window.* (previewImage/countChars/openModal/closeModal);
   inline onclick/onchange/oninput converted to data-action delegation / direct listeners. */

(function () {
    'use strict';

    var sidebar = document.getElementById('sidebar');

    document.getElementById('hamburger').addEventListener('click', function(){
        sidebar.classList.toggle('open');
    });
    document.addEventListener('click', function(e){
        if (window.innerWidth <= 820 && !sidebar.contains(e.target) && !document.getElementById('hamburger').contains(e.target)){
            sidebar.classList.remove('open');
        }
    });

    var toastWrap = document.getElementById('toastContainer');
    if (toastWrap.dataset.success) setTimeout(function(){ toast(toastWrap.dataset.success, 'success', { duration: 4500 }); }, 200);
    if (toastWrap.dataset.error)   setTimeout(function(){ toast(toastWrap.dataset.error, 'error', { duration: 4500 }); }, 200);

    function previewImage(event) {
        var file = event.target.files[0];
        if (!file) return;
        var preview = document.getElementById('newPreview');
        preview.src = URL.createObjectURL(file);
        preview.style.display = 'block';
        var old = document.getElementById('currentCover');
        if (old) old.style.opacity = '0.4';
    }

    function countChars(el, spanId) {
        document.getElementById(spanId).textContent = el.value.length;
    }

    function openModal(id) {
        document.getElementById(id).classList.add('open');
        document.body.style.overflow = 'hidden';
        setTimeout(function(){
            var ta = document.querySelector('#' + id + ' .modal-ta');
            if (ta) ta.focus();
        }, 60);
    }
    function closeModal(id) {
        document.getElementById(id).classList.remove('open');
        document.body.style.overflow = '';
    }
    document.addEventListener('click', function(e){
        var a = e.target.closest('[data-action="close-modal"]');
        if (a) closeModal(a.dataset.modal);
    });
    ['pauseModal','resumeModal'].forEach(function(id){
        document.getElementById(id).addEventListener('click', function(e){
            if (e.target === this) closeModal(id);
        });
    });
    document.addEventListener('keydown', function(e){
        if (e.key === 'Escape'){ closeModal('pauseModal'); closeModal('resumeModal'); }
    });

    var coverInput = document.querySelector('.file-drop input[type="file"]');
    if (coverInput) coverInput.addEventListener('change', previewImage);

    var desc = document.getElementById('descField');
    if (desc) desc.addEventListener('input', function(){ countChars(this, 'descCount'); });
    var pauseReason = document.getElementById('pauseReason');
    if (pauseReason) pauseReason.addEventListener('input', function(){ countChars(this, 'pauseCount'); });
    var resumeReason = document.getElementById('resumeReason');
    if (resumeReason) resumeReason.addEventListener('input', function(){ countChars(this, 'resumeCount'); });

    document.getElementById('pauseForm').addEventListener('submit', function(e){
        var v = document.getElementById('pauseReason').value.trim();
        var err = document.getElementById('pauseErr');
        if (v.length < 10){ e.preventDefault(); err.style.display = 'block'; return; }
        err.style.display = 'none';
        var btn = document.getElementById('pauseSubmitBtn');
        btn.disabled = true; btn.textContent = 'Pausing…';
    });

    document.getElementById('resumeForm').addEventListener('submit', function(e){
        var v = document.getElementById('resumeReason').value.trim();
        var err = document.getElementById('resumeErr');
        if (v.length < 10){ e.preventDefault(); err.style.display = 'block'; return; }
        err.style.display = 'none';
        var btn = document.getElementById('resumeSubmitBtn');
        btn.disabled = true; btn.textContent = 'Resuming…';
    });

    document.getElementById('editForm').addEventListener('submit', function(){
        var btn = document.getElementById('saveBtn');
        if (!btn.disabled){ btn.disabled = true; btn.textContent = '⏳ Saving…'; }
    });

    if (desc) document.getElementById('descCount').textContent = desc.value.length;
})();