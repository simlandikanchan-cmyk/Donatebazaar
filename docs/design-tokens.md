# DonateBazaar — Design Token System

Global color system for the entire application (public pages, user portal, admin).

- **Source of truth:** `resources/css/base/_variables.css`
- **User portal scope:** `resources/css/_core.css` (aligns the same tokens to its surface/text values)
- **Admin scope:** `resources/css/admin/core/_variables.css` (full mirror + admin shorthand aliases)
- **Delivered via:** `public/app.css` and `user/user.css` import `base/_variables.css`; admin entries import the admin copy. No Blade/backend changes required.

## Brand palette (never change these)

| Token | Value | Usage |
|---|---|---|
| `--primary` | `#2563EB` | Primary blue — CTAs, links, nav, progress |
| `--primary-hover` | `#1D4ED8` | Hover states |
| `--primary-active` | `#1E40AF` | Pressed states |
| `--primary-light` | `#3B82F6` | Light accents, icon strokes |
| `--secondary` | `#0D9488` | Secondary teal — gradients, accents |
| `--secondary-hover` | `#0F766E` | Teal hover |
| `--secondary-light` | `#14B8A6` | Teal light accents |
| `--bg` | `#F4F5FB` | Page background |
| `--surface` | `#FFFFFF` | Cards, panels |
| `--text` | `#0F1117` | Primary text |
| `--text2` | `#4B5563` | Secondary text |
| `--text3` | `#9CA3AF` | Muted text |
| `--success` | `#16A34A` | Success |
| `--warning` | `#F59E0B` | Warning |
| `--danger` | `#EF4444` | Danger |

## SECTION II — canonical tokens (new)

### Surfaces
| Token | Light | Dark | Usage |
|---|---|---|---|
| `--surface-alt` | `#f8f9fe` | `#13141f` | Subtle section backgrounds, zebra rows |
| `--card` | `#ffffff` | `#13141f` | Card backgrounds |
| `--glass-bg` / `--glass-border` / `--glass-shadow` | white 65% / white 35% | `rgba(17,18,32,.72)` | Glassmorphism headers, dashboards |

### Typography
| Token | Value | Usage |
|---|---|---|
| `--text-secondary` | `#4b5563` | Body copy below primary |
| `--text-muted` | `#9ca3af` | Large muted text (≥18px), icons |
| `--text-muted-strong` | `#6b7280` | **AA-compliant muted text** — use for small (<18px) muted labels, captions, helper text |
| `--text-disabled` | `#b8bec9` | Disabled content (not required to meet AA) |
| `--text-on-primary` | `#ffffff` | White on blue → 5.0:1 AA |
| `--text-on-secondary` | `#ffffff` | White on teal → AA-large only; use `--secondary-ink` (`#042f2e`) for small text on teal |

### Borders
| Token | Value | Usage |
|---|---|---|
| `--border-light` | `rgba(0,0,0,.045)` | Hairlines inside cards, dividers |
| `--border` | `rgba(0,0,0,.06)` | Default separators (legacy) |
| `--border-strong` | `rgba(0,0,0,.16)` | Emphasis borders, outline cards |
| `--border-focus` | `rgba(37,99,235,.55)` | Focus ring color |
| `--border-success` | `rgba(22,163,74,.55)` | Valid inputs |
| `--border-danger` | `rgba(239,68,68,.55)` | Invalid inputs, destructive emphasis |

Dark mode borders are `rgba(255,255,255,…)` equivalents; focus/success/danger switch to lighter hues for contrast.

### Focus, interaction, disabled
| Token | Usage |
|---|---|
| `--focus-ring` (`0 0 0 3px rgba(37,99,235,.45)`) | Keyboard focus on buttons/links/inputs |
| `--focus-ring-danger` / `--focus-ring-success` | Focus on destructive/validating controls |
| `--focus-ring-offset` (`2px`) | Ring offset |
| `--glow-primary` / `--glow-success` / `--glow-danger` / `--glow-secondary` | Colored shadow glow for primary actions on dark hero sections |
| `--disabled-bg` / `--disabled-text` / `--disabled-border` | Disabled controls (`#f0f1fa` / `#9ca3af` / `rgba(0,0,0,.08)`) |
| `--ease-bounce` | Success checkmarks, celebratory pop animations |

## Category colors (campaign badges / pills / stats / charts)

Each category ships: `base`, `hover`, `light`, `tint`, `tint-bg`, `ink`.

| Category | Token prefix | Base | ink (text on tint-bg, AA) |
|---|---|---|---|
| Medical | `--cat-medical` | `#ef4444` | `#b91c1c` |
| Education | `--cat-education` | `#7c3aed` | `#5b21b6` |
| Environment | `--cat-environment` | `#16a34a` | `#166534` |
| Animal Welfare | `--cat-animal` | `#f59e0b` | `#92400e` |
| Disaster Relief | `--cat-disaster` | `#ea580c` | `#9a3412` |
| Children | `--cat-children` | `#ec4899` | `#be185d` |
| Women Empowerment | `--cat-women` | `#8b5cf6` | `#6d28d9` |
| Food | `--cat-food` | `#d97706` | `#92400e` |
| Healthcare | `--cat-healthcare` | `#dc2626` | `#991b1b` |
| Community | `--cat-community` | `#0891b2` | `#155e75` |
| Elderly Support | `--cat-elderly` | `#6366f1` | `#4338ca` |
| Emergency | `--cat-emergency` | `#b91c1c` | `#7f1d1d` |

