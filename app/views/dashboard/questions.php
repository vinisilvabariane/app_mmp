<?php
$basePath = isset($_SERVER['APP_BASE_PATH']) ? (string) $_SERVER['APP_BASE_PATH'] : '';
$globalStylePath = __DIR__ . '/../../../public/css/global/style.css';
$globalStyleVersion = file_exists($globalStylePath) ? filemtime($globalStylePath) : time();
$metricsJson = htmlspecialchars((string) json_encode($metrics, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), ENT_QUOTES, 'UTF-8');
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
                <p class="system-subtitle">Cadastre perguntas do formulario, defina a ordem de exibicao e vincule cada pergunta a pelo menos uma metrica.</p>
            </header>

            <?php if ($flash): ?>
                <div class="dashboard-flash <?= htmlspecialchars((string) $flash['type']) ?>">
                    <?= htmlspecialchars((string) $flash['message']) ?>
                </div>
            <?php endif; ?>

            <?php if ($metrics === []): ?>
                <div class="dashboard-flash error">
                    Cadastre pelo menos uma metrica antes de criar perguntas. O formulario exige pelo menos um impacto por pergunta.
                </div>
            <?php endif; ?>

            <div class="dashboard-card">
                <div class="dashboard-item-header">
                    <div>
                        <h2 class="h5 mb-1">Nova pergunta</h2>
                        <p class="mb-0 text-muted">A ordem salva aqui define como a pergunta aparece no formulario publicado para os alunos.</p>
                    </div>
                </div>

                <form method="post" action="<?= $basePath ?>/dashboard/questions/create" class="dashboard-form-grid question-config-form" data-form-id="create-question" data-metrics="<?= $metricsJson ?>">
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

                    <div class="full dashboard-subsection" data-role="text-config-section" style="grid-column: 1 / -1;">
                        <div class="dashboard-subsection-header">
                            <div>
                                <label class="form-label mb-1">Configuracao da resposta escrita</label>
                                <p class="dashboard-form-help mb-0">Defina como a pergunta dissertativa deve aparecer para o aluno.</p>
                            </div>
                        </div>
                        <div class="dashboard-config-grid dashboard-config-grid-text">
                            <div>
                                <label class="form-label">Formato do campo</label>
                                <select name="text_input_type" class="form-select">
                                    <option value="textarea">Texto longo</option>
                                    <option value="text">Texto curto</option>
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Limite de caracteres</label>
                                <input type="number" name="text_maxlength" class="form-control" min="1" step="1" placeholder="Opcional">
                            </div>
                            <div style="grid-column: 1 / -1;">
                                <label class="form-label">Placeholder</label>
                                <input type="text" name="text_placeholder" class="form-control" placeholder="Ex: conte sua principal dificuldade">
                            </div>
                        </div>
                    </div>

                    <div class="full dashboard-subsection" data-role="scale-config-section" style="grid-column: 1 / -1;">
                        <div class="dashboard-subsection-header">
                            <div>
                                <label class="form-label mb-1">Rotulos da escala 1 a 5</label>
                                <p class="dashboard-form-help mb-0">Personalize o significado de cada ponto da escala para o aluno.</p>
                            </div>
                        </div>
                        <div class="dashboard-config-grid dashboard-config-grid-scale">
                            <?php for ($index = 1; $index <= 5; $index++): ?>
                                <div>
                                    <label class="form-label">Nivel <?= $index ?></label>
                                    <input type="text" name="scale_labels[]" class="form-control" placeholder="<?= $index ?>">
                                </div>
                            <?php endfor; ?>
                        </div>
                    </div>

                    <div class="full dashboard-subsection" data-role="options-section" style="grid-column: 1 / -1;">
                        <div class="dashboard-subsection-header">
                            <div>
                                <label class="form-label mb-1">Opcoes do formulario</label>
                                <p class="dashboard-form-help mb-0">Necessario para multipla escolha. A ordem das linhas e a ordem exibida ao aluno.</p>
                            </div>
                            <button type="button" class="btn btn-outline-primary" data-action="add-option">Adicionar opcao</button>
                        </div>

                        <div data-role="options-container" class="dashboard-config-stack">
                            <div class="dashboard-config-row" data-role="option-row" data-row="option">
                                <div class="dashboard-config-grid dashboard-config-grid-options">
                                    <div>
                                        <label class="form-label">Valor</label>
                                        <input type="text" name="option_values[]" class="form-control" placeholder="ex: publica">
                                    </div>
                                    <div>
                                        <label class="form-label">Rotulo</label>
                                        <input type="text" name="option_labels[]" class="form-control" placeholder="ex: Rede publica">
                                    </div>
                                    <div class="dashboard-config-row-action">
                                        <button type="button" class="btn btn-outline-danger" data-action="remove-row">Remover</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="full dashboard-subsection" style="grid-column: 1 / -1;">
                        <div class="dashboard-subsection-header">
                            <div>
                                <label class="form-label mb-1">Impacto nas metricas</label>
                                <p class="dashboard-form-help mb-0">Cada pergunta precisa ter pelo menos um impacto. Para perguntas de multipla escolha, voce pode atrelar o impacto a uma opcao especifica.</p>
                            </div>
                            <button type="button" class="btn btn-outline-primary" data-action="add-affect" <?= $metrics === [] ? 'disabled' : '' ?>>Adicionar impacto</button>
                        </div>

                        <div data-role="affects-container" class="dashboard-config-stack">
                            <div class="dashboard-config-row" data-row="affect">
                                <div class="dashboard-config-grid dashboard-config-grid-affects">
                                    <div>
                                        <label class="form-label">Metrica</label>
                                        <select name="affect_metric_ids[]" class="form-select" required>
                                            <option value="">Selecione</option>
                                            <?php foreach ($metrics as $metric): ?>
                                                <option value="<?= (int) $metric['id'] ?>"><?= htmlspecialchars((string) $metric['name']) ?> (<?= htmlspecialchars((string) $metric['metric_key']) ?>)</option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="form-label">Opcao relacionada</label>
                                        <select name="affect_option_values[]" class="form-select" data-role="affect-option-select">
                                            <option value="">Pergunta inteira</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="form-label">Peso</label>
                                        <input type="number" step="0.01" name="affect_weights[]" class="form-control" value="1">
                                    </div>
                                    <div>
                                        <label class="form-label">Impacto</label>
                                        <input type="text" name="affect_impact_types[]" class="form-control" value="sum" placeholder="sum">
                                    </div>
                                    <div class="dashboard-config-row-action">
                                        <button type="button" class="btn btn-outline-danger" data-action="remove-row">Remover</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="dashboard-actions" style="grid-column: 1 / -1;">
                        <button type="submit" class="btn btn-primary" <?= $metrics === [] ? 'disabled' : '' ?>>Cadastrar pergunta</button>
                    </div>
                </form>
            </div>

            <div class="dashboard-card mt-4">
                <div class="dashboard-item-header">
                    <div>
                        <h2 class="h5 mb-1">Perguntas cadastradas</h2>
                        <p class="mb-0 text-muted">Edite estrutura, ordem e relacoes com metricas sem precisar alterar o arquivo do formulario manualmente.</p>
                    </div>
                </div>

                <?php if ($questions === []): ?>
                    <div class="dashboard-item">
                        <p class="mb-0 text-muted">Nenhuma pergunta cadastrada.</p>
                    </div>
                <?php else: ?>
                    <div class="dashboard-table-wrap">
                        <table class="dashboard-table">
                            <thead>
                                <tr>
                                    <th>Pergunta</th>
                                    <th>Chave</th>
                                    <th>Tipo</th>
                                    <th>Ordem</th>
                                    <th>Impactos</th>
                                    <th>Status</th>
                                    <th>Acoes</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($questions as $question): ?>
                                    <?php
                                    $questionId = (int) $question['id'];
                                    $isActive = (int) $question['active'] === 1;
                                    $configFields = is_array($question['config_fields'] ?? null) ? $question['config_fields'] : [];
                                    ?>
                                    <tr class="<?= $isActive ? '' : 'is-inactive' ?>">
                                        <td>
                                            <strong><?= htmlspecialchars((string) $question['enunciado']) ?></strong>
                                            <p class="dashboard-table-description">
                                                <?= (int) ($question['is_required'] ?? 0) === 1 ? 'Obrigatoria' : 'Opcional' ?>
                                                <?php if ((int) ($question['allows_multiple'] ?? 0) === 1): ?>
                                                    • multiplas respostas
                                                <?php endif; ?>
                                            </p>
                                        </td>
                                        <td><code><?= htmlspecialchars((string) $question['question_key']) ?></code></td>
                                        <td><?= htmlspecialchars((string) $question['question_type']) ?></td>
                                        <td><?= (int) $question['question_order'] ?></td>
                                        <td><?= count($question['affects'] ?? []) ?></td>
                                        <td>
                                            <span class="dashboard-badge <?= $isActive ? '' : 'is-inactive' ?>">
                                                <?= $isActive ? 'Ativa' : 'Inativa' ?>
                                            </span>
                                        </td>
                                        <td>
                                            <details class="dashboard-inline-editor">
                                                <summary class="btn btn-outline-primary btn-sm">Editar</summary>
                                                <div class="dashboard-inline-editor-panel dashboard-inline-editor-panel-lg">
                                                    <form method="post" action="<?= $basePath ?>/dashboard/questions/update" class="dashboard-form-grid question-config-form" data-form-id="question-<?= $questionId ?>" data-metrics="<?= $metricsJson ?>">
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

                                                        <div class="full dashboard-subsection" data-role="text-config-section" style="grid-column: 1 / -1;">
                                                            <div class="dashboard-subsection-header">
                                                                <div>
                                                                    <label class="form-label mb-1">Configuracao da resposta escrita</label>
                                                                    <p class="dashboard-form-help mb-0">Defina como a pergunta dissertativa deve aparecer para o aluno.</p>
                                                                </div>
                                                            </div>
                                                            <div class="dashboard-config-grid dashboard-config-grid-text">
                                                                <div>
                                                                    <label class="form-label">Formato do campo</label>
                                                                    <select name="text_input_type" class="form-select">
                                                                        <option value="textarea" <?= (($configFields['input'] ?? 'textarea') === 'textarea') ? 'selected' : '' ?>>Texto longo</option>
                                                                        <option value="text" <?= (($configFields['input'] ?? '') === 'text') ? 'selected' : '' ?>>Texto curto</option>
                                                                    </select>
                                                                </div>
                                                                <div>
                                                                    <label class="form-label">Limite de caracteres</label>
                                                                    <input type="number" name="text_maxlength" class="form-control" min="1" step="1" value="<?= htmlspecialchars((string) ($configFields['maxlength'] ?? '')) ?>" placeholder="Opcional">
                                                                </div>
                                                                <div style="grid-column: 1 / -1;">
                                                                    <label class="form-label">Placeholder</label>
                                                                    <input type="text" name="text_placeholder" class="form-control" value="<?= htmlspecialchars((string) ($configFields['placeholder'] ?? '')) ?>" placeholder="Ex: conte sua principal dificuldade">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="full dashboard-subsection" data-role="scale-config-section" style="grid-column: 1 / -1;">
                                                            <div class="dashboard-subsection-header">
                                                                <div>
                                                                    <label class="form-label mb-1">Rotulos da escala 1 a 5</label>
                                                                    <p class="dashboard-form-help mb-0">Personalize o significado de cada ponto da escala para o aluno.</p>
                                                                </div>
                                                            </div>
                                                            <div class="dashboard-config-grid dashboard-config-grid-scale">
                                                                <?php for ($index = 0; $index < 5; $index++): ?>
                                                                    <div>
                                                                        <label class="form-label">Nivel <?= $index + 1 ?></label>
                                                                        <input type="text" name="scale_labels[]" class="form-control" value="<?= htmlspecialchars((string) (($configFields['scale_labels'][$index] ?? ''))) ?>" placeholder="<?= $index + 1 ?>">
                                                                    </div>
                                                                <?php endfor; ?>
                                                            </div>
                                                        </div>

                                                        <div class="full dashboard-subsection" data-role="options-section" style="grid-column: 1 / -1;">
                                                            <div class="dashboard-subsection-header">
                                                                <div>
                                                                    <label class="form-label mb-1">Opcoes do formulario</label>
                                                                    <p class="dashboard-form-help mb-0">A ordem das linhas define a ordem exibida ao aluno.</p>
                                                                </div>
                                                                <button type="button" class="btn btn-outline-primary" data-action="add-option">Adicionar opcao</button>
                                                            </div>

                                                            <div data-role="options-container" class="dashboard-config-stack">
                                                                <?php if (($question['options'] ?? []) === []): ?>
                                                                    <div class="dashboard-config-row" data-role="option-row" data-row="option">
                                                                        <div class="dashboard-config-grid dashboard-config-grid-options">
                                                                            <div>
                                                                                <label class="form-label">Valor</label>
                                                                                <input type="text" name="option_values[]" class="form-control" placeholder="ex: publica">
                                                                            </div>
                                                                            <div>
                                                                                <label class="form-label">Rotulo</label>
                                                                                <input type="text" name="option_labels[]" class="form-control" placeholder="ex: Rede publica">
                                                                            </div>
                                                                            <div class="dashboard-config-row-action">
                                                                                <button type="button" class="btn btn-outline-danger" data-action="remove-row">Remover</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                <?php endif; ?>

                                                                <?php foreach (($question['options'] ?? []) as $option): ?>
                                                                    <div class="dashboard-config-row" data-role="option-row" data-row="option">
                                                                        <div class="dashboard-config-grid dashboard-config-grid-options">
                                                                            <div>
                                                                                <label class="form-label">Valor</label>
                                                                                <input type="text" name="option_values[]" class="form-control" value="<?= htmlspecialchars((string) $option['option_value']) ?>" placeholder="ex: publica">
                                                                            </div>
                                                                            <div>
                                                                                <label class="form-label">Rotulo</label>
                                                                                <input type="text" name="option_labels[]" class="form-control" value="<?= htmlspecialchars((string) $option['option_label']) ?>" placeholder="ex: Rede publica">
                                                                            </div>
                                                                            <div class="dashboard-config-row-action">
                                                                                <button type="button" class="btn btn-outline-danger" data-action="remove-row">Remover</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                <?php endforeach; ?>
                                                            </div>
                                                        </div>

                                                        <div class="full dashboard-subsection" style="grid-column: 1 / -1;">
                                                            <div class="dashboard-subsection-header">
                                                                <div>
                                                                    <label class="form-label mb-1">Impacto nas metricas</label>
                                                                    <p class="dashboard-form-help mb-0">Toda pergunta deve manter pelo menos uma relacao ativa com uma metrica.</p>
                                                                </div>
                                                                <button type="button" class="btn btn-outline-primary" data-action="add-affect" <?= $metrics === [] ? 'disabled' : '' ?>>Adicionar impacto</button>
                                                            </div>

                                                            <div data-role="affects-container" class="dashboard-config-stack">
                                                                <?php if (($question['affects'] ?? []) === []): ?>
                                                                    <div class="dashboard-config-row" data-row="affect">
                                                                        <div class="dashboard-config-grid dashboard-config-grid-affects">
                                                                            <div>
                                                                                <label class="form-label">Metrica</label>
                                                                                <select name="affect_metric_ids[]" class="form-select" required>
                                                                                    <option value="">Selecione</option>
                                                                                    <?php foreach ($metrics as $metric): ?>
                                                                                        <option value="<?= (int) $metric['id'] ?>"><?= htmlspecialchars((string) $metric['name']) ?> (<?= htmlspecialchars((string) $metric['metric_key']) ?>)</option>
                                                                                    <?php endforeach; ?>
                                                                                </select>
                                                                            </div>
                                                                            <div>
                                                                                <label class="form-label">Opcao relacionada</label>
                                                                                <select name="affect_option_values[]" class="form-select" data-role="affect-option-select">
                                                                                    <option value="">Pergunta inteira</option>
                                                                                </select>
                                                                            </div>
                                                                            <div>
                                                                                <label class="form-label">Peso</label>
                                                                                <input type="number" step="0.01" name="affect_weights[]" class="form-control" value="1">
                                                                            </div>
                                                                            <div>
                                                                                <label class="form-label">Impacto</label>
                                                                                <input type="text" name="affect_impact_types[]" class="form-control" value="sum" placeholder="sum">
                                                                            </div>
                                                                            <div class="dashboard-config-row-action">
                                                                                <button type="button" class="btn btn-outline-danger" data-action="remove-row">Remover</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                <?php endif; ?>

                                                                <?php foreach (($question['affects'] ?? []) as $affect): ?>
                                                                    <div class="dashboard-config-row" data-row="affect">
                                                                        <div class="dashboard-config-grid dashboard-config-grid-affects">
                                                                            <div>
                                                                                <label class="form-label">Metrica</label>
                                                                                <select name="affect_metric_ids[]" class="form-select" required>
                                                                                    <option value="">Selecione</option>
                                                                                    <?php foreach ($metrics as $metric): ?>
                                                                                        <option value="<?= (int) $metric['id'] ?>" <?= (int) $metric['id'] === (int) $affect['metric_id'] ? 'selected' : '' ?>>
                                                                                            <?= htmlspecialchars((string) $metric['name']) ?> (<?= htmlspecialchars((string) $metric['metric_key']) ?>)
                                                                                        </option>
                                                                                    <?php endforeach; ?>
                                                                                </select>
                                                                            </div>
                                                                            <div>
                                                                                <label class="form-label">Opcao relacionada</label>
                                                                                <select name="affect_option_values[]" class="form-select" data-role="affect-option-select" data-selected="<?= htmlspecialchars((string) ($affect['option_value'] ?? '')) ?>">
                                                                                    <option value="">Pergunta inteira</option>
                                                                                </select>
                                                                            </div>
                                                                            <div>
                                                                                <label class="form-label">Peso</label>
                                                                                <input type="number" step="0.01" name="affect_weights[]" class="form-control" value="<?= htmlspecialchars((string) $affect['weight']) ?>">
                                                                            </div>
                                                                            <div>
                                                                                <label class="form-label">Impacto</label>
                                                                                <input type="text" name="affect_impact_types[]" class="form-control" value="<?= htmlspecialchars((string) $affect['impact_type']) ?>" placeholder="sum">
                                                                            </div>
                                                                            <div class="dashboard-config-row-action">
                                                                                <button type="button" class="btn btn-outline-danger" data-action="remove-row">Remover</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                <?php endforeach; ?>
                                                            </div>
                                                        </div>

                                                        <div class="dashboard-actions" style="grid-column: 1 / -1;">
                                                            <button type="submit" class="btn btn-primary">Salvar alteracoes</button>
                                                            <?php if ($isActive): ?>
                                                                <button
                                                                    type="submit"
                                                                    class="btn btn-outline-danger"
                                                                    formaction="<?= $basePath ?>/dashboard/questions/delete"
                                                                    formmethod="post"
                                                                >
                                                                    Desativar pergunta
                                                                </button>
                                                            <?php endif; ?>
                                                        </div>
                                                    </form>
                                                </div>
                                            </details>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <?php include_once __DIR__ . '/../../../includes/footer.php'; ?>
    <?php include_once __DIR__ . '/../../../includes/dependencies.php'; ?>
    <script src="<?= $basePath ?>/public/js/dashboard/question-config.js"></script>
</body>

</html>
