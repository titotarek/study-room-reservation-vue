<?php

namespace App\Repositories\Interfaces;

interface IUsersRepository
{
    public function findByEmail(string $email): ?array;

    public function create(array $data): int;

    public function findById(int $id): ?array;
}