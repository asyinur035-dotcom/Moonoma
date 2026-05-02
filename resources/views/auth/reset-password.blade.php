<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reset Password - Moonoma</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
    *{margin:0;padding:0;box-sizing:border-box;font-family:'Poppins',sans-serif;}
    body{
        background:#0d0f0d;
        color:#fff;
        display:flex;
        justify-content:center;
        align-items:center;
        min-height:100vh;
    }
    .wrapper{width:100%;max-width:360px; padding:20px;}
    .logo{
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 20px;
        text-align: center;
    }
    .logo span { color: #3E5641; }
    .title{font-size:22px; margin-bottom:8px; text-align: center; font-weight: 600;}
    .subtitle{
        font-size:13px;
        color:#6f8a75;
        margin-bottom:30px;
        line-height:1.6;
        text-align: center;
    }
    .form-group {
        margin-bottom: 20px;
    }
    .label {
        display: block;
        font-size: 13px;
        color: #888;
        margin-bottom: 8px;
    }
    .input {
        width: 100%;
        height: 48px;
        background: rgba(62, 86, 65, 0.05);
        border: 1px solid #3E5641;
        border-radius: 12px;
        padding: 0 15px;
        color: #fff;
        font-size: 14px;
        transition: 0.3s;
    }
    .input:focus {
        outline: none;
        border-color: #6f8a75;
        background: rgba(62, 86, 65, 0.15);
    }
    .btn{
        width:100%;
        height:48px;
        border:none;
        border-radius:24px;
        background:#3E5641;
        color:#fff;
        cursor:pointer;
        font-size:15px;
        font-weight: 600;
        transition: 0.3s;
        margin-top: 10px;
    }
    .btn:hover {
        background: #4f6b52;
        transform: translateY(-2px);
    }
    .alert {
        padding: 12px;
        border-radius: 10px;
        margin-bottom: 20px;
        font-size: 13px;
        text-align: center;
    }
    .alert-error { background: rgba(232, 124, 124, 0.1); color: #e87c7c; border: 1px solid #e87c7c; }
</style>
</head>
<body>

<div class="wrapper">
    <div class="logo">MOON<span>OMA</span></div>
    <div class="title">New Password</div>
    <div class="subtitle">
        Create a new secure password for your Moonoma account.
    </div>

    @if($errors->any())
        <div class="alert alert-error">{{ $errors->first() }}</div>
    @endif

    <form action="{{ route('password.update') }}" method="POST">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <input type="hidden" name="email" value="{{ $email }}">

        <div class="form-group">
            <label class="label">New Password</label>
            <input type="password" name="password" class="input" placeholder="Min. 8 characters" required>
        </div>

        <div class="form-group">
            <label class="label">Confirm New Password</label>
            <input type="password" name="password_confirmation" class="input" placeholder="Repeat your password" required>
        </div>

        <button type="submit" class="btn">Update Password</button>
    </form>
</div>

</body>
</html>
