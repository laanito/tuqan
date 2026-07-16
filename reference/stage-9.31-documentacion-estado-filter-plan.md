# Stage 9.31 — Documentación estado/filter polish

**Status**: Planning phase (plan committed first)
**Branch**: `feat/stage-9.31-documentacion-estado-filter`
**Driver**: Sized next after 9.30. Also capture architecture note: `/admin` is modern URL namespace; Catalog/Pages domain is reusable; authz + path hardcoding still open.

## Goals
1. Map documento `estado` integers (legacy constants) to human labels + badges in list/form.
2. Filter list by estado (+ keep activo filter option if cheap).
3. Form: estado as select (not raw number), show badge.
4. Nav link to Árbol; patch 0041 for mixed demo estados.
5. Note in MIGRATION-TODOS: admin path vs reusable modules (no code fork yet).
6. verify + playbook + TODOS.

## Out
- Rich HTML editor, binario, full approval transitions/quick buttons (can follow like Mejora 9.28).
- Permission gates on Phroute.

## Sizing
- One outcome: “see and filter documents by estado with readable labels”.
- ~10–14 files, 1 small patch.

---
*Plan committed first. Docker-only.*
