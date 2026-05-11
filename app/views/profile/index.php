<?php
use App\config\Auth;

$basePath = isset($_SERVER['APP_BASE_PATH']) ? (string) $_SERVER['APP_BASE_PATH'] : '';
$globalStylePath = __DIR__ . '/../../../public/css/global/style.css';
$globalStyleVersion = file_exists($globalStylePath) ? filemtime($globalStylePath) : time();
$profileScriptPath = __DIR__ . '/../../../public/js/profile/script.js';
$profileScriptVersion = file_exists($profileScriptPath) ? filemtime($profileScriptPath) : time();
$authUser = Auth::user();
$authUserName = trim((string) ($authUser['full_name'] ?? ''));
$authUserEmail = trim((string) ($authUser['email'] ?? ''));
$authUserRole = trim((string) ($authUser['role'] ?? ''));
$authLoginAt = trim((string) ($authUser['login_at'] ?? ''));
$authResetRequired = !empty($authUser['reset_required']);

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

if (!function_exists('mmp_role_label')) {
    function mmp_role_label(string $role): string
    {
        return match (strtolower(trim($role))) {
            'admin' => 'Administrador',
            'user' => 'Aluno',
            default => $role !== '' ? ucfirst($role) : 'Usuario',
        };
    }
}

if (!function_exists('mmp_format_login_at')) {
    function mmp_format_login_at(string $value): string
    {
        if ($value === '') {
            return 'Nao registrado';
        }

        try {
            $date = new DateTimeImmutable($value);
            return $date->format('d/m/Y H:i');
        } catch (Throwable) {
            return $value;
        }
    }
}

$authUserInitials = mmp_initials($authUserName);
$roleLabel = mmp_role_label($authUserRole);
$formattedLoginAt = mmp_format_login_at($authLoginAt);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil</title>

    <link rel="icon" type="image/png" href="<?= $basePath ?>/public/img/logo-v2.png" sizes="512x512">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= $basePath ?>/public/css/global/style.css?v=<?= $globalStyleVersion ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/cropperjs@1.6.2/dist/cropper.min.css">
</head>
<body class="admin-page">

<?php include_once __DIR__ . '/../../../includes/navbar.php'; ?>

