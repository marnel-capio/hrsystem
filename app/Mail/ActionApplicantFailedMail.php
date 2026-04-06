<?php

namespace App\Mail;

use App\Models\ActionApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ActionApplicantFailedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $application;
    public $failedStage;
    public $link;

    public function __construct($application, $failedStage, $link)
    {
        $this->application = $application;
        $this->failedStage = $failedStage;
        $this->link = $link;
    }

    public function build()
    {
        return $this->subject('【HR System】Application Update')
            ->view('emails.action-applicant-failed')
            ->with([
                'application' => $this->application,
                'failedStage' => $this->failedStage,
                'link' => $this->link,
                'subject' => 'Application Update',
            ]);
    }
}
