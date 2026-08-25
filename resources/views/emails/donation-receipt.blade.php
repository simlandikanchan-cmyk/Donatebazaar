<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="color-scheme" content="light">
<meta name="supported-color-schemes" content="light">
<title>Donation Receipt — DONATEBAZAAR</title>
<!-- Progressive enhancement for clients that honour <style> (Apple Mail, some webmail).
     Gmail and Outlook ignore this block and rely on the inline styles below. -->
<style>
@media only screen and (max-width:520px){
  .card-pad{ padding-left:20px !important; padding-right:20px !important; }
  .body-pad{ padding:24px 12px !important; }
}
</style>
</head>
<body style="margin:0;padding:0;background:#f4f2fa;font-family:-apple-system,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;-webkit-text-size-adjust:100%;text-size-adjust:100%;">

<!-- Preheader: controls the inbox preview snippet, hidden in the email body -->
<span style="display:none;font-size:1px;color:#f4f2fa;line-height:1px;max-height:0;max-width:0;opacity:0;overflow:hidden;">
    Your ₹{{ number_format($amount, 2) }} donation is confirmed — official receipt {{ $receiptNo }} enclosed. Thank you, {{ $donorName }}!
</span>

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f4f2fa;">
<tr><td align="center" class="body-pad" style="padding:32px 16px;">

