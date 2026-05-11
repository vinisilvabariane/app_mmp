<?php

namespace App\controllers;

use App\config\Auth;
use App\services\FormAnalysisService;
use App\services\FormQuestionSyncService;
use Exception;

class FormsController
{
    public function index(): void
    {
        Auth::requireAuth(isset($_SERVER['APP_BASE_PATH']) ? (string) $_SERVER['APP_BASE_PATH'] : '');
        $questionsDefinition = (new FormQuestionSyncService())->getActiveQuestionsPayload();
        require_once __DIR__ . '/../views/forms/index.php';
    }

    public function saveAnswers(array $data): void
    {
        $answers = $data['answers'] ?? [];

        if (empty($answers)) {
            $this->jsonOrRedirectError('Voce precisa responder as questoes para continuar.', '/forms', 422);
            return;
        }

        try {
            $userId = $this->authenticatedUserId();
            if ($userId <= 0) {
                throw new Exception('Usuario nao identificado.');
            }

            $result = (new FormAnalysisService())->submit($userId, $answers);

            if ($this->expectsJson()) {
                $this->jsonResponse([
                    'ok' => true,
                    'message' => 'Respostas processadas e trilha gerada com sucesso.',
                    'redirect' => $this->route('/profile'),
                    'submission_id' => $result['submission_id'] ?? null,
                ]);
                return;
            }

            header('Location: ' . $this->route('/profile'));
            exit;
        } catch (Exception $e) {
            $this->jsonOrRedirectError('Erro ao processar respostas: ' . $e->getMessage(), '/forms', 500);
        }
    }

    public function update(array $data): void
    {
        $answers = $data['answers'] ?? [];
        $userId = $this->authenticatedUserId();

        if ($userId <= 0 || empty($answers)) {
            $this->jsonOrRedirectError('Dados invalidos para atualizacao.', '/forms', 400);
            return;
        }

        try {
            $result = (new FormAnalysisService())->submit($userId, $answers);

            if ($this->expectsJson()) {
                $this->jsonResponse([
                    'ok' => true,
                    'message' => 'Questionario atualizado e trilha recalculada com sucesso.',
                    'redirect' => $this->route('/profile'),
                    'submission_id' => $result['submission_id'] ?? null,
                ]);
                return;
            }

            header('Location: ' . $this->route('/profile?status=updated'));
            exit;
        } catch (Exception $e) {
            $this->jsonOrRedirectError('Erro ao atualizar: ' . $e->getMessage(), '/forms', 500);
        }
    }

    private function authenticatedUserId(): int
    {
        $user = Auth::user();
        return is_array($user) ? (int) ($user['id'] ?? 0) : 0;
    }

    private function route(string $path): string
    {
        $base = $_SERVER['APP_BASE_PATH'] ?? '';
        return rtrim($base, '/') . $path;
    }

    private function expectsJson(): bool
    {
        $header = $_SERVER['HTTP_X_REQUESTED_WITH'] ?? '';
        return strtolower((string) $header) === 'xmlhttprequest';
    }

    private function jsonResponse(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    private function jsonOrRedirectError(string $message, string $path, int $code): void
    {
        if ($this->expectsJson()) {
            $this->jsonResponse(['ok' => false, 'message' => $message], $code);
            return;
        }

        header('Location: ' . $this->route($path . '?error=' . urlencode($message)));
        exit;
    }
}
