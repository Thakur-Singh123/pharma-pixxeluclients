<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>New Booking Received | Ad People</title>
    </head>
    <body style="margin:0; padding:0; background:#f2f4f8; font-family:Arial, Helvetica, sans-serif;">
        <table width="100%" cellpadding="0" cellspacing="0" style="padding:20px 0;">
            <tr>
                <td align="center">
                    <table width="650" cellpadding="0" cellspacing="0"
                        style="background:#ffffff; border-radius:10px;
                        box-shadow:0 6px 18px rgba(0,0,0,0.08);
                        border-top:4px solid #32bf3e;
                        border-bottom:4px solid #32bf3e;">
                        <tr>
                            <td style="padding:20px 30px; text-align:center; border-bottom:1px solid #e6e9f0; background: aliceblue;">
                                <p style="margin:0; font-size:16px; font-weight:600; color:#32bf3e;">
                                Ad People – Panel
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:26px 28px; color:#333333;">
                                <p style="margin:0 0 16px; font-size:14px; color:#444;">
                                    A <strong>new patient booking</strong> has been received.
                                    Please review the details below and take necessary action.
                                </p>
                                <table width="100%" cellpadding="0" cellspacing="0" style="background:#f9fafb; border:1px solid #e5e7eb; border-radius:8px; margin-bottom:20px;">
                                    <tr>
                                        <td style="padding:16px; font-size:14px;">
                                            <p style="margin:0 0 8px;">
                                                <strong>Patient Name:</strong> {{ $patient->patient_name }}
                                            </p>
                                            <p style="margin:0 0 8px;">
                                                <strong>Mobile:</strong> {{ $patient->mobile_no }}
                                            </p>
                                            <p style="margin:0 0 8px;">
                                                <strong>Email:</strong> {{ $patient->email }}
                                            </p>
                                            <p style="margin:0 0 8px;">
                                                <strong>Department:</strong> {{ $patient->department }}
                                            </p>
                                            <p style="margin:0 0 8px;">
                                                <strong>UHID:</strong> {{ $patient->uhid_no ?? 'N/A' }}
                                            </p>
                                            <p style="margin:0 0 8px;">
                                                <strong>Booking Amount:</strong>
                                                ₹{{ number_format($patient->booking_amount ?? 0) }}
                                            </p>
                                            <p style="margin:0 0 8px;">
                                                <strong>Booking Status:</strong> {{ $patient->booking_done }}
                                            </p>
                                            <p style="margin:0;">
                                                <strong>Remark:</strong> {{ $patient->remark ?? '-' }}
                                            </p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td style="background:#f9fafb; padding:14px; text-align:center;
                                font-size:12px; color:#777; border-top:1px solid #e6e9f0;">
                                © {{ date('Y') }} Ad People. Internal notification.<br>
                                This email is intended for administrative use only.
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>