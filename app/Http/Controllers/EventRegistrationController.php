<?php

namespace App\Http\Controllers;

use App\Mail\EventRegistrationMail;
use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class EventRegistrationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration Form  (GET /events/{event}/register)
    |--------------------------------------------------------------------------
    */

    public function register(Event $event): View|RedirectResponse
    {
        // Block if event is not active
        if (! $event->isActive()) {
            return redirect()
                ->route('events.show', $event->id)
                ->with('error', 'Registrations are not open for this event.');
        }

        // Block if event date has already passed
        if ($event->hasEnded()) {
            return redirect()
                ->route('events.show', $event->id)
                ->with('error', 'This event has already ended.');
        }

        // Block if spots are full
        if ($event->isFull()) {
            return redirect()
                ->route('events.show', $event->id)
                ->with('error', 'This event is fully booked.');
        }

        return view('events.register', compact('event'));
    }

    /*
    |--------------------------------------------------------------------------
    | Store Registration  (POST /events/{event}/register)
    |--------------------------------------------------------------------------
    */

    public function store(Request $request, Event $event): RedirectResponse
    {
        // Re-check guards on POST as well (belt-and-suspenders)
        if (! $event->isActive() || $event->hasEnded()) {
            return back()->with('error', 'Registrations are not open for this event.');
        }

        if ($event->isFull()) {
            return back()->with('error', 'Sorry, this event just became fully booked.');
        }

        /*
        |----------------------------------------------------------------------
        | Validation
        |----------------------------------------------------------------------
        */

        $validated = $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'email'   => ['required', 'email', 'max:255'],
            'phone'   => ['required', 'string', 'max:30'],
            'message' => ['nullable', 'string', 'max:1000'],
        ]);

        /*
        |----------------------------------------------------------------------
        | Duplicate check — same email, same event
        |----------------------------------------------------------------------
        */

        $alreadyRegistered = EventRegistration::where('event_id', $event->id)
            ->where('email', $validated['email'])
            ->where('status', 'registered')
            ->exists();

        if ($alreadyRegistered) {
            return back()
                ->withInput()
                ->with('error', 'This email address is already registered for the event.');
        }

        /*
        |----------------------------------------------------------------------
        | Create Registration
        |----------------------------------------------------------------------
        */

        $registration = EventRegistration::create([
            'event_id' => $event->id,
            'user_id'  => null,            // anonymous — no account required
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'phone'    => $validated['phone'],
            'message'  => $validated['message'] ?? null,
            'status'   => 'registered',
        ]);

        // Increment the denormalized counter on the event
        $event->increment('registered_count');

        /*
        |----------------------------------------------------------------------
        | Send Confirmation Email
        |----------------------------------------------------------------------
        */

        try {
            Mail::to($validated['email'])
                ->send(new EventRegistrationMail($event, $registration));
        } catch (\Throwable $e) {
            // Log but don't fail the registration if mail breaks
            \Illuminate\Support\Facades\Log::error('Event registration email failed', [
                'registration_id' => $registration->id,
                'error'           => $e->getMessage(),
            ]);
        }

        return redirect()
            ->route('events.show', $event->id)
            ->with('success', 'You\'re registered! A confirmation email has been sent to ' . $validated['email'] . '.');
    }
}