@extends('layouts.app')

@section('content')

<style>
.header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:16px 20px;
    border-bottom:1px solid #3E5641;
    margin-bottom:20px;
}

.header-left h2{
    font-size:22px;
    margin:0;
}

.header-sub{
    font-size:12px;
    color:#6f8a75;
}

.header-actions{
    display:flex;
    gap:8px;
}

.tab-btn{
    border:1px solid #3E5641;
    padding:6px 14px;
    border-radius:20px;
    background:transparent;
    color:#fff;
    cursor:pointer;
    font-size:12px;
}

.tab-btn.active{
    background:#3E5641;
}

.container{
    padding:0 20px;
}
</style>

<!-- HEADER -->
<div class="header">

    <div class="header-left">
        <h2>{{ $room }}</h2>
        <div class="header-sub">Room Chat & Workspace</div>
    </div>

    <div class="header-actions">
        <button onclick="switchTab(event,'chat')" class="tab-btn active">Chat</button>
        <button onclick="switchTab(event,'workspace')" class="tab-btn">Workspace</button>
    </div>

</div>

<!-- CONTENT -->
<div class="container">

    <!-- CHAT -->
    <div id="chatTab">
        @include('chat-content')
    </div>

    <!-- WORKSPACE -->
    <div id="workspaceTab" style="display:none;">
        @include('workspace')
    </div>

</div>

<script>
/* ROOM DARI ROUTE */
let currentRoom = @json($room);

/* SWITCH TAB */
function switchTab(e, tab){
    document.getElementById('chatTab').style.display = tab === 'chat' ? 'block' : 'none';
    document.getElementById('workspaceTab').style.display = tab === 'workspace' ? 'block' : 'none';

    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    e.target.classList.add('active');
}

/* DEFAULT */
window.onload = function(){
    document.getElementById('chatTab').style.display = 'block';
    document.getElementById('workspaceTab').style.display = 'none';
}
</script>

@endsection