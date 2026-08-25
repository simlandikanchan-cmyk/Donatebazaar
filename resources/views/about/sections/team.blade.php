<section class="team-section">
    <div class="container">
        <div class="section-header">
            <div class="eyebrow reveal" style="justify-content:center">The People Behind It</div>
            <h2 class="section-title reveal d1">Meet the <em>Team</em></h2>
            <p class="section-sub reveal d2">Driven by purpose, guided by values — our founding team brings together expertise in technology, social impact, and finance.</p>
        </div>

        <div class="team-grid">
            @php
            $team = [
                ['img'=>'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=600&q=80','name'=>'Soumik Banerjee','role'=>'Co-Founder & CEO','bio'=>'Former TATA Trust impact director with 12 years in social finance and nonprofit technology.'],
                ['img'=>'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?w=600&q=80','name'=>'Vikash Das','role'=>'Co-Founder & CTO','bio'=>'Ex-Razorpay payments engineer passionate about making secure fintech accessible to NGOs.'],
                ['img'=>'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=600&q=80','name'=>'Vikram Thakur','role'=>'Head of Trust & Safety','bio'=>'Former RBI compliance officer ensuring every rupee on DonateBazaar is protected.'],
            ];
            @endphp

            @foreach($team as $member)
            <div class="team-card reveal d{{ $loop->iteration }}">
                <div class="team-img-wrap">
                    <img src="{{ $member['img'] }}" alt="{{ $member['name'] }}" loading="lazy">
                    <div class="team-img-overlay"></div>
                </div>
                <div class="team-info">
                    <div class="team-name">{{ $member['name'] }}</div>
                    <div class="team-role">{{ $member['role'] }}</div>
                    <div class="team-bio">{{ $member['bio'] }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>