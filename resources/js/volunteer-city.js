import cities from './data/in-cities.json';

const input = document.getElementById('city');
if (input) {
  const box = document.getElementById('city-suggestions');
  const render = (q) => {
    const matches = q
      ? cities.filter(n => n.toLowerCase().startsWith(q.toLowerCase())).slice(0, 12)
      : [];
    box.innerHTML = '';
    if (!matches.length) { box.style.display = 'none'; return; }
    matches.forEach(name => {
      const el = document.createElement('div');
      el.className = 'city-suggestion';
      el.textContent = name;
      el.addEventListener('mousedown', e => {
        e.preventDefault();
        input.value = name;
        box.style.display = 'none';
      });
      box.appendChild(el);
    });
    box.style.display = 'block';
  };
  input.addEventListener('input', () => render(input.value.trim()));
  input.addEventListener('focus', () => render(input.value.trim()));
  document.addEventListener('click', e => {
    if (!box.contains(e.target) && e.target !== input) box.style.display = 'none';
  });
}
