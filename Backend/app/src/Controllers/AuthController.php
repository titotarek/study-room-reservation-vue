<?php

namespace App\Controllers;

use App\Services\AuthService;
use App\Repositories\UsersRepository;

class AuthController extends BaseController
{
    private AuthService $authService;

    public function __construct()
    {
        $repository = new UsersRepository();
        $this->authService = new AuthService($repository);
    }

    public function register(): void
    {
        try {
            $data = json_decode(file_get_contents("php://input"), true);

            $userId = $this->authService->register($data);

            $this->success(['id' => $userId], 'User registered successfully', 201);

        } catch (\Exception $e) {
            $this->error($e->getMessage(), 400);
        }
    }

    public function login(): void
    {
        try {
            $data = json_decode(file_get_contents("php://input"), true);

            if (!isset($data['email'], $data['password'])) {
                $this->error('Missing credentials', 400);
            }

            $token = $this->authService->login(
                $data['email'],
                $data['password']
            );

            $this->success(['token' => $token], 'Login successful');

        } catch (\Exception $e) {
            $this->error($e->getMessage(), 401);
        }
    }
}