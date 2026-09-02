# Admin Frontend Architecture

> **Status:** Active standard for all admin pages.
> **Last verified:** 2026-08-24

This document defines how CSS and JavaScript are organized in the admin panel. The structure described below is the **project standard** — new admin pages must follow it rather than introducing their own pattern.

---

## 1. Folder Structure (Actual)

```
resources/
├── css/admin/
│   ├── entries/          ← Vite entry points (registered in vite.config.js)
│   │   ├── core.css
│   │   ├── dashboard.css
│   │   ├── campaigns.css
│   │   ├── finance.css
│   │   └── ...
│   │
│   ├── core/             ← Global foundations (variables, reset, typography, animations)
│   │   ├── _variables.css
│   │   ├── _reset.css
│   │   ├── _typography.css
│   │   └── _animations.css
│   │
│   ├── layout/           ← Structural layout (sidebar, topbar, content, responsive)
│   │   ├── _content.css
│   │   ├── _sidebar.css
│   │   ├── _topbar.css
│   │   └── _responsive.css
│   │
│   ├── components/       ← Reusable UI components
│   │   ├── _buttons.css
│   │   ├── _badges.css
│   │   ├── _alerts.css
│   │   ├── _cards.css
│   │   ├── _danger-card.css
│   │   ├── _forms.css
│   │   ├── _tables.css
│   │   ├── _toolbar.css
│   │   ├── _pagination.css
│   │   ├── _tabs.css
│   │   ├── _page-header.css
│   │   ├── _hero.css
│   │   ├── _stats.css
│   │   ├── _dropdowns.css
│   │   ├── _modals.css
│   │   ├── _empty-state.css
│   │   └── _campaign-cards.css
│   │
│   ├── pages/            ← Page-specific styles
│   │   ├── dashboard.css
│   │   ├── campaigns.css
│   │   ├── finance.css
│   │   ├── jobs.css
│   │   ├── misc.css
│   │   ├── categories/
│   │   │   ├── index.css
│   │   │   ├── create-edit.css
│   │   │   └── ...
│   │   └── ...
│   │
│   └── utilities/        ← Small reusable helpers
│       ├── _helpers.css
│       └── _colors.css
│
└── js/admin/
    ├── admin.js          ← Core entry (imports shell.js)
    ├── shell.js          ← Shared shell (sidebar, theme, modals, toast)
    ├── dashboard.js      ← Page-specific
    ├── campaign-show.js  ← Page-specific
    ├── campaigns.js      ← Page-specific
    └── ...
```

---

## 2. CSS Ownership Rules

Each layer of the CSS tree has a clear owner. If a rule ends up in the wrong layer, it is worth fixing now because the mistake will only get more expensive to undo.

### `entries/` — Vite Entry Points

**Purpose:** Files registered in `vite.config.js`. Each admin page loads its own entry.

**Rules:**
- An entry is the **only CSS file loaded** for a page (besides `core.css`).
- Entries `@import` from `pages/`, `components/`, or other layers as needed.
- Do NOT write page styles directly in entries — delegate to `pages/`.

**Example:**
```css
/* entries/dashboard.css */
@import '../pages/dashboard.css';
```

```css
/* entries/misc.css — catch-all for simple pages */
@import '../pages/misc.css';
@import '../pages/contacts-index.css';
```

---

### `core/` — Global Foundations

**Purpose:** Design tokens and global defaults used everywhere.

**Contains:**
- `_variables.css` — CSS custom properties (colors, spacing, radii, shadows, fonts)
- `_reset.css` — Global reset/normalize
- `_typography.css` — Base typography
- `_animations.css` — Global keyframe animations

**Rules:**
- No page-specific styles here.
- No component styles here.
- Variables defined here are the **single source of truth** for design tokens.

---

### `layout/` — Structural Layout

**Purpose:** Global admin layout structure.

**Contains:**
- `_content.css` — Main content wrapper
- `_sidebar.css` — Sidebar navigation
- `_topbar.css` — Top navigation bar
- `_responsive.css` — Global responsive layout rules

**Rules:**
- Do NOT put campaign-, dashboard-, or other page-specific styles here.
- Global breakpoint rules that affect the entire admin shell belong here.

---

### `components/` — Reusable UI Components

**Purpose:** Shared UI components used by multiple admin pages.

**Contains:** Buttons, cards, forms, tables, tabs, dropdowns, modals, pagination, alerts, badges, empty states, hero, stats, campaign-cards, etc.

