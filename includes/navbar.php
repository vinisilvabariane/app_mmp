<?php
$basePath = isset($_SERVER['APP_BASE_PATH']) ? (string)$_SERVER['APP_BASE_PATH'] : '';
$currentRoute = isset($_SERVER['APP_CURRENT_ROUTE']) ? (string)$_SERVER['APP_CURRENT_ROUTE'] : '/';
$homeUrl = ($basePath !== '' ? $basePath : '') . '/home';
$formsUrl = ($basePath !== '' ? $basePath : '') . '/forms';
$logoutUrl = ($basePath !== '' ? $basePath : '') . '/logout';
$adminUrl = ($basePath !== '' ? $basePath : '') . '/admin';
$chatUrl = ($basePath !== '' ? $basePath : '') . '/chat';
?>
<header class="top-nav-shell">
    <div class="top-nav-inner">
        <a href="<?= $homeUrl ?>" class="brand-mark" aria-label="Lorem Ipsum Lorem">
            <img src="<?= $basePath ?>/public/img/logo-v2.png" alt="Logo" class="brand-logo">
            <span class="brand-text">Map My Path</span>
        </a>
        <nav id="sidebar" class="top-nav" aria-label="Lorem ipsum">
            <a href="<?= $homeUrl ?>"
                class="nav-link-item <?= ($currentRoute === '/' || $currentRoute === '/home') ? 'active' : '' ?>">
                Home
            </a>
            <a href="<?= $formsUrl ?>"
                class="nav-link-item <?= $currentRoute === '/forms' ? 'active' : '' ?>">
                Forms
            </a>
            <a href="<?= $adminUrl ?>"
                class="nav-link-item <?= $currentRoute === '/admin' ? 'active' : '' ?>">
                Admin
            </a>
            <a href="<?= $chatUrl ?>"
                class="nav-link-item <?= $currentRoute === '/chat' ? 'active' : '' ?>">
                Chat
            </a>

            <a href="<?= $basePath ?>/profile"
                class="nav-link-item <?= $currentRoute === '/profile' ? 'active' : '' ?>">
                    <div class="avatar-circle" id="avatar">
                        <span id="avatar-initials"></span>
                        <img id="avatar-img" src="" alt="Avatar" style="display: none;">
                    </div>
            </a>

            <script>
                function getInitials(name) {
                    const parts = name.trim().split(" ").filter(p => p);
                    if (parts.length === 1) return parts[0][0];
                    return (parts[0][0] + parts[parts.length - 1][0]).toUpperCase();
                }
                const userName = localStorage.getItem("userName") || "Maria Clara";
                document.getElementById("avatar-initials").textContent = getInitials(userName);
            </script>

            <a href="<?= $logoutUrl ?>" class="nav-link-item">
                Sair
            </a>
        </nav>
    </div>
</header>
