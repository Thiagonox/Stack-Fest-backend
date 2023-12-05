<?php

namespace Stack\Fest\Model\DAO;

use PDO;
use Stack\Fest\Config\Database;
use Stack\Fest\Model\Entity\User;

class UserDAO
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function getAllUsers(): false|array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users");
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserByNameWhereDeletedFalse($name): false|array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE name = :name");
        $stmt->bindParam(':name', $name);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function registerUser(User $user)
    {
        $hashedPassword = password_hash($user->getPassword(), PASSWORD_BCRYPT);

        $stmt = $this->pdo->prepare("
            INSERT INTO users (name, email, password, phone)
            VALUES (:name, :email, :password, :phone)
        ");

        $name = $user->getName();
        $stmt->bindValue(':name', $name);
        $email = $user->getEmail();
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':password', $hashedPassword);
        $phone = $user->getPhone();
        $stmt->bindValue(':phone', $phone);

        $stmt->execute();

        return $this->getUserByName($name);
    }

    public function getUserByName($username)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE name = :name");
        $stmt->bindParam(':name', $username);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateUser($id, User $user)
    {
        $stmt = $this->pdo->prepare("
            UPDATE users
            SET name = :name, email = :email, phone = :phone, updated_at = :updatedAt
            WHERE id = :id
        ");

        $stmt->bindValue(':id', $id);
        $stmt->bindValue(':name', $user->getName());
        $stmt->bindValue(':email', $user->getEmail());
        $stmt->bindValue(':phone', $user->getPhone());
        $stmt->bindValue(':updatedAt', date('Y-m-d H:i:s'));

        $stmt->execute();

        return $this->getUserById($id);
    }

    public function getUserById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function deleteUser($id)
    {
        $stmt = $this->pdo->prepare("UPDATE users SET deleted = true, deleted_at = CURRENT_TIMESTAMP WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $this->getUserById($id);
    }
}