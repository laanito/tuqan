-- 0049-documentacion-binario.sql
-- Stage 9.39: tipos_fichero + contenido_binario for document file attachments.
-- Modern shell stores payload in BYTEA (legacy also has archivo_oid LO; deferred).

CREATE TABLE IF NOT EXISTS tipos_fichero (
  id        SERIAL PRIMARY KEY,
  nombre    CHARACTER VARYING(32),
  extension CHARACTER VARYING(8),
  mime      CHARACTER VARYING(128)
);

-- Live DBs may have created tipos_fichero with mime VARCHAR(64) on a partial run
ALTER TABLE tipos_fichero ALTER COLUMN mime TYPE CHARACTER VARYING(128);

CREATE TABLE IF NOT EXISTS contenido_binario (
  id           INTEGER PRIMARY KEY,
  tipo_fichero INTEGER REFERENCES tipos_fichero (id),
  size         BIGINT,
  contenido    BYTEA,
  archivo_oid  OID,
  nombre_archivo CHARACTER VARYING(255)
);

-- Seed common types (idempotent by extension)
INSERT INTO tipos_fichero (nombre, extension, mime)
SELECT 'PDF', 'pdf', 'application/pdf'
WHERE NOT EXISTS (SELECT 1 FROM tipos_fichero WHERE lower(trim(extension)) = 'pdf');

INSERT INTO tipos_fichero (nombre, extension, mime)
SELECT 'Texto', 'txt', 'text/plain'
WHERE NOT EXISTS (SELECT 1 FROM tipos_fichero WHERE lower(trim(extension)) = 'txt');

INSERT INTO tipos_fichero (nombre, extension, mime)
SELECT 'PNG', 'png', 'image/png'
WHERE NOT EXISTS (SELECT 1 FROM tipos_fichero WHERE lower(trim(extension)) = 'png');

INSERT INTO tipos_fichero (nombre, extension, mime)
SELECT 'JPEG', 'jpg', 'image/jpeg'
WHERE NOT EXISTS (SELECT 1 FROM tipos_fichero WHERE lower(trim(extension)) = 'jpg');

INSERT INTO tipos_fichero (nombre, extension, mime)
SELECT 'Word', 'docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
WHERE NOT EXISTS (SELECT 1 FROM tipos_fichero WHERE lower(trim(extension)) = 'docx');

-- Demo binary for documento 1 (plain text payload as BYTEA, tipo txt)
INSERT INTO contenido_binario (id, tipo_fichero, size, contenido, nombre_archivo)
SELECT 1,
       (SELECT id FROM tipos_fichero WHERE lower(trim(extension)) = 'txt' LIMIT 1),
       octet_length(convert_to('Documento adjunto demo Stage 9.39 — Manual de Calidad (MAN-001).' || E'\n', 'UTF8')),
       convert_to('Documento adjunto demo Stage 9.39 — Manual de Calidad (MAN-001).' || E'\n', 'UTF8'),
       'man-001-demo.txt'
WHERE EXISTS (SELECT 1 FROM documentos WHERE id = 1)
  AND NOT EXISTS (SELECT 1 FROM contenido_binario WHERE id = 1);

INSERT INTO data_patches (filename, applied_at)
VALUES ('0049-documentacion-binario.sql', NOW())
ON CONFLICT (filename) DO NOTHING;
