# Stage 9.32 — Auditorías hallazgos first slice

**Status**: Planning (plan first)
**Branch**: `feat/stage-9.32-auditorias-hallazgos`
**Driver**: Sized next after 9.31. No legacy `hallazgos` table in clean schema — introduce minimal modern table + list/form linked to `auditorias` ejecución (and optional Mejora).

## Goals
1. Patch **0042**: `CREATE TABLE hallazgos_auditoria` + demo rows + data_patches.
2. `Pages/Auditorias/Hallazgos/{Listado,Formulario}` on Catalog base.
3. Routes `/admin/auditorias/hallazgos` (+ nuevo/editar POST/GET).
4. Filter by `auditoria`; prefill `?auditoria=`; reverse count on ejecución list; section on ejecución form.
5. Optional FK display/select for `accion_mejora`.
6. verify + playbook + TODOS.

## Out
- Plan/horario, informes PDF, full findings workflow, auto-create Mejora.

## Sizing
- One outcome: CRUD hallazgos linked to ejecución + navigation.
- ~12–16 files, 1 patch.

---
*Plan committed first. Docker-only.*
