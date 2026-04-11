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
    <title>Lorem Ipsum - Chat</title>
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

<body class="form-page">
<?php include_once __DIR__ . '/../../../includes/navbar.php'; ?>

<main id="main-content">
    <section class="fade-in-up">
        <div class="card shadow-sm register-config-card form-shell mx-auto" style="max-width: 900px;">
            <div class="card-body p-4 p-lg-5 d-flex flex-column gap-4">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                    <div>
                        <h1 class="system-title h3 mb-1">Chat</h1>
                        <p class="system-subtitle">Faça sua pergunta e continue a conversa.</p>
                    </div>
                    <button type="button" class="btn btn-outline-primary" id="chat-reset-top">
                        <i class="bi bi-arrow-counterclockwise me-2"></i>Resetar chat
                    </button>
                </div>

                <div class="chatbot-messages" id="chat-messages" style="min-height: 420px; padding: 18px;">
                    <div class="chat-msg bot" data-role="assistant">
                        Ola. Sou o assistente do Map My Path. Como posso ajudar?
                    </div>
                </div>

                <form class="d-grid gap-3" id="chat-form" data-endpoint="<?= $basePath ?>/chat/message">
                    <div>
                        <label for="chat-message" class="form-label">Pergunta</label>
                        <textarea id="chat-message" class="form-control" rows="4" placeholder="Digite sua pergunta aqui..."></textarea>
                    </div>
                    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                        <small class="text-muted" id="chat-status">Digite uma mensagem e envie para continuar a conversa.</small>
                        <button type="submit" class="btn btn-primary" id="chat-submit">
                            <i class="bi bi-send me-2"></i>Enviar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</main>

<?php include_once __DIR__ . '/../../../includes/footer.php'; ?>
<?php include_once __DIR__ . '/../../../includes/dependencies.php'; ?>

<script src="<?= $basePath ?>/public/js/chat/script.js"></script>
</body>

</html>
