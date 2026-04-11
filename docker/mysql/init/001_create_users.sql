CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(190) DEFAULT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(150) DEFAULT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO users (username, email, password_hash, full_name, is_active)
SELECT
    'admin',
    'admin@example.com',
    '$2y$10$54481VBo/89wEaxUZklaBuN9tp/..GE2/WieHedD8HnuftyptiFte',
    'Administrador',
    1
WHERE NOT EXISTS (
    SELECT 1
    FROM users
    WHERE username = 'admin'
);
