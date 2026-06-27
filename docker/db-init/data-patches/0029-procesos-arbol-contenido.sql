-- 0029-procesos-arbol-contenido.sql
-- Stage 9.11: Procesos Árbol + contenido basic shell.
-- Ensures contenido_procesos table + sample data linked to procesos (from 0028).
-- Tree hierarchy via procesos.padre; rich details (entradas/salidas/etc.) via contenido.
-- Full editing of arrays, flujogramas, indicators and tree drag/edit deferred.

CREATE TABLE IF NOT EXISTS contenido_procesos (
  proveedor              TEXT,
  entradas               TEXT,
  propietario            TEXT,
  indicadores            INTEGER [],
  salidas                TEXT,
  cliente                TEXT,
  doc_asociada           TEXT,
  registros              TEXT,
  indicaciones           TEXT,
  anejos                 INTEGER [],
  flujograma             INTEGER,
  instalaciones_ambiente TEXT,
  documento              INTEGER,
  proceso                INTEGER,
  id                     SERIAL NOT NULL
);

INSERT INTO contenido_procesos (proveedor, entradas, propietario, salidas, cliente, doc_asociada, registros, indicaciones, instalaciones_ambiente, proceso)
VALUES
    ('Proveedor Diseño S.L.', 'Requisitos cliente, especificaciones técnicas', 'Jefe de Diseño', 'Planos aprobados, prototipos', 'Cliente Final A', 'PRC-001, FRC-012', 'REG-DES-01', 'Revisión por pares', 'Sala de diseño climatizada', 1),
    ('Proveedor Revisión S.A.', 'Borradores, datos de ensayo', 'Responsable Calidad', 'Informe de revisión firmado', 'Cliente Final A', 'PRC-001', 'REG-REV-05', 'Checklist interno', 'Laboratorio', 2),
    ('Auditor Externo', 'Resultados de auditoría interna', 'Gerente Calidad', 'Certificado de conformidad', 'Cliente Final B', '', 'REG-CAL-03', 'Acciones correctivas', 'Oficina principal', 3)
ON CONFLICT DO NOTHING;

INSERT INTO data_patches (filename, applied_at)
VALUES ('0029-procesos-arbol-contenido.sql', NOW())
ON CONFLICT (filename) DO NOTHING;
