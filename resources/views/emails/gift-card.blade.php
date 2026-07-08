<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="color-scheme" content="light">
<title>Gift Card</title>
</head>
<body style="margin:0;padding:0;background:#eef1f8;font-family:Arial,Helvetica,sans-serif;-webkit-text-size-adjust:100%;">

<span style="display:none;font-size:1px;color:#eef1f8;line-height:1px;max-height:0;max-width:0;opacity:0;overflow:hidden;">
    {{ $giftCard->sender_name }} sent you a &#8377;{{ number_format($giftCard->amount, 0) }} gift card on DonateBazaar!
</span>

@php
    $themes = [
        'purple' => ['bg'=>'#EEEDFE','text'=>'#26215C','brand'=>'#3C3489'],
        'teal'   => ['bg'=>'#E1F5EE','text'=>'#04342C','brand'=>'#085041'],
        'coral'  => ['bg'=>'#FAECE7','text'=>'#4A1B0C','brand'=>'#712B13'],
        'blue'   => ['bg'=>'#E6F1FB','text'=>'#042C53','brand'=>'#0C447C'],
    ];
    $t = $themes[$giftCard->theme] ?? $themes['purple'];
@endphp

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#eef1f8;">
<tr><td align="center" style="padding:32px 16px;">

<table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;">

  <!-- Main card -->
  <tr>
    <td style="background:#ffffff;border-radius:18px;overflow:hidden;border:1px solid #e9ecf5;">
      <table role="presentation" width="100%" cellpadding="0" cellspacing="0">

        <!-- Card visual -->
        <tr>
          <td bgcolor="{{ $t['bg'] }}" style="background-color:{{ $t['bg'] }};padding:34px 30px;">
            <p style="font-size:11px;font-weight:700;letter-spacing:.08em;color:{{ $t['brand'] }};margin:0 0 10px;">DONATEBAZAAR</p>
            <p style="font-size:36px;font-weight:700;color:{{ $t['text'] }};margin:0 0 4px;line-height:1.1;">&#8377;{{ number_format($giftCard->amount, 0) }}</p>
            <p style="font-size:13px;color:{{ $t['text'] }};margin:0 0 16px;">Gift Card for {{ $giftCard->recipient_name }}</p>
            <p style="font-size:13px;font-family:'Courier New',monospace;letter-spacing:.12em;color:{{ $t['text'] }};margin:0;">{{ $giftCard->code }}</p>
          </td>
        </tr>

        <!-- Body -->
        <tr>
          <td style="padding:30px 30px 8px;">

            <h1 style="font-size:20px;color:#111827;margin:0 0 10px;font-weight:700;">
              Hi {{ $giftCard->recipient_name }},
            </h1>
            <p style="font-size:14.5px;color:#6b7280;line-height:1.7;margin:0 0 22px;">
              <strong>{{ $giftCard->sender_name }}</strong> has gifted you &#8377;{{ number_format($giftCard->amount, 0) }} to donate to a cause you love on DonateBazaar.
            </p>

            @if($giftCard->message)
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:22px;">
              <tr>
                <td style="background:#f8fafc;border-left:4px solid #4f46e5;border-radius:8px;padding:16px 18px;">
                  <p style="color:#4b5563;font-size:14px;font-style:italic;line-height:1.65;margin:0;">&ldquo;{{ $giftCard->message }}&rdquo;</p>
                </td>
              </tr>
            </table>
            @endif

            <p style="font-size:13px;color:#9ca3af;margin:0 0 12px;">Use your gift card code to donate to any active campaign:</p>

            <!-- Code box -->
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:26px;">
              <tr>
                <td align="center">
                  <table role="presentation" cellpadding="0" cellspacing="0">
                    <tr>
                      <td style="background:#f0f2fa;border:1px dashed #6366f1;border-radius:10px;padding:12px 24px;">
                        <p style="font-size:20px;font-family:'Courier New',monospace;letter-spacing:.15em;color:#6366f1;font-weight:700;margin:0;">{{ $giftCard->code }}</p>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>

            <!-- CTA -->
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:8px;">
              <tr>
                <td align="center">
                  <a href="{{ url('/gift-cards/redeem') }}"
                     style="display:inline-block;background:#4f46e5;color:#ffffff;font-size:14px;font-weight:600;text-decoration:none;padding:13px 32px;border-radius:9px;">
                    Redeem Gift Card&nbsp;&rarr;
                  </a>
                </td>
              </tr>
            </table>

            <p style="font-size:11px;color:#9ca3af;text-align:center;margin:16px 0 0;">
              This gift card never expires.
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