<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Registration Confirmed</title>
    <style>
        body        { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
        .wrapper    { max-width: 600px; margin: 40px auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,.08); }
        .header     { background: #16a34a; padding: 32px 40px; text-align: center; }
        .header h1  { color: #ffffff; margin: 0; font-size: 22px; letter-spacing: .5px; }
        .body       { padding: 32px 40px; }
        .body p     { color: #374151; line-height: 1.7; margin: 0 0 16px; }
        .card       { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 6px; padding: 20px 24px; margin: 24px 0; }
        .card table { width: 100%; border-collapse: collapse; }
        .card td    { padding: 6px 0; color: #374151; font-size: 14px; vertical-align: top; }
        .card td:first-child { font-weight: 600; width: 140px; color: #111827; }
        .badge      { display: inline-block; background: #dcfce7; color: #15803d; padding: 4px 12px; border-radius: 99px; font-size: 13px; font-weight: 600; }
        .footer     { background: #f9fafb; border-top: 1px solid #e5e7eb; padding: 20px 40px; text-align: center; }
        .footer p   { color: #9ca3af; font-size: 12px; margin: 0; }
    </style>
</head>
<body>
<div class="wrapper">

    {{-- Header --}}
    <div class="header">
        <h1>✅ You're Registered!</h1>
    </div>

    {{-- Body --}}
    <div class="body">
        <p>Hi <strong>{{ $registration->name }}</strong>,</p>

        <p>
            Thank you for registering for <strong>{{ $event->title }}</strong>.
            Your spot has been confirmed. Here's a summary of your registration:
        </p>

        {{-- Registration Details --}}
        <div class="card">
            <table>
                <tr>
                    <td>Event</td>
                    <td>{{ $event->title }}</td>
                </tr>
                <tr>
                    <td>Date</td>
                    <td>{{ \Carbon\Carbon::parse($event->event_date)->format('l, F j, Y') }}</td>
                </tr>
                @if($event->start_time)
                <tr>
                    <td>Time</td>
                    <td>
                        {{ \Carbon\Carbon::parse($event->start_time)->format('g:i A') }}
                        @if($event->end_time)
                            – {{ \Carbon\Carbon::parse($event->end_time)->format('g:i A') }}
                        @endif
                    </td>
                </tr>
                @endif
                <tr>
                    <td>Name</td>
                    <td>{{ $registration->name }}</td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td>{{ $registration->email }}</td>
                </tr>
                <tr>
                    <td>Phone</td>
                    <td>{{ $registration->phone }}</td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td><span class="badge">Registered</span></td>
                </tr>
            </table>
        </div>

        @if($registration->message)
        <p><strong>Your note:</strong><br>{{ $registration->message }}</p>
        @endif

        <p>
            We look forward to seeing you there! If you have any questions,
            please don't hesitate to reach out to the event organiser.
        </p>

        <p>Warm regards,<br><strong>{{ config('app.name') }}</strong></p>
    </div>

    {{-- Footer --}}
    <div class="footer">
        <p>
            This email was sent because you registered for an event on
            <a href="{{ config('app.url') }}" style="color:#6b7280;">{{ config('app.name') }}</a>.
        </p>
    </div>

</div>
</body>
</html>