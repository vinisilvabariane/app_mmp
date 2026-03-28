<?php

namespace App\config;

class Auth
{
    private const SESSION_KEY = 'auth_user';

    public static function check(): bool
    {
        return isset($_SESSION[self::SESSION_KEY]) && is_array($_SESSION[self::SESSION_KEY]);
    }

    public static function user(): ?array
    {
        return self::check() ? $_SESSION[self::SESSION_KEY] : null;
    }

    public static function attempt(string $username, string $password): bool
    {
        $credentials = self::credentials();

        if ($username !== $credentials['username']) {
            return false;
        }

        $storedPassword = $credentials['password'];
        $passwordMatches = password_verify($password, $storedPassword) || hash_equals($storedPassword, $password);

        if (!$passwordMatches) {
            return false;
        }

        $_SESSION[self::SESSION_KEY] = [
            'username' => $credentials['username'],
            'login_at' => date(DATE_ATOM),
        ];

        return true;
    }

    public static function logout(): void
    {
        unset($_SESSION[self::SESSION_KEY]);
    }

    public static function requireAuth(string $basePath = ''): void
    {
        if (self::check()) {
            return;
        }

        header('Location: ' . self::url($basePath, '/login'));
        exit;
    }

    public static function redirectIfAuthenticated(string $basePath = ''): void
    {
        if (!self::check()) {
            return;
        }

        header('Location: ' . self::url($basePath, '/home'));
        exit;
    }

    private static function credentials(): array
    {
        $username = self::env('AUTH_USER', 'admin');
        $password = self::env('AUTH_PASS', 'admin123');

        return [
            'username' => trim($username),
            'password' => trim($password),
        ];
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

        return isset($envCache[$key]) ? (string)$envCache[$key] : $default;
    }

    private static function url(string $basePath, string $path): string
    {
        return rtrim($basePath, '/') . $path;
    }
}
