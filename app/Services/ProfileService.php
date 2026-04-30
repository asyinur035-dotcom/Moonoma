<?php

namespace App\Services;

class ProfileService
{
    public function __construct(
        protected JsonDatabaseService $jsonDb
    ) {}

    public function getByUserId(int $userId): ?array
    {
        $profiles = $this->jsonDb->all('profiles');

        foreach ($profiles as $profile) {
            if ((int) ($profile['user_id'] ?? 0) === $userId) {
                return $profile;
            }
        }

        return null;
    }

    public function createDefault(int $userId): array
    {
        $existing = $this->getByUserId($userId);

        if ($existing) {
            return $existing;
        }

        $now = now()->toDateTimeString();

        return $this->jsonDb->insert('profiles', [
            'user_id' => $userId,
            'bio' => null,
            'skill_teach' => [],
            'skill_learn' => [],
            'school_name' => null,
            'class_name' => null,
            'city' => null,
            'portfolio_url' => null,
            'cv_path' => null,
            'availability' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }

    public function updateByUserId(int $userId, array $data): array
    {
        $profile = $this->getByUserId($userId);

        if (!$profile) {
            $profile = $this->createDefault($userId);
        }

        $updateData = [
            'bio' => $data['bio'] ?? $profile['bio'],
            'skill_teach' => is_string($data['skill_teach'] ?? null) ? array_values(array_filter(array_map('trim', explode(',', $data['skill_teach'])))) : ($data['skill_teach'] ?? $profile['skill_teach']),
            'skill_learn' => is_string($data['skill_learn'] ?? null) ? array_values(array_filter(array_map('trim', explode(',', $data['skill_learn'])))) : ($data['skill_learn'] ?? $profile['skill_learn']),
            'school_name' => $data['school_name'] ?? $profile['school_name'],
            'class_name' => $data['class_name'] ?? $profile['class_name'],
            'city' => $data['city'] ?? $profile['city'],
            'portfolio_url' => $data['portfolio_url'] ?? $profile['portfolio_url'],
            'cv_path' => $data['cv_path'] ?? $profile['cv_path'],
            'availability' => $data['availability'] ?? $profile['availability'],
            'updated_at' => now()->toDateTimeString(),
        ];

        return $this->jsonDb->update('profiles', (int) $profile['id'], $updateData);
    }
}