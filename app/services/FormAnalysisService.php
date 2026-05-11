<?php

namespace App\services;

use App\config\Connection;
use App\models\LearningRouteModel;
use App\models\MetricModel;
use App\models\QuestionModel;
use App\models\RegisterForms;
use PDO;
use RuntimeException;

class FormAnalysisService
{
    public function submit(int $userId, array $answers): array
    {
        $questionModel = new QuestionModel();
        $questions = $questionModel->getActiveWithRelations();
        if ($questions === []) {
            throw new RuntimeException('Nenhuma pergunta ativa esta disponivel no formulario.');
        }

        $submissionId = (new RegisterForms())->saveAnswers($userId, $answers);
        $contexts = $this->buildContexts($questions, $answers);
        $registeredMetrics = $this->calculateRegisteredMetrics($questions, $answers);

        $apiClient = new RouteApiClient();
        $inferredMetrics = $apiClient->generateMetrics($contexts);
        $studentMetrics = $this->mergeMetrics($registeredMetrics, $inferredMetrics);
        $routeQuestion = $this->buildRouteQuestion($questions, $answers);
        $routeResponse = $apiClient->generateRoute($routeQuestion, $studentMetrics, $contexts);
        $route = is_array($routeResponse['route'] ?? null) ? $routeResponse['route'] : [];

        $this->persistUserMetrics($userId, $studentMetrics);
        (new LearningRouteModel())->saveGeneratedRoute($userId, $submissionId, $routeQuestion, $studentMetrics, $route);

        (new RegisterForms())->updateSubmissionStatus($submissionId, 'route_generated');

        return [
            'submission_id' => $submissionId,
            'student_metrics' => $studentMetrics,
            'route' => $route,
        ];
    }

    private function buildContexts(array $questions, array $answers): array
    {
        $contexts = [];

        foreach ($questions as $question) {
            $questionKey = (string) $question['question_key'];
            $answerValues = $this->normalizeAnswerValues($answers[$questionKey] ?? null);
            if ($answerValues === []) {
                continue;
            }

            $contexts[] = [
                'question' => (string) $question['enunciado'],
                'answer' => $this->buildAnswerText($question, $answerValues),
            ];
        }

        if ($contexts === []) {
            throw new RuntimeException('Nao foi possivel montar o contexto textual das respostas.');
        }

        return $contexts;
    }

    private function calculateRegisteredMetrics(array $questions, array $answers): array
    {
        $accumulators = [];
        foreach ($questions as $question) {
            $answerValues = $this->normalizeAnswerValues($answers[(string) $question['question_key']] ?? null);
            if ($answerValues === []) {
                continue;
            }

            foreach ($question['affects'] ?? [] as $affect) {
                if ((int) ($affect['active'] ?? 1) !== 1) {
                    continue;
                }

                $metricKey = (string) ($affect['metric_key'] ?? '');
                if ($metricKey === '') {
                    continue;
                }

                $weight = (float) ($affect['weight'] ?? 1);
                if ($weight <= 0) {
                    continue;
                }

                $selectedOptionValue = trim((string) ($affect['option_value'] ?? ''));
                if ($selectedOptionValue !== '' && !in_array($selectedOptionValue, $answerValues, true)) {
                    continue;
                }

                $score = $this->resolveAffectScore($question, $answerValues, (string) ($affect['impact_type'] ?? 'sum'));
                $accumulators[$metricKey]['weighted_sum'] = ($accumulators[$metricKey]['weighted_sum'] ?? 0.0) + ($score * $weight);
                $accumulators[$metricKey]['weight_total'] = ($accumulators[$metricKey]['weight_total'] ?? 0.0) + $weight;
            }
        }

        $metrics = [];
        foreach ($accumulators as $metricKey => $bucket) {
            $weightTotal = (float) ($bucket['weight_total'] ?? 0);
            if ($weightTotal <= 0) {
                continue;
            }

            $metrics[$metricKey] = round(
                max(0, min(100, ((float) $bucket['weighted_sum']) / $weightTotal)),
                2
            );
        }

        return $metrics;
    }

    private function resolveAffectScore(array $question, array $answerValues, string $impactType): float
    {
        $normalizedImpact = strtolower(trim($impactType));
        $questionType = (string) $question['question_type'];

        if ($questionType === 'dissertativa') {
            return 100.0;
        }

        if ($questionType === 'intensidade_1_5') {
            $value = (int) ($answerValues[0] ?? 0);
            $value = max(1, min(5, $value));
            $score = (($value - 1) / 4) * 100;

            if (in_array($normalizedImpact, ['inverse', 'inverse_scale', 'risk'], true)) {
                return 100 - $score;
            }

            return $score;
        }

        if ($questionType === 'multipla_escolha') {
            if (in_array($normalizedImpact, ['inverse', 'inverse_scale', 'risk'], true)) {
                return 0.0;
            }

            return 100.0;
        }

        return 50.0;
    }

