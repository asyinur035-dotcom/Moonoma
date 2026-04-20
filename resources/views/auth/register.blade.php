<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Register to Moonoma</title>

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

.wrapper{
    width:100%;
    max-width:420px;
}

.logo{
    width:48px;height:48px;
    background:#ccc;border-radius:8px;
    margin-bottom:20px;
}

.title{font-size:20px;margin-bottom:6px;}
.subtitle{font-size:12px;color:#4f6b52;margin-bottom:28px;}

.row{display:flex;gap:10px;}
.group{margin-bottom:16px;}

.input{
    width:100%;height:42px;
    border-radius:20px;
    border:1px solid #3E5641;
    background:transparent;
    padding:0 16px;
    color:#3E5641;
    font-size:12px;
}

.input::placeholder{ color:#4c5f4e; }

.input:focus{
    outline:none;
    box-shadow:0 0 0 2px rgba(62,86,65,0.2);
}

.password-wrapper{ position:relative; }
.password-wrapper input{ padding-right:40px; }

.toggle-password{
    position:absolute;
    right:14px;
    top:50%;
    transform:translateY(-50%);
    cursor:pointer;
    width:18px;height:18px;
}

/* 🔥 FIX ICON STYLE */
.toggle-password svg{
    width:100%;
    height:100%;
    stroke:#3E5641;
}

.btn{
    width:100%;height:44px;
    border:none;border-radius:20px;
    background:#3E5641;
    color:#fff;font-size:13px;
    cursor:pointer;
    margin-top:6px;
}

.divider{
    display:flex;
    align-items:center;
    margin:22px 0;
    font-size:11px;
    color:#aaa;
}

.divider::before,.divider::after{
    content:"";
    flex:1;
    height:1px;
    background:#3E5641;
    opacity:0.5;
}

.divider span{margin:0 10px;}

.google{
    width:100%;height:44px;
    border-radius:20px;
    border:1px solid #3E5641;
    display:flex;
    align-items:center;
    justify-content:center;
    gap:10px;
    text-decoration:none;
    color:#fff;
    font-size:13px;
}

.footer{
    margin-top:20px;
    font-size:11px;
    color:#aaa;
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

<div class="title">Register to Moonoma</div>
<div class="subtitle">Everyone needs a space to grow. This is yours.</div>

<form method="POST" action="{{ route('register.process') }}">
@csrf

<div class="row group">
    <input type="text" name="name" class="input" placeholder="Username">

    <select name="role" class="input">
        <option disabled selected>Role</option>
        <option>Designer</option>
        <option>UI/UX</option>
        <option>Frontend</option>
        <option>Backend</option>
        <option>Mobile Dev</option>
    </select>
</div>

<div class="group">
    <input type="email" name="email" class="input" placeholder="Enter your email">
</div>

<!-- PASSWORD -->
<div class="group password-wrapper">
    <input type="password" id="password" name="password" class="input"
        placeholder="Enter your password">

    <!-- 🔥 ICON FIX -->
    <span class="toggle-password" onclick="togglePassword('password', this)">
        <svg class="eye" fill="none" stroke-width="2" viewBox="0 0 24 24">
            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z"/>
            <circle cx="12" cy="12" r="3"/>
        </svg>

        <svg class="eye-off" fill="none" stroke-width="2" viewBox="0 0 24 24" style="display:none;">
            <path d="M1 1l22 22"/>
            <path d="M17.94 17.94A10.94 10.94 0 0112 19c-7 0-11-7-11-7"/>
        </svg>
    </span>
</div>

<!-- CONFIRM -->
<div class="group password-wrapper">
    <input type="password" id="confirm_password" name="password_confirmation" class="input"
        placeholder="Confirm your password">

    <!-- 🔥 ICON FIX -->
    <span class="toggle-password" onclick="togglePassword('confirm_password', this)">
        <svg class="eye" fill="none" stroke-width="2" viewBox="0 0 24 24">
            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z"/>
            <circle cx="12" cy="12" r="3"/>
        </svg>

        <svg class="eye-off" fill="none" stroke-width="2" viewBox="0 0 24 24" style="display:none;">
            <path d="M1 1l22 22"/>
            <path d="M17.94 17.94A10.94 10.94 0 0112 19c-7 0-11-7-11-7"/>
        </svg>
    </span>
</div>

<button type="submit" class="btn">Register</button>

</form>

<div class="divider"><span>Or register with</span></div>

<a href="{{ route('google.redirect') }}" class="google">
    <img src="https://www.svgrepo.com/show/475656/google-color.svg" width="18">
    Google
</a>

<div class="footer">
    Already have account?
    <a href="{{ route('login') }}">Log In</a>
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