**Rules:**
- A component belongs here when it is **genuinely reusable** across 2+ pages.
- Do NOT put one-off page styling here just because the selector looks generic.
- Extract a component here **once** when it becomes reusable — do not duplicate it.

---

### `pages/` — Page-Specific Styles

**Purpose:** Styles specific to a particular admin page or feature.

**Contains:**
```text
pages/dashboard.css
pages/campaigns.css
pages/finance.css
pages/jobs.css
pages/volunteers-index.css
pages/blogs-index.css
pages/misc.css          ← catch-all for simple CRUD pages
pages/categories/       ← subfolder for complex page families
```

**Rules:**
- Page CSS may `@import` reusable components (e.g., `@import '../components/_campaign-cards.css';`).
- Do NOT redefine global component behavior unnecessarily.
- Page-specific responsive overrides belong here, not in `layout/_responsive.css`.

---

### `utilities/` — Small Helpers

**Purpose:** Utility classes and color helpers.

**Contains:**
- `_helpers.css` — Utility classes
- `_colors.css` — Color utility classes

**Rules:**
- Do NOT turn this folder into a dumping ground.
- Only genuinely reusable, single-purpose helpers belong here.

---

## 3. JS Architecture

### Current Structure

JavaScript files live **flat** in `resources/js/admin/`, organized by role:

```text
js/admin/
├── entries/          ← Vite entry points (thin wrappers)
│   ├── dashboard.js
│   ├── campaign-show.js
│   ├── campaign-edit.js
│   ├── blogs-index.js
│   └── ...
│
├── core/             ← Global admin behavior
│   ├── admin.js      ← Core entry (imports shell.js)
│   └── shell.js      ← Shared shell (sidebar, theme, modals, toast, form loading)
│
├── pages/            ← Page-specific behavior
│   ├── dashboard.js
│   ├── campaign-show.js
│   ├── campaign-edit.js
│   ├── blogs-index.js
│   └── ...
│
├── components/       ← Reusable admin JS components (if needed)
│
└── utilities/        ← Admin-specific utilities (if needed)
```

### Shared Modules

Modules used across multiple systems live in `resources/js/shared/`:

```text
js/shared/
├── api.js            ← csrfFetch wrapper
├── csrf.js           ← CSRF token helper
├── dom.js            ← DOM helpers ($, $$, delegate, onReady)
├── helpers.js        ← escapeHtml, animateCounter, formatting
├── modal.js          ← Modal defaults
├── theme.js          ← Theme toggle
└── toast.js          ← Toast notification system
```

### JS Ownership Rules

**`entries/` — Vite Entry Points:**

Each entry is a thin wrapper that imports the corresponding page module:

```js
// entries/dashboard.js
import '../pages/dashboard.js';
```

**Rules:**
- Entries are the **only JS files loaded by Blade** via `@vite()`.
- Entries should NOT contain application logic — only imports.
- Each entry imports exactly one page module.

---

**`core/admin.js` / `core/shell.js` — Core:**
- Sidebar toggle and mobile behavior
- Theme toggle
- Avatar dropdown
- Toast system initialization
- Modal defaults
- Form submit loading (`data-loading-text`)
- Generic `data-action` handlers (navigate, close-modal)

**Do NOT put page-specific logic here.**

**`pages/` — Page-Specific Modules:**
- Own the behavior specific to their page.
- Import shared modules as needed (`toast`, `csrfFetch`, `animateCounter`).
- Auto-initialize via a JSON config blob (`<script type="application/json" id="...">`).

**Do NOT place campaign logic, dashboard logic, or other page logic into `shell.js`.**

---

## 4. Entry-Point Rules

### How Pages Load Assets

Every admin page loads two things:

1. **`core/admin.js`** — loaded globally via `layouts/admin.blade.php`
2. **One page entry** — loaded in the specific Blade view via `@vite()`

**Example — Dashboard:**
```blade
{{-- layouts/admin.blade.php loads: --}}
@vite('resources/css/admin/entries/core.css')
@vite(['resources/js/admin/core/admin.js'])

{{-- dashboard.blade.php adds: --}}
@vite('resources/css/admin/entries/dashboard.css')
@vite('resources/js/admin/entries/dashboard.js')
```

**Example — Campaign Index:**
```blade
{{-- layouts/admin.blade.php loads: --}}
@vite('resources/css/admin/entries/core.css')
@vite(['resources/js/admin/core/admin.js'])

{{-- campaign/index.blade.php adds: --}}
@vite('resources/css/admin/entries/campaigns.css')
@vite('resources/js/admin/entries/campaign-index.js')
```

