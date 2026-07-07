<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="color-scheme" content="light">
<title>Blog Submitted</title>
</head>
<body style="margin:0;padding:0;background:#eef1f8;font-family:Arial,Helvetica,sans-serif;-webkit-text-size-adjust:100%;">

<span style="display:none;font-size:1px;color:#eef1f8;line-height:1px;max-height:0;max-width:0;opacity:0;overflow:hidden;">
    Your blog "{{ $blog->title }}" has been submitted.
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
              <tr><td style="width:54px;height:54px;border-radius:50%;background:rgba(255,255,255,.16);text-align:center;vertical-align:middle;font-size:26px;line-height:54px;color:#ffffff;">&#9997;</td></tr>
            </table>
            <p style="color:#ffffff;font-size:21px;font-weight:700;margin:0 0 4px;">Blog Submitted</p>
            <p style="color:#ddd6fe;font-size:13px;margin:0;">Thank you for your contribution</p>
          </td>
        </tr>

        <tr>
          <td style="padding:34px 30px 8px;">

            <h1 style="font-size:22px;color:#111827;margin:0 0 10px;font-weight:700;">
              Hi {{ $user->name }},
            </h1>
            <p style="font-size:14.5px;color:#6b7280;line-height:1.7;margin:0 0 24px;">
              Your blog <strong>"{{ $blog->title }}"</strong> has been submitted successfully.
              @if($blog->status === 'pending')
                It is now pending review by our team. We'll notify you once it's approved.
              @else
                It has been published and is now visible to our readers.
              @endif
            </p>

            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:24px;">
              <tr>
                <td style="background:#eff6ff;border:1px solid #dbeafe;border-radius:14px;padding:20px 22px;">
                  <p style="color:#1e40af;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;margin:0 0 7px;">Blog Details</p>
                  <p style="color:#111827;font-size:17px;font-weight:700;margin:0 0 4px;line-height:1.4;">{{ $blog->title }}</p>
                  @if($blog->excerpt)
                    <p style="color:#475569;font-size:13.5px;margin:0 0 2px;line-height:1.5;">{{ $blog->excerpt }}</p>
                  @endif
                  <p style="color:#475569;font-size:13.5px;margin:0;">
                    Status: <span style="color:{{ $blog->status === 'published' ? '#059669' : '#f59e0b' }};font-weight:600;">{{ ucfirst($blog->status) }}</span>
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
