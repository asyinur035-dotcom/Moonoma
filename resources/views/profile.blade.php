@extends('layouts.app')

@section('content')

<div class="header">
    <h2>Profile</h2>
    <a href="{{ route('profile.edit') }}" class="edit-btn">Edit Profile</a>
</div>

<style>
/* HEADER */
.header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:30px;
    padding-bottom:12px;
    border-bottom:1px solid #3E5641;
}

.header h2{
    font-size:26px;
    font-weight:500;
}

.edit-btn{
    background:#3E5641;
    padding:8px 22px;
    border-radius:20px;
    color:#fff;
    text-decoration:none;
    font-size:13px;
}

/* LAYOUT */
.profile-container{
    display:flex;
    gap:30px;
}

.profile-left{
    width:280px;
    display:flex;
    flex-direction:column;
    gap:20px;
}

.profile-right{
    flex:1;
    border:1px solid #3E5641;
    border-radius:16px;
    padding:30px;
    background:#0d0f0d;
}

/* CARD */
.card{
    border:1px solid #3E5641;
    border-radius:16px;
    padding:22px;
    text-align:center;
    background:#0d0f0d;
}

/* AVATAR */
.avatar{
    width:150px;
    height:150px;
    border-radius:50%;
    overflow:hidden;
    background:#e0e0e0;
    margin:0 auto;
    border:3px solid #3E5641;
}

.avatar img{
    width:100%;
    height:100%;
    object-fit:cover;
}

.name{
    font-size:18px;
    margin-top:12px;
}

.role{
    font-size:12px;
    color:#822659;
}

/* DETAILS */
.details{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:20px 25px;
    margin-top:10px;
    align-items:start;
}

.label{
    font-size:11px;
    color:#6f8a75;
    margin-bottom:6px;
}

.value{
    font-size:13px;
}

.empty{
    font-size:12px;
    color:#6f8a75;
    font-style:italic;
}

/* CV FIX */
.cv-wrapper{
    grid-column:span 2;
    margin-top:5px;
}

.cv-box{
    width:100%;
    border:1px solid #3E5641;
    border-radius:12px;
    padding:20px;
    text-align:center;
    box-sizing:border-box;
}

/* CV BUTTON */
.cv-upload{
    display:inline-block;
    padding:10px 22px;
    border-radius:20px;
    border:1px solid #3E5641;
    color:#3E5641;
    cursor:pointer;
    font-size:13px;
    transition:0.2s;
}

.cv-upload:hover{
    background:#3E5641;
    color:#fff;
}

.cv-upload input{
    display:none;
}

.cv-name{
    margin-top:12px;
    font-size:12px;
    color:#6f8a75;
}
</style>

<div class="profile-container">

    <!-- LEFT -->
    <div class="profile-left">

        <div class="card">
            <div class="avatar">
                <img src="{{ session('avatar', 'https://cdn-icons-png.flaticon.com/512/847/847969.png') }}">
            </div>
        </div>

        <div class="card">
            <div class="name">
                {{ session('name', 'Your Name') }}
            </div>
            <div class="role">
                {{ session('role', 'Your Role') }}
            </div>
        </div>

    </div>

    <!-- RIGHT -->
    <div class="profile-right">

        <h3>Details</h3>

        <div class="details">

            <div>
                <div class="label">Username</div>
                <div class="value">
                    {{ strtolower(str_replace(' ', '', session('name', 'username'))) }}
                </div>
            </div>

            <div>
                <div class="label">Name</div>
                <div class="value">
                    {{ session('name', 'Your Name') }}
                </div>
            </div>

            <div>
                <div class="label">Role</div>
                <div class="value">
                    {{ session('role', 'Your Role') }}
                </div>
            </div>

            <div>
                <div class="label">Skill teach</div>
                <div class="value">
                    <span class="empty">No skills yet</span>
                </div>
            </div>

            <div>
                <div class="label">City or region</div>
                <div class="value">-</div>
            </div>

            <div>
                <div class="label">Skill learn</div>
                <div class="value">
                    <span class="empty">No skills yet</span>
                </div>
            </div>

            <!-- CV FIX -->
            <div class="cv-wrapper">
                <div class="label">CV</div>

                <div class="cv-box">
                    <label class="cv-upload">
                        <input type="file" accept="application/pdf" onchange="previewCV(event)">
                        Upload CV (PDF)
                    </label>

                    <p id="cvName" class="cv-name">No file selected</p>
                </div>
            </div>

        </div>

    </div>

</div>

<script>
function previewCV(e){
    const file = e.target.files[0];
    if(!file) return;

    if(file.type !== 'application/pdf'){
        alert('Hanya file PDF');
        e.target.value = '';
        return;
    }

    document.getElementById('cvName').innerText = file.name;
}
</script>

@endsection