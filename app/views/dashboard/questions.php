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
    <title>Perguntas - Dashboard</title>
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
                <h1 class="system-title">Gerenciamento de Perguntas</h1>
                <p class="system-subtitle">Cadastre perguntas do formulario, configure opcoes e relacione os impactos nas metricas.</p>
            </header>

            <?php if ($flash): ?>
                <div class="dashboard-flash <?= htmlspecialchars((string) $flash['type']) ?>">
                    <?= htmlspecialchars((string) $flash['message']) ?>
                </div>
            <?php endif; ?>

            <div class="dashboard-card">
                <div class="dashboard-item-header">
                    <div>
                        <h2 class="h5 mb-1">Nova pergunta</h2>
                        <p class="mb-0 text-muted">Use a mesma estrutura do JSON do formulario para manter o front e o banco alinhados.</p>
                    </div>
                </div>

                <form method="post" action="<?= $basePath ?>/dashboard/questions/create" class="dashboard-form-grid">
                    <div>
                        <label class="form-label">Chave da pergunta</label>
                        <input type="text" name="question_key" class="form-control" placeholder="ex: curso_interesse" required>
                    </div>

                    <div>
                        <label class="form-label">Tipo</label>
                        <select name="question_type" class="form-select" required>
                            <option value="dissertativa">Dissertativa</option>
                            <option value="intensidade_1_5">Intensidade 1 a 5</option>
                            <option value="multipla_escolha">Multipla escolha</option>
                        </select>
                    </div>

                    <div>
                        <label class="form-label">Ordem</label>
                        <input type="number" name="question_order" class="form-control" min="1" step="1" required>
                    </div>

                    <div class="dashboard-inline-grid">
                        <label class="form-check">
                            <input type="checkbox" name="is_required" class="form-check-input" checked>
                            <span class="form-check-label">Obrigatoria</span>
                        </label>

                        <label class="form-check">
                            <input type="checkbox" name="allows_multiple" class="form-check-input">
                            <span class="form-check-label">Permite varias respostas</span>
                        </label>
                    </div>

                    <div class="w-100" style="grid-column: 1 / -1;">
                        <label class="form-label">Enunciado</label>
                        <textarea name="enunciado" class="form-control" rows="3" required></textarea>
                    </div>

                    <div>
                        <label class="form-label">Config JSON</label>
                        <textarea name="config_json" class="form-control" rows="6" placeholder='{"input":"textarea"}'></textarea>
                        <p class="dashboard-form-help">Opcional. Use para guardar configuracoes extras da pergunta.</p>
                    </div>

                    <div>
                        <label class="form-label">Opcoes</label>
                        <textarea name="options_text" class="form-control" rows="6" placeholder="publica|Rede publica&#10;privada|Rede privada"></textarea>
                        <p class="dashboard-form-help">Uma linha por opcao no formato <code>value|Label</code>.</p>
                    </div>

                    <div class="w-100" style="grid-column: 1 / -1;">
                        <label class="form-label">Impacto nas metricas</label>
                        <textarea name="affects_text" class="form-control" rows="6" placeholder="risk_score|privada|0.50|sum&#10;autonomy_score||1|sum"></textarea>
                        <p class="dashboard-form-help">Uma linha por relacao no formato <code>metric_key|option_value|weight|impact_type</code>. Para perguntas sem opcao especifica, deixe o segundo campo vazio.</p>
                    </div>

                    <div class="dashboard-actions" style="grid-column: 1 / -1;">
                        <button type="submit" class="btn btn-primary">Cadastrar pergunta</button>
                    </div>
                </form>
            </div>

            <div class="dashboard-card mt-4">
                <div class="dashboard-item-header">
                    <div>
                        <h2 class="h5 mb-1">Perguntas cadastradas</h2>
                        <p class="mb-0 text-muted">Edite a estrutura, desative perguntas antigas e revise o efeito nas metricas.</p>
                    </div>
                </div>

                <div class="dashboard-item-list">
                    <?php if ($questions === []): ?>
                        <div class="dashboard-item">
                            <p class="mb-0 text-muted">Nenhuma pergunta cadastrada.</p>
                        </div>
                    <?php endif; ?>

                    <?php foreach ($questions as $question): ?>
                        <?php
                        $questionId = (int) $question['id'];
                        $isActive = (int) $question['active'] === 1;
                        ?>
                        <article class="dashboard-item <?= $isActive ? '' : 'is-inactive' ?>">
                            <div class="dashboard-item-header">
                                <div>
                                    <h3 class="h6 mb-1"><?= htmlspecialchars((string) $question['enunciado']) ?></h3>
                                    <p class="mb-0 text-muted">
                                        <?= htmlspecialchars((string) $question['question_key']) ?>
                                        • ordem <?= (int) $question['question_order'] ?>
                                        • <?= htmlspecialchars((string) $question['question_type']) ?>
                                    </p>
                                </div>
                                <span class="dashboard-badge <?= $isActive ? '' : 'is-inactive' ?>">
                                    <?= $isActive ? 'Ativa' : 'Inativa' ?>
                                </span>
                            </div>

                            <form method="post" action="<?= $basePath ?>/dashboard/questions/update" class="dashboard-form-grid mt-3">
                                <input type="hidden" name="question_id" value="<?= $questionId ?>">

                                <div>
                                    <label class="form-label">Chave da pergunta</label>
                                    <input type="text" name="question_key" class="form-control" value="<?= htmlspecialchars((string) $question['question_key']) ?>" required>
                                </div>

                                <div>
                                    <label class="form-label">Tipo</label>
                                    <select name="question_type" class="form-select" required>
                                        <option value="dissertativa" <?= $question['question_type'] === 'dissertativa' ? 'selected' : '' ?>>Dissertativa</option>
                                        <option value="intensidade_1_5" <?= $question['question_type'] === 'intensidade_1_5' ? 'selected' : '' ?>>Intensidade 1 a 5</option>
                                        <option value="multipla_escolha" <?= $question['question_type'] === 'multipla_escolha' ? 'selected' : '' ?>>Multipla escolha</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="form-label">Ordem</label>
                                    <input type="number" name="question_order" class="form-control" min="1" step="1" value="<?= (int) $question['question_order'] ?>" required>
                                </div>

                                <div class="dashboard-inline-grid">
                                    <label class="form-check">
                                        <input type="checkbox" name="is_required" class="form-check-input" <?= (int) $question['is_required'] === 1 ? 'checked' : '' ?>>
                                        <span class="form-check-label">Obrigatoria</span>
                                    </label>

                                    <label class="form-check">
                                        <input type="checkbox" name="allows_multiple" class="form-check-input" <?= (int) $question['allows_multiple'] === 1 ? 'checked' : '' ?>>
                                        <span class="form-check-label">Permite varias respostas</span>
                                    </label>
                                </div>

                                <div class="w-100" style="grid-column: 1 / -1;">
                                    <label class="form-label">Enunciado</label>
                                    <textarea name="enunciado" class="form-control" rows="3" required><?= htmlspecialchars((string) $question['enunciado']) ?></textarea>
                                </div>

                                <div>
                                    <label class="form-label">Config JSON</label>
                                    <textarea name="config_json" class="form-control" rows="6"><?= htmlspecialchars((string) ($question['config_json'] ?? '')) ?></textarea>
                                </div>

                                <div>
                                    <label class="form-label">Opcoes</label>
                                    <textarea name="options_text" class="form-control" rows="6"><?= htmlspecialchars((string) $question['options_text']) ?></textarea>
                                </div>

                                <div class="w-100" style="grid-column: 1 / -1;">
                                    <label class="form-label">Impacto nas metricas</label>
                                    <textarea name="affects_text" class="form-control" rows="6"><?= htmlspecialchars((string) $question['affects_text']) ?></textarea>
                                </div>

                                <div class="dashboard-actions" style="grid-column: 1 / -1;">
                                    <button type="submit" class="btn btn-primary">Salvar alteracoes</button>
                                </div>
                            </form>

                            <?php if ($isActive): ?>
                                <form method="post" action="<?= $basePath ?>/dashboard/questions/delete" class="dashboard-actions mt-3">
                                    <input type="hidden" name="question_id" value="<?= $questionId ?>">
                                    <button type="submit" class="btn btn-outline-danger">Desativar pergunta</button>
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
