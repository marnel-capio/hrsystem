<?php

namespace App\Listeners;

use App\Events\EmailSent;
use App\Models\EmailHistory;

class LogMail
{
    /**
     * Handle the event.
     */
    public function handle(EmailSent $event)
    {
        // Generate a hash of the email content to detect duplicates
        $hash = md5($event->emailBody);

        // Check if a record already exists with same email, subject, and content
        $exists = EmailHistory::where('email_to', $event->emailTo)
            ->where('subject', $event->subject)
            ->whereRaw('MD5(email_body) = ?', [$hash])
            ->exists();

        if ($exists) {
            return; // Skip duplicate
        }

        EmailHistory::logEmail(
            $event->status,
            $event->subject,
            $event->fromName,
            $event->emailFrom,
            $event->emailTo,
            $event->emailBody,
            $event->userId
        );
    }
}