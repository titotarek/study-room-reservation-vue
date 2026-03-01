<?php

namespace App\Services;

use App\Repositories\Interfaces\IReservationsRepository;

class ReservationsService
{
    private IReservationsRepository $reservationsRepository;

    public function __construct(IReservationsRepository $reservationsRepository)
    {
        $this->reservationsRepository = $reservationsRepository;
    }

    public function createReservation(array $data, int $userId): int
    {
        if (
            !isset($data['room_id']) ||
            !isset($data['time_slot_id']) ||
            !isset($data['date'])
        ) {
            throw new \Exception('Missing required fields');
        }

        // Basic validation example
        if (strtotime($data['date']) < strtotime(date('Y-m-d'))) {
            throw new \Exception('Cannot book in the past');
        }

        return $this->reservationsRepository->create([
            'user_id' => $userId,
            'room_id' => $data['room_id'],
            'time_slot_id' => $data['time_slot_id'],
            'date' => $data['date'],
            'num_people' => $data['num_people'] ?? 1
        ]);
    }

    public function getUserReservations(int $userId): array
    {
        return $this->reservationsRepository->findByUserId($userId);
    }

    public function cancelReservation(int $id, int $userId): bool
    {
        return $this->reservationsRepository->delete($id, $userId);
    }
}