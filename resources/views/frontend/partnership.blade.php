@extends('layouts.app')

@section('content')

@push('styles') @vite(['resources/css/public/partnership.css']) @endpush

{{-- ══ TOAST CONTAINER ══ --}}
<div class="toast-stack" id="toastStack" role="status" aria-live="polite" aria-atomic="false"></div>

<div class="page-shell">
<div class="pg-grid">

{{-- ══ LEFT ══ --}}
<aside class="pg-left">DonateBazaar
    <div class="brand">
        <div class="brand-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        </div>
        <span class="brand-name">DonateBazaar</span>
    </div>
    <div class="trust-eyebrow"><span></span> Partnership Program</div>
    <h2 class="trust-headline">Make an impact<br>that truly matters</h2>
    <p class="trust-body">Join hundreds of organisations already creating measurable social change. Built on transparency, accountability, and shared values.</p>
    <div class="trust-signals">
        <div class="ts-item">
            <div class="ts-icon ts-icon-purple"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div>
            <div class="ts-text"><strong>100% Verified Partners</strong><span>Every partner is KYC-verified and audited before funds are released.</span></div>
        </div>
        <div class="ts-item">
            <div class="ts-icon ts-icon-green"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg></div>
            <div class="ts-text"><strong>Full Transparency Reports</strong><span>Quarterly impact reports delivered to all partners without exception.</span></div>
        </div>
        <div class="ts-item">
            <div class="ts-icon ts-icon-gold"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div>
            <div class="ts-text"><strong>Dedicated Support</strong><span>A relationship manager responds within 48 hours of your inquiry.</span></div>
        </div>
    </div>
    <div class="stats-row">
        <div class="st-cell"><div class="st-val">240+</div><div class="st-key">Partners</div></div>
        <div class="st-cell"><div class="st-val">₹4.2Cr</div><div class="st-key">Raised</div></div>
        <div class="st-cell"><div class="st-val">18</div><div class="st-key">States</div></div>
    </div>
    <div class="partner-wrap">
        <span class="partner-label">Trusted by</span>
        <div class="partner-chips">
            <span class="partner-chip">Tata Trusts</span>
            <span class="partner-chip">Give India</span>
            <span class="partner-chip">HDFC CSR</span>
            <span class="partner-chip">Infosys Foundation</span>
        </div>
    </div>
</aside>

