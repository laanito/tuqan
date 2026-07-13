# Stage 9.27 — Mejora deeper: basic state machine (verify/close support)

**Status**: Planning phase (plan committed first)
**Branch target**: `feat/stage-9.27-mejora-state-machine`
**Driver**: Suggested Next after 9.25/9.26. "More Mejora (full state machine)". Previous Mejora slices added fields and basic; now implement basic state machine: support for 'verificar' (set verifica fields) and 'cerrar' (set cerrada) with state display in list/form, basic transitions (e.g., can close if verified), update UI.

Follows exact ritual.

## Goals
1. Add basic state machine to modern Mejora: in Form allow setting 'verificar' and 'cerrar' states (using existing fields), with validation (e.g., require verifica before close).
2. Update Listado to compute and show current state (Pendiente, Verificada, Cerrada).
3. Update templates: form with action checkboxes/fields, list with state column.
4. Small patch for demo data with states.
5. Extend verify + playbook.
6. Update TODOS.
7. Plan first.

## Scope
**In:**
- Enhance Mejora/Formulario to support state actions (e.g., 'verificar' flag sets fields, 'cerrar' sets cerrada).
- Enhance Listado fetch/map to compute state.
- Templates updates for state UI.
- Patch 0037 for sample state data.
- Verify extend (states checks).
- 9.27 playbook.
- TODOS.
- This plan (first).

**Out:**
- Full state machine (all transitions, UI buttons separate, notifications, audit log).
- More links (to other modules).
- Broader Mejora.

## Pattern
- Builds on 9.21/9.25 Mejora deeper (fields added).
- Use existing 'cerrada', usuario_*, fecha_* for states.
- State logic: Pendiente (no verifica), Verificada (verifica set, not cerrada), Cerrada (cerrada set).

## Data
- Extend to set fields based on actions in post.
- Sample data with mixed states.

## Verification
- php-l, clean init, verify-8.6.sh with state asserts.
- Browser: /admin/mejora list shows states, form allows verify/close, transitions work, no reg.

## Risks
- Scope: basic actions in form; no full UI buttons yet.
- Validation simple.

## Handoff
- Advances Mejora state machine.
- Next: more Mejora links or other verticals like Aspectos full, Auditorias execution, etc.

---
*Part of the menu-driven modernization (Stage 8.x+ / 9.x).*

**Execution autonomous per AGENTS.md + testing strategy in STAGE-CHECKLISTS. Plan committed first. Docker-only. If git fails: retry.**

**Plan written on the feature branch (first commit).**