<?php

namespace App\models;

use App\config\Connection;
use PDO;

class UserModel
{
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