<main id="main-content">
    <section class="container mt-4 fade-in-up">
        <div class="row g-4">
            <aside class="col-lg-4 col-xl-3">
                <div class="card profile-sidebar profile-sidebar-card p-4 text-center">
                    <div class="profile-avatar mb-3">
                        <div class="avatar-circle avatar-large">
                            <span><?= htmlspecialchars($authUserInitials, ENT_QUOTES, 'UTF-8') ?></span>
                        </div>
                    </div>

                    <h1 class="h4 mb-1"><?= htmlspecialchars($authUserName !== '' ? $authUserName : 'Usuario', ENT_QUOTES, 'UTF-8') ?></h1>
                    <p class="text-muted mb-3"><?= htmlspecialchars($authUserEmail !== '' ? $authUserEmail : 'E-mail nao informado', ENT_QUOTES, 'UTF-8') ?></p>

                    <div class="profile-chip-row">
                        <span class="dashboard-badge"><?= htmlspecialchars($roleLabel, ENT_QUOTES, 'UTF-8') ?></span>
                        <span class="dashboard-badge <?= $authResetRequired ? 'is-inactive' : '' ?>">
                            <?= $authResetRequired ? 'Troca de senha pendente' : 'Conta ativa' ?>
                        </span>
                    </div>

                    <nav class="profile-menu mt-4">
                        <a href="#" class="profile-link active" data-tab="profile">Minhas informacoes</a>
                        <a href="#" class="profile-link" data-tab="photo">Minha foto</a>
                    </nav>

                    <div class="profile-side-actions mt-4">
                        <a href="<?= $basePath ?>/forms" class="btn btn-primary w-100">Responder formulario</a>
                        <a href="<?= $basePath ?>/chat" class="btn btn-outline-primary w-100">Abrir chat</a>
                        <a href="<?= $basePath ?>/login/change-password" class="btn btn-outline-primary w-100">Alterar senha</a>
                    </div>
                </div>
            </aside>

            <div class="col-lg-8 col-xl-9">
                <div class="card profile-main-card p-4 p-lg-5">
                    <div class="tab-content active" id="tab-profile">
                        <header class="main-header">
                            <h2 class="system-title">Minhas informacoes</h2>
                            <p class="system-subtitle">Resumo da conta usada para acessar o sistema e acompanhar sua trilha.</p>
                        </header>

                        <div class="profile-info-grid">
                            <article class="profile-info-card">
                                <span class="profile-info-label">Nome completo</span>
                                <strong class="profile-info-value"><?= htmlspecialchars($authUserName !== '' ? $authUserName : 'Nao informado', ENT_QUOTES, 'UTF-8') ?></strong>
                            </article>

                            <article class="profile-info-card">
                                <span class="profile-info-label">E-mail</span>
                                <strong class="profile-info-value"><?= htmlspecialchars($authUserEmail !== '' ? $authUserEmail : 'Nao informado', ENT_QUOTES, 'UTF-8') ?></strong>
                            </article>

                            <article class="profile-info-card">
                                <span class="profile-info-label">Perfil de acesso</span>
                                <strong class="profile-info-value"><?= htmlspecialchars($roleLabel, ENT_QUOTES, 'UTF-8') ?></strong>
                            </article>

                            <article class="profile-info-card">
                                <span class="profile-info-label">Ultimo login</span>
                                <strong class="profile-info-value"><?= htmlspecialchars($formattedLoginAt, ENT_QUOTES, 'UTF-8') ?></strong>
                            </article>
                        </div>

                        <div class="profile-summary-card mt-4">
                            <h3 class="h5 mb-3">Situacao da conta</h3>
                            <div class="profile-summary-list">
                                <div class="profile-summary-item">
                                    <span>Autenticacao atual</span>
                                    <strong><?= $authResetRequired ? 'Requer troca de senha' : 'Sessao ativa e valida' ?></strong>
                                </div>
                                <div class="profile-summary-item">
                                    <span>Uso principal</span>
                                    <strong><?= strtolower($authUserRole) === 'admin' ? 'Administracao do formulario e metricas' : 'Acompanhamento da trilha personalizada' ?></strong>
                                </div>
                                <div class="profile-summary-item">
                                    <span>Proximo passo recomendado</span>
                                    <strong>Responder o formulario e revisar a trilha gerada pela IA</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-content" id="tab-photo">
                        <header class="main-header">
                            <h2 class="system-title">Minha foto</h2>
                            <p class="system-subtitle">Escolha uma imagem, recorte no formato circular e visualize como ela pode aparecer no seu perfil.</p>
                        </header>

                        <div class="row g-4 align-items-start">
                            <div class="col-lg-7">
                                <div class="profile-photo-card">
                                    <label for="input-image" class="form-label">Selecionar imagem</label>
                                    <input type="file" id="input-image" class="form-control" accept="image/*">

                                    <div class="photo-crop-area mt-3">
                                        <img id="image-to-crop" style="max-width:100%; display:none;">
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-5">
                                <div class="profile-photo-card">
                                    <h3 class="h5 mb-3">Preview</h3>
                                    <div class="preview-box">
                                        <div class="avatar-circle avatar-preview">
                                            <img id="preview-image" alt="Preview da foto" style="display:none;">
                                            <span id="preview-fallback"><?= htmlspecialchars($authUserInitials, ENT_QUOTES, 'UTF-8') ?></span>
                                        </div>
                                    </div>

                                    <div class="mt-4 d-flex gap-2 flex-wrap">
                                        <button class="btn btn-outline-primary" id="btn-change" type="button">Alterar</button>
                                        <button class="btn btn-primary" id="btn-save" type="button">Salvar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include_once __DIR__ . '/../../../includes/footer.php'; ?>
<?php include_once __DIR__ . '/../../../includes/dependencies.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/cropperjs@1.6.2/dist/cropper.min.js"></script>
<script>
    window.BASE_PATH = "<?= $basePath ?>";
</script>
<script src="<?= $basePath ?>/public/js/profile/script.js?v=<?= $profileScriptVersion ?>"></script>

</body>
</html>
