<div class="faq-section anim anim-4">
        <div class="section-header">
            <div class="section-tag">FAQ</div>
            <h2>Frequently Asked <span class="dim">Questions</span></h2>
            <p>Everything you need to know about donating on DonateBazaar</p>
        </div>

        <div class="faq-grid">
            @foreach([
                'How can I make a donation?' => 'You can donate securely via UPI, credit/debit cards, net banking, and other trusted payment gateways. Just visit any active campaign and click the Donate button.',
                'Is my donation 100% secure?' => 'Absolutely. All transactions are end-to-end encrypted and processed through PCI-DSS compliant gateways. Your financial data is never stored on our servers.',
                'Where does my money go?' => 'Every rupee goes directly to the verified campaign you choose. We publish transparent fund allocation reports accessible to all donors at any time.',
                'Can I track my donation?' => 'Yes! Once logged in, your dashboard shows all donations, downloadable receipts, and real-time progress updates from the campaigns you\'ve supported.',
                'Can I get a tax receipt?' => 'Yes, all donations qualify for 80G tax deductions. Your certificate is auto-generated and emailed to your registered address within minutes.',
                'How do I contact support urgently?' => 'For urgent matters, call us at +91 98765 43210 or email info@donatebazar.com. Our team responds within 2 hours on business days.',
            ] as $question => $answer)
            <div class="faq-item">
                <button class="faq-q" onclick="toggleFAQ(this)" type="button">
                    {{ $question }}
                    <span class="faq-icon">
                        <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="5" x2="12" y2="19"/>
                            <line x1="5" y1="12" x2="19" y2="12"/>
                        </svg>
                    </span>
                </button>
                <div class="faq-answer">
                    <div class="faq-answer-inner">{{ $answer }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
