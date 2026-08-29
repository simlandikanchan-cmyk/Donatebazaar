<?php

namespace App\Http\Controllers;

use App\Models\LegalPage;

class LegalController extends Controller
{
    public function privacy()
    {
        return $this->show('privacy-policy');
    }

    public function terms()
    {
        return $this->show('terms-of-service');
    }

    public function refund()
    {
        return $this->show('refund-policy');
    }

    public function cookies()
    {
        return $this->show('cookie-policy');
    }

    public function donor()
    {
        return $this->show('donor-policy');
    }

    public function campaign()
    {
        return $this->show('campaign-policy');
    }

    public function kyc()
    {
        return $this->show('kyc-policy');
    }

    public function grievance()
    {
        return $this->show('grievance-policy');
    }

    public function acceptableUse()
    {
        return $this->show('acceptable-use-policy');
    }

    public function payment()
    {
        return $this->show('payment-policy');
    }

    protected function show(string $slug)
    {
        $page = LegalPage::where('slug', $slug)->first();

        if ($page) {
            return view('legal.show', compact('page'));
        }

        // Fall back to the static template view shipped with the app.
        return view('legal.'.$slug);
    }
}
