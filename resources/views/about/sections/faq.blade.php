<section class="faq-section">
    <div class="container">
        <div class="faq-layout">
            <div class="faq-sticky">
                <div class="eyebrow reveal">FAQs</div>
                <h2 class="section-title reveal d1">Questions <em>donors</em> ask us most</h2>
                <p class="reveal d2">We believe in full transparency — about our process, our fees, and our purpose. Here are the answers to what matters most.</p>
                <div style="margin-top:28px" class="reveal d3">
                    <a href="{{ url('/contact') }}" class="btn btn-accent">
                        Ask Us Anything
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>

            <div class="faq-list reveal d2">
                @php
                $faqs = [
                    ['q'=>'How does DonateBazaar verify campaigns?','a'=>'Every campaign goes through a 3-step verification process: document review (ID, cause proof), identity validation (video call or in-person visit for high-value campaigns), and periodic audits during the fundraising period. Only 100% verified campaigns go live.'],
                    ['q'=>'Are there any hidden fees on my donation?','a'=>'No. We show a complete fee breakdown before you confirm your donation. You see exactly how much goes to the cause and what (if any) platform fee applies. For many campaigns, donors can choose to cover the fee so 100% reaches the cause.'],
                    ['q'=>'How do I get my 80G tax exemption certificate?','a'=>'For all eligible campaigns, your 80G certificate is automatically generated and emailed to you within 24 hours of your donation. It is always available in your donor dashboard as well. You can use it to claim a 50% deduction on your taxable income.'],
                    ['q'=>'Is my payment information secure?','a'=>'Absolutely. All transactions use 256-bit SSL encryption. We are PCI-DSS compliant and we never store your card or bank details on our servers. Payments are processed through RBI-authorised payment gateways only.'],
                    ['q'=>'Can I get a refund if a campaign is found to be fraudulent?','a'=>'Yes. In the unlikely event that a campaign is found to be fraudulent after funds are released, DonateBazaar\'s Donor Protection Fund covers refunds. We take campaign authenticity extremely seriously and our track record speaks for itself.'],
                    ['q'=>'How are campaign funds transferred to beneficiaries?','a'=>'Funds are transferred in milestone-based tranches — not all at once. This ensures campaign creators use the money as promised. Each transfer is logged and donors receive notifications when funds are disbursed.'],
                ];
                @endphp

                @foreach($faqs as $i => $faq)
                <div class="faq-item" data-faq="{{ $i }}">
                    <div class="faq-q" onclick="toggleFaq({{ $i }})">
                        <span class="faq-q-text">{{ $faq['q'] }}</span>
                        <div class="faq-chevron">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                        </div>
                    </div>
                    <div class="faq-a">
                        <div class="faq-a-inner">{{ $faq['a'] }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
