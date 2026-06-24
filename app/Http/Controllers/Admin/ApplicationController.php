<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\OrganizationApplication;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ApplicationController extends Controller
{
    // ── Helpers ────────────────────────────────────────────────────────────

    /**
     * Only used in step1Post — creates the draft row with required fields.
     */
    private function getOrCreateApplication(array $requiredFields): OrganizationApplication
    {
        return OrganizationApplication::firstOrCreate(
            ['user_id' => Auth::id(), 'status' => 'draft'],
            array_merge($requiredFields, ['current_step' => 1])
        );
    }

    /**
     * Used in steps 2, 3, 4 — fetches existing draft or redirects to step1.
     */
    private function getDraftApplication(): OrganizationApplication
    {
        $application = OrganizationApplication::where('user_id', Auth::id())
            ->where('status', 'draft')
            ->first();

        if (!$application) {
            redirect()->route('application.step1')
                ->with('error', 'Please start your application from Step 1.')
                ->send();
            exit;
        }

        return $application;
    }

    private function storeFile(Request $request, string $field, string $folder): ?string
    {
        if ($request->hasFile($field)) {
            return $request->file($field)->store("applications/{$folder}", 'public');
        }
        return null;
    }

    // ── Admin: List & Review ───────────────────────────────────────────────

    public function index()
    {
        $applications = OrganizationApplication::with('user')
            ->whereIn('status', ['pending', 'under_review', 'approved', 'rejected'])
            ->latest('submitted_at')
            ->paginate(20);

        return view('admin.applications.index', compact('applications'));
    }


    public function show($id)
{
    $application = OrganizationApplication::with('user')
        ->findOrFail($id);

    return view('admin.applications.show', compact('application'));
}

    public function approve(Request $request, $id)
    {
        $application = OrganizationApplication::findOrFail($id);
        $application->update([
            'status'      => 'approved',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'admin_notes' => $request->admin_notes,
        ]);

        return back()->with('success', 'Application approved.');
    }

    public function reject(Request $request, $id)
    {
        $request->validate(['rejection_reason' => 'required|string']);

        $application = OrganizationApplication::findOrFail($id);
        $application->update([
            'status'           => 'rejected',
            'reviewed_by'      => Auth::id(),
            'reviewed_at'      => now(),
            'rejection_reason' => $request->rejection_reason,
            'admin_notes'      => $request->admin_notes,
        ]);

        return back()->with('success', 'Application rejected.');
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
            'organization_type'   => 'required|string|in:NGO,Trust,Society,Section-8',
            'name'                => 'required|string|max:255',
            'registration_number' => 'nullable|string|max:255',
            'registration_date'   => 'nullable|date',
            'address'             => 'nullable|string',
            'city'                => 'nullable|string|max:255',
            'state'               => 'nullable|string|max:255',
            'pincode'             => 'nullable|string|max:10',
            'causes'              => 'nullable|array',
            'causes.*'            => 'string',
            'founder_name'        => 'nullable|string|max:255',
            'founder_linkedin'    => 'nullable|url',
            'mission_statement'   => 'nullable|string',
        ]);

        // Step 1 is the ONLY place that creates the draft row.
        // Pass required DB fields so the INSERT never fails.
        $application = $this->getOrCreateApplication([
            'name'          => $validated['name'],
            'contact_name'  => '',
            'contact_phone' => '',
            'contact_email' => '',
        ]);

        $application->update(array_merge($validated, ['current_step' => 2]));

        return redirect()->route('application.step2');
    }

    // ── Step 2: Contact Person ─────────────────────────────────────────────

    public function step2()
    {
        $application = $this->getDraftApplication();
        return view('application.step2', compact('application'));
    }

    public function step2Post(Request $request)
    {
        $validated = $request->validate([
            'contact_name'     => 'required|string|max:255',
            'contact_phone'    => 'required|string|max:20',
            'contact_email'    => 'required|email|max:255',
            'contact_role'     => 'nullable|string|max:255',
            'contact_linkedin' => 'nullable|url',
            'contact_whatsapp' => 'nullable|string|max:20',
        ]);

        $application = $this->getDraftApplication();
        $application->update(array_merge($validated, ['current_step' => 3]));

        return redirect()->route('application.step3');
    }

    // ── Step 3: Certifications & Legal ────────────────────────────────────

    public function step3()
    {
        $application = $this->getDraftApplication();
        return view('application.step3', compact('application'));
    }

    public function step3Post(Request $request)
    {
        $validated = $request->validate([
            '80g_number'  => 'nullable|string|max:255',
            '80g_expiry'  => 'nullable|date',
            'fcra_number' => 'nullable|string|max:255',
            '12a_number'  => 'nullable|string|max:255',
            'pan_number'  => 'nullable|string|max:20',
            'darpan_id'   => 'nullable|string|max:255',
        ]);

        // Checkboxes: unchecked = not sent in request = must default to false
        $validated['has_80g']          = $request->boolean('has_80g');
        $validated['has_fcra']         = $request->boolean('has_fcra');
        $validated['has_12a']          = $request->boolean('has_12a');
        $validated['has_csr_eligible'] = $request->boolean('has_csr_eligible');

        $application = $this->getDraftApplication();
        $application->update(array_merge($validated, ['current_step' => 4]));

        return redirect()->route('application.step4');
    }

    // ── Step 4: Documents & Profile ───────────────────────────────────────

    public function step4()
    {
        $application = $this->getDraftApplication();
        return view('application.step4', compact('application'));
    }

    public function submit(Request $request)
    {
        $validated = $request->validate([
            'website'               => 'nullable|url',
            'social_facebook'       => 'nullable|url',
            'social_instagram'      => 'nullable|url',
            'social_twitter'        => 'nullable|url',
            'social_youtube'        => 'nullable|url',
            'budget_range'          => 'nullable|string|max:255',
            'donor_strength'        => 'nullable|string|max:255',
            'employee_strength'     => 'nullable|string|max:255',
            'campaign_timeline'     => 'nullable|string|max:255',
            'campaigns_completed'   => 'nullable|integer|min:0',
            'bank_name'             => 'nullable|string|max:255',
            'bank_account_number'   => 'nullable|string|max:50',
            'bank_ifsc'             => 'nullable|string|max:20',
            'bank_account_type'     => 'nullable|in:Savings,Current',
            'reference_1_name'      => 'nullable|string|max:255',
            'reference_1_org'       => 'nullable|string|max:255',
            'reference_1_phone'     => 'nullable|string|max:20',
            'reference_2_name'      => 'nullable|string|max:255',
            'reference_2_org'       => 'nullable|string|max:255',
            'reference_2_phone'     => 'nullable|string|max:20',
            'doc_registration_cert' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'doc_80g_certificate'   => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'doc_fcra_certificate'  => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'doc_annual_report'     => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'doc_audited_statement' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'doc_pan_card'          => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        // Handle file uploads
        foreach ([
            'doc_registration_cert',
            'doc_80g_certificate',
            'doc_fcra_certificate',
            'doc_annual_report',
            'doc_audited_statement',
            'doc_pan_card',
        ] as $field) {
            $path = $this->storeFile($request, $field, 'docs');
            if ($path) {
                $validated[$field] = $path;
            }
        }

        $validated['has_crowdfunded'] = $request->boolean('has_crowdfunded');

        $application = $this->getDraftApplication();
        $application->update(array_merge($validated, [
            'status'       => 'pending',
            'current_step' => 4,
            'submitted_at' => now(),
        ]));

        return redirect()->route('dashboard')
            ->with('success', 'Application submitted successfully!');
    }
}