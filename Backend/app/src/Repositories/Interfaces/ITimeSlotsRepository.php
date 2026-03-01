<?php

namespace App\Repositories\Interfaces;

interface ITimeSlotsRepository
{
    public function create(array $data): int;
    public function findAll(): array;
    public function findById(int $id): ?array;
    public function delete(int $id): bool;
}