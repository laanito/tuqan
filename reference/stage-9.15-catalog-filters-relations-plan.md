# Stage 9.15 — Cross-cut: list-with-filters and form-with-relations helpers

**Status**: Planning phase (plan committed first)
**Branch target**: `feat/stage-9.15-catalog-filters-relations` (fresh from master)
**Driver**: Suggested Next from `.agents/MIGRATION-TODOS.md` after 9.14 (Aspectos matrix). Explicitly "More cross-cuts (filters, relations, full tree base)". The cross-cutting backlog notes "list-with-filters, form-with-relations" as remaining after 9.8 base + 9.13 tree helpers.

Follows the exact process ritual (plan first, Docker-only, living docs, full verify/playbook, reviewable size). Continues the base extraction pattern from 9.8 and 9.13.

## Goals (Aligned with Directives)
1. Deliver first focused cross-cut for **list-with-filters** and **form-with-relations** in the Catalog base classes.
2. Add reusable helpers to CatalogListado (e.g. filter params, applyWhere, active-only toggle) and CatalogFormulario (e.g. load related for selects/displays, FK helpers).
3. Keep backward compatible; no behavior change to existing lists/forms.
4. Demonstrate in a couple of places (or just base + comments) without touching every module.
5. No data patch needed.
6. Extend verify + full "Stage 9.15 Verification Playbook".
7. Update living MIGRATION-TODOS (advance the cross-cut item), MIGRATION-PLAN, STAGE-CHECKLISTS.
8. 100% Docker-only, plan-first commit, clean verification, reviewable PR.

## Scope (Reviewable cross-cut slice)
**In:**
- New protected helpers in CatalogListado:
  - getFilterParams() or similar for querystring filters (e.g. active, tipo, area)
  - applyFiltersToQuery() or buildWhereFromFilters()
  - Common active filter support (many lists already filter on activo implicitly or explicitly).
- Helpers in CatalogFormulario:
  - loadRelated() or getSelectForRelated() for FKs (e.g. load area name, tipo label).
  - buildRelationVariables() or similar.
- Refactor the base ShowPage / build* slightly to support optional filters without breaking.
- Update 1-2 example modules lightly if it demonstrates value (e.g. one list gains an active filter toggle in URL, one form loads a related label).
- Extend scripts/verify-8.6.sh (php -l on bases).
- Full 9.15 playbook.
- TODOS advance + Suggested refresh (next could be full tree base, PDF reports, or a vertical like Mejora full).
- MIGRATION-PLAN note.
- This plan (first commit).

**Out (explicit future legs):**
- Full "list-with-filters" UI (search box, dropdowns, persisted filters in session).
- Advanced relations (multi-select, inline editing, eager loading in lists).
- Full tree base class (beyond the 9.13 helpers).
- Applying the helpers across all existing modern lists/forms (can be done gradually or in a dedicated cleanup).
- Filters in the new Matriz views or tree views.

## Pattern to Follow
- Same as 9.8 (initial rich helpers) and 9.13 (tree helpers): extract common patterns seen in rich modules into base, update the base + minimal consumers.
- Keep all existing lists/forms working exactly as before.
- Use the 9.8 helpers (getDb, getUserContext, etc.) as foundation.
- Verification: clean room + no regression on any modern list/form + php -l + browser confirm filters/relations work where demonstrated.
- Update living TODOS in the PR.

## Data Discovery
- Many current lists (e.g. Aspectos, Procesos, Documentación) already have 'activo' and often 'area'/'tipo_*' columns — natural filter candidates.
- Forms frequently display or select FKs (area, tipo_documento, tipo_aspecto, etc.) — relations helpers will help load labels without extra queries in templates.
- Query builder is still the legacy Manejador_Base_Datos + prepared statements; helpers should work with existing getSelectSql() + fetch.
- No change to data model or patches.

## Verification Checklist (Non-Interactive + Human Gate)
- php -l on CatalogListado + CatalogFormulario + touched files.
- After clean init: no data changes.
- verify-8.6.sh green (bases still pass, no breakage on any modern module lists/forms).
- Browser (after login):
  - Existing lists (e.g. /admin/aspectos, /admin/procesos) render unchanged.
  - If demonstrating, a list supports e.g. ?activo=1 or ?area=xx and filters correctly.
  - A form loads a related label cleanly.
  - No visual or functional regressions.
- All prior modules (9.1–9.14) unaffected.

## Files To Be Created/Modified
**New**:
- reference/stage-9.15-catalog-filters-relations-plan.md (this; first)

**Modified**:
- Pages/Catalog/CatalogListado.php (add filter helpers)
- Pages/Catalog/CatalogFormulario.php (add relation helpers)
- (Lightly) 1–2 example Pages/* if demonstrating (or just comments in base)
- scripts/verify-8.6.sh (ensure bases are syntax-checked)
- .agents/MIGRATION-TODOS.md (advance cross-cut item)
- .agents/STAGE-CHECKLISTS.md (full 9.15 section)
- .agents/MIGRATION-PLAN.md (Last Updated)

## Detailed Execution Order (Strict — Plan First)
1. Write + commit this plan as absolute first change.
2. Add filter helpers to CatalogListado (getActiveFilter, applyListFilters, etc.).
3. Add relation helpers to CatalogFormulario (loadRelatedItem, getRelatedLabel, etc.).
4. Optionally demonstrate in one list and one form (minimal diff).
5. Extend verify script if needed.
6. Update all .agents/ (TODOS first).
7. Full verification (clean room + psql + verify-8.6.sh + php -l + browser confirmation of no regression + any demo features).
8. Logical commits, push -u, open PR referencing the TODOS cross-cut item + this plan.
9. (Post-merge) Human browser sign-off.

## Success Criteria
- Plan first commit.
- Existing lists and forms unchanged in behavior and output.
- New helpers available in base for filters (e.g. active, type/area) and relations (FK labels).
- verify + psql + php -l green; no regression on 9.1–9.14 modules.
- Living TODOS has the cross-cut item advanced + Suggested refreshed.
- Clear comments/examples in code.

## Risks & Mitigations
- Scope creep into full UI: stick to backend helpers only for this leg; UI polish later.
- Breaking changes: add optional params with defaults that preserve current behavior.
- Demonstration: if time, only touch the base + maybe one safe example (e.g. add ?activo= filter support to one list without changing its default output).

## Related / Handoff
- Directly delivers the top item in "More cross-cuts".
- Enables easier richer lists/forms going forward (e.g. for Mejora, Formación, Auditorías execution).
- After: can continue with full tree base, PDF report cross-cut, questionnaire engine, or a vertical like Mejora full integration / Auditorías execution.

---
*Part of the menu-driven modernization (Stage 8.x+ / 9.x).*

**Execution autonomous per AGENTS.md + testing strategy in STAGE-CHECKLISTS. Plan committed first. Docker-only. If git fails: retry.**

**Plan written on the feature branch (first commit).**