@extends('layouts.app')

@section('content')

<style>

    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    .btn-spinner 
    
    {
        width: 16px;
        height: 16px;
        border: 2px solid rgba(255,255,255,0.4);
        border-top-color: #fff;
        border-radius: 50%;
        display: inline-block;
        animation: spin 0.7s linear infinite;
        
    }

</style>

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
                        Null-safe operator (?->) avoids a crash for guest
                        users, where auth()->user() returns null. Without
                        it, ->name on null throws before ?? can fall back.
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

                    <span id="payment-datetime" class="font-medium text-gray-800">
                        Pending
                    </span>
                </div>

                <div class="flex items-center justify-between">
                    <span class="text-gray-500 text-sm">
                        Email
                    </span>

                    <span class="font-medium text-gray-800 truncate max-w-[60%] text-right">
                        {{ auth()->user()?->email ?? $guest_email ?? 'N/A' }}
                    </span>
                </div>

                <div class="flex items-center justify-between">
                    <span class="text-gray-500 text-sm">
                        Phone
                    </span>

                    <span class="font-medium text-gray-800">
                        {{ auth()->user()?->phone ?? $guest_phone ?? 'N/A' }}
                    </span>
                </div>
            </div>

            {{--
                Pay Button — data-payment-status lets the script below
                decide whether to auto-open Razorpay on page load. Without
                this, refreshing the page after a successful payment would
                re-open the modal and risk a duplicate charge.
            --}}
            <button id="rzp-button"
                    class="w-full rounded-2xl text-white font-semibold py-4 transition-all duration-200 shadow-lg hover:scale-[1.01]"
                    style="background: linear-gradient(135deg,#4F46E5 0%,#7C3AED 100%);"
                    data-payment-status="{{ $donation->payment_status ?? 'pending' }}">

                <span class="flex items-center justify-center gap-2">

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
                </span>
            </button>

            {{-- Cancel / Back link --}}
            <a id="cancel-link"
               href="{{ route('campaign.public', ['category' => $campaign->category->slug, 'slug' => $campaign->slug]) }}"
               class="block text-center py-3 rounded-2xl mt-3 text-sm font-medium transition"
               style="border:1px solid #E5E7EB; color:#6B7280;"
               onmouseover="this.style.background='#F9FAFB'"
               onmouseout="this.style.background='transparent'">

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

{{-- Razorpay --}}
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

{{--
    SECURITY NOTE — why donor data is passed this way:

    Blade's {{ }} only HTML-escapes (e.g. < becomes &lt;). It does NOT
    escape characters that break a JS string literal, like " or \.
    If a donor's name ever contains a double-quote, interpolating it
    directly into "{{ $name }}" inside a <script> tag would either break
    the page's JS or, in the worst case, allow injected script to run.

    Js::from() (or @json) properly JSON-encodes the PHP value, escaping
    quotes/backslashes/HTML-sensitive characters, so it's safe to drop
    straight into JS regardless of what the donor's name contains.
--}}
<script>
    const donorName  = {{ Js::from(auth()->user()?->name ?? 'Guest Donor') }};
    const donorEmail = {{ Js::from(auth()->user()?->email ?? '') }};
</script>

<script>

    const payBtn = document.getElementById('rzp-button');
    const cancelLink = document.getElementById('cancel-link');
    const datetimeEl = document.getElementById('payment-datetime');
    const paymentStatus = payBtn.dataset.paymentStatus;

    const campaignUrl = "{{ route('campaign.public', ['category' => $campaign->category->slug, 'slug' => $campaign->slug]) }}";

    const idleButtonHtml = `
        <span class="flex items-center justify-center gap-2">
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
        </span>
    `;

    function resetButton() {
        payBtn.disabled = false;
        payBtn.style.background = "linear-gradient(135deg,#4F46E5 0%,#7C3AED 100%)";
        payBtn.innerHTML = idleButtonHtml;
    }

    var options = {

        key: "{{ $razorpay_key }}",
        amount: "{{ (int) round($amount * 100) }}",
        currency: "INR",

        name: "DonateBazaar",
        description: "{{ $campaign->title }}",

        image: "{{ asset('logo.png') }}",

        order_id: "{{ $order_id }}",

        prefill: {
            // donorName / donorEmail come from Js::from() above —
            // already safely JSON-encoded, not raw Blade interpolation.
            name: donorName,
            email: donorEmail
        },

        notes: {
            campaign_id: "{{ $campaign->id }}",
            donation_id: "{{ $donation_id }}"
        },

        theme: {
            color: "#4F46E5"
        },

        modal: {
            ondismiss: function () {
                resetButton();
            }
        },

        handler: function (response) {

            payBtn.disabled = true;
            cancelLink.style.visibility = "hidden";

            payBtn.innerHTML = `
                <span class="flex items-center justify-center gap-2">
                    <span class="btn-spinner"></span>
                    Verifying Payment...
                </span>
            `;

            // Guarantee the "Verifying" state is visible for at least 1s,
            // even if the server responds instantly, so the user actually
            // registers what's happening instead of seeing a flash.
            const minDelay = new Promise(resolve => setTimeout(resolve, 1000));

            const verifyRequest = fetch("{{ route('payment.verify') }}", {

                method: "POST",

                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },

                body: JSON.stringify({

                    razorpay_payment_id: response.razorpay_payment_id,
                    razorpay_order_id: response.razorpay_order_id,
                    razorpay_signature: response.razorpay_signature,
                    donation_id: "{{ $donation_id }}"
                })
            })
            .then(res => res.json());

            Promise.all([verifyRequest, minDelay])

            .then(([data]) => {

                if (data.success) {

                    const completedAt = data.paid_at
                        ? new Date(data.paid_at)
                        : new Date();

                    datetimeEl.textContent = completedAt.toLocaleString('en-IN', {
                        day: '2-digit', month: 'short', year: 'numeric',
                        hour: '2-digit', minute: '2-digit', hour12: true
                    });

                    payBtn.innerHTML = `
                        <span class="flex items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 width="18" height="18"
                                 viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor"
                                 stroke-width="2.5">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                            Payment Successful
                        </span>
                    `;

                    payBtn.style.background = "#059669";

                    // No auto-redirect. The user stays on this confirmation
                    // state and decides themselves when to leave, instead of
                    // being bounced back to the campaign page mid-read.
                    cancelLink.style.visibility = "visible";
                    cancelLink.textContent = "Back to Campaign";
                    cancelLink.href = campaignUrl;
                    cancelLink.style.borderColor = "#A7F3D0";
                    cancelLink.style.color = "#047857";
                    cancelLink.style.fontWeight = "600";

                } else {

                    resetButton();
                    cancelLink.style.visibility = "visible";

                    alert(data.message || 'Payment verification failed.');
                }
            })

            .catch(() => {

                resetButton();
                cancelLink.style.visibility = "visible";

                alert('Something went wrong. Please try again.');
            });
        }
    };

    const rzp = new Razorpay(options);

    payBtn.addEventListener('click', function (e) {

        e.preventDefault();

        payBtn.disabled = true;

        payBtn.innerHTML = `
            <span class="flex items-center justify-center gap-2">
                <span class="btn-spinner"></span>
                Opening Payment Gateway...
            </span>
        `;

        rzp.open();

        
    });


    // Auto-open on page load ONLY when the payment is still pending.
    // Without this check, refreshing the page after a completed payment
    // would re-open the modal and could trigger a duplicate payment.
    window.addEventListener('load', function () {
        if (paymentStatus === 'pending') {
            rzp.open();
        }
    });

</script>

@endsection