### Rule: Load Only What You Need

- Do NOT load every admin page's CSS/JS globally.
- Each page loads `core.css` + `core/admin.js` globally, plus its own entry.
- Shared components come in through the entry's `@import` chain, not by duplicating styles per page.

---

## 5. Naming Conventions

### CSS Files

| Layer | Convention | Example |
|-------|-----------|---------|
| `core/` | `_descriptor.css` | `_variables.css`, `_reset.css` |
| `layout/` | `_descriptor.css` | `_sidebar.css`, `_topbar.css` |
| `components/` | `_descriptor.css` | `_buttons.css`, `_cards.css` |
| `pages/` | `page-name.css` | `dashboard.css`, `campaigns.css` |
| `entries/` | `page-name.css` | `dashboard.css`, `campaigns.css` |
| `utilities/` | `_descriptor.css` | `_helpers.css`, `_colors.css` |

### JS Files

| Layer | Convention | Example |
|-------|-----------|---------|
| `entries/` | `page-name.js` | `dashboard.js`, `campaign-show.js` |
| `core/` | `descriptor.js` | `admin.js`, `shell.js` |
| `pages/` | `page-name.js` | `dashboard.js`, `campaign-show.js` |
| `components/` | `component-name.js` | `modal.js`, `data-table.js` |
| `utilities/` | `descriptor.js` | `format.js`, `query.js` |
| Shared module | `descriptor.js` | `toast.js`, `modal.js` |

### Forbidden Names

Do NOT create files such as those below. They end up duplicated, ambiguous, and impossible to maintain:

- `misc.css` / `misc.js` (except the existing catch-all entry)
- `fix.css` / `fix.js`
- `temp.css` / `temp.js`
- `new.css` / `new.js`
- `dashboard-final.css`
- `dashboard-final-2.css`

Use descriptive, permanent names from the start.

---

## 6. Cascade Rules

The admin CSS has been cleaned up and browser-verified. Keep it that way.

### Do NOT solve conflicts by blindly adding:

```css
✗ !important
✗ .foo.foo
✗ body .content .foo .bar
```

### When a conflict arises, work through the questions in order:

1. Which file owns the component?
2. Which file should win?
3. Is the source order correct?
4. Is the selector incorrectly scoped?
5. Is the rule actually page-specific?

Only increase specificity when there is a documented reason.

### Source Order

Entries load in this order:
1. `core.css` (global)
2. Page entry (e.g., `dashboard.css`)

Page entries load **after** core, so page rules win by source order — no `!important` needed.

---

## 7. Responsive CSS Rules

### Ownership

- **Global responsive rules** (sidebar collapse, topbar breakpoints) → `layout/_responsive.css`
- **Component responsive rules** (used everywhere) → within the component file
- **Page-specific responsive overrides** → within the page's `pages/` file

### Rules

- Do NOT scatter competing breakpoint rules across core, components, and pages.
- If a component has responsive behavior that applies everywhere, it belongs with the component.
- If the responsive behavior is specific to one page, it belongs in that page's stylesheet.
- Avoid duplicate breakpoint overrides.

---

## 8. Shared Component Rule

Before creating a new component CSS/JS file, check whether an existing component already provides the behavior.

### Do NOT create:

```text
✗ _dashboard-card.css
✗ _campaign-dashboard-card.css
✗ _campaign-card-v2.css
```

If the existing shared component (`_cards.css`, `_campaign-cards.css`) can be reused, reuse it.

### Extraction Rule

If a component genuinely becomes reusable across multiple pages, extract it **once** into `components/`. Do not duplicate.

---

## 9. Blade/Template Rules

Admin Blade files should primarily contain:

- HTML structure
- Laravel data
- Semantic markup
- Page-specific configuration (JSON blobs)

### Avoid:

```html
<!-- ✗ Do NOT do this -->
<style>
  .my-page-thing { ... }
</style>
```

```html
<!-- ✗ Do NOT do this -->
<script>
  // Large inline JavaScript block
</script>
```

### Do NOT create a second CSS/JS architecture inside Blade.

If a page requires CSS:
```
resources/css/admin/pages/<page>.css
```

If a page requires JS:
```
resources/js/admin/entries/<page>.js
```

The entry imports the page module:
```js
import '../pages/<page>.js';
```

---

