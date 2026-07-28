<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\CampaignStatusMail;
use App\Models\Campaign;
use App\Models\Category;
use App\Models\KycVerification;
use App\Notifications\KycRequestedNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response;

class CampaignController extends Controller
{
    // -------------------------------------------------------------------------
    // INDEX
    // -------------------------------------------------------------------------
    public function index(Request $request)
    {
        $status = $request->input('status', 'all');
        $search = $request->input('search');
        $sort = $request->input('sort', 'created_at');
        $dir = $request->input('direction', 'desc');

        $allowedSorts = ['title', 'goal_amount', 'raised_amount', 'created_at'];
        if (! in_array($sort, $allowedSorts)) {
            $sort = 'created_at';
        }
        $dir = $dir === 'asc' ? 'asc' : 'desc';

        $query = Campaign::with(['user:id,name,email', 'category:id,name']);

        if ($status !== 'all') {
            $query->where('campaign_state', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhereHas('user', fn ($uq) => $uq->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%"));
            });
        }

        $campaigns = $query->orderBy($sort, $dir)->paginate(15);

        return view('admin.campaign.index', [
            'campaigns' => $campaigns,
            'status' => $status,
            'search' => $search,
            'sort' => $sort,
            'dir' => $dir,
            'cntActive' => Campaign::active()->count(),
            'cntPending' => Campaign::pending()->count(),
            'cntPaused' => Campaign::paused()->count(),
            'cntRejected' => Campaign::rejected()->count(),
            'cntExpired' => Campaign::expired()->count(),
            'cntCompleted' => Campaign::completed()->count(),
        ]);
    }

    // -------------------------------------------------------------------------
    // SHOW
    // -------------------------------------------------------------------------
    public function show(Campaign $campaign)
    {
        $campaign->load([
            'user.kycVerification',
            'category',
            'events',
            'logs',
        ]);

        return view('admin.campaign.show', compact('campaign'));
    }

    // -------------------------------------------------------------------------
    // EDIT
    // -------------------------------------------------------------------------
    public function edit(Campaign $campaign)
    {
        $campaign->load([
            'user',
            'category',
            'events',
            'logs',
        ]);

        $categories = Category::orderBy('name')
            ->get();

        return view('admin.campaign.edit', compact(
            'campaign',
            'categories'
        ));
    }

