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

    public static function attempt(string $email, string $password): bool
    {
        $userModel = new UserModel();
        $user = $userModel->findByEmail(trim($email));

        if ($user === null || !self::isActive($user)) {
            return false;
        }

        $storedPassword = (string) ($user['password_hash'] ?? '');
        if ($storedPassword === '' || !password_verify($password, $storedPassword)) {
            return false;
        }

        $_SESSION[self::SESSION_KEY] = [
            'id' => (int) ($user['id'] ?? 0),
            'email' => isset($user['email']) ? (string) $user['email'] : null,
            'full_name' => isset($user['full_name']) ? (string) $user['full_name'] : null,
            'reset_required' => (int) ($user['reset_required'] ?? 0) === 1,
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
            if (self::needsPasswordReset()) {
                header('Location: ' . self::url($basePath, '/login/change-password'));
                exit;
            }
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

        if (self::needsPasswordReset()) {
            header('Location: ' . self::url($basePath, '/login/change-password'));
            exit;
        }

        header('Location: ' . self::url($basePath, '/home'));
        exit;
    }

    public static function needsPasswordReset(): bool
    {
        $user = self::user();
        return is_array($user) && !empty($user['reset_required']);
    }

    public static function markPasswordUpdated(): void
    {
        if (!self::check()) {
            return;
        }

        $_SESSION[self::SESSION_KEY]['reset_required'] = false;
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
