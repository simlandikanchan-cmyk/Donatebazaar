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
    var reader = new FileReader();
    reader.onload = function (e) {
      document.getElementById('prevImgIcon').style.display = 'none';
      var el = document.getElementById('prevImgEl');
      el.src = e.target.result; el.style.display = 'block';
      document.getElementById('uploadPrompt').style.display = 'none';
      document.getElementById('imgPreviewWrap').style.display = 'flex';
      document.getElementById('imgPreview').src = e.target.result;
    };
    reader.readAsDataURL(input.files[0]);
  }

  function removeImage() {
    document.getElementById('imageInput').value = '';
    document.getElementById('prevImgIcon').style.display = '';
    document.getElementById('prevImgEl').style.display = 'none';
    document.getElementById('uploadPrompt').style.display = '';
    document.getElementById('imgPreviewWrap').style.display = 'none';
  }

  var zone = document.getElementById('uploadZone');
  zone.addEventListener('dragover', function (e) { e.preventDefault(); zone.classList.add('drag'); });
  zone.addEventListener('dragleave', function () { zone.classList.remove('drag'); });
  zone.addEventListener('drop', function () { zone.classList.remove('drag'); });

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
    }
  });
})();
