<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Coupon;
use App\Models\User;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index(Request $request)
    {
        $query = Coupon::with(['user', 'campaign'])
            ->when($request->search, function ($q) use ($request) {
                $q->where('code', 'like', '%'.$request->search.'%');
            })
            ->when($request->status, function ($q) use ($request) {
                if ($request->status === 'active') {
                    $q->where('is_active', true);
                } elseif ($request->status === 'inactive') {
                    $q->where('is_active', false);
                } elseif ($request->status === 'expired') {
                    $q->whereNotNull('expires_at')->where('expires_at', '<', now()->startOfDay());
                }
            })
            ->latest();

        $coupons = $query->paginate(20)->withQueryString();

        $stats = [
            'total' => Coupon::count(),
            'active' => Coupon::where('is_active', true)->count(),
            'expired' => Coupon::whereNotNull('expires_at')->where('expires_at', '<', now()->startOfDay())->count(),
        ];

        return view('admin.coupons.index', compact('coupons', 'stats'));
    }

    public function create()
    {
        $users = User::orderBy('name')->get(['id', 'name', 'email']);
        $campaigns = Campaign::orderBy('title')->get(['id', 'title']);

        return view('admin.coupons.create', compact('users', 'campaigns'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        Coupon::create($data);

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon created successfully.');
    }

    public function edit(Coupon $coupon)
    {
        $users = User::orderBy('name')->get(['id', 'name', 'email']);
        $campaigns = Campaign::orderBy('title')->get(['id', 'title']);

        return view('admin.coupons.edit', compact('coupon', 'users', 'campaigns'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $data = $this->validated($request, $coupon);

        $coupon->update($data);

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon updated successfully.');
    }

    public function destroy(Coupon $coupon)
    {
        // Soft deactivate rather than hard delete (keeps redemption audit trail).
        $coupon->update(['is_active' => false]);

        return back()->with('success', 'Coupon deactivated.');
    }

    protected function validated(Request $request, ?Coupon $coupon = null): array
    {
        $unique = 'unique:coupons,code'.($coupon ? ','.$coupon->id : '');

        $data = $request->validate([
            'code' => ['required', 'string', 'max:50', $unique],
            'user_id' => 'nullable|exists:users,id',
            'campaign_id' => 'nullable|exists:campaigns,id',
            'discount_type' => 'required|in:fixed,percent',
            'discount_value' => 'required|numeric|min:0',
            'min_amount' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'expires_at' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        // Checkbox absent => false; present (any value) => true.
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
