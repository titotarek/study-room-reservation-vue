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
}