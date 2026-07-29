# Stage 9.38 — Equipos plan mantenimiento / auto-preventivo first slice

**Status**: Planning (plan first)
**Branch**: `feat/stage-9.38-equipos-plan-preventivo`
**Driver**: Sized next after 9.37. Legacy plan mantenimiento shows equipo maintenance history and offers Mant. Preventivo (next date from `mantenimiento_cada` + `dias`) / Correctivo. Modern has revisiones + calendario; interval fields not on form; no "program next preventivo".

## Goals
1. Expose `mantenimiento_cada` + `dias` (días/meses) on equipos form (create/edit).
2. `Pages/Equipos/Plan.php` — plan view per equipo: interval summary, next due (computed), list of mantenimientos, actions.
3. POST **Programar preventivo**: insert `mantenimientos` tipo=preventivo with fecha_prevista = next due (legacy interval rules).
4. Quick link to nueva revisión correctivo (existing form prefilled).
5. Routes `/admin/equipos/plan/{id}`; nav from list/form/calendario/revisiones.
6. Patch **0048** demo (ensure intervals + one past preventivo for due calc) if useful.
7. verify + playbook + TODOS.

## Out
- Full auto-batch for all equipos, email reminders, ICS, deep correctivo workflows.

## Sizing
- One outcome: see plan + schedule next preventivo for an equipo.
- ~12–15 files, 0–1 patch.

---
*Plan committed first. Docker-only.*
