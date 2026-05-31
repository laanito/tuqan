-- ============================================================================
-- 0003-usuarios-contact-info.sql
-- Extend usuarios table with apellido + email for better user cards and
-- the upcoming user management module.
-- Safe for repeated application.
-- ============================================================================

-- Add columns if they don't exist (Postgres 9.6+ supports IF NOT EXISTS for ADD COLUMN)
ALTER TABLE usuarios
    ADD COLUMN IF NOT EXISTS apellido CHARACTER VARYING(128);

ALTER TABLE usuarios
    ADD COLUMN IF NOT EXISTS email CHARACTER VARYING(128);

-- Backfill the existing demo admin user with sensible sample data
-- (only if it still has the old placeholder values)
UPDATE usuarios
SET
    nombre   = 'Administrador',
    apellido = 'Demo',
    email    = 'admin@demo.local'
WHERE id = 1
  AND (apellido IS NULL OR apellido = '' OR email IS NULL OR email = '');

-- Note: Future user management forms will allow editing these fields properly.
