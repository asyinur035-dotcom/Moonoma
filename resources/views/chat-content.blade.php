<style>
.chat-box{
    height:60vh;
    overflow-y:auto;
    display:flex;
    flex-direction:column;
    gap:12px;
    /* HIDE SCROLLBAR */
    -ms-overflow-style: none;
    scrollbar-width: none;
}

.chat-box::-webkit-scrollbar {
    display: none;
}

/* ITEM */
.chat-item{
    max-width:70%;
    display:flex;
    flex-direction:column;
}

.chat-item.me{
    align-self:flex-end;
    text-align:right;
}

.chat-item.other{
    align-self:flex-start;
}

/* NAME */
.msg-name{
    font-size:11px;
    color:#6f8a75;
    margin-bottom:4px;
}

/* MESSAGE */
.message{
    padding:10px 14px;
    border-radius:12px;
    font-size:13px;
    line-height:1.4;
}

.me .message{
    background:#3E5641;
    color:#fff;
}

.other .message{
    background:#1c1f1d;
    border:1px solid #3E5641;
}

/* INPUT */
.chat-input{
    display:flex;
    gap:10px;
    margin-top:30px;
    padding-bottom:10px;
}

.chat-input input{
    flex:1;
    padding:10px;
    border-radius:20px;
    border:1px solid #3E5641;
    background:#0d0f0d;
    color:#fff;
    outline:none;
}

.chat-input input::placeholder{
    color:#6f8a75;
}

/* BUTTON */
.send-btn{
    width:40px;
    height:40px;
    border-radius:50%;
    background:#3E5641;
    display:flex;
    align-items:center;
    justify-content:center;
    cursor:pointer;
    transition:0.2s;
}

.send-btn:hover{
    opacity:0.8;
}

/* DELETE */
.delete-btn{
    cursor:pointer;
    font-size:14px;
    color:#6f8a75;
    margin-left:12px;
    opacity:0;
    transition:0.2s;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 24px;
    height: 24px;
    border-radius: 50%;
}

.delete-btn:hover{
    background: rgba(62,86,65,0.3);
    color: #fff;
}

.chat-item:hover .delete-btn{
    opacity:1;
}

.delete-menu{
    display:none;
    position:absolute;
    background:#1c1f1d;
    border:1px solid #3E5641;
    border-radius:8px;
    padding:5px 0;
    z-index:100;
    width:160px;
    box-shadow:0 4px 12px rgba(0,0,0,0.5);
}

.delete-menu button{
    display:block;
    width:100%;
    padding:8px 12px;
    background:transparent;
    border:none;
    color:#fff;
    text-align:left;
    font-size:12px;
    cursor:pointer;
}

.delete-menu button:hover{
    background:rgba(62,86,65,0.3);
}
</style>

