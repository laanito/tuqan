# Stage 8.8 Plan — Finish Remaining Personalización Modules + Tipo Cursos

**Status**: Planning phase
**Branch target**: `feat/stage-8.8-finish-personalizacion-remaining-modules` (fresh from origin/master)
**Driver**: Finish the last 2 items from the original 7 Personalizacion modules under Aplicacion (T. Amb. Aplicable / tiposamb and Tipos Imp. Amb. / tiposimp), add one more similar catalog module (Tipo Cursos) to keep the PR size similar to 8.6/8.7, deliver full modern Listado + Formulario + Procesar for all three, extend verification, update docs. Continue the menu-driven modernization pattern without introducing enhancements or scope creep.

## Goals (Aligned with Previous User Directives and Plan)
1. Complete the Personalizacion vertical slice by delivering the final 2 of the original 7 items with full modern pages (no more Placeholders for them).
2. Add Tipo Cursos as the 3rd module in this leg (lightweight catalog like the others) so the change volume matches previous reviewable PRs.
3. Maintain and extend the testing/verification approach (playbook, verify script updates, DB asserts, php -l, clean-room via init-db.sh).
4. All work Docker-only, menu-driven (add/replace routes for modern + legacy accion paths, no menu_nuevo inserts needed since entries pre-exist from legacy import).
5. Update all .agents/ docs, db-init docs, etc. before review.
6. Keep PR size similar: ~3 full modules + patch + routes + verify expansion + docs.

## Scope (Sized Similarly to 8.6/8.7 for Pace)
- **3 full new modules** (to match the "3 full" volume from 8.7 and overall pace):
  - Tipos Amb. Aplicable (tiposamb) — table + 3-5 demo rows, full Pages/TiposAmb/ + templates/, modern + legacy routes + POST.
  - Tipos Imp. Amb. (tiposimp) — same.
  - Tipo Cursos (tipocursos) — same. (Menu entry exists under Formacion area; follows the tipo* catalog pattern.)
- Each: lightweight table (id serial pk, nombre text not null, activo boolean default true) + seeds in patch 0016.
- Full pattern copy from TiposMejora/TiposAreas/TipoDocumento (Listado using Manejador + consulta, Formulario with ShowPage($id) + Procesar using consultaPreparada, module-specific flash keys in $_SESSION, redirects, validation).
- Routes in index.php: clean /admin/tiposamb + /admin/tiposamb/nuevo + /admin/tiposamb/editar/{id} + POSTs; same for tiposimp; for tipocursos use /admin/tipo-cursos (hyphen for readability) + POSTs + legacy paths.
- Legacy accion paths: /administracion/tiposamb/listado/ver etc. (and /nuevo variants to match style of prior) routed to the modern classes.
- Update the "Remaining 2 Personalizacion children still basic/Placeholder" comment block.
- **Verification & Testing**:
  - New patch 0016 (tables + seeds + data_patches tracking; idempotent with ON CONFLICT).
  - Extend scripts/verify-8.6.sh (rename comment / header to cover 8.8, add php -l for the 3 new, DB checks for new tables + row counts + 0016 in patches list).
  - Add detailed "Stage 8.8 Verification Playbook" section in STAGE-CHECKLISTS.md (modeled exactly after 8.7/8.6: clean room with down -v + init-db.sh, php -l, psql asserts on tables/labels/patches after init, browser flows for create/edit + post-submit DB assert, flashes, no regressions).
- **Docs & Housekeeping**:
  - Full new section in .agents/STAGE-CHECKLISTS.md (todo items, validation commands, evidence skeleton, next steps).
  - Update .agents/MIGRATION-PLAN.md (mark 8.7 complete with date/branch, introduce 8.8, update timeline).
  - Update docker/db-init/README.md with 0016 note + full patch list.
  - Any small cleanups (route comments, etc.).
- **Out of scope for this PR** (to keep size + focus):
  - Adding child menu_nuevo entries (nuevo/editar) for these — only sedes has them; the in-page "Nuevo"/"Editar" buttons + direct routes suffice and match what was done for the 8.7 modules.
  - Leg. Aplicable or other remaining catalogs.
  - Any matrix/menus/permisos/usuarios enhancements.
  - New unit tests (follow current strategy: verify script + playbook + human browser gate).
  - Table renames or legacy data migration for these (new dedicated tables like 8.7).

