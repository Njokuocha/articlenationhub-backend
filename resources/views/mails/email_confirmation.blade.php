<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Confirmation</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>
<body
style="
font-family: 'Roboto', sans-serif;
">
    <section class="email-confirmation"
    style="max-width: 500px; padding: 20px 15px;">
        <div class="header">
            <div class="logo">
                <img src="http://localhost:8000/images/logo.png" alt="Logo" 
                style="width: 150px;">
            </div>
            <h1 style="font-size: 2rem; font-weight: bold; color: #0097A7; margin-top: 10px;">Confirm Your Email</h1>
        </div>
        

        <div class="content">
        <p>Hello {{ $name }},</p>

        <p>Thank you for signing up for <strong>{{ config('app.name') }}</strong>! Please confirm your email address by clicking the button below:</p>

        <div style="margin: 30px 0;">
            <a href="{{ $verificationUrl }}" 
            style="padding: 6px 11px; background: #0097A7; color: white; text-align: center;
            text-decoration: none; cursor: pointer; border-radius: 3px;">Verify Email Address</a>
        </div>
        <p>If you didn’t create an account, no further action is required.</p>

        <p>Best regards,<br>The {{ config('app.name') }} Team</p>
        </div>
        
        <div class="footer">
        &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </div>
  </div>
    </section>
</body>
</html>