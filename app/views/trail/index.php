<?php

use App\config\Auth;

$basePath = isset($_SERVER['APP_BASE_PATH']) ? (string) $_SERVER['APP_BASE_PATH'] : '';
$globalStylePath = __DIR__ . '/../../../public/css/global/style.css';
$globalStyleVersion = file_exists($globalStylePath) ? filemtime($globalStylePath) : time();
$trailScriptPath = __DIR__ . '/../../../public/js/trail/script.js';
$trailScriptVersion = file_exists($trailScriptPath) ? filemtime($trailScriptPath) : time();
$authUser = Auth::user();
$authUserName = trim((string) ($authUser['full_name'] ?? ''));

if (!function_exists('trail_user_first_name')) {
    function trail_user_first_name(string $name): string
    {
        $parts = preg_split('/\s+/', trim($name)) ?: [];
        $parts = array_values(array_filter($parts, static fn ($part) => $part !== ''));
        return $parts[0] ?? 'Aluno';
    }
}

$firstName = trail_user_first_name($authUserName);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minha Trilha</title>
    <link rel="icon" type="image/png" href="<?= $basePath ?>/public/img/logo-v2.png" sizes="512x512">
    <link rel="apple-touch-icon" href="<?= $basePath ?>/public/img/logo-v2.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= $basePath ?>/public/css/global/style.css?v=<?= $globalStyleVersion ?>">
</head>
<body class="trail-page">

<?php include_once __DIR__ . '/../../../includes/navbar.php'; ?>

<main id="main-content">
    <section class="trail-page-shell fade-in-up">
        <header class="trail-page-hero">
            <div class="trail-page-hero-copy">
                <span class="trail-kicker">Plano de estudo</span>
                <h1 class="system-title mb-0">Sua trilha de aprendizagem</h1>
                <p class="system-subtitle">Aqui fica a resposta estruturada da IA com diagnóstico, etapas, checkpoints e recursos priorizados para você estudar com mais direção.</p>
            </div>
            <div class="trail-page-hero-actions">
                <div class="trail-page-hero-chip">
                    <span>Aluno</span>
                    <strong><?= htmlspecialchars($firstName, ENT_QUOTES, 'UTF-8') ?></strong>
                </div>
                <button type="button" class="btn btn-outline-light" id="trailExportBtn">Exportar PDF</button>
                <a href="<?= $basePath ?>/forms" class="btn btn-light">Responder novamente</a>
            </div>
        </header>

        <section class="trail-shell">
            <div class="trail-overview-card">
                <div class="trail-overview-copy">
                    <span class="trail-kicker">Trilha gerada por IA</span>
                    <h3 id="trailGoalTitle">Aguardando trilha</h3>
                    <p id="trailGoalSummary">Assim que o processamento terminar, seu plano aparece aqui com foco, ritmo e próximos passos.</p>
                </div>
                <div class="trail-overview-meta">
                    <div class="trail-meta-item">
                        <span>Gerada em</span>
                        <strong id="trailGeneratedAt">-</strong>
                    </div>
                    <div class="trail-meta-item">
                        <span>Intensidade</span>
                        <strong id="trailIntensity">-</strong>
                    </div>
                    <div class="trail-meta-item">
                        <span>Ritmo sugerido</span>
                        <strong id="trailSchedule">-</strong>
                    </div>
                </div>
            </div>

            <div class="trail-stat-grid">
                <div class="card p-3 text-center profile-stat-card">
                    <h6>Nivel de suporte</h6>
                    <h3 id="supportLevel">-</h3>
                </div>

                <div class="card p-3 text-center profile-stat-card">
                    <h6>Carga total</h6>
                    <h3 id="totalTime">0h</h3>
                </div>

                <div class="card p-3 text-center profile-stat-card">
                    <h6>Etapas</h6>
                    <h3 id="completedCount">0</h3>
                </div>

                <div class="card p-3 text-center profile-stat-card">
                    <h6>Checkpoints</h6>
                    <h3 id="checkpointCount">0</h3>
                </div>
            </div>

            <div class="dashboard-flash success d-none" id="trailFreshNotice">
                Sua trilha foi gerada com sucesso e ja esta pronta para consulta.
            </div>

            <div class="learning-flow" id="learningFlow"></div>
        </section>
    </section>
</main>

<?php include_once __DIR__ . '/../../../includes/footer.php'; ?>
<?php include_once __DIR__ . '/../../../includes/dependencies.php'; ?>
<script>
    window.BASE_PATH = "<?= $basePath ?>";
</script>
<script src="<?= $basePath ?>/public/js/trail/script.js?v=<?= $trailScriptVersion ?>"></script>
</body>
</html>
