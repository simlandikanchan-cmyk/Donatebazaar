(function(){
'use strict';

var currentFilter = 'all';
var searchInput = document.getElementById('searchInput');
var filterSelect = document.getElementById('dhFilterSelect');
var rows = Array.prototype.slice.call(document.querySelectorAll('.dh-row'));
var noResults = document.getElementById('noResults');

function applyFilters() {
    var term = (searchInput?.value || '').trim().toLowerCase();
    var visibleCount = 0;
    rows.forEach(function(row){
        var status = row.getAttribute('data-status');
        var title = row.getAttribute('data-title') || '';
        var show = (currentFilter === 'all' || status === currentFilter) && title.indexOf(term) !== -1;
        row.style.display = show ? '' : 'none';
        if (show) visibleCount++;
    });
    if (noResults) noResults.style.display = (visibleCount === 0 && rows.length > 0) ? 'flex' : 'none';
}

document.querySelectorAll('.dh-stat').forEach(function(card){
    card.addEventListener('click', function(){
        var filter = this.getAttribute('data-filter');
        currentFilter = filter;
        document.querySelectorAll('.dh-stat').forEach(function(c){ c.classList.toggle('is-active', c.getAttribute('data-filter') === filter); });
        document.querySelectorAll('.dh-filter-tab').forEach(function(t){ t.classList.toggle('active', t.getAttribute('data-filter') === filter); });
        if (filterSelect) filterSelect.value = filter;
        applyFilters();
    });
});

document.querySelectorAll('.dh-filter-tab').forEach(function(tab){
    tab.addEventListener('click', function(){
        var filter = this.getAttribute('data-filter');
        currentFilter = filter;
        document.querySelectorAll('.dh-filter-tab').forEach(function(t){ t.classList.toggle('active', t.getAttribute('data-filter') === filter); });
        document.querySelectorAll('.dh-stat').forEach(function(c){ c.classList.toggle('is-active', c.getAttribute('data-filter') === filter); });
        if (filterSelect) filterSelect.value = filter;
        applyFilters();
    });
});

if (filterSelect) {
    filterSelect.addEventListener('change', function(){
        currentFilter = this.value;
        document.querySelectorAll('.dh-filter-tab').forEach(function(t){ t.classList.toggle('active', t.getAttribute('data-filter') === currentFilter); });
        document.querySelectorAll('.dh-stat').forEach(function(c){ c.classList.toggle('is-active', c.getAttribute('data-filter') === currentFilter); });
        applyFilters();
    });
}

searchInput?.addEventListener('input', applyFilters);

function toggleRefundDetails(id) {
    var el = document.getElementById('refundDetails' + id);
    if (el) {
        var isOpen = el.style.display !== 'none';
        el.style.display = isOpen ? 'none' : 'block';
    }
}

document.addEventListener('click', function(e){
    var btn = e.target.closest('[data-action="toggle-refund-details"]');
    if (btn) toggleRefundDetails(btn.getAttribute('data-id'));
});

})();
