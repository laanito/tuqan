-- 0024-documentos-table-and-seed.sql
-- Stage 9.5: Documentación initial shell (basic list + landing over core documentos table).
-- This is the first strangler step for the high-value Documentación module (legacy 66).
-- Full tree (arbol), editor, approval workflow, perfil permission arrays, and all sub-accions remain legacy.
-- Follows pattern from prior patches.

CREATE TABLE IF NOT EXISTS documentos (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(512),
    codigo VARCHAR(50),
    estado INTEGER,
    revisado_por INTEGER,
    aprobado_por INTEGER,
    revision VARCHAR(16),
    activo BOOLEAN,
    tipo_documento INTEGER,
    area INTEGER,
    calidad BOOLEAN,
    medioambiente BOOLEAN,
    perfil_ver BOOLEAN[],
    perfil_nueva BOOLEAN[],
    perfil_modificar BOOLEAN[],
    perfil_revisar BOOLEAN[],
    perfil_aprobar BOOLEAN[],
    perfil_historico BOOLEAN[],
    perfil_tareas BOOLEAN[],
    fecha_revision DATE,
    fecha_aprobacion DATE
);

INSERT INTO documentos (
    nombre, codigo, estado, revision, activo, tipo_documento, area,
    calidad, medioambiente,
    perfil_ver, perfil_nueva, perfil_modificar, perfil_revisar,
    perfil_aprobar, perfil_historico, perfil_tareas,
    fecha_revision, fecha_aprobacion
)
VALUES
    ('Manual de Calidad', 'MAN-001', 2, 'Rev 03', true, 1, 1,
     true, false,
     ARRAY[true,true,false,false,false,true,false,false],
     ARRAY[false,false,false,false,false,false,false,false],
     ARRAY[true,true,false,false,false,true,false,false],
     ARRAY[true,true,false,false,false,true,false,false],
     ARRAY[false,false,false,false,false,true,false,false],
     ARRAY[true,true,true,true,true,true,true,true],
     ARRAY[false,false,false,false,false,false,false,false],
     '2025-03-15', '2025-03-20'),
    ('Procedimiento de Control de Documentos', 'PRC-004', 2, 'Rev 02', true, 2, 1,
     true, true,
     ARRAY[true,true,true,false,false,true,false,false],
     ARRAY[false,false,false,false,false,false,false,false],
     ARRAY[true,true,true,false,false,true,false,false],
     ARRAY[true,true,true,false,false,true,false,false],
     ARRAY[false,false,false,false,false,true,false,false],
     ARRAY[true,true,true,true,true,true,true,true],
     ARRAY[false,false,false,false,false,false,false,false],
     '2025-04-10', '2025-04-12'),
    ('Instrucción Técnica de Ensayos', 'IT-012', 1, 'Rev 01', false, 3, 2,
     false, false,
     ARRAY[true,false,false,false,false,false,false,false],
     ARRAY[false,false,false,false,false,false,false,false],
     ARRAY[false,false,false,false,false,false,false,false],
     ARRAY[false,false,false,false,false,false,false,false],
     ARRAY[false,false,false,false,false,false,false,false],
     ARRAY[true,false,false,false,false,false,false,false],
     ARRAY[false,false,false,false,false,false,false,false],
     NULL, NULL)
ON CONFLICT DO NOTHING;

INSERT INTO data_patches (filename, applied_at)
VALUES ('0024-documentos-table-and-seed.sql', NOW())
ON CONFLICT (filename) DO NOTHING;