# Tuqan Migration TODOs — Remaining Modules to Migrate (Daily Navigable List)

**Purpose**: This is the **primary daily work list** for ongoing Stage 8.x+ module migration legs.  
MIGRATION-PLAN.md = architecture, constraints, high-level stages + history.  
STAGE-CHECKLISTS.md = detailed per-leg playbooks, exact commands, evidence, retrospective lessons.  
**This file** = scannable "what is left, how big is it, what should the next PR be?" + handoff package for other agents.

**Last updated**: Stage 9.0 (this leg) on `feat/stage-9.0-migration-todos`. Created as the explicit "first step before continuing more changes".

---

## How to Use (Agents + Humans — Read This Every Leg)

1. **Start of leg**: Read this file (focus on "Suggested Next Legs" + the area groups). Pick a reviewable slice (target: similar volume to 8.6 / 8.7 / 8.8 / 8.9 — a few full simple modules, or 1 complex vertical + supporting patch/routes/docs).
2. **Ritual (non-negotiable, from AGENTS.md + all prior stages)**:
   - Stay in local folder, use relative paths.
   - `git checkout master && git pull --ff-only` (or fetch + ensure clean).
   - `git checkout -b feat/stage-9.x-descriptive-name`
   - Write (or update) a short plan in `reference/stage-9.x-...-plan.md` and commit it **first**.
   - Implement (Docker-only: `docker compose exec app ...` for everything).
   - Update **this file**: flip checkboxes to [x], add notes/evidence links, refresh "Current snapshot" and "Suggested next".
   - Add/extend a full "Stage 9.x Verification Playbook" section in STAGE-CHECKLISTS.md (todo items, validation commands, DB asserts, browser flows, evidence).
   - Update MIGRATION-PLAN.md timeline/status if the stage milestone warrants it.
   - Verify: `docker compose exec app ./scripts/verify-8.6.sh` (extended as needed), php -l on touched, psql asserts after init-db.sh, clean room where possible.
   - Commit (logical chunks), `git push -u origin <branch>`, open PR.
   - "if git fails retry".
3. **Marking done**: Only `[x]` items **after merge + verified** (the human who asked does final browser pass on the flows; non-interactive gates must be green).
4. **Discovery**: During any leg, if you surface a new module, missing accion, extra table, or gap — add it to the right group here *immediately* with a short note + suggested size.
5. **Handoff to other agents**: The checkboxes + "Suggested Next Legs" shortlist + latest snapshot + links to the just-completed stage plan/checklist section are 90% of what the next agent needs. They still read the top of MIGRATION-PLAN + AGENTS.md once.
6. **PR sizing**: "the size of PRs we want from now on". One vertical or 2-3 catalogs + patch + routes + verify + docs is the model. Avoid giant refactors.

**Quick navigation**: Use your editor's outline / search for `### ` (area headers) or `[ ]` (open items).

---

## Current Snapshot (Post 8.9 Catalog Base + Menu Hygiene)

**Modern (Pages/ + templates/ + modern+legacy routes, most on Catalog base)**: 14 modules
- Usuarios, Perfiles, Sedes (ex-Empresas)
- Menus (batch orden + labels via Listado::Procesar), Idiomas, Permisos (matrix)
- Clientes, Criterios (catalog base)
- TiposMejora, TiposAreas, TipoDocumento, TiposAmb, TiposImp, TipoCursos (all catalog base post 8.7-8.9)

**Personalizacion (under Administracion/Aplicacion) catalogs**: Complete for the 7 original + Tipo Cursos (stages 8.5-8.9). Menu cleaned in 8.9 (0017 reparent actionable, 0018 delete redundant empty sections).

**Pending small note from 8.9/0018**: Row 84 ("Criterios" section, no accion) was removed as redundant. User recall: it should be "Criterios Ambientales" with a proper action and placed as direct child of Personalizacion (padre = 1400). Capture below as XS item.

