ALTER TABLE users
    ADD COLUMN IF NOT EXISTS session_id VARCHAR(128) DEFAULT NULL AFTER role_id;

SELECT 'session_id is available in users' AS info;
