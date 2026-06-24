<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CampaignTest extends TestCase
{
    use RefreshDatabase;

    public function test_campaign_page_loads()
    {
        $response = $this->get('/campaigns');

        $response->assertStatus(200);
    }
}