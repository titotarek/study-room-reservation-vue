<?php

use App\Controllers\ApiController;
use App\Controllers\RoomsController;

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