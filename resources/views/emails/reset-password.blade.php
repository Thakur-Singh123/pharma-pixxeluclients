<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
</head>

<body style="margin:0;padding:0;background:#eef2f7;font-family:Arial,Helvetica,sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" style="padding:40px 0;">
<tr>
<td align="center">

<table width="600" cellpadding="0" cellspacing="0"
       style="background:#ffffff;border-radius:12px;
       box-shadow:0 8px 25px rgba(0,0,0,0.08);overflow:hidden;">

    <!-- HEADER (Slim & Clean) -->
    <tr>
        <td style="
            background:#1f4ed8;
            padding:22px;
            text-align:center;
            color:#ffffff;">
            
            <h2 style="margin:0;font-size:20px;font-weight:600;">
                Ad People
            </h2>
            <p style="margin:4px 0 0;font-size:12px;opacity:0.9;">
                Hospital Management System
            </p>
        </td>
    </tr>

    <!-- BODY -->
    <tr>
        <td style="padding:34px 40px;color:#1f2937;">

            <p style="margin:0 0 14px;font-size:14px;">
                Dear <strong>{{ $user->name }}</strong>,
            </p>

            <p style="margin:0 0 22px;font-size:14px;color:#4b5563;line-height:1.6;">
                We received a request to reset your password for your
                <strong>Ad People</strong> account.
            </p>

            <!-- BUTTON (Smaller & Sharp) -->
            <div style="text-align:center;margin:26px 0;">
                <a href="{{ $url }}"
                   style="

                background: #31ce36;
                color: #ffffff;
                padding: 10px 18px;
                text-decoration: none;
                border-radius: 8px;
                font-size: 12px;
                font-weight: 600;
                display: inline-block;

                   ">
                    Reset Password
                </a>
            </div>

            <p style="margin:0 0 10px;font-size:13px;color:#6b7280;">
                This password reset link will expire in <strong>60 minutes</strong>.
            </p>

            <p style="margin:0 0 22px;font-size:13px;color:#6b7280;">
                If you did not request a password reset, no further action is required.
            </p>

            <p style="margin:0;font-size:13px;">
                Regards,<br>
                <strong>Ad People Team</strong>
            </p>

        </td>
    </tr>

    <!-- FOOTER -->
    <tr>
        <td style="
            background:#f8fafc;
            padding:14px;
            text-align:center;
            font-size:11px;
            color:#64748b;">
            © {{ date('Y') }} Ad People. All rights reserved.
        </td>
    </tr>

</table>

</td>
</tr>
</table>

</body>
</html>
