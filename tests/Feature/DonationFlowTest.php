<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DonationFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_view_all_campaigns(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/all-campaigns');

        $response->assertStatus(200);
    }

    public function test_guest_cannot_reach_donate_redirect(): void
    {
        $response = $this->post('/donate/1');

        $response->assertRedirect('/login');
    }
}
