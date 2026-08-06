<?php

namespace App\Jobs;

use App\Mail\CampaignProductStatusMail;
use App\Models\CampaignProduct;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendCampaignProductStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public int $tries = 3;

    public int $timeout = 120;

    public array $backoff = [60, 300, 900];

    public function __construct(
        public array $productIds,
        public string $status,
        public ?string $reason = null,
        public ?int $adminId = null,
    ) {}

    public function handle(): void
    {
        $products = CampaignProduct::with('user')
            ->whereIn('id', $this->productIds)
            ->get();

        $admin = $this->adminId ? User::find($this->adminId) : null;

        foreach ($products as $product) {
            if (! $product->user) {
                continue;
            }

            try {
                Mail::to($product->user)->send(
                    new CampaignProductStatusMail($product, $this->status, $this->reason, $admin)
                );
            } catch (Throwable $e) {
             
                Log::error('Failed to send campaign product status mail', [
                    'product_id' => $product->id,
                    'status'     => $this->status,
                    'error'      => $e->getMessage(),
                ]);
            }
        }
    }
}