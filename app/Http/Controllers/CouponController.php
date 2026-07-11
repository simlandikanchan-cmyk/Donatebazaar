<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Services\CouponService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CouponController extends Controller
{
    /**
     * AJAX endpoint used by the campaign donation form to validate a coupon
     * and return the discounted total for live display.
     */
    public function check(Request $request, CouponService $couponService): JsonResponse
    {
        $request->validate([
            'code'       => 'required|string',
            'amount'     => 'required|numeric|min:1',
            'campaign_id' => 'nullable|integer|exists:campaigns,id',
        ]);

        $user     = Auth::user();
        $campaign = $request->campaign_id ? Campaign::find($request->campaign_id) : null;
        $amount   = (float) $request->amount;

        $result = $couponService->validate($request->code, $user, $campaign, $amount);

        return response()->json([
            'valid'            => $result['valid'],
            'discount_amount'  => $result['discount_amount'],
            'discounted_total' => $result['discounted_total'],
            'message'          => $result['message'],
        ]);
    }
}
