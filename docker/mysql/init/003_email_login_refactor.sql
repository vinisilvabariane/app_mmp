SET @email_nullable = (
    SELECT IS_NULLABLE
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME = 'users'
      AND COLUMN_NAME = 'email'
    LIMIT 1
);

SET @email_alter_sql = IF(
    @email_nullable = 'YES',
    'ALTER TABLE users MODIFY COLUMN email VARCHAR(190) NOT NULL',
    'SELECT 1'
);

PREPARE email_stmt FROM @email_alter_sql;
EXECUTE email_stmt;
DEALLOCATE PREPARE email_stmt;

SET @username_exists = (
    SELECT COUNT(*)
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME = 'users'
      AND COLUMN_NAME = 'username'
);

SET @drop_username_sql = IF(
    @username_exists = 1,
    'ALTER TABLE users DROP COLUMN username',
    'SELECT 1'
);

PREPARE username_stmt FROM @drop_username_sql;
EXECUTE username_stmt;
DEALLOCATE PREPARE username_stmt;
