<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\EventPublishedMail;
use App\Models\Campaign;
use App\Models\Category;
use App\Models\Event;
use App\Models\Volunteer;
use App\Models\VolunteerAssignment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class EventController extends Controller
{
    /* ─────────────────────────────────────────
     | INDEX
     ───────────────────────────────────────── */
    public function index(Request $request): View
    {
        $query = Event::query()
            ->with(['campaign', 'user'])
            ->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $query->whereDate('event_date', $request->date);
        }

        $events = $query->paginate(15)->withQueryString();

        // Stats for hero badges
        $stats = [
            'total'     => Event::count(),
            'active'    => Event::where('status', Event::STATUS_ACTIVE)->count(),
            'pending'   => Event::where('status', Event::STATUS_PENDING)->count(),
            'draft'     => Event::where('status', 'draft')->count(),
            'completed' => Event::where('status', Event::STATUS_COMPLETED)->count(),
            'cancelled' => Event::where('status', Event::STATUS_CANCELLED)->count(),
            'expired'   => Event::where('status', Event::STATUS_EXPIRED)->count(),
        ];

        return view('admin.events.index', compact('events', 'stats'));
    }

    /* ─────────────────────────────────────────
     | CREATE
     ───────────────────────────────────────── */
    public function create(): View
    {
        $categories = Category::active()
            ->withCount(['campaigns' => fn($q) => $q->where('campaign_state', 'active')])
            ->orderBy('name')
            ->get();

        // Grouped by category_id for the JS picker
        $campaignsByCategory = Campaign::select(
                'id', 'title', 'cover_image', 'goal_amount', 'category_id', 'campaign_state'
            )
            ->where('campaign_state', 'active')
            ->latest()
            ->get()
            ->groupBy('category_id')
            ->map(fn($group) => $group->values()->toArray())
            ->toArray();

        return view('admin.events.create', compact('categories', 'campaignsByCategory'));
    }

    /* ─────────────────────────────────────────
     | STORE
     ───────────────────────────────────────── */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'campaign_id'     => ['required', 'exists:campaigns,id'],
            'title'           => ['required', 'string', 'max:255'],
            'description'     => ['required', 'string'],
            'event_date'      => ['required', 'date'],
            'start_time'      => ['nullable'],
            'end_time'        => ['nullable', 'after:start_time'],
            'goal_amount'     => ['nullable', 'numeric', 'min:0'],
            'max_participants' => ['nullable', 'integer', 'min:1'],
            'cover_image'     => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            // draft = saved but not live, active = published/live
            'status'          => ['nullable', 'in:draft,active'],
            'location' => ['nullable', 'string', 'max:255'],
            'send_notification' => ['nullable', 'boolean'],
        ]);

        // Build unique slug
        $baseSlug = Str::slug($validated['title']);
        $slug     = $baseSlug;
        $counter  = 1;
        while (Event::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter++;
        }

        // Respect the button the admin clicked (draft or active)
        // Default to draft if nothing sent
        $validated['status']              = $request->input('status', 'draft');
        $validated['send_notification']    = $request->boolean('send_notification');
        $validated['allow_registrations']  = $request->boolean('allow_registrations');
        $validated['show_on_campaign']     = $request->boolean('show_on_campaign');
        $validated['slug']                = $slug;
        $validated['raised_amount']       = 0;
        $validated['registered_count']    = 0;
        $validated['user_id']             = auth()->id();

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request
                ->file('cover_image')
                ->store('events', 'public');
        }

        $event = Event::create($validated);

        Log::info('Admin created event', [
            'event_id' => $event->id,
            'admin_id' => auth()->id(),
            'status'   => $event->status,
        ]);

        // Notify campaign followers + creator if published with notifications on
        if ($event->status === Event::STATUS_ACTIVE) {
            $this->notifyEventPublished($event);
        }

        $message = $event->status === 'active'
            ? 'Event published successfully!'
            : 'Event saved as draft. Publish it whenever you\'re ready.';

        return redirect()
            ->route('admin.events.show', $event)
            ->with('success', $message);
    }

    /* ─────────────────────────────────────────
     | SHOW
     ───────────────────────────────────────── */
    public function show(Event $event): View
    {
        $event->load(['campaign', 'user', 'registrations.user', 'volunteerAssignments.volunteer.user'])
              ->loadCount('registrations');

        // Auto-expire if event date has passed
       // Auto-expire if event date + end_time has passed.
// Uses the model's hasEnded() helper, which combines event_date with
// end_time instead of treating event_date as midnight.
if (
    $event->status === Event::STATUS_ACTIVE &&
    $event->hasEnded()
) {
    $event->update(['status' => Event::STATUS_EXPIRED]);
    $event->refresh();
}

        return view('admin.events.show', compact('event'));
    }

    /* ─────────────────────────────────────────
     | EDIT
     ───────────────────────────────────────── */
    public function edit(Event $event): View
    {
        $event->load(['campaign', 'user']);

        $categories = Category::active()->orderBy('name')->get();

        $campaigns = Campaign::with('category')
            ->where('campaign_state', 'active')
            ->latest()
            ->get();

        return view('admin.events.edit', compact('event', 'categories', 'campaigns'));
    }

    /* ─────────────────────────────────────────
     | UPDATE
     ───────────────────────────────────────── */
    public function update(Request $request, Event $event): RedirectResponse

    
    {
        $validated = $request->validate([
            'title'           => ['required', 'string', 'max:255'],
            'description'     => ['required', 'string'],
            'campaign_id'     => ['required', 'exists:campaigns,id'],
            'category_id'     => ['nullable', 'exists:categories,id'],
            'event_date'      => ['required', 'date'],
            'start_time'      => ['nullable'],
            'end_time'        => ['nullable', 'after:start_time'],
            'goal_amount'     => ['nullable', 'numeric', 'min:0'],
            'max_participants' => ['nullable', 'integer', 'min:1'],
            'location' => ['nullable', 'string', 'max:255'],
            'cover_image'     => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            // All valid statuses an admin can manually set from edit page
            'status'          => [
                'required',
                'in:draft,active,' . implode(',', [
                    Event::STATUS_PENDING,
                    Event::STATUS_COMPLETED,
                    Event::STATUS_CANCELLED,
                    Event::STATUS_EXPIRED,
                ]),
            ],
        ]);

        // Re-slug only if title changed
        if ($event->title !== $validated['title']) {
            $baseSlug = Str::slug($validated['title']);
            $slug     = $baseSlug;
            $counter  = 1;
            while (Event::where('slug', $slug)->where('id', '!=', $event->id)->exists()) {
                $slug = $baseSlug . '-' . $counter++;
            }
            $validated['slug'] = $slug;
        }

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request
                ->file('cover_image')
                ->store('events', 'public');
        }

        $event->update($validated);

        Log::info('Admin updated event', [
            'event_id' => $event->id,
            'admin_id' => auth()->id(),
            'status'   => $event->status,
        ]);

        return redirect()
            ->route('admin.events.show', $event)
            ->with('success', 'Event updated successfully.');
    }

    /* ─────────────────────────────────────────
     | PUBLISH  (draft → active)
     ───────────────────────────────────────── */
    // public function publish(Event $event): RedirectResponse
    // {
    //     $event->update(['status' => 'active']);

    //     Log::info('Admin published event', [
    //         'event_id' => $event->id,
    //         'admin_id' => auth()->id(),
    //     ]);

    //     return back()->with('success', 'Event is now live and publicly visible!');
    // }

    // /* ─────────────────────────────────────────
    //  | REVERT TO DRAFT  (active → draft)
    //  ───────────────────────────────────────── */
    // public function draft(Event $event): RedirectResponse
    // {
    //     $event->update(['status' => 'draft']);

    //     Log::info('Admin reverted event to draft', [
    //         'event_id' => $event->id,
    //         'admin_id' => auth()->id(),
    //     ]);

    //     return back()->with('success', 'Event reverted to draft. It is no longer publicly visible.');
    // }



    public function publish(Event $event): RedirectResponse
    {
        $event->update(['status' => Event::STATUS_ACTIVE]);
        $this->notifyEventPublished($event);

        $message = 'Event published';
        if ($event->campaign && ($fc = $event->campaign->followers()->count()) > 0) {
            $message .= " — notification email sent to {$fc} follower" . ($fc !== 1 ? 's' : '');
        }
        $message .= '.';

        return back()->with('success', $message);
    }

