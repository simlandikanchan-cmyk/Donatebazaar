/* ═══════════════════════════════════════════════════════════════════
   Admin Events Edit page — moved from admin/events/edit.blade.php inline
   <script>. window.* bridges converted to internal functions with
   data-action delegation; all logic preserved.
   ═══════════════════════════════════════════════════════════════════ */

(function(){
'use strict';

function setStatus(val) {
  document.getElementById('statusHidden').value = val;
  document.querySelectorAll('.status-opt').forEach(function(o){ o.classList.remove('selected-status'); });
  document.querySelector('.sel-' + val).classList.add('selected-status');
  var badge = document.getElementById('summaryStatusBadge');
  var map = { draft:'sb-draft', active:'sb-active', cancelled:'' };
  badge.className = 'summary-badge ' + (map[val] || '');
  if (val === 'cancelled') badge.style.cssText = 'background:var(--red-lt);color:var(--red);';
  else badge.style.cssText = '';
  badge.textContent = val.charAt(0).toUpperCase() + val.slice(1);
}

var descEl = document.getElementById('descInp');
var descCount = document.getElementById('descCount');
if (descEl) {
  descEl.addEventListener('input', function(){
    descCount.textContent = this.value.length + ' / 2000';
  });
}

function previewImage(input) {
  if (!input.files || !input.files[0]) return;
  var reader = new FileReader();
  reader.onload = function(e) {
    var preview = document.getElementById('uploadPreview');
    var zone = document.getElementById('uploadZone');
    var placeholder = document.getElementById('uploadPlaceholder');
    preview.src = e.target.result;
    preview.classList.add('show');
    placeholder.style.display = 'none';
    zone.classList.add('has-preview');
  };
  reader.readAsDataURL(input.files[0]);
}

/* ── delegated actions ── */
document.addEventListener('click', function (e) {
  var el = e.target.closest('[data-action]');
  if (!el) return;
  var action = el.getAttribute('data-action');

  if(action==='set-status'){setStatus(el.getAttribute('data-status'));}
});

document.addEventListener('change', function (e) {
  var el = e.target.closest('[data-action]');
  if (!el) return;
  var action = el.getAttribute('data-action');

  if(action==='preview-image'){previewImage(el);}
});

})();