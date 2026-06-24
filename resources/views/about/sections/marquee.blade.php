<div class="marquee-band">
    <div class="marquee-inner">
        <div class="marquee-row">
            @php $items = ['Trusted by 50,000+ Donors', '2,000+ Verified Campaigns', '₹10 Crore+ Raised', 'Pan-India Coverage', 'RBI-Compliant Payments', '256-bit SSL Encryption', '24×7 Donor Support', '100% Transparency Guaranteed', 'Featured in 15+ National Media']; @endphp
            @for($r=0;$r<3;$r++)
                @foreach($items as $item)
                    <span class="m-item"><span class="m-dot"></span>{{ $item }}</span>
                @endforeach
            @endfor
        </div>
    </div>
</div>