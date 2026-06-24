document.addEventListener('DOMContentLoaded', function () {

    /* ── SCROLL REVEAL ── */
    var revealEls = document.querySelectorAll('.reveal');
    var obs = new IntersectionObserver(function (entries) {
        entries.forEach(function (e) {
            if (e.isIntersecting) { e.target.classList.add('visible'); obs.unobserve(e.target); }
        });
    }, { threshold: 0.08, rootMargin: '0px 0px -30px 0px' });
    revealEls.forEach(function (el) { obs.observe(el); });

    /* ── SCROLL TO TOP ── */
    var sBtn = document.getElementById('scrollTopBtn');
    window.addEventListener('scroll', function () {
        sBtn.classList.toggle('visible', window.scrollY > 600);
    }, { passive: true });

    /* ── PROGRESS FILL ANIMATION ── */
    var fills = document.querySelectorAll('.camp-progress-fill, .spotlight-progress-fill');
    var fObs = new IntersectionObserver(function (entries) {
        entries.forEach(function (e) {
            if (e.isIntersecting) {
                e.target.style.width = e.target.getAttribute('data-w') || e.target.style.width;
            }
        });
    }, { threshold: 0.3 });
    fills.forEach(function (el) {
        el.setAttribute('data-w', el.style.width);
        el.style.width = '0%';
        setTimeout(function () { fObs.observe(el); }, 100);
    });

    /* ── VIEW TOGGLE persistence ── */
    var savedView = localStorage.getItem('campView') || 'grid';
    applyView(savedView);

    /* ── Close dropdowns on outside click ── */
    document.addEventListener('click', function (e) {
        if (!e.target.closest('.custom-select')) {
            document.querySelectorAll('.cs-dropdown.open').forEach(function (d) { d.classList.remove('open'); });
            document.querySelectorAll('.cs-trigger.open').forEach(function (t) { t.classList.remove('open'); });
        }
    });

    /* ── Sync modal dropdowns to current URL params on load ── */
    var params = new URLSearchParams(window.location.search);

    var locVal = params.get('location') || 'all';
    var locOption = document.querySelector('#locationDropdown .cs-option[data-value="' + locVal + '"]');
    if (locOption) document.getElementById('locationLabel').textContent = locOption.textContent.trim();

    var typeVal = params.get('campaign_type') || 'active';
    var typeOption = document.querySelector('#typeDropdown .cs-option[data-value="' + typeVal + '"]');
    if (typeOption) document.getElementById('typeLabel').textContent = typeOption.textContent.trim();

    /* ── Update filter badge on load ── */
    updateFilterBadge();
    renderActiveFilterChips();
});

/* ══════════════════════
   VIEW TOGGLE
══════════════════════ */
function setView(v) {
    localStorage.setItem('campView', v);
    applyView(v);
}

function applyView(v) {
    var grid = document.getElementById('campGrid');
    var gBtn = document.getElementById('gridBtn');
    var lBtn = document.getElementById('listBtn');
    if (!grid) return;
    if (v === 'list') {
        grid.classList.add('list-view');
        if (lBtn) lBtn.classList.add('active');
        if (gBtn) gBtn.classList.remove('active');
    } else {
        grid.classList.remove('list-view');
        if (gBtn) gBtn.classList.add('active');
        if (lBtn) lBtn.classList.remove('active');
    }
}

/* ══════════════════════
   FILTER MODAL
══════════════════════ */
function openFilterModal() {
    document.getElementById('filterModal').classList.add('open');
    document.getElementById('filterBackdrop').classList.add('open');
    document.body.style.overflow = 'hidden';
}

function closeFilterModal() {
    document.getElementById('filterModal').classList.remove('open');
    document.getElementById('filterBackdrop').classList.remove('open');
    document.body.style.overflow = '';
}

function toggleDropdown(dropdownId, triggerId) {
    var dropdown = document.getElementById(dropdownId);
    var trigger  = document.getElementById(triggerId);
    var isOpen   = dropdown.classList.contains('open');
    /* Close all first */
    document.querySelectorAll('.cs-dropdown.open').forEach(function (d) { d.classList.remove('open'); });
    document.querySelectorAll('.cs-trigger.open').forEach(function (t) { t.classList.remove('open'); });
    if (!isOpen) {
        dropdown.classList.add('open');
        trigger.classList.add('open');
    }
}

function selectOption(dropdownId, triggerId, labelId, value, label, hiddenId) {
    document.getElementById(labelId).textContent = label;
    document.getElementById(hiddenId).value = value;
    document.querySelectorAll('#' + dropdownId + ' .cs-option').forEach(function (o) {
        o.classList.toggle('selected', o.dataset.value === value);
    });
    document.getElementById(dropdownId).classList.remove('open');
    document.getElementById(triggerId).classList.remove('open');
}

function selectChip(el, groupId, hiddenId, value) {
    document.querySelectorAll('#' + groupId + ' .type-chip').forEach(function (c) { c.classList.remove('selected'); });
    el.classList.add('selected');
    document.getElementById(hiddenId).value = value;
}

function applyModalFilters() {
    var params = new URLSearchParams(window.location.search);

    var location = document.getElementById('filterLocation').value;
    var campType = document.getElementById('filterCampaignType').value;
    var funding  = document.getElementById('filterFunding').value;
    var category = document.getElementById('filterCategory').value;

    if (location && location !== 'all')  params.set('location', location);      else params.delete('location');
    if (campType && campType !== 'all')  params.set('campaign_type', campType); else params.delete('campaign_type');
    if (funding  && funding  !== 'any')  params.set('funding', funding);        else params.delete('funding');
    if (category)                        params.set('category', category);      else params.delete('category');

    params.delete('page');
    closeFilterModal();
    window.location.href = window.location.pathname + '?' + params.toString();
}

