<?php

namespace App\routers;

use App\controllers\LoginController;

class LoginRouter
{
    public function index(): void
    {
        $controller = new LoginController();
        $method = strtoupper((string)($_SERVER['REQUEST_METHOD'] ?? 'GET'));

        if ($method === 'POST') {
            $controller->authenticate();
            return;
        }

        $controller->index();
    }

    public function authenticate(): void
    {
        (new LoginController())->authenticate();
    }

    public function showChangePassword(): void
    {
        (new LoginController())->showChangePassword();
    }

    public function changePassword(): void
    {
        (new LoginController())->changePassword();
    }

    public function requestPasswordReset(): void
    {
        (new LoginController())->requestPasswordReset();
    }

    public function register(): void
    {
        (new LoginController())->register();
    }

    public function logout(): void
    {
        (new LoginController())->logout();
    }
}
