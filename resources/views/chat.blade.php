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

/* MODAL STYLES */
.modal-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.8);
    backdrop-filter: blur(5px);
    z-index: 1000;
    align-items: center;
    justify-content: center;
}

.modal-content {
    background: #0d0f0d;
    border: 1px solid #3E5641;
    border-radius: 20px;
    width: 90%;
    max-width: 500px;
    padding: 30px;
    position: relative;
    box-shadow: 0 10px 30px rgba(0,0,0,0.5);
}

.modal-close {
    position: absolute;
    top: 15px;
    right: 20px;
    font-size: 24px;
    color: #6f8a75;
    cursor: pointer;
}

.modal-header {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-bottom: 25px;
}

.modal-avatar {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    border: 3px solid #3E5641;
    object-fit: cover;
    margin-bottom: 15px;
}

.modal-details {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}

.modal-label {
    font-size: 11px;
    color: #6f8a75;
    margin-bottom: 4px;
}

.modal-value {
    font-size: 13px;
    color: #fff;
}

.modal-full {
    grid-column: span 2;
    margin-top: 10px;
}

.modal-cv-btn {
    display: block;
    width: 100%;
    padding: 12px;
    background: #3E5641;
    color: #fff;
    text-align: center;
    text-decoration: none;
    border-radius: 12px;
    font-size: 13px;
    margin-top: 20px;
}

@media (max-width: 768px) {
    .header {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
        padding: 15px;
    }

    .header-actions {
        width: 100%;
        overflow-x: auto;
        padding-bottom: 5px;
        scrollbar-width: none;
    }
    
    .header-actions::-webkit-scrollbar {
        display: none;
    }

    #chatSearch {
        width: 100% !important;
    }
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
        <div style="position:relative; margin-right:10px;">
            <input type="text" id="chatSearch" placeholder="Cari pesan..." onkeyup="searchMessages()" 
                style="background:#0d0f0d; border:1px solid #3E5641; border-radius:20px; padding:6px 14px 6px 30px; color:#fff; font-size:12px; width:150px; outline:none;">
            <span style="position:absolute; left:10px; top:50%; transform:translateY(-50%); font-size:12px; color:#6f8a75;">🔍</span>
        </div>
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
                    <div onclick="showUserProfile('{{ $pEmail }}')" style="display: flex; justify-content: space-between; align-items: center; padding: 10px 14px; background: #0d0f0d; border: 1px solid {{ $isAdmin ? '#c9a227' : '#3E5641' }}; border-radius: 10px; cursor: pointer; transition: 0.2s; {{ $isAdmin ? 'box-shadow: 0 0 8px rgba(201,162,39,0.2);' : '' }}" onmouseover="this.style.borderColor='#fff'" onmouseout="this.style.borderColor='{{ $isAdmin ? '#c9a227' : '#3E5641' }}'">
                        <div style="display:flex; align-items:center; gap:10px;">
                            {{-- Avatar initials or image --}}
                            <div style="width:32px; height:32px; border-radius:50%; background:{{ $isAdmin ? 'linear-gradient(135deg, #c9a227, #f5d679)' : '#1f2d22' }}; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:13px; color:{{ $isAdmin ? '#1a1100' : '#8fba97' }}; flex-shrink:0; overflow:hidden;">
                                @if(isset($usersMap[$pEmail]['avatar']) && $usersMap[$pEmail]['avatar'])
                                    <img src="{{ $usersMap[$pEmail]['avatar'] }}" style="width:100%; height:100%; object-fit:cover;">
                                @else
                                    {{ $initials }}
                                @endif
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

