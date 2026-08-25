@extends('layouts.user')

@section('page_title', 'Donation Receipt')
@section('page_subtitle', 'Tax-exempt receipt for your contribution')

@section('content')
<div class="receipt" id="receipt">
    <div class="receipt-header">
        <div class="receipt-brand">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>FundRaising</span>
        </div>
        <span class="receipt-badge">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Receipt
        </span>
    </div>

    <div class="receipt-title">
        <h1>Donation Receipt</h1>
        <p>Thank you for your generous contribution. This receipt serves as official documentation for your donation.</p>
    </div>

    <div class="receipt-grid">
        <div class="receipt-field">
            <span class="receipt-label">Receipt Number</span>
            <span class="receipt-value mono">{{ $receiptNo ?? 'N/A' }}</span>
        </div>
        <div class="receipt-field">
            <span class="receipt-label">Date of Donation</span>
            <span class="receipt-value">{{ $paidAt ? \Carbon\Carbon::parse($paidAt)->format('F d, Y') : ($donation->created_at->format('F d, Y')) }}</span>
        </div>
        <div class="receipt-field">
            <span class="receipt-label">Donor Name</span>
            <span class="receipt-value">{{ $donorName }}</span>
        </div>
        <div class="receipt-field">
            <span class="receipt-label">Payment Method</span>
            <span class="receipt-value">{{ ucfirst($donation->payment_method ?? 'Online') }}</span>
        </div>
        <div class="receipt-field full">
            <span class="receipt-label">Campaign</span>
            <span class="receipt-value">{{ $campaign->title ?? 'General Fund' }}</span>
        </div>
        <div class="receipt-field">
            <span class="receipt-label">Donation Type</span>
            <span class="receipt-value">{{ ucfirst($donation->donation_type ?? 'One-time') }}</span>
        </div>
        <div class="receipt-field">
            <span class="receipt-label">Transaction ID</span>
            <span class="receipt-value mono" style="word-break:break-all;">{{ $donation->transaction_id ?? 'N/A' }}</span>
        </div>
    </div>

    <div class="receipt-amounts">
        <div class="receipt-amount-row">
            <span>Donation Amount</span>
            <span>₹{{ number_format($amount, 2) }}</span>
        </div>
        <div class="receipt-amount-row sub">
            <span>Platform Fee</span>
            <span>₹{{ number_format($platformFee ?? 0, 2) }}</span>
        </div>
        <div class="receipt-amount-row sub">
            <span>Net Amount</span>
            <span>₹{{ number_format($netAmount ?? $amount, 2) }}</span>
        </div>
        <div class="receipt-amount-row total">
            <span>Total Charged</span>
            <span>₹{{ number_format($amount, 2) }}</span>
        </div>
    </div>

    <div class="receipt-footer">
        <p>This receipt was generated automatically. For any discrepancies, please contact our support team.</p>
        <div class="receipt-stamp">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            Verified
        </div>
    </div>

    <div class="receipt-actions">
        <x-button variant="primary" type="button" onclick="window.print()">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            Print Receipt
        </x-button>
        <x-button variant="secondary" type="button" onclick="history.back()">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back
        </x-button>
    </div>
</div>
@endsection

@push('page_styles')
<style>
.receipt{max-width:680px;margin:0 auto;background:var(--surface);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh-lg);padding:32px;animation:fadeUp .4s both;}
.receipt-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;padding-bottom:16px;border-bottom:2px solid var(--border);}
.receipt-brand{display:flex;align-items:center;gap:8px;font-size:13px;font-weight:800;color:var(--accent);letter-spacing:-0.01em;}
.receipt-brand svg{width:20px;height:20px;}
.receipt-badge{display:inline-flex;align-items:center;gap:6px;padding:5px 13px;border-radius:100px;background:rgba(16,185,129,0.12);color:var(--green);font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;font-family:var(--mono);}
.receipt-badge svg{width:13px;height:13px;}
.receipt-title{text-align:center;margin-bottom:28px;}
.receipt-title h1{font-size:22px;font-weight:800;color:var(--text);margin-bottom:6px;letter-spacing:-0.02em;}
.receipt-title p{font-size:12px;color:var(--text3);line-height:1.6;max-width:420px;margin:0 auto;}
.receipt-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:24px;}
.receipt-field{display:flex;flex-direction:column;gap:2px;padding:12px;background:var(--surface2);border-radius:var(--r-sm);}
.receipt-field.full{grid-column:1/-1;}
.receipt-label{font-size:10px;font-weight:600;color:var(--text3);text-transform:uppercase;letter-spacing:0.06em;font-family:var(--mono);}
.receipt-value{font-size:13px;font-weight:700;color:var(--text);}
.receipt-value.mono{font-family:var(--mono);font-size:11.5px;letter-spacing:-0.01em;}
.receipt-amounts{background:var(--surface2);border-radius:var(--r-sm);padding:12px 16px;margin-bottom:20px;}
.receipt-amount-row{display:flex;justify-content:space-between;align-items:center;padding:7px 0;font-size:13px;color:var(--text);}
.receipt-amount-row.sub{font-size:11.5px;color:var(--text3);border-top:1px solid var(--border);}
.receipt-amount-row.total{border-top:2px solid var(--text);margin-top:4px;padding-top:10px;font-weight:800;font-size:15px;color:var(--text);}
.receipt-footer{display:flex;align-items:center;justify-content:space-between;gap:16px;padding-top:16px;border-top:1px solid var(--border);margin-bottom:14px;}
.receipt-footer p{font-size:10px;color:var(--text3);line-height:1.5;max-width:400px;}
.receipt-stamp{display:inline-flex;align-items:center;gap:6px;padding:6px 14px;border-radius:100px;background:rgba(16,185,129,0.12);color:var(--green);font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;font-family:var(--mono);flex-shrink:0;border:1.5px dashed var(--green);opacity:.7;}
.receipt-stamp svg{width:14px;height:14px;}
.receipt-actions{display:flex;align-items:center;gap:10px;justify-content:center;}
@media(max-width:600px){.receipt-grid{grid-template-columns:1fr;}.receipt{padding:20px;}}
@media print{body{background:#fff !important;}.receipt{box-shadow:none;border:1px solid #ddd;max-width:100%;}:is(.sidebar,.n-header,.receipt-actions,*[onclick*=print]){display:none !important;}}
</style>
@endpush
