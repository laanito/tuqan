-- 0022-acciones-mejora-table-and-seed.sql
-- Stage 9.4: Acciones de Mejora main table + seed (first basic CRUD slice of the Mejora module under Aplicacion).
-- Follows exact pattern from 0021-equipos and 0020-proveedores.
-- All columns from legacy schema included for completeness (workflow user assignments, full FK enrichment,
-- auditoria links and sub-entities like plan_formacion will be exercised in later legs).

CREATE TABLE IF NOT EXISTS acciones_mejora (
    id SERIAL PRIMARY KEY,
    tipo INTEGER,
    cliente INTEGER,
    fecha DATE,
    usuario_detectado INTEGER,
    descripcion VARCHAR(1024),
    analisis VARCHAR(1024),
    requiere_tratamiento BOOLEAN,
    tratamiento VARCHAR(1024),
    accion_preventiva VARCHAR(1024),
    fecha_implantacion DATE,
    usuario_verifica INTEGER,
    fecha_verifica DATE,
    observaciones VARCHAR(1024),
    coste NUMERIC(10, 2),
    cerrada BOOLEAN,
    usuario_cerrado INTEGER,
    fecha_cierre DATE,
    usuario_implantacion INTEGER,
    plazo DATE,
    auditoria INTEGER,
    area VARCHAR(128)
);

INSERT INTO acciones_mejora (
    tipo, cliente, fecha, descripcion, analisis, requiere_tratamiento, tratamiento,
    accion_preventiva, fecha_implantacion, plazo, coste, cerrada, area, observaciones
)
VALUES
    (1, 1, '2025-05-10', 'Fuga en compresor principal de aire', 'Análisis inicial: junta desgastada por ciclo alto. Requiere repuesto y parada programada.', true, 'Sustituir junta tórica + realinear acoplamiento. Parada 4h.', 'Revisión trimestral de juntas en compresores críticos.', '2025-06-15', '2025-06-20', 1250.50, false, 'Producción - Línea A', 'Detectado durante ronda de mantenimiento preventivo.'),
    (2, NULL, '2025-06-02', 'Incumplimiento de temperatura en sala de servidores', 'Sensor de temperatura reportó picos >28C durante 3 noches. Causa probable: fallo ventilación backup.', true, 'Instalar sensor redundante + revisar setpoints del climatizador.', 'Añadir alarma SMS para desviaciones >2C del setpoint.', NULL, '2025-06-10', 480.00, false, 'IT / CPD', NULL),
    (1, 2, '2025-04-15', 'Error de etiquetado en lote de producto acabado', 'Etiquetadora aplicó código incorrecto en 240 unidades. Lote retenido y 100% reinspeccionado.', false, NULL, 'Doble verificación de setup de etiquetadora antes de cada lote + checklist digital.', '2025-04-18', NULL, 320.00, true, 'Calidad - Almacén', 'Cerrada tras reinspección y liberación controlada. Tipo de mejora: corrección.')
ON CONFLICT DO NOTHING;

INSERT INTO data_patches (filename, applied_at)
VALUES ('0022-acciones-mejora-table-and-seed.sql', NOW())
ON CONFLICT (filename) DO NOTHING;