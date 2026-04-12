CREATE TABLE IF NOT EXISTS questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    question_order INT NOT NULL,
    active TINYINT(1) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS question_options (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    question_id INT NOT NULL,
    active TINYINT(1) NOT NULL,
    CONSTRAINT fk_question_options_question
        FOREIGN KEY (question_id) REFERENCES questions(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS metrics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    risk_score INT NOT NULL,
    general_readiness_score INT NOT NULL,
    mathematical_foundation_score INT NOT NULL,
    autonomy_score INT,
    active TINYINT(1) NOT NULL DEFAULT 1,
    CONSTRAINT uq_metrics_user UNIQUE (user_id),
    CONSTRAINT fk_metrics_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;
