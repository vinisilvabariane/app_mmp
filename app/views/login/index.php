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
            <h1>Crie trilhas de estudos personalizadas.</h1>
            <p class="hero-copy">
                Aplicativo para criar trilhas de estudos personalizadas, com foco em resultados. Ideal para estudantes que buscam otimizar seu aprendizado e alcançar seus objetivos de forma eficiente.
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
                        <p>Autenticação para garantir a privacidade dos dados.</p>
                    </div>
                </article>
                <article class="login-feature-card">
                    <span class="login-feature-icon"><i class="bi bi-graph-up-arrow"></i></span>
                    <div>
                        <h2>Estude de maneira inteligente</h2>
                        <p>Descubra a melhor forma de obter resultados!</p>
                    </div>
                </article>
                <article class="login-feature-card">
                    <span class="login-feature-icon"><i class="bi bi-lightbulb"></i></span>
                    <div>
                        <h2>Dicas inteligentes</h2>
                        <p>Receba sugestões personalizadas para otimizar seu aprendizado.</p>
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
                data-base-path="<?= $basePath ?>"
                novalidate>
                <div
                    id="error-message"
                    class="alert alert-warning<?= $loginError !== '' ? '' : ' d-none' ?>"
                    role="alert"><?= htmlspecialchars($loginError, ENT_QUOTES, 'UTF-8') ?></div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" autocomplete="email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Senha</label>
                    <div class="password-field">
                        <input type="password" class="form-control password-field-input" id="password" name="password" autocomplete="current-password" required>
                        <button type="button" class="password-toggle" data-password-target="password" aria-label="Mostrar senha" aria-pressed="false">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>
                <div class="login-secondary-actions">
                    <button type="button" class="btn btn-link px-0 login-link-btn" id="toggle-reset-password">
                        Resetar senha
                    </button>
                    <button type="button" class="btn btn-link px-0 login-link-btn" id="toggle-register-user">
                        Criar conta 
                    </button>
                </div>
                <div class="login-reset-panel d-none" id="reset-password-panel">
                    <label for="reset-email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="reset-email" placeholder="Digite o email da conta">
                    <button type="button" class="btn btn-outline-primary login-inline-button" id="request-password-reset">Enviar senha</button>
                    <small class="text-muted">Uma senha aleatoria sera enviada por email e a troca sera obrigatoria no proximo login.</small>
                </div>
                <div class="login-reset-panel d-none" id="register-user-panel">
                    <div class="login-register-grid">
                        <div>
                            <label for="register-full-name" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="register-full-name" placeholder="Nome completo">
                        </div>
                        <div>
                            <label for="register-email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="register-email" placeholder="voce@email.com">
                        </div>
                        <div>
                            <label for="register-password" class="form-label">Senha</label>
                            <div class="password-field">
                                <input type="password" class="form-control password-field-input" id="register-password" placeholder="Minimo 8 caracteres">
                                <button type="button" class="password-toggle" data-password-target="register-password" aria-label="Mostrar senha" aria-pressed="false">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div>
                            <label for="register-password-confirm" class="form-label">Confirmar senha</label>
                            <div class="password-field">
                                <input type="password" class="form-control password-field-input" id="register-password-confirm" placeholder="Repita a senha">
                                <button type="button" class="password-toggle" data-password-target="register-password-confirm" aria-label="Mostrar senha" aria-pressed="false">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="login-register-actions">
                        <button type="button" class="btn btn-outline-primary" id="register-user-button">Criar conta</button>
                    </div>
                    <small class="text-muted">A conta sera criada imediatamente e ja podera usar o login normal.</small>
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
