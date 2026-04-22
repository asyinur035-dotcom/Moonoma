<?php

namespace App\Services;

class MessageService
{
    public function __construct(
        protected JsonDatabaseService $jsonDb,
        protected RoomService $roomService
    ) {}

    public function getByRoom(int $roomId): array
    {
        $messages = $this->jsonDb->all('messages');

        $messages = array_values(array_filter($messages, function ($message) use ($roomId) {
            return (int) ($message['room_id'] ?? 0) === $roomId;
        }));

        usort($messages, function ($a, $b) {
            return strcmp($a['created_at'] ?? '', $b['created_at'] ?? '');
        });

        return $messages;
    }

    public function send(int $roomId, int $userId, string $message): array
    {
        $room = $this->roomService->findById($roomId);

        if (!$room) {
            throw new \Exception('Room tidak ditemukan.');
        }

        if (!$this->roomService->isMember($roomId, $userId)) {
            throw new \Exception('User bukan anggota room ini.');
        }

        return $this->jsonDb->insert('messages', [
            'room_id' => $roomId,
            'user_id' => $userId,
            'message' => $message,
            'type' => 'text',
            'attachment_path' => null,
            'created_at' => now()->toDateTimeString(),
        ]);
    }
}