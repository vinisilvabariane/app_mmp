<?php
$basePath = isset($_SERVER['APP_BASE_PATH']) ? (string)$_SERVER['APP_BASE_PATH'] : '';
$currentRoute = isset($_SERVER['APP_CURRENT_ROUTE']) ? (string)$_SERVER['APP_CURRENT_ROUTE'] : '/';
$homeUrl = ($basePath !== '' ? $basePath : '') . '/home';
$formsUrl = ($basePath !== '' ? $basePath : '') . '/forms';
$logoutUrl = ($basePath !== '' ? $basePath : '') . '/logout';
$adminUrl = ($basePath !== '' ? $basePath : '') . '/admin';
$testeUrl = ($basePath !== '' ? $basePath : '') . '/teste';
?>
<header class="top-nav-shell">
    <div class="top-nav-inner">
        <a href="<?= $homeUrl ?>" class="brand-mark" aria-label="Lorem Ipsum Lorem">
            <span class="brand-dot"></span>
            <span class="brand-text">Lorem Ipsum</span>
        </a>

        <nav id="sidebar" class="top-nav" aria-label="Lorem ipsum">
            <a href="<?= $homeUrl ?>"
                class="nav-link-item <?= ($currentRoute === '/' || $currentRoute === '/home') ? 'active' : '' ?>">
                Lorem
            </a>
            <a href="<?= $formsUrl ?>"
                class="nav-link-item <?= $currentRoute === '/forms' ? 'active' : '' ?>">
                Ipsum
            </a>
            <a href="<?= $testeUrl ?>"
                class="nav-link-item <?= $currentRoute === '/teste' ? 'active' : '' ?>">
                Teste
            </a>
            <a href="<?= $adminUrl ?>"
                class="nav-link-item <?= $currentRoute === '/admin' ? 'active' : '' ?>">
                Admin
            </a>
            <a href="<?= $logoutUrl ?>" class="nav-link-item">
                Sair
            </a>
        </nav>
    </div>
</header>