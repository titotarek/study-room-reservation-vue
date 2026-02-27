<?php

namespace App\Services;

use App\Repositories\Interfaces\IRoomsRepository;

class RoomsService
{
    private IRoomsRepository $roomsRepository;

    public function __construct(IRoomsRepository $roomsRepository)
    {
        $this->roomsRepository = $roomsRepository;
    }

    public function getAllRooms(): array
    {
        return $this->roomsRepository->getAll();
    }

    public function getRoomById(int $id): ?array
    {
        return $this->roomsRepository->getById($id);
    }

    public function createRoom(array $data): int
    {
        // Example business logic placeholder
        // (you can add validation rules here later)

        return $this->roomsRepository->create($data);
    }

    public function updateRoom(int $id, array $data): bool
    {
        return $this->roomsRepository->update($id, $data);
    }

    public function deleteRoom(int $id): bool
    {
        return $this->roomsRepository->delete($id);
    }
}