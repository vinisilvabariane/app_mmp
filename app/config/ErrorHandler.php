<?php

namespace App\config;

use Throwable;

class ErrorHandler
{
    public function __construct()
    {
        date_default_timezone_set('America/Sao_Paulo');
    }

    public function errorHandler(int $errno, string $errstr, string $errfile, int $errline): void
    {
        $this->handleError($errno, $errstr, $errfile, $errline);
    }

    public function exceptionHandler(Throwable $exception): void
    {
        $this->handleError($exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine());
    }

    private function handleError(int $errno, string $errstr, string $errfile, int $errline): void
    {
        $trace = debug_backtrace();
        array_shift($trace);
        $message = $this->createErrorMessage($errno, $errstr, $errfile, $errline, $trace);
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST) $message .= $this->collectRequestInfo();
        $message .= $this->collectServerInfo();
        error_log($message);
    }

    private function createErrorMessage(int $errno, string $errstr, string $errfile, int $errline, array $trace): string
    {
        $username = $_SESSION['username'] ?? 'N/A';
        $errorInfo = " *Error Alert:* \n";
        $errorInfo .= "```\n";
        $errorInfo .= "Date: " . date("d/m/Y H:i:s") . "\n";
        $errorInfo .= "Usuário: {$username}\n";
        $errorInfo .= "Message: {$errstr}\n";
        $errorInfo .= "Error Code: {$errno}\n";
        $errorInfo .= "File: {$errfile}\n";
        $errorInfo .= "Line: {$errline}\n";
        $errorInfo .= "```";
        $errorInfo .= $this->getFormattedTrace($trace);
        return $errorInfo;
    }
    private function getFormattedTrace(array $trace): string
    {
        $formattedTrace = " *Trace:* \n```\n";
        foreach ($trace as $index => $traceInfo) {
            $file = $traceInfo['file'] ?? 'N/A';
            $line = $traceInfo['line'] ?? 'N/A';
            $formattedTrace .= "#{$index}: {$file} ({$line})\n";
        }
        $formattedTrace .= "\n```";
        return $formattedTrace;
    }
    private function collectRequestInfo(): string
    {
        $requestInfo = " *FormData:* \n```\n";
        $requestInfo .= json_encode($_POST);
        $requestInfo .= "\n```";
        return $requestInfo;
    }

    private function collectServerInfo(): string
    {
        $serverInfo = " *Server:* \n```\n";
        $serverInfo .= "- IP do cliente: " . (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'N/A') . ";\n";
        $serverInfo .= "- User Agent do navegador do cliente: " . (isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'N/A') . ";\n";
        $serverInfo .= "- Porta usada pelo cliente: " . (isset($_SERVER['REMOTE_PORT']) ? $_SERVER['REMOTE_PORT'] : 'N/A') . ";\n";
        $serverInfo .= "- Query da URL: " . (isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : 'N/A') . ";\n";
        $serverInfo .= "- URL da página de origem da requisição: " . (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'Acesso direto ou bloqueado') . ";\n";
        $serverInfo .= "- Host do servidor do cliente: " . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'N/A') . ";\n";
        $serverInfo .= "- Cabeçalho de conexão do cliente: " . (isset($_SERVER['HTTP_CONNECTION']) ? $_SERVER['HTTP_CONNECTION'] : 'N/A') . ";\n";
        $serverInfo .= "- ID da Sessão: " . (isset($_COOKIE['PHPSESSID']) ? $_COOKIE['PHPSESSID'] : 'N/A') . ";\n";
        $serverInfo .= "\n```";
        return $serverInfo;
    }
}
