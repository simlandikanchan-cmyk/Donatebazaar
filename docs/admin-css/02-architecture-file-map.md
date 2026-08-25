# Admin CSS Refactor — Architecture & File Map

## Entry point

`resources/views/layouts/admin.blade.php` (line 11) references only:

```
@vite(['resources/css/admin/admin.css'])
```

`resources/css/admin/admin.css` contains the full import graph (38 `@import` statements, all resolvable).

## Directory layout

| Directory | Contents | Files |
|---|---|---|
| `resources/css/base/` | Design tokens shared app-wide (variables, reset, typography, animations) | 4 |
| `resources/css/admin/_variables.css` | Admin-specific CSS custom properties (layout sizes, sidebar/topbar dimensions) | 1 |
| `resources/css/admin/utilities/` | Color tokens (`_colors.css`), generic helpers (`_helpers.css`) | 2 |
| `resources/css/admin/layout/` | App shell: content, sidebar, topbar + `_responsive.css` (breakpoints ≤1400/1200/960/640/480/380) | 4 |
| `resources/css/admin/components/` | Reusable primitives: buttons, badges, alerts, cards, forms, tables, toolbar, pagination, tabs, page-header, hero, stats, dropdowns, modals, empty-state | 15 |
| `resources/css/admin/pages/` | Page-scoped classes (ordered: dashboard, campaigns, categories, products, finance, messages, blogs, events, jobs, organizations, applications, misc) | 12 |
| `resources/css/admin/admin.css` | Entry / import graph | 1 |

## Import order (cascade load-bearing)

1. `base/*` (4)
2. `_variables.css`
3. `utilities/_colors.css`, `utilities/_helpers.css`
4. `layout/_content.css`, `layout/_sidebar.css`, `layout/_topbar.css`
5. `components/*` (buttons, badges, alerts, cards, forms, tables, toolbar, pagination, tabs, page-header, hero, stats, dropdowns, modals, empty-state)
6. `pages/*` in the exact order above (`.body` has no `page-*` class, so later page files win ties)
7. `layout/_responsive.css` (all media queries last)

## Component files (15)

`_buttons.css` (canonical `.btn` + legacy `btn-*`), `_badges.css` (`.tag*`, `.pill*`, `.badge`), `_alerts.css` (`.flash`, `.toast`, `.alert-ok/-error`, `.toast-close`), `_cards.css` (card family + `ci-*` tints, `detail-*`, `meta-*`, `info-*`, `accent-card`), `_forms.css` (`.form-*`, `.field-*`, `.inp`/`.ta`/`.sel`, `.toggle-*`, `.slug-*`, `.upload-*`, `.stepper`), `_tables.css`, `_toolbar.css`, `_pagination.css`, `_tabs.css` (tabs + breadcrumbs), `_page-header.css`, `_hero.css`, `_stats.css`, `_dropdowns.css`, `_modals.css`, `_empty-state.css`.

## Page files (12)

`_dashboard.css` (hero, impact cards, quicknav, charts, campaign table, `hb-count`), `_campaigns.css` (includes `qk-*`, `stat-val`, `sv-paused`), `_categories.css` (includes `cp-*` bulk UI, `btn-destructive`, preview panel), `_products.css`, `_finance.css` (donations, settlements, wallets + full `cp-*` and `gc-*` sets), `_messages.css` (incl. `filter-*` bar), `_blogs.css`, `_events.css`, `_jobs.css`, `_organizations.css`, `_applications.css`, `_misc.css` (levels, contacts, modal-body, generic tint utilities).
