<?php

declare(strict_types=1);

use App\config\Env;

require_once dirname(__DIR__) . '/vendor/autoload.php';

function envValue(string $key, string $default = ''): string
{
    return Env::get($key, $default);
}

function createServerConnection(): \PDO
{
    $driver = envValue('DB_DRIVER', 'mysql');
    $host = envValue('DB_HOST', '127.0.0.1');
    $port = envValue('DB_PORT', '3306');
    $charset = envValue('DB_CHARSET', 'utf8mb4');
    $username = envValue('DB_USER', 'root');
    $password = envValue('DB_PASS', '');

    $dsn = sprintf(
        '%s:host=%s;port=%s;charset=%s',
        $driver,
        $host,
        $port,
        $charset
    );

    return new \PDO($dsn, $username, $password, [
        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
    ]);
}

function dropAllTables(\PDO $pdo, string $database): void
{
    $statement = $pdo->prepare(
        'SELECT table_name
         FROM information_schema.tables
         WHERE table_schema = :database
           AND table_type = \'BASE TABLE\''
    );
    $statement->execute(['database' => $database]);
    $tables = $statement->fetchAll(\PDO::FETCH_COLUMN);

    if ($tables === []) {
        return;
    }

    $quotedTables = array_map(
        static fn (string $table): string => sprintf('`%s`', str_replace('`', '``', $table)),
        $tables
    );

    $pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
    $pdo->exec('DROP TABLE IF EXISTS ' . implode(', ', $quotedTables));
    $pdo->exec('SET FOREIGN_KEY_CHECKS = 1');
}

function executeSqlFile(\PDO $pdo, string $filePath): void
{
    if (!is_file($filePath)) {
        throw new RuntimeException(sprintf('SQL file not found: %s', $filePath));
    }

    $sql = trim((string) file_get_contents($filePath));
    if ($sql === '') {
        return;
    }

    $pdo->exec($sql);
}

$database = envValue('DB_NAME');
$schemaPath = dirname(__DIR__) . '/docker/mysql/init/001_schema.sql';
$seedPath = dirname(__DIR__) . '/docker/mysql/init/002_seed.sql';

if ($database === '') {
    fwrite(STDERR, "DB_NAME is not configured.\n");
    exit(1);
}

try {
    $pdo = createServerConnection();
    $pdo->exec(sprintf(
        'CREATE DATABASE IF NOT EXISTS `%s` CHARACTER SET %s COLLATE %s_unicode_ci',
        str_replace('`', '``', $database),
        envValue('DB_CHARSET', 'utf8mb4'),
        envValue('DB_CHARSET', 'utf8mb4')
    ));
    $pdo->exec(sprintf('USE `%s`', str_replace('`', '``', $database)));

    dropAllTables($pdo, $database);
    executeSqlFile($pdo, $schemaPath);
    executeSqlFile($pdo, $seedPath);

    fwrite(STDOUT, "Database reset completed successfully.\n");
    exit(0);
} catch (\PDOException $exception) {
    fwrite(STDERR, 'Database reset failed: ' . $exception->getMessage() . PHP_EOL);
    exit(1);
} catch (RuntimeException $exception) {
    fwrite(STDERR, $exception->getMessage() . PHP_EOL);
    exit(1);
}
