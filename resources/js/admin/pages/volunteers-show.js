'use strict';
(function(){
  var form = document.getElementById('assignForm');
  if (!form) return;

  var sel  = document.getElementById('assignEvent');
  var btn  = document.getElementById('assignBtn');
  var hint = document.getElementById('assignHint');
  var startInp = document.getElementById('assignStart');
  var endInp   = document.getElementById('assignEnd');
  var baseUrl  = form.getAttribute('data-url');

  if (sel) {
    sel.addEventListener('change', function(){
      var val = this.value;
      if (val) {
        var opt = this.options[this.selectedIndex];
        btn.disabled = false;
        hint.textContent = 'Will be assigned to event #' + val;
        form.action = baseUrl + '/' + val + '/assign-volunteer';
        var d = opt.getAttribute('data-date');
        if (d) {
          startInp.value = d;
          endInp.value = d;
        }
      } else {
        btn.disabled = true;
        hint.textContent = 'Select an event above';
        form.action = '';
        startInp.value = '';
        endInp.value = '';
      }
    });
  }
})();
