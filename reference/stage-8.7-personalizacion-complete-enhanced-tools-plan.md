# Stage 8.7 Plan — Complete Personalización + Enhanced Admin Tools (Permisos Matrix, Menus Editing)

**Status**: Planning phase
**Branch target**: `feat/stage-8.7-personalizacion-complete-enhanced-tools` (fresh from origin/master)
**Driver**: Finish the Personalizacion vertical slice (the 7 items from original request), deepen the "deeper logic" for Permisos and Menus as called out in Stage 9+ notes, keep verification strategy front and center, maintain reviewable PR size similar to 8.6.

## Goals (Aligned with Previous User Directives and Plan)
1. Complete the remaining Personalizacion sub-modules with full modern pages (Listado + Formulario + POST) following the exact pattern from Clientes/Criterios (and Perfiles/Sedes).
2. Enhance the existing "basic" tools from 8.6 to make them more useful:
   - Permisos matrix: expand scope, better UX, support the full Aplicacion subtree, improve the update logic.
   - Menus editing: support children, more robust batch editing, perhaps minor improvements to form.
3. Maintain and extend the testing/verification approach established post-8.6 (playbook, verify scripts, DB asserts, CI).
4. All work Docker-only, menu-driven (update routes, add missing child actions in patches if needed), idempotent patches.
5. Update all .agents/ docs, db-init docs, etc.

## Scope (Sized Similarly to 8.6 for Pace)
- **3 full new modules** (to match "first additional" + "Criterios" volume from 8.6):
  - Tipos Acc. Mejora (tipomejora / related to tipo_acciones)
  - Tipos Area (tiposareas)
  - Tipo Documento (tipodocumento)
  - Each: lightweight table (id, nombre, activo) + 3-5 demo rows in patch, full Pages/ + templates/, modern + legacy routes + POST, following Perfiles pattern exactly (including flashes, validation, etc.).
- **For the other 2** (Tipos Amb. Aplicable / tiposamb, Tipos Imp. Amb. / tiposimp): add modern routes pointing to Placeholder (or minimal generic if easy), ensure they appear correctly under Personalizacion in sidebar. Do not bloat PR with 5 identical modules.
- **Enhance Permisos** (from "basic matrix" in 8.6):
  - Load menus specifically under Aplicacion (padre = 82 from 0010).
  - Support editing for the profile in a cleaner way (perhaps list of menus with toggles per profile or focused per-profile view).
  - Improve the Procesar logic (less DB reconnections, better array handling).
  - Add flash support, update template for better UX (perhaps table with checkboxes per menu).
- **Enhance Menus editing** (from basic in 8.6):
  - Support children menus in the editable form (group by parent in UI).
  - Make the POST more robust (handle more fields if needed, validation).
  - Update template to show hierarchy (padre column, indentation).
- **Verification & Testing**:
  - New patches 0015+ (tables + seeds + any menu child actions for nuevo/editar).
  - Extend scripts/verify-*.sh (or new verify-8.7.sh) with specific checks for the 3 new modules + enhanced matrix/menus.
  - Add detailed "Stage 8.7 Verification Playbook" section (modeled exactly after the 8.6 one: clean room, DB asserts for tables/labels/permisos/orden after "user actions", php -l on all new files, etc.).
  - Integrate the verify into CI if not already (but it was in 65).
- **Docs & Housekeeping**:
  - Full new section in .agents/STAGE-CHECKLISTS.md (todo items, validation commands, evidence skeleton).
  - Update .agents/MIGRATION-PLAN.md (mark 8.6 complete, introduce 8.7).
  - Update docker/db-init/README.md with new patches.
  - Any small cleanups (e.g. comments, route comments).
- **Out of scope for this PR** (to keep size):
  - Full RBAC redesign (keep using the legacy permisos array).
  - Other idiomas support beyond ES labels.
  - Usuarios password change flow beyond basic.
  - More than 3 full modules.
  - New tests beyond the verify script + playbook (per current strategy: automated where cheap, reproducible commands + human test for complex UI/DB).

**Expected size**: Similar to 8.6 ( ~3 full modules + 2 enhancements + patches + routes + docs + verify expansion). Reviewable, not the entire remaining work.

## Current Reality (Post 8.6 / #64 + #65)
- Clientes and Criterios fully implemented with GET/POST (from 8.6).
- Tiposmejora table exists (from 0014, used for one).
- Permisos has basic matrix (limited menus, simple checkboxes, array mutation in Procesar).
- Menus has basic editing form for orden + ES label (batch POST, loads all).
- All under Personalizacion parent in menu (from 0010 patch).
- Menu IDs from 0004/0010:
  - 85: tipomejora (Tipos Acc. Mejora)
  - 87: tiposareas
  - 88: tiposamb (T. Amb. Aplicable)
  - 90: tiposimp (Tipos Imp. Amb.)
  - 92: tipodocumento