**Everything else**: Still served via legacy entry points (arbol_*.php, cuestionario.php, editor.php, GenPDF, crearExcel, graficaIndicadores.php, items.php, procesa_*, etc.) + LegacyAction fallback for unmapped colon accions. Top-level Aplicacion branches (Documentación 66, Procesos 76, Proveedores 67, Equipos 70, Mejora 68, Formación 69, Auditorías 71, Indicadores 72, Aspectos Ambientales 73) have no modern Pages/* equivalents yet.

**Infra ready for more modules**: Docker-only, init-db.sh + data_patches (idempotent), Phroute (clean /admin/* + legacy /administracion/*), Twig layouts/app + per-module, prepared statements path via Manejador_Base_Datos::consultaPreparada, MainPage sidebar from real menu_nuevo, flash pattern, verify-8.6.sh + playbook discipline.

**No uncommitted work** on this branch at creation (pure new leg).

---

## Suggested Next Legs (Start Here for Daily Pace)

Pick 1-2 related items that together form a reviewable PR. Update this list when you complete legs.

- [ ] **XS hygiene + note closure**: Restore/add "Criterios Ambientales" as direct actionable menu entry under Personalizacion (padre=1400, proper accion e.g. administracion:criterios-ambientales:... or reuse), any missing legacy route mappings for already-modern modules, cross-check all current menu accions have either modern route or explicit LegacyAction coverage. Data patch (0019?) + index.php tweak + update this file + small checklist note. (See 0018 NOTE FOR LATER.)
- [ ] **Small vertical (catalog-style if table fits)**: Basic modern support for one more Tipo* or reference list under Formación / Mejora / etc. (e.g. if Tipo Cursos was the last easy one, find the next simple nombre/activo that already has menu entry).
- [ ] **One real Aplicacion vertical (medium)**: Proveedores (listado + nuevo/editar + contacts/incidencias if table structure allows) or Equipos (list + maintenance bits). Follow 8.6-8.8 pattern (table in patch if new, Pages/ + templates/, full routes modern+legacy, POST Procesar, flashes, verify extension, playbook, update this TODOS + checklists).
- [ ] **Documentación slice (larger but high value)**: Landing or basic tree view for Documentación (arbol_documentos.php strangler). May need new base or different pattern (tree not flat catalog).
- [ ] **Mejora or Formación focused leg**: Acciones de mejora workflows (beyond the tipo catalog) or Formación plans/inscripciones/reqs (tipocursos is only the catalog part).
- [ ] **Cross-cut extraction**: After 2-3 more modules, extract a second base (e.g. for "rich list with filters" or "form with relations") so later legs stay tiny.

Aim for a mix: one "close the small gaps" + one "new vertical" per couple of legs. Keep delivering working, reviewable increments.

---

## Remaining Modules by Functional Area

### Personalización / Aplicación Admin (largely complete; small gaps)
- [x] All 7 original Personalizacion items + Tipo Cursos + supporting (Clientes, Criterios, Tipos* x6) — Stages 8.5-8.9, catalog base.
- [x] Core admin under Aplicacion/Administracion: Usuarios, Perfiles, Sedes, Menus, Idiomas, Permisos (8.5+).
- [ ] Criterios Ambientales (row 84) — restore as direct actionable child of Personalizacion (padre=1400) with proper label + accion. See 0018 patch + 8.9 retrospective. Est: XS (data patch + route + note). Deps: none.
- [ ] Any remaining child actions (nuevo/editar) under existing modern parents if menu expectations differ from in-page buttons (sedes has some; most don't — decide consistently).

### Aspectos Ambientales / mAspectos (linked to Criterios)
- [ ] Aspectos / Aspectos Ambientales matrix + revisiones (legacy 73). Involves cuestionario flows, linked criterios, possibly special rendering. Old files: mAspectos? / cuestionario.php / procesa_cuestionario.php + related. Complexity: medium-large (matrix + workflow). Suggested: start with data model + basic modern list, then questionnaire integration. Est size for first leg: M.
- [ ] Full environmental aspects revision + reporting tie-in to Indicadores.

### Mejora (Improvement)
- [ ] Acciones de Mejora (legacy 68) — full module beyond the TiposMejora catalog (which is done). Likely tables for acciones, states, responsible, deadlines, linked to other entities (audits, aspects, etc.). Old files: probably under root or Classes related to "mejora". Complexity: M-L (workflow + notifications? + lists with filters). First leg could be basic CRUD + list using/extending catalog patterns if the core table is simple.
- [ ] Integration of mejora actions with Auditorias / Aspectos / Indicadores (cross links).

### Formación (Training)
- [ ] Formación full (legacy 69) — plans, inscripciones, ficha personal, requisitos de puesto, etc. (Tipo Cursos catalog done in 8.8). Old: formacion-related, calendario?, ficha. Complexity: M (several sub-flows). Suggested first slice: cursos + inscripciones or planes.
- [ ] Link to Empleados / RRHH concepts if present in legacy (ficha personal).

### Proveedores (Suppliers)
- [ ] Proveedores (legacy 67) — listado, incidencias, contactos, productos. Classic master + children. Old files: likely proveedores.php or items.php patterns + procesa. Tables probably exist in full schema. Complexity: M (1 main + 2-3 child entities). Good candidate for "one vertical" PR or split (basic + contacts).
- [ ] Homologacion / evaluation flows (common in ISO supplier management).

### Equipos (Equipment / Assets)
- [ ] Equipos (legacy 70) — listado, calendario de revisiones, maintenance. Old: calendario.inc.php + related + equipos-specific. Complexity: M (calendar integration + list + history). May reuse or extend date/calendar bits.
- [ ] Revisiones + compliance records (tie to Auditorias / Mejora).

### Documentación (Core ISO — high value, likely larger)
- [ ] Documentación (legacy 66) — SG docs, formats, control, approval workflows, tree structure. This is one of the central modules (arbol_documentos.php, editor.php, fckeditor usage in docs context, generacion of PDFs, upload, versioning?). Complexity: L (tree + permissions + lifecycle + search). Suggested approach: strangler fig — modern landing + list first, keep legacy tree for edit until new editor + tree component ready. First leg: modern shell + basic list + route for the top accion.
- [ ] Formats / plantillas / control de documentos specifics.
- [ ] PDF ficha / export modernization (GenPDF.inc, related) — cross-cutting but surfaces here.

### Auditorías (Audits)
- [ ] Auditorías (legacy 71) — programa, plan, execution, findings, follow-up (link to Mejora acciones). Old files: likely auditoria* or cuestionario variants + procesa. Complexity: M-L (planning + checklists + reporting). Good to do after or with Mejora.
- [ ] Integration with Indicadores + Aspectos.

### Indicadores + Objetivos (KPIs)
- [ ] Indicadores (legacy 72) — + Objetivos. Old: graficaIndicadores.php, graficamensajes.php, generadorGraficas. Complexity: M (data entry + calculations + charts). Charts may stay legacy or move to modern lib later.
- [ ] Dashboard / home page enhancements that surface key indicators.

### Procesos (Processes)
- [ ] Procesos (legacy 76) — catalogos, arbol de procesos (related to Documentación tree?). Complexity: M (tree + description + links to other modules).
- [ ] Process mapping / revision / approval.

### Other / Smaller or Cross
- [ ] Mensajes / Tareas (were reparented to Administracion in 0010; check if still using legacy or need modern pages).
- [ ] Ayuda / context help system (Manejador_Ayuda etc.).
- [ ] Full Usuarios enhancements (contact info was added early; more profile / company user fields?).
- [ ] Permisos/Menus/Idiomas deeper (they are modern but batch/matrix; possible later base extraction or UI polish).
- [ ] Calendar component modernization (used by Equipos/Formacion/etc.).
- [ ] Bulk import/export (crearExcel.php patterns) + reporting.

**Notes on discovery**: Many legacy accions in the full 0004 menu (and 0005 reorders) point to colon routes that currently fall to LegacyAction or 404-ish. As we modernize we either (a) add matching modern routes + update accion in a patch or (b) keep legacy path mapping in index.php (current pattern for all done modules).

---

## Cross-Cutting / Non-Module Backlog (Important but Not "a Module")

- [ ] Twig 1.x → 2/3 (or 3) proper upgrade (deferred repeatedly; vendor patches in place; will touch all templates + possibly custom extensions when we have many modules).
- [ ] More base class extraction (Catalog* was 8.9; next candidates after a few complex modules: list-with-filters base, form-with-relations base, tree-view base, matrix/batch base for Permisos-style).
- [ ] PDF / Excel / report generation modernization (GenPDF, crearExcel, related generators — used by almost every vertical for "ficha", exports, compliance outputs).
- [ ] Tree / arbol UI + generators (arbol_documentos.php, estructura_arbol.php, generador_arboles.php, dhtmlgoodies tree, saveNodes etc. — core for Documentación + Procesos; big but high leverage).
- [ ] Questionnaire / checklist engine (cuestionario.php + procesa_cuestionario.php + procesa_Editor — used by Aspectos, Auditorias, possibly others; aspects/audits/reqs depend on it).
- [ ] Real integration + characterization tests for modern Pages/*/Formulario::Procesar and business rules (current strategy is verify script + playbook + human gate; move more to automated as DB layer cleans).
- [ ] Legacy bloat continued cleanup (FCKeditor in document context, old Image/, PEAR remnants once usage is fully mapped).
- [ ] PHPStan level raises + more strict CI (after more surface is modern).
- [ ] i18n completeness (catalan partial, more strings in modern templates, Locale/ maintenance).
- [ ] Auth / permissions matrix full coverage + admin UX for assigning (Permisos module exists but depth?).
- [ ] Main page / dashboard evolution (currently enhanced placeholder + tree; surface indicators, tasks, recent docs).

