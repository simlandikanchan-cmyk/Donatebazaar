# Admin Dashboard — UI/UX Enhancements

## Context
Page: `http://127.0.0.1:8000/admin/dashboard` → `resources/views/admin/dashboard.blade.php`
Controller: `app/Http/Controllers/Admin/DashboardController.php`
Layout/shared JS/CSS: `resources/views/layouts/admin.blade.php`, `resources/js/admin.js`, `resources/css/admin.css`

**Already implemented (do NOT rebuild):** dark-mode toggle (`admin.js:19-31`, persists `localStorage.adminTheme`, sets `data-theme`) and the toast system (`admin.js:72-106`, reads `session('success'|'error'|'warning')` from the `toast-wrap` div in the layout). Campaign actions already set these flashes (`CampaignController::approve/reject/pause/resume` → `back()->with(...)`). There is also an existing bulk-action pattern to mirror in `CampaignProductController::bulkApprove/bulkReject` (`routes/admin/campaigns.php:27-28`).

**Real gaps this plan addresses:**
1. No bulk actions on campaigns (select many → approve/reject/pause).
2. No quick-view (clicking a card navigates away; no in-page detail panel).
3. Performance: only `activeCampaigns` is paginated (`DashboardController:60` `paginate(12)`); `pending/rejected/inactive` use `get()` and render every row. Client-side JS hides/shows cards across all lists, which breaks pagination and is heavy at scale.
4. No per-filter empty states via server (only a single client `#noResults`).
5. Charts render once on load; toggling theme does not re-render them, so grid/label colors look stale until reload.

## Goal
Enhance the dashboard UX by adding: (a) bulk campaign actions, (b) an in-page quick-view slide-over, (c) a server-driven AJAX campaign grid with pagination across all states + search/sort/filter, (d) chart re-render on theme toggle, and polish (selection bar, loading states, empty states). Keep existing visual language (CSS vars, cards, modals, toasts).

---

## Task 1 — Server-driven campaign grid (performance + empty states)
Replace the client-side hide/show grid with an AJAX grid so all states paginate uniformly.

