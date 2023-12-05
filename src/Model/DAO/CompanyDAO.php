<?php

namespace Stack\Fest\Model\DAO;

use PDO;
use Stack\Fest\Config\Database;
use Stack\Fest\Model\Entity\Company;

class CompanyDAO
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function getCompanies(): false|array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM companies');
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createCompany(int $id, Company $company)
    {
        $stmt = $this->pdo->prepare('INSERT INTO companies (owner_id, name, cnpj, email, phone) VALUES (:owner_id, :name, :cnpj, :email, :phone)');
        $stmt->bindValue(':owner_id', $id);
        $stmt->bindValue(':name', $company->getName());
        $stmt->bindValue(':cnpj', $company->getCnpj());
        $stmt->bindValue(':email', $company->getEmail());
        $stmt->bindValue(':phone', $company->getPhone());
        $stmt->execute();

        return $this->getCompanyByName($company->getName());
    }

    public function getCompanyByName($getName)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM companies WHERE name = :name');
        $stmt->bindValue(':name', $getName);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateCompany($id, Company $company)
    {
        $stmt = $this->pdo->prepare('UPDATE companies SET name = :name, cnpj = :cnpj, email = :email, phone = :phone, updated_at = NOW() WHERE id = :id');
        $stmt->bindValue(':id', $id);
        $stmt->bindValue(':name', $company->getName());
        $stmt->bindValue(':cnpj', $company->getCnpj());
        $stmt->bindValue(':email', $company->getEmail());
        $stmt->bindValue(':phone', $company->getPhone());
        $stmt->execute();

        return $this->getCompanyByName($company->getName());
    }

    public function deleteCompany($id): void
    {
        $stmt = $this->pdo->prepare('UPDATE companies SET deleted = true, deleted_at = NOW() WHERE id = :id');
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }

    public function getCompanyById($id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM companies WHERE id = :id');
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getEventsByCompanyId($id): false|array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM events WHERE company_id = :id');
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}