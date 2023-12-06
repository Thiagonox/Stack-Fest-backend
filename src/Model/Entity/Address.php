<?php

namespace Stack\Fest\Model\Entity;

use DateTime;

class Address
{
    private int $id;
    private string $street;
    private string $city;
    private string $state;
    private string $zipCode;
    private string $neighborhood;
    private string $complement;
    private string $number;
    private bool $deleted;
    private ?DateTime $createdAt;
    private ?DateTime $updatedAt;
    private ?DateTime $deletedAt;

    public function __construct(
        string $street,
        string $city,
        string $state,
        string $zipCode,
        string $neighborhood,
        string $complement,
        string $number
    )
    {
        $this->street = $street;
        $this->city = $city;
        $this->state = $state;
        $this->zipCode = $zipCode;
        $this->neighborhood = $neighborhood;
        $this->complement = $complement;
        $this->number = $number;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getStreet(): string
    {
        return $this->street;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function getZipCode(): string
    {
        return $this->zipCode;
    }

    public function getNeighborhood(): string
    {
        return $this->neighborhood;
    }

    public function getComplement(): string
    {
        return $this->complement;
    }

    public function getNumber(): string
    {
        return $this->number;
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
}
