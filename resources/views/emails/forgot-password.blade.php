<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reset Your Password - Moonoma</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
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
            color: #c9a227;
        }
        p {
            font-size: 16px;
            line-height: 1.6;
            color: #888888;
            margin-bottom: 30px;
        }
        .btn {
            display: inline-block;
            background: #c9a227;
            color: #1a1100 !important;
            font-size: 16px;
            font-weight: 700;
            padding: 15px 40px;
            border-radius: 30px;
            text-decoration: none;
            transition: 0.3s;
        }
        .footer {
            margin-top: 40px;
            font-size: 12px;
            color: #444444;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="{{ $message->embed(public_path('images/logo.png')) }}" alt="Moonoma Logo" style="width: 200px; height: auto; max-width: 100%;">
        </div>
        <h1>Reset Your Password</h1>
        <p>
            We received a request to reset the password for your Moonoma account. 
            Click the button below to choose a new password.
        </p>
        
        <a href="{{ $link }}" class="btn">Reset Password</a>
        
        <p style="font-size: 14px; margin-top: 30px;">
            If you did not request a password reset, please ignore this email or contact support if you have concerns.
        </p>
        
        <div class="footer">
            &copy; {{ date('Y') }} Moonoma Project. All rights reserved.<br>
            Where Skills Meet Vision.
        </div>
    </div>
</body>
</html>
