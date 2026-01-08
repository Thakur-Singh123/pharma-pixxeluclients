<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
</head>
<body style="margin:0; padding:0; background:#f3f4f6; font-family:Arial, sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" style="background:#f3f4f6; padding:40px 0;">
    <tr>
        <td align="center">
            <table width="520" cellpadding="0" cellspacing="0"
                   style="background:#ffffff; border-radius:14px; padding:35px 30px; box-shadow:0 15px 40px rgba(0,0,0,0.12);">

                <!-- LOGO / TITLE -->
                <tr>
                    <td style="text-align:center;">
                        <h2 style="margin:0; color:#111827;">Forgot your password?</h2>
                        <p style="margin:12px 0 0; color:#6b7280; font-size:14px;">
                            Don’t worry, it happens. Let’s get you back in.
                        </p>
                    </td>
                </tr>

                <!-- DIVIDER -->
                <tr>
                    <td style="padding:25px 0;">
                        <hr style="border:none; border-top:1px solid #e5e7eb;">
                    </td>
                </tr>

                <!-- MESSAGE -->
                <tr>
                    <td style="color:#374151; font-size:14px; line-height:1.6;">
                        Hi {{ $user->name ?? 'there' }},<br><br>
                        We received a request to reset your password.  
                        Click the button below to choose a new one.
                    </td>
                </tr>

                <!-- BUTTON -->
                <tr>
                    <td align="center" style="padding:30px 0;">
                        <a href="{{ $resetUrl }}"
                           style="background:#7c3aed; color:#ffffff; text-decoration:none;
                                  padding:14px 28px; border-radius:10px;
                                  font-size:15px; font-weight:600; display:inline-block;">
                            Reset Password
                        </a>
                    </td>
                </tr>

                <!-- FOOTER TEXT -->
                <tr>
                    <td style="color:#6b7280; font-size:13px; line-height:1.6;">
                        This password reset link will expire in 60 minutes.<br><br>
                        If you did not request a password reset, you can safely ignore this email.
                    </td>
                </tr>

                <!-- SIGNATURE -->
                <tr>
                    <td style="padding-top:25px; font-size:13px; color:#6b7280;">
                        Regards,<br>
                        <strong>Your Company Team</strong>
                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>

</body>
</html>
