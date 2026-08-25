<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Services\DonationReceiptService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DonationReceiptController extends Controller
{
    public function __construct(
        private DonationReceiptService $receiptService
    ) {}

    public function download(Request $request, Donation $donation)
    {
        if (! $this->receiptService->isReceiptAvailable($donation)) {
            abort(403, 'This receipt is not available.');
        }

        if (Auth::check() && ! $this->receiptService->isAuthorized($donation, Auth::user())) {
            abort(403, 'You are not authorized to download this receipt.');
        }

        $data = $this->receiptService->data($donation, withUrls: false);

        $pdf = Pdf::loadView('receipts.donation-pdf', $data)
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => false,
            ]);

        return $pdf->download($this->receiptService->receiptFileName($donation));
    }
}