**Badge recipe (light):** `color: var(--cat-X-ink); background: var(--cat-X-tint-bg); border: 1px solid var(--cat-X-tint)`.
**Badge recipe (dark):** the `ink`/`tint-bg` tokens auto-switch to light tints under `[data-theme="dark"]`.

## Color hierarchy (do not cross)

| Purpose | Use | Never use |
|---|---|---|
| Buttons, links, nav, progress, primary CTAs, focus | `--primary`, `--secondary` family | category colors |
| Campaign badges, category pills, status labels, stats, charts, icons, illustrations | semantic + category tokens | `--primary` |

## Gradients

| Token | Value | Usage |
|---|---|---|
| `--grad-brand` | `120deg #2563eb → #0d9488` | Signature identity gradient (progress, brand cards) |
| `--grad-primary` | `135deg #2563eb → #1d4ed8` | Primary buttons, deep blue fills |
| `--grad-soft-blue` | `#eff6ff → #e0f2fe` | Section backgrounds, empty states |
| `--grad-success` | `#16a34a → #059669` | Success states, raised milestones |
| `--grad-medical` | `#ef4444 → #dc2626` | Medical/donation-urgency accents |
| `--grad-environment` | `#16a34a → #84cc16` | Environment campaigns |
| `--grad-sunrise` | `#f59e0b → #ec4899` | Hero moments, special campaigns |
| `--grad-violet` | `#7c3aed → #8b5cf6` | Education/empowerment |
| `--grad-hero` | `rgba(10,11,20,0) → rgba(10,11,20,.55)` | Dark overlay under hero copy |
| `--grad-glass` | white 85% → white 10% | Glass surfaces over imagery |
| `--grad-progress` | `90deg #2563eb → #0d9488` | Progress bars, donation meters |

## Shadows

| Token | Usage |
|---|---|
| `--shadow-sm` | Resting elevation (cards, rows) |
| `--shadow-md` | Cards, dropdowns |
| `--shadow-lg` | Modals, floating panels |
| `--shadow-xl` | Overlays, hero depth |
| `--shadow-hover` | Interactive lift (cards on hover) |
| `--shadow-glass` | Glassmorphism depth |
| `--shadow-glow` | Colored presence on dark surfaces |

## Charts (Chart.js / analytics)

- `--chart-1` … `--chart-12` — categorical series in prominence order (blue, teal, medical red, education violet, environment green, animal amber, disaster orange, children pink, women violet, food amber, community cyan, elderly indigo).
- `--chart-soft-1` … `--chart-soft-12` — same hues at 14% alpha for area fills / banding.

## Illustration palette

`--illu-primary` `#3b82f6`, `--illu-secondary` `#2dd4bf`, `--illu-warm` `#fbbf24`, `--illu-rose` `#fda4af`, `--illu-violet` `#a78bfa`, `--illu-green` `#86efac`, `--illu-sky` `#67e8f9`, `--illu-skin` `#ffd7b3`, `--illu-ink` `#0f1117`, `--illu-soft-bg` `#eef1f9`. Use for SVG artwork, hero illustrations, empty-state art — always paired with `--illu-ink` line work.

## Dark mode

`[data-theme="dark"]` in `base/_variables.css` (plus scope overrides in `_core.css` and admin) redefines: surfaces (`--surface-alt`, `--card`, glass), typography (secondary/muted/disabled), borders (light/strong/focus/success/danger), focus rings (lighter blue `rgba(96,165,250,…)` for contrast on dark), glows, disabled colors, shadows, and category `ink`/`tint-bg` (light tints on translucent color backgrounds). Category `base` hues stay unchanged — identity is preserved.

## Accessibility notes

- Category `base` colors on white fail AA for small text (e.g. `#ef4444` ≈ 3.5:1, `#f59e0b` ≈ 2.1:1). Always use the `-ink` variant for text; reserve `base` for icons, filled pills with white text (≥AA-large), charts, and illustrations.
- `--text3`/`--text-muted` (`#9ca3af`) fails AA on white — use `--text-muted-strong` (`#6b7280`, 4.8:1) for small text.
- White on `--primary` (#2563eb) is 5.0:1 AA; white on `--secondary` (#0d9488) is AA-large only — use `--secondary-ink` for small text on teal.
- Focus rings are 3px at 45–50% alpha with a 2px offset; danger/success variants available for context.

## Verification

- `npm run build` — passes.
- `npm run lint:css` — no errors introduced in `_variables.css` / `_core.css` (86 pre-existing errors elsewhere untouched).
- Tokens resolve live on `/volunteer/apply` in both light and `data-theme="dark"`.
