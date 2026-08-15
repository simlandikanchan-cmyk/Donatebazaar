/* ═══════════════════════════════════════════════════════════════════
   Admin Categories Index page — moved from admin/categories/index.blade.php
   inline <script>. window.* bridges converted to internal functions with
   data-action delegation; all logic preserved.
   ═══════════════════════════════════════════════════════════════════ */

(function(){
'use strict';

// ---------- Toast (page-local style, intentionally distinct) ----------
function toast(msg,type){
  var t=document.createElement('div');
  t.style.cssText='position:fixed;top:20px;right:20px;z-index:9999;display:flex;align-items:center;gap:10px;padding:13px 16px;border-radius:14px;font-size:13px;font-weight:500;color:#fff;min-width:240px;box-shadow:0 10px 30px rgba(0,0,0,.25);animation:fadeUp .3s ease both;'+(type==='error'?'background:linear-gradient(135deg,#dc2626,#f04444);':'background:linear-gradient(135deg,#059669,#10b981);');
  t.innerHTML=(type==='error'?'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:15px;height:15px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>':'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:15px;height:15px;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>')+'<span>'+msg+'</span><button style="margin-left:auto;background:transparent;border:none;color:inherit;opacity:.7;cursor:pointer;font-size:14px;" onclick="this.parentElement.remove()">✕</button>';
  document.body.appendChild(t);
  setTimeout(function(){t.style.transition='opacity .3s,transform .3s';t.style.opacity='0';t.style.transform='translateX(20px)';setTimeout(function(){t.remove();},300);},3800);
}

// ---------- Inline status toggle ----------
function updateStatusStat(toActive){
  var a=document.getElementById('statActive'),i=document.getElementById('statInactive');
  if(a&&i){
    var av=parseInt(a.querySelector('.stat-val').textContent||'0',10);
    var iv=parseInt(i.querySelector('.stat-val').textContent||'0',10);
    a.querySelector('.stat-val').textContent=toActive?av+1:av-1;
    i.querySelector('.stat-val').textContent=toActive?iv-1:iv+1;
  }
}

function toggleStatus(id,toActive){
  var row=document.querySelector('[data-id="'+id+'"]');
  var url=row?row.getAttribute('data-toggle-url'):null;
  var txt=document.getElementById('statusTxt-'+id);
  if(!url)return;
  // optimistic UI
  if(row)row.setAttribute('data-status',toActive?'active':'inactive');
  if(txt){txt.textContent=toActive?'Active':'Inactive';txt.className='cat-toggle-txt '+(toActive?'active':'inactive');}
  updateStatusStat(toActive);
  var token=document.querySelector('#deleteForm input[name="_token"]').value;
  var fd=new FormData();fd.append('_token',token);
  fetch(url,{method:'POST',body:fd,headers:{'X-Requested-With':'XMLHttpRequest'}})
    .then(function(r){if(!r.ok)throw new Error('Failed');toast('Status updated','ok');})
    .catch(function(){ // rollback
      if(row)row.setAttribute('data-status',toActive?'inactive':'active');
      if(txt){txt.textContent=toActive?'Inactive':'Active';txt.className='cat-toggle-txt '+(toActive?'inactive':'active');}
      updateStatusStat(!toActive);
      toast('Could not update status','error');
    });
}

// ---------- View toggle ----------
var currentView=localStorage.getItem('catView')||'table';
function applyView(v){
  document.getElementById('tableView').style.display=v==='table'?'':'none';
  document.getElementById('gridView').style.display=v==='grid'?'':'none';
  document.querySelectorAll('.view-btn').forEach(function(b){b.classList.remove('on');});
  document.getElementById(v==='table'?'viewTable':'viewGrid').classList.add('on');
}
function setView(v){currentView=v;localStorage.setItem('catView',v);applyView(v);filterTable();}
applyView(currentView);

// ---------- Filters ----------
var activeFilter='all';
var campaignFilter='all';
var currentSort='default';
var sortDir={};

var filterBtnMap={all:'fAll',active:'fActive',inactive:'fInactive'};
var campaignBtnMap={all:'cAll',with:'cWith',without:'cWithout'};

function setFilter(f,btn){
  activeFilter=f;
  document.querySelectorAll('#fAll,#fActive,#fInactive').forEach(function(b){b.classList.remove('on');});
  if(btn)btn.classList.add('on');
  document.querySelectorAll('.stat').forEach(function(s){s.classList.remove('stat-on');});
  var map={all:'statAll',active:'statActive',inactive:'statInactive'};
  document.getElementById(map[f]).classList.add('stat-on');
  updateChips();
  filterTable();
}

function setCampaignFilter(f,btn){
  campaignFilter=f;
  document.querySelectorAll('#cAll,#cWith,#cWithout').forEach(function(b){b.classList.remove('on');});
  if(btn)btn.classList.add('on');
  updateChips();
  filterTable();
}

function clearSearch(){
  var input=document.getElementById('searchInput');
  input.value='';
  input.focus();
  filterTable();
}

function clearAllFilters(){
  document.getElementById('searchInput').value='';
  setFilter('all',document.getElementById('fAll'));
  setCampaignFilter('all',document.getElementById('cAll'));
}

function updateChips(){
  var q=document.getElementById('searchInput').value.trim();
  var wrap=document.getElementById('activeFilters');
  var any=false;

  var chipSearch=document.getElementById('chipSearch');
  if(q){chipSearch.style.display='inline-flex';document.getElementById('chipSearchText').textContent=q;any=true;}
  else{chipSearch.style.display='none';}

  var chipStatus=document.getElementById('chipStatus');
  if(activeFilter!=='all'){chipStatus.style.display='inline-flex';document.getElementById('chipStatusText').textContent=activeFilter==='active'?'Active only':'Inactive only';any=true;}
  else{chipStatus.style.display='none';}

  var chipCampaigns=document.getElementById('chipCampaigns');
  if(campaignFilter!=='all'){chipCampaigns.style.display='inline-flex';document.getElementById('chipCampaignsText').textContent=campaignFilter==='with'?'With campaigns':'Without campaigns';any=true;}
  else{chipCampaigns.style.display='none';}

  wrap.classList.toggle('hide',!any);
}

function filterTable(){
  var q=document.getElementById('searchInput').value.toLowerCase().trim();
  document.getElementById('searchClearBtn').classList.toggle('show',q.length>0);
  document.getElementById('searchKbd').classList.toggle('hide',q.length>0);
  updateChips();

  var tableRows=document.querySelectorAll('.cat-row');
  var gridItems=document.querySelectorAll('#gridBody .cat-grid-item');
  var visible=0;

  function matches(el){
    var nameOk=!q||(el.getAttribute('data-name')||'').includes(q);
    var statusOk=activeFilter==='all'||el.getAttribute('data-status')===activeFilter;
    var count=parseInt(el.getAttribute('data-campaigns')||'0',10);
    var campOk=campaignFilter==='all'||(campaignFilter==='with'?count>0:count===0);
    return nameOk&&statusOk&&campOk;
  }

  tableRows.forEach(function(r){var s=matches(r);r.style.display=s?'':'none';if(s)visible++;});
  gridItems.forEach(function(r){r.style.display=matches(r)?'':'none';});

  document.getElementById('visibleCount').textContent=visible+' total';

  var noRes=document.getElementById('noResultsState');
  if(noRes){noRes.style.display=(visible===0&&tableRows.length>0)?'':'none';}
  var noResGrid=document.getElementById('noResultsStateGrid');
  if(noResGrid){noResGrid.style.display=(visible===0&&gridItems.length>0)?'':'none';}

  updateSelectAllState();
}

// ---------- Sorting ----------
function setSort(v){
  currentSort='default';
  document.querySelectorAll('.sortable').forEach(function(th){th.classList.remove('active-sort','desc');});
  if(v==='default')return;
  var map={'name-asc':['name',1],'name-desc':['name',-1],'campaigns-desc':['campaigns',-1],'campaigns-asc':['campaigns',1],'status':['status',1]};
  var conf=map[v];
  if(conf)applySort(conf[0],conf[1]);
}

function sortTable(col){
  var dir=sortDir[col]===1?-1:1;
  sortDir={};sortDir[col]=dir;
  document.getElementById('sortSelect').value='default';
  applySort(col,dir);
}

function applySort(col,dir){
  document.querySelectorAll('.sortable').forEach(function(th){
    th.classList.toggle('active-sort',th.getAttribute('data-sort')===col);
    th.classList.toggle('desc',th.getAttribute('data-sort')===col&&dir===-1);
  });
  var tb=document.getElementById('tableBody');
  var rows=Array.from(tb.querySelectorAll('tr.cat-row'));
  rows.sort(function(a,b){
    var va,vb;
    if(col==='campaigns'){va=parseInt(a.getAttribute('data-campaigns')||'0',10);vb=parseInt(b.getAttribute('data-campaigns')||'0',10);return dir*(va-vb);}
    if(col==='status'){va=a.getAttribute('data-status');vb=b.getAttribute('data-status');return dir*va.localeCompare(vb);}
    va=a.getAttribute('data-name')||'';vb=b.getAttribute('data-name')||'';
    return dir*va.localeCompare(vb);
  });
  rows.forEach(function(r){tb.appendChild(r);});

  var gb=document.getElementById('gridBody');
  if(gb){
    var gitems=Array.from(gb.querySelectorAll('.cat-grid-item'));
    gitems.sort(function(a,b){
      var va,vb;
      if(col==='campaigns'){va=parseInt(a.getAttribute('data-campaigns')||'0',10);vb=parseInt(b.getAttribute('data-campaigns')||'0',10);return dir*(va-vb);}
      if(col==='status'){va=a.getAttribute('data-status');vb=b.getAttribute('data-status');return dir*va.localeCompare(vb);}
      va=a.getAttribute('data-name')||'';vb=b.getAttribute('data-name')||'';
      return dir*va.localeCompare(vb);
    });
    gitems.forEach(function(r){gb.appendChild(r);});
  }
}

// ---------- Keyboard shortcuts ----------
document.addEventListener('keydown',function(e){
  var tag=(e.target.tagName||'').toLowerCase();
  if(e.key==='/'&&tag!=='input'&&tag!=='textarea'){
    e.preventDefault();
    document.getElementById('searchInput').focus();
  }
  if(e.key==='Escape'&&tag==='input'&&e.target.id==='searchInput'){
    clearSearch();
  }
});

// ---------- Bulk selection ----------
var selected=new Set();

function toggleRowSelect(cb){
  var row=cb.closest('[data-id]');
  var id=row.getAttribute('data-id');
  if(cb.checked){selected.add(id);row.classList.add('row-selected');}
  else{selected.delete(id);row.classList.remove('row-selected');}
  syncCheckboxesForId(id,cb.checked);
  updateBulkBar();
  updateSelectAllState();
}

function syncCheckboxesForId(id,checked){
  document.querySelectorAll('[data-id="'+id+'"] .row-check').forEach(function(cb){cb.checked=checked;});
}

function toggleSelectAll(cb){
  var visibleRows=Array.from(document.querySelectorAll('.cat-row')).filter(function(r){return r.style.display!=='none';});
  visibleRows.forEach(function(r){
    var id=r.getAttribute('data-id');
    var rowCb=r.querySelector('.row-check');
    if(cb.checked){selected.add(id);r.classList.add('row-selected');if(rowCb)rowCb.checked=true;}
    else{selected.delete(id);r.classList.remove('row-selected');if(rowCb)rowCb.checked=false;}
    syncCheckboxesForId(id,cb.checked);
  });
  updateBulkBar();
}

function updateSelectAllState(){
  var selAll=document.getElementById('selectAll');
  if(!selAll)return;
  var visibleRows=Array.from(document.querySelectorAll('.cat-row')).filter(function(r){return r.style.display!=='none';});
  var allChecked=visibleRows.length>0&&visibleRows.every(function(r){return selected.has(r.getAttribute('data-id'));});
  selAll.checked=allChecked;
}

function updateBulkBar(){
  var bar=document.getElementById('bulkBar');
  var count=selected.size;
  document.getElementById('bulkCount').textContent=count+' selected';
  bar.classList.toggle('show',count>0);
}

function clearSelection(){
  selected.clear();
  document.querySelectorAll('.row-check').forEach(function(cb){cb.checked=false;});
  document.querySelectorAll('.row-selected').forEach(function(r){r.classList.remove('row-selected');});
  var selAll=document.getElementById('selectAll');
  if(selAll)selAll.checked=false;
  updateBulkBar();
}

// ---------- Delete modal (single + bulk) ----------
var pendingUrl=null;
var bulkMode=false;

function openModal(id,name,url){
  bulkMode=false;
  pendingUrl=url;
  document.getElementById('modalTitle').textContent='Delete Category?';
  document.getElementById('modalMsg').innerHTML='This will permanently remove <strong id="modalCatName">"'+name+'"</strong>. Campaigns using this category may be affected.';
  document.getElementById('deleteOverlay').classList.add('open');
}

function openBulkModal(){
  bulkMode=true;
  var ids=Array.from(selected);
  var withCamp=0;
  ids.forEach(function(id){
    var row=document.querySelector('[data-id="'+id+'"]');
    if(row&&parseInt(row.getAttribute('data-campaigns')||'0',10)>0)withCamp++;
  });
  var deletable=ids.length-withCamp;
  document.getElementById('modalTitle').textContent='Delete '+deletable+' Categor'+(deletable===1?'y':'ies')+'?';
  var msg='This will permanently remove <strong>'+deletable+' selected categor'+(deletable===1?'y':'ies')+'</strong>.';
  if(withCamp>0)msg+=' <span style="color:var(--amber);font-weight:600;">'+withCamp+' linked to campaigns will be skipped.</span>';
  document.getElementById('modalMsg').innerHTML=msg;
  document.getElementById('deleteOverlay').classList.add('open');
}

function closeModal(){
  document.getElementById('deleteOverlay').classList.remove('open');
  pendingUrl=null;
  bulkMode=false;
}

function confirmDelete(){
  if(bulkMode){
    var token=document.querySelector('#deleteForm input[name="_token"]').value;
    var ids=Array.from(selected);
    var reqs=ids.filter(function(id){
      var row=document.querySelector('[data-id="'+id+'"]');
      return row && parseInt(row.getAttribute('data-campaigns')||'0',10)===0;
    }).map(function(id){
      var row=document.querySelector('[data-id="'+id+'"]');
      var url=row?row.getAttribute('data-delete-url'):null;
      if(!url)return null;
      var fd=new FormData();
      fd.append('_token',token);
      fd.append('_method','DELETE');
      return fetch(url,{method:'POST',body:fd,headers:{'X-Requested-With':'XMLHttpRequest'}});
    }).filter(Boolean);
    Promise.all(reqs).then(function(){window.location.reload();}).catch(function(){window.location.reload();});
    return;
  }
  if(!pendingUrl)return;
  var f=document.getElementById('deleteForm');
  f.action=pendingUrl;
  f.submit();
}

// ---------- delegated actions ----------
document.addEventListener('click',function(e){
  var el=e.target.closest('[data-action]');
  if(!el)return;
  var action=el.getAttribute('data-action');

  if(action==='close-modal'){closeModal();}
  else if(action==='confirm-delete'){confirmDelete();}
  else if(action==='set-filter'){setFilter(el.getAttribute('data-filter'),document.getElementById(filterBtnMap[el.getAttribute('data-filter')]));}
  else if(action==='set-campaign-filter'){setCampaignFilter(el.getAttribute('data-filter'),document.getElementById(campaignBtnMap[el.getAttribute('data-filter')]));}
  else if(action==='clear-search'){clearSearch();}
  else if(action==='clear-all'){clearAllFilters();}
  else if(action==='set-view'){setView(el.getAttribute('data-view'));}
  else if(action==='sort-table'){sortTable(el.getAttribute('data-col'));}
  else if(action==='open-modal'){openModal(el.getAttribute('data-id'),el.getAttribute('data-name'),el.getAttribute('data-url'));}
  else if(action==='open-bulk-modal'){openBulkModal();}
  else if(action==='clear-selection'){clearSelection();}
});

document.addEventListener('change',function(e){
  var el=e.target.closest('[data-action]');
  if(!el)return;
  var action=el.getAttribute('data-action');

  if(action==='toggle-status'){toggleStatus(el.getAttribute('data-id'),el.checked);}
  else if(action==='toggle-row-select'){toggleRowSelect(el);}
  else if(action==='toggle-select-all'){toggleSelectAll(el);}
});

// ---------- direct listeners (unique elements) ----------
var searchInput=document.getElementById('searchInput');
if(searchInput)searchInput.addEventListener('input',filterTable);
var sortSelect=document.getElementById('sortSelect');
if(sortSelect)sortSelect.addEventListener('change',function(){setSort(this.value);});

// init
filterTable();
})();