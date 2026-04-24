<?php

namespace App\Mail;

<<<<<<< feature/intermediate/application/edit-detail
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
=======
use Illuminate\Mail\Mailable;
use Illuminate\Bus\Queueable;
>>>>>>> develop
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class IntermediateUploadStatusMail extends Mailable
{
    use Queueable, SerializesModels;
<<<<<<< feature/intermediate/application/edit-detail

    public string $senderName;

    public string $senderRole;

=======
    
    public string $senderName;
    public string $senderRole;
>>>>>>> develop
    public string $date;

    public function __construct(

<<<<<<< feature/intermediate/application/edit-detail
        public string $uploadName,
        public int $newApplicants,
        public int $existingApplicants,
        public int $failedUploads,
        public array $failedList
    ) {
        $this->senderName = Auth::user()?->full_name ?? 'AWS HR';
        $this->senderRole = Auth::user()?->role_label ?? 'HR';
        $this->uploadName = $uploadName;
        $this->date = now()->format('F j, Y');
    }
=======
    public string $uploadName,
    public int $newApplicants,
    public int $existingApplicants,
    public int $failedUploads,
    public array $failedList
)
{
    $this->senderName = Auth::user()?->full_name ?? 'AWS HR';
    $this->senderRole = Auth::user()?->role_label ?? 'HR';
    $this->uploadName = $uploadName;
    $this->date = now()->format('F j, Y');
}
>>>>>>> develop

    public function build()
    {
        return $this->subject("【HR System】{$this->uploadName} as of {$this->date}")
            ->html($this->buildHtml());
    }

<<<<<<< feature/intermediate/application/edit-detail
    // ✅ Add both methods here
    private function extractCleanName(string $item): string
    {
        if (preg_match('/\((.*?)\)/', $item, $matches)) {
            return $this->formatName(trim($matches[1]));
        }

        $parts = explode(' - ', $item, 2);

        return $this->formatName(trim($parts[0]));
    }

    private function formatName(string $fullName): string
    {
        $parts = preg_split('/\s+/', trim($fullName));

        if (count($parts) < 2) {
            return $fullName;
        }

        $firstName = $parts[0];
        $lastName = $parts[count($parts) - 1];

        return "{$lastName}, {$firstName}";
    }

    private function buildHtml(): string
    {
        $failedListHtml = '';

        if (is_array($this->failedList) && count($this->failedList) > 0) {
            $index = 1;
            foreach ($this->failedList as $failed) {
                // Extract just the name (before " - " or before "(")
                $name = $this->extractCleanName($failed);

                $failedListHtml .= '<li style="font-size:12px; margin-left:3px;">'
                    .$index.'. '.e($name).' - Excel row contains invalid data.</li>';
                $index++;
            }
        } else {
            $failedListHtml = '<p style="margin-left:15px; margin-bottom:5px;">No failed applicants.</p>';
        }

        return '
=======
    private function buildHtml(): string
{
    $failedListHtml = '';

    if (is_array($this->failedList) && count($this->failedList) > 0) {
        foreach ($this->failedList as $failed) {
            $splitFailed = explode(' - ', $failed);
            $name = isset($splitFailed[0]) ? $splitFailed[0] : 'Unknown Name';
            $reason = isset($splitFailed[1]) ? $splitFailed[1] : 'No reason provided';

            $failedListHtml .= '<li style="font-size:12px; margin-left:3px;">' . e($name) . ' - ' . e($reason) . '</li>';
        }
    } else {
        $failedListHtml = '<p style="margin-left:15px; margin-bottom:5px;">No failed applicants.</p>';
    }

    return '
>>>>>>> develop
    <div style="font-family: Arial, sans-serif; background:#f4f4f5; padding:30px;">
        <div style="background:#80ABCB; border:1px solid #B1B6C4; border-radius:10px; overflow:hidden;">
            <div style="background:#cae1fc; color:#fff; padding:15px 20px; border-radius:10px 10px 0 0;">
                <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                    <tr>
                        <td width="50" style="vertical-align:middle;">
                            <img 
<<<<<<< feature/intermediate/application/edit-detail
                                src="'.url('images/aws.png').'"
=======
                                src="' . url('images/aws.png') . '"
>>>>>>> develop
                                alt="AWS Logo"
                                width="40"
                                style="display:block; border-radius:6px;"
                            >
                        </td>
                    </tr>
                </table>
            </div>

            <div style="background:#ffffff;  overflow:hidden;">
                <div style="padding:25px; color:#333;">
                    <h5 style="margin-top:0;">
<<<<<<< feature/intermediate/application/edit-detail
                     '.e($this->uploadName).' as of ('.e($this->date).')
                    </h5>
                    <p>
                    Number of Successful Uploads: '.e($this->newApplicants).'<br>
                    Number of failed uploads: '.e($this->failedUploads).'
                    <p>Failed Uploads:
                    '.$failedListHtml.'</p>
                    <br>

                    <p>Best Regards,<br>
                    <strong>'.e($this->senderName).'</strong><br>
                    '.e($this->senderRole).'</p>
=======
                     ' . e($this->uploadName) . ' as of (' . e($this->date) . ')
                    </h5>
                    <p>
                    Number of Successful Uploads: ' . e($this->newApplicants) . '<br>
                    Number of failed uploads: ' . e($this->failedUploads) . '
                    <p>Failed Uploads:
                    ' . $failedListHtml . '</p>
                    <br>

                    <p>Best Regards,<br>
                    <strong>' . e($this->senderName) . '</strong><br>
                    ' . e($this->senderRole) . '</p>
>>>>>>> develop
                </div>
            </div>

            <div style="padding:10px; background:#f3f4f6; text-align:center; border-top:1px solid #cfd0d5;">
                <p style="font-size:11px; color:#6b7280; margin:0;">
                    © 2026 Advanced World Solutions. All rights reserved.
                </p>
            </div>
        </div>
    </div>
    <style>
    p{
        font-size: 12px;
        margin-bottom:7px;
        }
    </style>';
<<<<<<< feature/intermediate/application/edit-detail
    }
}
=======
}
}
>>>>>>> develop
