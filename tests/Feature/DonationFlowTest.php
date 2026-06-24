<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DonationFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_donation_page_loads()
    {
        $response = $this->get('/donate');

        $response->assertStatus(200);
    }
}