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
    <title>Lorem Ipsum</title>
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

<body class="home-page">
<?php include_once __DIR__ . '/../../../includes/navbar.php'; ?>

<button class="home-right-bubble" id="toggleAside" type="button" title="Lorem ipsum">
    <i class="bi bi-chat-dots"></i>
</button>

<main id="main-content" class="home-rebuilt">
    <section class="hero-panel hero-panel-1 fade-in-up">
        <div class="hero-panel-inner">
            <p class="hero-kicker">Lorem Ipsum</p>
            <h1>Lorem ipsum dolor sit amet, consectetur adipiscing elit sed do eiusmod.</h1>
            <p class="hero-copy">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
            </p>
            <div class="hero-actions">
                <a href="<?= $basePath ?>/forms" class="btn btn-primary btn-lg">
                    <i class="bi bi-send-check me-2"></i>Lorem Ipsum
                </a>
                <a href="<?= $basePath ?>/forms" class="btn btn-outline-primary btn-lg">
                    <i class="bi bi-mortarboard me-2"></i>Dolor Sit
                </a>
            </div>
        </div>
    </section>

    <section class="hero-info-cards fade-in-up">
        <div class="hero-panel-inner">
            <div class="hero-info-head">
                <h2>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</h2>
                <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip.</p>
            </div>
            <div class="hero-cards-grid">
                <article class="hero-info-card">
                    <h3>Lorem A</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                </article>
                <article class="hero-info-card">
                    <h3>Lorem B</h3>
                    <p>Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                </article>
                <article class="hero-info-card">
                    <h3>Lorem C</h3>
                    <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.</p>
                </article>
                <article class="hero-info-card">
                    <h3>Lorem D</h3>
                    <p>Duis aute irure dolor in reprehenderit in voluptate velit esse.</p>
                </article>
                <article class="hero-info-card">
                    <h3>Lorem E</h3>
                    <p>Excepteur sint occaecat cupidatat non proident, sunt in culpa.</p>
                </article>
                <article class="hero-info-card hero-info-card-cta">
                    <h3>Lorem F</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit sed do.</p>
                    <button type="button" class="btn btn-outline-primary" id="openAsideInfo">
                        <i class="bi bi-compass me-2"></i>Lorem
                    </button>
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




