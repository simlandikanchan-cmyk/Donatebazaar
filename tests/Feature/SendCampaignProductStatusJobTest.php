<?php

namespace Tests\Feature;

use App\Jobs\SendCampaignProductStatusJob;
use App\Models\Campaign;
use App\Models\CampaignProduct;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SendCampaignProductStatusJobTest extends TestCase
{
    use RefreshDatabase;

    private function makeProductWithUser(): array
    {
        $user = User::factory()->create();

        $campaign = Campaign::create([
            'user_id'     => $user->id,
            'title'       => 'Test Campaign',
            'slug'        => 'test-campaign-' . uniqid(),
            'description' => 'desc',
            'goal_amount' => 1000,
        ]);

        $product = CampaignProduct::create([
            'campaign_id'        => $campaign->id,
            'user_id'            => $user->id,
            'name'               => 'Product',
            'price'              => 100,
            'quantity'           => 10,
            'remaining_quantity' => 10,
            'reserved_quantity'  => 0,
            'approval_status'    => 'pending',
            'is_active'          => false,
        ]);

        return [$product, $user];
    }

    public function test_individual_mail_failure_does_not_stop_loop()
    {
        [$product1, $user1] = $this->makeProductWithUser();
        [$product2, $user2] = $this->makeProductWithUser();
        [$product3, $user3] = $this->makeProductWithUser();

        $callCount = 0;

        $pendingMail = \Mockery::mock();
        $pendingMail->shouldReceive('send')
            ->times(3)
            ->andReturnUsing(function () use (&$callCount) {
                $callCount++;
                if ($callCount === 2) {
                    throw new \RuntimeException('SMTP timeout on product 2');
                }
            });

        Mail::shouldReceive('to')
            ->times(3)
            ->andReturn($pendingMail);

        Log::spy();

        $job = new SendCampaignProductStatusJob(
            [$product1->id, $product2->id, $product3->id],
            'approved',
            null,
            null,
        );

        // Must NOT throw — exception from the 2nd mail is caught and logged.
        $job->handle();

        // All three iterations ran despite the 2nd one throwing.
        $this->assertEquals(3, $callCount, 'All three iterations should have run');

        // The 2nd product's failure was logged.
        Log::shouldHaveReceived('error')
            ->once()
            ->with('Failed to send campaign product status mail', \Mockery::on(function ($context) use ($product2) {
                return ($context['product_id'] ?? null) === $product2->id
                    && ($context['status'] ?? null) === 'approved'
                    && str_contains($context['error'] ?? '', 'SMTP timeout on product 2');
            }));
    }
}
