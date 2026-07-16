import statesCities from './data/in-states-cities.json';

const stateSelect = document.getElementById('state');
const cityInput = document.getElementById('city');
const box = document.getElementById('city-suggestions');

// Populate state dropdown
if (stateSelect) {
  Object.keys(statesCities).sort().forEach(state => {
    const opt = document.createElement('option');
    opt.value = state;
    opt.textContent = state;
    stateSelect.appendChild(opt);
  });
}

function getCityPool() {
  const selectedState = stateSelect ? stateSelect.value : '';
  if (selectedState && statesCities[selectedState]) {
    return statesCities[selectedState];
  }
  // No state selected -> search across all states combined
  return Object.values(statesCities).flat();
}

function render(q) {
  const pool = getCityPool();
  const selectedState = stateSelect ? stateSelect.value : '';
  const matches = (q || selectedState)
    ? pool.filter(n => n.toLowerCase().startsWith(q.toLowerCase())).slice(0, 12)
    : [];
  box.innerHTML = '';
  if (!matches.length) { box.style.display = 'none'; return; }
  matches.forEach(name => {
    const el = document.createElement('div');
    el.className = 'city-suggestion';
    el.textContent = name;
    el.addEventListener('mousedown', e => {
      e.preventDefault();
      cityInput.value = name;
      box.style.display = 'none';
    });
    box.appendChild(el);
  });
  box.style.display = 'block';
}

if (cityInput) {
  cityInput.addEventListener('input', () => render(cityInput.value.trim()));
  cityInput.addEventListener('focus', () => render(cityInput.value.trim()));
}
if (stateSelect) {
  // When state changes, clear city input (since old value may not belong to new state)
  // and reveal the new state's cities so the user sees the cascaded list immediately.
  stateSelect.addEventListener('change', () => {
    cityInput.value = '';
    render('');
  });
}
document.addEventListener('click', e => {
  if (!box.contains(e.target) && e.target !== cityInput) box.style.display = 'none';
});