{{-- ══ CENTER ══ --}}
<main class="pg-center">

    <div class="page-eyebrow"><span></span> Apply Now</div>
    <h1 class="page-title">Apply for Partnership</h1>
    <p class="page-subtitle">Fill in your details — our team responds within 2 business days.</p>

    @if(isset($partnership) && $partnership)

        {{-- ── REVIEW STATE ── --}}
        <div class="stepper-wrap">
            <div class="stepper-item s-done">
                <div class="stepper-dot s-done"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:14px;height:14px"><path d="M20 6L9 17l-5-5"/></svg></div>
                <span class="stepper-label s-done">Your Info</span>
            </div>
            <div class="stepper-item s-done">
                <div class="stepper-dot s-done"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:14px;height:14px"><path d="M20 6L9 17l-5-5"/></svg></div>
                <span class="stepper-label s-done">Organisation</span>
            </div>
            <div class="stepper-item">
                <div class="stepper-dot {{ $partnership->status === 'approved' ? 's-done' : 's-active' }}">
                    @if($partnership->status === 'approved')
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:14px;height:14px"><path d="M20 6L9 17l-5-5"/></svg>
                    @else 3 @endif
                </div>
                <span class="stepper-label s-active">Review</span>
            </div>
        </div>

        <div class="review-card">
            <div class="review-status-bar">
                <div class="review-status-icon icon-{{ $partnership->status }}">
                    @if($partnership->status === 'approved')
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    @elseif($partnership->status === 'rejected')
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                    @else
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    @endif
                </div>
                <div class="review-status-text">
                    @if($partnership->status === 'approved')
                        <h3>Partnership Approved</h3>
                        <p>Congratulations — your request has been approved. Welcome to the DonateBazaar partner network.</p>
                    @elseif($partnership->status === 'rejected')
                        <h3>Request Not Approved</h3>
                        <p>Your request could not be approved at this time. Review the feedback below and consider reapplying.</p>
                    @else
                        <h3>Request Under Review</h3>
                        <p>Your partnership request is being reviewed. We will respond within 2 business days.</p>
                    @endif
                    <div class="status-pill pill-{{ $partnership->status }}">
                        <span class="status-pill-dot"></span>
                        @if($partnership->status === 'approved') Approved
                        @elseif($partnership->status === 'rejected') Not Approved
                        @else Pending Review
                        @endif
                    </div>
                </div>
            </div>
            <div class="review-body">
                @if($partnership->status === 'approved')
                <div class="approved-highlight">
                    <div class="approved-highlight-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0 1 12 2.944a11.955 11.955 0 0 1-8.618 3.04A12.02 12.02 0 0 0 3 9c0 5.591 3.824 10.29 9 11.622C17.176 19.29 21 14.591 21 9c0-1.082-.132-2.135-.382-3.042z"/></svg>
                    </div>
                    <div>
                        <h4>Your partnership is now active</h4>
                        <p>A dedicated relationship manager has been assigned. Check your email for onboarding instructions.</p>
                    </div>
                </div>
                @endif
                @if($partnership->status === 'rejected' && $partnership->admin_note)
                <div class="rv-section">Admin feedback</div>
                <div class="admin-feedback">{{ $partnership->admin_note }}</div>
                @endif
                <div class="rv-section">Your submitted details</div>
                <div class="rv-grid">
                    <div class="rv-field"><div class="rv-field-label">Full Name</div><div class="rv-field-value">{{ $partnership->name }}</div></div>
                    <div class="rv-field"><div class="rv-field-label">Email</div><div class="rv-field-value">{{ $partnership->email }}</div></div>
                    <div class="rv-field"><div class="rv-field-label">Phone</div><div class="rv-field-value {{ !$partnership->phone?'empty':'' }}">{{ $partnership->phone ?: 'Not provided' }}</div></div>
                    <div class="rv-field"><div class="rv-field-label">Organisation</div><div class="rv-field-value">{{ $partnership->organization_name }}</div></div>
                    <div class="rv-field"><div class="rv-field-label">Website</div><div class="rv-field-value {{ !$partnership->website?'empty':'' }}">@if($partnership->website)<a href="{{ $partnership->website }}" target="_blank">{{ $partnership->website }}</a>@else Not provided @endif</div></div>
                    <div class="rv-field"><div class="rv-field-label">Type</div><div class="rv-field-value"><span class="type-badge"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 3H8l-2 4h12l-2-4z"/></svg>{{ ucwords(str_replace('_',' ',$partnership->partnership_type ?? 'Not selected')) }}</span></div></div>
                    @if($partnership->message)<div class="rv-field full"><div class="rv-field-label">Proposal</div><div class="rv-field-value" style="white-space:pre-line">{{ $partnership->message }}</div></div>@endif
                    <div class="rv-field"><div class="rv-field-label">Document</div><div class="rv-field-value {{ !$partnership->document?'empty':'' }}">@if($partnership->document)<a href="{{ asset($partnership->document) }}" target="_blank" style="display:inline-flex;align-items:center;gap:6px"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:13px;height:13px"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>View document</a>@else Not uploaded @endif</div></div>
                    <div class="rv-field"><div class="rv-field-label">Submitted</div><div class="rv-field-value">{{ $partnership->created_at->format('d M Y, h:i A') }}</div></div>
                </div>
                @if($partnership->status === 'pending')
                <div class="rv-section">What happens next</div>
                <div class="timeline">
                    <div class="tl-item"><div class="tl-dot tl-done"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg></div><div class="tl-content"><div class="tl-title">Request submitted</div><div class="tl-desc">Received on {{ $partnership->created_at->format('d M Y') }}.</div></div></div>
                    <div class="tl-item"><div class="tl-dot tl-active"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div><div class="tl-content"><div class="tl-title">Under review <span class="tl-inprogress">In progress</span></div><div class="tl-desc">Our team is reviewing your documents. Typically 1–2 business days.</div></div></div>
                    <div class="tl-item"><div class="tl-dot"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg></div><div class="tl-content"><div class="tl-title">Decision email</div><div class="tl-desc">Sent to <strong>{{ $partnership->email }}</strong> once complete.</div></div></div>
                    <div class="tl-item"><div class="tl-dot"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></div><div class="tl-content"><div class="tl-title">Onboarding call</div><div class="tl-desc">If approved, a relationship manager will schedule your onboarding.</div></div></div>
                </div>
                @endif
                @if($partnership->status === 'approved')
                <div class="rv-section">Your next steps</div>
                <div class="timeline">
                    <div class="tl-item"><div class="tl-dot tl-done"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg></div><div class="tl-content"><div class="tl-title">Check your email</div><div class="tl-desc">Onboarding instructions sent to <strong>{{ $partnership->email }}</strong>.</div></div></div>
                    <div class="tl-item"><div class="tl-dot tl-active"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 13a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.77 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l.91-.91a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 17z"/></svg></div><div class="tl-content"><div class="tl-title">Schedule call <span class="tl-action">Action needed</span></div><div class="tl-desc">Your relationship manager will reach out for a 30-min onboarding session.</div></div></div>
                    <div class="tl-item"><div class="tl-dot"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg></div><div class="tl-content"><div class="tl-title">Sign partnership agreement</div><div class="tl-desc">A digital agreement activates your full partner dashboard.</div></div></div>
                </div>
                @endif
            </div>
        </div>

    @else

        {{-- ── FORM STATE ── --}}
        <div class="stepper-wrap">
            <div class="stepper-item s-done">
                <div class="stepper-dot s-done"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:14px;height:14px"><path d="M20 6L9 17l-5-5"/></svg></div>
                <span class="stepper-label s-done">Your Info</span>
            </div>
            <div class="stepper-item">
                <div class="stepper-dot s-active">2</div>
                <span class="stepper-label s-active">Organisation</span>
            </div>
            <div class="stepper-item">
                <div class="stepper-dot">3</div>
                <span class="stepper-label">Review</span>
            </div>
        </div>

        <div class="form-card">
            <div class="card-header-bar">
                <div class="step-badge">Partnership Request</div>
                <div class="step-heading">Tell us about yourself</div>
                <p class="step-sub">Provide your contact and organisation details so we can reach out.</p>
            </div>
            <div class="card-body">
                <form action="{{ route('partnership.store') }}" method="POST" enctype="multipart/form-data" id="partnerForm">
