<?php

namespace Stack\Fest\Controller;

use Exception;
use MareaTurbo\Route;
use Stack\Fest\Config\Request;
use Stack\Fest\Config\Response;
use Stack\Fest\Model\DAO\EventDAO;
use Stack\Fest\Model\DAO\EventTimeDAO;
use Stack\Fest\Model\DAO\TicketDAO;
use Stack\Fest\Model\Entity\Ticket;
use Stack\Fest\Model\Service\AuthService;

class TicketController
{
    public function __construct(
        protected AuthService  $authService,
        protected TicketDAO    $ticketDAO,
        protected EventDAO     $eventDAO,
        protected EventTimeDAO $eventTimeDAO,
        protected Request      $request,
        protected Response     $response
    )
    {
    }

    #[Route("/api/v1/events/{id}/tickets", "GET")]
    public function getAllTicketsFromEvent($parameters)
    {
        try {
            $this->authService->isUserAuthenticated();

            $event = $this->eventDAO->getEventById($parameters->id);
            if (!$event) {
                throw new Exception('Event not found');
            }

            $tickets = $this->ticketDAO->getAllTicketsFromEvent($parameters->id);
            $this->response->json(200, ['tickets' => $tickets]);
        } catch (Exception $e) {
            $this->response->json(500, ['error' => $e->getMessage()]);
        }
    }

    #[Route("/api/v1/tickets/users", "GET")]
    public function getAllTicketsFromUser()
    {
        try {
            $userId = $this->authService->isUserAuthenticated();

            $tickets = $this->ticketDAO->getAllTicketsFromUser($userId);
            $this->response->json(200, ['tickets' => $tickets]);
        } catch (Exception $e) {
            $this->response->json(500, ['error' => $e->getMessage()]);
        }
    }

    #[Route("/api/v1/events/{id}/tickets", "POST")]
    public function createTicket($parameters)
    {
        try {
            $userId = $this->authService->isUserAuthenticated();

            $event = $this->eventDAO->getEventById($parameters->id);
            if (!$event) {
                throw new Exception('Event not found');
            }

            if ($event['total_tickets'] <= $event['sold_tickets']) {
                throw new Exception('Event sold out');
            }

            $body = $this->request->getBody();

            if ($body['paymentMethod'] !== 'credit_card' && $body['paymentMethod'] !== 'pix') {
                throw new Exception('Invalid payment method');
            }

            $eventTime = $this->eventTimeDAO->getEventTimeById($body['eventTimeId']);
            if (!$eventTime) {
                throw new Exception('Event time not found');
            }

            $ticket = new Ticket(
                $body['price'],
                $userId,
                $body['eventTimeId'],
                $parameters->id,
                $body['paymentMethod']
            );

            $ticket = $this->ticketDAO->createTicket($ticket);
            $this->ticketDAO->updateEventSoldTickets($parameters->id, 1);
            $this->response->json(201, ['ticket' => $ticket]);
        } catch (Exception $e) {
            $this->response->json(500, ['error' => $e->getMessage()]);
        }
    }

    #[Route("/api/v1/events/{id}/tickets/{ticketId}", "PUT")]
    public function updateTicket($parameters)
    {
        try {
            $userId = $this->authService->isUserAuthenticated();

            $event = $this->eventDAO->getEventById($parameters->id);
            if (!$event) {
                throw new Exception('Event not found');
            }

            $body = $this->request->getBody();

            if ($body['paymentMethod'] !== 'credit_card' && $body['paymentMethod'] !== 'pix') {
                throw new Exception('Invalid payment method');
            }

            $eventTime = $this->eventTimeDAO->getEventTimeById($body['eventTimeId']);
            if (!$eventTime) {
                throw new Exception('Event time not found');
            }

            $ticket = new Ticket(
                $body['price'],
                $userId,
                $body['eventTimeId'],
                $parameters->id,
                $body['paymentMethod']
            );

            $ticket = $this->ticketDAO->updateTicket($parameters->ticketId, $ticket);
            $this->response->json(200, ['ticket' => $ticket]);
        } catch (Exception $e) {
            $this->response->json(500, ['error' => $e->getMessage()]);
        }
    }

    #[Route("/api/v1/events/{id}/tickets/{ticketId}", "DELETE")]
    public function deleteTicket($parameters)
    {
        try {
            $this->authService->isUserAuthenticated();

            $event = $this->eventDAO->getEventById($parameters->id);
            if (!$event) {
                throw new Exception('Event not found');
            }

            $ticket = $this->ticketDAO->getTicketById($parameters->ticketId);
            if (!$ticket) {
                throw new Exception('Ticket not found');
            }

            $this->ticketDAO->deleteTicket($parameters->ticketId);
            $this->ticketDAO->updateEventSoldTickets($parameters->id, -1);
            $this->response->json(204);
        } catch (Exception $e) {
            $this->response->json(500, ['error' => $e->getMessage()]);
        }
    }

    #[Route("/api/v1/events/{id}/tickets/{ticketId}", "GET")]
    public function getTicketById($parameters)
    {
        try {
            $this->authService->isUserAuthenticated();

            $event = $this->eventDAO->getEventById($parameters->id);
            if (!$event) {
                throw new Exception('Event not found');
            }

            $ticket = $this->ticketDAO->getTicketById($parameters->ticketId);
            if (!$ticket) {
                throw new Exception('Ticket not found');
            }

            $this->response->json(200, ['ticket' => $ticket]);
        } catch (Exception $e) {
            $this->response->json(500, ['error' => $e->getMessage()]);
        }
    }
}