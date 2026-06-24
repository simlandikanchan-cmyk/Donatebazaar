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

        <div id="donors" class="testi-tab">
            <div class="testi-track"><div class="testi-slider" id="slider-donors">
                @for($i=1;$i<=10;$i++)
                <div class="testi-card">
                    <div class="testi-quote">"</div>
                    <span class="testi-badge badge-blue">Contributed {{ $i+2 }} Times</span>
                    <p class="testi-text">Donating here makes me happy. Helping others is the greatest joy I have experienced on this platform.</p>
                    <div class="testi-author">
                        <img src="https://i.pravatar.cc/40?img={{ $i }}" class="testi-avatar" alt="">
                        <div><div class="testi-name">Donor {{ $i }}</div><div class="testi-role">Supporter</div></div>
                    </div>
                </div>
                @endfor
            </div></div>
        </div>

        <div id="ngos" class="testi-tab" style="display:none;">
            <div class="testi-track"><div class="testi-slider" id="slider-ngos">
                @for($i=1;$i<=10;$i++)
                <div class="testi-card">
                    <div class="testi-quote">"</div>
                    <span class="testi-badge badge-green">NGO Partner</span>
                    <p class="testi-text">This platform helps NGOs reach donors easily. The verification process builds genuine trust with supporters.</p>
                    <div class="testi-author">
                        <img src="https://i.pravatar.cc/40?img={{ $i+5 }}" class="testi-avatar" alt="">
                        <div><div class="testi-name">NGO {{ $i }}</div><div class="testi-role">Organization</div></div>
                    </div>
                </div>
                @endfor
            </div></div>
        </div>

        <div id="celebs" class="testi-tab" style="display:none;">
            <div class="testi-track"><div class="testi-slider" id="slider-celebs">
                @for($i=1;$i<=10;$i++)
                <div class="testi-card">
                    <div class="testi-quote">"</div>
                    <span class="testi-badge badge-purple">Celebrity Supporter</span>
                    <p class="testi-text">Giving back to society is important. This platform makes it easy to contribute meaningfully.</p>
                    <div class="testi-author">
                        <img src="https://i.pravatar.cc/40?img={{ $i+10 }}" class="testi-avatar" alt="">
                        <div><div class="testi-name">Celebrity {{ $i }}</div><div class="testi-role">Influencer</div></div>
                    </div>
                </div>
                @endfor
            </div></div>
        </div>
    </div>
</section>


