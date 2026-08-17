(function(){
'use strict';
var html   = document.documentElement;
var toggle = document.getElementById('themeToggle');
var saved  = localStorage.getItem('adminTheme') || 'light';
if (saved === 'dark') { html.setAttribute('data-theme','dark'); toggle.checked = true; }
toggle.addEventListener('change', function(){
  var t = this.checked ? 'dark' : 'light';
  html.setAttribute('data-theme', t);
  localStorage.setItem('adminTheme', t);
});
document.getElementById('hamburger').addEventListener('click', function(){
  document.getElementById('sidebar').classList.toggle('open');
});
function toggleDD(){ document.getElementById('avDD').classList.toggle('open'); }
document.addEventListener('click', function(e){
  var w = document.getElementById('avWrap');
  if (w && !w.contains(e.target)) document.getElementById('avDD').classList.remove('open');
});

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

/* ── Copy event link ── */
function copyEventLink(btn) {
  var url = btn.dataset.url;
  if (navigator.clipboard && navigator.clipboard.writeText) {
    navigator.clipboard.writeText(url).then(function() {
      var span = btn.querySelector('span');
      var orig = span.textContent;
      span.textContent = 'Copied!';
      btn.style.borderColor = 'var(--green)';
      btn.style.color = 'var(--green)';
      setTimeout(function() { span.textContent = orig; btn.style.borderColor = ''; btn.style.color = ''; }, 2000);
    }).catch(function() { fallbackCopy(url, btn); });
  } else { fallbackCopy(url, btn); }
}
function fallbackCopy(url, btn) {
  var i = document.createElement('input');
  i.value = url; i.style.position = 'fixed'; i.style.opacity = '0';
  document.body.appendChild(i); i.select();
  try { document.execCommand('copy'); btn.querySelector('span').textContent = 'Copied!'; } catch(e) {}
  document.body.removeChild(i);
}

/* ── Toggle loading indicator ── */
document.querySelectorAll('.toggle-wrap input[type=checkbox]').forEach(function(chk) {
  chk.addEventListener('change', function() {
    var wrap = this.closest('.toggle-wrap');
    wrap.style.opacity = '.5';
    wrap.style.pointerEvents = 'none';
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
