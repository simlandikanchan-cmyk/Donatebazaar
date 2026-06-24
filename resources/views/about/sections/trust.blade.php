<section class="trust-section">
    <div class="container">
        <div class="section-header">
            <div class="eyebrow reveal" style="justify-content:center">Why trust us</div>
            <h2 class="section-title reveal d1">Built on <em>Integrity</em> &amp; Accountability</h2>
            <p class="section-sub reveal d2">Every feature of our platform is designed around one goal — making sure your donation reaches the right person, at the right time, every time.</p>
        </div>

        @php
        $pillars = [
            ['bg'=>'#ede9fe','color'=>'#6366f1','tc'=>'#6366f1','title'=>'Rigorous Verification','desc'=>'Every campaign undergoes multi-step checks — document verification, identity validation, and periodic audits to ensure 100% authenticity.','badge'=>'ISO Certified Process','svg'=>'<path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/>'],
            ['bg'=>'#d1fae5','color'=>'#10b981','tc'=>'#10b981','title'=>'Bank-Grade Security','desc'=>'256-bit SSL encryption on every transaction. Your payment details are never stored on our servers — ever.','badge'=>'PCI-DSS Compliant','svg'=>'<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>'],
            ['bg'=>'#fef3c7','color'=>'#f59e0b','tc'=>'#f59e0b','title'=>'Real-time Updates','desc'=>'Campaign creators post regular photo and video updates. Know exactly how and when your donation creates impact on the ground.','badge'=>'Live Tracking','svg'=>'<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>'],
            ['bg'=>'#ede9fe','color'=>'#8b5cf6','tc'=>'#8b5cf6','title'=>'Zero Hidden Fees','desc'=>'We are completely transparent about platform costs. You see exactly how much reaches the cause before you click donate.','badge'=>'Full Breakdown','svg'=>'<line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/>'],
            ['bg'=>'#dbeafe','color'=>'#3b82f6','tc'=>'#3b82f6','title'=>'80G Tax Benefits','desc'=>'All eligible donations come with 80G tax exemption certificates automatically sent to your registered email. Save up to 50% on taxes.','badge'=>'Auto Tax Certificate','svg'=>'<path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/>'],
            ['bg'=>'#d1fae5','color'=>'#059669','tc'=>'#059669','title'=>'24×7 Donor Support','desc'=>'Our dedicated team is available around the clock via chat, email, and phone — for donors and campaign creators alike.','badge'=>'Avg. 4 min response','svg'=>'<path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/>'],
        ];
        @endphp

        <div class="trust-grid">
            @foreach($pillars as $p)
            <div class="trust-card reveal d{{ ($loop->index % 3) + 1 }}" style="--tc-color:{{ $p['tc'] }}">
                <div class="trust-icon-wrap" style="background:{{ $p['bg'] }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="{{ $p['color'] }}" stroke-width="2">{!! $p['svg'] !!}</svg>
                </div>
                <div>
                    <div class="trust-card-title">{{ $p['title'] }}</div>
                    <div class="trust-card-desc">{{ $p['desc'] }}</div>
                    <div class="trust-card-badge">
                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="{{ $p['color'] }}" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                        {{ $p['badge'] }}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
