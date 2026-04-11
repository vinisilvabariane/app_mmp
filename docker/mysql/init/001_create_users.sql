CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(190) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(150) DEFAULT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    reset_required TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO users (email, password_hash, full_name, is_active, reset_required)
SELECT
    'vinisilvabariane10@gmail.com',
    '$2y$10$54481VBo/89wEaxUZklaBuN9tp/..GE2/WieHedD8HnuftyptiFte',
    'Administrador',
    1,
    0
WHERE NOT EXISTS (
    SELECT 1
    FROM users
    WHERE email = 'vinisilvabariane10@gmail.com'
);
