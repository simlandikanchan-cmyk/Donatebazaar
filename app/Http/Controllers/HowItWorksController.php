<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;

class HowItWorksController extends Controller
{
    public function index()
    {
        $viewData = Cache::rememberForever('hiw_page_data', fn() => [
            'donorSteps'      => $this->donorSteps(),
            'fundraiserSteps' => $this->fundraiserSteps(),
            'trustPillars'    => $this->trustPillars(),
            'faqsDonors'      => $this->faqsDonors(),
            'faqsFundraisers' => $this->faqsFundraisers(),
            'stats'           => $this->stats(),
        ]);

        return view('how-it-works', $viewData);
    }

    private function donorSteps(): array
    {
        return [
            [
                'number' => '01',
                'icon'   => 'search',
                'title'  => 'Browse Campaigns',
                'desc'   => 'Explore hundreds of verified campaigns across medical, education, disaster relief, and more. Use filters to find the cause closest to your heart.',
                'color'  => '#6366f1',
                'bg'     => 'rgba(99,102,241,.1)',
            ],
            [
                'number' => '02',
                'icon'   => 'heart',
                'title'  => 'Choose Your Cause',
                'desc'   => 'Read the campaign story, see real-time updates, and verify the documents. Know exactly where every rupee will go before you donate.',
                'color'  => '#10b981',
                'bg'     => 'rgba(16,185,129,.1)',
            ],
            [
                'number' => '03',
                'icon'   => 'credit-card',
                'title'  => 'Donate Securely',
                'desc'   => 'Pay via UPI, debit/credit card, or net banking. All transactions are 256-bit SSL encrypted via RBI-authorised, PCI-DSS compliant gateways.',
                'color'  => '#3b82f6',
                'bg'     => 'rgba(59,130,246,.1)',
            ],
            [
                'number' => '04',
                'icon'   => 'activity',
                'title'  => 'Track Your Impact',
                'desc'   => 'Get live photo and video updates directly from the campaign. View your full donation history and download your 80G tax certificate anytime.',
                'color'  => '#f59e0b',
                'bg'     => 'rgba(245,158,11,.1)',
            ],
        ];
    }

    private function fundraiserSteps(): array
    {
        return [
            [
                'number' => '01',
                'icon'   => 'edit',
                'title'  => 'Create Your Campaign',
                'desc'   => 'Fill in your campaign title, goal amount, description, and upload a cover image. Add products donors can buy, and set a timeline. Takes less than 5 minutes.',
                'color'  => '#6366f1',
                'bg'     => 'rgba(99,102,241,.1)',
            ],
            [
                'number' => '02',
                'icon'   => 'shield',
                'title'  => 'KYC Verification',
                'desc'   => 'Submit your KYC documents (Aadhaar, PAN, bank details). Our dedicated team reviews and approves your campaign within 24 hours.',
                'color'  => '#10b981',
                'bg'     => 'rgba(16,185,129,.1)',
            ],
            [
                'number' => '03',
                'icon'   => 'zap',
                'title'  => 'Go Live & Share',
                'desc'   => 'Once approved your campaign goes live instantly. Share it on WhatsApp, Instagram, and email. Every share multiplies your reach and donations.',
                'color'  => '#f59e0b',
                'bg'     => 'rgba(245,158,11,.1)',
            ],
            [
                'number' => '04',
                'icon'   => 'trending-up',
                'title'  => 'Withdraw Funds',
                'desc'   => 'Submit a withdrawal request with bills or receipts. Funds are transferred directly to your verified bank account within 3–5 working days.',
                'color'  => '#8b5cf6',
                'bg'     => 'rgba(139,92,246,.1)',
            ],
        ];
    }

