<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MessageService;

class MessageController extends Controller
{
    public function __construct(protected MessageService $messageService) {}

    public function index(int $roomId)
    {
        $messages = $this->messageService->getByRoom($roomId);

        return response()->json($messages);
    }

    public function store(Request $request, int $roomId)
    {
        $validated = $request->validate([
            'message' => 'required|string',
        ]);

        $message = $this->messageService->send(
            $roomId,
            session('user_id'),
            $validated['message']
        );

        return response()->json($message);
    }
}