# Stage 9.10 — Procesos basic slice (core `procesos` table + list/form shell)

**Status**: Planning phase (plan committed first)
**Branch target**: `feat/stage-9.10-procesos` (fresh from master)
**Driver**: Next from `.agents/MIGRATION-TODOS.md` Suggested Next Legs after 9.9 (Indicadores). "Next verticals": Procesos (legacy 76), or deeper...

Follows the exact process ritual (plan first, Docker-only, living docs, full verify/playbook, reviewable size). Leverages the improved Catalog* bases from 9.8.

## Goals (Aligned with Directives)
1. Advance the next **vertical**: basic modern CRUD for the core `procesos` table (catalog of processes and sub-processes).
2. Use the enhanced Catalog* from 9.8 (helpers) + minimal overrides.
3. Patch 0028 (idempotent table+seed + data_patches).
4. Modern + legacy routes (map the key 'procesos:catalogos:arbol:ver' and related).
5. Templates + Pages with stage notes (tree/arbol view, flujogramas, contenido_procesos details, indicators-per-process, approval/ficha/matrix deferred).
6. Extend verify + full "Stage 9.10 Verification Playbook".
7. Update living MIGRATION-TODOS (flip Procesos), MIGRATION-PLAN, STAGE-CHECKLISTS.
8. 100% Docker-only, plan-first commit, clean verification, reviewable PR.

## Scope (Reviewable basic vertical slice)
**In:**
- New patch `0028-procesos-table-and-seed.sql` (columns from schema + 3-4 demo rows, including padre for hierarchy hint).
- `Pages/Procesos/{Listado,Formulario}.php` (using enhanced base + overrides for core fields).
- `templates/procesos/{listado,formulario}.twig`.
- Routes: `/admin/procesos` + key legacy (arbol ver mapped to the list shell).
- verify-8.6.sh updates + full 9.10 playbook.
- TODOS flip + Suggested refresh (next: deeper on Procesos/Documentación or other pending verticals like full Mejora/Auditorias execution).
- MIGRATION-PLAN update.
- This plan (first commit).

**Out (explicit future legs):**
- Full Árbol de Procesos / tree UI + drag-drop or hierarchy management (padre usage + generador_arboles / Procesar_Arbol).
- `contenido_procesos` (rich details: entradas/salidas/proveedor/cliente/doc_asociada/flujograma/indicadores arrays etc.).
- `flujogramas` table + visual flow.
- Indicadores de proceso, Ficha, Matriz, Baja, Revisar/Aprobar workflows.
- Links to Documentación (anexos), other modules.
- Any special "catalogo:proceso:*" legacy variants if surfaced.

## Pattern to Follow
- Same as 9.5 Documentación shell / 9.9 Indicadores: plan first, patch with full columns + synthetic seeds + ON CONFLICT.
- Leverage 9.8 base helpers to keep code small.
- Simple fields (nombre, codigo, revision, padre, activo) — list subset + form full.
- Exact template style + explicit stage .alert notes on deferred (tree, flujos, etc.).
- Routes after Indicadores 9.9 block.
- Use `/admin/procesos`, flashPrefix 'proceso', templateDir 'procesos', title 'Procesos'.
- Verification: clean room + psql (table/patch/rows) + verify-8.6.sh + php -l + browser flow + no regression on prior modules (incl. 9.9 Indicadores + 9.8 bases).
- Update living TODOS in the PR.

## Data Discovery
- `procesos` (from 00-schema-clean.sql): id SERIAL, nombre VARCHAR(64), revision VARCHAR(16), padre INTEGER, codigo VARCHAR(32), activo BOOLEAN DEFAULT TRUE.
- Related (deferred): contenido_procesos (links via proceso FK, has indicadores[], flujograma, many TEXT fields), flujogramas (links to proceso).
- Menu (0001/0004/0005): top 76 'procesos', child 400 'procesos:catalogos:arbol:ver' (Árbol de Procesos / Catálogo).
- Buttons in full legacy reference 'catalogo:proceso:*' and 'procesos:catalogo:comun:*' variants (main actionable mapped for now).
- Old drivers: generador_arboles.php, Procesar_Arbol.php, arbol views (deferred).
- No prior patch for `procesos` table in our sequence — will be 0028.
- Demo seeds: 3-4 rows e.g. main "Proceso de Diseño", "Subproceso Revisión", with padre relations and mix of activo/revision/codigo.

## Verification Checklist (Non-Interactive + Human Gate)
- php -l on new Pages/* + Catalog bases (to confirm 9.8 still good) + index.php + verify.
- After clean init: procesos table + 0028 + >=3 rows.
- verify-8.6.sh green (new table/counts, no breakage on 8.x-9.9 including cross-cut bases and menu invariants).
- Browser (after login):
  - Sidebar / Procesos → list (nombre, codigo, revision, padre, activo), create/edit all core fields, flash, legacy arbol path resolves to modern list, DB matches.
  - Note: Full tree/arbol rendering, sub-contenido, flujogramas, per-process indicators and ficha/matrix remain legacy for now.
- No PHP errors; priors (incl. Indicadores, Aspectos, bases) untouched.
- Post-submit selects confirm values.

## Files To Be Created/Modified
**New**:
- `reference/stage-9.10-procesos-plan.md` (this; first)
- `docker/db-init/data-patches/0028-procesos-table-and-seed.sql`
- `Pages/Procesos/Listado.php`, `Pages/Procesos/Formulario.php`
- `templates/procesos/listado.twig`, `templates/procesos/formulario.twig`

**Modified**:
- `index.php` (routes block after 9.9)
- `scripts/verify-8.6.sh`
- `.agents/MIGRATION-TODOS.md` (flip Procesos item, update Suggested + snapshot)
- `.agents/STAGE-CHECKLISTS.md` (full 9.10 section)
- `.agents/MIGRATION-PLAN.md` (Last Updated)

## Detailed Execution Order (Strict — Plan First)
1. Write + commit this plan as absolute first change.
2. Create patch 0028.
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
- After init: procesos table + 0028 + rows.
- /admin/procesos works (list + form + flashes + persistence) using improved bases.
- Legacy path resolves.
- verify + psql + php -l green; no regression (incl. 9.9 + 9.8 catalog helpers).
- Living TODOS has Procesos marked + Suggested refreshed (e.g. Documentación tree, full Mejora, Aspectos matrix, Auditorías execution, or cross-cuts).
- Clear notes on deferred (árbol, flujogramas, contenido, etc.).

## Risks & Mitigations
- Tree/hierarchy (padre + arbol): scoped out; basic flat list + form for core catalog data first. Future leg will modernize the arbol experience.
- Related rich tables (contenido_procesos, flujogramas): defer; provide usable basic process catalog entry point now.
- Mixed legacy accion names (procesos:catalogos vs catalogo:proceso): map the primary one from current menu data; add others if they surface in testing.

## Related / Handoff
- Delivers the first "Next verticals" item (Procesos).
- After this: Suggested can promote deeper Procesos work, Documentación tree completion, or other open verticals (Mejora full, Auditorías execution, Aspectos matrix) or cross-cut continuation.
- Reinforces incremental + leveraging prior cross-cuts (9.8 bases make this shell short).

---
*Part of the menu-driven modernization (Stage 8.x+ / 9.x).*

**Execution autonomous per AGENTS.md + testing strategy in STAGE-CHECKLISTS. Plan committed first. Docker-only. If git fails: retry.**

**Plan written on the feature branch (first commit).**
