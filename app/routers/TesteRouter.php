<?php

namespace App\routers;

use App\controllers\TesteController;

class TesteRouter
{
    public function index()
    {
        $controller = new TesteController();
        $action = $_GET['action'] ?? 'index';
        switch ($action) {
            default:
                $controller->index();
        }
    }
}
