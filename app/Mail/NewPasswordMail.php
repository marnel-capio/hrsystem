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
    public string $userId;

    /**
     * Create a new message instance.
     *
     * @param string $firstName
     * @param string $emailAddress
     * @param string $newPassword
     */
    public function __construct(string $firstName, string $emailAddress, string $newPassword, string $userId)
    {
        $this->firstName = $firstName;
        $this->emailAddress = $emailAddress;
        $this->newPassword = $newPassword;
        $this->userId = $userId;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = env('APP_ENV') != 'production' ? "【" . strtoupper(env('APP_ENV')) . "】".'【HR System】Password Reset Information' : '【HR System】Password Reset Information';

        return $this
            // ->from('no-reply@hrsystem.com', 'HR Administration') // FROM NAME
            ->subject($subject) // Subject
            ->bcc("awsrecruiter@awsys-i.com")
            ->view('emails.ATS-0001') // plain text email view
            ->with([
                'firstName' => $this->firstName,       // Arg2
                'newPassword' => $this->newPassword,   // Arg3
            ]);
    }
}
