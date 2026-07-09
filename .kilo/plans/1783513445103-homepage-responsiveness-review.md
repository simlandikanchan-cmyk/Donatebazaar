# Home Page Responsiveness Review — `http://127.0.0.1:8000/`

## Scope
Static code review of the homepage route `HomeController@index` → `home.index` and its 10 section partials, the compiled CSS `resources/css/home.css`, the navbar (`resources/views/layouts/navigation.blade.php` + `public/css/navbar.css`), and the base `layouts/app.blade.php`.

Note: This is a **code-level review only**. No browser was available in this environment to render the page, so overlap/visual issues are inferred from CSS, not visually confirmed. A live render check (see "Verification") is still required to claim "responsive for all formats."

## Verdict (from code)
The homepage is **designed responsively and is in good shape**. It uses:
- Fluid typography via `clamp()` (hero title, section titles, stat values).
- `auto-fit` / `auto-fill` `minmax()` grids for categories, campaigns, why, mini-stats.
- A comprehensive breakpoint set: 1100, 1024, 900, 780, 600, 580, 560, 480, 400 (home) + 768 (navbar switch desktop↔hamburger).
- `<meta name="viewport" content="width=device-width, initial-scale=1.0">` present (`layouts/app.blade.php:6`) — required for mobile scaling.
- `100dvh` hero with `min-height` fallbacks.

## Findings (issues to fix / verify)

### 1. Impact section uses fragile inline-style attribute selectors (priority: medium)
`home.css` (≈ lines 1018–1041) targets mobile overrides with attribute substrings:
- `.impact-layout .reveal[style*="padding:28px"]`
- `.impact-layout [style*="font-size:13.5px"]`
- `.mini-stats-grid [style*="font-size:22px"]`

Risks:
- `[style*="font-size:13.5px"]` is **over-broad**: it also matches the state-row label on `impact.blade.php:69` (`.13.5px`), not just the intended element — changing its size unintentionally on ≤560px.
- These rules break the moment any of those inline styles are edited (e.g. `padding: 28px` → `padding:28px` difference is fine, but `padding: 32px` silently disables the mobile padding fix).
- Recommendation: move these styles into real CSS classes (e.g. `.impact-map-card`, `.impact-state-label`, `.impact-mini-num`) instead of matching on inline `style` strings.

### 2. Mobile drawer max-height clipping (priority: low)
`navbar.css:592` `.db-mobile.is-open { max-height: 640px }` with `overflow:hidden`. For a logged-in user with the full account menu this is normally fine, but any future added links or a long email could be clipped. Consider `max-height: 90vh` + `overflow-y:auto` for safety.

### 3. Hero stat-bar vs. dots spacing on very small screens (priority: low, verify visually)
- `.hero-stat-bar` becomes 2 columns (2 rows) at ≤600px (`home.css:1109`).
- `.hero-dots` is pushed up to `bottom:148px` (≤600) / `bottom:120px` (≤400).
- On a 360×640 phone this is likely OK, but on short landscape phones the 2-row bar + centered hero content could feel cramped. Confirm on real device / DevTools.

### 4. CTA banner fixed height (priority: low)
`.cta-section { height:460px }` (`home.css:957`) is fixed; content is centered so it won't break, but on short viewports the image is cropped. Acceptable; just confirm text remains readable.

## No issues found in
- Campaign grid: at ≤480px it correctly switches to `grid-template-columns:1fr`, so the `minmax(320px,1fr)` floor can't cause horizontal overflow on small phones.
- Categories / Why / How / Testimonials / Blogs carousels: all have explicit small-screen rules (single column / 50% / 82% cards).
- Navbar: single, consistent 768px breakpoint; hamburger drawer is present and styled.

## Verification plan (to confirm "all screen formats")
Recommended automated + manual check (implementation task for an exec-capable agent or the user):

1. **Automated screenshots** with Playwright/Puppeteer at widths:
   320, 375, 414 (phones), 768 (tablet portrait), 1024 (tablet landscape), 1280, 1440 (desktop), plus landscape phone 812×375.
   - Capture full-page + viewport screenshots.
   - Assert **no horizontal scroll** (`document.documentElement.scrollWidth <= window.innerWidth`) at each width.
   - Visually inspect hero/content/stat-bar overlap, nav hamburger behavior, grids reflowing to 1 column.
2. **Manual DevTools** checks: emulate iPhone SE (375), Pixel (411), iPad (768/1024); toggle device toolbar; verify navbar hamburger opens drawer; resize across 400→1100 to watch breakpoints.
3. Fix the fragile impact section selectors (Finding 1) and re-test.

## Files referenced
- `resources/views/home/index.blade.php`, `resources/views/home/sections/*.blade.php`
- `resources/css/home.css`
- `resources/views/layouts/app.blade.php:6` (viewport)
- `resources/views/layouts/navigation.blade.php`, `public/css/navbar.css`
