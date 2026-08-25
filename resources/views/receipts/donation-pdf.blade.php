<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>DONATEBAZAAR Receipt {{ $receiptNo }}</title>
<style>
    @page { size: A4 portrait; margin: 18mm 16mm 20mm 16mm; }
    * { box-sizing: border-box; }
    body {
        font-family: 'DejaVu Sans', sans-serif;
        color: #1e1b2e;
        font-size: 11px;
        line-height: 1.55;
        margin: 0;
        padding: 0;
    }
    .page { border: 2px solid #4f46e5; border-radius: 12px; padding: 6px; }
    .page-inner { border: 1px solid #c7d2fe; border-radius: 8px; padding: 26px 28px 20px; }
    .brand-row { width: 100%; }
    .brand { font-size: 17px; font-weight: 800; color: #4f46e5; letter-spacing: 4px; }
    .brand-sub { font-size: 8.5px; color: #8b87a3; letter-spacing: 2px; text-transform: uppercase; margin-top: 1px; }
    .status-pill {
        background: #ecfdf5; border: 1px solid #a7f3d0; color: #047857;
        font-size: 9px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase;
        border-radius: 20px; padding: 5px 14px; text-align: center;
    }
    h1 { font-size: 20px; margin: 0 0 2px; color: #1e1b2e; letter-spacing: -0.2px; }
    .subtitle { font-size: 10.5px; color: #8b87a3; margin: 0; }
    .divider { border-bottom: 1.5px solid #e9e6f5; margin: 14px 0; }
    .meta-label { font-size: 8.5px; color: #8b87a3; letter-spacing: 1.4px; text-transform: uppercase; font-weight: 700; }
    .meta-value { font-size: 12px; color: #1e1b2e; font-weight: 700; }
    .meta-value.mono { font-family: 'DejaVu Sans', monospace; }
    .section-label {
        font-size: 9px; font-weight: 800; color: #6d28d9;
        letter-spacing: 1.6px; text-transform: uppercase; margin: 0 0 6px;
    }
    .box {
        background: #f5f3ff; border: 1px solid #e9e2fd; border-radius: 8px;
        padding: 10px 14px; margin-bottom: 12px;
    }
    .box-title { font-size: 12.5px; font-weight: 700; color: #1e1b2e; margin: 0 0 3px; }
    .box-text { font-size: 10px; color: #6b7280; margin: 0; }
    table.amounts { width: 100%; border-collapse: collapse; margin-top: 2px; }
    table.amounts td { padding: 7px 0; border-bottom: 1px solid #f0eef8; font-size: 11px; }
    table.amounts td.label { color: #6b7280; }
    table.amounts td.value { color: #1e1b2e; font-weight: 700; text-align: right; }
    tr.net td {
        border-bottom: none !important; background: #f5f3ff;
        padding: 11px 14px; font-weight: 800; color: #4338ca; font-size: 13px;
        border-radius: 6px;
    }
    tr.net td.label { color: #3730a3; }
    .note {
        background: #f8fafc; border-left: 3px solid #4f46e5;
        padding: 10px 14px; font-size: 9.5px; color: #64748b;
        border-radius: 6px; margin-top: 14px; line-height: 1.7;
    }
    .footer { margin-top: 18px; text-align: center; }
    .footer p { margin: 0; font-size: 9px; color: #8b87a3; }
    .footer a { color: #4f46e5; text-decoration: none; }
    .thanks {
        text-align: center; margin-top: 16px; padding-top: 12px;
        border-top: 1px dashed #d8d2f0; font-size: 10px; color: #6b7280;
    }
</style>
</head>
<body>

<div class="page">
<div class="page-inner">

    <!-- Header -->
    <table class="brand-row" cellpadding="0" cellspacing="0">
        <tr>
            <td style="width:60%;">
                <div class="brand">DONATEBAZAAR</div>
                <div class="brand-sub">Give &middot; Grow &middot; Change Lives</div>
            </td>
            <td style="text-align:right;">
                <div class="status-pill">&#10003;&nbsp; Donation Confirmed</div>
            </td>
        </tr>
    </table>

    <div class="divider"></div>

    <!-- Title -->
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td>
                <h1>Official Donation Receipt</h1>
                <p class="subtitle">This document certifies a donation received by DONATEBAZAAR on behalf of the campaign below.</p>
            </td>
            <td style="text-align:right;width:45%;">
                <div class="meta-label">Receipt Number</div>
                <div class="meta-value mono" style="font-size:13px;color:#4f46e5;">{{ $receiptNo }}</div>
            </td>
        </tr>
    </table>

    <!-- Meta -->
    <table width="100%" cellpadding="0" cellspacing="0" style="margin-top:16px;">
        <tr>
            <td style="width:33%;">
                <div class="meta-label">Donor Name</div>
                <div class="meta-value">{{ $donorName }}</div>
            </td>
            <td style="width:34%;">
                <div class="meta-label">Date &amp; Time</div>
                <div class="meta-value">{{ $paidAt ? \Carbon\Carbon::parse($paidAt)->format('d M Y, h:i A') : $donation->created_at->format('d M Y, h:i A') }}</div>
            </td>
            <td style="width:33%;">
                <div class="meta-label">Payment Method</div>
                <div class="meta-value">{{ $paymentMethod }}</div>
            </td>
        </tr>
    </table>

    <!-- Campaign -->
    <div class="box" style="margin-top:16px;">
        <div class="section-label">Supporting Campaign</div>
        <p class="box-title">{{ optional($campaign)->title ?: 'Campaign' }}</p>
        <p class="box-text">Your contribution helps this campaign move closer to its fundraising goal.</p>
    </div>

    <!-- Financial summary -->
    <div class="section-label">Financial Summary</div>
    <table class="amounts" cellpadding="0" cellspacing="0">
        <tr>
            <td class="label">Donation Amount</td>
            <td class="value">&#8377; {{ number_format($amount, 2) }}</td>
        </tr>
        <tr>
            <td class="label">Platform Fee (5%)</td>
            <td class="value">&#8377; {{ number_format($platformFee, 2) }}</td>
        </tr>
        @if ((float) ($discountAmount ?? 0) > 0)
        <tr>
            <td class="label">Coupon ({{ $couponCode ?: 'Discount' }})</td>
            <td class="value">&#8722; &#8377; {{ number_format($discountAmount, 2) }}</td>
        </tr>
        @endif
        <tr class="net">
            <td class="label">Amount to Campaign</td>
            <td class="value">&#8377; {{ number_format($netAmount, 2) }}</td>
        </tr>
    </table>

    @if (! empty($paymentReference))
    <table width="100%" cellpadding="0" cellspacing="0" style="margin-top:14px;">
        <tr>
            <td style="width:33%;">
                <div class="meta-label">Payment Reference</div>
                <div class="meta-value mono" style="font-size:10.5px;">{{ $paymentReference }}</div>
            </td>
        </tr>
    </table>
    @endif

    <!-- Official note -->
    <div class="note">
        <strong>Official Receipt Note:</strong> This receipt confirms a donation of
        &#8377; {{ number_format($amount, 2) }} received via {{ $paymentMethod }}.
        A platform fee of &#8377; {{ number_format($platformFee, 2) }} has been deducted to help
        maintain and operate the platform; the remaining
        &#8377; {{ number_format($netAmount, 2) }} is transferred toward the campaign.
        Please retain this document for your records.
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>www.donatebazaar.com &nbsp;&middot;&nbsp; support@donatebazaar.com</p>
        <p style="margin-top:4px;">&copy; {{ date('Y') }} DONATEBAZAAR. All rights reserved.</p>
        <p style="margin-top:4px;">This is a computer-generated receipt and does not require a physical signature.</p>
    </div>

    <div class="thanks">Thank you for making a difference.</div>

</div>
</div>

</body>
</html>