<?php

namespace Stack\Fest\Model\DAO;

use PDO;
use Stack\Fest\Config\Database;
use Stack\Fest\Model\Entity\EventTime;

class EventTimeDAO
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function getEventTimes($id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM event_times WHERE event_id = :id');
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createEventTime($id, $eventTime)
    {
        $stmt = $this->pdo->prepare('INSERT INTO event_times (event_id, time) VALUES (:event_id, :time)');
        $stmt->bindValue(':event_id', $id);
        $stmt->bindValue(':time', $eventTime->getTime()->format('Y-m-d H:i:s'));
        $stmt->execute();

        $lastId = $this->pdo->lastInsertId();

        return $this->getEventTimeById($lastId);
    }

    public function getEventTimeById(false|string $id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM event_times WHERE id = :id');
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateEventTime($timeId, EventTime $eventTime)
    {
        $stmt = $this->pdo->prepare('UPDATE event_times SET time = :time, updated_at = NOW() WHERE id = :id');
        $stmt->bindValue(':time', $eventTime->getTime()->format('Y-m-d H:i:s'));
        $stmt->bindValue(':id', $timeId);
        $stmt->execute();

        return $this->getEventTimeById($timeId);
    }

    public function deleteEventTime($timeId)
    {
        $stmt = $this->pdo->prepare('UPDATE event_times SET deleted = 1, deleted_at = NOW() WHERE id = :id');
        $stmt->bindValue(':id', $timeId);
        $stmt->execute();

        return $this->getEventTimeById($timeId);
    }
}