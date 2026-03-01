<?php

namespace App\Repositories;

use App\Core\Database;
use App\Repositories\Interfaces\ITimeSlotsRepository;
use PDO;

class TimeSlotsRepository implements ITimeSlotsRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO time_slots (room_id, day_of_week, start_time, end_time)
            VALUES (:room_id, :day_of_week, :start_time, :end_time)
        ");

        $stmt->execute([
            'room_id' => $data['room_id'],
            'day_of_week' => $data['day_of_week'],
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time']
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM time_slots");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM time_slots WHERE id = :id");
        $stmt->execute(['id' => $id]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ?: null;
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM time_slots WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}