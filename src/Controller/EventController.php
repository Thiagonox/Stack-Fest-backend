<?php

namespace Stack\Fest\Controller;

use DateTime;
use Exception;
use MareaTurbo\Route;
use Stack\Fest\Config\Request;
use Stack\Fest\Config\Response;
use Stack\Fest\Model\DAO\CompanyDAO;
use Stack\Fest\Model\DAO\EventDAO;
use Stack\Fest\Model\Entity\Address;
use Stack\Fest\Model\Entity\Event;
use Stack\Fest\Model\Entity\Link;
use Stack\Fest\Model\Service\AuthService;

class EventController
{
    public function __construct(
        protected AuthService $authService,
        protected EventDAO    $eventDAO,
        protected CompanyDAO  $companyDAO,
        protected Request     $request,
        protected Response    $response
    )
    {
    }

    #[Route("/api/v1/events", "GET")]
    public function getEvents()
    {
        try {
            $this->authService->isUserAuthenticated();

            $this->response->json(200, ['events' => $this->eventDAO->getEvents()]);
        } catch (Exception $e) {
            $this->response->json(500, ['error' => $e->getMessage()]);
        }
    }

    #[Route("/api/v1/events/{id}", "GET")]
    public function getEventById($parameters)
    {
        try {
            $this->authService->isUserAuthenticated();

            $event = $this->eventDAO->getEventById($parameters->id);
            if (!$event) {
                throw new Exception('Event not found');
            }

            $this->response->json(200, ['event' => $event]);
        } catch (Exception $e) {
            $this->response->json(500, ['error' => $e->getMessage()]);
        }
    }

    #[Route("/api/v1/events", "POST")]
    public function createEvent()
    {
        try {
            $id = $this->authService->isUserAuthenticated();

            $body = $this->request->getBody();

            $company = $this->companyDAO->getCompanyById($body['company_id']);
            if (!$company) {
                throw new Exception('Company not found');
            }

            if (isset($body['address'])) {
                $address = new Address(
                    $body['address']['street'],
                    $body['address']['city'],
                    $body['address']['state'],
                    $body['address']['zipCode'],
                    $body['address']['neighborhood'],
                    $body['address']['complement'],
                    $body['address']['number']
                );
                $address = $this->eventDAO->createAddress($address);
            }

            if (isset($body['link'])) {
                $link = new Link(
                    $body['link']['url']
                );
                $link = $this->eventDAO->createLink($link);
            }

            $date = new DateTime($body['date']);
            $event = new Event(
                $body['name'],
                $date,
                $body['description'],
                $body['totalTickets'],
                $address['id'] ?? null,
                $link['id'] ?? null
            );

            $this->response->json(201, ['event' => $this->eventDAO->createEvent($body['company_id'], $event)]);
        } catch (Exception $e) {
            $this->response->json(500, ['error' => $e->getMessage()]);
        }
    }

    #[Route("/api/v1/events/{id}", "PUT")]
    public function updateEvent($parameters)
    {
        try {
            $this->authService->isUserAuthenticated();

            $body = $this->request->getBody();

            $company = $this->companyDAO->getCompanyById($body['company_id']);
            if (!$company) {
                throw new Exception('Company not found');
            }

            if (isset($body['address'])) {
                $address = $this->eventDAO->getAddressByEventId($parameters->id);
                $addressEntity = new Address(
                    $body['address']['street'],
                    $body['address']['city'],
                    $body['address']['state'],
                    $body['address']['zipCode'],
                    $body['address']['neighborhood'],
                    $body['address']['complement'],
                    $body['address']['number']
                );
                if ($address) {
                    $address = $this->eventDAO->updateAddress($parameters->id, $addressEntity);
                } else {
                    $address = $this->eventDAO->createAddress($addressEntity);
                }
            }

            if (isset($body['link'])) {
                $linkEntity = new Link(
                    $body['link']['url']
                );

                $link = $this->eventDAO->getLinkByEventId($parameters->id);
                if ($link) {
                    $link = $this->eventDAO->updateLink($parameters->id, $linkEntity);
                } else {
                    $link = $this->eventDAO->createLink($linkEntity);
                }
            }

            $date = new DateTime($body['date']);
            $event = new Event(
                $body['name'],
                $date,
                $body['description'],
                $body['totalTickets'],
                $address['id'] ?? null,
                $link['id'] ?? null
            );

            $this->response->json(200, ['event' => $this->eventDAO->updateEvent($parameters->id, $event)]);
        } catch (Exception $e) {
            $this->response->json(500, ['error' => $e->getMessage()]);
        }
    }

    #[Route("/api/v1/events/{id}", "DELETE")]
    public function deleteEvent($parameters)
    {
        try {
            $this->authService->isUserAuthenticated();

            $this->eventDAO->deleteEvent($parameters->id);

            $this->response->json(204);
        } catch (Exception $e) {
            $this->response->json(500, ['error' => $e->getMessage()]);
        }
    }
}