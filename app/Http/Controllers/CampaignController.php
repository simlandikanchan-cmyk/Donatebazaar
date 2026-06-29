<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Category;
use App\Models\CategoryProduct;
use App\Models\CampaignUpdate;
use App\Models\CampaignProduct;
use App\Models\KycVerification;
use App\Services\FundraiserLevelService;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class CampaignController extends Controller
{
    public function __construct(
        protected FundraiserLevelService $levelService
    ) {}

    private const STORE_RULES = [
        'title'       => 'required|string|max:255',
        'description' => 'required',
        'goal_amount' => 'required|numeric|min:1',
        'category_id' => 'required|exists:categories,id',
        'cover_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'location'    => 'nullable|string|max:255',
        'start_date'  => 'required|date',
        'end_date'    => 'required|date',
        'video_url'   => 'nullable|url',
        'kyc_aadhaar' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096',
        'kyc_pan'     => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096',
        'kyc_selfie'  => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
        'kyc_account_number' => 'nullable|string|max:30',
        'kyc_ifsc'           => 'nullable|string|max:20',
        'kyc_bank_name'      => 'nullable|string|max:255',
    ];

    // -------------------------------------------------------------------------
    // CREATE
    // -------------------------------------------------------------------------

    public function create()
    {
        $categories       = Category::where('is_active', 1)->get();
        $categoryProducts = CategoryProduct::where('is_active', 1)->get();
        $user             = auth()->user();
        $maxGoal          = $user->maxCampaignGoal();
        $levelName        = $user->fundraiserLevelName();

        return view('campaigns.create', compact(
            'categories', 'categoryProducts', 'maxGoal', 'levelName'
        ));
    }

    // -------------------------------------------------------------------------
    // STORE
    // -------------------------------------------------------------------------

    public function store(Request $request)
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

        $request->merge([
            'goal_amount' => str_replace(',', '', $request->goal_amount),
        ]);

        $request->validate(self::STORE_RULES);

        $campaign = Campaign::create([
            'user_id'           => Auth::id(),
            'category_id'       => $request->category_id,
            'title'             => $request->title,
            'slug'              => $this->generateSlug($request->title),
            'description'       => $request->description,
            'goal_amount'       => $request->goal_amount,
            'raised_amount'     => 0,
            'location'          => $request->location,
            'start_date'        => $request->start_date,
            'end_date'          => $request->end_date,
            'video_url'         => $request->video_url,
            'cover_image'       => $this->uploadCoverImage($request),
            'is_featured'       => $request->boolean('is_featured'),
            'is_urgent'         => $request->boolean('is_urgent'),
            'campaign_state'    => Campaign::STATE_PENDING,
            'required_level_id' => $check['level']->id,
        ]);

        $this->storeCampaignUpdates($request, $campaign);
        $this->storeCampaignProducts($request, $campaign);
        $this->storeKycData($request);

        // Clear categories cache when new campaign is created
        Cache::forget('active_categories');

        return redirect()
            ->route('dashboard')
            ->with('success', 'Campaign submitted successfully! Our team will review it within 24 hours.');
    }

    // -------------------------------------------------------------------------
    // SHOW
    // -------------------------------------------------------------------------

    public function show(Campaign $campaign)
    {
        $campaign->load(['category', 'user', 'products', 'updates']);

        return view('campaigns.show', compact('campaign'));
    }

    // -------------------------------------------------------------------------
    // EDIT
    // -------------------------------------------------------------------------

    public function edit(Campaign $campaign)
    {
        if ($campaign->user_id !== Auth::id()) {
            abort(403);
        }

        $categories       = Category::where('is_active', 1)->get();
        $categoryProducts = CategoryProduct::where('is_active', 1)->get();
        $user             = auth()->user();
        $maxGoal          = $user->maxCampaignGoal();
        $levelName        = $user->fundraiserLevelName();

        return view('campaigns.edit', compact(
            'campaign', 'categories', 'categoryProducts', 'maxGoal', 'levelName'
        ));
    }

    // -------------------------------------------------------------------------
    // UPDATE
    // -------------------------------------------------------------------------

    public function update(Request $request, Campaign $campaign)
    {
        if ($campaign->user_id !== Auth::id()) {
            abort(403);
        }

        $request->merge([
            'goal_amount' => str_replace(',', '', $request->goal_amount),
        ]);

        $request->validate(self::STORE_RULES);

        $campaign->update([
            'category_id' => $request->category_id,
            'title'       => $request->title,
            'description' => $request->description,
            'goal_amount' => $request->goal_amount,
            'location'    => $request->location,
            'start_date'  => $request->start_date,
            'end_date'    => $request->end_date,
            'video_url'   => $request->video_url,
            'is_featured' => $request->boolean('is_featured'),
            'is_urgent'   => $request->boolean('is_urgent'),
            'cover_image' => $this->uploadCoverImage($request) ?? $campaign->cover_image,
        ]);

        // Clear categories cache on update
        Cache::forget('active_categories');

        return redirect()
            ->route('campaign.show', $campaign->id)
            ->with('success', 'Campaign updated successfully.');
    }

    // -------------------------------------------------------------------------
    // PAUSE
    // -------------------------------------------------------------------------

    public function pause(Campaign $campaign)
    {
        if ($campaign->user_id !== Auth::id()) {
            abort(403);
        }

        if ($campaign->campaign_state !== Campaign::STATE_ACTIVE) {
            return back()->with('error', 'Only active campaigns can be paused.');
        }

        $campaign->update(['campaign_state' => Campaign::STATE_PAUSED]);

        return back()->with('success', 'Campaign paused.');
    }

    // -------------------------------------------------------------------------
    // RESUME
    // -------------------------------------------------------------------------

    public function resume(Campaign $campaign)
    {
        if ($campaign->user_id !== Auth::id()) {
            abort(403);
        }

        if ($campaign->campaign_state !== Campaign::STATE_PAUSED) {
            return back()->with('error', 'Only paused campaigns can be resumed.');
        }

        $campaign->update(['campaign_state' => Campaign::STATE_ACTIVE]);

        return back()->with('success', 'Campaign resumed.');
    }

    // -------------------------------------------------------------------------
    // RESUBMIT
    // -------------------------------------------------------------------------

    public function resubmit(Campaign $campaign)
    {
        if ($campaign->user_id !== Auth::id()) {
            abort(403);
        }

        if ($campaign->campaign_state !== Campaign::STATE_REJECTED) {
            return back()->with('error', 'Only rejected campaigns can be resubmitted.');
        }

        $campaign->update(['campaign_state' => Campaign::STATE_PENDING]);

        return back()->with('success', 'Campaign resubmitted for review.');
    }

    // -------------------------------------------------------------------------
    // INDEX (user's own campaigns)
    // -------------------------------------------------------------------------

    public function index()
    {
        $campaigns = Campaign::where('user_id', Auth::id())
            ->latest()
            ->paginate(12);

        return view('campaigns.index', compact('campaigns'));
    }

    // -------------------------------------------------------------------------
    // PUBLIC CAMPAIGNS
    // -------------------------------------------------------------------------

    public function publicCampaigns(Request $request)
    {
        $query = Campaign::with(['category', 'user'])
            ->withCount('donations')
            ->whereIn('campaign_state', ['active', 'completed', 'expired']);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('category')) {
            //  Direct FK lookup — faster than whereHas
            $category = Category::where('slug', $request->category)
                                ->select('id')
                                ->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        switch ($request->sort) {
            case 'most_funded': $query->orderByDesc('raised_amount'); break;
            case 'ending_soon': $query->orderBy('end_date');          break;
            default:            $query->latest();                     break;
        }

        //  Cache categories for 1 hour — they rarely change
        $categories = Cache::remember('active_categories', 3600, function () {
            return Category::withCount('campaigns')
                           ->where('is_active', 1)
                           ->get();
        });

        $campaigns = $query->paginate(12)->withQueryString();

        return view('campaigns.all-campaigns', compact('campaigns', 'categories'));
    }

    // -------------------------------------------------------------------------
    // BY CATEGORY
    // -------------------------------------------------------------------------

    public function byCategory($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        $campaigns = Campaign::with(['category', 'user', 'products'])
            ->where('category_id', $category->id)
            ->whereIn('campaign_state', ['active', 'completed', 'expired'])
            ->latest()
            ->paginate(12);

        return view('campaigns.all-campaigns', compact('category', 'campaigns'));
    }

    // -------------------------------------------------------------------------
    // PRIVATE HELPERS
    // -------------------------------------------------------------------------

    private function storeKycData(Request $request): void
    {
        $user     = auth()->user();
        $existing = $user->kycVerification;

        $data = [
            'document_type'      => null,
            'document_number'    => null,
            'kyc_account_name'   => $request->kyc_account_name,
            'kyc_account_number' => $request->kyc_account_number,
            'kyc_ifsc'           => $request->kyc_ifsc ? strtoupper($request->kyc_ifsc) : null,
            'kyc_bank_name'      => $request->kyc_bank_name,
        ];

        if (! $existing || ! $existing->isApproved()) {
            $data['status']           = KycVerification::STATUS_PENDING;
            $data['rejection_reason'] = null;
            $data['verified_by']      = null;
            $data['verified_at']      = null;
        }

        if ($request->hasFile('kyc_aadhaar')) {
            if ($existing?->aadhaar_url) {
                Storage::disk('public')->delete($existing->aadhaar_url);
            }
            $data['aadhaar_url'] = $request->file('kyc_aadhaar')
                ->store('kyc_documents', 'public');
        }

        if ($request->hasFile('kyc_pan')) {
            if ($existing?->pan_url) {
                Storage::disk('public')->delete($existing->pan_url);
            }
            $data['pan_url'] = $request->file('kyc_pan')
                ->store('kyc_documents', 'public');
        }

        if ($request->hasFile('kyc_selfie')) {
            if ($existing?->selfie_url) {
                Storage::disk('public')->delete($existing->selfie_url);
            }
            $data['selfie_url'] = $request->file('kyc_selfie')
                ->store('kyc_documents', 'public');
        }

        KycVerification::updateOrCreate(
            ['user_id' => $user->id],
            $data
        );
    }

    private function storeCampaignProducts(Request $request, Campaign $campaign): void
    {
        $products = $request->input('products', []);

        if (empty($products)) return;

        foreach ($products as $data) {
            $name = trim($data['name'] ?? '');
            if ($name === '') continue;

            $categoryProductId = $data['category_product_id'] ?? null;
            $source            = $categoryProductId ? 'admin' : 'user';
            $quantity          = (int) ($data['quantity'] ?? $data['stock'] ?? 1);
            $image             = $data['image'] ?? null;

            if (empty($image) && $categoryProductId) {
                $catProduct = CategoryProduct::find((int) $categoryProductId);
                $image      = $catProduct?->image;
            }

            if (empty($image)) {
                $catProduct = CategoryProduct::where('name', $name)
                    ->where('category_id', $campaign->category_id)
                    ->where('is_active', 1)
                    ->first();

                if ($catProduct) {
                    $image             = $catProduct->image;
                    $categoryProductId = $categoryProductId ?? $catProduct->id;
                    $source            = 'admin';
                }
            }

            CampaignProduct::create([
                'campaign_id'         => $campaign->id,
                'category_product_id' => $categoryProductId,
                'user_id'             => auth()->id(),
                'name'                => $name,
                'description'         => trim($data['description'] ?? ''),
                'price'               => (float) ($data['price'] ?? 0),
                'quantity'            => $quantity,
                'remaining_quantity'  => $quantity,
                'image'               => $image,
                'source'              => $source,
                'approval_status'     => $source === 'admin' ? 'approved' : 'pending',
                'approved_by'         => null,
                'approved_at'         => null,
                'is_active'           => true,
            ]);
        }
    }

    private function storeCampaignUpdates(Request $request, Campaign $campaign): void
    {
        $updates = $request->input('updates', []);

        if (empty($updates)) return;

        foreach ($updates as $index => $data) {
            $title = trim($data['title'] ?? '');
            if ($title === '') continue;

            $documentPath = null;
            $fileKey      = "updates.{$index}.document";

            if ($request->hasFile($fileKey)) {
                $documentPath = $request->file($fileKey)->store('campaign-updates', 'public');
            }

            CampaignUpdate::create([
                'campaign_id' => $campaign->id,
                'title'       => $title,
                'body'        => trim($data['body'] ?? ''),
                'description' => trim($data['description'] ?? ''),
                'media_url'   => $documentPath,
                'created_by'  => auth()->id(),
            ]);
        }
    }

    private function uploadCoverImage(Request $request): ?string
    {
        if (! $request->hasFile('cover_image')) return null;

        $file     = $request->file('cover_image');
        $filename = Str::slug($request->title) . '-' . time() . '.webp';
        $savePath = storage_path('app/public/images/' . $filename);

        //  Auto-convert to WebP on upload — smaller file size
        Image::read($file)
             ->scale(width: 1200)
             ->toWebp(85)
             ->save($savePath);

        return 'images/' . $filename;
    }

    private function generateSlug(string $title): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i    = 1;

        while ( Campaign :: where ('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }
}