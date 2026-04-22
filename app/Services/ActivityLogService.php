<?php

namespace App\Services;

class ActivityLogService
{
    public function __construct(
        protected JsonDatabaseService $jsonDb
    ) {}

    public function all(): array
    {
        $logs = $this->jsonDb->all('activity_logs');

        usort($logs, function ($a, $b) {
            return strcmp($b['created_at'] ?? '', $a['created_at'] ?? '');
        });

        return $logs;
    }

    public function log(?int $userId, string $action, ?string $targetType = null, ?int $targetId = null, array $metadata = []): array
    {
        return $this->jsonDb->insert('activity_logs', [
            'user_id' => $userId,
            'action' => $action,
            'target_type' => $targetType,
            'target_id' => $targetId,
            'metadata' => $metadata,
            'created_at' => now()->toDateTimeString(),
        ]);
    }
}