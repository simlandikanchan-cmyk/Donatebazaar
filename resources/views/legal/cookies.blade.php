@extends('layouts.app')

@section('title', 'Cookie Policy')
@section('meta_description', 'How DonateBazaar uses cookies and similar tracking technologies.')

@section('content')
<div class="legal-page">
    <div class="legal-hero">
        <div class="legal-hero-bg"></div>
        <div class="legal-hero-inner">
            <h1>Cookie Policy</h1>
            <p>Last updated: July 2026</p>
        </div>
    </div>
    <div class="legal-body">
        <h2>1. What Are Cookies</h2>
        <p>Cookies are small text files stored on your device by your web browser. They help websites remember your preferences, understand how you use the site, and improve your overall experience. Cookies can be "session" cookies (deleted when you close your browser) or "persistent" cookies (remain until they expire or are deleted).</p>

        <h2>2. How We Use Cookies</h2>
        <p>We use cookies for the following purposes:</p>
        <ul style="color:#4b5563;font-size:14px;line-height:1.8;padding-left:20px;">
            <li><strong>Essential Cookies:</strong> Required for the platform to function properly, including authentication, session management, and security. These cannot be disabled.</li>
            <li><strong>Analytics Cookies:</strong> Help us understand how visitors interact with our platform so we can improve it. We use services like Google Analytics.</li>
            <li><strong>Functional Cookies:</strong> Remember your preferences and settings for a personalized experience.</li>
            <li><strong>Payment Cookies:</strong> Used by our payment gateway (Razorpay) to process transactions securely.</li>
        </ul>

        <h2>3. Third-Party Cookies</h2>
        <p>Some cookies are placed by third-party services we use, such as Google Analytics for traffic analysis and Razorpay for payment processing. These third parties have their own cookie policies. We do not control their cookie practices.</p>

        <h2>4. Your Cookie Choices</h2>
        <p>You can control and manage cookies in your browser settings. Most browsers allow you to block or delete cookies. However, please note that disabling essential cookies may affect the functionality of our platform. Here's how to manage cookies in popular browsers:</p>
        <ul style="color:#4b5563;font-size:14px;line-height:1.8;padding-left:20px;">
            <li>Google Chrome: Settings → Privacy and Security → Cookies and other site data</li>
            <li>Mozilla Firefox: Options → Privacy &amp; Security → Cookies and Site Data</li>
            <li>Safari: Preferences → Privacy → Cookies and website data</li>
            <li>Microsoft Edge: Settings → Cookies and site permissions → Cookies and site data</li>
        </ul>

        <h2>5. Changes to This Policy</h2>
        <p>We may update this Cookie Policy from time to time. Changes will be posted on this page with an updated revision date. We encourage you to review this policy periodically.</p>

        <h2>6. Contact</h2>
        <p>If you have questions about our use of cookies, please <a href="{{ route('contact') }}">contact us</a>.</p>
    </div>
</div>
@endsection

@push('styles') @vite(['resources/css/legal.css']) @endpush
