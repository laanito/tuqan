# Stage 9.9 — Indicadores basic slice (core `indicadores` list + form)

**Status**: Planning phase (plan committed first)
**Branch target**: `feat/stage-9.9-indicadores` (fresh from master)
**Driver**: Next from `.agents/MIGRATION-TODOS.md` Suggested Next Legs after 9.8 (cross-cut first delivery). "Next verticals": Indicadores (legacy 72) + Objetivos.

Follows the exact process ritual (plan first, Docker-only, living docs, full verify/playbook, reviewable size). Leverages the improved Catalog* bases from 9.8.

## Goals (Aligned with Directives)
1. Advance the next **vertical**: basic modern CRUD for the core `indicadores` table (KPIs / indicators definition, targets, tolerances, responsibilities, frequencies).
2. Use the enhanced Catalog* from 9.8 (helpers for DB, context, load/persist etc.) + overrides where needed for the rich fields.
3. Patch 0027 (idempotent table+seed + data_patches).
4. Modern + legacy routes (map 'indicadores:indicadores:listado:ver' and related).
5. Templates + Pages with stage notes (charts, calculations, metas_indicadores, objetivos, dashboard deferred).
6. Extend verify + full "Stage 9.9 Verification Playbook".
7. Update living MIGRATION-TODOS (flip Indicadores), MIGRATION-PLAN, STAGE-CHECKLISTS.
8. 100% Docker-only, plan-first commit, clean verification, reviewable PR.

## Scope (Reviewable basic vertical slice)
**In:**
- New patch `0027-indicadores-table-and-seed.sql` (columns from schema + 3-4 demo rows).
- `Pages/Indicadores/{Listado,Formulario}.php` (using enhanced base + overrides for all fields).
- `templates/indicadores/{listado,formulario}.twig`.
- Routes: `/admin/indicadores` + key legacy.
- verify-8.6.sh updates + full 9.9 playbook.
- TODOS flip + Suggested refresh (deeper on existing or Procesos next).
- MIGRATION-PLAN update.
- This plan (first commit).

**Out (explicit future legs):**
- Full Objetivos + Metas (metas_indicadores, objetivos_indicadores).
- Calculations, tolerances logic, "genera_objetivo".
- Charts / graphs (graficaIndicadores.php, generadorGraficas — may stay or move later).
- Matriz view, PDF reports, dashboard integration.
- Integration with other modules (Aspectos, Auditorias, etc.).

## Pattern to Follow
- Same as 9.6/9.7/9.8: plan first, patch with full columns + synthetic seeds + ON CONFLICT.
- Leverage 9.8 base helpers where possible to keep subclass code smaller than pre-9.8 rich modules.
- For complex fields: full getSelectSql/mapRow, custom load/persist if needed.
- Exact template style + explicit stage .alert notes on deferred parts.
- Routes after 9.8 block.
- Use `/admin/indicadores`, flashPrefix 'indicador' or 'indicadores', templateDir 'indicadores', title 'Indicadores'.
- Verification: clean room + psql (table/patch/rows) + verify-8.6.sh + php -l + browser flow + no regression on priors (incl. 9.8 bases).
- Update living TODOS in the PR.

## Data Discovery
- `indicadores` (from 00-schema.sql): id, nombre, definicion, valor_inicial, tecnica, variables_control, activo, frecuencia_seg, frecuencia_ana, genera_objetivo, responsable_analisis, responsable_seguimiento, valor_tolerable, valor_tolerable2, valor_objetivo.
- Menu (0004): under 72 'indicadores', main 'indicadores:indicadores:listado:ver', also objetivos sibling.
- Related tables: metas_indicadores, objetivos_indicadores (deferred).
- Old drivers: graficaIndicadores.php etc. (deferred).
- No prior patch for this table — will be 0027.
- Demo seeds: realistic indicators e.g. "Tasa de defectos", "Consumo energético", with mix of activo, values, responsables.

## Verification Checklist (Non-Interactive + Human Gate)
- php -l on new Pages/* + Catalog bases (to confirm 9.8 still good) + index.php + verify.
- After clean init: indicadores table + 0027 + >=3 rows.
- verify-8.6.sh green (new table/counts, no breakage on 9.1-9.8 including cross-cut bases and menu invariants).
- Browser (after login):
  - Sidebar to Indicadores → list (nombre, definicion, valores, activo etc.), create/edit all fields, flash, legacy path works, DB matches.
  - Note: Charts, objetivos, full calculations and dashboard remain legacy for now.
- No PHP errors; priors (incl. Aspectos, cross-cut bases) untouched.
- Post-submit selects confirm values.

## Files To Be Created/Modified
**New**:
- `reference/stage-9.9-indicadores-plan.md` (this; first)
- `docker/db-init/data-patches/0027-indicadores-table-and-seed.sql`
- `Pages/Indicadores/Listado.php`, `Pages/Indicadores/Formulario.php`
- `templates/indicadores/listado.twig`, `templates/indicadores/formulario.twig`

**Modified**:
- `index.php` (routes block)
- `scripts/verify-8.6.sh`
- `.agents/MIGRATION-TODOS.md` (flip Indicadores, update Suggested)
- `.agents/STAGE-CHECKLISTS.md` (full 9.9 section)
- `.agents/MIGRATION-PLAN.md` (Last Updated)

## Detailed Execution Order (Strict — Plan First)
1. Write + commit this plan as absolute first change.
2. Create patch 0027.
3. Implement the two Page classes (using 9.8 helpers + overrides).
4. Update index.php routes.
5. Create the two twig templates with stage notes.
6. Extend verify script.
7. Update all .agents/ (TODOS first).
8. Full verification (clean room + psql + verify-8.6.sh + php -l + browser flow).
9. Logical commits, push -u, open PR referencing the TODOS item + this plan.
10. (Post-merge) Human browser sign-off; flip checkbox.

## Success Criteria
- Plan first commit.
- After init: indicadores table + 0027 + rows.
- /admin/indicadores works (list + form + flashes + persistence) using improved bases.
- Legacy path resolves.
- verify + psql + php -l green; no regression (incl. 9.8 catalog helpers).
- Living TODOS has Indicadores marked + Suggested refreshed (e.g. Procesos or deeper Aspectos/Auditorias).
- Clear notes on deferred (charts, objetivos, etc.).

## Risks & Mitigations
- Charts/calculations: scoped out; provide basic data CRUD first.
- Multiple related tables (metas, objetivos): focus on core indicadores for this reviewable leg; siblings in follow-up.
- Using new base: new code will demonstrate the 9.8 improvements.

## Related / Handoff
- Delivers the first "Next verticals" item.
- After this: Suggested can promote Procesos, deeper (Mejora full, Aspectos matrix, Documentación tree, Auditorías execution), or continue cross-cut (filters etc.).
- Reinforces incremental + leveraging prior cross-cuts.

---
*Part of the menu-driven modernization (Stage 8.x+ / 9.x).*

**Execution autonomous per AGENTS.md + testing strategy in STAGE-CHECKLISTS. Plan committed first. Docker-only. If git fails: retry.**

**Plan written on the feature branch (first commit).**