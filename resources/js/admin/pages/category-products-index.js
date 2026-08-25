/* ═══════════════════════════════════════════════════════════════════
   Admin Category Products Index page — moved from
   admin/category-products/index.blade.php inline <script>. window.*
   bridges converted to internal functions with data-action delegation;
   Blade routes moved to data-toggle-url / data-delete-url attributes on
   #bulkForm; all logic preserved.
   ═══════════════════════════════════════════════════════════════════ */

(function(){
'use strict';

// flash auto-hide
(function(){var a=document.getElementById('flashAlert');if(!a)return;setTimeout(function(){a.style.transition='opacity .4s,transform .4s';a.style.opacity='0';a.style.transform='translateY(-6px)';setTimeout(function(){a.remove();},400);},4000);})();

// auto-submit search after debounce
var searchTimer;
function autoSubmit(){
  clearTimeout(searchTimer);
  searchTimer=setTimeout(function(){
    document.getElementById('filterForm').submit();
  },500);
}

// lightbox
function openLightbox(src){
  var lb=document.getElementById('lightboxOverlay');
  document.getElementById('lightboxImg').src=src;
  lb.classList.add('open');
  lb.style.display='flex';
}
function closeLightbox(){
  var lb=document.getElementById('lightboxOverlay');
  lb.classList.remove('open');
  lb.style.display='none';
}
document.addEventListener('keydown',function(e){
  if(e.key==='Escape'){closeLightbox();closeModal();closeBulkModal();}
});

// single delete modal
var pendingUrl=null;
function openModal(id,name,url){
  pendingUrl=url;
  document.getElementById('modalProdName').textContent='"'+name+'"';
  document.getElementById('deleteOverlay').classList.add('open');
}
function closeModal(){document.getElementById('deleteOverlay').classList.remove('open');pendingUrl=null;}
function confirmDelete(){if(!pendingUrl)return;var f=document.getElementById('deleteForm');f.action=pendingUrl;f.submit();}
document.getElementById('deleteOverlay').addEventListener('click',function(e){if(e.target===this)closeModal();});

// bulk
var bulkForm=document.getElementById('bulkForm');

function toggleAll(cb){
  document.querySelectorAll('.row-check').forEach(function(c){c.checked=cb.checked;});
  updateBulkBar();
}

function updateBulkBar(){
  var checks=document.querySelectorAll('.row-check:checked');
  var bar=document.getElementById('bulkBar');
  var countEl=document.getElementById('bulkCount');
  var count=checks.length;
  countEl.textContent=count;
  bar.classList.toggle('show',count>0);
}

function bulkAction(action){
  var checks=document.querySelectorAll('.row-check:checked');
  if(!checks.length)return;
  var ids=Array.from(checks).map(function(c){return c.value;});
  var url='';
  if(action==='activate')    url=bulkForm.getAttribute('data-toggle-url');
  else if(action==='deactivate') url=bulkForm.getAttribute('data-toggle-url');
  ids.forEach(function(id){
    var inp=document.createElement('input');
    inp.type='hidden';inp.name='ids[]';inp.value=id;
    bulkForm.appendChild(inp);
  });
  if(action==='activate'||action==='deactivate'){
    var inp=document.createElement('input');
    inp.type='hidden';inp.name='is_active';inp.value=action==='activate'?'1':'0';
    bulkForm.appendChild(inp);
    bulkForm.action=url;
    bulkForm.submit();
  }
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
  var url=bulkForm.getAttribute('data-delete-url');
  checks.forEach(function(c){
    var inp=document.createElement('input');
    inp.type='hidden';inp.name='ids[]';inp.value=c.value;
    bulkForm.appendChild(inp);
  });
  bulkForm.action=url;
  bulkForm.submit();
}

document.getElementById('bulkDeleteOverlay').addEventListener('click',function(e){if(e.target===this)closeBulkModal();});

function clearAllCheckboxes(){
  document.querySelectorAll('.row-check').forEach(function(c){c.checked=false;});
  if(document.getElementById('selectAll'))document.getElementById('selectAll').checked=false;
  updateBulkBar();
}

// prevent Enter key from submitting the form prematurely (use autoSubmit instead)
document.getElementById('filterForm').addEventListener('keypress',function(e){
  if(e.key==='Enter'){e.preventDefault();this.submit();}
});

/* ── delegated actions ── */
document.addEventListener('click',function(e){
  var el=e.target.closest('[data-action]');
  if(!el)return;
  var action=el.getAttribute('data-action');

  if(action==='close-modal'){closeModal();}
  else if(action==='confirm-delete'){confirmDelete();}
  else if(action==='close-bulk-modal'){closeBulkModal();}
  else if(action==='confirm-bulk-delete'){confirmBulkDelete();}
  else if(action==='close-lightbox'){closeLightbox();}
  else if(action==='bulk-action'){bulkAction(el.getAttribute('data-status'));}
  else if(action==='open-bulk-delete'){openBulkDelete();}
  else if(action==='clear-checkboxes'){clearAllCheckboxes();}
  else if(action==='open-lightbox'){openLightbox(el.getAttribute('data-src'));}
  else if(action==='open-modal'){openModal(el.getAttribute('data-id'),el.getAttribute('data-name'),el.getAttribute('data-url'));}
});

document.addEventListener('change',function(e){
  var el=e.target.closest('[data-action]');
  if(!el)return;
  var action=el.getAttribute('data-action');

  if(action==='toggle-all'){toggleAll(el);}
  else if(action==='update-bulk-bar'){updateBulkBar();}
});

/* ── direct listeners (unique form elements) ── */
var searchInput=document.querySelector('#filterForm input[name="search"]');
if(searchInput)searchInput.addEventListener('input',autoSubmit);
var catSelect=document.querySelector('#filterForm select[name="category"]');
if(catSelect)catSelect.addEventListener('change',function(){this.form.submit();});
var statusSelect=document.querySelector('#filterForm select[name="status"]');
if(statusSelect)statusSelect.addEventListener('change',function(){this.form.submit();});

})();