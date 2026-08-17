@extends('layouts.user')

@section('page_title', 'Gift Cards')
@section('page_subtitle', 'Buy & send gift cards')

@push('page_styles')
@vite('resources/css/public/gift-cards-index.css')
@endpush

@section('content')
<div class="gc-wrap">

    {{-- Hero --}}
    <div class="gc-hero">
        <div class="gc-hero-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="7" width="18" height="13" rx="2"/><path d="M8 7V5a2 2 0 012-2h4a2 2 0 012 2v2"/><line x1="12" y1="12" x2="12" y2="17"/><line x1="9.5" y1="14.5" x2="14.5" y2="14.5"/></svg>
        </div>
        <div class="gc-hero-body">
            <h1>Gift the power of giving</h1>
            <p>Send a digital gift card — the recipient donates to any campaign they love.</p>
        </div>
        <div class="gc-hero-extra">
            <a href="{{ route('gift-cards.redeem') }}">Redeem a card →</a>
        </div>
    </div>

    <div class="gc-cols">

        {{-- Left column — Form --}}
        <div class="gc-left">

            {{-- Theme picker --}}
            <div class="gc-card">
                <div class="gc-card-hdr">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="4"/><line x1="21.17" y1="8" x2="12" y2="8"/><line x1="3.95" y1="6.06" x2="8.54" y2="14"/><line x1="10.88" y1="21.94" x2="15.46" y2="14"/></svg>
                    <span class="gc-card-label">Card design</span>
                </div>
                <div class="gc-theme-grid" id="themeGrid">
                    @foreach(['purple'=>['bg'=>'#EEEDFE','text'=>'#26215C','brand'=>'#3C3489'],'teal'=>['bg'=>'#E1F5EE','text'=>'#04342C','brand'=>'#085041'],'coral'=>['bg'=>'#FAECE7','text'=>'#4A1B0C','brand'=>'#712B13'],'blue'=>['bg'=>'#E6F1FB','text'=>'#042C53','brand'=>'#0C447C']] as $theme => $t)
                    <div data-action="select-theme" data-theme="{{ $theme }}" id="card-{{ $theme }}" class="gc-theme-swatch {{ $theme === 'purple' ? 'selected' : '' }}"
                         style="background:{{ $t['bg'] }};">
                        <div class="gc-theme-brand" style="color:{{ $t['brand'] }};">DONATEBAZAAR</div>
                        <div class="gc-theme-amt" style="color:{{ $t['text'] }};" id="preview-amt-{{ $theme }}">₹500</div>
                        <div class="gc-theme-tag" style="color:{{ $t['text'] }};">Gift Card</div>
                        <div id="check-{{ $theme }}" class="gc-theme-check">
                            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Amount --}}
            <div class="gc-card">
                <div class="gc-card-hdr">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="gc-card-label">Amount</span>
                </div>
                <div class="gc-amt-pills" id="amtPills">
                    @foreach([100,250,500,1000,2000,5000,10000,20000] as $a)
                    <x-button variant="secondary" type="button" class="gc-amt-pill {{ $a===500 ? 'active' : '' }}" data-action="set-amt" data-amt="{{ $a }}">
                        ₹{{ number_format($a) }}
                    </x-button>
                    @endforeach
                </div>
                <div class="gc-custom-row">
                    <span>Custom:</span>
                    <div class="gc-field" style="flex:1;">
                        <input type="number" id="customAmt" placeholder="Enter ₹ amount" min="100" data-action="custom-amt">
                    </div>
                </div>
            </div>

            {{-- Details --}}
            <div class="gc-card">
                <div class="gc-card-hdr">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <span class="gc-card-label">Details</span>
                </div>
                <div class="gc-field-row">
                    <div class="gc-field">
                        <label>Your name *</label>
                        <input type="text" id="senderName" placeholder="Your name">
                    </div>
                    <div class="gc-field">
                        <label>Your email *</label>
                        <input type="email" id="senderEmail" placeholder="your@email.com">
                    </div>
                    <div class="gc-field">
                        <label>Recipient name *</label>
                        <input type="text" id="recipientName" placeholder="Their name" data-action="live-preview">
                    </div>
                    <div class="gc-field">
                        <label>Recipient email *</label>
                        <input type="email" id="recipientEmail" placeholder="their@email.com">
                    </div>
                </div>
                <div class="gc-field" style="margin-bottom:10px;">
                    <label>Personal message</label>
                    <textarea id="gcMessage" placeholder="Write a heartfelt message…" rows="2" data-action="live-preview"></textarea>
                </div>
                <div class="gc-field">
                    <label>Send on date</label>
                    <input type="date" id="sendAt">
                </div>
            </div>

        </div>

        {{-- Right column — Preview + Trust --}}
        <div class="gc-right">
            <div class="gc-card">
                <div class="gc-card-hdr">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                    <span class="gc-card-label">Preview</span>
                </div>
                <div id="liveCard" class="gc-preview-card" style="background:#EEEDFE;">
                    <div class="gc-preview-brand" style="color:#3C3489;">DONATEBAZAAR</div>
                    <div id="liveAmt" class="gc-preview-amt" style="color:#26215C;">₹500</div>
                    <div id="liveTo" class="gc-preview-to" style="color:#26215C;">For: —</div>
                    <div class="gc-preview-code" style="color:#26215C;">DNBZ-XXXX-XXXX</div>
                    <div class="gc-preview-sticker" style="color:#26215C;"></div>
                    <div class="gc-preview-sticker2" style="color:#26215C;"></div>
                </div>
                <div id="liveMsg" class="gc-preview-msg">Your message will appear here.</div>
            </div>

            <div class="gc-trust-row">
                <span class="gc-trust-chip">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                    Secure payment
                </span>
                <span class="gc-trust-chip">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    Instant delivery
                </span>
                <span class="gc-trust-chip">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 11-.71-8"/></svg>
                    Never expires
                </span>
            </div>
        </div>

    </div>

    {{-- Buy button --}}
    <div class="gc-buy-wrap">
        <x-button variant="primary" type="button" id="buyBtn" data-action="initiate-purchase" class="gc-buy-btn">
            Purchase &amp; Send Gift Card — ₹<span id="btnAmt">500</span>
        </x-button>
    </div>

    {{-- Mobile redeem link --}}
    <p class="gc-foot-mobile">
        Already have a gift card?
        <a href="{{ route('gift-cards.redeem') }}">Redeem it here</a>
    </p>

</div>
@endsection

@push('page_scripts')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script type="application/json" id="giftCardsData">
@php
    $giftCardsData = [
        'orderUrl' => route('gift-cards.order'),
        'verifyUrl' => route('gift-cards.verify'),
        'csrfToken' => csrf_token(),
    ];
@endphp
@json($giftCardsData)
</script>
@vite('resources/js/public/gift-cards-index.js')
@endpush