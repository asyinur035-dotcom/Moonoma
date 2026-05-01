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
