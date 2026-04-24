<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class ATS0007Mail extends Mailable
{
    use Queueable, SerializesModels;

    public $application;
    public $link;
    public $senderName;
    public $senderRole;

    public function __construct($application, $link)
    {
        $this->application = clone $application;
        $this->application->applicant = (object) [
            'first_name' => $application->intermediateApplicant->first_name ?? '',
            'last_name'  => $application->intermediateApplicant->last_name ?? '',
        ];

        $this->link        = $link;
        $this->senderName  = Auth::user()?->full_name ?? 'AWS HR Team';
        $this->senderRole  = Auth::user()?->role_label ?? '';
    }

    public function build()
    {
        return $this->subject('【HR System】Scheduled Job Offer')
                    ->view('emails.ATS-0007')
                    ->with([
                        'application' => $this->application,
                        'link'        => $this->link,
                        'senderName'  => $this->senderName,
                        'senderRole'  => $this->senderRole,
                    ]);
    }
}