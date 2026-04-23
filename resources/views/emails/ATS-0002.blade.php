<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>New Resource Schedule Notification</title>
</head>

<body style="font-family: Arial, sans-serif; background:#f4f4f5; padding:30px;">
    <div style="background:#80ABCB; border:1px solid #B1B6C4; border-radius:10px; overflow:hidden;">
        <div style="background:#cae1fc; color:#fff; padding:15px 20px; border-radius:10px 10px 0 0;">
            <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                <tr>
                    <td width="50" style="vertical-align:middle;">
                        <img 
                            src="{{ asset('images/aws.png') }}"
                            alt="AWS Logo"
                            width="40"
                            style="display:block; border-radius:6px;">
                    </td>
                </tr>
            </table>
        </div>

        <div style="background:#ffffff; overflow:hidden;">
            <div style="padding:25px; color:#333; font-size: 12px;">
                <p>Good Day! <br><br>
                A resource schedule has been created/updated.<br><br>
                <strong>Batch:</strong>
                <a href="{{ $link }}" 
                   style="color:blue; text-decoration: underline; font-weight:bold;">
                   {{ $batchName }}
                </a><br><br>
                Please review in the HR System for further details.
                </p><br>

                <p>Best Regards,<br>
                <strong>{{ $senderName }}</strong><br>
                <span style="font-size: 12px; color: #555;">
                    {{ $senderRole }}
                </span></p>
            </div>
        </div>

        <!-- FOOTER (BOTTOM OF CARD, EDGE-TO-EDGE) -->
        <div style="padding:10px; background:#f3f4f6; text-align:center; border-top:1px solid #cfd0d5;">
            <p style="font-size:12px; color:#6b7280; margin:0;">
                © {{ date('Y') }} Advanced World Solutions. All rights reserved.
            </p>
        </div>
    </div>

    <style>
        p {
            font-size: 12px;
            margin-bottom: 7px;
        }
    </style>
</body>

</html>