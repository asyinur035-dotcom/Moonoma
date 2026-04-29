<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Laravel\Socialite\Facades\Socialite;

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

Route::get('/', fn () => redirect()->route('login'));

Route::view('/login', 'auth.login')->name('login');

Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ], [
        'email.required' => 'Email wajib diisi.',
        'email.email' => 'Format email tidak valid.',
        'password.required' => 'Password wajib diisi.',
    ]);

    $users = getUsers();
    $foundUser = null;

    foreach ($users as $user) {
        if ($user['email'] === $request->email && Illuminate\Support\Facades\Hash::check($request->password, $user['password'])) {
            $foundUser = $user;
            break;
        }
    }

    if (!$foundUser) {
        return back()->withErrors(['email' => 'Email atau password salah'])->withInput();
    }

    $role = $foundUser['email'] === 'moonomaproject@gmail.com' ? 'admin' : ($foundUser['role'] ?? 'Designer');

    session([
        'is_login' => true,
        'email' => $foundUser['email'],
        'name' => $foundUser['name'],
        'role' => $role,
        'avatar' => $foundUser['avatar'] ?? null,
    ]);

    return redirect()->route('home');
})->name('login.process');

Route::view('/register', 'auth.register')->name('register');

Route::post('/register', function (Request $request) {
    $request->validate([
        'name' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:6',
    ], [
        'name.required' => 'Nama wajib diisi.',
        'email.required' => 'Email wajib diisi.',
        'email.email' => 'Format email tidak valid.',
        'password.required' => 'Password wajib diisi.',
        'password.min' => 'Password minimal 6 karakter.',
    ]);

    $users = getUsers();
    foreach ($users as $user) {
        if ($user['email'] === $request->email) {
            return back()->withErrors(['email' => 'Email sudah terdaftar.'])->withInput();
        }
    }

    $otp = rand(100000, 999999);

    session([
        'register_email' => $request->email,
        'register_name' => $request->name,
        'register_password' => $request->password,
        'otp_code' => $otp,
        'otp_type' => 'register',
    ]);

    Mail::raw("Kode verifikasi Moonoma kamu adalah: $otp", function ($message) use ($request) {
        $message->to($request->email)
            ->subject('Kode Verifikasi Moonoma');
    });

    return redirect()
        ->route('verification')
        ->with('success', 'Kode OTP sudah dikirim ke email kamu.');
})->name('register.process');

Route::view('/verification', 'auth.verification')->name('verification');

Route::post('/verification', function (Request $request) {
    $request->validate([
        'otp' => 'required',
    ], [
        'otp.required' => 'Kode OTP wajib diisi.',
    ]);

    if ($request->otp != session('otp_code')) {
        return back()->withErrors([
            'otp' => 'Kode OTP salah.',
        ]);
    }

    if (session('otp_type') === 'register') {
        $users = getUsers();
        
        $emailExists = false;
        foreach ($users as $user) {
            if ($user['email'] === session('register_email')) {
                $emailExists = true;
                break;
            }
        }

        if (!$emailExists) {
            $users[] = [
                'id' => uniqid('user_'),
                'name' => session('register_name'),
                'email' => session('register_email'),
                'password' => Illuminate\Support\Facades\Hash::make(session('register_password')),
                'role' => 'Designer',
                'avatar' => null,
            ];
            saveUsers($users);
        }

        session()->forget([
            'register_email',
            'register_name',
            'register_password',
            'otp_code',
            'otp_type',
        ]);

        return redirect()
            ->route('login')
            ->with('success', 'Verifikasi berhasil! Akun sudah dibuat, silakan login.');
    }

    if (session('otp_type') === 'reset') {
        return redirect()
            ->route('password.reset.form')
            ->with('success', 'Kode benar. Silakan buat password baru.');
    }

    return redirect()->route('login');
})->name('verify.otp');

Route::get('/resend-otp', function () {
    $email = session('register_email') ?? session('reset_email');

    if (!$email) {
        return redirect()->route('login');
    }

    $otp = rand(100000, 999999);

    session([
        'otp_code' => $otp,
    ]);

    Mail::raw("Kode OTP Moonoma kamu adalah: $otp", function ($message) use ($email) {
        $message->to($email)
            ->subject('Kode OTP Moonoma');
    });

    return back()->with('success', 'OTP dikirim ulang!');
})->name('resend.otp');

Route::view('/forgot-password', 'auth.forgot-password')->name('password.request');

Route::post('/forgot-password', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
    ], [
        'email.required' => 'Email wajib diisi.',
        'email.email' => 'Format email tidak valid.',
    ]);

    $otp = rand(100000, 999999);

    session([
        'reset_email' => $request->email,
        'otp_code' => $otp,
        'otp_type' => 'reset',
    ]);

    Mail::raw("Kode reset password Moonoma kamu adalah: $otp", function ($message) use ($request) {
        $message->to($request->email)
            ->subject('Reset Password Moonoma');
    });

    return redirect()
        ->route('verification')
        ->with('success', 'Kode reset password sudah dikirim ke email kamu.');
})->name('password.email');

