<?php

namespace App\Services;

use Illuminate\Support\Facades\File;

class JsonDatabaseService
{
    protected string $basePath;

    public function __construct()
    {
        $this->basePath = storage_path('app/data');
    }

    protected function getPath(string $file): string
    {
        return $this->basePath . '/' . $file . '.json';
    }

    public function read(string $file): array
    {
        $path = $this->getPath($file);

        if (!File::exists($path)) {
            File::ensureDirectoryExists($this->basePath);
            File::put($path, json_encode([], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }

        $content = File::get($path);
        $data = json_decode($content, true);

        return is_array($data) ? $data : [];
    }

    public function write(string $file, array $data): void
    {
        $path = $this->getPath($file);

        File::ensureDirectoryExists($this->basePath);
        File::put($path, json_encode(array_values($data), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    public function all(string $file): array
    {
        return $this->read($file);
    }

    public function find(string $file, int $id): ?array
    {
        $items = $this->read($file);

        foreach ($items as $item) {
            if ((int) ($item['id'] ?? 0) === $id) {
                return $item;
            }
        }

        return null;
    }

    public function insert(string $file, array $record): array
    {
        $items = $this->read($file);

        $maxId = 0;
        foreach ($items as $item) {
            $itemId = (int) ($item['id'] ?? 0);
            if ($itemId > $maxId) {
                $maxId = $itemId;
            }
        }

        $record['id'] = $maxId + 1;
        $items[] = $record;

        $this->write($file, $items);

        return $record;
    }

    public function update(string $file, int $id, array $newData): ?array
    {
        $items = $this->read($file);

        foreach ($items as $index => $item) {
            if ((int) ($item['id'] ?? 0) === $id) {
                $items[$index] = array_merge($item, $newData);
                $this->write($file, $items);
                return $items[$index];
            }
        }

        return null;
    }

    public function delete(string $file, int $id): bool
    {
        $items = $this->read($file);
        $before = count($items);

        $items = array_filter($items, function ($item) use ($id) {
            return (int) ($item['id'] ?? 0) !== $id;
        });

        if ($before === count($items)) {
            return false;
        }

        $this->write($file, array_values($items));
        return true;
    }
}