<?php

namespace App\Http\Controllers;

abstract class Controller
{
    /**
     * Ambil user login dari session (JSON auth)
     */
    protected function user()
    {
        return [
            'id' => session('user_id'),
            'name' => session('user_name'),
            'role' => session('user_role'),
        ];
    }

    /**
     * Cek apakah user login
     */
    protected function isLoggedIn(): bool
    {
        return session()->has('user_id');
    }

    /**
     * Cek apakah admin
     */
    protected function isAdmin(): bool
    {
        return session('user_role') === 'admin';
    }

    /**
     * Redirect kalau belum login
     */
    protected function requireLogin()
    {
        if (!$this->isLoggedIn()) {
            return redirect('/login');
        }
    }

    /**
     * Abort kalau bukan admin
     */
    protected function requireAdmin()
    {
        if (!$this->isAdmin()) {
            abort(403, 'Akses ditolak');
        }
    }
}