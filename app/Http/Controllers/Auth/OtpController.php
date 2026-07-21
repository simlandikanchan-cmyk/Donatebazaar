<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
<<<<<<< HEAD
=======
use App\Models\PhoneVerification;
>>>>>>> origin/master
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class OtpController extends Controller
{
    protected int $otpExpiryMinutes = 5;

    protected int $maxOtpAttempts = 5;

    public function login()
    {
        return view('auth.phone');
    }

    /**
     * Send OTP to phone number.
     * Stores the OTP in the phone_verifications table — does NOT eagerly
     * create a User record, preventing phone-number enumeration.
     */
    public function sendOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|digits:10',
        ]);

        $otp = (string) random_int(100000, 999999);

        PhoneVerification::updateOrCreate(
            ['phone' => $request->phone],
            [
<<<<<<< HEAD
                'name' => 'User_'.substr($request->phone, -4),
                'role' => 'donor',
            ]
        );

        $user->update([
            'otp_hash' => Hash::make($otp),
            'otp_expires_at' => now()->addMinutes($this->otpExpiryMinutes),
            'otp_attempts' => 0,
        ]);

=======
                'otp_hash'   => Hash::make($otp),
                'expires_at' => now()->addMinutes($this->otpExpiryMinutes),
                'attempts'   => 0,
                'verified_at' => null,
            ]
        );

>>>>>>> origin/master
        $this->dispatchOtp($request->phone, $otp);

        if (app()->environment('local')) {
            session(['otp_dev' => $otp]);
        }

        session(['otp_phone' => $request->phone]);

        $message = 'If this number is valid, an OTP has been sent.';

        if ($request->wantsJson()) {
            return response()->json([
                'success'  => true,
                'message'  => $message,
                'redirect' => route('otp.verify'),
            ]);
        }

        return redirect()
            ->route('otp.verify')
            ->with('status', $message);
    }

    public function verifyPage()
    {
        if (! session('otp_phone')) {
            return redirect()->route('otp.login')->with('error', 'Please enter your phone number first.');
        }

        return view('auth.verify', ['phone' => session('otp_phone')]);
    }

    /**
     * Verify the submitted OTP and log the user in.
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|digits:10',
            'otp' => 'required|digits:6',
        ]);

        if ($request->phone !== session('otp_phone')) {
            throw ValidationException::withMessages([
                'otp' => 'Invalid or expired OTP. Please request a new one.',
            ]);
        }

        $verification = PhoneVerification::where('phone', $request->phone)->first();

        $genericError = fn () => throw ValidationException::withMessages([
            'otp' => 'Invalid or expired OTP. Please request a new one.',
        ]);

<<<<<<< HEAD
        if (! $user || ! $user->otp_hash) {
            $genericError();
        }

        if (! $user->otp_expires_at || $user->otp_expires_at->isPast()) {
            $user->update(['otp_hash' => null, 'otp_expires_at' => null, 'otp_attempts' => 0]);
=======
        if (!$verification || !$verification->otp_hash) {
            $genericError();
        }

        if (!$verification->expires_at || $verification->expires_at->isPast()) {
            $verification->delete();
>>>>>>> origin/master
            $genericError();
        }

        if ($verification->attempts >= $this->maxOtpAttempts) {
            $verification->delete();
            $genericError();
        }

<<<<<<< HEAD
        if (! Hash::check($request->otp, $user->otp_hash)) {
            $user->increment('otp_attempts');
=======
        if (!Hash::check($request->otp, $verification->otp_hash)) {
            $verification->increment('attempts');
>>>>>>> origin/master
            $genericError();
        }

        $verification->update(['verified_at' => now()]);

        $user = User::firstOrCreate(
            ['phone' => $request->phone],
            [
                'name' => 'User_' . substr($request->phone, -4),
                'role' => 'donor',
            ]
        );

        $user->update([
<<<<<<< HEAD
            'otp_hash' => null,
            'otp_expires_at' => null,
            'otp_attempts' => 0,
=======
>>>>>>> origin/master
            'phone_verified_at' => $user->phone_verified_at ?? now(),
            'last_login_at' => now(),
        ]);

        Auth::login($user, true);
        $request->session()->regenerate();
        $request->session()->forget('otp_phone');

        if ($request->wantsJson()) {
            return response()->json([
<<<<<<< HEAD
                'success' => true,
                'redirect' => '/user/dashboard',
            ]);
        }

        return redirect('/user/dashboard');
=======
                'success'  => true,
                'redirect' => '/user/dashboard',
            ]);
        }
>>>>>>> origin/master

        return redirect('/user/dashboard');
    }

    /**
     * Resend OTP — always responds the same way whether or not the phone exists,
     * to avoid leaking which numbers are registered.
     */
    public function resend(Request $request)
    {
        $request->validate([
            'phone' => 'required|digits:10',
        ]);

        $verification = PhoneVerification::where('phone', $request->phone)->first();

        if ($verification) {
            $otp = (string) random_int(100000, 999999);

<<<<<<< HEAD
            $user->update([
                'otp_hash' => Hash::make($otp),
                'otp_expires_at' => now()->addMinutes($this->otpExpiryMinutes),
                'otp_attempts' => 0,
=======
            $verification->update([
                'otp_hash'    => Hash::make($otp),
                'expires_at'  => now()->addMinutes($this->otpExpiryMinutes),
                'attempts'    => 0,
                'verified_at' => null,
>>>>>>> origin/master
            ]);

            $this->dispatchOtp($request->phone, $otp);

            if (app()->environment('local')) {
                session(['otp_dev' => $otp]);
            }
        }

        $message = 'If this number is registered, a new OTP has been sent.';

        if ($request->wantsJson()) {
            return response()->json(['status' => $message]);
        }

        return back()->with('status', $message);
    }

    /**
     * Central place to send the OTP via SMS gateway.
     * Swap the body of this method for your chosen provider (MSG91 / Twilio / etc).
     */
    protected function dispatchOtp(string $phone, string $otp): void
    {
        $message = "Your DonateBazaar OTP is {$otp}. It is valid for {$this->otpExpiryMinutes} minutes. Do not share this with anyone.";

        if (app()->environment('local')) {
<<<<<<< HEAD
            Log::info("OTP dispatched to {$phone}.");

=======
            // No SMS gateway connected yet — OTP is written to the log so you can test locally.
            // Check storage/logs/laravel.log after requesting an OTP.
            Log::info("OTP sent for {$phone}");
>>>>>>> origin/master
            return;
        }

        // Example MSG91 integration — replace with your actual provider call.
        // app(\App\Services\SmsService::class)->send($phone, $message);
    }
}
