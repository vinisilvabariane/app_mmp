<?php

namespace App\models;

use App\config\Connection;
use PDO;

class UserModel
{
    public function findByEmail(string $email): ?array
    {
        $pdo = Connection::connect();
        $querySql = 'SELECT * FROM users WHERE email = :email LIMIT 1';
        $statement = $pdo->prepare($querySql);
        $statement->bindValue(':email', $email, PDO::PARAM_STR);
        $statement->execute();

        return $statement->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function updateTemporaryPassword(int $id, string $passwordHash): void
    {
        $pdo = Connection::connect();
        $querySql = 'UPDATE users SET password_hash = :password_hash, reset_required = 1 WHERE id = :id';
        $statement = $pdo->prepare($querySql);
        $statement->bindValue(':password_hash', $passwordHash, PDO::PARAM_STR);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->execute();
    }

    public function updatePassword(int $id, string $passwordHash): void
    {
        $pdo = Connection::connect();
        $querySql = 'UPDATE users SET password_hash = :password_hash, reset_required = 0 WHERE id = :id';
        $statement = $pdo->prepare($querySql);
        $statement->bindValue(':password_hash', $passwordHash, PDO::PARAM_STR);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->execute();
    }

    public function create(string $email, string $passwordHash, ?string $fullName = null): int
    {
        $pdo = Connection::connect();
        $querySql = 'INSERT INTO users (email, password_hash, full_name, is_active, reset_required) VALUES (:email, :password_hash, :full_name, 1, 0)';
        $statement = $pdo->prepare($querySql);
        $statement->bindValue(':email', $email, PDO::PARAM_STR);
        $statement->bindValue(':password_hash', $passwordHash, PDO::PARAM_STR);
        $statement->bindValue(':full_name', $fullName, $fullName !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
        $statement->execute();

        return (int) $pdo->lastInsertId();
    }

    public function getAll(): array
    {
        $pdo = Connection::connect();
        $querySql = 'SELECT * FROM users';
        $statement = $pdo->query($querySql);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById(int $id): ?array
    {
        $pdo = Connection::connect();
        $querySql = 'SELECT * FROM users WHERE id = :id';
        $statement = $pdo->prepare($querySql);
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch(PDO::FETCH_ASSOC) ?: null;
    }
}
