<?php

namespace Tests\Feature\FormValidation;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WalletFormsValidationTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['role' => 'donor']);
    }

    // ─── Save Payout Account ──────────────────────────────────────────────

    public function test_save_payout_account_happy_path(): void
    {
        $response = $this->actingAs($this->user)->post('/user/dashboard/wallet/payout-account', [
            'account_holder_name' => 'John Doe',
            'bank_name' => 'State Bank of India',
            'account_number' => '12345678901',
            'ifsc_code' => 'SBIN0001234',
            'upi_id' => 'john@upi',
        ]);

        $response->assertSessionHasNoErrors();
    }

    public function test_save_payout_account_holder_name_required(): void
    {
        $response = $this->actingAs($this->user)->post('/user/dashboard/wallet/payout-account', [
            'account_holder_name' => '', 'bank_name' => 'SBI', 'account_number' => '12345', 'ifsc_code' => 'SBIN001',
        ]);

        $response->assertSessionHasErrors('account_holder_name');
    }

    public function test_save_payout_account_holder_name_max_length(): void
    {
        $response = $this->actingAs($this->user)->post('/user/dashboard/wallet/payout-account', [
            'account_holder_name' => str_repeat('A', 256), 'bank_name' => 'SBI',
        ]);

        $response->assertSessionHasErrors('account_holder_name');
    }

    public function test_save_payout_account_bank_name_max_length(): void
    {
        $response = $this->actingAs($this->user)->post('/user/dashboard/wallet/payout-account', [
            'account_holder_name' => 'John Doe', 'bank_name' => str_repeat('A', 256),
        ]);

        $response->assertSessionHasErrors('bank_name');
    }

    public function test_save_payout_account_number_max_length(): void
    {
        $response = $this->actingAs($this->user)->post('/user/dashboard/wallet/payout-account', [
            'account_holder_name' => 'John Doe', 'bank_name' => 'SBI', 'account_number' => str_repeat('1', 51),
        ]);

        $response->assertSessionHasErrors('account_number');
    }

    public function test_save_payout_account_ifsc_max_length(): void
    {
        $response = $this->actingAs($this->user)->post('/user/dashboard/wallet/payout-account', [
            'account_holder_name' => 'John Doe', 'bank_name' => 'SBI', 'ifsc_code' => str_repeat('A', 21),
        ]);

        $response->assertSessionHasErrors('ifsc_code');
    }

    public function test_save_payout_account_upi_max_length(): void
    {
        $response = $this->actingAs($this->user)->post('/user/dashboard/wallet/payout-account', [
            'account_holder_name' => 'John Doe', 'upi_id' => str_repeat('A', 256),
        ]);

        $response->assertSessionHasErrors('upi_id');
    }

    public function test_save_payout_account_guest_redirect(): void
    {
        $response = $this->post('/user/dashboard/wallet/payout-account', [
            'account_holder_name' => 'John Doe',
        ]);

        $response->assertRedirect('/login');
    }

    public function test_save_payout_account_only_holder_name_required(): void
    {
        $response = $this->actingAs($this->user)->post('/user/dashboard/wallet/payout-account', [
            'account_holder_name' => 'John Doe',
        ]);

        $response->assertSessionHasNoErrors();
    }

    // ─── Request Payout ───────────────────────────────────────────────────

    public function test_request_payout_guest_redirect(): void
    {
        $response = $this->post('/user/dashboard/wallet/request-payout');

        $response->assertRedirect('/login');
    }

    public function test_request_payout_requires_auth(): void
    {
        $response = $this->actingAs($this->user)->post('/user/dashboard/wallet/request-payout');

        $response->assertSessionHasNoErrors();
    }

    // ─── Edge Cases ───────────────────────────────────────────────────────

    public function test_save_payout_account_special_chars(): void
    {
        $response = $this->actingAs($this->user)->post('/user/dashboard/wallet/payout-account', [
            'account_holder_name' => "O'Brien & Sons (Trust)",
            'bank_name' => "SBI - Main Branch",
            'account_number' => '12345678901',
            'ifsc_code' => 'SBIN0001234',
            'upi_id' => 'john@upi',
        ]);

        $response->assertSessionHasNoErrors();
    }

    public function test_save_payout_account_session_errors(): void
    {
        $response = $this->actingAs($this->user)->post('/user/dashboard/wallet/payout-account', [
            'account_holder_name' => '',
        ]);

        $response->assertSessionHasErrors();
        $response->assertRedirect();
    }

    // ─── Wallet Adjust (Admin) ────────────────────────────────────────────

    public function test_wallet_adjust_guest_redirect(): void
    {
        $response = $this->post('/admin/wallets/1/adjust');

        $response->assertRedirect('/login');
    }
}
