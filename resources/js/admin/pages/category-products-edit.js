/* ═══════════════════════════════════════════════════════════════════
   Admin Category Product Edit page — moved from admin/category-products/edit.blade.php
   inline <script>. window.* bridges converted to internal functions with
   data-action delegation; inline DOM-op onclick handlers moved to
   data-action/direct listeners.
   ═══════════════════════════════════════════════════════════════════ */

(function () {
  'use strict';

  var flash = document.getElementById('flashAlert');
  if (flash) setTimeout(function () {
    flash.style.transition = 'opacity .4s,transform .4s'; flash.style.opacity = '0';
    flash.style.transform = 'translateY(-6px)';
    setTimeout(function () { flash.remove(); }, 400);
  }, 4000);

  function closeDeleteModal() { document.getElementById('deleteOverlay').classList.remove('open'); }
  function openDeleteModal() { document.getElementById('deleteOverlay').classList.add('open'); }
  function confirmDelete() { document.getElementById('deleteForm').submit(); }
  document.getElementById('deleteOverlay').addEventListener('click', function (e) { if (e.target === this) closeDeleteModal(); });
  document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeDeleteModal(); });

  var isActiveChk = document.getElementById('is_active');
  var statusRow   = document.getElementById('statusRow');
  function syncStatus() { statusRow.classList.toggle('active-toggle', isActiveChk.checked); }
  isActiveChk.addEventListener('change', syncStatus); syncStatus();

  var removeChk   = document.getElementById('removeImage');
  var currentImg  = document.getElementById('currentImg');
  if (removeChk && currentImg) {
    removeChk.addEventListener('change', function () {
      currentImg.style.opacity  = this.checked ? '.3' : '1';
      currentImg.style.filter   = this.checked ? 'grayscale(1)' : '';
    });
  }

  var uploadInput   = document.getElementById('imageUpload');
  var uploadZone    = document.getElementById('uploadZone');
  var imgPreviewWrap= document.getElementById('imgPreviewWrap');
  var imgPreview    = document.getElementById('imgPreview');
  var imgLabel      = document.getElementById('imgLabel');
  var uploadTitle   = document.getElementById('uploadTitle');
  var prevImgWrap   = document.getElementById('prevImgWrap');

  uploadZone.addEventListener('click', function () { uploadInput.click(); });

  uploadInput.addEventListener('change', function () { loadPreview(this.files[0]); });

  ['dragover','dragenter'].forEach(function (ev) {
    uploadZone.addEventListener(ev, function (e) { e.preventDefault(); uploadZone.classList.add('drag-over'); });
  });
  ['dragleave','drop'].forEach(function (ev) {
    uploadZone.addEventListener(ev, function (e) {
      e.preventDefault(); uploadZone.classList.remove('drag-over');
      if (ev === 'drop' && e.dataTransfer.files.length) loadPreview(e.dataTransfer.files[0]);
    });
  });

  function loadPreview(file) {
    if (!file || !file.type.startsWith('image/')) return;
    var reader = new FileReader();
    reader.onload = function (e) {
      imgPreview.src = e.target.result;
      imgLabel.textContent = file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)';
      imgPreviewWrap.style.display = 'block';
      uploadTitle.textContent = 'Image selected';
      var pi = prevImgWrap.querySelector('img');
      if (pi) { pi.src = e.target.result; }
      else {
        prevImgWrap.innerHTML = '';
        var ni = document.createElement('img');
        ni.src = e.target.result; ni.alt = 'preview'; ni.id = 'prevImg';
        prevImgWrap.appendChild(ni);
      }
    };
    reader.readAsDataURL(file);
  }

  function clearImagePreview() {
    uploadInput.value = '';
    imgPreviewWrap.style.display = 'none';
    imgPreview.src = '';
    uploadTitle.textContent = 'Click to upload or drag & drop';
  }

  var nameInp   = document.getElementById('name');
  var priceInp  = document.getElementById('price');
  var stockInp  = document.getElementById('stock');
  var typeInp   = document.getElementById('product_type');
  var descInp   = document.getElementById('description');

  function fmt(v) {
    var n = parseFloat(v);
    return isNaN(n) ? '—' : '₹' + n.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
  }

  function updatePreview() {
    var n = nameInp.value.trim();
    document.getElementById('prevName').textContent = n || 'Product name…';

    var p = priceInp.value;
    var pf = fmt(p);
    document.getElementById('prevPrice').textContent    = pf;
    document.getElementById('prevPriceVal').textContent = pf;

    var s = parseInt(stockInp.value, 10);
    document.getElementById('prevStock').textContent    = 'Stock: ' + (isNaN(s) ? '—' : s);
    document.getElementById('prevStockVal').textContent = isNaN(s) ? '—' : s;

    document.getElementById('prevType').textContent = typeInp.value ? typeInp.options[typeInp.selectedIndex].text : '—';

    var d = descInp.value.trim();
    document.getElementById('prevDesc').textContent = d ? (d.length > 120 ? d.slice(0,120)+'…' : d) : 'Description will appear here…';

    var active = isActiveChk.checked;
    var sv = document.getElementById('prevStatusVal');
    sv.textContent = active ? '●' : '○';
    sv.style.color = active ? 'var(--green)' : 'var(--red)';
  }

  [nameInp, priceInp, stockInp, descInp].forEach(function (el) { el.addEventListener('input', updatePreview); });
  typeInp.addEventListener('change', updatePreview);
  isActiveChk.addEventListener('change', updatePreview);

  document.getElementById('editForm').addEventListener('submit', function () {
    var btn = document.getElementById('saveBtn');
    btn.disabled = true;
    btn.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="animation:spin .7s linear infinite"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg> Saving…';
  });

  /* ── delegated actions ── */
  document.addEventListener('click', function(e){
    var el = e.target.closest('[data-action]');
    if (!el) return;
    var action = el.getAttribute('data-action');

    if (action === 'close-delete-modal') { closeDeleteModal(); }
    else if (action === 'open-delete-modal') { openDeleteModal(); }
    else if (action === 'confirm-delete') { confirmDelete(); }
    else if (action === 'clear-image-preview') { clearImagePreview(); }
  });

}());