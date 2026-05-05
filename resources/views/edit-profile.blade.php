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

@media (max-width: 768px) {
    .profile-container {
        flex-direction: column;
    }
    .profile-left {
        width: 100%;
    }
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
    background:#3E5641;
    border:none;
    padding:12px 30px;
    border-radius:25px;
    color:#fff;
    cursor:pointer;
}

.delete-account-btn{
    background:transparent;
    border:1px solid #e87c7c;
    padding:12px 25px;
    border-radius:25px;
    color:#e87c7c;
    cursor:pointer;
    font-size:13px;
    transition:0.3s;
}
.delete-account-btn:hover{
    background:rgba(232, 124, 124, 0.1);
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

        @if(session('email') !== 'moonomaproject@gmail.com')
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
                placeholder="Custom role (optional)..."
                style="margin-top:10px; display:none;"
            >
        </div>
        @else
        <div class="card">
            <input class="input" id="nameInput" value="{{ session('name') }}" placeholder="Name">
            <input type="hidden" id="roleSelect" value="admin">
            <input type="hidden" id="customRole" value="">
            <div style="margin-top:10px; color:#c9a227; font-weight:700; font-size:14px;">ADMIN ACCOUNT</div>
        </div>
        @endif

    </div>

    <!-- RIGHT -->
    <div class="profile-right">

        <h3>Details</h3>

        <div class="details">

            <div>
                <div class="label">Username</div>
                <input class="input" id="usernameInput" value="{{ session('username', strtolower(str_replace(' ', '', session('name', 'username')))) }}">
            </div>

            <div>
                <div class="label">Name</div>
                <input class="input" id="nameDisplayInput" value="{{ session('name') }}">
            </div>

            @if(session('email') !== 'moonomaproject@gmail.com')
            <div>
                <div class="label">Role</div>
                <input class="input" id="roleDisplayInput" value="{{ session('role') }}" readonly>
            </div>
            @endif

            <div>
                <div class="label">Skill teach</div>
                <input class="input" id="skillTeachInput" placeholder="Example: Web, UI/UX" value="{{ $profile && is_array($profile['skill_teach']) ? implode(', ', $profile['skill_teach']) : '' }}">
            </div>

            <div>
                <div class="label">City</div>
                <input class="input" id="cityInput" placeholder="City" value="{{ $profile['city'] ?? '' }}">
            </div>

            <div>
                <div class="label">Skill learn</div>
                <input class="input" id="skillLearnInput" placeholder="Example: Python, React" value="{{ $profile && is_array($profile['skill_learn']) ? implode(', ', $profile['skill_learn']) : '' }}">
            </div>

            <div>
                <div class="label">Availability</div>
                <input class="input" id="availabilityInput" placeholder="Available" value="{{ $profile['availability'] ?? '' }}">
            </div>

            <div class="cv-wrapper">
                <div class="label">CV</div>

                <div class="cv-box">
                    <div style="display:flex; flex-direction:column; align-items:center; gap:10px;">
                        <label class="cv-upload">
                            <input type="file" id="cvInput" accept="application/pdf" onchange="previewCV(event)">
                            Upload CV (PDF)
                        </label>

                        <div id="cvInfo" style="display: {{ ($profile['cv_path'] ?? null) ? 'flex' : 'none' }}; align-items:center; gap:10px;">
                            <p id="cvName" class="cv-name" style="margin:0;">{{ ($profile['cv_path'] ?? null) ? basename($profile['cv_path']) : 'No file selected' }}</p>
                            <button type="button" id="deleteCvBtn" onclick="deleteCV()" style="background:none; border:none; color:#8b3a3a; font-size:12px; cursor:pointer; text-decoration:underline;">Hapus CV</button>
                        </div>
                        <p id="cvStatus" class="cv-name" style="display: {{ ($profile['cv_path'] ?? null) ? 'none' : 'block' }};">No file selected</p>
                    </div>
                </div>
            </div>

        </div>

        <div style="display:flex; justify-content:space-between; align-items:center; margin-top:35px;">
            <button type="button" class="delete-account-btn" onclick="deleteAccount()">Delete Account</button>
            <button type="submit" class="save-btn">Save</button>
        </div>

    </div>

</div>

</form>

<script>
let isDeletingCV = false;

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
    
    if (file) {
        document.getElementById('cvInfo').style.display = 'flex';
        document.getElementById('cvStatus').style.display = 'none';
        document.getElementById('cvName').innerText = file.name;
        isDeletingCV = false;
    }
}

function deleteCV() {
    if (confirm('Yakin ingin menghapus CV ini?')) {
        document.getElementById('cvInput').value = '';
        document.getElementById('cvInfo').style.display = 'none';
        document.getElementById('cvStatus').style.display = 'block';
        document.getElementById('cvStatus').innerText = 'CV akan dihapus setelah disimpan';
        isDeletingCV = true;
    }
}

function deleteAccount() {
    if (confirm('Apakah Anda yakin ingin menghapus akun ini secara permanen? Semua data dan pesan Anda akan hilang. Tindakan ini tidak dapat dibatalkan.')) {
        fetch("{{ route('profile.delete-account') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            }
        }).then(res => res.json())
        .then(data => {
            if(data.success) {
                alert('Akun berhasil dihapus.');
                window.location.href = "{{ route('login') }}";
            } else {
                alert(data.message || 'Gagal menghapus akun.');
            }
        }).catch(err => {
            console.error(err);
            alert('Terjadi kesalahan saat menghapus akun.');
        });
    }
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
    let avatar = document.getElementById('preview').src;
    let username = document.getElementById('usernameInput').value;
    let skill_teach = document.getElementById('skillTeachInput').value;
    let skill_learn = document.getElementById('skillLearnInput').value;
    let city = document.getElementById('cityInput').value;
    let availability = document.getElementById('availabilityInput').value;
    let cvFile = document.getElementById('cvInput').files[0];

    let role = (select === 'other') ? (custom.trim() || 'Other') : select;

    let formData = new FormData();
    formData.append('name', name);
    formData.append('role', role);
    formData.append('avatar', avatar);
    formData.append('username', username);
    formData.append('skill_teach', skill_teach);
    formData.append('skill_learn', skill_learn);
    formData.append('city', city);
    formData.append('availability', availability);
    if(cvFile) {
        formData.append('cv', cvFile);
    }
    if(isDeletingCV) {
        formData.append('delete_cv', '1');
    }

    fetch("{{ route('profile.save') }}", {
        method:"POST",
        headers:{
            "X-CSRF-TOKEN":"{{ csrf_token() }}"
        },
        body: formData
    }).then(res => res.json())
    .then(data => {
        if(data.success) {
            window.location.href="{{ route('profile') }}";
        } else {
            alert(data.message || 'Gagal menyimpan profil');
        }
    }).catch(err => {
        console.error(err);
        alert('Terjadi kesalahan');
    });
}
</script>

@endsection