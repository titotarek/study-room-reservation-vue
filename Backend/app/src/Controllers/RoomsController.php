<?php

namespace App\Controllers;

use App\Services\RoomsService;
use App\Repositories\RoomsRepository;

class RoomsController extends BaseController
{
    private RoomsService $roomsService;

    public function __construct()
    {
        $repository = new RoomsRepository();
        $this->roomsService = new RoomsService($repository);
    }

    public function index(): void
    {
        $rooms = $this->roomsService->getAllRooms();

        $this->success($rooms, 'Rooms fetched successfully');
    }

    public function show(int $id): void
    {
        $room = $this->roomsService->getRoomById($id);

        if (!$room) {
            $this->error('Room not found', 404);
        }

        $this->success($room, 'Room fetched successfully');
    }


    public function store(): void
{
    $data = json_decode(file_get_contents("php://input"), true);

    if (
        !isset($data['room_number']) ||
        !isset($data['building']) ||
        !isset($data['capacity'])
    ) {
        $this->error('Missing required fields', 400);
    }

    $newId = $this->roomsService->createRoom($data);

    $this->success(['id' => $newId], 'Room created successfully', 201);
}

public function update(int $id): void
{
    $data = json_decode(file_get_contents("php://input"), true);

    $updated = $this->roomsService->updateRoom($id, $data);

    if (!$updated) {
        $this->error('Room not found or not updated', 404);
    }

    $this->success(null, 'Room updated successfully');
}

public function destroy(int $id): void
{
    $deleted = $this->roomsService->deleteRoom($id);

    if (!$deleted) {
        $this->error('Room not found or not deleted', 404);
    }

    $this->success(null, 'Room deleted successfully');
}
}