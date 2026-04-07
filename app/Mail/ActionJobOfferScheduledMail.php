<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ActionJobOfferScheduledMail extends Mailable
{
    use Queueable, SerializesModels;

    public $application;
    public $link;

    public function __construct($application, $link)
    {
        $this->application = $application;
        $this->link = $link;
    }

    public function build()
    {
        return $this->subject('【HR System】Scheduled Job Offer')
            ->view('emails.action-job-offer-scheduled')
            ->with([
                'application' => $this->application,
                'link' => $this->link,
            ]);
    }
}
