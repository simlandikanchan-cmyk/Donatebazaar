<?php

namespace Tests\Feature;

use App\Enums\PaymentStatus;
use App\Models\Campaign;
use App\Models\CampaignProduct;
use App\Models\Category;
use App\Models\Donation;
use App\Models\GiftCard;
use App\Models\Organization;
use App\Models\ProductReservation;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Services\WalletService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DataIntegrityTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->category = Category::create([
            'name' => 'Health',
            'slug' => 'health',
            'icon' => 'heart',
            'color' => '#2563eb',
            'is_active' => true,
        ]);
    }

    private function makeCampaign(array $overrides = []): Campaign
    {
        return Campaign::factory()->create(array_merge([
            'category_id' => $this->category->id,
        ], $overrides));
    }

    private function makeDonation(array $overrides = []): Donation
    {
        $defaults = [
            'campaign_id' => $this->makeCampaign()->id,
            'user_id' => $this->user->id,
            'donation_type' => 'money',
            'total_amount' => 100.00,
            'platform_fee' => 5.00,
            'net_amount' => 95.00,
            'currency' => 'INR',
        ];

        $fillable = array_intersect_key($overrides, array_flip((new Donation())->getFillable()));
        $nonFillable = array_diff_key($overrides, $fillable);

        $donation = Donation::create(array_merge($defaults, $fillable));

        foreach ($nonFillable as $key => $value) {
            $donation->$key = $value;
        }
        $donation->payment_status = 'completed';
        $donation->save();

        return $donation;
    }

    // ─── 3.1 ENUM CONVERSION ─────────────────────────────────────────────

    #[Test]
    public function payment_status_column_is_enum(): void
    {
        $columns = DB::select('DESCRIBE donations payment_status;');
        $this->assertNotEmpty($columns);
        $row = (array) $columns[0];
        $type = $row['Type'] ?? $row['type'] ?? '';
        $this->assertStringContainsString('enum', $type);
    }

    #[Test]
    public function payment_status_accepts_all_valid_values(): void
    {
        $d = $this->makeDonation();

        foreach (['completed', 'pending', 'failed', 'refunded', 'cancelled', 'processing'] as $status) {
            $d->payment_status = $status;
            $d->save();
            $this->assertEquals($status, $d->fresh()->payment_status);
        }
    }

    #[Test]
    public function donation_rejects_invalid_payment_status(): void
    {
        $d = $this->makeDonation();
        $this->expectException(\InvalidArgumentException::class);
        $d->payment_status = 'invalid_status';
        $d->save();
    }

    #[Test]
    public function donation_accepts_enum_object(): void
    {
        $d = $this->makeDonation();
        $d->payment_status = PaymentStatus::Completed;
        $d->save();
        $this->assertEquals('completed', $d->fresh()->payment_status);
    }

    #[Test]
    public function gift_card_rejects_invalid_payment_status(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        GiftCard::create([
            'code' => 'GC-TEST',
            'amount' => 100,
            'sender_name' => 'Sender',
            'sender_email' => 's@t.com',
            'recipient_name' => 'Recv',
            'recipient_email' => 'r@t.com',
            'message' => 'Hi',
            'send_at' => now(),
            'payment_status' => 'bogus',
        ]);
    }

    #[Test]
    public function gift_card_is_paid_works_correctly(): void
    {
        $card = GiftCard::create([
            'code' => 'PAID-CHK',
            'amount' => 100,
            'sender_name' => 'A',
            'sender_email' => 'a@b.com',
            'recipient_name' => 'B',
            'recipient_email' => 'b@a.com',
            'message' => 'Hi',
            'send_at' => now(),
            'payment_status' => 'completed',
        ]);
        $this->assertTrue($card->isPaid());

        $card->update(['payment_status' => 'pending']);
        $this->assertFalse($card->fresh()->isPaid());
    }

    // ─── 3.2 FINANCIAL CORRECTNESS ───────────────────────────────────────

    #[Test]
    public function wallet_balance_after_equals_balance_plus_reserved(): void
    {
        $wallet = Wallet::create([
            'owner_type' => User::class,
            'owner_id' => $this->user->id,
            'user_id' => $this->user->id,
            'currency' => 'INR',
        ]);
        $wallet->balance = 100.00;
        $wallet->reserved_balance = 50.00;
        $wallet->save();

        $service = app(WalletService::class);
        $tx = $service->record($wallet, 'credit', 25.00, 'adjustment', 1, Donation::class, 'test');

        $this->assertEquals(150.00, (float) $tx->balance_after);
    }

    #[Test]
    public function platform_fee_aggregation_is_accurate(): void
    {
        $campaign = $this->makeCampaign();

        foreach ([10.00, 20.00, 30.00] as $fee) {
            $this->makeDonation([
                'campaign_id' => $campaign->id,
                'payment_status' => 'completed',
                'platform_fee' => $fee,
                'total_amount' => $fee * 10,
            ]);
        }

        $total = (float) Donation::where('campaign_id', $campaign->id)
            ->where('payment_status', 'completed')
            ->sum('platform_fee');

        $this->assertEquals(60.00, $total);
    }

    #[Test]
    public function settlement_net_amount_matches_donations(): void
    {
        $campaign = $this->makeCampaign();

        $this->makeDonation(['campaign_id' => $campaign->id, 'total_amount' => 1000, 'platform_fee' => 50, 'net_amount' => 950]);
        $this->makeDonation(['campaign_id' => $campaign->id, 'total_amount' => 2000, 'platform_fee' => 100, 'net_amount' => 1900]);
        $this->makeDonation(['campaign_id' => $campaign->id, 'total_amount' => 3000, 'platform_fee' => 150, 'net_amount' => 2850]);

        $gross = (float) Donation::where('campaign_id', $campaign->id)->where('payment_status', 'completed')->sum('total_amount');
        $fees = (float) Donation::where('campaign_id', $campaign->id)->where('payment_status', 'completed')->sum('platform_fee');
        $net = (float) Donation::where('campaign_id', $campaign->id)->where('payment_status', 'completed')->sum('net_amount');

        $this->assertEquals(6000, $gross);
        $this->assertEquals(300, $fees);
        $this->assertEquals(5700, $net);
        $this->assertEquals($gross - $fees, $net);
    }

    // ─── 3.3 UNIQUE CONSTRAINTS ──────────────────────────────────────────

    #[Test]
    public function duplicate_wallet_transaction_is_rejected(): void
    {
        $wallet = Wallet::create([
            'owner_type' => User::class,
            'owner_id' => $this->user->id,
            'user_id' => $this->user->id,
            'balance' => 0,
            'currency' => 'INR',
        ]);

        $service = app(WalletService::class);

        $service->record($wallet, 'credit', 100.00, 'donation', 42, Donation::class, 'first');

        $this->expectException(\Illuminate\Database\QueryException::class);

        $service->record($wallet, 'credit', 100.00, 'donation', 42, Donation::class, 'duplicate');
    }

    #[Test]
    public function duplicate_product_reservation_rejected_by_idempotency_key(): void
    {
        $campaign = $this->makeCampaign();
        $product = CampaignProduct::create([
            'campaign_id' => $campaign->id,
            'user_id' => $this->user->id,
            'name' => 'T-shirt',
            'price' => 500.00,
            'quantity' => 10,
            'remaining_quantity' => 10,
            'reserved_quantity' => 0,
            'is_active' => true,
        ]);

        $donation = $this->makeDonation();

        ProductReservation::create([
            'product_id' => $product->id,
            'quantity' => 1,
            'donation_id' => $donation->id,
            'expires_at' => now()->addMinutes(15),
            'idempotency_key' => 'key-123',
        ]);

        $this->expectException(\Illuminate\Database\QueryException::class);

        ProductReservation::create([
            'product_id' => $product->id,
            'quantity' => 1,
            'donation_id' => $donation->id,
            'expires_at' => now()->addMinutes(15),
            'idempotency_key' => 'key-123',
        ]);
    }

    #[Test]
    public function only_one_wallet_per_owner(): void
    {
        $org = Organization::factory()->create();

        Wallet::create([
            'owner_type' => Organization::class,
            'owner_id' => $org->id,
            'user_id' => null,
            'balance' => 0,
            'currency' => 'INR',
        ]);

        $this->expectException(\Illuminate\Database\QueryException::class);

        Wallet::create([
            'owner_type' => Organization::class,
            'owner_id' => $org->id,
            'user_id' => null,
            'balance' => 0,
            'currency' => 'INR',
        ]);
    }

    // ─── 3.4 CASCADE DELETES ─────────────────────────────────────────────

    #[Test]
    public function force_delete_campaign_cascades_to_donations(): void
    {
        $campaign = $this->makeCampaign();
        $donation = $this->makeDonation(['campaign_id' => $campaign->id]);

        $campaign->forceDelete();

        $this->assertDatabaseMissing('campaigns', ['id' => $campaign->id]);
        $this->assertDatabaseMissing('donations', ['id' => $donation->id]);
    }

    #[Test]
    public function delete_user_sets_donation_user_id_to_null(): void
    {
        $campaign = $this->makeCampaign();
        $donation = $this->makeDonation([
            'user_id' => $this->user->id,
            'campaign_id' => $campaign->id,
        ]);

        $this->user->delete();

        $this->assertDatabaseHas('donations', [
            'id' => $donation->id,
            'user_id' => null,
        ]);
    }

    #[Test]
    public function delete_user_cascades_to_wallet(): void
    {
        $wallet = Wallet::create([
            'owner_type' => User::class,
            'owner_id' => $this->user->id,
            'user_id' => $this->user->id,
            'balance' => 0,
            'currency' => 'INR',
        ]);

        $this->user->delete();

        $this->assertDatabaseMissing('wallets', ['id' => $wallet->id]);
    }

    #[Test]
    public function soft_delete_preserves_campaign_for_restore(): void
    {
        $campaign = $this->makeCampaign();

        $campaign->delete();
        $this->assertSoftDeleted($campaign);

        $campaign->restore();
        $this->assertNotSoftDeleted($campaign);
        $this->assertDatabaseHas('campaigns', ['id' => $campaign->id]);
    }

    #[Test]
    public function delete_product_cascades_to_reservations(): void
    {
        $campaign = $this->makeCampaign();
        $product = CampaignProduct::create([
            'campaign_id' => $campaign->id,
            'user_id' => $this->user->id,
            'name' => 'Mug',
            'price' => 200.00,
            'quantity' => 5,
            'remaining_quantity' => 5,
            'reserved_quantity' => 0,
            'is_active' => true,
        ]);

        $reservation = ProductReservation::create([
            'product_id' => $product->id,
            'quantity' => 1,
            'expires_at' => now()->addMinutes(15),
        ]);

        $product->forceDelete();

        $this->assertDatabaseMissing('campaign_products', ['id' => $product->id]);
        $this->assertDatabaseMissing('product_reservations', ['id' => $reservation->id]);
    }
}