Route::get('/reset-password', function () {
    if (!session('reset_email')) {
        return redirect()->route('password.request');
    }

    return view('auth.reset-password');
})->name('password.reset.form');

Route::post('/reset-password', function (Request $request) {
    $request->validate([
        'password' => 'required|min:6|confirmed',
    ], [
        'password.required' => 'Password baru wajib diisi.',
        'password.min' => 'Password minimal 6 karakter.',
        'password.confirmed' => 'Konfirmasi password tidak sama.',
    ]);

    session()->forget([
        'reset_email',
        'otp_code',
        'otp_type',
    ]);

    return redirect()
        ->route('login')
        ->with('success', 'Password berhasil direset. Silakan login.');
})->name('password.reset');

Route::get('/logout', function () {
    session()->flush();

    return redirect()->route('login');
})->name('logout');


/*
|--------------------------------------------------------------------------
| JSON DATABASE HELPERS
|--------------------------------------------------------------------------
*/

function jsonFilePath($filename)
{
    $path = storage_path('app/data/' . $filename . '.json');
    if (!file_exists(dirname($path))) {
        mkdir(dirname($path), 0755, true);
    }
    if (!file_exists($path)) {
        file_put_contents($path, json_encode([]));
    }
    return $path;
}

function getUsers()
{
    $data = json_decode(file_get_contents(jsonFilePath('users')), true);
    return is_array($data) ? $data : [];
}

function saveUsers($users)
{
    file_put_contents(jsonFilePath('users'), json_encode(array_values($users), JSON_PRETTY_PRINT));
}

function getChats()
{
    $data = json_decode(file_get_contents(jsonFilePath('chats')), true);
    return is_array($data) ? $data : [];
}

function saveChats($chats)
{
    file_put_contents(jsonFilePath('chats'), json_encode($chats, JSON_PRETTY_PRINT));
}

/*
|--------------------------------------------------------------------------
| HOME
|--------------------------------------------------------------------------
*/

Route::get('/home', function () {
    $rooms = getRooms();
    $userName = session('name');

    $roomsJoined = 0;
    foreach ($rooms as $room) {
        $joined = $room['joined_users'] ?? [];
        $names = array_column($joined, 'name');
        // handle old string format too
        if (empty($names)) {
            $names = array_filter($joined, 'is_string');
        }
        if (in_array($userName, $names)) {
            $roomsJoined++;
        }
    }

    return view('home', compact('roomsJoined'));
})->name('home');
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

    $roomCode = strtoupper(substr(md5($request->name . time()), 0, 6));
    $status = $request->status ?? 'Public';

    $rooms[] = [
        'id' => uniqid('room_'),
        'slug' => $slug,
        'name' => $request->name,
        'topic' => $request->topic,
        'desc' => $request->topic,
        'role_required' => $request->role_required,
        'type' => $request->type ?? 'Coding',
        'status' => $status,
        'member' => 1,
        'code' => $roomCode,
        'created_by' => session('email', 'guest'),
        'created_at' => now()->toDateTimeString(),
    ];

    saveRooms($rooms);

    // If private, show the room code to the creator
    if ($status === 'Private') {
        return redirect()->route('rooms')->with('room_created_private', true)->with('room_code', $roomCode)->with('room_name', $request->name);
    }

    return redirect()->route('rooms')->with('success', 'Room berhasil dibuat!');
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
            if (in_array(session('name'), $room['banned_users'] ?? [])) {
                return back()->with('error', 'Anda telah di-kick dari room ini dan tidak bisa bergabung lagi.');
            }

            $userRole = session('role', session('user_role', ''));

            $isAlreadyJoined = false;
            $joined = $room['joined_users'] ?? [];
            foreach ($joined as $u) {
                $uName = is_array($u) ? ($u['name'] ?? '') : $u;
                if ($uName === session('name')) {
                    $isAlreadyJoined = true;
                    break;
                }
            }

            // Private room: validate code (admin bypasses or user already joined)
            if ($userRole !== 'admin' && ($room['status'] ?? 'Public') === 'Private' && !$isAlreadyJoined) {
                $inputCode = strtoupper(trim(request('room_code', '')));
                if ($inputCode !== ($room['code'] ?? '')) {
                    return back()->with('error', 'Kode room salah! Silakan cek kembali kode yang diberikan.');
                }
            }

            if ($userRole !== 'admin' && !empty($room['role_required']) && strtolower($userRole) !== strtolower($room['role_required'])) {
                return back()->with('error', 'Gagal join! Role kamu (' . ($userRole ?: 'Belum diatur') . ') tidak sesuai dengan requirement: ' . $room['role_required']);
            }

            $room['member'] = ($room['member'] ?? 0) + 1;

            $joined = $room['joined_users'] ?? [];
            $names = array_column($joined, 'name');
            if (!in_array(session('name'), $names)) {
                $joined[] = [
                    'name' => session('name'),
                    'email' => session('email'),
                ];
            }
            $room['joined_users'] = $joined;

            saveRooms($rooms);

            return redirect()->route('chat.room', $slug);
        }
    }

    return back()->with('error', 'Room tidak ditemukan');
})->name('room.join.direct');

