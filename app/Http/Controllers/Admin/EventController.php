<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\EventPublishedMail;
use App\Models\Campaign;
use App\Models\Category;
use App\Models\Event;
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
        $validated['status']           = $request->input('status', 'draft');
        $validated['send_notification'] = $request->boolean('send_notification');
        $validated['slug']             = $slug;
        $validated['raised_amount']    = 0;
        $validated['registered_count'] = 0;
        $validated['user_id']          = auth()->id();

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
        $event->load(['campaign', 'user', 'registrations.user'])
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

        $message = $event->send_notification
            ? 'Event published — notification email sent to campaign followers.'
            : 'Event published successfully.';

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

        $message = $event->send_notification
            ? 'Event approved and is now live — notification email sent to campaign followers.'
            : 'Event approved and is now live!';

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
     | NOTIFY CAMPAIGN FOLLOWERS + CREATOR
     | Sends the "event published" email when the
     | event's send_notification flag is enabled.
     ───────────────────────────────────────── */
    protected function notifyEventPublished(Event $event): void
    {
        if (! $event->send_notification) {
            return;
        }

        $campaign = $event->campaign;
        if (! $campaign) {
            return;
        }

        // Recipients: campaign creator + anyone following the campaign.
        $recipients = collect();

        if ($campaign->user && $campaign->user->email) {
            $recipients->push($campaign->user);
        }

        try {
            foreach ($campaign->followers as $follower) {
                if ($follower->email) {
                    $recipients->push($follower);
                }
            }
        } catch (\Throwable $e) {
            // Followers relation unavailable — creator still gets notified.
            Log::warning('Could not load campaign followers for event notification', [
                'event_id' => $event->id,
                'error'    => $e->getMessage(),
            ]);
        }

        $recipients = $recipients->unique(fn($user) => $user->email);

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

        if ($recipients->isNotEmpty()) {
            Log::info('Event published notifications sent', [
                'event_id'   => $event->id,
                'recipients' => $recipients->count(),
            ]);
        }
    }


}