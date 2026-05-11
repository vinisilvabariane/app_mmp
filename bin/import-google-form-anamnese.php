<?php

declare(strict_types=1);

use App\config\Env;
use App\services\FormQuestionSyncService;

require_once dirname(__DIR__) . '/vendor/autoload.php';

function connectToDatabase(): PDO
{
    $driver = Env::get('DB_DRIVER', 'mysql');
    $port = Env::get('DB_PORT', '3306');
    $database = Env::get('DB_NAME', '');
    $username = Env::get('DB_USER', 'root');
    $password = Env::get('DB_PASS', '');
    $charset = Env::get('DB_CHARSET', 'utf8mb4');

    $hosts = [];
    $configuredHost = Env::get('DB_HOST', '127.0.0.1');
    if ($configuredHost !== '') {
        $hosts[] = $configuredHost;
    }
    if (!in_array('127.0.0.1', $hosts, true)) {
        $hosts[] = '127.0.0.1';
    }
    if (!in_array('localhost', $hosts, true)) {
        $hosts[] = 'localhost';
    }

    $lastException = null;

    foreach ($hosts as $host) {
        $dsn = sprintf(
            '%s:host=%s;port=%s;dbname=%s;charset=%s',
            $driver,
            $host,
            $port,
            $database,
            $charset
        );

        try {
            return new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $exception) {
            $lastException = $exception;
        }
    }

    throw new RuntimeException(
        'Nao foi possivel conectar ao banco para importar as perguntas: '
        . ($lastException?->getMessage() ?? 'erro desconhecido')
    );
}

function normalizeOptionValue(string $label): string
{
    $value = trim($label);
    $value = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value) ?: $value;
    $value = strtolower($value);
    $value = preg_replace('/[^a-z0-9]+/', '_', $value) ?? $value;
    $value = trim($value, '_');
    return $value !== '' ? $value : 'opcao';
}