public function draft(Event $event): RedirectResponse
{
    $event->update(['status' => Event::STATUS_PENDING]);
    return back()->with('success', 'Event reverted to draft.');
}

public function toggleSetting(Request $request, Event $event): RedirectResponse
{
    $field = $request->validate([
        'field' => ['required', 'in:allow_registrations,show_on_campaign,send_notification'],
    ])['field'];

    $event->update([$field => !$event->$field]);

    return back()->with('success', 'Setting updated.');
}
    /* ─────────────────────────────────────────
     | DESTROY
     ───────────────────────────────────────── */
    public function destroy(Event $event): RedirectResponse
    {
        $eventId = $event->id;
        $event->delete();

        Log::warning('Admin deleted event', [
            'event_id' => $eventId,
            'admin_id' => auth()->id(),
        ]);

        return redirect()
            ->route('admin.events.index')
            ->with('success', 'Event deleted successfully.');
    }


    /* ─────────────────────────────────────────
 | APPROVE  (pending → active)
 ───────────────────────────────────────── */
    public function approve(Event $event): RedirectResponse
    {
        $event->update(['status' => Event::STATUS_ACTIVE]);
        $this->notifyEventPublished($event);

        Log::info('Admin approved event', [
            'event_id' => $event->id,
            'admin_id' => auth()->id(),
        ]);

        $message = 'Event approved and is now live';
        if ($event->campaign && ($fc = $event->campaign->followers()->count()) > 0) {
            $message .= " — notification email sent to {$fc} follower" . ($fc !== 1 ? 's' : '');
        }
        $message .= '.';

        return back()->with('success', $message);
    }



