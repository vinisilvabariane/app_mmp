<?php

namespace App\routers;

use App\controllers\ProfileController;

class ProfileRouter
{
    public function index(): void
    {
        $controller = new ProfileController();
        $action = $_GET['action'] ?? 'index';
        switch ($action) {
            case 'save':
                // Passamos o $_POST para o método de criação
                $controller->save($_POST);
                break;

            case 'update':
                // Passamos o $_POST para o método de edição
                $controller->update($_POST);
                break;

            case 'delete':
                // Passamos o $_POST (ou $_GET) com o ID para deletar
                $controller->delete($_POST);
                break;

            case 'show':
                // Usado para carregar os dados de uma questão (via AJAX para o Modal)
                $controller->show($_GET);
                break;

            case 'list':
                // Caso queira uma rota específica para retornar a lista de questões
                $controller->listQuestions();
                break;

            case 'index':
            default:
                // Carrega a página principal do admin (views/admin/index.php)
                $controller->index();
                break;
        }
    }
}
