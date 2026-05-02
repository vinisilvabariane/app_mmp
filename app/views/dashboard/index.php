<?php
$basePath = isset($_SERVER['APP_BASE_PATH']) ? (string)$_SERVER['APP_BASE_PATH'] : '';
$globalStylePath = __DIR__ . '/../../../public/css/global/style.css';
$globalStyleVersion = file_exists($globalStylePath) ? filemtime($globalStylePath) : time();
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Professor</title>
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

<body class="admin-page">
    <?php include_once __DIR__ . '/../../../includes/navbar.php'; ?>

    <main id="main-content">
        <section class="container mt-4 fade-in-up">
            <header class="main-header fade-in-up">
                <h1 class="system-title">Dashboard do Professor</h1>
                <p class="system-subtitle">Visualize respostas dos alunos, acompanhe desempenho e identifique padroes de aprendizagem.</p>
            </header>
        </section>

        <section class="container mt-4 fade-in-up">
            <div class="dashboard-grid mb-4">
                <article class="dashboard-card">
                    <div class="dashboard-item-header">
                        <div>
                            <h2 class="h5 mb-1">Perguntas</h2>
                            <p class="mb-0 text-muted">Cadastre, edite e desative perguntas do formulario.</p>
                        </div>
                        <a href="<?= $basePath ?>/dashboard/questions" class="btn btn-primary">Gerenciar</a>
                    </div>
                </article>

                <article class="dashboard-card">
                    <div class="dashboard-item-header">
                        <div>
                            <h2 class="h5 mb-1">Metricas</h2>
                            <p class="mb-0 text-muted">Defina as metricas usadas nos calculos e mapeamentos.</p>
                        </div>
                        <a href="<?= $basePath ?>/dashboard/metrics" class="btn btn-primary">Gerenciar</a>
                    </div>
                </article>
            </div>

            <div class="row g-3">
                <div class="col-md-3">
                    <div class="card p-3 shadow-sm">
                        <h6>Total de Alunos</h6>
                        <h3 id="total-alunos">--</h3>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card p-3 shadow-sm">
                        <h6>Media de Engajamento</h6>
                        <h3 id="media-engajamento">--</h3>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card p-3 shadow-sm">
                        <h6>Uso de IA</h6>
                        <h3 id="uso-ia">--</h3>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card p-3 shadow-sm">
                        <h6>Tempo de Estudo</h6>
                        <h3 id="tempo-estudo">--</h3>
                    </div>
                </div>
            </div>
        </section>

        <div class="container mt-4">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="card p-3 h-100 chart-card">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0">Engajamento</h6>
                            <button class="btn btn-sm btn-outline-primary" onclick="abrirGrafico('engajamento')">
                                Ver
                            </button>
                        </div>
                        <canvas id="graficoEngajamento"></canvas>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card p-3 h-100 chart-card">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0">Risco</h6>
                            <button class="btn btn-sm btn-outline-primary" onclick="abrirGrafico('risco')">
                                Ver
                            </button>
                        </div>
                        <canvas id="graficoRisco"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <section class="container mt-4 fade-in-up">
            <div class="card shadow-sm mb-4 filter-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="mb-0">Filtros</h5>

                        <button id="clear-filters" class="btn btn-outline-secondary btn-sm">
                            Limpar
                        </button>
                    </div>

                    <div class="row g-4 align-items-end">
                        <div class="col-md-6">
                            <label class="form-label small text-muted">Curso</label>
                            <div class="input-group custom-filter">
                                <span class="input-group-text">
                                    <i class="bi bi-mortarboard"></i>
                                </span>
                                <select id="filter-curso" class="form-select">
                                    <option value="">Todos os cursos</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small text-muted">Semestre</label>
                            <div class="input-group custom-filter">
                                <span class="input-group-text">
                                    <i class="bi bi-calendar3"></i>
                                </span>
                                <select id="filter-semestre" class="form-select">
                                    <option value="">Todos os semestres</option>
                                    <?php for ($i = 1; $i <= 10; $i++): ?>
                                        <option value="<?= $i ?>"><?= $i ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="container mt-4 fade-in-up">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between">
                    <h5>Respostas dos Alunos</h5>
                    <input type="text" id="searchAluno" class="form-control w-25" placeholder="Buscar aluno...">
                </div>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Curso</th>
                                <th>Semestre</th>
                                <th>Engajamento</th>
                                <th>Acoes</th>
                            </tr>
                        </thead>
                        <tbody id="tabela-alunos"></tbody>
                    </table>
                </div>
            </div>
        </section>

        <div class="modal fade" id="modalAluno" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5>Detalhes do Aluno</h5>
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body" id="detalhe-aluno"></div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalGrafico" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-lg modal-grafico-fixo">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="tituloGrafico">Grafico</h5>
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <canvas id="graficoExpandido"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script type="module" src="<?= $basePath ?>/public/js/dashboard/script.js"></script>
    <?php include_once __DIR__ . '/../../../includes/footer.php'; ?>
    <?php include_once __DIR__ . '/../../../includes/dependencies.php'; ?>
</body>

</html>
