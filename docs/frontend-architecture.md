# Frontend Architecture

The frontend is built on Vite with one entry per page. Global behavior — layout, navigation, CSRF — lives in a few shared modules, and everything else is scoped to the page that uses it.

## Vite Entries

- **Shell**: `resources/js/app.js` — shared layout, navbar, footer, CSRF token handling.
- **Admin**: `resources/js/admin/app.js` — admin-specific shell, sidebar, tables.
- **User**: `resources/js/user/user.js` — user dashboard shell, sidebar.
- **Page-specific**: each major page has its own entry in `vite.config.js` (e.g., `home.js`, `campaigns.js`, `campaigns-create.js`).

## Shared Modules

`resources/js/shared/` holds the modules that multiple entry points reuse:

- `dom.js` — `$`, `$$`, `delegate()`, `onReady()`.
- `helpers.js` — `escapeHtml()`, `qs`, currency formatting.
- `toast.js` — toast notification system (`showInfoToast`, `showErrorToast`, etc.).
- `api.js` — `api()` fetch wrapper with CSRF handling.

## Admin Shell

- Layout: `resources/views/layouts/admin.blade.php`
- Sidebar: `resources/views/partials/admin-sidebar.blade.php`
- Scripts loaded via `@vite('resources/js/admin/app.js')`.

## User Modules

- Layout: `resources/views/layouts/user.blade.php`
- Sidebar: `resources/views/partials/user-sidebar.blade.php`
- Dashboard: `resources/views/dashboard.blade.php` + `resources/js/dashboard.js`.

## Blade Data Handoff

- Page-specific data is passed controller → view.
- Global data (site name, navigation, auth user) is shared via `AppServiceProvider::shareGlobalViewData()`.
- Inline scripts are reserved for critical bootstrapping (e.g., `contenteditable` editor init).
- Complex JS state is handed off as JSON blobs: `<script type="application/json" id="...">@json($data)</script>`.

## Build

- `npm run build` produces the Vite production build in `public/build/`.
- `vite.config.js` handles code splitting per entry.
- CSS is extracted per page for critical-path optimization.