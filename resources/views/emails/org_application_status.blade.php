<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="color-scheme" content="light">
<title>NGO Application Status Update</title>
</head>
<body style="margin:0;padding:0;background:#eef1f8;font-family:Arial,Helvetica,sans-serif;-webkit-text-size-adjust:100%;">

<span style="display:none;font-size:1px;color:#eef1f8;line-height:1px;max-height:0;max-width:0;opacity:0;overflow:hidden;">
    Your NGO application for {{ $organization }} has been {{ strtolower($status) }}.
</span>

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#eef1f8;">
<tr><td align="center" style="padding:32px 16px;">

<table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;">

  <tr>
    <td style="background:#ffffff;border-radius:18px;overflow:hidden;border:1px solid #e9ecf5;">
      <table role="presentation" width="100%" cellpadding="0" cellspacing="0">

        <tr>
          <td bgcolor="{{ $isApproved ? '#059669' : '#dc2626' }}" style="background-image:{{ $isApproved ? 'linear-gradient(135deg,#059669,#34d399)' : 'linear-gradient(135deg,#dc2626,#f87171)' }};background-color:{{ $isApproved ? '#059669' : '#dc2626' }};padding:38px 30px 34px;text-align:center;">
            <table role="presentation" align="center" cellpadding="0" cellspacing="0" style="margin:0 auto 16px;">
              <tr><td style="width:54px;height:54px;border-radius:50%;background:rgba(255,255,255,.16);text-align:center;vertical-align:middle;font-size:26px;line-height:54px;color:#ffffff;">{{ $isApproved ? '&#10003;' : '&#10007;' }}</td></tr>
            </table>
            <p style="color:#ffffff;font-size:21px;font-weight:700;margin:0 0 4px;">
              Application {{ ucfirst($status) }}
            </p>
            <p style="color:rgba(255,255,255,.8);font-size:13px;margin:0;">
              {{ $isApproved ? 'Your NGO has been onboarded' : 'Update on your application' }}
            </p>
          </td>
        </tr>

        <tr>
          <td style="padding:34px 30px 8px;">

            <h1 style="font-size:22px;color:#111827;margin:0 0 10px;font-weight:700;">
              Hi {{ $applicant }},
            </h1>

            @if($isApproved)
              <p style="font-size:14.5px;color:#6b7280;line-height:1.7;margin:0 0 24px;">
                Great news! Your application for <strong>{{ $organization }}</strong> has been reviewed and
                <strong style="color:#059669;">approved</strong>. Your NGO is now onboarded on
                {{ config('app.name') }} and you can start creating campaigns.
              </p>
            @else
              <p style="font-size:14.5px;color:#6b7280;line-height:1.7;margin:0 0 24px;">
                Thank you for applying to join {{ config('app.name') }}. After careful review,
                we're unable to approve your application for <strong>{{ $organization }}</strong> at this time.
              </p>
            @endif

            @if($adminNotes)
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:24px;">
              <tr>
                <td style="background:{{ $isApproved ? '#f8fafc' : '#fef2f2' }};border:1px solid {{ $isApproved ? '#e5e7eb' : '#fecaca' }};border-radius:14px;padding:20px 22px;">
                  <p style="color:{{ $isApproved ? '#1e40af' : '#991b1b' }};font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;margin:0 0 7px;">Note from our team</p>
                  <p style="color:{{ $isApproved ? '#475569' : '#b91c1c' }};font-size:14px;margin:0;line-height:1.6;">{{ $adminNotes }}</p>
                </td>
              </tr>
            </table>
            @endif

            @if(!$isApproved && $rejectionReason)
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:24px;">
              <tr>
                <td style="background:#fef2f2;border:1px solid #fecaca;border-radius:14px;padding:20px 22px;">
                  <p style="color:#991b1b;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;margin:0 0 7px;">Reason</p>
                  <p style="color:#b91c1c;font-size:14px;margin:0;line-height:1.6;">{{ $rejectionReason }}</p>
                </td>
              </tr>
            </table>
            @endif

            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e5e7eb;border-radius:14px;margin-bottom:6px;">
              <tr>
                <td style="padding:18px 20px 0;">
                  <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                      <td style="padding:12px 0;font-size:13.5px;color:#6b7280;width:140px;">Organization</td>
                      <td style="padding:12px 0;font-size:13.5px;color:#111827;font-weight:600;">{{ $organization }}</td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td style="padding:0 20px 18px;border-top:1px solid #f1f5f9;">
                  <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                      <td style="padding:12px 0;font-size:13.5px;color:#6b7280;width:140px;">Status</td>
                      <td style="padding:12px 0;">
                        <span style="color:{{ $isApproved ? '#059669' : '#dc2626' }};font-weight:600;font-size:13.5px;">{{ ucfirst($status) }}</span>
                      </td>
                    </tr>
                  </table>
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
