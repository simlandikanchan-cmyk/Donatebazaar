/* ═══════════════════════════════════════════════════════════════════
   NGO APPLICATION WIZARD — page entry
   Replaces inline <script> in application/layout.blade.php.
   Behavior wired via data-action attributes (event delegation).
   ═══════════════════════════════════════════════════════════════════ */

// ── Cert expand (step 3): data-action="toggle-cert" data-section="..."
document.addEventListener('click', (event) => {
    const card = event.target.closest('[data-action="toggle-cert"]');

    if (!card) return;

    const section = document.getElementById(card.dataset.section);
    const cb = card.querySelector('input[type="checkbox"]');

    if (!section || !cb) return;

    // Deferred so the native checkbox toggle completes first.
    setTimeout(() => {
        section.classList.toggle('open', cb.checked);
    }, 10);
});

// ── Doc upload feedback (step 4): data-action="mark-uploaded" data-key="..."
document.addEventListener('change', (event) => {
    const input = event.target.closest('input[type="file"][data-action="mark-uploaded"]');

    if (!input) return;

    const key = input.dataset.key;
    const item = document.getElementById('docitem-' + key);

    if (!item || !input.files || !input.files[0]) return;

    item.classList.add('uploaded');
    item.querySelector('.doc-size').textContent =
        input.files[0].name + ' · ' + (input.files[0].size / 1024).toFixed(0) + ' KB';
    item.querySelector('.doc-upload-btn').innerHTML = `
      <svg class="btn-icon-xs" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M20 6L9 17l-5-5"/>
      </svg> Uploaded`;
});

// ── Success overlay: click outside to dismiss
document.getElementById('successOverlay').addEventListener('click', function (e) {
    if (e.target === this) {
        this.style.opacity = '0';
        this.style.transition = 'opacity .3s';
        setTimeout(() => {
            this.classList.remove('show');
            this.style.opacity = '';
            this.style.transition = '';
        }, 300);
    }
});