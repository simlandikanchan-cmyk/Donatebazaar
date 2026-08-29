<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta color-scheme="content="light">
<meta supported-color-schemes="content="light">
<title>Payment Not Completed — DONATEBAZAAR</title>
<style>
@media only screen and (max-width:520px){
  .card-pad{ padding-left:20px !important; padding-right:20px !important; }
}
</style>
</head>
<body style="margin:0;padding:0;background:#eef1f8;font-family:Arial,Helvetica,sans-serif;-webkit-text-size-adjust:100%;">

<span style="display:none;font-size:1px;color:#eef1f8;line-height:1px;max-height:0;max-width:0;opacity:0;overflow:hidden;">
    Your payment of ₹{{ number_format($amount, 2) }} was not completed. Please try again.
</span>

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#eef1f8;">
<tr><td align="center" class="card-pad" style="padding:32px 16px;">

<table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;">

  <tr>
    <td style="background:#ffffff;border-radius:18px;overflow:hidden;border:1px solid #e9ecf5;">
      <table role="presentation" width="100%" cellpadding="0" cellspacing="0">

        <tr>
          <td bgcolor="#dc2626" style="background-image:linear-gradient(135deg,#dc2626,#f87171);background-color:#dc2626;padding:38px 30px 34px;text-align:center;">
            <table role="presentation" align="center" cellpadding="0" cellspacing="0" style="margin:0 auto 16px;">
              <tr><td style="width:54px;height:54px;border-radius:50%;background:rgba(255,255,255,.16);text-align:center;vertical-align:middle;font-size:26px;line-height:54px;color:#ffffff;">✕</td></tr>
            </table>
            <p style="color:#ffffff;font-size:21px;font-weight:700;margin:0 0 4px;">Payment Not Completed</p>
            <p style="color:rgba(255,255,255,.7);font-size:13px;margin:0;">Your transaction could not be processed</p>
          </td>
        </tr>

        <tr>
          <td style="padding:34px 30px 8px;">

            <h1 style="font-size:22px;color:#111827;margin:0 0 10px;font-weight:700;">
              Hello, {{ $donorName }}
            </h1>
            <p style="font-size:14.5px;color:#6b7280;line-height:1.7;margin:0 0 24px;">
              We were unable to complete your payment for the campaign
              <strong>"{{ $campaign->title ?? 'Campaign' }}"</strong>. This could be due to
              insufficient funds, network issues, or an expired payment session.
            </p>

            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:24px;">
              <tr>
                <td style="background:#fef2f2;border:1px solid #fecaca;border-radius:14px;padding:20px 22px;">
                  <p style="color:#991b1b;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;margin:0 0 7px;">Payment Details</p>
                  <p style="color:#111827;font-size:17px;font-weight:700;margin:0 0 4px;line-height:1.4;">{{ $campaign->title ?? 'Campaign' }}</p>
                  <p style="color:#475569;font-size:13.5px;margin:0 0 2px;">Amount: ₹{{ number_format($amount, 2) }}</p>
                  <p style="color:#475569;font-size:13.5px;margin:0;">Status: <span style="color:#dc2626;font-weight:600;">Failed</span></p>
                </td>
              </tr>
            </table>

            <p style="font-size:14.5px;color:#6b7280;line-height:1.7;margin:0 0 28px;">
              Please try donating again. If the issue persists, contact your bank or card provider,
              or reach out to our support team for assistance.
            </p>

            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:30px;">
              <tr>
                 <td align="center">
                   <a href="{{ route('campaign.public', ['category' => $campaign->category->slug ?? 'campaigns', 'slug' => $campaign->slug ?? '']) }}"
                      style="display:inline-block;background:#dc2626;color:#ffffff;font-size:14px;font-weight:600;text-decoration:none;padding:13px 32px;border-radius:9px;">
                     Try Again&nbsp;&rarr;
                   </a>
                 </td>
               </tr>
            </table>

          </td>
        </tr>

      </table>
    </td>
  </tr>

  <tr>
    <td align="center" style="padding:26px 20px 8px;">
      <p style="color:#6b7280;font-size:12px;margin:0 0 10px;line-height:1.7;">
        &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
      </p>
      <p style="color:#6b7280;font-size:12px;margin:0 0 4px;">
        <a href="{{ config('app.url') }}" style="color:#4f46e5;text-decoration:none;">{{ config('app.name') }}</a>
        &nbsp;&middot;&nbsp;
        <a href="mailto:support@{{ parse_url(config('app.url'), PHP_URL_HOST) }}" style="color:#4f46e5;text-decoration:none;">Contact Support</a>
      </p>
    </td>
  </tr>

</table>

</td></tr>
</table>

</body>
</html>
