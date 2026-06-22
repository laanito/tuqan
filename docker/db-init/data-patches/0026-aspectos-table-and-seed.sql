-- 0026-aspectos-table-and-seed.sql
-- Stage 9.7: Aspectos Ambientales basic slice (first vertical for legacy 73 / maspectos).
-- Core `aspectos` table (nombre + multiple score fields + tipo_aspecto + activo + area/observaciones).
-- This is the main list entry for the environmental aspects matrix + revisiones.
-- Follows exact pattern. Matrix view, cuestionario integration, revisiones, full FK resolution, and supporting catalog modernizations (magnitud etc.) deferred.
-- Linked to Criterios (already modern).

CREATE TABLE IF NOT EXISTS aspectos (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(256),
    magnitud SMALLINT,
    gravedad SMALLINT,
    frecuencia SMALLINT,
    tipo_aspecto INTEGER,
    activo BOOLEAN,
    impacto INTEGER,
    probabilidad SMALLINT,
    severidad SMALLINT,
    area VARCHAR(128),
    observaciones TEXT
);

INSERT INTO aspectos (nombre, magnitud, gravedad, frecuencia, tipo_aspecto, activo, impacto, probabilidad, severidad, area, observaciones)
VALUES
    ('Consumo de energía eléctrica', 4, 3, 5, 1, true, 2, 3, 4, 'Producción', 'Principal consumo en líneas de montaje y climatización de planta.'),
    ('Generación de residuos peligrosos', 3, 4, 3, 2, true, 3, 4, 5, 'Almacén', 'Aceites y disolventes de mantenimiento. Gestión por gestor autorizado.'),
    ('Emisiones de CO2 por transporte', 2, 2, 2, 1, false, 1, 2, 2, 'Logística', 'Flota propia y proveedores. Plan de reducción en revisión.')
ON CONFLICT DO NOTHING;

INSERT INTO data_patches (filename, applied_at)
VALUES ('0026-aspectos-table-and-seed.sql', NOW())
ON CONFLICT (filename) DO NOTHING;
