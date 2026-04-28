<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $title ?? 'Moonoma' }}</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:'Poppins',sans-serif;}

body{
    background:#0d0f0d;
    color:#fff;
    display:flex;
}

/* SIDEBAR */
.sidebar{
    width:240px;
    height:100vh;
    background:#0f1110;
    border-right:1px solid #1c1c1c;
    padding:24px 18px;
    display:flex;
    flex-direction:column;
    justify-content:space-between;
}

/* LOGO */
.logo{
    display:flex;
    gap:10px;
    align-items:center;
    margin-bottom:30px;
}

.logo-icon{
    width:32px;
    height:32px;
    border-radius:8px;
    background:linear-gradient(135deg,#8b5cf6,#6366f1);
    display:flex;
    align-items:center;
    justify-content:center;
}

/* MENU */
.menu-title{
    font-size:10px;
    color:#444;
    margin-bottom:12px;
    letter-spacing:1px;
}

.menu a{
    display:block;
    color:#888;
    text-decoration:none;
    font-size:13px;
    margin-bottom:12px;
}

.menu a.active{
    color:#fff;
    font-weight:600;
}

/* ACTION */
.create-room{
    margin-top:10px;
    font-size:13px;
    color:#6f8a75;
    text-decoration:none;
    display:block;
}

.create-room:hover{
    color:#fff;
}

/* PROFILE */
.bottom-profile{
    display:flex;
    gap:10px;
}

.mini-avatar{
    width:32px;
    height:32px;
    border-radius:50%;
    background:#3E5641;
    display:flex;
    align-items:center;
    justify-content:center;
}

/* MAIN */
.main{
    flex:1;
    padding:40px 0;
}

.container{
    max-width:900px;
    margin:0 auto;
}
</style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">

    <div>

        <!-- LOGO -->
        <div class="logo">
            <div class="logo-icon">🌙</div>
            <div>
                <div>Moonoma</div>
                <div style="font-size:10px;color:#555;">WORKSPACE</div>
            </div>
        </div>

        <!-- NAV -->
        <div class="menu-title">NAVIGATE</div>
        <div class="menu">
            <a href="{{ route('home') }}" class="{{ request()->routeIs('home*') ? 'active' : '' }}">Home</a>
            <a href="{{ route('rooms') }}" class="{{ request()->routeIs('rooms*') ? 'active' : '' }}">Rooms</a>
            <a href="{{ route('search') }}" class="{{ request()->routeIs('search*') ? 'active' : '' }}">Search</a>
            <a href="{{ route('chat') }}" class="{{ request()->routeIs('chat*') ? 'active' : '' }}">Room Chat</a>
            <a href="{{ route('profile') }}" class="{{ request()->routeIs('profile*') ? 'active' : '' }}">Profile</a>
        </div>

        <!-- ACTION -->
        <div class="menu-title" style="margin-top:20px;">ROOM</div>

        <a href="{{ route('room.create') }}" class="create-room">+ Create Room</a>

        <!-- 🔥 JOIN PRIVATE ROOM -->
        <a href="{{ route('invite.page') }}" class="create-room">
            + Join via Code
        </a>

    </div>

    <!-- PROFILE -->
    <div class="bottom-profile">
        <div class="mini-avatar">Un</div>
        <div>
            <div style="font-size:12px;">Unknown</div>
            <div style="font-size:10px;color:#888;">Designer</div>
        </div>
    </div>

</div>

<!-- CONTENT -->
<div class="main">
    <div class="container">
        @yield('content')
    </div>
</div>

</body>
</html>