<?php

namespace App\Http\Controllers;

use App\Mail\NewsletterWelcome;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $email = $request->input('email');

        $subscriber = Subscriber::where('email', $email)->first();

        if ($subscriber && $subscriber->unsubscribed_at === null) {
            return back()->with('newsletter_success', 'You are already subscribed!');
        }

        if ($subscriber && $subscriber->unsubscribed_at !== null) {
            $subscriber->update([
                'unsubscribed_at' => null,
                'subscribed_at' => now(),
            ]);
        }

        if (! $subscriber) {
            $subscriber = Subscriber::create([
                'email' => $email,
                'unsubscribe_token' => Subscriber::generateToken(),
                'subscribed_at' => now(),
            ]);
        }

        try {
            Mail::to($email)->send(new NewsletterWelcome($subscriber));
        } catch (\Throwable $e) {
            report($e);
        }

        return back()->with('newsletter_success', 'You have subscribed successfully!');
    }

    public function unsubscribe($token)
    {
        $subscriber = Subscriber::where('unsubscribe_token', $token)->first();

        if ($subscriber && $subscriber->unsubscribed_at === null) {
            $subscriber->update(['unsubscribed_at' => now()]);
        }

        return view('newsletter.unsubscribed', [
            'already' => $subscriber && $subscriber->unsubscribed_at !== null,
            'found' => $subscriber !== null,
        ]);
    }
}
