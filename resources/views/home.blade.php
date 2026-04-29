@extends('layouts.app')

@section('content')

<!-- HEADER -->
<div class="header">
    <h2>Home</h2>

    <div class="header-actions">
        <a href="{{ route('search') }}" class="btn-outline">Search</a>
        <a href="{{ route('home.edit') }}" class="btn-primary">Edit</a>
    </div>
</div>

<style>

/* HEADER FIX (RATA & TIDAK TURUN) */
.header{
    display:flex;
    justify-content:space-between;
    align-items:center;

    padding:16px 20px 14px; /* 🔥 fix posisi */
    border-bottom:1px solid #3E5641;
    margin-bottom:25px;
}

.header h2{
    font-size:24px;
    font-weight:500;
    margin:0;              /* 🔥 penting */
    line-height:1.2;
}

/* BUTTON HEADER */
.header-actions{
    display:flex;
    gap:10px;
}

.btn-outline{
    border:1px solid #3E5641;
    color:#fff;
    padding:8px 18px;
    border-radius:20px;
    text-decoration:none;
    font-size:13px;
}

.btn-outline:hover{
    background:#3E5641;
}

.btn-primary{
    background:#3E5641;
    color:#fff;
    padding:8px 18px;
    border-radius:20px;
    text-decoration:none;
    font-size:13px;
}

/* GRID */
.home-grid{
    display:grid;
    grid-template-columns:280px 1fr;
    gap:30px;
    padding:0 20px;
}

.left,.right{
    display:flex;
    flex-direction:column;
    gap:20px;
}

/* CARD */
.card{
    border:1px solid #3E5641;
    border-radius:16px;
    padding:22px;
    background:#0d0f0d;
}

/* AVATAR */
.avatar{
    width:130px;
    height:130px;
    border-radius:50%;
    overflow:hidden;
    background:#e0e0e0;
    border:3px solid #3E5641;
    margin:auto;
}

.avatar img{
    width:100%;
    height:100%;
    object-fit:cover;
}

/* NAME */
.name{
    font-size:18px;
    text-align:center;
}

.role{
    font-size:13px;
    text-align:center;
    color:#822659;
}

/* STATS */
.stats-card{
    display:flex;
    justify-content:space-between;
    text-align:center;
}

.stat span{
    font-size:12px;
    color:#6f8a75;
}

.stat b{
    display:block;
    margin-top:6px;
    font-size:20px;
}

/* SECTION */
.section-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.card-title{
    font-size:13px;
    color:#aaa;
}

/* ADD BUTTON */
.add-btn{
    background:#3E5641;
    color:#fff;
    padding:6px 14px;
    border-radius:10px;
    border:none;
    cursor:pointer;
    font-size:12px;
}

.add-btn:hover{
    opacity:0.85;
}

/* TAG */
.tags{
    margin-top:10px;
}

.tag{
    background:#000;
    border:1px solid #3E5641;
    padding:6px 12px;
    border-radius:10px;
    font-size:12px;
    margin:4px;
    display:inline-block;
}

/* MODAL */
.modal{
    position:fixed;
    top:0; left:0;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.5);
    display:none;
    justify-content:center;
    align-items:center;
}

.modal-box{
    background:#000;
    border:2px solid #3E5641;
    padding:25px;
    border-radius:14px;
    width:320px;
    color:#fff;
}

.modal-box select,
.modal-box input{
    width:100%;
    margin-top:10px;
    padding:10px;
    border-radius:8px;
    border:1px solid #3E5641;
    background:#0d0f0d;
    color:#fff;
}

.modal-actions{
    margin-top:18px;
    display:flex;
    justify-content:flex-end;
    gap:10px;
}

.save-btn{
    background:#3E5641;
    color:#fff;
    padding:8px 18px;
    border:none;
    border-radius:8px;
}

.cancel-btn{
    background:transparent;
    border:1px solid #3E5641;
    color:#3E5641;
    padding:8px 18px;
    border-radius:8px;
}

</style>

<div class="home-grid">

    <!-- LEFT -->
    <div class="left">

        <div class="card">
            <div class="avatar">
                <img src="{{ session('avatar','https://cdn-icons-png.flaticon.com/512/847/847969.png') }}">
            </div>
        </div>

        <div class="card">
            <div class="name">{{ session('name','Your Name') }}</div>
            <div class="role">{{ session('role','Your Role') }}</div>
        </div>

    </div>

    <!-- RIGHT -->
    <div class="right">

        <div class="card stats-card">
            <div class="stat">
                <span>Room joined</span>
                <b>{{ $roomsJoined ?? 0 }}</b>
            </div>
            <div class="stat">
                <span>Skill teach</span>
                <b id="teachCount">0</b>
            </div>
            <div class="stat">
                <span>Skill learn</span>
                <b id="learnCount">0</b>
            </div>
        </div>

        <div class="card">
            <div class="section-header">
                <div class="card-title">Skill teach</div>
                <button class="add-btn" onclick="openModal('teach')">+ Add</button>
            </div>
            <div id="teachBox" class="tags"></div>
        </div>

        <div class="card">
            <div class="section-header">
                <div class="card-title">Skill learn</div>
                <button class="add-btn" onclick="openModal('learn')">+ Add</button>
            </div>
            <div id="learnBox" class="tags"></div>
        </div>

    </div>

</div>

<!-- MODAL -->
<div id="skillModal" class="modal">
    <div class="modal-box">

        <h4>Add Skill</h4>

        <select id="skillSelect">
            <option value="">Select skill</option>
            <option>Coding</option>
            <option>Web Development</option>
            <option>UI/UX Design</option>
            <option>Graphic Design</option>
            <option>Video Editing</option>
            <option>Computer Networking</option>
            <option>Fixing Network Problems</option>
            <option>IT Support</option>
            <option value="other">Other</option>
        </select>

        <input id="customSkill" placeholder="Type your skill..." style="display:none;">

        <div class="modal-actions">
            <button class="cancel-btn" onclick="closeModal()">Cancel</button>
            <button class="save-btn" onclick="saveSkill()">Save</button>
        </div>

    </div>
</div>

<script>
let currentType = '';

function openModal(type){
    currentType = type;
    document.getElementById('skillModal').style.display = 'flex';
}

function closeModal(){
    document.getElementById('skillModal').style.display = 'none';
}

document.getElementById('skillSelect').addEventListener('change', function(){
    let custom = document.getElementById('customSkill');

    if(this.value === 'other'){
        custom.style.display = 'block';
    }else{
        custom.style.display = 'none';
        custom.value = '';
    }
});

function saveSkill(){
    let select = document.getElementById('skillSelect').value;
    let custom = document.getElementById('customSkill').value;

    let skill = (select === 'other') ? custom : select;

    if(!skill){
        alert('Isi skill dulu');
        return;
    }

    let box = currentType === 'teach'
        ? document.getElementById('teachBox')
        : document.getElementById('learnBox');

    let tag = document.createElement('span');
    tag.className = 'tag';
    tag.innerText = skill;

    box.appendChild(tag);

    updateCount();
    closeModal();
}

function updateCount(){
    document.getElementById('teachCount').innerText =
        document.getElementById('teachBox').children.length;

    document.getElementById('learnCount').innerText =
        document.getElementById('learnBox').children.length;
}
</script>

@endsection