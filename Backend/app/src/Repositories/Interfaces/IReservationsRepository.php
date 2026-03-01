<?php

namespace App\Repositories\Interfaces;

interface IReservationsRepository
{
    public function create(array $data): int;

    public function findByUserId(int $userId): array;

    public function delete(int $id, int $userId): bool;
}