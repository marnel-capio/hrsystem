<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ResourceRequisitionDeletedMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $projectName;
    public string $location;
    public string $startDate;
    public string $senderName;
    public string $senderRole;


    public function __construct(string $projectName, string $location, $startDate)
    {
        $this->projectName = $projectName;
        $this->location = $location;

        $this->startDate = Carbon::parse($startDate)
            ->format('F j, Y');

        $this->senderName = Auth::user()?->full_name ?? 'AWS HR';
        $this->senderRole = Auth::user()?->role_label ?? 'HR';
    }

    public function build()
    {
        return $this->subject("【HR System】Resource Requisition Deleted")
            ->html($this->buildHtml());
    }

    private function buildHtml(): string
    {
        return '
        <div style="font-family: Arial, sans-serif; background:#f4f4f5; padding:30px;">

            <!-- CARD -->
            <div style="background:#ffffff; border:1px solid #B1B6C4; border-radius:10px; overflow:hidden;">

                <!-- HEADER -->
                <div style="background:#cae1fc; color:#fff; padding:15px 20px; border-radius:10px 10px 0 0;">

                    <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                        <tr>

                            <!-- LEFT LOGO -->
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

                <!-- BODY CARD -->
                <div style="background:#ffffff; border-radius:0 0 10px 10px; overflow:hidden;">

                    <!-- CONTENT -->
                    <div style="padding:25px;">

                        <p>Good Day!</p><br>

                        <p style="color:#333;">
                            A Resource Requisition has been deleted.
                        </p>

                    <!-- DETAILS BOX -->
                    <div style="background:#f9fafb; border-radius:8px; margin-bottom:20px;">

                        <p style="">
                            <strong>Project:</strong>
                                ' . e($this->projectName) . '
                        </p>
                        <p style="">
                            <strong>Location Assignment:</strong> 
                            ' . e($this->location) . '
                        </p>
                        <p style="">
                            <strong>Start Date:</strong> 
                            ' . e($this->startDate) . '
                        </p>

                    </div><br>

                    <p style="font-size:14px">Thank you,</p>
                        <strong style="font-size:14px">' . e($this->senderName) . '</strong>
                        <p style="font-size:14px">' . e($this->senderRole) . '</p>

                    </div>

                    <!-- FOOTER (BOTTOM OF CARD, EDGE-TO-EDGE) -->
                    <div style="padding:10px; background:#f3f4f6; text-align:center; border-bottom:1px solid #cfd0d5;">
                        <p style="font-size:11px; color:#6b7280; margin:0;">
                            © 2026 Advanced World Solutions. All rights reserved.
                        </p>
                    </div>

            </div>

        </div>
        ';
    }
}