<?php

namespace App\models;

use App\config\Connection;
use App\config\connection\models;
use PDO;


class QuestionModel{


    private int $id;  

    public function saveQuestion(Question $question): void
    {
        $pdo = Connection::connect();
        $pdo->beginTransaction(); 

        try {
           
            $querySql = 'INSERT INTO questions (name, description, question_order, active) 
                        VALUES (:name, :description, :order, :active)';
            
            $stmt = $pdo->prepare($querySql);
            $stmt->bindValue(':name', $question->getName(), PDO::PARAM_STR);
            $stmt->bindValue(':description', $question->getDescription(), PDO::PARAM_STR);
            $stmt->bindValue(':order', $question->getQuestionOrder(), PDO::PARAM_INT);
            //$stmt->bindValue(':order', 1, PDO::PARAM_INT);
            $stmt->bindValue(':active', 1, PDO::PARAM_INT);
            $stmt->execute();

            

            $pdo->commit(); // aqui que os dados vão ser realmente salvoss
        } catch (\Throwable $e) {
            $pdo->rollBack(); // se algum erro acontecer ele vai deletar tudo 
            throw $e;
        }
    }


    public function getQuestions(): array
    {
        $pdo = Connection::connect();


        $sql = "SELECT * FROM questions WHERE active = 1 ORDER BY question_order ASC";
        $questions = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

        $fullData = [];

        foreach ($questions as $q) {
        
            $sqlOpt = "SELECT * FROM question_options WHERE question_id = :id AND active = 1";
            $stmt = $pdo->prepare($sqlOpt);
            $stmt->bindValue(':id', $q['id'], PDO::PARAM_INT);
            $stmt->execute();
            
            $q['options'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            //fetchAll(PDO::FETCH_ASSOC);busca todas as linhas restantes de um resultado de consulta SQL e as retorna como um array associativo
        
            $fullData[] = $q;
        }

        return $fullData;
    }


    //como vou fazer o controler?? 
    public function gatSpecificQuestions(int $id): ?Question
    {
        $pdo = Connection::connect();

        $sql = "SELECT * FROM questions WHERE active = 1 end id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::FETCH_ASSOC);

        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$data){
            return null;
        }
        
        return new Question(
            $data['name'],
            $data['description'],
            (int)$data['order'],
            (bool)$data['active']
        );
    }

    public function updateQuestion(int $id, string $name, string $description, int $order): bool 
    {
        try {
            $pdo = Connection::connect();

            $sql = "UPDATE questions 
                    SET name = :name, 
                        description = :description, 
                        `order` = :order 
                    WHERE id = :id AND active = 1";

            $stmt = $pdo->prepare($sql);

          
            $stmt->bindValue(':name', $name, \PDO::PARAM_STR);
            $stmt->bindValue(':description', $description, \PDO::PARAM_STR);
            $stmt->bindValue(':order', $order, \PDO::PARAM_INT);
            $stmt->bindValue(':id', $id, \PDO::PARAM_INT);

            
            return $stmt->execute();

        } catch (\PDOException $e) {
            
            return false;
        }
    }

    public function deleteQuestion(int $id): bool
    {
        //apenas mudo a active!
        $pdo = Connection::connect();
        
        $sql = "UPDATE questions SET active = 0 WHERE id = :id";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }


}


