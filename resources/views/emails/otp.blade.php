<!DOCTYPE html>
<html>
<body style="font-family: Arial; background:#f2f2f7; padding:20px;">
    <div style="max-width:500px; margin:auto; background:white; padding:30px; border-radius:10px; text-align:center;">
        <h2 style="color:#333;">Email Verification</h2>
        <p style="font-size:16px;">Hello <strong>{{ $name }}</strong>,</p>
        <p style="font-size:15px;">Use the OTP below to verify your email:</p>

        <h1 style="font-size:40px; letter-spacing:10px; color:#4A90E2;">{{ $otp }}</h1>

        <p style="color:#888;">This OTP is valid for 10 minutes.</p>
        <br>
        <p style="font-size:14px; color:#999;">Thank you!<br>Your App Team</p>
    </div>
</body>
</html>