## 10. How to Add a New Admin Page

Follow this recipe in order:

1. **Create the Blade view** in `resources/views/admin/<feature>/`.
2. **Create page CSS** (if needed):
   ```
   resources/css/admin/pages/<page>.css
   ```
3. **Create the entry CSS** (if needed):
   ```
   resources/css/admin/entries/<page>.css
   ```
   Content: `@import '../pages/<page>.css';`
4. **Create page JS** (if needed):
   ```
   resources/js/admin/pages/<page>.js
   ```
5. **Create the entry JS** (if needed):
   ```
   resources/js/admin/entries/<page>.js
   ```
   Content: `import '../pages/<page>.js';`
6. **Register the entry in `vite.config.js`**:
   ```js
   'resources/css/admin/entries/<page>.css',
   'resources/js/admin/entries/<page>.js',
   ```
7. **Load only the page assets** in the Blade view:
   ```blade
   @vite('resources/css/admin/entries/<page>.css')
   @vite('resources/js/admin/entries/<page>.js')
   ```
8. **Reuse existing components** — import from `components/` or `shared/` instead of duplicating.
9. **Test desktop/mobile** — verify responsive behavior.
10. **Test light/dark** — verify both themes.
11. **Run the production build**:
    ```bash
    npm run build
    ```

---

## 11. How to Create a Reusable Component

1. Verify no existing component already provides the behavior.
2. Create the CSS in `resources/css/admin/components/_component-name.css`.
3. If the component needs JS, add it to `resources/js/shared/` (for truly global behavior) or create a page-specific module that imports shared helpers.
4. Import the component where needed:
   ```css
   @import '../components/_component-name.css';
   ```
5. Document the component's purpose in a comment at the top of the file.

---

## 12. What NOT to Do

| Anti-pattern | Why |
|-------------|-----|
| Inline `<style>` in Blade | Breaks caching, defeats Vite, hard to maintain |
| Inline `<script>` blocks | Same problems |
| `!important` everywhere | Indicates source-order or specificity problems |
| `misc.css` dumping ground | Use specific page files; only use `misc.css` for genuinely shared simple-page styles |
| Duplicate CSS to avoid imports | Use `@import` — that's what it's for |
| One file per tiny selector | Group related styles logically |
| New frontend framework | Stick with vanilla JS + shared modules |
| Rename everything unnecessarily | The current names are the standard |
| Load all admin CSS/JS globally | Load only what each page needs |

---

## 13. Architecture Health

### Score: 9/10

### What follows the standard:

- **`core/`** — Clean separation of variables, reset, typography, animations.
- **`layout/`** — Clear ownership of sidebar, topbar, content, responsive.
- **`components/`** — Well-organized reusable components.
- **`utilities/`** — Properly scoped helpers and colors.
- **`entries/`** — Correct Vite entry-point pattern.
- **CSS cascade** — Clean source order, minimal `!important`.
- **Blade loading** — Correct `@vite()` usage per page.
- **JS folder structure** — Organized into `entries/`, `core/`, `pages/` matching the CSS architecture.

### Exceptions (intentional):

- **`entries/misc.css`** — Intentional catch-all for simple CRUD pages (faqs, success-stories, legal, subscribers, etc.). Pages like these share minimal styling, so a shared entry avoids one empty stylesheet per feature.
- **`pages/misc.css`** — Companion to the entry; holds the actual styles for those simple pages.
- **No JS `components/` or `utilities/`** — so far, no admin-specific JS component or utility has justified separate folders. The shared modules in `js/shared/` cover all reusable behavior. If admin-specific reusable behavior shows up later, it belongs in `js/admin/components/` or `js/admin/utilities/`.

### Is the current architecture safe for future pages?

**Yes.** Both the CSS and JS architectures are solid and ready for new pages. Follow the recipe in Section 10.

### Changes required now:

**None.** The architecture is documented and ready.

---

## 14. Quick Reference

| Need | File Location |
|------|--------------|
| New design token | `css/admin/core/_variables.css` |
| New global component | `css/admin/components/_name.css` |
| New page style | `css/admin/pages/<page>.css` |
| New page entry | `css/admin/entries/<page>.css` |
| New page JS | `js/admin/pages/<page>.js` |
| New page entry | `js/admin/entries/<page>.js` |
| New shared JS helper | `js/shared/<name>.js` |
| Register entry | `vite.config.js` |
| Load in Blade | `@vite('resources/js/admin/entries/<page>.js')` |