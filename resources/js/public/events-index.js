(function () {
    'use strict';

    const searchInput = document.getElementById('evSearch');
    const clearBtn    = document.getElementById('evClear');
    const sortSel     = document.getElementById('evSort');
    const countEl     = document.getElementById('evCount');
    const noResults   = document.getElementById('evNoResults');
    let activeCategory = 'all';
    let activePeriod = 'all';

    function sortKey(el) {
        return (el.dataset.date || '0000-00-00') + ' ' + (el.dataset.time || '00:00');
    }

    function animateCard(card, show) {
        if (show) {
            card.classList.remove('ev-card--hidden');
            requestAnimationFrame(function() {
                card.classList.remove('ev-card--fade');
            });
        } else {
            card.classList.add('ev-card--fade');
            setTimeout(function() {
                card.classList.add('ev-card--hidden');
                card.classList.remove('ev-card--fade');
            }, 300);
        }
    }

    function applyFilters() {
        const term = (searchInput.value || '').trim().toLowerCase();
        let total = 0;

        document.querySelectorAll('.ev-category-section').forEach(section => {
            if (activeCategory !== 'all' && section.dataset.cat !== activeCategory) {
                section.classList.add('hidden');
                return;
            }
            section.classList.remove('hidden');

            const grid  = section.querySelector('.ev-grid');
            const cards = Array.from(grid.querySelectorAll('.ev-card'));
            const dir   = sortSel.value;

            cards.sort((a, b) => {
                const cmp = sortKey(a).localeCompare(sortKey(b));
                return dir === 'date-desc' ? -cmp : cmp;
            });
            cards.forEach(c => grid.appendChild(c));

            let visible = 0;
            cards.forEach(function(card) {
                const hay = (card.dataset.title + ' ' + card.dataset.location).toLowerCase();
                const matchesSearch = !term || hay.includes(term);
                const matchesPeriod = activePeriod === 'all' || card.dataset.period === activePeriod;
                const show = matchesSearch && matchesPeriod;
                animateCard(card, show);
                if (show) visible++;
            });

            section.classList.toggle('ev-section--empty', visible === 0);
            total += visible;
        });

        countEl.textContent = total + (total === 1 ? ' event' : ' events');
        noResults.hidden = total !== 0;
        clearBtn.hidden = term === '';
    }

    function filterCat(catId, btn) {
        document.querySelectorAll('.ev-filter-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        activeCategory = catId;
        applyFilters();
    }

    function filterPeriod(period, btn) {
        document.querySelectorAll('.ev-period-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        activePeriod = period;
        applyFilters();
    }

    function resetFilters() {
        searchInput.value = '';
        activeCategory = 'all';
        activePeriod = 'all';
        sortSel.value = 'date-asc';
        document.querySelectorAll('.ev-filter-btn').forEach(b =>
            b.classList.toggle('active', b.dataset.cat === 'all'));
        document.querySelectorAll('.ev-period-btn').forEach(b =>
            b.classList.toggle('active', b.dataset.period === 'all'));
        applyFilters();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    if (searchInput) searchInput.addEventListener('input', applyFilters);
    if (sortSel)     sortSel.addEventListener('change', applyFilters);
    if (clearBtn)    clearBtn.addEventListener('click', function () {
        searchInput.value = '';
        applyFilters();
        searchInput.focus();
    });

    document.querySelectorAll('.ev-card').forEach(function (card) {
        card.addEventListener('click', function (e) {
            if (e.target.closest('a, button, select, input')) return;
            window.location.href = card.dataset.url;
        });
        card.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                window.location.href = card.dataset.url;
            }
        });
    });

    document.addEventListener('click', function (e) {
        const btn = e.target.closest('[data-action="filter-cat"]');
        if (btn) filterCat(btn.dataset.cat, btn);
    });

    applyFilters();
})();