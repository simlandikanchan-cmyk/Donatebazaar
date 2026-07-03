<section class="testimonials-section">
    <div class="container">
        <div class="section-header" style="text-align:left;">
            <div class="section-eyebrow">Love from our community</div>
            <h2 class="section-title">Testimonials</h2>
        </div>
        <div class="tab-btns">
            <button class="tab-btn active" onclick="switchTab('donors', this)">Donors</button>
            <button class="tab-btn" onclick="switchTab('ngos', this)">NGOs</button>
            <button class="tab-btn" onclick="switchTab('celebs', this)">Celebrities</button>
        </div>

        <div id="donors" class="testi-tab active">
            <div class="testi-track"><div class="testi-slider" id="slider-donors">
                @for($i=1;$i<=10;$i++)
                <div class="testi-card">
                    <div class="testi-quote">"</div>
                    <span class="testi-badge badge-blue">Contributed {{ $i+2 }} Times</span>
                    <p class="testi-text">Donating here makes me happy. Helping others is the greatest joy I have experienced on this platform.</p>
                    <div class="testi-author">
                        <div class="testi-avatar-initials" style="background:{{ ['#6366f1','#10b981','#f59e0b','#ef4444','#8b5cf6','#ec4899','#14b8a6','#f97316','#06b6d4','#84cc16'][($i-1)%10] }}">D</div>
                        <div><div class="testi-name">Donor {{ $i }}</div><div class="testi-role">Supporter</div></div>
                    </div>
                </div>
                @endfor
            </div></div>
        </div>

        <div id="ngos" class="testi-tab">
            <div class="testi-track"><div class="testi-slider" id="slider-ngos">
                @for($i=1;$i<=10;$i++)
                <div class="testi-card">
                    <div class="testi-quote">"</div>
                    <span class="testi-badge badge-green">NGO Partner</span>
                    <p class="testi-text">This platform helps NGOs reach donors easily. The verification process builds genuine trust with supporters.</p>
                    <div class="testi-author">
                        <div class="testi-avatar-initials" style="background:{{ ['#6366f1','#10b981','#f59e0b','#ef4444','#8b5cf6','#ec4899','#14b8a6','#f97316','#06b6d4','#84cc16'][($i-1)%10] }}">N</div>
                        <div><div class="testi-name">NGO {{ $i }}</div><div class="testi-role">Organization</div></div>
                    </div>
                </div>
                @endfor
            </div></div>
        </div>

        <div id="celebs" class="testi-tab">
            <div class="testi-track"><div class="testi-slider" id="slider-celebs">
                @for($i=1;$i<=10;$i++)
                <div class="testi-card">
                    <div class="testi-quote">"</div>
                    <span class="testi-badge badge-purple">Celebrity Supporter</span>
                    <p class="testi-text">Giving back to society is important. This platform makes it easy to contribute meaningfully.</p>
                    <div class="testi-author">
                        <div class="testi-avatar-initials" style="background:{{ ['#6366f1','#10b981','#f59e0b','#ef4444','#8b5cf6','#ec4899','#14b8a6','#f97316','#06b6d4','#84cc16'][($i-1)%10] }}">C</div>
                        <div><div class="testi-name">Celebrity {{ $i }}</div><div class="testi-role">Influencer</div></div>
                    </div>
                </div>
                @endfor
            </div></div>
        </div>
    </div>
</section>


