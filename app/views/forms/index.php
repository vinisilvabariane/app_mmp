<?php
$basePath = isset($_SERVER['APP_BASE_PATH']) ? (string)$_SERVER['APP_BASE_PATH'] : '';
$userScriptPath = __DIR__ . '/../../../public/js/forms/script.js';
$userScriptVersion = file_exists($userScriptPath) ? filemtime($userScriptPath) : time();
$globalStylePath = __DIR__ . '/../../../public/css/global/style.css';
$globalStyleVersion = file_exists($globalStylePath) ? filemtime($globalStylePath) : time();
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulário de Anamnese</title>
    <link rel="icon" type="image/png" href="<?= $basePath ?>/public/img/logo-v2.png" sizes="512x512">
    <link rel="apple-touch-icon" href="<?= $basePath ?>/public/img/logo-v2.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700&family=Nunito:wght@400;600;700&display=swap">
    <link rel="stylesheet" href="<?= $basePath ?>/public/css/global/style.css?v=<?= $globalStyleVersion ?>">
</head>

<body class="form-page">
    <?php include_once __DIR__ . '/../../../includes/navbar.php'; ?>

    <button class="home-right-bubble" id="toggleAside" type="button" title="Lorem ipsum">
        <i class="bi bi-chat-dots"></i>
    </button>

    <main id="main-content">
        <header class="main-header fade-in-up">
            <h1 class="system-title">Anamnese</h1>
            <p class="system-subtitle">Este formulário nos ajuda a entender seu nível, suas dificuldades e preferências, para recomendar o melhor caminho de aprendizagem..</p>
        </header>

        <section class="mt-4 fade-in-up">
            <div class="card shadow-sm register-config-card form-shell wizard-shell">
                <div class="card-body p-4 p-lg-5">
                    <div class="wizard-progress-wrap mb-4">
                        <div class="wizard-progress-bar">
                            <div class="wizard-progress-fill" id="wizard-progress-fill"></div>
                        </div>
                        <p class="wizard-progress-text mb-0" id="wizard-progress-text">Pergunta 1 de 35</p>
                    </div>

                    <div class="wizard-stepper-wrap mb-4">
                        <div class="wizard-stepper" id="wizard-stepper" aria-label="Navegação das perguntas"></div>
                        <p class="wizard-stepper-hint mb-0">
                            Use os números para navegar. Perguntas obrigatórias pendentes ficam destacadas.
                        </p>
                    </div>

                    <form id="education-interest-form" class="wizard-form">
                        <div class="wizard-track" id="wizard-track"></div>

                        <div class="wizard-actions">
                            <button type="button" class="btn btn-outline-primary" id="wizard-prev" disabled>
                                <i class="bi bi-arrow-left me-2"></i>Voltar
                            </button>
                            <button type="button" class="btn btn-primary" id="wizard-next">
                                Próximo<i class="bi bi-arrow-right ms-2"></i>
                            </button>
                            <button type="submit" class="btn btn-primary d-none" id="wizard-submit">
                                <i class="bi bi-send-check me-2"></i>Finalizar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </main>

    <?php include_once __DIR__ . '/../../../includes/footer.php'; ?>
    <?php include_once __DIR__ . '/../../../includes/infoAside.php'; ?>
    <?php include_once __DIR__ . '/../../../includes/dependencies.php'; ?>
    <script src="<?= $basePath ?>/public/js/shared/aside-chatbot.js"></script>
    <script src="<?= $basePath ?>/public/js/forms/questions.js"></script>
    <script src="<?= $basePath ?>/public/js/forms/script.js?v=<?= $userScriptVersion ?>"></script>
</body>

</html>
