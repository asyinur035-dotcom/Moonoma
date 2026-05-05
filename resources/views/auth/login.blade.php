<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>Login to Moonoma</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Poppins',sans-serif;
}

body{
    min-height:100vh;
    background:#0d0f0d;
    color:#ffffff;
    display:flex;
    align-items:center;
    justify-content:center;
}

.login-wrapper{
    width:100%;
    max-width:380px;
}

/* LOGO */
.auth-logo{
    width: 260px;
    height: auto;
    max-width: 100%;
    margin-bottom: 25px;
    object-fit: contain;
    display: block;
    margin-left: auto;
    margin-right: auto;
}

/* TEXT */
.title{
    font-size:20px;
    margin-bottom:6px;
}

.subtitle{
    font-size:13px;
    color:#4f6b52;
    margin-bottom:30px;
}

/* INPUT */
.form-group{
    margin-bottom:14px;
}

.input-field{
    width:100%;
    height:40px;
    border-radius:20px;
    border:1px solid #3E5641;
    background:#0d0f0d; /* 🔥 FIX */
    color:#fff;
    padding:0 16px;
    font-size:13px;
}

/* placeholder */
.input-field::placeholder{
    color:#4c5f4e;
}

/* focus */
.input-field:focus{
    background:#0d0f0d;
    color:#fff;
    outline:none;
    box-shadow:0 0 0 2px rgba(62,86,65,0.2);
}

/* 🔥 AUTOFILL FIX */
input:-webkit-autofill,
input:-webkit-autofill:hover,
input:-webkit-autofill:focus{
    -webkit-box-shadow: 0 0 0px 1000px #0d0f0d inset !important;
    -webkit-text-fill-color: #fff !important;
}

/* PASSWORD */
.password-wrapper{
    position:relative;
}

.password-wrapper input{
    padding-right:40px;
}

.toggle-password{
    position:absolute;
    right:12px;
    top:50%;
    transform:translateY(-50%);
    cursor:pointer;
    width:18px;
    height:18px;
}

.toggle-password svg{
    width:100%;
    height:100%;
    stroke:#3E5641;
}

/* BUTTON */
.login-btn{
    width:100%;
    height:40px;
    border:none;
    border-radius:20px;
    background:#3E5641;
    color:#fff;
    font-size:14px;
    cursor:pointer;
    margin-top:5px;
}

.login-btn:hover{
    opacity:0.9;
}

/* LINKS */
.helper-link{
    display:inline-block;
    margin-top:10px;
    font-size:12px;
    color:#ccc;
    text-decoration:underline;
}

/* DIVIDER */
.divider{
    display:flex;
    align-items:center;
    margin:22px 0;
    font-size:12px;
    color:#aaa;
}

.divider::before,
.divider::after{
    content:"";
    flex:1;
    height:1px;
    background:#3E5641;
}

.divider span{
    margin:0 12px;
}

/* GOOGLE BUTTON */
.google-btn{
    width:100%;
    height:40px;
    border-radius:20px;
    border:1px solid #3E5641;
    display:flex;
    align-items:center;
    justify-content:center;
    gap:10px;
    color:#fff;
    text-decoration:none;
}

.google-btn:hover{
    background:#3E5641;
}

/* REGISTER */
.register-text{
    margin-top:20px;
    font-size:12px;
    color:#ccc;
}

.register-text a{
    color:#fff;
    text-decoration:underline;
}
</style>
</head>

<body>

<div class="login-wrapper">

<img src="{{ asset('images/logo.png') }}" alt="Moonoma Logo" class="auth-logo">

<div class="title">Login to Moonoma</div>
<div class="subtitle">Everyone needs a space to grow. This is yours.</div>

@if ($errors->any())
<div style="background: rgba(232, 124, 124, 0.1); color: #e87c7c; border: 1px solid #e87c7c; padding: 12px; border-radius: 10px; margin-bottom: 20px; font-size: 13px;">
    <ul style="padding-left: 20px; margin: 0;">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

@if (session('success'))
<div style="background: rgba(62, 86, 65, 0.2); color: #8fba97; border: 1px solid #3E5641; padding: 12px; border-radius: 10px; margin-bottom: 20px; font-size: 13px; text-align: center;">
    {{ session('success') }}
</div>
@endif


<form method="POST" action="{{ route('login') }}">
@csrf

<div class="form-group">
    <input type="email" name="email" class="input-field"
        placeholder="Enter your email"
        value="{{ old('email') }}">
</div>

<div class="form-group password-wrapper">
    <input type="password" id="password" name="password"
        class="input-field"
        placeholder="Enter your password">

    <span class="toggle-password" onclick="togglePassword('password', this)">
        <svg class="eye" fill="none" stroke-width="2" viewBox="0 0 24 24">
            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z"/>
            <circle cx="12" cy="12" r="3"/>
        </svg>

        <svg class="eye-off" fill="none" stroke-width="2" viewBox="0 0 24 24" style="display:none;">
            <path d="M1 1l22 22"/>
        </svg>
    </span>
</div>

<button type="submit" class="login-btn">Login</button>

<a href="{{ route('password.request') }}" class="helper-link">Forgot password?</a>

</form>

<div class="divider"><span>Or login with</span></div>

<a href="{{ route('google.redirect') }}" class="google-btn">
    <img src="https://www.svgrepo.com/show/475656/google-color.svg" width="18">
    Google
</a>

<div class="register-text">
    Do not have an account?
    <a href="{{ route('register') }}">Register</a>
</div>

</div>

<script>
function togglePassword(id, el){
    const input = document.getElementById(id);
    const eye = el.querySelector('.eye');
    const eyeOff = el.querySelector('.eye-off');

    if(input.type === "password"){
        input.type = "text";
        eye.style.display = "none";
        eyeOff.style.display = "block";
    }else{
        input.type = "password";
        eye.style.display = "block";
        eyeOff.style.display = "none";
    }
}
</script>

</body>
</html>