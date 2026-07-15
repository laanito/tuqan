-- 0039-mejora-auditorias-cross-links.sql
-- Stage 9.29: Ensure Mejora ↔ Auditorías demo links for filter + reverse counts

-- Link any unlinked demo rows to auditoría 1 (idempotent where null)
UPDATE acciones_mejora
SET auditoria = 1
WHERE auditoria IS NULL
  AND descripcion LIKE '%transiciones rápidas%';

-- Keep existing links from 0033/0037/0038 (ids 1→1, 2→2, 3→3) if present
UPDATE acciones_mejora SET auditoria = 1 WHERE id = 1 AND (auditoria IS NULL OR auditoria = 0);
UPDATE acciones_mejora SET auditoria = 2 WHERE id = 2 AND (auditoria IS NULL OR auditoria = 0);
UPDATE acciones_mejora SET auditoria = 3 WHERE id = 3 AND (auditoria IS NULL OR auditoria = 0);

INSERT INTO data_patches (filename, applied_at)
VALUES ('0039-mejora-auditorias-cross-links.sql', NOW())
ON CONFLICT (filename) DO NOTHING;
