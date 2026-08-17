(function(){
  'use strict';

  var pageData = JSON.parse(document.getElementById('blogsCarouselData').textContent);

  var list = document.getElementById('featuredList');
  var rows = Array.prototype.slice.call(list.querySelectorAll('.feature-row'));
  if (rows.length < 2) return;

  var csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  var saveBtn = document.getElementById('saveOrder');
  var hint = document.getElementById('saveHint');

  function renumber(){
    rows.forEach(function(row, i){
      row.querySelector('.f-pos').textContent = i + 1;
      row.querySelector('.f-up').disabled = (i === 0);
      row.querySelector('.f-down').disabled = (i === rows.length - 1);
    });
  }

  function swap(a, b){
    if (a < 0 || b < 0 || a >= rows.length || b >= rows.length || a === b) return;
    var na = rows[a], nb = rows[b];
    if (a < b) list.insertBefore(nb, na);
    else list.insertBefore(na, nb);
    var t = rows[a]; rows[a] = rows[b]; rows[b] = t;
    renumber();
  }

  list.addEventListener('click', function(e){
    var btn = e.target.closest('.f-up, .f-down');
    if (!btn) return;
    var row = btn.closest('.feature-row');
    var i = rows.indexOf(row);
    if (btn.classList.contains('f-up')) swap(i - 1, i);
    else swap(i, i + 1);
  });

  var dragId = null;
  list.addEventListener('dragstart', function(e){
    var row = e.target.closest('.feature-row');
    if (!row) return;
    dragId = rows.indexOf(row);
    row.classList.add('dragging');
    e.dataTransfer.effectAllowed = 'move';
  });
  list.addEventListener('dragover', function(e){
    e.preventDefault();
    var row = e.target.closest('.feature-row');
    if (!row || dragId === null) return;
    var overId = rows.indexOf(row);
    if (overId !== dragId && overId !== dragId + 1) swap(dragId, overId);
  });
  list.addEventListener('dragend', function(e){
    var row = e.target.closest('.feature-row');
    if (row) row.classList.remove('dragging');
    dragId = null;
  });

  saveBtn.addEventListener('click', function(){
    var order = rows.map(function(row){ return row.dataset.id; });
    saveBtn.disabled = true;
    fetch(pageData.reorderUrl, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
      body: JSON.stringify({ order: order })
    })
    .then(function(r){ return r.json(); })
    .then(function(d){
      if (d.success) {
        hint.textContent = 'Order saved.';
        hint.style.color = 'var(--green)';
      } else {
        hint.textContent = 'Save failed.';
        hint.style.color = 'var(--red)';
      }
      saveBtn.disabled = false;
    })
    .catch(function(){
      hint.textContent = 'Network error.';
      hint.style.color = 'var(--red)';
      saveBtn.disabled = false;
    });
  });

  renumber();
})();
