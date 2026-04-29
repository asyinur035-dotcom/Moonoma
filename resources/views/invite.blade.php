@extends('layouts.app')

@section('content')

<style>
.invite-wrapper {
    display: flex;
    justify-content: center;
    padding: 40px 20px;
}

.invite-card {
    width: 100%;
    max-width: 420px;
    background: linear-gradient(160deg, #0d1a10, #0a120c);
    border: 1px solid #3E5641;
    border-radius: 20px;
    padding: 32px 28px;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.invite-icon {
    font-size: 40px;
    text-align: center;
}

.invite-title {
    font-size: 20px;
    font-weight: 700;
    text-align: center;
    color: #fff;
}

.invite-sub {
    font-size: 12px;
    color: #6f8a75;
    text-align: center;
    margin-top: -12px;
}

.invite-input {
    width: 100%;
    padding: 14px 18px;
    border: 1px solid #3E5641;
    border-radius: 12px;
    background: #07100a;
    color: #fff;
    font-size: 18px;
    letter-spacing: 4px;
    text-align: center;
    text-transform: uppercase;
    box-sizing: border-box;
    outline: none;
    transition: border-color 0.2s;
}

.invite-input:focus {
    border-color: #6f8a75;
}

.join-btn {
    width: 100%;
    background: #3E5641;
    border: none;
    padding: 13px;
    border-radius: 12px;
    color: #fff;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s;
}

.join-btn:hover {
    background: #4e6e52;
}

.alert-error {
    background: #2e1a1a;
    border: 1px solid #8b3a3a;
    border-radius: 10px;
    padding: 10px 14px;
    color: #e87c7c;
    font-size: 13px;
    text-align: center;
}

.alert-success {
    background: #1a2e1c;
    border: 1px solid #3E5641;
    border-radius: 10px;
    padding: 10px 14px;
    color: #8fba97;
    font-size: 13px;
    text-align: center;
}
</style>

<div class="invite-wrapper">
    <div class="invite-card">
        <div class="invite-icon">🔑</div>
        <div class="invite-title">Join via Kode</div>
        <div class="invite-sub">Masukkan kode room yang dibagikan kepadamu</div>

        @if(session('error'))
            <div class="alert-error">❌ {{ session('error') }}</div>
        @endif

        @if(session('success'))
            <div class="alert-success">✅ {{ session('success') }}</div>
        @endif

        <form action="{{ route('room.join') }}" method="POST" style="display:flex; flex-direction:column; gap:14px;">
            @csrf
            <input
                type="text"
                name="code"
                class="invite-input"
                placeholder="ABC123"
                autocomplete="off"
                autofocus
                maxlength="6"
                value="{{ old('code') }}"
            >
            @error('code')
                <div style="color:#e87c7c; font-size:12px; text-align:center; margin-top:-8px;">{{ $message }}</div>
            @enderror
            <button type="submit" class="join-btn">Masuk ke Room →</button>
        </form>

        <div style="text-align:center; margin-top:-8px;">
            <a href="{{ route('rooms') }}" style="font-size:12px; color:#6f8a75; text-decoration:none;">← Kembali ke daftar room</a>
        </div>
    </div>
</div>

@endsection