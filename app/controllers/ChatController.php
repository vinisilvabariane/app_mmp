<?php

namespace App\Controllers;

use App\config\Auth;
use App\config\ChatClient;
use RuntimeException;

class ChatController
{
    public function index(): void
    {
        Auth::requireAuth($this->basePath());
        require_once __DIR__ . '/../views/chat/index.php';
    }

    public function message(): void
    {
        if (!$this->isAuthenticated()) {
            $this->jsonResponse([
                'ok' => false,
                'message' => 'Sua sessao expirou. Faca login novamente.',
            ], 401);
            return;
        }

        if ($this->requestMethod() !== 'POST') {
            $this->jsonResponse([
                'ok' => false,
                'message' => 'Metodo nao permitido.',
            ], 405);
            return;
        }

        $payload = json_decode(file_get_contents('php://input') ?: '', true);
        $message = trim((string) ($payload['message'] ?? ''));
        $history = is_array($payload['history'] ?? null) ? $payload['history'] : [];

        if ($message === '') {
            $this->jsonResponse([
                'ok' => false,
                'message' => 'Digite uma mensagem antes de enviar.',
            ], 422);
            return;
        }

        $history[] = [
            'role' => 'user',
            'text' => $message,
        ];

        try {
            $client = new ChatClient();
            $reply = $client->generateReply(array_slice($history, -20));

            $this->jsonResponse([
                'ok' => true,
                'reply' => $reply,
            ]);
        } catch (RuntimeException $exception) {
            error_log('[ChatController] Assistant provider error: ' . $exception->getMessage());

            $this->jsonResponse([
                'ok' => false,
                'message' => $this->friendlyErrorMessage($exception->getMessage()),
            ], $this->friendlyErrorStatusCode($exception->getMessage()));
        }
    }

    private function basePath(): string
    {
        return isset($_SERVER['APP_BASE_PATH']) ? (string) $_SERVER['APP_BASE_PATH'] : '';
    }

    private function isAuthenticated(): bool
    {
        return Auth::check();
    }

    private function requestMethod(): string
    {
        return strtoupper((string) ($_SERVER['REQUEST_METHOD'] ?? 'GET'));
    }

    private function friendlyErrorMessage(string $message): string
    {
        $normalized = strtolower($message);

        if (
            str_contains($normalized, 'quota exceeded') ||
            str_contains($normalized, 'rate limit') ||
            str_contains($normalized, 'resource_exhausted')
        ) {
            return 'O assistente esta temporariamente indisponivel por limite de uso. Tente novamente em instantes.';
        }

        if (str_contains($normalized, 'api key') || str_contains($normalized, 'api_key')) {
            return 'O assistente nao esta configurado corretamente no momento.';
        }

        if (str_contains($normalized, 'nao foi possivel conectar')) {
            return 'Nao foi possivel conectar ao assistente agora. Tente novamente em instantes.';
        }

        if (
            str_contains($normalized, 'resposta invalida') ||
            str_contains($normalized, 'nao retornou texto')
        ) {
            return 'O assistente nao conseguiu gerar uma resposta valida agora. Tente novamente.';
        }

        return 'Nao foi possivel processar sua mensagem agora. Tente novamente em alguns instantes.';
    }

    private function friendlyErrorStatusCode(string $message): int
    {
        $normalized = strtolower($message);

        if (
            str_contains($normalized, 'quota exceeded') ||
            str_contains($normalized, 'rate limit') ||
            str_contains($normalized, 'resource_exhausted')
        ) {
            return 429;
        }

        if (str_contains($normalized, 'api key') || str_contains($normalized, 'api_key')) {
            return 503;
        }

        return 500;
    }

    private function jsonResponse(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}
