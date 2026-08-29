<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PhoneVerification;
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
                'otp_hash' => Hash::make($otp),
                'expires_at' => now()->addMinutes($this->otpExpiryMinutes),
                'attempts' => 0,
                'verified_at' => null,
            ]
        );

        $this->dispatchOtp($request->phone, $otp);

        if (app()->environment('local')) {
            session(['otp_dev' => $otp]);
        }

        session(['otp_phone' => $request->phone]);

        $message = 'If this number is valid, an OTP has been sent.';

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
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

        if (! $verification || ! $verification->otp_hash) {
            $genericError();
        }

        if (! $verification->expires_at || $verification->expires_at->isPast()) {
            $verification->delete();
            $genericError();
        }

        if ($verification->attempts >= $this->maxOtpAttempts) {
            $verification->delete();
            $genericError();
        }

        if (! Hash::check($request->otp, $verification->otp_hash)) {
            $verification->increment('attempts');
            $genericError();
        }

        $verification->update(['verified_at' => now()]);

        $user = User::firstOrCreate(
            ['phone' => $request->phone],
            [
                'name' => 'User_'.substr($request->phone, -4),
            ]
        );

        $user->ensureDefaultLevel();

        $user->role = 'donor';
        $user->phone_verified_at = $user->phone_verified_at ?? now();
        $user->last_login_at = now();
        $user->save();

        Auth::login($user, true);
        $request->session()->regenerate();
        $request->session()->forget('otp_phone');

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
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

        $verification = PhoneVerification::where('phone', $request->phone)->first();

        if ($verification) {
            $otp = (string) random_int(100000, 999999);

            $verification->update([
                'otp_hash' => Hash::make($otp),
                'expires_at' => now()->addMinutes($this->otpExpiryMinutes),
                'attempts' => 0,
                'verified_at' => null,
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
            Log::info("OTP sent for {$phone}");

            return;
        }

        // Example MSG91 integration — replace with your actual provider call.
        // app(\App\Services\SmsService::class)->send($phone, $message);
    }
}
