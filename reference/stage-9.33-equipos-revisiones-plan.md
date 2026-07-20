# Stage 9.33 — Equipos revisiones shell (mantenimientos)

**Status**: Planning (plan first)
**Branch**: `feat/stage-9.33-equipos-revisiones`
**Driver**: Sized next after 9.32. Legacy menu `equipos:revision:listado:ver`; schema has `mantenimientos` (equipo FK) for revision/maintenance records.

## Goals
1. Patch **0043**: CREATE `mantenimientos` if missing + demo rows linked to equipos.
2. `Pages/Equipos/Revisiones/{Listado,Formulario}` (table `mantenimientos`).
3. Routes `/admin/equipos/revisiones` + nuevo/editar; legacy path optional.
4. Filter by equipo; prefill `?equipo=`; reverse count + **+ Revisión** on equipos list.
5. verify + playbook + TODOS.

## Out
- Calendario anual, plan mantenimiento UI, correctivo workflows deep.

## Sizing
- One outcome: list/edit revisiones per equipo.
- ~12–15 files, 1 patch.

---
*Plan committed first. Docker-only.*
