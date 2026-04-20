@extends('layouts.app')

@section('content')

<div class="edit-box">
    <div class="edit-grid">

        <div class="profile-left">
            <div class="avatar-preview">
                <img id="preview" src="https://cdn-icons-png.flaticon.com/512/847/847969.png">
            </div>

            <label class="upload-file">
                Change Photo
                <input type="file" hidden onchange="previewImage(event)">
            </label>
        </div>

        <div class="form-section">

            <div class="form-group">
                <label class="label">Name</label>
                <input 
                    class="input" 
                    id="nameInput" 
                    placeholder="Enter name"
                    value="{{ session('name') }}"
                >
            </div>

            <div class="form-group">
                <label class="label">Role</label>

                <select class="input" id="roleSelect" onchange="handleRoleSelect()">
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
                    placeholder="Type your role here..."
                    style="display:none; margin-top:10px;"
                >
            </div>

            <div class="btn-group">
                <a href="{{ route('home') }}" class="cancel-btn">Cancel</a>
                <button onclick="saveProfile()" class="save-btn">Save</button>
            </div>

        </div>

    </div>
</div>

<style>
.edit-box{
    padding:30px;
    background:#0f1110;
    max-width:880px;
    border-radius:16px;
}

.edit-grid{
    display:grid;
    grid-template-columns:200px 1fr;
    gap:40px;
}

.profile-left{
    display:flex;
    flex-direction:column;
    align-items:center;
}

.avatar-preview{
    width:140px;
    height:140px;
    border-radius:50%;
    overflow:hidden;
    border:3px solid #3E5641;
    background:#e0e0e0;
}

.avatar-preview img{
    width:100%;
    height:100%;
    object-fit:cover;
}

.upload-file{
    margin-top:14px;
    padding:6px 14px;
    border:1px solid #3E5641;
    border-radius:10px;
    font-size:12px;
    color:#3E5641;
    cursor:pointer;
}

.upload-file:hover{
    background:#3E5641;
    color:#fff;
}

.form-section{ max-width:400px; }

.form-group{ margin-bottom:18px; }

.label{
    font-size:13px;
    color:#aaa;
    margin-bottom:6px;
    display:block;
}

.input{
    width:100%;
    padding:10px;
    border-radius:10px;
    border:1px solid #3E5641;
    background:#0d0f0d;
    color:#fff;
}

.btn-group{
    display:flex;
    justify-content:flex-end;
    gap:10px;
    margin-top:25px;
}

.save-btn{
    background:#3E5641;
    border:none;
    padding:8px 18px;
    border-radius:20px;
    color:#fff;
    cursor:pointer;
}

.cancel-btn{
    border:1px solid #555;
    background:transparent;
    color:#aaa;
    padding:8px 18px;
    border-radius:20px;
    text-decoration:none;
}
</style>

<script>
function previewImage(e){
    const file = e.target.files[0];
    if(!file) return;

    const reader = new FileReader();
    reader.onload = () => {
        document.getElementById('preview').src = reader.result;
    };
    reader.readAsDataURL(file);
}

function handleRoleSelect(){
    const select = document.getElementById('roleSelect').value;
    const custom = document.getElementById('customRole');

    if(select === 'other'){
        custom.style.display = 'block';
        custom.focus();
    } else {
        custom.style.display = 'none';
        custom.value = '';
    }
}

function saveProfile(){
    let name = document.getElementById('nameInput').value.trim();
    let select = document.getElementById('roleSelect').value;
    let custom = document.getElementById('customRole').value.trim();

    let role = (select === 'other') ? custom : select;

    if(!name){
        alert('Name wajib diisi');
        return;
    }

    if(!role){
        alert('Role wajib diisi');
        return;
    }

    fetch("{{ route('profile.save') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({ name, role })
    })
    .then(() => {
        window.location.href = "{{ route('home') }}";
    });
}
</script>

@endsection