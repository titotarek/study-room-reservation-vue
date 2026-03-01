<?php

namespace App\Repositories;

use App\Core\Database;
use App\Repositories\Interfaces\IReservationsRepository;
use PDO;

class ReservationsRepository implements IReservationsRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO reservations (user_id, room_id, time_slot_id, date, num_people)
            VALUES (:user_id, :room_id, :time_slot_id, :date, :num_people)
        ");

        $stmt->execute([
            'user_id' => $data['user_id'],
            'room_id' => $data['room_id'],
            'time_slot_id' => $data['time_slot_id'],
            'date' => $data['date'],
            'num_people' => $data['num_people'] ?? 1
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    public function findByUserId(int $userId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM reservations WHERE user_id = :user_id
        ");

        $stmt->execute(['user_id' => $userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete(int $id, int $userId): bool
    {
        $stmt = $this->pdo->prepare("
            DELETE FROM reservations 
            WHERE id = :id AND user_id = :user_id
        ");

        return $stmt->execute([
            'id' => $id,
            'user_id' => $userId
        ]);
    }
}