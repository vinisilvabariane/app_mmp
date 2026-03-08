<?php

namespace App\routers;

use App\controllers\FormsController;

class FormsRouter
{
    public function index(): void
    {
        $controller = new FormsController();
        $action = $_GET['action'] ?? 'index';

        switch ($action) {
            case 'index':
            default:
                $controller->index();
        }
    }
}
