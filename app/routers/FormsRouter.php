<?php

namespace App\routers;

use App\controllers\FormsController;

class FormsRouter
{
    public function index(): void
    {
        (new FormsController())->index();
    }

    public function save(): void
    {
        (new FormsController())->saveAnswers($this->requestData());
    }

    public function update(): void
    {
        (new FormsController())->update($this->requestData());
    }

    private function requestData(): array
    {
        if ($_POST !== []) {
            return $_POST;
        }

        $rawInput = file_get_contents('php://input');
        if (!is_string($rawInput) || trim($rawInput) === '') {
            return [];
        }

        $decoded = json_decode($rawInput, true);
        return is_array($decoded) ? $decoded : [];
    }
}
