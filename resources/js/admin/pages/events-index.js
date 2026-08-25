/* ═══════════════════════════════════════════════════════════════════
   Admin Events Index page — moved from admin/events/index.blade.php
   inline <script>. window.* bridges converted to internal functions with
   data-action delegation; all logic preserved verbatim.
   ═══════════════════════════════════════════════════════════════════ */

import { csrfFetch } from '../../shared/api.js';

(function(){
'use strict';

// ---------- Quick page-local search (topbar) ----------
var searchEl = document.getElementById('liveSearch');
var clearBtn = document.getElementById('liveSearchClear');
var kbdEl = document.getElementById('liveSearchKbd');

function applyLiveFilter(){
  var q = searchEl.value.toLowerCase().trim();
  clearBtn.classList.toggle('show', q.length>0);
  kbdEl.classList.toggle('hide', q.length>0);
  document.querySelectorAll('#eventsTable tbody tr[data-name], .ev-cards .ev-card[data-name]').forEach(function(row){
    row.style.display = (!q || row.dataset.name.includes(q)) ? '' : 'none';
  });
}

if (searchEl) {
  var st;
  searchEl.addEventListener('input', function(){
    clearTimeout(st);
    st = setTimeout(applyLiveFilter, 160);
  });
  // Enter commits as a real server-side search (reloads with full dataset + pagination)
  searchEl.addEventListener('keydown', function(e){
    if(e.key === 'Enter'){
      e.preventDefault();
      var fs = document.getElementById('filterSearch');
      if(fs){ fs.value = searchEl.value; document.getElementById('filterForm').submit(); }
    }
  });
  applyLiveFilter();
}

function clearLiveSearch(){
  searchEl.value='';
  searchEl.focus();
  applyLiveFilter();
}
clearBtn.addEventListener('click', clearLiveSearch);

document.addEventListener('keydown', function(e){
  var tag = (e.target.tagName||'').toLowerCase();
  if(e.key==='/' && tag!=='input' && tag!=='textarea'){
    e.preventDefault();
    searchEl.focus();
  }
  if(e.key==='Escape' && tag==='input' && e.target.id==='liveSearch'){
    clearLiveSearch();
  }
});

// ---------- Client-side sort (current page only) ----------
document.getElementById('sortSelect').addEventListener('change', function(){
  var v = this.value;
  if(v==='default') return;
  var cmp;
  if(v==='date-soon')       cmp = function(a,b){ return Number(a.dataset.ts) - Number(b.dataset.ts); };
  else if(v==='date-far')   cmp = function(a,b){ return Number(b.dataset.ts) - Number(a.dataset.ts); };
  else if(v==='raised-high')cmp = function(a,b){ return Number(b.dataset.raised) - Number(a.dataset.raised); };
  else if(v==='raised-low') cmp = function(a,b){ return Number(a.dataset.raised) - Number(b.dataset.raised); };
  else if(v==='participants')cmp= function(a,b){ return Number(b.dataset.participants) - Number(a.dataset.participants); };
  else if(v==='title')      cmp = function(a,b){ return (a.dataset.name||'').localeCompare(b.dataset.name||''); };
  else return;

  var tbody = document.querySelector('#eventsTable tbody');
  var rows = Array.from(tbody.querySelectorAll('tr[data-id]'));
  rows.sort(cmp).forEach(function(r){ tbody.appendChild(r); });

  var cardsWrap = document.querySelector('.ev-cards');
  var cards = Array.from(cardsWrap.querySelectorAll('.ev-card[data-id]'));
  cards.sort(cmp).forEach(function(c){ cardsWrap.appendChild(c); });
});

// ---------- Bulk selection ----------
var selected = new Set();

function toggleRowSelect(cb){
  var el = cb.closest('[data-id]');
  var id = el.dataset.id;
  if(cb.checked){ selected.add(id); el.classList.add('row-selected'); }
  else{ selected.delete(id); el.classList.remove('row-selected'); }
  syncCheckboxesForId(id, cb.checked);
  updateBulkBar();
  updateSelectAllState();
}

function syncCheckboxesForId(id, checked){
  document.querySelectorAll('[data-id="'+id+'"] .row-check').forEach(function(cb){ cb.checked = checked; });
}

function toggleSelectAll(cb){
  var visibleRows = Array.from(document.querySelectorAll('#eventsTable tbody tr[data-id]')).filter(function(r){ return r.style.display !== 'none'; });
  visibleRows.forEach(function(r){
    var id = r.dataset.id;
    var rowCb = r.querySelector('.row-check');
    if(cb.checked){ selected.add(id); r.classList.add('row-selected'); if(rowCb) rowCb.checked=true; }
    else{ selected.delete(id); r.classList.remove('row-selected'); if(rowCb) rowCb.checked=false; }
    syncCheckboxesForId(id, cb.checked);
  });
  updateBulkBar();
}

function updateSelectAllState(){
  var selAll = document.getElementById('selectAll');
  if(!selAll) return;
  var visibleRows = Array.from(document.querySelectorAll('#eventsTable tbody tr[data-id]')).filter(function(r){ return r.style.display !== 'none'; });
  selAll.checked = visibleRows.length>0 && visibleRows.every(function(r){ return selected.has(r.dataset.id); });
}

function updateBulkBar(){
  var bar = document.getElementById('bulkBar');
  document.getElementById('bulkCount').textContent = selected.size + ' selected';
  bar.classList.toggle('show', selected.size>0);
}

function clearSelection(){
  selected.clear();
  document.querySelectorAll('.row-check').forEach(function(cb){ cb.checked=false; });
  document.querySelectorAll('.row-selected').forEach(function(r){ r.classList.remove('row-selected'); });
  var selAll = document.getElementById('selectAll');
  if(selAll) selAll.checked=false;
  updateBulkBar();
}

function openBulkConfirm(){
  if(selected.size===0) return;
  if(!confirm('Delete '+selected.size+' selected event(s)? This cannot be undone.')) return;
  var ids = Array.from(selected);
  var reqs = ids.map(function(id){
    var el = document.querySelector('[data-id="'+id+'"][data-delete-url]');
    var url = el ? el.dataset.deleteUrl : null;
    if(!url) return Promise.resolve();
    var fd = new FormData();
    fd.append('_method', 'DELETE');
    return csrfFetch(url, { method:'POST', body:fd });
  });
  Promise.all(reqs).then(function(){ window.location.reload(); }).catch(function(){ window.location.reload(); });
}

/* ── delegated actions ── */
document.addEventListener('click', function (e) {
  var el = e.target.closest('[data-action]');
  if (!el) return;
  var action = el.getAttribute('data-action');

  if(action==='open-bulk-confirm'){openBulkConfirm();}
  else if(action==='clear-selection'){clearSelection();}
});

document.addEventListener('change', function (e) {
  var el = e.target.closest('[data-action]');
  if (!el) return;
  var action = el.getAttribute('data-action');

  if(action==='toggle-select-all'){toggleSelectAll(el);}
  else if(action==='toggle-row-select'){toggleRowSelect(el);}
});

})();