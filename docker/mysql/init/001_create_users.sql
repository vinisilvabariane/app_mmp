CREATE TABLE IF NOT EXISTS roles (
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    active TINYINT(1) NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS users (
    id INT NOT NULL AUTO_INCREMENT,
    email VARCHAR(190) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(150) DEFAULT NULL,
    role_id INT NOT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    reset_required TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY email (email),
    CONSTRAINT fk_users_role
        FOREIGN KEY (role_id) REFERENCES roles(id)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS user_roles (
    user_id INT NOT NULL,
    role_id INT NOT NULL,
    PRIMARY KEY (user_id, role_id),
    CONSTRAINT fk_user_roles_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT fk_user_roles_role
        FOREIGN KEY (role_id) REFERENCES roles(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO roles (name, active)
SELECT 'admin', 1
WHERE NOT EXISTS (
    SELECT 1
    FROM roles
    WHERE name = 'admin'
);

INSERT INTO roles (name, active)
SELECT 'user', 1
WHERE NOT EXISTS (
    SELECT 1
    FROM roles
    WHERE name = 'user'
);

INSERT INTO users (email, password_hash, full_name, role_id, is_active, reset_required)
SELECT
    'vinisilvabariane10@gmail.com',
    '$2y$10$54481VBo/89wEaxUZklaBuN9tp/..GE2/WieHedD8HnuftyptiFte',
    'Administrador',
    roles.id,
    1,
    0
FROM roles
WHERE roles.name = 'admin'
  AND NOT EXISTS (
      SELECT 1
      FROM users
      WHERE email = 'vinisilvabariane10@gmail.com'
  );

INSERT INTO user_roles (user_id, role_id)
SELECT users.id, roles.id
FROM users
INNER JOIN roles ON roles.id = users.role_id
WHERE users.email = 'vinisilvabariane10@gmail.com'
  AND NOT EXISTS (
      SELECT 1
      FROM user_roles
      WHERE user_id = users.id
        AND role_id = roles.id
  );
