<?php

namespace App\Repositories;

use App\Core\Database;
use App\Repositories\Interfaces\IRoomsRepository;
use PDO;

class RoomsRepository implements IRoomsRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    public function getAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM rooms");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM rooms WHERE id = :id");
        $stmt->execute(['id' => $id]);

        $room = $stmt->fetch(PDO::FETCH_ASSOC);

        return $room ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO rooms (room_number, building, capacity, equipment)
            VALUES (:room_number, :building, :capacity, :equipment)
        ");

        $stmt->execute([
            'room_number' => $data['room_number'],
            'building' => $data['building'],
            'capacity' => $data['capacity'],
            'equipment' => $data['equipment'] ?? null,
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE rooms
            SET room_number = :room_number,
                building = :building,
                capacity = :capacity,
                equipment = :equipment
            WHERE id = :id
        ");

        return $stmt->execute([
            'id' => $id,
            'room_number' => $data['room_number'],
            'building' => $data['building'],
            'capacity' => $data['capacity'],
            'equipment' => $data['equipment'] ?? null,
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM rooms WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}