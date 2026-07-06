@extends('layouts.user')

@section('page_title', 'Recurring Donation Details')
@section('page_subtitle', $recurringDonation->campaign->title ?? 'Campaign')

@section('content')
@php
    $rd = $recurringDonation;
    $status = $rd->status;
    $billingCount = $rd->billing_count ?? 0;
    $totalBilled = $rd->amount * $billingCount;
@endphp

<div style="max-width:800px;">

<a href="{{ route('recurring.index') }}" class="btn btn-secondary" style="margin-bottom:18px;">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m7 7l-7-7 7-7"/></svg>
    Back to All Plans
</a>

<div class="card">
    <div style="padding:24px 28px;">

        <div style="display:flex;align-items:center;gap:16px;margin-bottom:20px;">
            <div style="width:52px;height:52px;border-radius:13px;background:linear-gradient(135deg,var(--accent),var(--accent2));color:#fff;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:22px;height:22px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21s-7-4.35-9-8.5C1 8 3.5 4 7.5 4c2.04 0 3.04 1 4.5 2.5C13.46 5 14.46 4 16.5 4 20.5 4 23 8 21 12.5 19 16.65 12 21 12 21z"/></svg>
            </div>
            <div>
                <div style="font-size:17px;font-weight:700;color:var(--text);">{{ $rd->campaign->title ?? 'Campaign' }}</div>
                <div style="font-size:12px;color:var(--text3);">
                    <a href="{{ route('campaign.show', $rd->campaign_id) }}" style="color:var(--accent);text-decoration:none;font-weight:600;">View campaign →</a>
                </div>
            </div>
            <div style="margin-left:auto;">
                <span class="status-chip chip-{{ $status }}" style="display:inline-flex;align-items:center;gap:6px;padding:5px 14px;border-radius:100px;font-size:11px;font-weight:700;text-transform:uppercase;font-family:var(--mono);">
                    <span style="width:7px;height:7px;border-radius:50%;background:currentColor;flex-shrink:0;"></span>{{ ucfirst($status) }}
                </span>
            </div>
        </div>

        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:14px;margin-bottom:24px;">
            <div style="background:var(--surface2);border-radius:var(--r-sm);padding:14px 16px;text-align:center;">
                <div style="font-size:10px;color:var(--text3);font-family:var(--mono);text-transform:uppercase;letter-spacing:0.06em;margin-bottom:4px;">Amount</div>
                <div style="font-size:20px;font-weight:800;color:var(--accent);font-family:var(--mono);">₹{{ number_format($rd->amount, 2) }}</div>
                <div style="font-size:11px;color:var(--text3);margin-top:2px;">per {{ $rd->frequency }}</div>
            </div>
            <div style="background:var(--surface2);border-radius:var(--r-sm);padding:14px 16px;text-align:center;">
                <div style="font-size:10px;color:var(--text3);font-family:var(--mono);text-transform:uppercase;letter-spacing:0.06em;margin-bottom:4px;">Frequency</div>
                <div style="font-size:20px;font-weight:800;color:var(--text);font-family:var(--mono);text-transform:capitalize;">{{ $rd->frequency }}</div>
                <div style="font-size:11px;color:var(--text3);margin-top:2px;">billing cycle</div>
            </div>
            <div style="background:var(--surface2);border-radius:var(--r-sm);padding:14px 16px;text-align:center;">
                <div style="font-size:10px;color:var(--text3);font-family:var(--mono);text-transform:uppercase;letter-spacing:0.06em;margin-bottom:4px;">Payments Made</div>
                <div style="font-size:20px;font-weight:800;color:var(--text);font-family:var(--mono);">{{ $billingCount }}</div>
                <div style="font-size:11px;color:var(--text3);margin-top:2px;">
                    @if($billingCount > 0)₹{{ number_format($totalBilled) }} total
                    @else—@endif
                </div>
            </div>
            @if($status !== 'cancelled')
            <div style="background:var(--surface2);border-radius:var(--r-sm);padding:14px 16px;text-align:center;">
                <div style="font-size:10px;color:var(--text3);font-family:var(--mono);text-transform:uppercase;letter-spacing:0.06em;margin-bottom:4px;">Next Billing</div>
                <div style="font-size:20px;font-weight:800;color:var(--text);font-family:var(--mono);">{{ optional($rd->next_billing_date)?->format('d M Y') ?? '—' }}</div>
                <div style="font-size:11px;color:var(--text3);margin-top:2px;">
                    @if($rd->next_billing_date)
                        {{ $rd->next_billing_date->diffForHumans() }}
                    @endif
                </div>
            </div>
            @endif
        </div>

        <div style="background:var(--surface2);border-radius:var(--r-sm);padding:16px 18px;margin-bottom:24px;">
            <div style="font-size:11px;font-weight:600;color:var(--text2);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:10px;">Plan Timeline</div>
            <div style="display:flex;flex-direction:column;gap:8px;">
                <div style="display:flex;align-items:center;gap:10px;font-size:12.5px;">
                    <span style="width:8px;height:8px;border-radius:50%;background:var(--accent);flex-shrink:0;"></span>
                    <span style="color:var(--text2);font-weight:600;">Started</span>
                    <span style="color:var(--text3);font-family:var(--mono);font-size:11px;margin-left:auto;">{{ $rd->created_at->format('d M Y, h:i A') }}</span>
                </div>
                @if($rd->last_billed_at)
                <div style="display:flex;align-items:center;gap:10px;font-size:12.5px;">
                    <span style="width:8px;height:8px;border-radius:50%;background:var(--green);flex-shrink:0;"></span>
                    <span style="color:var(--text2);font-weight:600;">Last billed</span>
                    <span style="color:var(--text3);font-family:var(--mono);font-size:11px;margin-left:auto;">{{ $rd->last_billed_at->format('d M Y, h:i A') }}</span>
                </div>
                @endif
                @if($status === 'paused' && $rd->paused_at)
                <div style="display:flex;align-items:center;gap:10px;font-size:12.5px;">
                    <span style="width:8px;height:8px;border-radius:50%;background:var(--yellow);flex-shrink:0;"></span>
                    <span style="color:var(--text2);font-weight:600;">Paused</span>
                    <span style="color:var(--text3);font-family:var(--mono);font-size:11px;margin-left:auto;">{{ \Carbon\Carbon::parse($rd->paused_at)->format('d M Y, h:i A') }}</span>
                </div>
                @endif
                @if($status === 'cancelled')
                <div style="display:flex;align-items:center;gap:10px;font-size:12.5px;">
                    <span style="width:8px;height:8px;border-radius:50%;background:var(--red);flex-shrink:0;"></span>
                    <span style="color:var(--text2);font-weight:600;">Cancelled</span>
                    <span style="color:var(--text3);font-family:var(--mono);font-size:11px;margin-left:auto;">{{ optional($rd->updated_at)->format('d M Y, h:i A') }}</span>
                </div>
                @endif
                @if($status !== 'cancelled' && $rd->next_billing_date)
                <div style="display:flex;align-items:center;gap:10px;font-size:12.5px;">
                    <span style="width:8px;height:8px;border-radius:50%;background:var(--accent-glow);border:1.5px solid var(--accent);flex-shrink:0;"></span>
                    <span style="color:var(--text2);font-weight:600;">Next billing</span>
                    <span style="color:var(--text3);font-family:var(--mono);font-size:11px;margin-left:auto;">{{ $rd->next_billing_date->format('d M Y') }}</span>
                </div>
                @endif
            </div>
        </div>

        @if($rd->razorpay_subscription_id)
        <div style="background:var(--surface2);border-radius:var(--r-sm);padding:14px 18px;margin-bottom:24px;">
            <div style="font-size:10.5px;color:var(--text3);font-family:var(--mono);">
                Subscription ID: <strong style="color:var(--text2);">{{ $rd->razorpay_subscription_id }}</strong>
            </div>
        </div>
        @endif

        <div style="display:flex;gap:10px;flex-wrap:wrap;">
            <a href="{{ route('recurring.index') }}" class="btn btn-secondary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m7 7l-7-7 7-7"/></svg>
                Back to All Plans
            </a>

            @if($status === 'active')
            <form action="{{ route('recurring.pause', $rd->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-yellow">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/></svg>
                    Pause Plan
                </button>
            </form>
            @endif

            @if($status === 'paused')
            <form action="{{ route('recurring.resume', $rd->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-green">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                    Resume Plan
                </button>
            </form>
            @endif

            @if($status !== 'cancelled')
            <form action="{{ route('recurring.cancel', $rd->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this recurring donation? This action cannot be undone.')">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-red">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                    Cancel Plan
                </button>
            </form>
            @endif
        </div>

    </div>
</div>

</div>
@endsection
