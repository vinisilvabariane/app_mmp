<?php
$basePath = isset($_SERVER['APP_BASE_PATH']) ? (string)$_SERVER['APP_BASE_PATH'] : '';
$currentRoute = isset($_SERVER['APP_CURRENT_ROUTE']) ? (string)$_SERVER['APP_CURRENT_ROUTE'] : '/';
$homeUrl = ($basePath !== '' ? $basePath : '') . '/home';
$formsUrl = ($basePath !== '' ? $basePath : '') . '/forms';
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
        </nav>
    </div>
</header>


