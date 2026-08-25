(function () {
  'use strict';

  function toggleAllCheckboxes() {
    var checked = document.getElementById('selectAll').checked;
    document.querySelectorAll('.cmp-checkbox').forEach(function(cb) { cb.checked = checked; });
    updateBulkBar();
  }

  function updateBulkBar() {
    var checked = document.querySelectorAll('.cmp-checkbox:checked');
    var bar = document.getElementById('bulkBar');
    if (checked.length > 0) {
      bar.style.display = 'flex';
      document.getElementById('bulkCount').textContent = checked.length;
    } else {
      bar.style.display = 'none';
    }
  }

  function clearSelection() {
    document.querySelectorAll('.cmp-checkbox').forEach(function(cb) { cb.checked = false; });
    document.getElementById('selectAll').checked = false;
    updateBulkBar();
  }

  function bulkAction(url) {
    var checked = document.querySelectorAll('.cmp-checkbox:checked');
    if (checked.length === 0) return;

    var ids = [];
    checked.forEach(function(cb) { ids.push(cb.value); });

    var container = document.getElementById('bulkIds');
    container.innerHTML = '';
    ids.forEach(function(id) {
      var input = document.createElement('input');
      input.type = 'hidden';
      input.name = 'ids[]';
      input.value = id;
      container.appendChild(input);
    });

    if (confirm('Apply action to ' + ids.length + ' campaign(s)?')) {
      document.getElementById('bulkForm').action = url;
      document.getElementById('bulkForm').submit();
    }
  }

  let _t;
  function autoSubmit(){clearTimeout(_t);_t=setTimeout(()=>document.getElementById('filterForm').submit(),400);}

  document.addEventListener('change', function (e) {
    var el = e.target.closest('[data-action]');
    if (!el) return;
    switch (el.getAttribute('data-action')) {
      case 'tab-select': window.location.href = el.value; break;
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
    if (!el) return;
    switch (el.getAttribute('data-action')) {
      case 'bulk-action': bulkAction(el.getAttribute('data-url')); break;
      case 'clear-selection': clearSelection(); break;
    }
  });
})();
