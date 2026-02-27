<?php

namespace App\Controllers;

class BaseController
{
    protected function json($data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }

    protected function success($data = null, string $message = 'Success', int $statusCode = 200): void
    {
        $this->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }

    protected function error(string $message = 'Error', int $statusCode = 400): void
    {
        $this->json([
            'status' => 'error',
            'message' => $message
        ], $statusCode);
    }
}