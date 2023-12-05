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
        int       $id,
        string    $street,
        string    $city,
        string    $state,
        string    $zipCode,
        string    $neighborhood,
        string    $complement,
        string    $number,
        bool      $deleted = false,
        ?DateTime $createdAt = null,
        ?DateTime $updatedAt = null,
        ?DateTime $deletedAt = null
    )
    {
        $this->id = $id;
        $this->street = $street;
        $this->city = $city;
        $this->state = $state;
        $this->zipCode = $zipCode;
        $this->neighborhood = $neighborhood;
        $this->complement = $complement;
        $this->number = $number;
        $this->deleted = $deleted;
        $this->createdAt = $createdAt ?? new DateTime();
        $this->updatedAt = $updatedAt ?? new DateTime();
        $this->deletedAt = $deletedAt;
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
