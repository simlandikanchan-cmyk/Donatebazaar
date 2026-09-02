(function () {
  'use strict';

  function updatePreview() {
    var name = document.getElementById('name').value.trim();
    var active = document.getElementById('isActive').checked;
    var catSel = document.getElementById('category_id');
    var catText = catSel.options[catSel.selectedIndex] ? catSel.options[catSel.selectedIndex].text : '—';
    var typeSel = document.getElementById('product_type');
    var typeText = typeSel.value ? typeSel.value.charAt(0).toUpperCase() + typeSel.value.slice(1) : '—';
    var price = parseFloat(document.getElementById('price').value) || 0;
    var stock = parseInt(document.getElementById('stock').value) || 0;

    var nameEl = document.getElementById('prevName');
    nameEl.textContent = name || 'Product name…';
    nameEl.classList.toggle('empty', !name);

    var badge = document.getElementById('prevBadge');
    var statusEl = document.getElementById('prevStatus');
    if (active) {
      badge.className = 'prev-badge pb-active';
      badge.innerHTML = '<span style="width:5px;height:5px;border-radius:50%;background:currentColor;display:inline-block;"></span> Active';
      statusEl.textContent = 'Active'; statusEl.style.color = 'var(--green)';
    } else {
      badge.className = 'prev-badge pb-inactive';
      badge.innerHTML = '<span style="width:5px;height:5px;border-radius:50%;background:currentColor;display:inline-block;"></span> Inactive';
      statusEl.textContent = 'Inactive'; statusEl.style.color = 'var(--text3)';
    }
    document.getElementById('prevCat').textContent = catText === 'Select category…' ? '—' : catText;
    document.getElementById('prevType').textContent = typeText;
    document.getElementById('prevPrice').textContent = '₹' + price.toFixed(2);
    document.getElementById('prevStock').textContent = stock;
  }

  function handleImageChange(input) {
    if (!input.files || !input.files[0]) return;
    processFile(input.files[0]);
  }

  var ALLOWED_TYPES = ['image/jpeg', 'image/png', 'image/webp'];
  var MAX_SIZE = 2 * 1024 * 1024;

  function clearImageError() {
    var err = document.getElementById('imageError');
    var zone = document.getElementById('uploadZone');
    if (err) err.hidden = true;
    if (zone) zone.classList.remove('has-error');
  }

  function showImageError(message) {
    var err = document.getElementById('imageError');
    var zone = document.getElementById('uploadZone');
    if (err) { err.textContent = message; err.hidden = false; }
    if (zone) zone.classList.add('has-error');
  }

  function processFile(file) {
    clearImageError();

    if (!file.type || ALLOWED_TYPES.indexOf(file.type) === -1) {
      showImageError('Invalid file type. Please choose a JPG, PNG or WEBP image.');
      return;
    }
    if (file.size > MAX_SIZE) {
      showImageError('Image is too large. Maximum size is 2MB.');
      return;
    }

    var spinner = document.getElementById('imgPreviewSpinner');
    var prevErr = document.getElementById('imgPreviewError');
    var preview = document.getElementById('imgPreview');

    document.getElementById('imgPreviewWrap').hidden = false;
    document.getElementById('uploadPrompt').style.display = 'none';
    if (spinner) spinner.hidden = false;
    if (prevErr) prevErr.hidden = true;
    preview.classList.remove('loaded');
    preview.onload = null;

    var reader = new FileReader();
    reader.onload = function (e) {
      preview.src = e.target.result;
      var el = document.getElementById('prevImgEl');
      if (el) { el.src = e.target.result; el.style.display = 'block'; }
      document.getElementById('prevImgIcon').style.display = 'none';
    };
    reader.onerror = function () {
      if (spinner) spinner.hidden = true;
      if (prevErr) { prevErr.textContent = 'Could not read this image.'; prevErr.hidden = false; }
    };
    reader.readAsDataURL(file);

    preview.onload = function () {
      preview.classList.add('loaded');
      if (spinner) spinner.hidden = true;
    };
    preview.onerror = function () {
      if (spinner) spinner.hidden = true;
      if (prevErr) { prevErr.textContent = 'Preview could not be loaded for this file.'; prevErr.hidden = false; }
      if (preview) preview.classList.remove('loaded');
    };
  }

  function showEmptyState() {
    document.getElementById('uploadPrompt').style.display = '';
    document.getElementById('imgPreviewWrap').hidden = true;
    document.getElementById('prevImgIcon').style.display = '';
    document.getElementById('prevImgEl').style.display = 'none';
    document.getElementById('imageInput').value = '';
    clearImageError();
  }

  function removeImage() {
    showEmptyState();
  }

  var uploadInput = document.getElementById('imageInput');
  var zone = document.getElementById('uploadZone');
  zone.addEventListener('click', function (e) {
    if (e.target.closest('[data-action="remove-image"],[data-action="change-image"]')) return;
    uploadInput.click();
  });
  uploadInput.addEventListener('click', function (e) { e.stopPropagation(); });
  ['dragover', 'dragenter'].forEach(function (ev) {
    zone.addEventListener(ev, function (e) { e.preventDefault(); zone.classList.add('drag'); });
  });
  ['dragleave', 'drop'].forEach(function (ev) {
    zone.addEventListener(ev, function (e) {
      e.preventDefault(); zone.classList.remove('drag');
      if (ev === 'drop' && e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files.length) {
        var f = e.dataTransfer.files[0];
        if (ALLOWED_TYPES.indexOf(f.type) !== -1) {
          uploadInput.files = e.dataTransfer.files;
          processFile(f);
        } else {
          showImageError('Invalid file type. Please choose a JPG, PNG or WEBP image.');
        }
      }
    });
  });

  document.getElementById('prodForm').addEventListener('submit', function () {
    var btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="15" height="15"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg> Creating…';
  });

  updatePreview();

  document.addEventListener('change', function (e) {
    var el = e.target.closest('[data-action]');
    if (!el) return;
    switch (el.getAttribute('data-action')) {
      case 'update-preview': updatePreview(); break;
      case 'image-change': handleImageChange(el); break;
    }
  });

  document.addEventListener('input', function (e) {
    var el = e.target.closest('[data-action]');
    if (!el) return;
    switch (el.getAttribute('data-action')) {
      case 'update-preview': updatePreview(); break;
    }
  });

  document.addEventListener('click', function (e) {
    var el = e.target.closest('[data-action]');
    if (!el) return;
    switch (el.getAttribute('data-action')) {
      case 'remove-image': removeImage(); break;
      case 'change-image': uploadInput.click(); break;
    }
  });
})();
