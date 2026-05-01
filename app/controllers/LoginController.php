<?php

namespace App\controllers;

use App\config\Auth;
use App\config\Mailer;
use App\models\UserModel;
use RuntimeException;

class LoginController
{
    public function index(): void
    {
        Auth::redirectIfAuthenticated($this->basePath());
        require_once __DIR__ . '/../views/login/index.php';
    }

    public function authenticate(): void
    {
        $email = trim((string)($_POST['email'] ?? ''));
        $password = (string)($_POST['password'] ?? '');
        $success = $email !== '' && $password !== '' && Auth::attempt($email, $password);

        if ($this->expectsJson()) {
            header('Content-Type: application/json; charset=UTF-8');

            if ($success) {
                echo json_encode([
                    'ok' => true,
                    'redirect' => Auth::needsPasswordReset() ? $this->route('/login/change-password') : $this->route('/home'),
                ]);
                return;
            }

            http_response_code(401);
            echo json_encode([
                'ok' => false,
                'message' => 'Email ou senha invalidos.',
            ]);
            return;
        }

        if ($success) {
            header('Location: ' . (Auth::needsPasswordReset() ? $this->route('/login/change-password') : $this->route('/home')));
            exit;
        }

        $_SESSION['login_error'] = 'Email ou senha invalidos.';
        header('Location: ' . $this->route('/login'));
        exit;
    }

    public function showChangePassword(): void
    {
        $this->ensureAuthenticated();
        require_once __DIR__ . '/../views/login/change-password.php';
    }

    public function changePassword(): void
    {
        $this->ensureAuthenticated();

        $password = (string) ($_POST['password'] ?? '');
        $confirmPassword = (string) ($_POST['password_confirm'] ?? '');

        if (trim($password) === '' || strlen($password) < 8) {
            $this->jsonOrRedirectError('A nova senha deve ter pelo menos 8 caracteres.', '/login/change-password', 422);
            return;
        }

        if ($password !== $confirmPassword) {
            $this->jsonOrRedirectError('As senhas nao conferem.', '/login/change-password', 422);
            return;
        }

        $user = Auth::user();
        $userId = isset($user['id']) ? (int) $user['id'] : 0;
        if ($userId <= 0) {
            $this->jsonOrRedirectError('Sessao invalida.', '/login', 401);
            return;
        }

        $userModel = new UserModel();
        $userModel->updatePassword($userId, password_hash($password, PASSWORD_DEFAULT));
        Auth::markPasswordUpdated();

        if ($this->expectsJson()) {
            $this->jsonResponse([
                'ok' => true,
                'redirect' => $this->route('/home'),
            ]);
            return;
        }

        header('Location: ' . $this->route('/home'));
        exit;
    }

    public function requestPasswordReset(): void
    {
        $email = trim((string) ($_POST['email'] ?? ''));

        if ($email === '') {
            $this->jsonResponse([
                'ok' => false,
                'message' => 'Informe o email para resetar a senha.',
            ], 422);
            return;
        }

        $userModel = new UserModel();
        $user = $userModel->findByEmail($email);

        if ($user === null) {
            $this->jsonResponse([
                'ok' => false,
                'message' => 'Nao foi encontrado um usuario com esse email.',
            ], 404);
            return;
        }

        $temporaryPassword = $this->generateTemporaryPassword();
        $userModel->updateTemporaryPassword((int) $user['id'], password_hash($temporaryPassword, PASSWORD_DEFAULT));

        try {
            $mailer = new Mailer();
            $mailer->send(
                (string) $user['email'],
                (string) ($user['full_name'] ?? $user['email']),
                'Reset de senha - Map My Path',
                '<p>Uma nova senha temporaria foi gerada para sua conta.</p><p><strong>Senha temporaria:</strong> ' . htmlspecialchars($temporaryPassword, ENT_QUOTES, 'UTF-8') . '</p><p>No primeiro acesso, voce precisara definir uma nova senha.</p>',
                'Senha temporaria: ' . $temporaryPassword . '. No primeiro acesso, voce precisara definir uma nova senha.'
            );
        } catch (RuntimeException $exception) {
            $this->jsonResponse([
                'ok' => false,
                'message' => $exception->getMessage(),
            ], 500);
            return;
        }

        $this->jsonResponse([
            'ok' => true,
            'message' => 'Uma senha temporaria foi enviada para o email cadastrado.',
        ]);
    }

    public function register(): void
    {
        $email = trim((string) ($_POST['register_email'] ?? ''));
        $password = (string) ($_POST['register_password'] ?? '');
        $confirmPassword = (string) ($_POST['register_password_confirm'] ?? '');
        $fullName = trim((string) ($_POST['register_full_name'] ?? ''));

        if ($email === '' || $password === '' || $confirmPassword === '') {
            $this->jsonResponse([
                'ok' => false,
                'message' => 'Preencha email e senha para criar a conta.',
            ], 422);
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->jsonResponse([
                'ok' => false,
                'message' => 'Informe um email valido.',
            ], 422);
            return;
        }

        if (strlen($password) < 8) {
            $this->jsonResponse([
                'ok' => false,
                'message' => 'A senha deve ter pelo menos 8 caracteres.',
            ], 422);
            return;
        }

        if ($password !== $confirmPassword) {
            $this->jsonResponse([
                'ok' => false,
                'message' => 'As senhas nao conferem.',
            ], 422);
            return;
        }

        $userModel = new UserModel();

        if ($userModel->findByEmail($email) !== null) {
            $this->jsonResponse([
                'ok' => false,
                'message' => 'Esse email ja esta em uso.',
            ], 409);
            return;
        }

        $userModel->create(
            $email,
            password_hash($password, PASSWORD_DEFAULT),
            $fullName !== '' ? $fullName : null
        );

        $this->jsonResponse([
            'ok' => true,
            'message' => 'Conta criada com sucesso. Agora voce ja pode entrar.',
        ], 201);
    }

    public function logout(): void
    {
        Auth::logout();
        header('Location: ' . $this->route('/login'));
        exit;
    }

    private function expectsJson(): bool
    {
        $header = $_SERVER['HTTP_X_REQUESTED_WITH'] ?? '';
        return strtolower((string)$header) === 'xmlhttprequest';
    }

    private function ensureAuthenticated(): void
    {
        if (Auth::check()) {
            return;
        }

        header('Location: ' . $this->route('/login'));
        exit;
    }

    private function jsonOrRedirectError(string $message, string $redirectPath, int $statusCode): void
    {
        if ($this->expectsJson()) {
            $this->jsonResponse([
                'ok' => false,
                'message' => $message,
            ], $statusCode);
            return;
        }

        $_SESSION['login_error'] = $message;
        header('Location: ' . $this->route($redirectPath));
        exit;
    }

    private function jsonResponse(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    private function generateTemporaryPassword(): string
    {
        return substr(str_replace(['+', '/', '='], '', base64_encode(random_bytes(12))), 0, 12);
    }

    private function basePath(): string
    {
        return isset($_SERVER['APP_BASE_PATH']) ? (string)$_SERVER['APP_BASE_PATH'] : '';
    }

    private function route(string $path): string
    {
        return rtrim($this->basePath(), '/') . $path;
    }
}