<div id="chatBox" class="chat-box">
    @foreach($messages as $m)
        @php
            $timeStr = $m['time'] ?? '';
            $formattedTime = $timeStr;
            if ($timeStr && strpos($timeStr, '-') !== false) {
                try {
                    $date = \Carbon\Carbon::parse($timeStr);
                    if ($date->isToday()) {
                        $formattedTime = $date->format('H:i');
                    } elseif ($date->isYesterday()) {
                        $formattedTime = 'Yesterday ' . $date->format('H:i');
                    } else {
                        $formattedTime = $date->format('d M, H:i');
                    }
                } catch (\Exception $e) {}
            }
        @endphp
        <div class="chat-item {{ ($m['sender'] ?? '') === session('name', 'User') ? 'me' : 'other' }}">
            <div class="msg-name">
                {{ $m['sender'] ?? 'User' }} <span style="font-size: 9px; color: #555;">{{ $formattedTime }}</span>
                <span class="delete-btn" onclick="toggleDeleteMenu(event, '{{ $m['id'] ?? '' }}')">⋮</span>
                <div id="menu-{{ $m['id'] ?? '' }}" class="delete-menu">
                    <button onclick="confirmDelete('{{ $m['id'] ?? '' }}', 'for_me')">Hapus untuk saya</button>
                    @if(($m['sender'] ?? '') === session('name'))
                        @php
                            $canDeleteEveryone = false;
                            if (isset($m['time'])) {
                                $sentTime = \Carbon\Carbon::parse($m['time']);
                                $canDeleteEveryone = $sentTime->diffInMinutes(now()) <= 10;
                            }
                        @endphp
                        @if($canDeleteEveryone)
                            <button onclick="confirmDelete('{{ $m['id'] ?? '' }}', 'for_everyone')" style="color:#8b3a3a;">Hapus untuk semua</button>
                        @endif
                    @endif
                </div>
            </div>
            <div class="message">
                @if($m['message'] ?? '')
                    <div>{{ $m['message'] }}</div>
                @endif

                @if(isset($m['attachment']) && $m['attachment'])
                    <div style="margin-top:8px;">
                        @if($m['attachment']['type'] === 'image')
                            <img src="{{ asset($m['attachment']['path']) }}" style="max-width:100%; border-radius:8px; cursor:pointer;" onclick="window.open(this.src)">
                        @elseif($m['attachment']['type'] === 'video')
                            <div style="background:rgba(0,0,0,0.2); border-radius:8px; padding:5px;">
                                <video controls style="max-width:100%; border-radius:8px; display:block;">
                                    <source src="{{ asset($m['attachment']['path']) }}" type="{{ $m['attachment']['mime'] }}">
                                    Your browser does not support the video tag.
                                </video>
                                <a href="{{ asset($m['attachment']['path']) }}" target="_blank" style="display:block; font-size:10px; color:#6f8a75; text-decoration:none; margin-top:5px; text-align:center;">
                                    📥 Download Video ({{ $m['attachment']['name'] }})
                                </a>
                            </div>
                        @elseif($m['attachment']['type'] === 'audio')
                            <div style="background:rgba(0,0,0,0.2); border-radius:8px; padding:8px;">
                                <audio controls style="width:100%;">
                                    <source src="{{ asset($m['attachment']['path']) }}" type="{{ $m['attachment']['mime'] }}">
                                    Your browser does not support the audio element.
                                </audio>
                                <a href="{{ asset($m['attachment']['path']) }}" target="_blank" style="display:block; font-size:10px; color:#6f8a75; text-decoration:none; margin-top:5px; text-align:center;">
                                    📥 Download Audio ({{ $m['attachment']['name'] }})
                                </a>
                            </div>
                        @else
                            <a href="{{ asset($m['attachment']['path']) }}" target="_blank" style="display:flex; align-items:center; gap:8px; padding:10px; background:rgba(255,255,255,0.05); border-radius:8px; text-decoration:none; color:#fff; border:1px solid rgba(62,86,65,0.3);">
                                <span style="font-size:20px;">📄</span>
                                <div style="flex:1; overflow:hidden;">
                                    <div style="font-size:12px; white-space:nowrap; text-overflow:ellipsis; overflow:hidden;">{{ $m['attachment']['name'] }}</div>
                                    <div style="font-size:10px; color:#6f8a75;">Download File</div>
                                </div>
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    @endforeach
</div>

<div id="loadingOverlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.7); z-index:9999; flex-direction:column; align-items:center; justify-content:center;">
    <div style="width:40px; height:40px; border:4px solid #3E5641; border-top:4px solid #fff; border-radius:50%; animation:spin 1s linear infinite;"></div>
    <div style="margin-top:15px; color:#fff; font-size:14px; font-weight:500;">Mengirim file...</div>
    <div style="margin-top:5px; color:#6f8a75; font-size:11px;">Mohon tunggu sebentar</div>
</div>

<style>
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>

<div id="filePreview" style="display:none; padding:8px 15px; background:#1a221b; border:1px solid #3E5641; border-radius:10px; margin-bottom:10px; font-size:12px; color:#6f8a75; justify-content:space-between; align-items:center;">
    <span id="fileName"></span>
    <span onclick="clearFile()" style="cursor:pointer; font-weight:bold; color:#8b3a3a; padding:0 5px;">&times;</span>
