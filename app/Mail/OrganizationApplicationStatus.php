<?php

namespace App\Mail;

use App\Models\OrganizationApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrganizationApplicationStatus extends Mailable
{
    use Queueable, SerializesModels;

    public $application;

    public function __construct(OrganizationApplication $application)
    {
        $this->application = $application;
    }

    public function build()
    {
        $isApproved = $this->application->status === 'approved';

        return $this->subject(
            $isApproved
                ? 'NGO Application Approved — '.$this->application->name
                : 'Update on Your NGO Application — '.$this->application->name
        )
            ->view('emails.org_application_status')
            ->with([
            'applicant' => $this->application->user?->name ?? $this->application->contact_name,
            'organization' => $this->application->name,
            'status' => $this->application->status,
            'adminNotes' => $this->application->admin_notes,
            'rejectionReason' => $this->application->rejection_reason,
            'isApproved' => $isApproved,
        ]);
    }
}
