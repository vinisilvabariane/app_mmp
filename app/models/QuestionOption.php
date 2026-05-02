<?php

namespace App\models;

use Exception;

class QuestionOption {

    private ?int $id; 
    private string $name;
    private bool $active;


    public function __construct(string $name, bool $active = true, ?int $id = null)
    {
        $this->validate($name);
        $this->id = $id;
        $this->name = $name;
        $this->active = $active;
    }

    private function validate(string $name): void
    {
        if (empty(trim($name))) {
            throw new Exception("O texto da alternativa não pode ser vazio.");
        }
    }

    public function getName(): string {
        return $this->name;
    }

    public function isActive(): bool {
        return $this->active;
    }

    public function getId(): ?int {

        return $this->id;
    }

}





