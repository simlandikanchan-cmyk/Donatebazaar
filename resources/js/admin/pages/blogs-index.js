/* ═══════════════════════════════════════════════════════════════════
   Admin Blogs Index page — extracted from inline <script>.
   Reads server data from #blogsIndexData (JSON) injected by the blade.
   ═══════════════════════════════════════════════════════════════════ */

import { toast as showToast } from '../../shared/toast.js';
import { csrfFetch } from '../../shared/api.js';

(function(){
  'use strict';

  var pageData = JSON.parse(document.getElementById('blogsIndexData').textContent);

  /* live counts (server-provided, adjusted on actions) */
  var counts = {
    total:     parseInt(document.getElementById('statTotal').textContent, 10) || 0,
    pending:   parseInt(document.getElementById('statPending').textContent, 10) || 0,
    published: parseInt(document.getElementById('statPublished').textContent, 10) || 0,
    rejected:  parseInt(document.getElementById('statRejected').textContent, 10) || 0
  };
  var activeStatus = pageData.activeStatus;

  function writeCounts(){
    document.getElementById('statTotal').textContent     = counts.total;
    document.getElementById('statPending').textContent   = counts.pending;
    document.getElementById('statPublished').textContent = counts.published;
    document.getElementById('statRejected').textContent  = counts.rejected;
    document.getElementById('fcntAll').textContent       = counts.total;
    document.getElementById('fcntPending').textContent   = counts.pending;
    document.getElementById('fcntPublished').textContent = counts.published;
    document.getElementById('fcntRejected').textContent  = counts.rejected;
  }

  var rows = Array.from(document.querySelectorAll('#tbody tr[data-id]'));
  var noRow = document.getElementById('noResultsRow');
  var bulkBar = document.getElementById('bulkBar');

  /* build category filter options from rendered rows */
  (function(){
    var cats = {};
    rows.forEach(function(r){ var c = r.dataset.category || ''; if(c) cats[c] = true; });
    var sel = document.getElementById('catFilter');
    Object.keys(cats).sort().forEach(function(c){
      var o = document.createElement('option'); o.value = c; o.textContent = c; sel.appendChild(o);
    });
  })();

  function applyFilters(){
    var q   = (document.getElementById('searchInput').value || '').toLowerCase().trim();
    var cat = document.getElementById('catFilter').value;
    var vis = 0;
    rows.forEach(function(r){
      var mS  = !q   || (r.dataset.search || '').includes(q);
      var mC  = cat === 'all' || (r.dataset.category || '') === cat;
      var mSt = activeStatus === 'all' || r.dataset.status === activeStatus;
      var show = mS && mC && mSt;
      r.classList.toggle('row-hidden', !show);
      if(show) vis++;
    });
    var e = document.getElementById('cntVisF');
    if(e) e.textContent = vis;
    if(noRow) noRow.style.display = (vis === 0 && rows.length > 0) ? '' : 'none';
  }

  var st;
  document.getElementById('searchInput').addEventListener('input', function(){
    clearTimeout(st);
    st = setTimeout(applyFilters, 180);
  });
  document.getElementById('catFilter').addEventListener('change', applyFilters);

  document.querySelectorAll('.ftab').forEach(function(tab){
    tab.addEventListener('click', function(){
      var url = new URL(window.location.href);
      url.searchParams.set('status', this.dataset.status);
      url.searchParams.set('page', 1);
      window.location.href = url.toString();
    });
  });

  document.getElementById('sortSelect').addEventListener('change', function(){
    var url = new URL(window.location.href);
    url.searchParams.set('sort', this.value);
    url.searchParams.set('status', activeStatus);
    url.searchParams.set('page', 1);
    window.location.href = url.toString();
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
    csrfFetch(pageData.bulkUrl, {
      method: 'POST',
      headers: { 'Content-Type':'application/json', 'Accept':'application/json' },
      body: JSON.stringify({ ids: ids, action: action })
    })
    .then(function(r){ return r.json(); })
    .then(function(d){ if(d.ok && onDone) onDone(d); else showToast('Something went wrong.', 'error', { duration: 4200 }); })
    .catch(function(){ showToast('Network error.', 'error', { duration: 4200 }); });
  }

  document.getElementById('bulkPublish').addEventListener('click', function(){
    var ids = selectedIds();
    if(!ids.length) return;
    postBulk('publish', ids, function(d){
      ids.forEach(function(id){
        var tr = document.querySelector('#tbody tr[data-id="'+id+'"]');
        if(!tr) return;
        var old = tr.dataset.status;
        setRowStatus(tr, 'published', false);
        counts.published++;
        if(old === 'pending') counts.pending = Math.max(0, counts.pending - 1);
        else if(old === 'rejected') counts.rejected = Math.max(0, counts.rejected - 1);
        else if(old !== 'published') counts.total++; /* draft/archived/flagged were not in total */
      });
      writeCounts();
      syncBulkBar();
      applyFilters();
      toast(d.msg || 'Published.', 'success', { duration: 4200 });
    });
  });

  document.getElementById('bulkDelete').addEventListener('click', function(){
    var ids = selectedIds();
    if(!ids.length) return;
    if(!confirm('Delete ' + ids.length + ' selected post(s)?')) return;
    ids.forEach(function(id){
      var tr = document.querySelector('#tbody tr[data-id="'+id+'"]');
      if(tr){
        if(tr.dataset.status === 'pending') counts.pending = Math.max(0, counts.pending - 1);
        else if(tr.dataset.status === 'published') counts.published = Math.max(0, counts.published - 1);
        else if(tr.dataset.status === 'rejected') counts.rejected = Math.max(0, counts.rejected - 1);
        counts.total = Math.max(0, counts.total - 1);
      }
    });
    postBulk('delete', ids, function(d){
      ids.forEach(function(id){
        var tr = document.querySelector('#tbody tr[data-id="'+id+'"]');
        if(tr) tr.remove();
      });
      rows = Array.from(document.querySelectorAll('#tbody tr[data-id]'));
      writeCounts();
      syncBulkBar();
      applyFilters();
      toast(d.msg || 'Deleted.', 'success', { duration: 4200 });
    });
  });

  /* status / featured helpers */
  function setRowStatus(tr, status, featured){
    tr.dataset.status = status;
    var badge = tr.querySelector('.badge');
    if(badge){
      badge.className = 'badge b-' + status;
      badge.innerHTML = '<span class="badge-dot"></span>' + (status.charAt(0).toUpperCase() + status.slice(1));
    }
    if(typeof featured !== 'undefined'){
      var fBtn = tr.querySelector('.js-feature');
      if(fBtn){ fBtn.dataset.featured = featured ? '1' : '0'; fBtn.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg> ' + (featured ? 'Unfeature' : 'Feature'); }
    }
  }

  function ajaxAction(url, tr, onOk){
    csrfFetch(url, { method:'POST', headers:{ 'Accept':'application/json' } })
    .then(function(r){ return r.json(); })
    .then(function(d){
      if(!d.ok){ showToast((d.message)||'Action failed.', 'error', { duration: 4200 }); return; }
      if(onOk) onOk(d);
    })
    .catch(function(){ showToast('Network error.', 'error', { duration: 4200 }); });
  }

  document.querySelectorAll('.js-approve').forEach(function(btn){
    btn.addEventListener('click', function(){
      var id = btn.dataset.id;
      var tr = document.querySelector('#tbody tr[data-id="'+id+'"]');
      if(!tr) return;
      ajaxAction(pageData.approveUrl.replace('__ID__', id), tr, function(d){
        setRowStatus(tr, 'published', false);
        counts.pending = Math.max(0, counts.pending - 1);
        counts.published++;
        writeCounts();
        applyFilters();
        toast(d.message || 'Approved.', 'success', { duration: 4200 });
      });
    });
  });

  document.querySelectorAll('.js-archive').forEach(function(btn){
    btn.addEventListener('click', function(){
      var id = btn.dataset.id;
      var tr = document.querySelector('#tbody tr[data-id="'+id+'"]');
      if(!tr) return;
      ajaxAction(pageData.archiveUrl.replace('__ID__', id), tr, function(d){
        setRowStatus(tr, 'archived', false);
        counts.published = Math.max(0, counts.published - 1);
        counts.total = Math.max(0, counts.total - 1);
        writeCounts();
        applyFilters();
        toast(d.message || 'Archived.', 'success', { duration: 4200 });
      });
    });
  });

  document.querySelectorAll('.js-feature').forEach(function(btn){
    btn.addEventListener('click', function(){
      var id = btn.dataset.id;
      var tr = document.querySelector('#tbody tr[data-id="'+id+'"]');
      if(!tr) return;
      ajaxAction(pageData.featureUrl.replace('__ID__', id), tr, function(d){
        var featured = !!(d.is_featured);
        setRowStatus(tr, tr.dataset.status, featured);
        toast(d.message || (featured ? 'Featured.' : 'Unfeatured.'), 'success', { duration: 4200 });
      });
    });
  });

  /* ── delegated handlers for data-action attributes ── */

  /* mobile status tabs dropdown */
  document.addEventListener('change', function(e){
    var el = e.target.closest('[data-action="ftab-select"]');
    if(!el) return;
    var btn = document.querySelector('.ftab[data-status="' + el.value + '"]');
    if(btn) btn.click();
  });

  /* row delete confirm (was onsubmit="return confirm(...)") */
  document.addEventListener('submit', function(e){
    var f = e.target.closest('form[data-confirm]');
    if(!f) return;
    if(!confirm(f.getAttribute('data-confirm'))) e.preventDefault();
  });

  /* flash toast from session (server value via #blogsIndexData) */
  if(pageData.success) setTimeout(function(){ showToast(pageData.success, 'success', { duration: 4200 }); }, 200);

  syncBulkBar();
  applyFilters();
})();