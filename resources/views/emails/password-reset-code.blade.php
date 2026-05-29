<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Password Reset Code</title>
</head>
<body style="margin:0;padding:24px;background:#f5f1e8;font-family:Arial,sans-serif;color:#1f2937;">
    <div style="max-width:560px;margin:0 auto;background:#ffffff;border-radius:16px;padding:32px;border:1px solid #e5e7eb;">
        <p style="margin:0 0 8px;font-size:12px;letter-spacing:1.2px;text-transform:uppercase;color:#92400e;">VA Tools</p>
        <h1 style="margin:0 0 16px;font-size:28px;line-height:1.2;color:#111827;">Password Reset Code</h1>
        <p style="margin:0 0 16px;font-size:16px;line-height:1.6;">Hello {{ $fullName }},</p>
        <p style="margin:0 0 24px;font-size:16px;line-height:1.6;">Use the code below to reset your password. This code expires in 15 minutes.</p>
        <div style="margin:0 0 24px;padding:18px 20px;background:#111827;color:#ffffff;border-radius:14px;text-align:center;font-size:32px;font-weight:700;letter-spacing:8px;">
            {{ $code }}
        </div>
        <p style="margin:0;font-size:14px;line-height:1.6;color:#4b5563;">If you did not request this reset, you can ignore this email.</p>
    </div>
</body>
</html>
