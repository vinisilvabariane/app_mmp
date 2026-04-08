<?php

namespace App\config;

use PDO;

class Connection
{
    private string $host;
    private string $port;
    private string $password;
    private string $username;
    private string $database;
    private string $charset;

    public function __construct()
    {
        $this->host = self::env('DB_HOST', 'mysql');
        $this->port = self::env('DB_PORT', '3306');
        $this->password = self::env('DB_PASSWORD', self::env('DB_PASS', ''));
        $this->username = self::env('DB_USERNAME', 'root');
        $this->database = self::env('DB_DATABASE', '');
        $this->charset = self::env('DB_CHARSET', 'utf8mb4');
    }

    public function getConnection(): PDO
    {
        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=%s',
            $this->host,
            $this->port,
            $this->database,
            $this->charset
        );

        $pdo = new PDO($dsn, $this->username, $this->password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);

        $this->normalizeUsersTableSchema($pdo);
        return $pdo;
    }

    private function normalizeUsersTableSchema(PDO $pdo): void
    {
        $tableExistsStmt = $pdo->prepare(
            "SELECT COUNT(*)
             FROM INFORMATION_SCHEMA.TABLES
             WHERE TABLE_SCHEMA = :schema
               AND TABLE_NAME = 'users'"
        );
        $tableExistsStmt->execute([':schema' => $this->database]);

        if ((int) $tableExistsStmt->fetchColumn() === 0) {
            return;
        }

        $stmt = $pdo->prepare(
            "SELECT DISTINCT INDEX_NAME
             FROM INFORMATION_SCHEMA.STATISTICS
             WHERE TABLE_SCHEMA = :schema
               AND TABLE_NAME = 'users'
               AND COLUMN_NAME = 'cnpj'
               AND NON_UNIQUE = 0
               AND INDEX_NAME != 'PRIMARY'"
        );
        $stmt->execute([':schema' => $this->database]);
        $uniqueIndexes = $stmt->fetchAll(PDO::FETCH_COLUMN);

        foreach ($uniqueIndexes as $indexName) {
            $safeIndexName = str_replace('`', '``', $indexName);
            $pdo->exec("ALTER TABLE users DROP INDEX `{$safeIndexName}`");
        }
    }

    private static function env(string $key, string $default = ''): string
    {
        $value = getenv($key);

        if (is_string($value) && $value !== '') {
            return $value;
        }

        static $envCache = null;

        if ($envCache === null) {
            $envPath = dirname(__DIR__, 2) . '/.env';
            $envCache = [];

            if (is_file($envPath)) {
                $parsed = parse_ini_file($envPath, false, INI_SCANNER_RAW);
                if (is_array($parsed)) {
                    $envCache = $parsed;
                }
            }
        }

        return isset($envCache[$key]) ? trim((string) $envCache[$key]) : $default;
    }
}
