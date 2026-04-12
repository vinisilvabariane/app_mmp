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
