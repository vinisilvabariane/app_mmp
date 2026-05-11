<?php

namespace App\models;

use App\config\Connection;
use PDO;
use RuntimeException;
use Throwable;

class RegisterForms
{
    public function saveAnswers(int $userId, array $answers): int
    {
        return $this->persistAnswers($userId, $answers, true);
    }

    public function updateAnswers(int $userId, array $answers): int
    {
        return $this->persistAnswers($userId, $answers, false);
    }

    private function persistAnswers(int $userId, array $answers, bool $createSubmission): int
    {
        $pdo = Connection::connect();
        $questionModel = new QuestionModel();
        $activeQuestions = $questionModel->getActiveWithRelations();

        if ($activeQuestions === []) {
            throw new RuntimeException('Nenhuma pergunta ativa foi cadastrada para o formulario.');
        }

        $questionsByKey = [];
        foreach ($activeQuestions as $question) {
            $questionsByKey[(string) $question['question_key']] = $question;
        }

        $normalizedAnswers = $this->normalizeAnswers($answers, $questionsByKey);

        $pdo->beginTransaction();

        try {
            $submissionId = $this->createSubmission($pdo, $userId);

            if (!$createSubmission) {
                $this->archivePreviousAnswers($pdo, $userId);
            }

            $this->insertAnswers($pdo, $submissionId, $userId, $normalizedAnswers, $questionsByKey);
            $this->updateSubmissionStatusInConnection($pdo, $submissionId, 'submitted');

            $pdo->commit();
            return $submissionId;
        } catch (Throwable $exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            throw $exception;
        }
    }

    private function normalizeAnswers(array $answers, array $questionsByKey): array
    {
        $normalized = [];

        foreach ($questionsByKey as $questionKey => $question) {
            $rawAnswer = $answers[$questionKey] ?? null;
            $questionType = (string) $question['question_type'];
            $isRequired = (int) $question['is_required'] === 1;

            if (is_array($rawAnswer)) {
                $values = array_values(array_filter(array_map(
                    static fn (mixed $value): string => trim((string) $value),
                    $rawAnswer
                ), static fn (string $value): bool => $value !== ''));
            } else {
                $singleValue = trim((string) $rawAnswer);
                $values = $singleValue !== '' ? [$singleValue] : [];
            }

            if ($isRequired && $values === []) {
                throw new RuntimeException(sprintf('A pergunta "%s" precisa ser respondida.', (string) $question['enunciado']));
            }

            if ($questionType === 'multipla_escolha' || $questionType === 'intensidade_1_5') {
                $allowedValues = [];
                foreach ($question['options'] ?? [] as $option) {
                    if ((int) ($option['active'] ?? 1) === 1) {
                        $allowedValues[(string) $option['option_value']] = true;
                    }
                }

                foreach ($values as $value) {
                    if ($allowedValues !== [] && !isset($allowedValues[$value])) {
                        throw new RuntimeException(sprintf(
                            'A resposta "%s" nao e valida para a pergunta "%s".',
                            $value,
                            (string) $question['enunciado']
                        ));
                    }
                }
            }

            $normalized[$questionKey] = [
                'values' => $values,
                'raw' => $rawAnswer,
            ];
        }

        return $normalized;
    }

    private function createSubmission(PDO $pdo, int $userId): int
    {
        $statement = $pdo->prepare('INSERT INTO form_submissions (user_id, status) VALUES (:user_id, :status)');
        $statement->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $statement->bindValue(':status', 'processing', PDO::PARAM_STR);
        $statement->execute();

        return (int) $pdo->lastInsertId();
    }

    private function archivePreviousAnswers(PDO $pdo, int $userId): void
    {
        $statement = $pdo->prepare(
            "UPDATE form_submissions
             SET status = 'superseded'
             WHERE user_id = :user_id
               AND status IN ('submitted', 'processed', 'route_generated')"
        );
        $statement->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $statement->execute();
    }

    private function insertAnswers(
        PDO $pdo,
        int $submissionId,
        int $userId,
        array $normalizedAnswers,
        array $questionsByKey
    ): void {
        $sql = 'INSERT INTO question_answers (
                submission_id,
                user_id,
                question_id,
                answer_text,
                answer_value,
                answer_json
            ) VALUES (
                :submission_id,
                :user_id,
                :question_id,
                :answer_text,
                :answer_value,
                :answer_json
            )';
        $statement = $pdo->prepare($sql);

        foreach ($normalizedAnswers as $questionKey => $answer) {
            $question = $questionsByKey[$questionKey];
            $values = $answer['values'];

            if ($values === []) {
                continue;
            }

            $questionType = (string) $question['question_type'];
            $answerText = $this->buildAnswerText($questionType, $values, $question);
            $answerValue = count($values) === 1 ? $values[0] : null;
            $answerJson = count($values) > 1 ? json_encode($values, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : null;

            $statement->bindValue(':submission_id', $submissionId, PDO::PARAM_INT);
            $statement->bindValue(':user_id', $userId, PDO::PARAM_INT);
            $statement->bindValue(':question_id', (int) $question['id'], PDO::PARAM_INT);
            $statement->bindValue(':answer_text', $answerText, PDO::PARAM_STR);
            $statement->bindValue(':answer_value', $answerValue, $answerValue !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
            $statement->bindValue(':answer_json', $answerJson, $answerJson !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
            $statement->execute();
        }
    }

    private function buildAnswerText(string $questionType, array $values, array $question): string
    {
        if ($questionType === 'dissertativa') {
            return (string) $values[0];
        }

        $labelsByValue = [];
        foreach ($question['options'] ?? [] as $option) {
            $labelsByValue[(string) $option['option_value']] = (string) $option['option_label'];
        }

        $labels = array_map(
            static fn (string $value): string => $labelsByValue[$value] ?? $value,
            $values
        );

        return implode(', ', $labels);
    }

    public function updateSubmissionStatus(int $submissionId, string $status): void
    {
        $pdo = Connection::connect();
        $this->updateSubmissionStatusInConnection($pdo, $submissionId, $status);
    }

    private function updateSubmissionStatusInConnection(PDO $pdo, int $submissionId, string $status): void
    {
        $statement = $pdo->prepare('UPDATE form_submissions SET status = :status WHERE id = :id');
        $statement->bindValue(':status', $status, PDO::PARAM_STR);
        $statement->bindValue(':id', $submissionId, PDO::PARAM_INT);
        $statement->execute();
    }
}
