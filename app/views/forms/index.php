<?php
$basePath = isset($_SERVER['APP_BASE_PATH']) ? (string)$_SERVER['APP_BASE_PATH'] : '';
$userScriptPath = __DIR__ . '/../../../public/js/forms/script.js';
$userScriptVersion = file_exists($userScriptPath) ? filemtime($userScriptPath) : time();
$globalStylePath = __DIR__ . '/../../../public/css/global/style.css';
$globalStyleVersion = file_exists($globalStylePath) ? filemtime($globalStylePath) : time();
$questionsDefinitionJson = json_encode($questionsDefinition ?? [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
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
        <section class="forms-stage fade-in-up">
            <header class="forms-hero">
                <div class="forms-hero-copy">
                    <span class="forms-hero-kicker">Diagnóstico inicial</span>
                    <h1 class="system-title mb-0">Anamnese acadêmica</h1>
                    <p class="system-subtitle">Responda com calma. Suas respostas ajudam o sistema a entender seu momento atual, identificar dificuldades e montar uma trilha de aprendizagem mais útil.</p>
                </div>
                <div class="forms-hero-aside">
                    <div class="forms-hero-stat">
                        <strong><?= count($questionsDefinition ?? []) ?></strong>
                        <span>Perguntas ativas</span>
                    </div>
                    <ul class="forms-hero-notes">
                        <li>Leva cerca de 10 a 15 minutos.</li>
                        <li>Você pode avançar por etapas.</li>
                        <li>A trilha aparece no seu perfil ao final.</li>
                    </ul>
                </div>
            </header>

            <div class="forms-layout">
                <aside class="forms-side-panel">
                    <div class="forms-side-card">
                        <span class="forms-side-label">Como funciona</span>
                        <h2>Preencha por blocos curtos</h2>
                        <p>Cada resposta ajuda a compor seu perfil acadêmico, hábitos de estudo, familiaridade digital e expectativas sobre o assistente.</p>
                    </div>
                    <div class="forms-side-card forms-side-card-accent">
                        <span class="forms-side-label">Navegação</span>
                        <p>Use os números para revisar respostas anteriores e os botões no rodapé para seguir no seu ritmo.</p>
                    </div>
                </aside>

                <div class="card shadow-sm register-config-card form-shell wizard-shell">
                    <div class="card-body p-4 p-lg-5">
                    <?php if (($questionsDefinition ?? []) === []): ?>
                        <div class="dashboard-flash error mb-4">
                            Nenhuma pergunta ativa foi cadastrada ainda. Peça ao administrador para publicar o formulário antes de responder.
                        </div>
                    <?php endif; ?>

                    <div class="wizard-topbar">
                        <div class="wizard-progress-wrap">
                            <span class="wizard-section-label">Progresso</span>
                            <div class="wizard-progress-bar">
                                <div class="wizard-progress-fill" id="wizard-progress-fill"></div>
                            </div>
                            <p class="wizard-progress-text mb-0" id="wizard-progress-text">Carregando perguntas...</p>
                        </div>

                        <div class="wizard-stepper-wrap">
                            <span class="wizard-section-label">Etapas</span>
                            <div class="wizard-stepper" id="wizard-stepper" aria-label="Navegação das perguntas"></div>
                            <p class="wizard-stepper-hint mb-0">
                                A ordem segue exatamente as perguntas cadastradas no sistema.
                            </p>
                        </div>
                    </div>

                    <form
                        id="education-interest-form"
                        class="wizard-form"
                        data-submit-url="<?= $basePath ?>/forms/save"
                        data-success-url="<?= $basePath ?>/profile"
                    >
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
    <script>
        window.FORM_QUESTION_DEFINITIONS = <?= is_string($questionsDefinitionJson) ? $questionsDefinitionJson : '[]' ?>;
    </script>
    <script src="<?= $basePath ?>/public/js/shared/aside-chatbot.js"></script>
    <script src="<?= $basePath ?>/public/js/forms/script.js?v=<?= $userScriptVersion ?>"></script>
</body>

</html>
