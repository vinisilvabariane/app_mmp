<?php

namespace App\routers;

use App\controllers\ProfileController;

class ProfileRouter
{
    public function index(): void
    {
        $controller = new ProfileController();
        $action = $_GET['action'] ?? 'index';
        switch ($action) {
            case 'index':
            default:
                $controller->index();
        }
    }
}
