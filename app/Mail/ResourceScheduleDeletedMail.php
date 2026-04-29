<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class ResourceScheduleDeletedMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $batchName;
    public string $targetLocation;
    public string $deploymentDate;
    public $senderName;
public $senderRole;

    public function __construct(string $batchName, string $targetLocation, string $deploymentDate)
    {
        $this->batchName = $batchName;
        $this->targetLocation = $targetLocation;
        $this->deploymentDate = $deploymentDate;
        $this->senderName = Auth::user()?->full_name ?? 'AWS HR';
    $this->senderRole = Auth::user()?->role_label ?? 'HR';
    }

    public function build()
    {
        return $this->subject("【HR System】Resource Schedule Deleted - {$this->batchName}")
        ->bcc("awsrecruiter@awsys-i.com")
            ->view('emails.ATS-0003')->with([
            'senderName' => $this->senderName,
            'senderRole' => $this->senderRole, ]);
    }
}
