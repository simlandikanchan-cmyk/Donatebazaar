@extends('layouts.app')

@section('content')
<div style="min-height:100vh;background:#f4f5fb;padding:60px 16px;display:flex;align-items:center;justify-content:center;">
<div style="max-width:480px;width:100%;">

    <div style="background:#fff;border-radius:20px;border:1px solid rgba(0,0,0,0.07);padding:40px 32px;text-align:center;">

        <div style="width:64px;height:64px;border-radius:50%;background:#d1fae5;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="20 6 9 17 4 12"/>
            </svg>
        </div>

        <h1 style="font-size:24px;font-weight:700;color:#0f1117;margin-bottom:8px;">Donation Successful!</h1>
        <p style="font-size:14px;color:#6b7280;margin-bottom:24px;line-height:1.6;">
            Your gift card has been redeemed and your donation has been sent to the campaign. Thank you for giving!
        </p>

        <div style="background:#f4f5fb;border-radius:12px;padding:16px;margin-bottom:24px;">
            <p style="font-size:11px;color:#9ca3af;text-transform:uppercase;letter-spacing:.08em;margin-bottom:6px;">Gift Card Code</p>
            <p style="font-size:16px;font-family:monospace;font-weight:600;color:#0f1117;letter-spacing:.05em;">{{ $giftCard->code }}</p>
        </div>

        <div style="display:flex;justify-content:space-between;margin-bottom:24px;padding:0 4px;">
            <div style="text-align:left;">
                <p style="font-size:11px;color:#9ca3af;margin-bottom:4px;">Amount Donated</p>
                <p style="font-size:18px;font-weight:700;color:#0f1117;">₹{{ number_format($giftCard->amount) }}</p>
            </div>
            <div style="text-align:right;">
                <p style="font-size:11px;color:#9ca3af;margin-bottom:4px;">Redeemed On</p>
                <p style="font-size:14px;color:#0f1117;">{{ $giftCard->redeemed_at?->format('d M Y') }}</p>
            </div>
        </div>

        <a href="{{ route('home') }}" style="display:block;width:100%;padding:14px;background:#6366f1;color:#fff;border-radius:12px;font-size:14px;font-weight:600;text-decoration:none;">
            Back to Home
        </a>
    </div>

</div>
</div>
@endsection