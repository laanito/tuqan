# Stage 9.29 — Mejora ↔ Auditorías cross-links (first slice)

**Status**: Planning phase (plan committed first)
**Branch target**: `feat/stage-9.29-mejora-auditorias-cross-links`
**Driver**: Suggested Next after 9.28: "Mejora cross links (Auditorias/Aspectos/Indicadores integration)". Schema already has `acciones_mejora.auditoria` FK + labels/selects from 9.21; missing bidirectional navigation, filter-by-auditoría, prefilled create from Auditorías, and reverse list of linked Mejora on ejecución form/list.

Follows exact ritual.

## Goals
1. Mejora list: filter by auditoría (`?auditoria=N`) + filter UI dropdown; clickable link to Auditoría ejecución.
2. Mejora form: prefill `auditoria` from `?auditoria=` on nuevo; clickable link to linked auditoría when set.
3. Auditorías ejecución list: show count of linked Mejora actions + link to filtered Mejora list.
4. Auditorías ejecución form (edit): list related Mejora rows + "Nueva acción de mejora" button → `/admin/mejora/nuevo?auditoria={id}`.
5. Small patch 0039 if needed for demo consistency (link remaining rows / ensure counts).
6. Extend verify + full 9.29 playbook + TODOS.

## Scope
**In:**
- Pages/Mejora/Listado.php + Formulario.php (filter, prefill, buildListVariables for filter options).
- Pages/Auditorias/Ejecucion/Listado.php + Formulario.php (reverse counts + related items).
- templates/mejora/* and templates/auditorias/ejecucion/* UI links.
- Optional light extension of CatalogListado getFilterParams for `auditoria` id (or Mejora-only override).
- Patch 0039 (idempotent) if data needs polish.
- verify-8.6.sh, STAGE-CHECKLISTS, MIGRATION-TODOS.
- This plan first.

**Out:**
- Aspectos / Indicadores FK columns (not on acciones_mejora today).
- Full hallazgos / plan-horario execution.
- Creating Mejora from incidencias.accion_mejora (Proveedores).
- Deep filter UX (multi-state, AJAX).

## Pattern
- Reuse getRelatedLabel/Options, getFilterParams pattern from 9.15 Aspectos.
- Reverse query: `SELECT id, descripcion, cerrada, usuario_verifica FROM acciones_mejora WHERE auditoria = ?`.
- Prefill: in Mejora Formulario::buildFormVariables when !$item and GET auditoria set.

## Data
- Most demo rows already have auditoria 1/2/3; ensure at least one per auditoría where useful.

## Verification
- Clean room optional; php -l; init + verify-8.6.sh with 9.29 asserts (counts linked, patch).
- Browser: filter Mejora by auditoría; open auditoría form → related Mejora + create prefilled; links round-trip.

## Risks
- Template key for Auditorías ejecución is `auditoria` (flashPrefix); related list as separate var `mejora_relacionadas`.
- Filter on ORDER BY SQL: append WHERE carefully before ORDER BY.

## Handoff
- First Mejora cross-module integration.
- Next: Aspectos/Indicadores links when schema supports, or Formación/Documentación, or Auditorías hallazgos.

---
*Part of the menu-driven modernization (Stage 8.x+ / 9.x).*

**Execution autonomous per AGENTS.md + testing strategy in STAGE-CHECKLISTS. Plan committed first. Docker-only. If git fails: retry.**

**Plan written on the feature branch (first commit).**

## Execution Evidence (9.29)

- Plan committed first.
- Clean room: down -v, up, init-db.sh — 0039 applied; 4 Mejora rows linked (auditoria 1×2, 2×1, 3×1).
- php -l green on all touched PHP.
- verify-8.6.sh passes with 0039 patch presence + mejora_with_auditoria.
- Core: Mejora filter + prefill; Auditorías reverse count/list + create link; CatalogListado getFilterParams auditoria.
- Docs: TODOS, STAGE-CHECKLISTS updated.
- Ready for push + PR; browser gate remains human.
