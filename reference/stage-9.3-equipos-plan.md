# Stage 9.3 — Equipos (first vertical slice under Aplicacion)

**Status**: Planning phase (plan committed first)
**Branch target**: `feat/stage-9.3-equipos` (fresh from master)
**Driver**: Next "One real Aplicacion vertical (medium)" from the living `.agents/MIGRATION-TODOS.md` Suggested Next Legs (after the XS Criterios Ambientales hygiene in 9.1 and Proveedores in 9.2). Follows the exact process ritual established in 9.0 and reinforced after 9.2 standards review.

## Goals (Aligned with Directives)
1. Deliver basic modern CRUD (list + nuevo/editar) for the core `equipos` entity using Catalog* bases + targeted overrides (no bypass).
2. Add the table definition + realistic demo seed via idempotent data patch 0021 (following 0013-clientes / 0020-proveedores exactly, with data_patches tracking).
3. Wire correct modern Phroute + legacy colon-derived routes (exact pattern from clientes/criterios/sedes, not the incomplete 9.2 Proveedores block).
4. As route hygiene discovered during this leg: correct the Proveedores list route on /admin/proveedores (and legacy) which was incorrectly pointing to Formulario::ShowPage instead of Listado (a standards gap that left the list view unreachable at the primary URL).
5. Clean templates matching current project style exactly (no custom flash rendering in module content blocks, consistent page headers, bottom stage notes, plural/singular var names, flashPrefix usage).
6. Extend verify-8.6.sh + add full "Stage 9.3 Verification Playbook" in STAGE-CHECKLISTS.md.
7. Update the living MIGRATION-TODOS.md (flip completed prior items for real, refresh snapshot + Suggested Next, add 9.3 note), plus MIGRATION-PLAN timeline.
8. 100% Docker-only, plan-first commit, reviewable PR size (1 vertical + 1 patch + routes fix + verify/docs).
9. Prove the list + form + POST + flashes + DB side-effects + legacy path resolution for the new module (and the fixed Proveedores list as bonus verification).

## Scope (First Slice — Reviewable Size)
**In:**
- Core Equipos entity: listado (numero, descripcion, modelo, ubicacion, activo + id) and basic form (add numero_serie/fabricante for completeness; maintenance fields like revisiones/calendario/planmantenimiento/mantenimientoprev/mantcorr deferred).
- New patch `0021-equipos-table-and-seed.sql` (CREATE IF NOT EXISTS with all legacy columns + 3 demo rows + data_patches entry).
- `Pages/Equipos/{Listado,Formulario}.php` (Listado: 4 protected + 2 small overrides; Formulario: full ShowPage/Procesar with proper field handling + activo in every query/POST/UPDATE/INSERT — correcting the incomplete pattern from 9.2).
- `templates/equipos/{listado,formulario}.twig` (copy/adapt from proveedores or clientes, exact visual + var conventions).
- Routes in index.php (5 modern + 1-2 legacy for equipos; + the 2-3 line fix for the broken /admin/proveedores list route).
- verify extension (tables include, count, patch filter, header).
- Full .agents/ updates (plan first, this file + 9.3 section in checklists, TODOS refresh, MIGRATION-PLAN note).
- All verification commands, php -l, clean-room + browser flows.

**Out (explicit future legs):**
- Sub-flows: revisiones, calendario, plan de mantenimiento, mantenimientos preventivos/correctivos, equipo auditor (links to Auditorias).
- Any FKs or joins to other modules in this leg.
- Homologation or other cross entities.
- Calendar UI component extraction (even if used here later).