    private function mergeMetrics(array $registeredMetrics, array $inferredMetrics): array
    {
        $merged = [];

        foreach ($inferredMetrics as $metricKey => $value) {
            if (!is_numeric($value)) {
                continue;
            }

            $merged[(string) $metricKey] = round((float) $value, 2);
        }

        foreach ($registeredMetrics as $metricKey => $value) {
            if (!is_numeric($value)) {
                continue;
            }

            $merged[(string) $metricKey] = round((float) $value, 2);
        }

        foreach ((new MetricModel())->getAll(false) as $metricRow) {
            $metricKey = (string) ($metricRow['metric_key'] ?? '');
            if ($metricKey === '' || array_key_exists($metricKey, $merged)) {
                continue;
            }

            $merged[$metricKey] = 50.0;
        }

        return $merged;
    }

    private function persistUserMetrics(int $userId, array $studentMetrics): void
    {
        $pdo = Connection::connect();
        $metricRows = (new MetricModel())->getAll(false);

        $metricIdsByKey = [];
        foreach ($metricRows as $metricRow) {
            $metricIdsByKey[(string) $metricRow['metric_key']] = (int) $metricRow['id'];
        }

        $statement = $pdo->prepare(
            'INSERT INTO user_metrics (
                user_id,
                metric_id,
                score,
                calculated_at
            ) VALUES (
                :user_id,
                :metric_id,
                :score,
                CURRENT_TIMESTAMP
            )
            ON DUPLICATE KEY UPDATE
                score = VALUES(score),
                calculated_at = CURRENT_TIMESTAMP'
        );

        foreach ($studentMetrics as $metricKey => $score) {
            if (!isset($metricIdsByKey[$metricKey]) || !is_numeric($score)) {
                continue;
            }

            $statement->bindValue(':user_id', $userId, PDO::PARAM_INT);
            $statement->bindValue(':metric_id', $metricIdsByKey[$metricKey], PDO::PARAM_INT);
            $statement->bindValue(':score', (float) $score);
            $statement->execute();
        }
    }

    private function buildRouteQuestion(array $questions, array $answers): string
    {
        $course = $this->answerForQuestionKey($answers, 'anamnese_q06_curso_graduacao');
        $mathBase = $this->answerForQuestionKey($answers, 'anamnese_q01_base_matematica');
        $mathDifficulties = $this->answerForQuestionKey($answers, 'anamnese_q02_dificuldades_medio');
        $priorDifficulties = $this->answerForQuestionKey($answers, 'anamnese_q16_areas_dificuldade');
        $studyPreference = $this->answerForQuestionKey($answers, 'anamnese_q13_preferencia_conteudos');

        $segments = [
            'Monte uma trilha de aprendizagem personalizada com foco principal na disciplina de Calculo',
        ];

        if ($course !== '') {
            $segments[] = 'para um estudante do curso de ' . $course;
        }

        $contextParts = [];
        if ($mathBase !== '') {
            $contextParts[] = 'base atual em Matematica: ' . $mathBase;
        }
        if ($mathDifficulties !== '') {
            $contextParts[] = 'dificuldades de base: ' . $mathDifficulties;
        }
        if ($priorDifficulties !== '') {
            $contextParts[] = 'outras dificuldades academicas: ' . $priorDifficulties;
        }
        if ($studyPreference !== '') {
            $contextParts[] = 'preferencia de estudo: ' . $studyPreference;
        }

        if ($contextParts !== []) {
            $segments[] = 'considerando o seguinte contexto do formulario: ' . implode('; ', $contextParts);
        }

        $segments[] = 'Priorize fundamentos, prerequisitos matematicos, aplicacoes de Calculo na Engenharia e recursos objetivos para estudo.';

        return implode(' ', $segments) . '.';
    }

    private function answerForQuestionKey(array $answers, string $questionKey): string
    {
        $values = $this->normalizeAnswerValues($answers[$questionKey] ?? null);
        return $values !== [] ? implode(', ', $values) : '';
    }

    private function normalizeAnswerValues(mixed $value): array
    {
        if (is_array($value)) {
            return array_values(array_filter(array_map(
                static fn (mixed $item): string => trim((string) $item),
                $value
            ), static fn (string $item): bool => $item !== ''));
        }

        $normalized = trim((string) $value);
        return $normalized !== '' ? [$normalized] : [];
    }

    private function buildAnswerText(array $question, array $answerValues): string
    {
        if ((string) $question['question_type'] === 'dissertativa') {
            return $answerValues[0];
        }

        $labelsByValue = [];
        foreach ($question['options'] ?? [] as $option) {
            $labelsByValue[(string) $option['option_value']] = (string) $option['option_label'];
        }

        $labels = array_map(
            static fn (string $value): string => $labelsByValue[$value] ?? $value,
            $answerValues
        );

        return implode(', ', $labels);
    }
}
