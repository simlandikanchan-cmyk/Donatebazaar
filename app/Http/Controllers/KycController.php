<?php

namespace App\Http\Controllers;

use App\Http\Requests\KycUploadRequest;
use App\Models\KycVerification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class KycController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the KYC status / upload form.
     */
    public function index(): View
    {
        $kyc = auth()->user()->kycVerification;

        return view('user.kyc.index', compact('kyc'));
    }

    /**
     * Store or update the user's KYC submission.
     */
    public function store(KycUploadRequest $request): RedirectResponse
    {
        $user = auth()->user();

        if ($user->kycVerification?->status === 'approved') {
            return redirect()->route('dashboard')
                ->with('info', 'Your identity is already verified.');
        }

        DB::beginTransaction();

        try {
            $existing = $user->kycVerification;

            $data = [
                'status'             => 'pending',
                'rejection_reason'   => null,
                'verified_by'        => null,
                'verified_at'        => null,
                'kyc_account_name'   => $request->kyc_account_name,
                'kyc_account_number' => $request->kyc_account_number,
                'kyc_ifsc'           => $request->kyc_ifsc ? strtoupper($request->kyc_ifsc) : null,
                'kyc_bank_name'      => $request->kyc_bank_name,
            ];

            // Aadhaar
            if ($request->hasFile('kyc_aadhaar')) {
                if ($existing?->aadhaar_url) {
                    Storage::disk('public')->delete($existing->aadhaar_url);
                }
                $data['aadhaar_url'] = $request->file('kyc_aadhaar')
                    ->store('kyc_documents', 'public');
            }

            // PAN
            if ($request->hasFile('kyc_pan')) {
                if ($existing?->pan_url) {
                    Storage::disk('public')->delete($existing->pan_url);
                }
                $data['pan_url'] = $request->file('kyc_pan')
                    ->store('kyc_documents', 'public');
            }

            // Selfie
            if ($request->hasFile('kyc_selfie')) {
                if ($existing?->selfie_url) {
                    Storage::disk('public')->delete($existing->selfie_url);
                }
                $data['selfie_url'] = $request->file('kyc_selfie')
                    ->store('kyc_documents', 'public');
            }

            // Legacy single document (backward compatibility)
            if ($request->hasFile('document_file')) {
                if ($existing?->document_url) {
                    Storage::disk('private')->delete($existing->document_url);
                }
                $data['document_url']    = $request->file('document_file')
                    ->store('kyc_documents', 'private');
                $data['document_type']   = $request->document_type;
                $data['document_number'] = $request->document_number;
            }

            KycVerification::updateOrCreate(
                ['user_id' => $user->id],
                $data
            );

            DB::commit();

        } catch (\Throwable $e) {
            DB::rollBack();

            // Clean up any newly uploaded files if DB write failed
            foreach (['aadhaar_url', 'pan_url', 'selfie_url'] as $field) {
                if (isset($data[$field])) {
                    Storage::disk('public')->delete($data[$field]);
                }
            }
            if (isset($data['document_url'])) {
                Storage::disk('private')->delete($data['document_url']);
            }

            Log::error('KYC submission failed', [
                'user_id' => $user->id,
                'error'   => $e->getMessage(),
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Something went wrong while submitting your documents. Please try again.');
        }

        return redirect()->route('dashboard')
            ->with('kyc_submitted', true);
    }
}