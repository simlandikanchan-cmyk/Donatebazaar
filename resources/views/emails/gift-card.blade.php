<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"></head>
<body style="font-family:sans-serif;background:#f4f5fb;padding:40px 20px;">
<div style="max-width:520px;margin:0 auto;background:#fff;border-radius:16px;overflow:hidden;border:1px solid rgba(0,0,0,0.07);">

    @php
    $themes = [
        'purple' => ['bg'=>'#EEEDFE','text'=>'#26215C','brand'=>'#3C3489'],
        'teal'   => ['bg'=>'#E1F5EE','text'=>'#04342C','brand'=>'#085041'],
        'coral'  => ['bg'=>'#FAECE7','text'=>'#4A1B0C','brand'=>'#712B13'],
        'blue'   => ['bg'=>'#E6F1FB','text'=>'#042C53','brand'=>'#0C447C'],
    ];
    $t = $themes[$giftCard->theme] ?? $themes['purple'];
    @endphp

    {{-- Card visual --}}
    <div style="background:{{ $t['bg'] }};padding:32px 28px;">
        <div style="font-size:11px;font-weight:600;letter-spacing:.08em;color:{{ $t['brand'] }};margin-bottom:8px;">DONATEBAZAAR</div>
        <div style="font-size:36px;font-weight:700;color:{{ $t['text'] }};margin-bottom:4px;">₹{{ number_format($giftCard->amount, 0) }}</div>
        <div style="font-size:13px;color:{{ $t['text'] }};opacity:.7;">Gift Card for {{ $giftCard->recipient_name }}</div>
        <div style="margin-top:16px;font-size:13px;font-family:monospace;letter-spacing:.12em;color:{{ $t['text'] }};opacity:.6;">{{ $giftCard->code }}</div>
    </div>

    {{-- Message --}}
    <div style="padding:28px;">
        <p style="font-size:15px;color:#0f1117;margin-bottom:8px;">Hi {{ $giftCard->recipient_name }},</p>
        <p style="font-size:14px;color:#4b5563;line-height:1.7;">
            <strong>{{ $giftCard->sender_name }}</strong> has gifted you ₹{{ number_format($giftCard->amount, 0) }} to donate to a cause you love on DonateBazaar.
        </p>

        @if($giftCard->message)
        <div style="margin:16px 0;padding:14px 16px;background:#f4f5fb;border-radius:10px;border-left:3px solid #6366f1;font-size:14px;color:#4b5563;font-style:italic;line-height:1.65;">
            "{{ $giftCard->message }}"
        </div>
        @endif

        <p style="font-size:13px;color:#9ca3af;margin:16px 0 20px;">Use your gift card code to donate to any active campaign:</p>

        <div style="text-align:center;margin-bottom:24px;">
            <div style="display:inline-block;background:#f0f2fa;border:1px dashed #6366f1;border-radius:10px;padding:12px 24px;font-size:20px;font-family:monospace;letter-spacing:.15em;color:#6366f1;font-weight:700;">
                {{ $giftCard->code }}
            </div>
        </div>

        <div style="text-align:center;">
            <a href="{{ url('/gift-cards/redeem') }}"
               style="display:inline-block;background:#6366f1;color:#fff;padding:13px 32px;border-radius:10px;font-size:14px;font-weight:600;text-decoration:none;">
                Redeem Gift Card
            </a>
        </div>

        <p style="font-size:11px;color:#9ca3af;text-align:center;margin-top:20px;">
            This gift card never expires · Powered by DonateBazaar
        </p>
    </div>
</div>
</body>
</html>