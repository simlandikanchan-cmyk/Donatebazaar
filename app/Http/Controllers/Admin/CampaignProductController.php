<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\CampaignProductStatusMail;
use App\Models\CampaignProduct;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class CampaignProductController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->input('status', 'pending');

        $query = CampaignProduct::with([
            'campaign:id,title,slug',
            'user:id,name,email',
            'categoryProduct:id,name',
        ]);

        if ($status !== 'all') {
            $query->where('approval_status', $status);
        }

        $products = $query->latest()->paginate(20);

        $cntPending  = CampaignProduct::where('approval_status', 'pending')->count();
        $cntApproved = CampaignProduct::where('approval_status', 'approved')->count();
        $cntRejected = CampaignProduct::where('approval_status', 'rejected')->count();
        $cntTotal    = CampaignProduct::count();

        return view('admin.campaign-products.index', compact(
            'products', 'status', 'cntPending', 'cntApproved', 'cntRejected', 'cntTotal'
        ));
    }

    public function approve(CampaignProduct $product): RedirectResponse
    {
        if ($product->approval_status !== 'pending') {
            return back()->with('error', 'Only pending products can be approved.');
        }

        $product->update([
            'approval_status' => 'approved',
            'approved_by'     => Auth::id(),
            'approved_at'     => now(),
            'is_active'       => true,
        ]);

        $admin = Auth::user();

        Mail::to($product->user)->queue(
            new CampaignProductStatusMail($product, 'approved', null, $admin)
        );

        return back()->with('success', "Product \"{$product->name}\" approved.");
    }

    public function reject(Request $request, CampaignProduct $product): RedirectResponse
    {
        if ($product->approval_status !== 'pending') {
            return back()->with('error', 'Only pending products can be rejected.');
        }

        $data = $request->validate([
            'reason' => ['required', 'string', 'max:500'],
        ]);

        $product->update([
            'approval_status' => 'rejected',
            'approved_by'     => Auth::id(),
            'approved_at'     => now(),
            'is_active'       => false,
        ]);

        $admin = Auth::user();

        Mail::to($product->user)->queue(
            new CampaignProductStatusMail($product, 'rejected', $data['reason'], $admin)
        );

        return back()->with('success', "Product \"{$product->name}\" rejected.");
    }
}
