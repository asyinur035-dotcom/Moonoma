@extends('layouts.app')

@section('content')

<div class="header">
    <h2>Join Room</h2>
</div>

<div class="header-line"></div>

<style>
.invite-container{
    max-width:500px;
    margin:0 auto;
    display:flex;
    flex-direction:column;
    gap:20px;
}

/* INPUT */
.invite-input{
    border:1px solid #3E5641;
    background:transparent;
    padding:12px 16px;
    border-radius:20px;
    color:#fff;
}

/* BUTTON */
.join-btn{
    background:#3E5641;
    border:none;
    padding:10px;
    border-radius:20px;
    color:#fff;
    cursor:pointer;
}

/* BOX */
.invite-box{
    border:1px solid #3E5641;
    border-radius:20px;
    padding:10px 14px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.copy-btn{
    background:#3E5641;
    border:none;
    padding:4px 12px;
    border-radius:20px;
    color:#fff;
    cursor:pointer;
}

.section-title{
    font-size:12px;
    color:#6f8a75;
}
</style>

<div class="invite-container">

    <!-- JOIN PRIVATE -->
    <div class="section-title">Join with code</div>

    <input id="inviteCode" class="invite-input" placeholder="Enter invite code...">

    <button class="join-btn" onclick="joinByCode()">Join Room</button>

    <!-- SHARE INFO -->
    <div class="section-title">Example invite link</div>

    <div class="invite-box">
        <span id="inviteLink">/chat/room-name</span>
        <button class="copy-btn" onclick="copyLink()">Copy</button>
    </div>

</div>

<script>
let rooms = JSON.parse(localStorage.getItem('rooms')) || [];
let joined = JSON.parse(localStorage.getItem('joinedRooms')) || [];

/* 🔥 COPY LINK (STATIC EXAMPLE) */
function copyLink(){
    let sample = window.location.origin + "/chat/sample-room";
    navigator.clipboard.writeText(sample);
    alert("Copied!");
}

/* 🔥 JOIN BY CODE */
function joinByCode(){
    let code = document.getElementById('inviteCode').value.trim();

    if(!code){
        alert("Enter code first");
        return;
    }

    let room = rooms.find(r => generateCode(r.name) === code);

    if(!room){
        alert("Invalid code");
        return;
    }

    if(!joined.includes(room.name)){
        joined.push(room.name);
        localStorage.setItem('joinedRooms', JSON.stringify(joined));
    }

    window.location.href = "/chat/" + encodeURIComponent(room.name);
}

/* 🔥 CODE GENERATOR (CONSISTENT) */
function generateCode(name){
    return name.slice(0,3).toUpperCase() + name.length + "X";
}
</script>

@endsection