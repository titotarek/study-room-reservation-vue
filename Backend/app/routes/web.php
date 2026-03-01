<?php

use App\Controllers\ApiController;
use App\Controllers\RoomsController;
use App\Controllers\AuthController;
use App\Middleware\AuthMiddleware;
use App\Controllers\ReservationsController;
use App\Controllers\TimeSlotsController;

/* RESERVATIONS (STUDENT) */

$router->add('GET', '/reservations', function () {
    (new ReservationsController())->index();
});

$router->add('POST', '/reservations', function () {
    (new ReservationsController())->store();
});

$router->add('DELETE', '/reservations/{id}', function ($id) {
    (new ReservationsController())->destroy((int) $id);
});


/** @var \App\Core\Router $router */

$router->add('GET', '/', function () {
    (new ApiController())->index();
});

$router->add('GET', '/rooms', function () {
    (new RoomsController())->index();
});

$router->add('GET', '/rooms/{id}', function ($id) {
    (new RoomsController())->show((int) $id);
});

/* 🔐 PROTECTED ROUTES (ADMIN ONLY) */

$router->add('POST', '/rooms', function () {
    (new AuthMiddleware())->handle('admin');
    (new RoomsController())->store();
});

$router->add('PUT', '/rooms/{id}', function ($id) {
    (new AuthMiddleware())->handle('admin');
    (new RoomsController())->update((int) $id);
});

$router->add('DELETE', '/rooms/{id}', function ($id) {
    (new AuthMiddleware())->handle('admin');
    (new RoomsController())->destroy((int) $id);
});

/* AUTH ROUTES */

$router->add('POST', '/register', function () {
    (new AuthController())->register();
});

$router->add('POST', '/login', function () {
    (new AuthController())->login();
});


$router->add('GET', '/reservations', function () {
    (new ReservationsController())->index();
});

$router->add('POST', '/reservations', function () {
    (new ReservationsController())->store();
});

$router->add('DELETE', '/reservations/{id}', function ($id) {
    (new ReservationsController())->destroy((int) $id);
});


/* TIME SLOTS */

$router->add('GET', '/time-slots', function () {
    (new TimeSlotsController())->index();
});

$router->add('GET', '/time-slots/{id}', function ($id) {
    (new TimeSlotsController())->show((int) $id);
});

$router->add('POST', '/time-slots', function () {
    (new TimeSlotsController())->store();
});

$router->add('DELETE', '/time-slots/{id}', function ($id) {
    (new TimeSlotsController())->destroy((int) $id);
});