- The 7 Personalizacion items were reparented in 0010; child actions (nuevo, editar) may need adding for the new ones like in 8.5 plan.
- Verification strategy and playbook from post-#64 work (STAGE-CHECKLISTS top section + 8.6 playbook + verify-8.6.sh + CI step).
- DB has sedes, clientes, criterios, tiposmejora.

## Detailed Tasks / Patches
1. New data patches (idempotent, tracked):
   - 0015-...-tiposmejora-etc.sql or separate: CREATE TABLE IF NOT EXISTS for the 3 (if not using tiposmejora for Tipos Acc), seed 3-5 rows, INSERT data_patches.
   - Update menu_nuevo for child actions (nuevo, editar) for the 3, similar to 0010/8.5.
   - Any label fixes if needed.

2. Code for 3 modules:
   - Pages/TiposMejora/{Listado,Formulario}.php (namespace, queries to new table, variables 'tiposmejora' or consistent, 'Sede' style naming? Use "Tipos de Acc. Mejora" in titles, but class TiposMejora for simplicity like Criterios).
   - Same for TiposAreas, TipoDocumento.
   - templates/ for each (listado + formulario, modeled exactly on clientes/criterios, with POST actions, flashes note removed since now supported via layout).

3. Routes in index.php:
   - Modern GET/POST for /admin/tipos-mejora etc.
   - Legacy /administracion/... for the accions.
   - Update the comment block for Personalizacion children.
   - Add legacy for the new if not present.

4. Enhancements:
   - Permisos/Formulario.php and template: focus query on padre=82, better form (perhaps show Aplicacion menus), improve Procesar (cleaner, less reconnections), add support for flash.
   - Menus/Listado.php and template: enhance query or display to show children, update Procesar if needed, make form show hierarchy (padre info, groups).

5. Verification:
   - Extend or new verify script with psql checks for new tables, menu updates, etc.
   - New section in STAGE-CHECKLISTS for 8.7 verification playbook (clean room, DB asserts for the 3 modules + matrix/menus changes, php -l on new files, browser flows for create/edit + assert DB).

6. Docs:
   - New Stage 8.7 section in STAGE-CHECKLISTS (modeled on 8.6: goal, selected scope, key changes, evidence commands skeleton, next steps).
   - Update MIGRATION-PLAN (complete 8.6 note, add 8.7).
   - db-init/README example list of patches.

## Risks & Mitigations
- Duplicate module code: Acceptable for now (as in 8.6); future could extract generic master-data class.
- Legacy table names: Use simple new tables like before (tiposmejora already used); document that full schema has tipo_* with idiomas variants.
- Menu child actions: Add in patch like 8.5 plan; verify with psql after init.
- Verification size: The playbook will be long but copy-paste; non-interactive script keeps it practical.
- PR size: Strictly limit to 3 full + targeted enhancements + verify/docs.

## Execution Order (Strict, like 8.5)
1. Write this plan + supporting reference (this file).
2. Create fresh branch from origin/master.
3. Implement data patches (0015+) + apply/verify with init-db + psql.
4. Implement the 3 full modules (code + templates + routes).
5. Enhance Permisos and Menus (code + templates + routes if needed).
6. Expand verify script + add full verification playbook section to checklists.
7. Update all other docs (MIGRATION, db-init/README, etc.).
8. Full Docker clean test (down -v, up, init-db, login, exercise new modules + enhanced tools, run verify script, psql asserts).
9. Commit, push, open PR. (Follow AGENTS.md; use todo_write for internal tracking.)

## Success Criteria
- After patches + init: the 3 new tables exist with data, menu actions/labels correct for the items, child actions present if added.
- Clicking the 3 new entries (and the other 2) in Personalizacion lands on modern pages (or clear Placeholder with sidebar).
- Create/edit for the 3 modules works end-to-end (form submit → flash → list updated → DB row correct via psql).
- Enhanced Permisos matrix: can edit for a profile, changes persist in permisos array, visible in re-open or list.
- Enhanced Menus: can edit orden/labels for children too, changes persist, reflected in sidebar after refresh.
- Verification playbook + script cover the new work (commands pass in clean room).
- No regressions on existing Clientes/Criterios/Sedes/Perfiles/etc.
- All changes in one clean, reviewable PR with proper evidence appended to .agents/ docs.
- Docker-only, consistent with previous stages.

## Related
- Continues directly from 8.6 (more Personalizacion + deeper for the tools introduced as "basic" there).
- Prepares ground for even deeper business logic or other sections (e.g. full under Administracion or other branches).
- Reinforces the testing strategy (playbook will be updated with 8.7 specifics).

---

*Part of the ongoing menu-driven modernization (Stage 8.x). June 2026.*

**Execution will be autonomous per AGENTS.md once branch created. Only circle back on blockers.**

**Plan written on master, will be committed as first thing on the new branch.**