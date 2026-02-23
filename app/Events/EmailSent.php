<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EmailSent
{
    use Dispatchable;

    public int $status;
    public string $subject;
    public string $fromName;
    public string $emailFrom;
    public string $emailTo;
    public string $emailBody;
    public ?int $userId;

    public function __construct(
        int $status,
        string $subject,
        string $fromName,
        string $emailFrom,
        string $emailTo,
        string $emailBody,
        ?int $userId = null
    ) {
        $this->status = $status;
        $this->subject = $subject;
        $this->fromName = $fromName;
        $this->emailFrom = $emailFrom;
        $this->emailTo = $emailTo;
        $this->emailBody = $emailBody;
        $this->userId = $userId;
    }
}
