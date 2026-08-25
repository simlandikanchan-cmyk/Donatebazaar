# Frontend Architecture

## Vite Entries
- **Shell**: `resources/js/app.js` — shared layout, navbar, footer, CSRF token handling.
- **Admin**: `resources/js/admin/app.js` — admin-specific shell, sidebar, tables.
- **User**: `resources/js/user/user.js` — user dashboard shell, sidebar.
- **Page-specific**: Each major page has its own entry in `vite.config.js` (e.g., `home.js`, `campaigns.js`, `campaigns-create.js`).

## Shared Modules
- `resources/js/shared/dom.js` — `$`, `$$`, `delegate()`, `onReady()`.
- `resources/js/shared/helpers.js` — `escapeHtml()`, `qs`, currency formatting.
- `resources/js/shared/toast.js` — toast notification system (`showInfoToast`, `showErrorToast`, etc.).
- `resources/js/shared/api.js` — `api()` fetch wrapper with CSRF handling.

## Admin Shell
- Layout: `resources/views/layouts/admin.blade.php`
- Sidebar: `resources/views/partials/admin-sidebar.blade.php`
- Scripts loaded via `@vite('resources/js/admin/app.js')`.

## User Modules
- Layout: `resources/views/layouts/user.blade.php`
- Sidebar: `resources/views/partials/user-sidebar.blade.php`
- Dashboard: `resources/views/dashboard.blade.php` + `resources/js/dashboard.js`.

## Blade Data Handoff
- Page-specific data passed via controller → view.
- Global data: `AppServiceProvider::shareGlobalViewData()` (site name, navigation, auth user).
- Inline scripts only for critical bootstrapping (e.g., `contenteditable` editor init).
- JSON data blobs for complex JS state: `<script type="application/json" id="...">@json($data)</script>`.

## Build
- `npm run build` → Vite production build → `public/build/`.
- `vite.config.js` handles code splitting per entry.
- CSS is extracted per page for critical-path optimization.
