@extends('layouts.user')

@section('page_title', 'Redeem Gift Card')
@section('page_subtitle', 'Turn your code into a donation')

@push('page_styles')
@vite('resources/css/user/pages/gift-card-redeem.css')
@endpush

@section('content')
<div class="gr-wrap">

    {{-- Hero --}}
    <div class="gr-hero">
        <div class="gr-hero-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="8" width="20" height="13" rx="3"/><path d="M2 11h20M12 8v13"/><path d="M12 8c-2-4-7-3.5-7-1s3 1.2 7 1c4 .2 7-1.5 7-1s-5-3-7 1z"/></svg>
        </div>
        <div class="gr-hero-body">
            <span class="gr-eyebrow">DonateBazaar</span>
            <h1>Redeem Your Gift Card</h1>
            <p>Turn your code into a donation for a cause you love — it takes under a minute.</p>
        </div>
        <div class="gr-hero-badge">Secure &amp; instant</div>
    </div>

    {{-- Stepper --}}
    <nav class="gr-stepper" id="grStepper" aria-label="Redeem progress">
        <button type="button" class="gr-step-node active" data-node="1" tabindex="-1">
            <span class="gr-step-dot">1</span>
            <span class="gr-step-label">Code</span>
        </button>
        <span class="gr-step-line" data-line="1"></span>
        <button type="button" class="gr-step-node" data-node="2" tabindex="-1">
            <span class="gr-step-dot">2</span>
            <span class="gr-step-label">Campaign</span>
        </button>
        <span class="gr-step-line" data-line="2"></span>
        <button type="button" class="gr-step-node" data-node="3" tabindex="-1">
            <span class="gr-step-dot">3</span>
            <span class="gr-step-label">Details</span>
        </button>
        <span class="gr-step-line" data-line="3"></span>
        <button type="button" class="gr-step-node" data-node="4" tabindex="-1">
            <span class="gr-step-dot">4</span>
            <span class="gr-step-label">Review</span>
        </button>
    </nav>

    <div class="gr-progress" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="25">
        <div class="gr-progress-bar"><div class="gr-progress-fill" id="progressFill"></div></div>
        <div class="gr-progress-meta">
            <span class="gr-progress-step" id="progressStepLabel">Step 1 of 4</span>
            <span class="gr-progress-title" id="progressTitleLabel">Enter your gift card code</span>
        </div>
    </div>

    @if(session('error'))
    <div class="alert-error">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
        <div>{{ session('error') }}</div>
    </div>
    @endif

    @if ($errors->any())
    <div class="alert-error">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
        <div>
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    </div>
    @endif

    <form method="POST" action="{{ route('gift-cards.redeem.submit') }}" id="redeemForm">
        @csrf
        <input type="hidden" name="code" id="hiddenCode">
        <input type="hidden" name="campaign_id" id="hiddenCampaignId">

        <div class="gr-wizard" id="wizard">

            {{-- STEP 1 — Code --}}
            <section class="gr-step" data-step="1" aria-current="step">
                <div class="gr-card">
                    <div class="gr-card-step"><span class="s-num">1</span><span class="s-txt">Gift card code</span></div>
                    <div class="gr-code-row">
                        <input type="text" id="giftCode" placeholder="DNBZ-XXXX-XXXX" maxlength="14" class="gr-code-input" autocomplete="off" inputmode="text" aria-label="Gift card code">
                        <x-button variant="secondary" type="button" id="checkBtn" data-action="check-code" data-validate-url="{{ route('gift-cards.validate-code') }}" data-csrf="{{ csrf_token() }}" class="gr-code-btn">Check</x-button>
                    </div>
                    <p class="gr-code-hint">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
                        Format: XXXX-XXXX-XXXX &mdash; we'll check it automatically once complete.
                    </p>
                    <div id="codeStatus" class="gr-status" role="status" aria-live="polite"></div>
                </div>

                <div class="hiw">
                    <div class="hiw-title">How it works</div>
                    <div class="hiw-grid">
                        <div class="hiw-item">
                            <div class="hiw-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="8" width="18" height="13" rx="1"/><path d="M12 8v13M3 12h18M12 8c-1.5-4-6-4-6-1s3 1 6 1c3 0 6 2 6-1s-4.5-3-6 1z"/></svg></div>
                            <div class="hiw-item-title">Enter your code</div>
                            <div class="hiw-item-desc">Type in the code from your gift card and we'll check its value.</div>
                        </div>
                        <div class="hiw-item">
                            <div class="hiw-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1-1.1a5.5 5.5 0 1 0-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8z"/></svg></div>
                            <div class="hiw-item-title">Pick a cause</div>
                            <div class="hiw-item-desc">Choose from active campaigns and see exactly where it goes.</div>
                        </div>
                        <div class="hiw-item">
                            <div class="hiw-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg></div>
                            <div class="hiw-item-title">We donate instantly</div>
                            <div class="hiw-item-desc">Your gift card value is transferred to the campaign right away.</div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- STEP 2 — Campaign --}}
            <section class="gr-step" data-step="2">
                <div class="gr-card">
                    <div class="gr-card-step"><span class="s-num">2</span><span class="s-txt">Choose a campaign</span></div>

                    @if($campaigns->count() > 6)
                    <div class="gr-search">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        <input type="text" id="campSearch" placeholder="Search campaigns…">
                    </div>
                    @endif

                    <div class="gr-camp-grid" id="campGrid">
                        @forelse($campaigns as $c)
                        @php $pct = $c->goal_amount > 0 ? min(100, round(($c->raised_amount / $c->goal_amount) * 100)) : 0; @endphp
                        <div data-action="select-campaign" data-id="{{ $c->id }}" data-title="{{ addslashes($c->title) }}" data-image="{{ $c->cover_image ? addslashes(asset('storage/'.$c->cover_image)) : '' }}"
                             id="camp-{{ $c->id }}"
                             class="gr-camp-card"
                             data-title="{{ strtolower($c->title) }}"
                             style="--pct:{{ $pct }}"
                             role="button" tabindex="0" aria-pressed="false">
                            <div class="gr-camp-check">
                                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                            </div>
                            <div class="gr-camp-thumb-wrap">
                                <div class="gr-camp-thumb" @if($c->cover_image) style="background-image:url('{{ asset('storage/'.$c->cover_image) }}')" @endif>
                                    @if(!$c->cover_image)
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 7h16v13H4z"/><path d="M4 7l8-4 8 4"/></svg>
                                    @endif
                                </div>
                                <div class="gr-camp-ring" style="background:conic-gradient(var(--accent) calc({{ $pct }}*1%), rgba(255,255,255,.6) 0)">
                                    <div class="gr-camp-ring-inner">{{ $pct }}%</div>
                                </div>
                            </div>
                            <div class="gr-camp-info">
                                <div class="gr-camp-title">{{ \Illuminate\Support\Str::limit($c->title, 40) }}</div>
                                <div class="gr-camp-amt">₹{{ number_format($c->raised_amount) }} <span class="gr-camp-goal">of ₹{{ number_format($c->goal_amount) }} goal</span></div>
                                <div class="gr-camp-bar"><div class="gr-camp-bar-fill" style="width:{{ $pct }}%"></div></div>
                            </div>
                        </div>
                        @empty
                        <p style="font-size:13px;color:var(--text3);grid-column:1/-1;">No active campaigns available right now.</p>
                        @endforelse
                    </div>
                    <p class="gr-search-empty" id="searchEmpty">No campaigns match your search.</p>
                </div>

                <div class="gr-step-actions">
                    <x-button variant="secondary" type="button" class="gr-btn-secondary" data-action="goto-step" data-step="1">Back</x-button>
                    <x-button variant="primary" type="button" id="step2NextBtn" data-action="goto-step" data-step="3" disabled>Continue</x-button>
                </div>
            </section>

            {{-- STEP 3 — Details --}}
            <section class="gr-step" data-step="3">
                <div class="gr-card">
                    <div class="gr-card-step"><span class="s-num">3</span><span class="s-txt">Your details</span></div>

                    <div class="gr-field">
                        <label for="donorName">Your name <span class="req">*</span></label>
                        <input type="text" name="donor_name" id="donorName" placeholder="e.g. Ananya Sharma" required>
                    </div>
                    <div class="gr-field">
                        <label for="donorEmail">Your email <span class="req">*</span></label>
                        <input type="email" name="donor_email" id="donorEmail" placeholder="you@example.com" required>
                        <p class="gr-field-hint" id="emailHint"></p>
                    </div>
                </div>

                <div class="gr-step-actions">
                    <x-button variant="secondary" type="button" class="gr-btn-secondary" data-action="goto-step" data-step="2">Back</x-button>
                    <x-button variant="primary" type="button" data-action="goto-review">Review donation</x-button>
                </div>
            </section>

            {{-- STEP 4 — Review --}}
            <section class="gr-step" data-step="4">
                <div class="gr-card">
                    <div class="gr-card-step"><span class="s-num">4</span><span class="s-txt">Review &amp; confirm</span></div>

                    <div class="gr-review-camp">
                        <div class="gr-review-camp-thumb" id="reviewCampImg"></div>
                        <div>
                            <div class="gr-review-camp-title" id="reviewCampaign"></div>
                            <div class="gr-review-camp-sub">Your chosen campaign</div>
                        </div>
                    </div>

                    <div class="gr-review-row">
                        <span class="gr-review-label">Gift card code</span>
                        <span class="gr-review-value mono" id="reviewCode"></span>
                    </div>
                    <div class="gr-review-row">
                        <span class="gr-review-label">Donor name</span>
                        <span class="gr-review-value" id="reviewName"></span>
                        <x-button variant="secondary" type="button" class="gr-review-change" data-action="goto-step" data-step="3">Change</x-button>
                    </div>
                    <div class="gr-review-row">
                        <span class="gr-review-label">Donor email</span>
                        <span class="gr-review-value" id="reviewEmail"></span>
                    </div>

                    <div class="gr-review-total">
                        <span class="gr-review-total-label">Total donation</span>
                        <span class="gr-review-total-amt" id="reviewAmount"></span>
                    </div>
                </div>

                <div class="gr-step-actions">
                    <x-button variant="secondary" type="button" class="gr-btn-secondary" data-action="goto-step" data-step="3">Back</x-button>
                    <x-button variant="primary" type="submit" id="redeemBtn">Redeem Gift Card &amp; Donate</x-button>
                </div>
            </section>

        </div>
    </form>

    <p class="gr-foot">
        Don't have a gift card yet?
        <a href="{{ route('gift-cards.index') }}">Buy one here <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg></a>
    </p>

</div>
@endsection

@push('page_scripts')
@vite('resources/js/user/gift-card-redeem.js')
@endpush
