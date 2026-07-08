<?php

namespace App\Http\Controllers;

use App\Mail\CampaignCreatedMail;
use App\Models\Campaign;
use App\Models\Category;
use App\Models\CategoryProduct;
use App\Models\CampaignUpdate;
use App\Models\CampaignProduct;
use App\Services\FundraiserLevelService;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Intervention\Image\Laravel\Facades\Image;
use Carbon\Carbon;

class CampaignController extends Controller
{
    public function __construct(
        protected FundraiserLevelService $levelService
    ) {}

    private const CREATE_RULES = [
        'title'       => 'required|string|max:255',
        'description' => 'required',
        'goal_amount' => 'required|numeric|min:1',
        'category_id' => 'required|exists:categories,id',
        'cover_image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        'location'    => 'nullable|string|max:255',
        'start_date'  => 'required|date',
        'end_date'    => 'required|date|after_or_equal:start_date',
        'video_url'   => 'nullable|url',
    ];

    private const UPDATE_RULES = [
        'title'       => 'required|string|max:255',
        'description' => 'required',
        'goal_amount' => 'required|numeric|min:1',
        'category_id' => 'required|exists:categories,id',
        'cover_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'location'    => 'nullable|string|max:255',
        'start_date'  => 'required|date',
        'end_date'    => 'required|date|after_or_equal:start_date',
        'video_url'   => 'nullable|url',
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

        $request->validate(self::CREATE_RULES);

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

        // Clear categories cache when new campaign is created
        Cache::forget('active_campaign_categories');

        Mail::to($campaign->user)->send(new CampaignCreatedMail($campaign));

        return redirect()
            ->route('kyc.upload.form', $campaign->id)
            ->with('success', 'Campaign submitted successfully! Now complete KYC verification to activate your campaign.');
    }

    // -------------------------------------------------------------------------
    // SHOW
    // -------------------------------------------------------------------------

    public function show(Campaign $campaign)
    {
        $campaign->load([
            'category', 'user', 'products', 'updates',
            'donations' => fn($q) => $q->where('payment_status', 'completed')->orderBy('created_at', 'desc'),
        ]);

        // Real-time expiry: end_date has no time component, so compare against
        // its end of day. Keeps the displayed state accurate on this page too
        // (the scheduled command can lag up to 24h).
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

        $request->validate(self::UPDATE_RULES);

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

        // If the campaign was expired but the end date was just extended into
        // the future, reactivate it so it isn't stuck in the expired state.
        if (
            $campaign->wasChanged('end_date') &&
            $campaign->campaign_state === Campaign::STATE_EXPIRED &&
            $request->end_date &&
            ! Carbon::parse($request->end_date)->endOfDay()->isPast()
        ) {
            $campaign->update(['campaign_state' => Campaign::STATE_ACTIVE]);
        }

        // Clear categories cache on update
        Cache::forget('active_campaign_categories');

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
            ->whereIn('campaign_state', ['active', 'completed']);

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
        $categories = Cache::remember('active_campaign_categories', 3600, function () {
            return Category::where('is_active', 1)
                ->withCount(['campaigns' => function ($q) {
                    $q->whereIn('campaign_state', ['active', 'completed']);
                }])
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
            ->whereIn('campaign_state', ['active', 'completed'])
            ->latest()
            ->paginate(12);

        return view('campaigns.all-campaigns', compact('category', 'campaigns'));
    }

    // -------------------------------------------------------------------------
    // PRIVATE HELPERS
    // -------------------------------------------------------------------------

    private function storeCampaignProducts(Request $request, Campaign $campaign): void
    {
        $products = $request->input('products', []);

        if (empty($products)) return;

        foreach ($products as $index => $data) {
            $name = trim($data['name'] ?? '');
            if ($name === '') continue;

            $categoryProductId = $data['category_product_id'] ?? null;
            $source            = $categoryProductId ? 'admin' : 'user';
            $quantity          = (int) ($data['quantity'] ?? $data['stock'] ?? 1);
            $image             = null;

            // Handle uploaded image file
            if ($request->hasFile("products.{$index}.image")) {
                $image = $request->file("products.{$index}.image")
                    ->store('campaign-products', 'public');
            }

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