<?php

namespace App\Mail;

use App\Models\OrganizationApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrganizationApplicationSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public $application;

    public function __construct(OrganizationApplication $application)
    {
        $this->application = $application;
    }

    public function build()
    {
        return $this->subject('NGO Application Received — '.$this->application->name)
            ->view('emails.org_application_submitted')
            ->with([
                'applicant' => $this->application->user?->name ?? $this->application->contact_name,
                'organization' => $this->application->name,
                'type' => $this->application->organization_type,
                'submittedAt' => $this->application->submitted_at,
            ]);
    }
}
