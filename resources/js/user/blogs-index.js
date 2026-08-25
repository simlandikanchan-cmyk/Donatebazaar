(function(){
'use strict';

var currentFilter = 'all';
var currentSearch = '';

var emptyTitles = {
    all:'No blogs yet', published:'No published blogs',
    pending:'No blogs in review', draft:'No draft blogs', rejected:'No rejected blogs'
};
var emptySubs = {
    all:'Start writing your first blog post to share your story with the world.',
    published:'Your published blogs will appear here once approved.',
    pending:'Submit a blog to have it reviewed by the admin team.',
    draft:'Save a blog as draft and come back to finish it later.',
    rejected:'Edit and resubmit any rejected blogs.'
};

function applyFilter(filter, search) {
    currentFilter = filter || currentFilter;
    currentSearch = (search !== undefined) ? search : currentSearch;

    var cards   = document.querySelectorAll('#blogGrid .blog-card');
    var visible = 0;

    cards.forEach(function(card) {
        var matchFilter = currentFilter === 'all' || card.dataset.status === currentFilter;
        var matchSearch = !currentSearch ||
            card.dataset.title.includes(currentSearch) ||
            card.dataset.excerpt.includes(currentSearch);
        var show = matchFilter && matchSearch;
        card.classList.toggle('hidden', !show);
        if (show) visible++;
    });

    document.querySelectorAll('.ftab').forEach(function(b){
        b.classList.toggle('on', b.dataset.filter === currentFilter);
    });

    document.querySelectorAll('.stat-card[data-filter]').forEach(function(c){
        c.classList.toggle('active-filter', c.dataset.filter === currentFilter);
    });

    var labels = { all:'total', published:'published', pending:'in review', draft:'drafts', rejected:'rejected' };
    var txt = visible + ' post' + (visible !== 1 ? 's' : '') + ' ' + (labels[currentFilter] || '');
    document.getElementById('subLabel').textContent = txt;

    var empty = document.getElementById('emptyState');
    document.getElementById('emptyTitle').textContent = emptyTitles[currentFilter] || emptyTitles.all;
    document.getElementById('emptySub').textContent   = emptySubs[currentFilter]   || emptySubs.all;
    document.getElementById('emptyBtn').style.display = currentFilter === 'all' ? '' : 'none';
    empty.style.display = visible === 0 ? 'block' : 'none';
}

document.querySelectorAll('[data-filter]').forEach(function(el){
    el.addEventListener('click', function(){ applyFilter(this.dataset.filter); });
});

document.getElementById('searchInput').addEventListener('input', function(){
    applyFilter(null, this.value.trim().toLowerCase());
});

document.addEventListener('DOMContentLoaded', function(){
    applyFilter('all', '');
    document.querySelectorAll('.blog-card').forEach(function(card, i){
        card.style.animationDelay = (i * 0.06) + 's';
    });
});

})();
