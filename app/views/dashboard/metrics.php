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
    <title>Metricas - Dashboard</title>
    <link rel="icon" type="image/png" href="<?= $basePath ?>/public/img/com-fundo-maior.png" sizes="512x512">
    <link rel="apple-touch-icon" href="<?= $basePath ?>/public/img/com-fundo-maior.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= $basePath ?>/public/css/global/style.css?v=<?= $globalStyleVersion ?>">
</head>

<body class="admin-page">
    <?php include_once __DIR__ . '/../../../includes/navbar.php'; ?>

    <main id="main-content">
        <section class="container mt-4 fade-in-up dashboard-section">
            <header class="main-header fade-in-up">
                <h1 class="system-title">Gerenciamento de Metricas</h1>
                <p class="system-subtitle">Cadastre, edite e desative metricas que recebem impacto das respostas do formulario.</p>
            </header>

            <?php if ($flash): ?>
                <div class="dashboard-flash <?= htmlspecialchars((string) $flash['type']) ?>">
                    <?= htmlspecialchars((string) $flash['message']) ?>
                </div>
            <?php endif; ?>

            <div class="dashboard-card">
                <div class="dashboard-item-header">
                    <div>
                        <h2 class="h5 mb-1">Nova metrica</h2>
                        <p class="mb-0 text-muted">Defina a chave usada nos mapeamentos das perguntas e a descricao operacional da metrica.</p>
                    </div>
                </div>

                <form method="post" action="<?= $basePath ?>/dashboard/metrics/create" class="dashboard-form-grid">
                    <div>
                        <label class="form-label">Chave da metrica</label>
                        <input type="text" name="metric_key" class="form-control" placeholder="ex: autonomy_score" required>
                    </div>

                    <div>
                        <label class="form-label">Nome</label>
                        <input type="text" name="name" class="form-control" placeholder="Autonomia" required>
                    </div>

                    <div style="grid-column: 1 / -1;">
                        <label class="form-label">Descricao</label>
                        <textarea name="description" class="form-control" rows="4" placeholder="Explica como a metrica e usada no sistema."></textarea>
                    </div>

                    <div class="dashboard-actions" style="grid-column: 1 / -1;">
                        <button type="submit" class="btn btn-primary">Cadastrar metrica</button>
                    </div>
                </form>
            </div>

            <div class="dashboard-card mt-4">
                <div class="dashboard-item-header">
                    <div>
                        <h2 class="h5 mb-1">Metricas cadastradas</h2>
                        <p class="mb-0 text-muted">Mantenha as chaves consistentes com os mapeamentos de perguntas para evitar relacoes quebradas.</p>
                    </div>
                </div>

                <div class="dashboard-item-list">
                    <?php if ($metrics === []): ?>
                        <div class="dashboard-item">
                            <p class="mb-0 text-muted">Nenhuma metrica cadastrada.</p>
                        </div>
                    <?php endif; ?>

                    <?php foreach ($metrics as $metric): ?>
                        <?php
                        $metricId = (int) $metric['id'];
                        $isActive = (int) $metric['active'] === 1;
                        ?>
                        <article class="dashboard-item <?= $isActive ? '' : 'is-inactive' ?>">
                            <div class="dashboard-item-header">
                                <div>
                                    <h3 class="h6 mb-1"><?= htmlspecialchars((string) $metric['name']) ?></h3>
                                    <p class="mb-0 text-muted"><?= htmlspecialchars((string) $metric['metric_key']) ?></p>
                                </div>
                                <span class="dashboard-badge <?= $isActive ? '' : 'is-inactive' ?>">
                                    <?= $isActive ? 'Ativa' : 'Inativa' ?>
                                </span>
                            </div>

                            <form method="post" action="<?= $basePath ?>/dashboard/metrics/update" class="dashboard-form-grid mt-3">
                                <input type="hidden" name="metric_id" value="<?= $metricId ?>">

                                <div>
                                    <label class="form-label">Chave da metrica</label>
                                    <input type="text" name="metric_key" class="form-control" value="<?= htmlspecialchars((string) $metric['metric_key']) ?>" required>
                                </div>

                                <div>
                                    <label class="form-label">Nome</label>
                                    <input type="text" name="name" class="form-control" value="<?= htmlspecialchars((string) $metric['name']) ?>" required>
                                </div>

                                <div style="grid-column: 1 / -1;">
                                    <label class="form-label">Descricao</label>
                                    <textarea name="description" class="form-control" rows="4"><?= htmlspecialchars((string) ($metric['description'] ?? '')) ?></textarea>
                                </div>

                                <div class="dashboard-actions" style="grid-column: 1 / -1;">
                                    <button type="submit" class="btn btn-primary">Salvar alteracoes</button>
                                </div>
                            </form>

                            <?php if ($isActive): ?>
                                <form method="post" action="<?= $basePath ?>/dashboard/metrics/delete" class="dashboard-actions mt-3">
                                    <input type="hidden" name="metric_id" value="<?= $metricId ?>">
                                    <button type="submit" class="btn btn-outline-danger">Desativar metrica</button>
                                </form>
                            <?php endif; ?>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    </main>

    <?php include_once __DIR__ . '/../../../includes/footer.php'; ?>
    <?php include_once __DIR__ . '/../../../includes/dependencies.php'; ?>
</body>

</html>
