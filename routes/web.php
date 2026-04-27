<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('login'));

Route::view('/login', 'auth.login')->name('login');
Route::post('/login', fn() => redirect()->route('home'))->name('login.process');

Route::view('/register', 'auth.register')->name('register');
Route::post('/register', fn() =>
    redirect()->route('verification')->with('success', 'Register berhasil!')
)->name('register.process');

Route::view('/verification', 'auth.verification')->name('verification');
Route::post('/verification', fn() =>
    redirect()->route('login')->with('success', 'Verifikasi berhasil!')
)->name('verify.otp');

Route::get('/resend-otp', fn() =>
    back()->with('success', 'OTP dikirim ulang!')
)->name('resend.otp');

Route::view('/forgot-password', 'auth.forgot-password')->name('password.request');
Route::post('/forgot-password', fn() =>
    redirect()->route('verification')->with('success', 'Reset dikirim!')
)->name('password.email');

Route::view('/home', 'home')->name('home');
Route::view('/home/edit', 'home-edit')->name('home.edit');

Route::post('/profile/save', function () {
    session([
        'name' => request('name'),
        'role' => request('role'),
    ]);
    return response()->json(['success' => true]);
})->name('profile.save');

Route::get('/rooms', function () {
    return view('rooms', [
        'rooms' => session('rooms', [])
    ]);
})->name('rooms');

Route::view('/rooms/create', 'create-room')->name('room.create');

Route::post('/rooms/store', function () {
    $rooms = session('rooms', []);

    $rooms[] = [
        'name' => request('name'),
        'type' => request('type'),
        'desc' => request('topic'),
        'member' => 1,
        'status' => request('status'),
    ];

    session(['rooms' => $rooms]);

    return redirect()->route('rooms');
})->name('room.store');

Route::get('/search', function () {
    $query = request('q');
    $rooms = session('rooms', []);

    if ($query) {
        $rooms = array_filter($rooms, fn($room) =>
            str_contains(strtolower($room['name']), strtolower($query)) ||
            str_contains(strtolower($room['desc']), strtolower($query)) ||
            str_contains(strtolower($room['type']), strtolower($query))
        );
    }

    return view('search', compact('rooms', 'query'));
})->name('search');

Route::view('/profile', 'profile')->name('profile');
Route::view('/profile/edit', 'edit-profile')->name('profile.edit');

Route::get('/logout', function () {
    session()->flush();
    return redirect()->route('login');
})->name('logout');

Route::get('/chat', fn() => redirect()->route('rooms'))->name('chat');

Route::get('/chat/{room}', function ($room) {
    return view('chat', compact('room'));
})->name('chat.room');

Route::view('/invite', 'invite')->name('invite.page');

Route::get('/chat/{room}/invite', function ($room) {
    return view('invite', compact('room'));
})->name('chat.invite');

Route::get('/auth/google', fn() => 'Google login belum dibuat')->name('google.redirect');
Route::get('/auth/google/callback', fn() => 'Callback Google belum dibuat');