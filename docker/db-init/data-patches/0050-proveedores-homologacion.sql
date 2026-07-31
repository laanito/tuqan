-- 0050-proveedores-homologacion.sql
-- Stage 9.40: Homologation fields on proveedores + criterios + productos shells.

ALTER TABLE proveedores ADD COLUMN IF NOT EXISTS direccion TEXT;
ALTER TABLE proveedores ADD COLUMN IF NOT EXISTS web VARCHAR(255);
ALTER TABLE proveedores ADD COLUMN IF NOT EXISTS cif VARCHAR(20);
ALTER TABLE proveedores ADD COLUMN IF NOT EXISTS fecha_homologacion DATE;
ALTER TABLE proveedores ADD COLUMN IF NOT EXISTS ultima_revision DATE;
ALTER TABLE proveedores ADD COLUMN IF NOT EXISTS fecha_deshomologacion DATE;

CREATE TABLE IF NOT EXISTS criterios_homologacion (
  id     SERIAL PRIMARY KEY,
  nombre CHARACTER VARYING(255) NOT NULL,
  valor  INTEGER DEFAULT 0,
  activo BOOLEAN DEFAULT true
);

CREATE TABLE IF NOT EXISTS productos (
  id             SERIAL PRIMARY KEY,
  valor          INTEGER DEFAULT 0,
  proveedor      INTEGER REFERENCES proveedores (id),
  criterios      INTEGER [],
  fecha_revision DATE,
  nombre         CHARACTER VARYING(128) NOT NULL,
  activo         BOOLEAN DEFAULT true,
  homologado     BOOLEAN DEFAULT false
);

-- Seed criteria
INSERT INTO criterios_homologacion (nombre, valor, activo)
SELECT 'Certificación ISO 9001', 30, true
WHERE NOT EXISTS (SELECT 1 FROM criterios_homologacion WHERE nombre = 'Certificación ISO 9001');

INSERT INTO criterios_homologacion (nombre, valor, activo)
SELECT 'Entregas a tiempo', 25, true
WHERE NOT EXISTS (SELECT 1 FROM criterios_homologacion WHERE nombre = 'Entregas a tiempo');

INSERT INTO criterios_homologacion (nombre, valor, activo)
SELECT 'Calidad del producto', 35, true
WHERE NOT EXISTS (SELECT 1 FROM criterios_homologacion WHERE nombre = 'Calidad del producto');

INSERT INTO criterios_homologacion (nombre, valor, activo)
SELECT 'Servicio postventa', 10, true
WHERE NOT EXISTS (SELECT 1 FROM criterios_homologacion WHERE nombre = 'Servicio postventa');

-- Demo homologation on proveedor 1
UPDATE proveedores SET
  fecha_homologacion = COALESCE(fecha_homologacion, CURRENT_DATE - INTERVAL '180 days'),
  ultima_revision = COALESCE(ultima_revision, CURRENT_DATE - INTERVAL '30 days'),
  fecha_deshomologacion = NULL
WHERE id = 1;

-- Demo products
INSERT INTO productos (nombre, proveedor, valor, homologado, activo, fecha_revision)
SELECT 'Producto homologado A', 1, 50, true, true, CURRENT_DATE - INTERVAL '30 days'
WHERE EXISTS (SELECT 1 FROM proveedores WHERE id = 1)
  AND NOT EXISTS (SELECT 1 FROM productos WHERE nombre = 'Producto homologado A' AND proveedor = 1);

INSERT INTO productos (nombre, proveedor, valor, homologado, activo, fecha_revision)
SELECT 'Producto pendiente B', 1, 70, false, true, NULL
WHERE EXISTS (SELECT 1 FROM proveedores WHERE id = 1)
  AND NOT EXISTS (SELECT 1 FROM productos WHERE nombre = 'Producto pendiente B' AND proveedor = 1);

INSERT INTO productos (nombre, proveedor, valor, homologado, activo, fecha_revision)
SELECT 'Suministro estándar', 2, 40, false, true, NULL
WHERE EXISTS (SELECT 1 FROM proveedores WHERE id = 2)
  AND NOT EXISTS (SELECT 1 FROM productos WHERE nombre = 'Suministro estándar' AND proveedor = 2);

INSERT INTO data_patches (filename, applied_at)
VALUES ('0050-proveedores-homologacion.sql', NOW())
ON CONFLICT (filename) DO NOTHING;
