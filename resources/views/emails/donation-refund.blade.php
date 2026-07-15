<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="color-scheme" content="light">
<title>Refund Confirmation</title>
</head>
<body style="margin:0;padding:0;background:#eef1f8;font-family:Arial,Helvetica,sans-serif;-webkit-text-size-adjust:100%;">

<!-- Preheader -->
<span style="display:none;font-size:1px;color:#eef1f8;line-height:1px;max-height:0;max-width:0;opacity:0;overflow:hidden;">
    Your refund of ₹{{ number_format($amount, 0) }} has been processed successfully.
</span>

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#eef1f8;">
<tr><td align="center" style="padding:32px 16px;">

<table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;">

  <!-- Main card -->
  <tr>
    <td style="background:#ffffff;border-radius:18px;overflow:hidden;border:1px solid #e9ecf5;">
      <table role="presentation" width="100%" cellpadding="0" cellspacing="0">

        <!-- Header -->
        <tr>
          <td bgcolor="#d97706" style="background-image:linear-gradient(135deg,#d97706,#f59e0b);background-color:#d97706;padding:38px 30px 34px;text-align:center;">
            <table role="presentation" align="center" cellpadding="0" cellspacing="0" style="margin:0 auto 16px;">
              <tr><td style="width:54px;height:54px;border-radius:50%;background:rgba(255,255,255,.16);text-align:center;vertical-align:middle;font-size:26px;line-height:54px;color:#ffffff;">↺</td></tr>
            </table>
            <p style="color:#ffffff;font-size:21px;font-weight:700;margin:0 0 4px;">Refund Processed</p>
            <p style="color:#fde68a;font-size:13px;margin:0;">Refund #{{ $refundId }} &middot; {{ $processedAt ? \Carbon\Carbon::parse($processedAt)->format('d M Y, h:i A') : '—' }}</p>
          </td>
        </tr>

        <!-- Body -->
        <tr>
          <td style="padding:34px 30px 8px;">

            <p style="display:inline-block;background:#fef3c7;color:#92400e;font-size:12px;font-weight:700;letter-spacing:.02em;padding:6px 14px;border-radius:20px;margin:0 0 20px;">
              ● REFUND COMPLETED
            </p>

            <h1 style="font-size:22px;color:#111827;margin:0 0 10px;font-weight:700;">
              Hello, {{ $donorName }}
            </h1>
            <p style="font-size:14.5px;color:#6b7280;line-height:1.7;margin:0 0 28px;">
              Your donation has been refunded successfully. The amount has been returned to your original payment method. Depending on your bank or card provider, it may take 5–7 business days to reflect in your account.
            </p>

            <!-- Amount card -->
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:24px;">
              <tr>
                <td bgcolor="#d97706" style="background-image:linear-gradient(135deg,#d97706,#f59e0b);background-color:#d97706;border-radius:14px;padding:26px 24px;text-align:center;">
                  <p style="color:#fef3c7;font-size:12.5px;letter-spacing:.03em;margin:0 0 8px;">REFUND AMOUNT</p>
                  <p style="color:#ffffff;font-size:38px;font-weight:700;margin:0;line-height:1.1;">₹{{ number_format($amount, 2) }}</p>
                </td>
              </tr>
            </table>

            <!-- Campaign card -->
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:24px;">
              <tr>
                <td style="background:#fffbeb;border:1px solid #fde68a;border-radius:14px;padding:20px 22px;">
                  <p style="color:#92400e;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;margin:0 0 7px;">Previously Donated To</p>
                  <p style="color:#111827;font-size:17px;font-weight:700;margin:0 0 8px;line-height:1.4;">{{ $campaign->title ?? 'Campaign' }}</p>
                </td>
              </tr>
            </table>

            <!-- Refund details -->
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e5e7eb;border-radius:14px;margin-bottom:24px;">
              <tr>
                <td style="padding:20px 24px 6px;">
                  <p style="font-size:15px;font-weight:700;color:#111827;margin:0;">Refund Details</p>
                </td>
              </tr>

               @php
                 $rows = [
                     ['Refund ID', '#' . $refundId],
                     ['Processed At', $processedAt ? \Carbon\Carbon::parse($processedAt)->format('d M Y, h:i A') : '—'],
                     ['Refund Amount', '₹' . number_format($amount, 2)],
                 ];

                 if ($reason) {
                     $rows[] = ['Reason', $reason];
                 }
               @endphp

              @foreach ($rows as $row)
              <tr>
                <td style="padding:0 24px;border-top:1px solid #f1f5f9;">
                  <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                      <td style="padding:12px 0;font-size:13.5px;color:#6b7280;">{{ $row[0] }}</td>
                      <td style="padding:12px 0;font-size:13.5px;color:#d97706;font-weight:600;text-align:right;">{{ $row[1] }}</td>
                    </tr>
                  </table>
                </td>
              </tr>
              @endforeach
            </table>

            <!-- Note -->
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:6px;">
              <tr>
                <td style="background:#f8fafc;border-left:4px solid #d97706;border-radius:8px;padding:16px 18px;">
                  <p style="color:#64748b;font-size:12.5px;line-height:1.7;margin:0;">
                    If you have any questions about this refund, please contact our support team at
                    <a href="mailto:support@donatebazaar.com" style="color:#d97706;text-decoration:underline;">support@donatebazaar.com</a>.
                  </p>
                </td>
              </tr>
            </table>

          </td>
        </tr>

      </table>
    </td>
  </tr>

  <!-- Footer -->
  <tr>
    <td align="center" style="padding:26px 20px 8px;">
      <p style="color:#6b7280;font-size:12px;margin:0 0 10px;line-height:1.7;">
        © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
      </p>
      <p style="color:#6b7280;font-size:12px;margin:0 0 4px;">
        <a href="{{ config('app.url') }}" style="color:#d97706;text-decoration:none;">{{ config('app.name') }}</a>
        &nbsp;&middot;&nbsp;
        <a href="mailto:support@donatebazaar.com" style="color:#d97706;text-decoration:none;">support@donatebazaar.com</a>
      </p>
    </td>
  </tr>

</table>

</td></tr>
</table>

</body>
</html>
