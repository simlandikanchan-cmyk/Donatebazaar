<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Partnership;
use Illuminate\Support\Facades\Mail;
use App\Mail\PartnershipSubmitted;
use Illuminate\Support\Str;

class PartnershipController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Show Partnership Form
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        return view('frontend.partnership');
    }

    /*
    |--------------------------------------------------------------------------
    | Store Partnership Request
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // Basic Info
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',

            // Organization Info
            'organization_name' => 'required|string|max:255',
            'organization_type' => 'nullable|string|max:255',
            'organization_size' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'website' => 'nullable|url',

            // Partnership Details
            'partnership_type' => 'required|string',
            'goal' => 'nullable|string|max:500',
            'timeline' => 'nullable|string|max:255',

            // Message + File
            'message' => 'nullable|string|max:1000',
            'document' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Prevent Spam (same email multiple times quickly)
        |--------------------------------------------------------------------------
        */
        $recent = Partnership::where('email', $request->email)
            ->where('created_at', '>', now()->subMinutes(5))
            ->exists();

        if ($recent) {
            return back()->withErrors([
                'email' => 'You already submitted a request. Please wait before trying again.'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Upload Document
        |--------------------------------------------------------------------------
        */
        if ($request->hasFile('document')) {
            $validated['document'] = $request->file('document')
                ->store('partnership_documents', 'public');
        }

        /*
        |--------------------------------------------------------------------------
        | Default Status
        |--------------------------------------------------------------------------
        */
        $validated['status'] = 'pending';

        /*
        |--------------------------------------------------------------------------
        | Auto Priority Score
        |--------------------------------------------------------------------------
        */
        $score = 0;

        // Website bonus
        if ($request->website) {
            $score += 10;
        }

        // Document bonus
        if ($request->hasFile('document')) {
            $score += 20;
        }

        // Business email bonus
        if (Str::contains($request->email, [
            'gmail.com',
            'yahoo.com',
            'hotmail.com'
        ])) {
            $score -= 10;
        } else {
            $score += 10;
        }

        // Strong message bonus
        if ($request->message && strlen($request->message) > 200) {
            $score += 10;
        }

        $validated['priority_score'] = $score;

        /*
        |--------------------------------------------------------------------------
        | Create Partnership Request
        |--------------------------------------------------------------------------
        */
        $partnership = Partnership::create($validated);

        /*
        |--------------------------------------------------------------------------
        | Notify Admin
        |--------------------------------------------------------------------------
        */
        Mail::to('admin@yourdomain.com')
            ->queue(new PartnershipSubmitted($partnership));

        /*
        |--------------------------------------------------------------------------
        | Success Response
        |--------------------------------------------------------------------------
        */
        return redirect()->back()->with(
            'success',
            'Your partnership request has been submitted successfully!'
        );
    }
}