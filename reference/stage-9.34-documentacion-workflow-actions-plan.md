# Stage 9.34 — Documentación quick workflow actions

**Status**: Planning (plan first)
**Branch**: `feat/stage-9.34-documentacion-workflow-actions`
**Driver**: Sized next after 9.33. Parallel to Mejora 9.28: one-click transitions using existing estado + revisado_por/aprobado_por fields.

## Goals
1. Quick POST actions: Enviar a revisión → estado 3; Revisar → user+fecha + estado 4; Aprobar → user+fecha + estado 1 (requires prior revisión).
2. Form checkboxes for same actions with auto-assign current user/today.
3. List conditional buttons by estado.
4. Routes; small patch 0044 demo states for all three buttons.
5. verify + playbook + TODOS.

## Out
- Rich HTML editor, binario, full audit trail.

## Sizing
- One outcome: click Revisar/Aprobar on document list/form.
- ~10 files, 1 small patch.

---
*Plan committed first. Docker-only.*
