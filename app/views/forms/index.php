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

                <form id="education-interest-form" class="wizard-form">
                    <div class="wizard-track" id="wizard-track">
                        <!-- 1 -->
                        <fieldset class="wizard-step active" data-step="1">
                            <legend class="wizard-question">Qual campo do conhecimento gostaria de estudar?</legend>
                            <div class="wizard-options">
                                <label class="wizard-option"><input type="radio" name="q1" value="matematica" required><span>Matemática</span></label>
                                <label class="wizard-option"><input type="radio" name="q1" value="portugues"><span>Português</span></label>
                                <label class="wizard-option"><input type="radio" name="q1" value="ingles"><span>Inglês</span></label>
                                <label class="wizard-option"><input type="radio" name="q1" value="geografia"><span>Geografia</span></label>
                                <label class="wizard-option"><input type="radio" name="q1" value="fisica"><span>Física</span></label>
                                <label class="wizard-option"><input type="radio" name="q1" value="natureza"><span>Ciências da Natureza</span></label>
                            </div>
                        </fieldset>

                        <!-- 2 -->
                        <fieldset class="wizard-step" data-step="2">
                            <legend class="wizard-question">Qual o seu o tema de interesse dentro do campo do conhecimento escolhido?</legend>
                            <textarea name="q2" class="form-control wizard-textarea" required></textarea>
                        </fieldset>

                        <!-- 3 -->
                        <fieldset class="wizard-step" data-step="3">
                            <legend class="wizard-question">3.	No ensino médio, como você avalia sua base em Campo do conhecimento escolhido?</legend>
                            <div class="wizard-options">
                                <label class="wizard-option"><input type="radio" name="q3" value="1" required><span>1 Muito fraca</span></label>
                                <label class="wizard-option"><input type="radio" name="q3" value="2"><span>2</span></label>
                                <label class="wizard-option"><input type="radio" name="q3" value="3"><span>3</span></label>
                                <label class="wizard-option"><input type="radio" name="q3" value="4"><span>4</span></label>
                                <label class="wizard-option"><input type="radio" name="q3" value="5"><span>5 Excelente</span></label>
                            </div>
                        </fieldset>

                        <!-- 4 -->
                        <fieldset class="wizard-step" data-step="4">
                            <legend class="wizard-question">Quais conteúdos do ensino médio você sente mais dificuldade hoje? </legend>
                            <textarea name="q4" class="form-control wizard-textarea" required></textarea>
                        </fieldset>

                        <!-- 5 -->
                        <fieldset class="wizard-step" data-step="5">
                            <legend class="wizard-question">Você já teve contato com Tema escolhido antes da faculdade (ex.: cursinho, livro, vídeos)? </legend>
                            <div class="wizard-options">
                                <label class="wizard-option"><input type="radio" name="q5" value="1" required><span>1 Nenhum</span></label>
                                <label class="wizard-option"><input type="radio" name="q5" value="2"><span>2</span></label>
                                <label class="wizard-option"><input type="radio" name="q5" value="3"><span>3</span></label>
                                <label class="wizard-option"><input type="radio" name="q5" value="4"><span>4</span></label>
                                <label class="wizard-option"><input type="radio" name="q5" value="5"><span>5 Bastante</span></label>
                            </div>
                        </fieldset>

                        <!-- 6 -->
                        <fieldset class="wizard-step" data-step="6">
                            <legend class="wizard-question">No início de uma disciplina de Campo do conhecimento, o que mais te preocupa? </legend>
                            <textarea name="q6" class="form-control wizard-textarea" required></textarea>
                        </fieldset>

                        <!-- 7 -->
                        <fieldset class="wizard-step" data-step="7">
                            <legend class="wizard-question">Você fez o ensino médio, ou técnico em instituição pública ou privada?</legend>
                            <div class="wizard-options">
                                <label class="wizard-option"><input type="radio" name="q7" value="publica" required><span>Pública</span></label>
                                <label class="wizard-option"><input type="radio" name="q7" value="privada"><span>Privada</span></label>
                            </div>
                        </fieldset>

                        <!-- 8 -->
                        <fieldset class="wizard-step" data-step="8">
                            <legend class="wizard-question">Qual o seu curso (Graduação)?</legend>
                            <input type="text" name="q8" class="form-control" required>
                        </fieldset>

                        <!-- 9 -->
                        <fieldset class="wizard-step" data-step="9">
                            <legend class="wizard-question">Qual o semestre está cursando? </legend>
                            <div class="wizard-options">
                                <?php for($i=1;$i<=10;$i++): ?>
                                <label class="wizard-option"><input type="radio" name="q9" value="<?= $i ?>" required><span><?= $i ?></span></label>
                                <?php endfor; ?>
                            </div>
                        </fieldset>

                        <!-- 10 -->
                        <fieldset class="wizard-step" data-step="10">
                            <legend class="wizard-question">Qual o campus ou polo em que estuda?</legend>
                            <input type="text" name="q10" class="form-control">
                        </fieldset>

                        <!-- 11 -->
                        <fieldset class="wizard-step" data-step="11">
                            <legend class="wizard-question">Componentes curriculares EaD em andamento (ou já cursadas). </legend>
                            <textarea name="q11" class="form-control"></textarea>
                        </fieldset>

                        <!-- 12 -->
                        <fieldset class="wizard-step" data-step="12">
                            <legend class="wizard-question">Coeficiente de rendimento (CR) ou média atual. (Utilizar números de 0 a 10, com 3 casas decimais) </legend>
                            <input type="number" step="0.001" min="0" max="10" name="q12" class="form-control">
                        </fieldset>

                        <!-- 13 -->
                        <fieldset class="wizard-step" data-step="13">
                            <legend class="wizard-question">Em média, quantas horas por semana você consegue dedicar aos estudos fora do horário das aulas presenciais?</legend>
                            <div class="wizard-options">
                                <label class="wizard-option"><input type="radio" name="q13" value="2" required><span>Até 2h</span></label>
                                <label class="wizard-option"><input type="radio" name="q13" value="4"><span>2–4h</span></label>
                                <label class="wizard-option"><input type="radio" name="q13" value="6"><span>4–6h</span></label>
                                <label class="wizard-option"><input type="radio" name="q13" value="8"><span>6–8h</span></label>
                                <label class="wizard-option"><input type="radio" name="q13" value="10"><span>8–10h</span></label>
                                <label class="wizard-option"><input type="radio" name="q13" value="10+"><span>+10h</span></label>
                            </div>
                        </fieldset>

                        <!-- 14 -->
                        <fieldset class="wizard-step" data-step="14">
                            <legend class="wizard-question">Qual o seu local preferencial de estudo? </legend>
                            <div class="wizard-options">
                                <label class="wizard-option"><input type="checkbox" name="q14[]" value="casa"><span>Casa</span></label>
                                <label class="wizard-option"><input type="checkbox" name="q14[]" value="biblioteca"><span>Biblioteca</span></label>
                                <label class="wizard-option"><input type="checkbox" name="q14[]" value="lab"><span>Laboratório</span></label>
                                <label class="wizard-option"><input type="checkbox" name="q14[]" value="outro"><span>Outro</span></label>
                            </div>
                        </fieldset>

                        <!-- 15 -->
                        <fieldset class="wizard-step" data-step="15">
                            <legend class="wizard-question">Em que medida você sente que consegue organizar e manter seus estudos sem precisar sempre da orientação do professor ou colegas?
                                Avalie a si mesmo em relação à sua capacidade de estudar de forma independente, sem depender o tempo todo do professor ou de instruções externas.  
                            </legend>
                            <div class="wizard-options">
                                <?php for($i=1;$i<=5;$i++): ?>
                                <label class="wizard-option"><input type="radio" name="q15" value="<?= $i ?>" required><span><?= $i ?></span></label>
                                <?php endfor; ?>
                            </div>
                        </fieldset>

                        <!-- 16 -->
                        <fieldset class="wizard-step" data-step="16">
                            <legend class="wizard-question">16.	Quais estratégias você mais costuma usar para aprender?</legend>
                            <div class="wizard-options">
                                <label class="wizard-option"><input type="checkbox" name="q16[]" value="resumos"><span>Resumos</span></label>
                                <label class="wizard-option"><input type="checkbox" name="q16[]" value="mapas"><span>Mapas mentais</span></label>
                                <label class="wizard-option"><input type="checkbox" name="q16[]" value="video"><span>Vídeos</span></label>
                                <label class="wizard-option"><input type="checkbox" name="q16[]" value="exercicios"><span>Exercícios</span></label>
                            </div>
                        </fieldset>

                        <!-- 17 -->
                        <fieldset class="wizard-step" data-step="17">
                            <legend class="wizard-question">Preferência por conteúdos.</legend>
                            <div class="wizard-options">
                                <label class="wizard-option"><input type="checkbox" name="q17[]" value="textual"><span>Textual</span></label>
                                <label class="wizard-option"><input type="checkbox" name="q17[]" value="visual"><span>Visual</span></label>
                                <label class="wizard-option"><input type="checkbox" name="q17[]" value="auditivo"><span>Auditivo</span></label>
                                <label class="wizard-option"><input type="checkbox" name="q17[]" value="interativo"><span>Interativo</span></label>
                            </div>
                        </fieldset>

                        <!-- 18 -->
                        <fieldset class="wizard-step" data-step="18">
                            <legend class="wizard-question">Experiência prévia com mapas mentais, resumos, simuladores, quizzes.)</legend>
                            <div class="wizard-options">
                                <?php for($i=1;$i<=5;$i++): ?>
                                <label class="wizard-option"><input type="radio" name="q18" value="<?= $i ?>" required><span><?= $i ?></span></label>
                                <?php endfor; ?>
                            </div>
                        </fieldset>

                        <!-- 19 -->
                        <fieldset class="wizard-step" data-step="19">
                            <legend class="wizard-question">Motivação intrínseca (aprender por interesse) x extrínseca (nota, diploma).</legend>
                            <div class="wizard-options">
                                <?php for($i=1;$i<=5;$i++): ?>
                                <label class="wizard-option"><input type="radio" name="q19" value="<?= $i ?>" required><span><?= $i ?></span></label>
                                <?php endfor; ?>
                            </div>
                        </fieldset>

                        <!-- 20 -->
                        <fieldset class="wizard-step" data-step="20">
                            <legend class="wizard-question">Principais áreas de dificuldade (ex.: cálculo, programação, interpretação de texto).</legend>
                            <textarea name="q20" class="form-control"></textarea>
                        </fieldset>

                        <!-- 21 -->
                        <fieldset class="wizard-step" data-step="21">
                            <legend class="wizard-question">Quantas disciplinas EaD (ou digitais) você já cursou no seu curso até agora?</legend>
                            <input type="number" name="q21" class="form-control">
                        </fieldset>

                        <!-- 22 -->
                        <fieldset class="wizard-step" data-step="22">
                            <legend class="wizard-question">Em quantas dessas disciplinas você teve reprovação ou trancamento?</legend>
                            <div class="wizard-options">
                                <?php for($i=1;$i<=5;$i++): ?>
                                <label class="wizard-option"><input type="radio" name="q22" value="<?= $i ?>" required><span><?= $i ?></span></label>
                                <?php endfor; ?>
                            </div>
                        </fieldset>

                        <!-- 23 -->
                        <fieldset class="wizard-step" data-step="23">
                            <legend class="wizard-question">Como você se sente antes de provas e trabalhos?</legend>
                            <div class="wizard-options">
                                <?php for($i=1;$i<=5;$i++): ?>
                                <label class="wizard-option"><input type="radio" name="q23" value="<?= $i ?>" required><span><?= $i ?></span></label>
                                <?php endfor; ?>
                            </div>
                        </fieldset>

                        <!-- 24 -->
                        <fieldset class="wizard-step" data-step="24">
                            <legend class="wizard-question">Percepção sobre os maiores obstáculos no EaD?</legend>
                            <div class="wizard-options">
                                <label class="wizard-option"><input type="checkbox" name="q24[]" value="tempo"><span>Tempo disponível para estudar.</span></label>
                                <label class="wizard-option"><input type="checkbox" name="q24[]" value="conteudo"><span>Os conteúdos são complexos.</span></label>
                                <label class="wizard-option"><input type="checkbox" name="q24[]" value="engajamento"><span>Não consigo engajar nas aulas.</span></label>
                                <label class="wizard-option"><input type="checkbox" name="q24[]" value="apoio"><span>Não percebo o apoio docente.</span></label>
                                <label class="wizard-option"><input type="checkbox" name="q24[]" value="apoio_docente"><span>O apoio docente existe, mas não é o adequado para mim.</span></label>
                            </div>
                        </fieldset>

                        <!-- 25 -->
                        <fieldset class="wizard-step" data-step="25">
                            <legend class="wizard-question">Nível de familiaridade com o Ambiente Virtual de Aprendizagem (AVA ou plataforma)</legend>
                            <div class="wizard-options">
                                <?php for($i=1;$i<=5;$i++): ?>
                                <label class="wizard-option"><input type="radio" name="q25" value="<?= $i ?>" required><span><?= $i ?></span></label>
                                <?php endfor; ?>
                            </div>
                        </fieldset>

                        <!-- 26 -->
                        <fieldset class="wizard-step" data-step="26">
                            <legend class="wizard-question">Frequência acesso</legend>
                            <select name="q26" class="form-control">
                                <option>Diária</option>
                                <option>Semanal</option>
                                <option>Mensal</option>
                                <option>Eventual</option>
                                <option>Nenhuma</option>
                            </select>
                        </fieldset>

                        <!-- 27 -->
                        <fieldset class="wizard-step" data-step="27">
                            <legend class="wizard-question">Dispositivo mais usado por você para acessar aulas e estudar.</legend>
                            <div class="wizard-options">
                                <label class="wizard-option"><input type="checkbox" name="q27[]" value="pc_proprio"><span>Computador / Notebook próprio</span></label>
                                <label class="wizard-option"><input type="checkbox" name="q27[]" value="pc_faculdade"><span>Computador / Notebook da faculdade</span></label>
                                <label class="wizard-option"><input type="checkbox" name="q27[]" value="celular"><span>Celular</span></label>
                                <label class="wizard-option"><input type="checkbox" name="q27[]" value="tablet"><span>Tablet</span></label>
                            </div>
                        </fieldset>

                        <!-- 28 -->
                        <fieldset class="wizard-step" data-step="28">
                            <legend class="wizard-question">Qual o seu nível de conforto com ferramentas de IA. Nos referimos aqui aos chatbots, geradores de resumos, IA generativa (ChatGPT, por exemplo), etc.</legend>
                            <div class="wizard-options">
                                <?php for($i=1;$i<=5;$i++): ?>
                                <label class="wizard-option"><input type="radio" name="q28" value="<?= $i ?>" required><span><?= $i ?></span></label>
                                <?php endfor; ?>
                            </div>
                        </fieldset>

                        <!-- 29 -->
                        <fieldset class="wizard-step" data-step="29">
                            <legend class="wizard-question">Qual seu nível de dependência destas ferramentas? (chatbots, geradores de resumos, IA generativa)</legend>
                            <div class="wizard-options">
                                <?php for($i=1;$i<=5;$i++): ?>
                                <label class="wizard-option"><input type="radio" name="q29" value="<?= $i ?>" required><span><?= $i ?></span></label>
                                <?php endfor; ?>
                            </div>
                        </fieldset>

                        <!-- 30 -->
                        <fieldset class="wizard-step" data-step="30">
                            <legend class="wizard-question">30.	De que forma você acha que o assistente pode te ajudar mais?</legend>
                            <div class="wizard-options">
                                <label class="wizard-option"><input type="checkbox" name="q30[]" value="explicacao"><span>Explicações extras</span></label>
                                <label class="wizard-option"><input type="checkbox" name="q30[]" value="recomendacao"><span>Recomendações de materiais</span></label>
                                <label class="wizard-option"><input type="checkbox" name="q30[]" value="tempo"><span>Gestão de tempo</span></label>
                                <label class="wizard-option"><input type="checkbox" name="q30[]" value="prazo"><span>Lembretes de prazos</span></label>
                                <label class="wizard-option"><input type="checkbox" name="q30[]" value="roteiro"><span>Roteiro de estudos</span></label>
                            </div>
                        </fieldset>

                        <!-- 31 -->
                        <fieldset class="wizard-step" data-step="31">
                            <legend class="wizard-question">Como você gostaria de receber o retorno do assistente? </legend>
                            <div class="wizard-options">
                                <label class="wizard-option"><input type="checkbox" name="q31[]" value="relatorio"><span>Relatórios semanais</span></label>
                                <label class="wizard-option"><input type="checkbox" name="q31[]" value="alertas"><span>Alertas rápidos</span></label>
                                <label class="wizard-option"><input type="checkbox" name="q31[]" value="dashboard"><span>Dashboards visuais</span></label>
                            </div>
                        </fieldset>

                        <!-- 32 -->
                        <fieldset class="wizard-step" data-step="32">
                            <legend class="wizard-question">Grau de abertura para testes experimentais.</legend>
                            <div class="wizard-options">
                                <label class="wizard-option"><input type="checkbox" name="q32[]" value="prototipos"><span>Usar protótipos</span></label>
                                <label class="wizard-option"><input type="checkbox" name="q32[]" value="pesquisas"><span>Responder pesquisas rápidas</span></label>
                            </div>
                        </fieldset>

                        <!-- 33 -->
                        <fieldset class="wizard-step" data-step="33">
                            <legend class="wizard-question">Até o momento, como você avalia o seu desempenho em disciplinas EaD ou digitais já cursadas?  </legend>
                            <div class="wizard-options">
                                <?php for($i=1;$i<=5;$i++): ?>
                                <label class="wizard-option"><input type="radio" name="q33" value="<?= $i ?>" required><span><?= $i ?></span></label>
                                <?php endfor; ?>
                            </div>
                        </fieldset>

                        <!-- 34 -->
                        <fieldset class="wizard-step" data-step="34">
                            <legend class="wizard-question">Considere o quanto você se sente capaz de utilizar recursos digitais para estudar, realizar tarefas e resolver problemas acadêmicos (por exemplo: AVA, planilhas, editores de texto, ferramentas de IA, etc.).  </legend>
                            <div class="wizard-options">
                                <?php for($i=1;$i<=5;$i++): ?>
                                <label class="wizard-option"><input type="radio" name="q34" value="<?= $i ?>" required><span><?= $i ?></span></label>
                                <?php endfor; ?>
                            </div>
                        </fieldset>       
                        
                        <!-- 35 -->
                        <fieldset class="wizard-step" data-step="35">
                            <legend class="wizard-question">Pense em quanto você tem se envolvido com as atividades do curso: participação nas aulas, realização de tarefas, interação com colegas e professores e interesse em aprender.  </legend>
                            <div class="wizard-options">
                                <?php for($i=1;$i<=5;$i++): ?>
                                <label class="wizard-option"><input type="radio" name="q35" value="<?= $i ?>" required><span><?= $i ?></span></label>
                                <?php endfor; ?>
                            </div>
                        </fieldset>   
                    </div>

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
<script src="<?= $basePath ?>/public/js/forms/script.js?v=<?= $userScriptVersion ?>"></script>
</body>
</html>



