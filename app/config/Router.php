<?php

namespace App\config;

use App\routers\HomeRouter;
use App\routers\FormsRouter;
use App\routers\LoginRouter;
use App\routers\DashboardRouter;
use App\routers\ChatRouter;
use App\routers\ProfileRouter;

class Router
{
    public function dispatch(string $requestUri, string $scriptName): void
    {
        $basePath = $this->extractBasePath($scriptName);
        $routePath = $this->extractRoutePath($requestUri, $basePath);
        $routes = [
            '/' => [LoginRouter::class, 'index'],
            '/login' => [LoginRouter::class, 'index'],
            '/login/authenticate' => [LoginRouter::class, 'authenticate'],
            '/login/change-password' => [LoginRouter::class, 'showChangePassword'],
            '/login/update-password' => [LoginRouter::class, 'changePassword'],
            '/login/request-password-reset' => [LoginRouter::class, 'requestPasswordReset'],
            '/login/register' => [LoginRouter::class, 'register'],
            '/logout' => [LoginRouter::class, 'logout'],
            '/home' => [HomeRouter::class, 'index'],
            '/forms' => [FormsRouter::class, 'index'],
            '/dashboard' => [DashboardRouter::class, 'index'],
            '/dashboard/questions' => [DashboardRouter::class, 'questions'],
            '/dashboard/questions/create' => [DashboardRouter::class, 'createQuestion'],
            '/dashboard/questions/update' => [DashboardRouter::class, 'updateQuestion'],
            '/dashboard/questions/delete' => [DashboardRouter::class, 'deleteQuestion'],
            '/dashboard/metrics' => [DashboardRouter::class, 'metrics'],
            '/dashboard/metrics/create' => [DashboardRouter::class, 'createMetric'],
            '/dashboard/metrics/update' => [DashboardRouter::class, 'updateMetric'],
            '/dashboard/metrics/delete' => [DashboardRouter::class, 'deleteMetric'],
            '/chat' => [ChatRouter::class, 'index'],
            '/chat/message' => [ChatRouter::class, 'message'],
            '/profile' => [ProfileRouter::class, 'index'],
        ];
        if (!isset($routes[$routePath])) {
            http_response_code(404);
            $_SERVER['APP_BASE_PATH'] = $basePath;
            $_SERVER['APP_CURRENT_ROUTE'] = '/not-found';
            require_once __DIR__ . '/notFound/index.php';
            return;
        }
        $_SERVER['APP_BASE_PATH'] = $basePath;
        $_SERVER['APP_CURRENT_ROUTE'] = $routePath;
        list($controllerClass, $action) = $routes[$routePath];
        $controller = new $controllerClass();
        $controller->$action();
    }

    private function extractBasePath(string $scriptName)
    {
        $normalizedScript = str_replace('\\', '/', (string)$scriptName);
        $basePath = str_replace('/index.php', '', $normalizedScript);
        $basePath = rtrim($basePath, '/');
        if ($basePath === '' || $basePath === '.') {
            return '';
        }
        return $basePath;
    }

    private function extractRoutePath(string $requestUri, string $basePath)
    {
        $path = parse_url($requestUri, PHP_URL_PATH);
        $path = str_replace('\\', '/', (string)$path);
        if ($basePath !== '' && strpos($path, $basePath) === 0) {
            $path = substr($path, strlen($basePath));
        }
        if ($path === '' || $path === false || $path === '/index.php') {
            $queryPage = isset($_GET['page']) ? trim((string)$_GET['page']) : '';
            $queryRoute = strtolower($queryPage);
            if (in_array($queryRoute, ['home', 'forms', 'dashboard', 'profile'], true)) {
                return '/' . $queryRoute;
            }
            return '/';
        }
        if (strpos($path, '/index.php/') === 0) {
            $path = substr($path, strlen('/index.php'));
        }
        $path = '/' . trim($path, '/');
        if ($path === '/index.php' || $path === '//') {
            return '/';
        }
        return strtolower($path);
    }
}
