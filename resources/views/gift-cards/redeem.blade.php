@extends('layouts.user')

@section('page_title', 'Redeem Gift Card')
@section('page_subtitle', 'Turn your code into a donation')

@push('page_styles')
<style>
.gr-wrap{max-width:600px;width:100%;margin:0 auto;}
.gr-head{text-align:center;margin-bottom:24px;}
.gr-eyebrow{font-size:11px;color:var(--text3);letter-spacing:.1em;text-transform:uppercase;margin-bottom:8px;}
.gr-head h1{font-size:27px;font-weight:700;color:var(--text);margin-bottom:8px;letter-spacing:-0.02em;line-height:1.2;}
.gr-head p{font-size:14px;color:var(--text2);line-height:1.5;margin:0;}

/* ── Progress ── */
.gr-progress{margin-bottom:22px;}
.gr-progress-bar{height:5px;border-radius:99px;background:var(--border2);overflow:hidden;margin-bottom:10px;}
.gr-progress-fill{height:100%;border-radius:99px;background:linear-gradient(90deg,var(--accent),var(--accent2,var(--accent)));width:25%;transition:width .35s cubic-bezier(.65,0,.35,1);}
.gr-progress-meta{display:flex;align-items:baseline;justify-content:space-between;gap:8px;}
.gr-progress-step{font-size:11px;font-weight:700;color:var(--accent);text-transform:uppercase;letter-spacing:.06em;font-family:var(--font-mono);white-space:nowrap;}
.gr-progress-title{font-size:12.5px;color:var(--text2);font-weight:600;text-align:right;}

/* ── Wizard steps ── */
.gr-wizard{position:relative;}
.gr-step{display:none;}
.gr-step.slide-in-right{animation:slideInRight .32s cubic-bezier(.22,1,.36,1) both;}
.gr-step.slide-in-left{animation:slideInLeft .32s cubic-bezier(.22,1,.36,1) both;}
@keyframes slideInRight{from{opacity:0;transform:translateX(18px);}to{opacity:1;transform:translateX(0);}}
@keyframes slideInLeft{from{opacity:0;transform:translateX(-18px);}to{opacity:1;transform:translateX(0);}}
@media (prefers-reduced-motion:reduce){
    .gr-step.slide-in-right,.gr-step.slide-in-left{animation:none;}
}

