@extends('layouts.app')

@section('content')

<div class="header">
    <h2>Search</h2>

    <div class="header-right">
        <form method="GET" action="{{ route('search') }}">
            <input 
                type="text" 
                name="q" 
                value="{{ $query ?? '' }}"
                class="search-input"
                placeholder="Search..."
            >
        </form>

        <div class="search-icon" onclick="focusSearch()">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="7"></circle>
                <line x1="16.5" y1="16.5" x2="21" y2="21"></line>
            </svg>
        </div>
    </div>
</div>

<style>

/* HEADER FIX (SAMA HOME) */
.header{
    display:flex;
    justify-content:space-between;
    align-items:center;

    padding:16px 20px 14px;
    border-bottom:1px solid #3E5641;
    margin-bottom:25px;
}

.header h2{
    font-size:24px;
    font-weight:500;
    margin:0;
    line-height:1.2;
}

/* RIGHT */
.header-right{
    display:flex;
    align-items:center;
    gap:12px;
}

/* SEARCH INPUT */
.search-input{
    border:1px solid #3E5641;
    background:transparent;
    padding:8px 14px;
    border-radius:20px;
    color:#fff;
    width:200px;
    font-size:13px;
    outline:none;
}

.search-input::placeholder{
    color:#6f8a75;
}

/* ICON */
.search-icon{
    width:34px;
    height:34px;
    border-radius:50%;
    border:1px solid #3E5641;
    display:flex;
    align-items:center;
    justify-content:center;
    color:#3E5641;
    cursor:pointer;
    transition:0.2s;
}

.search-icon svg{
    width:15px;
    height:15px;
}

.search-icon:hover{
    background:#3E5641;
    color:#fff;
}

/* CONTENT */
.search-content{
    padding:0 20px;
    display:flex;
    flex-direction:column;
    gap:20px;
}

/* KEYWORDS */
.keywords{
    display:flex;
    gap:10px;
}

.keyword{
    border:1px solid #3E5641;
    padding:6px 14px;
    border-radius:20px;
    font-size:12px;
    color:#fff;
    text-decoration:none;
    transition:0.2s;
}

.keyword:hover{
    background:#3E5641;
}

/* GRID */
.room-grid{
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:20px;
}

/* CARD */
.room-card{
    border:1px solid #3E5641;
    border-radius:16px;
    padding:18px;
    background:#0d0f0d;
}

.status{
    float:right;
    font-size:10px;
    padding:3px 8px;
    border-radius:10px;
    border:1px solid #3E5641;
}

.room-title{
    font-size:15px;
}

.room-sub{
    font-size:12px;
    color:#6f8a75;
    margin:10px 0;
}

.join-btn{
    width:100%;
    background:#3E5641;
    border:none;
    padding:8px;
    border-radius:20px;
    color:#fff;
    cursor:pointer;
}

/* EMPTY */
.empty{
    grid-column:1 / -1;
    display:flex;
    justify-content:center;
    align-items:center;
    height:200px;
    color:#6f8a75;
    font-size:14px;
}

</style>

<div class="search-content">

    <div class="keywords">
        <a href="{{ route('search', ['q' => 'coding']) }}" class="keyword">Coding</a>
        <a href="{{ route('search', ['q' => 'design']) }}" class="keyword">Design</a>
        <a href="{{ route('search', ['q' => 'data']) }}" class="keyword">etc.</a>
    </div>

    <div class="room-grid">
        @forelse($rooms as $r)
            <div class="room-card">
                <span class="status">{{ $r['status'] }}</span>

                <div class="room-title">{{ $r['name'] }}</div>
                <div class="room-sub">{{ $r['desc'] }}</div>
                <div class="room-sub">{{ $r['type'] }}</div>

                <form action="{{ route('room.join.direct', !empty($r['slug']) ? $r['slug'] : 'invalid') }}" method="POST" style="margin:0;">
                    @csrf
                    <button type="submit" class="join-btn">Join</button>
                </form>
            </div>
        @empty
            <div class="empty">
                No results found
            </div>
        @endforelse
    </div>

</div>

<script>
function focusSearch(){
    document.querySelector('.search-input').focus();
}
</script>

@endsection