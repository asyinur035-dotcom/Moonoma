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
    align-items:center;
    padding-top:20px;
    border-top:1px solid #1c1c1c;
}

.logout-btn{
    margin-left:auto;
    color:#e87c7c;
    text-decoration:none;
    font-size:16px;
    opacity:0.8;
    transition:0.2s;
}

.logout-btn:hover{
    color:#ff4d4d;
    opacity:1;
    transform:scale(1.1);
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
    min-height: 100vh;
}

.container{
    max-width:900px;
    margin:0 auto;
    padding: 0 20px;
}

/* MOBILE NAV */
.mobile-header{
    display:none;
    background:#0f1110;
    border-bottom:1px solid #1c1c1c;
    padding:14px 20px;
    justify-content:space-between;
    align-items:center;
    position:sticky;
    top:0;
    z-index:900;
}

.hamburger{
    background:none;
    border:none;
    color:#fff;
    font-size:24px;
    cursor:pointer;
}

.sidebar-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.5);
    z-index: 998;
}

/* RESPONSIVE */
@media (max-width: 768px) {
    body {
        flex-direction: column;
    }

    .sidebar {
        position: fixed;
        left: -240px;
        top: 0;
        z-index: 999;
        transition: 0.3s;
        box-shadow: 10px 0 30px rgba(0,0,0,0.5);
    }

    .sidebar.open {
        left: 0;
    }

    .sidebar-overlay.show {
        display: block;
    }

    .mobile-header {
        display: flex;
    }

    .main {
        padding: 20px 0;
    }

    .container {
        padding: 0 15px;
    }
}
</style>
</head>

<!-- MOBILE HEADER -->
<div class="mobile-header">
    <div class="logo" style="margin-bottom:0;">
        <div class="logo-icon" style="width:28px; height:28px; font-size:14px;">🌙</div>
        <div style="font-size:14px; font-weight:600;">Moonoma</div>
    </div>
    <button class="hamburger" onclick="toggleSidebar()">☰</button>
</div>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

<!-- SIDEBAR -->
<div class="sidebar" id="sidebar">

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
        <div class="mini-avatar">
            @if(session('avatar'))
                <img src="{{ session('avatar') }}" style="width:100%; height:100%; border-radius:50%; object-fit:cover;">
            @else
                {{ strtoupper(substr(session('name', session('user_name', 'Unknown')), 0, 2)) }}
            @endif
        </div>
        <div style="flex:1; overflow:hidden;">
            <div style="font-size:12px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ session('name', session('user_name', 'Unknown')) }}</div>
            <div style="font-size:10px;color:#888;">
                @if(session('email') === 'moonomaproject@gmail.com')
                    <span style="color:#c9a227; font-weight:700;">ADMIN</span>
                @else
                    {{ session('role', session('user_role', 'Designer')) }}
                @endif
            </div>
        </div>
        <a href="{{ route('logout') }}" class="logout-btn" title="Logout">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                <polyline points="16 17 21 12 16 7"></polyline>
                <line x1="21" y1="12" x2="9" y2="12"></line>
            </svg>
        </a>
    </div>

</div>

<!-- CONTENT -->
<div class="main">
    <div class="container">
        @if(session('success'))
            <div style="background:#3E5641; color:#fff; padding:10px 15px; border-radius:8px; margin-bottom:20px;">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div style="background:#8b3a3a; color:#fff; padding:10px 15px; border-radius:8px; margin-bottom:20px;">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div style="background:#8b3a3a; color:#fff; padding:10px 15px; border-radius:8px; margin-bottom:20px;">
                <ul style="margin:0; padding-left:15px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </div>
</div>

<script>
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    sidebar.classList.toggle('open');
    overlay.classList.toggle('show');
}

// Close sidebar on link click (mobile)
if (window.innerWidth <= 768) {
    document.querySelectorAll('.sidebar a').forEach(link => {
        link.addEventListener('click', () => {
            document.getElementById('sidebar').classList.remove('open');
            document.getElementById('sidebarOverlay').classList.remove('show');
        });
    });
}
</script>

</body>
</html>