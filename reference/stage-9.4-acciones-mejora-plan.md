# Stage 9.4 — Acciones de Mejora (basic CRUD slice under Mejora / legacy 68)

**Status**: Planning phase (plan committed first)
**Branch target**: `feat/stage-9.4-acciones-mejora` (fresh from master)
**Driver**: Next "Small vertical or next Aplicacion" from the living `.agents/MIGRATION-TODOS.md` Suggested Next Legs (after 9.1 Criterios Ambientales hygiene, 9.2 Proveedores, 9.3 Equipos). Follows the exact process ritual established in 9.0 and reinforced after 9.2/9.3.

## Goals (Aligned with Directives)
1. Deliver basic modern CRUD (list + nuevo/editar + POST + flashes) for the core `acciones_mejora` entity using Catalog* bases + targeted overrides (no bypass, full field lifecycle in custom classes).
2. Add the table definition + realistic demo seed via idempotent data patch 0022 (following 0020/0021 exactly, with data_patches tracking).
3. Wire correct modern Phroute + legacy colon-derived routes (exact clientes/equipos pattern for the primary 'mejora:listado:listado:ver' accion).
4. Clean templates matching current project style exactly (templateDir='mejora', flashPrefix='mejora', list var from templateDir, singular item key from flashPrefix, bottom stage notes, consistent headers/buttons).
5. Extend verify-8.6.sh + add full "Stage 9.4 Verification Playbook" in STAGE-CHECKLISTS.md.
6. Update the living MIGRATION-TODOS.md (flip the Mejora basic item, refresh snapshot + Suggested Next, add 9.4 note), plus MIGRATION-PLAN timeline.
7. 100% Docker-only, plan-first commit, reviewable PR size (1 vertical + 1 patch + routes + verify/docs).
8. Prove the list + form + POST + flashes + DB side-effects + legacy path resolution for the new module.

## Scope (First Slice — Reviewable Size)
**In:**
- Core Mejora entity `acciones_mejora`: listado (key cols: id, fecha, descripcion, area, cerrada, tipo) and basic form (core data-entry fields: fecha, tipo, cliente, descripcion, analisis, requiere_tratamiento, tratamiento, accion_preventiva, fecha_implantacion, plazo, coste, cerrada, area, observaciones).
- New patch `0022-acciones-mejora-table-and-seed.sql` (CREATE IF NOT EXISTS with all legacy columns + 3 demo rows + data_patches entry).
- `Pages/Mejora/{Listado,Formulario}.php` (Listado: 4 protected + getSelectSql/mapRow overrides; Formulario: full ShowPage/Procesar with proper multi-field handling, no 'nombre'/'activo' assumptions, validation on descripcion+fecha, correct UPDATE/INSERT subsets).
- `templates/mejora/{listado,formulario}.twig` (copy/adapt from equipos, exact visual + var conventions; use "mejora" container for list loop, "mejora" singular for form record).
- Routes in index.php (modern /admin/mejora + /nuevo/editar + POSTs + legacy /administracion/mejora/listado/ver to match the 0004 menu accion).
- verify extension (tables include 'acciones_mejora', row count, patch 0022, php -l, header).
- Full .agents/ updates (plan first, this file + 9.4 section in checklists, TODOS refresh with flip + snapshot + suggested, MIGRATION-PLAN note).
- All verification commands, php -l, clean-room + browser flows.

**Out (explicit future legs):**
- FK resolution / dropdowns (tipo from tiposmejora, cliente from clientes, usuario_* assignments).
- Workflow actions (set usuario_detectado/verifica/cierre + fecha_* on submit or buttons).
- Display joins (cliente nombre, tipo nombre, user names in lists or ficha).
- Links to Auditorias / Aspectos / other cross entities.
- Full sub-entities (plan_formacion, inscripciones, ficha personal forms, reqpuesto).
- Calendar or reporting views.
- "Dar de baja" or bulk operations from legacy buttons.

