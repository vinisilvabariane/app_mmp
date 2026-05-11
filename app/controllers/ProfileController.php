<?php

namespace App\controllers;

use App\config\Auth;
use App\models\LearningRouteModel;

class ProfileController
{
    public function index(): void
    {
        Auth::requireAuth(isset($_SERVER['APP_BASE_PATH']) ? (string) $_SERVER['APP_BASE_PATH'] : '');
        require_once __DIR__ . '/../views/profile/index.php';
    }

    public function trail(): void
    {
        Auth::requireAuth(isset($_SERVER['APP_BASE_PATH']) ? (string) $_SERVER['APP_BASE_PATH'] : '');

        $user = Auth::user();
        $userId = is_array($user) ? (int) ($user['id'] ?? 0) : 0;
        $route = $userId > 0 ? (new LearningRouteModel())->getLatestByUserId($userId) : null;

        $this->jsonResponse([
            'ok' => true,
            'trail' => $route,
        ]);
    }

    private function jsonResponse(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}
