<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class ActionJobOfferScheduledMail extends Mailable
{
    use Queueable, SerializesModels;

    public $application;
    public $link;
    public $senderName;
public $senderRole;

    public function __construct($application, $link)
    {
        $this->application = $application;
        $this->link = $link;
            $this->senderName = Auth::user()?->full_name ?? 'AWS HR Team';
    $this->senderRole = Auth::user()?->role_label ?? '';
    }

    public function build()
    {
        return $this->subject('【HR System】Scheduled Job Offer')
            ->view('emails.action-job-offer-scheduled')
            ->with([
                'application' => $this->application,
                'link' => $this->link,
                 'senderName' => $this->senderName,
            'senderRole' => $this->senderRole,
            ]);
    }
}
