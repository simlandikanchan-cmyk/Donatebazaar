<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="color-scheme" content="light">
<title>Product Status Update</title>
</head>
<body style="margin:0;padding:0;background:#eef1f8;font-family:Arial,Helvetica,sans-serif;-webkit-text-size-adjust:100%;">

<span style="display:none;font-size:1px;color:#eef1f8;line-height:1px;max-height:0;max-width:0;opacity:0;overflow:hidden;">
    Your product "{{ $product->name }}" has been {{ $status === 'approved' ? 'approved' : 'rejected' }}.
</span>

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#eef1f8;">
<tr><td align="center" style="padding:32px 16px;">

<table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;">

  <tr>
    <td style="background:#ffffff;border-radius:18px;overflow:hidden;border:1px solid #e9ecf5;">
      <table role="presentation" width="100%" cellpadding="0" cellspacing="0">

        <tr>
          <td bgcolor="{{ $status === 'approved' ? '#059669' : '#dc2626' }}" style="background-image:{{ $status === 'approved' ? 'linear-gradient(135deg,#059669,#34d399)' : 'linear-gradient(135deg,#dc2626,#f87171)' }};background-color:{{ $status === 'approved' ? '#059669' : '#dc2626' }};padding:38px 30px 34px;text-align:center;">
            <table role="presentation" align="center" cellpadding="0" cellspacing="0" style="margin:0 auto 16px;">
              <tr><td style="width:54px;height:54px;border-radius:50%;background:rgba(255,255,255,.16);text-align:center;vertical-align:middle;font-size:26px;line-height:54px;color:#ffffff;">{{ $status === 'approved' ? '✓' : '✕' }}</td></tr>
            </table>
            <p style="color:#ffffff;font-size:21px;font-weight:700;margin:0 0 4px;">
              Product {{ $status === 'approved' ? 'Approved' : 'Rejected' }}
            </p>
          </td>
        </tr>

        <tr>
          <td style="padding:34px 30px 8px;">

            <h1 style="font-size:22px;color:#111827;margin:0 0 10px;font-weight:700;">
              Hi {{ $user->name }},
            </h1>

            @if($status === 'approved')
              <p style="font-size:14.5px;color:#6b7280;line-height:1.7;margin:0 0 24px;">
                Your product <strong>"{{ $product->name }}"</strong> has been reviewed and
                <strong style="color:#059669;">approved</strong>. It is now visible on your campaign page
                and available for donors to purchase.
              </p>
            @else
              <p style="font-size:14.5px;color:#6b7280;line-height:1.7;margin:0 0 24px;">
                Unfortunately, your product <strong>"{{ $product->name }}"</strong> has been
                <strong style="color:#dc2626;">rejected</strong> after review.
              </p>
              @if($reason)
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:24px;">
                  <tr>
                    <td style="background:#fef2f2;border:1px solid #fecaca;border-radius:14px;padding:20px 22px;">
                      <p style="color:#991b1b;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;margin:0 0 7px;">Reason</p>
                      <p style="color:#b91c1c;font-size:14px;margin:0;line-height:1.6;">{{ $reason }}</p>
                    </td>
                  </tr>
                </table>
              @endif
            @endif

            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:24px;">
              <tr>
                <td style="background:#f8fafc;border:1px solid #e5e7eb;border-radius:14px;padding:20px 22px;">
                  <p style="color:#1e40af;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;margin:0 0 7px;">Product Details</p>
                  <p style="color:#111827;font-size:17px;font-weight:700;margin:0 0 4px;line-height:1.4;">{{ $product->name }}</p>
                  @if($product->description)
                    <p style="color:#475569;font-size:13.5px;margin:0 0 2px;line-height:1.5;">{{ $product->description }}</p>
                  @endif
                  <p style="color:#475569;font-size:13.5px;margin:0;">
                    Price: &#8377;{{ number_format($product->price, 2) }} &middot; Qty: {{ $product->quantity }}
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
