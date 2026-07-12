# Stage 9.25 — Mejora deeper workflow: remaining fields and basic state

**Status**: Planning phase (plan committed first)
**Branch target**: `feat/stage-9.25-mejora-more-workflow`
**Driver**: Suggested Next after 9.24. "More Mejora". The 9.21 slice added initial user assignments + auditoria link; "full state machine, more links" remains. This contained slice adds the remaining workflow fields (usuario_verifica, fecha_verifica, usuario_implantacion, fecha_cierre, etc.) + relations, enriches display, and adds basic state handling in form.

Follows exact ritual.

## Goals
1. Add remaining schema fields for Mejora workflow to modern Form/Listado (usuario_verifica, fecha_verifica, usuario_implantacion, fecha_cierre, etc.).
2. Use relations for additional user fields.
3. Update templates to show/enrich more workflow info and basic state (e.g., verify/implant/close indicators).
4. Basic support for changing state (e.g., set cerrado via form).
5. Extend verify + full playbook.
6. Update TODOS.
7. Plan first.

## Scope
**In:**
- Enhance Mejora/Formulario and Listado with missing workflow fields + relations.
- Templates updates for additional labels and state fields.
- Small data enrichment patch if needed.
- Verify extension.
- 9.25 playbook.
- TODOS update.
- This plan (first).

**Out:**
- Full UI buttons/transitions (verificar, cerrar as separate actions).
- More integration (with Aspectos, Indicadores).
- Complete state machine logic.

## Pattern
- Direct continuation of 9.21.
- Leverage existing relations polish.

## Data Discovery
- Extend selects to include: usuario_verifica, fecha_verifica, usuario_implantacion, fecha_cierre, etc.
- Enrich seeds if workflow users not populated.

## Verification
- php-l, clean init, verify-8.6.sh with specific workflow asserts.
- Browser: /admin/mejora form/list shows additional workflow fields and labels; basic state change works; no reg.

## Risks
- Scope: fields + display + basic state; no new buttons or complex logic yet.

## Handoff
- Continues Mejora deeper.
- Next: more Mejora (full machine), Formación, Documentación content editor, etc.

---
*Part of the menu-driven modernization (Stage 8.x+ / 9.x).*

**Execution autonomous per AGENTS.md + testing strategy in STAGE-CHECKLISTS. Plan committed first. Docker-only. If git fails: retry.**

**Plan written on the feature branch (first commit).**