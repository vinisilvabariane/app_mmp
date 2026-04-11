CREATE TABLE IF NOT EXISTS videos (
    id INT NOT NULL AUTO_INCREMENT,
    titulo VARCHAR(255) NOT NULL,
    url VARCHAR(500) NOT NULL,
    plataforma VARCHAR(100) NOT NULL,
    duracao_minutos INT NOT NULL,
    topico VARCHAR(255) NOT NULL,
    idioma VARCHAR(100) NOT NULL,
    nivel_dificuldade TINYINT NOT NULL,
    fonte VARCHAR(255) NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS literatura (
    id INT NOT NULL AUTO_INCREMENT,
    titulo VARCHAR(255) NOT NULL,
    tipo VARCHAR(100) NOT NULL,
    topico VARCHAR(150) NOT NULL,
    url VARCHAR(500) NOT NULL,
    autores VARCHAR(255) NOT NULL,
    data_publicacao YEAR NOT NULL,
    idioma VARCHAR(100) NOT NULL,
    nivel VARCHAR(100) NOT NULL,
    acesso VARCHAR(50) NOT NULL,
    formato VARCHAR(50) NOT NULL,
    descricao TEXT NOT NULL,
    palavras_chave VARCHAR(255) NOT NULL,
    citacoes INT NOT NULL,
    instituicao VARCHAR(255) NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS disciplinas (
    id INT NOT NULL AUTO_INCREMENT,
    disciplina VARCHAR(255) NOT NULL,
    ementa TEXT NOT NULL,
    pre_requisitos VARCHAR(255) NOT NULL,
    horas INT NOT NULL,
    semestre TINYINT NOT NULL,
    dificuldade TINYINT NOT NULL,
    departamento VARCHAR(150) NOT NULL,
    creditos TINYINT NOT NULL,
    habilidades_adquiridas TEXT NOT NULL,
    ferramentas_usadas VARCHAR(255) NOT NULL,
    metodos_avaliacao VARCHAR(255) NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
