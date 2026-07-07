<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="color-scheme" content="light">
<title>KYC Reminder</title>
</head>
<body style="margin:0;padding:0;background:#eef1f8;font-family:Arial,Helvetica,sans-serif;-webkit-text-size-adjust:100%;">

<span style="display:none;font-size:1px;color:#eef1f8;line-height:1px;max-height:0;max-width:0;opacity:0;overflow:hidden;">
    Reminder: Upload your KYC documents to activate "{{ $campaign->title }}".
</span>

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#eef1f8;">
<tr><td align="center" style="padding:32px 16px;">

<table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;">

  <tr>
    <td style="background:#ffffff;border-radius:18px;overflow:hidden;border:1px solid #e9ecf5;">
      <table role="presentation" width="100%" cellpadding="0" cellspacing="0">

        <tr>
          <td bgcolor="#f59e0b" style="background-image:linear-gradient(135deg,#f59e0b,#f97316);background-color:#f59e0b;padding:38px 30px 34px;text-align:center;">
            <table role="presentation" align="center" cellpadding="0" cellspacing="0" style="margin:0 auto 16px;">
              <tr><td style="width:54px;height:54px;border-radius:50%;background:rgba(255,255,255,.16);text-align:center;vertical-align:middle;font-size:26px;line-height:54px;color:#ffffff;">&#9200;</td></tr>
            </table>
            <p style="color:#ffffff;font-size:21px;font-weight:700;margin:0 0 4px;">KYC Upload Reminder</p>
            <p style="color:#fef3c7;font-size:13px;margin:0;">Action required to activate your campaign</p>
          </td>
        </tr>

        <tr>
          <td style="padding:34px 30px 8px;">

            <h1 style="font-size:22px;color:#111827;margin:0 0 10px;font-weight:700;">
              Hi {{ $user->name }},
            </h1>
            <p style="font-size:14.5px;color:#6b7280;line-height:1.7;margin:0 0 24px;">
              We noticed you haven't completed your KYC verification for the campaign
              <strong>"{{ $campaign->title }}"</strong>. Your campaign cannot be approved and go live
              until you upload your KYC documents.
            </p>

            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:24px;">
              <tr>
                <td style="background:#fffbeb;border:1px solid #fde68a;border-radius:14px;padding:20px 22px;">
                  <p style="color:#92400e;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;margin:0 0 7px;">Campaign Details</p>
                  <p style="color:#111827;font-size:17px;font-weight:700;margin:0 0 4px;line-height:1.4;">{{ $campaign->title }}</p>
                  <p style="color:#475569;font-size:13.5px;margin:0 0 2px;">
                    Goal: &#8377;{{ number_format($campaign->goal_amount, 2) }}
                  </p>
                  <p style="color:#475569;font-size:13.5px;margin:0;">
                    Status: <span style="color:#f59e0b;font-weight:600;">Pending KYC</span>
                  </p>
                </td>
              </tr>
            </table>

            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:30px;">
              <tr>
                <td align="center">
                  <a href="{{ route('kyc.upload.form', $campaign->id) }}"
                     style="display:inline-block;background:#4f46e5;color:#ffffff;font-size:14px;font-weight:600;text-decoration:none;padding:13px 32px;border-radius:9px;">
                    Upload KYC Now&nbsp;&rarr;
                  </a>
                </td>
              </tr>
            </table>

            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:6px;">
              <tr>
                <td style="background:#f8fafc;border-left:4px solid #f59e0b;border-radius:8px;padding:16px 18px;">
                  <p style="color:#64748b;font-size:12.5px;line-height:1.7;margin:0;">
                    KYC verification is mandatory before your campaign can be approved. The process
                    takes just a few minutes. If you have already submitted your documents, please
                    ignore this reminder.
                  </p>
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
