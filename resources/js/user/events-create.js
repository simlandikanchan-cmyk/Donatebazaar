(function(){
'use strict';

/* ── SUBMIT GUARD ── */
document.getElementById('eventForm').addEventListener('submit', function(){
    var btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:15px;height:15px;animation:spin 1s linear infinite"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg> Creating…';
});

function previewCreateImage(input) {
    if (!input.files || !input.files[0]) return;
    var reader = new FileReader();
    reader.onload = function(e) {
        var preview = document.getElementById('createUploadPreview');
        var zone = document.getElementById('createUploadZone');
        var placeholder = document.getElementById('createUploadPlaceholder');
        preview.src = e.target.result;
        preview.classList.add('show');
        placeholder.style.display = 'none';
        zone.classList.add('has-preview');
    };
    reader.readAsDataURL(input.files[0]);
}

document.addEventListener('change', function(e){
    var input = e.target.closest('[data-action="create-cover-input"]');
    if (input) previewCreateImage(input);
});

})();