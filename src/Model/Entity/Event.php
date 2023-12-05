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
    private ?Address $address;
    private ?Link $link;
    private Company $company;
    private array $eventTimes = [];

    public function __construct(
        int       $id,
        string    $name,
        DateTime  $date,
        string    $description,
        int       $totalTickets,
        bool      $deleted = false,
        ?DateTime $createdAt = null,
        ?DateTime $updatedAt = null,
        ?DateTime $deletedAt = null,
        ?Address  $address = null,
        Company   $company
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->date = $date;
        $this->description = $description;
        $this->totalTickets = $totalTickets;
        $this->deleted = $deleted;
        $this->createdAt = $createdAt ?? new DateTime();
        $this->updatedAt = $updatedAt ?? new DateTime();
        $this->deletedAt = $deletedAt;
        $this->address = $address;
        $this->company = $company;
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

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(?Address $address): void
    {
        $this->address = $address;
    }

    public function getCompany(): Company
    {
        return $this->company;
    }

    public function setCompany(Company $company): void
    {
        $this->company = $company;
    }

    public function getLink(): ?Link
    {
        return $this->link;
    }

    public function setLink(?Link $link): void
    {
        $this->link = $link;
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
