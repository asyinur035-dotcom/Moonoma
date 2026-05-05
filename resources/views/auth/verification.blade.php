<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Verification - Moonoma</title>
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
    .otp-container{
        display:flex;
        gap:10px;
        justify-content:center;
        margin-bottom:25px;
    }
    .otp-input{
        width:45px;
        height:50px;
        border-radius:12px;
        border:1px solid #3E5641;
        background:rgba(62, 86, 65, 0.05);
        text-align:center;
        font-size:20px;
        font-weight: 600;
        color:#fff;
        transition: 0.3s;
    }
    .otp-input:focus{
        outline:none;
        border-color: #6f8a75;
        background: rgba(62, 86, 65, 0.15);
        box-shadow: 0 0 15px rgba(62, 86, 65, 0.2);
    }
    .timer{
        text-align:center;
        font-size:13px;
        color:#888;
        margin-bottom:25px;
    }
    .resend-btn {
        background: none;
        border: none;
        color: #c9a227;
        cursor: pointer;
        font-size: 13px;
        font-weight: 600;
        text-decoration: underline;
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
    .alert-success { background: rgba(62, 86, 65, 0.2); color: #8fba97; border: 1px solid #3E5641; }
    .alert-error { background: rgba(232, 124, 124, 0.1); color: #e87c7c; border: 1px solid #e87c7c; }
</style>
</head>
<body>

<div class="wrapper">
    <div class="logo">MOON<span>OMA</span></div>
    <div class="title">Verification</div>
    <div class="subtitle">
        We've sent a 6-digit verification code to your email. Please enter it below.
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-error">
            <ul style="list-style: none; padding: 0; margin: 0;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('verify.otp') }}" method="POST" id="otpForm">
        @csrf
        <input type="hidden" name="otp" id="fullOtp">
        <div class="otp-container">
            <input class="otp-input" maxlength="1" required>
            <input class="otp-input" maxlength="1" required>
            <input class="otp-input" maxlength="1" required>
            <input class="otp-input" maxlength="1" required>
            <input class="otp-input" maxlength="1" required>
            <input class="otp-input" maxlength="1" required>
        </div>

        <div class="timer">
            Didn't receive the code? <span id="countdown">Resend in 00:59</span>
        </div>

        <button type="submit" class="btn">Verify Account</button>
    </form>
    
    <form id="resendForm" action="{{ route('resend.otp') }}" method="GET" style="display:none;"></form>
</div>

<script>
const inputs = document.querySelectorAll('.otp-input');
const form = document.getElementById('otpForm');
const fullOtpInput = document.getElementById('fullOtp');

inputs.forEach((input, index) => {
    input.addEventListener('input', (e) => {
        input.value = input.value.replace(/[^0-9]/g,'');
        if(input.value && index < inputs.length - 1){
            inputs[index + 1].focus();
        }
        updateFullOtp();
    });

    input.addEventListener('keydown', (e) => {
        if(e.key === "Backspace" && !input.value && index > 0){
            inputs[index - 1].focus();
        }
    });

    // Handle paste
    input.addEventListener('paste', (e) => {
        e.preventDefault();
        const data = e.clipboardData.getData('text').slice(0, 6);
        if(/^\d+$/.test(data)) {
            data.split('').forEach((char, i) => {
                if(inputs[i]) inputs[i].value = char;
            });
            updateFullOtp();
            inputs[Math.min(data.length, 5)].focus();
        }
    });
});

function updateFullOtp() {
    let otp = "";
    inputs.forEach(input => otp += input.value);
    fullOtpInput.value = otp;
}

form.addEventListener('submit', (e) => {
    updateFullOtp();
    if(fullOtpInput.value.length < 6) {
        e.preventDefault();
        alert('Please enter all 6 digits');
    }
});

// countdown timer
let time = 59;
const countdown = document.getElementById('countdown');

const timer = setInterval(() => {
    let seconds = time < 10 ? '0'+time : time;
    countdown.innerText = `Resend in 00:${seconds}`;
    time--;

    if(time < 0){
        clearInterval(timer);
        countdown.innerHTML = `<button type="button" class="resend-btn" onclick="document.getElementById('resendForm').submit()">Resend code</button>`;
    }
}, 1000);
// auto-fill from query param
window.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    const otpParam = urlParams.get('otp');
    if(otpParam && otpParam.length === 6) {
        otpParam.split('').forEach((char, i) => {
            if(inputs[i]) inputs[i].value = char;
        });
        updateFullOtp();
        // Optional: auto-submit
        setTimeout(() => form.submit(), 500);
    }
});
</script>

</body>
</html>