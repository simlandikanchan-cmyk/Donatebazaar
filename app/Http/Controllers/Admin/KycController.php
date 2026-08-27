<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\KycRejectRequest;
use App\Models\ActivityLog;
use App\Models\KycVerification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class KycController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * List all KYC submissions.
     */
    public function index(): View
    {
        $kycList = KycVerification::with('user')
            ->latest()
            ->paginate(20);

        $counts = [
            'total' => KycVerification::count(),
            'pending' => KycVerification::where('status', 'pending')->count(),
            'approved' => KycVerification::where('status', 'approved')->count(),
            'rejected' => KycVerification::where('status', 'rejected')->count(),
        ];

        return view('admin.kyc.index', compact('kycList', 'counts'));
    }

    /**
     * Approve a KYC submission.
     */
    public function approve(KycVerification $kyc): RedirectResponse
    {
        $originalStatus = $kyc->status;

        $kyc->status = 'approved';
        $kyc->verified_by = auth()->id();
        $kyc->verified_at = now();
        $kyc->rejection_reason = null;
        $kyc->save();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'kyc_approved',
            'loggable_type' => KycVerification::class,
            'loggable_id' => $kyc->id,
            'meta' => [
                'kyc_id' => $kyc->id,
                'user_id' => $kyc->user_id,
                'previous_status' => $originalStatus,
                'new_status' => 'approved',
            ],
        ]);

        return back()->with('success', "KYC approved for {$kyc->user->name}.");
    }

    /**
     * Reject a KYC submission with a reason.
     */
    public function reject(KycRejectRequest $request, KycVerification $kyc): RedirectResponse
    {
        $originalStatus = $kyc->status;

        $kyc->status = 'rejected';
        $kyc->rejection_reason = $request->rejection_reason;
        $kyc->verified_by = auth()->id();
        $kyc->verified_at = now();
        $kyc->save();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'kyc_rejected',
            'loggable_type' => KycVerification::class,
            'loggable_id' => $kyc->id,
            'meta' => [
                'kyc_id' => $kyc->id,
                'user_id' => $kyc->user_id,
                'previous_status' => $originalStatus,
                'new_status' => 'rejected',
                'reason' => $request->rejection_reason,
            ],
        ]);

        return back()->with('success', "KYC rejected for {$kyc->user->name}.");
    }

    /**
     * Serve the KYC document file to admin.
     */
    public function showDocument(KycVerification $kyc)
    {
        abort_if(! $kyc->document_url, 404);
        abort_if(! Storage::disk('private')->exists($kyc->document_url), 404);

        $path = Storage::disk('private')->path($kyc->document_url);
        $mimeType = mime_content_type($path);

        return response()->file($path, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="'.basename($path).'"',
        ]);
    }
}
