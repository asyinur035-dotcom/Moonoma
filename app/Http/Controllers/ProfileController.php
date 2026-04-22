<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ProfileService;

class ProfileController extends Controller
{
    public function __construct(protected ProfileService $profileService) {}

    public function show()
    {
        $profile = $this->profileService->getByUserId(session('user_id'));

        return view('profile.show', compact('profile'));
    }

    public function edit()
    {
        $profile = $this->profileService->getByUserId(session('user_id'));

        return view('profile.edit', compact('profile'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'bio' => 'nullable|string',
            'school_name' => 'nullable|string',
            'class_name' => 'nullable|string',
            'city' => 'nullable|string',
        ]);

        $this->profileService->updateByUserId(session('user_id'), $validated);

        return redirect('/profile')->with('success', 'Profil berhasil diperbarui');
    }
}