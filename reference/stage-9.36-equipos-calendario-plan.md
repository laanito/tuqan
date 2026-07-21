# Stage 9.36 — Equipos calendario first slice

**Status**: Planning (plan first)
**Branch**: `feat/stage-9.36-equipos-calendario`
**Driver**: Sized next after 9.35. Legacy menu `equipos:calendario:listado:ver` + `calendario.inc.php` year view marking mantenimiento dates. Revisiones CRUD already modern (9.33, table `mantenimientos`).

## Goals
1. Patch **0046**: demo `mantenimientos` rows with dates in the current year (so the calendar is visible after init).
2. `Pages/Equipos/Calendario.php` — annual calendar shell over `mantenimientos` (fecha_prevista / fecha_realiza).
3. Routes `/admin/equipos/calendario` + legacy path mapping when needed.
4. Year nav (`?year=`), optional `?equipo=` filter; day markers by tipo (revisión / preventivo / correctivo); event list + links to revisiones edit.
5. Nav from equipos list + revisiones list.
6. verify + playbook + TODOS.

## Out
- Full preventivo auto-scheduling from `mantenimiento_cada` / dias.
- Correctivo-only workflows deeper than existing revisiones form.
- Drag-drop calendar, ICS export, email reminders.

## Sizing
- One outcome: clickable annual calendar of maintenance/revision dates.
- ~10–14 files, 1 small data patch (demo only; no new table).

---
*Plan committed first. Docker-only.*
