<?php

namespace App\Controllers;

use App\Services\TimeSlotsService;
use App\Repositories\TimeSlotsRepository;
use App\Middleware\AuthMiddleware;

class TimeSlotsController extends BaseController
{
    private TimeSlotsService $service;

    public function __construct()
    {
        $repository = new TimeSlotsRepository();
        $this->service = new TimeSlotsService($repository);
    }

    public function index(): void
    {
        $slots = $this->service->getAll();
        $this->success($slots, 'Time slots fetched successfully');
    }

    public function show(int $id): void
    {
        $slot = $this->service->getById($id);

        if (!$slot) {
            $this->error('Time slot not found', 404);
        }

        $this->success($slot, 'Time slot fetched successfully');
    }

    public function store(): void
    {
        try {
            // Admin only
            (new AuthMiddleware())->handle('admin');

            $data = json_decode(file_get_contents("php://input"), true);

            $id = $this->service->createTimeSlot($data);

            $this->success(['id' => $id], 'Time slot created successfully', 201);

        } catch (\Exception $e) {
            $this->error($e->getMessage(), 400);
        }
    }

    public function destroy(int $id): void
    {
        try {
            // Admin only
            (new AuthMiddleware())->handle('admin');

            $deleted = $this->service->delete($id);

            if (!$deleted) {
                $this->error('Time slot not found', 404);
            }

            $this->success(null, 'Time slot deleted successfully');

        } catch (\Exception $e) {
            $this->error($e->getMessage(), 401);
        }
    }
}