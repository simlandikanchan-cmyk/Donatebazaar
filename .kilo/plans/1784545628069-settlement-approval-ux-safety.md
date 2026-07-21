# Settlement Approval Screen — UI/UX & Safety-Rail Pass

## Goal
Improve the admin settlement approval screen with clearer labeling, confirmation
guards, client-side validation, verified scrutiny flags, and contextual links.
This is a **UI/UX + safety-rail pass only**.

## Hard Constraints
- **DO NOT modify** `WalletService::approveSettlement()` or
  `WalletService::rejectSettlement()` transaction/balance logic.
- `initiatePayout()` remains a placeholder (logs + returns `PAYOUT_{id}_{time}`);
  do not integrate a real gateway in this pass.
- Match the existing inline-style / CSS-variable conventions in the blade file
  (`var(--red)`, `var(--green)`, `var(--mono)`, etc.). No new CSS framework.
- Implementation requires source edits → must be done by an implementation-capable agent.

## Affected Files
- `resources/views/admin/settlements/show.blade.php` (tasks 1, 2, 3, 5)
- `app/Http/Controllers/Admin/SettlementController.php` (task 4 — verify only, likely no change)

## Context / Verified Facts
- View forms live at lines 157–176 (approve form ~159–165, reject form ~166–174).
- Approve button label is `Approve & Pay` (line 163).
- Gateway Reference stat card is lines 49–52.
- `$flags` array is already passed to the view and rendered at lines 55–69.
- Reject input is single-line `<input type="text" name="reason" required>` (line 168).
- Settlement items table is lines 88–107; view eager-loads
  `settlementItems.donation.campaign`, so `$item->donation` and
  `$item->donation->campaign` are available.
- Route names confirmed:
  - Donation detail (admin): `admin.donations.show` → `/admin/donations/{donation}`
  - Campaign detail (admin): `admin.campaign.show` → `/admin/campaign/{campaign}`
- **Task 4 flag already exists**: `SettlementController@show()` lines 63–70 build a
  `"{n} refund(s) in last 30 days"` flag via
  `Refund::whereHas('donation', fn → user_id = $org->user_id)`. Relationship
  `Refund->donation()` and `Donation.user_id` both exist, so it is structurally sound.

## Workflow
Do the tasks **in order (1 → 2 → 3 → 4 → 5)**. After each task, show the diff and
get user confirmation before starting the next.

---

### Task 1 — Fix misleading button label + add simulated-payout note
1. Change approve button text (line 163) from `Approve &amp; Pay` to
   `Approve Settlement`.
2. Add a small warning note near the Gateway Reference card (lines 49–52), e.g. a
   muted/amber line: `Simulated payout — gateway integration pending`. Place it as
   a `stat-foot`-style sub-line or a small caption under the card so a future
   reviewer knows no real transfer occurred.
- Acceptance: button no longer implies money moved; note is visible near the
  gateway reference regardless of settlement status.

### Task 2 — Confirmation before approve/reject
- Add a JS `confirm()` guard on submit for **both** forms (approve + reject).
  A modal is acceptable only if the admin layout already provides one; otherwise
  use `onsubmit="return confirm(...)"`.
- If the settlement has scrutiny flags (`!empty($flags)`), the confirmation text
  must call it out, e.g.:
  `This settlement is flagged for review. Are you sure you want to approve it?`
  Non-flagged text: `Approve this settlement? This will lock and debit the funds.`
- Reject confirmation: `Reject this settlement and return funds to balance?`
- Acceptance: submitting either form triggers a confirm dialog; approve dialog
  wording changes when `$flags` is non-empty.

### Task 3 — Enforce non-empty rejection reason client-side
1. Change reject input (line 168) from `<input type="text">` to a small
   `<textarea name="reason" rows="2">` keeping `required`, placeholder, and styling
   consistent (width ~260px, same border/background vars).
2. Disable the Reject button while the textarea is empty/whitespace; enable it once
   it has content (inline JS: `oninput` toggling `disabled`; start disabled).
- Keep server-side validation untouched (controller already `required`; service
  throws on empty).
- Acceptance: Reject button starts disabled, enables only with non-empty content;
  textarea replaces the single-line input.

### Task 4 — Verify the "refund count in last 30 days" scrutiny flag
- **Finding: the flag already exists and should render** (controller lines 63–70,
  rendered via `$flags` at lines 55–69). No fix required for existence.
- Action: confirm it renders by reasoning/inspection; only adjust if a real defect
  is found. One correctness note to validate: it attributes refunds by matching the
  donor `Donation.user_id` to `$org->user_id` (the org owner). This matches the
  wallet system's owner-resolution convention, so treat as correct unless the user
  wants org-campaign-scoped attribution instead (out of scope otherwise).
- Acceptance: with ≥1 refund in the last 30 days for the org owner, the
  `"N refund(s) in last 30 days"` item appears in the "Needs extra scrutiny" box.

### Task 5 — Make settlement items clickable
- In the items table (lines 98–104):
  - Wrap the donation id cell in a link to `route('admin.donations.show', $item->donation_id)`.
  - Wrap the campaign name cell in a link to
    `route('admin.campaign.show', $item->donation->campaign)` when
    `$item->donation?->campaign` exists; otherwise render plain `—` as today.
- Style links to inherit color (use `var(--a)` or underline on hover) so the table
  stays readable; open in same tab.
- Acceptance: donation id links to admin donation detail; campaign name links to
  admin campaign detail; missing campaign still shows `—` with no broken link.

---

## Validation
- Load `/admin/settlements/{id}` for a `pending_approval` settlement:
  - Approve button reads "Approve Settlement"; simulated-payout note visible.
  - Approve/Reject each prompt a confirm dialog; flagged settlements get the
    "flagged for review" wording.
  - Reject button disabled until textarea has content.
  - Settlement item donation/campaign links navigate correctly.
- Load a settlement with a recent refund on the org owner → refund flag appears.
- Confirm no changes to `WalletService` approve/reject transaction logic
  (`git diff app/Services/WalletService.php` should be empty).

## Risks
- Route-model-binding: pass the model/id that matches each route's binding
  (`{donation}`, `{campaign}`). Verified both use default binding.
- Disabled-button JS must not block keyboard/paste input edge cases — use `oninput`
  (fires on paste) and trim before toggling.

## Open Questions
- None blocking. (Optional: whether refund-flag attribution should be
  campaign-scoped rather than org-owner-scoped — currently out of scope.)
