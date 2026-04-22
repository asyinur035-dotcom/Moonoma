<?php

namespace App\Services;

use Illuminate\Support\Str;

class RoomService
{
    public function __construct(
        protected JsonDatabaseService $jsonDb
    ) {}

    public function all(): array
    {
        $rooms = $this->jsonDb->all('rooms');

        usort($rooms, function ($a, $b) {
            return strcmp($b['created_at'] ?? '', $a['created_at'] ?? '');
        });

        return $rooms;
    }

    public function findById(int $id): ?array
    {
        return $this->jsonDb->find('rooms', $id);
    }

    public function findBySlug(string $slug): ?array
    {
        $rooms = $this->jsonDb->all('rooms');

        foreach ($rooms as $room) {
            if (($room['slug'] ?? null) === $slug) {
                return $room;
            }
        }

        return null;
    }

    public function findByCode(string $code): ?array
    {
        $rooms = $this->jsonDb->all('rooms');

        foreach ($rooms as $room) {
            if (($room['code'] ?? null) === $code) {
                return $room;
            }
        }

        return null;
    }

    public function create(array $data, int $ownerId): array
    {
        $now = now()->toDateTimeString();
        $slug = Str::slug($data['name']) . '-' . strtolower(Str::random(4));

        $room = $this->jsonDb->insert('rooms', [
            'owner_id' => $ownerId,
            'name' => $data['name'],
            'slug' => $slug,
            'topic' => $data['topic'],
            'description' => $data['description'] ?? null,
            'type' => $data['type'],
            'code' => strtoupper(Str::random(6)),
            'invite_token' => Str::random(24),
            'status' => 'active',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $this->jsonDb->insert('room_members', [
            'room_id' => $room['id'],
            'user_id' => $ownerId,
            'member_role' => 'owner',
            'joined_at' => $now,
        ]);

        return $room;
    }

    public function getMembers(int $roomId): array
    {
        $members = $this->jsonDb->all('room_members');

        return array_values(array_filter($members, function ($member) use ($roomId) {
            return (int) ($member['room_id'] ?? 0) === $roomId;
        }));
    }

    public function isMember(int $roomId, int $userId): bool
    {
        $members = $this->getMembers($roomId);

        foreach ($members as $member) {
            if ((int) ($member['user_id'] ?? 0) === $userId) {
                return true;
            }
        }

        return false;
    }

    public function join(int $roomId, int $userId): array
    {
        $room = $this->findById($roomId);

        if (!$room) {
            throw new \Exception('Room tidak ditemukan.');
        }

        if ($this->isMember($roomId, $userId)) {
            throw new \Exception('User sudah tergabung di room ini.');
        }

        if (($room['type'] ?? 'public') === 'private') {
            return $this->requestJoin($roomId, $userId);
        }

        return $this->jsonDb->insert('room_members', [
            'room_id' => $roomId,
            'user_id' => $userId,
            'member_role' => 'member',
            'joined_at' => now()->toDateTimeString(),
        ]);
    }

    public function joinByCode(string $code, int $userId): array
    {
        $room = $this->findByCode($code);

        if (!$room) {
            throw new \Exception('Kode room tidak valid.');
        }

        return $this->join((int) $room['id'], $userId);
    }

    public function requestJoin(int $roomId, int $userId): array
    {
        $requests = $this->jsonDb->all('join_requests');

        foreach ($requests as $request) {
            if (
                (int) ($request['room_id'] ?? 0) === $roomId &&
                (int) ($request['user_id'] ?? 0) === $userId &&
                ($request['status'] ?? null) === 'pending'
            ) {
                throw new \Exception('Permintaan join masih pending.');
            }
        }

        return $this->jsonDb->insert('join_requests', [
            'room_id' => $roomId,
            'user_id' => $userId,
            'status' => 'pending',
            'created_at' => now()->toDateTimeString(),
            'updated_at' => now()->toDateTimeString(),
        ]);
    }
}