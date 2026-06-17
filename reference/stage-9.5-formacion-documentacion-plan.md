# Stage 9.5 — Formación basic (Planes) + Documentación initial shell (landing + list)

**Status**: Planning phase (plan committed first)
**Branch target**: `feat/stage-9.5-formacion-documentacion` (fresh from master)
**Driver**: Next two items from the living `.agents/MIGRATION-TODOS.md` Suggested Next Legs after 9.4 (Mejora). Explicitly "continue with both": 
  - **Formación focused leg** (first slice: planes using plan_formacion, which is catalog-like with nombre + activo + extras).
  - **Documentación slice** (first shell per strangler guidance: modern landing + basic list for the core `documentos` table; keep legacy tree/editor/arbol + heavy subs for now).

Follows the exact process ritual (plan first, Docker-only, living docs, full verify/playbook, reviewable size).

## Goals (Aligned with Directives)
1. Advance **both** remaining high-priority Aplicacion items in one reviewable PR (mix of focused vertical slice + high-value shell).
2. Formación: basic modern CRUD for `plan_formacion` (Planes de Formación) — nombre + activo + vigente/descripcion/quality flags. Use/extend Catalog* + targeted overrides.
3. Documentación: first strangler step — modern shell/landing + basic list over `documentos` (id/nombre/codigo/estado/activo + core fields). Full tree, approval workflows, perfil permission arrays, editor, and sub-accions (manual/politica/docvigor/etc.) remain on legacy for this leg.
4. Patches 0023 + 0024 (idempotent table+seed + data_patches).
5. Correct modern + legacy routes (map key accions from 0004: formacion:planes + several documentacion:*listado).
6. Templates + Pages exactly matching style (with stage notes explaining deferred parts).
7. Extend verify + full "Stage 9.5 Verification Playbook".
8. Update living MIGRATION-TODOS (flip both), MIGRATION-PLAN, STAGE-CHECKLISTS.
9. 100% Docker-only, plan-first commit, clean verification, PR sized as "1-2 items".

## Scope (Two Items — Reviewable)
**In (Formación / Planes):**
- New patch `0023-plan-formacion-table-and-seed.sql` (columns from legacy + 3 demo rows).
- `Pages/Formacion/{Listado,Formulario}.php` (overrides for all fields; list shows nombre/vigente/activo/etc.).
- `templates/formacion/{listado,formulario}.twig`.
- Routes: `/admin/formacion` + legacy for 'formacion:planes:listado:ver' (and general if needed).

**In (Documentación shell):**
- New patch `0024-documentos-table-and-seed.sql` (core columns + 3 demo rows; arrays handled).
- `Pages/Documentacion/{Listado,Formulario}.php` (basic overrides; list primary display fields; form for core editable fields only).
- `templates/documentacion/{listado,formulario}.twig` (or landing.twig for shell feel; list + note).
- Routes: `/admin/documentacion` + 2-3 key legacy (e.g. documentacion:docvigor:listado:ver, documentacion:docborrador:listado:ver, and/or top documentacion).

**Shared:**
- verify-8.6.sh updates (both tables, both Pages, counts, patches 0023/0024).
- Full 9.5 playbook in STAGE-CHECKLISTS.
- MIGRATION-TODOS flips + Suggested refresh (next candidates: cross-cut, Auditorias/Aspectos, etc.).
- MIGRATION-PLAN Last Updated.
- reference plan (this; first commit).

**Out (explicit future legs):**
- Formación: cursos table, alumnos/inscripciones, reqpuesto, fichapersonal, full links to Empleados, calendario.
- Documentación: tree/arbol UI, full editor (FCK/ modern), approval workflow (revisado/aprobado), perfil_* bool arrays editing, all sub-accions (manual, politica, pg, pe, frl, registros, formatos, normativa, aai, planamb, etc.), PDF/GenPDF, versioning, search.
- Any new base extraction for tree or permission matrices (deferred per backlog).
- Integration with other modules.

## Pattern to Follow
- Same as 9.3/9.4: plan first, patch with full columns + synthetic realistic seeds + ON CONFLICT, Catalog* + overrides where columns != simple id/nombre/activo, full field coverage in custom Form (even if partial for shell), exact template style (vars from templateDir/flashPrefix, bottom stage .alert notes, no flashes in content, consistent buttons/headers).
- Routes: after previous Mejora block, using the exact clientes/equipos/Mejora pattern.
- For Documentación "shell": the list will be intentionally basic (selected columns); Form covers nombre/codigo/estado/activo + a few; heavy fields noted as deferred. Landing can be the list itself or a light wrapper page.
- For Formación "Planes": plan_formacion is close to catalog (nombre + activo present), but we still override getSelect/map + full Show/Procesar to handle vigente, descripcion, calidad, medioambiente cleanly.
- Verification: clean room + psql for both tables/patches/rows + verify-8.6.sh + php -l + browser flows for both areas + no regression.
- Docs: always update the living TODOS in the PR.

## Data Discovery
**Formación / Planes:**
- `plan_formacion` (from qnovaintegraldumpvacio.sql): id SERIAL, nombre VARCHAR(128), vigente BOOLEAN, descripcion TEXT, activo BOOLEAN, calidad BOOLEAN, medioambiente BOOLEAN.
- Menu (0004): under formacion branch (labels FORMACION), specific 'formacion:planes:listado:ver' (and siblings for cursos, inscripcion, fichapersonal, reqpuesto — this leg picks planes as the clean first slice).
- Tipocursos (catalog) already modern; this adds the plan level.
- No prior patch — will be 0023.

