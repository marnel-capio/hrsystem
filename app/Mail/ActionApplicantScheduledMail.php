<?php

namespace App\Mail;

use App\Models\ActionApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ActionApplicantScheduledMail extends Mailable
{
    use Queueable, SerializesModels;

    public $application;
    public $approvedInterviews;
    public $link;

    public function __construct($application, $approvedInterviews, $link)
    {
        $this->application = $application;
        $this->approvedInterviews = $approvedInterviews;
        $this->link = $link;
    }

    public function build()
    {
        return $this->subject('【HR System】AWS ACTION Application Schedule')
            ->view('emails.ATS-0005')
            ->with([
                'application' => $this->application,
                'approvedInterviews' => $this->approvedInterviews,
                'link' => $this->link,

            ]);
    }
}