**Expected size**: Similar to 8.6/8.7 (~3 full modules + 1 patch + routes for modern+legacy + verify script + full playbook section + 2-3 doc files). Reviewable, consistent pace.

## Current Reality (Post 8.7 / #66 + #67)
- 3 Personalizacion modules from 8.7 fully implemented with GET+POST (Tipos Acc. Mejora via tipoaccionesmejora, Tipos Area, Tipo Documento).
- Clientes + Criterios full from 8.6.
- The final 2 of the original 7 (T. Amb. Aplicable / administracion:tiposamb:listado:ver and Tipos Imp. Amb. / administracion:tiposimp:listado:ver) still route to Placeholder (see index.php:290-291).
- Tipo Cursos menu entry exists (administracion:tipo_cursos:listado:ver under padre 91) but no modern page or table.
- No tables for tiposamb, tiposimp, tipocursos (confirmed via psql).
- Latest patch: 0015-personalizacion-remaining-modules.sql
- verify-8.6.sh extended in place to cover 8.7 php -l + DB checks (still named 8.6 but handles 8.7).
- All Aplicacion children under 82 now have modern pages *except* the 2 placeholders.
- Menu labels/accions for the 2 are present (from full legacy import 0004 + 0010 restructure).
- Docker stack running, DB clean-room reproducible via scripts/init-db.sh (applies 0001.. in order + data_patches guard).

## Detailed Tasks / Patches
1. New data patch:
   - docker/db-init/data-patches/0016-personalizacion-last-two-plus-tipocursos.sql
   - CREATE TABLE IF NOT EXISTS for tiposamb, tiposimp, tipocursos (id SERIAL PRIMARY KEY, nombre TEXT NOT NULL, activo BOOLEAN NOT NULL DEFAULT true)
   - INSERT 3-5 demo rows each (ON CONFLICT DO NOTHING)
   - INSERT INTO data_patches (filename, applied_at) ... ON CONFLICT DO NOTHING
   - Comments on tables noting Stage 8.8 / Personalizacion catalog.
   - No menu inserts (entries pre-exist).

2. Code for 3 modules (copy/adapt from TiposMejora exactly):
   - Pages/TiposAmb/{Listado.php, Formulario.php}
     - Namespace Tuqan\Pages\TiposAmb
     - Listado: query "SELECT id, nombre, activo FROM tiposamb ORDER BY id", map to array, pass as 'tiposamb', use flash keys 'tiposamb_flash_success' / 'tiposamb_form_error'
     - Formulario: ShowPage($id=null) with edit load via consultaPreparada, Procesar with trim+required validation on nombre, INSERT/UPDATE via preparada, set flash, redirect to /admin/tiposamb
   - Same for TiposImp (table tiposimp, keys 'tiposimp_*', routes /admin/tiposimp , pageTitle "Tipos Imp. Amb.")
   - Same for TipoCursos (table tipocursos, keys 'tipocursos_*', modern route /admin/tipo-cursos for cleanliness + legacy, pageTitle "Tipo Cursos")

3. Templates (copy/adapt from templates/tiposmejora/ ):
   - templates/tiposamb/listado.twig + formulario.twig
   - templates/tiposimp/...
   - templates/tipocursos/...
   - Use extends "layouts/app.twig", page header with Nuevo/Volver, table or form, flash divs inside content (matching the 8.7 modules), small "Stage 8.8" info note at bottom.
   - Adapt labels, action URLs, variable names, titles to the module.

4. Routes in index.php:
   - Add the full set for /admin/tiposamb* (GET list/nuevo/editar, POST nuevo/editar) + legacy /administracion/tiposamb/listado/ver (and /nuevo style if present in other)
   - Same for /admin/tiposimp*
   - For TipoCursos: /admin/tipo-cursos + /admin/tipo-cursos/nuevo + /admin/tipo-cursos/editar/{id} + POSTs + legacy /administracion/tipo_cursos/listado/ver
   - Remove or comment the 2 Placeholder lines for tiposamb/tiposimp.
   - Update the comment: // Stage 8.8: last 2 Personalizacion + Tipo Cursos now full
   - Ensure legacy for the new ones are present (like 8.7 did for some).

