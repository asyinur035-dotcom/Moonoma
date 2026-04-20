@extends('layouts.app')

@section('content')

<!-- HEADER -->
<div class="header">
    <h2>Find a study room</h2>

    <div class="header-right">
        <a href="{{ route('search') }}" class="header-btn">Search</a>
        <a href="{{ route('room.create') }}" class="header-btn primary">+ Create Room</a>
    </div>
</div>

<div class="header-line"></div>

<style>
.rooms-content{
    display:flex;
    flex-direction:column;
    gap:20px;
}

/* HEADER */
.header{
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.header h2{
    font-size:22px;
    font-weight:500;
}

.header-right{
    display:flex;
    gap:10px;
}

.header-btn{
    border:1px solid #3E5641;
    padding:6px 16px;
    border-radius:8px;
    font-size:12px;
    color:#fff;
    text-decoration:none;
}

.header-btn.primary{
    background:#3E5641;
}

.header-line{
    height:1px;
    background:#3E5641;
    margin:20px 0;
}

/* FILTER */
.filters{
    display:flex;
    gap:10px;
}

.filter-btn{
    border:1px solid #3E5641;
    padding:6px 14px;
    border-radius:20px;
    font-size:12px;
    cursor:pointer;
}

.filter-btn.active{
    background:#3E5641;
}

/* GRID */
.room-grid{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:20px;
}

/* CARD */
.room-card{
    border:1px solid #3E5641;
    border-radius:16px;
    padding:18px;
}

/* BADGE */
.badge{
    border:1px solid #3E5641;
    padding:3px 10px;
    border-radius:10px;
    font-size:10px;
    display:inline-block;
    margin-bottom:10px;
}

/* STATUS */
.status{
    float:right;
    font-size:10px;
    padding:3px 8px;
    border-radius:10px;
    border:1px solid #3E5641;
}

/* TEXT */
.room-title{font-size:15px;}

.room-sub{
    font-size:12px;
    color:#6f8a75;
    margin:10px 0;
}

/* FOOTER */
.room-footer{
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.member{
    font-size:12px;
    color:#6f8a75;
}

.join-btn{
    background:#3E5641;
    border:none;
    padding:6px 16px;
    border-radius:20px;
    color:#fff;
    cursor:pointer;
}

/* EMPTY */
.empty{
    text-align:center;
    color:#6f8a75;
    margin-top:80px;
}
</style>

<div class="rooms-content">

    <!-- FILTER -->
    <div class="filters">
        <div class="filter-btn active" onclick="filterRoom('all', this)">All</div>
        <div class="filter-btn" onclick="filterRoom('coding', this)">Coding</div>
        <div class="filter-btn" onclick="filterRoom('design', this)">Design</div>
        <div class="filter-btn" onclick="filterRoom('data', this)">etc.</div>
    </div>

    <!-- ROOMS -->
    <div id="roomContainer" class="room-grid"></div>

    <!-- EMPTY -->
    <div id="emptyState" class="empty" style="display:none;">
        No rooms available
    </div>

</div>

<script>
let allRooms = JSON.parse(localStorage.getItem('rooms')) || [];
let currentFilter = 'all';

function renderRooms(){
    let container = document.getElementById('roomContainer');
    let empty = document.getElementById('emptyState');

    let joined = JSON.parse(localStorage.getItem('joinedRooms')) || [];
    let pending = JSON.parse(localStorage.getItem('pendingRooms')) || [];

    container.innerHTML = '';

    let filtered = allRooms.filter(r =>
        currentFilter === 'all' || r.type === currentFilter
    );

    if(filtered.length === 0){
        empty.style.display = 'block';
        container.style.display = 'none';
        return;
    }

    empty.style.display = 'none';
    container.style.display = 'grid';

    filtered.forEach(r=>{

        let label = "";
        let btnText = r.status === 'Private' ? 'Request' : 'Join';

        if(joined.includes(r.name)){
            label = " (Joined)";
            btnText = "Joined";
        } 
        else if(pending.includes(r.name)){
            label = " (Pending)";
            btnText = "Pending";
        }

        container.innerHTML += `
            <div class="room-card">
                <div>
                    <span class="badge">${r.type}</span>
                    <span class="status">${r.status}</span>
                </div>

                <div class="room-title">${r.name}${label}</div>
                <div class="room-sub">${r.desc}</div>

                <div class="room-footer">
                    <div class="member">${r.member} Member</div>
                    <button class="join-btn" onclick="joinRoom('${r.name}', '${r.status}')">
                        ${btnText}
                    </button>
                </div>
            </div>
        `;
    });
}

/* FILTER */
function filterRoom(type, el){
    currentFilter = type;

    document.querySelectorAll('.filter-btn').forEach(b=>b.classList.remove('active'));
    el.classList.add('active');

    renderRooms();
}

/* JOIN SYSTEM */
function joinRoom(name, status){

    let joined = JSON.parse(localStorage.getItem('joinedRooms')) || [];
    let pending = JSON.parse(localStorage.getItem('pendingRooms')) || [];

    if(status === 'Private'){
        if(!pending.includes(name)){
            pending.push(name);
            localStorage.setItem('pendingRooms', JSON.stringify(pending));
        }

        alert("Waiting approval...");
        renderRooms();
        return;
    }

    if(!joined.includes(name)){
        joined.push(name);
        localStorage.setItem('joinedRooms', JSON.stringify(joined));
    }

    window.location.href = '/chat/' + encodeURIComponent(name);
}

/* INIT */
renderRooms();
</script>

@endsection