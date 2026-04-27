<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

Route::get('/', fn () => redirect()->route('login'));

Route::view('/login', 'auth.login')->name('login');

Route::post('/login', function (Request $request) {
    session([
        'is_login' => true,
        'email' => $request->email,
        'name' => session('name', 'Your Name'),
        'role' => session('role', 'Your Role'),
    ]);

    return redirect()->route('home');
})->name('login.process');

Route::view('/register', 'auth.register')->name('register');

Route::post('/register', function (Request $request) {
    session([
        'register_email' => $request->email,
        'register_name' => $request->name,
    ]);

    return redirect()
        ->route('verification')
        ->with('success', 'Register berhasil!');
})->name('register.process');

Route::view('/verification', 'auth.verification')->name('verification');

Route::post('/verification', function () {
    return redirect()
        ->route('login')
        ->with('success', 'Verifikasi berhasil!');
})->name('verify.otp');

Route::get('/resend-otp', function () {
    return back()->with('success', 'OTP dikirim ulang!');
})->name('resend.otp');

Route::view('/forgot-password', 'auth.forgot-password')->name('password.request');

Route::post('/forgot-password', function () {
    return redirect()
        ->route('verification')
        ->with('success', 'Reset dikirim!');
})->name('password.email');

Route::get('/logout', function () {
    session()->flush();

    return redirect()->route('login');
})->name('logout');


/*
|--------------------------------------------------------------------------
| HOME
|--------------------------------------------------------------------------
*/

Route::view('/home', 'home')->name('home');
Route::view('/home/edit', 'home-edit')->name('home.edit');

Route::post('/profile/save', function (Request $request) {
    session([
        'name' => $request->name,
        'role' => $request->role,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Profile berhasil disimpan',
    ]);
})->name('profile.save');


/*
|--------------------------------------------------------------------------
| ROOMS
|--------------------------------------------------------------------------
*/

function roomsFilePath()
{
    return storage_path('app/data/rooms.json');
}

function getRooms()
{
    $file = roomsFilePath();

    if (!file_exists($file)) {
        file_put_contents($file, json_encode([]));
    }

    $data = json_decode(file_get_contents($file), true);

    return is_array($data) ? $data : [];
}

function saveRooms($rooms)
{
    file_put_contents(
        roomsFilePath(),
        json_encode(array_values($rooms), JSON_PRETTY_PRINT)
    );
}

Route::get('/rooms', function () {
    $rooms = getRooms();

    return view('rooms', compact('rooms'));
})->name('rooms');

Route::view('/rooms/create', 'create-room')->name('room.create');

Route::post('/rooms/store', function (Request $request) {
    $rooms = getRooms();

    $slug = strtolower(trim($request->name));
    $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
    $slug = trim($slug, '-');

    $rooms[] = [
        'id' => uniqid('room_'),
        'slug' => $slug,
        'name' => $request->name,
        'topic' => $request->topic,
        'desc' => $request->topic,
        'role_required' => $request->role_required,
        'type' => $request->type ?? 'Coding',
        'status' => $request->status ?? 'Public',
        'member' => 1,
        'code' => strtoupper(substr(md5($request->name . time()), 0, 6)),
        'created_by' => session('email', 'guest'),
        'created_at' => now()->toDateTimeString(),
    ];

    saveRooms($rooms);

    return redirect()->route('rooms');
})->name('room.store');

Route::get('/search', function (Request $request) {
    $query = $request->q;
    $rooms = getRooms();

    if ($query) {
        $rooms = array_filter($rooms, function ($room) use ($query) {
            $query = strtolower($query);

            return str_contains(strtolower($room['name'] ?? ''), $query)
                || str_contains(strtolower($room['desc'] ?? ''), $query)
                || str_contains(strtolower($room['topic'] ?? ''), $query)
                || str_contains(strtolower($room['type'] ?? ''), $query);
        });
    }

    return view('search', compact('rooms', 'query'));
})->name('search');

Route::post('/rooms/join/{slug}', function ($slug) {
    $rooms = getRooms();

    foreach ($rooms as &$room) {
        if (($room['slug'] ?? '') === $slug) {
            $room['member'] = ($room['member'] ?? 0) + 1;

            saveRooms($rooms);

            return redirect()->route('chat.room', $slug);
        }
    }

    return back()->with('error', 'Room tidak ditemukan');
})->name('room.join.direct');


/*
|--------------------------------------------------------------------------
| INVITE (JOIN VIA CODE)
|--------------------------------------------------------------------------
*/

Route::get('/invite', function () {
    return view('invite');
})->name('invite.page');

Route::post('/invite/join', function (\Illuminate\Http\Request $request) {
    $code = strtoupper($request->code);
    $rooms = getRooms(); // pakai function JSON tadi

    foreach ($rooms as &$room) {
        if (($room['code'] ?? '') === $code) {
            $room['member'] = ($room['member'] ?? 0) + 1;

            saveRooms($rooms);

            return redirect()->route('chat.room', $room['slug']);
        }
    }

    return back()->with('error', 'Kode room tidak ditemukan');
})->name('room.join');



/*
|--------------------------------------------------------------------------
| CHAT
|--------------------------------------------------------------------------
*/

Route::get('/chat', fn () => redirect()->route('rooms'))->name('chat');

Route::get('/chat/{room}', function ($room) {
    $rooms = session('rooms', []);
    $messages = session("messages.$room", []);

    $roomData = $rooms[$room] ?? [
        'id' => $room,
        'name' => $room,
        'topic' => '-',
        'status' => 'Public',
    ];

    return view('chat', [
        'room' => $room,
        'roomData' => $roomData,
        'messages' => $messages,
    ]);
})->name('chat.room');

Route::post('/chat/{room}/send', function (Request $request, $room) {
    $messages = session("messages.$room", []);

    $messages[] = [
        'sender' => session('name', 'User'),
        'message' => $request->message,
        'time' => now()->format('H:i'),
    ];

    session(["messages.$room" => $messages]);

    return back();
})->name('chat.send');

Route::get('/chat/{room}/invite', function ($room) {
    return view('invite', compact('room'));
})->name('chat.invite');


/*
|--------------------------------------------------------------------------
| PROFILE
|--------------------------------------------------------------------------
*/

Route::get('/profile', function () {
    return view('profile', [
        'name' => session('name', 'Your Name'),
        'role' => session('role', 'Your Role'),
        'username' => session('username', 'username'),
        'city' => session('city', '-'),
        'skill_teach' => session('skill_teach', []),
        'skill_learn' => session('skill_learn', []),
    ]);
})->name('profile');

Route::view('/profile/edit', 'edit-profile')->name('profile.edit');

Route::post('/profile/update', function (Request $request) {
    session([
        'name' => $request->name,
        'username' => $request->username,
        'role' => $request->role,
        'city' => $request->city,
        'skill_teach' => $request->skill_teach ? explode(',', $request->skill_teach) : [],
        'skill_learn' => $request->skill_learn ? explode(',', $request->skill_learn) : [],
    ]);

    return redirect()->route('profile');
})->name('profile.update');


/*
|--------------------------------------------------------------------------
| GOOGLE AUTH PLACEHOLDER
|--------------------------------------------------------------------------
*/

Route::get('/auth/google', fn () => 'Google login belum dibuat')->name('google.redirect');
Route::get('/auth/google/callback', fn () => 'Callback Google belum dibuat');