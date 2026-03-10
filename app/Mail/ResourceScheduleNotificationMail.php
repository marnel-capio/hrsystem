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

    public function __construct(int $userId, string $recipientName, string $batchName, string $link)
    {
        $this->userId = $userId;
        $this->recipientName = $recipientName;
        $this->batchName = $batchName;
        $this->link = $link;
    }

    public function build()
    {
        return $this->subject("【HR System】New Resource Schedule Created")
                    ->view('emails.ats-0002')
                    ->with([
                        'name' => $this->recipientName,
                        'batchName' => $this->batchName,
                        'link' => $this->link,
                        'userId' => $this->userId,
                    ]);
    }
}