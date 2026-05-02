<?php

namespace App\models;

use App\config\Connection;
use PDO;
use Throwable;

class QuestionModel
{
    public function getActiveWithRelations(): array
    {
        return array_values(array_filter(
            $this->getAllWithRelations(),
            static fn (array $question): bool => (int) ($question['active'] ?? 0) === 1
        ));
    }

    public function getAllWithRelations(): array
    {
        $pdo = Connection::connect();
        $questionsSql = 'SELECT *
            FROM questions
            ORDER BY active DESC, question_order ASC, id ASC';
        $questions = $pdo->query($questionsSql)->fetchAll(PDO::FETCH_ASSOC);

        if ($questions === []) {
            return [];
        }

        $questionIds = array_map(static fn (array $question): int => (int) $question['id'], $questions);
        $placeholders = implode(',', array_fill(0, count($questionIds), '?'));

        $optionsSql = "SELECT *
            FROM question_options
            WHERE question_id IN ($placeholders)
            ORDER BY option_order ASC, id ASC";
        $optionsStatement = $pdo->prepare($optionsSql);
        foreach ($questionIds as $index => $questionId) {
            $optionsStatement->bindValue($index + 1, $questionId, PDO::PARAM_INT);
        }
        $optionsStatement->execute();
        $optionsRows = $optionsStatement->fetchAll(PDO::FETCH_ASSOC);

        $affectsSql = "SELECT qma.*, m.metric_key, m.name AS metric_name, qo.option_value
            FROM question_metrics_affects qma
            INNER JOIN metrics m ON m.id = qma.metric_id
            LEFT JOIN question_options qo ON qo.id = qma.question_option_id
            WHERE qma.question_id IN ($placeholders)
            ORDER BY qma.active DESC, m.metric_key ASC, qma.id ASC";
        $affectsStatement = $pdo->prepare($affectsSql);
        foreach ($questionIds as $index => $questionId) {
            $affectsStatement->bindValue($index + 1, $questionId, PDO::PARAM_INT);
        }
        $affectsStatement->execute();
        $affectsRows = $affectsStatement->fetchAll(PDO::FETCH_ASSOC);

        $optionsByQuestion = [];
        foreach ($optionsRows as $row) {
            $optionsByQuestion[(int) $row['question_id']][] = $row;
        }

        $affectsByQuestion = [];
        foreach ($affectsRows as $row) {
            $affectsByQuestion[(int) $row['question_id']][] = $row;
        }

        foreach ($questions as &$question) {
            $questionId = (int) $question['id'];
            $question['options'] = $optionsByQuestion[$questionId] ?? [];
            $question['affects'] = $affectsByQuestion[$questionId] ?? [];
        }
        unset($question);

        return $questions;
    }

    public function create(array $data): void
    {
        $pdo = Connection::connect();
        $pdo->beginTransaction();

        try {
            $questionId = $this->insertQuestion($pdo, $data);
            $optionMap = $this->replaceOptions($pdo, $questionId, $data['options'] ?? []);
            $this->replaceAffects($pdo, $questionId, $data['affects'] ?? [], $optionMap);
            $pdo->commit();
        } catch (Throwable $exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            throw $exception;
        }
    }

