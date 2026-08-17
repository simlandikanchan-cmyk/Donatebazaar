@extends('layouts.app')

@section('content')

@push('styles') @vite(['resources/css/public/payment.css']) @endpush

<div class="min-h-screen flex items-center justify-center px-4 py-10"
     style="background: linear-gradient(180deg,#F8FAFC 0%,#EEF2FF 100%);">

    {{-- Main Card --}}
    <div class="w-full max-w-md overflow-hidden shadow-2xl"
         style="background:#fff; border-radius:28px; border:1px solid #E5E7EB;">

        {{-- Top Banner --}}
        <div class="relative px-6 pt-6 pb-5"
             style="background: linear-gradient(135deg,#4F46E5 0%,#7C3AED 100%);">

            <div class="flex items-center justify-between mb-5">

                {{-- Brand --}}
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-2xl bg-white/15 backdrop-blur flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             width="20" height="20"
                             viewBox="0 0 24 24"
                             fill="none"
                             stroke="white"
                             stroke-width="2">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                        </svg>
                    </div>

                    <div>
                        <h2 class="text-white font-semibold text-lg leading-none">
                            DonateBazaar
                        </h2>
                        <p class="text-indigo-100 text-xs mt-1">
                            Trusted donation platform
                        </p>
                    </div>
                </div>

                {{-- Secure Badge --}}
                <div class="px-3 py-1.5 rounded-full text-xs font-medium flex items-center gap-1"
                     style="background: rgba(255,255,255,0.15); color:#fff;">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         width="13"
                         height="13"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2">
                        <rect x="3" y="11" width="18" height="11" rx="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>

                    Secure
                </div>
            </div>

            {{-- Campaign Info --}}
            <div class="bg-white/10 backdrop-blur rounded-2xl p-4 border border-white/10">

                <p class="text-[11px] uppercase tracking-[2px] text-indigo-100 mb-2">
                    Campaign
                </p>

                <h1 class="text-white text-lg font-semibold leading-snug">
                    {{ $campaign->title }}
                </h1>

                <div class="flex items-center gap-2 mt-3 text-indigo-100 text-sm">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         width="14"
                         height="14"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                        <circle cx="12" cy="10" r="3"/>
                    </svg>

                    <span>{{ $campaign->location ?? 'India' }}</span>

                    @if($campaign->is_urgent)
                    <span class="ml-auto px-2 py-1 rounded-full text-[10px] font-semibold"
                          style="background:#FEF3C7; color:#B45309;">
                        Ending Soon
                    </span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Card Body --}}
        <div class="p-6">

            {{-- Donation Amount --}}
            <div class="rounded-3xl text-center py-6 px-5 mb-6"
                 style="background: linear-gradient(180deg,#EEF2FF 0%,#F8FAFC 100%); border:1px solid #E0E7FF;">

                <p class="text-xs uppercase tracking-[2px] text-gray-400 mb-2">
                    Donation Amount
                </p>

                <h2 class="text-5xl font-bold text-gray-900 tracking-tight">
                    ₹{{ number_format($amount, 2) }}
                </h2>

                <p class="text-sm text-gray-500 mt-2">
                    One-time secure contribution
                </p>
            </div>

            {{-- Summary --}}
            <div class="space-y-4 mb-6">

                <div class="flex items-center justify-between">
                    <span class="text-gray-500 text-sm">
                        Donor
                    </span>
                    {{--
                        ?-> null-safe operator — if auth()->user() is null
                        (guest), plain -> would throw. ?-> returns null safely
                        and ?? falls back to 'Guest Donor'.
                    --}}
                    <span class="font-semibold text-gray-800">
                        {{ auth()->user()?->name ?? 'Guest Donor' }}
                    </span>
                </div>

                <div class="flex items-center justify-between">
                    <span class="text-gray-500 text-sm">
                        Payment Method
                    </span>

                    <div class="flex items-center gap-2 text-gray-800 font-medium">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             width="15"
                             height="15"
                             viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2">
                            <rect x="1" y="4" width="22" height="16" rx="2"/>
                            <line x1="1" y1="10" x2="23" y2="10"/>
                        </svg>

                        Razorpay
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <span class="text-gray-500 text-sm">
                        Currency
                    </span>

                    <span class="font-medium text-gray-800">
                        INR (₹)
                    </span>
                </div>

                @if($donation->discount_amount > 0)
                <div class="flex items-center justify-between">
                    <span class="text-gray-500 text-sm">
                        Original Amount
                    </span>

                    <span class="font-medium text-gray-800">
                        ₹{{ number_format($donation->original_amount, 2) }}
                    </span>
                </div>

                <div class="flex items-center justify-between">
                    <span class="text-gray-500 text-sm">
                        Coupon ({{ $donation->coupon_code }})
                    </span>

                    <span class="font-semibold text-green-600">
                        − ₹{{ number_format($donation->discount_amount, 2) }}
                    </span>
                </div>
                @endif

                <div class="flex items-center justify-between">
                    <span class="text-gray-500 text-sm">
                        Receipt No.
                    </span>

                    <span class="font-medium text-gray-800">
                        DN-{{ str_pad($donation_id, 6, '0', STR_PAD_LEFT) }}
                    </span>
                </div>

                <div class="flex items-center justify-between">
                    <span class="text-gray-500 text-sm">
                        Date &amp; Time
                    </span>

                    {{--
                        Starts as "Pending" — JS fills this in after
                        successful payment verification. No server timestamp
                        leaks into the initial page source.
                    --}}
                    <span id="payment-datetime" class="font-medium text-gray-800">
                        Pending
                    </span>
                </div>

                <div class="flex items-center justify-between">
                    <span class="text-gray-500 text-sm">
                        Email
                    </span>

                    <span class="font-medium text-gray-800 truncate max-w-[60%] text-right">
                        {{ auth()->user()?->email ?? 'N/A' }}
                    </span>
                </div>

                <div class="flex items-center justify-between">
                    <span class="text-gray-500 text-sm">
                        Phone
                    </span>

                    <span class="font-medium text-gray-800">
                        {{ auth()->user()?->phone ?? 'N/A' }}
                    </span>
                </div>
            </div>

            {{--
                data-payment-status — read by JS to decide whether to
                auto-open Razorpay on page load. Prevents the modal from
                re-opening when the user refreshes after a completed payment.
            --}}
            <x-button id="rzp-button" type="button" variant="primary" fullWidth data-payment-status="{{ $donation->payment_status ?? 'pending' }}">
                <svg xmlns="http://www.w3.org/2000/svg"
                     width="17"
                     height="17"
                     viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="2">
                    <rect x="3" y="11" width="18" height="11" rx="2"/>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
                Pay ₹{{ number_format($amount, 2) }} Securely
            </x-button>

            {{-- Cancel / Back link --}}
            <a id="cancel-link"
               href="{{ route('campaign.public', ['category' => $campaign->category->slug, 'slug' => $campaign->slug]) }}"
               class="block text-center py-3 rounded-2xl mt-3 text-sm font-medium transition"
               style="border:1px solid #E5E7EB; color:#6B7280;"
               data-action="cancel-link-hover">

                Cancel Donation
            </a>

            {{-- Trust Badges --}}
            <div class="grid grid-cols-3 gap-3 mt-6">

                <div class="rounded-2xl border border-gray-100 py-3 text-center">
                    <div class="flex justify-center mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             width="16"
                             height="16"
                             viewBox="0 0 24 24"
                             fill="none"
                             stroke="#4F46E5"
                             stroke-width="2">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        </svg>
                    </div>

                    <p class="text-[11px] text-gray-500">
                        Verified
                    </p>
                </div>

                <div class="rounded-2xl border border-gray-100 py-3 text-center">
                    <div class="flex justify-center mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             width="16"
                             height="16"
                             viewBox="0 0 24 24"
                             fill="none"
                             stroke="#4F46E5"
                             stroke-width="2">
                            <circle cx="12" cy="8" r="6"/>
                            <path d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11"/>
                        </svg>
                    </div>

                    <p class="text-[11px] text-gray-500">
                        RBI Safe
                    </p>
                </div>

                <div class="rounded-2xl border border-gray-100 py-3 text-center">
                    <div class="flex justify-center mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             width="16"
                             height="16"
                             viewBox="0 0 24 24"
                             fill="none"
                             stroke="#4F46E5"
                             stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <polyline points="14 2 14 8 20 8"/>
                        </svg>
                    </div>

                    <p class="text-[11px] text-gray-500">
                        80G Eligible
                    </p>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Razorpay SDK --}}
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script type="application/json" id="paymentData">
@php
    $paymentData = [
        'campaignUrl' => route('campaign.public', ['category' => $campaign->category->slug, 'slug' => $campaign->slug]),
        'amountLabel' => number_format($amount, 2),
        'key' => $razorpay_key,
        'amount' => (string) (int) round($amount * 100),
        'description' => $campaign->title,
        'image' => asset('logo.png'),
        'orderId' => $order_id,
        'donorName' => auth()->user()?->name ?? 'Guest Donor',
        'donorEmail' => auth()->user()?->email ?? '',
        'campaignId' => (string) $campaign->id,
        'donationId' => (string) $donation_id,
        'verifyUrl' => route('payment.verify'),
        'csrfToken' => csrf_token(),
    ];
@endphp
@json($paymentData)
</script>

@push('scripts')
@vite('resources/js/public/payment.js')
@endpush

@endsection