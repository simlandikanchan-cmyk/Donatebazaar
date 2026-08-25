import { csrfFetch } from '../shared/api.js';

(function(){
  'use strict';

  /* ── SERVER DATA (from #volunteerApplyData JSON block) ── */
  var data = {};
  (function () {
    var dataEl = document.getElementById('volunteerApplyData');
    if (!dataEl) return;
    try { data = JSON.parse(dataEl.textContent); } catch (e) { /* keep defaults */ }
  })();

  var _cities = data.cities || {};

  var countryEl = document.getElementById('country');
  var stateEl = document.getElementById('state');
  var cityEl = document.getElementById('city');
  var form = document.getElementById('volunteerForm');
  var submitBtn = document.getElementById('volSubmitBtn');
  var successEl = document.getElementById('volSuccess');

  function loadStates(country) {
    if (country === 'India') {
      csrfFetch('/api/v1/states/india')
        .then(function(r) { return r.json(); })
        .then(function(states) {
          stateEl.innerHTML = '<option value="">Select state</option>';
          states.forEach(function(s) {
            var selected = data.oldState === s ? ' selected' : '';
            stateEl.innerHTML += '<option value="' + s + '"' + selected + '>' + s + '</option>';
          });
          stateEl.style.display = '';
          if (stateEl.value) { loadCities(stateEl.value); }
        });
    } else {
      stateEl.innerHTML = '<option value="">Select state</option>';
      stateEl.style.display = 'none';
      cityEl.innerHTML = '<option value="">Select city</option>';
      cityEl.style.display = 'none';
    }
  }

  function loadCities(state) {
    if (!state || !_cities[state]) {
      cityEl.innerHTML = '<option value="">Select city</option>';
      return;
    }
    cityEl.innerHTML = '<option value="">Select city</option>';
    _cities[state].forEach(function(c) {
      var selected = data.oldCity === c ? ' selected' : '';
      cityEl.innerHTML += '<option value="' + c + '"' + selected + '>' + c + '</option>';
    });
    cityEl.style.display = '';
  }

  if (countryEl) {
    countryEl.addEventListener('change', function() { loadStates(this.value); });
    loadStates(countryEl.value);
  }

  if (stateEl) {
    stateEl.addEventListener('change', function() { loadCities(this.value); });
  }

  var stack = document.getElementById('toastStack');

  function toast(opts){
    var type     = opts.type    || 'info';
    var title    = opts.title   || '';
    var message  = opts.message || '';
    var duration = opts.duration || 5000;

    var icons = {
      success: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>',
      error:   '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>',
      warning: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>',
      info:    '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>',
    };

    var t = document.createElement('div');
    t.className = 'toast toast-' + type;
    t.style.setProperty('--toast-dur', (duration/1000) + 's');
    t.setAttribute('role','alert');
    t.innerHTML =
        '<div class="toast-icon">' + (icons[type]||icons.info) + '</div>' +
        '<div class="toast-body">' +
            (title   ? '<div class="toast-title">'+ title   +'</div>' : '') +
            (message ? '<div class="toast-msg">'  + message +'</div>' : '') +
        '</div>' +
        '<button class="toast-close" aria-label="Dismiss">✕</button>';

    t.querySelector('.toast-close').addEventListener('click', function(){ dismiss(t); });
    stack.appendChild(t);

    var timer = setTimeout(function(){ dismiss(t); }, duration);
    t._timer = timer;

    t.addEventListener('mouseenter', function(){ clearTimeout(t._timer); t.style.setProperty('--toast-dur','0s'); t.style.animationPlayState='paused'; });
    t.addEventListener('mouseleave', function(){ t._timer = setTimeout(function(){ dismiss(t); }, 2000); });
  }

  function dismiss(t){
    if (!t.parentNode) return;
    t.classList.add('dismissing');
    setTimeout(function(){ if(t.parentNode) t.parentNode.removeChild(t); }, 320);
  }

  /* Form submit with loading state */
  if (form && submitBtn) {
    form.addEventListener('submit', function() {
      submitBtn.classList.add('loading');
      submitBtn.disabled = true;
    });
  }

  /* FAQ accordion */
  document.querySelectorAll('.vol-faq-q').forEach(function(btn) {
    btn.addEventListener('click', function() {
      var item = this.closest('.vol-faq-item');
      var isOpen = item.classList.contains('open');
      document.querySelectorAll('.vol-faq-item').forEach(function(el) { el.classList.remove('open'); });
      if (!isOpen) { item.classList.add('open'); this.setAttribute('aria-expanded', 'true'); }
      else { this.setAttribute('aria-expanded', 'false'); }
    });
  });

  /* Success state from session */
  if (data.success) {
    setTimeout(function(){
      toast({ type:'success', title:'Application Submitted!', message: data.success, duration:6000 });
      if (form) form.style.display = 'none';
      if (successEl) successEl.classList.add('visible');
    }, 300);
  }

  if (data.error) {
    setTimeout(function(){
      toast({ type:'error', title:'Something went wrong', message: data.error, duration:7000 });
    }, 300);
  }

  if (data.errorsCount) {
    setTimeout(function(){
      toast({
        type: 'error',
        title: 'Please fix ' + data.errorsCount + ' error' + (data.errorsCount > 1 ? 's' : ''),
        message: 'Check the form fields highlighted below.',
        duration: 8000
      });
    }, 300);
  }

  /* Animate hero stats counter */
  function animateCounters() {
    document.querySelectorAll('[data-count]').forEach(function(el) {
      var target = parseInt(el.getAttribute('data-count'), 10);
      var duration = 1500;
      var start = performance.now();
      function update(now) {
        var elapsed = now - start;
        var progress = Math.min(elapsed / duration, 1);
        var eased = 1 - Math.pow(1 - progress, 3);
        el.textContent = Math.floor(target * eased).toLocaleString();
        if (progress < 1) requestAnimationFrame(update);
        else el.textContent = target.toLocaleString() + '+';
      }
      requestAnimationFrame(update);
    });
  }

  var heroObserver = new IntersectionObserver(function(entries) {
    entries.forEach(function(entry) {
      if (entry.isIntersecting) {
        animateCounters();
        heroObserver.disconnect();
      }
    });
  }, { threshold: 0.3 });

  var heroStats = document.querySelector('.vol-hero-stats');
  if (heroStats) heroObserver.observe(heroStats);
})();