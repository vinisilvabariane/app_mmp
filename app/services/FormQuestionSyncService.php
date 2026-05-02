<?php

namespace App\services;

use App\models\QuestionModel;
use RuntimeException;

class FormQuestionSyncService
{
    public function syncActiveQuestionsToFile(): void
    {
        $questions = (new QuestionModel())->getActiveWithRelations();
        $payload = array_map([$this, 'mapQuestionToFrontend'], $questions);

        $script = "window.FORM_QUESTION_DEFINITIONS = " . json_encode(
            $payload,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        ) . PHP_EOL;

        $target = dirname(__DIR__, 2) . '/public/js/forms/questions.js';
        $result = @file_put_contents($target, $script);

        if ($result === false) {
            throw new RuntimeException('Nao foi possivel atualizar o arquivo questions.js com as perguntas ativas.');
        }
    }

    private function mapQuestionToFrontend(array $question): array
    {
        $mapped = [
            'id' => (string) $question['question_key'],
            'enunciado' => (string) $question['enunciado'],
            'tipo' => (string) $question['question_type'],
            'obrigatoria' => (int) $question['is_required'] === 1,
        ];

        $config = [];
        if (!empty($question['config_json'])) {
            $decoded = json_decode((string) $question['config_json'], true);
            if (is_array($decoded)) {
                $config = $decoded;
            }
        }

        if ($mapped['tipo'] === 'multipla_escolha') {
            $mapped['multipla'] = (int) $question['allows_multiple'] === 1;
            $mapped['opcoes'] = array_values(array_map(static function (array $option): array {
                return [
                    'value' => (string) $option['option_value'],
                    'label' => (string) $option['option_label'],
                ];
            }, $question['options'] ?? []));
        }

        foreach ($config as $key => $value) {
            $mapped[$key] = $value;
        }

        return $mapped;
    }
}
