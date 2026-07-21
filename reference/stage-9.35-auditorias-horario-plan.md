# Stage 9.35 — Auditorías plan/horario first slice

**Status**: Planning (plan first)
**Branch**: `feat/stage-9.35-auditorias-horario`
**Driver**: Sized next after 9.34. Legacy table `horario_auditoria` (horainicio/horafin/requisito/auditor/area) exists in clean schema dump but not in live minimal DB. Add modern `auditoria` FK for ejecución cross-links (same pattern as hallazgos 9.32).

## Goals
1. Patch **0045**: CREATE `horario_auditoria` (+ `auditoria` column) + demo rows.
2. `Pages/Auditorias/Horario/{Listado,Formulario}` Catalog CRUD.
3. Routes `/admin/auditorias/horario`; filter/prefill by auditoría.
4. Ejecución reverse count + section + **+ Horario** button.
5. verify + playbook + TODOS.

## Out
- Informes PDF, full calendar UI, drag schedule.

## Sizing
- One outcome: manage schedule slots per auditoría.
- ~12–15 files, 1 patch.

---
*Plan committed first. Docker-only.*
