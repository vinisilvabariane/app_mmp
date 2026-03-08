<?php

namespace App\controllers;

class FormsController
{
    public function index(): void
    {
        require_once __DIR__ . '/../views/forms/index.php';
    }
}
