<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Verification</title>

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
    line-height:1.5;
}

.otp-container{
    display:flex;
    gap:10px;
    justify-content:center;
    margin-bottom:25px;
}

.otp-input{
    width:45px;
    height:45px;
    border-radius:10px;
    border:1px solid #3E5641;
    background:transparent;
    text-align:center;
    font-size:16px;
    color:#fff;
}

.otp-input:focus{
    outline:none;
    box-shadow:0 0 0 2px rgba(62,86,65,0.2);
}

.timer{
    text-align:center;
    font-size:12px;
    color:#ccc;
    margin-bottom:20px;
}

.resend{
    color:#3E5641;
    cursor:pointer;
}

.btn{
    width:100%;
    height:36px;
    border:none;
    border-radius:18px;
    background:#3E5641;
    color:#fff;
    cursor:pointer;
    font-size:12px;
}
</style>
</head>

<body>

<div class="wrapper">

<div class="logo"></div>

<div class="title">Verification</div>
<div class="subtitle">
We’ve sent a verification code to your email. Enter it below to continue.
</div>

<div class="otp-container">
    <input class="otp-input" maxlength="1">
    <input class="otp-input" maxlength="1">
    <input class="otp-input" maxlength="1">
    <input class="otp-input" maxlength="1">
    <input class="otp-input" maxlength="1">
    <input class="otp-input" maxlength="1">
</div>

<div class="timer">
    You can resend the code in <span id="countdown">00:59</span>
</div>

<button class="btn">Send code</button>

</div>

<script>
const inputs = document.querySelectorAll('.otp-input');

// auto pindah + angka only
inputs.forEach((input, index) => {

    input.addEventListener('input', () => {
        input.value = input.value.replace(/[^0-9]/g,'');

        if(input.value && index < inputs.length - 1){
            inputs[index + 1].focus();
        }
    });

    input.addEventListener('keydown', (e) => {
        if(e.key === "Backspace" && !input.value && index > 0){
            inputs[index - 1].focus();
        }
    });
});


// countdown timer
let time = 59;
const countdown = document.getElementById('countdown');

const timer = setInterval(() => {
    let seconds = time < 10 ? '0'+time : time;
    countdown.innerText = `00:${seconds}`;
    time--;

    if(time < 0){
        clearInterval(timer);
        countdown.innerHTML = `<span class="resend">Resend code</span>`;
    }
}, 1000);
</script>

</body>
</html>