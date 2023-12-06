<?php

namespace Stack\Fest\Model\Entity;

use DateTime;

class Ticket
{
    private int $id;
    private float $price;
    private bool $deleted;
    private ?DateTime $createdAt;
    private ?DateTime $updatedAt;
    private ?DateTime $deletedAt;
    private User $user;
    private EventTime $eventTime;
    private Event $event;
    private string $paymentMethod;

    public function __construct(
        float  $price,
        int    $userId,
        int    $eventTimeId,
        int    $eventId,
        string $paymentMethod
    )
    {
        $this->price = $price;
        $this->paymentMethod = $paymentMethod;
        $this->userId = $userId;
        $this->eventTimeId = $eventTimeId;
        $this->eventId = $eventId;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getEventId(): int
    {
        return $this->eventId;
    }

    public function getEventTimeId(): int
    {
        return $this->eventTimeId;
    }

    public function getUserId(): int
    {
        return $this->userId;
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

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getEventTime(): EventTime
    {
        return $this->eventTime;
    }

    public function setEventTime(EventTime $eventTime): void
    {
        $this->eventTime = $eventTime;
    }

    public function getEvent(): Event
    {
        return $this->event;
    }

    public function setEvent(Event $event): void
    {
        $this->event = $event;
    }

    public function getPaymentMethod(): string
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod(string $paymentMethod): void
    {
        $this->paymentMethod = $paymentMethod;
    }
}