**Documentación:**
- `documentos` (core table): id, nombre, codigo, estado, revisado_por, aprobado_por, revision, activo, tipo_documento, area, calidad, medioambiente, perfil_ver/ nueva/ modificar/ revisar/ aprobar/ historico/ tareas (BOOLEAN[]), fecha_revision, fecha_aprobacion.
- Many sub-listados (docvigor, docborrador, manual, politica, pg, pe, frl, registros, legislacion, docformatos, aai, normativa, planamb...).
- Legacy drivers: arbol_documentos.php, editor.php, permisos_documentos, etc. (all deferred).
- Suggested first: modern shell + basic list for the top accion(s). We will provide a usable basic list + minimal form over the main table; tree + complex fields + all subs stay legacy.
- No prior patch for this table in our sequence.

## Verification Checklist (Non-Interactive + Human Gate)
- php -l on new Pages/* + index.php + verify.
- After clean init: both tables present, 0023+0024 in data_patches, >=3 rows each.
- verify-8.6.sh fully green (new tables/counts in the lists, no breakage on prior 9.1-9.4 asserts or Personalizacion menu invariants).
- Browser (after login):
  - Formación: sidebar to Formación/Planes → list (nombre, vigente, activo etc), create/edit with all fields, flash, legacy path works, DB matches.
  - Documentación: sidebar to Documentación → landing/list shows basic cols (nombre/codigo/estado/activo), create/edit core fields, flash, key legacy paths resolve to modern where mapped, DB matches. Note on page: "Tree, full editor, approval workflow and other documentacion subs remain on legacy".
- No PHP errors, flashes via prefix, prior modules untouched.
- Post-submit selects confirm values.

## Files To Be Created/Modified
**New**:
- `reference/stage-9.5-formacion-documentacion-plan.md` (this; first)
- `docker/db-init/data-patches/0023-plan-formacion-table-and-seed.sql`
- `docker/db-init/data-patches/0024-documentos-table-and-seed.sql`
- `Pages/Formacion/Listado.php`, `Pages/Formacion/Formulario.php`
- `Pages/Documentacion/Listado.php`, `Pages/Documentacion/Formulario.php`
- `templates/formacion/listado.twig`, `templates/formacion/formulario.twig`
- `templates/documentacion/listado.twig`, `templates/documentacion/formulario.twig` (or landing emphasis)

**Modified**:
- `index.php` (routes blocks for both)
- `scripts/verify-8.6.sh` (php -l, tables IN, counts, patch comments, header)
- `.agents/MIGRATION-TODOS.md` (flip both, refresh Suggested + snapshot + notes)
- `.agents/STAGE-CHECKLISTS.md` (full 9.5 section + todo json)
- `.agents/MIGRATION-PLAN.md` (Last Updated)

## Detailed Execution Order (Strict — Plan First)
1. (This branch) Write + commit this plan as absolute first change.
2. Create the two patches (0023 for planes, 0024 for documentos).
3. Implement the four Page classes (Formacion closer to catalog; Documentacion intentionally basic shell).
4. Update index.php routes (both modules).
5. Create the four (or landing) twig templates with stage notes.
6. Extend verify script.
7. Update all .agents/ (TODOS first).
8. Full verification (clean room + targeted psql + verify-8.6.sh + php -l + browser flows for **both**).
9. Logical commits, push -u, open PR referencing the two TODOS items + this plan.
10. (Post-merge) Human browser sign-off; flip checkboxes if needed.

## Success Criteria
- Plan is the first commit.
- After init: plan_formacion + documentos tables + patches 0023/0024 + sample rows.
- /admin/formacion and /admin/documentacion work (list + form where provided + flashes + persistence).
- Key legacy paths resolve.
- verify-8.6.sh + psql + php -l all green; no regression on anything prior (9.4 Mejora, Equipos, etc.).
- Living TODOS has both items marked + Suggested refreshed.
- PR self-contained, follows every ritual point, delivers working increments for **both** items.

## Risks & Mitigations
- Documentación complexity (arrays, workflow, tree): explicitly scoped as shell only; stage note + deferred list is clear. Basic list still provides immediate modern value + navigation entry.
- Two tables/patches in one leg: kept minimal seeds + basic field sets per module so total size matches prior "1-2 items" model.
- Naming for Formación (planes vs cursos): chose plan_formacion because it is the simplest catalog-like (nombre+activo) for a fast win; cursos can be next slice.
- Route coverage: map the primary planes accion + representative documentacion listados; menu sidebar will use what we map + LegacyAction fallback for unmapped subs.

## Related / Handoff
- Delivers exactly the two bullets called out in Suggested Next.
- After this: Suggested will point to cross-cut extraction, Auditorías/Aspectos, Indicadores, Procesos, or deeper sub-entities for recently added modules.
- Reinforces: even for a "larger" item (Documentación), we deliver a safe strangler first slice + full ritual.

---
*Part of the menu-driven modernization (Stage 8.x+ / 9.x). Uses the daily MIGRATION-TODOS navigation.*

**Execution autonomous per AGENTS.md + testing strategy in STAGE-CHECKLISTS. Plan committed first. Docker-only. If git fails: retry.**

**Plan written on the feature branch (first commit).**