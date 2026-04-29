<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ATS0008Mail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public $applicantName,
        public $venue,
        public $stage,
        public $scheduledDate
    ) {}

    public function build()
    {
        return $this->subject('【HR System】AWS Intermediate Application Schedule')
        ->bcc("awsrecruiter@awsys-i.com")
                    ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME', 'AWS HR'))
                    ->view('emails.ATS-0008');
    }
}
