<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Services\Payment\PaymentOrderService;
use App\Services\Payment\PaymentWebhookService;
use App\Services\Payment\PaymentVerificationService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(
        private PaymentOrderService $paymentOrderService,
        private PaymentVerificationService $paymentVerificationService,
        private PaymentWebhookService $paymentWebhookService
    ) {}

    public function redirectToPayment(Request $request, Campaign $campaign)
    {
        return $this->paymentOrderService->initiateDonation($request, $campaign);
    }

    public function paymentPage(Campaign $campaign)
    {
        return $this->paymentOrderService->showPaymentPage($campaign);
    }

    public function verify(Request $request)
    {
        return $this->paymentVerificationService->verifyPayment($request);
    }

    public function webhook(Request $request)
    {
        return $this->paymentWebhookService->handleWebhook($request);
    }
}
