(function(){
'use strict';

var currentFilter = 'all';
var searchInput = document.getElementById('searchInput');
var rows = Array.prototype.slice.call(document.querySelectorAll('.campaign-row'));
var noResults = document.getElementById('noResults');

function matchesFilter(kyc, filter) {
    if (filter === 'all') return true;
    if (filter === 'action') return kyc === 'rejected' || kyc === 'none';
    return kyc === filter;
}

function applyFilters() {
    var term = (searchInput?.value || '').trim().toLowerCase();
    var visibleCount = 0;
    rows.forEach(function(row){
        var kyc = row.getAttribute('data-kyc');
        var title = row.getAttribute('data-title') || '';
        var show = matchesFilter(kyc, currentFilter) && title.indexOf(term) !== -1;
        row.style.display = show ? '' : 'none';
        if (show) visibleCount++;
    });
    if (noResults) noResults.style.display = (visibleCount === 0 && rows.length > 0) ? 'flex' : 'none';
}

function setActiveStatCard(filter) {
    document.querySelectorAll('.stat-card').forEach(function(card){
        card.classList.toggle('is-active', card.getAttribute('data-filter') === filter);
    });
}
function setActiveTab(filter) {
    document.querySelectorAll('.filter-tab').forEach(function(tab){
        tab.classList.toggle('active', tab.getAttribute('data-filter') === filter);
    });
}

document.querySelectorAll('.stat-card').forEach(function(card){
    card.addEventListener('click', function(){
        var filter = this.getAttribute('data-filter');
        currentFilter = filter;
        setActiveStatCard(filter);
        setActiveTab(filter);
        applyFilters();
    });
});
document.querySelectorAll('.filter-tab').forEach(function(tab){
    tab.addEventListener('click', function(){
        var filter = this.getAttribute('data-filter');
        currentFilter = filter;
        setActiveTab(filter);
        setActiveStatCard(filter === 'rejected' || filter === 'none' ? 'action' : filter);
        applyFilters();
    });
});
searchInput?.addEventListener('input', applyFilters);

})();
