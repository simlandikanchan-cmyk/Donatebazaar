<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Support\Collection;

class FaqController extends Controller
{
    public function index()
    {
        if (Faq::count() > 0) {
            $faqs = Faq::active()
                ->ordered()
                ->get()
                ->groupBy('category')
                ->map(function ($items) {
                    return $items->map(function ($faq) {
                        return ['q' => $faq->question, 'a' => $faq->answer];
                    });
                });
        } else {
            $faqs = $this->defaults();
        }

        return view('faq.index', compact('faqs'));
    }

    protected function defaults(): Collection
    {
        $data = [
            'Getting Started' => [
                ['q' => 'What is DonateBazaar?', 'a' => 'DonateBazaar is a crowdfunding platform that connects donors with verified campaigns across various categories including medical, education, disaster relief, and community projects. We make it easy to start a campaign or donate to a cause you care about.'],
                ['q' => 'How do I create a campaign?', 'a' => 'Sign up for a free account, complete your KYC verification, and click "Start a Campaign" from your dashboard. Fill in your campaign details including title, description, goal amount, and cover image. Once submitted, our team reviews and approves campaigns within 24-48 hours.'],
                ['q' => 'Is there a fee to start a campaign?', 'a' => 'Creating a campaign is completely free. We only charge a small platform fee on donations received, which helps us maintain the platform, process payments securely, and provide support to campaign organizers.'],
                ['q' => 'Who can start a campaign?', 'a' => 'Any individual or organization with a valid KYC verification can start a campaign. We verify all campaign organizers to ensure transparency and trust.'],
            ],
            'Donations' => [
                ['q' => 'How do I make a donation?', 'a' => 'Browse campaigns on our platform, select one you\'d like to support, and click "Donate Now". You can choose from various payment methods including credit/debit cards, UPI, net banking, and more.'],
                ['q' => 'Is my donation secure?', 'a' => 'Absolutely. All payments are processed through Razorpay, a PCI-DSS compliant payment gateway. Your payment information is encrypted and never stored on our servers.'],
                ['q' => 'Can I get a refund?', 'a' => 'Donations are generally non-refundable as they are transferred to campaign organizers. However, if a campaign is fraudulent or fails to meet its stated goals, please contact our support team and we will review your case.'],
                ['q' => 'Do I get a receipt for my donation?', 'a' => 'Yes, a detailed donation receipt is generated automatically for every completed donation. You can download it from your donation history in your account dashboard.'],
                ['q' => 'Can I donate anonymously?', 'a' => 'Yes, you can choose to make your donation anonymous during the checkout process. Your name will not be displayed on the campaign page.'],
            ],
            'Campaign Management' => [
                ['q' => 'How do I withdraw funds from my campaign?', 'a' => 'Funds are automatically settled to your registered bank account after KYC verification. The settlement process typically takes 3-5 business days after a donation is completed.'],
                ['q' => 'Can I edit my campaign after it\'s live?', 'a' => 'Yes, you can edit your campaign details including title, description, images, and goal amount from your campaign management dashboard.'],
                ['q' => 'What happens if my campaign doesn\'t reach its goal?', 'a' => 'Unlike some platforms, we believe every contribution makes a difference. You keep all funds raised regardless of whether you reach your goal.'],
                ['q' => 'How do I promote my campaign?', 'a' => 'Share your campaign link on social media, embed it on your website, and use our built-in tools to send updates to your donors. We also feature outstanding campaigns on our homepage and social media channels.'],
            ],
            'Account & Support' => [
                ['q' => 'How do I reset my password?', 'a' => 'Click "Forgot Password" on the login page and enter your registered email. We\'ll send you a password reset link within minutes.'],
                ['q' => 'How do I contact support?', 'a' => 'You can reach our support team through the Contact page, email us at support@donatebazaar.com, or use the live chat feature available on our website during business hours.'],
                ['q' => 'Is my personal information safe?', 'a' => 'Yes, we follow industry-standard security practices to protect your data. Please refer to our Privacy Policy for detailed information about how we handle your personal information.'],
            ],
        ];

        return collect($data)->map(function ($items, $category) {
            return collect($items)->map(function ($item, $i) use ($category) {
                return new Faq([
                    'category' => $category,
                    'question' => $item['q'],
                    'answer' => $item['a'],
                    'sort_order' => $i,
                    'is_active' => true,
                ]);
            });
        });
    }
}
