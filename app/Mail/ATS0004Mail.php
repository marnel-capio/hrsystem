<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class ATS0004Mail extends Mailable
{
    use Queueable, SerializesModels;

    public $application;
    public $interview;
    public $link;
    public $senderName;
    public $senderRole;

    public function __construct($application, $interview, $link, $applicantName)
    {
        // Wrap the real application and add an "applicant" property
        $this->application = clone $application;
        $this->application->applicant = (object) [
            'first_name' => explode(' ', $applicantName)[0] ?? '',
            'last_name'  => explode(' ', $applicantName)[1] ?? '',
        ];

        $this->interview   = $interview;
        $this->link        = $link;
        $this->senderName  = Auth::user()?->full_name ?? 'AWS HR Team';
        $this->senderRole  = Auth::user()?->role_label ?? '';
    }

    public function build()
    {
        return $this->subject('【HR System】Applicant Schedule Pending Approval')
                    ->view('emails.ATS-0004')
                    ->with([
                        'application' => $this->application,
                        'interview'   => $this->interview,
                        'link'        => $this->link,
                        'senderName'  => $this->senderName,
                        'senderRole'  => $this->senderRole,
                    ]);
    }
}