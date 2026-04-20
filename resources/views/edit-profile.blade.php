@extends('layouts.app')

@section('content')

<div class="header">
    <h2>Edit Profile</h2>
</div>

<style>
.header{
    margin-bottom:30px;
    padding-bottom:12px;
    border-bottom:1px solid #3E5641;
}

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

.card{
    border:1px solid #3E5641;
    border-radius:16px;
    padding:22px;
    text-align:center;
    background:#0d0f0d;
}

.avatar{
    width:150px;
    height:150px;
    border-radius:50%;
    overflow:hidden;
    background:#e0e0e0;
    margin:0 auto;
    border:3px solid #3E5641;
    position:relative;
}

.avatar img{
    width:100%;
    height:100%;
    object-fit:cover;
}

.edit-dot{
    width:28px;
    height:28px;
    border-radius:50%;
    background:#3E5641;
    position:absolute;
    bottom:8px;
    right:8px;
    display:flex;
    align-items:center;
    justify-content:center;
    color:#fff;
    cursor:pointer;
}

/* RIGHT */
.profile-right{
    flex:1;
    border:1px solid #3E5641;
    border-radius:16px;
    padding:30px;
    background:#0d0f0d;
}

.details{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:20px 25px;
    margin-top:10px;
}

.label{
    font-size:11px;
    color:#6f8a75;
    margin-bottom:6px;
}

.input{
    width:100%;
    border:1px solid #3E5641;
    background:transparent;
    padding:10px;
    border-radius:10px;
    color:#fff;
}

/* SELECT STYLE */
select.input{
    appearance:none;
    background:#0d0f0d;
    color:#fff;
    cursor:pointer;
}

select.input option{
    background:#0d0f0d;
    color:#fff;
}

/* CV */
.cv-wrapper{
    grid-column:span 2;
}

.cv-box{
    border:1px solid #3E5641;
    border-radius:12px;
    padding:20px;
    text-align:center;
}

.cv-upload{
    display:inline-block;
    padding:10px 22px;
    border-radius:20px;
    border:1px solid #3E5641;
    color:#3E5641;
    cursor:pointer;
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

/* BUTTON */
.save-btn{
    margin-top:25px;
    background:#3E5641;
    border:none;
    padding:12px 30px;
    border-radius:25px;
    color:#fff;
    float:right;
    cursor:pointer;
}
</style>

<form onsubmit="saveProfile(event)">

<div class="profile-container">

    <!-- LEFT -->
    <div class="profile-left">

        <div class="card">
            <div class="avatar">
                <img id="preview" src="{{ session('avatar','https://cdn-icons-png.flaticon.com/512/847/847969.png') }}">
                <label class="edit-dot">
                    ✎
                    <input type="file" hidden onchange="previewImage(event)">
                </label>
            </div>
        </div>

        <div class="card">
            <input class="input" id="nameInput" value="{{ session('name') }}" placeholder="Name">

            <!-- ROLE FIX -->
            <select class="input" id="roleSelect" onchange="handleRole()" style="margin-top:10px;">
                <option value="">Select role</option>
                <option>Designer</option>
                <option>UI/UX</option>
                <option>Frontend</option>
                <option>Backend</option>
                <option>Mobile Dev</option>
                <option value="other">Other</option>
            </select>

            <input 
                class="input" 
                id="customRole" 
                placeholder="Type your role..."
                style="margin-top:10px; display:none;"
            >
        </div>

    </div>

    <!-- RIGHT -->
    <div class="profile-right">

        <h3>Details</h3>

        <div class="details">

            <div>
                <div class="label">Username</div>
                <input class="input" value="{{ strtolower(str_replace(' ', '', session('name','username'))) }}">
            </div>

            <div>
                <div class="label">Name</div>
                <input class="input" value="{{ session('name') }}">
            </div>

            <div>
                <div class="label">Role</div>
                <input class="input" value="{{ session('role') }}">
            </div>

            <div>
                <div class="label">Skill teach</div>
                <input class="input" placeholder="Example: Web, UI/UX">
            </div>

            <div>
                <div class="label">City</div>
                <input class="input" placeholder="City">
            </div>

            <div>
                <div class="label">Availability</div>
                <input class="input" placeholder="Available">
            </div>

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

        <button class="save-btn">Save</button>

    </div>

</div>

</form>

<script>
function previewImage(e){
    const file = e.target.files[0];
    if(!file) return;
    const reader = new FileReader();
    reader.onload = () => preview.src = reader.result;
    reader.readAsDataURL(file);
}

function previewCV(e){
    const file = e.target.files[0];
    if(file && file.type !== 'application/pdf'){
        alert('Hanya PDF');
        e.target.value='';
        return;
    }
    document.getElementById('cvName').innerText = file ? file.name : 'No file selected';
}

function handleRole(){
    let val = document.getElementById('roleSelect').value;
    let custom = document.getElementById('customRole');

    if(val === 'other'){
        custom.style.display = 'block';
        custom.focus();
    }else{
        custom.style.display = 'none';
        custom.value = '';
    }
}

/* AUTO LOAD SESSION ROLE */
window.onload = () => {
    let saved = "{{ session('role') }}";
    let select = document.getElementById('roleSelect');

    for(let opt of select.options){
        if(opt.value === saved){
            select.value = saved;
        }
    }

    if(saved && !Array.from(select.options).some(o => o.value === saved)){
        select.value = 'other';
        document.getElementById('customRole').style.display = 'block';
        document.getElementById('customRole').value = saved;
    }
};

function saveProfile(e){
    e.preventDefault();

    let name = document.getElementById('nameInput').value;
    let select = document.getElementById('roleSelect').value;
    let custom = document.getElementById('customRole').value;

    let role = (select === 'other') ? custom : select;

    fetch("{{ route('profile.save') }}", {
        method:"POST",
        headers:{
            "Content-Type":"application/json",
            "X-CSRF-TOKEN":"{{ csrf_token() }}"
        },
        body:JSON.stringify({name, role})
    }).then(()=>{
        window.location.href="{{ route('profile') }}";
    });
}
</script>

@endsection