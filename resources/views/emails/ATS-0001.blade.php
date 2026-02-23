<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Password Reset Notification</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f6f8; margin: 0; padding: 0;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f6f8; padding: 20px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    
                    <!-- Header / Logo -->
                    <tr>
                        <td style="background-color: #0d6efd; padding: 20px; text-align: center;">
                            <img src="{{ asset('images/aws-logo.jpg') }}" alt="AWS Logo" width="150" style="display: block;">
                        </td>
                    </tr>
                    
                    <!-- Body -->
                    <tr>
                        <td style="padding: 30px; color: #333333;">
                            <p style="font-size: 16px;">Hi {{ $firstName }},</p>

                            <p style="font-size: 16px; line-height: 1.6;">
                                A request has been received to reset your password for your ATS - HR account.
                            </p>

                            <p style="font-size: 16px; line-height: 1.6;">
                                Please refer to your updated login details below:
                            </p>

                            <p style="font-size: 16px; line-height: 1.6; font-weight: bold; color: #0d6efd;">
                                Password: {{ $newPassword }}
                            </p>

                            <p style="font-size: 16px; line-height: 1.6;">
                                For security purposes, we recommend logging in immediately and changing your password after access.
                            </p>

                            <p style="font-size: 16px; line-height: 1.6;">
                                If you did not request this password reset, please contact the HR Administration immediately.
                            </p>

                            <p style="font-size: 16px; line-height: 1.6;">
                                Thank you,<br>
                                <strong>AWS HR</strong>
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f1f3f6; padding: 20px; text-align: center; font-size: 12px; color: #999999;">
                            &copy; {{ date('Y') }} Advanced World Solutions. All rights reserved.
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
