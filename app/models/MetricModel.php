<?php

namespace App\models;

use App\config\Connection;
use PDO;
use Throwable;

class MetricModel
{
    public function getAll(bool $includeInactive = true): array
    {
        $pdo = Connection::connect();
        $sql = 'SELECT *
            FROM metrics';

        if (!$includeInactive) {
            $sql .= ' WHERE active = 1';
        }

        $sql .= ' ORDER BY active DESC, name ASC, id ASC';

        return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(array $data): void
    {
        $pdo = Connection::connect();
        $sql = 'INSERT INTO metrics (
                metric_key,
                name,
                description,
                active
            ) VALUES (
                :metric_key,
                :name,
                :description,
                1
            )';
        $statement = $pdo->prepare($sql);
        $statement->bindValue(':metric_key', $data['metric_key'], PDO::PARAM_STR);
        $statement->bindValue(':name', $data['name'], PDO::PARAM_STR);
        $statement->bindValue(':description', $data['description'], $data['description'] !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
        $statement->execute();
    }

    public function update(int $id, array $data): void
    {
        $pdo = Connection::connect();
        $sql = 'UPDATE metrics
            SET metric_key = :metric_key,
                name = :name,
                description = :description
            WHERE id = :id';
        $statement = $pdo->prepare($sql);
        $statement->bindValue(':metric_key', $data['metric_key'], PDO::PARAM_STR);
        $statement->bindValue(':name', $data['name'], PDO::PARAM_STR);
        $statement->bindValue(':description', $data['description'], $data['description'] !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->execute();
    }

    public function softDelete(int $id): void
    {
        $pdo = Connection::connect();
        $pdo->beginTransaction();

        try {
            $metricSql = 'UPDATE metrics SET active = 0 WHERE id = :id';
            $metricStatement = $pdo->prepare($metricSql);
            $metricStatement->bindValue(':id', $id, PDO::PARAM_INT);
            $metricStatement->execute();

            $affectSql = 'UPDATE question_metrics_affects SET active = 0 WHERE metric_id = :id';
            $affectStatement = $pdo->prepare($affectSql);
            $affectStatement->bindValue(':id', $id, PDO::PARAM_INT);
            $affectStatement->execute();

            $pdo->commit();
        } catch (Throwable $exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            throw $exception;
        }
    }
}
