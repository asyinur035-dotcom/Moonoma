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

    {{-- PRIVATE ROOM CREATED: Show Code --}}
    @if(session('room_created_private'))
    <div id="privateCodeBanner" style="background:linear-gradient(135deg, #1a2e1c, #0d1a10); border:1px solid #c9a227; border-radius:14px; padding:20px 24px; margin-bottom:20px; position:relative;">
        <button onclick="document.getElementById('privateCodeBanner').style.display='none'" style="position:absolute; top:10px; right:14px; background:none; border:none; color:#8a7030; font-size:18px; cursor:pointer;">✕</button>
        <div style="font-size:11px; color:#8a7030; letter-spacing:1px; text-transform:uppercase; margin-bottom:4px;">Room Private Berhasil Dibuat ✅</div>
        <div style="font-size:15px; font-weight:700; color:#f5d679; margin-bottom:10px;">{{ session('room_name') }}</div>
        <div style="font-size:12px; color:#6f8a75; margin-bottom:8px;">Bagikan kode ini kepada anggota yang ingin bergabung:</div>
        <div style="display:flex; align-items:center; gap:10px;">
            <div id="roomCodeDisplay" style="font-size:28px; font-weight:900; letter-spacing:8px; color:#f5d679; background:#0a120c; border:1px solid #c9a227; border-radius:10px; padding:10px 20px; font-family:monospace;">{{ session('room_code') }}</div>
            <button onclick="navigator.clipboard.writeText('{{ session('room_code') }}').then(()=>this.textContent='Tersalin ✓')" style="background:#c9a227; border:none; color:#1a1100; padding:8px 14px; border-radius:8px; cursor:pointer; font-size:12px; font-weight:700; white-space:nowrap;">Salin Kode</button>
        </div>
    </div>
    @endif

    {{-- General success/error --}}
    @if(session('success'))
        <div style="background:#1a2e1c; border:1px solid #3E5641; border-radius:10px; padding:12px 16px; margin-bottom:16px; color:#8fba97; font-size:13px;">
            ✅ {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div style="background:#2e1a1a; border:1px solid #8b3a3a; border-radius:10px; padding:12px 16px; margin-bottom:16px; color:#e87c7c; font-size:13px;">
            ❌ {{ session('error') }}
        </div>
    @endif

    <!-- FILTER -->
    <div class="filters">
        <div class="filter-btn active" onclick="filterRoom('all', this)">All</div>
        <div class="filter-btn" onclick="filterRoom('coding', this)">Coding</div>
        <div class="filter-btn" onclick="filterRoom('design', this)">Design</div>
        <div class="filter-btn" onclick="filterRoom('data', this)">etc.</div>
    </div>

    <!-- ROOMS -->
    <div id="roomContainer" class="room-grid">
        @forelse($rooms as $r)
            <div class="room-card" data-type="{{ strtolower($r['type'] ?? 'coding') }}">
                <div>
                    <span class="badge">{{ $r['type'] ?? 'Coding' }}</span>
                    <span class="status">{{ $r['status'] ?? 'Public' }}</span>
                </div>

                <div class="room-title">{{ $r['name'] }}</div>
                <div class="room-sub">{{ $r['desc'] ?? '' }}</div>

                <div class="room-footer">
                    <div class="member">{{ $r['member'] ?? 1 }} Member</div>
                    <div style="display: flex; gap: 8px;">
                        @if(session('role') === 'admin')
                            <form action="{{ route('room.delete', !empty($r['slug']) ? $r['slug'] : 'invalid') }}" method="POST" style="margin:0;" onsubmit="return confirm('Yakin ingin menghapus room ini?');">
                                @csrf
                                <button type="submit" class="join-btn" style="background:transparent; border:1px solid #8b3a3a; color:#8b3a3a;">Delete</button>
                            </form>
                        @endif

                        @php
                            $isJoined = false;
                            if(isset($r['joined_users'])) {
                                foreach($r['joined_users'] as $u) {
                                    $uName = is_array($u) ? ($u['name'] ?? '') : $u;
                                    if($uName === session('name')) {
                                        $isJoined = true;
                                        break;
                                    }
                                }
                            }
                        @endphp

                        @if(($r['status'] ?? 'Public') === 'Private' && session('role') !== 'admin' && !$isJoined)
                            {{-- Private room: show modal to enter code --}}
                            <button type="button" class="join-btn"
                                onclick="openCodeModal('{{ !empty($r['slug']) ? $r['slug'] : 'invalid' }}', '{{ $r['name'] ?? 'Room' }}')"
                                style="display:flex; align-items:center; gap:4px;">
                                🔒 Join
                            </button>
                        @else
                            {{-- Public room or Admin or already joined: join directly --}}
                            <form action="{{ route('room.join.direct', !empty($r['slug']) ? $r['slug'] : 'invalid') }}" method="POST" style="margin:0;">
                                @csrf
                                <button type="submit" class="join-btn">Join</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="empty">
                No rooms available
            </div>
        @endforelse
    </div>

    <!-- EMPTY -->
    <div id="emptyState" class="empty" style="display:none;">
        No rooms available
    </div>

</div>

{{-- PRIVATE ROOM CODE MODAL --}}
<div id="codeModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.7); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:#0d1a10; border:1px solid #3E5641; border-radius:16px; padding:28px 32px; width:100%; max-width:360px; text-align:center;">
        <div style="font-size:28px; margin-bottom:6px;">🔒</div>
        <div style="font-size:16px; font-weight:700; margin-bottom:4px;" id="modalRoomName">Room Private</div>
        <div style="font-size:12px; color:#6f8a75; margin-bottom:20px;">Masukkan kode room untuk bergabung</div>

        <form id="codeForm" action="" method="POST">
            @csrf
            <input type="text" name="room_code" id="codeInput"
                placeholder="Masukkan kode room"
                autocomplete="off"
                style="width:100%; padding:10px 14px; border-radius:10px; border:1px solid #3E5641; background:#0a120c; color:#fff; font-size:14px; letter-spacing:2px; text-align:center; text-transform:uppercase; margin-bottom:16px; box-sizing:border-box;">
            <div style="display:flex; gap:10px;">
                <button type="button" onclick="closeCodeModal()"
                    style="flex:1; padding:10px; border-radius:10px; border:1px solid #3E5641; background:transparent; color:#fff; cursor:pointer;">Batal</button>
                <button type="submit"
                    style="flex:1; padding:10px; border-radius:10px; border:none; background:#3E5641; color:#fff; cursor:pointer; font-weight:600;">Masuk</button>
            </div>
        </form>
    </div>
</div>

<script>
/* FILTER */
function filterRoom(type, el){
    document.querySelectorAll('.filter-btn').forEach(b=>b.classList.remove('active'));
    el.classList.add('active');

    let cards = document.querySelectorAll('.room-card');
    let visibleCount = 0;

    cards.forEach(card => {
        if(type === 'all' || card.getAttribute('data-type') === type) {
            card.style.display = 'block';
            visibleCount++;
        } else {
            card.style.display = 'none';
        }
    });

    if(visibleCount === 0) {
        document.getElementById('emptyState').style.display = 'block';
    } else {
        document.getElementById('emptyState').style.display = 'none';
    }
}

/* PRIVATE ROOM MODAL */
function openCodeModal(slug, roomName) {
    document.getElementById('modalRoomName').textContent = roomName;
    document.getElementById('codeForm').action = '/rooms/join/' + slug;
    document.getElementById('codeInput').value = '';
    const modal = document.getElementById('codeModal');
    modal.style.display = 'flex';
    setTimeout(() => document.getElementById('codeInput').focus(), 100);
}

function closeCodeModal() {
    document.getElementById('codeModal').style.display = 'none';
}

// Close modal on backdrop click
document.getElementById('codeModal').addEventListener('click', function(e) {
    if (e.target === this) closeCodeModal();
});
</script>

@endsection