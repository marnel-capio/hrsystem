<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResourceScheduleNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $recipientName;
    public string $batchName;
    public string $link;

    public string|int $userId;

    public function __construct(string $batchName, string $link)
    {
        $this->batchName = $batchName;
        $this->link = $link;
    }

    public function build()
    {
        return $this->subject("【HR System】New Resource Schedule Created")
                    ->view('emails.ats-0002')
                    ->with([
                        'batchName' => $this->batchName,
                        'link' => $this->link,
                    ]);
    }
}