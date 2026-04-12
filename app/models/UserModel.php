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
        $pdo->beginTransaction();

        try {
            $roleQuery = "SELECT id
                FROM roles
                WHERE active = 1
                ORDER BY CASE WHEN name = 'user' THEN 0 ELSE 1 END, id ASC
                LIMIT 1";
            $roleId = (int) $pdo->query($roleQuery)->fetchColumn();

            if ($roleId <= 0) {
                throw new \RuntimeException('Nenhum perfil ativo foi encontrado para criar o usuario.');
            }

            $querySql = 'INSERT INTO users (email, password_hash, full_name, role_id, is_active, reset_required)
                VALUES (:email, :password_hash, :full_name, :role_id, 1, 0)';
            $statement = $pdo->prepare($querySql);
            $statement->bindValue(':email', $email, PDO::PARAM_STR);
            $statement->bindValue(':password_hash', $passwordHash, PDO::PARAM_STR);
            $statement->bindValue(':full_name', $fullName, $fullName !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
            $statement->bindValue(':role_id', $roleId, PDO::PARAM_INT);
            $statement->execute();

            $userId = (int) $pdo->lastInsertId();

            $userRoleSql = 'INSERT INTO user_roles (user_id, role_id) VALUES (:user_id, :role_id)';
            $userRoleStatement = $pdo->prepare($userRoleSql);
            $userRoleStatement->bindValue(':user_id', $userId, PDO::PARAM_INT);
            $userRoleStatement->bindValue(':role_id', $roleId, PDO::PARAM_INT);
            $userRoleStatement->execute();

            $pdo->commit();

            return $userId;
        } catch (\Throwable $exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            throw $exception;
        }
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
