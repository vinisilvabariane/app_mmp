<?php
$basePath = isset($_SERVER['APP_BASE_PATH']) ? (string)$_SERVER['APP_BASE_PATH'] : '';
$loginScriptPath = __DIR__ . '/../../../public/js/login/script.js';
$loginScriptVersion = file_exists($loginScriptPath) ? filemtime($loginScriptPath) : time();
$globalStylePath = __DIR__ . '/../../../public/css/global/style.css';
$globalStyleVersion = file_exists($globalStylePath) ? filemtime($globalStylePath) : time();
$loginError = isset($_SESSION['login_error']) ? (string)$_SESSION['login_error'] : '';
unset($_SESSION['login_error']);
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Map My Path - Login</title>
    <link rel="icon" type="image/png" href="<?= $basePath ?>/public/img/com-fundo-maior.png" sizes="512x512">
    <link rel="apple-touch-icon" href="<?= $basePath ?>/public/img/com-fundo-maior.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700&family=Nunito:wght@400;600;700&display=swap">
    <link rel="stylesheet" href="<?= $basePath ?>/public/css/global/style.css?v=<?= $globalStyleVersion ?>">
</head>

<body class="login-page">
    <main id="main-content" class="login-layout fade-in-up">
        <section class="login-showcase">
            <p class="hero-kicker">Map My Path</p>
            <h1>Acompanhe jornadas e mantenha seu fluxo centralizado.</h1>
            <p class="hero-copy">
                Entre para acessar os formulários, acompanhar os caminhos mapeados e seguir o mesmo ecossistema visual já aplicado no front.
            </p>

            <div class="login-feature-grid">
                <article class="login-feature-card">
                    <span class="login-feature-icon"><i class="bi bi-signpost-split"></i></span>
                    <div>
                        <h2>Fluxo guiado</h2>
                        <p>Formulários em etapas, com progresso claro e foco na decisão.</p>
                    </div>
                </article>
                <article class="login-feature-card">
                    <span class="login-feature-icon"><i class="bi bi-shield-check"></i></span>
                    <div>
                        <h2>Acesso protegido</h2>
                        <p>Autenticação simples com sessão para restringir as rotas internas.</p>
                    </div>
                </article>
                <article class="login-feature-card">
                    <span class="login-feature-icon"><i class="bi bi-graph-up-arrow"></i></span>
                    <div>
                        <h2>Continuidade visual</h2>
                        <p>A mesma paleta, tipografia e linguagem da home e dos formulários.</p>
                    </div>
                </article>
            </div>
        </section>

        <section class="login-panel">
            <div class="login-panel-head">
                <span class="login-badge">Acesso</span>
                <h2>Bem-vindo de volta</h2>
                <p>Use suas credenciais para continuar.</p>
            </div>

            <form
                id="login-form"
                class="login-form"
                method="POST"
                action="<?= $basePath ?>/login/authenticate"
                novalidate>
                <div
                    id="error-message"
                    class="alert alert-warning<?= $loginError !== '' ? '' : ' d-none' ?>"
                    role="alert"><?= htmlspecialchars($loginError, ENT_QUOTES, 'UTF-8') ?></div>

                <div class="mb-3">
                    <label for="user" class="form-label">Usuario</label>
                    <input type="text" class="form-control" id="user" name="user" autocomplete="username" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Senha</label>
                    <input type="password" class="form-control" id="password" name="password" autocomplete="current-password" required>
                </div>

                <button type="submit" class="btn btn-primary w-100 login-submit" data-idle-label="Entrar">
                    <span class="login-submit-text">Entrar</span>
                    <i class="bi bi-arrow-right-short"></i>
                </button>
            </form>
        </section>
    </main>

    <?php include_once __DIR__ . '/../../../includes/dependencies.php'; ?>
    <script src="<?= $basePath ?>/public/js/login/script.js?v=<?= $loginScriptVersion ?>"></script>
</body>

</html>