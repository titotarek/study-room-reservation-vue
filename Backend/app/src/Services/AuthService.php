<?php

namespace App\Services;

use App\Repositories\Interfaces\IUsersRepository;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthService
{
    private IUsersRepository $usersRepository;
    private string $secret;

    public function __construct(IUsersRepository $usersRepository)
    {
        $this->usersRepository = $usersRepository;
        $this->secret = $_ENV['JWT_SECRET'];
    }

    public function register(array $data): int
    {
        if (!isset($data['name'], $data['email'], $data['password'])) {
            throw new \Exception('Missing required fields');
        }

        $existing = $this->usersRepository->findByEmail($data['email']);

        if ($existing) {
            throw new \Exception('Email already exists');
        }

        $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);

        return $this->usersRepository->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password_hash' => $hashedPassword,
            'role' => $data['role'] ?? 'student'
        ]);
    }

    public function login(string $email, string $password): string
    {
        $user = $this->usersRepository->findByEmail($email);

        if (!$user || !password_verify($password, $user['password_hash'])) {
            throw new \Exception('Invalid credentials');
        }

        $payload = [
            'iss' => 'study-room-api',
            'iat' => time(),
            'exp' => time() + 3600,
            'sub' => $user['id'],
            'role' => $user['role']
        ];

        return JWT::encode($payload, $this->secret, 'HS256');
    }

    public function verifyToken(string $token): array
    {
        return (array) JWT::decode($token, new Key($this->secret, 'HS256'));
    }
}