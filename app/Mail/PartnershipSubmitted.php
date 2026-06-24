<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Queueable;
use App\Models\Partnership;

class PartnershipSubmitted extends Mailable
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
            ->view('emails.partnership_submitted')
            ->with([
                'name' => $this->partnership->name,
                'email' => $this->partnership->email,
                'organization' => $this->partnership->organization_name,
                'type' => $this->partnership->partnership_type,
                'score' => $this->partnership->priority_score ?? 0,
                'message' => $this->partnership->message,
                'website' => $this->partnership->website,
                'hasDocument' => !empty($this->partnership->document),
            ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Dynamic Subject (SMART)
    |--------------------------------------------------------------------------
    */
    protected function getSubject()
    {
        $score = $this->partnership->priority_score ?? 0;

        if ($score >= 30) {
            return " High Priority Partnership Request - {$this->partnership->organization_name}";
        }

        return "New Partnership Request - {$this->partnership->organization_name}";
    }
}