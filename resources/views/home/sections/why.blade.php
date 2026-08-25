<section class="why-section section-gradient">
    <div class="container">
        <div class="section-header">
            <div class="section-eyebrow">6 Reasons of assurance</div>
            <h2 class="section-title">Why DonateBazaar?</h2>
            <p class="section-sub">Trusted platform for transparent, secure, and impactful donations.</p>
        </div>
        <div class="why-grid">
            @php
            $reasons = [
                ['icon'=>'loyalty-program.png','title'=>'Product Giving',    'desc'=>'Make your impact tangible by donating products directly.'],
                ['icon'=>'verify.png',          'title'=>'Verified & Trusted','desc'=>'Support charities through strict verification processes.'],
                ['icon'=>'rotation.png',        'title'=>'Guaranteed Updates','desc'=>'Stay informed with regular campaign progress updates.'],
                ['icon'=>'easy-return.png',     'title'=>'Easy Setup',        'desc'=>'Launch your fundraiser in just a few minutes.'],
                ['icon'=>'lock.png',            'title'=>'Secure & Private',  'desc'=>'Encrypted payments and protected donor data always.'],
                ['icon'=>'support.png',         'title'=>'24×7 Support',      'desc'=>'Our team is always here to help you succeed.'],
            ];
            @endphp
            @foreach($reasons as $r)
            <div class="why-card">
                <div class="why-icon">
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
