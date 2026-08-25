<?php

namespace App\Http\Controllers;

use App\Mail\CampaignCreatedMail;
use App\Models\Campaign;
use App\Models\CategoryProduct;
use App\Models\KycVerification;
use App\Repositories\CampaignRepository;
use App\Repositories\CategoryRepository;
use App\Http\Requests\StoreCampaignRequest;
use App\Http\Requests\UpdateCampaignRequest;
use App\Services\Campaign\CampaignCoverImageService;
use App\Services\Campaign\CampaignCreationService;
use App\Services\FundraiserLevelService;
use App\Services\SlugGenerator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class CampaignController extends Controller
{
    public function __construct(
        protected FundraiserLevelService $levelService,
        protected CampaignRepository $campaignRepo,
        protected CategoryRepository $categoryRepo,
        protected SlugGenerator $slugGenerator,
        protected CampaignCreationService $creationService,
        protected CampaignCoverImageService $coverImageService,
    ) {}

    public function create()
    {
        $categories = $this->categoryRepo->getActive();
        $categoryProducts = CategoryProduct::where('is_active', 1)->get();
        $user = auth()->user();
        $maxGoal = $user->maxCampaignGoal();
        $levelName = $user->fundraiserLevelName();

        return view('campaigns.create', compact(
            'categories', 'categoryProducts', 'maxGoal', 'levelName'
        ));
    }

    public function store(StoreCampaignRequest $request)
    {
        if (! Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        $check = $this->levelService->canCreateCampaign(
            auth()->user(),
            (float) str_replace(',', '', $request->goal_amount)
        );

        if (! $check['allowed']) {
            return back()->withErrors(['goal_amount' => $check['reason']])->withInput();
        }

        $existing = Campaign::where('user_id', Auth::id())
            ->where('title', $request->title)
            ->whereNull('deleted_at')
            ->exists();

        if ($existing) {
            return back()->withErrors(['title' => 'You already have a campaign with this title.'])->withInput();
        }

        $updates = $request->input('updates', []);
        $hasValidUpdate = false;
        foreach ($updates as $data) {
            if (trim($data['title'] ?? '') !== '' && trim($data['body'] ?? '') !== '') {
                $hasValidUpdate = true;
                break;
            }
        }
        if (! $hasValidUpdate) {
            return back()->withErrors(['updates' => 'Please add at least one update with a title and description.'])->withInput();
        }

        $campaign = $this->creationService->create(
            $request,
            Auth::id(),
            $check['level']->id
        );

        Cache::forget('active_campaign_categories');

        try {
            Mail::to($campaign->user)->send(new CampaignCreatedMail($campaign));
        } catch (\Throwable $e) {
            report($e);
        }

        $hasApprovedKyc = KycVerification::where('user_id', Auth::id())
            ->where('status', KycVerification::STATUS_APPROVED)
            ->exists();

        if (! $hasApprovedKyc) {
            return redirect()
                ->route('kyc.upload.form', $campaign->id)
                ->with('success', 'Campaign submitted successfully! Now complete KYC verification to activate your campaign.');
        }

        return redirect()
            ->route('campaign.show', $campaign->id)
            ->with('success', 'Campaign submitted successfully!');
    }

    public function show(Campaign $campaign)
    {
        if ($campaign->user_id !== Auth::id()) {
            abort(403);
        }

        $campaign->load([
            'category', 'user', 'products', 'updates',
            'donations' => fn ($q) => $q->where('payment_status', 'completed')->orderBy('created_at', 'desc'),
        ]);

        if (
            $campaign->campaign_state === Campaign::STATE_ACTIVE &&
            $campaign->end_date &&
            Carbon::parse($campaign->end_date)->endOfDay()->isPast()
        ) {
            $campaign->update(['campaign_state' => Campaign::STATE_EXPIRED]);
            $campaign->campaign_state = Campaign::STATE_EXPIRED;
        }

        return view('campaigns.show', compact('campaign'));
    }

    public function edit(Campaign $campaign)
    {
        if ($campaign->user_id !== Auth::id()) {
            abort(403);
        }

        $categories = $this->categoryRepo->getActive();
        $categoryProducts = CategoryProduct::where('is_active', 1)->get();
        $user = auth()->user();
        $maxGoal = $user->maxCampaignGoal();
        $levelName = $user->fundraiserLevelName();

        return view('campaigns.edit', compact(
            'campaign', 'categories', 'categoryProducts', 'maxGoal', 'levelName'
        ));
    }

    public function update(UpdateCampaignRequest $request, Campaign $campaign)
    {
        if ($campaign->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validated();

        $campaign->update([
            'category_id' => $validated['category_id'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'goal_amount' => $validated['goal_amount'],
            'location' => $validated['location'] ?? null,
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'video_url' => $validated['video_url'] ?? null,
            'is_featured' => $request->boolean('is_featured'),
            'is_urgent' => $request->boolean('is_urgent'),
            'cover_image' => $request->hasFile('cover_image')
                ? $this->coverImageService->store($request->file('cover_image'), $request->title)
                : $campaign->cover_image,
        ]);

        if (
            $campaign->wasChanged('end_date') &&
            $campaign->campaign_state === Campaign::STATE_EXPIRED &&
            $request->end_date &&
            ! Carbon::parse($request->end_date)->endOfDay()->isPast()
        ) {
            $campaign->update(['campaign_state' => Campaign::STATE_ACTIVE]);
        }

        Cache::forget('active_campaign_categories');

        return redirect()
            ->route('campaign.show', $campaign->id)
            ->with('success', 'Campaign updated successfully.');
    }

    public function pause(Campaign $campaign)
    {
        if ($campaign->user_id !== Auth::id()) {
            abort(403);
        }

        if ($campaign->campaign_state !== Campaign::STATE_ACTIVE) {
            return back()->with('error', 'Only active campaigns can be paused.');
        }

        $campaign->pause('Paused by user');

        return back()->with('success', 'Campaign paused.');
    }

    public function resume(Campaign $campaign)
    {
        if ($campaign->user_id !== Auth::id()) {
            abort(403);
        }

        if ($campaign->campaign_state !== Campaign::STATE_PAUSED) {
            return back()->with('error', 'Only paused campaigns can be resumed.');
        }

        if (! $campaign->ownerKycApproved()) {
            return back()->with('error', 'KYC not approved. Please complete KYC verification before resuming your campaign.');
        }

        try {
            $campaign->resume();
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Campaign resumed.');
    }

    public function toggleFollow(Campaign $campaign)
    {
        $user = Auth::user();

        if ($campaign->isFollowedBy($user)) {
            $campaign->unfollow($user);
            $message = 'You unfollowed this campaign.';
        } else {
            $campaign->follow($user);
            $message = 'You are now following this campaign — you\'ll be notified about new events.';
        }

        // The public campaign page is cached per user — clear it so the
        // Follow button state and follower count update immediately.
        $categorySlug = optional($campaign->category)->slug;
        Cache::forget("campaign:show:{$categorySlug}:{$campaign->slug}:" . $user->id);
        Cache::forget("campaign:show:{$categorySlug}:{$campaign->slug}:guest");

        return back()->with('success', $message);
    }

    public function resubmit(Campaign $campaign)
    {
        if ($campaign->user_id !== Auth::id()) {
            abort(403);
        }

        if ($campaign->campaign_state !== Campaign::STATE_REJECTED) {
            return back()->with('error', 'Only rejected campaigns can be resubmitted.');
        }

        $campaign->resubmit();

        return back()->with('success', 'Campaign resubmitted for review.');
    }

    public function index()
    {
        $campaigns = Campaign::where('user_id', Auth::id())
            ->with('donations')
            ->withSum(['donations' => fn ($q) => $q->where('payment_status', 'completed')], 'total_amount')
            ->latest()
            ->paginate(12);

        return view('campaigns.index', compact('campaigns'));
    }
}
