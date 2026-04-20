<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class UploadStatusReportMail extends Mailable
{
    use Queueable, SerializesModels;
    
    public string $senderName;
    public string $senderRole;
    public string $batchName;
    public string $date;
    public array $failedList = [];

    public function __construct(
    string $batchName,
    array $reportData = [],  // Array with senderName and senderRole
    public int $totalApplicants,
    public int $newApplicants,
    public int $existingApplicants ,
    public int $failedUploads 
) {
    $this->senderName = Auth::user()?->full_name ?? 'AWS HR';
    $this->senderRole = Auth::user()?->role_label ?? 'HR';
    $this->batchName = $batchName;
    $this->date = now()->format('F j, Y');

}

    public function build()
    {
        return $this->subject("【HR System】Upload Status Report ({$this->batchName}) as of {$this->date}")
            ->html($this->buildHtml());
    }

    private function buildHtml(): string
{
    $failedListHtml = '';

    // Ensure $this->failedList is an array and contains arrays with 'name' and 'reason' keys
    if (is_array($this->failedList)) {
        foreach ($this->failedList as $failed) {
            // Check if each item in the failed list is an array with the expected structure
            if (is_array($failed) && isset($failed['name'], $failed['reason'])) {
                $failedListHtml .= '<p style="margin-left:15px; margin-bottom:5px;">- ' . e($failed['name']) . ', ' . e($failed['reason']) . '</p>';
            } else {
                // Handle invalid data structure
                $failedListHtml .= '<p style="margin-left:15px; margin-bottom:5px;">- Invalid failed data</p>';
            }
        }
    } else {
        $failedListHtml = '<p style="margin-left:15px; margin-bottom:5px;">No failed applicants.</p>';
    }

    return '
    <div style="font-family: Arial, sans-serif; background:#f4f4f5; padding:30px;">
        <div style="background:#ffffff; border:1px solid #B1B6C4; border-radius:10px; overflow:hidden;">
            <div style="background:#cae1fc; color:#fff; padding:15px 20px; border-radius:10px 10px 0 0;">
                <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                    <tr>
                        <td width="50" style="vertical-align:middle;">
                            <img 
                                src="' . url('images/aws.png') . '"
                                alt="AWS Logo"
                                width="40"
                                style="display:block; border-radius:6px;"
                            >
                        </td>
                    </tr>
                </table>
            </div>

            <div style="background:#ffffff; border-radius:0 0 10px 10px; overflow:hidden;">
                <div style="padding:25px; color:#333;">
                    <h5 style="margin-top:0;">
                        Upload Status Report (<b  style="color: #2F359E;">' . e($this->batchName) . '</b>) as of ' . e($this->date) . '
                    </h5>
                    <br>
                    <p><strong>Total Number of Applicants:</strong> ' . e($this->totalApplicants) . '</p>
                    <p><b>Number of Successful Uploads:</b> ' . e($this->newApplicants) . '</p>
                    <p><b>Number of failed uploads:</b> ' . e($this->failedUploads) . ' </p>
                    <p style="margin-left:15px;">
                     <br>
                    </p>
                    

                    <br>


                    <br>
                    <p>Thank you,</p>
                    <strong>' . e($this->senderName) . '</strong>
                    <p margin:0;">' . e($this->senderRole) . '</p>
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
        font-size:12px;}
    </style>';
}
}