<?php

namespace App\services;

use App\config\Env;
use RuntimeException;

class RouteApiClient
{
    public function generateMetrics(array $educationalFormResponses): array
    {
        return $this->postWithFallback(
            [
                '/routes/generate-metrics',
                '/generate-metrics',
            ],
            [
                'educational_form_responses' => $educationalFormResponses,
            ]
        );
    }

    public function generateRoute(string $question, array $studentMetrics, array $educationalFormResponses): array
    {
        return $this->postWithFallback(
            [
                '/routes/generate',
                '/generate',
            ],
            [
                'question' => $question,
                'student_metrics' => $studentMetrics,
                'educational_form_responses' => $educationalFormResponses,
            ]
        );
    }

    private function postWithFallback(array $paths, array $payload): array
    {
        $lastException = null;

        foreach ($paths as $path) {
            try {
                return $this->postJson($path, $payload);
            } catch (RuntimeException $exception) {
                $lastException = $exception;
                if (!str_contains($exception->getMessage(), '404')) {
                    break;
                }
            }
        }

        throw $lastException ?? new RuntimeException('Nao foi possivel consultar a API de trilha.');
    }

    private function postJson(string $path, array $payload): array
    {
        $baseUrl = rtrim(Env::get('IA_API_BASE_URL', 'http://ia-api:8000'), '/');
        $json = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        if (!is_string($json)) {
            throw new RuntimeException('Falha ao serializar a requisicao da trilha.');
        }

        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => implode("\r\n", [
                    'Content-Type: application/json',
                    'Accept: application/json',
                ]),
                'content' => $json,
                'ignore_errors' => true,
                'timeout' => 30,
            ],
        ]);

        $result = @file_get_contents($baseUrl . $path, false, $context);
        $statusCode = $this->extractStatusCode($http_response_header ?? []);

        if ($result === false) {
            throw new RuntimeException('Nao foi possivel conectar com a API de trilha.');
        }

        $decoded = json_decode($result, true);
        if (!is_array($decoded)) {
            throw new RuntimeException('A API de trilha retornou uma resposta invalida.');
        }

        if ($statusCode >= 400) {
            $detail = (string) ($decoded['detail'] ?? $decoded['error'] ?? ('Erro HTTP ' . $statusCode));
            throw new RuntimeException($statusCode . ': ' . $detail);
        }

        return $decoded;
    }

    private function extractStatusCode(array $headers): int
    {
        foreach ($headers as $header) {
            if (preg_match('/^HTTP\/\S+\s+(\d{3})/', (string) $header, $matches) === 1) {
                return (int) $matches[1];
            }
        }

        return 0;
    }
}
