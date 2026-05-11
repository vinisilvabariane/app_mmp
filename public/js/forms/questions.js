window.FORM_QUESTION_DEFINITIONS = [
    {
        "id": "anamnese_q01_base_matematica",
        "enunciado": "No ensino médio, como você avalia sua base em Matemática?",
        "tipo": "intensidade_1_5",
        "obrigatoria": true,
        "escala": [
            "Muito fraca",
            "2",
            "3",
            "4",
            "Excelente"
        ]
    },
    {
        "id": "anamnese_q02_dificuldades_medio",
        "enunciado": "Quais conteúdos do ensino médio você sente mais dificuldade hoje?",
        "tipo": "multipla_escolha",
        "obrigatoria": true,
        "multipla": true,
        "opcoes": [
            {
                "value": "funcoes_1o_e_2o_grau_exponenciais_logaritmos",
                "label": "Funções (1º e 2º grau, exponenciais, logaritmos)"
            },
            {
                "value": "trigonometria",
                "label": "Trigonometria"
            },
            {
                "value": "geometria_analitica",
                "label": "Geometria analítica"
            },
            {
                "value": "progressoes_pa_pg",
                "label": "Progressões (PA/PG)"
            },
            {
                "value": "polinomios",
                "label": "Polinômios"
            },
            {
                "value": "algebra",
                "label": "Álgebra"
            },
            {
                "value": "outro",
                "label": "Outro"
            }
        ]
    },
    {
        "id": "anamnese_q03_contato_previo_calculo",
        "enunciado": "Você já teve contato com Cálculo antes da faculdade (ex.: cursinho, livro, vídeos)?",
        "tipo": "intensidade_1_5",
        "obrigatoria": true,
        "escala": [
            "Não, não vi nada sobre o assunto",
            "2",
            "3",
            "4",
            "Sim, bastante"
        ]
    },
    {
        "id": "anamnese_q04_preocupacao_calculo",
        "enunciado": "No início de uma disciplina de Cálculo, o que mais te preocupa?",
        "tipo": "multipla_escolha",
        "obrigatoria": true,
        "multipla": true,
        "opcoes": [
            {
                "value": "linguagem_e_simbolos_matematicos",
                "label": "Linguagem e símbolos matemáticos"
            },
            {
                "value": "volume_de_exercicios",
                "label": "Volume de exercícios"
            },
            {
                "value": "dificuldade_em_interpretar_problemas",
                "label": "Dificuldade em interpretar problemas"
            },
            {
                "value": "tempo_para_acompanhar_as_aulas",
                "label": "Tempo para acompanhar as aulas"
            },
            {
                "value": "outros_cite_na_proxima_pergunta",
                "label": "Outros (cite na próxima pergunta)"
            },
            {
                "value": "outro",
                "label": "Outro"
            }
        ]
    },
    {
        "id": "anamnese_q05_tipo_instituicao",
        "enunciado": "Você fez o ensino médio, ou técnico em instituição pública ou privada?",
        "tipo": "multipla_escolha",
        "obrigatoria": true,
        "multipla": false,
        "opcoes": [
            {
                "value": "publica",
                "label": "Pública"
            },
            {
                "value": "privada",
                "label": "Privada"
            }
        ]
    },
    {
        "id": "anamnese_q06_curso_graduacao",
        "enunciado": "Qual o seu curso (Graduação)?",
        "tipo": "dissertativa",
        "obrigatoria": true,
        "input": "text",
        "attributes": {
            "maxlength": 150,
            "placeholder": "Digite o nome do seu curso"
        }
    },
    {
        "id": "anamnese_q07_semestre",
        "enunciado": "Qual o semestre está cursando?",
        "tipo": "multipla_escolha",
        "obrigatoria": true,
        "multipla": false,
        "opcoes": [
            {
                "value": "1",
                "label": "1"
            },
            {
                "value": "2",
                "label": "2"
            },
            {
                "value": "3",
                "label": "3"
            },
            {
                "value": "4",
                "label": "4"
            },
            {
                "value": "5",
                "label": "5"
            },
            {
                "value": "6",
                "label": "6"
            },
            {
                "value": "7",
                "label": "7"
            },
            {
                "value": "8",
                "label": "8"
            },
            {
                "value": "9",
                "label": "9"
            },
            {
                "value": "10",
                "label": "10"
            }
        ]
    },
    {
        "id": "anamnese_q08_campus_polo",
        "enunciado": "Qual o campus ou polo em que estuda?",
        "tipo": "multipla_escolha",
        "obrigatoria": true,
        "multipla": false,
        "opcoes": [
            {
                "value": "campus_braganca_paulista",
                "label": "Câmpus Bragança Paulista"
            },
            {
                "value": "campus_campinas_cambui",
                "label": "Câmpus Campinas (Cambuí)"
            },
            {
                "value": "campus_campinas_swift",
                "label": "Câmpus Campinas (Swift)"
            },
            {
                "value": "campus_sagrado_coracao",
                "label": "Câmpus Sagrado Coração"
            },
            {
                "value": "campus_itatiba",
                "label": "Câmpus Itatiba"
            },
            {
                "value": "polo_cambui",
                "label": "Polo Cambuí"
            },
            {
                "value": "polo_pouso_alegre",
                "label": "Polo Pouso Alegre"
            },
            {
                "value": "polo_extrema",
                "label": "Polo Extrema"
            },
            {
                "value": "polo_itajuba",
                "label": "Polo Itajubá"
            },
            {
                "value": "polo_petropolis",
                "label": "Polo Petrópolis"
            },
            {
                "value": "polo_campinas_cambui",
                "label": "Polo Campinas (Cambuí)"
            },
            {
                "value": "polo_campinas_swift",
                "label": "Polo Campinas (Swift)"
            },
            {
                "value": "polo_braganca_paulista",
                "label": "Polo Bragança Paulista"
            },
            {
                "value": "polo_sao_jose_dos_campos",
                "label": "Polo São José dos Campos"
            },
            {
                "value": "polo_sao_bernardo_do_campo",
                "label": "Polo São Bernardo do Campo"
            },
            {
                "value": "polo_paulinia",
                "label": "Polo Paulínia"
            },
            {
                "value": "polo_jundiai",
                "label": "Polo Jundiaí"
            },
            {
                "value": "polo_mairipora",
                "label": "Polo Mairiporã"
            },
            {
                "value": "polo_atibaia",
                "label": "Polo Atibaia"
            },
            {
                "value": "polo_amparo",
                "label": "Polo Amparo"
            }
        ]
    },
    {
        "id": "anamnese_q09_horas_estudo",
        "enunciado": "Em média, quantas horas por semana você consegue dedicar aos estudos fora do horário das aulas presenciais?",
        "tipo": "multipla_escolha",
        "obrigatoria": true,
        "multipla": false,
        "opcoes": [
            {
                "value": "ate_2_horas",
                "label": "Até 2 horas"
            },
            {
                "value": "entre_2_horas_e_4_horas",
                "label": "Entre 2 horas e 4 horas"
            },
            {
                "value": "entre_4_horas_e_6_horas",
                "label": "Entre 4 horas e 6 horas"
            },
            {
                "value": "entre_6_horas_e_8_horas",
                "label": "Entre 6 horas e 8 horas"
            },
            {
                "value": "entre_8_horas_e_10_horas",
                "label": "Entre 8 horas e 10 horas"
            },
            {
                "value": "mais_de_10_horas",
                "label": "Mais de 10 horas"
            }
        ]
    },
    {
        "id": "anamnese_q10_local_estudo",
        "enunciado": "Qual o seu local preferencial de estudo?",
        "tipo": "multipla_escolha",
        "obrigatoria": true,
        "multipla": true,
        "opcoes": [
            {
                "value": "minha_casa",
                "label": "Minha casa"
            },
            {
                "value": "casa_de_um_parente_ou_amigo_a",
                "label": "Casa de um parente ou amigo(a)"
            },
            {
                "value": "biblioteca_da_universidade",
                "label": "Biblioteca da universidade"
            },
            {
                "value": "biblioteca_publica",
                "label": "Biblioteca pública"
            },
            {
                "value": "laboratorio",
                "label": "Laboratório"
            },
            {
                "value": "outro",
                "label": "Outro"
            }
        ]
    },
    {
        "id": "anamnese_q11_autonomia_estudos",
        "enunciado": "Em que medida você sente que consegue organizar e manter seus estudos sem precisar sempre da orientação do professor ou colegas?",
        "tipo": "intensidade_1_5",
        "obrigatoria": true,
        "escala": [
            "Nada autônomo - só consigo estudar quando há orientação direta",
            "2",
            "3",
            "4",
            "Muito autônomo - sou totalmente capaz de estudar e aprender sem depender de orientações externas"
        ]
    },
    {
        "id": "anamnese_q12_estrategias_aprender",
        "enunciado": "Quais estratégias você mais costuma usar para aprender?",
        "tipo": "multipla_escolha",
        "obrigatoria": true,
        "multipla": true,
        "opcoes": [
            {
                "value": "resumos",
                "label": "Resumos"
            },
            {
                "value": "mapas_mentais",
                "label": "Mapas mentais"
            },
            {
                "value": "video_aulas",
                "label": "Vídeo aulas"
            },
            {
                "value": "exercicios_praticos_e_teoricos",
                "label": "Exercícios práticos e teóricos"
            },
            {
                "value": "outro",
                "label": "Outro"
            }
        ]
    },
    {
        "id": "anamnese_q13_preferencia_conteudos",
        "enunciado": "Preferência por conteúdos.",
        "tipo": "multipla_escolha",
        "obrigatoria": true,
        "multipla": false,
        "opcoes": [
            {
                "value": "textuais",
                "label": "Textuais"
            },
            {
                "value": "visuais",
                "label": "Visuais"
            },
            {
                "value": "auditivos",
                "label": "Auditivos"
            },
            {
                "value": "interativos",
                "label": "Interativos"
            }
        ]
    },
    {
        "id": "anamnese_q14_experiencia_recursos",
        "enunciado": "Experiência prévia com mapas mentais, resumos, simuladores, quizzes.",
        "tipo": "intensidade_1_5",
        "obrigatoria": true,
        "escala": [
            "Nenhuma experiência",
            "2",
            "3",
            "4",
            "Grande experiência"
        ]
    },
    {
        "id": "anamnese_q15_motivacao",
        "enunciado": "Motivação intrínseca (aprender por interesse) x extrínseca (nota, diploma).",
        "tipo": "intensidade_1_5",
        "obrigatoria": true,
        "escala": [
            "Motivação intrínseca muito menor que a extrínseca",
            "2",
            "3",
            "4",
            "Motivação intrínseca muito maior que a extrínseca"
        ]
    },
    {
        "id": "anamnese_q16_areas_dificuldade",
        "enunciado": "Principais áreas de dificuldade (ex.: cálculo, programação, interpretação de texto).",
        "tipo": "dissertativa",
        "obrigatoria": true,
        "input": "textarea",
        "attributes": {
            "maxlength": 500,
            "placeholder": "Descreva as áreas em que você sente mais dificuldade"
        }
    },
    {
        "id": "anamnese_q17_ansiedade_provas",
        "enunciado": "Como você se sente antes de provas e trabalhos?",
        "tipo": "intensidade_1_5",
        "obrigatoria": true,
        "escala": [
            "Tranquilo(a), sem ansiedade",
            "2",
            "3",
            "4",
            "Ansiedade extrema"
        ]
    },
    {
        "id": "anamnese_q18_obstaculos_ead",
        "enunciado": "Percepção sobre os maiores obstáculos no EaD (tempo, conteúdo, engajamento, apoio docente).",
        "tipo": "multipla_escolha",
        "obrigatoria": true,
        "multipla": false,
        "opcoes": [
            {
                "value": "tempo_disponivel_para_estudar",
                "label": "Tempo disponível para estudar"
            },
            {
                "value": "os_conteudos_sao_complexos",
                "label": "Os conteúdos são complexos"
            },
            {
                "value": "nao_consigo_engajar_nas_aulas",
                "label": "Não consigo engajar nas aulas"
            },
            {
                "value": "nao_percebo_o_apoio_docente",
                "label": "Não percebo o apoio docente"
            },
            {
                "value": "o_apoio_docente_existe_mas_nao_e_o_adequado_para_mim",
                "label": "O apoio docente existe, mas não é o adequado para mim"
            }
        ]
    },
    {
        "id": "anamnese_q19_familiaridade_ava",
        "enunciado": "Nível de familiaridade com o Ambiente Virtual de Aprendizagem (AVA ou plataforma).",
        "tipo": "intensidade_1_5",
        "obrigatoria": true,
        "escala": [
            "Nenhuma familiaridade",
            "2",
            "3",
            "4",
            "Total familiaridade"
        ]
    },
    {
        "id": "anamnese_q20_frequencia_acesso",
        "enunciado": "Frequência de acesso.",
        "tipo": "multipla_escolha",
        "obrigatoria": true,
        "multipla": false,
        "opcoes": [
            {
                "value": "diaria",
                "label": "Diária"
            },
            {
                "value": "semanal",
                "label": "Semanal"
            },
            {
                "value": "mensal",
                "label": "Mensal"
            },
            {
                "value": "eventual",
                "label": "Eventual"
            },
            {
                "value": "nenhuma",
                "label": "Nenhuma"
            }
        ]
    },
    {
        "id": "anamnese_q21_dispositivo_estudo",
        "enunciado": "Dispositivo mais usado por você para acessar aulas e estudar.",
        "tipo": "multipla_escolha",
        "obrigatoria": true,
        "multipla": true,
        "opcoes": [
            {
                "value": "computador_notebook_proprio",
                "label": "Computador / Notebook próprio"
            },
            {
                "value": "computador_notebook_da_universidade",
                "label": "Computador / Notebook da universidade"
            },
            {
                "value": "smartphone_celular",
                "label": "Smartphone (Celular)"
            },
            {
                "value": "tablet",
                "label": "Tablet"
            },
            {
                "value": "aparelho_de_televisao",
                "label": "Aparelho de televisão"
            }
        ]
    },
    {
        "id": "anamnese_q22_conforto_ia",
        "enunciado": "Qual o seu nível de conforto com ferramentas de IA.",
        "tipo": "intensidade_1_5",
        "obrigatoria": true,
        "escala": [
            "Não tenho nenhuma prática com o uso das IAs",
            "2",
            "3",
            "4",
            "Uso todas as ferramentas que tenho à disposição"
        ]
    },
    {
        "id": "anamnese_q23_dependencia_ia",
        "enunciado": "Qual seu nível de dependência destas ferramentas? (chatbots, geradores de resumos, IA generativa)",
        "tipo": "intensidade_1_5",
        "obrigatoria": true,
        "escala": [
            "Não utilizo IA para nenhuma atividade",
            "2",
            "3",
            "4",
            "Preciso utilizar em todas as atividades que realizo, desde escrever uma mensagem até nas atividades e trabalhos acadêmicos"
        ]
    },
    {
        "id": "anamnese_q24_como_ajudar",
        "enunciado": "De que forma você acha que o assistente pode te ajudar mais?",
        "tipo": "multipla_escolha",
        "obrigatoria": true,
        "multipla": true,
        "opcoes": [
            {
                "value": "explicacoes_extras",
                "label": "Explicações extras"
            },
            {
                "value": "recomendacoes_de_materiais",
                "label": "Recomendações de materiais"
            },
            {
                "value": "gestao_de_tempo",
                "label": "Gestão de tempo"
            },
            {
                "value": "lembretes_de_prazos",
                "label": "Lembretes de prazos"
            },
            {
                "value": "roteiro_de_estudos",
                "label": "Roteiro de estudos"
            }
        ]
    },
    {
        "id": "anamnese_q25_forma_retorno",
        "enunciado": "Como você gostaria de receber o retorno do assistente?",
        "tipo": "multipla_escolha",
        "obrigatoria": true,
        "multipla": true,
        "opcoes": [
            {
                "value": "relatorios_semanais",
                "label": "Relatórios semanais"
            },
            {
                "value": "alertas_rapidos",
                "label": "Alertas rápidos"
            },
            {
                "value": "dashboards_visuais",
                "label": "Dashboards visuais"
            },
            {
                "value": "trilha_de_estudos_dentro_da_materia",
                "label": "Trilha de estudos dentro da matéria"
            },
            {
                "value": "resumos",
                "label": "Resumos"
            }
        ]
    },
    {
        "id": "anamnese_q26_abertura_testes",
        "enunciado": "Grau de abertura para testes experimentais.",
        "tipo": "multipla_escolha",
        "obrigatoria": true,
        "multipla": false,
        "opcoes": [
            {
                "value": "usar_prototipos",
                "label": "Usar protótipos"
            },
            {
                "value": "responder_pesquisas_rapidas",
                "label": "Responder pesquisas rápidas"
            },
            {
                "value": "nao_quero_testes",
                "label": "Não quero testes"
            }
        ]
    },
    {
        "id": "anamnese_q27_autoavaliacao_desempenho",
        "enunciado": "Autoavaliação de desempenho antes: Até o momento, como você avalia o seu desempenho em disciplinas EaD ou digitais já cursadas?",
        "tipo": "intensidade_1_5",
        "obrigatoria": false,
        "escala": [
            "Muito baixo",
            "2",
            "3",
            "4",
            "Excelente"
        ]
    },
    {
        "id": "anamnese_q28_proficiencia_digital",
        "enunciado": "Autoavaliação de proficiência digital.",
        "tipo": "intensidade_1_5",
        "obrigatoria": true,
        "escala": [
            "Iniciante",
            "2",
            "3",
            "4",
            "Avançado"
        ]
    },
    {
        "id": "anamnese_q29_engajamento_atual",
        "enunciado": "Engajamento atual.",
        "tipo": "intensidade_1_5",
        "obrigatoria": true,
        "escala": [
            "Engajamento nenhum",
            "2",
            "3",
            "4",
            "Engajamento forte"
        ]
    }
]
