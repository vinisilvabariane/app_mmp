<?php
$basePath = isset($_SERVER['APP_BASE_PATH']) ? (string) $_SERVER['APP_BASE_PATH'] : '';
$globalStylePath = __DIR__ . '/../../../public/css/global/style.css';
$globalStyleVersion = file_exists($globalStylePath) ? filemtime($globalStylePath) : time();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Map My Path</title>
    <link rel="icon" type="image/png" href="<?= $basePath ?>/public/img/com-fundo-maior.png" sizes="512x512">
    <link rel="apple-touch-icon" href="<?= $basePath ?>/public/img/com-fundo-maior.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= $basePath ?>/public/css/global/style.css?v=<?= $globalStyleVersion ?>">
</head>
<body class="admin-page">
    <?php include_once __DIR__ . '/../../../includes/navbar.php'; ?>

    <main id="main-content">
        <section class="container mt-4 dashboard-section">
            <header class="main-header">
                <h1 class="system-title">Administracao do formulario pedagogico</h1>
                <p class="system-subtitle">Aqui o professor define as metricas, cadastra as perguntas e escolhe como cada resposta afeta a leitura pedagogica da IA.</p>
            </header>

            <div class="dashboard-grid">
                <article class="dashboard-card p-4">
                    <div class="dashboard-item-header">
                        <div>
                            <h2 class="h5 mb-1">Metricas</h2>
                            <p class="mb-0 text-muted">Cadastre os eixos que serao afetados pelas respostas, como autonomia, risco ou base matematica.</p>
                        </div>
                        <a href="<?= $basePath ?>/dashboard/metrics" class="btn btn-primary">Abrir tela</a>
                    </div>

                    <div class="dashboard-meta-grid">
                        <div class="dashboard-meta-box">
                            <strong><?= (int) ($summary['active_metrics'] ?? 0) ?></strong>
                            <span>metricas ativas</span>
                        </div>
                        <div class="dashboard-meta-box">
                            <strong><?= (int) ($summary['inactive_metrics'] ?? 0) ?></strong>
                            <span>metricas inativas</span>
                        </div>
                    </div>
                </article>

                <article class="dashboard-card p-4">
                    <div class="dashboard-item-header">
                        <div>
                            <h2 class="h5 mb-1">Perguntas</h2>
                            <p class="mb-0 text-muted">Cadastre o formulario, escolha a ordem de exibicao e vincule cada pergunta a uma ou mais metricas.</p>
                        </div>
                        <a href="<?= $basePath ?>/dashboard/questions" class="btn btn-primary">Abrir tela</a>
                    </div>

                    <div class="dashboard-meta-grid">
                        <div class="dashboard-meta-box">
                            <strong><?= (int) ($summary['active_questions'] ?? 0) ?></strong>
                            <span>perguntas ativas</span>
                        </div>
                        <div class="dashboard-meta-box">
                            <strong><?= (int) ($summary['inactive_questions'] ?? 0) ?></strong>
                            <span>perguntas inativas</span>
                        </div>
                        <div class="dashboard-meta-box">
                            <strong><?= (int) ($summary['active_mappings'] ?? 0) ?></strong>
                            <span>relacoes pergunta x metrica</span>
                        </div>
                    </div>
                </article>
            </div>

            <section class="dashboard-card p-4">
                <div class="dashboard-item-header">
                    <div>
                        <h2 class="h5 mb-1">Fluxo do sistema</h2>
                        <p class="mb-0 text-muted">A ordem correta para configurar o sistema.</p>
                    </div>
                </div>

                <div class="dashboard-item-list">
                    <article class="dashboard-item">
                        <h3 class="h6 mb-2">1. Cadastre as metricas</h3>
                        <p class="mb-0 text-muted">Cada metrica representa algo que a IA deve considerar ao montar a trilha do aluno.</p>
                    </article>
                    <article class="dashboard-item">
                        <h3 class="h6 mb-2">2. Cadastre as perguntas</h3>
                        <p class="mb-0 text-muted">Defina o enunciado, a ordem no formulario e, principalmente, quais metricas cada resposta afeta.</p>
                    </article>
                    <article class="dashboard-item">
                        <h3 class="h6 mb-2">3. Publique o formulario</h3>
                        <p class="mb-0 text-muted">As perguntas ativas sao sincronizadas automaticamente para a tela do aluno.</p>
                    </article>
                    <article class="dashboard-item">
                        <h3 class="h6 mb-2">4. Gere a trilha</h3>
                        <p class="mb-0 text-muted">Quando o aluno responde, o sistema calcula metricas, envia o contexto para a IA e salva a trilha gerada.</p>
                    </article>
                </div>
            </section>
        </section>
    </main>

    <?php include_once __DIR__ . '/../../../includes/footer.php'; ?>
    <?php include_once __DIR__ . '/../../../includes/dependencies.php'; ?>
</body>
</html>
