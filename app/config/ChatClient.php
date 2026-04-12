<?php

namespace App\config;

use RuntimeException;

class ChatClient
{
    public function generateReply(array $history): string
    {
        $apiKey = Env::get('GROQ_API_KEY');
        if ($apiKey === '') {
            throw new RuntimeException('A chave GROQ_API_KEY nao foi configurada.');
        }

        $model = Env::get('GROQ_MODEL', 'llama-3.3-70b-versatile');
        $instruction = trim(Env::get(
            'GROQ_SYSTEM_INSTRUCTION',
            'Voce e um assistente util do sistema Map My Path. Responda em portugues do Brasil com clareza e objetividade.'
        ));

        $messages = $this->buildMessages($history, $instruction);
        if ($messages === []) {
            throw new RuntimeException('Nenhuma mensagem valida foi enviada ao assistente.');
        }

        $payload = [
            'model' => $model,
            'messages' => $messages,
            'temperature' => 0.7,
            'max_completion_tokens' => 800,
        ];

        $url = 'https://api.groq.com/openai/v1/chat/completions';

        $response = $this->postJson($url, $payload, $apiKey);
        return $this->extractText($response);
    }

    private function buildMessages(array $history, string $instruction): array
    {
        $messages = [];

        if ($instruction !== '') {
            $messages[] = [
                'role' => 'system',
                'content' => $instruction,
            ];
        }

        foreach ($history as $message) {
            if (!is_array($message)) {
                continue;
            }

            $role = (string) ($message['role'] ?? '');
            $text = trim((string) ($message['text'] ?? ''));

            if ($text === '') {
                continue;
            }

            $messages[] = [
                'role' => $role === 'assistant' ? 'assistant' : 'user',
                'content' => $text,
            ];
        }

        return $messages;
    }

    private function postJson(string $url, array $payload, string $apiKey): array
    {
        $json = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if (!is_string($json)) {
            throw new RuntimeException('Falha ao serializar a requisicao para o assistente.');
        }

        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => implode("\r\n", [
                    'Content-Type: application/json',
                    'Accept: application/json',
                    'Authorization: Bearer ' . $apiKey,
                ]),
                'content' => $json,
                'ignore_errors' => true,
                'timeout' => 30,
            ],
        ]);

        $result = @file_get_contents($url, false, $context);
        $statusCode = $this->extractStatusCode($http_response_header ?? []);

        if ($result === false) {
            throw new RuntimeException('Nao foi possivel conectar com a API do assistente.');
        }

        $decoded = json_decode($result, true);
        if (!is_array($decoded)) {
            throw new RuntimeException('A API do assistente retornou uma resposta invalida.');
        }

        if ($statusCode >= 400) {
            $message = (string) ($decoded['error']['message'] ?? 'Erro ao consultar o assistente.');
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
        $candidate = $response['choices'][0]['message']['content'] ?? '';
        $text = trim((string) $candidate);

        if ($text === '') {
            throw new RuntimeException('O assistente nao retornou texto para esta solicitacao.');
        }

        return $text;
    }
}
