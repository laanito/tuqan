-- 0036-documentacion-contenido.sql
-- Stage 9.26: Sample text content for existing documents to demo content editor.
-- Ensure table exists (linked to documentos).

CREATE TABLE IF NOT EXISTS contenido_texto (
  id INTEGER PRIMARY KEY,
  contenido TEXT
);

INSERT INTO contenido_texto (id, contenido)
VALUES
  (1, 'Este es el contenido de texto del Manual de Calidad. Incluye políticas y procedimientos básicos.'),
  (2, 'Procedimiento para el control de documentos: versión, revisión y aprobación.')
ON CONFLICT (id) DO UPDATE SET contenido = EXCLUDED.contenido;

INSERT INTO data_patches (filename, applied_at)
VALUES ('0036-documentacion-contenido.sql', NOW())
ON CONFLICT (filename) DO NOTHING;
