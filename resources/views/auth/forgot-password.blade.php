<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Forgot Password</title>

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

.wrapper{width:100%;max-width:360px;}

.logo{
    width:46px;height:46px;
    background:#d9d9d9;
    border-radius:8px;
    margin-bottom:18px;
}

.title{font-size:18px;margin-bottom:6px;}

.subtitle{
    font-size:12px;
    color:#4f6b52;
    margin-bottom:26px;
}

.input{
    width:100%;
    height:34px;
    border-radius:14px;
    border:1px solid #3E5641;
    background:transparent;
    color:#fff;
    padding:0 14px;
    font-size:11px;
}

.input::placeholder{
    color:#4c5f4e;
}

.input:focus{
    outline:none;
    box-shadow:0 0 0 2px rgba(62,86,65,0.2);
}

.btn{
    width:100%;
    height:34px;
    border:none;
    border-radius:14px;
    background:#3E5641;
    color:#fff;
    margin-top:14px;
    cursor:pointer;
    font-size:12px;
}

.divider{
    display:flex;
    align-items:center;
    margin:20px 0;
    font-size:11px;
    color:#aaa;
}

.divider::before,.divider::after{
    content:"";
    flex:1;
    height:1px;
    background:#666;
}

.divider span{
    margin:0 10px;
}

.google{
    width:100%;
    height:34px;
    border-radius:14px;
    border:1px solid #3E5641;
    display:flex;
    align-items:center;
    justify-content:center;
    gap:10px;
    color:#fff;
    text-decoration:none;
}

.footer{
    margin-top:20px;
    font-size:11px;
    color:#ccc;
}

.footer a{
    color:#fff;
    text-decoration:underline;
}
</style>
</head>

<body>

<div class="wrapper">

<div class="logo"></div>

<div class="title">Forgot password</div>
<div class="subtitle">
No worries. Drop your email and we’ll help you get back in.
</div>

<form>
    <input type="email" class="input" placeholder="Enter your email">
    <button class="btn">Send</button>
</form>

<div class="divider"><span>Or login with</span></div>

<a href="#" class="google">
    <img src="https://www.svgrepo.com/show/475656/google-color.svg" width="18">
    Google
</a>

<div class="footer">
    Remember your password?
    <a href="{{ route('login') }}">Login</a>
</div>

</div>

</body>
</html>