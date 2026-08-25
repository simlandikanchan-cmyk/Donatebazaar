<!DOCTYPE html>
<html>
<head>
    <title>Verify OTP</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @vite(['resources/css/public/auth-verify.css'])
</head>
<body>

<h2>Verify OTP</h2>

<p>Phone: {{ $phone ?? session('otp_phone') }}</p>

@if (app()->environment('local') && session('otp_dev'))
    <p style="color:#e67e22; font-weight:bold; border:1px dashed #e67e22; padding:8px; display:inline-block;">
        DEV OTP: {{ session('otp_dev') }}
    </p>
@endif

<input type="text" id="otp" placeholder="Enter OTP" maxlength="6" inputmode="numeric">
<br>

<x-button variant="primary" type="button" id="verifyBtn" data-action="verify-otp">Verify OTP</x-button>

<br><br>

<x-button variant="secondary" type="button" id="resendBtn" data-action="resend-otp">
    Resend OTP (30s)
</x-button>

<div id="message"></div>

<script type="application/json" id="authVerifyData">
@php
    $authVerifyData = [
        'phone' => $phone ?? session('otp_phone'),
        'verifyUrl' => route('otp.verify.post'),
        'resendUrl' => route('otp.resend'),
    ];
@endphp
@json($authVerifyData)
</script>

@vite(['resources/js/public/auth-verify.js'])

</body>
</html>