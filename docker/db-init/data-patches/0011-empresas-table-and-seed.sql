-- Stage 8.5: Minimal Empresas table (renamed concept from legacy "hospitales")
-- Added so the Aplicacion → Empresas menu entry has real backing data and modern pages work.
-- Structure kept close to the legacy hospitales definition for compatibility notes.
-- Idempotent.

CREATE TABLE IF NOT EXISTS empresas (
    id         SERIAL PRIMARY KEY,
    nombre     CHARACTER VARYING(128),
    activo     BOOLEAN DEFAULT TRUE,
    -- legacy had a "password" column (odd for master data); kept for reference only
    legacy_password CHARACTER VARYING(32)
);

-- Seed a couple of demo companies so the listing is not empty on first run
INSERT INTO empresas (nombre, activo) VALUES
    ('Acme Corporation', true),
    ('Beta Industries', true),
    ('Gamma Consulting', false)
ON CONFLICT DO NOTHING;

-- Note: legacy code still references the 'hospitales' table in many places.
-- We do not drop it. Modern Empresas module uses the new table.
-- Future migration chore can consolidate.