<?php

namespace App\Http\Controllers;

use App\Services\JsonDatabaseService;

class AdminController extends Controller
{
    protected JsonDatabaseService $jsonDb;

    public function __construct(JsonDatabaseService $jsonDb)
    {
        $this->jsonDb = $jsonDb;
    }

    /**
     * Dashboard admin
     */
    public function dashboard()
    {
        if ($redirect = $this->requireLogin()) return $redirect;
        $this->requireAdmin();

        $users = $this->jsonDb->all('users');
        $rooms = $this->jsonDb->all('rooms');
        $messages = $this->jsonDb->all('messages');

        return view('admin.dashboard', [
            'totalUsers' => count($users),
            'totalRooms' => count($rooms),
            'totalMessages' => count($messages),
        ]);
    }

    /**
     * List semua user
     */
    public function users()
    {
        if ($redirect = $this->requireLogin()) return $redirect;
        $this->requireAdmin();

        $users = $this->jsonDb->all('users');

        return view('admin.users', compact('users'));
    }

    /**
     * List semua room
     */
    public function rooms()
    {
        if ($redirect = $this->requireLogin()) return $redirect;
        $this->requireAdmin();

        $rooms = $this->jsonDb->all('rooms');

        return view('admin.rooms', compact('rooms'));
    }

    /**
     * Hapus user (opsional)
     */
    public function deleteUser(int $id)
    {
        if ($redirect = $this->requireLogin()) return $redirect;
        $this->requireAdmin();

        $this->jsonDb->delete('users', $id);

        return redirect('/admin/users')->with('success', 'User dihapus');
    }

    /**
     * Hapus room
     */
    public function deleteRoom(int $id)
    {
        if ($redirect = $this->requireLogin()) return $redirect;
        $this->requireAdmin();

        $this->jsonDb->delete('rooms', $id);

        return redirect('/admin/rooms')->with('success', 'Room dihapus');
    }

    /**
     * Activity log
     */
    public function activity()
    {
        if ($redirect = $this->requireLogin()) return $redirect;
        $this->requireAdmin();

        $logs = $this->jsonDb->all('activity_logs');

        return view('admin.activity', compact('logs'));
    }
}