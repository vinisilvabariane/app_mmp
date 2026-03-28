<?php

namespace App\routers;

use App\controllers\AdminController;

class AdminRouter
{
    public function index(): void
    {
        $controller = new AdminController();
        $action = $_GET['action'] ?? 'index';
        switch ($action) {
            case 'index':
            default:
                $controller->index();
        }
    }
}
