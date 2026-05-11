<?php

namespace App\controllers;

use App\config\Auth;
use App\models\LearningRouteModel;
use App\models\RegisterForms;

class TrailController
{
    public function index(): void
    {
        Auth::requireAuth(isset($_SERVER['APP_BASE_PATH']) ? (string) $_SERVER['APP_BASE_PATH'] : '');
        require_once __DIR__ . '/../views/trail/index.php';
    }

    public function data(): void
    {
        Auth::requireAuth(isset($_SERVER['APP_BASE_PATH']) ? (string) $_SERVER['APP_BASE_PATH'] : '');

        $user = Auth::user();
        $userId = is_array($user) ? (int) ($user['id'] ?? 0) : 0;
        $route = $userId > 0 ? (new LearningRouteModel())->getLatestByUserId($userId) : null;
        $submissionId = is_array($route) ? (int) ($route['submission_id'] ?? 0) : 0;
        $answers = $submissionId > 0 ? (new RegisterForms())->getAnswersBySubmissionId($submissionId) : [];

        $this->jsonResponse([
            'ok' => true,
            'trail' => $route,
            'answers' => $answers,
            'user' => [
                'full_name' => is_array($user) ? (string) ($user['full_name'] ?? '') : '',
                'email' => is_array($user) ? (string) ($user['email'] ?? '') : '',
            ],
        ]);
    }

    private function jsonResponse(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}
