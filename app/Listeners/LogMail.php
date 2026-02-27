<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Support\Facades\Log;
use App\Models\EmailHistory;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Mime\Address;

class LogMail
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(MessageSent $event): void
    {
        $fromEmail = '';
        $fromName = '';
        $mailBody =  (array) $event->message->getBody();
        $ctr=0;
        $body = "";
        $userId =  $event->data['userId'];
        foreach ($mailBody as $key => $val) {
            if ($ctr == 1) {
                $body = $val;
            } else if ($ctr > 1) {
                break;
            }
            $ctr++;
        }
        foreach ($event->message->getFrom() as $address) {
            $fromEmail = $address->getAddress(); // "from@example.com"
            $fromName = $address->getName();     // "Sender Name"
        }
        foreach ($event->message->getTo() as $address) {
            $toEmail = $address->getAddress(); // "from@example.com"
            $toName = $address->getName();     // "Sender Name"
            EmailHistory::create([
                'status' => 1,
                'subject' => $event->message->getSubject(),
                'from' => $fromName,
                'email_from' => $fromEmail,
                'email_to' => $toEmail,
                'email_body' => $body,
                'created_by' => $userId,
                'updated_by' => $userId,
                'create_time' => now(),
                'update_time' => now(),
            ]);
        }
        
    }
}