**New controller method** — `DashboardController::campaigns(Request $request)`:
- Accept `state` (`all|pending|active|paused|rejected|inactive`), `search`, `sort` (`amount-desc|amount-asc|date-desc|date-asc`), `page`.
- Build one `Campaign::with('user','category')` query scoped by `state` (reuse the same state/expiry logic from `index()`), apply `search` on `title` (ILIKE/`like`), apply `sort`, then `paginate(12, ['*'], 'cpage')`.
  - Map `state`→`campaign_state` where applicable; `inactive` = expired+completed+date-passed active (mirror `index()`'s `$inactiveCampaigns` sub-query).
  - `all` = union excluding nothing (full list). Keep `active` excluding date-expired per existing rule.
- Return a Blade fragment (not full page). Add `@if($request->ajax())` branch in `index()` OR a dedicated `campaigns()` returning `view('admin._campaign_cards', compact('campaigns'))` plus counts.

**New partial** — `resources/views/admin/_campaign_cards.blade.php`:
- Move the four card loops from `dashboard.blade.php` (lines ~174-257) into this partial, driven by a single `$campaigns` collection and switching action buttons by `$c->campaign_state` (pending→Approve/Reject; active→Pause/View; paused→Resume/View; rejected→View + reason; inactive→View + Completed/Inactive label). Keep the same markup/classes (`c-card`, `data-filter`, `data-title`, `data-amount`, `data-date`) and per-card checkbox hook (see Task 2).
- When empty, render the existing `#noResults` markup (or return an `empty` flag).

**`dashboard.blade.php` changes:**
- Render the grid initially via `@include('admin._campaign_cards', ['campaigns' => $activeCampaigns])` (default tab = Active) inside `#campaignGrid`.
- Replace the bottom JS filter/sort logic (lines ~452-493) with an `fetchGrid()` that `fetch()`es `admin.dashboard.campaigns?state=&search=&sort=&page=`, swaps `#campaignGrid` innerHTML, updates `#ftabs` counts and the `cGrid` pagination (`$campaigns->links('vendor.pagination.admin')` returned in fragment or separate), shows `#noResults` when empty. Debounce search (180ms). Keep filter-tab + sort-select + search box.
- Add route `GET /admin/dashboard/campaigns` → `DashboardController::campaigns` (name `admin.dashboard.campaigns`) in `routes/admin/dashboard.php`.

**`DashboardController::index()`** can keep computing the stat counts/charts as today; counts for tab badges come from the same counts already computed.

## Task 2 — Bulk actions
**Routes** (`routes/admin/campaigns.php`, mirror bulk pattern):
- `POST /admin/campaigns/bulk-approve` → `CampaignController::bulkApprove` (`admin.campaigns.bulk-approve`)
- `POST /admin/campaigns/bulk-reject` → `CampaignController::bulkReject` (`admin.campaigns.bulk-reject`)
- `POST /admin/campaigns/bulk-pause` → `CampaignController::bulkPause` (`admin.campaigns.bulk-pause`)

**Controller** (`CampaignController`): add `bulkApprove`/`bulkReject`/`bulkPause` modeled on `CampaignProductController::bulkApprove/bulkReject` (validate `ids` array + `ids.* exists:campaigns,id`; `reason` required for reject). Loop, apply only where state permits (approve→pending, pause→active, reject→pending), send `CampaignStatusMail` for approve/reject, log each, return `back()->with('success', "N campaign(s) <action>.")` and `with('warning', ...)` for skipped ones. Honor KYC gating for approve (reuse logic from `approve()`).

**Frontend:**
- Add a checkbox (`.c-check`) top-left of each card in `_campaign_cards.blade.php`.
- A floating **bulk action bar** (`.bulk-bar`) appears when ≥1 selected, showing "{n} selected", buttons Approve / Pause / Reject, and Clear. Maintain a `Set` of selected ids (survives grid re-render by re-checking on swap).
- Approve/Pause → POST the ids (Pause opens the existing pause reason modal in bulk mode, wiring `bulk-pause`). Reject → opens a bulk reject modal (reuse reject modal markup, set form action to `bulk-reject`). Use `fetch` + CSRF; on success re-fetch grid and `window.toast(...)`. Disable buttons while submitting.

## Task 3 — Quick-view slide-over
**Route** (`routes/admin/campaigns.php`): `GET /admin/campaign/{campaign}/quick` → `CampaignController::quick` (`admin.campaign.quick`), returns a Blade partial (not full `show`).
- Reuse `show()` loading (`user.kycVerification`, `category`, `events`, `logs`) but return `view('admin._campaign_quick', compact('campaign'))` (HTML fragment with image, owner, goal/raised progress, status, dates, description excerpt, and action buttons linking to existing approve/reject/pause/show routes).

**Frontend:**
- Add a right-side slide-over (`.slide-over` + `.slide-over-backdrop`) in `dashboard.blade.php`.
- Clicking a card's body (excluding buttons, checkbox, links) calls `openQuick(id)` → `fetch` the quick partial → inject → add `.open`. Close on backdrop click / Esc (reuse modal keydown pattern in `admin.js`).

## Task 4 — Chart re-render on theme toggle + polish
- In `admin.js` theme `change` handler (line ~26), after setting `data-theme`, `window.dispatchEvent(new Event('themechange'))`.
- In `dashboard.blade.php` script, listen for `themechange` and re-run `loadChart()` + `loadDoughnut()` (they already destroy/rebuild and re-read theme colors).
- Loading state: show a `.c-grid.skeleton` shimmer while `fetchGrid()` is in flight; disable sort/inputs briefly.
- Ensure focus management/aria on modals and slide-over (`role="dialog" aria-modal="true"` already present; add `aria-labelledby`).

## Task 5 — CSS additions (`resources/css/admin.css`)
Add styles for: `.c-check` (card checkbox overlay), `.bulk-bar` (fixed bottom floating bar with slide-up animation), `.slide-over` + `.slide-over-backdrop` (right panel transform transition), `.c-grid.skeleton` shimmer cards, and bulk/pause/reject modal reuse tweaks. Keep using existing CSS vars (`--a`, `--green`, `--amber`, `--red`, `--sh-*`, `--r-*`) and toast classes already defined.

---

## Files to create
- `resources/views/admin/_campaign_cards.blade.php` (moved card loops + checkbox)
- `resources/views/admin/_campaign_quick.blade.php` (quick-view fragment)
- `app/Http/Controllers/Admin/DashboardController::campaigns()` (new method)

## Files to edit
- `resources/views/admin/dashboard.blade.php` (use partial, AJAX grid JS, bulk bar, slide-over, chart re-render listener)
- `app/Http/Controllers/Admin/DashboardController.php` (`index()` simplification + `campaigns()`)
- `app/Http/Controllers/Admin/CampaignController.php` (bulk methods)
- `routes/admin/dashboard.php` (campaigns AJAX route)
- `routes/admin/campaigns.php` (bulk + quick routes)
- `resources/js/admin.js` (dispatch `themechange`)
- `resources/css/admin.css` (bulk bar, slide-over, skeleton, checkbox)

## Validation
1. `php artisan serve` and load `/admin/dashboard` — stats/charts/quick-nav unchanged.
2. Toggle theme → charts re-render with correct colors; choice persists across reload.
3. Filter tabs + search + sort paginate correctly across all states; empty state shows when no matches.
4. Select multiple cards → bulk bar appears → Approve/Pause/Reject work; toasts show; grid refreshes; skipped items reported via warning toast.
5. Click a card body → slide-over shows details; Esc/backdrop closes.
6. Approve/reject/pause single actions still post and show success/error toasts (regression).
7. Run `php artisan route:list | findstr dashboard` / `campaigns` to confirm new routes; check no JS console errors.

## Risks / decisions
- **AJAX grid is the main refactor.** Fallback if too risky: keep separate per-state `paginate()` lists with independent page params and drop cross-list client filtering (less ideal, but lower risk). Recommended: AJAX grid (Task 1) for uniform behavior.
- Bulk approve must keep KYC gating; non-pending items are skipped (not errored) and summarized.
- Keep existing `vendor.pagination.admin` view for pagination HTML inside the fragment.
