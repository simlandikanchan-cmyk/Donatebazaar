<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="color-scheme" content="light">
<title>New Job Application</title>
</head>
<body style="margin:0;padding:0;background:#eef1f8;font-family:Arial,Helvetica,sans-serif;-webkit-text-size-adjust:100%;">

<span style="display:none;font-size:1px;color:#eef1f8;line-height:1px;max-height:0;max-width:0;opacity:0;overflow:hidden;">
    New application from {{ $application->name }} for {{ $application->jobPost->title ?? 'a position' }}.
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
          <td bgcolor="#4f46e5" style="background-image:linear-gradient(135deg,#4f46e5,#7c3aed);background-color:#4f46e5;padding:38px 30px 34px;text-align:center;">
            <table role="presentation" align="center" cellpadding="0" cellspacing="0" style="margin:0 auto 16px;">
              <tr><td style="width:54px;height:54px;border-radius:50%;background:rgba(255,255,255,.16);text-align:center;vertical-align:middle;font-size:26px;line-height:54px;color:#ffffff;">&#128188;</td></tr>
            </table>
            <p style="color:#ffffff;font-size:21px;font-weight:700;margin:0 0 4px;">New Job Application</p>
            <p style="color:#ddd6fe;font-size:13px;margin:0;">Someone applied for a position at {{ config('app.name') }}</p>
          </td>
        </tr>

        <!-- Body -->
        <tr>
          <td style="padding:34px 30px 8px;">

            <h1 style="font-size:22px;color:#111827;margin:0 0 16px;font-weight:700;">
              Application Details
            </h1>

            <!-- Details table -->
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e5e7eb;border-radius:14px;margin-bottom:24px;">

              @php
                $rows = [
                    ['Position', $application->jobPost->title ?? 'N/A'],
                    ['Applicant Name', $application->name],
                    ['Email', $application->email],
                    ['Phone', $application->phone ?? '&mdash;'],
                    ['Applied On', $application->created_at->format('d M Y, h:i A')],
                ];
              @endphp

              @foreach ($rows as $i => $row)
              <tr>
                <td style="padding:{{ $i === 0 ? '18px' : '0' }} 20px {{ $i === count($rows) - 1 ? '18px' : '0' }};{{ $i > 0 ? 'border-top:1px solid #f1f5f9;' : '' }}">
                  <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                      <td style="padding:12px 0;font-size:13.5px;color:#6b7280;width:140px;">{{ $row[0] }}</td>
                      <td style="padding:12px 0;font-size:13.5px;color:#111827;font-weight:600;">{!! $row[1] !!}</td>
                    </tr>
                  </table>
                </td>
              </tr>
              @endforeach

            </table>

            @if($application->cover_letter)
            <p style="color:#111827;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;margin:0 0 10px;">Cover Letter</p>
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:26px;">
              <tr>
                <td style="background:#f8fafc;border-left:4px solid #4f46e5;border-radius:8px;padding:16px 18px;">
                  <p style="color:#374151;font-size:13.5px;line-height:1.7;margin:0;">{{ $application->cover_letter }}</p>
                </td>
              </tr>
            </table>
            @endif

            <!-- CTA -->
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:30px;">
              <tr>
                <td align="center">
                  <a href="{{ url('/admin/job_post_applications') }}"
                     style="display:inline-block;background:#4f46e5;color:#ffffff;font-size:14px;font-weight:600;text-decoration:none;padding:13px 32px;border-radius:9px;">
                    View in Admin Panel&nbsp;&rarr;
                  </a>
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