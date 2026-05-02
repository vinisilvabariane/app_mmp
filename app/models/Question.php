<?php

namespace App\models;

use Exception;

class Question 
{
    private ?int $id; 
    private string $name;
    private string $description;
    private int $question_order; 
    private bool $active;
    private array $options = []; 

    public function __construct(string $name, string $description, int $question_order, bool $active, ?int $id = null)
    {
        $this->validate($name, $description, $question_order);

        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->question_order = $question_order;
        $this->active = $active;
    }

    private function validate($name, $description, $question_order): void
    {
        if (empty($name)) {
            throw new Exception("O nome da questão é obrigatório.");
        }
        if (empty($description)) {
            throw new Exception("A descrição da questão é obrigatória.");
        }
        
    } 

    public function addOption(QuestionOption $option): void
    {
        $this->options[] = $option;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function getId(): ?int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getdescription(): string { return $this->description; }
    public function getQuestionOrder(): int {return $this->question_order; }
    
}

