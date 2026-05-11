<?php

namespace App\models;

use App\config\Connection;
use PDO;

class LearningRouteModel
{
    public function saveGeneratedRoute(
        int $userId,
        int $submissionId,
        string $question,
        array $metrics,
        array $route
    ): void {
        $pdo = Connection::connect();
        $this->ensureTable($pdo);
        $statement = $pdo->prepare(
            'INSERT INTO learning_routes (
                user_id,
                submission_id,
                question,
                metrics_json,
                route_json,
                status
            ) VALUES (
                :user_id,
                :submission_id,
                :question,
                :metrics_json,
                :route_json,
                :status
            )'
        );
        $statement->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $statement->bindValue(':submission_id', $submissionId, PDO::PARAM_INT);
        $statement->bindValue(':question', $question, PDO::PARAM_STR);
        $statement->bindValue(':metrics_json', json_encode($metrics, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), PDO::PARAM_STR);
        $statement->bindValue(':route_json', json_encode($route, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), PDO::PARAM_STR);
        $statement->bindValue(':status', 'generated', PDO::PARAM_STR);
        $statement->execute();
    }

    public function getLatestByUserId(int $userId): ?array
    {
        $pdo = Connection::connect();
        $this->ensureTable($pdo);
        $statement = $pdo->prepare(
            'SELECT *
             FROM learning_routes
             WHERE user_id = :user_id
             ORDER BY created_at DESC, id DESC
             LIMIT 1'
        );
        $statement->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $statement->execute();

        $row = $statement->fetch(PDO::FETCH_ASSOC);
        if (!is_array($row)) {
            return null;
        }

        $row['metrics'] = $this->decodeJsonField($row['metrics_json'] ?? null);
        $row['route'] = $this->decodeJsonField($row['route_json'] ?? null);
        return $row;
    }

    private function ensureTable(PDO $pdo): void
    {
        $pdo->exec(
            'CREATE TABLE IF NOT EXISTS learning_routes (
                id INT NOT NULL AUTO_INCREMENT,
                user_id INT NOT NULL,
                submission_id INT NOT NULL,
                question TEXT NOT NULL,
                metrics_json JSON NOT NULL,
                route_json JSON NOT NULL,
                status VARCHAR(50) NOT NULL DEFAULT "generated",
                created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (id),
                KEY idx_learning_routes_user_id (user_id),
                KEY idx_learning_routes_submission_id (submission_id),
                CONSTRAINT fk_learning_routes_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE ON UPDATE CASCADE,
                CONSTRAINT fk_learning_routes_submission FOREIGN KEY (submission_id) REFERENCES form_submissions (id) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'
        );
    }

    private function decodeJsonField(mixed $value): array
    {
        if (!is_string($value) || trim($value) === '') {
            return [];
        }

        $decoded = json_decode($value, true);
        return is_array($decoded) ? $decoded : [];
    }
}
