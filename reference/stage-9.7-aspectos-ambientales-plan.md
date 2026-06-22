# Stage 9.7 — Aspectos Ambientales basic slice (core `aspectos` list + form)

**Status**: Planning phase (plan committed first)
**Branch target**: `feat/stage-9.7-aspectos-ambientales` (fresh from master)
**Driver**: Next from the living `.agents/MIGRATION-TODOS.md` Suggested Next Legs after 9.6 (Auditorías basic). "Next verticals": Aspectos Ambientales (legacy 73, linked to Criterios).

Follows the exact process ritual (plan first, Docker-only, living docs, full verify/playbook, reviewable size).

## Goals (Aligned with Directives)
1. Advance the next **Aplicacion vertical**: basic modern CRUD shell for the core `aspectos` table (Aspectos Ambientales main list/matrix entry point).
2. Use/extend Catalog* + full overrides for the multi-field table (nombre + several score ints + tipo + activo + observaciones).
3. Patch 0026 (idempotent table+seed + data_patches).
4. Modern + legacy routes (map primary accions from 0004: maspectos + aambientales:* ).
5. Templates + Pages exactly matching style (with stage notes explaining deferred matrix, questionnaire, revisiones).
6. Extend verify + full "Stage 9.7 Verification Playbook".
7. Update living MIGRATION-TODOS (flip Aspectos item), MIGRATION-PLAN, STAGE-CHECKLISTS.
8. 100% Docker-only, plan-first commit, clean verification, PR sized as one vertical basic slice.

## Scope (Reviewable basic vertical slice)
**In:**
- New patch `0026-aspectos-table-and-seed.sql` (columns from legacy schema + 3 demo rows).
- `Pages/Aspectos/{Listado,Formulario}.php` (overrides for all core fields).
- `templates/aspectos/{listado,formulario}.twig`.
- Routes: `/admin/aspectos` + representative legacy for maspectos / aambientales paths.
- verify-8.6.sh updates (table, count, patch, php-l, header).
- Full 9.7 playbook in STAGE-CHECKLISTS.
- MIGRATION-TODOS flip + Suggested refresh (cross-cut extraction now strongly primed; next: Indicadores, Procesos, deeper subs).
- MIGRATION-PLAN Last Updated.
- reference plan (this; first commit).

**Out (explicit future legs):**
- Full matrix view, revisiones, "Aspectos Ambientales Emergencia".
- Cuestionario integration (cuestionario.php flows).
- Proper joins / friendly names for magnitud/gravedad/frecuencia/tipo_aspecto/impacto (supporting lookup tables).
- Formula/significancia calculation (see crearExcel etc.).
- Links to Criterios, full workflow.
- The admin "Aspectos Ambientales" config catalogs (magnitud etc.) can be separate small legs or alongside.

## Pattern to Follow
- Same as 9.4-9.6: plan first, patch with full columns + synthetic realistic seeds + ON CONFLICT, Catalog* + overrides (no default id/nombre/activo only), full field coverage in Form, exact template style (stage notes at bottom, no inner flashes).
- Routes after the 9.6 Auditorias block.
- Use `/admin/aspectos` , flashPrefix 'aspectos', templateDir 'aspectos', title 'Aspectos Ambientales'.
- Verification: clean room + psql (table/patch/rows) + verify-8.6.sh + php -l + browser flow + no regression.
- Docs: update living TODOS.

## Data Discovery
- `aspectos` (core from 00-schema.sql): id, nombre VARCHAR(256), magnitud/gravedad/frecuencia/probabilidad/severidad SMALLINT, tipo_aspecto INTEGER, activo BOOLEAN, impacto INTEGER, area VARCHAR(128), observaciones TEXT.
- Menu (0004): top 'maspectos' (73) under Aplicacion, children including 'aambientales:revision:listado:ver', 'aambientales:matriz:detalles:ver', 'aambientales:revisionemergencia:listado:ver'.
- Also admin config lists (magnitud, gravedad...) under 95.
- Linked heavily to tipo_* tables + Criterios + questionnaire for revision/matrix.
- No prior patch — 0026.
- Seeds: 3 demo aspectos with mix of values (e.g. different magnitudes, one inactive).

## Verification Checklist (Non-Interactive + Human Gate)
- php -l on new Pages/* + index.php + verify.
- After clean init: aspectos table present, 0026 in data_patches, >=3 rows.
- verify-8.6.sh fully green (new table/count, no breakage on 9.1-9.6 or Personalizacion invariants).
- Browser (after login):
  - Sidebar to Aspectos Ambientales → list (nombre + scores + activo), create/edit core fields, flash, legacy paths resolve where mapped, DB matches.
  - Note on page: matrix, revisiones, cuestionario and full FK resolution deferred.
- No PHP errors; prior modules (incl. 9.6 Auditorias, Criterios) untouched.
- Post-submit selects confirm persisted values.

## Files To Be Created/Modified
**New**:
- `reference/stage-9.7-aspectos-ambientales-plan.md` (this; first)
- `docker/db-init/data-patches/0026-aspectos-table-and-seed.sql`
- `Pages/Aspectos/Listado.php`, `Pages/Aspectos/Formulario.php`
- `templates/aspectos/listado.twig`, `templates/aspectos/formulario.twig`

**Modified**:
- `index.php` (routes block)
- `scripts/verify-8.6.sh`
- `.agents/MIGRATION-TODOS.md` (flip Aspectos Ambientales, update snapshot + Suggested)
- `.agents/STAGE-CHECKLISTS.md` (full 9.7 section)
- `.agents/MIGRATION-PLAN.md` (Last Updated)

## Detailed Execution Order (Strict — Plan First)
1. Write + commit this plan as absolute first change.
2. Create the patch 0026.
3. Implement two Page classes (full overrides for the non-simple fields).
4. Update index.php routes.
5. Create the two twig templates with stage notes.
6. Extend verify script.
7. Update all .agents/ (TODOS first).
8. Full verification (clean room + psql + verify-8.6.sh + php -l + browser flow).
9. Logical commits, push -u, open PR referencing the TODOS item + this plan.
10. (Post-merge) Human browser sign-off; flip checkbox.

## Success Criteria
- Plan first commit.
- After init: aspectos + 0026 + rows.
- /admin/aspectos works (list + form + persistence + flashes).
- Key legacy paths resolve or gracefully fallback.
- verify + psql + php -l green; no regression.
- Living TODOS updated + Suggested refreshed (cross-cut now highlighted).
- Clear notes on deferred work.

## Risks & Mitigations
- Table complexity (multiple score fields + FKs): treat as basic shell (raw values in form/list for this leg); document future nice selects + matrix view.
- Questionnaire/matrix dependency: explicitly scoped out; first leg provides usable list entry point + navigation.
- Supporting tables (tipo_aspectos etc.): defer; can be added in follow-up or as small catalogs if they surface in menu.

## Related / Handoff
- Delivers the "Aspectos Ambientales" starter from Suggested Next.
- After this: cross-cut extraction is the clear next candidate (several verticals + one shell done; good time for list-with-filters or form-relations base).
- Continues the menu-driven incremental modernization.

---
*Part of the menu-driven modernization (Stage 8.x+ / 9.x).*

**Execution autonomous per AGENTS.md + testing strategy in STAGE-CHECKLISTS. Plan committed first. Docker-only. If git fails: retry.**

**Plan written on the feature branch (first commit).**