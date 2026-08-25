import { toast } from '../../shared/toast.js';

(function(){
'use strict';

/* ── Registration search ── */
function filterRegistrations(q) {
  q = q.toLowerCase().trim();
  var rows = document.querySelectorAll('.reg-row');
  var visible = 0;
  rows.forEach(function(r) {
    var match = !q || r.dataset.search.includes(q);
    r.style.display = match ? '' : 'none';
    if (match) visible++;
  });
  var empty = document.getElementById('regEmpty');
  if (empty) empty.style.display = visible === 0 ? 'block' : 'none';
}

/* ── Copy event link (uses shared toast) ── */
function copyEventLink(btn) {
  var url = btn.dataset.url;
  if (navigator.clipboard && navigator.clipboard.writeText) {
    navigator.clipboard.writeText(url).then(function() {
      toast('Event link copied to clipboard', 'success');
    }).catch(function() { fallbackCopy(url); });
  } else { fallbackCopy(url); }
}
function fallbackCopy(url) {
  var i = document.createElement('input');
  i.value = url; i.style.position = 'fixed'; i.style.opacity = '0';
  document.body.appendChild(i); i.select();
  try { document.execCommand('copy'); toast('Event link copied to clipboard', 'success'); } catch(e) {}
  document.body.removeChild(i);
}

/* ── Toggle loading indicator ── */
document.querySelectorAll('.toggle-wrap input[type=checkbox]').forEach(function(chk) {
  chk.addEventListener('change', function() {
    var wrap = this.closest('.toggle-wrap');
    wrap.style.opacity = '.5';
    wrap.style.pointerEvents = 'none';
    var track = wrap.querySelector('.toggle-track');
    if (track) track.setAttribute('aria-busy', 'true');
  });
});

document.addEventListener('click', function (e) {
  var el = e.target.closest('[data-action]');
  if (!el) return;
  switch (el.getAttribute('data-action')) {
    case 'toggle-check': document.getElementById(el.getAttribute('data-target')).click(); break;
    case 'copy-event-link': copyEventLink(el); break;
  }
});

document.addEventListener('change', function (e) {
  var el = e.target.closest('[data-action]');
  if (!el) return;
  switch (el.getAttribute('data-action')) {
    case 'submit-form': document.getElementById(el.getAttribute('data-target')).submit(); break;
  }
});

document.addEventListener('input', function (e) {
  var el = e.target.closest('[data-action]');
  if (!el) return;
  switch (el.getAttribute('data-action')) {
    case 'filter-registrations': filterRegistrations(el.value); break;
  }
});

document.addEventListener('submit', function (e) {
  var msg = e.target.getAttribute('data-confirm');
  if (msg !== null && !confirm(msg)) e.preventDefault();
});
})();
