<?php

namespace App\config;

use App\models\UserModel;

class Auth
{
    private const SESSION_KEY = 'auth_user';

    public static function check(): bool
    {
        if (!isset($_SESSION[self::SESSION_KEY]) || !is_array($_SESSION[self::SESSION_KEY])) {
            return false;
        }

        return self::sessionMatchesDatabase();
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

        if (session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);
        }

        $currentSessionId = session_id();
        $userModel->updateSessionId((int) ($user['id'] ?? 0), $currentSessionId !== '' ? $currentSessionId : null);

        $_SESSION[self::SESSION_KEY] = [
            'id' => (int) ($user['id'] ?? 0),
            'email' => isset($user['email']) ? (string) $user['email'] : null,
            'full_name' => isset($user['full_name']) ? (string) $user['full_name'] : null,
            'role_id' => (int) ($user['role_id'] ?? 0),
            'role' => isset($user['role_name']) ? (string) $user['role_name'] : null,
            'session_id' => $currentSessionId !== '' ? $currentSessionId : null,
            'reset_required' => (int) ($user['reset_required'] ?? 0) === 1,
            'login_at' => date(DATE_ATOM),
        ];

        return true;
    }

    public static function logout(): void
    {
        $user = self::user();
        $userId = isset($user['id']) ? (int) $user['id'] : 0;
        if ($userId > 0) {
            (new UserModel())->updateSessionId($userId, null);
        }

        unset($_SESSION[self::SESSION_KEY]);

        if (session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);
        }
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

    public static function hasRole(string $role): bool
    {
        $user = self::user();
        return is_array($user) && isset($user['role']) && (string) $user['role'] === $role;
    }

    private static function isActive(array $user): bool
    {
        return (int) ($user['is_active'] ?? 0) === 1;
    }

    private static function sessionMatchesDatabase(): bool
    {
        $user = $_SESSION[self::SESSION_KEY] ?? null;
        $userId = is_array($user) && isset($user['id']) ? (int) $user['id'] : 0;
        if ($userId <= 0) {
            return false;
        }

        $databaseSessionId = (new UserModel())->getSessionIdByUserId($userId);
        $currentSessionId = session_id();

        if ($databaseSessionId === null || $databaseSessionId === '') {
            return false;
        }

        if ($currentSessionId === '' || !hash_equals($databaseSessionId, $currentSessionId)) {
            unset($_SESSION[self::SESSION_KEY]);
            return false;
        }

        return true;
    }

    private static function url(string $basePath, string $path): string
    {
        return rtrim($basePath, '/') . $path;
    }
}
