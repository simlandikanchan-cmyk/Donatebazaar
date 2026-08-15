/* ═══════════════════════════════════════════════════════════════════
   Admin Messages Index page — moved from admin/messages/index.blade.php
   inline <script>. Blade route directives moved to data-bulk-url /
   data-toggle-url attributes on #bulkBar; session-success toast now
   read from #toastWrap data-success; ftab-select onchange and
   ab-delete onclick moved to a direct listener / data-action
   delegation.
   ═══════════════════════════════════════════════════════════════════ */

(function(){
'use strict';

var csrf   = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
var bulkBar = document.getElementById('bulkBar');
var bulkUrl = bulkBar.getAttribute('data-bulk-url');
var toggleUrl = bulkBar.getAttribute('data-toggle-url');

/* live totals (server-provided, adjusted on actions) */
var totals = {
  total:  parseInt(document.getElementById('statTotal').textContent, 10) || 0,
  unread: parseInt(document.getElementById('statUnread').textContent, 10) || 0,
  read:   parseInt(document.getElementById('statRead').textContent, 10) || 0,
  today:  parseInt(document.getElementById('statToday').textContent, 10) || 0
};

/* sidebar unread chip */
(function(){
  var chip = document.getElementById('sidebarUnread');
  if(chip && totals.unread > 0){
    chip.textContent = totals.unread;
    chip.style.display = '';
  } else if(chip){
    chip.style.display = 'none';
  }
})();

var activeFilter = 'all';
var activeSort   = 'newest';
var activePeriod = 'all';
var activeSubj   = 'all';
var dateFrom = '', dateTo = '';
var rows  = Array.from(document.querySelectorAll('#tbody tr[data-id]'));
var tbody = document.getElementById('tbody');
var noRow = document.getElementById('noResultsRow');

function todayStr(){ return new Date().toISOString().slice(0,10); }
function yesterdayStr(){ var d=new Date(); d.setDate(d.getDate()-1); return d.toISOString().slice(0,10); }
function weekStartStr(){ var d=new Date(); d.setDate(d.getDate()-d.getDay()); return d.toISOString().slice(0,10); }
function monthStartStr(){ var d=new Date(); return d.getFullYear()+'-'+String(d.getMonth()+1).padStart(2,'0')+'-01'; }
function getPeriodRange(p){
  var t = todayStr();
  if(p==='today')     return [t,t];
  if(p==='yesterday') return [yesterdayStr(),yesterdayStr()];
  if(p==='week')      return [weekStartStr(),t];
  if(p==='month')     return [monthStartStr(),t];
  if(p==='custom')    return [dateFrom,dateTo];
  return ['',''];
}

function applyFilters(){
  var q     = (document.getElementById('searchInput').value || '').toLowerCase().trim();
  var range = getPeriodRange(activePeriod);
  var from  = range[0], to = range[1];
  var vis   = 0;

  rows.forEach(function(r){
    var mF    = activeFilter === 'all' || r.dataset.status === activeFilter;
    var mS    = !q || (r.dataset.search || '').includes(q);
    var mSubj = activeSubj === 'all' || r.dataset.subject === activeSubj;
    var mDate = true;
    if(from && to){
      var ds = r.dataset.datestr || '';
      mDate = ds >= from && ds <= to;
    } else if(from){
      mDate = (r.dataset.datestr || '') >= from;
    } else if(to){
      mDate = (r.dataset.datestr || '') <= to;
    }
    var show = mF && mS && mSubj && mDate;
    r.classList.toggle('row-hidden', !show);
    if(show) vis++;
  });

  sortRows();

  var e2 = document.getElementById('cntVisF');
  if(e2) e2.textContent = vis;
  if(noRow) noRow.style.display = (vis === 0 && rows.length > 0) ? '' : 'none';
}

function sortRows(){
  var visible = rows.filter(function(r){ return !r.classList.contains('row-hidden'); });
  visible.sort(function(a,b){
    if(activeSort === 'newest')  return Number(b.dataset.ts) - Number(a.dataset.ts);
    if(activeSort === 'oldest')  return Number(a.dataset.ts) - Number(b.dataset.ts);
    if(activeSort === 'name_az') return (a.dataset.name||'').localeCompare(b.dataset.name||'');
    if(activeSort === 'name_za') return (b.dataset.name||'').localeCompare(a.dataset.name||'');
    return 0;
  });
  visible.forEach(function(r){ tbody.appendChild(r); });
  if(noRow) tbody.appendChild(noRow);
}

/* filter + search wiring */
document.querySelectorAll('.ftab').forEach(function(tab){
  tab.addEventListener('click', function(){
    document.querySelectorAll('.ftab').forEach(function(t){ t.classList.remove('on'); });
    this.classList.add('on');
    activeFilter = this.dataset.filter;
    applyFilters();
  });
});

document.querySelector('.ftab-select').addEventListener('change', function(){
  var btn = document.querySelector('.ftab[data-filter="'+this.value+'"]');
  if(btn) btn.click();
});

var st;
document.getElementById('searchInput').addEventListener('input', function(){
  clearTimeout(st);
  st = setTimeout(applyFilters, 180);
});

document.getElementById('filterPeriod').addEventListener('change', function(){
  activePeriod = this.value;
  var g = document.getElementById('customDateGroup');
  if(this.value === 'custom'){ g.style.display = 'flex'; }
  else { g.style.display = 'none'; dateFrom = ''; dateTo = ''; }
  applyFilters();
});
document.getElementById('dateFrom').addEventListener('change', function(){ dateFrom = this.value; applyFilters(); });
document.getElementById('dateTo').addEventListener('change',   function(){ dateTo   = this.value; applyFilters(); });
document.getElementById('filterSort').addEventListener('change', function(){ activeSort = this.value; applyFilters(); });
document.getElementById('filterSubject').addEventListener('change', function(){ activeSubj = this.value; applyFilters(); });
document.getElementById('filterReset').addEventListener('click', function(){
  activeFilter = 'all'; activePeriod = 'all'; activeSort = 'newest'; activeSubj = 'all';
  dateFrom = ''; dateTo = '';
  document.getElementById('filterPeriod').value  = 'all';
  document.getElementById('filterSort').value    = 'newest';
  document.getElementById('filterSubject').value = 'all';
  document.getElementById('searchInput').value   = '';
  document.getElementById('customDateGroup').style.display = 'none';
  document.getElementById('dateFrom').value = '';
  document.getElementById('dateTo').value   = '';
  document.querySelectorAll('.ftab').forEach(function(t){ t.classList.remove('on'); });
  document.querySelector('.ftab[data-filter="all"]').classList.add('on');
  applyFilters();
});
document.getElementById('thDate').addEventListener('click', function(){
  if(activeSort === 'newest'){ activeSort = 'oldest'; }
  else if(activeSort === 'oldest'){ activeSort = 'newest'; }
  else { activeSort = 'newest'; }
  this.classList.toggle('sort-asc', activeSort === 'oldest');
  this.classList.toggle('sort-desc', activeSort === 'newest');
  document.getElementById('filterSort').value = activeSort;
  applyFilters();
});

/* selection + bulk */
function selectedIds(){
  return Array.from(document.querySelectorAll('.row-check:checked')).map(function(c){ return c.value; });
}
function syncBulkBar(){
  var ids = selectedIds();
  document.getElementById('bulkCount').textContent = ids.length;
  bulkBar.classList.toggle('show', ids.length > 0);
  var all = document.querySelectorAll('.row-check').length;
  var checked = ids.length;
  document.getElementById('selectAll').checked = all > 0 && checked === all;
  document.getElementById('selectAll').indeterminate = checked > 0 && checked < all;
}
document.getElementById('selectAll').addEventListener('change', function(){
  document.querySelectorAll('.row-check').forEach(function(c){ c.checked = document.getElementById('selectAll').checked; });
  syncBulkBar();
});
document.querySelectorAll('.row-check').forEach(function(c){
  c.addEventListener('change', syncBulkBar);
});
document.getElementById('bulkClear').addEventListener('click', function(){
  document.querySelectorAll('.row-check').forEach(function(c){ c.checked = false; });
  syncBulkBar();
});

function postBulk(action, ids, onDone){
  fetch(bulkUrl, {
    method: 'POST',
    headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': csrf, 'Accept':'application/json' },
    body: JSON.stringify({ ids: ids, action: action })
  })
  .then(function(r){ return r.json(); })
  .then(function(d){ if(d.ok && onDone) onDone(d); else toast('Something went wrong.', 'error'); })
  .catch(function(){ toast('Network error.', 'error'); });
}

document.getElementById('bulkRead').addEventListener('click', function(){
  var ids = selectedIds();
  if(!ids.length) return;
  postBulk('read', ids, function(){
    ids.forEach(function(id){
      var tr = document.querySelector('#tbody tr[data-id="'+id+'"]');
      if(tr && tr.dataset.status === 'new'){ setRowRead(tr, true); }
    });
    syncBulkBar();
    toast(ids.length + ' message' + (ids.length===1?'':'s') + ' marked as read.', 'success');
  });
});
document.getElementById('bulkDelete').addEventListener('click', function(){
  var ids = selectedIds();
  if(!ids.length) return;
  if(!confirm('Delete ' + ids.length + ' selected message(s)?')) return;
  /* adjust totals before removing rows from the DOM */
  ids.forEach(function(id){
    var tr = document.querySelector('#tbody tr[data-id="'+id+'"]');
    if(tr && tr.dataset.status === 'new'){ totals.unread = Math.max(0, totals.unread - 1); }
    else { totals.read = Math.max(0, totals.read - 1); }
    totals.total = Math.max(0, totals.total - 1);
  });
  postBulk('delete', ids, function(d){
    ids.forEach(function(id){
      var tr = document.querySelector('#tbody tr[data-id="'+id+'"]');
      if(tr) tr.remove();
    });
    rows = Array.from(document.querySelectorAll('#tbody tr[data-id]'));
    writeTotals();
    syncBulkBar();
    applyFilters();
    toast(d.msg || 'Deleted.', 'success');
  });
});

/* per-row mark read / unread toggle */
function setRowRead(tr, isRead){
  tr.dataset.status = isRead ? 'read' : 'new';
  var name = tr.querySelector('.sender-name');
  var av   = tr.querySelector('.row-av .unread-dot');
  var badge = tr.querySelector('.badge');
  var toggle = tr.querySelector('.ab-toggle');
  tr.classList.toggle('unread', !isRead);
  if(isRead && av){ av.remove(); }
  if(!isRead && !av){
    var nd = document.createElement('span'); nd.className = 'unread-dot'; tr.querySelector('.row-av').appendChild(nd);
  }
  if(badge){
    badge.className = 'badge b-' + (isRead ? 'read' : 'new');
    badge.innerHTML = '<span class="badge-dot"></span>' + (isRead ? 'Read' : 'New');
  }
  if(toggle){
    toggle.dataset.read = isRead ? '1' : '0';
    toggle.innerHTML = isRead
      ? '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9l9 6 9-6"/></svg> Unread'
      : '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Read';
  }
}
function writeTotals(){
  document.getElementById('statUnread').textContent = totals.unread;
  document.getElementById('statRead').textContent   = totals.read;
  document.getElementById('statTotal').textContent  = totals.total;
  document.getElementById('cntUnread').textContent  = totals.unread;
  document.getElementById('cntRead').textContent    = totals.read;
  var chip = document.getElementById('sidebarUnread');
  if(chip){
    if(totals.unread > 0){ chip.textContent = totals.unread; chip.style.display = ''; }
    else { chip.style.display = 'none'; }
  }
}

document.querySelectorAll('.ab-toggle').forEach(function(btn){
  btn.addEventListener('click', function(e){
    e.preventDefault();
    var id = btn.dataset.id;
    var tr = document.querySelector('#tbody tr[data-id="'+id+'"]');
    if(!tr) return;
    var url = toggleUrl.replace('__ID__', id);
    fetch(url, {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': csrf, 'Accept':'application/json' }
    })
    .then(function(r){ return r.json(); })
    .then(function(d){
      if(!d.ok) return;
      setRowRead(tr, d.is_read);
      if(d.is_read){ totals.unread = Math.max(0, totals.unread - 1); totals.read++; }
      else { totals.read = Math.max(0, totals.read - 1); totals.unread++; }
      totals.total = totals.unread + totals.read;
      writeTotals();
      toast(d.is_read ? 'Marked as read.' : 'Marked as unread.', 'success');
    })
    .catch(function(){ toast('Network error.', 'error'); });
  });
});

/* toast */
function toast(msg, type){
  var icons = {
    success: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
    error: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
  };
  var el = document.createElement('div');
  el.className = 'toast ' + (type === 'error' ? 'toast-err' : 'toast-ok');
  el.innerHTML = (icons[type]||icons.success) + '<span>' + msg + '</span><button class="toast-x" onclick="this.parentElement.remove()">✕</button>';
  document.getElementById('toastWrap').appendChild(el);
  setTimeout(function(){
    el.style.transition = 'opacity .3s,transform .3s';
    el.style.opacity = '0';
    el.style.transform = 'translateX(20px)';
    setTimeout(function(){ el.remove(); }, 300);
  }, 4200);
}

(function(){
  var w = document.getElementById('toastWrap');
  var s = w ? w.getAttribute('data-success') : '';
  if(s) setTimeout(function(){ toast(s, 'success'); }, 200);
})();

/* ── delegated actions ── */
document.addEventListener('click', function(e){
  var el = e.target.closest('[data-action]');
  if(!el) return;
  var action = el.getAttribute('data-action');

  if(action === 'delete-message'){
    if(!confirm('Delete this message?')) e.preventDefault();
  }
});

syncBulkBar();
applyFilters();
})();