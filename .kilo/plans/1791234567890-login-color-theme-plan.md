# Login Page — Site-Matched Color Theme + Dark/Light Toggle

## Goal
Make `http://127.0.0.1:8000/login` use the **same color palette as the rest of the website** and add a
dark/light toggle that reuses the site's exact `[data-theme="dark"]` tokens. Scope: only
`resources/views/auth/login.blade.php` (its inline `<style>` + markup + script). No new assets/files.

## Site palette (canonical, confirmed in `app.css` / `home.css`)
Light (`:root`):
- `--accent #2563eb`, `--accent2 #0d9488`, `--accent-glow rgba(37,99,235,0.18)`
- `--green #16a34a`, `--blue #3b82f6`, `--red #ef4444`, `--yellow #f59e0b`
- `--bg #f4f5fb`, `--surface #ffffff`, `--surface2 #f8f9fe`
- `--border rgba(0,0,0,0.06)`, `--border2 rgba(0,0,0,0.10)`
- `--text #0f1117`, `--text2 #4b5563`, `--text3 #9ca3af`
- `--font 'DM Sans'`, `--font-mono 'DM Mono'`, `--radius 14px`, `--radius-sm 9px`
- `--shadow 0 1px 3px rgba(0,0,0,0.06),0 4px 16px rgba(0,0,0,0.04)`, `--shadow-lg 0 8px 40px rgba(0,0,0,0.12)`

Dark (`[data-theme="dark"]`):
- `--bg #0b0c14`, `--surface #13141f`, `--surface2 #1a1b2e`
- `--border rgba(255,255,255,0.06)`, `--border2 rgba(255,255,255,0.10)`
- `--text #f0f1ff`, `--text2 #a5b4c8`, `--text3 #5a6579`
- `--accent-glow rgba(37,99,235,0.25)`, `--shadow`/`--shadow-lg` darkened

Note: the site defines `[data-theme="dark"]` but has **no toggle anywhere**. We introduce a page-local
toggle on the login page only, using the SAME selector/tokens so it stays consistent with the site.

## Current problems in `login.blade.php`
- `:root` defines its own mismatched vars; `--accent` and `--green` are **referenced but never defined**
  → focus ring, forgot-link, register-link, live-dot, activity-amount fall back to default colors.
- Button gradient hardcodes `#1d4ed8`/`#0d9488` (site uses `#2563eb`/`#0d9488`).
- Inputs hardcode `#f9fafb` bg + `rgba(0,0,0,0.09)` border → won't adapt to dark theme.
- Brand/trust SVG fills hardcode `rgba(37,99,235,…)` (fine, equals site accent).

## Tasks (ordered)

### 1. Replace `:root` with the site's canonical tokens
In `<head>` `<style>`, replace the existing `:root` block with the full light token set above. Keep any
login-only conveniences by mapping them to site tokens:
- `--card: var(--surface);`
- `--muted: var(--text2);`
- `--danger: var(--red);`
This makes `--accent`/`--green`/`--radius-sm` defined → fixes the broken accents.

### 2. Add `[data-theme="dark"]` override block
Immediately after `:root`, paste the site's dark token override (bg/surface/border/text/accent-glow/shadow).
No per-element edits needed because the login CSS will reference tokens.

### 3. Tokenize the login CSS (swap hardcoded colors → vars)
- `.btn-login` gradient → `linear-gradient(135deg, var(--accent), var(--accent2))`; box-shadow uses `var(--accent-glow)`; hover shadow uses `var(--accent-glow)`.
- `.input-wrap input` background → `var(--surface2)`; border → `var(--border2)`; text → `var(--text)`; focus `box-shadow: 0 0 0 3px var(--accent-glow)`; focus border → `var(--accent)`.
- `.right-panel` background → `var(--surface)` (was `var(--card)`).
- `.session-status`, `.alert-errors`, `.register-link a`, `.forgot-link`, `.pwd-toggle:hover`, `.input-wrap:focus-within .ico` → already use `var(--accent)` (now defined) — verify they read correctly.
- Left-panel decorative blues/teals: keep `rgba(37,99,235,…)` / `rgba(13,148,136,…)` (== site accent/accent2). Thematic blobs already `#2563eb`/`#0d9488`.
- `--green` usages (`.tag-dot`, `.live-dot`, `.live-badge`, `.activity-amount`) → now resolve to `#16a34a`.
- Wrap color/background transitions: add `transition: background .3s, color .3s, border-color .3s, box-shadow .3s;` to `body, .right-panel, .input-wrap input` for smooth toggle.

### 4. Add a persisted dark/light toggle
- **No-flash init** (inline `<script>` at top of `<head>`, before CSS paints):
  ```js
  (function(){var t=localStorage.getItem('theme');if(!t){t=matchMedia('(prefers-color-scheme:dark)').matches?'dark':'light';}document.documentElement.setAttribute('data-theme',t);})();
  ```
- **Button** (fixed, top-right of viewport, `z-index:5`, above `.page-wrapper`): `.theme-toggle` with sun + moon inline SVGs; uses `var(--surface)`/`var(--border2)`/`var(--text)` so it adapts.
- **Click handler** (in existing `<script>`): read current `document.documentElement.getAttribute('data-theme')`, flip to the other, `setAttribute`, `localStorage.setItem('theme', next)`, swap visible icon.

### 5. Polish (light)
- Slightly larger layered shadows via `--shadow-lg` on `.page-wrapper` in light; dark uses site dark shadow.
- Keep left-panel min-height (600px) and responsive breakpoints unchanged.

## Files touched
- `resources/views/auth/login.blade.php` — only file.

## Validation
- Server already on :8000. Open `http://127.0.0.1:8000/login`:
  - **Palette match:** forgot-password link, focus ring, live-activity dot/amount now render blue/green (not black) and match the site's `#2563eb`/`#16a34a`.
  - **Light = site:** page bg `#f4f5fb`, card `#fff`, button gradient blue→teal — consistent with home/about/user pages.
  - **Toggle:** click → right panel + bg switch to dark (`#0b0c14`/`#13141f`, light text); reload → choice persists (localStorage). First visit with OS dark mode → starts dark.
  - **Layout:** resize <720px → stacks correctly; toggle remains clickable; no FOUC on load.
- Login flow unchanged (no route/controller edits).

## Risks / notes
- Login page is a standalone HTML doc (own `<html>`/`<head>`); toggle is page-local, consistent with the
  site's `[data-theme]` convention but not shared globally (site has no global toggle).
- Do not load external CSS/JS; keep everything inline.
- Don't alter the left-panel's decorative character beyond recolor-to-site-tokens.
