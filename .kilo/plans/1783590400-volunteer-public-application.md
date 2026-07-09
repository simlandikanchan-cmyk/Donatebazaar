# Public Volunteer Application — Implementation Plan

## Context
The site exposes `GET /volunteer/apply` (`volunteer.apply`, `web` middleware only) but the
flow is **broken and incomplete**:
- `VolunteerController::apply()` (`app/Http/Controllers/VolunteerController.php:12`) calls
  `auth()->user()` with no `auth` middleware (guest → fatal `null->volunteer`), references an
  **undefined `$campaign`** (line 45), and performs a **duplicate** `VolunteerApplication::create`.
- `volunteers.campaign` route (`routes/web/volunteer.php:9`) points to `campaignVolunteers()`,
  which **does not exist** in the controller.
- **No public form/page exists** — nothing in the Blade views references `volunteer.apply` or
  `volunteers.campaign`. So the route is unreachable by users.

Goal (confirmed with user): build a **full public volunteer application flow** + add a
**"Volunteer" nav link** to the About dropdown.

## Key constraints (from code)
- `volunteers.user_id` is a **required** FK (`app/Models/Volunteer.php`), so a Volunteer is
  always bound to a User. A truly anonymous volunteer would need a schema change (out of scope).
- `volunteer_applications`: `volunteer_id` required, `campaign_id`/`ngo_id` nullable,
  `message` nullable, `status` enum default `pending`. Unique indexes on
  `(volunteer_id, campaign_id)` and `(volunteer_id, ngo_id)` already prevent duplicates at DB level
  (`database/migrations/2026_04_15_000007_prevent_duplicate_volunteer_applications.php`).
- `login` route name exists (`routes/web/auth.php:19`).

## Decisions
1. **Page is public (GET), submission requires auth.** Anyone can view `/volunteer/apply`; on
   submit, guests are redirected to `login` (intended URL) with a flash notice. No schema change.
2. **Keep `volunteer.apply` name for the GET form page.** Add `POST /volunteer/apply` →
   `store()` named `volunteer.apply.store`. Remove the old broken `apply()` action.
3. **Single application row** with `ngo_id` derived from the chosen campaign
   (`$campaign->ngo_id`); dedupe check before insert.
4. **Mirror the partnership page UX** (`resources/views/frontend/partnership.blade.php`):
   public layout `layouts.app`, same `.toast-stack` + `toast()` JS, and
   `@if(session('success'|'error'))` → toast. (Optional: extract a shared
   `partials/toast.blade.php` + JS snippet to avoid duplication.)
5. **Add the missing `campaignVolunteers($id)`** (auth-gated) returning a minimal view listing
   applicants for a campaign (volunteer/user + status). Keeps the existing route from 404-ing.

## Tasks
1. **Routes** — `routes/web/volunteer.php`:
   - `GET /volunteer/apply` → `VolunteerController@create` (name `volunteer.apply`).
   - `POST /volunteer/apply` → `VolunteerController@store` (name `volunteer.apply.store`).
   - Keep `volunteers.campaign` (auth) and `admin.volunteer.status` (admin) as-is.
2. **Controller** — `app/Http/Controllers/VolunteerController.php`:
   - `create()`: return `view('volunteer.apply')` (no auth). Optionally prefill from
     `auth()->user()`.
   - `store(Request $request)`:
     - If `! auth()->check()` → `redirect()->route('login')->with('error', 'Please log in to volunteer.')`
       (Laravel stores intended URL automatically).
     - Validate: `campaign_id` nullable `exists:campaigns,id`; `ngo_id` nullable
       `exists:ngos,id`; `message` nullable `string|max:1000`.
     - `$volunteer = Volunteer::firstOrCreate(['user_id' => auth()->id()])`.
     - Resolve `ngo_id`: if `campaign_id` present, load campaign and use its `ngo_id`; else use
       validated `ngo_id`.
     - Dedupe: skip if `VolunteerApplication::where(['volunteer_id'=>$volunteer->id,
       'campaign_id'=>$request->campaign_id, 'ngo_id'=>$ngoId])->exists()` →
       `back()->with('error','Already applied.')`.
     - Create **one** `VolunteerApplication` (volunteer_id, campaign_id, ngo_id, message, status
       `pending`). Redirect `back()->with('success','Application submitted!')`.
   - `campaignVolunteers($id)`: auth; load campaign + its applications with `volunteer.user`;
     return `view('volunteer.campaign', compact('campaign','applications'))` (or
     `abort(403)` if the current user isn't the campaign owner/NGO — keep minimal/permissive for
     now, document as a follow-up).
3. **View** — `resources/views/volunteer/apply.blade.php` (extends `layouts.app`):
   - Hero/intro ("Volunteer With Us"), short copy.
   - Form `action="{{ route('volunteer.apply.store') }}"` method POST with `@csrf`.
   - Fields: optional **Campaign** `<select>` of active campaigns (name `campaign_id`,
     `value=id`, label = title), **Message** `<textarea name="message">`.
   - If guest: show a notice banner with Login/Register links and disable/guard submit (server
     still redirects).
   - Include `.toast-stack` div + `toast()` JS (copy from partnership page) and the
     `@if(session('success'))` / `@if(session('error'))` toast triggers.
   - Style with existing public CSS (`resources/css/app.css`, `navbar.css`); reuse card/input
     classes from the partnership page for visual consistency.
4. **Campaign volunteers view** — `resources/views/volunteer/campaign.blade.php`: simple list
   (avatar/name, message, status badge). Minimal.
5. **Nav link** — `resources/views/layouts/navigation.blade.php`: add
   `<a href="{{ route('volunteer.apply') }}" …>Volunteer</a>` after Disaster Relief in **both**
   the desktop About dropdown and the mobile `--sub` list (mirror Partnership/Disaster Relief).

## Files
- Edit: `routes/web/volunteer.php`, `app/Http/Controllers/VolunteerController.php`,
  `resources/views/layouts/navigation.blade.php`
- Create: `resources/views/volunteer/apply.blade.php`,
  `resources/views/volunteer/campaign.blade.php`

## Validation
- `php artisan route:list | findstr volunteer` → `volunteer.apply` (GET), `volunteer.apply.store`
  (POST), `volunteers.campaign` (auth) all present; no duplicate/collision.
- `php -l` on the controller.
- Render check (tinker): `view()->file(base_path('resources/views/volunteer/apply.blade.php'),
  [])->render()` compiles; `VolunteerController::create()` returns HTML.
- Manual (browser, `php artisan serve`):
  - Guest visits `/volunteer/apply` → page renders; submit → redirected to `/login`.
  - Logged-in user submits with a campaign selected → success toast; exactly **one** row in
    `volunteer_applications` with correct `ngo_id`; duplicate submit → error toast.
  - `/campaign/{id}/volunteers` (logged in) → lists applicants (no 404).

## Risks / open questions
- **Guest volunteers** are out of scope (would require nullable `user_id` + new form fields).
  If the user later wants anonymous applications, that's a separate schema + model change.
- `campaignVolunteers` authorization is left permissive (auth only); tighten to campaign
  owner/NGO in a follow-up if needed.
- Form currently collects only an optional campaign + message. Skills/availability/bio exist on
  the `volunteers` table but are not captured here (could be added later).
