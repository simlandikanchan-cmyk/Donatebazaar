<?php

namespace App\Http\Controllers;

use App\Models\LegalPage;
use Illuminate\Http\Request;

class LegalController extends Controller
{
    public function privacy()
    {
        return $this->show('privacy');
    }

    public function terms()
    {
        return $this->show('terms');
    }

    public function refund()
    {
        return $this->show('refund');
    }

    public function cookies()
    {
        return $this->show('cookies');
    }

    protected function show(string $slug)
    {
        $page = LegalPage::where('slug', $slug)->first();

        if ($page) {
            return view('legal.show', compact('page'));
        }

        // Fall back to the static template view shipped with the app.
        return view('legal.' . $slug);
    }
}