## Pattern to Follow (Standards from 8.5–9.3 + Lessons)
1. **Data**: New patch 0022 modeled 1:1 on 0021/0020 (CREATE TABLE IF NOT EXISTS with PRIMARY KEY, all columns from legacy dump for completeness even if some left for later, INSERT 3 demo rows with realistic values, ON CONFLICT DO NOTHING, data_patches INSERT with ON CONFLICT (filename) DO NOTHING).
2. **Listado**: Extend CatalogListado, set the 4 protected strings (table='acciones_mejora', title='Acciones de Mejora', templateDir='mejora', flashPrefix='mejora'), override getSelectSql() + mapRow() for the list-relevant columns (base default assumes id/nombre/activo).
3. **Formulario**: Extend CatalogFormulario, same 5 protected (incl listRoute='/admin/mejora'). Provide full ShowPage + Procesar overrides (copy structure from Equipos 9.3): custom SELECT with the supported fields, map $item['mejora'] = [...], extract + validate (descripcion required + fecha), UPDATE/INSERT only the columns controlled in this slice (other legacy cols like user ids / auditoria remain NULL/default), parse checkboxes for the two booleans (requiere_tratamiento, cerrada).
4. **Routes**: Strictly follow the clientes/equipos block style (after the Equipos block):
   - GET /admin/mejora → Listado ShowPage
   - GET /admin/mejora/nuevo + /editar/{id} → Formulario ShowPage
   - POST .../nuevo + /editar/{id} → Formulario Procesar
   - Legacy: /administracion/mejora/listado/ver → Listado (covers the primary menu accion 'mejora:listado:listado:ver' from 0004).
5. **Templates**: Extend layouts/app.twig. pageTitle, list container var matches templateDir ('mejora'), form uses strtolower(flashPrefix) key ('mejora'). Table with ID/Fecha/Descripción/Área/Estado/Acciones. Form groups for the fields (date inputs for dates, checkbox for bools, number for coste/tipo/cliente ids in v1), hidden id on edit, action URLs conditional. Bottom .alert note with stage + deferred work. No flash rendering inside content block.
6. **Verification**: Extend the shared script + new dedicated playbook section with exact docker/psql/browser/DB-assert steps. Always run full verify-8.6.sh after init.
7. **Docs + TODOS**: This plan first (committed before any code). Update TODOS to mark the Mejora basic item "done in Stage 9.4", refresh Suggested (promote Documentación slice or Formación basic or sub-entities), add 9.4 section to checklists. Small MIGRATION-PLAN note.

**Key lesson applied**: Route wiring, naming (Formulario.php), Catalog base + overrides, full field coverage (no ignored columns in the supported set), template style (vars, no inner flashes, stage notes), and living TODOS update on every functional PR are non-negotiable.

## Data Discovery (for Acciones de Mejora)
- Legacy table (from scripts/qnovaintegraldumpvacio.sql): `acciones_mejora` with:
  id SERIAL NOT NULL,
  tipo INTEGER,
  cliente INTEGER,
  fecha DATE,
  usuario_detectado INTEGER,
  descripcion CHARACTER VARYING(1024),
  analisis CHARACTER VARYING(1024),
  requiere_tratamiento BOOLEAN,
  tratamiento CHARACTER VARYING(1024),
  accion_preventiva CHARACTER VARYING(1024),
  fecha_implantacion DATE,
  usuario_verifica INTEGER,
  fecha_verifica DATE,
  observaciones CHARACTER VARYING(1024),
  coste NUMERIC(10, 2),
  cerrada BOOLEAN,
  usuario_cerrado INTEGER,
  fecha_cierre DATE,
  usuario_implantacion INTEGER,
  plazo DATE,
  auditoria INTEGER,
  area CHARACTER VARYING(128)
- Menu entries (0004-full-legacy-menu.sql): top-level branch id 68 'mejora' (label 'ACC. MEJORA') with child 'mejora:listado:listado:ver' (id 14). Other children exist for sub-flows (deferred).
- Primary key field in legacy usage: descripcion is the main human-readable (no 'nombre' column).
- Status/closing tracked via 'cerrada' + fecha_cierre etc. (workflow buttons deferred).
- Joins in legacy (creaFicha, crearExcel): to clientes (on cliente), usuarios (multiple), tipoaccionesmejora likely via tipo. These will surface as integer ids in v1 list/form; enrichment in later legs.
- No prior patch created the table (verify tables list and data_patches up to 0021 do not reference it). 0022 will provide it + seed for reproducible dev DB + tracking.
- TiposMejora catalog (tipoaccionesmejora) and Clientes already modernized (good for future joins).