@csrf

<div class="field-stack">

    {{-- CONTACT --}}
    <div class="section-title">Contact information</div>

    <div class="field-grid">
        <div class="field-wrap">
            <label class="field-label">Full name *</label>
            <input type="text" name="name" class="field-input" value="{{ old('name') }}" required>
        </div>

        <div class="field-wrap">
            <label class="field-label">Email *</label>
            <input type="email" name="email" class="field-input" value="{{ old('email') }}" required>
        </div>
    </div>

    <div class="field-wrap">
        <label class="field-label">Phone *</label>
        <input type="text" name="phone" class="field-input" value="{{ old('phone') }}" required>
    </div>


    {{-- ORGANIZATION --}}
    <div class="section-title">Organisation details</div>

    <div class="field-grid">
        <div class="field-wrap">
            <label class="field-label">Organisation name *</label>
            <input type="text" name="organization_name" class="field-input" value="{{ old('organization_name') }}" required>
        </div>

        <div class="field-wrap">
            <label class="field-label">Website</label>
            <input type="url" name="website" class="field-input" value="{{ old('website') }}">
        </div>
    </div>

    {{-- 🔥 NEW --}}
    <div class="field-grid">
        <div class="field-wrap">
            <label class="field-label">Organisation Type</label>
            <select name="organization_type" class="field-input">
                <option value="">Select</option>
                <option value="ngo">NGO</option>
                <option value="company">Company</option>
                <option value="startup">Startup</option>
                <option value="individual">Individual</option>
            </select>
        </div>

        <div class="field-wrap">
            <label class="field-label">Team Size</label>
            <select name="organization_size" class="field-input">
                <option value="">Select</option>
                <option value="1-10">1-10</option>
                <option value="10-50">10-50</option>
                <option value="50-200">50-200</option>
                <option value="200+">200+</option>
            </select>
        </div>
    </div>

    <div class="field-wrap">
        <label class="field-label">Location</label>
        <input type="text" name="location" class="field-input" placeholder="City, Country">
    </div>


    {{-- PARTNERSHIP TYPE --}}
    <div class="section-title">Partnership type</div>

