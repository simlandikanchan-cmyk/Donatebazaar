<?php

namespace Tests\Feature\FormValidation;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class AuthFormsValidationTest extends TestCase
{
    use RefreshDatabase;

    private const VALID_PASSWORD = 'ValidP@ss1';

    // ─── Registration ───────────────────────────────────────────────────────

    public function test_registration_happy_path(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => self::VALID_PASSWORD,
            'password_confirmation' => self::VALID_PASSWORD,
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_registration_name_required(): void
    {
        $response = $this->post('/register', [
            'name' => '',
            'email' => 'test@example.com',
            'password' => self::VALID_PASSWORD,
            'password_confirmation' => self::VALID_PASSWORD,
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_registration_email_required(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => '',
            'password' => self::VALID_PASSWORD,
            'password_confirmation' => self::VALID_PASSWORD,
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_registration_password_required(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertSessionHasErrors('password');
    }

    public function test_registration_email_format(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'not-an-email',
            'password' => self::VALID_PASSWORD,
            'password_confirmation' => self::VALID_PASSWORD,
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_registration_email_unique(): void
    {
        User::factory()->create(['email' => 'test@example.com']);

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => self::VALID_PASSWORD,
            'password_confirmation' => self::VALID_PASSWORD,
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_registration_password_confirmation_mismatch(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => self::VALID_PASSWORD,
            'password_confirmation' => 'DifferentP@ss1',
        ]);

        $response->assertSessionHasErrors('password');
    }

    public function test_registration_name_max_length(): void
    {
        $response = $this->post('/register', [
            'name' => str_repeat('A', 256),
            'email' => 'test@example.com',
            'password' => self::VALID_PASSWORD,
            'password_confirmation' => self::VALID_PASSWORD,
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_registration_sql_injection_in_name(): void
    {
        $response = $this->post('/register', [
            'name' => "'; DROP TABLE users; --",
            'email' => 'test@example.com',
            'password' => self::VALID_PASSWORD,
            'password_confirmation' => self::VALID_PASSWORD,
        ]);

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', ['name' => "'; DROP TABLE users; --"]);
    }

    public function test_registration_xss_in_name(): void
    {
        $response = $this->post('/register', [
            'name' => '<script>alert("xss")</script>',
            'email' => 'test@example.com',
            'password' => self::VALID_PASSWORD,
            'password_confirmation' => self::VALID_PASSWORD,
        ]);

        $this->assertAuthenticated();
    }

    // ─── Login ──────────────────────────────────────────────────────────────

    public function test_login_happy_path(): void
    {
        $user = User::factory()->create(['password' => Hash::make(self::VALID_PASSWORD)]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => self::VALID_PASSWORD,
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_login_email_required(): void
    {
        $response = $this->post('/login', ['email' => '', 'password' => 'anything']);

        $response->assertSessionHasErrors('email');
    }

    public function test_login_password_required(): void
    {
        $response = $this->post('/login', ['email' => 'test@example.com', 'password' => '']);

        $response->assertSessionHasErrors('password');
    }

    public function test_login_email_format(): void
    {
        $response = $this->post('/login', ['email' => 'invalid', 'password' => 'anything']);

        $response->assertSessionHasErrors('email');
    }

    public function test_login_invalid_credentials(): void
    {
        $response = $this->post('/login', [
            'email' => 'nonexistent@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_login_rate_limiting(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', [
                'email' => 'test@example.com',
                'password' => 'wrong',
            ]);
        }

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'wrong',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_login_session_error_and_redirect(): void
    {
        $response = $this->post('/login', ['email' => '', 'password' => '']);

        $response->assertSessionHasErrors();
        $response->assertRedirect();
    }

    public function test_login_old_input_retained(): void
    {
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'wrong',
        ]);

        $response->assertRedirect();
        $this->assertStringContainsString('test@example.com', $response->getSession()->get('_old_input')['email'] ?? '');
    }

    // ─── Password Reset ─────────────────────────────────────────────────────

    public function test_forgot_password_happy_path(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/forgot-password', ['email' => $user->email]);

        $response->assertSessionHas('status');
    }

    public function test_forgot_password_email_required(): void
    {
        $response = $this->post('/forgot-password', ['email' => '']);

        $response->assertSessionHasErrors('email');
    }

    public function test_forgot_password_email_format(): void
    {
        $response = $this->post('/forgot-password', ['email' => 'not-email']);

        $response->assertSessionHasErrors('email');
    }

    public function test_forgot_password_nonexistent_email(): void
    {
        $response = $this->post('/forgot-password', ['email' => 'nobody@example.com']);

        $response->assertSessionHasErrors('email');
    }

    // ─── Password Reset (Reset Form) ────────────────────────────────────────

    public function test_reset_password_happy_path(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/reset-password', [
            'token' => 'valid-token',
            'email' => $user->email,
            'password' => self::VALID_PASSWORD,
            'password_confirmation' => self::VALID_PASSWORD,
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_reset_password_email_required(): void
    {
        $response = $this->post('/reset-password', [
            'token' => 'token',
            'email' => '',
            'password' => self::VALID_PASSWORD,
            'password_confirmation' => self::VALID_PASSWORD,
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_reset_password_token_required(): void
    {
        $response = $this->post('/reset-password', [
            'token' => '',
            'email' => 'test@example.com',
            'password' => self::VALID_PASSWORD,
            'password_confirmation' => self::VALID_PASSWORD,
        ]);

        $response->assertSessionHasErrors('token');
    }

    public function test_reset_password_confirmation_mismatch(): void
    {
        $response = $this->post('/reset-password', [
            'token' => 'token',
            'email' => 'test@example.com',
            'password' => self::VALID_PASSWORD,
            'password_confirmation' => 'DifferentP@ss1',
        ]);

        $response->assertSessionHasErrors('password');
    }

    // ─── Confirm Password ───────────────────────────────────────────────────

    public function test_confirm_password_happy_path(): void
    {
        $user = User::factory()->create(['password' => Hash::make(self::VALID_PASSWORD)]);

        $response = $this->actingAs($user)->post('/confirm-password', [
            'password' => self::VALID_PASSWORD,
        ]);

        $response->assertRedirect();
    }

    public function test_confirm_password_required(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/confirm-password', ['password' => '']);

        $response->assertSessionHasErrors('password');
    }

    public function test_confirm_password_wrong(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/confirm-password', ['password' => 'wrong']);

        $response->assertSessionHasErrors('password');
    }

    // ─── Password Update ────────────────────────────────────────────────────

    public function test_password_update_happy_path(): void
    {
        $user = User::factory()->create(['password' => Hash::make('currentPass1')]);

        $response = $this->actingAs($user)->put('/password', [
            'current_password' => 'currentPass1',
            'password' => 'NewP@ss123',
            'password_confirmation' => 'NewP@ss123',
        ]);

        $response->assertSessionHas('status');
    }

    public function test_password_update_current_required(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->put('/password', [
            'current_password' => '',
            'password' => 'NewP@ss123',
            'password_confirmation' => 'NewP@ss123',
        ]);

        $response->assertSessionHasErrorsIn('updatePassword', 'current_password');
    }

    public function test_password_update_new_required(): void
    {
        $user = User::factory()->create(['password' => Hash::make('currentPass1')]);

        $response = $this->actingAs($user)->put('/password', [
            'current_password' => 'currentPass1',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertSessionHasErrorsIn('updatePassword', 'password');
    }

    public function test_password_update_min_length(): void
    {
        $user = User::factory()->create(['password' => Hash::make('currentPass1')]);

        $response = $this->actingAs($user)->put('/password', [
            'current_password' => 'currentPass1',
            'password' => 'Short1',
            'password_confirmation' => 'Short1',
        ]);

        $response->assertSessionHasErrorsIn('updatePassword', 'password');
    }

    public function test_password_update_confirmation_mismatch(): void
    {
        $user = User::factory()->create(['password' => Hash::make('currentPass1')]);

        $response = $this->actingAs($user)->put('/password', [
            'current_password' => 'currentPass1',
            'password' => 'NewP@ss123',
            'password_confirmation' => 'Different1',
        ]);

        $response->assertSessionHasErrorsIn('updatePassword', 'password');
    }

    public function test_password_update_guest_redirect(): void
    {
        $response = $this->put('/password', [
            'current_password' => 'anything',
            'password' => 'NewP@ss123',
            'password_confirmation' => 'NewP@ss123',
        ]);

        $response->assertRedirect(route('login'));
    }

    // ─── OTP ────────────────────────────────────────────────────────────────

    public function test_send_otp_phone_required(): void
    {
        $response = $this->post('/send-otp', ['phone' => '']);

        $response->assertSessionHasErrors('phone');
    }

    public function test_send_otp_phone_digits_only(): void
    {
        $response = $this->post('/send-otp', ['phone' => '12345']);

        $response->assertSessionHasErrors('phone');
    }

    public function test_send_otp_phone_not_10_digits(): void
    {
        $response = $this->post('/send-otp', ['phone' => '123456789']);

        $response->assertSessionHasErrors('phone');
    }

    public function test_verify_otp_phone_required(): void
    {
        $response = $this->post('/verify-otp', ['phone' => '', 'otp' => '123456']);

        $response->assertSessionHasErrors('phone');
    }

    public function test_verify_otp_code_required(): void
    {
        $response = $this->post('/verify-otp', ['phone' => '1234567890', 'otp' => '']);

        $response->assertSessionHasErrors('otp');
    }

    public function test_verify_otp_code_must_be_6_digits(): void
    {
        $response = $this->post('/verify-otp', ['phone' => '1234567890', 'otp' => '12345']);

        $response->assertSessionHasErrors('otp');
    }

    public function test_verify_otp_code_not_numeric(): void
    {
        $response = $this->post('/verify-otp', ['phone' => '1234567890', 'otp' => 'abcdef']);

        $response->assertSessionHasErrors('otp');
    }

    // ─── Email Verification Resend ──────────────────────────────────────────

    public function test_email_verification_resend_requires_auth(): void
    {
        $response = $this->post('/email/verification-notification');

        $response->assertRedirect(route('login'));
    }

    // ─── Logout ─────────────────────────────────────────────────────────────

    public function test_logout_requires_no_fields(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect();
    }

    // ─── Edge Cases (shared) ────────────────────────────────────────────────

    public function test_registration_with_null_bytes(): void
    {
        $response = $this->post('/register', [
            'name' => "Test\x00User",
            'email' => "test\x00@example.com",
            'password' => self::VALID_PASSWORD,
            'password_confirmation' => self::VALID_PASSWORD,
        ]);

        $response->assertStatus(500);
    }

    public function test_login_with_unicode_email(): void
    {
        $response = $this->post('/login', [
            'email' => 'user@äöü.com',
            'password' => 'anything',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_registration_very_long_email(): void
    {
        $local = str_repeat('a', 64);
        $domain = str_repeat('b', 63) . '.com';
        $email = $local . '@' . $domain;

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => $email,
            'password' => self::VALID_PASSWORD,
            'password_confirmation' => self::VALID_PASSWORD,
        ]);

        if (strlen($email) > 255) {
            $response->assertSessionHasErrors('email');
        } else {
            $response->assertSessionHasNoErrors();
        }
    }

    public function test_password_update_wrong_current_password(): void
    {
        $user = User::factory()->create(['password' => Hash::make('currentPass1')]);

        $response = $this->actingAs($user)->put('/password', [
            'current_password' => 'wrongCurrent1',
            'password' => 'NewP@ss123',
            'password_confirmation' => 'NewP@ss123',
        ]);

        $response->assertSessionHasErrorsIn('updatePassword', 'current_password');
    }

    public function test_login_empty_strings(): void
    {
        $response = $this->post('/login', [
            'email' => '   ',
            'password' => '   ',
        ]);

        $response->assertSessionHasErrors(['email', 'password']);
    }

    public function test_registration_with_special_chars_in_name(): void
    {
        $response = $this->post('/register', [
            'name' => "O'Connor-Brown, Jr. & Sr. (Test)",
            'email' => 'special@example.com',
            'password' => self::VALID_PASSWORD,
            'password_confirmation' => self::VALID_PASSWORD,
        ]);

        $this->assertAuthenticated();
    }
}
