<?php

namespace Stack\Fest\Model\Entity;

use DateTime;

class Event
{
    private int $id;
    private string $name;
    private DateTime $date;
    private string $description;
    private int $totalTickets;
    private bool $deleted;
    private ?DateTime $createdAt;
    private ?DateTime $updatedAt;
    private ?DateTime $deletedAt;
    private ?int $addressId;
    private ?int $linkId;
    private Company $company;
    private array $eventTimes = [];

    public function __construct(
        string   $name,
        DateTime $date,
        string   $description,
        int      $totalTickets,
        ?int     $addressId,
        ?int     $linkId,
    )
    {
        $this->name = $name;
        $this->date = $date;
        $this->description = $description;
        $this->totalTickets = $totalTickets;
        $this->deleted = false;
        $this->createdAt = $createdAt ?? new DateTime();
        $this->updatedAt = $updatedAt ?? new DateTime();
        $this->addressId = $addressId;
        $this->linkId = $linkId;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDate(): DateTime
    {
        return $this->date;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getTotalTickets(): int
    {
        return $this->totalTickets;
    }

    public function isDeleted(): bool
    {
        return $this->deleted;
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

    public function getAddress(): ?int
    {
        return $this->addressId ?? null;
    }

    public function getCompany(): Company
    {
        return $this->company;
    }

    public function setCompany(Company $company): void
    {
        $this->company = $company;
    }

    public function getLink(): ?int
    {
        return $this->linkId ?? null;
    }

    public function getEventTimes(): array
    {
        return $this->eventTimes;
    }

    public function setEventTimes(array $eventTimes): void
    {
        $this->eventTimes = $eventTimes;
    }

    public function addEventTime(EventTime $eventTime): void
    {
        $this->eventTimes[] = $eventTime;
    }
}
