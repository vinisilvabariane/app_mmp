<?php
$basePath = isset($_SERVER['APP_BASE_PATH']) ? (string)$_SERVER['APP_BASE_PATH'] : '';
$changePasswordScriptPath = __DIR__ . '/../../../public/js/login/change-password.js';
$changePasswordScriptVersion = file_exists($changePasswordScriptPath) ? filemtime($changePasswordScriptPath) : time();
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
    <title>Map My Path - Nova senha</title>
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
            <h1>Defina uma nova senha para continuar.</h1>
            <p class="hero-copy">Sua conta recebeu uma senha temporaria. Antes de acessar o sistema, crie uma senha nova e segura.</p>
        </section>

        <section class="login-panel">
            <div class="login-panel-head">
                <span class="login-badge">Nova senha</span>
                <h2>Atualize sua credencial</h2>
                <p>Essa etapa e obrigatoria quando a conta foi resetada.</p>
            </div>
            <form
                id="change-password-form"
                class="login-form"
                method="POST"
                action="<?= $basePath ?>/login/update-password"
                novalidate>
                <div
                    id="error-message"
                    class="alert alert-warning<?= $loginError !== '' ? '' : ' d-none' ?>"
                    role="alert"><?= htmlspecialchars($loginError, ENT_QUOTES, 'UTF-8') ?></div>
                <div class="mb-3">
                    <label for="password" class="form-label">Nova senha</label>
                    <input type="password" class="form-control" id="password" name="password" autocomplete="new-password" required>
                </div>
                <div class="mb-3">
                    <label for="password_confirm" class="form-label">Confirmar nova senha</label>
                    <input type="password" class="form-control" id="password_confirm" name="password_confirm" autocomplete="new-password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100 login-submit" data-idle-label="Salvar nova senha">
                    <span class="login-submit-text">Salvar nova senha</span>
                    <i class="bi bi-arrow-right-short"></i>
                </button>
            </form>
        </section>
    </main>
    <?php include_once __DIR__ . '/../../../includes/dependencies.php'; ?>
    <script src="<?= $basePath ?>/public/js/login/change-password.js?v=<?= $changePasswordScriptVersion ?>"></script>
</body>

</html>