    // -------------------------------------------------------------------------
    // UPDATE
    // -------------------------------------------------------------------------
    public function update(Request $request, Campaign $campaign): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'description' => ['required', 'string'],
            'goal_amount' => ['required', 'numeric', 'min:1'],
            'status' => ['nullable', 'string'],
            'end_date' => ['nullable', 'date'],
        ]);

        DB::transaction(function () use ($campaign, $data) {

            $campaign->update($data);

            $this->log(
                $campaign,
                'updated',
                'Campaign updated by admin.'
            );
        });

        return redirect()
            ->route('admin.campaign.show', $campaign)
            ->with('success', 'Campaign updated successfully.');
    }

    // -------------------------------------------------------------------------
    // APPROVE
    // -------------------------------------------------------------------------
    public function approve(Campaign $campaign): RedirectResponse
    {
        if (! $campaign->isPending()) {
            return back()->with('error', 'Only pending campaigns can be approved.');
        }

        if ($campaign->isExpired()) {
            return back()->with('error', 'Cannot approve expired campaign.');
        }

        $hasKyc = KycVerification::where('user_id', $campaign->user_id)
            ->where('status', KycVerification::STATUS_APPROVED)
            ->exists();

        if (! $hasKyc) {
            return back()->with('error', 'Cannot approve: User KYC not approved.');
        }

        DB::transaction(function () use ($campaign) {

            $campaign->approve();

            $this->log(
                $campaign,
                'approved',
                'Campaign approved.'
            );
        });

        Mail::to($campaign->user)->send(new CampaignStatusMail($campaign, 'approved'));

        return back()->with('success', 'Campaign approved and live.');
    }

    // -------------------------------------------------------------------------
    // REJECT
    // -------------------------------------------------------------------------
    public function reject(Request $request, Campaign $campaign): RedirectResponse
    {
        if (! $campaign->isPending()) {
            return back()->with('error', 'Only pending campaigns can be rejected.');
        }

        $data = $request->validate([
            'reason' => ['required', 'min:10', 'max:500'],
        ]);

        DB::transaction(function () use ($campaign, $data) {

            $campaign->reject($data['reason']);

            $this->log(
                $campaign,
                'rejected',
                'Rejected: '.$data['reason']
            );
        });

        Mail::to($campaign->user)->send(new CampaignStatusMail($campaign, 'rejected', $data['reason']));

        return back()->with('success', 'Campaign rejected.');
    }

    // -------------------------------------------------------------------------
    // PAUSE
    // -------------------------------------------------------------------------
    public function pause(Request $request, Campaign $campaign): RedirectResponse
    {
        if (! $campaign->isActive()) {
            return back()->with('error', 'Only active campaigns can be paused.');
        }

        if ($campaign->isExpired()) {
            return back()->with('error', 'Cannot pause expired campaign.');
        }

        if ($campaign->isPaused()) {
            return back()->with('error', 'Already paused.');
        }

        $data = $request->validate([
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        DB::transaction(function () use ($campaign, $data) {

            $campaign->pause(
                $data['reason'] ?? 'Paused by admin'
            );

            $this->log(
                $campaign,
                'paused',
                'Campaign paused.'
            );
        });

        return back()->with('success', 'Campaign paused.');
    }

    // -------------------------------------------------------------------------
    // RESUME
    // -------------------------------------------------------------------------
    public function resume(Campaign $campaign): RedirectResponse
    {
        if (! $campaign->isPaused()) {
            return back()->with('error', 'Campaign is not paused.');
        }

        if ($campaign->isExpired()) {
            return back()->with('error', 'Cannot resume expired campaign.');
        }

        if (! $campaign->ownerKycApproved()) {
            return back()->with('error', 'KYC not approved.');
        }

        try {

            DB::transaction(function () use ($campaign) {

                $campaign->resume();

                $this->log(
                    $campaign,
                    'resumed',
                    'Campaign resumed.'
                );
            });

        } catch (\RuntimeException $e) {

            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Campaign resumed.');
    }

    // -------------------------------------------------------------------------
    // COMPLETE
    // -------------------------------------------------------------------------
    public function complete(Campaign $campaign): RedirectResponse
    {
        if (! $campaign->isActive()) {
            return back()->with('error', 'Only active campaigns can be completed.');
        }

        if ($campaign->isExpired()) {
            return back()->with('error', 'Expired campaigns cannot be completed.');
        }

        if ($campaign->isCompleted()) {
            return back()->with('error', 'Already completed.');
        }

        DB::transaction(function () use ($campaign) {

            $campaign->complete();

            $this->log(
                $campaign,
                'completed',
                'Campaign completed.'
            );
        });

        return back()->with('success', 'Campaign marked completed.');
    }

    // -------------------------------------------------------------------------
    // BULK APPROVE
    // -------------------------------------------------------------------------
    public function bulkApprove(Request $request): Response
    {
        $data = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:campaigns,id'],
        ]);

        $campaigns = Campaign::with('user.kycVerification')->whereIn('id', $data['ids'])->get();
        $done = 0;
        $skipped = 0;

        foreach ($campaigns as $campaign) {
            if (! $campaign->isPending() || $campaign->isExpired()) {
                $skipped++;

                continue;
            }

            $hasKyc = $campaign->user?->kycVerification?->status === KycVerification::STATUS_APPROVED;
            if (! $hasKyc) {
                $skipped++;

                continue;
            }

            DB::transaction(function () use ($campaign) {
                $campaign->approve();
                $this->log($campaign, 'approved', 'Campaign approved (bulk).');
            });

            Mail::to($campaign->user)->send(new CampaignStatusMail($campaign, 'approved'));
            $done++;
        }

        if ($skipped > 0 && $done === 0) {
            $type = 'warning';
            $msg = "No campaigns approved ({$skipped} skipped: not pending / KYC unverified).";
        } else {
            $type = 'success';
            $msg = "{$done} campaign(s) approved.";
            if ($skipped > 0) {
                $msg .= " {$skipped} skipped (not pending / KYC unverified).";
            }
        }

        if ($request->expectsJson()) {
            return response()->json(['message' => $msg, 'type' => $type]);
        }

        return back()->with($type, $msg);
    }

    // -------------------------------------------------------------------------
    // BULK REJECT
    // -------------------------------------------------------------------------
    public function bulkReject(Request $request): Response
    {
        $data = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:campaigns,id'],
            'reason' => ['required', 'string', 'min:10', 'max:500'],
        ]);

        $campaigns = Campaign::with('user')->whereIn('id', $data['ids'])->get();
        $done = 0;
        $skipped = 0;

        foreach ($campaigns as $campaign) {
            if (! $campaign->isPending()) {
                $skipped++;

                continue;
            }

            DB::transaction(function () use ($campaign, $data) {
                $campaign->reject($data['reason']);
                $this->log($campaign, 'rejected', 'Rejected (bulk): '.$data['reason']);
            });

            Mail::to($campaign->user)
                ->send(new CampaignStatusMail($campaign, 'rejected', $data['reason']));
            $done++;
        }

        if ($skipped > 0 && $done === 0) {
            $type = 'warning';
            $msg = "No campaigns rejected ({$skipped} skipped: not pending).";
        } else {
            $type = 'success';
            $msg = "{$done} campaign(s) rejected.";
            if ($skipped > 0) {
                $msg .= " {$skipped} skipped (not pending).";
            }
        }

        if ($request->expectsJson()) {
            return response()->json(['message' => $msg, 'type' => $type]);
        }

        return back()->with($type, $msg);
    }

    // -------------------------------------------------------------------------
    // BULK PAUSE
    // -------------------------------------------------------------------------
    public function bulkPause(Request $request): Response
    {
        $data = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:campaigns,id'],
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        $campaigns = Campaign::with('user')->whereIn('id', $data['ids'])->get();
        $done = 0;
        $skipped = 0;

        foreach ($campaigns as $campaign) {
            if (! $campaign->isActive() || $campaign->isExpired() || $campaign->isPaused()) {
                $skipped++;

                continue;
            }

            DB::transaction(function () use ($campaign, $data) {
                $campaign->pause($data['reason'] ?? 'Paused by admin (bulk)');
                $this->log($campaign, 'paused', 'Campaign paused (bulk).');
            });

            $done++;
        }

        if ($skipped > 0 && $done === 0) {
            $type = 'warning';
            $msg = "No campaigns paused ({$skipped} skipped: not active).";
        } else {
            $type = 'success';
            $msg = "{$done} campaign(s) paused.";
            if ($skipped > 0) {
                $msg .= " {$skipped} skipped (not active).";
            }
        }

        if ($request->expectsJson()) {
            return response()->json(['message' => $msg, 'type' => $type]);
        }

        return back()->with($type, $msg);
    }

    // -------------------------------------------------------------------------
    // QUICK VIEW (AJAX fragment)
    // -------------------------------------------------------------------------
    public function quick(Campaign $campaign)
    {
        $campaign->load([
            'user.kycVerification',
            'category',
            'events',
            'logs',
        ]);

        return view('admin._campaign_quick', compact('campaign'));
    }

    // -------------------------------------------------------------------------
    // REQUEST KYC
    // -------------------------------------------------------------------------
    public function requestKyc(Request $request, Campaign $campaign): RedirectResponse
    {
        $data = $request->validate([
            'admin_message' => ['nullable', 'string', 'max:500'],
        ]);

        $hasApprovedKyc = KycVerification::where('user_id', $campaign->user_id)
            ->where('status', KycVerification::STATUS_APPROVED)
            ->exists();

        if ($hasApprovedKyc) {
            return back()->with('error', 'KYC already approved.');
        }

        $hasPendingKyc = KycVerification::where('user_id', $campaign->user_id)
            ->where('status', KycVerification::STATUS_PENDING)
            ->exists();

        if ($hasPendingKyc) {
            return back()->with('warning', 'KYC already pending.');
        }

        $campaign->user->notify(
            new KycRequestedNotification(
                campaign: $campaign,
                adminMessage: $data['admin_message'] ?? ''
            )
        );

        $this->log(
            $campaign,
            'kyc_requested',
            'KYC requested.'
        );

        return back()->with('success', 'KYC request sent.');
    }

    // -------------------------------------------------------------------------
    // LOGGER
    // -------------------------------------------------------------------------
    private function log(
        Campaign $campaign,
        string $action,
        string $message
    ): void {

        try {

            $campaign->logs()->create([
                'action' => $action,
                'message' => $message,
                'user_id' => auth()->id(),
            ]);

        } catch (\Throwable $e) {

            \Log::warning(
                'Campaign log failed: '.$e->getMessage()
            );
        }
    }
}
