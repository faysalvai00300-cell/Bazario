<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your OTP Code - SmartLookBD</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f7f9; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #333;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td align="center" style="padding: 40px 0;">
                <table border="0" cellpadding="0" cellspacing="0" width="400" style="background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
                    <tr>
                        <td align="center" style="padding: 30px 40px; background-color: #1a1a1a;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 24px; letter-spacing: 2px;">SmartLook <span style="color: #45b86f;">BD</span></h1>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="padding: 40px 40px 20px 40px;">
                            <h2 style="margin: 0 0 20px 0; color: #111827; font-size: 20px; font-weight: 600;">Verification Code</h2>
                            <p style="margin: 0; color: #6b7280; font-size: 14px; line-height: 24px;">Please use the following 6-digit code to complete your login. This code is valid for 10 minutes.</p>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="padding: 0 40px 40px 40px;">
                            <div style="background-color: #f9fafb; border: 2px dashed #e5e7eb; border-radius: 12px; padding: 20px; margin: 20px 0;">
                                <span style="font-size: 36px; font-weight: 800; letter-spacing: 12px; color: #FF6A00; font-family: 'Courier New', Courier, monospace;">{{ $otp }}</span>
                            </div>
                            <p style="margin: 0; color: #9ca3af; font-size: 12px;">If you didn't request this code, you can safely ignore this email.</p>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="padding: 20px 40px; background-color: #f9fafb; border-top: 1px solid #f1f5f9;">
                            <p style="margin: 0; color: #64748b; font-size: 11px;">© {{ date('Y') }} <a href="{{ url('/') }}" style="color: #45b86f; text-decoration: none;">SmartLookBD</a> . All rights reserved.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
