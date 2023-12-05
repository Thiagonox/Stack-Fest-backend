<?php

use Stack\Fest\Config\Router;
use Stack\Fest\Controller\CompanyController;
use Stack\Fest\Controller\EventController;
use Stack\Fest\Controller\UserController;

require __DIR__ . '/vendor/autoload.php';

(new Router())->controllers([
    UserController::class,
    CompanyController::class,
    EventController::class
]);