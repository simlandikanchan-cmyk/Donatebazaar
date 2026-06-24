<section class="why-section">
    <div class="container">
        <div class="section-header">
            <div class="section-eyebrow">6 Reasons of assurance</div>
            <h2 class="section-title">Why DonateBazar?</h2>
            <p class="section-sub">Trusted platform for transparent, secure, and impactful donations.</p>
        </div>
        <div class="why-grid">
            @php
            $reasons = [
                ['icon'=>'loyalty-program.png','color'=>'#fef3c7','title'=>'Product Giving',    'desc'=>'Make your impact tangible by donating products directly.'],
                ['icon'=>'verify.png',          'color'=>'#d1fae5','title'=>'Verified & Trusted','desc'=>'Support charities through strict verification processes.'],
                ['icon'=>'rotation.png',        'color'=>'#dbeafe','title'=>'Guaranteed Updates','desc'=>'Stay informed with regular campaign progress updates.'],
                ['icon'=>'easy-return.png',     'color'=>'#ede9fe','title'=>'Easy Setup',        'desc'=>'Launch your fundraiser in just a few minutes.'],
                ['icon'=>'lock.png',            'color'=>'#fee2e2','title'=>'Secure & Private',  'desc'=>'Encrypted payments and protected donor data always.'],
                ['icon'=>'support.png',         'color'=>'#e0e7ff','title'=>'24×7 Support',      'desc'=>'Our team is always here to help you succeed.'],
            ];
            @endphp
            @foreach($reasons as $r)
            <div class="why-card">
                <div class="why-icon" style="background:{{ $r['color'] }};">
                    <img src="{{ asset('images/' . $r['icon']) }}" alt="{{ $r['title'] }}">
                </div>
                <div>
                    <div class="why-title">{{ $r['title'] }}</div>
                    <div class="why-desc">{{ $r['desc'] }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
