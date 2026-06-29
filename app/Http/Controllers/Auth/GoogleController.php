<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\WelcomeGoogleMail;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;

class GoogleController extends Controller
{
    /**
     * Redirect to Google OAuth page.
     */
    public function redirect(): RedirectResponse
    {
        return Socialite::driver('google')
            ->scopes(['openid', 'profile', 'email'])
            ->redirect();
    }

    /**
     * Handle Google OAuth callback.
     */
    public function callback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();

        } catch (InvalidStateException $e) {
            Log::warning('Google OAuth invalid state', [
                'error' => $e->getMessage(),
                'ip'    => request()->ip(),
            ]);
            return redirect()->route('login')
                ->with('error', 'Session expired. Please try again.');

        } catch (\Exception $e) {
            Log::error('Google OAuth failed', [
                'error' => $e->getMessage(),
                'ip'    => request()->ip(),
            ]);
            return redirect()->route('login')
                ->with('error', 'Google login failed. Please try again.');
        }

        // Validate we got an email
        if (empty($googleUser->email)) {
            Log::warning('Google OAuth returned no email', [
                'google_id' => $googleUser->getId(),
            ]);
            return redirect()->route('login')
                ->with('error', 'Could not retrieve email from Google.');
        }

        try {
            $isNewUser = false;

            $user = DB::transaction(function () use ($googleUser, &$isNewUser) {
                $existing = User::where('email', $googleUser->email)->first();

                // User exists — update Google ID if not set
                if ($existing) {
                    if (empty($existing->google_id)) {
                        $existing->update(['google_id' => $googleUser->getId()]);
                    }
                    return $existing;
                }

                // New user — create account
                $isNewUser = true;

                return User::create([
                    'name'              => $googleUser->name ?? 'Google User',
                    'email'             => $googleUser->email,
                    'google_id'         => $googleUser->getId(),
                    'password'          => bcrypt(Str::random(32)),
                    'email_verified_at' => now(),
                ]);
            });

        } catch (\Exception $e) {
            Log::error('Google OAuth DB error', [
                'email' => $googleUser->email,
                'error' => $e->getMessage(),
            ]);
            return redirect()->route('login')
                ->with('error', 'Something went wrong. Please try again.');
        }

        // Send welcome email only to new users
        if ($isNewUser) {
            try {
                Mail::to($user->email)->send(new WelcomeGoogleMail($user));

                Log::info('Welcome email sent', [
                    'user_id' => $user->id,
                    'email'   => $user->email,
                ]);
            } catch (\Exception $e) {
                // Don't block login if email fails
                Log::error('Welcome email failed', [
                    'user_id' => $user->id,
                    'error'   => $e->getMessage(),
                ]);
            }
        }

        Auth::login($user, remember: true);

        request()->session()->regenerate(); // Prevent session fixation

        Log::info('Google OAuth login success', [
            'user_id' => $user->id,
            'email'   => $user->email,
            'ip'      => request()->ip(),
        ]);

        return redirect()->intended(route('dashboard'))
            ->with('success', 'Welcome, ' . $user->name . '!');
    }
}