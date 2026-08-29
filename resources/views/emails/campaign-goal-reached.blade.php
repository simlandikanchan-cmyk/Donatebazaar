<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta color-scheme="content="light">
<title>Campaign Goal Reached — DONATEBAZAAR</title>
<style>
@media only screen and (max-width:520px){
  .card-pad{ padding-left:20px !important; padding-right:20px !important; }
}
</style>
</head>
<body style="margin:0;padding:0;background:#eef1f8;font-family:Arial,Helvetica,sans-serif;-webkit-text-size-adjust:100%;">

<span style="display:none;font-size:1px;color:#eef1f8;line-height:1px;max-height:0;max-width:0;opacity:0;overflow:hidden;">
    Congratulations! Your campaign has reached its fundraising goal.
</span>

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#eef1f8;">
<tr><td align="center" class="card-pad" style="padding:32px 16px;">

<table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;">

  <tr>
    <td style="background:#ffffff;border-radius:18px;overflow:hidden;border:1px solid #e9ecf5;">
      <table role="presentation" width="100%" cellpadding="0" cellspacing="0">

        <tr>
          <td bgcolor="#059669" style="background-image:linear-gradient(135deg,#059669,#34d399);background-color:#059669;padding:38px 30px 34px;text-align:center;">
            <table role="presentation" align="center" cellpadding="0" cellspacing="0" style="margin:0 auto 16px;">
              <tr><td style="width:54px;height:54px;border-radius:50%;background:rgba(255,255,255,.16);text-align:center;vertical-align:middle;font-size:26px;line-height:54px;color:#ffffff;">🎉</td></tr>
            </table>
            <p style="color:#ffffff;font-size:21px;font-weight:700;margin:0 0 4px;">Goal Reached!</p>
            <p style="color:rgba(255,255,255,.7);font-size:13px;margin:0;">Your campaign has hit its target</p>
          </td>
        </tr>

        <tr>
          <td style="padding:34px 30px 8px;">

            <h1 style="font-size:22px;color:#111827;margin:0 0 10px;font-weight:700;">
              Congratulations, {{ $user->name }}!
            </h1>
            <p style="font-size:14.5px;color:#6b7280;line-height:1.7;margin:0 0 24px;">
              Amazing news! Your campaign <strong>"{{ $campaign->title }}"</strong> has reached its
              fundraising goal of <strong>₹{{ number_format($campaign->goal_amount, 2) }}</strong>.
              This is a huge milestone — thank you for your incredible effort and the generosity of your supporters.
            </p>

            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:24px;">
              <tr>
                <td style="background:#ecfdf5;border:1px solid #a7f3d0;border-radius:14px;padding:20px 22px;">
                  <p style="color:#047857;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;margin:0 0 7px;">Campaign Details</p>
                  <p style="color:#111827;font-size:17px;font-weight:700;margin:0 0 4px;line-height:1.4;">{{ $campaign->title }}</p>
                  <p style="color:#475569;font-size:13.5px;margin:0 0 2px;">Goal: ₹{{ number_format($campaign->goal_amount, 2) }}</p>
                  <p style="color:#475569;font-size:13.5px;margin:0;">Status: <span style="color:#059669;font-weight:600;">Goal Reached</span></p>
                </td>
              </tr>
            </table>

            <p style="font-size:14.5px;color:#6b7280;line-height:1.7;margin:0 0 28px;">
              Keep the momentum going! Share your campaign with more people to exceed your goal even further.
              You can also prepare for the next steps — our team will guide you through the payout process.
            </p>

            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:30px;">
              <tr>
                <td align="center">
                  <a href="{{ route('campaign.show', $campaign->slug ?? $campaign->id) }}"
                     style="display:inline-block;background:#059669;color:#ffffff;font-size:14px;font-weight:600;text-decoration:none;padding:13px 32px;border-radius:9px;">
                    View Your Campaign&nbsp;&rarr;
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
