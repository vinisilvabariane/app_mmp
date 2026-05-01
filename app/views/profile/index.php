<?php
use App\config\Auth;

$basePath = isset($_SERVER['APP_BASE_PATH']) ? (string) $_SERVER['APP_BASE_PATH'] : '';
$globalStylePath = __DIR__ . '/../../../public/css/global/style.css';
$globalStyleVersion = file_exists($globalStylePath) ? filemtime($globalStylePath) : time();
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
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil</title>

    <link rel="icon" href="<?= $basePath ?>/public/img/com-fundo-maior.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= $basePath ?>/public/css/global/style.css?v=<?= $globalStyleVersion ?>">
    <link rel="stylesheet" href="<?= $basePath ?>/public/css/profile/style.css">
    <link rel="stylesheet" href="https://unpkg.com/cropperjs/dist/cropper.min.css">
</head>
<body class="admin-page">

<?php include_once __DIR__ . '/../../../includes/navbar.php'; ?>

<main id="main-content">
    <section class="container mt-4 fade-in-up">
        <div class="row g-4">
            <aside class="col-md-3">
                <div class="card profile-sidebar p-3 text-center">
                    <div class="profile-avatar mb-3">
                        <div class="avatar-circle avatar-large">
                            <span><?= htmlspecialchars($authUserInitials, ENT_QUOTES, 'UTF-8') ?></span>
                        </div>
                    </div>

                    <h5 class="mb-1"><?= htmlspecialchars($authUserName !== '' ? $authUserName : 'Usuario', ENT_QUOTES, 'UTF-8') ?></h5>

                    <nav class="profile-menu">
                        <a href="#" class="profile-link" data-tab="trail">Minha trilha</a>
                        <a href="#" class="profile-link active" data-tab="profile">Perfil</a>
                        <a href="#" class="profile-link" data-tab="photo">Foto</a>
                    </nav>
                </div>
            </aside>

            <div class="col-md-9">
                <div class="card p-4">
                    <div class="tab-content active" id="tab-trail">
                        <header class="main-header">
                            <h1 class="system-title">Minha trilha</h1>
                            <p class="system-subtitle">Seu plano de aprendizado personalizado.</p>
                        </header>

                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <div class="card p-3 text-center">
                                    <h6>Progresso</h6>
                                    <h3 id="progressPercent">0%</h3>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card p-3 text-center">
                                    <h6>Tempo concluido</h6>
                                    <h3 id="totalTime">0h</h3>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card p-3 text-center">
                                    <h6>Topicos concluidos</h6>
                                    <h3 id="completedCount">0</h3>
                                </div>
                            </div>
                        </div>

                        <div class="learning-flow" id="learningFlow"></div>
                    </div>

                    <div class="tab-content" id="tab-profile">
                        <header class="main-header">
                            <h1 class="system-title">Perfil Publico</h1>
                            <p class="system-subtitle">Visualize e edite informacoes sobre voce.</p>
                        </header>

                        <input type="text" class="form-control mb-2" placeholder="Primeiro nome">
                        <input type="text" class="form-control mb-2" placeholder="Sobrenome">
                        <input type="text" class="form-control mb-2" placeholder="Curso">

                        <h5 class="mt-4 mb-3">Objetivos</h5>

                        <div class="profile-bio-box">
                            <textarea class="form-control" rows="5"></textarea>
                        </div>
                    </div>

                    <div class="tab-content" id="tab-photo">
                        <header class="main-header">
                            <h1 class="system-title">Foto</h1>
                            <p class="system-subtitle">Adicione uma foto para seu perfil.</p>
                        </header>

                        <div class="mb-3">
                            <input type="file" id="input-image" class="form-control" accept="image/*">
                        </div>

                        <div class="mb-3">
                            <div class="photo-crop-area">
                                <img id="image-to-crop" style="max-width:100%; display:none;">
                            </div>
                        </div>

                        <div class="mt-4 d-flex gap-2">
                            <button class="btn btn-outline-primary" id="btn-change">Alterar</button>
                            <button class="btn btn-primary" id="btn-save">Salvar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include_once __DIR__ . '/../../../includes/footer.php'; ?>
<?php include_once __DIR__ . '/../../../includes/dependencies.php'; ?>

<script src="https://unpkg.com/cropperjs/dist/cropper.min.js"></script>
<script>
    window.BASE_PATH = "<?= $basePath ?>";
</script>
<script src="<?= $basePath ?>/public/js/profile/script.js"></script>

</body>
</html>
