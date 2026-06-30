# Stage 9.14 — Aspectos Ambientales matrix first slice

**Status**: Planning phase (plan committed first)
**Branch target**: `feat/stage-9.14-aspectos-matrix` (fresh from master)
**Driver**: Suggested Next from `.agents/MIGRATION-TODOS.md` after 9.13 (cross-cut tree helpers). "Aspectos matrix" is explicitly listed among the next verticals. The basic shell for `aspectos` was delivered in 9.7; full matrix, revisiones, cuestionario integration deferred.

Follows the exact process ritual (plan first, Docker-only, living docs, full verify/playbook, reviewable size). Builds on the enhanced Catalog* bases (9.8 + 9.13 tree helpers) and previous Aspectos shell.

## Goals (Aligned with Directives)
1. Deliver the first modern **matrix view** for Aspectos Ambientales (legacy 73) — a grouped/table matrix showing aspects with their evaluation scores (magnitud, gravedad, frecuencia, impacto, etc.), typically by área and/or tipo.
2. Use the current CatalogListado + 9.13 helpers where useful for data fetching and common variables.
3. No new data patch (reuse existing `aspectos` rows and fields from the 0026 basic patch).
4. `Pages/Aspectos/Matriz.php` + `templates/aspectos/matriz.twig` (clear stage notes on deferred parts).
5. Route: `/admin/aspectos/matriz` (and legacy mapping if accion identified).
6. Extend verify-8.6.sh + full "Stage 9.14 Verification Playbook".
7. Update living MIGRATION-TODOS (advance Aspectos matrix item), MIGRATION-PLAN, STAGE-CHECKLISTS.
8. 100% Docker-only, plan-first commit, clean verification, reviewable PR.

## Scope (Reviewable first slice)
**In:**
- Modern matrix view for Aspectos: fetch all aspects, group by área / tipo_aspecto, display in a matrix-style table (scores as columns or heat-style indicators).
- `Pages/Aspectos/Matriz.php` (leverages base helpers for DB/sidebar/context/flash; custom grouping and presentation).
- `templates/aspectos/matriz.twig` (matrix table + links back to list/edit + stage alert).
- Add modern route `/admin/aspectos/matriz`.
- Extend verify script (php -l on new files, matrix path in checks).
- Full 9.14 playbook.
- TODOS advance + Suggested refresh (e.g. more cross-cuts or Auditorías execution / Mejora full).
- MIGRATION-PLAN update.
- This plan (first commit).

**Out (explicit future legs):**
- Full interactive matrix (editable scores in grid).
- Revisiones (historico for aspects).
- Cuestionario integration (the main driver for populating scores).
- Reporting tie-in to Indicadores.
- "Emergencia" handling or other advanced logic.
- PDF export of the matrix.
- Supporting catalogs if missing (tipo_aspectos is already partially modernized via Tipos*).

## Pattern to Follow
- Same as 9.7 (basic Aspectos) + 9.12 (Documentación tree): focused first slice for the pending "matrix" part.
- Use 9.8/9.13 helpers to keep the new page small.
- Exact template style (page-header, table, .alert notes for deferred).
- Modern route + legacy if applicable.
- Verification: clean room + psql (aspectos rows + scores), verify-8.6.sh, php -l, browser flow for matrix + no regression on basic list/form.
- Update living TODOS in the PR.

## Data Discovery
- `aspectos` (from 0026 + current code): id, nombre, magnitud, gravedad, frecuencia, tipo_aspecto, activo, impacto, probabilidad, severidad, area, observaciones, ...
- Scores appear to be stored directly (magnitud/gravedad/frecuencia etc.). Impacto/severidad etc. may be computed or entered.
- tipo_aspectos and areas exist as supporting data.
- Legacy "Ver Matriz" / Matriz buttons exist for aspects.
- No formula_aspectos table in current dev DB (may be legacy calculation aid or not yet seeded in our patches).
- Current modern pages: basic Listado + Formulario only. No matrix yet.

## Verification Checklist (Non-Interactive + Human Gate)
- php -l on new Matriz + Catalog base + index + verify.
- After clean init: aspectos table + rows with scores present.
- verify-8.6.sh green (includes matrix checks, no breakage on 9.7 Aspectos basic or other modules).
- Browser (after login):
  - Sidebar or link to Aspectos → matrix view shows grouped table with scores (by area/tipo).
  - Links to edit/list work; basic CRUD unaffected.
  - Matrix looks usable as first modern view.
- No regression on priors (incl. 9.13 tree helpers, other verticals).
- Post-view DB state consistent.

## Files To Be Created/Modified
**New**:
- reference/stage-9.14-aspectos-matrix-plan.md (this; first)
- Pages/Aspectos/Matriz.php
- templates/aspectos/matriz.twig

**Modified**:
- index.php (add modern /admin/aspectos/matriz route)
- scripts/verify-8.6.sh
- .agents/MIGRATION-TODOS.md (advance Aspectos matrix, refresh Suggested)
- .agents/STAGE-CHECKLISTS.md (full 9.14 section)
- .agents/MIGRATION-PLAN.md (Last Updated)

## Detailed Execution Order (Strict — Plan First)
1. Write + commit this plan as absolute first change.
2. Implement Matriz page + template (use helpers for common parts, custom for matrix grouping/presentation).
3. Add route in index.php.
4. Extend verify script.
5. Update all .agents/ (TODOS first).
6. Full verification (clean room + psql + verify-8.6.sh + php -l + browser matrix + basic list + no regression).
7. Logical commits, push -u, open PR referencing the TODOS item + this plan.
8. (Post-merge) Human browser sign-off.

## Success Criteria
- Plan first commit.
- /admin/aspectos/matriz works and displays a useful matrix of aspects with scores.
- Basic list/form (/admin/aspectos) untouched.
- verify + psql + php -l green.
- Living TODOS marks Aspectos matrix advanced + Suggested refreshed.
- Clear notes on deferred (full cuestionario, revisiones, editable matrix, PDF).

## Risks & Mitigations
- Exact matrix layout in legacy unknown: implement a clean, useful grouped table/matrix based on current fields (area + tipo as axes, scores as values). Can be refined later.
- No dedicated formula table in dev data: use stored fields directly for this first slice.
- Scope: keep as view-only matrix shell; editing in matrix and deeper flows later.

## Related / Handoff
- Delivers the "Aspectos matrix" item from current Suggested.
- After: can continue Documentación (editor/perfiles), do Auditorías execution, Mejora full integration, or more cross-cuts (filters, relations, full tree base, PDF reports).
- Good use of recent base + tree helpers for richer views.

---
*Part of the menu-driven modernization (Stage 8.x+ / 9.x).*

**Execution autonomous per AGENTS.md + testing strategy in STAGE-CHECKLISTS. Plan committed first. Docker-only. If git fails: retry.**

**Plan written on the feature branch (first commit).**