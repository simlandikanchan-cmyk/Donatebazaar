<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;

class EventController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Create Event Form
    |--------------------------------------------------------------------------
    */

    public function create(Campaign $campaign): View|RedirectResponse
    {
        // Authorization
        if ($campaign->user_id !== Auth::id()) {
            abort(403);
        }

        // Prevent event creation for expired/inactive campaigns
        if (
            $campaign->campaign_state !== 'active' ||
            (
                $campaign->end_date &&
                Carbon::parse($campaign->end_date)->isPast()
            )
        ) {
            return redirect()
                ->route('campaign.show', $campaign->id)
                ->with(
                    'error',
                    'Cannot create event for expired campaign.'
                );
        }

        return view('events.create', compact('campaign'));
    }

    /*
    |--------------------------------------------------------------------------
    | Store Event
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request,
        Campaign $campaign
    ): RedirectResponse {

        // Authorization
        if ($campaign->user_id !== Auth::id()) {
            abort(403);
        }

        // Prevent event creation for expired campaigns
        if (
            $campaign->campaign_state !== 'active' ||
            (
                $campaign->end_date &&
                Carbon::parse($campaign->end_date)->isPast()
            )
        ) {
            return back()->with(
                'error',
                'Cannot create event for expired campaign.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'required',
                'string',
            ],

            'event_date' => [
                'required',
                'date',
                'after_or_equal:today',
            ],

            'start_time' => [
                'required',
            ],

            'end_time' => [
                'required',
                'after:start_time',
            ],

            'goal_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'max_participants' => [
                'nullable',
                'integer',
                'min:1',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Generate Unique Slug
        |--------------------------------------------------------------------------
        */

        $baseSlug = Str::slug($validated['title']);

        $slug = $baseSlug;

        $counter = 1;

        while (Event::where('slug', $slug)->exists()) {

            $slug = $baseSlug . '-' . $counter;

            $counter++;
        }

        /*
        |--------------------------------------------------------------------------
        | Create Event
        |--------------------------------------------------------------------------
        */

        $event = $campaign->events()->create([
            'title'             => $validated['title'],
            'description'       => $validated['description'],
            'event_date'        => $validated['event_date'],
            'start_time'        => $validated['start_time'],
            'end_time'          => $validated['end_time'],
            'goal_amount'       => $validated['goal_amount'] ?? 0,
            'max_participants'  => $validated['max_participants'] ?? 0,
            'user_id'           => Auth::id(),
            'slug'              => $slug,
            'status'            => Event::STATUS_PENDING,
        ]);

        Log::info('Event created', [
            'event_id'    => $event->id,
            'campaign_id' => $campaign->id,
            'user_id'     => Auth::id(),
        ]);

        return redirect()
            ->route('events.show', $event->id)
            ->with(
                'success',
                'Event created successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Show Event
    |--------------------------------------------------------------------------
    */

    public function show(Event $event): View
    {
        /*
        |--------------------------------------------------------------------------
        | Auto Expire Event
        |--------------------------------------------------------------------------
        */

        $shouldExpire = false;

        // Campaign expired/inactive
        if (
            $event->campaign &&
            (
                $event->campaign->campaign_state !== 'active' ||
                (
                    $event->campaign->end_date &&
                    Carbon::parse(
                        $event->campaign->end_date
                    )->isPast()
                )
            )
        ) {
            $shouldExpire = true;
        }

        // Event date passed
        if (
            $event->event_date &&
            Carbon::parse($event->event_date)->isPast()
        ) {
            $shouldExpire = true;
        }

        // Auto update status
        if (
            $shouldExpire &&
            !$event->isExpired()
        ) {

            $event->update([
                'status' => Event::STATUS_EXPIRED,
            ]);

            $event->refresh();
        }

        return view('events.view', compact('event'));
    }

    /*
    |--------------------------------------------------------------------------
    | Edit Event
    |--------------------------------------------------------------------------
    */

    public function edit(
        Event $event
    ): View|RedirectResponse {

        // Authorization
        if ($event->campaign->user_id !== Auth::id()) {
            abort(403);
        }

        // Prevent editing expired event
        if ($event->isExpired()) {

            return redirect()
                ->route('events.show', $event->id)
                ->with(
                    'error',
                    'Expired events cannot be edited.'
                );
        }

        return view('events.edit', compact('event'));
    }

    /*
    |--------------------------------------------------------------------------
    | Update Event
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Event $event
    ): RedirectResponse {

        // Authorization
        if ($event->campaign->user_id !== Auth::id()) {
            abort(403);
        }

        // Prevent editing expired event
        if ($event->isExpired()) {

            return redirect()
                ->route('events.show', $event->id)
                ->with(
                    'error',
                    'Expired events cannot be updated.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'required',
                'string',
            ],

            'event_date' => [
                'required',
                'date',
                'after_or_equal:today',
            ],

            'start_time' => [
                'required',
            ],

            'end_time' => [
                'required',
                'after:start_time',
            ],

            'goal_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'max_participants' => [
                'required',
                'integer',
                'min:1',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Regenerate Slug If Title Changed
        |--------------------------------------------------------------------------
        */

        if ($event->title !== $validated['title']) {

            $baseSlug = Str::slug($validated['title']);

            $slug = $baseSlug;

            $counter = 1;

            while (
                Event::where('slug', $slug)
                    ->where('id', '!=', $event->id)
                    ->exists()
            ) {

                $slug = $baseSlug . '-' . $counter;

                $counter++;
            }

            $validated['slug'] = $slug;
        }

        /*
        |--------------------------------------------------------------------------
        | Auto Status Handling
        |--------------------------------------------------------------------------
        */

        if (
            $event->campaign->campaign_state !== 'active' ||
            (
                $event->campaign->end_date &&
                Carbon::parse(
                    $event->campaign->end_date
                )->isPast()
            )
        ) {

            $validated['status'] =
                Event::STATUS_EXPIRED;

        } else {

            $validated['status'] =
                $event->status;
        }

        /*
        |--------------------------------------------------------------------------
        | Update Event
        |--------------------------------------------------------------------------
        */

        $event->update($validated);

        Log::info('Event updated', [
            'event_id' => $event->id,
            'user_id'  => Auth::id(),
        ]);

        return redirect()
            ->route('events.show', $event->id)
            ->with(
                'success',
                'Event updated successfully.'
            );
    }
}