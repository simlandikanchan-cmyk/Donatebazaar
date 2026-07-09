# Navbar & Footer: Integrate Standalone Template into Vite + Align Design System

## Context / Findings
- `resources/views/layouts/navigation.blade.php` loads its styles/scripts from
  **standalone public files** via `asset('css/navbar.css')` and `asset('js/navbar.js')`
  (lines 381-382). These bypass the Vite build — they are not listed in the `@vite`
  directive in `layouts/app.blade.php`, so `npm run build` never processes or hashes
  them. For production this means no cache-busting and the assets only exist because
  they were hand-placed in `public/`.
- Those standalone files define their **own design tokens** (`public/css/navbar.css:5`):
  `--db-accent-from:#3B6FE8`, `--db-accent-to:#6C48C5`, `--db-font:'Sora'`, which
  diverge from the shared system (`--accent:#6366f1`, `--accent2:#8b5cf6`,
  `--font:'DM Sans'` used across `home.css`/`user.css`/`app.css`). The navbar therefore
  looks visually inconsistent (different blue + different font).
- `resources/views/partials/footer.blade.php` is **already Vite-integrated**: its CSS
  (`resources/css/footer.css`) is in the `@vite` array and its JS is inline. However
  `footer.css:3` redefines its own divergent tokens (`--accent:#7c6dfa`,
  `--green:#7effc4`).
- **Latent bug:** `public/js/navbar.js` references `#db-backdrop`
  (`openMobileDrawer()` does `backdrop.classList.add('is-open')`), but no element with
  `id="db-backdrop"` exists anywhere in the markup. Opening the mobile drawer currently
  throws a TypeError and renders with no dimmed backdrop. The standalone template was
  incomplete.

## Goal
Make nav + footer production-ready: build both via Vite (hashed / cache-busted), and
align accent / font / green tokens to the shared design system so they match the rest
of the site. Also fix the missing mobile-drawer backdrop.

## Steps
1. **Move navbar assets into the Vite pipeline**
   - `git mv public/css/navbar.css resources/css/navbar.css`
   - `git mv public/js/navbar.js resources/js/navbar.js`
   - In `resources/views/layouts/app.blade.php`, add to the existing `@vite([...])` array:
     `'resources/css/navbar.css'`, `'resources/js/navbar.js'`.
   - In `resources/views/layouts/navigation.blade.php`, remove the `@once` block that
     contains `<link rel="stylesheet" href="{{ asset('css/navbar.css') }}">` and
     `<script src="{{ asset('js/navbar.js') }}" defer></script>`. Move the two avatar
     rules currently in its inline `<style>` (`.db-dropdown__avatar`, `.db-mobile__avatar`)
     into `resources/css/navbar.css`, then delete the now-empty `@once` block.

2. **Add the missing mobile-drawer backdrop element**
   - Add `<div id="db-backdrop" class="db-backdrop"></div>` inside the `<header>`, just
     before `#mobile-drawer` (or right after it). The CSS `.db-backdrop` /
     `.db-backdrop.is-open` already exist in `navbar.css`; only the element was missing.
     This stops the `openMobileDrawer()` null-error and restores the dimmed backdrop.

3. **Align navbar tokens to the shared design system**
   - In `resources/css/navbar.css` `:root`, update values:
     - `--db-font: 'DM Sans', system-ui, sans-serif;`
     - `--db-accent-from: #6366f1;`
     - `--db-accent-to: #8b5cf6;`
     - `--db-accent-mid: #7c5cf0;`
     - `--db-border-focus: rgba(99,102,241,0.5);`
     - `--db-hover-bg: rgba(99,102,241,0.06);`
     - keep `--db-navbar-h: 64px` (matches the `top:64px` sticky toolbar on all-campaigns).
   - Grep `navbar.css` to confirm no remaining `Sora`, `#3B6FE8`, or `#6C48C5`.

4. **Align footer tokens**
   - In `resources/css/footer.css` `:root`, update:
     - `--accent: #6366f1;`
     - `--accent2: #8b5cf6;`
     - `--green: #10b981;`
     - (`--font` is already `'DM Sans'`.)
   - Footer's neon green (`#7effc4`) becomes the standard `#10b981` — intended alignment.

5. **Build & verify**
   - `npm run build` (generates `public/build/manifest.json` + hashed assets). Required
     for production; without it navbar/footer assets would 404 (same build gap noted for
     the homepage earlier).
   - Local dev (`npm run dev`): load `/`, `/all-campaigns`, a campaign page. Verify:
     navbar About/profile dropdowns, mobile hamburger drawer + dimmed backdrop (no console
     error), and footer JS (count-up, newsletter feedback, back-to-top).
   - Grep to confirm zero remaining `asset('css/navbar')` / `asset('js/navbar')` refs and
     that `public/css/navbar.css` and `public/js/navbar.js` are removed.

## Risks
- `npm run build` MUST run before deploy; otherwise production 404s on navbar/footer CSS/JS.
- Token changes alter navbar/footer appearance (intended). Spot-check brand color + font
  on desktop and mobile.
- When removing the `@once` inline `<style>`, ensure the avatar rules are preserved in
  `navbar.css`.

## Validation checklist
- [ ] `npm run build` succeeds; `public/build/manifest.json` exists.
- [ ] No `asset('...navbar...')` references remain; `public/css/navbar.css` & `public/js/navbar.js` deleted.
- [ ] `#db-backdrop` element present; mobile drawer opens with backdrop and no console error.
- [ ] Navbar font = DM Sans and accent = indigo `#6366f1`, consistent with the rest of the site.
- [ ] Footer accent/green aligned to shared tokens; footer JS still works.
