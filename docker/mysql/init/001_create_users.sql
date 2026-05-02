CREATE TABLE IF NOT EXISTS roles (
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_roles_name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS users (
    id INT NOT NULL AUTO_INCREMENT,
    email VARCHAR(190) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(150) DEFAULT NULL,
    role_id INT NOT NULL,
    session_id VARCHAR(128) DEFAULT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    reset_required TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_users_email (email),
    UNIQUE KEY uq_users_session_id (session_id),
    CONSTRAINT fk_users_role
        FOREIGN KEY (role_id) REFERENCES roles(id)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS metrics (
    id INT NOT NULL AUTO_INCREMENT,
    metric_key VARCHAR(100) NOT NULL,
    name VARCHAR(150) NOT NULL,
    description TEXT DEFAULT NULL,
    active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_metrics_metric_key (metric_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS user_metrics (
    id INT NOT NULL AUTO_INCREMENT,
    user_id INT NOT NULL,
    metric_id INT NOT NULL,
    score DECIMAL(10,4) NOT NULL DEFAULT 0.0000,
    calculated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_user_metrics_user_metric (user_id, metric_id),
    CONSTRAINT fk_user_metrics_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT fk_user_metrics_metric
        FOREIGN KEY (metric_id) REFERENCES metrics(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS questions (
    id INT NOT NULL AUTO_INCREMENT,
    question_key VARCHAR(100) NOT NULL,
    enunciado TEXT NOT NULL,
    question_type VARCHAR(50) NOT NULL,
    allows_multiple TINYINT(1) NOT NULL DEFAULT 0,
    is_required TINYINT(1) NOT NULL DEFAULT 0,
    question_order INT NOT NULL,
    config_json JSON DEFAULT NULL,
    active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_questions_question_key (question_key),
    UNIQUE KEY uq_questions_question_order (question_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS question_options (
    id INT NOT NULL AUTO_INCREMENT,
    question_id INT NOT NULL,
    option_value VARCHAR(100) NOT NULL,
    option_label VARCHAR(255) NOT NULL,
    option_order INT NOT NULL,
    active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_question_option_order (question_id, option_order),
    UNIQUE KEY uq_question_option_value (question_id, option_value),
    CONSTRAINT fk_question_options_question
        FOREIGN KEY (question_id) REFERENCES questions(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS form_submissions (
    id INT NOT NULL AUTO_INCREMENT,
    user_id INT NOT NULL,
    submitted_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    status VARCHAR(50) NOT NULL DEFAULT 'submitted',
    PRIMARY KEY (id),
    KEY idx_form_submissions_user_id (user_id),
    CONSTRAINT fk_form_submissions_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS question_answers (
    id INT NOT NULL AUTO_INCREMENT,
    submission_id INT NOT NULL,
    user_id INT NOT NULL,
    question_id INT NOT NULL,
    answer_text TEXT DEFAULT NULL,
    answer_value VARCHAR(255) DEFAULT NULL,
    answer_json JSON DEFAULT NULL,
    answered_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_question_answers_submission_id (submission_id),
    KEY idx_question_answers_user_id (user_id),
    KEY idx_question_answers_question_id (question_id),
    CONSTRAINT fk_question_answers_submission
        FOREIGN KEY (submission_id) REFERENCES form_submissions(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT fk_question_answers_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT fk_question_answers_question
        FOREIGN KEY (question_id) REFERENCES questions(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS question_metrics_affects (
    id INT NOT NULL AUTO_INCREMENT,
    question_id INT NOT NULL,
    question_option_id INT DEFAULT NULL,
    metric_id INT NOT NULL,
    weight DECIMAL(10,4) NOT NULL DEFAULT 1.0000,
    impact_type VARCHAR(50) NOT NULL DEFAULT 'sum',
    active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_question_metric_affect (question_id, question_option_id, metric_id),
    CONSTRAINT fk_question_metrics_affects_question
        FOREIGN KEY (question_id) REFERENCES questions(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT fk_question_metrics_affects_option
        FOREIGN KEY (question_option_id) REFERENCES question_options(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT fk_question_metrics_affects_metric
        FOREIGN KEY (metric_id) REFERENCES metrics(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS videos (
    id INT NOT NULL AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    url VARCHAR(500) NOT NULL,
    platform VARCHAR(100) NOT NULL,
    duration_minutes INT NOT NULL,
    topic VARCHAR(255) NOT NULL,
    language VARCHAR(100) NOT NULL,
    difficulty_level TINYINT NOT NULL,
    source VARCHAR(255) NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS literature (
    id INT NOT NULL AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    type VARCHAR(100) NOT NULL,
    topic VARCHAR(150) NOT NULL,
    url VARCHAR(500) NOT NULL,
    authors VARCHAR(255) NOT NULL,
    publication_year YEAR NOT NULL,
    language VARCHAR(100) NOT NULL,
    level VARCHAR(100) NOT NULL,
    access VARCHAR(50) NOT NULL,
    format VARCHAR(50) NOT NULL,
    description TEXT NOT NULL,
    keywords VARCHAR(255) NOT NULL,
    citations INT NOT NULL,
    institution VARCHAR(255) NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS disciplines (
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    syllabus TEXT NOT NULL,
    prerequisites VARCHAR(255) NOT NULL,
    workload_hours INT NOT NULL,
    semester TINYINT NOT NULL,
    difficulty_level TINYINT NOT NULL,
    department VARCHAR(150) NOT NULL,
    credits TINYINT NOT NULL,
    acquired_skills TEXT NOT NULL,
    tools_used VARCHAR(255) NOT NULL,
    assessment_methods VARCHAR(255) NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO roles (name, active)
SELECT 'admin', 1
WHERE NOT EXISTS (
    SELECT 1 FROM roles WHERE name = 'admin'
);

INSERT INTO roles (name, active)
SELECT 'user', 1
WHERE NOT EXISTS (
    SELECT 1 FROM roles WHERE name = 'user'
);

INSERT INTO metrics (metric_key, name, description, active)
SELECT 'risk_score', 'Risk Score', 'Indice agregado de risco academico.', 1
WHERE NOT EXISTS (
    SELECT 1 FROM metrics WHERE metric_key = 'risk_score'
);

INSERT INTO metrics (metric_key, name, description, active)
SELECT 'general_readiness_score', 'General Readiness Score', 'Prontidao geral para trilhas e componentes.', 1
WHERE NOT EXISTS (
    SELECT 1 FROM metrics WHERE metric_key = 'general_readiness_score'
);

INSERT INTO metrics (metric_key, name, description, active)
SELECT 'mathematical_foundation_score', 'Mathematical Foundation Score', 'Base matematica declarada e inferida.', 1
WHERE NOT EXISTS (
    SELECT 1 FROM metrics WHERE metric_key = 'mathematical_foundation_score'
);

INSERT INTO metrics (metric_key, name, description, active)
SELECT 'autonomy_score', 'Autonomy Score', 'Nivel de autonomia do estudante.', 1
WHERE NOT EXISTS (
    SELECT 1 FROM metrics WHERE metric_key = 'autonomy_score'
);

INSERT INTO users (email, password_hash, full_name, role_id, session_id, is_active, reset_required)
SELECT
    'vinisilvabariane10@gmail.com',
    '$2y$10$54481VBo/89wEaxUZklaBuN9tp/..GE2/WieHedD8HnuftyptiFte',
    'Administrador',
    roles.id,
    NULL,
    1,
    0
FROM roles
WHERE roles.name = 'admin'
  AND NOT EXISTS (
      SELECT 1 FROM users WHERE email = 'vinisilvabariane10@gmail.com'
  );
