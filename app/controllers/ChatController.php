<?php

namespace App\Controllers;

class ChatController
{
    public function index(): void
    {
        require_once __DIR__ . '/../views/chat/index.php';
    }
}
