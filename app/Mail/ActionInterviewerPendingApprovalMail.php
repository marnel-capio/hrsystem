<?php

namespace App\Mail;

use App\Models\ActionApplication;
use App\Models\ActionApplicationInterview;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ActionInterviewerPendingApprovalMail extends Mailable
{
    use Queueable, SerializesModels;

    public $application;
    public $interview;
    public $link;

    public function __construct($application, $interview, $link)
    {
        $this->application = $application;
        $this->interview = $interview;
        $this->link = $link;
    }

    public function build()
    {
        return $this->subject('【HR System】Exam/Interview Schedule Pending Approval')
            ->view('emails.action-interviewer-pending-approval')
            ->with([
                'application' => $this->application,
                'interview' => $this->interview,
                'link' => $this->link,

            ]);
    }
}
