import { toast as showToast } from '../../shared/toast.js';

(function () {
  'use strict';
  var pageData = JSON.parse(document.getElementById('donationsShowData').textContent);
  if (pageData.success) setTimeout(function(){showToast(pageData.success,'success', { duration: 4200 });},200);
  if (pageData.error) setTimeout(function(){showToast(pageData.error,'error', { duration: 4200 });},200);
  if (pageData.info) setTimeout(function(){showToast(pageData.info,'info', { duration: 4200 });},200);

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
