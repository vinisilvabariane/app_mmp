<?php

namespace App\controllers;

use App\config\Auth;
use App\services\FormQuestionSyncService;
use App\models\Question;
use App\models\QuestionOption;
use App\models\QuestionModel;
use Exception;

class FormsController {

    public function index(): void
    {
        // Aqui vai garantir que o aluno tenha logado
        Auth::requireAuth(isset($_SERVER['APP_BASE_PATH']) ? (string)$_SERVER['APP_BASE_PATH'] : '');
        (new FormQuestionSyncService())->syncActiveQuestionsToFile();
        require_once __DIR__ . '/../views/forms/index.php';
    }


    public function saveAnswers(array $data): void
    {
       
        $answers = $data['answers'] ?? [];

        if (empty($answers)) {
            $this->jsonOrRedirectError('Você precisa responder as questões para continuar.', '/forms', 422);
            return;
        }

        try {
          
            $userId = $_SESSION['user_id'] ?? 0;

            if ($userId === 0) {
                throw new Exception("Usuário não identificado.");
            }


            $registerModel = new \App\models\RegisterForms();
            $registerModel->saveAnswers($userId, $answers);

      
            if ($this->expectsJson()) {
                $this->jsonResponse([
                    'ok' => true,
                    'message' => 'Respostas salvas com sucesso!',
                    'redirect' => $this->route('/home?status=success')
                ]);
                return;
            }

            header('Location: ' . $this->route('/home?status=success'));
            exit;

        } catch (Exception $e) {
            $this->jsonOrRedirectError('Erro ao salvar respostas: ' . $e->getMessage(), '/forms', 500);
        }
    }

    public function update(array $data): void 
    {
     
        $userId = $_SESSION['user_id'] ?? 0;
        
       
        $answers = $data['answers'] ?? [];

        if ($userId === 0 || empty($answers)) {
            $this->jsonOrRedirectError('Dados inválidos para atualização.', '/forms', 400);
            return;
        }

        try {
            $model = new \App\models\RegisterForms();
            $model->updateAnswers($userId, $answers);

            if ($this->expectsJson()) {
                $this->jsonResponse([
                    'ok' => true,
                    'message' => 'Questionário atualizado com sucesso!',
                    'redirect' => $this->route('/home')
                ]);
                return;
            }

            header('Location: ' . $this->route('/home?status=updated'));
            exit;

        } catch (\Exception $e) {
            $this->jsonOrRedirectError('Erro ao atualizar: ' . $e->getMessage(), '/forms', 500);
        }
    }

    private function route(string $path): string {
        $base = $_SERVER['APP_BASE_PATH'] ?? '';
        return rtrim($base, '/') . $path;
    }

    private function expectsJson(): bool {
        $header = $_SERVER['HTTP_X_REQUESTED_WITH'] ?? '';
        return strtolower((string)$header) === 'xmlhttprequest';
    }


    private function jsonResponse(array $data, int $statusCode = 200): void {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    private function jsonOrRedirectError(string $message, string $path, int $code): void {
        if ($this->expectsJson()) {
            $this->jsonResponse(['ok' => false, 'message' => $message], $code);
            return;
        }
        header('Location: ' . $this->route($path . '?error=' . urlencode($message)));
        exit;
    }
}
