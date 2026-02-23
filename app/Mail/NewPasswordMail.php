<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $firstName;
    public string $emailAddress;
    public string $newPassword;

    /**
     * Create a new message instance.
     *
     * @param string $firstName
     * @param string $emailAddress
     * @param string $newPassword
     */
    public function __construct(string $firstName, string $emailAddress, string $newPassword)
    {
        $this->firstName = $firstName;
        $this->emailAddress = $emailAddress;
        $this->newPassword = $newPassword;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->from('no-reply@hrsystem.com', 'HR Administration') // FROM NAME
            ->to($this->emailAddress) // Arg1: recipient email
            ->subject('【HR System】Password Reset Information') // Subject
            ->view('emails.ATS-0001') // plain text email view
            ->with([
                'firstName' => $this->firstName,       // Arg2
                'newPassword' => $this->newPassword,   // Arg3
            ]);
    }
}
