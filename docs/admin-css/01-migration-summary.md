# Admin CSS Refactor — Migration Summary

## What changed

The flat, single-purpose admin stylesheet (19 files, thousands of duplicated and dead rules) was reorganized into a layered, component-based architecture:

- **Before**: `resources/css/admin/*.css` flat files (admin.css importing ~19 old files) + inline `<style>` blocks in `coupons/index` and `gift-cards/index` + a dead `dashboard_yoyo` view with its own inline styles.
- **After**: `resources/css/admin/admin.css` (entry, 38 `@import`s) → `base/`, `utilities/`, `layout/`, `components/`, `pages/` + `_variables.css` and `layout/_responsive.css`.

## Verified facts

- `npm run build` succeeds; built admin CSS: `public/build/assets/admin-Bgvzs2_L.css`, 247.1 kB raw.
- Final tree-wide scan (`final_check.ps1`): every custom class used by live admin views is defined in the new tree. The only remaining undefined names are documented dead utilities (Tailwind `bg-*`/`text-*`/`border-*`/`rounded-*`/`shadow-*`, Bootstrap `col-md-*`) and JS hooks (`js-*`).
- 646 legacy custom classes, 121 old-only rules, and the 55-class inline `<style>` pair were all migrated into the new tree.
- `dashboard_yoyo.blade.php` was deleted (dead: no route references it); its ~120 unique classes were never defined (correct — they died with the view).

## Files deleted

- Legacy flat: `_pages.css`, `_layout.css`, `_tables.css`, `_campaigns.css`, `_dashboard_stats.css`, `_badges.css`, `_buttons.css`, `_dashboard_charts.css`, `_dashboard_hero.css`, `_dashboard_impact.css`, `_dashboard_quicknav.css`, `_forms.css`, `_modals.css`, `_sidebar.css`, `_topbar.css`, `_utilities.css`, `components/_buttons.css` (old duplicate, recreated from `_buttons.css`).
- Views: `dashboard_yoyo.blade.php`; inline `<style>` blocks in `coupons/index.blade.php` and `gift-cards/index.blade.php`.

## Notes

- `components/_buttons.css` was accidentally deleted mid-session and restored 1:1 from the top-level `_buttons.css` (same content, see `_buttons-migration.md`).
- The `errors-*.css` esbuild warning (`Unexpected "*"`) is pre-existing, in error-page CSS, out of scope.