function questionDefinitions(): array
{
    return [
        [
            'question_key' => 'anamnese_q01_base_matematica',
            'enunciado' => 'No ensino médio, como você avalia sua base em Matemática?',
            'question_type' => 'intensidade_1_5',
            'allows_multiple' => 0,
            'is_required' => 1,
            'question_order' => 1,
            'config_json' => json_encode([
                'escala' => ['Muito fraca', '2', '3', '4', 'Excelente'],
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'options' => [],
        ],
        [
            'question_key' => 'anamnese_q02_dificuldades_medio',
            'enunciado' => 'Quais conteúdos do ensino médio você sente mais dificuldade hoje?',
            'question_type' => 'multipla_escolha',
            'allows_multiple' => 1,
            'is_required' => 1,
            'question_order' => 2,
            'config_json' => null,
            'options' => [
                'Funções (1º e 2º grau, exponenciais, logaritmos)',
                'Trigonometria',
                'Geometria analítica',
                'Progressões (PA/PG)',
                'Polinômios',
                'Álgebra',
                'Outro',
            ],
        ],
        [
            'question_key' => 'anamnese_q03_contato_previo_calculo',
            'enunciado' => 'Você já teve contato com Cálculo antes da faculdade (ex.: cursinho, livro, vídeos)?',
            'question_type' => 'intensidade_1_5',
            'allows_multiple' => 0,
            'is_required' => 1,
            'question_order' => 3,
            'config_json' => json_encode([
                'escala' => ['Não, não vi nada sobre o assunto', '2', '3', '4', 'Sim, bastante'],
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'options' => [],
        ],
        [
            'question_key' => 'anamnese_q04_preocupacao_calculo',
            'enunciado' => 'No início de uma disciplina de Cálculo, o que mais te preocupa?',
            'question_type' => 'multipla_escolha',
            'allows_multiple' => 1,
            'is_required' => 1,
            'question_order' => 4,
            'config_json' => null,
            'options' => [
                'Linguagem e símbolos matemáticos',
                'Volume de exercícios',
                'Dificuldade em interpretar problemas',
                'Tempo para acompanhar as aulas',
                'Outros (cite na próxima pergunta)',
                'Outro',
            ],
        ],
        [
            'question_key' => 'anamnese_q05_tipo_instituicao',
            'enunciado' => 'Você fez o ensino médio, ou técnico em instituição pública ou privada?',
            'question_type' => 'multipla_escolha',
            'allows_multiple' => 0,
            'is_required' => 1,
            'question_order' => 5,
            'config_json' => null,
            'options' => ['Pública', 'Privada'],
        ],
        [
            'question_key' => 'anamnese_q06_curso_graduacao',
            'enunciado' => 'Qual o seu curso (Graduação)?',
            'question_type' => 'dissertativa',
            'allows_multiple' => 0,
            'is_required' => 1,
            'question_order' => 6,
            'config_json' => json_encode([
                'input' => 'text',
                'attributes' => [
                    'placeholder' => 'Digite o nome do seu curso',
                    'maxlength' => 150,
                ],
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'options' => [],
        ],
        [
            'question_key' => 'anamnese_q07_semestre',
            'enunciado' => 'Qual o semestre está cursando?',
            'question_type' => 'multipla_escolha',
            'allows_multiple' => 0,
            'is_required' => 1,
            'question_order' => 7,
            'config_json' => null,
            'options' => ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10'],
        ],
        [
            'question_key' => 'anamnese_q08_campus_polo',
            'enunciado' => 'Qual o campus ou polo em que estuda?',
            'question_type' => 'multipla_escolha',
            'allows_multiple' => 0,
            'is_required' => 1,
            'question_order' => 8,
            'config_json' => null,
            'options' => [
                'Câmpus Bragança Paulista',
                'Câmpus Campinas (Cambuí)',
                'Câmpus Campinas (Swift)',
                'Câmpus Sagrado Coração',
                'Câmpus Itatiba',
                'Polo Cambuí',
                'Polo Pouso Alegre',
                'Polo Extrema',
                'Polo Itajubá',
                'Polo Petrópolis',
                'Polo Campinas (Cambuí)',
                'Polo Campinas (Swift)',
                'Polo Bragança Paulista',
                'Polo São José dos Campos',
                'Polo São Bernardo do Campo',
                'Polo Paulínia',
                'Polo Jundiaí',
                'Polo Mairiporã',
                'Polo Atibaia',
                'Polo Amparo',
            ],
        ],
        [
            'question_key' => 'anamnese_q09_horas_estudo',
            'enunciado' => 'Em média, quantas horas por semana você consegue dedicar aos estudos fora do horário das aulas presenciais?',
            'question_type' => 'multipla_escolha',
            'allows_multiple' => 0,
            'is_required' => 1,
            'question_order' => 9,
            'config_json' => null,
            'options' => [
                'Até 2 horas',
                'Entre 2 horas e 4 horas',
                'Entre 4 horas e 6 horas',
                'Entre 6 horas e 8 horas',
                'Entre 8 horas e 10 horas',
                'Mais de 10 horas',
            ],
        ],
        [
            'question_key' => 'anamnese_q10_local_estudo',
            'enunciado' => 'Qual o seu local preferencial de estudo?',
            'question_type' => 'multipla_escolha',
            'allows_multiple' => 1,
            'is_required' => 1,
            'question_order' => 10,
            'config_json' => null,
            'options' => [
                'Minha casa',
                'Casa de um parente ou amigo(a)',
                'Biblioteca da universidade',
                'Biblioteca pública',
                'Laboratório',
                'Outro',
            ],
        ],
        [
            'question_key' => 'anamnese_q11_autonomia_estudos',
            'enunciado' => 'Em que medida você sente que consegue organizar e manter seus estudos sem precisar sempre da orientação do professor ou colegas?',
            'question_type' => 'intensidade_1_5',
            'allows_multiple' => 0,
            'is_required' => 1,
            'question_order' => 11,
            'config_json' => json_encode([
                'escala' => [
                    'Nada autônomo - só consigo estudar quando há orientação direta',
                    '2',
                    '3',
                    '4',
                    'Muito autônomo - sou totalmente capaz de estudar e aprender sem depender de orientações externas',
                ],
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'options' => [],
        ],
        [
            'question_key' => 'anamnese_q12_estrategias_aprender',
            'enunciado' => 'Quais estratégias você mais costuma usar para aprender?',
            'question_type' => 'multipla_escolha',
            'allows_multiple' => 1,
            'is_required' => 1,
            'question_order' => 12,
            'config_json' => null,
            'options' => [
                'Resumos',
                'Mapas mentais',
                'Vídeo aulas',
                'Exercícios práticos e teóricos',
                'Outro',
            ],
        ],
        [
            'question_key' => 'anamnese_q13_preferencia_conteudos',
            'enunciado' => 'Preferência por conteúdos.',
            'question_type' => 'multipla_escolha',
            'allows_multiple' => 0,
            'is_required' => 1,
            'question_order' => 13,
            'config_json' => null,
            'options' => ['Textuais', 'Visuais', 'Auditivos', 'Interativos'],
        ],
        [
            'question_key' => 'anamnese_q14_experiencia_recursos',
            'enunciado' => 'Experiência prévia com mapas mentais, resumos, simuladores, quizzes.',
            'question_type' => 'intensidade_1_5',
            'allows_multiple' => 0,
            'is_required' => 1,
            'question_order' => 14,
            'config_json' => json_encode([
                'escala' => ['Nenhuma experiência', '2', '3', '4', 'Grande experiência'],
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'options' => [],
        ],
        [
            'question_key' => 'anamnese_q15_motivacao',
            'enunciado' => 'Motivação intrínseca (aprender por interesse) x extrínseca (nota, diploma).',
            'question_type' => 'intensidade_1_5',
            'allows_multiple' => 0,
            'is_required' => 1,
            'question_order' => 15,
            'config_json' => json_encode([
                'escala' => [
                    'Motivação intrínseca muito menor que a extrínseca',
                    '2',
                    '3',
                    '4',
                    'Motivação intrínseca muito maior que a extrínseca',
                ],
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'options' => [],
        ],
        [
            'question_key' => 'anamnese_q16_areas_dificuldade',
            'enunciado' => 'Principais áreas de dificuldade (ex.: cálculo, programação, interpretação de texto).',
            'question_type' => 'dissertativa',
            'allows_multiple' => 0,
            'is_required' => 1,
            'question_order' => 16,
            'config_json' => json_encode([
                'input' => 'textarea',
                'attributes' => [
                    'placeholder' => 'Descreva as áreas em que você sente mais dificuldade',
                    'maxlength' => 500,
                ],
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'options' => [],
        ],
        [
            'question_key' => 'anamnese_q17_ansiedade_provas',
            'enunciado' => 'Como você se sente antes de provas e trabalhos?',
            'question_type' => 'intensidade_1_5',
            'allows_multiple' => 0,
            'is_required' => 1,
            'question_order' => 17,
            'config_json' => json_encode([
                'escala' => ['Tranquilo(a), sem ansiedade', '2', '3', '4', 'Ansiedade extrema'],
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'options' => [],
        ],
        [
            'question_key' => 'anamnese_q18_obstaculos_ead',
            'enunciado' => 'Percepção sobre os maiores obstáculos no EaD (tempo, conteúdo, engajamento, apoio docente).',
            'question_type' => 'multipla_escolha',
            'allows_multiple' => 0,
            'is_required' => 1,
            'question_order' => 18,
            'config_json' => null,
            'options' => [
                'Tempo disponível para estudar',
                'Os conteúdos são complexos',
                'Não consigo engajar nas aulas',
                'Não percebo o apoio docente',
                'O apoio docente existe, mas não é o adequado para mim',
            ],
        ],
        [
            'question_key' => 'anamnese_q19_familiaridade_ava',
            'enunciado' => 'Nível de familiaridade com o Ambiente Virtual de Aprendizagem (AVA ou plataforma).',
            'question_type' => 'intensidade_1_5',
            'allows_multiple' => 0,
            'is_required' => 1,
            'question_order' => 19,
            'config_json' => json_encode([
                'escala' => ['Nenhuma familiaridade', '2', '3', '4', 'Total familiaridade'],
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'options' => [],
        ],
        [
            'question_key' => 'anamnese_q20_frequencia_acesso',
            'enunciado' => 'Frequência de acesso.',
            'question_type' => 'multipla_escolha',
            'allows_multiple' => 0,
            'is_required' => 1,
            'question_order' => 20,
            'config_json' => null,
            'options' => ['Diária', 'Semanal', 'Mensal', 'Eventual', 'Nenhuma'],
        ],
        [
            'question_key' => 'anamnese_q21_dispositivo_estudo',
            'enunciado' => 'Dispositivo mais usado por você para acessar aulas e estudar.',
            'question_type' => 'multipla_escolha',
            'allows_multiple' => 1,
            'is_required' => 1,
            'question_order' => 21,
            'config_json' => null,
            'options' => [
                'Computador / Notebook próprio',
                'Computador / Notebook da universidade',
                'Smartphone (Celular)',
                'Tablet',
                'Aparelho de televisão',
            ],
        ],
        [
            'question_key' => 'anamnese_q22_conforto_ia',
            'enunciado' => 'Qual o seu nível de conforto com ferramentas de IA.',
            'question_type' => 'intensidade_1_5',
            'allows_multiple' => 0,
            'is_required' => 1,
            'question_order' => 22,
            'config_json' => json_encode([
                'escala' => [
                    'Não tenho nenhuma prática com o uso das IAs',
                    '2',
                    '3',
                    '4',
                    'Uso todas as ferramentas que tenho à disposição',
                ],
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'options' => [],
        ],
        [
            'question_key' => 'anamnese_q23_dependencia_ia',
            'enunciado' => 'Qual seu nível de dependência destas ferramentas? (chatbots, geradores de resumos, IA generativa)',
            'question_type' => 'intensidade_1_5',
            'allows_multiple' => 0,
            'is_required' => 1,
            'question_order' => 23,
            'config_json' => json_encode([
                'escala' => [
                    'Não utilizo IA para nenhuma atividade',
                    '2',
                    '3',
                    '4',
                    'Preciso utilizar em todas as atividades que realizo, desde escrever uma mensagem até nas atividades e trabalhos acadêmicos',
                ],
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'options' => [],
        ],
        [
            'question_key' => 'anamnese_q24_como_ajudar',
            'enunciado' => 'De que forma você acha que o assistente pode te ajudar mais?',
            'question_type' => 'multipla_escolha',
            'allows_multiple' => 1,
            'is_required' => 1,
            'question_order' => 24,
            'config_json' => null,
            'options' => [
                'Explicações extras',
                'Recomendações de materiais',
                'Gestão de tempo',
                'Lembretes de prazos',
                'Roteiro de estudos',
            ],
        ],
        [
            'question_key' => 'anamnese_q25_forma_retorno',
            'enunciado' => 'Como você gostaria de receber o retorno do assistente?',
            'question_type' => 'multipla_escolha',
            'allows_multiple' => 1,
            'is_required' => 1,
            'question_order' => 25,
            'config_json' => null,
            'options' => [
                'Relatórios semanais',
                'Alertas rápidos',
                'Dashboards visuais',
                'Trilha de estudos dentro da matéria',
                'Resumos',
            ],
        ],
        [
            'question_key' => 'anamnese_q26_abertura_testes',
            'enunciado' => 'Grau de abertura para testes experimentais.',
            'question_type' => 'multipla_escolha',
            'allows_multiple' => 0,
            'is_required' => 1,
            'question_order' => 26,
            'config_json' => null,
            'options' => [
                'Usar protótipos',
                'Responder pesquisas rápidas',
                'Não quero testes',
            ],
        ],
        [
            'question_key' => 'anamnese_q27_autoavaliacao_desempenho',
            'enunciado' => 'Autoavaliação de desempenho antes: Até o momento, como você avalia o seu desempenho em disciplinas EaD ou digitais já cursadas?',
            'question_type' => 'intensidade_1_5',
            'allows_multiple' => 0,
            'is_required' => 0,
            'question_order' => 27,
            'config_json' => json_encode([
                'escala' => ['Muito baixo', '2', '3', '4', 'Excelente'],
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'options' => [],
        ],
        [
            'question_key' => 'anamnese_q28_proficiencia_digital',
            'enunciado' => 'Autoavaliação de proficiência digital.',
            'question_type' => 'intensidade_1_5',
            'allows_multiple' => 0,
            'is_required' => 1,
            'question_order' => 28,
            'config_json' => json_encode([
                'escala' => ['Iniciante', '2', '3', '4', 'Avançado'],
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'options' => [],
        ],
        [
            'question_key' => 'anamnese_q29_engajamento_atual',
            'enunciado' => 'Engajamento atual.',
            'question_type' => 'intensidade_1_5',
            'allows_multiple' => 0,
            'is_required' => 1,
            'question_order' => 29,
            'config_json' => json_encode([
                'escala' => ['Engajamento nenhum', '2', '3', '4', 'Engajamento forte'],
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'options' => [],
        ],
    ];
}

function upsertQuestions(PDO $pdo, array $questions): int
{
    $pdo->beginTransaction();

    try {
        $pdo->exec('UPDATE question_metrics_affects SET active = 0');
        $pdo->exec('UPDATE question_options SET active = 0');
        $pdo->exec('UPDATE questions SET active = 0, question_order = question_order + 1000');

        $selectStatement = $pdo->prepare('SELECT id FROM questions WHERE question_key = :question_key LIMIT 1');
        $updateStatement = $pdo->prepare(
            'UPDATE questions
             SET enunciado = :enunciado,
                 question_type = :question_type,
                 allows_multiple = :allows_multiple,
                 is_required = :is_required,
                 question_order = :question_order,
                 config_json = :config_json,
                 active = 1
             WHERE id = :id'
        );
        $insertStatement = $pdo->prepare(
            'INSERT INTO questions (
                question_key,
                enunciado,
                question_type,
                allows_multiple,
                is_required,
                question_order,
                config_json,
                active
             ) VALUES (
                :question_key,
                :enunciado,
                :question_type,
                :allows_multiple,
                :is_required,
                :question_order,
                :config_json,
                1
             )'
        );
        $deleteOptionsStatement = $pdo->prepare('DELETE FROM question_options WHERE question_id = :question_id');
        $deleteAffectsStatement = $pdo->prepare('DELETE FROM question_metrics_affects WHERE question_id = :question_id');
        $insertOptionStatement = $pdo->prepare(
            'INSERT INTO question_options (
                question_id,
                option_value,
                option_label,
                option_order,
                active
             ) VALUES (
                :question_id,
                :option_value,
                :option_label,
                :option_order,
                1
             )'
        );
        $finalOrderStatement = $pdo->prepare('UPDATE questions SET question_order = :question_order WHERE id = :id');

        $importedIds = [];
        foreach ($questions as $index => $question) {
            $temporaryOrder = 5000 + $index;

            $selectStatement->execute([
                'question_key' => $question['question_key'],
            ]);
            $existingId = $selectStatement->fetchColumn();

            if ($existingId !== false) {
                $updateStatement->execute([
                    'enunciado' => $question['enunciado'],
                    'question_type' => $question['question_type'],
                    'allows_multiple' => $question['allows_multiple'],
                    'is_required' => $question['is_required'],
                    'question_order' => $temporaryOrder,
                    'config_json' => $question['config_json'],
                    'id' => (int) $existingId,
                ]);
                $questionId = (int) $existingId;
            } else {
                $insertStatement->execute([
                    'question_key' => $question['question_key'],
                    'enunciado' => $question['enunciado'],
                    'question_type' => $question['question_type'],
                    'allows_multiple' => $question['allows_multiple'],
                    'is_required' => $question['is_required'],
                    'question_order' => $temporaryOrder,
                    'config_json' => $question['config_json'],
                ]);
                $questionId = (int) $pdo->lastInsertId();
            }

            $deleteOptionsStatement->execute(['question_id' => $questionId]);
            $deleteAffectsStatement->execute(['question_id' => $questionId]);

            foreach (array_values($question['options']) as $optionIndex => $optionLabel) {
                $insertOptionStatement->execute([
                    'question_id' => $questionId,
                    'option_value' => normalizeOptionValue((string) $optionLabel),
                    'option_label' => $optionLabel,
                    'option_order' => $optionIndex + 1,
                ]);
            }

            $importedIds[] = $questionId;
        }

        foreach ($importedIds as $index => $questionId) {
            $finalOrderStatement->execute([
                'question_order' => $index + 1,
                'id' => $questionId,
            ]);
        }

        $pdo->commit();
        return count($importedIds);
    } catch (Throwable $exception) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        throw $exception;
    }
}

try {
    $pdo = connectToDatabase();
    $count = upsertQuestions($pdo, questionDefinitions());
    (new FormQuestionSyncService())->syncActiveQuestionsToFile();

    fwrite(STDOUT, sprintf("Importacao concluida com sucesso. %d perguntas ativas do Google Forms foram carregadas.\n", $count));
    exit(0);
} catch (Throwable $exception) {
    fwrite(STDERR, $exception->getMessage() . PHP_EOL);
    exit(1);
}
