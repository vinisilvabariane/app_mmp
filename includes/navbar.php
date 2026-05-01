<?php

use App\config\Auth;

$basePath = isset($_SERVER['APP_BASE_PATH']) ? (string)$_SERVER['APP_BASE_PATH'] : '';
$currentRoute = isset($_SERVER['APP_CURRENT_ROUTE']) ? (string)$_SERVER['APP_CURRENT_ROUTE'] : '/';
$homeUrl = ($basePath !== '' ? $basePath : '') . '/home';
$formsUrl = ($basePath !== '' ? $basePath : '') . '/forms';
$logoutUrl = ($basePath !== '' ? $basePath : '') . '/logout';
$adminUrl = ($basePath !== '' ? $basePath : '') . '/admin';
$chatUrl = ($basePath !== '' ? $basePath : '') . '/chat';
$profileUrl = ($basePath !== '' ? $basePath : '') . '/profile';
$authUser = Auth::user();
$authUserName = trim((string) ($authUser['full_name'] ?? ''));

if (!function_exists('mmp_initials')) {
    function mmp_initials(string $name): string
    {
        $name = trim($name);
        if ($name === '') {
            return '?';
        }
        $parts = preg_split('/\s+/', $name) ?: [];
        $parts = array_values(array_filter($parts, static fn($part) => $part !== ''));
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
        <a href="<?= $homeUrl ?>" class="brand-mark" aria-label="Lorem Ipsum Lorem">
            <img src="<?= $basePath ?>/public/img/logo-v2.png" class="brand-logo">
            <span class="brand-text">Map My Path</span>
        </a>
        <div class="top-nav-cluster">
            <nav id="sidebar" class="top-nav" aria-label="Lorem ipsum">
                <a href="<?= $homeUrl ?>"
                    class="nav-link-item <?= ($currentRoute === '/' || $currentRoute === '/home') ? 'active' : '' ?>">
                    Início
                </a>
                <a href="<?= $formsUrl ?>"
                    class="nav-link-item <?= $currentRoute === '/forms' ? 'active' : '' ?>">
                    Formulário
                </a>

                <?php if ($authUser && isset($authUser['role']) && $authUser['role'] === 'admin'): ?>
                    <a href="<?= $adminUrl ?>"
                        class="nav-link-item <?= $currentRoute === '/admin' ? 'active' : '' ?>">
                        Gerenciamento
                    </a>
                <?php endif; ?>

                <a href="<?= $chatUrl ?>"
                    class="nav-link-item <?= $currentRoute === '/chat' ? 'active' : '' ?>">
                    Chat
                </a>
            </nav>

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

        <!-- <script>
            console.log('Navbar session user', {
                id: <?= json_encode($authUser['id'] ?? null, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>,
                email: <?= json_encode($authUser['email'] ?? null, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>,
                fullName: <?= json_encode($authUser['full_name'] ?? null, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>,
                roleId: <?= json_encode($authUser['role_id'] ?? null, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>,
                role: <?= json_encode($authUser['role'] ?? null, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>,
                sessionId: <?= json_encode($authUser['session_id'] ?? null, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>,
                initials: <?= json_encode($authUserInitials, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>,
                resetRequired: <?= json_encode($authUser['reset_required'] ?? null, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>
            });
        </script> -->
    </div>
</header>