<?php

namespace Stack\Fest\Controller;

use Stack\Fest\Model\DAO\EventDAO;
use Stack\Fest\Model\Service\AuthService;

class EventController
{
    public function __construct(
        protected AuthService $authService,
        protected EventDAO  $eventDAO
    )
    {
    }
}