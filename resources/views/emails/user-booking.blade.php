<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Booking Confirmed | Ad People</title>
    </head>
    <body style="margin:0; padding:0; background:#f2f4f8; font-family:Arial, Helvetica, sans-serif;">
        <table width="100%" cellpadding="0" cellspacing="0" style="padding:20px 0;">
            <tr>
                <td align="center">
                    <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff; border-radius:10px; box-shadow:0 6px 18px rgba(0,0,0,0.08); border-top:4px solid #32bf3e; border-bottom:4px solid #32bf3e;">
                        <tr>
                            <td style="padding:20px 30px; text-align:center; border-bottom:1px solid #e6e9f0; background: aliceblue;">
                                <p style="margin:0; font-size:16px; font-weight:600; color:#32bf3e;">
                                    Ad People
                                </p>
                                <p style="margin:4px 0 0; font-size:12px; color:#6b7280;">
                                    Booking Confirmation
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:26px 28px; color:#333333;">
                                <p style="margin:0 0 10px; font-size:15px;">
                                    Hello <strong>{{ $patient->patient_name }}</strong>,
                                </p>
                                <p style="margin:0 0 18px; font-size:14px; line-height:22px; color:#444;">
                                    Thank you for choosing <strong>Ad People</strong>.
                                    Your appointment booking has been
                                    <strong style="color:#2454d6;">successfully received</strong>.
                                </p>
                                <table width="100%" cellpadding="0" cellspacing="0" style="background:#f9fafb; border:1px solid #e5e7eb; border-radius:8px; margin-bottom:18px;">
                                    <tr>
                                        <td style="padding:14px 16px;">
                                            <p style="margin:0 0 8px; font-size:14px;">
                                                <strong>Department:</strong> {{ $patient->department }}
                                            </p>
                                            <p style="margin:0 0 8px; font-size:14px;">
                                                <strong>Booking Amount:</strong> ₹{{ number_format($patient->booking_amount ?? 0) }}
                                            </p>
                                            <p style="margin:0 0 8px; font-size:14px;">
                                                <strong>Booking Confirmed:</strong>
                                                @if($patient->booking_done == 'Yes')
                                                    <span style="color:#16a34a; font-weight:600;">Yes</span>
                                                @endif
                                            </p>
                                        </td>
                                    </tr>
                                </table>
                                <p style="margin:0 0 18px; font-size:14px; color:#555;">
                                    Our team will contact you shortly to assist you further.
                                </p>
                                <p style="margin:0; font-size:14px;">
                                    Warm regards,<br>
                                    <strong>Team Ad People</strong>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td style="background:#f9fafb; padding:14px; text-align:center;
                                font-size:12px; color:#777; border-top:1px solid #e6e9f0;">
                                © {{ date('Y') }} Ad People. All rights reserved.<br>
                                This is an automated email. Please do not reply.
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>