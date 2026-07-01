<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\KycVerification;
use App\Models\User;
use App\Notifications\KycSubmittedNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class KycUploadController extends Controller
{
    /**
     * Show the dedicated KYC upload form.
     * Route: GET /kyc/upload/{campaign}
     * Name: kyc.upload.form
     */
    public function show(Campaign $campaign): View
    {
        abort_unless(Auth::id() === $campaign->user_id, 403);

        // FIXED: scoped by campaign_id, not just user_id — otherwise a user
        // with multiple campaigns would see another campaign's KYC status here.
        $existingKyc = KycVerification::where('user_id', Auth::id())
            ->where('campaign_id', $campaign->id)
            ->latest()
            ->first();

        return view('kyc.upload', compact('campaign', 'existingKyc'));
    }

    /**
     * Show the KYC documents view page.
     * Route: GET /kyc/view/{campaign}
     * Name: kyc.view
     */
    public function view(Campaign $campaign): View
    {
        abort_unless(Auth::id() === $campaign->user_id, 403);

        $kyc = KycVerification::where('user_id', Auth::id())
            ->where('campaign_id', $campaign->id)
            ->latest()
            ->first();

        return view('kyc.view', compact('campaign', 'kyc'));
    }

    /**
     * Serve the private KYC document file securely.
     * Route: GET /kyc/document/{campaign}
     * Name: kyc.document
     *
     * Only the campaign owner can access their own document.
     */
    public function serveDocument(Campaign $campaign): Response
    {
        abort_unless(Auth::id() === $campaign->user_id, 403);

        $kyc = KycVerification::where('user_id', Auth::id())
            ->where('campaign_id', $campaign->id)
            ->firstOrFail();

        abort_unless($kyc->document_url && Storage::disk('private')->exists($kyc->document_url), 404);

        $file     = Storage::disk('private')->get($kyc->document_url);
        $mimeType = Storage::disk('private')->mimeType($kyc->document_url);
        $fileName = basename($kyc->document_url);

        return response($file, 200)
            ->header('Content-Type', $mimeType)
            ->header('Content-Disposition', 'inline; filename="' . $fileName . '"');
    }

    /**
     * Handle KYC document submission.
     * Route: POST /kyc/upload/{campaign}
     * Name: kyc.upload
     */
    public function store(Request $request, Campaign $campaign): RedirectResponse
    {
        abort_unless(Auth::id() === $campaign->user_id, 403);

        $validated = $request->validate([
            'document_type'   => ['required', 'in:pan,aadhaar,passport,other'],
            'document_number' => ['required', 'string', 'max:255'],
            'document_file'   => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ]);

        // FIXED: look up the existing record for THIS campaign only, so
        // uploading for Campaign B no longer deletes/overwrites Campaign A's
        // approved document and verification status.
        $existing = KycVerification::where('user_id', Auth::id())
            ->where('campaign_id', $campaign->id)
            ->first();

        if ($existing && $existing->document_url) {
            Storage::disk('private')->delete($existing->document_url);
        }

        $path = $request->file('document_file')
            ->store('kyc-documents/' . Auth::id() . '/' . $campaign->id, 'private');

        $kyc = KycVerification::updateOrCreate(
            [
                'user_id'     => Auth::id(),
                'campaign_id' => $campaign->id,
            ],
            [
                'document_type'    => $validated['document_type'],
                'document_number'  => $validated['document_number'],
                'document_url'     => $path,
                'status'           => 'pending',
                'verified_by'      => null,
                'verified_at'      => null,
                'rejection_reason' => null,
            ]
        );

        $admins = User::where('role', 'admin')->get();

        // Minor cleanup: batch notification instead of an N+1 foreach loop.
        Notification::send($admins, new KycSubmittedNotification(Auth::user(), $campaign));

        return redirect()
            ->route('kyc.view', $campaign->id)
            ->with('success', 'KYC documents submitted successfully. Our team will review within 24 hours.');
    }
}