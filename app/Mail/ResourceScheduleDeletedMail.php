<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResourceScheduleDeletedMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $batchName;
    public string $targetLocation;
    public string $deploymentDate;

    public function __construct(string $batchName, string $targetLocation, string $deploymentDate)
    {
        $this->batchName = $batchName;
        $this->targetLocation = $targetLocation;
        $this->deploymentDate = $deploymentDate;
    }

    public function build()
    {
        return $this->subject("【HR System】Resource Schedule Deleted - {$this->batchName}")
            ->view('emails.ATS-0003');
    }
}