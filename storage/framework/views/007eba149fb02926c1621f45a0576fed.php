<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="color-scheme" content="light">
<title>Donation Receipt</title>
</head>
<body style="margin:0;padding:0;background:#eef1f8;font-family:Arial,Helvetica,sans-serif;-webkit-text-size-adjust:100%;">

<!-- Preheader: controls inbox preview snippet, hidden in the email body -->
<span style="display:none;font-size:1px;color:#eef1f8;line-height:1px;max-height:0;max-width:0;opacity:0;overflow:hidden;">
    Your ₹<?php echo e(number_format($amount, 0)); ?> donation to <?php echo e($campaign->title ?? 'this campaign'); ?> is confirmed — thank you, <?php echo e($donorName); ?>!
</span>

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#eef1f8;">
<tr><td align="center" style="padding:32px 16px;">

<table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;">

  <!-- Brand mark above the card -->
  <!-- <tr>
    <td align="center" style="padding-bottom:18px;">
      <span style="font-size:15px;font-weight:700;color:#4f46e5;letter-spacing:.03em;"><?php echo e(config('app.name')); ?></span>
    </td>
  </tr> -->

  <!-- Main card -->
  <tr>
    <td style="background:#ffffff;border-radius:18px;overflow:hidden;border:1px solid #e9ecf5;">
      <table role="presentation" width="100%" cellpadding="0" cellspacing="0">

        <!-- Header -->
        <tr>
          <td bgcolor="#4f46e5" style="background-image:linear-gradient(135deg,#4f46e5,#7c3aed);background-color:#4f46e5;padding:38px 30px 34px;text-align:center;">
            <table role="presentation" align="center" cellpadding="0" cellspacing="0" style="margin:0 auto 16px;">
              <tr><td style="width:54px;height:54px;border-radius:50%;background:rgba(255,255,255,.16);text-align:center;vertical-align:middle;font-size:26px;line-height:54px;color:#ffffff;">✓</td></tr>
            </table>
            <p style="color:#ffffff;font-size:21px;font-weight:700;margin:0 0 4px;">Donation Confirmed</p>
            <p style="color:#ddd6fe;font-size:13px;margin:0;">Official Receipt &middot; <?php echo e($receiptNo); ?></p>
          </td>
        </tr>

        <!-- Body -->
        <tr>
          <td style="padding:34px 30px 8px;">

            <p style="display:inline-block;background:#ecfdf5;color:#047857;font-size:12px;font-weight:700;letter-spacing:.02em;padding:6px 14px;border-radius:20px;margin:0 0 20px;">
              ● PAYMENT SUCCESSFUL
            </p>

            <h1 style="font-size:22px;color:#111827;margin:0 0 10px;font-weight:700;">
              Thank you, <?php echo e($donorName); ?> <span style="font-weight:400;">❤️</span>
            </h1>
            <p style="font-size:14.5px;color:#6b7280;line-height:1.7;margin:0 0 28px;">
              Your generosity is already at work. Because of supporters like you, this campaign can keep moving toward its goal. Here's your receipt for the records.
            </p>

            <!-- Amount card -->
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:24px;">
              <tr>
                <td bgcolor="#4f46e5" style="background-image:linear-gradient(135deg,#4f46e5,#7c3aed);background-color:#4f46e5;border-radius:14px;padding:26px 24px;text-align:center;">
                  <p style="color:#e0e7ff;font-size:12.5px;letter-spacing:.03em;margin:0 0 8px;">DONATION AMOUNT</p>
                  <p style="color:#ffffff;font-size:38px;font-weight:700;margin:0;line-height:1.1;">₹<?php echo e(number_format($amount, 2)); ?></p>
                </td>
              </tr>
            </table>

            <!-- Campaign card -->
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:24px;">
              <tr>
                <td style="background:#eff6ff;border:1px solid #dbeafe;border-radius:14px;padding:20px 22px;">
                  <p style="color:#1e40af;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;margin:0 0 7px;">Supporting</p>
                  <p style="color:#111827;font-size:17px;font-weight:700;margin:0 0 8px;line-height:1.4;"><?php echo e($campaign->title ?? 'Campaign'); ?></p>
                  <p style="color:#475569;font-size:13.5px;line-height:1.6;margin:0;">
                    Your contribution goes directly toward this campaign's fundraising goal.
                  </p>
                </td>
              </tr>
            </table>

            
            <?php if(isset($campaign->slug)): ?>
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:30px;">
              <tr>
                <td align="center">
                  <a href="<?php echo e(rtrim(config('app.url'), '/')); ?>/campaigns/<?php echo e($campaign->slug); ?>"
                     style="display:inline-block;background:#4f46e5;color:#ffffff;font-size:14px;font-weight:600;text-decoration:none;padding:13px 32px;border-radius:9px;">
                    View Campaign Progress&nbsp;→
                  </a>
                </td>
              </tr>
            </table>
            <?php endif; ?>

            <!-- Receipt details -->
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e5e7eb;border-radius:14px;margin-bottom:24px;">
              <tr>
                <td style="padding:20px 24px 6px;">
                  <p style="font-size:15px;font-weight:700;color:#111827;margin:0;">Receipt Details</p>
                </td>
              </tr>

              <?php
                $rows = [
                    ['Receipt Number', $receiptNo],
                    ['Date & Time', \Carbon\Carbon::parse($paidAt)->format('d M Y, h:i A')],
                    ['Payment Method', 'Razorpay'],
                    ['Donation Amount', '₹' . number_format($amount, 2)],
                    ['Platform Fee (5%)', '₹' . number_format($platformFee, 2)],
                ];
              ?>

              <?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <tr>
                <td style="padding:0 24px;border-top:1px solid #f1f5f9;">
                  <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                      <td style="padding:12px 0;font-size:13.5px;color:#6b7280;"><?php echo e($row[0]); ?></td>
                      <td style="padding:12px 0;font-size:13.5px;color:#4f46e5;font-weight:600;text-align:right;"><?php echo e($row[1]); ?></td>
                    </tr>
                  </table>
                </td>
              </tr>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

              <tr>
                <td style="padding:0 24px 20px;border-top:1px solid #f1f5f9;">
                  <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                      <td style="padding:14px 0 0;font-size:14.5px;color:#111827;font-weight:700;">Amount to Campaign</td>
                      <td style="padding:14px 0 0;font-size:14.5px;color:#4f46e5;font-weight:700;text-align:right;">₹<?php echo e(number_format($netAmount, 2)); ?></td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>

            <!-- Note -->
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:6px;">
              <tr>
                <td style="background:#f8fafc;border-left:4px solid #4f46e5;border-radius:8px;padding:16px 18px;">
                  <p style="color:#64748b;font-size:12.5px;line-height:1.7;margin:0;">
                    Please keep this email for your records — it serves as your official donation receipt.
                    A platform fee has been deducted to help maintain and operate the platform, while the
                    remaining amount is transferred toward the campaign.
                  </p>
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
      <p style="color:#6b7280;font-size:12px;margin:0 0 10px;line-height:1.7;">
        © <?php echo e(date('Y')); ?> <?php echo e(config('app.name')); ?>. All rights reserved.
      </p>
      <p style="color:#6b7280;font-size:12px;margin:0 0 4px;">
        <a href="<?php echo e(config('app.url')); ?>" style="color:#4f46e5;text-decoration:none;">www.donatebazaar.com</a>
        &nbsp;&middot;&nbsp;
        <a href="mailto:support@donatebazaar.com" style="color:#4f46e5;text-decoration:none;">support@donatebazaar.com</a>
      </p>
    </td>
  </tr>

</table>

</td></tr>
</table>

</body>
</html><?php /**PATH C:\xampp\htdocs\fundraise\resources\views/emails/donation-receipt.blade.php ENDPATH**/ ?>