<table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;">

  <!-- Brand wordmark -->
  <tr>
    <td align="center" style="padding-bottom:18px;">
      <span style="font-size:14px;font-weight:800;color:#4f46e5;letter-spacing:.22em;">DONATEBAZAAR</span>
    </td>
  </tr>

  <!-- ============================================================ -->
  <!-- Main card                                                   -->
  <!-- ============================================================ -->
  <tr>
    <td style="background:#ffffff;border-radius:20px;overflow:hidden;border:1px solid #e9e6f5;">
      <table role="presentation" width="100%" cellpadding="0" cellspacing="0">

        <!-- ======================= HEADER ======================= -->
        <tr>
          <td bgcolor="#4f46e5" style="background-image:linear-gradient(135deg,#4338ca 0%,#5b21b6 60%,#7c3aed 100%);background-color:#4f46e5;border-radius:20px 20px 0 0;padding:40px 32px 34px;text-align:center;">

            <!-- Success ring + check -->
            <table role="presentation" align="center" cellpadding="0" cellspacing="0" style="margin:0 auto 18px;">
              <tr>
                <td align="center" style="width:76px;height:76px;border-radius:50%;background:rgba(255,255,255,.16);">
                  <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                      <td align="center" style="width:60px;height:60px;border-radius:50%;background:#ffffff;">
                        <span style="display:block;width:60px;height:60px;line-height:60px;font-size:30px;color:#4f46e5;font-weight:700;">✓</span>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>

            <p style="color:#ffffff;font-size:25px;font-weight:700;margin:0 0 6px;letter-spacing:-.01em;">Donation Confirmed</p>
            <p style="color:#ddd6fe;font-size:14px;margin:0 0 18px;">Official Donation Receipt</p>

            <!-- Receipt number pill -->
            <table role="presentation" align="center" cellpadding="0" cellspacing="0" style="margin:0 auto;">
              <tr>
                <td align="center" style="background:rgba(255,255,255,.14);border:1px solid rgba(255,255,255,.35);border-radius:24px;padding:7px 18px;">
                  <span style="color:#ffffff;font-size:12px;font-weight:700;letter-spacing:.08em;">RECEIPT # {{ $receiptNo }}</span>
                </td>
              </tr>
            </table>

          </td>
        </tr>

        <!-- ======================= BODY ======================= -->
        <tr>
          <td class="card-pad" style="padding:36px 32px 10px;">

            <!-- Payment success tag -->
            <table role="presentation" cellpadding="0" cellspacing="0" style="margin:0 0 18px;">
              <tr>
                <td align="center" style="background:#ecfdf5;border:1px solid #a7f3d0;border-radius:20px;padding:5px 14px;">
                  <span style="color:#047857;font-size:11px;font-weight:700;letter-spacing:.08em;">● PAYMENT SUCCESSFUL</span>
                </td>
              </tr>
            </table>

            <!-- Greeting -->
            <h1 style="font-size:23px;color:#1e1b2e;margin:0 0 8px;font-weight:700;letter-spacing:-.01em;line-height:1.35;">
              Thank you, {{ $donorName }} <span style="font-weight:400;">❤️</span>
            </h1>
            <p style="font-size:14.5px;color:#6b7280;line-height:1.75;margin:0 0 30px;">
              Your donation was received successfully and this email serves as your official
              receipt. Here's a summary of your contribution.
            </p>

            <!-- =============== DONATION AMOUNT CARD =============== -->
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:28px;">
              <tr>
                <td bgcolor="#4f46e5" style="background-image:linear-gradient(135deg,#4338ca 0%,#6d28d9 55%,#7c3aed 100%);background-color:#4f46e5;border-radius:16px;padding:30px 24px;text-align:center;">
                  <p style="color:#c7d2fe;font-size:11px;font-weight:700;letter-spacing:.16em;text-transform:uppercase;margin:0 0 10px;">Donation Amount</p>
                  <p style="color:#ffffff;font-size:42px;font-weight:700;line-height:1.1;margin:0;letter-spacing:-.01em;">₹{{ number_format($amount, 2) }}</p>
                </td>
              </tr>
            </table>

            <!-- =============== CAMPAIGN CARD =============== -->
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:30px;">
              <tr>
                <td style="background:#f5f3ff;border:1px solid #e9e2fd;border-radius:16px;padding:22px 24px;">

                  @php
                    $categoryName = $campaign && $campaign->category ? $campaign->category->name : null;
                    $stateLabels = ['completed' => 'Goal Reached', 'expired' => 'Ended', 'urgent' => 'Urgent'];
                    $stateLabel = $campaign && isset($stateLabels[$campaign->campaign_state]) ? $stateLabels[$campaign->campaign_state] : null;
                  @endphp

                  <p style="color:#6d28d9;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.14em;margin:0 0 8px;">Supporting Campaign</p>
                  <p style="color:#1e1b2e;font-size:17px;font-weight:700;margin:0 0 8px;line-height:1.45;">{{ optional($campaign)->title ?: 'Campaign' }}</p>
                  <p style="color:#6b7280;font-size:13.5px;line-height:1.65;margin:0 0 14px;">
                    Your contribution helps this campaign move closer to its fundraising goal.
                  </p>

                  @if ($categoryName || $stateLabel)
                  <table role="presentation" cellpadding="0" cellspacing="0" style="margin:0;">
                    <tr>
                      @if ($categoryName)
                      <td style="padding-right:8px;">
                        <span style="display:inline-block;background:#e0e7ff;color:#4338ca;font-size:11px;font-weight:700;border-radius:6px;padding:4px 10px;">{{ $categoryName }}</span>
                      </td>
                      @endif
                      @if ($stateLabel)
                      <td>
                        <span style="display:inline-block;background:#fef3c7;color:#92400e;font-size:11px;font-weight:700;border-radius:6px;padding:4px 10px;">{{ $stateLabel }}</span>
                      </td>
                      @endif
                    </tr>
                  </table>
                  @endif

                </td>
              </tr>
            </table>

            <!-- =============== ACTIONS =============== -->
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:34px;">
              <tr>
                <td align="center" style="padding-bottom:12px;">
                  <!-- Primary action: Download Receipt PDF -->
                  <table role="presentation" cellpadding="0" cellspacing="0" style="width:100%;max-width:360px;">
                    <tr>
                      <td align="center" bgcolor="#4f46e5" style="background-image:linear-gradient(135deg,#4f46e5 0%,#7c3aed 100%);background-color:#4f46e5;border-radius:12px;">
                        <a href="{{ $receiptDownloadUrl }}" target="_blank" style="display:block;padding:16px 28px;color:#ffffff;font-size:15px;font-weight:700;text-decoration:none;line-height:1.2;">
                          <span style="display:inline-block;width:22px;height:22px;border-radius:5px;background:rgba(255,255,255,.22);border:1px solid rgba(255,255,255,.55);color:#ffffff;font-size:9px;font-weight:800;line-height:20px;text-align:center;margin-right:10px;vertical-align:middle;">PDF</span>
                          Download Receipt PDF
                        </a>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
              @isset($campaignUrl)
              <tr>
                <td align="center">
                  <!-- Secondary action: View campaign -->
                  <table role="presentation" cellpadding="0" cellspacing="0" style="width:100%;max-width:360px;">
                    <tr>
                      <td align="center" bgcolor="#ffffff" style="border:1.5px solid #c7d2fe;border-radius:12px;">
                        <a href="{{ $campaignUrl }}" target="_blank" style="display:block;padding:13px 28px;color:#4f46e5;font-size:14px;font-weight:600;text-decoration:none;line-height:1.2;">
                          View Campaign Progress&nbsp;→
                        </a>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
              @endisset
            </table>

            <!-- =============== RECEIPT DETAILS =============== -->
            @php
              $rows = [
                  ['Receipt Number', $receiptNo],
                  ['Date & Time', $paidAt
                      ? \Carbon\Carbon::parse($paidAt)->format('d M Y, h:i A')
                      : $donation->created_at->format('d M Y, h:i A')],
                  ['Payment Method', $paymentMethod],
                  ['Donation Amount', '₹'.number_format($amount, 2)],
                  ['Platform Fee (5%)', '₹'.number_format($platformFee, 2)],
              ];

              if ((float) ($discountAmount ?? 0) > 0) {
                  $rows[] = ['Coupon ('.($couponCode ?: '').')', '− ₹'.number_format($discountAmount, 2)];
              }

              if (! empty($paymentReference)) {
                  $rows[] = ['Payment Reference', $paymentReference];
              }
            @endphp

            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e9e6f5;border-radius:16px;margin-bottom:26px;">
              <tr>
                <td style="padding:22px 24px 10px;">
                  <p style="font-size:16px;font-weight:700;color:#1e1b2e;margin:0;">Receipt Details</p>
                  <p style="font-size:12px;color:#9ca3af;margin:3px 0 0;">Transaction summary</p>
                </td>
              </tr>

              @foreach ($rows as $row)
              <tr>
                <td style="padding:0 24px;border-top:1px solid #f0eef8;">
                  <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                      <td style="padding:12px 0;font-size:13px;color:#6b7280;">{{ $row[0] }}</td>
                      <td style="padding:12px 0;font-size:13px;color:#1e1b2e;font-weight:600;text-align:right;word-break:break-all;">{{ $row[1] }}</td>
                    </tr>
                  </table>
                </td>
              </tr>
              @endforeach

              <!-- Amount to Campaign — emphasised final row -->
              <tr>
                <td style="padding:0 24px 0;border-top:1px solid #e9e6f5;background:#f5f3ff;border-radius:0 0 15px 15px;">
                  <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                      <td style="padding:16px 0;font-size:14px;color:#3730a3;font-weight:700;">Amount to Campaign</td>
                      <td style="padding:16px 0;font-size:16px;color:#4338ca;font-weight:800;text-align:right;">₹{{ number_format($netAmount, 2) }}</td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>

            <!-- Official note -->
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:8px;">
              <tr>
                <td style="background:#f8fafc;border-left:4px solid #4f46e5;border-radius:10px;padding:16px 18px;">
                  <p style="color:#64748b;font-size:12.5px;line-height:1.7;margin:0;">
                    Please keep this email for your records — it serves as your official donation receipt.
                    A platform fee has been deducted to help maintain and operate the platform, while the
                    remaining amount is transferred toward the campaign.
                  </p>
                </td>
              </tr>
            </table>

          </td>
        </tr>

      </table>
    </td>
  </tr>

  <!-- ======================= FOOTER ======================= -->
  <tr>
    <td align="center" style="padding:28px 20px 10px;">
      <p style="color:#9ca3af;font-size:12.5px;margin:0 0 12px;line-height:1.7;">Thank you for making a difference.</p>
      <p style="color:#8b87a3;font-size:12px;margin:0 0 10px;line-height:1.7;">
        © {{ date('Y') }} DONATEBAZAAR. All rights reserved.
      </p>
      <p style="color:#8b87a3;font-size:12px;margin:0 0 4px;">
        <a href="{{ config('app.url') }}" style="color:#4f46e5;text-decoration:none;">www.donatebazaar.com</a>
        &nbsp;&middot;&nbsp;
        <a href="mailto:support@donatebazaar.com" style="color:#4f46e5;text-decoration:none;">support@donatebazaar.com</a>
      </p>
    </td>
  </tr>

</table>

</td></tr>
</table>

</body>
</html>