## Verification Checklist (Non-Interactive + Human Gate)
- docker compose exec app php -l on new Pages/Mejora/* + index.php + verify script.
- After clean init-db.sh: psql confirms 'acciones_mejora' table, 0022 in data_patches, >=3 rows with realistic data.
- verify-8.6.sh passes (tables list now includes 'acciones_mejora', count assert, no breakage of prior asserts including Equipos/Proveedores).
- Browser: after login, sidebar navigation to Acc. Mejora / Mejora (under Aplicacion), list shows expected columns + "Nueva Acción", create works (required descripcion + fecha + others), edit updates fields (incl flipping cerrada), success flash on return to list, legacy path resolves to modern list.
- Post-submit DB SELECT matches submitted values for the fields we control.
- No PHP errors/warnings, flashes only via the prefix mechanism, templates use layout vars, stage notes visible.
- No regression on Equipos/Proveedores or Personalizacion modules.

## Files To Be Created/Modified
**New**:
- `reference/stage-9.4-acciones-mejora-plan.md` (this; first commit)
- `docker/db-init/data-patches/0022-acciones-mejora-table-and-seed.sql`
- `Pages/Mejora/Listado.php`
- `Pages/Mejora/Formulario.php`
- `templates/mejora/listado.twig`
- `templates/mejora/formulario.twig`

**Modified**:
- `index.php` (routes block for /admin/mejora + legacy)
- `scripts/verify-8.6.sh` (tables IN list, count, patch comment, php -l, header)
- `.agents/MIGRATION-TODOS.md` (checkbox for Mejora basic + snapshot + suggested refresh + stage note)
- `.agents/STAGE-CHECKLISTS.md` (full 9.4 playbook section + todo json)
- `.agents/MIGRATION-PLAN.md` (Last Updated + short status)

## Detailed Execution Order (Strict — Plan First)
1. (This branch) Write + commit this plan.md as the absolute first change. No code before.
2. Create the 0022 patch (full columns + 3 demo seeds).
3. Implement the two Page classes (Listado small overrides, Formulario with multi-field SELECT/UPDATE/INSERT + validation).
4. Update index.php routes (new Mejora block).
5. Create the two twig templates (style match + descriptive stage notes).
6. Extend verify script.
7. Update all .agents/ files (TODOS first among docs so the living list reflects reality immediately).
8. Run full verification suite (clean room preferred: down -v, up, init-db.sh, targeted psql, php -l, verify-8.6.sh, browser flows).
9. Logical commits (patch, pages+templates, routes+verify, docs), `git push -u origin feat/stage-9.4-acciones-mejora`, open PR with reference to the TODOS Mejora item.
10. (Post-merge) Human confirms browser flows; flip final checkbox here if not already.

## Success Criteria
- Plan was the first commit on the branch (visible in git log).
- After init: acciones_mejora table + 0022 recorded + sample data present (psql count >=3).
- /admin/mejora renders the list (using Listado), /nuevo and /editar render forms, POSTs work and persist the supported fields including booleans and dates.
- Legacy /administracion/mejora/listado/ver resolves to the modern list.
- All prior modules (9.3 Equipos etc.) unaffected.
- verify-8.6.sh + targeted psql + php -l green.
- Full playbook evidence + updated living TODOS + no open gaps.
- PR is self-contained, reviewable size, follows every ritual bullet in MIGRATION-TODOS.md "How to Use".

## Risks & Mitigations
- Table has many columns + FK ints + no single "activo"/"nombre": handled by explicit custom overrides + limited INSERT/UPDATE column lists + clear stage notes + deferred items documented.
- Form length: 10+ fields will be taller than Equipos; acceptable for first slice of a workflow entity; stage alert explains what is intentionally out.
- Synthetic demo data (no direct INSERTs extracted for this table in the head of the dump): use plausible values matching other seeds (clientes, tiposmejora exist so ids can reference them in future).
- Sidebar menu entry for "ACC. MEJORA": relies on existing 0004 menu data (branch 68 + child) + MainPage + route match. The new routes make the main flow modern.
- Date/ numeric inputs: use HTML5 date + text/number; postgres accepts the formats from form; basic validation only (full business rules later).

## Related / Handoff
- Directly delivers the "Mejora focused leg" / "Acciones de Mejora (legacy 68)" entry in the functional area groups + the corresponding Suggested Next bullet.
- Reinforces pattern: one vertical per leg, catalog base + overrides where needed, living list always updated, plan first.
- After this, Suggested Next will promote starting the high-value Documentación strangler (modern landing + list shell) or a Formación basic slice (cursos/inscripciones/planes) or sub-entities + enrichment for Equipos/Proveedores/Mejora (joins, workflows).
- Any new discovery (extra accions under mejora, related tables) will be added to TODOS immediately.

---
*Part of the menu-driven modernization (Stage 8.x+ / 9.x). Uses the daily MIGRATION-TODOS navigation introduced in 9.0. "the size of PRs we want from now on".*

**Execution autonomous per AGENTS.md + the testing strategy in STAGE-CHECKLISTS. Plan committed first. Docker-only. If git fails: retry.**

**Plan written on the feature branch (first commit).**