</div>

<form method="POST" action="{{ route('chat.send', $room) }}" class="chat-input" enctype="multipart/form-data" id="chatForm">
    @csrf
    <label class="send-btn" style="background:transparent; border:1px solid #3E5641; cursor:pointer;" title="Attach File">
        📎
        <input type="file" name="attachment" id="attachmentInput" hidden onchange="handleFile(this)">
    </label>
    <input type="text" name="message" id="messageInput" placeholder="Message..." autocomplete="off">
    <button type="submit" class="send-btn">➤</button>
</form>

<script>
    function scrollToBottom() {
        let box = document.getElementById('chatBox');
        if (box) {
            box.scrollTop = box.scrollHeight;
        }
    }

    // Jalankan saat pertama kali dimuat
    scrollToBottom();

    // Jalankan lagi setelah sedikit jeda untuk memastikan gambar/media sudah mengambil ruang
    setTimeout(scrollToBottom, 100);
    setTimeout(scrollToBottom, 500);

    function handleFile(input) {
        if (input.files && input.files[0]) {
            let file = input.files[0];
            let maxSize = 40 * 1024 * 1024; // 40MB

            if (file.size > maxSize) {
                alert('Ukuran file melebihi 40MB (limit server). Silakan pilih file yang lebih kecil.');
                input.value = '';
                return;
            }

            document.getElementById('fileName').innerText = file.name;
            document.getElementById('filePreview').style.display = 'flex';
            document.getElementById('messageInput').required = false;
        }
    }

    function clearFile() {
        let input = document.getElementById('attachmentInput');
        input.value = '';
        document.getElementById('filePreview').style.display = 'none';
        document.getElementById('messageInput').required = true;
    }

    // Auto submit on enter for message
    document.getElementById('messageInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            submitForm();
        }
    });

    document.getElementById('chatForm').addEventListener('submit', function(e) {
        e.preventDefault();
        submitForm();
    });

    function submitForm() {
        let fileInput = document.getElementById('attachmentInput');
        // Hanya tampilkan loading jika ada file yang dikirim
        if (fileInput && fileInput.files && fileInput.files.length > 0) {
            document.getElementById('loadingOverlay').style.display = 'flex';
        }
        document.getElementById('chatForm').submit();
    }

    /* DELETE LOGIC */
    function toggleDeleteMenu(e, id) {
        e.stopPropagation();
        document.querySelectorAll('.delete-menu').forEach(m => {
            if (m.id !== 'menu-' + id) m.style.display = 'none';
        });

        let menu = document.getElementById('menu-' + id);
        if (menu.style.display === 'block') {
            menu.style.display = 'none';
        } else {
            menu.style.display = 'block';
            let rect = e.target.getBoundingClientRect();
            menu.style.top = (rect.bottom + window.scrollY) + 'px';
            menu.style.left = (rect.left + window.scrollX - 140) + 'px';
            menu.style.position = 'fixed';
        }
    }

    document.addEventListener('click', function() {
        document.querySelectorAll('.delete-menu').forEach(m => m.style.display = 'none');
    });

    function confirmDelete(msgId, type) {
        let label = type === 'for_everyone' ? 'semua orang' : 'Anda saja';
        if (confirm('Hapus pesan ini untuk ' + label + '?')) {
            let form = document.createElement('form');
            form.method = 'POST';
            form.action = "{{ route('chat.delete', $room) }}";
            
            let csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = "{{ csrf_token() }}";
            
            let idInput = document.createElement('input');
            idInput.type = 'hidden';
            idInput.name = 'message_id';
            idInput.value = msgId;

            let typeInput = document.createElement('input');
            typeInput.type = 'hidden';
            typeInput.name = 'type';
            typeInput.value = type;

            form.appendChild(csrf);
            form.appendChild(idInput);
            form.appendChild(typeInput);
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>