<?php

namespace App\controllers;

use App\config\Auth;

class DashboardController
{
    public function index(): void

    {
        Auth::requireAuth(isset($_SERVER['APP_BASE_PATH']) ? (string)$_SERVER['APP_BASE_PATH'] : '');
        require_once __DIR__ . '/../views/dashboard/index.php';
    }
}
