<?php
$basePath = isset($_SERVER['APP_BASE_PATH']) ? (string)$_SERVER['APP_BASE_PATH'] : '';
$homeScriptPath = __DIR__ . '/../../../public/js/home/script.js';
$homeScriptVersion = file_exists($homeScriptPath) ? filemtime($homeScriptPath) : time();
$globalStylePath = __DIR__ . '/../../../public/css/global/style.css';
$globalStyleVersion = file_exists($globalStylePath) ? filemtime($globalStylePath) : time();
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Map My Path - Home</title>
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

<body class="home-page">
    <?php include_once __DIR__ . '/../../../includes/navbar.php'; ?>

    <button class="home-right-bubble" id="toggleAside" type="button" title="Lorem ipsum">
        <i class="bi bi-chat-dots"></i>
    </button>

    <main id="main-content" class="home-rebuilt">
        <section class="hero-panel hero-panel-1 fade-in-up">
            <div class="hero-panel-inner">
                <p class="hero-kicker">Map My Path</p>
                <h1>Trilhe seu caminho de conhecimento com planejamento.</h1>
                <p class="hero-copy">
                    Estude de forma personalizada e alcance seus objetivos com o Map My Path.
                </p>
                <div class="hero-actions">
                    <a href="<?= $basePath ?>/forms" class="btn btn-primary btn-lg">
                        <i class="bi bi-send-check me-2"></i>Inicie o apredizado.
                    </a>
                    <a href="<?= $basePath ?>/chat" class="btn btn-outline-primary btn-lg text-light">
                        <i class="bi bi-mortarboard me-2"></i>Converse com um chat.
                    </a>
                </div>
            </div>
        </section>

        <section class="hero-info-cards fade-in-up">
            <div class="hero-panel-inner">
                <div class="hero-info-head">
                    <h2>Obtenha um plano de estudos personalizados, que se adegue a sua necessidade.</h2>
                    <p>Um plano de estudos personalizado é uma excelente forma de organizar seu aprendizado e alcançar seus objetivos de forma mais eficiente.</p>
                </div>
                <div class="hero-cards-grid">
                    <article class="hero-info-card">
                        <h3>Seu plano, pra você!</h3>
                        <p>Uma análise detalhada das suas necessidades e objetivos.</p>
                    </article>
                    <article class="hero-info-card">
                        <h3>Ferramentas de IA</h3>
                        <p>Ferramentas de IA otimizadas para se adaptar às suas necessidades.</p>
                    </article>
                    <article class="hero-info-card">
                        <h3>Facilite seu aprendizado</h3>
                        <p>Recursos e ferramentas para tornar seu processo de aprendizado mais eficiente e agradável.</p>
                    </article>
                    <article class="hero-info-card">
                        <h3>Seu perfil, suas regras!</h3>
                        <p>Conforme suas facilidades e dificuldades, comece pelo que melhor se adapta a você.</p>
                    </article>
                    <article class="hero-info-card">
                        <h3>Comece agora!</h3>
                        <p>Responda a algumas perguntas para criar seu plano de estudos personalizado.</p>
                    </article>
                    <article class="hero-info-card hero-info-card-cta">
                        <h3>Converse abertamente!</h3>
                        <p>Ficou com dúvidas? Converse com nosso assistente virtual!</p>
                        <!-- <button type="button" class="btn btn-outline-primary" id="openAsideInfo">
                            <i class="bi bi-compass me-2"></i>Lorem
                        </button> -->
                    </article>
                </div>
            </div>
        </section>
    </main>

    <?php include_once __DIR__ . '/../../../includes/footer.php'; ?>
    <?php include_once __DIR__ . '/../../../includes/infoAside.php'; ?>
    <?php include_once __DIR__ . '/../../../includes/dependencies.php'; ?>

    <script src="<?= $basePath ?>/public/js/shared/aside-chatbot.js"></script>
    <script src="<?= $basePath ?>/public/js/home/script.js?v=<?= $homeScriptVersion ?>"></script>
</body>

</html>