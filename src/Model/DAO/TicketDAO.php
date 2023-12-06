<?php

namespace Stack\Fest\Model\DAO;

use PDO;
use Stack\Fest\Config\Database;
use Stack\Fest\Model\Entity\Ticket;

class TicketDAO
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function getAllTicketsFromEvent($id)
    {
        $sql = "SELECT * FROM tickets WHERE event_id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createTicket(Ticket $ticket)
    {
        $sql = "INSERT INTO tickets (price, user_id, event_time_id, event_id, payment_method) VALUES (:price, :userId, :eventTimeId, :eventId, :paymentMethod)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':price', $ticket->getPrice());
        $stmt->bindValue(':userId', $ticket->getUserId());
        $stmt->bindValue(':eventTimeId', $ticket->getEventTimeId());
        $stmt->bindValue(':eventId', $ticket->getEventId());
        $stmt->bindValue(':paymentMethod', $ticket->getPaymentMethod());
        $stmt->execute();
        return $this->getTicketById($this->pdo->lastInsertId());
    }

    public function getTicketById($ticketId)
    {
        $sql = "SELECT * FROM tickets WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $ticketId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function deleteTicket($ticketId)
    {
        $sql = "UPDATE tickets SET deleted = 1, deleted_at = NOW() WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $ticketId);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function updateTicket($ticketId, Ticket $ticket)
    {
        $sql = "UPDATE tickets SET price = :price, user_id = :userId, event_time_id = :eventTimeId, event_id = :eventId, payment_method = :paymentMethod, updated_at = NOW() WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':price', $ticket->getPrice());
        $stmt->bindValue(':userId', $ticket->getUserId());
        $stmt->bindValue(':eventTimeId', $ticket->getEventTimeId());
        $stmt->bindValue(':eventId', $ticket->getEventId());
        $stmt->bindValue(':paymentMethod', $ticket->getPaymentMethod());
        $stmt->bindValue(':id', $ticketId);
        $stmt->execute();
        return $this->getTicketById($ticketId);
    }

    public function updateEventSoldTickets($id, int $param)
    {
        $sql = "UPDATE events SET sold_tickets = sold_tickets + :param WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':param', $param);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function getAllTicketsFromUser($userId)
    {
        $sql = "SELECT * FROM tickets WHERE user_id = :userId";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':userId', $userId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}