## Pattern to Follow (Standards from 8.5–9.2 + 9.2 Post-Review Lessons)
1. **Data**: New patch 0021 modeled 1:1 on 0020 (CREATE, INSERT ... ON CONFLICT DO NOTHING, data_patches ON CONFLICT DO NOTHING). Include every column from legacy schema even if some left for later form iterations.
2. **Listado**: Extend CatalogListado, set the 4 protected strings (table='equipos', title='Equipos', templateDir='equipos', flashPrefix='equipo'), override getSelectSql() + mapRow() for the extra columns (base default is only id/nombre/activo).
3. **Formulario**: Extend CatalogFormulario, same 5 protected (incl listRoute='/admin/equipos'). Because of >3 columns and no 'nombre' column, provide full ShowPage + Procesar overrides (copy structure from base + from the 9.2 Formulario, but **include activo in SELECT for edit, in item map, in UPDATE SET, in INSERT cols, and bind the parsed value** — do not parse-and-ignore like the 9.2 gap).
4. **Routes**: Strictly follow the clientes/criterios/sedes block style:
   - GET /admin/equipos → Listado ShowPage
   - GET /admin/equipos/nuevo + /editar/{id} → Formulario ShowPage
   - POST .../nuevo + /editar/{id} → Formulario Procesar
   - Legacy: at least `/administracion/equipos/listado/ver` (and the administracion one if distinct) → Listado.
5. **Templates**: Extend layouts/app.twig. Use pageTitle, the plural var (equipos) for list loop, singular 'equipo' for form (comes from strtolower(flashPrefix) in base/custom). Table with ID/Número/Descripción/.../Estado/Acciones. Form groups + checkbox for activo + hidden id on edit + action URLs conditional on isEdit. Bottom .alert note with stage + deferred work. No flash rendering inside {% block content %}.
6. **Verification**: Extend the shared script + new dedicated playbook section here with exact docker/psql/browser/DB-assert steps. Always run full verify-8.6.sh after init.
7. **Docs + TODOS**: This plan first (committed before any code). Update TODOS to mark 9.1 Criterios Ambientales + 9.2 Proveedores as done (with stage refs), note this delivery, refresh Suggested (e.g. promote Documentación slice or Mejora/ Formación basic or next vertical). Add 9.3 section to checklists. Small MIGRATION-PLAN note.

**Key lesson applied from 9.2 review**: Route wiring, naming (Formulario.php not Form.php), Catalog base usage, full field coverage in custom forms (incl activo), template style, and living TODOS update are non-negotiable. The "Qwen delivered the table+pages but missed standards" pattern must not recur.

## Data Discovery (for Equipos)
- Legacy table (from scripts/qnovaintegraldumpvacio.sql): `equipos` with id (serial), numero (varchar10 NOT NULL), descripcion (varchar255 NOT NULL), numero_serie (varchar20 NOT NULL), modelo, fabricante, ubicacion, fuera_uso (bool), causa (text), fecha_fuera (date), ver_interna (bool NOT NULL), mantenimiento_cada (smallint NOT NULL), dias (bool NOT NULL), activo (bool).
- Menu entries (0004-full-legacy-menu.sql + 0005): top-level branch id 70 under Aplicacion with children 'equipos:listado:listado:ver', 'equipos:revision:listado:ver', 'equipos:calendario:listado:ver'; also 'administracion:equipos:listado:ver' (id 115). Labels updated to 'Equipos'.
- Primary list view in legacy highlighted "número de control y descripción".
- Buttons existed for Nuevo, Dar de Baja, Ver, Plan Mant., Mant. Preventivo, Mant. Correctivo, etc. (deferred).
- No prior patch created the table in our minimal data_patches sequence (unlike 0020 for proveedores), so 0021 will provide it + seed for reproducible dev DB + tracking.

## Verification Checklist (Non-Interactive + Human Gate)
- docker compose exec app php -l on new Pages + index.php + verify script.
- After clean init-db.sh: psql confirms 'equipos' table, 0021 in data_patches, >=3 rows with realistic data.
- verify-8.6.sh passes (tables list now includes it, count, no breakage of prior 9.1/9.2 asserts).
- Browser: after login, sidebar navigation to Equipos (under Aplicacion), list shows expected columns + "Nuevo Equipo", create works (required fields + activo), edit updates all chosen fields (incl flipping activo), success flash on return to list, legacy path resolves to modern list.
- Same flows for the (now fixed) Proveedores list URL to confirm it lands on list not a form.
- Post-submit DB SELECT matches submitted values.
- No PHP errors/warnings, flashes only via the prefix mechanism, templates use layout vars.

