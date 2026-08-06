# Admin CSS Refactor — Shared Component Inventory

Every component file, its exported classes, and usage guidance. Exported = defined here; many are used across multiple admin pages.

## `components/_buttons.css` — canonical button system
- `.btn` (+ states: hover/active/focus-visible/disabled), `.btn__label`, `.btn__icon`, `.spinner`/`.btn-spinner`, `.btn.is-loading`
- Canonical: `.btn--primary` `.btn--secondary` `.btn--outline` `.btn--ghost` `.btn--destructive` `.btn--link` `.btn--active` `.btn--sm/md/lg` `.btn--full` `.btn--pill` `.btn--icon`
- Legacy (used by raw `<button class="btn-*">`): `.btn-primary` `.btn-secondary` `.btn-ghost` `.btn-edit` `.btn-red` `.btn-modal-cancel` `.btn-modal-delete` `.bulk-btn` `.export-btn` `.filter-btn` `.clear-btn` `.clear-all-btn` `.cover-preview-btn(.change)` `.doc-btn` `.editor-btn` `.slug-lock-btn` `.cp-btn-clear` `.gc-btn-clear` `.gc-action-btn` `.modal-y-btn` `.action-btn` `.cv-btn` + sizes `.btn-sm` `.btn-lg` `.btn-block`
- Rendered by `resources/views/components/button.blade.php` (`<x-button>`)

## `components/_badges.css`
`.tag` `.tag-amber/-blue/-green/-red`, `.type-pill` `.role-pill` `.cat-pill` `.pri-pill` `.mono-pill` `.remote-pill` `.slug-pill`, `.stock-pill` `.stock-ok/-low/-out` `.stock-badge`, `.admin-badge`, `.campaign-badge`

## `components/_alerts.css`
`.flash` `.flash-*`? (base), `.toast` `.toast-wrap` `.toast-x` `.toast-close` `.toast-ok/-err/-warn/-undo`, `.alert-ok` `.alert-error`

## `components/_cards.css`
`.ci-amber/-blue/-gray/-green/-purple/-teal`, `.card-hdr*` `.card-ico` `.card-ttl`, `.card-title-icon` `.card-title-badge` `.card-subtitle`, `.card-section`, `.card-link`, `.side-card` `.side-stack`, `.content-card` `.content-header` `.content-grid`, `.detail-card/-hdr/-grid/-item/-lbl/-key`, `.meta-card/-key/-val/-info/-lbl/-name/-desc-footer`, `.accent-card`, `.info-box/-grid/-item/-lbl/-value/-list`

## `components/_forms.css`
`.form-group/-row/-input/-select/-textarea/-control` `.is-invalid` `.field-*` `.error-msg` `.input-row` `.form-grid` `.form-layout` `.form-actions` `.form-note/-hint/-error`, `.inp-wrap` `.inp-icon`, `.inp` `.ta` `.sel` `.sel-active/-draft/-cancelled`, `.slug-*` family, `.tags-wrap` `.tag-input`, `.toggle-*` legacy family, `.check-row`, `.upload-*`, `.stepper` `.step-*`, `.img-preview` `.img-remove` `.current-img*`, plus legacy `filter-*` bar: `.filter-bar/-row/-right/-group/-inp/-sel/-date/-lbl/-div/-spacer/-reset/-count`

## `components/_tables.css`
`.row-check` `.chk` `.action-cell` `.id-cell` `.num-cell` `.td-actions` `.td-mono` `.td-name` `.td-sub` `.tbl-av` `.tbl-av-ph`

## `components/_toolbar.css`
`.toolbar` `.toolbar-actions` `.toolbar-filters` `.cp-bulk-approve/-reject/-pause/-clear` (shared bulk buttons)

## `components/_pagination.css`
`.pg` `.pg-page` `.pg-page.active` `.pg-active` `.pg-info` etc.

## `components/_tabs.css`
Tabs + **breadcrumbs** (`.ftab*` family, `.ftab-active`, breadcrumb classes)

## `components/_page-header.css`
`.sec-hdr` `.sec-ttl` (section headers used by most index pages), `.page-hdr` `.page-ttl`

## `components/_hero.css`
`.hero` `.hero-*` (dashboard hero, badges, stat chips)

## `components/_stats.css`
`.stat-card/-label/-value/-delta/-foot`, `.stat-icon-wrap/-icon`, `.delta-up`, `.admin-stat-grid`, `.stats-grid`

## `components/_dropdowns.css`
`.dd-*`, `.dropdown-*`, `.av-dd`, `.dd-hdr/-item/-name/-email/-sep`

## `components/_modals.css`
`.modal*` `.overlay*` `.open` (show-state), `.modal-body`, `.modal-y-btn`, `.lightbox-close`, `.imageLightbox` helpers

## `components/_empty-state.css`
`.empty-*`

## `layout/_content.css` / `_sidebar.css` / `_topbar.css`
Shell: `.shell` `.main` `.body` `.sidebar`/`#sidebar` `#sidebarOverlay` `.hamburger`; topbar `.tb-*`; sidebar `.s-*`, `.s-logo`, menu groups
