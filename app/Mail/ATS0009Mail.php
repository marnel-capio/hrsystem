<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ATS0009Mail extends Mailable
{
    use Queueable, SerializesModels;

    public $application;
    public $failedStage;

    public function __construct($application, $failedStage)
    {
        $this->application = clone $application;
        $this->application->applicant = (object) [
            'first_name' => $application->intermediateApplicant->first_name ?? '',
            'last_name'  => $application->intermediateApplicant->last_name ?? '',
        ];

        $this->failedStage = $failedStage;
    }

    public function build()
    {
        return $this->subject('【HR System】Intermediate Application Result')
                    ->view('emails.ATS-0009')
                    ->with([
                        'application' => $this->application,
                        'failedStage' => $this->failedStage,
                    ]);
    }
}