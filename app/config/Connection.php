<?php

namespace App\config;

use PDO;
use PDOException;
    
class Connection
{
    public static function connect(): PDO
    {
        $driver = self::env('DB_DRIVER', 'mysql');
        $host = self::env('DB_HOST', '127.0.0.1');
        $port = self::env('DB_PORT', '3306');
        $database = self::env('DB_NAME', '');
        $username = self::env('DB_USER', 'root');
        $password = self::env('DB_PASS', '');
        $charset = self::env('DB_CHARSET', 'utf8mb4');

        $dsn = sprintf(
            '%s:host=%s;port=%s;dbname=%s;charset=%s',
            $driver,
            $host,
            $port,
            $database,
            $charset
        );

        try {
            return new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
            throw new PDOException('Database connection failed: ' . $e->getMessage(), (int) $e->getCode(), $e);
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

        return isset($envCache[$key]) ? (string) $envCache[$key] : $default;
    }
}
