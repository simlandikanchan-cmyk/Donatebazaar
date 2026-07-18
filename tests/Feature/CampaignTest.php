<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CampaignTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_view_their_campaigns(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/campaigns');

        $response->assertStatus(200);
    }

    public function test_guest_is_redirected_from_campaigns_list(): void
    {
        $response = $this->get('/campaigns');

        $response->assertRedirect('/login');
    }
}
