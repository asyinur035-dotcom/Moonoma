<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RoomService;

class RoomController extends Controller
{
    public function __construct(protected RoomService $roomService) {}

    public function index()
    {
        $rooms = $this->roomService->all();

        return view('rooms.index', compact('rooms'));
    }

    public function create()
    {
        return view('rooms.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'topic' => 'required|string',
            'description' => 'nullable|string',
            'type' => 'required|in:public,private',
        ]);

        $room = $this->roomService->create($validated, session('user_id'));

        return redirect('/rooms/' . $room['slug']);
    }

    public function show(string $slug)
    {
        $room = $this->roomService->findBySlug($slug);

        if (!$room) {
            abort(404);
        }

        return view('rooms.show', compact('room'));
    }
}