@extends('layouts.app')

@section('title', 'Privacy Policy')
@section('meta_description', 'Our privacy policy outlines how we collect, use, and protect your personal information.')

@section('content')
<div class="legal-page">
    <div class="legal-hero">
        <div class="legal-hero-bg"></div>
        <div class="legal-hero-inner">
            <h1>Privacy Policy</h1>
            <p>Last updated: July 2026</p>
        </div>
    </div>
    <div class="legal-body">
        <h2>1. Information We Collect</h2>
        <p>We collect information you provide directly to us, including your name, email address, phone number, and payment information when you create an account, start a campaign, or make a donation. We also automatically collect certain technical information such as your IP address, browser type, and device information when you use our platform.</p>

        <h2>2. How We Use Your Information</h2>
        <p>We use your information to process donations, manage campaigns, send transaction receipts, provide customer support, and improve our services. With your consent, we may send you newsletters and updates about campaigns and platform features.</p>

        <h2>3. Information Sharing</h2>
        <p>We do not sell your personal information to third parties. We may share your information with payment processors (such as Razorpay) to process transactions, with campaign organizers when you make a donation to their campaign, and as required by law or to protect our legal rights.</p>

        <h2>4. Data Security</h2>
        <p>We implement industry-standard security measures including SSL encryption, secure data storage, and regular security audits to protect your personal information. However, no method of electronic storage is 100% secure, and we cannot guarantee absolute security.</p>

        <h2>5. Your Rights</h2>
        <p>You have the right to access, correct, or delete your personal information at any time through your account settings. You may also opt out of marketing communications by clicking the unsubscribe link in any email or contacting our support team.</p>

        <h2>6. Cookies</h2>
        <p>We use cookies and similar tracking technologies to enhance your browsing experience, analyze site traffic, and understand where our audience comes from. You can control cookie preferences through your browser settings. See our <a href="{{ route('cookies') }}">Cookie Policy</a> for more details.</p>

        <h2>7. Third-Party Services</h2>
        <p>Our platform may contain links to third-party websites and services. We are not responsible for the privacy practices of these third parties. We encourage you to review their privacy policies before providing any personal information.</p>

        <h2>8. Children's Privacy</h2>
        <p>Our services are not directed to individuals under the age of 18. We do not knowingly collect personal information from minors. If we become aware that a minor has provided us with personal information, we will take steps to delete it promptly.</p>

        <h2>9. Changes to This Policy</h2>
        <p>We may update this Privacy Policy from time to time. We will notify you of any material changes by posting the new policy on this page and updating the "Last updated" date. Your continued use of the platform after changes constitutes acceptance of the updated policy.</p>

        <h2>10. Contact Us</h2>
        <p>If you have any questions about this Privacy Policy, please contact us through our <a href="{{ route('contact') }}">Contact page</a> or email us at privacy@donatebazaar.com.</p>
    </div>
</div>
@endsection

@push('styles')
<style>
.legal-page{--font:'DM Sans',sans-serif;--mono:'DM Mono',monospace;--accent:#6366f1;}
.legal-hero{position:relative;overflow:hidden;background:linear-gradient(160deg,#0d0e1a,#0f172a 50%,#042f2e);padding:80px 24px 64px;text-align:center;}
.legal-hero-bg{position:absolute;inset:0;background-image:linear-gradient(rgba(99,102,241,0.04) 1px,transparent 1px),linear-gradient(90deg,rgba(99,102,241,0.04) 1px,transparent 1px);background-size:36px 36px;pointer-events:none;}
.legal-hero-inner{position:relative;z-index:1;}
.legal-hero-inner h1{font-family:var(--mono);font-size:clamp(26px,4vw,36px);font-weight:500;color:#fff;letter-spacing:-0.03em;}
.legal-hero-inner p{font-size:12px;color:rgba(255,255,255,.4);font-family:var(--mono);margin-top:4px;}
.legal-body{max-width:760px;margin:0 auto;padding:48px 24px 80px;}
.legal-body h2{font-size:17px;font-weight:700;color:#0f1117;margin:32px 0 10px;letter-spacing:-0.01em;}
.legal-body p{font-size:14px;color:#4b5563;line-height:1.8;margin-bottom:16px;}
.legal-body a{color:var(--accent);text-decoration:none;}
.legal-body a:hover{text-decoration:underline;}
@media(max-width:520px){.legal-hero{padding:60px 16px 48px;}.legal-body{padding:32px 16px 60px;}}
</style>
@endpush