function clearAllFilters() {
    /* Reset location dropdown */
    document.getElementById('locationLabel').textContent = 'All Locations';
    document.getElementById('filterLocation').value = 'all';
    document.querySelectorAll('#locationDropdown .cs-option').forEach(function (o) {
        o.classList.toggle('selected', o.dataset.value === 'all');
    });

    /* Reset type dropdown */
    document.getElementById('typeLabel').textContent = 'All Types';
    document.getElementById('filterCampaignType').value = 'all';
    document.querySelectorAll('#typeDropdown .cs-option').forEach(function (o) {
        o.classList.toggle('selected', o.dataset.value === 'all');
    });

    /* Reset funding chips */
    document.querySelectorAll('#fundingChips .type-chip').forEach(function (c) {
        c.classList.toggle('selected', c.dataset.value === 'any');
    });
    document.getElementById('filterFunding').value = 'any';

    /* Reset category chips */
    document.querySelectorAll('#categoryChips .type-chip').forEach(function (c) {
        c.classList.toggle('selected', c.dataset.value === '');
    });
    document.getElementById('filterCategory').value = '';
}

function updateFilterBadge() {
    var params = new URLSearchParams(window.location.search);
    var count = 0;
    ['location', 'campaign_type', 'funding', 'category'].forEach(function (k) {
        var v = params.get(k);
        if (v && v !== 'all' && v !== 'any' && v !== '') count++;
    });
    var badge = document.getElementById('filterBadge');
    if (count > 0) {
        badge.textContent = count;
        badge.style.display = 'inline-flex';
    } else {
        badge.style.display = 'none';
    }
}

function renderActiveFilterChips() {
    var params = new URLSearchParams(window.location.search);
    var wrap = document.getElementById('activeFilters');
    if (!wrap) return;
    wrap.innerHTML = '';

    var labels = {
        location: {
            label: 'Location',
            values: {
                pan_india: 'PAN India', bengaluru: 'Bengaluru', chennai: 'Chennai',
                hyderabad: 'Hyderabad', kolkata: 'Kolkata', mumbai: 'Mumbai',
                new_delhi: 'New Delhi', agartala: 'Agartala', ahmedabad: 'Ahmedabad',
                bhopal: 'Bhopal', bhubaneswar: 'Bhubaneswar', chandigarh: 'Chandigarh',
                coimbatore: 'Coimbatore', guwahati: 'Guwahati', indore: 'Indore',
                jaipur: 'Jaipur', lucknow: 'Lucknow', nagpur: 'Nagpur',
                patna: 'Patna', pune: 'Pune', surat: 'Surat',
                vadodara: 'Vadodara', visakhapatnam: 'Visakhapatnam'
            }
        },
        campaign_type: {
            label: 'Type',
            values: { active: 'Active', urgent: 'Urgent', newly_launched: 'Newly Launched', closed: 'Closed', most_raised: 'Most Raised' }
        },
        funding: {
            label: 'Funding',
            values: { lt25: 'Under 25%', '25to75': '25–75%', gt75: '75%+', '100': 'Fully Funded' }
        },
    };

    ['location', 'campaign_type', 'funding', 'category'].forEach(function (key) {
        var val = params.get(key);
        if (!val || val === 'all' || val === 'any' || val === '') return;
        var displayVal = (labels[key] && labels[key].values[val]) ? labels[key].values[val] : val;
        var displayKey = labels[key] ? labels[key].label : key;
        var chip = document.createElement('span');
        chip.className = 'active-filter-chip';
        chip.innerHTML = displayKey + ': <strong>' + displayVal + '</strong>' +
            '<button onclick="removeFilter(\'' + key + '\')" aria-label="Remove ' + displayKey + ' filter">' +
            '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 18L18 6M6 6l12 12"/></svg></button>';
        wrap.appendChild(chip);
    });

    /* Search chip */
    var s = params.get('search');
    if (s) {
        var chip = document.createElement('span');
        chip.className = 'active-filter-chip';
        chip.innerHTML = 'Search: <strong>' + s + '</strong>' +
            '<button onclick="removeFilter(\'search\')" aria-label="Remove search filter">' +
            '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 18L18 6M6 6l12 12"/></svg></button>';
        wrap.appendChild(chip);
    }
}

function removeFilter(key) {
    var params = new URLSearchParams(window.location.search);
    params.delete(key);
    params.delete('page');
    window.location.href = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
}

/* ── Sidebar filters navigate on change ── */
function applySidebarFilters() {
    var params = new URLSearchParams(window.location.search);

    var checkedFunding = document.querySelector('input[name="funding"]:checked');
    if (checkedFunding && checkedFunding.value !== 'any') {
        params.set('funding', checkedFunding.value);
    } else {
        params.delete('funding');
    }

    var checkedCat = document.querySelector('input[name="cat_sidebar"]:checked');
    if (checkedCat) {
        params.set('category', checkedCat.value);
    } else {
        params.delete('category');
    }

    params.delete('page');
    window.location.href = window.location.pathname + '?' + params.toString();
}

function clearFundingFilter() {
    document.querySelectorAll('input[name="funding"]').forEach(function (c) {
        c.checked = c.value === 'any';
    });
    applySidebarFilters();
}

/* ── Close modal on Escape ── */
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closeFilterModal();
});