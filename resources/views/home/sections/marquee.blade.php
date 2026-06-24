<div class="marquee-wrap">
    <div class="marquee-track">
        @php $items = ['Trusted by 2.5 Million Donors','10,000+ Verified NGOs','Regular Updates','Multiple Causes','Product Giving','Secure Payments','24x7 Support']; @endphp
        @for($r=0;$r<2;$r++)
            @foreach($items as $item)
                <span class="marquee-item"><span class="marquee-dot"></span>{{ $item }}</span>
            @endforeach
        @endfor
    </div>
</div>
