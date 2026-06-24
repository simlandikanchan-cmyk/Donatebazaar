<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Job Application</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
        .wrapper { max-width: 600px; margin: 40px auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
        .header { background: #c84b2f; padding: 32px 40px; }
        .header h1 { color: #ffffff; margin: 0; font-size: 22px; font-weight: 700; }
        .header p { color: rgba(255,255,255,0.8); margin: 6px 0 0; font-size: 14px; }
        .body { padding: 32px 40px; }
        .body h2 { font-size: 18px; color: #0f0e0d; margin: 0 0 20px; }
        .detail-table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        .detail-table tr { border-bottom: 1px solid #f0ece7; }
        .detail-table tr:last-child { border-bottom: none; }
        .detail-table td { padding: 10px 0; font-size: 14px; }
        .detail-table td:first-child { color: #7a726a; width: 140px; }
        .detail-table td:last-child { color: #0f0e0d; font-weight: 500; }
        .cover-box { background: #f5f2ee; border-left: 3px solid #c84b2f; padding: 16px 20px; border-radius: 4px; margin-bottom: 24px; }
        .cover-box p { margin: 0; font-size: 14px; color: #3a3530; line-height: 1.7; }
        .btn { display: inline-block; padding: 12px 28px; background: #c84b2f; color: #ffffff; text-decoration: none; border-radius: 5px; font-size: 14px; font-weight: 600; }
        .footer { background: #f5f2ee; padding: 20px 40px; text-align: center; font-size: 12px; color: #7a726a; }
    </style>
</head>
<body>
<div class="wrapper">

    <div class="header">
        <h1>New Job Application Received</h1>
        <p>Someone applied for a position at DonateBazaar</p>
    </div>

    <div class="body">
        <h2>Application Details</h2>

        <table class="detail-table">
            <tr>
                <td>Position</td>
                <td>{{ $application->jobPost->title ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td>Applicant Name</td>
                <td>{{ $application->name }}</td>
            </tr>
            <tr>
                <td>Email</td>
                <td>{{ $application->email }}</td>
            </tr>
            <tr>
                <td>Phone</td>
                <td>{{ $application->phone ?? '—' }}</td>
            </tr>
            <tr>
                <td>Applied On</td>
                <td>{{ $application->created_at->format('d M Y, h:i A') }}</td>
            </tr>
        </table>

        @if($application->cover_letter)
        <p style="font-size:13px; color:#7a726a; margin-bottom:8px; font-weight:600; text-transform:uppercase; letter-spacing:0.05em;">Cover Letter</p>
        <div class="cover-box">
            <p>{{ $application->cover_letter }}</p>
        </div>
        @endif

        <a href="{{ url('/admin/job_post_applications') }}" class="btn">
            View in Admin Panel →
        </a>
    </div>

    <div class="footer">
        © {{ date('Y') }} DonateBazaar. This is an automated notification.
    </div>

</div>
</body>
</html>