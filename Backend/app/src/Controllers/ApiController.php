<?php

namespace App\Controllers;

class ApiController extends BaseController
{
    public function index(): void
    {
        $this->success(
            null,
            'Study Room Reservation API is running'
        );
    }
}