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
        <h2>{{ $roomData['name'] ?? $room }}</h2>
        <div class="header-sub" style="display:flex; align-items:center; gap:8px;">
            <span>Room Chat &amp; Workspace</span>
            @if(($roomData['status'] ?? 'Public') === 'Private')
                <span style="font-size:10px; background:#1a2200; border:1px solid #c9a227; color:#c9a227; border-radius:6px; padding:1px 7px;">🔒 Private</span>
            @endif
        </div>
        @if(session('role') === 'admin' && ($roomData['status'] ?? 'Public') === 'Private')
            <div style="margin-top:6px; display:flex; align-items:center; gap:8px;">
                <span style="font-size:10px; color:#6f8a75;">Kode Room:</span>
                <span style="font-family:monospace; font-size:13px; font-weight:700; color:#f5d679; letter-spacing:3px;">{{ $roomData['code'] ?? '-' }}</span>
                <button onclick="navigator.clipboard.writeText('{{ $roomData['code'] ?? '' }}').then(()=>this.textContent='✓')"
                    style="background:none; border:1px solid #c9a227; color:#c9a227; border-radius:5px; padding:1px 7px; font-size:10px; cursor:pointer;">Salin</button>
            </div>
        @endif
    </div>

    <div class="header-actions">
        <button onclick="switchTab(event,'chat')" class="tab-btn active">Chat</button>
        <button onclick="switchTab(event,'members')" class="tab-btn">Members</button>
        <button onclick="switchTab(event,'workspace')" class="tab-btn">Workspace</button>
    </div>

</div>

<!-- CONTENT -->
<div class="container">

    <!-- CHAT -->
    <div id="chatTab">
        @include('chat-content')
    </div>

    <!-- MEMBERS -->
    <div id="membersTab" style="display:none;">
        <div style="margin-top: 20px;">
            <h3 style="font-size:14px; margin-bottom: 15px; color:#888; letter-spacing:1px; text-transform:uppercase;">Participants ({{ count($roomData['joined_users'] ?? []) }})</h3>
            <div style="display: flex; flex-direction: column; gap: 10px;">
                @forelse($roomData['joined_users'] ?? [] as $participant)
                    @php
                        $pName = is_array($participant) ? ($participant['name'] ?? 'User') : $participant;
                        $pEmail = is_array($participant) ? ($participant['email'] ?? '') : '';
                        $isAdmin = $pEmail === 'moonomaproject@gmail.com';
                        $initials = strtoupper(substr($pName, 0, 1));
                    @endphp
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px 14px; background: #0d0f0d; border: 1px solid {{ $isAdmin ? '#c9a227' : '#3E5641' }}; border-radius: 10px; {{ $isAdmin ? 'box-shadow: 0 0 8px rgba(201,162,39,0.2);' : '' }}">
                        <div style="display:flex; align-items:center; gap:10px;">
                            {{-- Avatar initials --}}
                            <div style="width:32px; height:32px; border-radius:50%; background:{{ $isAdmin ? 'linear-gradient(135deg, #c9a227, #f5d679)' : '#1f2d22' }}; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:13px; color:{{ $isAdmin ? '#1a1100' : '#8fba97' }}; flex-shrink:0;">
                                {{ $initials }}
                            </div>
                            <div>
                                <div style="display:flex; align-items:center; gap:6px;">
                                    <span style="font-size:13px; font-weight:{{ $isAdmin ? '700' : '400' }}; color:{{ $isAdmin ? '#f5d679' : '#ccc' }};">{{ $pName }}</span>
                                    @if($isAdmin)
                                        <span title="Admin" style="font-size:15px; line-height:1;">👑</span>
                                        <span style="font-size:9px; background:#c9a227; color:#1a1100; border-radius:4px; padding:1px 5px; font-weight:700; letter-spacing:0.5px;">ADMIN</span>
                                    @endif
                                </div>
                                @if($isAdmin)
                                    <div style="font-size:10px; color:#8a7030; margin-top:2px;">Room Moderator</div>
                                @endif
                            </div>
                        </div>
                        @if(session('role') === 'admin' && $pName !== session('name', 'User') && !$isAdmin)
                            <form action="{{ route('chat.kick', ['room' => $room]) }}" method="POST" style="margin:0;" onsubmit="return confirm('Kick {{ $pName }} dari room ini?');">
                                @csrf
                                <input type="hidden" name="target_user" value="{{ $pName }}">
                                <button type="submit" style="background:#8b3a3a; border:none; color:#fff; font-size:11px; padding: 5px 12px; border-radius: 6px; cursor:pointer; font-weight:600;">Kick</button>
                            </form>
                        @endif
                    </div>
                @empty
                    <div style="font-size: 12px; color: #6f8a75; text-align:center; padding:20px;">Belum ada partisipan yang bergabung.</div>
                @endforelse
            </div>
        </div>
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
    document.getElementById('membersTab').style.display = tab === 'members' ? 'block' : 'none';
    document.getElementById('workspaceTab').style.display = tab === 'workspace' ? 'block' : 'none';

    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    e.target.classList.add('active');
}

/* DEFAULT */
window.onload = function(){
    document.getElementById('chatTab').style.display = 'block';
    document.getElementById('membersTab').style.display = 'none';
    document.getElementById('workspaceTab').style.display = 'none';
}
</script>

@endsection