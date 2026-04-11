CREATE TABLE IF NOT EXISTS question (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    question_order INT NOT NULL,
    active TINYINT(1) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS question_option (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    fk_question INT NOT NULL,
    active TINYINT(1) NOT NULL,
    CONSTRAINT fk_question_option_question
        FOREIGN KEY (fk_question) REFERENCES question(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS metrics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fk_user INT UNSIGNED NOT NULL,
    risk_score INT NOT NULL,
    general_ready_score INT NOT NULL,
    mathematical_base_score INT NOT NULL,
    score_autonomy INT,
    active TINYINT(1) NOT NULL DEFAULT 1,
    CONSTRAINT uq_metrics_user UNIQUE (fk_user),
    CONSTRAINT fk_metrics_user
        FOREIGN KEY (fk_user) REFERENCES users(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;
