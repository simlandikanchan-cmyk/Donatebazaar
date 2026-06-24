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
                ['initial'=>'A','name'=>'Soumik Banerjee','role'=>'Co-Founder & CEO','bio'=>'Former TATA Trust impact director with 12 years in social finance and nonprofit technology.','grad'=>'135deg,#6366f1,#8b5cf6'],
                ['initial'=>'S','name'=>'Vikash Das','role'=>'Co-Founder & CTO','bio'=>'Ex-Razorpay payments engineer passionate about making secure fintech accessible to NGOs.','grad'=>'135deg,#10b981,#059669'],
                ['initial'=>'V','name'=>'Vikram Thakur','role'=>'Head of Trust & Safety','bio'=>'Former RBI compliance officer ensuring every rupee on DonateBazaar is protected.','grad'=>'135deg,#f59e0b,#d97706'],
            ];
            @endphp

            @foreach($team as $member)
            <div class="team-card reveal d{{ $loop->iteration }}">
                <div class="team-img-wrap">
                    <div class="team-avatar-placeholder" style="background:linear-gradient({{ $member['grad'] }})">
                        {{ $member['initial'] }}
                    </div>
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