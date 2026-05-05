<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Verification Code - Moonoma</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #0d0f0d;
            margin: 0;
            padding: 0;
            color: #ffffff;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #0f1110;
            border: 1px solid #3E5641;
            border-radius: 20px;
            padding: 40px;
            text-align: center;
        }
        .logo {
            font-size: 28px;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 30px;
            letter-spacing: 2px;
        }
        .logo span {
            color: #3E5641;
        }
        h1 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #6f8a75;
        }
        p {
            font-size: 16px;
            line-height: 1.6;
            color: #888888;
            margin-bottom: 30px;
        }
        .otp-code {
            display: inline-block;
            background: linear-gradient(135deg, #3E5641 0%, #1f2d22 100%);
            color: #ffffff;
            font-size: 36px;
            font-weight: 700;
            padding: 15px 40px;
            border-radius: 15px;
            letter-spacing: 8px;
            margin-bottom: 30px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .footer {
            margin-top: 40px;
            font-size: 12px;
            color: #444444;
        }
        .highlight {
            color: #c9a227;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="{{ $message->embed(public_path('images/logo.png')) }}" alt="Moonoma Logo" style="width: 200px; height: auto; max-width: 100%;">
        </div>
        <h1>{{ $title }}</h1>
        <p>{{ $messageText }}</p>
        
        <div class="otp-code">
            {{ $otp }}
        </div>
        
        <p style="font-size: 14px;">
            This code will expire shortly. If you did not request this, please ignore this email.
        </p>
        
        <div class="footer">
            &copy; {{ date('Y') }} Moonoma Project. All rights reserved.<br>
            Where Skills Meet Vision.
        </div>
    </div>
</body>
</html>