.gr-card{background:var(--surface);border-radius:16px;border:1px solid var(--border);padding:20px;margin-bottom:16px;}
.gr-card-step{font-size:12px;font-weight:600;color:var(--text2);text-transform:uppercase;letter-spacing:.08em;margin-bottom:14px;display:flex;align-items:center;gap:8px;}
.gr-card-step .s-num{width:20px;height:20px;border-radius:50%;background:var(--accent);color:#fff;display:inline-flex;align-items:center;justify-content:center;font-size:10px;font-family:var(--font-mono);font-weight:700;flex-shrink:0;}

.gr-code-row{display:flex;gap:10px;}
.gr-code-input{flex:1;height:48px;border-radius:9px;border:1.5px solid var(--border2);padding:0 14px;font-size:16px;font-family:var(--font-mono);letter-spacing:.08em;text-transform:uppercase;outline:none;background:var(--surface2);color:var(--text);transition:border-color var(--tr),box-shadow var(--tr);}
.gr-code-input:focus{border-color:var(--accent);box-shadow:0 0 0 3px var(--accent-glow);}
.gr-code-btn{padding:0 22px;height:48px;background:var(--accent);color:#fff;border:none;border-radius:9px;font-size:13px;font-weight:600;cursor:pointer;white-space:nowrap;transition:opacity var(--tr);}
.gr-code-btn:hover{opacity:.9;}
.gr-code-btn:disabled{opacity:.6;cursor:not-allowed;}
.gr-code-hint{font-size:11px;color:var(--text3);margin-top:8px;}
.gr-status{margin-top:10px;font-size:13px;min-height:18px;display:flex;align-items:center;gap:6px;}
.gr-spin{width:13px;height:13px;border-radius:50%;border:2px solid var(--border2);border-top-color:var(--accent);animation:spin .6s linear infinite;flex-shrink:0;}
@keyframes spin{to{transform:rotate(360deg);}}

.gr-search{position:relative;margin-bottom:12px;}
.gr-search input{width:100%;height:38px;border-radius:9px;border:1.5px solid var(--border2);padding:0 12px 0 34px;font-size:13px;outline:none;background:var(--surface2);color:var(--text);transition:border-color var(--tr),box-shadow var(--tr);}
.gr-search input:focus{border-color:var(--accent);box-shadow:0 0 0 3px var(--accent-glow);}
.gr-search svg{position:absolute;left:11px;top:50%;transform:translateY(-50%);color:var(--text3);pointer-events:none;}
.gr-search-empty{display:none;padding:16px;text-align:center;font-size:12px;color:var(--text3);}

.gr-camp-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px;max-height:380px;overflow-y:auto;}
.gr-camp-card{position:relative;border:2px solid var(--border);border-radius:12px;overflow:hidden;cursor:pointer;transition:border-color .15s,transform .15s,box-shadow .15s;background:var(--surface);}
.gr-camp-card:hover{border-color:var(--accent-glow);transform:translateY(-2px);}
.gr-camp-card.selected{border-color:var(--accent);box-shadow:0 6px 18px var(--accent-glow);}
.gr-camp-card.just-selected{animation:pop .32s ease;}
@keyframes pop{0%{transform:scale(1);}45%{transform:scale(1.035);}100%{transform:scale(1);}}
@media (prefers-reduced-motion:reduce){.gr-camp-card.just-selected{animation:none;}}
.gr-camp-check{position:absolute;top:6px;right:6px;width:20px;height:20px;border-radius:50%;background:var(--accent);color:#fff;display:none;align-items:center;justify-content:center;font-size:11px;z-index:2;}
.gr-camp-card.selected .gr-camp-check{display:flex;}
.gr-camp-thumb-wrap{height:84px;overflow:hidden;position:relative;background:var(--surface2);}
.gr-camp-thumb{width:100%;height:100%;background-size:cover;background-position:center;display:flex;align-items:center;justify-content:center;color:var(--text3);transition:transform .35s ease;}
.gr-camp-card:hover .gr-camp-thumb{transform:scale(1.06);}
.gr-camp-ring{position:absolute;top:8px;right:8px;width:34px;height:34px;border-radius:50%;display:flex;align-items:center;justify-content:center;z-index:1;box-shadow:0 2px 6px rgba(0,0,0,.15);}
.gr-camp-ring-inner{width:27px;height:27px;border-radius:50%;background:var(--surface);display:flex;align-items:center;justify-content:center;font-size:8.5px;font-weight:700;color:var(--text);font-family:var(--font-mono);}
.gr-camp-info{padding:10px;}
.gr-camp-title{font-size:12px;font-weight:600;color:var(--text);line-height:1.4;margin-bottom:4px;}
.gr-camp-amt{font-size:13px;font-weight:700;color:var(--text);font-family:var(--font-mono);}
.gr-camp-goal{font-size:10px;color:var(--text3);}

.gr-field{margin-bottom:12px;}
.gr-field:last-of-type{margin-bottom:0;}
.gr-field label{font-size:12px;color:var(--text2);display:block;margin-bottom:5px;font-weight:600;}
.gr-field input{width:100%;height:40px;border-radius:9px;border:1.5px solid var(--border2);padding:0 12px;font-size:13px;outline:none;background:var(--surface2);color:var(--text);transition:border-color var(--tr),box-shadow var(--tr);}
.gr-field input:focus{border-color:var(--accent);box-shadow:0 0 0 3px var(--accent-glow);}
.gr-field-hint{font-size:11px;color:var(--text3);margin-top:5px;}

/* ── Step actions ── */
.gr-step-actions{display:flex;gap:10px;}
.gr-btn-secondary{flex:0 0 auto;padding:0 18px;height:46px;background:transparent;color:var(--text2);border:1.5px solid var(--border2);border-radius:12px;font-size:14px;font-weight:600;cursor:pointer;transition:background var(--tr),border-color var(--tr);}
.gr-btn-secondary:hover{background:var(--surface2);border-color:var(--border);}
.gr-btn-primary{flex:1;padding:0 14px;height:46px;background:var(--accent);color:#fff;border:none;border-radius:12px;font-size:15px;font-weight:600;cursor:pointer;transition:opacity var(--tr);}
.gr-btn-primary:disabled{opacity:.5;cursor:not-allowed;}
.gr-btn-primary:not(:disabled):hover{opacity:.9;}

/* ── Review step ── */
.gr-review-camp{display:flex;align-items:center;gap:12px;padding:12px;background:var(--surface2);border-radius:12px;margin-bottom:14px;}
.gr-review-camp-thumb{width:52px;height:52px;border-radius:9px;background-size:cover;background-position:center;background-color:var(--surface);flex-shrink:0;}
.gr-review-camp-title{font-size:13px;font-weight:700;color:var(--text);}
.gr-review-camp-sub{font-size:11px;color:var(--text3);}
.gr-review-row{display:flex;align-items:center;justify-content:space-between;padding:10px 0;border-bottom:1px solid var(--border);gap:10px;}
.gr-review-row:last-child{border-bottom:none;}
.gr-review-label{font-size:12px;color:var(--text3);}
.gr-review-value{font-size:13px;color:var(--text);font-weight:600;text-align:right;}
.gr-review-value.mono{font-family:var(--font-mono);}
.gr-review-change{font-size:11px;color:var(--accent);font-weight:700;cursor:pointer;background:none;border:none;padding:0;flex-shrink:0;}
.gr-review-change:hover{text-decoration:underline;}
.gr-review-total{display:flex;align-items:center;justify-content:space-between;padding:14px 16px;background:var(--accent-glow);border-radius:12px;margin-top:14px;}
.gr-review-total-label{font-size:13px;font-weight:600;color:var(--text);}
.gr-review-total-amt{font-size:20px;font-weight:800;color:var(--accent);font-family:var(--font-mono);}

/* ── How it works (step 1) ── */
#howItWorks{margin-top:24px;}
.hiw-title{font-size:11px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.08em;text-align:center;margin-bottom:16px;}
.hiw-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:14px;}
.hiw-item{text-align:center;padding:18px 12px;}
.hiw-icon{width:44px;height:44px;border-radius:12px;background:var(--accent-glow);color:var(--accent);display:flex;align-items:center;justify-content:center;margin:0 auto 12px;}
.hiw-item-title{font-size:13px;font-weight:700;color:var(--text);margin-bottom:4px;}
.hiw-item-desc{font-size:12px;color:var(--text3);line-height:1.5;}

.gr-foot{text-align:center;margin-top:16px;font-size:12px;color:var(--text3);}
.gr-foot a{color:var(--accent);font-weight:600;text-decoration:none;}
.gr-foot a:hover{text-decoration:underline;}

.alert-error{background:#fee2e2;border:1px solid #fecaca;color:#b91c1c;padding:12px 16px;border-radius:10px;font-size:13px;margin-bottom:16px;}
.alert-error p{margin:0;}

@media (max-width:640px){
    .gr-head h1{font-size:22px;}
    .gr-camp-grid{grid-template-columns:1fr;max-height:none;gap:8px;}
    .gr-card{padding:14px;}
    .gr-code-row{flex-direction:column;gap:8px;}
    .gr-code-btn{height:44px;}
    .hiw-grid{grid-template-columns:1fr;gap:0;}
    .hiw-item{text-align:left;display:flex;align-items:flex-start;gap:12px;padding:12px 4px;}
    .hiw-icon{margin:0;flex-shrink:0;}
    .gr-progress-title{display:none;}
}
@media (max-width:480px){
    .gr-head{margin-bottom:18px;}
    .gr-head h1{font-size:20px;}
    .gr-head p{font-size:13px;}
    .gr-camp-thumb-wrap{height:64px;}
    .gr-camp-info{padding:8px 10px;}
    .gr-camp-title{font-size:11px;}
}
</style>
@endpush

@section('content')
<div class="gr-wrap">

    <div class="gr-head">
        <div class="gr-eyebrow">DonateBazaar</div>
        <h1>Redeem Your Gift Card</h1>
        <p>Enter your code and turn it into a donation for a cause you love.</p>
    </div>

    <div class="gr-progress" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="25">
        <div class="gr-progress-bar"><div class="gr-progress-fill" id="progressFill"></div></div>
        <div class="gr-progress-meta">
            <span class="gr-progress-step" id="progressStepLabel">Step 1 of 4</span>
            <span class="gr-progress-title" id="progressTitleLabel">Enter your gift card code</span>
        </div>
    </div>

    @if(session('error'))
    <div class="alert-error">{{ session('error') }}</div>
    @endif

    @if ($errors->any())
    <div class="alert-error">
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
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
                    <div class="gr-card-step"><span class="s-num">1</span>Gift card code</div>
                    <div class="gr-code-row">
                        <input type="text" id="giftCode" placeholder="DNBZ-XXXX-XXXX" maxlength="14" class="gr-code-input" autocomplete="off" inputmode="text">
                        <button type="button" onclick="checkCode()" id="checkBtn" class="gr-code-btn">Check</button>
                    </div>
                    <p class="gr-code-hint">Format: XXXX-XXXX-XXXX &mdash; we'll check it automatically once complete.</p>
                    <div id="codeStatus" class="gr-status" role="status" aria-live="polite"></div>
                </div>

                <div id="howItWorks">
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
                    <div class="gr-card-step"><span class="s-num">2</span>Choose a campaign</div>

                    @if($campaigns->count() > 6)
                    <div class="gr-search">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        <input type="text" id="campSearch" placeholder="Search campaigns…" oninput="filterCampaigns(this.value)">
                    </div>
                    @endif

                    <div class="gr-camp-grid" id="campGrid">
                        @forelse($campaigns as $c)
                        @php $pct = $c->goal_amount > 0 ? min(100, round(($c->raised_amount / $c->goal_amount) * 100)) : 0; @endphp
                        <div onclick="selectCampaign({{ $c->id }}, this, '{{ addslashes($c->title) }}', {{ $c->raised_amount }}, {{ $c->goal_amount }}, '{{ $c->cover_image ? addslashes(asset('storage/'.$c->cover_image)) : '' }}')"
                             id="camp-{{ $c->id }}"
                             class="gr-camp-card"
                             data-title="{{ strtolower($c->title) }}"
                             style="--pct:{{ $pct }}"
                             role="button" tabindex="0" aria-pressed="false">
                            <div class="gr-camp-check">✓</div>
                            <div class="gr-camp-thumb-wrap">
                                <div class="gr-camp-thumb" @if($c->cover_image) style="background-image:url('{{ asset('storage/'.$c->cover_image) }}')" @endif>
                                    @if(!$c->cover_image)
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 7h16v13H4z"/><path d="M4 7l8-4 8 4"/></svg>
                                    @endif
                                </div>
                                <div class="gr-camp-ring" style="background:conic-gradient(var(--accent) calc({{ $pct }}*1%), rgba(255,255,255,.55) 0)">
                                    <div class="gr-camp-ring-inner">{{ $pct }}%</div>
                                </div>
                            </div>
                            <div class="gr-camp-info">
                                <div class="gr-camp-title">{{ \Illuminate\Support\Str::limit($c->title, 40) }}</div>
                                <div class="gr-camp-amt">₹{{ number_format($c->raised_amount) }}</div>
                                <div class="gr-camp-goal">of ₹{{ number_format($c->goal_amount) }} goal</div>
                            </div>
                        </div>
                        @empty
                        <p style="font-size:13px;color:var(--text3);grid-column:1/-1;">No active campaigns available right now.</p>
                        @endforelse
                    </div>
                    <p class="gr-search-empty" id="searchEmpty">No campaigns match your search.</p>
                </div>

                <div class="gr-step-actions">
                    <button type="button" class="gr-btn-secondary" onclick="gotoStep(1)">Back</button>
                    <button type="button" class="gr-btn-primary" id="step2NextBtn" disabled onclick="gotoStep(3)">Continue</button>
                </div>
            </section>

            {{-- STEP 3 — Details --}}
            <section class="gr-step" data-step="3">
                <div class="gr-card">
                    <div class="gr-card-step"><span class="s-num">3</span>Your details</div>

                    <div class="gr-field">
                        <label>Your name *</label>
                        <input type="text" name="donor_name" id="donorName" required>
                    </div>
                    <div class="gr-field">
                        <label>Your email *</label>
                        <input type="email" name="donor_email" id="donorEmail" required>
                        <p class="gr-field-hint" id="emailHint"></p>
                    </div>
                </div>

                <div class="gr-step-actions">
                    <button type="button" class="gr-btn-secondary" onclick="gotoStep(2)">Back</button>
                    <button type="button" class="gr-btn-primary" onclick="tryGotoReview()">Review donation</button>
                </div>
            </section>

            {{-- STEP 4 — Review --}}
            <section class="gr-step" data-step="4">
                <div class="gr-card">
                    <div class="gr-card-step"><span class="s-num">4</span>Review &amp; confirm</div>

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
                        <button type="button" class="btn btn-secondary gr-review-change" onclick="gotoStep(3)">Change</button>
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
                    <button type="button" class="gr-btn-secondary" onclick="gotoStep(3)">Back</button>
                    <button type="submit" id="redeemBtn" class="gr-btn-primary">Redeem Gift Card &amp; Donate</button>
                </div>
            </section>

        </div>
    </form>

    <p class="gr-foot">
        Don't have a gift card yet?
        <a href="{{ route('gift-cards.index') }}">Buy one here</a>
    </p>

</div>
@endsection

@push('page_scripts')
<script>
var validatedCode = null;
var selectedCampaignId = null;
var selectedCampaignTitle = '';
var selectedCampaignImg = '';
var giftAmount = 0;
var currentStep = 1;
var totalSteps = 4;
var stepTitles = ['Enter your gift card code', 'Choose a campaign', 'Your details', 'Review & confirm'];

function updateProgress() {
    var pct = Math.round((currentStep / totalSteps) * 100);
    document.getElementById('progressFill').style.width = pct + '%';
    document.getElementById('progressStepLabel').textContent = 'Step ' + currentStep + ' of ' + totalSteps;
    document.getElementById('progressTitleLabel').textContent = stepTitles[currentStep - 1];
    document.querySelector('.gr-progress').setAttribute('aria-valuenow', pct);
}

function showStep(n, direction) {
    document.querySelectorAll('.gr-step').forEach(function (s) {
        var stepNum = parseInt(s.dataset.step, 10);
        if (stepNum === n) {
            s.style.display = 'block';
            s.classList.remove('slide-in-left', 'slide-in-right');
            void s.offsetWidth;
            s.classList.add(direction === 'back' ? 'slide-in-left' : 'slide-in-right');
            s.setAttribute('aria-current', 'step');
        } else {
            s.style.display = 'none';
            s.removeAttribute('aria-current');
        }
    });
    currentStep = n;
    updateProgress();
    var wiz = document.getElementById('wizard');
    var top = wiz.getBoundingClientRect().top + window.scrollY - 20;
    window.scrollTo({ top: top, behavior: 'smooth' });
}

function gotoStep(n) {
    if (n === currentStep) return;
    showStep(n, n < currentStep ? 'back' : 'forward');
}

function formatCode(raw) {
    raw = raw.toUpperCase().replace(/[^A-Z0-9]/g, '').substring(0, 12);
    var groups = raw.match(/.{1,4}/g) || [];
    return groups.join('-');
}

var giftCodeInput = document.getElementById('giftCode');

giftCodeInput.addEventListener('input', function (e) {
    e.target.value = formatCode(e.target.value);
    if (/^[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}$/.test(e.target.value)) {
        checkCode();
    }
});

giftCodeInput.addEventListener('keypress', function (e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        checkCode();
    }
});

function checkCode() {
    var code = giftCodeInput.value.trim().toUpperCase();
    var statusEl = document.getElementById('codeStatus');
    var btn = document.getElementById('checkBtn');

    if (!code) {
        statusEl.innerHTML = '<span style="color:#b91c1c;">Please enter a code.</span>';
        return;
    }

    btn.disabled = true;
    btn.textContent = 'Checking…';
    statusEl.innerHTML = '<span class="gr-spin"></span><span>Checking your code…</span>';

    fetch('{{ route("gift-cards.validate-code") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ code: code })
    })
    .then(function (r) { return r.json(); })
    .then(function (data) {
        btn.disabled = false;
        btn.textContent = 'Check';

        if (data.valid) {
            validatedCode = data.code;
            giftAmount = Number(data.amount);
            statusEl.innerHTML = '<span style="color:#059669;">✓ Valid! This gift card is worth ₹' + giftAmount.toLocaleString('en-IN') + '.</span>';
            document.getElementById('hiddenCode').value = data.code;

            var emailInput = document.getElementById('donorEmail');
            emailInput.readOnly = false;
            emailInput.style.background = '';
            emailInput.style.cursor = '';
            document.getElementById('emailHint').innerHTML = 'This gift card was sent to <strong>' + data.recipient_email_masked + '</strong> — enter your full email above.';

            setTimeout(function () { gotoStep(2); }, 350);
        } else {
            validatedCode = null;
            statusEl.innerHTML = '<span style="color:#b91c1c;">' + (data.message || 'Invalid code.') + '</span>';

            var emailInput = document.getElementById('donorEmail');
            emailInput.value = '';
            emailInput.readOnly = false;
            emailInput.style.background = '';
            emailInput.style.cursor = '';
            document.getElementById('emailHint').textContent = '';
        }
    })
    .catch(function () {
        btn.disabled = false;
        btn.textContent = 'Check';
        statusEl.innerHTML = '<span style="color:#b91c1c;">Something went wrong. Please try again.</span>';
    });
}

function selectCampaign(id, el, title, raised, goal, imageUrl) {
    selectedCampaignId = id;
    selectedCampaignTitle = title;
    selectedCampaignImg = imageUrl;
    document.getElementById('hiddenCampaignId').value = id;

    document.querySelectorAll('.gr-camp-card').forEach(function (c) {
        c.classList.remove('selected', 'just-selected');
        c.setAttribute('aria-pressed', 'false');
    });
    el.classList.add('selected', 'just-selected');
    el.setAttribute('aria-pressed', 'true');

    document.getElementById('step2NextBtn').disabled = false;
}

function filterCampaigns(query) {
    query = query.trim().toLowerCase();
    var cards = document.querySelectorAll('.gr-camp-card');
    var visibleCount = 0;
    cards.forEach(function (card) {
        var match = card.dataset.title.indexOf(query) !== -1;
        card.style.display = match ? '' : 'none';
        if (match) visibleCount++;
    });
    document.getElementById('searchEmpty').style.display = visibleCount === 0 ? 'block' : 'none';
}

function tryGotoReview() {
    var name = document.getElementById('donorName');
    var email = document.getElementById('donorEmail');
    if (!name.value.trim()) { name.reportValidity(); name.focus(); return; }
    if (!email.value.trim()) { email.reportValidity(); email.focus(); return; }

    document.getElementById('reviewCode').textContent = validatedCode || '';
    document.getElementById('reviewCampaign').textContent = selectedCampaignTitle;
    document.getElementById('reviewCampImg').style.backgroundImage = selectedCampaignImg ? "url('" + selectedCampaignImg + "')" : 'none';
    document.getElementById('reviewName').textContent = name.value.trim();
    document.getElementById('reviewEmail').textContent = email.value.trim();
    document.getElementById('reviewAmount').textContent = '₹' + giftAmount.toLocaleString('en-IN');

    gotoStep(4);
}

// Keyboard support for campaign cards
document.addEventListener('keydown', function (e) {
    if ((e.key === 'Enter' || e.key === ' ') && e.target.classList.contains('gr-camp-card')) {
        e.preventDefault();
        e.target.click();
    }
});

// Prevent double submission
document.getElementById('redeemForm').addEventListener('submit', function () {
    var btn = document.getElementById('redeemBtn');
    if (btn.dataset.submitted === 'true') { event.preventDefault(); return; }
    btn.dataset.submitted = 'true';
    btn.disabled = true;
    btn.textContent = 'Processing…';
});

showStep(1, 'forward');
</script>
@endpush