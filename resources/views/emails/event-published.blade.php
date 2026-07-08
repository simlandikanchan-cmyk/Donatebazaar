<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="color-scheme" content="light">
<title>New Event Published</title>
</head>
<body style="margin:0;padding:0;background:#eef1f8;font-family:Arial,Helvetica,sans-serif;-webkit-text-size-adjust:100%;">

<span style="display:none;font-size:1px;color:#eef1f8;line-height:1px;max-height:0;max-width:0;opacity:0;overflow:hidden;">
    A new event, {{ $event->title }}, has been published on {{ $event->campaign->title ?? config('app.name') }}.
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
          <td bgcolor="#6e56f7" style="background-image:linear-gradient(135deg,#6e56f7,#9b6dff);background-color:#6e56f7;padding:38px 30px 34px;text-align:center;">
            <table role="presentation" align="center" cellpadding="0" cellspacing="0" style="margin:0 auto 16px;">
              <tr><td style="width:54px;height:54px;border-radius:50%;background:rgba(255,255,255,.16);text-align:center;vertical-align:middle;font-size:26px;line-height:54px;color:#ffffff;">&#9201;</td></tr>
            </table>
            <p style="color:#ffffff;font-size:21px;font-weight:700;margin:0 0 4px;">New Event Published!</p>
            <p style="color:rgba(255,255,255,.8);font-size:13px;margin:0;">A fresh event is now live on the campaign you follow</p>
          </td>
        </tr>

        <!-- Body -->
        <tr>
          <td style="padding:34px 30px 8px;">

            <h1 style="font-size:22px;color:#111827;margin:0 0 10px;font-weight:700;">
              Hi {{ $recipient->name }},
            </h1>
            <p style="font-size:14.5px;color:#6b7280;line-height:1.7;margin:0 0 24px;">
              A new event, <strong>{{ $event->title }}</strong>, has just been published
              @if($event->campaign)<strong> for the {{ $event->campaign->title }} campaign</strong>@endif.
              We thought you'd like to know so you can join in and support the cause.
            </p>

            <!-- Details card -->
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e5e7eb;border-radius:14px;margin-bottom:24px;">

              @php
                $rows = [
                    ['Event', $event->title],
                ];
                if ($event->event_date) {
                    $rows[] = ['Date', \Carbon\Carbon::parse($event->event_date)->format('l, F j, Y')];
                }
                if ($event->start_time) {
                    $time = \Carbon\Carbon::parse($event->start_time)->format('g:i A');
                    if ($event->end_time) {
                        $time .= ' &ndash; ' . \Carbon\Carbon::parse($event->end_time)->format('g:i A');
                    }
                    $rows[] = ['Time', $time];
                }
                if ($event->location) {
                    $rows[] = ['Location', $event->location];
                }
                if ($event->goal_amount) {
                    $rows[] = ['Fundraising Goal', '&#8377;' . number_format($event->goal_amount, 0)];
                }
              @endphp

              @foreach ($rows as $i => $row)
              <tr>
                <td style="padding:{{ $i === 0 ? '18px' : '0' }} 20px 0;{{ $i > 0 ? 'border-top:1px solid #f1f5f9;' : '' }}">
                  <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                      <td style="padding:12px 0;font-size:13.5px;color:#6b7280;width:150px;">{{ $row[0] }}</td>
                      <td style="padding:12px 0;font-size:13.5px;color:#111827;font-weight:600;">{!! $row[1] !!}</td>
                    </tr>
                  </table>
                </td>
              </tr>
              @endforeach

            </table>

            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:8px;">
              <tr>
                <td align="center" style="padding:6px 0 26px;">
                  <a href="{{ $eventUrl }}" style="display:inline-block;background:linear-gradient(135deg,#6e56f7,#9b6dff);color:#ffffff;text-decoration:none;font-size:14px;font-weight:700;padding:13px 30px;border-radius:12px;">View Event &amp; Register</a>
                </td>
              </tr>
            </table>

            <p style="font-size:14.5px;color:#6b7280;line-height:1.7;margin:0 0 6px;">
              Your support makes a real difference. We hope to see you there!
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
        You received this email because you follow
        <strong>{{ $event->campaign->title ?? config('app.name') }}</strong> and have notifications enabled.
      </p>
    </td>
  </tr>

</table>

</td></tr>
</table>

</body>
</html>
