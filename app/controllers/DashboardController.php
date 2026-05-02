<?php

namespace App\controllers;

use App\config\Auth;
use App\models\MetricModel;
use App\models\QuestionModel;
use App\services\FormQuestionSyncService;
use RuntimeException;
use Throwable;

class DashboardController
{
    private const FLASH_KEY = 'dashboard_flash';

    public function index(): void
    {
        $this->requireAdmin();
        require_once __DIR__ . '/../views/dashboard/index.php';
    }

    public function questions(): void
    {
        $this->requireAdmin();

        $metricModel = new MetricModel();
        $questionModel = new QuestionModel();

        $metrics = $metricModel->getAll(false);
        $questions = array_map(function (array $question): array {
            $question['options_text'] = $this->formatOptionsText($question['options'] ?? []);
            $question['affects_text'] = $this->formatAffectsText($question['affects'] ?? []);
            return $question;
        }, $questionModel->getAllWithRelations());

        $flash = $this->pullFlash();
        require_once __DIR__ . '/../views/dashboard/questions.php';
    }

    public function createQuestion(): void
    {
        $this->requireAdmin();

        try {
            $data = $this->buildQuestionPayload($_POST);
            (new QuestionModel())->create($data);
            $this->syncFrontendQuestions();
            $this->pushFlash('success', 'Pergunta cadastrada com sucesso.');
        } catch (Throwable $exception) {
            $this->pushFlash('error', $exception->getMessage());
        }

        $this->redirect('/dashboard/questions');
    }

    public function updateQuestion(): void
    {
        $this->requireAdmin();

        try {
            $questionId = isset($_POST['question_id']) ? (int) $_POST['question_id'] : 0;
            if ($questionId <= 0) {
                throw new RuntimeException('Pergunta invalida para atualizacao.');
            }

            $data = $this->buildQuestionPayload($_POST);
            (new QuestionModel())->update($questionId, $data);
            $this->syncFrontendQuestions();
            $this->pushFlash('success', 'Pergunta atualizada com sucesso.');
        } catch (Throwable $exception) {
            $this->pushFlash('error', $exception->getMessage());
        }

        $this->redirect('/dashboard/questions');
    }

    public function deleteQuestion(): void
    {
        $this->requireAdmin();

        try {
            $questionId = isset($_POST['question_id']) ? (int) $_POST['question_id'] : 0;
            if ($questionId <= 0) {
                throw new RuntimeException('Pergunta invalida para exclusao.');
            }

            (new QuestionModel())->softDelete($questionId);
            $this->syncFrontendQuestions();
            $this->pushFlash('success', 'Pergunta desativada com sucesso.');
        } catch (Throwable $exception) {
            $this->pushFlash('error', $exception->getMessage());
        }

        $this->redirect('/dashboard/questions');
    }

    public function metrics(): void
    {
        $this->requireAdmin();

        $metricModel = new MetricModel();
        $metrics = $metricModel->getAll(true);
        $flash = $this->pullFlash();

        require_once __DIR__ . '/../views/dashboard/metrics.php';
    }

    public function createMetric(): void
    {
        $this->requireAdmin();

        try {
            $data = $this->buildMetricPayload($_POST);
            (new MetricModel())->create($data);
            $this->pushFlash('success', 'Metrica cadastrada com sucesso.');
        } catch (Throwable $exception) {
            $this->pushFlash('error', $exception->getMessage());
        }

        $this->redirect('/dashboard/metrics');
    }

    public function updateMetric(): void
    {
        $this->requireAdmin();

        try {
            $metricId = isset($_POST['metric_id']) ? (int) $_POST['metric_id'] : 0;
            if ($metricId <= 0) {
                throw new RuntimeException('Metrica invalida para atualizacao.');
            }

            $data = $this->buildMetricPayload($_POST);
            (new MetricModel())->update($metricId, $data);
            $this->pushFlash('success', 'Metrica atualizada com sucesso.');
        } catch (Throwable $exception) {
            $this->pushFlash('error', $exception->getMessage());
        }

        $this->redirect('/dashboard/metrics');
    }

    public function deleteMetric(): void
    {
        $this->requireAdmin();

        try {
            $metricId = isset($_POST['metric_id']) ? (int) $_POST['metric_id'] : 0;
            if ($metricId <= 0) {
                throw new RuntimeException('Metrica invalida para exclusao.');
            }

            (new MetricModel())->softDelete($metricId);
            $this->pushFlash('success', 'Metrica desativada com sucesso.');
        } catch (Throwable $exception) {
            $this->pushFlash('error', $exception->getMessage());
        }

        $this->redirect('/dashboard/metrics');
    }

    private function requireAdmin(): void
    {
        Auth::requireRole('admin', $this->basePath());
    }

    private function syncFrontendQuestions(): void
    {
        (new FormQuestionSyncService())->syncActiveQuestionsToFile();
    }

