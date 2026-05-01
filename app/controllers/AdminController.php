<?php

namespace App\controllers;

use App\config\Auth;
use App\models\Question;
use App\models\QuestionOption;
use App\models\QuestionModel;
use Exception;

class AdminController
{
    public function index(): void

    {
        Auth::requireAuth(isset($_SERVER['APP_BASE_PATH']) ? (string)$_SERVER['APP_BASE_PATH'] : '');
        require_once __DIR__ . '/../views/admin/index.php';
    }

    public function save(array $data): void {
        if (empty($data)) { //aqui estou verificando se data é vazia 
            if($this->expectsJson()){
                $this->jsonResponse(['ok' => false, 'message' => 'O formulário está vazioo'], 422);
                return;
            }
            header('Location: ' . $this->route('/admin?error=vazio'));
            exit;
        }
        // ?? varifica se a string é null, se for retorna '' 
        $name = trim((string)($data['name'] ?? ''));
        $description = trim((string)($data['description']?? ''));
        $order = (int)($data['order']);
        $options = $data['options'] ?? [];

        if($name === ''){
            $this->jsonOrRedirectError('O nome da questao é obrigatório.', '/admin', 422);
            return;
        }

        if($order <= 0){
            $this->jsonOrRedirectError('A ordem não pode ser um número negativo', '/admin', 422);
            return;
        }

        $validOptions = array_filter($options, fn($opt) => trim((string)$opt) !== '');
        if(count($validOptions) < 2){
            $this->jsonOrRedirectError('Uma questão precisa de pelo menis 2 alternativas preenchdas.', '/admin', 422);
            return;
        }


        try {
            $question = new Question( $data['name'],  $data['description'], $data['order'], true);
            
            foreach($validOptions as $optionText){
                $option = new QuestionOption(trim($optionText), true);
                $question->addOption($option);

            }

            $model = new QuestionModel();
            $model->saveQuestion($question);

            if($this->expectsJson()){
                $this->jsonResponse([
                    'ok' => true,
                    'message' => 'Questão cadastrada com sucesso!',
                    'redirect' => $this->route('/admin')

                ]);

            }

            header('Location: ' . $this->route('/admin?status=success'));
            exit;


        } catch (Exception $e) {
            if($this->expectsJson()){
                $this->jsonResponse([
                    'ok' => false,
                    'message' => 'Erro ao salvar: ' .$e->getMessage()], 500);
            }
        
            header('Location: ' . $this->route('/admin?error=' . urlencode($e->getMessage())));
            exit;
        }
    }

    public function delete(array $data): void 
    {
        
        $id = (int)($data['id'] ?? 0);

        
        if ($id <= 0) {
            if ($this->expectsJson()) {
                $this->jsonResponse(['ok' => false, 'message' => 'ID da questão inválido.'], 400);
                return;
            }
            header('Location: ' . $this->route('/admin?error=id_invalido'));
            exit;
        }

        try {
           
            $model = new QuestionModel();
            $sucesso = $model->deleteQuestion($id);

            if ($sucesso) {
        
                if ($this->expectsJson()) {
                    $this->jsonResponse([
                        'ok' => true, 
                        'message' => 'Questão removida com sucesso!',
                        'redirect' => $this->route('/admin')
                    ]);
                    return;
                }
                header('Location: ' . $this->route('/admin?status=deleted'));
                exit;
            }

            throw new Exception("Não foi possível desativar a questão no banco de dados.");

        } catch (Exception $e) {
           
            if ($this->expectsJson()) {
                $this->jsonResponse(['ok' => false, 'message' => $e->getMessage()], 500);
                return;
            }
            header('Location: ' . $this->route('/admin?error=falha_ao_deletar'));
            exit;
        }
    }

    public function update(array $data): void 
    {
        
        $id = (int)($data['id'] ?? 0);
        $name = trim((string)($data['name'] ?? ''));
        $description = trim((string)($data['description'] ?? ''));
        $order = (int)($data['order'] ?? 0);

      
        if ($id <= 0) {
            $this->jsonOrRedirectError('ID da questão inválido para atualização.', '/admin', 400);
            return;
        }

        if ($name === '') {
            $this->jsonOrRedirectError('O nome da questão não pode ficar vazio.', '/admin', 422);
            return;
        }

        if ($order <= 0) {
            $this->jsonOrRedirectError('A ordem deve ser um número positivo.', '/admin', 422);
            return;
        }

        try {
            
            $model = new QuestionModel();
            
           
            $sucesso = $model->updateQuestion($id, $name, $description, $order);

            if ($sucesso) {
           
                if ($this->expectsJson()) {
                    $this->jsonResponse([
                        'ok' => true,
                        'message' => 'Questão atualizada com sucesso!',
                        'redirect' => $this->route('/admin')
                    ]);
                    return;
                }

                header('Location: ' . $this->route('/admin?status=updated'));
                exit;
            }

            throw new Exception("Não foi possível atualizar a questão. Ela pode ter sido removida.");

        } catch (Exception $e) {
            // 5. Tratamento de erro
            if ($this->expectsJson()) {
                $this->jsonResponse(['ok' => false, 'message' => $e->getMessage()], 500);
                return;
            }

            header('Location: ' . $this->route('/admin?error=' . urlencode($e->getMessage())));
            exit;
        }
    }

    public function listQuestions(): void
    {
        try{

            $model = new QuestionModel();

            $questions = $model->getQuestions();
            
            if($this->expectsJson()){
                $this->jsonResponse([
                    'ok' => true,
                    'data' => $questions
                ]);
                return;
            }
        }catch (Exception $e){
            if($this->expectsjson()){
                $this->jsonResponse(['ok' => false, 'nessage' =>'Erro ao listar questões.'], 500);
                return;
            }
            header('Location: ' . $this->route('/admin?error=list_fail'));
            exit;
        }
    }

    public function show(array $data): void 
    {
   
        $id = (int)($data['id'] ?? 0);


        if ($id <= 0) {
            if ($this->expectsJson()) {
                $this->jsonResponse(['ok' => false, 'message' => 'ID inválido.'], 400);
                return;
            }
            header('Location: ' . $this->route('/admin?error=id_invalido'));
            exit;
        }

        try {
            $model = new QuestionModel();
            $question = $model->gatSpecificQuestions($id);

            if (!$question) {
                if ($this->expectsJson()) {
                    $this->jsonResponse(['ok' => false, 'message' => 'Questão não encontrada.'], 404);
                    return;
                }
                header('Location: ' . $this->route('/admin?error=not_found'));
                exit;
            }

         
            if ($this->expectsJson()) {
                $this->jsonResponse([
                    'ok' => true,
                    'data' => [
                        'id' => $id,
                        'name' => $question->getName(),
                        'description' => $question->getDescription(),
                        'order' => $question->getQuestionOrder()
                    ]
                ]);
                return;
            }

        } catch (Exception $e) {
            if ($this->expectsJson()) {
                $this->jsonResponse(['ok' => false, 'message' => 'Erro interno.'], 500);
                return;
            }
            header('Location: ' . $this->route('/admin?error=internal_error'));
            exit;
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
