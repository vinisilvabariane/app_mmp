window.FORM_QUESTION_DEFINITIONS = [
    {
        id: 'q1',
        enunciado: 'Qual campo do conhecimento gostaria de estudar?',
        tipo: 'multipla_escolha',
        obrigatoria: true,
        multipla: false,
        opcoes: [
            { value: 'matematica', label: 'Matematica' },
            { value: 'portugues', label: 'Portugues' },
            { value: 'ingles', label: 'Ingles' },
            { value: 'geografia', label: 'Geografia' },
            { value: 'fisica', label: 'Fisica' },
            { value: 'natureza', label: 'Ciencias da Natureza' }
        ]
    },
    {
        id: 'q2',
        enunciado: 'Qual o seu tema de interesse dentro do campo do conhecimento escolhido?',
        tipo: 'dissertativa',
        obrigatoria: true,
        input: 'textarea'
    },
    {
        id: 'q3',
        enunciado: 'No ensino medio, como voce avalia sua base no campo do conhecimento escolhido?',
        tipo: 'intensidade_1_5',
        obrigatoria: true,
        escala: ['1 Muito fraca', '2', '3', '4', '5 Excelente']
    },
    {
        id: 'q4',
        enunciado: 'Quais conteudos do ensino medio voce sente mais dificuldade hoje?',
        tipo: 'dissertativa',
        obrigatoria: true,
        input: 'textarea'
    },
    {
        id: 'q5',
        enunciado: 'Voce ja teve contato com o tema escolhido antes da faculdade (ex.: cursinho, livro, videos)?',
        tipo: 'intensidade_1_5',
        obrigatoria: true,
        escala: ['1 Nenhum', '2', '3', '4', '5 Bastante']
    },
    {
        id: 'q6',
        enunciado: 'No inicio de uma disciplina desse campo do conhecimento, o que mais te preocupa?',
        tipo: 'dissertativa',
        obrigatoria: true,
        input: 'textarea'
    },
    {
        id: 'q7',
        enunciado: 'Voce fez o ensino medio ou tecnico em instituicao publica ou privada?',
        tipo: 'multipla_escolha',
        obrigatoria: true,
        multipla: false,
        opcoes: [
            { value: 'publica', label: 'Publica' },
            { value: 'privada', label: 'Privada' }
        ]
    },
    {
        id: 'q8',
        enunciado: 'Qual o seu curso (graduacao)?',
        tipo: 'dissertativa',
        obrigatoria: true,
        input: 'text'
    },
    {
        id: 'q9',
        enunciado: 'Qual semestre voce esta cursando?',
        tipo: 'multipla_escolha',
        obrigatoria: true,
        multipla: false,
        opcoes: Array.from({ length: 10 }, (_, index) => {
            const value = String(index + 1)
            return { value, label: value }
        })
    },
    {
        id: 'q10',
        enunciado: 'Qual o campus ou polo em que estuda?',
        tipo: 'dissertativa',
        obrigatoria: false,
        input: 'text'
    },
    {
        id: 'q11',
        enunciado: 'Componentes curriculares EaD em andamento (ou ja cursadas).',
        tipo: 'dissertativa',
        obrigatoria: false,
        input: 'textarea'
    },
    {
        id: 'q12',
        enunciado: 'Coeficiente de rendimento (CR) ou media atual. Utilize numeros de 0 a 10, com 3 casas decimais.',
        tipo: 'dissertativa',
        obrigatoria: false,
        input: 'number',
        attributes: {
            step: '0.001',
            min: '0',
            max: '10'
        }
    },
    {
        id: 'q13',
        enunciado: 'Em media, quantas horas por semana voce consegue dedicar aos estudos fora do horario das aulas presenciais?',
        tipo: 'multipla_escolha',
        obrigatoria: true,
        multipla: false,
        opcoes: [
            { value: '2', label: 'Ate 2h' },
            { value: '4', label: '2-4h' },
            { value: '6', label: '4-6h' },
            { value: '8', label: '6-8h' },
            { value: '10', label: '8-10h' },
            { value: '10+', label: '+10h' }
        ]
    },
    {
        id: 'q14',
        enunciado: 'Qual o seu local preferencial de estudo?',
        tipo: 'multipla_escolha',
        obrigatoria: false,
        multipla: true,
        opcoes: [
            { value: 'casa', label: 'Casa' },
            { value: 'biblioteca', label: 'Biblioteca' },
            { value: 'lab', label: 'Laboratorio' },
            { value: 'outro', label: 'Outro' }
        ]
    },
    {
        id: 'q15',
        enunciado: 'Em que medida voce sente que consegue organizar e manter seus estudos sem precisar sempre da orientacao do professor ou colegas? Avalie sua capacidade de estudar de forma independente.',
        tipo: 'intensidade_1_5',
        obrigatoria: true
    },
    {
        id: 'q16',
        enunciado: 'Quais estrategias voce mais costuma usar para aprender?',
        tipo: 'multipla_escolha',
        obrigatoria: false,
        multipla: true,
        opcoes: [
            { value: 'resumos', label: 'Resumos' },
            { value: 'mapas', label: 'Mapas mentais' },
            { value: 'video', label: 'Videos' },
            { value: 'exercicios', label: 'Exercicios' }
        ]
    },
    {
        id: 'q17',
        enunciado: 'Preferencia por conteudos.',
        tipo: 'multipla_escolha',
        obrigatoria: false,
        multipla: true,
        opcoes: [
            { value: 'textual', label: 'Textual' },
            { value: 'visual', label: 'Visual' },
            { value: 'auditivo', label: 'Auditivo' },
            { value: 'interativo', label: 'Interativo' }
        ]
    },
    {
        id: 'q18',
        enunciado: 'Experiencia previa com mapas mentais, resumos, simuladores e quizzes.',
        tipo: 'intensidade_1_5',
        obrigatoria: true
    },
    {
        id: 'q19',
        enunciado: 'Motivacao intrinseca (aprender por interesse) x extrinseca (nota, diploma).',
        tipo: 'intensidade_1_5',
        obrigatoria: true
    },
    {
        id: 'q20',
        enunciado: 'Principais areas de dificuldade (ex.: calculo, programacao, interpretacao de texto).',
        tipo: 'dissertativa',
        obrigatoria: false,
        input: 'textarea'
    },
    {
        id: 'q21',
        enunciado: 'Quantas disciplinas EaD (ou digitais) voce ja cursou no seu curso ate agora?',
        tipo: 'dissertativa',
        obrigatoria: false,
        input: 'number',
        attributes: {
            min: '0'
        }
    },
    {
        id: 'q22',
        enunciado: 'Em quantas dessas disciplinas voce teve reprovacao ou trancamento?',
        tipo: 'intensidade_1_5',
        obrigatoria: true
    },
    {
        id: 'q23',
        enunciado: 'Como voce se sente antes de provas e trabalhos?',
        tipo: 'intensidade_1_5',
        obrigatoria: true
    },
    {
        id: 'q24',
        enunciado: 'Percepcao sobre os maiores obstaculos no EaD?',
        tipo: 'multipla_escolha',
        obrigatoria: false,
        multipla: true,
        opcoes: [
            { value: 'tempo', label: 'Tempo disponivel para estudar.' },
            { value: 'conteudo', label: 'Os conteudos sao complexos.' },
            { value: 'engajamento', label: 'Nao consigo engajar nas aulas.' },
            { value: 'apoio', label: 'Nao percebo o apoio docente.' },
            { value: 'apoio_docente', label: 'O apoio docente existe, mas nao e o adequado para mim.' }
        ]
    },
    {
        id: 'q25',
        enunciado: 'Nivel de familiaridade com o Ambiente Virtual de Aprendizagem (AVA ou plataforma).',
        tipo: 'intensidade_1_5',
        obrigatoria: true
    },
    {
        id: 'q26',
        enunciado: 'Frequencia de acesso.',
        tipo: 'multipla_escolha',
        obrigatoria: false,
        multipla: false,
        opcoes: [
            { value: 'diaria', label: 'Diaria' },
            { value: 'semanal', label: 'Semanal' },
            { value: 'mensal', label: 'Mensal' },
            { value: 'eventual', label: 'Eventual' },
            { value: 'nenhuma', label: 'Nenhuma' }
        ]
    },
    {
        id: 'q27',
        enunciado: 'Dispositivo mais usado por voce para acessar aulas e estudar.',
        tipo: 'multipla_escolha',
        obrigatoria: false,
        multipla: true,
        opcoes: [
            { value: 'pc_proprio', label: 'Computador / Notebook proprio' },
            { value: 'pc_faculdade', label: 'Computador / Notebook da faculdade' },
            { value: 'celular', label: 'Celular' },
            { value: 'tablet', label: 'Tablet' }
        ]
    },
    {
        id: 'q28',
        enunciado: 'Qual o seu nivel de conforto com ferramentas de IA, como chatbots, geradores de resumos e IA generativa?',
        tipo: 'intensidade_1_5',
        obrigatoria: true
    },
    {
        id: 'q29',
        enunciado: 'Qual seu nivel de dependencia dessas ferramentas de IA?',
        tipo: 'intensidade_1_5',
        obrigatoria: true
    },
    {
        id: 'q30',
        enunciado: 'De que forma voce acha que o assistente pode te ajudar mais?',
        tipo: 'multipla_escolha',
        obrigatoria: false,
        multipla: true,
        opcoes: [
            { value: 'explicacao', label: 'Explicacoes extras' },
            { value: 'recomendacao', label: 'Recomendacoes de materiais' },
            { value: 'tempo', label: 'Gestao de tempo' },
            { value: 'prazo', label: 'Lembretes de prazos' },
            { value: 'roteiro', label: 'Roteiro de estudos' }
        ]
    },
    {
        id: 'q31',
        enunciado: 'Como voce gostaria de receber o retorno do assistente?',
        tipo: 'multipla_escolha',
        obrigatoria: false,
        multipla: true,
        opcoes: [
            { value: 'relatorio', label: 'Relatorios semanais' },
            { value: 'alertas', label: 'Alertas rapidos' },
            { value: 'dashboard', label: 'Dashboards visuais' }
        ]
    },
    {
        id: 'q32',
        enunciado: 'Grau de abertura para testes experimentais.',
        tipo: 'multipla_escolha',
        obrigatoria: false,
        multipla: true,
        opcoes: [
            { value: 'prototipos', label: 'Usar prototipos' },
            { value: 'pesquisas', label: 'Responder pesquisas rapidas' }
        ]
    },
    {
        id: 'q33',
        enunciado: 'Ate o momento, como voce avalia o seu desempenho em disciplinas EaD ou digitais ja cursadas?',
        tipo: 'intensidade_1_5',
        obrigatoria: true
    },
    {
        id: 'q34',
        enunciado: 'Considere o quanto voce se sente capaz de utilizar recursos digitais para estudar, realizar tarefas e resolver problemas academicos.',
        tipo: 'intensidade_1_5',
        obrigatoria: true
    },
    {
        id: 'q35',
        enunciado: 'Pense em quanto voce tem se envolvido com as atividades do curso: participacao nas aulas, realizacao de tarefas, interacao com colegas e professores e interesse em aprender.',
        tipo: 'intensidade_1_5',
        obrigatoria: true
    }
]
