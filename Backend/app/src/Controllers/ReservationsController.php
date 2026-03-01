<?php

namespace App\Controllers;

use App\Services\ReservationsService;
use App\Repositories\ReservationsRepository;
use App\Middleware\AuthMiddleware;

class ReservationsController extends BaseController
{
    private ReservationsService $reservationsService;

    public function __construct()
    {
        $repository = new ReservationsRepository();
        $this->reservationsService = new ReservationsService($repository);
    }

    public function store(): void
    {
        try {
            $decoded = (new AuthMiddleware())->handle(); // any logged-in user

            $data = json_decode(file_get_contents("php://input"), true);

            $reservationId = $this->reservationsService->createReservation(
                $data,
                $decoded['sub']
            );

            $this->success(['id' => $reservationId], 'Reservation created successfully', 201);

        } catch (\Exception $e) {
            $this->error($e->getMessage(), 400);
        }
    }

    public function index(): void
    {
        try {
            $decoded = (new AuthMiddleware())->handle(); // logged-in user

            $reservations = $this->reservationsService->getUserReservations(
                $decoded['sub']
            );

            $this->success($reservations, 'Reservations fetched successfully');

        } catch (\Exception $e) {
            $this->error($e->getMessage(), 401);
        }
    }

    public function destroy(int $id): void
    {
        try {
            $decoded = (new AuthMiddleware())->handle(); // logged-in user

            $deleted = $this->reservationsService->cancelReservation(
                $id,
                $decoded['sub']
            );

            if (!$deleted) {
                $this->error('Reservation not found or not allowed', 404);
            }

            $this->success(null, 'Reservation cancelled successfully');

        } catch (\Exception $e) {
            $this->error($e->getMessage(), 401);
        }
    }
}