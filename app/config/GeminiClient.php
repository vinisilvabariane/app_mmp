<?php

namespace App\config;

use RuntimeException;

class GeminiClient
{
    public function generateReply(array $history): string
    {
        $apiKey = Env::get('GEMINI_API_KEY');
        if ($apiKey === '') {
            throw new RuntimeException('A chave GEMINI_API_KEY nao foi configurada.');
        }

        $model = Env::get('GEMINI_MODEL', 'gemini-2.0-flash');
        $instruction = trim(Env::get(
            'GEMINI_SYSTEM_INSTRUCTION',
            'Voce e um assistente util do sistema Map My Path. Responda em portugues do Brasil com clareza e objetividade.'
        ));

        $contents = $this->buildContents($history);
        if ($contents === []) {
            throw new RuntimeException('Nenhuma mensagem valida foi enviada ao assistente.');
        }

        $payload = [
            'contents' => $contents,
            'generationConfig' => [
                'temperature' => 0.7,
                'maxOutputTokens' => 800,
            ],
        ];

        if ($instruction !== '') {
            $payload['systemInstruction'] = [
                'parts' => [
                    ['text' => $instruction],
                ],
            ];
        }

        $url = sprintf(
            'https://generativelanguage.googleapis.com/v1beta/models/%s:generateContent?key=%s',
            rawurlencode($model),
            rawurlencode($apiKey)
        );

        $response = $this->postJson($url, $payload);
        return $this->extractText($response);
    }

    private function buildContents(array $history): array
    {
        $contents = [];

        foreach ($history as $message) {
            if (!is_array($message)) {
                continue;
            }

            $role = (string) ($message['role'] ?? '');
            $text = trim((string) ($message['text'] ?? ''));

            if ($text === '') {
                continue;
            }

            $contents[] = [
                'role' => $role === 'assistant' ? 'model' : 'user',
                'parts' => [
                    ['text' => $text],
                ],
            ];
        }

        return $contents;
    }

    private function postJson(string $url, array $payload): array
    {
        $json = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if (!is_string($json)) {
            throw new RuntimeException('Falha ao serializar a requisicao para o Gemini.');
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

        $result = @file_get_contents($url, false, $context);
        $statusCode = $this->extractStatusCode($http_response_header ?? []);

        if ($result === false) {
            throw new RuntimeException('Nao foi possivel conectar com a API do Gemini.');
        }

        $decoded = json_decode($result, true);
        if (!is_array($decoded)) {
            throw new RuntimeException('A API do Gemini retornou uma resposta invalida.');
        }

        if ($statusCode >= 400) {
            $message = (string) ($decoded['error']['message'] ?? 'Erro ao consultar o Gemini.');
            throw new RuntimeException($message);
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

    private function extractText(array $response): string
    {
        $candidate = $response['candidates'][0]['content']['parts'][0]['text'] ?? '';
        $text = trim((string) $candidate);

        if ($text === '') {
            throw new RuntimeException('O Gemini nao retornou texto para esta solicitacao.');
        }

        return $text;
    }
}