5. Verification:
   - Extend scripts/verify-8.6.sh : update header/echo to "Stage 8.6 / 8.7 / 8.8", add the 3 new *Listado.php *Formulario.php to the php -l line, add to the psql union counts + tables list + patch filter, add specific SELECT for the new tables.
   - New "Stage 8.8 Verification Playbook" section in .agents/STAGE-CHECKLISTS.md (copy structure from 8.7: goal, selected scope, key changes, evidence commands, DB gates, browser flows for create/edit + post DB assert on new tables, next steps).
   - In playbook: note that init-db.sh + psql will apply 0016.

6. Docs:
   - New Stage 8.8 section in STAGE-CHECKLISTS.md modeled on 8.7.
   - Update MIGRATION-PLAN.md : mark Stage 8.7 completed (date, branch, PR), add 8.8 bullet in timeline + "Stage 8.8 (in progress / completed)" note.
   - docker/db-init/README.md : add 0016 to the patch list + short description.
   - (Optional) root Readme.md or others if they list modules.

## Risks & Mitigations
- Table name choice: Using short tiposamb / tiposimp / tipocursos (no legacy conflict found; consistent with how 8.7 chose tipoaccionesmejora etc. for display names). Documented in patch.
- Route legacy paths: Match exactly the accion in menu (e.g. administracion:tiposamb:listado:ver -> /administracion/tiposamb/listado/ver). Added both clean /admin/ and legacy.
- Flash/session keys: Per-module to avoid collision (as in all prior catalog modules); passed through to layout + rendered in content templates.
- Size: Strictly 3 modules + 1 patch + routes + 1 script update + 3 doc updates. No more.
- No child menu entries: Matches what 8.7 delivered for its 3 (sedes is the exception from earlier); in-page buttons provide the flows.
- Verification: Playbook will be long but reusable; script keeps non-interactive part practical.

## Execution Order (Strict, like 8.5/8.7)
1. Write this plan + supporting reference (this file) — on the new branch as first commit.
2. Implement data patch 0016 + apply/verify with docker compose exec app ./scripts/init-db.sh + psql checks.
3. Implement the 3 full modules (code under Pages/ + templates/ + routes in index.php).
4. Extend verify-8.6.sh + add full verification playbook section to checklists.
5. Update all other docs (MIGRATION-PLAN, db-init/README, etc.).
6. Full Docker verification (php -l via script or direct, init + psql asserts for new tables/patch, exercise via browser or LegacyAction paths, confirm no regressions on prior modules).
7. Commit in logical chunks, push, open PR. (Follow AGENTS.md + todo_write; only circle back if blocked.)

## Success Criteria
- After patch + init-db.sh: the 3 new tables exist, have >=3 rows each, data_patches includes '0016-...sql', no errors.
- The 2 previous Placeholder entries now resolve to modern Listado (clicking in sidebar under Personalizacion works; /admin/tiposamb and /admin/tiposimp and legacy paths render the list with "Nuevo" button).
- Tipo Cursos also has modern page at its legacy-resolved path + clean /admin/tipo-cursos.
- Create + edit for all 3 work end-to-end: submit form → success flash (in list) → row appears/updated in table → psql confirms the nombre/activo.
- Flashes appear (both content and layout if applicable), validation rejects empty nombre, redirects correct.
- verify-8.6.sh (updated) passes php -l + basic DB asserts for the 3 + patch.
- Full playbook commands (clean room) pass; human browser pass would cover the flows (per "continue a couple of legs" + deliberate non-interactive until gaps found).
- No regressions (existing Perfiles/Sedes/Clientes/.../Tipos* still work, menu sidebar still shows all Aplicacion items).
- All changes in one clean, reviewable PR with proper evidence appended to .agents/ docs.
- Docker-only, consistent with previous stages.

## Related
- Continues directly from 8.7 (finishes the exact "Remaining 2 Personalizacion" called out in 8.7 checklists "Next in this or follow-up legs").
- Completes the Aplicacion submenu modernization focus from the original user request (profiles, empresas/sedes, menus, idiomas, permisos, and now all 7 Personalizacion items + bonus Tipo Cursos for size).
- Prepares ground for deeper sections (other top-level like Documentacion, Calidad, or full Usuarios enhancements / POSTs if gaps found after legs).
- Reinforces the testing strategy (playbook + script will be updated).

---

*Part of the ongoing menu-driven modernization (Stage 8.x). June 2026.*

**Execution will be autonomous per AGENTS.md once branch created. Only circle back on blockers.**

**Plan written on the feature branch (first commit).**
