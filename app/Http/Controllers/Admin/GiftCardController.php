<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GiftCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class GiftCardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index(Request $request)
    {
        $status = $request->input('status', 'all');
        $search = $request->input('search', '');

        $query = GiftCard::latest();

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('recipient_email', 'like', "%{$search}%")
                  ->orWhere('sender_name', 'like', "%{$search}%")
                  ->orWhere('recipient_name', 'like', "%{$search}%");
            });
        }

        $stats = [
            'total'    => GiftCard::count(),
            'pending'  => GiftCard::where('status', 'pending')->count(),
            'sent'     => GiftCard::where('status', 'sent')->count(),
            'redeemed' => GiftCard::where('status', 'redeemed')->count(),
            'expired'  => GiftCard::where('status', 'expired')->count(),
            'revenue'  => GiftCard::where('payment_status', 'completed')->sum('amount'),
        ];

        return view('admin.gift-cards.index', [
            'giftCards' => $query->paginate(20)->withQueryString(),
            'stats'     => $stats,
            'status'    => $status,
            'search'    => $search,
        ]);
    }

    public function show(GiftCard $giftCard)
    {
        $giftCard->load('redeemedBy', 'campaign');
        return view('admin.gift-cards.show', compact('giftCard'));
    }

    public function updateStatus(Request $request, GiftCard $giftCard)
    {
        $request->validate([
            'status'     => 'required|in:pending,sent,redeemed,expired,cancelled',
            'admin_note' => 'nullable|string|max:500',
        ]);

        $giftCard->update([
            'status'     => $request->status,
            'admin_note' => $request->admin_note,
        ]);

        return back()->with('success', 'Gift card status updated.');
    }

    public function resend(GiftCard $giftCard)
    {
        if (!$giftCard->isPaid()) {
            return back()->with('error', 'Cannot resend unpaid gift card.');
        }

        // Send email
        \Mail::send('emails.gift-card', ['giftCard' => $giftCard], function ($m) use ($giftCard) {
            $m->to($giftCard->recipient_email, $giftCard->recipient_name)
              ->subject("You've received a DonateBazaar Gift Card from {$giftCard->sender_name}!");
        });

        $giftCard->update(['status' => 'sent']);

        return back()->with('success', 'Gift card resent successfully.');
    }

    public function destroy(GiftCard $giftCard)
    {
        if ($giftCard->isRedeemed()) {
            return back()->with('error', 'Cannot delete a redeemed gift card.');
        }

        $giftCard->update(['status' => 'cancelled']);

        return back()->with('success', 'Gift card cancelled.');
    }
}