    private function trustPillars(): array
    {
        return [
            [
                'icon'  => 'shield',
                'color' => '#6366f1',
                'bg'    => 'rgba(99,102,241,.1)',
                'title' => 'Verified Campaigns',
                'desc'  => 'Every campaign undergoes strict multi-step KYC verification before going live. We verify identity, cause legitimacy, and bank details.',
            ],
            [
                'icon'  => 'lock',
                'color' => '#10b981',
                'bg'    => 'rgba(16,185,129,.1)',
                'title' => 'Secure Payments',
                'desc'  => '256-bit SSL encryption. RBI-authorised payment gateways. PCI-DSS compliant. Your card details are never stored on our servers.',
            ],
            [
                'icon'  => 'eye',
                'color' => '#3b82f6',
                'bg'    => 'rgba(59,130,246,.1)',
                'title' => '100% Transparent',
                'desc'  => 'Every rupee is tracked with a full disbursement log. Campaigners post photo and video updates that donors can see in real time.',
            ],
            [
                'icon'  => 'file-text',
                'color' => '#f59e0b',
                'bg'    => 'rgba(245,158,11,.1)',
                'title' => '80G Tax Benefits',
                'desc'  => 'Your 80G certificate is auto-generated and emailed within 24 hours of donating — always available in your donor dashboard.',
            ],
            [
                'icon'  => 'refresh-cw',
                'color' => '#ef4444',
                'bg'    => 'rgba(239,68,68,.1)',
                'title' => 'Donor Protection',
                'desc'  => 'If a campaign is found fraudulent, our Donor Protection Fund covers your full refund. No questions asked.',
            ],
            [
                'icon'  => 'headphones',
                'color' => '#8b5cf6',
                'bg'    => 'rgba(139,92,246,.1)',
                'title' => '24×7 Support',
                'desc'  => 'Our dedicated team is available round the clock to help both donors and fundraisers — by chat, email, or phone.',
            ],
        ];
    }

    private function faqsDonors(): array
    {
        return [
            [
                'q' => 'Is my donation 100% secure?',
                'a' => 'Yes. All payments use 256-bit SSL encryption and go through RBI-authorised, PCI-DSS compliant payment gateways. We never store your card details on our servers.',
            ],
            [
                'q' => 'How do I get my 80G certificate?',
                'a' => 'Your 80G certificate is automatically generated and emailed to you within 24 hours of your donation. It is always available in your donor dashboard under "My Donations".',
            ],
            [
                'q' => 'Can I set up recurring donations?',
                'a' => 'Absolutely. Choose Weekly or Monthly giving on any campaign page. There is no long-term commitment — you can cancel anytime from My Dashboard → Recurring Donations.',
            ],
            [
                'q' => 'What happens if a campaign is fraudulent?',
                'a' => 'Every campaign is verified before going live. In the rare event of fraud our Donor Protection Fund guarantees a full refund — no questions asked.',
            ],
            [
                'q' => 'How do I track my donation?',
                'a' => 'Log in to your dashboard. Under "My Donations" you will see a real-time disbursement log, campaign updates, and photo/video reports from the fundraiser team.',
            ],
            [
                'q' => 'Is UPI accepted?',
                'a' => 'Yes. We accept UPI, debit cards, credit cards, net banking, and wallets. All payment modes are available on the donation page.',
            ],
        ];
    }

    private function faqsFundraisers(): array
    {
        return [
            [
                'q' => 'How do I start a campaign?',
                'a' => 'Click "Start a Campaign", fill in the details (title, goal, description, cover image), submit KYC documents, and our team approves your campaign within 24 hours.',
            ],
            [
                'q' => 'What documents are needed for KYC?',
                'a' => 'You will need a government-issued ID (Aadhaar/PAN), address proof, a selfie, and bank account details for fund withdrawal.',
            ],
            [
                'q' => 'How long does approval take?',
                'a' => 'Our team reviews and approves campaigns within 24 hours of receiving complete KYC documents. You will receive an email notification as soon as it goes live.',
            ],
            [
                'q' => 'How and when can I withdraw funds?',
                'a' => 'Submit a withdrawal request from your dashboard along with relevant bills or receipts. Funds are transferred to your verified bank account within 3–5 working days.',
            ],
            [
                'q' => 'Is there any fee to create a campaign?',
                'a' => 'Creating a campaign is completely free. DonateBazaar charges a nominal platform fee on successful donations — fully disclosed and transparent before withdrawal.',
            ],
            [
                'q' => 'Can I add products to my campaign?',
                'a' => 'Yes. Our unique Product Giving feature lets you list physical products (stationery, food kits, medical supplies, etc.) that donors can purchase directly for your cause.',
            ],
        ];
    }

    private function stats(): array
    {
        return [
            ['val' => '₹10 Cr+', 'lbl' => 'Funds Raised'],
            ['val' => '50,000+', 'lbl' => 'Donors'],
            ['val' => '2,000+',  'lbl' => 'Campaigns'],
            ['val' => '98.7%',   'lbl' => 'Success Rate'],
        ];
    }
}