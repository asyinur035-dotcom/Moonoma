<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;

class AuthController extends Controller
{
    public function __construct(protected UserService $userService) {}

    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'username' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|min:8',
            'major' => 'required|in:TKJ,RPL,DKV',
        ]);

        $user = $this->userService->register($validated);

        session([
            'user_id' => $user['id'],
            'user_name' => $user['name'],
            'user_role' => $user['role'],
        ]);

        return redirect('/rooms');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = $this->userService->authenticate(
            $validated['email'],
            $validated['password']
        );

        if (!$user) {
            return back()->withErrors([
                'email' => 'Email atau password salah'
            ]);
        }

        session([
            'user_id' => $user['id'],
            'user_name' => $user['name'],
            'user_role' => $user['role'],
        ]);

        return redirect('/rooms');
    }

    public function logout()
    {
        session()->flush();
        return redirect('/login');
    }
}