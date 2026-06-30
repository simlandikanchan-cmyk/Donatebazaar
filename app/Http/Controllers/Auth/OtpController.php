<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
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
     * Creates a placeholder user if phone is new (not yet verified).
     */
    public function sendOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|digits:10',
        ]);

        $otp = (string) random_int(100000, 999999);

        $user = User::firstOrCreate(
            ['phone' => $request->phone],
            [
                'name' => 'User_' . substr($request->phone, -4),
                'role' => 'donor',
            ]
        );

        $user->update([
            'otp_hash'       => Hash::make($otp),
            'otp_expires_at' => now()->addMinutes($this->otpExpiryMinutes),
            'otp_attempts'   => 0,
        ]);

        $this->dispatchOtp($request->phone, $otp);

        session(['otp_phone' => $request->phone]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'redirect' => route('otp.verify'),
            ]);
        }

        return redirect()
            ->route('otp.verify')
            ->with('status', 'OTP sent successfully. Please check your phone.');
    }

    public function verifyPage()
    {
        if (!session('otp_phone')) {
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
            'otp'   => 'required|digits:6',
        ]);

        // Guard: the phone being verified must match the one OTP was sent to in this session.
        if ($request->phone !== session('otp_phone')) {
            throw ValidationException::withMessages([
                'otp' => 'Invalid or expired OTP. Please request a new one.',
            ]);
        }

        $user = User::where('phone', $request->phone)->first();

        $genericError = fn () => throw ValidationException::withMessages([
            'otp' => 'Invalid or expired OTP. Please request a new one.',
        ]);

        if (!$user || !$user->otp_hash) {
            $genericError();
        }

        if (!$user->otp_expires_at || $user->otp_expires_at->isPast()) {
            $user->update(['otp_hash' => null, 'otp_expires_at' => null, 'otp_attempts' => 0]);
            $genericError();
        }

        if ($user->otp_attempts >= $this->maxOtpAttempts) {
            $user->update(['otp_hash' => null, 'otp_expires_at' => null, 'otp_attempts' => 0]);
            $genericError();
        }

        if (!Hash::check($request->otp, $user->otp_hash)) {
            $user->increment('otp_attempts');
            $genericError();
        }

        // Success — clear OTP fields, mark phone verified, log in
        $user->update([
            'otp_hash'          => null,
            'otp_expires_at'    => null,
            'otp_attempts'      => 0,
            'phone_verified_at' => $user->phone_verified_at ?? now(),
            'last_login_at'     => now(),
        ]);

        Auth::login($user, true);
        $request->session()->regenerate();
        $request->session()->forget('otp_phone');

        if ($request->wantsJson()) {
    return response()->json([
        'success'  => true,
        'redirect' => '/user/dashboard',
    ]);
}

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

        $user = User::where('phone', $request->phone)->first();

        if ($user) {
            $otp = (string) random_int(100000, 999999);

            $user->update([
                'otp_hash'       => Hash::make($otp),
                'otp_expires_at' => now()->addMinutes($this->otpExpiryMinutes),
                'otp_attempts'   => 0,
            ]);

            $this->dispatchOtp($request->phone, $otp);
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
            // No SMS gateway connected yet — OTP is written to the log so you can test locally.
            // Check storage/logs/laravel.log after requesting an OTP.
            Log::info("OTP for {$phone}: {$otp}");
            return;
        }

        // Example MSG91 integration — replace with your actual provider call.
        // app(\App\Services\SmsService::class)->send($phone, $message);
    }
}