Route::post('/rooms/{slug}/delete', function ($slug) {
    if (session('role') !== 'admin') {
        return back()->with('error', 'Akses ditolak. Anda bukan admin.');
    }

    // Delete room from rooms.json
    $rooms = getRooms();
    $rooms = array_filter($rooms, function($r) use ($slug) {
        return ($r['slug'] ?? '') !== $slug;
    });
    saveRooms($rooms);

    // Delete chats for this room
    $chats = getChats();
    if (isset($chats[$slug])) {
        unset($chats[$slug]);
        saveChats($chats);
    }

    return redirect()->route('rooms')->with('success', 'Room berhasil dihapus.');
})->name('room.delete');


/*
|--------------------------------------------------------------------------
| INVITE (JOIN VIA CODE)
|--------------------------------------------------------------------------
*/

Route::get('/invite', function () {
    return view('invite');
})->name('invite.page');

Route::post('/invite/join', function (Request $request) {
    $request->validate([
        'code' => 'required',
    ]);

    $code = strtoupper($request->code);
    $rooms = getRooms();

    foreach ($rooms as &$room) {
        if (($room['code'] ?? '') === $code) {
            if (in_array(session('name'), $room['banned_users'] ?? [])) {
                return back()->with('error', 'Anda telah di-kick dari room ini dan tidak bisa bergabung lagi.');
            }

            $userRole = session('role', session('user_role', ''));
            if ($userRole !== 'admin' && !empty($room['role_required']) && strtolower($userRole) !== strtolower($room['role_required'])) {
                return back()->with('error', 'Gagal join! Role kamu (' . ($userRole ?: 'Belum diatur') . ') tidak sesuai dengan requirement: ' . $room['role_required']);
            }

            $room['member'] = ($room['member'] ?? 0) + 1;

            $joined = $room['joined_users'] ?? [];
            $names = array_column($joined, 'name');
            if (!in_array(session('name'), $names)) {
                $joined[] = [
                    'name' => session('name'),
                    'email' => session('email'),
                ];
            }
            $room['joined_users'] = $joined;

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
    $rooms = getRooms();

    $roomData = collect($rooms)->firstWhere('slug', $room);

    if (!$roomData) {
        return redirect()->route('rooms')->with('error', 'Room tidak ditemukan');
    }

    if (in_array(session('name'), $roomData['banned_users'] ?? [])) {
        return redirect()->route('rooms')->with('error', 'Anda telah di-kick dari room ini.');
    }

    $allChats = getChats();
    $messages = $allChats[$room] ?? [];

    return view('chat', [
        'room' => $room,
        'roomData' => $roomData,
        'messages' => $messages,
    ]);
})->name('chat.room');

Route::post('/chat/{room}/send', function (Request $request, $room) {
    $request->validate([
        'message' => 'required',
    ]);

    $allChats = getChats();
    $messages = $allChats[$room] ?? [];

    $messages[] = [
        'sender' => session('name', 'User'),
        'message' => $request->message,
        'time' => now()->toDateTimeString(),
    ];

    $allChats[$room] = $messages;
    saveChats($allChats);

    return back();
})->name('chat.send');

Route::post('/chat/{room}/kick', function (Request $request, $room) {
    if (session('role') !== 'admin') {
        return back()->with('error', 'Akses ditolak.');
    }

    $targetUser = $request->target_user;
    if (!$targetUser) return back();

    $rooms = getRooms();
    foreach ($rooms as &$r) {
        if (($r['slug'] ?? '') === $room) {
            // Add to banned list
            $banned = $r['banned_users'] ?? [];
            if (!in_array($targetUser, $banned)) {
                $banned[] = $targetUser;
            }
            $r['banned_users'] = $banned;

            // Remove from joined_users list
            $joined = $r['joined_users'] ?? [];
            $joined = array_filter($joined, function($u) use ($targetUser) {
                $name = is_array($u) ? ($u['name'] ?? '') : $u;
                return $name !== $targetUser;
            });
            $r['joined_users'] = array_values($joined);

            break;
        }
    }
    saveRooms($rooms);

    return back()->with('success', "User $targetUser berhasil di-kick.");
})->name('chat.kick');



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
| GOOGLE AUTH
|--------------------------------------------------------------------------
*/

Route::get('/auth/google', function () {
    return Socialite::driver('google')->redirect();
})->name('google.redirect');

Route::get('/auth/google/callback', function () {
    $googleUser = Socialite::driver('google')->user();

    $role = $googleUser->getEmail() === 'moonomaproject@gmail.com' ? 'admin' : 'Member';

    session([
        'is_login' => true,
        'name' => $googleUser->getName(),
        'email' => $googleUser->getEmail(),
        'google_id' => $googleUser->getId(),
        'role' => $role,
    ]);

    return redirect()->route('home');
});