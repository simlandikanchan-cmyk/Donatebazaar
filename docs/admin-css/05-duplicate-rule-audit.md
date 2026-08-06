# Admin CSS Refactor — Duplicate Rule Audit

## Method

`conflict_check.ps1` compared selectors across old flat files and the new tree. Additional pairwise diff (`Compare-Object` over `.class` regex extraction) verified old flat files against their new counterparts.

## Duplicates found & resolved

| Old file | Result | Notes |
|---|---|---|
| `_sidebar.css` (22 classes) | **deleted** | 100% covered by `layout/_sidebar.css` |
| `_badges.css` (48 classes) | **deleted** | 100% covered by `components/_badges.css` |
| `_dashboard_charts.css` (45 classes) | **deleted** | 100% covered by `pages/_dashboard.css` |
| `_variables.css` (top-level) | **kept** | it IS the imported `_variables.css` |
| `_topbar.css` (25 classes) | **deleted** | 13 not in `layout/_topbar.css` — all `search-*`/`dd-*`/`av-*` legacy classes were already defined elsewhere in the new tree (verified via `final_check.ps1`: none undefined) |
| `_forms.css` (31 classes) | **deleted** | 12 `filter-*` only-old — defined in new tree (`components/_forms.css`/`_toolbar.css`), verified |
| `_modals.css` (54 classes) | **deleted** | 7 `toast-*` only-old — `toast-wrap/toast-x/toast-err/toast-ok` etc. defined in `components/_alerts.css` (`.toast` family + `.toast-close`), verified |
| `_utilities.css` (6 classes) | **deleted** | `alert-error/alert-ok` in `components/_alerts.css`; `sec-hdr/sec-ttl` in `components/_page-header.css`; `hb-count` in `pages/_dashboard.css` |
| `_buttons.css` (top-level) | **deleted after restore** | content copied 1:1 to `components/_buttons.css` (see `05-buttons-restoration.md`) |
| `components/_buttons.css` (old duplicate) | **deleted** | replaced by restored canonical file |
| `_breadcrumbs.css` | **created then deleted** | breadcrumbs already live in `components/_tabs.css` |

## Duplication guardrails in place

- `.cp-bulk-*` defined only in `components/_toolbar.css`.
- `.btn--*` canonical variants + legacy `.btn-*` mappings only in `components/_buttons.css`.
- No selector is defined in more than one `pages/` file (page order handles ties).
