@extends('layouts.app')

@section('content')

<style>
.create-wrapper{
    display:flex;
    justify-content:center;
}

.create-box{
    width:100%;
    max-width:500px;
    display:flex;
    flex-direction:column;
    gap:15px;
}

.title{
    text-align:center;
    font-size:18px;
    margin-bottom:20px;
}

.input{
    width:100%;
    padding:10px;
    border-radius:14px;
    border:1px solid #3E5641;
    background:transparent;
    color:#3E5641;
}

.row{
    display:flex;
    gap:10px;
}

.btn-group{
    display:flex;
    gap:10px;
    margin-top:10px;
}

.btn{
    flex:1;
    padding:10px;
    border-radius:20px;
    cursor:pointer;
    border:none;
}

.btn-primary{
    background:#3E5641;
    color:#fff;
}

.btn-outline{
    border:1px solid #3E5641;
    background:transparent;
    color:#fff;
    text-align:center;
    text-decoration:none;
}
</style>

<div class="create-wrapper">

<form action="{{ route('room.store') }}" method="POST" class="create-box">
@csrf

<div class="title">Create new room</div>

<input name="name" class="input" placeholder="Room name">
<input name="topic" class="input" placeholder="Topic title">
<select name="role_required" class="input" required>
    <option value="" disabled selected>Select role required</option>
    <option value="Designer">Designer</option>
    <option value="UI/UX">UI/UX</option>
    <option value="Frontend">Frontend</option>
    <option value="Backend">Backend</option>
    <option value="Mobile Dev">Mobile Dev</option>
    <option value="Other">Other</option>
</select>

<div class="row">
    <select name="type" class="input">
        <option value="coding">Coding</option>
        <option value="design">Design</option>
        <option value="data">Data</option>
    </select>

    <select name="status" class="input">
        <option value="Public">Public</option>
        <option value="Private">Private</option>
    </select>
</div>

<div class="btn-group">
    <button class="btn btn-primary">Create</button>
    <a href="{{ route('rooms') }}" class="btn btn-outline">Cancel</a>
</div>

</form>

</div>

@endsection