<!-- USER PROFILE MODAL -->
<div id="profileModal" class="modal-overlay" onclick="closeProfileModal(event)">
    <div class="modal-content" onclick="event.stopPropagation()">
        <span class="modal-close" onclick="closeProfileModal(event)">&times;</span>
        <div class="modal-header">
            <img id="modalAvatar" src="" class="modal-avatar">
            <h2 id="modalName" style="margin:0;"></h2>
            <div id="modalRole" style="font-size:12px; color:#c9a227; margin-top:5px;"></div>
        </div>
        
        <div class="modal-details">
            <div id="modalEmailContainer" style="{{ session('role') === 'admin' ? '' : 'display:none;' }}">
                <div class="modal-label">Email</div>
                <div id="modalEmail" class="modal-value"></div>
            </div>
            <div>
                <div class="modal-label">City</div>
                <div id="modalCity" class="modal-value"></div>
            </div>
            <div class="modal-full">
                <div class="modal-label">Skill Teach</div>
                <div id="modalSkillTeach" class="modal-value"></div>
            </div>
            <div class="modal-full">
                <div class="modal-label">Skill Learn</div>
                <div id="modalSkillLearn" class="modal-value"></div>
            </div>
            <div class="modal-full">
                <div class="modal-label">Availability</div>
                <div id="modalAvailability" class="modal-value"></div>
            </div>
        </div>

        <a id="modalCvLink" href="#" target="_blank" class="modal-cv-btn" style="display:none;">View CV (PDF)</a>
    </div>
</div>

<script>
/* ROOM DARI ROUTE */
let currentRoom = @json($room);
let currentUserRole = @json(session('role'));

/* SWITCH TAB */
function switchTab(e, tab){
    document.getElementById('chatTab').style.display = tab === 'chat' ? 'block' : 'none';
    document.getElementById('membersTab').style.display = tab === 'members' ? 'block' : 'none';
    document.getElementById('workspaceTab').style.display = tab === 'workspace' ? 'block' : 'none';

    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    e.target.classList.add('active');
}

/* DATA FOR MODAL */
const usersMap = @json($usersMap);
const profilesMap = @json($profilesMap);

function showUserProfile(email) {
    const user = usersMap[email];
    if (!user) return;

    const profile = profilesMap[user.id] || {};

    document.getElementById('modalAvatar').src = user.avatar || 'https://cdn-icons-png.flaticon.com/512/847/847969.png';
    document.getElementById('modalName').innerText = user.name;
    document.getElementById('modalRole').innerText = user.role || 'Member';
    const emailContainer = document.getElementById('modalEmailContainer');
    if (emailContainer) {
        document.getElementById('modalEmail').innerText = user.email;
    }

    document.getElementById('modalCity').innerText = profile.city || '-';
    
    document.getElementById('modalSkillTeach').innerText = (profile.skill_teach && Array.isArray(profile.skill_teach)) 
        ? profile.skill_teach.join(', ') 
        : (profile.skill_teach || '-');

    document.getElementById('modalSkillLearn').innerText = (profile.skill_learn && Array.isArray(profile.skill_learn)) 
        ? profile.skill_learn.join(', ') 
        : (profile.skill_learn || '-');

    document.getElementById('modalAvailability').innerText = profile.availability || '-';

    const cvBtn = document.getElementById('modalCvLink');
    if (profile.cv_path && currentUserRole === 'admin') {
        cvBtn.href = "{{ asset('') }}" + profile.cv_path;
        cvBtn.style.display = 'block';
    } else {
        cvBtn.style.display = 'none';
    }

    document.getElementById('profileModal').style.display = 'flex';
}

function closeProfileModal(e) {
    document.getElementById('profileModal').style.display = 'none';
}

/* SEARCH MESSAGES */
function searchMessages() {
    let query = document.getElementById('chatSearch').value.toLowerCase();
    let items = document.querySelectorAll('.chat-item');
    
    items.forEach(item => {
        let text = item.innerText.toLowerCase();
        if (text.includes(query)) {
            item.style.display = 'flex';
        } else {
            item.style.display = 'none';
        }
    });
}

/* DEFAULT */
window.onload = function(){
    document.getElementById('chatTab').style.display = 'block';
    document.getElementById('membersTab').style.display = 'none';
    document.getElementById('workspaceTab').style.display = 'none';
}
</script>

@endsection