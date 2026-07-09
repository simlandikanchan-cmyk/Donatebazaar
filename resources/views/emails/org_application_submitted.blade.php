<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="color-scheme" content="light">
<title>NGO Application Received</title>
</head>
<body style="margin:0;padding:0;background:#eef1f8;font-family:Arial,Helvetica,sans-serif;-webkit-text-size-adjust:100%;">

<span style="display:none;font-size:1px;color:#eef1f8;line-height:1px;max-height:0;max-width:0;opacity:0;overflow:hidden;">
    Thank you for submitting your NGO application for {{ $organization }}.
</span>

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#eef1f8;">
<tr><td align="center" style="padding:32px 16px;">

<table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;">

  <tr>
    <td style="background:#ffffff;border-radius:18px;overflow:hidden;border:1px solid #e9ecf5;">
      <table role="presentation" width="100%" cellpadding="0" cellspacing="0">

        <tr>
          <td bgcolor="#4f46e5" style="background-image:linear-gradient(135deg,#4f46e5,#7c3aed);background-color:#4f46e5;padding:38px 30px 34px;text-align:center;">
            <table role="presentation" align="center" cellpadding="0" cellspacing="0" style="margin:0 auto 16px;">
              <tr><td style="width:54px;height:54px;border-radius:50%;background:rgba(255,255,255,.16);text-align:center;vertical-align:middle;font-size:26px;line-height:54px;color:#ffffff;">&#128640;</td></tr>
            </table>
            <p style="color:#ffffff;font-size:21px;font-weight:700;margin:0 0 4px;">Application Received</p>
            <p style="color:#ddd6fe;font-size:13px;margin:0;">We've received your NGO onboarding request</p>
          </td>
        </tr>

        <tr>
          <td style="padding:34px 30px 8px;">

            <h1 style="font-size:22px;color:#111827;margin:0 0 16px;font-weight:700;">
              Hi {{ $applicant }},
            </h1>

            <p style="font-size:14.5px;color:#6b7280;line-height:1.7;margin:0 0 24px;">
              Thank you for applying to join {{ config('app.name') }} as an NGO. We have
              successfully received your application for <strong>{{ $organization }}</strong>
              and our team will review it shortly.
            </p>

            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e5e7eb;border-radius:14px;margin-bottom:24px;">
              @php
                $rows = [
                    ['Organization', $organization],
                    ['Type', $type],
                    ['Submitted', $submittedAt ? $submittedAt->format('d M Y, h:i A') : now()->format('d M Y, h:i A')],
                ];
              @endphp
              @foreach ($rows as $i => $row)
              <tr>
                <td style="padding:{{ $i === 0 ? '18px' : '0' }} 20px 0;{{ $i > 0 ? 'border-top:1px solid #f1f5f9;' : '' }}">
                  <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                      <td style="padding:12px 0;font-size:13.5px;color:#6b7280;width:140px;">{{ $row[0] }}</td>
                      <td style="padding:12px 0;font-size:13.5px;color:#111827;font-weight:600;">{{ $row[1] }}</td>
                    </tr>
                  </table>
                </td>
              </tr>
              @endforeach
            </table>

            <p style="font-size:13px;color:#9ca3af;line-height:1.7;margin:0;">
              You'll receive another email once our team has reviewed your application.
            </p>

          </td>
        </tr>

      </table>
    </td>
  </tr>

  <tr>
    <td align="center" style="padding:26px 20px 8px;">
      <p style="color:#6b7280;font-size:12px;margin:0 0 4px;line-height:1.7;">
        &copy; {{ date('Y') }} {{ config('app.name') }}. This is an automated notification.
      </p>
    </td>
  </tr>

</table>

</td></tr>
</table>

</body>
</html>
