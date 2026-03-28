<?php

namespace App\controllers;

use App\config\Auth;

class LoginController
{
    public function index(): void
    {
        Auth::redirectIfAuthenticated($this->basePath());
        require_once __DIR__ . '/../views/login/index.php';
    }

    public function authenticate(): void
    {
        $username = trim((string)($_POST['user'] ?? ''));
        $password = (string)($_POST['password'] ?? '');
        $success = $username !== '' && $password !== '' && Auth::attempt($username, $password);

        if ($this->expectsJson()) {
            header('Content-Type: application/json; charset=UTF-8');

            if ($success) {
                echo json_encode([
                    'ok' => true,
                    'redirect' => $this->route('/home'),
                ]);
                return;
            }

            http_response_code(401);
            echo json_encode([
                'ok' => false,
                'message' => 'Usuario ou senha invalidos.',
            ]);
            return;
        }

        if ($success) {
            header('Location: ' . $this->route('/home'));
            exit;
        }

        $_SESSION['login_error'] = 'Usuario ou senha invalidos.';
        header('Location: ' . $this->route('/login'));
        exit;
    }

    public function logout(): void
    {
        Auth::logout();
        header('Location: ' . $this->route('/login'));
        exit;
    }

    private function expectsJson(): bool
    {
        $header = $_SERVER['HTTP_X_REQUESTED_WITH'] ?? '';
        return strtolower((string)$header) === 'xmlhttprequest';
    }

    private function basePath(): string
    {
        return isset($_SERVER['APP_BASE_PATH']) ? (string)$_SERVER['APP_BASE_PATH'] : '';
    }

    private function route(string $path): string
    {
        return rtrim($this->basePath(), '/') . $path;
    }
}