## Files To Be Created/Modified
**New**:
- `reference/stage-9.3-equipos-plan.md` (this; first commit)
- `docker/db-init/data-patches/0021-equipos-table-and-seed.sql`
- `Pages/Equipos/Listado.php`
- `Pages/Equipos/Formulario.php`
- `templates/equipos/listado.twig`
- `templates/equipos/formulario.twig`

**Modified**:
- `index.php` (routes block + the 9.2 list route correction)
- `scripts/verify-8.6.sh` (tables, counts, comments, header)
- `.agents/MIGRATION-TODOS.md` (checkboxes + snapshot + suggested refresh + stage notes)
- `.agents/STAGE-CHECKLISTS.md` (full 9.3 playbook section + todo json)
- `.agents/MIGRATION-PLAN.md` (Last Updated + short status)

## Detailed Execution Order (Strict — Plan First)
1. (This branch) Write + commit this plan.md as the absolute first change. No code before.
2. Create the 0021 patch.
3. Implement the two Page classes (Listado small, Formulario with correct field coverage).
4. Update index.php routes (Equipos new + fix for Proveedores).
5. Create the two twig templates (style match + stage notes).
6. Extend verify script.
7. Update all .agents/ files (TODOS first among docs so the living list reflects reality immediately).
8. Run full verification suite (clean room preferred).
9. Logical commits, push -u origin feat/stage-9.3-equipos, open PR with reference to the TODOS item being delivered and the 9.2 route hygiene included.
10. (Post-merge) Human confirms browser flows; flip final checkbox here if not already.

## Success Criteria
- Plan was the first commit on the branch (visible in git log).
- After init: equipos table + 0021 recorded + sample data present.
- /admin/equipos renders the list (using Listado), /nuevo and /editar render forms, POSTs work and persist all fields including activo.
- Same primary list URL for Proveedores now correctly uses Listado (fix landed).
- All legacy menu accions for the basic listado resolve.
- verify-8.6.sh + targeted psql + php -l green.
- Full playbook evidence + updated living TODOS + no open "NOTE FOR LATER" style gaps.
- PR is self-contained, reviewable size, follows every ritual bullet in MIGRATION-TODOS.md "How to Use".

## Risks & Mitigations
- Table may have slight differences in production vs the dump schema we used for CREATE — use CREATE TABLE IF NOT EXISTS + exact types from the authoritative dump; test via the clean init in verify.
- Form length: with 6-7 fields it will be taller than proveedores but still simple catalog; document in the stage note that this is the core entity slice.
- Sidebar menu entry for "Equipos": relies on existing legacy menu data (70 + children) + MainPage renderer + LegacyAction fallback for any unmapped; the new routes + legacy slash mappings should make the main flow modern.
- Activo / required fields: explicit validation only on numero + descripcion (mirrors legacy primary); other NOT NULLs will be satisfied by demo data and form defaults.

## Related / Handoff
- Directly delivers the next vertical item called out in MIGRATION-TODOS Suggested + the "Equipos (legacy 70)" entry in the functional area groups.
- Reinforces post-9.2 lesson: TODOs prove delivery is possible; only strict adherence to naming, bases, routes, field coverage, template style, and living-list updates proves the standards.
- After this, Suggested will point to Documentación (high value, larger, tree/strangler approach) or Mejora/ Formación slices or sub-entities for Equipos/Proveedores.
- Any new discovery (extra accions, related tables surfacing in menu) will be added to TODOS immediately.

---

*Part of the menu-driven modernization (Stage 8.x+ / 9.x). Uses the daily MIGRATION-TODOS navigation introduced in 9.0. "the size of PRs we want from now on".*

**Execution autonomous per AGENTS.md + the testing strategy in STAGE-CHECKLISTS. Plan committed first. Docker-only. If git fails: retry.**

**Plan written on the feature branch (first commit).**