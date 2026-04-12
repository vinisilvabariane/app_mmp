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
    <title>Map My Path - Chat</title>
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

<body class="form-page chat-page">
<?php include_once __DIR__ . '/../../../includes/navbar.php'; ?>

<main id="main-content" class="chat-main">
    <section class="fade-in-up chat-page-stage">
        <div class="card shadow-sm register-config-card form-shell chat-shell mx-auto">
            <div class="card-body p-4 p-lg-4 d-flex flex-column gap-4">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                    <div>
                        <h1 class="system-title h3 mb-1">Chat</h1>
                        <p class="system-subtitle">Converse com o assistente sem sair da página.</p>
                    </div>
                    <button type="button" class="btn btn-outline-primary" id="chat-reset-top">
                        <i class="bi bi-arrow-counterclockwise me-2"></i>Resetar chat
                    </button>
                </div>

                <div class="chat-page-card">
                    <div class="chatbot-messages chat-page-messages" id="chat-messages">
                        <div class="chat-empty-state" id="chat-empty-state" aria-hidden="true">
                            <i class="bi bi-chat-square-text"></i>
                            <span>Escreva a primeira mensagem para iniciar a conversa.</span>
                        </div>
                    </div>

                    <form class="chat-page-form" id="chat-form" data-endpoint="<?= $basePath ?>/chat/message">
                        <div>
                            <label for="chat-message" class="form-label">Mensagem</label>
                            <textarea id="chat-message" class="form-control chat-page-input" rows="3" placeholder="Digite sua pergunta aqui..."></textarea>
                        </div>
                        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                            <small class="text-muted" id="chat-status">Envie uma mensagem para começar.</small>
                            <button type="submit" class="btn btn-primary" id="chat-submit">
                                <i class="bi bi-send me-2"></i>Enviar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include_once __DIR__ . '/../../../includes/footer.php'; ?>
<?php include_once __DIR__ . '/../../../includes/dependencies.php'; ?>

<script src="<?= $basePath ?>/public/js/chat/script.js"></script>
</body>

</html>
