@extends('layouts.app')

@section('title', 'Terms of Service')
@section('meta_description', 'Terms of service governing the use of DonateBazaar platform.')

@section('content')
<div class="legal-page">
    <div class="legal-hero">
        <div class="legal-hero-bg"></div>
        <div class="legal-hero-inner">
            <h1>Terms of Service</h1>
            <p>Last updated: July 2026</p>
        </div>
    </div>
    <div class="legal-body">
        <h2>1. Acceptance of Terms</h2>
        <p>By accessing or using DonateBazaar, you agree to be bound by these Terms of Service. If you do not agree, please do not use our platform.</p>

        <h2>2. Account Registration</h2>
        <p>You must be at least 18 years old to register. You are responsible for maintaining the confidentiality of your account credentials and for all activities that occur under your account. You agree to provide accurate and complete information during registration.</p>

        <h2>3. Campaigns</h2>
        <p>Campaign organizers agree to use funds raised exclusively for the stated purpose. Misrepresentation or fraudulent use of funds may result in legal action. We reserve the right to suspend or remove campaigns that violate our policies.</p>

        <h2>4. Donations</h2>
        <p>Donations are final and non-refundable except as provided in our <a href="{{ route('refund') }}">Refund & Cancellation Policy</a>. Donors must have legal capacity to make donations and must use lawful payment methods.</p>

        <h2>5. Prohibited Activities</h2>
        <p>You agree not to: use the platform for any unlawful purpose; attempt to interfere with platform security; post false or misleading information; engage in spamming or harassment; or violate any applicable laws or regulations.</p>

        <h2>6. Intellectual Property</h2>
        <p>The DonateBazaar name, logo, platform design, and content are protected by intellectual property laws. You may not reproduce, distribute, or create derivative works without our prior written consent.</p>

        <h2>7. Limitation of Liability</h2>
        <p>DonateBazaar is provided "as is" without warranties of any kind. We are not liable for any damages arising from your use of the platform, including but not limited to direct, indirect, incidental, or consequential damages.</p>

        <h2>8. Termination</h2>
        <p>We reserve the right to suspend or terminate accounts that violate these terms, engage in fraudulent activity, or for any other reason at our discretion. You may also delete your account at any time.</p>

        <h2>9. Governing Law</h2>
        <p>These terms are governed by the laws of India. Any disputes shall be subject to the exclusive jurisdiction of the courts in the relevant jurisdiction.</p>

        <h2>10. Changes to Terms</h2>
        <p>We reserve the right to modify these terms at any time. Users will be notified of material changes via email or platform notice. Continued use after changes constitutes acceptance of the new terms.</p>

        <h2>11. Contact</h2>
        <p>For questions about these terms, please <a href="{{ route('contact') }}">contact us</a>.</p>
    </div>
</div>
@endsection

@push('styles') @vite(['resources/css/legal.css']) @endpush
