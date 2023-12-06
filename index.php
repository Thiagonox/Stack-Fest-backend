<?php
require __DIR__ . '/vendor/autoload.php';

use MareaTurbo\Router;
use Stack\Fest\Controller\CompanyController;
use Stack\Fest\Controller\EventController;
use Stack\Fest\Controller\EventTimeController;
use Stack\Fest\Controller\TicketController;
use Stack\Fest\Controller\UserController;


(new Router())->controllers([
    UserController::class,
    CompanyController::class,
    EventController::class,
    EventTimeController::class,
    TicketController::class
]);