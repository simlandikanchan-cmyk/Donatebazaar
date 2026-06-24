<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewsletterWelcome;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $email = $request->input('email');

        // Send welcome email to subscriber
        Mail::to($email)->send(new NewsletterWelcome($email));

        return back()->with('newsletter_success', 'You have subscribed successfully!');
    }
}