/* ═══════════════════════════════════════════════════════════════════
   Admin Partnership Index page — moved from admin/partnership/index.blade.php
   inline <script>. window.* bridges converted to internal functions with
   data-action delegation; Blade routes moved to data attributes.
   ═══════════════════════════════════════════════════════════════════ */

import { getCsrfToken } from '../../shared/csrf.js';

(function(){
'use strict';
(function(){var a=document.getElementById('flashAlert');if(!a)return;setTimeout(function(){a.style.transition='opacity .4s,transform .4s';a.style.opacity='0';a.style.transform='translateY(-6px)';setTimeout(function(){a.remove();},400);},4000);})();

var searchTimer;
function autoSubmit(){
  clearTimeout(searchTimer);
  searchTimer=setTimeout(function(){
    document.getElementById('filterForm').submit();
  },500);
}

// single delete modal
var pendingUrl=null;
function openModal(id,name,url){
  pendingUrl=url;
  document.getElementById('modalPartnerName').textContent='"'+name+'"';
  document.getElementById('deleteOverlay').classList.add('open');
}
function closeModal(){document.getElementById('deleteOverlay').classList.remove('open');pendingUrl=null;}
function confirmDelete(){if(!pendingUrl)return;var f=document.getElementById('deleteForm');f.action=pendingUrl;f.submit();}
document.getElementById('deleteOverlay').addEventListener('click',function(e){if(e.target===this)closeModal();});
document.addEventListener('keydown',function(e){if(e.key==='Escape'){closeModal();closeBulkModal();}});

// bulk
var bulkForm=document.getElementById('bulkForm');

function toggleAll(cb){
  document.querySelectorAll('.row-check').forEach(function(c){c.checked=cb.checked;});
  updateBulkBar();
}

function updateBulkBar(){
  var checks=document.querySelectorAll('.row-check:checked');
  var bar=document.getElementById('bulkBar');
  document.getElementById('bulkCount').textContent=checks.length;
  bar.classList.toggle('show',checks.length>0);
}

function bulkAction(status){
  var checks=document.querySelectorAll('.row-check:checked');
  if(!checks.length)return;
  bulkForm.innerHTML='';
  bulkForm.appendChild(csrfInput());
  checks.forEach(function(c){
    var inp=document.createElement('input');
    inp.type='hidden';inp.name='ids[]';inp.value=c.value;
    bulkForm.appendChild(inp);
  });
  var inp=document.createElement('input');
  inp.type='hidden';inp.name='status';inp.value=status;
  bulkForm.appendChild(inp);
  bulkForm.action=bulkForm.getAttribute('data-update-url');
  bulkForm.submit();
}

function openBulkDelete(){
  var checks=document.querySelectorAll('.row-check:checked');
  if(!checks.length)return;
  document.getElementById('bulkCountDisplay').textContent=checks.length;
  document.getElementById('bulkDeleteOverlay').classList.add('open');
}

function closeBulkModal(){
  document.getElementById('bulkDeleteOverlay').classList.remove('open');
}

function confirmBulkDelete(){
  var checks=document.querySelectorAll('.row-check:checked');
  if(!checks.length){closeBulkModal();return;}
  bulkForm.innerHTML='';
  bulkForm.appendChild(csrfInput());
  checks.forEach(function(c){
    var inp=document.createElement('input');
    inp.type='hidden';inp.name='ids[]';inp.value=c.value;
    bulkForm.appendChild(inp);
  });
  bulkForm.action=bulkForm.getAttribute('data-delete-url');
  bulkForm.submit();
}

function csrfInput(){
  var inp=document.createElement('input');
  inp.type='hidden';inp.name='_token';inp.value=getCsrfToken()||'';
  return inp;
}

document.getElementById('bulkDeleteOverlay').addEventListener('click',function(e){if(e.target===this)closeBulkModal();});

function clearAllCheckboxes(){
  document.querySelectorAll('.row-check').forEach(function(c){c.checked=false;});
  if(document.getElementById('selectAll'))document.getElementById('selectAll').checked=false;
  updateBulkBar();
}

/* ── delegated actions ── */
document.addEventListener('click',function(e){
  var el=e.target.closest('[data-action]');
  if(!el)return;
  var action=el.getAttribute('data-action');

  if(action==='close-modal'){closeModal();}
  else if(action==='confirm-delete'){confirmDelete();}
  else if(action==='close-bulk-modal'){closeBulkModal();}
  else if(action==='confirm-bulk-delete'){confirmBulkDelete();}
  else if(action==='bulk-action'){bulkAction(el.getAttribute('data-status'));}
  else if(action==='open-bulk-delete'){openBulkDelete();}
  else if(action==='clear-checkboxes'){clearAllCheckboxes();}
  else if(action==='open-modal'){openModal(el.getAttribute('data-id'),el.getAttribute('data-name'),el.getAttribute('data-url'));}
});

document.addEventListener('change',function(e){
  var el=e.target.closest('[data-action]');
  if(!el)return;
  var action=el.getAttribute('data-action');

  if(action==='toggle-all'){toggleAll(el);}
  else if(action==='update-bulk-bar'){updateBulkBar();}
});

/* ── direct listeners (unique elements) ── */
var searchInput=document.querySelector('#filterForm input[name="search"]');
if(searchInput)searchInput.addEventListener('input',autoSubmit);
var statusSelect=document.querySelector('#filterForm select[name="status"]');
if(statusSelect)statusSelect.addEventListener('change',function(){this.form.submit();});

document.getElementById('filterForm').addEventListener('keypress',function(e){
  if(e.key==='Enter'){e.preventDefault();this.submit();}
});
})();