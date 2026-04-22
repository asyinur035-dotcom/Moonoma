<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.process');

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

Route::get('/rooms', [RoomController::class, 'index'])->name('rooms');
Route::get('/rooms/create', [RoomController::class, 'create'])->name('room.create');
Route::post('/rooms/store', [RoomController::class, 'store'])->name('room.store');
Route::get('/rooms/{slug}', [RoomController::class, 'show'])->name('rooms.show');

Route::post('/rooms/{roomId}/join', [RoomController::class, 'join'])->name('rooms.join');
Route::post('/rooms/join/code', [RoomController::class, 'joinByCode'])->name('rooms.join.code');

Route::get('/search', [RoomController::class, 'search'])->name('search');

Route::get('/chat', function () {
    return redirect()->route('rooms');
})->name('chat');

Route::get('/chat/{roomId}', [MessageController::class, 'room'])->name('chat.room');
Route::get('/rooms/{roomId}/messages', [MessageController::class, 'index'])->name('rooms.messages.index');
Route::post('/rooms/{roomId}/messages', [MessageController::class, 'store'])->name('rooms.messages.store');

Route::get('/invite', function () {
    return view('invite');
})->name('invite.page');

Route::get('/chat/{roomId}/invite', [RoomController::class, 'invite'])->name('chat.invite');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
Route::get('/admin/rooms', [AdminController::class, 'rooms'])->name('admin.rooms');
Route::get('/admin/activity', [AdminController::class, 'activity'])->name('admin.activity');

Route::post('/admin/users/delete/{id}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');
Route::post('/admin/rooms/delete/{id}', [AdminController::class, 'deleteRoom'])->name('admin.rooms.delete');

/*
|--------------------------------------------------------------------------
| Google Auth Placeholder
|--------------------------------------------------------------------------
*/
Route::get('/auth/google', function () {
    return 'Google login belum dibuat';
})->name('google.redirect');

Route::get('/auth/google/callback', function () {
    return 'Callback Google belum dibuat';
});