<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class OtpController extends Controller
{
    //  Login Page
    public function login()
    {
        return view('auth.phone');
    }

    //  Send OTP
    public function sendOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|digits:10'
        ]);

        $otp = rand(100000, 999999);

        // $user = User::firstOrCreate(
        //     ['phone' => $request->phone],
        //     ['role' => 'donor']
        // );

$user = User::firstOrCreate(
    ['phone' => $request->phone],
    [
        'name' => 'User_' . substr($request->phone, -4),
        'role' => 'donor'
    ]
);


        $user->update([
            'otp' => Hash::make($otp), // 
            'otp_expires_at' => now()->addMinutes(5)
        ]);

        // ⚠️ TEMP (remove in production)
        return redirect('/verify-otp')
            ->with('phone', $request->phone)
            ->with('otp', $otp);
    }

    // 🔹 Verify Page
    public function verifyPage()
    {
        return view('auth.verify');
    }

    // 🔹 Verify OTP
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required',
            'otp' => 'required'
        ]);

        $user = User::where('phone', $request->phone)->first();

        if (!$user) {
            return back()->with('error', 'User not found');
        }

        // Check OTP (hashed)
        if (!Hash::check($request->otp, $user->otp)) {
            return back()->with('error', 'Invalid OTP');
        }

        //  Expiry check
        if ($user->otp_expires_at < now()) {
            return back()->with('error', 'OTP expired');
        }

        //  Clear OTP + update login
        $user->update([
            'otp' => null,
            'otp_expires_at' => null,
            'phone_verified_at' => now(),
            'last_login_at' => now()
        ]);

        Auth::login($user);

        return redirect('/dashboard');
    }

    //  Resend OTP
    public function resend(Request $request)
    {
        $request->validate([
            'phone' => 'required'
        ]);

        $otp = rand(100000, 999999);

        $user = User::where('phone', $request->phone)->first();

        if (!$user) {
            return back()->with('error', 'User not found');
        }

        $user->update([
            'otp' => Hash::make($otp),
            'otp_expires_at' => now()->addMinutes(5)
        ]);

        return back()->with('otp', $otp);
    }
}