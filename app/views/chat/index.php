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

<button class="home-right-bubble" id="toggleAside" type="button" title="Abrir assistente lateral">
    <i class="bi bi-chat-dots"></i>
</button>

<main id="main-content">
    <header class="main-header fade-in-up">
        <h1 class="system-title">Chat inteligente</h1>
        <p class="system-subtitle">Uma interface de conversa por texto no mesmo padrão visual do sistema, com foco em leitura, contexto e resposta contínua.</p>
    </header>

    <section class="fade-in-up">
        <div class="card shadow-sm register-config-card form-shell overflow-hidden">
            <div class="card-body p-0">
                <div class="row g-0">
                    <aside class="col-12 col-lg-4 col-xl-3 border-end">
                        <div class="p-4 h-100 d-flex flex-column gap-4">
                            <div>
                                <button type="button" class="btn btn-primary w-100">
                                    <i class="bi bi-plus-circle me-2"></i>Nova conversa
                                </button>
                            </div>

                            <div>
                                <p class="hero-kicker mb-2">Conversas</p>
                                <div class="d-grid gap-2">
                                    <button type="button" class="btn btn-outline-primary text-start active">
                                        <i class="bi bi-chat-left-text me-2"></i>Planejamento de estudos
                                    </button>
                                    <button type="button" class="btn btn-outline-primary text-start">
                                        <i class="bi bi-chat-left-text me-2"></i>Dúvidas sobre carreira
                                    </button>
                                    <button type="button" class="btn btn-outline-primary text-start">
                                        <i class="bi bi-chat-left-text me-2"></i>Trilha personalizada
                                    </button>
                                </div>
                            </div>

                            <div class="mt-lg-auto">
                                <p class="hero-kicker mb-2">Atalhos</p>
                                <div class="d-flex flex-wrap gap-2">
                                    <span class="badge text-bg-light border">Resumo</span>
                                    <span class="badge text-bg-light border">Explicação</span>
                                    <span class="badge text-bg-light border">Próximos passos</span>
                                </div>
                            </div>
                        </div>
                    </aside>

                    <div class="col-12 col-lg-8 col-xl-9">
                        <div class="p-4 p-lg-5 d-flex flex-column gap-4" style="min-height: 72vh;">
                            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                                <div>
                                    <p class="hero-kicker mb-2">Conversa ativa</p>
                                    <h2 class="system-title h3 mb-1">Assistente virtual</h2>
                                    <p class="system-subtitle">Faça perguntas em linguagem natural e acompanhe as respostas em formato de chat.</p>
                                </div>
                                <div class="d-flex flex-wrap gap-2">
                                    <button type="button" class="btn btn-outline-primary">
                                        <i class="bi bi-clock-history me-2"></i>Histórico
                                    </button>
                                    <button type="button" class="btn btn-outline-primary">
                                        <i class="bi bi-three-dots me-2"></i>Mais ações
                                    </button>
                                </div>
                            </div>

                            <div class="chatbot-messages flex-grow-1" style="height: auto; min-height: 420px; padding: 18px;">
                                <div class="chat-msg bot">
                                    Olá. Posso ajudar com perguntas, orientações e respostas em texto dentro da plataforma.
                                </div>
                                <div class="chat-msg user">
                                    Quero entender qual trilha combina mais com meu perfil.
                                </div>
                                <div class="chat-msg bot">
                                    Posso te ajudar com isso. Se quiser, posso comparar opções, montar um plano inicial ou resumir os próximos passos.
                                </div>
                                <div class="chat-msg user">
                                    Comece com um plano inicial objetivo.
                                </div>
                            </div>

                            <div class="d-flex flex-wrap gap-2">
                                <button type="button" class="btn btn-sm btn-outline-primary">Criar plano inicial</button>
                                <button type="button" class="btn btn-sm btn-outline-primary">Resumir conversa</button>
                                <button type="button" class="btn btn-sm btn-outline-primary">Sugerir próximos passos</button>
                            </div>

                            <form class="d-grid gap-3">
                                <div>
                                    <label for="chat-message" class="form-label">Mensagem</label>
                                    <textarea id="chat-message" class="form-control" rows="4" placeholder="Digite sua pergunta aqui..."></textarea>
                                </div>
                                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                                    <small class="text-muted">Digite uma mensagem e envie para continuar a conversa.</small>
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-outline-primary">
                                            <i class="bi bi-paperclip me-2"></i>Anexar
                                        </button>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-send me-2"></i>Enviar mensagem
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include_once __DIR__ . '/../../../includes/footer.php'; ?>
<?php include_once __DIR__ . '/../../../includes/infoAside.php'; ?>
<?php include_once __DIR__ . '/../../../includes/dependencies.php'; ?>

<script src="<?= $basePath ?>/public/js/shared/aside-chatbot.js"></script>
</body>

</html>
