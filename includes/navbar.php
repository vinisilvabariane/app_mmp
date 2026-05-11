<?php

use App\config\Auth;

$basePath = isset($_SERVER['APP_BASE_PATH']) ? (string) $_SERVER['APP_BASE_PATH'] : '';
$currentRoute = isset($_SERVER['APP_CURRENT_ROUTE']) ? (string) $_SERVER['APP_CURRENT_ROUTE'] : '/';
$homeUrl = ($basePath !== '' ? $basePath : '') . '/home';
$formsUrl = ($basePath !== '' ? $basePath : '') . '/forms';
$logoutUrl = ($basePath !== '' ? $basePath : '') . '/logout';
$dashboardUrl = ($basePath !== '' ? $basePath : '') . '/dashboard';
$dashboardQuestionsUrl = ($basePath !== '' ? $basePath : '') . '/dashboard/questions';
$dashboardMetricsUrl = ($basePath !== '' ? $basePath : '') . '/dashboard/metrics';
$chatUrl = ($basePath !== '' ? $basePath : '') . '/chat';
$trailUrl = ($basePath !== '' ? $basePath : '') . '/trail';
$profileUrl = ($basePath !== '' ? $basePath : '') . '/profile';
$authUser = Auth::user();
$authUserName = trim((string) ($authUser['full_name'] ?? ''));
$isDashboardArea = strpos($currentRoute, '/dashboard') === 0;

if (!function_exists('mmp_initials')) {
    function mmp_initials(string $name): string
    {
        $name = trim($name);
        if ($name === '') {
            return '?';
        }

        $parts = preg_split('/\s+/', $name) ?: [];
        $parts = array_values(array_filter($parts, static fn ($part) => $part !== ''));
        if (count($parts) === 0) {
            return '?';
        }

        if (count($parts) === 1) {
            return strtoupper(substr($parts[0], 0, 1));
        }

        return strtoupper(substr($parts[0], 0, 1) . substr($parts[count($parts) - 1], 0, 1));
    }
}

$authUserInitials = mmp_initials($authUserName);
?>
<header class="top-nav-shell">
    <div class="top-nav-inner">
        <a href="<?= $homeUrl ?>" class="brand-mark" aria-label="Map My Path">
            <img src="<?= $basePath ?>/public/img/logo-v2.png" class="brand-logo" alt="Map My Path">
            <span class="brand-text">Map My Path</span>
        </a>

        <div class="top-nav-cluster">
            <nav id="sidebar" class="top-nav" aria-label="Principal">
                <a href="<?= $homeUrl ?>" class="nav-link-item <?= ($currentRoute === '/' || $currentRoute === '/home') ? 'active' : '' ?>">
                    Inicio
                </a>
                <a href="<?= $formsUrl ?>" class="nav-link-item <?= $currentRoute === '/forms' ? 'active' : '' ?>">
                    Formulario
                </a>
                <a href="<?= $trailUrl ?>" class="nav-link-item <?= $currentRoute === '/trail' ? 'active' : '' ?>">
                    Minha trilha
                </a>
                <a href="<?= $chatUrl ?>" class="nav-link-item <?= $currentRoute === '/chat' ? 'active' : '' ?>">
                    Chat
                </a>
            </nav>

            <?php if ($authUser && isset($authUser['role']) && $authUser['role'] === 'admin'): ?>
                <details class="nav-admin-menu <?= $isDashboardArea ? 'is-active' : '' ?>" <?= $isDashboardArea ? 'open' : '' ?>>
                    <summary class="nav-admin-toggle">
                        <span class="nav-link-item <?= $isDashboardArea ? 'active' : '' ?>">Admin</span>
                        <i class="bi bi-chevron-down"></i>
                    </summary>
                    <div class="nav-admin-dropdown">
                        <a href="<?= $dashboardUrl ?>" class="nav-admin-action <?= $currentRoute === '/dashboard' ? 'active' : '' ?>">
                            <i class="bi bi-speedometer2"></i>
                            <span>Visao Geral</span>
                        </a>
                        <a href="<?= $dashboardQuestionsUrl ?>" class="nav-admin-action <?= $currentRoute === '/dashboard/questions' ? 'active' : '' ?>">
                            <i class="bi bi-ui-checks-grid"></i>
                            <span>Perguntas</span>
                        </a>
                        <a href="<?= $dashboardMetricsUrl ?>" class="nav-admin-action <?= $currentRoute === '/dashboard/metrics' ? 'active' : '' ?>">
                            <i class="bi bi-activity"></i>
                            <span>Metricas</span>
                        </a>
                    </div>
                </details>
            <?php endif; ?>

            <details class="nav-profile-menu <?= $currentRoute === '/profile' ? 'is-active' : '' ?>">
                <summary class="nav-profile-toggle" aria-label="Abrir menu do perfil">
                    <div class="avatar-circle" id="avatar">
                        <span><?= htmlspecialchars($authUserInitials, ENT_QUOTES, 'UTF-8') ?></span>
                    </div>
                    <i class="bi bi-chevron-down"></i>
                </summary>

                <div class="nav-profile-dropdown">
                    <a href="<?= $profileUrl ?>" class="nav-profile-action">
                        <i class="bi bi-person-circle"></i>
                        <span>Perfil</span>
                    </a>
                    <a href="<?= $logoutUrl ?>" class="nav-profile-action nav-profile-action-logout">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </details>
        </div>
    </div>
</header>
