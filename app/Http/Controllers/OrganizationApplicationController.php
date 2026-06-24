<?php

namespace App\Http\Controllers;

use App\Models\OrganizationApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OrganizationApplicationController extends Controller
{
    // ── Helpers ────────────────────────────────────────────────────────────

    private function getOrCreateApplication(): OrganizationApplication
    {
        return OrganizationApplication::firstOrCreate(
            [
                'user_id' => Auth::id(),
                'status'  => 'draft',
            ],
            ['current_step' => 1]
        );
    }

    private function storeFile(Request $request, string $field, string $folder): ?string
    {
        if ($request->hasFile($field)) {
            return $request->file($field)->store("applications/{$folder}", 'public');
        }
        return null;
    }

    // ── Step 1: Organization Info ──────────────────────────────────────────

    public function step1()
    {
        $application = OrganizationApplication::where('user_id', Auth::id())
            ->where('status', 'draft')
            ->first();

        return view('application.step1', compact('application'));
    }

    public function step1Post(Request $request)
    {
        $validated = $request->validate([
            'organization_type' => 'required|string|in:NGO,Trust,Society,Section-8',
            'name'              => 'required|string|max:255',
            'registration_number' => 'nullable|string|max:255',
            'registration_date'   => 'nullable|date',
            'address'           => 'nullable|string',
            'city'              => 'nullable|string|max:255',
            'state'             => 'nullable|string|max:255',
            'pincode'           => 'nullable|string|max:10',
            'causes'            => 'nullable|array',
            'causes.*'          => 'string',
            'founder_name'      => 'nullable|string|max:255',
            'founder_linkedin'  => 'nullable|url',
            'mission_statement' => 'nullable|string',
        ]);

        $application = $this->getOrCreateApplication();
        $application->update(array_merge($validated, ['current_step' => 2]));

        return redirect()->route('application.step2');
    }

    // ── Step 2: Contact Person ─────────────────────────────────────────────

    public function step2()
    {
        $application = OrganizationApplication::where('user_id', Auth::id())
            ->where('status', 'draft')
            ->firstOrFail();

        return view('application.step2', compact('application'));
    }

    public function step2Post(Request $request)
    {
        $validated = $request->validate([
            'contact_name'      => 'required|string|max:255',
            'contact_phone'     => 'required|string|max:20',
            'contact_email'     => 'required|email|max:255',
            'contact_role'      => 'nullable|string|max:255',
            'contact_linkedin'  => 'nullable|url',
            'contact_whatsapp'  => 'nullable|string|max:20',
        ]);

        $application = $this->getOrCreateApplication();
        $application->update(array_merge($validated, ['current_step' => 3]));

        return redirect()->route('application.step3');
    }

    // ── Step 3: Certifications & Legal ────────────────────────────────────

    public function step3()
    {
        $application = OrganizationApplication::where('user_id', Auth::id())
            ->where('status', 'draft')
            ->firstOrFail();

        return view('application.step3', compact('application'));
    }

    public function step3Post(Request $request)
    {
        $validated = $request->validate([
            'has_80g'          => 'boolean',
            '80g_number'       => 'nullable|string|max:255',
            '80g_expiry'       => 'nullable|date',
            'has_fcra'         => 'boolean',
            'fcra_number'      => 'nullable|string|max:255',
            'has_12a'          => 'boolean',
            '12a_number'       => 'nullable|string|max:255',
            'has_csr_eligible' => 'boolean',
            'pan_number'       => 'nullable|string|max:20',
            'darpan_id'        => 'nullable|string|max:255',
        ]);

        // Convert checkboxes (unchecked = not sent = false)
        foreach (['has_80g', 'has_fcra', 'has_12a', 'has_csr_eligible'] as $bool) {
            $validated[$bool] = $request->boolean($bool);
        }

        $application = $this->getOrCreateApplication();
        $application->update(array_merge($validated, ['current_step' => 4]));

        return redirect()->route('application.step4');
    }

    // ── Step 4: Documents & Profile ───────────────────────────────────────

    public function step4()
    {
        $application = OrganizationApplication::where('user_id', Auth::id())
            ->where('status', 'draft')
            ->firstOrFail();

        return view('application.step4', compact('application'));
    }

    public function submit(Request $request)
    {
        $validated = $request->validate([
            'website'           => 'nullable|url',
            'social_facebook'   => 'nullable|url',
            'social_instagram'  => 'nullable|url',
            'social_twitter'    => 'nullable|url',
            'social_youtube'    => 'nullable|url',
            'budget_range'      => 'nullable|string|max:255',
            'donor_strength'    => 'nullable|string|max:255',
            'employee_strength' => 'nullable|string|max:255',
            'has_crowdfunded'   => 'boolean',
            'campaign_timeline' => 'nullable|string|max:255',
            'campaigns_completed' => 'nullable|integer|min:0',

            // Bank details
            'bank_name'           => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|string|max:50',
            'bank_ifsc'           => 'nullable|string|max:20',
            'bank_account_type'   => 'nullable|in:Savings,Current',

            // References
            'reference_1_name'  => 'nullable|string|max:255',
            'reference_1_org'   => 'nullable|string|max:255',
            'reference_1_phone' => 'nullable|string|max:20',
            'reference_2_name'  => 'nullable|string|max:255',
            'reference_2_org'   => 'nullable|string|max:255',
            'reference_2_phone' => 'nullable|string|max:20',

            // Documents
            'doc_registration_cert' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'doc_80g_certificate'   => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'doc_fcra_certificate'  => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'doc_annual_report'     => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'doc_audited_statement' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'doc_pan_card'          => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $application = $this->getOrCreateApplication();

        // Handle file uploads
        $docFields = [
            'doc_registration_cert',
            'doc_80g_certificate',
            'doc_fcra_certificate',
            'doc_annual_report',
            'doc_audited_statement',
            'doc_pan_card',
        ];

        foreach ($docFields as $field) {
            $path = $this->storeFile($request, $field, 'docs');
            if ($path) {
                $validated[$field] = $path;
            }
        }

        $validated['has_crowdfunded'] = $request->boolean('has_crowdfunded');

        $application->update(array_merge($validated, [
            'status'       => 'pending',
            'current_step' => 4,
            'submitted_at' => now(),
        ]));

        return redirect()->route('dashboard')->with('success', 'Application submitted successfully!');
    }
}