(function () {
  'use strict';
  var pageData = JSON.parse(document.getElementById('volunteersIndexData').textContent);
  var statesCities = pageData.statesCities;

  var stateSel  = document.getElementById('filter-state');
  var cityInp   = document.getElementById('filter-city');
  var box       = document.getElementById('city-suggestions');
  if (!stateSel || !cityInp || !box) return;

  Object.keys(statesCities).sort().forEach(state => {
    const opt = document.createElement('option');
    opt.value = state;
    opt.textContent = state;
    if (state === pageData.selectedState) opt.selected = true;
    stateSel.appendChild(opt);
  });

  function pool() {
    const s = stateSel.value;
    return (s && statesCities[s]) ? statesCities[s] : Object.values(statesCities).flat();
  }

  function render(q) {
    const list = pool().filter(n => n.toLowerCase().startsWith(q.toLowerCase())).slice(0, 12);
    box.innerHTML = '';
    if (!list.length) { box.style.display = 'none'; return; }
    list.forEach(name => {
      const el = document.createElement('div');
      el.className = 'city-suggestion';
      el.style.padding = '9px 12px';
      el.style.cursor = 'pointer';
      el.style.fontSize = '12.5px';
      el.textContent = name;
      el.addEventListener('mousedown', e => {
        e.preventDefault();
        cityInp.value = name;
        box.style.display = 'none';
      });
      box.appendChild(el);
    });
    box.style.display = 'block';
  }

  cityInp.addEventListener('input', () => render(cityInp.value.trim()));
  cityInp.addEventListener('focus', () => render(cityInp.value.trim()));
  stateSel.addEventListener('change', () => { cityInp.value = ''; render(''); });
  document.addEventListener('click', e => {
    if (!box.contains(e.target) && e.target !== cityInp) box.style.display = 'none';
  });
})();
