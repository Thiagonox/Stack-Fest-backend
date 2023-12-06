<?php

namespace Stack\Fest\Model\DAO;

use PDO;
use Stack\Fest\Config\Database;
use Stack\Fest\Model\Entity\Address;
use Stack\Fest\Model\Entity\Event;
use Stack\Fest\Model\Entity\Link;

class EventDAO
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function getEvents(): false|array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM events');
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createEvent($id, Event $event)
    {
        $stmt = $this->pdo->prepare('INSERT INTO events (company_id, name, date, description, total_tickets, address_id, link_id) VALUES (:company_id, :name, :date, :description, :total_tickets, :address_id, :link_id)');
        $stmt->bindValue(':company_id', $id);
        $stmt->bindValue(':name', $event->getName());
        $stmt->bindValue(':date', $event->getDate()->format('Y-m-d H:i:s'));
        $stmt->bindValue(':description', $event->getDescription());
        $stmt->bindValue(':total_tickets', $event->getTotalTickets());
        $stmt->bindValue(':address_id', $event->getAddress() ?? null);
        $stmt->bindValue(':link_id', $event->getLink() ?? null);
        $stmt->execute();

        $lastId = $this->pdo->lastInsertId();

        return $this->getEventById($lastId);
    }

    public function getEventById($id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM events WHERE id = :id');
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createAddress(Address $address)
    {
        $stmt = $this->pdo->prepare('INSERT INTO addresses (street, city, state, zip_code, neighborhood, complement, number) VALUES (:street, :city, :state, :zipCode, :neighborhood, :complement, :number)');
        $stmt->bindValue(':street', $address->getStreet());
        $stmt->bindValue(':city', $address->getCity());
        $stmt->bindValue(':state', $address->getState());
        $stmt->bindValue(':zipCode', $address->getZipCode());
        $stmt->bindValue(':neighborhood', $address->getNeighborhood());
        $stmt->bindValue(':complement', $address->getComplement());
        $stmt->bindValue(':number', $address->getNumber());
        $stmt->execute();

        $lastId = $this->pdo->lastInsertId();

        return $this->getAddressById($lastId);
    }

    public function getAddressById($id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM addresses WHERE id = :id');
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createLink(Link $link)
    {
        $stmt = $this->pdo->prepare('INSERT INTO links (url) VALUES (:url)');
        $stmt->bindValue(':url', $link->getUrl());
        $stmt->execute();

        $lastId = $this->pdo->lastInsertId();

        return $this->getLinkById($lastId);
    }

    public function getLinkById(false|string $lastId)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM links WHERE id = :id');
        $stmt->bindValue(':id', $lastId);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateAddress(mixed $id, Address $addressEntity)
    {
        $stmt = $this->pdo->prepare('UPDATE addresses SET street = :street, city = :city, state = :state, zip_code = :zipCode, neighborhood = :neighborhood, complement = :complement, number = :number, updated_at = NOW() WHERE id = :id');
        $stmt->bindValue(':id', $id);
        $stmt->bindValue(':street', $addressEntity->getStreet());
        $stmt->bindValue(':city', $addressEntity->getCity());
        $stmt->bindValue(':state', $addressEntity->getState());
        $stmt->bindValue(':zipCode', $addressEntity->getZipCode());
        $stmt->bindValue(':neighborhood', $addressEntity->getNeighborhood());
        $stmt->bindValue(':complement', $addressEntity->getComplement());
        $stmt->bindValue(':number', $addressEntity->getNumber());
        $stmt->execute();

        return $this->getAddressById($id);
    }

    public function updateLink(mixed $id, Link $linkEntity)
    {
        $stmt = $this->pdo->prepare('UPDATE links SET url = :url, updated_at = NOW() WHERE id = :id');
        $stmt->bindValue(':id', $id);
        $stmt->bindValue(':url', $linkEntity->getUrl());
        $stmt->execute();

        return $this->getLinkById($id);
    }

    public function updateEvent($id, Event $event)
    {
        $stmt = $this->pdo->prepare('UPDATE events SET name = :name, date = :date, description = :description, total_tickets = :total_tickets, address_id = :address_id, link_id = :link_id, updated_at = NOW() WHERE id = :id');
        $stmt->bindValue(':id', $id);
        $stmt->bindValue(':name', $event->getName());
        $stmt->bindValue(':date', $event->getDate()->format('Y-m-d H:i:s'));
        $stmt->bindValue(':description', $event->getDescription());
        $stmt->bindValue(':total_tickets', $event->getTotalTickets());
        $stmt->bindValue(':address_id', $event->getAddress() ?? null);
        $stmt->bindValue(':link_id', $event->getLink() ?? null);
        $stmt->execute();

        return $this->getEventById($id);
    }

    public function getAddressByEventId($id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM events WHERE id = :id');
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        $event = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($event['address_id']) {
            return $this->getAddressById($event['address_id']);
        }

        return false;
    }

    public function getLinkByEventId($id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM events WHERE id = :id');
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        $event = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($event['link_id']) {
            return $this->getLinkById($event['link_id']);
        }

        return false;
    }

    public function deleteEvent($id)
    {
        $stmt = $this->pdo->prepare('UPDATE events SET deleted = true, deleted_at = NOW() WHERE id = :id');
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }
}