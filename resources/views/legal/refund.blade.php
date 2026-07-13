@extends('layouts.app')

@section('title', 'Refund & Cancellation Policy')
@section('meta_description', 'Our refund and cancellation policy for donations made on DonateBazaar.')

@section('content')
<div class="legal-page">
    <div class="legal-hero">
        <div class="legal-hero-bg"></div>
        <div class="legal-hero-inner">
            <h1>Refund &amp; Cancellation Policy</h1>
            <p>Last updated: July 2026</p>
        </div>
    </div>
    <div class="legal-body">
        <h2>1. General Policy</h2>
        <p>Donations made on DonateBazaar are generally non-refundable. When you make a donation to a campaign, the funds are transferred to the campaign organizer to support their cause. Because of this, we cannot provide a refund once a donation has been processed successfully.</p>

        <h2>2. Exceptions</h2>
        <p>Refunds may be considered in the following circumstances:</p>
        <ul style="color:#4b5563;font-size:14px;line-height:1.8;padding-left:20px;">
            <li>The campaign is found to be fraudulent or misleading</li>
            <li>Duplicate or erroneous payments made due to a technical error</li>
            <li>Unauthorized use of your payment method</li>
            <li>The campaign organizer fails to deliver on promised benefits or perks (applicable to reward-based campaigns)</li>
        </ul>

        <h2>3. Refund Request Process</h2>
        <p>To request a refund, please contact our support team at <a href="mailto:support@donatebazaar.com">support@donatebazaar.com</a> within 7 days of the donation. Include your transaction ID, the campaign name, and the reason for your request. We will review your case and respond within 5-7 business days.</p>

        <h2>4. Payment Gateway Fees</h2>
        <p>If a refund is approved, any payment gateway fees (typically 2-3% of the transaction amount) are non-refundable as they are charged by the payment processor and not by DonateBazaar.</p>

        <h2>5. Chargebacks</h2>
        <p>If you believe a transaction was unauthorized, please contact us immediately. Initiating a chargeback with your bank without first contacting us may result in the suspension of your account. We will work with you to resolve any legitimate disputes.</p>

        <h2>6. Cancellation of Donations</h2>
        <p>Once a donation has been processed, it cannot be cancelled. Please review your donation carefully before confirming the transaction. If you have not yet completed your donation (i.e., payment is pending), the transaction will automatically expire after a set period.</p>

        <h2>7. Contact</h2>
        <p>For refund-related inquiries, please email <a href="mailto:support@donatebazaar.com">support@donatebazaar.com</a> or visit our <a href="{{ route('contact') }}">Contact page</a>.</p>
    </div>
</div>
@endsection

@push('styles')
<style>.legal-page{--font:'DM Sans',sans-serif;--mono:'DM Mono',monospace;--accent:#6366f1;}.legal-hero{position:relative;overflow:hidden;background:linear-gradient(160deg,#0d0e1a,#0f172a 50%,#042f2e);padding:80px 24px 64px;text-align:center;}.legal-hero-bg{position:absolute;inset:0;background-image:linear-gradient(rgba(99,102,241,0.04) 1px,transparent 1px),linear-gradient(90deg,rgba(99,102,241,0.04) 1px,transparent 1px);background-size:36px 36px;pointer-events:none;}.legal-hero-inner{position:relative;z-index:1;}.legal-hero-inner h1{font-family:var(--mono);font-size:clamp(26px,4vw,36px);font-weight:500;color:#fff;letter-spacing:-0.03em;}.legal-hero-inner p{font-size:12px;color:rgba(255,255,255,.4);font-family:var(--mono);margin-top:4px;}.legal-body{max-width:760px;margin:0 auto;padding:48px 24px 80px;}.legal-body h2{font-size:17px;font-weight:700;color:#0f1117;margin:32px 0 10px;letter-spacing:-0.01em;}.legal-body p{font-size:14px;color:#4b5563;line-height:1.8;margin-bottom:16px;}.legal-body a{color:var(--accent);text-decoration:none;}.legal-body a:hover{text-decoration:underline;}@media(max-width:520px){.legal-hero{padding:60px 16px 48px;}.legal-body{padding:32px 16px 60px;}}</style>
@endpush
