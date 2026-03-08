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

<body class="form-page">
<?php include_once __DIR__ . '/../../../includes/navbar.php'; ?>

<button class="home-right-bubble" id="toggleAside" type="button" title="Lorem ipsum">
    <i class="bi bi-chat-dots"></i>
</button>

<main id="main-content">
    <header class="main-header fade-in-up">
        <h1 class="system-title">Lorem Ipsum</h1>
        <p class="system-subtitle">Lorem ipsum dolor sit amet, consectetur adipiscing elit sed do eiusmod tempor.</p>
    </header>

    <section class="mt-4 fade-in-up">
        <div class="card shadow-sm register-config-card form-shell wizard-shell">
            <div class="card-body p-4 p-lg-5">
                <div class="wizard-progress-wrap mb-4">
                    <div class="wizard-progress-bar">
                        <div class="wizard-progress-fill" id="wizard-progress-fill"></div>
                    </div>
                    <p class="wizard-progress-text mb-0" id="wizard-progress-text">Lorem 1 de 7</p>
                </div>

                <form id="education-interest-form" class="wizard-form">
                    <div class="wizard-track" id="wizard-track">
                        <fieldset class="wizard-step active" data-step="1">
                            <legend class="wizard-question">Lorem ipsum dolor sit amet?</legend>
                            <div class="wizard-options">
                                <label class="wizard-option">
                                    <input type="radio" name="track" value="gestao-lideranca" required>
                                    <span>Lorem One</span>
                                </label>
                                <label class="wizard-option">
                                    <input type="radio" name="track" value="tecnologia-dados">
                                    <span>Lorem Two</span>
                                </label>
                                <label class="wizard-option">
                                    <input type="radio" name="track" value="comercial-atendimento">
                                    <span>Lorem Three</span>
                                </label>
                            </div>
                        </fieldset>

                        <fieldset class="wizard-step" data-step="2">
                            <legend class="wizard-question">Consectetur adipiscing elit?</legend>
                            <div class="wizard-options">
                                <label class="wizard-option">
                                    <input type="radio" name="role" value="coordenacao" required>
                                    <span>Option Alpha</span>
                                </label>
                                <label class="wizard-option">
                                    <input type="radio" name="role" value="docente">
                                    <span>Option Beta</span>
                                </label>
                                <label class="wizard-option">
                                    <input type="radio" name="role" value="treinamento-corporativo">
                                    <span>Option Gamma</span>
                                </label>
                                <label class="wizard-option">
                                    <input type="radio" name="role" value="aluno">
                                    <span>Option Delta</span>
                                </label>
                            </div>
                        </fieldset>

                        <fieldset class="wizard-step" data-step="3">
                            <legend class="wizard-question">Sed do eiusmod tempor?</legend>
                            <div class="wizard-input-group">
                                <label for="intake" class="form-label">Lorem month</label>
                                <input type="month" class="form-control" id="intake" name="intake" required>
                            </div>
                        </fieldset>

                        <fieldset class="wizard-step" data-step="4">
                            <legend class="wizard-question">Ut enim ad minim veniam?</legend>
                            <div class="wizard-options">
                                <label class="wizard-option">
                                    <input type="radio" name="class_size" value="ate-20" required>
                                    <span>Option One</span>
                                </label>
                                <label class="wizard-option">
                                    <input type="radio" name="class_size" value="21-50">
                                    <span>Option Two</span>
                                </label>
                                <label class="wizard-option">
                                    <input type="radio" name="class_size" value="51-plus">
                                    <span>Option Three</span>
                                </label>
                            </div>
                        </fieldset>

                        <fieldset class="wizard-step" data-step="5">
                            <legend class="wizard-question">Quis nostrud exercitation?</legend>
                            <div class="wizard-input-group">
                                <label for="goals" class="form-label">Lorem text</label>
                                <textarea id="goals" name="goals" class="form-control" rows="4" placeholder="Lorem ipsum dolor sit amet." required></textarea>
                            </div>
                        </fieldset>

                        <fieldset class="wizard-step" data-step="6">
                            <legend class="wizard-question">Lorem et dolore magna</legend>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="full-name" class="form-label">Lorem name</label>
                                    <input type="text" class="form-control" id="full-name" name="full_name" placeholder="Lorem Ipsum" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Lorem mail</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="lorem@ipsum.com" required>
                                </div>
                            </div>
                        </fieldset>

                        <fieldset class="wizard-step" data-step="7">
                            <legend class="wizard-question">Ut aliquip ex ea commodo</legend>
                            <div class="row g-3">
                                <div class="col-md-7">
                                    <label for="institution" class="form-label">Lorem institution</label>
                                    <input type="text" class="form-control" id="institution" name="institution" placeholder="Lorem Corp" required>
                                </div>
                                <div class="col-md-5">
                                    <label for="city" class="form-label">Lorem city</label>
                                    <input type="text" class="form-control" id="city" name="city" placeholder="Lorem City" required>
                                </div>
                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="accept-terms" name="accept_terms" required>
                                        <label class="form-check-label" for="accept-terms">
                                            Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>

                    <div class="wizard-actions mt-4">
                        <button type="button" class="btn btn-outline-primary" id="wizard-prev" disabled>
                            <i class="bi bi-arrow-left me-2"></i>Lorem
                        </button>
                        <button type="button" class="btn btn-primary" id="wizard-next">
                            Ipsum<i class="bi bi-arrow-right ms-2"></i>
                        </button>
                        <button type="submit" class="btn btn-primary d-none" id="wizard-submit">
                            <i class="bi bi-send-check me-2"></i>Dolor
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
<script src="<?= $basePath ?>/public/js/forms/script.js?v=<?= $userScriptVersion ?>"></script>
</body>
</html>



