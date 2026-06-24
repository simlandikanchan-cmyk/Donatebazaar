<?php
namespace App\Mail;

use App\Models\JobPostApplication;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class JobPostApplicationReceivedMail extends Mailable
{
    use SerializesModels;

    public function __construct(public JobPostApplication $application) {}

    public function build()
    {
        return $this->subject('New Application: ' . $this->application->jobPost->title)
                    ->view('emails.job_post_application_received');
    }
}