    public function update(int $id, array $data): void
    {
        $pdo = Connection::connect();
        $pdo->beginTransaction();

        try {
            $sql = 'UPDATE questions
                SET question_key = :question_key,
                    enunciado = :enunciado,
                    question_type = :question_type,
                    allows_multiple = :allows_multiple,
                    is_required = :is_required,
                    question_order = :question_order,
                    config_json = :config_json
                WHERE id = :id';
            $statement = $pdo->prepare($sql);
            $statement->bindValue(':question_key', $data['question_key'], PDO::PARAM_STR);
            $statement->bindValue(':enunciado', $data['enunciado'], PDO::PARAM_STR);
            $statement->bindValue(':question_type', $data['question_type'], PDO::PARAM_STR);
            $statement->bindValue(':allows_multiple', $data['allows_multiple'], PDO::PARAM_INT);
            $statement->bindValue(':is_required', $data['is_required'], PDO::PARAM_INT);
            $statement->bindValue(':question_order', $data['question_order'], PDO::PARAM_INT);
            $statement->bindValue(':config_json', $data['config_json'], $data['config_json'] !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
            $statement->bindValue(':id', $id, PDO::PARAM_INT);
            $statement->execute();

            $optionMap = $this->replaceOptions($pdo, $id, $data['options'] ?? []);
            $this->replaceAffects($pdo, $id, $data['affects'] ?? [], $optionMap);
            $pdo->commit();
        } catch (Throwable $exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            throw $exception;
        }
    }

    public function softDelete(int $id): void
    {
        $pdo = Connection::connect();
        $pdo->beginTransaction();

        try {
            $questionSql = 'UPDATE questions SET active = 0 WHERE id = :id';
            $questionStatement = $pdo->prepare($questionSql);
            $questionStatement->bindValue(':id', $id, PDO::PARAM_INT);
            $questionStatement->execute();

            $optionsSql = 'UPDATE question_options SET active = 0 WHERE question_id = :id';
            $optionsStatement = $pdo->prepare($optionsSql);
            $optionsStatement->bindValue(':id', $id, PDO::PARAM_INT);
            $optionsStatement->execute();

            $affectsSql = 'UPDATE question_metrics_affects SET active = 0 WHERE question_id = :id';
            $affectsStatement = $pdo->prepare($affectsSql);
            $affectsStatement->bindValue(':id', $id, PDO::PARAM_INT);
            $affectsStatement->execute();

            $pdo->commit();
        } catch (Throwable $exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            throw $exception;
        }
    }

    private function insertQuestion(PDO $pdo, array $data): int
    {
        $sql = 'INSERT INTO questions (
                question_key,
                enunciado,
                question_type,
                allows_multiple,
                is_required,
                question_order,
                config_json,
                active
            ) VALUES (
                :question_key,
                :enunciado,
                :question_type,
                :allows_multiple,
                :is_required,
                :question_order,
                :config_json,
                1
            )';
        $statement = $pdo->prepare($sql);
        $statement->bindValue(':question_key', $data['question_key'], PDO::PARAM_STR);
        $statement->bindValue(':enunciado', $data['enunciado'], PDO::PARAM_STR);
        $statement->bindValue(':question_type', $data['question_type'], PDO::PARAM_STR);
        $statement->bindValue(':allows_multiple', $data['allows_multiple'], PDO::PARAM_INT);
        $statement->bindValue(':is_required', $data['is_required'], PDO::PARAM_INT);
        $statement->bindValue(':question_order', $data['question_order'], PDO::PARAM_INT);
        $statement->bindValue(':config_json', $data['config_json'], $data['config_json'] !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
        $statement->execute();

        return (int) $pdo->lastInsertId();
    }

    private function replaceOptions(PDO $pdo, int $questionId, array $options): array
    {
        $deleteSql = 'DELETE FROM question_options WHERE question_id = :question_id';
        $deleteStatement = $pdo->prepare($deleteSql);
        $deleteStatement->bindValue(':question_id', $questionId, PDO::PARAM_INT);
        $deleteStatement->execute();

        $optionMap = [];
        $insertSql = 'INSERT INTO question_options (
                question_id,
                option_value,
                option_label,
                option_order,
                active
            ) VALUES (
                :question_id,
                :option_value,
                :option_label,
                :option_order,
                1
            )';
        $insertStatement = $pdo->prepare($insertSql);

        foreach ($options as $index => $option) {
            $insertStatement->bindValue(':question_id', $questionId, PDO::PARAM_INT);
            $insertStatement->bindValue(':option_value', $option['value'], PDO::PARAM_STR);
            $insertStatement->bindValue(':option_label', $option['label'], PDO::PARAM_STR);
            $insertStatement->bindValue(':option_order', $index + 1, PDO::PARAM_INT);
            $insertStatement->execute();

            $optionMap[$option['value']] = (int) $pdo->lastInsertId();
        }

        return $optionMap;
    }

    private function replaceAffects(PDO $pdo, int $questionId, array $affects, array $optionMap): void
    {
        $deleteSql = 'DELETE FROM question_metrics_affects WHERE question_id = :question_id';
        $deleteStatement = $pdo->prepare($deleteSql);
        $deleteStatement->bindValue(':question_id', $questionId, PDO::PARAM_INT);
        $deleteStatement->execute();

        $insertSql = 'INSERT INTO question_metrics_affects (
                question_id,
                question_option_id,
                metric_id,
                weight,
                impact_type,
                active
            ) VALUES (
                :question_id,
                :question_option_id,
                :metric_id,
                :weight,
                :impact_type,
                1
            )';
        $insertStatement = $pdo->prepare($insertSql);

        foreach ($affects as $affect) {
            $questionOptionId = null;
            $optionValue = $affect['option_value'] ?? '';
            if ($optionValue !== '' && isset($optionMap[$optionValue])) {
                $questionOptionId = $optionMap[$optionValue];
            }

            $insertStatement->bindValue(':question_id', $questionId, PDO::PARAM_INT);
            $insertStatement->bindValue(':question_option_id', $questionOptionId, $questionOptionId !== null ? PDO::PARAM_INT : PDO::PARAM_NULL);
            $insertStatement->bindValue(':metric_id', $affect['metric_id'], PDO::PARAM_INT);
            $insertStatement->bindValue(':weight', $affect['weight']);
            $insertStatement->bindValue(':impact_type', $affect['impact_type'], PDO::PARAM_STR);
            $insertStatement->execute();
        }
    }
}
