<?php

namespace App\models;

use App\config\Connection;
use App\config\connection\models;
use PDO;


//esta classe tem com objetivo salvar as reposta do formulario vindo dos alunos

class RegisterForms
{
    private $teste;
   /*
    public function saveAnswers(array $data): void 
    {
        $pdo = Connection::connect();
        $pdo->beginTransaction(); // Inicia o "modo de segurança"

        try {
            $querySql = 'INSERT INTO answers (name, description, question_order, active) 
                        VALUES (:name, :description, :order, :active)'; 
            
            
            $stmt = $pdo->prepare($querySql);

            
            $stmt->bindValue(':name', $data['name'] ?? '', \PDO::PARAM_STR);
            $stmt->bindValue(':description', $data['description'] ?? '', \PDO::PARAM_STR);
            $stmt->bindValue(':order', (int)($data['order'] ?? 0), \PDO::PARAM_INT);
            $stmt->bindValue(':active', 1, \PDO::PARAM_INT);

            $stmt->execute();

           
            $pdo->commit(); 

        } catch (\Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack(); // Desfaz tudo 
            }
            throw $e; 
        }
    }*/

    public function saveAnswers(int $userId, array $answers): void 
    {
        $pdo = Connection::connect();
        $pdo->beginTransaction();

        try {
            // Tabela de ligação: guarda apenas as chaves estrangeiras (FK)
            $sql = 'INSERT INTO user_answers (user_id, question_id, option_id) 
                    VALUES (:user_id, :question_id, :option_id)';
            
            $stmt = $pdo->prepare($sql);

            // O segredo está neste loop:
            foreach ($answers as $questionId => $optionId) {
                $stmt->bindValue(':user_id', $userId, \PDO::PARAM_INT);
                $stmt->bindValue(':question_id', $questionId, \PDO::PARAM_INT); // Chave do array
                $stmt->bindValue(':option_id', $optionId, \PDO::PARAM_INT);    // Valor do array
                $stmt->execute();
            }

            $pdo->commit(); 

        } catch (\Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
    }

    public function updateAnswers(int $userId, array $answers): void 
    {
        $pdo = Connection::connect();
        $pdo->beginTransaction();

        try {
           
            $deleteSql = 'DELETE FROM user_answers WHERE user_id = :user_id';
            $deleteStmt = $pdo->prepare($deleteSql);
            $deleteStmt->bindValue(':user_id', $userId, \PDO::PARAM_INT);
            $deleteStmt->execute();

 
            $insertSql = 'INSERT INTO user_answers (user_id, question_id, option_id) 
                        VALUES (:user_id, :question_id, :option_id)';
            $insertStmt = $pdo->prepare($insertSql);

            foreach ($answers as $questionId => $optionId) {
                $insertStmt->bindValue(':user_id', $userId, \PDO::PARAM_INT);
                $insertStmt->bindValue(':question_id', $questionId, \PDO::PARAM_INT);
                $insertStmt->bindValue(':option_id', $optionId, \PDO::PARAM_INT);
                $insertStmt->execute();
            }

            $pdo->commit();

        } catch (\Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
    }


}

