<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="color-scheme" content="light">
<title>Volunteer Application Received</title>
</head>
<body style="margin:0;padding:0;background:#eef1f8;font-family:Arial,Helvetica,sans-serif;-webkit-text-size-adjust:100%;">

<span style="display:none;font-size:1px;color:#eef1f8;line-height:1px;max-height:0;max-width:0;opacity:0;overflow:hidden;">
    Your volunteer application has been submitted successfully.
</span>

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#eef1f8;">
<tr><td align="center" style="padding:32px 16px;">

<table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;">

  <tr>
    <td style="background:#ffffff;border-radius:18px;overflow:hidden;border:1px solid #e9ecf5;">
      <table role="presentation" width="100%" cellpadding="0" cellspacing="0">

        <tr>
          <td bgcolor="#2563eb" style="background-image:linear-gradient(135deg,#2563eb,#0f766e);background-color:#2563eb;padding:38px 30px 34px;text-align:center;">
            <table role="presentation" align="center" cellpadding="0" cellspacing="0" style="margin:0 auto 16px;">
              <tr><td style="width:54px;height:54px;border-radius:50%;background:rgba(255,255,255,.16);text-align:center;vertical-align:middle;font-size:26px;line-height:54px;color:#ffffff;">&#10084;</td></tr>
            </table>
            <p style="color:#ffffff;font-size:21px;font-weight:700;margin:0 0 4px;">Application Received!</p>
            <p style="color:#dbeafe;font-size:13px;margin:0;">Thank you for volunteering with {{ config('app.name') }}</p>
          </td>
        </tr>

        <tr>
          <td style="padding:34px 30px 8px;">

            <h1 style="font-size:22px;color:#111827;margin:0 0 10px;font-weight:700;">Hi {{ $application->volunteer->user->name }},</h1>

            <p style="font-size:14.5px;color:#6b7280;line-height:1.7;margin:0 0 24px;">
              We've received your volunteer application and it's now under review.
              Our team will get back to you once it's been processed.
            </p>

            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e5e7eb;border-radius:14px;margin-bottom:24px;">
              <tr>
                <td style="padding:18px 20px 0;">
                  <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                      <td style="padding:12px 0;font-size:13.5px;color:#6b7280;width:140px;">Status</td>
                      <td style="padding:12px 0;">
                        <span style="background:#fef3c7;color:#b45309;font-size:12px;font-weight:700;padding:4px 10px;border-radius:20px;">Pending Review</span>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td style="padding:0 20px 18px;border-top:1px solid #f1f5f9;">
                  <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                      <td style="padding:12px 0;font-size:13.5px;color:#6b7280;width:140px;">Applied On</td>
                      <td style="padding:12px 0;font-size:13.5px;color:#111827;font-weight:600;">{{ $application->created_at->format('d M Y, h:i A') }}</td>
                    </tr>
                  </table>
                </td>
              </tr>
              @if($application->campaign)
              <tr>
                <td style="padding:0 20px 18px;border-top:1px solid #f1f5f9;">
                  <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                      <td style="padding:12px 0;font-size:13.5px;color:#6b7280;width:140px;">Campaign</td>
                      <td style="padding:12px 0;font-size:13.5px;color:#111827;font-weight:600;">{{ $application->campaign->title }}</td>
                    </tr>
                  </table>
                </td>
              </tr>
              @endif
            </table>

            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:30px;">
              <tr>
                <td align="center">
                  <a href="{{ url('/volunteer/apply') }}"
                     style="display:inline-block;background:#2563eb;color:#ffffff;font-size:14px;font-weight:600;text-decoration:none;padding:13px 32px;border-radius:9px;">
                    View Status &rarr;
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
        &copy; {{ date('Y') }} {{ config('app.name') }}. This is an automated notification.
      </p>
    </td>
  </tr>

</table>

</td></tr>
</table>

</body>
</html>
