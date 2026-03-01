<?php

namespace App\Middleware;

use App\Services\AuthService;
use App\Repositories\UsersRepository;

class AuthMiddleware
{
    private AuthService $authService;

    public function __construct()
    {
        $repository = new UsersRepository();
        $this->authService = new AuthService($repository);
    }

    public function handle(?string $requiredRole = null): array
    {
        $headers = getallheaders();

        if (!isset($headers['Authorization'])) {
            $this->unauthorized('Missing Authorization header');
        }

        if (!preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches)) {
            $this->unauthorized('Invalid Authorization format');
        }

        $token = $matches[1];

        try {
            $decoded = $this->authService->verifyToken($token);
        } catch (\Exception $e) {
            $this->unauthorized('Invalid or expired token');
        }

        if ($requiredRole && ($decoded['role'] ?? null) !== $requiredRole) {
            $this->unauthorized('Forbidden: insufficient permissions', 403);
        }

        return $decoded;
    }

    private function unauthorized(string $message, int $code = 401): void
    {
        http_response_code($code);
        echo json_encode([
            'status' => 'error',
            'message' => $message
        ]);
        exit;
    }
}