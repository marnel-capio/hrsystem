<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class ResourceScheduleNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $recipientName;
    public string $batchName;
    public string $link;

    public string|int $userId;

public $senderName;
public $senderRole;

    public function __construct(string $batchName, string $link)
    {
        $this->batchName = $batchName;
        $this->link = $link;
            $this->senderName = Auth::user()?->full_name ?? 'AWS HR';
    $this->senderRole = Auth::user()?->role_label ?? 'HR';
    }

    public function build()
    {
        return $this->subject("【HR System】New Resource Schedule Created")
        ->bcc("awsrecruiter@awsys-i.com")
                    ->view('emails.ATS-0002')
                    ->with([
                        'batchName' => $this->batchName,
                        'link' => $this->link,
                         'senderName' => $this->senderName,
            'senderRole' => $this->senderRole,
                    ]);
    }
}
