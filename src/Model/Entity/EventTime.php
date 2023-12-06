<?php

namespace Stack\Fest\Model\Entity;

use DateTime;

class EventTime
{
    private int $id;
    private DateTime $time;
    private bool $deleted;
    private ?DateTime $createdAt;
    private ?DateTime $updatedAt;
    private ?DateTime $deletedAt;
    private Event $event;

    public function __construct(
        DateTime $time,
        int      $eventId
    )
    {
        $this->time = $time;
        $this->eventId = $eventId;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTime(): DateTime
    {
        return $this->time;
    }

    public function setTime(DateTime $time): void
    {
        $this->time = $time;
    }

    public function getEventId(): int
    {
        return $this->eventId;
    }

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function getDeletedAt(): ?DateTime
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?DateTime $deletedAt): void
    {
        $this->deletedAt = $deletedAt;
    }

    public function getEvent(): Event
    {
        return $this->event;
    }

    public function setEvent(Event $event): void
    {
        $this->event = $event;
    }
}
