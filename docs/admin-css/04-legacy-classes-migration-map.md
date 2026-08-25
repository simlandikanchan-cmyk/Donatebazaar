# Admin CSS Refactor — 646 Legacy Classes Migration Map

All 646 legacy custom classes used by live admin views were relocated. Sources were: the flat old files (`_pages.css`, `_layout.css`, `_tables.css`, `_campaigns.css`, `_colors.css`, `_dashboard_stats.css`), the `121 old-only rules` audit, and the two inline `<style>` blocks.

## Family → Destination

| Family (prefix/examples) | Destination file |
|---|---|
| `shell`, `main`, `body`, `sidebar`/`#sidebar`, `#sidebarOverlay`, `hamburger`, topbar/sidebar internals (`tb-*`, `s-*`) | `layout/_content.css`, `layout/_sidebar.css`, `layout/_topbar.css` |
| Media queries (≤1400…≤380) | `layout/_responsive.css` |
| `.btn`, `btn-*` (canonical + legacy) | `components/_buttons.css` |
| `tag*`, `*pill*`, `*badge`, `*chip*` | `components/_badges.css` |
| `flash*`, `toast*`, `alert-ok/-error` | `components/_alerts.css` |
| Card family (`card*`, `ci-*`, `detail-*`, `meta-*`, `info-*`, `accent-card`, `side-card`) | `components/_cards.css` |
| `form-*`, `field-*`, `inp/ta/sel`, `toggle-*`, `slug-*`, `upload-*`, `stepper`, `check-row` | `components/_forms.css` |
| `table*`, `row-*`, `td-*`, `.chk`, `.action-cell`, `.id-cell`, `.num-cell`, `.tbl-av` | `components/_tables.css` |
| `filter-*` (bar/inp/sel/date/div/group/lbl/reset/right/row/spacer/count) | `components/_forms.css` + `components/_toolbar.css` |
| `pg-*`, `.pg-page.active`, `.pg-active` | `components/_pagination.css` |
| `ftab*`, breadcrumbs | `components/_tabs.css` |
| `hdr`, `page-hdr`, `page-ttl` | `components/_page-header.css` |
| `hero-*`, `hero-badge` | `components/_hero.css` |
| `stat-*` (cards, label, value, delta, foot) | `components/_stats.css` |
| `dd-*`, `dropdown-*` | `components/_dropdowns.css` |
| `modal*`, `overlay`, `.open` show-state | `components/_modals.css` |
| `empty-*` | `components/_empty-state.css` |
| Dashboard (`impact-*`, `hb-*`, `qk-*`, `chart-*`, `don-*`, `read-stats`, campaign-table rows) | `pages/_dashboard.css` |
| Campaigns (`c-*` page, `stat-val`, `sv-paused`, `date-input`, `filter-sep`, `cat-tag`, `qk-*`) | `pages/_campaigns.css` |
| Categories/products (`preview-*`, `cat-*`, `fa-*`, `f-textarea`, `cat-grid-item`, `product-*`, `cp-bulk-*` (shared) + `cp-del`, `cp-meta`, `lightbox-close`) | `pages/_categories.css`, `pages/_products.css` |
| Finance: `don-*`/`dn-*`, `sett-*`, wallets `w-*`, coupons `cp-*` (26), gift-cards `gc-*` (29), `.k/.v`, `gc-date`, `gc-action-resend` | `pages/_finance.css` |
| Messages (`msg-*`, `conv-*`, `filter-*` bar) | `pages/_messages.css` |
| Blogs (`sn/sl/prose-area`, `bar-track/fill`, `ready-*`, `sd-*`, `title-*`, `blog-thumb`, `author-*`) | `pages/_blogs.css` |
| Events (`ev-*`, `participants-*`, `organizer-name`, `prog-*`, `.val`) | `pages/_events.css` |
| Jobs (`hero-strip`, `prev-*`, `preview-ttl-row`) | `pages/_jobs.css` |
| Organizations (`org-*`, `side-*`, `p-card`, `accent`) | `pages/_organizations.css` |
| Applications (volunteers/jobs) | `pages/_applications.css` |
| Levels/contacts/misc (`def-pill`, `apr-pill`, `level-attrs`, `.attr`, `contact-row`, `modal-body`, tint utilities `ic-*`, `.sv-*`, `resize-none`) | `pages/_misc.css` |
| Generic helpers (`.hide`, `.full`, `.muted`, `.grid-2`, `.scroll-x`) | `utilities/_helpers.css` |

## Inline `<style>` migration

- `coupons/index.blade.php`: 26 `cp-*` classes → `pages/_finance.css`.
- `gift-cards/index.blade.php`: 29 `gc-*` classes → `pages/_finance.css`.
- Both `<style>` blocks removed; verified programmatically (`inline_check.ps1`) that every inline class exists in the new tree before removal.

## Dead classes (correctly NOT defined)

- ~120 classes used only by `dashboard_yoyo.blade.php` (deleted view): `main-layout`, `side-col`, `side-panel`, `donut-wrap`, `sp-*`, `topbar-*`, `theme-toggle*`, `t-user*`, `avatar-dropdown`, `s-upgrade*`, `c-*` cluster, `toast-container`, `reason-y/r`, `chip-y/r`, `js-*`. Do not define these.
