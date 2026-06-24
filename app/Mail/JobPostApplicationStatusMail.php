<?php
namespace App\Mail;

use App\Models\JobPostApplication;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class JobPostApplicationStatusMail extends Mailable

{
    use SerializesModels;

    public function __construct(public JobPostApplication $application) {}

    public function build()
    {
        return $this->subject('Your Application Update – DonateBazaar')
                    ->view('emails.job_post_application_status');
    }
}