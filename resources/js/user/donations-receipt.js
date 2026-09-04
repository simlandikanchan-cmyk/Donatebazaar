(function(){
'use strict';

document.addEventListener('click', function(e){
    var goBack = e.target.closest('[data-action="go-back"]');
    if (goBack) { history.back(); return; }

    var btn = e.target.closest('[data-action="download-receipt"]');
    if (!btn) return;

    var receipt = document.getElementById('receipt');
    if (!receipt) return;

    var receiptNo = (btn.getAttribute('data-receipt-no') || 'donation-receipt').replace(/[^a-zA-Z0-9-_]/g, '_');

    btn.disabled = true;
    var originalHTML = btn.innerHTML;
    btn.innerHTML = '<svg class="btn__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg><span class="btn__label">Generating...</span>';

    // Load the PDF library lazily only when the user asks to download.
    import('html2pdf.js').then(function (module) {
        var html2pdf = module.default || module;
        var opt = {
            margin:       [10, 10, 10, 10],
            filename:     receiptNo + '.pdf',
            image:        { type: 'jpeg', quality: 0.98 },
            html2canvas:  { scale: 2, useCORS: true, letterRendering: true },
            jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
        };
        html2pdf().set(opt).from(receipt).save().then(function(){
            btn.disabled = false;
            btn.innerHTML = originalHTML;
        })['catch'](function(){
            btn.disabled = false;
            btn.innerHTML = originalHTML;
        });
    })['catch'](function(){
        btn.disabled = false;
        btn.innerHTML = originalHTML;
    });
});

})();
