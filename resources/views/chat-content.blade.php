<style>
.chat-box{
    height:60vh;
    overflow-y:auto;
    display:flex;
    flex-direction:column;
    gap:12px;
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
    margin-top:10px;
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
            </div>
            <div class="message">{{ $m['message'] ?? '' }}</div>
        </div>
    @endforeach
</div>

<form method="POST" action="{{ route('chat.send', $room) }}" class="chat-input">
    @csrf
    <input type="text" name="message" placeholder="Message..." required autocomplete="off">
    <button type="submit" class="send-btn">➤</button>
</form>

<script>
    let box = document.getElementById('chatBox');
    if (box) {
        box.scrollTop = box.scrollHeight;
    }
</script>