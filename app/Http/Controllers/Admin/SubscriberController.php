<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SubscriberController extends Controller
{
    public function index(Request $request): View
    {
        $query = Subscriber::query()
            ->when($request->search, function ($q) use ($request) {
                $q->where('email', 'like', '%'.$request->search.'%');
            })
            ->when($request->status, function ($q) use ($request) {
                if ($request->status === 'active') {
                    $q->whereNull('unsubscribed_at');
                } elseif ($request->status === 'unsubscribed') {
                    $q->whereNotNull('unsubscribed_at');
                }
            })
            ->orderByDesc('subscribed_at');

        $subscribers = $query->paginate(25)->withQueryString();

        $stats = [
            'total' => Subscriber::count(),
            'active' => Subscriber::active()->count(),
            'unsubscribed' => Subscriber::whereNotNull('unsubscribed_at')->count(),
        ];

        return view('admin.subscribers.index', compact('subscribers', 'stats'));
    }

    public function unsubscribe(Subscriber $subscriber): RedirectResponse
    {
        if ($subscriber->unsubscribed_at === null) {
            $subscriber->update(['unsubscribed_at' => now()]);
            $message = 'Subscriber has been unsubscribed.';
        } else {
            $message = 'Subscriber is already unsubscribed.';
        }

        return back()->with('success', $message);
    }

    public function resubscribe(Subscriber $subscriber): RedirectResponse
    {
        $subscriber->update([
            'unsubscribed_at' => null,
            'subscribed_at' => $subscriber->subscribed_at ?? now(),
        ]);

        return back()->with('success', 'Subscriber has been re-subscribed.');
    }

    public function destroy(Subscriber $subscriber): RedirectResponse
    {
        $subscriber->delete();

        return back()->with('success', 'Subscriber removed.');
    }

    public function export(): StreamedResponse
    {
        $subscribers = Subscriber::orderByDesc('subscribed_at')->get();

        $fileName = 'newsletter-subscribers-'.now()->format('Y-m-d').'.csv';

        return response()->streamDownload(function () use ($subscribers) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Email', 'Status', 'Subscribed At', 'Unsubscribed At']);
            foreach ($subscribers as $s) {
                fputcsv($out, [
                    $s->email,
                    $s->unsubscribed_at ? 'Unsubscribed' : 'Active',
                    $s->subscribed_at?->format('Y-m-d H:i'),
                    $s->unsubscribed_at?->format('Y-m-d H:i'),
                ]);
            }
            fclose($out);
        }, $fileName, ['Content-Type' => 'text/csv']);
    }
}
