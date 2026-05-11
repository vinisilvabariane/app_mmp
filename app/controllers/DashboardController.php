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
    private const QUESTION_TYPES = ['dissertativa', 'intensidade_1_5', 'multipla_escolha'];

    public function index(): void
    {
        $this->requireAdmin();
        $metrics = (new MetricModel())->getAll(true);
        $questions = (new QuestionModel())->getAllWithRelations();
        $summary = [
            'active_metrics' => count(array_filter($metrics, static fn (array $metric): bool => (int) ($metric['active'] ?? 0) === 1)),
            'inactive_metrics' => count(array_filter($metrics, static fn (array $metric): bool => (int) ($metric['active'] ?? 0) !== 1)),
            'active_questions' => count(array_filter($questions, static fn (array $question): bool => (int) ($question['active'] ?? 0) === 1)),
            'inactive_questions' => count(array_filter($questions, static fn (array $question): bool => (int) ($question['active'] ?? 0) !== 1)),
            'active_mappings' => array_sum(array_map(static fn (array $metric): int => (int) ($metric['active_mapping_count'] ?? 0), $metrics)),
        ];
        require_once __DIR__ . '/../views/dashboard/index.php';
    }

    public function questions(): void
    {
        $this->requireAdmin();

        $metricModel = new MetricModel();
        $questionModel = new QuestionModel();

        $metrics = $metricModel->getAll(false);
        $questions = array_map(function (array $question): array {
            $question['config_fields'] = $this->extractQuestionConfigFields($question['config_json'] ?? null);
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

        if ($questionKey === '' || $enunciado === '' || $questionType === '' || $questionOrder <= 0) {
            throw new RuntimeException('Preencha chave, enunciado, tipo e ordem da pergunta.');
        }

        if (!in_array($questionType, self::QUESTION_TYPES, true)) {
            throw new RuntimeException('Tipo de pergunta invalido.');
        }

        $options = $this->parseOptionsInput(
            $input['option_values'] ?? [],
            $input['option_labels'] ?? [],
            $questionType
        );

        $affects = $this->parseAffectsInput(
            $input['affect_metric_ids'] ?? [],
            $input['affect_option_values'] ?? [],
            $input['affect_weights'] ?? [],
            $input['affect_impact_types'] ?? [],
            $options
        );

        return [
            'question_key' => $questionKey,
            'enunciado' => $enunciado,
            'question_type' => $questionType,
            'allows_multiple' => isset($input['allows_multiple']) ? 1 : 0,
            'is_required' => isset($input['is_required']) ? 1 : 0,
            'question_order' => $questionOrder,
            'config_json' => $this->buildQuestionConfigJson($input, $questionType),
            'options' => $options,
            'affects' => $affects,
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

    private function parseOptionsInput(mixed $valuesInput, mixed $labelsInput, string $questionType): array
    {
        $values = is_array($valuesInput) ? array_values($valuesInput) : [];
        $labels = is_array($labelsInput) ? array_values($labelsInput) : [];
        $options = [];
        $seenValues = [];

        $max = max(count($values), count($labels));
        for ($index = 0; $index < $max; $index++) {
            $value = trim((string) ($values[$index] ?? ''));
            $label = trim((string) ($labels[$index] ?? ''));

            if ($value === '' && $label === '') {
                continue;
            }

            if ($value === '' || $label === '') {
                throw new RuntimeException('Preencha valor e rótulo em todas as opções informadas.');
            }

            if (isset($seenValues[$value])) {
                throw new RuntimeException('Os valores das opções de uma mesma pergunta precisam ser únicos.');
            }

            $options[] = [
                'value' => $value,
                'label' => $label,
            ];
            $seenValues[$value] = true;
        }

        if ($questionType === 'multipla_escolha' && count($options) < 2) {
            throw new RuntimeException('Perguntas de múltipla escolha precisam de pelo menos duas opções.');
        }

        return $options;
    }

    private function parseAffectsInput(
        mixed $metricIdsInput,
        mixed $optionValuesInput,
        mixed $weightsInput,
        mixed $impactTypesInput,
        array $options
    ): array
    {
        $metricMap = [];
        foreach ((new MetricModel())->getAll(false) as $metric) {
            $metricMap[(int) $metric['id']] = $metric;
        }

        if ($metricMap === []) {
            throw new RuntimeException('Cadastre pelo menos uma métrica ativa antes de criar perguntas.');
        }

        $metricIds = is_array($metricIdsInput) ? array_values($metricIdsInput) : [];
        $optionValues = is_array($optionValuesInput) ? array_values($optionValuesInput) : [];
        $weights = is_array($weightsInput) ? array_values($weightsInput) : [];
        $impactTypes = is_array($impactTypesInput) ? array_values($impactTypesInput) : [];
        $validOptionValues = array_flip(array_map(
            static fn (array $option): string => (string) $option['value'],
            $options
        ));

        $affects = [];
        $max = max(count($metricIds), count($optionValues), count($weights), count($impactTypes));

        for ($index = 0; $index < $max; $index++) {
            $metricId = (int) ($metricIds[$index] ?? 0);
            $optionValue = trim((string) ($optionValues[$index] ?? ''));
            $weight = trim((string) ($weights[$index] ?? ''));
            $impactType = trim((string) ($impactTypes[$index] ?? 'sum'));

            if ($metricId <= 0 && $optionValue === '' && $weight === '' && $impactType === '') {
                continue;
            }

            if ($metricId <= 0 || !isset($metricMap[$metricId])) {
                throw new RuntimeException('Toda relação precisa apontar para uma métrica ativa cadastrada.');
            }

            if ($optionValue !== '' && !isset($validOptionValues[$optionValue])) {
                throw new RuntimeException('O impacto informado referencia uma opção que não existe na pergunta.');
            }

            if (!is_numeric($weight)) {
                throw new RuntimeException('O peso do impacto deve ser numérico.');
            }

            $affects[] = [
                'metric_id' => $metricId,
                'option_value' => $optionValue,
                'weight' => (float) $weight,
                'impact_type' => $impactType !== '' ? $impactType : 'sum',
            ];
        }

        if ($affects === []) {
            throw new RuntimeException('Cada pergunta precisa afetar pelo menos uma métrica cadastrada.');
        }

        return $affects;
    }

    private function extractQuestionConfigFields(?string $rawJson): array
    {
        if ($rawJson === null || trim($rawJson) === '') {
            return [
                'input' => 'textarea',
                'placeholder' => '',
                'maxlength' => '',
                'scale_labels' => ['', '', '', '', ''],
            ];
        }

        $decoded = json_decode($rawJson, true);
        if (!is_array($decoded)) {
            $decoded = [];
        }

        $scaleLabels = array_values(array_slice(is_array($decoded['escala'] ?? null) ? $decoded['escala'] : [], 0, 5));
        while (count($scaleLabels) < 5) {
            $scaleLabels[] = '';
        }

        return [
            'input' => in_array((string) ($decoded['input'] ?? 'textarea'), ['textarea', 'text'], true) ? (string) ($decoded['input'] ?? 'textarea') : 'textarea',
            'placeholder' => trim((string) (($decoded['attributes']['placeholder'] ?? ''))),
            'maxlength' => isset($decoded['attributes']['maxlength']) && is_numeric($decoded['attributes']['maxlength'])
                ? (string) (int) $decoded['attributes']['maxlength']
                : '',
            'scale_labels' => array_map(static fn (mixed $label): string => trim((string) $label), $scaleLabels),
        ];
    }

    private function buildQuestionConfigJson(array $input, string $questionType): ?string
    {
        $config = [];

        if ($questionType === 'dissertativa') {
            $inputType = trim((string) ($input['text_input_type'] ?? 'textarea'));
            $placeholder = trim((string) ($input['text_placeholder'] ?? ''));
            $maxLength = isset($input['text_maxlength']) ? (int) $input['text_maxlength'] : 0;

            $config['input'] = in_array($inputType, ['textarea', 'text'], true) ? $inputType : 'textarea';

            $attributes = [];
            if ($placeholder !== '') {
                $attributes['placeholder'] = $placeholder;
            }
            if ($maxLength > 0) {
                $attributes['maxlength'] = $maxLength;
            }
            if ($attributes !== []) {
                $config['attributes'] = $attributes;
            }
        }

        if ($questionType === 'intensidade_1_5') {
            $rawLabels = is_array($input['scale_labels'] ?? null) ? array_values($input['scale_labels']) : [];
            $labels = [];
            for ($index = 0; $index < 5; $index++) {
                $labels[] = trim((string) ($rawLabels[$index] ?? ''));
            }

            if (count(array_filter($labels, static fn (string $label): bool => $label !== '')) > 0) {
                $config['escala'] = $labels;
            }
        }

        if ($config === []) {
            return null;
        }

        $encoded = json_encode($config, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        return is_string($encoded) ? $encoded : null;
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
