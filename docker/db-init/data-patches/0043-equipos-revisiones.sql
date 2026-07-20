-- 0043-equipos-revisiones.sql
-- Stage 9.33: Equipos revisiones / mantenimientos records (legacy table name)
-- Menu accion equipos:revision:listado:ver

CREATE TABLE IF NOT EXISTS mantenimientos (
    id SERIAL PRIMARY KEY,
    equipo INTEGER,
    tipo VARCHAR(16),
    fecha_prevista DATE,
    fecha_realiza DATE NOT NULL DEFAULT CURRENT_DATE,
    comentarios TEXT NOT NULL DEFAULT '',
    motivos TEXT NOT NULL DEFAULT ''
);

INSERT INTO mantenimientos (equipo, tipo, fecha_prevista, fecha_realiza, comentarios, motivos)
SELECT 1, 'revision', '2025-03-01', '2025-03-05', 'Revisión trimestral de seguridad', 'Programa de mantenimiento preventivo'
WHERE NOT EXISTS (
    SELECT 1 FROM mantenimientos WHERE equipo = 1 AND comentarios LIKE 'Revisión trimestral%'
);

INSERT INTO mantenimientos (equipo, tipo, fecha_prevista, fecha_realiza, comentarios, motivos)
SELECT 1, 'preventivo', '2025-06-01', '2025-06-02', 'Cambio de filtros y lubricación', 'Cada 90 días'
WHERE NOT EXISTS (
    SELECT 1 FROM mantenimientos WHERE equipo = 1 AND comentarios LIKE 'Cambio de filtros%'
);

INSERT INTO mantenimientos (equipo, tipo, fecha_prevista, fecha_realiza, comentarios, motivos)
SELECT 2, 'revision', '2025-04-10', '2025-04-12', 'Inspección de sellos hidráulicos', 'Revisión semestral'
WHERE NOT EXISTS (
    SELECT 1 FROM mantenimientos WHERE equipo = 2 AND comentarios LIKE 'Inspección de sellos%'
);

INSERT INTO data_patches (filename, applied_at)
VALUES ('0043-equipos-revisiones.sql', NOW())
ON CONFLICT (filename) DO NOTHING;
