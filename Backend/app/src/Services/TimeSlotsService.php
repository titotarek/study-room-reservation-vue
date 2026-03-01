<?php

namespace App\Services;

use App\Repositories\Interfaces\ITimeSlotsRepository;

class TimeSlotsService
{
    private ITimeSlotsRepository $repository;

    public function __construct(ITimeSlotsRepository $repository)
    {
        $this->repository = $repository;
    }

    public function createTimeSlot(array $data): int
    {
        if (
            !isset($data['room_id']) ||
            !isset($data['day_of_week']) ||
            !isset($data['start_time']) ||
            !isset($data['end_time'])
        ) {
            throw new \Exception('Missing required fields');
        }

        if (strtotime($data['start_time']) >= strtotime($data['end_time'])) {
            throw new \Exception('Start time must be before end time');
        }

        return $this->repository->create($data);
    }

    public function getAll(): array
    {
        return $this->repository->findAll();
    }

    public function getById(int $id): ?array
    {
        return $this->repository->findById($id);
    }

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }
}