    private function buildQuestionPayload(array $input): array
    {
        $questionKey = trim((string) ($input['question_key'] ?? ''));
        $enunciado = trim((string) ($input['enunciado'] ?? ''));
        $questionType = trim((string) ($input['question_type'] ?? ''));
        $questionOrder = isset($input['question_order']) ? (int) $input['question_order'] : 0;
        $configJson = trim((string) ($input['config_json'] ?? ''));

        if ($questionKey === '' || $enunciado === '' || $questionType === '' || $questionOrder <= 0) {
            throw new RuntimeException('Preencha chave, enunciado, tipo e ordem da pergunta.');
        }

        if (!in_array($questionType, ['dissertativa', 'intensidade_1_5', 'multipla_escolha'], true)) {
            throw new RuntimeException('Tipo de pergunta invalido.');
        }

        if ($configJson !== '') {
            json_decode($configJson, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new RuntimeException('O campo config_json precisa ser um JSON valido.');
            }
        }

        return [
            'question_key' => $questionKey,
            'enunciado' => $enunciado,
            'question_type' => $questionType,
            'allows_multiple' => isset($input['allows_multiple']) ? 1 : 0,
            'is_required' => isset($input['is_required']) ? 1 : 0,
            'question_order' => $questionOrder,
            'config_json' => $configJson !== '' ? $configJson : null,
            'options' => $this->parseOptionsText((string) ($input['options_text'] ?? '')),
            'affects' => $this->parseAffectsText((string) ($input['affects_text'] ?? '')),
        ];
    }

    private function buildMetricPayload(array $input): array
    {
        $metricKey = trim((string) ($input['metric_key'] ?? ''));
        $name = trim((string) ($input['name'] ?? ''));
        $description = trim((string) ($input['description'] ?? ''));

        if ($metricKey === '' || $name === '') {
            throw new RuntimeException('Preencha a chave e o nome da metrica.');
        }

        return [
            'metric_key' => $metricKey,
            'name' => $name,
            'description' => $description !== '' ? $description : null,
        ];
    }

    private function parseOptionsText(string $raw): array
    {
        $lines = preg_split('/\r\n|\r|\n/', trim($raw)) ?: [];
        $options = [];

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '') {
                continue;
            }

            $parts = array_map('trim', explode('|', $line, 2));
            if (count($parts) !== 2 || $parts[0] === '' || $parts[1] === '') {
                throw new RuntimeException('Cada opcao deve seguir o formato value|Label.');
            }

            $options[] = [
                'value' => $parts[0],
                'label' => $parts[1],
            ];
        }

        return $options;
    }

    private function parseAffectsText(string $raw): array
    {
        $lines = preg_split('/\r\n|\r|\n/', trim($raw)) ?: [];
        $metricMap = [];

        foreach ((new MetricModel())->getAll(false) as $metric) {
            $metricMap[(string) $metric['metric_key']] = (int) $metric['id'];
        }

        $affects = [];
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '') {
                continue;
            }

            $parts = array_map('trim', explode('|', $line));
            if (count($parts) < 3 || count($parts) > 4) {
                throw new RuntimeException('Cada relacao de metrica deve seguir metric_key|option_value|weight|impact_type.');
            }

            [$metricKey, $optionValue, $weight] = $parts;
            $impactType = $parts[3] ?? 'sum';

            if ($metricKey === '' || !isset($metricMap[$metricKey])) {
                throw new RuntimeException('Foi informada uma metrica inexistente no mapeamento da pergunta.');
            }

            if (!is_numeric($weight)) {
                throw new RuntimeException('O peso do mapeamento de metrica deve ser numerico.');
            }

            $affects[] = [
                'metric_id' => $metricMap[$metricKey],
                'option_value' => $optionValue,
                'weight' => (float) $weight,
                'impact_type' => $impactType !== '' ? $impactType : 'sum',
            ];
        }

        return $affects;
    }

    private function formatOptionsText(array $options): string
    {
        $lines = array_map(static function (array $option): string {
            return sprintf('%s|%s', (string) ($option['option_value'] ?? ''), (string) ($option['option_label'] ?? ''));
        }, $options);

        return implode(PHP_EOL, $lines);
    }

    private function formatAffectsText(array $affects): string
    {
        $lines = array_map(static function (array $affect): string {
            return sprintf(
                '%s|%s|%s|%s',
                (string) ($affect['metric_key'] ?? ''),
                (string) ($affect['option_value'] ?? ''),
                (string) ($affect['weight'] ?? '1'),
                (string) ($affect['impact_type'] ?? 'sum')
            );
        }, $affects);

        return implode(PHP_EOL, $lines);
    }

    private function pushFlash(string $type, string $message): void
    {
        $_SESSION[self::FLASH_KEY] = [
            'type' => $type,
            'message' => $message,
        ];
    }

    private function pullFlash(): ?array
    {
        if (!isset($_SESSION[self::FLASH_KEY]) || !is_array($_SESSION[self::FLASH_KEY])) {
            return null;
        }

        $flash = $_SESSION[self::FLASH_KEY];
        unset($_SESSION[self::FLASH_KEY]);
        return $flash;
    }

    private function redirect(string $path): void
    {
        header('Location: ' . rtrim($this->basePath(), '/') . $path);
        exit;
    }

    private function basePath(): string
    {
        return isset($_SERVER['APP_BASE_PATH']) ? (string) $_SERVER['APP_BASE_PATH'] : '';
    }
}
