<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Queueable;
use App\Models\Partnership;

class PartnershipStatusUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public $partnership;

    public function __construct(Partnership $partnership)
    {
        $this->partnership = $partnership;
    }

    public function build()
    {
        return $this->subject($this->getSubject())
            ->view('emails.partnership_status')
            ->with([
                'name' => $this->partnership->name,
                'organization' => $this->partnership->organization_name,
                'status' => $this->partnership->status,
                'notes' => $this->partnership->admin_notes,
            ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Dynamic Subject (IMPORTANT)
    |--------------------------------------------------------------------------
    */
    protected function getSubject()
    {
        if ($this->partnership->status === 'approved') {
            return " Partnership Approved - {$this->partnership->organization_name}";
        }

        if ($this->partnership->status === 'rejected') {
            return "Update on Your Partnership Request";
        }

        return "Partnership Status Updated";
    }
}