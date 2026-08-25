<?php

namespace App\Services\Campaign;

use App\Http\Requests\StoreCampaignRequest;
use App\Models\Campaign;
use App\Models\CampaignProduct;
use App\Models\CampaignUpdate;
use App\Models\CategoryProduct;
use App\Services\SlugGenerator;
use Illuminate\Http\Request;

class CampaignCreationService
{
    public function __construct(
        private SlugGenerator $slugGenerator,
        private CampaignCoverImageService $coverImageService,
    ) {}

    public function create(
        StoreCampaignRequest $request,
        int $userId,
        ?int $requiredLevelId = null
    ): Campaign {
        $campaign = Campaign::create([
            'user_id' => $userId,
            'category_id' => $request->category_id,
            'title' => $request->title,
            'slug' => $this->slugGenerator->unique(new Campaign(), $request->title),
            'description' => $request->description,
            'goal_amount' => $request->goal_amount,
            'raised_amount' => 0,
            'location' => $request->location,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'video_url' => $request->video_url,
            'cover_image' => $request->hasFile('cover_image')
                ? $this->coverImageService->store($request->file('cover_image'), $request->title)
                : null,
            'is_featured' => $request->boolean('is_featured'),
            'is_urgent' => $request->boolean('is_urgent'),
            'campaign_state' => Campaign::STATE_PENDING,
            'required_level_id' => $requiredLevelId,
        ]);

        $this->storeCampaignProducts($request, $campaign, $userId);
        $this->storeCampaignUpdates($request, $campaign, $userId);

        return $campaign;
    }

    private function storeCampaignProducts(Request $request, Campaign $campaign, int $userId): void
    {
        $products = $request->input('products', []);

        if (empty($products)) {
            return;
        }

        foreach ($products as $index => $data) {
            $name = trim($data['name'] ?? '');
            if ($name === '') {
                continue;
            }

            $categoryProductId = $data['category_product_id'] ?? null;
            $source = $categoryProductId ? 'admin' : 'user';
            $quantity = (int) ($data['quantity'] ?? $data['stock'] ?? 1);
            $image = null;

            if ($request->hasFile("products.{$index}.image")) {
                $image = $request->file("products.{$index}.image")
                    ->store('campaign-products', 'public');
            }

            if (empty($image) && $categoryProductId) {
                $catProduct = CategoryProduct::find((int) $categoryProductId);
                $image = $catProduct?->image;
            }

            if (empty($image)) {
                $catProduct = CategoryProduct::where('name', $name)
                    ->where('category_id', $campaign->category_id)
                    ->where('is_active', 1)
                    ->first();

                if ($catProduct) {
                    $image = $catProduct->image;
                    $categoryProductId = $categoryProductId ?? $catProduct->id;
                    $source = 'admin';
                }
            }

            CampaignProduct::create([
                'campaign_id' => $campaign->id,
                'category_product_id' => $categoryProductId,
                'user_id' => $userId,
                'name' => $name,
                'description' => trim($data['description'] ?? ''),
                'price' => (float) ($data['price'] ?? 0),
                'quantity' => $quantity,
                'remaining_quantity' => $quantity,
                'image' => $image,
                'source' => $source,
                'approval_status' => $source === 'admin' ? 'approved' : 'pending',
                'approved_by' => null,
                'approved_at' => null,
                'is_active' => true,
            ]);
        }
    }

    private function storeCampaignUpdates(Request $request, Campaign $campaign, int $userId): void
    {
        $updates = $request->input('updates', []);

        if (empty($updates)) {
            return;
        }

        foreach ($updates as $index => $data) {
            $title = trim($data['title'] ?? '');
            if ($title === '') {
                continue;
            }

            $documentPath = null;
            $fileKey = "updates.{$index}.document";

            if ($request->hasFile($fileKey)) {
                $documentPath = $request->file($fileKey)->store('campaign-updates', 'public');
            }

            CampaignUpdate::create([
                'campaign_id' => $campaign->id,
                'title' => $title,
                'body' => trim($data['body'] ?? ''),
                'description' => trim($data['description'] ?? ''),
                'media_url' => $documentPath,
                'created_by' => $userId,
            ]);
        }
    }
}