<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class ResourceRequisitionNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $projectName;
    public string $link;
    public string $senderName;
    public string $senderRole;

    public function __construct(string $projectName, string $link)
    {
        $this->projectName = $projectName;
        $this->link = $link;

        $this->senderName = Auth::user()?->full_name ?? 'AWS HR';
        $this->senderRole = Auth::user()?->role_label ?? 'HR';
    }

    public function build()
    {
        return $this->subject("【HR System】New Resource Requisition Created")
        ->bcc("awsrecruiter@awsys-i.com")
            ->html($this->buildHtml());
    }

    private function buildHtml(): string
    {
        return '
        <div style="font-family: Arial, sans-serif; background:#f4f4f5; padding:30px;">
        <div style="background:#80ABCB; border:1px solid #B1B6C4; border-radius:10px; overflow:hidden;">
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

                <div style="background:#ffffff;  overflow:hidden;">
                    <div style="padding:25px; color:#333;">

                        <p>Good Day! <br><br>
                        A new Resource Requisition has been created. <br><br>
                        <strong>Project:</strong>
                        <a href="' . e($this->link) . '"
                        style="color:blue; text-decoration: underline; font-weight:bold;">
                        ' . e($this->projectName) . '
                        </a><br><br>
                        Please review in the HR System for further details.
                        </p><br>

                        <p>Best Regards,<br>
                        <strong>' . e($this->senderName) . '</strong><br>
                        ' . e($this->senderRole) . '</p>
                    </div>
                </div>


                <!-- FOOTER (BOTTOM OF CARD, EDGE-TO-EDGE) -->
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
    }
}
