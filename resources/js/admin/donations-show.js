(function () {
  'use strict';
  var pageData = JSON.parse(document.getElementById('donationsShowData').textContent);

  function toast(msg, type) {
    var icons = {
      success: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
      error:   '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
      info:    '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
    };
    var t = document.createElement('div');
    t.className = 'toast toast-' + (type === 'success' ? 'ok' : type === 'info' ? 'info' : 'err');
    t.innerHTML = (icons[type] || '') + '<span>' + msg + '</span><button class="toast-x" onclick="this.parentElement.remove()">✕</button>';
    document.getElementById('toastWrap').appendChild(t);
    setTimeout(function () { t.style.transition='opacity .3s,transform .3s'; t.style.opacity='0'; t.style.transform='translateX(20px)'; setTimeout(function(){ t.remove(); }, 300); }, 4200);
  }
  if (pageData.success) setTimeout(function(){toast(pageData.success,'success');},200);
  if (pageData.error) setTimeout(function(){toast(pageData.error,'error');},200);
  if (pageData.info) setTimeout(function(){toast(pageData.info,'info');},200);

  function openRefund(id, donor, amount) {
    document.getElementById('refundForm').action = pageData.refundUrl.replace(':id', id);
    document.getElementById('refundDonor').textContent = '"' + donor + '"';
    document.getElementById('refundAmount').textContent = '₹' + Number(amount).toLocaleString('en-IN', {minimumFractionDigits:2, maximumFractionDigits:2});
    document.getElementById('refundReason').value = '';
    document.getElementById('refundOverlay').classList.add('open');
  }
  function closeRefund() { document.getElementById('refundOverlay').classList.remove('open'); }
  document.getElementById('refundOverlay').addEventListener('click', function (e) { if (e.target === this) closeRefund(); });
  document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeRefund(); });

  document.addEventListener('click', function (e) {
    var el = e.target.closest('[data-action]');
    if (!el) return;
    switch (el.getAttribute('data-action')) {
      case 'open-refund': openRefund(el.getAttribute('data-id'), el.getAttribute('data-donor'), el.getAttribute('data-amount')); break;
    }
  });
}());
