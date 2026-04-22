<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;

class UserService
{
    public function __construct(
        protected JsonDatabaseService $jsonDb,
        protected ProfileService $profileService
    ) {}

    public function all(): array
    {
        return $this->jsonDb->all('users');
    }

    public function findById(int $id): ?array
    {
        return $this->jsonDb->find('users', $id);
    }

    public function findByEmail(string $email): ?array
    {
        $users = $this->jsonDb->all('users');

        foreach ($users as $user) {
            if (($user['email'] ?? null) === $email) {
                return $user;
            }
        }

        return null;
    }

    public function findByUsername(string $username): ?array
    {
        $users = $this->jsonDb->all('users');

        foreach ($users as $user) {
            if (($user['username'] ?? null) === $username) {
                return $user;
            }
        }

        return null;
    }

    public function register(array $data): array
    {
        if ($this->findByEmail($data['email'])) {
            throw new \Exception('Email sudah terdaftar.');
        }

        if ($this->findByUsername($data['username'])) {
            throw new \Exception('Username sudah terdaftar.');
        }

        $now = now()->toDateTimeString();

        $user = $this->jsonDb->insert('users', [
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'major' => $data['major'] ?? null,
            'role' => $data['role'] ?? 'user',
            'google_id' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $this->profileService->createDefault($user['id']);

        return $user;
    }

    public function authenticate(string $email, string $password): ?array
    {
        $user = $this->findByEmail($email);

        if (!$user) {
            return null;
        }

        if (!Hash::check($password, $user['password'])) {
            return null;
        }

        return $user;
    }

    public function delete(int $id): bool
    {
        return $this->jsonDb->delete('users', $id);
    }
}