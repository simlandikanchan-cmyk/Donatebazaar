# Admin CSS Refactor — Buttons Restoration (Incident Report)

## What happened

During the legacy-file cleanup, `resources/css/admin/components/_buttons.css` (the canonical button system, imported at position 11 of `admin.css`) was mistakenly deleted — it had been flagged as a "duplicate", but the surviving top-level `_buttons.css` was the source content and the components/ copy was the live import target. The next `npm run build` was at risk of failing.

## Detection

The post-cleanup import-integrity check listed `components/_buttons.css` as the only MISSING import (the 4 `base/*` entries were false alarms from a bad path join — `@import '../base/...'` resolves correctly).

## Resolution

`components/_buttons.css` was restored **1:1** from the surviving top-level `resources/css/admin/_buttons.css` (223 lines), which contained the identical canonical `.btn` system:

- `.btn` base + hover/active/focus/disabled states
- spinner (`btn-spin` keyframes, reduced-motion)
- legacy button family (`.action-btn`, `.btn-primary`, `.bulk-btn`, `.export-btn`, `.cp-btn-clear`, `.gc-action-btn`, `.modal-y-btn`, …) with shared state rules
- legacy variant mappings via CSS custom properties (`--btn-bg`, `--btn-fg`, …)
- canonical variants (`.btn--primary/secondary/outline/ghost/destructive/link/active`)
- sizes/modifiers (`.btn--sm/md/lg`, `--full`, `--pill`, `--icon`) + legacy `.btn-sm/.btn-lg/.btn-block`
- JS loading markers (`.btn.is-loading`, `.btn-spinner`)

Then the top-level `_buttons.css` (now identical) was deleted as the duplicate.

## Verification

- `npm run build` → success; built `public/build/assets/admin-Bgvzs2_L.css` (247.1 kB) — CSS output size matches the pre-incident build (~246 kB + later additions).
- `final_check.ps1` → no undefined classes; every `btn-*` class used by views resolves.
- `x-button` component (`resources/views/components/button.blade.php`) emits `btn btn--{variant} btn--{size}` — all covered by the restored file.

## Lesson

When deleting "duplicate" files, verify which copy the import graph actually resolves to BEFORE deletion. The `admin.css` import list is the single source of truth.
