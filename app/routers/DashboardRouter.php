<?php

namespace App\routers;

use App\controllers\DashboardController;

class DashboardRouter
{
    public function index(): void
    {
        $controller = new DashboardController();
        $action = $_GET['action'] ?? 'index';

        switch ($action) {
            case 'index':
            default:
                $controller->index();
        }
    }
}
