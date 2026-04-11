<?php

namespace App\config;

use App\models\UserModel;

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
        $userModel = new UserModel();
        $user = $userModel->findByUsername(trim($username));

        if ($user === null || !self::isActive($user)) {
            return false;
        }

        $storedPassword = (string) ($user['password_hash'] ?? '');
        if ($storedPassword === '' || !password_verify($password, $storedPassword)) {
            return false;
        }

        $_SESSION[self::SESSION_KEY] = [
            'id' => (int) ($user['id'] ?? 0),
            'username' => (string) ($user['username'] ?? ''),
            'email' => isset($user['email']) ? (string) $user['email'] : null,
            'full_name' => isset($user['full_name']) ? (string) $user['full_name'] : null,
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

    private static function isActive(array $user): bool
    {
        return (int) ($user['is_active'] ?? 0) === 1;
    }

    private static function url(string $basePath, string $path): string
    {
        return rtrim($basePath, '/') . $path;
    }
}