<select name="partnership_type" class="field-input" required>
    <option value="">Select type</option>
    <option value="csr" {{ old('partnership_type') == 'csr' ? 'selected' : '' }}>CSR</option>
    <option value="event" {{ old('partnership_type') == 'event' ? 'selected' : '' }}>Event</option>
    <option value="product" {{ old('partnership_type') == 'product' ? 'selected' : '' }}>Product</option>
    <option value="corporate" {{ old('partnership_type') == 'corporate' ? 'selected' : '' }}>Corporate</option>
    <option value="media" {{ old('partnership_type') == 'media' ? 'selected' : '' }}>Media</option>
    <option value="other" {{ old('partnership_type') == 'other' ? 'selected' : '' }}>Other</option>
</select>


    {{--  NEW INTENT --}}
    <div class="section-title">Partnership goal</div>

    <div class="field-grid">
        <div class="field-wrap">
            <label class="field-label">Goal</label>
            <select name="goal" class="field-input">
                <option value="">Select</option>
                <option value="funding">Funding</option>
                <option value="collaboration">Collaboration</option>
                <option value="listing">Platform Listing</option>
                <option value="csr">CSR</option>
            </select>
        </div>

        <div class="field-wrap">
            <label class="field-label">Timeline</label>
            <select name="timeline" class="field-input">
                <option value="">Select</option>
                <option value="immediate">Immediate</option>
                <option value="1_month">1 Month</option>
                <option value="flexible">Flexible</option>
            </select>
        </div>
    </div>


    {{-- PROPOSAL --}}
    <div class="section-title">Proposal</div>

    <div class="field-wrap">
        <textarea name="message" class="field-input" rows="5"
        placeholder="Describe your partnership idea...">{{ old('message') }}</textarea>
    </div>


    {{-- DOCUMENT --}}
    <div class="field-wrap">
        <label class="field-label">Upload Document</label>
        <input type="file" name="document" class="field-input">
    </div>


    {{-- SUBMIT --}}
    <div class="form-nav">
        <x-button variant="primary" type="submit">
            Submit Partnership Request
        </x-button>
    </div>

</div>
</form>
            </div>
        </div>

    @endif

</main>

{{-- ══ RIGHT ══ --}}
<aside class="pg-right">
    <div class="testimonial-card">
        <div class="quote-mark">"</div>
        <p class="testimonial-text">Partnering with DonateBazaar gave our CSR program real credibility. The impact reports are detailed and our employees feel genuinely proud of where the money goes.</p>
        <div class="testimonial-author">
            <div class="t-avatar">R</div>
            <div><div class="t-name">Riya Menon</div><div class="t-role">CSR Head, Infosys Foundation</div></div>
        </div>
    </div>
    <div class="testimonial-card">
        <div class="quote-mark">"</div>
        <p class="testimonial-text">The onboarding was seamless. Within a week, our donation drive was live and we could track exactly where every rupee was going in real time.</p>
        <div class="testimonial-author">
            <div class="t-avatar" style="background:linear-gradient(135deg,#15803d,#10b981)">A</div>
            <div><div class="t-name">Arjun Kapoor</div><div class="t-role">Director, Tata Trusts</div></div>
        </div>
    </div>
    <div class="faq-card">
        <div class="faq-header"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg> Common Questions</div>
        <div class="faq-item"><div class="faq-q"><span class="faq-q-badge">Q</span> How long does approval take?</div><div class="faq-a">Our team reviews applications within 2 business days and schedules a call to discuss next steps.</div></div>
        <div class="faq-item"><div class="faq-q"><span class="faq-q-badge">Q</span> Is there a minimum commitment?</div><div class="faq-a">No. Partnerships are flexible — you define the scope, timeline, and contribution level.</div></div>
        <div class="faq-item"><div class="faq-q"><span class="faq-q-badge">Q</span> What do we get in return?</div><div class="faq-a">Co-branding, impact certificates, quarterly reports, and a dedicated relationship manager.</div></div>
        <div class="faq-item"><div class="faq-q"><span class="faq-q-badge">Q</span> Is my data secure?</div><div class="faq-a">All submissions are encrypted. We never share partner data with third parties.</div></div>
    </div>
</aside>

</div>
</div>

<script type="application/json" id="partnershipData">
@php
    $partnershipData = [
        'success' => session('success'),
        'error' => session('error'),
        'errorsCount' => $errors->any() ? $errors->count() : 0,
    ];
@endphp
@json($partnershipData)
</script>

@push('scripts')
@vite('resources/js/public/partnership.js')
@endpush

@endsection