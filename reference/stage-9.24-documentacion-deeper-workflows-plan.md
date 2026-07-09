# Stage 9.24 — Documentación deeper (workflows) first slice

**Status**: Planning phase (plan committed first)
**Branch target**: `feat/stage-9.24-documentacion-deeper-workflows`
**Driver**: Suggested Next after 9.23 (Documentación editor/perfiles first slice). The TODO calls for "Documentación deeper (full editor/workflows)". We did the perfiles/editor refactor slice; next contained slice: add workflow fields (revisado_por, aprobado_por, fechas) + relations, basic state display.

Follows exact ritual.

## Goals
1. Deepen Documentacion Form/Listado with workflow fields from schema (revisado_por, aprobado_por, fecha_revision, fecha_aprobacion).
2. Use relations for user fields (usuarios).
3. Update templates to show/edit workflows.
4. Perhaps add basic "revisar/aprobar" indicators.
5. Extend verify + playbook.
6. Update TODOS.
7. Plan first.

## Scope
**In:**
- Enhance Documentacion/Formulario and Listado with workflow fields + relations.
- Templates updates.
- Verify extension.
- 9.24 playbook.
- TODOS.
- This plan (first).

**Out:**
- Full editor (rich content).
- Perfiles full integration in Arbol/workflows.
- PDF, sub accions.

## Pattern
- Builds on 9.23 refactor.
- Use getRelated for users.

## Data
- Extend selects to include workflow columns.
- Seeds may need enrichment if not present.

## Verification
- php-l, clean init, verify script, browser /admin/documentacion form shows workflows, no reg.

## Risks
- Scope tight: only workflow fields + display.

## Handoff
- Continues Documentación deeper.
- Next: more Documentación (editor content), or other.

---
*Part of the menu-driven modernization (Stage 8.x+ / 9.x).*

**Execution autonomous per AGENTS.md + testing strategy in STAGE-CHECKLISTS. Plan committed first. Docker-only. If git fails: retry.**

**Plan written on the feature branch (first commit).**