(function () {
  'use strict';

  var pageData = JSON.parse(document.getElementById('campaignProductsData').textContent);

  function showRejectModal(id, name) {
    document.getElementById('rejectProductName').textContent = name;
    document.getElementById('rejectForm').action = pageData.rejectUrl.replace('__ID__', id);
    document.getElementById('rejectModal').classList.add('open');
  }
  function closeRejectModal() {
    document.getElementById('rejectModal').classList.remove('open');
  }
  document.getElementById('rejectModal')?.addEventListener('click', function(e) {
    if (e.target === this) closeRejectModal();
  });

  function toggleAllCheckboxes() {
    var checked = document.getElementById('selectAll').checked;
    document.querySelectorAll('.product-checkbox:not(:disabled)').forEach(function(cb) {
      cb.checked = checked;
    });
    updateBulkBar();
  }

  function updateBulkBar() {
    var checked = document.querySelectorAll('.product-checkbox:checked:not(:disabled)');
    var bar = document.getElementById('bulkBar');
    if (checked.length > 0) {
      bar.style.display = 'flex';
      document.getElementById('bulkCount').textContent = checked.length;
    } else {
      bar.style.display = 'none';
    }
  }

  function clearSelection() {
    document.querySelectorAll('.product-checkbox').forEach(function(cb) { cb.checked = false; });
    document.getElementById('selectAll').checked = false;
    updateBulkBar();
  }

  function bulkApprove() {
    var checked = document.querySelectorAll('.product-checkbox:checked:not(:disabled)');
    if (checked.length === 0) return;

    var ids = [];
    checked.forEach(function(cb) { ids.push(cb.value); });

    var container = document.getElementById('bulkApproveIds');
    container.innerHTML = '';
    ids.forEach(function(id) {
      var input = document.createElement('input');
      input.type = 'hidden';
      input.name = 'ids[]';
      input.value = id;
      container.appendChild(input);
    });

    if (confirm('Approve ' + ids.length + ' product(s)?')) {
      document.getElementById('bulkApproveForm').submit();
    }
  }

  function openBulkRejectModal() {
    var checked = document.querySelectorAll('.product-checkbox:checked:not(:disabled)');
    if (checked.length === 0) return;

    document.getElementById('bulkRejectCount').textContent = checked.length;

    var container = document.getElementById('bulkRejectIds');
    container.innerHTML = '';
    checked.forEach(function(cb) {
      var input = document.createElement('input');
      input.type = 'hidden';
      input.name = 'ids[]';
      input.value = cb.value;
      container.appendChild(input);
    });

    document.getElementById('bulkRejectModal').classList.add('open');
  }
  function closeBulkRejectModal() {
    document.getElementById('bulkRejectModal').classList.remove('open');
  }
  document.getElementById('bulkRejectModal')?.addEventListener('click', function(e) {
    if (e.target === this) closeBulkRejectModal();
  });

  function openLightbox(src) {
    document.getElementById('lightboxImg').src = src;
    document.getElementById('imageLightbox').classList.add('open');
  }
  function closeLightbox() {
    document.getElementById('imageLightbox').classList.remove('open');
  }

  let _t;
  function autoSubmit(){clearTimeout(_t);_t=setTimeout(()=>document.getElementById('filterForm').submit(),400);}

  document.addEventListener('change', function (e) {
    var el = e.target.closest('[data-action]');
    if (!el) return;
    switch (el.getAttribute('data-action')) {
      case 'tab-select': window.location.href = el.value; break;
      case 'form-submit': el.form.submit(); break;
      case 'toggle-all': toggleAllCheckboxes(); break;
      case 'update-bulk': updateBulkBar(); break;
    }
  });

  document.addEventListener('input', function (e) {
    var el = e.target.closest('[data-action]');
    if (!el) return;
    switch (el.getAttribute('data-action')) {
      case 'auto-submit': autoSubmit(); break;
    }
  });

  document.addEventListener('click', function (e) {
    var el = e.target.closest('[data-action]');
    if (el) {
      switch (el.getAttribute('data-action')) {
        case 'bulk-approve': bulkApprove(); return;
        case 'open-bulk-reject': openBulkRejectModal(); return;
        case 'clear-selection': clearSelection(); return;
        case 'open-lightbox': openLightbox(el.src); return;
        case 'open-reject': showRejectModal(el.getAttribute('data-id'), el.getAttribute('data-name')); return;
      }
    }
    if (e.target.closest && e.target.closest('#imageLightbox')) closeLightbox();
  });

  document.addEventListener('submit', function (e) {
    var msg = e.target.getAttribute('data-confirm');
    if (msg !== null && !confirm(msg)) e.preventDefault();
  });
})();