These often make good "alongside a vertical" work or dedicated legs when a cluster of modules would benefit.

---

## Recipe: How to Migrate One (More) Module in a Future Leg

(Extracted from 8.5-8.9 patterns — keep this section updated with new learnings.)

1. **Identify**: Pick from this list. Find the legacy accion (e.g. administracion:proveedores:listado:ver) in data-patches/0004 or 0005 or via menu query after init. Note parent id for possible patch.
2. **Data (if needed)**: New patch `00XX-foo.sql` — CREATE TABLE IF NOT EXISTS (id serial pk, nombre..., activo..., other fields), INSERT demo rows ON CONFLICT DO NOTHING, INSERT INTO data_patches. Or just menu fixes / seeds for existing tables.
3. **Menu (if reparent or new children)**: Idempotent UPDATE/INSERT menu_nuevo + menu_idiomas_nuevo (use the MAX(id)+gap or high base technique from 0010). Record in patch.
4. **Code + templates**:
   - If simple catalog (id, nombre, activo): `Pages/NewName/{Listado,Formulario}.php` extending Catalog* (4-6 protected string lines), `templates/newname/{listado,formulario}.twig` (copy from tiposmejora or sedes, adapt titles/vars/flashPrefix).
   - Complex: may need custom query/map, extra fields in form, relations, or new base first.
