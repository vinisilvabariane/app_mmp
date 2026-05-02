<?php

namespace App\controllers;

use App\config\Auth;
use App\services\FormQuestionSyncService;

class FormsController
{
    public function index(): void
    {
        Auth::requireAuth(isset($_SERVER['APP_BASE_PATH']) ? (string)$_SERVER['APP_BASE_PATH'] : '');
        (new FormQuestionSyncService())->syncActiveQuestionsToFile();
        require_once __DIR__ . '/../views/forms/index.php';
    }
}
