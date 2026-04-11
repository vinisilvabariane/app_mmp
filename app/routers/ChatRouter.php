<?php

namespace App\routers;

use App\Controllers\ChatController;

class ChatRouter
{
    public function index(): void
    {
        $controller = new ChatController();
        $action = $_GET['action'] ?? 'index';
        switch ($action) {
            default:
                $controller->index();
        }
    }

    public function message(): void
    {
        $controller = new ChatController();
        $controller->message();
    }
}
