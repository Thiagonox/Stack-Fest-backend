<?php

namespace Stack\Fest\Controller;

use DateTime;
use Exception;
use MareaTurbo\Route;
use Stack\Fest\Config\Request;
use Stack\Fest\Config\Response;
use Stack\Fest\Model\DAO\EventDAO;
use Stack\Fest\Model\DAO\EventTimeDAO;
use Stack\Fest\Model\Entity\EventTime;
use Stack\Fest\Model\Service\AuthService;

class EventTimeController
{
    public function __construct(
        protected AuthService  $authService,
        protected EventDAO     $eventDAO,
        protected EventTimeDAO $eventTimeDAO,
        protected Request      $request,
        protected Response     $response
    )
    {
    }

    #[Route("/api/v1/events/{id}/times", "GET")]
    public function getEventTimes($parameters)
    {
        try {
            $this->authService->isUserAuthenticated();

            $event = $this->eventDAO->getEventById($parameters->id);
            if (!$event) {
                throw new Exception('Event not found');
            }

            $eventTimes = $this->eventTimeDAO->getEventTimes($parameters->id);
            $this->response->json(200, ['eventTimes' => $eventTimes]);
        } catch (Exception $e) {
            $this->response->json(500, ['error' => $e->getMessage()]);
        }
    }

    #[Route("/api/v1/events/{id}/times/{timeId}", "GET")]
    public function getTimeEventById($parameters)
    {
        try {
            $this->authService->isUserAuthenticated();

            $event = $this->eventDAO->getEventById($parameters->id);
            if (!$event) {
                throw new Exception('Event not found');
            }

            $eventTime = $this->eventTimeDAO->getEventTimeById($parameters->timeId);
            if (!$eventTime) {
                throw new Exception('Event not found');
            }

            $this->response->json(200, ['eventTime' => $eventTime]);
        } catch (Exception $e) {
            $this->response->json(500, ['error' => $e->getMessage()]);
        }
    }

    #[Route("/api/v1/events/{id}/times", "POST")]
    public function createEventTime($parameters)
    {
        try {
            $this->authService->isUserAuthenticated();

            $event = $this->eventDAO->getEventById($parameters->id);
            if (!$event) {
                throw new Exception('Event not found');
            }

            $body = $this->request->getBody();

            $date = new DateTime($body['date']);
            $eventTime = new EventTime(
                $date,
                $parameters->id
            );

            $eventTime = $this->eventTimeDAO->createEventTime($parameters->id, $eventTime);
            $this->response->json(200, ['eventTime' => $eventTime]);
        } catch (Exception $e) {
            $this->response->json(500, ['error' => $e->getMessage()]);
        }
    }

    #[Route("/api/v1/events/{id}/times/{timeId}", "PUT")]
    public function updateTimeEvent($parameters)
    {
        try {
            $this->authService->isUserAuthenticated();

            $event = $this->eventDAO->getEventById($parameters->id);
            if (!$event) {
                throw new Exception('Event not found');
            }

            $eventTime = $this->eventTimeDAO->getEventTimeById($parameters->timeId);
            if (!$eventTime) {
                throw new Exception('Event not found');
            }

            $body = $this->request->getBody();

            $date = new DateTime($body['date']);
            $eventTime = new EventTime(
                $date,
                $parameters->id
            );

            $eventTime = $this->eventTimeDAO->updateEventTime($parameters->timeId, $eventTime);
            $this->response->json(200, ['eventTime' => $eventTime]);
        } catch (Exception $e) {
            $this->response->json(500, ['error' => $e->getMessage()]);
        }
    }

    #[Route("/api/v1/events/{id}/times/{timeId}", "DELETE")]
    public function deletedTimeEvent($parameters)
    {
        try {
            $this->authService->isUserAuthenticated();

            $event = $this->eventDAO->getEventById($parameters->id);
            if (!$event) {
                throw new Exception('Event not found');
            }

            $eventTime = $this->eventTimeDAO->getEventTimeById($parameters->timeId);
            if (!$eventTime) {
                throw new Exception('Event not found');
            }

            $eventTime = $this->eventTimeDAO->deleteEventTime($parameters->timeId);
            $this->response->json(204);
        } catch (Exception $e) {
            $this->response->json(500, ['error' => $e->getMessage()]);
        }
    }
}