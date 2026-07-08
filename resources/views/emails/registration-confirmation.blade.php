<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="color-scheme" content="light">
<title>Registration Confirmed</title>
</head>
<body style="margin:0;padding:0;background:#eef1f8;font-family:Arial,Helvetica,sans-serif;-webkit-text-size-adjust:100%;">

<span style="display:none;font-size:1px;color:#eef1f8;line-height:1px;max-height:0;max-width:0;opacity:0;overflow:hidden;">
    You're registered for {{ $event->title }} &mdash; here are your details, {{ $registration->name }}.
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
          <td bgcolor="#059669" style="background-image:linear-gradient(135deg,#059669,#34d399);background-color:#059669;padding:38px 30px 34px;text-align:center;">
            <table role="presentation" align="center" cellpadding="0" cellspacing="0" style="margin:0 auto 16px;">
              <tr><td style="width:54px;height:54px;border-radius:50%;background:rgba(255,255,255,.16);text-align:center;vertical-align:middle;font-size:26px;line-height:54px;color:#ffffff;">&#10003;</td></tr>
            </table>
            <p style="color:#ffffff;font-size:21px;font-weight:700;margin:0 0 4px;">You're Registered!</p>
            <p style="color:rgba(255,255,255,.8);font-size:13px;margin:0;">Your spot has been confirmed</p>
          </td>
        </tr>

        <!-- Body -->
        <tr>
          <td style="padding:34px 30px 8px;">

            <h1 style="font-size:22px;color:#111827;margin:0 0 10px;font-weight:700;">
              Hi {{ $registration->name }},
            </h1>
            <p style="font-size:14.5px;color:#6b7280;line-height:1.7;margin:0 0 24px;">
              Thank you for registering for <strong>{{ $event->title }}</strong>. Your spot has been
              confirmed. Here's a summary of your registration:
            </p>

            <!-- Details card -->
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e5e7eb;border-radius:14px;margin-bottom:24px;">

              @php
                $rows = [
                    ['Event', $event->title],
                    ['Date', \Carbon\Carbon::parse($event->event_date)->format('l, F j, Y')],
                ];
                if ($event->start_time) {
                    $time = \Carbon\Carbon::parse($event->start_time)->format('g:i A');
                    if ($event->end_time) {
                        $time .= ' &ndash; ' . \Carbon\Carbon::parse($event->end_time)->format('g:i A');
                    }
                    $rows[] = ['Time', $time];
                }
                $rows[] = ['Name', $registration->name];
                $rows[] = ['Email', $registration->email];
                $rows[] = ['Phone', $registration->phone];
              @endphp

              @foreach ($rows as $i => $row)
              <tr>
                <td style="padding:{{ $i === 0 ? '18px' : '0' }} 20px 0;{{ $i > 0 ? 'border-top:1px solid #f1f5f9;' : '' }}">
                  <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                      <td style="padding:12px 0;font-size:13.5px;color:#6b7280;width:140px;">{{ $row[0] }}</td>
                      <td style="padding:12px 0;font-size:13.5px;color:#111827;font-weight:600;">{!! $row[1] !!}</td>
                    </tr>
                  </table>
                </td>
              </tr>
              @endforeach

              <tr>
                <td style="padding:0 20px 18px;border-top:1px solid #f1f5f9;">
                  <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                      <td style="padding:12px 0;font-size:13.5px;color:#6b7280;width:140px;">Status</td>
                      <td style="padding:12px 0;">
                        <span style="display:inline-block;background:#dcfce7;color:#15803d;padding:4px 12px;border-radius:99px;font-size:12.5px;font-weight:600;">Registered</span>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>

            @if($registration->message)
            <p style="color:#111827;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;margin:0 0 10px;">Your Note</p>
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:24px;">
              <tr>
                <td style="background:#f8fafc;border-left:4px solid #059669;border-radius:8px;padding:16px 18px;">
                  <p style="color:#374151;font-size:13.5px;line-height:1.7;margin:0;">{{ $registration->message }}</p>
                </td>
              </tr>
            </table>
            @endif

            <p style="font-size:14.5px;color:#6b7280;line-height:1.7;margin:0 0 6px;">
              We look forward to seeing you there! If you have any questions, please don't hesitate
              to reach out to the event organiser.
            </p>

          </td>
        </tr>

      </table>
    </td>
  </tr>

  <!-- Footer -->
  <tr>
    <td align="center" style="padding:26px 20px 8px;">
      <p style="color:#6b7280;font-size:12px;margin:0 0 10px;line-height:1.7;">
        &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
      </p>
      <p style="color:#6b7280;font-size:12px;margin:0 0 4px;">
        This email was sent because you registered for an event on
        <a href="{{ config('app.url') }}" style="color:#4f46e5;text-decoration:none;">{{ config('app.name') }}</a>.
      </p>
    </td>
  </tr>

</table>

</td></tr>
</table>

</body>
</html>