5. **Routes (index.php)**: Add the set — modern `/admin/new-name`, `/admin/new-name/nuevo`, `/admin/new-name/editar/{id}`, POSTs, and legacy `/administracion/.../listado/ver` (and /nuevo/editar variants to match existing accions). Follow the comment blocks style from 8.8/8.9.
6. **Verification**: Extend scripts/verify-8.6.sh (php -l, new table counts or patch presence, specific SELECTs). New section in STAGE-CHECKLISTS (clean room down -v + init + psql + php -l + browser flows for create/edit + post DB assert + flashes + no regression on prior modules).
7. **Docs**: Update this TODOS (flip the item + add "done in stage 9.x, branch X, PR Y"), MIGRATION-PLAN if milestone, db-init/README patch list, any other.
8. **Size control**: Stop at 1-3 modules + 1 patch + routes + verify + 2-4 doc files. If bigger, split.

**For trees / questionnaires / PDF**: the first leg will usually be "modern shell + route + basic list or landing that still delegates heavy parts", plus plan for follow-ups.

---

## Handoff & Maintenance Notes

- **When a leg finishes**: Update the snapshot, flip items here, refresh "Suggested Next", make sure the just-completed stage checklist section points back to the items it delivered.
- **Staleness prevention**: This file is edited in *every* functional PR. If you didn't touch it, the PR is probably missing the "update the living list" step.
- **Agent handoff package**: (1) this file, (2) the reference/stage-9.x-*-plan.md of the last merged leg, (3) the corresponding section at the bottom of STAGE-CHECKLISTS.md, (4) current git tip on master after merge.
- **"I have eyeballed the code, but haven't tested (deliberate)"**: The verify script + playbook + human browser gate remain the contract. Add more invariants (like the Personalizacion menu tree checks added in 8.9) when a leg mutates shared structures.
- **Bilingual / praderas notes**: Not directly in scope here (see BLOG-POSTING.md + praderas rules for any blog articles spun out of the work).

---

**This file turns the long-term migration into an executable, daily-navigable backlog that scales to multiple agents.**

**Next leg starts by reading the "Suggested Next Legs" section and picking something that fits the "reviewable size" + "plan first" rules.**

*Created during Stage 9.0 on `feat/stage-9.0-migration-todos`. Part of the menu-driven modernization (Stage 8.x+). June 2026.*