/* ─────────────────────────────────────────
 | REJECT  (pending → cancelled)
 ───────────────────────────────────────── */


public function reject(Event $event): RedirectResponse
{
    $event->update(['status' => Event::STATUS_CANCELLED]);

    Log::info('Admin rejected event', [
        'event_id' => $event->id,
        'admin_id' => auth()->id(),
    ]);

    return back()->with('success', 'Event has been rejected.');
}


    /* ─────────────────────────────────────────
     | ASSIGN VOLUNTEER TO EVENT
     | With overlap detection to prevent assigning
     | a volunteer to two events at the same time.
     ───────────────────────────────────────── */
    /**
     * Assign a verified volunteer to an event.
     *
     * Fix 5: Only verified volunteers (is_verified = true) can be assigned.
     * Fix 6: Overlap detection checks event start_time/end_time when
     *        two assignments fall on the same single date, allowing
     *        same-day back-to-back events (e.g. 9-11am and 2-4pm).
     */
    public function assignVolunteer(Request $request, Event $event): RedirectResponse
    {
        $data = $request->validate([
            'volunteer_id' => ['required', 'integer', 'exists:volunteers,id'],
            'role'         => ['nullable', 'string', 'max:255'],
            'start_date'   => ['nullable', 'date'],
            'end_date'     => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        $volunteer = Volunteer::findOrFail($data['volunteer_id']);

        // ── Fix 5: Only verified volunteers can be assigned ──
        if (! $volunteer->is_verified) {
            return back()->with(
                'error',
                'Only verified volunteers can be assigned to events. Approve their application first.'
            );
        }

        // ── Past event check (uses hasEnded() which respects end_time/start_time) ──
        if ($event->hasEnded()) {
            return back()->with(
                'error',
                'Cannot assign a volunteer to an event that has already ended (event: ' . $event->event_date->format('d M Y') . ($event->end_time ? ' ' . Carbon::parse($event->end_time)->format('H:i') : '') . ').'
            );
        }

        // ── Duplicate assignment check ──
        $existing = VolunteerAssignment::where('volunteer_id', $volunteer->id)
            ->where('event_id', $event->id)
            ->where('status', 'active')
            ->exists();

        if ($existing) {
            return back()->with(
                'error',
                'This volunteer is already assigned to this event.'
            );
        }

        // ── Build the proposed assignment's date range ──
        $start = $data['start_date'] ?? $event->event_date->format('Y-m-d');
        $end   = $data['end_date'] ?? $event->event_date->format('Y-m-d');

        // ── Fetch potentially overlapping active assignments ──
        $overlapping = VolunteerAssignment::with('event')
            ->where('volunteer_id', $volunteer->id)
            ->where('status', 'active')
            ->where(function ($q) use ($start, $end) {
                $q->where(function ($q) {
                    $q->whereNull('start_date')->whereNull('end_date');
                })->orWhere(function ($q) use ($start, $end) {
                    $q->where('start_date', '<=', $end)
                      ->where('end_date', '>=', $start);
                });
            })
            ->get();

        // ── Fix 6: Time-aware overlap check (fixed Carbon vs string bug) ──
        $conflict = $overlapping->contains(function ($assignment) use ($start, $end, $event) {
            $aStart = optional($assignment->start_date)?->format('Y-m-d');
            $aEnd   = optional($assignment->end_date)?->format('Y-m-d');

            // Multi-day range or different dates → date overlap is conclusive
            if ($aStart !== $aEnd || $start !== $end || $aStart !== $start) {
                return true;
            }

            // Same single date — check time overlap if both events have times
            if (! $assignment->event || ! $assignment->event->start_time || ! $assignment->event->end_time) {
                return true; // assume full-day
            }
            if (! $event->start_time || ! $event->end_time) {
                return true; // assume full-day
            }

            // No overlap if one ends before the other starts (strict <)
            return ! ($assignment->event->end_time <= $event->start_time
                   || $event->end_time   <= $assignment->event->start_time);
        });

        if ($conflict) {
            return back()->with(
                'error',
                'This volunteer is already assigned to another event during this period.'
            );
        }

        // ── Create the assignment ──
        $assignment = VolunteerAssignment::create([
            'volunteer_id' => $volunteer->id,
            'event_id'     => $event->id,
            'campaign_id'  => $event->campaign_id,
            'role'         => $data['role'] ?? null,
            'start_date'   => $start,
            'end_date'     => $end,
            'status'       => 'active',
        ]);

        Log::info('Volunteer assigned to event', [
            'assignment_id' => $assignment->id,
            'volunteer_id'  => $volunteer->id,
            'event_id'      => $event->id,
            'admin_id'      => auth()->id(),
        ]);

        return back()->with('success', 'Volunteer assigned to event successfully.');
    }

    /* ─────────────────────────────────────────
     | NOTIFY CAMPAIGN FOLLOWERS + CREATOR
     | Sends the "event published" email when the
     | event's send_notification flag is enabled.
     ───────────────────────────────────────── */
    protected function notifyEventPublished(Event $event): void
    {
        $campaign = $event->campaign;
        if (! $campaign) {
            return;
        }

        $recipients = collect();

        // Anyone following the campaign opted in, so they are ALWAYS notified
        // when a new event is published — independent of the admin toggle.
        try {
            foreach ($campaign->followers as $follower) {
                if ($follower->email) {
                    $recipients->push($follower);
                }
            }
        } catch (\Throwable $e) {
            Log::warning('Could not load campaign followers for event notification', [
                'event_id' => $event->id,
                'error'    => $e->getMessage(),
            ]);
        }

        // The "Send Notification Email" toggle controls notifying the creator.
        if ($event->send_notification && $campaign->user && $campaign->user->email) {
            $recipients->push($campaign->user);
        }

        $recipients = $recipients->unique(fn($user) => $user->email);

        if ($recipients->isEmpty()) {
            return;
        }

        foreach ($recipients as $user) {
            try {
                Mail::to($user->email)->send(new EventPublishedMail($event, $user));
            } catch (\Throwable $e) {
                Log::error('Failed to send event published notification', [
                    'event_id' => $event->id,
                    'user_id'  => $user->id,
                    'email'    => $user->email,
                    'error'    => $e->getMessage(),
                ]);
            }
        }

        Log::info('Event published notifications sent', [
            'event_id'   => $event->id,
            'recipients' => $recipients->count(),
            'followers'  => $campaign->followers()->count(),
        ]);
    }


}